<style type="text/css">
    .overflow {overflow: inherit; display: table; width: 100%;}
    .datepicker {z-index: 9999999999 !important;}
    .message {text-align: center;}
    /*#txtUserEmail {background-color: #EEE !important; cursor: no-drop;}*/
</style>
<?php use Cake\Cache\Cache; ?>
<?php use Cake\Routing\Router; ?>
<?php echo $this->Html->css(['innerpage-custom']); ?>
<?= $this->Html->script ('innerpage-custom'); ?>
<?= $this->Flash->render() ?>
<!-- <div class="message success" style="display: none;"></div> -->
<?php if (!empty($this->request->session()->read('profileUpdated'))) {
        $recordUpdated = $this->request->session()->read('profileUpdated'); ?>
<?php } ?>
<?php
    $hours = array(0=>'00 a.m. (00.00)', 0=>'00 a.m. (00.00)', 1=>'01 a.m. (01.00)', 2=>'02 a.m. (02.00)', 3=>'03 a.m. (03.00)', 4=>'04 a.m. (04.00)', 5=>'05 a.m. (05.00)', 6=>'06 a.m. (06.00)', 7=>'07 a.m. (07.00)', 8=>'08 a.m. (08.00)', 9=>'09 a.m. (09.00)', 10=>'10 a.m. (10.00)', 11=>'11 a.m. (11.00)', 12=>'12 p.m. (12.00)', 13=>'01 p.m. (13.00)', 14=>'02 p.m. (14.00)', 15=>'03 p.m. (15.00)', 16=>'04 p.m. (16.00)', 17=>'05 p.m. (17.00)', 18=>'06 p.m. (18.00)', 19=>'07 p.m. (19.00)', 20=>'08 p.m. (20.00)', 21=>'09 p.m. (21.00)', 22=>'10 p.m. (22.00)', 23=>'11 p.m. (23.00)');
?>
<div class="editProfile">
    <div class=" et_pb_row et_pb_row_0">
        <div class="common-box box-shadow editprofileBox overflow">
            <div class="common-box-one">
                <h2 class="profileName">
                    <?= $this->Html->image ('../images/icon-about.png', ['alt'=>'Logon details', 'title'=>'Logon details']); ?>
                    <?= $header.__('details'); ?>
                    <span class="back_myastro">
                        ( <?= $this->Html->link (__('Back to My AstroPage'),['controller'=>'users', 'action'=>'dashboard']); ?> )
                    </span>
                </h2>
                <div class="common-box-content accordianContent">
                <?php if (is_numeric($personId)) { ?>
                    <div class="accordian"> 
                    	<a class="menuitem submenuheader" href="#" headerindex="0h">
                            <span class="accordprefix"></span><?= __('Edit your details');?><span class="accordsuffix">
                            <img src="<?php echo $this->request->webroot; ?>images/icon-minus.png" class="statusicon"></span>
                        </a>
                        <div class="accordian-content" contentindex="0c">
                            <div id="dvLogonDetail" class="my-account-user-birth-detail">
                                <?php echo $this->Form->create ($entity1, ['id'=>'frmUserDetail']); ?>
                                    <table style="width:100%">
                                        <tbody>
                                            <tr>
                                                <td colspan="2"><div class="clear"></div></td>
                                            </tr>
                                            
                                            <tr>
                                                <td><label for="textfield">*<?= __('First Name');?>:</label></td>
                                                <td>
                                                    <input type="text" name="txtUserFname" placeholder="<?= __('First Name'); ?>" maxlength="60" id="txtUserFname" value="<?php echo $fname = !empty($userProfile['first_name']) ? ($userProfile['first_name']) : ''; ?>" class='validate[required]'>
                                                    
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2"><div class="clear"></div></td>
                                            </tr>

                                            <tr>
                                                <td><label for="textfield">*<?= __('Last Name');?>:</label></td>
                                                <td>
                                                    <input type="text" name="txtUserlname" placeholder="<?= __('Last Name'); ?>" maxlength="60" id="txtUserlname" value="<?php echo $lname = !empty($userProfile['last_name']) ? ($userProfile['last_name']) : ''; ?>" class='validate[required]'>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td colspan="2"><div class="clear"></div></td>
                                            </tr>

                                            <tr>
                                                <td><label for="textfield">*<?= __('Email Address');?>:</label></td>
                                                <td>
                                                    <input type="text" name="txtUserEmail" maxlength="80" id="txtUserEmail" readonly="readonly" value="<?php echo $email = !empty($userData['username']) ? ($userData['username']) : ''; ?>" style="background-color: #EEE !important; cursor: no-drop;">
                                                </td>
                                            </tr>

                                            <?php //if ( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' ) { ?>
                                            <tr class="genderRdoBtn">
                                                <td><label for="textfield">*<?= __('Gender');?>:</label></td>
                                                <td>
                                                    <div class="redo">
                                                        <ul>
                                                        <?php
                                                            $gender = '';
                                                            if(!empty($userProfile->gender)) {
                                                                $gender = $userProfile->gender;
                                                            }
                                                            $genderList = ['M' => __('Male'), 'F' => __('Female')];
                                                            foreach ($genderList as $key => $value) {
                                                                $checked = '';
                                                                if (!empty($userProfile['gender'])) {
                                                                    if ($key == $userProfile['gender']) {
                                                                        $checked = ' checked';
                                                                    }
                                                                }
                                                            ?>
                                                                <li>
                                                                    <input name="rdoGender" id="rdo<?= $value; ?>" value="<?= $key; ?>" class="validate[required]" type="radio"<?= $checked; ?>>
                                                                    <label for="rdo<?= $value; ?>">&nbsp;<?= $value;?></label>
                                                                    <div class="check"></div>
                                                                </li>
                                                        <?php } ?>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php //} ?>
                                            <tr>
                                                <td colspan="2"><div class="clear"></div></td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td>
                                                    <input type="submit" name="btnUpdateUserDetail" id="btnUpdateUserDetail" value="<?= __('Update User Detail');?>" class="sign-up-button">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2"><div class="clear"></div></td>
                                            </tr>
                                            <tr>
                                                <td><label for="textfield">*<?= __('Password');?>:</label></td>
                                                <td><label for="textfield"><b>○○○○○○○○</b></label>
                                                    <div class="clear"></div></td>
                                            </tr>
                                            <tr>
                                                <td><div class="clear"></div></td>
                                                <td class="changePass">
                                                    <?php echo $this->Html->link(__('Change Password'), ['controller' =>'Users','action'=>'change-password']); ?>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                <?php echo $this->Form->end(); ?>
                            </div>
                        </div>
                        <a class="menuitem submenuheader" href="#" headerindex="1h"><span class="accordprefix"></span><?= __('Birth details');?><span class="accordsuffix"><img src="<?php echo $this->request->webroot; ?>images/icon-plus.png" class="statusicon"></span></a>
                        <div class="accordian-content" contentindex="1c">
                            <div id="dvBirthDetail" class="my-account-user-birth-detail">
                                <?php echo $this->Form->create ($entity2, ['id'=>'frmUserDetail']); ?>
                                    <table style="width:100%">
                                        <tbody>
                                            <tr>
                                                <td colspan="2"><div class="clear"></div></td>
                                            </tr>

                                            <?php /*if ( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' ) { ?>
                                            <tr>
                                                <td><label for="textfield">*<?= __('Gender');?>:</label></td>
                                                <td>
                                                    <div class="redo">
                                                        <ul>
                                                        <?php
                                                            $gender = $userProfile->gender;
                                                            $genderList = ['M' => __('Male'), 'F' => __('Female')];
                                                            foreach ($genderList as $key => $value) {
                                                                $checked = '';
                                                                if (!empty($userProfile['gender'])) {
                                                                    if ($key == $userProfile['gender']) {
                                                                        $checked = ' checked';
                                                                    }
                                                                } else {
                                                                    if ($value == __('Male')) {
                                                                        $checked = ' checked';
                                                                    }
                                                                }
                                                            ?>
                                                                <li>
                                                                    <input name="rdoGender" id="rdo<?= $value; ?>" value="<?= $key; ?>" class="validate[required]" type="radio"<?= $checked; ?>>
                                                                    <label for="rdo<?= $value; ?>">&nbsp;<?= $value;?></label>
                                                                    <div class="check"></div>
                                                                </li>
                                                        <?php } ?>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php }*/ ?>

                                            <tr>
                                                <td colspan="2"><div class="clear"></div></td>
                                            </tr>
                                            <tr>
                                                <td><label for="textfield">*<?= __('Birth Date');?>:</label></td>
                                                <td>
                                                    <input type='text' id='user_dob_edit' readonly="readonly" name='user_dob_edit' value = "<?php echo $user_dob = date('d/m/Y', strtotime($BirthDetails['date']));?>" class='validate[required]'/>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2"><div class="clear"></div></td>
                                            </tr>
                                            <tr>
                                                <td><label for="textfield">*<?= __('Birth Time');?>:</label></td>
                                                <td>
                                                     <?php
                                                        $user_dob_time_hrs = $user_dob_time_mins = 0;
                                                        if (strpos($BirthDetails['time'], ':') !== false) {
                                                            $user_dob_time = @explode (':', $BirthDetails['time']);
                                                            $user_dob_time_hrs = !empty($user_dob_time[0]) ? $user_dob_time[0] : 0;
                                                            $user_dob_time_mins = !empty($user_dob_time[1]) ? $user_dob_time[1] : 0;
                                                        }
                                                    ?>
                                                    <select id="birthhour" name="birthhour" class="validate[required]">
                                                        <?php 
                                                        for ($h=0; $h<count($hours); $h++) {
                                                            $hrsSelected = '';
                                                            if ($h == $user_dob_time_hrs) {
                                                                $hrsSelected = 'selected';
                                                            }
                                                        ?>
                                                            <option value="<?php echo $h; ?>" <?php echo $hrsSelected; ?>><?php echo $hours[$h]; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                    <select id="birthminute" name="birthminute" class="validate[required]">
                                                        <?php for ($min=0; $min<60; $min++) {
                                                            $minsSelected = '';
                                                            if ($min == $user_dob_time_mins) {
                                                                $minsSelected = 'selected';
                                                            }
                                                        ?>
                                                            <option value="<?php echo $min; ?>" <?php echo $minsSelected; ?>><?php echo $min; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                    <input id="knowntime" name="knowntime" value="0" type="hidden">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2"><div class="clear"></div></td>
                                            </tr>
                                            <tr>
                                                <td><label for="select4">*<?= __('Birth Country');?>:</label></td>
                                                <td>
                                                    <?php $selectedCountry = $BirthDetails['country_id']; ?>
                                                    <select name="ddBirthCountry" id="ddBirthCountry" class="validate[required]">
                                                        <?php 
                                                        foreach ( $Countries as $countryList ) {
                                                            $countrySelected = '';
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
                                                <td><label for="textfield">*<?= __('Birth City');?>:</label></td>
                                                <td> 
                                                    <?php $selectedCity = $BirthDetails['city_id']; ?>
                                                    <select name="ddBirthCity" id="ddBirthCity" class="validate[required]">
                                                        <?php
                                                        foreach ($Cities as $cityList ) {
                                                            $citySelected = '';
                                                            if ($cityList->id == $selectedCity) {
                                                                echo $cityList->id;
                                                                $citySelected = 'selected';
                                                            }
                                                        ?>
                                                            <option value="<?php echo $cityList->id; ?>" <?php echo $citySelected; ?> >
                                                                <?php echo $cityList->city; ?>
                                                            </option>
                                                        <?php } ?>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2"><div class="clear"></div></td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td>
                                                    <input type="submit" name="btnUpdateBirthDetail" id="btnUpdateBirthDetail" value="<?=  __('Update Birth Detail');?>" class="sign-up-button">
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                <?php echo $this->Form->end(); ?>
                            </div>
                        </div>
                        <a class="menuitem submenuheader" href="#" headerindex="4h">
                            <span class="accordprefix"></span><?= __('Previous order detail');?>
                            <span class="accordsuffix"><img src="<?php echo $this->request->webroot; ?>images/icon-plus.png" class="statusicon"></span>
                        </a>
                        <div class="accordian-content detail_box" contentindex="4c">

                            <?php //if (!empty($perchasedReport)) { ?>
                            <?php if (!$perchasedReport->isEmpty()) { ?>

                                <div id="no-more-tables">
                                    <table class="col-md-12 table-bordered table-striped table-condensed cf">
                                        <thead class="cf">
                                            <tr>
                                                <th class="numeric"><?= __('Image');?></th>
                                                <th class="numeric"><?= __('Order Id');?></th>
                                                <th class="numeric"><?= __('Product Name');?></th>
                                                <th class="numeric"><?= __('Category Name');?></th>
                                                <th class="numeric"><?= __('Price');?></th>
                                                <th class="numeric"><?= __('Order Date');?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($perchasedReport as $reports) { ?>
                                            <tr>
                                                <td data-title="<?= __('Image');?>">
                                                    <?php
                                                    if ($reports['product_type'] == 5 || $reports['product_type'] == 8) {
                                                        if (file_exists(WWW_ROOT.'reports/var/spool/'.$reports['id'].'.bundle.pdf')) { ?>
                                                            <a href="<?= Router::url('/', true); ?>reports/var/spool/<?= $reports['id'].'.bundle.pdf'; ?>" target="_blank">
                                                                <?php echo $this->Html->image('../uploads/products/'.$reports['products']['image'], ['title' => 'Product Image', 'alt' => 'Product Image', 'width' => '50px', 'height' => '50px']); ?>
                                                            </a>
                                                    <?php } else {
                                                            echo $this->Html->image('../uploads/products/'.$reports['products']['image'], ['title' => 'Product Image', 'alt' => 'Product Image', 'width' => '50px', 'height' => '50px']);
                                                        }
                                                    } else {
                                                        echo $this->Html->image('../uploads/products/'.$reports['products']['image'], ['title' => 'Product Image', 'alt' => 'Product Image', 'width' => '50px', 'height' => '50px']);
                                                    } ?>
                                                </td>
                                                <td data-title="<?= __('Order Id');?>"><?= $reports['payer_order_id']; ?></td>
                                                <td data-title="<?= __('Product Name');?>" class="numeric">
                                                    <?php 
                                                    if ($reports['product_type'] == 5 || $reports['product_type'] == 8) { 
                                                        if (file_exists(WWW_ROOT.'reports/var/spool/'.$reports['id'].'.bundle.pdf')) { ?>
                                                        <a href="<?= Router::url('/', true); ?>reports/var/spool/<?= $reports['id'].'.bundle.pdf'; ?>" target="_blank"> <?php echo __d('default', $reports['products']['name']); ?>
                                                        </a>
                                                    <?php } else {
                                                            echo __d('default', $reports['products']['name']);
                                                        }
                                                    } else { echo __d('default', $reports['products']['name']); } ?>
                                                </td>
                                                <td data-title="<?= __('Category Name');?>" class="numeric">
                                                    <?php echo __d('default', $reports['categories']['name']); ?>
                                                </td>
                                                <td data-title="<?= __('Price');?>" class="numeric">
                                                    <?php echo $reports['currencies']['symbol'].$this->Custom->formatPrice($reports['price'],$reports['currencies']['symbol']); ?>
                                                </td>
                                                <td data-title="<?= __('Order Date');?>" class="numeric">
                                                    <?php
                                                        if (!empty ($this->request->session()->read('locale')) && ($this->request->session()->read('locale') == 'da')) {
                                                                    $order_date = str_replace('/', '-', $reports['order_date']);
                                                                    echo date('d-m-Y', strtotime($order_date));
                                                                } else {
                                                                    echo date('d-m-Y', strtotime($reports['order_date']));
                                                                }
                                                    ?>
                                                </td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>

                                <?php if ($this->Paginator->params()['count'] > $this->Paginator->params()['perPage']) { ?>
                                    <div class="box-footer clearfix">
                                        <ul class="pull-right reportsPagination">
                                          <?php echo $this->Paginator->prev(' << '); ?>
                                          <?php echo $this->Paginator->numbers(); ?>
                                          <?php echo $this->Paginator->next(' >> '); ?>
                                        </ul>
                                    </div>
                                <?php } ?>
                            <?php } else { ?>

                                <div id="no-more-tables">
                                    <table class="col-md-12 table-bordered table-striped table-condensed cf" style="width: 100%;">
                                        <thead class="cf">
                                            <tr>
                                                <th class="numeric"><?= __('Image');?></th>
                                                <th class="numeric"><?= __('Order Id');?></th>
                                                <th class="numeric"><?= __('Product Name');?></th>
                                                <th class="numeric"><?= __('Category Name');?></th>
                                                <th class="numeric"><?= __('Price');?></th>
                                                <th class="numeric"><?= __('Order Date');?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td data-title='' class="numeric" colspan="6" style="text-align: center; padding-left: 0px !important;">
                                                    <?= __('Currently you do not have any purchased reports.'); ?>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                
                                <!-- echo 'Currently you do not have any purchased reports.'; -->
                            <?php } ?>
                        </div>

                        <?php
                            $session = $this->request->session();
                            $loggedInUser = !empty($session->read ('Auth.User.id')) ? $session->read ('Auth.User.id') : $session->read ('user_id');
                            $localeVal = !empty($session->read('locale')) ? $session->read('locale') : 'en';
                            if ($user_id == $loggedInUser) {
                                //$message = __('Are you sure to delete your account?');
                                $message2 = __('Are you sure you want to delete your account?');
                                //$message = __('Are you sure you want to delete your account permanently?');
                                if ($localeVal == 'da') {
                                    $text = 'Deaktiver min konto';
                                    $text2 = 'Slet min konto permanent';
                                    $message = 'Er du sikker på du vil slette din konto?';
                                } else {
                                    $text = 'Deactivate My Account';
                                    $text2 = 'Delete My Account Permanently';
                                    $message = 'Are you sure you want to deactivate your account?';
                                }
                            } else {
                                $name = !empty($userProfile['first_name']) ? ucwords($userProfile['first_name']) : '';
                                $name .= !empty($userProfile['last_name']) ? ucwords(' '.$userProfile['last_name']) : '';
                                $message = __('Are you sure you want to delete ').$name.'?';
                                //$message = __('Are you sure to delete').' '.$name.'?';
                                $text = __('Delete').' '.$name;
                            }
                        ?>
                        <a class="menuitem submenuheader" href="<?= Router::url(['controller' => 'users', 'action' => 'deleteaccount']); ?>" headerindex="5h" onclick=" return confirm('<?= $message; ?>')">
                            <span class="accordprefix"></span><?= $text; ?>
                        </a>
                        <?php if($localeVal == 'da') { ?>
                            <a class="menuitem submenuheader" href="<?= Router::url('/', true).'dk/brugere/deletepermanent'; ?>" headerindex="6h" onclick=" return confirm('<?= $message2; ?>')">
                                <span class="accordprefix"></span><?= $text2; ?>
                            </a>
                        <?php } else { ?>
                            <a class="menuitem submenuheader" href="<?= Router::url(['controller' => 'users', 'action' => 'deletepermanent']); ?>" headerindex="6h" onclick=" return confirm('<?= $message2; ?>')">
                                <span class="accordprefix"></span><?= $text2; ?>
                            </a>
                        <?php } ?>
                    </div>
                <?php } else { ?>
                                <!-- <form id="frmUserBirthDetail" name="frmUserBirthDetail" action="" method="post" class="edit_another_person"> -->
                                <?php echo $this->Form->create ($entity, ['id'=>'frmUserDetail']); ?>
                                    <table>
                                        <tbody>
                                            <tr>
                                                <td colspan="2"><div class="clear"></div></td>
                                            </tr>
                                            <tr>
                                                <td width="30%"><label for="textfield">*<?= __('First Name');?>:</label></td>
                                                <td>
                                                    <input type='text' id='fname' name='fname' placeholder="<?= __('First Name'); ?>" maxlength="60" class="validate[required]" value = "<?php echo $BirthDetails['fname']; ?>" />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2"><div class="clear"></div></td>
                                            </tr>
                                            <tr>
                                                <td width="30%"><label for="textfield">*<?= __('Last Name');?>:</label></td>
                                                <td>
                                                    <input type='text' id='lname' name='lname' placeholder="<?= __('Last Name'); ?>" maxlength="60" class="validate[required]" value = "<?php echo $BirthDetails['lname']; ?>" />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2"><div class="clear"></div></td>
                                            </tr>

                                            <tr>
			                                    <td width="30%"><label for="textfield"><?= __('Gender'); ?>:</label></td>
			                                    <td>
                                                    <div class="redo">
                                                        <ul>
    			                                    	<?php
    			                                    		$genderList = ['1' => __('Male'), '2' => __('Female')];
    			                                    		foreach ($genderList as $key => $value) {
    			                                    			$checked = '';
    			                                    			if ($BirthDetails['gender'] == $key) {
    			                                    				$checked = 'checked';
    			                                    			}
    			                                    			?>
                                                                <li>
                                                                    <input name="rdoGender" id="rdo<?= $value; ?>" value="<?= $key; ?>" class="validate[required]" type="radio" <?= $checked; ?>>
                                                                    <label for="rdo<?= $value; ?>">&nbsp;<?= $value;?></label>
                                                                    <div class="check"></div>
                                                                </li>
			                                    		<?php } ?>
                                                        </ul>
                                                    </div>
			                                        <br>
			                                    </td>
			                                </tr>
                                            <tr>
                                                <td colspan="2"><div class="clear"></div></td>
                                            </tr>

                                            <tr>
                                                <td width="30%"><label for="textfield">*<?= __('Birth Date');?>:</label></td>
                                                <td>
                                                    <input type='text' id='user_dob_edit' class="validate[required]" name='user_dob_edit' readonly="readonly" value = "<?php echo $user_dob = date('d/m/Y', strtotime($BirthDetails['date'])); ?>" />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2"><div class="clear"></div></td>
                                            </tr>
                                            <tr>
                                                <td width="30%"><label for="textfield">*<?= __('Birth Time');?>:</label></td>
                                                <td>
                                                     <?php
                                                        $user_dob_time_hrs = $user_dob_time_mins = 0;
                                                        if (strpos($BirthDetails['time'], ':') !== false) {
                                                            $user_dob_time = @explode (':', $BirthDetails['time']);
                                                            $user_dob_time_hrs = !empty($user_dob_time[0]) ? $user_dob_time[0] : 0;
                                                            $user_dob_time_mins = !empty($user_dob_time[1]) ? $user_dob_time[1] : 0;
                                                        }
                                                    ?>
                                                    <select id="birthhour" name="birthhour" class="validate[required]">
                                                        <?php 
                                                        for ($h=0; $h<count($hours); $h++) {
                                                            $hrsSelected = '';
                                                            if ($h == $user_dob_time_hrs) {
                                                                $hrsSelected = 'selected';
                                                            }
                                                        ?>
                                                            <option value="<?php echo $h; ?>" <?php echo $hrsSelected; ?>><?php echo $hours[$h]; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                    <select id="birthminute" name="birthminute" class="validate[required]">
                                                        <?php for ($min=0; $min<60; $min++) {
                                                            $minsSelected = '';
                                                            if ($min == $user_dob_time_mins) {
                                                                $minsSelected = 'selected';
                                                            }
                                                        ?>
                                                            <option value="<?php echo $min; ?>" <?php echo $minsSelected; ?>><?php echo $min; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                    <input id="knowntime" name="knowntime" value="0" type="hidden">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2"><div class="clear"></div></td>
                                            </tr>
                                            <tr>
                                                <td width="33%"><label for="select4">*<?= __('Birth Country');?>:</label></td>
                                                <td>
                                                    <?php $selectedCountry = $BirthDetails['country_id']; ?>
                                                    <select name="ddBirthCountry" id="ddBirthCountry" class="validate[required]">
                                                        <?php 
                                                        foreach ( $Countries as $countryList ) {
                                                            $countrySelected = '';
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
                                                <td width="30%"><label for="textfield">*<?= __('Birth City');?>:</label></td>
                                                <td>
                                                    <?php $selectedCity = $BirthDetails['city_id']; ?>
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
                                                <td>&nbsp;</td>
                                                <td>
                                                    <?php
                                                        $uname = ucwords($BirthDetails['fname'].' '.$BirthDetails['lname']);
                                                        $cnfrm_message = __('Are you sure you want to delete the user:').' '.$uname.'?';
                                                    ?>
                                                    <input type="submit" name="btnUpdateBirthDetail" id="btnUpdateBirthDetail" value="<?= __('Update Birth Detail'); ?>" class="sign-up-button">
                                                    <div class="del">
                                                        <?php
                                                            if ($this->request->session()->read('locale') == 'da') {
                                                                $localeVal = '/dk/';
                                                            } else {
                                                                $localeVal = '/';
                                                            }
                                                            echo $this->Html->link (__('Delete This User'), Router::url($localeVal.__('users').'/'.__('delete-another-person').'/'.$BirthDetails['id'], true), ['class'=>'btn btn-red', 'onclick' => "return confirm('".$cnfrm_message."')"]);
                                                        ?>
                                                    	<?php //$this->Html->link (__('Delete This User'),['controller'=>'users', 'action'=>'deleteAnotherPerson', $BirthDetails['id']], ['class'=>'btn btn-red', 'onclick' => "return confirm('".$cnfrm_message."')"]); ?>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                <?php echo $this->Form->end(); ?>
                            <!-- </div>
                        </div>
                    </div> -->
                <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>





<style type="text/css">
    
</style>
<script type="text/javascript">
    $(document).ready(function() {

        /**
         * Used to manage page html
         * Created By : Kingslay <kingslay@123789.org>
         * Created Date : Nov 08, 2016
         */
        var paginationRequestOrNot = "<?php if (isset ($_GET['page'])) { echo $_GET['page']; } ?>";
        
        /**
         * Show reports div on clicking pagination else it'll be hidden
         */
        if (paginationRequestOrNot != '') {
        	$('.accordian-content').hide();
            $('.accordian-content.detail_box').show(); // show reports detail on clicking paginattion
            var minus = '<?php echo $this->request->webroot; ?>images/icon-minus.png'; // Path of minus sign image
            var plus = '<?php echo $this->request->webroot; ?>images/icon-plus.png'; // Path of plus sign image
            $('a[headerindex=4h]').find('img.statusicon').attr('src', minus);
            $('a[headerindex=0h]').find('img.statusicon').attr('src', plus);
            $('div[contentindex=4c]').show();
        }
        // End
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
            var langg = "<?php echo $lang = !empty($this->request->session()->read('locale')) ? $this->request->session()->read('locale') : 'en'; ?>";
            var selectedCountryCityURL = "<?php echo $this->Url->build([ 'controller' => 'users', 'action' => 'get-country-cities']);?>/"+country_id;
            if (langg == 'dk' || langg == 'da') {
                var selectedCountryCityURL = "<?php echo Router::url('/', true);?>dk/brugere/få-country-byer/"+country_id;
            }
            getCitiesListBasedOnSelectedCountry (selectedCountryCityURL);
        });

        /**
         * Used to generate horoscope wheel if birth detail is updated
         * Created By : Kingslay <kingslay@123789.org>
         * Created Date : Feb. 09, 2017
         */
        var dataUpdated = "<?php echo $detailUpdated = (isset($recordUpdated) && !empty($recordUpdated)) ? $recordUpdated : false; ?>";
        if (dataUpdated) {
            setTimeout(function(){
                generateWheelOnPageLoad (dataUpdated);
            },500);
            //generateWheelOnPageLoad (dataUpdated);
        }
    });

    /**
     * Used to generate wheel
     * Created By : Kingslay <kingslay@123789.org>
     * Created Date : Feb. 09, 2017
     */
    function generateWheelOnPageLoad (selectedUser) {
        var langg = "<?php echo $lang = !empty($this->request->session()->read('locale')) ? $this->request->session()->read('locale') : 'en'; ?>";
        var apikey = "<?php echo md5('astrowow.com'); ?>";
        var userId = userHoroscopeWheelURL = '';
        if(selectedUser.indexOf('_') != -1){
            var splittedData = selectedUser.split('_');
            userId = splittedData[1];
            //userHoroscopeWheelURL = "<?php //echo $this->Url->build([ 'controller' => 'users', 'action' => 'wheel']); ?>?apikey="+apikey+'&uid='+userId+'&task=natalwheel&aper=yes';
            userHoroscopeWheelURL = "<?php echo $this->Url->build([ 'controller' => 'users', 'action' => 'wheel']); ?>/"+apikey+'/'+userId+'/natalwheel/yes';
            if (langg == 'dk' || langg == 'da') {
                userHoroscopeWheelURL = "<?php echo Router::url('/', true);?>dk/brugere/hjul/"+apikey+'/'+userId+'/natalwheel/yes';
            }
        } else {
            userId = selectedUser;
            //userHoroscopeWheelURL = "<?php //echo $this->Url->build([ 'controller' => 'users', 'action' => 'wheel']); ?>?apikey="+apikey+'&uid='+userId+'&task=natalwheel';
            userHoroscopeWheelURL = "<?php echo $this->Url->build([ 'controller' => 'users', 'action' => 'wheel']); ?>/"+apikey+'/'+userId+'/natalwheel';
            if (langg == 'dk' || langg == 'da') {
                userHoroscopeWheelURL = "<?php echo Router::url('/', true);?>dk/brugere/hjul/"+apikey+'/'+userId+'/natalwheel';
            }
        }
        $.ajax({
            type:"POST",
            /*async: false,*/
            cache : false,
            url: userHoroscopeWheelURL,
            success: function(data) {
                if(selectedUser.indexOf('_') != -1){
                    var splittedData = selectedUser.split('_');
                    var userId = splittedData[1];
                    var imagepath = "<?php echo Router::url('/', true); ?>user-personal-horoscope/anotherPerson_"+userId+".onlywheel.jpg";
                    $('#my-personal-horoscope-wheel-link').html('<div id="myPersonalHorospcopeImage"><img src="'+imagepath+'" title="Personal Horoscope wheel" alt="Personal Horoscope wheel" id="my-personal-horoscope-wheel"></div>');
                } else {
                    $('#my-personal-horoscope-wheel-link').html(selectedUser+".onlywheel.jpg");
                    var imagepath = "<?php echo Router::url('/', true); ?>user-personal-horoscope/"+selectedUser+".onlywheel.jpg";
                    $('#my-personal-horoscope-wheel-link').html('<div id="myPersonalHorospcopeImage"><img src="'+imagepath+'" title="Personal Horoscope wheel" alt="Personal Horoscope wheel" id="my-personal-horoscope-wheel"></div>');
                }
                <?php //$this->request->session()->delete('profileUpdated'); ?>
                $('#pleaseWaitLoader').css('display', 'none');
                <?php //$this->request->session()->write('profileUpdated', 0); ?>
            }
        });
    }

</script>