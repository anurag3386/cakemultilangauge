<?php
/** 
 * @author: Amit Parmar
 * @copyright: World of Wisdom inc.,
 * @package: WOW Year Report
 * 
 * 27-Nov-2013
 */  
date_default_timezone_set ( 'America/Los_Angeles' );

//define('ROOTPATH', $_SERVER['DOCUMENT_ROOT']);
define('ROOTPATH', '/home/astrowow/public_html');

require_once(ROOTPATH . '/var/lib/wsapi/portal_defs.php');

$debug_error_reporting = true;
$LanguageFile = 'en';
echo "HElo";

require_once(ROOTPATH.'/bin/amanuensis/year.report.framework.bermanbraun.php');
?>