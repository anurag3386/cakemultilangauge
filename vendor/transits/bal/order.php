<?php
@session_start();
ini_set("display_errors", 1);
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);

require_once("include.php");
require_once("acs.php");
if (!class_exists('cDatabase')) {
	if(!include("cDatabase.php")) {
		//require_once("../cDatabase.php");
		require_once(ROOTPATH."cDatabase.php");
	}
}

if (!class_exists('ACSStateList')) {
	if(!include(ROOTPATH ."/helper/acs/ACSStateList.php")) {
		//require_once("../cDatabase.php");
		require_once(ROOTPATH ."/helper/acs/ACSStateList.php");
	}
}

require_once(DALPATH."/orderRepository.php");

if(!@include("dto/orderDTO.php")) {
	//throw new Exception("Failed to include 'userRepository.php'");
	require_once(DTOPATH."/orderDTO.php");
}

require_once("user.php");
require_once(DALPATH."/productRepository.php");

class Order {
	public function SaveOrder($data) {
		$orderRepository = new OrderRepository();
		$returnValue = $orderRepository->SaveOrder($data);
		return $returnValue;
	}

	public function SaveOrderShipping($data) {
		$orderRepository = new OrderRepository();
		$returnValue = $orderRepository->SaveOrderShipping($data);
		return $returnValue;
	}

	public function UpdateOrderData($data) {
		$orderRepository = new OrderRepository();
		$returnValue = $orderRepository->UpdateOrderData($data);
		return $returnValue;
	}

	public function UpdateOrderShipping($data) {
		$orderRepository = new OrderRepository();
		$returnValue = $orderRepository->UpdateOrderShipping($data);
		return $returnValue;
	}

	public function GetOrderDetailByOrderId($order_id) {
		$orderRepository = new OrderRepository();
		$returnValue = $orderRepository->GetOrderDetailByOrderId($order_id);
		return $returnValue;
	}

	public function GetOrderShippingDetailByOrderId($order_id) {
		$orderRepository = new OrderRepository();
		$returnValue = $orderRepository->GetOrderShippingDetailByOrderId($order_id);
		return $returnValue;
	}

	public function SaveBirthData($data) {
		$orderRepository = new OrderRepository();
		$returnValue = $orderRepository->SaveBirthData($data);
		return $returnValue;
	}

	public function UpdateBirthData($data) {
		$orderRepository = new OrderRepository();
		$returnValue = $orderRepository->UpdateBirthData($data);
		return $returnValue;
	}

	public function GetUserBirthDetailByOrderId($order_id) {
		$orderRepository = new OrderRepository();
		$returnValue = $orderRepository->GetUserBirthDetailByOrderId($order_id);
		return $returnValue;
	}

	public function SaveLoversData($data) {
		$orderRepository = new OrderRepository();
		$returnValue = $orderRepository->SaveLoversData($data);
		return $returnValue;
	}

	public function UpdateLoversData($data) {
		$orderRepository = new OrderRepository();
		$returnValue = $orderRepository->UpdateLoversData($data);
		return $returnValue;
	}

	public function GetLoversData($order_id) {
		$orderRepository = new OrderRepository();
		$returnValue = $orderRepository->GetLoversData($order_id);
		return $returnValue;
	}

	public function UpdateOrderStatusFromIPN($order_id,$status) {
		// Order Status
		//1 = awaiting for payment,
		//		2 = payment accepted,
		//		3 = refund

		$orderRepository = new OrderRepository();
		$returnValue = $orderRepository->UpdateOrderStatusFromIPN($order_id,$status);
		return $returnValue;
	}

	public function GetUserIdByOrderId($order_id) {
		$orderRepository = new OrderRepository();
		$returnValue = $orderRepository->GetUserIdByOrderId($order_id);
		return $returnValue;
	}

	public function GetOrderCountForUser($user_id) {
		$orderRepository = new OrderRepository();
		$returnValue = $orderRepository->GetOrderCountForUser($user_id);
		return $returnValue;
	}

	public function GetOrderListForUser($user_id,$items_per_page,$start) {
		$orderRepository = new OrderRepository();
		$returnValue = $orderRepository->GetOrderListForUser($user_id,$items_per_page,$start);
		return $returnValue;
	}

	public function GetOrderDetailForUser($user_id,$order_id) {
		$orderRepository = new OrderRepository();
		$returnValue = $orderRepository->GetOrderDetailForUser($user_id,$order_id);
		return $returnValue;
	}

	public function GetPurchasedReportCountByUserId($user_id,$language_code) {
		$orderRepository = new OrderRepository();
		$returnValue = $orderRepository->GetPurchasedReportCountByUserId($user_id,$language_code);
		return $returnValue;
	}

	public function GetPurchasedReportByUserId($user_id,$language,$items_per_page,$start) {
		$orderRepository = new OrderRepository();
		$returnValue = $orderRepository->GetPurchasedReportByUserId($user_id,$language,$items_per_page,$start);
		return $returnValue;
	}

	public function GetPreviewReportByUserId($user_id,$language) {
		$orderRepository = new OrderRepository();
		$returnValue = $orderRepository->GetPreviewReportByUserId($user_id,$language);
		return $returnValue;
	}

	public function SaveOrderToken($order_id,$token,$product_item_id) {
		$orderRepository = new OrderRepository();
		$returnValue = $orderRepository->SaveOrderToken($order_id,$token,$product_item_id);
		return $returnValue;
	}

	public function GetOrderToken($token) {
		$orderRepository = new OrderRepository();
		$returnValue = $orderRepository->GetOrderToken($token);
		return $returnValue;
	}

	public function UpdateOrderToken($download_count,$token) {
		$orderRepository = new OrderRepository();
		$returnValue = $orderRepository->UpdateOrderToken($download_count,$token);
		return $returnValue;
	}

	public function SaveOrderTransactionDetail($data) {
		$orderRepository = new OrderRepository();
		$returnValue = $orderRepository->SaveOrderTransactionDetail($data);
		return $returnValue;
	}

	public function UpdateOrderForPreviewReport($data) {
		if(!empty($data) && count($data)>0) {
			// Get Order_Id whose deliver_option = 3 and user_id = $data['user_id']
			$user_id = $data->UserId;

			$orderRepository = new OrderRepository();
			$order_ids = $orderRepository->GetOrderIdToUpdatePreviewReportOrder($user_id);

			// If record found,
			for($i=0;$i<count($order_ids);$i++) {
				$order_id = $order_ids[$i]['order_id'];
				//then traverse through records and update order status = 12 (queue)
				$orderRepository->UpdateOrderStatusForPreviewReport($order_id);

				// Then update birthdata for that order

				$objUserBirthData = new userBirthDataDTO();

				$objUserBirthData->day = $data->Day;
				$objUserBirthData->month = $data->Month;
				$objUserBirthData->year = $data->Year;

				if($data->Hours == -1) {
					$objUserBirthData->untimed = 'yes';
				}
				else {
					$objUserBirthData->hour = $data->Hours;
					$objUserBirthData->minute = $data->Minutes;
				}

				$objASC = new acsAtlas();
				$result = $objASC->GetACSDetails($data->country,$data->city,1);

				if(count($result) > 0) {
					$objUserBirthData->summerref = $result[0]->type;
					$objUserBirthData->zoneref = $result[0]->zone;
				}

				$objUserBirthData->latitude = $data->latitude;
				$objUserBirthData->longitude = $data->longitude;
				$objUserBirthData->zoneref = $result[0]->zone;
				$objUserBirthData->place = $data->city;
				$objUserBirthData->state = $data->country;
				$objUserBirthData->orderid =  $order_id;

				$orderRepository->UpdateOrderBirthDataForPreviewReport($objUserBirthData);
			}
		}
	}

	public function SendPaymentAcceptMailForReport($order_id, $LanguageId = 'en') {
		if(!empty($order_id)) {
			$orderRepository = new OrderRepository();
			//$product_type = 5;
			//$language_code = 'en';
			$ReturnResult = true;

			$language_code = $LanguageId;
			$result = $orderRepository->GetOrderDetailToSendPaymentAcceptMailForReport($order_id,$language_code);
						
			if(count($result)>0) {
				if($result['product_type'] == 5)        //Purchase Report
				{
					if(!empty($result['email_id'])) {
						if(trim($result['product_item_id']) == "16") {  //LOVER reprot data to Admin
							$isSent = genericMail::SendLoverReportNotification( $order_id );
						}

						//SENDING PRINTED REPORT DATA to Adrian sir
						if(trim($result['delivery_option']) == "2" || trim($result['delivery_option']) == 2) {
							if( trim($result['product_item_id']) == "16") {
							} else {
								$isSent = genericMail::SendPrintedReportNotification( $order_id );
							}
						}

						$sender = "service@astrowow.com";
						$rec = array('to' => $result['email_id']);

						$data = array(
								'subject'			=> "Thank you for ordering ".$result['productName']." ",
								'mailtext'			=> "",
								'type'				=> "html",
								'order_date'		=> date("Y-m-d"),
								'product_name'		=> $result['productName'],
								'product_id'		=> $result['product_id'],
								'first_name'		=> $result['first_name'],
								'last_name'			=> $result['last_name'],
								'delivery_option'	=> $result['delivery_option'],
								'email_id'          => $result['email_id'],
								'language_id'		=> $language_code
						);

						if ( genericMail::SendPaymentAcceptMailForReport( $sender, $rec, $data ) ) {
							$ReturnResult = true;
						} else {
							$ReturnResult = false;
						}
					}
					return $ReturnResult;
				}
				else if($result['product_type'] == 3)        //Buy Software CD
				{
					if(!empty($result['email_id'])) {

						$sender = "service@astrowow.com";
						$rec = array('to' => $result['email_id'] );

						$data = array(
								'subject'			=> "Thank you for ordering ".$result['productName']." ",
								'mailtext'			=> "",
								'type'				=> "html",
								'order_date'		=> date("Y-m-d"),
								'product_name'		=> $result['productName'],
								'product_id'		=> $result['product_id'],
								'first_name'		=> $result['first_name'],
								'last_name'			=> $result['last_name'],
								'delivery_option'	=> $result['delivery_option'],
								'email_id'          => $result['email_id'],
								'language_id'		=> $language_code
						);

						try {
							if ( genericMail::SendPaymentAcceptMailForSoftware( $sender, $rec, $data ) ) {
								$ReturnResult = true;
							}

							if ( genericMail::SendSoftwareCDInstruction( $sender, $rec, $data ) ) {
								$ReturnResult = true;
							} else {
								$ReturnResult = true;
							}
						}
						catch(Exception $ex) {
							/*mail("parmaramit1111@gmail.com", "Error While ", $ex->getMessage());*/
							$ReturnResult = true;
						}

						return $ReturnResult;
					}
				}
				else if($result['product_type'] == 2)        //Registered Shareware
				{
					if(!empty($result['email_id'])) {
						$sender = "service@astrowow.com";

						$rec = array('to' => $result['email_id'] );

						$data = array(
								'subject'		=> "Thank you for ordering ".$result['productName']." ",
								'mailtext'		=> "",
								'type'			=> "html",
								'order_date'		=> date("Y-m-d"),
								'product_name'		=> $result['productName'],
								'product_id'		=> $result['product_id'],
								'first_name'		=> $result['first_name'],
								'last_name'		=> $result['last_name'],
								'delivery_option'	=> $result['delivery_option'],
								'email_id'              => $result['email_id'],
								//'language_id'		=> $result['language_code']
								'language_id'		=> $language_code
						);

						if ( genericMail::SendPaymentAcceptMailForRegisteredShareware( $sender, $rec, $data ) ) {
							return true;
						} else {
							return false;
						}
					}
				}
			}
		}
		return false;
	}

	public function GetOrderTransactionDetailById($OrderId) {
		$orderRepository = new OrderRepository();
		$returnValue = $orderRepository->GetOrderTransactionDetailById($OrderId);
		return $returnValue;
	}

	public function GetDiscountedPricesByProductAndCurrency($ProductItemId, $CurrencyId) {
		$productRepository = new ProductRepository();
		$returnValue = $productRepository->GetDiscountedPricesByProductAndCurrency($ProductItemId, $CurrencyId);
		return $returnValue;
	}

	public function GetFreeSoftwareDownloadByUserId($UserId, $Language) {
		$orderRepository = new OrderRepository();
		$returnValue = $orderRepository->GetFreeSoftwareDownloadByUserId($UserId, $Language);
		return $returnValue;
	}

	public function SaveUpsaleOrder($NewOrderId, $OldOrderId) {
		$orderRepository = new OrderRepository();
		$returnValue = $orderRepository->SaveUpsaleOrder($NewOrderId, $OldOrderId);
		return $returnValue;
	}

	public function GetOrderDetailForReport($OrderID) {
		$orderRepository = new OrderRepository();
		$returnValue = $orderRepository->GetOrderDetailForReport($OrderID);
		return $returnValue;
	}	

	public function GetOrderDetailForSoftware($OrderID) {
		$orderRepository = new OrderRepository();
		$returnValue = $orderRepository->GetOrderDetailForSoftware($OrderID);
		return $returnValue;
	}
	

	public function SendPaymentAcceptMailForBundleProduct($order_id, $LanguageId = 'en', $CategoryId) {
		if(!empty($order_id)) {
			$orderRepository = new OrderRepository();
			$ReturnResult = true;
	
			$language_code = $LanguageId;
			$result = $orderRepository->GetBundleOrderDetailToSendPaymentAcceptMailForReport($order_id,$language_code, $CategoryId);
			
			if(count($result)>0) {
				if($result['product_type'] == 3)        //Buy Software CD
				{
					if(!empty($result['email_id'])) {
	
						$sender = "service@astrowow.com";
						$rec = array('to' => $result['email_id'] );
	
						$data = array(
								'subject'			=> "Thank you for ordering ".$result['productName']." ",
								'mailtext'			=> "",
								'type'				=> "html",
								'order_date'		=> date("Y-m-d"),
								'product_name'		=> $result['productName'],
								'product_id'		=> $result['product_id'],
								'first_name'		=> $result['first_name'],
								'last_name'			=> $result['last_name'],
								'delivery_option'	=> $result['delivery_option'],
								'email_id'          => $result['email_id'],
								'language_id'		=> $language_code
						);
	
						try {
							if ( genericMail::SendPaymentAcceptMailForSoftware( $sender, $rec, $data ) ) {
								$ReturnResult = true;
							}
	
							if ( genericMail::SendSoftwareCDInstruction( $sender, $rec, $data ) ) {
								$ReturnResult = true;
							} else {
								$ReturnResult = true;
							}
						}
						catch(Exception $ex) {
							/*mail("parmaramit1111@gmail.com", "Error While ", $ex->getMessage());*/
							$ReturnResult = true;
						}
	
						return $ReturnResult;
					}
				}
				else if($result['product_type'] == 2)        //Registered Shareware
				{
					if(!empty($result['email_id'])) {
						$sender = "service@astrowow.com";
	
						$rec = array('to' => $result['email_id'] );
	
						$data = array(
								'subject'		=> "Thank you for ordering ".$result['productName']." ",
								'mailtext'		=> "",
								'type'			=> "html",
								'order_date'		=> date("Y-m-d"),
								'product_name'		=> $result['productName'],
								'product_id'		=> $result['product_id'],
								'first_name'		=> $result['first_name'],
								'last_name'		=> $result['last_name'],
								'delivery_option'	=> $result['delivery_option'],
								'email_id'              => $result['email_id'],
								'language_id'		=> $language_code
						);
	
						if ( genericMail::SendPaymentAcceptMailForRegisteredShareware( $sender, $rec, $data ) ) {
							return true;
						} else {
							return false;
						}
					}
				}
			}
		}
		return false;
	}
	
}

if(isset($_REQUEST['hdnTask'])) {
	$id = 0;
	if(isset($_REQUEST['hdnId'])) {
		$id = $_REQUEST['hdnId'];
	}

	if($_REQUEST['hdnTask'] === 'BuySoftware') {
		if (strtolower($_SERVER['REQUEST_METHOD']) === strtolower('POST')) { 
			
			$ebody = '';
			foreach ($_POST as $key => $item) {
				$ebody .= sprintf("%s => %s \n\n",$key , $item);
			}
			$ebody1 = '';
			foreach ($_REQUEST as $key => $item) {
				$ebody1 .= sprintf("%s => %s \n\n",$key , $item);
			}
			try {
				@mail("parmaramit1111@gmail.com", "BuySoftware ORDER -POST", $ebody);
				@mail("parmaramit1111@gmail.com", "BuySoftware ORDER -REQUEST", $ebody1);
			}catch (Exception $e) {
				
			}
			
			// first save user if user_id is null or empty
			$user_id = 0;
			$order_id = 0;
			$shipping_id = 0;
			$objUserDTO = new UserDTO();
	
			if(isset($_POST['chkRegister'])) {
				//COMMENTED ON 10-Apr-2013, We are storing discount on product table, Its no longer static
				//$_REQUEST['discount'] = REGISTER_USER_PERSENTAGE_FROM_PRODUCT_PAGE;
	
				// SAVE USER
				$returnValue = SaveUser($_REQUEST, 1);
				if($returnValue > 0) {
					$user_id = $returnValue;
	
					// SAVE USER PROFILE
					$returnValue = SaveUserProfile($_REQUEST,$user_id);
	
					// SAVE USER BIRTH DETAIL
					$returnValue = SaveUserBirthDetail($_REQUEST,$user_id);
				}
				else {
					echo 'Error occured while saving user data';
				}
			}
			else if(isset($_POST['user_id']) && !empty($_POST['user_id'])) {
				$user_id = $_POST['user_id'];
	
				// UPDATE USER PROFILE
				//$returnValue = UpdateUserProfile($_REQUEST,$user_id);
			}
			else {
				/* SAVE USER */			 
			}
	
			// insert data in order table
			if(isset($_POST['order_id']) && !empty($_POST['order_id'])) {
				// UPDATE ORDER DETAIL
				$order_id = $_POST['order_id'];
				$returnValue = UpdateOrderData($_POST, $user_id,$order_id);
				if(!$returnValue) {
					echo 'Error occured while updating order data';
				}
			}
			else {
				// SAVE ORDER DETAIL
				$chk_register = 0;
				if(isset($_POST['chkRegister'])) {
					$chk_register = 1;
				}
				$returnValue = SaveOrder($_POST,$user_id,$chk_register);
				if($returnValue > 0) {
					$order_id = $returnValue;
				}
				else {
					echo 'Error occured while saving order data';
				}
			}
	
			// insert data into order_shipping table as per product type selected by user
			if(isset($_POST['ddProductType']) && ($_POST['ddProductType'] == 2 || $_POST['ddProductType'] == 3)) {
				if(isset($_POST['shipping_id']) && !empty($_POST['shipping_id'])) {
					// SAVE ORDER SHIPPING DETAIL
					$shipping_id = $_POST['shipping_id'];
					$returnValue = UpdateOrderShipping($_POST,$user_id,$order_id,$shipping_id);
					if(!$returnValue) {
						echo 'Error occured while updating order data';
					}
				}
				else {
					// UPDATE ORDER SHIPPING DETAIL
	
					$returnValue = SaveOrderShipping($_POST,$user_id,$order_id);
					if($returnValue > 0) {
						$shipping_id = $returnValue;
					}
					else {
						echo 'Error occured while saving shipping data';
					}
				}
			}
	
			if($_POST['ddProductType'] == 1 && isset($_POST['button']) && $_POST['button'] == "Submit") {
				/* Generate a unique token: */
				$token = md5(uniqid(rand(),1));
				$objOrder = new Order();
	
				//$objOrder->SaveOrderToken($order_id, $token, $_REQUEST['hdnId']);
				$objOrder->SaveOrderToken($order_id, $token, $_POST['hdnSoftwareFreeTrial']);
	
				$donwload_link = BASEURL.'download-page.php?token='.$token;
	
				$to = $_REQUEST['txtUserEmail'];
				// TODO :: for now we send mail derectly, letter on it will fired from generic mail class
				$subject = "Download Link";
				$body =  "Thank you for order free astrowow software:".$_POST['hdnProductName']."\n";
				$body .=  "To download ".$_POST['hdnProductName']." software, please click on below noted url\n";
				$body .= $donwload_link ;
				//mail($to, $subject, $body);
				SendFreeTrialSoftwareDownload($_REQUEST, $order_id, $donwload_link);
	
				$message = 'Thank you for order free shareware, shareware download link is sent to your email id.';
				echo
				"<html>".
				"<body onLoad=\"document.forms['download_form'].submit();\">".
				"<form method=\"post\" name=\"download_form\" action=\"../thank-you-free-trial.php\">";
				//echo "<input type=\"hidden\" name=\"product_item_id\" value=\"".$_REQUEST['hdnId']."\"/>";
				echo "<input type=\"hidden\" name=\"product_item_id\" value=\"".$_POST['hdnSoftwareFreeTrial']."\"/>";
				echo "<input type=\"hidden\" name=\"message\" value=\"".$message."\"/>";
				echo
				"</form>".
				"</body>".
				"</html>";
				exit;
			}
			else if(isset($_POST['button']) && $_POST['button'] == "Submit" && 
					($_POST['ddProductType'] == 2 || $_POST['ddProductType'] == 3)) {
				
				$request_url = '../confirm-order.php?order_id='.$order_id;
				echo "<html>".
						'<body onLoad=document.forms["user_form"].submit();>'.
						"<form method='post' name='user_form' action='".$request_url."'>";
				echo				"<input type='hidden' name='order_id' value='".$order_id."' />";
				echo				"<input type='hidden' name='product_item_id' value='".$_POST['hdnId']."' />";
				echo				"<input type='hidden' name='product_id' value='".$_POST['hdnProductId']."' />";
				echo				"<input type='hidden' name='payment_method' value='".$_POST['payment_method']."' />";
				echo				"<input type='hidden' name='CardNumber' value='".$_POST['CardNumber']."' />";
				echo				"<input type='hidden' name='CardCVC' value='".$_POST['CardCVC']."' />";
				echo				"<input type='hidden' name='ExpireMonth' value='".$_POST['ExpireMonth']."' />";
				echo				"<input type='hidden' name='ExpireYear' value='".$_POST['ExpireYear']."' />";
				echo				"<input type='hidden' name='hdnCurrencyCodeNo' value='".$_POST['hdnCurrencyCodeNo']."' />";
				echo 		"</form>";
				echo 	"</body>";
				echo "</html>";
			}
			//else if(isset($_REQUEST['button']) && $_REQUEST['button'] == "Place Order") {
			//SendOrderEmail($_REQUEST);
			else if(isset($_POST['button']) && $_POST['button'] == "Place Order" &&
					($_POST['ddProductType'] == 2 || $_POST['ddProductType'] == 3)) {
				
				if($_POST['ddProductType'] == 2 ) {
					$token = md5(uniqid(rand(),1));
					$objOrder = new Order();
					//$objOrder->SaveOrderToken($order_id,$token,$_REQUEST['hdnId']);
					$objOrder->SaveOrderToken($order_id,$token,$_POST['hdnSoftwareRCode']);
	
					$donwload_link = BASEURL.'download-page.php?token='.$token;
	
					$data = array(
							'subject'                   => "Register Shareware",
							'mailtext'                  => '',
							'name'                      => $_POST['txtFirstName'],
							'hdnProductName'            => $_POST['hdnProductName'],
							'donwload_link' 			=> $donwload_link,
							'type'                      => "html",
							'product_id'				=> $_POST['hdnProductId']
					);
					$rec = array( 'to'	=>  $_POST['txtUserEmail']);
					$sender = "no-reply@astrowow.com";
					$isSent = genericMail::SendSharewareDownloadLink( $sender, $rec, $data );
				}
				else if($_POST['ddProductType'] == 3) {
					//SendSoftwareBuyEmail($_REQUEST);
				}
	
				if($_POST['payment_method'] == "paypal") {
					$queryString = "action=process";
					$queryString .= '&orderid='.$order_id;
		
					$ppProductType = '';
					$orderPrefix = 'AWSW-CD-';
		
					if($_POST['ddProductType'] == 1 ) {
						$queryString .= '&product_item_id='.$_POST['hdnSoftwareFreeTrial'];
					} else if($_POST['ddProductType'] == 2 ) {
						$orderPrefix = 'AWSW-RS-';
						$ppProductType = ' - (Register shareware)';
						$queryString .= '&product_item_id='.$_POST['hdnSoftwareRCode'];
					} else if($_POST['ddProductType'] == 3 ) {
						$ppProductType = ' - (Software CD)';
						$orderPrefix = 'AWSW-CD-';
						$queryString .= '&product_item_id='.$_POST['hdnSoftwareOnCD'];
					}
					/*else {
						$ppProductType = ' - (Software CD)';
						$orderPrefix = 'AWSW-CD-';
						$queryString .= '&product_item_id='.$_REQUEST['hdnSoftwareOnCD'];
					}*/
		
					$queryString .= '&currency_code='.$_POST['hdnCurrencyCode'];
					$queryString .= '&product_name='.urlencode(utf8_decode($_POST['hdnProductName']) . $ppProductType);
					$queryString .= '&product_price='. number_format(floatval($_POST['hdnPrice']), 2);
					//$queryString .= '&product_price='.$_REQUEST['hdnPrice'];
					$queryString .= '&product_discount='. number_format(floatval($_POST['discount']), 2);
		
					if($_POST['ddProductType'] == 3 ) {
						$queryString .= '&product_shipping_charge='. number_format(floatval($_POST['hdnShipping']), 2);
					} else {
						$queryString .= '&product_shipping_charge=0';
					}
		
					$queryString .= '&prefix=AWSW-';
					//$queryString .= '&product_item_id=AWSW-'.$order_id;
					$queryString .= '&product_item_id='.$orderPrefix.$order_id;
					$queryString .= '&user_id='.$user_id;
		
					if(isset($_POST['hdnProductId']) && !empty($_POST['hdnProductId'])) {
						if($_POST['ddProductType'] == 3 ) { 					// BUY CD
							$extra = '../helper/paypal/paypal-for-software-id-'.$_POST['hdnProductId'].'.php?'.$queryString;
						} else if($_POST['ddProductType'] == 2 ) { 			// REGISTERED SHARWARE
							$extra = '../helper/paypal/paypal-for-software-id-'.$_POST['hdnProductId'].'-rs.php?'.$queryString;
						}
					} else {
						$extra = '../helper/paypal/paypal.php?'.$queryString;
					}
		
					$host  = $_SERVER['HTTP_HOST'];
					$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
					header("Location: http://$host$uri/$extra");
					exit;
				} else {
					$PostDict = array();
	
					/*** CREDIT CARD ****/
					$PostDict["CardNumber"] = trim($_POST["CardNumber"]);
					$PostDict["CardCVC"] = trim($_POST["CardCVC"]);
					$PostDict["ExpireMonth"] = trim($_POST["ExpireMonth"]);
					$PostDict["ExpireYear"] = trim($_POST["ExpireYear"]);
					$PostDict["PaymentMethod"] = 2;
					/*** CREDIT CARD ****/
	
					$currencyCode = $_POST["hdnCurrencyCode"];					// or other currency ('GBP', 'EUR', 'JPY', 'CAD', 'AUD')
					$amount = $_POST["hdnPrice"];						// or other currency ('GBP', 'EUR', 'JPY', 'CAD', 'AUD')
					$currencyCodeNo = $_POST["hdnCurrencyCodeNo"];	// or other currency ('GBP', 'EUR', 'JPY', 'CAD', 'AUD')
	
					$orderPrefix = 'ASWOW-CD-';
					$ProductPostFix = '';
					
					if($_REQUEST['ddProductType'] == 2 ) {
						$orderPrefix = 'ASWOW-RS-';
						$ProductPostFix = '-RS';
					} else if($_REQUEST['ddProductType'] == 3 ) {
						$orderPrefix = 'ASWOW-CD-';
						$ProductPostFix = '';
					}
					
					$Items = array();
					$Items["ProductId"] = $_POST['hdnProductId'];
					$Items["ProductName"] = $_POST["hdnProductName"];
					$Items["ProductDesc"] = $_POST["hdnProductName"];
					$Items["ReferenceText"] = $_POST["hdnProductName"] . " [ $orderPrefix-" .$_POST["order_id"] . " ]";
					$Items["Price"] = $_POST["hdnPrice"];
					$Items["Currency"] = $currencyCode;
					$Items["ProductType"] = $ProductPostFix;
					$Items["invoice_number"] = $orderPrefix .$_POST["order_id"];
					$Items["custom"] = $_POST["txtUserEmail"];
					$Items["OrderID"] = $_POST["order_id"];
					$Items["currencyCodeNo"] = $currencyCodeNo;
					
					require_once ('../helper/nets/nets.software.post.php');
				}
			}
		}
	}
	else if ($_REQUEST['hdnTask'] == "-BuyReport-") {
		$start_date = '';
		if(isset($_REQUEST['hdnProductId']) && $_REQUEST['hdnProductId'] == 21) {
			$start_date = $_REQUEST['ddYear']+$_REQUEST['for_year'].'-'.$_REQUEST['ddMonth'].'-'.$_REQUEST['ddDay'];
		}
		else {
			$start_date = date('Y-m-d');
		}

		$_REQUEST['start_date'] = $start_date;

		$user_id = 0;
		$order_id = 0;
		$shipping_id = 0;
		$birthdataid1 = 0;
		$birthdataid2 = 0;
		$lovers_report_data_id1 = 0;
		$lovers_report_data_id2 = 0;
		$objUserDTO = new UserDTO();
		$userObj = new User();

		/* IF USER CHECK FOR REGISTER THEN CREATE AND SAVE USER AND PROVIDE 5% DISCOUNT  */
		if(isset($_REQUEST['chkRegister'])) {
			$ProductItemId = $_REQUEST['hdnProductItemId'];
			$CurrencyId = $_REQUEST['hdnCurrencyId'];
			SetNewUserDiscount($ProductItemId, $CurrencyId, $_REQUEST);
				
			/* SAVE USER  */
			$returnValue = SaveUser($_REQUEST, 1);
			if($returnValue > 0) {
				$user_id = $returnValue;

				/* SAVE USER PROFILE  */
				$returnValue = SaveUserProfile($_REQUEST,$user_id);
				$returnValue = SaveUserBirthDetail($_REQUEST,$user_id);
			}
			else {
				echo 'Error occured while saving user data';
			}
		}
		else if(isset($_REQUEST['user_id']) && !empty($_REQUEST['user_id'])) {
			$user_id = $_REQUEST['user_id'];
			// UPDATE USER PROFILE
			//$returnValue = UpdateUserProfile($_REQUEST,$user_id);
		}

		// IF ORDER ID IS NULL OR EMPTY THEN CREATE ORDER
		if(isset($_REQUEST['order_id']) && !empty($_REQUEST['order_id'])) {
			// UPDATE ORDER DETAIL
			$order_id = $_REQUEST['order_id'];
			$returnValue = UpdateOrderData($_REQUEST,$user_id,$order_id);
			if(!$returnValue) {
				echo 'Error occured while updating order data';
			}
		}
		else {
			// SAVE ORDER DETAIL
			$chk_for_register = 0;
			if(isset($_REQUEST['chkRegister'])) {
				$chk_for_register = 1;
			}
			$returnValue = SaveOrder($_REQUEST,$user_id,$chk_for_register);
			if($returnValue > 0) {
				$order_id = $returnValue;
			}
			else {
				echo 'Error occured while saving order data';
			}
		}

		// SAVE / UPDATE USER SHIPPING ADDRESS
		if(isset($_REQUEST['rdoDeliveryMethod']) && $_REQUEST['rdoDeliveryMethod'] == 2) {
			if(isset($_REQUEST['shipping_id']) && !empty($_REQUEST['shipping_id'])) {
				// SAVE ORDER SHIPPING DETAIL
				$shipping_id = $_REQUEST['shipping_id'];
				$returnValue =UpdateOrderShipping($_REQUEST,$user_id,$order_id,$shipping_id);
				if(!$returnValue) {
					echo 'Error occured while updating order data';
				}
			}
			else {
				// UPDATE ORDER SHIPPING DETAIL
				$returnValue = SaveOrderShipping($_REQUEST, $user_id, $order_id);
				if($returnValue > 0) {
					$shipping_id = $returnValue;
				}
				else {
					echo 'Error occured while saving shipping data';
				}
			}
		}

		/** Below code is added for upsale setup */
		if(isset($_REQUEST['upordid'])) {
			if(is_numeric($_REQUEST['upordid'])) {
				$upordid = intval($_REQUEST['upordid']);
				SaveUpsaleOrder($order_id, $upordid);
			}
		}
		/** Below code is added for upsale setup */

		if(isset($_REQUEST['hdnIsLoversReport']) && $_REQUEST['hdnIsLoversReport'] == 1) {
			// SAVE / UPDATE USER BIRTH DATA
			if(isset($_REQUEST['birthdataid1']) && !empty($_REQUEST['birthdataid1'])) {
				$birthdataid1 = $_REQUEST['birthdataid1'];
				$returnValue = UpdateBirthData($_REQUEST,$order_id,$birthdataid1,1);
				if(!$returnValue) {
					echo 'Error occured while updating order data';
				}
			}
			else {
				$returnValue = SaveBirthData($_REQUEST,$order_id,1);
				if($returnValue > 0) {
					$birthdataid1 = $returnValue;
				}
				else {
					echo 'Error occured while saving birth data';
				}
			}

			// SAVE / UPDATE USER LOVERS DATA FOR PERSON 1
			if(isset($_REQUEST['lovers_report_data_id1']) && !empty($_REQUEST['lovers_report_data_id1'])) {
				$returnValue = UpdateLoversData($_REQUEST,$order_id,$birthdataid1,1);
				if(!$returnValue) {
					echo 'Error occured while updating lovers data';
				}
			}
			else {
				$returnValue = SaveLoversData($_REQUEST,$order_id,$birthdataid1,1);
				if($returnValue > 0) {
					$lovers_report_data_id1 = $returnValue;
				}
				else {
					echo 'Error occured while saving person 1 data';
				}
			}

			if(isset($_REQUEST['birthdataid2']) && !empty($_REQUEST['birthdataid2'])) {
				$birthdataid2 = $_REQUEST['birthdataid2'];
				$returnValue = UpdateBirthData($_REQUEST,$order_id,$birthdataid2,2);
				if(!$returnValue) {
					echo 'Error occured while updating order data';
				}
			}
			else {
				$returnValue = SaveBirthData($_REQUEST,$order_id,2);
				if($returnValue > 0) {
					$birthdataid2 = $returnValue;
				}
				else {
					echo 'Error occured while saving birth data';
				}
			}

			/*  SAVE / UPDATE USER LOVERS DATA FOR PERSON 2  */

			if(isset($_REQUEST['lovers_report_data_id2']) && !empty($_REQUEST['lovers_report_data_id2'])) {
				$returnValue = UpdateLoversData($_REQUEST,$order_id,$birthdataid2,2);
				if(!$returnValue) {
					echo 'Error occured while updating lovers data';
				}
			}
			else {
				$returnValue = SaveLoversData($_REQUEST,$order_id,$birthdataid2,2);
				if($returnValue > 0) {
					$lovers_report_data_id2 = $returnValue;
				}
				else {
					echo 'Error occured while saving person 2 data';
				}
			}

			$extra = '../confirm-lovers-report-order.php?order_id='.$order_id;
		}
		else {
			// SAVE / UPDATE USER BIRTH DATA
			if(isset($_REQUEST['birthdataid']) && !empty($_REQUEST['birthdataid'])) {
				$birthdataid1 = $_REQUEST['birthdataid'];
				$returnValue = UpdateBirthData($_REQUEST,$order_id,$birthdataid1,'');
				if(!$returnValue) {
					echo 'Error occured while updating order data';
				}
			}
			else {
				$returnValue = SaveBirthData($_REQUEST,$order_id,'');
				if($returnValue > 0) {
					$birthdataid1 = $returnValue;
				}
				else {
					echo 'Error occured while saving birth data';
				}
			}

			$extra = '../confirm-report-order.php?order_id='.$order_id;
		}

		if(isset($_REQUEST['button']) && $_REQUEST['button'] == "Submit") {
			
		   	$extra = '../confirm-report-order.php?order_id='.$order_id;
           	$request_url = $extra;
           	echo "<html>".
                   	'<body onLoad=document.forms["user_form"].submit();>'.
                		"<form method='post' name='user_form' action='".$request_url."'>";
           echo				"<input type='hidden' name='order_id' value='".$order_id."' />";
           echo				"<input type='hidden' name='product_item_id' value='".$_REQUEST['hdnId']."' />";
           echo				"<input type='hidden' name='product_id' value='".$_REQUEST['hdnProductId']."' />";
           echo				"<input type='hidden' name='payment_method' value='".$_REQUEST['payment_method']."' />";
           echo				"<input type='hidden' name='CardNumber' value='".$_REQUEST['CardNumber']."' />";
           echo				"<input type='hidden' name='CardCVC' value='".$_REQUEST['CardCVC']."' />";
           echo				"<input type='hidden' name='ExpireMonth' value='".$_REQUEST['ExpireMonth']."' />";
           echo				"<input type='hidden' name='ExpireYear' value='".$_REQUEST['ExpireYear']."' />";
           echo				"<input type='hidden' name='hdnCurrencyCodeNo' value='".$_REQUEST['hdnCurrencyCodeNo']."' />";
           echo 		"</form>";
           echo 	"</body>";
           echo "</html>";
       }
       else if(isset($_REQUEST['button']) && $_REQUEST['button'] == "Place Order") {

			if( isset($_REQUEST['ddProductType']) && $_REQUEST['ddProductType'] == 4) {
				//SendOrderEmail($_REQUEST);
				$to = $_REQUEST['txtUserEmail'];
				// TODO :: for now we send mail derectly, letter on it will fired from generic mail class
				$subject = "Free Report placed successfully";
				$body =  "Thank you for order free report:".$_REQUEST['hdnProductName']."\n";
				$body .=  "To view your report, please navigate to your astropage of astrowow.com and see Purchased report section\n";
				mail($to, $subject, $body);


				$extra = '../thankyou.php';
				$request_url = $extra;
				$message = '';
				echo
				"<html>".
				"<body onLoad=\"document.forms['user_form'].submit();\">".
				"<form method=\"post\" name=\"user_form\" action=\"".$request_url."\">";

				echo "<input type=\"hidden\" name=\"order_id\" value=\"".$order_id."\"/>";
				echo "<input type=\"hidden\" name=\"product_item_id\" value=\"".$_REQUEST['hdnId']."\"/>";
				echo "<input type=\"hidden\" name=\"product_id\" value=\"".$_REQUEST['hdnProductId']."\"/>";
				echo "<input type=\"hidden\" name=\"message\" value=\"".$message."\"/>";


				echo 		  "</form>".			  "</body>".		  "</html>";
			}
			else {								
				//SendOrderEmail($_REQUEST);				
				if(isset($_POST["payment_method"]) && $_POST["payment_method"] == "creditcard") {
					$PostDict = array();

					/*** CREDIT CARD ****/
					$PostDict["CardNumber"] = trim($_POST["CardNumber"]);
					$PostDict["CardCVC"] = trim($_POST["CardCVC"]);
					$PostDict["ExpireMonth"] = trim($_POST["ExpireMonth"]);
					$PostDict["ExpireYear"] = trim($_POST["ExpireYear"]);
					$PostDict["PaymentMethod"] = 2;
					/*** CREDIT CARD ****/

					$currencyCode = $_POST["hdnCurrencyCode"];			// or other currency ('GBP', 'EUR', 'JPY', 'CAD', 'AUD')
					$amount = $_POST["hdnPrice"];						// or other currency ('GBP', 'EUR', 'JPY', 'CAD', 'AUD')
					$currencyCodeNo = $_POST["hdnCurrencyCodeNo"];		// or other currency ('GBP', 'EUR', 'JPY', 'CAD', 'AUD')
					
					
					$Items = array();
					$Items["ProductId"] = $_POST['hdnProductId'];
					$Items["ProductName"] = $_POST["hdnProductName"];
					$Items["ProductDesc"] = $_POST["hdnProductName"];
					$Items["ReferenceText"] = $_POST["hdnProductName"] . " [ ASWOW-" .$_POST["order_id"] . " ]";
					$Items["Price"] = $_POST["hdnPrice"];					
					$Items["Currency"] = $currencyCode;
					$Items["invoice_number"] = "ASWOW-" .$_POST["order_id"];
					$Items["custom"] = $_POST["txtUserEmail"];
					$Items["OrderID"] = $_POST["order_id"];
					$Items["currencyCodeNo"] = $currencyCodeNo;
					
					require_once ('../helper/nets/nets.report.post.php');
				} else {
					$ProductPostFix = '';
					$InvoicePreFix = 'AWRP-'.$order_id.'';
					if(isset($_REQUEST['rdoDeliveryMethod'])) {
						if($_REQUEST['rdoDeliveryMethod'] == 2) {
							$ProductPostFix = " - (Printed)";
							$InvoicePreFix = $InvoicePreFix.'-PRN';
						}
					}
						
					$queryString = "action=process";
					$queryString .= '&orderid='.$order_id;
					$queryString .= '&currency_code='.$_REQUEST['hdnCurrencyCode'];
					$queryString .= '&product_name='.urlencode(utf8_decode($_REQUEST['hdnProductName'])). $ProductPostFix;
					$queryString .= '&product_price='. number_format(floatval($_REQUEST['hdnPrice']), 2);
					$queryString .= '&product_discount='. (isset($_REQUEST['discount']) ? number_format(floatval($_REQUEST['discount']),2) : 0);
					$queryString .= '&product_shipping_charge='.number_format(floatval($_REQUEST['hdnShipping']),2);
					$queryString .= '&order_id='.$order_id;
					$queryString .= '&languageid='.isset($_REQUEST['Languageid']) ? $_REQUEST['Languageid'] : "en";
					$queryString .= '&prefix=AWRP-'.$_REQUEST['hdnProductName'].'';
					$queryString .= '&product_item_id='.$InvoicePreFix;
					$queryString .= '&user_id='.$_REQUEST['txtUserEmail'];
	
					if(isset($_REQUEST['hdnProductId']) && !empty($_REQUEST['hdnProductId'])) {
						$extra = '../helper/paypal/paypal-for-report-id-'.$_REQUEST['hdnProductId'].'.php?'.$queryString;
					}
					else {
						$extra = '../helper/paypal/paypal.php?'.$queryString;
					}
					
					//$m_url_success = ROOTPATH.'helper/paypal.php';
					//$m_url_failure = $s['HTTP_REFERER'];
	
					$host  = $_SERVER['HTTP_HOST'];
					$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
					header("Location: http://$host$uri/$extra");
					exit;
				}
			}
		}
	}
	else if ($_REQUEST['hdnTask'] == "BuySubscription") {

		$user_id = 0;
		$order_id = 0;
		$shipping_id = 0;
		$objUserDTO = new UserDTO();

		$_REQUEST['hdnId'] = $_REQUEST['product_id'][$_REQUEST['hdnSubscriptionId']];
		$_REQUEST['ddProductType'] = 6;
		$_REQUEST['ddLanguage'] = 'en';
		$_REQUEST['txtUserEmail'] = $_COOKIE['UserEmail'];
		$user_id = $_REQUEST['user_id'];

		// SAVE ORDER DETAIL
		$chk_register = 0;
		if(isset($_REQUEST['chkRegister'])) {
			$chk_register = 1;
		}
		$returnValue = SaveOrder($_REQUEST,$user_id,$chk_register);
		if($returnValue > 0) {
			$order_id = $returnValue;

			$returnValue = SaveSubscribedUser($_REQUEST['hdnSubscriptionId'],$user_id,$_REQUEST['duration'][$_REQUEST['hdnSubscriptionId']]);


			if($returnValue > 0) {
				$price = $_REQUEST['hdnPrice'];
				$discount =  number_format(floatval($_REQUEST['hdnDiscount']),2);
				$discount =  number_format(floatval($discount * $price / 100),2);
				$subtotal =  number_format(floatval($price - $discount),2);

				$sender = "service@astrowow.com";
				$rec = array(
						'to'	=> $_REQUEST['txtUserEmail'],
				);

				$data = array(
						'subject'			=> "Order Confirmation Mail",
						'mailtext'			=> "<h1>Order Confirm.</h1>",
						'type'				=> "html",
						'attachment'		=> array( 'testfile.html' ),
						'order_date'		=> date("Y-m-d"),
						'product_name'		=> $_REQUEST['product_name'][$_REQUEST['hdnSubscriptionId']],
						'product_price'		=> $price,
						'discount'			=> $discount,
						'subtotal'			=> $subtotal

				);

				if ( genericMail::SendSubscriptionBuyEmail( $sender, $rec, $data ) ) {
					print "Mail was send successfull ... \n";
				} else {
					print "Hmm .. there could be a Problem ... \n";
				}

				//exit;

				$queryString = "action=process";
				$queryString .= '&orderid='.$order_id;
				$queryString .= '&product_item_id='.$_REQUEST['product_id'][$_REQUEST['hdnSubscriptionId']];
				$queryString .= '&currency_code='.$_REQUEST['hdnCurrencyCode'];
				$queryString .= '&product_name='.urlencode($_REQUEST['product_name'][$_REQUEST['hdnSubscriptionId']]);
				$queryString .= '&product_price='. number_format(floatval($_REQUEST['hdnPrice']),2);
				//$queryString .= '&product_price='.$_REQUEST['hdnPrice'];
				$queryString .= '&product_discount='. number_format(floatval($_REQUEST['hdnDiscount']),2);
				$queryString .= '&product_shipping_charge=0.00';

				$queryString .= '&prefix=AWMSUB-'.$order_id;
				$queryString .= '&product_item_id=AWMSUB-'.$order_id;
				$queryString .= '&user_id='.$returnValue;
				$extra = '../helper/paypal/add-another/paypal-for-subscription.php?'.$queryString;
				//$extra = '../helper/paypal/paypal-for-subscription.php?'.$queryString;
				//$m_url_success = ROOTPATH.'helper/paypal.php';
				//$m_url_failure = $s['HTTP_REFERER'];

				$host  = $_SERVER['HTTP_HOST'];
				$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
				header("Location: http://$host$uri/$extra");
				exit;
			}
			else {
				//echo 'Error occured while saving subscription data';
				$queryString = "Error occured while saving subscription data";
				$extra = '../subscribe.php?';

				echo
				"<html>".
				"<body onLoad=\"document.forms['user_form'].submit();\">".
				"<form method=\"post\" name=\"user_form\" action=\"".$extra."\">";
				echo "<input type=\"hidden\" name=\"message\" value=\"".$queryString."\"/>";
				echo 		  "</form>".			  "</body>".		  "</html>";
			}
		}
		else {
			//echo 'Error occured while saving order data';
			$queryString = "Error occured while saving order data";
			$extra = '../subscribe.php?';
			echo
			"<html>".
			"<body onLoad=\"document.forms['user_form'].submit();\">".
			"<form method=\"post\" name=\"user_form\" action=\"".$extra."\">";
			echo "<input type=\"hidden\" name=\"message\" value=\"".$queryString."\"/>";
			echo 		  "</form>".			  "</body>".		  "</html>";
		}


	}
	else if ($_REQUEST['hdnTask'] == "BuyComboProducts") {
		
		$user_id = 0;
		$order_id = 0;
		$shipping_id = 0;
		$objUserDTO = new UserDTO();

		if(!isset($_REQUEST['order_id'])) {
			$_REQUEST['hdnId'] = $_REQUEST['product_id'][$_REQUEST['hdnProductId']];
			$_REQUEST['hdnSoftwareOnCD'] = $_REQUEST['product_id'][$_REQUEST['hdnProductId']];
			$_REQUEST['hdnSoftwareRCode'] = $_REQUEST['product_id'][$_REQUEST['hdnProductId']];
			
			$_REQUEST['hdnProductName'] = $_REQUEST['product_name'][$_REQUEST['hdnProductId']];
			$_REQUEST['hdnProductId'] = $_REQUEST['product_id'][$_REQUEST['hdnProductId']];
		}
		
		//$_REQUEST['ddLanguage'] = 'en';
				
		if(isset($_REQUEST['order_id']) && !empty($_REQUEST['order_id'])) {

			//UPDATE ORDER DETAIL
			$order_id = $_REQUEST['order_id'];
			$returnValue = UpdateOrderData($_REQUEST, $user_id,$order_id);
			if(!$returnValue) {
				//echo 'Error occured while updating order data';
				$queryString = "Error occured while updating order data";
				$extra = '../subscribe.php?';
				echo "<html>";
				echo "<body onLoad=\"document.forms['user_form'].submit();\">";
				echo "<form method=\"post\" name=\"user_form\" action=\"".$extra."\">";
				echo 	"<input type=\"hidden\" name=\"message\" value=\"".$queryString."\"/>";
				echo "</form>";
				echo "</body>";		  
				echo "</html>";
			}
		}
		else {
			// SAVE ORDER DETAIL
			$chk_register = 0;

			$returnValue = SaveOrder($_REQUEST,$user_id,$chk_register);
			if($returnValue > 0) {
				$order_id = $returnValue;
			}
			else {
				//echo 'Error occured while saving order data';
				$queryString = "Error occured while saving order data";
				$extra = '../subscribe.php?';
				echo "<html>";
				echo "<body onLoad=\"document.forms['user_form'].submit();\">";
				echo "<form method=\"post\" name=\"user_form\" action=\"".$extra."\">";
				echo "<input type=\"hidden\" name=\"message\" value=\"".$queryString."\"/>";
				echo "</form>";
				echo "</body>";
				echo "</html>";

			}
		}

		// insert data into order_shipping table as per product type selected by user
		if(isset($_REQUEST['ddProductType']) && $_REQUEST['ddProductType'] == 3 || $_REQUEST['ddProductType'] == 2) {
			if(isset($_REQUEST['shipping_id']) && !empty($_REQUEST['shipping_id'])) {
				// SAVE ORDER SHIPPING DETAIL
				$shipping_id = $_REQUEST['shipping_id'];
				$returnValue = UpdateOrderShipping($_REQUEST,$user_id,$order_id,$shipping_id);
				if(!$returnValue) {
					//echo 'Error occured while updating order data';
				}
			}
			else {
				// UPDATE ORDER SHIPPING DETAIL
				$returnValue = SaveOrderShipping($_REQUEST,$user_id,$order_id);
				if($returnValue > 0) {
					$shipping_id = $returnValue;
				}
				else {
					//echo 'Error occured while saving shipping data';
					$queryString = "Error occured while saving shipping data";
					$extra = '../subscribe.php?';
					echo "<html>";
					echo "<body onLoad=\"document.forms['user_form'].submit();\">";
					echo "<form method=\"post\" name=\"user_form\" action=\"".$extra."\">";
					echo "<input type=\"hidden\" name=\"message\" value=\"".$queryString."\"/>";
					echo "</form>";
					echo "</body>";
					echo "</html>";
				}
			}
		}
		
		if(isset($_REQUEST['button']) && strtolower($_REQUEST['button']) == "submit") {		
			$request_url = '../confirm-combo-product.php?order_id='.$order_id;
			
			echo "<html>".
					'<body onLoad=document.forms["user_form"].submit();>'.
					"<form method='post' name='user_form' action='".$request_url."'>";
			echo				"<input type='hidden' name='order_id' value='".$order_id."' />";
			echo				"<input type='hidden' name='product_item_id' value='".$_REQUEST['hdnId']."' />";
			echo				"<input type='hidden' name='product_id' value='".$_REQUEST['hdnProductId']."' />";
			echo				"<input type='hidden' name='payment_method' value='".$_REQUEST['payment_method']."' />";
			echo				"<input type='hidden' name='CardNumber' value='".$_REQUEST['CardNumber']."' />";
			echo				"<input type='hidden' name='CardCVC' value='".$_REQUEST['CardCVC']."' />";
			echo				"<input type='hidden' name='ExpireMonth' value='".$_REQUEST['ExpireMonth']."' />";
			echo				"<input type='hidden' name='ExpireYear' value='".$_REQUEST['ExpireYear']."' />";
			echo				"<input type='hidden' name='hdnCurrencyCodeNo' value='".$_REQUEST['hdnCurrencyCodeNo']."' />";
			echo 		"</form>";
			echo 	"</body>";
			echo "</html>";
		
		} else if(isset($_REQUEST['button']) && strtolower($_REQUEST['button']) == "place order") {
			if($_POST["payment_method"] == "paypal") {
				$queryString = "action=process";
				$queryString .= '&orderid='.$order_id;
				$queryString .= '&product_item_id='.$_REQUEST['hdnProductId'];
				$queryString .= '&product_name='.urlencode($_REQUEST['hdnProductName']);
				$queryString .= '&product_price='. number_format(floatval(str_replace(',', '', $_REQUEST['hdnPrice'])), 2);
				$queryString .= '&product_discount='. number_format(floatval(str_replace(',', '', $_REQUEST['hdnDiscount'])),2);
				$queryString .= '&discount='. number_format(floatval(str_replace(',', '',$_REQUEST['hdnDiscount'])),2);
				$queryString .= '&currency_code='.$_REQUEST['hdnCurrencyCode'];
		
				if(isset($_REQUEST['hdnProductId']) && !empty($_REQUEST['hdnProductId'])) {
					if($_REQUEST['ddProductType'] == 3) {
						$queryString .= '&product_shipping_charge='.number_format(floatval(str_replace(',', '',$_REQUEST['hdnShipping'])),2);
						$extra = '../helper/paypal/paypal-combo-cd.php?'.$queryString;
					}
					else {
						$queryString .= '&product_shipping_charge=0.00';
						$extra = '../helper/paypal/paypal-combo-rs.php?'.$queryString;
					}
				}
				else {
					$extra = '../helper/paypal/paypal.php?'.$queryString;
				}
				$host  = $_SERVER['HTTP_HOST'];
				$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
				header("Location: http://$host$uri/$extra");
				exit;		
			} else {
				$PostDict = array();
			
				/*** CREDIT CARD ****/
				$PostDict["CardNumber"] = trim($_POST["CardNumber"]);
				$PostDict["CardCVC"] = trim($_POST["CardCVC"]);
				$PostDict["ExpireMonth"] = trim($_POST["ExpireMonth"]);
				$PostDict["ExpireYear"] = trim($_POST["ExpireYear"]);
				$PostDict["PaymentMethod"] = 2;
				/*** CREDIT CARD ****/
			
				$currencyCode = $_POST["hdnCurrencyCode"];					// or other currency ('GBP', 'EUR', 'JPY', 'CAD', 'AUD')
				$amount = $_POST["hdnPrice"];						// or other currency ('GBP', 'EUR', 'JPY', 'CAD', 'AUD')
				$currencyCodeNo = $_POST["hdnCurrencyCodeNo"];	// or other currency ('GBP', 'EUR', 'JPY', 'CAD', 'AUD')
			
				$orderPrefix = 'ASWOW-CMB-CD-';
				$ProductPostFix = '';
			
				if($_REQUEST['ddProductType'] == 2 ) {
					$orderPrefix = 'ASWOW-RS-';
					$ProductPostFix = '-CMB-RS';
				} else if($_REQUEST['ddProductType'] == 3 ) {
					$orderPrefix = 'ASWOW-CMB-CD-';
					$ProductPostFix = '';
				}
				
				$Items = array();
				$Items["ProductId"] = $_POST['hdnProductId'];
				$Items["ProductName"] = $_POST["hdnProductName"];
				$Items["ProductDesc"] = $_POST["hdnProductName"];
				$Items["ReferenceText"] = $_POST["hdnProductName"] . " [ $orderPrefix-" .$order_id. " ]";
				$Items["Price"] = $_POST["hdnPrice"];
				$Items["Currency"] = $currencyCode;
				$Items["ProductType"] = $ProductPostFix;
				$Items["ddProductType"] = $_REQUEST['ddProductType'];
				$Items["invoice_number"] = $orderPrefix .$order_id;
				$Items["custom"] = $_POST["txtUserEmail"];
				$Items["OrderID"] = $order_id;
				$Items["currencyCodeNo"] = $currencyCodeNo;
			
				require_once ('../helper/nets/nets.combo.software.post.php');
			}
		}
	}
	else if($_REQUEST['hdnTask'] == "UpdateOrderStatusFromIPN") {
		$order_id = 0;
		$user_id = 0;
		$order_status = 1;
		$transaction_id = '';
		$item_name = '';
		$discount = '';
		$item_number = '';
		$handling_amount = '';
		$payment_gross = '';
		$shipping = '';

		if(isset($_REQUEST['order_id'])) {
			$order_id = $_REQUEST['order_id'];
		}

		if(isset($_REQUEST['order_status'])) {
			$order_status = $_REQUEST['order_status'];
		}

		if(isset($_REQUEST['transaction_id'])) {
			$transaction_id = $_REQUEST['transaction_id'];
		}

		if(isset($_REQUEST['user_id'])) {
			$user_id = $_REQUEST['user_id'];
			$item_name = $_REQUEST['item_name'];
			$discount = $_REQUEST['discount'];
			$item_number = $_REQUEST['item_number'];
			$handling_amount = $_REQUEST['handling_amount'];
			$payment_gross = $_REQUEST['payment_gross'];
			$shipping = $_REQUEST['shipping'];
		}

		$objOrder = new Order();

		$returnValue = $objOrder->UpdateOrderStatusFromIPN($order_id, $order_status);

		$userObj = new User();
		/*$result = $userObj->GetUserDetailForMail($user_id);

		if(count($result)>0)
		{
		$user_email = $result[0]['UserName'];
		$first_name = $result[0]['FirstName'];
		$last_name = $result[0]['LastName'];
		}*/


		if(count($result)>0) {
			$data = array(
					"order_id"=>$order_id,
					"item_name"=>$item_name,
					"discount"=>$discount,
					"item_number"=>$item_number,
					"handling_amount"=>$handling_amount,
					"payment_gross"=>$payment_gross,
					"first_name"=>'',
					"shipping"=>$shipping,
					"user_email"=>$user_id,
					"last_name"=>''
			);
		}

		SendPaymentAcceptedEmail($data);
	}
	else if($_REQUEST['hdnTask'] == "OrderConsultationMe") {
		$user_id = 0;
		$order_id = 0;
		$shipping_id = 0;
		$objUserDTO = new UserDTO();
			
		$_REQUEST['hdnId'] = $_REQUEST['hdnProductItemId'];
		$_REQUEST['ddProductType'] = 11;
		$_REQUEST['ddLanguage'] = 'English';
		$_REQUEST['txtUserEmail'] = '';
		$user_id = $_REQUEST['user_id'];
			
		// SAVE ORDER DETAIL
		$chk_register = 0;
		//    	print "<pre>".print_r($_REQUEST)."</pre>";
		$returnValue = SaveOrder($_REQUEST,$user_id,$chk_register);
		if($returnValue > 0) {
			$order_id = $returnValue;

			$price = $_REQUEST['hdnPrice'];
			$discount = $_REQUEST['hdnDiscount'];
			$discount =  number_format(floatval($discount * $price / 100),2);
			$subtotal =  number_format(floatval($price - $discount),2);

			$to_email_id = 'ard@astrowow.com';
			$sender = "service@astrowow.com";
			$rec = array( 'to'	=> $to_email_id );

			$queryString = "action=process";
			$queryString .= '&orderid='.$order_id;
			$queryString .= '&product_item_id='.$_REQUEST['hdnProductItemId'];
			$queryString .= '&currency_code='.$_REQUEST['hdnCurrencyCode'];
			$queryString .= '&product_name='.urlencode(trim($_REQUEST['product_name']));
			$queryString .= '&product_price='. number_format(floatval($_REQUEST['hdnPrice']),2);
			$queryString .= '&product_discount='. number_format(floatval($_REQUEST['hdnDiscount']),2);
			$queryString .= '&product_shipping_charge=0';
			$queryString .= '&prefix=CONSULT-';
			$queryString .= '&product_item_id=CONSULT-'.$order_id;
			$queryString .= '&user_id='.$user_id;
			$extra = '../helper/paypal/order-consultation/paypal.php?'.$queryString;
			$host  = $_SERVER['HTTP_HOST'];
			$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
			echo "<html>".
					"<body onLoad=\"document.forms['user_form'].submit();\">".
					"<form method=\"post\" name=\"user_form\" action=\"http://".$host.$uri.'/'.$extra."\">";
			echo 		  "</form>".			  "</body>".		  "</html>";
			//header("Location: http://$host/$extra");
			exit;
		}
	}
	else if($_REQUEST['hdnTask'] == "OrderConsultation") {
		$user_id = 0;
		$order_id = 0;
		$shipping_id = 0;
		$objUserDTO = new UserDTO();

		$_REQUEST['hdnId'] = $_REQUEST['hdnProductItemId'];
		$_REQUEST['ddProductType'] = 11;
		$_REQUEST['ddLanguage'] = 'English';
		$_REQUEST['txtUserEmail'] = '';
		$user_id = $_REQUEST['user_id'];

		// SAVE ORDER DETAIL
		$chk_register = 0;

		$returnValue = SaveOrder($_REQUEST,$user_id,$chk_register);
		if($returnValue > 0) {
			$order_id = $returnValue;

			$price = $_REQUEST['hdnPrice'];
			$discount = $_REQUEST['hdnDiscount'];
			$discount =  number_format(floatval($discount * $price / 100),2);
			$subtotal =  number_format(floatval($price - $discount),2);

			//$to_email_id = 'dhruv.sarvaiya@n-techcorporate.com';
			$to_email_id = 'ard@astrowow.com';

			$sender = "service@astrowow.com";
			$rec = array(
					'to'	=> $to_email_id,
			);

			$data = array(
					'subject'			=> "Order Consultation Place Mail",
					'mailtext'			=> "<h1>Order Cosulation Place by User.</h1>",
					'type'			=> "html",
					'attachment'		=> array( 'testfile.html' ),
					'order_date'		=> date("Y-m-d"),
					'product_name'		=> $_REQUEST['product_name'][$_REQUEST['hdnSubscriptionId']],
					'product_price'		=> $price,
					'discount'			=> $discount,
					'subtotal'			=> $subtotal
			);

			//            if ( genericMail::SendSubscriptionBuyEmail( $sender, $rec, $data ) ) {
			//                print "Mail was send successfull ... \n";
			//            } else {
			//                print "Hmm .. there could be a Problem ... \n";
			//            }

			//exit;

			$queryString = "action=process";
			$queryString .= '&orderid='.$order_id;
			$queryString .= '&product_item_id='.$_REQUEST['hdnProductItemId'];
			$queryString .= '&currency_code='.$_REQUEST['hdnCurrencyCode'];
			$queryString .= '&product_name='.urlencode($_REQUEST['product_name']);
			$queryString .= '&product_price='. number_format(floatval($_REQUEST['hdnPrice']),2);
			//$queryString .= '&product_price='.$_REQUEST['hdnPrice'];
			$queryString .= '&product_discount='. number_format(floatval($_REQUEST['hdnDiscount']),2);
			$queryString .= '&product_shipping_charge=0';

			$queryString .= '&prefix=CONSULT-';
			$queryString .= '&product_item_id=CONSULT-'.$order_id;
			$queryString .= '&user_id='.$user_id;
			$extra = '../helper/paypal/order-consultation/paypal.php?'.$queryString;

			//$m_url_success = ROOTPATH.'helper/paypal.php';
			//$m_url_failure = $s['HTTP_REFERER'];

			$host  = $_SERVER['HTTP_HOST'];
			$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
			//echo "http://$host$uri/$extra";
			//header("Location: http://$host$uri/$extra");
			exit;
		}
		else {
			echo 'Error occured while saving order data';
			die;
		}
	}
	else if ($_REQUEST['hdnTask'] == "RecurringBuySubscription") {

		$user_id = 0;
		$order_id = 0;
		$shipping_id = 0;
		$objUserDTO = new UserDTO();

		$_REQUEST['hdnId'] = $_REQUEST['product_id'][$_REQUEST['hdnSubscriptionId']];
		$_REQUEST['ddProductType'] = 6;
		$_REQUEST['ddLanguage'] = 'en';
		$_REQUEST['txtUserEmail'] = $_COOKIE['UserEmail'];
		$user_id = $_REQUEST['user_id'];
		$SubUserId = $_REQUEST['drpSubUserId'];

		// SAVE ORDER DETAIL
		$chk_register = 0;
		if(isset($_REQUEST['chkRegister'])) {
			$chk_register = 1;
		}
		$returnValue = SaveOrder($_REQUEST,$user_id,$chk_register);
		if($returnValue > 0) {
			$order_id = $returnValue;

			//$returnValue = SaveSubscribedUser($_REQUEST['hdnSubscriptionId'],$user_id,$_REQUEST['duration'][$_REQUEST['hdnSubscriptionId']]);
			$returnValue = SaveSubscribedUserwithOrderID($_REQUEST['hdnSubscriptionId'], $SubUserId, $_REQUEST['duration'][$_REQUEST['hdnSubscriptionId']], $order_id);

			if($returnValue > 0) {
				$price = $_REQUEST['hdnPrice'];
				$discount =  number_format(floatval($_REQUEST['hdnDiscount']),2);
				$discount =  number_format(floatval($discount * $price / 100),2);
				$subtotal =  number_format(floatval($price - $discount),2);

				$sender = "service@astrowow.com";
				$rec = array(
						'to'	=> $_REQUEST['txtUserEmail'],
				);

				$data = array(
						'subject'			=> "Order Confirmation Mail",
						'mailtext'			=> "<h1>Order Confirm.</h1>",
						'type'				=> "html",
						'attachment'		=> array( 'testfile.html' ),
						'order_date'		=> date("Y-m-d"),
						'product_name'		=> $_REQUEST['product_name'][$_REQUEST['hdnSubscriptionId']],
						'product_price'		=> $price,
						'discount'			=> $discount,
						'subtotal'			=> $subtotal
				);

				//     			if ( genericMail::SendSubscriptionBuyEmail( $sender, $rec, $data ) ) {
				//     				print "Mail was send successfull ... \n";
				//     			} else {
				//     				print "Hmm .. there could be a Problem ... \n";
				//     			}

				$queryString = "action=process";
				$queryString .= '&orderid='.$order_id;
				$queryString .= '&product_item_id='.$_REQUEST['product_id'][$_REQUEST['hdnSubscriptionId']];
				$queryString .= '&currency_code='.$_REQUEST['hdnCurrencyCode'];
				$queryString .= '&product_name='.urlencode($_REQUEST['product_name'][$_REQUEST['hdnSubscriptionId']]);
				$queryString .= '&product_price='. number_format(floatval($_REQUEST['hdnPrice']),2);
				$queryString .= '&product_discount='. number_format(floatval($_REQUEST['hdnDiscount']),2);
				$queryString .= '&product_shipping_charge=0.00';

				$queryString .= '&prefix=AW_REC_MSUB-';
				$queryString .= '&product_item_id=AW_REC_MSUB-'.$order_id;
				$queryString .= '&user_id='.$returnValue;
				$extra = '../helper/paypal/recurring-payment/paypal-for-subscription.php?'.$queryString;

				$host  = $_SERVER['HTTP_HOST'];
				$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
				header("Location: http://$host$uri/$extra");
				exit;
			}
			else {
				//echo 'Error occured while saving subscription data';
				$queryString = "Error occured while saving subscription data";
				$extra = '../rec-subscribe.php?';

				echo
				"<html>".
				"<body onLoad=\"document.forms['user_form'].submit();\">".
				"<form method=\"post\" name=\"user_form\" action=\"".$extra."\">";
				echo "<input type=\"hidden\" name=\"message\" value=\"".$queryString."\"/>";
				echo 		  "</form>".			  "</body>".		  "</html>";
			}
		}
		else {
			//echo 'Error occured while saving order data';
			$queryString = "Error occured while saving order data";
			$extra = '../rec-subscribe.php?';
			echo
			"<html>".
			"<body onLoad=\"document.forms['user_form'].submit();\">".
			"<form method=\"post\" name=\"user_form\" action=\"".$extra."\">";
			echo 	"<input type=\"hidden\" name=\"message\" value=\"".$queryString."\"/>";
			echo "</form>".
					"</body>".
					"</html>";
		}


	}
}
else if(isset($_REQUEST['task'])) {
	if($_REQUEST['task'] == "GetOrderCountForUser") {
		$objOrder = new Order();
		$result = $objOrder->GetOrderCountForUser($_REQUEST['user_id']);
		echo json_encode($result);
	}
	else if($_REQUEST['task'] == "GetOrderListForUser") {
		$objOrder = new Order();
		$result = $objOrder->GetOrderListForUser($_REQUEST['user_id'],$_REQUEST['items_per_page'],$_REQUEST['start']);
		echo json_encode($result);
	}
	else if($_REQUEST['task'] == "GetOrderDetailForUser") {
		$objOrder = new Order();
		$result = $objOrder->GetOrderDetailForUser($_REQUEST['user_id'],$_REQUEST['order_id']);
		echo json_encode($result);
	}
	else if($_REQUEST['task'] == "GetPurchasedReportCountByUserId") {
		$objOrder = new Order();
		$result = $objOrder->GetPurchasedReportCountByUserId($_REQUEST['user_id'],$_REQUEST['language_id']);
		echo json_encode($result);
	}
	else if($_REQUEST['task'] == "GetPurchasedReportByUserId") {
		$objOrder = new Order();
		$result = $objOrder->GetPurchasedReportByUserId($_REQUEST['user_id'],$_REQUEST['language_id'],$_REQUEST['items_per_page'],$_REQUEST['start']);
		echo json_encode($result);
	}
	//ADDED on 24-Apr-2013 Signup form with recurring subscription
	else if($_REQUEST['task'] == "RecSubscriptionWithSignup") {
		try {
			$userDTO = new userDTO();
			$userDTO->user_name = $_REQUEST['txtUserEmail'];
			$userDTO->password = md5($_REQUEST['txtPassword']);
			$userDTO->language = $_REQUEST['drpLanguage'];
			$userDTO->user_group = Enum::$userGroupList['Registered'];
			$userDTO->status = 1;
			$userDTO->portal_id = 2;

			$user_id = $userObj->SaveUser($userDTO);
			$_REQUEST['user_id'] = $user_id;

			if($user_id > 0) {
				$rec = array( 'to'	=> $userDTO->user_name );

				$data = array(
						'subject'			=> "Welcome to Astrowow.com‏",
						'mailtext'			=> "<h1>Hello,</h1>",
						'type'				=> "html",
						'language_id'		=> $_REQUEST['drpLanguage'],
						'password'			=> trim($_REQUEST['txtPassword']),
						'username'			=> trim($_REQUEST['txtUserEmail']),
						'name'				=> trim($_REQUEST['txtFirstName'])." ".trim($_REQUEST['txtLastName']),
						'sitelink'			=> BASEURL
				);

				SendUserEmail($data,$rec);
			}
			$optin = '';
			if(isset($_REQUEST['chkDailySunsign']) && !empty($_REQUEST['chkDailySunsign'])) {
				$optin .= $_REQUEST['chkDailySunsign'].',';
			}
			if(isset($_REQUEST['chkWeeklySunsign']) && !empty($_REQUEST['chkWeeklySunsign'])) {
				$optin .= $_REQUEST['chkWeeklySunsign'].',';
			}
			if(isset($_REQUEST['chkDailyPersonalHoroscope']) && !empty($_REQUEST['chkDailyPersonalHoroscope'])) {
				$optin .= $_REQUEST['chkDailyPersonalHoroscope'].',';
			}
			if(isset($_REQUEST['chkAstrologyArticles']) && !empty($_REQUEST['chkAstrologyArticles'])) {
				$optin .= $_REQUEST['chkAstrologyArticles'].',';
			}
			if(isset($_REQUEST['chkSpecialOffers']) && !empty($_REQUEST['chkSpecialOffers'])) {
				$optin .= $_REQUEST['chkSpecialOffers'];
			}

			$returnValue = $userObj->SaveUserOptin($user_id, $optin);

			$objUserProfileDTO = new userProfileDTO();
			$objUserProfileDTO->UserId = $user_id;
			$objUserProfileDTO->FirstName =$_REQUEST['txtFirstName'];
			$objUserProfileDTO->LastName =$_REQUEST['txtLastName'];
			$objUserProfileDTO->Gender = $_REQUEST['rdoGender'];
			$objUserProfileDTO->city = $_REQUEST['txtBirthCity'];
			$objUserProfileDTO->state = $_REQUEST['hdnState'];
			$objUserProfileDTO->country = $_REQUEST['ddBirthCountry'];

			$returnValue = $userObj->SaveUserProfile($objUserProfileDTO);

			$objUserbIRTHDTO = new userBirthDetailDTO();
			$objUserbIRTHDTO->UserId = $user_id;
			$objUserbIRTHDTO->Day = $_REQUEST['ddDay'];
			$objUserbIRTHDTO->Month = $_REQUEST['ddMonth'];
			$objUserbIRTHDTO->Year = $_REQUEST['ddYear'];
			$objUserbIRTHDTO->Hours = $_REQUEST['birthhour'];
			$objUserbIRTHDTO->Minutes = $_REQUEST['birthminute'];

			if($_REQUEST['birthhour'] == "-1") {
				$objUserbIRTHDTO->unTimed = 1;
			} else {
				$objUserbIRTHDTO->unTimed = 0;
			}

			$objUserbIRTHDTO->sunsign = CalculateSunsignFromDate($_REQUEST['ddMonth'],$_REQUEST['ddDay']);
			$objUserbIRTHDTO->country = $_REQUEST['ddBirthCountry'];
			$objUserbIRTHDTO->state = $_REQUEST['hdnState'];
			$objUserbIRTHDTO->country_name = $_REQUEST['hdncurrent_country_name'];
			$objUserbIRTHDTO->city = $_REQUEST['txtBirthCity'];

			$objUserbIRTHDTO->longitude = $_REQUEST['hdnLongitude'];
			$objUserbIRTHDTO->latitude = $_REQUEST['hdnLatitude'];
			$returnValue = $userObj->SaveUserBirthDetail($objUserbIRTHDTO);

			if($returnValue > 0) {
				$host  = $_SERVER['HTTP_HOST'];
				$uri   = DIR_FRONT;
					
				setcookie("UserId", $user_id, time()+(60*60*24*30),'/',$host);  					/* expire in 1 month */
				setcookie("UserGroupId", $userDTO->user_group, time()+(60*60*24*30),'/',$host);  	/* expire in 1 month */
				setcookie("UserEmail", $userDTO->user_name, time()+(60*60*24*30),'/',$host);  		/* expire in 1 month */

				$order_id = 0;
				$shipping_id = 0;
				$objUserDTO = new UserDTO();

				$_REQUEST['hdnId'] = $_REQUEST['product_id'][$_REQUEST['hdnSubscriptionId']];
				$_REQUEST['ddProductType'] = 6;
				$_REQUEST['ddLanguage'] = 'en';
				$_REQUEST['txtUserEmail'] = $_COOKIE['UserEmail'];
				$_REQUEST['hdnPrice'] = $_REQUEST['hdPriceV_'. $_REQUEST['RadioCurrency']];
					
				// SAVE ORDER DETAIL
				$chk_register = 0;
				if(isset($_REQUEST['chkRegister'])) {
					$chk_register = 1;
				}

				$returnValue = SaveOrder($_REQUEST, $user_id, $chk_register);
				if($returnValue > 0) {
					$order_id = $returnValue;
					$returnValue = SaveSubscribedUserwithOrderID($_REQUEST['hdnSubscriptionId'], $SubUserId, $_REQUEST['duration'][$_REQUEST['hdnSubscriptionId']], $order_id);

					if($returnValue > 0) {
						$price = $_REQUEST['hdnPrice'];

						$sender = "service@astrowow.com";
						$rec = array( 'to'	=> $_REQUEST['txtUserEmail'] );

						$data = array(
								'subject'			=> "Order Confirmation Mail",
								'mailtext'			=> "<h1>Order Confirm.</h1>",
								'type'				=> "html",
								'attachment'		=> array( 'testfile.html' ),
								'order_date'		=> date("Y-m-d"),
								'product_name'		=> $_REQUEST['product_name'][$_REQUEST['hdnSubscriptionId']],
								'product_price'		=> $price,
						);

						if ( genericMail::SendSubscriptionBuyEmail( $sender, $rec, $data ) ) {
							print "Mail was send successfull ... \n";
						} else {
							print "Hmm .. there could be a Problem ... \n";
						}

						$queryString = "action=process";
						$queryString .= '&orderid='.$order_id;
						$queryString .= '&product_item_id='.$_REQUEST['product_id'][$_REQUEST['hdnSubscriptionId']];
						$queryString .= '&currency_code='.$_REQUEST['hdnCurrencyCode_'.$_REQUEST['RadioCurrency']];
						$queryString .= '&product_name='.urlencode($_REQUEST['product_name'][$_REQUEST['hdnSubscriptionId']]);
						$queryString .= '&product_price='. number_format(floatval($_REQUEST['hdnPrice']),2);
						$queryString .= '&product_discount='. number_format(0,2);
						$queryString .= '&product_shipping_charge=0.00';

						$queryString .= '&prefix=AW_REC_MSUB-';
						$queryString .= '&product_item_id=AW_REC_MSUB-'.$order_id;
						$queryString .= '&user_id='.$returnValue;
						$extra = '../helper/paypal/recurring-payment/paypal-for-subscription.php?'.$queryString;

						$host  = $_SERVER['HTTP_HOST'];
						$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
						header("Location: http://$host$uri/$extra");
						exit;
					}
					else {
						//echo 'Error occured while saving subscription data';
						$queryString = "Error occured while saving subscription data";
						$extra = '../singup-form-subscription.php?';

						echo
						"<html>".
						"<body onLoad=\"document.forms['user_form'].submit();\">".
						"<form method=\"post\" name=\"user_form\" action=\"".$extra."\">";
						echo "<input type=\"hidden\" name=\"message\" value=\"".$queryString."\"/>";
						echo 		  "</form>".			  "</body>".		  "</html>";
					}
				}
				else {
					//echo 'Error occured while saving order data';
					$queryString = "Error occured while saving order data";
					$extra = '../singup-form-subscription.php?';
					echo
					"<html>".
					"<body onLoad=\"document.forms['user_form'].submit();\">".
					"<form method=\"post\" name=\"user_form\" action=\"".$extra."\">";
					echo 	"<input type=\"hidden\" name=\"message\" value=\"".$queryString."\"/>";
					echo "</form>".
							"</body>".
							"</html>";
				}

			}
			else {
				$request_url = $_SERVER['HTTP_REFERER'];
				echo "<html>".
						"<body onLoad=\"document.forms['user_form'].submit();\">".
						"<form method=\"post\" name=\"user_form\" action=\"".$request_url."\">";
				echo "<input type=\"hidden\" name=\"error\" value=\"Error Saving Data\"/>";
				echo 		  "</form></body></html>";
			}
		}
		catch(Exception $ex) {
			$request_url = $_SERVER['HTTP_REFERER'];
			echo
			"<html>".
			"<body onLoad=\"document.forms['user_form'].submit();\">".
			"<form method=\"post\" name=\"user_form\" action=\"".$request_url."\">";

			echo "<input type=\"hidden\" name=\"error\" value=\"".print_r($ex)."\"/>";

			echo 		  "</form>".			  "</body>".		  "</html>";
		}
	}
	//ADDED on 24-Apr-2013 Signup form with recurring subscription
}


function rand_passwd( $length = 8, $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789@#$%' ) {
	return substr( str_shuffle( $chars ), 0, $length );
}

function SaveUser($data, $option = 0) {
	$objUserDTO = new UserDTO();
	if($option == 1) {
		$objUserDTO = new userDTO();
		$objUserDTO->user_name = $data['txtUserEmail'];
		$objUserDTO->password = md5($data['txtPassword']);
		$objUserDTO->user_group = '3';
		$objUserDTO->status = 1;
	}
	else if($option == 2) {
		$objUserDTO->user_name = $data['txtUserEmail'];
		$objUserDTO->password = md5(rand_passwd())	;
		$objUserDTO->user_group = '5';
		$objUserDTO->status = 1;
	}

	$userObj = new User();
	$returnValue = $userObj->SaveUser($objUserDTO);
	return 	$returnValue;
}

function SaveUserProfile($data, $user_id) {
	$objUserProfileDTO = new userProfileDTO();
	$objUserProfileDTO->UserId = $user_id;
	$objUserProfileDTO->FirstName =$data['txtFirstName'];
	$objUserProfileDTO->LastName =$data['txtLastName'];
	$objUserProfileDTO->Gender = $data['rdoGender'];

	$userObj = new User();
	$returnValue = $userObj->SaveUserProfile($objUserProfileDTO);
	return 	$returnValue;
}

function UpdateUserProfile($data, $user_id) {
	$objUserProfileDTO = new userProfileDTO();
	$objUserProfileDTO->UserId = $user_id;
	$objUserProfileDTO->FirstName =$data['txtFirstName'];
	$objUserProfileDTO->LastName =$data['txtLastName'];
	$objUserProfileDTO->Gender = $data['rdoGender'];

	//echo '<br>'; print_r($objUserProfileDTO);
	$userObj = new User();
	$returnValue = $userObj->UpdateUserProfile($objUserProfileDTO);
	return 	$returnValue;
}

function SaveOrder($data, $user_id, $chk_register) {
	// delivery option 1 = Email, 2 = in post
	// Order Status 1 = awaiting for payment, 2 = payment accepted
	
	$objOrderDTO = new orderDTO();
	$objOrderDTO->delivery_option = 1;

	if(isset($_REQUEST['rdoDeliveryMethod'])) {
		$objOrderDTO->delivery_option = $_REQUEST['rdoDeliveryMethod'];
	}
	/*
	 if(isset($_REQUEST['rdoDeliveryMethod']) && $_REQUEST['rdoDeliveryMethod'] == 0) {
	$objOrderDTO->delivery_option = 1;
	}
	else if(isset($_REQUEST['rdoDeliveryMethod']) && $_REQUEST['rdoDeliveryMethod'] == 1) {
	$objOrderDTO->delivery_option = 2;
	}
	else {
	$objOrderDTO->delivery_option = 0;
	}*/
	if($data['ddProductType'] == 1) {
		$data['hdnId'] = $_REQUEST['hdnSoftwareFreeTrial'];
		$objOrderDTO->delivery_option = 1;
	}
	else if($data['ddProductType'] == 2) {
		$data['hdnId'] = $_REQUEST['hdnSoftwareRCode'];
		$objOrderDTO->delivery_option = 1;
	}
	else if($data['ddProductType'] == 3) {
		$data['hdnId']= $_REQUEST['hdnSoftwareOnCD'];
		$objOrderDTO->delivery_option = 2;
	}

	$objOrderDTO->user_id = $user_id;
	$objOrderDTO->product_item_id = $data['hdnId'];
	$objOrderDTO->price = $data['hdnPrice'];
	
	if(isset($data['discount'])) {
		$objOrderDTO->discount = $data['discount'];
	}
	$objOrderDTO->order_date = date('Y-m-d');
	
	//$objOrderDTO->order_status = '1';
	if($data['ddProductType'] == 1 || $data['ddProductType'] == 4) {
		$objOrderDTO->order_status = '2';
	}
	else {
		$objOrderDTO->order_status = '1';
	}
	
	$objOrderDTO->product_type = $data['ddProductType'];
	$objOrderDTO->currency_code = $data['hdnCurrencyCode'];
	$objOrderDTO->chk_for_register = $chk_register;
	
	if(isset($data['hdnShipping']) && $data['hdnShipping'] != "" ) {
		$objOrderDTO->shipping_charge = $data['hdnShipping'];
	} else {
		$objOrderDTO->shipping_charge = 0.00;
	}

	$objOrderDTO->email_id = $data['txtUserEmail'];
	$objOrderDTO->language_code = $data['ddLanguage'];

	$objOrderDTO->portalid = isset($data['hdnPortalId']) ? trim($data['hdnPortalId']) : 2;
	$objOrderDTO->payment_method = $data['payment_method'] == "paypal" ? 1 : 2;

	$objOrder = new Order();
	$returnValue = $objOrder->SaveOrder($objOrderDTO);
	return 	$returnValue;
}

function UpdateOrderData($data, $user_id, $order_id ) {
	$objOrderDTO = new orderDTO();

	$objOrderDTO->delivery_option = 1;

	if(isset($_REQUEST['rdoDeliveryMethod'])) {
		$objOrderDTO->delivery_option = $_REQUEST['rdoDeliveryMethod'];
	}

	//     if(isset($_REQUEST['rdoDeliveryMethod']) && $_REQUEST['rdoDeliveryMethod'] == 0) {
	//         $objOrderDTO->delivery_option = 1;
	//     }
	//     else if(isset($_REQUEST['rdoDeliveryMethod']) && $_REQUEST['rdoDeliveryMethod'] == 1) {
	//         $objOrderDTO->delivery_option = 2;
		//     }
		//     else {
		//         $objOrderDTO->delivery_option = 0;
		//     }

		$objOrderDTO->user_id = $user_id;
		$objOrderDTO->product_item_id = $data['hdnId'];
		$objOrderDTO->price = $data['hdnPrice'];
		if(isset($data['discount'])) {
			$objOrderDTO->discount = $data['discount'];
		}
		$objOrderDTO->order_date = date('Y-m-d');
		//$objOrderDTO->order_status = '1';
		if($data['ddProductType'] == 1 || $data['ddProductType'] == 4) {
			$objOrderDTO->order_status = '2';
		}
		else {
			$objOrderDTO->order_status = '1';
		}
		$objOrderDTO->product_type = $data['ddProductType'];
		$objOrderDTO->currency_code = $data['hdnCurrencyCode'];
		$objOrderDTO->order_id = $order_id;
		$objOrderDTO->email_id = $data['txtUserEmail'];
		$objOrderDTO->language_code = $data['ddLanguage'];
		if(isset($data['hdnShipping'])) {
			$objOrderDTO->shipping_charge = $data['hdnShipping'];
		}

		$objOrderDTO->portalid = isset($data['hdnPortalId']) ? trim($data['hdnPortalId']) : 2;

		$objOrder = new Order();
		$returnValue = $objOrder->UpdateOrderData($objOrderDTO);
		return 	$returnValue;
}

function SaveOrderShipping($data, $user_id, $order_id) {

	$objShipingDTO = new orderShippingDTO();
	$objShipingDTO->address_1 = isset($data['txtAdd1']) ? $data['txtAdd1'] : '';
	$objShipingDTO->address_2 = isset($data['txtAdd2']) ? $data['txtAdd2'] : '';
	$objShipingDTO->city = isset($data['txtCity']) ? $data['txtCity'] : '';
	$objShipingDTO->country = isset($data['txtCountry']) ? $data['txtCountry'] : '';
	
	if(isset($data['txtFirstName'])) {
		$objShipingDTO->first_name =$data['txtFirstName'];
	}
	else if(isset($data['txtFirstName1'])) {
		$objShipingDTO->first_name =$data['txtFirstName1'];
	}
	if(isset($data['txtLastName'])) {
		$objShipingDTO->last_name = $data['txtLastName'];
	}
	else if(isset($data['txtLastName1'])) {
		$objShipingDTO->last_name =$data['txtLastName1'];
	}

	$objShipingDTO->order_id = $order_id;
	$objShipingDTO->phone = isset($data['txtTelephone']) ? $data['txtTelephone'] : '';
	$objShipingDTO->postal_code = isset($data['txtPostcode']) ? $data['txtPostcode'] : '';
	$objShipingDTO->shipping_id = $data['ddProductType'];
	$objShipingDTO->state = isset($data['txtState']) ? $data['txtState'] : '';
	$objShipingDTO->user_id = $user_id;

	$objOrder = new Order();
	$returnValue = $objOrder->SaveOrderShipping($objShipingDTO);

	return 	$returnValue;
}

function UpdateOrderShipping($data, $user_id, $order_id, $shipping_id) {
	$objShipingDTO = new orderShippingDTO();
	$objShipingDTO->address_1 = isset($data['txtAdd1']) ? $data['txtAdd1'] : '';
	$objShipingDTO->address_2 = isset($data['txtAdd2']) ? $data['txtAdd2'] : '';
	$objShipingDTO->city = isset($data['txtCity']) ? $data['txtCity'] : '';
	$objShipingDTO->country = isset($data['txtCountry']) ? $data['txtCountry'] : '';
	$objShipingDTO->first_name = isset($data['txtFirstName']) ? $data['txtFirstName'] : '';
	$objShipingDTO->last_name = isset($data['txtLastName']) ? $data['txtLastName'] : '';
	$objShipingDTO->order_id = $order_id;
	$objShipingDTO->phone = isset($data['txtTelephone']) ? $data['txtTelephone'] : '';
	$objShipingDTO->postal_code = isset($data['txtPostcode']) ? $data['txtPostcode'] : '';
	$objShipingDTO->shipping_id = $data['ddProductType'];
	$objShipingDTO->state = isset($data['txtState']) ? $data['txtState'] : '';
	$objShipingDTO->user_id = $user_id;
	$objShipingDTO->shipping_id = $shipping_id;

	$objOrder = new Order();
	$returnValue = $objOrder->UpdateOrderShipping($objShipingDTO);
	return 	$returnValue;
}

function SaveUserBirthDetail($data, $user_id) {
	$objUserbIRTHDTO = new userBirthDetailDTO();

	$objUserbIRTHDTO->UserId = $user_id;
	$objUserbIRTHDTO->Day = $data['ddDay'];
	$objUserbIRTHDTO->Month = $data['ddMonth'];
	$objUserbIRTHDTO->Year = $data['ddYear'];
	$objUserbIRTHDTO->Hours = $data['birthhour'];
	$objUserbIRTHDTO->Minutes = $data['birthminute'];
	//$objUserbIRTHDTO->Seconds = $_REQUEST['txtUserEmail'];
	//$objUserbIRTHDTO->unTimed = $_REQUEST['txtUserEmail'];
	//$objUserbIRTHDTO->GMT = $_REQUEST['txtUserEmail'];
	//$objUserbIRTHDTO->ZoneRef = $_REQUEST['txtUserEmail'];
	//$objUserbIRTHDTO->SummerTimeZoneRef = $_REQUEST['txtUserEmail'];
	$objUserbIRTHDTO->Longitute = $_REQUEST['hdnLongitude'];
	$objUserbIRTHDTO->Lagitute = $_REQUEST['hdnLatitude'];
	//$objUserbIRTHDTO->CreatedDate = $_REQUEST['txtUserEmail'];
	//$objUserbIRTHDTO->ModifiedDate = $_REQUEST['txtUserEmail'];
	//$objUserbIRTHDTO->CreatedBy = $_REQUEST['txtUserEmail'];
	//$objUserbIRTHDTO->ModifiedBy = $_REQUEST['txtUserEmail'];

	$userBirthDto->sunsign = CalculateSunsignFromDate($_REQUEST['ddMonth'],$_REQUEST['ddDay']);

	$userObj = new User();
	$returnValue = $userObj->SaveUserBirthDetail($objUserbIRTHDTO);
	return 	$returnValue;
}

function UpdateUserBirthDetail($data, $user_id, $user_birth_detail_id ) {
	$objUserbIRTHDTO = new userBirthDetailDTO();
	$objUserbIRTHDTO->UserBirthDetailId = $user_birth_detail_id;
	$objUserbIRTHDTO->UserId = $user_id;
	$objUserbIRTHDTO->Day = $data['ddDay'];
	$objUserbIRTHDTO->Month = $data['ddMonth'];
	$objUserbIRTHDTO->Year = $data['ddYear'];
	$objUserbIRTHDTO->Hours = $data['birthhour'];
	$objUserbIRTHDTO->Minutes = $data['birthminute'];
	//$objUserbIRTHDTO->Seconds = $_REQUEST['txtUserEmail'];
	//$objUserbIRTHDTO->unTimed = $_REQUEST['txtUserEmail'];
	//$objUserbIRTHDTO->GMT = $_REQUEST['txtUserEmail'];
	//$objUserbIRTHDTO->ZoneRef = $_REQUEST['txtUserEmail'];
	//$objUserbIRTHDTO->SummerTimeZoneRef = $_REQUEST['txtUserEmail'];
	//$objUserbIRTHDTO->Longitute = $_REQUEST['txtUserEmail'];
	//$objUserbIRTHDTO->Lagitute = $_REQUEST['txtUserEmail'];
	//$objUserbIRTHDTO->CreatedDate = $_REQUEST['txtUserEmail'];
	//$objUserbIRTHDTO->ModifiedDate = $_REQUEST['txtUserEmail'];
	//$objUserbIRTHDTO->CreatedBy = $_REQUEST['txtUserEmail'];
	//$objUserbIRTHDTO->ModifiedBy = $_REQUEST['txtUserEmail'];

	$userBirthDto->sunsign = CalculateSunsignFromDate($_REQUEST['ddMonth'],$_REQUEST['ddDay']);

	$userObj = new User();
	$returnValue = $userObj->UpdateUserBirthDetail($objUserbIRTHDTO);
	return 	$returnValue;
}

function SaveBirthData($data ,$order_id ,$option = 1) {
	$objUserBirthData = new userBirthDataDTO();

	$objUserBirthData->day = $data['ddDay'.$option];
	$objUserBirthData->month = $data['ddMonth'.$option];
	$objUserBirthData->year = $data['ddYear'.$option];

	if($data['birthhour'.$option] == -1) {
		$objUserBirthData->untimed = 'yes';
	}
	else {
		$objUserBirthData->hour = $data['birthhour'.$option];
		$objUserBirthData->minute = $data['birthminute'.$option];
		$objUserBirthData->untimed = '';
	}

	$objASC = new acsAtlas();
	$result = $objASC->GetACSDetails($data['ddBirthCountry'.$option],$data['txtBirthCity'.$option],1);

	if(count($result) > 0) {
		$objUserBirthData->summerref = $result[0]->type;
		$objUserBirthData->zoneref = $result[0]->zone;
	}

	$objUserBirthData->latitude = $data['hdnLatitude'.$option];
	$objUserBirthData->longitude = $data['hdnLongitude'.$option];

	$objUserBirthData->zoneref = $result[0]->zone;


	$objUserBirthData->place = $data['txtBirthCity'.$option];
	$objUserBirthData->state = $data['ddBirthCountry'.$option];

	$objUserBirthData->first_name = $data['txtFirstName'.$option];
	$objUserBirthData->last_name = $data['txtLastName'.$option];
	$objUserBirthData->start_date = $data['start_date'];
	$objUserBirthData->gender = $data['rdoGender'];

	$objUserBirthData->orderid =  $order_id;

	$objOrder = new Order();
	$returnValue = $objOrder->SaveBirthData($objUserBirthData);
	return 	$returnValue;
}

function UpdateBirthData($data,$order_id,$birth_data_id, $option = 1) {
	//echo '<br>call update birth data';
	$objUserBirthData = new userBirthDataDTO();

	$objUserBirthData->day = $data['ddDay'.$option];
	$objUserBirthData->month = $data['ddMonth'.$option];
	$objUserBirthData->year = $data['ddYear'.$option];
	//$objUserBirthData->gmt = $_REQUEST['gmt'];
	if($data['birthhour'.$option] == -1) {
		$objUserBirthData->untimed = 'yes';
	}
	else {
		$objUserBirthData->hour = $data['birthhour'.$option];
		$objUserBirthData->minute = $data['birthminute'.$option];
	}

	$objASC = new acsAtlas();
	$result = $objASC->GetACSDetails($data['ddBirthCountry'.$option],$data['txtBirthCity'.$option],1);
	if(count($result) > 0) {
		$objUserBirthData->latitude = $result[0]->latitude;
		$objUserBirthData->longitude = $result[0]->longitude;
		$objUserBirthData->summerref = $result[0]->type;
		$objUserBirthData->zoneref = $result[0]->zone;
	}

	$objUserBirthData->place = $data['txtBirthCity'.$option];
	$objUserBirthData->state = $data['ddBirthCountry'.$option];

	$objUserBirthData->first_name = $data['txtFirstName'.$option];
	$objUserBirthData->last_name = $data['txtLastName'.$option];
	$objUserBirthData->start_date = $data['start_date'];

	$objUserBirthData->orderid =  $order_id;
	$objUserBirthData->birthdataid = $data['birthdataid'.$option];
	$objUserBirthData->gender = $data['rdoGender'];

	//echo '<br>'; print_r($objUserBirthData);
	$objOrder = new Order();
	$returnValue = $objOrder->UpdateBirthData($objUserBirthData);
	return 	$returnValue;
}

function SaveLoversData($data,$order_id,$birth_data_id, $option = 1) {
	//echo '<br>call save lover data';
	$objLoversDataDTO = new loversDataDTO();

	$objLoversDataDTO->birth_data_id = $birth_data_id;
	$objLoversDataDTO->gender = $data['rdoGender'.$option];
	$objLoversDataDTO->order_id = $order_id;
	$objLoversDataDTO->person_name = $data['txtFirstName'.$option]." ".$data['txtLastName'.$option];

	$objOrder = new Order();
	$returnValue = $objOrder->SaveLoversData($objLoversDataDTO);
	return 	$returnValue;
}

function UpdateLoversData($data,$order_id,$birth_data_id, $option = 1) {
	//echo '<br>call update lover data';
	$objLoversDataDTO = new loversDataDTO();

	$objLoversDataDTO->birth_data_id = $birth_data_id;
	$objLoversDataDTO->gender = $data['rdoGender'.$option];
	$objLoversDataDTO->order_id = $order_id;
	$objLoversDataDTO->person_name = $data['txtFirstName'.$option]." ".$data['txtLastName'.$option];

	//echo '<br>'; print_r($objLoversDataDTO);
	$objOrder = new Order();
	$objLoversDataDTO->lovers_report_data_id = $data['lovers_report_data_id1'];
	$returnValue = $objOrder->UpdateLoversData($objLoversDataDTO);
	return 	$returnValue;
}

function SaveSubscribedUser($subscription_id, $user_id, $duration) {
	$objSubscription = new Subscription();
	$objSubscriptionDTO = new SubscriptionUserDTO();

	$start = date('Y-m-d');
	$d = new DateTime( $start );
	//$d->setDate(date('Y', 'W', (date('d')+90)));
	$d->modify( "+$duration day" );
	$end = $d->format( 'Y-m-d' );

	$objSubscriptionDTO->subscription_user_id = '';
	$objSubscriptionDTO->subscription_id = $subscription_id;
	$objSubscriptionDTO->user_id = $user_id;
	$objSubscriptionDTO->start_date = $start;
	$objSubscriptionDTO->end_date = $end;
	$objSubscriptionDTO->status = 0;

	$returnValue = $objSubscription->SaveSubscribedUser($objSubscriptionDTO);
	return 	$returnValue;
	//echo "value:".$returnValue;
}

function SaveSubscribedUserwithOrderID($SubscriptionId, $UserId, $Duration, $OrderId) {
	$objSubscription = new Subscription();
	$objSubscriptionDTO = new SubscriptionUserDTO();

	$start = date('Y-m-d');
	$d = new DateTime( $start );
	//$d->setDate(date('Y', 'W', (date('d')+90)));
	$d->modify( "+$Duration day" );
	$end = $d->format( 'Y-m-d' );

	$objSubscriptionDTO->subscription_user_id = '';
	$objSubscriptionDTO->subscription_id = $SubscriptionId;
	$objSubscriptionDTO->user_id = $UserId;
	$objSubscriptionDTO->start_date = $start;
	$objSubscriptionDTO->end_date = $end;
	$objSubscriptionDTO->status = 0;
	$objSubscriptionDTO->orderId = $OrderId;

	$returnValue = $objSubscription->SaveSubscribedUser($objSubscriptionDTO);
	return 	$returnValue;
	//echo "value:".$returnValue;
}

function UpdateSubscribedUserStatus($subscription_id, $user_id,$status) {
	$objSubscription = new Subscription();
	$objSubscriptionDTO = new SubscriptionUserDTO();

	$objSubscriptionDTO->subscription_user_id = '';
	$objSubscriptionDTO->subscription_id = $subscription_id;
	$objSubscriptionDTO->user_id = $user_id;
	$objSubscriptionDTO->status = $status;

	$returnValue = $objSubscription->UpdateSubscribedUserStatus($objSubscriptionDTO);
	return 	$returnValue;
}

function SendOrderEmail($data) {
	$price = $data['hdnPrice'];
	if(isset($data['hdnDiscount'])) {
		$discount = $data['hdnDiscount'];
	}
	else {
		$discount = 0;
	}
	$discount = $discount * $price / 100;
	$subtotal = $price - $discount;

	$productName = '';
	if(isset($data['product_name'])) {
		$productName = $data['hdnSubscriptionId'];
	}
	else if(isset($data['hdnProductName'])) {
		$productName = $data['hdnProductName'];
	}

	$sender = "service@astrowow.com";
	$rec = array(
			'to'	=> $data['txtUserEmail'],
	);

	if(isset($data['hdnIsLoversReport'])) {
		echo 'if';
		if ( genericMail::SendLoverReportDataToAdmin($data ) ) {
			print "Mail was send successfull ... \n";
		} else {
			print "Hmm .. there could be a Problem ... \n";
		}
	}

	$data2 = array(
			'subject'			=> "Order Confirmation Mail",
			'mailtext'			=> "<h1>Order Confirm.</h1>",
			'type'                      => "html",
			//'attachment'		=> array( 'testfile.html' ),
			'order_date'		=> date("Y-m-d"),
			'product_name'		=> $productName,
			'product_price'		=> $price,
			'discount'			=> $discount,
			'subtotal'			=> $subtotal

	);

	if ( genericMail::SendSubscriptionBuyEmail( $sender, $rec, $data2 ) ) {
		print "Mail was send successfull ... \n";
	} else {
		print "Hmm .. there could be a Problem ... \n";
	}

	$rec = array(
			'to'	=> 'orders@astrowow.com',
	);

	if ( genericMail::SendSubscriptionBuyEmail( $sender, $rec, $data ) ) {
		print "Mail was send successfull ... \n";
	} else {
		print "Hmm .. there could be a Problem ... \n";
	}
}

function SendPaymentAcceptedEmail($data) {
	$price = $data['price'];
	$discount = $data['discount'];
	$shipping = $data['shipping'];
	$discount = $discount * $price / 100;
	$subtotal = $price - $discount;

	$sender = "service@astrowow.com";
	$rec = array(
			'to'	=> $data['user_email'],
	);

	$data = array(
			'subject'			=> "Your Payment of order no ".$data['order_id']." accepted",
			'mailtext'			=> "<h1>Payment Accepted</h1>",
			'type'				=> "html",
			//'attachment'		=> array( 'testfile.html' ),
			'order_date'		=> date("Y-m-d"),
			'product_name'		=> $data['product_name'],
			'product_price'		=> $price,
			'discount'			=> $discount,
			'shipping'			=> $shipping,
			'subtotal'			=> $subtotal

	);

	$to = $data['user_email'];
	// TODO :: for now we send mail derectly, letter on it will fired from generic mail class
	$subject = "Your Payment of order no ".$data['order_id']." accepted";
	$body =  "Payment Accepted\n>";
	$body =  "Order No:".$data['order_id'];
	$body =  "Product Name:".$data['product_name'];


	mail($to, $subject, $body);

	/*if ( genericMail::SendPaymentAcceptedEmail( $sender, $rec, $data ) ) {
	 print "Mail was send successfull ... \n";
	} else {
	print "Hmm .. there could be a Problem ... \n";
	}*/
}

function SendSoftwareBuyEmail($data) {
	//print_r($data);
	$price = $data['hdnPrice'];
	if(isset($data['hdnDiscount'])) {
		$discount = $data['hdnDiscount'];
	}
	else {
		$discount = 0;
	}

	$discount = $discount * $price / 100;
	$subtotal = $price - $discount;

	$productName = '';
	if(isset($data['product_name'])) {
		$productName = $data['hdnSubscriptionId'];
	}
	else if(isset($data['hdnProductName'])) {
		$productName = $data['hdnProductName'];
	}

	$sender = "service@astrowow.com";
	$rec = $data['txtUserEmail'];

	if($data['ddProductType'] == 2) // Shareware
	{
		$data = array(
				'subject'			=> "Order Confirmation Mail",
				//'site_name'			=> BASEURL,
				'mailtext'			=> "<h1>Order Confirm.</h1>",
				'type'				=> "html",
				//'attachment'		=> array( 'testfile.html' ),
				'order_date'		=> date("Y-m-d"),
				'product_name'		=> $productName,
				'product_price'		=> $price,
				'discount'			=> $discount,
				'subtotal'			=> $subtotal,
				'email'				=> $data['txtUserEmail'],
				'product_type'		=> $data['ddProductType']		,
				'language'			=> $data['ddLanguage']	,
				'order_id'			=> $data['order_id']
		);
	}
	else {
		$data = array(
				'subject'			=> "Order Confirmation Mail",
				//'site_name'			=> BASEURL,
				'mailtext'			=> "<h1>Order Confirm.</h1>",
				'type'				=> "html",
				//'attachment'		=> array( 'testfile.html' ),
				'order_date'		=> date("Y-m-d"),
				'product_name'		=> $productName,
				'product_price'		=> $price,
				'discount'			=> $discount,
				'subtotal'			=> $subtotal,
				'first_name'		=> $data['txtFirstName'],
				'last_name'			=> $data['txtLastName'],
				'address_1'			=> $data['txtAdd1'],
				'address_2'			=> $data['txtAdd2'],
				'city'				=> $data['txtCity'],
				'state'				=> $data['txtState'],
				'country'			=> $data['txtCountry'],
				'postal_code'		=> $data['txtPostcode'],
				'telephone'			=> $data['txtTelephone'],
				'email'				=> $data['txtUserEmail'],
				'product_type'		=> $data['ddProductType']	,
				'language'			=> $data['ddLanguage']	,
				'order_id'			=> $data['order_id']
		);
	}




	if ( genericMail::SendSoftwareBuyEmail( $sender, $rec, $data ) ) {
		//print "Mail was send successfull ... \n";
	} else {
		//print "Hmm .. there could be a Problem ... \n";
	}

	//exit;
}


function SendFreeTrialSoftwareDownload($data, $order_id, $donwload_link) {


	$productName = '';
	if(isset($data['product_name'])) {
		$productName = $data['hdnSubscriptionId'];
	}
	else if(isset($data['hdnProductName'])) {
		$productName = $data['hdnProductName'];
	}

	$sender = "service@astrowow.com";
	$rec = array(
			'to'	=> $data['txtUserEmail'],
	);
	$data = array(
			'subject'			=> "Order Confirmation Mail",
			'mailtext'			=> "<h1>Order Confirm.</h1>",
			'type'              => "html",
			'order_date'		=> date("Y-m-d"),
			'product_id'        => $data['hdnProductId'],
			'product_name'		=> $productName,
			'product_price'		=> 0,
			'discount'			=> 0,
			'subtotal'			=> 0,
			'first_name'		=> $data['txtFirstName'],
			'last_name'			=> $data['txtLastName'],
			'address_1'			=> $data['txtAdd1'],
			'address_2'			=> $data['txtAdd2'],
			'city'				=> $data['txtCity'],
			'state'				=> $data['txtState'],
			'country'			=> $data['txtCountry'],
			'postal_code'		=> $data['txtPostcode'],
			'telephone'			=> $data['txtTelephone'],
			'email'				=> $data['txtUserEmail'],
			'product_type'		=> $data['ddProductType'],
			'language'			=> $data['ddLanguage'],
			'order_id'			=> $order_id,
			'donwload_link'     => $donwload_link,
			'language_id'		=> (isset($_COOKIE['language']) ? $_COOKIE['language'] : 'en')
	);
	if ( genericMail::SendFreeTrailSoftwareDownloadLink($sender, $rec, $data) ) {
		//print "Mail was send successfull ... \n";
	} else {
		//print "Hmm .. there could be a Problem ... \n";
	}
}
if(isset($_REQUEST['task']) == "lovetest") {
	if($_REQUEST['task'] == "lovetest") {
		if( isset($_REQUEST['oid']) ) {
			$OId = $_REQUEST['oid'];
			//if(genericMail::SendLoverReportNotification('3544')) {
			if(genericMail::SendLoverReportNotification($OId)) {
				echo "EMail is sent to Amit";
			}
			else {
				echo "Ohhhh there is error ";
			}
		}
	}
}

function SetNewUserDiscount($ProductItemId, $CurrencyId, $RequestedData) {
	$objOrder = new Order();
	$returnValue = $objOrder->GetDiscountedPricesByProductAndCurrency($ProductItemId, $CurrencyId);

	foreach ($returnValue as $item) {
		$Price_1 =  $item['price_1'];
		$DicountedPrice_1 =  $item['discounted_price_1'];
		$PostedCharge = $item['packagePostalCharge'];

		if( $RequestedData['rdoDeliveryMethod'] == 2){
			$Price_1 =  $item['price_2'];
			$DicountedPrice_1 =  $item['discounted_price_2'];
		}

		$Discount = floatval($Price_1) - floatval($DicountedPrice_1);
		$RequestedData['discount'] = $Discount;
		$RequestedData['hdnShipping'] = $PostedCharge;
		$RequestedData['hdnPrice'] = $Price_1;
	}
}



function SaveUpsaleOrder($NewOrderId, $OldOrderId) {
	$objOrder = new Order();
	$returnValue = $objOrder->SaveUpsaleOrder($NewOrderId, $OldOrderId);
}

/**
 * 'FormatCurrency' Function to convert your floating int into a
 * @author Joel Peterson - @joelasonian - www.joelpeterson.com
 * @param flatcurr	float	integer to convert
 * @param curr	string of desired currency format
 * @return formatted number
 */
function FormatCurrency($floatcurr, $curr = "USD") {
	$currencies['ARS'] = array(2,',','.');			//	Argentine Peso
	$currencies['AMD'] = array(2,'.',',');			//	Armenian Dram
	$currencies['AWG'] = array(2,'.',',');			//	Aruban Guilder
	$currencies['AUD'] = array(2,'.',' ');			//	Australian Dollar
	$currencies['BSD'] = array(2,'.',',');			//	Bahamian Dollar
	$currencies['BHD'] = array(3,'.',',');			//	Bahraini Dinar
	$currencies['BDT'] = array(2,'.',',');			//	Bangladesh, Taka
	$currencies['BZD'] = array(2,'.',',');			//	Belize Dollar
	$currencies['BMD'] = array(2,'.',',');			//	Bermudian Dollar
	$currencies['BOB'] = array(2,'.',',');			//	Bolivia, Boliviano
	$currencies['BAM'] = array(2,'.',',');			//	Bosnia and Herzegovina, Convertible Marks
	$currencies['BWP'] = array(2,'.',',');			//	Botswana, Pula
	$currencies['BRL'] = array(2,',','.');			//	Brazilian Real
	$currencies['BND'] = array(2,'.',',');			//	Brunei Dollar
	$currencies['CAD'] = array(2,'.',',');			//	Canadian Dollar
	$currencies['KYD'] = array(2,'.',',');			//	Cayman Islands Dollar
	$currencies['CLP'] = array(0,'','.');			//	Chilean Peso
	$currencies['CNY'] = array(2,'.',',');			//	China Yuan Renminbi
	$currencies['COP'] = array(2,',','.');			//	Colombian Peso
	$currencies['CRC'] = array(2,',','.');			//	Costa Rican Colon
	$currencies['HRK'] = array(2,',','.');			//	Croatian Kuna
	$currencies['CUC'] = array(2,'.',',');			//	Cuban Convertible Peso
	$currencies['CUP'] = array(2,'.',',');			//	Cuban Peso
	$currencies['CYP'] = array(2,'.',',');			//	Cyprus Pound
	$currencies['CZK'] = array(2,'.',',');			//	Czech Koruna
	$currencies['DKK'] = array(2,',','.');			//	Danish Krone
	$currencies['DOP'] = array(2,'.',',');			//	Dominican Peso
	$currencies['XCD'] = array(2,'.',',');			//	East Caribbean Dollar
	$currencies['EGP'] = array(2,'.',',');			//	Egyptian Pound
	$currencies['SVC'] = array(2,'.',',');			//	El Salvador Colon
	$currencies['ATS'] = array(2,',','.');			//	Euro
	$currencies['BEF'] = array(2,',','.');			//	Euro
	$currencies['DEM'] = array(2,',','.');			//	Euro
	$currencies['EEK'] = array(2,',','.');			//	Euro
	$currencies['ESP'] = array(2,',','.');			//	Euro
	$currencies['EUR'] = array(2,',','.');			//	Euro
	$currencies['FIM'] = array(2,',','.');			//	Euro
	$currencies['FRF'] = array(2,',','.');			//	Euro
	$currencies['GRD'] = array(2,',','.');			//	Euro
	$currencies['IEP'] = array(2,',','.');			//	Euro
	$currencies['ITL'] = array(2,',','.');			//	Euro
	$currencies['LUF'] = array(2,',','.');			//	Euro
	$currencies['NLG'] = array(2,',','.');			//	Euro
	$currencies['PTE'] = array(2,',','.');			//	Euro
	$currencies['GHC'] = array(2,'.',',');			//	Ghana, Cedi
	$currencies['GIP'] = array(2,'.',',');			//	Gibraltar Pound
	$currencies['GTQ'] = array(2,'.',',');			//	Guatemala, Quetzal
	$currencies['HNL'] = array(2,'.',',');			//	Honduras, Lempira
	$currencies['HKD'] = array(2,'.',',');			//	Hong Kong Dollar
	$currencies['HUF'] = array(0,'','.');			//	Hungary, Forint
	$currencies['ISK'] = array(0,'','.');			//	Iceland Krona
	$currencies['INR'] = array(2,'.',',');			//	Indian Rupee
	$currencies['IDR'] = array(2,',','.');			//	Indonesia, Rupiah
	$currencies['IRR'] = array(2,'.',',');			//	Iranian Rial
	$currencies['JMD'] = array(2,'.',',');			//	Jamaican Dollar
	$currencies['JPY'] = array(0,'',',');			//	Japan, Yen
	$currencies['JOD'] = array(3,'.',',');			//	Jordanian Dinar
	$currencies['KES'] = array(2,'.',',');			//	Kenyan Shilling
	$currencies['KWD'] = array(3,'.',',');			//	Kuwaiti Dinar
	$currencies['LVL'] = array(2,'.',',');			//	Latvian Lats
	$currencies['LBP'] = array(0,'',' ');			//	Lebanese Pound
	$currencies['LTL'] = array(2,',',' ');			//	Lithuanian Litas
	$currencies['MKD'] = array(2,'.',',');			//	Macedonia, Denar
	$currencies['MYR'] = array(2,'.',',');			//	Malaysian Ringgit
	$currencies['MTL'] = array(2,'.',',');			//	Maltese Lira
	$currencies['MUR'] = array(0,'',',');			//	Mauritius Rupee
	$currencies['MXN'] = array(2,'.',',');			//	Mexican Peso
	$currencies['MZM'] = array(2,',','.');			//	Mozambique Metical
	$currencies['NPR'] = array(2,'.',',');			//	Nepalese Rupee
	$currencies['ANG'] = array(2,'.',',');			//	Netherlands Antillian Guilder
	$currencies['ILS'] = array(2,'.',',');			//	New Israeli Shekel
	$currencies['TRY'] = array(2,'.',',');			//	New Turkish Lira
	$currencies['NZD'] = array(2,'.',',');			//	New Zealand Dollar
	$currencies['NOK'] = array(2,',','.');			//	Norwegian Krone
	$currencies['PKR'] = array(2,'.',',');			//	Pakistan Rupee
	$currencies['PEN'] = array(2,'.',',');			//	Peru, Nuevo Sol
	$currencies['UYU'] = array(2,',','.');			//	Peso Uruguayo
	$currencies['PHP'] = array(2,'.',',');			//	Philippine Peso
	$currencies['PLN'] = array(2,'.',' ');			//	Poland, Zloty
	$currencies['GBP'] = array(2,'.',',');			//	Pound Sterling
	$currencies['OMR'] = array(3,'.',',');			//	Rial Omani
	$currencies['RON'] = array(2,',','.');			//	Romania, New Leu
	$currencies['ROL'] = array(2,',','.');			//	Romania, Old Leu
	$currencies['RUB'] = array(2,',','.');			//	Russian Ruble
	$currencies['SAR'] = array(2,'.',',');			//	Saudi Riyal
	$currencies['SGD'] = array(2,'.',',');			//	Singapore Dollar
	$currencies['SKK'] = array(2,',',' ');			//	Slovak Koruna
	$currencies['SIT'] = array(2,',','.');			//	Slovenia, Tolar
	$currencies['ZAR'] = array(2,'.',' ');			//	South Africa, Rand
	$currencies['KRW'] = array(0,'',',');			//	South Korea, Won
	$currencies['SZL'] = array(2,'.',', ');			//	Swaziland, Lilangeni
	$currencies['SEK'] = array(2,',','.');			//	Swedish Krona
	$currencies['CHF'] = array(2,'.','\'');			//	Swiss Franc
	$currencies['TZS'] = array(2,'.',',');			//	Tanzanian Shilling
	$currencies['THB'] = array(2,'.',',');			//	Thailand, Baht
	$currencies['TOP'] = array(2,'.',',');			//	Tonga, Paanga
	$currencies['AED'] = array(2,'.',',');			//	UAE Dirham
	$currencies['UAH'] = array(2,',',' ');			//	Ukraine, Hryvnia
	$currencies['USD'] = array(2,'.',',');			//	US Dollar
	$currencies['VUV'] = array(0,'',',');			//	Vanuatu, Vatu
	$currencies['VEF'] = array(2,',','.');			//	Venezuela Bolivares Fuertes
	$currencies['VEB'] = array(2,',','.');			//	Venezuela, Bolivar
	$currencies['VND'] = array(0,'','.');			//	Viet Nam, Dong
	$currencies['ZWD'] = array(2,'.',' ');			//	Zimbabwe Dollar

	function formatinr($input){
		//CUSTOM FUNCTION TO GENERATE ##,##,###.##
		$dec = "";
		$pos = strpos($input, ".");
		if ($pos === false){
			//no decimals
		} else {
			//decimals
			$dec = substr(round(substr($input,$pos),2),1);
			$input = substr($input,0,$pos);
		}
		$num = substr($input,-3); //get the last 3 digits
		$input = substr($input,0, -3); //omit the last 3 digits already stored in $num
		while(strlen($input) > 0) //loop the process - further get digits 2 by 2
		{
			$num = substr($input,-2).",".$num;
			$input = substr($input,0,-2);
		}
		return $num . $dec;
	}

	if ($curr == "INR"){
		return formatinr($floatcurr);
	} else {
		return number_format($floatcurr,$currencies[$curr][0],$currencies[$curr][1],$currencies[$curr][2]);
	}
}

/*
FormatCurrency(1000045.25);				//1,000,045.25 (USD)
FormatCurrency(1000045.25, "CHF");		//1'000'045.25
FormatCurrency(1000045.25, "EUR");		//1.000.045,25
FormatCurrency(1000045, "JPY");			//1,000,045
FormatCurrency(1000045, "LBP");			//1 000 045
FormatCurrency(1000045.25, "INR");		//10,00,045.25
*/
?>