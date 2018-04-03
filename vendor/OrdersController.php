<?php 

  namespace App\Controller;
  use App\Controller\AppController;
  use Cake\ORM\TableRegistry;
  use Cake\Event\Event;
  use Cake\Network\Exception\NotFoundException;
  use Cake\Cache\Cache;
  use Cake\I18n\I18n;
  use Cake\Validation\Validator;
  use Cake\Routing\Router;
  use Cake\Datasource\ConnectionManager;

class OrdersController extends AppController{
 public function initialize()
 {
    parent::initialize();

    $this->viewBuilder()->layout('home');
    $this->loadModel('Products');
    $this->loadModel('Users');
    $this->loadModel('TemporaryOrders');
    $this->loadModel('TemporaryLoversReportData');
    $this->loadModel('Countries');
    $this->loadModel('Currencies');
    $this->loadModel('OrderTransactions');
    $this->loadModel('Cities');
    $this->loadModel('Birthdata');
    $this->loadModel('LoversReportData');
    $this->loadModel('DeliveryOptions');
    $this->loadModel('Portals');
    $this->loadModel('ProductTypes');
    $this->loadModel('States');
    $this->loadModel('Categories');
    $this->loadModel('GuestUserProductDetails');
    $this->loadModel('OrderShippings');
    $this->loadModel('Languages');
    $this->loadModel('ProductPrices');
    if ($this->request->session()->read('locale') == 'en') 
    {
       I18n::locale('en_US');
    }
    elseif ($this->request->session()->read('locale') == 'da')
    {
       I18n::locale('da');   
    }
    
    if( $this->user_id = $this->request->session()->read('user_id') ):
                  $this->user = $this->Users->find('all')
                                            ->where([ 'Users.id' => $this->user_id])
                                            ->contain('Profiles')
                                            ->first();
    endif;
     

 }

public function validateOrder($userId = 0, $email = '', $productId='', $languageId = 1)
{
       $order =  $this->Orders->find('all')
                             ->select( ['user_id', 'email'] )
                             ->where( ['user_id' => $userId, 'email' => $email, 'product_id' => $productId , 'language_id' => $languageId ] )
                             ->first(); 
       return $order;                      

}
public function checkoutStep1 () {
    $canonical['en'] = SITE_URL.'orders/checkout-step-1';
    $canonical['da'] = SITE_URL.'dk/ordrer/kassen-trin-1';
    $meta['title'] = 'Astrowow.com - Astrology, free horoscopes, sun sign, Astrology reading, Astrology software';
    $meta['description'] = 'Astrowow.com provides free horoscopes, Astrology reading, Astrologer, Astrology Software, Astrology prediction, Daily, weekly, monthly horoscopes for aries, taurus, gemini, cancer, leo, virgo, libra, scorpio, sagittarius, capricorn, aquarius, pisces, sun sign';
    $meta['keywords'] = 'Free Astrology, Horoscope, Horoscope Reading,  Aries, taurus, gemini, cancer, leo, virgo, libra, scorpio, sagittarius, capricorn, aquarius, pisces,  daily horoscope, weekly horoscope, monthly horoscope,online astrology';
    $this->set(compact('canonical', 'meta'));

            $order = $this->request->session()->read('Order');
           
            if( empty($order['product_id']) )
            {
              return $this->redirect( ['controller'=> 'Pages', 'action' => 'index'] );
            }
            

            if(!isset($order['price'] ) || empty($order['price']) ) 
            {
              return $this->redirect($order['url']);
            }

             $finalPrice =  $this->getVatPrice( $order['product_id'], $order['currency_id'], $order['product_type']);
                   
             $product_id   = $order['product_id'];
             $product_name = $order['product_name'];
             $entity       = $this->Orders->newEntity();
             $seo_url      = $order['seo_url'];
             $userLanguage = $order['language_id'];

            if( $user_id = $this->request->session()->read('user_id') )
            {
                             $user = $this->Users->find('all')
                                                 ->where([ 'Users.id' => $user_id])
                                                 ->contain('Profiles')
                                                 ->first();
            }

            $this->set(compact('user'));

        if( $this->request->is('post') )
        {
              // Because front end and admin panel Profile validation conflicting
             $this->request->data['profile']['first_name'] = $this->request->data['first_name'];
             $this->request->data['profile']['last_name']  = $this->request->data['last_name'];


             $first_name = $this->request->data['profile']['first_name'];
             $last_name  = $this->request->data['profile']['last_name'];                      
             $email = $this->request->data['username'];

            if( $user_id = $this->request->session()->read('user_id') )
            {           
              /* Check if current user already bought the report*/
            
             // $validateOrder = $this->validateOrder($user_id, $email, $product_id, $userLanguage);
              //if( empty($validateOrder) )
              //{
                $this->request->session()->write('first_name',  $first_name);
                $this->request->session()->write('last_name', $last_name);
                $this->request->session()->write('username', $email);
                return $this->redirect([ 'controller' => 'Orders', 'action' => 'checkout-step-2']);
              //}
              // else
              // {
              //   $this->Flash->error(__( $product_name .' has already been bought by this user.'));
              //   return $this->redirect([ 'controller' => 'Orders', 'action' => 'checkout-step-1']);
              // }
            }
            else
            {
               
                if($seo_url == 'comprehensive-lovers-report' ||  $seo_url == 'astrologi-og-parforhold-rapport')
                {

                 // $validateOrder = $this->validateOrder(0, $email, $product_id, $userLanguage);
                 // if( empty($validateOrder) )
                  //{
                     $this->request->session()->write('first_name',  $first_name);
                     $this->request->session()->write('last_name', $last_name);
                     $this->request->session()->write('username', $email);
                     $data                 = $this->request->data;
                     //$data['email']        = $username;
                     $data['email']        = $email;
                     $data['product_id']   = $product_id;
                     $data['product_type'] = $order['product_type'];
                     $data['language_id']  = $order['language_id'];
                     $data['portal_id']    = 2;
                     $data['first_name']   = $data['profile']['first_name'];
                     $data['last_name']    = $data['profile']['last_name'];
                     $entity = $this->GuestUserProductDetails->newEntity();                
                     $entity = $this->GuestUserProductDetails->patchEntity($entity, $data);
                     if( $this->GuestUserProductDetails->save($entity) )
                     {
                        $this->request->session()->write('first_name',  $this->request->data['profile']['first_name']);
                        $this->request->session()->write('last_name', $this->request->data['profile']['last_name']);
                        $this->request->session()->write('username', $this->request->data['username']);
                        return $this->redirect([ 'controller' => 'Orders', 'action' => 'checkout-step-2']); 
                     }
                     else
                     {
                       $this->Flash->error(__('Unable to save data'));
                     }
                  // }   
                  // else
                  // {
                  //   $this->Flash->error(__( $product_name .' has already been bought by this user.'));
                  //   return $this->redirect([ 'controller' => 'Orders', 'action' => 'checkout-step-1']);
                  // }

                }
                else
                {


                   $user =  $this->Users->findByUsername($email)->first();

                  /* If email id already exits */

                 if( !empty($user) ):
                       
                    $this->Flash->error(__('Email id already exists'));
                    return $this->redirect([ 'controller' => 'Orders', 'action' => 'checkout-step-1']);
                 else:
                    $this->request->session()->write('first_name',  $this->request->data['profile']['first_name']);
                    $this->request->session()->write('last_name', $this->request->data['profile']['last_name']);
                    $this->request->session()->write('username', $this->request->data['username']);
                    return $this->redirect([ 'controller' => 'Orders', 'action' => 'checkout-step-2']); 
                 endif;
                }

        } 
    }
  $this->set('form', $entity);
  $this->set(compact('finalPrice'));

 }


public function setReportsData($birthDate, $hours, $minutes, $countryId, $cityId, $firstName, $lastName, $gender )
{
  $person_date = $this->formatDateForDb($birthDate);
  $person_newDate = explode( '-', $person_date );
  $person['birth_date'] = date('Y-m-d', strtotime($person_newDate[0]));


  if($hours == -1)
  {
    $person['birth_time'] = NULL;
  }
  else
  {
    $person['birth_time'] = date("H:i", strtotime($hours .":". $minutes));
  }

  $person['country_id'] = $countryId;
  $person['city_id'] = $cityId;
  $person['first_name'] = ucwords($firstName);
  $person['last_name'] = ucwords($lastName);
  $person['name_on_report'] = $person['first_name']." ".$person['last_name'];
  $person['gender'] = $gender;

  return $person;
  
}

public function checkoutStep2() {
  $canonical['en'] = SITE_URL.'orders/checkout-step-2';
  $canonical['da'] = SITE_URL.'dk/ordrer/kassen-trin-2';
  $meta['title'] = 'Astrowow.com - Astrology, free horoscopes, sun sign, Astrology reading, Astrology software';
  $meta['description'] = 'Astrowow.com provides free horoscopes, Astrology reading, Astrologer, Astrology Software, Astrology prediction, Daily, weekly, monthly horoscopes for aries, taurus, gemini, cancer, leo, virgo, libra, scorpio, sagittarius, capricorn, aquarius, pisces, sun sign';
  $meta['keywords'] = 'Free Astrology, Horoscope, Horoscope Reading,  Aries, taurus, gemini, cancer, leo, virgo, libra, scorpio, sagittarius, capricorn, aquarius, pisces,  daily horoscope, weekly horoscope, monthly horoscope,online astrology';
  $this->set(compact('canonical', 'meta'));  
  $order   = $this->request->session()->read('Order');
  if( empty($order['product_id']) )
  {
      return $this->redirect( ['controller'=> 'Pages', 'action' => 'index'] );
  }
   $finalPrice =  $this->getVatPrice( $order['product_id'], $order['currency_id'], $order['product_type']);
                          
      $entity  = $this->Orders->newEntity();
      $seo_url = $order['seo_url'];

   /* With login */
  if( $user_id = $this->request->session()->read('user_id') ):
                          $userData = $this->Users->find('all')
                                                  ->contain(['Profiles', 'BirthDetails'])
                                                  ->where(['Users.id' => $user_id])
                                                  ->first();
              
               if( $this->request->is('post') ):

                       /** For lovers report **/
                        if($seo_url == 'comprehensive-lovers-report' ||  $seo_url == 'astrologi-og-parforhold-rapport' ):
                          
                        $this->request->session()->write('loversData', $this->request->data);

                        if(isset($this->request->data['person_1_city_id'] ) && empty($this->request->data['person_1_city_id'] ))
                        {
                                  $this->Flash->error(__('Please select person 1 city from the dropdown'));
                                  return $this->redirect( Router::url($this->here, true ) );
                        }

                        if(isset($this->request->data['person_2_city_id'] ) && empty($this->request->data['person_2_city_id'] ))
                        {
                                  $this->Flash->error(__('Please select person 2 city from the dropdown'));
                                  return $this->redirect( Router::url($this->here, true ) );
                        }
                         /* Person 1 array */ 
                        $person_1 = $this->setReportsData($this->request->data['person_1_birth_date'],$this->request->data['person_1_hours'], $this->request->data['person_1_minutes'], $this->request->data['person_1_country_id'], $this->request->data['person_1_city_id'], $this->request->data['person_1_first_name'], $this->request->data['person_1_last_name'] , $this->request->data['person_1_gender']  );
                          $order['email'] = $this->request->data['username'];

                        /* Person 2 array */
                          $person_2 = $this->setReportsData($this->request->data['person_2_birth_date'],$this->request->data['person_2_hours'], $this->request->data['person_2_minutes'], $this->request->data['person_2_country_id'], $this->request->data['person_2_city_id'], $this->request->data['person_2_first_name'], $this->request->data['person_2_last_name'] , $this->request->data['person_2_gender']  );

                          $order['first_name'] = ucwords($this->request->session()->read('first_name'));
                          $order['last_name'] = ucwords($this->request->session()->read('last_name'));
                          $order['name_on_report'] = $order['first_name']." ".$order['last_name'];
                          $order['lovers_report'] = 1;
                          $this->request->session()->write('Order', $order);

                         // pr($person1);
                         // pr($person2);

                          $this->request->session()->write('Person_1', $person_1);
                          $this->request->session()->write('Person_2', $person_2);

                          return $this->redirect([ 'controller' => 'Orders', 'action' => 'checkout-step-3']);
                  else:
                            /** for other reports **/
                          // to resolve validation conflict
                          $this->request->data['profile']['gender'] = $this->request->data['gender'];

                          $orderData = $this->setReportsData($this->request->data['birth_date'], $this->request->data['hours'], $this->request->data['minutes'], $this->request->data['birth_detail']['country_id'], $this->request->data['birth_detail']['city_id'], $this->request->session()->read('first_name'), $this->request->session()->read('last_name') , $this->request->data['profile']['gender'] );


                            if(isset($orderData['city_id']) && empty($orderData['city_id'] ))
                            {

                                  $this->request->session()->write('Order', array_merge($order, $orderData));
                                  $this->Flash->error(__('Please select city from the dropdown'));
                                  return $this->redirect( Router::url($this->here, true ) );
                            }
                       
                           
                           $order = array_merge($order, $orderData);
                           $order['email'] = $this->request->data['username'];
                          $this->request->session()->write('Order', $order);
                          return $this->redirect([ 'controller' => 'Orders', 'action' => 'checkout-step-3']);
                   endif;         
              endif;
  else:
                  /* Without login */
                  if( $this->request->is('post') ):
                            
                         
                          if($seo_url == 'comprehensive-lovers-report' ||  $seo_url == 'astrologi-og-parforhold-rapport' )
                          {
                            $this->request->session()->write('loversData', $this->request->data);
                            if(isset($this->request->data['person_1_city_id'] ) && empty($this->request->data['person_1_city_id'] ))
                            {
                                      $this->Flash->error(__('Please select person 1 city from the dropdown'));
                                      return $this->redirect( Router::url($this->here, true ) );
                            }

                            if(isset($this->request->data['person_2_city_id'] ) && empty($this->request->data['person_2_city_id'] ))
                            {
                                      $this->Flash->error(__('Please select person 2 city from the dropdown'));
                                      return $this->redirect( Router::url($this->here, true ) );
                            }

                             /* Person 1 array */ 
                         $person_1 = $this->setReportsData($this->request->data['person_1_birth_date'],$this->request->data['person_1_hours'], $this->request->data['person_1_minutes'], $this->request->data['person_1_country_id'], $this->request->data['person_1_city_id'], $this->request->data['person_1_first_name'], $this->request->data['person_1_last_name'] , $this->request->data['person_1_gender']  );

                          $order['email'] = $this->request->data['username'];

                        /* Person 2 array */
                         $person_2 = $this->setReportsData($this->request->data['person_2_birth_date'],$this->request->data['person_2_hours'], $this->request->data['person_2_minutes'], $this->request->data['person_2_country_id'], $this->request->data['person_2_city_id'], $this->request->data['person_2_first_name'], $this->request->data['person_2_last_name'] , $this->request->data['person_2_gender']  );


                          $order['first_name'] = ucwords($this->request->session()->read('first_name'));
                          $order['last_name'] = ucwords($this->request->session()->read('last_name'));
                          $order['name_on_report'] = $order['first_name']." ".$order['last_name'];
                          $order['lovers_report'] = 1;
                          $order['user_id'] = 0;

                          $this->request->session()->write('Order', $order);
                          $this->request->session()->write('Person_1', $person_1);
                          $this->request->session()->write('Person_2', $person_2);

                          return $this->redirect([ 'controller' => 'Orders', 'action' => 'checkout-step-3']);

                          }
                          else
                          {

                          if(isset($this->request->data['birth_detail']['city_id'] ) && empty($this->request->data['birth_detail']['city_id'] ))
                            {
                                 // Making this array to show in form
                                 //pr($this->request->data);
                                 //pr($order);
                                // $formatDate = explode('-', $this->request->data['birth_date']);

                                 $formatDate = $this->formatDateForDb($this->request->data['birth_date']);
                                 $newDateData = explode( '-', $formatDate );
                                 $reportData['birth_date'] = date('Y-m-d', strtotime($newDateData[0]));
                                // $reportData['birth_date'] = 
                                 $reportData['birth_time'] = $this->request->data['hours'].":".$this->request->data['minutes'];
                                 $reportData['country_id'] = $this->request->data['birth_detail']['country_id'];
                                 $reportData['city_id'] = $this->request->data['birth_detail']['city_id'];
                                 $reportData['first_name'] = $this->request->data['profile']['first_name'];
                                 $reportData['last_name'] = $this->request->data['profile']['last_name'];
                                 $reportData['name_on_report'] = $this->request->data['name_on_report'];
                                 $reportData['gender'] = $this->request->data['gender'];
                                 $this->request->session()->write('Order', array_merge($order, $reportData));

                                 $this->Flash->error(__('Please select city from the dropdown'));
                                 return $this->redirect( Router::url($this->here, true ) );
                            }

                            // to resolve validation conflict
                            $this->request->data['profile']['gender'] = $this->request->data['gender'];
                            $data = $this->request->data;
                            $data['birth_date'] = $this->formatDateForDb($data['birth_date']) ;
                            $user = $this->Users->newEntity();
                            $data['is_guest'] = 1;
                            $date = explode( '-', $data['birth_date'] );

                            $data['birth_detail']['date'] = date('Y-m-d', strtotime($date[0]) );
                            
                            if(isset($date[1]))
                            {
                              $data['birth_detail']['day'] = $date[1];
                            }
                            else
                            {
                             $data['birth_detail']['day'] = ''; 
                            }

                            $mnth =  date('m', strtotime($date[0]));
                            $day  =  date('d', strtotime($date[0]));
                            $data['birth_detail']['sun_sign_id'] = $this->calculateSunsignFromDate( $mnth, $day) ;
                            $dateArray = explode( '-' , $data['birth_detail']['date']);
                            $this->request->data['year'] = $dateArray[0];
                            $this->request->data['month'] = $dateArray[1];
                            $this->request->data['day'] = $dateArray[2];

                          $cities = $this->Cities->find('all')
                                              ->where(['id' => $data['birth_detail']['city_id'] ])->toArray();
                        
                        foreach ($cities as $city) 
                        {
                           $this->request->data['latitude'] = $city['latitude'];
                           $this->request->data['longitude'] = $city['longitude'];
                           $this->request->data['city_name'] = $city['city'];
                           $this->request->data['country'] = $city['country'];
                        
                        }
                          $zone_data = $this->SetLatLong($this->request->data);
                          $data['birth_detail']['zone'] = $this->request->data['birth_detail']['zone'];
                          $data['birth_detail']['type'] = $this->request->data['birth_detail']['type'];

                        if( $data['hours'] == -1):
                          $data['birth_detail']['time'] = NULL;
                        else:
                          $data['birth_detail']['time'] = date("H:i", strtotime($data['hours'] .":". $data['minutes']));
                        endif;
                  
                            $data['profile']['first_name'] = ucwords($data['profile']['first_name']);
                            $data['profile']['last_name'] = ucwords($data['profile']['last_name']);
                            $fullname = $data['profile']['first_name']." ".$data['profile']['last_name'];
                            $password  = $this->generate_password();
                            $data['password'] = $password;
                            $data['step'] = 2; 
                            $user = $this->Users->patchEntity($user, $data , ['associated' => ['Profiles', 'BirthDetails']] );

                        if( $this->Users->save($user) ):
                          
                          $this->request->session()->write('user_id', $user->id);
                          $recipient = $data['username'];
                          $mailData      = [
                                                'subject'     => "Welcome to Astrowow.com‏",
                                                'mailtext'    => "<h1>Hello $fullname,</h1>",
                                                'password'    => $password,
                                                'username'    => $data['username'],
                                                'name'        => $fullname
                                         ];

                          $emailTemplate = new EmailTemplatesController();
                          $send =  $emailTemplate->sendWelcomeEmailOnSignup($recipient, $mailData);
                          $order['birth_date'] = date('Y-m-d', strtotime($date[0]));
                          $order['birth_time'] = date("H:i", strtotime($data['hours'] .":". $data['minutes']));
                          $order['country_id'] = $data['birth_detail']['country_id'];
                          $order['city_id'] = $data['birth_detail']['city_id'];
                          $order['first_name'] = ucwords($this->request->session()->read('first_name'));
                          $order['last_name'] = ucwords($this->request->session()->read('last_name'));
                          $order['name_on_report'] = ucwords($this->request->data['name_on_report']);
                          $order['gender'] = $data['profile']['gender'];
                          $order['email'] = $data['username'];
                          $this->request->session()->write('Order', $order);
                          return $this->redirect([ 'controller' => 'Orders', 'action' => 'checkout-step-3']);
                    endif;
            }
              endif;
            
      endif;
                         $countryOptions = $this->Countries->find('list', ['order' => [ 'name' => 'ASC']])
                                                           ->toArray();
                         $this->set('form', $entity);
                         $this->set(compact('userData'));
                         $this->set('countryOptions',$countryOptions);
                         $this->set(compact('finalPrice'));
  }



  public function checkoutStep3() {
    $canonical['en'] = SITE_URL.'orders/checkout-step-3';
    $canonical['da'] = SITE_URL.'dk/ordrer/kassen-trin-3';
    $meta['title'] = 'Astrowow.com - Astrology, free horoscopes, sun sign, Astrology reading, Astrology software';
    $meta['description'] = 'Astrowow.com provides free horoscopes, Astrology reading, Astrologer, Astrology Software, Astrology prediction, Daily, weekly, monthly horoscopes for aries, taurus, gemini, cancer, leo, virgo, libra, scorpio, sagittarius, capricorn, aquarius, pisces, sun sign';
    $meta['keywords'] = 'Free Astrology, Horoscope, Horoscope Reading,  Aries, taurus, gemini, cancer, leo, virgo, libra, scorpio, sagittarius, capricorn, aquarius, pisces,  daily horoscope, weekly horoscope, monthly horoscope,online astrology';
    $this->set(compact('canonical', 'meta'));
            $order = $this->request->session()->read('Order');
            if( empty($order['product_id']) )
            {
              return $this->redirect( ['controller'=> 'Pages', 'action' => 'index'] );
            }
            
            $finalPrice =  $this->getVatPrice( $order['product_id'], $order['currency_id'], $order['product_type']);


            /* Checking condition for payment gateway */   
            unset($order['type']);  
            if(!$this->validateDataForPaymentGateway($order))
            {
              $this->Flash->error(__('Unable to process your request'));
              $this->redirect($order['url']);
            }
             
          /* If someone tries to access page via url */
            
               $entity      =   $this->TemporaryOrders->newEntity();
               $seo_url     =   $order['seo_url'];
               $product_id  =   $order['product_id'];
               $language_id =   $order['language_id'];
               $currency_id =   $order['currency_id'];


               if( $this->request->session()->read('user_id') ):
               $user_id  = $this->request->session()->read('user_id') ; 
               else:
                $user_id = 0;
               endif;
               
                              /* Step 3 Code */
                              $deliveryOptions = $this->DeliveryOptions->find('all')
                                                                       ->select(['id'])
                                                                       ->where(['name' => 'Email'])
                                                                       ->first();
                
                             // Storing data in temp table on page load
                              $price = $order['price'];
                              $price_array = explode(' ',$price);
                              $order['delivery_option'] = $deliveryOptions['id'];
                              if ($this->request->session()->read('Auth.User.role') == 'elite') {
                                $order['product_type'] = 8;
                              } else {
                                $order['product_type'] = 5;
                              }
                              $order['order_date'] = date('Y-m-d h:i:s');
                              $order['user_id'] = $user_id;
                              $order['price'] = $price_array[1];
                              $currency = $this->Currencies->find()
                                                       ->select(['code'])
                                                       ->where(['id' => $currency_id])
                                                       ->first()
                                                       ->toArray();

                             $order['currency_code'] = $currency['code']; 
                             $entity = $this->TemporaryOrders->patchEntity($entity, $order);

                   if($this->TemporaryOrders->save($entity))
                   {

                      /* Saving lovers data in temporary_lovers_report_data table */
                       if( $seo_url == "comprehensive-lovers-report" ||  $seo_url == 'astrologi-og-parforhold-rapport' )
                       {
                            $person_1 = $this->request->session()->read('Person_1');
                            $person_2 = $this->request->session()->read('Person_2');
                            $person_1['temporary_order_id'] = $entity->id;
                            $person_2['temporary_order_id'] = $entity->id;
                            $peron_1_entity  = $this->TemporaryLoversReportData->newEntity();
                            $person_1_entity = $this->TemporaryLoversReportData->patchEntity($peron_1_entity,$person_1);
                            $peron_2_entity  = $this->TemporaryLoversReportData->newEntity();
                            $person_2_entity = $this->TemporaryLoversReportData->patchEntity($peron_2_entity, $person_2);
                            $this->TemporaryLoversReportData->save($peron_1_entity);
                            $this->TemporaryLoversReportData->save($peron_2_entity);
                        }                   
                            $order_id = $this->getOrderId($product_id, $entity->id);
                            $order['temp_order_id'] = $entity->id;
                            $order['order_id'] = $order_id;
                            $order['price'] = $price;
                            $this->request->session()->write('Order', $order);
                    }
              /* End Here */
                  $this->set('form', $entity);
                  $this->set(compact('deliveryOptions'));
                  $this->set('order_id', $order['order_id']);
                  $this->set(compact('finalPrice'));
    }

    protected function sendLoverReportMailToAdmin ($order_id, $price, $transaction_id, $language_id, $emailid) {
      $session_val = $this->request->session();
      $role = (!empty($session_val->read('Auth.User.role')) && (strtolower($session_val->read('Auth.User.role')) == 'elite')) ? 'Yes' : 'No';
      $city1 = $this->tableData("Cities", $session_val->read("Person_1.city_id"), "city");
      $country1 = $this->tableData("Countries", $session_val->read("Person_1.country_id"), "name");
      $city2 = $this->tableData("Cities", $session_val->read("Person_2.city_id"), "city");
      $country2 = $this->tableData("Countries", $session_val->read("Person_2.country_id"), "name");
      $payerId = $this->tableData("Orders", $order_id, "payer_order_id");
      $language = $this->tableData("Languages", $language_id, "name");
      $birth_time1 = (!empty($session_val->read("Person_1.birth_time"))) ? $session_val->read("Person_1.birth_time") : '00:00';
      $birth_time2 = (!empty($session_val->read("Person_2.birth_time"))) ? $session_val->read("Person_2.birth_time") : '00:00';
      return $template = '<table style="width: 80%;">
                        <tbody>
                          <tr><td style="margin: 0; padding: 0;">Hi,</td></tr>
                          <tr><td>&nbsp;</td></tr>
                          <tr><td width="100%">You have received a lover report order. Order details are mentioned below: </td></tr>
                          <tr>
                            <table>
                              <tr>
                                <td width="20%">Order Date : </td>
                                <td>'.date('d-m-Y h:i:sa').'</td>
                              </tr>
                              <tr>
                                <td width="20%">Elite User : </td>
                                <td>'.$role.'</td>
                              </tr>
                              <tr>
                                <td width="20%">Language : </td>
                                <td>'.$language["name"].'</td>
                              </tr>
                              <tr>
                                <td width="20%">Order No. : </td>
                                <td>'.$payerId["payer_order_id"].'</td>
                              </tr>
                              <tr>
                                <td width="20%">Amount : </td>
                                <td>'.str_replace(" ", "", $price).'</td>
                              </tr>
                              <tr>
                                <td width="20%">Transaction Id : </td>
                                <td>'.$transaction_id.'</td>
                              </tr>
                              <tr>
                                <td width="20%">Status : </td>
                                <td>'.__('Authorized and awaits to be raised').'</td>
                              </tr>
                              <tr>
                                <td width="20%">Email : </td>
                                <td>'.$emailid.'</td>
                              </tr>
                            </table>
                          </tr>
                        </tbody>
                      </table>
                      <br>
                      <table style="border: 1px solid #e4165b; width: 60%;">
                        <tr><td style="border-right: 2px solid #337ab7; width: 50%;">
                              <h3 style="color: #fff; text-align: center; background-color: #e4165b; border-color: #337ab7;" class="panel-title">Person 1</h3>
                              <table>
                                <tbody style="border-top: 2px solid #ddd;">
                                  <tr style="display: table-row; vertical-align: inherit; border-color: inherit;">
                                    <td style="width: 50%">Name : </td>
                                    <td>'.$session_val->read("Person_1.first_name").' '.$session_val->read("Person_1.last_name").'</td>
                                  </tr>
                                  <tr style="display: table-row; vertical-align: inherit; border-color: inherit;">
                                    <td style="width: 50%">Gender : </td>
                                    <td>'.$session_val->read("Person_1.gender").'</td>
                                  </tr>
                                  <tr style="display: table-row; vertical-align: inherit; border-color: inherit;">
                                    <td style="width: 50%">Date of Birth : </td>
                                    <td>'.date("d-m-Y", strtotime($session_val->read("Person_1.birth_date"))).'</td>
                                  </tr>
                                  <tr style="display: table-row; vertical-align: inherit; border-color: inherit;">
                                    <td style="width: 50%">Birth Time : </td>
                                    <td>'.$birth_time1.'</td>
                                  </tr>
                                  <tr style="display: table-row; vertical-align: inherit; border-color: inherit;">
                                    <td style="width: 50%">Birth City : </td>
                                    <td>'.$city1["city"].'</td>
                                  </tr>
                                  <tr style="display: table-row; vertical-align: inherit; border-color: inherit;">
                                    <td style="width: 50%">Country : </td>
                                    <td>'.$country1["name"].'</td>
                                  </tr>
                                </tbody>
                              </table>
                          </td>
                          <td style="width: 50%;">
                              <h3 style="color: #fff; text-align: center; background-color: #e4165b; border-color: #337ab7;" class="panel-title">Person 2</h3>
                              <table>
                                <tbody style="border-top: 2px solid #ddd;">
                                  <tr style="display: table-row; vertical-align: inherit; border-color: inherit;">
                                    <td style="width: 50%">Name : </td>
                                    <td>'.$session_val->read("Person_2.first_name").' '.$session_val->read("Person_2.last_name").'</td>
                                  </tr>
                                  <tr style="display: table-row; vertical-align: inherit; border-color: inherit;">
                                    <td style="width: 50%">Gender : </td>
                                    <td>'.$session_val->read("Person_2.gender").'</td>
                                  </tr>
                                  <tr style="display: table-row; vertical-align: inherit; border-color: inherit;">
                                    <td style="width: 50%">Date of Birth : </td>
                                    <td>'.date("d-m-Y", strtotime($session_val->read("Person_2.birth_date"))).'</td>
                                  </tr>
                                  <tr style="display: table-row; vertical-align: inherit; border-color: inherit;">
                                    <td style="width: 50%">Birth Time : </td>
                                    <td>'.$birth_time2.'</td>
                                  </tr>
                                  <tr style="display: table-row; vertical-align: inherit; border-color: inherit;">
                                    <td style="width: 50%">Birth City : </td>
                                    <td>'.$city2["city"].'</td>
                                  </tr>
                                  <tr style="display: table-row; vertical-align: inherit; border-color: inherit;">
                                    <td style="width: 50%">Country : </td>
                                    <td>'.$country2["name"].'</td>
                                  </tr>
                                </tbody>
                              </table>
                          </td>
                        </tr>
                      </table>
                    <br><p>Thanks,</p><p>AstroWow Team</p>';
    }
    

    public function thankYou() {
      $canonical['en'] = SITE_URL.'orders/thank-you';
      $canonical['da'] = SITE_URL.'dk/ordrer/tak';
      $meta['title'] = 'Astrowow.com - Astrology, free horoscopes, sun sign, Astrology reading, Astrology software';
      $meta['description'] = 'Astrowow.com provides free horoscopes, Astrology reading, Astrologer, Astrology Software, Astrology prediction, Daily, weekly, monthly horoscopes for aries, taurus, gemini, cancer, leo, virgo, libra, scorpio, sagittarius, capricorn, aquarius, pisces, sun sign';
      $meta['keywords'] = 'Free Astrology, Horoscope, Horoscope Reading,  Aries, taurus, gemini, cancer, leo, virgo, libra, scorpio, sagittarius, capricorn, aquarius, pisces,  daily horoscope, weekly horoscope, monthly horoscope,online astrology';
      $this->set(compact('canonical', 'meta'));

      if(empty ($this->request->session()->read('Order'))) {
        return $this->redirect( ['controller'=> 'Pages', 'action' => 'index'] );
      }
      $order = $this->request->session()->read('Order');

      if( $this->request->is('get') && !empty($this->request->query('hash'))):
        $params = $_GET;
        $var = "";
        foreach ($params as $key => $value) {
          if($key != "hash") {
            $var .= $value;
          }
        }
        $genstamp = md5($var . "AstrowowNethues");
        if( $genstamp != $this->request->query("hash")) {
          echo "Hash is not valid";
          exit();
        } else {
          $loverreportstatus = false;
          $entity  = $this->Orders->newEntity();
          $user_id = $this->request->session()->read('user_id');
          $order   = $this->request->session()->read('Order');
          $orderPrice = $order['price'];

          /*
           * krishan kumar gupta
           */
          // Facebook purchase report tracking code
          $purchasedReportPrice = explode(' ', $order['price']);
          $purchaseEvent = "<script>fbq('track', 'Purchase', {value: ".$purchasedReportPrice[1].", currency: '".$order['currency_code']."' });</script>";

          $id      = $order['temp_order_id'];
          $seo_url = $order['seo_url'];
          /* For Lovers Report */
          if($seo_url == "comprehensive-lovers-report" ||  $seo_url == 'astrologi-og-parforhold-rapport') {
            $loverreportstatus = true;
            //if( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' ) {
              //echo '<pre>'; print_r($order); print_r($this->request->session()->read()); die;
            //}
            //$price = $order['price'];
            $priceDetail = explode(' ',$orderPrice);
            $order['price'] = $priceDetail[1];
            $order['order_status']  = 12;
            $order['payer_order_id'] = $order['order_id'];
            $entity =  $this->Orders->patchEntity($entity, $order);
            if($this->Orders->save($entity)) {
              /** Fetching Temporary data for lovers report **/
              $temp_order = $this->TemporaryLoversReportData->find('all')
                                                           ->contain(['TemporaryOrders'])
                                                            ->where( ['temporary_order_id' => $id ] )
                                                           ->select(['TemporaryLoversReportData.first_name', 'TemporaryLoversReportData.last_name','TemporaryLoversReportData.name_on_report', 'TemporaryLoversReportData.gender', 'TemporaryLoversReportData.birth_date', 'TemporaryLoversReportData.birth_time', 'TemporaryLoversReportData.city_id', 'TemporaryLoversReportData.country_id'])
                                                           ->toArray();

              /** Storing lovers data in birth data and order transactions table **/
              foreach($temp_order as $temp_order):
                $birthDetails =   $this->saveBirthData($temp_order->birth_date, $temp_order->birth_time, $temp_order->city_id, $temp_order->country_id, $temp_order->first_name, $temp_order->last_name, $temp_order->name_on_report, $temp_order->gender, $entity->id);
              
                /* End Here*/
                if( $this->Birthdata->save($birthDetails) ) {
                  $report['person_name'] = $temp_order->first_name." ".$temp_order->last_name ;
                  $report['gender'] = $temp_order->gender;
                  $report['birth_data_id'] = $birthDetails->id;
                  $report['order_id'] = $entity->id;
                  $lovers_report_data = $this->LoversReportData->newEntity();
                  $lovers_report_data = $this->LoversReportData->patchEntity($lovers_report_data, $report); 

                  /* 7th March 2017*/
                  if(!$this->LoversReportData->save($lovers_report_data)) {
                    $this->Flash->error(__('Unable to process your request') ); 
                  }
                } else {
                  $this->Flash->error(__('Unable to process your request') );
                }
              endforeach;
              $transaction = $this->saveTransactionData($entity->id, $order['first_name'], $order['last_name'], $order['email'], $order['price'], $order['currency_code'] , $order['order_id']);

              if( $this->OrderTransactions->save($transaction) ):
                $recipient = $order['email'];
                $orderText = ((strtolower( substr( I18n::locale(), 0, 2)) == 'da')) ? 'Tak for din Bestilling' : 'Thank you for Ordering';
                $orderData = [  'subject'      => $orderText."‏ ". $order['product_name'],
                                'name'         => $order['name_on_report'],
                                'username'     => $order['email'],
                                'product_id'   => $order['product_id'],
                                'product_name' => $order['product_name'] 
                              ];
                

                $emailTemplate = new EmailTemplatesController();
                $send =  $emailTemplate->sendReportsOrderEmail($recipient, $orderData);

              else:
                $this->Flash->error(__('Unable to process your request') );
              endif;
            }
          } else { /** For other reports **/
            $temp_order = $this->TemporaryOrders->find()
                                                ->where( ['TemporaryOrders.id' => $id ] )
                                                ->first()
                                                ->toArray();



            $temp_order['order_status'] = 12;
            $temp_order['payer_order_id'] = $order['order_id'];
            $temp_order['confirm_payment_date'] = date('Y-m-d h:i:s');

            unset($temp_order['id']);
            unset($entity);
            $entity  = $this->Orders->newEntity();
            $entity =  $this->Orders->patchEntity($entity, $temp_order);

            if($this->Orders->save($entity)):
              /* Saving Transaction Data */
              $transaction = $this->saveTransactionData($entity->id, $temp_order['first_name'], $temp_order['last_name'], $temp_order['email'], $temp_order['price'], $order['currency_code'], $order['order_id'] );

              /*** Creating data for birthdata table ***/
              $birthDetails =   $this->saveBirthData($temp_order['birth_date'], $temp_order['birth_time'], $temp_order['city_id'], $temp_order['country_id'], $order['first_name'], $order['last_name'], $order['name_on_report'], $order['gender'], $entity->id);
              /** End Here**/

              if( $this->OrderTransactions->save($transaction) && $this->Birthdata->save($birthDetails)):
                $recipient = $temp_order['email'];
                $orderText = ((strtolower( substr( I18n::locale(), 0, 2)) == 'da')) ? 'Tak for din Bestilling' : 'Thank you for Ordering';  
                $orderData = [  'subject'      => $orderText."‏ ". $order['product_name'],
                                'name'         => $temp_order['name_on_report'],
                                'username'     => $temp_order['email'],
                                'product_id'   => $order['product_id'],
                                'product_name' => $order['product_name'] 
                              ];


                $emailTemplate = new EmailTemplatesController();
                $send =  $emailTemplate->sendReportsOrderEmail($recipient, $orderData);
                /* 6 March 2017 */
              else:
                $this->Flash->error(__('Unable to process your request') );
              endif;
              /* 6 March 2017 */
            else:
              $this->Flash->error(__('Unable to process your request') );
            endif;
          }
          $this->set(compact('purchaseEvent'));
          $this->set('order_id', $order['order_id']);
          $this->set('txnid', $this->request->query('txnid'));
          if ($loverreportstatus) {
              //if ( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' ) {
                  $msgBody = $this->sendLoverReportMailToAdmin ($entity->id, $orderPrice, $this->request->query('txnid'), $order['language_id'], $order['email']);
                  $emailTemplate->sendMailToAdmin($order['product_name'].' report order received', $msgBody, true);
              //}
          } else {
              //if ( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' ) {
                  $msgBody = $this->otherReportsMailToAdmin ($entity->id, $orderPrice, $this->request->query('txnid'), $order['product_name'], $temp_order['city_id'], $temp_order['country_id'],$temp_order['birth_date'], $temp_order['birth_time'], $order['language_id'], $order['email']);
                  $emailTemplate->sendMailToAdmin($order['product_name'].' report order received', $msgBody);
              //}
          }

          $this->request->session()->delete('Order');
          $this->request->session()->delete('first_name');
          $this->request->session()->delete('last_name');
          $this->request->session()->delete('birthdata');
          $this->request->session()->delete('Person_1');
          $this->request->session()->delete('Person_2');
          $this->request->session()->delete('loversData');
        }
      else:
        return $this->redirect( ['controller'=> 'Users', 'action' => 'login'] );
      endif;
    }


    protected function otherReportsMailToAdmin ($order_id, $price, $transaction_id, $report_name, $city_id, $country_id, $birth_date, $birth_time, $language_id, $emailid) {
      $this->loadModel('Birthdata');
      $birthdataDetails = $this->Birthdata->find()->where(['order_id' => $order_id])->first();
      $language = $this->tableData("Languages", $language_id, "name");
      $city = $this->tableData("Cities", $city_id, "city");
      $country = $this->tableData("Countries", $country_id, "name");
      $payerId = $this->tableData("Orders", $order_id, "payer_order_id");
      $session_val = $this->request->session();
      $birth_date = explode('-', $birth_date);
      $birth_date = $birth_date[2].'-'.$birth_date[1].'-'.$birth_date[0];
      $birth_time = explode(', ', $birth_time);
      $birth_time = $birth_time[1];
      $role = (!empty($session_val->read('Auth.User.role')) && (strtolower($session_val->read('Auth.User.role')) == 'elite')) ? 'Yes' : 'No';
      return $template = '<table style="width: 800px;">
                        <tbody>
                          <tr><td style="margin: 0; padding: 0;">Hi,</td></tr>
                          <tr><td>&nbsp;</td></tr>
                          <tr><td width="100%">You have received a report order. Order details are mentioned below: </td></tr>
                        </tbody>
                      </table>

                      <table style="border: 1px solid #e4165b; width: 800px;">
                        <tbody>
                          <tr>
                            <td style="width: 100%;">
                              <h3 style="color: #fff; text-align: center; background-color: #e4165b; border-color: #337ab7;" class="panel-title">Order details</h3>
                            </td>
                          </tr>
                          <tr>
                            <td>
                              <table style="width: 100%;">
                                <tr>
                                  <td style="width: 50%;">Report Name : </td>
                                  <td>'.$report_name.'</td>
                                </tr>
                                <tr>
                                  <td style="width: 50%;">Email : </td>
                                  <td>'.$emailid.'</td>
                                </tr>
                                <tr>
                                  <td style="width: 50%;">Language : </td>
                                  <td>'.$language["name"].'</td>
                                </tr>
                                <tr>
                                  <td style="width: 50%;">Order Date : </td>
                                  <td>'.date('d-m-Y h:i:sa').'</td>
                                </tr>
                                <tr>
                                  <td style="width: 50%;">Order No. : </td>
                                  <td>'.$payerId["payer_order_id"].'</td>
                                </tr>
                                <tr>
                                  <td style="width: 50%;">Amount : </td>
                                  <td>'.str_replace(" ","", $price).'</td>
                                </tr>
                                <tr>
                                  <td style="width: 50%;">Transaction Id : </td>
                                  <td>'.$transaction_id.'</td>
                                </tr>
                                <tr style="display: table-row; vertical-align: inherit; border-color: inherit;">
                                  <td style="width: 50%;">Name : </td>
                                   <td>'.ucwords($birthdataDetails["first_name"]." ".$birthdataDetails["last_name"]).'</td>
                                </tr>
                                <tr style="display: table-row; vertical-align: inherit; border-color: inherit;">
                                  <td style="width: 50%;">Gender : </td>
                                  <td>'.$birthdataDetails["gender"].'</td>
                                </tr>
                                <tr style="display: table-row; vertical-align: inherit; border-color: inherit;">
                                  <td style="width: 50%;">Date of Birth : </td>
                                  <td>'.$birth_date.'</td>
                                </tr>
                                <tr style="display: table-row; vertical-align: inherit; border-color: inherit;">
                                  <td style="width: 50%;">Birth Time : </td>
                                  <td>'.$birth_time.'</td>
                                </tr>
                                <tr style="display: table-row; vertical-align: inherit; border-color: inherit;">
                                  <td style="width: 50%;">Birth City : </td>
                                  <td>'.$city["city"].'</td>
                                </tr>
                                <tr style="display: table-row; vertical-align: inherit; border-color: inherit;">
                                  <td style="width: 50%;">Country : </td>
                                  <td>'.$country["name"].'</td>
                                </tr>
                              </table>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    <br><p>Thanks,</p><p>AstroWow Team</p>';
    }
  

protected function saveBirthData($birthDate, $birthTime, $cityId, $countryId, $firstName, $lastName, $nameOnReport, $gender, $orderId)
 {  

  

      $birthdata['year'] = date('Y', strtotime($birthDate));
      $birthdata['month'] = date('m', strtotime($birthDate));
      $birthdata['day'] = date('d', strtotime($birthDate));


      if( $birthTime != NULL )
      {
      	    //if( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' )
  			//{

  				$locale = strtolower( substr(I18n::locale(), 0, 2)  ) ;
 
                // Changing locale to get correct time from the database
  				if($locale == 'da')
  				{
  					I18n::locale('en_US');
   		  		    $birthdata['hour'] = date('H', strtotime($birthTime));
                    $birthdata['minute'] = date('i', strtotime($birthTime));
                    I18n::locale('da');
             	}
             	else
             	{
      	    //   die('reach');
      	    //}

               $birthdata['hour'] = date('H', strtotime($birthTime));
               $birthdata['minute'] = date('i', strtotime($birthTime));

                   }
      	    // $newbirthTime        = explode(':', $birthTime);
      	    // $birthdata['hour']   = $newbirthTime[0];
      	    // $birthdata['minute'] = $newbirthTime[1];
      }
      else
      {
              $birthdata['hour'] = 00;
              $birthdata['minute'] = 00;
              $birthdata['untimed'] = 1;
      }

      $cityData = $this->Cities->find('all')
                               ->contain('Countries')
                               ->where(['Cities.id' => $cityId, 'Countries.id' => $countryId])
                               ->select(['Countries.name','Countries.abbr', 'Cities.city', 'Cities.county', 'Cities.longitude', 'Cities.latitude'])
                               ->first();

      $birthdata['city'] = $cityData['city'];
      $birthdata['state'] = $cityData['county'];
      $birthdata['country'] = $cityData['cities']['name'];
      $birthdata['place'] = $cityData['city'];
      $birthdata['longitude'] = $cityData['longitude'];
      $birthdata['latitude'] = $cityData['latitude'];
      $birthdata['first_name'] = $firstName;
      $birthdata['last_name'] = $lastName;
      $birthdata['name_on_report'] = $nameOnReport;
      $birthdata['start_date'] = date('Y-m-d');
      $birthdata['gender'] = $gender;
      $birthdata['state'] = $cityData['cities']['abbr'];
      $birthdata['city_name'] = $cityData['city'];
      $birthdata['duration']  = 3;
      $birthdata['birth_detail']['country_id'] = $countryId;
      $birthdata['minutes'] = $birthdata['minute'];
      $birthdata['hours'] = $birthdata['hour'];
      $zone_data = $this->SetLatLong($birthdata);
      $birthdata['zoneref'] = $birthdata['birth_detail']['zone'];
      $birthdata['summerref'] = $birthdata['birth_detail']['type'];
      unset($birthdata['city']);
      unset($birthdata['country']);
      $birthdata['order_id'] = $orderId;
      $birthDetails = $this->Birthdata->newEntity();
      $birthDetails = $this->Birthdata->patchEntity($birthDetails, $birthdata);    

      return $birthDetails;
 }

 protected function saveTransactionData($orderId, $firstName, $lastName, $email, $price, $currencyCode, $payer_order_id)
 {
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
      return $transaction;
 }

 // public function downloadFreeTrial($productId)
   public function downloadFreeTrial()
   {
    $canonical['en'] = SITE_URL.'orders/download-free-trial';
    $canonical['da'] = SITE_URL.'dk/ordrer/download-gratis-prøveversion';
    $meta['title'] = 'Astrowow.com - Astrology, free horoscopes, sun sign, Astrology reading, Astrology software';
    $meta['description'] = 'Astrowow.com provides free horoscopes, Astrology reading, Astrologer, Astrology Software, Astrology prediction, Daily, weekly, monthly horoscopes for aries, taurus, gemini, cancer, leo, virgo, libra, scorpio, sagittarius, capricorn, aquarius, pisces, sun sign';
    $meta['keywords'] = 'Free Astrology, Horoscope, Horoscope Reading,  Aries, taurus, gemini, cancer, leo, virgo, libra, scorpio, sagittarius, capricorn, aquarius, pisces,  daily horoscope, weekly horoscope, monthly horoscope,online astrology';
    $this->set(compact('canonical', 'meta'));

       $order = $this->request->session()->read('Order');
       if(!isset($order['category']) || empty($order['category']))
       {
         return $this->redirect(['controller' => 'Pages', 'action'=> 'index']);
       }

               $entity          = $this->Orders->newEntity();
               //$product       = $this->Products->get($productId);
               $productId       = $order['product_id']; 
               if(strtolower( substr( I18n::locale(), 0, 2)) == 'da')
               {
                 I18n::locale('en_us');
                 $product         = $this->Products->get($productId); 
                 I18n::locale('da');
               }
               else
               {
                 $product         = $this->Products->get($productId); 
               }
               


               $deliveryOption  = $this->DeliveryOptions->find('all')
                                                        ->where(['slug' => 'email'])
                                                        ->select(['DeliveryOptions.id'])
                                                        ->first();
      
               $portal          = $this->Portals->find('all')
                                                ->where(['Portals.name' => 'astrowow'])
                                                ->select(['Portals.id'])
                                                ->first();
                                                
               $orderStatus     = $this->States->find('all')
                                               ->where(['States.name' => 'closed'])
                                               ->select(['States.id'])
                                               ->first();

               $categoryData    = $this->Categories->get($product['category_id']);
               $orderData['category']            = $categoryData['slug'];   
               //$orderData['product_type']        = 'free-trial';       
               $orderData['product_type']        = $order['product_type'];       
               
               $orderData['product_id']          = $order['product_id'];             
               $orderData['language_id']         = $order['language_id'];

                                                       
       if($this->request->is('post'))
       {

              $orderData                         = $this->request->data;
              $orderData['email']                = $orderData['username'];
              $orderData['portal_id']            = $portal['id'];
              $orderData['category_id']          = $product['category_id'];
              $categoryData                      = $this->Categories->get($product['category_id']);
              $orderData['category']             = $categoryData['slug']; 
              $orderData['product_name']         = $product['name'];
              $orderData['product_id']           = $productId;             
              //$orderData['language_id']          = $orderData['language_id']; 
              $orderData['payer_order_id']       = $this->getOrderIdByProductType('Trial');
              //$products    =  $this->Products->Get($orderData['product_id']);
             
                 
              $url = base64_encode($this->getSoftwarePath($orderData['language_id'], $product->seo_url));

              

          if(!empty($this->user_id) && isset($this->user_id))
          {
              
              $orderData['order_date']           = date('Y-m-d h:i:s');
              $orderData['product_type']         = $orderData['product_type'];
              $orderData['delivery_option']      = $deliveryOption['id'];
              $orderData['order_status']         = $orderStatus['id'];
              $orderData['confirm_payment_date'] = date('Y-m-d');
              $orderData['user_id']              = $this->user_id;

              $query = $this->Orders->query();
              $query->insert(['product_id', 'user_id', 'email', 'delivery_option', 'order_status', 'order_date', 'confirm_payment_date', 'product_type', 'language_id', 'portal_id', 'created', 'payer_order_id'])
                  ->values([
                      'product_id'            => $orderData['product_id'],
                      'user_id'               => $orderData['user_id'],
                      'email'                 => $orderData['email'],
                      'delivery_option'       => $orderData['delivery_option'],
                      'order_status'          => $orderData['order_status'],
                      'order_date'            => $orderData['order_date'],
                      'confirm_payment_date'  => $orderData['confirm_payment_date'],
                      'product_type'          => $orderData['product_type'],
                      'language_id'           => $orderData['language_id'],
                      'portal_id'             => $orderData['portal_id'],
                      'created'               => date('Y-m-d h:i:s'),
                      'payer_order_id'        => $orderData['payer_order_id']
                  ]);
              
              if($query->execute())
              {
                $this->request->session()->delete('product_type');
                $this->request->session()->delete('language_id');
               // $this->redirect(['controller' => 'Orders', 'action' => 'thank-you-free-trial', '?' => ['oic' => $url]]);
                $this->redirect(['controller' => 'Orders', 'action' => 'thank-you-free-trial', $url ]);
              }
              else
              {
                $this->Flash->error(__('Error saving data'));
              }
              
          }
          else
          {
              $orderData['first_name']           = $orderData['profile']['first_name'];
              $orderData['last_name']            = $orderData['profile']['last_name'];
              $orderData['product_type']         = $orderData['product_type'];
              $download = $this->GuestUserProductDetails->newEntity();
              $download = $this->GuestUserProductDetails->patchEntity($download, $orderData);
              if($this->GuestUserProductDetails->save($download))
              {
                 $this->request->session()->delete('product_type');
                 $this->request->session()->delete('language_id');
              //   $this->redirect(['controller' => 'Orders', 'action' => 'thank-you-free-trial', '?' => ['oic' => $url]]);
                $this->redirect(['controller' => 'Orders', 'action' => 'thank-you-free-trial',  $url ]);
              }
              else
              {
                $this->Flash->error(__('Error saving data'));
              }
          }
       }
      // $language_id  = $order['language_id'];
      // $product_type = $order['product_type'];
      // $this->request->session()->write('language_id',  $language_id);
       //$this->request->session()->write('product_type', $product_type);


       // elseif($this->request->is('put'))
       // {
       //       $language_id  = $this->request->data['language_id'];
       //       $product_type = $this->request->data['product_type'];

       //       if(isset($language_id)  && !empty($language_id) && isset($product_type) && !empty($product_type))
       //       {
       //          $this->request->session()->write('language_id',  $language_id);
       //          $this->request->session()->write('product_type', $product_type);
       //       }
       // }
             $this->request->session()->write('Order', $orderData);
             $this->set('user', $this->user);
             $this->set('form', $entity);
             $this->set(compact('product', 'deliveryOption'));
   }

   public function thankYouFreeTrial($link) {
    $canonical['en'] = SITE_URL.'orders/thank-you-free-trial';
    $canonical['da'] = SITE_URL.'dk/ordrer/tak-free-trial';
    $meta['title'] = 'Astrowow.com - Astrology, free horoscopes, sun sign, Astrology reading, Astrology software';
    $meta['description'] = 'Astrowow.com provides free horoscopes, Astrology reading, Astrologer, Astrology Software, Astrology prediction, Daily, weekly, monthly horoscopes for aries, taurus, gemini, cancer, leo, virgo, libra, scorpio, sagittarius, capricorn, aquarius, pisces, sun sign';
    $meta['keywords'] = 'Free Astrology, Horoscope, Horoscope Reading,  Aries, taurus, gemini, cancer, leo, virgo, libra, scorpio, sagittarius, capricorn, aquarius, pisces,  daily horoscope, weekly horoscope, monthly horoscope,online astrology';
    $this->set(compact('canonical', 'meta'));

    $purchaseEvent = "<script>fbq('track', 'Purchase', {value: 0.00, currency: 'USD' });</script>";
    $this->set(compact('purchaseEvent'));
    if( empty($link) || !isset($link) ) {
      return $this->redirect(['controller' => 'Pages', 'action' => 'index']);
    } else {
      $link = base64_decode($link);
      $this->set(compact('link'));
    }
        // if($link = $this->request->query('oic'))
        //  {
        //     $link = base64_decode($link);
        //     $this->set(compact('link'));
        //  }
   }

   public function download()
   {

         if($link = $this->request->query('oic'))
         {
            $link = base64_decode($link);
            $this->set(compact('link'));
         }
   }
 
 private function checkDownloadOrderWithLogin($productId, $languageId, $userId, $portalId, $productType, $deliveryOption)
 {

   $order = $this->Orders->find('all')
                         ->where(['Orders.product_id' => $productId, 'Orders.language_id' => $languageId, 'Orders.portal_id' => $portalId, 'Orders.product_type' => $productType, 'Orders.delivery_option' => $deliveryOption, 'Orders.user_id' => $userId])
                         ->select(['Orders.id'])
                         ->first();

   if(!empty($order))
   {
    return $order['id'];
   }
   else
   {
    return 0;
   }
 }


/* This function is used for purchasing Software CD */
function softwareCheckoutStep1()
 {
    $canonical['en'] = SITE_URL.'orders/software-checkout-step-1';
    $canonical['da'] = SITE_URL.'dk/ordrer/software-kassen-trin-1';
    $meta['title'] = 'Astrowow.com - Astrology, free horoscopes, sun sign, Astrology reading, Astrology software';
    $meta['description'] = 'Astrowow.com provides free horoscopes, Astrology reading, Astrologer, Astrology Software, Astrology prediction, Daily, weekly, monthly horoscopes for aries, taurus, gemini, cancer, leo, virgo, libra, scorpio, sagittarius, capricorn, aquarius, pisces, sun sign';
    $meta['keywords'] = 'Free Astrology, Horoscope, Horoscope Reading,  Aries, taurus, gemini, cancer, leo, virgo, libra, scorpio, sagittarius, capricorn, aquarius, pisces,  daily horoscope, weekly horoscope, monthly horoscope,online astrology';
    $this->set(compact('canonical', 'meta'));

    $entity            = $this->Orders->newEntity();
    $session           = $this->request->session();
    //$order             = $session->read('Order');
    $order             = $session->read('SOrder');

   
    if( !isset($order['product_id']) || empty($order['product_id']) )
    {
      $this->redirect(['controller'=> 'Pages', 'action' => 'index']);
    }
    if(!isset($order['price'] ) || empty($order['price']) ) 
    {
      return $this->redirect($order['url']);
    }
    
    $finalPrice =  $this->getVatPrice( $order['product_id'], $order['currency_id'], $order['product_type']);
  
    $language          = $this->Languages->find('all')
                                       ->where(['Languages.id' => $order['language_id']])
                                       ->select(['Languages.name'])
                                       ->first();

    $order['language'] = $language['name']; 
    $portal            = $this->Portals->find('all')
                                    ->where(['Portals.name' => 'astrowow'])
                                    ->select(['Portals.id'])
                                    ->first();

    $status            = $this->States->find('all')
                                      ->select(['States.id'])
                                      ->where(['States.name' => 'queued'])
                                      ->first();


    $currencyId        = $order['currency_id'];
    $currency          = $this->Currencies->find()
                                       ->select(['Currencies.code'])
                                       ->where(['Currencies.id' => $currencyId])
                                       ->first();



    if(!empty($this->request->is('post')))
    {
          $data                     = $this->request->data;
          $order['first_name']      = $data['profile']['first_name'];
          $order['last_name']       = $data['profile']['last_name'];
          $order['email']           = $data['username'];
          $order['portal_id']       = $portal['id']; 
          $order['currency_code']   = $currency['code'];
          $order['order_status']    = $status['id'];
        
        /* Without login */
        if(empty($this->user_id))
        {
           $guestEntity             = $this->GuestUserProductDetails->newEntity();
           $guestEntity             = $this->GuestUserProductDetails->patchEntity($guestEntity, $order);
           
           if($this->GuestUserProductDetails->save($guestEntity))
           {
              $order['guest']      = $guestEntity->id;
              if($order['product_type'] == SOFTWARE_CD)
              {
                $this->redirect(['controller' => 'Orders', 'action' => 'software-checkout-step-2' ]);      
              }
              elseif($order['product_type'] == SHAREWARE)
              {
               $this->redirect(['controller' => 'Orders', 'action' => 'software-checkout-step-3' ]); 
              }
           }
           else
           {
               $this->Flash->error(__('Unable to save data'));
           }
        }
        else
        {
            if($order['product_type'] == SOFTWARE_CD)
            {
                $this->redirect(['controller' => 'Orders', 'action' => 'software-checkout-step-2' ]);      
            }
            elseif($order['product_type'] == SHAREWARE)
            {
               $this->redirect(['controller' => 'Orders', 'action' => 'software-checkout-step-3' ]); 
            }
        }
         // $session->write('Order', $order);
          $session->write('SOrder', $order);
    }
   // $session->write('Order', $order);
    $session->write('SOrder', $order);
    $this->set('user', $this->user);
    $this->set('form', $entity);
    $this->set(compact('finalPrice'));
 }

 function softwareCheckoutStep2()
 {
    $canonical['en'] = SITE_URL.'orders/software-checkout-step-2';
    $canonical['da'] = SITE_URL.'dk/ordrer/software-kassen-trin-2';
    $meta['title'] = 'Astrowow.com - Astrology, free horoscopes, sun sign, Astrology reading, Astrology software';
    $meta['description'] = 'Astrowow.com provides free horoscopes, Astrology reading, Astrologer, Astrology Software, Astrology prediction, Daily, weekly, monthly horoscopes for aries, taurus, gemini, cancer, leo, virgo, libra, scorpio, sagittarius, capricorn, aquarius, pisces, sun sign';
    $meta['keywords'] = 'Free Astrology, Horoscope, Horoscope Reading,  Aries, taurus, gemini, cancer, leo, virgo, libra, scorpio, sagittarius, capricorn, aquarius, pisces,  daily horoscope, weekly horoscope, monthly horoscope,online astrology';
    $this->set(compact('canonical', 'meta'));

      $entity        = $this->Orders->newEntity();
      $session       = $this->request->session();
      //$order         = $session->read('Order');
      $order         = $session->read('SOrder');
     
      if( !isset($order['product_id']) || empty($order['product_id']) )
      {
        $this->redirect(['controller'=> 'Pages', 'action' => 'index']);
      }

     $finalPrice =  $this->getVatPrice( $order['product_id'], $order['currency_id'], $order['product_type']);

     $countryOptions = $this->Countries->find('list',
            [

                'order' => [ 'name' => 'ASC']
            ]

         )->toArray();
    
    if($this->request->is('post'))
    {
     
       $this->request->session()->write('shipping_data', $this->request->data);
       $this->redirect(['controller' => 'Orders', 'action' => 'software-checkout-step-3' ]);
    }
    $this->set('user', $this->user);
    $this->set('countryOptions',$countryOptions);
    $this->set('form', $entity);
    $this->set(compact('finalPrice'));
 }

 function softwareCheckoutStep3()
 {
      $canonical['en'] = SITE_URL.'orders/software-checkout-step-3';
      $canonical['da'] = SITE_URL.'dk/ordrer/software-kassen-trin-3';
      $meta['title'] = 'Astrowow.com - Astrology, free horoscopes, sun sign, Astrology reading, Astrology software';
      $meta['description'] = 'Astrowow.com provides free horoscopes, Astrology reading, Astrologer, Astrology Software, Astrology prediction, Daily, weekly, monthly horoscopes for aries, taurus, gemini, cancer, leo, virgo, libra, scorpio, sagittarius, capricorn, aquarius, pisces, sun sign';
      $meta['keywords'] = 'Free Astrology, Horoscope, Horoscope Reading,  Aries, taurus, gemini, cancer, leo, virgo, libra, scorpio, sagittarius, capricorn, aquarius, pisces,  daily horoscope, weekly horoscope, monthly horoscope,online astrology';
      $this->set(compact('canonical', 'meta'));

      $entity        = $this->Orders->newEntity();
      $session       = $this->request->session();
      //$order         = $session->read('Order');
      $order         = $session->read('SOrder');

      if( !isset($order['product_id']) || empty($order['product_id']) )
      {
        $this->redirect(['controller'=> 'Pages', 'action' => 'index']);
      }

      if(!$this->validateDataForPaymentGateway($order))
      {
        $this->Flash->error(__('Unable to process your request'));
        $this->redirect($order['url']);
      }

      $finalPrice =  $this->getVatPrice( $order['product_id'], $order['currency_id'], $order['product_type']);

      $orderId       = $this->getOrderIdByProductType($order['type']);
      $order['payer_order_id'] = $orderId;
      //$session->write('Order', $order);
      $session->write('SOrder', $order);
      $this->set('orderId', $orderId);
      $this->set('form', $entity);
      $this->set('currency_code', $order['currency_code']);
      $this->set(compact('finalPrice'));
 }
 
 function softwareThankYou()
 {
    $canonical['en'] = SITE_URL.'orders/software-thank-you';
    $canonical['da'] = SITE_URL.'dk/ordrer/software-tak';
    $meta['title'] = 'Astrowow.com - Astrology, free horoscopes, sun sign, Astrology reading, Astrology software';
    $meta['description'] = 'Astrowow.com provides free horoscopes, Astrology reading, Astrologer, Astrology Software, Astrology prediction, Daily, weekly, monthly horoscopes for aries, taurus, gemini, cancer, leo, virgo, libra, scorpio, sagittarius, capricorn, aquarius, pisces, sun sign';
    $meta['keywords'] = 'Free Astrology, Horoscope, Horoscope Reading,  Aries, taurus, gemini, cancer, leo, virgo, libra, scorpio, sagittarius, capricorn, aquarius, pisces,  daily horoscope, weekly horoscope, monthly horoscope,online astrology';
    $this->set(compact('canonical', 'meta'));

  // Default status for software is processing
       $session               = $this->request->session();
       //$order                 = $session->read('Order');
       $order                 = $session->read('SOrder');
   if(!empty($order['product_id'])) 
   {
       
       $orderEntity           = $this->Orders->newEntity();
       $firstName             = $order['first_name'];
       $lastName              = $order['last_name'];
       $fullName              = $this->getFullName($firstName, $lastName);
       $email                 = $order['email'];
       $price                 = $order['price'];
       $currencyCode          = $order['currency_code'];
       $shipping              = $session->read('shipping_data');
       $order['order_date']   = date('Y-m-d h:i:s');
       $order['confirm_payment_date'] = date('Y-m-d h:i:s'); 
       $priceDetail           = explode(' ', $price);      
       $order['price']        =  $priceDetail[1];
      
     if(!empty($this->user_id))
     {
      $shipping['user_id']   = $this->user_id;
      $order['user_id']      = $this->user_id;
     }
     else
     {
       $shipping['user_id']  = 0; 
       $order['user_id']     = 0;
       $guestEntity          = $this->GuestUserProductDetails->get($order['guest']);
       $guest['payment_status'] = 1;
       $guestEntity          = $this->GuestUserProductDetails->patchEntity($guestEntity, $guest);
       if(!$this->GuestUserProductDetails->save($guestEntity))
       {
           $this->Flash->error(__('Unable to save Data'));
       }

     }
      $orderEntity           = $this->Orders->patchEntity($orderEntity, $order);

    if($this->Orders->save($orderEntity))
    {
        $orderId              = $orderEntity->id;
        $shipping['order_id'] = $orderId;
        $shippingEntity       = $this->OrderShippings->newEntity();
        $shippingEntity       = $this->OrderShippings->patchEntity($shippingEntity, $shipping);
        $transactionEntity    = $this->saveTransactionData($orderId, $firstName, $lastName, $email, $order['price'], $currencyCode, $order['payer_order_id']);

      if($this->OrderShippings->save($shippingEntity) && $this->OrderTransactions->save($transactionEntity))
      {
        $recipient          = $order['email'];
        $orderText = ((strtolower( substr( I18n::locale(), 0, 2)) == 'da')) ? 'Tak for din Bestilling' : 'Thank you for Ordering';  
        $orderData          = [
                                'subject'      => $orderText."‏ ". $order['product_name'],
                                'name'         => $fullName,
                                'username'     => $order['email'],
                                'product_id'   => $order['product_id'],
                                'product_name' => $order['product_name'],
                                'category_slug' => $order['category']
                              ];

        $emailTemplate      = new EmailTemplatesController();

        if(strtolower(trim($order['type'])) == 'cd')
        {
         $send               =  $emailTemplate->orderConfirmationforSoftwareCD($recipient, $orderData);

         /** This is used to send instruction email**/
         // $orderData          = [
         //                          'subject'      => "Instructions for Astrology Software C.D Version",
         //                          'name'         => $fullName,
         //                          'username'     => $order['email'],
         //                          'product_id'   => $order['product_id'],
         //                          'product_name' => $order['product_name'],
         //                          'category_slug' => $order['category']
         //                       ];
         // $send               =  $emailTemplate->instructionForSoftwareCD($recipient, $orderData);

        }
        elseif(strtolower(trim($order['type'])) == 'shareware')
        {
          $send              =  $emailTemplate->orderConfirmationforShareware($recipient, $orderData);
          $send              =  $emailTemplate->orderConfirmationforSoftwareCD($recipient, $orderData);
        }
       
        // $session->delete('Order');
         $session->delete('SOrder');
         $session->delete('shipping_data');
      }
      else
      {
        $this->Flash->error(__('Unable to save data'));
      }
    }
    
    else
    {
       $this->Flash->error(__('Unable to save data'));
    }
    $this->set('order_id', $this->request->query('orderid'));
    $this->set('txnid', $this->request->query('txnid'));
    //if ( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' ) {
        $this->loadModel('ProductTypes');
        $orderDetail = $this->Orders->find()
                                        ->join([
                                              'product_types' => [
                                                  'table' => 'product_types',
                                                  'type' => 'INNER',
                                                  'conditions' => [
                                                      'product_types.id = Orders.product_type',
                                                  ] 
                                              ]
                                        ])
                                ->select([ 'Orders.id', 'Orders.product_type', 'product_types.id', 'product_types.name'])
                                ->where(['Orders.id' => $orderEntity->id])
                                ->first();

        $msgBody = $this->softwareAdminMail ($orderEntity->id, $price, $this->request->query('txnid'), $order['product_name'], $order['first_name'].' '.$order['last_name'], $order['payer_order_id'], $order['email'], $orderDetail['product_types']['name'], $order['language']);
              $emailTemplate->sendMailToAdmin($order['product_name'].' '.$orderDetail['product_types']['name'].' order received', $msgBody);
    //}
  }
  else
  {
       $this->redirect(['controller' => 'pages', 'action' => 'index']);
  }   
    
 }


    protected function softwareAdminMail ($order_id, $price, $transaction_id, $productname, $name, $payerOrderId, $email, $productType, $softLanguage) {
      /*if ( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' ) {
        echo '<pre>'; print_r($this->request->session()->read());
        echo '<br><br><br>'.$order_id.' => '.$price.' => '.$transaction_id.' => '.$productname.' => '.$name.' => '.$payerOrderId.' => '.$email.' => '.$productType,' => '.$softLanguage; die;
      }*/
      $this->loadModel('OrderShippings');
      $this->loadModel('Countries');
      //$this->loadModel('Orders');
      //$this->loadModel('ProductTypes');
      $shippingAdd = $this->OrderShippings->find()->where(['order_id' => $order_id])->first();
      $address = '';
      $address = !empty($shippingAdd["address_1"]) ? $shippingAdd["address_1"].', ' : $address;
      $address = !empty($shippingAdd["address_2"]) ? $address.$shippingAdd["address_2"].', ' : $address;
      $address = !empty($shippingAdd["city"]) ? $address.$shippingAdd["city"].', ' : $address;
      $address = !empty($shippingAdd["state"]) ? $address.$shippingAdd["state"].', ' : $address;
      $address = !empty($shippingAdd["name"]) ? $address.$shippingAdd["name"].', ' : $address;
      //$address = !empty($shippingAdd["postal_code"]) ? $address.$shippingAdd["postal_code"].', ' : $address;
      $address = rtrim($address, ", ");
      $address = rtrim($address, ",");
      //$address = !empty($address) ? $address : 'N/A';
      //$address = $shippingAdd["address_1"].', '.$shippingAdd["address_2"].', '.$shippingAdd["city"].', '.$shippingAdd["state"].', '.$shippingAdd["postal_code"].', '.$countryNme["name"];
      $countryNme = $this->Countries->find()->where(['id' => $shippingAdd['country']])->select(['name'])->first();
      $session_val = $this->request->session();
      $template = '<table style="width: 800px;">
                            <tr><td>Hi,</td></tr>
                            <tr><td>&nbsp;</td></tr>
                            <tr><td>You have received a '.$productType.' order. Order details are mentioned below: </td></tr>
                          </table>
                          <table style="border: 1px solid #e4165b; width: 800px;">
                            <tbody>
                              <tr>
                                <td><h3 style="color: #fff; text-align: center; background-color: #e4165b; border-color: #337ab7;" class="panel-title">'.$productType.' order details</h3></td>
                              </tr>
                              <tr>
                                <td>
                                  <table>
                                    <tr>
                                      <td>Buyer Name : </td>
                                      <td>'.$name.'</td>
                                    </tr>
                                    <tr>
                                      <td>Email : </td>
                                      <td>'.$email.'</td>
                                    </tr>
                                    <tr>
                                      <td>Language : </td>
                                      <td>'.$softLanguage.'</td>
                                    </tr>
                                    <tr>
                                      <td>Software Name : </td>
                                      <td>'.$productname.'</td>
                                    </tr>
                                    <tr>
                                      <td>Order Date : </td>
                                      <td>'.date('d-m-Y h:i:sa').'</td>
                                    </tr>
                                    <tr>
                                      <td>Order No. : </td>
                                      <td>'.$payerOrderId.'</td>
                                    </tr>
                                    <tr>
                                      <td>Amount : </td>
                                      <td>'.str_replace(" ", "", $price).'</td>
                                    </tr>
                                    <tr>
                                      <td>Transaction Id : </td>
                                      <td>'.$transaction_id.'</td>
                                    </tr>';
          if (!empty($address)) {
            $template .= '<tr>
                            <td>Shipping Address : </td>
                            <td>'.$address.'</td>
                          </tr>';
          }
          if (!empty($shippingAdd["postal_code"])) {
            $template .= '<tr>
                            <td>Postal Code : </td>
                            <td>'.$shippingAdd["postal_code"].'</td>
                          </tr>';
          }
          if (!empty($shippingAdd["phone"])) {
            $template .= '<tr>
                            <td>Phone No. : </td>
                            <td>'.$shippingAdd["phone"].'</td>
                          </tr>';
          }
          $template .= '</table>
                                </td>
                              </tr>
                            </tbody>
                          </table>
                    <br><p>Thanks,</p><p>AstroWow Team</p>';
          return $template;
    }




  public function consultation()
    {

      $canonical['en'] = SITE_URL.'orders/consultation';
      $canonical['da'] = SITE_URL.'dk/ordrer/konsultation';
      $meta['title'] = 'Astrowow.com - Astrology, free horoscopes, sun sign, Astrology reading, Astrology software';
      $meta['description'] = 'Astrowow.com provides free horoscopes, Astrology reading, Astrologer, Astrology Software, Astrology prediction, Daily, weekly, monthly horoscopes for aries, taurus, gemini, cancer, leo, virgo, libra, scorpio, sagittarius, capricorn, aquarius, pisces, sun sign';
      $meta['keywords'] = 'Free Astrology, Horoscope, Horoscope Reading,  Aries, taurus, gemini, cancer, leo, virgo, libra, scorpio, sagittarius, capricorn, aquarius, pisces,  daily horoscope, weekly horoscope, monthly horoscope,online astrology';
      $this->set(compact('canonical', 'meta'));

      // $entity       = $this->Orders->newEntity();

      // if($this->request->is('post'))
      // {

      //    $category = $this->Categories->find('all') 
      //                                 ->where(['Categories.id' => $this->request->data['category_id']])
      //                                 ->select(['Categories.slug'])
      //                                 ->first();
       
      //    $this->request->data['category'] = $category['slug'];
      //    $this->request->data['product_type'] = CONSULTATION;
      //    $this->request->data['order_id'] = $this->getOrderIdByProductType('consult');
      //    $this->request->session()->write('Order', $this->request->data);
      //    if(!$this->request->session()->read('user_id'))
      //    {
      //      $order        = $this->request->session()->read('Order'); 
      //      $order['cancel_url'] = $order['url'];
      //      $order['url'] = ['controller' => 'Orders', 'action' => 'consultation-checkout'];
      //      $this->request->session()->write('Order' , $order);
      //      $this->redirect(['controller' => 'Users', 'action' => 'login']);
      //    }
      //    else
      //    {
      //      $this->redirect(['controller' => 'Orders', 'action' => 'consultation-checkout']);
      //    }
      // }
      // else
      // {

      //  if( strtolower(I18n::locale()) == 'en_us' )
      //  {
      //     $CurrencyData = $this->Currencies->find('all', ['conditions' => ['Currencies.status' =>  1, 'Currencies.code IN' => ['USD', 'GBP', 'EUR'] ] ])->toArray();
      //      $priceInfo          = $this->getProductPrice(1, SKYPE_CONSULTATION) ;
      //      $data['categoryId'] = SKYPE_CONSULTATION;
      //  }
      //  elseif(strtolower(I18n::locale()) == 'da' )
      //  { // For DK
      //      $CurrencyData = $this->Currencies->find('all', ['conditions' => ['Currencies.status' =>  1, 'Currencies.code IN' => ['DKK'] ] ])->toArray();
      //      $priceInfo          = $this->getProductPrice(3, OFFICE_CONSULTATION) ;
      //      $data['categoryId'] = OFFICE_CONSULTATION;

      //  }
        
      //   foreach ( $CurrencyData as $currency ) 
      //   {  
      //     $currencyOptions[] = ['value' => $currency['id'], 'text' => $currency['name'].'('.$currency['symbol'].')','class' =>'skype-radio']  ;
      //   }
     
      //    $categoryOptions    = $this->Categories->find('list')
      //                                        ->where(['Categories.status' => 1, 'Categories.slug IN' => ['skype-consultation', 'office-consultation',  'questions-skype']])
      //                                        ->toArray();



      //    $this->set(compact('currencyOptions', 'data'));
      //    $this->set(compact('priceInfo', 'categoryOptions'));
      //    $this->set('form', $entity);
      // }

    }  

    public function consultationCheckout()
    {
      $canonical['en'] = SITE_URL.'orders/consultation-checkout';
      $canonical['da'] = SITE_URL.'dk/ordrer/konsultation-kassen';
      $meta['title'] = 'Astrowow.com - Astrology, free horoscopes, sun sign, Astrology reading, Astrology software';
      $meta['description'] = 'Astrowow.com provides free horoscopes, Astrology reading, Astrologer, Astrology Software, Astrology prediction, Daily, weekly, monthly horoscopes for aries, taurus, gemini, cancer, leo, virgo, libra, scorpio, sagittarius, capricorn, aquarius, pisces, sun sign';
      $meta['keywords'] = 'Free Astrology, Horoscope, Horoscope Reading,  Aries, taurus, gemini, cancer, leo, virgo, libra, scorpio, sagittarius, capricorn, aquarius, pisces,  daily horoscope, weekly horoscope, monthly horoscope,online astrology';
      $this->set(compact('canonical', 'meta'));

      $session = $this->request->session();
      $order   = $session->read('Order');

      if( !empty($order['order_id']) )
      {
        $this->set('order', $session->read('Order'));
      }
      else
      {
         $this->redirect(['controller' => 'pages', 'action' => 'index']);
      }

    }

    public function getProductPrice($currency_id = 0, $category_id = 0 ) {
       if ( $this->request->is('post') ) { 
          $this->viewBuilder()->layout('ajax');
          $this->autoRender = false; 
          $category_id      = $this->request->data['category'];
          $currency_id      = $this->request->data['currency'];
          $locale           = $this->request->data['locale'];

          if($locale == 'da')
          {
            I18n::locale('da');
          }
       }


      $data = $this->Products->find()
                             ->hydrate(false)
                             ->join([

                                'ProductPrices' => [
                                      'table' => 'product_prices',
                                      'type' => 'INNER',
                                      'conditions' => [
                                            'ProductPrices.product_id = Products.id',
                                      ] 
                                  ],

                                  'currency' => [
                                      'table' => 'currencies',
                                      'type' => 'INNER',
                                      'conditions' => [
                                          'currency.id ' => $currency_id,
                                          'currency.id = ProductPrices.currency_id',
                                      ] 
                                  ]

                              ])

                             ->where(['Products.category_id' => $category_id])
                             ->select(['ProductPrices.total_price' , 'ProductPrices.discount_total_price', 'currency.code', 'currency.id', 'currency.symbol', 'Products.id', 'Products.name'])
                             ->toArray();

           if ( $this->request->is('post') ) 
           { 
              echo json_encode($data);
              exit;
           }     
            return $data;
    }


    public function consultationThankYou()
    {
      $canonical['en'] = SITE_URL.'orders/consultation-thank-you';
      $canonical['da'] = SITE_URL.'dk/ordrer/konsultation-tak';
      $meta['title'] = 'Astrowow.com - Astrology, free horoscopes, sun sign, Astrology reading, Astrology software';
      $meta['description'] = 'Astrowow.com provides free horoscopes, Astrology reading, Astrologer, Astrology Software, Astrology prediction, Daily, weekly, monthly horoscopes for aries, taurus, gemini, cancer, leo, virgo, libra, scorpio, sagittarius, capricorn, aquarius, pisces, sun sign';
      $meta['keywords'] = 'Free Astrology, Horoscope, Horoscope Reading,  Aries, taurus, gemini, cancer, leo, virgo, libra, scorpio, sagittarius, capricorn, aquarius, pisces,  daily horoscope, weekly horoscope, monthly horoscope,online astrology';
      $this->set(compact('canonical', 'meta'));

       $session = $this->request->session();
       $checkSession = $session->read('Order');
     
      if( !empty($checkSession['order_id']) )
      {        $status     = $this->States->find('all')
                                          ->select(['States.id'])
                                          ->where(['States.name' => 'queued'])
                                          ->first();

               $deliveryOption  = $this->DeliveryOptions->find('all')
                                                        ->where(['DeliveryOptions.slug' => 'skype'])
                                                        ->select(['DeliveryOptions.id'])
                                                        ->first();
               $orderEntity = $this->Orders->newEntity();    

         if($session->read('user_id'))
         {
                   $order      =  $session->read('Order');
                   $user_id    =  $session->read('user_id');
                   $userDetail =  $this->Users->find('all')
                                              ->contain(['Profiles'])
                                              ->where(['Users.id' => $user_id])
                                              ->select(['Users.username', 'Profiles.first_name', 'Profiles.last_name'])
                                              ->first();
                   $order['user_id']              = $user_id;
                   $order['email']                = $userDetail['username'];
                   $firstName                     = $userDetail['profile']['first_name'];
                   $lastName                      = $userDetail['profile']['last_name']; 
                   $order['order_date']           = date('Y-m-d h:i:s');
                   $order['confirm_payment_date'] = date('Y-m-d h:i:s'); 
                   $order['order_status']         = $status['id'];
                   $order['delivery_option']      = $deliveryOption['id'];
                  // $order['language_id']          = 1; 
                   $fullName                      = $this->getFullName($firstName, $lastName);
                   $order['payer_order_id']       = $order['order_id'];
                   $orderEntity                   = $this->Orders->patchEntity($orderEntity, $order);
            if($this->Orders->save($orderEntity))
            {
                $orderId              = $orderEntity->id;
                $transactionEntity    = $this->saveTransactionData($orderId, $firstName, $lastName, $order['email'], $order['price'], $order['currencyCode'], $order['order_id']);

              if($this->OrderTransactions->save($transactionEntity))
              {
                          
                            $recipient          = $order['email'];
                            $orderText = ((strtolower( substr( I18n::locale(), 0, 2)) == 'da')) ? 'Tak for din Bestilling' : 'Thank you for Ordering';  
                            $orderData          = 
                               [
                                'subject'       => $orderText."‏ ".$order['product_name'],
                                'name'          => $fullName,
                                'username'      => $order['email'],
                                'product_id'    => $order['product_id'],
                                'product_name'  => $order['product_name'],
                                'mailText'      => $orderText."‏ ".$order['product_name']
                              ];
                            
                  $emailTemplate      = new EmailTemplatesController();
                  $send               =  $emailTemplate->orderConfirmationforSkype($recipient, $orderData);

              }

              $session->delete('Order');
            }  
         }
      }
      else
      {
        $this->redirect(['controller' => 'Pages', 'action' => 'index']);
      }
       $this->set('order_id', $this->request->query('orderid'));
       $this->set('txnid', $this->request->query('txnid'));
       $msgBody = $this->consultationAdminMail ($orderEntity->id, $order['product_name'], $order['price'], $this->request->query('txnid'), $order['language_id'], $firstName.' '.$lastName, $order['email']);
       $emailTemplate->sendMailToAdmin($order['product_name'].' order received', $msgBody);
    }


    protected function consultationAdminMail ($order_id, $productname, $price, $transaction_id, $language_id, $name, $email) {
      $this->loadModel('Orders');
      $this->loadModel('Users');
      $orderDetail = $this->Orders->find()->where(['id' => $order_id])->first();
      $currency = $this->tableData("Currencies", $orderDetail['currency_id'], 'symbol');
      $user = $this->Users->find()->where(['id' => $orderDetail['user_id']])->first();
      $language = $this->tableData("Languages", $language_id, "name");
      $session_val = $this->request->session();
      return $template = '<table>
                          <tr><td>Hi,</td></tr>
                          <tr><td>&nbsp;</td></tr>
                          <tr><td>You have received a consultation order. Order details are mentioned below: </td></tr>
                      </table>
                      <table style="border: 1px solid #e4165b; width: 800px;">
                        <tbody>
                          <tr>
                            <td>
                              <h3 style="color: #fff; text-align: center; background-color: #e4165b; border-color: #337ab7;" class="panel-title">consultation order details</h3>
                            </td>
                          </tr>
                          <tr>
                            <td>
                              <table>
                                <tr>
                                  <td>User Name : </td>
                                  <td>'.ucwords($name).'</td>
                                </tr>
                                <tr>
                                  <td>Email : </td>
                                  <td>'.$email.'</td>
                                </tr>
                                <tr>
                                  <td>Product Name : </td>
                                  <td>'.$productname.'</td>
                                </tr>
                                <tr>
                                  <td>Language : </td>
                                  <td>'.$language["name"].'</td>
                                </tr>
                                <tr>
                                  <td>Order Date : </td>
                                  <td>'.date('d-m-Y h:i:sa').'</td>
                                </tr>
                                <tr>
                                  <td>Order No. : </td>
                                  <td>'.$orderDetail['payer_order_id'].'</td>
                                </tr>
                                <tr>
                                  <td>Amount : </td>
                                  <td>'.$currency["symbol"].$price.'</td>
                                </tr>
                                <tr>
                                  <td>Transaction Id : </td>
                                  <td>'.$transaction_id.'</td>
                                </tr>
                              </table>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                      <br><p>Thanks,</p><p>AstroWow Team</p>';
    }

}

?>