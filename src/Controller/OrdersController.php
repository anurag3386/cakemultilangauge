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
    $this->loadModel('TempOrderShippings');
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

            if($this->checkIp()) {
              if(!empty($this->request->session()->read('product_id'))){
                if($this->request->session()->read('product_id') == $this->request->session()->read('Order.product_id')) {
                  $this->request->session()->write('userOrderStatus', 'old'); // old order
                } else {
                  $this->request->session()->write('product_id', $this->request->session()->read('Order.product_id'));
                  $this->request->session()->write('userOrderStatus', 'new'); // new order
                }
              } else {
                $this->request->session()->write('product_id', $this->request->session()->read('Order.product_id'));
                $this->request->session()->write('userOrderStatus', 'new'); // new order
              }
            }
                


            

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
                //$this->request->session()->write('product_id', $product_id);
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
                 //if ($this->checkIp()) {
                     // $user =  $this->Users->findByUsername($email)->first();
                     $user = $this->Users->find('all')->where(['username' => $email])->contain(['Profiles', 'BirthDetails'])->first();
                     $is_elite = $user['role'];
                     if( !empty($user) ):
                        if($is_elite!='elite'){
                            //changed by anurag dubey as per client requirement dated 09/nov/2017   
                            /*$this->Flash->error(__('Email ID already exists - please log in'));
                          
                            // if ($this->checkIp()) {
                            //   $this->Flash->error(__('Email ID already exists - please log in'));
                            // }
    
                            return $this->redirect([ 'controller' => 'Orders', 'action' => 'checkout-step-1']);*/
                            $this->request->session()->write('first_name',  $this->request->data['profile']['first_name']);
                            $this->request->session()->write('last_name', $this->request->data['profile']['last_name']);
                            $this->request->session()->write('username', $this->request->data['username']);
                            $this->request->session()->write('guest_user', $user);
                            return $this->redirect([ 'controller' => 'Orders', 'action' => 'checkout-step-2']);
                        } else {
                            $this->request->session()->delete('Order');
                            $this->Flash->error(__('You are an elite user. Please login to proceed'));
                            return $this->redirect([ 'controller' => 'Users', 'action' => 'login']);
                        }
                      else:
                            $this->request->session()->write('first_name',  $this->request->data['profile']['first_name']);
                            $this->request->session()->write('last_name', $this->request->data['profile']['last_name']);
                            $this->request->session()->write('username', $this->request->data['username']);
                            return $this->redirect([ 'controller' => 'Orders', 'action' => 'checkout-step-2']); 
                     endif;
                  
                /*} else {

                     if( !empty($user) ):
                           
                        $this->Flash->error(__('Email id is already exists'));
                        return $this->redirect([ 'controller' => 'Orders', 'action' => 'checkout-step-1']);
                     else:
                        $this->request->session()->write('first_name',  $this->request->data['profile']['first_name']);
                        $this->request->session()->write('last_name', $this->request->data['profile']['last_name']);
                        $this->request->session()->write('username', $this->request->data['username']);
                        return $this->redirect([ 'controller' => 'Orders', 'action' => 'checkout-step-2']); 
                     endif;
                }*/


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
  //if($this->checkIp()) {
      if(empty($this->request->session()->read('username'))){
          return $this->redirect( ['controller'=> 'Orders', 'action' => 'checkout-step-1'] );       
      }
  //}
  if( empty($order['product_id']) )
  {
      return $this->redirect( ['controller'=> 'Pages', 'action' => 'index'] );
  }
  if(!isset($order['price'] ) || empty($order['price']) ) 
    {
      return $this->redirect($order['url']);
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
                       
                           
                           // if( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' || $_SERVER['REMOTE_ADDR'] == '103.248.117.10') {
                              if ($order['product_id'] == 19) {
                                $orderData['age'] = !empty($this->request->data['age']) ? $this->request->data['age'] : 0;
                              }
                            //}
                           $order = array_merge($order, $orderData);
                           $order['email'] = $this->request->data['username'];
                          $this->request->session()->write('Order', $order);
                          return $this->redirect([ 'controller' => 'Orders', 'action' => 'checkout-step-3']);
                   endif;         
              endif;
  else:
                  /* Without login */
                  if( $this->request->is('post') ):
                            
                         $sess_order_minutes = $this->request->session()->read('Order.birth_time');   
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

                           //if($this->checkIp()) { 
                              
                              //if($this->checkIp()) {
                                  if(empty($sess_order_minutes)) {
                                      if ($this->request->data['hours'] >= 0) {
                                        //echo 'Set => <pre>'; print_r($this->request->data); die;
                                        if($this->request->data['minutes']) {
                                        } else {
                                          $this->request->data['minutes'] = 00;
                                        }
                                        $reportData['birth_time'] = $this->request->data['hours'].":".$this->request->data['minutes'];
                                      } else {
                                        //echo 'Not Set => <pre>'; print_r($this->request->data); die;
                                        $this->request->data['hours'] = $this->request->data['minutes'] = 00;
                                        //echo '<pre>'; print_r($this->request->data); die;
                                        $reportData['birth_time'] = $this->request->data['hours'].":".$this->request->data['minutes'];
                                      }
                                  } else {
                                      $reportData['birth_time'] = $sess_order_minutes;
                                  }
                              //}
                                
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
                                 if(!empty($sess_order_minutes)){   
                                    $reportData['birth_time'] = $sess_order_minutes;
                                 } else {
                                    $reportData['birth_time'] = $this->request->data['hours'].":".$this->request->data['minutes'];
                                 }
                                 $reportData['country_id'] = $this->request->data['birth_detail']['country_id'];
                                 $reportData['city_id'] = $this->request->data['birth_detail']['city_id'];
                                 $reportData['first_name'] = $this->request->data['profile']['first_name'];
                                 $reportData['last_name'] = $this->request->data['profile']['last_name'];
                                 $reportData['name_on_report'] = $this->request->data['name_on_report'];
                                 $reportData['gender'] = $this->request->data['gender'];

                                  //if( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' || $_SERVER['REMOTE_ADDR'] == '103.248.117.10' ) {
                                    if ($order['product_id'] == 19) {
                                      $orderData['age'] = !empty($this->request->data['age']) ? $this->request->data['age'] : 0;
                                    }
                                  //}

                                 $this->request->session()->write('Order', array_merge($order, $reportData));

                                 $this->Flash->error(__('Please select city from the dropdown'));
                                 return $this->redirect( Router::url($this->here, true ) );
                            }

                            // to resolve validation conflict
                            $this->request->data['profile']['gender'] = $this->request->data['gender'];
                            $user = $this->request->session()->read('guest_user');
                         
                            if(!isset($user) ||  empty($user)) {
                                        $data = $this->request->data;

                                        
                                        $data['birth_date'] = $this->formatDateForDb($data['birth_date']) ;
                                       
                                        $user = $this->Users->newEntity();
                                        
                                        $data['is_guest'] = 1;
                                        $date = explode( '-', $data['birth_date'] );
                                        $data['birth_detail']['date'] = date('Y-m-d', strtotime(trim($date[0])) );
                                                                       
                                        if(isset($date[1]))
                                        {
                                          $data['birth_detail']['day'] = $date[1];
                                        }
                                        else
                                        {
                                         $data['birth_detail']['day'] = ''; 
                                        }

                                        $mnth =  date('m', strtotime(trim($date[0])));
                                        $day  =  date('d', strtotime(trim($date[0])));
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
                                        //if($this->checkIp()) {
                                          $data['is_guest'] = 1;
                                        //}

                                        
                                        $user = $this->Users->patchEntity($user, $data , ['associated' => ['Profiles', 'BirthDetails']] );
                                      /*if( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' || $_SERVER['REMOTE_ADDR'] == '103.248.117.10') {
                                          pr ($user); die;
                                      }*/


                                    if( $this->Users->save($user) ):
                                      
                                      $loggedinUserData['User']['username'] = $user['username'];
                                      $loggedinUserData['User']['role'] = 'user';
                                      $loggedinUserData['UserProfile']['first_name'] = $user['profile']['first_name'];
                                      $loggedinUserData['UserProfile']['last_name'] = $user['profile']['last_name'];
                                      $loggedinUserData['UserProfile']['user_id'] = $user->id;
                                      $loggedinUserData['UserProfile']['gender'] = $user['profile']['gender'];
                                      $loggedinUserData['UserProfile']['country_id'] = $user['birth_detail']['country_id'];
                                      $loggedinUserData['UserProfile']['city_id'] = $user['birth_detail']['city_id'];
                                      $loggedinUserData['UserProfile']['language_id'] = $user['profile']['language_id'];
                                      $loggedinUserData['BirthDetails']['user_id'] = $user->id;
                                      $loggedinUserData['BirthDetails']['country_id'] = $user['birth_detail']['country_id'];
                                      $loggedinUserData['BirthDetails']['city_id'] = $user['birth_detail']['city_id'];
                                      $this->request->session()->write('Auth', $loggedinUserData);
                                      $this->request->session()->write('selectedUser', $user->id);

                                      $this->request->session()->write('user_id', $user->id);
                                      $recipient = $data['username'];
                                      $mailData      = [
                                                            'subject'     => __("Welcome to Astrowow.com‏"),
                                                            'mailtext'    => "<h1>Hello $fullname,</h1>",
                                                            'password'    => $password,
                                                            'username'    => $data['username'],
                                                            'name'        => $fullname
                                                     ];

                                      $emailTemplate = new EmailTemplatesController();
                                      $send =  $emailTemplate->sendWelcomeEmailOnSignup($recipient, $mailData);
                                      $order['birth_date'] = date('Y-m-d', strtotime(trim($date[0])));
                                      $order['birth_time'] = date("H:i", strtotime($data['hours'] .":". $data['minutes']));
                                      $order['country_id'] = $data['birth_detail']['country_id'];
                                      $order['city_id'] = $data['birth_detail']['city_id'];
                                      $order['first_name'] = ucwords($this->request->session()->read('first_name'));
                                      $order['last_name'] = ucwords($this->request->session()->read('last_name'));
                                      $order['name_on_report'] = ucwords($this->request->data['name_on_report']);
                                      $order['gender'] = $data['profile']['gender'];
                                      $order['email'] = $data['username'];
                                      if(isset($data['age']) && !empty($data['age'])) {
                                        $order['age'] = $data['age'];
                                      } else {
                                        $order['age'] = 0;
                                      }

                                        
                                      $this->request->session()->write('Order', $order);
                                      /*if( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' || $_SERVER['REMOTE_ADDR'] == '103.248.117.10') {
                                            pr ($this->request->session()->read('Order')); die;
                                      }*/
                                      return $this->redirect([ 'controller' => 'Orders', 'action' => 'checkout-step-3']);
                                endif;
                  } else {
                    
                                      $data = $this->request->data;
                                      
                                      $date = explode( '-', $data['birth_date'] );
                                      
                                      $loggedinUserData['User']['username'] = $user['username'];
                                      $loggedinUserData['User']['role'] = 'user';
                                      $loggedinUserData['UserProfile']['first_name'] = $user['profile']['first_name'];
                                      $loggedinUserData['UserProfile']['last_name'] = $user['profile']['last_name'];
                                      $loggedinUserData['UserProfile']['user_id'] = $user->id;
                                      $loggedinUserData['UserProfile']['gender'] = $user['profile']['gender'];
                                      $loggedinUserData['UserProfile']['country_id'] = $user['birth_detail']['country_id'];
                                      $loggedinUserData['UserProfile']['city_id'] = $user['birth_detail']['city_id'];
                                      $loggedinUserData['UserProfile']['language_id'] = $user['profile']['language_id'];
                                      $loggedinUserData['BirthDetails']['user_id'] = $user->id;
                                      $loggedinUserData['BirthDetails']['country_id'] = $user['birth_detail']['country_id'];
                                      $loggedinUserData['BirthDetails']['city_id'] = $user['birth_detail']['city_id'];
                                     // $this->request->session()->write('Auth', $loggedinUserData);
                                      $this->request->session()->write('selectedUser', $user->id);

                                     // $this->request->session()->write('user_id', $user->id);
                                      // $recipient = $data['username'];
                                      // $mailData      = [
                                      //                       'subject'     => __("Welcome to Astrowow.com‏"),
                                      //                       'mailtext'    => "<h1>Hello $fullname,</h1>",
                                      //                       'password'    => $password,
                                      //                       'username'    => $data['username'],
                                      //                       'name'        => $fullname
                                      //                ];

                                      // $emailTemplate = new EmailTemplatesController();
                                      // $send =  $emailTemplate->sendWelcomeEmailOnSignup($recipient, $mailData);

                                      $date[0] = $this->formatDashDateForDb(trim($date[0])); //Added by Anurag Dubey on nov 09 2017
                                       
                                      $order['birth_date'] = date('Y-m-d', strtotime(trim($date[0])));
                                      $order['birth_time'] = date("H:i", strtotime($data['hours'] .":". $data['minutes']));
                                      $order['country_id'] = $data['birth_detail']['country_id'];
                                      $order['city_id'] = $data['birth_detail']['city_id'];
                                      $order['first_name'] = ucwords($this->request->session()->read('first_name'));
                                      $order['last_name'] = ucwords($this->request->session()->read('last_name'));
                                      $order['name_on_report'] = ucwords($this->request->session()->read('first_name')." ".$this->request->session()->read('last_name'));
                                      $order['gender'] = $data['profile']['gender'];
                                      $order['email'] = $data['username'];
                                      if(isset($data['age']) && !empty($data['age'])) {
                                        $order['age'] = $data['age'];
                                      } else {
                                        $order['age'] = 0;
                                      }
                                      
                                        
                                      $this->request->session()->write('Order', $order);
                                     
                                      /*if( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' || $_SERVER['REMOTE_ADDR'] == '103.248.117.10') {
                                            pr ($this->request->session()->read('Order')); die;
                                      }*/
                                      return $this->redirect([ 'controller' => 'Orders', 'action' => 'checkout-step-3']);

                  }



                           /*} else {

                              //if($this->checkIp()) {
                                  if ($this->request->data['hours'] > 0) {
                                    //echo 'Set => <pre>'; print_r($this->request->data); die;
                                    if($this->request->data['minutes']) {
                                    } else {
                                      $this->request->data['minutes'] = 00;
                                    }
                                    $reportData['birth_time'] = $this->request->data['hours'].":".$this->request->data['minutes'];
                                  } else {
                                    //echo 'Not Set => <pre>'; print_r($this->request->data); die;
                                    $this->request->data['hours'] = $this->request->data['minutes'] = 00;
                                    //echo '<pre>'; print_r($this->request->data); die;
                                    $reportData['birth_time'] = $this->request->data['hours'].":".$this->request->data['minutes'];
                                  }
                              //}
                                
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

                                  //if( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' || $_SERVER['REMOTE_ADDR'] == '103.248.117.10' ) {
                                    if ($order['product_id'] == 19) {
                                      $orderData['age'] = !empty($this->request->data['age']) ? $this->request->data['age'] : 0;
                                    }
                                  //}

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

                            $data['birth_detail']['date'] = date('Y-m-d', strtotime(trim($date[0])) );
                            
                            if(isset($date[1]))
                            {
                              $data['birth_detail']['day'] = $date[1];
                            }
                            else
                            {
                             $data['birth_detail']['day'] = ''; 
                            }

                            $mnth =  date('m', strtotime(trim($date[0])));
                            $day  =  date('d', strtotime(trim($date[0])));
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
                            if($this->checkIp()) {
                              $data['is_guest'] = 1;
                            }

                            $user = $this->Users->patchEntity($user, $data , ['associated' => ['Profiles', 'BirthDetails']] );
                          /*if( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' || $_SERVER['REMOTE_ADDR'] == '103.248.117.10') {
                              pr ($user); die;
                          }
                        if( $this->Users->save($user) ):
                          
                          $loggedinUserData['User']['username'] = $user['username'];
                          $loggedinUserData['User']['role'] = 'user';
                          $loggedinUserData['UserProfile']['first_name'] = $user['profile']['first_name'];
                          $loggedinUserData['UserProfile']['last_name'] = $user['profile']['last_name'];
                          $loggedinUserData['UserProfile']['user_id'] = $user->id;
                          $loggedinUserData['UserProfile']['gender'] = $user['profile']['gender'];
                          $loggedinUserData['UserProfile']['country_id'] = $user['birth_detail']['country_id'];
                          $loggedinUserData['UserProfile']['city_id'] = $user['birth_detail']['city_id'];
                          $loggedinUserData['UserProfile']['language_id'] = $user['profile']['language_id'];
                          $loggedinUserData['BirthDetails']['user_id'] = $user->id;
                          $loggedinUserData['BirthDetails']['country_id'] = $user['birth_detail']['country_id'];
                          $loggedinUserData['BirthDetails']['city_id'] = $user['birth_detail']['city_id'];
                          $this->request->session()->write('Auth', $loggedinUserData);
                          $this->request->session()->write('selectedUser', $user->id);

                          $this->request->session()->write('user_id', $user->id);
                          $recipient = $data['username'];
                          $mailData      = [
                                                'subject'     => __("Welcome to Astrowow.com‏"),
                                                'mailtext'    => "<h1>Hello $fullname,</h1>",
                                                'password'    => $password,
                                                'username'    => $data['username'],
                                                'name'        => $fullname
                                         ];

                          $emailTemplate = new EmailTemplatesController();
                          $send =  $emailTemplate->sendWelcomeEmailOnSignup($recipient, $mailData);
                          $order['birth_date'] = date('Y-m-d', strtotime(trim($date[0])));
                          $order['birth_time'] = date("H:i", strtotime($data['hours'] .":". $data['minutes']));
                          $order['country_id'] = $data['birth_detail']['country_id'];
                          $order['city_id'] = $data['birth_detail']['city_id'];
                          $order['first_name'] = ucwords($this->request->session()->read('first_name'));
                          $order['last_name'] = ucwords($this->request->session()->read('last_name'));
                          $order['name_on_report'] = ucwords($this->request->data['name_on_report']);
                          $order['gender'] = $data['profile']['gender'];
                          $order['email'] = $data['username'];
                          if(isset($data['age']) && !empty($data['age'])) {
                            $order['age'] = $data['age'];
                          } else {
                            $order['age'] = 0;
                          }

                            
                          $this->request->session()->write('Order', $order);
                          /*if( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' || $_SERVER['REMOTE_ADDR'] == '103.248.117.10') {
                                pr ($this->request->session()->read('Order')); die;
                          }
                          return $this->redirect([ 'controller' => 'Orders', 'action' => 'checkout-step-3']);
                    endif;
                  }*/
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
            //if($this->checkIp()) {
                if(empty($this->request->session()->read('username'))){
                    return $this->redirect( ['controller'=> 'Orders', 'action' => 'checkout-step-1'] );       
                } 
                if(empty($order['first_name'])){
                    return $this->redirect( ['controller'=> 'Orders', 'action' => 'checkout-step-2'] ); 
                }
            //}
            if( empty($order['product_id']) )
            {
              return $this->redirect( ['controller'=> 'Pages', 'action' => 'index'] );
            }
            
            $finalPrice =  $this->getVatPrice( $order['product_id'], $order['currency_id'], $order['product_type']);

            /*if($this->checkIp()) {
              //$order['age'] = 0;
              pr($order); die;
            }*/
            /* Checking condition for payment gateway */   
            unset($order['type']);
            /*if( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' ) {
              echo $hello = $this->validateDataForPaymentGateway($order); die;
            }*/
            
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
    
    
	
	public function thanks() {
		$canonical['en'] = SITE_URL.'orders/thank-you';
		$canonical['da'] = SITE_URL.'dk/ordrer/tak';
		$meta['title'] = 'Astrowow.com - Astrology, free horoscopes, sun sign, Astrology reading, Astrology software';
		$meta['description'] = 'Astrowow.com provides free horoscopes, Astrology reading, Astrologer, Astrology Software, Astrology prediction, Daily, weekly, monthly horoscopes for aries, taurus, gemini, cancer, leo, virgo, libra, scorpio, sagittarius, capricorn, aquarius, pisces, sun sign';
		$meta['keywords'] = 'Free Astrology, Horoscope, Horoscope Reading,  Aries, taurus, gemini, cancer, leo, virgo, libra, scorpio, sagittarius, capricorn, aquarius, pisces,  daily horoscope, weekly horoscope, monthly horoscope,online astrology';
		$this->set(compact('canonical', 'meta'));
		


		if(empty ($this->request->session()->read('Order'))) {
			return $this->redirect( ['controller'=> 'pages', 'action' => 'index'] );
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

        //if (!$this->checkIp()) {
          $checkPayerIdExistancy = $this->Orders->find()->where(['payer_order_id' => $this->request->session()->read('Order.order_id')])->count();
          if ($checkPayerIdExistancy > 0) {
            $this->request->session()->delete('Order');
            return $this->redirect(['controller' => 'pages', 'action' => 'index']);
          }
        //}

				$loverreportstatus = false;
				$entity = $this->Orders->newEntity();
				$user_id = $this->request->session()->read('user_id');
				$order = $this->request->session()->read('Order');
				$orderPrice = $order['price'];

				/*
				 * krishan kumar gupta
				 */
				// Facebook purchase report tracking code
				$purchasedReportPrice = explode(' ', $order['price']);
				$this->request->session()->write('purchaseEvent.Price', $purchasedReportPrice[1]);
				$this->request->session()->write('purchaseEvent.Currency', $order['currency_code']);

				$id = $order['temp_order_id'];
				$seo_url = $order['seo_url'];
                $emailTemplate = new EmailTemplatesController();
				/* For Lovers Report */
				if($seo_url == "comprehensive-lovers-report" ||  $seo_url == 'astrologi-og-parforhold-rapport') {
					$loverreportstatus = true;

					$priceDetail = explode(' ',$orderPrice);

                    /*if($this->checkIp()) {
                        pr($order);
                        if(empty($order['user_id'])) {
                            pr($order);
                        }
                        die;
                    }*/


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
				                $this->loadModel('UserThankyouMails');
				                $thankyouMailEntity = $this->UserThankyouMails->newEntity();
				                $thankyouMailEntity['order_id'] = $entity->id;
				                $thankyouMailEntity['product_type'] = $order['product_type'];
                        $this->UserThankyouMails->save($thankyouMailEntity);
			              	
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
          //if ($this->checkIp()) {
                    $user = $this->request->session()->read('guest_user');
                    
                    if (isset($user) && !empty($user)) {
                        $temp_order['user_id'] = $user['id'];
                    } 

          //}
					$temp_order['confirm_payment_date'] = date('Y-m-d h:i:s');

					unset($temp_order['id']);
					unset($entity);
					$entity  = $this->Orders->newEntity();
					$entity =  $this->Orders->patchEntity($entity, $temp_order);
					
					if($this->Orders->save($entity)):
						/* Saving Transaction Data */
						$transaction = $this->saveTransactionData($entity->id, $temp_order['first_name'], $temp_order['last_name'], $temp_order['email'], $temp_order['price'], $order['currency_code'], $order['order_id'] );

            

						/*** Creating data for birthdata table ***/
						$birthDetails =   $this->saveBirthData123($temp_order['birth_date'], $temp_order['birth_time'], $temp_order['city_id'], $temp_order['country_id'], $order['first_name'], $order['last_name'], $order['name_on_report'], $order['gender'], $entity->id, $temp_order['age']);
						/** End Here**/

						if( $this->OrderTransactions->save($transaction) && $this->Birthdata->save($birthDetails)):
              				//if($this->checkIp()) {
				                $this->loadModel('UserThankyouMails');
				                $thankyouMailEntity = $this->UserThankyouMails->newEntity();
				                $thankyouMailEntity['order_id'] = $entity->id;
				                $thankyouMailEntity['product_type'] = $order['product_type'];
                        
				                $this->UserThankyouMails->save($thankyouMailEntity);
              				
						else:
							$this->Flash->error(__('Unable to process your request') );
						endif;
						/* 6 March 2017 */
					else:
						$this->Flash->error(__('Unable to process your request') );
					endif;
				}
				//$this->set(compact('purchaseEvent'));
				$this->set('order_id', $order['order_id']);
				$this->set('txnid', $this->request->query('txnid'));
				

				$this->request->session()->delete('Order');
				$this->request->session()->delete('first_name');
				$this->request->session()->delete('last_name');
				$this->request->session()->delete('birthdata');
				$this->request->session()->delete('Person_1');
				$this->request->session()->delete('Person_2');
        $this->request->session()->delete('loversData');
				$this->request->session()->delete('guest_user');

			}
		else:
			return $this->redirect( ['controller'=> 'Users', 'action' => 'login'] );
		endif;
    }


  protected function saveBirthData123 ($birthDate, $birthTime, $cityId, $countryId, $firstName, $lastName, $nameOnReport, $gender, $orderId, $age) {
      $birthdata['year'] = date('Y', strtotime($birthDate));
      $birthdata['month'] = date('m', strtotime($birthDate));
      $birthdata['day'] = date('d', strtotime($birthDate));


      if(!empty($birthTime)) {
        
        $locale = strtolower( substr(I18n::locale(), 0, 2)  ) ;
          
        // Changing locale to get correct time from the database
        if($locale == 'da') {
          I18n::locale('en_US');
          $birthdata['hour'] = date('H', strtotime($birthTime));
          $birthdata['minute'] = date('i', strtotime($birthTime));
          I18n::locale('da');
        } else {
          $birthdata['hour'] = date('H', strtotime($birthTime));
          $birthdata['minute'] = date('i', strtotime($birthTime));
        }
      } else {
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
      $birthdata['age'] = $age;
      $birthdata['state'] = $cityData['county'];
      $birthdata['country'] = $cityData['cities']['name'];
      $birthdata['place'] = $cityData['city'];
      $birthdata['longitude'] = $cityData['longitude'];
      $birthdata['latitude'] = $cityData['latitude'];
      $birthdata['first_name'] = $firstName;
      $birthdata['last_name'] = $lastName;
      $birthdata['name_on_report'] = $nameOnReport;
      if ($age) {
        $birthdata['start_date'] = ($birthdata['year']+$age).'-'.$birthdata['month'].'-'.$birthdata['day'];
      } else {
        $birthdata['start_date'] = date('Y-m-d');
      }
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

 protected function saveTransactionData($orderId, $firstName, $lastName, $email, $price, $currencyCode, $payer_order_id, $tno='')
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
      if(!empty($tno)) {
        $transactions['transaction_no'] = $tno;
      } else {
        $transactions['transaction_no'] = $this->request->query('txnid');
      }
      $transaction = $this->OrderTransactions->newEntity();
      $transaction = $this->OrderTransactions->patchEntity($transaction, $transactions);
      return $transaction;
 }

 // public function downloadFreeTrial($productId)
  public function downloadFreeTrial() {
    $canonical['en'] = SITE_URL.'orders/download-free-trial';
    $canonical['da'] = SITE_URL.'dk/ordrer/download-gratis-prøveversion';
    $meta['title'] = 'Astrowow.com - Astrology, free horoscopes, sun sign, Astrology reading, Astrology software';
    $meta['description'] = 'Astrowow.com provides free horoscopes, Astrology reading, Astrologer, Astrology Software, Astrology prediction, Daily, weekly, monthly horoscopes for aries, taurus, gemini, cancer, leo, virgo, libra, scorpio, sagittarius, capricorn, aquarius, pisces, sun sign';
    $meta['keywords'] = 'Free Astrology, Horoscope, Horoscope Reading,  Aries, taurus, gemini, cancer, leo, virgo, libra, scorpio, sagittarius, capricorn, aquarius, pisces,  daily horoscope, weekly horoscope, monthly horoscope,online astrology';
    $this->set(compact('canonical', 'meta'));

    $order = $this->request->session()->read('Order');
    if(!isset($order['category']) || empty($order['category'])) {
      return $this->redirect(['controller' => 'Pages', 'action'=> 'index']);
    }
    $entity = $this->Orders->newEntity();
    //$product = $this->Products->get($productId);
    $productId = $order['product_id'];
    if(strtolower( substr( I18n::locale(), 0, 2)) == 'da') {
      I18n::locale('en_us');
      $product = $this->Products->get($productId);
      I18n::locale('da');
    } else {
      $product = $this->Products->get($productId);
    }

    $deliveryOption  = $this->DeliveryOptions->find('all')
                                            ->where(['slug' => 'email'])
                                            ->select(['DeliveryOptions.id'])
                                            ->first();
    $portal = $this->Portals->find('all')
                            ->where(['Portals.name' => 'astrowow'])
                            ->select(['Portals.id'])
                            ->first();
    $orderStatus = $this->States->find('all')
                                ->where(['States.name' => 'closed'])
                                ->select(['States.id'])
                                ->first();
    $categoryData = $this->Categories->get($product['category_id']);
    $orderData['category'] = $categoryData['slug'];
    //$orderData['product_type']        = 'free-trial';       
    $orderData['product_type'] = $order['product_type'];
    $orderData['product_id']          = $order['product_id'];
    $orderData['language_id']         = $order['language_id'];

    if($this->request->is('post')) {
      $orderData = $this->request->data;
      $orderData['email'] = $orderData['username'];
      $orderData['portal_id'] = $portal['id'];
      $orderData['category_id'] = $product['category_id'];
      $categoryData = $this->Categories->get($product['category_id']);
      $orderData['category'] = $categoryData['slug'];
      $orderData['product_name'] = $product['name'];
      $orderData['product_id'] = $productId;
      //$orderData['language_id'] = $orderData['language_id'];
      $orderData['payer_order_id'] = $this->getOrderIdByProductType('Trial');
      //$products    =  $this->Products->Get($orderData['product_id']);

      $url = base64_encode($this->getSoftwarePath($orderData['language_id'], $product->seo_url));
      if(!empty($this->user_id) && isset($this->user_id)) {
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
        if($query->execute()) {
          $this->request->session()->delete('product_type');
          $this->request->session()->delete('language_id');
          // $this->redirect(['controller' => 'Orders', 'action' => 'thank-you-free-trial', '?' => ['oic' => $url]]);
          $this->redirect(['controller' => 'Orders', 'action' => 'thank-you-free-trial', $url ]);
        } else {
          $this->Flash->error(__('Error saving data'));
        }
      } else {
        $orderData['first_name']           = $orderData['profile']['first_name'];
        $orderData['last_name']            = $orderData['profile']['last_name'];
        $orderData['product_type']         = $orderData['product_type'];
        $download = $this->GuestUserProductDetails->newEntity();
        $download = $this->GuestUserProductDetails->patchEntity($download, $orderData);
        if($this->GuestUserProductDetails->save($download)) {
          $this->request->session()->delete('product_type');
          $this->request->session()->delete('language_id');
          //$this->redirect(['controller' => 'Orders', 'action' => 'thank-you-free-trial', '?' => ['oic' => $url]]);
          $this->redirect(['controller' => 'Orders', 'action' => 'thank-you-free-trial',  $url ]);
        } else {
          $this->Flash->error(__('Error saving data'));
        }
      }
    }
      
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

    /*$purchaseEvent = "<script>fbq('track', 'Purchase', {value: 0.00, currency: 'USD' });</script>";
    $this->set(compact('purchaseEvent'));*/
    $this->request->session()->write('purchaseEvent.Price', 0.00);
    $this->request->session()->write('purchaseEvent.Currency', 'USD');
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
  function softwareCheckoutStep1() {
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

    if( !isset($order['product_id']) || empty($order['product_id']) ) {
      $this->redirect(['controller'=> 'Pages', 'action' => 'index']);
    }
    if(!isset($order['price'] ) || empty($order['price']) ) {
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



    if(!empty($this->request->is('post'))) {
      $data                     = $this->request->data;
      $order['first_name']      = $data['profile']['first_name'];
      $order['last_name']       = $data['profile']['last_name'];
      $order['email']           = $data['username'];
      $order['portal_id']       = $portal['id']; 
      $order['currency_code']   = $currency['code'];
      $order['order_status']    = $status['id'];
        
      /* Without login */
      if(empty($this->user_id)) {
        $guestEntity             = $this->GuestUserProductDetails->newEntity();
        $guestEntity             = $this->GuestUserProductDetails->patchEntity($guestEntity, $order);
        if($this->GuestUserProductDetails->save($guestEntity)) {
          $order['guest']      = $guestEntity->id;
          if($order['product_type'] == SOFTWARE_CD)
          {
            $this->redirect(['controller' => 'Orders', 'action' => 'software-checkout-step-2' ]);      
          }
          elseif($order['product_type'] == SHAREWARE)
          {
           $this->redirect(['controller' => 'Orders', 'action' => 'software-checkout-step-3' ]); 
          }
        } else {
          $this->Flash->error(__('Unable to save data'));
        }
      } else {
        if($order['product_type'] == SOFTWARE_CD) {
          $this->redirect(['controller' => 'Orders', 'action' => 'software-checkout-step-2' ]);
        } elseif($order['product_type'] == SHAREWARE) {
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

  function softwareCheckoutStep2() {
    $canonical['en'] = SITE_URL.'orders/software-checkout-step-2';
    $canonical['da'] = SITE_URL.'dk/ordrer/software-kassen-trin-2';
    $meta['title'] = 'Astrowow.com - Astrology, free horoscopes, sun sign, Astrology reading, Astrology software';
    $meta['description'] = 'Astrowow.com provides free horoscopes, Astrology reading, Astrologer, Astrology Software, Astrology prediction, Daily, weekly, monthly horoscopes for aries, taurus, gemini, cancer, leo, virgo, libra, scorpio, sagittarius, capricorn, aquarius, pisces, sun sign';
    $meta['keywords'] = 'Free Astrology, Horoscope, Horoscope Reading,  Aries, taurus, gemini, cancer, leo, virgo, libra, scorpio, sagittarius, capricorn, aquarius, pisces,  daily horoscope, weekly horoscope, monthly horoscope,online astrology';
    $this->set(compact('canonical', 'meta'));
    
    /*if($this->checkIp()){
      pr($this->request->session()->read());
    }*/

    $entity        = $this->Orders->newEntity();
    $session       = $this->request->session();
    //$order         = $session->read('Order');
    $order         = $session->read('SOrder');

    if( !isset($order['product_id']) || empty($order['product_id']) ) {
        $this->redirect(['controller'=> 'Pages', 'action' => 'index']);
    }
    if( !isset($order['email']) || empty($order['email']) ) {
        $this->redirect(['controller' => 'Orders', 'action' => 'software-checkout-step-1' ]);
    }
    $finalPrice =  $this->getVatPrice( $order['product_id'], $order['currency_id'], $order['product_type']);
    $countryOptions = $this->Countries->find('list', ['order' => [ 'name' => 'ASC']])->toArray();
    
    if($this->request->is('post')) {
      $this->request->session()->write('shipping_data', $this->request->data);
      $this->redirect(['controller' => 'Orders', 'action' => 'software-checkout-step-3' ]);
    }
    $this->set('user', $this->user);
    $this->set('countryOptions',$countryOptions);
    $this->set('form', $entity);
    $this->set(compact('finalPrice'));
 }

  function softwareCheckoutStep3() {
    /*if($this->checkIp()){
      pr($this->request->session()->read());
    }*/
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

    if( !isset($order['product_id']) || empty($order['product_id']) ) {
      $this->redirect(['controller'=> 'Pages', 'action' => 'index']);
    }
    /**
     * Check Added By Anurag dubey dated 29-nov-2017 starts
    */
    if( !isset($order['email']) || empty($order['email']) ) {
       $this->redirect(['controller' => 'Orders', 'action' => 'software-checkout-step-1' ]);
    }
     
      
    if( empty($session->read('shipping_data')) ) {
        if($order['product_type'] == SOFTWARE_CD){
            $this->redirect(['controller' => 'Orders', 'action' => 'software-checkout-step-2' ]);
        }
    } else {
        
        $orderEntity = $this->TemporaryOrders->newEntity();
      	$shipping = $session->read('shipping_data');
      	$order['order_date'] = date('Y-m-d h:i:s');
      	$order['confirm_payment_date'] = date('Y-m-d h:i:s');
      	$priceDetail = explode(' ', $order['price']);
        $order['price'] = @$priceDetail[1];
        if(empty($order['price'])){
            $order['price'] = $priceDetail['0'];
        }
        if(!empty($this->user_id)) {
      		$shipping['user_id']   = $this->user_id;
      		$order['user_id']      = $this->user_id;
      	} else {
	      	$shipping['user_id'] = 0;
	      	$order['user_id'] = 0;
	    }
        $orderEntity = $this->TemporaryOrders->patchEntity($orderEntity, $order);

      	if($this->TemporaryOrders->save($orderEntity)) {
            $orderId = $orderEntity->id;
            $shipping['order_id'] = $orderId;
            $shippingEntity = $this->TempOrderShippings->newEntity();
            $shippingEntity = $this->TempOrderShippings->patchEntity($shippingEntity, $shipping);
            $this->TempOrderShippings->save($shippingEntity);
        }
    }
    /**
     * Check Added By Anurag dubey dated 29-nov-2017 ends
    */
    
    if(!$this->validateDataForPaymentGateway($order)) {
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
    $this->set('currency_code', @$order['currency_code']);
    $this->set(compact('finalPrice'));
  }
 
function softwareThankYou() {
    $canonical['en'] = SITE_URL.'orders/software-thank-you';
    $canonical['da'] = SITE_URL.'dk/ordrer/software-tak';
    $meta['title'] = 'Astrowow.com - Astrology, free horoscopes, sun sign, Astrology reading, Astrology software';
    $meta['description'] = 'Astrowow.com provides free horoscopes, Astrology reading, Astrologer, Astrology Software, Astrology prediction, Daily, weekly, monthly horoscopes for aries, taurus, gemini, cancer, leo, virgo, libra, scorpio, sagittarius, capricorn, aquarius, pisces, sun sign';
    $meta['keywords'] = 'Free Astrology, Horoscope, Horoscope Reading,  Aries, taurus, gemini, cancer, leo, virgo, libra, scorpio, sagittarius, capricorn, aquarius, pisces,  daily horoscope, weekly horoscope, monthly horoscope,online astrology';
    $this->set(compact('canonical', 'meta'));

    // Default status for software is processing
    $session = $this->request->session();
    //$order = $session->read('Order');
    $order = $session->read('SOrder');



    if(!empty($order['product_id'])) {
      	$orderEntity = $this->Orders->newEntity();
      	$firstName = $order['first_name'];
      	$lastName = $order['last_name'];
      	$fullName = $this->getFullName($firstName, $lastName);
      	$email = $order['email'];
      	$price = $order['price'];
      	$currencyCode = $order['currency_code'];
      	$shipping = $session->read('shipping_data');
      	$order['order_date'] = date('Y-m-d h:i:s');
      	$order['confirm_payment_date'] = date('Y-m-d h:i:s');
      	$priceDetail = explode(' ', $price);
      	$order['price'] = @$priceDetail[1];
        if(empty($order['price'])){
            $order['price'] = $priceDetail[0];
        }
        
      	$this->request->session()->write('purchaseEvent.Price', $order['price']);
      	$this->request->session()->write('purchaseEvent.Currency', $currencyCode);

      	$checkPayerIdExistancy = $this->Orders->find()->where(['payer_order_id' => $this->request->session()->read('SOrder.payer_order_id')])->count();
      	if ($checkPayerIdExistancy > 0) {
        	$this->request->session()->delete('SOrder');
        	return $this->redirect(['controller' => 'pages', 'action' => 'index']);
      	}

      	if(!empty($this->user_id)) {
      		$shipping['user_id']   = $this->user_id;
      		$order['user_id']      = $this->user_id;
      	} else {
	      	$shipping['user_id'] = 0;
	      	$order['user_id'] = 0;
	      	$guestEntity = $this->GuestUserProductDetails->get($order['guest']);
	      	$guest['payment_status'] = 1;
	      	$guestEntity = $this->GuestUserProductDetails->patchEntity($guestEntity, $guest);
	      	if(!$this->GuestUserProductDetails->save($guestEntity)) {
	      		$this->Flash->error(__('Unable to save Data'));
	      	}
      	}
      	$orderEntity = $this->Orders->patchEntity($orderEntity, $order);

      	if($this->Orders->save($orderEntity)) {
	      	$orderId = $orderEntity->id;
	        $shipping['order_id'] = $orderId;
            $shippingEntity = $this->OrderShippings->newEntity();
	        $shippingEntity = $this->OrderShippings->patchEntity($shippingEntity, $shipping);
	        $transactionEntity = $this->saveTransactionData($orderId, $firstName, $lastName, $email, $order['price'], $currencyCode, $order['payer_order_id']);
        	if($this->OrderShippings->save($shippingEntity) && $this->OrderTransactions->save($transactionEntity)) {
		        $recipient = $order['email'];
		        $orderText = ((strtolower( substr( I18n::locale(), 0, 2)) == 'da')) ? 'Tak for din Bestilling' : 'Thank you for Ordering';  
	        	$orderData = [
	        					'subject' => $orderText."‏ ". $order['product_name'],
	                            'name' => $fullName,
	                            'username' => $order['email'],
	                            'product_id' => $order['product_id'],
	                            'product_name' => $order['product_name'],
	                            'category_slug' => $order['category']
	                        ];
	        	$emailTemplate = new EmailTemplatesController();
	         	$session->delete('SOrder');
	         	$session->delete('shipping_data');
      		} else {
      			$this->Flash->error(__('Unable to save data'));
      		}
      	} else {
      		$this->Flash->error(__('Unable to save data'));
      	}
      	$this->set('order_id', $this->request->query('orderid'));
      	$this->set('txnid', $this->request->query('txnid'));
          $this->loadModel('UserThankyouMails');
          $thankyouMailEntity = $this->UserThankyouMails->newEntity();
          $thankyouMailEntity['order_id'] = $orderEntity->id;
          $thankyouMailEntity['product_type'] = $order['product_type'];
          $this->UserThankyouMails->save($thankyouMailEntity);
  	} else {
  		$this->redirect(['controller' => 'pages', 'action' => 'index']);
  	}
}


    protected function softwareAdminMail ($order_id, $price, $transaction_id, $productname, $name, $payerOrderId, $email, $productType, $softLanguage) {
      $this->loadModel('OrderShippings');
      $this->loadModel('Countries');
      $shippingAdd = $this->OrderShippings->find()->where(['order_id' => $order_id])->first();
      $address = '';
      $address = !empty($shippingAdd["address_1"]) ? $shippingAdd["address_1"].', ' : $address;
      $address = !empty($shippingAdd["address_2"]) ? $address.$shippingAdd["address_2"].', ' : $address;
      $address = !empty($shippingAdd["city"]) ? $address.$shippingAdd["city"].', ' : $address;
      $address = !empty($shippingAdd["state"]) ? $address.$shippingAdd["state"].', ' : $address;
      $address = !empty($shippingAdd["name"]) ? $address.$shippingAdd["name"].', ' : $address;
      $address = rtrim($address, ", ");
      $address = rtrim($address, ",");
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
                                    if (!empty($shippingAdd["phone"])) {
                                      $template .= '<tr>
                                                      <td>Phone No. : </td>
                                                      <td>'.$shippingAdd["phone"].'</td>
                                                    </tr>';
                                    }
                                    if (!empty($countryNme)) {
                                      $template .= '<tr>
                                                      <td>Country : </td>
                                                      <td>'.$countryNme['name'].'</td>
                                                    </tr>';
                                    }
                                    if (!empty($shippingAdd["postal_code"])) {
                                      $template .= '<tr>
                                                      <td>Postal Code : </td>
                                                      <td>'.$shippingAdd["postal_code"].'</td>
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




  public function consultation() {
      $canonical['en'] = SITE_URL.'orders/consultation';
      $canonical['da'] = SITE_URL.'dk/ordrer/konsultation';
      $meta['title'] = 'Astrowow.com - Astrology, free horoscopes, sun sign, Astrology reading, Astrology software';
      $meta['description'] = 'Astrowow.com provides free horoscopes, Astrology reading, Astrologer, Astrology Software, Astrology prediction, Daily, weekly, monthly horoscopes for aries, taurus, gemini, cancer, leo, virgo, libra, scorpio, sagittarius, capricorn, aquarius, pisces, sun sign';
      $meta['keywords'] = 'Free Astrology, Horoscope, Horoscope Reading,  Aries, taurus, gemini, cancer, leo, virgo, libra, scorpio, sagittarius, capricorn, aquarius, pisces,  daily horoscope, weekly horoscope, monthly horoscope,online astrology';
      $this->set(compact('canonical', 'meta'));
    }  

    public function consultationCheckout() {
      $canonical['en'] = SITE_URL.'orders/consultation-checkout';
      $canonical['da'] = SITE_URL.'dk/ordrer/konsultation-kassen';
      $meta['title'] = 'Astrowow.com - Astrology, free horoscopes, sun sign, Astrology reading, Astrology software';
      $meta['description'] = 'Astrowow.com provides free horoscopes, Astrology reading, Astrologer, Astrology Software, Astrology prediction, Daily, weekly, monthly horoscopes for aries, taurus, gemini, cancer, leo, virgo, libra, scorpio, sagittarius, capricorn, aquarius, pisces, sun sign';
      $meta['keywords'] = 'Free Astrology, Horoscope, Horoscope Reading,  Aries, taurus, gemini, cancer, leo, virgo, libra, scorpio, sagittarius, capricorn, aquarius, pisces,  daily horoscope, weekly horoscope, monthly horoscope,online astrology';
      $this->set(compact('canonical', 'meta'));

      $session = $this->request->session();
      $order   = $session->read('Order');

      if( !empty($order['order_id']) ) {
        $this->set('order', $session->read('Order'));
      } else {
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
                    foreach($data as $key=>$val){                
                        if($val['currency']['symbol']=='kr.'){
                            $data[$key]['ProductPrices']['total_price'] = round($val['ProductPrices']['total_price'],0);
                            $data[$key]['ProductPrices']['discount_total_price'] = round($val['ProductPrices']['total_price'],0);                                      }  
                    }
                    //pr($data);  
                    echo json_encode($data);
                    exit;
               }     
                return $data;
                      
    }


    public function consultationThankYou(){
      $canonical['en'] = SITE_URL.'orders/consultation-thank-you';
      $canonical['da'] = SITE_URL.'dk/ordrer/konsultation-tak';
      $meta['title'] = 'Astrowow.com - Astrology, free horoscopes, sun sign, Astrology reading, Astrology software';
      $meta['description'] = 'Astrowow.com provides free horoscopes, Astrology reading, Astrologer, Astrology Software, Astrology prediction, Daily, weekly, monthly horoscopes for aries, taurus, gemini, cancer, leo, virgo, libra, scorpio, sagittarius, capricorn, aquarius, pisces, sun sign';
      $meta['keywords'] = 'Free Astrology, Horoscope, Horoscope Reading,  Aries, taurus, gemini, cancer, leo, virgo, libra, scorpio, sagittarius, capricorn, aquarius, pisces,  daily horoscope, weekly horoscope, monthly horoscope,online astrology';
      $this->set(compact('canonical', 'meta'));

      $session = $this->request->session();
      $checkSession = $session->read('Order');
      $checkPayerIdExistancy = $this->Orders->find()->where(['payer_order_id' => $this->request->session()->read('Order.order_id')])->count();
      if ($checkPayerIdExistancy > 0) {
        $this->request->session()->delete('Order');
        return $this->redirect(['controller' => 'pages', 'action' => 'index']);
      }
      $this->request->session()->write('  .Price', $session->read('Order.price'));
      $this->request->session()->write('purchaseEvent.Currency', $session->read('Order.currencyCode'));
      
      if( !empty($checkSession['order_id']) ) {
        $status = $this->States->find('all')
                                ->select(['States.id'])
                                ->where(['States.name' => 'queued'])
                                ->first();

        $deliveryOption  = $this->DeliveryOptions->find('all')
                                                  ->where(['DeliveryOptions.slug' => 'skype'])
                                                  ->select(['DeliveryOptions.id'])
                                                  ->first();
        $orderEntity = $this->Orders->newEntity();

        if($session->read('user_id')){
          $order = $session->read('Order');
          $user_id = $session->read('user_id');
          $userDetail = $this->Users->find('all')
                                    ->contain(['Profiles'])
                                    ->where(['Users.id' => $user_id])
                                    ->select(['Users.username', 'Profiles.first_name', 'Profiles.last_name'])
                                    ->first();
          $order['user_id'] = $user_id;
          $order['email'] = $userDetail['username'];
          $firstName = $userDetail['profile']['first_name'];
          $lastName = $userDetail['profile']['last_name'];
          $order['order_date'] = date('Y-m-d h:i:s');
          $order['confirm_payment_date'] = date('Y-m-d h:i:s'); 
          $order['order_status'] = $status['id'];
          $order['delivery_option'] = $deliveryOption['id'];
          $fullName = $this->getFullName($firstName, $lastName);
          $order['payer_order_id'] = $order['order_id'];
          $orderEntity = $this->Orders->patchEntity($orderEntity, $order);

          if($this->Orders->save($orderEntity)){
            $orderId = $orderEntity->id;
            $transactionEntity = $this->saveTransactionData($orderId, $firstName, $lastName, $order['email'], $order['price'], $order['currencyCode'], $order['order_id']);
            if($this->OrderTransactions->save($transactionEntity)){
              $this->loadModel('UserThankyouMails');
              $thankyouMailEntity = $this->UserThankyouMails->newEntity();
              $thankyouMailEntity['order_id'] = $orderEntity->id;
              $thankyouMailEntity['product_type'] = $order['product_type'];
              $this->UserThankyouMails->save($thankyouMailEntity);
            }
            $session->delete('Order');
          }
        }
      } else {
        $this->redirect(['controller' => 'Pages', 'action' => 'index']);
      }
      $this->set('order_id', $this->request->query('orderid'));
      $this->set('txnid', $this->request->query('txnid'));
    }

    /*
     * This function used to place a mini report order using AstroClock App
     * Created Date : September 25, 2017
     * Modified Date : September 26, 2017
     * Created By : Krishan Kumar <Kingslay@123789.org>
     */
    function mobileUserPlaceOrder(){
      $entity = $this->Orders->newEntity();
      $order = [];
      $order['product_id'] = $this->request->data('product_id');
      $order['price'] = $this->request->data('price');
      $order['user_id'] = $this->request->data('user_id');
      $order['another_person'] = 0;
      $order['email'] = $this->request->data('email_id');
      $order['delivery_option'] = $this->request->data('delivery_option');
      $order['order_status'] = 12;
      $order['order_date'] = $this->request->data('order_date');
      $order['confirm_payment_date'] = $this->request->data('confirm_payment_date');
      $order['product_type'] = $this->request->data('product_type');
      $order['currency_id'] = $this->request->data('currency_code');
      $order['shipping_charge'] = $this->request->data('shipping_charge');
      $order['language_id'] = $this->request->data('language_code');
      $order['portal_id'] = $this->request->data('portalid');
      $order['order_by'] = 1;
      $order['created'] = date('Y-m-d H:i:s');
      $order['modified'] = date('Y-m-d H:i:s');
      $data = $this->Orders->patchEntity($entity, $order);
      $returnData = [];
      if($result = $this->Orders->save($data)){
        $returnData['id'] = $result->id;
        $returnData['msg'] = 'Data saved';
      } else {
        $returnData['msg'] = 'Data not saved';
      }
      echo json_encode($returnData); die;
    }


    /*
     * This function used to place a free mini report order using Admin panel
     * Created Date : October 06, 2017
     * Created By : Krishan Kumar <Kingslay@123789.org>
     */
    function miniReportOrderByAdmin(){
      if($this->request->is('post')) {
        $this->request->session()->write('free-mini-report-details', $this->request->data);
        if(!empty($this->request->data['first_name']) && !empty($this->request->data['product_id']) && !empty($this->request->data['language_id']) && !empty($this->request->data['datepicker']) && !empty($this->request->data['timepicker']) && !empty($this->request->data['email'])) {
          $entity = $this->Orders->newEntity();
          $dob = explode('-', $this->request->data['datepicker']);
          $dob = trim($dob[0]);
          $dob = str_replace('/', '-', $dob);
          $order = [];
          $this->request->data['order_status'] = 12;
          $this->request->data['order_by'] = 3;
          $this->request->data['payer_order_id'] = 'MiniAmn-'.time();
          $this->request->data['last_name'] = !empty($this->request->data['last_name']) ? $this->request->data['last_name'] : '';
          $this->request->data['created'] = $this->request->data['modified'] = $this->request->data['order_date'] = $this->request->data['confirm_payment_date'] = date('Y-m-d H:i:s');
          $data = $this->Orders->patchEntity($entity, $this->request->data);

          if($result = $this->Orders->save($data)){
            $transaction = $this->saveTransactionData($result->id, $this->request->data['first_name'], $this->request->data['last_name'], $this->request->data['email'], $this->request->data['price'], 'USD', $this->request->data['payer_order_id'], '111111111111111');
            
            $birthDetails = $this->saveBirthData123($dob, $this->request->data['timepicker'], $this->request->data['city_id'], $this->request->data['country_id'], $this->request->data['first_name'], $this->request->data['last_name'], $this->request->data['first_name'].' '.$this->request->data['last_name'], $this->request->data['gender'], $result->id, 0);
            if(!array_key_exists('last_name', $birthDetails)) {
              $birthDetails['last_name'] = '';
            }
            
            if( $this->OrderTransactions->save($transaction) && $this->Birthdata->save($birthDetails)) {
              $this->request->session()->delete('free-mini-report-details');
              $this->Flash->success(__('Mini report order has been placed successfully.') );
              return $this->redirect(['controller' => 'orders', 'action' => 'generate-mini-report', 'prefix' => 'admin_panel']);
            } else {
              $this->Flash->error(__('Something went wrong.') );
              return $this->redirect(['controller' => 'orders', 'action' => 'generate-mini-report', 'prefix' => 'admin_panel']);
            }
          } else {
            $this->Flash->error(__('Unable to process your request.') );
            return $this->redirect(['controller' => 'orders', 'action' => 'generate-mini-report', 'prefix' => 'admin_panel']);
          }
        } else {
          $this->Flash->error(__('Please fill all required fields.') );
          return $this->redirect(['controller' => 'orders', 'action' => 'generate-mini-report', 'prefix' => 'admin_panel']);
        }
      } else {
        $this->Flash->error(__('Unable to process your request.') );
        return $this->redirect(['controller' => 'orders', 'action' => 'generate-mini-report', 'prefix' => 'admin_panel']);
      }
    }

}

?>