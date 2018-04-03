<?php
	require_once ( ROOTPATH .'helper/paypal/paypal-config.php');
        require_once ( ROOTPATH .'helper/nets/nets.post.class.php');
	
	$MerChantNumber = "6098967";
	$Amount = $Items["Price"];
	
	if(isset($_POST["hdnShipping"])) {
		$Amount = $Amount + floatval($_POST["hdnShipping"]);
	}
	//$Amount = FormatCurrency($Amount, $Items["Currency"]);
	$Amount = FormatCurrency($Amount, "DKK");
	
	$OrderID = $Items["OrderID"];
	$CurrencyID = $currencyCodeNo;  /* DKK 208 | EUR 978 | USD 840 */
	
	/** $TestMode = 1; TEST MODE **/
	$TestMode = 0; /** LIVE MODE **/
	
	if($Items["ddProductType"] == 3) {	
		$OKURL = SUCCESS_URL_COMBO_CD_1;		/*  "http://wpclone.astrowow.com/thank-you.php";   */
	} else {
		$OKURL = SUCCESS_URL_COMBO_RS_1;
	}
	$FAILURL = "http://" . $_SERVER['SERVER_NAME']. "/oops-error.php"; 	/* 	"http://wpclone.astrowow.com/oops-error.php";  */
	
	$Port = 8080;
	$ReferenceText = $Items["ReferenceText"];
	/**
		Can take the following values: 
		0 = Normal credit card (Default)
		1 = eDankort
		2 = 3D Secure
		10 = eWire
	*/
	$PayType = 0; 
	$PostingID = "eDankort";
	$Checksum  = md5(sprintf("%s+%s+%s+%s", $OrderID, $Amount, 'Key', $CurrencyID));
	
	/**
	 * 1 : Means that made immediate withdrawal of the transaction immediately after authorization.
	 * 0 : Mannual Confirm the payment.
	 * @var unknown_type
	 */
	$InstantCapture = 1;
	
	/**
		The ability to send multiple parameters for which types of cards can be used. See list of card types: 3.3 Card Types
		SecureCapture.asp called with the following control of card types: 
		https://pay.dandomain.dk/SecureCapture.asp?CardTypeID=1,2,3,4 
		Here limited payment to the types: Visa, Mastercard and Visa / Dankort 
	*/ 
	$CardTypeID = 0;
	$CardNumber = $PostDict["CardNumber"];
	$CardCVC = $PostDict["CardCVC"];
	$ExpireMonth = $PostDict["ExpireMonth"];
	$ExpireYear = $PostDict["ExpireYear"];
?>
<html>
	<head></head>
	<body>
		<form method="post" action="https://pay.dandomain.dk/securecapture.asp" name="Form" autocomplete="off">
			<strong>Please wait... we are processing your payment... Please don't close your window.</strong><br />		
			<input type="hidden" name="MerChantNumber" value="<?php echo $MerChantNumber; ?>" /><br />
			<input type="hidden" name="Amount" value="<?php echo $Amount; ?>" />	<br />
			<input type="hidden" name="OrderID" value="<?php echo $OrderID; ?>" />	<br />
			<input type="hidden" name="CurrencyID" value="<?php echo $CurrencyID; ?>" />	<br />
			<input type="hidden" name="TestMode" value="<?php echo $TestMode; ?>" /><br />
			<input type="hidden" name="OKURL" value="<?php echo $OKURL; ?>" /><br />
			<input type="hidden" name="FAILURL" value="<?php echo $FAILURL; ?>" />	<br />
			<input type="hidden" name="Port" value="<?php echo $Port; ?>"><br />
			
			<input type="hidden" name="ReferenceText" value="<?php echo $ReferenceText; ?>" /><br />
			<input type="hidden" name="PayType" value="<?php echo $PayType; ?>" /><br />
			<input type="hidden" name="PostingID" value="<?php echo $PostingID; ?>" /><br />
			<input type="hidden" name="Checksum" value="<?php echo $Checksum; ?>" /><br />
			<input type="hidden" name="InstantCapture" value="<?php echo $InstantCapture; ?>" /><br />
			
			<input type="hidden" name="CardTypeID" value="<?php echo $CardTypeID; ?>" />	<br />
			<input type="hidden" name="CardNumber" value="<?php echo $CardNumber; ?>" /><br />
			<input type="hidden" name="CardCVC" value="<?php echo $CardCVC; ?>" /><br />
			<input type="hidden" name="ExpireMonth" value="<?php echo $ExpireMonth; ?>" /><br />
			<input type="hidden" name="ExpireYear" value="<?php echo $ExpireYear; ?>" /><br />
			<!--<button type="submit"  value="Approve payment">Approve payment</button>--> 
		</form>
		
		<script type="text/javascript">
			document.forms["Form"].submit();
		</script>
	</body>
</html>