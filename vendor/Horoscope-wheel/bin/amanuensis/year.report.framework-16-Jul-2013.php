<?php
/**
 * @name YearReport Scheduler
 * @package YearReportScheduler
 *
 * @author Amit Parmar <parmaramit1111@gmail.com>
 * @copyright World of wisdom Inc and Amit Parmar <parmaramit1111@gmail.com>
 * @version 1.0
 *
 * Step by step process to generate the report
 *
 * Step 1 : Fetch the all Order Status and creating common data access objects/variables
 *          [ Example : Queue |  Processing | Close etc.. ]
 * Step 2 : Fetch newly queued report order
 *
 * Step 3 : Generate Birth Data object and setting transaction
 *
 * Step 4 : Processing Birth chart (Wheel)
 *
 * Step 5 : Processing Transit graph
 *
 * Step 6 : Processing Solar Return, Horary and aspect calculation
 *
 * Step 7 : Processing Prograssion calculation
 *
 * Step 8 : Bundling all the PDF files
 *
 * Step 9 : Email the generated PDF
 *
 * Step 10: Setting order status to Close
 */
ini_set('memory_limit', '-1');
set_time_limit(12000);
date_default_timezone_set ( 'America/Los_Angeles' );

if (isset($debug_error_reporting) && $debug_error_reporting === true) {    
    error_reporting(E_ALL);
} else {    
    error_reporting(0);
}

//ini_set("display_errors", 1);
//error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);

if (!defined('ROOTPATH')) {
    //define('ROOTPATH', '/www/astrowow');
    //define('ROOTPATH', '/var/www/vhosts/world-of-wisdom.com/astrowow.com/');
    define('ROOTPATH', '/home/astrowow/public_html/');
}

//Initializing the Classes and library and defult variables
define('CLASSPATH', ROOTPATH . '/classes');
define('LIBPATH', ROOTPATH . '/lib');
define('SPOOLPATH', ROOTPATH . '/var/spool');

//Including FPDI library
require_once(LIBPATH . '/fpdf/fpdf.php');
define('FPDF_FONTPATH', LIBPATH . '/fpdf/fonts/');
require_once(LIBPATH . '/fpdi/fpdi.php');

require_once(ROOTPATH . '/include/lang/year-report/common_variables.php');

/* SwissEph */
require_once(ROOTPATH . '/bin/test-swisseph/configuration/wheel-constants.php');
require_once(ROOTPATH . '/bin/test-swisseph/birthdto.php');
require_once(ROOTPATH . '/bin/test-swisseph/astrolog-services-server.php');
require_once(ROOTPATH . '/bin/test-swisseph/astrolog-calculator.php');

/* PDF Library (Open source lib) */
require_once(ROOTPATH . '/bin/test-swisseph/report-pdf/finishing-pdf.php');
require_once(ROOTPATH . '/bin/test-swisseph/report-pdf/commonpdfhelper.php');
require_once(ROOTPATH . '/bin/test-swisseph/wheel-lib/generate-wheel-pdf.php');
require_once(ROOTPATH . '/bin/test-swisseph/wheel-lib/generate-transit-graph.php');
require_once(ROOTPATH . '/bin/test-swisseph/wheel-lib/generate-solar-return-pdf.php');
require_once(ROOTPATH . '/bin/test-swisseph/wheel-lib/generate-progressions.php');

require_once(ROOTPATH . '/bin/test-swisseph/wheel-lib/report-generator.php'); 	//ADDED ON 09-March-2012

/* Generic Class and common functions */
require_once(ROOTPATH . '/bin/test-swisseph/report-pdf/class.generichelper.php'); 	//ADDED ON 06-June-2012
/* Generic Class and common functions  */

//require_once (ROOTPATH . '/bin/test-swisseph/jpgraph-2-3-3/src/jpgraph.php');
//require_once (ROOTPATH . '/bin/test-swisseph/jpgraph-2-3-3/src/jpgraph_line.php');

/* POG data */
require_once(CLASSPATH . '/configuration.php');
require_once(CLASSPATH . '/objects/class.database.php');
require_once(CLASSPATH . '/objects/class.pog_base.php');

require_once (CLASSPATH . '/objects/class.state.php');
require_once (CLASSPATH . '/objects/class.order.php');              //POG CLASS ADDED
require_once (CLASSPATH . '/objects/class.transaction.php');
require_once (CLASSPATH . '/objects/class.portal.php');
require_once (CLASSPATH . '/objects/class.product.php');
require_once (CLASSPATH . '/objects/class.product.description.php');
require_once (CLASSPATH . '/objects/class.birthdata.php');          //POG CLASS ADDED
require_once (CLASSPATH . '/objects/class.horarydata.php');
require_once (CLASSPATH . '/objects/class.reportoption.php');
require_once (CLASSPATH . '/objects/class.emailaddress.php');

require_once(CLASSPATH . '/objects/class.year_book_en.php');			// YEAR REPORT TEST Interpretation text
require_once(CLASSPATH . '/objects/class.year_book_en_agetext.php');	// YEAR REPORT TEST Interpretation Age text
require_once(CLASSPATH . '/objects/class.year_book_dk.php');			// YEAR REPORT TEST Interpretation text
require_once(CLASSPATH . '/objects/class.year_book_dk_agetext.php');	// YEAR REPORT TEST Interpretation Age text

/* ACS Atlas */
require_once(CLASSPATH . '/objects/class.acs.atlas.php');
require_once(CLASSPATH . '/acs/class.acs.statelist.php');

require_once(ROOTPATH . '/dal/acsRepository.php');

/* Mailer */
// targetted for development action
// deprecate and use Zend_Mail instead
require_once(LIBPATH . '/Swift-3.3.2-php5/lib/Swift.php');
require_once(LIBPATH . '/Swift-3.3.2-php5/lib/Swift/Connection/Sendmail.php');

require_once(CLASSPATH . '/delivery/email/class.delivery.email.php');
require_once(CLASSPATH . '/delivery/email/class.delivery.email.aller.dk.php');
require_once(CLASSPATH . '/delivery/email/class.delivery.email.datastar.en.php');
require_once(CLASSPATH . '/delivery/email/class.delivery.email.pernilleholm.dk.php');
require_once(CLASSPATH . '/delivery/email/class.delivery.email.seo.en.php');
require_once(CLASSPATH . '/delivery/email/class.delivery.email.wow.en.php');
require_once(CLASSPATH . '/delivery/email/class.delivery.email.wow.dk.php');
require_once(CLASSPATH . '/delivery/email/class.delivery.email.wow.se.php');
require_once(CLASSPATH . '/delivery/email/class.delivery.email.zodia.en.php');
require_once(CLASSPATH . '/delivery/email/class.delivery.email.wow.yr.en.php');

// deprecate and use Zend_Log instead
define('LOG4PHP_DIR', LIBPATH . '/log4php-0.9/src/log4php');
define('LOG4PHP_CONFIGURATION', ROOTPATH . '/var/lib/log4php/swissephe/wowuk.xml');

require_once(LOG4PHP_DIR . '/LoggerManager.php');
$logger = & LoggerManager::getLogger('swissephe::wowuk');
$reportLogger = & LoggerManager::getLogger('swissephe::wowuk');

$reportLogger->debug("YearReport::Scheduler Start " . Date('dmY H:i:s') . " | ");

//Step 1 : Fetching Order status  and creating common data access objects/variables [Start]
//Now create Data access objects
$birthDataDAL       = new BirthData();      //Its use to get the User Birth data information
$horaryDataDAL      = new horarydata();      //Its use to get the User Birth data information
$productDAL         = new product();        //Its use to get the Product information
$reportOptionDAL    = new ReportOption();   //Its use to get the User full name
$orderStateDAL      = new State();          //Its use to get the Order Status
$acsAtlasDAL        = new ACSAtlas();       //Its use to get the Place and country name

$orderStates = array();
$states = array();
$orderStateList = $orderStateDAL->GetList(array(array('stateId', '>', 0)));

foreach ($orderStateList as $sItem) {
    $orderStates[$sItem->name] = $sItem->stateId;
    $states[$sItem->name] = $sItem->stateId;
}

//Step 2 : Fetch newly queued report order
$reportLogger->debug("YearReport:: Checking queued order | ");
/*
 * NOTE:    State = 3      - Newly queue order
*          Productid = 16  - Year report
*/
$orderDAL = new Order();
$orderQueueList = $orderDAL->GetList ( array(
                        array ('order_status', '=', 12 ),                                           /* Queued */
                        array ('portalid', '=', intval ( $affiliate_id ) ),                         /* Portal ID */
                        array ('delivery_option', '=', 1),                                          /* Only Email Version */
                        array ('product_type', '=', 5),                                             /* Only Email Version */
                        array ('product_item_id', '=', "19"),                                       /* YEAR REPORT */
                    ) );
//$orderQueueList = $orderDAL->GetList( array( array( 'order_id', '=', 183 ) ) );			// Sir Age 63
//$orderQueueList = $orderDAL->GetList( array( array( 'order_id', '=', 1616 ) ) );                   // James Duncan

$languageCodes = array(
        'english' => 'en',
        'danish' => 'dk',
        'swedish' => 'se',
        'spanish' => 'sp');

//Report Language and Email address
$reportLanguage  = 'en';
$memberName      = '';
$startDate;
$reportDuration  = 3;
$reportPageSize  = 'A4';
$emailAddressId  = 0;
$deliveryEmailId = '';

//Report Language and Email address

//Setup Global Variables
global $Global_Natal_MObject;				//Natal Wheel details
global $Global_Natal_TransitSortedList;                 //Sorted Transit list with point
global $Global_Natal_Transit_Window;			//Transit list of 2 year
global $Global_Natal_m_transit;				//Transit list of 2 year
global $Global_Natal_m_crossing;			//House crossing list of 2 year

global $Global_PreviousYear;				//Trancking last year data  (like 16-July-2011)
global $Global_NextYear;				//Trancking next year data  (like 16-July-2013)
global $Global_CurrntYear;				//Trancking current year data  -Date of birth  (like 16-July-2012)
global $UserAge;					//Member Age
global $Gender;						//Member Gender

//Setup Global Variables

if (count($orderQueueList) > 0) {
    $reportLogger->debug('YearReport:: Total new order(s) : ' . count($orderQueueList) . ' | ');


    $birthdata = new BirthData ();
    $product = new Product ();
    $productDescriptionObj = new product_description();

    //Processing each new order and creating PDF
    foreach ($orderQueueList as $orderitem) {
        //Fetching Report option for full name
        if(strlen($orderitem->language_code) > 2) {
            $reportLanguage = $languageCodes[strtolower( $orderitem->language_code ) ];
            echo "<pre>REPORT LAN :  $reportLanguage  :: ".$languageCodes[strtolower( $orderitem->language_code ) ]."</pre>";
        }
        else {
            $reportLanguage = strtolower( $orderitem->language_code );
        }

        $Global_Language = $reportLanguage;
        echo "<pre>reportLanguage :: $reportLanguage</pre>";

        //Alway takes English
        $YBookText  = new year_book_en();
        $YBookAgeText = new year_book_en_agetext();

        if($reportLanguage == 'en') {
            require_once(ROOTPATH . '/include/lang/year-report/en.php');
            $YBookText  = new year_book_en();
            $YBookAgeText = new year_book_en_agetext();
        }
        else if($reportLanguage == 'dk') {
            require_once(ROOTPATH . '/include/lang/year-report/dk.php');
            $YBookText  = new year_book_dk();
            $YBookAgeText = new year_book_dk_agetext();
        }
        else {
            require_once(ROOTPATH . '/include/lang/year-report/en.php');
        }

        //Now saving Order status to processing
        $orderitem->order_status = $orderStates['processing'];
        $orderitem->save();
        $reportLogger->debug('YearReport:: Order Status = PROCESSING | ');

        //Creating PROCESSING transaction entry
        $transactionDAL = new Transaction();
        $transactionDAL->orderId = $orderitem->order_id;
        $transactionDAL->state = $orderitem->order_status;
        $transactionDAL->timestamp = strftime("%Y-%m-%d %H:%M:%S");
        $transactionDAL->save();
        $reportLogger->debug('YearReport:: - Added new Transaction = PROCESSING | ');

        $productDescription = $productDescriptionObj->GetProductById($orderitem->product_item_id);

        //Creating object for the birth data for current order
        $birthDataObj = $birthDataDAL->GetList(array(array('orderid', '=', $orderitem->order_id)));
        $reportLogger->debug('YearReport:: Gethering birth data of the current order');

        if(count($birthDataObj) > 0) {
            //  Step 3 : Birth data objects
            //We don't need this loop because there will be only one order
            foreach ($birthDataObj as $key => $bData) {
                $birthDTO = CreateBirthDTO($bData);
            }
            $bData = SetLatLong($bData);

            /*******************************************************************************/
            //  Step 4 : Processing Birth chart (Wheel)
            /******************** Wheel PDF Start ***************************** */
            $reportLogger->debug("YearReport:: Calculating Birth chart data for Wheel PDF | ");
            global $planets;

            $memberName = sprintf("%s %s", trim($bData->first_name), trim($bData->last_name));
            $Global_PreviousYear = date("Y-m-d", mktime ( 0, 0, 0, $bData->month, $bData->day, date('Y', strtotime( $bData->start_date ) ) - 1 ) );
            $Global_NextYear = date("Y-m-d", mktime ( 0, 0, 0, $bData->month, $bData->day, date('Y', strtotime( $bData->start_date ) ) + 1 ) );
            $Global_CurrntYear= date("Y-m-d", mktime ( 0, 0, 0, $bData->month, $bData->day, date('Y', strtotime( $bData->start_date ) ) ) );

            $UserBirthDate = sprintf("%02d-%02d-%04d %02d:%02d", $bData->day, $bData->month, $bData->year, $bData->hour, $bData->minute);
            $UserBirthDate = new DateTime($UserBirthDate);
            $OrdDate = new DateTime($bData->start_date);

            $AgeDiff = $OrdDate->diff($UserBirthDate);
            //$UserAge = intval($AgeDiff->format('%Y')) + 1;
            $UserAge = intval($AgeDiff->format('%Y'));

            $astrologService = new AstrologServices($birthDTO);

            $wheel = new GenerateWheelPDF('P', 'mm', 'A4');
            $wheel->language = $reportLanguage;
            $wheel->Open();
            //$wheel->AliasNbPages();
            $wheel->AddFont('wows', '', 'wows.php');            //text
            $wheel->AddFont('ww_rv1', '', 'ww_rv1.php');        //graphics
            $wheel->SetDisplayMode('fullpage');
            $wheel->SetAutoPageBreak(false);

            $Global_Natal_MObject = $astrologService->m_object;

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

            //Setting Birth data information Table
            SetBirthInformation($bData, &$wheel);

            $reportLogger->debug("YearReport:: Creating wheel in PDF | ");
            $wheel->generateChartWheelPage();

            $reportLogger->debug("YearReport:: Saving Wheel PDF | ");
            $wheel->Output(sprintf("%s/%d.wheel.pdf", SPOOLPATH, $orderitem->order_id));

            $wheel->Close();
            unset($wheelPdf);
            unset($wheel);

            /*******************************************************************************/
            /* look for transits from the start of 2010 until the end of a 3 year span => 2011 */

            $reportLogger->debug("YearReport:: Creating Transit Graph Object | ");

            $generateTransitGraph = new GenerateTransitGraph ($birthDTO, new DateTime($bData->start_date));
            $generateTransitGraph->orderDate = new DateTime($bData->start_date);

            $generateTransitGraph->Open();
            $generateTransitGraph->AliasNbPages();
            $generateTransitGraph->AddFont('wows', '', 'wows.php');                 // text
            $generateTransitGraph->AddFont('ww_rv1', '', 'ww_rv1.php');             // text
            $generateTransitGraph->SetDisplayMode('fullpage');
            $generateTransitGraph->SetAutoPageBreak(true, 20);
            $generateTransitGraph->AddPage();
            $generateTransitGraph->SetFont('wows', '', 12);
            $reportLogger->debug("YearReport:: Placing Transit graph in PDF | ");
            $generateTransitGraph->AddGraphtoPDF();   //Show only Transit Dates
            $reportLogger->debug("YearReport:: Saving Transit graph PDF | ");
            $generateTransitGraph->Output(sprintf("%s/%d.transitgraph.pdf", SPOOLPATH, $orderitem->order_id));
            $generateTransitGraph->Close();
            unset($generateTransitGraph);

            /*******************************************************************************/
            //  Step 5 :
            /******************************* Solar return chart ************************************************/

            $SolarReturn = new GenerateSolarReturnPDF($birthDTO);
            $astrologServiceForSolar = new AstrologServices($birthDTO , true);

            $astrologServiceForSolar->natalChart = $astrologServiceForSolar->m_object;

            $reportLogger->debug("YearReport:: Calculate Solar return date time | ");
            $astrologServiceForSolar->CalculateSolarReturnTime($birthDTO, date('Y', strtotime($bData->start_date)));

            $SolarReturn->SolarReturnTime =  DATE("H:i", STRTOTIME($astrologServiceForSolar->solarReturnTime));
            $SolarReturn->SolarReturnDate = $astrologServiceForSolar->solarReturnDate;

            //Write code for solar return to Natal
            //Calulate Solar Return Chart
            $astrologServiceForSolar->CalculateSolarReturnChart($birthDTO);

            unset($astrologServiceForSolar->m_aspect);

            $astrologServiceForSolar->CalculateSolarReturn($birthDTO, date('Y', strtotime($bData->start_date)));
            $SolarReturn->SolarReturnAspects = $astrologServiceForSolar->m_aspect;

            $wheelSolarReturn = new GenerateWheelPDF('P', 'mm', 'A4');
            $wheelSolarReturn->language = $reportLanguage;
            $wheelSolarReturn->Open();
            $wheelSolarReturn->AddFont('wows', '', 'wows.php');            // text
            $wheelSolarReturn->AddFont('ww_rv1', '', 'ww_rv1.php');        // graphics
            $wheelSolarReturn->SetDisplayMode('fullpage');
            $wheelSolarReturn->SetAutoPageBreak(false);

            for ($planetIndex = 0; $planetIndex < 12; $planetIndex++) {
                $wheelSolarReturn->planet_longitude[$planetIndex] = $astrologServiceForSolar->m_object[$planets[$planetIndex]]['longitude'];
                $wheelSolarReturn->planet_in_house[$planetIndex] = $astrologServiceForSolar->m_object[$planets[$planetIndex]]['house'];
                $wheelSolarReturn->planet_retrograde[$planetIndex] = $astrologServiceForSolar->m_object[$planets[$planetIndex]]['retrograde'];
            }

            for ($aspectNo = 0; $aspectNo < count($astrologServiceForSolar->m_aspect); $aspectNo++) {
                array_push($wheelSolarReturn->planet_aspects, $astrologServiceForSolar->m_aspect[$aspectNo]);
            }

            for ($houseNo = 0; $houseNo < 12; $houseNo++) {
                $wheelSolarReturn->house_cusp_longitude[$houseNo] = $astrologServiceForSolar->m_object['cusp'][$houseNo + 1];
            }

            //Prioritizing Solar return internal aspects
            $SolarReturn->PrioritizingInternalAspect();

            global $Global_Solar_MObject;
            global $global_Solar_HouseCups;
            global $global_Solar_PlanetLongitude;
            global $Global_Solar_InternalAspect;

            $Global_Solar_MObject = $astrologServiceForSolar->m_object;
            $global_Solar_HouseCups = $wheelSolarReturn->house_cusp_longitude;
            $global_Solar_PlanetLongitude = $wheelSolarReturn->planet_longitude;

            SetBirthInformation($bData, &$wheelSolarReturn);

            //Setting Birth data information Table
            $reportLogger->debug("YearReport:: Creating wheelSolarReturn in PDF | ");

            $SRDate = sprintf('%2d/%02d/%4d', substr($SolarReturn->SolarReturnDate, 6,2), substr($SolarReturn->SolarReturnDate, 4,2),substr($SolarReturn->SolarReturnDate, 0,4));

            $wheelSolarReturn->table_user_info_birth_date = $SRDate . strtoupper( $SolarReturn->SolarReturnTime );
            $wheelSolarReturn->table_user_info_lname .= 'Solar Return Chart';

            $wheelSolarReturn->generateChartWheelPage();

            $reportLogger->debug("YearReport:: Saving wheelSolarReturn PDF | ");
            $wheelSolarReturn->Output(sprintf("%s/%d.wheelSolarReturn.pdf", SPOOLPATH, $orderitem->order_id));

            $wheelSolarReturn->Close();
            unset($wheelSolarReturn);

            //Write code for solar return to Natal
            $astrologServiceForSolar->CalulateAspectForSolarReturnToNatal();

            $SolarReturn->SolarReturnToNatalAspects = $astrologServiceForSolar->aspectSolarToNatal;
            //Prioritizing Solar return to natal aspects
            $SolarReturn->PrioritizingTransit();

            $SolarReturn->Open();
            $SolarReturn->AliasNbPages();
            $SolarReturn->AddFont('wows', '', 'wows.php');            		// text
            $SolarReturn->AddFont('ww_rv1', '', 'ww_rv1.php');            	// text
            $SolarReturn->SetFont('wows', '', 12);
            $SolarReturn->SetDisplayMode('fullpage');
            $SolarReturn->SetAutoPageBreak(true, 20);
            $SolarReturn->AddPage();

            $reportLogger->debug("YearReport:: Setting Solar return aspect to Natal chart in PDF | ");
            $SolarReturn->PrintListOfSortedTransit();

            $reportLogger->debug("YearReport:: Saving Solar return PDF | ");
            $SolarReturn->Output(sprintf("%s/%d.solarReturn.pdf", SPOOLPATH, $orderitem->order_id));
            $SolarReturn->Close();

            unset($SolarReturn);
            unset($astrologServiceForSolar);

            /***********************************************************/
            /*			Calculate Prograssion						   */
            /***********************************************************/

            $progressionDate = date('Y-m-d H:i:s', strtotime($bData->start_date));
            $progressionDay = date('d', strtotime($bData->start_date));
            $progressionMonth = date('m', strtotime($bData->start_date));
            $progressionYear = date('Y', strtotime($bData->start_date));

            $asForProgression = new AstrologServices($birthDTO);

            //			Calculating Progressed chart
            $asForProgression->CalculatePrograssionsChart($birthDTO, $progressionDay, $progressionMonth, $progressionYear);

            $wheelProgression = new GenerateWheelPDF('P', 'mm', 'A4');
            $wheelProgression->language = $reportLanguage;
            $wheelProgression->Open();
            $wheelProgression->AddFont('wows', '', 'wows.php');            // text
            $wheelProgression->AddFont('ww_rv1', '', 'ww_rv1.php');        // graphics
            $wheelProgression->SetDisplayMode('fullpage');
            $wheelProgression->SetAutoPageBreak(false);

            for ($planetIndex = 0; $planetIndex < 12; $planetIndex++) {
                $wheelProgression->planet_longitude[$planetIndex] = $asForProgression->m_object[$planets[$planetIndex]]['longitude'];
                $wheelProgression->planet_in_house[$planetIndex] = $asForProgression->m_object[$planets[$planetIndex]]['house'];
                $wheelProgression->planet_retrograde[$planetIndex] = $asForProgression->m_object[$planets[$planetIndex]]['retrograde'];
            }

            for ($aspectNo = 0; $aspectNo < count($asForProgression->m_aspect); $aspectNo++) {
                array_push($wheelProgression->planet_aspects, $asForProgression->m_aspect[$aspectNo]);
            }

            for ($houseNo = 0; $houseNo < 12; $houseNo++) {
                $wheelProgression->house_cusp_longitude[$houseNo] = $asForProgression->m_object['cusp'][$houseNo + 1];
            }

            //Setting Birth data information Table
            SetBirthInformation($bData, &$wheelProgression);

            $wheelProgression->table_user_info_fname .= ' Progression Chart';
            $wheelProgression->table_user_info_birth_date = date('Y-m-d H:i', strtotime($bData->start_date));

            $reportLogger->debug("YearReport:: Creating wheelProgression in PDF | ");
            $wheelProgression->generateChartWheelPage();

            $reportLogger->debug("YearReport:: Saving wheelProgression PDF | ");
            $wheelProgression->Output(sprintf("%s/%d.wheelProgression.pdf", SPOOLPATH, $orderitem->order_id));

            $wheelProgression->Close();
            unset($wheelProgression);

            //Calculating Cross aspects
            $asForProgression->ProgressionToNatalAspects($birthDTO, $progressionDay, $progressionMonth, $progressionYear);
            global $Global_Progression_MObject;
            global $Global_Progression_m_transit;
            global $Global_Progression_m_crossing;
            global $Global_Progression_TransitSortedList;

            //Setting Progressed Chart Position
            $Global_Progression_MObject = $asForProgression->m_object;

            $asForProgression->CalculateDynamicProgression($birthDTO, date('Y', strtotime( $Global_PreviousYear )) - 2, 10);
            $Global_Progression_m_transit = $asForProgression->m_transit;

            //Calculating Sign Crossing
            $asForProgression->CalculateDynamicProgressionIngress($birthDTO, date('Y', strtotime( $Global_PreviousYear )) - 2, 10);
            $Sign =  $asForProgression->m_crossing;

            //Calculating House Crossing
            unset($asForProgression->m_crossing);
            $asForProgression->CalcPrograssedHouseCrossing($birthDTO, date('Y', strtotime( $Global_PreviousYear )) - 2, 10);
            $House = $asForProgression->m_crossing;
            $Global_Progression_m_crossing = array_merge($Sign, $House);

            unset($asForProgression->m_transit);
            $asForProgression->CalculateProgressedToProgressed($birthDTO, date('Y', strtotime( $Global_PreviousYear )), 99);
            $Global_ProgressedToProgressed = $asForProgression->m_transit;

            $progressedAspectPDF = new GenerateProgressionsPDF($birthDTO, new DateTime($bData->start_date));
            $progressedAspectPDF->aspectProgressedToNatal = $asForProgression->aspectProgressedToNatal;

            $progressedAspectPDF->Open();
            $progressedAspectPDF->AliasNbPages();
            $progressedAspectPDF->AddFont('wows', '', 'wows.php');            		// text
            $progressedAspectPDF->AddFont('ww_rv1', '', 'ww_rv1.php');            	// text
            $progressedAspectPDF->SetFont('wows', '', 12);
            $progressedAspectPDF->SetDisplayMode('fullpage');
            $progressedAspectPDF->SetAutoPageBreak(true, 20);
            $progressedAspectPDF->AddPage();

            $reportLogger->debug("YearReport:: Setting Progressed to Natal aspect in PDF | ");
            $progressedAspectPDF->GenerateProgressionList();
            $progressedAspectPDF->FullListOfProgression();

            $reportLogger->debug("YearReport:: Saving Progressed to natal content PDF | ");
            $progressedAspectPDF->Output(sprintf("%s/%d.ProgressionContent.pdf", SPOOLPATH, $orderitem->order_id));
            $progressedAspectPDF->Close();

            unset($progressedAspectPDF);
            unset($asForProgression);

            $reportGenerator = new ReportGenerator($YBookText, $YBookAgeText);

            $reportGenerator->Open();
            $reportGenerator->AliasNbPages();
            $reportGenerator->AddFont('wows', '', 'wows.php');            // text
            $reportGenerator->AddFont('ww_rv1', '', 'ww_rv1.php');        // graphics
            $reportGenerator->SetFont('wows', '', 12);
            $reportGenerator->SetDisplayMode('fullpage');
            $reportGenerator->SetAutoPageBreak(true, 20);
            $reportGenerator->AddPage();

            $reportGenerator->SetIntroductionText();

            //PRINT 3 THEMES and Section wise data;
//			$reportGenerator->PrintTop3Themes();
//			$reportGenerator->PrintReportData();

//			$reportGenerator->SolarReturnInternalAspects();
//			$reportGenerator->PrintSolarReturnTopAspect();

            $reportGenerator->Output(sprintf("%s/%d.FinalReport.pdf", SPOOLPATH, $orderitem->order_id));
            $reportGenerator->Close();
            unset($reportGenerator);
            //Now binding all the PDF

            $reportLogger->debug("YearReport:: bundling all the PDFs");
            $finalBundlePDF = new FinalBundleGeneraterPDF();
            $finalBundlePDF->AliasNbPages();
            $finalBundlePDF->CurrentOrderId = $orderitem->order_id;
            $finalBundlePDF->UserFullName = $memberName;
            $finalBundlePDF->BirthDate = GetBirthDateTime(false, $bData);
            $finalBundlePDF->BirthTime = GetBirthDateTime(true, $bData);
            $finalBundlePDF->BirthPlace = GetCountryAndPlaceName($bData);
            $finalBundlePDF->FinalBundling();
            $finalBundlePDF->Output(sprintf("%s/%d.bundle.pdf", SPOOLPATH, $orderitem->order_id) ,'F');
            $finalBundlePDF->closeParsers();
            unset($finalBundlePDF);

            $orderitem->order_status = $orderStates['ready'];
            $orderitem->save();

//          Set Report to Ready status
            $transactionDAL->state = $orderitem->order_status;
            $transactionDAL->timestamp = strftime("%Y-%m-%d %H:%M:%S");
            $transactionDAL->savenew();
            $reportLogger->debug("YearReport::Order Processing Completed Order No : " . $orderitem->order_id . " | ");

//          Creating Email Objects
            $emailObj = new WowYearReportEmailDelivery_EN();
            $emailObj->setOrderId($orderitem->order_id);
            $emailObj->send();
            $reportLogger->debug("YearReport:: Emaiil Send to Order No : " . $orderitem->order_id . " | ");

            //DELETING UNWANTED FILES
            DeleteUnwantedFile(SPOOLPATH, $orderitem->order_id);
            echo "<pre>Email is Sent</pre>";
        }
    }
}

//Releasing memory
unset($birthDataDAL);
unset($horaryDataDAL);
unset($productDAL);
unset($reportOptionDAL);
unset($orderStateDAL);
unset($orderDAL);
unset($orderQueueList);
unset($astrologService);


function CreateBirthDTO($bData) {
    echo "<pre>************************CreateBirthDTO()</pre>";
    $bDate              = sprintf("%04d%02d%02d", $bData->year, $bData->month, $bData->day);
    $bTime              = sprintf("%02d:%02d", $bData->hour, $bData->minute);

    $isTimed            = ((strtoupper(trim($bData->untimed)) == '1') ? false : true );
    $bSummerTimeZone    = $bData->summerref;
    $bTimeZone          = $bData->zoneref;
    $bLongitude         = $bData->longitude;
    $bLatitude          = $bData->latitude;

    $Lat = $bData->latitude;
    $Long = $bData->longitude;
    $CalLat = $bData->latitude;
    $CalLong = $bData->longitude;

    if($bData->latitude >= -90 && $bData->latitude <= 90) {
        $Lat = $bData->latitude * 3600;
    }
    else {
        $bData->latitude = $bData->latitude / 3600;
        $bLatitude = $bData->latitude;
        $Lat = $bData->latitude;
    }

    if($bData->longitude >= -180 && $bData->longitude <= 180) {
        $Long = $bData->longitude * 3600;
    }
    else {
        $bData->longitude = $bData->longitude / 3600;
        $bLongitude = $bData->longitude;
        $Long = $bData->longitude;
    }

    $ZoneValue = abs( number_format(floatval( $bTimeZone ), 2) );
    $tmpZone = intval($ZoneValue);
    $tmpZoneDiff = number_format( floatval(  $ZoneValue - $tmpZone ), 2 );
    $FinalZone = $ZoneValue;
    if($tmpZoneDiff > 0.0 &&  $tmpZoneDiff <= 0.50 ) {
        $FinalZone = number_format( floatval( $tmpZone + 0.30 ), 2);
    }
    else if($tmpZoneDiff >= 0.51 && $tmpZoneDiff <= 1 ){
        $FinalZone = number_format( floatval( $tmpZone + 0.45 ), 2);
    }
    if( floatval( $bData->zoneref ) < 0) {
        $bTimeZone = number_format(floatval((-1 * $FinalZone)), 2);
    }
    else {
        $bTimeZone = $FinalZone;
    }
//    $zhh = intval( $bTimeZone );
//    $zmm = intval( floatval( floor( ($bTimeZone - intval($bTimeZone)) * 100.0) ) );
//    $zmm = intval( floatval($zmm) * (60.0 / 100.0) );               /* convert to minutes */
//    $FinalZone = sprintf("%d%.02d", $zhh, abs($zmm) );
//    $bTimeZone = $FinalZone;
//
//    $shh = intval( $bSummerTimeZone );
//    $smm = intval( floatval( floor( ($bSummerTimeZone - intval($bSummerTimeZone)) * 100.0) ) );
//    $smm = intval( floatval($zmm) * (60.0 / 100.0) );                       /* convert to minutes */
//    $FinalSZone = sprintf("%d%.02d", $shh, abs($smm) );
//    $bSummerTimeZone = $FinalSZone;
    
    echo "== $bTimeZone :: $FinalZone ==  $bSummerTimeZone == $FinalSZone";

    //              BirthDTO(BirthDate | Time | isTimed | SummerTimezone |  Timezone | Longitude | Latitude)
    //$birthDTO = new BirthDTO($bDate, $bTime, $isTimed, $bSummerTimeZone, $bTimeZone, $bLongitude, $bLatitude);

    $birthDTO = new BirthDTO($bDate, $bTime, $isTimed, $bSummerTimeZone, $bTimeZone, $bLongitude, $bLatitude);
    return $birthDTO;
}

function CreateHoraryDTO($hData) {
    echo "<pre>************************CreateHoraryDTO()</pre>";
    $bDate              = sprintf("%04d%02d%02d", $hData->year, $hData->month, $hData->day);
    $bTime              = sprintf("%02d:%02d", $hData->hour, $hData->minute);
    $isTimed            = true;
    $bSummerTimeZone    = $hData->summerref;
    $bTimeZone          = $hData->zoneref;
    $bLangitude         = $hData->longitude;
    $bLatitude          = $hData->latitude;

    $ZoneValue = abs( number_format(floatval( $bTimeZone ), 2) );
    $tmpZone = intval($ZoneValue);
    $tmpZoneDiff = number_format( floatval(  $ZoneValue - $tmpZone ), 2 );
    $FinalZone = $ZoneValue;
    if($tmpZoneDiff > 0.0 &&  $tmpZoneDiff <= 0.50 ){
        $FinalZone = number_format( floatval( $tmpZone + 0.30 ), 2);
    }
    else if($tmpZoneDiff >= 0.51 && $tmpZoneDiff <= 1 ){
        $FinalZone = number_format( floatval( $tmpZone + 0.45 ), 2);
    }
    if( floatval( $bData->zoneref ) < 0) {
        $bTimeZone = number_format(floatval((-1 * $FinalZone)), 2);
    }
    else {
        $bTimeZone = $FinalZone;
    }

//    $zhh = intval( $bTimeZone );
//    $zmm = intval( floatval( floor( ($bTimeZone - intval($bTimeZone)) * 100.0) ) );
//    $zmm = intval( floatval($zmm) * (60.0 / 100.0) );               /* convert to minutes */
//    $FinalZone = sprintf("%d%.02d", $zhh, abs($zmm) );
//    $bTimeZone = $FinalZone;
//
//    $shh = intval( $bSummerTimeZone );
//    $smm = intval( floatval( floor( ($bSummerTimeZone - intval($bSummerTimeZone)) * 100.0) ) );
//    $smm = intval( floatval($zmm) * (60.0 / 100.0) );                       /* convert to minutes */
//    $FinalSZone = sprintf("%d%.02d", $shh, abs($smm) );
//    $bSummerTimeZone = $FinalSZone;

    //              BirthDTO(BirthDate | Time | isTimed | SummerTimezone |  Timezone | Longitude | Latitude)
    $horaryDTO = new BirthDTO($bDate, $bTime, $isTimed, $bSummerTimeZone, $bTimeZone, $bLangitude, $bLatitude);
    return $horaryDTO;
}

function parseCoords($coord, $long=true) {
    echo "<pre>************************parseCoords()</pre>";
    if ($long === true) {
        $hemi = ($coord < 0) ? 'E' : 'W';
    } else {
        $hemi = ($coord < 0) ? 'S' : 'N';
    }
    $coord = abs($coord); 
    $degree = intval($coord);
    $coord = floatval($coord - $degree);
    $minute = intval(floatval($coord * 60.0));
    $second = 0;
    return sprintf("%d:%02d:%02d%s", $degree, $minute, $second, $hemi);
}

function SetBirthInformation($birthentry, &$wheel) {
    echo "<pre>************************SetBirthInformation()</pre>";
    global $reportLanguage;
    global $wheel_top_weekdays;
    global $wheel_top_orbsensitivity;

    //Setting Birth data information Table [Start]
    $isTimedData = ( (strtoupper(trim($birthentry->untimed)) == '1') ? false :true  );

    if( $isTimedData === true ) {
        $birthdate = sprintf("%02d/%02d/%04d %02d:%02d", $birthentry->day, $birthentry->month, $birthentry->year,
                $birthentry->hour, $birthentry->minute);
    } else {
        $birthdate = sprintf("%02d/%02d/%04d", $birthentry->day, $birthentry->month, $birthentry->year);
    }

    $birthplace = '';
    $birthcountry = '';
    $PlaceAndCountry = GetCountryAndPlaceName($birthentry);
    $PC = explode(',', $PlaceAndCountry);
    if(count($PC) > 0) {
        $birthplace = trim($PC[0]);
        if(count($PC) >= 1) {
            $birthcountry = trim($PC[1]);
        }
    }

    $coords = sprintf("%2d%s%02d %3d%s%02d",
            abs( intval( $birthentry->latitude ) ),
            (($birthentry->latitude >= 0) ? 'N' : 'S'),
            abs( intval( (($birthentry->latitude - intval( $birthentry->latitude )) * 60 ))),
            abs( intval( $birthentry->longitude )),
            (($birthentry->longitude >= 0) ? 'W' : 'E'),
            abs( intval( (($birthentry->longitude - intval( $birthentry->longitude )) * 60 )))
    );

    $timedelta =  number_format(abs($birthentry->zoneref), 2);
    $timedelta = str_replace('.', ':', $timedelta);

    echo "<pre> zone :: ". $birthentry->zoneref ."</pre>";
    echo "<pre> timedelta :: $timedelta</pre>";

    $timediffStr =  number_format(abs($birthentry->summerref), 2);
    $timediff = str_replace('.', ':', $timediffStr);

//    $timediffStr = sprintf("%04d", intval( abs($birthentry->summerref) ) );
//    $timediff = sprintf("%d:%02d", intval( substr($timediffStr,0,2) ), intval( substr($timediffStr,2,2) ));

    $wheel->wheel_offset = $wheel->house_cusp_longitude[0];

    $wheel->table_user_info_fname		= sprintf("%s %s", trim($birthentry->first_name), trim($birthentry->last_name));
    $wheel->table_user_info_lname		= "";
    $wheel->table_user_info_birth_weekday	= $wheel_top_weekdays[ strtolower( $reportLanguage ) ]
            [JDDayOfWeek( cal_to_jd(CAL_GREGORIAN,$birthentry->month,$birthentry->day,$birthentry->year), 1 )];
    $wheel->table_user_info_birth_date		= $birthdate;
    $wheel->table_user_info_birth_place		= trim($birthplace);
    $wheel->table_user_info_birth_state		= trim($birthcountry);
    $wheel->table_user_info_birth_coords	= $coords;
    $wheel->table_user_info_birth_timezone	= $timedelta;
    $wheel->table_user_info_birth_summertime	= $timediff;
    $wheel->table_user_info_house_system	= (($isTimedData === true) ? 'Placidus' : 'Solar');
    $wheel->table_user_info_orb_system		= $wheel_top_orbsensitivity[ strtolower( $reportLanguage ) ];
}

function SetHoraryInformation($birthentry, &$wheel, $reportoptionitem) {
    echo "<pre>************************SetHoraryInformation()</pre>";
    global $wheel_top_weekdays;
    global $wheel_top_orbsensitivity;
    global $Global_Language;

    //Setting Birth data information Table [Start]
    $isTimedData = true;
    $birthdate = sprintf("%02d/%02d/%04d %02d:%02d", $birthentry->day, $birthentry->month, $birthentry->year,
            $birthentry->hour, $birthentry->minute);

    $birthplace = '';
    $birthcountry = '';

    $PlaceAndCountry = GetCountryAndPlaceName($birthentry);
    $PC = explode(',', $PlaceAndCountry);

    if(count($PC) > 0) {
        $birthplace = trim($PC[0]);
        if(count($PC) >= 1) {
            $birthcountry = trim($PC[1]);
        }
    }

    $coords = sprintf("%2d%s%02d %3d%s%02d",
            abs( intval( $birthentry->latitude ) ),
            (($birthentry->latitude >= 0) ? 'N' : 'S'),
            abs( intval( (($birthentry->latitude - intval( $birthentry->latitude )) * 60 ))),
            abs( intval( $birthentry->longitude )),
            (($birthentry->longitude >= 0) ? 'W' : 'E'),
            abs( intval( (($birthentry->longitude - intval( $birthentry->longitude )) * 60 )))
    );

    /* daylight savings offset */
    $timezone = $birthentry->zoneref;
    $summertime = $birthentry->summerref;

    $timediff = sprintf("%04d", abs($summertime));
    $timediff_hh = substr($timediff,0,2);
    $timediff_mm = substr($timediff,2,2);
    $timediff = ((intval($summertime) < 0) ? '-' : '');
    $timediff .= sprintf("%d:%02d", $timediff_hh, $timediff_mm);

    /* timezone offset */
    $timedelta = sprintf("%04d", abs($timezone));
    $timedelta_hh = substr($timedelta,0,2);
    $timedelta_mm = substr($timedelta,2,2);
    $timedelta = ((intval($timezone) < 0) ? '-' : '');
    $timedelta .= sprintf("%d:%02d", $timedelta_hh, $timedelta_mm);

    $wheel->wheel_offset = $wheel->house_cusp_longitude[0];

    if( $Global_Language == 'dk' ) {
        $wheel->table_user_info_fname		= utf8_decode( trim( $reportoptionitem->name ) );
    } else {
        $wheel->table_user_info_fname		= trim($reportoptionitem->name);
    }
    $wheel->table_user_info_lname		= "";
    $wheel->table_user_info_birth_weekday	= $wheel_top_weekdays[ strtolower( $reportoptionitem->language ) ]
            [JDDayOfWeek( cal_to_jd(CAL_GREGORIAN,$birthentry->month,$birthentry->day,$birthentry->year), 1 )];
    $wheel->table_user_info_birth_date		= $birthdate;
    $wheel->table_user_info_birth_place		= trim($birthplace);
    $wheel->table_user_info_birth_state		= trim($birthcountry);
    $wheel->table_user_info_birth_coords	= $coords;
    $wheel->table_user_info_birth_timezone	= $timedelta;
    $wheel->table_user_info_birth_summertime	= $timediff;
    $wheel->table_user_info_house_system	= (($isTimedData === true) ? 'Placidus' : 'Solar');
    $wheel->table_user_info_orb_system		= $wheel_top_orbsensitivity[ strtolower( $reportoptionitem->language ) ];
}

function GetCountryAndPlaceName($birthentry) {
    echo "<pre>************************GetCountryAndPlaceName()</pre>";
    /* get place information */
    $countryAbbr = $birthentry->state;
    $birthplace = $birthentry->place;
    $fullbirthplace = explode ( ">", $birthplace );

    if (count ( $fullbirthplace ) > 0) {
        $birthplace = trim ( $fullbirthplace [0] );
    }

    $bLongitude         = $birthentry->longitude;
    $bLatitude          = $birthentry->latitude;

    if($bLatitude >= -90 && $bLatitude <= 90) {
        $bLatitude = $bLatitude * 3600;
    }

    if($bLongitude >= -180 && $bLongitude <= 180) {
        $bLongitude = $bLongitude * 3600;
    }

    $acsatlas = new ACSAtlas();
    //$placeList = $acsatlas->GetList( array(array ('latitude', '=', $bLatitude), array ('longitude', '=',  $bLongitude )));
    $placeList = $acsatlas->ExecuteCustomQuery( " lower(placename) = '" . strtolower($birthentry->place) ."' AND lkey like '". $birthentry->state . "%'");

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

    /* get state information */
    $acsstatelist = new ACSStatelist();
    $birthcountry = $acsstatelist->getStateNameByAbbrev( $countryAbbr );

    return sprintf( "%s, %s",	$birthplace, $birthcountry);
}

function GetBirthDateTime($isTime, $birthentry) {
    echo "<pre>************************GetBirthDateTime()</pre>";
    global $MonthNameForContent;
    global $Global_Language;
    if($isTime == false) {
        if( $Global_Language == 'dk' ) {
            return sprintf("%02d %s, %04d", $birthentry->day, $MonthNameForContent[$Global_Language][sprintf("%02d", $birthentry->month)], $birthentry->year);
        }
        else {
            return sprintf("%s %02d, %04d", $MonthNameForContent[$Global_Language][sprintf("%02d", $birthentry->month)],  $birthentry->day, $birthentry->year);
        }
    }
    else {
        $NormalFormat = "";
        $FinalFormat = "";
        $AMPM = 'am';

        if(intval($birthentry->hour) >= 12) {
            $NormalFormat = intval($birthentry->hour) - 12;
            $AMPM = "pm";
            $FinalFormat = sprintf("%02d:%02d %s", intval($NormalFormat), intval($birthentry->minute), $AMPM);
        }
        else {
            $NormalFormat = intval($birthentry->hour);
            $FinalFormat = sprintf("%02d:%02d %s", intval($NormalFormat),  intval($birthentry->minute), $AMPM);
        }

        return  sprintf("%s (%02d:%02d)", $FinalFormat, intval($birthentry->hour), intval($birthentry->minute));
    }
}

function SetLatLong(&$bData) {
    $Longitude = 0;
    $Latitude = 0;
    if($bData->latitude > -90 && $bData->latitude < 90) {
        $Latitude = $bData->latitude * 3600;
    }
    else {
        $Latitude = $bData->latitude;
        $bData->latitude = $bData->latitude / 3600;
    }

    if($bData->longitude > -180 && $bData->longitude < 180) {
        $Longitude = $bData->longitude * 3600;
    }
    else {
        $Longitude = $bData->longitude;
        $bData->longitude = $bData->longitude / 3600;
    }

    $birthplace = $bData->place;
    $countryAbbr = $bData->state;
    $acsatlas = new ACSAtlas();
    $placeList = $acsatlas->GetList( array(array ('latitude', '=', $Latitude), array ('longitude', '=', $Longitude )));
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
        $bData->zoneref = $IsThere['m_timezone_offset'];
        $bData->summerref = $IsThere['m_summertime_offset'];
    }
    else {
//        $ACSRep = new  ACSRepository();
//        $sql =  "select * from `acsatlas`".
//                " where upper(placename)='".strtoupper($bData->place)."' ".
//                " and longitude ='".$Longitude."'".
//                " and latitude ='".$Latitude."'".
//                " order by lkey";
//        $Result = $ACSRep->GetACSDataRow($sql);
//        if($Result) {
//            $acsTimeTable = new AcsTimetables();
//            $acsTimeTable->setBirthdate( sprintf("%04d-%02d-%02d %02d:%02d:%02d",
//                    $bData->Year, $bData->Month, $bData->Day,
//                    $bData->Hours, $bData->Minutes, 0) );
//
//            $bData->zoneref = $acsTimeTable->getZoneOffset($Result[0]['zone']);
//            $bData->summerref = $acsTimeTable->getTypeOffset($Result[0]['type']);
//        }
    }

    global $birthDataDAL;
    $birthId = $bData->Save();
    $birthDataObj1 = $birthDataDAL->GetList(array(array('birthdataid', '=', $birthId)));
    foreach ($birthDataObj1 as $key => $bData1) {
        $bData    = $bData;
    }

    return $bData;
}


function GetSummerTimeZoneANDTimeZone($location, $data) {
    $TimeZoneArray = array();

    if(extension_loaded('acsatlas')) {
        //Get the city info
        $city_info = acs_lookup_city($location);

        if (!$city_info) {
            return $TimeZoneArray;
        }
        extract($city_info);

        // $city_info = Array (
        // [city_index] => 4360
        // [country_index] => 4
        // [city] => Pomona
        // [county] => Los Angeles
        // [country] => California
        // [countydup] => 37
        // [latitude] => 122599
        // [longitude] => 423905
        // [typetable] => 83
        // [zonetable] => 7200)

        //Get the time zone info
        $time_info = acs_time_change_lookup($data->month, $data->day, $data->year,
                $data->hour, $data->minute, $zonetable, $typetable);

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

//            $zhh = intval( $ActualZoneValue );
//            $zmm = intval( floatval( floor( ($ActualZoneValue - intval($ActualZoneValue)) * 100.0) ) );
//            $zmm = intval( floatval($zmm) * (60.0 / 100.0) );               /* convert to minutes */
//            $FinalZone = sprintf("%d%.02d", $zhh, abs($zmm) );
//            $FinalZone = $FinalZone;
//
//            $shh = intval( $time_types[$type] );
//            $smm = intval( floatval( floor( ($time_types[$type] - intval($time_types[$type])) * 100.0) ) );
//            $smm = intval( floatval($zmm) * (60.0 / 100.0) );                       /* convert to minutes */
//            $FinalSZone = sprintf("%d%.02d", $shh, abs($smm) );
//            $bSummerTimeZone = $FinalSZone;
//
//            $TimeZoneArray["m_timezone_offset"] = number_format($FinalZone, 2);
//            $TimeZoneArray["m_summertime_offset"] = number_format( $bSummerTimeZone, 2);


            if( $ActualZoneValue < 0) {
                $TimeZoneArray["m_timezone_offset"] = number_format(-1 * floatval( $FinalZone ), 2);
            }
            else {
                $TimeZoneArray["m_timezone_offset"] = number_format(floatval( $FinalZone ), 2);
            }
            $TimeZoneArray["m_summertime_offset"] = number_format( floatval( $time_types[$type] ), 2);

//            $TimeZoneArray["m_timezone_offset"] = number_format(floatval( ($zone/900) ), 2);
//            $TimeZoneArray["m_summertime_offset"] = number_format( floatval( $time_types[$type] ), 2);
        }
    }
    return $TimeZoneArray;
}


function DeleteUnwantedFile($SPOOLPATH, $OrderNumber) {
    #complete serverpath must be given like
    #example "/apache/htdocs/myfile.pdf" ( not "http:xyz.com/myfile.pdf" )

    # delete file if exists
    $DeleteProgressionContentFile = sprintf("%s/%d.ProgressionContent.pdf", $SPOOLPATH, $OrderNumber);
    $DeletesolarReturnFile = sprintf("%s/%d.solarReturn.pdf", $SPOOLPATH, $OrderNumber);
    $DeleteTransitgraphFile = sprintf("%s/%d.transitgraph.pdf", $SPOOLPATH, $OrderNumber);
    $DeleteWheelFile = sprintf("%s/%d.wheel.pdf", $SPOOLPATH, $OrderNumber);
    $DeleteWheelProgressionFile = sprintf("%s/%d.wheelProgression.pdf", $SPOOLPATH, $OrderNumber);
    $DeleteWheelSolarReturnFile = sprintf("%s/%d.wheelSolarReturn.pdf", $SPOOLPATH, $OrderNumber);
    $DeleteFinalReportFile = sprintf("%s/%d.FinalReport.pdf", $SPOOLPATH, $OrderNumber);    

    if (file_exists($DeleteProgressionContentFile))   {     unlink ($DeleteProgressionContentFile);   }
    if (file_exists($DeletesolarReturnFile))   {     unlink ($DeletesolarReturnFile);   }
    if (file_exists($DeleteTransitgraphFile))   {     unlink ($DeleteTransitgraphFile);   }
    if (file_exists($DeleteWheelFile))   {     unlink ($DeleteWheelFile);   }
    if (file_exists($DeleteWheelProgressionFile))   {     unlink ($DeleteWheelProgressionFile);   }
    if (file_exists($DeleteWheelSolarReturnFile))   {     unlink ($DeleteWheelSolarReturnFile);   }
    if (file_exists($DeleteFinalReportFile))   {     unlink ($DeleteFinalReportFile);   }    
}
?>