<?php
  /**
   * Script: 03_reports/personal.php
   * Author: Andy Gray
   *
   * Description
   * Personal Horoscope Interpreteter Report
   *
   * Development Note
   * The order capture process is managed within a single browser session
   * - order form
   * - order confirmation and payment
   * - order posted to post.php
   * - post.php calls paypal
   * - paypal returns ipn to paypal.php?action=ipn
   * - paypal returns confirmation of order to paypal.php?action=success
   * - paypal returns cancellation of order to paypal.php?action=cancel
   */

define('PORTAL','wow.dk');
define('PRODUCTFILE','personlig_analyse');
define('PRODUCT','personal');
define('LANGUAGE','dk');

require_once('framework.php');
?>
