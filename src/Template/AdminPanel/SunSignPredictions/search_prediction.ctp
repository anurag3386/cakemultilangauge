<style>
.datepicker-days table tbody tr:hover {
    background-color: #eee;
}

</style>
<!-- SELECT2 EXAMPLE -->
<div class="box box-default">
  <div class="box-header with-border">
    <div class="row">
    <div class="col-md-8">
       <h3 class="box-title">Search Prediction</h3>
    </div>
     <div class="col-md-4">
      <div class="col-md-6">
        <span class="add_btn">
          <a href="<?php echo $this->Url->build(['controller' => 'SunSignPredictions', 'action' => 'add'])?>" 
          class="btn btn-success btn-sm">Add Prediction</a>
         </span>
         </div>
        <div class="col-md-6">
         <span class="add_btn">
          <a href="<?php echo $this->Url->build(['controller' => 'SunSignPredictions', 'action' => 'index'])?>" 
          class="btn btn-success btn-sm">Upload Prediction File</a>
         </span>
       </div>
      </div>
    </div>
  </div>
  <?php echo $this->Form->create($form, ['id' => 'form_id']) ?>
  <!-- /.box-header -->
  <div class="box-body">

    <div class="row">
      <div class="col-md-2">
        <div class="form-group">
          <label>Scope *</label>
          <?php
                           $scope = [
                                      '1' => 'Daily' ,
                                      '2' => 'Weekly' ,
                                      '3' => 'Monthly',
                                      '4' => 'Yearly' ,

                                     ];
                       echo $this->Form->select('scope', $scope, ['class' => 'form-control validate[required]', 'id' => 'scope'] ); ?>
        </div>
      
      </div>
      <!-- /.col -->
      <div class="col-md-2">
        <div class="form-group">
          <label>Sun Sign *</label>
         <?php echo $this->Form->select('sun_sign_id', $sunSignOptions, ['class' => 'form-control validate[required]', 'empty' => 'Select Sunsign'] ); ?>
        </div>
        
      </div>

       <div class="col-md-3">
        <div class="form-group">
         <div class="row">
          <label>Schedule Date</label><br />
          <?php echo $this->Form->text('schedule_date', ['class' => 'form-control pull-right validate[required]', 'id' => 'datepicker-1', 'autocomplete'=>false] ); ?>
          </div>
<br>
          <div class="row">
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

       <div class="col-md-2">
        <div class="form-group">
          <label>Language</label><br />
          <?php 
                              $languageOptions = [
                                      'en' => 'English' ,
                                      'dk' => 'Danish' ,

                                     ];
                     echo $this->Form->select('language', $languageOptions, ['class' => 'form-control validate[required]'] ); ?>
        </div>
      
      </div>



 <div class="col-md-2">
        <div class="form-group">
         <br />
         <div class="box-tools pull-right">
       <?php echo $this->Form->button(__('Submit'), ['class' => 'btn btn-primary btn-block btn-flat']); ?>
    </div>
        </div>
      
      </div>

      <!-- /.col -->
    </div>
    <!-- /.row -->

  </div>
  <?php echo $this->Form->end() ?>
  <!-- /.box-body -->
<?php 
 if(isset($prediction) && !empty($prediction))
 {


?>
 <table class="table table-bordered">
          <tr>
            <th class="sno">#</th>
            <th>Date</th>
            <th>Sign</th>
            <th>Action</th>
          </tr>

          <?php 
              $i = 1;
              $id = $prediction['id'];
          ?>    
            <tr>
              <td><?php echo $i; ?></td>
              <td><?php echo date('Y-m-d',strtotime($prediction['schedule_date'])); ?></td>
              <td><?php echo $prediction['sun_sign']['name']?></td>
              <td>
                <a class="btn btn-info btn-xs" href="<?php echo $this->Url->build(['controller' => 'SunSignPredictions', 'action' => 'edit/'.$id])?>"><i class="glyphicon glyphicon-edit"></i>
                </a>
              </td>
            </tr>
          
       
       
        </table>

<?php
}


?>




</div>
<!-- /.box -->