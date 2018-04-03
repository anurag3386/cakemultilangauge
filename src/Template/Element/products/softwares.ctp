<?php use Cake\Routing\Router;?>
<?php use Cake\Cache\Cache;
      use Cake\I18n\I18n;
      $locale = strtolower( I18n::locale() );
 ?>
<div class="et_pb_column et_pb_column_1_4  et_pb_column_0">
 <div class="left_software_section">

  <div class="et_pb_module et-waypoint et_pb_image et_pb_animation_left et_pb_image_0 et_always_center_on_mobile et-animated">

    <?php 
    $img_path = $this->request->webroot.'uploads'.DS.'products'.DS;
    echo '<br /><img src="'.$img_path.$data['image'].'" alt="'.$data['name'].'"/>';    
    ?>
  </div>
  <?php 
       $productType = $data['productType']; 
  ?>
  <div class="et_pb_row et_pb_row_0 et_pb_row_1-2_1-4_1-4 et_pb_text_align_center">
    <label for="drpLanguage"><?= __('Language');?></label><br>
    <?php 
          foreach($LanguageOptions as $key => $selectLanguage)
          {

           $val[$selectLanguage->language_id] = $selectLanguage['language']->name;
          }
 
         if(!isset($val) || empty($val))
         {
          $val = '';
         }

         if( $locale == 'en_us')
         {
           $default = 1;
         }
         else
         {
          $default = 2;
         }
        echo $this->Form->select('language_id', $val, ['class' => 'select-sm', 'id' => 'language', 'default' => $default]);
   ?>
</div>
<br/>


<div class="et_pb_row et_pb_row_0 et_pb_row_1-2_1-4_1-4 et_pb_text_align_center">
  <!--<p>30 Days Free Trial</p>-->
  <p>

    <?php
    $order['product_id']    = $data['id'];
    $order['product_type']  = $productType; 
    $order['type']          = $type;
    $order['category']      = $category; 

    if($category != SOFTWARE_BUNDLE_SLUG)
    {
      //echo $this->Form->create(null, ['url' => ['controller' => 'Orders', 'action' => 'download-free-trial',  $data['id'] ], 'type' => 'put']);

      echo $this->Form->create($form);
      echo $this->Form->hidden('product_type', ['value' => FREE_TRIAL]);
      echo $this->Form->hidden('category', ['value' => 'free-trial']);
      echo $this->Form->hidden('language_id', [ 'class' => 'language_id', 'value' => $default]);
      echo $this->Form->hidden('product_id', ['value' => $data['id']]);
      echo $this->Form->submit(__('Download Free Trial'), ['class' => 'btn btn-blue btn-sm']);
      echo $this->Form->end();
    }
    ?>

  </p>
  <br>

</div>
<?php  if($category != SOFTWARE_BUNDLE_SLUG)
{
  ?>
  <div class="et_pb_row et_pb_row_0 et_pb_row_1-2_1-4_1-4">
    <div class="et_pb_text_align_center"> <!--et_pb_column et_pb_column_1 et_pb_column_0-->
      <h2 class="sideLines"><span><?= __('OR');?></span></h2>
    </div>
  </div>
  <?php 
}
?>
<div class="et_pb_row et_pb_row_0 et_pb_row_1-2_1-4_1-4 et_pb_text_align_center ">
  <p>
    <?php
            $active = '';
            $active1 = '';
            if($productType    == SHAREWARE)
            {
              $active = 'active-color';
            }
            elseif($productType == SOFTWARE_CD)
            {
              $active1 = 'active-color';
            }

           // echo $this->Form->button(__('Buy/Register shareware'), ['type' => 'button', 'class' => $active ." btn-color btn btn-blue btn-sm", 'onclick' => "changePrice('".$data['id']."', document.getElementById('currency_id').value, ".SHAREWARE.", '".$category."')"]);
             echo $this->Form->button(__('Buy/Register shareware'), ['type' => 'button', 'class' => $active ." btn-color btn btn-blue btn-sm", 'onclick' => "changePrice('".$data['id']."', document.getElementById('currency_id').value, ".SHAREWARE.", '".$category."', '".$locale."')"]);
    ?>

  </p>
  <br>

</div>
<div class="et_pb_row et_pb_row_0 et_pb_row_1-2_1-4_1-4">
  <div class="et_pb_text_align_center"> <!--et_pb_column et_pb_column_1 et_pb_column_0-->
    <h2 class="sideLines"><span><?= __('OR');?></span></h2>
  </div>
</div>

<div class="et_pb_row et_pb_row_0 et_pb_row_1-2_1-4_1-4 et_pb_text_align_center ">
  <p>
    <?php
       //echo $this->Form->button(__('Buy Software CD'), ['type' => 'button', 'class' => $active1."  btn-color btn btn-blue btn-sm", 'onclick' => "changePrice('".$data['id']."', document.getElementById('currency_id').value, ".SOFTWARE_CD.",'".$category."')"]);
    echo $this->Form->button(__('Buy Software CD'), ['type' => 'button', 'class' => $active1."  btn-color btn btn-blue btn-sm", 'onclick' => "changePrice('".$data['id']."', document.getElementById('currency_id').value, ".SOFTWARE_CD.",'".$category."', '".$locale."')"]);



    ?>

  </p>
  <br>

</div>
<?php echo $this->Form->create($form, ['id' => 'form_id']) ?>
<?php echo $this->Form->hidden('url', ['value' => Router::url( $this->request->here, true )]);?>
<?php echo $this->Form->hidden('category', ['value' => $category]);?>
<?php echo $this->Form->hidden('product_id',['value' => $data['id'] ]); ?>
<?php echo $this->Form->hidden('image',['value' => $data['image'] ]); ?>
<?php echo $this->Form->hidden('pages',['value' => $data['pages'] ]); ?>
<?php echo $this->Form->hidden('product_name',['value' => $data['name'] ]); ?>
<?php echo $this->Form->hidden('seo_url',['value' => $data['seo_url'] ]); ?>
<?php  echo $this->Form->hidden('language_id', [ 'class' => 'language_id', 'value' => $default]);?>
<?php echo $this->Form->hidden('product_type',['value' => $productType , 'id' => 'product_type_id' ]); ?>



<div class="et_pb_text et_pb_module et_pb_bg_layout_light et_pb_text_align_center  et_pb_text_0">

  <div>
   <div class="et_pb_row et_pb_row_0 et_pb_row_1-4_3-4">
     <div class="et_pb_row et_pb_row_0 et_pb_row_1-2_1-4_1-4 et_pb_text_align_center">
      <label for="currency"><?= __('Currency');?></label><br>
      <?php
      
      $defaultCurrency = ($locale == 'en_us') ? 1 : 3;
      echo $this->Form->select('currency_id', $CurrencyOptions, ['id' => 'currency_id','default' => $defaultCurrency, 'class' => 'select-sm', 'onchange' => 'changePrice('.$data['id'].', this.value, document.getElementById(\'product_type_id\').value,\''.$category.'\',\''.$locale.'\' )']);
      ?>    
    </div>
  </div>


  <br class="clearfix">
  <?php echo $this->Form->hidden('price', ['value'=> $data['product_price'], 'id' => 'price']);?>
    </div>
   
</div>
</div>
</div>
<?php 
      $order['check'] = 'products-page';
      $order['url']   = Router::url($this->request->here, true);
      $this->request->session()->write('Order' , $order);
?>

