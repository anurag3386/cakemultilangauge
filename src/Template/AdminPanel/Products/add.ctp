<div class="box box-default">
  <div class="box-header with-border">
    <h3 class="box-title">Product</h3>
  </div>
  <?php 
       //$productType    = $this->request->session()->read('productType');
       //  $categoryType   = $this->request->session()->read('categoryType');
       // $product_type_1 = isset($productType['productType1']) ? $productType['productType1'] : ''; 
       // $product_type_2 = isset($productType['productType2']) ? $productType['productType2'] : ''; 
       
   ?>
  <?php echo $this->Form->create($form, ['id' => 'form_id', 'novalidate' => true, 'enctype' => 'multipart/form-data']) ?>
  <?php //echo $this->Form->hidden('categoryType')?>
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
                      <?php echo $this->Form->text('name', ['class' => 'form-control validate[required]', 'maxlength' => 255, 'onmouseout' => 'javascript:updateSeo( $(this).val() )']) ?>
                    </div>
                  </div>

                </div>

                   <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>Short Description *</label>
                      <?php echo $this->Form->textarea('short_description', ['class' => 'form-control validate[required]', 'maxlength' => 500, 'rows'=>2]) ?>
                    </div>
                  
                  </div>

                </div>


                  <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>Content *</label>
                      <?php echo $this->Form->textarea('description', ['class' => 'form-control html_editor validate[required]']) ?>
                    </div>
                  
                  </div>
                  <!-- /.col -->
                 
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Meta Title </label>
                      <?php echo $this->Form->text('meta_title', ['class' => 'form-control', 'maxlength' => 255]) ?>
                    </div>

                     <div class="form-group">
                      <label>Meta Keywords </label>
                     <?php echo $this->Form->text('meta_keywords', ['class' => 'form-control', 'maxlength' => 500])?>
                    </div>
                  
                  </div>
                  <!-- /.col -->
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Meta Description </label>
                     <?php echo $this->Form->textarea('meta_description', ['class' => 'form-control'])?>
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
                      <?php echo $this->Form->text('_translations.da.name', ['class' => 'form-control validate[required]', 'maxlength' => 255]) ?>
                    </div>
                  </div>

                </div>

                   <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>Short Description *</label>
                      <?php echo $this->Form->textarea('_translations.da.short_description', ['class' => 'form-control validate[required]', 'maxlength' => 500, 'rows'=>2]) ?>
                    </div>
                  
                  </div>

                </div>


                  <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>Content *</label>
                      <?php echo $this->Form->textarea('_translations.da.description', ['class' => 'form-control html_editor validate[required]']) ?>
                    </div>
                  
                  </div>
                  <!-- /.col -->
                 
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Meta Title </label>
                      <?php echo $this->Form->text('_translations.da.meta_title', ['class' => 'form-control', 'maxlength' => 255]) ?>
                    </div>

                     <div class="form-group">
                      <label>Meta Keywords </label>
                     <?php echo $this->Form->text('_translations.da.meta_keywords', ['class' => 'form-control', 'maxlength' => 500])?>
                    </div>
                  
                  </div>
                  <!-- /.col -->
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Meta Description </label>
                     <?php echo $this->Form->textarea('_translations.da.meta_description', ['class' => 'form-control'])?>
                    </div>
                    
                  </div>
                  <!-- /.col -->
                </div>

              </div>

            <div class="tab-pane" id="tab_3">


            <div class="nav-tabs-custom">
             

             <ul class="nav nav-tabs">
              <li class="active"><a href="#tab_5" data-toggle="tab" id="dynamic-tab-1" >Tab-1</a></li>
              <li><a href="#tab_6" data-toggle="tab" id="dynamic-tab-2" style="display:none">Tab-2</a></li>
             </ul>


            <div class="tab-content">
              <div class="tab-pane active" id="tab_5">
              <?php echo $this->Form->hidden('product-type-1', ['id' => 'product-type-1']) ;?>
              <?php 
                $currencyArr = $this->Custom->getCurrencies();
                $i = 0;
                foreach($currencyArr as $item) {
                  ++$i;

                  $currency = strtolower($item['code']);
                  $key = $item['symbol'];
                  $currency_id = $item['id'];
              ?>
               <?php echo $this->Form->hidden('product_price.'.$i.'.currency_id', ['value' => $currency_id]) ;    echo $this->Form->hidden('product_price.'.$i.'.product_type_id',['id' => 'product_price_'.$i.'_product_type_id']) ;
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
                      <?php echo $this->Form->text('product_price.'.$i.'.price', ['id' => 'price_'.$currency.'_'.$i, 'class' => 'form-control validate[required, custom[number]]', 'maxlength' => 10, 'value' => '0.00', 'placeholder' => 'Price', 'onkeyup' => 'javascript:calculatePrice(\''.$currency.'\', '.$i.')'  ] ) ?>
                    </div>
                  </div>

                  <div class="col-md-2">
                    <label>VAT *</label>
                     <div class="input-group">
                      <span class="input-group-addon">VAT</span>
                      <?php echo $this->Form->text('product_price.'.$i.'.vat', ['id' => 'vat_'.$currency.'_'.$i, 'class' => 'form-control validate[required, custom[number]]', 'maxlength' => 6, 'value' => '0.00', 'placeholder' => 'VAT', 'onkeyup' => 'javascript:calculatePrice(\''.$currency.'\', '.$i.')']) ?>
                    </div>
                  </div>


                   <div class="col-md-2">
                     <label>Total Price</label>
                     <div class="input-group">
                      <span class="input-group-addon"><?php echo $key; ?></span>
                      <?php echo $this->Form->text('product_price.'.$i.'.total_price', ['id' => 'total_'.$currency.'_'.$i, 'class' => 'form-control validate[required, custom[number]]', 'maxlength' => 10, 'value' => '0.00', 'readonly']) ?>
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
                      <?php echo $this->Form->text('product_price.'.$i.'.discount_price', ['id' => 'discount_price_'.$currency.'_'.$i, 'class' => 'form-control validate[required, custom[number]]', 'maxlength' => 10, 'value' => '0.00', 'placeholder' => 'Price', 'onkeyup' => 'javascript:calculateDiscountPrice(\''.$currency.'\', '.$i.')'  ] ) ?>
                    </div>
                  </div>

                  <div class="col-md-2">
                     <div class="input-group">
                      <span class="input-group-addon">VAT</span>
                      <?php echo $this->Form->text('product_price.'.$i.'.discount_vat', ['id' => 'discount_vat_'.$currency.'_'.$i, 'class' => 'form-control validate[required, custom[number]]', 'maxlength' => 6, 'value' => '0.00', 'placeholder' => 'VAT', 'onkeyup' => 'javascript:calculateDiscountPrice(\''.$currency.'\','.$i.')']) ?>
                    </div>
                  </div>


                   <div class="col-md-2">
                     <div class="input-group">
                      <span class="input-group-addon"><?php echo $key; ?></span>
                      <?php echo $this->Form->text('product_price.'.$i.'.discount_total_price', ['id' => 'discount_total_'.$currency.'_'.$i, 'class' => 'form-control validate[required, custom[number]]', 'maxlength' => 10, 'value' => '0.00', 'readonly']) ?>
                    </div>
                  </div>

                </div>
                <hr />
                <?php } ?>    

              </div>

              <div class="tab-pane" id="tab_6" style="display:none">


              <?php echo $this->Form->hidden('product-type-2', ['id' => 'product-type-2']) ;?>
               
               <?php 
                  $currencyArr = $this->Custom->getCurrencies();
                  foreach($currencyArr as $item) {
                     ++$i;
                  $currency = strtolower($item['code']);
                  $key = $item['symbol'];
                  $currency_id = $item['id'];
              ?>
               <?php echo $this->Form->hidden('product_price.'.$i.'.currency_id', ['value' => $currency_id]) ;
                    echo $this->Form->hidden('product_price.'.$i.'.product_type_id',['id' => 'product_price_'.$i.'_product_type_id'] ) ;
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
                      <?php echo $this->Form->text('product_price.'.$i.'.price', ['id' => 'price_'.$currency.'_'.$i, 'class' => 'form-control validate[required, custom[number]]', 'maxlength' => 10, 'value' => '0.00', 'placeholder' => 'Price', 'onkeyup' => 'javascript:calculatePrice(\''.$currency.'\', '.$i.')'  ] ) ?>
                    </div>
                  </div>

                  <div class="col-md-2">
                    <label>VAT *</label>
                     <div class="input-group">
                      <span class="input-group-addon">VAT</span>
                      <?php echo $this->Form->text('product_price.'.$i.'.vat', ['id' => 'vat_'.$currency.'_'.$i, 'class' => 'form-control validate[required, custom[number]]', 'maxlength' => 6, 'value' => '0.00', 'placeholder' => 'VAT', 'onkeyup' => 'javascript:calculatePrice(\''.$currency.'\', '.$i.')']) ?>
                    </div>
                  </div>


                   <div class="col-md-2">
                     <label>Total Price</label>
                     <div class="input-group">
                      <span class="input-group-addon"><?php echo $key; ?></span>
                      <?php echo $this->Form->text('product_price.'.$i.'.total_price', ['id' => 'total_'.$currency.'_'.$i, 'class' => 'form-control validate[required, custom[number]]', 'maxlength' => 10, 'value' => '0.00', 'readonly']) ?>
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
                      <?php echo $this->Form->text('product_price.'.$i.'.discount_price', ['id' => 'discount_price_'.$currency.'_'.$i, 'class' => 'form-control validate[required, custom[number]]', 'maxlength' => 10, 'value' => '0.00', 'placeholder' => 'Price', 'onkeyup' => 'javascript:calculateDiscountPrice(\''.$currency.'\', '.$i.')'  ] ) ?>
                    </div>
                  </div>

                  <div class="col-md-2">
                     <div class="input-group">
                      <span class="input-group-addon">VAT</span>
                      <?php echo $this->Form->text('product_price.'.$i.'.discount_vat', ['id' => 'discount_vat_'.$currency.'_'.$i, 'class' => 'form-control validate[required, custom[number]]', 'maxlength' => 6, 'value' => '0.00', 'placeholder' => 'VAT', 'onkeyup' => 'javascript:calculateDiscountPrice(\''.$currency.'\','.$i.')']) ?>
                    </div>
                  </div>


                   <div class="col-md-2">
                     <div class="input-group">
                      <span class="input-group-addon"><?php echo $key; ?></span>
                      <?php echo $this->Form->text('product_price.'.$i.'.discount_total_price', ['id' => 'discount_total_'.$currency.'_'.$i, 'class' => 'form-control validate[required, custom[number]]', 'maxlength' => 10, 'value' => '0.00', 'readonly']) ?>
                    </div>
                  </div>

                </div>
                <hr />
                <?php } ?>  
              </div> <!-- tab-content-6-->







              </div> <!-- tab-content -->
              </div>



                        


              </div>



              <div class="tab-pane" id="tab_4">
                <div class="row">

                  <div class="col-md-3">

                    <div class="form-group">
                      <label>Category *</label>
                      <?php echo $this->Form->select('category_id', $categoryOptions, ['class' => 'form-control validate[required]', 'id' => 'category' , 'onclick' => 'javascript:getProductTypes(this.value)', 'empty' => 'Select']) ?>
                    </div>
                  </div>

                   <div class="col-md-3">
                    <div class="form-group">
                      <label>Product Language *</label>
                      <?php echo $this->Form->select('language_id', $languageOptions, ['multiple' => true, 'class' => 'form-control validate[required]', 'id' => 'language']) ?>
                    </div>
                  </div>

                  <div class="col-md-3" style="display:none" id="products">
                    <div class="form-group">
                      <label>Products *</label>
                      <?php echo $this->Form->select('parent_id', $productOptions, ['multiple' => true, 'class' => 'form-control validate[required]', 'id' => 'products_id']) ?>
                    </div>
                  </div>




                <!--   <div class="col-md-3">


                     <div class="form-group">
                      <label>SEO URL *</label>
                      <?php //echo $this->Form->text('seo_url', ['class' => 'form-control validate[required]', 'maxlength' => 255, 'id' => 'seo_url']) ?>
                    </div>

                  </div> -->

                  
                  <div class="col-md-3">
                    <div class="form-group">
                      <label>Product Number *</label>
                      <?php echo $this->Form->text('pr_number', ['class' => 'form-control validate[required, custom[onlyNumberSp]]', 'maxlength' => 4]) ?>
                    </div>
                  </div>

                  

                </div>

                <div class="row">

                  <div class="col-md-3">

         
                    <div class="form-group">
                      <label>Image English *</label>
                      <?php echo $this->Form->file('image', ['class' => 'form-control validate[required]']) ?>
                    </div>
                  </div>

                   <div class="col-md-3">

         
                    <div class="form-group">
                      <label>Image Danish*</label>
                      <?php echo $this->Form->file('_translations.da.image', ['class' => 'form-control validate[required]']) ?>
                    </div>
                  </div>

                  <!--  <div class="col-md-4">
                    <div class="form-group">
                      <label>Detail Page Image *</label>
                       <?php // echo $this->Form->file('image_detail', ['class' => 'form-control validate[required]']) ?>
                    </div>
                  </div>
 -->
                     <div class="col-md-2">
                    <div class="form-group">
                      <label>Sort Order *</label>
                      <?php echo $this->Form->text('sort_order', ['class' => 'form-control validate[required, custom[onlyNumberSp]]', 'maxlength' => 3, 'value' => 0]) ?>

                    </div>
                  </div>

                   <div class="col-md-2">
                    <div class="form-group">
                      <label>Status</label>
                      <?php 
                      $options = ['1' => 'Active', '2' => 'Inactive'];
                      echo $this->Form->select('status', $options, ['class' => 'form-control'] ) ?>
                      
                    </div>
                  </div>

                   <div class="col-md-2">
                    <div class="form-group">
                      <label>Number of Pages </label>
                      <?php echo $this->Form->text('pages', ['class' => 'form-control validate[custom[onlyNumberSp]]', 'maxlength' => 3]) ?>
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

