<?php
error_reporting ( E_ALL );
require_once(BALPATH."/product.php");

if(!class_exists('ACSStatelist')) {
	//echo 'class not exist';
	require_once(ROOTPATH."helper/acs/ACSStateList.php");
}

include(LIBRARYPATH."/swift/swift_required.php");

if (!defined('EMAIL_IMAGE_PATH')) {
	define("EMAIL_IMAGE_PATH", BASEURL.'emailImages');
}

if (!defined('WELLCOME_EMAIL_TEMPLATE_ID')) {
	define("WELLCOME_EMAIL_TEMPLATE_ID", 1);
}

if (!defined('WELLCOME_ELITE_EMAIL_TEMPLATE_ID')) {
	define("WELLCOME_ELITE_EMAIL_TEMPLATE_ID", 19);
}

if (!defined('DAILY_SUNSIGN_EMAIL_TEMPLATE_ID')) {
	define("DAILY_SUNSIGN_EMAIL_TEMPLATE_ID", 2);
}

if (!defined('RESET_PASSWORD_EMAIL_TEMPLATE_ID')) {
	define("RESET_PASSWORD_EMAIL_TEMPLATE_ID", 3);
}

if (!defined('SEND_PAYMENT_ACCEPT_MAIL_FOR_REPORT')) {
	define("SEND_PAYMENT_ACCEPT_MAIL_FOR_REPORT", 4);
}

if (!defined('GC_ASTROLOGER_QUESTION_ALERT_EMAIL_TEMPLATE_ID')) {
	define("GC_ASTROLOGER_QUESTION_ALERT_EMAIL_TEMPLATE_ID", 5);
}

if (!defined('GC_ASTROLOGER_QUESTION_ALERT_EMAIL_TEMPLATE_ID')) {
	define("GC_ASTROLOGER_QUESTION_ALERT_EMAIL_TEMPLATE_ID", 5);
}

if (!defined('GC_USER_ANSWER_ALERT_EMAIL_TEMPLATE_ID')) {
	define("GC_USER_ANSWER_ALERT_EMAIL_TEMPLATE_ID", 6);
}

if (!defined('WEEKLY_SUNSIGN_EMAIL_TEMPLATE_ID')) {
	define("WEEKLY_SUNSIGN_EMAIL_TEMPLATE_ID", 7);
}

if (!defined('MONTHLY_SUNSIGN_EMAIL_TEMPLATE_ID')) {
	define("MONTHLY_SUNSIGN_EMAIL_TEMPLATE_ID", 8);
}

if (!defined('FREE_SOFTWARE_DOWNLOAD_TEMPLATE')) {
	define("FREE_SOFTWARE_DOWNLOAD_TEMPLATE", 9);
}

if (!defined('REGISTERED_SHAREWARE_EMAIL_TEMPLATE')) {
	define("REGISTERED_SHAREWARE_EMAIL_TEMPLATE", 10);
}

if (!defined('BUY_SOFTWARE_CD_EMAIL_TEMPLATE')) {
	define("BUY_SOFTWARE_CD_EMAIL_TEMPLATE", 11);
}

if (!defined('SOFTWARE_CD_INSTRUCTION_EMAIL_TEMPLATE')) {
	define("SOFTWARE_CD_INSTRUCTION_EMAIL_TEMPLATE", 12);
}

if (!defined('SEND_PAYMENT_ACCEPT_MAIL_FOR_PRINTED_REPORT')) {
	define("SEND_PAYMENT_ACCEPT_MAIL_FOR_PRINTED_REPORT", 13);
}

if (!defined('SEND_PAYMENT_ACCEPT_MAIL_FOR_LOVER_REPORT')) {
	define("SEND_PAYMENT_ACCEPT_MAIL_FOR_LOVER_REPORT", 14);
}

if (!defined('SEND_INVITATION_TO_FRIENDS')) {
	define("SEND_INVITATION_TO_FRIENDS", 15);
}

if (!defined('SEND_PAYMENT_ACCEPT_MAIL_3_MONTH_CALENDAR')) {
	define("SEND_PAYMENT_ACCEPT_MAIL_3_MONTH_CALENDAR", 16);
}

if (!defined('SEND_SHAREWARE_DOWNLOAD_LINK')) {
	define("SEND_SHAREWARE_DOWNLOAD_LINK", 17);
}

if (!defined('SEND_FEEDBACK_THANK_YOU_EMAIL')) {
	define("SEND_FEEDBACK_THANK_YOU_EMAIL", 18);
}

if (!defined('ELITE_PAYMENT_CONFIRMATION_EMAIL_TEMPLATE_ID')) {
	define("ELITE_PAYMENT_CONFIRMATION_EMAIL_TEMPLATE_ID", 20);
}

//MAIL SETTINGs
if (!defined('SMTP_SERVER_NAME')) {
	define("SMTP_SERVER_NAME", 'mail.astrowow.com');
}

if (!defined('SMTP_PORT_NO')) {
	define("SMTP_PORT_NO", 25);
}


if (!defined('SMTP_SERVER_USERNAME')) {
	define("SMTP_SERVER_USERNAME", 'services@astrowow.com');
}

if (!defined('SMTP_SERVER_PASSWORD')) {
	define("SMTP_SERVER_PASSWORD", 'ard6969ag');
}

class genericMail {

	function sendmail( $sender, $recipient, $data ) {
		$to = '';
		if(count($recipient)>0) {
			$to = $recipient['to'];
		}

		$subject = $data['subject'];
		$body = $data['mailtext'];

		//$headers  = 'MIME-Version: 1.0' . "\r\n";
		//$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

		$file = ROOTPATH."/sample/celebrity/horoscope/personal/sample-en.php";

		//return mail($to, $subject, $message, $headers);


		// Create the Transport
		//dhruv.sarvaiya@n-techcorporate.com
		//
		$transport = Swift_SmtpTransport::newInstance(SMTP_SERVER_NAME, SMTP_PORT_NO)
		->setUsername(SMTP_SERVER_USERNAME)
		->setPassword(SMTP_SERVER_PASSWORD);


		// Create the Mailer using your created Transport
		$mailer = Swift_Mailer::newInstance($transport);

		$message = Swift_Message::newInstance()
		->setSubject($data['subject'])
		->setFrom(array("no-reply@astrowow.com" => "Astrowow-Team"))
		->setTo($recipient['to'])
		->setBody($body)
		->addPart($body, 'text/html')
		->attach(Swift_Attachment::fromPath($file));


		// Send the message
		return $result = $mailer->send($message);
	}

	function SendSoftwareBuyEmail($sender,$recipient, $data ) {
		$email_line_1 = '<img title="Astrowow.com" alt="Astrowow.com" src="'.BASEURL.'/images/logo.png">';

		$email_line_1 .= '<p>Thank you for placing your order with ##SITENAME !</p> ';
		$email_line_2 = '<p>New Order Placed with ##SITENAME !</p> ';

		$str = '';

		//$str .='<p>Thank you for placing your order with ##SITENAME !</p> ';

		$str .='<p>This email is to confirm your recent order- ##ORDERID</p> ';
		$str .='<p>Date ##ORDERDATE</p>';

		if($data['product_type'] == 3) // Buy CD
		{

			$str .='<p><b>Shipping address</b><br /> ';
			$str .='##FIRSTNAME ##LASTNAME  <br /> ';
			$str .='##EMAIL <br /> ';
			$str .='##ADDRESS1 <br /> ';
			$str .='##ADDRESS2 ';
			$str .='##CITY <br /> ';
			$str .='##STATE <br /> ';
			$str .='##COUNTRY <br /> ';
			$str .='##POSTALCODE <br /> ';
			$str .='Ph. ##TELEPHONE </p><br />';

		}
		/*{% if requires_shipping %}
		 <p><b>Shipping address</b><br />
		{{ shipping_address.name }}<br />
		{{ shipping_address.street }}<br />
		{{ shipping_address.city }}<br />
		{{ shipping_address.province }}
		{{ shipping_address.zip }}<br />
		{{ shipping_address.country }}</p>
		{% endif %}
		*/
		/*
		 <p><b>Billing address</b><br />
		{{ billing_address.name }}<br />
		{{ billing_address.street }}<br />
		{{ billing_address.city }}<br />
		{{ billing_address.province }}
		{{ billing_address.zip }}<br />
		{{ billing_address.country }}</p> */

		//$str .='<ul style="list-style-type:none"> ';
		//$str .='<li>';
		//$str .= '<img title="Astrowow.com" alt="Astrowow.com" src="http://localhost/astrowow/images/img-astrology-software.jpg"><br />';
		$str .='Quantity : 1 &nbsp;<br />';
		$str .='Product Name : ##PRODUCTNAME &nbsp;<br />';
		$str .='Product Type : ';
		if($data['product_type'] == 3) // Buy CD
		{
			$str .='Buy CD <br/>';
		}
		else {
			$str .='Register Shareware <br/>';
		}
		$str .='Price : ##PRODUCTPRICE &nbsp;<br />';
		$str .='Language : ##LANGUAGE &nbsp;<br />';
		$str .='</li>';
		$str .='</ul>';
		$str .='<p>Discounts : ##DISCOUNT</p>';
		$str .='<p>Subtotal : ##SUBTOTAL</p>';

		$str .='<br /><br /><br /><b>With Warm Regards, World of Wisdom</b>';


		$email_line_1 = str_replace("##SITENAME",'Astrowow.com',$email_line_1);
		$email_line_2 = str_replace("##SITENAME",'Astrowow.com',$email_line_2);
		$str = str_replace("##ORDERID",$data['order_id'],$str);
		$str = str_replace("##ORDERDATE",$data['order_date'],$str);
		$str = str_replace("##PRODUCTNAME",$data['product_name'],$str);
		$str = str_replace("##PRODUCTPRICE",$data['product_price'],$str);
		$str = str_replace("##DISCOUNT",$data['discount'],$str);
		$str = str_replace("##SUBTOTAL",$data['subtotal'],$str);
		$str = str_replace("##LANGUAGE",$data['language'],$str);

		if($data['product_type'] == 3) // Buy CD
		{
			$str = str_replace("##FIRSTNAME",$data['first_name'],$str);
			$str = str_replace("##LASTNAME",$data['last_name'],$str);
			$str = str_replace("##EMAIL",$data['email'],$str);
			$str = str_replace("##ADDRESS1",$data['address_1'],$str);
			$str = str_replace("##ADDRESS2",$data['address_2'],$str);
			$str = str_replace("##CITY",$data['city'],$str);
			$str = str_replace("##STATE",$data['state'],$str);
			$str = str_replace("##COUNTRY",$data['country'],$str);
			$str = str_replace("##POSTALCODE",$data['postal_code'],$str);
			$str = str_replace("##TELEPHONE",$data['telephone'],$str);
		}

		$to = $recipient;
		$adminEmail = 'ntech.corporate@gmail.com';

		$subject = $data['subject'];
		$body = $data['mailtext'].$email_line_1.$str;

		//        $transport = Swift_SmtpTransport::newInstance('aspmx.l.google.com', 25)
		//                ->setUsername('')
		//                ->setPassword('');
		$transport = Swift_SmtpTransport::newInstance(SMTP_SERVER_NAME, SMTP_PORT_NO)
		->setUsername(SMTP_SERVER_USERNAME)
		->setPassword(SMTP_SERVER_PASSWORD);

		$mailer = Swift_Mailer::newInstance($transport);

		$message = Swift_Message::newInstance()
		->setSubject($data['subject'])
		->setFrom(array("no-reply@astrowow.com" => "Astrowow-Team"))
		->setTo($to)
		->setBcc($adminEmail)
		->setBody($body)
		->addPart($body, 'text/html');

		//$message->addBcc('parmaramit1111@gmail.com', 'Amit Parmar');

		// Send the message
		return $result = $mailer->send($message);
	}

	function SendReportBuyEmail($sender,$recipient, $data ) {
		$str = '<img title="Astrowow.com" alt="Astrowow.com" src="http://localhost/astrowow/images/logo.png">';

		$str .='<p>Thank you for placing your order with ##SITENAME !</p> ';
		$str .='<p>This email is to confirm your recent order.</p> ';
		$str .='<p>Date ##ORDERDATE</p>';

		/*{% if requires_shipping %}
		 <p><b>Shipping address</b><br />
		{{ shipping_address.name }}<br />
		{{ shipping_address.street }}<br />
		{{ shipping_address.city }}<br />
		{{ shipping_address.province }}
		{{ shipping_address.zip }}<br />
		{{ shipping_address.country }}</p>
		{% endif %}
		*/
		/*
		 <p><b>Billing address</b><br />
		{{ billing_address.name }}<br />
		{{ billing_address.street }}<br />
		{{ billing_address.city }}<br />
		{{ billing_address.province }}
		{{ billing_address.zip }}<br />
		{{ billing_address.country }}</p> */

		$str .='<ul style="list-style-type:none"> ';
		$str .='<li>';
		$str .= '<img title="Astrowow.com" alt="Astrowow.com" src="http://localhost/astrowow/images/img-astrology-software.jpg">';
		$str .='1 &nbsp;';
		$str .='##PRODUCTNAME &nbsp;';
		$str .='##PRODUCTPRICE &nbsp;';
		$str .='</li>';
		$str .='</ul>';
		$str .='<p>Discounts : ##DISCOUNT';
		$str .='<p>Subtotal : ##SUBTOTAL';


		$str = str_replace("##SITENAME","Astrowow.com",$str);
		$str = str_replace("##ORDERDATE",$data['order_date'],$str);
		$str = str_replace("##PRODUCTNAME",$data['product_name'],$str);
		$str = str_replace("##PRODUCTPRICE",$data['product_price'],$str);
		$str = str_replace("##DISCOUNT",$data['discount'],$str);
		$str = str_replace("##SUBTOTAL",$data['subtotal'],$str);

		$to = '';
		if(count($recipient)>0) {
			$to = $recipient['to'];
		}

		$subject = $data['subject'];
		$body = $data['mailtext'].$str;

		//        $transport = Swift_SmtpTransport::newInstance('aspmx.l.google.com', 25)
		//                ->setUsername('')
		//                ->setPassword('');
		$transport = Swift_SmtpTransport::newInstance(SMTP_SERVER_NAME, SMTP_PORT_NO)
		->setUsername(SMTP_SERVER_USERNAME)
		->setPassword(SMTP_SERVER_PASSWORD);

		$mailer = Swift_Mailer::newInstance($transport);

		$message = Swift_Message::newInstance()
		->setSubject($data['subject'])
		->setFrom(array("no-reply@astrowow.com" => "Astrowow-Team"))
		->setTo($recipient['to'])
		->setBody($body)
		->addPart($body, 'text/html');

		//$message->addBcc('parmaramit1111@gmail.com', 'Amit Parmar');

		// Send the message
		return $result = $mailer->send($message);
	}

	function SendSubscriptionBuyEmail($sender,$recipient, $data ) {
		$str  = '';
		$str .='<p>Thank you for placing your order with ##SITENAME !</p> ';
		$str .='<p>This email is to confirm your recent order.</p> ';
		$str .='<p>Date ##ORDERDATE</p>';


		$str .='<ul style="list-style-type:none"> ';
		$str .='<li>';
		$str .='1 &nbsp;';
		$str .='##PRODUCTNAME &nbsp;';
		$str .='##PRODUCTPRICE &nbsp;';
		$str .='</li>';
		$str .='</ul>';
		$str .='<p>Discounts : ##DISCOUNT';
		$str .='<p>Subtotal : ##SUBTOTAL';

		$str = str_replace("##SITENAME", "Astrowow.com",$str);
		$str = str_replace("##ORDERDATE", (isset($data['order_date']) ? $data['order_date'] : date('Y-m-d')), $str);
		$str = str_replace("##PRODUCTNAME", (isset($data['product_name']) ? $data['product_name'] : ""), $str);
		$str = str_replace("##PRODUCTPRICE", (isset($data['product_price']) ? $data['product_price'] : ""), $str);
		$str = str_replace("##DISCOUNT", (isset($data['discount']) ? $data['discount'] : ""), $str);
		$str = str_replace("##SUBTOTAL", (isset($data['subtotal']) ? $data['subtotal'] : ""), $str);

		$to = '';
		if(count($recipient)>0) {
			$to = $recipient['to'];
		}

		$subject = (isset($data['subject']) ?  $data['subject'] : "");
		$body = (isset($data['mailtext']) ? $data['mailtext'] : "") .$str;

		$transport = Swift_SmtpTransport::newInstance(SMTP_SERVER_NAME, SMTP_PORT_NO)
		->setUsername(SMTP_SERVER_USERNAME)
		->setPassword(SMTP_SERVER_PASSWORD);

		$mailer = Swift_Mailer::newInstance($transport);

		$message = Swift_Message::newInstance()
		->setSubject($data['subject'])
		->setFrom(array("no-reply@astrowow.com" => "Astrowow-Team"))
		->setTo($recipient['to'])
		->setBody($body)
		->addPart($body, 'text/html');

		////$message->addBcc('parmaramit1111@gmail.com', 'Amit Parmar');

		// Send the message
		//It's commented because we don't have email template
		return $result = $mailer->send($message);
	}


	function SendDailySunsignMail($sender,$recipient, $data) {

		$language_id = 'en';
		$locale = 'en-US';
		$default_locale = 'en-US';
		$FACEBOOK 		= 'http://www.facebook.com/MyAstropage?ref=hl';
		$TWEETER 		= 'https://twitter.com/AdrianDuncan8';
		$GOOGLEPLUS     = 'https://plus.google.com/106143888045600794106/posts';
		$YOUTUBE 		= 'http://www.youtube.com/watch?v=Ze8VwNle15I&feature=results_video';

		if(isset($data['language_id'])) {
			$language_id = $data['language_id'];
		}
		if(isset($data['locale'])) {
			$locale = $data['locale'];
		}

		$objEmailTemplate = new emailTemplate();
		$result = $objEmailTemplate->GetEmailTemplateById(DAILY_SUNSIGN_EMAIL_TEMPLATE_ID,$language_id);

		// Set the locale:
		setlocale(LC_ALL, $locale);
		$date = strftime('%A %B %d,%G ',time());
		$str = '';
		$subject = '';
		if(count($result)>0) {
			$str = html_entity_decode( utf8_decode($result['content']));
			$subject = ((isset($result['subject']) && trim($result['subject']) != "") ? stripslashes(html_entity_decode( utf8_decode($result['subject']))) : "");
		}
		else {
			$str = $data['mailtext'].'<img title="Astrowow.com" alt="Astrowow.com" src="'.BASEURL.'images/logo.png">';

			$str .='<p>Your Sun Sign Horoscope for '.$date.'</p> ';
			$str .='<p>Now you can sign with below noted username and password</p> ';
			$str .='<p>##SITELINK</p>';
			$str = '';
		}

		if(strlen($str) > 0) {
			$transit_section = '
					<tr>
					<td colspan="2" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#fff; text-align:left; line-height:18px; background-image:url(##BASEURL/title-bg.png); width:415px; height:54px; background-position:left; background-repeat:no-repeat;">
					<table width="98%" border="0" align="left" cellpadding="1" cellspacing="1">
					<tr>
					<td colspan="2" height="27" style="font-family:Arial, Helvetica, sans-serif; font-size:18px; color:#fff; text-align:left;">&nbsp;Daily Personal Horoscope</td>
					</tr>
					<tr><td style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#fff; text-align:left;">&nbsp;Based On Your Birth Planets</td>
					<td style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#fff; text-align:right;">##CURRENT_DATE</td></tr>
					</table>
					</td>
					<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#fff; text-align:left; line-height:18px;">&nbsp;</td>
					</tr>
					<tr>
					<td height="21" style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#eaaf10; text-align:left; line-height:18px;">&nbsp;</td>
					<td style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#eaaf10; text-align:left; line-height:18px;">##DAILY_TRANSIT</td>
					<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#fff; text-align:left; line-height:18px;">&nbsp;</td>
					</tr>
					<tr>
					<td height="29" style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#eaaf10; text-align:left; line-height:18px;">&nbsp;</td>
					<td style="font-family:Arial, Helvetica, sans-serif; font-size:13px; color:#fff; text-align:left; line-height:18px;">##PERSONAL_TRANSIT_DESCRIPTION</td>
					<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#fff; text-align:left; line-height:18px;">&nbsp;</td>
					</tr>
					<tr>
					<td height="29" style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#eaaf10; text-align:left; line-height:18px;">&nbsp;</td>
					<td align="right" valign="bottom" style="font-family:Arial, Helvetica, sans-serif; font-size:13px; color:#fff; text-align:right; line-height:18px;"><a href="##SITELINKmy-astropage.php" style="color:#fff; text-decoration:none;">Read More...</a></td>
					<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#fff; text-align:left; line-height:18px;">&nbsp;</td>
					</tr>';

			//             $strDailyReadMore = '<tr>
			//             <td height="29" style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#eaaf10; text-align:left; line-height:18px;">&nbsp;</td>
			//             <td align="right" valign="bottom" style="font-family:Arial, Helvetica, sans-serif; font-size:13px; color:#fff; text-align:right; line-height:18px;"><a href="##SITELINKmy-astropage.php" style="color:#fff; text-decoration:none;">Read More...</a></td>
			//             <td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#fff; text-align:left; line-height:18px;">&nbsp;</td>
			//           </tr>';
			$strDailyReadMore = '';

			$str = str_replace("##SITELINK",$data['sitelink'],$str);
			$str = str_replace("##USERNAME",$data['username'],$str);
			$str = str_replace("##NAME",$data['name'],$str);
			$str = str_replace("##SUNSIGN",$data['sunsign_name'],$str);

			if(!empty($data['daily_transit'])) {
				$str = str_replace("##DAILY_PERSONAL_BLOCK",$transit_section, $str);
				$str = str_replace("##DAILY_READ_MORE",'', $str);
			}
			else {
				$str = str_replace("##DAILY_PERSONAL_BLOCK",'',$str);
				$str = str_replace("##DAILY_READ_MORE",$strDailyReadMore, $str);
			}

			$str = str_replace("##DAILY_HOROSCOPE",$data['daily_horoscope'],$str);
			$str = str_replace("##DAILY_TRANSIT",$data['daily_transit'],$str);
			//$str = str_replace("##PERSONAL_TRANSIT_DESCRIPTION",substr($data['daily_transit_description'],0,164),$str);
			$str = str_replace("##PERSONAL_TRANSIT_DESCRIPTION", $data['daily_transit_description'], $str);
			$str = str_replace("##HOROSCOPEURL",$data['horoscope_url'],$str);
			$str = str_replace("##HOROSCOPEIMAGE",$data['horoscope_image'],$str);
			$str = str_replace("##FACEBOOK",$FACEBOOK,$str);
			$str = str_replace("##TWEETER",$TWEETER,$str);
			$str = str_replace("##GOOGLEPLUS",$GOOGLEPLUS,$str);
			$str = str_replace("##YOUTUBE",$YOUTUBE,$str);
			$str = str_replace("##SITELINK",$data['sitelink'],$str);
			$str = str_replace("##BASEURL",EMAIL_IMAGE_PATH,$str);
			$str = str_replace("##CURRENT_DATE",$data['CURRENT_DATE'],$str);
			$str = str_replace("##CURRENTDATE2",$data['CURRENTDATE2'],$str);
			//##HOROSCOPEURL
			//##HOROSCOPEIMAGE

			$to = '';
			if(count($recipient)>0) {
				$to = $recipient['to'];
			}
			if( isset($subject) && $subject != ""){
				$subject .= " " .$data['CURRENT_DATE'];
			}else {
				//$subject = 'Your Sun Sign Horoscope for '. $data['CURRENT_DATE'];
				$subject = $data['subject'];
			}

			$body = $str;

			$transport = Swift_SmtpTransport::newInstance(SMTP_SERVER_NAME, SMTP_PORT_NO)
			->setUsername(SMTP_SERVER_USERNAME)
			->setPassword(SMTP_SERVER_PASSWORD);

			$mailer = Swift_Mailer::newInstance($transport);

			$message = Swift_Message::newInstance()
			->setSubject($subject)
			->setFrom(array("no-reply@astrowow.com" => "Astrowow-Team"))
			->setTo($recipient['to'])
			->setBody($body)
			->addPart($body, 'text/html');

			// Send the message
			try {
				$result = $mailer->send($message);
			}
			catch (Exception $ex) {

			}
			return $result;
		}
		return 0;
	}

	function SendComboProductBuyEmail($sender,$recipient, $data ) {
		$str = '<img title="Astrowow.com" alt="Astrowow.com" src="http://localhost/astrowow/images/logo.png">';

		$str .='<p>Thank you for placing your order with ##SITENAME !</p> ';
		$str .='<p>This email is to confirm your recent order.</p> ';
		$str .='<p>Date ##ORDERDATE</p>';

		/*{% if requires_shipping %}
		 <p><b>Shipping address</b><br />
		{{ shipping_address.name }}<br />
		{{ shipping_address.street }}<br />
		{{ shipping_address.city }}<br />
		{{ shipping_address.province }}
		{{ shipping_address.zip }}<br />
		{{ shipping_address.country }}</p>
		{% endif %}
		*/
		/*
		 <p><b>Billing address</b><br />
		{{ billing_address.name }}<br />
		{{ billing_address.street }}<br />
		{{ billing_address.city }}<br />
		{{ billing_address.province }}
		{{ billing_address.zip }}<br />
		{{ billing_address.country }}</p> */

		$str .='<ul style="list-style-type:none"> ';
		$str .='<li>';
		$str .= '<img title="Astrowow.com" alt="Astrowow.com" src="http://localhost/astrowow/images/img-astrology-software.jpg">';
		$str .='1 &nbsp;';
		$str .='##PRODUCTNAME &nbsp;';
		$str .='##PRODUCTPRICE &nbsp;';
		$str .='</li>';
		$str .='</ul>';
		$str .='<p>Discounts : ##DISCOUNT';
		$str .='<p>Subtotal : ##SUBTOTAL';


		$str = str_replace("##SITENAME","Astrowow.com",$str);
		$str = str_replace("##ORDERDATE",$data['order_date'],$str);
		$str = str_replace("##PRODUCTNAME",$data['product_name'],$str);
		$str = str_replace("##PRODUCTPRICE",$data['product_price'],$str);
		$str = str_replace("##DISCOUNT",$data['discount'],$str);
		$str = str_replace("##SUBTOTAL",$data['subtotal'],$str);

		$to = '';
		if(count($recipient)>0) {
			$to = $recipient['to'];
		}

		$subject = $data['subject'];
		$body = $data['mailtext'].$str;


		//        $transport = Swift_SmtpTransport::newInstance('aspmx.l.google.com', 25)
		//                ->setUsername('ntech.n.ntech@gmail.com')
		//                ->setPassword('ntechpassn');

		$transport = Swift_SmtpTransport::newInstance(SMTP_SERVER_NAME, SMTP_PORT_NO)
		->setUsername(SMTP_SERVER_USERNAME)
		->setPassword(SMTP_SERVER_PASSWORD);

		$mailer = Swift_Mailer::newInstance($transport);

		$message = Swift_Message::newInstance()
		->setSubject($subject)
		->setFrom(array("no-reply@astrowow.com" => "Astrowow-Team"))
		->setTo($recipient['to'])
		->setBody($body)
		->addPart($body, 'text/html');

		//$message->addBcc('parmaramit1111@gmail.com', 'Amit Parmar');

		// Send the message
		return $result = $mailer->send($message);
	}

	function SendWellComeEmailOnSignup($sender,$recipient, $data) {
		$language_id = 'en';
		if(isset($data['sitelink'])) {
			$language_id = $data['language_id'];
		}

		$objEmailTemplate = new emailTemplate();
		$result = $objEmailTemplate->GetEmailTemplateById(WELLCOME_EMAIL_TEMPLATE_ID,$language_id);
		//print_r($data);
		if(count($result)>0) {
			$str = html_entity_decode($result['content']);
		}
		else {
			$str = $data['mailtext'].'<img title="Astrowow.com" alt="Astrowow.com" src="'.BASEURL.'images/logo.png">';

			$str .='<p>Wellcome and Thank you for signup with Astrowow.com !</p> ';
			$str .='<p>Now you can sign with below noted username and password</p> ';
			$str .='<p>Username: ##USERNAME</p>';
			$str .='<p>Password: ##PASSWORD</p>';
			$str .='<p>##SITELINK</p>';

		}
		$str = str_replace("##SITELINK",$data['sitelink'],$str);
		$str = str_replace("##USERNAME",$data['username'],$str);
		$str = str_replace("##PASSWORD",$data['password'],$str);
		$str = str_replace("##NAME",$data['name'],$str);
		$str = str_replace("##BASEURL",EMAIL_IMAGE_PATH,$str);


		$to = '';
		if(count($recipient)>0) {
			$to = $recipient['to'];
		}

		$subject = $data['subject'];
		$body = $str;

		/*$transport = Swift_SmtpTransport::newInstance('aspmx.l.google.com', 25)
		 ->setUsername('')
		->setPassword('');*/


		//        $transport = Swift_SmtpTransport::newInstance('aspmx.l.google.com', 25)
		//                ->setUsername('ntech.n.ntech@gmail.com')
		//                ->setPassword('ntechpassn');

		$transport = Swift_SmtpTransport::newInstance(SMTP_SERVER_NAME, SMTP_PORT_NO)
		->setUsername(SMTP_SERVER_USERNAME)
		->setPassword(SMTP_SERVER_PASSWORD);

		$mailer = Swift_Mailer::newInstance($transport);

		$message = Swift_Message::newInstance()
		->setSubject($subject)
		->setFrom(array("no-reply@astrowow.com" => "Astrowow-Team"))
		->setTo($recipient['to'])
		->setBody($body)
		->addPart($body, 'text/html');

		//$message->addBcc('parmaramit1111@gmail.com', 'Amit Parmar');

		//print_r($str);

		// Send the message
		try {
			$result = $mailer->send($message);
		}
		catch(Exception $ex) {
			//print_r($ex);
		}
		//print_r($result);
		//exit;
		return $result;
	}

	function SendWellComeEmailOnEliteSignup($sender,$recipient, $data) {

		$language_id = 'en';
		if(isset($data['sitelink'])) {
			$language_id = $data['language_id'];
		}

		$subject = $data['subject'];
		
		$objEmailTemplate = new emailTemplate();
		$result = $objEmailTemplate->GetEmailTemplateById(WELLCOME_ELITE_EMAIL_TEMPLATE_ID,$language_id);
		//print_r($data);
		if(count($result)>0) {
			$str = html_entity_decode($result['content']);			
			$subject = ((isset($result['subject']) && trim($result['subject']) != "") ? stripslashes(html_entity_decode( utf8_decode($result['subject']))) : "");
		}
		else {
			$str = $data['mailtext'].'<img title="Astrowow.com" alt="Astrowow.com" src="'.BASEURL.'images/logo.png">';

			$str .='<p>Wellcome and Thank you for signup with Astrowow.com !</p> ';
			$str .='<p>Now you can sign with below noted username and password</p> ';
			$str .='<p>Username: ##USERNAME</p>';
			$str .='<p>Password: ##PASSWORD</p>';
			$str .='<p>##SITELINK</p>';
		}
		
		$str = str_replace("##SITELINK", $data['sitelink'],$str);
		$str = str_replace("##USERNAME", $data['username'],$str);
		$str = str_replace("##PASSWORD", $data['password'],$str);
		$str = str_replace("##NAME",$data['name'],$str);
		$str = str_replace("##BASEURL",EMAIL_IMAGE_PATH,$str);


		$to = '';
		if(count($recipient)>0) {
			$to = $recipient['to'];
		}

		$body = $str;
		$transport = Swift_SmtpTransport::newInstance(SMTP_SERVER_NAME, SMTP_PORT_NO)
		->setUsername(SMTP_SERVER_USERNAME)
		->setPassword(SMTP_SERVER_PASSWORD);

		$mailer = Swift_Mailer::newInstance($transport);

		$message = Swift_Message::newInstance()
		->setSubject($subject)
		->setFrom(array("no-reply@astrowow.com" => "Astrowow-Team"))
		->setTo($recipient['to'])
		->setBody($body)
		->addPart($body, 'text/html');


		try {
			$result = $mailer->send($message);
		}
		catch(Exception $ex) {
			//print_r($ex);
		}
		//print_r($result);
		//exit;
		return $result;
	}

	function SendLoverReportDataToAdmin($request_data) {
		try {
			$user_email 	= $request_data['txtUserEmail'];
			$order_id 		= $request_data['order_id'];
			$order_language 	= $request_data['ddLanguage'];
			//$delivery_method 	= $request_data['rdoDeliveryMethod'];

			if($request_data['rdoDeliveryMethod'] == 0) {
				$delivery_method = 'Email attachment';
			}
			else {
				$delivery_method = 'Printed (by post)';
			}

			if($request_data['rdoGender1'] == 1) {
				$gender = 'Male';
			}
			else {
				$gender = 'Female';
			}
			$person_1_name 		= $request_data['txtFirstName1']." ". $request_data['txtLastName1'];
			$person_1_gender 		= $gender;
			$person_1_birth_date 	= $request_data['ddDay1']."-".$request_data['ddMonth1']."-".$request_data['ddYear1']." ".$request_data['birthhour1'].":".$request_data['birthminute1'];
			$person_1_lagitude 		= $request_data['hdnLatitude1'];
			$person_1_longitude 	= $request_data['hdnLongitude1'];
			$person_1_city 		= $request_data['txtBirthCity1'];
			$person_1_state 		= $request_data['ddBirthCountry1'];


			if($request_data['rdoGender2'] == 1) {
				$gender = 'Male';
			}
			else {
				$gender = 'Female';
			}
			$person_2_name 		= $request_data['txtFirstName2']." ". $request_data['txtLastName2'];
			$person_2_gender 		= $gender;
			$person_2_birth_date 	= $request_data['ddDay2']."-".$request_data['ddMonth2']."-".$request_data['ddYear2']." ".$request_data['birthhour2'].":".$request_data['birthminute2'];
			$person_2_lagitude 		= $request_data['hdnLatitude2'];
			$person_2_longitude 	= $request_data['hdnLongitude2'];
			$person_2_city              = $request_data['txtBirthCity2'];
			$person_2_state 		= $request_data['ddBirthCountry2'];

			// TODO :: for now we put static email ids, letter on it will be comes from config file
			//$to = 'urvish.u@gmail.com,ntech.corporate@gmail.com';
			$to = array(
					"to"    => "jette@rybak.dk",
			);
			$cc = 'urvish.u@gmail.com';

			$sender = "service@astrowow.com";

			$subject = 'Lovers Report Data for Order No.'.$order_id;
			$body = 'Lovers Report Data for Order No.'.$order_id;
			$body .= '<br><br>';
			$body .= '<br><br>';

			$body .= 'Delivery Method:'.$delivery_method;

			$body .= '<br><br>';
			$body .= '<br><br>';
			$body .= '<b>Person 1</b><br>';
			$body .= '<b>Name</b>:'.$person_1_name;
			$body .= '<br>';
			$body .= '<b>Gender</b>:'.$person_1_gender;
			$body .= '<br>';
			$body .= '<b>Birth Date</b>:'.$person_1_birth_date;
			$body .= '<br>';
			$body .= '<b>City</b>:'.$person_1_city;
			$body .= '<br>';
			$body .= '<b>State</b>:'.$person_1_state;
			$body .= '<br>';
			$body .= '<b>Lagitude</b>:'.$person_1_lagitude;
			$body .= '<br>';
			$body .= '<b>Logitude</b>:'.$person_1_longitude;

			$body .= '<br><br>';
			$body .= '<br><br>';
			$body .= '<b>Person 2</b><br>';
			$body .= '<b>Name</b>:'.$person_2_name;
			$body .= '<br>';
			$body .= '<b>Gender</b>:'.$person_2_gender;
			$body .= '<br>';
			$body .= '<b>Birth Date</b>:'.$person_2_birth_date;
			$body .= '<br>';
			$body .= '<b>City</b>:'.$person_2_city;
			$body .= '<br>';
			$body .= '<b>State</b>:'.$person_2_state;
			$body .= '<br>';
			$body .= '<b>Lagitude</b>:'.$person_2_lagitude;
			$body .= '<br>';
			$body .= '<b>Logitude</b>:'.$person_2_longitude;


			//        $transport = Swift_SmtpTransport::newInstance('aspmx.l.google.com', 25)
			//                ->setUsername('ntech.n.ntech@gmail.com')
			//                ->setPassword('ntechpassn');

			$transport = Swift_SmtpTransport::newInstance(SMTP_SERVER_NAME, SMTP_PORT_NO)
			->setUsername(SMTP_SERVER_USERNAME)
			->setPassword(SMTP_SERVER_PASSWORD);

			$mailer = Swift_Mailer::newInstance($transport);
			
//			->setCc($cc)

			$message = Swift_Message::newInstance()
			->setSubject($subject)
			->setFrom(array("no-reply@astrowow.com" => "Astrowow-Team"))
			->setTo($to)			
			->setBody($body)
			->addPart($body, 'text/html');

			//$message->addBcc('parmaramit1111@gmail.com', 'Amit Parmar');

			// Send the message

			return $result = $mailer->send($message);
		}
		catch(Exception $ex) {
			return false;
		}
	}

	function SendPasswordToUserInEmail($sender,$recipient, $data) {
		$to = '';
		if(count($recipient)>0) {
			$to = $recipient['to'];
		}

		//$subject = $data['subject'];
		//$body = $data['mailtext'];

		$subject = $data['subject'];
		$body = $data['mailtext'];


		//        $transport = Swift_SmtpTransport::newInstance('aspmx.l.google.com', 25)
		//                ->setUsername('ntech.n.ntech@gmail.com')
		//                ->setPassword('ntechpassn');

		$transport = Swift_SmtpTransport::newInstance(SMTP_SERVER_NAME, SMTP_PORT_NO)
		->setUsername(SMTP_SERVER_USERNAME)
		->setPassword(SMTP_SERVER_PASSWORD);

		$mailer = Swift_Mailer::newInstance($transport);

		$message = Swift_Message::newInstance()
		->setSubject($subject)
		->setFrom(array("no-reply@astrowow.com" => "Astrowow-Team"))
		->setTo($recipient['to'])
		->setBody($body)
		->addPart($body, 'text/html');


		// Send the message
		return $result = $mailer->send($message);
	}

	function SendResetPasswordLinkToUser($sender,$recipient,$data) {
		$language_id = 'en';

		$FACEBOOKLINK = 'http://www.facebook.com/MyAstropage?ref=hl';

		if(isset($data['language_id'])) {
			$language_id = $data['language_id'];
		}

		$objEmailTemplate = new emailTemplate();
		$result = $objEmailTemplate->GetEmailTemplateById(RESET_PASSWORD_EMAIL_TEMPLATE_ID,$language_id);
		$subject = '';
		if(count($result)>0) {
			$str = html_entity_decode( utf8_decode($result['content']));
			$subject = ((isset($result['subject']) && trim($result['subject']) != "") ? stripslashes(html_entity_decode( utf8_decode($result['subject']))) : "");
		}
		else {
			$str = $data['mailtext'].'<img title="Astrowow.com" alt="Astrowow.com" src="'.BASEURL.'images/logo.png">';

			$str .='Click below link to reset your password for Astrowow.com<br/> ';
			$str .='##PASSWORD-RESET-LINK';
		}
		$str = str_replace("##SITELINK",$data['sitelink'],$str);
		$str = str_replace("##NAME",$data['name'],$str);
		$str = str_replace("##BASEURL",EMAIL_IMAGE_PATH,$str);
		$str = str_replace("##FACEBOOKLINK",$FACEBOOKLINK,$str);
		$str = str_replace("##PASSWORD-RESET-LINK",$data['reset_password_link'],$str);

		$to = '';
		if(count($recipient)>0) {
			$to = $recipient['to'];
		}
		if(isset($subject) && trim($subject) != "") {
		}else {
			$subject = $data['subject'];
		}
		$body = $str;

		$transport = Swift_SmtpTransport::newInstance(SMTP_SERVER_NAME, SMTP_PORT_NO)
		->setUsername(SMTP_SERVER_USERNAME)
		->setPassword(SMTP_SERVER_PASSWORD);

		$mailer = Swift_Mailer::newInstance($transport);

		$message = Swift_Message::newInstance()
		->setSubject($subject)
		->setFrom(array("no-reply@astrowow.com" => "Astrowow-Team"))
		->setTo($recipient['to'])
		->setBody($body)
		->addPart($body, 'text/html');

		//$message->addBcc('parmaramit1111@gmail.com', 'Amit Parmar');

		// Send the message
		try {
			$result = $mailer->send($message);
		}catch (Exception $ex) {
		}

		//exit;
		return $result;
	}

	function SendPaymentAcceptMailForReport($sender,$recipient,$data) {
		$language_id = 'en';
		$subject =  '';
		$REPORTLINK1= BASEURL.'report-detail.php?product_id=13';
		$REPORTLINK2= BASEURL.'report-detail.php?product_id=21';
		$REPORTLINK3= BASEURL.'report-detail.php?product_id=14';

		$REPORTIMAGE1= EMAIL_IMAGE_PATH.'/lover.png';
		$REPORTIMAGE2= EMAIL_IMAGE_PATH.'/yearreport.png';
		$REPORTIMAGE3= EMAIL_IMAGE_PATH.'/calender.png';

		$REPORTTEXT1= 'Astrology for Lovers Report';
		$REPORTTEXT2= 'Essential Year Ahead Report';
		$REPORTTEXT3= 'Astrology Calendar Report';

		$FACEBOOKLINK = 'http://www.facebook.com/MyAstropage?ref=hl';

		if(isset($data['language_id'])) {
			if($data['language_id'] == 'English' || $data['language_id'] == 'en') {
				$language_id = 'en';
			}
			else if($data['language_id'] == 'Danish' || $data['language_id'] == 'dk') {
				$language_id = 'dk';
			}
			else {
				$language_id = 'en';
			}
		}

		$TESTIMONIALSLINK = 'testimonials/astropage/'.$language_id."/";

		if(isset($subject) && trim($subject) != "" ){
		}else {
			$subject = $data['subject'];
		}

		$objEmailTemplate = new emailTemplate();
		$result = array();

		if(isset($data['product_id'])) {
			if($data['product_id'] == 13) {
				$result = $objEmailTemplate->GetEmailTemplateById(SEND_PAYMENT_ACCEPT_MAIL_FOR_LOVER_REPORT,$language_id);				
			} else {
				if($data['delivery_option'] == 1) {
					$result = $objEmailTemplate->GetEmailTemplateById(SEND_PAYMENT_ACCEPT_MAIL_FOR_REPORT,$language_id);
				} else {
					$result = $objEmailTemplate->GetEmailTemplateById(SEND_PAYMENT_ACCEPT_MAIL_FOR_PRINTED_REPORT,$language_id);
				}
			}
			
		}
		else {
			if($data['delivery_option'] == 1) {
				$result = $objEmailTemplate->GetEmailTemplateById(SEND_PAYMENT_ACCEPT_MAIL_FOR_REPORT,$language_id);
			} else {
				$result = $objEmailTemplate->GetEmailTemplateById(SEND_PAYMENT_ACCEPT_MAIL_FOR_PRINTED_REPORT,$language_id);
			}
		}

		if(count($result)>0) {
			$str = html_entity_decode( utf8_decode($result['content']));
			$subject = sprintf(stripcslashes(html_entity_decode( utf8_decode($result['subject']))) , stripcslashes(html_entity_decode(utf8_decode( $data['product_name']))));
		}
		else {
			$str = $data['mailtext'].'<img title="Astrowow.com" alt="Astrowow.com" src="'.BASEURL.'images/logo.png">';

			$str .='Click below link to reset your password for Astrowow.com<br/> ';
			$str .='##PASSWORD-RESET-LINK';
		}

		if(isset($data['product_id'])) {
			if($data['product_id'] == 21) {
				$REPORTLINK1= BASEURL.'report-detail.php?product_id=13';
				$REPORTLINK2= BASEURL.'report-detail.php?product_id=12';
				$REPORTLINK3= BASEURL.'report-detail.php?product_id=14';

				$REPORTIMAGE1= EMAIL_IMAGE_PATH.'/lover.png';
				$REPORTIMAGE2= EMAIL_IMAGE_PATH.'/character.png';
				$REPORTIMAGE3= EMAIL_IMAGE_PATH.'/calender.png';

				$REPORTTEXT1= 'Astrology for Lovers Report';
				$REPORTTEXT2= 'Character and destiny Report ';
				$REPORTTEXT3= 'Astrology Calendar Report';
				$TESTIMONIALSLINK = 'testimonials/reports/essential-year-ahead-prediction/'.$language_id."/";
			}
			else if($data['product_id'] == 12) {
				$REPORTLINK1= BASEURL.'report-detail.php?product_id=13';
				$REPORTLINK2= BASEURL.'report-detail.php?product_id=21';
				$REPORTLINK3= BASEURL.'report-detail.php?product_id=14';

				$REPORTIMAGE1= EMAIL_IMAGE_PATH.'/lover.png';
				$REPORTIMAGE2= EMAIL_IMAGE_PATH.'/yearreport.png';
				$REPORTIMAGE3= EMAIL_IMAGE_PATH.'/calender.png';

				$REPORTTEXT1= 'Astrology for Lovers Report';
				$REPORTTEXT2= 'Essential Year Ahead Report';
				$REPORTTEXT3= 'Astrology Calendar Report';
				$TESTIMONIALSLINK = 'testimonials/reports/character-and-destiny/'.$language_id."/";
			}
			else if($data['product_id'] == 13) {
				$REPORTLINK1= BASEURL.'report-detail.php?product_id=21';
				$REPORTLINK2= BASEURL.'report-detail.php?product_id=12';
				$REPORTLINK3= BASEURL.'report-detail.php?product_id=14';

				$REPORTIMAGE1= EMAIL_IMAGE_PATH.'/yearreport.png';
				$REPORTIMAGE2= EMAIL_IMAGE_PATH.'/character.png';
				$REPORTIMAGE3= EMAIL_IMAGE_PATH.'/calender.png';

				$REPORTTEXT1= 'Essential Year Ahead Report';
				$REPORTTEXT2= 'Character and destiny Report ';
				$REPORTTEXT3= 'Astrology Calendar Report';
				$TESTIMONIALSLINK = 'testimonials/reports/comprehensive-lover/'.$language_id."/";
			}
			else if($data['product_id'] == 14) {
				$REPORTLINK1= BASEURL.'report-detail.php?product_id=13';
				$REPORTLINK2= BASEURL.'report-detail.php?product_id=12';
				$REPORTLINK3= BASEURL.'report-detail.php?product_id=21';

				$REPORTIMAGE1= EMAIL_IMAGE_PATH.'/lover.png';
				$REPORTIMAGE2= EMAIL_IMAGE_PATH.'/character.png';
				$REPORTIMAGE3= EMAIL_IMAGE_PATH.'/yearreport.png';

				$REPORTTEXT1= 'Astrology for Lovers Report';
				$REPORTTEXT2= 'Character and destiny Report ';
				$REPORTTEXT3= 'Essential Year Ahead Report';
				$TESTIMONIALSLINK = 'testimonials/reports/astrology-calendar/'.$language_id."/";
			}
			else if($data['product_id'] == 16) {
				$TESTIMONIALSLINK = 'testimonials/reports/psychological-analysis/'.$language_id."/";
			}
			else if($data['product_id'] == 17) {
				$TESTIMONIALSLINK = 'testimonials/reports/career-and-vocation/'.$language_id."/";
			}
			else if($data['product_id'] == 18) {
				$TESTIMONIALSLINK = 'testimonials/reports/childs-horoscope/'.$language_id."/";
			}
		}


		$str = str_replace("##SITELINK",BASEURL,$str);
		//$str = str_replace("##USERNAME",$data['username'],$str);
		//$str = str_replace("##PASSWORD",$data['password'],$str);
		$str = str_replace("##NAME",$data['first_name'],$str);
		$str = str_replace("##PRODUCTNAME",$data['product_name'],$str);
		$str = str_replace("##BASEURL",EMAIL_IMAGE_PATH,$str);
		$str = str_replace("##USEREMAILID",$data['email_id'],$str);
		$str = str_replace("##FACEBOOKLINK",$FACEBOOKLINK,$str);
		$str = str_replace("##TESTIMONIALSLINK", BASEURL.$TESTIMONIALSLINK,$str);

		$str = str_replace("##REPORTLINK1",$REPORTLINK1,$str);
		$str = str_replace("##REPORTLINK2",$REPORTLINK2,$str);
		$str = str_replace("##REPORTLINK3",$REPORTLINK3,$str);

		$str = str_replace("##REPORTIMAGE1",$REPORTIMAGE1,$str);
		$str = str_replace("##REPORTIMAGE2",$REPORTIMAGE2,$str);
		$str = str_replace("##REPORTIMAGE3",$REPORTIMAGE3,$str);

		$str = str_replace("##REPORTTEXT1",$REPORTTEXT1,$str);
		$str = str_replace("##REPORTTEXT2",$REPORTTEXT2,$str);
		$str = str_replace("##REPORTTEXT3",$REPORTTEXT3,$str);

		$to = '';
		if(count($recipient)>0) {
			$to = $recipient['to'];
		}

		$body = $str;
		//exit;
		
		$subject	= "Thank you for ordering ".$data['product_name']." ";

		//        $transport = Swift_SmtpTransport::newInstance('aspmx.l.google.com', 25)
		//                ->setUsername('ntech.n.ntech@gmail.com')
		//                ->setPassword('ntechpassn');

		$transport = Swift_SmtpTransport::newInstance(SMTP_SERVER_NAME, SMTP_PORT_NO)
		->setUsername(SMTP_SERVER_USERNAME)
		->setPassword(SMTP_SERVER_PASSWORD);

		$mailer = Swift_Mailer::newInstance($transport);

		$message = Swift_Message::newInstance()
		->setSubject($subject)
		->setFrom(array("no-reply@astrowow.com" => "Astrowow-Team"))
		->setTo($recipient['to'])
		->setBody($body)
		->addPart($body, 'text/html');

		//$message->addBcc('parmaramit1111@gmail.com', 'Amit Parmar');

		// Send the message
		try {
			$result = $mailer->send($message);
		}catch (Exception $ex) {
		}

		//exit;
		return $result;
	}

	function SendQuestionAnsweredEmail($sender,$recipient,$data) {
		$language_id = 'en';

		if(isset($data['language_id'])) {
			$language_id = $data['language_id'];
		}

		$subject = $data['subject'];
		$objEmailTemplate = new emailTemplate();
		//$result = $objEmailTemplate->GetEmailTemplateById(RESET_PASSWORD_EMAIL_TEMPLATE_ID,$language_id);
		$result = array();
		if(count($result)>0) {
			$str = $result['content'];
			$subject = ((isset($result['subject']) && trim($result['subject']) != "") ? stripslashes(html_entity_decode( utf8_decode($result['subject']))) : "");
		}
		else {
			$str = 'Your Question for Golden Circle is answered, to view answer navigate to Golden Circle Asked Question section of your astropage in astrowow.com<br/>';

			$str .='<a href="'.BASEURL.'my-astropage.php">Click here</a> to open your astropagerd for Astrowow.com<br/> ';

		}
		$str = str_replace("##SITELINK",$data['sitelink'],$str);
		//$str = str_replace("##USERNAME",$data['username'],$str);
		//$str = str_replace("##PASSWORD",$data['password'],$str);
		$str = str_replace("##NAME",$data['name'],$str);
		$str = str_replace("##BASEURL",EMAIL_IMAGE_PATH,$str);

		$to = '';
		if(count($recipient)>0) {
			$to = $recipient['to'];
		}

		$body = $str;

		//        $transport = Swift_SmtpTransport::newInstance('aspmx.l.google.com', 25)
		//                ->setUsername('ntech.n.ntech@gmail.com')
		//                ->setPassword('ntechpassn');


		//        $transport = Swift_SmtpTransport::newInstance('aspmx.l.google.com', 25)
		//                ->setUsername('ntech.n.ntech@gmail.com')
		//                ->setPassword('ntechpassn');

		$transport = Swift_SmtpTransport::newInstance(SMTP_SERVER_NAME, SMTP_PORT_NO)
		->setUsername(SMTP_SERVER_USERNAME)
		->setPassword(SMTP_SERVER_PASSWORD);

		$mailer = Swift_Mailer::newInstance($transport);

		$message = Swift_Message::newInstance()
		->setSubject($subject)
		->setFrom(array("no-reply@astrowow.com" => "Astrowow-Team"))
		->setTo($recipient['to'])
		->setBody($body)
		->addPart($body, 'text/html');

		//$message->addBcc('parmaramit1111@gmail.com', 'Amit Parmar');

		// Send the message
		try {
			$result = $mailer->send($message);
		}catch (Exception $ex) {
		}

		//exit;
		return $result;
	}

	function SendFreeTrailSoftwareDownloadLink($sender, $recipient, $data) {
		$language_id = 'en';
		$objProduct = new Product();
		$subject = '';
		if(isset($data['language_id'])) {
			$language_id = $data['language_id'];
		}

		if(isset($data['language_id'])) {
			$language_id = $data['language_id'];
		}
		$subject = isset($data['subject']) ? $data['subject'] : "";

		$objEmailTemplate = new emailTemplate();
		$result = $objEmailTemplate->GetEmailTemplateById(FREE_SOFTWARE_DOWNLOAD_TEMPLATE,$language_id);

		if(count($result)>0) {
			//$str = $result['content'];
			$str = html_entity_decode( utf8_decode($result['content']));
			$subject = ((isset($result['subject']) && trim($result['subject']) != "") ? stripslashes(html_entity_decode( utf8_decode($result['subject']))) : "");
		}
		$FACEBOOKLINK = 'http://www.facebook.com/MyAstropage?ref=hl';
		$TESTIMONIALSLINK = '';
		$SOFTWARELINK1 = '';
		$SOFTWARETEXT1 = '';
		$SOFTWAREIMAGE1 = '';
		$SOFTWARELINK2 = '';
		$SOFTWARETEXT2 = '';
		$SOFTWAREIMAGE2 = '';
		$SOFTWARELINK3 = '';
		$SOFTWARETEXT3 = '';
		$SOFTWAREIMAGE3 = '';

		$BUYSOFTWARELINK = '';
		$BUYSHAREWARELINK = '';

		if(isset($data['product_id'])) {
			if($data['product_id'] == 7) {
				$SOFTWARELINK1 = BASEURL.'software-detail.php?product_id=7';
				$SOFTWARELINK2 = BASEURL.'software-detail.php?product_id=8';
				$SOFTWARELINK3 = BASEURL.'software-detail.php?product_id=9';

				$BUYSOFTWARELINK = BASEURL.'software-detail.php?product_id=7&report_type=3';
				$BUYSHAREWARELINK = BASEURL.'software-detail.php?product_id=7&report_type=2';

				$result = $objProduct->GetProductDetailsByProductId(7,$language_id);

				if(count($result) >0) {
					$SOFTWARETEXT1= $result[0]['productName'];
					$SOFTWAREIMAGE1= EMAIL_IMAGE_PATH.'/horoscope-interpreter.jpg';
				}

				unset($result);
				$result = $objProduct->GetProductDetailsByProductId(8,$language_id);
				if(count($result) >0) {
					$SOFTWARETEXT2= $result[0]['productName'];
					$SOFTWAREIMAGE2= EMAIL_IMAGE_PATH.'/lover.png';
				}

				unset($result);
				$result = $objProduct->GetProductDetailsByProductId(9,$language_id);

				if(count($result) >0) {
					$SOFTWARETEXT2= $result[0]['productName'];
					$SOFTWAREIMAGE2= EMAIL_IMAGE_PATH.'/calender.png';
				}

				$TESTIMONIALSLINK = 'testimonials/software/horoscope-interpreter/'.$language_id."/";
			}
			else if($data['product_id'] == 8) {
				$SOFTWARELINK1= BASEURL.'software-detail.php?product_id=7';
				$SOFTWARELINK2= BASEURL.'software-detail.php?product_id=8';
				$SOFTWARELINK3= BASEURL.'software-detail.php?product_id=9';

				$BUYSOFTWARELINK = BASEURL.'software-detail.php?product_id=8&report_type=3';
				$BUYSHAREWARELINK = BASEURL.'software-detail.php?product_id=8&report_type=2';

				$result = $objProduct->GetProductDetailsByProductId(7,$language_id);

				if(count($result) >0) {
					$SOFTWARETEXT1= $result[0]['productName'];
					$SOFTWAREIMAGE1= EMAIL_IMAGE_PATH.'/horoscope-interpreter.jpg';
				}

				unset($result);
				$result = $objProduct->GetProductDetailsByProductId(8, $language_id);
				if(count($result) >0) {
					$SOFTWARETEXT2= $result[0]['productName'];
					$SOFTWAREIMAGE2= EMAIL_IMAGE_PATH.'/lover.png';
				}

				unset($result);
				$result = $objProduct->GetProductDetailsByProductId(9, $language_id);
				if(count($result) >0) {
					$SOFTWARETEXT3= $result[0]['productName'];
					$SOFTWAREIMAGE3= EMAIL_IMAGE_PATH.'/calender.png';
				}

				$TESTIMONIALSLINK = 'testimonials/software/astrology-for-lovers/'.$language_id."/";
			}
			else if($data['product_id'] == 9) {
				$SOFTWARELINK1= BASEURL.'software-detail.php?product_id=7';
				$SOFTWARELINK2= BASEURL.'software-detail.php?product_id=8';
				$SOFTWARELINK3= BASEURL.'software-detail.php?product_id=9';

				$BUYSOFTWARELINK = BASEURL.'software-detail.php?product_id=9&report_type=3';
				$BUYSHAREWARELINK = BASEURL.'software-detail.php?product_id=9&report_type=2';

				$result = $objProduct->GetProductDetailsByProductId(7,$language_id);

				if(count($result) >0) {
					$SOFTWARETEXT1= $result[0]['productName'];
					$SOFTWAREIMAGE1= EMAIL_IMAGE_PATH.'/horoscope-interpreter.jpg';
				}
				unset($result);
				$result = $objProduct->GetProductDetailsByProductId(8,$language_id);
				if(count($result) >0) {
					$SOFTWARETEXT2= $result[0]['productName'];
					$SOFTWAREIMAGE2= EMAIL_IMAGE_PATH.'/lover.png';
				}

				unset($result);
				$result = $objProduct->GetProductDetailsByProductId(9, $language_id);
				if(count($result) >0) {
					$SOFTWARETEXT3= $result[0]['productName'];
					$SOFTWAREIMAGE3= EMAIL_IMAGE_PATH.'/calender.png';
				}

				$TESTIMONIALSLINK = 'testimonials/software/astrological-calendar/'.$language_id."/";
			}
		}


		$str = str_replace("##SITELINK", BASEURL, $str);
		$str = str_replace("##BASEURL", EMAIL_IMAGE_PATH, $str);
		$str = str_replace("##NAME", $data['first_name'], $str);
		$str = str_replace("##PRODUCTNAME", $data['product_name'], $str);
		$str = str_replace("##USEREMAILID", $data['email'], $str);
		$str = str_replace("##FACEBOOKLINK", $FACEBOOKLINK, $str);
		$str = str_replace("##TESTIMONIALSLINK", BASEURL.$TESTIMONIALSLINK, $str);
		$str = str_replace("##DOWNLOADLINK", $data['donwload_link'], $str);
		$str = str_replace("##SOFTWARELINK1", $SOFTWARELINK1, $str);
		$str = str_replace("##SOFTWARETEXT1", $SOFTWARETEXT1, $str);
		$str = str_replace("##SOFTWAREIMAGE1", $SOFTWAREIMAGE1, $str);

		$str = str_replace("##SOFTWARELINK2", $SOFTWARELINK2, $str);
		$str = str_replace("##SOFTWARETEXT2", $SOFTWARETEXT2, $str);
		$str = str_replace("##SOFTWAREIMAGE2", $SOFTWAREIMAGE2, $str);

		$str = str_replace("##SOFTWARELINK3", $SOFTWARELINK3, $str);
		$str = str_replace("##SOFTWARETEXT3", $SOFTWARETEXT3, $str);
		$str = str_replace("##SOFTWAREIMAGE3", $SOFTWAREIMAGE3, $str);

		$str = str_replace("##BUYCDLINK", $BUYSOFTWARELINK, $str);
		$str = str_replace("##BUYSHAREWARELINK", $BUYSHAREWARELINK, $str);

		$to = '';
		if(count($recipient)>0) {
			$to = $recipient['to'];
		}

		$body = $str;

		$transport = Swift_SmtpTransport::newInstance('mail.astrowow.com', 25)
		->setUsername('services@astrowow.com')
		->setPassword('ard6969ag');

		$mailer = Swift_Mailer::newInstance($transport);

		$message = Swift_Message::newInstance()
		->setSubject($subject)
		->setFrom(array("no-reply@astrowow.com" => "Astrowow-Team"))
		->setTo($recipient['to'])
		->setBody($body)
		->addPart($body, 'text/html');

		//$message->addBcc('parmaramit1111@gmail.com', 'Amit Parmar');

		// Send the message
		try {
			$result = $mailer->send($message);
		}catch (Exception $ex) {
		}

		return $result;
	}

	function SendWeeklySunsignMail($sender,$recipient, $data) {

		$language_id = 'en';
		$locale = 'en-US';
		$default_locale = 'en-US';
		$FACEBOOK 		= 'http://www.facebook.com/MyAstropage?ref=hl';
		$TWEETER 		= 'https://twitter.com/AdrianDuncan8';
		$GOOGLEPLUS 	= 'https://plus.google.com/106143888045600794106/posts';
		$YOUTUBE 		= 'http://www.youtube.com/watch?v=Ze8VwNle15I&feature=results_video';

		if(isset($data['language_id'])) {
			$language_id = $data['language_id'];
		}
		if(isset($data['locale'])) {
			$locale = $data['locale'];
		}

		$objEmailTemplate = new emailTemplate();
		$result = $objEmailTemplate->GetEmailTemplateById(WEEKLY_SUNSIGN_EMAIL_TEMPLATE_ID,$language_id);

		// Set the locale:
		setlocale(LC_ALL, $locale);
		$date = strftime('%A %B %d,%G ',time());
		$str = '';

		if(count($result)>0) {
			$str = $result['content'];
		}
		else {
			$str = $data['mailtext'].'<img title="Astrowow.com" alt="Astrowow.com" src="'.BASEURL.'images/logo.png">';
			$str .='<p>Your Sun Sign Horoscope for '.$date.'</p> ';
			$str .='<p>Now you can sign with below noted username and password</p> ';
			$str .='<p>##SITELINK</p>';
			$str = '';
		}

		if(strlen($str) > 0) {

			$str = str_replace("##SITELINK",$data['sitelink'],$str);
			$str = str_replace("##NAME",$data['name'],$str);
			$str = str_replace("##BASEURL",EMAIL_IMAGE_PATH,$str);
			$str = str_replace("##CURRENT_DATE",strftime('%A %B %d,%Y ',time()),$str);
			$str = str_replace("##CURRENTDATE2",strftime('%B %d,%Y ',time()),$str);
			$str = str_replace("##SUNSIGN",$data['sunsign_name'],$str);
			$str = str_replace("##DATE1",strftime('%A %B %d,%Y ',strtotime($data['start_date'])),$str);
			$str = str_replace("##DATE2",strftime('%A %B %d,%Y ',strtotime($data['end_date'])),$str);

			$str = str_replace("##WEEKLY_HOROSCOPE",$data['weekly_horoscope'],$str);
			$str = str_replace("##HOROSCOPEURL",$data['horoscope_url'],$str);
			$str = str_replace("##HOROSCOPEIMAGE",$data['horoscope_image'],$str);
			$str = str_replace("##FACEBOOK",$FACEBOOK,$str);
			$str = str_replace("##TWEETER",$TWEETER,$str);
			$str = str_replace("##GOOGLEPLUS",$GOOGLEPLUS,$str);
			$str = str_replace("##YOUTUBE",$YOUTUBE,$str);
			//##HOROSCOPEURL
			//##HOROSCOPEIMAGE

			$to = '';
			if(count($recipient)>0) {
				$to = $recipient['to'];
			}

			$subject = 'Your Sun Sign Horoscope for '.strftime('%A %B %d,%Y ',strtotime($data['start_date'])). ' to ' .strftime('%A %B %d,%Y ',strtotime($data['end_date']));
			$body = $str;

			$transport = Swift_SmtpTransport::newInstance('mail.astrowow.com', 25)
			->setUsername('services@astrowow.com')
			->setPassword('ard6969ag');

			$mailer = Swift_Mailer::newInstance($transport);

			$message = Swift_Message::newInstance()
			->setSubject($subject)
			->setFrom(array("no-reply@astrowow.com" => "Astrowow-Team"))
			->setTo($recipient['to'])
			->setBody($body)
			->addPart($body, 'text/html');

			// Send the message
			try {
				$result = $mailer->send($message);
			}catch (Exception $ex) {

			}
			//exit;
			return $result;
		}
		return 0;
	}

	function SendMonthlySunsignMail($sender,$recipient, $data) {

		$language_id = 'en';
		$locale = 'en-US';
		$default_locale = 'en-US';
		$FACEBOOK 		= 'http://www.facebook.com/MyAstropage?ref=hl';
		$TWEETER 		= 'https://twitter.com/AdrianDuncan8';
		$GOOGLEPLUS 	= 'https://plus.google.com/106143888045600794106/posts';
		$YOUTUBE 		= 'http://www.youtube.com/watch?v=Ze8VwNle15I&feature=results_video';

		if(isset($data['language_id'])) {
			$language_id = $data['language_id'];
		}
		if(isset($data['locale'])) {
			$locale = $data['locale'];
		}

		$objEmailTemplate = new emailTemplate();
		$result = $objEmailTemplate->GetEmailTemplateById(MONTHLY_SUNSIGN_EMAIL_TEMPLATE_ID,$language_id);

		// Set the locale:
		setlocale(LC_ALL, $locale);
		$date = strftime('%A %B %d,%G ',time());
		$str = '';

		if(count($result)>0) {
			$str = $result['content'];
		}
		else {
			$str = $data['mailtext'].'<img title="Astrowow.com" alt="Astrowow.com" src="'.BASEURL.'images/logo.png">';

			$str .='<p>Your Sun Sign Horoscope for '.$date.'</p> ';
			$str .='<p>Now you can sign with below noted username and password</p>';
			$str .='<p>##SITELINK</p>';
			$str = '';
		}

		if(strlen($str) > 0) {
			$str = str_replace("##MONTH_HOROSCOPE",$data['monthly_horoscope'],$str);
			$str = str_replace("##SITELINK",$data['sitelink'],$str);
			$str = str_replace("##NAME",$data['name'],$str);
			$str = str_replace("##BASEURL",EMAIL_IMAGE_PATH,$str);
			$str = str_replace("##CURRENT_DATE",strftime('%A %B %d,%Y ',time()),$str);
			$str = str_replace("##CURRENTDATE2",strftime('%B %d,%Y ',time()),$str);
			$str = str_replace("##SUNSIGN",$data['sunsign_name'],$str);
			$str = str_replace("##DATE1",strftime('%A %B %d,%Y ',strtotime($data['start_date'])),$str);
			$str = str_replace("##DATE2",strftime('%A %B %d,%Y ',strtotime($data['end_date'])),$str);

			$str = str_replace("##HOROSCOPEURL",$data['horoscope_url'],$str);
			$str = str_replace("##HOROSCOPEIMAGE",$data['horoscope_image'],$str);
			$str = str_replace("##FACEBOOK",$FACEBOOK,$str);
			$str = str_replace("##TWEETER",$TWEETER,$str);
			$str = str_replace("##GOOGLEPLUS",$GOOGLEPLUS,$str);
			$str = str_replace("##YOUTUBE",$YOUTUBE,$str);
			//##HOROSCOPEURL
			//##HOROSCOPEIMAGE

			$to = '';
			if(count($recipient)>0) {
				$to = $recipient['to'];
			}

			//$subject = 'Your Sun Sign Horoscope for '.date('l F d, Y');
			$subject = 'Your Sun Sign Horoscope for '.strftime('%A %B %d,%Y ',strtotime($data['start_date'])). ' to ' .strftime('%A %B %d,%Y ',strtotime($data['end_date'])) ;

			$body = $str;

			$transport = Swift_SmtpTransport::newInstance('mail.astrowow.com', 25)
			->setUsername('services@astrowow.com')
			->setPassword('ard6969ag');

			$mailer = Swift_Mailer::newInstance($transport);

			$message = Swift_Message::newInstance()
			->setSubject($subject)
			->setFrom(array("no-reply@astrowow.com" => "Astrowow-Team"))
			->setTo($recipient['to'])
			->setBody($body)
			->addPart($body, 'text/html');

			// Send the message
			try {
				$result = $mailer->send($message);
			}catch (Exception $ex) {
			}

			//exit;
			return $result;
		}
		return 0;
	}

	function SendQuestionAllotmentToAstrologer($recipient, $data) {

		$language_id = 'en';
		$locale = 'en-US';
		$default_locale = 'en-US';

		$subject = isset($data['subject']) ? $data['subject'] : "";

		if(isset($data['language_id'])) {
			$language_id = $data['language_id'];
		}

		if(isset($data['locale'])) {
			$locale = $data['locale'];
		}

		$objEmailTemplate = new emailTemplate();
		$result = $objEmailTemplate->GetEmailTemplateById(GC_ASTROLOGER_QUESTION_ALERT_EMAIL_TEMPLATE_ID, $language_id);

		// Set the locale:
		setlocale(LC_ALL, $locale);
		$date = strftime('%A %B %d,%G ',time());

		if(count($result)>0) {
			$str = html_entity_decode( utf8_decode($result['content']));
			$subject = ((isset($result['subject']) && trim($result['subject']) != "") ? stripslashes(html_entity_decode( utf8_decode($result['subject']))) : "");
		}

		$str = str_replace("##SITELINK", $data['sitelink'], $str);
		$str = str_replace("##NAME", $data['name'], $str);
		$str = str_replace("##BASEURL", EMAIL_IMAGE_PATH, $str);
		$str = str_replace("##PRIORITY", $data['PRIORITY'], $str);
		$str = str_replace("##BOOKTIME", $data['BOOKTIME'], $str);
		$str = str_replace("##REMAININGTIME", $data['REMAININGTIME'], $str);

		$body = $str;
		$sender = array("service@astrowow.com" => "Astrowow Service");

		//        $transport = Swift_SmtpTransport::newInstance('aspmx.l.google.com', 25)
		//                ->setUsername('ntech.n.ntech@gmail.com')
		//                ->setPassword('ntechpassn');

		$transport = Swift_SmtpTransport::newInstance('mail.astrowow.com', 25)
		->setUsername('services@astrowow.com')
		->setPassword('ard6969ag');

		$mailer = Swift_Mailer::newInstance($transport);

		$message = Swift_Message::newInstance()
		->setSubject($subject)
		->setFrom(array("no-reply@astrowow.com" => "Astrowow-Team"))
		->setTo($recipient)
		->setBody($body)
		->addPart($body, 'text/html');

		// Send the message
		try {
			$result = $mailer->send($message);
		}catch (Exception $ex) {

		}

		//exit;
		return $result;
	}

	function SendPaymentAcceptMailForSoftware($sender,$recipient,$data) {
		$language_id = 'en';
		$subject = '';
		$objProduct = new Product();

		$SOFTWARELINK1= BASEURL.'software-detail.php?product_id=7';
		$SOFTWARELINK2= BASEURL.'software-detail.php?product_id=8';
		$SOFTWARELINK3= BASEURL.'software-detail.php?product_id=9';

		$SOFTWAREIMAGE1= EMAIL_IMAGE_PATH.'/lover.png';
		$SOFTWAREIMAGE2= EMAIL_IMAGE_PATH.'/yearreport.png';
		$SOFTWAREIMAGE3= EMAIL_IMAGE_PATH.'/calender.png';

		$SOFTWARETEXT1= 'Astrology for Lovers Report';
		$SOFTWARETEXT2= 'Essential Year Ahead Report';
		$SOFTWARETEXT3= 'Astrology Calendar Report';

		$FACEBOOKLINK = 'http://www.facebook.com/MyAstropage?ref=hl';

		if(isset($data['language_id'])) {
			if($data['language_id'] == 'English' || $data['language_id'] == 'en') {
				$language_id = 'en';
			}
			else if($data['language_id'] == 'Danish' || $data['language_id'] == 'dk') {
				$language_id = 'dk';
			}
			else {
				$language_id = 'en';
			}
		}

		$TESTIMONIALSLINK = 'testimonials/astropage/'.$language_id."/";
		
		$subject = $data['subject'];
		
		$objEmailTemplate = new emailTemplate();
		$result = $objEmailTemplate->GetEmailTemplateById(BUY_SOFTWARE_CD_EMAIL_TEMPLATE,$language_id);
		if(count($result)>0) {
			//$str = $result['content'];
			$str = html_entity_decode( utf8_decode($result['content']));
			$subject = ((isset($result['subject']) && trim($result['subject']) != "") ? stripslashes(html_entity_decode( utf8_decode($result['subject']))) : "");
		}
		else {
			$str = "Dear ##NAME, <br />";
			$str .= "Thank you for ordering C.D version of “##PRODUCTNAME”.<br />";
			$str .= 'You will receive one more e-mail in your inbox. Please add our e-mail address to your address book, in order to make sure that you receive important e-mails from us in your inbox.
					Instruction E-mail<br />
					This e-mail will have instructions regarding how to install the C.D, and activate “Horoscope Interpreter”. All you need to know, to make the software up and running.<br />
					If you have any questions, concerns or feedback, please contacts us at <a href="mailto:support@astrowow.com">support@astrowow.com</a><br />';

			$str .= "To ensure you continue to receive our daily horoscope, be sure to add our email id to your contact list or address book.<br />
					Remember, you can unsubscribe anytime you want! –Thanks<br />
					Astrowow.com<br />
					To learn about our email privacy polices click here.<br />
					This email was sent to ##USEREMAILID . You are receiving it because you signed up at astrowow.com or otherwise requested to be<br />
					included in our mailings.<br />
					Astrowow.com";
		}

		if(isset($data['product_id'])) {
			if($data['product_id'] == 7) {
				$SOFTWARELINK1= BASEURL.'software-detail.php?product_id=8';
				$SOFTWARELINK2= BASEURL.'software-detail.php?product_id=9';
				$SOFTWARELINK3= BASEURL.'software-detail.php?product_id=7';

				$result = $objProduct->GetProductDetailsByProductId(8,$language_id);

				if(count($result) >0) {
					$SOFTWARETEXT1= $result[0]['productName'];
					$SOFTWAREIMAGE1= EMAIL_IMAGE_PATH.'/horoscope-interpreter.jpg';
				}

				unset($result);
				$result = $objProduct->GetProductDetailsByProductId(9,$language_id);
				if(count($result) >0) {
					$SOFTWARETEXT2= $result[0]['productName'];
					$SOFTWAREIMAGE2= EMAIL_IMAGE_PATH.'/lover.png';
				}

				unset($result);
				$result = $objProduct->GetProductDetailsByProductId(7,$language_id);

				if(count($result) >0) {
					$SOFTWARETEXT3= $result[0]['productName'];
					$SOFTWAREIMAGE3= EMAIL_IMAGE_PATH.'/calender.png';
				}

				$TESTIMONIALSLINK = 'testimonials/software/horoscope-interpreter/'.$language_id."/";

			}
			else if($data['product_id'] == 8) {
				$SOFTWARELINK1= BASEURL.'software-detail.php?product_id=7';
				$SOFTWARELINK2= BASEURL.'software-detail.php?product_id=9';
				$SOFTWARELINK3= BASEURL.'software-detail.php?product_id=8';

				$result = $objProduct->GetProductDetailsByProductId(7,$language_id);

				if(count($result) >0) {
					$SOFTWARETEXT1= $result[0]['productName'];
					$SOFTWAREIMAGE1= EMAIL_IMAGE_PATH.'/horoscope-interpreter.jpg';
				}

				unset($result);
				$result = $objProduct->GetProductDetailsByProductId(9, $language_id);
				if(count($result) >0) {
					$SOFTWARETEXT2= $result[0]['productName'];
					$SOFTWAREIMAGE2= EMAIL_IMAGE_PATH.'/lover.png';
				}

				unset($result);
				$result = $objProduct->GetProductDetailsByProductId(8, $language_id);
				if(count($result) >0) {
					$SOFTWARETEXT3= $result[0]['productName'];
					$SOFTWAREIMAGE3= EMAIL_IMAGE_PATH.'/calender.png';
				}

				$TESTIMONIALSLINK = 'testimonials/software/astrology-for-lovers/'.$language_id."/";
			}
			else if($data['product_id'] == 9) {
				$SOFTWARELINK1= BASEURL.'software-detail.php?product_id=7';
				$SOFTWARELINK2= BASEURL.'software-detail.php?product_id=8';
				$SOFTWARELINK3= BASEURL.'software-detail.php?product_id=9';

				$result = $objProduct->GetProductDetailsByProductId(7,$language_id);

				if(count($result) >0) {
					$SOFTWARETEXT1= $result[0]['productName'];
					$SOFTWAREIMAGE1= EMAIL_IMAGE_PATH.'/horoscope-interpreter.jpg';
				}
				unset($result);
				$result = $objProduct->GetProductDetailsByProductId(8,$language_id);
				if(count($result) >0) {
					$SOFTWARETEXT2= $result[0]['productName'];
					$SOFTWAREIMAGE2= EMAIL_IMAGE_PATH.'/lover.png';
				}

				unset($result);
				$result = $objProduct->GetProductDetailsByProductId(9, $language_id);
				if(count($result) >0) {
					$SOFTWARETEXT3= $result[0]['productName'];
					$SOFTWAREIMAGE3= EMAIL_IMAGE_PATH.'/calender.png';
				}

				$TESTIMONIALSLINK = 'testimonials/software/hastrological-calendar/'.$language_id."/";
			}
		}

		if($str != "") {
			$str = str_replace("##SITELINK", BASEURL, $str);
			$str = str_replace("##NAME", $data['first_name'], $str);
			$str = str_replace("##PRODUCTNAME", $data['product_name'], $str);
			$str = str_replace("##BASEURL", EMAIL_IMAGE_PATH, $str);
			$str = str_replace("##USEREMAILID", $data['email_id'], $str);
			$str = str_replace("##FACEBOOKLINK", $FACEBOOKLINK, $str);
			$str = str_replace("##TESTIMONIALSLINK", BASEURL.$TESTIMONIALSLINK, $str);

			$str = str_replace("##SOFTWARELINK1", $SOFTWARELINK1, $str);
			$str = str_replace("##SOFTWARELINK2", $SOFTWARELINK2, $str);
			$str = str_replace("##SOFTWARELINK3", $SOFTWARELINK3, $str);

			$str = str_replace("##SOFTWAREIMAGE1", $SOFTWAREIMAGE1, $str);
			$str = str_replace("##SOFTWAREIMAGE2", $SOFTWAREIMAGE2, $str);
			$str = str_replace("##SOFTWAREIMAGE3", $SOFTWAREIMAGE3, $str);

			$str = str_replace("##SOFTWARETEXT1", $SOFTWARETEXT1, $str);
			$str = str_replace("##SOFTWARETEXT2", $SOFTWARETEXT2, $str);
			$str = str_replace("##SOFTWARETEXT3", $SOFTWARETEXT3, $str);
		}

		$to = '';
		if(count($recipient)>0) {
			$to = $recipient['to'];
		}

		$body = $str;
		//exit;

		//        $transport = Swift_SmtpTransport::newInstance('aspmx.l.google.com', 25)
		//                ->setUsername('ntech.n.ntech@gmail.com')
		//                ->setPassword('ntechpassn');

		$transport = Swift_SmtpTransport::newInstance('mail.astrowow.com', 25)
		->setUsername('services@astrowow.com')
		->setPassword('ard6969ag');

		$mailer = Swift_Mailer::newInstance($transport);

		$subject	= "Thank you for ordering ".$data['product_name']." ";
		
		$message = Swift_Message::newInstance()
		->setSubject($subject)
		->setFrom(array("no-reply@astrowow.com" => "Astrowow-Team"))
		->setTo($recipient['to'])
		->setBody($body)
		->addPart($body, 'text/html');

		//$message->addBcc('parmaramit1111@gmail.com', 'Amit Parmar');

		// Send the message
		try {
			$result = $mailer->send($message);
		}catch (Exception $ex) {
			/*mail("parmaramit1111@gmail.com", "In GENERIC MAIL Error While ", $ex->getMessage());*/
		}

		//exit;
		return $result;
	}

	function SendSoftwareCDInstruction($sender,$recipient,$data) {
		$language_id = 'en';
		$subject = '';
		$objProduct = new Product();

		$SOFTWARELINK1= BASEURL.'software-detail.php?product_id=7';
		$SOFTWARELINK2= BASEURL.'software-detail.php?product_id=8';
		$SOFTWARELINK3= BASEURL.'software-detail.php?product_id=9';

		$SOFTWAREIMAGE1= EMAIL_IMAGE_PATH.'/';
		$SOFTWAREIMAGE2= EMAIL_IMAGE_PATH.'/';
		$SOFTWAREIMAGE3= EMAIL_IMAGE_PATH.'/';

		$SOFTWARETEXT1= '';
		$SOFTWARETEXT2= '';
		$SOFTWARETEXT3= '';

		$FACEBOOKLINK = 'http://www.facebook.com/MyAstropage?ref=hl';

		if(isset($data['language_id'])) {
			if($data['language_id'] == 'English' || $data['language_id'] == 'en') {
				$language_id = 'en';
			}
			else if($data['language_id'] == 'Danish' || $data['language_id'] == 'dk') {
				$language_id = 'dk';
			}
			else {
				$language_id = 'en';
			}
		}

		$TESTIMONIALSLINK = 'testimonials/astropage/'.$language_id."/";

		$subject = $data['subject'];
		
		$objEmailTemplate = new emailTemplate();
		$result = $objEmailTemplate->GetEmailTemplateById(SOFTWARE_CD_INSTRUCTION_EMAIL_TEMPLATE,$language_id);
		if(count($result)>0) {
			//$str = $result['content'];
			$str = html_entity_decode( utf8_decode($result['content']));
			$subject = ((isset($result['subject']) && trim($result['subject']) != "") ? stripslashes(html_entity_decode( utf8_decode($result['subject']))) : "");
		}
		else {
			$str = "Dear ##NAME, <br />";
			$str .= "Thank you for ordering C.D version of “##PRODUCTNAME”.<br />";
			$str .= 'Below are the instructions to download and install the software <br />
					1. 	Insert the CD then right click on icon of the CD and select “Run as Administrator” (You have to perform this only if you are trying to install on Windows Vista, 7 and 8).<br />
					2. 	For Windows versions prior to Vista Just directly install the software.<br />
					3. 	After installation, to open the software in Windows Vista, 7 and 8, right click on the icon and select “Open as Administrator”.<br />
					4. 	Please make sure that you don’t have any browser or any other Internet based software running while you try to open the software. Once you open the software successfully, you can then open and use any other software you like.<br />
					5. 	If the software does not open, try to open software again after closing all the browser and other programs<br />
					6. 	You must have received another email with the license key. Insert the license key into the software when prompted. (NOTE: Horoscope Interpreter, and Astrology for lover’s software, does not require license key.)<br />
					Please feel free to contact us at ssupport@astrowow.com, if you have any question, concerns or feedback for us.<br />';

			$str .= "To ensure you continue to receive our daily horoscope, be sure to add our email id to your contact list or address book.<br />
					Remember, you can unsubscribe anytime you want! –Thanks<br />
					Astrowow.com<br />
					To learn about our email privacy polices click here.<br />
					This email was sent to ##USEREMAILID . You are receiving it because you signed up at astrowow.com or otherwise requested to be<br />
					included in our mailings.<br />
					Astrowow.com";
		}

		if(isset($data['product_id'])) {
			if($data['product_id'] == 7) {
				$SOFTWARELINK1= BASEURL.'software-detail.php?product_id=8';
				$SOFTWARELINK2= BASEURL.'software-detail.php?product_id=9';
				$SOFTWARELINK3= BASEURL.'software-detail.php?product_id=7';

				$result = $objProduct->GetProductDetailsByProductId(8,$language_id);

				if(count($result) >0) {
					$SOFTWARETEXT1= $result[0]['productName'];
					$SOFTWAREIMAGE1= EMAIL_IMAGE_PATH.'/horoscope-interpreter.jpg';
				}

				unset($result);
				$result = $objProduct->GetProductDetailsByProductId(9,$language_id);
				if(count($result) >0) {
					$SOFTWARETEXT2= $result[0]['productName'];
					$SOFTWAREIMAGE2= EMAIL_IMAGE_PATH.'/lover.png';
				}

				unset($result);
				$result = $objProduct->GetProductDetailsByProductId(7,$language_id);

				if(count($result) >0) {
					$SOFTWARETEXT3= $result[0]['productName'];
					$SOFTWAREIMAGE3= EMAIL_IMAGE_PATH.'/calender.png';
				}

				$TESTIMONIALSLINK = 'testimonials/software/horoscope-interpreter/'.$language_id."/";

			}
			else if($data['product_id'] == 8) {
				$SOFTWARELINK1= BASEURL.'software-detail.php?product_id=7';
				$SOFTWARELINK2= BASEURL.'software-detail.php?product_id=9';
				$SOFTWARELINK3= BASEURL.'software-detail.php?product_id=8';

				$result = $objProduct->GetProductDetailsByProductId(7,$language_id);

				if(count($result) >0) {
					$SOFTWARETEXT1= $result[0]['productName'];
					$SOFTWAREIMAGE1= EMAIL_IMAGE_PATH.'/horoscope-interpreter.jpg';
				}

				unset($result);
				$result = $objProduct->GetProductDetailsByProductId(9, $language_id);
				if(count($result) >0) {
					$SOFTWARETEXT2= $result[0]['productName'];
					$SOFTWAREIMAGE2= EMAIL_IMAGE_PATH.'/lover.png';
				}

				unset($result);
				$result = $objProduct->GetProductDetailsByProductId(8, $language_id);
				if(count($result) >0) {
					$SOFTWARETEXT3= $result[0]['productName'];
					$SOFTWAREIMAGE3= EMAIL_IMAGE_PATH.'/calender.png';
				}

				$TESTIMONIALSLINK = 'testimonials/software/astrology-for-lovers/'.$language_id."/";
			}
			else if($data['product_id'] == 9) {
				$SOFTWARELINK1= BASEURL.'software-detail.php?product_id=7';
				$SOFTWARELINK2= BASEURL.'software-detail.php?product_id=8';
				$SOFTWARELINK3= BASEURL.'software-detail.php?product_id=9';

				$result = $objProduct->GetProductDetailsByProductId(7,$language_id);

				if(count($result) >0) {
					$SOFTWARETEXT1= $result[0]['productName'];
					$SOFTWAREIMAGE1= EMAIL_IMAGE_PATH.'/horoscope-interpreter.jpg';
				}
				unset($result);
				$result = $objProduct->GetProductDetailsByProductId(8,$language_id);
				if(count($result) >0) {
					$SOFTWARETEXT2= $result[0]['productName'];
					$SOFTWAREIMAGE2= EMAIL_IMAGE_PATH.'/lover.png';
				}

				unset($result);
				$result = $objProduct->GetProductDetailsByProductId(9, $language_id);
				if(count($result) >0) {
					$SOFTWARETEXT3= $result[0]['productName'];
					$SOFTWAREIMAGE3= EMAIL_IMAGE_PATH.'/calender.png';
				}

				$TESTIMONIALSLINK = 'testimonials/software/hastrological-calendar/'.$language_id."/";
			}
		}

		if($str != "") {
			$str = str_replace("##SITELINK", BASEURL, $str);
			$str = str_replace("##NAME", $data['first_name'], $str);
			$str = str_replace("##PRODUCTNAME", $data['product_name'], $str);
			$str = str_replace("##BASEURL", EMAIL_IMAGE_PATH, $str);
			$str = str_replace("##USEREMAILID", $data['email_id'], $str);
			$str = str_replace("##FACEBOOKLINK", $FACEBOOKLINK, $str);
			$str = str_replace("##TESTIMONIALSLINK", BASEURL.$TESTIMONIALSLINK, $str);

			$str = str_replace("##SOFTWARELINK1", $SOFTWARELINK1, $str);
			$str = str_replace("##SOFTWARELINK2", $SOFTWARELINK2, $str);
			$str = str_replace("##SOFTWARELINK3", $SOFTWARELINK3, $str);

			$str = str_replace("##SOFTWAREIMAGE1", $SOFTWAREIMAGE1, $str);
			$str = str_replace("##SOFTWAREIMAGE2", $SOFTWAREIMAGE2, $str);
			$str = str_replace("##SOFTWAREIMAGE3", $SOFTWAREIMAGE3, $str);

			$str = str_replace("##SOFTWARETEXT1", $SOFTWARETEXT1, $str);
			$str = str_replace("##SOFTWARETEXT2", $SOFTWARETEXT2, $str);
			$str = str_replace("##SOFTWARETEXT3", $SOFTWARETEXT3, $str);
		}

		$to = '';
		if(count($recipient)>0) {
			$to = $recipient['to'];
		}

		$body = $str;
		//exit;

		//        $transport = Swift_SmtpTransport::newInstance('aspmx.l.google.com', 25)
		//                ->setUsername('ntech.n.ntech@gmail.com')
		//                ->setPassword('ntechpassn');

		$transport = Swift_SmtpTransport::newInstance('mail.astrowow.com', 25)
		->setUsername('services@astrowow.com')
		->setPassword('ard6969ag');

		$mailer = Swift_Mailer::newInstance($transport);

		$message = Swift_Message::newInstance()
		->setSubject($subject)
		->setFrom(array("no-reply@astrowow.com" => "Astrowow-Team"))
		->setTo($recipient['to'])
		->setBody($body)
		->addPart($body, 'text/html');

		// Send the message
		try {
			$result = $mailer->send($message);
		}catch (Exception $ex) {
			mail("parmaramit1111@gmail.com", "In GENERIC MAIL Error While ", $ex->getMessage());
		}

		//exit;
		return $result;
	}

	function SendPaymentAcceptMailForRegisteredShareware($sender,$recipient,$data) {
		$language_id = 'en';
		$subject = '';
		$objProduct = new Product();

		$SOFTWARELINK1= BASEURL.'software-detail.php?product_id=7';
		$SOFTWARELINK2= BASEURL.'software-detail.php?product_id=8';
		$SOFTWARELINK3= BASEURL.'software-detail.php?product_id=9';

		$SOFTWAREIMAGE1= EMAIL_IMAGE_PATH.'/lover.png';
		$SOFTWAREIMAGE2= EMAIL_IMAGE_PATH.'/yearreport.png';
		$SOFTWAREIMAGE3= EMAIL_IMAGE_PATH.'/calender.png';

		$SOFTWARETEXT1= 'Astrology for Lovers Report';
		$SOFTWARETEXT2= 'Essential Year Ahead Report';
		$SOFTWARETEXT3= 'Astrology Calendar Report';

		$FACEBOOKLINK = 'http://www.facebook.com/MyAstropage?ref=hl';

		if(isset($data['language_id'])) {
			if($data['language_id'] == 'English' || $data['language_id'] == 'en') {
				$language_id = 'en';
			}
			else if($data['language_id'] == 'Danish' || $data['language_id'] == 'dk') {
				$language_id = 'dk';
			}
			else {
				$language_id = 'en';
			}
		}

		$TESTIMONIALSLINK = 'testimonials/astropage/'.$language_id."/";

		$subject = $data['subject'];
		
		$objEmailTemplate = new emailTemplate();
		$result = $objEmailTemplate->GetEmailTemplateById(REGISTERED_SHAREWARE_EMAIL_TEMPLATE, $language_id);

		if(count($result)>0) {
			//$str = $result['content'];
			$str = html_entity_decode( utf8_decode($result['content']));
			$subject = ((isset($result['subject']) && trim($result['subject']) != "") ? stripslashes(html_entity_decode( utf8_decode($result['subject']))) : "");
		}
		else {
			$str = "Dear ##NAME, <br />";
			$str .= "Thank you for ordering Registered Shareware version of “##PRODUCTNAME”.<br />";
			$str .= 'You will receive two more e-mails in your inbox. Please add our e-mail address to your address book, in order to make sure that you receive important e-mails from us in your inbox. <br />
					Instruction E-mail<br />
					This e-mail will have instructions regarding how to download, install and activate “##PRODUCTNAME”. All you need to know, to make the software up and running.<br />
					This e-mail will also have a download link, through which you will be able to download a trial version of the software. Once you download the trial version, you will have to enter the license key to activate the software.<br />
					License Key E-mail<br />
					This e-mail will be sent to you within next 48 hours with a license key in it. Insert the license key into the software to activate it.<br />
					If you have any questions, concerns or feedback, please contacts us at support@astrowow.com<br />
					In the mean time, you may want to login to your Astropage and subscribe for “3 Month Daily Personal Horoscope Calendar”, which is based on your birth planets and is unique to you. You can now get a clear guidance on how to plan each day in advance according to the astrological events happening in your life.<br />';

			$str .= "To ensure you continue to receive our daily horoscope, be sure to add our email id to your contact list or address book.<br />
					Remember, you can unsubscribe anytime you want! –Thanks<br />
					Astrowow.com<br />
					To learn about our email privacy polices click here.<br />
					This email was sent to ##USEREMAILID . You are receiving it because you signed up at astrowow.com or otherwise requested to be<br />
					included in our mailings.<br />
					Astrowow.com";
		}

		if(isset($data['product_id'])) {
			if($data['product_id'] == 7) {
				$SOFTWARELINK1= BASEURL.'software-detail.php?product_id=8';
				$SOFTWARELINK2= BASEURL.'software-detail.php?product_id=9';
				$SOFTWARELINK3= BASEURL.'software-detail.php?product_id=7';

				$result = $objProduct->GetProductDetailsByProductId(8, $language_id);

				if(count($result) >0) {
					$SOFTWARETEXT1= $result[0]['productName'];
					$SOFTWAREIMAGE1= EMAIL_IMAGE_PATH.'/horoscope-interpreter.jpg';
				}

				unset($result);
				$result = $objProduct->GetProductDetailsByProductId(9, $language_id);
				if(count($result) >0) {
					$SOFTWARETEXT2= $result[0]['productName'];
					$SOFTWAREIMAGE2= EMAIL_IMAGE_PATH.'/lover.png';
				}

				unset($result);
				$result = $objProduct->GetProductDetailsByProductId(7, $language_id);

				if(count($result) >0) {
					$SOFTWARETEXT3= $result[0]['productName'];
					$SOFTWAREIMAGE3= EMAIL_IMAGE_PATH.'/calender.png';
				}

				$TESTIMONIALSLINK = 'testimonials/software/horoscope-interpreter/'.$language_id."/";

			}
			else if($data['product_id'] == 8) {
				$SOFTWARELINK1= BASEURL.'software-detail.php?product_id=7';
				$SOFTWARELINK2= BASEURL.'software-detail.php?product_id=9';
				$SOFTWARELINK3= BASEURL.'software-detail.php?product_id=8';

				$result = $objProduct->GetProductDetailsByProductId(7, $language_id);

				if(count($result) >0) {
					$SOFTWARETEXT1= $result[0]['productName'];
					$SOFTWAREIMAGE1= EMAIL_IMAGE_PATH.'/horoscope-interpreter.jpg';
				}

				unset($result);
				$result = $objProduct->GetProductDetailsByProductId(9, $language_id);
				if(count($result) >0) {
					$SOFTWARETEXT2= $result[0]['productName'];
					$SOFTWAREIMAGE2= EMAIL_IMAGE_PATH.'/lover.png';
				}

				unset($result);
				$result = $objProduct->GetProductDetailsByProductId(8, $language_id);
				if(count($result) >0) {
					$SOFTWARETEXT3= $result[0]['productName'];
					$SOFTWAREIMAGE3= EMAIL_IMAGE_PATH.'/calender.png';
				}

				$TESTIMONIALSLINK = 'testimonials/software/astrology-for-lovers/'.$language_id."/";
			}
			else if($data['product_id'] == 9) {
				$SOFTWARELINK1= BASEURL.'software-detail.php?product_id=7';
				$SOFTWARELINK2= BASEURL.'software-detail.php?product_id=8';
				$SOFTWARELINK3= BASEURL.'software-detail.php?product_id=9';

				$result = $objProduct->GetProductDetailsByProductId(7, $language_id);

				if(count($result) >0) {
					$SOFTWARETEXT1= $result[0]['productName'];
					$SOFTWAREIMAGE1= EMAIL_IMAGE_PATH.'/horoscope-interpreter.jpg';
				}
				unset($result);
				$result = $objProduct->GetProductDetailsByProductId(8, $language_id);
				if(count($result) >0) {
					$SOFTWARETEXT2= $result[0]['productName'];
					$SOFTWAREIMAGE2= EMAIL_IMAGE_PATH.'/lover.png';
				}

				unset($result);
				$result = $objProduct->GetProductDetailsByProductId(9, $language_id);
				if(count($result) >0) {
					$SOFTWARETEXT3= $result[0]['productName'];
					$SOFTWAREIMAGE3= EMAIL_IMAGE_PATH.'/calender.png';
				}

				$TESTIMONIALSLINK = 'testimonials/software/hastrological-calendar/'.$language_id."/";
			}
		}

		if($str != "") {
			$str = str_replace("##SITELINK", BASEURL, $str);
			$str = str_replace("##NAME", $data['first_name'], $str);
			$str = str_replace("##PRODUCTNAME", $data['product_name'], $str);
			$str = str_replace("##BASEURL", EMAIL_IMAGE_PATH, $str);
			$str = str_replace("##USEREMAILID", $data['email_id'], $str);
			$str = str_replace("##FACEBOOKLINK", $FACEBOOKLINK, $str);
			$str = str_replace("##TESTIMONIALSLINK", BASEURL.$TESTIMONIALSLINK, $str);

			$str = str_replace("##SOFTWARELINK1", $SOFTWARELINK1, $str);
			$str = str_replace("##SOFTWARELINK2", $SOFTWARELINK2, $str);
			$str = str_replace("##SOFTWARELINK3", $SOFTWARELINK3, $str);

			$str = str_replace("##SOFTWAREIMAGE1", $SOFTWAREIMAGE1, $str);
			$str = str_replace("##SOFTWAREIMAGE2", $SOFTWAREIMAGE2, $str);
			$str = str_replace("##SOFTWAREIMAGE3", $SOFTWAREIMAGE3, $str);

			$str = str_replace("##SOFTWARETEXT1", $SOFTWARETEXT1, $str);
			$str = str_replace("##SOFTWARETEXT2", $SOFTWARETEXT2, $str);
			$str = str_replace("##SOFTWARETEXT3", $SOFTWARETEXT3, $str);
		}

		$to = '';
		if(count($recipient)>0) {
			$to = $recipient['to'];
		}

		$body = $str;
		//exit;

		$subject = $data['subject'];
		
		//        $transport = Swift_SmtpTransport::newInstance('aspmx.l.google.com', 25)
		//                ->setUsername('ntech.n.ntech@gmail.com')
		//                ->setPassword('ntechpassn');

		$transport = Swift_SmtpTransport::newInstance('mail.astrowow.com', 25)
		->setUsername('services@astrowow.com')
		->setPassword('ard6969ag');

		$mailer = Swift_Mailer::newInstance($transport);

		$message = Swift_Message::newInstance()
		->setSubject($subject)
		->setFrom(array("no-reply@astrowow.com" => "Astrowow-Team"))
		->setTo($recipient['to'])
		->setBody($body)
		->addPart($body, 'text/html');

		//$message->addBcc('parmaramit1111@gmail.com', 'Amit Parmar');

		// Send the message
		try {
			$result = $mailer->send($message);
		}catch (Exception $ex) {
		}

		//exit;
		return $result;
	}

	function SendLoverReportNotification($LoverReportOrderId) {
		try {
			$emailBody =  '<html><body>
					<table nrules="all" style="border-color: #666;" cellpadding="10">';

			$objOrd = new Order();

			$DeliveryType = 1;
			$ordResult = $objOrd->GetOrderDetailByOrderId($LoverReportOrderId);

			foreach($ordResult as $ordItem) {
				$emailBody .= "<tr style='background: #eee;'><td colspan='2'><strong>Relationship report order detail :</strong> </td></tr>";
				$emailBody .= "<tr><td><strong>Order No :</strong> </td><td>" . strip_tags($ordItem['order_id']) . "</td></tr>";
				$emailBody .= "<tr><td><strong>Order Date :</strong> </td><td>" . strip_tags($ordItem['order_date']) . "</td></tr>";
				$emailBody .= "<tr><td><strong>Report Language :</strong> </td><td>" . strip_tags($ordItem['language_code']) . "</td></tr>";

				$DeliveryType = $ordItem['delivery_option'];

				$DeliveryOption  = "EMail";
				if($ordItem['delivery_option'] == 2) {
					$DeliveryOption  = "Printed";
				}
				$emailBody .= "<tr><td><strong>Report Delivery Method :</strong> </td><td>" . strip_tags($DeliveryOption) . "</td></tr>";
				$emailBody .= "<tr><td><strong>Email Address :</strong> </td><td>" . strip_tags($ordItem['email_id']) . "</td></tr>";
			}
			$ordTranResult = $objOrd->GetOrderTransactionDetailById($LoverReportOrderId);

			foreach($ordTranResult as $ordTranItem) {
				$emailBody .= "<tr style='background: #eee;'><td colspan='2'><strong>PayPal Transaction Id :</strong> </td></tr>";
				$emailBody .= "<tr><td><strong>Transaction Id:</strong> </td><td>" . strip_tags($ordTranItem['PaypalTxnID']) . "</td></tr>";
			}

			if($DeliveryType == 2) {
				$ordShippingResult = $objOrd->GetOrderShippingDetailByOrderId($LoverReportOrderId);

				foreach($ordShippingResult as $ordShipItem) {
					$emailBody .= "<tr style='background: #eee;' colspan='2'><strong>Send Report to below address : </strong></td></tr>";

					$emailBody .= "<tr><td><strong>Address :</strong> </td><td>" . strip_tags($ordShipItem['address_1']) . "</td></tr>";
					$emailBody .= "<tr></td><td>" . strip_tags($ordShipItem['address_2']) . "</td></tr>";
					$emailBody .= "<tr><td><strong>City :</strong> </td><td>" . strip_tags($ordShipItem['city']) . "</td></tr>";
					$emailBody .= "<tr><td><strong>State :</strong> </td><td>" . strip_tags($ordShipItem['state']) . "</td></tr>";
					$emailBody .= "<tr><td><strong>Country :</strong> </td><td>" . strip_tags($ordShipItem['country']) . "</td></tr>";
					$emailBody .= "<tr><td><strong>Zip Code :</strong> </td><td>" . strip_tags($$ordShipItem['postal_code']) . "</td></tr>";
					$emailBody .= "<tr><td><strong>Phone :</strong> </td><td>" . strip_tags($ordShipItem['phone']) . "</td></tr>";
				}
			}

			$ordLoveResult = $objOrd->GetLoversData($LoverReportOrderId);
			$acsStateList = new ACSStatelist ();

			$Index = 0;
			foreach ($ordLoveResult as $ordDataItem) {
				if($Index == 0) {
					$emailBody .= "<tr style='background: #eee;'><td colspan='2'><strong>Person 1 Information</strong></td></tr>";
				} else {
					$emailBody .= "<tr style='background: #eee;'><td colspan='2'><strong>Person 2 Information</strong></td></tr>";
				}

				$emailBody .= "<tr><td><strong>Name:</strong> </td><td>" . strip_tags($ordDataItem['person_name']) . "</td></tr>";
				$Gender = "Male";
				if(strip_tags($ordDataItem['gender']) == 0) {
					$Gender = "Female";
				}
				$emailBody .= "<tr><td><strong>Gender:</strong> </td><td>" . $Gender . "</td></tr>";
				$emailBody .= "<tr><td><strong>Date of Birth:</strong> </td><td>" . strip_tags($ordDataItem['day']) . '-' . strip_tags($ordDataItem['month']) . '-' . strip_tags($ordDataItem['year']) . "</td></tr>";
				if($ordDataItem['untimed'] == 1) {
					$emailBody .= "<tr><td><strong>Time of Birth::</strong> </td><td>Unknown Time</td></tr>";
				} else {
					$emailBody .= "<tr><td><strong>Time of Birth::</strong> </td><td>" . strip_tags($ordDataItem['hour']) . ':' . strip_tags($ordDataItem['minute']) . "</td></tr>";
				}

				$emailBody .= "<tr><td><strong>City ::</strong> </td><td>" . strip_tags($ordDataItem['place']) . "</td></tr>";
				$emailBody .= "<tr><td><strong>State/Country:</strong> </td><td>" . $acsStateList->getStateNameByAbbrev (strip_tags($ordDataItem['state'])) . "</td></tr>";
				$emailBody .= "<tr><td><strong>Longitude ::</strong> </td><td>" . $ordDataItem['longitude'] / 3600 . "</td></tr>";
				$emailBody .= "<tr><td><strong>Latitude ::</strong> </td><td>" . $ordDataItem['latitude'] / 3600 . "</td></tr>";

				$Index++;
			}
			$emailBody .=  '</table></body></html>';

			$transport = Swift_SmtpTransport::newInstance(SMTP_SERVER_NAME, SMTP_PORT_NO)
			->setUsername(SMTP_SERVER_USERNAME)
			->setPassword(SMTP_SERVER_PASSWORD);

			$mailer = Swift_Mailer::newInstance($transport);

// 			$to = array('ntech.corporate@gmail.com' => 'Dhara Masani');
// 			$cc = array('urvish.u@gmail.com' => 'Urvish Patel');
			$to = array('jette@rybak.dk' => 'Jette Rybak');
			
			//->setCc($cc)
			
			$message = Swift_Message::newInstance()
			->setSubject("New Astrology for Lover Report from Astrowow.com. Order No:: ". $LoverReportOrderId)
			->setFrom(array("no-reply@astrowow.com" => "Astrowow-Team"))
			->setTo($to)
			->setBody($emailBody)
			->addPart($emailBody, 'text/html');
			$message->addBcc('parmaramit1111@gmail.com', 'Amit Parmar');

			// Send the message
			return $result = $mailer->send($message);
		}
		catch(Exception $ex) {
			echo $ex->getMessage();
			return false;
		}
	}

	function SendThankSubscriptionEmail($recipient, $data) {
		$to = '';
		if(count($recipient)>0) {
			$to = $recipient['to'];
		}

		$language_id = 'en';
		$FACEBOOKLINK = 'http://www.facebook.com/MyAstropage?ref=hl';

		if(isset($data['language_id'])) {
			if($data['language_id'] == 'English' || $data['language_id'] == 'en') {
				$language_id = 'en';
			}
			else if($data['language_id'] == 'Danish' || $data['language_id'] == 'dk') {
				$language_id = 'dk';
			}
			else {
				$language_id = 'en';
			}
		}

		$subject = $data['subject'];
		$email = $data['email_id'];
		$first_name = $data['first_name'];
		$last_name = $data['last_name'];
		$subject = "Thank you very my for ordering monthly subscription";
		$body = '';

		$objEmailTemplate = new emailTemplate();
		$result = $objEmailTemplate->GetEmailTemplateById(SEND_PAYMENT_ACCEPT_MAIL_3_MONTH_CALENDAR, $language_id);

		if(count($result)>0) {
			$str =html_entity_decode(stripcslashes(utf8_decode($result['content'])));
			$subject = ((isset($result['subject']) && trim($result['subject']) != "") ? stripslashes(html_entity_decode( utf8_decode($result['subject']))) : "");

			$str = str_replace("##SITELINK", BASEURL, $str);
			$str = str_replace("##NAME", $first_name, $str);
			$str = str_replace("##BASEURL", EMAIL_IMAGE_PATH, $str);
			$str = str_replace("##USEREMAILID", $email, $str);
			$body = $str;
		}
			
		//$subject = 'Paypal IPN LOG -  = ' . $this->last_error . " = " . $this->ipn_response;
		$to = $email;

		$transport = Swift_SmtpTransport::newInstance(SMTP_SERVER_NAME, SMTP_PORT_NO)
		->setUsername(SMTP_SERVER_USERNAME)
		->setPassword(SMTP_SERVER_PASSWORD);

		$mailer = Swift_Mailer::newInstance($transport);

		$message = Swift_Message::newInstance()
		->setSubject($subject)
		->setFrom(array("no-reply@astrowow.com" => "Astrowow-Team"))
		->setTo($recipient['to'])
		->setBody($body)
		->addPart($body, 'text/html');
		// Send the message

		return $result = $mailer->send($message);
	}

	function SendInvitationToFriends($sender, $recipient, $data) {
		$language_id = 'en';

		if(isset($_COOKIE['language']) && !empty($_COOKIE['language'])) {
			$language_id = $_COOKIE['language'];
		}

		$objEmailTemplate = new emailTemplate();
		$result = $objEmailTemplate->GetEmailTemplateById(SEND_INVITATION_TO_FRIENDS, $language_id);

		$subject = $data['subject'];

		if(count($result)>0) {
			$str = html_entity_decode( utf8_decode(utf8_encode($result['content'])));
			$subject = ((isset($result['subject']) && trim($result['subject']) != "") ? stripslashes(html_entity_decode( utf8_decode($result['subject']))) : "");
				
			if($str != "") {
				$str = str_replace("##SITELINK", BASEURL, $str);
				$str = str_replace("##MEMBERNAME##", $data['InvitedMemberName'], $str);
				$str = str_replace("##USERNAME##", $data['InvitedBy'], $str);
				$str = str_replace("##IMAGEPATH##", $data['BASEURL'], $str);
				$str = str_replace("##MEMBEREMAIL##", $data['InvitedMemberEMail'], $str);
			}

			$to = '';
			if(count($recipient)>0) {
				$to = $recipient['to'];
			}
			$body = $str;

			$transport = Swift_SmtpTransport::newInstance('mail.astrowow.com', 25)
			->setUsername('services@astrowow.com')
			->setPassword('ard6969ag');

			$mailer = Swift_Mailer::newInstance($transport);

			$message = Swift_Message::newInstance()
			->setSubject($subject)
			->setFrom(array("no-reply@astrowow.com" => "Astrowow-Team"))
			->setTo($recipient['to'])
			->setBody($body)
			->addPart($body, 'text/html');

			// Send the message
			try {
				$result = $mailer->send($message);
			}catch (Exception $ex) {
			}

			//exit;
			return $result;
		}
	}

	/*
	 function SendSharewareDownloadLink($Sender, $Recipient, $data) {
	$language_id = 'en';

	if(isset($_COOKIE['language']) && !empty($_COOKIE['language'])) {
	$language_id = $_COOKIE['language'];
	}

	$subject = $data['subject'];

	$objEmailTemplate = new emailTemplate();
	$result = $objEmailTemplate->GetEmailTemplateById(SEND_SHAREWARE_DOWNLOAD_LINK, $language_id);

	if(count($result)>0) {

	$str = html_entity_decode( utf8_decode(utf8_encode($result['content'])));
	$subject = html_entity_decode( utf8_decode(utf8_encode($result['subject'])));
	if($str != "") {
	$str =  "Thank you for order to Register free astrowow software: ##PRODUCTNAME<br />";
	$str .=  "Download ##PRODUCTNAME software, please click on below noted url<br />";
	$str .=  "##DOWNLOADLINK";
	$str .= '  and you will receive license key in 48 hours';
	}

	if($str != "") {
	$str = str_replace("##SITELINK", BASEURL, $str);
	$str = str_replace("##PRODUCTNAME", $data['hdnProductName'], $str);
	$str = str_replace("##DOWNLOADLINK", $data['donwload_link'], $str);
	}

	$to = '';
	if(count($Recipient) > 0 ) {
	$to = $Recipient['to'];
	}

	$body = $str;

	$str = str_replace("##SITELINK", BASEURL, $str);
	$str = str_replace("##BASEURL", EMAIL_IMAGE_PATH, $str);
	$str = str_replace("##NAME", isset($data['first_name']) ? $data['first_name'] : "", $str);
	$str = str_replace("##PRODUCTNAME", isset($data['product_name']) ? $data['product_name'] : "", $str);
	$str = str_replace("##USEREMAILID", isset($data['email']) ? $data['email'] : "", $str);
	$str = str_replace("##FACEBOOKLINK", $FACEBOOKLINK, $str);
	$str = str_replace("##DOWNLOADLINK", $data['donwload_link'], $str);

	$str = str_replace("##BUYCDLINK", '', $str);
	$str = str_replace("##BUYSHAREWARELINK", '', $str);



	$transport = Swift_SmtpTransport::newInstance('mail.astrowow.com', 25)
	->setUsername('services@astrowow.com')
	->setPassword('ard6969ag');

	$mailer = Swift_Mailer::newInstance($transport);

	$message = Swift_Message::newInstance()
	->setSubject($subject)
	->setFrom(array("no-reply@astrowow.com" => "Astrowow-Team"))
	->setTo($Recipient['to'])
	->setBody($body)
	->addPart($body, 'text/html');

	// Send the message
	try {
	$result = $mailer->send($message);
	}catch (Exception $ex) {
	echo $ex->getCode() . " - " . $ex->getMessage();
	}

	//exit;
	return $result;
	}
	} */

	function SendSharewareDownloadLink($Sender, $Recipient, $data) {
		$language_id = 'en';
		$objProduct = new Product();
		$subject = '';
		if(isset($data['language_id'])) {
			$language_id = $data['language_id'];
		}

		if(isset($_COOKIE['language']) && !empty($_COOKIE['language'])) {
			$language_id = $_COOKIE['language'];
		}

		$subject = $data['subject'];

		$objEmailTemplate = new emailTemplate();
		$result = $objEmailTemplate->GetEmailTemplateById(SEND_SHAREWARE_DOWNLOAD_LINK, $language_id);

		if(count($result)>0) {

			$str = html_entity_decode( utf8_decode(utf8_encode($result['content'])));
			$subject = html_entity_decode( utf8_decode(utf8_encode($result['subject'])));
			if($str == "") {
				$str =  "Thank you for order to Register free astrowow software: ##PRODUCTNAME<br />";
				$str .=  "Download ##PRODUCTNAME software, please click on below noted url<br />";
				$str .=  "##DOWNLOADLINK";
				$str .= '  and you will receive license key in 48 hours';
			}

			if($str != "") {
				$str = str_replace("##SITELINK", BASEURL, $str);
				$str = str_replace("##PRODUCTNAME", $data['hdnProductName'], $str);
				$str = str_replace("##DOWNLOADLINK", $data['donwload_link'], $str);
			}

			$FACEBOOKLINK = 'http://www.facebook.com/MyAstropage?ref=hl';
			$TESTIMONIALSLINK = '';
			$SOFTWARELINK1 = '';
			$SOFTWARETEXT1 = '';
			$SOFTWAREIMAGE1 = '';
			$SOFTWARELINK2 = '';
			$SOFTWARETEXT2 = '';
			$SOFTWAREIMAGE2 = '';
			$SOFTWARELINK3 = '';
			$SOFTWARETEXT3 = '';
			$SOFTWAREIMAGE3 = '';

			$BUYSOFTWARELINK = '';
			$BUYSHAREWARELINK = '';

			if(isset($data['product_id'])) {
				if($data['product_id'] == 7) {
					$SOFTWARELINK1 = BASEURL.'software-detail.php?product_id=7';
					$SOFTWARELINK2 = BASEURL.'software-detail.php?product_id=8';
					$SOFTWARELINK3 = BASEURL.'software-detail.php?product_id=9';

					$BUYSOFTWARELINK = BASEURL.'software-detail.php?product_id=7&report_type=3';
					$BUYSHAREWARELINK = BASEURL.'software-detail.php?product_id=7&report_type=2';

					$result = $objProduct->GetProductDetailsByProductId(7,$language_id);

					if(count($result) >0) {
						$SOFTWARETEXT1= $result[0]['productName'];
						$SOFTWAREIMAGE1= EMAIL_IMAGE_PATH.'/horoscope-interpreter.jpg';
					}

					unset($result);
					$result = $objProduct->GetProductDetailsByProductId(8,$language_id);
					if(count($result) >0) {
						$SOFTWARETEXT2= $result[0]['productName'];
						$SOFTWAREIMAGE2= EMAIL_IMAGE_PATH.'/lover.png';
					}

					unset($result);
					$result = $objProduct->GetProductDetailsByProductId(9,$language_id);

					if(count($result) >0) {
						$SOFTWARETEXT2= $result[0]['productName'];
						$SOFTWAREIMAGE2= EMAIL_IMAGE_PATH.'/calender.png';
					}

					$TESTIMONIALSLINK = 'testimonials/software/horoscope-interpreter/'.$language_id."/";
				}
				else if($data['product_id'] == 8) {
					$SOFTWARELINK1= BASEURL.'software-detail.php?product_id=7';
					$SOFTWARELINK2= BASEURL.'software-detail.php?product_id=8';
					$SOFTWARELINK3= BASEURL.'software-detail.php?product_id=9';

					$BUYSOFTWARELINK = BASEURL.'software-detail.php?product_id=8&report_type=3';
					$BUYSHAREWARELINK = BASEURL.'software-detail.php?product_id=8&report_type=2';

					$result = $objProduct->GetProductDetailsByProductId(7,$language_id);

					if(count($result) >0) {
						$SOFTWARETEXT1= $result[0]['productName'];
						$SOFTWAREIMAGE1= EMAIL_IMAGE_PATH.'/horoscope-interpreter.jpg';
					}

					unset($result);
					$result = $objProduct->GetProductDetailsByProductId(8, $language_id);
					if(count($result) >0) {
						$SOFTWARETEXT2= $result[0]['productName'];
						$SOFTWAREIMAGE2= EMAIL_IMAGE_PATH.'/lover.png';
					}

					unset($result);
					$result = $objProduct->GetProductDetailsByProductId(9, $language_id);
					if(count($result) >0) {
						$SOFTWARETEXT3= $result[0]['productName'];
						$SOFTWAREIMAGE3= EMAIL_IMAGE_PATH.'/calender.png';
					}

					$TESTIMONIALSLINK = 'testimonials/software/astrology-for-lovers/'.$language_id."/";
				}
				else if($data['product_id'] == 9) {
					$SOFTWARELINK1= BASEURL.'software-detail.php?product_id=7';
					$SOFTWARELINK2= BASEURL.'software-detail.php?product_id=8';
					$SOFTWARELINK3= BASEURL.'software-detail.php?product_id=9';

					$BUYSOFTWARELINK = BASEURL.'software-detail.php?product_id=9&report_type=3';
					$BUYSHAREWARELINK = BASEURL.'software-detail.php?product_id=9&report_type=2';

					$result = $objProduct->GetProductDetailsByProductId(7,$language_id);

					if(count($result) >0) {
						$SOFTWARETEXT1= $result[0]['productName'];
						$SOFTWAREIMAGE1= EMAIL_IMAGE_PATH.'/horoscope-interpreter.jpg';
					}
					unset($result);
					$result = $objProduct->GetProductDetailsByProductId(8,$language_id);
					if(count($result) >0) {
						$SOFTWARETEXT2= $result[0]['productName'];
						$SOFTWAREIMAGE2= EMAIL_IMAGE_PATH.'/lover.png';
					}

					unset($result);
					$result = $objProduct->GetProductDetailsByProductId(9, $language_id);
					if(count($result) >0) {
						$SOFTWARETEXT3= $result[0]['productName'];
						$SOFTWAREIMAGE3= EMAIL_IMAGE_PATH.'/calender.png';
					}

					$TESTIMONIALSLINK = 'testimonials/software/astrological-calendar/'.$language_id."/";
				}
			}

			$to = '';
			if(count($Recipient) > 0 ) {
				$to = $Recipient['to'];
			}

			$body = $str;

			$str = str_replace("##SITELINK", BASEURL, $str);
			$str = str_replace("##BASEURL", EMAIL_IMAGE_PATH, $str);
			$str = str_replace("##NAME", isset($data['name']) ? $data['name'] : "", $str);
			$str = str_replace("##NAME", isset($data['first_name']) ? $data['first_name'] : "", $str);
			$str = str_replace("##PRODUCTNAME", $data['hdnProductName'], $str);
			$str = str_replace("##USEREMAILID", $to, $str);
			$str = str_replace("##USEREMAILID", isset($data['email']) ? $data['email'] : "", $str);
			$str = str_replace("##FACEBOOKLINK", $FACEBOOKLINK, $str);
			$str = str_replace("##TESTIMONIALSLINK", BASEURL.$TESTIMONIALSLINK, $str);
			$str = str_replace("##DOWNLOADLINK", $data['donwload_link'], $str);
			$str = str_replace("##SOFTWARELINK1", $SOFTWARELINK1, $str);
			$str = str_replace("##SOFTWARETEXT1", $SOFTWARETEXT1, $str);
			$str = str_replace("##SOFTWAREIMAGE1", $SOFTWAREIMAGE1, $str);

			$str = str_replace("##SOFTWARELINK2", $SOFTWARELINK2, $str);
			$str = str_replace("##SOFTWARETEXT2", $SOFTWARETEXT2, $str);
			$str = str_replace("##SOFTWAREIMAGE2", $SOFTWAREIMAGE2, $str);

			$str = str_replace("##SOFTWARELINK3", $SOFTWARELINK3, $str);
			$str = str_replace("##SOFTWARETEXT3", $SOFTWARETEXT3, $str);
			$str = str_replace("##SOFTWAREIMAGE3", $SOFTWAREIMAGE3, $str);

			$str = str_replace("##BUYCDLINK", $BUYSOFTWARELINK, $str);
			$str = str_replace("##BUYSHAREWARELINK", $BUYSHAREWARELINK, $str);

			$body = $str;

			$transport = Swift_SmtpTransport::newInstance('mail.astrowow.com', 25)
			->setUsername('services@astrowow.com')
			->setPassword('ard6969ag');

			$mailer = Swift_Mailer::newInstance($transport);

			$message = Swift_Message::newInstance()
			->setSubject($subject)
			->setFrom(array("no-reply@astrowow.com" => "Astrowow-Team"))
			->setTo($Recipient['to'])
			->setBody($body)
			->addPart($body, 'text/html');

			// Send the message
			try {
				$result = $mailer->send($message);
			}catch (Exception $ex) {
				echo $ex->getCode() . " - " . $ex->getMessage();
			}

			//exit;
			return $result;
		}
	}

	function SendEmailOnfeedback($sender,$recipient, $data) {
		$language_id = 'en';
		if(isset($data['language_id'])) {
			$language_id = $data['language_id'];
		}

		$FACEBOOK 		= 'http://www.facebook.com/MyAstropage?ref=hl';
		$TWEETER 		= 'https://twitter.com/AdrianDuncan8';
		$GOOGLEPLUS     = 'https://plus.google.com/106143888045600794106/posts';
		$YOUTUBE 		= 'http://www.youtube.com/watch?v=Ze8VwNle15I&feature=results_video';

		$objEmailTemplate = new emailTemplate();
		$result = $objEmailTemplate->GetEmailTemplateById(SEND_FEEDBACK_THANK_YOU_EMAIL, $language_id);
		$subject ='';
		$str = '';
		if(count($result)>0) {
			$subject = html_entity_decode($result['subject']);
			$str = html_entity_decode($result['content']);
		} else {
			$subject = $data['subject'];
		}

		$str = str_replace("##SITELINK", $data['sitelink'], $str);
		$str = str_replace("##USERNAME", isset($data['email']) ? trim($data['email']) : "", $str);
		$str = str_replace("##NAME", isset($data['name']) ? $data['name'] : "", $str);
		$str = str_replace("##BASEURL", EMAIL_IMAGE_PATH, $str);
		$str = str_replace("##FACEBOOK", $FACEBOOK, $str);
		$str = str_replace("##TWEETER", $TWEETER, $str);
		$str = str_replace("##GOOGLEPLUS", $GOOGLEPLUS, $str);
		$str = str_replace("##YOUTUBE", $YOUTUBE, $str);

		$to = '';
		if(count($recipient)>0) {
			$to = $recipient['to'];
		}

		$body = $str;

		$transport = Swift_SmtpTransport::newInstance(SMTP_SERVER_NAME, SMTP_PORT_NO)
		->setUsername(SMTP_SERVER_USERNAME)
		->setPassword(SMTP_SERVER_PASSWORD);

		$mailer = Swift_Mailer::newInstance($transport);

		$message = Swift_Message::newInstance()
		->setSubject($subject)
		->setFrom(array("no-reply@astrowow.com" => "Astrowow-Team"))
		->setTo($recipient['to'])
		->setBody($body)
		->addPart($body, 'text/html');

		// Send the message
		try {
			$result = $mailer->send($message);
		}
		catch(Exception $ex) {
		}
		return $result;

	}

	function SendEmailOnfeedbackForAdmin($sender,$recipient, $data ) {
		$str = "";
		$str .='<p>Feedback from user For astrowow.com </p> ';
		$str .='<p><b>User name :</b>  ##NAME</p>';
		$str .='<p><b>Email     :</b> ##EMAIL</p>';
		$str .='<p><b>Message   :</b> ##MESSAGE</p>';

		$str = str_replace("##NAME",$data['name'],$str);
		$str = str_replace("##EMAIL",$data['username'],$str);
		$str = str_replace("##MESSAGE",$data['message'],$str);

		//$to = 'ard@astrowow.com';
		$to = 'ard@world-of-wisdom.com';		
		$subject = $data['subject'];
		$body = $data['mailtext'].$str;

		$transport = Swift_SmtpTransport::newInstance(SMTP_SERVER_NAME, SMTP_PORT_NO)
		->setUsername(SMTP_SERVER_USERNAME)
		->setPassword(SMTP_SERVER_PASSWORD);

		$mailer = Swift_Mailer::newInstance($transport);

		$message = Swift_Message::newInstance()
			->setSubject("Feedback from the Astrowow.com")
			->setFrom(array("no-reply@astrowow.com" => "Astrowow-Team"))
			->setTo($to)
			->setBody($body)
			->addPart($body, 'text/html');
		$message->addBcc('pr@world-of-wisdom.com', 'Amit Parmar');
		// Send the message
		return $result = $mailer->send($message);
	}
	
	function SendPrintedReportNotification($PrintedReportOrderId) {
		try {
			$emailBody =  '<html><body>
					<table nrules="all" style="border-color: #666;" cellpadding="10">';
	
			$objOrd = new Order();
	
			$DeliveryType = 1;
			$ordResult = $objOrd->GetOrderDetailByOrderId($PrintedReportOrderId);
	
			foreach($ordResult as $ordItem) {
				$emailBody .= "<tr style='background: #eee;'><td colspan='2'><strong>Printed report detail :</strong> </td></tr>";
				$emailBody .= "<tr><td><strong>Order No :</strong> </td><td>" . strip_tags($ordItem['order_id']) . "</td></tr>";
				$emailBody .= "<tr><td><strong>Order Date :</strong> </td><td>" . strip_tags($ordItem['order_date']) . "</td></tr>";
				$emailBody .= "<tr><td><strong>Report Language :</strong> </td><td>" . strip_tags($ordItem['language_code']) . "</td></tr>";
	
				$DeliveryType = $ordItem['delivery_option'];
	
				$DeliveryOption  = "EMail";
				if($ordItem['delivery_option'] == 2) {
					$DeliveryOption  = "Printed";
				}
				$emailBody .= "<tr><td><strong>Report Delivery Method :</strong> </td><td>" . strip_tags($DeliveryOption) . "</td></tr>";
				$emailBody .= "<tr><td><strong>Email Address :</strong> </td><td>" . strip_tags($ordItem['email_id']) . "</td></tr>";
			}
	
			if($DeliveryType == 2) {
				$ordShippingResult = $objOrd->GetOrderShippingDetailByOrderId($PrintedReportOrderId);
	
				foreach($ordShippingResult as $ordShipItem) {
					$FullAddress = "";
					$FullAddress = strip_tags($ordShipItem['address_1']) . "<br />";
					$FullAddress .= strip_tags($ordShipItem['address_2']) . "<br />";
					$FullAddress .= strip_tags($ordShipItem['city']) . " " . strip_tags($ordShipItem['state']) . " - " . strip_tags($ordShipItem['postal_code'])  . ".<br />";
					$FullAddress .= strip_tags($ordShipItem['country']) . "<br />";
										
					$emailBody .= "<tr style='background: #eee;'><td colspan='2'><strong>Send Report to below address : </strong></td></tr>";
					$emailBody .= "<tr><td><strong>Address :</strong> </td><td>" . $FullAddress . "</td></tr>";
					
// 					$emailBody .= "<tr><td><strong>Address :</strong> </td><td>" . strip_tags($ordShipItem['address_1']) . "</td></tr>";
// 					$emailBody .= "<tr><td></td><td>" . strip_tags($ordShipItem['address_2']) . "</td></tr>";
// 					$emailBody .= "<tr><td><strong>City :</strong> </td><td>" . strip_tags($ordShipItem['city']) . "</td></tr>";
// 					$emailBody .= "<tr><td><strong>State :</strong> </td><td>" . strip_tags($ordShipItem['state']) . "</td></tr>";
// 					$emailBody .= "<tr><td><strong>Country :</strong> </td><td>" . strip_tags($ordShipItem['country']) . "</td></tr>";
// 					$emailBody .= "<tr><td><strong>Zip Code :</strong> </td><td>" . strip_tags($$ordShipItem['postal_code']) . "</td></tr>";
// 					$emailBody .= "<tr><td><strong>Phone :</strong> </td><td>" . strip_tags($ordShipItem['phone']) . "</td></tr>";
				}
			}
	
			$ordLoveResult = $objOrd->GetUserBirthDetailByOrderId($PrintedReportOrderId);
			$acsStateList = new ACSStatelist ();
	
			$Index = 0;
			foreach ($ordLoveResult as $ordDataItem) {
				$emailBody .= "<tr style='background: #eee;'><td colspan='2'><strong>Person Birth Information</strong></td></tr>";				
	
				$emailBody .= "<tr><td><strong>Name:</strong> </td><td>" . strip_tags($ordDataItem['first_name']) . " " . strip_tags($ordDataItem['last_name']) . "</td></tr>";
				$Gender = "Male";
				if(isset($ordDataItem['gender']) && strip_tags($ordDataItem['Gender']) == 0) {
					$Gender = "Female";
				}
				$emailBody .= "<tr><td><strong>Gender:</strong> </td><td>" . $Gender . "</td></tr>";
				$emailBody .= "<tr><td><strong>Date of Birth (DD-MM-YYYY):</strong> </td><td>" . strip_tags($ordDataItem['day']) . '-' . strip_tags($ordDataItem['month']) . '-' . strip_tags($ordDataItem['year']) . "</td></tr>";
				
				if($ordDataItem['untimed'] == 1 || strtolower( $ordDataItem['untimed'] ) == 'yes') {
					$emailBody .= "<tr><td><strong>Time of Birth::</strong> </td><td>Unknown Time</td></tr>";
				} else {
					$emailBody .= "<tr><td><strong>Time of Birth::</strong> </td><td>" . strip_tags($ordDataItem['hour']) . ':' . strip_tags($ordDataItem['minute']) . "</td></tr>";
				}
	
				$emailBody .= "<tr><td><strong>City ::</strong> </td><td>" . strip_tags($ordDataItem['place']) . "</td></tr>";
				$emailBody .= "<tr><td><strong>State/Country:</strong> </td><td>" . $acsStateList->getStateNameByAbbrev (strip_tags($ordDataItem['state'])) . "</td></tr>";
				$emailBody .= "<tr><td><strong>Longitude ::</strong> </td><td>" . $ordDataItem['longitude'] / 3600 . "</td></tr>";
				$emailBody .= "<tr><td><strong>Latitude ::</strong> </td><td>" . $ordDataItem['latitude'] / 3600 . "</td></tr>";
	
				$Index++;
			}
			$emailBody .=  '</table></body></html>';
	
			$transport = Swift_SmtpTransport::newInstance(SMTP_SERVER_NAME, SMTP_PORT_NO)
			->setUsername(SMTP_SERVER_USERNAME)
			->setPassword(SMTP_SERVER_PASSWORD);
	
			$mailer = Swift_Mailer::newInstance($transport);
	
 			$to = array('ard@world-of-wisdom.com');
 			$cc = array('parmaramit1111@gmail.com');
	
			$message = Swift_Message::newInstance()
				->setSubject("New Printed Report from Astrowow.com. Order No :: ". $PrintedReportOrderId)
				->setFrom(array("no-reply@astrowow.com" => "Astrowow-Team"))
				->setTo($to)
				->setCc($cc)
				->setBody($emailBody)
				->addPart($emailBody, 'text/html');
	
			// Send the message
			return $result = $mailer->send($message);
		}
		catch(Exception $ex) {
			echo $ex->getMessage();
			return false;
		}
	}
	
	function SendElitCustomerPaymentConfirmation($sender,$recipient, $data) {
	
		$language_id = 'en';
		if(isset($data['sitelink'])) {
			$language_id = $data['language_id'];
		}
	
		$subject = $data['subject'];
	
		$objEmailTemplate = new emailTemplate();
		$result = $objEmailTemplate->GetEmailTemplateById(ELITE_PAYMENT_CONFIRMATION_EMAIL_TEMPLATE_ID, $language_id);
		//print_r($data);
		if(count($result)>0) {
			$str = html_entity_decode($result['content']);
			$subject = ((isset($result['subject']) && trim($result['subject']) != "") ? stripslashes(html_entity_decode( utf8_decode($result['subject']))) : "");
		}
		else {
			$str = $data['mailtext'].'<img title="Astrowow.com" alt="Astrowow.com" src="'.BASEURL.'images/logo.png">';
	
			$str .='<p>Wellcome and Thank you for signup with Astrowow.com !</p> ';
			$str .='<p>Now you can sign with below noted username and password</p> ';
			$str .='<p>Username: ##USERNAME</p>';
			$str .='<p>Password: ##PASSWORD</p>';
			$str .='<p>##SITELINK</p>';
	
		}
		$str = str_replace("##SITELINK",$data['sitelink'],$str);
		$str = str_replace("##USERNAME",$data['username'],$str);
		$str = str_replace("##NAME",$data['name'],$str);
		$str = str_replace("##BASEURL",EMAIL_IMAGE_PATH,$str);	
	
		$to = '';
		if(count($recipient)>0) {
			$to = $recipient['to'];
		}
	
		$body = $str;
		$transport = Swift_SmtpTransport::newInstance(SMTP_SERVER_NAME, SMTP_PORT_NO)
										->setUsername(SMTP_SERVER_USERNAME)
										->setPassword(SMTP_SERVER_PASSWORD);
	
		$mailer = Swift_Mailer::newInstance($transport);
	
		$message = Swift_Message::newInstance()
		->setSubject($subject)
		->setFrom(array("no-reply@astrowow.com" => "Astrowow-Team"))
		->setTo($recipient['to'])
		->setBody($body)
		->addPart($body, 'text/html');
	
	
		try {
			$result = $mailer->send($message);
		}
		catch(Exception $ex) {
			//print_r($ex);
		}
		return $result;
	}

}
/*$message = Swift_Message::newInstance()
 ->setSubject('Your subject')
->setFrom(array('john@doe.com' => 'John Doe'))
->setTo(array('receiver@domain.org', 'other@domain.org' => 'A name'))
->setBody('Here is the message itself')
->addPart('<q>Here is the message itself</q>', 'text/html')
->attach(Swift_Attachment::fromPath('my-document.pdf'))
;*/
?>
