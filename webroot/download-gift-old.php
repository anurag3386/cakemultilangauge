<?php
@ob_start();
@session_start();

ini_set("display_errors", 1);
error_reporting(E_ERROR);


if(!empty($_GET["gtoken"]))
{
	$order_id	= base64_decode($_GET["gtoken"]);
	if (!defined('ROOTPATH'))
	{
		define('ROOTPATH', '/var/www/html');
	}
	
	define('SPOOLPATH', ROOTPATH . '/webroot/reports/var/spool');

	$fullPath = sprintf ( "%s/%d.birthday.gift.pdf", SPOOLPATH, $order_id );
	
	if(file_exists($fullPath)) {
		
		// Required for some browsers
		if(ini_get('zlib.output_compression')) {
			ini_set('zlib.output_compression', 'Off');
		}

		// Parse Info / Get Extension
		$fsize = filesize($fullPath);
		$path_parts = pathinfo($fullPath);
		$ext = strtolower($path_parts["extension"]);

		// Determine Content Type
		switch ($ext) {
			case "pdf": $ctype="application/pdf"; break;
			case "exe": $ctype="application/octet-stream"; break;
			case "zip": $ctype="application/zip"; break;
			case "doc": $ctype="application/msword"; break;
			case "xls": $ctype="application/vnd.ms-excel"; break;
			case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
			case "gif": $ctype="image/gif"; break;
			case "png": $ctype="image/png"; break;
			case "jpeg":
			case "jpg": $ctype="image/jpg"; break;
			default: $ctype="application/force-download";
		}

		header("Pragma: public"); // required
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: private",false); // required for certain browsers
		header("Content-Type: $ctype");
		header("Content-Disposition: attachment; filename=\"".basename($fullPath)."\";" );
		header("Content-Transfer-Encoding: binary");
		header("Content-Length: ".$fsize);
		ob_clean();
		flush();
		readfile( $fullPath );
	} else {
		echo "The requested files is removed or not available right now. Please contact support team for more detail.";
	}
} else {
	echo "Sorry, Requested files not found.";
}