<?php
/**
 * @author: Amit Parmar
 * @copyright: Astrowow and World of Wisdom Inc.,\
 *
 * @package: Generate Year Report Transit
 *
 * Description:
 * Generate Transit for All the Memeber for Current Birth Year
 */

echo "[GENERATE TRANSIT] (start) == ";
ini_set('memory_limit', '-1');
set_time_limit(12000);
date_default_timezone_set ( 'America/Los_Angeles' );

ini_set("display_errors", 1);
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);

if (!defined('ROOTPATH')) {
	//define('ROOTPATH', '/var/www/astrowow');
	define('ROOTPATH', '/home/astrowow/public_html/');
}

define ( 'CLASSPATH', ROOTPATH . '/classes' );
define ( 'LIBPATH', ROOTPATH . '/lib' );
define ( 'SPOOLPATH', ROOTPATH . '/var/spool' );

/* astrolog data */
require_once(ROOTPATH . '/bin/test-swisseph/configuration/wheel-constants.php');
require_once(ROOTPATH . '/bin/test-swisseph/birthdto.php');
require_once(ROOTPATH . '/bin/test-swisseph/astrolog-services-server.php');
require_once(ROOTPATH . '/bin/test-swisseph/astrolog-calculator.php');

require_once(ROOTPATH . '/include/lang/year-report/common_variables.php');

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

require_once (ROOTPATH . '/bin/year-report-transit/current.year.transit.php');
//require_once(ROOTPATH . '/bin/test-swisseph/report-pdf/commonpdfhelper.php');
require_once (ROOTPATH . '/bin/year-report-transit/current.year.progression.php');
require_once (ROOTPATH . '/bin/year-report-transit/generate.current.year.progression.php');

// deprecate and use Zend_Log instead
define('LOG4PHP_DIR', LIBPATH . '/log4php-0.9/src/log4php');
define('LOG4PHP_CONFIGURATION', ROOTPATH . '/var/lib/log4php/swissephe/wowuk.xml');

require_once(LOG4PHP_DIR . '/LoggerManager.php');
$logger = & LoggerManager::getLogger('swissephe::wowuk');
$reportLogger = & LoggerManager::getLogger('swissephe::wowuk');

$reportLogger->debug("YearReport::Scheduler Start " . Date('dmY H:i:s') . " | ");

/**
 * Step 1        : Get All the User
 * Step 2        : Check Currnet Year Transit
 * Step 3        : If Current Year Transit is Not Available
 * Step 4        : Process User Birth Information
 * Step 5        : Prepair List of Transit
 * Step 6        : Insert the Transit in Hitting Order Dates.
*/
$Global_Natal_Transit_Window;			//Transit list of 2 year
$Global_Natal_m_transit;					//Transit list of 2 year
$Global_Natal_m_crossing;				//House crossing list of 2 year

global $AbbrPlanetToFullName;
global $AspectTypes;
global $Connector;
global $NameOfPlanets;

$users = new User();
$userbirthdetail = new userbirthdetail();

//$UserList = $users->GetList( array( array ("UserId" , "=", 90 ) ) );
$UserList = $users->GetList(  array( array( "UserGroupId", "=", 3 ),  array( "status", "=", 1 ),  array( " AND parent_user_id IS NULL" ) ) );
//$UserList = $users->GetList( array( array ("UserId" , "=", 56 ), array (" OR UserId = 59 OR UserId = 90 OR UserId= 79 " )) );

echo "<pre>";
echo "==". count( $UserList ) . "==";
if( count( $UserList ) > 0 ) {
	foreach ($UserList as $UserItem) {
		if(strlen($UserItem->DefaultLanguage) > 2) {
			$reportLanguage = $languageCodes[strtolower( $UserItem->DefaultLanguage ) ];
		}
		else {
			$reportLanguage = strtolower( $UserItem->DefaultLanguage );
		}

		$Global_Language = $reportLanguage;

		//Alway takes English
		$YBookText  = new year_book_en();
		$YBookAgeText = new year_book_en_agetext();

		if($reportLanguage == 'en') {
			$YBookText  = new year_book_en();
			$YBookAgeText = new year_book_en_agetext();
		}
		else if($reportLanguage == 'dk') {
			$YBookText  = new year_book_dk();
			$YBookAgeText = new year_book_dk_agetext();
		}

		$UserDetailList = $userbirthdetail->GetList( array( array( "UserId", "=", $UserItem->UserId ) ) );

		if( count( $UserDetailList ) > 0 ) {

			foreach($UserDetailList as $UDetailItem) {
				$bData = $UDetailItem;

				$birthDTO = CreateBirthDTO($bData);
				$bData = SetLatLong($bData);

				$Global_OrdDate = date("Y-m-d", mktime ( 0, 0, 0, $bData->Month, $bData->Day, date('Y') - 1 ) );
				$Global_PreviousYear = date("Y-m-d", mktime ( 0, 0, 0, $bData->Month, $bData->Day, date('Y') - 1 ) );
				$Global_NextYear = date("Y-m-d", mktime ( 0, 0, 0, $bData->Month, $bData->Day, date('Y') + 1 ) );
				$Global_CurrntYear = date("Y-m-d", mktime ( 0, 0, 0, $bData->Month, $bData->Day, date('Y') - 1) );

				$AlreadyCalulatedTransit = new user_year_report_transit();
				$YouAlreadyProcessed = $AlreadyCalulatedTransit->GetList(array(
						array('HittingDate', '>= ', $Global_CurrntYear),
						array('HittingDate', '<=', $Global_NextYear),
						array('UserId', '=', $UserItem->UserId)
				));

				if( count($YouAlreadyProcessed) == 0) {
					$UserBirthDate = sprintf("%02d-%02d-%04d %02d:%02d", $bData->Day, $bData->Month, $bData->Year, $bData->Hours, $bData->Minutes);					
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
								}
								else {
									$PlanetCode2 = $AbbrPlanetToFullName[$PN];
								}

								$AspectType = $AspectTypes[$ASP];
								$ChapterNo = $GLOBALS["ChapterNameBasedOnPlanet"][$PT];
								
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

										$AddTransit->UserId = $UserItem->UserId;
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
					
					echo "Progression List <br />";
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
							}
							else {
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
								}
								else {
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
			}
		}
	}
}
echo "[GENERATE TRANSIT] (end) == ";
echo "</pre>";

function CreateBirthDTO($bData) {
	//echo "<pre>************************CreateBirthDTO()</pre>";	
	$bDate              = sprintf("%04d%02d%02d", $bData->Year, $bData->Month, $bData->Day);
	$bTime              = sprintf("%02d:%02d", $bData->Hours, $bData->Minutes);

	$isTimed            = ((strtoupper(trim($bData->unTimed)) == '1') ? false : true );
	$bSummerTimeZone    = $bData->SummerTimeZoneRef;
	$bTimeZone          = $bData->ZoneRef;
	$bLongitude         = $bData->Longitute;
	$bLatitude          = $bData->Lagitute;

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

	$acsstatelist = new ACSStatelist ();
	$country_name = $acsstatelist->getStateNameByAbbrev ( $countryAbbr );

	$Location = sprintf( "%s, %s", $birthplace, $country_name);

	$IsThere = GetSummerTimeZoneANDTimeZone($Location, $bData);

	if(count($IsThere) > 0 ) {
		$bData->ZoneRef = $IsThere['m_timezone_offset'];
		$bData->SummerTimeZoneRef = $IsThere['m_summertime_offset'];
	}

	global $userbirthdetail;

	return $bData;
}

function GetSummerTimeZoneANDTimeZone($location, $data) {
	//echo "<pre>************************GetSummerTimeZoneANDTimeZone()</pre>";
	$TimeZoneArray = array();

	if(extension_loaded('acsatlas')) {
		//Get the city info
		$city_info = acs_lookup_city($location);

		if (!$city_info) {
			return $TimeZoneArray;
		}
		extract($city_info);

		//Get the time zone info
		$time_info = acs_time_change_lookup($data->Month, $data->Day, $data->Year,
				$data->Hours, $data->Minutes, $zonetable, $typetable);

		if (!$time_info) {
			return $TimeZoneArray;
		}
		extract($time_info);

		if($type >= 0) {
			//Get the offset in hours from UTC
			$time_types = array(0,1,1,2); 							//assume $time_type < 4
			$offset = ($zone/900) - $time_types[$type];

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

			$TimeZoneArray["m_timezone_offset"] = number_format(floatval( $FinalZone ), 2);
			$TimeZoneArray["m_summertime_offset"] = number_format( floatval( $time_types[$type] ), 2);
		}
	}
	return $TimeZoneArray;
}

?>