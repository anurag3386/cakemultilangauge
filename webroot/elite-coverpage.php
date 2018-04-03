<?php
	ini_set('display_errors', 1);
	error_reporting(E_ALL);
	set_include_path("/var/www/html/webroot/phpseclib");
	include_once('/var/www/html/webroot/phpseclib/Net/SFTP.php');

	$servername = "astrowow-mysql.cwlq0bny8ltt.us-west-1.rds.amazonaws.com";
	$username = "awswow";
	$password = "Main4itl123";
	$database = "astronew_db";

	// Create connection
	$conn = new mysqli($servername, $username, $password, $database);
	// Check connection
	if ($conn->connect_error) {
    	die("Connection failed: " . $conn->connect_error);
	} 

	$sql = "SELECT user_id, cover_page FROM elite_members WHERE status = 1 && cover_page != '' ";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
	    while($row = $result->fetch_assoc()) {
	        if (transferFile($row['user_id'].'.pdf')) {
	        	$sql1 = "UPDATE elite_members SET status = 0 WHERE user_id = ".$row['user_id'];
	        	$conn->query($sql1);
	        } else {
	        	continue;
	        }
	    }
	}



	function transferFile ($fileName) {
   	    $local_file_path  = '/var/www/html/webroot/uploads/elite-user-coverPage/'.$fileName;
   	    $remote_file_path = '/var/www/uploads/elite-user-coverPage/'.$fileName;
		//$sftp = new Net_SFTP('54.153.95.173');
		$sftp = new Net_SFTP('52.52.17.200');
		if (!$sftp->login('webdev', 'nethues123')) {
			print_r($sftp->getSFTPErrors());
		    exit('Login Failed');
		} else {
     	    $output = $sftp->put($remote_file_path, $local_file_path, NET_SFTP_LOCAL_FILE);
            if( $output )
            	return true; //echo "Succefully Copied";
         	else
       			return false; //echo "Some Error Occured";
        }
    }

?>