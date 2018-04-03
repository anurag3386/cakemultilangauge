<?php
    /*if( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' ) {
        echo '<pre>'; print_r($this->request->session()->read()); die;
    }*/
?>
<?php use Cake\Routing\Router; ?>
<?php use Cake\Cache\Cache; ?>
<?php echo $this->Html->css('innerpage-custom'); ?>
<?= $this->Html->script ('innerpage-custom'); ?>
<?php // Generate wheel if user profile is updated
    if (!empty($this->request->session()->read('profileUpdated'))) {
        $recordUpdated = $this->request->session()->read('profileUpdated');
} ?>


<?php $session = $this->request->session();
    $dashboardUser = !empty($session->read('selectedUser')) ? $session->read('selectedUser') : $session->read('Auth.User.id');
    $dashboardUser = (!empty($session->read('user_id')) && ($dashboardUser=='')) ? $session->read('user_id') : $dashboardUser;
    $subscriptionActivated = '';
    $selectedLanguage = !empty($this->request->session()->read('locale')) ? $this->request->session()->read('locale') : 'en';
    if (!empty($subscriptionActivateOrNot)) {
        $subscriptionActivated = 'yes';
    }
    if (!empty($session->read('anotherPersonAdded'))) {
        $session->delete('anotherPersonAdded');
        $newCreatedPerson = explode('_', $session->read('selectedUser'));
        $user_id = $newCreatedPerson[1];
    } else if (!empty($session->read('selectedUser'))) {
        $user_id = $session->read('selectedUser');
        if (strpos($user_id, '_') !== false) {
            $uid = explode('_', $user_id);
            $user_id = $uid[1];
        }
    } else {
        $user_id = !empty($session->read('Auth.User.id')) ? $session->read('Auth.User.id') : $session->read('user_id');
    }
    
    $exist = 0;
    if ($_SERVER['SERVER_NAME'] == 'localhost') {
        if (!empty($anotherPersonDetail)) {
            $horoscop_wheel_path = $_SERVER['DOCUMENT_ROOT'].'/astrowow/webroot/user-personal-horoscope/anotherPerson_'.$user_id.'.onlywheel.jpg';
            $horoscop_wheel_path_pdf = $_SERVER['DOCUMENT_ROOT'].'/astrowow/webroot/user-personal-horoscope/anotherPerson_'.$user_id.'.natalwheel.pdf';
        } else {
            $horoscop_wheel_path = $_SERVER['DOCUMENT_ROOT'].'/astrowow/webroot/user-personal-horoscope/'.$user_id.'.onlywheel.jpg';
            $horoscop_wheel_path_pdf = $_SERVER['DOCUMENT_ROOT'].'/astrowow/webroot/user-personal-horoscope/'.$user_id.'.natalwheel.pdf';
        }
    } else {
        if (!empty($anotherPersonDetail)) {
            $horoscop_wheel_path = $_SERVER['DOCUMENT_ROOT'].'/webroot/user-personal-horoscope/anotherPerson_'.$user_id.'.onlywheel.jpg';
            $horoscop_wheel_path_pdf = $_SERVER['DOCUMENT_ROOT'].'/webroot/user-personal-horoscope/anotherPerson_'.$user_id.'.natalwheel.pdf';
        } else {
            $horoscop_wheel_path = $_SERVER['DOCUMENT_ROOT'].'/webroot/user-personal-horoscope/'.$user_id.'.onlywheel.jpg';
            $horoscop_wheel_path_pdf = $_SERVER['DOCUMENT_ROOT'].'/webroot/user-personal-horoscope/'.$user_id.'.natalwheel.pdf';
        }
    }
    //chmod($horoscop_wheel_path, 0777);

    $lang_id = !empty($anotherPersonDetail) ? $anotherPersonDetail['language_id'] : $loggedInUserProfileDetails['language_id'];
    if ($lang_id == 2) {
        $selectedLang = 'dk';
    } else {
        $selectedLang = 'en';
    }
    if (!empty($session->read('locale'))) {
        if ($session->read('locale') == 'da') {
            $selectedLang = 'dk';
        } else {
            $selectedLang = $session->read('locale');
        }
    }

    if (file_exists($horoscop_wheel_path)) {
        if (!empty($anotherPersonDetail)) {
            $personal_horoscope_wheel_image = Router::url('/', true).'user-personal-horoscope/anotherPerson_'.$user_id.'.onlywheel.jpg';
        } else {
            $personal_horoscope_wheel_image = Router::url('/', true).'user-personal-horoscope/'.$user_id.'.onlywheel.jpg';
        }
        $exist = 1;
    } else {
        $personal_horoscope_wheel_image = Router::url('/', true).'img/calendar-loading.gif';
    }

    //chmod($personal_horoscope_wheel_image, 0777);

    $check_pdf_file = 0;
    if (file_exists($horoscop_wheel_path_pdf)) {
        $check_pdf_file = 1;
    }


    /*if( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' ) {
        //echo 'Here => '.$exist.' => '.$check_pdf_file; die;
    }*/

    if (!$exist || !$check_pdf_file) {
        $recordUpdated = !empty($session->read('selectedUser')) ? $session->read('selectedUser') : $session->read('Auth.User.id');
    }


    ?>
    <article id="post-1159" class="post-1159 page type-page status-publish hentry" >            
    <div class="entry-content">
    <div class="dashboardClass">
	<div class="et_pb_section  et_pb_section_0 et_pb_with_background et_section_regular inner_bg">
		<div class=" et_pb_row et_pb_row_0">
            <?= $this->Flash->render() ?>
			<div class="et_pb_column et_pb_column_4_4  et_pb_column_0">
				<div class="et_pb_code et_pb_module  et_pb_code_0">
					<div class="dvContainer">
					 <div class="classRight">
                            <div id="astrowell" style="margin-bottom: 15%;">
                                <div class="wellHeader">
                                    <h3><?= __('My AstroPage');?></h3>
                                    <select name="astro_page_user" id="astro_page_user" class="down up">
                                        <?php foreach ($usersName as $key => $uList) {
                                            $selectedPerson = '';
                                            if ($key == $session->read('selectedUser')) {
                                                $selectedPerson = 'selected';
                                            }
                                        ?>
                                            <option value='<?= $key; ?>' <?= $selectedPerson; ?>><?= $uList; ?></option>
                                        <?php } ?>
                                    </select>
                                    <?php echo $this->Html->link($this->Html->image('../images/edit-icon2.png').__(' Edit'), ['controller'=>'Users', 'action'=>'edit-profile'], ['escape' => false]); 
                                    echo '<br/>';
                                    echo $this->Html->link(__('Add New Profile'), ['controller'=>'users', 'action'=>'add-another-person'], ['class'=>'addNew']); ?>
                                </div>
                                <div class="wellBody">
                                    <div class="cell col2" style="text-align: center;">
                                        <div id="dvResult"></div><br>
                                        <div class="main_circle">
                                            <?php $apikey = md5('astrowow.com'); ?>
                                            <?php if (isset($anotherPersonDetail) && !empty($anotherPersonDetail)) {
                                                $another_per_id = explode('_', $session->read('selectedUser'));
                                                $another_per_id = $another_per_id[1];
                                            ?>
                                                <a href="<?= Router::url('/', true); ?>user-personal-horoscope/anotherPerson_<?= $another_per_id; ?>.natalwheel.pdf" target='_blank' id='my-personal-horoscope-wheel-link'>
                                            <?php } else { ?>
                                                <a href="<?= Router::url('/', true); ?>user-personal-horoscope/<?= $user_id; ?>.natalwheel.pdf" target='_blank' id='my-personal-horoscope-wheel-link'>
                                            <?php } ?>
                                                <?php if (!empty($exist)) { ?>
                                                    <div id="myPersonalHorospcopeImage">
                                                        <?php echo $this->Html->image ($personal_horoscope_wheel_image, ['title' => 'Personal Horoscope wheel', 'alt' => 'Personal Horoscope wheel', 'id'=>'my-personal-horoscope-wheel']);
                                                        ?>
                                                    </div>
                                                <?php } else { ?>
                                                    <div id="default_wheel">
                                                    <!-- <div id="myPersonalHorospcopeImage"> -->
                                                        <?php echo $this->Html->image ($personal_horoscope_wheel_image, ['title' => 'Personal Horoscope wheel', 'alt' => 'Personal Horoscope wheel', 'id'=>'my-personal-horoscope-wheel']);
                                                        ?>
                                                    </div>  
                                                <?php } ?>
                                            </a>
                                        </div>

                                        <?php if (!empty($exist) || $recordUpdated) { ?>
                                            <div class="click-expand">
                                        <?php } else { ?>
                                            <div class="click-expand" style="display: none;">
                                        <?php } ?>
                                        <?php 
                                            if (isset($anotherPersonDetail) && !empty($anotherPersonDetail)) {
                                                $another_per_id = explode('_', $session->read('selectedUser'));
                                                $another_per_id = $another_per_id[1]; ?>
                                                <a href="<?= Router::url('/', true); ?>user-personal-horoscope/anotherPerson_<?= $user_id; ?>.natalwheel.pdf" target='_blank', id='expand-horoscope-wheel'><?= __('Click to Expand'); ?></a>
                                        <?php } else { ?>
                                                <a href="<?= Router::url('/', true); ?>user-personal-horoscope/<?= $user_id; ?>.natalwheel.pdf" target='_blank', id='expand-horoscope-wheel'><?= __('Click to Expand'); ?></a>
                                        <?php } ?>
                                        </div>
                                    </div>
                                    <div class="cell col1">
                                        <br>
                                        <h3><?= __('General Info');?></h3>
                                        <?php if (!empty($BirthDetails['date']) || !empty($anotherPersonDetail['dob'])) { ?>
                                            <div class="user_general_info">
                                                <label><strong><?= __('Birth Date');?>: </strong></label>
                                                <span id="user_dob">
                                                    <?php
                                                        if (!empty($anotherPersonDetail['dob'])) {
                                                            echo $newDate = date("M d, Y", strtotime($anotherPersonDetail['dob']));
                                                        } else {
                                                            echo $newDate = date("M d, Y", strtotime($BirthDetails['date']));
                                                            //<\s\up>S</\s\up>
                                                        }
                                                    ?>
                                                </span>
                                                <div id="edit_dob_calendar"></div>
                                            </div>
                                        <?php } ?>

                                        <?php if (!empty($BirthDetails['time']) || !empty($anotherPersonDetail['time'])) { ?>
                                            <div class="user_general_info">
                                                <label><strong><?= __('Birth Time');?>: </strong></label>
                                                <span id="user_birth_time">
                                                    <?php
                                                        if (!empty($anotherPersonDetail['time'])) {
                                                            echo $time = date('g:i A', strtotime($anotherPersonDetail['time']));
                                                        } else {
                                                            echo $time = date('g:i A', strtotime($BirthDetails['time']));
                                                        }
                                                    ?>
                                                </span>
                                            </div>
                                        <?php } ?>

                                        <?php if (!empty($cityname['city'])) { ?>
                                            <div class="user_general_info">
                                                <label><strong><?= __('City'); ?>: </strong></label>
                                                <span id="user_birth_city">
                                                    <?php 
                                                        $city = '';
                                                        $city .= !empty ($cityname['city']) ? ucfirst ($cityname['city']) : '';
                                                        $city .= !empty ($countryname['name']) ? ', '.ucfirst ($countryname['name']) : '';
                                                        echo $city;
                                                    ?>
                                                </span>
                                            </div>
                                        <?php } ?>
                                        <?php if (!empty($TimeZoneData['timezone'])) { ?>
                                            <div class="user_general_info">
                                                <label><strong><?= __('Time Zone'); ?>: </strong></label>
                                                <span id="user_birth_timezone">
                                                    <?php
                                                        $timezoneName = '';
                                                        if (isset($countryname['name']) && !empty($countryname['name'])) {
                                                            $twoLetterAbbreviationOfCountry = $this->Custom->countryShortCode ($countryname['name']);
                                                            if (!empty($twoLetterAbbreviationOfCountry)) {
                                                                $arr = DateTimeZone::listIdentifiers(DateTimeZone::PER_COUNTRY, $twoLetterAbbreviationOfCountry);
                                                                foreach ($arr as $key => $value) {
                                                                    if (!empty($value)) {
                                                                        $timezoneName = $value;
                                                                    }
                                                                }
                                                            } else {
                                                                $timezoneName = $this->Custom->userTimeZoneName ($TimeZoneData['timezone']);
                                                            }
                                                        } else {
                                                            $timezoneName = $this->Custom->userTimeZoneName ($TimeZoneData['timezone']);
                                                        }
                                                        echo $timezoneName." (".$TimeZoneData['timezone'].")";
                                                    ?>
                                                </span>
                                            </div>                      
                                        <?php } ?>
                                        <?php if (!empty($TimeZoneData['summerreff'])) { ?>
                                            <div class="user_general_info">
                                                <label><strong><?= __('Summer Time'); ?>: </strong></label>
                                                <span id="user_birth_summertime">
                                                    <?php echo $TimeZoneData['summerreff']; ?>
                                                </span>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <br class="clearfix">
                                    <br class="clearAll">
                                    <?php if ((strtolower($session->read('Auth.User.role')) != 'elite')) { ?>
                                        <div class="become-customer"><?php echo $this->Html->link (__('Become an Elite Customer'), ['controller' => 'elite-users', 'action' => 'index']); ?>
                                        </div>
                                        <br><br>
                                        <br class="clearAll">
                                    <?php } ?>
                                        <br>
                                </div>
                            </div>

                            <?php if (!empty($latestPosts)) { ?>
                            <div class="mini-block" >
                                <div class="sml-common-box box-shadow margin0" style="margin-top: 10px">
                                    <h2><?= 'Mini Blog';?></h2>
                                    <div class="content">
                                        <ul class="micriblo">
                                            <?php foreach ($latestPosts as $value) { ?>
                                            <li>
                                                <div class="dvImg">
                                                    <?= $this->Html->image('/uploads/mini-blog/thumb/sm/'.$value['image'], ['title' => $value['title'], 'alt' => $value['title']]); ?>                   
                                                </div>
                                                <div class="dvText">
                                                    <a class="readmore" href="<?= Router::url(['controller' => 'mini-blogs', 'action'=> 'post', $value['slug'] ]) ?>">
                                                        <?= $value['title']; ?>
                                                    </a>
                                                    <p>
                                                        <?= implode(' ', array_slice(explode(' ', strip_tags($value['description'])), 0, 30)); ?><br>
                                                        <a class="readmore" href="<?= Router::url(['controller' => 'mini-blogs', 'action'=> 'post', $value['slug'] ]); ?>">
                                                            <?= __('Read More').'...'; ?>
                                                        </a>
                                                    </p>
                                                </div>
                                            </li>
                                            <hr>
                                            <?php } ?>
                                            <li class="last">
                                                <?= $this->Html->link(__('View All'), ['controller' => 'mini-blogs', 'action' => 'index'], ['class' => 'title']) ?>
                                                
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
						<div class="classLeft">
							<div id="astrocalendar">
								<h3><?= __('Horoscope Calendar');?></h3>
                                <?php
                                    if (!empty($subscriptionActivateOrNot)) {
                                        $removecalendarConfirmationMsg = __("Are you sure you want to unsubscribe from calendar?");
                                        echo "<span class='unsubscribe'>".$this->Html->link (__('Unsubscribe'), ['controller' => 'users', 'action' => 'remove-from-subscription'], ['onclick' => "return confirm ('".$removecalendarConfirmationMsg."');"])."</span>";
                                    } else {
                                        echo "<span class='unsubscribe'>".$this->Html->link (__('Subscription'), ['action'=>'subscribe'], ['target'=>'_blank', 'id'=>'subcription'])."</span>";
                                    }
                                ?>
								<br class="clearAll">
								<p><?= __('Below you can see short term and long term influences day by day on your personal birth chart. You can use the questions and answers to clarify issues that arise.');?></p>
								<form id="frmGetCalendarView" name="frmGetCalendarView" method="GET" action="">
									<input type="hidden" id="action" name="action" value="gettrends">
									<input type="hidden" id="ukey" name="ukey" value="MTI=">
									<input type="hidden" id="lang" name="lang" value="en">
									<input type="hidden" id="pDate" name="pDate" value="11-04-2016">
    							</form>
        						<br>

                                <?php if (empty($subscriptionActivateOrNot)) { ?>
    								<div class="calender-new clearfix">
                                        <?php $currentDate = strtotime(date('d-m-Y')); ?>
                                    </div>
                                    <?php } else { ?>
                                        <div class="calender-new clearfix" id="activeSubscriptionCalendar">
                                            <div id="loading" style="width: 100%; text-align: center;">
                                                <p><?= __('Loading subscription calendar'); ?></p>
                                                <?php echo $this->Html->image('calendar-loading.gif', ['style'=>'height:70px;', 'alt'=>'Calendar loader image', 'title'=>'Calendar loader image']); ?>
                                            </div>
                                        </div>
                                    <?php } ?>

                                <!-- Showing daily prediction / influences start -->
                                <div id="dvLoader" name="dvLoader" style="width: 8%; height: 8%; margin: auto; align-content: center; display: none;"></div>
                                <div id="dvCalendarView" name="dvCalendarView" class="et_pb_module et_pb_accordion et_pb_accordion_0" style="display: none;"></div>

                                <div id="accordion" tabindex='1'></div>
                                <!-- Showing daily prediction / influences End -->

                                <div id="transitOfSelectedDate"></div>
                                <div class="purchasedReports">
                                    <h3><?= __('Your Purchased Reports'); ?></h3>
                                    <?php /*pr ($perchasedReport); die;*/ if (!$perchasedReport->isEmpty()) { ?>

                                        <div id="no-more-tables">
                                            <table class="col-md-12 table-bordered table-striped table-condensed cf">
                                                <thead class="cf">
                                                    <tr>
                                                        <th class="numeric"><?= __('Product Name');?></th>
                                                        <th class="numeric"><?= __('Order Id'); //__('Category Name');?></th>
                                                        <th class="numeric"><?= __('Price');?></th>
                                                        <th class="numeric"><?= __('Order Date');?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($perchasedReport as $reports) { ?>
                                                    <tr>
                                                        <td data-title="<?= __('Product Name');?>" class="numeric">
                                                            <?php if (file_exists(WWW_ROOT.'reports/var/spool/'.$reports['id'].'.bundle.pdf')) { ?>
                                                                <a href="<?= Router::url('/', true); ?>reports/var/spool/<?= $reports['id'].'.bundle.pdf'; ?>" target="_blank">
                                                                    <?php echo __d('default', $reports['products']['name']); ?>
                                                                </a>
                                                            <?php } else {
                                                                echo __d('default', $reports['products']['name']);
                                                            } ?>
                                                        </td>
                                                        <td data-title="<?= __('Category Name');?>" class="numeric">
                                                            <?php //echo __d('default', $reports['categories']['name']); ?>
                                                            <?= $reports['payer_order_id']; ?>
                                                        </td>
                                                        <td data-title="<?= __('Price');?>" class="numeric">
                                                            <?php echo $reports['currencies']['symbol'].number_format($reports['price'], 2, '.', ''); ?>
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
                                                  <?php echo $this->Paginator->prev(' < '); ?>
                                                  <?php echo $this->Paginator->numbers(); ?>
                                                  <?php echo $this->Paginator->next(' > '); ?>
                                                </ul>
                                            </div>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <div id="no-more-tables">
                                            <table class="col-md-12 table-bordered table-striped table-condensed cf">
                                                <thead class="cf">
                                                    <tr>
                                                        <th class="numeric"><?= __('Product Name'); ?></th>
                                                        <th class="numeric"><?= __('Category Name'); ?></th>
                                                        <th class="numeric"><?= __('Price'); ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td data-title='' class="numeric" colspan="3" style="text-align: center; padding-left: 0px !important;">
                                                            <?= __('Currently you do not have any purchased reports.'); ?>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php } ?>
                                </div>
                                
                                <div id="dvLoader" name="dvLoader" style="width: 8%; height: 8%; margin: auto; align-content: center; display: none;">
                                </div>    
                                <div id="dvCalendarView" name="dvCalendarView" class="et_pb_module et_pb_accordion et_pb_accordion_0" style="display: none;"></div>
                            </div>
                        </div>
                    </div>
		<div class="et_pb_column et_pb_column_4_4  et_pb_column_2">
			</div> <!-- .et_pb_column -->
                    <script type="text/html" id="tmpCalendarView">
                        <div class="et_pb_module et_pb_toggle" data-class="cssClass">
                            <h5 class="et_pb_toggle_title" data-content="Title"></h5>
                            <div class="et_pb_toggle_content clearfix">
                                <p data-content="Description"></p>
                                <div class="cell">
                                    <strong data-content="Quetion1"></strong>
                                    <p data-content="Answer1"></p>
                                </div>
                                <div class="cell">
                                    <strong data-content="Quetion2"></strong>
                                    <p data-content="Answer2"></p>
                                </div>
                                <div class="cell">
                                    <strong data-content="Quetion3"></strong>
                                    <p data-content="Answer3"></p>
                                </div>
                            </div> <!-- .et_pb_toggle_content -->
                        </div> <!-- .et_pb_toggle -->
                    </script>

                    <script type="text/html" id="tmpEmptyCalendarView">
                        <div class="et_pb_module et_pb_toggle et_pb_toggle_close et_pb_accordion_item_0">
                            <h5 class="et_pb_toggle_title" data-content="Message"></h5>
                        </div>
                    </script>

                    <script type="text/html" id="tmpBuyYearPassText">
                        <div class="et_pb_module et_pb_toggle et_pb_toggle_open et_pb_accordion_item_0">
                            <h5 class="et_pb_toggle_title">Buy year pass, to get unlimited access for Calender</h5>
                            <div class="et_pb_toggle_content clearfix">
                                <p><?php echo $this->Html->link ('Click here to buy', []); ?></p>
                                <div data-content="Message"></div>
                            </div>
                        </div>
                    </script>
                </div> <!-- .et_pb_code -->
			</div> <!-- .et_pb_column -->
					
		</div> <!-- .et_pb_row -->
		<?php
            if (!empty($BirthDetails) || !empty($anotherPersonDetail)) {
                $userBirthDate = !empty($anotherPersonDetail) ? $anotherPersonDetail['dob'] : $BirthDetails['date'];
            }
            $dobdate = date("m/d/Y", strtotime($userBirthDate));
        ?>
	</div>
    <input type='hidden' id='current_page' />
    <input type='hidden' id='show_per_page' />
<?php
    if ($selectedLanguage == 'dk') {
        $MonthNameArray = array('January' => 'januar', 'February' => 'februar', 'March' => 'marts', 'April' => 'April',
            'May' => 'Kan', 'June' => 'juni', 'July' => 'juli', 'August' => 'august', 'September' => 'september', 'October' => 'oktober', 'November' => 'november', 'December' => 'december');
    } else {
        $MonthNameArray = array('January' => 'January', 'February' => 'February', 'March' => 'March', 'April' => 'April',
            'May' => 'May', 'June' => 'June', 'July' => 'July', 'August' => 'August', 'September' => 'September', 'October' => 'October', 'November' => 'November', 'December' => 'December');
    }
?>
</div>
<div class="our_reports_section">
    <?php echo $this->Element('products/our_reports');?>
</div>
</div>
</article>

<script>
    $(document).ready(function () {
        var d = new Date();
        var curr_date = d.getDate();
        var curr_month = d.getMonth()+1;
        var curr_year = d.getFullYear();
        var selectedDate = curr_date+'-'+curr_month+'-'+curr_year;
        var currentDateClendar = curr_month+'-'+curr_date+'-'+curr_year;
        var langg = "<?php echo $this->request->session()->read('locale'); ?>";
        /*var importantTransitURL = "<?php //echo $this->Url->build([ 'controller' => 'users', 'action' => 'transit']);?>/"+selectedDate+'/'+langg;*/
        var importantTransitURL = "<?php echo $this->Url->build([ 'controller' => 'users', 'action' => 'transit']);?>";

        setTimeout(function(){
            var userOnDash = "<?php echo $dashboardUser; ?>";
            if(userOnDash.indexOf('_') != -1){
                var splittedData = userOnDash.split('_');
                var userId = splittedData[1];
                var imagepath = "<?php echo Router::url('/', true); ?>user-personal-horoscope/anotherPerson_"+userId+".onlywheel.jpg";
                $('#my-personal-horoscope-wheel-link').html('<div id="myPersonalHorospcopeImage"><img src="'+imagepath+'" title="Personal Horoscope wheel" alt="Personal Horoscope wheel" id="my-personal-horoscope-wheel"></div>');
            } else {
                $('#my-personal-horoscope-wheel-link').html(userOnDash+".onlywheel.jpg");
                var imagepath = "<?php echo Router::url('/', true); ?>user-personal-horoscope/"+userOnDash+".onlywheel.jpg";
                $('#my-personal-horoscope-wheel-link').html('<div id="myPersonalHorospcopeImage"><img src="'+imagepath+'" title="Personal Horoscope wheel" alt="Personal Horoscope wheel" id="my-personal-horoscope-wheel"></div>');
            }
            $('.click-expand').css('display', 'block');
        },500);


        var someVarName1 = localStorage.getItem("calendarData");
        // To get important transitions
        //getImportantTransitions (importantTransitURL);
        var apikey = "<?php echo md5('astrowow.com'); ?>";
        var subsCal = '<?php echo $subscriptionActivated; ?>';
        if (subsCal != ''/* && someVarName1 == null*/) {
            var calendarPrediction = "<?php echo $this->Url->build([ 'controller' => 'users', 'action' => 'subscriptionCalendarPm']);?>";
            $.ajax({
                /*async : false,*/
                type:"POST",
                url: calendarPrediction,
                success: function(data) {
                    console.log(data);
                    //localStorage.setItem("calendarData", data);

                    showCalendarData(data);
                }
            });
        }/*
        else {
            showCalendarData(someVarName1);
        }*/


        function showCalendarData(data) {
            //alert (data); return false;
                    $('#activeSubscriptionCalendar').html(data);
                    $('tr[class="Icon"]').each( function () {
                        var IconExistOrBlank = $(this).children('td').html();
                        if (IconExistOrBlank == '') {
                            $(this).children('td').html('&nbsp;');
                        }
                    });
                    // Highlighted selected date
                    $('a[class="lnkDaily"]').each( function () {
                        var dateOnCalendarLink = $(this).attr('id');
                        if (dateOnCalendarLink == currentDateClendar) {
                            $(this).parent('td').addClass('calendarActiveClass');
                        } else {
                            $(this).parent('td').removeClass('calendarActiveClass');
                        }
                    });

                    /**
                     * Show current month calendar by default
                     * Created By : kingslay <kingslay@123789.org>
                     * Created Date : January 17, 2017
                     * Modified Date : January 18, 2017
                     */
                    if (langg != '' && langg == 'da') {
                        var currentMonth = "<?php echo __d('default', strtolower(date ('F'))); ?>"; // Current month name
                    } else {
                        var currentMonth = "<?php echo strtolower (date ('F')); ?>"; // Current month name
                    }
                    $(".tablinks").each(function() {
                        var maxllimit = $('li.previousMonth').attr('myAttr');
                        if (maxllimit == '') {
                            maxllimit = 13;
                        } else {
                            maxllimit = parseInt(maxllimit)+1;
                        }
                        var monthYe = $(this).text();
                        monthYe = monthYe.split(' - ');
                        
                        if (monthYe[0].trim().toLowerCase() == currentMonth) {
                            $(this).addClass('active');
                            $(this).css ('display', '');
                            var refVal = $(this).attr('ref');
                            $('div.tabify04-content[ref='+refVal+']').show ();
                            var previous = next = refVal;
                            next++;
                            previous--;
                            if (previous > 0) {
                                $('li.previousMonth').attr('ref', previous);
                                //$('li.previousMonth').attr('onclick', "tabbed_calendar("+previous+", "+maxllimit+")");
                                $('li.previousMonth').removeClass('visibilityHidden');;
                            } else {
                                $('li.previousMonth').removeAttr('ref');
                                $('li.previousMonth').removeAttr('onclick');
                                $('li.previousMonth').addClass('visibilityHidden');
                            }
                            if (next < maxllimit) {
                                $('li.nextMonth').attr('ref', next);
                                $('li.nextMonth').attr('onclick', "tabbed_calendar("+next+", "+maxllimit+")");
                                $('li.nextMonth').removeClass('visibilityHidden');
                            } else {
                                $('li.nextMonth').removeAttr('ref');
                                $('li.nextMonth').removeAttr('onclick');
                                $('li.nextMonth').addClass('visibilityHidden');
                            }
                        }
                    });
        }

        var dailyPredictionURL = "<?php echo $this->Url->build([ 'controller' => 'users', 'action' => 'dailytransit']); ?>?apikey="+apikey+'&date='+selectedDate+'&lan='+langg;

        if (subsCal == '') {
            var imageSrc = '<?php echo $this->Html->image('calendar-loading.gif', ['style'=>'height:50px; margin-left: 50%;', 'alt'=>'Data loader image', 'title'=>'Data loader image']); ?>';
            $('#accordion').html(imageSrc);
        }
        // To get daily prediction data / influences
        //getDailyPredictionData (dailyPredictionURL, currentDateClendar);
        setTimeout(function(){
            getDailyPredictionData (dailyPredictionURL, currentDateClendar);
        },500);
        $('#main-header').focus();

        var selecteddatePredictionURL = "<?php echo $this->Url->build([ 'controller' => 'users', 'action' => 'mycalendarIcons']); ?>?apikey="+apikey+'&date='+selectedDate;
        $.ajax({
            /*async : false,*/
            type:"POST",
            url: selecteddatePredictionURL,
            success: function(data) {
                //console.log (data);
            }
        });

        // Show calendar current month on page load
        var loggedUserID = "<?php echo $this->request->session()->read('Auth.User.id'); ?>";
        // END

        /* Insert important transition and show current date transition */
        var userImportantTransitionsInsert = "<?php echo $this->Url->build([ $selectedLanguage, 'controller' => 'users', 'action' => 'inserttransit']); ?>/";
        
        $.ajax({
            /*async : false,*/
            type:"POST",
            url: userImportantTransitionsInsert,
            success: function(data) {
                // To display current date transitions
                getImportantTransitions (importantTransitURL);
            }
        });
        // END

        /** Show last 7days prediction including current date for unsubscribed (calendar) users
         * Created By : Kingslay <kingslay@123789.org>
         * Created Date : Jan. 12, 2017
         * Modified Date : Jan. 13, 2017
         */
        if (subsCal == '') {
            var currentTime = new Date();
            // First Date Of the month
            var startDateFrom = new Date(currentTime.getFullYear(),currentTime.getMonth(),1); //'Mon Mar 20 2017 00:00:00 GMT+0530 (IST)';
            //alert (startDateFrom);
            // Last Date Of the Month
            var startDateTo = new Date(currentTime.getFullYear(),currentTime.getMonth() +1,0);
            $('.calender-new').datepicker({startDate: startDateFrom, endDate: '+1m'/*startDateTo*/, format: 'yyyy-mm-dd', viewMode: -1, todayHighlight: true}).datepicker("setDate", '0').on ('changeDate', function (e) {
                var selectedCalendarDate = e.format(0, "yyyy-mm-dd"); // selected date
                var todayDate = currentTime.getDate();
                todayDate = (todayDate < 10) ? '0'+todayDate : todayDate;
                var Curmonth = currentTime.getMonth()+1;
                Curmonth = (Curmonth < 10) ? '0'+Curmonth : Curmonth; // current date
                var today = currentTime.getFullYear()+'-'+Curmonth+'-'+todayDate;
                var dateDiff =  Math.floor(( Date.parse(selectedCalendarDate) - Date.parse(today) ) / 86400000); // Difference between current date and selected date 
                if (dateDiff < 1) { // if date is selected from last 7 days including current date
                    var selectedDate = '';
                    if (selectedCalendarDate) {
                        selectedDate = selectedCalendarDate.split('-');
                        selectedDate = selectedDate[2]+'-'+selectedDate[1]+'-'+selectedDate[0];
                    }
                    var apikey = "<?php echo md5('astrowow.com'); ?>";
                    var dailyPredictionUrl = "<?php echo $this->Url->build([ 'controller' => 'users', 'action' => 'dailytransit']); ?>?apikey="+apikey+'&date='+selectedDate;
                    var imageSrc = '<?php echo $this->Html->image('calendar-loading.gif', ['style'=>'height:50px;', 'alt'=>'Data loader image', 'title'=>'Data loader image', 'class' => 'dailyPredictionLoader']); ?>';
                    $('#accordion').html(imageSrc);
                    // To get daily prediction data / influences
                    //getDailyPredictionData (dailyPredictionUrl); // get selected date prediction
                    setTimeout(function(){
                        getDailyPredictionData (dailyPredictionUrl); // get selected date prediction
                    },500);
                } else {
                    if (langg == 'dk' || langg == 'da') {
                        $('#accordion').html('<h3 style="width: 100%"><?= $this->Html->link (("Klik her"), ["controller" => "users", "action" => "subscribe"], ["style" => ["color: #e4165b"]]); ?> <?php echo html_entity_decode(utf8_decode(utf8_encode( "for at se vigtige indflydelser for hele året for kun kr.185"))); ?></h3>');
                    } else {
                        $('#accordion').html('<h3 style="width: 100%"><?= $this->Html->link ("Click Here", ["controller" => "users", "action" => "subscribe"], ["style" => ["color: #e4165b"]]); ?> to see trends and important transitions for a whole year for just $19.95</h3>');
                        //to see trends and important transitions for a whole year for just $19.95
                    }
                }
                $('#accordion').focus();
            });
        }
        // END

        // If wheel is not exist for existing user
        var wheelExist = "<?php echo $exist; ?>";
        if (!wheelExist) {
            var selectedUserDash = "<?php echo $dashboardUser; ?>";
            setTimeout(function(){
                generateWheelOnPageLoad (selectedUserDash);
            },500);
        }

        // Generate wheel for new user
        var newUserRegistration = "<?php !empty($this->request->session()->read('newUser')) ? $this->request->session()->read('newUser') : ''; ?>";
        if (newUserRegistration) {
            //var selectedUserDash = "<?php //echo $dashboardUser; ?>";
            setTimeout(function(){
                generateWheelOnPageLoad (newUserRegistration, 'newRegistration');
                /*$('#default_wheel').css('display', 'none');
                $('#myPersonalHorospcopeImage').css('display', 'block');*/
                <?php $this->request->session()->delete('newUser'); ?>
            },500);
        }

        /**
         * Used to generate horoscope wheel if birth detail is updated
         * Created By : Kingslay <kingslay@123789.org>
         * Created Date : Feb. 09, 2017
         */
        var dataUpdated = "<?php echo $detailUpdated = (isset($recordUpdated) && !empty($recordUpdated)) ? $recordUpdated : false; ?>";
        if (dataUpdated) {
            setTimeout(function(){
                generateWheelOnUpdateProfile (dataUpdated);
                <?php //$exist = $check_pdf_file = 0; ?>
            },500);
            //generateWheelOnUpdateProfile (dataUpdated);
        }

        $('#astro_page_user').on('change', function () {
        	//var imageSrc = '<?php //echo $this->Html->image('calendar-loading.gif', ['style'=>'height:50px;', 'alt'=>'Horoscope Wheel loader', 'title'=>'Horoscope Wheel', 'class' => 'dailyPredictionLoader']); ?>';
        	//$('#myPersonalHorospcopeImage').html(imageSrc);
	        var selectedUser = $(this).val();
	        var holdSelectedUserInSession = base_url+'users/selected-user-on-dash/'+selectedUser;
	        holdSelectedUser (holdSelectedUserInSession, langg);
    	});


    });

    /**
     * Used to generate wheel
     * Created By : Kingslay <kingslay@123789.org>
     * Created Date : Feb. 09, 2017
     */
    function generateWheelOnUpdateProfile (selectedUser) {
        $('.click-expand').css('display', 'none');
        var langg = "<?php echo $lang = !empty($this->request->session()->read('locale')) ? $this->request->session()->read('locale') : 'en'; ?>";
        var loaderpath = "<?php echo Router::url('/', true); ?>img/calendar-loading.gif";
            $('#my-personal-horoscope-wheel-link').html('<img src="'+loaderpath+'" title="Personal Horoscope wheel Loader" alt="Personal Horoscope wheel Loader" id="my-personal-horoscope-wheel"></div>');
        var apikey = "<?php echo md5('astrowow.com'); ?>";
        var userId = userHoroscopeWheelURL = '';
        if(selectedUser.indexOf('_') != -1){
            var splittedData = selectedUser.split('_');
            userId = splittedData[1];
            userHoroscopeWheelURL = "<?php echo $this->Url->build([ 'controller' => 'users', 'action' => 'wheel']); ?>/"+apikey+'/'+userId+'/natalwheel/yes';
            if (langg == 'dk' || langg == 'da') {
                userHoroscopeWheelURL = "<?php echo Router::url('/', true);?>dk/brugere/hjul/"+apikey+'/'+userId+'/natalwheel/yes';
            }
        } else {
            userId = selectedUser;
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
                    //chmod 777 imagepath;
                    $('#my-personal-horoscope-wheel-link').html('<div id="myPersonalHorospcopeImage"><img src="'+imagepath+'" title="Personal Horoscope wheel" alt="Personal Horoscope wheel" id="my-personal-horoscope-wheel"></div>');
                } else {
                    $('#my-personal-horoscope-wheel-link').html(selectedUser+".onlywheel.jpg");
                    var imagepath = "<?php echo Router::url('/', true); ?>user-personal-horoscope/"+selectedUser+".onlywheel.jpg";
                    //chmod 777 imagepath;
                    $('#my-personal-horoscope-wheel-link').html('<div id="myPersonalHorospcopeImage"><img src="'+imagepath+'" title="Personal Horoscope wheel" alt="Personal Horoscope wheel" id="my-personal-horoscope-wheel"></div>');
                }
                $('.click-expand').css('display', 'block');
                <?php $this->request->session()->delete('profileUpdated'); ?>
                $('.success').css('display', 'block');
                $('#pleaseWaitLoader').css('display', 'none');
                <?php $this->request->session()->write('profileUpdated', 0); ?>
            }
        });
    }

    /**
     * Used to generate wheel
     * Created By : Kingslay <kingslay@123789.org>
     * Created Date : Feb. 09, 2017
     */
    function generateWheelOnPageLoad (selectedUser, userStatus/*userStatus=''*/) {
        $('.click-expand').css('display', 'none');
        var langg = "<?php echo $lang = !empty($this->request->session()->read('locale')) ? $this->request->session()->read('locale') : 'en'; ?>";
        if (!userStatus && userStatus != '') {
            var loaderpath = "<?php echo Router::url('/', true); ?>img/calendar-loading.gif";
            $('#my-personal-horoscope-wheel-link').html('<img src="'+loaderpath+'" title="Personal Horoscope wheel Loader" alt="Personal Horoscope wheel Loader" id="my-personal-horoscope-wheel"></div>');
        }
        var apikey = "<?php echo md5('astrowow.com'); ?>";
        var userId = userHoroscopeWheelURL = '';
        if(selectedUser.indexOf('_') != -1){
            var splittedData = selectedUser.split('_');
            userId = splittedData[1];
            userHoroscopeWheelURL = "<?php echo $this->Url->build([ 'controller' => 'users', 'action' => 'wheel']); ?>/"+apikey+'/'+userId+'/natalwheel/yes';
            if (langg == 'dk' || langg == 'da') {
                userHoroscopeWheelURL = "<?php echo Router::url('/', true);?>dk/brugere/hjul/"+apikey+'/'+userId+'/natalwheel/yes';
            }
        } else {
            userId = selectedUser;
            userHoroscopeWheelURL = "<?php echo $this->Url->build([ 'controller' => 'users', 'action' => 'wheel']); ?>/"+apikey+'/'+userId+'/natalwheel';
            if (langg == 'dk' || langg == 'da') {
                userHoroscopeWheelURL = "<?php echo Router::url('/', true);?>dk/brugere/hjul/"+apikey+'/'+userId+'/natalwheel';
            }
        }
        $.ajax({
            type:"POST",
            /*async: true,*/
            cache : false,
            url: userHoroscopeWheelURL,
            success: function(data) {
                if(selectedUser.indexOf('_') != -1){
                    var splittedData = selectedUser.split('_');
                    var userId = splittedData[1];
                    var imagepath = "<?php echo Router::url('/', true); ?>user-personal-horoscope/anotherPerson_"+userId+".onlywheel.jpg";
                    //chmod 777 imagepath;
                    $('#my-personal-horoscope-wheel-link').html('<div id="myPersonalHorospcopeImage"><img src="'+imagepath+'" title="Personal Horoscope wheel" alt="Personal Horoscope wheel" id="my-personal-horoscope-wheel"></div>');
                    $('.click-expand').css('display', 'block');
                } else {
                    $('#my-personal-horoscope-wheel-link').html(selectedUser+".onlywheel.jpg");
                    var imagepath = "<?php echo Router::url('/', true); ?>user-personal-horoscope/"+selectedUser+".onlywheel.jpg";
                    //chmod 777 imagepath;
                    $('#my-personal-horoscope-wheel-link').html('<div id="myPersonalHorospcopeImage"><img src="'+imagepath+'" title="Personal Horoscope wheel" alt="Personal Horoscope wheel" id="my-personal-horoscope-wheel"></div>');
                    $('.click-expand').css('display', 'block');
                }
            }
        });
    }

    /**
     * Get daily prediction / influences based on date selection
     * Created By : Kingslay <kingslay@123789.org>
     * Created Date : Nov. 28, 2016
     * Modified Date : Nov. 30, 2016
     */
    $('.transitDate').on('click', function () {
        var selectedDate = $(this).attr('id'); //'11-12-2016'; m-d-Y
        $('.transitDate').removeClass('active');
        $('#'+selectedDate).addClass('active');
        var apikey = "<?php echo md5('astrowow.com'); ?>";
        var dailyPredictionUrl = "<?php echo $this->Url->build([ 'controller' => 'users', 'action' => 'dailytransit']); ?>?apikey="+apikey+'&date='+selectedDate;
        var imageSrc = '<?php echo $this->Html->image('calendar-loading.gif', ['style'=>'height:50px; margin-left: 50%;', 'alt'=>'Data loader image', 'title'=>'Data loader image']); ?>';
        $('#accordion').html(imageSrc);
        // To get daily prediction data / influences
        setTimeout(function(){
            getDailyPredictionData (dailyPredictionUrl);
        },500);
    });

    function getSelectedUserDataFromDropdownList (url, selectedUser) {
        $.ajax({
            type:"POST",
            url: url,
            /*async: false,*/
            dataType: 'json',
            success: function(data) {}
        });
    }

    /**
     * function call on selected calendar date for get prediction / influences for particular date
     * Created By : Kingslay <kingslay@123789.org>
     * Created Date : Dec. 09, 2016
     * Modified Date : Dec. 09, 2016
     */
    function button_onClick (date) {
        var selectedDate1 = date.split('-'); //m-d-Y
        selectedDate = selectedDate1[1]+'-'+selectedDate1[0]+'-'+selectedDate1[2]; //d-m-Y
        var apikey = "<?php echo md5('astrowow.com'); ?>";
        var dailyPredictionURL = "<?php echo $this->Url->build([ 'controller' => 'users', 'action' => 'dailytransit']); ?>?apikey="+apikey+'&date='+selectedDate;
        var imageSrc = '<?php echo $this->Html->image('calendar-loading.gif', ['style'=>'height:50px;', 'alt'=>'Data loader image', 'title'=>'Data loader image', 'class' => 'dailyPredictionLoader']); ?>';
        $('#accordion').html(imageSrc);
        // To get daily prediction data / influences
        setTimeout(function(){
            getDailyPredictionData (dailyPredictionURL, date, 'active');
        },500);
    }

    /**
     * Hide arrow sign from Calendar accordian paragraph (No transitions available)
     * Created By : Kingslay <kingslay@123789.org>
     * Created Date : Dec. 19, 2016
     */
    $('#accordion').on('click', function () {
        $('p.ui-state-active').removeClass('ui-state-active');
    });

            

</script>

<style type="text/css">
    .floatL { width: 100%; }
    .Text { text-align: center; }
    .Text > td, .Icon > td { border: 0 none !important; }
    .message {text-align: center;}
    .error {color : red;}
    .success { color: green; }
    .calendar_content {position: absolute;}
    p.ui-accordion-header {cursor: default}
    .firstChild {display: block !important;}
    .tablinks.active { text-align: center; width: 80%; }
    .nextMonth, .previousMonth { width: 10% !important; cursor: pointer; }
    .nextMonth > img, .previousMonth > img { height: 20px; vertical-align: middle; }
    .tablinks.active { font-size: 18px; font-weight: bold; }
    .datepicker-switch { pointer-events: none; }
</style>