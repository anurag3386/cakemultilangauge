<?php
error_reporting(E_ALL);
ini_set ('display_error', 1);
	$servername = 'astrowow-mysql.cwlq0bny8ltt.us-west-1.rds.amazonaws.com'; //"localhost";
	$username = 'awswow'; //"astronew_astrnw";
	$password = 'Main4itl123'; //"dR.9WbEMGmEF";
	// Create connection
	$conn = mysqli_connect ($servername, $username, $password);


	// Check connection
	if (!$conn) {
	    die("Connection failed: " . mysqli_connect_error()); die;
	}
	//$db_selected = mysqli_select_db($conn, 'astronew_astrnwdb');
	$db_selected = mysqli_select_db($conn, 'astronew_db');
	if (!$db_selected) {
	    die ('Can\'t use foo : ' . mysqli_error());
	}

	$request = $_GET['request'];
	switch ($request) {
	    case "eliteMembershipExpire":
	        eliteMembershipExpire ();
	        break;
	    case "subscriptionExpire":
	        subscriptionExpire ();
	        break;
	}

	

	/**
     * Send mail for elite users to inform them about the elite membership expiration with in 3days
     * Created by : Kingslay <kingslay@123789.org>
     * Created Date : January 31, 2017
     */
	// http://astro-new.newsoftdemo.info/cronjobs.php?request=subscriptionExpire
    function eliteMembershipExpire () {
    	global $conn;
    	$todayMidNight = strtotime('midnight', time());
    	$date = strtotime("+3 day", $todayMidNight);
        $eliteMembersDetail = "SELECT `users`.`id`, `users`.`role`, `users`.`username`, `elite_members`.`user_id`, `elite_members`.`end_date`, `profiles`.`user_id`, `profiles`.`first_name`, `profiles`.`last_name` FROM users JOIN elite_members ON (`elite_members`.`user_id` = `users`.`id`) JOIN profiles ON (`profiles`.`user_id` = `users`.`id`) WHERE `users`.`role` = 'elite' AND `elite_members`.`end_date` <= ".$date." AND `elite_members`.`end_date` >= ".$todayMidNight;
        $result = mysqli_query($conn, $eliteMembersDetail) or die(mysqli_error($conn));
		
		if (mysqli_num_rows($result) > 0) {
        	$EmailTemplate = "SELECT `id`, `name`, `content`, `short_code` FROM email_templates WHERE `short_code` = 'elite_membership_expiring' LIMIT 1";
        	$getEmailTemplate = mysqli_query($conn, $EmailTemplate);
        	$emailTemplate = mysqli_fetch_assoc($getEmailTemplate);
	        $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
	        $baseurl = $protocol . "://" . $_SERVER['HTTP_HOST']; // . $_SERVER['REQUEST_URI'];
        	while($row = mysqli_fetch_assoc($result)) {
        		$name = ucwords($row['first_name'].' '.$row['last_name']);
		        $to = $row['username'];
		        $expireOn = date ('F dS, Y', $row['end_date']);
		        $message = "<html><body>".$emailTemplate['content']."</body></html>";
		        $message = str_replace('{NAME}', $name, $emailTemplate['content']);
		        $message = str_replace('{elite_expiry}', $expireOn, $message);
		        $supportmail = "<a href='mailto:support@astrowow.com'>support@astrowow.com</a>";
		        $message = str_replace('{SUPPORT_EMAIL}', $supportmail, $message);
		        $renewalUrl = '<a target="_blank" href = '.$baseurl.'/elite-users>click here</a>';
		        $message = str_replace('{renew_elite}', $renewalUrl, $message);
		        $headers = "MIME-Version: 1.0\r\n";
				$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
		        mail ($to, $emailTemplate['name'], $message, $headers);
    		}
		}
    }

    /**
     * Send mail for users to inform them about the calendar subscription expiration with in 3days
     * Created by : Kingslay <kingslay@123789.org>
     * Created Date : January 31, 2017
     */
	// http://astro-new.newsoftdemo.info/cronjobs.php?request=subscriptionExpire
    function subscriptionExpire () {
    	global $conn;
    	$todayMidNight = strtotime('midnight', time());
    	$date = strtotime("+3 day", $todayMidNight);
        $eliteMembersDetail = "SELECT `users`.`id`, `users`.`username`, `subscribes`.`user_id`, `subscribes`.`end_date`, `subscribes`.`status`, `profiles`.`user_id`, `profiles`.`first_name`, `profiles`.`last_name` FROM users JOIN subscribes ON (`subscribes`.`user_id` = `users`.`id`) JOIN profiles ON (`profiles`.`user_id` = `users`.`id`) WHERE `subscribes`.`status` = 1 AND `subscribes`.`end_date` <= ".$date." AND `subscribes`.`end_date` >= ".$todayMidNight." AND `users`.`id` = 17";
        
        $result = mysqli_query($conn, $eliteMembersDetail) or die(mysqli_error($conn));
		if (mysqli_num_rows($result) > 0) {
        	$EmailTemplate = "SELECT `id`, `name`, `content`, `short_code` FROM email_templates WHERE `short_code` = 'calendar_subscription_expiring' LIMIT 1";
        	$getEmailTemplate = mysqli_query($conn, $EmailTemplate);
        	$emailTemplate = mysqli_fetch_assoc($getEmailTemplate);
	        $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
	        $baseurl = $protocol . "://" . $_SERVER['HTTP_HOST']; // . $_SERVER['REQUEST_URI'];
        	while($row = mysqli_fetch_assoc($result)) {
        		$name = ucwords($row['first_name'].' '.$row['last_name']);
		        $to = $row['username'];
		        $expireOn = date ('F dS, Y', $row['end_date']);
		        $message = "<html><body>".$emailTemplate['content']."</body></html>";
		        $message = str_replace('{NAME}', $name, $emailTemplate['content']);
		        $message = str_replace('{Expiry_date}', $expireOn, $message);
		        $renewalUrl = '<a target="_blank" href = '.$baseurl.'/users/subscribe>click here</a>';
		        $message = str_replace('{renew}', $renewalUrl, $message);
		        $headers = "MIME-Version: 1.0\r\n";
				$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
				sendMailUsingSwiftMailer ($to, $emailTemplate['name'], $message);
		        //mail ($to, $emailTemplate['name'], $message, $headers);
		        if ($row['end_date'] <= time()) {
		        	$update = "UPDATE subscribes SET status = 0 WHERE user_id=".$row['id'];
		        	$updateSubscriptionStatus = mysqli_query($conn, $update) or die(mysqli_error($conn));
		        }
    		}
		}
    }


    function sendMailUsingSwiftMailer ($recipient, $subject, $message_part) {
    	require_once ('/var/www/html/webroot/Swift-3.3.2-php5/lib/swift_required.php');
    	$return_path = "replies@astrowow.com";
    	/*
         * Swift mailer Config
         * Created By : Krishan Kumar
         * Created Date : March 24, 2017
         */
        // Create the Transport
        $this->transport = Swift_SmtpTransport::newInstance('108.168.143.191', 25)
              ->setUsername('services@astrowow.com')
              ->setPassword('Astro@2016$');
    	die ('ertertertertre');
    	echo $recipient.' => '.$subject.' => '.$message_part; die;
        $this->swift = Swift_Mailer::newInstance($this->transport);
        $this->message = Swift_Message::newInstance( $subject );
        $this->message->addPart($message_part, "text/html");
        //$this->message->attach( Swift_Attachment::fromPath ( $this->attachment_file ));
        $this->message->setFrom(array($return_path));
        $this->message->setTo(array($recipient));
        if( $this->swift->send($message) ) {
        	echo 'sent'; die;
        	return true;
        } else {
        	echo 'not sent'; die;
        }
        //return false;
    }


?>