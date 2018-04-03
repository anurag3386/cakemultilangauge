<?php use Cake\Routing\Router; ?>
<?php use Cake\I18n\I18n;?>
<?php use Cake\Cache\Cache;?>
<?php $locale = strtolower(I18n::locale());?>
<div class="consultation_col_3">
  <div class="et_pb_column et_pb_column_1_2  et_pb_column_7">
    <div class="et_pb_code et_pb_module  et_pb_code_2">
      <?php echo $this->Form->create($form, ['id' => 'frmCheckOutConsultation'])?>
      <!--   <form name="frmCheckOutConsultation" id="frmCheckOutConsultation" method="post" action="/consultation-checkout-step-1/">
    -->
      <?php
        if( $locale == 'en_us' ) {
          $default = 9;
          $currencyRadioButton =  $this->Form->input('currency_id', [
            'type' => 'radio',
            'options' => $currencyOptions,
            'templates' => [
                'nestingLabel' => '{{hidden}}<label{{attrs}}>{{text}}</label>{{input}}',
                'radioWrapper' => '<li>{{label}}<div class="check"></div></li>'
            ],
            'value' => '1',
            'label' => false
          ]);
          // $currencyRadioButton = $this->Form->radio('currency_id',$currencyOptions, [ 'value' => 1 , 'id'=> 'currency_id']
          //   );
          echo $this->Form->hidden('language_id', ['value' => ENGLISH]);
        } else {
          $default = 21;
          // $currencyRadioButton = $this->Form->radio('currency_id',$currencyOptions, [ 'value' => 3 , 'id'=> 'currency_id']
          //  );
          $currencyRadioButton =  $this->Form->input('currency_id', [
            'type' => 'radio',
            'options' => $currencyOptions,
            'templates' => [
                'nestingLabel' => '{{hidden}}<label{{attrs}}>{{text}}</label>{{input}}',
                'radioWrapper' => '<li>{{label}}<div class="check"></div></li>'
            ],
            'value' => '3',
            'label' => false
          ]);
          echo $this->Form->hidden('language_id', ['value' => DANISH]);
        }
      ?>
      <?php echo $this->Form->select('category_id', $categoryOptions, ['id' => 'category_id', 'default' => $default , 'class' => 'down']);?>
      <div class="currency-dropdown">
        <p><strong><?= __('Please choose your currency');?>:</strong></p>
        <br>
        <div class="redo">
          <ul>
            <?= $currencyRadioButton; ?>
          </ul>
        </div>
      </div>
      <p id="dvError"></p>
      <p><strong> <?= __('Consultation Type');?>:</strong></p>
      <div class="redo">
        <ul>
          <?php 
            $i = 0 ;
            foreach( $priceInfo as $product) {
              if($product['ProductPrices']['discount_total_price'] == 0){
                $productPrice = $product['ProductPrices']['total_price'];
              } else {
                $productPrice = $product['ProductPrices']['discount_total_price']; 
              }
              $currencySymbol = $product['currency']['symbol'];
              $currencyCode = $product['currency']['code'];
              $currencyId = $product['currency']['id'];
              $productName = $product['name'];
              if($i == 0) {
                $checked = "checked = 'checked'";
              } else {
                $checked = "";
              }
              //changed by anurag dubey Nov-13-2017
              //if( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' || $_SERVER['REMOTE_ADDR'] == '47.9.199.112')  {
              if($currencySymbol=='kr.'){
                $productPrice = round($productPrice,0);
              }
            //}
          ?>
          <p> 
            <li>
              <input name="consult_product" id="product_id_<?php echo $i?>" value="<?php echo $product['id']?>" <?php echo $checked;?> type="radio" onclick = "setConsultationData(this.value, <?php echo $i?>)"  >
              <!-- <label for="product_id_<?php echo $i?>"><?= $productName; ?> </label>
   -->
              <label for="product_id_<?php echo $i?>">
                <span id="consult-title-<?php echo $i?>"><?php echo __d('default', $product['name']);?></span>
                <span class="lblCurrencyConsultation_1" id="spnSymbol_<?php echo $i?>" style="display: inline;">(<?php echo $currencySymbol.$productPrice;?>)
                </span> 
              </label>
              <div class="check"></div>
            </li>

            <?php echo $this->Form->hidden('price'.$i, ['id' => 'checkout-price-'.$i, 'value' => $productPrice]); ?>
            <?php echo $this->Form->hidden('product_name'.$i, ['value' => $productName, 'id' => 'product_name_'. $i]); ?>

            <!--  <label for="consultation_14">
              <span id="consult-title-<?php echo $i?>"><?php echo __d('default', ucwords($product['name']));?></span> 

              <span class="lblCurrencyConsultation_1" id="spnSymbol_<?php echo $i?>" style="display: inline;">(<?php echo $currencySymbol.$productPrice;?>)
              </span> 

            </label>   -->

            <?php $i++; } ?>
            <?php echo $this->Form->hidden('counter', ['id' => 'counter', 'value'=>0]); ?>
            <?php echo $this->Form->hidden('product_id', ['id' => 'product_id', 'value'=>$priceInfo[0]['id']]); ?>
            <?php echo $this->Form->hidden('price', ['id' => 'checkout-price', 'value' => $priceInfo[0]['ProductPrices']['total_price']]); ?>
            <?php echo $this->Form->hidden('currencyCode', ['class' => 'checkout-currency-code', 'value' => $priceInfo[0]['currency']['code']]); ?>
            <?php echo $this->Form->hidden('currency_symbol', ['class' => 'checkout-currency-symbol', 'value'=> $priceInfo[0]['currency']['symbol'] ]); ?>
            <?php echo $this->Form->hidden('product_name', [ 'id' => 'product_name', 'value' => $priceInfo[0]['name']]); ?>
            <?php echo $this->Form->hidden('url', ['value' => Router::url($this->request->here, true)])?>
          </p>
          <div id="consultation" style="display:none">
            <p> 
              <li>
                <input name="consult_product" id="product_id_2" type="radio" onclick = "setConsultationData(this.value, 2)" >
                <label for="product_id_2">
                  <span id="consult-title-2"></span> 
                  <span class="lblCurrencyConsultation_1" id="spnSymbol_2" style="display: inline;"></span> 
                  <?php echo $this->Form->hidden('price2', ['id' => 'checkout-price-2']); ?>
                  <?php echo $this->Form->hidden('product_name2', [ 'id' => 'product_name_2']); ?>
                </label>  
                <div class="check"></div>
              </li>
              <!--  <label for="consultation_14">
              <span id="consult-title-2"></span> 
              <span class="lblCurrencyConsultation_1" id="spnSymbol_2" style="display: inline;">
              </span> 
              <?php echo $this->Form->hidden('price2', ['id' => 'checkout-price-2']); ?>
              <?php echo $this->Form->hidden('product_name2', [ 'id' => 'product_name_2']); ?>
              </label>   -->
            </p>
          </div>
        </ul>
      </div>
      <?php echo $this->Form->hidden('locale', ['id' => 'locale', 'val' => $locale]);?>
      <p><?php echo $this->Form->submit(__('Order Consultation'), ['class' => 'btn btn-red']);?></p>
      <?php echo $this->Form->end();?>
    </div> <!-- .et_pb_code -->
  </div> <!-- .et_pb_column -->
</div>