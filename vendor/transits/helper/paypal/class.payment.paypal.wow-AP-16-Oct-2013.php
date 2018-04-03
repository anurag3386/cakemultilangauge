<?php
/**
 * Script: class.payment.paypal.wow.php
 * Author: Andy Gray
 *
 * Description
 * Extension of PayPal IPN generic class for Datastar
 *
 * Modification History
 * - initial spike
 */

class WOWPaypalTransaction extends GenericPaypalTransaction {

    function WOWPaypalTransaction() {

        /* call parent constructor */
        $this->GenericPaypalTransaction();

        /* set business owner */
       // $this->add_field('business', 'ard@world-of-wisdom.com');
        //$this->add_field('business', 'dhruv_1350561627_biz@gmail.com');
        //$this->add_field('business', 'abhi_p_1352295643_biz@yahoo.co.in');
		$this->add_field('business', BUSINESS_PAYPAL_ID);

        /* order detail */
        $this->add_field('item_number', md5('world-of-wisdom.com'));

        /* locale */
        $this->add_field('lc','EN');
    }

    /*
   * Send the transaction to Paypal
   * This is a hidden form
   * Perhaps make this a splash screen and hide the form itself as it could be inspected through latency
    */
    function send() {
        echo
        "<html>".
                "<body onLoad=\"document.forms['paypal_form'].submit();\">".
                //"<body>".
                "<form method=\"post\" name=\"paypal_form\" action=\"".$this->paypal_url."\">";
        foreach ($this->fields as $name => $value) {
        	if($name == "item_name"){
            	echo '<input type="hidden" name="'.$name.'" value="'. trim(stripslashes(html_entity_decode(utf8_decode(urldecode($value))))) .'"/>';
        		//echo "<input type=\"hidden\" name=\"$name\" value=\"".trim(stripslashes(html_entity_decode(utf8_decode(utf8_encode(urldecode($value))))))."\"/>";
        	} else {
        		echo "<input type=\"hidden\" name=\"$name\" value=\"$value\"/>";
        	}        	
            //echo sprintf("<br />FieldName : %s  =  %s", $name, $value);
        }
        //echo '<br /><input type="submit" id="btnSubmit" value="Submit">';
        echo "</form>
            </body>
            </html>";

    }
} /*
 end of class */;

?>
