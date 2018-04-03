<?php

class WowMiniEmailDelivery_EN extends WowEmailDelivery_EN {
	
	var $CustomerName = '';
	var $ReportName = "Personal mini reading";
	var $Language = 'en';
	
	public function WowMiniEmailDelivery_EN($CurrentLanguage = 'en') {
		$this->WowEmailDelivery_EN ();
		$this->test_mode = false;
		$this->Language = $CurrentLanguage;
		if($this->Language  == "en") {
			$this->subject = "Your Astrological Report from Astrowow.com";
		} else {
			$this->subject = "Din astrologiske Rapport fra Astrowow.com";
		}
		$this->sender_address = "enquiries@Astrowow.com";
		$this->sender_name = "Astrowow.com";
		$this->return_path = "replies@Astrowow.com";
		
	}	
	
	private function createDeliveryNote() {
		/*
		$this->message_part = '<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
        			<tr><td><img src="http://www.astrowow.com/emailImages/email-header.jpg" width="601" height="81" /><br /><br /></td><tr>
        			<tr><td>Dear '.$this->CustomerName.',<br /><br /></td><tr>
        			<tr><td>Thank you for ordering '.$this->ReportName.' from Astrowow.<br /><br /></td><tr>
        			<tr><td>Your report is attached to this email.<br /><br /></td><tr>
        			<tr><td><strong>Best Wishes</strong><br /><br /></td><tr>
        			<tr><td><strong>Astrowow.com.</strong><br /></td><tr>
        			<tr><td><strong>With best wishes for the future - Your astrologer Adrian<strong><br /></td><tr>
        		</table>';
       	*/
		if($this->Language  == "en") {
			$this->message_part = '<html>
									<head></head>
									<body style="background-color:  #844AA3; color:#FFFFFF; pedding-left:10px;">
										<div style=" pedding-left:10px;">
											<p style=" text-align: center;"><img src="http://mini-report.astrowow.com/css/images/logo.png" /></p>
											<p>Dear '.$this->CustomerName.',</p>
											<p>Thanks for ordering your personal minihoroscope from Astrowow. You\'ll find it attached to this mail. Enjoy!.</p>
											<p><strong>Best Regards - your astrologer Adrian Duncan</strong><br /><br /></p>
										</div>
									</body>
									</html>';
		} else {
			$this->message_part = utf8_decode('<html>
									<head></head>
									<body style="background-color: #844AA3; color:#FFFFFF;">
										<div style=" pedding-left:10px;">
											<p style=" text-align: center;"><img src="http://mini-report.astrowow.com/css/images/logo.png" /></p>
											<p>Kære '.$this->CustomerName.',</p>
											<p>Tak for din bestilling af et personligt minihoroskop fra Astrowow. Find venligst rapporten vehæftet denne mail. Jeg håber du nyder den!</p>
											<p><strong>Med venlig hilsen - din astrolog Adrian Duncan</strong><br /><br /></p>
										</div>
									</body>
									</html>');
		}
	}
	
	
	public function send() {
		$this->createDeliveryNote ();
		$this->_send ();
	}
}