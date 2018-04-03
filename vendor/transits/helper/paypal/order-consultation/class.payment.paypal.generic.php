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



class GenericPaypalTransaction extends paypal_class {

    private	$email_product_description;
    private	$printed_product_description;

    function GenericPaypalTransaction() {

        global $_SERVER;

        $this->paypal_class();
        //$this->paypal_url = 'https://www.paypal.com/cgi-bin/webscr';
        $this->paypal_url = PAYAPL_URL;

        /* set business owner */
        //$this->add_field('business', 'dhruv_1350561627_biz@gmail.com');
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
        $this->add_field('item_name', $product_name);
        //$this->add_field('item_price', 25);
        $this->add_field('amount', $product_price);

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


        if($product_discount > 0) {
            $this->add_field('discount_rate', $product_discount);
        }

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


            $subject = 'Paypal IPN Gateway - Payment Received';
            $to = $this->admin_email;
            $body =  "An instant payment notification was successfully received\n";
            $body .= "from ".$this->ipn_data['payer_email']." on ".date('m/d/Y');
            $body .= " at ".date('g:i A')."\n\nDetails:\n";

            foreach ($this->ipn_data as $key => $value) {
                $body .= "\n$key: $value";
            }
            mail($to, $subject, $body);

            mail($to,  strtolower( $this->ipn_data['payment_status']),  strtolower( $this->ipn_data['payment_status']));

            switch( strtolower( $this->ipn_data['payment_status']) ) {
                case  strtolower('Completed'):
                    $order_state = 2;
                    break;
                case  strtolower('Refunded'):
                    $order_state = 3;
                    break;
                default:
                    $order_state = 1;
                    break;
            }
            //$extra = ROOTPATH.'bal/order.php?';
            $extra = ORDER_URL.'?';

//            require_once(ORDER_URL);
//            require_once(GOLDEN_CIRCLE_URL);
//            require_once(USER_URL);

            $objOrder = new Order();
            $objSubscription = new Subscription();
            $objUser = new User();
			
			// SAVE IPN DETAIL INTO TRANSACTION TABLE
			
			$transactionData = new ArrayObject();
			$transactionData->PaymentStatus 		= $this->ipn_data['payment_status'];
			$transactionData->PaymentDate 			= $this->ipn_data['payment_date'];
			$transactionData->PaymentDetail 		= $this->ipn_data['payment_status'];
			$transactionData->OrderID 				= $this->ipn_data['invoice'];
			$transactionData->FullName 				= $this->ipn_data['first_name']." ".$this->ipn_data['last_name'];
			$transactionData->PayerEmail 			= $this->ipn_data['payer_email'];
			$transactionData->Amount 				= $this->ipn_data['payment_gross'];
			$transactionData->CurrencyCode 			= $this->ipn_data['mc_currency'];
			$transactionData->PaypalTxnID 			= $this->ipn_data['txn_id'];
						
			try
			{
				$returnValue = $objOrder->SaveOrderTransactionDetail($transactionData);
			}
			catch(Exception $ex)
			{
				
			}

            if($order_state == 2) 
			{         
                    $subject = 'Order Cosulation Place ';
                    $body = 'Order Cosulation Place ';
                                      
                    $to = ADRIAN_SIR_EMAIL;
					
                    mail($to, $subject, $body);                
            }
            else if($order_state == 3) {}
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
