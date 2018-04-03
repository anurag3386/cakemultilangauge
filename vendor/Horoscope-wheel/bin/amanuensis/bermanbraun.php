<?php
/**
 * source : elite-astrowow.php
 * 
 * @copyright: Astrowow and World of wisdom Inc.
 * @author: Amit Parmar
 */

//define('ROOTPATH', $_SERVER['DOCUMENT_ROOT']);
define('ROOTPATH', '/home/astrowow/public_html');
require_once(ROOTPATH.'/var/lib/wsapi/portal_defs.php');

$affiliate_id = C_AFFILIATE_WOW;
$affiliate_collate_path = 'class.collate.wow.en.php';
$affiliate_logger_tag = 'wowuk';
$ReportLanguage = 'en';

$debug_error_reporting = true;

echo ROOTPATH;
require_once(ROOTPATH.'/bin/amanuensis/framework-bermanbraun.php');
?>