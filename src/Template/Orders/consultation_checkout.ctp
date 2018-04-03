<?php use Cake\Routing\Router; ?>
<?php use Cake\I18n\I18n;?>

<?php
	$locale       = strtolower( substr(I18n::locale(), 0, 2) );
	if(isset($order) && !empty($order))
	{
	  ?>
	  <div class="active">
	  <script charset="UTF-8" src="https://ssl.ditonlinebetalingssystem.dk/integration/ewindow/paymentwindow.js" type="text/javascript"></script>
	    <!-- <script charset="UTF-8" src="./Checkout Step 4 _ AstroWow_files/paymentwindow.js" type="text/javascript">
	    </script> -->
	    <div id="payment-div">
	      <?php 
	      $base_path   = Router::url('/', true);
	      

	      $acceptUrl   = $base_path."orders/consultation-thank-you?utm_nooverride=1";
	      $callback    = $base_path."orders/constulation-thank-you?utm_nooverride=1";
	      
	      if( $locale == 'da' )
	      {
	      	$acceptUrl   = $base_path."dk/ordrer/konsultation-tak?utm_nooverride=1";
	        $callback    = $base_path."dk/ordrer/konsultation-tak?utm_nooverride=1";
	      }


	      if(isset($order['cancel_url']))
	      {
	       $cancel      = $order['cancel_url'];
	      }
	      else
	      {
           $cancel     = $order['url']; 
	      }

	      $currency    = $order['currencyCode'];
	      $orderId     = $order['order_id'];
	      $price = $this->Custom->getEpayPriceFormat($order['price']);
	      ?>
	      <?php
	     // $params = array('merchantnumber' => "8023058", 'amount' => $price, 'currency' => $currency, 'instantcallback' => 1, 'orderid' => $orderId, 'ownreceipt' => 1, 'windowstate'=>3, 'accepturl'=>$acceptUrl, 'cancelurl' => $cancel, 'iframeheight' => 580);
	      $params = array('merchantnumber' => MERCHANT_ID, 'amount' => $price, 'currency' => $currency, 'instantcallback' => 1, 'orderid' => $orderId, 'ownreceipt' => 1, 'windowstate'=>3, 'accepturl'=>$acceptUrl, 'cancelurl' => $cancel, 'iframeheight' => 580);

		 
	      ?>
	      <script charset="UTF-8" src="https://ssl.ditonlinebetalingssystem.dk/integration/ewindow/paymentwindow.js" type="text/javascript">
	      </script>
	      <div id="payment-div"></div>
	      <script type="text/javascript">
	        paymentwindow = new PaymentWindow({
	         <?php
	         foreach ($params as $key => $value)
	         {
	           echo "'" . $key . "': \"" . $value . "\",\n";
	         }
	         ?>
	         'hash': "<?php echo md5(implode("", array_values($params)) . "AstrowowNethues"); ?>",
	       });
	        paymentwindow.append('payment-div');
	        paymentwindow.open();
	      </script>
	    </div>

	    <?php 
	  }
  ?>
