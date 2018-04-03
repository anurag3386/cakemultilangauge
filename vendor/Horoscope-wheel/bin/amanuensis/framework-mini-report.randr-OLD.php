<?php
/**
 * Script: amanuensis.framework.php
 * @author: Amit Parmar <parmaramit1111@gmail.com>
 *
 * Description
 * Requires
 * @property - affiliate_id
 * @property - affiliate collate path
 * @property - affiliate logger config xml path
 * @property - affiliate logger tag
 */

if (isset ( $debug_error_reporting ) && $debug_error_reporting === true) {
	error_reporting ( E_ALL );
} else {
	error_reporting ( 0 );
}
error_reporting(E_ALL);
ini_set('display_errors', 1);

date_default_timezone_set ( 'America/Los_Angeles' );

/* path definitions */
if (! defined ( 'ROOTPATH' )) {
	//define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . 'astrowow');
	define('ROOTPATH', '/home/astrowow/public_html/');
}
define ( 'CLASSPATH', ROOTPATH . '/classes' );
define ( 'LIBPATH', ROOTPATH . '/lib' );
define ( 'SPOOLPATH', ROOTPATH . '/var/spool' );
define ( 'CLASSMINIREPORTPATH', ROOTPATH . '/classes/mini-report/classes' );

/* font information */
define ( 'FPDF_FONTPATH', LIBPATH . '/fpdf/fonts/' );
require_once (LIBPATH . '/fpdf/fpdf.php');
require_once (LIBPATH . '/fpdi/fpdi.php');

require_once (LIBPATH . '/tcpdf/config/tcpdf_config.php');
require_once (LIBPATH . '/tcpdf/tcpdf.php');

if($ReportLanguage == "en")
	require_once (LIBPATH . '/tcpdf/lang/eng.php');
else
	require_once (LIBPATH . '/tcpdf/lang/dan.php');

require_once (CLASSPATH . '/collate/class.collate.php');
require_once (CLASSPATH . '/collate/class.collate.de.php');
require_once (CLASSPATH . '/collate/class.collate.dk.php');
require_once (CLASSPATH . '/collate/class.collate.en.php');
require_once (CLASSPATH . '/collate/class.collate.se.php');
require_once (CLASSPATH . '/collate/class.collate.no.php'); /* Added on 18-Nov-2011 By Amit Parmar */
require_once (CLASSPATH . '/collate/' . $affiliate_collate_path);

//Add for Preview Generator
require_once (CLASSPATH . '/collate/preview.generate.php');

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
require_once (CLASSPATH . '/objects/class.bookdu.php'); /* TODO - need to port short term trends */
require_once (CLASSPATH . '/objects/class.bookge.php'); /* TODO - need to port short term trends */
require_once (CLASSPATH . '/objects/class.bookgr.php'); /* TODO - need to port short term trends */
require_once (CLASSPATH . '/objects/class.bookno.php'); /* TODO - need to port short term trends */
require_once (CLASSPATH . '/objects/class.booksp.php'); /* TODO - need to port short term trends */
require_once (CLASSPATH . '/objects/class.booksw.php'); /* TODO - need to port short term trends */

/* astrolog data */
// targetted for development action
// generate XML
require_once (CLASSPATH . '/astrolog/api.php');
require_once (CLASSPATH . '/astrolog/birthdata.php');
require_once (CLASSPATH . '/astrolog/pipe.php');

/* WOW data */
// targetted for development action
// deprecate FPDF and use TCPDF instead
// use Zend_Translate for embedded strings
// deprecate Netsity data format and use XML instead

//require_once (CLASSPATH . '/wow/birthanalysis.php'); 		// Deprecate
//require_once (CLASSPATH . '/wow/dynamicanalysis.php'); 		// Deprecate
//require_once (CLASSPATH . '/wow/report.php'); 				// Rewrite for XML
//require_once (CLASSPATH . '/wow/report.pdf.generic.php');
//require_once (CLASSPATH . '/wow/generator/default/report.pdf.rc1.php');

/* WOW Calendar/Seasonal reports */

require_once (CLASSMINIREPORTPATH . '/generator/forecast.php');
require_once (CLASSMINIREPORTPATH . '/generator/forecast.pdf.php');

require_once (CLASSPATH . '/mini-report-collate/forecast-generic-text.php');

/* WOW Wheel */
/* Developer Note - these are temporary until the issues are iron out */
//require_once (ROOTPATH . '/bin/wheel/class.report.pdf.wheel.rc1.php');
//require_once (ROOTPATH . '/bin/wheel/class.report.pdf.wheel.wow.php');


/* language resources */
//require_once (ROOTPATH . '/include/lang/en.php');
require_once (CLASSMINIREPORTPATH . '/languages/en.php');
require_once (CLASSMINIREPORTPATH . '/collate/class.collate.mini.wow.en.php');

/* WOW Wheel */
require_once (CLASSMINIREPORTPATH . '/wheel/report.pdf.generic.php');
require_once (CLASSMINIREPORTPATH . '/wheel/class.report.pdf.wheel.rc.mini.php');
require_once (CLASSMINIREPORTPATH . '/wheel/class.report.pdf.wheel.wow.mini.php');

//BIRTH ANALYSIS
require_once (CLASSMINIREPORTPATH . '/astrolog/apibridge.php');
require_once (CLASSMINIREPORTPATH . '/analysis/birthanalyzer.php');
//require_once (CLASSMINIREPORTPATH . '/report-generator/report-generator.php');
require_once (CLASSMINIREPORTPATH . '/report-generator/wow.mini.report.generator.php');
require_once (CLASSMINIREPORTPATH . '/report-generator/PDF.Report.Generator.php');

require_once (CLASSMINIREPORTPATH . '/generic-classes.php');
set_include_path(get_include_path() . PATH_SEPARATOR . CLASSPATH.'/PHPLinq/');

require_once ('PHPLinq/LinqToObjects.php');

// require_once (CLASSPATH . '/LinqForPhp/start.php');
// require_once (CLASSPATH . '/LinqForPhp/Collections/List.php');


/* Mailer */
// targetted for development action
// deprecate and use Zend_Mail instead
require_once (LIBPATH . '/Swift-3.3.2-php5/lib/Swift.php');
require_once (LIBPATH . '/Swift-3.3.2-php5/lib/Swift/Connection/Sendmail.php');

require_once (CLASSPATH . '/delivery/email/class.delivery.email.php');
require_once (CLASSPATH . '/delivery/email/class.delivery.email.aller.dk.php');
require_once (CLASSPATH . '/delivery/email/class.delivery.email.datastar.en.php');
require_once (CLASSPATH . '/delivery/email/class.delivery.email.pernilleholm.dk.php');
require_once (CLASSPATH . '/delivery/email/class.delivery.email.seo.en.php');
require_once (CLASSPATH . '/delivery/email/class.delivery.email.wow.en.php');
require_once (CLASSPATH . '/delivery/email/class.delivery.email.wow.dk.php');
require_once (CLASSPATH . '/delivery/email/class.delivery.email.wow.se.php');
require_once (CLASSPATH . '/delivery/email/class.delivery.email.zodia.en.php');
require_once (CLASSPATH . '/delivery/email/class.delivery.email.zodia.en.php');
require_once (CLASSPATH . '/delivery/email/class.delivery.email.kontura.no.php');
require_once (CLASSPATH . '/delivery/email/class.delivery.email.ascendinghearts.en.php');

require_once (CLASSMINIREPORTPATH . '/email-delivery/class.delivery.email.wow.mini.en.php');

// targetted for development action
// deprecate and use Zend_Log instead
define ( 'LOG4PHP_DIR', LIBPATH . '/log4php-0.9/src/log4php' );
define ( 'LOG4PHP_CONFIGURATION', ROOTPATH . '/var/lib/log4php/amanuensis/' . $affiliate_logger_tag . '.xml' ); /* find a home for this */
require_once (LOG4PHP_DIR . '/LoggerManager.php');
$logger = & LoggerManager::getLogger ( 'amanuensis::' . $affiliate_logger_tag );

/***************************************************************************/

/* build a table of states */
// targetted for development action
// work with constants
$state = new State ();
$states = array ();
$statelist = $state->GetList ( array (array ('stateId', '>', 0 ) ) );
foreach ( $statelist as $item ) {
	$states [$item->name] = $item->stateId;
}
/* look for queued orders */
$order = new Order ();

if(strpos(ROOTPATH, "public_html")!== FALSE) {
	$orderqueue = $order->GetList ( array(
			array ('order_status', '=', 12 ),                                       	/* Queued */
			array ('portalid', '=', intval ( $affiliate_id ) ),                     	/* Portal ID */
			array ('delivery_option', '=', 1),                                       	/* Only Email Version */
			array ('product_item_id', 'IN', "(22,23)"),                             	/* Personal / Seasonal */
			array ('language_code', '=', $ReportLanguage),                         		/* REPORT Language*/
	) );
} else {
	$orderqueue = $order->GetList ( array(
			array ('order_status', '=', 12 ),                                       	/* Queued */
			array ('portalid', '=', intval ( $affiliate_id ) ),                     	/* Portal ID */
			array ('delivery_option', '=', 1),                                       	/* Only Email Version */
			array ('product_item_id', 'IN', "(34, 35)"),                             	/* Personal / Seasonal */
			array ('language_code', '=', $ReportLanguage),                         		/* REPORT Language*/
	) );
	$orderqueue = $order->GetList ( array( array ('order_id', '=', 64893 ) ) );  /** PERSONAL */
	$orderqueue1 = $order->GetList ( array( array ('order_id', '=', 64896 ) ) ); /** PERSONAL */
	$orderqueue2 = $order->GetList ( array( array ('order_id', '=', 64897 ) ) ); /** SEASONAL */

	$orderqueue = array_merge($orderqueue, $orderqueue1, $orderqueue2);
	//$orderqueue = $order->GetList ( array( array ('order_id', '=', 64891 ) ) ); /** SEASONAL */
}

//22  = Personal/full/pc3
//23  = Calendar
echo $order->pog_query . '\\n<br />';

/* only process orders in the queued state */
echo " [ === R&R Music MAIN TOTAL ORDER : " .count ( $orderqueue ). '=== ]\\n<br />  ';

$languageCodes = array(
		'english' => 'en',
		'danish' => 'dk',
		'dansk' => 'dk',
		'swedish' => 'se',
		'spanish' => 'sp');

$ReportsDefaultLanguage = 'en';

if (count ( $orderqueue ) > 0) {
	$birthdata = new BirthData ();
	$reportoption = new ReportOption ();
	$product = new Product ();
	$productDescriptionObj = new product_description();

	// is this still required?
	// where is this used
	$planets = array ('Sun', 'Moon', 'Mercury', 'Venus', 'Mars', 'Jupiter', 'Saturn', 'Uranus', 'Neptune', 'Pluto', 'NNode', 'SNode', 'Ascendant', 'MC', 'IC', 'Descendant' );

	/* cycle through queued orders */
	foreach ( $orderqueue as $orderitem ) {
		error_log ( "\nFramework: time now is " . strftime ( "%c" ) . "\n", 3, ROOTPATH . "/data/log/amanuensis.log" );
		error_log ( "Framework: processing order ID=$orderitem->order_id\n", 3, ROOTPATH . "/data/log/amanuensis.log" );

		if(strlen($orderitem->language_code) > 2) {
			$ReportsDefaultLanguage = $languageCodes[strtolower( $orderitem->language_code ) ];
			echo "<pre>REPORT LAN :  $ReportsDefaultLanguage  :: ".$languageCodes[strtolower( $orderitem->language_code ) ]."</pre>";
		}
		else {
			$ReportsDefaultLanguage = strtolower( $orderitem->language_code );
		}

		/* update state = processing */
		$orderitem->order_status = $states ['processing'];
		$orderitem->save ();

		echo "[==ORDER NO ::". $orderitem->order_id." ==]";

		/* update transactions 'Amanuensis::Framework - creating new transaction - processing' */
		$transaction = new Transaction ();
		$transaction->orderId = $orderitem->order_id;
		$transaction->state = $orderitem->order_status;
		$transaction->timestamp = strftime ( "%Y-%m-%d %H:%M:%S" );
		$transaction->save ();

		$productDescription = $productDescriptionObj->GetProductById($orderitem->product_item_id);

		/* look for the birth data component :: 'Amanuensis::Framework - processing birthdata' */
		$birthdatalist = $birthdata->GetList ( array ( array ('orderId', '=', $orderitem->order_id ) ) );

		/* there should be exactly 1 result */
		if (count ( $birthdatalist ) != 1) {
			//'Amanuensis::Framework - multiple or no birthdata found'
		} else {
			//'Amanuensis::Framework - birthdata found'
		}

		foreach ( $birthdatalist as $birthentry ) {
			$formattedDate = sprintf ( "%04d%02d%02d", $birthentry->year, $birthentry->month, $birthentry->day );
			$formattedTime = sprintf ( "%02d:%02d", $birthentry->hour, $birthentry->minute );

			/** added for solar chart */
			$timed_data = ((trim ( $birthentry->untimed ) == 'y') ? false : true);

			$Lat = $birthentry->latitude;
			$Long = $birthentry->longitude;
			$CalLat = $birthentry->latitude;
			$CalLong = $birthentry->longitude;
			$StartDate = $birthentry->start_date;

			if($StartDate == '0000-00-00') {
				$StartDate = date('Y-m-d');
			}

			if($birthentry->latitude >= -90 && $birthentry->latitude <= 90) {
				$Lat = $birthentry->latitude * 3600;
			}
			else {
				$birthentry->latitude = $birthentry->latitude / 3600;
				$CalLat = $birthentry->latitude;
				$Lat = $birthentry->latitude;
			}

			if($birthentry->longitude >= -180 && $birthentry->longitude <= 180) {
				$Long = $birthentry->longitude * 3600;
			}
			else {
				$birthentry->longitude = $birthentry->longitude / 3600;
				$CalLong = $birthentry->longitude;
				$Long = $birthentry->longitude;
			}

			$ZoneRef = GetZoneValues($birthentry);
			$birthentry->zoneref = $ZoneRef;

			$data = new AstrologBirthData(  $formattedDate,             /* date         */
					$formattedTime,             /* time         */
					$timed_data,                /* timed data   */
					$birthentry->summerref,     /* summertime	 */
					$birthentry->zoneref,       /* timezone	 */
					$CalLong,                   /* longitude	 */
					$CalLat );                  /* latitude	 */
			echo "<pre>Data is Set now</pre>";
		} /* birthdata */

		/** 'Amanuensis::Framework - processing report options' */
		/** 'Amanuensis::Framework - creating PDF generator for default case ( A4 size )' */
		/** 'Amanuensis::Framework - generator language set to ' . $ReportsDefaultLanguage */
		switch (strtoupper ( $ReportsDefaultLanguage )) {
			case 'ENGLISH' :
			case 'EN' :
				/** 'Amanuensis::Framework - generator using English Book'  */
				$book = new BookUK ();
				$shorttermtrend = new ShortTermTrend ();
				break;
			default :
				/** 'Failed to determine which Book to use' */
				/* update state */
				/** 'Amanuensis::Framework - setting order state - orphaned'  */
				$orderitem->order_status = $states ['orphaned'];
				$orderitem->save ();

				/* update transactions */
				/** 'Amanuensis::Framework - creating new transaction - orphaned' */
				$transaction = new Transaction ();
				$transaction->orderId = $orderitem->order_id;
				$transaction->state = $orderitem->order_status;
				$transaction->timestamp = strftime ( "%Y-%m-%d %H:%M:%S" );
				$transaction->save ();
				die ( "failed to find language" );
		}

		/*
		 * Static reports are	: personal, career, pc                  (supported)
		* Seasonal reports are	: seasonal				(supported)
		* Calendar reports are	: calendar				(supported)
		* Dynamic reports are	: y3, pc3				(unsupported)
		*/
		$ProductsShortName = array('');
		$ReportHTMLText = '';
		/** REPORT GENERATOR to create the Final Out Put with Text */
		$FullName = sprintf("%s %s", trim ( $birthentry->first_name ), trim ( $birthentry->last_name ));

		switch ($orderitem->product_item_id) {
			case 22 :                                   //Personal Report
			case 34 :                                   //Personal Report
				/** 'Amanuensis::Framework - report type = ' . $report->type  */
				/* start the report at the beginning of next month */
				$start_month = intval ( substr ( $StartDate, 6, 2 ) );
				$start_year = intval ( substr ( $StartDate, 0, 4 ) );
				$OrderDate = $StartDate;

				//$start_month == 11 added by Amit Parmar (21-Nov-2011)
				/** 'Amanuensis::Framework -ANP $start_year - $start_month = ' . $start_year . ' - ' . $start_month */
				if ($start_month == 11) {
					$start_month = 1;
					$start_year ++;
				}
				if ($start_month == 12) {
					$start_month = 1;
					$start_year ++;
				}
				/** DB Context */
				$BookText = new BookUK ();

				/** Set Report text in Two columns */
				$PDFGenerator = new PDFReportGenerator( "A4");

				$StartMonth = intval (  $start_month );
				$StartYear = intval ( $start_year );
				$StartDate = sprintf ( '%04d-%02d-01 00:00:00', $StartYear, $StartMonth );

				$ReportType =  "mini-b";
				/** @todo: For testing, I have used MINI-B */

				/** PLACE BRITHANALYZER */
				$BirthAnalyzerContext = new BirthAnalyzer ( $data, $StartDate, 3, $ReportType, $OrderDate );

				global $Introduction_Generic_Text;
				$Introduction_Generic_Text[$ReportsDefaultLanguage]["Introduction"] = sprintf($Introduction_Generic_Text[$ReportsDefaultLanguage]["Introduction"], $FullName);

				$ReportGenerator = new Report($FullName, $ReportsDefaultLanguage, $orderitem->order_id);
				$ReportGenerator->UserName = $FullName;
				$ReportGenerator->ReportType = strtolower($ReportType);

				$ReportGenerator->Run($BirthAnalyzerContext, $BookText, $PDFGenerator,  $orderitem->order_id);

				$Introduction_Generic_Text[$ReportsDefaultLanguage]["Introduction"] = str_replace($FullName, "%s", $Introduction_Generic_Text[$ReportsDefaultLanguage]["Introduction"]);
				
				$ReportGenerator->Generator->pdf->Output ( sprintf ( "%s/%d.report.pdf", SPOOLPATH, $orderitem->order_id ) );
				$ReportGenerator->Generator->pdf->Close ();
				
				$ReportHTMLText = utf8_encode( $PDFGenerator->ReportHTML );	
				$ReportType =  "personal";
				break;
			case 23 :
			case 35 :                       //Seasonal Report for 3 Months with Questions and Anwser
				echo $orderitem->product_item_id . " Product Item Id In condition<br />";

				$ReportType =  "seasonal";
				$report = new Forecast();
					
				$generator = new PDF_Forecast ();
				$generator = new Forecast_With_Generic_Text(
						trim ( $birthentry->first_name ),
						trim ( $birthentry->last_name ),
						sprintf ( "%02d/%02d/%04d", $birthentry->month, $birthentry->day, $birthentry->year ));

				$logger->debug ( "Amanuensis::Framework - generator for seasonal report" );

				$generator->GenericIntroductionPageContentForSeasonal();

				$report->run($data,                             	/* birth data	 */
						$StartDate,                         	/* start date	 */
						1,              						/* Its only 1 month duration */
						true,                               	/* seasonal	 	 */
						$ReportsDefaultLanguage,        		/* language      */
						$generator );                       	/* generator	 */

				$generator->GenericUpSellPageContentForSeasonal($orderitem->order_id);
				
				$Introduction_Generic_Text[$ReportsDefaultLanguage]["Introduction"] = str_replace($FullName, "%s", $Introduction_Generic_Text[$ReportsDefaultLanguage]["Introduction"]);
				
				$ReportHTMLText = utf8_encode( $generator->ReportHTML );
				
				$report->generator->pdf->Output ( sprintf ( "%s/%d.report.pdf", SPOOLPATH, $orderitem->order_id ) );
				$report->generator->pdf->Close ();
				break;
			default:
				/* update state */
				/** 'Amanuensis::Framework - setting order state - orphaned' */
				$orderitem->order_status = $states ['orphaned'];
				$orderitem->save ();

				/* update transactions */
				/** 'Amanuensis::Framework - creating new transaction - orphaned' */
				$transaction = new Transaction ();
				$transaction->orderId = $orderitem->order_id;
				$transaction->state = $orderitem->order_status;
				$transaction->timestamp = strftime ( "%Y-%m-%d %H:%M:%S" );
				$transaction->save ();
				die ( "failed to find report type" );
				break;
		}

		/** Setup Country and City name  */
		if ($timed_data === true) {
			$birthdate = sprintf ( "%02d/%02d/%04d %02d:%02d", $birthentry->day, $birthentry->month, $birthentry->year, $birthentry->hour, $birthentry->minute );
		} else {
			$birthdate = sprintf ( "%02d/%02d/%04d", $birthentry->day, $birthentry->month, $birthentry->year );
		}

		echo "<pre>AstrologChartAPI()  Creating Chart Row Material</pre>";
		$generator->language = $ReportsDefaultLanguage;
				
		echo "*************************************<br />";
		$tcpdf = new RandR_TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);		
		// set some language-dependent strings (optional)
		
		$tcpdf->setLanguageArray($l);
		$tcpdf->SetReportType($ReportType);
		$tcpdf->SetFranchise("R & R Music", "randrmusic.com");
		$tcpdf->SetCoverPage();
		$tcpdf->PrintReportContent($ReportHTMLText);		
		
		$tcpdf->Output ( sprintf ( "%s/%d.bundle.pdf", SPOOLPATH, $orderitem->order_id ), "F" );
		unset($tcpdf);
		echo "*************************************<br />";

		/* update state */
		$logger->debug ( 'Amanuensis::Framework - setting order state - ready' );
		$orderitem->order_status = $states ['ready'];
		$orderitem->save ();

		/* update transaction */
		$transaction->state = $orderitem->order_status;
		$transaction->timestamp = strftime ( "%Y-%m-%d %H:%M:%S" );
		$transaction->savenew ();

		/** if delivery method is email then deliver the report */
		if( $orderitem->email_id != "") {

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
				
				//write to filed
				$fp = fopen( sprintf ( "%s/%d.bundle.pdf", SPOOLPATH, $orderitem->order_id ), 'r');  //make sure the directory markdown.md is in and the result.pdf will go to has proper permissions
				fwrite($fp, $result);
				fclose($fp);
				curl_close($ch);
				
				//echo '$fp is resource = ' . (is_resource($fp) ? 'true': 'false');
				
				//close connection
			
				
				$orderitem->order_status = $states['closed'];
				$orderitem->save ();
				/* update transaction */
				$transaction->orderId = $orderitem->order_id;
				$transaction->state = $orderitem->order_status;
				$transaction->timestamp = strftime("%Y-%m-%d %H:%M:%S");
				$transaction->save();
					
				echo "<pre>DATA IS PUSHED TO R & R Server</pre>";
			} catch (Exception $Ex) {
				echo "<pre>DATA IS NOT PUSHED TO R & R Server::: ".$Ex->getMessage()."</pre>";
				die;
			}
		} /* mail delivery context */
		DeleteUnwantedFile(SPOOLPATH, $orderitem->order_id);
	} /* end of foreach orderitem */

	/* GC */
	unset ( $pdi );
	unset ( $book );
	unset ( $report );
}

echo "Framework is Closed";

function GetZoneValues($hData) {
	$bSummerTimeZone    = $hData->summerref;
	$bTimeZone          = $hData->zoneref;

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
	$bTimeZone = $FinalZone;
	if( floatval( $hData->zoneref ) < 0) {
		$bTimeZone = number_format(floatval((-1 * $FinalZone)), 2);
	}
	return $bTimeZone;
}



function DeleteUnwantedFile($SPOOLPATH, $OrderNumber) {
	#complete serverpath must be given like
	#example "/apache/htdocs/myfile.pdf" ( not "http:xyz.com/myfile.pdf" )

	# delete file if exists
	$DeleteReportFile = sprintf("%s/%d.report.pdf", $SPOOLPATH, $OrderNumber);

	if (file_exists($DeleteReportFile))   {
		@unlink ($DeleteReportFile);
	}

}
?>