<!-- SELECT2 EXAMPLE -->
<div class="box box-default">
  <div class="box-header with-border">
    <h3 class="box-title">Settings</h3>
  </div>
  <?php echo $this->Form->create($form, ['id' => 'form_id']) ?>
  <!-- /.box-header -->
  <div class="box-body">

    <div class="row">
      <div class="col-md-4">
        <div class="form-group">
          <label>Key *</label>
          <?php echo $this->Form->text('field_key', ['class' => 'form-control validate[required]', 'maxlength' => 255]) ?>
        </div>
      
      </div>

     <div class="col-md-8">
        <div class="form-group">
          <label>Value *</label>
          <?php echo $this->Form->text('field_value', ['class' => 'form-control  validate[required]', 'maxlength' => 255]) ?>
        </div>
      
      </div>

      <!-- /.col -->
    </div>
    <!-- /.row -->


    <div class="box-tools pull-right">
       <?php echo $this->Form->button(__('Submit'), ['class' => 'btn btn-primary btn-block btn-flat']); ?>
    </div>

  </div>
  <?php echo $this->Form->end() ?>
  <!-- /.box-body -->
 
</div>
<!-- /.box -->