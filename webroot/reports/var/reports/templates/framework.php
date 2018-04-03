<?php
  /**
   * Script: var/lib/reports/templates/framework.php
   * Author: Andy Gray
   *
   * Description
   * Common framework for report ordering pages
   *
   * Required definitions
   * - PORTAL
   * - PRODUCT
   * - LANGUAGE
   */

if( ! defined('LANGUAGE') ) {
  define('LANGUAGE','en');
 }

/* main template */
$page = file_get_contents( ROOTPATH.'/var/lib/reports/templates/'.PORTAL.'/'.PRODUCT.'.html');

/* header */
$header = file_get_contents( ROOTPATH.'/var/lib/reports/templates/'.PORTAL.'/header.html');
$page = str_replace('<!--HEADER-->', 
		    $header, 
		    $page);

/* left column */
$left_column = file_get_contents( ROOTPATH.'/var/lib/reports/templates/'.PORTAL.'/left_column.html');
$page = str_replace('<!--LEFTCOLUMN-->', 
		    $left_column, 
		    $page);

/* order form */
$order_context = file_get_contents( ROOTPATH.'/var/lib/reports/templates/'.PORTAL.'/orderform.html');
$page = str_replace('<!--ORDERFORM-->', 
		    $order_context, 
		    $page);

/* API ID */
$page = str_replace('<!--APIID-->',
		    md5(PORTAL),
		    $page);

/* product type */
$page = str_replace('<!--PRODUCTTYPE-->',
		    PRODUCT,
		    $page);

/* language */
$page = str_replace('<!--LANGUAGE-->',
		    LANGUAGE,
		    $page);

/* day options */
for( $dayoptions='', $day = 0; $day < 31; $day++ ) {
  $dayoptions .= '<option value="'. ($day+1).'">'.($day+1).'</option>';
 }
$page = str_replace('<!--LABEL_DAY_OPTIONS-->',
		    $dayoptions,
		    $page);

/* month options */
$months = array( 'January','February','March','April','May','June','July','August','September','October','November','December');
for( $monthoptions='', $month = 0; $month < 12; $month++ ) {
  $monthoptions .= '<option value="'. ($month+1).'">'.$months[$month].'</option>';
 }
$page = str_replace('<!--LABEL_MONTH_OPTIONS-->',
		    $monthoptions,
		    $page);

/* year options */
for( $yearoptionss='', $year = 2010; $year >= 1900; $year-- ) {
  $yearoptions .= '<option value="'. ($year).'">'.($year).'</option>';
 }
$page = str_replace('<!--LABEL_YEAR_OPTIONS-->',
		    $yearoptions,
		    $page);

/* hour options */
for( $houroptions='', $hour = 0; $hour < 24; $hour++ ) {
  $houroptions .= '<option value="'. ($hour).'">'.($hour).'</option>';
 }
$page = str_replace('<!--LABEL_HOUR_OPTIONS-->',
		    $houroptions,
		    $page);

/* minute options */
for( $minuteoptions='', $minute = 0; $minute < 60; $minute++ ) {
  $minuteoptions .= '<option value="'. ($minute).'">'.($minute).'</option>';
 }
$page = str_replace('<!--LABEL_MINUTE_OPTIONS-->',
		    $minuteoptions,
		    $page);

/* order confirm */
$confirm_context = file_get_contents( ROOTPATH.'/var/lib/reports/templates/'.PORTAL.'/orderconfirm.html');
$page = str_replace('<!--ORDERCONFIRM-->', 
		    $confirm_context, 
		    $page);

/* links */
$links = file_get_contents( ROOTPATH.'/var/lib/reports/templates/'.PORTAL.'/links.html');
$page = str_replace('<!--LINKS-->', 
		    $links, 
		    $page);

/* tracking */
$tracking = file_get_contents( ROOTPATH.'/var/lib/reports/templates/'.PORTAL.'/tracking.html');
$page = str_replace('<!--TRACKING-->', 
		    $tracking, 
		    $page);

/* scripts */
$scripts = file_get_contents( ROOTPATH.'/var/lib/reports/templates/'.PORTAL.'/scripts.html');
$page = str_replace('<!--SCRIPTS-->', 
		    $scripts, 
		    $page);

/* remove emacs hints */
$page = str_replace('<!-- -*- mode: html; -*- -->',
		    '',
		    $page);

die( $page );
?>
