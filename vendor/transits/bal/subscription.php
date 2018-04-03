<?php 
if(!require_once("include.php")) {
	//throw new Exception("Failed to include 'userRepository.php'");
	require_once("include.php");
}

if (!defined('BASEURL')) {
	$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
	define("BASEURL", $protocol . $_SERVER['SERVER_NAME']. "/");
}

if (!defined('ROOTPATH')) {
	define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/');
}


if(!@include("dal/subscriptionRepository.php"))
{
	require_once(DALPATH."/subscriptionRepository.php");
}

if(!@include("dto/subscriptionDTO.php"))
{
	require_once(DTOPATH."/subscriptionDTO.php");
}

if(!class_exists("Subscription")) {
	class Subscription
	{
		private $APIUsernameSandBox = 'abhi_p_1352295643_biz_api1.yahoo.co.in';
		private $APIPasswordSandBox = '1352295661';
		private $APISignatureSandBox = 'A0HIsAs11TnZ9IXx3SGHBoq-Za4hAESo1PJ9GR7bxms.DWAEK18gK4It';
		
		private $APIUsername = 'ard_api1.world-of-wisdom.com';
		private $APIPassword = 'FRFNC9PB7QK8GPFX';
		private $APISignature = 'AIrYsTugr48pAeQp75l5Jv-O6kZ5A11rpU5xsK1JjXVHQ.LSrdZanYoz';
		
		public function GetById($subscription_id)
		{
			$subscriptionRepository = new SubscriptionRepository();
			$currencyDTO = array();
			try
			{
				$result = $subscriptionRepository->GetById($subscription_id);
				$subscriptionDTOObj = new SubscriptionDTO();
				for($i=0;$i<count($result);$i++)
				{
					//print_r($result[$i]);

					if($subscriptionDTOObj->subscription_id != $result[$i]['subscription_id'])
					{
						$subscriptionDTOObj->subscription_id = $result[$i]['subscription_id'];
						$subscriptionDTOObj->sort_order = $result[$i]['sort_order'];
						$subscriptionDTOObj->status = $result[$i]['status'];
						$subscriptionDTOObj->parent_subscription_id = $result[$i]['parent_subscription_id'];

						$subscriptionDescriptionArray = array();
							
						$subscriptionDescriptionDTO = new SubscriptionDescriptionDTO();
						$subscriptionDescriptionDTO->subscription_id = $result[$i]['subscription_id'];
						$subscriptionDescriptionDTO->subscription_description_id = $result[$i]['subscription_description_id'];
						$subscriptionDescriptionDTO->name = $result[$i]['name'];
						$subscriptionDescriptionDTO->description = $result[$i]['description'];
						$subscriptionDescriptionDTO->language = $result[$i]['language_id'];
						$subscriptionDescriptionArray[] = $subscriptionDescriptionDTO;
					}
					else
					{
						$subscriptionDescriptionDTO = new SubscriptionDescriptionDTO();
						$subscriptionDescriptionDTO->subscription_id = $result[$i]['subscription_id'];
						$subscriptionDescriptionDTO->subscription_description_id = $result[$i]['subscription_description_id'];
						$subscriptionDescriptionDTO->name = $result[$i]['name'];
						$subscriptionDescriptionDTO->description = $result[$i]['description'];
						$subscriptionDescriptionDTO->language = $result[$i]['language_id'];
						$subscriptionDescriptionArray[] = $subscriptionDescriptionDTO;
					}



				}
				$subscriptionDTOObj->subscription_description = $subscriptionDescriptionArray;

				$currencyDTO[] =$subscriptionDTOObj;
			}
			catch(Exception $ex)
			{
				//die($ex->getMessage());
			}
			return json_encode($currencyDTO);
		}

		public function GetList($subscription_id = 0)
		{
			$subscriptionRepository = new SubscriptionRepository();
			$subscriptionDTOArray = array();
			try
			{
				$result = $subscriptionRepository->GetList($subscription_id);
				//return json_encode($result);
				/*echo "<pre>";
				print_r($result);
				echo "</pre>";*/

				if(empty($result))
				{
				}
				else
				{
					$id= 0;
					for($i=0;$i<count($result);$i++)
					{
						$subscriptionDTOObj = new SubscriptionDTO();
						$subscriptionDTOObj->subscription_id 		= 	$result[$i]['subscription_id'];
						$subscriptionDTOObj->product_id 			= 	$result[$i]['product_id'];
						$subscriptionDTOObj->early_expiry_days 		= 	$result[$i]['early_expiry_mail_days'];
						$subscriptionDTOObj->late_expiry_days 		= 	$result[$i]['late_expiry_mail_days'];
						$subscriptionDTOObj->is_free_subscription 	= 	$result[$i]['is_free_subscription'];
						$subscriptionDTOObj->duration 				= 	$result[$i]['duration'];
						$subscriptionDTOObj->product_name 			= 	$result[$i]['productName'];
						$subscriptionDTOObj->discription			= 	$result[$i]['description'];
						$subscriptionDTOObj->image_name				= 	$result[$i]['file_name'];
						$subscriptionDTOObj->image_path				= 	$result[$i]['path'];

						$result2 = $subscriptionRepository->GetPriceListById($result[$i]['subscription_id']);
						$subscriptionPriceArray = array();
						if(!empty($result2))
						{
							for($j=0;$j<count($result2);$j++)
							{
								$subscriptionPriceDTO = new SubscriptionPriceDTO();
								$subscriptionPriceDTO->subscription_id 			= $result2[$j]['subscription_id'];
								$subscriptionPriceDTO->subscription_price_id 	= $result2[$j]['subscription_price_id'];
								$subscriptionPriceDTO->price 					= $result2[$j]['price'];
								$subscriptionPriceDTO->renew_price 				= $result2[$j]['renew_price'];
								$subscriptionPriceDTO->upgrade_price 			= $result2[$j]['upgrade_price'];
								$subscriptionPriceDTO->currency_id 				= $result2[$j]['currency_id'];

								$subscriptionPriceDTO->currency_name 			= $result2[$j]['NAME'];
								$subscriptionPriceDTO->currency_symbol 			= $result2[$j]['symbol'];
								$subscriptionPriceDTO->currency_code 			= $result2[$j]['CODE'];

								$subscriptionPriceArray[] = $subscriptionPriceDTO;
							}
						}
						$subscriptionDTOObj->subscription_price = $subscriptionPriceArray;
						$subscriptionDTOArray[] = $subscriptionDTOObj;
					}

				}
				//print_r($languageDTO);
				return json_encode($subscriptionDTOArray);
			}
			catch(Exception $ex)
			{
				die($ex->getMessage());
			}
		}

		public function GetSubscriptionById($subscription_id)
		{
			$subscriptionRepository = new SubscriptionRepository();
			$subscriptionDTOObj = new SubscriptionDTO();
			try
			{
				$result = $subscriptionRepository->GetSubscriptionById($subscription_id);
				if(!empty($result))
				{

					$priceArray = array();
					for($i=0;$i<count($result);$i++)
					{
						if($subscriptionDTOObj->subscription_id != 	$result[$i]['subscription_id'])
						{
							$subscriptionDTOObj->subscription_id 		= 	$result[$i]['subscription_id'];
							$subscriptionDTOObj->product_id 			= 	$result[$i]['product_id'];
							$subscriptionDTOObj->early_expiry_days 		= 	$result[$i]['early_expiry_mail_days'];
							$subscriptionDTOObj->late_expiry_days 		= 	$result[$i]['late_expiry_mail_days'];
							$subscriptionDTOObj->is_free_subscription 	= 	$result[$i]['is_free_subscription'];
							$subscriptionDTOObj->duration 				= 	$result[$i]['duration'];
						}
							
						$subscriptionPriceDTO = new SubscriptionPriceDTO();
						$subscriptionPriceDTO->subscription_price_id 	= $result[$i]['subscription_price_id'];
						$subscriptionPriceDTO->price 					= $result[$i]['price'];
						$subscriptionPriceDTO->renew_price 				= $result[$i]['renew_price'];
						$subscriptionPriceDTO->upgrade_price 			= $result[$i]['upgrade_price'];
						$subscriptionPriceDTO->currency_id 				= $result[$i]['currency_id'];
							
						$priceArray[] = $subscriptionPriceDTO;
					}
					$subscriptionDTOObj->subscription_price = $priceArray;
				}
				return json_encode($subscriptionDTOObj);
			}
			catch(Exception $ex)
			{
				die($ex->getMessage());
			}
		}

		public function Save($data)
		{
			$subscriptionRepository = new SubscriptionRepository();
			try
			{
				return $subscriptionRepository->SaveSubscription($data);
			}
			catch(Exception $ex)
			{
				die($ex->getMessage());
			}
		}

		public function Update($data)
		{
			$subscriptionRepository = new SubscriptionRepository();
			try
			{
				$result = $subscriptionRepository->Update($data);
					
				if(!empty($result))
				{
					return $result;
				}
				else
				{
					return '';
				}
			}
			catch(Exception $ex)
			{
				//$userDetail->resultState->code=1;
				//$userDetail->resultState->message=$ex;
				die($ex->getMessage());
			}
		}

		public function SaveSubscribedUser($data)
		{
			$subscriptionRepository = new SubscriptionRepository();
			try
			{
				return $subscriptionRepository->SaveSubscribedUser($data);
			}
			catch(Exception $ex)
			{
				die($ex->getMessage());
			}
		}

		public function UpdateSubscribedUserStatus($user_id,$subscription_id,$status)
		{
			$subscriptionRepository = new SubscriptionRepository();
			try
			{
				return $subscriptionRepository->UpdateSubscribedUserStatus($user_id,$subscription_id,$status);
			}
			catch(Exception $ex)
			{
				die($ex->getMessage());
			}
		}

		public function IsSubscribeUser($user_id)
		{
			$subscriptionRepository = new SubscriptionRepository();
			try
			{
				return $subscriptionRepository->IsSubscribeUser($user_id);
			}
			catch(Exception $ex)
			{
				die($ex->getMessage());
			}
		}

		public function GetUserActiveSubscription($user_id)
		{
			$subscriptionRepository = new SubscriptionRepository();
			try
			{
				return $subscriptionRepository->GetUserActiveSubscription($user_id);
			}
			catch(Exception $ex)
			{
				die($ex->getMessage());
			}
		}

		public function GetSubScriptionIdFromOrderId($OrderId)
		{
			$subscriptionRepository = new SubscriptionRepository();
			try
			{
				return $subscriptionRepository->GetSubScriptionIdFromOrderId($OrderId);
			}
			catch(Exception $ex)
			{
				die($ex->getMessage());
			}
		}

		public function UpdateSubscribedUserStatusFromIPN($OrderId, $UserId, $SubscriptionId, $Status, $PayPalSubScrId)
		{
			$subscriptionRepository = new SubscriptionRepository();
			try
			{
				return $subscriptionRepository->UpdateSubscribedUserStatusFromIPN($OrderId, $UserId, $SubscriptionId, $Status, $PayPalSubScrId);
			}
			catch(Exception $ex)
			{
				die($ex->getMessage());
			}
		}

		public function CancelSubscription($OrderId)
		{
			$subscriptionRepository = new SubscriptionRepository();
			try
			{
				return $subscriptionRepository->CancelSubscription($OrderId);
			}
			catch(Exception $ex)
			{
				die($ex->getMessage());
			}
		}

		public function GetListById($SubscriptionId = 0)
		{
			$subscriptionRepository = new SubscriptionRepository();
			$subscriptionDTOArray = array();
			try
			{
				$result = $subscriptionRepository->GetListById($SubscriptionId);

				if(empty($result))
				{
				}
				else
				{
					$id= 0;
					for($i=0;$i<count($result);$i++)
					{
						$subscriptionDTOObj = new SubscriptionDTO();
						$subscriptionDTOObj->subscription_id 		= 	$result[$i]['subscription_id'];
						$subscriptionDTOObj->product_id 			= 	$result[$i]['product_id'];
						$subscriptionDTOObj->early_expiry_days 		= 	$result[$i]['early_expiry_mail_days'];
						$subscriptionDTOObj->late_expiry_days 		= 	$result[$i]['late_expiry_mail_days'];
						$subscriptionDTOObj->is_free_subscription 	= 	$result[$i]['is_free_subscription'];
						$subscriptionDTOObj->duration 				= 	$result[$i]['duration'];
						$subscriptionDTOObj->product_name 			= 	$result[$i]['productName'];
						$subscriptionDTOObj->discription			= 	$result[$i]['description'];
						$subscriptionDTOObj->image_name				= 	$result[$i]['file_name'];
						$subscriptionDTOObj->image_path				= 	$result[$i]['path'];

						$result2 = $subscriptionRepository->GetPriceListById($result[$i]['subscription_id']);
						$subscriptionPriceArray = array();
						if(!empty($result2))
						{
							for($j=0;$j<count($result2);$j++)
							{
								$subscriptionPriceDTO = new SubscriptionPriceDTO();
								$subscriptionPriceDTO->subscription_id 			= $result2[$j]['subscription_id'];
								$subscriptionPriceDTO->subscription_price_id 	= $result2[$j]['subscription_price_id'];
								$subscriptionPriceDTO->price 					= $result2[$j]['price'];
								$subscriptionPriceDTO->renew_price 				= $result2[$j]['renew_price'];
								$subscriptionPriceDTO->upgrade_price 			= $result2[$j]['upgrade_price'];
								$subscriptionPriceDTO->currency_id 				= $result2[$j]['currency_id'];
								$subscriptionPriceDTO->isdefault 				= $result2[$j]['isdefault'];

								$subscriptionPriceDTO->currency_name 			= $result2[$j]['NAME'];
								$subscriptionPriceDTO->currency_symbol 			= $result2[$j]['symbol'];
								$subscriptionPriceDTO->currency_code 			= $result2[$j]['CODE'];

								$subscriptionPriceArray[] = $subscriptionPriceDTO;
							}
						}
						$subscriptionDTOObj->subscription_price = $subscriptionPriceArray;
						$subscriptionDTOArray[] = $subscriptionDTOObj;
					}

				}
				return json_encode($subscriptionDTOArray);
			}
			catch(Exception $ex)
			{
				die($ex->getMessage());
			}
		}

		public function GetAllActiveSubscriptionList($UserId) {
			$subscriptionRepository = new SubscriptionRepository();
			
			return $subscriptionRepository->GetAllActiveSubscriptionList($UserId) ;
		}

		/**
		 * Performs an Express Checkout NVP API operation as passed in $action.
		 *
		 * Although the PayPal Standard API provides no facility for cancelling a subscription, the PayPal
		 * Express Checkout  NVP API can be used.
		 */
		function ChangeSubscriptionStatus( $ProfileId, $ProfileAction ) {

			$api_request = 'USER=' . urlencode( trim($this->APIUsername) )
			.  '&PWD=' . urlencode( trim($this->APIPassword) )
			.  '&SIGNATURE=' . urlencode( trim($this->APISignature) )
			.  '&VERSION=76.0'
			.  '&METHOD=ManageRecurringPaymentsProfileStatus'
			.  '&PROFILEID=' . urlencode( trim($ProfileId) )
			.  '&ACTION=' . urlencode( trim($ProfileAction) )
			.  '&NOTE=' . urlencode( 'Profile cancelled at store' );

			$ch = curl_init();
			// For LIVE transactions, change to 'https://api-3t.paypal.com/nvp'
			curl_setopt( $ch, CURLOPT_URL, 'https://api-3t.paypal.com/nvp' );
			
			// For SANDBOX transactions, change to 'https://api-3t.sandbox.paypal.com/nvp'
			//curl_setopt( $ch, CURLOPT_URL, 'https://api-3t.sandbox.paypal.com/nvp' ); 
			
			curl_setopt( $ch, CURLOPT_VERBOSE, 1 );

			// Uncomment these to turn off server and peer verification
			// curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );
			// curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, FALSE );
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
			curl_setopt( $ch, CURLOPT_POST, 1 );

			// Set the API parameters for this transaction
			curl_setopt( $ch, CURLOPT_POSTFIELDS, $api_request );

			// Request response from PayPal
			$response = curl_exec( $ch );

			// If no response was received from PayPal there is no point parsing the response
			if( ! $response )
				die( 'Calling PayPal to change_subscription_status failed: ' . curl_error( $ch ) . '(' . curl_errno( $ch ) . ')' );

			curl_close( $ch );

			// An associative array is more usable than a parameter string
			parse_str( $response, $parsed_response );

			return $parsed_response;
		}

		/**
		 * DeleteUsersOfAstroPage()
		 * 
		 * Updating User Table to remove data of AstroPage Users.
		 * 
		 * @param Int $UserId
		 * @return boolean
		 */
		public function DeleteUsersOfAstroPage($UserId) {
			$isDeleted = false;
			
			$subscriptionRepository = new SubscriptionRepository();	
			$isDeleted = $subscriptionRepository->DeleteUsersOfAstroPage($UserId);
			
			$list = $subscriptionRepository->GetSubscriptionId($UserId);
			
			if(count($list) > 0) {
				$ProfileId = $list[0]["subscr_id"];
				if($ProfileId != "") {
					$this->ChangeSubscriptionStatus($ProfileId, 'Cancel');
				}
			}
			return $isDeleted;
		}
	}
}

if(isset($_REQUEST['btnSaveSubscription']))
{
	require_once("currency.php");
	$objCurrency = new Currency();
	$result = $objCurrency->GetList();
	$currencies = json_decode($result);


	//print_r($languages);
	print_r($_REQUEST);

	$subscriptionDTOObj = new SubscriptionDTO();

	$subscriptionDTOObj->subscription_id 		= 	$_REQUEST['subscription_id'];
	$subscriptionDTOObj->product_id 			= 	$_REQUEST['ddProduct'];
	$subscriptionDTOObj->early_expiry_days 		= 	$_REQUEST['txtEarlyExpiryDays'];
	$subscriptionDTOObj->late_expiry_days 		= 	$_REQUEST['txtLateExpiryDays'];
	$subscriptionDTOObj->is_free_subscription 	= 	$_REQUEST['ddIsFreeSubscription'];
	$subscriptionDTOObj->duration 				= 	$_REQUEST['txtDuration'];


	$subscriptionPriceArray = array();
	for($i=0;$i<count($currencies);$i++)
	{
		$id = $currencies[$i]->currency_id;
		$subscriptionPriceDTO = new SubscriptionPriceDTO();
		$subscriptionPriceDTO->subscription_price_id 	= $_REQUEST['hdnPriceId'][$id];
		$subscriptionPriceDTO->price 					= $_REQUEST['txtPrice'][$id];
		$subscriptionPriceDTO->renew_price 				= $_REQUEST['txtRenewPrice'][$id];
		$subscriptionPriceDTO->upgrade_price 			= $_REQUEST['txtUpgradePrice'][$id];
		$subscriptionPriceDTO->currency_id 				= $id;
			
		$subscriptionPriceArray[] = $subscriptionPriceDTO;
	}
	$subscriptionDTOObj->subscription_price = $subscriptionPriceArray;

	//print_r($subscriptionDTOObj);
	//exit;

	//echo 'in task';
	$subscription = new Subscription();

	if($_REQUEST['subscription_id'] < 1)
	{
		// Save Subscription
		if($subscription->Save($subscriptionDTOObj) > 0)
		{
			$message = '';
		}
		else
		{
			$message = 'An error occured while saving data';
		}
	}
	else
	{
		// Update Subscription
		if($subscription->Update($subscriptionDTOObj))
		{
			$message = '';
		}
		else
		{
			$message = 'An error occured while saving data';
		}
	}
	$host  = $_SERVER['HTTP_HOST'];
	//$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
	$uri   = DIR_ADMIN;
	$extra = 'subscription.php?message='.$message;
	header("Location: http://$host$uri/$extra");
}

if(isset($_REQUEST['task'])) {	
	if(trim($_REQUEST['task']) == 'cancelsubscription') {
		$subscription = new Subscription();		
		$ReturnResult = $subscription->ChangeSubscriptionStatus($_REQUEST['pid'], 'Cancel');

		if( isset($ReturnResult['ACK'])) {
			if(trim($ReturnResult['ACK']) == "Success") {
				echo true;
			} else {
				echo false;
			}
		}		
		exit;		
	} elseif (trim($_REQUEST['task']) == 'deletesubscription') {
		$subscription = new Subscription();

		$isDeleted =  $subscription->DeleteUsersOfAstroPage($_REQUEST['uid']);

		echo $isDeleted;
		exit;
	}
}
?>
