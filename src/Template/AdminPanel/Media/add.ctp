<!-- SELECT2 EXAMPLE -->
<div class="box box-default">
  <div class="box-header with-border">
    <h3 class="box-title">Media</h3>
  </div>
<?php echo $this->Form->create($form, ['id' => 'form_id', 'type' => 'file']) ?>  <!-- /.box-header -->
  <div class="box-body">

    <div class="row">

     <div class="col-md-3">
        <div class="form-group">
          <label>Category *</label>
          <?php echo $this->Form->select('category_id', $categoryOptions , ['class' => 'form-control validate[required]', 'empty' => 'Select Category', 'onChange' => 'changeCategory(this.value)']); ?>
        </div>
      
      </div>

      <div class="col-md-3">
        <div class="form-group">
          <label>Name *</label>
          <?php echo $this->Form->text('name', ['class' => 'form-control validate[required]', 'maxlength' => 50]) ?>
        </div>
      
      </div>

   <div class="col-md-3">
        <div class="form-group">
          <label>Name * (Dansk)</label>
          <?php echo $this->Form->text('_translations.da.name', ['class' => 'form-control  validate[required]', 'maxlength' => 50]) ?>
        </div>
      
      </div>

     
      <!-- /.col -->
      <div class="col-md-2">
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
    
        <div class="col-md-3" id="path" style="display:none">
        <div class="form-group">
          <label>Path *</label>
          <div id="src">
          <?php //echo $this->Form->text('path', ['class' => 'form-control validate[required]', 'maxlength' => 200, 'placeholder' => 'Enter youtube video id']) ?>
          </div>
          
        </div>
      
      </div>

      <!--  <div class="col-md-3" style="display: none" id="audio">

         
                    <div class="form-group">
                      <label>Source *</label>
                      <?php //echo $this->Form->file('path', ['class' => 'form-control validate[required]']) ?>
                    </div>
                  </div> -->


      <div class="col-md-2">
        <div class="form-group">
          <label>Sort Order *</label><br />
           <?php echo $this->Form->text('sort_order', ['class' => 'form-control validate[required, custom[onlyNumberSp]]', 'maxlength' => 3, 'value' => 0]) ?>
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
<script>
/* This function is used to change category of media module*/
// function changeCategory(category)
// {
//   $("#path").show();
//   if(category == 25)
//   {
//     $("#src").html("<input name='path' class='form-control validate[required]' maxlength='200' placeholder='Enter youtube video id' type='text'/>");
    
//   }
//   else if(category == 24)
//   {
//       $("#src").html("<input name='path' class='form-control validate[required]' type='file'/>");
    
//   }
//   else
//   {
//       $("#path").hide();
//   }
// }
</script>