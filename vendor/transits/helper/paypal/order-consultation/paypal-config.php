<?php
ini_set("display_errors", 1);
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);

if (!defined('BASEURL')) {
	$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
    define("BASEURL", $protocol . $_SERVER['SERVER_NAME']. "/");
    //define("BASEURL", "http://" . $_SERVER['SERVER_NAME']. "/");
}

if (!defined('ROOTPATH')) {
    define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/');
}

//define('BUSINESS_PAYPAL_ID', 'abhi_p_1352295643_biz@yahoo.co.in');
define('BUSINESS_PAYPAL_ID', 'ard@world-of-wisdom.com');

//define('PAYAPL_URL', 'https://www.sandbox.paypal.com/cgi-bin/webscr');
define('PAYAPL_URL', 'https://www.paypal.com/cgi-bin/webscr');

define('SUCCESS_URL', BASEURL.'thank-you-order-consultation.php');
define('CANCEL_URL', BASEURL.'');

//define('ADMIN_EMAIL', 'dhruv.sarvaiya@n-techcorporate.com');
//define('ADRIAN_SIR_EMAIL','dhruv.sarvaiya@n-techcorporate.com');

define('ADMIN_EMAIL', 'parmaramit1111@gmail.com');
define('ADRIAN_SIR_EMAIL','parmaramit1111@gmail.com'); 

define('ORDER_URL', ROOTPATH.'bal/order.php');

require_once(ORDER_URL);
define("ONE_MONTH_SUBSCRIPTION_ID", "7");
define("ACTIVE_SUBSCRIPTION_STATUS_ID", "5");
?>