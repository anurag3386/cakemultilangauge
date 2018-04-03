<?php
/**
 * Script: class.payment.paypal.generic.php
 * Author: Andy Gray
 *
 * Description
 * Generic extension of PayPal IPN generic class
 *
 * Modification History
 * - initial spike
 */

if (!defined('ROOTPATH')) {
	define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/');
}

class eLiteGenericPaypalTransaction extends paypal_class {

	private	$email_product_description;
	private	$printed_product_description;

	function eLiteGenericPaypalTransaction() {

		global $_SERVER;

		$this->paypal_class();
		$this->paypal_url = PAYAPL_URL;

		$this->add_field('business', BUSINESS_PAYPAL_ID);
		//  echo $this_script;

		/* navigation detail */
		$this_script = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
		$this->add_field('return', $this_script.'?action=success');
		$this->add_field('cancel_return', $this_script.'?action=cancel');
		$this->add_field('notify_url', $this_script.'?action=ipn');

		/* order detail */
		$this->add_field('quantity', '1');
		$this->add_field('item_number', md5("world-of-wisdom.com"));

		/* order description */
		$this->email_product_description = 'Beautiful colour printout via email as PDF file attachment';
		$this->printed_product_description = 'Beautifully bound colour printout via post';

		/* transaction detail */
		//$this->add_field('currency_code', 'GBP');
		$this->add_field('currency_code', 'USD');
		$this->add_field('shipping', '0');
		$this->add_field('shipping2', '0');
		$this->add_field('handling', '0');
		$this->add_field('no_shipping', '1');
		$this->add_field('no_note', '1');
		//$this->add_field('custom','default1'.$_COOKIE['PAPVisitorId']);
	}

	protected function setEmailProductDescription( $description ) {
		$this->email_product_description = $description;
	}

	protected function setPrintedProductDescription( $description ) {
		$this->printed_product_description = $description;
	}

	/**
	 * manage process
	 */
	function manage_process( $orderId,$currency_code,$product_item_number,$product_name,$product_price,$product_discount,$product_shipping_charge,$user_id) {

		$this->add_field('invoice', $orderId );
		$this->add_field('currency_code', $currency_code);

		/* navigation detail */
		$this_script = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];

		/* order detail */
		$this->add_field('quantity', '1');
		$this->add_field('item_number', $product_item_number);
		$this->add_field('item_name', utf8_decode(utf8_encode($product_name)));

		$this->add_field('amount', number_format(floatval(str_replace(',', '', $product_price)) ,2));

		$this->add_field('country_code', 'US');
		
		//$this->add_field('custom', $user_id);
		$Custom = $user_id . "|" . (isset($_COOKIE['language']) ? strtolower($_COOKIE['language']) : "en");
		$this->add_field('custom', $Custom);
		
		if(isset($_COOKIE['language'])) {
			if(strtolower($_COOKIE['language']) == "dk") {
				$this->add_field('lc', "da_DK");
				$this->add_field('option_name1', 'dk');
			} else {
				$this->add_field('lc', "US");
				$this->add_field('option_name1', 'en');
			}
		} else {
			$this->add_field('lc', "US");
			$this->add_field('option_name1', 'en');
		}
		$this->add_field('discount_rate', 0);

		if($product_shipping_charge > 0) {
			$this->add_field('shipping', $product_shipping_charge);
		}
		$this->send();
	}

	/**
	 * manage_ipn
	 * Paypal has sent a dark notification which requires no response
	 */
	function manage_ipn() {

		global $logger;

		// It's important to remember that paypal calling this script.  There
		// is no output here.  This is where you validate the IPN data and if it's
		// valid, update your database to signify that the user has payed.  If
		// you try and use an echo or printf function here it's not going to do you
		// a bit of good.  This is on the "backend".  That is why, by default, the
		// class logs all IPN data to a text file.
				
		if ($this->validate_ipn()) {

			// Payment has been received and IPN is verified.  This is where you
			// update your database to activate or process the order, or setup
			// the database with the user's order details, email an administrator,
			// etc.  You can access a slew of information via the ipn_data() array.

			// Check the paypal documentation for specifics on what information
			// is available in the IPN POST variables.  Basically, all the POST vars
			// which paypal sends, which we send back for validation, are now stored
			// in the ipn_data() array.

			// For this example, we'll just email ourselves ALL the data.

			// send transaction details via email
			$subject = 'Paypal IPN Gateway - Payment Received';

			$to = $this->admin_email;
			$body =  "An instant payment notification was successfully received\n";
			$body .= "from ".$this->ipn_data['payer_email']." on ".date('m/d/Y');
			$body .= " at ".date('g:i A')."\n\nDetails:\n";

			foreach ($this->ipn_data as $key => $value) {
				$body .= "\n$key: $value";
			}
			mail($to, $subject, $body);
			
			$order_state = 1;
			switch( strtolower( $this->ipn_data['payment_status'] ) ) {
				case strtolower( 'Completed' ):
					//$order_state = 2;         //PAYMENT IS ACCEPTED
					$order_state = 12;          //SEND TO ORDER QUEUE
					break;
				case strtolower( 'Refunded' ):
					$order_state = 3;
					break;
				case strtolower( 'Pending' ):
					$order_state = 10;
					break;
				default:
					$order_state = 1;
					break;
			}
			
			//NEW CUSTOMIZATION WITH ELITE CUSTOMER ORDER
			$userObj = new EliteUser();
			$objOrder = new eLiteProduct();
			$objUserRep = new EliteUserRepository();
			$OrderID = 0;		
			$eMailId = '';
			
			//NEW CUSTOMIZATION WITH ELITE CUSTOMER ORDER

			try {				
				$transactionData = new ArrayObject();
				$transactionData->PaymentStatus         = $this->ipn_data['payment_status'];
				$transactionData->PaymentDate 			= $this->ipn_data['payment_date'];
				$transactionData->PaymentDetail         = $this->ipn_data['payment_status'];
				$transactionData->OrderID 				= $this->ipn_data['invoice'];
				$transactionData->FullName 				= sprintf("%s %s", $this->ipn_data['first_name'], $this->ipn_data['last_name']);
				$transactionData->PayerEmail 			= trim($this->ipn_data['payer_email']);;
				$transactionData->Amount 				= $this->ipn_data['payment_gross'];
				$transactionData->CurrencyCode 			= $this->ipn_data['mc_currency'];
				$transactionData->PaypalTxnID 			= $this->ipn_data['txn_id'];
				
				$OrderID = $this->ipn_data['invoice'];
				
				$returnValue = $objOrder->SaveOrderTransactionDetail($transactionData);			
				$returnValue = $objOrder->UpdateOrderStatusAndMemberShipdate($OrderID);

				if( $order_state == 2 ||  $order_state == 12) {
					$CustomArray = explode("|", $this->ipn_data['custom']);
					
					if( count($CustomArray) > 0 ) {
						$eMailId = $CustomArray[0];
						$user_id = $CustomArray[0];
						$languageid = $CustomArray[1];
					} else  {
						$eMailId = $this->ipn_data['custom'];
						$user_id = $this->ipn_data['custom'];
						$languageid = 'en';
					}
										
					$order_id = $this->ipn_data['invoice'];					
					$item_name = $this->ipn_data['item_name'];
					$discount = $this->ipn_data['discount'];
					$item_number = $this->ipn_data['item_number'];
					$handling_amount = $this->ipn_data['handling_amount'];
					$payment_gross = $this->ipn_data['payment_gross'];
					$shipping = $this->ipn_data['shipping'];
					$firstName = $this->ipn_data['first_name'];
					$lastName = $this->ipn_data['last_name'];
					
					$rec = array('to'	=> $eMailId);
						
					$LanguageCode = 'en';
						
					if(isset($_COOKIE['language']) && !empty($_COOKIE['language'])) {
						$LanguageCode = trim($_COOKIE['language']);
					}
						
					$data = array(
							'subject'			=> "Welcome to Astrowow.com‏",
							'mailtext'			=> "<h1>Hello,</h1>",
							'type'				=> "html",
							'language_id'		=> $LanguageCode,
							'username'			=> trim($eMailId),
							'name'				=> sprintf("%s %s", $firstName, $lastName),
							'sitelink'			=> BASEURL
					);						
					$IsMailSent = false;
						
					$sender = "astrowow-team@astrowow.com";
					if ( genericMail::SendElitCustomerPaymentConfirmation( $sender, $rec, $data ) ) {
						$IsMailSent = true;
					}					
				}
				else if( $order_state == 3) {
					$OrderID = $this->ipn_data['invoice'];
					$objOrder = new Order();
					$returnValue = $objOrder->UpdateOrderStatusFromIPN($OrderID, $order_state);
				}
				else if( $order_state == 10) {
					$OrderID = $this->ipn_data['invoice'];
					$objOrder = new Order();
					$returnValue = $objOrder->UpdateOrderStatusFromIPN($OrderID, $order_state);
				}
			}
			catch(Exception $ex) {
				mail("parmaramit1111@gmail.com", "Error While ", $ex->getMessage());
			}
		}
	}

	/**
	 * manage_success
	 * Paypal has returned a successful transaction notification
	 * Progress the order from awaiting payment to queued
	 * For personal/season reports {
	 *   Managed by automation
	 * } else {
	 *   Managed by manual process
	 * }
	 */
	function manage_success( $callback ) {

		global $_POST;
		global $logger;

		// log the post variables
		foreach( $_POST as $key => $value ) {
			//$logger->debug("manage_success: $key: $value");
		}

		// route to the callback URL
		header("Location: " . $callback);
	}

	/**
	 * manage_cancel
	 * Paypal has returned a failed transaction notification
	 * No information seems to be passed with this notification
	 */
	function manage_cancel( $callback ) {

		global $_POST;
		global $logger;

		foreach( $_POST as $key => $value ) {
			$logger->debug("manage_success: $key: $value");
		}

		// route to the callback URL
		header("Location: " . $callback);
	}
};