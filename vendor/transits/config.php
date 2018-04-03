<?php
//define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/');
if (!defined('BASEURL')) {
	$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
	define("BASEURL", $protocol . $_SERVER['SERVER_NAME']. "/");
}

if (!defined('BASEHTTPSURL')) {
	define("BASEHTTPSURL", "https://" . $_SERVER['SERVER_NAME']. "/");
}

if (!defined('ROOTPATH')) {	
	define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/');
}
define('CONFIG_INCLUDED',  '');

// common variables
/* define('DB_DRIVER',  'mysql');
define('DB_HOSTNAME',  'localhost');
define('DB_USERNAME',  'astrowow_live');
define('DB_PASSWORD',  'ard6969@p_!947');
define('DB_DATABASE',  'astrowow_live'); */
define('DB_DRIVER',  'mysql');

if ($_SERVER['SERVER_NAME'] == 'localhost') {
    define('DB_HOSTNAME',  'localhost');
	define('DB_USERNAME',  'root');
	define('DB_PASSWORD',  '');
	define('DB_DATABASE',  'astro_wow');
} else {
	//astro-new.newsoftdemo.info
	/*define('DB_HOSTNAME',  'localhost');
	define('DB_USERNAME',  'astronew_astrnw');
	define('DB_PASSWORD',  'dR.9WbEMGmEF');
	define('DB_DATABASE',  'astronew_astrnwdb');*/
	define('DB_HOSTNAME',  'astrowow-mysql.cwlq0bny8ltt.us-west-1.rds.amazonaws.com');
	define('DB_USERNAME',  'awswow');
	define('DB_PASSWORD',  'Main4itl123');
	define('DB_DATABASE',  'astronew_db');
}

// LOCAL common variables
// define('DB_DRIVER',  'mysql');
// define('DB_HOSTNAME',  'localhost');
// define('DB_USERNAME',  'root');
// define('DB_PASSWORD',  'crackjack25');
// define('DB_DATABASE',  'astrowow');


define('DIR_ADMIN','/admin');
define('DIR_FRONT','/');

// HTTP
define('HTTP_SERVER', 'http://' . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['SCRIPT_NAME']), '/.\\') . '/');
define('HTTP_OPENCART', 'http://' . $_SERVER['HTTP_HOST'] . rtrim(rtrim(dirname($_SERVER['SCRIPT_NAME']), 'install'), '/.\\'). '/');

// DIR
define('DIR_APPLICATION', str_replace('\'', '/', realpath(dirname(__FILE__))) . '/');
//define('DIR_SYSTEM', str_replace('\'', '/', realpath(dirname(__FILE__) . '/../')) . '/library/');
define('DIR_SYSTEM', str_replace('\'', '/', realpath(dirname(__FILE__))) . '/library/');
define('DIR_OPENCART', str_replace('\'', '/', realpath(DIR_APPLICATION . '../')) . '/');
define('DIR_DATABASE', DIR_SYSTEM . '');
define('DIR_LANGUAGE', DIR_APPLICATION . 'language/');
define('DIR_TEMPLATE', DIR_APPLICATION . 'view/template/');
define('DIR_CONFIG', DIR_SYSTEM . 'config/');

// Check Version
if (version_compare(phpversion(), '5.1.0', '<') == true) {
	exit('PHP5.1+ Required');
}

// set time zone
if (!ini_get('date.timezone')) {
	//date_default_timezone_set('UTC');
	date_default_timezone_set ( 'America/Los_Angeles' );
}


// as per old data
define('SOAP', 'http://www.phpobjectgenerator.com/services/soap.php?wsdl');
define('HOMEPAGEe', 'http://www.phpobjectgenerator.com');
define('REVISION_NUMBER', '');
define('VERSION_NUMBER', '3.0d');
define('SETUP_PASSWORD', '');

define('REPORT_CATEGORY','4');
define('SOFTWARE_CATEGORY','3');

define('SOFTWARE_ON_CD_CATEGORY','7');
define('SOFTWARE_REGISTRATION_CODE_CATEGORY','6');

define('REPORT_CATEGORY_FOR_EMAIL_AND_POST_DELIVERY','8');
define('REPORT_CATEGORY_FOR_ONLY_POST_DELIVERY','9');

define('LOVERS_REPORT_PRODUCT_ID','13');
define('REGISTER_USER_PERSENTAGE_FROM_PRODUCT_PAGE','5');
define('REGISTERED_USER_PERSENTAGE','10');
define('PREVIEW_REPORT_AVAILABLE_FOR_IDS','21,12,14');


define('MEMBERSHIP_CATEGORY','10');

define('SIGNUP_THANKYOU_PAGE_TOKEN','3fe10bf44e9a7deab63ea946c04fbcd8');
define('PRODUCT_BUY_THANKYOU_PAGE_TOKEN','4c24aac86aa49adce486631bf365098f');
define('GOLDEN_CIRCLE_THANKYOU_PAGE_TOKEN','5e90bd03dd54647873ccc553f18195b9');
define('ADD_ANOTHER_PERSON_THANKYOU_PAGE_TOKEN','c4488572d41de1534fae2ceb75768989');
define('ORDER_CONSULTATION_THANKYOU_PAGE_TOKEN','a99ab39ea805e0d92c04bfe65679fa3a');

define('GMAIL_API_CLIENTID','392758152364');
define('GOOGLE_API_CLIENTID','392758152364.apps.googleusercontent.com');
define('GOOGLE_API_SECREAT_KEY','91T6q4tFNr-89fEO8ZELO7JG');
define('GOOGLE_API_CALLBACK_URL',BASEURL.'invite-friends/google-invite-api.php');
define('GOOGLE_API_MAXRESULT', 300);

define('FACEBOOK_APIID','239978769478002');
define('FACEBOOK_API_SECREAT_KEY','1185b47ceb18933bfd222de210ab228b'); 		//'25b51b83b6b5bee5660c9be5ba02ff04'

define('YAHOO_APIID','dj0yJmk9c295QjQxTGYxaUhkJmQ9WVdrOVRETm1iRTlNTjJjbWNHbzlNakF4TkRNM05EYzJNZy0tJnM9Y29uc3VtZXJzZWNyZXQmeD03NA--');
define('YAHOO_API_SECREAT_KEY','03a00b65c0bfcb0e296cad361ea5088762f19102');
define('OAUTH_DOMAIN', 'www.astrowow.com');
define('OAUTH_APP_ID', 'L3flOL7g');


class Config {
	private $data = array();

  	public function get($key) {
    	return (isset($this->data[$key]) ? $this->data[$key] : null);
  	}	
	
	public function set($key, $value) {
    	$this->data[$key] = $value;
  	}

	public function has($key) {
    	return isset($this->data[$key]);
  	}

  	public function load($filename) {
		$file = DIR_CONFIG . $filename . '.php';
		
    	if (file_exists($file)) { 
	  		$_ = array();
	  
	  		require($file);
	  
	  		$this->data = array_merge($this->data, $_);
		} else {
			trigger_error('Error: Could not load config ' . $filename . '!');
			exit();
		}
  	}
}
?>
