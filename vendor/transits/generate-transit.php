<?php 
/**
 * @author: Kingslay
 * @copyright: Astrowow and World of Wisdom Inc.,\
 *
 * @package: Generate Year Report Transit
 *
 * Description:
 * Generate Transit for All the Memeber for Current Birth Year
 */
    
//echo "[GENERATE TRANSIT] (start) == ";
ini_set('memory_limit', '-1');
set_time_limit(12000);
date_default_timezone_set ( 'America/Los_Angeles' );

ini_set("display_errors", 1);
//error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
error_reporting(E_ERROR | E_PARSE);

$protocol = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';
if (! defined ( 'CLASSPATH' )) {
    //define('CLASSPATH', ROOTPATH . '/classes');
    if ($_SERVER['SERVER_NAME'] == 'localhost') {
        define( 'ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/astrowow' );
        define( 'BASEURL', $protocol.$_SERVER['SERVER_NAME'] .  '/astrowow' );
    } else {
        define( 'ROOTPATH', $_SERVER['DOCUMENT_ROOT'] );
        define( 'BASEURL', $protocol.$_SERVER['SERVER_NAME']);
    }
}

define( 'TRASITSVENDORPATH', ROOTPATH.'/vendor/transits' );
define( 'CLASSPATH', TRASITSVENDORPATH.'/classes' );
define( 'BINPATH', TRASITSVENDORPATH.'/bin' );
define( 'DTOPATH', TRASITSVENDORPATH.'/dto' );
define( 'BALPATH', TRASITSVENDORPATH.'/bal' );
define( 'DALPATH', TRASITSVENDORPATH.'/dal' );
define( 'HELPERPATH', TRASITSVENDORPATH.'/helper' );
define( 'LIBRARYPATH', TRASITSVENDORPATH.'/library' );
define( 'LANGUAGEPATH', TRASITSVENDORPATH.'/language' );
define( 'INCLUDEPATH', TRASITSVENDORPATH.'/include' );
define( 'LIBPATH', TRASITSVENDORPATH.'/lib' );
define( 'SPOOLPATH', TRASITSVENDORPATH.'/var/spool' );


/* astrolog data */
require_once(BINPATH . '/test-swisseph/configuration/wheel-constants.php');
require_once(BINPATH . '/test-swisseph/birthdto.php');
require_once(BINPATH . '/test-swisseph/astrolog-services-server.php');
require_once(BINPATH . '/test-swisseph/astrolog-calculator.php');

require_once(INCLUDEPATH . '/lang/year-report/common_variables.php');

/* POG data */
require_once(CLASSPATH . '/configuration.php');
require_once(CLASSPATH . '/objects/class.database.php');
require_once(CLASSPATH . '/objects/class.pog_base.php');

require_once (CLASSPATH . '/objects/class.state.php');
require_once (CLASSPATH . '/objects/class.order.php');              		//POG CLASS ADDED
require_once (CLASSPATH . '/objects/class.transaction.php');
require_once (CLASSPATH . '/objects/class.portal.php');
require_once (CLASSPATH . '/objects/class.product.php');
require_once (CLASSPATH . '/objects/class.product.description.php');
require_once (CLASSPATH . '/objects/class.birthdata.php');          		//POG CLASS ADDED
require_once (CLASSPATH . '/objects/class.horarydata.php');
require_once (CLASSPATH . '/objects/class.reportoption.php');
require_once (CLASSPATH . '/objects/class.emailaddress.php');

require_once (CLASSPATH . '/objects/class.user.php');
require_once (CLASSPATH . '/objects/class.user.year.report.transit.php');
require_once (CLASSPATH . '/objects/class.userbirthdetail.php');

require_once(CLASSPATH . '/objects/class.year_book_en.php');					// YEAR REPORT TEST Interpretation text
require_once(CLASSPATH . '/objects/class.year_book_en_agetext.php');            // YEAR REPORT TEST Interpretation Age text
require_once(CLASSPATH . '/objects/class.year_book_dk.php');					// YEAR REPORT TEST Interpretation text
require_once(CLASSPATH . '/objects/class.year_book_dk_agetext.php');            // YEAR REPORT TEST Interpretation Age text

/* ACS Atlas */
require_once(CLASSPATH . '/objects/class.acs.atlas.php');
require_once(CLASSPATH . '/acs/class.acs.statelist.php');

require_once (BINPATH . '/year-report-transit/current.year.transit.php');
//require_once(ROOTPATH . '/bin/test-swisseph/report-pdf/commonpdfhelper.php');
require_once (BINPATH . '/year-report-transit/current.year.progression.php');
require_once (BINPATH . '/year-report-transit/generate.current.year.progression.php');

// deprecate and use Zend_Log instead
/*define('LOG4PHP_DIR', LIBPATH . '/log4php-0.9/src/log4php');
define('LOG4PHP_CONFIGURATION', ROOTPATH . '/var/lib/log4php/swissephe/wowuk.xml');

require_once(LOG4PHP_DIR . '/LoggerManager.php');
$logger 		= & LoggerManager::getLogger('swissephe::wowuk');
$reportLogger 	= & LoggerManager::getLogger('swissephe::wowuk');

$reportLogger->debug("YearReport::Scheduler Start " . Date('dmY H:i:s') . " | ");*/

/**
 * Step 1        : Get All the User
 * Step 2        : Check Currnet Year Transit
 * Step 3        : If Current Year Transit is Not Available
 * Step 4        : Process User Birth Information
 * Step 5        : Prepair List of Transit
 * Step 6        : Insert the Transit in Hitting Order Dates.
*/
$Global_Natal_Transit_Window;			//Transit list of 2 year
$Global_Natal_m_transit;				//Transit list of 2 year
$Global_Natal_m_crossing;				//House crossing list of 2 year

global $AbbrPlanetToFullName;
global $AspectTypes;
global $Connector;
global $NameOfPlanets;
$ChapterNameBasedOnPlanet = array (
		"1012"  => "10001",		// Ascendant = AS
		"1013"  => "10002",		// Midheaven = MC
		"1014"  => "10002",		// IC
		"1015"  => "10001",		// Descendant = DS
		"1000"  => "10003",		// SUN
		"1001"  => "10004",		// MOON
		"1002"  => "10005",		// Mercury
		"1003"  => "10006",		// Venus
		"1004"  => "10007",		// Mars
		"1005"  => "10008",		// Jupitar
		"1006"  => "10008",		// Saturn
		"1007"  => "10008",		// Urabus
		"1008"  => "10008",		// Neptune
		"1009"  => "10008",		// Pluto
		"1010"  => "10008",		// N.Node
		"1011"  => "10008",		// S.Node
		"1016"  => "10008");	// Chiron
$AbbrPlanetToFullName = array(
		//	Planet code to Abbr name
		"0000" => "SU",
		"1000" => "SU",
		"1001" => "MO",
		"1002" => "ME",
		"1003" => "VE",
		"1004" => "MA",
		"1005" => "JU",
		"1006" => "SA",
		"1007" => "UR",
		"1008" => "NE",
		"1009" => "PL",
		"1010" => "NN",
		"1011" => "SN",
		"1012" => "AS",
		"1013" => "MC",
		"1014" => "IC",
		"1015" => "DS",
		"1016" => "CH",
		//Signs
		"0101" => "AR",
		"0102" => "TA",
		"0103" => "GE",
		"0104" => "CN",
		"0105" => "LE",
		"0106" => "VI",
		"0107" => "LI",
		"0108" => "SC",
		"0109" => "SG",
		"0110" => "CP",
		"0111" => "AQ",
		"0112" => "PI",
		"SU"	=>	"Sun",
		"MO"	=>	"Moon",
		"ME"	=>	"Mercury",
		"VE"	=>	"Venus",
		"MA"	=>	"Mars",
		"JU"	=>	"Jupiter",
		"SA"	=>	"Saturn",
		"UR"	=>	"Uranus",
		"NE"	=>	"Neptune",
		"PL"	=>	"Pluto",
		"NN"	=>	"N.Node",
		"SN"	=>	"S.Node",
		"AS"	=>	"Ascendant",
		"MC"	=>	"Midheaven",
		"IC"	=>	"IC",
		"DS"	=>	"Descendant",
		"CH" 	=>  "Chiron",
		//Signs
		"AR"	=>	"Aries",
		"TA"	=>	"Taurus",
		"GE"	=>	"Gemini",
		"CN"	=>	"Cancer",
		"LE"	=>	"Leo",
		"VI"	=>	"Virgo",
		"LI"	=>	"Libra",
		"SC"	=>	"Scorpio",
		"SG"	=>	"Sagittarius",
		"CP"	=>	"Capricorn",
		"AQ"	=>	"Aquarius",
		"PI"	=>	"Pisces",
		//Planet Full name to Abbr
		"Sun"        => "SU",
		"Moon"       => "MO",
		"Mercury"    => "ME",
		"Venus"      => "VE",
		"Mars"       => "MA",
		"Jupiter"    => "JU",
		"Saturn"     => "SA",
		"Uranus"     => "UR",
		"Neptune"    => "NE",
		"Pluto"      => "PL",
		"N.Node"     => "NN",
		"S.Node"     => "SN",
		"Ascendant"  => "AS",
		"Midheaven"  => "MC",
		"IC"         => "IC",
		"Descendant" => "DC",
		//Signs
		"Aries"       => "AR",
		"Taurus"      => "TA",
		"Gemini"      => "GE",
		"Cancer"      => "CN",
		"Leo"         => "LE",
		"Virgo"       => "VI",
		"Libra"       => "LI",
		"Scorpio"     => "SC",
		"Sagittarius" => "SG",
		"Capricorn"   => "CP",
		"Aquarius"    => "AQ",
		"Pisces"      => "PI"
		);
$AspectTypes = array(
		"000"	=>	"CON",
		"060"	=>	"POS",
		"090"	=>	"NEG",
		"120"	=>	"POS",
		"180"	=>	"NEG",
		"-->" 	=>  "CON",
		"S/D" => "moves direct",
		"S/R" => "moves retrograde",
		""      =>  "CON",
		"NA"    =>  "CON");
$users = new User();
$userbirthdetail = new userbirthdetail();

function getLoggedinUserId () {
	$UserId = !empty($_SESSION['Auth']['User']['id']) ? $_SESSION['Auth']['User']['id'] : '';
	$UserId = (!empty($_SESSION['user_id'])) ? $_SESSION['user_id'] : $UserId;
	$UserId = (!empty($_SESSION['selectedUser'])) ? $_SESSION['selectedUser'] : $UserId;
	return $UserId;
}

/*$selectedLan = '';
if (isset($_SESSION['locale']) && !empty($_SESSION['locale'])) {
	if ($_SESSION['locale'] == 'da') {
		$selectedLan = 'dk';
	} else {
		$selectedLan = $_SESSION['locale'];
	}
}*/


$user_id = getLoggedinUserId ();

$registeredUserList = array ();
if (empty($user_id)) {
	$registeredUserList = $users->getRegisteredUserList ();
} else {
	$registeredUserList[0] = $user_id;
}

$countUsers = count ($registeredUserList);
foreach ($registeredUserList as $userkey => $user_id) {
	if (strpos($user_id, '_') !== false) {
        $anotherPersonId = explode('_', $user_id);
        $UserList = $users->GetListBasedOnUser ( 'anotherPerson', array( array( "another_persons`.`id", "=", $anotherPersonId[1] ) ) );
    } else {
		//$UserList = $users->GetList( array( array ("UserId" , "=", 60 ) ) );
		$UserList = $users->GetListBasedOnUser ( 'user', array( array( "users`.`id", "=", $user_id ), array( "users`.`status", "=", 1 ) ) );
		//$UserList = $users->GetList( array( array ("UserId" , "=", 56 ), array (" OR UserId = 59 OR UserId = 90 OR UserId= 79 " )) );
	}
//echo "<pre>";
//echo "\n TOTAL USERS::" .count( $UserList )  . "\n";
	if( count( $UserList ) > 0 ) {
		foreach ($UserList as $UserItem) {
			if(strlen($UserItem->DefaultLanguage) > 2) {
				$reportLanguage = $languageCodes[strtolower( $UserItem->DefaultLanguage ) ];
			} else {
				$reportLanguage = strtolower( $UserItem->DefaultLanguage );
			}

			//$reportLanguage = !empty($selectedLan) ? $selectedLan : $reportLanguage;

			$Global_Language = $reportLanguage;

			//Alway takes English
			$YBookText  = new year_book_en();
			$YBookAgeText = new year_book_en_agetext();

			if($reportLanguage == 'en') {
				$YBookText  = new year_book_en();
				$YBookAgeText = new year_book_en_agetext();
			} else if($reportLanguage == 'dk') {
				$YBookText  = new year_book_dk();
				$YBookAgeText = new year_book_dk_agetext();
			}
			

			//$UserDetailList = $userbirthdetail->GetList( array( array( "birth_details`.`user_id", "=", $user_id ) ) );
			if (strpos($user_id, '_') !== false) {
				$anotherPersonId = explode('_', $user_id);
				$UserDetailList = $userbirthdetail->GetListBasedOnSelectedUser ('anotherperson', array( array( "another_persons`.`id", "=", $anotherPersonId[1] ) ) );
			} else {
				$UserDetailList = $userbirthdetail->GetListBasedOnSelectedUser ('user',  array( array( "birth_details`.`user_id", "=", $user_id ) ) );
			}
			//$UserDetailList = $userbirthdetail->GetList( array( array( "birth_details`.`user_id", "=", $user_id ) ) );
			
			if( count( $UserDetailList ) > 0 ) {
				foreach($UserDetailList as $UDetailItem) {
					$bData = $UDetailItem;
							
					$birthDTO = CreateBirthDTO($bData);
					$bData = SetLatLong($bData);
		
					$Global_OrdDate = date("Y-m-d", mktime ( 0, 0, 0, $bData->Month, $bData->Day, date('Y') - 1 ) );
					$Global_PreviousYear = date("Y-m-d", mktime ( 0, 0, 0, $bData->Month, $bData->Day, date('Y') - 1 ) );				
					//$Global_CurrntYear = date("Y-m-d", mktime ( 0, 0, 0, $bData->Month, $bData->Day, date('Y') - 1) );
					//$Global_NextYear = date("Y-m-d", mktime ( 0, 0, 0, $bData->Month, $bData->Day, date('Y') + 1 ) );

					$Global_CurrntYear = date("Y-m-d", strtotime("-3 month"));
					$Global_NextYear = date("Y-m-d", strtotime("+1 year $Global_CurrntYear ") );

					$AlreadyCalulatedTransit = new user_year_report_transit();
					
					if (strpos($user_id, '_') !== false) {
						$anotherPersonId = explode('_', $user_id);
						$YouAlreadyProcessed = $AlreadyCalulatedTransit->GetList(array(
							array('HittingDate', '>= ', $Global_CurrntYear),
							array('HittingDate', '<=', $Global_NextYear),
							array('user_id', '=', $anotherPersonId[1]),
							array('user_type', '=', 'anotherPerson')
						));
					} else {
						$YouAlreadyProcessed = $AlreadyCalulatedTransit->GetList(array(
							array('HittingDate', '>= ', $Global_CurrntYear),
							array('HittingDate', '<=', $Global_NextYear),
							array('user_id', '=', $user_id),
							array('user_type', '=', 'user')
						));
					}

					
					//echo $Global_CurrntYear.'<br>'.$Global_NextYear.'<br>'.$user_id.'<br>';
					//pr ($YouAlreadyProcessed); die;
	// 				echo "<br />$Global_CurrntYear = $Global_NextYear [ $YouAlreadyProcessed ]<br />";
					
					if( count($YouAlreadyProcessed) == 0) {
						//echo "\n Processing USER ID: " . $user_id . "\n";
						if(intval($bData->Hours) >= 0) {
							if(intval($bData->Minutes) >= 0) {	
								$UserBirthDate = sprintf("%02d-%02d-%04d %02d:%02d", $bData->Day, $bData->Month, $bData->Year, $bData->Hours, $bData->Minutes);
							} else {
								$UserBirthDate = sprintf("%02d-%02d-%04d %02d:%02d", $bData->Day, $bData->Month, $bData->Year, $bData->Hours, 0);
							}
						} else {
							$UserBirthDate = sprintf("%02d-%02d-%04d %02d:%02d", $bData->Day, $bData->Month, $bData->Year, 12, 0);
						}
	// 					echo "<br />[ $UserBirthDate ]<br />";
						
						$UserBirthDate = new DateTime($UserBirthDate);
						//$OrdDate = new DateTime($Global_CurrntYear);
						$OrdDate = new DateTime($Global_OrdDate);
						
						$AgeDiff = $OrdDate->diff($UserBirthDate);
						$UserAge = intval($AgeDiff->format('%Y')) + 1;
							
						$generateTransitGraph = new CurrentYearTransit ($birthDTO, $OrdDate);
						
	
						
						//echo "Transit List <br />";
						
						foreach( $generateTransitGraph->transit_window as $TransitItem ) {
							$HittingDate = $TransitItem['hitdate'];
							$StartDate = $TransitItem['start'];
							$EndDate = $TransitItem['end'];
							$PT = $TransitItem["pt"];
							$ASP = $TransitItem["asp"];
							$PN = $TransitItem["pn"];
							$AspectIdentifier = 'T';

							//if($HittingDate >= $Global_CurrntYear && $HittingDate <= $Global_NextYear) {
							if($HittingDate >= $Global_CurrntYear) {
								$PlanetCode1 = $AbbrPlanetToFullName[$PT];
								$PlanetCode2 = $PN;
								if(intval($PN) >= 1000) {
									if(intval($PN) < 100) {
										$PlanetCode2 = sprintf("%02d", $PN);
									} else {
										$PlanetCode2 = $AbbrPlanetToFullName[$PN];
									}

									$AspectType = $AspectTypes[$ASP];

									$ChapterNo = $ChapterNameBasedOnPlanet[$PT];
									
									//echo "$HittingDate = $PlanetCode1 = $AspectType = $PlanetCode2 <br />";
									$YBookTextList = $YBookText->GetList(
											array(
													array('chapter_id', '=', $ChapterNo),
													array('planet_code1', '=', $PlanetCode1),
													array('aspect_id', '=', $AspectType),
													array('planet_code12', '=', $PlanetCode2),
													array('aspect_type', 'LIKE', $AspectIdentifier)
											)
									);
									

									if( count($YBookTextList) > 0 ) {
										foreach($YBookTextList as $BookItem) {
											$AddTransit = new user_year_report_transit();
											if (strpos($user_id, '_') !== false) {
												$anotherPersonId = explode('_', $user_id);
												$AddTransit->UserId = $anotherPersonId[1];
												$AddTransit->UserType = 'anotherPerson';
											} else {
												$AddTransit->UserId = $user_id;
												$AddTransit->UserType = 'user';
											}
											
											$AddTransit->HittingDate = $HittingDate;
											$AddTransit->StartDate = $StartDate;
											$AddTransit->EndDate = $EndDate;
											$AddTransit->year_book_id = $BookItem->year_book_id;
											$AddTransit->AspectType = "TR";
											$AddTransit->Aspect= $ASP;
											$AddTransit->PRTOPR_Date = null;
											$AddTransit->SaveNew();
											
											unset($AddTransit);
										}
									}
								}
							}
						}

						$OrdDate = new DateTime($Global_OrdDate);
						$CurrentYRProgression = new CurrentYearProgression($birthDTO, $OrdDate);
						
	// 					echo "Progression List <br />";
						foreach( $Global_Progression_TransitSortedList as $TransitItem ) {
							$HittingDate = $TransitItem['hitdate'];
							$StartDate = $TransitItem['start'];
							$EndDate = $TransitItem['enddate'];
							$PT = $TransitItem["pt"];
							$ASP = $TransitItem["asp"];
							$PN = $TransitItem["pn"];
							$AspectIdentifier = 'P';
							
							$PlanetCode1 = $AbbrPlanetToFullName[$PT];
							if(intval($PN) >= 1000) {
								if(intval($PN) < 100) {
									$PlanetCode2 = sprintf("%02d", $PN);
								} else {
									$PlanetCode2 = $AbbrPlanetToFullName[$PN];
								}
							}
							$AspectType = $AspectTypes[$ASP];
							
							//echo "$HittingDate = $PlanetCode1 = $AspectType = $PlanetCode2 <br />";
							
							if($HittingDate >= $Global_CurrntYear) {							
								$PlanetCode1 = $AbbrPlanetToFullName[$PT];
								$PlanetCode2 = $PN;
								if(intval($PN) >= 1000) {
									if(intval($PN) < 100) {
										$PlanetCode2 = sprintf("%02d", $PN);
									} else {
										$PlanetCode2 = $AbbrPlanetToFullName[$PN];
									}

									$AspectType = $AspectTypes[$ASP];
									$ChapterNo = $GLOBALS["ChapterNameBasedOnPlanet"][$PT];

									$YBookTextList = $YBookText->GetList(
											array(
													array('chapter_id', '=', $ChapterNo),
													array('planet_code1', '=', $PlanetCode1),
													array('aspect_id', '=', $AspectType),
													array('planet_code12', '=', $PlanetCode2),
													array('aspect_type', 'LIKE', $AspectIdentifier)
											)
									);


									if( count($YBookTextList) > 0 ) {
										foreach($YBookTextList as $BookItem) {										
											$AddTransit1 = new user_year_report_transit();

											$AddTransit1->UserId = $UserItem->UserId;
											$AddTransit1->HittingDate = $HittingDate;
											$AddTransit1->StartDate = $StartDate;
											$AddTransit1->EndDate = $EndDate;
											$AddTransit1->year_book_id = $BookItem->year_book_id;
											$AddTransit1->AspectType = "PR";
											$AddTransit1->Aspect= $ASP;
											$AddTransit1->PRTOPR_Date = $EndDate;										
											$AddTransit1->SaveNew();
											
											unset($AddTransit1);
										}
									}
								}
							}
						}
					}
					unset($AlreadyCalulatedTransit);
					//return true;
				}
			}
		}
	}

	if ($countUsers == ($userkey+1)) {
		return true;
	}
}

//echo "[GENERATE TRANSIT] (end) == ";
//echo "</pre>";

function CreateBirthDTO($bData) {
	//echo "<pre>************************CreateBirthDTO()</pre>";	
	$bDate = sprintf("%04d%02d%02d", $bData->Year, $bData->Month, $bData->Day);
	$bTime = sprintf("%02d:%02d", $bData->Hours, $bData->Minutes);

	$isTimed = ((strtoupper(trim($bData->unTimed)) == '1') ? false : true );
	$bSummerTimeZone = $bData->SummerTimeZoneRef;
	$bTimeZone = $bData->ZoneRef;
	$bLongitude = $bData->Longitute;
	$bLatitude = $bData->Lagitute;

	$Lat = $bData->Lagitute;
	$Long = $bData->Longitude;
	$CalLat = $bData->Lagitute;
	$CalLong = $bData->Longitude;

	if($bData->Lagitute >= -90 && $bData->Lagitute <= 90) {
		$Lat = $bData->Lagitute * 3600;
	}
	else {
		$bData->Lagitute = $bData->Lagitute / 3600;
		$bLatitude = $bData->Lagitute / 3600;
		$Lat = $bData->Lagitute;
	}

	if($bData->Longitute >= -180 && $bData->Longitute <= 180) {
		$Long = $bData->Longitude * 3600;
	}
	else {
		$bLongitude = $bData->Longitute / 3600;
		$bData->Longitude = $bData->Longitute / 3600;		
		$Long = $bData->Longitude;
	}
		
	$birthDTO = new BirthDTO($bDate, $bTime, $isTimed, $bSummerTimeZone, $bTimeZone, $bLongitude, $bLatitude);
	return $birthDTO;
}

function SetLatLong(&$bData) {
	//echo "<pre>************************SetLatLong()</pre>";
	$Longitude = 0;
	$Latitude = 0;
	if($bData->Lagitute > -90 && $bData->Lagitute < 90) {
		$Latitude = $bData->Lagitute * 3600;
	}
	else {
		$Latitude = $bData->Lagitute;
		$bData->Lagitute = $bData->Lagitute / 3600;
	}

	if($bData->Longitute > -180 && $bData->Longitute < 180) {
		$Longitude = $bData->Longitute * 3600;
	}
	else {
		$Longitude = $bData->Longitute;
		$bData->Longitute = $bData->Longitute / 3600;
	}

	$birthplace = $bData->city;
	$countryAbbr = $bData->country;
	$acsatlas = new ACSAtlas();
	$placeList = $acsatlas->GetList( array( array ('latitude', '=', $Latitude), array ('longitude', '=', $Longitude )));
	if (count ( $placeList ) > 0) {
		foreach($placeList  as $pItem) {
			$fullbirthplace = explode ( ">", $pItem->placename );
			if (count ( $fullbirthplace ) > 0) {
				$birthplace = trim ( $fullbirthplace [0] );
			} else {
				$birthplace = trim( $pItem->placename );
			}
			$countryAbbr = substr ( $pItem->lkey, 0, 2 );
		}
	}
	//$acsstatelist = new ACSStatelist ();
	//$country_name = $acsstatelist->getStateNameByAbbrev ( $countryAbbr );

	$Location = sprintf( "%s, %s", $birthplace, $bData->country_name);

	//echo "\nUSER PLACE: ".$Location . "\n" ;

	$IsThere = GetSummerTimeZoneANDTimeZone($bData);

	if(count($IsThere) > 0 ) {
		$bData->ZoneRef = $IsThere['m_timezone_offset'];
		$bData->SummerTimeZoneRef = $IsThere['m_summertime_offset'];
	}
	global $userbirthdetail;

	return $bData;
}

	function GetSummerTimeZoneANDTimeZone($data) {
        $TimeZoneArray = array();

        if(isset($data->country_name)  && isset($data->Hours) && isset($data->Minutes)) {
            $city_name = $data->country_name;
            $minutes =  $data->Minutes;
            $hours =  $data->Hours;
        } else {
        	$city_name = $data->city;
        	$minutes = $data->Minutes;
        	$hours = $data->Hours;
        }
        $country = $data->country;
        $location = sprintf( "%s, %s",  $city_name, $country);
        $output = getAcsatlasData("action=lookup_city&country=$location");
        $city_info = unserialize($output);

        if (!$city_info) {
        	return $TimeZoneArray;
        	die('The city lookup was unsuccessful.');
        }
           
        $month = $data->month;
        $day = $data->day;
        $year = $data->year;
        $minutes = $minutes;
        $hours = $hours;
        $zonetable = $city_info['zonetable'];
        $typetable =  $city_info['typetable'];
        //$output = $this->getAcsatlasData("action=time_change_lookup&month=$month&day=$day&year=$year&hour=$hours&minute=$minutes&zonetable=$zonetable&typetable=$typetable");
        $output = getAcsatlasData("action=time_change_lookup&month=$month&day=$day&year=$year&hour=$hours&minute=$minutes&zonetable=$zonetable&typetable=$typetable");

        $time_info = unserialize($output);
        if (!$time_info) {
            return $TimeZoneArray;
        }
        extract($time_info);
        if($type >= 0) {
            //Get the offset in hours from UTC
            $time_types = array(0,1,1,2); //assume $time_type < 4
            $offset = ($zone/900) - $time_types[$type];

            $TimeZoneArray["m_timezone_offset"] = number_format(floatval( ($zone/900) ), 2);
            $TimeZoneArray["m_summertime_offset"] = number_format( floatval( $time_types[$type] ), 2);
        }
        return $TimeZoneArray;
    }

    function getAcsatlasData($data) {
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
        return $output;
                  
    }

?>