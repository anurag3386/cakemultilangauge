<?php
@ob_start();
@session_start();

$is_remote_download	= 0;
$download_file_url	= "http://52.52.17.200/reports/var/spool";
$alternate_file_url	= "http://216.245.193.174/var/spool";

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
	
	if($is_remote_download == 1)
	{
		$fullPath 		= sprintf ( "%s/%d.birthday.gift.pdf", $download_file_url, $order_id );
		$file_contents	= file_get_contents($fullPath);
		if($file_contents)
		{
			// Parse Info / Get Extension
			$fsize		= count($file_contents);
			$ext 		= "pdf";
			$ctype		= "application/pdf";
			ob_clean();
			flush();
			header("Pragma: public"); // required
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Cache-Control: private", false); // required for certain browsers
			header("Content-Type: $ctype");
			header("Content-Disposition: attachment; filename=\"".basename($fullPath)."\";" );
			header("Content-Transfer-Encoding: binary");
			header("Content-Length: ".$fsize);
			echo $file_contents;
			exit;
		}
		else
		{
			$fullPath 		= sprintf ( "%s/%d.birthday.gift.pdf", $alternate_file_url, $order_id );
			$file_contents	= file_get_contents($fullPath);
			if($file_contents)
			{
				// Parse Info / Get Extension
				$fsize		= count($file_contents);
				$ext 		= "pdf";
				$ctype		= "application/pdf";
				
				header("Pragma: public"); // required
				header("Expires: 0");
				header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
				header("Cache-Control: private", false); // required for certain browsers
				header("Content-Type: $ctype");
				header("Content-Disposition: attachment; filename=\"".basename($fullPath)."\";" );
				header("Content-Transfer-Encoding: binary");
				header("Content-Length: ".$fsize);
				ob_clean();
				flush();
				echo $file_contents;
				exit;
			}
			else
			{
				$fullPath = sprintf ( "%s/%d.birthday.gift.pdf", SPOOLPATH, $order_id );
				
				if(file_exists($fullPath))
				{
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
				}
				else
				{
					echo "The requested files is removed or not available right now. Please contact support team for more detail.";
				}
			}
		}
	}
	else
	{
		$fullPath = sprintf ( "%s/%d.birthday.gift.pdf", SPOOLPATH, $order_id );
		
		if(file_exists($fullPath))
		{
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
		}
		else
		{
			echo "The requested files is removed or not available right now. Please contact support team for more detail.";
		}
	}
}
else
{
	echo "Sorry, Requested files not found.";
}