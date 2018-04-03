<?php use Cake\Cache\Cache; ?>
<?php use Cake\I18n\I18n;?>
<?php 
  $locale = strtolower(I18n::locale());
  //if (!empty($this->request->session()->read('signUpUserDetails'))) {
    $signUpUserDetails = $this->request->session();
    if (!empty($signUpUserDetails->read('signUpUserDetails.profile.first_name'))) {
        $signUpUserDetails->write('fname', $signUpUserDetails->read('signUpUserDetails.profile.first_name'));
    }
    if (!empty($signUpUserDetails->read('signUpUserDetails.profile.first_name'))) {
        $signUpUserDetails->write('lname', $signUpUserDetails->read('signUpUserDetails.profile.last_name'));
    }
    $selectedgender = !empty($signUpUserDetails->read('signUpUserDetails.profile.gender')) ? $signUpUserDetails->read('signUpUserDetails.profile.gender') : '';
    //$selectedlanguageid = !empty($signUpUserDetails->read('signUpUserDetails.profile.language_id')) ? $signUpUserDetails->read('signUpUserDetails.profile.language_id') : '';

    $useremailId = !empty($signUpUserDetails->read('signUpUserDetails.username')) ? $signUpUserDetails->read('signUpUserDetails.username') : '';
    $password = !empty($signUpUserDetails->read('signUpUserDetails.password')) ? $signUpUserDetails->read('signUpUserDetails.password') : '';
    $confirm_password = !empty($signUpUserDetails->read('signUpUserDetails.confirm_password')) ? $signUpUserDetails->read('signUpUserDetails.confirm_password') : '';
  //}
?>
				<article id="post-134" class="post-134 page type-page status-publish hentry">

                  <div class="entry-content">
                     <div class="et_pb_section  et_pb_section_0 et_section_regular">
                     <div class="sign-up-step-1">
                         <div class=" et_pb_row et_pb_row_0">

                            <div class="et_pb_column et_pb_column_4_4  et_pb_column_0">

                                <div class="et_pb_text et_pb_module et_pb_bg_layout_light et_pb_text_align_left  et_pb_text_0">
                                    <div style="text-align:center; color: red"><?php echo $this->Flash->render() ?></div>
                                    <?php if( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' ) { ?>
                                        <h1><?= __('Sign Up')?></h1>
                                    <?php } else { ?>
                                        <h2><?= __('Sign Up')?></h2>
                                    <?php } ?>
                                </div> <!-- .et_pb_text -->
                            </div> <!-- .et_pb_column -->

                        </div> <!-- .et_pb_row -->
                        </div>
                        <div class=" et_pb_row et_pb_row_1">

                        <div class="et_pb_column et_pb_column_4_4  et_pb_column_1">

                            <div class="et_pb_code et_pb_module  et_pb_code_0">
                                <!-- <div class="et_pb_section  et_pb_section_1 et_section_regular"> -->

                                <div class="et_pb_row et_pb_row_1" style="width: 100%; padding: 0px;">

                                    <div class="et_pb_column et_pb_column_1_3  et_pb_column_1 ">

                                       <div class="et_pb_text et_pb_module et_pb_bg_layout_light et_pb_text_align_left et_pb_text_1">
                                            <div class="signup_well">
                                                <p><?= __('Sign up and get your free personalized report as your welcome gift.');?></p>
                                                <p><cite><?= __('In this report you will get:');?></cite></p>
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

                                            <p>
                                                <?php echo $this->Html->image('sign_up_1.png', ['alt'=>'sign_up_1','class'=>'alignleft size-full wp-image-364','width'=>'787','height'=>'74'])?>
                                            </p>
                                            <div class="signup_1 signup_active"><?= __('Step 1');?></div>
                                            <div class="signup_2"><?= __('Step 2');?></div>
                                            <div id="signup_1_wrapper">
                                                <h3><?= __('General Information');?></h3>
                                                <div class="sign_up_form1">
                                                <?php echo $this->Form->create($form,['class'=>'checkout_form', 'id'=>'frmRegStepOne', 'id' => 'frmRegStepOne', 'novalidate' => true])?>
                                                <div class="alignleft">
                                                    <label for=""><?= __('First Name');?> <span style='color:red'>*</span></label><br>
                                                    <?php
                                                       echo $this->Form->text('profile.first_name', ['class'=>'inputLarge validate[required] capitalize', 'placeholder'=>'John', 'default' => $this->request->session()->read('fname'), 'maxlength' => 60]);
                                                    ?>
                                                    <br>

                                                    <label for=""><?= __('Last Name');?> <span style='color:red'>*</span></label><br>
                                                    <?php 
                                                    echo $this->Form->text('profile.last_name', ['class'=>'inputLarge capitalize validate[required]' , 'placeholder'=>'Doe', 'id'=>'lastname', 'default' => $this->request->session()->read('lname'), 'maxlength' => 60]);

                                                      ?>
                                                    <br>

                                                    <label for=""><?= __('Gender');?> <span style='color:red'>*</span></label><br>

                                                    <?php
                                                    $options = ['M' => __('Male'), 'F' => __('Female')];

                                                    echo $this->Form->select('profile.gender', $options, ['class' => 'validate[required] down', 'empty' => __('Select Gender'), 'default' => $selectedgender] ); ?>

                                                    <label for=""><?= __('Language'); ?> <span style='color:red'>*</span></label><br>

                                                    <?php
                                                        $selectedlanguage = '';
                                                        if ($this->request->session()->read('locale') == 'da') {
                                                            $selectedlanguage = 2;
                                                        } else {
                                                            $selectedlanguage = 1;
                                                        }
                                                        echo $this->Form->select('profile.language_id', $languageOptions, ['class' => 'validate[required] down', 'value' => $selectedlanguage/*, 'empty'=> __('Select Language')*/] ); ?>
                                                </div>

                                                <div class="alignright"><label for=""><?= __('Email');?> <span style='color:red'>*</span></label><br>
                                                    <?php echo $this->Form->text('username', ['class'=>'inputLarge validate[required,custom[email]]', 'placeholder'=>'youremail@example.com', 'id'=>'email_id', 'default' => $useremailId, 'maxlength' => 80])?>
                                                    <br>

                                                    <label for=""><?= __('Password');  ?> <span style='color:red'>*</span></label><br>
                                                    <?php echo $this->Form->password('password', ['class'=>'inputLarge validate[required, minSize[6]]', 'id'=>'password', 'default' => $password, 'maxlength' => 20, 'placeholder' => 'Enter password']);?>
                                                    <br>
                                                    <label for=""><?= __('Confirm Password'); ?><span style='color:red'> *</span></label><br>
                                                    <?php echo $this->Form->password('confirm password', ['class'=>'inputLarge validate[required, equals[password]]', 'id'=>'password', 'default' => $confirm_password, 'maxlength' => 20, 'placeholder' => 'Confirm your password']);?><br>

                                                    <?php //if( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' ) { ?>
                                                        <label for=""><?= __('Captcha');  ?> <span style='color:red'>*</span></label><br>
                                                        <?php echo $this->Form->text('captcha', ['class'=>'inputLarge validate[required]', 'id'=>'captcha', 'maxlength' => 10, 'placeholder' => 'Enter captcha code']); ?>
                                                        <br/><img src="https://www.astrowow.com/users/get-captcha" />   
                                                    <?php /*} else { ?>
                                                        <div class="g-recaptcha" data-sitekey="6Lc8yx8UAAAAAAlXJB5nBVHmScDaP1RH8SV6wHre"></div>
                                                    <?php }*/ ?>

                                                </div>
                                                <!-- form end here-->
                                              </div>
                                            </div>

                                        </div> <!-- .et_pb_text -->
                                    </div> <!-- .et_pb_column -->

                                </div> <!-- .et_pb_row -->
                                <div class=" et_pb_row et_pb_row_2" style="width: 100%; padding: 0px;">

                                    <div class="et_pb_column et_pb_column_4_4  et_pb_column_3">

                                        <div class="et_pb_text et_pb_module et_pb_bg_layout_light et_pb_text_align_left  et_pb_text_3">

                                            <hr class="divider">
                                            <div class="login_section">
                                            <div class="alignleft"><?= __('Are you a returning user?');?> 
                                                <?php echo $this->Html->link(__('Login Here'), ['controller'=>'Users', 'action'=>'login'])?>
                                            </div>

                                            
                                            <div class="alignright">


                                                <?php
                                                    //echo $this->Form->button(__('NOW, ENTER YOUR BIRTH DETAILS').' <i class="fa fa-long-arrow-right" aria-hidden="true"></i>', ['class'=>'btn btn-red', 'id'=>'btnSubmit', 'name'=>'btnSubmit', 'onClick' => "ga('send', 'event', { eventCategory: 'SignUp', eventAction: 'Click', eventLabel: 'UserRegistration', eventValue: 0});" ]);
                                                    echo $this->Form->button(__('NOW, ENTER YOUR BIRTH DETAILS').' <i class="fa fa-long-arrow-right" aria-hidden="true"></i>', ['class'=>'btn btn-red', 'id'=>'btnSubmit', 'name'=>'btnSubmit' ]);
                                                ?>
                                                <?php echo $this->Form->end();?>
                                            </div>
                                          </div>
                                        </div> <!-- .et_pb_text -->
                                    </div> <!-- .et_pb_column -->

                                </div> <!-- .et_pb_row -->

                            </div>

                        </div> <!-- .et_pb_code -->
                    </div> <!-- .et_pb_column -->

                </div> <!-- .et_pb_row -->

            </div> <!-- .et_pb_section -->
        </article></div> <!-- .entry-content -->


        <!-- .et_pb_post -->



    </div> <!-- #main-content -->
    <?php $this->request->session()->delete('firstname');?>
    <?php //Cache::delete('firstname');?>
