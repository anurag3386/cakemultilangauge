<?= $this->Html->script(['common_front']); ?>
<style type="text/css">
    .message { text-align: center; }
    .success { color: green; }
    .error { color: red; }
    #frmUserPersonalDetail label { margin-right: 30px; }
    #frmUserPersonalDetail input[type="radio"] { margin-right: 5px; margin-top: 1%;}
</style>
<?php echo $this->Html->css('elite-innerpage-custom'); ?>
<?php use Cake\Cache\Cache; ?>
<?php use Cake\Routing\Router; ?>
<?= $this->Html->script ('innerpage-custom'); ?>
<article id="post-1159" class="post-1159 page type-page status-publish hentry">
	<div class="entry-content">
        <?php echo $this->element ('elite_expire'); ?>
			<div class="div-outer inn-container">
    			<div class="div-clear">
        			<div class="div-inner">
        				<?= $this->Flash->render() ?>
            			<div class="common-content-left floatL">
                			<div class="common-box box-shadow margin0">
                    			<div class="common-box-one">
                        			<h2>
                        				<?= $this->Html->image ('../images/icon-about.png', ['alt'=>'Logon details', 'title'=>'Logon details']); ?>
                        				<?php echo ucwords($this->request->session()->read('Auth.UserProfile.first_name').' '.$this->request->session()->read('Auth.UserProfile.last_name')).' '.__('details'); ?>
                        			</h2>
                        			<div class="common-box-content">
                            			<div class="accordian"> 
                            				<a class="menuitem submenuheader" href="#" headerindex="0h">
                            					<span class="accordprefix"></span>
                            						<?= __('Edit your details');?>
                            					<span class="accordsuffix">
                            						<img src="<?php echo $this->request->webroot; ?>images/icon-minus.png" class="statusicon">
                            					</span>
                            				</a>
                                			<div class="accordian-content" contentindex="0c" style="display: block;">
                                    			<div id="dvLogonDetail" class="my-account-user-birth-detail">
                                        			<table>
                                            			<tbody>
                                            				<tr>
                                                				<td colspan="2"><div class="clear"></div></td>
                                            				</tr>
                                            				<tr>
                                                				<td width="40%">
                                                					<label for="textfield">*<?= __('Email Address');?>:</label>
                                                				</td>
                                                				<td>
                                                					<input name="txtUserEmail" id="txtUserEmail" class="validate[required,custom[email]]" readonly="readonly" value="<?php echo $this->request->session()->read('Auth.User.username') ?>" type="text">
                                                				</td>
                                            				</tr>
                                            				<tr>
                                                				<td colspan="2">
                                                					<div class="clear"></div>
                                                				</td>
                                            				</tr>
                                            				<tr>
                                                				<td width="40%">
                                                					<label for="textfield">*<?= __('Password');?>:</label>
                                                				</td>
                                                				<td>
                                                					<label for="textfield"><b>○○○○○○○○</b></label>
                                                    				<div class="clear"></div>
                                                    			</td>
                                            				</tr>
                                            				<tr>
                                            					<td>
                                            						<div class="clear"></div>
                                            					</td>
                                                				<td>
                                                					<?= $this->Html->link (__('Change Password'), ['controller' => 'EliteUsers', 'action' => 'changePassword']) ?>
                                                				</td>
                                            				</tr>
                                        				</tbody>
                                        			</table>
                                        		</div>
                                        	</div>
                                        	<a class="menuitem submenuheader" href="#" headerindex="1h">
                                        		<span class="accordprefix"></span>
                                        		<?= __('Personal details');?>
                                        		<span class="accordsuffix">
                                        			<img src="<?php echo $this->request->webroot; ?>images/icon-plus.png" class="statusicon"></span>
                                        		</span>
                                        	</a>
                                			<div class="accordian-content" contentindex="1c" style="display: none;">
                                    			<div id="dvBirthDetail" class="my-account-user-birth-detail">
                                        			<form id="frmUserPersonalDetail" name="frmUserPersonalDetail" action="<?= Router::url(['controller' => 'EliteUsers', 'action' => 'updateProfile']); ?>" method="post">
                                            			<input id="hdnUserProfileId" name="hdnUserProfileId" value="19770" type="hidden">
                                            			<input id="task" name="task" value="SaveUserPersonalDetail" type="hidden">
			                                            <input id="user_id" name="user_id" value="44329" type="hidden">
			                                            <table>
                                                			<tbody>
                                                				<tr>
                                                    				<td colspan="2"><div class="clear"></div></td>
                                                				</tr>
                                                				<tr>
                                                    				<td width="40%"><label for="textfield">*<?= __('First Name');?>:</label></td>
                                                    				<td><input name="txtFirstName" id="txtFirstName" maxlength="60" placeholder="First Name" class="validate[required]" value="<?= $profile['first_name']; ?>" type="text"></td>
                                                				</tr>
				                                                <tr>
				                                                    <td colspan="2"><div class="clear"></div></td>
				                                                </tr>
				                                                <tr>
				                                                    <td width="30%"><label for="textfield">*<?= __('Last Name ');?>:</label></td>
				                                                    <td><input name="txtLastName" maxlength="60" placeholder="Last Name" id="txtLastName" class="validate[required]" value="<?= $profile['last_name']; ?>" type="text"></td>
				                                                </tr>
				                                                <tr>
				                                                    <td colspan="2"><div class="clear"></div></td>
				                                                </tr>
				                                                <tr>
				                                                    <td width="30%">
				                                                    	<label for="textfield">*<?= __('Gender ');?>:</label>
				                                                    </td>
				                                                    <td>
				                                                        <?php
                                                                            $gender = ['M' => __('Male'), 'F' => __('Female')];
                                                                            $attributes = ['legend' => false, 'value' => $profile['gender']];
                                                                            echo $this->Form->radio('rdoGender', $gender, $attributes);
                                                                        ?>
                                                                    </td>
				                                                </tr>
				                                                <tr>
				                                                    <td colspan="2"><div class="clear"></div></td>
				                                                </tr>
                                                				<tr>
                                                    				<td width="30%"><label for="textfield">*<?= __('Address');?> :</label></td>
                                                    				<td>
                                                                        <?= $this->Form->textarea ('txtAdd1', ['value' => trim($profile['address']), 'placeholder' => 'Present Address', 'maxlength'=>500, 'rows' => 5, "class"=>"validate[required]", 'cols' => 37]) ?>
                                                    				</td>
                                                				</tr>
				                                                <tr>
				                                                    <td colspan="2"><div class="clear"></div></td>
				                                                </tr>

                                                                <tr>
                                                                    <td width="30%">
                                                                        <label for="textfield">*<?= __('Country');?> :</label>
                                                                    </td>
                                                                    <td>
                                                                        <?php $selectedCountry = $country_id; ?>
                                                                        <select name="ddBirthCountry" id="ddBirthCountry" class="validate[required]">
                                                                            <?php 
                                                                            foreach ( $Countries as $countryList ) {
                                                                                $countrySelected = $profile['country_id'];
                                                                                if ($countryList['id'] == $selectedCountry) {
                                                                                    $countrySelected = 'selected';
                                                                                }
                                                                            ?>
                                                                                <option value="<?php echo $countryList['id']; ?>" <?php echo $countrySelected; ?>><?php echo $countryList['name']; ?></option>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </td>
                                                                </tr>

				                                                <tr>
				                                                    <td colspan="2"><div class="clear"></div></td>
				                                                </tr>
				                                                <tr>
				                                                    <td colspan="2"><div class="clear"></div></td>
				                                                </tr>
				                                                
                                                                <tr>
                                                                    <td width="30%">
                                                                        <label for="textfield">*<?= __('City');?> :</label>
                                                                    </td>
                                                                    <td>
                                                                        <?php $selectedCity = $profile['city_id']; ?>
                                                                        <select name="ddBirthCity" id="ddBirthCity" class="validate[required]">
                                                                        <?php 
                                                                            foreach ($Cities as $cityList ) {
                                                                            $citySelected = '';
                                                                            if ($cityList['id'] == $selectedCity) {
                                                                                $citySelected = 'selected';
                                                                            }
                                                                        ?>
                                                                        <option value="<?php echo $cityList['id']; ?>" <?php echo $citySelected; ?> >
                                                                            <?php echo $cityList['city']; ?>
                                                                        </option>
                                                                        <?php } ?>
                                                                    </select>
                                                                    </td>
                                                                </tr>
                                                                

				                                                <tr>
				                                                    <td colspan="2"><div class="clear"></div></td>
				                                                </tr>
				                                                <tr>
				                                                    <td colspan="2"><input name="btnUpdatePersonalDetail" id="btnUpdatePersonalDetail" value="<?= __('Update Personal Details'); ?>" class="sign-up-button btn btn-red" type="submit"></td>
				                                                </tr>
                                            				</tbody>
                                            			</table>
                                        			</form>
                                    			</div>
                                			</div>
                            			</div>
			                        </div>
			                    </div>
			                </div>
			            </div>
			            <?= $this->element ('elite_member_products'); ?>
					</div>
				</div>
			</div>
	</div>
</article>

<script type="text/javascript">
	$(document).ready(function() {
        
		$('.accordian a.submenuheader').on('click', function () {
            var plus = '<?php echo $this->request->webroot; ?>images/icon-plus.png'; // Path of plus sign image
            var minus = '<?php echo $this->request->webroot; ?>images/icon-minus.png'; // Path of minus sign image
            var headerindex = $(this).attr('headerindex'); // get headerindex attribute value of selected expandable link
            showOrHideDivsOnClick (plus, minus, headerindex);
        });

		
        /**
         * To get cities list based on selected country
         * Created By : Kingslay <kingslay@123789.org>
         * Created Date : Nov 09, 2016
         */
        $('#ddBirthCountry').on('change', function () {
            var country_id = $(this).val();
            var selectedCountryCityURL = "<?php echo $this->Url->build([ 'controller' => 'users', 'action' => 'getCountryCities']);?>/"+country_id;
            getCitiesListBasedOnSelectedCountry (selectedCountryCityURL);
        });


    });
</script>

