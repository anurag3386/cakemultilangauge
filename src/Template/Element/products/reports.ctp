<?php use Cake\Routing\Router;?>
<?php use Cake\Cache\Cache; ?>
<?php use Cake\I18n\I18n;?>

<?php 
 $locale = substr( strtolower( I18n::locale() ), 0, 2 ) ;
?>
<div class="et_pb_column et_pb_column_1_4  et_pb_column_0">

  <div class="et_pb_module et-waypoint et_pb_image et_pb_animation_left et_pb_image_0 et_always_center_on_mobile et-animated productDetail">
    <?php

          $img_path = $this->request->webroot.'uploads'.DS.'products'.DS;
          echo '<br /><img src="'.$img_path.$data['image'].'" alt="'.__($data["name"]).'" />'; // Updated by Kingslay (2017-04-12) : alt attribute   
    ?>
  </div>
  <?php 
  $category               = $this->Custom->getCategory($data['category_id']);
  $productType            = $data['productType']; 
  $order['product_id']    = $data['id'];
  $order['product_type']  = $productType; 
  $order['category']      = $category; 
  ?>
  <div class="et_pb_text et_pb_module et_pb_bg_layout_light et_pb_text_align_center  et_pb_text_0">
   <cite><?= __('Approx');?> <?php echo $data['pages']; ?> <?= __('pages');?></cite>
   <?php echo $this->Form->create($form, ['id' => 'form_id']) ?>
   <?php echo $this->Form->hidden('url', ['value' => Router::url( $this->request->here, true )]);?>
   <?php echo $this->Form->hidden('category', ['value' => $category]);?>
   <?php echo $this->Form->hidden('product_id',['value' => $data['id'] ]); ?>
   <?php echo $this->Form->hidden('image',['value' => $data['image'] ]); ?>
   <?php echo $this->Form->hidden('pages',['value' => $data['pages'] ]); ?>
   <?php echo $this->Form->hidden('product_name',['value' => $data['name'] ]); ?>
   <?php echo $this->Form->hidden('seo_url',['value' => $data['seo_url'] ]); ?>
   <?php echo $this->Form->hidden('product_type',['value' => $productType , 'id' => 'product_type_id' ]); ?>
   <div class="et_pb_row et_pb_row_0 et_pb_row_1-4_3-4">
     <div class="et_pb_column et_pb_column_1_2 et_pb_column_0">

    <?php 
      $deafultCurrency = ($locale == 'en') ? 1 : 3;
    ?>

     <label for="currency"><?php echo __('Currency');?></label><br>
      <?php echo $this->Form->select('currency_id', $CurrencyOptions, ['id' => 'currency_id', 'class' => 'down', 'default' => $deafultCurrency, 'onchange' => 'changePrice('.$data['id'].', this.value, document.getElementById(\'product_type_id\').value,\''.$category.'\')']);
      ?>    
    </div>


    <div class="et_pb_column et_pb_column_1_2  et_pb_column_2">
      <label for="drpLanguage" id='rl'><?= __('Language');?></label><br>
      <?php
      foreach($LanguageOptions as $key => $selectLanguage)
      {

       $val[$selectLanguage->language_id] = $selectLanguage['language']->name;
     }

     if(!isset($val) || empty($val))
     {
      $val = '';
    }

    ?>
    <?php 
       $default = ( $locale == 'en') ? 1 : 2;
       echo $this->Form->select('language_id', $val, ['class' => 'down', 'id' => 'language', 'default' => $default]);
    ?>
    
  </div>


</div>
<br class="clearfix">
<p class="total_left"><?= __('TOTAL');?></p>

<strike><p id="original_price">
  <?php if(isset($data['original_price']) && !empty($data['product_price']))
  {
    echo $this->Custom->removeSpace($data['original_price'],$locale);
  } 
  ?>
</p></strike>
<h2 id="total_price" class="currency_h2">
  <?php echo $this->Custom->removeSpace($data['product_price'],$locale); ?> 
</h2>
<?php 
echo $this->Form->hidden('price', ['value'=> $data['product_price'], 'id' => 'price']);
?>
<p class="inclusive_vat">(<?= __('Inclusive VAT')?>)</p>

<?php echo $this->Form->submit(__('Buy Now'), ['class' => 'btn btn-red btn-sm']); ?>
<br>
<?php echo $this->element('products/satisfaction'); ?>
<!-- <p><em style="text-align:left;"></em></p><em style="text-align:left;">
</em></ul><em style="text-align:left;">
<p><em style="text-align:left;"><br></em></p>
</em> --></div></div>
<?php 
      $order['check'] = 'products-page';
      $order['url']   = Router::url($this->request->here, true);
      $this->request->session()->write('Order' , $order);
?>