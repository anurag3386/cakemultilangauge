<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
ini_set("display_errors", 1);
try
{
  if (!defined('BASEURL')) {
  	$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
  	define("BASEURL", $protocol . $_SERVER['SERVER_NAME']. "/");
}

if (!defined('ROOTPATH')) {
	//define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/');
	define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/');
}
	
require_once(ROOTPATH."config.php");

require_once(ROOTPATH."bal/include.php");
require_once(ROOTPATH."dto/acsDTO.php");
require_once("ACSStateList.php");
require_once("suggest.php");



unset($method);
$method = $_REQUEST['method'];


if( isset($method) && !empty($method) ) 
{
	//print_r($_REQUEST);
  header("Content-Type: text/javascript; charset=UTF-8");
  switch($method) 
  {
	case 'getStatesXML':
	  	$callback = $_REQUEST['callback'];
	  	$acs = new ACSStateList();
		
	  	echo $callback . '(' . $acs->getJSON() . ')';
	  	break;/* NOTREACHED */
	case 'getPlacesXML':
	  	$param = $_REQUEST['param'];
	  	$callback = $_REQUEST['callback'];
	  	$acs = new ACSPlaceList($param);
	 	 // print_r( $acs->places );
	 	 echo $callback . '(' . $acs->getJSON() . ')';
	  	break;/* NOTREACHED */
	case 'GetSugestedPlaceName':  
		$q = $_REQUEST['q'];
		$q = utf8_decode( $q );
		
		if( isset($_REQUEST['country']) ) 
		{
			$s = $_REQUEST['country'];
		} 
		else 
		{
			$s = 'JX';
		}
		
		if( isset( $_REQUEST['callback'] ) ) 
		{
  			$callback = $_REQUEST['callback'];
 		}
		
		if( isset($_REQUEST['limit']) ) 
		{
			$l = $_REQUEST['limit'];
		} 
		else 
		{
			$l = '10';
		}
		
		$acs = new Suggest();
		$acs->GetSugestedPlaceName($s,$q,$callback,$l);
		//$result = $acs->GetSugestedPlaceName($s,$q,$callback,$l);
		//echo json_encode($result);
		break;
	default:
	/* error */
  }
}
}
catch(Exception $ex)
{
	print_r($ex);
}

?>