<?php 
  $da_data = $data['_translations']['da'];
?>

<div class="box box-default">
  <div class="box-header with-border">
    <h3 class="box-title">Event</h3>

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
                      <?php echo $this->Form->text('title', ['class' => 'form-control validate[required]', 'maxlength' => 255, 'value' => $data['title'] ]) ?>
                    </div>
                  </div>

                   <div class="col-md-6">
                    <div class="form-group">
                      <label>Date *</label>
                      <?php echo $this->Form->text('date', ['class' => 'form-control validate[required]', 'maxlength' => 255, 'value' => $data['date'] ]) ?>
                    </div>
                  </div>

                </div>


               <div class="row">

                  <div class="col-md-6">
                    <div class="form-group">
                      <label>
                      Time *</label>
                      <?php echo $this->Form->text('time', ['class' => 'form-control validate[required]', 'maxlength' => 255, 'value' => $data['time'] ]) ?>
                    </div>
                  </div>

                   <div class="col-md-6">
                    <div class="form-group">
                      <label>Place *</label>
                      <?php echo $this->Form->text('place', ['class' => 'form-control validate[required]', 'maxlength' => 255, 'value' => $data['place'] ]) ?>
                    </div>
                  </div>

                </div>

                  <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>Description *</label>
                      <?php echo $this->Form->textarea('description', ['class' => 'form-control html_editor validate[required]', 'maxlength' => 500, 'value' => $data['description']]) ?>
                    </div>
                  
                  </div>

                </div>

              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="tab_2">

               <div class="row">

                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Title *</label>
                      <?php echo $this->Form->text('_translations.da.title', ['class' => 'form-control validate[required]', 'maxlength' => 255, 'value' => $da_data['title']]) ?>
                    </div>
                  </div>

                   <div class="col-md-6">
                    <div class="form-group">
                      <label>Date *</label>
                      <?php echo $this->Form->text('_translations.da.date', ['class' => 'form-control validate[required]', 'maxlength' => 255, 'value' => $da_data['date'] ]) ?>
                    </div>
                  </div>

                </div>


               <div class="row">

                  <div class="col-md-6">
                    <div class="form-group">
                      <label>
                      Time *</label>
                      <?php echo $this->Form->text('_translations.da.time', ['class' => 'form-control validate[required]', 'maxlength' => 255, 'value' => $da_data['time'] ]) ?>
                    </div>
                  </div>

                   <div class="col-md-6">
                    <div class="form-group">
                      <label>Place *</label>
                      <?php echo $this->Form->text('_translations.da.place', ['class' => 'form-control validate[required]', 'maxlength' => 255, 'value' => $da_data['place'] ]) ?>
                    </div>
                  </div>

                </div>

               
                   <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>Description *</label>
                      <?php echo $this->Form->textarea('_translations.da.description', ['class' => 'form-control html_editor validate[required]', 'maxlength' => 500, 'value' => $da_data['description'] ]) ?>
                    </div>
                  
                  </div>

                </div>


              </div>

              <div class="tab-pane" id="tab_3">
               
                  <div class="row">

                   <div class="col-md-6">
                    <div class="form-group">
                      <label>Image *</label>
                      <?php echo $this->Form->file('image', ['class' => 'form-control']) ?>
                       <?php 
                        if($data['image']) {
                           $img_path = $this->request->webroot.'uploads'.DS.'events'.DS.'thumb'.DS.'sm'.DS;
                           echo '<br /><img src="'.$img_path.$data['image'].'" />';
                        }   
                        echo $this->Form->hidden('prev_image', ['value' => $data['image']]);
                      ?>
                    </div>
                  </div>



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
                      echo $this->Form->select('status', $options, ['class' => 'form-control', 'value'=>$data['status']] ) ?>
                      
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