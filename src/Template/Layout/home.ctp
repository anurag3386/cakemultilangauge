<?php use Cake\Routing\Router;?>
<!DOCTYPE html>
<!--[if IE 6]>
<html id="ie6" lang="en-US">
<![endif]-->
<!--[if IE 7]>
<html id="ie7" lang="en-US">
<![endif]-->
<!--[if IE 8]>
<html id="ie8" lang="en-US">
<![endif]-->
<!--[if !(IE 6) | !(IE 7) | !(IE 8)  ]><!-->
<html class="js" lang="en"><!--<![endif]-->
<head>

	<!--[if lt IE 9]>
	<?php echo $this->Html->script('html5')?>
	<![endif]-->
<?php echo $this->element('head');?>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
 <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>	
 <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,600,600i,700" rel="stylesheet">
 <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">	
	<style id="theme-customizer-css">
			.woocommerce #respond input#submit, .woocommerce-page #respond input#submit, .woocommerce #content input.button, .woocommerce-page #content input.button, .woocommerce-message, .woocommerce-error, .woocommerce-info { background: #c43a5c !important; }
			#et_search_icon:hover, .mobile_menu_bar:before, .mobile_menu_bar:after, .et_toggle_slide_menu:after, .et-social-icon a:hover, .et_pb_sum, .et_pb_pricing li a, .et_pb_pricing_table_button, .et_overlay:before, .entry-summary p.price ins, .woocommerce div.product span.price, .woocommerce-page div.product span.price, .woocommerce #content div.product span.price, .woocommerce-page #content div.product span.price, .woocommerce div.product p.price, .woocommerce-page div.product p.price, .woocommerce #content div.product p.price, .woocommerce-page #content div.product p.price, .et_pb_member_social_links a:hover, .woocommerce .star-rating span:before, .woocommerce-page .star-rating span:before, .et_pb_widget li a:hover, .et_pb_filterable_portfolio .et_pb_portfolio_filters li a.active, .et_pb_filterable_portfolio .et_pb_portofolio_pagination ul li a.active, .et_pb_gallery .et_pb_gallery_pagination ul li a.active, .wp-pagenavi span.current, .wp-pagenavi a:hover, .nav-single a, .posted_in a { color: #c43a5c; }
			.et_pb_contact_submit, .et_password_protected_form .et_submit_button, .et_pb_bg_layout_light .et_pb_newsletter_button, .comment-reply-link, .form-submit input, .et_pb_bg_layout_light .et_pb_promo_button, .et_pb_bg_layout_light .et_pb_more_button, .woocommerce a.button.alt, .woocommerce-page a.button.alt, .woocommerce button.button.alt, .woocommerce-page button.button.alt, .woocommerce input.button.alt, .woocommerce-page input.button.alt, .woocommerce #respond input#submit.alt, .woocommerce-page #respond input#submit.alt, .woocommerce #content input.button.alt, .woocommerce-page #content input.button.alt, .woocommerce a.button, .woocommerce-page a.button, .woocommerce button.button, .woocommerce-page button.button, .woocommerce input.button, .woocommerce-page input.button { color: #c43a5c; }
			.footer-widget h4 { color: #c43a5c; }
			.et-search-form, .nav li ul, .et_mobile_menu, .footer-widget li:before, .et_pb_pricing li:before, blockquote { border-color: #c43a5c; }
			.et_pb_counter_amount, .et_pb_featured_table .et_pb_pricing_heading, .et_quote_content, .et_link_content, .et_audio_content, .et_pb_post_slider.et_pb_bg_layout_dark, .et_slide_in_menu_container { background-color: #c43a5c; }
							.container, .et_pb_row, .et_pb_slider .et_pb_container, .et_pb_fullwidth_section .et_pb_title_container, .et_pb_fullwidth_section .et_pb_title_featured_container, .et_pb_fullwidth_header:not(.et_pb_fullscreen) .et_pb_fullwidth_header_container { max-width: 1360px; }
			.et_boxed_layout #page-container, .et_fixed_nav.et_boxed_layout #page-container #top-header, .et_fixed_nav.et_boxed_layout #page-container #main-header, .et_boxed_layout #page-container .container, .et_boxed_layout #page-container .et_pb_row { max-width: 1520px; }
							
													#top-header, #et-secondary-nav li ul { background-color: #c43a5c; }
																
		
													#main-footer .footer-widget h4 { color: #ffffff; }
							.footer-widget li:before { border-color: ; }
																
		
																														
		@media only screen and ( min-width: 981px ) {																																								.et-fixed-header#top-header, .et-fixed-header#top-header #et-secondary-nav li ul { background-color: #c43a5c; }
																			.et-fixed-header #top-menu li.current-menu-ancestor > a,
				.et-fixed-header #top-menu li.current-menu-item > a { color: #ffffff !important; }
						
					}
		@media only screen and ( min-width: 1700px) {
			.et_pb_row { padding: 34px 0; }
			.et_pb_section { padding: 68px 0; }
			.single.et_pb_pagebuilder_layout.et_full_width_page .et_post_meta_wrapper { padding-top: 102px; }
			.et_pb_section.et_pb_section_first { padding-top: inherit; }
			.et_pb_fullwidth_section { padding: 0; }
		}
		@media only screen and ( max-width: 980px ) {
																				}
		@media only screen and ( max-width: 767px ) {
														}
	</style>

	<style id="module-customizer-css">
			</style>
	<style id="fit-vids-style">.fluid-width-video-wrapper{width:100%;position:relative;padding:0;}.fluid-width-video-wrapper iframe,.fluid-width-video-wrapper object,.fluid-width-video-wrapper embed {position:absolute;top:0;left:0;width:100%;height:100%;}</style></head>
	<body class="home page page-id-37 page-template-default et_pb_button_helper_class et_fixed_nav et_show_nav et_cover_background et_pb_gutter windows et_pb_gutters3 et_primary_nav_dropdown_animation_fade et_secondary_nav_dropdown_animation_fade et_pb_footer_columns4 et_header_style_left et_pb_pagebuilder_layout et_right_sidebar gecko sticky-menu">
	
<noscript>
	<style type="text/css">
		#page-container  { display: none; visibility: hidden; }
		#main-footer { display: none; visibility: hidden; }
	</style>
	<center>	
		<br />
		<div class="jsDisable">Warning: Javascript disable </div><br />
		<p>You need to have Javascript enabled in order to view this site.</p><br />
		<p>Please enable Javascript in your browser, and then return to the <a href="https://www.astrowow.com">home page</a>.</p><br />
		<table>
			<tr><td align="left"><div class="jsDisable">How to enable Javascript in Internet Explorer ?</div></td></tr>
			<tr><td align="left"><p>Step 1 : Go to Tools -> Internet Option</p></td></tr>
			<tr><td align="left"><p>Step 2 : Then Go to Security Tab and Click on Custom level button</p></td></tr>
			<tr><td align="left"><p>Step 3 : Scroll down and find Scripting and Active scripting</p></td></tr>
			<tr><td align="left"><p>Step 4 : Select Enable Radio button and click Ok</p></td></tr>
			<tr><td align="left"><p>Step 5 : Close the Option window and refresh the browser</p></td></tr>
			<tr><td></td></tr>
			<tr><td align="left"><br /><div class="jsDisable">How to enable Javascript in Mozilla Firefox ?</div></td></tr>
			<tr><td align="left"><p>Step 1 : Go to Tools -> Internet Option</p></td></tr>
			<tr><td align="left"><p>Step 2 : Then Go to Content Tab</p></td></tr>
			<tr><td align="left"><p>Step 3 : Tick mark the "Enable Javascript" box</p></td></tr>
			<tr><td align="left"><p>Step 4 : Close the Option window and refresh the browser</p></td></tr>
			<tr><td></td></tr>
			<tr><td align="left"><br /><div class="jsDisable">How to enable Javascript in Google Chrome ?</div></td></tr>
			<tr><td align="left"><p>Step 1 : Go to Tools -> Settings</p></td></tr>
			<tr><td align="left"><p>Step 2 : Then click on "Advance setting" -> Privacy and Click on Content Setting.</p></td></tr>
			<tr><td align="left"><p>Step 3 : Content setting -> scroll down and select "Allow all sites to run JavaScript (recommended)" option</p></td></tr>
			<tr><td align="left"><p>Step 4 : Close the Content setting window and refresh the browser</p></td></tr>			
		</table>
	</center>
 </noscript>
 
	
	<?php if ( !empty($this->request->session()->read('Auth.User.role')) && ($this->request->session()->read('Auth.User.role') == 'elite')) { ?>
		<div id="page-container" class="et-animated-content top_space eliteMember">
	<?php } else { ?>
		<div id="page-container" class="et-animated-content top_space">
	<?php } ?>

	<?php echo $this->element('header');?>
	<?php echo $this->fetch('content');  ?>

<!-- Footer Start -->
<!-- <span class="et_pb_scroll_top et-pb-icon et-visible" style="display: inline;"></span> -->

<footer id="main-footer">
  <div class="container">
    <div id="footer-widgets" class="clearfix">
   
      <?php $i=100;  $j=2?>
      <?php if(!empty($footerMenus)):?>
      <?php foreach($footerMenus as $key ):?>
      <div class="footer-widget <?php echo ($j===5)?'last':'';?>">
        <div id="nav_menu-<?php echo $j;?>" class="fwidget et_pb_widget widget_nav_menu">
          <h4 class="title"><?php echo __d('default', $key['title']);?></h4>
          <div class="menu-footer_navigation-container">
            <ul id="menu-footer_navigation" class="menu">
              <?php $children=$this->Menu->findChild($key['id'],'bottom');?>
              <?php if($_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12') {
              		/*if($key['id']==30){
              			echo count($children);
              			pr($children); die;
              		}*/
              	} ?>
	          <?php if($j==5):?>
              <?php $i=129;?>
              <?php endif;?>
              <?php if(!empty($children)):?>
              <?php  foreach($children as $data):?>

              <li id="menu-item-<?php echo $i?>" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-<?php echo $i;?>">

              <?php 
			  
			    if( strtolower($data['title']) == 'facebook' || strtolower($data['title']) == 'twitter' || strtolower($data['title']) == 'youtube' || strtolower($data['title']) == 'google plus' || strtolower($data['title']) == 'instagram'): //https://plus.google.com/106143888045600794106
			 
    	           	echo $this->Html->link($data['title'], $data['url'] , ['ref' => 'nofollow', 'target' => '_blank']);
			    elseif( $data['url'] == 'free-horoscope'):
  	            	echo $this->Html->link( $data['title'], ['controller'=> 'sun-signs', 'action' => 'free-horoscope']);

                elseif( $data['url'] == 'blog'):
  	            	echo $this->Html->link( __d('default', $data['title']), SITE_URL."blog/");
  	            
  	            elseif( $data['url'] == 'support-tickets'):
  	            	if($this->request->session()->read('locale') == 'dk' || $this->request->session()->read('locale') == 'da'){
  	            		$support_tickets_link = SITE_URL."dk/support-billetter";
  	            	} else {
  	            		$support_tickets_link = SITE_URL."support-tickets";
  	            	}
  	            	echo $this->Html->link( __d('default', $data['title']), $support_tickets_link);

                elseif( empty($data[ 'url' ])):
					echo $this->Html->link($data['title'], ['controller'=> 'pages', 'action' => 'menu-pages', '#']);

                else:
					echo $this->Html->link($data['title'], ['controller'=> 'pages', 'action' => 'menu-pages', $data['url']]);
			    endif;
			  			  
			  ?>
			  </li>
              <?php ++$i;?>

              <?php endforeach;?>
            </ul>
          </div>
        </div>
      </div>
      <!-- end .fwidget -->
      <?php $j++;?>
      <?php else:?>
      <p>No Submenu Found</p>
      <?php endif;?>
      <?php endforeach;?>
      <?php else:?>
      <p>No Menus Found</p>
      <?php endif;?>
   <!--  </div> -->
    <!-- end .footer-widget -->

  <!-- </div> -->
  <!-- #footer-widgets -->
  </div>

  <!-- .container -->

  <div id="footer-bottom">
    <div class="container clearfix">
      <ul class="et-social-icons">
      </ul>
      <p id="footer-info"><?= __('Copyright');?> <?php echo date('Y');?> <?php echo $this->Html->link('AstroWOW',['controller' => 'pages', 'action' => 'index'],['title'=>'AstroWOW'])?> </p>
    </div>
    <!-- .container --> </div>
</footer>
<!-- #main-footer -->
</div>
<!-- #et-main-area -->
</div>
<!-- #page-container -->

<!--script src="<?php //echo $this->request->webroot?>plugins/jQuery/jquery-2.2.3.min.js"></script-->
<!-- datepicker -->

<script src="<?php echo $this->request->webroot?>plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?php echo $this->request->webroot?>plugins/validation/jquery.validationEngine.js"></script>
<!-- <script>
 var current_location = location.pathname;
   if(current_location.indexOf("/dk") != -1)
    {
      validation_language =  'da';
    }
    else
    {
      validation_language =  'en';
    }
</script> -->

<?php 

$currentUrl = Router::url($this->request->here, true);
$parts = parse_url($currentUrl);
if(strpos($parts['path'], 'dk') )
{
   $validation_language =  "da";
}
else
{
  $validation_language =  "en";
}
?>
<script src="<?php echo $this->request->webroot?>plugins/validation/languages/jquery.validationEngine-<?php echo $validation_language?>.js"></script>
<script src="<?php echo $this->request->webroot?>js/common_front.js"></script>
<?php echo $this->Html->script(['frontend-builder-global-functions','custom', 'responsiveTabs.min']);?>
<style type="text/css">
  .message.success {color: green;}
  .message.error {color: red;}
</style>

<!-- Google Code for Remarketing Tag -->

<script type="text/javascript">
var google_tag_params = {
dynx_itemid: '5',
dynx_itemid2: '13',
dynx_itemid3: '17',
dynx_itemid4: '19',
dynx_itemid5: '24',
dynx_itemid6: '26',
dynx_itemid7: '28',
dynx_itemid8: '52',
dynx_itemid9: '53',
dynx_itemid10: '62',
dynx_itemid11: '63',
dynx_itemid12: '64',
dynx_itemid13: '66',
dynx_itemid14: '68',
dynx_itemid15: '69',
dynx_itemid16: '71',
dynx_itemid17: '72',
dynx_itemid18: '74',
dynx_itemid19: '75',
dynx_itemid20: '76',
dynx_itemid21: '77',
dynx_itemid22: '78',
dynx_pagetype: 'Ecommerce',
dynx_totalvalue: 22,
};
</script>
<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 1072504019;
var google_custom_params = window.google_tag_params;
var google_remarketing_only = true;
/* ]]> */
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/1072504019/?guid=ON&amp;script=0"/>
</div>
</noscript>

<img src="https://cts.vresp.com/s.gif?h=eedb2f5337" height="1" width="1"/>
</body>
</html>