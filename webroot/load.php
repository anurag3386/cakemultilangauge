<?php 

	$url = "http://locationapi.world-of-wisdom.com/api/jsonrpc/server.php";
	
	$locations = array('del', 'mum', 'kol', 'pun', 'kar', 'gur', 'cha', 'bar', 'jal', 'amr', 'sri', 'tha');

	$loc = array_rand($locations,1);

	$data = array(
	    'id'      => '1',
	    'jsonrpc'    => '2.0',
	    'method'       => "getlocations",
	    'params' => array('DX', $locations[$loc] )
	);
	$data_string = json_encode($data);

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,            $url );
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
	curl_setopt($ch, CURLOPT_POST,           1 );
	curl_setopt($ch, CURLOPT_POSTFIELDS,     $data_string);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
	    'Content-Type: application/json',                                                                                
	    'Content-Length: ' . strlen($data_string))                                                                       
	);   
	$result = curl_exec ($ch);
	print_r($result);
	curl_close($ch);	
?>