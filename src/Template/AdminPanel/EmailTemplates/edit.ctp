<?php 
  $da_data = $data['_translations']['da'];
?>

<div class="box box-default">
  <div class="box-header with-border">
    <h3 class="box-title">Email Template</h3>
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
              <li><a href="#tab_3" data-toggle="tab">Template Variables</a></li>
             </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_1">

                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>Subject *</label>
                      <?php echo $this->Form->text('name', ['class' => 'form-control validate[required]', 'maxlength' => 255, 'value' => $data['name'] ]) ?>
                    </div>
                  
                  </div>
                 
                </div>

                  <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>Content *</label>
                      <?php echo $this->Form->textarea('content', ['class' => 'form-control html_editor validate[required]', 'value' => $data['content']]) ?>
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
                <label>Subject *</label>
                <?php echo $this->Form->text('_translations.da.name', ['class' => 'form-control validate[required]', 'maxlength' => 255, 'value' => $da_data['name']]) ?>
              </div>
            
            </div>
          
          </div>

            <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label>Content *</label>
                <?php echo $this->Form->textarea('_translations.da.content', ['class' => 'form-control html_editor validate[required]', 'value' => $da_data['content']]) ?>
              </div>
            
            </div>
            <!-- /.col -->
           
          </div>

            </div>

               <div class="tab-pane" id="tab_3">
                <div class="row">
                 <div class="col-md-12">
                 <ul>
                 <?php 
                  $variables = explode(',', $data['variables']);
                  foreach($variables as $variable) {
                 ?>
                   <li><?php echo trim($variable); ?></li>
                 <?php } ?> 
                 </ul>

                 <!--  <div class="form-group">
                  <label>Variables * &nbsp; <span style="font-size:11px; font-weight:normal">(Enter Comma Separated Values)</span></label>
                  <?php // echo $this->Form->text('variables',['class' => 'form-control validate[required]']);  ?>
                  </div> -->
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