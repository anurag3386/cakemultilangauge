<div class="box box-default">
  <div class="box-header with-border">
    <h3 class="box-title">Category</h3>
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

       <div class="col-md-4">
        <div class="form-group">
          <label>Name * (Dansk)</label>
          <?php echo $this->Form->text('_translations.da.name', ['class' => 'form-control', 'maxlength' => 50, 'value' => $data['_translations']['da']['name'] ]) ?>
        </div>
      
      </div>


      <!-- /.col -->
      <div class="col-md-2">
       
       <div class="form-group">
          <label>Status</label><br />
          <?php 
          $options = array('1' => 'Active', '2' => 'Inactive');
          $attributes = array('value' => $data['status'], 'class' => 'form-control');
          echo $this->Form->select('status', $options, $attributes); ?>
        </div>
        
      </div>


      <div class="col-md-2">
        <div class="form-group">
          <label>Sort Order *</label><br />
           <?php echo $this->Form->text('sort_order', ['class' => 'form-control validate[required, custom[onlyNumberSp]]', 'maxlength' => 3, 'value' => $data['sort_order']]) ?>
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