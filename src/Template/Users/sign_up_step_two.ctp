<?php use Cake\Routing\Router; ?>
<?php use Cake\Cache\Cache; ?>
<?php use Cake\I18n\I18n; ?>
<?php
   $currentBirthData = $this->request->session()->read('birthData');
   $currentHours     = (!empty($currentBirthData['hours']))? $currentBirthData['hours'] : '';
   $currentMinutes   = (!empty($currentBirthData['minutes']))? $currentBirthData['minutes'] : '';
   $currentCountry   = (!empty($currentBirthData['birth_detail']['country_id']))? $currentBirthData['birth_detail']['country_id'] : '';
   $currentCityId    = !empty($currentBirthData['birth_detail']['city_id']) ? $currentBirthData['birth_detail']['city_id'] : '';

   
   if(!empty($currentBirthData['birth_date']))
   {
     $currentBirthDate = $currentBirthData['birth_date'];
   }
   else
   {
     $currentBirthDate = $this->request->session()->read('dob');
   }

    if( !empty($currentBirthData['city']) )
    {
      $currentCity = (strpos($currentBirthData['city'], '[') != false) ? $currentBirthData['city'] : ''; 
    }
    else
    {
      $currentCity = '';
    }



?>
<article id="post-1154" class="post-1154 page type-page status-publish hentry">

  <div class="entry-content">
    <div class="et_pb_section  et_pb_section_0 et_section_regular">
      <div class=" et_pb_row et_pb_row_0">
        <div class="et_pb_column et_pb_column_4_4  et_pb_column_0">
          <div class="et_pb_text et_pb_module et_pb_bg_layout_light et_pb_text_align_left  et_pb_text_0">
            <h1><?= __('Sign Up');?></h1>
          </div> <!-- .et_pb_text --> 
        </div> <!-- .et_pb_column --> 
      </div>
      <!-- .et_pb_row -->
      <div class=" et_pb_row et_pb_row_1">
        <div class="et_pb_column et_pb_column_4_4  et_pb_column_1">
          <div class="et_pb_code et_pb_module  et_pb_code_0">
            <div class="et_pb_row et_pb_row_1" style="width: 100%; padding: 0px;">
              <div class="et_pb_column et_pb_column_1_3  et_pb_column_1">
                <div class="et_pb_text et_pb_module et_pb_bg_layout_light et_pb_text_align_left et_pb_text_1">
                  <div class="signup_well">
                    <p><?= __('Sign up and get your free personalized report as your welcome gift.');?></p>
                    <p><cite><?= __('In this report you will get:')?></cite></p>
                    <ul class="checklist">
                      <li><?= __('Personal horoscope');?></li>
                      <li><?= __('Daily personalized astrological calendar');?></li>
                      <li><?= __('Major influences');?></li>
                    </ul>
                  </div>
                </div> <!-- .et_pb_text --> 
              </div> <!-- .et_pb_column -->

              <div class="et_pb_column et_pb_column_2_3  et_pb_column_2 signup_form_wrapper">
                <div class="et_pb_text et_pb_module et_pb_bg_layout_light et_pb_text_align_left  et_pb_text_2">
                  <p> <?php echo $this->Html->image('sign_up_2.png',['alt'=>'', 'class'=>'alignleft size-full wp-image-364', 'width'=>'787','height'=>'74'])?> </p>
                  <div class="signup_1 "><?= __('Step 1');?></div>
                  <div class="signup_2 signup_active"><?= __('Step 2');?></div>
                  <div id="signup_1_wrapper">
                  <span class="text_center" id="flash_message">
                  <?php echo $this->Flash->render(); ?>
                  </span>
                    <h3><?= __('Birth Information');?></h3>
                    <?php echo $this->Form->create(null, ['url' => [ 'controller' => 'users','action' => 'sign-up-step-two' ], 'id'=>'frmRegStepTwo', 'class'=>'checkout_form', 'name'=>'frmRegStepTwo'])?>
                      <div class="alignleft">
                        <label for=""><?= __('Birth Date');?><span style='color:red'> *</span></label>
                        <br>
                        <?php echo $this->Form->text('birth_date', ['class' => 'common-calendar pull-right validate[required]', 'id' => 'signup-datepicker' , 'autocomplete' => 'off' , 'readonly' => 'readonly', 'default' => $currentBirthDate] ); ?>
                        <br>
                        <label for=""><?= __('Birth Time');?><span style='color:red'> *</span></label>
                        <br>
                       <?php   $hourOtions = [
                        '-1' => __('Unknown Time'),
                        '00' => '00 a.m. (00.00)',                '01' => '01 a.m. (01.00)',
                        '02' => '02 a.m. (02.00)',                '03' => '03 a.m. (03.00)',
                        '04' => '04 a.m. (04.00)',                '05' => '05 a.m. (05.00)',
                        '06' => '06 a.m. (06.00)',                '07' => '07 a.m. (07.00)',
                        '08' => '08 a.m. (08.00)',                '09' => '09 a.m. (09.00)',
                        '10' => '10 a.m. (10.00)',                '11' => '11 a.m. (11.00)',
                        '12' => '12 p.m. (12.00)',                '13' => '01 p.m. (13.00)',
                        '14' => '02 p.m. (14.00)',                '15' => '03 p.m. (15.00)',
                        '16' => '04 p.m. (16.00)',                '17' => '05 p.m. (17.00)',
                        '18' => '06 p.m. (18.00)',                '19' => '07 p.m. (19.00)',
                        '20' => '08 p.m. (20.00)',                '21' => '09 p.m. (21.00)',
                        '22' => '10 p.m. (22.00)',                '23' => '11 p.m. (23.00)'

                        ]; ?>
                      <?php echo $this->Form->select('hours', $hourOtions, ['id' => 'hours' , 'class' => 'validate[required] hour down' , 'empty' => __('Hour'), 'default' => $currentHours]); ?>
                      <?php $minOptions=[
                             '-1' => __('Unknown Time'),'00' => '00','01' => '01','02' => '02','03' => '03','04' => '04','05' => '05', '06' => '06','07' => '07','08' => '08','09' => '09','10' => '10', '11' => '11', '12' => '12','13' => '13',
                               '14' => '14', '15' => '15', '16' => '16', '17' => '17', '18' => '18', '19' => '19',
                               '20' => '20', '21' => '21', '22' => '22', '23' => '23', '24' => '24', '25' => '25',
                               '26' => '26', '27' => '27', '28' => '28', '29' => '29', '30' => '30', '31' => '31',
                               '32' => '32', '33' => '33', '34' => '34', '35' => '35', '36' => '36', '37' => '37',
                               '38' => '38', '39' => '39', '40' => '40', '41' => '41', '42' => '42', '43' => '43',
                               '44' => '44', '45' => '45', '46' => '46', '47' => '47', '48' => '48', '49' => '49',
                               '50' => '50', '51' => '51', '52' => '52', '53' => '53', '54' => '54', '55' => '55',
                               '56' => '56', '57' => '57', '58' => '58', '59' => '59'] ;?>
                      <?php echo $this->Form->select('minutes' , $minOptions , ['id' => 'minutes' , 'class' => ' validate[required] minute down' , 'empty' => __('Minute'), 'default' => $currentMinutes])?>
                      <br>
                      <?php //echo $this->Form->checkbox('subscription.special_offers', ['hiddenField' => false ,  'value' => 1 ]); ?>
                    <!--   <span>< ?= __('Send me special offers');?></span> -->
                    </div>
                    <div class="alignright">
                      <label for=""><?= __('Birth Country');?><span style='color:red'> *</span></label>
                      <br>
                      <?php echo $this->Form->select('birth_detail.country_id', $countryOptions, ['id' => 'country',  'class' => 'validate[required] down', 'empty' => __('Select Country'), 'onChange' => "document.getElementById('search-box').value = ''" , 'default' => $currentCountry] ); ?>
                        <br>
                        <label for=""><?= __('Birth City');?><span style='color:red'> *</span></label>
                        <br>
                        <?php echo $this->Form->text( 'city', ['id' => 'search-box', 'placeholder' => __('Enter City'), 'autocomplete' => 'off', 'class' => ' validate[required] ui-autocomplete-input', 'default' => $currentCity])?>
                        <div id="loading" style="display:none">
                          <?php echo $this->Html->image('calendar-loading.gif');?>
                        </div>
                        <div id="suggesstion-box"></div>
                        <br>
                        <?php echo $this->Form->hidden('birth_detail.city_id', ['id' => 'city_id', 'default' => $currentCityId]);  ?>
                        <br>
                        <em><?= __('(Enter first few letters of birth town and CLICK on town in list)');?></em> <br>
                      </div>
                      <!-- From end here-->
                  </div>
                </div>
                <!-- .et_pb_text --> 
              </div>
              <!-- .et_pb_column --> 
            </div>
            <!-- .et_pb_row -->
            <div class=" et_pb_row et_pb_row_2" style="width: 100%; padding: 0px;">
              <div class="et_pb_column et_pb_column_4_4  et_pb_column_3">
                <div class="et_pb_text et_pb_module et_pb_bg_layout_light et_pb_text_align_left  et_pb_text_3">
                  <hr class="divider">
                  <?php 
                       $user_id = $this->request->session()->read('user_id');
                       if( !isset($user_id) || (empty($user_id))):
                  ?>
                    <div class="alignleft"><?= __('Are you a returning user?');?> <?php echo $this->Html->link('Login Here', ['action'=>'login'],['target'=>'_blank'])?> </div>
                  <?php endif; ?>
                  <div class="alignright"> <?= __('By clicking on SUBMIT button, I agree with AstroWOW');?> 
                  <?php echo $this->Html->link(__('Terms of Use'), [ 'controller'=>'Pages', 'action' => 'menu-pages', 'terms-of-use'], ['target' => '_blank']);?>
                  , AstroWOW 
<?php echo $this->Html->link(__('Privacy Policy'), [ 'controller'=>'Pages', 'action' => 'menu-pages', 'privacy-policy'], ['target' => '_blank']);?>
 and Communications Terms.
<?php 
 if( !isset($user_id) || (empty($user_id))):
         echo $this->Form->button(__('Login'), ['class' => 'btn btn-red', 'id'=>'btnSubmit', 'name'=>'btnSubmit']); 
endif;
       ?>
                   <?php echo $this->Form->button(__('Submit'), ['class'=>'btn btn-red', 'id'=>'btnSubmit', 'name'=>'btnSubmit'])?>
                    <!--button type="submit" id="btnSubmit" name="btnSubmit" class="btn btn-red">SUBMIT</button-->
                                    <?php echo $this->Form->end();?>

                  </div>
                  
                </div>
                <!-- .et_pb_text --> 
              </div>
              <!-- .et_pb_column --> 
            </div>
            <!-- .et_pb_row --> 
          </div>
        </div>
        <!-- .et_pb_code --> 
      </div>
      <!-- .et_pb_column --> 
      
    </div>
    <!-- .et_pb_row --> 
    
  </div>
  <!-- .et_pb_section --> 
</article>
</div>
<!-- .entry-content --> 

<!-- .et_pb_post -->

</div>
<!-- #main-content -->

<script>
        $(document).ready(function(){
         $('#hours').change(function(){
             var hours = $('#hours').val();
             if(hours == -1){
               $('#minutes').val('-1');
             }
         })
        $("#search-box").keyup(function(event){
                event.preventDefault();
                $("#flash_message").hide();
                getCities('country', 'search-box', 'suggesstion-box', 'city_id', "<?php echo $this->Url->build(['controller' => 'Users', 'action' => 'cities' ])?>");
         
        });
        });
</script>
<?php $this->request->session()->delete('dob');?>