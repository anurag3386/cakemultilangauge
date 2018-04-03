<?php
if ( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' ) {
                            //die ('hererer'); //echo '<pre>'; print_r($result); die;
                        }
/**
 * This is the Initiater page for generating daily personal based on passed user Id
 */
@ob_start();

ini_set("display_errors", 1);
//error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
//ini_set("display_errors", 0);
error_reporting(E_ALL);

date_default_timezone_set ( 'America/Los_Angeles' );

//define( 'ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/' );
//echo $_SERVER['DOCUMENT_ROOT'];
//define( 'ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . 'astrowow/' );

if ($_SERVER['SERVER_NAME'] == 'localhost') {
    define( 'ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/astrowow' );
} else {
    define( 'ROOTPATH', $_SERVER['DOCUMENT_ROOT'] );
}
define ( 'PERSONAL_HOROSCOPE_IMAGE_PATH', $_SERVER['DOCUMENT_ROOT'] . '/astrowow/' );


define ( 'CLASSPATH', ROOTPATH . '/vendor/Horoscope-wheel/classes' );
define ( 'LIBPATH', ROOTPATH . '/vendor/Horoscope-wheel/lib' );
define ( 'SPOOLPATH', ROOTPATH . '/vendor/Horoscope-wheel/var/spool' );
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

$ReportsDefaultLanguage = (isset($_SESSION['locale']) && !empty($_SESSION['locale'])) ? $_SESSION['locale'] : 'en'; //'en';
if ($ReportsDefaultLanguage == 'da') {
    $ReportsDefaultLanguage = 'dk';
}
$planets = array ('Sun', 'Moon', 'Mercury', 'Venus', 'Mars', 'Jupiter', 'Saturn', 'Uranus', 'Neptune', 'Pluto', 'NNode', 'SNode', 'Ascendant', 'MC', 'IC', 'Descendant' );

$apikey = $apikey; //$_REQUEST['apikey'];
$task = $task; //$_REQUEST['task'];
$uid = $uid; //$_REQUEST['uid'];
$user_type = $aper; //!empty($_REQUEST['aper']) ? trim($_REQUEST['aper']) : '';

//echo $apikey.' => '.$task.' => '.$uid.' => '.$user_type; die;

if(!isset ($apikey) && empty ($apikey) ) {
    //$dPersonal = new DailyPersonal(1001, $_['UNAUTHORIEDACCESS']);
    //echo $_['UNAUTHORIEDACCESS'];
    exit(0);
} else {
    if(isset ($task) && !empty ($task)) {
        if(strtolower( $task ) == 'natalwheel') {
            if( isset ($uid) && !empty ($uid) ) {
                $User_id = $uid;

                if (empty($user_type)) { // For registered user 
                    $NatalWheelJPGFileName = sprintf ( "%s/%d.natalwheel.jpg", SPOOLPATH, $User_id );
                    $NatalWheelJPGFileName1 = sprintf ( "http://%s/var/spool/%d.natalwheel.jpg", $_SERVER['SERVER_NAME']."/astrowow", $User_id );
                    $NatalWheelJPGThumb = sprintf ( "http://%s/var/spool/%d.natalwheelthumb.jpg", $_SERVER['SERVER_NAME']."/astrowow", $User_id );

                    $NatalWheelFileName = sprintf ( "%s/%d.natalwheel.pdf", SPOOLPATH, $User_id );
                    $NatalWheelFileName1 = sprintf ( "http://%s/var/spool/%d.natalwheel.pdf", $_SERVER['SERVER_NAME']."/astrowow", $User_id );
                } else { // For added another persons
                    $cust = 'anotherPerson_'.$User_id;
                    $NatalWheelJPGFileName = sprintf ( "%s/%s.natalwheel.jpg", SPOOLPATH, $cust );
                    $NatalWheelJPGFileName1 = sprintf ( "http://%s/var/spool/%s.natalwheel.jpg", $_SERVER['SERVER_NAME']."/astrowow", $cust );
                    $NatalWheelJPGThumb = sprintf ( "http://%s/var/spool/%s.natalwheelthumb.jpg", $_SERVER['SERVER_NAME']."/astrowow", $cust );

                    $NatalWheelFileName = sprintf ( "%s/%s.natalwheel.pdf", SPOOLPATH, $cust );
                    $NatalWheelFileName1 = sprintf ( "http://%s/var/spool/%s.natalwheel.pdf", $_SERVER['SERVER_NAME']."/astrowow", $cust );
                }



				if(file_exists($NatalWheelFileName)) {
                	unlink($NatalWheelFileName);
                }
                $user = new User ();

                if (empty($user_type)) {
                    $userDetail = $user->GetUserDetail($User_id, $user_type);
                    $userPersonalDetail = $user->GetUserProfileDetailByUserId($User_id);
                    $result = $user->GetUserBirthDetailByUserId($User_id);
                } else {
                    $userDetail = $user->GetUserDetail($User_id, $user_type);
                }
                //echo '<pre>'; print_r($userDetail); die;
                //echo '<pre>'; print_r($result); die;
                //echo '<pre>';  print_r($userPersonalDetail); print_r($result); die;


                if($userDetail) {

                    if(strlen($userDetail[0]['language']) > 2) {
                        $ReportsDefaultLanguage = $languageCodes[strtolower( $userDetail[0]['language'] ) ];
                    } else {
                        $ReportsDefaultLanguage = strtolower( $userDetail[0]['DefaultLanguage'] );
                    }

                    //if(count($result) > 0 && count($userDetail) > 0) {
                    if(count($userDetail) > 0) {
                        $mylang = '';
                        if ($userDetail[0]['language_id'] == 1) {
                            $mylang = 'en';
                        }
                        if ($userDetail[0]['language_id'] == 2) {
                            $mylang = 'dk';
                        }
                        $userDetailDTO = new userDTO ();
                        $userbirthDTO = new userBirthDetailDTO ();
                        $userDetailDTO->language = !empty($mylang) ? $mylang : 'en';
                        $userDetailDTO->user_id = $User_id;
                        $userDetailDTO->portal_id =  !empty($userDetail[0]['PortalId']) ? $userDetail[0]['PortalId'] : 1;

                        if (empty($user_type)) {
                            $userbirthDTO->UserBirthDetailId  = $result[0]["id"];
                            $userbirthDTO->UserId = $result[0]["user_id"];
                        }



                        //BIRTH DATE
                        if (empty($user_type)) { // For registered user
                            $dob = explode('-', $result[0]["date"]);
                            $dob_time = explode(':', $result[0]["time"]);
                            $userbirthDTO->Day = $dob[2]; // Date
                            $userbirthDTO->Month = $dob[1]; // Month
                            $userbirthDTO->Year = $dob[0]; // Year
                            $userbirthDTO->Hours = $dob_time[0]; //$result[0]["Hours"];
                            $userbirthDTO->Minutes = $dob_time[1]; //$result[0]["Minutes"];
                            $userbirthDTO->Seconds = $dob_time[1]; //$result[0]["Seconds"];
                            $userbirthDTO->GMT = ''; //$result[0]["GMT"];
                            $userbirthDTO->Lagitute = $result[0]["latitude"];
                            $userbirthDTO->Longitute = $result[0]["longitude"];
                            $userbirthDTO->unTimed = 0;     //$result[0]["unTimed"];

                            $userbirthDTO->ZoneRef = $result[0]["zone"];
                            $userbirthDTO->SummerTimeZoneRef = $result[0]["type"];
                            $userbirthDTO->country = $result[0]["countryname"]; //$result[0]["country_id"];
                            $userbirthDTO->country_id = $result[0]["country_id"];
                            $userbirthDTO->state = $result[0]["state"];
                            $userbirthDTO->city = $result[0]["cityname"]; //$result[0]["city_id"];
                            $userbirthDTO->country_name = $result[0]["countryname"];
                        } else { // For added another person by registered user
                            $dob = explode('-', $userDetail[0]["dob"]);
                            $dob_time = explode(':', $userDetail[0]["time"]);
                            $userbirthDTO->Day = $dob[2]; // Date
                            $userbirthDTO->Month = $dob[1]; // Month
                            $userbirthDTO->Year = $dob[0]; // Year
                            $userbirthDTO->Hours = $dob_time[0]; //$result[0]["Hours"];
                            $userbirthDTO->Minutes = $dob_time[1]; //$result[0]["Minutes"];
                            $userbirthDTO->Seconds = $dob_time[1]; //$result[0]["Seconds"];
                            $userbirthDTO->GMT = ''; //$result[0]["GMT"];
                            $userbirthDTO->Lagitute = $userDetail[0]["latitude"];
                            $userbirthDTO->Longitute = $userDetail[0]["longitude"];
                            $userbirthDTO->unTimed = 0;     //$result[0]["unTimed"];

                            $userbirthDTO->ZoneRef = $userDetail[0]["zone"];
                            $userbirthDTO->SummerTimeZoneRef = $userDetail[0]["type"];
                            $userbirthDTO->country = $userDetail[0]["country"]; //$result[0]["country_id"];
                            $userbirthDTO->country_id = $userDetail[0]["country_id"];
                            $userbirthDTO->state = $userDetail[0]["county"];
                            $userbirthDTO->city = $userDetail[0]["city"]; //$result[0]["city_id"];
                            $userbirthDTO->country_name = $userDetail[0]["country"];
                        }
                        $formattedDate = sprintf ( "%04d%02d%02d", $userbirthDTO->Year, $userbirthDTO->Month, $userbirthDTO->Day );
                        $formattedTime = sprintf ( "%02d:%02d", $userbirthDTO->Hours, $userbirthDTO->Minutes );
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
                        $wheel->Open ();
                        $wheel->AddFont ( 'wows', '', 'wows.php' ); // text
                        $wheel->AddFont ( 'ww_rv1', '', 'ww_rv1.php' ); // graphics
                        $wheel->SetDisplayMode ( 'fullpage' );
                        $wheel->SetAutoPageBreak ( false );

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


                        /* daylight savings offset */
                        /*$timediff = number_format(floatval(abs($userbirthDTO->SummerTimeZoneRef )), 2);
                        $timediff = str_replace('.', ':', $timediff);*/
                        /* timezone offset */
                        /*$timedelta =  number_format(abs($userbirthDTO->ZoneRef), 2);
                        $timedelta = str_replace('.', ':', $timedelta);*/
                        $timedelta =  getTimezoneAndSummerTimezoneOnDashboard ($userbirthDTO->ZoneRef);
                        if ($timedelta['summerreff']) {
                            $timediff = $timedelta['summerreff'];
                        } else {
                            $timediff = '0:00';
                        }
                        $timedelta = $timedelta['timezone'];

                        $wheel->wheel_offset = $wheel->house_cusp_longitude [0];


                        if (empty($user_type)) { // For registered user
                            if($userPersonalDetail) {
                                $wheel->table_user_info_fname = ucwords (sprintf("%s %s", html_entity_decode(utf8_decode(utf8_encode(trim ( $userPersonalDetail[0]['first_name'] )))), html_entity_decode(utf8_decode(utf8_encode(trim ( $userPersonalDetail[0]['last_name'] ))))) );
                                $wheel->table_user_info_lname = '';
                            } else {
                                $wheel->table_user_info_fname = trim ( "Not Provided" );
                                $wheel->table_user_info_lname = '';
                            }
                        } else { // For another persons
                            if($userDetail) {
                                $wheel->table_user_info_fname = ucwords (sprintf("%s %s", html_entity_decode(utf8_decode(utf8_encode(trim ( $userDetail[0]['fname'] )))), html_entity_decode(utf8_decode(utf8_encode(trim ( $userDetail[0]['lname'] ))))) );
                                $wheel->table_user_info_lname = '';

                            } else {
                                $wheel->table_user_info_fname = trim ( "Not Provided" );
                                $wheel->table_user_info_lname = '';
                            }
                        }

                        $CityArray = explode('>',  trim ( $userbirthDTO->city ));
                        $CityName = trim ( $userbirthDTO->city );
                        if( count($CityArray) > 0) {
                            $CityName = utf8_decode( $CityArray[0] );
                        }

                        $wheel->table_user_info_birth_weekday = $wheel_top_weekdays [strtolower ( $ReportsDefaultLanguage )] [JDDayOfWeek ( cal_to_jd ( CAL_GREGORIAN, $userbirthDTO->Month, $userbirthDTO->Day, $userbirthDTO->Year ), 1 )];


                        $wheel->table_user_info_birth_date = $birthdate;
                        //$wheel->table_user_info_birth_place = sprintf("%s %s", trim ( $userbirthDTO->city ), trim ( $userbirthDTO->state ));
                        $wheel->table_user_info_birth_place = trim ( $CityName );
                        $wheel->table_user_info_birth_state = trim ( $CountryName );
                        $wheel->table_user_info_birth_coords = $coords;
                        $wheel->table_user_info_birth_timezone = $timedelta;
                        $wheel->table_user_info_birth_summertime = $timediff;
                        $wheel->table_user_info_house_system = (($timed_data === true) ? 'Placidus' : 'Solar');
                        $wheel->table_user_info_orb_system = $wheel_top_orbsensitivity [strtolower ( $ReportsDefaultLanguage )];
						
                        $wheel->generateChartWheelPage (); // generating full horoscope wheel chart (bin/wheel/class.report.pdf.wheel.wow.php)

                        if(file_exists($NatalWheelFileName)) {
                        	unlink($NatalWheelFileName);
                        }
                        if(file_exists($NatalWheelJPGFileName)) {
                        	unlink($NatalWheelJPGFileName);
                        }

                        $wheel->Output ( $NatalWheelFileName , "F");
                        $wheel->Close();

                        $generateImage = "/usr/bin/convert -alpha off -density 125 \"{$NatalWheelFileName}[0]\" -colorspace RGB -strip -quality 100 -geometry 150% $NatalWheelJPGFileName";
                        exec($generateImage);
                        
                        $im;
                        
                        if( isset ($_REQUEST['rs']) &&  !empty ($_REQUEST['rs']) ) {
							$width  = 1095;
                            $height = 1095;
                            $x1 =   365;
                            $y1 =   310;
							
                            $cropped = resizeThumbnailImage($NatalWheelJPGFileName, $NatalWheelJPGFileName,$width, $height, $x1, $y1);
                            $im = imagecreatefromjpeg($cropped);

                            //copy('foo/test.php', 'bar/test.php');

                            //show the image
                            ob_end_clean();                            
//							flush();
                            // Set the content type header - in this case image/jpeg
                            header('Content-Type: image/jpeg');
                            // Output the image
                            imagejpeg($im);
                            // Free up memory
                            imagedestroy($im);
                            exit;
                        } else {
                                $im = imagecreatefromjpeg($NatalWheelJPGFileName);
                            /**
                             * Added By : Krishna Gupta
                             * Created Date : Nov. 21, 2016
                             */

                            $folderpath = '';
                            if ($_SERVER['SERVER_NAME'] == 'localhost') {
                                $folderpath = $_SERVER['DOCUMENT_ROOT'] . '/astrowow/webroot/user-personal-horoscope/';
                                if (empty($user_type)) { // For registered user
                                    $source_horo_image_path = $_SERVER['DOCUMENT_ROOT'] . '/astrowow/vendor/Horoscope-wheel/var/spool/'.$User_id.'.natalwheel.jpg';
                                    $desc_horo_image_path = $folderpath.$User_id.'.natalwheel.jpg';
                                    $source_horo_image_path_pdf = $_SERVER['DOCUMENT_ROOT'] . '/astrowow/vendor/Horoscope-wheel/var/spool/'.$User_id.'.natalwheel.pdf';
                                    $desc_horo_image_path_pdf = $folderpath.$User_id.'.natalwheel.pdf';
                                } else {
                                    $source_horo_image_path = $_SERVER['DOCUMENT_ROOT'] . '/astrowow/vendor/Horoscope-wheel/var/spool/anotherPerson_'.$User_id.'.natalwheel.jpg';
                                    $desc_horo_image_path = $folderpath.'anotherPerson_'.$User_id.'.natalwheel.jpg';
                                    $source_horo_image_path_pdf = $_SERVER['DOCUMENT_ROOT'] . '/astrowow/vendor/Horoscope-wheel/var/spool/anotherPerson_'.$User_id.'.natalwheel.pdf';
                                    $desc_horo_image_path_pdf = $folderpath.'anotherPerson_'.$User_id.'.natalwheel.pdf';
                                }
                            } else {
                                $folderpath = $_SERVER['DOCUMENT_ROOT'].'/webroot/user-personal-horoscope/';
                                if (empty($user_type)) { // For registered user
                                    $source_horo_image_path = $_SERVER['DOCUMENT_ROOT'] . '/vendor/Horoscope-wheel/var/spool/'.$User_id.'.natalwheel.jpg';
                                    $desc_horo_image_path = $folderpath.$User_id.'.natalwheel.jpg';
                                    $source_horo_image_path_pdf = $_SERVER['DOCUMENT_ROOT'] . '/vendor/Horoscope-wheel/var/spool/'.$User_id.'.natalwheel.pdf';
                                    $desc_horo_image_path_pdf = $folderpath.$User_id.'.natalwheel.pdf';
                                } else {
                                    $source_horo_image_path = $_SERVER['DOCUMENT_ROOT'] . '/vendor/Horoscope-wheel/var/spool/anotherPerson_'.$User_id.'.natalwheel.jpg';
                                    $desc_horo_image_path = $folderpath.'anotherPerson_'.$User_id.'.natalwheel.jpg';
                                    $source_horo_image_path_pdf = $_SERVER['DOCUMENT_ROOT'] . '/vendor/Horoscope-wheel/var/spool/anotherPerson_'.$User_id.'.natalwheel.pdf';
                                    $desc_horo_image_path_pdf = $folderpath.'anotherPerson_'.$User_id.'.natalwheel.pdf';
                                }
                            }

                            if (!file_exists($folderpath)) { // create folder if folder not exists
                                mkdir($folderpath, 0777, true);
                            }
                            // If horoscope image exists then copy that image
                            if(!copy($source_horo_image_path, $desc_horo_image_path)) {
                                echo "Canot Copy file"; die;
                            }

                            // Copy horoscope pdf file
                            if(!copy($source_horo_image_path_pdf, $desc_horo_image_path_pdf)) {
                                echo "Canot Copy pdf file"; die;
                            }

                            onlywheel ($NatalWheelJPGFileName, $user_type, $User_id); // For creating only horoscope wheel of user

                            // END

                            //show the image
                            ob_end_flush();
                            ob_end_clean();
                            // Set the content type header - in this case image/jpeg
                            header('Content-Type: image/jpeg');
                            // Output the image
                            imagejpeg($im);
                            // Free up memory
                            imagedestroy($im);
                            //return true;
                            exit;
                        }
                    }
                }
            }
            if ($uid) {
                return true;
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

/**
 * This function is used to copy only horoscope wheel of user
 * Created By : Kingslay <kingslay@123789.org>
 * created Date : January 27, 2017
 */
function onlywheel ($NatalWheelJPGFileName, $user_type, $User_id) {
    /*if( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' ) {
        $width  = 700;
        $height = 700;
        $x1 = 365;
        $y1 = 310;
    } else {*/
        $width  = 1095;
        $height = 1095;
        $x1 = 365;
        $y1 = 310;
        //echo 'onlywheel';
    //}
    //if( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' ) {
        $cropped = resizeThumbnailImage($NatalWheelJPGFileName, $NatalWheelJPGFileName,$width, $height, $x1, $y1, 'onlywheel');
    /*} else {
        $cropped = resizeThumbnailImage($NatalWheelJPGFileName, $NatalWheelJPGFileName,$width, $height, $x1, $y1);
    }*/
    $imgg = imagecreatefromjpeg($cropped);


    $folderpath = '';
    if ($_SERVER['SERVER_NAME'] == 'localhost') {
        $folderpath = $_SERVER['DOCUMENT_ROOT'] . '/astrowow/webroot/user-personal-horoscope/';
        if (empty($user_type)) { // For registered user
            $source_horo_image_path = $_SERVER['DOCUMENT_ROOT'] . '/astrowow/vendor/Horoscope-wheel/var/spool/'.$User_id.'.natalwheel.jpg';
            $wheel_horo_image_path = $folderpath.$User_id.'.onlywheel.jpg';
        } else {
            $source_horo_image_path = $_SERVER['DOCUMENT_ROOT'] . '/astrowow/vendor/Horoscope-wheel/var/spool/anotherPerson_'.$User_id.'.natalwheel.jpg';
            $wheel_horo_image_path = $folderpath.'anotherPerson_'.$User_id.'.onlywheel.jpg';
        }
    } else {
        $folderpath = $_SERVER['DOCUMENT_ROOT'].'/webroot/user-personal-horoscope/';
        if (empty($user_type)) { // For registered user
            $source_horo_image_path = $_SERVER['DOCUMENT_ROOT'] . '/vendor/Horoscope-wheel/var/spool/'.$User_id.'.natalwheel.jpg';
            $wheel_horo_image_path = $folderpath.$User_id.'.onlywheel.jpg';
        } else {
            $source_horo_image_path = $_SERVER['DOCUMENT_ROOT'] . '/vendor/Horoscope-wheel/var/spool/anotherPerson_'.$User_id.'.natalwheel.jpg';
            $wheel_horo_image_path = $folderpath.'anotherPerson_'.$User_id.'.onlywheel.jpg';
        }
    }
    //echo $horo_image; die;

    if (!file_exists($folderpath)) { // create folder if folder not exists
        mkdir($folderpath, 0777, true);
    }

    //if (file_exists($source_horo_image_path)) { // check created image exist or not
        // If horoscope image exists then copy that image
        if(!copy($source_horo_image_path, $wheel_horo_image_path)) {
            echo "Canot Copy file"; die;
        }
    //}
    // END

}


?>
