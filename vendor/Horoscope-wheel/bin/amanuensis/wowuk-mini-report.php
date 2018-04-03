<?php
/**
 * Script: wowuk-mini-report.php
 * Author: Amit Parmar
 */
    
//define('ROOTPATH', $_SERVER['DOCUMENT_ROOT']);
define('ROOTPATH', '/home/astrowow/public_html/');

require_once(ROOTPATH.'/var/lib/wsapi/portal_defs.php');

//$affiliate_id = C_AFFILIATE_WOW;
$affiliate_id = 151;
$affiliate_collate_path = 'class.collate.wow.en.php';
$affiliate_logger_tag = 'wowuk';
$ReportLanguage = 'en';

$debug_error_reporting = true;

echo "<pre>Framework Starts [For English Report]</pre>";

require_once(ROOTPATH.'/bin/amanuensis/framework-mini-report.php');