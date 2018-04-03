<?php
  $fname = $lname = $profile = $website = $descriptionEnglish = $descriptionDanish = $product = '';
  // For edit functionality
  if (isset($form) && !empty($form) && isset($daform) && !empty($daform)) {
    $this->request->session()->write('testimonial-data.first_name', stripslashes($form['first_name']));
    $this->request->session()->write('testimonial-data.last_name', stripslashes($form['last_name']));
    $this->request->session()->write('testimonial-data.user_profile', stripslashes($form['user_profile']));
    $this->request->session()->write('testimonial-data.website', stripslashes($form['website']));
    $this->request->session()->write('testimonial-data.product_id', $form['product_id']);
    $this->request->session()->write('testimonial-data.content', stripslashes($form['content']));
    $this->request->session()->write('testimonial-data._translations.da.content', stripslashes($daform['content']));
  }

  if (!empty($this->request->session()->read('testimonial-data'))) {
    $fname = stripslashes($this->request->session()->read('testimonial-data.first_name'));
    $lname = stripslashes($this->request->session()->read('testimonial-data.last_name'));
    $profile = stripslashes($this->request->session()->read('testimonial-data.user_profile'));
    $website = stripslashes($this->request->session()->read('testimonial-data.website'));
    $product = $this->request->session()->read('testimonial-data.product_id');
    $descriptionEnglish = stripslashes($this->request->session()->read('testimonial-data.content'));
    $descriptionDanish = stripslashes($this->request->session()->read('testimonial-data._translations.da.content'));
  }
?>
<div class="box box-default">
  <div class="box-header with-border">
    <h3 class="box-title">User Testimonials</h3>

    <div class="box-tools pull-right">
      <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
      <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
    </div>
  </div>
  <?php echo $this->Form->create($form, ['id' => 'form_id', 'novalidate' => true, 'enctype' => 'multipart/form-data']) ?>
    <!-- /.box-header -->
    <div class="box-body">
      <div class="row">
        <div class="col-md-12">
          <!-- Custom Tabs -->
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#tab_1" data-toggle="tab">English</a></li>
              <li><a href="#tab_2" data-toggle="tab">Dansk</a></li>
              <li><a href="#tab_3" data-toggle="tab">Details</a></li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_1">
                <div class="row">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label>Description *</label>
                        <?php echo $this->Form->textarea('content', ['class' => 'form-control html_editor validate[required]', 'maxlength' => 500, 'value' => $descriptionEnglish]) ?>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="tab_2">
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>Description *</label>
                      <?php echo $this->Form->textarea('_translations.da.content', ['class' => 'form-control html_editor validate[required]', 'maxlength' => 500, 'value' => $descriptionDanish]) ?>
                    </div>
                  </div>
                </div>
              </div>

              <div class="tab-pane" id="tab_3">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>First Name *</label>
                      <?php echo $this->Form->text('first_name', ['class' => 'form-control validate[required]', 'maxlength' => 255, 'value' => $fname]) ?>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Last Name </label>
                      <?php echo $this->Form->text('last_name', ['class' => 'form-control', 'maxlength' => 255, 'value' => $lname]) ?>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Profile </label>
                      <?php echo $this->Form->text('user_profile', ['class' => 'form-control', 'maxlength' => 255, 'value' => $profile]) ?>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Website </label>
                      <?php echo $this->Form->text('website', ['class' => 'form-control', 'id' => 'siteUrl', 'onkeyup' => 'myFunction(this);', 'maxlength' => 255, 'value' => $website, 'placeholder' => 'http://abc.com']) ?>
                      <div id='urlMessage' style="display: none;"></div>
                    </div>
                  </div>

                  <div class="col-md-12">
                    <div class="form-group">
                      <label>Product *</label>
                      <?php echo $this->Form->select('product_id', $productArr, ['class' => 'form-control validate[required]', 'empty' => 'Select Product', 'value' => $product]) ?>
                    </div>
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
    </div>
  <?php echo $this->Form->end() ?>
  <!-- /.box-body -->
 
</div>
<!-- /.box -->



<script type="text/javascript">
  function myFunction(value) {
    if(isUrlValid($('#siteUrl').val())) {
      $('#urlMessage').html('Valide Url');
      $('#urlMessage').css('display', 'block');
      $('#urlMessage').css('color', 'green');
    } else {
      $('#urlMessage').html('Invalide Url');
      $('#urlMessage').css('display', 'block');
      $('#urlMessage').css('color', 'red');
    }
  }

  function isUrlValid(url) {
    return /^(https?|s?ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(url);
  }
</script>