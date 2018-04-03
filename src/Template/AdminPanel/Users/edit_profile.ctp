<div class="box box-default">
  <div class="box-header with-border">
    <h3 class="box-title">Edit Profile</h3>
  </div>
  <?php echo $this->Form->create($form, ['id' => 'form_id']) ?>
  <!-- /.box-header -->
  <div class="box-body">

    <div class="row">
      <div class="col-md-4">
        <div class="form-group">
          <label>Username *</label>
          <?php echo $this->Form->text('username', ['class' => 'form-control validate[required, custom[email]]', 'maxlength' => 200, 'value' => $data['username'] ]) ?>
        </div>
      
      </div>

       <div class="col-md-4">
        <div class="form-group">
          <label>First Name *</label>
          <?php echo $this->Form->text('profile.first_name', ['class' => 'form-control validate[required]', 'maxlength' => 50, 'value' => $data['profile']['first_name']]) ?>
        </div>
      
      </div>

      <!-- /.col -->
      <div class="col-md-4">
        <div class="form-group">
          <label>Last Name *</label>
         <?php echo $this->Form->text('profile.last_name', ['class' => 'form-control validate[required]', 'maxlength' => 50, 'value' => $data['profile']['last_name']] )?>
        </div>
        
      </div>
      <!-- /.col -->
    </div>


     <div class="row">
      <div class="col-md-4">
        <div class="form-group">
          <label>Old Password *</label>
          <?php echo $this->Form->password('old_pass', ['class' => 'form-control validate[required]', 'maxlength' => 20 ]) ?>
        </div>
      
      </div>

       <div class="col-md-4">
        <div class="form-group">
          <label>New Password </label>
          <?php echo $this->Form->password('new_pass', ['class' => 'form-control validate[minSize[6]]', 'maxlength' => 20, 'id' => 'new_pass']) ?>
        </div>
      
      </div>

      <!-- /.col -->
      <div class="col-md-4">
        <div class="form-group">
          <label>Confirm Password </label>
         <?php echo $this->Form->password('new_cpass', ['class' => 'form-control validate[equals[new_pass]]', 'maxlength' => 20] )?>
        </div>
        
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

<!-- /.box -->
