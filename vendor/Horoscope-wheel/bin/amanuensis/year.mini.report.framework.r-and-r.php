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

ini_set("display_errors", 0);
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
error_reporting(E_ALL);

if (!defined('ROOTPATH')) {
	//define('ROOTPATH', '/www/astrowow');
	//define('ROOTPATH', '/var/www/vhosts/world-of-wisdom.com/astrowow.com/');
	define('ROOTPATH', '/home/astrowow/public_html/');
}

//Initializing the Classes and library and defult variables
define('CLASSPATH', ROOTPATH . '/classes');
define('LIBPATH', ROOTPATH . '/lib');
define('SPOOLPATH', ROOTPATH . '/var/spool');

//Its Added for PDO to support PDO			(14-Nov-2013 By Amit Parmar)
require_once (CLASSPATH . '/PDO.config.inc.php');

//Including FPDI library
require_once(LIBPATH . '/fpdf/fpdf.php');
define('FPDF_FONTPATH', LIBPATH . '/fpdf/fonts/');
require_once(LIBPATH . '/fpdi/fpdi.php');

require_once (LIBPATH . '/tcpdf/config/tcpdf_config.php');
require_once (LIBPATH . '/tcpdf/tcpdf.php');

require_once(ROOTPATH . '/include/lang/year-report/elite_common_variables.php');

/* SwissEph */
require_once(ROOTPATH . '/bin/test-swisseph/configuration/wheel-constants.php');
require_once(ROOTPATH . '/bin/test-swisseph/birthdto.php');
require_once(ROOTPATH . '/bin/test-swisseph/astrolog-services-server.php');
require_once(ROOTPATH . '/bin/test-swisseph/astrolog-calculator.php');

/* PDF Library (Open source lib) */
require_once(ROOTPATH . '/bin/test-swisseph/report-pdf/elite.finishing.pdf.php');
//require_once(ROOTPATH . '/bin/test-swisseph/report-pdf/commonpdfhelper.php');
require_once(ROOTPATH . '/bin/test-swisseph/report-pdf/elite.commonpdfhelper.php');

require_once(ROOTPATH . '/bin/test-swisseph/wheel-lib/generate-wheel-pdf.php');
require_once(ROOTPATH . '/bin/test-swisseph/wheel-lib/generate-transit-graph.php');
require_once(ROOTPATH . '/bin/test-swisseph/wheel-lib/generate-solar-return-pdf.php');
require_once(ROOTPATH . '/bin/test-swisseph/wheel-lib/generate-progressions.php');

/** TWO COLUMN YEAR REPORT WITHOUT GRAPHICS **/
require_once(ROOTPATH . '/bin/test-swisseph/wheel-lib/elite-report-generator.php'); 	//ADDED ON 18-Jan-2014

/* Generic Class and common functions */
/** TWO COLUMN YEAR REPORT WITHOUT GRAPHICS **/
require_once(ROOTPATH . '/bin/test-swisseph/report-pdf/elite.twocol.class.generichelper.php'); 	//ADDED ON 18-Jan-2014

/* Generic Class and common functions  */
/* POG data */

require_once(CLASSPATH . '/PDO.config.inc.php');	//This is added for PDO classes. [09-Jun-2014]

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

//Added For Elite Customer Generic EMails
require_once (CLASSPATH . '/delivery/email/class.delivery.email.wow.elite.en.php');
require_once (CLASSPATH . '/delivery/email/class.delivery.email.wow.elite.pp.en.php');

define ( 'CLASSMINIREPORTPATH', ROOTPATH . '/classes/mini-report/classes' );

require_once (CLASSPATH . '/collate/class.collate.php');
require_once (CLASSPATH . '/collate/class.collate.en.php');
require_once (CLASSPATH . '/collate/class.collate.wow.en.php');
require_once (CLASSPATH . '/mini-report-collate/class.collate.mini.randr.en.php');

set_include_path(get_include_path() . PATH_SEPARATOR . CLASSPATH.'/PHPLinq/');
set_include_path(get_include_path() . PATH_SEPARATOR . CLASSMINIREPORTPATH);

require_once ('PHPLinq/LinqToObjects.php');

require_once (CLASSMINIREPORTPATH . '/generic-classes.php');
require_once ('astrolog/astrolog.pipe.extented.php');
require_once ('astrolog/astrolog.service.extented.php');
require_once ('year-report/transit.calculation.php');

require_once ('languages/mini.en.php');
//require_once ('year-report/transit.calculation.old.php');
require_once ('year-report/final.report.generator.php');
require_once ('year-report/mini.generichelper.php'); 	

// deprecate and use Zend_Log instead
define('LOG4PHP_DIR', LIBPATH . '/log4php-0.9/src/log4php');
define('LOG4PHP_CONFIGURATION', ROOTPATH . '/var/lib/log4php/swissephe/wowuk.xml');

require_once(LOG4PHP_DIR . '/LoggerManager.php');
$logger = & LoggerManager::getLogger('swissephe::wowuk');
$reportLogger = & LoggerManager::getLogger('swissephe::wowuk');

//$reportLogger->debug("YearReport::Scheduler Start " . Date('dmY H:i:s') . " | ");

//Step 1 : Fetching Order status  and creating common data access objects/variables [Start]
//Now create Data access objects
$birthDataDAL       = new BirthData();      //Its use to get the User Birth data information
$horaryDataDAL      = new horarydata();     //Its use to get the User Birth data information
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
//$reportLogger->debug("YearReport:: Checking queued order | ");
/*
 * NOTE:    State = 3      - Newly queue order
*          Productid = 16  - Year report
*/
$orderDAL = new Order();

if(strpos(ROOTPATH, "public_html")!== FALSE) {
	$orderQueueList = $orderDAL->GetList ( array(
			array ('order_status', '=', 12 ),                                       	/* Queued */
			array ('portalid', '=', intval ( $affiliate_id ) ),                     	/* Portal ID */
			array ('delivery_option', '=', 1),                                       	/* Only Email Version */
			array ('product_item_id', 'IN', "(24)"),                             		/* YEAR REPORT */
	) );
} else {
	$orderQueueList = $orderDAL->GetList ( array(
			array ('order_status', '=', 12 ),                                       	/* Queued */
			array ('portalid', '=', intval ( $affiliate_id ) ),                     	/* Portal ID */
			array ('delivery_option', '=', 1),                                       	/* Only Email Version */
			array ('product_item_id', 'IN', "(36)"),                             		/* YEAR REPORT */
	) );
	//$orderQueueList = $orderDAL->GetList ( array( array ('order_id', '=', 64893 ) ) ); 	/** JAMES - YEAR REPORT */
	//$orderQueueList = $orderDAL->GetList ( array( array ('order_id', '=', 64897 ) ) ); 		/** ARD - YEAR REPORT */
	//$orderQueueList = $orderDAL->GetList ( array( array ('order_id', '=', 64891 ) ) ); 	/** AMIT - YEAR REPORT */
	
	$orderQueueList1 = array();
	$orderQueueList2 = array();
	$orderQueueList3 = array();
	$orderQueueList4 = array();
	
	$orderQueueList1 = $orderDAL->GetList ( array( array ('order_id', '=', 64898 ) ) ); 	/** JAMES Duncan - YEAR REPORT */
	$orderQueueList2 = $orderDAL->GetList ( array( array ('order_id', '=', 64899 ) ) );  	/** TOMMY Duncan - YEAR REPORT */
	$orderQueueList3 = $orderDAL->GetList ( array( array ('order_id', '=', 64900 ) ) ); 	/** Charlotte Duncan - YEAR REPORT */
	$orderQueueList4 = $orderDAL->GetList ( array( array ('order_id', '=', 64901 ) ) ); 	/** Adrian Duncan - YEAR REPORT */
	
	$orderQueueList = array_merge($orderQueueList1,$orderQueueList2,$orderQueueList3,$orderQueueList4);	
}

$languageCodes = array(
		'english' => 'en',
		'danish' => 'dk',
		'dansk' => 'dk',
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
$TableOfContent = array();

//Report Language and Email address

//Setup Global Variables
global $Global_Natal_MObject;					//Natal Wheel details
global $Global_Natal_TransitSortedList;         //Sorted Transit list with point
global $Global_Natal_Transit_Window;			//Transit list of 2 year
global $Global_Natal_m_transit;					//Transit list of 2 year
global $Global_Natal_m_crossing;				//House crossing list of 2 year

global $Global_PreviousYear;					//Trancking last year data  (like 16-July-2011)
global $Global_NextYear;						//Trancking next year data  (like 16-July-2013)
global $Global_CurrntYear;						//Trancking current year data  -Date of birth  (like 16-July-2012)
global $UserAge;								//Member Age
global $Gender;									//Member Gender

global $db;
global $eLiteUserID;
global $eLitePortalID;

//Setup Global Variables
echo "\n\n" .count($orderQueueList) . " YEAR REPORT \n\n";
if (count($orderQueueList) > 0) {
	//$reportLogger->debug('YearReport:: Total new order(s) : ' . count($orderQueueList) . ' | ');


	$birthdata = new BirthData ();
	$product = new Product ();
	$productDescriptionObj = new product_description();

	//Processing each new order and creating PDF
	foreach ($orderQueueList as $orderitem) {
		$Global_Natal_MObject = array();					//Natal Wheel details
		$Global_Natal_TransitSortedList = array();         	//Sorted Transit list with point
		$Global_Natal_Transit_Window = array();				//Transit list of 2 year
		$Global_Natal_m_transit = array();					//Transit list of 2 year
		$Global_Natal_m_crossing = array();					//House crossing list of 2 year
		
		
		/** #7F7F7E; GRAY **/
		$ReportHTMLGlobal = '<style>
				h1 { color: #2f386f; font-family: "arial"; font-size: "13pt"; line-height: "15pt"; font-weight: bold; vertical-align: baseline; }
				h2 { color: #3c3c3b; font-family: "arial"; font-size: "12pt"; line-height: "15pt"; font-weight: bold; vertical-align: baseline; }
				h3 { color: #3c3c3b; font-family: "arial"; font-size: "12pt"; line-height: "15pt"; font-weight: bold; vertical-align: baseline; }
				h4 { color: #3c3c3b; font-family: "arial"; font-size: "12pt"; line-height: "18pt"; font-weight: normal; vertical-align: baseline; }
				p.subHeading { color: #3c3c3b; font-family: arial; font-size: 20pt; font-weight:bold; line-height: 16pt; vertical-align: baseline; }
				p.sectionContent { color: #9d9d9c; font-family: arial; font-size: 14pt; font-weight: 600; line-height: 16pt; vertical-align: baseline; }
				</style>';
		
		//Fetching Report option for full name
		if(strlen($orderitem->language_code) > 2) {
			$reportLanguage = $languageCodes[strtolower( $orderitem->language_code ) ];
			echo "<pre>REPORT LAN :  $reportLanguage  :: ".$languageCodes[strtolower( $orderitem->language_code ) ]."</pre>";
		}
		else {
			$reportLanguage = strtolower( $orderitem->language_code );
		}		

		if($reportLanguage == "en")
			require_once (LIBPATH . '/tcpdf/lang/eng.php');
		else
			require_once (LIBPATH . '/tcpdf/lang/dan.php');

		$eLiteUserID = $orderitem->user_id;
		$eLitePortalID = $orderitem->portalid;

		$Global_Language = $reportLanguage;
		echo "<pre>reportLanguage :: $reportLanguage</pre>";

		//Alway takes English
		require_once(ROOTPATH . '/include/lang/year-report/en.elite.php');
		$YBookText  = new year_book_en();
		$YBookAgeText = new year_book_en_agetext();
		 

		//Now saving Order status to processing
		$orderitem->order_status = $orderStates['processing'];
		$orderitem->save();

		//Creating PROCESSING transaction entry
		$transactionDAL = new Transaction();
		$transactionDAL->orderId = $orderitem->order_id;
		$transactionDAL->state = $orderitem->order_status;
		$transactionDAL->timestamp = strftime("%Y-%m-%d %H:%M:%S");
		$transactionDAL->save();

		$productDescription = $productDescriptionObj->GetProductById($orderitem->product_item_id);

		//Creating object for the birth data for current order
		$birthDataObj = $birthDataDAL->GetList(array(array('orderid', '=', $orderitem->order_id)));

		if(count($birthDataObj) > 0) {
			echo "Order No :  $orderitem->order_id<br />";
			//  Step 3 : Birth data objects
			//We don't need this loop because there will be only one order
			foreach ($birthDataObj as $key => $bData) {
				$birthDTO = CreateBirthDTO($bData);
			}
			$bData = SetLatLong($bData);
			
			/*******************************************************************************/
			//  Step 4 : Processing Birth chart (Wheel)
			/******************** Wheel PDF Start ***************************** */
			global $planets;
			$UserFName = trim($bData->first_name);
			$memberName = sprintf("%s %s", trim($bData->first_name), trim($bData->last_name));
					
			if($bData->hour > -1) {			
				$UserBirthDate = sprintf("%02d-%02d-%04d %02d:%02d", $bData->day, $bData->month, $bData->year, $bData->hour, $bData->minute);				
			} else {
				$UserBirthDate = sprintf("%02d-%02d-%04d %02d:%02d", $bData->day, $bData->month, $bData->year, 12, 0);
			}
			
			$DateOfBirth = sprintf ( "%02d/%02d/%04d", $bData->month, $bData->day, $bData->year );
			
			$UserBirthDate = new DateTime($UserBirthDate);

			$ForAgeDiff = sprintf("%02d-%02d-%04d %02d:%02d", $bData->day, $bData->month, $bData->year, 0, 0);
			$ForAgeDiff = new DateTime($ForAgeDiff);
			$OrdDate = new DateTime($bData->start_date);
			$AgeDiff = $OrdDate->diff($ForAgeDiff);
			$UserAge = intval($AgeDiff->format('%Y'));
			
			$NewDate = $UserBirthDate;
			$NewDate->modify("+$UserAge years");
			
			/** Below code is commented on 23-Jan-2015. Due to wrong Year*/
			/**
			 $Global_PreviousYear = date("Y-m-d", mktime ( 0, 0, 0, $bData->month, $bData->day, date('Y', strtotime( $bData->start_date ) ) - 1 ) );
			 $Global_NextYear = date("Y-m-d", mktime ( 0, 0, 0, $bData->month, $bData->day, date('Y', strtotime( $bData->start_date ) ) + 1 ) );
			 $Global_CurrntYear= date("Y-m-d", mktime ( 0, 0, 0, $bData->month, $bData->day, date('Y', strtotime( $bData->start_date ) ) ) );
			 
			//new DateTime($bData->start_date)
			echo "Order No :  $orderitem->order_id<br />";
			echo date("Y-m-d", strtotime($bData->start_date) ). " DATE<br />";
			echo date("Y-m-d", strtotime("+1 year " . $bData->start_date) ). " DATE<br />";
			echo date("Y-m-d", strtotime("-1 year " . $bData->start_date) ). " DATE<br />";
			
			$Global_PreviousYear = date("Y-m-d", strtotime("-1 year " . $bData->start_date) );
			$Global_NextYear = date("Y-m-d", strtotime("+1 year " . $bData->start_date) );
			$Global_CurrntYear= date("Y-m-d", strtotime( $bData->start_date) ); 
			**/
			
			$Global_PreviousYear = date("Y-m-d", mktime ( 0, 0, 0, $NewDate->format('m'), $NewDate->format('d'),  $NewDate->format('Y')  - 1 ) );
			$Global_NextYear = date("Y-m-d", mktime ( 0, 0, 0, $NewDate->format('m'), $NewDate->format('d'),  $NewDate->format('Y')  + 1 ) );
			$Global_CurrntYear= date("Y-m-d", mktime ( 0, 0, 0, $NewDate->format('m'), $NewDate->format('d'), $NewDate->format('Y') ) );
				

			echo $UserAge . " User Age";
			$birthDTO->timed_data = false;
			
			$astrologService = new AstrologServices($birthDTO);
			$Global_Natal_MObject = $astrologService->m_object;
						
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

			$HouseCuspLongitude = array();
			
			for ($houseNo = 0; $houseNo < 12; $houseNo++) {
				$wheel->house_cusp_longitude[$houseNo] = $astrologService->m_object['cusp'][$houseNo + 1];
				$sign = intval($wheel->house_cusp_longitude[$houseNo] / 30.0);
				$HouseCuspLongitude[$sign] = $houseNo;
			}
			
			//Setting Birth data information Table
			SetBirthInformation($bData, &$wheel);
			
			//$reportLogger->debug("YearReport:: Creating wheel in PDF | ");
			$wheel->generateChartWheelPage();
			
			//$reportLogger->debug("YearReport:: Saving Wheel PDF | ");
			$wheel->Output(sprintf("%s/%d.wheel.pdf", SPOOLPATH, $orderitem->order_id));
			
			$wheel->Close();
			unset($wheelPdf);
			unset($wheel);
			
			/*******************************************************************************/
			/* look for transits from the start of 2010 until the end of a 3 year span => 2011 */
			
			//$generateTransitGraph = new TransitCalculation ($birthDTO, new DateTime($bData->start_date));
			$generateTransitGraph = new GenerateTransitForMiniReport ($birthDTO, new DateTime($bData->start_date));
			$generateTransitGraph->orderDate = new DateTime($bData->start_date);
			unset($generateTransitGraph);

			$orderitem->order_status = $orderStates['ready'];
			$orderitem->save();

			//          Set Report to Ready status
			$transactionDAL->state = $orderitem->order_status;
			$transactionDAL->timestamp = strftime("%Y-%m-%d %H:%M:%S");
			$transactionDAL->savenew();

			echo "Hello ERROR IS HERE";
			
			$reportGenerator = new MiniReportGenerator($YBookText, $YBookAgeText);
			$reportGenerator->OrderId = $orderitem->order_id;
			
			$reportGenerator->Open();
			$reportGenerator->AliasNbPages();
			$reportGenerator->AddFont('wows', '', 'wows.php');            // text
			$reportGenerator->AddFont('ww_rv1', '', 'ww_rv1.php');        // graphics
			$reportGenerator->SetFont('wows', '', 12);
			$reportGenerator->SetDisplayMode('fullpage');
			$reportGenerator->SetAutoPageBreak(true, 20);
			$reportGenerator->AddPage();			
			
			$reportGenerator->SetIntroductionText();
			
			$reportGenerator->Output(sprintf("%s/%d.bundle.pdf", SPOOLPATH, $orderitem->order_id));
			$reportGenerator->Close();
			unset($reportGenerator);
			
			/*******************GENERATING TCPDF report with HTML ******************/;
				
			$tcpdf = new RandR_TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
				
			// set some language-dependent strings (optional)
			$tcpdf->setLanguageArray($l);
				
			// set header and footer fonts
			$tcpdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
			$tcpdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
				
			// set default monospaced font
			$tcpdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
				
			// set margins
			$tcpdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
			$tcpdf->SetHeaderMargin(PDF_MARGIN_HEADER);
			$tcpdf->SetFooterMargin(PDF_MARGIN_FOOTER);
			$tcpdf->SetLineWidth(0.1);
			$tcpdf->setCellHeightRatio(1);
			$tcpdf->setHtmlVSpace(array('p' => array(array('n' => 0.1), array('n' => 0.1))));
			$tcpdf->setHtmlVSpace(array('h1' => array(array('n' => 0.1), array('n' => 0.1))));
			$tcpdf->setHtmlVSpace(array('h2' => array(array('n' => 0.1), array('n' => 0.1))));
				
			// set auto page breaks
			$tcpdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);					// set font
			$tcpdf->SetFont('dejavusans', '', 14, '', true);
				
			// get the current page break margin
			$bMargin = $tcpdf->getBreakMargin();
			// get current auto-page-break mode
			$auto_page_break = $tcpdf->getAutoPageBreak();
			// disable auto-page-break
			$tcpdf->SetAutoPageBreak(false, 0);
			
			// restore auto-page-break status
			$tcpdf->SetAutoPageBreak($auto_page_break, $bMargin);
			// set the starting point for the page content
			//$tcpdf->setPageMark();
			
			$tcpdf->SetReportType("year");
			$tcpdf->SetFranchise("R & R Music", "randrmusic.com");
			$tcpdf->SetCoverPage();
				
			$ReportHTMLGlobal = str_replace(". .", ".", $ReportHTMLGlobal );
			$ReportHTMLGlobal = str_replace("  ", " ",  $ReportHTMLGlobal );
			$ReportHTMLGlobal = str_replace("Â–", "-",  $ReportHTMLGlobal );
			$ReportHTMLGlobal = utf8_encode ( str_replace("–", "-",  $ReportHTMLGlobal ) );
			$ReportHTMLGlobal = str_replace("", "-",  $ReportHTMLGlobal );
			
			$tcpdf->SetFillColor(255, 255, 127);
			$tcpdf->PrintReportContent($ReportHTMLGlobal);	
			$tcpdf->Output ( sprintf ( "%s/%d.bundle.pdf", SPOOLPATH, $orderitem->order_id ), "F" );
			unset($tcpdf);
			
			/*******************GENERATING TCPDF report with HTML ******************/;
			
			echo "<pre>YearReport::Order Processing Completed Order No : " . $orderitem->order_id . " = " .$orderitem->delivery_option ." | </pre>";

			//set POST variables
			$url = 'http://restapi.tunehog.com/api/mobile/startune/reports.json';
			$fields = array(
					"email" => $orderitem->email_id,
					"report" => sprintf ( "@%s/%d.bundle.pdf", SPOOLPATH, $orderitem->order_id ),
					"id" => $orderitem->order_id
			);
			
			try {				
				//open connection
				$ch = curl_init();
				//set options
				curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-type: multipart/form-data"));
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //needed so that the $result=curl_exec() output is the file and isn't just true/false
				 
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_VERBOSE, 1);
				curl_setopt($ch, CURLOPT_HEADER, 1);
				 
				//execute post
				$result = curl_exec($ch);
				 
				//close connection
				curl_close($ch);				 

				//write to filed
				$fp = fopen( sprintf ( "%s/%d.bundle.pdf", SPOOLPATH, $orderitem->order_id ), 'w');  //make sure the directory markdown.md is in and the result.pdf will go to has proper permissions
				fwrite($fp, $result);
				fclose($fp);
				
				$orderitem->order_status = $orderStates['closed'];
				$orderitem->save();
				
				/* update transaction */
				$transaction = new Transaction ();
				$transaction->orderId = $orderitem->order_id;
				$transaction->state = $orderitem->order_status;
				$transaction->timestamp = strftime("%Y-%m-%d %H:%M:%S");
				$transaction->save();
				 
				echo "<pre>DATA IS PUSHED TO R & R Server</pre>";
			} catch (Exception $Ex) {
				echo "<pre>DATA IS NOT PUSHED TO R & R Server::: ".$Ex->getMessage()."</pre>";
				die;
			}
									
			//DELETING UNWANTED FILES
			DeleteUnwantedFile(SPOOLPATH, $orderitem->order_id);
			
			echo "<pre>UNWANTED FILES ARE DELETED</pre>";
		}
	}
}

//Releasing Memory
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
	$isTimed            = ((strtoupper(trim($bData->untimed)) == 'y') ? false : true );
	$bSummerTimeZone    = $bData->summerref;
	$bTimeZone          = $bData->zoneref;
	$bLongitude         = $bData->longitude;
	$bLatitude          = $bData->latitude;

	$bDate              = sprintf("%04d%02d%02d", $bData->year, $bData->month, $bData->day);
	
	if($bData->hour > -1) {
		$bTime              = sprintf("%02d:%02d", $bData->hour, $bData->minute);
	} else {
		$bTime              = sprintf("%02d:%02d", 12, 0);
	}
		
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

	//              BirthDTO(BirthDate | Time | isTimed | SummerTimezone |  Timezone | Longitude | Latitude)
	//$birthDTO = new BirthDTO($bDate, $bTime, $isTimed, $bSummerTimeZone, $bTimeZone, $bLongitude, $bLatitude);
	$birthDTO = new BirthDTO($bDate, $bTime, $isTimed, $bSummerTimeZone, $bTimeZone, $bLongitude, $bLatitude);
	if($bData->hour < 0) {
		$birthDTO->birthHour = sprintf("%02d", 12);
		$birthDTO->birthMinute = sprintf("%02d", 0);
		$birthDTO->timed_data =  false;
	} 
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

	//     echo "<pre> zone :: ". $birthentry->zoneref ."</pre>";
	//     echo "<pre> timedelta :: $timedelta</pre>";

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
	$wheel->table_user_info_birth_summertime= $timediff;
	$wheel->table_user_info_house_system	= (($isTimedData === true) ? 'Placidus' : 'Solar');
	$wheel->table_user_info_orb_system		= $wheel_top_orbsensitivity[ strtolower( $reportLanguage ) ];

	//BB specific

	//     $wheel->table_user_info_birth_weekday	= "SUN in " .$wheel->planet_in_house[0];
	//     $wheel->table_user_info_house_system		= "Moon in " .$wheel->planet_in_house[1];
	//     $wheel->table_user_info_orb_system		= intval($wheel->house_cusp_longitude[0] / 30.0);

	//     $wheel->table_user_info_birth_weekday		= sprintf("%s in %s", chr(184), $wheel->planet_in_house[0]);
	//     $wheel->table_user_info_house_system		= sprintf("%s in %s", chr(155), $wheel->planet_in_house[1]);
	//     $wheel->table_user_info_orb_system			= sprintf("%s in %s", chr(124), chr(intval($wheel->house_cusp_longitude[0] / 30.0) + 12));

	$wheel->table_user_info_birth_weekday		= (intval($wheel->planet_longitude[0] / 30.0) + 12);
	$wheel->table_user_info_house_system		= (intval($wheel->planet_longitude[1] / 30.0) + 12);
	$wheel->table_user_info_orb_system			= (intval($wheel->house_cusp_longitude[0] / 30.0) + 12);
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
	$placeList = $acsatlas->ExecuteCustomQuery( " lower(placename) = '" . strtolower($bData->place) ."' AND lkey like '". $bData->state . "%'");
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
}

function DeleteUnwantedFile($SPOOLPATH, $OrderNumber) {
	# delete file if exists
	$DeleteTransitgraphFile = sprintf("%s/%d.transitgraph.pdf", $SPOOLPATH, $OrderNumber);
	$DeleteWheelFile = sprintf("%s/%d.wheel.pdf", $SPOOLPATH, $OrderNumber);

	if (file_exists($DeleteTransitgraphFile))   {
		unlink ($DeleteTransitgraphFile);
	}

	if (file_exists($DeleteWheelFile))   {
		unlink ($DeleteWheelFile);
	}
}
?>