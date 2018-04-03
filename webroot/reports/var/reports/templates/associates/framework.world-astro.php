<?php
  /**
   * Script: var/lib/reports/templates/associates/framework.world-astro.php
   * Author: Andy Gray
   *
   * Description
   * Generic order form for WOW associates
   */

error_reporting(E_NONE);

/*
 * Load the template page
 * This is common to all associates
 */
$page = file_get_contents(ROOTPATH.'/var/lib/reports/templates/associates/world-astro.html');

/*
 * Manage the affiliate name displayed in the form banner region
 */
$page = str_replace('<!--AFFILIATE_NAME-->',
		    $affiliate_name,
		    $page);

/*
 * The API ID is an MD5 has that identifies the associate to the workflow
 */
$page = str_replace('<!--AFFILIATE_APIID-->',
		    md5($affiliate_name),
		    $page);

/*
 * The language refers to the content language when the report is generated
 */
$page = str_replace('<!--AFFILIATE_LANGUAGE-->',
		    $affiliate_language,
		    $page);

/*
 * Show available products
 * if multi-product is true then offer a drop down list
 * else a hidden field.
 */
if( isset( $multiproduc ) && $multiproduct === true ) {
  // add label here
  // add drop down list here
 } else {
  // remove comment
  $page = str_replace('<!--LABEL_PRODUCT-->',
		      'Report Type:',
		      $page);
 }

/*
 * Show default currency
 */
$page = str_replace('<!--AFFILIATE_DEFAULT_CURRENCY-->',
		    $affiliate_default_currency,
		    $page);

/*
 * Show the cover title request
 */
$lang['en']['name'] = 'Name on Report:';
$lang['dk']['name'] = 'Navn p&aring; rapport:';
$page = str_replace('<!--LABEL_NAME-->',
		    sprintf
		    (
		     "<div id='locale_en'>%s</div>".
		     "<div id='locale_dk' style='display:none'>%s</div>",
		     $lang['en']['name'],
		     $lang['dk']['name']
		     ),
		    $page);

/*
 * Gender
 */
$lang['en']['gender'] = 'Sex / Gender:';
$lang['dk']['gender'] = 'Sex / K&oslash;n:';
$page = str_replace('<!--LABEL_GENDER-->',
		    sprintf
		    (
		     "<div id='locale_en'>%s</div>".
		     "<div id='locale_dk' style='display:none'>%s</div>",
		     $lang['en']['gender'],
		     $lang['dk']['gender']
		     ),
		    $page);

/*
 * Email address
 */
$lang['en']['email'] = 'Email Address:';
$lang['dk']['email'] = 'Emailadresse:';
$page = str_replace('<!--LABEL_EMAIL-->',
		    sprintf
		    (
		     "<div id='locale_en'>%s</div>".
		     "<div id='locale_dk' style='display:none'>%s</div>",
		     $lang['en']['email'],
		     $lang['dk']['email']
		     ),
		    $page);

/*
 * Email address confirmation
 */
$lang['en']['email_confirm'] = 'Confirm Email Address:';
$lang['dk']['email_confirm'] = 'Bekr&aelig;ft Emailadresse:';
$page = str_replace('<!--LABEL_EMAIL_CONFIRM-->',
		    sprintf
		    (
		     "<div id='locale_en'>%s</div>".
		     "<div id='locale_dk' style='display:none'>%s</div>",
		     $lang['en']['email_confirm'],
		     $lang['dk']['email_confirm']
		     ),
		    $page);

/*
 * Date of birth
 */
$lang['en']['date'] = 'Date of Birth:';
$lang['dk']['date'] = 'Date of Birth:';
$page = str_replace('<!--LABEL_DATE-->',
		    sprintf
		    (
		     "<div id='locale_en'>%s</div>".
		     "<!--div id='locale_dk' style='display:none'>%s</div-->",
		     $lang['en']['date'],
		     $lang['dk']['date']
		     ),
		    $page);

$lang['en']['day'] = 'Day';
$lang['dk']['day'] = 'Dag';
$page = str_replace('<!--LABEL_DAY-->',
		    $lang['en']['day'],
		    $page);

$lang['en']['month'] = 'Month';
$lang['dk']['month'] = 'M&aring;aned';
$page = str_replace('<!--LABEL_MONTH-->',
		     $lang['en']['month'],
		    $page);

$lang['en']['months'] = array( 'January','February','March','April','May','June','July','August','September','October','November','December');
$lang['dk']['months'] = array( 'Januar','Februar','Marts','April','M&aring;','Juni','Juli','August','September','Oktober','November','December');
for( $months='', $month = 0; $month < 12; $month++ ) {
  $months .= '<option value="'. ($month+1).'">'.$lang[$affiliate_language]['months'][$month].'</option>';
 }
$page = str_replace('<!--LABEL_MONTH_OPTIONS-->',
		    $months,
		    $page);

$lang['en']['year'] = 'Year';
$lang['dk']['year'] = '&Aring;ar';
$page = str_replace('<!--LABEL_YEAR-->',
		    $lang[$affiliate_language]['year'],
		    $page);

$lang['en']['time'] = 'Time of Birth:';
$page = str_replace('<!--LABEL_TIME-->',
		    $lang[$affiliate_language]['time'],
		    $page);

$lang['en']['hour'] = 'Hour';
$page = str_replace('<!--LABEL_HOUR-->',
		    $lang[$affiliate_language]['hour'],
		    $page);

$lang['en']['time_unknown'] = 'Unknown';
$page = str_replace('<!--LABEL_TIME_UNKNOWN-->',
		    $lang[$affiliate_language]['time_unknown'],
		    $page);

$lang['en']['minute'] = 'Minute';
$page = str_replace('<!--LABEL_MINUTE-->',
		    $lang[$affiliate_language]['minute'],
		    $page);

$lang['en']['country'] = 'Country of Birth:';
$lang['dk']['country'] = 'F&oslash;deland:';
$page = str_replace('<!--LABEL_COUNTRY-->',
		    sprintf
		    (
		     "<div id='locale_en'>%s</div>".
		     "<div id='locale_dk' style='display:none'>%s</div>",
		     $lang['en']['country'],
		     $lang['dk']['country']
		     ),
		    $page);

$lang['en']['place'] = 'Place of Birth:';
$page = str_replace('<!--LABEL_PLACE-->',
		    $lang[$affiliate_language]['place'],
		    $page);

$page = str_replace('<!--AFFILIATE_POST-->',
		    $affiliate_post,
		    $page);

$page = str_replace('<!--AFFILIATE_PAYMENT-->',
		    $affiliate_payment,
		    $page);

/*
 * Product name
 * For now the product name is 'full'
 */
$product_name = 'full';
$page = str_replace('<!--PRODUCT_NAME-->',
		    $product_name,
		    $page);

if( $isComplete === true ) {
  $page = str_replace('<!--ONLOAD_ACTIONS-->',
		      ' onLoad="javascript:completeOrder();"',
		      $page);
 }

if( $isCancelled === true ) {
  $page = str_replace('<!--ONLOAD_ACTIONS-->',
		      ' onLoad="javascript:cancelOrder();"',
		      $page);
 }

if( $isComplete === false && $isCancelled === false ) {
  $page = str_replace('<!--ONLOAD_ACTIONS-->',
		      ' onLoad="javascript:startOrder();"',
		      $page);
 }

/*
 * Finally ...
 * Render the page
 */
die($page);
?>
