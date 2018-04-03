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
    //define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/');
    define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/');
}

class GenericPaypalTransaction extends paypal_class {

    private	$email_product_description;
    private	$printed_product_description;

    function GenericPaypalTransaction() {

        global $_SERVER;

        $this->paypal_class();
        //$this->paypal_url = 'https://www.paypal.com/cgi-bin/webscr';
        $this->paypal_url = PAYAPL_URL;
        //$this->paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';

        /* set business owner */
        //$this->add_field('business', 'dhruv_1350561627_biz@gmail.com');
        //$this->add_field('business', 'abhi_p_1352295643_biz@yahoo.co.in');
        //$this->add_field('business', 'ard@world-of-wisdom.com');
        $this->add_field('business', BUSINESS_PAYPAL_ID);

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

        /*$state = new State();
		$states = array();
		$statelist = $state->GetList( array( array( 'stateId', '>', 0)));
		foreach( $statelist as $item ) {
			$states[$item->name] = $item->stateId;
		}*/

        //$this->getOrderContext( $orderId );
        //added by dhruvraj
        //$this->add_field('item_number', $orderId );
        $this->add_field('invoice', $orderId );
        $this->add_field('currency_code', $currency_code);

        /* navigation detail */
        $this_script = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
//        $this->add_field('return', $this_script.'?action=success');
//        $this->add_field('cancel_return', $this_script.'?action=cancel');
//        $this->add_field('notify_url', $this_script.'?action=ipn');


        /* order detail */
        $this->add_field('quantity', '1');
        $this->add_field('item_number', $product_item_number);
        //$this->add_field('item_name', urldecode($product_name));
        //$this->add_field('item_name', utf8_decode(utf8_encode($product_name)));
        $this->add_field('item_name', trim(stripslashes(html_entity_decode(utf8_decode(utf8_encode(urldecode($product_name)))))));
        //$this->add_field('item_price', 25);
        //$this->add_field('amount', $product_price);
        $this->add_field('amount', number_format(floatval( $product_price),2));

        $this->add_field('country_code', 'US');
        
        $this->add_field('custom', $user_id);        
       	if(isset($_COOKIE['language'])) {
        	if(strtolower($_COOKIE['language']) == "dk") {
        		$this->add_field('lc', "da_DK"); 
        	} else {
        		$this->add_field('lc', "US");
			}
		} else {
        	$this->add_field('lc', "US");
		}

//         if($product_discount > 0) {
//             $this->add_field('discount_rate', $product_discount);
//         }
        $this->add_field('discount_rate', 0);

        if($product_shipping_charge > 0) {
            $this->add_field('shipping', $product_shipping_charge);
        }

        //print_r($this);
        //exit;
        // update order state
        /*$order = new Order();
		$order->get( $orderId );
		$order->state = $states['awaitingpayment'];
		$order->save();

		// add transaction
		$transaction = new Transaction();
		$transaction->orderId = $order->orderId;
		$transaction->state = $order->state;
		$transaction->timestamp = strftime("%Y-%m-%d %H:%M:%S");
		$transaction->save();*/

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
                default:
                    $order_state = 1;
                    break;
            }

            $objOrder = new Order();
            $objUser = new User();

            // SAVE IPN DETAIL INTO TRANSACTION TABLE

            $transactionData = new ArrayObject();
            $transactionData->PaymentStatus                     = $this->ipn_data['payment_status'];
            $transactionData->PaymentDate 			= $this->ipn_data['payment_date'];
            $transactionData->PaymentDetail                     = $this->ipn_data['payment_status'];
            $transactionData->OrderID 				= $this->ipn_data['invoice'];
            $transactionData->FullName 				= $this->ipn_data['first_name']." ".$this->ipn_data['last_name'];
            $transactionData->PayerEmail 			= $this->ipn_data['payer_email'];
            $transactionData->Amount 				= $this->ipn_data['payment_gross'];
            $transactionData->CurrencyCode 			= $this->ipn_data['mc_currency'];
            $transactionData->PaypalTxnID 			= $this->ipn_data['txn_id'];

            try {

                $returnValue = $objOrder->SaveOrderTransactionDetail($transactionData);

                if( $order_state == 2 ||  $order_state == 12) {
                    $order_id = $this->ipn_data['invoice'];
                    $user_id = $this->ipn_data['custom'];
                    $item_name = $this->ipn_data['item_name'];
                    $discount = $this->ipn_data['discount'];
                    $item_number = $this->ipn_data['item_number'];
                    $handling_amount = $this->ipn_data['handling_amount'];
                    $payment_gross = $this->ipn_data['payment_gross'];
                    $shipping = $this->ipn_data['shipping'];
                    $email = '';
                    $first_name = '';
                    $last_name = '';
                    
                    $returnValue = $objOrder->UpdateOrderStatusFromIPN($order_id, $order_state);

                    if($objOrder->SendPaymentAcceptMailForReport($order_id)) {

                    }
                    else {
                        $result = $objUser->GetUserDetailForMail($this->ipn_data['custom']);
                        if(count($result)>0) {
                            $email = $result[0]['UserName'];
                            $first_name = $result[0]['FirstName'];
                            $last_name = $result[0]['LastName'];
                        }
                        $sender = "astrowow-team@astrowow.com";
                        $to = $email;
                        // TODO :: for now we send mail derectly, letter on it will fired from generic mail class
                        $subject = "Your Payment of order".$data['order_id']." accepted";
                        $body =  "Dear $first_name<br />";
                        $body =  "Payment Accepted <br />";
                        $body =  "Item No:".$item_number . '<br />';
                        $body =  "Item Name:".$item_name . '<br />';
                        $body =  "Discount : ".$discount . '<br />';
                        $body =  "Shipping Charges :".$shipping . '<br />';
                        $body =  "Total Payment : ".$payment_gross . '<br />';

                        //Email to User
                        mail($to, $subject, $body);
                        mail('parmaramit1111@gmail.com', $subject, $body);
                    }
                }
                else if( $order_state == 3) {
                    $objOrder = new Order();
                    $returnValue = $objOrder->UpdateOrderStatusFromIPN($order_id, $order_state);
                }
            }
            catch(Exception $ex) {
                mail("parmaramit1111@gmail.com", "Error While ", $ex->getMessage());
            }

//            $extra = ROOTPATH.'bal/order.php?';
//            $extra = '../../bal/order.php?';
//            echo "<html>".
//                    "<body onLoad=\"document.forms['user_form'].submit();\">".
//                    "<form method=\"post\" name=\"user_form\" action=\"".$extra."\">";
//
//            echo "<input type=\"hidden\" name=\"order_id\" value=\"".$this->ipn_data['invoice']."\"/>";
//            echo "<input type=\"hidden\" name=\"order_status\" value=\"".$order_state."\"/>";
//            echo "<input type=\"hidden\" name=\"transaction_id\" value=\"".$this->ipn_data['txn_id']."\"/>";
//            echo "<input type=\"hidden\" name=\"task\" value=\"UpdateOrderStatusFromIPN\"/>";
//            echo "<input type=\"hidden\" name=\"user_id\" value=\"".$this->ipn_data['custom']."\"/>";
//
//            echo "<input type=\"hidden\" name=\"user_id\" value=\"".$this->ipn_data['item_name']."\"/>";
//            echo "<input type=\"hidden\" name=\"user_id\" value=\"".$this->ipn_data['discount']."\"/>";
//            echo "<input type=\"hidden\" name=\"user_id\" value=\"".$this->ipn_data['item_number']."\"/>";
//            echo "<input type=\"hidden\" name=\"user_id\" value=\"".$this->ipn_data['handling_amount']."\"/>";
//            echo "<input type=\"hidden\" name=\"user_id\" value=\"".$this->ipn_data['payment_gross']."\"/>";
//            echo "<input type=\"hidden\" name=\"user_id\" value=\"".$this->ipn_data['shipping']."\"/>";
//
//            echo 		  "</form>".			  "</body>".		  "</html>";
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

    /*
	 * get the order context based on the order id
    */
    function getOrderContext( $orderId ) {

        $order = new Order;
        $order->get( $orderId );
        $this->add_field('item_number', $order->orderId );
        $this->add_field('invoice', $order->orderId );

        // identify the product
        $product = new Product();
        $product->get( $order->productId );

        // identify the currency
        $currency = new Currency();
        $currency->get( $order->currency );
        $this->add_field('currency_code', $currency->abbrev);

        // get the pricing context
        $connection = Database::Connect();
        $pog_query =
                "select price,handling,tax".
                " from `pricing`"          .
                " where portalid="         . $order->portalId .
                " and   productid="        . $order->productId .
                " and   currencyid="       . $order->currency .
                " and   deliveryoptionid=" . $order->delivery_option
        ;

        $cursor = Database::Reader($pog_query, $connection);
        $row = Database::Read($cursor);
        $order->value = floatval($row['price']) + floatval($row['handling']);
        /* update order value */
        $order->save();

        // Tax is added to the total so we need to calculate this if we use it
        // $this->add_field('tax', $row['tax']);

// Replaced product description with delivery description
//		$this->add_field('item_name', $product->description );
        switch( $order->delivery_option ) {
            case 1: // email
                $this->add_field('item_name', $this->email_product_description);
                break;
            case 2: // post
                $this->add_field('item_name', $this->printed_product_description);
                break;
        }
        $this->add_field('amount', $order->value);

//		$this->add_field('on0', 'Delivery');
//		switch( $order->delivery_option ) {
//			case 1: // email
//				$this->add_field('os0', $this->email_product_description);
//				break;
//			case 2: // post
//				$this->add_field('os0', $this->printed_product_description);
//				break;
//			default:
//				break;
//		}
        // debug only
        //$this->add_field('on0', 'SQL');
        //$this->add_field('os0', $pog_query);
    }

};
