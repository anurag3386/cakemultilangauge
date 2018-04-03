<?php
  /**
   * Script: 03_reports/rh_calendar.php
   * Author: Andy Gray
   *
   * Description
   * Rob Hand Calendar Report
   */

define('PORTAL','world-of-wisdom.com');
define('PRODUCTFILE','rh_calendar');
define('PRODUCT','rh_calendar');
define('LANGUAGE','en');

/*
 * Mask the email/print radio button group
 */
$mask_email = true;
$show_current_location = true;

require_once('framework.php');
?>
