<?php

/**
 * Class for quick and easy mailuse
 *
 * With this class you can easy send Mails to any amount of persons.
 * Text and HTML Mails can be send by proper settings. As well attachments
 * can be served as well.
 * This class is based on PEAR/Mail System. For more infomrmations see http://pear.php.net
 * You'll need from PEAR 2 packages:
 *	- Mail_mime  http://pear.php.net/package/Mail_Mime
 *  - Mail       http://pear.php.net/package/Mail
 *
 * This class can be used free without any limitations expect the PEAR licence.
 * 
 * @package mail
 * @version 1.0
 * @author Peter Valicek <Peter.Valicek@GMX.COM>
 *
 *
 *
 * Following shows an quick example
 * 
 * <code>
 * require_once 'genericMail.php';
 * $sender = "Superuser <testuser@example.com>";
 * $recipient = array(
 *          'to'			=> 'You <you@example.com>'
 *			['reply-to'		=> 'Me <me@example.com>'] ## optional
 *			['return-path'	=> 'Me <me@example.com>'] ## optional
 *          ['cc'  			=> 'He <he@example.com>',] ## optional
 *          ['bcc' 			=> 'She <she@example.com>'] ## optional
 *          ['precedence'   => 'normal'] ## optional
 * );
 * $data = array(
 *          'subject'       => "This is a testmail",
 *          'mailtext'      => "<b>hallo das ist ein Test</b>",
 *          ['type'         => "html"] ## optional
 *			['attachment'	=> array('/tmp/testfile.txt')] ##optional
 * );
 * genericMail::sendmail( $sender, $recipient, $data );
 *</code>
 */
 
class genericMail {
	
	/**
	 * Instance on PEAR::Mail_Mime
	 *
	 * @link http://pear.php.net/packages/Mail_Mime
	 * @access private
	 * @return object mailObject
	 */
	function _instanceMailMime() {
		require_once 'Mail/mime.php';
		
		/*
		 * linefeed
		 */
		$crfl = "\r\n";
		
		$mime_obj =& new Mail_mime( $crfl );
		
		if ( is_object( $mime_obj ) )
			return $mime_obj;
	}
	
	/**
	 * Instance of Mail
	 * @access private
	 * @return object $mail
	 */
	function _instanceMail() {
		require_once 'Mail.php';
		
		$params = array(
						'sendmail_path' => '/usr/lib/sendmail',
						'host'			=> 'localhost'
						);
		
		$mail_obj =& Mail::factory('sendmail', $params);
		
		return $mail_obj;
	}
	
	/**
	 * Construct Header of the Mail
	 *
	 * @access private
	 * @param string $sender Mailsender
	 * @param array $rec Recipient Data
	 * @param string $subject Mail subject
	 * @return array $headerInfo Return HeaderArray and recipientlist
	 */
	function _constructHeader( $sender, $recipient, $subject ) {
		$recipient_list = $recipient['to'];
		
		/*
		 * Set Mailsernde ( From )
		 * Set Recipient ( To )
		 * Set Mailsubject ( Subject )
		 */
		$header = array(
						'From'			=> $sender,
						'To'			=> $recipient['to'],
						'Subject'		=> $subject
						);
		
		/*
		 * if provided set Reply-To Address
		 */
		if ( isset($recipient['reply-to']) && $recipient['reply-to'] != '' )
			$header['Reply-To'] = $recipient['reply-to'];
		
		/*
		 * if provided set Return-Path Address
		 */
		if ( isset($recipient['return-path']) && $recipient['return-path'] != '' )
			$header['Return-Path'] = $recipient['return-path'];	
		
		/*
		 * if provided set Carbon Copy Address
		 */
		if ( isset($recipient['cc']) && $recipient['cc'] != '' ) { 
			$header['Cc'] = $recipient['cc'];
			$recipient_list .= ", ".$recipient['cc'];
		}
		
		/*
		 * if provided set Blind Carbon Copy Address
		 */
		if ( isset($recipient['bcc']) && $recipient['bcc'] != '' ) { 
			$header['Bcc'] = $recipient['bcc'];
			$recipient_list .= ", ".$recipient['bcc'];
		}
		
		/*
		 * if provided set Precedence Header
		 */
		if ( isset($recipient['precedence']) && $recipient['precedence'] != '' ) {
			$header['Precedence'] = $recipient['precedence'];
		} else {
			$header['Precedence'] = "normal";
		}
		
		$return = array('headers' => $header, 'recipient_list' => $recipient_list);

		if ( is_array($return) )
			return $return;
	}
	
	
	
	/**
	 * Sendmail Function
	 *
	 * This will finally send the Email to Recipient
	 * @access public
	 * @param string $sender [ email ]
	 * @param array $recipient [ to | cc | bcc | precedence ]
	 * @param array $data [ subject | mailtext | type ( html|txt ) | attachment ]
	 * @return bool
	 */
	function sendmail( $sender, $recipient, $data ) {
		/*
		 * Get mime object
		 */
		$mime = genericMail::_instanceMailMime();
		
		/*
		 * Get Headerdata
		 */
		$headers = genericMail::_constructHeader($sender, $recipient, $data['subject']);
		
		/*
		 * Check if we have attachments
		 */
		if ( isset($data['attachment']) ) {
			$attachment = $data['attachment'];
			if ( is_array( $attachment ) ) {
				foreach ( $attachment as $file ) {
					if ( is_file($file) && is_readable($file) )
						$mime->addAttachment($file);
				}
			} else {
				if ( is_file($attachment) && is_readable($attachment) )
					$mime->addAttachment($attachment);
			}
		}
		
		/*
		 * Check if mailtype is specified.
		 * By default we use Text ( TXT )
		 */
		if ( isset($data['type']) && strtoupper($data['type']) == 'HTML' ) {
			$mime->setHTMLbody($data['mailtext']);
		} else {
			$mime->setTxtbody($data['mailtext']);
		}
		
		/*
		 * Creaty Body of the Mail
		 */
		$body = $mime->get();
		
		/*
		 * Set correct Headers
		 */
		$hdrs = $mime->headers($headers['headers']);
		
		/*
		 * Get Mail Object
		 */
		$mail_object = genericMail::_instanceMail();
		
		/*
		 * Send Mail
		 */
		if ( $mail_object->send( $headers['recipient_list'], $hdrs, $body ) )
			return true;
	}
	
	
}

?>