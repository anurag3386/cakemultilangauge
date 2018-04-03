<?php
global $configuration;
$configuration['soap'] = "http://www.phpobjectgenerator.com/services/soap.php?wsdl";
$configuration['homepage'] = "http://www.phpobjectgenerator.com";
$configuration['revisionNumber'] = "";
$configuration['versionNumber'] = "3.0d";

$configuration['setup_password'] = '';


// to enable automatic data encoding, run setup, go to the manage plugins tab and install the base64 plugin.
// then set db_encoding = 1 below.
// when enabled, db_encoding transparently encodes and decodes data to and from the database without any
// programmatic effort on your part.
$configuration['db_encoding'] = 0;

// edit the information below to match your database settings

/*
$configuration['db']	= 'astrowow';                   //	database name
$configuration['host']	= 'localhost';			//	database host
$configuration['user']	= 'root';			//	database user
$configuration['pass']	= 'crackjack25';		//	database password
$configuration['port'] 	= '3306';			//	database port
*/

/* $configuration['db']	= 'astrowow_astrwowdb'; 		//	database name
$configuration['host']	= 'localhost';          //	database host
$configuration['user']	= 'astrowow_astrwow';		//	database user
$configuration['pass']	= 'z$HH7dO7styd';		//	database password
$configuration['port'] 	= '3306';		//	database port */

if ($_SERVER['SERVER_NAME'] == 'localhost') {
    $configuration['db']	= 'astro_wow'; 		//	database name
	$configuration['host']	= 'localhost';          //	database host
	$configuration['user']	= 'root';		//	database user
	$configuration['pass']	= '';		//	database password
	$configuration['port'] 	= '';		//	database port
} else {
	//astro-new.newsoftdemo.info
    /*$configuration['host'] = "localhost";
	$configuration['db'] = "astronew_astrnwdb";
	$configuration['user'] = "astronew_astrnw";
	$configuration['pass'] = 'dR.9WbEMGmEF';*/
	// AWS mysql
	$configuration['host'] = 'astrowow-mysql.cwlq0bny8ltt.us-west-1.rds.amazonaws.com';
	$configuration['user'] = 'awswow';
	$configuration['pass'] = 'Main4itl123';
	$configuration['db'] = 'astronew_db';
	$configuration['port'] 	= '';
}

//proxy settings - if you are behnd a proxy, change the settings below
$configuration['proxy_host'] = false;
$configuration['proxy_port'] = false;
$configuration['proxy_username'] = false;
$configuration['proxy_password'] = false;


//plugin settings
$configuration['plugins_path'] = 'plugins';

?>
