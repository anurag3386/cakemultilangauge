<!-- SELECT2 EXAMPLE -->
<div class="box box-default">
  <div class="box-header with-border">
    <h3 class="box-title">Astrologer</h3>
        <div class="box-tools pull-right">
      <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
      <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
    </div>
  </div>
  <?php echo $this->Form->create($form, ['id' => 'form_id', 'enctype' => 'multipart/form-data']) ?>
  <?php echo $this->Form->hidden('role', ['value' => 'astrologer'])?>
  <!-- /.box-header -->
  <div class="box-body">

    <div class="row">
      <div class="col-md-4">
        <div class="form-group">
          <label>Username (primary email)*</label>
          <?php echo $this->Form->email('username', ['class' => 'form-control validate[required, custom[email]]', 'maxlength' => 200, 'value' => $data['username']]) ?>
        </div>
      
      </div>
      <!-- /.col -->
      <div class="col-md-4">
        <div class="form-group">
          <label>Password 
          <span style="font-weight:normal; font-size:11px;">(leave empty if not to be updated)</span></label>
         <?php echo $this->Form->password('password', ['class' => 'form-control', 'maxlength' => 15])?>
        </div>
        
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->

     <div class="row">
      <div class="col-md-4">
        <div class="form-group">
          <label>First Name *</label>
          <?php echo $this->Form->text('astrologer.first_name', ['class' => 'form-control validate[required]', 'maxlength' => 50, 'value' => $data['astrologer']['first_name']]) ?>
        </div>
      
      </div>
      <!-- /.col -->
      <div class="col-md-4">
        <div class="form-group">
          <label>Last Name *</label>
         <?php echo $this->Form->text('astrologer.last_name', ['class' => 'form-control validate[required]', 'maxlength' => 50, 'value' => $data['astrologer']['last_name']])?>
        </div>
      </div>

       <div class="col-md-3">
          <div class="form-group">
          <label>Gender</label><br />
          <?php
           $options = ['M' => 'Male', 'F' => 'Female'];
           echo $this->form->select('astrologer.gender', $options, ['class' => 'form-control validate[required]', 'empty' => 'Select Gender', 'value' => $data['astrologer']['gender'] ]); ?>
        </div>      
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->


     <div class="row">
      <div class="col-md-4">
        <div class="form-group">
          <label>Alternate Email *</label>
          <?php echo $this->Form->email('astrologer.email', ['class' => 'form-control validate[required, custom[email]]', 'maxlength' => 200, 'value' => $data['astrologer']['email'] ]) ?>
        </div>
      
      </div>
      <!-- /.col -->
      <div class="col-md-4">
        <div class="form-group">
          <label>Phone *</label>
         <?php echo $this->Form->tel('astrologer.phone', ['class' => 'form-control validate[required, custom[phone]]', 'maxlength' => 20, 'value' => $data['astrologer']['phone'] ])?>
        </div>
      </div>

      <div class="col-md-4">
        <div class="form-group">
          <label>Languages *</label>
         <?php echo $this->Form->select('astrologer.languages[]', $languageOptions, ['class' => 'form-control validate[required] select2', 'multiple', 'data-prompt-position' => 'topright:190,0', 'value' => unserialize($data['astrologer']['languages']) ])?>
        </div>
      </div>

      <!-- /.col -->
    </div>
    <!-- /.row -->


  
     <div class="row">
      <div class="col-md-4">
        <div class="form-group">
          <label>Address *</label>
          <?php echo $this->Form->textarea('astrologer.address', ['class' => 'form-control validate[required]', 'maxlength' => 500, 'value' => $data['astrologer']['address'] ]) ?>
        </div>
      
      </div>
      <!-- /.col -->
      <div class="col-md-4">
        <div class="form-group">
          <label>City *</label>
         <?php echo $this->Form->text('astrologer.city', ['class' => 'form-control validate[required]', 'maxlength' => 200, 'value' => $data['astrologer']['city']])?>
        </div>

        <div class="form-group">
          <label>State *</label>
         <?php echo $this->Form->text('astrologer.state', ['class' => 'form-control validate[required]', 'maxlength' => 200, 'value' => $data['astrologer']['state']])?>
        </div>

      </div>

       <div class="col-md-4">
         <div class="form-group">
          <label>Country *</label>
          <?php echo $this->Form->select('astrologer.country_id', $countryOptions, ['class' => 'form-control select2 validate[required]', 'empty' => 'Select Country', 'data-prompt-position' => 'topRight:190,0', 'value' => $data['astrologer']['country_id'] ]) ?>
        </div> 

        <div class="form-group">
          <label>Timezone *</label>
          <?php echo $this->Form->select('astrologer.timezone_id', $timezoneOptions, ['class' => 'form-control select2 validate[required]', 'empty' => 'Select Timezone', 'data-prompt-position' => 'topRight:190,0', 'value' => $data['astrologer']['timezone_id']]) ?>
        </div>   

      </div>

      <!-- /.col -->
    </div>
    <!-- /.row -->



      <div class="row">
      <div class="col-md-4">
        <div class="form-group">
          <label>Biography *</label>
          <?php echo $this->Form->textarea('astrologer.biography', ['class' => 'form-control validate[required]', 'value' => $data['astrologer']['biography']]) ?>
        </div>
      
      </div>
      <!-- /.col -->
      <div class="col-md-4">
       <div class="form-group">
          <label>Preferred Mode of Contact *</label>
         <?php
         $options = ['email' => 'Email', 'phone' => 'Phone'];
          echo $this->Form->select('astrologer.mode', $options, ['class' => 'form-control validate[required]' , 'value' => $data['astrologer']['mode']])?>
        </div>

         <div class="form-group">
          <label>On Vacation *</label>
         <?php
         $options = ['1' => 'No', '2' => 'Yes'];
          echo $this->Form->select('astrologer.on_vacation', $options, ['class' => 'form-control', 'value' => $data['astrologer']['on_vacation']])?>
        </div>

      </div>

       <div class="col-md-4">


        <div class="form-group">
          <label>Image *</label>
         <?php echo $this->Form->file('astrologer.image', ['class' => 'form-control'])?>
         <?php 
          if($data['astrologer']['image']) {
             $img_path = $this->request->webroot.'uploads'.DS.'profiles'.DS.'thumb'.DS.'sm'.DS;
             echo '<br /><img src="'.$img_path.$data['astrologer']['image'].'" /> <i class="fa fa-trash" aria-hidden="true" 
             onclick="javascript:deleteImage()"></i>';
          }   
          echo $this->Form->hidden('astrologer.prev_image', ['value' => $data['astrologer']['image']]);
          echo $this->Form->hidden('astrologer.delete_image', ['value' => 0]);
        ?>
        </div>


        <div class="form-group">
          <label>Status</label><br />
          <?php
          $options = ['1' => 'Active', '2' => 'Inactive'];
           echo $this->Form->select('status', $options, ['class' => 'form-control', 'value' => $data['status'] ]); ?>
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