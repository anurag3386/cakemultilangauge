<?php
//set POST variables
$url = 'http://restapi.tunehog.com/api/mobile/startune/reports.json';
$fields = array(
		"email" => "parmaramit1111@gmail.com", "report" => "@/var/www/astrowow/var/spool/64893.bundle.pdf", "id" => "64893"
);

//open connection
$ch = curl_init();

//set options
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-type: multipart/form-data"));
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //needed so that the $result=curl_exec() output is the file and isn't just true/false

curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_VERBOSE, 1);
curl_setopt($ch, CURLOPT_HEADER, 1);

//execute post
$result = curl_exec($ch);

//write to filed
$fp = fopen('/var/www/astrowow/var/spool/64893.bundle.pdf', 'w');  //make sure the directory markdown.md is in and the result.pdf will go to has proper permissions
fwrite($fp, $result);
fclose($fp);

//close connection
curl_close($ch);

echo $result;