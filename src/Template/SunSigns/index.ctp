<?php use Cake\Routing\Router;?>
<?php use Cake\Cache\Cache;?>
<?php use Cake\I18n\I18n;?>
<?php
	/*if( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' ) {
		echo $_COOKIE['showPopup']; die;
	}*/
?>
<?php $locale = substr(strtolower( I18n::locale() ), 0, 2) ;?>
<style>
	.tabs {
		position: relative;  
 		min-height: 200px; 
		clear: both;
		margin: 150px 0;
	}
	.tab {
		float: left;
	}
	.tab label {
		background: #eee; 
		padding: 10px; 
		border: 1px solid #ccc; 
		margin-left: -1px; 
		position: relative;
		left: 1px; 
	}
	.tab [type=radio] {
		display: none;   
	}
	.content {
		position: absolute;
		top: 28px;
		left: 0;
		background: white;
		right: 0;
		bottom: 0;
		padding: 20px;
		border: 1px solid #ccc; 
		height: 500px;
	}
	[type=radio]:checked ~ label {
		background: white;
		border-bottom: 1px solid white;
		z-index: 2;
	}
	[type=radio]:checked ~ label ~ .content {
		z-index: 1;
	}
</style>
<script>
	$(document).ready(function(){
		<?php if(!empty($this->request->params['pass'][1]) && isset($this->request->params['pass'][1])) { 
			$period = $this->request->params['pass'][1];
			switch ($period) {
				case 'daily-horoscope': 
				?>
				$( "#tab-1" ).prop( "checked", true );
				<?php 
				
				break;

				case 'weekly-horoscope': 
				?>
				$( "#tab-2" ).prop( "checked", true );
				<?php 
				
				break;

				case 'monthly-horoscope':     
				?>
				$( "#tab-3" ).prop( "checked", true );
				<?php 	

				break;

				case 'yearly-horoscope':     
				?>
				$( "#tab-4" ).prop( "checked", true );
				<?php 	
				
				break;

				case 'characteristics':     
				?>
				$( "#tab-5" ).prop( "checked", true );
				<?php 	
				
				break;
				case 'celebrity':     
				?>
				$( "#tab-6" ).prop( "checked", true );
				<?php 	
				
				break;
				case 'archive':     
				?>
				$( "#tab-7" ).prop( "checked", true );
				<?php 	
				
				break;

				default:                  ?>
				$( "#tab-1" ).prop( "checked", true );
				<?php 
				
				break;
			}
		}
		?>

	})
</script>


<?php
$today = date('Y-m-d');
$tomorrow = date("Y-m-d", strtotime("Tomorrow"));
$yesterday = date("Y-m-d", strtotime("Yesterday"));
$currentWeekSunday = date("Y-m-d", strtotime("last week sunday"));
$currentWeek = $this->Custom->getCurrentWeek();

//echo "here=>".$currentWeek;

$previousWeekSunday = $this->Custom->getPreviousWeekSunday();
$nextWeekSunday = $this->Custom->getNextWeekSunday();
$previousWeek = $this->Custom->getPreviousWeek();
$nextWeek = $this->Custom->getNextWeek();
$currentMonth = $this->Custom->getCurrentMonth();
$currentMonthFirstDay = date('Y-m-01');
$previousMonth = $this->Custom->getPreviousMonth();
$nextMonth = $this->Custom->getNextMonth();

//$previousMonthFirstDay = date('Y-m-01',strtotime('last month')) ;
$previousMonthFirstDay = date('Y-m-01',strtotime('first day of previous month')) ;

//$nextMonthFirstDay = date('Y-m-01',strtotime('next month')) ;
//$currentMonthFirstDay = date("Y-m-01"); // 2012-01-30
$nextMonthFirstDay = date("Y-m-01", strtotime("$currentMonthFirstDay +1 month"));
/*if( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' ) {
	$huhuuhtoday = date("Y-m-01"); // 2012-01-30
	$mynext_month = date("Y-m-01", strtotime("$huhuuhtoday +1 month"));
	echo $nextMonthFirstDay.' => '.$mynext_month; die;
}*/
$currentYear =  $this->Custom->getCurrentYear();
$previousYear = $this->Custom->getPreviousYear();
$nextYear = $this->Custom->getNextYear();
$year = date('Y') ;
$currentYearFirstDay  =  date('Y-m-d', mktime(0,0,0,1,1,$year));
$year = date('Y') - 1 ;
$previousYearFirstDay =  date('Y-m-d', mktime(0,0,0,1,1,$year));
$year = date('Y') + 1 ;
$nextYearFirstDay =  date('Y-m-d', mktime(0,0,0,1,1,$year));
			//$url = $this->Url->build([ "controller" => "SunSigns", "action" => "get-prediction"]);
$url = 'get-prediction';
$img = $this->Html->image("calendar-loading.gif", ['height' => 50, 'width' => 50, 'style' => ['margin-left:47%']]);

$popupError = $this->request->session()->read('popupError');

?>

 <?php
 /** evernote api **/
//require __DIR__ . '/evernote/src/autoload.php';

/**
 * Authorization Tokens are created by either:
 * [1] OAuth workflow: https://dev.evernote.com/doc/articles/authentication.php
 * or by creating a 
 * [2] Developer Token: https://dev.evernote.com/doc/articles/authentication.php#devtoken
 */
//$token = 'S=s1:U=93cbb:E=1644533c5b6:C=15ced8298d0:P=1cd:A=en-devtoken:V=2:H=b2bfe6747a9f489792268f3bc1483b65';

/** Understanding SANDBOX vs PRODUCTION vs CHINA Environments
 *
 * The Evernote API 'Sandbox' environment -> SANDBOX.EVERNOTE.COM 
 *    - Create a sample Evernote account at https://sandbox.evernote.com
 * 
 * The Evernote API 'Production' Environment -> WWW.EVERNOTE.COM
 *    - Activate your Sandboxed API key for production access at https://dev.evernote.com/support/
 * 
 * The Evernote API 'CHINA' Environment -> APP.YINXIANG.COM
 *    - Activate your Sandboxed API key for Evernote China service access at https://dev.evernote.com/support/ 
 *      or https://dev.yinxiang.com/support/. For more information about Evernote China service, please refer 
 *      to https://dev.evernote.com/doc/articles/bootstrap.php
 *
 * For testing, set $sandbox to true; for production, set $sandbox to false and $china to false; 
 * for china service, set $sandbox to false and $china to true.
 * 
 */
//$sandbox = true;
//$china   = false;

//$client = new \Evernote\Client($token, $sandbox, null, null, $china);

?>

<article id="post-55" class="post-55 page type-page status-publish hentry">
	<div class="entry-content">
		<div class="home_sunsign_banner">
			<div class="et_pb_section et_pb_fullwidth_section  et_pb_section_0 et_section_regular">
				<?php echo $this->element('sunsigns');?>
			</div>
		</div>

		<div class="sunsign_prediction">
		<div class="et_pb_section  et_pb_section_0 et_pb_with_background et_section_regular freeHoroscope2">
			<div class=" et_pb_row et_pb_row_0">
				<div class="et_pb_column et_pb_column_4_4  et_pb_column_0">
					<div class="et_pb_text et_pb_module et_pb_bg_layout_light et_pb_text_align_left  et_pb_text_0">
						<h2><?php echo __('FREE HOROSCOPES');?></h2>
						
						<?php //if( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' ) { ?>
						<div>
							<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
							<!-- Astrowow- Horoscope -Internal page -->
							<ins class="adsbygoogle"
							     style="display:inline-block;width:728px;height:90px"
							     data-ad-client="ca-pub-5485211874854826"
							     data-ad-slot="2854610287"></ins>
							<script>
							(adsbygoogle = window.adsbygoogle || []).push({});
							</script>

							<!-- <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script> -->
							<!-- Astrowow- Horoscope -Internal page -->
							<!-- <ins class="adsbygoogle"
							     style="display:inline-block;width:728px;height:90px"
							     data-ad-client="ca-pub-5485211874854826"
							     data-ad-slot="2854610287"></ins>
							<script>
							(adsbygoogle = window.adsbygoogle || []).push({});
							</script> -->
							
						</div>
						<?php //} ?>

					</div> <!-- .et_pb_text -->
				</div> <!-- .et_pb_column -->	
			</div> <!-- .et_pb_row -->
			<div class=" et_pb_row et_pb_row_1">
				<div class="et_pb_column et_pb_column_4_4  et_pb_column_1">
					<div class="et_pb_code et_pb_module  et_pb_code_0">
						<div id="signWrapper"></div>
						<?php $sign = $sunsigns->id;?>
						<?php if( $locale == 'en' ) {
							$language = "en";
						} elseif( $locale == 'da') {
							$language = "dk";
						}
						?>
						<div id="sign3" class="signDetails" style="display: block;">
							<?php echo $this->Html->image('/uploads/sunsigns/'.$sunsigns->icon, ['class' => 'signImage', 'alt' =>__d('default',$sunsigns->name)]);?>
							<?php //if( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' ) { ?>
								<div class="sunsignTitle"><h1><?php echo __d('default',$sunsigns->name).' : '.$additionalText; ?></h1></div>
							<?php /*} else { ?>
								<div class="sunsignTitle"><h3><?php echo __d('default',$sunsigns->name).' : '.$additionalText; ?></h3></div>
							<?php }	*/ ?>
							<div class="sunsignDate"><h4><?php echo __d('default', $sunsigns->date); ?></h4></div>
							<?php echo $this->Html->image('/uploads/sunsigns/'.$sunsigns->avatar, ['class' => 'signAvatar', 'style' => 'height:120px !important', 'alt' => __d('default',$sunsigns->name).' '.__('Avatar Image')]);?>
							<div class="tabs">
								<div class="tab">
									<input type="radio" id="tab-1" name="tab-group-1" checked>
									<label for="tab-1">
										<?php //echo $this->Html->link(__('Today'), ['controller' => 'SunSigns',strtolower($sunsigns->name),__('daily-horoscope')]); ?>
										<?php if( $locale == 'da') { ?>
									  		<a href="<?php echo Router::url('/', true).'dk/'.__d('default', 'sun-signs').'/'.__d('default', strtolower($sunsigns->name)).'/'. __('daily-horoscope').'#dag-fanen'?>"><?= __('Today');?></a>
										<?php } else { ?>
									  		<a href="<?php echo Router::url('/', true).__d('default', 'sun-signs').'/'.__d('default', strtolower($sunsigns->name)).'/'. __('daily-horoscope').'#today-tab'?>"><?= __('Today');?></a>
										<?php } ?>
									</label>
									<div class="content">
										<div class="date"><span id="date"> <?php echo date("M j, Y");?></span></div>
										<div id="daily-prediction" class="mainContent">
											<div style="text-align:center">
												<?php echo $this->Html->image("calendar-loading.gif", ['height' => 50, 'width' => 50	]);?>
											</div>
										</div>
										<?php /*if ( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' ) { ?>
										<div class="socialIcons" style="background-color: #ffffff;">
										<?php } else {*/ ?>
										<div class="socialIcons">
										<?php //} ?>
											<?php //if ( $_SERVER['REMOTE_ADDR'] != '103.254.97.13' && $_SERVER['REMOTE_ADDR'] != '103.254.97.14' && $_SERVER['REMOTE_ADDR'] != '103.248.117.12' ) { ?>
												<a href="https://www.youtube.com/watch?v=76wipJ7Wk_U" target="_blank" class="seeVideo"><b>See video</b></a><br>
											<?php //} ?>
												<?php
                                                
												echo "<span>";
                                               ?><a href="javascript:;" onclick="openFbPopUp('<?php echo Router::Url($this->request->here, true)?>','<?php echo Router::url("/",true)."img/social-sunsign-icon/".strtolower($sunsigns->name)."_new.jpg"?>','Free Horoscope - <?php echo ucwords($sunsigns->name);?>','daily-prediction')" /><i class="fa fa-facebook fa-lg"></i></a>
                                               <?php 
												//echo $this->SocialShare->fa(  'facebook', Router::Url($this->request->here, true) , ['text' => 'Free Horoscope - '.ucwords($sunsigns->name), 'image' => Router::url('/',true).'uploads/sunsigns/'.$sunsigns->avatar, 'description' => $content, 'icon_class' => 'fa fa-facebook fa-lg'] );
												echo "</span>";
												echo "<span class='social-icon'>";
												echo  $this->SocialShare->fa( 'twitter', null, ['text' => 'Free Horoscope - '.ucwords($sunsigns->name)."\n", 'icon_class' => 'fa fa-twitter fa-lg']) ; 
												echo "</span>";
												echo "<span class='social-icon'>";
												echo  $this->SocialShare->fa( 'gplus', null, [ 'icon_class' => 'fa fa-google-plus fa-lg' ]) ; 
												echo "</span>";
												//if ($_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12') {
													echo '<span class="social-icon">
															<a href="https://api.addthis.com/oexchange/0.8/forward/evernote/offer?url='.Router::url($this->request->here, true).'&title=Free Horoscope - '.ucwords($sunsigns->name).'&ct=1" target="_blank">';
													echo $this->Html->image('ele.png', ['alt' => 'Evernote']);
													echo '</a></span>';
												//}
											/*} else { ?>
												<!-- <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-595f1a129307d60e"></script> -->
												<!-- Go to www.addthis.com/dashboard to customize your tools -->
												<!-- <div class="addthis_inline_share_toolbox_xcsj"></div> -->
												<a href="https://api.addthis.com/oexchange/0.8/forward/email/offer?url=https://www.astrowow.com/sun-signs/aries/daily-horoscope&title=Astrowow&ct=1" target="_blank">
													<img src="https://cache.addthiscdn.com/icons/v3/thumbs/32x32/email.png" border="0" alt="Email"/>
												</a>
												<a href="https://api.addthis.com/oexchange/0.8/forward/facebook/offer?url=https://www.astrowow.com/sun-signs/aries/daily-horoscope&title=Astrowow&ct=1" target="_blank">
													<img src="https://cache.addthiscdn.com/icons/v3/thumbs/32x32/facebook.png" border="0" alt="Facebook"/>
												</a>
												<a href="https://api.addthis.com/oexchange/0.8/forward/google_plusone_share/offer?url=https://www.astrowow.com/sun-signs/aries/daily-horoscope&title=Astrowow&ct=1" target="_blank">
													<img src="https://cache.addthiscdn.com/icons/v3/thumbs/32x32/google_plusone_share.png" border="0" alt="Google+"/>
												</a>
												<a href="https://api.addthis.com/oexchange/0.8/forward/twitter/offer?url=https://www.astrowow.com/sun-signs/aries/daily-horoscope&title=Astrowow&ct=1" target="_blank">
													<img src="https://cache.addthiscdn.com/icons/v3/thumbs/32x32/twitter.png" border="0" alt="Twitter"/>
												</a>
												<a href="https://api.addthis.com/oexchange/0.8/forward/evernote/offer?url=https://www.astrowow.com/sun-signs/aries/daily-horoscope&title=Astrowow&ct=1" target="_blank">
													<img src="https://cache.addthiscdn.com/icons/v3/thumbs/32x32/evernote.png" border="0" alt="Evernote"/>
												</a>

											<?php }*/
											
											
											?>

											<?php /*if ( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' ) { ?>
												<br><br>
												<a href="https://www.youtube.com/watch?v=76wipJ7Wk_U" target="_blank" class="seeVideo"><b>See video</b></a>
											<?php }*/ ?>

										</div>

										<?php 
										$user_id = $this->request->session()->read('user_id');
		  
										if( !isset($user_id) || empty($user_id)):
											?>
										<div class="bottomContent">
											<?= __('Go beyond sun signs and get your free personal horoscope astropage, Absolutely free! ');?>
											<?php 
											echo $this->Html->link(__('Click here to sign up'), ['controller' => 'Users', 'action' => 'sign-up']);
											?>
										</div>
										<?php 
										endif;
										?>
										<div id='daily' class="nextPrev">
											<div id="yesterday" class="btn btn-red prevBtn"><?= __('Yesterday');?></div>
											<div id="today" class="btn btn-red" style="display: none"><?= __('Today');?></div>
											<div id="tomorrow" class="btn btn-red nextBtn"><?= __('Tomorrow');?></div>
										</div>

									</div> 
								</div>
								
								<div class="tab">
									<input type="radio" id="tab-2" name="tab-group-1">
									<label for="tab-2">

										<?php //echo $this->Html->link(__('Weekly'), ['controller' => 'SunSigns',strtolower($sunsigns->name),__('weekly-horoscope')])?>

										<?php if( $locale == 'da') { ?>
									  		<a href="<?php echo Router::url('/', true).'dk/'.__d('default', 'sun-signs').'/'.__d('default', strtolower($sunsigns->name)).'/'. __('weekly-horoscope').'#ugentlig-fanen'?>"><?= __('Weekly');?></a>

									  	<?php } else { ?>
									  		<a href="<?php echo Router::url('/', true).__d('default', 'sun-signs').'/'.__d('default', strtolower($sunsigns->name)).'/'. __('weekly-horoscope').'#weekly-tab'?>"><?= __('Weekly');?></a>
										<?php } ?>
									</label>
									
									<div class="content">
										<!--div class="name"><strong><?php //echo $sunsigns->name;?></strong></div-->
										<div class="date"><span id="week"><?php echo $currentWeek;?></span></div>
										<div id="weekly-prediction" class="mainContent"><div style="text-align:center"><?php echo $this->Html->image("calendar-loading.gif", ['height' => 50, 'width' => 50	]);?></div></div>
										<div class="socialIcons">
												<a href="https://www.youtube.com/watch?v=4ImKjNWj6xE" target="_blank" class="seeVideo"><b>See video</b></a><br>

											<?php  
											echo "<span>";
                                               ?><a href="javascript:;" onclick="openFbPopUp('<?php echo Router::Url($this->request->here, true)?>','<?php echo Router::url("/",true)."img/social-sunsign-icon/".strtolower($sunsigns->name)."_new.jpg"?>','Free Horoscope - <?php echo ucwords($sunsigns->name);?>','weekly-prediction')" /><i class="fa fa-facebook fa-lg"></i></a>
                                               <?php 
												//echo $this->SocialShare->fa(  'facebook', Router::Url($this->request->here, true) , ['text' => 'Free Horoscope - '.ucwords($sunsigns->name), 'image' => Router::url('/',true).'uploads/sunsigns/'.$sunsigns->avatar, 'description' => $content, 'icon_class' => 'fa fa-facebook fa-lg'] );
											echo "</span>";
                                            echo "<span class='social-icon'>";
											echo  $this->SocialShare->fa( 'twitter', null, ['text' => 'Free Horoscope - '.ucwords($sunsigns->name)."\n"]) ;
											echo "</span>";
											echo "<span class='social-icon'>";
											echo  $this->SocialShare->fa( 'gplus', null, [ 'icon_class' => 'fa fa-google-plus fa-lg' ]) ; 
											echo "</span>";
											//if ($_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12') {
												echo '<span class="social-icon">
														<a href="https://api.addthis.com/oexchange/0.8/forward/evernote/offer?url='.Router::url($this->request->here, true).'&title=Free Horoscope - '.ucwords($sunsigns->name).'&ct=1" target="_blank">';
												echo $this->Html->image('ele.png', ['alt' => 'Evernote']);
												echo '</a></span>';
											//}


											?>
										</div>

										<?php //if( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' ) { ?>
											<?php if($this->request->session()->read('locale') == 'da') { ?>
                                        		<div class="cons-adrn"><a href="<?= Router::url('dk/konsultation', true); ?>" title="<?= __('Konsultationer med Adrian'); ?>" target="_blank"><?= __('Konsultationer med Adrian'); ?></a></div>
                                    		<?php } ?>
										<?php //} ?>

										<div class="nextPrev">
											<div id="previous-week" class="btn btn-red prevBtn"><?= __('Previous Week');?></div>
											<div id="this-week" style="display:none" class="btn btn-red"><?= __('This Week');?></div>
											<div id="next-week" class="btn btn-red nextBtn"><?= __('Next Week');?></div>
										</div>
									</div> 
								</div>


								
								<div class="tab">
									<input type="radio" id="tab-3" name="tab-group-1">
									<label for="tab-3">
										<?php //echo $this->Html->link(__('Monthly'), ['controller' => 'SunSigns',strtolower($sunsigns->name),__('monthly-horoscope')]) ?>
										<?php if( $locale == 'da') { ?>
									  		<a href="<?php echo Router::url('/', true).'dk/'.__d('default', 'sun-signs').'/'.__d('default', strtolower($sunsigns->name)).'/'. __('monthly-horoscope').'#manedligt-fanen'?>"><?= __('Monthly');?></a>
									  	<?php } else { ?>
									  		<a href="<?php echo Router::url('/', true).__d('default', 'sun-signs').'/'.__d('default', strtolower($sunsigns->name)).'/'. __('monthly-horoscope').'#monthly-tab'?>"><?= __('Monthly');?></a>
										<?php } ?>
									</label>
									
									<div class="content">
										<!--div class="name"><strong><?php //echo $sunsigns->name;?></strong></div-->
										<div class="date"><span id="month"><?php echo $currentMonth?></span></div>
										<div id="monthly-prediction" class="mainContent"><div style="text-align:center"><?php echo $this->Html->image("calendar-loading.gif", ['height' => 50, 'width' => 50	]);?></div></div>
										<div class="socialIcons">
												<a href="https://www.youtube.com/watch?v=SqUg3mrPLLY" target="_blank" class="seeVideo"><b>See video</b></a><br>

											<?php  
											echo "<span>";
                                               ?><a href="javascript:;" onclick="openFbPopUp('<?php echo Router::Url($this->request->here, true)?>','<?php echo Router::url("/",true)."img/social-sunsign-icon/".strtolower($sunsigns->name)."_new.jpg"?>','Free Horoscope - <?php echo ucwords($sunsigns->name);?>','monthly-prediction')" /><i class="fa fa-facebook fa-lg"></i></a>
                                           <?php 
											//echo $this->SocialShare->fa(  'facebook', Router::Url($this->request->here, true) , ['text' => 'Free Horoscope - '.ucwords($sunsigns->name), 'image' => Router::url('/',true).'uploads/sunsigns/'.$sunsigns->avatar, 'description' => $content, 'icon_class' => 'fa fa-facebook fa-lg'] );
											echo "</span>";
											echo "<span class='social-icon'>";
											echo  $this->SocialShare->fa( 'twitter', null, ['text' => 'Free Horoscope - '.ucwords($sunsigns->name)."\n"]) ;
											echo "</span>";
											echo "<span class='social-icon'>";
											echo  $this->SocialShare->fa( 'gplus', null, [ 'icon_class' => 'fa fa-google-plus fa-lg' ]) ; 
											echo "</span>";
											//if ($_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12') {
												echo '<span class="social-icon">
														<a href="https://api.addthis.com/oexchange/0.8/forward/evernote/offer?url='.Router::url($this->request->here, true).'&title=Free Horoscope - '.ucwords($sunsigns->name).'&ct=1" target="_blank">';
												echo $this->Html->image('ele.png', ['alt' => 'Evernote']);
												echo '</a></span>';
											//}
											?>
										</div>
										<div class="nextPrev">
											<div id="previous-month" class="btn btn-red prevBtn"><?= __('Previous Month');?></div>
											<div id="this-month" style="display:none" class="btn btn-red"><?= __('This Month');?></div>
											<div id="next-month" class="btn btn-red nextBtn"><?= __('Next Month');?></div>
										</div>
									</div> 
								</div>


								<div class="tab">
									<input type="radio" id="tab-4" name="tab-group-1">

									<label for="tab-4">
										
										<?php if( $locale == 'da') { ?>
									  		<a href="<?php echo Router::url('/', true).'dk/'.__d('default', 'sun-signs').'/'.__d('default', strtolower($sunsigns->name)).'/'. __('yearly-horoscope').'#arlig-fanen'?>"><?= __('Yearly');?></a>
									  	<?php } else { ?>
									  		<a href="<?php echo Router::url('/', true).__d('default', 'sun-signs').'/'.__d('default', strtolower($sunsigns->name)).'/'. __('yearly-horoscope').'#yearly-tab'?>"><?= __('Yearly');?></a>
										<?php } ?>
									</label>
									
									<div class="content">
										<!--div class="name"><strong><?php //echo $sunsigns->name;?></strong></div-->
										<div class="date"><span id="year"><?php echo $currentYear;?><span></div>
										<div id="yearly-prediction" class="mainContent"><div style="text-align:center"><?php echo $this->Html->image("calendar-loading.gif", ['height' => 50, 'width' => 50	]);?></div></div>
										<div class="socialIcons">
												<a href="https://www.youtube.com/watch?v=ysW3vQdwqdw" target="_blank" class="seeVideo"><b>See video</b></a><br>
											<?php  
											
											echo "<span>";
                                               ?><a href="javascript:;" onclick="openFbPopUp('<?php echo Router::Url($this->request->here, true)?>','<?php echo Router::url("/",true)."img/social-sunsign-icon/".strtolower($sunsigns->name)."_new.jpg"?>','Free Horoscope - <?php echo ucwords($sunsigns->name);?>','yearly-prediction')" /><i class="fa fa-facebook fa-lg"></i></a>
                                           <?php 
											//echo $this->SocialShare->fa(  'facebook', Router::Url($this->request->here, true) , ['text' => 'Free Horoscope - '.ucwords($sunsigns->name), 'image' => Router::url('/',true).'uploads/sunsigns/'.$sunsigns->avatar, 'description' => $content, 'icon_class' => 'fa fa-facebook fa-lg'] );
											echo "</span>";

											echo "<span class='social-icon'>";
											echo  $this->SocialShare->fa( 'gplus', null, [ 'icon_class' => 'fa fa-google-plus fa-lg' ]) ; 
											echo "</span>";
											//if ($_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12') {
												echo '<span class="social-icon">
														<a href="https://api.addthis.com/oexchange/0.8/forward/evernote/offer?url='.Router::url($this->request->here, true).'&title=Free Horoscope - '.ucwords($sunsigns->name).'&ct=1" target="_blank">';
												echo $this->Html->image('ele.png', ['alt' => 'Evernote']);
												echo '</a></span>';
											//}
											?>
										</div>
										<div class="nextPrev">
											<div id="previous-year" class="btn btn-red prevBtn"><?= __('Previous Year');?></div>
											<div id="this-year" style="display:none" class="btn btn-red"><?= __('This Year');?></div>
											<div id="next-year" class="btn btn-red nextBtn"><?= __('Next Year');?></div>
										</div>
										
									</div> 
								</div>
								

								<div class="tab">
									<input type="radio" id="tab-5" name="tab-group-1">
									<label for="tab-5">
										<?php //echo $this->Html->link(__('Characteristics'), ['controller' => 'SunSigns',strtolower($sunsigns->name),__('characteristics')]) ?>
										<?php if( $locale == 'da') { ?>
									  		<a href="<?php echo Router::url('/', true).'dk/'.__d('default', 'sun-signs').'/'.__d('default', strtolower($sunsigns->name)).'/'. __('characteristics').'#egenskaber-fanen'?>"><?= __('Characteristics');?></a>
									  	<?php } else { ?>
									  		<a href="<?php echo Router::url('/', true).__d('default', 'sun-signs').'/'.__d('default', strtolower($sunsigns->name)).'/'. __('characteristics').'#characteristics-tab'?>"><?= __('Characteristics');?></a>
										<?php } ?>
									</label>
									
									<div class="content">
										<!--div class="name"><strong><?php //echo $sunsigns->name;?></strong></div-->
										<div class="mainContent" id="characteristics-prediction"><?php echo __d('default', $sunsigns->characteristics);?></div>
									</div> 
								</div>

								<div class="tab">
									<input type="radio" id="tab-6" name="tab-group-1">
									<label for="tab-6">
										<?php if( $locale == 'da') { ?>
									  		<a href="<?php echo Router::url('/', true).'dk/'.__d('default', 'sun-signs').'/'.__d('default', strtolower($sunsigns->name)).'/'. __('celebrity').'#kendis-fanen'?>"><?= __('Celebrity');?></a>
								  		<?php } else { ?>
									  		<a href="<?php echo Router::url('/', true).__d('default', 'sun-signs').'/'.__d('default', strtolower($sunsigns->name)).'/'. __('celebrity').'#celebrity-tab'?>"><?= __('Celebrity');?></a>
										<?php } ?>
									</label>
									
									<div class="content">
										<!--div class="name"><strong><?php //echo $sunsigns->name;?></strong></div-->
										<div class="date" style="padding:0">
											<?php if($locale == 'en') {
												echo 'Famous people born in the sign of '.__d('default', $sunsigns->name).':';
											} else {
												echo 'Berømte mennesker født i '.__d('default', $sunsigns->name).' tegn:';
											}
											?>

										</div>
										<div class="mainContent" style="text-align:center"><?php echo $sunsigns->celebrity?></div>
									</div> 
								</div>
								

								<div class="tab">
									<input type="radio" id="tab-7" name="tab-group-1">
									<label for="tab-7">
										<?php //echo $this->Html->link(__('Archive'), ['controller' => 'SunSigns',strtolower($sunsigns->name),__('archive')]); ?>
										<?php if( $locale == 'da') { ?>
									  		<a href="<?php echo Router::url('/', true).'dk/'.__d('default', 'sun-signs').'/'.__d('default', strtolower($sunsigns->name)).'/'. __('archive')?>"><?= __('Archive');?></a>
									  	<?php } else { ?>
									  		<a href="<?php echo Router::url('/', true).__d('default', 'sun-signs').'/'.__d('default', strtolower($sunsigns->name)).'/'. __('archive')?>"><?= __('Archive');?></a>
										<?php } ?>

									</label>
									
									<div class="content">
										<!--div class="name"><strong><?php //echo $sunsigns->name;?></strong></div-->
										<div class="formBox">
											<div class="formRow">
												<span><?= __('Scope');?> </span>
												<?php if($locale == 'en') {
													$options =  [
													'1' => 'Daily',
													'2' => 'Weekly',
													'3' => 'Monthly',
													'4' => 'Yearly'
													];
												} elseif($locale == 'da') {
													$options =  [
													'1' => 'Daglige',
													'2' => 'Ugentlig',
													'3' => 'Månedlige',
													'4' => 'Årlig'
													];
												}
												

												echo $this->Form->select('scope', $options, ['id' => 'scope']);
												?>

											</div>

											<div class="formRow">
												<span>Date</span>
												<?php echo $this->Form->text('date',['id' => 'archive-date-picker' , 'autocomplete' => 'off', 'readonly' => 'readonly'] ); ?> 
											</div>
											<span id="getPrediction" class="btn btn-red" ><?= __('Get Prediction');?></span>
										</div>
										<div id='archive-prediction'></div>
										<div class="socialIcons">
									<?php  
											echo "<span>";
                                               ?><a href="javascript:;" onclick="openFbPopUp('<?php echo Router::Url($this->request->here, true)?>','<?php echo Router::url("/",true)."img/social-sunsign-icon/".strtolower($sunsigns->name)."_new.jpg"?>','Free Horoscope - <?php echo ucwords($sunsigns->name);?>','archive-prediction')" /><i class="fa fa-facebook fa-lg"></i></a>
                                           <?php 
											//echo $this->SocialShare->fa(  'facebook', Router::Url($this->request->here, true) , ['text' => 'Free Horoscope - '.ucwords($sunsigns->name), 'image' => Router::url('/',true).'uploads/sunsigns/'.$sunsigns->avatar, 'description' => $content, 'icon_class' => 'fa fa-facebook fa-lg'] );
											echo "</span>";
											echo "<span class='social-icon'>";
											echo  $this->SocialShare->fa( 'twitter', null, ['text' => 'Free Horoscope - '.ucwords($sunsigns->name)."\n", 'icon_class' => 'fa fa-twitter fa-lg']) ; 
											echo "</span>";
  											echo "<span class='social-icon'>";
											echo  $this->SocialShare->fa( 'gplus', null, [ 'icon_class' => 'fa fa-google-plus fa-lg' ]) ; 
											echo "</span>";
											//if ($_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12') {
												echo '<span class="social-icon">
														<a href="https://api.addthis.com/oexchange/0.8/forward/evernote/offer?url='.Router::url($this->request->here, true).'&title=Free Horoscope - '.ucwords($sunsigns->name).'&ct=1" target="_blank">';
												echo $this->Html->image('ele.png', ['alt' => 'Evernote']);
												echo '</a></span>';
											//}
											?>
										</div>
									</div> 
								</div>
							</div>
						</div>



						<div class="signAdvertisement">

							<?php //if ( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' ) { ?>
							<div style="margin-bottom:10px;">
								<!-- <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script> -->
								<!-- Astrowow- Horoscope -Internal page -->
								<!-- <ins class="adsbygoogle"
								     style="display:inline-block;width:250px;height:250px"
								     data-ad-client="ca-pub-5485211874854826"
								     data-ad-slot="2854610287"></ins>
								<script>
								(adsbygoogle = window.adsbygoogle || []).push({});
								</script> -->

								<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
								<!-- Astrowow -Horoscope -Rectangle -->
								<ins class="adsbygoogle"
								     style="display:inline-block;width:300px;height:250px"
								     data-ad-client="ca-pub-5485211874854826"
								     data-ad-slot="5808076680"></ins>
								<script>
								(adsbygoogle = window.adsbygoogle || []).push({});
								</script>

								<!-- <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
								<ins class="adsbygoogle"
									style="display:block"
								    data-ad-format="autorelaxed"
								    data-ad-client="ca-pub-5485211874854826"
								    data-ad-slot="2948792288">
							  	</ins>
								<script>
									(adsbygoogle = window.adsbygoogle || []).push({});
								</script> -->
							</div>
						<?php //} ?>


							<a href="https://itunes.apple.com/us/app/astrowow/id892775222?mt=8" target="_blank">
								<?php echo $this->Html->image('astrowow_app.png')?>  
							</a>
							<h3><?= __('Astrowow App');?></h3>
							<p>
								<a href="https://itunes.apple.com/us/app/astrowow/id892775222?mt=8" target="_blank"><?php echo $this->Html->image('icon_iOS.png', ['alt' => 'icon_app_store'])?></a>
								<br>
								<?php //__('This refers to the AstroClock app, but the picture is of the Sun Sign app'); ?>
								<?php //echo __('The AstroClock has been a labor of love, and the first version is now available on the App Store');?> </p>
								<p>
									<a href="https://itunes.apple.com/us/app/astrowow/id892775222?mt=8" class="btn btn-red"><?= __('Get Astrowow Sun Signs'); ?></a>
									<?php //echo $this->Html->link(__('Get Astrowow Sun Signs'), ['controller' => 'Pages', 'action' =>'menu-pages', 'free-astropage'], ['class' => 'btn btn-red']);?>
								</p>

									<div id="astro_newsletter">

									<?php 
									 $fid = ($locale == 'en') ? 'a3c18e9539' : 'f35479ea54';
									?>
								<form method="post" action="https://oi.vresp.com?fid=<?= $fid?>" >
								  <div>
								    <div><strong><span style="color: #333333;"><?= __('ASTROWOW Newsletter');?></span></strong></div>
								    	<div>
								    		<label><?= __('Email Address');?>:</label>
								    		<input name="email_address" size="15" />
								   		</div>
								    	<div>
								    		<button class="btn btn-red btn-large" type="submit"><?= __('Submit');?></button>
							    		</div>								    
								  </div>
								</form>
							</div>
							</div> 

						</div> <!-- .et_pb_code -->
							
							<div class="google_adsense">
								<?php 
					              /*
					               * Google Adsense
					               * Created by : Kingslay 
					               * Created by : March 29, 2017 
					               */
					            echo $this->Element('GoogleAdSense/google-add-horoscope');
					            //if ( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' ) {
					            //echo $this->Element('GoogleAdSense/google-add-horoscope-mobile');
					            //}
					            ?>
				            </div>
				            <?php /*if ( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' ) { ?>
					            <div class="google_adsense">
									<?php 
						              
						               * Google Adsense
						               * Created by : Kingslay 
						               * Created by : March 29, 2017 
						               
						            echo $this->Element('GoogleAdSense/google-add-horoscope-mobile');
						            ?>
					            </div>
				            <?php }*/ ?>
					</div> <!-- .et_pb_column -->
				</div> <!-- .et_pb_row -->
			</div> <!-- .et_pb_section -->
			</div>
			<div class="sinesign_reports">
				<?php echo $this->Element('products/our_reports');?>
			</div>
			<div class="sinesign_softwares">
				<?php echo $this->Element('products/our_software');?>
			</div>
			<?php if( !$user_id = $this->request->session()->read('user_id')):?>
				<?php //if( !$user_id = Cache::read('user_id')):?>
				
				<?php echo $this->Element('newsletter');?>
			<?php endif;?>
		</div> <!-- .entry-content -->
	</article> <!-- .et_pb_post -->
</div> <!-- #main-content -->

<?php //if ( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' ) { ?>
	<!-- <h1>Login Modal Dialog Window with CSS and jQuery<small>Tutorial by Alessio Atzeni | <a href="http://www.alessioatzeni.com/blog/login-box-modal-dialog-window-with-css-and-jquery/">View Tutorial</a></small></h1> -->
	<!-- <div class="container">
		<div id="content"> -->
	    
			<!-- <div class="post">
	    		<h2>Your Login or Sign In Box</h2>
	        	<div class="btn-sign">
					<a href="#login-box" class="login-window">Login / Sign In</a>
	        	</div>
			</div> -->
	<?php
		/*if (!empty($this->request->session()->read('soft-exit'))) {
			$popupData = $this->request->session()->read('soft-exit');
			pr($popupData); die;
		}*/
	?>

	<?php /*if ( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' ) {
		if(!empty($afterFormSubmitionMsg)) {
		?>
		<div id="mask" style="display: none;" class="maskMsg">
			<div class="msg_popup" style="display: none; top: 50%; left: 50%; position: fixed;">
				<div id="thankyoumsg_popup">
					<a href="#" class="close">
		        		<?= $this->Html->image('../images/close_pop.png', ['class' => 'btn_close', 'title' => 'Close Window', 'alt' => 'Close']); ?>
		        	</a>
					
				</div>
			</div>
		</div>
	<?php } }*/ ?>

	<?php //if ( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' ) {
		if(!empty($this->request->session()->read('popupUserRegistrationMsg'))) {
			$popupUserRegistrationMsg = $this->request->session()->read('popupUserRegistrationMsg');
		}

		if(!empty($popupUserRegistrationMsg)) { ?>
	<div id="mask" style="display: none;" class="maskMsg">
		<div class="msg_popup" style="display: none; top: 50%; left: 50%; position: fixed;">
	        <div id="thankyoumsg_popup">
		        <a href="#" class="closeThankyouPopup">
		        	<?= $this->Html->image('../images/close_pop.png', ['class' => 'btn_close', 'title' => 'Close Window', 'alt' => 'Close']); ?>
		        </a>
	        	<h3 style="color: green;"><?= __('Success!'); ?></h3>
		        <div id="popupUserRegistrationMsg"><?= $popupUserRegistrationMsg; ?></div>
			</div>
		</div>
	</div>
	<?php } //} ?>


	<div id="mask" style="display: none;" class="maskForm">
		<div class="pop_new" style="display: none;">
	        <div id="login-box" class="login-popup">
		        <a href="#" class="close">
		        	<?= $this->Html->image('../images/close_pop.png', ['class' => 'btn_close', 'title' => 'Close Window', 'alt' => 'Close']); ?>
		        </a>
	        	<h3><?= __('Fill in your birth details to get the free mini report!'); ?></h3>
	        	<?= $this->Flash->render(); ?>
		        <form method="post" id="free-mini-report-for-guest-user" class="signin" action="#">
		        	<fieldset class="textbox">
			        	<label class="username">
			                <span><?= __('First Name'); ?><em style='color:red'> *</em></span>
			                <input id="fname" name="fname" class="validate[required]" type="text" placeholder="<?= __('First Name'); ?>" value="<?php echo $fname = !empty($this->request->session()->read('soft-exit.fname')) ? $this->request->session()->read('soft-exit.fname') : ''; ?>">
	                	</label>

	                	<label class="username">
			                <span><?= __('Email'); ?><em style='color:red'> *</em></span>
			                <input id="username" name="username" class="validate[required, custom[email]]" type="text" autocomplete="on" placeholder="<?= __('Email address'); ?>" value="<?php echo $uname = !empty($this->request->session()->read('soft-exit.username')) ? $this->request->session()->read('soft-exit.username') : ''; ?>">
	                	</label>

	                	<label class="password">
			                <span><?= __('Birth Date'); ?><em style='color:red'> *</em></span>
			                <input id="dob" name="dob" class="validate[required]" type="text" placeholder="<?= __('Birth Date'); ?>" readonly="readonly" value="<?php echo $dob = !empty($this->request->session()->read('soft-exit.dob')) ? $this->request->session()->read('soft-exit.dob') : ''; ?>">
	                	</label>
		                <div class="clearfix"></div>
			                
		                <button class="submit button" type="submit" onClick="ga('send', 'event', { eventCategory: 'SignUp', eventAction: 'Click', eventLabel: 'UserRegistration', eventValue: 0});"><?= __('Get Free Mini Report'); ?></button>
			                
		                <!-- <p><a class="forgot" href="#">Forgot your password?</a></p> -->

	                </fieldset>
	          	</form>
			</div>
		</div>
	</div>
	    <!-- </div>
	</div> -->
<?php //} ?>

<script>
window.fbAsyncInit = function() {
	FB.init({
	  appId            : '1001431253334839',
	  autoLogAppEvents : true,
	  xfbml            : true,
	  version          : 'v2.9'
	});
	FB.AppEvents.logPageView();
  };

 (function(d, s, id){
	 var js, fjs = d.getElementsByTagName(s)[0];
	 if (d.getElementById(id)) {return;}
	 js = d.createElement(s); js.id = id;
	 js.src = "//connect.facebook.net/en_US/sdk.js";
	 fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));

function openFbPopUp(fburl,fbimgurl,fbtitle,fbsummaryid) {
var fbsummary = document.getElementById(fbsummaryid).innerText;
fbsummary = fbsummary.replace(/[^a-z0-9 ,.?!]/ig, '');
    FB.ui({
    		method: 'share_open_graph',
    		action_type: 'og.shares',
    		action_properties: JSON.stringify({
    			object: {
    				'og:url': fburl,
    				'og:title': fbtitle,
    				'og:description': fbsummary,
    				'og:image': fbimgurl
    			}
    		})
    },
function (response) {
// Action after response
});
}
     
	$(document).ready(function(){
		var sign = "<?php echo $sign?>";
		var language = "<?php echo $language?>";
		
		if(sign == '')
		{
			sign = 1;
			
		}

	// On click of today button
	
	getPredictionOnClick('today', 'yesterday', 'tomorrow', 'date', '<?php echo date('M j, Y',strtotime($today))?>' , 'daily-prediction', '<?= $img?>', '<?= $today?>', sign, language, 1);

	// On click of tomorrow button
	getPredictionOnClick('tomorrow', 'yesterday', 'today', 'date', '<?php echo date('M j, Y',strtotime($tomorrow))?>' , 'daily-prediction', '<?= $img?>', '<?= $tomorrow?>', sign, language, 1);

	// On click of yesterday button
	getPredictionOnClick('yesterday', 'today', 'tomorrow', 'date', '<?php echo date('M j, Y',strtotime($yesterday))?>' , 'daily-prediction', '<?= $img?>', '<?= $yesterday?>', sign, language, 1);

	// On click of This Week button
	getPredictionOnClick('this-week', 'previous-week', 'next-week', 'week', '<?= $currentWeek?>' , 'weekly-prediction', '<?= $img?>', '<?= $currentWeekSunday?>', sign, language, 2);

	// On click of Previous Week button
	getPredictionOnClick('previous-week', 'this-week', 'next-week', 'week', '<?php echo $previousWeek?>' , 'weekly-prediction', '<?= $img?>', '<?= $previousWeekSunday?>', sign, language, 2);

	// On click of Next Week button
	getPredictionOnClick('next-week', 'previous-week', 'this-week', 'week', '<?php echo $nextWeek?>' , 'weekly-prediction', '<?= $img?>', '<?= $nextWeekSunday?>', sign, language, 2);

	// On click of This Month button
	getPredictionOnClick('this-month', 'previous-month', 'next-month', 'month', '<?php echo $currentMonth?>' , 'monthly-prediction', '<?= $img?>', '<?= $currentMonthFirstDay?>', sign, language, 3);

	// On click of Previous Month button
	getPredictionOnClick('previous-month', 'this-month', 'next-month', 'month', '<?php echo $previousMonth?>' , 'monthly-prediction', '<?= $img?>', '<?= $previousMonthFirstDay?>', sign, language, 3);

	// On click of Previous Month button
	getPredictionOnClick('next-month', 'previous-month', 'this-month', 'month', '<?php echo $nextMonth?>' , 'monthly-prediction', '<?= $img?>', '<?= $nextMonthFirstDay?>', sign, language, 3);

	// On click of This Year button
	getPredictionOnClick('this-year', 'previous-year', 'next-year', 'year', '<?php echo $currentYear?>' , 'yearly-prediction', '<?= $img?>', '<?= $currentYearFirstDay?>', sign, language, 4);

	// On click of Previous Year button
	getPredictionOnClick('previous-year', 'this-year', 'next-year', 'year', '<?php echo $previousYear?>' , 'yearly-prediction', '<?= $img?>', '<?= $previousYearFirstDay?>', sign, language, 4);

	// On click of Next Year button
	getPredictionOnClick('next-year', 'previous-year', 'this-year', 'year', '<?php echo $nextYear?>' , 'yearly-prediction', '<?= $img?>', '<?= $nextYearFirstDay?>', sign, language, 4);

	/* Get Archive Prediction*/
	getArchivePrediction('<?= $img?>', sign, language);				

	/* Daily Prediction */
	getPrediction('<?= $today?>', sign, language, 1, 'daily-prediction', 'daily');

	/* Weekly Prediction */
	getPrediction('<?= $currentWeekSunday?>', sign, language, 2, 'weekly-prediction', 'weekly');

	/* Monthly Prediction */
	getPrediction('<?= $currentMonthFirstDay?>', sign, language, 3, 'monthly-prediction', 'monthly');

	/* Yearly Prediction */
	getPrediction('<?= $currentYearFirstDay?>', sign, language, 4, 'yearly-prediction', 'yearly');

	<?php //if ( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' ) { ?>
		<?php if($popupError && (!empty($_COOKIE['showPopup'])) ) { ?>
			showPopup();
		<?php } ?>
		$('#country').on('change', function () {
			//alert('hihi');
			$('#city option').html("<?= __('First select country'); ?>");
            var country_id = $(this).val();
            //alert(country_id);
            var langg = "<?php echo $lang = !empty($this->request->session()->read('locale')) ? $this->request->session()->read('locale') : 'en'; ?>";
            if (langg == 'dk' || langg == 'da') {
                var countryCityURL = "<?php echo Router::url('/', true);?>dk/brugere/få-country-byer/"+country_id;
            } else {
                var countryCityURL = "<?php echo $this->Url->build([ 'controller' => 'users', 'action' => 'get-country-cities']);?>/"+country_id;
            }
            getCitiesListBasedOnSelectedCountry (countryCityURL);
        });

        function getCitiesListBasedOnSelectedCountry (url) {
			$.ajax({
		        async : false,
		        type:"POST",
		        url: url,
		        beforeSend: function(){
		            $('#ddBirthCity').html('Loading city');
		        },
		        success: function(data){
		    		$('#city').html(data);
		        },
		        error: function (data) {
		            alert('Some error occured.');
		        }
		    });
		}
	<?php //} ?>
		<?php //if ( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' ) {
			if (isset($popupUserRegistrationMsg) && !empty($popupUserRegistrationMsg)) { ?>
			thankyouMsg();
		<?php $popupUserRegistrationMsg = ''; } //} ?>

		function thankyouMsg() {
			$('.maskMsg').css('display', 'block');
			$('.msg_popup').css('display', 'block');
			<?php $this->request->session()->delete('popupUserRegistrationMsg'); ?>
		}
		/*setTimeout(function() {
    		$('.maskMsg').fadeOut('slow');
    		$('.maskMsg').remove();
			$('.maskForm').remove();
		}, 5000);*/

		//$('.maskMsg').delay(5000).fadeOut('slow');

})



	<?php //if ( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' ) { ?>
		<?php /*if($popupError) { ?>
			showPopup();
		<?php }*/ ?>

		// Remove all anchor tag links from page excluding sunsign-detail div
		/*$(document).ready(function(){
			$("body a").not("div.signDetails a").removeAttr('href');
		});*/

		$(document).click(function(){
			if(getCookie('showPopup') != '' && getCookie('showPopup') != 'no'){
				showPopup();
			}
		});

		$('.signDetails').click(function(event){
     		event.stopPropagation();
 		});


		$('#birthhour').on('click', function(e) {
			e.preventDefault();
		});

		$('#birthminute').on('click', function(e) {
			e.preventDefault();
		});

		function showPopup() {
			$("body a").not("div.signDetails a, a.close").removeClass('not-active');
			//console.log(getCookie('showPopup'));
			if(getCookie('showPopup') != '' && getCookie('showPopup') != 'no'){
				$('.pop_new').css('display', 'block');
				$('#mask').css('display', 'block');
				
				$('#login-box').css('display', 'block');
				$('body').addClass('height-scroll');
				// Getting the variable's value from a link 
				var loginBox = $(this).attr('href');

				//Fade in the Popup and add close button
				$(loginBox).fadeIn(300);
				
				//Set the center alignment padding + border
				var popMargTop = ($(loginBox).height() + 24) / 2;
				var popMargLeft = ($(loginBox).width() + 24) / 2;
				return false;
			} else {
				return false;
			}
		}

		$('a.close').on('click', function() {
			$("body a").removeClass('not-active');
			//console.log(getCookie('showPopup'));
	        //createCookie('showPopup', 'no', -1);
	        createCookie('showPopup', 'no', 30);
			$('#mask , .login-popup').fadeOut(300 , function() {
			});
			$('.pop_new').css('display', 'none');
			$('body').removeClass('height-scroll');
			//console.log(getCookie('showPopup'));
			//location.reload();
			return false;
		});

		$('a.closeThankyouPopup').on('click', function() {
			//console.log(getCookie('showPopup'));
	        //createCookie('showPopup', 'no', -1);
	        //createCookie('showPopup', 'no', 30);
			$("body a").removeClass('not-active');
			createCookie('showPopup', 'no', 30);
			$('#mask , .msg_popup').fadeOut(300 , function() {
			});

			$('.maskMsg').remove();
			$('.maskForm').remove();
			$('body').removeClass('height-scroll');
			//$('.pop_new').css('display', 'none');
			//$('body').removeClass('height-scroll');
			//console.log(getCookie('showPopup'));
			//location.reload();
			return false;
		});

		function getCookie(cname) {
		    var name = cname + "=";
		    var decodedCookie = decodeURIComponent(document.cookie);
		    var ca = decodedCookie.split(';');
		    for(var i = 0; i <ca.length; i++) {
		        var c = ca[i];
		        while (c.charAt(0) == ' ') {
		            c = c.substring(1);
		        }
		        if (c.indexOf(name) == 0) {
		            return c.substring(name.length, c.length);
		        }
		    }
		    return "";
		}

		function createCookie(name,value,days) {
		    if (days) {
		        var date = new Date();
		        date.setTime(date.getTime()+(days*24*60*60*1000));
		        var expires = "; expires="+date.toGMTString();
		    }
		    else var expires = "";
		    document.cookie = name+"="+value+expires+"; path=/";
		}


		$(document).ready(function() {
			if(getCookie('showPopup') != '' && getCookie('showPopup') != 'no'){
				//$("body a").not("div.signDetails a").removeAttr('href');
				$("body a").not("div.signDetails a, .closeThankyouPopup, a.close").addClass('not-active');
			}
			$('#dob').datepicker({
				autoclose: true,
				startView:2,
				endDate: '+0',
				format: 'dd/mm/yyyy'
			});




		});

	<?php //} ?>


</script>


<?php //if ( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' ) { ?>
<style type="text/css">
	.not-active { pointer-events: none; cursor: default; }
	.cons-adrn {margin-bottom: 10px;}
	.cons-adrn a {color: #000; font-weight: bold; text-decoration: underline;}
</style>
<?php //} ?>