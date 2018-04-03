<?php

namespace App\Controller;
use Cake\Routing\Router;

use Cake\Cache\Cache;
use Cake\Cache\CacheEngine;
use Cake\Cache\CacheRegistry;
use Cake\Mailer\Email;
use Cake\I18n\I18n;
use Cake\Auth\DefaultPasswordHasher;


class SunSignsController extends AppController
{
   public function initialize()
   {
       parent::initialize();
       $this->viewBuilder()->layout('home');
       $this->loadModel('SunSignPredictions');
      
       if ($this->request->session()->read('locale') == 'en') 
       {
            I18n::locale('en_US');
       }
       elseif ($this->request->session()->read('locale') == 'da')
       {
           I18n::locale('da');   
       }
 
       // Memcache 
       if(Cache::read('sunsigns', 'long') == false)
       {
         $allsunsigns = $this->SunSigns->find('all')->toArray();
         Cache::write('sunsigns', $allsunsigns, 'long');
       }
       else
       {
          $allsunsigns = Cache::read('sunsigns', 'long');
       }
       $this->set(compact('allsunsigns'));
   }



   public function index($sunsign, $period = null) {
      if( !isset($sunsign) || empty($sunsign) ) {
        return $this->redirect([ 'controller'=> 'pages', 'action'=> 'index']);
      }
      $this->request->session()->write('popupError', false);

      $sunsigns = $this->SunSigns->find('all')->where( ['name' => $sunsign] )->first();
      
      
        $locale = substr(strtolower( I18n::locale() ), 0, 2);
        if( $locale == 'en' ) {
			$language = "en";
		} elseif( $locale == 'da') {
			$language = "dk";
		}
        $where = array();
        $sign = $sunsigns->id;
        $language = $language;
        $today = date('Y-m-d');
        $currWeek = date("Y-m-d", strtotime("last week sunday"));
        $currMonth = date('Y-m-01');
        $year = date('Y') ;
        $currYear =  date('Y-m-d', mktime(0,0,0,1,1,$year));
        if (strtolower($period) == 'daily-horoscope') {
            $scope = 1;
        } else if(strtolower($period) == 'weekly-horoscope'){
            $scope = 2;
        } else if(strtolower($period) == 'monthly-horoscope'){
            $scope = 3;
        } else if(strtolower($period) == 'yearly-horoscope'){
            $scope = 4;
        }
        if($scope == 3) {
          $schedule_date =  $currMonth;
        } elseif($scope == 4) {
          $schedule_date = $currYear;
        } elseif($scope == 2) {
          $schedule_date =  $currWeek;
        } else {
          $schedule_date = date('Y-m-d', strtotime($today));
        }
        if (isset($language) && !empty($language)) {
          $where['language'] = $language;
        }
        $where['sun_sign_id'] = $sign;
        $where['scope'] = $scope;
        $where['schedule_date'] = $schedule_date;
        
    
        $SunSignPredictions = $this->SunSignPredictions->find('all')->select(['prediction'])->where([$where])->first();
        if($this->checkIp()){
        //pr($SunSignPredictions);die;
        }
      
      if (strpos($period, '-') !== false) {
        $titlePeriod = ucwords(str_replace('-', ' ', $period));
      } else {
        if (strtolower($period) == 'archive') {
          $titlePeriod = 'Sunsigns Horoscope '.ucwords($period);
        } else {
          $titlePeriod = 'Horoscope '.ucwords($period);
        }
      }


        if(empty($this->request->session()->read('Auth.User.id'))) {
          $this->loadModel('IpAddresses');
          $ipChecker = $this->IpAddresses->find()->where(['ip' => $this->request->clientIp()])->count();
          if( (!isset($_COOKIE['showPopup']) || empty($_COOKIE['showPopup'])) && (!$ipChecker)) {
            setcookie("showPopup", 'yes', time()+3600, "/", "",  0);
          }
        }

        
        if($this->request->is('post') && !empty($this->request->data)) {
            //pr($this->request->data); die;
            $this->request->session()->write('soft-exit', $this->request->data);
            $email = trim($this->request->data['username']);
            $errorStatus = false;

            //if($this->checkIp()) {
              if (empty($this->request->data['fname']) || empty($email) || empty($this->request->data['dob'])) {
                $errorStatus = true;
                $this->request->session()->write('popupError', true);
                $this->Flash->set(__('All fields are required.'), [ 'element' => 'error' ]);
              }

              if (!empty($email)) {
                if (!filter_var($email, FILTER_VALIDATE_EMAIL) === true) {
                  $errorStatus = true;
                  $this->request->session()->write('popupError', true);
                  $fmsg = $email.', '.__('is not a valid email address');
                  $this->Flash->set($fmsg, [ 'element' => 'error' ]);

                  //$this->Flash->set(__($email.', is not a valid email address'), [ 'element' => 'error' ]);
                } else {
                  $this->loadModel('Users');
                  $count = $this->Users->find()->where(['Users.username' => $email])->count();
                  if ($count) {
                    $errorStatus = true;
                    $this->request->session()->write('popupError', true);
                    $this->Flash->set(__('This email address is already registered with us. Please login.'), [ 'element' => 'error' ]);
                  }
                }
              }

            if(!$errorStatus){
                $this->saveUserData123($this->request->data);
                $this->request->session()->write('popupUserRegistrationMsg', __('Thank you for submitting the form. Please check your email to receive your free mini report.'));
                $this->request->session()->delete('showPopup');
            }
        }
        $this->loadModel('Countries');
        $Countries = $this->Countries->find()->order(['Countries.name' => 'ASC'])->where(['Countries.status' => 1])->toArray();
        $this->set(compact('Countries'));

      $meta['title'] = ucfirst(__($sunsign)).' '.$titlePeriod." by Adrian Duncan - Astrowow.com";

      //$youtubeVideo = '';
      if (strtolower($period) == 'daily-horoscope') {
        $meta['description'] = 'Daily '.ucfirst(__($sunsign)).' horoscope by Astrowow. Get your Free daily, tomorrow, yesterday, weekly, monthly, yearly, about celebrity, characteristics and personality for '.ucfirst(__($sunsign)).' sunsigns Horoscope.';
        //$youtubeVideo = 'https://www.youtube.com/watch?v=76wipJ7Wk_U';
      } elseif (strtolower($period) == 'weekly-horoscope') {
        $meta['description'] = 'Weekly '.ucfirst(__($sunsign)).' sunsigns horoscope by Astrowow. Get your Free daily, weekly,next week, previous week, monthly, yearly, about celebrity, characteristics and personality for '.ucfirst(__($sunsign)).' Horoscope.';
        $youtubeVideo = 'https://www.youtube.com/watch?v=4ImKjNWj6xE';
      } elseif (strtolower($period) == 'monthly-horoscope') {
        $meta['description'] = 'Monthly '.ucfirst(__($sunsign)).' sunsigns horoscope by Astrowow. Get your Free daily, weekly, monthly, previous month, next month, yearly, about celebrity, characteristics and personality for '.ucfirst(__($sunsign)).' Horoscope.';
        $youtubeVideo = 'https://www.youtube.com/watch?v=SqUg3mrPLLY';
      } elseif (strtolower($period) == 'yearly-horoscope') {
        $meta['description'] = 'Yearly '.ucfirst(__($sunsign)).' sunsigns horoscope by Astrowow. Get your Free daily, weekly, monthly, yearly, previous year, next year, about celebrity,characteristics and personality for '.ucfirst(__($sunsign)).' Horoscope.';
        $youtubeVideo = 'https://www.youtube.com/watch?v=ysW3vQdwqdw';
      } elseif (strtolower($period) == 'characteristics') {
        $meta['description'] = ucfirst(__($sunsign)).' characteristics sunsigns horoscope by Astrowow. Get your Free daily, weekly, monthly, yearly, about celebrity, male characteristics, and female characteristics for '.ucfirst(__($sunsign)).' Horoscope.';
      } elseif (strtolower($period) == 'celebrity') {
        $meta['description'] = ucfirst(__($sunsign)).' celebrity sunsigns horoscope by Astrowow. Get your Free daily, weekly, monthly, yearly, about celebrity, and characteristics for '.ucfirst(__($sunsign)).' Horoscope.';
      } elseif (strtolower($period) == 'archive') {
        $meta['description'] = ucfirst(__($sunsign)).' horoscope archive by Astrowow. Get your Free daily, weekly, monthly, yearly, about celebrity, characteristics, archive prediction on basis of scope and date for '.ucfirst(__($sunsign)).' sunsigns. ';
      }
      //$this->set(compact('youtubeVideo'));

      $meta['keywords'] = __($sunsign).', free horoscopes, sun sign, '.__($sunsign).' horoscope, '.__($sunsign).' zodiac, characteristics of aries, taurus, gemini, cancer, leo, virgo, libra, scorpio, sagittarius, capricorn, aquarius, pisces';

        $sunSignArr = ['aries' => 'vædder', 'taurus' => 'tyr', 'gemini' => 'tvilling', 'cancer' => 'krebs', 'leo' => 'løve', 'virgo' => 'jomfru', 'libra' => 'vægt', 'scorpio' => 'skorpion', 'sagittarius' => 'skytte', 'capricorn' => 'stenbuk', 'aquarius' => 'vandbærer', 'pisces' => 'fisk'];
        $periodTabArr = ['daily-horoscope' => 'daglig-horoskop', 'weekly-horoscope' => 'ugentlig-horoskop', 'monthly-horoscope' => 'månedligt-horoskop', 'yearly-horoscope' => 'årlig-horoskop', 'characteristics' => 'egenskaber', 'celebrity' => 'berømthed', 'archive' => 'arkiv'];

        if (!empty($period)) {
          $anchorEn = '';
          //if( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' ) {
            if (strpos($period, '-') !== false) {
              $periodArr = explode('-', $period);
              $periodArrFirstPos = (strtolower(trim($periodArr[0])) == 'daily') ? 'today' : strtolower(trim($periodArr[0]));
              $anchorEn = $periodArrFirstPos.'-tab';
            } else {
              if ($period == 'archive') {
                $anchorEn = $period;
              } else {
                $anchorEn = $period.'-tab';
              }
            }
          //}
          $anchorDa = __d('default', $anchorEn);
        } else {
          $anchorEn =  'today-tab';
          $anchorDa = __d('default', $anchorEn);
        }
        if (array_key_exists(strtolower($sunsign), $sunSignArr)) {
            $sunsignDa = $sunSignArr[strtolower($sunsign)];
        } else {
            $sunsignDa = __d('default', strtolower($sunsign) );
        }
        if (array_key_exists(strtolower($period), $periodTabArr)) {
            $periodDa = $periodTabArr[strtolower($period)];
        } else {
            $periodDa = __d('default', strtolower($period) );
        }
        
        if (strpos($period, '-') !== false) {
          $canonical['en'] = SITE_URL.'sun-signs/'.strtolower($sunsign).'/'.strtolower($period)/*.'#'.$anchorEn*/;
          $canonical['da'] = SITE_URL.'dk/sol-skilte/'.$sunsignDa.'/'. $periodDa/*__d('default', strtolower($period) )*//*.'#'.$anchorDa*/;
        } else {
          if ($period == 'archive') {
            $canonical['en'] = SITE_URL.'sun-signs/'.strtolower($sunsign).'/'.strtolower($period);
            $canonical['da'] = SITE_URL.'dk/sol-skilte/'.$sunsignDa.'/'. $periodDa/*__d('default', strtolower($period) )*/;
          } else {
            $canonical['en'] = SITE_URL.'sun-signs/'.strtolower($sunsign).'/'.strtolower($period)/*.'#'.$anchorEn*/;
            $canonical['da'] = SITE_URL.'dk/sol-skilte/'.$sunsignDa.'/'. $periodDa/*__d('default', strtolower($period) )*//*.'#'.$anchorDa*/;
          }
        }

        $additionalText = '';
        if (strpos($period, '-') !== false) {
          $additionalText = ucwords(str_replace('-', ' ', $period));
        } else {
          $additionalText = ucwords($period.' horoscope');
        }

        $this->set(compact('canonical', 'meta', 'sunsigns', 'additionalText','SunSignPredictions'));
   }


   protected function saveUserData123($data) {
    //pr($data); die;
    $entity = $this->Users->newEntity();
    $entity['username'] = $data['username'];
    $psw = $entity['password'] = $this->generate_password();
    //$entity['status'] = 1;
    $entity['step'] = 2;
    //$entity['is_guest'] = 0;
    //$entity['self_signup'] = 1;
    //$entity['portal_id'] = 2;
    $entity['preview_report'] = '';
    $entity['popup_user'] = 1;

    if($user = $this->Users->save($entity)) {
      $this->loadModel('BirthDetails');
      $birthDetailsData = $this->BirthDetails->newEntity();
      $birthDetailsData['user_id'] = $user->id;
      $birthDetailsData['country_id'] = 33; //$data['country'];
      $birthDetailsData['city_id'] = 95142; //$data['city'];
      $dob = explode('/', $data['dob']);
      $birthDetailsData['day'] = sprintf("%02d", $dob[0]);
      $birthDetailsData['month'] = sprintf("%02d", $dob[1]);
      $birthDetailsData['year'] = $dob[2];
      $dob = $dob[2].'-'.$dob[1].'-'.$dob[0];
      $birthDetailsData['hours'] = 12; //sprintf("%02d", $data['birthhour']);
      $birthDetailsData['minutes'] = 00; //sprintf("%02d", $data['birthminute']);
      $birthDetailsData['date'] = $dob;
      $birthDetailsData['time'] = $birthDetailsData['hours'].':'.$birthDetailsData['minutes']; //sprintf("%02d", $data['birthhour']).':'.sprintf("%02d", $data['birthminute']);
      $birthDetailsData['sun_sign_id'] = $this->calculateSunsignFromDate( $dob[1], $dob[0]);
      $birthDetailsData['status'] = 'yes';
      $this->loadModel('Cities');
      $cityName = $this->Cities->find()->where(['Cities.id' => $birthDetailsData['city_id']])->select(['city', 'latitude', 'longitude'])->first();
      $birthDetailsData['status'] = 'yes';
      $birthDetailsData['popupUser'] = 'yes';
      $birthDetailsData['city_name'] = $cityName['city'];
      $birthDetailsData['latitude'] = $cityName['latitude'];
      $birthDetailsData['longitude'] = $cityName['longitude'];
      $this->SetLatLong($birthDetailsData);
      $birthDetailsData['day'] = date('l', strtotime($dob));

      if($this->BirthDetails->save($birthDetailsData)){
        $this->loadModel('Profiles');
        $profileData = $this->Profiles->newEntity();
        $profileData['user_id'] = $user->id;
        $profileData['first_name'] = $data['fname'];
        //$profileData['last_name'] = $data['lname'];
        //$profileData['gender'] = $data['gender'];
        $profileData['language_id'] = (!empty($this->request->session()->read('locale')) && ($this->request->session()->read('locale') == 'da')) ? 2 : 1;
        if($this->Profiles->save($profileData)) {
          $this->loadModel('IpAddresses');
          $ipAddressesEntity = $this->IpAddresses->newEntity();
          $ipAddressesEntity['user_id'] = $user->id;
          $ipAddressesEntity['ip'] = $this->request->clientIp();
          $ipAddressesEntity['created'] = time();
          $ipAddressesEntity['end_date'] = strtotime("+30 days");
          if($this->IpAddresses->save($ipAddressesEntity)) {



            /* Sending Registration Email */
            $recipient = $data['username'];
            $fullname = ucwords($data['fname']);
            $data = [
                      'subject' => __("Welcome to Astrowow.com‏"),
                      'mailtext' => "<h1>Hello $fullname,</h1>",
                      'password' => $psw,
                      'username' => $data['username'],
                      'name' => $fullname
                    ];
            $emailTemplate = new EmailTemplatesController();
            $send =  $emailTemplate->sendWelcomeEmailOnSignup($recipient, $data);





            $this->request->session()->delete('soft-exit');
            $this->request->session()->delete('popupError');
            return true;
          }
        }
        return false;
      }
      return false;
      /*$this->loadModel('Profiles');
      $profileData = $this->Profiles->newEntity();
      $profileData['user_id'] = 13630;
      $profileData['first_name'] = $data['fname'];
      $profileData['last_name'] = $data['lname'];
      $profileData['language_id'] = (!empty($this->request->session()->read('locale')) && ($this->request->session()->read('locale') == 'da')) ? 2 : 1;*/
      //pr($profileData); die;
    }
    return false;

    /*echo 'here';
    pr($data); die;*/
  }

  protected function saveUserData($data) {
    $entity = $this->Users->newEntity();
    $entity['username'] = $data['username'];
    $psw = $entity['password'] = $this->generate_password();
    $entity['status'] = 1;
    $entity['step'] = 2;
    $entity['is_guest'] = 0;
    $entity['self_signup'] = 1;
    $entity['portal_id'] = 2;
    $entity['preview_report'] = '';
    $entity['self_signup'] = 1;

    if($user = $this->Users->save($entity)) {
      $this->loadModel('BirthDetails');
      $birthDetailsData = $this->BirthDetails->newEntity();
      $birthDetailsData['user_id'] = $user->id;
      $birthDetailsData['country_id'] = $data['country'];
      $birthDetailsData['city_id'] = $data['city'];
      $dob = explode('/', $data['dob']);
      $birthDetailsData['day'] = sprintf("%02d", $dob[0]);
      $birthDetailsData['month'] = sprintf("%02d", $dob[1]);
      $birthDetailsData['year'] = $dob[2];
      $dob = $dob[2].'-'.$dob[1].'-'.$dob[0];
      $birthDetailsData['minutes'] = sprintf("%02d", $data['birthminute']);
      $birthDetailsData['hours'] = sprintf("%02d", $data['birthhour']);
      $birthDetailsData['date'] = $dob;
      $birthDetailsData['time'] = sprintf("%02d", $data['birthhour']).':'.sprintf("%02d", $data['birthminute']);
      $birthDetailsData['sun_sign_id'] = $this->calculateSunsignFromDate( $dob[1], $dob[0]);
      $birthDetailsData['status'] = 'yes';
      $this->loadModel('Cities');
      $cityName = $this->Cities->find()->where(['Cities.id' => $data['city']])->select(['city', 'latitude', 'longitude'])->first();
      $birthDetailsData['status'] = 'yes';
      $birthDetailsData['popupUser'] = 'yes';
      $birthDetailsData['city_name'] = $cityName['city'];
      $birthDetailsData['latitude'] = $cityName['latitude'];
      $birthDetailsData['longitude'] = $cityName['longitude'];
      $this->SetLatLong($birthDetailsData);
      $birthDetailsData['day'] = date('l', strtotime($dob));

      if($this->BirthDetails->save($birthDetailsData)){
        $this->loadModel('Profiles');
        $profileData = $this->Profiles->newEntity();
        $profileData['user_id'] = $user->id;
        $profileData['first_name'] = $data['fname'];
        $profileData['last_name'] = $data['lname'];
        $profileData['gender'] = $data['gender'];
        $profileData['language_id'] = (!empty($this->request->session()->read('locale')) && ($this->request->session()->read('locale') == 'da')) ? 2 : 1;
        if($this->Profiles->save($profileData)) {
          $this->loadModel('IpAddresses');
          $ipAddressesEntity = $this->IpAddresses->newEntity();
          $ipAddressesEntity['user_id'] = $user->id;
          $ipAddressesEntity['ip'] = $this->request->clientIp();
          $ipAddressesEntity['created'] = time();
          $ipAddressesEntity['end_date'] = strtotime("+30 days");
          if($this->IpAddresses->save($ipAddressesEntity)) {




            /* Sending Registration Email */
            $recipient = $data['username'];
            $fullname = ucwords($data['fname'].' '.$data['lname']);
            $data = [
                      'subject' => "Welcome to Astrowow.com‏",
                      'mailtext' => "<h1>Hello $fullname,</h1>",
                      'password' => $psw,
                      'username' => $data['username'],
                      'name' => $fullname
                    ];
            $emailTemplate = new EmailTemplatesController();
            $send =  $emailTemplate->sendWelcomeEmailOnSignup($recipient, $data);





            $this->request->session()->delete('soft-exit');
            $this->request->session()->delete('popupError');
            return true;
          }
        }
        return false;
      }
      return false;
      /*$this->loadModel('Profiles');
      $profileData = $this->Profiles->newEntity();
      $profileData['user_id'] = 13630;
      $profileData['first_name'] = $data['fname'];
      $profileData['last_name'] = $data['lname'];
      $profileData['language_id'] = (!empty($this->request->session()->read('locale')) && ($this->request->session()->read('locale') == 'da')) ? 2 : 1;*/
      //pr($profileData); die;
    }
    return false;

    /*echo 'here';
    pr($data); die;*/
  }


  public function getPrediction(){
    $this->viewBuilder()->layout('ajax');
    $this->autoRender = false;
    if($this->request->is('ajax')) {
      $this->getPredictionData();
    }
  }

  public function getPredictionData(){
    $this->autoRender = false;
    $where = array();
    $sign = $_REQUEST['sign'];
    $language = $_REQUEST['language'];
    $scope = $_REQUEST['scope'];
    /* This is to modify date format*/
    // $_REQUEST['date'] = implode("-", array_reverse(explode("-", $_REQUEST['date'])));
    /*switch ($scope) {
      case '1':
        $schedule_date = date('Y-m-d', strtotime($_REQUEST['date']));
        // if( $_REQUEST['scope'] == 1) {
        //   $schedule_date = $_REQUEST['date'];
        // } else {
        //   $schedule_date = $_REQUEST['date'];
        //   $schedule_date = date('Y-m-d', strtotime($schedule_date));
        // }
        break;
      case '2':
        $schedule_date =  date('Y-m-d', strtotime('last Sunday', strtotime($_REQUEST['date'])));
        // if( $_REQUEST['scope'] == 2) {
        //   $schedule_date = $_REQUEST['date'];
        // } else {
        //   $schedule_date = $_REQUEST['date'];
        //   $schedule_date =  date('Y-m-d', strtotime('last Sunday', strtotime($schedule_date)));
        // }
        break;
      case '3':  
        $schedule_date =  date('Y-m-01', (strtotime($_REQUEST['date'])));
        // if( $_REQUEST['scope'] == 3) {
        //   $schedule_date = $_REQUEST['date'];
        // } else {
        //   $schedule_date = $_REQUEST['date'];
        //   $schedule_date =  date('Y-m-01', (strtotime($schedule_date)));
        // }
        break;
      case '4':
        $schedule_date =  date('Y-01-01', (strtotime($_REQUEST['date'])));
        // if( $_REQUEST['scope'] == 4) {
        //   $schedule_date = $_REQUEST['date'];
        // } else {
        //   $schedule_date = $_REQUEST['date'];
        //   $schedule_date =  date('Y-01-01', (strtotime($schedule_date)));
        // }
        break;
    }*/
    
    if($scope == 3) {
      $schedule_date =  date('Y-m-01', (strtotime($_REQUEST['date'])));
    } elseif($scope == 4) {
      $schedule_date =  date('Y-01-01', (strtotime($_REQUEST['date'])));
    } else {
      $schedule_date = date('Y-m-d', strtotime($_REQUEST['date']));
    }

    /*if($this->checkIp()){
      echo $schedule_date; die;
    }*/

    if(isset($_REQUEST['language'])){
      $language = $_REQUEST['language'];
    } else {
        $language = 'en';
    }
    if (isset($sign) && !empty($sign)){
      $where['sun_sign_id'] =$sign;
    }
    if (isset($language) && !empty($language)) {
      $where['language'] = $language;
    }
    $where['scope'] = $scope;
    $where['schedule_date'] = $schedule_date;

    $result = $this->SunSignPredictions->find('all')->select(['prediction'])->where([$where])->first();
    /*if($scope == 4) {
      $result['prediction'] = utf8_decode($result['prediction']);
    }*/
    if(!empty($result)) {
      //$result['encryption_type'] = utf8_decode($result['prediction']); //mb_detect_encoding($result['prediction']);
      if (mb_detect_encoding(utf8_decode($result['prediction']) , 'UTF-8', true) === 'UTF-8') {
        /*$result['type'] = mb_detect_encoding(utf8_decode($result['prediction']) , 'UTF-8', true);
        $result['prediction'] = $result['prediction'];
      } else {
        $result['type'] = mb_detect_encoding(utf8_decode($result['prediction']) , 'UTF-8', true);*/
        $result['prediction'] = utf8_decode($result['prediction']);
      }
      echo json_encode($result);
    } else {
      if($language == 'en'){
        $result['prediction'] = 'We are working hard to bring you the very best in astrological content, please come back later.</br></br>';
      } elseif($language == 'dk') {
        $result['prediction'] = 'Vi arbejder på at give dig det allerbedste astrologiske indhold, prøv igen senere.</br></br>';
      }
      echo json_encode($result);
    }
    exit();
  }

    public function freeHoroscope()
    {
        $locale = strtolower( substr(I18n::locale(), 0, 2) );
        //$meta['title'] 			= __('Free Horoscope : Daily, Weekly & Monthly Free Horoscopes'); //'Horoscope: Daily, weekly and Monthly free horoscpes';
        $meta['title'] 			= __('Free Horoscope : Daily, Weekly & Monthly Horoscopes, Characteristics');
		
        //$meta['description'] 	= __('Get free horoscope daily, weekly and monthly. Astrowow.com provides free daily, weekly, monthly and yearly horoscopes for each sun sign'); //'Astrowow.com provides free daily, weekly, monthly and yearly horoscopes for each sun sign';
        $meta['description'] 	= __('Weekly free horoscope from Astrowow - We offer a daily, weekly, monthly and yearly horoscopes, Zodiac signs characteristics, celebrities and more absolutely free.');
		
        $meta['keywords'] 		= 'Free horoscopes, Daily horoscope, weekly horoscope, monthly horoscope, horoscope 2013, Aries, taurus, gemini, cancer, leo, virgo, libra, scorpio, sagittarius, capricorn, aquarius, pisces, sun sign';
		
        $entity 			= $this->SunSignPredictions->newEntity();
       
        $canonical['en'] = SITE_URL.'sun-signs/free-horoscope';
        $canonical['da'] = SITE_URL.'dk/sol-skilte/gratis-horoskop';
       
        $this->set(compact('canonical', 'meta', 'entity'));
        /*$this->set(compact('meta'));
        $this->set(compact('entity'));*/
    }



}

?>
