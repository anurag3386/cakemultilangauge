<?php

if( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' ) {
	/*echo '<pre>'; print_r($_SERVER); die;
	if($_SERVER['REQUEST_URI'] == '/dk/sol-skilte/v%C3%A6dder/daglig-horoskop') {
    	header("HTTP/1.1 301 Moved Permanently");
		header("Location: https://www.astrowow.com/users/sign-up");
		exit;
	}*/
	if($_SERVER['REQUEST_URI'] == '/dk/sol-skilte/gratis-horoskop') {
    	header("HTTP/1.1 301 Moved Permanently");
		header("Location: https://www.astrowow.com/dk/soltegn/gratis-horoskop");
		exit;
	}

	if ((strpos($_SERVER['REQUEST_URI'], '/dk/sol-skilte/') !== false) && ($_SERVER['REQUEST_URI'] != '/dk/sol-skilte/gratis-horoskop')) {
		$sunSigns = array();
	    $sunSigns['aries'] = 'vædder';
	    $sunSigns['taurus'] = 'tyr';
	    $sunSigns['gemini'] = 'tvilling';
	    $sunSigns['cancer'] = 'krebs';
	    $sunSigns['leo'] = 'løve';
	    $sunSigns['virgo'] = 'jomfru';
	    $sunSigns['libra'] = 'vægt';
	    $sunSigns['scorpio'] = 'skorpion';
	    $sunSigns['sagittarius'] = 'skytte';
	    $sunSigns['capricorn'] = 'stenbuk';
	    $sunSigns['aquarius'] = 'vandbærer';
	    $sunSigns['pisces'] = 'fisk';

	    $scope['daily-horoscope'] = 'daglig-horoskop';
	    $scope['weekly-horoscope'] = 'ugentlig-horoskop'; 
	    $scope['monthly-horoscope'] = 'månedligt-horoskop'; 
	    $scope['yearly-horoscope'] = 'årlig-horoskop'; 
	    $scope['characteristics'] = 'egenskaber'; 
	    $scope['celebrity'] = 'berømthed'; 
	    $scope['archive'] = 'arkiv';

	    $requestParams = explode('/', $_SERVER['REDIRECT_URL']);
	    
	    foreach( $scope as $k => $period) {
	    	if($period == $requestParams[5]){
	    		foreach ($sunSigns as $key => $sunsign) {
	    			if($sunsign == $requestParams[4]){
	    				$rUrl = 'https://www.astrowow.com/dk/soltegn/'.$sunsign.'/'.$period;
	    				header("HTTP/1.1 301 Moved Permanently");
						header("Location: ".$rUrl);
						exit;
	    			}
	    		}
	    	}
    	}
	}

	/*if($_SERVER['REQUEST_URI'] == '/dk/sol-skilte/gratis-horoskop') {
    	header("HTTP/1.1 301 Moved Permanently");
		header("Location: https://www.astrowow.com/dk/soltegn/gratis-horoskop");
		exit;
	}*/
}

//if($_SERVER['REMOTE_ADDR'] == '103.254.97.14') {
	//echo '<pre>'; print_r($_SERVER); die;
	if($_SERVER['REQUEST_URI'] == '/products/astrology/software?language=dk') {
		header("HTTP/1.1 301 Moved Permanently");
		header("Location: https://www.astrowow.com/dk/astrologi-software");
		exit;
	}
	
	if($_SERVER['REQUEST_URI'] == '/products/astrology/software' || $_SERVER['REQUEST_URI'] == '/products/astrology/software?language=en') {
		header("HTTP/1.1 301 Moved Permanently");
		header("Location: https://www.astrowow.com/astrology-software");
		exit;
	}

	if($_SERVER['REQUEST_URI'] == '/products/detail/astrology-for-lovers/software-cd/28?language=en') {
		header("HTTP/1.1 301 Moved Permanently");
		header("Location: https://www.astrowow.com/astrology-software/astrology-for-lovers/software-cd");
		exit;
	}

	if($_SERVER['REQUEST_URI'] == '/products/detail/interpreter-calendar-lovers/software-cd/66?language=en') {
		header("HTTP/1.1 301 Moved Permanently");
		header("Location: https://www.astrowow.com/astrology-software/interpreter-calendar-lovers/software-cd");
		exit;
	}

	if($_SERVER['REQUEST_URI'] == '/products/detail/interpreter-lovers/software-cd/62?language=en') {
		header("HTTP/1.1 301 Moved Permanently");
		header("Location: https://www.astrowow.com/astrology-software/interpreter-lovers/software-cd");
		exit;
	}

	if($_SERVER['REQUEST_URI'] == '/products/detail/interpreter-calendar/software-cd/63?language=en') {
		header("HTTP/1.1 301 Moved Permanently");
		header("Location: https://www.astrowow.com/astrology-software/interpreter-calendar/software-cd");
		exit;
	}

	if($_SERVER['REQUEST_URI'] == '/products/detail/calendar-lovers/software-cd/64?language=en') {
		header("HTTP/1.1 301 Moved Permanently");
		header("Location: https://www.astrowow.com/astrology-software/calendar-lovers/software-cd");
		exit;
	}

	if($_SERVER['REQUEST_URI'] == '/products/detail/astrology-calendar/software-cd/26?language=en') {
		header("HTTP/1.1 301 Moved Permanently");
		header("Location: https://www.astrowow.com/astrology-software/astrology-calendar/software-cd");
		exit;
	}

	if($_SERVER['REQUEST_URI'] == '/sun-signs/aries/characteristics?country-options=dk') {
		header("HTTP/1.1 301 Moved Permanently");
		header("Location: https://www.astrowow.com/dk/sol-skilte/vædder/egenskaber");
		exit;
	}
	if($_SERVER['REQUEST_URI'] == '/astrology-reports/astrology-calendar-report/full-reports/?country-options=en') {
		header("HTTP/1.1 301 Moved Permanently");
		header("Location: https://www.astrowow.com/astrology-reports/astrology-calendar-report/full-reports");
		exit;
	}

	if($_SERVER['REQUEST_URI'] == '/astrology-reports/astrology-calendar-report/full-reports/?country-options=dk') {
		header("HTTP/1.1 301 Moved Permanently");
		header("Location: https://www.astrowow.com/dk/astrologi-rapport/astrokalender-rapport/fuld-rapport");
		exit;
	}
	
	if($_SERVER['REQUEST_URI'] == '/astrology-reports/character-and-destiny-report/full-reports/?country-options=dk') {
		header("HTTP/1.1 301 Moved Permanently");
		header("Location: https://www.astrowow.com/dk/astrologi-rapport/karakter-og-skaebne-rapport/fuld-rapport");
		exit;
	}

	if($_SERVER['REQUEST_URI'] == '/free-astrology-reading/signup.php') {
    	header("HTTP/1.1 301 Moved Permanently");
		header("Location: https://www.astrowow.com/users/sign-up");
		exit;
	}

	/*if( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' ) {
		if($_SERVER['REQUEST_URI'] == '/free-astrology-reading/signup.php') {
	    	header("HTTP/1.1 301 Moved Permanently");
			header("Location: https://www.astrowow.com/users/sign-up");
			exit;
		}
	}*/





//}
?>