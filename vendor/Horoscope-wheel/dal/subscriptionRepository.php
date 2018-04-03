<?php

if(!class_exists("cDatabase")) {
	require_once("cDatabase.php");
}

if(!class_exists("SubscriptionRepository")) {
	class SubscriptionRepository
	{
		public function GetById($subscription)
		{
			try
			{
				$obj = new cDatabase();

				$sql = " SELECT c.category_id,c.parent_category_id,c.file_id,c.sort_order,c.status
						,cd.name,cd.description,cd.language_id ,cd.category_description_id
						FROM category c
						INNER JOIN category_description cd ON c.category_id = cd.category_id ";
				$where = " where 1=1 and c.category_id = ".$category_id;
				$sql = $sql.$where;
				$query = $obj->db->query($sql);
				//print_r($query);
				return $query->rows;
			}
			catch(Exception $ex)
			{
				die($ex->getMessage());
			}
		}

		public function GetList($subscription = 0)
		{
			try
			{
				$obj = new cDatabase();

				/*$sql = " SELECT s.subscription_id,s.product_id,s.early_expiry_mail_days,s.late_expiry_mail_days,s.is_free_subscription,s.duration,
				 sp.currency_id,sp.subscription_price_id,sp.price,sp.renew_price,sp.upgrade_price, pd.productName
				FROM subscription s
				LEFT JOIN subscription_price sp ON sp.subscription_id = s.subscription_id
				LEFT JOIN product_description pd ON pd.product_id = s.product_id
				GROUP BY s.subscription_id ";*/

				$sql = " SELECT s.subscription_id,s.product_id,s.early_expiry_mail_days,s.late_expiry_mail_days,
						s.is_free_subscription, s.duration, pd.productName ,f.NAME AS file_name,f.path,pd.description
						FROM subscription s
						LEFT JOIN product_description pd ON pd.product_id = s.product_id
						LEFT JOIN product p ON p.product_id= s.product_id
						LEFT JOIN files f ON f.file_id = p.image_id
						GROUP BY s.subscription_id";
				//$sql = $sql.$where;
				$query = $obj->db->query($sql);
				return $query->rows;
			}
			catch(Exception $ex)
			{
				//$userDetail->resultState->code=1;
				//$userDetail->resultState->message=$ex;
				die($ex->getMessage());
			}
		}

		public function GetSubscriptionById($subscription = 0)
		{
			try
			{
				$obj = new cDatabase();

				$sql = " SELECT s.subscription_id,s.product_id,s.early_expiry_mail_days, s.late_expiry_mail_days, s.is_free_subscription,
				 	s.duration, sp.currency_id, sp.subscription_price_id, sp.price, sp.renew_price, sp.upgrade_price
						FROM subscription s
						LEFT JOIN subscription_price sp ON sp.subscription_id = s.subscription_id ";
				$where = " where 1=1 and s.subscription_id = ".$subscription;
				$sql = $sql.$where;
				$query = $obj->db->query($sql);
				return $query->rows;
			}
			catch(Exception $ex)
			{
				//$userDetail->resultState->code=1;
				//$userDetail->resultState->message=$ex;
				die($ex->getMessage());
			}
		}

		public function SaveSubscription($data)
		{
			try
			{
				$obj = new cDatabase();
				$sql = " Insert into subscription
						(product_id, early_expiry_mail_days, late_expiry_mail_days, is_free_subscription, duration) ";
				$sql .= " values(
							'".$obj->db->escape($data->product_id)."',
							'".$obj->db->escape($data->early_expiry_days)."',
							'".$obj->db->escape($data->late_expiry_days)."',
							'".$obj->db->escape($data->is_free_subscription)."',
							'".$obj->db->escape($data->duration)."'
						)";
				$query = $obj->db->query($sql);
				//print_r($query);
				$id = $obj->db->getLastId();
				if($id)
				{
					for($i=0;$i<count($data->subscription_price);$i++)
					{
						$sql = " Insert into subscription_price
								(subscription_id,price,renew_price,upgrade_price,currency_id) ";
						$sql .= " values(
									'".$obj->db->escape($id)."',
									'".$obj->db->escape($data->subscription_price[$i]->price)."',
									'".$obj->db->escape($data->subscription_price[$i]->renew_price)."',
									'".$obj->db->escape($data->subscription_price[$i]->upgrade_price)."',
									'".$obj->db->escape($data->subscription_price[$i]->currency_id)."'
								)";
						$query = $obj->db->query($sql);
					}
				}
				else
				{
					$id=0;
				}
				return $id;
			}
			catch(Exception $ex)
			{
				print_r($ex);
				return false;
			}
			//return false;
		}

		public function Update($data)
		{
			try
			{
				$obj = new cDatabase();
				$sql = " UPDATE subscription set
							product_id='".$obj->db->escape($data->product_id)."',
							early_expiry_mail_days='".$obj->db->escape($data->early_expiry_days)."',
							late_expiry_mail_days='".$obj->db->escape($data->late_expiry_days)."',
							is_free_subscription='".$obj->db->escape($data->is_free_subscription)."',
							duration= '".$obj->db->escape($data->duration)."'
						WHERE subscription_id = '".$data->subscription_id."'";
				$query = $obj->db->query($sql);

				for($i=0;$i<count($data->subscription_price);$i++)
				{
					$sql = " update subscription_price set
								price						='".$obj->db->escape($data->subscription_price[$i]->price)."',
								renew_price					='".$obj->db->escape($data->subscription_price[$i]->renew_price)."',
								upgrade_price				='".$obj->db->escape($data->subscription_price[$i]->upgrade_price)."',
								currency_id					='".$obj->db->escape($data->subscription_price[$i]->currency_id)."'
							WHERE subscription_id = '".$data->subscription_id."'
									AND subscription_price_id 	= '".$data->subscription_price[$i]->subscription_price_id."'";
					$query = $obj->db->query($sql);
				}

				return $data->subscription_id;
					
			}
			catch(Exception $ex)
			{
				echo $ex->getMessage();
			}
		}

		public function GetPriceListById($subscription_id)
		{
			try
			{
				$languageId = 'en';				
				if(isset($_COOKIE['language'])) {				
					$languageId = $_COOKIE['language'];				
				}
				
				$obj = new cDatabase();
// 				$sql = " SELECT sp.subscription_id, sp.currency_id, sp.subscription_price_id, sp.price, sp.renew_price, sp.upgrade_price, c.CODE, c.symbol, c.NAME
// 						FROM subscription_price sp
// 						LEFT JOIN currency c ON c.currency_id = sp.currency_id
// 						WHERE sp.subscription_id ='".$subscription_id."'";
				$sql = "SELECT sp.subscription_id, sp.currency_id, sp.subscription_price_id, sp.price, sp.renew_price, sp.upgrade_price, c.CODE, c.symbol, c.NAME, cl.isdefault
						FROM subscription_price sp, currency c, currency_to_language cl, `language` l
						WHERE sp.currency_id = cl.currency_id
							AND c.currency_id = cl.currency_id
							AND l.language_id = cl.language_id
							AND c.currency_id = sp.currency_id
							AND sp.subscription_id = '".$subscription_id."' AND l.CODE = '".$languageId."'";		
				
				$query = $obj->db->query($sql);
				return $query->rows;
			}
			catch(Exception $ex)
			{
				die($ex->getMessage());
			}
		}

		public function SaveSubscribedUser($data)
		{
			try
			{
			  $obj = new cDatabase();
			  $sql = " Insert into subscription_user
			  		(subscription_id,user_id,start_date,end_date,status, order_id) ";
			  $sql .= " values(
			  				'".$obj->db->escape($data->subscription_id)."',
			  				'".$obj->db->escape($data->user_id)."',
			  				'".$obj->db->escape($data->start_date)."',
			  				'".$obj->db->escape($data->end_date)."',
			  				'".$obj->db->escape($data->status)."',
			  				'".$obj->db->escape($data->orderId)."'
			  			)";
	
			  $query = $obj->db->query($sql);
			  //print_r($query);
			  $id = $obj->db->getLastId();
			  if($id > 0)
			  {
			  }
			  else
			  {
				  $id=0;
			  }
			  return $id;
			}
			catch(Exception $ex)
			{
				print_r($ex);
				return false;
			}
		}

		public function UpdateSubscribedUserStatus($user_id,$subscription_id,$status)
		{
			try
			{
				$obj = new cDatabase();
				$sql = "UPDATE subscription_user SET
							status='".$obj->db->escape($status)."'
						WHERE subscription_id = '".$subscription_id."' and subscription_user_id = '".$user_id."'";

				$query = $obj->db->query($sql);
				if($query)
				{
					return $subscription_id;
				}
				else
				{
					return 0;
				}
			}
			catch(Exception $ex)
			{
				echo $ex->getMessage();
			}
		}


		public function UpdateSubscribedUserStatusFromIPN($OrderId, $UserId, $SubscriptionId, $Status, $PayPalSubScrId)
		{
			try
			{
				$start = date('Y-m-d');
				$d = new DateTime( $start );
				$d->modify( "+30 day" );
				$end = $d->format( 'Y-m-d' );
					
				$obj = new cDatabase();
				$sql = "UPDATE subscription_user SET
							start_date='".$obj->db->escape($start)."',
							end_date='".$obj->db->escape($end)."',
							status='".$obj->db->escape($Status)."',
							subscr_id='".$obj->db->escape($PayPalSubScrId)."'
						WHERE subscription_id = '".$SubscriptionId."' AND subscription_user_id = '".$UserId."' AND order_id = '$OrderId'";
				$query = $obj->db->query($sql);
				if($query)
				{
					return $subscription_id;
				}
				else
				{
					return 0;
				}
			}
			catch(Exception $ex)
			{
				echo $ex->getMessage();
			}
		}

		public function IsSubscribeUser($user_id)
		{
			try
			{
				$obj = new cDatabase();
				//$sql = " SELECT subscription_user_id FROM subscription_user where user_id =".$user_id." and status =5 AND STATUS !=0 and end_date >= '".date('Y-m-d')."'";
				$sql = " SELECT subscription_user_id FROM subscription_user where user_id = '".$user_id."' and status IN (5, 12) AND STATUS !=0 and end_date >= '".date('Y-m-d')."'";
				$sql .= " LIMIT 1";
				$query = $obj->db->query($sql);
				return $query->rows;
			}
			catch(Exception $ex)
			{
				die($ex->getMessage());
			}
		}

		public function GetUserActiveSubscription($user_id)
		{
			$obj = new cDatabase();

			$sql = "SELECT * FROM `subscription_user` WHERE STATUS =5 AND user_id =".$user_id;
			$query = $obj->db->query($sql);
			return $query->row;
		}

		public function GetListOfSubscriptionForAnotherPerson($subscription_id = 0)
		{
			try
			{
				$obj = new cDatabase();
					
				$sql = " SELECT s.subscription_id,s.product_id,s.early_expiry_mail_days,s.late_expiry_mail_days,
						s.is_free_subscription, s.duration, pd.productName ,f.NAME AS file_name,f.path,pd.description
						FROM subscription s
						LEFT JOIN product_description pd ON pd.product_id = s.product_id
						LEFT JOIN product p ON p.product_id= s.product_id
						LEFT JOIN files f ON f.file_id = p.image_id		";
					
				$where = " 1=1 ";
				if(!empty($subscription_id))
				{
					$where .= " and s.subscription_id <= ".$subscription_id;
				}
				$where .= " GROUP BY s.subscription_id";
				$sql = $sql.$where;
				$query = $obj->db->query($sql);
				return $query->rows;
			}
			catch(Exception $ex)
			{
				//$userDetail->resultState->code=1;
				//$userDetail->resultState->message=$ex;
				die($ex->getMessage());
			}
		}

		public function GetListOfSubscriptionForAnotherPersonForRenew($subscription = 0)
		{
		}

		/**
		 * Get Subscription Id by Order ID
		 * This function will helpfull while updating subscription status based on IPN
		 * @param unknown_type $OrderId
		 */
		public function GetSubScriptionIdFromOrderId($OrderId){
			try
			{
				$obj = new cDatabase();
				$sql = " SELECT s.subscription_id FROM `order` o, subscription s WHERE ";
				$where = " o.order_id = '$OrderId' AND o.product_item_id = s.product_id";
				$limit = ' LIMIT 1';
				$sql = $sql.$where.$limit;
				$query = $obj->db->query($sql);
				return $query->rows;
			}
			catch(Exception $ex)
			{
				die($ex->getMessage());
			}
		}
		

		public function CancelSubscription($OrderId)
		{
			try
			{		
				$obj = new cDatabase();
				$sql = "UPDATE order SET order_status=7 WHERE order_id = '$OrderId'";
				$query = $obj->db->query($sql);
				
				if($query) {
					return true;
				} else {
					return false;
				}
			}
			catch(Exception $ex)
			{
				echo $ex->getMessage();
			}
		}
		
		public function GetListById($SubscriptionId = 0)
		{
			try
			{
				$obj = new cDatabase();				
				$sql = " SELECT s.subscription_id,s.product_id,s.early_expiry_mail_days,s.late_expiry_mail_days,
								s.is_free_subscription, s.duration, pd.productName ,f.NAME AS file_name,f.path,pd.description
						FROM subscription s
									LEFT JOIN product_description pd ON pd.product_id = s.product_id
									LEFT JOIN product p ON p.product_id= s.product_id
									LEFT JOIN files f ON f.file_id = p.image_id
						WHERE s.subscription_id = $SubscriptionId
						GROUP BY s.subscription_id";				
				$query = $obj->db->query($sql);
				return $query->rows;
			}
			catch(Exception $ex)
			{
				die($ex->getMessage());
			}
		}
		
		public function GetAllActiveSubscriptionList($UserId) {
			$TodaysDate = date('Y-m-d');
			try
			{
				$obj = new cDatabase();
				// AND (u.userid = 55 OR u.parent_user_id = 55) 
				$SQL = "SELECT u.UserId, u.Username, up.FirstName, up.LastName, su.start_date, su.end_date, su.subscr_id
						FROM `subscription_user` su , user u, `userprofile` up
						WHERE su.user_id = u.userid AND up.userid = u.userid AND su.user_id = up.userid AND 
							  subscr_id IS NOT NULL AND END_DATE >= '$TodaysDate' AND su.status = 5
						      AND (u.userid = $UserId OR u.parent_user_id = $UserId) ";				
				
				$query = $obj->db->query($SQL);
				return $query->rows;
			}
			catch(Exception $ex)
			{
				die($ex->getMessage());
			}
		}
		
		public function DeleteUsersOfAstroPage($UserId) {
			$obj = new cDatabase();			
			$SQL = "UPDATE `user` SET `Status` = 0 WHERE userid = $UserId ";
			
			$query = $obj->db->query($SQL);
			
			if($query) {
				return true;
			} else {
				return false;
			}
		}
		
		public function GetSubscriptionId($UserId) {
			$TodaysDate = date('Y-m-d');
			try
			{
				$obj = new cDatabase();
				$SQL = "SELECT 
								u.UserId, u.Username, up.FirstName, up.LastName, su.start_date, su.end_date, su.subscr_id
						FROM `subscription_user` su , user u, `userprofile` up
						WHERE su.user_id = u.userid AND up.userid = u.userid AND su.user_id = up.userid AND
							subscr_id IS NOT NULL AND END_DATE >= '$TodaysDate' AND su.status = 5
							AND (u.userid = $UserId ) ";
		
				$query = $obj->db->query($SQL);
				return $query->rows;
			}
			catch(Exception $ex){
				die($ex->getMessage());
			}
		}
	}
}
?>