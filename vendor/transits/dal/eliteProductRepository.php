<?php
/**
 * @author : Amit Parmar <parmaramit1111@gmail.com>
 * @package : Astrowow.eLiteCustomer
 * @uses : It will use to manage the Product 
 *
 * @filesource: eliteProductRepository.php
 */

if(!class_exists("cDatabase")) {
	require_once("cDatabase.php");
}

if(!class_exists("eLiteProductRepository")) {
	class eLiteProductRepository {
		
		public function GetMembershipWithDefaultCurrency($LanguageCode) {
			$obj = new cDatabase();
			$SQL =	"SELECT md.`membershipdetailid`, md.`membershipid`, md.`title`, md.`description`, md.`languageid`, l.code, l.name ";
			$SQL .= "FROM  `membership_master` mm, `membership_detail` md, language l ";
			$WHERE = "WHERE mm.membershipid  = md.membershipid AND md.languageid = l.language_id "; 
      		$WHERE .= " AND l.code ='$LanguageCode' AND l.status =1 AND mm.status = 1";
						
			$SQL = $SQL.$WHERE;
      		
			$query = $obj->db->query($SQL);
			$result = array();
			$result = $query->rows;
			
			if(count($query->rows)>0) {
				$MembershipID = $query->rows[0]['membershipid'];
				$MembershipDetailId = $query->rows[0]['membershipdetailid'];
				
				$SQL = "SELECT mp.`priceid`, mp.`membershipid`, mp.`membershipdetailid`, mp.`currencyid`, mp.`price`, mp.`discounted_price`, mp.`status`, ";
				$SQL .= " c.NAME AS currency_name, c.symbol, c.code, l.CODE AS LCode, cl.isdefault ";
				$SQL .= "FROM `membership_prices`mp, currency c, `language` l, currency_to_language cl ";
				$WHERE = "WHERE mp.currencyid = cl.currency_id AND c.currency_id = cl.currency_id AND l.language_id = cl.language_id ";
				$WHERE .= " AND l.CODE = '$LanguageCode' ";
				$WHERE .= " AND c.currency_id = mp.currencyid AND mp.membershipid = $MembershipID AND mp.membershipdetailid = $MembershipDetailId";
				
				$SQL = $SQL.$WHERE;
				$query = $obj->db->query($SQL);
				$result[0]['prices'] = $query->rows;
			}
			return $result;				
		}		

		public function SaveOrderTransactionDetail($data) {
			$obj = new cDatabase();
			$sql = "INSERT INTO `transactions_membership` SET ";
			$sql .= " PaymentStatus='".$data->PaymentStatus."',";
			$sql .= " PaymentDate='".$data->PaymentDate."',";
			$sql .= " PaymentDetail='".$data->PaymentDetail."',";
			$sql .= " OrderID='".$data->OrderID."',";
			$sql .= " FullName='".$obj->db->escape($data->FullName)."',";
			$sql .= " PayerEmail='".$data->PayerEmail."',";
			$sql .= " Amount='".$data->Amount."',";
			$sql .= " CurrencyCode='".$data->CurrencyCode."',";
			$sql .= " PaypalTxnID='".$data->PaypalTxnID."'";
			$query = $obj->db->query($sql);
		
			return true;
		}
		
		public function SaveMembershipOrder($data) {
			$obj = new cDatabase();
			$sql = " INSERT INTO `membership_orders` ";
			$sql .=" (`membershipid`, `membershipdetailid`, `price`, `order_status`, `order_date`, `currency_code`, `email_id`, `language_code`, `portalid`, `user_id`) ";
			$sql .=" VALUE (";
			$sql .=" '".$data['membershipid']."', ";
			$sql .=" '".$data['membershipdetailid']."', ";
			$sql .=" '".$data['price']."', ";
			$sql .=" '1', ";						//NEW ORDER
			$sql .=" '".date('Y-m-d')."', ";
			$sql .=" '".$data['currency_code']."', ";
			$sql .=" '".$data['email_id']."' ,";
			$sql .=" '".$data['language_code']."',";
			$sql .=" '2' ,";						//".$data['portalid']."
			$sql .=" '".$data['user_id']."' ";
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
		
		public function UpdateOrderStatusAndMemberShipdate($OrderId) {
			$obj = new cDatabase();
			$ConfirmDate = date('Y-m-d');
			$MembershipEndDate = date('Y-m-d', strtotime('+1 years'));
			
			$SQL = "UPDATE `membership_orders` SET order_status = 9, confirm_payment_date= '$ConfirmDate', membership_enddate = '$MembershipEndDate' ";
			$WHERE = " WHERE OrderID = ".$OrderId;
			$SQL = $SQL.$WHERE;
			$query = $obj->db->query($SQL);
		
			return true;
		}
		
		public function GetPortalId($UserId) {
			$obj = new cDatabase();
			$SQL =	"SELECT portalid FROM `membership_to_portal` ";
			$WHERE = "WHERE userid  = '$UserId'";
		
			$SQL = $SQL.$WHERE;
			$query = $obj->db->query($SQL);
			$result = array();
			$result = $query->rows;
			
			$PortalId = 2;	//WORLD-OF-WISDOM.COM
			if(count($query->rows)>0) {
				 $PortalId = $query->rows[0]['portalid'];
			} 
			return $PortalId;
		}

		public function SavePortalSetting($PortalData) {
			$obj = new cDatabase();
			$SQL =	"SELECT portalid FROM `elite_user_portal_settings` ";
			$WHERE = "WHERE  `userid`  = '".$PortalData['UserId']."' AND `portalid` = '".$PortalData['PortalId']."' ";
		
			$SQL = $SQL.$WHERE;
			$query = $obj->db->query($SQL);
			
			$ReturnResult =  false;
			
			if( count($query->rows)>0 ) {
				$SQL =	"UPDATE `elite_user_portal_settings` SET";
				$SQL .=	" `portalname` = '".$PortalData['PortalName']."', ";
				$SQL .=	" `portalurl` = '".$PortalData['PortalUrl']."' ";
				$WHERE = "WHERE  `userid`  = '".$PortalData['UserId']."' AND `portalid` = '".$PortalData['PortalId']."' ";
				$SQL = $SQL.$WHERE;
				$query = $obj->db->query($SQL);
				$ReturnResult = true;
			} else {
				$SQL =	"INSERT INTO `elite_user_portal_settings` (`userid`, `portalid`, `portalname`, `portalurl`)";
				$SQL .=	" VALUES ('".$PortalData['UserId']."', '".$PortalData['PortalId']."', '".$PortalData['PortalName']."', '".$PortalData['PortalUrl']."') ";
				$query = $obj->db->query($SQL);
				
				$ReturnResult = true;
			}
			
			return $ReturnResult;
		}
		
		public function AddNewPortal($PortalData) {
			$obj = new cDatabase();
			$SQL =	"SELECT portalid FROM `portal` ";
			$WHERE = "WHERE  `name`  = '".$PortalData['PortalName']."' ";
			
			$SQL = $SQL.$WHERE;
			$query = $obj->db->query($SQL);
				
			$ReturnResult =  false;
			$NewPortalId =0;
			if( count($query->rows)>0 ) {
				$ReturnResult = true;
			} else {
				$SQL =	"INSERT INTO `portal` (`name`)";
				$SQL .=	" VALUES ('".$PortalData['PortalName']."') ";
				$query = $obj->db->query($SQL);
				
				if($query) {
					$NewPortalId = $obj->db->getLastId();
					
					$SQL =	"INSERT INTO `membership_to_portal` (`userid`, `portalid`)";
					$SQL .=	" VALUES ('".$PortalData['UserId']."', '".$NewPortalId."') ";
					$query = $obj->db->query($SQL);
					$ReturnResult = true;
				}
				$ReturnResult = true;
			}
				
			return $NewPortalId;
		}
	}
}