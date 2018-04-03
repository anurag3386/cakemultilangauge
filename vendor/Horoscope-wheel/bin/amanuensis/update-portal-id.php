<?php
ini_set("display_errors", 0);
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
error_reporting ( E_ALL );

date_default_timezone_set ( 'America/Los_Angeles' );

/* path definitions */
if (!defined ( 'ROOTPATH' )) {
    define('ROOTPATH', '/home/astrowow/public_html/');
}
define ( 'CLASSPATH', ROOTPATH . '/classes' );
//Its Added for PDO to support PDO			(14-Nov-2013 By Amit Parmar)
require_once (CLASSPATH . '/PDO.config.inc.php');

/***************************************************************************/

$SQLQuery = "UPDATE `order` SET portalid = 2 WHERE portalid = 1"; 

$objDB  = $db->prepare($SQLQuery);
$objDBResult = $objDB->execute();

echo "TOTAL UPDATE PORTALID : " . $objDB->rowCount();