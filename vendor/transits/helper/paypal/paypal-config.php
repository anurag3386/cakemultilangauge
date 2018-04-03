<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
ini_set("display_errors", 1);
if (!defined('BASEURL')) {
	$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
	define("BASEURL", $protocol . $_SERVER['SERVER_NAME']. "/");
     //   define("BASEURL", "http://" . $_SERVER['SERVER_NAME']. "/");
}

if (!defined('ROOTPATH')) {
	//define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/');
	define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/');
     //   define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/');
}

//define('BUSINESS_PAYPAL_ID', 'abhi_p_1352295643_biz@yahoo.co.in');
define('BUSINESS_PAYPAL_ID', 'ard@world-of-wisdom.com');


//define('PAYAPL_URL', 'https://www.sandbox.paypal.com/cgi-bin/webscr');
define('PAYAPL_URL', 'https://www.paypal.com/cgi-bin/webscr');


define('SUCCESS_URL', BASEURL.'golden-circle/confirm.php');
define('SUCCESS_URL_HI', BASEURL.'thank-you-horoscope-interpreter.php');	// Horoscope Interpreter, product_id  = 7
define('SUCCESS_URL_AFL', BASEURL.'thank-you-astrology-for-lovers.php');	// Astrology for Lovers, product_id  = 8
define('SUCCESS_URL_AC', BASEURL.'thank-you-astrological-calendar.php');	// Astrological Calender, product_id  = 9
define('SUCCESS_URL_HI_RS', BASEURL.'thank-you-register-sharware-horoscope-interpreter.php');
define('SUCCESS_URL_AFL_RS', BASEURL.'thank-you-register-sharware-astrology-for-lovers.php');
define('SUCCESS_URL_AC_RS', BASEURL.'thank-you-register-sharware-astrological-calendar.php');

define('SUCCESS_URL_RPT_CAD', BASEURL.'thank-you-character-and-destiny-report.php');	// character-and-destiny-report 12
define('SUCCESS_URL_RPT_CL', BASEURL.'thank-you-comprehensive-lover-report.php');		// comprehensive-lover-report 13 
define('SUCCESS_URL_RPT_AC', BASEURL.'thank-you-astrology-calendar-report.php');		// astrology-calendar-report 14
define('SUCCESS_URL_RPT_PA', BASEURL.'thank-you-psychological-analysis.php');			// psychological-analysis 16
define('SUCCESS_URL_RPT_CAV', BASEURL.'thank-you-career-and-vocation.php');				// career-and-vocation 17 
define('SUCCESS_URL_RPT_CH', BASEURL.'thank-you-childs-horoscope.php');					// childs-horoscope8 18 
define('SUCCESS_URL_RPT_EYAP', BASEURL.'thank-you-essential-year-ahead-prediction.php'); // essential-year-ahead-prediction 21

define('SUCCESS_URL_BUY_SUBSCRIPTION', BASEURL.'thank-you-subscription.php');

define('SUCCESS_URL_COMBO_CD_1', BASEURL.'thank-you-combo-cd1.php');	// 	Horoscope Interpreter + Astrological Calendar 
define('SUCCESS_URL_COMBO_CD_2', BASEURL.'thank-you-combo-cd2.php');	// Horoscope Interpreter + Astrology for Lovers 
define('SUCCESS_URL_COMBO_CD_3', BASEURL.'thank-you-combo-cd3.php');	// Astrological Calendar + Astrology for Lovers 
define('SUCCESS_URL_COMBO_CD_4', BASEURL.'thank-you-combo-cd4.php');	// Horoscope Interpreter + Astrological Calendar + Astrology for Lovers

define('SUCCESS_URL_COMBO_RS_1', BASEURL.'thank-you-register-sharware-combo-products1.php');	//  Register Horoscope Interpreter + Register Astrology FOR Lovers
define('SUCCESS_URL_COMBO_RS_2', BASEURL.'thank-you-register-sharware-combo-products2.php');	// Register Horoscope Interpreter + Register Astrological Calendar
define('SUCCESS_URL_COMBO_RS_3', BASEURL.'thank-you-register-sharware-combo-products3.php');	// Register Astrology FOR Lovers + Register Astrological Calendar
define('SUCCESS_URL_COMBO_RS_4', BASEURL.'thank-you-register-sharware-combo-products4.php');	// Register Horoscope Interpreter + Register Astrology FOR Lovers + Register Astrological Calendar 

define('CANCEL_URL', BASEURL);


//define('ADMIN_EMAIL', 'dhruv.sarvaiya@n-techcorporate.com');
define('ADMIN_EMAIL', 'parmaramit1111@gmail.com');


define('ORDER_URL', ROOTPATH.'bal/order.php');
define('GOLDEN_CIRCLE_URL', ROOTPATH.'bal/goldenCircle.php');
define('USER_URL', ROOTPATH.'bal/user.php');

//require_once(ORDER_URL);
//require_once(GOLDEN_CIRCLE_URL);
//require_once(USER_URL);


/*define('SIGNUP_THANKYOU_PAGE_TOKEN','3fe10bf44e9a7deab63ea946c04fbcd8');
define('PRODUCT_BUY_THANKYOU_PAGE_TOKEN','4c24aac86aa49adce486631bf365098f');
define('GOLDEN_CIRCLE_THANKYOU_PAGE_TOKEN','5e90bd03dd54647873ccc553f18195b9');
define('ADD_ANOTHER_PERSON_THANKYOU_PAGE_TOKEN','c4488572d41de1534fae2ceb75768989');
define('ORDER_CONSULTATION_THANKYOU_PAGE_TOKEN','a99ab39ea805e0d92c04bfe65679fa3a');*/

?>