<?php
	die('jijiji');
	error_reporting(E_ALL);
	ini_set ('display_error', 1);
	require_once ('/var/www/reports/lib/Swift-3.3.2-php5/lib/swift_required.php');
	$servername = 'astrowow-mysql.cwlq0bny8ltt.us-west-1.rds.amazonaws.com'; //"localhost";
	$username = 'awswow'; //"astronew_astrnw";
	$password = 'Main4itl123'; //"dR.9WbEMGmEF";
	// Create connection
	$conn = mysqli_connect ($servername, $username, $password);
	// Check connection
	if (!$conn) {
	    die("Connection failed: " . mysqli_connect_error()); die;
	}
	$db_selected = mysqli_select_db($conn, 'astronew_db');
	if (!$db_selected) {
	    die ('Can\'t use foo : ' . mysqli_error());
	}


	/*$eliteMembersDetail = "SELECT 
							FROM user_thnakyou_mails
							JOIN subscribes ON (`subscribes`.`user_id` = `users`.`id`)
							JOIN profiles ON (`profiles`.`user_id` = `users`.`id`) WHERE `user_thnakyou_mails`.`mail_status` = 0";*/
	$eliteMembersDetail = "SELECT
									`user_thankyou_mails`.`order_id`, `orders`.`email`, `orders`.`product_id`, `orders`.`language_id`, `orders`.`product_type` as `product_type_id`,
									`product_types`.`name` as `product_types_name`,
									`products`.`name` as `product_name`, `products`.`category_id`,
									`categories`.`name` as `category_name`,
									`birthdata`.`first_name`, `birthdata`.`last_name`
							FROM user_thankyou_mails
							JOIN orders ON(`orders`.`id` = `user_thankyou_mails`.`order_id`)
							JOIN product_types ON(`product_types`.`id` = `orders`.`product_type`)
							JOIN products ON(`products`.`id` = `orders`.`product_id`)
							JOIN categories ON(`categories`.`id` = `products`.`category_id`)
							JOIN birthdata ON(`birthdata`.`order_id` = `user_thankyou_mails`.`order_id`)
							WHERE `user_thankyou_mails`.`mail_status` = 0";
	$getEmailTemplate = mysqli_query($conn, $eliteMembersDetail);
	die('plpllp');
	//$emailTemplate = mysqli_fetch_assoc($getEmailTemplate);
	//echo '<pre>'; print_r($emailTemplate); die;
	/*$data = array(
				1 => 
				2 => 'order_confirmation_for_reports', //report orders
			);*/
	/*$emailTemplateBasedOnProductType = array(
				5 => 'order_confirmation_for_reports', //report orders
				6 => 'order_confirmation_for_software_cd', // For software CD
				7 => 'order_confirmation_for_software_cd' // For Software Shareware, Also need to send instruction mail to user `order_confirmation_for_registered_shareware`
			);*/
	/*$data = array();
	$i = 0;*/
	if (mysqli_num_rows($result) > 0) {
		$emailTemplateBasedOnProductType = array(
				5 => 'order_confirmation_for_reports', //report orders
				6 => 'order_confirmation_for_software_cd', // For software CD
				7 => 'order_confirmation_for_software_cd' // For Software Shareware, Also need to send instruction mail to user `order_confirmation_for_registered_shareware`
			);
		$languageArray = array(1 => 'en', 2 => 'dk');
		$subjectArray = array(
								'5_en' => 'Thank you for Ordering', // Key : "product_type_id"_"language"
								'5_dk' => 'Tak for din Bestilling'
							);

		$transport = Swift_SmtpTransport::newInstance('108.168.143.191', 25)
		              ->setUsername('services@astrowow.com')
		              ->setPassword('Astro@2016$');
		while ($userData = mysqli_fetch_assoc($getEmailTemplate)) {
			$emailTemplate = "SELECT * FROM `email_templates` where `email_templates`.`short_code`='".$emailTemplateBasedOnProductType[$userData['product_type_id']]."'";
			echo '<pre>'; print_r($userData); die;
			$emailTemplateData = mysqli_query($conn, $emailTemplate);
			$emailTemplateData = mysqli_fetch_assoc($emailTemplateData);
			$msgbody = str_replace('{PRODUCT_NAME}', $userData['product_name'], $emailTemplateData['content']);
			$msgbody = str_replace('{NAME}', ucwords($userData['first_name'].' '.$userData['last_name']).',', $msgbody);
			//echo $msgbody; die;

			$swift = Swift_Mailer::newInstance($transport);
			$message = Swift_Message::newInstance( $subject );
			$message->addPart($message_part, "text/html");
			$message->setFrom(array('replies@astrowow.com'));
			$message->setTo(array($to));
			if ($swift->send($message)) {
				if ($row['end_date'] <= time()) {
		        	$update = "UPDATE users SET role = 'user' WHERE id=".$row['id'];
		        	$updateSubscriptionStatus = mysqli_query($conn, $update) or die(mysqli_error($conn));
	        	}
			} else {
				echo 'user id-'.$row['id'].' calendar expired but details are not updated<br><br>';
			}



		}
		echo '<pre>'; print_r($data); die;
	}
									//`product_types`.`name` as `product_types_name`,
							//JOIN product_types ON(`product_types`.`id` = `products`.`product_type`)

?>