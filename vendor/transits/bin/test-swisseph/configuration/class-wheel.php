<?php

if (isset($debug_error_reporting) && $debug_error_reporting === true) {
    //error_reporting(E_ALL & ~E_DEPRECATED);
    error_reporting(E_ALL);
} else {
    //error_reporting(0 & ~E_DEPRECATED);
    error_reporting(0);
}

if (!defined('ROOTPATH')) {
    //define('ROOTPATH', '/home/29078/domains/world-of-wisdom.com');
    define('ROOTPATH', '/www/wow-year');
}

//Initializing the Classes and library and defult variables
define('CLASSPATH', ROOTPATH . '/classes');
define('LIBPATH', ROOTPATH . '/lib');
define('SPOOLPATH', ROOTPATH . '/var/spool');

//Including FPDI library
//require_once(LIBPATH . '/fpdf/fpdf_1.53.php');
require_once(LIBPATH . '/fpdf/fpdf.php');
define('FPDF_FONTPATH', LIBPATH . '/fpdf/fonts/');
require_once(LIBPATH . '/fpdi/fpdi.php');

require_once(ROOTPATH . '/include/lang/en.php');

/* SwissEph */
require_once(ROOTPATH . '/bin/test-swisseph/configuration/wheel-constants.php');
require_once(ROOTPATH . '/bin/test-swisseph/birthdto.php');
require_once(ROOTPATH . '/bin/test-swisseph/astrolog-services.php');
require_once(ROOTPATH . '/bin/test-swisseph/astrolog-calculator.php');
//require_once(ROOTPATH . '/bin/test-swisseph/birthtransit-calculation.php');
//require_once(ROOTPATH . '/bin/test-swisseph/calculate-transits.php');

/* PDF Library (Open source lib) */
require_once(ROOTPATH . '/bin/test-swisseph/report-pdf/finishing-pdf.php');
//require_once(ROOTPATH . '/bin/test-swisseph/report-pdf/bookmarkhelper.php');
require_once(ROOTPATH . '/bin/test-swisseph/report-pdf/finishing-pdf.php');
require_once(ROOTPATH . '/bin/test-swisseph/report-pdf/commonpdfhelper.php');
require_once(ROOTPATH . '/bin/test-swisseph/wheel-lib/generate-wheel-pdf.php');
//require_once(ROOTPATH . '/bin/test-swisseph/report-pdf/generate-wheel-pdf.php');
require_once(ROOTPATH . '/bin/test-swisseph/wheel-lib/generate-transit-graph.php');
require_once(ROOTPATH . '/bin/test-swisseph/wheel-lib/generate-solar-return-pdf.php');

require_once (ROOTPATH . '/bin/test-swisseph/jpgraph-2-3-3/src/jpgraph.php');
require_once (ROOTPATH . '/bin/test-swisseph/jpgraph-2-3-3/src/jpgraph_line.php');

// deprecate and use Zend_Log instead
define('LOG4PHP_DIR', LIBPATH . '/log4php-0.9/src/log4php');
define('LOG4PHP_CONFIGURATION', ROOTPATH . '/var/lib/log4php/swissephe/wowuk.xml');

require_once(LOG4PHP_DIR . '/LoggerManager.php');
$logger = & LoggerManager::getLogger('swissephe::wowuk');
$reportLogger = & LoggerManager::getLogger('swissephe::wowuk');

//ini_set('set_magic_quotes_runtime', 0);
//
global $planets;
$birthDTO = new BirthDTO(
                '19820716', //Birth Date
                '19:00', //Birth Time
                true, //User know his/her birth time
                0, //Calulated Summer time zone
                +530, //Calulated time zone
                -68.58, //Birth place longitude
                22.13);

$astrologService = new AstrologServices($birthDTO);

/*******************************************************************************/
/******************** Wheel PDF Start ***************************** */

$wheel = new GenerateWheelPDF('P', 'mm', 'A4');
$wheel->language = 'en';
$wheel->Open();
$wheel->AddFont('wows', '', 'wows.php');            // text
$wheel->AddFont('ww_rv1', '', 'ww_rv1.php');        // graphics
$wheel->SetDisplayMode('fullpage');
$wheel->SetAutoPageBreak(false);

for ($planetIndex = 0; $planetIndex < 12; $planetIndex++) {
    $wheel->planet_longitude[$planetIndex] = $astrologService->m_object[$planets[$planetIndex]]['longitude'];
    $wheel->planet_in_house[$planetIndex] = $astrologService->m_object[$planets[$planetIndex]]['house'];
    $wheel->planet_retrograde[$planetIndex] = $astrologService->m_object[$planets[$planetIndex]]['retrograde'];
}

for ($aspectNo = 0; $aspectNo < count($astrologService->m_aspect); $aspectNo++) {
    array_push($wheel->planet_aspects, $astrologService->m_aspect[$aspectNo]);
}

for ($houseNo = 0; $houseNo < 12; $houseNo++) {
    $wheel->house_cusp_longitude[$houseNo] = $astrologService->m_object['cusp'][$houseNo + 1];
}

$wheel->generateChartWheelPage();
//$wheel->Output(sprintf("%d.wheel.pdf", date('dmy')),"I");
$wheel->Output(sprintf("%s/%d.wheel.pdf", SPOOLPATH, date('dmy')),"I");

$wheel->Close();
unset($wheelPdf);

////Calculate the Solar Return and Horary Data
$SolarReturn = new GenerateSolarReturnPDF($birthDTO);
$astrologServiceForSolar = new AstrologServices($birthDTO);

$astrologServiceForSolar->CalculateSolarReturnTime($birthDTO, Date('Y'));
$SolarReturn->SolarReturnTime = $astrologServiceForSolar->solarReturnTime;
$SolarReturn->SolarReturnDate = $astrologServiceForSolar->solarReturnDate;

unset($astrologServiceForSolar->m_aspect);
$astrologServiceForSolar->CalculateSolarReturn($birthDTO, Date('Y'));
$SolarReturn->SolarReturnAspects = $astrologServiceForSolar->m_aspect;

$horaryData = new BirthDTO(
                Date('Ymd'),                //Birth Date
                Date('hh:mm'),              //Birth Time
                true,                       //User know his/her birth time
                0.00,                       //Calulated Summer time zone
                +530,                       //Calulated time zone
                '72.37',                    //Birth place longitude
                '23.02');                   //Birth place latitude

$astrologServiceForSolar->CalculateHorary($horaryData);
unset($astrologServiceForSolar->m_aspect);
$astrologServiceForSolar->CalculateHoraryAspects($horaryData);

$SolarReturn->HoraryAspects =  $astrologServiceForSolar->m_aspect;
$SolarReturn->SolarReturnAndHoraryAspects =  $astrologServiceForSolar->aspectSolarToHorary;

$SolarReturn->Open();
$SolarReturn->AddFont('wows', '', 'wows.php');            // text
$SolarReturn->AddFont('ww_rv1', '', 'ww_rv1.php');            // text
$SolarReturn->SetDisplayMode('fullpage');
$SolarReturn->SetAutoPageBreak(false);
$SolarReturn->SetFont('wows', '', 12);
$SolarReturn->SetSolarReturnAspect();
$SolarReturn->SetHoraryAspect();
$SolarReturn->SetSolarReturnToHoraryAspect();
$SolarReturn->Output(sprintf("%s/%d.solarReturn.pdf", SPOOLPATH, date('dmy')), "I");
$SolarReturn->Close();
unset($SolarReturn);

/*******************************************************************************/
/* look for transits from the start of 2010 until the end of a 3 year span => 2011 */
$generateTransitGraph = new GenerateTransitGraph ($birthDTO);
$generateTransitGraph->Open();
$generateTransitGraph->AddFont('wows', '', 'wows.php');            // text
$generateTransitGraph->AddFont('ww_rv1', '', 'ww_rv1.php');            // text
$generateTransitGraph->SetDisplayMode('fullpage');
$generateTransitGraph->SetAutoPageBreak(true);
$generateTransitGraph->SetFont('wows', '', 12);
$generateTransitGraph->AddGraphtoPDF();
$generateTransitGraph->Output(sprintf("%s/%d.transitgraph.pdf", SPOOLPATH, date('dmy')), "I");
$generateTransitGraph->Close();
unset($SolarReturn);

unset($astrologService);
/******************** Wheel PDF End ******************************/
$finalBundlePDF = new FinalBundleGeneraterPDF();
$finalBundlePDF->AliasNbPages();
$finalBundlePDF->CurrentOrderId = 6317;
$finalBundlePDF->UserFullName = 'Amit Parmar';
$finalBundlePDF->FinalBundling();
$finalBundlePDF->Output(sprintf("%s/%d.finalbundle.pdf", SPOOLPATH, 6317));
//$finalBundlePDF->Output(sprintf("%s/%d.finalbundle.pdf", SPOOLPATH, $orderitem->orderId));
$finalBundlePDF->closeParsers();
unset($finalBundlePDF);
?>