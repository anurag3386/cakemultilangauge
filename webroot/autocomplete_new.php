<?php
	
	
	$data = array(
    'id'      => '1',
    'jsonrpc'    => '2.0',
    'method'       => "getlocations",
    'params' => array('DX', 'DEL')
	);
	
	$url = "http://locationapi.world-of-wisdom.com/api/jsonrpc/server.php";
	
	

        $content = json_encode($data);

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER,
                array("Content-type: application/json"));
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $content);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT ,10); 
		

        $json_response = curl_exec($curl);

        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if ( $status != 201 ) {
            //die("Error: call to URL $url failed with status $status, response $json_response, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl));
        }


        curl_close($curl);

        $response = json_decode($json_response, true);
		
		//mail("jitender@nethuesindia.com","test",'',print_r($response,1));
		
		$count = count($response['result']);
		
		$total_result = array();
		
		for($i=0;$i<$count;$i++)
		{
			$acsatlasid = $response['result'][$i]['acsatlasid'];
			$placename = $response['result'][$i]['placename'];
			$region = $response['result'][$i]['region'];
			
			$total_result[] = $placename."||".$acsatlasid."||".$region;
		}
		
		
		
		
		
		$results = array();
		$data_result = array();
		
		if($count > 0)
		{
			$counter = 0;
			
			
			
			// search colors
			foreach($total_result as $color)
			{
				
				$colora = explode("||",$color);
				// if it starts with 'part' add to results
				if( @strpos($colora[0], ucwords($_REQUEST['term'])) === 0 ){						
					$results[] = $color;
					
					$town = $colora[0].", ".$colora[2];
															
					$data_result[] = array(
						'label' => $colora[0],
						'value' => $colora[1],
						'region' => $colora[2]
					);
					
					
					$counter++;
				}
			}
			
			
			
			
			
		
		}
		
		
		
echo json_encode($data_result);
flush();
	