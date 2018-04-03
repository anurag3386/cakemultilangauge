<link rel="stylesheet" href="<?php echo $this->request->webroot; ?>plugins/validation/validationEngine.jquery.css">
<article id="post-136" class="post-136 page type-page status-publish hentry">
  <div class="entry-content">
    <div class="et_pb_section  et_pb_section_0 et_section_regular">
      <div class=" et_pb_row et_pb_row_0">
        <div class="et_pb_column et_pb_column_4_4  et_pb_column_0">
          <div class="et_pb_code et_pb_module  et_pb_code_0">
            <div class="et_pb_row et_pb_row_1" style="width: 100%; padding: 0px;">
               <div class="et_pb_column et_pb_column_1_3  et_pb_column_1 ">
                <div class="et_pb_text et_pb_module et_pb_bg_layout_light et_pb_text_align_left et_pb_text_1">
                  <div class="signup_well">
                    <p>
                    <?php echo $this->Html->link(__('Sign Up'), ['controller'=>'Users', 'action'=>'sign-up'])?>

                    <?= __('and get your free personalized report as your welcome gift.');?></p>
                    <p><cite><?= __('In this report you will get:');?></cite></p>
                    <ul class="checklist">
                      <li><?= __('Personal horoscope');?></li>
                      <li><?= __('Daily personalized astrological calendar');?></li>
                      <li><?= __('Major influences');?></li>
                    </ul>
                  </div>
                </div>
                <!-- .et_pb_text --> 
              </div>
              <!-- .et_pb_column -->
              
              <div class="et_pb_column et_pb_column_2_3  et_pb_column_2 signup_form_wrapper">
                <div class="et_pb_text et_pb_module et_pb_bg_layout_light et_pb_text_align_left  et_pb_text_2">
                  <div id="signup_1_wrapper">
                  <?= $this->Flash->render() ?>
                  <?php //if( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' ) { ?>
                    <h1><?= __('Login');?></h1>
                  <?php /*} else { ?>
                    <h3><?= __('Login');?></h3>
                  <?php }*/ ?>
                   <?php echo $this->Form->create('',['class'=>'checkout_form', 'name'=>'frmLogin', 'id'=>'frmLogin']);?>
<div class="login_form_1">
                      <div class="alignleft">
                        <label for=""><?= __('Username / Email');?></label>
						<?php echo $this->Form->text('username',['class'=>'inputLarge validate[required, custom[email]]', 'autocomplete'=>'no', 'placeholder'=>'youremail@example.com', 'id'=>'email_id']);?>
                        <br>
                        <label for=""><?= __('Password');?></label>
                        <?php echo $this->Form->password('password', ['class'=>'inputLarge validate[required]', 'autocomplete'=>'no'])?>

						<input name="astrowow_login_nonce" value="3825c2355a" type="hidden">
						
                        <br>
                        <br>
                        <div class="alignleft">

                        <?php
                          // if( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' ) {
                            echo $this->Form->input('remember_me', ['type' => 'checkbox', 'label' => __('Remember Me'), 'div' => false, 'style' => ['margin-right:5px;']]);
                          /*} else {
                            echo $this->Form->input('remember_me', ['type' => 'checkbox', 'div' => false, 'style' => ['margin-right:5px;']]);
                          }*/
                        ?>

                        <?php echo $this->Html->link(__('Forgot Password'), [ 'controller' => 'Users', 'action' => 'forgot-password'])?>
                   
                        </div>
                        

                        <div class="alignright">
                            <?php echo $this->Form->button(__('Login'), ['class' => 'btn btn-red', 'id'=>'btnSubmit', 'name'=>'btnSubmit']); ?>
   					    </div>
                      </div>
                      </div>
                    <?php echo $this->Form->end();?>
					
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
<script src="<?php echo $this->request->webroot; ?>plugins/validation/jquery.validationEngine.js"></script>
<script src="<?php echo $this->request->webroot; ?>plugins/validation/languages/jquery.validationEngine-en.js"></script>
<script src="<?php echo $this->request->webroot; ?>js/common_front.js"></script>
