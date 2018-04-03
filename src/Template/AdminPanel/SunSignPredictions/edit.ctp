<style>
.datepicker-days table tbody tr:hover {
    background-color: #eee;
}
</style>
<div class="box box-default">
  <div class="box-header with-border">
   <div class="row">
    <div class="col-md-6">
    <h3 class="box-title">Update Predictions</h3>
    </div>
    <div class="col-md-6">
       <div class="col-md-3">
        <span class="pull-right">
          <a href="<?php echo $this->Url->build(['controller' => 'SunSignPredictions', 'action' => 'add'])?>" 
          class="btn btn-success btn-sm">Add Prediction</a>
         </span>

       </div>
       <div class="col-md-3">
         <span>
           <a href="<?php echo $this->Url->build(['controller' => 'SunSignPredictions', 'action' => 'search-prediction'])?>" 
          class="btn btn-success btn-sm">Search Prediction</a>
         </span>
       </div>

        <div class="col-md-3">
         <span>
          <a href="<?php echo $this->Url->build(['controller' => 'SunSignPredictions', 'action' => 'index'])?>" 
          class="btn btn-success btn-sm">Upload Prediction File</a>
         </span>
       </div>

    </div>
  </div>
 
  </div>
  <?php echo $this->Form->create($form, ['id' => 'form_id', 'novalidate' => true]) ?>
    <span style="display:none" id="edit_option">0</span>
  <!-- /.box-header -->
  <div class="box-body">

       <div class="row">
        <div class="col-md-12">
          <!-- Custom Tabs -->
          <div class="nav-tabs-custom">
            <div class="tab-content">
              <div class="tab-pane active" id="tab_1">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Scope *</label>
                      <?php
                           $scope = [
                                      '1' => 'Daily' ,
                                      '2' => 'Weekly' ,
                                      '3' => 'Monthly',
                                      '4' => 'Yearly' ,

                                     ];
                       echo $this->Form->select('scope', $scope, ['class' => 'form-control validate[required]', 'id'=>'scope'] ); ?>
                    </div>
                  
                  </div>
                  <!-- /.col -->
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Schedule Date *</label>
                   <div class="input-group date">
                        <div class="input-group-addon">
                          <i class="fa fa-calendar"></i>
                        </div>
                        <?php 
                          $form['schedule_date'] = date('Y-m-d', strtotime($form['schedule_date']));
                        echo $this->Form->text('schedule_date', ['class' => 'form-control pull-right validate[required]', 'id' => 'datepicker-1'] ); ?>
                      </div>
                    </div>
                    
                  </div>
                  <!-- /.col -->
                </div>

                <div class="row">

                  <div class="col-md-6">
                      <div class="form-group">
                      <label>Sun Sign *</label>
                     <?php echo $this->Form->select('sun_sign_id', $sunSignOptions, ['class' => 'form-control validate[required]', 'empty' => 'Select Sunsign'] ); ?>
                    </div>
                  
                  </div>
                  <div class="col-md-6">
                          <div class="form-group">
                                <div class="col-md-6">
                                <?php echo $this->Form->text('startDate', ['class' => 'form-control', 'style' => ['display:none'], 'readonly' => true, 'id' => 'startDate'
                                ])?>
                                </div>
                                <div class="col-md-6">
                                <?php echo $this->Form->text('endDate', ['class' => 'form-control', 'style' => ['display:none'], 'readonly' => true, 'id' => 'endDate'
                                ])?>
                                </div>
                            </div>
                        </div>


              </div>
              <div class="row">

                  <div class="col-md-6">
                      <div class="form-group">
                      <label>Language *</label>
                     <?php 
                              $languageOptions = [
                                      'en' => 'English' ,
                                      'dk' => 'Danish' ,

                                     ];
                     echo $this->Form->select('language', $languageOptions, ['class' => 'form-control validate[required]'] ); ?>
                    </div>
                  
                  </div>


              </div>
              <div class="row">

                  <div class="col-md-6">
                      <div class="form-group">
                      <label>Content *</label>
                     <?php
                          $form['prediction'] = strip_tags($form['prediction']); 
                          echo $this->Form->textarea('prediction', ['class' => 'form-control validate[required]'] ); ?>
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