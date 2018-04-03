<?php
  $titleEnglish = $descriptionEnglish = $titleDanish = $descriptionDanish = '';
  if (!empty($this->request->session()->read('mini-blog-data'))) {
    $titleEnglish = $this->request->session()->read('mini-blog-data.title');
    $descriptionEnglish = $this->request->session()->read('mini-blog-data.description');
    $titleDanish = $this->request->session()->read('mini-blog-data._translations.da.title');
    $descriptionDanish = $this->request->session()->read('mini-blog-data._translations.da.description');
  }
?>
<div class="box box-default">
  <div class="box-header with-border">
    <h3 class="box-title">Mini Blogs</h3>

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

                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Title *</label>
                      <?php echo $this->Form->text('title', ['class' => 'form-control validate[required]', 'maxlength' => 255, 'value' => $titleEnglish]) ?>
                    </div>
                  </div>


                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Image</label>
                      <?php echo $this->Form->file('image', ['class' => 'form-control']) ?>
                    </div>
                  </div>
                </div>

              

                  <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>Description *</label>
                      <?php echo $this->Form->textarea('description', ['class' => 'form-control html_editor validate[required]', 'maxlength' => 500, 'value' => $descriptionEnglish]) ?>
                    </div>
                  
                  </div>

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

                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Title *</label>
                      <?php echo $this->Form->text('_translations.da.title', ['class' => 'form-control validate[required]', 'maxlength' => 255, 'value' => $titleDanish]) ?>
                    </div>
                  </div>
                 
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Image</label>
                      <?php echo $this->Form->file('_translations.da.image', ['class' => 'form-control']) ?>
                    </div>
                  </div>

                </div>
              
                 <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>Description *</label>
                      <?php echo $this->Form->textarea('_translations.da.description', ['class' => 'form-control html_editor validate[required]', 'maxlength' => 500, 'value' => $descriptionDanish]) ?>
                    </div>
                  
                  </div>

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
               
                 <div class="row">
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