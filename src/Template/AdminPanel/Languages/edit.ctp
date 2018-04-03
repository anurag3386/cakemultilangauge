<div class="box box-default">
  <div class="box-header with-border">
    <h3 class="box-title">Language</h3>
  </div>
  <?php echo $this->Form->create($form, ['id' => 'form_id']) ?>
  <!-- /.box-header -->
  <div class="box-body">

    <div class="row">
      <div class="col-md-4">
        <div class="form-group">
          <label>Name *</label>
          <?php echo $this->Form->text('name', ['class' => 'form-control validate[required]', 'maxlength' => 50, 'value' => $data['name']]) ?>
        </div>
      
      </div>
      <!-- /.col -->
      <div class="col-md-2">
        <div class="form-group">
          <label>Code *</label>
         <?php echo $this->Form->text('code', ['class' => 'form-control validate[required]', 'maxlength' => 5, 'value' => $data['code']] )?>
        </div>
        
      </div>

       <div class="col-md-2">
        <div class="form-group">
          <label>Status</label><br />
          <?php 
          $options = array('1' => 'Active', '2' => 'Inactive');
          $attributes = array('value' => $data['status'], 'class' => 'form-control');
          echo $this->Form->select('status', $options, $attributes); ?>
        </div>
      
      </div>
      
      <!-- /.col -->
      <div class="col-md-2">
        <div class="form-group">
          <label>Language Category</label><br />
          <?php 
          $options = ['0' => 'Website', '1' => 'Product', '2' => 'Both'];
          echo $this->Form->select('language_category', $options, ['class' => 'form-control validate[required]', 'value' => $data['language_category']]); ?>
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