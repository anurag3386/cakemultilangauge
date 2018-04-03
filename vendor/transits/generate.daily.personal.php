<?php
/**
 * This is the Initiater page for generating daily personal based on passed user Id
 * 
 * spiritus_movens@net.hr
 * ibenik,Croatia
 */

@ob_start();
$_['UNAUTHORIEDACCESS'] = "Invalid API Key. Authentication failed";
$_['INVALIDPARAM'] = "Invalid parameters";
$_['NOTRANSITFOUND'] = "There is no important transit for the selected day.";
$_['VALIDPARAM'] = "Ok";
$_['TRANSITING'] = "transit";
$_['NATALPLANET'] = "in birth horoscope";
$_['USERNOTFOUND'] = "User not found";

//ini_set("display_errors", 1);
//error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
ini_set("display_errors", 1);
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_COMPILE_ERROR);

date_default_timezone_set ( 'America/Los_Angeles' );


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


require_once(TRASITSVENDORPATH.'/config.php');

$languageId = (isset($lang) && !empty($lang)) ? $lang : 'en'; //(isset($_SESSION['locale']) && !empty($_SESSION['locale'])) ? $_SESSION['locale'] : 'en'; //!empty($this->request->session()->read('locale')) ? $this->request->session()->read('locale') : 'en'; die;
if ($languageId == 'da') {
    $languageId = 'dk';
}
/*if(isset($language)) {
	$languageId = $language;
}*/
if($languageId == "en") {
	require_once(LANGUAGEPATH.'/en/personal-daily/personal_daily.php');
} else if($languageId == "dk") {
	require_once(LANGUAGEPATH.'/dk/personal-daily/personal_daily.php');
} else {
	require_once(LANGUAGEPATH.'/en/personal-daily/personal_daily.php');
}
require_once(INCLUDEPATH.'/functions.php');
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
//require_once (CLASSPATH . '/objects/class.order.php');              //POG CLASS ADDED
require_once (CLASSPATH . '/objects/class.transaction.php');
require_once (CLASSPATH . '/objects/class.portal.php');
//require_once (CLASSPATH . '/objects/class.product.php');
//require_once (CLASSPATH . '/objects/class.product.description.php');
require_once (CLASSPATH . '/objects/class.birthdata.php');          //POG CLASS ADDED
require_once (CLASSPATH . '/objects/class.reportoption.php');
require_once (CLASSPATH . '/objects/class.emailaddress.php');

/* ACS Atlas */
//require_once (CLASSPATH . '/objects/class.acs.atlas.php');
if (!class_exists('ACSStateList')) {
    if(!include(CLASSPATH . '/acs/class.acs.statelist.php')) {
        require_once (CLASSPATH . '/acs/class.acs.statelist.php');
    }
}

/* HI report content */
require_once (CLASSPATH . '/objects/class.bookdk.php');
require_once (CLASSPATH . '/objects/class.astrowow.shorttermtrend_dk.php');
require_once (CLASSPATH . '/objects/class.bookuk.php');
require_once (CLASSPATH . '/objects/class.astrowow.shorttermtrend.php');
require_once (CLASSPATH . '/objects/class.bookdu.php'); 
require_once (CLASSPATH . '/objects/class.bookge.php'); 
require_once (CLASSPATH . '/objects/class.bookgr.php'); 
require_once (CLASSPATH . '/objects/class.bookno.php'); 
require_once (CLASSPATH . '/objects/class.booksp.php'); 
require_once (CLASSPATH . '/objects/class.booksw.php'); 

if(!isset ($apikey) && empty ($apikey) ) {
    $dPersonal = new DailyPersonal(1001, $_['UNAUTHORIEDACCESS']);
    echo json_encode($dPersonal);
    exit(0);
} else {
    if(isset ($user_id) && !empty ($user_id) && isset ($date) && !empty ($date)  ) {
        $User_id = $user_id;
        $userType = '';
        if (strpos($User_id, '_') !== false) {
            $userType = 'anotherPerson';
        }

        $pDate = $date;
        $checkDate = explode('-', $pDate);

        $userbirthDTO = new UserBirthDetailDTO();
        $userDetailDTO = new UserDTO();
        $user = new User();

        $userDetail =  $user->GetUserDetail($User_id, $userType);
        $result =  $user->GetUserBirthDetailByUserId($User_id, $userType);

        if(count($result) > 0 && count($userDetail) > 0) {
            $userDetailDTO->language = !empty($languageId) ? $languageId : 'en';
            $userDetailDTO->user_id = $User_id;
            $userDetailDTO->portal_id = (isset($userDetail[0]['PortalId']) && !empty($userDetail[0]['PortalId'])) ? $userDetail[0]['PortalId'] : 2;
            if (!empty($userType)) {
                //$userbirthDTO->UserBirthDetailId = $result[0]["id"];
                $userbirthDTO->UserId = $result[0]["added_by"];
                $dob = explode('-', $result[0]["dob"]);
            } else {
                $userbirthDTO->UserBirthDetailId = $result[0]["id"];
                $userbirthDTO->UserId = $result[0]["user_id"];

                $dob = explode('-', $result[0]["date"]);
            }
            $dob_time = explode(':', $result[0]["time"]);
            //BIRTH DATE
            $userbirthDTO->Day        = $dob[2]; //$result[0]["Day"];
            $userbirthDTO->Month      = $dob[1]; //$result[0]["Month"];
            $userbirthDTO->Year       = $dob[0]; //$result[0]["Year"];
            $userbirthDTO->Hours      = $dob_time[0]; //$result[0]["Hours"];
            $userbirthDTO->Minutes    = $dob_time[1]; //$result[0]["Minutes"];
            $userbirthDTO->Seconds    = $dob_time[1]; //$result[0]["Seconds"];
            $userbirthDTO->GMT        = ''; //$result[0]["GMT"];
            $userbirthDTO->Lagitute   = $result[0]["latitude"]; //$result[0]["Lagitute"];
            $userbirthDTO->Longitute  = $result[0]["longitude"]; //$result[0]["Longitute"];
            $userbirthDTO->unTimed    = 0; //$result[0]["unTimed"];

            $userbirthDTO->ZoneRef    = $result[0]["zone"]; //$result[0]["ZoneRef"];
            $userbirthDTO->SummerTimeZoneRef = $result[0]["type"]; //$result[0]["SummerTimeZoneRef"];

            $userbirthDTO->country      = $result[0]["countryname"]; //$result[0]["country"];
            $userbirthDTO->country_id   = $result[0]["country_id"];
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

            $userDetailDTO->language = $languageId;
            switch (strtoupper ( $userDetailDTO->language )) {
            //switch (strtoupper ( $languageId )) {
                case 'EN' :
                    $book = new BookUK ();
                    $shorttermtrend = new ShortTermTrend ();
                    break;
                case 'DK' :
                    $book = new BookDK ();
                    $shorttermtrend = new ShortTermTrend_DK ();
                    break;
                default :
                    $book = new BookUK ();
                    $shorttermtrend = new ShortTermTrend ();
                    break;
            }
            if(count($checkDate) > 0) {
                $month = $checkDate[1];
                $day = $checkDate[0];
                $year = $checkDate[2];
                $CheckPassedDate = sprintf("%04d-%02d-%02d", $year, $month,  $day);
                
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
                    
                    if($TransitDate == $CheckPassedDate) {
                        $TransitPlanet =  trim ( substr ( $item, 17, 4 ) );
                        $Aspect =  trim ( substr ( $item, 21, 3 ) );
                        $NatalPlanet =  trim ( substr ( $item, 24, 4 ) );
                        
//                        echo "<pre>**** <br />$item</pre>";
//                        echo "<pre>$TransitDate = [ TP : $TransitPlanet ] = [ A : $Aspect ] = [ NP : $NatalPlanet ]</pre>";

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

                        if( intval( $TransitPlanet ) == 1001 /* Moon */ ) {
                            // error
                        }

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

                        if( $TransitPlanet == 1000 ) {
                            //SUN
                            $TrendIndex = (($AspectIndex * 13) + ($NatalPlanet - 1000));
                        } else {
                            //Mercury to Pluto
                            $TrendIndex = (39 + ((($TransitPlanet - 1002) * 39) + ($AspectIndex * 13) + ($NatalPlanet - 1000)));
                        }

                        switch( $userDetailDTO->language ) {
                            case 'danish':
                            case 'dk':
                                $Trend = new ShortTermTrend_DK();
                                break;
                            case 'en':
                            case 'english':
                            default:
                                $Trend = new ShortTermTrend();
                                break;
                        }
                        $Trend->Get( intval( $TrendIndex + 1 ) );

                        $Title .= sprintf(" %s ", $_['NATALPLANET']);                        
                        $Desciption = $Trend->trendtext;
                        $Que1 = $Trend->txtques1;
                        $Ans1 = $Trend->txtans1;
                        $Que2 = $Trend->txtques2;
                        $Ans2 = $Trend->txtans2;
                        $Que3 = $Trend->txtques3;
                        $Ans3 = $Trend->txtans3;

                        $dpData = new DailyPersonalData();
                        $dpData->PredictionDate = sprintf("%04d-%02d-%02d", $year, $month,  $day);;
                        $dpData->Title =  $Title;
                        $dpData->Description = html_entity_decode(utf8_encode($Desciption));
                        $dpData->Quetion1 = html_entity_decode(utf8_encode($Que1));
                        $dpData->Answer1 = html_entity_decode(utf8_encode($Ans1));
                        $dpData->Quetion2 = html_entity_decode(utf8_encode($Que2));
                        $dpData->Answer2 = html_entity_decode(utf8_encode($Ans2));
                        $dpData->Quetion3 = html_entity_decode(utf8_encode($Que3));
                        $dpData->Answer3 = html_entity_decode(utf8_encode($Ans3));

                        $dPersonal->AddPredictionData($dpData);                        
                    }
                }
                if(count($dPersonal->DailyPredictionData) == 0) {
                    $dPersonal->Code = 1004;
                    $dPersonal->Message = $_['NOTRANSITFOUND'];
                }                
                ob_end_clean();
                header('Content-Type: text/html; charset=utf-8');
                $dPersonal = (array) $dPersonal;
                $returnData = '';
                if (!$dPersonal['Code']) {
                    $returnData = DailyPredictionDataOnSelectedDate ($dPersonal);
                } else {
                    $returnData .= "<p>".$dPersonal['Message']."</p>";
                }
                echo $returnData;
            } else {
                $dPersonal = new DailyPersonal(1002, $_['INVALIDPARAM']);
                ob_end_clean();
                header('Content-Type: text/html; charset=utf-8');
                $dPersonal = (array) $dPersonal;
                $returnData = '';
                if (!$dPersonal['Code']) {
                    $returnData = DailyPredictionDataOnSelectedDate ($dPersonal);
                } else {
                    $returnData .= "<p>".$dPersonal['Message']."</p>";
                }
                echo $returnData;
                //echo json_encode($dPersonal);
            }
        } else {
            $dPersonal = new DailyPersonal(1003, $_['USERNOTFOUND']);
            ob_end_clean();
            header('Content-Type: text/html; charset=utf-8');
            $dPersonal = (array) $dPersonal;
            $returnData = '';
            if (!$dPersonal['Code']) {
                $returnData = DailyPredictionDataOnSelectedDate ($dPersonal);
            } else {
                $returnData .= "<p>".$dPersonal['Message']."</p>";
            }
            echo $returnData;
            //echo json_encode($dPersonal);
        }
    }
    else {
        $dPersonal = new DailyPersonal(1002, $_['INVALIDPARAM']);
        ob_end_clean();
        header('Content-Type: text/html; charset=utf-8');
        $dPersonal = (array) $dPersonal;
        $returnData = '';
        if (!$dPersonal['Code']) {
            $returnData = DailyPredictionDataOnSelectedDate ($dPersonal);
        } else {
            $returnData .= "<p>".$dPersonal['Message']."</p>";
        }
        echo $returnData;
        //echo json_encode($dPersonal);
    }
}

/**
 * Return selected date prediction / influence data
 * Created By : Krishna Gupta
 * Created Date : Nov. 06, 2016
*/
function DailyPredictionDataOnSelectedDate ($dPersonal) {
    $returnData = '';
    foreach ($dPersonal['DailyPredictionData'] as $key => $prediction) {
        $dd = (array) $prediction;
        $cont = explode(':', $dd['Description']);
        if (!$key) {
            $h3class = 'ui-accordion-header ui-corner-top ui-state-default ui-accordion-icons geth3content ui-accordion-header-active ui-state-active geth3content';
            $arialSelected = 'true';
            $tabindex = 0;
            $display = 'block';
            $sign = '<span class="ui-accordion-header-icon ui-icon ui-icon-triangle-1-s"></span>';
            $ariaHidden = 'false';
            $div1 = 'getDivContent accordion-inner ui-accordion-content ui-corner-bottom ui-helper-reset ui-widget-content ui-accordion-content-active firstChild';
        } else {
            $h3class = 'ui-accordion-header ui-corner-top ui-accordion-header-collapsed ui-corner-all ui-state-default ui-accordion-icons geth3content';
            $arialSelected = 'false';
            $tabindex = -1;
            $display = 'none';
            $ariaHidden = 'true';
            $sign = '<span class="ui-accordion-header-icon ui-icon ui-icon-triangle-1-e"></span>';
            $div1 = 'getDivContent accordion-inner ui-accordion-content ui-corner-bottom ui-helper-reset ui-widget-content';
        }
        $returnData .= "<h3 class='".$h3class."' role='tab' aria-selected='".$arialSelected."' aria-expanded='".$arialSelected."' tabindex='".$tabindex."' ref='title_".$key."'>".$sign.$dd['Title']."</h3>"; // Title of prediction
        //if (!$key) {
            $returnData .= "<div class='".$div1."' style='display: block;' role='tabpanel' aria-hidden='".$ariaHidden."'>";
            if (isset($cont[0]) && !empty($cont[0])) {
                $returnData .= "<h4>".$cont[0]." : </h4>";
            }
            if (isset($cont[1]) && !empty($cont[1])) {
                $returnData .= "<b>".trim($cont[1])."</b>";
            }
        //}
        for ($i=1; $i < 11; $i++) {
            if (array_key_exists('Quetion'.$i, $dd) && array_key_exists('Answer'.$i, $dd)) {
                $returnData .= "<div class='accordion-sec'><span>".$dd['Quetion'.$i]."</span><p>".$dd['Answer'.$i]."</p></div>";
            }
        }
        $returnData .= "</div>";
    }
    return $returnData;
}



function GetLocationInformation($data) {
    $Longitude = $data->Longitute;
    $Latitude = $data->Lagitute;

    /*$sql =  "SELECT * FROM `acsatlas`".
            " WHERE upper(placename)='". strtoupper($data->city) ."' ".
            " AND (upper(SUBSTR(lkey, 0, 2))= '".  strtoupper( $data->country) ."') ".
            " ORDER BY lkey";*/
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

    if( count( $Result ) > 0 ) {
        if (isset($Result[0]['lkey']) && !empty($Result[0]['lkey'])) {
            $data->country_name = $actStateList->getStateNameByAbbrev(substr( $Result[0]['lkey'] , 0, 2));
        } else {
            $data->country_name = $data->country;
        }
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
/*function GetSummerTimeZoneANDTimeZone($location, $data) {
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
}*/
?>