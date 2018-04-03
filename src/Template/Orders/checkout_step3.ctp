<?php use Cake\Routing\Router; ?>
<?php use Cake\Cache\Cache; ?>
<?php use Cake\I18n\I18n;?>
<?php 
      $order = $this->request->session()->read('Order');
      $price = $order['price'];
      $image = $order['image'];
      $pages = $order['pages'];
      $product_name = $order['product_name'];
      $product_id = $order['product_id'];
      $locale = strtolower( substr( I18n::locale(), 0, 2) );
?>
<article id="post-303" class="post-303 page type-page status-publish hentry">
  <div class="entry-content">
    <div class="et_pb_section  et_pb_section_0 et_section_regular checkout-pages">
      <div class=" et_pb_row et_pb_row_0">
        <div class="et_pb_column et_pb_column_4_4  et_pb_column_0">
          <div class="et_pb_code et_pb_module  et_pb_code_0">
            <div class="et_pb_section checkout_header et_pb_section_0 et_section_regular top-section">
              <div class="et_pb_text et_pb_module et_pb_bg_layout_light et_pb_text_align_left  et_pb_text_0">
                <div class="checkoutLeft">
               <?php
                   $translate = $this->Custom->getTranslation('Products',['Products.id' => $product_id], ['image', 'name'] );
               ?>
               
                <h3 class="checkout_title"><?php echo ucwords( $translate['name'] )?></h3>
                <cite><?= __('Approx');?> <?php echo $pages?> <?= __('pages');?></cite>
                <p><cite><?= __('Deliver through Email');?></cite></p>
                  
                <?php echo $this->Html->image("/uploads/products/".$translate['image'], [ 'class' => 'size-thumbnail wp-image-164', 'alt'=> $translate['name'] ])?>    
               
                </div>
                        <div class="total_price_section">
                            <?php if($finalPrice['vat'] > 0)
                                  {//changed by anurag dubey 13-nov-2017
                                      ?>
                            <h3 class="checkout_price">
                              <?= __('Price');?>: <?= $finalPrice['currency_symbol'].$this->Custom->formatPrice($finalPrice['price'],$finalPrice['currency_symbol']); ?>
                              </h3>
                              <h4 class="">
                              <?= __('VAT').': '.$finalPrice['currency_symbol'].$this->Custom->formatPrice($finalPrice['vat'],$finalPrice['currency_symbol']);
                              ?></h4>
                              <?php
                                  }
                              ?>
                              
                              <h5 class="">
                              <?= __('Total Price');?>: <strong><?php echo $finalPrice['currency_symbol'].$this->Custom->formatPrice($finalPrice['total_price'],$finalPrice['currency_symbol']);?></strong
                              >
                              </h5>
                     </div>
                
                    <div class="checkoutProgressBox">
                    <div class="progressCol  complete">
                    <div class="stepNo"><span>1</span></div>
                    <div class="stepName"><?= __('General Info');?></div>
                    </div>
                    <div class="progressCol complete step3">
                    <div class="stepNo"><span>2</span></div>
                    <div class="stepName"><?= __('Personal Info');?></div>
                    </div>  
                    <div class="progressCol active">
                    <div class="stepNo"><span>3</span></div>
                    <div class="stepTitle"><?= __('Payment Method');?></div>
                    </div>          
                    </div>
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
                        //if( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' ) {
                          $acceptUrl = $base_path."orders/thanks?utm_nooverride=1";
                          $callback = $base_path."orders/thanks?utm_nooverride=1";
                        /*} else {
                          $acceptUrl = $base_path."orders/thank-you";
                          $callback = $base_path."orders/thank-you";                          
                        }*/
                        
                        
                        if($locale == 'da')
                        {
                          //if( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' ) {
                            $acceptUrl = $base_path."dk/ordrer/taks?utm_nooverride=1";
                            $callback = $base_path."dk/ordrer/taks?utm_nooverride=1";
                          /*} else {
                            $acceptUrl = $base_path."dk/ordrer/tak";
                            $callback = $base_path."dk/ordrer/tak";                  
                          }*/
                        }    
                                              
                        $cancel = $order['url'];
                        $currency = $order['currency_code'];
                        $price_array = explode(' ', $price);
                        $price = $this->Custom->getEpayPriceFormat($price_array[1],$currency);
                  ?>
                  <?php
                  //$params = array('merchantnumber' => "8023058", 'amount' => $price, 'currency' => $currency, 'instantcallback' => 1, 'orderid' => $order_id, 'ownreceipt' => 1, 'windowstate'=>2, 'accepturl'=>$acceptUrl, 'cancelurl' => $cancel, 'iframeheight' => 580);
                  
                      /*if( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' )
                      {
                        $price = $this->Custom->getEpayPriceFormat(0.10);
                        $currency = 'USD';
                      }*/

                   $params = array('merchantnumber' => MERCHANT_ID, 'amount' => $price, 'currency' => $currency, 'instantcallback' => 1, 'orderid' => $order_id, 'ownreceipt' => 1, 'windowstate'=>2, 'accepturl'=>$acceptUrl, 'cancelurl' => $cancel, 'iframeheight' => 580);
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
            <?php echo $this->Form->end();?>
          
            <div class="et_pb_section checkout_header et_pb_section_0 et_section_regular">
              <div class="et_pb_text et_pb_module et_pb_bg_layout_light et_pb_text_align_right checkout_footer et_pb_text_2">
                <hr>
                <?php 
             
                 echo  $this->Html->link('<i class="fa fa-long-arrow-left" aria-hidden="true"></i> '. __('Back'),['controller' => 'Orders', 'action' => 'checkout-step-2'] ,[  'class' => 'btn btn-red check_back'  , 'escape' => false]);
                ?>
              </div>
            </div>
          </div> <!-- .et_pb_code -->
        </div> <!-- .et_pb_column -->

      </div> <!-- .et_pb_row -->

    </div> <!-- .et_pb_section -->
  </div> <!-- .entry-content -->
</article> <!-- .et_pb_post -->