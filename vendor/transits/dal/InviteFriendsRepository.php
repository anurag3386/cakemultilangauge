<?php
/**
 * @author : Amit Parmar <parmaramit1111@gmail.com>
 * @package : Astrowow.InviteFriend
 * @uses : It will use to manage the Invited friends from different social sites.
 *
 * @filesource: InviteFriendsRepository.php
 */

if(!class_exists("cDatabase")) {
	require_once("cDatabase.php");
}

if(!class_exists("InviteFriendsRepository")) {
	class InviteFriendsRepository {

		function __construct() {
		}

		function Save($InviteDTO) {
				
			if($this->GetDuplicate($InviteDTO) == 0) {
				
				$obj = new cDatabase();
				$sql = " INSERT INTO `invite_friends` (`firstname`, `lastname`, `emailid`, `userid`, `contacttype`, `issubscribe`) ";
				$sql .=" VALUE (";
				$sql .=" '".$obj->db->escape(utf8_encode($InviteDTO->FirstName))."', ";
				$sql .=" '".$obj->db->escape(utf8_encode($InviteDTO->LastName))."', ";
				$sql .=" '".$obj->db->escape(utf8_encode($InviteDTO->EmailID))."', ";
				$sql .=" '".$obj->db->escape($InviteDTO->UserID)."', ";
				$sql .=" '".$obj->db->escape($InviteDTO->ContactType)."', ";
				$sql .=" '1'";
				$sql .=" )";

				$query = $obj->db->query($sql);
				if($query) {
					return $obj->db->getLastId();
				}
				
			}
			return 0;
		}

		function GetDuplicate($InviteDTO){
			$obj = new cDatabase();
			$sql = " SELECT count(*) AS dCount FROM `invite_friends` WHERE `emailid` = '".$obj->db->escape($InviteDTO->EmailID)."' AND ";
			$sql .=" `userid` = '".$obj->db->escape(utf8_encode($InviteDTO->UserID))."'";

			$query = $obj->db->query($sql);
			return $query->row['dCount'];
		}
		
		function IsTheyInvitedAnyFriends($UserId, $ContactType) {			 
			/**
			 * Contact Type 
			 * Gmail = 1 ,
			 * Google Plus = 2, 
			 * Facebook = 3, 
			 * Yahoo = 4
			 */
			$obj = new cDatabase();
			$sql = " SELECT count(*) AS dCount FROM `invite_friends` WHERE `contacttype` = '".$obj->db->escape($ContactType)."' AND ";
			$sql .=" `userid` = '".$obj->db->escape($UserId)."'";
			
			$query = $obj->db->query($sql);
			return $query->row['dCount'];
		}
		
		public function SaveSubScriptionOrder($data) {
			$obj = new cDatabase();
			$sql = " Insert into `order` ";
			$sql .=" (product_item_id,price,discount,user_id,delivery_option,order_status,order_date,confirm_payment_date,product_type,chk_for_register,currency_code,shipping_charge,email_id,language_code) ";
			$sql .=" VALUE (";
			$sql .=" '".$data->product_item_id."', ";
			$sql .=" '".$data->price."', ";
			$sql .=" '".$data->discount."', ";
			$sql .=" '".$data->user_id."', ";
			$sql .=" '".$data->delivery_option."', ";
			$sql .=" '".$data->order_status."', ";
			$sql .=" '".$data->order_date."', ";
			$sql .=" '".$data->confirm_payment_date."', ";
			$sql .=" '".$data->product_type."' ,";
			$sql .=" '".$data->chk_for_register."',";
			$sql .=" '".$data->currency_code."' ,";
			$sql .=" '".$data->shipping_charge."' ,";
			$sql .=" '".$data->email_id."' ,";
			$sql .=" '".$data->language_code."' ";
			$sql .=" )";
		
			$query = $obj->db->query($sql);
			if($query) {
				$order_id = $obj->db->getLastId();
			}
			else {
				$order_id = 0;
			}
			return $order_id;
		}
		
		function UnSubscribe($EMailID) {		
			$obj = new cDatabase();
			$sql = " UPDATE `invite_friends` SET issubscribe = 0  ";
			$sql .=" WHERE `emailid` = '".$obj->db->escape(utf8_encode($EMailID))."'";
	
			$query = $obj->db->query($sql);
			if($query) {
				return true;
			}
			else {
				return false;
			}
		}
		
	}
}

if(!class_exists("InviteDTO")) {
	class InviteDTO {
		var $InviteID;
		var $FirstName;
		var $LastName;
		var $EmailID;
		var $UserID;
		var $ContactType;
		var $isRegister;
		var $isInvitationSent;
		var $isSubscribe;

		function __construct() {
			$this->InviteID = 0;
			$this->FirstName = '';
			$this->LastName = '';
			$this->EmailID = '';
			$this->UserID = 0;
			$this->ContactType = '';
			$this->isRegister = 0;
			$this->isInvitationSent = 0;
			$this->isSubscribe = 1;
		}
	}
}
?>