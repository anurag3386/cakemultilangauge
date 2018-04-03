<?php use Cake\Routing\Router; ?>
<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
/* POG data */
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
ini_set("display_errors", 1);

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
global $AnnotationText;
global $GenericText;
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

require_once(INCLUDEPATH . '/lang/year-report/common_variables.php');
require_once(TRASITSVENDORPATH . '/config.php');

require_once (CLASSPATH . '/configuration.php');
require_once (CLASSPATH . '/objects/class.database.php');
require_once (CLASSPATH . '/objects/class.pog_base.php');

require_once (CLASSPATH . '/objects/class.user1.php');
require_once (CLASSPATH . '/objects/class.user.year.report.transit.php');
require_once (CLASSPATH . '/objects/class.userbirthdetail.php');
require_once (CLASSPATH . '/objects/class.year_book_en.php');			// YEAR REPORT TEST Interpretation text
require_once (CLASSPATH . '/objects/class.year_book_dk.php');			// YEAR REPORT TEST Interpretation text    

require_once(LIBRARYPATH."/language.php");
$language = new Language (TRASITSVENDORPATH);

$config = new Config ();
//By Krishna Gupta
//$YearReport =  $language->load("year.report.transit", $config->get('config_language'));

$users = new POGUserClass();
$reportLanguage = 'en';

$reportLanguage = (isset($lang) && !empty($lang)) ? $lang : 'en';
if ($reportLanguage == 'da') {
    $reportLanguage = 'dk';
}
$language_id = $reportLanguage;
/*if (isset($_SESSION['locale']) && !empty($_SESSION['locale'])) {
    if ($_SESSION['locale'] == 'da') {
        $reportLanguage = 'dk';
    } else {
        $reportLanguage = 'en';
    }
}*/
$YBookText  = new year_book_en();
if($reportLanguage == 'dk'){
	$YBookText  = new year_book_dk();	
}


$MyTransit = array();
//By Krishna Gupta
$ASPECT_NAME_LIST = $language->get("ASPECT_NAME_LIST");

function GetMonthNameFromDate($SearchingDate) {
    if(isset($SearchingDate) && $SearchingDate != '') {
            global $MonthNameForContent;
            global $reportLanguage;

            $MonthNumber = substr($SearchingDate, 5, 2);
            return $MonthNameForContent[$reportLanguage][$MonthNumber];
    }
    else {
            return '';
    }
}
function GetYearFromDate($SearchingDate) {
        return substr($SearchingDate, 0, 4);
}

function GetDayFromDate($SearchingDate){
        return substr($SearchingDate, 9, 2);
}

function GetFullDateWithMonth($SearchingDate) {
    $FinalDate = GetMonthNameFromDate($SearchingDate);
    $FinalDate = sprintf($FinalDate. ' %02d,', GetDayFromDate($SearchingDate));
    $FinalDate = sprintf($FinalDate. ' %04d', GetYearFromDate($SearchingDate));
    return $FinalDate;
}
$UserId = !empty($_SESSION['Auth']['User']['id']) ? $_SESSION['Auth']['User']['id'] : '';
$UserId = (!empty($_SESSION['user_id'])) ? $_SESSION['user_id'] : $UserId;
$UserId = (!empty($_SESSION['selectedUser'])) ? $_SESSION['selectedUser'] : $UserId;

if(isset($UserId) && !empty($UserId)) {
    $TUserId = $UserId;
    $userType = '';
    if (strpos($TUserId, '_') !== false) {
        $userType = 'anotherPerson';
    }
    $users->Get($TUserId, $userType);
    $Gender = 'M';

    //Alway takes English
    if(isset($language_id)) {
    	$reportLanguage = $language_id;
    }
    if($reportLanguage == 'dk'){
    	$YBookText  = new year_book_dk();
    }

    if($reportLanguage == 'en') {
        require_once(INCLUDEPATH . '/lang/year-report/en.php');
        $YBookText  = new year_book_en();        
    }
    else if($reportLanguage == 'dk') {
        require_once(INCLUDEPATH . '/lang/year-report/dk.php');
        $YBookText  = new year_book_dk();        
    }


$selectedDateByUser = date('Y-m-d', strtotime($date));

    $AlreadyCalulatedTransit = new user_year_report_transit ();
    if (strpos($TUserId, '_') !== false) {
        $anotherPersonId = explode('_', $TUserId);
        $YouAlreadyProcessed = $AlreadyCalulatedTransit->GetList (
                            array (
                                array ('HittingDate', '<=', $selectedDateByUser),
                                array ('user_id', '=', $anotherPersonId[1]),
                                array ('user_type', '=', 'anotherPerson')
                            ),
                            'HittingDate', false, 1);
    } else {
        $YouAlreadyProcessed = $AlreadyCalulatedTransit->GetList (
                            array (
                                array ('HittingDate', '<=', $selectedDateByUser),
                                array ('user_id', '=', $TUserId),
                                array ('user_type', '=', 'user')
                            ),
                            'HittingDate', false, 1);
    }

    if( count( $YouAlreadyProcessed ) > 0 ) {
        foreach ($YouAlreadyProcessed as $Item) {
            $BookList = $YBookText->GetList(array(array('year_book_id', '=', $Item->year_book_id)));
            //echo '<pre>'; print_r($BookList); print_r ($AnnotationText); die;
            $HittingDate = $Item->HittingDate;
            $StartDate = $Item->StartDate;
            $EndDate = $Item->EndDate;
            $YRBookID =  $Item->year_book_id;
            $AspectType = $Item->AspectType;
            $Aspect = $Item->Aspect;

            foreach ($BookList as $BookItem) {
                $AnnotationTitle = sprintf("%s ", trim($AnnotationText[$reportLanguage][$BookItem->planet_code1]));
                
                if(strtolower($AspectType) == 't' || strtolower($AspectType) == 'tr') {
                	$AnnotationTitle .= sprintf("%s ", isset ($AnnotationText[$reportLanguage]["TR"]) ? trim($AnnotationText[$reportLanguage]["TR"]) : '');
                } else {
                	$AnnotationTitle .= sprintf("%s ", isset ($AnnotationText[$reportLanguage]["PR"]) ? trim($AnnotationText[$reportLanguage]["PR"]) : '');
                }

                //$AnnotationTitle .= sprintf("%s ", trim($AnnotationText[$reportLanguage][$BookItem->aspect_id]));
                $AnnotationTitle .= sprintf("%s ", !empty($ASPECT_NAME_LIST[$Aspect]) ? $ASPECT_NAME_LIST[$Aspect] : '');
                $AnnotationTitle .= sprintf("%s ", trim($AnnotationText[$reportLanguage][$BookItem->planet_code12]));
                $AnnotationTitle .= sprintf("%s ", trim($AnnotationText[$reportLanguage]["R"]));
                
                $StartDateMonth = explode('-', $StartDate);
                $dateObj = DateTime::createFromFormat('!m', $StartDateMonth[1]);
                $StartDateMonthName = $dateObj->format('F');
                $StartDate = ($StartDate != '' ? sprintf("%s %s", $StartDateMonthName, $StartDateMonth[0]) : '');
                if(strtolower($AspectType) == 't' || strtolower($AspectType) == 'tr') {
                    $EndDateMonth = explode('-', $EndDate);
                    $dateObj   = DateTime::createFromFormat('!m', $EndDateMonth[1]);
                    $EndDateMonthName = $dateObj->format('F');

                	$EndDate = ($EndDate != '' ? sprintf("%s %s", $EndDateMonthName, $EndDateMonth[0]) : '');
                    $FinalReplaceDate = sprintf('%s %s %s %s', $GenericText[$reportLanguage]['FromConnector'], $StartDate, $GenericText[$reportLanguage]['TOConnector'], $EndDate);
                } else{
                	$FinalReplaceDate = sprintf('%s %s ', $GenericText[$reportLanguage]['FromConnector'], $StartDate);
                }

                $Desc = $BookItem->description;
                $Desc = str_replace('[transitperiod]', " $FinalReplaceDate ", $Desc);
                $Desc = str_replace('[pr_aspect_radix]', " $FinalReplaceDate ", $Desc);
                
                $Desc = str_replace('[ingress_year]', " $StartDate ", $Desc);
                $Desc = str_replace('[ingress_date]', " $StartDate ", $Desc);
                $Desc = str_replace('[ingress_month]', " $StartDate ", $Desc);                
                
                if($EndDate != '' && isset($EndDate)) {  
                	$Desc  = str_replace('[pr_aspect_pr]', " $EndDate ", $Desc);
                }
                else {
                	$Desc  = str_replace('[pr_aspect_pr]', " ", $Desc);
                }
                
                if($Gender == "M"){
                	$Desc  = preg_replace('/\[female](.*?)\[end]/','', $Desc );
                }
                else {
                	$Desc  = preg_replace('/\[male](.*?)\[end]/','', $Desc );
                }

                $MyTransit['Title'] = utf8_encode($AnnotationTitle);
                $MyTransit['StartDate'] = $StartDate;
                $MyTransit['EndDate'] = $EndDate;
                $MyTransit['HittingDate'] = isset($HittingDate) ?  date("d-m-Y", strtotime($HittingDate)) : "";

                $arrL = explode(' ', $Desc);
                $MyTransit['Desc'] = utf8_encode(implode(' ', array_slice($arrL, 0, 50)));
                //$MyTransit['Desc'] = substr(utf8_encode($Desc), 0, 225);
                $MyTransit['FullDesc'] = utf8_encode($Desc);
                $readMoreOrNot = '';
                if (count($arrL) > 50) {
                    if ($language_id == 'dk') {
                        $readMoreOrNot = 'Læs Mere';
                    } else {
                        $readMoreOrNot = 'Read More';
                    }
                }
                /*if (strlen($Desc) > 225) {
                    if ($language_id == 'dk') {
                        $readMoreOrNot = 'Læs Mere';
                    } else {
                        $readMoreOrNot = 'Read More';
                    }
                }*/
                $_SESSION['transit']['Title'] = $MyTransit['Title'];
                $_SESSION['transit']['transitDescription'] = $MyTransit['FullDesc'];
                if(strtolower($AspectType) == 't' || strtolower($AspectType) == 'tr') {
                	$MyTransit['FinalReplaceDate'] = sprintf("%s %s", isset ($AnnotationText[$reportLanguage]["TR"]) ? trim($AnnotationText[$reportLanguage]["TR"]) : '', $FinalReplaceDate);
                }else {
                	$MyTransit['FinalReplaceDate'] = sprintf("%s %s", isset ($AnnotationText[$reportLanguage]["PR"]) ? trim($AnnotationText[$reportLanguage]["PR"]) : '', $FinalReplaceDate);
                }
            }
        }
    }
}

$desc = strip_tags(trim(stripcslashes(html_entity_decode(utf8_decode(utf8_encode($MyTransit['Desc']))))));
//$desc = htmlspecialchars($desc);

$controller = 'users';
$action = 'fulltransit';
if ($language_id == 'dk') {
    $imp_tran = 'Vigtige Overgange';
    $MonthNameArray = array('January' => 'januar', 'February' => 'februar', 'March' => 'marts', 'April' => 'April',
            'May' => 'Kan', 'June' => 'juni', 'July' => 'juli', 'August' => 'august', 'September' => 'september', 'October' => 'oktober', 'November' => 'november', 'December' => 'december' );
    $language = 'dk';
    $controller = 'brugere';
    $action = 'fulltransit';
    //$url = Router::url([$language, 'controller' => $controller, 'action' => $action]);
    $url = Router::url('/', true).'dk/brugere/transitbeskrivelse';
} else {
    $imp_tran = 'Important Transitions';
    $MonthNameArray = array('January' => 'January', 'February' => 'February', 'March' => 'March', 'April' => 'April',
            'May' => 'May', 'June' => 'June', 'July' => 'July', 'August' => 'August', 'September' => 'September', 'October' => 'October', 'November' => 'November', 'December' => 'December');
    $url = Router::url(['controller' => $controller, 'action' => $action]);
}
$monthName = date('F', strtotime($date));
if( array_key_exists($monthName, $MonthNameArray) ){
    $monthName1 = $MonthNameArray[$monthName];
}

$data = '';
if (!empty($MyTransit)) {
$data .= "<div class='important-transitions'>
    <h3>".htmlspecialchars($imp_tran)." - ".ucfirst($monthName1)." ".date('d, Y', strtotime($date))."</h3>
    
    <div class='important-inner'>
        <div class='important-left'>".trim(stripcslashes(html_entity_decode(utf8_decode(utf8_encode($MyTransit['Title'])))))."<em>(".$MyTransit['FinalReplaceDate'].")</em>
    </div>
    <div class='important-right'>
        <p>".$desc."...</p>
            <a href='".$url."' target='_blank'>
            <em>
                <strong>".__($readMoreOrNot)."</strong>
            </em>
        </a>
        </div>
</div><br class='clearfix'><br class='clearAll'>";
}
return $data;

?>

<?php
/*
    <a href='".BASEURL."/users/fulltransit' target='_blank'>
            <em>
                <strong>".__($readMoreOrNot)."</strong>
            </em>
        </a>
*/
?>