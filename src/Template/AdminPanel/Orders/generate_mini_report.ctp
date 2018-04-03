<!--SELECT2 EXAMPLE -->
<?php use Cake\Routing\Router; ?>
<?php $url = Router::url('/', true); ?>
<?php //pr($this->request->params['prefix']); die; ?>
<div class="box box-default">
  <div class="box-header with-border">
    <div class="row">
      <div class="col-md-8">
        <h3 class="box-title">Generate free mini report</h3>
      </div>
    </div>
  </div>
  <?= $this->Flash->render() ?>
  <?php echo $this->Form->create($form,['url' => Router::url('/orders/mini-report-order-by-admin', true), 'id' => 'form_id', 'type' => 'post']) ?>
    <!-- /.box-header -->
    <div class="box-body">

      <?php
        echo $this->Form->hidden('price', ['value' => '0.00']);
        echo $this->Form->hidden('user_id', ['value' => '0']);
        echo $this->Form->hidden('delivery_option', ['value' => '1']);
        //echo $this->Form->hidden('order_date', ['value' => date('Y-m-d H:i:s')]);
        //echo $this->Form->hidden('confirm_payment_date', ['value' => date('Y-m-d H:i:s')]);
        echo $this->Form->hidden('product_type', ['value' => 12]);
        echo $this->Form->hidden('currency_id', ['value' => 1]);
        echo $this->Form->hidden('shipping_charge', ['value' => 0.00]);
        echo $this->Form->hidden('portal_id', ['value' => 2]);
      ?>

      <div class="row">
        <!-- /.col -->
        <div class="col-md-2"></div>
        <div class="col-md-4">
          <div class="form-group">
            <label>First Name * </label>
            <?= $this->Form->input('first_name', ['class' => 'form-control validate[required]', 'placeholder' => 'First Name', 'label' => false]); ?>
          </div>
        </div>

        <div class="col-md-4">
          <div class="form-group">
            <label>Last Name </label>
            <?= $this->Form->input('last_name', ['class' => 'form-control', 'placeholder' => 'Last Name', 'label' => false]); ?>
          </div>
        </div>
        <div class="col-md-2"></div>
      </div>


      <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-4">
          <div class="form-group">
            <label>Mini Report * </label>
            <?= $this->Form->select('product_id', $productsList, ['class' => 'form-control select2 validate[required]', 'id' => 'product_id', 'empty' => __('Select Mini Report'), 'label' => false] ); ?>
          </div>
        </div>

        <div class="col-md-4">
          <div class="form-group">
            <label>Language * </label>
            <?php $languages = ['1' => 'English', '2' => 'Danish']; ?>
            <?= $this->Form->select('language_id', $languages, ['class' => 'form-control select2 validate[required]', 'id' => 'language_id', 'empty' => __('Select Language'), 'label' => false] ); ?>
          </div>
        </div>
        <div class="col-md-2"></div>
      </div>

      <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-4">

          <div class="form-group">
            <label>Date of Birth * </label>
            <div class="input-group date">
              <div class="input-group-addon">
                <i class="fa fa-calendar"></i>
              </div>
              <?= $this->Form->text('datepicker', ['class' => 'form-control pull-right validate[required]', 'id' => 'datepicker'/*, 'value' => $defaultDob*/] ); ?>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="bootstrap-timepicker">
            <div class="form-group">
              <label>Birth Time * </label>
              <div class="input-group">
               <div class="input-group-addon">
                  <i class="fa fa-clock-o"></i>
                </div>
                <?php echo $this->Form->text('timepicker', ['class' => 'form-control pull-right', 'id' => 'timepicker'/*, 'value' => $data['birth_detail']['time']*/ ])?>
              </div>
              <!-- /.input group -->
            </div>
            <!-- /.form group -->
          </div>
        </div>
        <div class="col-md-2"></div>
      </div>


      <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-4">
          <div class="form-group">
            <label>Email * </label>
            <?= $this->Form->input('email', ['class' => 'form-control validate[required, custom[email]]', 'placeholder' => 'Email Address', 'label' => false]); ?>
          </div>
        </div>
        <?php /*
        <div class="col-md-4">
          <div class="form-group">
            <label>Gender * </label>
            <?php $options = ['M' => __('Male'), 'F' => __('Female')]; ?>
            <?= $this->Form->select('gender', $options, ['class' => 'form-control select2 validate[required]', 'empty' => __('Select Gender'), 'label' => false] ); ?>
          </div>
        </div>
         */ ?>
        <div class="col-md-6"></div>
      </div>

      <?= $this->Form->hidden('country_id', ['value' => 33]); ?>
      <?= $this->Form->hidden('city_id', ['value' => 95142]); ?>
      <?= $this->Form->hidden('gender', ['value' => 'M']); ?>


      <?php /*
      <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-4">
          <div class="form-group">
            <label>Birth Country *</label>
            <?= $this->Form->select('country_id', $countryOptions, ['class' => 'form-control validate[required] select2', 'id' => 'country_id', 'empty'=>'Select Country' , 'style' => 'width:490px', 'data-prompt-position' => 'topRight:360,0', 'value' => $data['birth_detail']['country_id']]); ?>
          </div>
        </div>

        <div class="col-md-4">
          <div class="bootstrap-timepicker">
            <div class="form-group">
              <label>Birth City *</label>
              <?= $this->Form->select('city_id', $birthCitiesOptions, ['class' => 'form-control validate[required] select2', 'id' => 'city_id', 'style' => 'width:500px', 'empty' => 'Select City', 'value' => $data['birth_detail']['city_id']]); ?>
            </div>
            <!-- /.form group -->
          </div>
        </div>
        <div class="col-md-2"></div>
      </div>
      */ ?>

      <div class="row">
        <div class="col-md-5"></div>
        <div class="col-md-2">
          <?= $this->Form->button(__('Submit'), ['class' => 'btn btn-primary btn-block btn-flat']); ?>
        </div>
        <div class="col-md-5"></div>
      </div>
      <!-- /.row -->
      <!-- end-->
    </div>
  <?php echo $this->Form->end() ?>
</div>
<!-- /.box -->

<script type="text/javascript">
  $('#product_id').on('change', function(){
    if($(this).val() == 82) {
      $("#language_id option[value='2']").remove();
    } else {
      if($("#language_id option[value='2']").length == 0) {
        var newOption = "<option value='"+"2"+"'>Danish</option>"; 
        $("#language_id").append(newOption);
      }
    }
  });
</script>