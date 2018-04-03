<?php 

if (!defined('ROOTPATH')) {
        define('ROOTPATH', '/var/www/html/webroot/reports/');
}
if (!defined('BASEURL')) {
    define("BASEURL", "http://54.193.51.211/reports/");
}

//Initializing the Classes and library and defult variables
define('CLASSPATH', ROOTPATH . 'classes');
define('LIBPATH', ROOTPATH . 'lib');
define('SPOOLPATH', ROOTPATH . 'var/spool');


?>