<?php
/**
 * This is the Initiater page for generating daily personal based on passed user Id
 */
@ob_start();

ini_set("display_errors", 1);
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
//ini_set("display_errors", 0);
//error_reporting(E_ALL);

date_default_timezone_set ( 'America/Los_Angeles' );

define( 'ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/' );
//define( 'ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . 'astrowow/' );
define ( 'CLASSPATH', ROOTPATH . '/classes' );
define ( 'LIBPATH', ROOTPATH . '/lib' );
define ( 'SPOOLPATH', ROOTPATH . 'var/spool' );

require_once(ROOTPATH.'config.php');

require_once(ROOTPATH.'bal/include.php');
require_once(ROOTPATH.'bal/user.php');
require_once(ROOTPATH.'bal/goldenCircle.php');
require_once(ROOTPATH.'dto/userDTO.php');

/* language resources */
require_once (ROOTPATH . '/include/lang/en.php');
require_once (ROOTPATH . '/include/lang/dk.php');
require_once (ROOTPATH . '/include/lang/du.php');
require_once (ROOTPATH . '/include/lang/ge.php');
/* add greek		 */
require_once (ROOTPATH . '/include/lang/no.php');
/* add portugese	 */
require_once (ROOTPATH . '/include/lang/sp.php');
require_once (ROOTPATH . '/include/lang/sw.php');

/* POG data */
require_once (CLASSPATH . '/configuration.php');
require_once (CLASSPATH . '/objects/class.database.php');
require_once (CLASSPATH . '/objects/class.pog_base.php');

require_once (CLASSPATH . '/objects/class.state.php');
require_once (CLASSPATH . '/objects/class.transaction.php');
require_once (CLASSPATH . '/objects/class.portal.php');
//require_once (CLASSPATH . '/objects/class.product.php');
//require_once (CLASSPATH . '/objects/class.product.description.php');
require_once (CLASSPATH . '/objects/class.birthdata.php');              //POG CLASS ADDED
require_once (CLASSPATH . '/objects/class.reportoption.php');
require_once (CLASSPATH . '/objects/class.emailaddress.php');

/* ACS Atlas */
//require_once (CLASSPATH . '/objects/class.acs.atlas.php');
//require_once (CLASSPATH . '/acs/class.acs.statelist.php');
if (!class_exists('ACSStateList')) {
    if(!include(CLASSPATH . '/acs/class.acs.statelist.php')) {
        require_once (CLASSPATH . '/acs/class.acs.statelist.php');
    }
}

/* font information */
define ( 'FPDF_FONTPATH', LIBPATH . '/fpdf/fonts/' );
require_once (LIBPATH . '/fpdf/fpdf.php');
require_once (LIBPATH . '/fpdi/fpdi.php');

/* astrolog data */
// targetted for development action
// generate XML
require_once(CLASSPATH . '/personal-daily/class.daily.personal.php');
require_once(CLASSPATH . '/personal-daily/daily.personal.api.php');
require_once(CLASSPATH . '/personal-daily/daily.personal.pipe.php');
require_once (ROOTPATH . '/bin/daily-personal/generate-birth-data.php');

//require_once (CLASSPATH . '/wow/birthanalysis.php');                  // deprecate
//require_once (CLASSPATH . '/wow/dynamicanalysis.php'); 		// deprecate
require_once (CLASSPATH . '/wow/report.php'); 			// rewrite for XML
require_once (CLASSPATH . '/wow/report.pdf.generic.php');
require_once (CLASSPATH . '/wow/generator/default/report.pdf.rc1.php');

/* WOW Wheel */
/* Developer Note - these are temporary until the issues are iron out */
require_once (ROOTPATH . '/bin/wheel/class.report.pdf.wheel.rc1.php');
require_once (ROOTPATH . '/bin/wheel/class.report.pdf.wheel.wow.php');

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

$ReportsDefaultLanguage = 'en';
$planets = array ('Sun', 'Moon', 'Mercury', 'Venus', 'Mars', 'Jupiter', 'Saturn', 'Uranus', 'Neptune', 'Pluto', 'NNode', 'SNode', 'Ascendant', 'MC', 'IC', 'Descendant' );

if(!isset ($_REQUEST['apikey']) && empty ($_REQUEST['apikey']) ) {
    $dPersonal = new DailyPersonal(1001, $_['UNAUTHORIEDACCESS']);
    echo $_['UNAUTHORIEDACCESS'];
    exit(0);
}
else {
    if(isset ($_REQUEST['nwtask']) && !empty ($_REQUEST['nwtask'])) {
        if(strtolower( $_REQUEST['nwtask'] ) == 'natalwheel') {
            if( isset ($_REQUEST['userid']) && !empty ($_REQUEST['userid']) ) {
                $User_id = $_REQUEST['userid'];
                $NatalWheelJPGFileName = sprintf ( "%s/%d.natalwheel.jpg", SPOOLPATH, $User_id );
                $NatalWheelJPGFileName1 = sprintf ( "http://%s/var/spool/%d.natalwheel.jpg", $_SERVER['SERVER_NAME']."/astrowow", $User_id );
                $NatalWheelJPGThumb = sprintf ( "http://%s/var/spool/%d.natalwheelthumb.jpg", $_SERVER['SERVER_NAME']."/astrowow", $User_id );

                $NatalWheelFileName = sprintf ( "%s/%d.natalwheel.pdf", SPOOLPATH, $User_id );
                $NatalWheelFileName1 = sprintf ( "http://%s/var/spool/%d.natalwheel.pdf", $_SERVER['SERVER_NAME']."/astrowow", $User_id );
                //$NatalWheelFileName1 = sprintf ( "http://%s/var/spool/%d.natalwheel.pdf", $_SERVER['SERVER_NAME'], $User_id );

				if(file_exists($NatalWheelFileName)) {
                	unlink($NatalWheelFileName);
                }
                //if(!file_exists($NatalWheelFileName)) {
                $user = new User();

                $userDetail =  $user->GetUserDetail($User_id);
                $userPersonalDetail =  $user->GetUserProfileDetailByUserId($User_id);
                $result =  $user->GetUserBirthDetailByUserId($User_id);


                if($userDetail) {
                    if(strlen($userDetail[0]['DefaultLanguage']) > 2) {
                        $ReportsDefaultLanguage = $languageCodes[strtolower( $userDetail[0]['DefaultLanguage'] ) ];
                    }
                    else {
                        $ReportsDefaultLanguage = strtolower( $userDetail[0]['DefaultLanguage'] );
                    }

                    if(count($result) > 0 && count($userDetail) > 0) {
                        $userDetailDTO->language = $userDetail[0]['DefaultLanguage'];
                        $userDetailDTO->user_id = $User_id;
                        $userDetailDTO->portal_id =  $userDetail[0]['PortalId'];

                        $userbirthDTO->UserBirthDetailId  = $result[0]["UserBirthDetailId"];
                        $userbirthDTO->UserId = $result[0]["UserId"];

                        //BIRTH DATE
                        $userbirthDTO->Day = $result[0]["Day"];
                        $userbirthDTO->Month = $result[0]["Month"];
                        $userbirthDTO->Year = $result[0]["Year"];
                        $userbirthDTO->Hours = $result[0]["Hours"];
                        $userbirthDTO->Minutes = $result[0]["Minutes"];
                        $userbirthDTO->Seconds = $result[0]["Seconds"];
                        $userbirthDTO->GMT = $result[0]["GMT"];
                        $userbirthDTO->Lagitute = $result[0]["Lagitute"];
                        $userbirthDTO->Longitute = $result[0]["Longitute"];
                        $userbirthDTO->unTimed = $result[0]["unTimed"];

                        $userbirthDTO->ZoneRef = $result[0]["ZoneRef"];
                        $userbirthDTO->SummerTimeZoneRef = $result[0]["SummerTimeZoneRef"];

                        $userbirthDTO->country = $result[0]["country"];
                        $userbirthDTO->state = $result[0]["state"];
                        $userbirthDTO->city = $result[0]["city"];
                        $userbirthDTO->country_name = $result[0]["country_name"];

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
                        $userbirthDTO = GetLocationInformation($userbirthDTO);

                        $dailyBirthData = new DailyBirthData( $formattedDate,                             /* Birth Date	 */
                                $formattedTime,                         /* Birth Time	 */
                                $timed_data,                            /* timed data  */
                                $userbirthDTO->SummerTimeZoneRef,       /* summertime	 */
                                $userbirthDTO->ZoneRef,                 /* timezone	 */
                                $userbirthDTO->Longitute,               /* longitude	 */
                                $userbirthDTO->Lagitute );              /* latitude	 */

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

                        //$coords = sprintf ( "%2d%s%02d %3d%s%02d", abs ( intval ( $userbirthDTO->Lagitute ) ), (($userbirthDTO->Lagitute >= 0) ? 'N' : 'S'), abs ( intval ( (($userbirthDTO->Lagitute - intval ( $userbirthDTO->Lagitute )) * 60) ) ), abs ( intval ( $userbirthDTO->Longitute ) ), (($userbirthDTO->Longitute >= 0) ? 'W' : 'E'), abs ( intval ( (($userbirthDTO->Longitute - intval ( $userbirthDTO->Longitute )) * 60) ) ) );
                        $coords = sprintf ( "%2d%s%02d %3d%s%02d", abs ( intval (  $dailyBirthData->latitude  ) ), (( $dailyBirthData->latitude >= 0) ? 'N' : 'S'), abs ( intval ( (($dailyBirthData->latitude - intval ( $dailyBirthData->latitude )) * 60) ) ),
                                abs ( intval ( $dailyBirthData->longitude ) ), (($dailyBirthData->longitude >= 0) ? 'W' : 'E'), abs ( intval ( (($dailyBirthData->longitude - intval ( $dailyBirthData->longitude )) * 60) ) ) );

                        /* daylight savings offset */
                        $timediff = number_format(floatval(abs($userbirthDTO->SummerTimeZoneRef )), 2);
                        $timediff = str_replace('.', ':', $timediff);
                        /* timezone offset */
                        $timedelta =  number_format(abs($userbirthDTO->ZoneRef), 2);
                        $timedelta = str_replace('.', ':', $timedelta);

                        $wheel->wheel_offset = $wheel->house_cusp_longitude [0];

                        if($userPersonalDetail) {
                            $wheel->table_user_info_fname = sprintf("%s %s", trim ( $userPersonalDetail[0]['FirstName'] ), trim ( $userPersonalDetail[0]['LastName'] ));
                            $wheel->table_user_info_lname = '';
                        }
                        else {
                            $wheel->table_user_info_fname = trim ( "Not Provided" );
                            $wheel->table_user_info_lname = '';
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
						
                        $wheel->generateChartWheelPage ();                

                        if(file_exists($NatalWheelFileName)) {
                        	unlink($NatalWheelFileName);
                        }
                        if(file_exists($NatalWheelJPGFileName)) {
                        	unlink($NatalWheelJPGFileName);
                        }
                        
                        $wheel->Output ( $NatalWheelFileName , "F");
                        $wheel->Close();

                        $generateImage = "/usr/bin/convert -alpha off -density 125 \"{$NatalWheelFileName}[0]\" -colorspace RGB -strip -quality 200 -geometry 150% $NatalWheelJPGFileName"; 
                        exec($generateImage);
                        //exec("/usr/bin/convert -alpha off -density 125 \"{$NatalWheelFileName}[0]\" -colorspace RGB -strip -quality 200 -geometry 150% $NatalWheelJPGFileName");
						//echo "/usr/bin/convert -density 175 \"{$NatalWheelFileName}[0]\" -colorspace RGB -strip -quality 100 -geometry 150% $NatalWheelJPGFileName";
                        $im;
                        if( isset ($_REQUEST['rs']) &&  !empty ($_REQUEST['rs']) ) {
                            //http://24x7servermanagement.com/blog/?p=754
                            //INSTALL IMAGICK module.
// 							flush();
//                            	@ob_end_clean();
//                            	$im = new imagick($NatalWheelFileName);
//                            	$im->setImageFormat( "jpg" );
//                            	$im->cropImage(407, 415, 150, 125);
//                            	$im->setImagePage(0, 0, 0, 0); // Remove canvas
//                            	$im->resizeImage(240, 240, imagick::FILTER_LANCZOS, 0.9, true);
//                            	header( "Content-Type: image/jpeg" );
//                            	echo $im;
//                            	exit(0);

                            //Scale the image to the thumb_width set above
                            /*$width  = 615;
                            $height = 615;
                            $x1 =   225;
                            $y1 =   190;*/
							$width  = 1095;
                            $height = 1095;
                            $x1 =   365;
                            $y1 =   310;
							
                            $cropped = resizeThumbnailImage($NatalWheelJPGFileName, $NatalWheelJPGFileName,$width, $height, $x1, $y1);
                            $im = imagecreatefromjpeg($cropped);
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
                        }
                        else
                        {
                            $im = imagecreatefromjpeg($NatalWheelJPGFileName);
                            //show the image
                            ob_end_clean();
                            // Set the content type header - in this case image/jpeg
                            header('Content-Type: image/jpeg');
                            // Output the image
                            imagejpeg($im);
                            // Free up memory
                            imagedestroy($im);
                            exit;
                        }
                    }
                }
                //}
//                if(file_exists($NatalWheelFileName)) {
//                    //echo '<embed src="'.$NatalWheelFileName1.'" width="100%">';
//                    echo '<embed width="100%" height="100%" name="plugin" src="'.$NatalWheelFileName1.'" type="application/pdf">';
//                }

//                ob_end_clean();
//				  flush();
//                header('Content-type: application/pdf');
//                header('Content-Disposition: inline; filename="'.$NatalWheelFileName.'"');
//                header('Content-Length: ' . filesize($NatalWheelFileName));
//                @readfile($NatalWheelFileName);

//                $mm_type="application/octet-stream";                    // modify accordingly to the file type of $path, but in most cases no need to do so
//                header("Pragma: public");
//                header("Expires: 0");
//                header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
//                header("Cache-Control: public");
//                header("Content-Description: File Transfer");
//                header("Content-Type: " . $mm_type);
//                header("Content-Length: " .(string)(filesize($NatalWheelFileName)) );
//                //header('Content-Disposition: attachment; filename="'.basename($NatalWheelFileName).'"');
//                header('Content-Disposition: attachment; filename="Your-Natal-Chart.pdf"');
//                header("Content-Transfer-Encoding: binary\n");
//                readfile($NatalWheelFileName); // outputs the content of the file
//                exit();
            }
        }
        else if(strtolower( $_REQUEST['nwtask'] ) == 'qnatalwheel') {
            //echo "<pre>";
            if( isset ($_REQUEST['questionid']) && !empty ($_REQUEST['questionid']) ) {
                $QuestionId = $_REQUEST['questionid'];
                $NatalWheelFileName = sprintf ( "%s/%d.question.natalwheel.pdf", SPOOLPATH, $QuestionId );

                $user = new GoldenCircle();
                $QuestionDetail = $user->GetQuestionDetail($QuestionId);
                $result =  $user->GetBirthDetailByQuestionId($QuestionId);

                if($QuestionDetail) {
                    //echo "<pre>" .$QuestionDetail['Question'][0]['language']. "</pre>";
                    if(strlen($QuestionDetail['Question'][0]['language']) > 0) {
                        if(array_key_exists($QuestionDetail['Question'][0]['language'], $languageIds)){
                            $ReportsDefaultLanguage = $languageCodes[$languageIds [$QuestionDetail['Question'][0]['language']]];
                        }
                        else {
                            $ReportsDefaultLanguage = 'en';
                        }
                    }
                    else {
                        $ReportsDefaultLanguage = 'en';
                    }

                    if(count($result) > 0 ) {
                        $userDetailDTO->language            = $QuestionDetail['Question'][0]['language'];
                        $userDetailDTO->user_id             = $QuestionId;
                        $userbirthDTO->UserBirthDetailId  = $result[0]["question_birth_detail_id"];
                        $userbirthDTO->UserId             = $QuestionId;

                        //BIRTH DATE
                        $DateOfBirth  =   $result[0]["birth_date"];
                        $DOB = explode('-', $DateOfBirth);

                        $userbirthDTO->Day        = $DOB[2];
                        $userbirthDTO->Month      = $DOB[1];
                        $userbirthDTO->Year       = $DOB[0];
                        $userbirthDTO->Hours      = $result[0]["hours"];
                        $userbirthDTO->Minutes    = $result[0]["min"];
                        $userbirthDTO->Seconds    = '0';
                        $userbirthDTO->GMT        = '';
                        $userbirthDTO->Lagitute   = $result[0]["latitude"];
                        $userbirthDTO->Longitute  = $result[0]["longitude"];
                        $userbirthDTO->unTimed    = $result[0]["untimed"];

                        $userbirthDTO->ZoneRef    = $result[0]["zoneRef"];
                        $userbirthDTO->SummerTimeZoneRef = $result[0]["summerTimeZoneRef"];

                        $userbirthDTO->country      = $result[0]["country"];
                        $userbirthDTO->state        = $result[0]["state"];
                        $userbirthDTO->city         = $result[0]["city"];
                        $userbirthDTO->country_name = $result[0]["country_name"];

                        $formattedDate = sprintf ( "%04d%02d%02d", $userbirthDTO->Year, $userbirthDTO->Month, $userbirthDTO->Day );
                        $formattedTime = sprintf ( "%02d:%02d", $userbirthDTO->Hours, $userbirthDTO->Minutes );
                        //$timed_data = ( ( trim ( $userbirthDTO->unTimed ) == 'N') ? true : false);
                        $timed_data = ( ( trim ( $userbirthDTO->unTimed ) == '1' )  ? false : true );

                        if($userbirthDTO->Lagitute >= -90 && $userbirthDTO->Lagitute <= 90 ) {
                            $userbirthDTO->Lagitute = $userbirthDTO->Lagitute * 3600;
                        }

                        if($userbirthDTO->Longitute >= -180 && $userbirthDTO->Longitute <= 180) {
                            $userbirthDTO->Longitute = $userbirthDTO->Longitute * 3600;
                        }
                        
                        $userbirthDTO = GetLocationInformation($userbirthDTO);

                        $dailyBirthData = new DailyBirthData(   $formattedDate,                             /* Birth Date	 */
                                $formattedTime,                         /* Birth Time	 */
                                $timed_data,                            /* timed data  */
                                $userbirthDTO->SummerTimeZoneRef,       /* summertime	 */
                                $userbirthDTO->ZoneRef,                 /* timezone	 */
                                $userbirthDTO->Longitute,               /* longitude	 */
                                $userbirthDTO->Lagitute );              /* latitude	 */

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

                        //$coords = sprintf ( "%2d%s%02d %3d%s%02d", abs ( intval ( $userbirthDTO->Lagitute ) ), (($userbirthDTO->Lagitute >= 0) ? 'N' : 'S'), abs ( intval ( (($userbirthDTO->Lagitute - intval ( $userbirthDTO->Lagitute )) * 60) ) ), abs ( intval ( $userbirthDTO->Longitute ) ), (($userbirthDTO->Longitute >= 0) ? 'W' : 'E'), abs ( intval ( (($userbirthDTO->Longitute - intval ( $userbirthDTO->Longitute )) * 60) ) ) );
                        $coords = sprintf ( "%2d%s%02d %3d%s%02d", abs ( intval (  $dailyBirthData->latitude  ) ), (( $dailyBirthData->latitude >= 0) ? 'N' : 'S'), abs ( intval ( (($dailyBirthData->latitude - intval ( $dailyBirthData->latitude )) * 60) ) ),
                                abs ( intval ( $dailyBirthData->longitude ) ), (($dailyBirthData->longitude >= 0) ? 'W' : 'E'), abs ( intval ( (($dailyBirthData->longitude - intval ( $dailyBirthData->longitude )) * 60) ) ) );

                        /* daylight savings offset */
                        $timediff = number_format(floatval(abs($userbirthDTO->SummerTimeZoneRef )), 2);
                        $timediff = str_replace('.', ':', $timediff);
                        /* timezone offset */
                        $timedelta =  number_format(abs($userbirthDTO->ZoneRef), 2);
                        $timedelta = str_replace('.', ':', $timedelta);

                        $wheel->wheel_offset = $wheel->house_cusp_longitude [0];


                        $wheel->table_user_info_fname = trim ( $result[0]['name'] );
                        $wheel->table_user_info_lname = '';
                        //echo "<pre>$ReportsDefaultLanguage</pre>";
                        $wheel->table_user_info_birth_weekday = $wheel_top_weekdays [strtolower ( $ReportsDefaultLanguage )] [JDDayOfWeek ( cal_to_jd ( CAL_GREGORIAN, $userbirthDTO->Month, $userbirthDTO->Day, $userbirthDTO->Year ), 1 )];
                        $wheel->table_user_info_birth_date = $birthdate;
                        $wheel->table_user_info_birth_place = sprintf("%s %s", trim ( $userbirthDTO->city ), trim ( $userbirthDTO->state ));
                        $wheel->table_user_info_birth_state = trim ( $userbirthDTO->country_name );
                        $wheel->table_user_info_birth_coords = $coords;
                        $wheel->table_user_info_birth_timezone = $timedelta;
                        $wheel->table_user_info_birth_summertime = $timediff;
                        $wheel->table_user_info_house_system = (($timed_data === true) ? 'Placidus' : 'Solar');
                        $wheel->table_user_info_orb_system = $wheel_top_orbsensitivity [strtolower ( $ReportsDefaultLanguage )];

                        $wheel->generateChartWheelPage ();

                        $NatalWheelJPGFileName = sprintf ( "%s/%d.question.natalwheel.jpg", SPOOLPATH,  $QuestionId  );
                        $NatalWheelJPGFileName1 = sprintf ( "http://%s/var/spool/%d.question.natalwheel.jpg", $_SERVER['SERVER_NAME'],  $QuestionId  );
                        $NatalWheelFileName = sprintf ( "%s/%d.question.natalwheel.pdf", SPOOLPATH,  $QuestionId  );
                        $NatalWheelFileName1 = sprintf ( "http://%s/var/spool/%d.question.natalwheel.pdf", $_SERVER['SERVER_NAME'],  $QuestionId );

                        $wheel->Output ( $NatalWheelFileName );

                        exec("/usr/bin/convert -alpha off -density 125 \"{$NatalWheelFileName}[0]\" -colorspace RGB -strip -quality 100 -geometry 150% $NatalWheelJPGFileName");
                        $im = imagecreatefromjpeg($NatalWheelJPGFileName1);

                        //show the image
			ob_end_clean();
//                      flush();
                        // Set the content type header - in this case image/jpeg
                        header('Content-Type: image/jpeg');
                        // Output the image
                        imagejpeg($im);
                        // Free up memory
                        imagedestroy($im);
                        exit;
                    }
                } 
            }            
        }
        else if(strtolower( $_REQUEST['nwtask'] ) == 'qhorarywheel') {
            if( isset ($_REQUEST['questionid']) && !empty ($_REQUEST['questionid']) ) {
                $OriginalTimeZone = date_default_timezone_get();
                $GobalTimeZone = date_default_timezone_get();
                $GobalTimeZoneOffset;
                $GobalTimeZoneOffset1;

                $QuestionId = $_REQUEST['questionid'];
                $NatalWheelFileName = sprintf ( "%s/%d.question.horarywheel.pdf", SPOOLPATH, $QuestionId );

                $user = new GoldenCircle();
                $QuestionDetail = $user->GetQuestionDetail($QuestionId);
                $result =  $user->GetHororaryDetailByQuestionId($QuestionId);

                if($QuestionDetail) {
                    if(strlen($QuestionDetail['Question'][0]['language']) > 0) {
                        if(array_key_exists($QuestionDetail['Question'][0]['language'], $languageIds)){
                            $ReportsDefaultLanguage = $languageCodes[$languageIds [$QuestionDetail['Question'][0]['language']]];
                        }
                        else {
                            $ReportsDefaultLanguage = 'en';
                        }
                    }
                    else {
                        $ReportsDefaultLanguage = 'en';
                    }

                    if(count($result) > 0 ) {
                        $userDetailDTO->language            = $QuestionDetail['Question'][0]['language'];
                        $userDetailDTO->user_id             = $QuestionId;
                        $userbirthDTO->UserBirthDetailId    = $result[0]["question_horory_detail_id"];
                        $userbirthDTO->UserId               = $QuestionId;

                        //Horary TIME
                        $CurrentTime = getdate();

                        //BIRTH DATE
                        $userbirthDTO->Day        = $CurrentTime['mday'];
                        $userbirthDTO->Month      = $CurrentTime['mon'];
                        $userbirthDTO->Year       = $CurrentTime['year'];
                        $userbirthDTO->Hours      = $CurrentTime['hours'];
                        $userbirthDTO->Minutes    = $CurrentTime['minutes'];
                        $userbirthDTO->Seconds    = $CurrentTime['seconds'];
                        $userbirthDTO->GMT        = '';
                        $userbirthDTO->Lagitute   = $result[0]["latitude"];
                        $userbirthDTO->Longitute  = $result[0]["longitude"];
                        //$userbirthDTO->unTimed    = $result[0]["untimed"];
                        $userbirthDTO->unTimed    = '';

                        $userbirthDTO->ZoneRef    = $result[0]["zoneRef"];
                        $userbirthDTO->SummerTimeZoneRef = $result[0]["summerTimeZoneRef"];

                        $userbirthDTO->country      = $result[0]["country"];
                        $userbirthDTO->state        = $result[0]["state"];
                        $userbirthDTO->city         = $result[0]["city"];
                        $userbirthDTO->country_name = $result[0]["country_name"];

                        if($userbirthDTO->Lagitute >= -90 && $userbirthDTO->Lagitute <= 90 ) {
                            $userbirthDTO->Lagitute = $userbirthDTO->Lagitute * 3600;
                        }

                        if($userbirthDTO->Longitute >= -180 && $userbirthDTO->Longitute <= 180) {
                            $userbirthDTO->Longitute = $userbirthDTO->Longitute * 3600;
                        }

                        //$userbirthDTO = GetLocationInformation($userbirthDTO);                        
                        $userbirthDTO = GetLocationInformationForHorary($userbirthDTO);

                        date_default_timezone_set($GobalTimeZone);
                        //date_default_timezone_set($OriginalTimeZone);
                        //date_default_timezone_set(getLocalTimezone($GobalTimeZoneOffset));
                        //date_default_timezone_set('UTC');

                        $formattedDate = sprintf ( "%04d%02d%02d", date('Y'), date('m'), date('d') );
                        $formattedTime = sprintf ( "%02d:%02d", date('H'), date('i') );
                        $timed_data = ( ( trim ( $userbirthDTO->unTimed ) == '1' )  ? false : true );
                        //echo "<pre>" . date('l jS \of F Y h:i:s A'). "</pre>";
                        

                        $dailyBirthData = new DailyBirthData(   $formattedDate,                             /* Birth Date	 */
                                $formattedTime,                         /* Birth Time	 */
                                $timed_data,                            /* timed data  */
                                $userbirthDTO->SummerTimeZoneRef,       /* summertime	 */
                                $userbirthDTO->ZoneRef,                 /* timezone	 */
                                $userbirthDTO->Longitute,               /* longitude	 */
                                $userbirthDTO->Lagitute );              /* latitude	 */

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
                            //$birthdate = sprintf ( "%02d/%02d/%04d %02d:%02d", $userbirthDTO->Day, $userbirthDTO->Month, $userbirthDTO->Year, $userbirthDTO->Hours, $userbirthDTO->Minutes);                                                        
                            $birthdate = date('Y/m/d H:i T');
                        } else {
                            $birthdate = sprintf ( "%02d/%02d/%04d", $userbirthDTO->Day, $userbirthDTO->Month, $userbirthDTO->Year );
                        }

                        //$coords = sprintf ( "%2d%s%02d %3d%s%02d", abs ( intval ( $userbirthDTO->Lagitute ) ), (($userbirthDTO->Lagitute >= 0) ? 'N' : 'S'), abs ( intval ( (($userbirthDTO->Lagitute - intval ( $userbirthDTO->Lagitute )) * 60) ) ), abs ( intval ( $userbirthDTO->Longitute ) ), (($userbirthDTO->Longitute >= 0) ? 'W' : 'E'), abs ( intval ( (($userbirthDTO->Longitute - intval ( $userbirthDTO->Longitute )) * 60) ) ) );
                        $coords = sprintf ( "%2d%s%02d %3d%s%02d", abs ( intval (  $dailyBirthData->latitude  ) ), (( $dailyBirthData->latitude >= 0) ? 'N' : 'S'), abs ( intval ( (($dailyBirthData->latitude - intval ( $dailyBirthData->latitude )) * 60) ) ),
                                abs ( intval ( $dailyBirthData->longitude ) ), (($dailyBirthData->longitude >= 0) ? 'W' : 'E'), abs ( intval ( (($dailyBirthData->longitude - intval ( $dailyBirthData->longitude )) * 60) ) ) );

                        /* daylight savings offset */
                        $timediff = number_format(floatval(abs($userbirthDTO->SummerTimeZoneRef )), 2);
                        $timediff = str_replace('.', ':', $timediff);
                        /* timezone offset */
                        $timedelta =  number_format(abs($userbirthDTO->ZoneRef), 2);
                        $timedelta = str_replace('.', ':', $timedelta);

                        $wheel->wheel_offset = $wheel->house_cusp_longitude [0];


                        $wheel->table_user_info_fname = utf8_decode( trim ( $QuestionDetail['BirthDetail'][0]['name'] ) );
                        $wheel->table_user_info_lname = '';
                        $PlaceName = explode(">", trim ( $userbirthDTO->city ));
                        $CityName = utf8_decode( trim ( $userbirthDTO->city ) );
                        if(count($PlaceName) > 0){
                            $CityName = utf8_decode( trim ( $PlaceName[0] ) );
                        }

                        $wheel->table_user_info_birth_weekday = $wheel_top_weekdays [strtolower ( $ReportsDefaultLanguage )] [JDDayOfWeek ( cal_to_jd ( CAL_GREGORIAN, $userbirthDTO->Month, $userbirthDTO->Day, $userbirthDTO->Year ), 1 )];
                        //$wheel->table_user_info_birth_date = $birthdate . ' ' . $GobalTimeZone;;
                        $wheel->table_user_info_birth_date = $birthdate;
                        //$wheel->table_user_info_birth_place = sprintf("%s %s", trim ( $userbirthDTO->city ), trim ( $userbirthDTO->state ));
                        $wheel->table_user_info_birth_place = sprintf("%s %s", trim ( $CityName ), utf8_decode( trim ( $userbirthDTO->state ) ));
                        $wheel->table_user_info_birth_state = utf8_decode( trim ( $userbirthDTO->country_name ) );
                        $wheel->table_user_info_birth_coords = $coords;
                        $wheel->table_user_info_birth_timezone = $timedelta;
                        $wheel->table_user_info_birth_summertime = $timediff;
                        $wheel->table_user_info_house_system = (($timed_data === true) ? 'Placidus' : 'Solar');
                        $wheel->table_user_info_orb_system = $wheel_top_orbsensitivity [strtolower ( $ReportsDefaultLanguage )];

                        $wheel->generateChartWheelPage ();

                        $NatalWheelJPGFileName = sprintf ( "%s/%d.horary.natalwheel.jpg", SPOOLPATH,  $QuestionId  );
                        $NatalWheelJPGFileName1 = sprintf ( "http://%s/var/spool/%d.horary.natalwheel.jpg", $_SERVER['SERVER_NAME'],  $QuestionId  );
                        $NatalWheelFileName = sprintf ( "%s/%d.horary.natalwheel.pdf", SPOOLPATH,  $QuestionId  );
                        $NatalWheelFileName1 = sprintf ( "http://%s/var/spool/%d.horary.natalwheel.pdf", $_SERVER['SERVER_NAME'],  $QuestionId );

                        $wheel->Output ( $NatalWheelFileName );

                        exec("/usr/bin/convert -alpha off -density 125 \"{$NatalWheelFileName}[0]\" -colorspace RGB -strip -quality 100 -geometry 150% $NatalWheelJPGFileName");
                        $im = imagecreatefromjpeg($NatalWheelJPGFileName1);

                        //show the image
                        ob_end_clean();
//                      flush();
                        // Set the content type header - in this case image/jpeg
                        header('Content-Type: image/jpeg');
                        // Output the image
                        imagejpeg($im);
                        // Free up memory
                        imagedestroy($im);
                        exit;
                    }
                }

//                $mm_type="application/octet-stream";                    // modify accordingly to the file type of $path, but in most cases no need to do so
//
//                header("Pragma: public");
//                header("Expires: 0");
//                header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
//                header("Cache-Control: public");
//                header("Content-Description: File Transfer");
//                header("Content-Type: " . $mm_type);
//                header("Content-Length: " .(string)(filesize($NatalWheelFileName)) );
//                //header('Content-Disposition: attachment; filename="'.basename($NatalWheelFileName).'"');
//                header('Content-Disposition: attachment; filename="Your-Natal-Chart.pdf"');
//                header("Content-Transfer-Encoding: binary\n");
//
//                readfile($NatalWheelFileName); // outputs the content of the file
//
//                exit();
            }
        }
        else {
            echo $_['INVALIDPARAM'];
        }
    }
}


function GetLocationInformation($data) {
    $Longitude = $data->Longitute;
    $Latitude = $data->Lagitute;

    $sql =  "SELECT * FROM `acsatlas`".
            " WHERE upper(placename)='". strtoupper($data->city) ."' ".
            " AND (upper(SUBSTR(lkey, 0, 2))= '".  strtoupper( $data->country) ."') ".
//            " where  longitude ='".$Longitude."'".
//            " AND  longitude ='".$Longitude."'".
//            " AND latitude ='".$Latitude."'".
            " ORDER BY lkey";
    $ACSRep = new  ACSRepository();
    $actStateList = new  ACSStateList();

    $Result = $ACSRep->GetACSDataRow($sql);

    if( count( $Result ) > 0 ) {
        $data->country_name = $actStateList->getStateNameByAbbrev(substr( $Result[0]['lkey'] , 0, 2));
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

function GetSummerTimeZoneANDTimeZone($location, $data) {
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
}

//You do not need to alter these functions
function resizeThumbnailImage($thumb_image_name, $image, $width, $height, $start_width, $start_height) {
	$newImageWidth = 235;
	$newImageHeight = 235;
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
?>
