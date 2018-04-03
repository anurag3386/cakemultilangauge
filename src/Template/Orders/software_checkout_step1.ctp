<?php use Cake\Routing\Router;?>
<?php use Cake\Cache\Cache;?>
<?php 
            //$order = $this->request->session()->read('Order');
            $order = $this->request->session()->read('SOrder');
            $first_name = $last_name = $username = '';
            /*if($_SERVER['REMOTE_ADDR'] == '103.254.97.14'){
              pr($this->request->session()->read()); die;
            }*/
            $price = $order['price'];
            $image = $order['image'];
            $pages = $order['pages'];
            $type  = $order['type'];
            $language = $order['language'];
            $product_name = $order['product_name'];
            $product_id   = $order['product_id'];

            if(!empty($this->request->session()->read('SOrder'))) {
              $first_name   = !empty($order['first_name']) ? $order['first_name'] : '';
              $last_name    = !empty( $order['last_name'] )? $order['last_name'] : '';
              $username     = !empty( $order['email'] ) ? $order['email']  :'' ;
            } else {
              $first_name   = !empty($user['profile']['first_name']) ? $user['profile']['first_name'] : '';
              $last_name    = !empty( $user['profile']['last_name'] )? $user['profile']['last_name'] : '';
              $username     = !empty( $user['username'] ) ? $user['username']  :'' ;
            }


?>
<article id="post-1293" class="post-1293 page type-page status-publish hentry">
   <div class="entry-content">
    <div class="software_checkout_1">
       <div class="et_pb_section  et_pb_section_0 et_section_regular checkout-pages">
           <div class=" et_pb_row et_pb_row_0">
            <div class="et_pb_column et_pb_column_4_4  et_pb_column_0">
                <div class="et_pb_code et_pb_module  et_pb_code_0 checkout-buttons">
                    <?php echo $this->Form->create($form,  ['id' => 'step-1', 'class' => 'checkout_form' ])?>
                        <div class="et_pb_section checkout_header et_pb_section_0 et_section_regular">

                            <div class=" et_pb_row et_pb_row_0">
                                <div class="et_pb_column et_pb_column_4_4  et_pb_column_0">
                                    <div class="et_pb_code et_pb_module  et_pb_code_0">
                                      <div class="checkoutLeft">
                                       <h3 class="checkout_title">
                                        <?php 
                                         $translate = $this->Custom->getTranslation('Products',['Products.id' => $product_id], ['image', 'name'] );
                                        echo ucwords($translate['name'].$type);?>
                                        </h3>
                                        <p><?= __('Language');?>: <strong><?php echo $language;?></strong></p>
                                      <?php 
                                         

                                         echo $this->Html->image("/uploads/products/".$translate['image'], [ 'class' => 'size-thumbnail wp-image-164', 'alt'=> $translate['name'].$type ]);

                                       ?>    
                                  
                                   </div>                                               
                      <div class="total_price_software">
                            <?php if($finalPrice['vat'] > 0)
                                  {
                                      ?>
                            <h3 class="">
                              <?= __('Price');?>: <?= $finalPrice['currency_symbol'].$this->Custom->formatPrice($finalPrice['price'],$finalPrice['currency_symbol']); ?>
                              </h3>
                              <h4 class="">
                                <?= __('VAT').': '.$finalPrice['currency_symbol'].$this->Custom->formatPrice($finalPrice['vat'],$finalPrice['currency_symbol']); ?>
                              </h4>
                              <?php
                                  }
                              ?>
                            
                              <h5 class="">
                              <?= __('Total Price');?>: <strong><?php echo $finalPrice['currency_symbol'].$this->Custom->formatPrice($finalPrice['total_price'],$finalPrice['currency_symbol']);
                               echo ($order['product_type'] == SOFTWARE_CD)? ' ( Incl. Shipping )'/*' + Shipping'*/ : '';
                              ?></strong
                              >
                              </h5>
                     </div>

</strong>
                                           
                                          <br class="clear">
                                        <p>
                                        <?php 
                                            if($order['product_type'] == SOFTWARE_CD)
                                            {
                                        ?>
                                        </p>
                                          <div class="checkoutProgressBox">
                                          <div class="progressCol active">
                                          <div class="stepNo"><span>1</span></div>
                                          <div class="stepName"><?= __('General Info');?></div>
                                          </div>
                                          <div class="progressCol">
                                          <div class="stepNo"><span>2</span></div>
                                          <div class="stepName"><?= __('Shipping Address');?></div>
                                          </div>  
                                          <div class="progressCol">
                                          <div class="stepNo"><span>3</span></div>
                                          <div class="stepTitle"><?= __('Payment Method');?></div>
                                          </div>          
                                          </div>

                                        <?php    }
                                        elseif($order['product_type'] == SHAREWARE)
                                            {
                                               
                                            ?>
                                        </p>
                                         <div class="two_steps">
	                                         <div class="checkoutProgressBox">
	                                          <div class="progressCol active">
	                                          <div class="stepNo"><span>1</span></div>
	                                          <div class="stepName"><?= __('General Info');?></div>
	                                          </div>
	                                          <div class="progressCol">
	                                          <div class="stepNo"><span>2</span></div>
	                                          <div class="stepName"><?= __('Payment Method');?></div>
	                                          </div>  
	                                                
	                                          </div>
										</div>

                                        <?php
                                            }
                                        ?>
                                        <hr class="clear">
                                    </div> <!-- .et_pb_code -->
                                </div> <!-- .et_pb_column -->
                            </div> <!-- .et_pb_row -->
                        </div>

                        <div id="checkout_1_wrapper">
                           <?php if( empty($user_id) ):?>
                            <ul class="checkout_user_login">
                                <li><?= __('Are you a returning user?');?> <?php echo $this->Html->link(__('Login here'), [ 'controller' => 'Users', 'action' => 'login'])?></li>
                                <li>Or you can create a <?php echo $this->Html->link(__('new account'), [ 'controller' => 'Users', 'action' => 'sign-up'])?></li>
                            </ul>

                            <?php endif;?>
                            <h2><?= __('General Information');?></h2>
                            <div class="text_center"><?= $this->Flash->render();?></div>
                            <label for=""><?= __('First Name');?></label>
                            <?php echo $this->Form->text('profile.first_name', [ 'id' => 'firstname', 'tabindex' => '2', 'class' =>'inputLarge validate[required]', 'placeholder' => 'John', 'default' => $first_name, 'value' => $first_name, 'maxlength' => 60, 'required' => false] )
                            ?>
                            <br>

                            <label for=""><?= __('Last Name');?></label>
                            <?php echo $this->Form->text('profile.last_name', [ 'id' => 'last_name', 'tabindex' => '3', 'class' =>'inputLarge validate[required]', 'placeholder' => 'Doe' , 'default' => $last_name, 'value' => $last_name, 'maxlength' => 60, 'required' => false] );
                            ?>
                            <br>

                            <label for=""><?= __('Email');?></label>
                            <?php echo $this->Form->text('username', [ 'id' => 'username', 'tabindex' => '4', 'class' =>'inputLarge validate[required, custom[email]]', 'placeholder' => 'youremail@example.com', 'default' => $username, 'value' => $username , 'maxlength' => 80, 'required' => false] );
                            ?>
                            <br>
                        </div>
                        <div class="et_pb_section  et_pb_section_2 et_section_regular">
                            <div class=" et_pb_row et_pb_row_2">
                                <div class="et_pb_column et_pb_column_4_4 et_pb_column_2">
                                    <div class="et_pb_text et_pb_module et_pb_bg_layout_light et_pb_text_align_right checkout_footer et_pb_text_2">
                                        <hr>
                                         
                                        <button type="submit" name="btnStartOrderProcess" id="btnStartOrderProcess" class="btn btn-red">
                                        <?php 
                                           if($order['product_type'] == SOFTWARE_CD)
                                            {
                                                echo __('Shipping Address');
                                            }
                                            elseif($order['product_type']  == SHAREWARE)
                                            {
                                                echo __('Payment Method');
                                            }
                                        ?>                                         
                                       <i class="fa fa-long-arrow-right" aria-hidden="true"></i> </button>
                                    </div> <!-- .et_pb_text -->
                                </div> <!-- .et_pb_column -->
                            </div> <!-- .et_pb_row -->
                        </div>
                        <?php echo $this->Form->hidden('category', ['value' => $order['category']]);?>
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
  //$this->request->session()->write('Order' , $order);
  $this->request->session()->write('SOrder' , $order);
?>  