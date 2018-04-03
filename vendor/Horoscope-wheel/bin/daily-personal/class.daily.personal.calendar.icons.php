<?php
/**
 * This is the Initiater page for generating daily personal based on passed user Id
 *
 * @filesource : class.daily.personal.calendar.icons.php
 */

//ini_set("display_errors", 1);
//error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
ini_set("display_errors", 1);
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_COMPILE_ERROR);

date_default_timezone_set ( 'America/Los_Angeles' );

define( 'ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/' );
define ( 'CLASSPATH', ROOTPATH . '/classes' );
define ( 'LIBPATH', ROOTPATH . '/lib' );

require_once(ROOTPATH.'config.php');

$languageId = 'en';
if(isset($_COOKIE['language'])) {
	$languageId = $_COOKIE['language'];
}
if($languageId == "en") {
	require_once(ROOTPATH.'language/en/personal-daily/personal_daily.php');
} else if($languageId == "dk") {
	require_once(ROOTPATH.'language/dk/personal-daily/personal_daily.php');
} else {
	require_once(ROOTPATH.'language/en/personal-daily/personal_daily.php');
}

require_once(ROOTPATH.'bal/include.php');
require_once(ROOTPATH.'bal/user.php');
require_once(ROOTPATH.'dto/userDTO.php');

require_once(CLASSPATH . '/personal-daily/class.daily.personal.php');
require_once(CLASSPATH . '/personal-daily/daily.personal.api.php');
require_once(CLASSPATH . '/personal-daily/daily.personal.pipe.php');

/* POG data */
require_once (CLASSPATH . '/configuration.php');
require_once (CLASSPATH . '/objects/class.database.php');
require_once (CLASSPATH . '/objects/class.pog_base.php');

require_once (CLASSPATH . '/objects/class.state.php');
require_once (CLASSPATH . '/objects/class.transaction.php');
require_once (CLASSPATH . '/objects/class.portal.php');
require_once (CLASSPATH . '/objects/class.birthdata.php');          //POG CLASS ADDED
require_once (CLASSPATH . '/objects/class.reportoption.php');
require_once (CLASSPATH . '/objects/class.emailaddress.php');

/* ACS Atlas */
if (!class_exists('ACSStateList')) {
	if(!include(CLASSPATH . '/acs/class.acs.statelist.php')) {
		require_once (CLASSPATH . '/acs/class.acs.statelist.php');
	}
}
//Positive: Sextiles and trines by transit + conjunctions from SU, MO, ME, VE, JU, NN
$PositiveAspect = array(1000 => "SU",
		1001 => "MO",
		1002 => "ME",
		1003 => "VE",
		1005 => "JU",
		1010 => "NN",
		1012 => "AS");
//Negative: Squares and oppositions by transit + conjunctions from MA, SA, UR, NE, PL and SN
$NegativeAspect = array(1004 => "MA",
		1006 => "SA",
		1007 => "UR",
		1008 => "NE",
		1009 => "PL",
		1011 => "SN",
		1013 => "MC");

$IconPATH =  array(
		"AS_POS" => "/images/calendar-icons/AS_pos_Agendagreen.svg",						//  1
		"AS_NEG" => "/images/calendar-icons/AS_neg_Agendared.svg",							//	2
		
		"MC_POS" => "/images/calendar-icons/MC_pos_careergreen.svg",						//	3
		"MC_NEG" => "/images/calendar-icons/MC_neg_careerred.svg",							//	4
		
		"SU_POS" => "/images/calendar-icons/SU_pos_confidencegreen.svg",					//	5
		"SU_NEG" => "/images/calendar-icons/SU_neg_confidencered.svg",						//	6
		
		"MO_POS" =>	"/images/calendar-icons/MO_pos_Securitygreen.svg",						//	7
		"MO_NEG" => "/images/calendar-icons/MO_neg_Securityred.svg",						//	8
		
		"ME_POS" => "/images/calendar-icons/ME_pos_communicationsgreen.svg",				//	9
		"ME_NEG" => "/images/calendar-icons/ME_neg_communicationsegative.svg",				//	10
		
		"VE_POS" => "/images/calendar-icons/VE_pos_Lovegreen.svg",							//	11
		"VE_NEG" => "/images/calendar-icons/VE_neg_Lovered.svg",							//	12
		
		"MA_POS" => "/images/calendar-icons/MA_pos_energysexgreen.svg",						//	13
		"MA_NEG" => "/images/calendar-icons/MA_neg_energysexred.svg",						//	14
		
		"JU_POS" => "/images/calendar-icons/JU_pos_expensiongreen.svg",						//	15
		"JU_NEG" => "/images/calendar-icons/JU_neg_expansionred.svg",						//	16
		
		"SA_POS" => "/images/calendar-icons/SA_pos_obstaclesgreen.svg",						//	17
		"SA_NEG" => "/images/calendar-icons/SA_neg_obstaclesred.svg",						//	18
		
		"UR_POS" => "/images/calendar-icons/UR_pos_riskgreen.svg",							//	19
		"UR_NEG" => "/images/calendar-icons/UR_neg_riskred.svg",							//	20
		
		"NE_POS" => "/images/calendar-icons/NE_neg_yinyanggreen.svg",							//	21
		"NE_NEG" => "/images/calendar-icons/NE_neg_yinyangred.svg",						//	22
		
		"PL_POS" => "/images/calendar-icons/PL_pos_Transformationgreen.svg",				//	23
		"PL_NEG" => "/images/calendar-icons/PL_neg_Transformationred.svg",					//	24
		
		"NN_POS" => "/images/calendar-icons/NN_pos_contactsgreen.svg",						//	25
		"NN_NEG" => "/images/calendar-icons/NN_neg_contactsred.svg",						//	26
		
		"SN_POS" => "/images/calendar-icons/NN_pos_contactsgreen.svg",						//	25
		"SN_NEG" => "/images/calendar-icons/NN_neg_contactsred.svg",						//	26
);

$MothlyObject = array();

if(!isset ($_REQUEST['apikey']) && empty ($_REQUEST['apikey']) ) {
	$dPersonal = new DailyPersonal(1001, $_['UNAUTHORIEDACCESS']);
	echo json_encode($dPersonal);
	exit(0);
}
else {
	if(isset ($_REQUEST['userid']) && !empty ($_REQUEST['userid']) && isset ($_REQUEST['pDate']) && !empty ($_REQUEST['pDate'])  ) {
		$User_id = $_REQUEST['userid'];
		$pDate = $_REQUEST['pDate'];
		$checkDate = explode('-', $pDate);

		$userbirthDTO = new UserBirthDetailDTO();
		$userDetailDTO = new UserDTO();
		$user = new User();

		$userDetail =  $user->GetUserDetail($User_id);
		$result =  $user->GetUserBirthDetailByUserId($User_id);

		if(count($result) > 0 && count($userDetail) > 0) {
			$userDetailDTO->language = $userDetail[0]['DefaultLanguage'];
			$userDetailDTO->user_id = $User_id;
			$userDetailDTO->portal_id =  $userDetail[0]['PortalId'];

			$userbirthDTO->UserBirthDetailId  = $result[0]["UserBirthDetailId"];
			$userbirthDTO->UserId             = $result[0]["UserId"];

			//BIRTH DATE
			$userbirthDTO->Day        = $result[0]["Day"];
			$userbirthDTO->Month      = $result[0]["Month"];
			$userbirthDTO->Year       = $result[0]["Year"];
			$userbirthDTO->Hours      = $result[0]["Hours"];
			$userbirthDTO->Minutes    = $result[0]["Minutes"];
			$userbirthDTO->Seconds    = $result[0]["Seconds"];
			$userbirthDTO->GMT        = $result[0]["GMT"];
			$userbirthDTO->Lagitute   = $result[0]["Lagitute"];
			$userbirthDTO->Longitute  = $result[0]["Longitute"];
			$userbirthDTO->unTimed    = $result[0]["unTimed"];

			$userbirthDTO->ZoneRef    = $result[0]["ZoneRef"];
			$userbirthDTO->SummerTimeZoneRef = $result[0]["SummerTimeZoneRef"];

			$userbirthDTO->country      = $result[0]["country"];
			$userbirthDTO->state        = $result[0]["state"];
			$userbirthDTO->city         = $result[0]["city"];
			$userbirthDTO->country_name = $result[0]["country_name"];

			$formattedDate = sprintf ( "%04d%02d%02d", $userbirthDTO->Year, $userbirthDTO->Month, $userbirthDTO->Day );
			$formattedTime = sprintf ( "%02d:%02d", $userbirthDTO->Hours, $userbirthDTO->Minutes );
			$timed_data = ((trim ( $userbirthDTO->unTimed ) == '1') ? false : true);

			if($userbirthDTO->Lagitute >= -90 && $userbirthDTO->Lagitute <= 90 ) {
				$userbirthDTO->Lagitute = $userbirthDTO->Lagitute * 3600;
			}

			if($userbirthDTO->Longitute >= -180 && $userbirthDTO->Longitute <= 180) {
				$userbirthDTO->Longitute = $userbirthDTO->Longitute * 3600;
			}
			$CountryName = $userbirthDTO->country_name;
			$userbirthDTO = GetLocationInformation($userbirthDTO);

			$dailyBirthData = new DailyBirthData(   $formattedDate,                         /* Birth Date	 */
					$formattedTime,                         /* Birth Time	 */
					$timed_data,                            /* Timed data  */
					$userbirthDTO->SummerTimeZoneRef,       /* Summertime	 */
					$userbirthDTO->ZoneRef,                 /* Timezone	 */
					$userbirthDTO->Longitute,               /* Longitude	 */
					$userbirthDTO->Lagitute );              /* Latitude	 */
			
			if(count($checkDate) > 0) {
				$month = $checkDate[0];
				$day = $checkDate[1];
				$year = $checkDate[2];
				$CheckPassedDate = sprintf("%04d-%02d-%02d", $year, $month, $day);

				$chart = new DailyPersonalChartAPI(  $dailyBirthData, false );
				$chart->getDailyAspects($dailyBirthData, $month, $day, $year, 2);

				$dPersonal = new DailyPersonal(0, $_['VALIDPARAM']);

				if(count($chart->m_aspect) == 0) {
					$chart->getDailyMoonAspects($dailyBirthData, $month, $day, $year, 2);
				}

				foreach ($chart->m_aspect as $key => $item) {
//                [0] => 2013-01-01 05:18 10020901007
//                [1] => 2013-01-01 11:01 10030901004
//                [2] => 2013-01-01 04:14 10030001002
//                [3] => 2013-01-02 01:19 10021801012
//                [4] => 2013-01-02 07:31 10030901013
//                [5] => 2013-01-03 09:26 10000901001
//                [6] => 2013-01-05 09:24 10020001009
//                       12345678901234567890123456789012 [Total 28 Char ]

					$TransitDate =   trim ( substr ( $item, 0, 10 ) );
					$TransitPlanet =  trim ( substr ( $item, 17, 4 ) );
					$Aspect =  trim ( substr ( $item, 21, 3 ) );
					$NatalPlanet =  trim ( substr ( $item, 24, 4 ) );

					/* patch for single node entry, so 1010 = node, 1011 = asc, 1012 = mc */
					if(  intval( $NatalPlanet ) > 1010 /* nodes */ ) {
						$NatalPlanet = intval( $NatalPlanet) - 1;
					}
					
					switch( intval( $Aspect ) ) {
						case 0:             $AspectIndex = 0;
						break;
						case 60: case 120:	$AspectIndex = 1;
						break;
						case 90: case 180:	$AspectIndex = 2;
						break;
						default:
							// error
					}
					
					$PlanetName = '';
					$AspectType = 'POS';
					$IsFind = false;

					if($NatalPlanet == '1011') {
						$NatalPlanet = '1010';
					}
					
					if(array_key_exists(trim($TransitPlanet), $PositiveAspect)) {
						$PlanetName = $PositiveAspect[$TransitPlanet];

						if(array_key_exists($NatalPlanet, $PositiveAspect)) {
							$PlanetName = $PositiveAspect[$NatalPlanet];
						}
						else {
							if(array_key_exists($NatalPlanet, $NegativeAspect)) {
								$PlanetName = $NegativeAspect[$NatalPlanet];
							}
						}
						switch( intval( $Aspect ) ) {
							//Conjunction  |  Sextile  |  Trine
							case 0: 	$AspectType = "POS";
								break;
							case 60: 	$AspectType = "POS";
								break;
							case 120:	$AspectType = "POS";
								break;
							//Square  |  Opposition
							case 90: 	$AspectType = "NEG";
								break;
							case 180:	$AspectType = "NEG";
								break;
							default:
								// error
								break;
						}
						$IsFind = true;
					}
					
					if($IsFind == false && array_key_exists($TransitPlanet, $NegativeAspect)) {
						//$PlanetName = $NegativeAspect[$TransitPlanet];
						if(array_key_exists($NatalPlanet, $NegativeAspect)) {
							$PlanetName = $NegativeAspect[$NatalPlanet];	
						}
						else {
							if(array_key_exists($NatalPlanet, $PositiveAspect)) {
								$PlanetName = $PositiveAspect[$NatalPlanet];
							}
						}
						
						switch( intval( $Aspect ) ) {
							//Conjunction  |  Sextile  |  Trine
							case 0: 	$AspectType = "NEG";
								break;
							case 60: 	$AspectType = "POS";
								break;
							case 120:	$AspectType = "POS";
								break;
							//Square  |  Opposition
							case 90: 	$AspectType = "NEG";
								break;
							case 180:	$AspectType = "NEG";
								break;
							default:
								// error
								break;
						}				
						
					echo "$TransitDate = [ TP : $TransitPlanet ] = [ A : $Aspect ] = [ NP : $NatalPlanet ] " . sprintf("%s_%s", $PlanetName, $AspectType) . "<br />";
					}
					
					
					$Title = '';
					if(array_key_exists($TransitPlanet, $PlanetNames)) {
						$Title .= sprintf('%s', $PlanetNames[$TransitPlanet]);
					}
					
					$Title .= sprintf(" %s", $_['TRANSITING']);
					
					if(array_key_exists($Aspect, $PlanetNames)) {
						$Title .= sprintf(" %s", $PlanetNames[$Aspect]);
					}
					
					if(array_key_exists($NatalPlanet, $PlanetNames)) {
						$Title .= sprintf(' %s', $PlanetNames[$NatalPlanet]);
					}
					$Title .= sprintf(" %s ", $_['NATALPLANET']);
					
					$TArray = array();
					$TArray["ROWTITLE"] = "$TransitDate = [ TP : $TransitPlanet ] = [ A : $Aspect ] = [ NP : $NatalPlanet ] " . sprintf("%s_%s", $PlanetName, $AspectType);
					$TArray["TransitTitle"] = $Title;
					
					$TArray["Title"] = $IconTitle[sprintf("%s_%s", $PlanetName, $AspectType)];
					
					$TArray["Icons"] = $IconPATH[sprintf("%s_%s", $PlanetName, $AspectType)];
					$TArray["AspectType"] = $AspectType;
					
					
					if(array_key_exists($TransitDate, $MothlyObject)) {
						array_push($MothlyObject[$TransitDate], $TArray);
					} else {
						$MothlyObject[$TransitDate][0] = $TArray;
					}
				}
				
				$dPersonal->DailyPredictionData = $MothlyObject;
				
				if(count($dPersonal->DailyPredictionData) == 0) {
					$dPersonal->Code = 1004;
					$dPersonal->Message = $_['NOTRANSITFOUND'];					
				}
				ob_end_clean();
				//header('Content-Type: text/html; charset=utf-8');
				header('Content-Type: application/json; charset=utf-8');
				echo json_encode($dPersonal);
			}
			else {
				$dPersonal = new DailyPersonal(1002, $_['INVALIDPARAM']);
				ob_end_clean();
				//                header('Content-Type: text/html; charset=utf-8');
				header('Content-Type: application/json; charset=utf-8');
				echo json_encode($dPersonal);
			}
		}
		else {
			$dPersonal = new DailyPersonal(1003, $_['USERNOTFOUND']);
			ob_end_clean();
			header('Content-Type: application/json; charset=utf-8');
			echo json_encode($dPersonal);
		}
	}
	else {
		$dPersonal = new DailyPersonal(1002, $_['INVALIDPARAM']);
		ob_end_clean();
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode($dPersonal);
	}
}


function GetLocationInformation($data) {
	$Longitude = $data->Longitute;
	$Latitude = $data->Lagitute;

	$sql =  "SELECT * FROM `acsatlas`".
			" WHERE upper(placename)='". strtoupper($data->city) ."' ".
			" AND (upper(SUBSTR(lkey, 0, 2))= '".  strtoupper( $data->country) ."') ".
			" ORDER BY lkey";
	$ACSRep = new  ACSRepository();
	$actStateList = new  ACSStateList();

	$Result = $ACSRep->GetACSDataRow($sql);

	if( count( $Result ) > 0 ) {
		$data->country_name = $actStateList->getStateNameByAbbrev(substr( $Result[0]['lkey'] , 0, 2));
	}

	$Location = sprintf( "%s, %s", utf8_decode($data->city), $data->country_name);

	$IsThere = GetSummerTimeZoneANDTimeZone($Location, $data);

	if(count($IsThere) > 0 ) {
		$data->ZoneRef = $IsThere['m_timezone_offset'];
		$data->SummerTimeZoneRef = $IsThere['m_summertime_offset'];
	}
	else {
		$Result = $ACSRep->GetACSDataRow($sql);

		if($Result) {
			$acsTimeTable = new AcsTimetables();
			$acsTimeTable->setBirthdate( sprintf("%04d-%02d-%02d %02d:%02d:%02d",
					$data->Year, $data->Month, $data->Day,
					$data->Hours, $data->Minutes, 0) );

			$data->ZoneRef = $acsTimeTable->getZoneOffset($Result[0]['zone']);
			$data->SummerTimeZoneRef = $acsTimeTable->getTypeOffset($Result[0]['type']);
		}
	}
	return $data;
}

function GetSummerTimeZoneANDTimeZone($location, $data) {
	$TimeZoneArray = array();

	if(extension_loaded('acsatlas')) {
		//Get the city info
		try {
			$city_info = acs_lookup_city($location);
		}
		catch (Exception $ex) {
			return $TimeZoneArray;
		}

		if (!$city_info) {
			return $TimeZoneArray;
			//die('The city lookup was unsuccessful.');
		}
		extract($city_info);
		//Get the time zone info
		//$time_info = acs_time_change_lookup($month, $day, $year, $hour, $minute, $zonetable, $typetable);
		$time_info = acs_time_change_lookup($data->Month, $data->Day, $data->Year,
				$data->Hours, $data->Minutes, $zonetable, $typetable);
		if (!$time_info) {
			return $TimeZoneArray;
			//die('The time zone lookup was unsuccessful.');
		}
		extract($time_info);

		if($type >= 0) {
			//Get the offset in hours from UTC
			$time_types = array(0,1,1,2); //assume $time_type < 4
			$offset = ($zone/900) - $time_types[$type];

			$ActualZoneValue = number_format(floatval( ($zone/900) ), 2);
			$ZoneValue = abs( number_format(floatval( ($zone/900) ), 2) );
			$tmpZone = intval($ZoneValue);
			$tmpZoneDiff = number_format( floatval(  $ZoneValue - $tmpZone ), 2 );
			$FinalZone = $ZoneValue;


			if($tmpZoneDiff > 0.0 &&  $tmpZoneDiff <= 0.50 ) {
				$FinalZone = number_format( floatval( $tmpZone + 0.30 ), 2);
			}
			else if($tmpZoneDiff >= 0.51 && $tmpZoneDiff <= 1 ) {
				$FinalZone = number_format( floatval( $tmpZone + 0.45 ), 2);
			}
			if( $ActualZoneValue < 0) {
				$TimeZoneArray["m_timezone_offset"] = number_format(-1 * floatval( $FinalZone ), 2);
			}
			else {
				$TimeZoneArray["m_timezone_offset"] = number_format(floatval( $FinalZone ), 2);
			}
			$TimeZoneArray["m_summertime_offset"] = number_format( floatval( $time_types[$type] ), 2);
		}
	}
	return $TimeZoneArray;
}
?>
