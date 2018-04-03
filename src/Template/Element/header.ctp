<?php  use Cake\I18n\I18n; ?>
<?php use Cake\Cache\Cache; ?>
<?php use Cake\Routing\Router;?>
<?php $locale =  I18n::locale(); ?>
<?php //pr ($this->request->params['pass'][0]); die; ?>
<header id="main-header" data-height-onload="183" data-height-loaded="true" style="top: 0px;" class="">
  <div class="container clearfix et_menu_container">
    <div class="logo_container"> <span class="logo_helper"></span> 

<?php
    // echo $this->Html->link(
    //     $this->Html->image('astrowow_logo.png'), ['controller' => 'Pages', 'action' => 'index'], array('escape' => false, 'alt'=>'Astro wow world of wisdom horoscopes', 'id'=>'logo','data-height-percentage'=>'54','data-actual-width'=>'302','data-actual-height'=>'72')
    // );


    $myImageVar = $this->Html->image('astrowow_logo.png',array('alt'=>'Astro wow world of wisdom horoscopes','class'=>''));

echo $this->Html->link($myImageVar, [
                          'controller' => 'Pages',
                          'action'     => 'index',

                          ],['escape'    => false, 
                          'id'         => 'logo', //if any parameters are passed
                          'data-height-percentage' => '54',
                          'data-actual-width' => '302',
                          'data-actual-height' => '72'
                          ]);

     /*$result = preg_replace(
                  array('/\/en/', '/\/dk/'),
                  array('', ''),
                  $this->request->here);*/

  //pr ($this->request->params); 
     //pr($this->request->query);

   // pr($this->request->query);


     $language = (isset($this->request->params['language']))?$this->request->params['language']:'';
     $translator = I18n::translator('default', 'da');

     $controllersArray = array('pages', 'sun-signs');
     //$controllersArray = array('pages');
     $urlControllersArray = array('users', 'sun-signs', 'products', 'elite-users', 'orders','mini-blogs', 'support-tickets');

     $controller = strtolower($this->request->params['controller']);
     $controller = ($controller == 'sunsigns')?'sun-signs':$controller;
     $controller = ($controller == 'eliteusers')?'elite-users':$controller;
     $controller = ($controller == 'supporttickets')?'support-tickets':$controller;
     $miniblogController = $controller = ($controller == 'miniblogs')?'mini-blogs':$controller;
     $action = $this->request->params['action'];
     
     /*if( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' ) {
        echo $controller.' => '.$action; die;
     }*/

     $action = ($action == 'freeHoroscope')?'free-horoscope':$action;
     $action = ($action == 'signUp')?'sign-up':$action;
     $action = ($action == 'astrologyReports')?'astrology-reports':$action;
     $action = ($action == 'myAccount')?'my-account':$action;
     $action = ($action == 'customizeReports')?'customize-reports':$action;
     $action = ($action == 'changePassword')?'change-password':$action;
     $action = ($action == 'orderList')?'order-list':$action;
     $action = ($action == 'addAnotherPerson')?'add-another-person':$action;
     $action = ($action == 'checkoutStep1')?'checkout-step-1':$action;
     $action = ($action == 'checkoutStep2')?'checkout-step-2':$action;
     $action = ($action == 'checkoutStep3')?'checkout-step-3':$action;    
     $action = ($action == 'editProfile')?'edit-profile':$action;    
     $action = ($action == 'forgotPassword')?'forgot-password':$action;        
     $action = ($action == 'signUpStepTwo')?'sign-up-step-two':$action;
     $action = ($action == 'eliteReportCheckout')?'elite-report-checkout':$action;  
     $action = ($action == 'eliteCustomerCheckoutStep2')?'elite-customer-checkout-step2':$action;
     $action = ($action == 'eliteCustomerCheckout')?'elite-customer-checkout':$action;
     $action = ($action == 'softwareCheckoutStep1')?'software-checkout-step-1':$action;  
     $action = ($action == 'softwareCheckoutStep2')?'software-checkout-step-2':$action;  
     $action = ($action == 'softwareCheckoutStep3')?'software-checkout-step-3':$action;  
     $action = ($action == 'downloadFreeTrial')?'download-free-trial':$action;  
     $action = ($action == 'thankYouFreeTrial')?'thank-you-free-trial':$action;  
     $action = ($action == 'orderDetail')?'order-detail':$action; 
     $action = ($action == 'thankYou')?'thank-you':$action;
     $action = ($action == 'softwareThankYou')?'software-thank-you':$action;
     $action = ($action == 'consultationThankYou')?'consultation-thank-you':$action;
     $action = ($action == 'eliteReportCheckout2')?'elite-report-checkout2':$action;
     $action = ($action == 'thankYouForReportPurchase')?'thank-you-for-report-purchase':$action;
     $action = ($action == 'consultationCheckout')?'consultation-checkout':$action;

     $action = ($action == 'userTestimonial')?'user-testimonial':$action;
     $action = ($action == 'resetPasswordToken')?'reset-password-token':$action;
    
    $controller_dk = $translator->translate($controller);

     if( $controller == 'products' ) {

        if($action == 'astrology-reports') {
          $action_dk = $translator->translate($action);
        } else {
            
           if(isset($current_product_id) && !empty($current_product_id) && $language == 'dk')
            // if( $language == 'dk')
            {
              $slug = $this->Custom->getEnglishTranslation('Products', 'seo_url', $current_product_id) ;
              $slug_dk = $translator->translate($slug);
            }
            else
            {
               $slug = $this->request->params['pass'][0];
               $slug_dk = $translator->translate($slug);
            }

            if($action == 'astrology') {
                $action = $slug;
                $action_dk = $slug_dk;
            } elseif ($action == 'user-testimonial') {
                $action = $slug;
                $action_dk = $slug_dk;
            } else {

   
                $product_type = $this->request->params['pass'][1];
              

                if($language == 'dk' && $product_type == 'fuld-rapport' || $product_type == 'elite-fuld-rapport')
                {
                 $product_type_en = $translator->translate($product_type);
                 $action = $slug.'/'.$product_type_en;
                 $action_dk = $slug_dk.$product_type;
                }
                else
                {
                  //echo "checkpoint-2";
                  $product_type_dk = $translator->translate($product_type);
                  $action = $slug.'/'.$product_type;
                  $action_dk = $slug_dk.'/'.$product_type_dk;
                }
           }
        }
     } else {
         if( in_array($controller, $controllersArray) && isset($this->request->params['pass'][0]) ) {
           $action = $this->request->params['pass'][0];
         }
         
          $action_dk = $translator->translate($action);
  } 

      if($controller == 'pages' && $action == 'index') {
        $action = $action_dk = '';
      } elseif( $controller == 'support-tickets' && $action == 'view' && !empty($this->request->params['pass'][0]) && !empty($this->request->params['pass'][1])) {
          $first_parameter = ['closed' => 'lukket', 'lukket' => 'closed', 'opened' => 'åbnet', 'åbnet' => 'opened'];
          if($language == 'dk'){
            $action = $first_parameter[$this->request->params['pass'][0]].'/'.$this->request->params['pass'][1];
            $action_dk = $this->request->params['pass'][0].'/'.$this->request->params['pass'][1];
          } else {
            $action_dk = $first_parameter[$this->request->params['pass'][0]].'/'.$this->request->params['pass'][1];
            $action = $this->request->params['pass'][0].'/'.$this->request->params['pass'][1];
          }
      } elseif( $controller == 'support-tickets' && $action == 'index' && !empty($this->request->params['pass'][0])) {
          $first_parameter = ['closed' => 'lukket', 'lukket' => 'closed', 'opened' => 'åbnet', 'åbnet' => 'opened'];
          if($language == 'dk'){
            $action = $first_parameter[$this->request->params['pass'][0]];
            $action_dk = $this->request->params['pass'][0];
          } else {
            $action_dk = $first_parameter[$this->request->params['pass'][0]];
            $action = $this->request->params['pass'][0];
          }
      } elseif( $controller == 'support-tickets' && $action == 'edit' && !empty($this->request->params['pass'][0])) {
         $action = $action_dk = 'edit/'.$this->request->params['pass'][0];
      } elseif( $controller == 'support-tickets' && $action == 'index') {
         $action = $action_dk = '';
      } elseif( $controller == 'support-tickets' && $action == 'view' && !empty($this->request->params['pass'][0]) && !empty($this->request->params['pass'][1])) {
        //echo $action.' => '.$translator->translate($action);
        $action_dk = /*$translator->translate($action).'/'.*/$translator->translate($this->request->params['pass'][0]).'/'.$this->request->params['pass'][1];
        $action = $this->request->params['pass'][0].'/'.$this->request->params['pass'][1];
        //pr($this->request->params['pass']);
     }
     elseif( $controller == 'mini-blogs' && $action == 'index' && !isset($this->request->query['page']))
     {
         $action = $action_dk = '';

     }
     elseif( ($controller == 'mini-blogs' && $action == 'post') && (isset($this->request->params['pass'][0]) ) )
     {

       //if( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' ) {
          $action = $action.'/'. $this->Custom->getMiniBlogSeoUrlTranslate($this->request->params['pass'][0]);
          $action_dk = $action_dk.'/'. $this->Custom->getMiniBlogSeoUrlTranslate($this->request->params['pass'][0]);
       /*} else {
          $action = $action.'/'. $this->request->params['pass'][0];
          $action_dk = $action_dk.'/'. $this->request->params['pass'][0];
          //$action_dk = $action_dk.'/'. $this->request->params['pass'][0];
       }*/
     }
     elseif($controller == 'mini-blogs' && isset( $this->request->query['page'] ) && !empty($this->request->query['page']))
     {  
      // This is used for mini blogs pagintion
       $action = $action_dk = '';
       $action = $action.'?page='. $this->request->query['page'];
       $action_dk = $action_dk.'?page='. $this->request->query['page'];
     }
      elseif(($controller == 'orders' && $action == 'thank-you-free-trial') && (isset($this->request->params['pass'][0]) )) {
       $action = $action.'/'. $this->request->params['pass'][0];
       $action_dk = $action_dk.'/'. $this->request->params['pass'][0];
     }
     elseif(($controller == 'elite-users' && $action == 'order-detail') && (isset($this->request->params['pass'][0]) )) {
       $action = $action.'/'. $this->request->params['pass'][0];
       $action_dk = $action_dk.'/'. $this->request->params['pass'][0];
     }
     
     elseif(($controller == 'users' && $action == 'reset-password-token') && (isset($this->request->params['pass'][0]) )) {

             $action = $action.'/'. $this->request->params['pass'][0];
             $action_dk = $action_dk.'/'. $this->request->params['pass'][0];
     }

     elseif(in_array($controller, $controllersArray) && isset($this->request->params['pass'][1])  )
     {

  
      // This is used to add anchor tabs on horoscope detail page
       if($controller == 'sun-signs')
       {
          $tab = '';
          switch ($this->request->params['pass'][1]) {
                        case 'daily-horoscope':
                                   $tab = 'today-tab';
                        break;

                        case 'weekly-horoscope':
                                   $tab = 'weekly-tab';
                        break;

                        case 'monthly-horoscope':
                                   $tab = 'monthly-tab';
                        break;
                        case 'yearly-horoscope':
                                   $tab = 'yearly-tab';
                        break;
                        
                        case 'characteristics':
                                   $tab = 'characteristics-tab';
                        break;
                        case 'celebrity':
                                   $tab = 'celebrity-tab';
                        break;
                        
                        default:
                                   $tab = 'daily-horoscope';
                        break;
                      }            

         $action = $action.'/'. $this->request->params['pass'][1].'#'.$tab;
         $action_dk = $action_dk.'/'. $translator->translate($this->request->params['pass'][1]).'#'.$translator->translate($tab);

       }
       else
       {
         $action = $action.'/'. $this->request->params['pass'][1];
         $action_dk = $action_dk.'/'. $translator->translate($this->request->params['pass'][1]);
       }
     
     

     }

     $en_url = Router::fullbaseUrl().'/'.$action;
     $dk_url = Router::fullbaseUrl().'/dk/'.$action_dk;


    
    if(strpos($action, 'thank-you') != false && !empty($this->request->query))
    {
       // This is used for all thankyou pages

      $query = '';
      foreach ($this->request->query as $key => $value) {
          $query .= '&'.$key.'='.$value;
      }
      $query  = ltrim($query, '&') ;
      $en_url = Router::fullbaseUrl().'/'.$controller.'/'.$action.'?'.$query;
      $dk_url = Router::fullbaseUrl().'/dk/'.$controller_dk.'/'.$action_dk.'?'.$query;
    } 
    elseif (($controller == 'elite-users' && $action == 'index')) 
    {
      $en_url = Router::fullbaseUrl().'/'.$controller;
      $dk_url = Router::fullbaseUrl().'/dk/'.$controller_dk;
    } 
    elseif( $controller == 'mini-blogs' && isset( $this->request->query['page'] ) && !empty($this->request->query['page']) )
    {
      $en_url = Router::fullbaseUrl().'/'.$controller.$action;
      $dk_url = Router::fullbaseUrl().'/dk/'.$controller_dk.$action_dk;
    }
    elseif($controller == 'products' && $action == 'astrology-reports')
    {
      $en_url = Router::fullbaseUrl().'/'.$action;
      $dk_url = Router::fullbaseUrl().'/dk/'.$action_dk;
    }
    elseif( in_array($controller, $urlControllersArray) && $this->request->params['action'] != 'astrology') 
    {

      

      $en_url = Router::fullbaseUrl().'/'.$controller.'/'.$action;
      $dk_url = Router::fullbaseUrl().'/dk/'.$controller_dk.'/'.$action_dk;
         
      if( ($controller == 'products') && (isset($this->request->params['pass'][1]) ) && (!empty($this->request->params['pass'][1]) )   )
      {
          if(($this->request->params['pass'][1] == 'software-cd') || ($this->request->params['pass'][1] == 'shareware'))
           {
      
            $en_url = Router::fullbaseUrl().'/astrology-software/'.$action;
            $dk_url = Router::fullbaseUrl().'/dk/astrologi-software/'.$action_dk;
           }
           elseif( ($this->request->params['pass'][1] == 'full-reports') || ($this->request->params['pass'][1] == 'fuld-rapport') || ($this->request->params['pass'][1] == 'elite-full-report') || ($this->request->params['pass'][1] == 'elite-fuld-rapport') )
           {
            //echo $action. "<br> ".$action_dk;
              $en_url = Router::fullbaseUrl().'/astrology-reports/'.$action;
              $dk_url = Router::fullbaseUrl().'/dk/astrologi-rapport/'.$action_dk;
           }
      }
      
       
      
    }
    
    

?> 
    </div>
      <div id="top-nav-secondary">

      <ul>

          <?php $myAstropageSelectedClass1 = $myAstropageSelectedClass = '';
          $user_id =$this->request->session()->read('user_id');
       
          if(isset($user_id) && !empty($user_id)) :  ?>
          <?php 

          if ( !empty($this->request->session()->read('Auth.User.role')) && ($this->request->session()->read('Auth.User.role') == 'elite')) { 
         
            ?>
            <li>
                <?php if (strtolower($this->request->controller) == 'users') { $myAstropageSelectedClass = 'my-astropage-class'; }
                    /*if( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' ) {
                      echo 'Here'; pr($this->request->session()->read()); die;
                    }*/
                    //$this->request->session()->write('userNotification.count', '2');
                  ?>
                      <?= $this->Html->link(__('My AstroPage'),['controller'=>'Users','action'=>'dashboard'], ['class' => $myAstropageSelectedClass]); ?>
                    <?php if($this->request->session()->read('userNotification.count') > 0){ ?>
                      <?php
                        $headerSupportTicketNotificationLink = '';
                        if($this->request->session()->read('locale') == 'da' || $this->request->session()->read('locale') == 'dk') {
                          $headerSupportTicketNotificationLink = Router::url('/', true).'dk/'.__('support-tickets');
                        } else {
                          $headerSupportTicketNotificationLink = Router::url('/', true).__('support-tickets');
                        }
                      ?>
                      <a href="<?= $headerSupportTicketNotificationLink; ?>">
                        <div class="header-notification-bell">
                            <p>
                              <?php
                                if($this->request->session()->read('userNotification.count')){
                                  echo $this->request->session()->read('userNotification.count');
                                } else {
                                  echo '0';
                                }
                              ?>
                            </p>
                        </div>
                      </a>
                    <?php } ?>
                <?php /*} else { ?>
                  <?php echo $this->Html->link(__('My AstroPage'),['controller'=>'Users','action'=>'dashboard'], ['class' => $myAstropageSelectedClass]); }*/ ?>
            </li>
            <?php 
            if ( !empty($this->request->session()->read('Auth.User.role')) && ($this->request->session()->read('Auth.User.role') == 'elite')) { 
            
              ?>
            <li>
                <?php if (strtolower($this->request->controller) == 'eliteusers') { $myAstropageSelectedClass1 = 'my-astropage-class'; } ?>
                <?= $this->Html->link(__('Elite Dashboard'),['controller'=>'elite-users','action'=>'dashboard'], ['class' => $myAstropageSelectedClass1]); ?>
            </li>
            <?php } ?>
          <?php } else { ?>
            <li>
                <?= $this->Html->link(__('My AstroPage'),['controller'=>'Users','action'=>'dashboard']); ?>
                <?php if($this->request->session()->read('userNotification.count') > 0){ ?>
                      <?php
                        $headerSupportTicketNotificationLink = '';
                        if($this->request->session()->read('locale') == 'da' || $this->request->session()->read('locale') == 'dk') {
                          $headerSupportTicketNotificationLink = Router::url('/', true).'dk/'.__('support-tickets');
                        } else {
                          $headerSupportTicketNotificationLink = Router::url('/', true).__('support-tickets');
                        }
                      ?>
                      <a href="<?= $headerSupportTicketNotificationLink; ?>">
                        <div class="header-notification-bell">
                            <p>
                              <?php
                                if($this->request->session()->read('userNotification.count')){
                                  echo $this->request->session()->read('userNotification.count');
                                } else {
                                  echo '0';
                                }
                              ?>
                            </p>
                        </div>
                      </a>
                    <?php } ?>
            </li>
          <?php } ?>

            <li> <?php echo $this->Html->link(__('Logout'),['controller'=>'Users','action'=>'logout'], ['class' => 'btn btn-red'])?> </li>
        <?php else : ?>


          <li>
            <?php
              $loginActionActive = '';
              if($action == 'login') {
                $loginActionActive = 'btn btn-red';
              }
              echo $this->Html->link(__('Log In'),['controller'=>'Users','action'=>'login'], ['class' => $loginActionActive]); ?> </li>
          <li>

           <?php echo $this->Html->link(__('Sign Up'),['controller'=>'Users','action'=>'sign-up'],['class'=>'btn btn-red'])?> </li>

         <?php endif;?>

       </ul>

     </div>
      <div class="mobile_language">
          <input class="toggle-box" id="header1" type="checkbox" >
          <?php $language = ($language == '') ? 'en' : $language; ?>
          <label for="header1" id="selected_language"><?php echo ($language == 'en')? 'EN' : 'DK' ?></label>
          <div>
             <ul>
             <?php 
              if($language == 'dk')
              {
             ?>
                <li class='active'> <a href="<?php echo $en_url; ?>" class="<?php echo ($language == 'en' || $language == '')?'language':'';?>">EN</a></li>
              <?php
                }
                else
                {
                  ?>
                <li> <a href="<?php echo $dk_url; ?>" class="<?php echo ($language == 'dk')?'language':'';?>">DK</a></li>
                <?php
                }
                ?>
             </ul>
           </div>
       </div>
  </div>
   <div id="et-top-navigation" style="padding-left: 313px;">
    <div class="container clearfix">
      <nav id="top-menu-nav">
        <ul id="top-menu" class="nav">
          <?php echo $this->Element('menu_top'); ?>
        </ul>
      </nav>
      <div id="et_mobile_nav_menu">
        <div class="mobile_nav closed"> <span class="select_page"><?= __('Select Page');?></span> <span class="mobile_menu_bar mobile_menu_bar_toggle"/>
          <span></span>
          <ul id="mobile_menu" class="et_mobile_menu">
            <?php echo $this->Element('menu_top'); ?>
          </ul>
        </div>
      </div>
    </div>
  </div>
</header>
<!-- #main-header -->
