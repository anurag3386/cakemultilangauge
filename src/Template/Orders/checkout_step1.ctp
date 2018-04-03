   <?php use Cake\Routing\Router;?>
   <?php use Cake\Cache\Cache;?>
   <?php use Cake\I18n\I18n;?>

   <?php $locale = strtolower( substr( I18n::locale(), 0, 2) );?>
   <?php 
            $order = $this->request->session()->read('Order');
            $price = $order['price'];
            $image = $order['image'];
            $pages = $order['pages'];
            $product_name = $order['product_name'];
            if ( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' ) {
                //pr($this->request->session()->read()); //die;
                $first_name = $last_name = $username = '';
                if($this->request->session()->read('userOrderStatus') == 'old') {
                    if (!empty($this->request->session()->read('first_name'))) {
                        $first_name = $this->request->session()->read('first_name');
                    } else {
                        $first_name = $this->request->session()->read('Auth.UserProfile.first_name');
                    }
                    if (!empty($this->request->session()->read('last_name'))) {
                        $last_name = $this->request->session()->read('last_name');
                    } else {
                        $last_name = $this->request->session()->read('Auth.UserProfile.last_name');
                    }
                    if (!empty($this->request->session()->read('username'))) {
                        $username = $this->request->session()->read('username');
                    } else {
                        $username = $this->request->session()->read('Auth.User.username');
                    }
                } else {
                    $first_name = $this->request->session()->read('Auth.UserProfile.first_name');
                    $last_name = $this->request->session()->read('Auth.UserProfile.last_name');
                    $username = $this->request->session()->read('Auth.User.username');
                }

                //$first_name   = !empty($this->request->session()->read('first_name')) ? $this->request->session()->read('first_name') : '';
                //$last_name    = !empty($this->request->session()->read('last_name'))? $this->request->session()->read('last_name') : '';
                //$username     = !empty($this->request->session()->read('username')) ? $this->request->session()->read('username') : '';
            } else {
                $first_name   = !empty($user['profile']['first_name']) ? $user['profile']['first_name'] : '';
                $last_name    = !empty( $user['profile']['last_name'] )? $user['profile']['last_name'] : '';
                $username     = !empty( $user['username'] ) ? $user['username']  :'' ;     
            }
            $product_id   = $order['product_id']          
    ?>
   <article id="post-197" class="post-197 page type-page status-publish hentry">
     <div class="entry-content">
       <div class="reports_checkout_1">
           <div class="et_pb_section  et_pb_section_0 et_section_regular checkout-pages">
               <div class=" et_pb_row et_pb_row_0">
                <div class="et_pb_column et_pb_column_4_4  et_pb_column_0">
                    <div class="et_pb_code et_pb_module  et_pb_code_0">
                        <?php echo $this->Form->create($form,  ['id' => 'step-1', 'class' => 'checkout_form' ])?>
                        <div class="et_pb_section checkout_header et_pb_section_0 et_section_regular">
                            <div class=" et_pb_row et_pb_row_0">
                                <div class="et_pb_column et_pb_column_4_4  et_pb_column_0">
                                    <div class="et_pb_code et_pb_module  et_pb_code_0">
                                    <div class="checkoutLeft">
                                    <?php 
                                     $translate = $this->Custom->getTranslation('Products',['Products.id' => $product_id], ['image', 'name'] );
               
                                  ?>
                                         <h3 class="checkout_title"><?php echo ucwords($translate['name']); ?></h3>
                                        <cite><?= __('Approx');?> <?php echo $pages?> <?= __('pages');?></cite>
                                        <p><cite><?= __('Deliver through Email');?></cite></p>
                                  <?php
                                       echo $this->Html->image("/uploads/products/".$translate['image'], [ 'class' => 'size-thumbnail wp-image-164', 'alt'=> $translate['name'] ])?> 
                                            


                                  
                                    
                                        </div>
                                          <div class="total_price_section">
                                              <?php if($finalPrice['vat'] > 0)
                                                    {
                                                        ?>
                                              <h3 class="checkout_price">
                                                <?= __('Price');?>: <?= $finalPrice['currency_symbol']. $this->Custom->formatPrice($finalPrice['price'],$finalPrice['currency_symbol']); ?>
                                                </h3>
                                                <h4 class="">
                                                    <?= __('VAT').': '.$finalPrice['currency_symbol'].$this->Custom->formatPrice($finalPrice['vat'],$finalPrice['currency_symbol']); ?>
                                                </h4>
                                                <?php } ?>
                                              
                                                <h5 class="">
                                                <?= __('Total Price');?>: <strong><?php echo $finalPrice['currency_symbol'].$this->Custom->formatPrice($finalPrice['total_price'],$finalPrice['currency_symbol']);?></strong
                                                >
                                                </h5>
                                            </div>
                                        </h3>                    
                                        <div class="checkoutProgressBox">
                                        <div class="progressCol active">
                                        <div class="stepNo"><span>1</span></div>
                                        <div class="stepName"><?= __('General Info');?></div>
                                        </div>
                                        <div class="progressCol">
                                        <div class="stepNo"><span>2</span></div>
                                        <div class="stepName"><?= __('Personal Info');?></div>
                                        </div>  
                                        <div class="progressCol">
                                        <div class="stepNo"><span>3</span></div>
                                        <div class="stepTitle"><?= __('Payment Method');?></div>
                                        </div>          
                                        </div>
                                        <hr>
                                    </div> <!-- .et_pb_code -->
                                </div> <!-- .et_pb_column -->
                            </div> <!-- .et_pb_row -->
                        </div>
                 <div id="checkout_1_wrapper">
                           <?php if( empty($user_id) ):?>
                            <ul class="checkout_user_login">
                                <li><?= __('Are you a returning user?');?> <?php echo $this->Html->link(__('Login here'), [ 'controller' => 'Users', 'action' => 'login'])?></li>
                                <li><?= __('Or you can create a');?> <?php echo $this->Html->link(__('New account'), [ 'controller' => 'Users', 'action' => 'sign-up'])?></li>
                            </ul>
                            <?php endif;?>
                            <h2><?= __('General Information');?></h2>
                            <span style="color:red; text-align:center"><?php echo $this->Flash->render();?></span>
                            <label for=""><?= __('First Name');?></label>
                            <?php echo $this->Form->text('first_name', [ 'id' => 'firstname', 'tabindex' => '2', 'class' =>'inputLarge validate[required]', 'placeholder' => 'John', 'default' => $first_name, 'maxlength' => 60, 'required' => false] )
                            ?>
                            <br>
                            <label for=""><?= __('Last Name');?></label>
                            <?php echo $this->Form->text('last_name', [ 'id' => 'last_name', 'tabindex' => '3', 'class' =>'inputLarge validate[required]', 'placeholder' => 'Doe' , 'default' => $last_name, 'maxlength' => 60, 'required' => false] );
                            ?>
                            <br>
                            <label for=""><?= __('Email');?></label>
                            <?php echo $this->Form->text('username', [ 'id' => 'username', 'tabindex' => '4', 'class' =>'inputLarge validate[required, custom[email]]', 'placeholder' => 'youremail@example.com', 'default' => $username , 'maxlength' => 80, 'required' => false] );
                            ?>
                            <br>
                        </div>
                        <div class="et_pb_section  et_pb_section_2 et_section_regular paddNone">
                            <div class=" et_pb_row et_pb_row_2">
                                <div class="et_pb_column et_pb_column_4_4  et_pb_column_2">
                                    <div class="et_pb_text et_pb_module et_pb_bg_layout_light et_pb_text_align_right checkout_footer et_pb_text_2">
                                        <hr>
                                        <?php 
                                        echo  $this->Form->button(__('Personal Info').' <i class="fa fa-long-arrow-right" aria-hidden="true"></i>', [ 'id' => 'submit', 'class' => 'btn btn-red', 'type' => 'submit' ]);
                                        ?>
                                    </div> <!-- .et_pb_text -->
                                </div> <!-- .et_pb_column -->
                            </div> <!-- .et_pb_row -->
                        </div>
                        <?php echo $this->Form->end(); ?>
                    </div> <!-- .et_pb_code -->
                </div> <!-- .et_pb_column -->
                
            </div> <!-- .et_pb_row -->
            
        </div> <!-- .et_pb_section -->
      </div>
    </div> <!-- .entry-content -->

    
                    </article> <!-- .et_pb_post -->
 <?php 
  $order['url']   = Router::url($this->request->here(), true);
  $this->request->session()->write('Order' , $order);
 ?>                   