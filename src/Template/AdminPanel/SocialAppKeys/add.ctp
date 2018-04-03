<!-- SELECT2 EXAMPLE -->
<div class="box box-default">
  <div class="box-header with-border">
    <h3 class="box-title">Social App Keys</h3>
  </div>
  <?php echo $this->Form->create($form, ['id' => 'form_id', "novalidate" => "novalidate"]) ?>
  <!-- /.box-header -->
  <div class="box-body">

    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label>Name *</label>
          <?php echo $this->Form->text('name', ['class' => 'form-control validate[required]', 'maxlength' => 50]) ?>
        </div>

      </div>


      <div class="col-md-3">
        <div class="form-group">
          <label>Sort Order</label>
          <?php echo $this->Form->text('sort_order', ['class' => 'form-control validate[required]', 'maxlength' => 4]) ?>
        </div>

      </div>
      <div class="col-md-3">
        <div class="form-group">
          <label>Status</label><br />
          <?php
          $options = ['1' => 'Active', '2' => 'Inactive'];
          echo $this->Form->select('status', $options, ['class' => 'form-control']) ?>
        </div>

      </div>

    </div>

    <!-- /.row -->
    <div class="row">
     <div class="col-md-6">
      <div class="form-group">
        <label>App Id *</label>
        <?php echo $this->Form->text('app_key', ['class' => 'form-control validate[required]', 'maxlength' => 255]) ?>
      </div>
      
    </div> 

    <div class="col-md-6">
      <div class="form-group">
        <label>App Secret *</label>
        <?php echo $this->Form->text('app_secret', ['class' => 'form-control validate[required]', 'maxlength' => 255]) ?>
      </div>
      
    </div>



  </div>
  <div class="row">

    <div class="col-md-6">
      <div class="form-group">
        <label>OAUTH TOKEN </label>
        <?php echo $this->Form->text('oauth_token', ['class' => 'form-control', 'maxlength' => 255])?>
      </div>

    </div>

    <div class="col-md-6">
      <div class="form-group">
        <label>OAUTH SECRET </label>
        <?php echo $this->Form->text('oauth_secret', ['class' => 'form-control', 'maxlength' => 255])?>
      </div>

    </div>
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