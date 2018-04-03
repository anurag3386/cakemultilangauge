<?php $da_data = $data['_translations']['da']; ?>
<div class="box box-default">
  <div class="box-header with-border">
    <h3 class="box-title">Sample Report</h3>
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
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Title *</label>
                      <?php echo $this->Form->text('title', ['class' => 'form-control validate[required]', 'maxlength' => 255, 'value'=>$data['title']]) ?>
                    </div>                  
                  </div>

                   <div class="col-md-4">
                    <div class="form-group">
                      <label>Upload PDF File *</label>
                      <?php echo $this->Form->file('pdf', ['class' => 'form-control']); ?>
                       <?php 
                        if($data['pdf']) {
                           $img_path = $this->request->webroot.'uploads'.DS.'preview_reports'.DS;
                           echo '<br /><a target="_blank" class="btn btn-success btn-xs" href="'.$img_path.$data['pdf'].'">View File</a>';
                        }   
                        echo $this->Form->hidden('prev_pdf', ['value' => $data['pdf']]);
                      ?>

                    </div>                  
                  </div>

                   <div class="col-md-4">
                    <div class="form-group">
                      <label>Upload Thumb Image *</label>
                      <?php echo $this->Form->file('image', ['class' => 'form-control']) ?>
                       <?php 
                        if($data['image']) {
                           $img_path = $this->request->webroot.'uploads'.DS.'preview_reports'.DS.'thumb'.DS.'sm'.DS;
                           echo '<br /><img src="'.$img_path.$data['image'].'" />';
                        }   
                        echo $this->Form->hidden('prev_image', ['value' => $data['image']]);
                      ?>
                    </div>                  
                  </div>
                 
                </div>


              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="tab_2">

                 <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Title *</label>
                      <?php echo $this->Form->text('_translations.da.title', ['class' => 'form-control validate[required]', 'maxlength' => 255, 'value' => $da_data['title']]) ?>
                    </div>                  
                  </div>

                   <div class="col-md-4">
                    <div class="form-group">
                      <label>Upload PDF File *</label>
                      <?php echo $this->Form->file('_translations.da.pdf', ['class' => 'form-control']) ?>
                      <?php 
                        if($da_data['pdf']) {
                           $img_path = $this->request->webroot.'uploads'.DS.'preview_reports'.DS;
                           echo '<br /><a target="_blank" class="btn btn-success btn-xs" href="'.$img_path.$da_data['pdf'].'">View File</a>';
                        }   
                        echo $this->Form->hidden('_translations.da.prev_pdf', ['value' => $da_data['pdf']]);
                      ?>
                    </div>                  
                  </div>

                   <div class="col-md-4">
                    <div class="form-group">
                      <label>Upload Thumb Image *</label>
                      <?php echo $this->Form->file('_translations.da.image', ['class' => 'form-control']) ?>
                       <?php 
                        if($da_data['image']) {
                           $img_path = $this->request->webroot.'uploads'.DS.'preview_reports'.DS.'thumb'.DS.'sm'.DS;
                           echo '<br /><img src="'.$img_path.$da_data['image'].'" />';
                        }   
                        echo $this->Form->hidden('_translations.da.prev_image', ['value' => $da_data['image']]);
                      ?>
                    </div>                  
                  </div>
                 
                </div>
 

            </div>

               <div class="tab-pane" id="tab_3">

                <div class="row">

                 <div class="col-md-2">
                  <div class="form-group">
                    <label>Status</label><br />
                     <?php
                    $options = ['1' => 'Active', '2' => 'Inactive'];
                    echo $this->Form->select('status', $options, ['class' => 'form-control', 'value' => $data['status']]) ?>
                  </div>
                
                </div>

                <div class="col-md-2">
                  <div class="form-group">
                    <label>Sort Order *</label><br />
                     <?php echo $this->Form->text('sort_order', ['class' => 'form-control validate[required, custom[onlyNumberSp]]', 'maxlength' => 3, 'value' => $data['sort_order']]) ?>
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