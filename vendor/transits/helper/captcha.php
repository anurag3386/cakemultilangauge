<?php
//error_reporting(0);
@session_start();
define ( 'HELPER_ROOTPATH', dirname(__FILE__) . '/' );

$fontPath = HELPER_ROOTPATH.'libs/monofont.ttf';
include (HELPER_ROOTPATH . "libs/phptextClass.php");

/* create class object */
$phptextObj = new phptextClass ();
/* phptext function to genrate image with text */
$phptextObj->phpcaptcha ( '#162453', '#fff', 120, 40, 3, 10);