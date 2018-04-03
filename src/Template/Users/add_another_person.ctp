<?php echo $this->Html->css(['innerpage-custom']); ?>
<?php use Cake\Routing\Router; ?>
<?= $this->Html->css ('innerpage-custom'); ?>
<?= $this->Html->script ('innerpage-custom'); ?>
<?php
    if (!empty($this->request->session()->read('profileUpdated'))) {
        $personAdded = $this->request->session()->read('profileUpdated');
    }
    /*if (!empty($this->request->session()->read('anotherPersonAdded'))) {
        $personAdded = $this->request->session()->read('anotherPersonAdded');
    }*/

    $fname = $lname = $gender = $dob = $birthhours = $birthminutes = $birthcountry = $birthcity = '';
    $anotherPersonDetails = !empty($this->request->session()->read('addAnotherPersonDetails')) ? $this->request->session()->read('addAnotherPersonDetails') : '';
    if (!empty($anotherPersonDetails) && is_array($anotherPersonDetails)) {
        $fname = !empty($anotherPersonDetails['txtFirstName']) ? $anotherPersonDetails['txtFirstName'] : '';
        $lname = !empty($anotherPersonDetails['txtLastName']) ? $anotherPersonDetails['txtLastName'] : '';
        $gender = !empty($anotherPersonDetails['rdoGender']) ? $anotherPersonDetails['rdoGender'] : '';
        $dob = !empty($anotherPersonDetails['user_dob_edit']) ? $anotherPersonDetails['user_dob_edit'] : '';
        $birthhours = !empty($anotherPersonDetails['birthhour']) ? $anotherPersonDetails['birthhour'] : '';
        $birthminutes = !empty($anotherPersonDetails['birthminute']) ? $anotherPersonDetails['birthminute'] : '';
        $birthcountry = !empty($anotherPersonDetails['ddBirthCountry']) ? $anotherPersonDetails['ddBirthCountry'] : '';
        $birthcity = !empty($anotherPersonDetails['ddBirthCity']) ? $anotherPersonDetails['ddBirthCity'] : '';
    }
?>
<?= $this->Flash->render() ?>
<div class="et_pb_row et_pb_row_0">
    <div class="common-box box-shadow editprofileBox">
        <div class="common-box-one">
            <h2 class="profileName"> 
                <?php echo $this->Html->image ('../images/icon-astrology-software02.png', ['title'=>'Add another person', 'alt'=>'Add another person']); ?>
                <span style='vertical-align: middle;'><?= __('Add Another Person'); ?></span>
                <span class="back_myastro">
                    ( <?= $this->Html->link (__('Back to My AstroPage'),['controller'=>'users', 'action'=>'dashboard']); ?> )
                </span>
                <?php /* <div class="del">
                    <?= $this->Html->link (__('Back to My Astropage'),['controller'=>'users', 'action'=>'dashboard'], ['class'=>'btn btn-red']); ?>
                </div> */ ?>
            </h2>
            <div class="paddingT">
                <div class="error" style="font-size: 16px; margin-top: 1%; text-align: center;"></div>
                <div id="dvBirthDetail" class="my-account-user-birth-detail">
                    <form id="frmUserBirthDetail" name="frmUserBirthDetail" action="" method="post">
                        <table width="100%">
                            <tbody>
                                <tr>
                                    <td colspan="2"><div class="clear"></div></td>
                                </tr>
                                <tr>
                                    <td width="30%"><label for="textfield">*<?= __('First Name'); ?>:</label></td>
                                    <td><input name="txtFirstName" maxlength="60" placeholder="<?= __('First Name');?>" id="txtFirstName" class="validate[required]" value="<?= $fname ?>" type="text"></td>
                                </tr>
                                <tr>
                                    <td colspan="2"><div class="clear"></div></td>
                                </tr>
                                <tr>
                                    <td width="30%"><label for="textfield">*<?= __('Last Name'); ?> :</label></td>
                                    <td><input name="txtLastName" id="txtLastName" placeholder="<?= __('Last Name'); ?>" maxlength="60" class="validate[required]" value="<?= $lname ?>" type="text"></td>
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
                                                    if (!empty($gender)) {
                                                        if ($key == $gender) {
                                                            $checked = ' checked';
                                                        }
                                                    } else {
                                                        if ($value == __('Male')) {
                                                            $checked = ' checked';
                                                        }
                                                    }
                                                ?>
                                                    <li>
                                                        <input name="rdoGender" id="rdo<?= $value; ?>" value="<?= $key; //$genderValue = !empty($gender) ? $gender : $key; ?>" class="validate[required]" type="radio"<?= $checked; ?>>
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
                                    <td width="30%"><label for="textfield">*<?= __('Birth Date'); ?>:</label></td>
                                    <td>
                                        <input type='text' class="validate[required]" placeholder="<?= __('Birth Date'); ?>" readonly='readonly' id='user_dob_edit' name='user_dob_edit' value = "<?= $dob ?>" />
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2"><div class="clear"></div></td>
                                </tr>
                                <tr>
                                    <td width="30%"><label for="textfield">*<?= __('Birth Time'); ?>:</label></td>
                                    <td>
                                        <select id="birthhour" name="birthhour" class="down validate[required]" >
                                            <?php
                                                $hours = array(0=>'00 a.m. (00.00)', 1=>'01 a.m. (01.00)', 2=>'02 a.m. (02.00)', 3=>'03 a.m. (03.00)', 4=>'04 a.m. (04.00)', 5=>'05 a.m. (05.00)', 6=>'06 a.m. (06.00)', 7=>'07 a.m. (07.00)', 8=>'08 a.m. (08.00)', 9=>'09 a.m. (09.00)', 10=>'10 a.m. (10.00)', 11=>'11 a.m. (11.00)', 12=>'12 p.m. (12.00)', 13=>'01 p.m. (13.00)', 14=>'02 p.m. (14.00)', 15=>'03 p.m. (15.00)', 16=>'04 p.m. (16.00)', 17=>'05 p.m. (17.00)', 18=>'06 p.m. (18.00)', 19=>'07 p.m. (19.00)', 20=>'08 p.m. (20.00)', 21=>'09 p.m. (21.00)', 22=>'10 p.m. (22.00)', 23=>'11 p.m. (23.00)');
                                            ?>
                                                <option value=''><?= __('Select Hour(s)'); ?></option>
                                            <?php
                                                for ($h=0; $h<count($hours); $h++) {
                                                    $selectedHours = '';
                                                    if (!empty($birthhours) && ($birthhours == $h)) {
                                                        //if ($birthhours == $h) {
                                                            $selectedHours = ' selected';
                                                        //}
                                                    }
                                            ?>
                                                    <option value="<?php echo $h; ?>"<?= $selectedHours; ?>><?php echo $hours[$h]; ?></option>
                                                <?php } ?>
                                        </select>
                                        <select id="birthminute" name="birthminute" class="down validate[required]">
                                                <option value=''><?= __('Select Minute(s)'); ?></option>
                                                <?php 
                                                    for ($min=00; $min<60; $min++) {
                                                    $selectedMin = '';
                                                    if (!empty($birthminutes) && ($birthminutes == $min)) {
                                                        $selectedMin = ' selected';
                                                    }
                                                ?>
                                                    <option value="<?php echo $min; ?>"<?= $selectedMin; ?>><?php echo $min; ?></option>
                                                <?php } ?>
                                        </select>
                                        <input id="knowntime" name="knowntime" value="0" type="hidden"></td>
                                </tr>
                                <tr>
                                    <td colspan="2"><div class="clear"></div></td>
                                </tr>
                                <tr>
                                    <td width="30%"><label for="select4">*<?= __('Birth Country'); ?>:</label></td>
                                    <td>
                                        <select name="ddBirthCountry" id="ddBirthCountry" class="down validate[required]">
                                            <option value=''><?= __('Select Country'); ?></option>
                                            <?php
                                                foreach ( $Countries as $countryList ) {
                                                    $selectedCountry = '';
                                                    if (!empty($birthcountry) && ($birthcountry == $countryList['id'])) {
                                                        $selectedCountry = ' selected';
                                                    }
                                            ?>
                                                <option value="<?php echo $countryList['id']; ?>"<?= $selectedCountry; ?>><?php echo $countryList['name']; ?></option>
                                            <?php } ?>
                                       </select>
                                   </td>
                                </tr>
                                <tr>
                                    <td colspan="2"><div class="clear"></div></td>
                                </tr>
                                <tr>
                                    <td width="30%"><label for="textfield">*<?= __('Birth City'); ?>:</label></td>
                                    <td>
                                        <select name="ddBirthCity" id="ddBirthCity" class="down validate[required]">
                                            <option value=''><?= __('First select country'); ?></option>
                                            <?php 
                                                if (!empty($Cities) && is_array($Cities)) {
                                                    foreach ( $Cities as $cityList ) {
                                                        $selectedCity = '';
                                                        if (!empty($birthcity) && ($birthcity == $cityList['id'])) {
                                                            $selectedCity = ' selected';
                                                        }  
                                            ?>
                                                        <option value="<?php echo $cityList['id']; ?>"<?= $selectedCity; ?>><?php echo $cityList['city']; ?></option>
                                            <?php } } ?>
                                       </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2"><div class="clear"></div></td>
                                </tr>
                                
                                <tr>
                                    <td></td>
                                    <td>
                                        <input type="submit" name="btnUpdateBirthDetail" id="btnUpdateBirthDetail" value="<?= __('Add Person');?>" class="sign-up-text sign-up-button input-purple" style="cursor: pointer;">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready (function () {
        /**
         * To get cities list based on selected country
         * Created By : Kingslay <kingslay@123789.org>
         * Created Date : Nov 09, 2016
         */
        $('#ddBirthCountry').on('change', function () {
            var country_id = $(this).val();
            var langg = "<?php echo $lang = !empty($this->request->session()->read('locale')) ? $this->request->session()->read('locale') : 'en'; ?>";
            if (langg == 'dk' || langg == 'da') {
                var countryCityURL = "<?php echo Router::url('/', true);?>dk/brugere/få-country-byer/"+country_id;
            } else {
                var countryCityURL = "<?php echo $this->Url->build([ 'controller' => 'users', 'action' => 'get-country-cities']);?>/"+country_id;
            }
            getCitiesListBasedOnSelectedCountry (countryCityURL);
        });


        /**
         * Used to generate horoscope wheel if birth detail is updated
         * Created By : Kingslay <kingslay@123789.org>
         * Created Date : Feb. 09, 2017
         */
        var dataUpdated = "<?php echo $personAdded = (isset($personAdded) && !empty($personAdded)) ? $personAdded : false; ?>";
        if (dataUpdated) {
            var selectedUserDash = 'anotherPerson_'+dataUpdated;
            generateWheelOnPageLoad (selectedUserDash);
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
            //userHoroscopeWheelURL = "<?php //echo $this->Url->build([ 'controller' => 'users', 'action' => 'wheel']); ?>/"+apikey+'/'+userId+'/natalwheel&aper=yes';

            userHoroscopeWheelURL = "<?php echo $this->Url->build([ 'controller' => 'users', 'action' => 'wheel']); ?>/"+apikey+'/'+userId+'/natalwheel/yes';
            if (langg == 'dk' || langg == 'da') {
                userHoroscopeWheelURL = "<?php echo Router::url('/', true);?>dk/brugere/hjul/"+apikey+'/'+userId+'/natalwheel/yes';
            }

        }/* else {
            userId = selectedUser;
            userHoroscopeWheelURL = "<?php //echo $this->Url->build([ 'controller' => 'users', 'action' => 'wheel']); ?>?apikey="+apikey+'&uid='+userId+'&task=natalwheel';
        }*/
        $.ajax({
            type:"POST",
            async: true,
            cache : false,
            url: userHoroscopeWheelURL,
            success: function(data) { 
                <?php $this->request->session()->delete('profileUpdated'); ?>
            }
        });
    }


</script>