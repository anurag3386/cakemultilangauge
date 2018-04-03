<?php use Cake\Cache\Cache; ?>
<?php 
    $shippingData    = $this->request->session()->read('shipping_data');
    $address1        = (isset($shippingData['address_1'])) ? $shippingData['address_1']   : '';
    $address2        = (isset($shippingData['address_2'])) ? $shippingData['address_2']   : '';
    $city            = (isset($shippingData['city']))      ? $shippingData['city']        : '';
    $country         = (isset($shippingData['country']))   ? $shippingData['country']     : '';
    $state           = (isset($shippingData['state']))     ? $shippingData['state']       : '';
    $postalcode      = (isset($shippingData['postal_code'])) ? $shippingData['postal_code'] : '';
    $phone           = (isset($shippingData['phone']))     ? $shippingData['phone']       : '';
    //$order           = $this->request->session()->read('Order');
   
    $order           = $this->request->session()->read('SOrder');
   
    $price           = $order['price'];
    $image           = $order['image'];
    $pages           = $order['pages'];
    $seo_url         = $order['seo_url'];
    $language_id     = $order['language_id'];
    $type            = $order['type'];
    $product_name    = $order['product_name'];
    
    $username        = $this->request->session()->read('username');
    $last_name       = $this->request->session()->read('last_name');
    $first_name      = $this->request->session()->read('first_name');
    $language        = $order['language'];
    $product_id      = $order['product_id'];
?>
<article id="post-1295" class="post-1295 page type-page status-publish hentry">
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
                            
                              <?php
                                  }
                              ?>
                              
                              <h5 class="">
                              <?= __('Total Price');?>: <strong>
                              <?php 
                                   echo $finalPrice['currency_symbol'].$this->Custom->formatPrice($finalPrice['total_price'],$finalPrice['currency_symbol']);
                                   echo ($order['product_type'] == SOFTWARE_CD)? ' ( Incl. Shipping )'/*' + Shipping'*/ : '';
                              ?>
                                
                              </strong
                              >
                              </h5>
                     </div>
                                          

  <div class="checkoutProgressBox">
  <div class="progressCol  complete">
  <div class="stepNo"><span>1</span></div>
  <div class="stepName"><?= __('General Info');?></div>
  </div>
  <div class="progressCol active">
  <div class="stepNo"><span>2</span></div>
  <div class="stepName"><?= __('Shipping Address');?></div>
  </div>  
  <div class="progressCol">
  <div class="stepNo"><span>3</span></div>
  <div class="stepTitle"><?= __('Payment Method');?></div>
  </div>          
  </div>

<hr>
</div> <!-- .et_pb_text -->
</div>
<br><br>
<?php echo $this->Form->create($form,  ['id' => 'step-1', 'class' => 'checkout_form' ])?>

<div id="checkout_3_wrapperr">
    <div class="active">
        <label for="os_first_name"><?= __('First Name');?></label>
        
         <?php echo $this->Form->text('first_name', [ 'id' => 'firstname', 'tabindex' => '2', 'class' =>'input-sm validate[required]', 'placeholder' => 'John', 'default' => $first_name] );
        ?> 
        <br>
        <br>
        <label for="os_last_name"><?= __('Last Name');?></label>
        <?php echo $this->Form->text('last_name', [ 'id' => 'last_name', 'tabindex' => '3', 'class' =>'input-sm validate[required]', 'placeholder' => 'Doe' , 'default' => $last_name] );
        ?>
        <br>
        <br>

        <label for="address_line_1"><?= __('Address 1');?></label>
        <?php echo $this->Form->text('address_1', [ 'id' => 'address_1', 'tabindex' => '4', 'class' =>'inputLarge validate[required]', 'placeholder' => 'Address 1', 'value' => $address1 ]);
        ?>
      
        <br>

        <label for="address_line_2"><?= __('Address 2');?></label>
        <?php echo $this->Form->text('address_2', [ 'id' => 'address_2', 'tabindex' => '5', 'class' =>'inputLarge', 'placeholder' => 'Address 2', 'value' => $address2 ]);
        ?>
        
        <br>

        <label for="shipping_city"><?= __('City');?></label>
        <?php echo $this->Form->text('city', [ 'id' => 'city', 'tabindex' => '6', 'class' =>'inputLarge validate[required]', 'value' => $city]);
        ?>
        <br>
        <label for="shipping_country"><?= __('Country');?></label>
        <?php echo $this->Form->select('country', $countryOptions, ['id' => 'country',  'class' => 'validate[required]', 'tabindex' => '7', 'empty' => 'Select Country', 'value' => $country] ); 
        ?>
        <br>

        <label for="shipping_state"><?= __('State');?></label>

        <?php echo $this->Form->text('state', [ 'id' => 'state', 'tabindex' => '8', 'class' =>'inputLarge',  'placeholder' => 'State', 'value' => $state]);
        ?>
        <br>

        <label for="shipping_zipcode"><?= __('Postcode');?></label>
        <?php echo $this->Form->text('postal_code', [ 'id' => 'postal_code', 'tabindex' => '9', 'class' =>'inputMedium validate[required]', 'placeholder' => 'Postcode', 'value' => $postalcode]);
        ?>

        <br>

        <label for="customer_phone"><?= __('Telephone');?></label>
        <?php echo $this->Form->text('phone', [ 'id' => 'phone', 'tabindex' => '10', 'class' =>'inputMedium validate[required]', 'placeholder' => 'Your Phone number', 'value' => $phone]);
        ?>
        <br>
        </div>
    </div>

       <div class="et_pb_section checkout_header et_pb_section_0 et_section_regular">
        <div class="et_pb_text et_pb_module et_pb_bg_layout_light et_pb_text_align_right checkout_footer et_pb_text_2 footer-center">
            <hr>
            <?php 
            echo  $this->Html->link("<i class='fa fa-long-arrow-left' aria-hidden='true'></i> ". __('Back'),['controller' => 'Orders', 'action' => 'software-checkout-step1'] ,[  'class' => 'check_back btn btn-red',  'escape' => false]);
            ?>

            <?php 
            echo  $this->Form->button(__('Payment Method').' <i class="fa fa-long-arrow-right" aria-hidden="true"></i>', [ 'id' => 'submit', 'class' => 'btn btn-red' , 'type' => 'submit']);
            ?>

            <?php echo $this->Form->end();?> 


        </div>
    </div>
    <?php echo $this->Form->end();?> 

</div> <!-- .et_pb_code -->
</div> <!-- .et_pb_column -->

</div> <!-- .et_pb_row -->

</div> <!-- .et_pb_section -->
</div> <!-- .entry-content -->


</article> <!-- .et_pb_post -->
