<?php
  /**
   * Script: amanuensis.rc1.wow.php
   * Author: Andy Gray
   */

//define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . 'astrowow');
//define('ROOTPATH', '/var/www/vhosts/world-of-wisdom.com/httpdocs');
//define('ROOTPATH', '/var/www/vhosts/world-of-wisdom.com/astrowow.com/');
define('ROOTPATH', '/home/astrowow/public_html/');

require_once(ROOTPATH.'/var/lib/wsapi/portal_defs.php');

$affiliate_id = C_AFFILIATE_WOW;
$affiliate_collate_path = 'class.collate.wow.en.php';
$affiliate_logger_tag = 'wowuk';

$debug_error_reporting = true;

echo "<pre>Framework Starts GENERATE PREVIEW REPORT</pre>";
require_once(ROOTPATH.'/bin/amanuensis/generate-preivew-framework.php');
?>