<?php
/**
 * Script: wowuk-mini-report.php
 * Author: Amit Parmar
 */
    
//define('ROOTPATH', $_SERVER['DOCUMENT_ROOT']);
define('ROOTPATH', '/home/astrowow/public_html/');

require_once(ROOTPATH.'/var/lib/wsapi/portal_defs.php');

$affiliate_id = C_AFFILIATE_RANDR;
$affiliate_collate_path = 'class.collate.wow.en.php';
$affiliate_logger_tag = 'wowuk';
$ReportLanguage = 'en';

$debug_error_reporting = true;

echo "<pre>Framework Starts For R & R Music [For English Report ]</pre>";

require_once(ROOTPATH.'/bin/amanuensis/framework-mini-report.randr-test.php');
