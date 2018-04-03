<?php
/**
 * This is the Initiater page for generating daily personal based on passed user Id
 */
ini_set("display_errors", 1);
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);

date_default_timezone_set ( 'America/Los_Angeles' );

define( 'ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/' );
//define( 'ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . 'astrowow/' );

define ( 'CLASSPATH', ROOTPATH . '/classes' );
define ( 'LIBPATH', ROOTPATH . '/lib' );

require_once(ROOTPATH.'config.php');
require_once(ROOTPATH.'language/en/personal-daily/personal_daily.php');
require_once(ROOTPATH.'language/dk/personal-daily/personal_daily.php');

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
require_once (CLASSPATH . '/objects/class.order.php');              //POG CLASS ADDED
require_once (CLASSPATH . '/objects/class.transaction.php');
require_once (CLASSPATH . '/objects/class.portal.php');
require_once (CLASSPATH . '/objects/class.product.php');
require_once (CLASSPATH . '/objects/class.product.description.php');
require_once (CLASSPATH . '/objects/class.birthdata.php');          //POG CLASS ADDED
require_once (CLASSPATH . '/objects/class.reportoption.php');
require_once (CLASSPATH . '/objects/class.emailaddress.php');

/* ACS Atlas */
require_once (CLASSPATH . '/objects/class.acs.atlas.php');
require_once (CLASSPATH . '/acs/class.acs.statelist.php');

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
            $timed_data = ((trim ( $userbirthDTO->unTimed ) == 'N') ? true : false);

            $dailyBirthData = new DailyBirthData(      $formattedDate,                             /* Birth Date	 */
                    $formattedTime,                         /* Birth Time	 */
                    $timed_data,                            /* timed data  */
                    $userbirthDTO->SummerTimeZoneRef,       /* summertime	 */
                    $userbirthDTO->ZoneRef,                 /* timezone	 */
                    $userbirthDTO->Longitute,               /* longitude	 */
                    $userbirthDTO->Lagitute );              /* latitude	 */

            switch (strtoupper ( $userDetailDTO->language )) {
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
                $month = $checkDate[0];
                $day = $checkDate[1];
                $year = $checkDate[2];

                $chart = new DailyPersonalChartAPI(  $dailyBirthData, false );
                $chart->getDailyAspects($dailyBirthData, $month, $day, $year, 2);

                $dPersonal = new DailyPersonal(0, $_['VALIDPARAM']);

                if(count($chart->m_aspect) == 0) {
                    $chart->getDailyMoonAspects($dailyBirthData, $month, $day, $year, 2);
                }

                foreach ($chart->m_aspect as $key => $item) {
//                [0] => 1008[0111]1201005[0107]
//                [1] => 1008[0111]0901007[0108]
//                [2] => 1007[0100]1801013[0106]
//                [3] => 1000[0108]0601013[0106]
//                       123456789012345678901234567

                    $TransitPlanet =  trim ( substr ( $item, 0, 4 ) );
                    $TransitSignPlanet =  trim ( substr ( $item, 5, 4 ) );
                    $Aspect =  trim ( substr ( $item, 10, 3 ) );
                    $NatalPlanet =  trim ( substr ( $item, 13, 4 ) );
                    $NatalSignPlanet =  trim ( substr ( $item, 18, 4 ) );

//                      echo "<pre>$TransitPlanet-$TransitSignPlanet-$Aspect-$NatalPlanet-$NatalSignPlanet</pre>";
//                      echo "<pre>$item</pre>";

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
                        case 0:                 $AspectIndex = 0;
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
                    $dpData->PredictionDate = $pDate;
                    $dpData->Title =  $Title;
                    $dpData->Description = $Desciption;
                    $dpData->Quetion1 = $Que1;
                    $dpData->Answer1 = $Ans1;
                    $dpData->Quetion2 = $Que2;
                    $dpData->Answer2 = $Ans2;
                    $dpData->Quetion3 = $Que3;
                    $dpData->Answer3 = $Ans3;

                    $dPersonal->AddPredictionData($dpData);
                }
                echo "<pre>";
                print_r( $dPersonal );
                //echo json_encode($dPersonal);
                echo "</pre>";
            }
            else {
                $dPersonal = new DailyPersonal(1002, $_['INVALIDPARAM']);
                echo json_encode($dPersonal);
            }
        }
        else {
            $dPersonal = new DailyPersonal(1003, $_['USERNOTFOUND']);
            echo json_encode($dPersonal);
        }
    }
    else {
        $dPersonal = new DailyPersonal(1002, $_['INVALIDPARAM']);

        echo json_encode($dPersonal);
    }
}
?>