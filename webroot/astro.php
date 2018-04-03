<?php
$arg	= isset($_GET['q'])?trim($_GET['q']):"";
//echo $arg	= base64_decode($arg);die;
//$arg	= '-qb 7 14 1987 10.28 0:00 -05:30 77:18:00E 28:26:00N -sd -YQ 0';
//-qb 7 14 1987 10.28 0:00 -05:30 77:18:00E 28:26:00N -sd -YQ 0

$cmd	= '/a541/./astrolog '.$arg;
if(($handle = popen( $cmd, 'r' )) === false)
{
	die('unable to open pipe');
}

while(!feof($handle))
{
	$m_cache	.= fgets($handle, 256);
}
echo $m_cache;
pclose($handle);
?>