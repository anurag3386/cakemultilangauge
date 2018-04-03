<?php use Cake\Routing\Router; ?>
<?php use Cake\Cache\Cache;?>
<?php use Cake\I18n\I18n;?>
<?php 
      // $order        = $this->request->session()->read('Order');
       $order        = $this->request->session()->read('SOrder');
      $price        = $order['price'];
      $image        = $order['image'];
      $pages        = $order['pages'];
      $type         = $order['type'];
      $language     = $order['language'];
      $product_name = $order['product_name'];
      $product_id   = $order['product_id'];
      $locale       = strtolower( substr(I18n::locale(), 0, 2) );

?>
<article id="post-303" class="post-303 page type-page status-publish hentry">
  <div class="entry-content">
     <div class="et_pb_section  et_pb_section_0 et_section_regular checkout-pages">
         <div class=" et_pb_row et_pb_row_0">
            <div class="et_pb_column et_pb_column_4_4  et_pb_column_0">
                <div class="et_pb_code et_pb_module  et_pb_code_0  checkout-buttons">
                    <div class="et_pb_section checkout_header et_pb_section_0 et_section_regular">
                        <div class="et_pb_text et_pb_module et_pb_bg_layout_light et_pb_text_align_left  et_pb_text_0">
                          <div class="checkoutLeft">
                         <?php 

                          $translate = $this->Custom->getTranslation('Products',['Products.id' => $product_id], ['image', 'name'] );
?>
<h3 class="checkout_title"><?php echo ucwords($translate['name'].$type);?></h3>
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
                                      <?php } ?>
                                      <h5 class="">
                                      <?= __('Total Price');?>: <strong><?php echo $finalPrice['currency_symbol'].$this->Custom->formatPrice($finalPrice['total_price'],$finalPrice['currency_symbol']);
                                       echo ($order['product_type'] == SOFTWARE_CD)? ' ( Incl. Shipping )'/*' + Shipping'*/ : '';
                                      ?></strong
                                      >
                                      </h5>
                           </div>
                              

                        <?php 
                         if($order['product_type'] == SOFTWARE_CD)
                          {
                            
                        ?>

                    </p>
           
                     <div class="checkoutProgressBox">
                      <div class="progressCol complete">
                      <div class="stepNo"><span>1</span></div>
                      <div class="stepName"><?= __('General Info');?></div>
                      </div>
                      <div class="progressCol complete step3">
                      <div class="stepNo"><span>2</span></div>
                      <div class="stepName"><?= __('Shipping Address');?></div>
                      </div>  
                      <div class="progressCol active">
                      <div class="stepNo"><span>3</span></div>
                      <div class="stepTitle"><?= __('Payment Method');?></div>
                      </div>          
                      </div>

                     <?php
                          }
                          elseif($order['product_type'] == SHAREWARE)
                          {
                            
                          ?>
                      </p>


                     
                      <div class="two_steps">
		 					  <div class="checkoutProgressBox">
		                      <div class="progressCol complete">
		                      <div class="stepNo"><span>1</span></div>
		                      <div class="stepName"><?= __('General Info');?></div>
		                      </div>
		                      <div class="progressCol active ">
		                      <div class="stepNo"><span>2</span></div>
		                      <div class="stepName"><?= __('Payment Method');?></div>
		                      </div>  
		                     
		                      </div>
                      </div>

                      <?php
                          }
                      ?>
  
                    <hr>
                </div> <!-- .et_pb_text -->
            </div>

            <br><br>
             <?php echo $this->Form->create($form, ['id' => 'step-4', 'class' => 'checkout_form' ])?>
                <div id="checkout_4_wrapper">
                    <div class="active">
                      <script charset="UTF-8" src="https://ssl.ditonlinebetalingssystem.dk/integration/ewindow/paymentwindow.js" type="text/javascript"></script>
                      
                <div id="payment-div">
                  <?php 
                          $base_path = Router::url('/', true);
                          

                          $acceptUrl = $base_path."orders/software-thank-you?utm_nooverride=1";
                          $callback = $base_path."orders/software-thank-you?utm_nooverride=1";
                        
                          if($locale == 'da')
                          {
                           $acceptUrl = $base_path."dk/ordrer/software-tak?utm_nooverride=1";
                           $callback = $base_path."dk/ordrer/software-tak?utm_nooverride=1";                  
                          }    

                          $cancel = $order['url'];
                          $price_array = @explode(' ', $price);
                          $new_price = @$price_array[1];
                          if(empty($new_price)){
                            $new_price = $price_array[0];
                          }
                          $price = $this->Custom->getEpayPriceFormat($new_price);
                   ?>
                  <?php
                  $params = array('merchantnumber' => MERCHANT_ID, 'amount' => $price, 'currency' => $currency_code, 'instantcallback' => 1, 'orderid' => $orderId, 'ownreceipt' => 1, 'windowstate'=>2, 'accepturl'=>$acceptUrl, 'cancelurl' => $cancel, 'iframeheight' => 580);
                  ?>
                  <script charset="UTF-8" src="https://ssl.ditonlinebetalingssystem.dk/integration/ewindow/paymentwindow.js" type="text/javascript">
                  </script>
                  <div id="payment-div"></div>
                  <script type="text/javascript">
                    paymentwindow = new PaymentWindow({
                     <?php
                     foreach ($params as $key => $value)
                     {
                       echo "'" . $key . "': \"" . $value . "\",\n";
                     }
                     ?>
                     'hash': "<?php echo md5(implode("", array_values($params)) . "AstrowowNethues"); ?>",
                   });
                    paymentwindow.append('payment-div');
                    paymentwindow.open();
                  </script>
                </div>
              </div>
            </div>

              <div class="et_pb_section checkout_header et_pb_section_0 et_section_regular">
              <div class="et_pb_text et_pb_module et_pb_bg_layout_light et_pb_text_align_right checkout_footer et_pb_text_2">
                <hr>
                <?php 

                 if($order['product_type'] == SHAREWARE)
                 {
                   echo  $this->Html->link('<i class="fa fa-long-arrow-left" aria-hidden="true"></i> '. __('Back'),['controller' => 'Orders', 'action' => 'software-checkout-step-1'] ,[  'class' => 'check_back btn btn-red', 'escape' => false ]);
                 }
                 else
                 {
                   echo  $this->Html->link('<i class="fa fa-long-arrow-left" aria-hidden="true"></i> '.__('Back'),['controller' => 'Orders', 'action' => 'software-checkout-step-2'] ,[  'class' => 'check_back btn btn-red', 'escape' => false  ]);
                 }
                
                ?>

              </div>
            </div>



            <?php echo $this->Form->end();?>
            <br><br>

        </div> <!-- .et_pb_code -->
    </div> <!-- .et_pb_column -->

</div> <!-- .et_pb_row -->

</div> <!-- .et_pb_section -->
</div> <!-- .entry-content -->


</article> <!-- .et_pb_post -->
