<?php ini_set("display_errors", 1);
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_COMPILE_ERROR);
		function getAstrologData($getData)
		{ 
			$getVar			= urlencode($getData." -YQ 0");
			//$getOutput		= file_get_contents("http://54.67.50.240/astro.php?q=".$getVar);
			//$getOutput		= file_get_contents("http://54.153.95.173/astro.php?q=".$getVar);
			$getOutput		= file_get_contents("http://52.52.17.200/astro.php?q=".$getVar);
			return $getOutput;
		}

		function getConfigPath()
		{
			include_once($_SERVER['DOCUMENT_ROOT']. '/astrowow/webroot/reports/config.php');

		}

        function getAcsatlasData($data) {
        	/*if( $_SERVER['REMOTE_ADDR'] != '103.254.97.14' ) {
	        	$username = 'astrowow';
	        	$password = 'astrowow$123';
	        	$ch = curl_init();                    // Initiate cURL
	        	$url = "astrowow.newsoftdemo.info/acs.php"; // Where you want to post data
	        	curl_setopt($ch, CURLOPT_URL,$url);
	        	curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
	        	curl_setopt($ch, CURLOPT_POST, true);  // Tell cURL you want to post something
	        	curl_setopt($ch, CURLOPT_POSTFIELDS, $data); // Define what you want to post
	        	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return the output in string format
	        	$output = curl_exec ($ch); //
	        	return $output;*/
	        /*}
        	if( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' ) {*/
        		$username = 'astrowow';
                $password = 'astrowow$123';
                $ch = curl_init();                    // Initiate cURL
                //$url = "54.153.95.173/acs_for_vendor.php"; /*"astrowow.newsoftdemo.info/acs.php";*/ // Where you want to post data
                $url = "52.52.17.200/acs_for_vendor.php"; /*"astrowow.newsoftdemo.info/acs.php";*/ // Where you want to post data
                curl_setopt($ch, CURLOPT_URL,$url);
                curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
                curl_setopt($ch, CURLOPT_POST, true);  // Tell cURL you want to post something
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data); // Define what you want to post
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return the output in string format
                $output = curl_exec ($ch);
                return $output;
	        //} 
        }
?>
