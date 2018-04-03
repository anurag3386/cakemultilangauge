<?php
  /**
   * Email template for Mystic Meg
   */

  /* create the email dispatcher */
$logger->debug('processing mail delivery');
$swift =& new Swift(
		    new Swift_Connection_Sendmail()
		    );
			
/* add the subject line */
/* TODO - check the wording here - Your Astrological Report from Mystic Meg */
$message =& new Swift_Message(
			      /* $config['mail'][strtolower($reportoptionitem->language)]['subject'] */
			      'Your Astrological Report from Michelle Knight'
			      );

/* message body */
$message_part = '
<style>
  body {
    font-family: arial;
    font-size: 10pt;
  }
</style>

<p style="font-family: arial; font-size: 10pt;">
   Hi, and thank you for placing your order with Michelle Knight.
</p>

<p style="font-family: arial; font-size: 10pt;">
Your <strong>Personal Astrological Report</strong> has been prepared for you by World of Wisdom, and is attached to this email.
If you are unsure whether to open attachments, and we do fully understand such concerns, you can download your report by clicking
<a href="http://world-of-wisdom.com/06_affiliates/wsapi/download.php?id='.$orderitem->orderId.'">here</a>
or entering http://world-of-wisdom.com/06_affiliates/wsapi/download.php?id='.$orderitem->orderId.' in your web browser.
</p>

<p style="font-family: arial; font-size: 10pt;">
We hope that you find your report both enlightening and insightful.
</p>

<p style="font-family: arial; font-size: 10pt;"><strong>World of Wisdom</strong> on behalf of Michelle Knight<br />
email: <a href="mailto:reports@world-of-wisdom.com">reports@world-of-wisdom.com</a><br />
tel: +45.3314.5555
</p>
'
    ;

/* attach the HTML content */
$message->attach(
		 new Swift_Message_Part($message_part,
					"text/html"
					)
		 );
			
/* attach the report */
$message->attach(
		 new Swift_Message_Attachment(
					      file_get_contents(
								sprintf("%s/%d.bundle.pdf",		/* path to physical report	*/
									SPOOLPATH,			/* path to spool area		*/
									$orderitem->orderId		/* order id			*/
									)
								),
					      sprintf("horoscope_%06d.pdf", $orderitem->orderId),	/* name of attachment		*/
					      "application/pdf"
					      )
		 );
			
/* return path / reply-to */
$message->setReturnPath(
			new Swift_Address("replies@world-of-wisdom.com")
			);
			
/* send the message */
if( $swift->send(
		 $message,
		 new Swift_Address($reportoptionitem->GetEmailAddress()->address),
		 new Swift_Address("enquiries@world-of-wisdom.com", "World of Wisdom")
		 ) === false ) {
  
  $logger->debug("failed to send email");
				
  /* update order */
  $orderitem->state = $states['orphaned'];
  $orderitem->save();
		
  /* update transactions */
  $transaction = new Transaction();
  $transaction->orderId = $orderitem->orderId;
  $transaction->state = $orderitem->state;
  $transaction->timestamp = strftime("%Y-%m-%d %H:%M:%S");
  $transaction->save();

 } else {
				
  $logger->debug("mail sent");
				
  /* update order */
  $orderitem->state = $states['dispatched'];
  $orderitem->save();
		
  /* update transactions */
  $transaction = new Transaction();
  $transaction->orderId = $orderitem->orderId;
  $transaction->state = $orderitem->state;
  $transaction->timestamp = strftime("%Y-%m-%d %H:%M:%S");
  $transaction->save();
				
  /* update order */
  $orderitem->state = $states['closed'];
  $orderitem->save();
		
  /* update transactions */
  $transaction = new Transaction();
  $transaction->orderId = $orderitem->orderId;
  $transaction->state = $orderitem->state;
  $transaction->timestamp = strftime("%Y-%m-%d %H:%M:%S");
  $transaction->save();
 }

?>
