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
use MySFTP\Net_SFTP;

class EliteUsersController extends AppController {
    public  $paginate = [
      'limit' => 5
    ];
    private $uploadDir = WWW_ROOT.'uploads'.DS.'elite-user-coverPage'.DS;

    public function initialize() {
        parent::initialize();
        if ($this->request->session()->read('locale') == 'en') {
            I18n::locale('en_US');
        } elseif ($this->request->session()->read('locale') == 'da') {
          I18n::locale('da');
        }
        
        $this->loadModel('Profiles');
        $this->loadModel('Users');
        $this->loadModel('Products');
        $this->loadModel('EliteMembers');
        $this->loadModel('EmailTemplates');
        $this->loadModel('AnotherPersons');
        $this->loadModel('BirthDetails');
        $this->loadModel('Orders');
        $this->loadComponent('Paginator');

        $this->Menus->recover();

        $user_id = $this->request->session()->read('user_id');
        $this->set('user_id', $user_id);
        $step = $this->request->session()->read('step');
        $this->set('step', $step);
        $this->viewBuilder()->layout('home');
        $this->loadComponent('FileUpload.FileUpload',[
              'uploadDir' => $this->uploadDir,
              'maintainAspectRation'=>true
          ]);
    }

    public function beforeFilter(Event $event) {
       parent::beforeFilter($event);
       $this->Auth->allow();
    }

    /**
     * Shows plans to become an elite member for normal user
     * Created by : Krishna Gupta
     * Created Date : Dec. 14, 2016
     */
    function index () {
        $canonical['en'] = SITE_URL.'elite-users';
        $canonical['da'] = SITE_URL.'dk/elite-brugere';
      	$meta['title'] = 'Elite Customer Membership : Pay a small yearly fee and sell reports for $10 per report.';
        $meta['description'] = '';
        $meta['keywords'] = '';
        $this->set(compact('meta', 'canonical'));
        $session = $this->request->session();
        $user_id = !empty($session->read('Auth.User.id')) ? $session->read('Auth.User.id') : $session->read('user_id');
        if ($user_id) {
            $Role = $this->Users->find()->select(['id', 'role'])->where(['id'=>$user_id])->first();
                $this->loadModel('Products');
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
                                      ->select([ 'Products.id', 'Products.short_description', 'Products.name', 'Products.image', 'Products.pages', 'Products.seo_url', 'product_prices.total_price', 'currency.code', 'currency.symbol'])
                                      ->where(['Categories.slug' => 'elite-membership'] )
                                      ->order(['Products.id' => 'DESC'])
                                      ->toArray();
                if ($this->request->is('post') && !empty($this->request->data)) {
                    $alldata = $data = array ();
                    $data = @explode('-', $this->request->data['currency']);
                    $alldata = @explode(' ', $data[0]);
                    $count = count ($alldata);
                    $alldata[$count] = $data[1];
                    $this->loadModel ('Currencies');
                    $this->loadModel ('Products');
                    $currency_id = $this->Currencies->find()->select(['id', 'code'])->where(['code' => $alldata[2]])->first();
                    $productsDetail = $this->Products->find()->select(['id', 'seo_url'])->where(['seo_url' => 'elite-membership'])->first();
                    //pr ($currency_id); die;

                    $finalPrice =  $this->getVatPrice( $productsDetail['id']/*$order['product_id']*/, $currency_id['id'], 11/*$order['product_type']*/);
                    
                    $session->write ('Elite.Elite Customer Amount', $alldata[1]);
                    $session->write ('Elite.Elite Currency Code', $alldata[2]);
                    $session->write ('Elite.Elite Customer Id', $user_id);
                    $session->write ('Elite.Elite Currency Sign', $alldata[0]);
                    $session->write ('Elite.priceDetail', $finalPrice);

                    $this->redirect(['controller' => 'elite-users', 'action' => 'elite-customer-checkout']);
                } else {
                    $this->set(compact('products'));
                }
        } else {
            $this->Flash->error(__("You can't access that location."));
            $this->redirect(['controller' => 'users', 'action' => 'login']);
        }
    }
    
    /**
     * Shows normal user details to become an elite member
     * Created by : Krishna Gupta
     * Created Date : Dec. 14, 2016
     * Modified Date : Jan. 09, 2017
     */
    function eliteCustomerCheckout () {
        $canonical['en'] = SITE_URL.'elite-users/elite-customer-checkout';
        $canonical['da'] = SITE_URL.'dk/elite-brugere/elite-kunde-kassen';
        $meta['title'] = 'Free astrology member sign up - free horoscopes, astrology reading';
        $meta['description'] = 'Get the free astrology member ship for free daily, weekly, monthly yearly horoscopes,sun sign, tarot.';
        $meta['keywords'] = 'Tarot sign, best astrology, horoscopes, astrologyical charts, astrology';
        $this->set(compact('meta', 'canonical'));
        $session = $this->request->session();
        $user_id = !empty($session->read('Auth.User.id')) ? $session->read('Auth.User.id') : $session->read('user_id');
        if ($user_id) {
            if (($this->request->is('post') && !empty($this->request->data))) {
                $this->redirect(['controller' => 'elite-users', 'action' => 'elite-customer-checkout-step2']);
            } else {
              if (!empty($session->read('Elite'))) {

              } else {
                $this->Flash->error(__("You can't access that location."));
                if (!empty($session->read ('Auth.User.role')) && ($session->read ('Auth.User.role') == 'elite')) {
                  $this->redirect(['controller' => 'elite-users', 'action' => 'dashboard']);
                } else {
                  $this->redirect(['controller' => 'users', 'action' => 'dashboard']);
                }
              }
            }
            $eliteUserDetails = $this->Profiles->find()->select(['id', 'user_id', 'first_name', 'last_name'])->where(['user_id' => $user_id])->first();
            $email = $this->Users->find()->select(['id', 'username'])->where(['id' => $user_id])->first();
            $this->set(compact('eliteUserDetails', 'email'));
        } else {
            $this->Flash->error(__("You can't access that location."));
            $this->redirect(['controller' => 'users', 'action' => 'login']);
        }
    }

    /**
     * Fill user general details to generate report
     * Created by : Krishna Gupta
     * Created Date : Jan. 19, 2017
     * Modified Date : Jan. 19, 2017
     */
    function eliteReportCheckout () {
        $canonical['en'] = SITE_URL.'elite-users/elite-report-checkout';
        $canonical['da'] = SITE_URL.'dk/elite-brugere/elite-rapport-kassen';
        $meta['title'] = 'Astrology - Report';
        $meta['description'] = '';
        $meta['keywords'] = '';
        $this->set(compact('meta', 'canonical'));
        $session = $this->request->session();
        $user_id = !empty($session->read('Auth.User.id')) ? $session->read('Auth.User.id') : $session->read('user_id');
        if ($user_id) {
            
          $this->loadModel ('Products');
          if (!empty($this->request->data)) {
            $this->request->session()->write ('EliteReportOrder', $this->request->data);
            $pid = $this->request->session()->read ('EliteReportOrder.product_id');
            $finalPrice =  $this->getVatPrice( $this->request->data['product_id'], $this->request->data['currency_id'], $this->request->data['product_type']);
            $session->write('EliteReportOrder.priceDetail', $finalPrice);
            $image = $this->Products->find ()->select (['id', 'image', 'pages', 'name', 'seo_url'])->where (['Products.id' => $pid])->first ();
            $this->request->session()->write ('EliteReportOrder.ProductDetail.image', $image['image']);
            $this->request->session()->write ('EliteReportOrder.ProductDetail.pages', $image['pages']);
            $this->request->session()->write ('EliteReportOrder.ProductDetail.name', $image['name']);
            $this->request->session()->write ('EliteReportOrder.ProductDetail.seo_url', $image['seo_url']);
          } else {
            if($this->checkIp()){
                if (empty($session->read('EliteReportOrder.UserDetail.first_name'))) {
                    $this->Flash->error(__("You can't access that location."));
                    $this->redirect(['controller' => 'elite-users', 'action' => 'dashboard']);
                }
            } else {
                $this->Flash->error(__("You can't access that location."));
                $this->redirect(['controller' => 'elite-users', 'action' => 'dashboard']);
            }
          } /*else {
              if (empty($session->read('EliteReportOrder.UserDetail.first_name'))) {
                  $this->redirect(['controller' => 'elite-users', 'action' => 'dashboard']);
              }
          }*/
        } else {
            $this->Flash->error(__("You can't access that location."));
            $this->redirect(['controller' => 'users', 'action' => 'login']);
        }
    }

    /**
     * Fill user personal details to generate report
     * Created by : Krishna Gupta
     * Created Date : Jan. 19, 2017
     * Modified Date : Jan. 19, 2017
     */
    function eliteReportCheckout2 () {
        $canonical['en'] = SITE_URL.'elite-users/elite-report-checkout2';
        $canonical['da'] = SITE_URL.'dk/elite-brugere/elite-rapport-kassen2';
        $meta['title'] = 'Astrology - Report';
        $meta['description'] = $meta['keywords'] = '';
        $this->set(compact('meta', 'canonical'));
        $session = $this->request->session();
        $user_id = !empty($session->read('Auth.User.id')) ? $session->read('Auth.User.id') : $session->read('user_id');
        if ($user_id) {
            if (!empty($this->request->data)) {
              $this->request->session()->write ('EliteReportOrder.UserDetail.first_name', $this->request->data['profile']['first_name']);
              $this->request->session()->write ('EliteReportOrder.UserDetail.last_name', $this->request->data['profile']['last_name']);
              $this->request->session()->write ('EliteReportOrder.UserDetail.email', $this->request->data['username']);
            } else {
              if (empty($session->read('EliteReportOrder.UserDetail.first_name'))) {
                $this->Flash->error(__("You can't access that location."));
                $this->redirect(['controller' => 'elite-users', 'action' => 'dashboard']);
              }
              //$this->redirect(['controller' => 'elite-users', 'action' => 'dashboard']);
            } /*else {
                if (empty($session->read('EliteReportOrder.UserDetail.first_name'))) {
                    $this->redirect(['controller' => 'elite-users', 'action' => 'dashboard']);
                }
            }*/
            $this->loadModel ('Countries');
            $countryOptions = $this->Countries->find ('list')->order(['name' => 'ASC'])->where(['Countries.status' => 1])->toArray();
            $this->set(compact('countryOptions'));
        } else {
            $this->Flash->error(__("You can't access that location."));
            $this->redirect(['controller' => 'users', 'action' => 'login']);
        }
    }

    /**
     * Payment process
     * Created by : Krishna Gupta
     * Created Date : Dec. 14, 2016
     * Modified Date : Jan. 09, 2017
     */
    function eliteCustomerCheckoutStep2 () {
        $canonical['en'] = SITE_URL.'elite-users/elite-customer-checkout-step2';
        $canonical['da'] = SITE_URL.'dk/elite-brugere/elite-kunde-kassen-step2';
        $meta['title'] = 'Astrology - Report';
        $meta['description'] = $meta['keywords'] = '';
        $this->set(compact('canonical', 'meta'));
        $session = $this->request->session();
        $user_id = !empty($session->read('Auth.User.id')) ? $session->read('Auth.User.id') : $session->read('user_id');
        if ($user_id) {
          //pr ($this->referer()); die;
          $session->write('previousUrl', $this->referer());
          if (!empty($session->read('EliteReportOrder')) || !empty($session->read('Elite'))) {
                if(empty($session->read('EliteReportOrder.UserDetail.email'))){
                    $this->redirect(['controller' => 'elite-users', 'action' => 'dashboard']);
                } 
                
            if (!empty($session->read ('Auth.User.role')) && ($session->read ('Auth.User.role') == 'elite')) {
            	$meta['title'] = 'Astrology - Report';
  		        $meta['description'] = '';
  		        $meta['keywords'] = '';
              $this->set(compact('meta'));
              if (!empty($this->request->data)) { // For elite member purchase reports
                  $this->request->session()->write('EliteReportOrder.BirthData', $this->request->data);
                  /*if( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' ) {
                    echo '<pre>'; print_r($session->read()); print_r($this->request->data); die;
                  }*/
                  if (empty($this->request->data['city_id']) || empty($this->request->data['city'])) {
                      if (empty($session->read('EliteReportOrder.city_id'))) {
                          $session->write('EliteReportOrder.BirthData.city', '');
                          //$session->write('EliteReportOrder.BirthData.city_id');
                          $this->Flash->error(__("Please select city from the dropdown"));
                          return $this->redirect(['controller' => 'elite-users', 'action' => 'elite-report-checkout2']);
                      }
                  }
                  $enteredData = [];
                  $enteredData = $session->read ('EliteReportOrder');
                  if (isset($this->request->data['city_id'])&& !empty($this->request->data['city_id'])) {
                    $session->write('EliteReportOrder.city_id', $this->request->data['city_id']);
                  }
                  if (isset($this->request->data['city'])&& !empty($this->request->data['city'])) {
                    $session->write('EliteReportOrder.city', $this->request->data['city']);
                  }
                  if (isset($this->request->data['country_id'])&& !empty($this->request->data['country_id'])) {
                    $session->write('EliteReportOrder.country_id', $this->request->data['country_id']);
                  }
                  if (isset($this->request->data['hours'])&& !empty($this->request->data['hours'])) {
                    $session->write('EliteReportOrder.hours', $this->request->data['hours']);
                  }
                  if (isset($this->request->data['minutes'])&& !empty($this->request->data['minutes'])) {
                    $session->write('EliteReportOrder.minutes', $this->request->data['minutes']);
                  }
                  if (isset($this->request->data['birth_date'])&& !empty($this->request->data['birth_date'])) {
                    $session->write('EliteReportOrder.birth_date', $this->request->data['birth_date']);
                  }
                  if (isset($this->request->data['gender'])&& !empty($this->request->data['gender'])) {
                    $session->write('EliteReportOrder.gender', $this->request->data['gender']);
                  }

                  unset ($enteredData['ProductDetail']);
                  unset ($enteredData['chk_for_register']);
                  unset ($enteredData['shipping_charge']);
                  unset ($enteredData['priceDetail']);
                  $enteredData['first_name'] = $session->read ('EliteReportOrder.UserDetail.first_name');
                  $enteredData['last_name'] = $session->read ('EliteReportOrder.UserDetail.last_name');
                  $enteredData['email'] = $session->read ('EliteReportOrder.UserDetail.email');

                  $enteredData['city_id'] = $session->read('EliteReportOrder.city_id');
                  $enteredData['city'] = $session->read('EliteReportOrder.city');
                  $enteredData['country_id'] = $session->read('EliteReportOrder.country_id');
                  $enteredData['hours'] = $session->read('EliteReportOrder.hours');
                  $enteredData['minutes'] = $session->read('EliteReportOrder.minutes');
                  $enteredData['birth_date'] = $session->read('EliteReportOrder.birth_date');
                  $enteredData['gender'] = $session->read('EliteReportOrder.gender');
                  unset ($enteredData['UserDetail']);
                  unset ($enteredData['BirthData']);

                  //pr ($enteredData); die;
                  if ($this->validateDataForPaymentGateway($enteredData)) { // Check all required fields are filled or not
                    $this->request->session()->write ('EliteReportOrder.BirthData', $this->request->data);
                    $price = trim($this->request->session()->read('EliteReportOrder.price'));
                    $currency_id = trim($this->request->session()->read('EliteReportOrder.currency_id'));
                    $order_id = 'Eorder-'.time();
                    $this->set(compact('order_id', 'currency_id', 'price'));
                  } else {
                    $this->Flash->error(__('Unable to process your request'));
                    return $this->redirect(['controller' => 'elite-users', 'action' => 'elite-report-checkout']);
                  }
              } else { // For upgrade elite membership
                //echo 'upgrade elite membership';
                //pr ($this->request->data); die;
                if(empty($session->read('EliteReportOrder.BirthData'))){
                    $this->redirect(['controller' => 'elite-users', 'action' => 'dashboard']);
                }
                $order_id = 'Elite-'.time();
                $this->request->session()->write('Elite.Elite Subscription Order Id', $order_id);
                $price = trim($session->read('Elite.Elite Customer Amount'));
                $this->set(compact('order_id', 'price'));
              }
              $session->write('EliteReportOrder.orderid', $order_id);
            } else { // For register as a elite member
                //echo 'new elite member';
                //pr ($this->request->data); die;
                $order_id = 'Elite-'.time();
                $this->request->session()->write('Elite.Elite Subscription Order Id', $order_id);
                $this->request->session()->write('Elite.Elite Customer Order Id', $order_id);
                $price = trim($session->read('Elite.Elite Customer Amount'));
                $session->write('Elite.orderid', $order_id);
                $this->set(compact('order_id', 'price'));
            }
          } else {
            $this->Flash->error(__("You can't access that location."));
            $this->redirect(['controller' => 'elite-users', 'action' => 'dashboard']);
          }
        } else {
            $this->Flash->error(__("You can't access that location."));
            $this->redirect(['controller' => 'users', 'action' => 'login']);
        }
    }

    /**
     * Shows thank you page and save data into DB of purchased reports by elite member
     * Created by : Krishna Gupta
     * Created Date : Dec. 20, 2016
     * Modified Date : Jan. 09, 2017
     */
    function thankYouForReportPurchase () {
        $canonical['en'] = SITE_URL.'elite-users/thank-you-for-report-purchase';
        $canonical['da'] = SITE_URL.'dk/elite-brugere/tak-for-rapport-køb';
        $meta['title'] = 'Astrology - Report';
        $meta['description'] = $meta['keywords'] = '';
        $this->set(compact('meta', 'canonical'));
        $this->viewBuilder()->layout('home');
        $session = $this->request->session();
        $user_id = !empty($session->read('Auth.User.id')) ? $session->read('Auth.User.id') : $session->read('user_id');
        if ($user_id) {
            if (!empty($session->read('EliteReportOrder'))) {
                $this->loadModel('Orders');
                $checkPayerIdExistancy = $this->Orders->find()->where(['payer_order_id' => $session->read('EliteReportOrder.orderid')])->count();
                if ($checkPayerIdExistancy > 0) {
                  $this->request->session()->delete('EliteReportOrder');
                  return $this->redirect(['controller' => 'elite-users', 'action' => 'dashboard']);
                }

                $ordersTable = TableRegistry::get('Orders');
                $entity = $this->Orders->newEntity();
                $entity->user_id = $session->read('EliteReportOrder.user_id');
                $entity->product_id = $session->read('EliteReportOrder.product_id');
                $entity->price = $session->read('EliteReportOrder.price');
                $entity->email = $session->read('EliteReportOrder.UserDetail.email');
                $entity->delivery_option = $session->read('EliteReportOrder.delivery_option');
                $entity->order_status = $session->read('EliteReportOrder.order_status');
                $entity->order_date = date('Y-m-d H:i:s', $session->read('EliteReportOrder.order_date'));
                $entity->confirm_payment_date = date('Y-m-d H:i:s', $session->read('EliteReportOrder.confirm_payment_date'));
                $entity->product_type = $session->read('EliteReportOrder.product_type');
                $entity->chk_for_register = $session->read('EliteReportOrder.chk_for_register');
                $entity->currency_id = $session->read('EliteReportOrder.currency_id');
                $entity->shipping_charge = $session->read('EliteReportOrder.shipping_charge');
                $selectedLang = !empty($session->read('locale')) ? $session->read('locale') : 'en';
                if ( (strtolower($selectedLang) == 'da') || (strtolower($selectedLang) == 'dk') ) {
                  $entity->language_id = 2;
                } else {
                  $entity->language_id = 1;
                }
                $entity->portal_id = $session->read('EliteReportOrder.portal_id');
                $entity->payment_method = $session->read('EliteReportOrder.payment_method');
                $entity->payer_order_id = $session->read('EliteReportOrder.orderid');
                $entity->created = date('Y-m-d H:i:s', time());
                $entity->modified = date('Y-m-d H:i:s', time());
                if ($ordersTable->save($entity)) { //Save data into Orders table
                    $currencycode = $this->getCurrencyDetails ($entity->currency_id);
                    $birthData = $this->saveBirthDataAsOrder ($entity->id); // Save data into birthdata table
                    $currencycode = $currencycode['code'];
                    $order_id = $payerId = $this->request->query ('orderid');
                    $transctions = $this->saveTransactionData ($entity->id, $session->read('EliteReportOrder.BirthData.first_name'), $session->read('EliteReportOrder.BirthData.last_name'), $session->read('EliteReportOrder.UserDetail.email'), $entity->price, $currencycode, $payerId); //Save data into order transaction table
                    $this->loadModel('Currencies');
                    $entityCurrencyCode = $this->Currencies->find()->where(['Currencies.id' => $entity->currency_id])->select(['id', 'code', 'symbol'])->first();
                    //$purchaseEvent = "<script>fbq('track', 'Purchase', {value: ".$entity->price.", currency: '".$entityCurrencyCode['code']."' });</script>";
                    $this->request->session()->write('purchaseEvent.Price', $entity->price);
                    $this->request->session()->write('purchaseEvent.Currency', $entityCurrencyCode['code']);
                    //if($this->checkIp()) {
                        $this->loadModel('UserThankyouMails');
                        $thankyouMailEntity = $this->UserThankyouMails->newEntity();
                        $thankyouMailEntity['order_id'] = $entity->id;
                        $thankyouMailEntity['product_type'] = $entity->product_type;
                        /*$ipAdd = 0;
                        if ($this->checkIp()) {
                          $ipAdd = $this->request->clientIp();
                        }
                        $thankyouMailEntity['ip'] = $ipAdd;*/
                        if ($this->UserThankyouMails->save($thankyouMailEntity)) {
                          $session->delete('EliteReportOrder');
                          $txnid = $this->request->query('txnid');
                          $this->set(compact('txnid', 'order_id'));
                          //$ProductDetail = $this->tableData("Products", $entity->product_id, "name");
                          //$emailTemplate = new EmailTemplatesController();
                          //$msgBody = $this->eliteReportsMailToAdmin ($entity->id, $entityCurrencyCode['symbol'].' '.$entity->price, $this->request->query('txnid'), $ProductDetail['name'], $entity->payer_order_id, $entity->email);
                          //$emailTemplate->sendMailToAdmin($ProductDetail['name'].' order received', $msgBody);
                        }
                    /*} else {
                      if ($transctions) {
                          $session->delete('EliteReportOrder');
                          $txnid = $this->request->query('txnid');
                          $this->set(compact('txnid', 'order_id', 'purchaseEvent'));
                          $ProductDetail = $this->tableData("Products", $entity->product_id, "name");
                          $emailTemplate = new EmailTemplatesController();
                          $msgBody = $this->eliteReportsMailToAdmin ($entity->id, $entityCurrencyCode['symbol'].' '.$entity->price, $this->request->query('txnid'), $ProductDetail['name'], $entity->payer_order_id, $entity->email);
                          $emailTemplate->sendMailToAdmin($ProductDetail['name'].' order received', $msgBody);
                      }
                    }*/
                }
            } else {
              $this->redirect(['controller' => 'pages', 'action' => 'index']);
            }
        } else {
            $this->Flash->error(__("You can't access that location."));
            $this->redirect(['controller' => 'users', 'action' => 'login']);
        }
    }

    protected function eliteReportsMailToAdmin ($order_id, $price, $transaction_id, $report_name, $payerId, $email) {
      $session_val = $this->request->session();
      $this->loadModel('Birthdata');
      $birthdataDetails = $this->Birthdata->find()->where(['order_id' => $order_id])->first();
      $gender = array ('M' => 'Male', 'F' => 'Female');
      $role = (!empty($session_val->read('Auth.User.role')) && (strtolower($session_val->read('Auth.User.role')) == 'elite')) ? 'Yes' : 'No';
      return $template = '<table style="width: 800px;">
                        <tbody>
                          <tr><td style="margin: 0; padding: 0;">Hi,</td></tr>
                          <tr><td>&nbsp;</td></tr>
                          <tr><td width="100%">You have received an elite report order. Order details are mentioned below: </td></tr>
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
                                  <td style="width: 50%;">User Type : </td>
                                  <td>Elite Member</td>
                                </tr>
                                <tr>
                                  <td style="width: 50%;">Email : </td>
                                  <td>'.$email.'</td>
                                </tr>
                                <tr>
                                  <td style="width: 50%;">Report Name : </td>
                                  <td>'.$report_name.'</td>
                                </tr>
                                <tr>
                                  <td style="width: 50%;">Order Date : </td>
                                  <td>'.date('d-m-Y h:i:sa').'</td>
                                </tr>
                                <tr>
                                  <td style="width: 50%;">Order No. : </td>
                                  <td>'.$payerId.'</td>
                                </tr>
                                <tr>
                                  <td style="width: 50%;">Amount : </td>
                                  <td>'.str_replace(" ", "", $price).'</td>
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
                                  <td>'.$gender[$birthdataDetails["gender"]].'</td>
                                </tr>
                                <tr style="display: table-row; vertical-align: inherit; border-color: inherit;">
                                  <td style="width: 50%;">Date of Birth : </td>
                                  <td>'.sprintf("%02d", $birthdataDetails["day"])."-".sprintf("%02d", $birthdataDetails["month"])."-".$birthdataDetails["year"].'</td>
                                </tr>
                                <tr style="display: table-row; vertical-align: inherit; border-color: inherit;">
                                  <td style="width: 50%;">Birth Time : </td>
                                  <td>'.$birthdataDetails["hour"].":".$birthdataDetails["minute"].'</td>
                                </tr>
                              </table>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    <br><p>Thanks,</p><p>AstroWow Team</p>';
    }

    /**
     * Save data into birthdata table of purchased report by elite member
     * Created by : Krishna Gupta
     * Created Date : Dec. 20, 2016
     * Modified Date : Jan. 09, 2017
     */
    protected function saveBirthDataAsOrder ($orderId) {
        $this->loadModel ('Birthdata');
        $this->loadModel ('BirthDetails');
        $session = $this->request->session();
        $user_id = !empty($session->read('Auth.User.id')) ? $session->read('Auth.User.id') : $session->read('user_id');
        $birthDataTable = TableRegistry::get('Birthdata');
        $entity = $this->Birthdata->newEntity();
        if (!empty($session->read('EliteReportOrder.BirthData'))) {
          $date11 = explode(' - ', $session->read('EliteReportOrder.BirthData.birth_date'));
          //$formattedDate = str_replace('/', '-', $date11[0]);
          $birthday = explode('/', $date11[0]);
          //$selectedDate = date ('m-d-Y', strtotime($formattedDate));
          //$birthday = date_parse($selectedDate);
          $entity->day = $birthday[0]; //$birthday['day'];
          $entity->month = $birthday[1]; //$birthday['month'];
          $entity->year = $birthday[2]; //$birthday['year'];
          $entity->untimed = 0;
        } else {
          $entity->day = 0;
          $entity->month = 0;
          $entity->year = 0;
          $entity->untimed = 1;
        }
        /*if( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' ) {
          echo '<pre>'; print_r($session->read()); die;
        }*/
        //pr ($session->read('EliteReportOrder.BirthData'));
        //pr ($entity); die;
        if (!empty($session->read('EliteReportOrder.BirthData'))) {
            $entity->hour = $session->read('EliteReportOrder.BirthData.hours');
            $entity->minute = $session->read('EliteReportOrder.BirthData.minutes');
        } else {
            $entity->hour = 0;
            $entity->minute = 0;
        }
        if ($session->read('EliteReportOrder.BirthData.age')) {
          $entity->age = $session->read('EliteReportOrder.BirthData.age');
          $entity->start_date = ($entity->year+$entity->age).'-'.$entity->month.'-'.$entity->day;
        } else {
          $entity->age = 0;
          $entity->start_date = date('Y-m-d', time());
        }

        $this->loadModel ('Cities');
        $this->loadModel ('Countries');
        $cityData = [];
        $cityData = $this->Cities->find()->select(['id', 'longitude', 'city', 'latitude', 'typetable', 'zonetable'])->where (['id' => $session->read('EliteReportOrder.BirthData.city_id')])->first();
        //echo '<pre>'; print_r($cityData); print_r($session->read()); die;
        $cityData['day'] = $entity->day;
        $cityData['month'] = $entity->month;
        $cityData['year'] = $entity->year;
        $cityData['minutes'] = $entity->minute;
        $cityData['hours'] = $entity->hour;
        $cityData['city_name'] = $cityData['city'];
        $cityData['country_id'] = $session->read('EliteReportOrder.BirthData.country_id');
        $cityData['status'] = 'normal';
        $this->SetLatLong ($cityData);
        $entity->zoneref = !empty($cityData['zone']) ? $cityData['zone'] : 0.00; //$cityData['birth_detail']['zone'];
        $entity->summerref = !empty($cityData['type']) ? $cityData['type'] : 0.00; //$cityData['birth_detail']['type'];
        $entity->place = $cityData['city'];
        $entity->state = $this->getBirthCountryDetails ($session->read('EliteReportOrder.BirthData.country_id'));
        $entity->longitude = $cityData['longitude'];
        $entity->latitude = $cityData['latitude'];
        $entity->order_id = $orderId;
        $entity->first_name = $session->read('EliteReportOrder.UserDetail.first_name');
        $entity->last_name = $session->read('EliteReportOrder.UserDetail.last_name');
        $entity->name_on_report = $entity->first_name.' '.$entity->last_name;
        $entity->duration = 3;
        //$entity->start_date = date('Y-m-d', time());
        $entity->gender = $session->read('EliteReportOrder.BirthData.gender');

        /*if( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' || $_SERVER['REMOTE_ADDR'] == '103.248.117.10' ) {
          echo '<pre>'; print_r($session->read('EliteReportOrder')); print_r($entity); die;
        }*/
        if ($birthDataTable->save($entity)) {
            return true;
        } else {

        }
    }

    /**
     * Get city details
     * Created by : Krishna Gupta
     * Created Date : Dec. 20, 2016
     */
    protected function getBirthCityDetails ($cityId) {
        $this->loadModel ('Cities');
        $citydata = $this->Cities->find()->select(['id', 'city', 'latitude', 'longitude'])->where(['id' => $cityId])->first();
        return $citydata;
    }

    /**
     * Get country details
     * Created by : Krishna Gupta
     * Created Date : Dec. 20, 2016
     */
    protected function getBirthCountryDetails ($countryId) {
        $this->loadModel ('Countries');
        $countrydata = $this->Countries->find()->select(['id', 'abbr'])->where(['id' => $countryId, 'Countries.status' => 1])->first();
        return $countrydata['abbr'];
    }

    /**
     * Get selected currency details
     * Created by : Krishna Gupta
     * Created Date : Dec. 20, 2016
     */
    protected function getCurrencyDetails ($id) {
        $this->loadModel ('Currencies');
        $result = $this->Currencies->find()->where(['id' => $id])->first();
        return $result;
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
    
    /**
     * Shows a thank you page after successfully become an elite member
     * Created by : Krishna Gupta
     * Created Date : Dec. 14, 2016
     */
    public function thankYou () {
      $canonical['en'] = SITE_URL.'elite-users/thank-you';
      $canonical['da'] = SITE_URL.'dk/elite-brugere/tak';
  	  $meta['title'] = 'Welcome to Astrowow.com';
  	  $meta['description'] = $meta['keywords'] = '';
  	  $this->set(compact('meta', 'canonical'));
      $session = $this->request->session();
      $user_id = !empty($session->read('Auth.User.id')) ? $session->read('Auth.User.id') : $session->read('user_id');
      if ($user_id) {
        if( $this->request->is('get') && !empty($this->request->query('hash'))) {
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
            if (empty($session->read('Elite'))) {
              return $this->redirect (['controller' => 'pages', 'action' => 'index']);
            }

            $this->loadModel('Orders');
            $checkPayerIdExistancy = $this->Orders->find()->where(['payer_order_id' => $this->request->session()->read('Elite.Elite Subscription Order Id')])->count();
            if ($checkPayerIdExistancy > 0) {
              $this->request->session()->delete('Elite');
              return $this->redirect(['controller' => 'users', 'action' => 'dashboard']);
            }

            $elitemembersTable = TableRegistry::get('EliteMembers');
            $entity = $elitemembersTable->newEntity();
            $memberId = $entity->user_id = $user_id;
            $ecurrency_code = $entity->currency_code = $session->read('Elite.Elite Currency Code');
            $eamount = $entity->amount = $session->read('Elite.Elite Customer Amount');
            $entity->currency = $session->read('Elite.Elite Currency Code');
            $start = $entity->start_date = time();
            $end = $entity->end_date = strtotime('+1 years');
            //$this->loadModel('Currencies');
            //$entityCurrencyCode = $this->Currencies->find()->where(['Currencies.id' => $entity->currency_id])->select(['id', 'code'])->first();
            //$purchaseEvent = "<script>fbq('track', 'Purchase', {value: ".$eamount.", currency: '".$entity->currency."' });</script>";

            $ordersTable = TableRegistry::get('Orders');
            $this->loadModel ('Products');
            $ProductDetails = $this->Products->find()->select(['id', 'seo_url'])->where(['seo_url' => 'elite-membership'])->first();

            $mydata['product_id'] = $ProductDetails['id'];
            $mydata['price'] = $this->request->session()->read('Elite.Elite Customer Amount');
            $mydata['user_id'] = $user_id;
            $mydata['email'] = $this->request->session()->read('Auth.User.username');
            $mydata['payer_order_id'] = $this->request->session()->read('Elite.Elite Subscription Order Id'); //'Elite-'.time();
            $mydata['delivery_option'] = 1;
            $mydata['order_status'] = 9;
            $mydata['order_date'] = date('Y-m-d H:i:s');
            $mydata['confirm_payment_date'] = date('Y-m-d H:i:s');
            $mydata['product_type'] = 11;
            $mydata['chk_for_register'] = 0;
            $this->loadModel ('Currencies');
            $currencyData = $this->Currencies->find ()->select (['id', 'code'])->where(['code' => $this->request->session()->read('Elite.Elite Currency Code')])->first();
            $mydata['currency_id'] = $currencyData['id'];
            $mydata['shipping_charge'] = 0.00;
            $selectedLan = !empty($this->request->session()->read('locale')) ? $this->request->session()->read('locale') : 'en';
            $lanArr = [1 => 'en', 2 => 'da'];
            $mydata['language_id'] = array_search($selectedLan, $lanArr);;
            $mydata['portal_id'] = 2;
            $mydata['payment_method'] = 1;

            $mydata['order_transaction']['payment_status'] = 'complete';
            $mydata['order_transaction']['payment_date'] = date ('Y-m-d H:i:s');
            $mydata['order_transaction']['full_name'] = $this->request->session()->read('Auth.UserProfile.first_name').' '.$this->request->session()->read('Auth.UserProfile.last_name');
            $mydata['order_transaction']['payer_email'] = $this->request->session()->read('Auth.User.username');
            $mydata['order_transaction']['amount'] = $mydata['price'];
            $mydata['order_transaction']['currency_code'] = $this->request->session()->read('Elite.Elite Currency Code');
            $mydata['order_transaction']['payer_order_id'] = $mydata['payer_order_id'];
            $mydata['order_transaction']['transaction_no'] = $_GET['txnid'];
            $data = $this->Orders->patchEntity($entity, $mydata, ['associated' => ['OrderTransactions']] );
            
            if ($ordersTable->save($data)) {
              $existancy = $this->EliteMembers->find()->where(['user_id' => $memberId])->first();
              if (empty($existancy)) {
                $query = $elitemembersTable->query();
                          $query->insert(['user_id', 'currency_code', 'amount', 'start_date', 'end_date'])
                              ->values([
                                  'user_id' => $user_id,
                                  'currency_code' => $entity->currency_code,
                                  'amount' => $entity->amount,
                                  'start_date' => $entity->start_date,
                                  'end_date' => $entity->end_date
                              ])
                              ->execute();
                //if ($elitemembersTable->save($entity)) {
                if ($query) {
                  $query = $this->Users->query();
                  $updateData = $query->update()
                                ->set(['role' => 'elite'])
                                ->where(['id' => $user_id])
                                ->execute();
                  $txnid = $this->request->query('txnid');
                  $order_id = $this->request->session()->read('Elite.Elite Subscription Order Id');; //$this->request->query('orderid');
                  
                  //if($this->checkIp()) {
                    $this->loadModel('UserThankyouMails');
                    $thankyouMailEntity = $this->UserThankyouMails->newEntity();
                    $thankyouMailEntity['order_id'] = $data->id;
                    $thankyouMailEntity['product_type'] = 11;
                    $this->UserThankyouMails->save($thankyouMailEntity);
                  /*} else {
                    $userdata = $this->Users->find()->select(['id', 'username'])->where(['id' => $user_id])->first();
                    $userprofile = $this->Profiles->find()->select(['id', 'first_name', 'last_name'])->where(['user_id' => $user_id])->first();
                    $to = $userdata['username'];
                    $name = ucwords($userprofile['first_name'].' '.$userprofile['last_name']).',';
                    $emailTemplate = $this->EmailTemplates->find()->where(['short_code' => 'elite_member_welcome'])->first();
                    if(!empty($emailTemplate)) {
                        $body = html_entity_decode($emailTemplate['content']);
                        $body = str_replace('{NAME}', $name, $body);
                        $lan = !empty($session->read('locale')) ? $session->read('locale') : 'en';
                        $loggedinText = (!empty($lan) && (strtolower($lan) == 'da')) ? 'logge ind' : 'log in';
                        $loginLink = '<a href="'.Router::url('/', true).'users/login" target="_blank">'.$loggedinText.'</a>';
                        $body = str_replace('{LOGIN}', $loginLink, $body);
                        $supportEmail = "<a href= 'mailto:support@astrowow.com'>support@astrowow.com</a>";
                        $body = str_replace('{SUPPORT_EMAIL_ADDRESS}', $supportEmail, $body);
                        $body = str_replace('{FACEBOOK}', FACEBOOK, $body);
                        $body = str_replace('{TWITTER}', TWITTER, $body);
                        $body = str_replace('{LINKEDIN}', LINKEDIN, $body);
                        $subject = $emailTemplate['name'];
                    } else {
                        $body = '';
                        $body .= "<div><div>Hello {NAME},</div><br><div>Now you are an elite member.</div><br/><div>Thank you,</div><div>Astrowow Team</div></div>";
                        $subject = 'Congratulations on becoming an Elite customer';
                    }
                    $body = str_replace('{NAME}', $name, $body);
                    $message = $this->replaceTemplateVariables($body);
                    $this->sendMail ($to, $subject, $message);
                  }*/
                  $session->delete('Elite');
                  $session->delete('Elite Currency Sign');
                  $this->request->session()->write ('Auth.User.role', 'elite');
                  //$purchaseEvent = "<script>fbq('track', 'Purchase', {value: ".$entity->amount.", currency: '".$entity->currency_code."' });</script>";
                  $this->request->session()->write('purchaseEvent.Price', $entity->amount);
                  $this->request->session()->write('purchaseEvent.Currency', $entity->currency_code);
                  $this->set(compact('txnid', 'order_id'/*, 'purchaseEvent'*/));
                }
              } else {
                  $start = $existancy['start_date'];
                  $end = strtotime('+1 years', $existancy['end_date']);
                  // Update data into elite member table and users table for elite member
                  $updateEliteMember = $this->EliteMembers->updateAll (['start_date' => $start, 'end_date' => $end, 'currency' => $session->read('Elite Currency Code'), 'currency_code' => $ecurrency_code, 'amount' => $eamount, 'membership_upgrade' => 1], ['user_id' => $user_id, 'id' => $existancy->id]);
                  $updateUserRole = $this->Users->updateAll (['role' => 'elite'], ['id' => $user_id]);

                  $txnid = $this->request->query('txnid');
                  $order_id = $this->request->query('orderid');
                  
                  //if($this->checkIp()) {
                    //die('kokokokooko');
                    $this->loadModel('UserThankyouMails');
                    $thankyouMailEntity = $this->UserThankyouMails->newEntity();
                    $thankyouMailEntity['order_id'] = $data->id;
                    $thankyouMailEntity['product_type'] = 11;
                    $this->UserThankyouMails->save($thankyouMailEntity);
                  /*} else {
                    $userdata = $this->Users->find()->select(['id', 'username'])->where(['id' => $user_id])->first();
                    $userprofile = $this->Profiles->find()->select(['id', 'first_name', 'last_name'])->where(['user_id' => $user_id])->first();
                    $to = $userdata['username'];
                    $name = ucwords($userprofile['first_name'].' '.$userprofile['last_name']);

                    $emailTemplate = $this->EmailTemplates->find()->where(['short_code' => 'upgrade_elite_membership'])->first();
                    if(!empty($emailTemplate)) {
                        $body = html_entity_decode($emailTemplate['content']);
                        $body = str_replace('{NAME}', $name, $body);
                        $loginLink = '<a href="'.Router::url('/', true).'users/login" target="_blank">log in</a>';
                        $body = str_replace('{LOGIN}', $loginLink, $body);
                        $supportEmail = '<a href="denied:support@astrowow.com">support@astrowow.com</a>';
                        $body = str_replace('{SUPPORT_EMAIL_ADDRESS}', $supportEmail, $body);
                        $subject = $emailTemplate['name'];
                    } else {
                        $body = '';
                        $body .= "<div><div>Hello {NAME},</div><br><div>Thank you for upgrading your elite membership subscription.</div><br/><div>Thank you,</div><div>Astrowow Team</div></div>";
                        $subject = 'Thank you for upgrading Elite membership';
                    }
                    $body = str_replace('{NAME}', $name, $body);
                    $message = $this->replaceTemplateVariables($body);
                    $this->sendMail ($to, $subject, $message);
                  }*/
                  $session->delete('Elite');
                  $this->request->session()->write ('Auth.User.role', 'elite');
                  //$purchaseEvent = "<script>fbq('track', 'Purchase', {value: ".$eamount.", currency: '".$ecurrency_code."' });</script>";
                  $this->request->session()->write('purchaseEvent.Price', $eamount);
                  $this->request->session()->write('purchaseEvent.Currency', $ecurrency_code);
                  $this->set(compact('txnid', 'order_id'/*, 'purchaseEvent'*/));
              }

              /*if( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' ) {
              } else {
                //echo '<pre>'; print_r($this->request->session()->read()); die;
                $usersObj = new UsersController();
                $msgBody = $usersObj->calendarAndEliteSubscriptionAdminMail ($data->id, $this->request->query('txnid'));
                $emailTemplate = new EmailTemplatesController();
                $emailTemplate->sendMailToAdmin('Elite membership subscription order received', $msgBody);
              }*/

            } else {
                $this->Flash->set(__('Something went wrong'), [ 'element' => 'error' ]);
                $this->redirect(['controller' => 'elite-users', 'action' => 'index']);
            }
            //$this->set(compact('purchaseEvent'));
          }
        } else {
            return $this->redirect (['controller' => 'pages', 'action' => 'index']);
        }
      } else {
          $this->Flash->set(__("You can't access that location."), [ 'element' => 'error' ]);
          $this->redirect(['controller' => 'users', 'action' => 'login']);
      }
    }

    /**
     * Replace email template variables
     * Created by : Krishna Gupta
     * Created Date : Dec. 15, 2016
     */
    private function replaceTemplateVariables ($data) {
      $data = str_replace('{IMAGES_URL}', TEMPLATE_IMAGES_URL, $data);
      $data = str_replace('{SITE_URL}', SITE_URL, $data);
      return $data;
    }

    /**
     * Elite user dashboard
     * Created by : Krishna Gupta
     * Created Date : Dec. 27, 2016
     */
    function dashboard () {
        $canonical['en'] = SITE_URL.'elite-users/dashboard';
        $canonical['da'] = SITE_URL.'dk/elite-brugere/instrumentbræt';
        $meta['title'] = 'Welcome to Elite Customer\'s Dashboard';
        $meta['description'] = '';
        $meta['keywords'] = '';
   		$this->set(compact('meta', 'canonical'));
      $session = $this->request->session();
      $user_id = !empty($session->read('Auth.User.id')) ? $session->read('Auth.User.id') : $session->read('user_id');
      if ($user_id) {
        $today = time();
        $checkEliteMemberValidity = $this->EliteMembers->find()->where(['user_id' => $user_id, 'start_date <= ' => $today, 'end_date >= ' => $today])->first();
        if (!empty($checkEliteMemberValidity)) {
            $this->request->session()->write ('EliteMemberDetail.status', 'yes');
            $this->request->session()->write ('EliteMemberDetail.expireOn', $checkEliteMemberValidity->end_date);
            $expireOn = date ('F dS, Y', $checkEliteMemberValidity->end_date);
            $newOrders = $this->Orders->find()->where(['order_status' => 12, 'user_id' => $user_id, 'product_type IN' => [5, 8]])->count();
            $inProcessOrders = $this->Orders->find()->where(['order_status' => 4, 'user_id' => $user_id, 'product_type IN' => [5, 8]])->count();
            $closedOrders = $this->Orders->find()->where(['order_status' => 9, 'user_id' => $user_id, 'product_type IN' => [5, 8]])->count();
            $totalOrders = $this->Orders->find()->where(['user_id' => $user_id, 'order_status IN ' => [4, 9, 12], 'product_type IN' => [5, 8]])->count();
            $product_type_id = 8;
            $this->loadModel ('DeliveryOptions');
            $this->loadModel ('States');
            $this->loadModel ('Languages');
            $deliveryOption = $this->DeliveryOptions->find ()->where(['slug' => 'email'])->first();
            $states = $this->States->find ()->where(['name' => 'queued'])->first();
            $languages = $this->Languages->find ()->where(['code' => 'en'])->select(['id', 'code'])->first();
            $selectedCurrencyCode = $checkEliteMemberValidity["currency_code"];
            $this->loadModel ('Currencies');
            $selectedCurrencyDetail = $this->Currencies->find ()->where (['Currencies.code' => $selectedCurrencyCode])->first ();
            $selectedCurrencyId = $selectedCurrencyDetail['id'];
            $products_detail = $this->Products->find('all')
                                      -> join ([
                                            'ProductPrices' => [
                                                          'table' => 'product_prices',
                                                          'type' => 'INNER',
                                                          'conditions' => [ 'ProductPrices.product_id = Products.id', 'ProductPrices.product_type_id = 8', 'ProductPrices.currency_id' => $selectedCurrencyId ]
                                                          ]
                                        ])
                                      ->where(['Products.id IN' => [19, 17, 13], 'Products.category_id' => 2, 'Products.status' => 1] )
                                      -> select (['ProductPrices.id', 'ProductPrices.product_id', 'ProductPrices.product_type_id', 'ProductPrices.currency_id', 'ProductPrices.total_price', 'Products.id', 'Products.category_id', 'Products.name', 'Products.short_description', 'Products.description', 'Products.image', 'Products.pages', 'Products.seo_url', 'Products.status'])
                                      ->order(['Products.id' => 'DESC'])
                                      ->toArray();
            $preferedLanguages = [];
            $this->set(compact('newOrders', 'closedOrders', 'totalOrders', 'inProcessOrders', 'products_detail', 'selectedCurrencyDetail', 'user_id', 'states', 'deliveryOption', 'selectedCurrencyDetail', 'languages', 'expireOn'));
        } else {
            $this->Flash->set(__('You are not an elite member'), [ 'element' => 'error' ]);
            $this->redirect(['controller' => 'users', 'action' => 'dashboard']);
        }
      } else {
        $this->Flash->set(__("You can't access that location."), [ 'element' => 'error' ]);
        $this->redirect(['controller' => 'users', 'action' => 'login']);
      }
    }

    /**
     * Elite user account details
     * Created by : Krishna Gupta
     * Created Date : Dec. 28, 2016
     */
    /*function myAccount () {
      $session = $this->request->session();
        $user_id = !empty($session->read('Auth.User.id')) ? $session->read('Auth.User.id') : $session->read('user_id');
        if ($user_id) {
            if ($this->request->session()->read('EliteMemberDetail.status') == 'yes') {
                ini_set('max_execution_time', 300);
                ini_set('memory_limit', '2048M');
                // $newOrders = $this->Orders->find()->where(['order_status' => 12, 'user_id' => $user_id])->count();
                // $inProcessOrders = $this->Orders->find()->where(['order_status' => 4, 'user_id' => $user_id])->count();
                // $closedOrders = $this->Orders->find()->where(['order_status' => 9, 'user_id' => $user_id])->count();
                // $totalOrders = $this->Orders->find()->where(['user_id' => $user_id, 'order_status IN ' => [4, 9, 12]])->count();
                $newOrders = $this->Orders->find()->where(['order_status' => 12, 'user_id' => $user_id, 'product_type IN' => [5, 8]])->count();
                $inProcessOrders = $this->Orders->find()->where(['order_status' => 4, 'user_id' => $user_id, 'product_type IN' => [5, 8]])->count();
                $closedOrders = $this->Orders->find()->where(['order_status' => 9, 'user_id' => $user_id, 'product_type IN' => [5, 8]])->count();
                $totalOrders = $this->Orders->find()->where(['user_id' => $user_id, 'order_status IN ' => [4, 9, 12], 'product_type IN' => [5, 8]])->count();
                $this->loadModel ('Countries');
                $this->loadModel ('Cities');
                $profile = $this->Profiles->findByUserId ($user_id)->first()->toArray ();
                $city_id = $profile['city_id'];
                $country_id = $profile['country_id'];
                $Countries = $this->Countries->find()->order(['Countries.name' => 'ASC']);
                $Cities = $this->Cities->find()->select(['id', 'city', 'country_id'])->where(['country_id' => $country_id, 'city !=' => ''])->order(['city' => 'ASC'])->toArray();
                $this->loadModel ('ProductLanguages');
                $reportLanguages = $this->ProductLanguages->find ()
                                                  -> join([
                                                      'Categories' => [
                                                          'table' => 'categories',
                                                          'type' => 'INNER',
                                                          'conditions' => [ 'Categories.name = "Reports"' ] 
                                                      ],
                                                      'Products' => [
                                                          'table' => 'products',
                                                          'type' => 'INNER',
                                                          'conditions' => [ 'Products.id = ProductLanguages.product_id', 'Products.category_id = Categories.id' ] 
                                                      ],
                                                      'Languages' => [
                                                          'table' => 'languages',
                                                          'type' => 'INNER',
                                                          'conditions' => [ 'Languages.id = ProductLanguages.language_id' ] 
                                                      ]
                                                  ])
                                                  -> select (['ProductLanguages.product_id', 'ProductLanguages.language_id', 'Categories.name', 'Categories.name', 'Languages.id', 'Languages.name'])
                                                  -> distinct ('language_id')
                                                  -> toArray ();
                $preferedLanguages = [];
                foreach ($reportLanguages as $language) {
                    $preferedLanguages[$language['Languages']['id']] = $language['Languages']['name'];
                }
                $this->loadModel ('Currencies');
                $currencyData = $this->Currencies->find ('all')->select(['id', 'code', 'name'])->toArray ();
                $currencyList = [];
                foreach ($currencyData as $currencyList1) {
                    $currencyList[$currencyList1['code']] = $currencyList1['name'];
                }
                $this->loadModel ('Profiles');
                $this->set(compact('newOrders', 'closedOrders', 'totalOrders', 'inProcessOrders', 'Countries', 'Cities', 'country_id', 'city_id', 'profile', 'reportLanguages', 'preferedLanguages', 'currencyList'));
            } else {
                $this->Flash->set(__('You are not an elite member'), [ 'element' => 'error' ]);
                $this->redirect(['controller' => 'users', 'action' => 'dashboard']);
            }
        } else {
            $this->Flash->set(__('You can\'t access that location.'), [ 'element' => 'error' ]);
            $this->redirect(['controller' => 'users', 'action' => 'login']);
        } 
    }*/

    /**
     * This function is used to change loggedin elite user password
     * Created By : Kingslay <kingslay@123789.org>
     * Created Date : Dec. 29, 2016
     */
    /*function changePassword () {
        $session = $this->request->session();
        $user_id = !empty($session->read('Auth.User.id')) ? $session->read('Auth.User.id') : $session->read('user_id');
        if ($user_id) {
            if ($this->request->session()->read('EliteMemberDetail.status') == 'yes') {
                $this->loadModel ('Orders');
                $newOrders = $this->Orders->find()->where(['order_status' => 12, 'user_id' => $user_id, 'product_type IN' => [5, 8]])->count();
                $inProcessOrders = $this->Orders->find()->where(['order_status' => 4, 'user_id' => $user_id, 'product_type IN' => [5, 8]])->count();
                $closedOrders = $this->Orders->find()->where(['order_status' => 9, 'user_id' => $user_id, 'product_type IN' => [5, 8]])->count();
                $totalOrders = $this->Orders->find()->where(['user_id' => $user_id, 'order_status IN ' => [4, 9, 12], 'product_type IN' => [5, 8]])->count();
                $header = 'Change Password';
                $this->set(compact('header', 'newOrders', 'inProcessOrders', 'closedOrders', 'totalOrders'));
                $this->viewBuilder()->layout('home');
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
                          $this->Flash->set(__('Your password has been changed successfully.'), [ 'element' => 'success' ]);
                          return $this->redirect(['controller' => 'elite-users', 'action' => 'myAccount']);
                        } else {
                          return $this->Flash->error('The new password does not match!');
                        }
                    } else {
                        return $this->Flash->error('The old password does not match the current password!');
                    }
                }
            } else {
                $this->Flash->set(__('You are not an elite member'), [ 'element' => 'error' ]);
                $this->redirect(['controller' => 'users', 'action' => 'dashboard']);
            }
        } else {
            $this->Flash->set(__('You can\'t access that location.'), [ 'element' => 'error' ]);
            $this->redirect(['controller' => 'users', 'action' => 'login']);
        }
    }*/

    /**
     * This function is used to update loggedin elite user details
     * Created By : Kingslay <kingslay@123789.org>
     * Created Date : Dec. 29, 2016
     */
    /*function updateProfile () {
        $this->autoRender = false;
        $session = $this->request->session();
        $user_id = !empty($session->read('Auth.User.id')) ? $session->read('Auth.User.id') : $session->read('user_id');
        if ($user_id) {
            if (!empty($this->request->data)) {
                $first_name = $this->request->data['txtFirstName'];
                $last_name = $this->request->data['txtLastName'];
                $address = $this->request->data['txtAdd1'];
                $country_id = $this->request->data['ddBirthCountry'];
                $city_id = $this->request->data['ddBirthCity'];
                $gender = $this->request->data['rdoGender'];
                $query = $this->Profiles->query();
                $status = $query->update ()
                              -> set (['first_name' => $first_name, 'last_name' => $last_name, 'gender' => $gender, 'address' => $address, 'city_id' => $city_id, 'country_id' => $country_id ])
                              -> where (['user_id' => $user_id])
                              -> execute();
                
                if ($status) {
                    $this->Flash->set(__('Profile details updated successfully.'), [ 'element' => 'success' ]);
                    $this->redirect(['controller' => 'EliteUsers', 'action' => 'myAccount']);
                }
            }
        } else {
            $this->Flash->set(__('You can\'t access that location.'), [ 'element' => 'error' ]);
            $this->redirect(['controller' => 'users', 'action' => 'login']);
        }
    }*/

    /**
     * Lits all order list of loggedin elite user
     * Created By : Kingslay <kingslay@123789.org>
     * Created Date : Dec. 29, 2016
     */
    function orderList () {
        $canonical['en'] = SITE_URL.'elite-users/order-list';
        $canonical['da'] = SITE_URL.'dk/elite-brugere/liste-ordre';
        $meta['title'] = "Astrowow : Elite Customer Order Queue";
        $meta['description'] = '';
        $meta['keywords'] = '';
        $this->set(compact('meta', 'canonical'));
      	$session = $this->request->session();
        $user_id = !empty($session->read('Auth.User.id')) ? $session->read('Auth.User.id') : $session->read('user_id');
        if ($user_id) {
          if ($this->request->session()->read('EliteMemberDetail.status') == 'yes') {
            /*$newOrders = $this->Orders->find()->where(['order_status' => 12, 'user_id' => $user_id])->count();
            $inProcessOrders = $this->Orders->find()->where(['order_status' => 4, 'user_id' => $user_id])->count();
            $closedOrders = $this->Orders->find()->where(['order_status' => 9, 'user_id' => $user_id])->count();
            $totalOrders = $this->Orders->find()->where(['user_id' => $user_id, 'order_status IN ' => [4, 9, 12]])->count();*/
            $newOrders = $this->Orders->find()->where(['order_status' => 12, 'user_id' => $user_id, 'product_type IN' => [5, 8]])->count();
            $inProcessOrders = $this->Orders->find()->where(['order_status' => 4, 'user_id' => $user_id, 'product_type IN' => [5, 8]])->count();
            $closedOrders = $this->Orders->find()->where(['order_status' => 9, 'user_id' => $user_id, 'product_type IN' => [5, 8]])->count();
            $totalOrders = $this->Orders->find()->where(['user_id' => $user_id, 'order_status IN ' => [4, 9, 12], 'product_type IN' => [5, 8]])->count();
            $orders = $this->Orders->find()
                                  ->join([
                                        'BirthData' => [
                                            'table' => 'birthdata',
                                            'type' => 'INNER',
                                            'conditions' => [ 'BirthData.order_id = Orders.id' ] 
                                        ]
                                    ])
                                   ->select([ 'Orders.id', 'Orders.payer_order_id', 'Orders.product_id', 'Orders.price', 'Orders.user_id', 'Orders.email', 'Orders.order_status', 'Orders.order_date', 'Orders.currency_id', 'BirthData.id', 'BirthData.first_name', 'BirthData.last_name', 'BirthData.order_id' ])
                                   ->where( ['Orders.user_id' => $user_id, 'Orders.product_type IN' => [5, 8]] )
                                   ->order(['Orders.id DESC'])
                                   ->group (['Orders.id']);
                                   //->toArray();
            $paginate['limit'] = 10;
            $orders = $this->Paginator->paginate($orders, $paginate);
            $this->loadModel ('states');
            $states = $this->states->find ('list')->toArray();
             $this->set(compact('newOrders', 'closedOrders', 'totalOrders', 'inProcessOrders', 'orders', 'states'));
          } else {
              $this->Flash->set(__('You are not an elite member'), [ 'element' => 'error' ]);
              $this->redirect(['controller' => 'users', 'action' => 'dashboard']);
          }
        } else {
            $this->Flash->set(__("You can't access that location."), [ 'element' => 'error' ]);
            $this->redirect(['controller' => 'users', 'action' => 'login']);
        }
    }

    /**
     * paricular order detail of loggedin elite user
     * Created By : Kingslay <kingslay@123789.org>
     * Created Date : Dec. 30, 2016
     */
    function orderDetail ($orderId=null) {
      $canonical['en'] = SITE_URL.'elite-users/order-detail';
      $canonical['da'] = SITE_URL.'dk/elite-brugere/ordre-detalje';
      $session = $this->request->session();
      $meta['title'] = "Astrowow : Elite Customer Order Queue";
      $meta['description'] = '';
      $meta['keywords'] = '';
      $this->set(compact('meta', 'canonical'));
      $user_id = !empty($session->read('Auth.User.id')) ? $session->read('Auth.User.id') : $session->read('user_id');
      if (!empty($orderId)) {
        if ($user_id) {
          if ($this->request->session()->read('EliteMemberDetail.status') == 'yes') {
            //if (!empty($orderId)) {
              $orderId = base64_decode($orderId); //base64_decode($this->request->query ('q'));
              $newOrders = $this->Orders->find()->where(['order_status' => 12, 'user_id' => $user_id, 'product_type IN' => [5, 8]])->count();
              $inProcessOrders = $this->Orders->find()->where(['order_status' => 4, 'user_id' => $user_id, 'product_type IN' => [5, 8]])->count();
              $closedOrders = $this->Orders->find()->where(['order_status' => 9, 'user_id' => $user_id, 'product_type IN' => [5, 8]])->count();
              $totalOrders = $this->Orders->find()->where(['user_id' => $user_id, 'order_status IN ' => [4, 9, 12], 'product_type IN' => [5, 8]])->count();
              $this->loadModel ('States');
              $this->loadModel ('Languages');
              $OrderDetails = $this->Orders->find()
                                  ->join([
                                        'Products' => [
                                            'table' => 'products',
                                            'type' => 'INNER',
                                            'conditions' => [ 'Products.id = Orders.product_id' ] 
                                        ],
                                        'Users' => [
                                            'table' => 'users',
                                            'type' => 'INNER',
                                            'conditions' => [ 'Users.id = Orders.user_id' ] 
                                        ],
                                        'States' => [
                                            'table' => 'states',
                                            'type' => 'INNER',
                                            'conditions' => [ 'States.id = Orders.order_status' ] 
                                        ],
                                        'Currencies' => [
                                            'table' => 'currencies',
                                            'type' => 'INNER',
                                            'conditions' => [ 'Currencies.id = Orders.currency_id' ] 
                                        ],
                                        'Languages' => [
                                            'table' => 'languages',
                                            'type' => 'INNER',
                                            'conditions' => [ 'Languages.id = Orders.language_id' ] 
                                        ],
                                        'Profiles' => [
                                            'table' => 'profiles',
                                            'type' => 'INNER',
                                            'conditions' => [ 'Profiles.user_id = Orders.user_id' ] 
                                        ],
                                        'BirthDetail' => [
                                            'table' => 'birth_details',
                                            'type' => 'INNER',
                                            'conditions' => [ 'BirthDetail.user_id = Orders.user_id' ] 
                                        ],
                                        'Cities' => [ // birth city
                                            'table' => 'cities',
                                            'type' => 'INNER',
                                            'conditions' => [ 'Cities.id = BirthDetail.city_id' ] 
                                        ],
                                        'Countries' => [ // birth country
                                            'table' => 'countries',
                                            'type' => 'INNER',
                                            'conditions' => [ 'Countries.id = BirthDetail.country_id', 'Countries.status' => 1] 
                                        ],
                                        'BirthData' => [
                                            'table' => 'birthdata',
                                            'type' => 'INNER',
                                            'conditions' => [ 'BirthData.order_id = Orders.id' ] 
                                        ]
                                    ])
                                   ->select([ 'Orders.id', 'Orders.payer_order_id', 'Orders.product_id', 'Orders.price', 'Orders.user_id', 'Orders.email', 'Orders.order_status', 'Orders.order_date', 'Orders.currency_id', 'States.name', 'Currencies.name', 'Currencies.code', 'Currencies.symbol', 'Languages.name', 'Languages.code', 'Profiles.first_name', 'Profiles.last_name', 'Profiles.gender', 'Profiles.city_id', 'Profiles.country_id', 'Countries.name', 'Cities.city', 'Products.name', 'BirthDetail.date', 'BirthDetail.zone', 'BirthDetail.time', 'BirthData.id', 'BirthData.first_name', 'BirthData.last_name', 'BirthData.order_id' ])
                                   ->where( ['Orders.id' => $orderId] )
                                   -> first ();
              if ($OrderDetails) {
                $TimeZoneData = $this->getTimezoneAndSummerTimezoneOnDashboard ($OrderDetails['BirthDetail']['zone']);
                $this->set(compact('newOrders', 'closedOrders', 'totalOrders', 'inProcessOrders', 'OrderDetails', 'TimeZoneData'));
              } else {
                  $this->Flash->set(__('Invalid order request'), [ 'element' => 'error' ]);
                  return $this->redirect(['controller' => 'elite-users', 'action' => 'order-list']);
              }
            /*} else {
                $this->Flash->set(__('Invalid order request'), [ 'element' => 'error' ]);
                return $this->redirect(['controller' => 'elite-users', 'action' => 'order-list']);
            }*/
          } else {
              $this->Flash->set(__('You are not an elite member'), [ 'element' => 'error' ]);
              $this->redirect(['controller' => 'users', 'action' => 'dashboard']);
          }
        } else {
            $this->Flash->set(__("You can't access that location."), [ 'element' => 'error' ]);
            $this->redirect(['controller' => 'users', 'action' => 'login']);
        }
      } else {
                $this->Flash->set(__('Invalid order request'), [ 'element' => 'error' ]);
                return $this->redirect(['controller' => 'elite-users', 'action' => 'order-list']);
            }
    }


    /**
     * Used to show timezone and summer reff on dashboard
     * Created By : Kingslay <kingslay@123789.org>
     * Created Date : Dec. 30, 2016
     */
    protected function getTimezoneAndSummerTimezoneOnDashboard ($tzone) {
        $TimeZone =  number_format(abs($tzone), 2);
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
     * Used to show timezone and summer reff on dashboard
     * Created By : Kingslay <kingslay@123789.org>
     * Created Date : Dec. 30, 2016
     */
    function customizeReports () {
        $canonical['en'] = SITE_URL.'elite-users/customize-reports';
        $canonical['da'] = SITE_URL.'dk/elite-brugere/tilpas-rapporter';
      	$meta['title'] = 'Welcome to Astrowow.com';
        $meta['description'] = $meta['keywords'] = '';
        $this->set(compact('meta', 'canonical'));
        $session = $this->request->session();
        //pr ($session->read()); die;
        $user_id = !empty($session->read('Auth.User.id')) ? $session->read('Auth.User.id') : $session->read('user_id');
        if ($user_id) {

          if (strtolower($this->request->session()->read('EliteMemberDetail.status')) == strtolower('yes')) {
            $newOrders = $this->Orders->find()->where(['order_status' => 12, 'user_id' => $user_id, 'product_type IN' => [5, 8]])->count();
            $inProcessOrders = $this->Orders->find()->where(['order_status' => 4, 'user_id' => $user_id, 'product_type IN' => [5, 8]])->count();
            $closedOrders = $this->Orders->find()->where(['order_status' => 9, 'user_id' => $user_id, 'product_type IN' => [5, 8]])->count();
            $totalOrders = $this->Orders->find()->where(['user_id' => $user_id, 'order_status IN ' => [4, 9, 12], 'product_type IN' => [5, 8]])->count();
            $entity = $this->EliteMembers->newEntity();
            if (!empty($this->request->data)) {
              if (empty($this->request->data['company_name'])) {
                  $this->Flash->set(__('Unable to save data. Please fill all the required fields.'), [ 'element' => 'error' ]);
                  $this->redirect(['controller' => 'elite-users', 'action' => 'customize-reports']);
              } else {
                $cover_page_updated = 0;
                if (!empty($this->request->data['cover_page']['name'])) {
                    $ext = pathinfo($this->request->data['cover_page']['name'], PATHINFO_EXTENSION);
                    if ($ext == 'pdf') {
                      $this->request->data['cover_page']['custom_index'] = 'elite-cover-page';
                      $cover_page_updated = 1;
                      $this->request->data['cover_page'] = $this->FileUpload->doFileUpload($this->request->data['cover_page']);
                    }
                } else {
                  if (!empty($this->request->data['existing_coverPage'])) {
                    $this->request->data['cover_page'] = $this->request->data['existing_coverPage'];
                  } else {
                    $this->request->data['cover_page'] = '';
                  }
                }
                $successMsg = '';
                $compName = $this->request->data['company_name'];
                $url = $this->request->data['url'];
                $cover_page = $this->request->data['cover_page'];
                $footer = $this->request->data['footer'];
                $elitemembers = TableRegistry::get('EliteMembers');
                $query = $elitemembers->query();
                if ($cover_page_updated) {
                  $updateData = $query->update()
                      ->set(['company_name' => $compName, 'url' => $url, 'cover_page' => $cover_page, 'footer' => $footer, 'status' => 1])
                      ->where(['user_id' => $user_id])
                      ->execute();
                  $localeVal = !empty($session->read('locale')) ? $session->read('locale') : 'en';
                  /*if ($localeVal == 'da') {
                    $successMsg = "Dine personlige indstillinger bliver anvendt inden for 5 minutter";
                  } else {*/
                    $successMsg = "Your customization settings will be applied within 5 minutes.";
                  //}
                } else {
                  $updateData = $query->update()
                      ->set(['company_name' => $compName, 'url' => $url, 'cover_page' => $cover_page, 'footer' => $footer])
                      ->where(['user_id' => $user_id])
                      ->execute();
                  $successMsg = 'Your customization settings have been saved';
                }
                if ($updateData) {
                        $this->Flash->set(__($successMsg), [ 'element' => 'success' ]);
                        $this->redirect(['controller' => 'elite-users', 'action' => 'dashboard']);
                } else {
                    $this->Flash->set(__('We are unable to save your settings'), [ 'element' => 'error' ]);
                }
              }
            } else {
                $this->loadModel ('EliteMembers');
                $this->request->data = $this->EliteMembers->find()->where(['user_id' => $user_id])->first();
            }
            $this->set(compact('newOrders', 'inProcessOrders', 'closedOrders', 'totalOrders', 'entity'));
          } else {
            $this->Flash->set(__('You are not an elite member'), [ 'element' => 'error' ]);
            $this->redirect(['controller' => 'users', 'action' => 'dashboard']);
          }
        } else {
            $this->Flash->set(__("You can't access that location."), [ 'element' => 'error' ]);
            $this->redirect(['controller' => 'users', 'action' => 'login']);
        }
    }


    /**
     * Used to dowload closed reports
     * Created By : Krishna Gupta
     * Created Date : Jan. 20, 2017
     */
    function download ($OrderId) {
        $session = $this->request->session();
        $user_id = !empty($session->read('Auth.User.id')) ? $session->read('Auth.User.id') : $session->read('user_id');
        if ($user_id) {
          if ($OrderId) {
            $this->set(compact('OrderId'));
          } else {
            $this->Flash->set(__("Something went wrong"), [ 'element' => 'error' ]);
            $this->redirect(['controller' => 'elite-users', 'action' => 'order-list']);
          }
        } else {
            $this->Flash->set(__("You can't access that location."), [ 'element' => 'error' ]);
            $this->redirect(['controller' => 'users', 'action' => 'login']);
        }
    }

    /**
     * Used to regenerate closed reports
     * Created By : Krishna Gupta
     * Created Date : Jan. 20, 2017
     */
    /*function regenerate () {
      $session = $this->request->session();
      $user_id = !empty($session->read('Auth.User.id')) ? $session->read('Auth.User.id') : $session->read('user_id');
      if ($user_id) {
        $this->autoRender = false;
        $orderId = base64_decode(trim($this->request->query('q')));
        $Orders = TableRegistry::get('Orders');
        $query = $Orders->query();
        $UpdatedSuc = $query->update()
                      ->set(['order_status' => 12])
                      ->where(['id' => $orderId])
                      ->execute();
        if ($UpdatedSuc) {
            $this->Flash->set(__("Your requested report has been regenerated successfully"), [ 'element' => 'success' ]);
            $this->redirect(['action' => 'order-list']);
        } else {
            $this->Flash->set(__("Something went wrong"), [ 'element' => 'error' ]);
            $this->redirect(['action' => 'order-list']);
        }
      } else {
          $this->Flash->set(__("You can't access that location."), [ 'element' => 'error' ]);
          $this->redirect(['controller' => 'users', 'action' => 'login']);
      }
    }*/


    /**
     * Used to regenerate closed reports
     * Created By : Krishna Gupta
     * Created Date : Jan. 20, 2017
     * Modified Date : Apr. 28, 2017
     */
    function regenerate ($orderId) {
      $session = $this->request->session();
      $user_id = !empty($session->read('Auth.User.id')) ? $session->read('Auth.User.id') : $session->read('user_id');
      if (!empty($orderId)) {
        if ($user_id) {
          $this->autoRender = false;
          $orderId = base64_decode(trim($orderId));
          $Orders = TableRegistry::get('Orders');
          $query = $Orders->query();
          $UpdatedSuc = $query->update()
                        ->set(['order_status' => 12])
                        ->where(['id' => $orderId])
                        ->execute();
          if ($UpdatedSuc) {
              $this->Flash->set(__("Your requested report has been regenerated successfully"), [ 'element' => 'success' ]);
              $this->redirect(['controller' => 'elite-users', 'action' => 'order-list']);
          } else {
              $this->Flash->set(__("Something went wrong"), [ 'element' => 'error' ]);
              $this->redirect(['controller' => 'elite-users', 'action' => 'order-list']);
          }
        } else {
            $this->Flash->set(__("You can't access that location."), [ 'element' => 'error' ]);
            $this->redirect(['controller' => 'users', 'action' => 'login']);
        }
      } else {
          $this->Flash->error(__('Unable to process your request'));
          $this->redirect(['controller' => 'elite-users', 'action' => 'order-list']);
      }
    }
}
