<?php 
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link      http://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;
ob_start();
use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\Cache\Cache;
use Cake\Cache\CacheEngine;
use Cake\Cache\CacheRegistry;
use Cake\Utility\Security;
use Cake\Mailer\Email;
use Cake\Routing\Router;
use Cake\I18n\I18n;
use Cake\Network\Exception\NotFoundException;


/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link http://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{
public $helpers = ['SocialShare.SocialShare'];
    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */
    public function initialize() {
        parent::initialize();
        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');
        //$this->loadComponent('Csrf');
        $this->loadModel('Menus');
        $footerMenus = $this->Menus->find('translations', ['conditions' => ['menu_type' => 'bottom', 'status' => 1, 'menu_id IS'=>null], 'order' => ['Menus.id' => 'asc']])->toArray();
        $topMenus = $this->Menus->find('translations', ['conditions' => ['menu_type' => 'top', 'status' => 1, 'menu_id IS'=>null], 'order' => ['Menus.sort_order' => 'asc']])->toArray();
        $this->set(compact('topMenus'));
        $this->set(compact('footerMenus'));

        $this->rememberMe();

        if ($this->request->prefix == 'admin_panel') {
            $this->loadComponent('Auth', [
                'authorize' => ['Controller'], // Added this line
                'loginRedirect' => [
                    'controller' => 'Users',
                    'action' => 'dashboard',
                    'prefix' => 'admin_panel'
                ],
                'logoutRedirect' => [
                    'controller' => 'Users',
                    'action' => 'login',
                    'prefix' => 'admin_panel'
                ],
                'storage' => [
                    'className' => 'Session',
                    'key' => 'Auth.Admin',              
                ]
            ]);
        } else {
            $this->loadComponent('Auth', [
                'authorize' => ['Controller'], // Added this line
                'loginRedirect' => [
                    'controller' => 'Users',
                    'action' => 'dashboard'
                ],
                'logoutRedirect' => [
                    'controller' => 'Pages',
                    'action' => 'index'
                ]
            ]);
        }
        if( isset($this->request->params['language'])) {
           	$lang_param = $this->request->params['language'];
            switch ($lang_param) {
              	case 'en':
                	I18n::locale('en_US');   
                   	$this->request->session()->write('locale', strtolower( substr(I18n::locale(), 0, 2)) );     
                  	break;  
              	case 'dk':
                   	I18n::locale('da');      
                   	$this->request->session()->write('locale', strtolower( substr(I18n::locale(), 0, 2)) );
                  	break;  
              	default:
                   I18n::locale('en_US');   
                   $this->request->session()->write('locale', strtolower( substr(I18n::locale(), 0, 2)) );
                  	break;
            }
        }
    }

    protected function checkUnreadMessage(){
      $this->loadModel('SupportTickets');
      if(!empty($this->request->session()->read('Auth.User.id'))){
        $User = $this->SupportTickets->find()->select(['id', 'subject', 'description', 'created', 'parent_id', 'status'])->where(['SupportTickets.user_id' => $this->request->session()->read('Auth.User.id'), 'SupportTickets.status' => 1, 'SupportTickets.approved' => 1, 'SupportTickets.commented_by !=' => 2, 'SupportTickets.admin_message_read' => 1, 'SupportTickets.user_message_read' => 0])->toArray();
        $notificationArr = [];
        foreach ($User as $key => $value) {
          if(!empty($value['parent_id'])) {
            $notificationArr[$key]['support_ticket_id'] = $value['parent_id'];
          } else {
            $notificationArr[$key]['support_ticket_id'] = $value['id'];
          }
          $notificationArr[$key]['subject'] = stripslashes($value['subject']);
          $notificationArr[$key]['created'] = $value['created'];
          $notificationArr[$key]['description'] = stripslashes($value['description']);
          $notificationArr[$key]['status'] = ($value['status'] == 2) ? 'closed' : 'opened';
        }
        $this->request->session()->write('userNotification.count', count($User));
        $this->request->session()->write('userNotification.data', $notificationArr);
      }

      if(!empty($this->request->session()->read('Auth.Admin.id'))){
        $User = $this->SupportTickets->find()->where(['SupportTickets.status' => 1, 'SupportTickets.commented_by' => 2, 'SupportTickets.admin_message_read' => 0, 'SupportTickets.user_message_read' => 1])->toArray();
        $notificationArr = [];
        foreach ($User as $key => $value) {
          if(!empty($value['parent_id'])) {
            $notificationArr[$key]['support_ticket_id'] = $value['parent_id'];
          } else {
            $notificationArr[$key]['support_ticket_id'] = $value['id'];
          }
          //$notificationArr[$key]['support_ticket_id'] = $value['id'];
          $notificationArr[$key]['subject'] = stripslashes($value['subject']);
        }
        $this->request->session()->write('notification.count', count($User));
        $this->request->session()->write('notification.data', $notificationArr);
      }
    }

    /*
	   * logged in user if user has checked remember me option
     */
    function rememberMe () {
        $session = $this->request->session();
        if (!empty($session->read('selectedUser'))) {
        } elseif (!empty($_COOKIE['userDetail_id']) && !empty($_COOKIE['userDetail_username'])) {
          $this->loadModel('Users');
          $this->loadModel('Cities');
          $this->loadModel('Countries');
          $data = $this->Users->find()
                    ->contain(['Profiles', 'BirthDetails'])
                    ->where(['Users.id' => base64_decode($_COOKIE['userDetail_id']), 'Users.username' => $_COOKIE['userDetail_username']])
                    ->first();
          $city = $this->Cities->find()->where(['Cities.id' => $data['birth_detail']['city_id']])->first();
          $country = $this->Countries->find()->where(['Countries.id' => $data['birth_detail']['country_id']])->first();

          $session->write('user_id', base64_decode($_COOKIE['userDetail_id']));
          $session->write('selectedUser', base64_decode($_COOKIE['userDetail_id']));
          $session->write('Auth.City', $city);
          $session->write('Auth.Country', $country);
          $session->write('Auth.BirthDetails', $data['birth_detail']);
          $session->write('Auth.UserProfile', $data['profile']);
          unset($data['birth_detail']);
          unset($data['profile']);
          $session->write('Auth.User', $data);
          unset($data);
        }
    }


    function sendMail ($to, $subject, $message) {
        $email = new Email();
        $email->emailFormat('html')
              ->subject($subject)
              ->to($to);

        if( $email->send($message) ) {
          return true;
        } else {
          return false;
        }
    }

    function subcriptionOrderId ($lastsubidInTempSubTable, $user_id) {
        return 'Wow_SUB-'.$lastsubidInTempSubTable.'-U-'.$user_id;
    }


    protected function encrypt ( $string ) {
       
       $data = Security::encrypt($string, SECURITY_KEY);
       return $data;

    }

    protected function decrypt ( $cipher ) { 

        $data = Security::decrypt($cipher, SECURITY_KEY);
        return $data;

    }

	 public function beforeFilter(Event $event)
    {
        //if ($this->checkIp()) {
          //$this->request->session()->delete('notification');
          //if(!$this->request->session()->read('notification') && empty($this->request->session()->read('notification')) ) {
            $this->checkUnreadMessage();
          //}
        //}
        $this->Auth->allow();
        $user_id = $this->request->session()->read('user_id');
        $this->set('user_id', $user_id);
        $step = $this->request->session()->read('step');
        $this->set('step', $step);
    }
          
    public function isAuthorized($user)
    {
        // Admin can access every action
        if (isset($user['role']) && $user['role'] === 'admin') {
            return true;
        }
        // Default deny
        return false;
    }
    /**
     * Before render callback.
     *
     * @param \Cake\Event\Event $event The beforeRender event.
     * @return void
     */
    public function beforeRender(Event $event)
    {
        if (!array_key_exists('_serialize', $this->viewVars) && in_array($this->response->type(), ['application/json', 'application/xml'])        ) {
            $this->set('_serialize', true);
        }
    }

    protected function calculateSunsignFromDate($month,$day) {

        if(!empty($month) && !empty($day)) {
            if(($month==1 && $day>=20)||($month==2 && $day<=18)) {
                return "11"; // "Aquarius";
            }
            else if(($month==2 && $day>=19 )||($month==3 && $day<=20)) {
                return "12"; // "Pisces";
            }
            else if(($month==3 && $day>=21)||($month==4 && $day<=19)) {

                return "1";  // "Aries";
            }
            else if(($month==4 && $day>=20)||($month==5 && $day<=20)) {
                return "2";  // "Taurus";
            }
            else if(($month==5 && $day>=21)||($month==6 && $day<=20)) {
                return "3";  // "Gemini";
            }
            else if(($month==6 && $day>=21)||($month==7 && $day<=22)) {
                return "4";  // "Cancer";
            }
            else if(($month==7 && $day>=23)||($month==8 && $day<=22)) {
                return "5";  // "Leo";
            }
            else if(($month==8 && $day>=23)||($month==9 && $day<=22)) {
                return "6";  // "Virgo";
            }
            else if(($month==9 && $day>=23)||($month==10 && $day<=22)) {
                return "7";  // "Libra";
            }
            else if(($month==10 && $day>23)||($month==11 && $day<=21)) {
                return "8";  // "Scorpio";
            }
            else if(($month==11 && $day>=22)||($month==12 && $day<=21)) {
                return "9";  // "Sagittarius";
            }
            else if(($month==12 && $day>=22)||($month==1 && $day<=19)) {
                return "10";  // "Capricorn";
            }
        }
        else {
            return 1;
        }

    }   


    protected function getAcsatlasData($data)
                {
                    $username = 'astrowow';
                    $password = 'astrowow$123';
                    $ch = curl_init();                    // Initiate cURL
                    //$url = "54.153.95.173/acs.php"; /*"astrowow.newsoftdemo.info/acs.php";*/ // Where you want to post data
                    $url = "52.52.17.200/acs.php"; /*"astrowow.newsoftdemo.info/acs.php";*/ // Where you want to post data
                    curl_setopt($ch, CURLOPT_URL,$url);
                    curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
                    curl_setopt($ch, CURLOPT_POST, true);  // Tell cURL you want to post something
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $data); // Define what you want to post
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return the output in string format
                    $output = curl_exec ($ch); //
                    return $output;
                  
                }

/* Function to create order id */
function getOrderId( $product_id ,$order_id )
{
    return "Astro-".$product_id.'-'.$order_id;
}

function SetLatLong(&$bData) {
    /*if( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' ) {
      echo '<pre>'; print_r ($bData); die;
    }*/
    $Longitude = 0;
    $Latitude  = 0;
    if($bData['latitude'] > -90 && $bData['latitude'] < 90) {
        $Latitude = $bData['latitude'] * 3600;
    } else {
        $Latitude = $bData['latitude'];
        $bData['latitude'] = $bData['latitude'] / 3600;
    }

    if($bData['longitude'] > -180 && $bData['longitude'] < 180) {
        $Longitude = $bData['longitude'] * 3600;
    } else {
        $Longitude = $bData['longitude'];
        $bData['longitude'] = $bData['longitude'] / 3600;
    }
    

    if (isset($bData['status']) && !empty($bData['status'])) {
      $cond = ['Countries.id' => $bData['country_id'] ];
    } else {
      $cond = ['Countries.id' => $bData['birth_detail']['country_id'] ];
    }

    $birthplace = $bData['city_name'];
    if(isset($bData['popupUser']) && !empty($bData['popupUser'])) {
      $this->loadModel('Countries');
      $countryData = $this->Countries->find('all')
                    ->where($cond)
                    ->first();
    } else {
      $countryData = $this->Countries->find('all')
                    ->where([$cond])
                    //->where(['Countries.id' => $bData['birth_detail']['country_id'] ])
                    ->first();
    }
    /*$birthplace = $bData['city_name'];
    $countryData = $this->Countries->find('all')
                    ->where(['Countries.id' => $bData['birth_detail']['country_id'] ])
                    ->first();*/

    $countryAbbr = $countryData['abbr'];


    //if( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' ) {
      if (!empty($bData['place'])) {
          $placeList = $this->Cities->find()->where(['latitude'  => $Latitude, 'longitude' => $Longitude, 'city' => trim($bData['place'])])->first();
      }
      if (!empty($bData['birth_detail']['city_id'])) {
          $placeList = $this->Cities->find()->where(['latitude'  => $Latitude, 'longitude' => $Longitude, 'id' => $bData['birth_detail']['city_id']])->first();
      }
      if (!empty($bData['city_id'])) {
          $placeList = $this->Cities->find()->where(['latitude'  => $Latitude, 'longitude' => $Longitude, 'id' => $bData['city_id']])->first();
      }
      if (!empty($bData['city_name'])) {
          $placeList = $this->Cities->find()->where(['latitude'  => $Latitude, 'longitude' => $Longitude, 'city' => trim($bData['city_name'])])->first();
      }
      
      /*if ( $placeList->count()  > 0) {
          foreach($placeList  as $pItem) {*/
            if (!empty($placeList)) {
              /*$fullbirthplace = explode ( ">", $pItem->city );
              if (count ( $fullbirthplace ) > 0) {
                  $birthplace = trim ( $fullbirthplace [0] );
              } else {
                  $birthplace = trim( $pItem->city );
              }*/
              $countryDetail = $this->Countries->find('all')
                                          ->where(['Countries.id' => $placeList->country_id])
                                          ->select(['Countries.abbr', 'Countries.name'])
                                          ->first();
              $countryAbbr = $countryDetail['abbr'];
              $country_name = $countryDetail['name'];
            }
          /*}
      }*/
    /*} else {
      if (!empty($bData['place'])) {
          $placeList = $this->Cities->find('all')->where(['latitude'  => $Latitude, 'longitude' => $Longitude, 'city' => trim($bData['place'])]);
      }
      if (!empty($bData['birth_detail']['city_id'])) {
          $placeList = $this->Cities->find('all')->where(['latitude'  => $Latitude, 'longitude' => $Longitude, 'id' => $bData['birth_detail']['city_id']]);
      }
      if (!empty($bData['city_id'])) {
          $placeList = $this->Cities->find('all')->where(['latitude'  => $Latitude, 'longitude' => $Longitude, 'id' => $bData['city_id']]);
      }
      if (!empty($bData['city_name'])) {
          $placeList = $this->Cities->find('all')->where(['latitude'  => $Latitude, 'longitude' => $Longitude, 'city' => trim($bData['city_name'])]);
      }
      
      if ( $placeList->count()  > 0) {
          foreach($placeList  as $pItem) {
              $fullbirthplace = explode ( ">", $pItem->city );
              if (count ( $fullbirthplace ) > 0) {
                  $birthplace = trim ( $fullbirthplace [0] );
              } else {
                  $birthplace = trim( $pItem->city );
              }
              $countryDetail = $this->Countries->find('all')
                                          ->where(['Countries.id' => $pItem->country_id])
                                          ->select(['Countries.abbr', 'Countries.name'])
                                          ->first();
              $countryAbbr = $countryDetail['abbr'];
              $country_name = $countryDetail['name'];
          }
      }
    }*/
   
    $Location = sprintf( "%s, %s", $birthplace, $country_name);
    $IsThere = $this->GetSummerTimeZoneANDTimeZone($Location, $bData);
    /*if ($_SERVER['REMOTE_ADDR'] == '103.254.97.14') {
      echo '<pre>'; print_r($IsThere); die;
    }*/

    if(count($IsThere) > 0 ) {
        if (isset($bData['status']) && !empty($bData['status'])) {
          $bData['zone'] = $IsThere['m_timezone_offset'];
          $bData['type'] = $IsThere['m_summertime_offset'];
        } else {
          $bData['birth_detail']['zone'] = $IsThere['m_timezone_offset'];
          $bData['birth_detail']['type'] = $IsThere['m_summertime_offset'];
        }
        //pr ($bData); die;
    }
    else {

    }
}


function GetSummerTimeZoneANDTimeZone($location, $data) {
     $TimeZoneArray = array();
     //$output = $this->getAcsatlasData("action=lookup_city&country=$location");
       //      $city_info = unserialize($output);
     $this->loadModel('Cities');
     $countryInfo = explode(',',$location);
	 
	 /*$country_name =  trim($countryInfo[1]);
     $cityName     = trim($countryInfo[0]);*/
	 
	//if ( $this->checkIp() ) {
		$mydata1 = $mydata2 = '';
		if (count($countryInfo) > 2) {
			foreach ($countryInfo as $countryInfoKey => $countryInfoValue) {
				if (!$countryInfoKey) {
					$mydata1 = trim($countryInfoValue);
				} else {
					$mydata2 .= trim($countryInfoValue).', ';
				}
			}
			$mydata2 = rtrim($mydata2, ', ');
			$countryInfo[0] = $mydata1;
			$countryInfo[1] = $mydata2;
		}
		$country_name = trim($countryInfo[1]);
		$cityName = trim($countryInfo[0]);
	//} 
	
     

     $city_info = $this->Cities->find('all')
                          ->where(['country' => trim($countryInfo[1]), 'city' => trim($countryInfo[0])])
                          ->first();

          if (!$city_info) {
                 return $TimeZoneArray;
             }

     extract((array)$city_info);
     $month = $data['month'];
     $day = $data['day'];
     $year = $data['year'];
     $minutes = $data['minutes'];
     $hours = $data['hours'];
     $zonetable = $city_info['zonetable'];
     $typetable =  $city_info['typetable'];
     /*if($this->checkIp()) {
      echo $month.' => '.$day.' => '.$year.' => '.$minutes.' => '.$hours.' => '.$zonetable.' => '.$typetable; pr($data); die;
     }*/
     
     $output = $this->getAcsatlasData("action=time_change_lookup&month=$month&day=$day&year=$year&hour=$hours&minute=$minutes&zonetable=$zonetable&typetable=$typetable");
     
             $time_info = unserialize($output);
             if (!$time_info) {
                 return $TimeZoneArray;
             }    

        extract($time_info);

    if($type >= 0) {
            //Get the offset in hours from UTC
            $time_types = array(0,1,1,2); //assume $time_type < 4
            $offset = ($zone/900) - $time_types[$type];
            /*if (isset($data['status']) && !empty($data['status'])) {
                $ActualZoneValue = number_format(floatval( ($zone/900) ), 2);
                $ActualZoneValue = ( -1 * $ActualZoneValue );
            } else {*/
            $ActualZoneValue = number_format(floatval( ($zone/900) ), 2);
            
            //}
            $ZoneValue = abs( number_format(floatval( ($zone/900) ), 2) );
            $tmpZone = intval($ZoneValue);
            $tmpZoneDiff = number_format( floatval(  $ZoneValue - $tmpZone ), 2 );
            $FinalZone = $ZoneValue;
            
            if($tmpZoneDiff > 0.0 &&  $tmpZoneDiff <= 0.50 ){
                $FinalZone = number_format( floatval( $tmpZone + 0.30 ), 2);
            } else if($tmpZoneDiff >= 0.51 && $tmpZoneDiff <= 1 ){
                $FinalZone = number_format( floatval( $tmpZone + 0.45 ), 2);
            }

            if( $ActualZoneValue < 0) {
                $TimeZoneArray["m_timezone_offset"] = number_format(-1 * floatval( $FinalZone ), 2);
            } else {
                $TimeZoneArray["m_timezone_offset"] = number_format(floatval( $FinalZone ), 2);
            }
            $TimeZoneArray["m_summertime_offset"] = number_format( floatval( $time_types[$type] ), 2);

            }
    return $TimeZoneArray;
}

function generate_password( $length = 8 ) {
       $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";
       $password = substr( str_shuffle( $chars ), 0, $length );
       return $password;
    }


function getFullName($firstname, $lastname)
{
  return ucwords($firstname ." ". $lastname);
}

function getSoftwarePath($languageId, $productSeoUrl)
{
        $path = Router::url('/', true);
        switch(strtolower($productSeoUrl))
        {
             case 'horoscope-interpreter':
                               

                                switch(strtolower($languageId))
                                {

                                    case 1 : $url = $path."uploads/products/softwares/interprt.exe";
                                             break;
                                    case 2 : $url = $path."uploads/products/softwares/hi_dk_sh.exe";
                                             break;
                                    case 4 : $url = $path."uploads/products/softwares/hi_ge_sh.exe";
                                             break;
                                    case 5 : $url = $path."uploads/products/softwares/hi_sp_sh.exe";
                                             break;
                                    case 6 : $url = $path."uploads/products/softwares/hi_no_sh.exe";
                                             break;
                                    case 7 : $url = $path."uploads/products/softwares/hi_sw_sh.exe";
                                             break;
                                    case 8 : $url = $path."uploads/products/softwares/hi_br_sh.exe";
                                             break;
                                    case 9 : $url = $path."uploads/products/softwares/hi_du_sh.exe";
                                             break;
                                    default: $url = $path."uploads/products/softwares/interprt.exe";
                                             break;   
                                }
                                break;

           case 'astrology-calendar' : 
                                switch(strtolower($languageId))
                                {

                                    case 1 : $url = $path."uploads/products/softwares/setup.exe";
                                             break;
                                    case 2 : $url = $path."uploads/products/softwares/setup.exe";
                                             break;
                                    /*case 3 : $url = $path."uploads/products/softwares/Notepad++.lnk";
                                             break;
                                    case 4 : $url = $path."uploads/products/softwares/Notepad++.lnk";
                                             break;
                                    case 5 : $url = $path."uploads/products/softwares/Notepad++.lnk";
                                             break;
                                    case 6 : $url = $path."uploads/products/softwares/Notepad++.lnk";
                                             break;
                                    case 7 : $url = $path."uploads/products/softwares/Notepad++.lnk";
                                             break;
                                    case 8 : $url = $path."uploads/products/softwares/Notepad++.lnk";
                                             break;
                                    case 9 : $url = $path."uploads/products/softwares/Notepad++.lnk";
                                             break;*/
                                    //default: $url = $path."uploads/products/softwares/Notepad++.lnk";
                                    default: $url = $path."uploads/products/softwares/setup.exe";
                                             break;   
                                }
                                break;

            case 'astrology-for-lovers' : 
                                switch(strtolower($languageId))
                                {

                                    case 1 : $url = $path."uploads/products/softwares/lovers.exe";
                                             break;
                                    case 2 : $url = $path."uploads/products/softwares/afl_dksh.exe";
                                             break;
                                    case 3 : $url = $path."uploads/products/softwares/Notepad++.lnk";
                                             break;
                                    case 4 : $url = $path."uploads/products/softwares/Notepad++.lnk";
                                             break;
                                    case 5 : $url = $path."uploads/products/softwares/afl_spsh.exe";
                                             break;
                                    case 6 : $url = $path."uploads/products/softwares/Notepad++.lnk";
                                             break;
                                    case 7 : $url = $path."uploads/products/softwares/Notepad++.lnk";
                                             break;
                                    case 8 : $url = $path."uploads/products/softwares/afl_brsh.exe";
                                             break;
                                    case 9 : $url = $path."uploads/products/softwares/Notepad++.lnk";
                                             break;
                                    default: $url = $path."uploads/products/softwares/lovers.exe";
                                             break;   
                                }
                                break;
            case 'interpreter-lovers' : 

                               

                                switch(strtolower($languageId))
                                {

                                    case 1 : $url = $path."uploads/products/softwares/Notepad++.lnk";
                                             break;
                                    case 2 : $url = $path."uploads/products/softwares/Notepad++.lnk";
                                             break;
                                    case 3 : $url = $path."uploads/products/softwares/Notepad++.lnk";
                                             break;
                                    case 4 : $url = $path."uploads/products/softwares/Notepad++.lnk";
                                             break;
                                    case 5 : $url = $path."uploads/products/softwares/Notepad++.lnk";
                                             break;
                                    case 6 : $url = $path."uploads/products/softwares/Notepad++.lnk";
                                             break;
                                    case 7 : $url = $path."uploads/products/softwares/Notepad++.lnk";
                                             break;
                                    case 8 : $url = $path."uploads/products/softwares/Notepad++.lnk";
                                             break;
                                    case 9 : $url = $path."uploads/products/softwares/Notepad++.lnk";
                                             break;
                                    default: $url = $path."uploads/products/softwares/Notepad++.lnk";
                                             break;   

                                }
                                break;

           case 'interpreter-calendar' :  
                                switch(strtolower($languageId))
                                {

                                    case 1 : $url = $path."uploads/products/softwares/Notepad++.lnk";
                                             break;
                                    case 2 : $url = $path."uploads/products/softwares/Notepad++.lnk";
                                             break;
                                    case 3 : $url = $path."uploads/products/softwares/Notepad++.lnk";
                                             break;
                                    case 4 : $url = $path."uploads/products/softwares/Notepad++.lnk";
                                             break;
                                    case 5 : $url = $path."uploads/products/softwares/Notepad++.lnk";
                                             break;
                                    case 6 : $url = $path."uploads/products/softwares/Notepad++.lnk";
                                             break;
                                    case 7 : $url = $path."uploads/products/softwares/Notepad++.lnk";
                                             break;
                                    case 8 : $url = $path."uploads/products/softwares/Notepad++.lnk";
                                             break;
                                    case 9 : $url = $path."uploads/products/softwares/Notepad++.lnk";
                                             break;
                                    default: $url = $path."uploads/products/softwares/Notepad++.lnk";
                                             break;   
                                }
                                break;

            case 'calendar-lovers' : 
                                switch(strtolower($languageId))
                                {

                                    case 1 : $url = $path."uploads/products/softwares/Notepad++.lnk";
                                             break;
                                    case 2 : $url = $path."uploads/products/softwares/Notepad++.lnk";
                                             break;
                                    case 3 : $url = $path."uploads/products/softwares/Notepad++.lnk";
                                             break;
                                    case 4 : $url = $path."uploads/products/softwares/Notepad++.lnk";
                                             break;
                                    case 5 : $url = $path."uploads/products/softwares/Notepad++.lnk";
                                             break;
                                    case 6 : $url = $path."uploads/products/softwares/Notepad++.lnk";
                                             break;
                                    case 7 : $url = $path."uploads/products/softwares/Notepad++.lnk";
                                             break;
                                    case 8 : $url = $path."uploads/products/softwares/Notepad++.lnk";
                                             break;
                                    case 9 : $url = $path."uploads/products/softwares/Notepad++.lnk";
                                             break;
                                    default: $url = $path."uploads/products/softwares/Notepad++.lnk";
                                             break;   
                                }
                                break;
            case 'interpreter-calendar-lovers' : 
                                switch(strtolower($languageId))
                                {

                                    case 1 : $url = $path."uploads/products/softwares/Notepad++.lnk";
                                             break;
                                    case 2 : $url = $path."uploads/products/softwares/Notepad++.lnk";
                                             break;
                                    case 3 : $url = $path."uploads/products/softwares/Notepad++.lnk";
                                             break;
                                    case 4 : $url = $path."uploads/products/softwares/Notepad++.lnk";
                                             break;
                                    case 5 : $url = $path."uploads/products/softwares/Notepad++.lnk";
                                             break;
                                    case 6 : $url = $path."uploads/products/softwares/Notepad++.lnk";
                                             break;
                                    case 7 : $url = $path."uploads/products/softwares/Notepad++.lnk";
                                             break;
                                    case 8 : $url = $path."uploads/products/softwares/Notepad++.lnk";
                                             break;
                                    case 9 : $url = $path."uploads/products/softwares/Notepad++.lnk";
                                             break;
                                    default: $url = $path."uploads/products/softwares/Notepad++.lnk";
                                             break;   
                                }
                                break;
       }

       return $url;
    }
    
  /* This function is used to create order id for software products */
  function getOrderIdByProductType($productType)
  {
   return ucfirst(trim($productType)).'-'.time();
  } 

  function getCategoryIdBySlug($slug)
  {

    $category = $this->Categories->find('all')
                                 ->where(['slug' => $slug])
                                 ->select(['id'])
                                 ->first();
    return $category;
                                 

  } 

  /*
   This  function is used to format date to YYYY/mm/dd - DD
   Created On: 04 Jan 2017
   Created By: Stan Field
  */
   function formatDateForDb($date)
   {
       $newDate = explode('-', $date);
       if(isset($newDate[1]) && !empty($newDate[1]))
       { 
         return ( implode("/", array_reverse(explode("/", trim($newDate[0])))) .' - '. trim($newDate[1]));
       }
       else
       {
         return implode("/", array_reverse(explode("/", $newDate[0])));
       }
   }

    /*
      This  function is used to format date to YYYY-mm-dd - DD
      Created On: 04 Jan 2017
      Created By: Stan Field
   */
   function formatAdminDateForDb($date)
   {
       $newDate = explode('-', $date);
       if(isset($newDate[1]) && !empty($newDate[1]))
       { 
         return ( implode("-", array_reverse(explode("/", trim($newDate[0])))) .' - '. trim($newDate[1]));
       }
       else
       {
         return implode("-", array_reverse(explode("/", $newDate[0])));
       }
   }

   function formatDashDateForDb($date)
   {
        return implode("-", array_reverse(explode("/", $date)));
   }
   
   function changeLocale($locale = 'en')
   {
     $this->autoRender = false;
     $this->request->session()->write('locale', $locale);
     $referer = $this->referer();
    
     $site_url = Router::fullbaseUrl();
     
 
     $url = Router::url('/', true);

     if( $locale == 'dk' && $referer == $url )
     {
       $referer = $referer. $locale;
     }
     elseif( preg_match('/dk\//', $referer) == 0 && $referer == $url.'dk')
     {
        $referer = $url;
     }
     else
     {
       /* $translator = I18n::translator('default', 'en');
       echo $action_en = $translator->translate('konsultation');


        $translator = I18n::translator('default', 'da');
       echo $action_en = $translator->translate('consultation');*/

       I18n::locale('en');
       echo __('consultation');

       I18n::locale('da');
       echo __('consultation');

        $translator = ($locale == 'dk')?I18n::translator('default', 'da'):I18n::translator('default', 'en');
        pr($translator);

       

        $referer = preg_replace(
                  array('/\/en/', '/\/dk/'),
                  array('', ''),
                  $referer);

        $refererArr = explode($site_url, $referer);
        pr($refererArr);
        $action = $site_url;
        foreach($refererArr as $refer) {
          if (strlen($refer) > 0 ) {   
            echo $refer = str_replace('/', '', $refer);
            echo "<br >";
            echo $translator->translate('konsultation');
            echo $action .= '/'.$translator->translate($refer);
          }
        }  

        if( $locale == 'dk' ) {
          $referer = str_replace($site_url, $site_url.'/'.$locale, $action);
        }

        echo $referer;
     }
      die;
     $this->redirect( $referer );
   }


   function validateDataForPaymentGateway ($dataArray) {
        $checkUserId = $checkAge = 0;
        if (!empty($this->request->session()->read('Auth.User'))) {
          $checkUserId = 1;
        }
        /*if($this->checkIp()) {
          if($dataArray['product_id'] != 19) {
            $checkAge = 1;
          }
        }*/
        foreach ($dataArray as $key => $value) {
          // if($this->checkIp()) {
            if($key == 'age' && ($dataArray['product_id'] != 19)) {
              continue;
            // }
          }
          if ((($key == 'user_id') && !$checkUserId) || ($key == 'birth_time' && empty($value))) {
            continue;
          }

          if (empty(trim($value))) {
            return false;
          }
        }
        return true;
    }
/* This function returns price, vat and total price 
   Created By: Stan Field
   Created On: March 21, 2017
   Last Modified : March 21, 2017
*/
 function getVatPrice($productId, $currencyId, $productType)
 {
    $this->loadModel('ProductPrices');
    $this->loadModel('Currencies');


    $final_prices = [];
    $data = $this->ProductPrices->find()
                 ->contain( ['Currencies'] )
                 ->where( ['ProductPrices.product_id' => $productId, 'ProductPrices.currency_id' => $currencyId, 'ProductPrices.product_type_id' => $productType ] )
                 ->select( ['ProductPrices.price', 'ProductPrices.vat', 'ProductPrices.total_price', 'ProductPrices.discount_price', 'ProductPrices.discount_vat', 'ProductPrices.discount_total_price', 'Currencies.symbol'] )
                 ->first();
   
  
    if(!empty($data))
    {
        if( $data['discount_total_price'] > 0 )
        {
           $final_prices['price'] = $data['discount_price'];
           $final_prices['vat']   = $data['discount_vat']; 
           $final_prices['total_price'] = $data['discount_total_price'];      

        }
        else
        {
           $final_prices['price'] = $data['price'];
           $final_prices['vat']   = $data['vat']; 
           $final_prices['total_price'] = $data['total_price'];      
        }
           $final_prices['currency_symbol'] = $data['currency']['symbol']; 

    }

    return $final_prices;
 }




    /*
     * To fetch data from table
     * created By : Kingslay
     * Created Date : April 14, 2017
     */
    //function cityCountryData ($modelname, $id, $fields) {
    function tableData ($modelname, $id, $fields) {
      $this->loadModel('.$modelname.');
      return $required_data = $this->$modelname->find()->select([$fields])->where([$modelname.'.id' => $id])->first();
    }

    /*
     * To fetch data from orders table for birthday report
     * Created By : Kingslay
     * Created Date : April 21, 2017
     */
    function getOrderIdForBirthdayReport ($user_id) {
      $this->loadModel('Orders');
      $birthdayReportOrderId = $this->Orders->find()->select(['id'])->where(['product_id' => 23, 'user_id' => $user_id])->first();
      return $birthdayReportOrderId['id'];
    }

    function checkIp()
    {
      if( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' || $_SERVER['REMOTE_ADDR'] == '47.9.199.112' || $_SERVER['REMOTE_ADDR'] == '43.248.239.10') 
      {
         return TRUE;
      }
    }
   
}
