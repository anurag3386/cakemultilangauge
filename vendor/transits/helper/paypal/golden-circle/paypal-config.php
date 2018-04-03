<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
ini_set("display_errors", 1);
if (!defined('BASEURL')) {
	//define("BASEURL", "http://" . $_SERVER['SERVER_NAME']. "/astrowow/");
	$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
    define("BASEURL", $protocol . $_SERVER['SERVER_NAME']. "/");
}

if (!defined('ROOTPATH')) {
	//define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/');
	//define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/astrowow/');
        define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/');
}

//define('BUSINESS_PAYPAL_ID', 'abhi_p_1352295643_biz@yahoo.co.in');
define('BUSINESS_PAYPAL_ID', 'ard@world-of-wisdom.com');
//define('BUSINESS_PAYPAL_ID', 'dhruv_1350561627_biz@gmail.com');


//define('PAYAPL_URL', 'https://www.sandbox.paypal.com/cgi-bin/webscr');
define('PAYAPL_URL', 'https://www.paypal.com/cgi-bin/webscr');

define('SUCCESS_URL', BASEURL.'thank-you-golden-circle.php');
define('CANCEL_URL', BASEURL.'');

//define('ADMIN_EMAIL', 'dhruv.sarvaiya@n-techcorporate.com');
define('ADMIN_EMAIL', 'parmaramit1111@gmail.com');
define('ORDER_URL', ROOTPATH.'bal/order.php');
define('GOLDEN_CIRCLE_URL', ROOTPATH.'bal/goldenCircle.php');
define('USER_URL', ROOTPATH.'bal/user.php');

require_once(ORDER_URL);
require_once(GOLDEN_CIRCLE_URL);
require_once(USER_URL);

?>