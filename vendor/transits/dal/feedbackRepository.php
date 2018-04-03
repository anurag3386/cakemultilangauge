<?php

if (!class_exists('cDatabase')) {
	if(!include("cDatabase.php")) {
		require_once("../cDatabase.php");
	}
}

class FeedbackRepository {
	
	var $MyPDO;
	
	public function __construct() {
		$this->MyPDO = $GLOBALS["db"];
	}
	
	public function IsEmailAvailable($email) {
		$obj = new cDatabase();
		if(!empty($email)) {
				
			$SQLQuery = "SELECT UserId FROM `user` WHERE UserName = :UserName";
			$objMyPDO = $this->MyPDO->prepare($SQLQuery);
				
			$objMyPDO->bindParam(':UserName', $email);
				
			$objMyPDO->execute();
			return $objMyPDO->fetchAll ( PDO::FETCH_ASSOC );
		}
		else {
			return false;
		}
	}

	
	public function SaveFeedback($data) {
		$SQLQuery = "INSERT INTO `user_feedback`
				(Name, email, message, feedbackDate)
			VALUES
				(:Name, :email, :message, :feedbackDate)";
		
		$objMyPDO = $this->MyPDO->prepare($SQLQuery);
		
		$regDate = date('Y-m-d h:i:s');
		$Id = 0;
		$objMyPDO->bindParam(':Name', $data->name);
		$objMyPDO->bindParam(':email', $data->user_name);
		$objMyPDO->bindParam(':message', $data->message);
		$objMyPDO->bindParam(':feedbackDate', $regDate);
		
		$objMyPDO->execute();
		$Id = $this->MyPDO->lastInsertId();
		return $Id;
	}		
}
?>