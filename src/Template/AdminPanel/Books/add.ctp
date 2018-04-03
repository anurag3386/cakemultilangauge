<div class="box box-default">
  <div class="box-header with-border">
    <h3 class="box-title">Book</h3>

    <div class="box-tools pull-right">
      <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
      <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
    </div>
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
              <li><a href="#tab_3" data-toggle="tab">Details</a></li>
             </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_1">

               <div class="row">

                  <div class="col-md-9">
                    <div class="form-group">
                      <label>Title *</label>
                      <?php echo $this->Form->text('title', ['class' => 'form-control validate[required]', 'maxlength' => 255]) ?>
                    </div>
                  </div>

                   <div class="col-md-3">
                    <div class="form-group">
                      <label>Price *</label>
                      <?php echo $this->Form->text('price', ['class' => 'form-control validate[required, custom[number]]', 'maxlength' => 8]) ?>
                    </div>
                  </div>

                </div>

                <div class="row">

                  <div class="col-md-9">
                    <div class="form-group">
                      <label>Discount Text</label>
                      <?php echo $this->Form->text('discount_text', ['class' => 'form-control', 'maxlength' => 255]) ?>
                    </div>
                  </div>

                   <div class="col-md-3">
                    <div class="form-group">
                      <label>Button Text *</label>
                      <?php echo $this->Form->text('button_text', ['class' => 'form-control validate[required]', 'maxlength' => 50]) ?>
                    </div>
                  </div>

                </div>

                   <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>Description *</label>
                      <?php echo $this->Form->textarea('description', ['class' => 'form-control validate[required]', 'maxlength' => 500]) ?>
                    </div>
                  
                  </div>

                </div>

              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="tab_2">
                <div class="row">
                  <div class="col-md-9">
                    <div class="form-group">
                      <label>Title *</label>
                      <?php echo $this->Form->text('_translations.da.title', ['class' => 'form-control validate[required]', 'maxlength' => 255]) ?>
                    </div>
                  </div>

                  <div class="col-md-3">
                    <div class="form-group">
                      <label>Price *</label>
                      <?php echo $this->Form->text('_translations.da.price', ['class' => 'form-control validate[required, custom[number]]', 'maxlength' => 8]) ?>
                    </div>
                  </div>

                </div>

                  <div class="row">

                  <div class="col-md-9">
                    <div class="form-group">
                      <label>Discount Text</label>
                      <?php echo $this->Form->text('_translations.da.discount_text', ['class' => 'form-control', 'maxlength' => 255]) ?>
                    </div>
                  </div>

                   <div class="col-md-3">
                    <div class="form-group">
                      <label>Button Text *</label>
                      <?php echo $this->Form->text('_translations.da.button_text', ['class' => 'form-control validate[required]', 'maxlength' => 50]) ?>
                    </div>
                  </div>

                </div>


                   <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>Description *</label>
                      <?php echo $this->Form->textarea('_translations.da.description', ['class' => 'form-control validate[required]', 'maxlength' => 500]) ?>
                    </div>
                  
                  </div>

                </div>


              </div>

              <div class="tab-pane" id="tab_3">
                <div class="row">
                   <div class="col-md-6">
                    <div class="form-group">
                      <label>Author *</label>
                      <?php echo $this->Form->text('author', ['class' => 'form-control validate[required]', 'maxlength' => 150]) ?>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label>URL Link *</label>
                      <?php echo $this->Form->text('url', ['class' => 'form-control validate[required,custom[url]]', 'maxlength' => 255]) ?>
                    </div>
                  </div>

                  </div>
                  <div class="row">

                   <div class="col-md-6">
                    <div class="form-group">
                      <label>Image *</label>
                      <?php echo $this->Form->file('image', ['class' => 'form-control validate[required]']) ?>
                    </div>
                  </div>



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
<!-- /.box