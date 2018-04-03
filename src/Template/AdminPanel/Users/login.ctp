<?php echo $this->Form->create() ?>

  <div class="form-group has-feedback">
  	<?php echo $this->Form->text('username', ['class' => 'form-control', 'placeholder' => 'Username']) ?>
    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
  </div>
  <div class="form-group has-feedback">
  	<?php echo $this->Form->password('password', ['class' => 'form-control', 'placeholder' => 'Password']) ?>	
    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
  </div>
  <div class="row">
    <div class="col-xs-8">
     
    </div>
    <!-- /.col -->
    <div class="col-xs-4">
      <?php echo $this->Form->button(__('Sign In'), ['class' => 'btn btn-primary btn-block btn-flat']); ?>
    </div>
    <!-- /.col -->
  </div>
<?php echo $this->Form->end() ?>