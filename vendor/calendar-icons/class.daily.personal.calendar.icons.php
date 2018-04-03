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
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";

if ($_SERVER['SERVER_NAME'] == 'localhost') {
    define( 'ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/astrowow' );
    define("BASEURL", $protocol . $_SERVER['SERVER_NAME']. "/astrowow/");
} else {
    define( 'ROOTPATH', $_SERVER['DOCUMENT_ROOT'] );
    define("BASEURL", $protocol . $_SERVER['SERVER_NAME']. "/");
}
define ( 'PERSONAL_HOROSCOPE_IMAGE_PATH', $_SERVER['DOCUMENT_ROOT'] . '/astrowow/' );

define ( 'VENDORPATH', ROOTPATH . '/vendor/calendar-icons' );
define ( 'LANGUAGEPATH', VENDORPATH . '/language' );
define ( 'BALPATH', VENDORPATH . '/bal' );
define ( 'DTOPATH', VENDORPATH . '/dto' );
define ( 'CLASSPATH', VENDORPATH . '/classes' );
define ( 'LIBRARYPATH', VENDORPATH . '/library' );
define ( 'DALPATH', VENDORPATH . '/dal' );
define ( 'HELPERPATH', VENDORPATH . '/helper' );
define ( 'INCLUDEPATH', VENDORPATH . '/include' );

require_once(VENDORPATH.'/config.php');
$languageId = $lang; //(!empty($this->request->session()->read('locale'))) ? $this->request->session()->read('locale') : 'en';
if ($languageId == 'da') {
    $languageId = 'dk';
}

/*if($languageId == "en") {
	require_once(LANGUAGEPATH.'/en/personal-daily/personal_daily.php');
} else if($languageId == "dk") {
	require_once(LANGUAGEPATH.'/dk/personal-daily/personal_daily.php');
} else {
	require_once(LANGUAGEPATH.'/en/personal-daily/personal_daily.php');
}*/

if($languageId == "dk") {
	$_['UNAUTHORIEDACCESS'] = "Invalid API Key. Authentication failed";
	$_['INVALIDPARAM'] = "Invalid parameters";
	$_['NOTRANSITFOUND'] = "Der er ingen vigtige transitter i dag";
	$_['VALIDPARAM'] = "Ok";
	$_['TRANSITING'] = "transit";
	$_['NATALPLANET'] = html_entity_decode(utf8_decode(utf8_encode( "i fødselshoroskopet" )));
	$_['USERNOTFOUND'] = "User not found";

	$PlanetNames =array (
        /*Planets*/
		"1000"	=>	"Sol", "1001"	=>	html_entity_decode(utf8_decode(utf8_encode( "Måne" ))), "1002"	=>	"Merkur", "1003"	=>	"Venus", "1004"	=>	"Mars", "1005"	=>	"Jupiter", "1006"	=>	"Saturn", "1007"	=>	"Uranus", "1008"	=>	"Neptun", "1009"	=>	"Pluto", "1010"	=>	html_entity_decode(utf8_decode(utf8_encode("Nordlige Måneknude"))), "1011"	=>	html_entity_decode(utf8_decode(utf8_encode( "Sydlige Måneknude" ))), "1012"	=>	"Ascendanten", "1013"	=>	"MC", "1014"	=>	"IC", "1015"	=>	"Descendanten",
        /*Signs*/
		"0100"	=>	html_entity_decode(utf8_decode(utf8_encode( "Vædder" ))), "0101"	=>	"Tyr",
		"0102"	=>	"Tvilling", "0103"	=>	"Krebs", "0104"	=>	html_entity_decode(utf8_decode(utf8_encode( "Løve" ))), "0105"	=>	"Jomfru", "0106"	=>	html_entity_decode(utf8_decode(utf8_encode( "Vægt" ))), "0107"	=>	"Skorpion", "0108"	=>	"Skytte", "0109"	=>	"Stenbuk", "0110"	=>	html_entity_decode(utf8_decode(utf8_encode( "Vandbærer" ))), "0111"	=>	"Fiskene",
		/*Houses*/
		"0001"	=>	"i 1. hus", "0002"	=>	"i 2. hus", "0003"	=>	"i 3. hus", "0004"	=>	"i 4. hus", "0005"	=>	"i 5. hus", "0006"	=>	"i 6. hus", "0007"	=>	"i 7. hus", "0008"	=>	"i 8. hus", "0009"	=>	"i 9. hus", "0010"	=>	"i 10. hus", "0011"	=>	"i 11. hus", "0012"	=>	"i 12. hus",
        /*Aspects*/
	    "000"	=>	"i konjunktion med", "060"	=>	"i harmoni med", "090"	=>	html_entity_decode(utf8_decode(utf8_encode( "i spænding til" ))), "120"	=>	html_entity_decode(utf8_decode(utf8_encode( "i harmoni med" ))), "180"	=>	html_entity_decode(utf8_decode(utf8_encode( "i spænding til" ))), "CON"	=>	" ", "POS"	=>	"i harmoni med", "NEG"	=>	html_entity_decode(utf8_decode(utf8_encode( "i spænding til" ))),
    );

    $IconTitle =  array(
		"AS_POS" => "Positive udsigter",
		"AS_NEG" => html_entity_decode(utf8_decode(utf8_encode( "Problemer på vejen"))),
		"MC_POS" => html_entity_decode(utf8_decode(utf8_encode( "Opfyldelse af mål"))),
		"MC_NEG" => html_entity_decode(utf8_decode(utf8_encode( "Problemer på hjemmefronten eller i karrieren"))),
		"SU_POS" => html_entity_decode(utf8_decode(utf8_encode( "Stærk selvbevidsthed"))),
		"SU_NEG" => html_entity_decode(utf8_decode(utf8_encode( "Usikkerhed omkring sig selv"))),
		"MO_POS" =>	"Hyggelig og tilpas",
		"MO_NEG" => html_entity_decode(utf8_decode(utf8_encode( "Følelsesmæssig utilpashed"))),
		"ME_POS" => html_entity_decode(utf8_decode(utf8_encode( "God kommunikation"))),
		"ME_NEG" => "Ukoncentreret",
		"VE_POS" => html_entity_decode(utf8_decode(utf8_encode( "Kærlighed og nydelse"))),
		"VE_NEG" => html_entity_decode(utf8_decode(utf8_encode( "Sanselig eller materiel forkælelse"))),
		"MA_POS" => "Beslutsom og magtfuld",
		"MA_NEG" => html_entity_decode(utf8_decode(utf8_encode( "Spændinger"))),
		"JU_POS" => "Indsigt, optimisme og medgang",
		"JU_NEG" => html_entity_decode(utf8_decode(utf8_encode( "Dårlig dømmekraft "))),
		"SA_POS" => html_entity_decode(utf8_decode(utf8_encode( "Resultater gennem disciplin og planlægning"))),
		"SA_NEG" => html_entity_decode(utf8_decode(utf8_encode( "Tilbagegang kræver hårdt arbejde"))),
		"UR_POS" => html_entity_decode(utf8_decode(utf8_encode( "Spændende indsigt, social og rejseaktivitet"))),
		"UR_NEG" => html_entity_decode(utf8_decode(utf8_encode( "Uvelkommen overraskelse og fremmedgørelse"))),
		"NE_POS" => html_entity_decode(utf8_decode(utf8_encode( "Åbenhed overfor universets magi, medfølelse"))),
		"NE_NEG" => html_entity_decode(utf8_decode(utf8_encode( "Opmærksomhed på sorg og lidelse"))),
		"PL_POS" => "Forvandling og styrke",
		"PL_NEG" => html_entity_decode(utf8_decode(utf8_encode( "Frasortering af det unødvendige"))),
		"NN_POS" => html_entity_decode(utf8_decode(utf8_encode( "Positiv indflydelse fra miljøet"))),
		"NN_NEG" => html_entity_decode(utf8_decode(utf8_encode( "Negativ indflydelse fra miljøet"))),
	);
} else {
	$_['UNAUTHORIEDACCESS'] = "Invalid API Key. Authentication failed";
	$_['INVALIDPARAM'] = "Invalid parameters";
	$_['NOTRANSITFOUND'] = "There is no important transit for today.";
	$_['VALIDPARAM'] = "Ok";
	$_['TRANSITING'] = "transit";
	$_['NATALPLANET'] = "in birth horoscope";
	$_['USERNOTFOUND'] = "User not found";

	$PlanetNames =array (
        /*Planets*/
        "1000" => "Sun", "1001" => "Moon", "1002" => "Mercury", "1003" => "Venus", "1004" => "Mars", "1005" => "Jupiter", "1006" => "Saturn", "1007" => "Uranus", "1008" => "Neptune", "1009" => "Pluto", "1010" => "Node", "1011" => "SNode", "1012" => "Ascendant", "1013" => "Midheaven", "1014" => "IC", "1015" => "Descendant",
        /*Signs*/
        "0100"	=>	"Aries", "0101"	=>	"Taurus", "0102"	=>	"Gemini", "0103"	=>	"Cancer", "0104"	=>	"Leo", "0105"	=>	"Virgo", "0106"	=>	"Libra", "0107"	=>	"Scorpio", "0108"	=>	"Sagittarius", "0109"	=>	"Capricorn", "0110"	=>	"Aquarius", "0111"	=>	"Pisces",
		/*Houses*/
		"0001"	=>	"1st House", "0002"	=>	"2nd House", "0003"	=>	"3rd House", "0004"	=>	"4th House", "0005"	=>	"5th House", "0006"	=>	"6th House", "0007"	=>	"7th House", "0008"	=>	"8th House", "0009"	=>	"9th House", "0010"	=>	"10th House", "0011"	=>	"11th House", "0012"	=>	"12th House",
    	/*Aspects*/
    	"000"	=>	"conjoins", "060"	=>	"in harmony with", "090"	=>	"in tension with", "120"	=>	"in harmony with", "180"	=>	"in tension with", "CON"	=>	" ", "POS"	=>	"in harmony with", "NEG"	=>	"in tension with",
    );

	$IconTitle =  array(
		"AS_POS" => "Positive outlook", "AS_NEG" => "Some glitches on the path", "MC_POS" => "Achievement of goals", "MC_NEG" => "Some domestic or career glitches", "SU_POS" => "Strong self-affirmation", "SU_NEG" => "Some uncertainty about self", "MO_POS" =>	"Cozy and comfortable", "MO_NEG" => "Some emotional discomfort", "ME_POS" => "Good communication", "ME_NEG" => "Some mental distraction", "VE_POS" => "Love and pleasure", "VE_NEG" => "Sensory or material indulgence", "MA_POS" => "Decisiveness and mastery", "MA_NEG" => "Some tension", "JU_POS" => "Insight, optimism and fortune", "JU_NEG" => "Overestimation, bad judgment", "SA_POS" => "Results through discipline and planning", "SA_NEG" => "Setbacks requiring hard work", "UR_POS" => "Exciting insights, social and travel activity", "UR_NEG" => "Unwelcome surprise, disassociation", "NE_POS" => "Openness to the magic of the universe, compassion", "NE_NEG" => "Awareness of sadness and suffering", "PL_POS" => "Transformation and empowerment", "PL_NEG" => "Elimination of what is useless", "NN_POS" => "Positive environmental influences", "NN_NEG" => "Negative environmental influences"
	);
}

require_once(BALPATH.'/include.php');
require_once(BALPATH.'/user.php');
require_once(DTOPATH.'/userDTO.php');

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

require_once(INCLUDEPATH.'/functions.php');

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
		"AS_POS" => "images/calendar-icons/AS_pos_Agendagreen.svg",						//  1
		"AS_NEG" => "images/calendar-icons/AS_neg_Agendared.svg",							//	2
		
		"MC_POS" => "images/calendar-icons/MC_pos_careergreen.svg",						//	3
		"MC_NEG" => "images/calendar-icons/MC_neg_careerred.svg",							//	4
		
		"SU_POS" => "images/calendar-icons/SU_pos_confidencegreen.svg",					//	5
		"SU_NEG" => "images/calendar-icons/SU_neg_confidencered.svg",						//	6
		
		"MO_POS" =>	"images/calendar-icons/MO_pos_Securitygreen.svg",						//	7
		"MO_NEG" => "images/calendar-icons/MO_neg_Securityred.svg",						//	8
		
		"ME_POS" => "images/calendar-icons/ME_pos_communicationsgreen.svg",				//	9
		"ME_NEG" => "images/calendar-icons/ME_neg_communicationsegative.svg",				//	10
		
		"VE_POS" => "images/calendar-icons/VE_pos_Lovegreen.svg",							//	11
		"VE_NEG" => "images/calendar-icons/VE_neg_Lovered.svg",							//	12
		
		"MA_POS" => "images/calendar-icons/MA_pos_energysexgreen.svg",						//	13
		"MA_NEG" => "images/calendar-icons/MA_neg_energysexred.svg",						//	14
		
		"JU_POS" => "images/calendar-icons/JU_pos_expensiongreen.svg",						//	15
		"JU_NEG" => "images/calendar-icons/JU_neg_expansionred.svg",						//	16
		
		"SA_POS" => "images/calendar-icons/SA_pos_obstaclesgreen.svg",						//	17
		"SA_NEG" => "images/calendar-icons/SA_neg_obstaclesred.svg",						//	18
		
		"UR_POS" => "images/calendar-icons/UR_pos_riskgreen.svg",							//	19
		"UR_NEG" => "images/calendar-icons/UR_neg_riskred.svg",							//	20
		
		"NE_POS" => "images/calendar-icons/NE_neg_yinyanggreen.svg",							//	21
		"NE_NEG" => "images/calendar-icons/NE_neg_yinyangred.svg",						//	22
		
		"PL_POS" => "images/calendar-icons/PL_pos_Transformationgreen.svg",				//	23
		"PL_NEG" => "images/calendar-icons/PL_neg_Transformationred.svg",					//	24
		
		"NN_POS" => "images/calendar-icons/NN_pos_contactsgreen.svg",						//	25
		"NN_NEG" => "images/calendar-icons/NN_neg_contactsred.svg",						//	26
		
		"SN_POS" => "images/calendar-icons/NN_pos_contactsgreen.svg",						//	25
		"SN_NEG" => "images/calendar-icons/NN_neg_contactsred.svg",						//	26
);

$MothlyObject = array();
                //pr ($this->request->session()->read()); die;
if (empty($user_id)) {
	$user_id = !empty($this->request->session()->read('Auth.UserProfile.user_id')) ? $this->request->session()->read('Auth.UserProfile.user_id') : $this->request->session()->read('user_id');
	$user_id = !empty($this->request->session()->read('selectedUser')) ? $this->request->session()->read('selectedUser') : $user_id;
}
if(!isset ($apikey) && empty ($apikey) ) {
	$dPersonal = new DailyPersonal(1001, $_['UNAUTHORIEDACCESS']);
	echo json_encode(array_map('utf8_encode', $dPersonal));
	exit(0);
} else {
	if(isset ($user_id) && !empty ($user_id) && isset ($date) && !empty ($date)  ) {
		
		$User_id = $user_id;
		$userType = '';
	    if (strpos($User_id, '_') !== false) {
	    	$anthrId = explode('_', $User_id);
	        $userType = 'anotherPerson';
	        $User_id = $anthrId[1];
	    }
		$pDate = $date; // m-d-Y
		$checkDate = explode('-', $pDate);
		
		$userbirthDTO = new UserBirthDetailDTO();
		$userDetailDTO = new UserDTO();
		$user = new User();
		
		$userDetail =  $user->GetUserDetail($User_id, $userType);
		$result =  $user->GetUserBirthDetailByUserId($User_id, $userType);
		/*if( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' ) {
			echo '<pre>'; print_r ($result); die;
		}*/
		/*if( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' ) {
			echo '<pre>'; print_r ($result); die;
		}*/
          
		if(count($result) > 0 && count($userDetail) > 0) {
			if (!empty($userType)) {
				$userDetailDTO->language = 'dk'; //$languageId; //$userDetail[0]['language'];
				$userDetailDTO->user_id = $userDetail[0]['added_by'];
				$userDetailDTO->portal_id =  2;
				$userbirthDTO->UserId = $result[0]["added_by"];
				//BIRTH DATE
				$dob = explode('-', $result[0]["dob"]);
			} else {
				$userDetailDTO->language = 'dk'; //$userDetail[0]['language'];
				$userDetailDTO->user_id = $User_id;
				$userDetailDTO->portal_id =  $userDetail[0]['portal_id'];
				$userbirthDTO->UserBirthDetailId  = $result[0]["id"];
				$userbirthDTO->UserId = $result[0]["user_id"];
				//BIRTH DATE
				$dob = explode('-', $result[0]["date"]);
			}

        	$dob_time = explode(':', $result[0]["time"]);			
			$userbirthDTO->Day        = $dob[2]; // Date
			$userbirthDTO->Month      = $dob[1]; // Month
			$userbirthDTO->Year       = $dob[0]; // Year
			$userbirthDTO->Hours      = $dob_time[0]; //$result[0]["Hours"];
			$userbirthDTO->Minutes    = $dob_time[1]; //$result[0]["Minutes"];
			$userbirthDTO->Seconds    = $dob_time[1]; //$result[0]["Seconds"];
			$userbirthDTO->GMT        = ''; //$result[0]["GMT"];
			$userbirthDTO->Lagitute   = $result[0]["latitude"]; //$result[0]["Lagitute"];
			$userbirthDTO->Longitute  = $result[0]["longitude"]; //$result[0]["Longitute"];
			$userbirthDTO->unTimed    = 0; //$result[0]["unTimed"];

			$userbirthDTO->ZoneRef    = !empty($result[0]["zone"]) ? $result[0]["zone"] : 0.00; //$result[0]["ZoneRef"];
			$userbirthDTO->SummerTimeZoneRef = $result[0]["type"]; //$result[0]["SummerTimeZoneRef"];

			$userbirthDTO->country      = $result[0]["countryname"]; //$result[0]["country"];
        	$userbirthDTO->country_id = $result[0]["country_id"];
			$userbirthDTO->state        = $result[0]["state"]; //$result[0]["state"];
			$userbirthDTO->city         = $result[0]["cityname"]; //$result[0]["city"];
			$userbirthDTO->country_name = $result[0]["countryname"]; //$result[0]["country_name"];


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
					$AspectType = "POS";
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
						$Title .= sprintf("%s", $PlanetNames[$TransitPlanet]);
					}

					$Title .= sprintf(" %s", $_['TRANSITING']);
					
					if(array_key_exists($Aspect, $PlanetNames)) {
						$Title .= sprintf(" %s", $PlanetNames[$Aspect]);
					}
					
					if(array_key_exists($NatalPlanet, $PlanetNames)) {
						$Title .= sprintf(" %s", $PlanetNames[$NatalPlanet]);
					}
					$Title .= sprintf(" %s ", $_['NATALPLANET']);

					$TArray = array();
					$TArray["ROWTITLE"] = "$TransitDate = [ TP : $TransitPlanet ] = [ A : $Aspect ] = [ NP : $NatalPlanet ] " . sprintf("%s_%s", $PlanetName, $AspectType);
					$TArray["TransitTitle"] = $Title;

					$TArray["Title"] = $IconTitle[sprintf("%s_%s", $PlanetName, $AspectType)];
					
					$TArray["Icons"] = BASEURL.$IconPATH[sprintf("%s_%s", $PlanetName, $AspectType)];
					$TArray["AspectType"] = $AspectType;

					if(array_key_exists($TransitDate, $MothlyObject)) {
						array_push($MothlyObject[$TransitDate], $TArray);
					} else {
						$MothlyObject[$TransitDate][0] = $TArray;
					}
				}
//pr ($PlanetNames); die;
					
				
				$dPersonal->DailyPredictionData = $MothlyObject;
				
				if(count($dPersonal->DailyPredictionData) == 0) {
					$dPersonal->Code = 1004;
					//$dPersonal->date = $date;
					$dPersonal->Message = $_['NOTRANSITFOUND'];					
				}
				ob_end_clean();
				
				//header('Content-Type: text/html; charset=utf-8');
				//header('Content-Type: application/json; charset=utf-8');
				$dPersonal =  (array) $dPersonal;
				/*if( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' ) {
            		echo 'here1221'; print_r($dPersonal); die;
          		}*/
				echo base64_encode(serialize($dPersonal)); exit ();
				//echo serialize($dPersonal); exit ();
				//echo json_encode($dPersonal); exit ();
			} else {
				$dPersonal = new DailyPersonal(1002, $_['INVALIDPARAM']);
				ob_end_clean();
				//                header('Content-Type: text/html; charset=utf-8');
				//header('Content-Type: application/json; charset=utf-8');
				echo base64_encode(serialize(array_map('utf8_encode', $dPersonal))); exit ();
				//echo base64_encode(serialize(array_map('utf8_encode', $dPersonal))); exit ();
				//echo json_encode(array_map('utf8_encode', $dPersonal)); exit ();
			}
		} else {
			$dPersonal = new DailyPersonal(1003, $_['USERNOTFOUND']);
			ob_end_clean();
			//header('Content-Type: application/json; charset=utf-8');
			echo base64_encode(serialize(array_map('utf8_encode', $dPersonal))); exit ();
			//echo json_encode(array_map('utf8_encode', $dPersonal)); exit ();
		}
	} else {
		$dPersonal = new DailyPersonal(1002, $_['INVALIDPARAM']);
		ob_end_clean();
		//header('Content-Type: application/json; charset=utf-8');
		echo base64_encode(serialize(array_map('utf8_encode', $dPersonal))); exit ();
		//echo json_encode(array_map('utf8_encode', $dPersonal)); exit ();
	}
}


function GetLocationInformation($data) {
	$Longitude = $data->Longitute;
	$Latitude = $data->Lagitute;

	/* $sql =  "SELECT * FROM `acsatlas`".
			" WHERE upper(placename)='". strtoupper($data->city) ."' ".
			" AND (upper(SUBSTR(lkey, 0, 2))= '".  strtoupper( $data->country) ."') ".
			" ORDER BY lkey"; */
	$sql =  "SELECT * FROM `cities`".
            " WHERE upper(city) ='". strtoupper($data->city) ."' ".
            " AND country_id = ".  strtoupper( $data->country_id) .
//            " where  longitude ='".$Longitude."'".
//            " AND  longitude ='".$Longitude."'".
//            " AND latitude ='".$Latitude."'".
            " ORDER BY id";
	$ACSRep = new  ACSRepository();
	$actStateList = new  ACSStateList();

	$Result = $ACSRep->GetACSDataRow($sql);

	/* if( count( $Result ) > 0 ) {
		$data->country_name = $actStateList->getStateNameByAbbrev(substr( $Result[0]['lkey'] , 0, 2));
	} */

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

	/*if ( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' ) {
		echo utf8_decode(utf8_encode($location)); die;
	}*/

    //Get the city info
    $output = getAcsatlasData("action=lookup_city&country=$location");
    
    $city_info = unserialize($output);
    
    $type = $city_info['typetable'];
    $zone = $city_info['zonetable'];

    if (!$city_info) {
         return $TimeZoneArray;
    }
    extract($city_info);

    //Get the time zone info
    $output = getAcsatlasData("action=time_change_lookup&month=$data->Month&day=$data->Day&year=$data->Year&hour=$data->Hours&minute=$data->Minutes&zonetable=$zonetable&typetable=$typetable");

    $time_info = unserialize($output);

    if (!$time_info) {
        return $TimeZoneArray;
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
        if($tmpZoneDiff > 0.0 &&  $tmpZoneDiff <= 0.50 ){
            $FinalZone = number_format( floatval( $tmpZone + 0.30 ), 2);
        }
        else if($tmpZoneDiff >= 0.51 && $tmpZoneDiff <= 1 ){
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
    return $TimeZoneArray;
}
?>
