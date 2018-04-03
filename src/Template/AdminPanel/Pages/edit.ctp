<div class="box box-default">
  <div class="box-header with-border">
    <h3 class="box-title">Page</h3>
  </div>
  <?php echo $this->Form->create($form, ['id' => 'form_id', 'novalidate' => true]) ?>
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
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>Title *</label>
                      <?php echo $this->Form->text('title', ['class' => 'form-control validate[required]', 'maxlength' => 255, 'value' => $data['title']]) ?>
                    </div>
                  
                  </div>
                 
                </div>

                  <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>Content *</label>
                      <?php echo $this->Form->textarea('body', ['class' => 'form-control html_editor validate[required]', 'value' => $data['body']]) ?>
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
                <label>Title *</label>
                <?php echo $this->Form->text('_translations.da.title', ['class' => 'form-control validate[required]', 'maxlength' => 255, 'value' => $data['_translations']['da']['title'] ]) ?>
              </div>
            
            </div>
          
          </div>

            <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label>Content *</label>
                <?php echo $this->Form->textarea('_translations.da.body', ['class' => 'form-control html_editor validate[required]', 'value' => $data['_translations']['da']['body']]) ?>
              </div>
            
            </div>
            <!-- /.col -->
           
          </div>


           <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Meta Title </label>
                <?php echo $this->Form->text('_translations.da.meta_title', ['class' => 'form-control', 'maxlength' => 255, 'value' => $data['_translations']['da']['meta_title']]) ?>
              </div>

               <div class="form-group">
                <label>Meta Keywords </label>
               <?php echo $this->Form->text('_translations.da.meta_keywords', ['class' => 'form-control', 'maxlength' => 500, 'value' => $data['_translations']['da']['meta_keywords']])?>
              </div>
            
            </div>
            <!-- /.col -->
            <div class="col-md-6">
              <div class="form-group">
                <label>Meta Description </label>
               <?php echo $this->Form->textarea('_translations.da.meta_description', ['class' => 'form-control', 'value' => $data['_translations']['da']['meta_description']])?>
              </div>
              
            </div>
            <!-- /.col -->
          </div>

              </div>

               <div class="tab-pane" id="tab_3">
                <div class="row">
                 <div class="col-md-6">
                  <div class="form-group">
                  <label>SEO URL *</label>
                  <?php echo $this->Form->text('seo_url',['class' => 'form-control validate[required]', 'maxlength' => 255, 'id' => 'seo_url', 'value'=>$data['seo_url']]);  ?>
                  </div>
                  </div>
                <div class="col-md-2">
                  <div class="form-group">
                  <label>Status</label>
                  <?php 
                  $options = ['1' => 'Active', '2' => 'Inactive'];
                  echo $this->Form->select('status', $options, ['class' => 'form-control', 'value' => $data['status']])
                  ?>
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

