<?php

namespace App\Controller;
use Cake\Event\Event;
use Cake\Routing\Router;
use Cake\Cache\Cache;
use Cake\Cache\CacheEngine;
use Cake\Cache\CacheRegistry;
use Cake\Mailer\Email;
use Cake\Utility\Security;
use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\TableRegistry;
use Cake\I18n\I18n;


class UsersController extends AppController {
    public  $paginate = [ 'limit' => 5 ];

    public function initialize() {
        parent::initialize();
        $this->loadModel('Languages');
        $this->loadModel('Countries');
        $this->loadModel('Menus');
        $this->loadModel('Cities');
        $this->loadModel('Profiles');
        $this->loadModel('BirthDetails');
        $this->loadModel('Subscriptions');
        $this->loadModel('Events');
        $this->loadModel('Subscribes');
        $this->loadModel('Orders');
        $this->loadComponent('Paginator');

        $this->Menus->recover();

        $user_id = $this->request->session()->read('user_id');
        $this->set('user_id', $user_id);
        $step = $this->request->session()->read('step');
        $this->set('step', $step);
        $this->loadModel('AnotherPersons');
        $this->viewBuilder()->layout('home');
    }

    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
        /*pr ($this->request->session()->read()); die;
        if ($this->request->session()->read('locale') == 'en') {
            I18n::locale('en_US');
        } elseif ($this->request->session()->read('locale') == 'da') {
            I18n::locale('da');
        }*/
        $this->Auth->allow();
        if (in_array($this->request->action, ['cities', 'add-another-person', 'get-county-cities'])) {
            $this->eventManager()->off($this->Csrf);
        }
    }


    public function index() {
        $this->redirect([ 'action' => 'login']);
    }

    public function signUp() {
        $canonical['en'] = SITE_URL.'users/sign-up';
        $canonical['da'] = SITE_URL.'dk/brugere/tilmeld-dig';
        $meta['title'] = 'Astrowow.com - Astrology, free horoscopes, sun sign, Astrology reading, Astrology software';
        $meta['description'] = 'Astrowow.com provides free horoscopes, Astrology reading, Astrologer, Astrology Software, Astrology prediction, Daily, weekly, monthly horoscopes for aries, taurus, gemini, cancer, leo, virgo, libra, scorpio, sagittarius, capricorn, aquarius, pisces, sun sign';
        $meta['keywords'] = 'Free Astrology, Horoscope, Horoscope Reading,  Aries, taurus, gemini, cancer, leo, virgo, libra, scorpio, sagittarius, capricorn, aquarius, pisces,  daily horoscope, weekly horoscope, monthly horoscope,online astrology';
        $this->set(compact('canonical', 'meta'));
        if($this->request->session()->read('user_id')) {
            $this->redirect([ 'action' => 'dashboard']);
        }
        $entity = $this->Users->newEntity();
        $languageOptions = $this->Languages->find('list', ['conditions' => ['status'=>1, 'language_category' => 2]])->toArray();
        if($this->request->is('post') && isset($this->request->data['usrname']) && isset($this->request->data['fname']) && isset($this->request->data['lname']) && isset($this->request->data['birth_date'])):
              /*
               * Allow a valid email id if anyone(user) has been remove JS validations in frontend
               * Created By : Kingslay
               * Created Date : April 11, 2017
              */
              if (!filter_var($this->request->data['usrname'], FILTER_VALIDATE_EMAIL)) {
                $this->Flash->error(__('Please enter valid email address'));
                return $this->redirect([ 'controller' => 'users', 'action' => 'sign-up']);
              }
              $this->request->session()->write('fname' , $this->request->data['fname']);
              $this->request->session()->write('lname' , $this->request->data['lname']);
              $this->request->session()->write('dob' , $this->request->data['birth_date']);
              $entity['username'] = $this->request->data['usrname'] ;
        elseif($this->request->is('post') && isset($this->request->data['uname'])):
              /*
               * Allow a valid email id if anyone(user) has been remove JS validations in frontend
               * Created By : Kingslay
               * Created Date : April 11, 2017
              */
              if (!filter_var($this->request->data['uname'], FILTER_VALIDATE_EMAIL)) {
                $this->Flash->error(__('Please enter valid email address'));
                return $this->redirect([ 'controller' => 'users', 'action' => 'sign-up']);
              }
              $entity['username'] = $this->request->data['uname'];
        elseif ($this->request->is('post')) :
              //if ($_SERVER['REMOTE_ADDR'] == '103.254.97.14') {
                /*
                 * Allow a valid email id if anyone(user) has been remove JS validations in frontend
                 * Created By : Kingslay
                 * Created Date : April 11, 2017
                */
                if (!filter_var($this->request->data['username'], FILTER_VALIDATE_EMAIL)) {
                  $this->Flash->error(__('Please enter valid email address'));
                  return $this->redirect([ 'controller' => 'users', 'action' => 'sign-up']);
                }
              //}
              $find=$this->Users->find('all')
                                 ->where(['username' => $this->request->data['username']]);
              if($find->isEmpty()):
                  $this->request->data['profile']['first_name'] = ucwords($this->request->data['profile']['first_name']);
                  $this->request->data['profile']['last_name'] = ucwords($this->request->data['profile']['last_name']);
                  $data = $this->Users->patchEntity($entity, $this->request->data, ['associated' => ['Profiles']] );
                  $this->request->session()->write('first_name', $this->request->data['profile']['first_name']);
                  $this->request->session()->write('last_name', $this->request->data['profile']['last_name']);
                  $fullname = $this->request->data['profile']['first_name']." ".$this->request->data['profile']['last_name'];

                    if($this->request->data['profile']['language_id'] == ENGLISH)
                    {
                       I18n::locale('en_US');
                       $this->request->session()->write('locale', 'en_US');

                    }
                    elseif($this->request->data['profile']['language_id'] == DANISH)
                    {
                       I18n::locale('da');
                       $this->request->session()->write('locale', 'da');

                    }
                    
                  if ($result = $this->Users->save($data)) {
                      $lastInsertedUserId = $result->id;
                      $userDetail = $this->Users->find()->where(['id' => $lastInsertedUserId])->first();
                      $userProfileDetail = $this->Profiles->find()->where(['user_id' => $lastInsertedUserId])->first();
                      $this->request->session()->write('Auth.User', $userDetail);
                      $this->request->session()->write('Auth.UserProfile', $userProfileDetail);

                      $this->request->session()->write('user_id', $data['id']);
                      $this->request->session()->write('step', '1');

                      /* Sending Registration Email */
                      $recipient = $this->request->data['username'];
                      $data = [
                                'subject' => "Welcome to Astrowow.com‏",
                                'mailtext' => "<h1>Hello $fullname,</h1>",
                                'password' => $this->request->data['password'],
                                'username' => $this->request->data['username'],
                                'name' => $fullname
                              ];
                      $emailTemplate = new EmailTemplatesController();
                      $send =  $emailTemplate->sendWelcomeEmailOnSignup($recipient, $data);
                      $this->request->session()->delete('fname');
                      $this->request->session()->delete('lname');

                      if(strtolower( substr(I18n::locale(), 0 , 2) ) == 'en')
                      {
                       return $this->redirect(['controller' => 'Users', 'action' => 'sign-up-step-two']);
                      }
                      elseif(strtolower( substr(I18n::locale(), 0 , 2) ) == 'da')
                      {
                        return $this->redirect(['language' => 'dk', 'controller' => 'Users', 'action' => 'sign-up-step-two']); 
                      }
                  }
                  $this->Flash->error(__('Unable to save data. Please enter all the details (login/profile/birth) before submitting.'));
              else:
                  $this->Flash->error(__('You are already a registered user. Please login to continue'));
                  return $this->redirect(['action' => 'login']);
              endif;
        endif;
        $this->set('form', $entity);
        $this->set('languageOptions', $languageOptions);
    }

    /*
     * used to communicate data properly with old website data (password) and manipulate a/c to cakephp
     * Created By : Kingslay
     * Created Date : March 28, 2017
     */
    protected function userStatus ($data) {
      $check = $this->Users->find()->where(['username' => trim($data['username']), 'old_password' => Security::hash(trim($data['password']), 'md5', false), 'flag' => 1])->first();
      if ($check) {
          $hasher = new DefaultPasswordHasher();
          $password = $hasher->hash(trim($data['password']));
          $query = $this->Users->query();
          $updateData = $query->update()
                        ->set(['password' => $password, 'flag' => 0, 'old_password' => ''])
                        ->where(['id' => $check['id']])
                        ->execute();
      }
      return true;
    }

    public function login(/*$userId = null*/) {
        $canonical['en'] = SITE_URL.'users/login';
        $canonical['da'] = SITE_URL.'dk/brugere/logpå';
        $meta['title'] = 'Astrowow.com - Astrology, free horoscopes, sun sign, Astrology reading, Astrology software';
        $meta['description'] = 'Astrowow.com provides free horoscopes, Astrology reading, Astrologer, Astrology Software, Astrology prediction, Daily, weekly, monthly horoscopes for aries, taurus, gemini, cancer, leo, virgo, libra, scorpio, sagittarius, capricorn, aquarius, pisces, sun sign';
        $meta['keywords'] = 'Free Astrology, Horoscope, Horoscope Reading,  Aries, taurus, gemini, cancer, leo, virgo, libra, scorpio, sagittarius, capricorn, aquarius, pisces,  daily horoscope, weekly horoscope, monthly horoscope,online astrology';
        $this->set(compact('canonical', 'meta'));

        $subscriptionUrlRe = !empty($this->request->session()->read('SubscriptionUrl')) ? $this->request->session()->read('SubscriptionUrl') : '';
        if($this->request->session()->read('user_id'))
        {
            $this->redirect([ 'action' => 'dashboard']);
        }

        if ( $this->request->is('post') || !empty ($userId) ) {
            /*
             * used to communicate data properly with old website data (password) and manipulate a/c to cakephp
             * Created By : Kingslay
             * Created Date : March 28, 2017
             */
            /*if (!empty ($userId)) {
              $user = $this->Users->findById($userId)->toArray();
            } else {*/
              $checkUserDetail = $this->userStatus ($this->request->data());
              $user = $this->Auth->identify();
            //}
            if($user['role'] == 'admin') {
              $this->redirect(['action' => 'logout']);
            }
            if ($user) {
              
                //$this->inserttransit (); // to insert transits for perticular user
                $session = $this->request->session();
                
                // Prevent to login, deleted users by Kingslay <kingslay@123789.org>
                if ($user['is_delete']) {
                    $session->destroy();
                    $this->Flash->error(__("Your account has been deleted. In order to recover your account, contact the Admin (support@astrowow.com)"), ['escape' => false]);
                    return $this->redirect(['controller' => 'users', 'action' => 'login']);
                }
                $data = $this->Users->get($user['id'], ['contain' => 'Profiles'])->toArray();
                $this->request->session()->write('name', $data['profile']['first_name'] .' '.$data['profile']['last_name']);
                $this->request->session()->write('user_id', $data['id']);
                
                // Setting cookie for blog page
                setcookie("user_id", $data['id'], time()+3600, "/", "",  0);


                $userProfile = $this->Profiles->find()->where(['user_id' => $user['id']])->first();
                $session->write('Auth.UserProfile', $userProfile);

                $BirthDetails = $this->BirthDetails->find()->where(['user_id' => $user['id']])->first();
                $session->write('Auth.BirthDetails', $BirthDetails);
                $session->write('selectedUser', $data['id']);

                $Countries = $this->Countries->find()->where(['id' => $BirthDetails['country_id'], 'Countries.status' => 1])->first();
                $session->write('Auth.Country', $Countries);

                $Cities = $this->Cities->find()->where(['id' => $BirthDetails['city_id']])->first();
                $session->write('Auth.City', $Cities);
                $this->Auth->setUser($user);
                
                // Conditions for Product Section 
                // if($this->request->session()->read('Order') && $this->request->session()->read('user_id') && $user['step'] == 2) {
                //   $orderData = $this->request->session()->read('Order');
                //   $this->request->session()->write('first_name',  $data['profile']['first_name']);
                //   $this->request->session()->write('last_name', $data['profile']['last_name']);
                //   return $this->redirect($orderData['url'] );
                // }
                 if( $this->request->session()->read('user_id') && $user['step'] == 2 && $this->request->session()->read('Order')) 
                 {
                       $orderData = $this->request->session()->read('Order');
                       $this->request->session()->write('first_name',  $data['profile']['first_name']);
                       $this->request->session()->write('last_name', $data['profile']['last_name']);
                       return $this->redirect($orderData['url'] );
                 }
                elseif($this->request->session()->read('user_id') && $user['step'] == 2 && $this->request->session()->read('SOrder'))
                 {
                       $orderData = $this->request->session()->read('SOrder');
                       $this->request->session()->write('first_name',  $data['profile']['first_name']);
                       $this->request->session()->write('last_name', $data['profile']['last_name']);
                       return $this->redirect($orderData['url'] );
                 }
                 elseif( $user['step'] == 1 ) {
                    $this->redirect(['action' => 'sign-up-step-two']);
                } elseif (!empty($subscriptionUrlRe)) { //SubscriptionUrl
                  return $this->redirect($session->read('SubscriptionUrl'));
                } else {
                    $this->request->session()->write('step', '2');
                    if (strtolower($user['role']) == 'elite') {
                      $today = time();
                      $this->loadModel('EliteMembers');
                      $checkEliteMemberValidity = $this->EliteMembers->find()->where(['user_id' => $user['id'], 'start_date <= ' => $today, 'end_date >= ' => $today])->first();
                      
                      if (!empty($checkEliteMemberValidity)) { // valid elite member
                        $this->redirect(['controller' => 'elite-users', 'action' => 'dashboard']);
                      } else { // elite member subscription expire or normal user 
                          $update = $this->Users->updateAll (['role' => 'user'], ['id' => $user['id']]);
                          $this->request->session()->write ('Auth.User.role', 'user');
                          return $this->redirect($this->Auth->redirectUrl());
                      }
                    } else {
                      return $this->redirect($this->Auth->redirectUrl());
                    }
                }
            } else {
              $this->Flash->error(__('Invalid username or password, please try again'));
              $this->redirect(['controller' => 'Users', 'action' => 'login']);
            }
        }
    }

     /**
     * This function is used to store birth details 
     * Created By : Sehdev Singh
     * Last Modified : Nov. 15, 2016
     */
    public function signUpStepTwo(){
        $canonical['en'] = SITE_URL.'users/sign-up-step-two';
        $canonical['da'] = SITE_URL.'dk/brugere/tilmeld-dig-trin-to';
        if( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' ) {
          $meta['title'] = 'Astrowow.com - Astrology, free horoscopes, sun sign, Astrology reading, Astrology software';
          $meta['description'] = 'Astrowow.com provides free horoscopes, Astrology reading, Astrologer, Astrology Software, Astrology prediction, Daily, weekly, monthly horoscopes for aries, taurus, gemini, cancer, leo, virgo, libra, scorpio, sagittarius, capricorn, aquarius, pisces, sun sign';
          $meta['keywords'] = 'Free Astrology, Horoscope, Horoscope Reading,  Aries, taurus, gemini, cancer, leo, virgo, libra, scorpio, sagittarius, capricorn, aquarius, pisces,  daily horoscope, weekly horoscope, monthly horoscope,online astrology';
          $this->set(compact('canonical', 'meta'));
        }

        $id = $this->request->session()->read('user_id');
        // Condition for products
        // if($this->request->session()->read('step') == '2' && $this->request->session()->read('Order')) {
        //     $orderData = $this->request->session()->read('Order');
        //     return $this->redirect($orderData['url'] );
        // }

              if( $this->request->session()->read('step') == '2' && $this->request->session()->read('Order')) 
              {
                       $orderData = $this->request->session()->read('Order');
                       return $this->redirect($orderData['url'] );
              }
              elseif($this->request->session()->read('step') == '2' && $this->request->session()->read('SOrder'))
              {
                       $orderData = $this->request->session()->read('SOrder');
                       return $this->redirect($orderData['url'] );
              }

         else if( $this->request->session()->read('step') == '2' ) {
            return $this->redirect( ['controller' => 'Users', 'action' => 'dashboard'] );
        } elseif( empty($id) ) {
            return $this->redirect(['action' => 'sign-up']);
        }
        $entity = $this->Users->get( $id, ['contain' => ['BirthDetails' , 'Subscriptions']]);
        $countryOptions = $this->Countries->find( 'list', ['order' => ['name' => 'ASC']] )->where(['Countries.status' => 1])->toArray();
        if ( $this->request->is('post') ) {
            if(isset($this->request->data['birth_detail']['city_id'] ) && empty($this->request->data['birth_detail']['city_id'] )) {

                $this->request->session()->write('birthData', $this->request->data);
                
                $this->Flash->error(__('Please select city from the dropdown'));
                return $this->redirect( Router::url($this->here, true ) );  
                                    //}
            }
            $this->request->data['birth_date'] = $this->formatDateForDb($this->request->data['birth_date']) ;
            $step = $this->request->session()->read('step');
            $date = $this->request->data['birth_date'];
            $newDate = explode( '-', $date );
            $this->request->data['birth_detail']['date'] = date('Y-m-d', strtotime($newDate[0]) );
            $this->request->data['birth_detail']['day'] = $newDate[1];
            $mnth =  date('m', strtotime($newDate[0]));
            $day = date('d', strtotime($newDate[0]));
            $dateArray = explode( '-' , $this->request->data['birth_detail']['date']);
            $this->request->data['birth_detail']['sun_sign_id'] = $this->calculateSunsignFromDate( $mnth, $day) ;
            $this->request->data['year'] = $dateArray[0];
            $this->request->data['month'] = $dateArray[1];
            $this->request->data['day'] = $dateArray[2];
            $cities = $this->Cities->find('all')
                                    ->where(['id' => $this->request->data['birth_detail']['city_id'] ])
                                    ->toArray();
            foreach ($cities as $city) {
               $this->request->data['latitude'] = $city['latitude'];
               $this->request->data['longitude'] = $city['longitude'];
               $this->request->data['city_name'] = $city['city'];
               $this->request->data['country'] = $city['country'];

            }
            $data = $this->SetLatLong($this->request->data);
            if( !isset($this->request->data['subscription']['special_offers'])) {
                $this->request->data['subscription']['special_offers'] = 0;
            }
            unset($this->request->data['birth_date']);
            unset($this->request->data['city']);
            unset($this->request->data['btnSubmit']);

            if( $this->request->data['hours'] == -1):
                $this->request->data['birth_detail']['time'] = NULL;
            else:
                $this->request->data['birth_detail']['time'] = date("H:i", strtotime($this->request->data['hours'] .":". $this->request->data['minutes']));
            endif;

            unset($this->request->data['hours']);
            unset($this->request->data['minutes']);

            /*if( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' ) {
              echo '<pre>'; print_r($this->request->data); print_r($data); die;
            }*/

            // pr($this->request->data);
            // die;
            $entity = $this->Users->PatchEntity($entity, $this->request->data, ['associated' => ['BirthDetails' , 'Subscriptions']]);
            if($this->Users->save($entity)):
                            // Update Step in User Table
                            $user_query = $this->Users->query();
                            $user_result =  $user_query->update()
                                ->set(['step' => 2])
                                ->where(['id' => $id])
                                ->execute();

                // Set step variable
                $this->request->session()->write('step', '2');
                //$this->request->session()->write('newUser', $id);
                $this->request->session()->write('selectedUser', $id);
                /* Conditions for Product Section */
                if($this->request->session()->read('Order') && $this->request->session()->read('user_id') ) {
                    $orderData = $this->request->session()->read('Order');
                    return $this->redirect($orderData['url'] );
                } elseif( $user_result ) {
                    return $this->redirect(['action' => 'dashboard']);
                } else {
                    $this->Flash->error(__('Unable to save data'));
                }
           else:
                 $this->Flash->error(__('Unable to save data'));
            endif;
        }
        $this->set('countryOptions',$countryOptions);
    }

    function forgotPassword() {
        $canonical['en'] = SITE_URL.'users/forgot-password';
        $canonical['da'] = SITE_URL.'dk/brugere/glemt-kodeord';
        $meta['title'] = 'Astrowow.com - Astrology, free horoscopes, sun sign, Astrology reading, Astrology software';
        $meta['description'] = 'Astrowow.com provides free horoscopes, Astrology reading, Astrologer, Astrology Software, Astrology prediction, Daily, weekly, monthly horoscopes for aries, taurus, gemini, cancer, leo, virgo, libra, scorpio, sagittarius, capricorn, aquarius, pisces, sun sign';
        $meta['keywords'] = 'Free Astrology, Horoscope, Horoscope Reading,  Aries, taurus, gemini, cancer, leo, virgo, libra, scorpio, sagittarius, capricorn, aquarius, pisces,  daily horoscope, weekly horoscope, monthly horoscope,online astrology';
        $this->set(compact('canonical', 'meta'));

        if (!empty($this->request->data)) {
            $user = $this->Users->findByUsername($this->request->data['username'])->first();
            if ( empty($user) ) {
                $this->Flash->error(__('Sorry, the username entered was not found.'));
                $this->redirect(['controller' => 'Users' , 'action' => 'forgot_password']);
            } else {
                $user = $this->__generatePasswordToken($user);
                $user = $this->Users->patchEntity($user, $this->request->data);
                if ($this->Users->save($user) && $this->__sendForgotPasswordEmail($user->id)) {
                 $this->Flash->success(__('Password reset instructions have been sent to your email address. You have 24 hours to complete the request.'));
                 $this->redirect(['controller' => 'Users' , 'action' => 'login']);

                }
            }
        }
    }

    protected function __generatePasswordToken($user) {
      if (empty($user)) { return null; }
        // Generate a random string 100 chars in length.
        $token = "";
        for ($i = 0; $i < 100; $i++) {
            $d = rand(1, 100000) % 2;
            $d ? $token .= chr(rand(33,79)) : $token .= chr(rand(80,126));
        }
        (rand(1, 100000) % 2) ? $token = strrev($token) : $token = $token;
        // Generate hash of random string
        $hash = Security::hash($token, 'sha256', true);;
        for ($i = 0; $i < 20; $i++) {
            $hash = Security::hash($hash, 'sha256', true);
        }
        $user->reset_password_token = $hash;
        $user->token_created_at  = date('Y-m-d H:i:s');
        return $user;
    }


    protected function __sendForgotPasswordEmail($id = null) {
        if (!empty($id)) {
            $user  = $this->Users->find('all')
                                 ->where(['Users.id' => $id])
                                 ->contain(['Profiles'])
                                 ->select(['Profiles.first_name', 'Profiles.last_name', 'Users.username', 'Users.reset_password_token'])
                                 ->first();
            // $email = new Email();
            // $email->template('reset_password_request', 'default')
            //       ->emailFormat('html')
            //       ->subject(__('Password Reset Request - DO NOT REPLY') )
            //       ->to($user['username'])
            //       ->from(['nethuesgrp@gmail.com' => 'Astrowow'])
            //       ->viewVars(compact('user'))
            //       ->send();

            $base = Router::url('/users/reset_password_token/', true);
            $link = $base.$user['reset_password_token'];
            $recipient = $user['username'];
            $data = [
                      'subject' => __('Password Reset Request - DO NOT REPLY'),
                      'name' => $this->getFullName( $user['profile']['first_name'] , $user['profile']['last_name'] ),
                      'link' => $link
                    ];
            $emailTemplate = new EmailTemplatesController();
            $send =  $emailTemplate->sendForgotPasswordEmail($recipient, $data);
            if($send)
            {
              return true;
            }
        }
        return false;
    }


  function resetPasswordToken($reset_password_token = null) {
        if (empty($this->request->data)) {
            $this->request->data = $this->Users->findByResetPasswordToken($reset_password_token)->first();
            if (!empty($this->request->data['reset_password_token']) && !empty($this->request->data['token_created_at']) &&
                $this->__validToken($this->request->data['token_created_at'])) {
                $this->request->data['id'] = null;
                $this->request->session()->write('token', $reset_password_token );
            } else {
                $this->Flash->error(__('The password reset request has either expired or is invalid' ));
                $this->redirect(  ['controller' => 'users', 'action' => 'login'] );
            }
        } else {
            if ($this->request->data['reset_password_token'] != $this->request->session()->read('token')) {
                $this->Flash->error(__('The password reset request has either expired or is invalid' ));
                $this->redirect(  ['controller' => 'users', 'action' => 'login'] );
            }
            $user = $this->Users->findByResetPasswordToken($this->request->data['reset_password_token'])->first();
            $user = $this->Users->patchEntity( $user, $this->request->data );
            if ($this->Users->save($user)) {
                $this->request->data['reset_password_token'] = $this->request->data['token_created_at'] = null;
                if ($this->Users->save($user) && $this->__sendPasswordChangedEmail($user->id)) {
                       $this->request->session()->delete('token');
                       $this->Flash->success(__('Your password has been changed successfully. Please login to continue.'));
                       $this->redirect(  ['controller' => 'users', 'action' => 'login'] );
                }

            }
        }
    }

   protected  function __validToken($token_created_at) {
        $expired = strtotime($token_created_at) + 86400;
        $time = strtotime("now");
        if ($time < $expired) {
            return true;
        }
        return false;
    }


    protected function __sendPasswordChangedEmail($id = null) {
        if (!empty($id)) {
            $this->Users->id = $id;
            $user  = $this->Users->find('all')
                                 ->where(['Users.id' => $id])
                                 ->contain(['Profiles'])
                                 ->select(['Profiles.first_name', 'Profiles.last_name', 'Users.username', 'Users.reset_password_token'])
                                 ->first();
            // $email = new Email();
            // $email->template('password_reset_success')
            //         ->emailFormat('html')
            //         ->subject(__('Password Changed - DO NOT REPLY') )
            //         ->to($user['username'])

            //         ->from(['nethuesgrp@gmail.com' => 'Astrowow'])
            //         ->viewVars(compact('user'))
            //         ->send();
            // $this->set('User', $user);

            //$recipient = $user['username'];
            $data = [
                      'subject' => __('Password Reset Request - DO NOT REPLY'),
                      'name' => $this->getFullName( $user['profile']['first_name'] , $user['profile']['last_name'] ),
                       'recipient' => $user['username']
                    ];
            $emailTemplate = new EmailTemplatesController();
            $send =  $emailTemplate->PasswordChangedEmail($data);

            if( $send )
            {
              return true;
            }
            else
            {
              return false;
            }
            
        }
        return false;
    }

    public function logout(){
        $locale = $this->request->session()->read('locale');
        $this->request->session()->destroy();
        $this->request->session()->write('locale', $locale);
        I18n::locale($locale);

        // removing cookie for blog page
        setcookie("user_id", "", time() - 3600, "/", "",  0);
        return $this->redirect($this->Auth->logout());
    }

    public function events(){
      $events = $this->Events->find('all', ['conditions' => ['status' => 1], 'order' => ['sort_order' => 'asc']])->toArray();
      $canonical['en'] = SITE_URL.'users/events';
      $canonical['da'] = SITE_URL.'dk/brugere/begivenheder';
      $meta['title'] = 'Astrowow.com - Astrology, free horoscopes, sun sign, Astrology reading, Astrology software';
      $meta['description'] = $meta['keywords'] = '';
      $this->set(compact('events', 'canonical', 'meta'));
    }


    public function cities(){
        $this->viewBuilder()->layout("ajax");
        $this->autoRender = false;
        if( $this->request->is('ajax') ) {
            $country = $_POST['country'];
            $city = strtolower($_POST['city']);
            $city_id_div = $_POST['city_id_div'];
            $result_div = $_POST['result_div'];
            $city_box = $_POST['city_box'];
            $query = $this->Cities->find( 'all' )
                                ->where( ['Cities.city LIKE'=>$city.'%', 'Cities.country_id'=>$country, 'Countries.status' => 1] )
                                ->contain( ['Countries'] )
                                ->limit(10)
                                ->toArray();
            if( !empty( $query ) ): ?>
                <ul id="country-list">
                    <?php
                    foreach($query as $data):
                        $latitude = floatval($data['latitude'] / 3600.0);
                        $latitude = trim( sprintf ( "%2d%s%02d", abs ( intval ($latitude) ), ( ( $latitude >= 0 ) ? 'N' : 'S' ), abs ( intval ( ( ( $latitude - intval ( $latitude ) ) * 60) ) ) ) );
                        $longitude = floatval($data['longitude'] / 3600.0);
                        $longitude = trim( sprintf ( "%3d%s%02d", abs ( intval ( $longitude ) ), (($longitude >= 0) ? 'W' : 'E' ), abs ( intval ( ( ( $longitude - intval ( $longitude ) ) * 60) ) ) ) );
                    ?>
                        <li onClick="selectCity( <?php echo $data['id']?> ,'<?php echo $city_id_div?>', '<?php echo $result_div?>', '<?php echo $city_box?>', '<?php  echo  addslashes($data['city']). ' , '. addslashes($data['county']) . ' [' . $latitude . ' '. $longitude .' ]' ; ?>');"><?php  echo  $data['city']. ' , '. $data['county'] . ' [' . $latitude . ' '. $longitude .' ]' ;?></li>
                    <?php endforeach; ?>
                </ul>
                <?php
                else:
                  
                  if(isset($_GET['language']) && !empty($_GET['language']))
                  {
                    $language = $_GET['language'];
                  }
                  else
                  {
                    $language = 'en';
                  }

                   if($language == 'dk')
                   {
                    echo 'Stednavn ikke fundet med disse bogstaver';
                   }
                   else
                   {
                      echo __('City not found with entered letters');
                   }

                endif;

           exit();

         }

     }



    /**
     * This function is used to calculate location based on country and city
     * Created By : Kingslay <kingslay@123789.org>
     * Created Date : Nov. 10, 2016
     */
    protected function calculateLocation ($country, $city) {
        $query = $this->Cities->find()
                                ->where( ['Cities.id' => $city, 'Cities.country_id'=>$country, 'Countries.status' => 1] )
                                ->contain( ['Countries'] )
                                ->first();
        $latitude = floatval($query['latitude'] / 3600.0);
        $latitude = trim( sprintf ( "%2d%s%02d", abs ( intval ($latitude) ), ( ( $latitude >= 0 ) ? 'N' : 'S' ), abs ( intval ( ( ( $latitude - intval ( $latitude ) ) * 60) ) ) ) );
        $longitude = floatval($query['longitude'] / 3600.0);
        $longitude = trim( sprintf ( "%3d%s%02d", abs ( intval ( $longitude ) ), (($longitude >= 0) ? 'W' : 'E' ), abs ( intval ( ( ( $longitude - intval ( $longitude ) ) * 60) ) ) ) );
        return $latitude.' '.$longitude;
    }

    /**
     * This function is used to show dashboard page for authentic users
     * Created By : Kingslay <kingslay@123789.org>
     * Created Date : Nov. 07, 2016
     * Last Modified Date : Nov. 28, 2016
     */
    public function dashboard () {
        $canonical['en'] = SITE_URL.'users/dashboard';
        $canonical['da'] = SITE_URL.'dk/brugere/instrumentbræt';
        $meta['title'] = 'Your Personalized horoscope, horoscope wheel, astrology report and astrology software';
        $meta['description'] = 'Read the your daily, weekly, monthly and yearly sun sign, astrology report and astrology software, horoscope, long-term trend, horoscope wheel.';
        $meta['keywords'] = 'sun sign, horoscopes, horoscope wheel, astrology report, astrology';
        $this->set(compact('meta', 'canonical'));
        $session = $this->request->session();
        $user_id = !empty($session->read('Auth.User.id')) ? $session->read('Auth.User.id') : $session->read('user_id');
        $loggedInUserId = $user_id;

        if ($loggedInUserId) {
            /** Checking registered user
              Created By: Sehdev Singh 24th Nov 2016
            **/
             $userDetails = $this->Users->get($loggedInUserId);
             $step = $userDetails['step'];
             if($step == 1) {
               return $this->redirect(['controller'=>'Users', 'action' => 'sign-up-step-two']);
             } 
            /* End here */

            $user_id = !empty($session->read('selectedUser')) ? $session->read('selectedUser') : $user_id;
            $this->loadModel ('MiniBlogs');
            $latestPosts = $this->MiniBlogs->find ()->where(['MiniBlogs.status' => 1])
            ->order(['MiniBlogs.sort_order' => 'ASC'])->limit(3)->toArray();
            $anotherPersonDetail = array();
            if (strpos($user_id, '_') !== false) {
                $user_id = explode('_', $user_id);
                $user_id = $user_id[1];
                $anotherPersonDetail = $this->AnotherPersons->find()->where(['id' => $user_id, 'added_by'=>$loggedInUserId])->first();
                $TimeZoneData = $this->getTimezoneAndSummerTimezoneOnDashboard ($anotherPersonDetail['zone']);
                $subscriptionActivateOrNot = $this->Subscribes->find()->where(['user_id' => $user_id, 'status' => 1, 'user_type' => 'anotherPerson', 'start_date <= ' => time(), 'end_date >=' => time()])->order(['id' => 'DESC'])->first();
            }
            // Get purchased reports detail of registered user
            $perchasedReport = $this->getUserPurchasedReports ($loggedInUserId);

            $loggedInUserProfileDetails = $this->Profiles->find()->where(['user_id' => $loggedInUserId])->first();
            $anotherpersons = $this->AnotherPersons->find()->select(['id', 'fname', 'lname'])->where(['added_by' => $loggedInUserId, 'status' => 1])->toArray();
            $arrayName = array();
            $usersName[$loggedInUserProfileDetails['user_id']] = ucwords($loggedInUserProfileDetails['first_name'].' '.$loggedInUserProfileDetails['last_name']);
            foreach ($anotherpersons as $anotherpersonsList) {
                $usersName['anotherPerson_'.$anotherpersonsList['id']] = ucwords($anotherpersonsList['fname'].' '.$anotherpersonsList['lname']);
            }
            if (!empty($anotherPersonDetail)) { // If another person is selected
                $cityname = $this->Cities->find()->select(['id', 'city'])->where(['id' => $anotherPersonDetail['city_id']])->first();
                $countryname = $this->Countries->find()->select(['id', 'name', 'abbr'])->where(['id' => $anotherPersonDetail['country_id'], 'Countries.status' => 1])->first();
                $this->set(compact('anotherPersonDetail', 'countryname', 'cityname', 'anotherpersons', 'usersName', 'TimeZoneData', 'subscriptionActivateOrNot'));
            } else {
                $BirthDetails = $this->BirthDetails->find()->where(['user_id' => $loggedInUserId])->first();
                $TimeZoneData = $this->getTimezoneAndSummerTimezoneOnDashboard ($BirthDetails['zone']);
                $cityname = $this->Cities->find()->select(['id', 'city'])->where(['id' => $BirthDetails['city_id']])->first();
                $countryname = $this->Countries->find()->select(['id', 'name', 'abbr'])->where(['id' => $BirthDetails['country_id'], 'Countries.status' => 1])->first();
                $subscriptionActivateOrNot = $this->Subscribes->find()->where(['user_id' => $loggedInUserId, 'status' => 1, 'user_type' => 'user', 'start_date <= ' => time(), 'end_date >=' => time()])->order(['id' => 'DESC'])->first();
                $this->set(compact('BirthDetails', 'countryname', 'cityname', 'anotherpersons', 'usersName', 'loggedInUserProfileDetails', 'TimeZoneData', 'subscriptionActivateOrNot'));
            }
            $this->set(compact('perchasedReport', 'latestPosts'));
            //$this->render('dashboard');
        } else {
            $this->Flash->set(__("You can't access that location."), [ 'element' => 'error' ]);
            $this->redirect(['controller' => 'users', 'action' => 'login']);
        }
    }

    /**
     * Used to show timezone and summer reff on dashboard
     * Created By : Kingslay <kingslay@123789.org>
     * Created Date : Nov. 06, 2016
     */
    function getTimezoneAndSummerTimezoneOnDashboard ($tzone) {
        $TimeZone =  number_format((-1 * $tzone), 2); //number_format(abs($tzone), 2);
        $timedelta_hh = intval($TimeZone );
        $timedelta_mm = number_format( substr($TimeZone , strpos($TimeZone , '.', 0) + 1, 2), 2);
        $tmpMM = number_format( substr($TimeZone , strpos($TimeZone , '.', 0) + 1, 2), 2);
        if($tmpMM != "") {
          if(intval($tmpMM) > 0 && intval($tmpMM) <= 50) {
            $timedelta_mm = 30;
          } else if(intval($tmpMM) > 50 && intval($tmpMM) <= 100) {
            $timedelta_mm = 45;
          }
        }
        $timediff = number_format(floatval(abs(0.00)), 2);
        $timediff_hh = intval($timediff);
        $timediff_mm = number_format( substr( $timediff, strpos($timediff, '.', 0) + 1, 2), 2);
        $tzDetail = array();
        $tzDetail['timezone'] = sprintf("%02d:%02d", $timedelta_hh, $timedelta_mm);
        $tzDetail['summerreff'] = sprintf("%d:%02d", $timediff_hh, $timediff_mm);
        return $tzDetail;
    }

    /**
     * Used to get purchased products detail of loggedin user
     * Created By : Kingslay <kingslay@123789.org>
     * Created Date : Nov. 28, 2016
     * params int $loggedInUserId
     */
    protected function getUserPurchasedReports ($loggedInUserId, $page = NULL) {
      if (!empty($page) && (strtolower($page) == 'editprofilepage')) {
        $conditions = ['Orders.user_id' => $loggedInUserId/*, 'Orders.product_id NOT IN' => [23, 52, 53]*/];
      } else {
        $conditions = ['Orders.user_id' => $loggedInUserId, 'Orders.product_type IN' => [5, 8]];
      }
      $data12 = $this->Orders->find()
                    ->join([
                      'products' => [
                                      'table' => 'products',
                                      'type' => 'INNER',
                                      'conditions' => [
                                          'products.id = Orders.product_id',
                                        ] 
                                    ],
                        'categories' => [
                                          'table' => 'categories',
                                          'type' => 'INNER',
                                          'conditions' => [
                                            'categories.id = products.category_id',
                                          ] 
                                        ],
                        'currencies' => [
                                          'table' => 'currencies',
                                          'type' => 'INNER',
                                          'conditions' => [
                                              'currencies.id = Orders.currency_id',
                                          ] 
                                        ]
                    ])
                    ->select([ 'Orders.id', 'Orders.product_id', 'Orders.payer_order_id', 'Orders.currency_id', 'Orders.price', 'Orders.user_id', 'Orders.product_type', 'Orders.currency_id', 'Orders.order_date', 'products.id', 'products.category_id', 'products.name', 'products.image', 'categories.id', 'categories.name', 'currencies.id', 'currencies.name', 'currencies.symbol'])
                    //->order (['Orders.id' => 'DESC'])
                    ->order (['Orders.order_date' => 'DESC'])
                    ->where($conditions);

                      $paginate['limit'] = 10;

        $data = $this->Paginator->paginate($data12, $paginate);
        return $data;
    }


    /**
     * This function is used to update DOB of user
     * Created By : Kingslay <kingslay@123789.org>
     * Created Date : Nov. 07, 2016
     * Modified Date : Nov. 28, 2016
     * param int $userId
     * param string $birthDayName
     * param string $dob
     */
    function changeDOB ($userId = null, $birthDayName = null, $dob = null) {
        $session = $this->request->session();
        $user_id = !empty($session->read('Auth.User.id')) ? $session->read('Auth.User.id') : $session->read('user_id');
        if ($user_id) {
            $this->viewBuilder()->layout("ajax");
            $this->autoRender = false;
            $days = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'); 
            $query = $this->BirthDetails->query();
            $updateData = $query->update()
                            ->set(['date' => $dob, 'day'=>$days[$birthDayName]])
                            ->where(['user_id' => $userId])
                            ->execute();
            exit();
        } else {
            $this->Flash->set(__("You can't access that location."), [ 'element' => 'error' ]);
            $this->redirect(['controller' => 'users', 'action' => 'login']);
        }
    }

    /**
     * This function is used to edit user profile details
     * params int $myaccount // Show loggedin user details on clicking myaccount link
     * Created By : Kingslay <kingslay@123789.org>
     * Created Date : Nov 07, 2016
     * Last Modified Date : Nov. 11, 2016
     */
    function editProfile ($myaccount = null) {
        $canonical['en'] = SITE_URL.'users/edit-profile';
        $canonical['da'] = SITE_URL.'dk/brugere/rediger-profil';
        $meta['title'] = 'Astrowow.com - Astrology, free horoscopes, sun sign, Astrology reading, Astrology software';
        $meta['description'] = 'Astrowow.com provides free horoscopes, Astrology reading, Astrologer, Astrology Software, Astrology prediction, Daily, weekly, monthly horoscopes for aries, taurus, gemini, cancer, leo, virgo, libra, scorpio, sagittarius, capricorn, aquarius, pisces, sun sign';
        $meta['keywords'] = 'Free Astrology, Horoscope, Horoscope Reading,  Aries, taurus, gemini, cancer, leo, virgo, libra, scorpio, sagittarius, capricorn, aquarius, pisces,  daily horoscope, weekly horoscope, monthly horoscope,online astrology';
        $this->set(compact('canonical', 'meta'));
        $meta['title'] = 'Welcome to Astrowow.com';
        $meta['description'] = '';
        $meta['keywords'] = '';
        $this->set(compact('meta'));
        $this->viewBuilder()->layout('home');
        $session = $this->request->session();
        $logeedInUser = !empty($session->read('Auth.User.id')) ? $session->read('Auth.User.id') : $session->read('user_id');
        if ($logeedInUser) {
            if (!empty($myaccount)) {
                $user_id = $logeedInUser;
            } else {
                $user_id = $session->read('selectedUser');
                if (empty($user_id)) {
                    $user_id = $logeedInUser;
                }
            }
            $personId = $user_id;
            if (!empty($this->request->data)) {
                /*if( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' ) {
                      pr ($this->request->data); die;
                    }*/
                $user_id = $session->read('selectedUser');
                if (empty($user_id)) {
                    $user_id = $logeedInUser;
                }
                // For update birth details
                if ( isset($this->request->data['btnUpdateBirthDetail']) && !empty($this->request->data['btnUpdateBirthDetail'])) {

                    $Countries = $this->Countries->find()->where(['id'=>$this->request->data['ddBirthCountry'], 'Countries.status' => 1])->first();
                    $Cities = $this->Cities->find()->where(['id'=>$this->request->data['ddBirthCity']])->first();
                    $this->request->data['country'] = $Countries['name'];
                    $this->request->data['city_name'] = $Cities['city'];
                    $this->request->data['latitude'] = $Cities['latitude'];
                    $this->request->data['longitude'] = $Cities['longitude'];
                    $this->request->data['zonetable'] = $Cities['zonetable'];
                    $this->request->data['typetable'] = $Cities['typetable'];
                    $this->request->data['city_id'] = $this->request->data['ddBirthCity'];
                    $this->request->data['country_id'] = $this->request->data['ddBirthCountry'];

                    $manipulatedDate = array ();
                    $manipulatedDate = explode ('/', $this->request->data['user_dob_edit']);
                    $this->request->data['month'] = $manipulatedDate[1];
                    $this->request->data['day'] = $manipulatedDate[0];
                    $this->request->data['year'] = $manipulatedDate[2];
                    $this->request->data['hours'] = $this->request->data['birthhour'];
                    $this->request->data['minutes'] = $this->request->data['birthminute'];
                    $this->request->data['status'] = 'yes';

                    $data = $this->SetLatLong ($this->request->data);
                    $this->request->data['birth_detail']['country_id'] = $this->request->data['ddBirthCountry'];
                    $dob = $manipulatedDate[2].'-'.$manipulatedDate[1].'-'.$manipulatedDate[0];
                    $day = date('l', strtotime($dob));  
                    $time = $this->request->data['birthhour'].':'.$this->request->data['birthminute'];
                    $country = $this->request->data['ddBirthCountry'];
                    $city = $this->request->data['ddBirthCity'];
                    $zone = !empty($this->request->data['zone']) ? $this->request->data['zone'] : 0.00; //$this->request->data['birth_detail']['zone'];
                    $type = !empty($this->request->data['type']) ? $this->request->data['type'] : 0.00; //$this->request->data['birth_detail']['type'];

                    if (is_numeric($user_id) || !empty($myaccount)) { // For loggedin user
                        $birthde = $this->BirthDetails->find()->select(['BirthDetails.id', 'BirthDetails.user_id'])->where(['BirthDetails.user_id'=>$logeedInUser])->first();
                        $mydata['myid'] = $birthde['id'];
                        $mydata['uesr_id'] = $user_id;
                        $mydata['day'] = $day;
                        $mydata['date'] = $dob;
                        $mydata['city_id'] = $city;
                        $mydata['country_id'] = $country;
                        $mydata['time'] = $time;
                        $mydata['zone'] = $zone;
                        $mydata['type'] = $type;

                        $birthDetailTable = TableRegistry::get('BirthDetails');
                        $birthDetailEntity = $birthDetailTable->newEntity();
                        $data = $this->BirthDetails->patchEntity($birthDetailEntity, $mydata, ['translations' => true]);
                        $data['id'] = $mydata['myid'];
                        if ($this->BirthDetails->save($data)) {
                          $this->request->session()->write ('profileUpdated', $user_id);
                          $this->Flash->set(__('Your profile details has been updated successfully'), [ 'element' => 'success' ]);
                          $this->redirect(['controller' => 'users', 'action' => 'edit-profile', $myaccount]);
                        } else {
                          $this->Flash->error(__('Unable to save data. Please fill all the required fields.'));
                        }
                        $this->request->session()->write ('profileUpdated', $user_id);                                
                    } else { // For associated users
                        $mydata['added_by'] = $logeedInUser;
                        $user_id = explode('_', $user_id);
                        $mydata['id'] = $user_id[1];
                        $mydata['fname'] = $fname = $this->request->data['fname'];
                        $mydata['lname'] = $lname = $this->request->data['lname'];
                        $mydata['gender'] = $this->request->data['rdoGender'];
                        $dob = explode('/', $this->request->data['user_dob_edit']);
                        $mydata['dob'] = $dob['2'].'-'.$dob['1'].'-'.$dob['0'];
                        $mydata['day'] = date('l', strtotime($mydata['dob']));
                        $mydata['time'] = $this->request->data['birthhour'].':'.$this->request->data['birthminute'];
                        $mydata['country_id'] = $this->request->data['ddBirthCountry'];
                        $mydata['city_id'] = $this->request->data['ddBirthCity'];
                        $mydata['zone'] = $zone;
                        $mydata['type'] = $type;

                        $anotherPersonTable = TableRegistry::get('AnotherPersons');
                        $anotherPersonEntity = $anotherPersonTable->newEntity();
                        $data = $this->AnotherPersons->patchEntity($anotherPersonEntity, $mydata, ['translations' => true]);
                        if ($this->AnotherPersons->save($data)) {
                          $this->Flash->set(__('Your profile details has been updated successfully'), [ 'element' => 'success' ]);
                          $this->redirect(['controller' => 'users', 'action' => 'edit-profile', $myaccount]);
                        } else {
                          $this->Flash->error(__('Unable to save data. Please fill all the required fields.'));
                          $this->redirect(['controller' => 'users', 'action' => 'edit-profile', $myaccount]);
                        }
                        $this->request->session()->write ('profileUpdated', 'anotherperson_'.$user_id[1]);
                    }
                } elseif ( isset($this->request->data['btnUpdateUserDetail']) && !empty($this->request->data['btnUpdateUserDetail'])) { // For update user personal details
                    if (is_numeric($user_id) || !empty($myaccount)) { // For loggedin user
                        $mydata['first_name'] = $this->request->data['txtUserFname'];
                        $mydata['last_name'] = $this->request->data['txtUserlname'];
                        $profile_id = $this->Profiles->find()->select(['id', 'user_id'])->where(['user_id' => $logeedInUser])->first();
                        $mydata['id'] = $profile_id['id'];
                        $profilesTable = TableRegistry::get('Profiles');
                        $profilesEntity = $profilesTable->newEntity();
                        $data = $this->Profiles->patchEntity($profilesEntity, $mydata, ['translations' => true]);
                        if ($this->Profiles->save($data)) {
                          $this->request->session()->write ('profileUpdated', $user_id);
                          $this->Flash->set(__('Your profile details has been updated successfully'), [ 'element' => 'success' ]);
                          $this->redirect(['controller' => 'users', 'action' => 'edit-profile', $myaccount]);
                        } else {
                          $this->Flash->error(__('Unable to save data. Please fill all the required fields.'));
                        }
                    }
                }
            }
	            $userData = $this->Users->find()->select(['id', 'username'])->where(['id' => $logeedInUser])->first();

	            if (is_numeric($user_id)) { // For registered User
	                $userProfile = $this->Profiles->find()->select(['id', 'user_id', 'first_name', 'last_name'])->where(['user_id' => $user_id])->first();
	                $BirthDetails = $this->BirthDetails->find()->select(['id', 'user_id', 'country_id', 'city_id', 'date', 'day', 'time'])->where(['user_id' => $user_id])->first();
                  $entity1 = $this->Profiles->newEntity();
                  $entity2 = $this->BirthDetails->newEntity();
                  $this->set(compact('entity1', 'entity2'));
	            } else { // For associated persons with registered user
	                $user_id = explode('_', $user_id);
	                $user_id = $user_id[1];
	                $anotherpersonDetail = $this->AnotherPersons->find()->where(['id' => $user_id])->first();
	                $dob = str_replace('-', '/', $anotherpersonDetail['dob']);
	                $BirthDetails = array ('id' => $anotherpersonDetail['id'], 'fname' => $anotherpersonDetail['fname'], 'lname' => $anotherpersonDetail['lname'], 'date'=>$dob, 'time'=>$anotherpersonDetail['time'], 'country_id'=>$anotherpersonDetail['country_id'], 'city_id'=>$anotherpersonDetail['city_id'], 'gender' => $anotherpersonDetail['gender']);
	                $userProfile = array('first_name' => $anotherpersonDetail['fname'], 'last_name' => $anotherpersonDetail['lname'], );
                  $entity = $this->AnotherPersons->newEntity();
                  $this->set(compact('entity'));
	            }
	            $header = ucwords($userProfile['first_name'].' '.$userProfile['last_name'])." ";
              $Countries = $this->Countries->find()->order(['Countries.name' => 'ASC'])->where(['Countries.status' => 1]);
              $Cities = $this->Cities->find()->select(['id', 'city'])->where(['country_id' => $BirthDetails['country_id'], 'city !=' => ''])->order(['city' => 'ASC'])->group(['city'])->toArray();
              $this->set(compact('userData', 'userProfile', 'BirthDetails', 'Countries', 'Cities', 'user_id'));

            // Get purchased reports detail of registered user
            $perchasedReport = $this->getUserPurchasedReports ($logeedInUser, 'editprofilepage');
            $this->set(compact('birthDetailsUpdated', 'personId', 'perchasedReport', 'header'));
        } else {
            $this->Flash->set(__("You can't access that location."), [ 'element' => 'error' ]);
            $this->redirect(['controller' => 'users', 'action' => 'login']);
        }
    }


    function selectedUserOnDash ($uid) {
        $this->autoRender = false;
        $session = $this->request->session();
        $loggedInUserId = !empty($session->read('Auth.User.id')) ? $session->read('Auth.User.id') : $session->read('user_id');
        if ($loggedInUserId) {
            if ($this->request->is('ajax')) {
              $this->request->session()->write('selectedUser', $uid);
              echo I18n::locale();
              echo '=> '.$this->request->params['language'].'=>';
              $language = !empty($this->request->session()->read('locale')) ? $this->request->session()->read('locale') : 'en';
              $language = (isset($this->request->params['language']) && !empty(isset($this->request->params['language']))) ? $this->request->params['language'] : 'en';
              $data = ['userId' => $session->read('selectedUser'), 'language' => $language];
              pr ($data);
            }
            //return $this->redirect(['controller' => 'Users', 'action' => 'dashboard']);
            //echo 'stored'; die;
        } else {
            $this->Flash->set(__("You can't access that location."), [ 'element' => 'error' ]);
            $this->redirect(['controller' => 'users', 'action' => 'login']);
        }
    }


    function deleteAnotherPerson ($user_id) {
      $this->autoRender = false;
      $session = $this->request->session();
      $loggedInUserId = !empty($session->read('Auth.User.id')) ? $session->read('Auth.User.id') : $session->read('user_id');
      if ($loggedInUserId) {
        $query = $this->AnotherPersons->query();
        $updateData = $query->update()
              ->set(['status' => 0])
              ->where(['id' => $user_id])
              ->execute();
        //echo $updateData; die;
        if ($updateData) {
          //$this->Flash->set(__('Another person has been deleted successfully.'), [ 'element' => 'success' ]);
          $this->Flash->set(__('Your selected person has been deleted successfully'), [ 'element' => 'success' ]);
          $this->redirect(['controller' => 'users', 'action' => 'dashboard']);
        } else {
          $this->Flash->set(__('Something went wrong'), [ 'element' => 'error' ]);
          $this->redirect(['controller' => 'users', 'action' => 'editProfile']);
        }
      } else {
        $this->Flash->set(__("You can't access that location."), [ 'element' => 'error' ]);
        $this->redirect(['controller' => 'users', 'action' => 'login']);
      }
    }


    
    function deletepermanent () {
        $this->autoRender = false;
        $session = $this->request->session();
        $logeedInUser = !empty($session->read('Auth.User.id')) ? $session->read('Auth.User.id') : $session->read('user_id');
        if ($logeedInUser) {
            $entity = $this->Users->get($logeedInUser);
            //if (!$this->Users->delete ($entity)) {
            if (!$this->Users->delete ($entity)) {
                $this->Flash->set(__("Something went wrong."), [ 'element' => 'error' ]);
                $this->redirect(['controller' => 'users', 'action' => 'edit-profile']);
            }
            $this->Orders->deleteAll (['user_id' => $logeedInUser]);
            $this->Flash->set(__("Your account has been deleted successfully."), [ 'element' => 'success' ]);
            $this->redirect(['controller' => 'users', 'action' => 'logout']);
        } else {
            $this->Flash->set(__("You can't access that location."), [ 'element' => 'error' ]);
            $this->redirect(['controller' => 'users', 'action' => 'login']);
        }
    }


    /**
     * This function is used to delete user profile account temporarily
     * Created By : Kingslay <kingslay@123789.org>
     * Created Date : Nov 08, 2016
     * Last Modified Date : Nov. 10, 2016
     */
    function deleteaccount () {
        $session = $this->request->session();
        $logeedInUser = !empty($session->read('Auth.User.id')) ? $session->read('Auth.User.id') : $session->read('user_id');
        $user_id = $session->read('selectedUser');
        if ($logeedInUser) {
            if (empty($user_id)) {
                $user_id = $logeedInUser;
            }
            if (is_numeric($user_id)) {
                $query = $this->Users->query();
                $updateData = $query->update()
                                ->set(['is_delete' => 1])
                                ->where(['id' => $user_id])
                                ->execute();
                $session->destroy ();
                $this->Flash->set(__('Your account has been deleted successfully'), [ 'element' => 'success' ]);
                $this->redirect(['controller' => 'users', 'action' => 'login']);
            } else {
                $user_id = explode('_', $user_id);
                $user_id = $user_id[1];
                $entity = $this->AnotherPersons->get($user_id);
                if ( $this->AnotherPersons->delete($entity) ) {
                    $this->Flash->set(__('Your selected person has been deleted successfully'), [ 'element' => 'success' ]);
                    $this->redirect(['controller' => 'users', 'action' => 'dashboard']);
                }
            }
        } else {
            $this->Flash->set(__("You can't access that location."), [ 'element' => 'error' ]);
            $this->redirect(['controller' => 'users', 'action' => 'login']);
        }
    }

    /**
     * To get cities list based on selected country
     * params int $countryId
     * Created By : Kingslay <kingslay@123789.org>
     * Created Date : Nov 09, 2016
     */
    function getCountryCities ($countryId) {
        $Cities = $this->Cities->find()->select(['id', 'city', 'country_id'])->where(['country_id' => $countryId])->toArray();
        $option = "";
        if (count($Cities) > 0) {
            foreach ($Cities as $val) {
              if (!empty($val->city)) {
                $option .= "<option value='$val->id'>$val->city</option>";
              }
            }
        } else {
          $option .= "<option>Any city not found.</option>";
        }
        echo $option; die;
    }


    /**
     * This function is used to change loggedin user password
     * Created By : Kingslay <kingslay@123789.org>
     * Created Date : Nov 09, 2016
     */
    function changePassword () {
        $canonical['en'] = SITE_URL.'users/change-password';
        $canonical['da'] = SITE_URL.'dk/brugere/skift-adgangskode';
        $meta['title'] = 'Welcome to Astrowow.com';
        $meta['description'] = '';
        $meta['keywords'] = '';
        $this->set(compact('meta', 'canonical'));
        $session = $this->request->session();
        $user_id = !empty($session->read('Auth.User.id')) ? $session->read('Auth.User.id') : $session->read('user_id');
        if ($user_id) {
            $this->viewBuilder()->layout('home');
            $header = 'Change Password';
            $this->set(compact('header'));
            /*if (!empty($this->request->data)) {
                $hasher = new DefaultPasswordHasher();
                $pass = $hasher->hash($this->request->data['password']);
                $query = $this->Users->query();
                $updateData = $query->update()
                            ->set(['password' => $pass])
                            ->where(['id' => $user_id])
                            ->execute();
                $this->Flash->set(__('Your password has been changed successfully.'), [ 'element' => 'success' ]);
                $this->redirect(['controller' => 'users', 'action' => 'editProfile']);
            }*/
            if (!empty($this->request->data)) {
                $hasher = new DefaultPasswordHasher();
                $pass = $this->Users->get($this->Auth->user('id'))->password;
                if ($hasher->check($this->request->data['old_password'], $pass)) {
                    if ($this->request->data['password'] == $this->request->data['txtCPassword']) {
                      $pass = $hasher->hash($this->request->data['password']);
                      $query = $this->Users->query();
                      $updateData = $query->update()
                              ->set(['password' => $pass])
                              ->where(['id' => $user_id])
                              ->execute();
                      $this->Flash->set(__('Your password has been changed successfully'), [ 'element' => 'success' ]);
                      return $this->redirect(['controller' => 'users', 'action' => 'edit-profile']);
                    } else {
                        return $this->Flash->error('The new password does not match!');
                    }
                } else {
                    return $this->Flash->error('The old password does not match!');
                }
            }
        } else {
            $this->Flash->set(__("You can't access that location."), [ 'element' => 'error' ]);
            $this->redirect(['controller' => 'users', 'action' => 'login']);
        }
    }

    /**
     * This function used to set session value for selected user
     * Created By : Kingslay <kingslay@123789.org>
     * Created Date : Nov. 10, 2016
     */
    function selectedUserId ($userId, $returnStatus=null) {
        $this->autoRender = false;
        $apikey = md5('astrowow.com');
        //if ($userId) {
            $this->request->session()->write('selectedUser' , $userId);
        //}
        $session = $this->request->session();

        if (!empty($returnStatus)) {
            return true;
        } else {
          if (strpos($userId, '_') !== false) {
            $uid = explode('_', $userId);
            $uid = $uid[1];
            $result = $this->AnotherPersons->find()->where(['AnotherPersons.id' => $uid])->first();
            $dob = strtotime($result->dob);

            /** Get proper timezone value and timezone name for another persons **/
            $timezone = $this->getTimezoneAndSummerTimezoneOnDashboard ($result['zone']);
            list($hours, $minutes) = explode(':', $timezone['timezone']);
            $seconds = $hours * 60 * 60 + $minutes * 60;
            // Get timezone name from seconds
            $tz = timezone_name_from_abbr('', $seconds, 1);
            // Workaround for bug #44780
            if($tz === false) $tz = timezone_name_from_abbr('', $seconds, 0);
            $result->zone = $tz.' ( '.$timezone['timezone'].' )';
            $result->type = $timezone['summerreff'];
            // END

            $result->dob = date("F dS, Y", $dob);
            $result->time = date('g:i A', strtotime($result->time));
            $Countries = $this->Countries->find()->where(['id'=>$result->country_id, 'Countries.status' => 1])->first()->toArray();
            $Cities = $this->Cities->find()->where(['id'=>$result->city_id])->first();
            $result->countryname = $Countries['name'];
            $result->cityname = $Cities['city'];
            $result->wheelURL = Router::url([ 'controller' => 'users', 'action' => 'wheel', '?' => ['apikey' => $apikey, 'task' => 'natalwheel', 'uid'=>$uid, 'aper'=>'yes']]);

            if ($_SERVER['SERVER_NAME'] == 'localhost') {
                $source_horo_image_path = $_SERVER['DOCUMENT_ROOT'].'/astrowow/webroot/user-personal-horoscope/anotherPerson_'.$uid.'.natalwheel.jpg';
            } else {
                $source_horo_image_path = $_SERVER['DOCUMENT_ROOT'].'/webroot/user-personal-horoscope/anotherPerson_'.$uid.'.natalwheel.jpg';
            }

            if (file_exists($source_horo_image_path)) {
                $result->wheelImageSRC = '../user-personal-horoscope/anotherPerson_'.$uid.'.natalwheel.jpg';
                $result->wheelImageDefault = 'no';
            } else {
              $result->wheelImageSRC = '../images/personal-wheel.jpeg';
              $result->wheelImageDefault = 'yes';
            }
          } else {
              $result = $this->Users->find()
                            ->select (['Users.id', 'BirthDetails.date', 'BirthDetails.time', 'BirthDetails.city_id', 'BirthDetails.country_id', 'BirthDetails.zone', 'BirthDetails.type' ])
                            ->contain(['BirthDetails'])
                            ->where(['Users.id' => $userId])
                            ->first();
              /** Get proper timezone value and timezone name for registered user **/
              $timezone = $this->getTimezoneAndSummerTimezoneOnDashboard ($result->birth_detail->zone);
              list($hours, $minutes) = explode(':', $timezone['timezone']);
              $seconds = $hours * 60 * 60 + $minutes * 60;
              // Get timezone name from seconds
              $tz = timezone_name_from_abbr('', $seconds, 1);
              // Workaround for bug #44780
              if($tz === false) $tz = timezone_name_from_abbr('', $seconds, 0);
              $result->zone = $tz.' ( '.$timezone['timezone'].' )';
              $result->type = $timezone['summerreff'];
              // END

              $dob = strtotime($result->birth_detail->date);
              $result->dob = date("F dS, Y", $dob);
              $result->time = date('g:i A', strtotime($result->birth_detail->time));
              $Countries = $this->Countries->find()->where(['id'=>$result->birth_detail->country_id, 'Countries.status' => 1])->first()->toArray();
              $Cities = $this->Cities->find()->where(['id'=>$result->birth_detail->city_id])->first();
              $result->countryname = $Countries['name'];
              $result->cityname = $Cities['city'];
              $result->wheelURL = Router::url([ 'controller' => 'users', 'action' => 'wheel', '?' => ['apikey' => $apikey, 'task' => 'natalwheel', 'uid'=>$userId]]);

              if ($_SERVER['SERVER_NAME'] == 'localhost') {
                $source_horo_image_path = $_SERVER['DOCUMENT_ROOT'].'/astrowow/webroot/user-personal-horoscope/'.$userId.'.natalwheel.jpg';
            } else {
                $source_horo_image_path = $_SERVER['DOCUMENT_ROOT'].'/webroot/user-personal-horoscope/'.$userId.'.natalwheel.jpg';
            }
            if (file_exists($source_horo_image_path)) {
                $result->wheelImageSRC = '../user-personal-horoscope/'.$userId.'.natalwheel.jpg';
                $result->wheelImageDefault = 'no';
            } else {
              $result->wheelImageSRC = '../images/personal-wheel.jpeg';
              $result->wheelImageDefault = 'yes';
            }
          }
          //echo $this->request->session()->read('selectedUser');
          echo $result; die; //exit();
        }
    }

    /**
     * This function is used to add another person details by loggedin user
     * Created By : Kingslay <kingslay@123789.org>
     * Created Date : Nov. 10, 2016
     */
    function addAnotherPerson () {
        $canonical['en'] = SITE_URL.'users/add-another-person';
        $canonical['da'] = SITE_URL.'dk/brugere/tilføj-en-person';
        $meta['title'] = 'Welcome to Astrowow.com';
        $meta['description'] = '';
        $meta['keywords'] = '';
        $this->set(compact('meta', 'canonical'));
        $this->viewBuilder()->layout('home');
        $session = $this->request->session();
        $user_id = !empty($session->read('Auth.User.id')) ? $session->read('Auth.User.id') : $session->read('user_id');
        if ($user_id) {
            $header = 'Add Another Person';
            $Countries = $this->Countries->find('all', ['order' => 'name ASC'])->where(['Countries.status' => 1])->toArray();
            if (!empty($session->read('addAnotherPersonDetails')) && !empty($session->read('addAnotherPersonDetails.ddBirthCountry')) ) {
              $Cities = $this->Cities->find()->where(['country_id' => $session->read('addAnotherPersonDetails.ddBirthCountry')])->order(['city' => 'ASC' ])->toArray();
            }
            
            if ($this->request->is('post') && !empty($this->request->data)) {
              if (empty($this->request->data['txtFirstName']) || empty($this->request->data['txtLastName']) || empty($this->request->data['rdoGender']) || empty($this->request->data['user_dob_edit']) /*|| empty($this->request->data['birthhour']) || empty($this->request->data['birthminute'])*/ || empty($this->request->data['ddBirthCountry']) || empty($this->request->data['ddBirthCity']) ) {
                  $session->write('addAnotherPersonDetails', $this->request->data);
                  $this->Flash->set(__('Unable to save data. Please fill all the required fields.'), [ 'element' => 'error' ]);
                  return $this->redirect(['controller' => 'users', 'action' => 'add-another-person']);
              } else {
                if (($this->request->data['birthhour'] == '') || empty($this->request->data['birthhour']) || ($this->request->data['birthhour'] == 0)) {
                    $this->request->data['birthhour'] = 0;
                }
                if (($this->request->data['birthminute'] == '') || empty($this->request->data['birthminute']) || ($this->request->data['birthminute'] == 0)) {
                    $this->request->data['birthminute'] = 0;
                }
                $anotherPersonsTable = TableRegistry::get('AnotherPersons');
                $anotherPerson = $anotherPersonsTable->newEntity();
                // Calculate time zone and summer time start //
                $Countries = $this->Countries->find()->where(['id'=>$this->request->data['ddBirthCountry'], 'Countries.status' => 1])->first()->toArray();
                $Cities = $this->Cities->find()->where(['id'=>$this->request->data['ddBirthCity']])->first();
                $this->request->data['country_id'] = $Countries['id'];
                $this->request->data['country'] = $Countries['name'];
                $this->request->data['city_name'] = $Cities['city'];
                $this->request->data['latitude'] = $Cities['latitude'];
                $this->request->data['longitude'] = $Cities['longitude'];
                $this->request->data['zonetable'] = $Cities['zonetable'];
                $this->request->data['typetable'] = $Cities['typetable'];
                $dob = explode('/', $this->request->data['user_dob_edit']);
                $this->request->data['year'] = $dob[2];
                $this->request->data['month'] = $dob[0];
                $this->request->data['day'] = $dob[1];
                $this->request->data['hours'] = $this->request->data['birthhour'];
                $this->request->data['minutes'] = $this->request->data['birthminute'];
                $this->request->data['birth_detail']['country_id'] = $this->request->data['ddBirthCountry'];
                $this->request->data['status'] = 'yes';
                $data = $this->SetLatLong($this->request->data);
                $anotherPerson->zone = !empty($this->request->data['zone']) ? $this->request->data['zone'] : 0.00; //$data['m_timezone_offset'];
                $anotherPerson->type = !empty($this->request->data['type']) ? $this->request->data['type'] : 0.00; //$data['m_summertime_offset'];
                // Calculate time zone and summer time en //

                $anotherPerson->fname = $this->request->data['txtFirstName'];
                $anotherPerson->lname = $this->request->data['txtLastName'];
                $anotherPerson->gender = $this->request->data['rdoGender'];
                $anotherpersonDOB = explode('/', $this->request->data['user_dob_edit']);
                $anotherpersonDOB = $anotherpersonDOB[2].'-'.$anotherpersonDOB[1].'-'.$anotherpersonDOB[0];
                $day = date('l', strtotime($anotherpersonDOB));
                $anotherPerson->dob = $anotherpersonDOB;
                $anotherPerson->day = $day;
                $anotherPerson->time = $this->request->data['birthhour'].':'.$this->request->data['birthminute'];
                $anotherPerson->country_id = $this->request->data['ddBirthCountry'];
                $anotherPerson->city_id = $this->request->data['ddBirthCity'];
                $anotherPerson->city_id = $this->request->data['ddBirthCity'];
                $anotherPerson->added_by = $user_id;
                $anotherPerson->created = time();
                $anotherPerson->modified = time();
                if ($anotherPersonsTable->save($anotherPerson)) {
                    $session->delete('addAnotherPersonDetails');
                    $lastid = $anotherPerson->id;
                    $this->selectedUserId('anotherPerson_'.$lastid, 'return'); // To shows as selected
                    $this->request->session()->write('anotherPersonAdded' , $lastid);
                    $this->request->session()->write ('profileUpdated', $lastid);
                    $this->Flash->set(__('Person has been added successfully.'), [ 'element' => 'success' ]);
                    $this->redirect (['controller' => 'users', 'action' => 'add-another-person']);
                }
              }
            }
            $this->set(compact('Countries', 'Cities', 'header'));
        } else {
            $this->Flash->set(__("You can't access that location."), [ 'element' => 'error' ]);
            $this->redirect(['controller' => 'users', 'action' => 'login']);
        }
    }

    /**
     * This function is used to subscribe a future horoscopes
     * Created By : Kingslay <kingslay@123789.org>
     * Created Date : Nov. 14, 2016
     */
    function subscribe () {
        $canonical['en'] = SITE_URL.'users/subscribe';
        $canonical['da'] = SITE_URL.'dk/brugere/tilmeld';
        $meta['title'] = 'Welcome to Astrowow.com';
        $meta['description'] = '';
        $meta['keywords'] = '';
        $this->set(compact('meta', 'canonical'));
        $session = $this->request->session();
        $session->write('SubscriptionUrl', Router::url());
        $user_id = !empty($session->read('Auth.User.id')) ? $session->read('Auth.User.id') : $session->read('user_id');
        $checkSubscriptionStatus = $this->Subscribes->find ()
                                                ->where (['user_id' => $user_id, 'status' => 1])
                                                ->order (['id' => 'DESC'])
                                                ->first ();
        //if (empty($checkSubscriptionStatus)) {
          /*$startDate = date ('Y-m-01'); // First day of current month
          $startDateInSeconds = strtotime($startDate); // First day of current month in seconds
          $endDateBasedOnCurrentDate = date('Y-m-01', strtotime('+11 months')); // First day after two months
          $lastDayOfEndMonth = date('Y-m-t', strtotime($endDateBasedOnCurrentDate)); // Last day of subscription end month
          $lastDayOfEndMonthInSeconds = strtotime($lastDayOfEndMonth); // Last day of subscription in seconds*/
          $startDate = date ('Y-m-d'); // First day of current month
          $startDateInSeconds = strtotime($startDate); // First day of current month in seconds
          //$endDateBasedOnCurrentDate = date($startDate, strtotime('+11 months')); // First day after two months
          //$lastDayOfEndMonth = date('Y-m-t', strtotime($endDateBasedOnCurrentDate)); // Last day of subscription end month
          $lastDayOfEndMonthInSeconds = strtotime("+12 months", $startDateInSeconds); //strtotime($endDateBasedOnCurrentDate); // Last day of subscription in seconds
        //} else {
          /*$startDateInSeconds = $checkSubscriptionStatus['start_date'];
          $endDateBasedOnCurrentDate = date('Y-m-01', strtotime('+12 months', $checkSubscriptionStatus['end_date'])); // First day after two months
          $lastDayOfEndMonth = date('Y-m-t', strtotime($endDateBasedOnCurrentDate)); // Last day of subscription end month
          $lastDayOfEndMonthInSeconds = strtotime($lastDayOfEndMonth); // Last day of subscription in seconds*/
        //}
        if ($user_id) {
            if (!empty($this->request->data)) {
                $this->loadModel('Products');
                $tempsubcribesTable = TableRegistry::get('TempSubscribes');
                $tempsubscribe = $tempsubcribesTable->newEntity();
                $tempsubscribe->user_id = $this->request->data['user_id'];
                $tempsubscribe->product_id = 53;
                $another_prsn = 0;
                if (strpos($this->request->data['user_id'], '_') !== false) {
                    $selectedUser = array ();
                    $selectedUser = explode('_', $this->request->data['user_id']);
                    $tempsubscribe->user_id = $selectedUser[1];
                    $subscriptionStatus = $this->Subscribes->find()->where(['user_id'=>$selectedUser[1], 'user_type' => 'anotherPerson', 'start_date <= ' => time(), 'end_date >= ' => time(), 'status' => 1])->order(['id' => 'DESC'])->first();
                    $another_prsn = 1;
                } else {
                  $subscriptionStatus = $this->Subscribes->find()->where(['user_id'=>$user_id, 'user_type' => 'user', 'start_date <= ' => time(), 'end_date >= ' => time(), 'status' => 1])->order(['id' => 'DESC'])->first();
                }
                //if (empty($subscriptionStatus)) {
                  $tempsubscribe->user_type = (isset($another_prsn) && !empty($another_prsn) && $another_prsn) ? 'anotherPerson' : 'user';
                  $tempsubscribe->amount = $this->request->data['hdnPrice'];
                  $tempsubscribe->currency = $this->request->data['hdnCurrencyCode'];
                  $tempsubscribe->created = time();
                  $tempsubscribe->modified = time();
                  $tempsubscribe->start_date = $startDateInSeconds; //time();
                  $tempsubscribe->status = 1;
                  $tempsubscribe->end_date = $lastDayOfEndMonthInSeconds; //strtotime('+90 days', time());
                  
                  if ($tempsubcribesTable->save($tempsubscribe)) {
                      $lastsubid = $tempsubscribe->id;
                      $this->request->session()->write('tempsubscriptionId' , $lastsubid);
                      $subscribe['price']    = $this->request->data['hdnPrice'];
                      $subscribe['currency'] = $this->request->data['hdnCurrencyCode'];
                      $subscribe['order_id'] = 'Sub-'.time(); //$this->subcriptionOrderId ($lastsubid, $user_id);
                      $this->request->session()->write ('subscriptionCalendarOrderId', $subscribe['order_id']);
                      $this->set(compact('subscribe', 'startDateInSeconds', 'lastDayOfEndMonthInSeconds'));
                      //$this->redirect(['controller' => 'users', 'action' => 'subscribe-step2']);
                  }
                /*} else {
                    $expiryDate = date('F dS, Y', $subscriptionStatus['end_date']);
                    $this->Flash->set(__('You have subscribed already, Your subscription will be expire on ') .$expiryDate.'.', [ 'element' => 'error' ]);
                    $this->redirect(['controller' => 'users', 'action' => 'subscribe']);
                }*/
            } else {
                $entity = $this->Subscribes->newEntity();
                $language12 = $session->read('locale');
                if ((strtolower($language12) == 'da') || (strtolower($language12) == 'dk')) {
                    $queryCondition = ['Categories.slug' => 'calendar-subscription', 'currency.code' => 'DKK'];
                } else {
                    $queryCondition = ['Categories.slug' => 'calendar-subscription', 'currency.code !=' => 'DKK'];
                }

                $this->loadModel ('Products');
                $products = $this->Products->find('all')
                                    ->hydrate(false)
                                    ->contain(['Categories'])
                                    ->join([
                                            'product_prices' => [
                                                'table' => 'product_prices',
                                                'type' => 'INNER',
                                                'conditions' => [
                                                   
                                                    'product_prices.product_id = Products.id',
                                                ] 
                                            ],
                                            'currency' => [
                                                'table' => 'currencies',
                                                'type' => 'INNER',
                                                'conditions' => [
                                                    
                                                    'currency.id = product_prices.currency_id',
                                                ] 
                                            ]
  
                                      ])
                                      ->select([ 'Products.id', 'Products.short_description', 'Products.name', 'Products.image', 'Products.pages', 'Products.seo_url', 'product_prices.total_price', 'currency.code', 'currency.name', 'currency.symbol'])
                                      ->where($queryCondition)
                                      ->order(['Products.id' => 'DESC'])
                                      ->toArray();
                //pr ($products); die;
                $loggedInUserDetail = $this->Profiles->find()->where(['user_id'=>$user_id])->first()->toArray();
                $anotherpersonsList = $this->AnotherPersons->find('all')->select(['id', 'fname', 'lname'])->where(['added_by'=>$user_id, 'status' => 1]);
                $users = array ();
                $users[$loggedInUserDetail['user_id']] = ucwords($loggedInUserDetail['first_name'].' '.$loggedInUserDetail['last_name']);
                foreach ($anotherpersonsList as $value) {
                    $users['AP_'.$value['id']] = ucwords($value['fname'].' '.$value['lname']);
                }
                $this->set(compact('entity', 'users', 'startDateInSeconds', 'lastDayOfEndMonthInSeconds', 'products'));
            }
        } else {
            $this->Flash->set(__("You can't access that location."), [ 'element' => 'error' ]);
            $this->redirect(['controller' => 'users', 'action' => 'login']);
        }
    }


    /*function subscribeStep2 () {

    }*/


    public function thankYou () {
        $meta['title'] = 'Welcome to Astrowow.com';
        $meta['description'] = '';
        $meta['keywords'] = '';
        $canonical['en'] = SITE_URL.'users/login';
        $canonical['da'] = SITE_URL.'dk/brugere/tak';
        $this->set(compact('meta', 'canonical'));
    	$session = $this->request->session();
    	//pr ($session->read()); die;
    	$user_id = !empty($session->read('Auth.User.id')) ? $session->read('Auth.User.id') : $session->read('user_id');
    	if( $this->request->is('get') && !empty($this->request->query('hash'))) {
	        $params = $_GET;
	        $var = "";
	        foreach ($params as $key => $value) {
	          if($key != "hash") { $var .= $value; }
	        }
	        $genstamp = md5($var . "AstrowowNethues");
	        if( $genstamp != $this->request->query("hash")) {
	          echo "Hash is not valid";
	          exit();
	        } else {
		        if (empty($session->read('subscriptionCalendarOrderId'))) {
	            return $this->redirect (['controller' => 'pages', 'action' => 'index']);
          	}
          	$subcribesTable = TableRegistry::get('Orders');
          	$entity = $this->Subscribes->newEntity();
          	$tempSub = $session->read('tempsubscriptionId');
          	$this->loadModel('TempSubscribes');
          	$TempSubscribesData = $this->TempSubscribes->find()->where(['id'=>$tempSub])->first();
          	$checkSubscriptionStatus = $this->Subscribes->find ()
                                                //->where (['user_id' => $user_id, 'status' => 1])
                                                ->where (['user_id' => $TempSubscribesData['user_id'], 'user_type' => $TempSubscribesData['user_type'], 'status' => 1])
                                                ->order (['id' => 'DESC'])
                                                ->first ();
            // set older records to 0
            if (!empty($checkSubscriptionStatus)) {
	            $query = $this->Subscribes->query();
	            $updateData = $query->update()
                            ->set(['status' => 0])
                            //->where(['user_id' => $user_id, 'id' => $checkSubscriptionStatus['id']])
                            ->where (['user_id' => $TempSubscribesData['user_id'], 'id' => $checkSubscriptionStatus['id'], 'user_type' => $TempSubscribesData['user_type'], 'status' => 1])
                            ->order (['id' => 'DESC'])
                            ->execute();
            }
          
          	$mydata['subscribe']['user_id'] = $TempSubscribesData['user_id'];
          	$mydata['subscribe']['user_type'] = $TempSubscribesData['user_type'];
          	$mydata['subscribe']['amount'] = $TempSubscribesData['amount'];
          	$mydata['subscribe']['currency'] = $TempSubscribesData['currency'];
          	$mydata['subscribe']['start_date'] = $TempSubscribesData['start_date'];
          	$mydata['subscribe']['end_date'] = $TempSubscribesData['end_date'];
          	$mydata['subscribe']['status'] = $TempSubscribesData['status'];
          	$mydata['subscribe']['created'] = time();
          	$mydata['subscribe']['modified'] = time();
          
          	$mydata['product_id'] = $TempSubscribesData['product_id'];
          	$mydata['price'] = $TempSubscribesData['amount'];
          	$mydata['user_id'] = $TempSubscribesData['user_id'];
          	if (strtolower($TempSubscribesData['user_type']) == 'user') {
            	$mydata['another_person'] = 0;
          	} else {
            	$mydata['another_Person'] = 1;
          	}
          	$anthrPrsnDtl = '';
          	$mydata['email'] = $this->request->session()->read('Auth.User.username');
          	//$mydata['email'] = 'peterson@123789.org';
          	$mydata['payer_order_id'] = $this->request->session()->read('subscriptionCalendarOrderId'); //'Sub-'.time();
          	$mydata['delivery_option'] = 1;
          	$mydata['order_status'] = 9;
          	$mydata['order_date'] = date('Y-m-d H:i:s');
          	$mydata['confirm_payment_date'] = date('Y-m-d H:i:s');
          	$mydata['product_type'] = 10;
          	$mydata['chk_for_register'] = 0;
          	$this->loadModel ('Currencies');
          	$currencyData = $this->Currencies->find ()->select (['id', 'code'])->where(['code' => $TempSubscribesData['currency']])->first();
          	$mydata['currency_id'] = $currencyData['id'];
          	$mydata['shipping_charge'] = 0.00;
          	$selectedLan = !empty($this->request->session()->read('locale')) ? $this->request->session()->read('locale') : 'en';
          	$lanArr = [1 => 'en', 2 => 'da'];
          	$mydata['language_id'] = array_search($selectedLan, $lanArr);;
          	$mydata['portal_id'] = 2;
          	$mydata['payment_method'] = 1;
          	$mydata['created'] = date ('Y-m-d H:i:s');
          	$mydata['modified'] = date ('Y-m-d H:i:s');

          	$mydata['order_transaction']['payment_status'] = 'complete';
          	$mydata['order_transaction']['payment_date'] = date ('Y-m-d H:i:s');
          	if (strtolower($TempSubscribesData['user_type']) == 'user') {
            	$mydata['order_transaction']['full_name'] = $this->request->session()->read('Auth.UserProfile.first_name').' '.$this->request->session()->read('Auth.UserProfile.last_name');
          	} else {
              	$this->loadModel ('AnotherPersons');
              	$anthrPrsnDtl = $this->AnotherPersons->find()->where(['id' => $TempSubscribesData['user_id']])->first();
              	$mydata['order_transaction']['full_name'] = $anthrPrsnDtl['fname'].' '.$anthrPrsnDtl['lname'];
          	}
          	$mydata['order_transaction']['payer_email'] = $this->request->session()->read('Auth.User.username');
          	$mydata['order_transaction']['amount'] = $TempSubscribesData['amount'];
          	$mydata['order_transaction']['currency_code'] = $TempSubscribesData['currency'];
          	$mydata['order_transaction']['payer_order_id'] = $mydata['payer_order_id'];
          	$mydata['order_transaction']['created'] = date ('Y-m-d H:i:s');
          	$mydata['order_transaction']['transaction_no'] = $_GET['txnid'];

          	$data = $this->Orders->patchEntity($entity, $mydata, ['associated' => ['OrderTransactions', 'Subscribes']] );


          	if ($subcribesTable->save($data)) {
                
              	$txnid = $this->request->query('txnid');
              	$order_id = $mydata['payer_order_id'];
              	$userdata = $this->Users->find()->select(['id', 'username'])->where(['id' => $user_id/*$TempSubscribesData['user_id']*/])->first();
              	$this->loadModel ('EmailTemplates');
              		$emailTemplate = $this->EmailTemplates->find()->where(['short_code' => 'yearly_subscription'])->first();
              	$to = $userdata['username'];
              	$subject = $emailTemplate['name'];
              	$userprofile = $this->Profiles->find()->select(['id', 'first_name', 'last_name'])->where(['user_id' => $user_id/*$TempSubscribesData['user_id']*/])->first();
              	if (strtolower($TempSubscribesData['user_type']) == 'user') {
                	$name = ucwords($userprofile['first_name'].' '.$userprofile['last_name']).',';
              	} else {
                	$name = ucwords($userprofile['first_name'].' '.$userprofile['last_name'].' - '.$anthrPrsnDtl['fname'].' '.$anthrPrsnDtl['lname']).' (Additional Person),';
              	}
              	$message = str_replace('{NAME}', $name, $emailTemplate['content']);
                $message = str_replace('{FACEBOOK}', FACEBOOK, $message);
                $message = str_replace('{TWITTER}', TWITTER, $message);
              	$message = str_replace('{LINKEDIN}', LINKEDIN, $message);
              
              	$this->sendMail($to, $subject, $message);
              	$this->request->session()->delete('subscriptionCalendarOrderId');

                $purchaseEvent = "<script>fbq('track', 'Purchase', {value: ".$mydata['order_transaction']['amount'].", currency: '".$mydata['order_transaction']['currency_code']."' });</script>";

              	$this->set(compact('txnid', 'order_id', 'purchaseEvent'));
          	}
        }
      } else {
        	return $this->redirect (['controller' => 'pages', 'action' => 'index']);
      }
    }

    /**
     * Save data into orderTransaction table of purchased report by elite member
     * Created by : Krishna Gupta
     * Created Date : Dec. 20, 2016
     */
    protected function saveTransactionData ($orderId, $firstName, $lastName, $email, $price, $currencyCode, $payer_order_id) {
      $this->loadModel ('OrderTransactions');
      $transactions['payment_status'] = 'complete';
      $transactions['payment_date'] = date('Y-m-d h:i:s');
      $transactions['order_id'] = $orderId;
      $transactions['full_name'] = $firstName." ".$lastName;
      $transactions['payer_email'] = $email;
      $transactions['amount'] = $price;
      $transactions['payer_order_id'] = $payer_order_id;
      $transactions['currency_code'] = $currencyCode;
      $transactions['created'] = date('Y-m-d h:i:s');
      $transactions['transaction_no'] = $this->request->query('txnid');
      $transaction = $this->OrderTransactions->newEntity();
      $transaction = $this->OrderTransactions->patchEntity($transaction, $transactions);
      if ($this->OrderTransactions->save ($transaction)) {
          return $transaction;
      } else {
        return false;
      }
    }



    function cronForInsertPrediction () {
        $this->autoRender = false;
        $session = $this->request->session();
        require_once ( ROOT . DS . 'vendor' . DS  . 'transits' . DS . 'generate-transit.php' ); 
        return true;
    }


    /**
     * This finction is used to generate personal horoscope wheel for user.
     * Created By : Kingslay <kingslay@123789.org>
     * Created Date : Nov. 16, 2016
     * Modified Date : Nov. 22, 2016
     */
    function wheel ($apikey, $uid, $task, $aper='') {
      $this->autoRender = false;
      $session = $this->request->session();
      $user_id = !empty($session->read('Auth.User.id')) ? $session->read('Auth.User.id') : $session->read('user_id');
        if ($user_id) {
          $forwardStatus = 0;
          if (!empty($uid)) {
              if (!empty($aper)) {
                  $anotherPersonStatus = $this->AnotherPersons->find()->where(['id' => $uid, 'added_by' => $user_id])->first();
                  if (!empty($anotherPersonStatus)) {
                      $forwardStatus = 1;
                  }
              } else {
                  if (($user_id == $uid) /*&& empty($aper)*/) {
                      $forwardStatus = 1;
                  }
              }
          }

          if ($forwardStatus) {
              require_once ( ROOT . DS . 'vendor' . DS  . 'Horoscope-wheel' . DS . 'personal-wheel.php');
          }

        } else {
            $this->Flash->set(__("You can't access that location."), [ 'element' => 'error' ]);
            $this->redirect(['controller' => 'users', 'action' => 'login']);
        }
    }
    /*function wheel ($uid=null) {
        $this->autoRender = false;
        $session = $this->request->session();
        $passedUser = $uid;
        $loggedin = $user_id = !empty($session->read('Auth.User.id')) ? $session->read('Auth.User.id') : $session->read('user_id');
        if ($user_id) {
          $forwardStatus = 0;
          if ($uid) {
              if (strpos($uid, '_') !== false) {
                $selectedAnotherPerId = explode('_', $uid);
                $selectedAnotherPerId = $selectedAnotherPerId[1];
                $anotherPersonStatus = $this->AnotherPersons->find()->where(['id' => $selectedAnotherPerId, 'added_by' => $user_id])->first();
                if (!empty($anotherPersonStatus)) {
                  $forwardStatus = 1;
                }
              } else {
                if ($user_id == $uid) {
                    $forwardStatus = 1;
                }
              }
          } else {
            if (!empty($this->request->query('uid'))) {
                if (!empty($this->request->query('aper'))) {
                    $selectedAnotherPerId = $this->request->query('uid');
                    $anotherPersonStatus = $this->AnotherPersons->find()->where(['id' => $selectedAnotherPerId, 'added_by' => $user_id])->first();
                    if (!empty($anotherPersonStatus)) {
                        $forwardStatus = 1;
                    }
                } else {
                    if ($user_id == $this->request->query('uid')) {
                        $forwardStatus = 1;
                    }
                }
            }
          }
          if ($forwardStatus) {
              $mywheel = require_once ( ROOT . DS . 'vendor' . DS  . 'Horoscope-wheel' . DS . 'personal-wheel.php');
              //$mywheel = require_once ( ROOT . DS . 'webroot' . DS  . 'Horoscope-wheel' . DS . 'personal-wheel.php');
              if ($mywheel) {
                return array ('loggedin' => $loggedin, 'passedUser' => $passedUser);
              }
          }

        } else {
            $this->Flash->set("You can't access that location.", [ 'element' => 'error' ]);
            $this->redirect(['controller' => 'users', 'action' => 'login']);
        }
    }*/

    /**
     * This function is used to get transits result for selected date
     * Created By : Kingslay <kingslay@123789.org>
     * Created Date : Nov. 22, 2016
     * Modified Date : Nov. 28, 2016
     * params string $date (dd-mm-yyyy)
     * params string $language
     */
    function inserttransit (/*$lang, $user_id*/) {
    	$session = $this->request->session();
    	$lang = $session->read('locale');
    	$this->autoRender = false;
    	require_once ( ROOT . DS . 'vendor' . DS  . 'transits' . DS . 'generate-transit.php');
    }
   
    /**
     * This function is used to get transits result for selected date
     * Created By : Kingslay <kingslay@123789.org>
     * Created Date : Nov. 22, 2016
     * Modified Date : Nov. 28, 2016
     * params string $date (dd-mm-yyyy)
     * params string $language
     */
    //function transit ($lang, $date) {
    function transit (/*$date, $lang*/) {
        $session = $this->request->session();
        $user_id = !empty($session->read('Auth.User.id')) ? $session->read('Auth.User.id') : $session->read('user_id');
        $lang = !empty($this->request->query('language')) ? $this->request->query('language') : 'en';
        if ($user_id) {
            $this->autoRender = false;
            $date = date ('d-m-Y', time());
            $mydata = require_once ( ROOT . DS . 'vendor' . DS  . 'transits' . DS . 'year.report.transit.php');
            echo $mydata; die;
        } else {
            $this->Flash->set(__("You can't access that location."), [ 'element' => 'error' ]);
            $this->redirect(['controller' => 'users', 'action' => 'login']);
        }
    }

    /**
     * This function is used to get transits result for selected date
     * Created By : Kingslay <kingslay@123789.org>
     * Created Date : Nov. 22, 2016
     * params string $date (dd-mm-yyyy)
     * params string $language
     */
    function fulltransit () {
        $canonical['en'] = SITE_URL.'users/fulltransit';
        $canonical['da'] = SITE_URL.'dk/brugere/transitbeskrivelse';
        $meta['title'] = 'Astrowow.com - Astrology, free horoscopes, sun sign, Astrology reading, Astrology software';
        $meta['description'] = '';
        $meta['keywords'] = '';
        $this->set(compact('canonical', 'meta'));
        
      $session = $this->request->session();
      $user_id = !empty($session->read('Auth.User.id')) ? $session->read('Auth.User.id') : $session->read('user_id');
      if (!$user_id) {
        $this->Flash->set(__("You can't access that location."), [ 'element' => 'error' ]);
        $this->redirect(['controller' => 'users', 'action' => 'login']);
      } else {
        $mydata = require_once ( ROOT . DS . 'vendor' . DS  . 'transits' . DS . 'full.transit.php');
        $this->set(compact('mydata'));
      }
    }

    /**
     * To show daily predictions or influences
     * created By : Kingslay <kingslay@123789.org>
     * Created Date : Dec. 12, 2016
     */
    function dailytransit () {
      $apikey = !empty($this->request->query('apikey')) ? $this->request->query('apikey') : '';
      $date = !empty($this->request->query('date')) ? $this->request->query('date') : '';
      $session = $this->request->session();
      $lang = !empty($this->request->query('language')) ? $this->request->query('language') : 'en';
      if (strpos($lang, '?') !== false) {
        $lang = explode('?', $lang);
        $lang = $lang[0];
      }

      $user_id = !empty($session->read('Auth.User.id')) ? $session->read('Auth.User.id') : $session->read('user_id');
      $user_id = !empty($session->read('selectedUser')) ? $session->read('selectedUser') : $user_id;
      if ($user_id) {
          $this->autoRender = false;
          $mydata = require_once ( ROOT . DS . 'vendor' . DS  . 'transits' . DS . 'generate.daily.personal.php');
          echo $mydata; die;
      } else {
          $this->Flash->set(__("You can't access that location."), [ 'element' => 'error' ]);
          $this->redirect(['controller' => 'users', 'action' => 'login']);
      }
    }

    /**
     * To show icons on calendar
     * created By : Kingslay <kingslay@123789.org>
     * Created Date : Dec. 12, 2016
     */
    function mycalendarIcons () {
      $apikey = !empty($this->request->query('apikey')) ? $this->request->query('apikey') : md5('astrowow.com');
      $session = $this->request->session();
      
      $user_id = !empty($session->read('Auth.User.id')) ? $session->read('Auth.User.id') : $session->read('user_id');
    
      $user_id = !empty($this->request->query('user')) ? $this->request->query('user') : $user_id;
      $date = !empty($this->request->query('date')) ? $this->request->query('date') : '';
      $lang = !empty($this->request->query('language')) ? $this->request->query('language') : 'en';
      if (strpos($lang, '?') !== false) {
        $lang = explode('?', $lang);
        $lang = $lang[0];
      }

      //echo 'API => '.$apikey.' => Date => '.$date.' => User => '.$user_id.' => Language => '.$lang; die;
      $this->autoRender = false;
      $dataf = require_once ( ROOT . DS . 'vendor' . DS  . 'calendar-icons' . DS . 'class.daily.personal.calendar.icons.php');
      return $dataf;
    }

    /**
     * To show Calendar after subscription
     * created By : Kingslay <kingslay@123789.org>
     * Created Date : Dec. 12, 2016
     */
    function subscriptionCalendar () {
      $apikey = md5('astrowow.com');
      $date = date("m-d-Y");
      $session = $this->request->session();
      $user_id = !empty($session->read('Auth.User.id')) ? $session->read('Auth.User.id') : $session->read('user_id');
     
      $lang = !empty($this->request->query('language')) ? $this->request->query('language') : 'en';
      if ($user_id) {
          $this->autoRender = false;
          require_once ( ROOT . DS . 'vendor' . DS  . 'calendar-icons' . DS . 'personal-daily-horoscope.php');
      } else {
          $this->Flash->set(__("You can't access that location."), [ 'element' => 'error' ]);
          $this->redirect(['controller' => 'users', 'action' => 'login']);
      }
    }


    /**
     * To unsubscribe calendar
     * created By : Kingslay <kingslay@123789.org>
     * Created Date : Dec. 12, 2016
     */
    function removeFromSubscription () {
        $session = $this->request->session();
        
        $user_id = !empty($session->read('Auth.User.id')) ? $session->read('Auth.User.id') : $session->read('user_id');
        $user_id = !empty($session->read('selectedUser')) ? $session->read('selectedUser') : $user_id;
        
        if ($user_id) {
            $this->autoRender = false;
            $userType = 'user';
            if (strpos($user_id, '_') !== false) {
              $anthrId = explode('_', $user_id);
              $userType = 'anotherPerson';
              $user_id = $anthrId[1];
            }
            $query = $this->Subscribes->query();
            $updateData = $query->update()
                            ->set(['status' => 0])
                            ->where(['user_id' => $user_id, 'user_type' => $userType])
                            ->execute();
            $this->Flash->success(__('Subscription has been removed'));
            $this->redirect(['controller' => 'users', 'action' => 'dashboard']);
        } else {
            $this->Flash->set(__("You can't access that location."), [ 'element' => 'error' ]);
            $this->redirect(['controller' => 'users', 'action' => 'login']);
        }
    }

    

    function miniBlog() {
       $this->loadModel('MiniBlogs');
       $paginate = [
            'order' => ['MiniBlogs.sort_order' => 'asc']
          ];
       
       $this->set('blogs', $this->paginate('MiniBlogs'));

    }
   


}
