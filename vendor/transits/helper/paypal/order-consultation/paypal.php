<?php
require_once ( 'paypal-config.php');
require_once ( 'paypal.class.php');
require_once ( 'class.payment.paypal.generic.php');
require_once ( 'class.payment.paypal.wow.php');

echo "Processing...";

$action = $_REQUEST['action'];
if (isset ( $action )) {

	$transaction = new WOWPaypalTransaction ();
	switch ($action) {
		case 'process' :
			$orderId = $_REQUEST ['orderid'];
			$currency_code =isset( $_REQUEST ['currency_code'] )  ? $_REQUEST ['currency_code'] : "USD";
			$product_item_number = $_REQUEST ['product_item_id'];
			$product_name = $_REQUEST ['product_name'];
			$product_price = $_REQUEST ['product_price'];
				
			if(isset($_REQUEST ['product_discount'])) {
				$product_discount = $_REQUEST ['product_discount'];
			}
			else {
				$product_discount = 0;
			}

			if(isset($_REQUEST ['product_discount'])) {
				$product_shipping_charge = $_REQUEST ['product_shipping_charge'];
			}
			else {
				$product_shipping_charge = 0;
			}
			if(isset($_REQUEST ['user_id'])) {
				$user_id = $_REQUEST ['user_id'];
			}
			else {
				$user_id = 0;
			}

			// echo "1= $orderId, | 2= $currency_code, | 3= $product_item_number, | 4= $product_name,  | 5= $product_price, | 6= $product_discount, | 7= $product_shipping_charge";
			$transaction->manage_process ( $orderId, $currency_code, $product_item_number, $product_name, $product_price, $product_discount, $product_shipping_charge, $user_id );

			break;
		case 'success' :
			$transaction->manage_success ( SUCCESS_URL );
			break;
		case 'cancel' :
			$transaction->manage_success ( CANCEL_URL );
			break;
		case 'ipn':
			$transaction->manage_ipn ();
			break;
		default :
	}
}
else {
	$orderId = $_REQUEST ['orderid'];
	$transaction = new WOWPaypalTransaction ();
	$transaction->manage_process ( $orderId );
}
die ();
?>