<?php

ini_set("display_errors", 1);
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
error_reporting ( E_ALL );

/* path definitions */
if (!defined ( 'ROOTPATH' )) {
	//define('ROOTPATH', '/var/www/astrowow/');
	//define('ROOTPATH', '/home/astronew/public_html/webroot/reports');
	define('ROOTPATH', '/var/www/reports/');
}

define ( 'CLASSPATH', ROOTPATH . '/classes' );
define ( 'LIBPATH', ROOTPATH . '/lib' );
define ( 'SPOOLPATH', ROOTPATH . '/var/spool' );

require_once (CLASSPATH. '/PDO.config.inc.php');
// FOR PARTNER DEVELOPERS
// You will need to have your certificates installed for any partner-specific calls to work.
// The cert simply needs to be accessible by this code and its path added to lines 27 & 28 below.
// For administrative actions, like creating accounts and transferring credits, you should not use impersonate_user when calling login.
// Rather, impersonate_user should only be called when making calls on behalf of a subaccount: creating emails, lists, getting reporting, etc.
// 
// FOR EVERYONE
// This code will take you through creating a list and campaign, associating the two, and launching the campaign.
// There are several section that you can update, including the email address we send the email to and the HTML content. 
// The only section you MUST update is the username and password below.

date_default_timezone_set('America/Los_Angeles'); // Timezone options are helpfully collected here: http://www.php.net/manual/en/timezones.php.

// ***** UPDATE LOGIN CREDENTIALS HERE ******
$username = "ard@world-of-wisdom.com";
$password = "ard6969ag"; //ard6969wow
$test_email = ''; // Update with your email address if you don't want to test emails going to the account email address.

$wsdl = "https://api.verticalresponse.com/wsdl/1.0/VRAPI.wsdl"; //location of the wsdl
$ses_time = 20;  // duration of session in minutes

// If you are using the Partner API, uncomment these lines and replace "Certificate_Path" with the path to your .pem certificate
// $cert = "*.pem"; // location of the .pem certificate
// $cert_pass = ""; // passphrase for .pem certificate
die('retretert');
// We use the default PHP soap client (http://php.net/manual/en/class.soapclient.php). 
$vr = new SoapClient($wsdl, 
	 array (
	        // 'local_cert' => $cert,
	        // 'passphrase' => $cert_pass
	        )
			);

try{
	
	echo("Starting API sample code\n");
	

	// Logging in! This call returns a session ID which we'll use in all subsequent calls. 

	echo("Logging in as $username\n");
	$sid = $vr->login(
	    array(
	          'username' => $username,
	          'password' => $password,
	          'session_duration_minutes' => $ses_time
	          )
	    );
	echo '<pre>'; print_r($sid); die;
	echo("Session ID: $sid\n");
	
	// Create a new list. This method returns the list ID, which we'll use to edit the list and attach it to the campaign.  

	echo("Creating a list...\n");

	$list_name = "API List: " . time() ; 

	$lid = $vr->createList(
	    array(
	        'session_id' => $sid,
	        'name' => $list_name,
	        'type' => "email",
	        'custom_field_names' => array()
	        )
   	    );
	echo("List created! List ID: $lid\n");

	// Let's add a contact to that test list. We're sending this to your account email address - update line 22 if you want to add a different email address. 
	// Most of the time you will be adding list members using the method appendFileToList, since looping over addListMember can cause significant lag.
	// Check out the docs at http://developers.verticalresponse.com for a code snippet using appendFileToList.

	$list_member =  array( 
	    'session_id'  => $sid, 
	    'list_member' => array(
	        'list_id' => $lid,						       
	        'member_data' => array(                                                             
	            array(
	                  'name' => "email_address",
	                  'value' => $test_email,
	                  ),
	            array(
	                  'name' => 'first_name',
	                  'value' => 'API',
	                  ),
	            array(
	                  'name' => 'last_name',
	                  'value' => 'User',
	                  )
	            )
        )
     );

	echo("Adding a list member...\n");
	$one = $vr->addListMember($list_member);		  
	echo("Added $test_email to list $lid\n");
	
    // Now for creating a test message. You can replace any of the content with your own.

	$email = array( 
		'name'          	=> "API Test " . time(),
		'email_type'    	=> "freeform",
		'from_label'    	=> "VerticalResponse API",
		'reply_to_email' 	=> $test_email,
		'freeform_html'		=> '<html><head></head><body>
					               <h1>Hello, World!</h1>
					               <br>
					               <b>This HTML email was created using the VerticalResponse API PHP sample code.</b>
					         	</body></html>',
		'freeform_text' 	=> 'Hello, World! This text email was created using the VerticalResponse API PHP sample code.',
		'subject' 			=> "VerticalResponse API Test " . time(),
		'unsub_text' 		=> "If you longer wish to receive our emails, please click",
		'unsub_link' 		=> "Unsubscribe",
		'send_friend'   	=> 'True',
		'hosted_email'		=> 'True',
		'previewed_html'	=> 'True', // You should set previewed_html and previewed_text to false only if your users will use the VerticalResponse website to create their emails. 
        'previewed_text'	=> 'True', // In that case, they will preview & test via our site.
	);

	echo("Creating new email...\n");
	
	$cid = $vr->createEmail( 
		array(
			'session_id' => $sid,
			'email' => $email,
		     )
	);
	
	echo("Email created! Campaign id: $cid\n");

	// We'll now send a test of the email. You will receive two copies: an HTML copy and a text copy. The actual launched campaign will
	// only go out once to each recipient; whether they receive the HTML or text version will depend on their email settings.

	$test_recipient = array(
	    array(
	          'name'  => "email_address",
	          'value' => $test_email,
	          )
	    );

	echo("Sending email campaign test...\n");
	
	$vr->sendEmailCampaignTest(
	    array(
	          'session_id' => $sid,
	          'campaign_id' => $cid,
	          'recipients'  => array($test_recipient)
	          )				    				     
	    );
	
	echo("Test emails sent to $test_email\n");

	// This attaches the list you made above to the campaign you just created. You can send an email to more than one list.	
	
	echo("Setting email campaign lists...\n");
	
	$vr->setCampaignLists( 
	     array(
	           'session_id' => $sid,
	           'campaign_id' => $cid,
	           'list_ids' => array ($lid)
	           )
	      );
              
	// Finally, let's launch the campaign! This will send the campaign to the VerticalResponse campaign checking team.
	// They make sure that all campaigns that we send out comply with US federal law and our terms of service.
	// If the campaign is declined, you'll receive an email at your log in email address explaining why.

	echo("Launching email campaign...\n"); 

	$lec = $vr->launchEmailCampaign(
	                                array (
	                                       'session_id' => $sid,
	                                       'campaign_id' => $cid
	                                       )
                                
	                                );

	echo("Campaign launched! Check your email.\n");

}catch(SoapFault $exception){
  echo 'fault: "' . $exception->faultcode . '" - ' . $exception->faultstring . "\n";
}

// Here's a helper function for putting the contents of a file into a string.
// It's not really a part of the API, per se, but you might find it helpful for loading your message content.

function getContents($file_name){
  $info = "";
  if (file_exists($file_name)){
    $handler = fopen($file_name,'r');
    while(!feof($handler)){
      $info .= fgets($handler, 1024);
    }
    fclose($handler);
  }
  return $info;
}
























//Error message :
/*Starting API sample code Logging in as ard@world-of-wisdom.com 
Fatal error: Uncaught SoapFault exception: [SOAP-ENV:VRAPI.InvalidLogin] The username or password supplied was incorrect. in /home/world-of-wisdom/public_html/bin/vertical-response/vr-import-api-kk.php:45 Stack trace: #0 /home/world-of-wisdom/public_html/bin/vertical-response/vr-import-api-kk.php(45): SoapClient->__call('login', Array) #1 /home/world-of-wisdom/public_html/bin/vertical-response/vr-import-api-kk.php(45): SoapClient->login(Array) #2 {main} thrown in /home/world-of-wisdom/public_html/bin/vertical-response/vr-import-api-kk.php on line 45*/




?>
