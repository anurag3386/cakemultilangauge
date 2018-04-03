<?php

class PushToFTPOfRandR {
	
	protected $URL =  'http://restapi.tunehog.com/api/mobile/startune/reports.json';
	
	function __construct() {
		
	}
	
	public function Push($Fields, $FileToPush) {
		//open connection
		$ch = curl_init();
				
		//set options
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-type: multipart/form-data"));
		curl_setopt($ch, CURLOPT_URL, $URL);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $Fields);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //needed so that the $result=curl_exec() output is the file and isn't just true/false
		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		
		//execute post
		$result = curl_exec($ch);
		
		//write to filed
		$fp = fopen($FileToPush, 'w');  //make sure the directory markdown.md is in and the result.pdf will go to has proper permissions
		fwrite($fp, $result);
		fclose($fp);
		
		//close connection
		curl_close($ch);		
		echo $result;
	} 
	
}
echo "TEST1-----<br />";
//set POST variables
$FileToPush = '/var/www/astrowow/var/spool/64893.bundle.pdf';
$fields = array("email" => "parmaramit1111@gmail.com", "report" => "@$FileToPush", "id" => "0VM63966XE5239417");

$Hello = new PushToFTPOfRandR();
$Hello->Push($fields, $FileToPush);

echo "TEST2-----<br />";