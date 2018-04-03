<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
ini_set("display_errors", 1);

if(!defined('ROOTPATH'))
{
	//define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . 'astrowow/');
	define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/');
}

require_once(ROOTPATH."bal/class.micro.blog.php");

$nw = 150;
$nh = 150;

if(isset($_REQUEST['imageid'])) {
	$nw = isset($_REQUEST['nw']) ? $_REQUEST['nw'] : 150;
	$nh =  isset($_REQUEST['nh']) ? $_REQUEST['nh'] : 150;

	$FileID = base64_decode( $_REQUEST['imageid'] );
	$objFile = new MicroBlog();

	$objRow = $objFile->GetImageById($FileID);
	$FilePath = '';
	$FileName = '';
	$FinalPath = '';
	$MineType = '';
	
	if( count($objRow ) > 0 ) {

		foreach ($objRow as $Item) {
			$FilePath = $Item['path'];
			$FileName = $Item['name'];
			$MineType = $Item['mimeType'];
		}
		$FinalPath = ROOTPATH.$FilePath.$FileName;		
		if(file_exists($FinalPath)) {
			$allowed_types=array('jpg','jpeg','gif','png');
			$file_parts=array();
			$ext = '';
			$title = '';
			$i = 0;

			/* Skipping the system files: */
			if($FinalPath=='.' || $FinalPath == '..') continue;

			$file_parts = explode('.',$FinalPath);    //This gets the file name of the images
			$ext = strtolower(array_pop($file_parts));

			/* Using the file name (withouth the extension) as a image title: */
			$title = implode('.',$file_parts);
			$title = htmlspecialchars($title);

			/* If the file extension is allowed: */
			if(in_array($ext, $allowed_types))
			{
				/* If you would like to inpute images into a database, do your mysql query here */

				/* The code past here is the code at the start of the tutorial */
				/* Outputting each image: */

				$source = $FinalPath;
				$stype = explode(".", $source);
				$stype = $stype[count($stype)-1];
				$dest = $FinalPath;

				$size = getimagesize($source);
				$w = $size[0];
				$h = $size[1];

				switch($stype) {
					case 'gif':
						$simg = imagecreatefromgif($source);
						break;
					case 'jpg':
						$simg = imagecreatefromjpeg($source);
						break;
					case 'png':
						$simg = imagecreatefrompng($source);
						break;
				}

				$dimg = imagecreatetruecolor($nw, $nh);
				$wm = $w/$nw;
				$hm = $h/$nh;
				$h_height = $nh/2;
				$w_height = $nw/2;

				if($w> $h) {
					$adjusted_width = $w / $hm;
					$half_width = $adjusted_width / 2;
					$int_width = $half_width - $w_height;
					imagecopyresampled($dimg,$simg,-$int_width,0,0,0,$adjusted_width,$nh,$w,$h);
				} elseif(($w <$h) || ($w == $h)) {
					$adjusted_height = $h / $wm;
					$half_height = $adjusted_height / 2;
					$int_height = $half_height - $h_height;

					imagecopyresampled($dimg,$simg,0,-$int_height,0,0,$nw,$adjusted_height,$w,$h);
				} else {
					imagecopyresampled($dimg,$simg,0,0,0,0,$nw,$nh,$w,$h);
				}			
						
				//Tell the browser what kind of file is come in
				header("Content-Type: $MineType");
				
				//Output the newly created image in jpeg format
				ImageJpeg($dimg);
				
				//Free up resources
				ImageDestroy($dimg);
			}			
		}
	}
}
?>