<?php use Cake\Routing\Router; ?>
<?php
	/**
	 * Returns the list monthly transit and aspect list with Icons and titles
	 * @param String $ApiKey = Api Key
	 * @param Int $UserId    = logged in user id
	 * @param Date $PDate    = [m-d-Y] format
	 */
	//function getMonthlyPOSNEGIcons($ApiKey, $PDate, $user_id) { //$PDate m-d-Y
	function predictionIcons ($ApiKey, $PDate, $user_id, $selectedLanguage, $op = 1) { //$PDate m-d-Y
		/*if( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' ) {
			echo $PDate; die;
		}*/
		
		$URL = BASEURL."users/mycalendarIcons?apikey=".$ApiKey."&date=".$PDate."&user=".$user_id."&language=".$selectedLanguage;
		if($op == 2) {
			$URL = BASEURL."users/mycalendarIconsPm?apikey=".$ApiKey."&date=".$PDate."&user=".$user_id."&language=".$selectedLanguage;
		}
		

		$ch = curl_init(); // Initiate cURL
		curl_setopt($ch, CURLOPT_URL,$URL);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return the output in string format
	    $output = curl_exec ($ch);

	    //if($op == 2) {
	    //if( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' ) {
	    	$out = unserialize(base64_decode($output));	

	    	$newFinalArr = array();
	    	$newFinalArr['Code'] = $out['Code'];
	    	$newFinalArr['Message'] = $out['Message'];

	    	$dateArr = explode('-', $PDate);
	    	$from_date = $dateArr[2].'-'.$dateArr[0].'-'.$dateArr[1];
	    	$calendar_end_date = date('Y-m-d', $_SESSION['calendar_end_date']);
	    
	    	foreach($out['DailyPredictionData'] as $key => $item) {
	    		if( $key >= $from_date && $key <= $calendar_end_date) {
	    			$newFinalArr['DailyPredictionData'][$key] = $item;
	    		}
	    	}
	    	return $newFinalArr;

	    /*}

	    if(curl_error($ch)) {
    		echo 'error:' . curl_error($ch);
		} else {
			return (unserialize(base64_decode($output)));
		}*/
	}
?>