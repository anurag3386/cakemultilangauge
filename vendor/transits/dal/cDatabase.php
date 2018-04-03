<?php
// Configuration
error_reporting ( E_ALL );
//require_once('../config.php');
/*
if(!@include("config.php")) 
{
	//throw new Exception("Failed to include 'userRepository.php'");
	require_once("../config.php");
}


//require_once('../library/db.php');
if(!@include("library/db.php")) 
{
	//throw new Exception("Failed to include 'userRepository.php'");
	require_once("../library/db.php");
}*/

$dirname = str_replace("admin\dal","", realpath(dirname(__FILE__)));

if(!defined('DIR_SYSTEM') )
{
	define('DIR_SYSTEM', str_replace('\'', '/', $dirname. '') . '/library/');
}
//define('DIR_SYSTEM', '../../library');
if(!defined('DIR_DATABASE') )
{
	define('DIR_DATABASE', DIR_SYSTEM . '');
}
//echo realpath(dirname(__FILE__));
//echo str_replace("admin\dal","", realpath(dirname(__FILE__)));
 //echo DIR_SYSTEM;
// Database 
//$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

class cDatabase
{
	public $db;
	
	function __construct()
	{
		$this->db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
	}
}
?>