<?php

/**
 * @author: Amit Parmar
 * @copyright: World of Wisdom inc.,
 * @package: WOW Year Report
 *
 */

date_default_timezone_set ( 'America/Los_Angeles' );

//define('ROOTPATH', '/var/www/vhosts/world-of-wisdom.com/astrowow.com/');
define('ROOTPATH', '/home/astrowow/public_html/');
    
require_once(ROOTPATH . '/var/lib/wsapi/portal_defs.php');

$affiliate_id = C_AFFILIATE_WOW;
$affiliate_collate_path = 'class.collate.wow.en.php';
$affiliate_logger_tag 	= 'wowuk';

$debug_error_reporting 	= true;
$LanguageFile 			= 'en';

require_once(ROOTPATH.'/bin/amanuensis/preview.year.report.framework.php');
?>