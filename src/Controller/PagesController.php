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

use Cake\Core\Configure;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Cake\Event\Event;
use Cake\I18n\I18n;
use Cake\Cache\Cache;
use Cake\Cache\CacheEngine;
use Cake\Cache\CacheRegistry;
use Cake\Routing\Router;
/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link http://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class PagesController extends AppController
{
    /**
     * Displays a view
     *
     * @return void|\Cake\Network\Response
     * @throws \Cake\Network\Exception\NotFoundException When the view file could not
     *   be found or \Cake\View\Exception\MissingTemplateException in debug mode.
     */
	 public function initialize(){

		     parent::initialize();
         $this->loadModel('Books');
         $this->loadModel('Testimonials');
         $this->loadModel('Menus');
         $this->loadModel('SunSigns');
         $this->loadModel('Settings');
         $this->loadModel('Media');
         $this->loadModel('Currencies');
         $this->loadModel('Categories');

         if ($this->request->session()->read('locale') == 'en') 
         {
             I18n::locale('en_US');
         }
         elseif ($this->request->session()->read('locale') == 'da')
         {
             I18n::locale('da');   
         }
         
         $this->Menus->recover();
         $user_id = $this->request->session()->read('user_id');
         $this->set('user_id', $user_id);
         $step = $this->request->session()->read('step');
         $this->set('step', $step);
         $this->viewBuilder()->layout('home');
	 }
	 
    public function beforeFilter(Event $event){
        $this->Auth->allow();
    }

    

    
    public function display(){
                    $path = func_get_args();
                		$this->autoRender = false;
                		$this->viewBuilder()->layout('');
                		$count = count($path);
                        if (!$count) {
                            return $this->redirect('/');
                        }
                        $page = $subpage = null;

                        if (!empty($path[0])) {
                            $page = $path[0];
                        }
                        if (!empty($path[1])) {
                            $subpage = $path[1];
                        }
                        $this->set(compact('page', 'subpage'));

                        try {
                            $this->render(implode('/', $path));
                        } catch (MissingTemplateException $e) {
                            if (Configure::read('debug')) {
                                throw $e;
                            }
                            throw new NotFoundException();
                        }
    }

    public function index() {

        $this->loadModel ('MiniBlogs');

        if(Cache::read('sunsigns', 'long') == false)
        {
         $allsunsigns = $this->SunSigns->find('all')->toArray();
         Cache::write('sunsigns', $allsunsigns, 'long');
        }
        else
        {
          $allsunsigns = Cache::read('sunsigns', 'long');
        }

        $testimonials = $this->Testimonials->find('all',['limit'=>1,
            'order'=>['Testimonials.id'=>'desc']
        ]);

        $settings =  $this->Settings->find('all')
                                    ->select([ 'field_key', 'field_value' ])
                                    ->where([ 'field_key IN' => ['home_banner', 'banner_text_en', 'banner_subtext_en', 'banner_button_en', 'banner_text_da' , 'banner_subtext_da', 'banner_button_da']])

                                    ->order(['id' => 'ASC' ])->toArray();
  

        $user_id = $this->request->session()->read('user_id');

       if( isset($user_id) && !empty($user_id)):
               $this->set( 'user_id', 1 );
        endif;
            //if ($this->checkIp()) {
              $latestPosts = $this->MiniBlogs->find ()->where(['MiniBlogs.status' => 1])->order(['MiniBlogs.sort_order' => 'ASC', 'MiniBlogs.created' => 'DESC'])->limit(3)->toArray();
              //pr ($latestPosts); die;
            /*} else {
              $latestPosts = $this->MiniBlogs->find ()->where(['MiniBlogs.status' => 1, 'MiniBlogs.id !=' => 54])->order(['MiniBlogs.sort_order' => 'ASC'])->limit(3)->toArray();
            }*/

          if (strtolower($this->request->session()->read('locale')) == 'da') {
            $meta['title'] = 'Astrowow - Astrologi, gratis astrologi og horoskoper rapporter & software'; // Page Title
            $meta['description'] = 'Astrowow.com tilbud gratis horoskoper, astrologi software, Horoskoper og forudsigelser, I dag, Ugentlig, Månedlig, Årlig, Karakteristik, Kendte horoskoper for Vædderen, Tyr Gemini, Krebs Løve Jomfru, Vægt, Skorpion, Skytte, Stenbuk, Vandmand, Vandbærer, Fiskene, soltegn';
          } else {
            $meta['title'] = 'Astrowow - Astrology, Free Horoscopes, Astrology Reading, Astrology Softwares & Reports'; // Page Title
            $meta['description'] = 'Astrowow.com provides free horoscopes reading, Astrologer, Astrology Software, prediction, Daily, weekly, monthly horoscopes for aries, taurus, gemini, cancer, leo, virgo, libra, scorpio, sagittarius, capricorn, aquarius, pisces, sunsign';
          }
          $meta['keywords'] = 'Free Astrology, Horoscope, Horoscope Reading,  Aries, taurus, gemini, cancer, leo, virgo, libra, scorpio, sagittarius, capricorn, aquarius, pisces,  daily horoscope, weekly horoscope, monthly horoscope,online astrology';

          $canonical['en'] = SITE_URL;
          $canonical['da'] = SITE_URL.'dk';
       
          $this->set(compact('canonical', 'meta'));
          //$this->set(compact('meta'));    
          $this->set('astro_blogs', $latestPosts);    
          $this->set(compact( 'testimonials', 'allsunsigns' , 'settings' ));
    }
	
	public function menuPages($param) {
    if ( strtolower(trim($param)) == 'meditation' || strtolower(trim($param)) == 'free-astroclock-ios-app' ) {
      $this->set('pageName', strtolower(trim($param)));
    }

    if( empty($param) || !isset($param) ):
      return $this->redirect(['controller' => 'Pages', 'action' => 'index']);
    elseif( $param  == "subscribe" ):
      return $this->redirect( ['controller' => 'Users', 'action' => 'subscribe'] );
    elseif($param == 'astrology-software'):
      return $this->redirect( ['controller' => 'Products', 'action' => 'astrology', 'astrology-software'] );
    elseif($param == 'media'):
      return $this->redirect( ['controller' => 'Pages', 'action' => 'media'] );
    elseif($param == 'consultation'):
      //Consultation start
      $entity = $this->Pages->newEntity();

    if($this->request->is('post')){
      $category = $this->Categories->find('all') 
                                    ->where(['Categories.id' => $this->request->data['category_id']])
                                    ->select(['Categories.slug'])
                                    ->first();
      $this->request->data['category'] = $category['slug'];
      $this->request->data['product_type'] = CONSULTATION;
      
      /*if($this->checkIp()){
        pr($this->request->data); die;
      }*/
      $this->request->data['order_id'] = $this->getOrderIdByProductType('consult');
      $this->request->session()->write('Order', $this->request->data);
      /*if($this->checkIp()){
        pr($this->request->data); pr($this->request->session()->read('Order')); die;
      }*/
      if(!$this->request->session()->read('user_id')){
        $order = $this->request->session()->read('Order');
        $order['cancel_url'] = $order['url'];
        $order['url'] = ['controller' => 'Orders', 'action' => 'consultation-checkout'];
        $this->request->session()->write('Order' , $order);
        $this->redirect(['controller' => 'Users', 'action' => 'login']);
      } else {
        $this->redirect(['controller' => 'Orders', 'action' => 'consultation-checkout']);
      }
    } else {
      $orderObj = new OrdersController();
      if( strtolower(I18n::locale()) == 'en_us' ) {
        $CurrencyData = $this->Currencies->find('all', ['conditions' => ['Currencies.status' =>  1, 'Currencies.code IN' => ['USD', 'GBP', 'EUR'] ] ])->toArray();
        $priceInfo = $orderObj->getProductPrice(1, SKYPE_CONSULTATION) ;
        $data['categoryId'] = SKYPE_CONSULTATION;
      } elseif(strtolower(I18n::locale()) == 'da' ) { // For DK
          $CurrencyData = $this->Currencies->find('all', ['conditions' => ['Currencies.status' =>  1, 'Currencies.code IN' => ['DKK'] ] ])->toArray();
        $priceInfo = $orderObj->getProductPrice(3, OFFICE_CONSULTATION) ;
        $data['categoryId'] = OFFICE_CONSULTATION;
      }

      foreach ( $CurrencyData as $currency ) {
        $currencyOptions[] = ['value' => $currency['id'], 'text' => $currency['name'].'('.$currency['symbol'].')','class' =>'skype-radio']  ;
      }

      $categoryOptions = $this->Categories->find('list')
                                          ->where(['Categories.status' => 1, 'Categories.slug IN' => ['skype-consultation', 'office-consultation',  'questions-skype']])
                                          ->toArray();

      $this->set(compact('currencyOptions', 'data'));
      $this->set(compact('priceInfo', 'categoryOptions'));
      $this->set('form', $entity);
    }
    //Consultation End

    // return $this->redirect( ['controller' => 'Orders', 'action' => 'consultation'] );
    elseif($param == 'freedom-of-speech'):
      $path = Router::url('/', true);
    $this->redirect( $path."blog/freedom-of-speech" );
    //$this->redirect( "http://ec2-54-193-51-211.us-west-1.compute.amazonaws.com/blog/freedom-of-speech/" );
    endif;
    $page = $this->Pages->find( 'translations' )
    			                    ->where( ['seo_url' => $param, 'status !=' => 2] ) //Status condition added by Kingslay to protect inactive pages in the front (2017-04-14)
                              ->first();
    if( empty($page) || (strtolower($page['title'])=='sitemap') ):
      $error = "The page you requested could not be found. Try refining your search, or use the navigation above to locate the post";
      $this->set('error', $error);
    else:
      $this->set( compact('page')  );
    endif;
  }

  public function media()
  {
    $entity = $this->Pages->newEntity();
    $audios = $this->Media->find('all')
                          ->where(['category_id' => 24, 'status' => 1])
                          ->toArray();
    $videos = $this->Media->find('all')
                          ->where(['category_id' => 25, 'status' => 1])
                          ->toArray();

    $canonical['en'] = SITE_URL.'media';
    $canonical['da'] = SITE_URL.'dk/medier';
    $meta['title'] = 'Astrowow.com - Astrology, free horoscopes, sun sign, Astrology reading, Astrology software';
    $meta['description'] = 'Astrowow.com provides free horoscopes, Astrology reading, Astrologer, Astrology Software, Astrology prediction, Daily, weekly, monthly horoscopes for aries, taurus, gemini, cancer, leo, virgo, libra, scorpio, sagittarius, capricorn, aquarius, pisces, sun sign';
    $meta['keywords'] = 'Free Astrology, Horoscope, Horoscope Reading,  Aries, taurus, gemini, cancer, leo, virgo, libra, scorpio, sagittarius, capricorn, aquarius, pisces,  daily horoscope, weekly horoscope, monthly horoscope,online astrology';
    $this->set(compact('meta', 'canonical'));
    $this->set(compact('audios', 'videos'));
    $this->set('form',$entity);
  }
}


