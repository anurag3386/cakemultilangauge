<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
ini_set("display_errors", 1);

if (!defined('BASEURL')) {
	$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
	define("BASEURL", $protocol . $_SERVER['SERVER_NAME']. "/");
}

if (!defined('ROOTPATH')) {
	define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/');
}

//define('BUSINESS_PAYPAL_ID', 'abhi_p_1352295643_biz@yahoo.co.in');
define('BUSINESS_PAYPAL_ID', 'ard@world-of-wisdom.com');

//define('PAYAPL_URL', 'https://www.sandbox.paypal.com/cgi-bin/webscr');
define('PAYAPL_URL', 'https://www.paypal.com/cgi-bin/webscr');

define('SUCCESS_URL_RPT_CAD', BASEURL.'elite-customer/thank-you-character-and-destiny-report.php');		// character-and-destiny-report 12
define('SUCCESS_URL_RPT_CL', BASEURL.'elite-customer/thank-you-comprehensive-lover-report.php');		// comprehensive-lover-report 13 
define('SUCCESS_URL_RPT_AC', BASEURL.'elite-customer/thank-you-astrology-calendar-report.php');			// astrology-calendar-report 14
define('SUCCESS_URL_RPT_PA', BASEURL.'elite-customer/thank-you-psychological-analysis.php');			// psychological-analysis 16
define('SUCCESS_URL_RPT_CAV', BASEURL.'elite-customer/thank-you-career-and-vocation.php');				// career-and-vocation 17 
define('SUCCESS_URL_RPT_CH', BASEURL.'elite-customer/thank-you-childs-horoscope.php');					// childs-horoscope8 18 
define('SUCCESS_URL_RPT_EYAP', BASEURL.'elite-customer/thank-you-essential-year-ahead-prediction.php'); // essential-year-ahead-prediction 21
define('SUCCESS_URL_RPT_ELITE_PAYMENT', BASEURL.'elite-customer/thank-you-elite-customer-payment.php'); // essential-year-ahead-prediction 21

define('SUCCESS_URL_BUY_SUBSCRIPTION', BASEURL.'thank-you-subscription.php');

define('CANCEL_URL', BASEURL);

define('ADMIN_EMAIL', 'parmaramit1111@gmail.com');

define('ORDER_URL', ROOTPATH.'bal/order.php');
define('GOLDEN_CIRCLE_URL', ROOTPATH.'bal/goldenCircle.php');
define('USER_URL', ROOTPATH.'bal/user.php');

define("ONE_MONTH_SUBSCRIPTION_ID", "7");
define("THREE_MONTH_SUBSCRIPTION_ID", "8");
define("ACTIVE_SUBSCRIPTION_STATUS_ID", "5");
?>