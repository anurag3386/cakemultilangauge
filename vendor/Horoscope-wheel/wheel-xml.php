<?php
/**
 * This is the Initiater page for generating daily personal based on passed user Id
 */
@ob_start();

//ini_set("display_errors", 1);
//error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
//error_reporting(0);
ini_set("display_errors", 0);
//error_reporting(E_ALL);

date_default_timezone_set ( 'America/Los_Angeles' );

if ($_SERVER['SERVER_NAME'] == 'localhost') {
    define( 'ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/astrowow' );
} else {
    define( 'ROOTPATH', $_SERVER['DOCUMENT_ROOT'] );
}
define ( 'PERSONAL_HOROSCOPE_IMAGE_PATH', $_SERVER['DOCUMENT_ROOT'] . '/astrowow/' );


define ( 'CLASSPATH', ROOTPATH . '/vendor/Horoscope-wheel/classes' );
define ( 'LIBPATH', ROOTPATH . '/vendor/Horoscope-wheel/lib' );
define ( 'SPOOLPATH', ROOTPATH . '/vendor/Horoscope-wheel/var/spool' );
define ( 'XMLPATH', ROOTPATH . '/webroot/Horoscope-wheel-xml' );
define ( 'BALPATH', ROOTPATH . '/vendor/Horoscope-wheel/bal' );
define ( 'BINPATH', ROOTPATH . '/vendor/Horoscope-wheel/bin' );
define ( 'DALPATH', ROOTPATH . '/vendor/Horoscope-wheel/dal' );
define ( 'LIBRARYPATH', ROOTPATH . '/vendor/Horoscope-wheel/library' );
define ( 'HELPERPATH', ROOTPATH . '/vendor/Horoscope-wheel/helper' );
define ( 'DTOPATH', ROOTPATH . '/vendor/Horoscope-wheel/dto' );
define ( 'INCLUDEPATH', ROOTPATH . '/vendor/Horoscope-wheel/include' );

require_once(ROOTPATH.'/vendor/Horoscope-wheel/config.php');
require_once(BALPATH.'/include.php');
require_once(BALPATH.'/user.php');
require_once(BALPATH.'/goldenCircle.php');
require_once(DTOPATH.'/userDTO.php');

/* language resources */

require_once (INCLUDEPATH . '/lang/en.php');
require_once (INCLUDEPATH . '/lang/dk.php');
require_once (INCLUDEPATH . '/lang/du.php');
require_once (INCLUDEPATH . '/lang/ge.php');
require_once (INCLUDEPATH . '/functions.php');

/* add greek		 */
require_once (INCLUDEPATH . '/lang/no.php');

/* add portugese	 */
require_once (INCLUDEPATH . '/lang/sp.php');
require_once (INCLUDEPATH . '/lang/sw.php');

/* POG data */
require_once (CLASSPATH . '/configuration.php');
require_once (CLASSPATH . '/objects/class.database.php');
require_once (CLASSPATH . '/objects/class.pog_base.php');

require_once (CLASSPATH . '/objects/class.state.php');
//require_once (CLASSPATH . '/objects/class.xmls.php');
require_once (CLASSPATH . '/objects/class.transaction.php');
require_once (CLASSPATH . '/objects/class.portal.php');

require_once (CLASSPATH . '/objects/class.birthdata.php');              //POG CLASS ADDED
require_once (CLASSPATH . '/objects/class.reportoption.php');
require_once (CLASSPATH . '/objects/class.emailaddress.php');

if (!class_exists('ACSStateList')) {
    if(!include(CLASSPATH . '/acs/class.acs.statelist.php')) {
        require_once (CLASSPATH . '/acs/class.acs.statelist.php');
    }
}

/* font information */
define ( 'FPDF_FONTPATH', LIBPATH . '/fpdf/fonts/' );
require_once (LIBPATH . '/fpdi/fpdi.php');
require_once (LIBPATH . '/fpdf/fpdf.php');

/* astrolog data */
// targetted for development action
// generate XML

require_once(CLASSPATH . '/class.daily.personal.php');
require_once(CLASSPATH . '/daily.personal.api.php');
require_once(CLASSPATH . '/daily.personal.pipe.php');
require_once (BINPATH . '/daily-personal/generate-birth-data.php');

require_once (CLASSPATH . '/wow/report.php');           // rewrite for XML
require_once (CLASSPATH . '/wow/report.pdf.generic.php');
require_once (CLASSPATH . '/wow/generator/default/report.pdf.rc1.php');


/* WOW Wheel */
/* Developer Note - these are temporary until the issues are iron out */
require_once (BINPATH . '/wheel/class.report.pdf.wheel.rc1.php');
require_once (BINPATH . '/wheel/class.report.pdf.wheel.wow.php');

$languageCodes = array(
        'english' => 'en',
        'danish' => 'dk',
        'swedish' => 'se',
        'spanish' => 'sp');
$languageIds = array(
        "1"  =>	"english",
        "2"  =>	"danish",
        "3"  =>	"german",
        "4"  =>	"norwegian",
        "5"  =>	"swedish",
        "6"  =>	"spanish",
        "7"  =>	"portuguese",
        "8"  =>	"dutch",
        "9"  =>	"finnish");

$ReportsDefaultLanguage = $lang;
if ($ReportsDefaultLanguage == 'da') {
    $ReportsDefaultLanguage = 'dk';
}
$planets = array ('Sun', 'Moon', 'Mercury', 'Venus', 'Mars', 'Jupiter', 'Saturn', 'Uranus', 'Neptune', 'Pluto', 'NNode', 'SNode', 'Ascendant', 'MC', 'IC', 'Descendant' );

$task = 'xml'; //$_REQUEST['task'];

if(!isset ($apikey) && empty ($apikey) ) {
    exit(0);
} else {
    if(isset ($task) && !empty ($task)) {
        if(strtolower( $task ) == 'xml') {
            $language = $lang;
            //if( isset ($uid) && !empty ($uid) ) {
                $this->loadModel('Xmls');
                $this->loadModel('Cities');
                //$XmlsTable = TableRegistry::get('Xmls');
                $XmlsEntity = $this->Xmls->newEntity();
                $data['email'] = $email; //'abc@gmai.com';
                $data['language_id'] = ($language=='da' || $language=='dk') ? 2 : 1;
                $data['language'] = $language;
                $data['DefaultLanguage'] = $language;
                $data['day'] = $day; //'20';
                $data['month'] = $month; //'05';
                $data['year'] = $year; //'1987';
                $data['hour'] = $hour; //'11';
                $data['minute'] = $minute; //'20';
                $data['place'] = $place; //'Jajpur';
                $data['country'] = $country; //'INDIA';
                $cityData = $this->Cities->find()
                            ->join([
                                    'Countries' => [
                                        'type' => 'INNER',
                                        'table' => 'countries',
                                        'conditions' => [
                                            'Countries.name = Cities.country'//$data["country"]
                                        ]
                                    ]
                                ])
                               ->where(['Cities.city' => $data['place'], 'Cities.country' => $data['country']])
                               ->select(['Countries.id','Countries.name','Countries.abbr', 'Cities.city', 'Cities.county', 'Cities.longitude', 'Cities.latitude'])
                               ->first();
                //pr($cityData); die;
                $data['status'] = 1;
                $data['country_id'] = $cityData['Countries']['id'];
                $data['city'] = $cityData['city'];
                $data['state'] = $cityData['county'];
                $data['country'] = $cityData['Countries']['name'];
                $data['place'] = $cityData['city'];
                $data['city_name'] = $cityData['city'];
                $data['longitude'] = $cityData['longitude'];
                $data['latitude'] = $cityData['latitude'];
                $data['first_name'] = $fname;
                $data['last_name'] = $lname;
                $data['gender'] = $gender; //'M';
                $data['hours'] = $data['hour'];
                $data['minutes'] = $data['minute'];
                $data['PortalId'] = '2';
                $data['modified'] = $data['created'] = date('Y-m-d H:i:s');
                //pr($cityData); pr($data); die;
                $xmlData = $this->Xmls->patchEntity($XmlsEntity, $data);
                $xmlId = '';
                if($xmlResult = $this->Xmls->save($xmlData)){
                    $data['id'] = $xmlResult->id;
                } else {
                    echo 'You have missed something.'; exit();
                }

                $NatalWheelFileName = sprintf ( "%s/%d.natalwheel.xml", XMLPATH, $data['id'] );

				if(file_exists($NatalWheelFileName)) {
                	unlink($NatalWheelFileName);
                }

                $birthdata = $data;
                $this->SetLatLong($birthdata);
                $userDetail = $birthdata;
                if($userDetail) {

                    if(strlen($userDetail['language_id']) > 2) {
                        $ReportsDefaultLanguage = $languageCodes[strtolower( $userDetail['language'] ) ];
                    } else {
                        $ReportsDefaultLanguage = strtolower( $userDetail['DefaultLanguage'] );
                    }

                    //if(count($result) > 0 && count($userDetail) > 0) {
                    if(count($userDetail) > 0) {
                        $mylang = '';
                        if ($userDetail['language_id'] == 1) {
                            $mylang = 'en';
                        }
                        if ($userDetail['language_id'] == 2) {
                            $mylang = 'dk';
                        }

                        $userDetailDTO = new userDTO ();
                        $userbirthDTO = new userBirthDetailDTO ();
                        $userDetailDTO->language = !empty($mylang) ? $mylang : 'en';
                        //$userDetailDTO->user_id = $User_id;
                        $userDetailDTO->portal_id =  !empty($userDetail['PortalId']) ? $userDetail['PortalId'] : 1;

                        //if (empty($user_type)) {
                            $userbirthDTO->UserBirthDetailId  = $userDetail["id"];
                            //$userbirthDTO->UserId = $result[0]["user_id"];
                        //}



                        //BIRTH DATE
                        
                            $userbirthDTO->Day = $userDetail['day']; // Date
                            $userbirthDTO->Month = $userDetail['month']; // Month
                            $userbirthDTO->Year = $userDetail['year']; // Year
                            $userbirthDTO->Hours = $userDetail['hour']; //$result[0]["Hours"];
                            $userbirthDTO->Minutes = $userDetail['minute']; //$result[0]["Minutes"];
                            $userbirthDTO->Seconds = $userDetail['minute']; //$result[0]["Seconds"];
                            $userbirthDTO->GMT = ''; //$result[0]["GMT"];
                            $userbirthDTO->Lagitute = $userDetail['latitude'];
                            $userbirthDTO->Longitute = $userDetail['longitude'];
                            $userbirthDTO->unTimed = 0;     //$result[0]["unTimed"];

                            $userbirthDTO->ZoneRef = $userDetail["zone"];
                            $userbirthDTO->SummerTimeZoneRef = $userDetail["type"];
                            $userbirthDTO->country = $userDetail["country"]; //$result[0]["country_id"];
                            $userbirthDTO->country_id = $userDetail["country_id"];
                            $userbirthDTO->state = $userDetail["state"];
                            $userbirthDTO->city = $userDetail["city_name"]; //$result[0]["city_id"];
                            $userbirthDTO->country_name = $userDetail["country"];
                            
                        $formattedDate = sprintf ( "%04d%02d%02d", $userbirthDTO->Year, $userbirthDTO->Month, $userbirthDTO->Day );
                        $formattedTime = sprintf ( "%02d:%02d", $userbirthDTO->Hours, $userbirthDTO->Minutes );
                        //die('kokok');
                        //$timed_data = ( ( trim ( $userbirthDTO->unTimed ) == 'N') ? true : false);
                        $timed_data = ( ( trim ( $userbirthDTO->unTimed ) == '1' )  ? false : true);

                        if($userbirthDTO->Lagitute >= -90 && $userbirthDTO->Lagitute <= 90 ) {
                            $userbirthDTO->Lagitute = $userbirthDTO->Lagitute * 3600;
                        }

                        if($userbirthDTO->Longitute >= -180 && $userbirthDTO->Longitute <= 180) {
                            $userbirthDTO->Longitute = $userbirthDTO->Longitute * 3600;
                        }
                        $CountryName = $userbirthDTO->country_name;
                        
                        //$userbirthDTO = GetLocationInformation($userbirthDTO);

                        $dailyBirthData = new DailyBirthData( $formattedDate, /* Birth Date	 */
                                                            $formattedTime,                         /* Birth Time	 */
                                                            $timed_data,                            /* timed data  */
                                                            $userbirthDTO->SummerTimeZoneRef,       /* summertime	 */
                                                            $userbirthDTO->ZoneRef,                 /* timezone	 */
                                                            $userbirthDTO->Longitute,               /* longitude	 */
                                                            $userbirthDTO->Lagitute
                                                        );              /* latitude	 */

                        $wheel = new PDF_Wheel_WOW ();
                        $wheel->language = $ReportsDefaultLanguage;

                        $chart = new DailyPersonalChartAPI ( $dailyBirthData );

                        /* NOTE - this is repetition, take from previous context */
                        /* get the planetary context - longitude and house occupancy */
                        for ($planet = 0/* Sun */; $planet < 12/* S.Node */; $planet ++) {
                            $wheel->planet_longitude [$planet] = $chart->m_object [$planets [$planet]] ['longitude'];
                            $wheel->planet_in_house [$planet] = $chart->m_object [$planets [$planet]] ['house'];
                            $wheel->planet_retrograde [$planet] = $chart->m_object [$planets [$planet]] ['retrograde'];
                        }


                        /* now go for the aspects */
                        for($asp = 0; $asp < count ( $chart->m_aspect ); $asp ++) {
                            array_push ( $wheel->planet_aspects, $chart->m_aspect [$asp] );
                        }

                        for($house = 0; $house < 12; $house ++) {
                            $wheel->house_cusp_longitude [$house] = $chart->m_object ['cusp'] [$house + 1];
                        }

                        if ($timed_data === true) {
                            $birthdate = sprintf ( "%02d/%02d/%04d %02d:%02d", $userbirthDTO->Day, $userbirthDTO->Month, $userbirthDTO->Year, $userbirthDTO->Hours, $userbirthDTO->Minutes);
                        } else {
                            $birthdate = sprintf ( "%02d/%02d/%04d", $userbirthDTO->Day, $userbirthDTO->Month, $userbirthDTO->Year );
                        }

                        $coords = sprintf ( "%2d%s%02d %3d%s%02d", abs ( intval (  $dailyBirthData->latitude  ) ), (( $dailyBirthData->latitude >= 0) ? 'N' : 'S'), abs ( intval ( (($dailyBirthData->latitude - intval ( $dailyBirthData->latitude )) * 60) ) ),
                                abs ( intval ( $dailyBirthData->longitude ) ), (($dailyBirthData->longitude >= 0) ? 'W' : 'E'), abs ( intval ( (($dailyBirthData->longitude - intval ( $dailyBirthData->longitude )) * 60) ) ) );

                        $timedelta =  getTimezoneAndSummerTimezoneOnDashboard ($userbirthDTO->ZoneRef);
                        if ($timedelta['summerreff']) {
                            $timediff = $timedelta['summerreff'];
                        } else {
                            $timediff = '0:00';
                        }
                        $timedelta = $timedelta['timezone'];

                        $wheel->wheel_offset = $wheel->house_cusp_longitude [0];

                        $wheel->table_user_info_fname = ucwords (sprintf("%s %s", html_entity_decode(utf8_decode(utf8_encode(trim ( $userDetail['first_name'] )))), html_entity_decode(utf8_decode(utf8_encode(trim ( $userDetail['last_name'] ))))) );
                                $wheel->table_user_info_lname = '';


                        $CityArray = explode('>',  trim ( $userbirthDTO->city ));
                        $CityName = trim ( $userbirthDTO->city );
                        if( count($CityArray) > 0) {
                            $CityName = utf8_decode( $CityArray[0] );
                        }

                        $wheel->table_user_info_birth_weekday = $wheel_top_weekdays [strtolower ( $ReportsDefaultLanguage )] [JDDayOfWeek ( cal_to_jd ( CAL_GREGORIAN, $userbirthDTO->Month, $userbirthDTO->Day, $userbirthDTO->Year ), 1 )];


                        $wheel->table_user_info_birth_date = $birthdate;
                        $wheel->table_user_info_birth_place = trim ( $CityName );
                        $wheel->table_user_info_birth_state = trim ( $CountryName );
                        $wheel->table_user_info_birth_coords = $coords;
                        $wheel->table_user_info_birth_timezone = $timedelta;
                        $wheel->table_user_info_birth_summertime = $timediff;
                        $wheel->table_user_info_house_system = (($timed_data === true) ? 'Placidus' : 'Solar');
                        $wheel->table_user_info_orb_system = $wheel_top_orbsensitivity [strtolower ( $ReportsDefaultLanguage )];

                        $wheel->generateChartWheelXML($wheel, $NatalWheelFileName);
                        $wheel->Close();
                        $filename = "https://www.astrowow.com/Horoscope-wheel-xml/".$data['id'].".natalwheel.xml";
                        echo file_get_contents($filename);
                        exit();
                        //echo 'successfully generated - ( https://www.astrowow.com/Horoscope-wheel-xml/'.$data['id'].'.natalwheel.xml )'; die;
                    }
                }
        } else {
            echo 'You are not valide for this.'; //$_['INVALIDPARAM'];
        }
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

function GetLocationInformationForHorary($data) {
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
        $data->country_name = $actStateList->getStateNameByAbbrev(substr($Result[0]['lkey'] , 0, 2));
    }
    else {
        $data->country_name = $actStateList->getStateNameByAbbrev($data->country);
    }
    $Location = sprintf( "%s, %s", $data->city, $data->country_name);

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

/* function GetSummerTimeZoneANDTimeZone($location, $data) {
    $TimeZoneArray = array();
    global $GobalTimeZone;
    global $GobalTimeZoneOffset;
    global $GobalTimeZoneOffset1;

    if(extension_loaded('acsatlas')) {
        //Get the city info
        $city_info = acs_lookup_city($location);

        if (!$city_info) {
            return $TimeZoneArray;
            //die('The city lookup was unsuccessful.');
        }
        extract($city_info);
// 			$city_info = Array (
// 					[city_index] => 4360
// 					[country_index] => 4
// 					[city] => Pomona
// 					[county] => Los Angeles
// 					[country] => California
// 					[countydup] => 37
// 					[latitude] => 122599
// 					[longitude] => 423905
// 					[typetable] => 83
// 					[zonetable] => 7200)

        //Get the time zone info
        //$time_info = acs_time_change_lookup($month, $day, $year, $hour, $minute, $zonetable, $typetable);
        $time_info = acs_time_change_lookup($data->Month, $data->Day, $data->Year,
                $data->Hours, $data->Minutes, $zonetable, $typetable);
        if (!$time_info) {
            return $TimeZoneArray;
            //die('The time zone lookup was unsuccessful.');
        }
        extract($time_info);
// 			$time_info = Array  (
// 					[zone] => 7200
// 					[type] => 1
// 					[abbr] => PDT
// 					[flagout] => 0)

//        echo "<pre>";
//        print_r($city_info);
//        print_r($time_info);
//        echo "</pre>";
        if($type >= 0) {
            $GobalTimeZone = trim( $abbr);
            if( strtolower( trim( $abbr) ) == 'ut') {
                $GobalTimeZone  = 'UTC';
            }           
            //$GobalTimeZone = timezone_name_from_abbr(strtolower($abbr), $zone);
            //$GobalTimeZone = timezone_name_from_abbr( strtolower($abbr), $zone);
                         
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
//            echo "$ZoneValue <br />";
//            echo "$tmpZone <br />";
//            echo "$tmpZoneDiff <br />";
//            echo "$FinalZone <br />";
            if( $ActualZoneValue < 0) {
                $GobalTimeZoneOffset = number_format(-1 * floatval( $FinalZone ), 2);
                $TimeZoneArray["m_timezone_offset"] = number_format(-1 * floatval( $FinalZone ), 2);
            }
            else {
                $GobalTimeZoneOffset = number_format(floatval( $FinalZone ), 2);
                $TimeZoneArray["m_timezone_offset"] = number_format(floatval( $FinalZone ), 2);
            }
            $TimeZoneArray["m_summertime_offset"] = number_format( floatval( $time_types[$type] ), 2);

            $GobalTimeZoneOffset1 = ($ActualZoneValue * 3600) * -1;            
            $GobalTimeZone = timezone_name_from_abbr(sprintf("%s", strtolower(trim($abbr))), $GobalTimeZoneOffset1);
            if($GobalTimeZone == "") {
                $GobalTimeZone = trim($abbr);
                if( strtolower( trim( $abbr) ) == 'ut') {
                    $GobalTimeZone  = 'UTC';
                }  
            }
//            echo strtolower($GobalTimeZone) . "<br />";
//            echo $GobalTimeZoneOffset1 . "<br />";
//            echo '== ' .timezone_name_from_abbr(sprintf("%s", strtolower(trim($GobalTimeZone))), $GobalTimeZoneOffset1);
//            $GobalTimeZone = timezone_name_from_abbr(sprintf("%s", strtolower(trim($GobalTimeZone))), $GobalTimeZoneOffset1);
        }
    }
    return $TimeZoneArray;
} */


 function GetSummerTimeZoneANDTimeZone($location, $data) {
    $TimeZoneArray = array();

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



//You do not need to alter these functions
function resizeThumbnailImage($thumb_image_name, $image, $width, $height, $start_width, $start_height, $image_type='') {
    if (!empty($image_type) && ($image_type == 'onlywheel')) {
        $newImageWidth = 700;
        $newImageHeight = 700;
    } else {
    	$newImageWidth = 235;
    	$newImageHeight = 235;
    }
	$newImage = imagecreatetruecolor($newImageWidth, $newImageHeight);
	$source = imagecreatefromjpeg($image);
	imagecopyresampled($newImage,$source,0,0,$start_width,$start_height,$newImageWidth,$newImageHeight,$width,$height);
		
	imagejpeg($newImage, $thumb_image_name,90);
	//chmod($thumb_image_name, 0644);
	//chmod($thumb_image_name, 0777); 
	//chmod($thumb_image_name, 0755);
	return $thumb_image_name;
}

function getLocalTimezone($ZoneOffset)
{
    $iTime = time();
    $arr = localtime($iTime);
    $arr[5] += 1900;
    $arr[4]++;
    $iTztime = gmmktime($arr[2], $arr[1], $arr[0], $arr[4], $arr[3], $arr[5], $arr[8]);
    $offset = doubleval(($iTztime-$iTime)/(60*60));
    $zonelist =
    array
    (
        'Kwajalein' => -12.00,
        'Pacific/Midway' => -11.00,
        'Pacific/Honolulu' => -10.00,
        'America/Anchorage' => -9.00,
        'America/Los_Angeles' => -8.00,
        'America/Denver' => -7.00,
        'America/Tegucigalpa' => -6.00,
        'America/New_York' => -5.00,
        'America/Caracas' => -4.30,
        'America/Halifax' => -4.00,
        'America/St_Johns' => -3.30,
        'America/Argentina/Buenos_Aires' => -3.00,
        'America/Sao_Paulo' => -3.00,
        'Atlantic/South_Georgia' => -2.00,
        'Atlantic/Azores' => -1.00,
        'Europe/Dublin' => 0,
        'Europe/Belgrade' => 1.00,
        'Europe/Minsk' => 2.00,
        'Asia/Kuwait' => 3.00,
        'Asia/Tehran' => 3.30,
        'Asia/Muscat' => 4.00,
        'Asia/Yekaterinburg' => 5.00,
        'Asia/Kolkata' => 5.30,
        'Asia/Katmandu' => 5.45,
        'Asia/Dhaka' => 6.00,
        'Asia/Rangoon' => 6.30,
        'Asia/Krasnoyarsk' => 7.00,
        'Asia/Brunei' => 8.00,
        'Asia/Seoul' => 9.00,
        'Australia/Darwin' => 9.30,
        'Australia/Canberra' => 10.00,
        'Asia/Magadan' => 11.00,
        'Pacific/Fiji' => 12.00,
        'Pacific/Tongatapu' => 13.00
    );
    //$index = array_keys($zonelist, $offset);
    $index = array_keys($zonelist, $ZoneOffset);
    if(sizeof($index)!=1){
        return false;
    }
    return $index[0];
}

/**
 * Used to show timezone and summer reff on wheel
 * Created By : Kingslay <kingslay@123789.org>
 * Created Date : April. 24, 2017
 */
function getTimezoneAndSummerTimezoneOnDashboard ($tzone) {
    $TimeZone =  number_format((-1 * $tzone), 2); //number_format(abs($tzone), 2);
    $timedelta_hh = intval($TimeZone );
    $timedelta_mm = number_format( substr($TimeZone , strpos($TimeZone , '.', 0) + 1, 2), 2);
    $tmpMM = number_format( substr($TimeZone , strpos($TimeZone , '.', 0) + 1, 2), 2);
    if($tmpMM != "") {
      if(intval($tmpMM) > 0 && intval($tmpMM) <= 50) {
        $timedelta_mm = 30;
      } else if(intval($tmpMM) > 50 && intval($tmpMM) <= 100) {
        $timedelta_mm = 45;
      }
    }
    $timediff = number_format(floatval(abs(0.00)), 2);
    $timediff_hh = intval($timediff);
    $timediff_mm = number_format( substr( $timediff, strpos($timediff, '.', 0) + 1, 2), 2);
    $tzDetail = array();
    $tzDetail['timezone'] = sprintf("%02d:%02d", $timedelta_hh, $timedelta_mm);
    $tzDetail['summerreff'] = sprintf("%d:%02d", $timediff_hh, $timediff_mm);
    return $tzDetail;
}


/*function SetLatLong(&$bData) {
    $Longitude = 0;
    $Latitude  = 0;
    if($bData['latitude'] > -90 && $bData['latitude'] < 90) {
        $Latitude = $bData['latitude'] * 3600;
    } else {
        $Latitude = $bData['latitude'];
        $bData['latitude'] = $bData['latitude'] / 3600;
    }

    if($bData['longitude'] > -180 && $bData['longitude'] < 180) {
        $Longitude = $bData['longitude'] * 3600;
    } else {
        $Longitude = $bData['longitude'];
        $bData['longitude'] = $bData['longitude'] / 3600;
    }
    

    if (isset($bData['status']) && !empty($bData['status'])) {
      $cond = ['Countries.id' => $bData['country_id'] ];
    } else {
      $cond = ['Countries.id' => $bData['birth_detail']['country_id'] ];
    }

    $birthplace = $bData['city_name'];
    if(isset($bData['popupUser']) && !empty($bData['popupUser'])) {
      $this->loadModel('Countries');
      $countryData = $this->Countries->find('all')
                    ->where($cond)
                    ->first();
    } else {
      $countryData = $this->Countries->find('all')
                    ->where([$cond])
                    //->where(['Countries.id' => $bData['birth_detail']['country_id'] ])
                    ->first();
    }

    $countryAbbr = $countryData['abbr'];


    //if( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' ) {
      if (!empty($bData['place'])) {
          $placeList = $this->Cities->find()->where(['latitude'  => $Latitude, 'longitude' => $Longitude, 'city' => trim($bData['place'])])->first();
      }
      if (!empty($bData['birth_detail']['city_id'])) {
          $placeList = $this->Cities->find()->where(['latitude'  => $Latitude, 'longitude' => $Longitude, 'id' => $bData['birth_detail']['city_id']])->first();
      }
      if (!empty($bData['city_id'])) {
          $placeList = $this->Cities->find()->where(['latitude'  => $Latitude, 'longitude' => $Longitude, 'id' => $bData['city_id']])->first();
      }
      if (!empty($bData['city_name'])) {
          $placeList = $this->Cities->find()->where(['latitude'  => $Latitude, 'longitude' => $Longitude, 'city' => trim($bData['city_name'])])->first();
      }
    if (!empty($placeList)) {
      $countryDetail = $this->Countries->find('all')
                                  ->where(['Countries.id' => $placeList->country_id])
                                  ->select(['Countries.abbr', 'Countries.name'])
                                  ->first();
      $countryAbbr = $countryDetail['abbr'];
      $country_name = $countryDetail['name'];
    }
   
    $Location = sprintf( "%s, %s", $birthplace, $country_name);
    $IsThere = $this->GetSummerTimeZoneANDTimeZone($Location, $bData);

    if(count($IsThere) > 0 ) {
        if (isset($bData['status']) && !empty($bData['status'])) {
          $bData['zone'] = $IsThere['m_timezone_offset'];
          $bData['type'] = $IsThere['m_summertime_offset'];
        } else {
          $bData['birth_detail']['zone'] = $IsThere['m_timezone_offset'];
          $bData['birth_detail']['type'] = $IsThere['m_summertime_offset'];
        }
    }
    else {

    }
}
*/

?>
