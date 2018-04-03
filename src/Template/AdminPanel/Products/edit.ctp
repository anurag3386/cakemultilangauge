<?php $da_data = $data['_translations']['da']; ?>

<div class="box box-default">
  <div class="box-header with-border">
    <h3 class="box-title">Product</h3>
  </div>
 
  <?php echo $this->Form->create($form, ['id' => 'form_id', 'novalidate' => true, 'enctype' => 'multipart/form-data']) ?>
  <!-- /.box-header -->
  <div class="box-body">

       <div class="row">
        <div class="col-md-12">
          <!-- Custom Tabs -->
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#tab_1" data-toggle="tab">English</a></li>
              <li><a href="#tab_2" data-toggle="tab">Dansk</a></li>
              <li><a href="#tab_4" data-toggle="tab">Details</a></li>
              <li><a href="#tab_3" data-toggle="tab">Prices</a></li>
             
             </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_1">

               <div class="row">

                  <div class="col-md-12">
                    <div class="form-group">
                      <label>Name *</label>
                      <?php echo $this->Form->text('name', ['class' => 'form-control validate[required]', 'maxlength' => 255, /*'onmouseout' => 'javascript:updateSeo( $(this).val() )', */ 'value' => $data['name'] ]) ?>
                    </div>
                  </div>

                </div>

                   <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>Short Description *</label>
                      <?php echo $this->Form->textarea('short_description', ['class' => 'form-control validate[required]', 'maxlength' => 500, 'rows'=>2, 'value' => $data['short_description']]) ?>
                    </div>
                  
                  </div>

                </div>


                  <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>Content *</label>
                      <?php echo $this->Form->textarea('description', ['class' => 'form-control html_editor validate[required]', 'value' => $data['description'] ]) ?>
                    </div>
                  
                  </div>
                  <!-- /.col -->
                 
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Meta Title </label>
                      <?php echo $this->Form->text('meta_title', ['class' => 'form-control', 'maxlength' => 255, 'value' => $data['meta_title']]) ?>
                    </div>

                     <div class="form-group">
                      <label>Meta Keywords </label>
                     <?php echo $this->Form->text('meta_keywords', ['class' => 'form-control', 'maxlength' => 500, 'value' => $data['meta_keywords']])?>
                    </div>
                  
                  </div>
                  <!-- /.col -->
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Meta Description </label>
                     <?php echo $this->Form->textarea('meta_description', ['class' => 'form-control', 'value' => $data['meta_description']])?>
                    </div>
                    
                  </div>
                  <!-- /.col -->
                </div>

              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="tab_2">
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>Name *</label>
                      <?php echo $this->Form->text('_translations.da.name', ['class' => 'form-control validate[required]', 'maxlength' => 255, 'value' => $da_data['name']]) ?>
                    </div>
                  </div>

                </div>

                   <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>Short Description *</label>
                      <?php echo $this->Form->textarea('_translations.da.short_description', ['class' => 'form-control validate[required]', 'maxlength' => 500, 'rows'=>2, 'value' => $da_data['short_description']]) ?>
                    </div>
                  
                  </div>

                </div>


                  <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>Content *</label>
                      <?php echo $this->Form->textarea('_translations.da.description', ['class' => 'form-control html_editor validate[required]', 'value' => $da_data['description']]) ?>
                    </div>
                  
                  </div>
                  <!-- /.col -->
                 
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Meta Title </label>
                      <?php echo $this->Form->text('_translations.da.meta_title', ['class' => 'form-control', 'maxlength' => 255, 'value' => $da_data['meta_title']]) ?>
                    </div>

                     <div class="form-group">
                      <label>Meta Keywords </label>
                     <?php echo $this->Form->text('_translations.da.meta_keywords', ['class' => 'form-control', 'maxlength' => 500, 'value' => $da_data['meta_keywords']])?>
                    </div>
                  
                  </div>
                  <!-- /.col -->
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Meta Description </label>
                     <?php echo $this->Form->textarea('_translations.da.meta_description', ['class' => 'form-control', 'value' => $da_data['meta_description']])?>
                    </div>
                    
                  </div>
                  <!-- /.col -->
                </div>

              </div>

                <?php
                        $categoryId = $data['category_id'];
                        $flag = 0;
                        switch($categoryId)
                        {
                            case 1 : 
                                      
                                      $productTypeData = $this->Custom->getProductTypes(['software-cd', 'shareware']);
                                       $flag = 1;
                                      break;
                            case 20 : $productTypeData = $this->Custom->getProductTypes(['software-cd', 'shareware']);
                                       $flag = 1;
                            break;
                                                            
                            case 2 :   $productTypeData = $this->Custom->getProductTypes(['full-reports', 'elite-full-report']);
                                       $flag = 1;
                                       break; 
                            case 10 :  $productTypeData = $this->Custom->getProductTypes(['preview-reports']);
                                       $flag = 0;
                                       break; 
                            case 19 :  $productTypeData = $this->Custom->getProductTypes(['calendar-subscription']);
                                       $flag = 0;
                                       break; 

                            case 18 :  $productTypeData = $this->Custom->getProductTypes(['elite-subscription']);
                                       $flag = 0;
                                       break; 


                            default : 

                                      $productTypeData[0] = 'Tab-1';
                                      $flag = 0 ;         
                                      break;
                        }
                       
                        $productTypeIds  = array_keys($productTypeData);

                        
                        $productTypeName = array_values($productTypeData); 
                                    
                ?>
              <div class="tab-pane" id="tab_3">
               <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                  <li class="active"><a href="#tab_5" data-toggle="tab" id="dynamic-tab-1"><?php

                      echo (isset($productTypeName[0])) ? $productTypeName[0] : 'Tab-1';

                    ?></a></li>
                  <?php 
                  
                     if($flag == 1)
                        {
                    ?>
                  <li><a href="#tab_6" data-toggle="tab" id="dynamic-tab-2"><?php
                     echo (isset($productTypeName[1])) ? $productTypeName[1] : 'Tab-2';

                   ?></a></li>
                  <?php }
                  ?>
                 </ul>
                <div class="tab-content">
                <div class="tab-pane active" id="tab_5">
              <?php 
                  $product_id = $data['id'];
                  $currencyArr = $this->Custom->getCurrencies();
                  $i = 0;
                  foreach($currencyArr as $item) {
                  $i++;
                  $currency = strtolower($item['code']);
                  $key = $item['symbol'];
                  $currency_id = $item['id'];
                  $price = $this->Custom->getProductPrice($product_id, $currency_id, $productTypeIds[0]);
              ?>
              <?php echo $this->Form->hidden('product_price.'.$i.'.price_id', ['value' => $price['id']]) ;?>
               <?php echo $this->Form->hidden('product_price.'.$i.'.currency_id', ['value' => $currency_id]) ;?>
               <?php echo $this->Form->hidden('product_price.'.$i.'.product_id', ['value' => $product_id]) ;
                    echo $this->Form->hidden('product_price.'.$i.'.product_type_id', ['value' => $productTypeIds[0]]) ;
               ?>
                <div class="row">

                  <div class="col-md-2">
                   <div class="form-group">
                      <label>&nbsp;</label>
                      <button type="button" style="display:block" class="btn btn-success" style="cursor: not-allowed;"><?php echo strtoupper($currency)?></button>
                    </div>
                    </div>


                  <div class="col-md-2">
                    <label>Price *</label>
                     <div class="input-group">
                      <span class="input-group-addon"><?php echo $key; ?></span>
                      <?php echo $this->Form->text('product_price.'.$i.'.price', ['id' => 'price_'.$currency.'_'.$i, 'class' => 'form-control validate[required, custom[number]]', 'maxlength' => 10, 'value' => $price['price'], 'placeholder' => 'Price', 'onkeyup' => 'javascript:calculatePrice(\''.$currency.'\', '.$i.')'  ] ) ?>
                    </div>
                  </div>

                  <div class="col-md-2">
                    <label>VAT *</label>
                     <div class="input-group">
                      <span class="input-group-addon">VAT</span>
                      <?php echo $this->Form->text('product_price.'.$i.'.vat', ['id' => 'vat_'.$currency.'_'.$i, 'class' => 'form-control validate[required, custom[number]]', 'maxlength' => 6, 'value' => $price['vat'], 'placeholder' => 'VAT', 'onkeyup' => 'javascript:calculatePrice(\''.$currency.'\','.$i.')']) ?>
                    </div>
                  </div>


                   <div class="col-md-2">
                     <label>Total Price</label>
                     <div class="input-group">
                      <span class="input-group-addon"><?php echo $key; ?></span>
                      <?php echo $this->Form->text('product_price.'.$i.'.total_price', ['id' => 'total_'.$currency.'_'.$i, 'class' => 'form-control validate[required, custom[number]]', 'maxlength' => 10, 'value' => $price['total_price'], 'readonly']) ?>
                    </div>
                  </div>
                  </div>

                   <div class="row">

                  <div class="col-md-2">
                   <div class="form-group">
                       <button type="button" style="display:block" class="btn btn-danger" style="cursor: not-allowed;">Discounted Price</button>
                     </div>
                    </div>


                  <div class="col-md-2">
                     <div class="input-group">
                      <span class="input-group-addon"><?php echo $key; ?></span>
                      <?php echo $this->Form->text('product_price.'.$i.'.discount_price', ['id' => 'discount_price_'.$currency.'_'.$i, 'class' => 'form-control validate[custom[number]]', 'maxlength' => 10, 'value' => $price['discount_price'], 'placeholder' => 'Price', 'onkeyup' => 'javascript:calculateDiscountPrice(\''.$currency.'\', '.$i.')'  ] ) ?>
                    </div>
                  </div>

                  <div class="col-md-2">
                     <div class="input-group">
                      <span class="input-group-addon">VAT</span>
                      <?php echo $this->Form->text('product_price.'.$i.'.discount_vat', ['id' => 'discount_vat_'.$currency.'_'.$i, 'class' => 'form-control validate[custom[number]]', 'maxlength' => 6, 'value' => $price['discount_vat'], 'placeholder' => 'VAT', 'onkeyup' => 'javascript:calculateDiscountPrice(\''.$currency.'\', '.$i.')']) ?>
                    </div>
                  </div>


                   <div class="col-md-2">
                     <div class="input-group">
                      <span class="input-group-addon"><?php echo $key; ?></span>
                      <?php echo $this->Form->text('product_price.'.$i.'.discount_total_price', ['id' => 'discount_total_'.$currency.'_'.$i, 'class' => 'form-control validate[ custom[number]]', 'maxlength' => 10, 'value' => $price['discount_total_price'], 'readonly']) ?>
                    </div>
                  </div>

                </div>
                <hr />
                <?php } ?>   

                </div>

                    <?php 
                  
                     if($flag == 1)
                        {
                    ?>
                <div class="tab-pane " id="tab_6">
                <?php
                  $currencyArr = $this->Custom->getCurrencies();
                  
                  foreach($currencyArr as $item) {
                  ++$i;
                  $currency = strtolower($item['code']);
                  $key = $item['symbol'];
                  $currency_id = $item['id'];
                  $price = $this->Custom->getProductPrice($product_id, $currency_id, $productTypeIds[1]);
              ?>
              <?php echo $this->Form->hidden('product_price.'.$i.'.price_id', ['value' => $price['id']]) ;?>
               <?php echo $this->Form->hidden('product_price.'.$i.'.currency_id', ['value' => $currency_id]) ;?>
               <?php echo $this->Form->hidden('product_price.'.$i.'.product_id', ['value' => $product_id]) ;

                echo $this->Form->hidden('product_price.'.$i.'.product_type_id', ['value' => $productTypeIds[1]]) ;?>
                <div class="row">

                  <div class="col-md-2">
                   <div class="form-group">
                      <label>&nbsp;</label>
                      <button type="button" style="display:block" class="btn btn-success" style="cursor: not-allowed;"><?php echo strtoupper($currency)?></button>
                    </div>
                    </div>


                  <div class="col-md-2">
                    <label>Price *</label>
                     <div class="input-group">
                      <span class="input-group-addon"><?php echo $key; ?></span>
                      <?php echo $this->Form->text('product_price.'.$i.'.price', ['id' => 'price_'.$currency.'_'.$i, 'class' => 'form-control validate[required, custom[number]]', 'maxlength' => 10, 'value' => $price['price'], 'placeholder' => 'Price', 'onkeyup' => 'javascript:calculatePrice(\''.$currency.'\', '.$i.')'  ] ) ?>
                    </div>
                  </div>

                  <div class="col-md-2">
                    <label>VAT *</label>
                     <div class="input-group">
                      <span class="input-group-addon">VAT</span>
                      <?php echo $this->Form->text('product_price.'.$i.'.vat', ['id' => 'vat_'.$currency.'_'.$i, 'class' => 'form-control validate[required, custom[number]]', 'maxlength' => 6, 'value' => $price['vat'], 'placeholder' => 'VAT', 'onkeyup' => 'javascript:calculatePrice(\''.$currency.'\','.$i.')']) ?>
                    </div>
                  </div>


                   <div class="col-md-2">
                     <label>Total Price</label>
                     <div class="input-group">
                      <span class="input-group-addon"><?php echo $key; ?></span>
                      <?php echo $this->Form->text('product_price.'.$i.'.total_price', ['id' => 'total_'.$currency.'_'.$i, 'class' => 'form-control validate[required, custom[number]]', 'maxlength' => 10, 'value' => $price['total_price'], 'readonly']) ?>
                    </div>
                  </div>
                  </div>

                   <div class="row">

                  <div class="col-md-2">
                   <div class="form-group">
                       <button type="button" style="display:block" class="btn btn-danger" style="cursor: not-allowed;">Discounted Price</button>
                     </div>
                    </div>


                  <div class="col-md-2">
                     <div class="input-group">
                      <span class="input-group-addon"><?php echo $key; ?></span>
                      <?php echo $this->Form->text('product_price.'.$i.'.discount_price', ['id' => 'discount_price_'.$currency.'_'.$i, 'class' => 'form-control validate[custom[number]]', 'maxlength' => 10, 'value' => $price['discount_price'], 'placeholder' => 'Price', 'onkeyup' => 'javascript:calculateDiscountPrice(\''.$currency.'\', '.$i.')'  ] ) ?>
                    </div>
                  </div>

                  <div class="col-md-2">
                     <div class="input-group">
                      <span class="input-group-addon">VAT</span>
                      <?php echo $this->Form->text('product_price.'.$i.'.discount_vat', ['id' => 'discount_vat_'.$currency.'_'.$i, 'class' => 'form-control validate[custom[number]]', 'maxlength' => 6, 'value' => $price['discount_vat'], 'placeholder' => 'VAT', 'onkeyup' => 'javascript:calculateDiscountPrice(\''.$currency.'\', '.$i.')']) ?>
                    </div>
                  </div>


                   <div class="col-md-2">
                     <div class="input-group">
                      <span class="input-group-addon"><?php echo $key; ?></span>
                      <?php echo $this->Form->text('product_price.'.$i.'.discount_total_price', ['id' => 'discount_total_'.$currency.'_'.$i, 'class' => 'form-control validate[custom[number]]', 'maxlength' => 10, 'value' => $price['discount_total_price'], 'readonly']) ?>
                    </div>
                  </div>

                </div>
               
                <?php }
               ?> 

                </div>
<?php }

?>


              </div>
              </div>



                        


              </div>




              <div class="tab-pane" id="tab_4">
                <div class="row">

                  <div class="col-md-3">

                    <div class="form-group">
                      <label>Category </label>
                     <?php echo $this->Form->select('category_id', $categoryOptions, ['class' => 'form-control validate[required]','id' => 'category', 'value' => $data['category_id'], 'empty' => 'Select', 'disabled' => true]) ?>
                    </div>
                  </div>

                   <div class="col-md-3">
                    <div class="form-group">
                      <label>Product Language *</label>
                      <?php 

                           foreach($selectedLanguages as $selectLanguage)
                           {
                             $val[]=$selectLanguage->language_id;
                           }
                           if(!isset($val) || empty($val))
                           {
                            $val = '';
                           }
                      ?>

                      <?php echo $this->Form->select('language_id', $languageOptions, ['multiple' => true, 'class' => 'form-control validate[required]', 'id' => 'language', 'value' => $val]) ?>
                    </div>
                  </div>
                  <?php if($data['category_id'] == '20'):?>
                  <?php 
                  
                    $products = explode(',', $data['parent_id']);
                  ?>
                   <div class="col-md-3"  id="products">
                    <div class="form-group">
                      <label>Products *</label>
                      <?php echo $this->Form->select('parent_id', $productOptions, ['multiple' => true, 'class' => 'form-control validate[required]', 'id' => 'products_id', 'value' => $products]) ?>
                    </div>
                  </div>
                <?php endif;?>

                  <!--  <div class="col-md-3">
                     <div class="form-group">
                      <label>SEO URL *</label>
                      <?php //echo $this->Form->text('seo_url', ['class' => 'form-control validate[required]', 'maxlength' => 255, 'id' => 'seo_url', 'value' => $data['seo_url']]) ?>
                    </div>

                  </div>
 -->
                 
                  <div class="col-md-3">
                    <div class="form-group">
                      <label>Product Number *</label>
                      <?php echo $this->Form->text('pr_number', ['class' => 'form-control validate[required, custom[onlyNumberSp]]', 'maxlength' => 4, 'value' => $data['pr_number'] ]) ?>
                    </div>
                  </div>

                

                </div>

                <div class="row">

                  <div class="col-md-3">

                    <div class="form-group">
                      <label>Image English</label>
                      <?php echo $this->Form->file('image', ['class' => 'form-control']) ?>
                       <?php 
                      if($data['image']) {
                         $img_path = $this->request->webroot.'uploads'.DS.'products'.DS.'thumb'.DS.'sm'.DS;
                         echo '<br /><img src="'.$img_path.$data['image'].'" />';
                      }   
                      echo $this->Form->hidden('prev_image', ['value' => $data['image']]);
                    ?>
                    </div>
                  </div>

                  <div class="col-md-3">

                    <div class="form-group">
                      <label>Image Danish</label>
                      <?php echo $this->Form->file('_translations.da.image', ['class' => 'form-control']) ?>
                       <?php 
                      if($da_data['image']) {
                         $img_path = $this->request->webroot.'uploads'.DS.'products'.DS.'thumb'.DS.'sm'.DS;
                         echo '<br /><img src="'.$img_path.$da_data['image'].'" />';
                      }   
                      echo $this->Form->hidden('prev_image_da', ['value' => $da_data['image']]);
                    ?>
                    </div>
                  </div>

<!--                    <div class="col-md-4">
                    <div class="form-group">
                      <label>Detail Page Image *</label>
                       <?php //echo $this->Form->file('image_detail', ['class' => 'form-control']) ?>
                        <?php 
                      /*if($data['image_detail']) {
                         $img_path = $this->request->webroot.'uploads'.DS.'products'.DS.'thumb'.DS.'sm'.DS;
                         echo '<br /><img src="'.$img_path.$data['image_detail'].'" />';
                      }   
                      echo $this->Form->hidden('prev_image_detail', ['value' => $data['image_detail']]);*/
                    ?>
                    </div>
                  </div> -->

                    <div class="col-md-2">
                    <div class="form-group">
                      <label>Sort Order *</label>
                      <?php echo $this->Form->text('sort_order', ['class' => 'form-control validate[required, custom[onlyNumberSp]]', 'maxlength' => 3, 'value' => $data['sort_order']]) ?>
                    </div>

                  </div>

                   <div class="col-md-2">
                    <div class="form-group">
                      <label>Status</label>
                      <?php 
                      $options = ['1' => 'Active', '2' => 'Inactive'];
                      echo $this->Form->select('status', $options, ['class' => 'form-control', 'value' => $data['status']] ) ?>
                      
                    </div>
                  </div>

                    <div class="col-md-2">
                    <div class="form-group">
                      <label>Number of Pages </label>
                      <?php echo $this->Form->text('pages', ['class' => 'form-control validate[custom[onlyNumberSp]]', 'maxlength' => 3, 'value' => $data['pages']]) ?>

                    </div>

                  </div>

                 

                </div>
              </div>

            </div>
            <!-- /.tab-content -->
          </div>
          <!-- nav-tabs-custom -->
        </div>
        <!-- /.col -->
      </div>

    <div class="box-tools pull-right">
       <?php echo $this->Form->button(__('Submit'), ['class' => 'btn btn-primary btn-block btn-flat']); ?>
    </div>

  </div>
  <?php echo $this->Form->end() ?>
  <!-- /.box-body -->
 
</div>
