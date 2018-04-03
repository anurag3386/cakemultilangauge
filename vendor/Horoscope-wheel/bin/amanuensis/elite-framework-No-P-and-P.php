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

ini_set("display_errors", 1);
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
error_reporting ( E_ALL );

date_default_timezone_set ( 'America/Los_Angeles' );

echo "InSide eLite Customer Framework is Start";

/* path definitions */
if (!defined ( 'ROOTPATH' )) {
    define('ROOTPATH', '/home/astrowow/public_html/');
}

define ( 'CLASSPATH', ROOTPATH . '/classes' );
define ( 'LIBPATH', ROOTPATH . '/lib' );
define ( 'SPOOLPATH', ROOTPATH . '/var/spool' );

/* font information */
define ( 'FPDF_FONTPATH', LIBPATH . '/fpdf/fonts/' );
require_once (LIBPATH . '/fpdf/fpdf.php');
require_once (LIBPATH . '/fpdi/fpdi.php');

require_once (CLASSPATH . '/collate/class.collate.php');
require_once (CLASSPATH . '/collate/class.collate.de.php');
require_once (CLASSPATH . '/collate/class.collate.dk.php');
require_once (CLASSPATH . '/collate/class.collate.en.php');
require_once (CLASSPATH . '/collate/class.collate.se.php');
require_once (CLASSPATH . '/collate/class.collate.no.php'); 			
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

//Its Added for PDO to support PDO			(14-Nov-2013 By Amit Parmar)
require_once (CLASSPATH . '/PDO.config.inc.php');

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
require_once (CLASSPATH . '/wow/birthanalysis.php'); 		// deprecate
require_once (CLASSPATH . '/wow/dynamicanalysis.php'); 		// deprecate
require_once (CLASSPATH . '/wow/report.php'); 			// rewrite for XML
require_once (CLASSPATH . '/wow/report.pdf.generic.php');
require_once (CLASSPATH . '/wow/generator/default/report.pdf.rc1.php');

/* WOW Calendar/Seasonal reports */
require_once (CLASSPATH . '/wow/forecast.php');
require_once (CLASSPATH . '/wow/generator/default/forecast.pdf.php');

/* WOW Wheel */
/* Developer Note - these are temporary until the issues are iron out */
require_once (ROOTPATH . '/bin/wheel/class.report.pdf.wheel.rc1.php');
require_once (ROOTPATH . '/bin/wheel/class.report.pdf.wheel.wow.php');

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
$logger->debug ( "Amanuensis::Framework - build table of states" );
$state = new State ();
$states = array ();
$statelist = $state->GetList ( array (array ('stateId', '>', 0 ) ) );
foreach ( $statelist as $item ) {
    $states [$item->name] = $item->stateId;
}

//NOW Fetching the All active eLite Customer From DB
//$SQLQuery = "SELECT mp.membership_to_id, mp.userid, mp.portalid FROM `membership_to_portal` mp, `membership_orders` mo WHERE mp.userid = mp.userid AND mp.portalid != 148 AND mo.order_status IN (9,12) AND membership_enddate >= :MembershipEndDate";
$SQLQuery = "SELECT mp.membership_to_id, mp.userid, mp.portalid FROM `membership_to_portal` mp, `membership_orders` mo "; 
$SQLQuery .= " WHERE mp.portalid > 148 AND mp.userid = mp.userid AND mo.order_status IN (9,12) AND membership_enddate >= :MembershipEndDate";
$queryParams = array(':MembershipEndDate' => date('Y-m-d'));

$objDB  = $db->prepare($SQLQuery);
$objDBResult = $objDB->execute($queryParams);
$eLiteActiveRow = $objDB->fetchAll();

if($eLiteActiveRow) {
	
	foreach ($eLiteActiveRow as $CurrentRow) {
		
		/* look for queued orders */
		$logger->debug ( "Amanuensis::Framework - look for queued orders" );
		$order = new Order ();
		
		$orderqueue = $order->GetList ( array(
		                array ('order_status', '=', 12 ),                                       		/* Queued */
		                array ('portalid', '=', intval ( $CurrentRow['portalid']) ),         /* Portal ID */
						array ('user_id', '=', intval ( $CurrentRow['userid']) ),         /* User ID */
		                array ('delivery_option', '=', 1),                                       		/* Only Email Version */
		                array ('product_item_id', 'IN', "(13, 17)"),                            		/* Personal/full/pc3 */
		            ) );
		
		/* only process orders in the queued state */		
		$languageCodes = array(
		        'english' => 'en',
		        'danish' => 'dk',
				'dansk' => 'dk',
		        'swedish' => 'se',
		        'spanish' => 'sp');
		
		$ReportsDefaultLanguage = 'en';
		
		if (count ( $orderqueue ) > 0) {
			$eLiteUserID = $CurrentRow['userid'];
			$eLitePortalID = $CurrentRow['portalid'];
			
			//NOW Fetching the Portal Setting for Header and Footer
			/*
			$PortalName = 'Adrian';
			$PortalURL = 'www.world-of-wisdom.com';
			
			$SQLQuery = "SELECT portalname, portalurl FROM `elite_user_portal_settings` WHERE userid = :UserId AND portalid = :PortalId";
			$queryParams = array(':UserId' => $CurrentRow['userid'], ':PortalId' => $CurrentRow['portalid']);
			
			$objDB  = $db->prepare($SQLQuery);
			$objDBResult = $objDB->execute($queryParams);
			$eLitePortalRow = $objDB->fetchAll();

			if($eLitePortalRow) {			
				foreach ($eLitePortalRow as $CurrentPortalRow) {
					$PortalName = $CurrentPortalRow['portalname'];
					$PortalURL =  $CurrentPortalRow['portalurl'];
				}
			}
			*/
			//NOW Fetching the Portal Setting for Header and Footer
			
		    $logger->debug ( 'Amanuensis::Framework - order queue has ' . count ( $orderqueue ) . ' orders' );
		
		    $birthdata = new BirthData ();
		    $reportoption = new ReportOption ();
		    $product = new Product ();
		    $productDescriptionObj = new product_description();
		
		    // is this still required?
		    // where is this used
		    $planets = array ('Sun', 'Moon', 'Mercury', 'Venus', 'Mars', 'Jupiter', 'Saturn', 'Uranus', 'Neptune', 'Pluto', 'NNode', 'SNode', 'Ascendant', 'MC', 'IC', 'Descendant' );
		
		    /* cycle through queued orders */
		    foreach ( $orderqueue as $orderitem ) {
		        $logger->debug ( 'Amanuensis::Framework - starting to process order' );
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
		        $logger->debug ( 'Amanuensis::Framework - setting order state - processing' );
		        $orderitem->order_status = $states ['processing'];
		        $orderitem->save ();
		        
		        echo "[==ORDER NO ::". $orderitem->order_id." ==]";
		        
		        /* update transactions */
		        $logger->debug ( 'Amanuensis::Framework - creating new transaction - processing' );
		        $transaction = new Transaction ();
		        $transaction->orderId = $orderitem->order_id;
		        $transaction->state = $orderitem->order_status;
		        $transaction->timestamp = strftime ( "%Y-%m-%d %H:%M:%S" );
		        $transaction->save ();
		
		        $productDescription = $productDescriptionObj->GetProductById($orderitem->product_item_id);
		
		        /* look for the birth data component */
		        $logger->debug ( 'Amanuensis::Framework - processing birthdata' );
		        $birthdatalist = $birthdata->GetList ( array ( array ('orderId', '=', $orderitem->order_id ) ) );
		
		        /* there should be exactly 1 result */
		        if (count ( $birthdatalist ) != 1) {
		            $logger->debug ( 'Amanuensis::Framework - multiple or no birthdata found' );
		        } else {
		            $logger->debug ( 'Amanuensis::Framework - birthdata found' );
		        }
		
		        foreach ( $birthdatalist as $birthentry ) {            
		            $formattedDate = sprintf ( "%04d%02d%02d", $birthentry->year, $birthentry->month, $birthentry->day );
		            $logger->debug ( 'Amanuensis::Framework - formatted date = ' . $formattedDate );
		
		            $formattedTime = sprintf ( "%02d:%02d", $birthentry->hour, $birthentry->minute );
		            $logger->debug ( 'Amanuensis::Framework - formatted time = ' . $formattedTime );
		
		            /**
		             * added for solar chart
		             */
		            $timed_data = ((trim ( $birthentry->untimed ) == '1') ? false : true);
		            $logger->debug ( "Amanuensis::Framework - timed data = [" . trim ( $birthentry->untimed ) . "]" );
		            echo "<pre>== Is UnTimede :: " . trim ( $birthentry->untimed ) ." == </pre>";
		            echo "<pre>== zoneref :: " . trim ( $birthentry->zoneref ) ." ==  </pre>";
		            echo "<pre>== [ CITY :: " . $birthentry->place . "] ==  </pre>";
		
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
		            echo "<pre> birthentry->longitude " . $birthentry->longitude ." </pre>";
		            echo "<pre> birthentry->latitude " . $birthentry->latitude ." </pre>";
		            echo "<pre> CalLong : $CalLong </pre>";
		            echo "<pre> CalLat : $CalLat </pre>";
		
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
		
		        $logger->debug ( 'Amanuensis::Framework - processing report options' );
		        $logger->debug ( 'Amanuensis::Framework - creating PDF generator for default case ( A4 size )' );
		        $generator = new PDFGenerator ( 'A4' );
		        
		        $generator->language = $ReportsDefaultLanguage;
		        $logger->debug ( 'Amanuensis::Framework - generator language set to ' . $ReportsDefaultLanguage );
		        switch (strtoupper ( $generator->language )) {
		            case 'ENGLISH' :
		            case 'EN' :
		                $logger->debug ( 'Amanuensis::Framework - generator using English Book' );
		                $book = new BookUK ();
		                $shorttermtrend = new ShortTermTrend ();
		                break;
		            case 'DANISH' :
		            case 'DK' :
		                $logger->debug ( 'Amanuensis::Framework - generator using Danish Book' );
		                $book = new BookDK ();
		                $shorttermtrend = new ShortTermTrend_DK ();
		                break;
		            case 'DUTCH' :
		            case 'DU' :
		                $logger->debug ( 'Amanuensis::Framework - generator using Dutch Book' );
		                $book = new BookDU ();
		                $shorttermtrend = new ShortTermTrend (); /* use UK trends until translation exists */
		                break;
		            case 'DE' :
		                $logger->debug ( 'Amanuensis::Framework - generator using German Book' );
		                $book = new BookGE ();
		                $shorttermtrend = new ShortTermTrend (); /* use UK trends until translation exists */
		                break;
		            case 'GREEK' :
		            case 'GR' :
		                $logger->debug ( 'Amanuensis::Framework - generator using Greek Book' );
		                $book = new BookGR ();
		                $shorttermtrend = new ShortTermTrend (); /* use UK trends until translation exists */
		                break;
		            case 'NO' :
		                $logger->debug ( 'Amanuensis::Framework - generator using Norwegian Book' );
		                $book = new BookNO ();
		                $shorttermtrend = new ShortTermTrend (); /* use UK trends until translation exists */
		                break;
		            case 'SPANISH' :
		            case 'SP' :
		                $logger->debug ( 'Amanuensis::Framework - generator using Spanish Book' );
		                $book = new BookSP ();
		                $shorttermtrend = new ShortTermTrend (); /* use UK trends until translation exists */
		                break;
		            case 'SWEDISH' :
		            case 'SE' :
		                $logger->debug ( 'Amanuensis::Framework - generator using Swedish Book' );
		                $book = new BookSW ();
		                $shorttermtrend = new ShortTermTrend (); /* use UK trends until translation exists */
		                break;
		            default :
		                $logger->error ( 'Failed to determine which Book to use' );
		                /* update state */
		                $logger->debug ( 'Amanuensis::Framework - setting order state - orphaned' );
		                $orderitem->order_status = $states ['orphaned'];
		                $orderitem->save ();
		
		                /* update transactions */
		                $logger->debug ( 'Amanuensis::Framework - creating new transaction - orphaned' );
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
		        switch ($orderitem->product_item_id) {
		            case 13 :                                   //Personal Report
		                $report = new Report ();
		                //$report->type = $productDescription->productName;
		                $report->type = 'pc3';
		                $logger->debug ( 'Amanuensis::Framework - report type = ' . $report->type );
		                /* start the report at the beginning of next month */
		//                $start_month = intval ( substr ( $birthentry->start_date, 6, 2 ) );
		//                $start_year = intval ( substr ( $birthentry->start_date, 0, 4 ) );
		                $start_month = intval ( substr ( $StartDate, 6, 2 ) );
		                $start_year = intval ( substr ( $StartDate, 0, 4 ) );
		
		                //$start_month == 11 added by Amit Parmar (21-Nov-2011)
		                $logger->debug ( 'Amanuensis::Framework -ANP $start_year - $start_month = ' . $start_year . ' - ' . $start_month );
		                if ($start_month == 11) {
		                    $start_month = 1;
		                    $start_year ++;
		                }
		                if ($start_month == 12) {
		                    $start_month = 1;
		                    $start_year ++;
		                }
		
		                $logger->debug ( 'Amanuensis::Framework -ANP $start_year - $start_month = ' . $start_year . ' - ' . $start_month );
		
		                $start_date = sprintf ( '%04d-%02d-01 00:00:00', $start_year, $start_month );
		
		                $logger->debug ( 'Amanuensis::Framework -ANP $start_date = ' . $start_date );
		                $analysis_context = new DynamicAnalysis(
		                                            $report->type,              /* report type	 */
		                                            $data,                      /* birth data	 */
		                                            $start_date,                /* start date	 */
		                                            $birthentry->duration,      /* duration	 */
		                                            false                       /* debug	 */ );
		                $report->run($analysis_context,                         /* report requires analysis data */
		                             $book,                                     /* report requires content */
		                             $generator );                              /* report requires a generator */
		                $report->generate->pdf->Output ( sprintf ( "%s/%d.report.pdf", SPOOLPATH, $orderitem->order_id ) );
		                $report->generate->pdf->Close ();
		                break;
		            case 17 :                       //Seasonal Report for 3 Months with Questions and Anwser
		                echo $orderitem->product_item_id . " Product Item Id In condition<br />";
		                $report = new Forecast ();
		                $generator = new PDF_Forecast ();
		                $logger->debug ( "Amanuensis::Framework - generator for seasonal report" );                
		                $report->run($data,                             /* birth data	 */
		                            $StartDate,                         /* start date	 */
		                            $birthentry->duration,              /* duration	 */
		                            true,                               /* seasonal	 */
		                            $ReportsDefaultLanguage,        /* language      */
		                            $generator );                       /* generator	 */
		                $report->generator->pdf->Output ( sprintf ( "%s/%d.report.pdf", SPOOLPATH, $orderitem->order_id ) );
		                $report->generator->pdf->Close ();
		                break;
		            default:
		            /* update state */
		                $logger->debug ( 'Amanuensis::Framework - setting order state - orphaned' );
		                $orderitem->order_status = $states ['orphaned'];
		                $orderitem->save ();
		
		                /* update transactions */
		                $logger->debug ( 'Amanuensis::Framework - creating new transaction - orphaned' );
		                $transaction = new Transaction ();
		                $transaction->orderId = $orderitem->order_id;
		                $transaction->state = $orderitem->order_status;
		                $transaction->timestamp = strftime ( "%Y-%m-%d %H:%M:%S" );
		                $transaction->save ();
		                die ( "failed to find report type" );
		                break;
		        }
		        
		        /*
		         * This is using the old code for now until the issues are further investigated
		        */
		        $logger->debug ( 'Amanuensis::Framework - processing wheel' );
		        $wheel = new PDF_Wheel_WOW ();
		        $wheel->language = $ReportsDefaultLanguage;
		
		        $wheel->Open ();
		        $wheel->AddFont ( 'wows', '', 'wows.php' ); // text
		        $wheel->AddFont ( 'ww_rv1', '', 'ww_rv1.php' ); // graphics
		        $wheel->SetDisplayMode ( 'fullpage' );
		        $wheel->SetAutoPageBreak ( false );
		        
		        $chart = new AstrologChartAPI ( $data );
		
		        /* NOTE - this is repetition, take from previous context */
		        /* get the planetary context - longitude and house occupancy */
		        for($planet = 0/* Sun */; $planet < 12/* S.Node */; $planet ++) {
		            $wheel->planet_longitude [$planet] = $chart->m_object [$planets [$planet]] ['longitude'];
		            $wheel->planet_in_house [$planet] = $chart->m_object [$planets [$planet]] ['house'];
		            $wheel->planet_retrograde [$planet] = $chart->m_object [$planets [$planet]] ['retrograde'];
		        }
		
		        /* now go for the aspects */
		        for($asp = 0; $asp < count ( $chart->m_aspect ); $asp ++) {
		            array_push ( $wheel->planet_aspects, $chart->m_aspect [$asp] );
		            $logger->debug ( $chart->m_aspect [$asp] );
		        }
		
		        for($house = 0; $house < 12; $house ++) {
		            $wheel->house_cusp_longitude [$house] = $chart->m_object ['cusp'] [$house + 1];
		        }
		
		        if ($timed_data === true) {
		            $birthdate = sprintf ( "%02d/%02d/%04d %02d:%02d", $birthentry->day, $birthentry->month, $birthentry->year, $birthentry->hour, $birthentry->minute );
		        } else {
		            $birthdate = sprintf ( "%02d/%02d/%04d", $birthentry->day, $birthentry->month, $birthentry->year );
		        }
		        echo "<pre>AstrologChartAPI()  Creating Chart Row Material</pre>";
		
		        /* get place information */
		        $acsatlas = new ACSAtlas ();
		        $birthplace = '';
		        $birthregion = '';
		        $FullPlaceName = '';
		        
		        //$placeList = $acsatlas->GetList( array(array ('latitude', '=', $Lat), array ('longitude', '=', $Long )));
		        $placeList = $acsatlas->ExecuteCustomQuery( " lower(placename) = '" . strtolower($birthentry->place) ."' AND lkey like '". $birthentry->state . "%'");
		        
		        if (count ( $placeList ) > 0) {
		            foreach($placeList  as $pItem){                
		                $fullbirthplace = explode ( ">", $pItem->placename );
		                if (count ( $fullbirthplace ) > 0) {
		                    $birthplace = trim ( $fullbirthplace [0] );
		                } else {
		                    $birthplace = trim( $pItem->placename );
		                }
		                $birthregion = trim( $pItem->region );
		                if(isset ($birthregion)){
		                    $FullPlaceName =  sprintf("%s, %s", $birthplace, $birthregion );
		                }
		                else {
		                    $FullPlaceName =  $birthplace;
		                }
		            }
		        }        
		
		        /* get state information */
		        $acsstatelist = new ACSStatelist ();
		        //$birthcountry = $acsstatelist->getStateNameByAbbrev ( substr ( $acsatlas->lkey, 0, 2 ) );
		        $birthcountry = $acsstatelist->getStateNameByAbbrev ( $birthentry->state );
		                
		        //$coords = sprintf ( "%2d%s%02d %3d%s%02d", abs ( intval ( $birthentry->latitude ) ), (($birthentry->latitude >= 0) ? 'N' : 'S'), abs ( intval ( (($birthentry->latitude - intval ( $birthentry->latitude )) * 60) ) ), abs ( intval ( $birthentry->longitude ) ), (($birthentry->longitude >= 0) ? 'W' : 'E'), abs ( intval ( (($birthentry->longitude - intval ( $birthentry->longitude )) * 60) ) ) );
		        $coords = sprintf ( "%2d%s%02d %3d%s%02d", abs ( intval ( $CalLat ) ), (($CalLat >= 0) ? 'N' : 'S'), abs ( intval ( (($CalLat - intval ( $CalLat )) * 60) ) ),
		                            abs ( intval ( $CalLong ) ), (($CalLong >= 0) ? 'W' : 'E'), abs ( intval ( (($CalLong - intval ( $CalLong )) * 60) ) ) );
		
		        /* daylight savings offset */
		        $timediff = number_format(floatval( abs( $birthentry->summerref ) ), 2);
		        $timediff = str_replace('.', ':', $timediff);
		        /* timezone offset */
		        $timedelta =  number_format( abs( $birthentry->zoneref ) , 2);
		        $timedelta = str_replace('.', ':', $timedelta);
		
		        $wheel->wheel_offset = $wheel->house_cusp_longitude [0];
		
		        $wheel->table_user_info_fname = sprintf("%s %s", trim ( $birthentry->first_name ), trim ( $birthentry->last_name ));
		        $wheel->table_user_info_lname = '';
		        $wheel->table_user_info_birth_weekday = $wheel_top_weekdays [strtolower ( $ReportsDefaultLanguage )] [JDDayOfWeek ( cal_to_jd ( CAL_GREGORIAN, $birthentry->month, $birthentry->day, $birthentry->year ), 1 )];
		        $wheel->table_user_info_birth_date = $birthdate;
		        $wheel->table_user_info_birth_place = trim ( $birthplace );
		        //$wheel->table_user_info_birth_place = trim ( $birthentry->place );
		        $wheel->table_user_info_birth_state = $FullPlaceName;
		        $wheel->table_user_info_birth_coords = $coords;
		        $wheel->table_user_info_birth_timezone = $timedelta;
		        $wheel->table_user_info_birth_summertime = $timediff;
		        $wheel->table_user_info_house_system = (($timed_data === true) ? 'Placidus' : 'Solar');
		        $wheel->table_user_info_orb_system = $wheel_top_orbsensitivity [strtolower ( $ReportsDefaultLanguage )];
		        echo "<pre>Horoscope Wheel Setting</pre>";
		        echo "<pre>== Place  :: " . trim ( $birthplace ) . " -- ". $birthentry->place . " == </pre>";
		        echo "<pre>== Country :: " . trim ( $birthcountry ) . " == </pre>";
		        $wheel->generateChartWheelPage ();
		        $wheel->Output ( sprintf ( "%s/%d.wheel.pdf", SPOOLPATH, $orderitem->order_id ) );
		        
		        /*
		         * *********************************************
		         *
		         * collate the various sections of the report
		         * Review:
		         * - replace World of Wisdom with portal name
		         * - vary report title for static reports
		         * - move report title to language files
		         * - add current season to seasonal title
		         *
		         * *********************************************
		        */
		        $logger->debug ( 'Amanuensis::Framework - processing assembly' );
		        $generator->language = $ReportsDefaultLanguage;
		        
		        switch (strtoupper ( $ReportsDefaultLanguage )) {
		        	case 'ENGLISH' :
		        	case 'EN' :
// 		        		require_once (CLASSPATH . '/collate/class.collate.wow.en.php');
// 		        		$pdi = new WOW_Col8_FPDI_EN ();
		        		require_once (CLASSPATH . '/collate/class.collate.wow.elite.en.php');
		        		$pdi = new WOW_eLite_Col8_FPDI_EN ();
		        		break;
		        	case 'SWEDISH' :
		        	case 'SE' :
		        		//require_once (CLASSPATH . '/collate/class.collate.wow.se.php');
		        		require_once (CLASSPATH . '/collate/class.collate.wow.elite.se.php');
		        		$pdi = new WOW_eLite_Col8_FPDI_SE ();
		        		break;
		        	case 'DE' :
		        		//German Language
		        		require_once (CLASSPATH . '/collate/class.collate.wow.elite.de.php');
		        		$pdi = new WOW_eLite_Col8_FPDI_DE ();
		        		break;
		        	case 'DANISH' :
		        	case 'DK' :
		        		require_once (CLASSPATH . '/collate/class.collate.wow.elite.dk.php');
		        		$pdi = new WOW_eLite_Col8_FPDI_DK ();
		        		break;
		        	default:
		        		require_once (CLASSPATH . '/collate/class.collate.wow.elite.en.php');
		        		$pdi = new WOW_eLite_Col8_FPDI_EN ();
		        		break;
		        }        
		        
		        $pdi->report_addressee =  sprintf("%s %s", trim ( $birthentry->first_name ),  trim ( $birthentry->last_name ));
		        $pdi->cover_birthdate = trim ( $birthdate );
		        $pdi->cover_birthplace = trim ( $birthplace );
		        $pdi->cover_birthcountry = trim ( $birthcountry );
		
		        switch ( $orderitem->product_item_id ) {
		            case 13 :   // PERSONAL REPORT
		                $start_month = intval ( substr ( $StartDate, 6, 2 ) );
		                $start_year = intval ( substr ( $StartDate, 0, 4 ) );
		
		                if ($start_month == 12 || $start_month == 11) {
		                    $start_month = 1;
		                    $start_year ++;
		                }
		                $date_start = sprintf ( '01-%02d-%04d', $start_month, $start_year );
		                $date_end = sprintf ( '01-%02d-%04d', $start_month, ($start_year + $birthentry->duration) );
		                $title = "Birth Analysis + Dynamic Analysis ($date_start - $date_end)";
		                $pdi->table_of_contents = $report->generate->toc;
		                
		                $pdi->introduction_context = 'personal';
		                break;
		            case 17 :   // SEASONAL REPORT
		                $title = $productDescription->productName;
		                switch (strtoupper($ReportsDefaultLanguage)) {
		                    case 'EN':
		                        $title = "Seasonal Report";
		                        $pdi->cover_heading = utf8_decode("Seasonal Report for");
		                        break;
		                    case 'DK':
		                        $title = utf8_decode("ASTROKALENDER 3-MÅNEDERS RAPPORT for");
		                        $pdi->cover_heading = utf8_decode("3-Måneders rapport  for");
		                        break;
		                    case 'SE':
		                        $title = utf8_decode("TREMÅNADERSHOROSKOP för");
		                        break;
		                    default:
		                        $title = "Seasonal Report";
		                        break;
		                }                
		                $pdi->introduction_context = 'season';
		
		                break;
		            default :
		                break;
		        }
		
		        /* footer stuff */
		        $pdi->report_title = $title;
		        $pdi->orderId = $orderitem->order_id;		               
		
		        $pdi->Assemble ();
		        $pdi->Output ( sprintf ( "%s/%d.bundle.pdf", SPOOLPATH, $orderitem->order_id ) );        
		        $pdi->closeParsers ();
		
		        //GENERATING THE PREVIEW
		        $previewGengerator = new PreviewGenerator();
		        $previewGengerator->GeneratePreview($orderitem->product_item_id, sprintf ( "%s/%d.bundle.pdf", SPOOLPATH, $orderitem->order_id ));
		        $previewGengerator->Output ( sprintf ( "%s/%d.preview_bundle.pdf", SPOOLPATH, $orderitem->order_id ) );
		        $previewGengerator->closeParsers ();
		        //GENERATING THE PREVIEW
		
		        /* update state */
		        $logger->debug ( 'Amanuensis::Framework - setting order state - ready' );
		        $orderitem->order_status = $states ['ready'];
		        $orderitem->save ();
		
		        /* update transaction */
		        $logger->debug ( sprintf ( "Amanuensis::Framework - creating transaction, order state = %d", $orderitem->order_status ) );
		        $transaction->state = $orderitem->order_status;
		        $logger->debug ( sprintf ( "Amanuensis::Framework - creating transaction, timestamp = %s", strftime ( "%Y-%m-%d %H:%M:%S" ) ) );
		        $transaction->timestamp = strftime ( "%Y-%m-%d %H:%M:%S" );
		        $logger->debug ( 'Amanuensis::Framework - creating transaction, savenew' );
		        $transaction->savenew ();
		        $logger->debug ( 'Amanuensis::Framework - creating transaction, done' );
		                
		        /* if delivery method is email then deliver the report */
		        $logger->debug ( 'Amanuensis::Framework - checking delivery option' );
		
		        echo " [ === Delivery Option : " .$orderitem->delivery_option. '=== ]\\n<br />  ';
		               
		
		        if ($orderitem->delivery_option == 1) {                 /* email */
		            $logger->debug ( 'Amanuensis::Framework - processing mail delivery' );
		            echo " [ === Portal Id : " .$orderitem->portalid. '=== ]\\n<br />  ';
		
		            switch (strtoupper ( $ReportsDefaultLanguage )) {
		            	case 'ENGLISH' :
		            	case 'EN' :
		            		$logger->debug ( 'Amanuensis::Framework - processing mail delivery for WOW(UK)' );
		                    $email = new WowEmailDelivery_EN ();
		                    break;
		            	case 'SWEDISH' :
		            	case 'SE' :
		            		$logger->debug ( 'Amanuensis::Framework - processing mail delivery for WOW(SE)' );
		                    $email = new WowEmailDelivery_SE ();
		                    break;
		            	case 'DE' :
		            		//German Language
		            		$logger->debug ( 'Amanuensis::Framework - processing mail delivery for WOW(DK)' );
		                    $email = new WowEmailDelivery_DK ();
		                    break;
		            	case 'DANISH' :
		            	case 'DK' :
		            		$logger->debug ( 'Amanuensis::Framework - processing mail delivery for WOW(DK)' );
		                    $email = new WowEmailDelivery_DK ();
		                    break;
		            	default:
		            		$pdi = new WOW_Col8_FPDI_EN ();
		            		break;
		            }            
		            echo " [ === Product Type : " .$orderitem->product_type. '=== ]\\n<br />  ';
		            
		            //FOR PREVIEW REPORT
		            if($orderitem->product_type == 4) {
		                $email->isPreview = true;
		            }
		            else {
		                $email->isPreview = false;
		            }
		            
		            //FOR PREVIEW REPORT
		            $email->setOrderId ( $orderitem->order_id );
		            $email->send ();
		            
		            echo "<pre>Email is Done</pre>";
		        } else {
		            $logger->debug ( 'Amanuensis::Framework - no processing mail delivery required' );
		        } /* mail delivery context */
		    } /* end of foreach orderitem */
		
		    /* GC */
		    unset ( $pdi );
		    unset ( $book );
		    unset ( $report );
		} else {
		    // $logger->debug('Amanuensis::Framework - order queue is empty');
		}
	}
}

//NOW REALESE MEMORIES
unset($objDB);
unset($objDBResult);
unset($eLiteActiveRow);
//NOW REALESE MEMORIES

echo "eLite Customer Framework is Closed";

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
?>