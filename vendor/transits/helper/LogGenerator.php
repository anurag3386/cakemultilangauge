<?php

class LogGenerator {
	function __construct() {

	}

	function getRealIp() {
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {  //check ip from share internet
			$ip=$_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {  //to check ip is pass from proxy
			$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip=$_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}

	function writeLog($where) {

		$ip = $this->getRealIp(); // Get the IP from superglobal
		$host = gethostbyaddr($ip);    // Try to locate the host of the attack
		$date = date("d M Y H:i:s");
			
		// create a logging message with php heredoc syntax
		$logging = <<<LOG
    		\n
    		<< Start of Message >>
    		There was a hacking attempt on your form. \n
    		Date of Attack: {$date}
    		IP-Adress: {$ip} \n
    		Host of Attacker: {$host}
    		Point of Attack: {$where}
    		<< End of Message >>
LOG;
		// Awkward but LOG must be flush left

		// open log file
		if($handle = fopen('hacklog.log', 'a')) {
			fputs($handle, $logging);  // write the Data to file
			fclose($handle);           // close the file
		} else {  // if first method is not working, for example because of wrong file permissions, email the data

			$to = 'parmaramit1111@gmail.com';
			$subject = 'HACK ATTEMPT';
			$header = 'From: amit@astrowow.com';
			if (mail($to, $subject, $logging, $header)) {
				echo "Sent notice to admin.";
			}
		}
	}
}