<?php
ini_set('display_error', 1);
error_reporting(-1);
$time_start = microtime(true);

$arg	= urlencode('-qb 7 14 1987 10.28 0:00 -05:30 77:18:00E 28:26:00N -sd -YQ 0');
echo $m_cache = file_get_contents("http://54.153.95.173/test.php?q=".$arg);
$time_end = microtime(true);

$execution_time = ($time_end - $time_start);

//execution time of the script
echo '<b>Total Execution Time:</b> '.$execution_time.' Mins';

?>