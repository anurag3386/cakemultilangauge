<?php
if (!class_exists('cDatabase')) {
	if(!include("cDatabase.php")) {
		require_once("../cDatabase.php");
	}
}

if(!class_exists("UserLogRepository")) {
	/**
	 * UserLogRepository class manage the User Login history for My-AstroPage
	 * @author : Amit Parmar
	 */
	class UserLogRepository {
		public $LogId;
		public $UserId;
		public $LoginDate;
		public $IP;

		function __construct($UserId = 0, $LoginDate = '', $IP = '0.0.0.0') {
			$this->UserId =  $UserId;
			$this->LoginDate = isset($LoginDate) ? $LoginDate : date('Y-m-d H:i:s');
			$this->IP = $IP;
		}

		private function GetTodayHistory($UserId) {
			$obj = new cDatabase();
				
			$sql = "SELECT count(*) as LogCount FROM `user_log_history` WHERE DATE_FORMAT(login_date, '%Y-%m-%d') = '".date('Y-m-d')."' AND userid = '$UserId'";
			$query = $obj->db->query($sql);
			return $query->row['LogCount'];
		}

		public function Save() {
			$isThere = $this->GetTodayHistory($this->UserId);
			if(isset($isThere) && $isThere == 0) {
				$obj = new cDatabase();
				try {
					$sql = "INSERT INTO `user_log_history` (`userid`, `login_date`, `ip`) ";
					$sql .= "VALUE ('".$this->UserId."', '".$this->LoginDate."', '".$this->IP."')";
					$query = $obj->db->query($sql);
				}
				catch (Exception $ex){
					return false;
				}
			}
			return true;
		}
	}
}
?>