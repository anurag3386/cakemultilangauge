<?php

/* gets the data from a URL */
function get_data($url) {
	$ch = curl_init();
	$timeout = 5;
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	$data = curl_exec($ch);
	curl_close($ch);
	return $data;
}

$returned_content = get_data('https://www.astrowow.com/affiliates-horoscope-api/horoscope-feeds-json.php?api_key=12345678&scope=monthly');

$data =  json_decode($returned_content,true);
?>
<html>
<head>
 <meta charset="UTF-8">
</head>
<body>
<?php 
foreach($data['horoscope'] as $key=>$val){
?>
<h1><?php echo utf8_decode($val['title'])?></h1>
<p><?php echo utf8_decode($val['horoscope']);?></p><br />
<?php     
}
?>
</body>
</html>