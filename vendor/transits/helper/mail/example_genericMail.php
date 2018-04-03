<?php

/***************************
 * Example for genericMail *
 ***************************/

/*
 * Include Class
 */
require_once 'class_genericMail.php';

/*
 * Set Mailsender
 */
$sender = "Superuser <root@example.com>";

/*
 * Set recipient
 */
$rec = array(
	'to'	=> 'you@example.com, He <he@example.com>',
	);

/*
 * Set Maildata
 */
$data = array(
	'subject'	=> "This is a testmail",
	'mailtext'	=> "<h1>Welcome to genericMail.</h1>",
	'type'		=> "html",
	'attachment'	=> array( 'testfile.html' )
	);

/*
 * Send the Mail
 */
if ( genericMail::sendmail( $sender, $rec, $data ) ) {
	print "Mail was send successfull ... \n";
} else {
	print "Hmm .. there could be a Problem ... \n";
}

?>
