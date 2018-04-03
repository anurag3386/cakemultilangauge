<?php
/**
 * @copyright : Astrowow and N-Tech Technologies Pvt.
 * 
 * @filesource : lovematachRepository.php
 * 
 * Holds the Lovematch opertions
 * 
 */

date_default_timezone_set ( 'America/Los_Angeles' );
if (!class_exists('cDatabase')) {
	if(!include("cDatabase.php")) {
		require_once("cDatabase.php");
	}
}

if(!class_exists("eLiteOrderRepository")){
	class eLiteOrderRepository {
		
		function __construct() {
				
		}
		
		/**
		 * Retrive no of order which are delivered to customer. 
		 * @param Int $UserID
		 * @param Int $OrderStatus  { New = 1, AwaitingforPayment = 2, Queue = 12, Close = 9, }
		 */
		public static function GetOrderCountByUserId($UserID, $OrderStatus = 0) {
			$obj = new cDatabase();
			
			//We are only retriving closed report which are delivered to client
			$SQL = "SELECT count(*) As oCount FROM `order` o, `membership_to_portal` mp ";
			$WHERE = "WHERE mp.portalid = o.portalid AND mp.userid = o.user_id AND o.user_id = $UserID ";
			
			if($OrderStatus > 0) {
				$WHERE .= "AND order_status = $OrderStatus";
			}
			
			$SQL = $SQL.$WHERE;
			$query = $obj->db->query($SQL);	
			$OrderCount = 0;
			
			if(count($query->rows)>0) {
				$OrderCount = $query->rows[0]['oCount'];
			}
			
			return $OrderCount;
		}
		
		public function GetOrderHistory($PortalId, $SearchKeyWord = "", $OrderStatus = 0) {
			$obj = new cDatabase();
				
			//We are only retriving closed report which are delivered to client
			$SQL = "SELECT  o.order_id, o.product_item_id, p.name, o.order_date, o.order_status, o.product_type, o.language_code, o.email_id, CONCAT( first_name, ' ', last_name ) AS FullName ";
			$SQL .= "FROM `order` o, birthdata b, `product_items_description` p, language l ";
			
			$WHERE = "WHERE o.order_id = b.orderid AND o.portalid = '$PortalId' AND o.product_type = 5  AND l.language_id = p.language_id AND ";
			$WHERE .= " (l.name = o.language_code OR l.code = o.language_code) AND p.product_items_id = o.product_item_id ";
			
			if($SearchKeyWord != "") {
				$WHERE .= "AND ( first_name LIKE '$SearchKeyWord%' OR last_name LIKE '$SearchKeyWord%' OR o.order_id LIKE '$SearchKeyWord%' ) ";
			}
					
			if($OrderStatus > 0) {
				$WHERE .= "AND order_status = $OrderStatus ";
			}
			
			$ORDERBY = " ORDER BY o.order_id DESC";
			
			$SQL = $SQL.$WHERE .$ORDERBY;
			$query = $obj->db->query($SQL);			
			
			return $query->rows;
		}
		

		public function GetOrderHistoryCount( $PortalId , $SearchKeyWord = "", $OrderStatus = 0) {
			$obj = new cDatabase();
		
			//We are only retriving closed report which are delivered to client
			$SQL = "SELECT  count(*) oCount ";
			$SQL .= "FROM `order` o, birthdata b, `product_items_description` p, language l ";
				
			$WHERE = "WHERE o.order_id = b.orderid AND o.portalid = '$PortalId' AND o.product_type = 5  AND l.language_id = p.language_id AND ";
			$WHERE .= " (l.name = o.language_code OR l.code = o.language_code) AND p.product_items_id = o.product_item_id ";
				
			if($SearchKeyWord != "") {
				$WHERE .= "AND ( first_name LIKE '$SearchKeyWord%' OR last_name LIKE '$SearchKeyWord%' OR o.order_id LIKE '$SearchKeyWord%' ) ";
			}
				
			if($OrderStatus > 0) {
				$WHERE .= "AND order_status = $OrderStatus ";
			}
				
			$SQL = $SQL.$WHERE;
			$query = $obj->db->query($SQL);
			$OrderCount = 0;
		
			if(count($query->rows)>0) {
				$OrderCount = $query->rows[0]['oCount'];
			}
		
			return $OrderCount;
		}

		public function GetOrderDetailById($OrderId) {
			$obj = new cDatabase();
		
			//We are only retriving closed report which are delivered to client
			$SQL = "SELECT *, p.name ProductName ";
			$SQL .= "FROM `order` o, birthdata b, `product_items_description` p, language l ";
				
//			$WHERE = "WHERE o.order_id = b.orderid AND o.portalid = 148 AND o.product_type = 5  AND l.language_id = p.language_id AND ";
			$WHERE = "WHERE o.order_id = b.orderid AND o.product_type = 5  AND l.language_id = p.language_id AND ";
			$WHERE .= " (l.name = o.language_code OR l.code = o.language_code) AND p.product_items_id = o.product_item_id ";							
			$WHERE .= "AND o.order_id = $OrderId ";				
			$ORDERBY = " ORDER BY o.order_id DESC";
				
			$SQL = $SQL.$WHERE.$ORDERBY;			
			$query = $obj->db->query($SQL);				
			return $query->rows;
		}
	}
}
?>