<?php
/*
 * Script: amanuensis.framework.php
 * Author: Amit Parmar <parmaramit1111@gmail.com>
 *
 * Description
 *
 * Requires
 * - affiliate_id
 * - affiliate collate path
 * - affiliate logger config xml path
 * - affiliate logger tag
 *
*/

if (isset ( $debug_error_reporting ) && $debug_error_reporting === true) {
    error_reporting ( E_ALL );
} else {
    error_reporting ( 0 );
}
ini_set("display_errors", 1);
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);

date_default_timezone_set ( 'America/Los_Angeles' );

/* path definitions */
if (! defined ( 'ROOTPATH' )) {
    //define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . 'astrowow');
    //define('ROOTPATH', '/var/www/vhosts/world-of-wisdom.com/astrowow.com/');
    define('ROOTPATH', '/home/astrowow/public_html/');
}
define ( 'CLASSPATH', ROOTPATH . '/classes' );
define ( 'LIBPATH', ROOTPATH . '/lib' );
define ( 'SPOOLPATH', ROOTPATH . '/var/spool' );

echo "<pre>ROOTPATH : ".ROOTPATH."</pre>";
echo "<pre>CLASSPATH : ".CLASSPATH."</pre>";
echo "<pre>SPOOLPATH : ".SPOOLPATH."</pre>";

/* font information */
define ( 'FPDF_FONTPATH', LIBPATH . '/fpdf/fonts/' );
require_once (LIBPATH . '/fpdf/fpdf.php');
require_once (LIBPATH . '/fpdi/fpdi.php');

require_once (CLASSPATH . '/collate/class.collate.php');
require_once (CLASSPATH . '/collate/class.collate.de.php');
require_once (CLASSPATH . '/collate/class.collate.dk.php');
require_once (CLASSPATH . '/collate/class.collate.en.php');
require_once (CLASSPATH . '/collate/class.collate.se.php');
require_once (CLASSPATH . '/collate/class.collate.no.php'); /* Added on 18-Nov-2011 By Amit Parmar */
require_once (CLASSPATH . '/collate/' . $affiliate_collate_path);

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
/* look for queued orders */
$logger->debug ( "Amanuensis::Framework - look for queued orders" );
$order = new Order ();
$orderqueue = $order->GetList ( array(
                array ('order_status', '=', 3 ),                                       /* Queued */
                //array ('order_status', '<=', 8 ),                                       /* Queued */
                array ('portalid', '=', intval ( $affiliate_id ) ),                     /* Portal ID */
                //array ('product_item_id', '=', 13 ),                                    /* Personal/full/pc3 */
                //array ('product_item_id', '=', 7 ),                                   /* Seasonal/trends with Q&A */
                array ('product_item_id', '=', 17 )                                   /* Calendar */
            ) );

/* only process orders in the queued state */
if (count ( $orderqueue ) > 0) {
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

        /* update state = processing */
        $logger->debug ( 'Amanuensis::Framework - setting order state - processing' );
        $orderitem->order_status = $states ['processing'];
        $orderitem->save ();

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
            $timed_data = ((trim ( $birthentry->untimed ) == 'N') ? true : false);
            $logger->debug ( "Amanuensis::Framework - timed data = [" . trim ( $birthentry->untimed ) . "]" );

            $data = new AstrologBirthData(  $formattedDate,             /* date	 */
                                            $formattedTime,             /* time	 */
                                            $timed_data,                /* timed data  */
                                            $birthentry->summerref,     /* summertime	 */
                                            $birthentry->zoneref,       /* timezone	 */
                                            $birthentry->longitude,     /* longitude	 */
                                            $birthentry->latitude );    /* latitude	 */
        } /* birthdata */

        $logger->debug ( 'Amanuensis::Framework - processing report options' );
        $logger->debug ( 'Amanuensis::Framework - creating PDF generator for default case ( A4 size )' );
        $generator = new PDFGenerator ( 'A4' );

        $generator->language = $orderitem->language_code;
        $logger->debug ( 'Amanuensis::Framework - generator language set to ' . $generator->language );
        switch (strtoupper ( $generator->language )) {
            case 'EN' :
                $logger->debug ( 'Amanuensis::Framework - generator using English Book' );
                $book = new BookUK ();
                $shorttermtrend = new ShortTermTrend ();
                break;
            case 'DK' :
                $logger->debug ( 'Amanuensis::Framework - generator using Danish Book' );
                $book = new BookDK ();
                $shorttermtrend = new ShortTermTrend_DK ();
                break;
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
            case 'SP' :
                $logger->debug ( 'Amanuensis::Framework - generator using Spanish Book' );
                $book = new BookSP ();
                $shorttermtrend = new ShortTermTrend (); /* use UK trends until translation exists */
                break;
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
                $start_month = intval ( substr ( $birthentry->start_date, 6, 2 ) );
                $start_year = intval ( substr ( $birthentry->start_date, 0, 4 ) );

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
                $report = new Forecast ();
                $generator = new PDF_Forecast ();
                $logger->debug ( "Amanuensis::Framework - generator for seasonal report" );
                $report->run($data,                             /* birth data	 */
                            $birthentry->start_date,            /* start date	 */
                            $birthentry->duration,              /* duration	 */
                            true,                               /* seasonal	 */
                            $reportoptionitem->language,        /* language      */
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
        $wheel->language = $orderitem->language_code;

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

        /* get place information */
        $acsatlas = new ACSAtlas ();
        $acsatlas->get ( $birthentry->place );

        //$fullbirthplace = split(">", $acsatlas->placename);
        $fullbirthplace = explode ( ">", $acsatlas->placename );

        if (count ( $fullbirthplace ) > 0) {
            //$birthplace = $acsatlas->placename;
            $birthplace = trim ( $fullbirthplace [0] );
        } else {
            $birthplace = $acsatlas->placename;
        }

        /* get state information */
        $acsstatelist = new ACSStatelist ();
        //$birthcountry = $acsstatelist->getStateNameByAbbrev ( substr ( $acsatlas->lkey, 0, 2 ) );
        $birthcountry = $acsstatelist->getStateNameByAbbrev ( $birthentry->state );

        $coords = sprintf ( "%2d%s%02d %3d%s%02d", abs ( intval ( $birthentry->latitude ) ), (($birthentry->latitude >= 0) ? 'N' : 'S'), abs ( intval ( (($birthentry->latitude - intval ( $birthentry->latitude )) * 60) ) ), abs ( intval ( $birthentry->longitude ) ), (($birthentry->longitude >= 0) ? 'W' : 'E'), abs ( intval ( (($birthentry->longitude - intval ( $birthentry->longitude )) * 60) ) ) );

        $timedeltaStr = sprintf ( "%04d", intval ( abs ( $birthentry->zoneref ) ) );
        $timedelta = sprintf ( "%d:%02d", intval ( substr ( $timedeltaStr, 0, 2 ) ), intval ( substr ( $timedeltaStr, 2, 2 ) ) );

        $timediffStr = sprintf ( "%04d", intval ( abs ( $birthentry->summerref ) ) );
        $timediff = sprintf ( "%d:%02d", intval ( substr ( $timediffStr, 0, 2 ) ), intval ( substr ( $timediffStr, 2, 2 ) ) );

        $wheel->wheel_offset = $wheel->house_cusp_longitude [0];

        $wheel->table_user_info_fname = trim ( $birthentry->first_name );
        $wheel->table_user_info_lname = trim ( $birthentry->last_name );
        $wheel->table_user_info_birth_weekday = $wheel_top_weekdays [strtolower ( $orderitem->language_code )] [JDDayOfWeek ( cal_to_jd ( CAL_GREGORIAN, $birthentry->month, $birthentry->day, $birthentry->year ), 1 )];
        $wheel->table_user_info_birth_date = $birthdate;
        //$wheel->table_user_info_birth_place = trim ( $birthplace );
        $wheel->table_user_info_birth_place = trim ( $birthentry->place );
        $wheel->table_user_info_birth_state = trim ( $birthcountry );
        $wheel->table_user_info_birth_coords = $coords;
        $wheel->table_user_info_birth_timezone = $timedelta;
        $wheel->table_user_info_birth_summertime = $timediff;
        $wheel->table_user_info_house_system = (($timed_data === true) ? 'Placidus' : 'Solar');
        $wheel->table_user_info_orb_system = $wheel_top_orbsensitivity [strtolower ( $orderitem->language_code )];

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
        switch ($orderitem->portalId) {
            case 0 : /* Test */
            case C_AFFILIATE_WOW :
                $pdi = new WOW_Col8_FPDI_EN ();
                break;
            case C_AFFILIATE_WOWDK :
                $pdi = new WOW_Col8_FPDI_DK ();
                break;
            case C_AFFILIATE_WOWSE :
                $pdi = new WOW_Col8_FPDI_SE ();
                break;
            case C_AFFILIATE_WOWNO :
                $pdi = new WOW_Col8_FPDI_EN ();
                break;
            case C_AFFILIATE_COURTESY :
                switch (strtoupper ( $generator->language )) {
                    case 'EN' :
                        $pdi = new Courtesy_Col8_FPDI_EN ();
                        break;
                }
                break;
            case C_AFFILIATE_TYCHOOBR :
                switch (strtoupper ( $generator->language )) {
                    case 'SE' :
                        $pdi = new Tychoo_Col8_FPDI_SE ();
                        break;
                    case 'DE' :
                        $pdi = new Tychoo_Col8_FPDI_DE ();
                        break;
                    case 'DK' :
                        $pdi = new Tychoo_Col8_FPDI_DK ();
                        break;
                }
                break;
            case C_AFFILIATE_MICHELEKNIGHT :
                $pdi = new MichelleKnight_Col8_FPDI_EN ();
                break;
            case C_AFFILIATE_STJERNEPORTALEN :
                switch (strtoupper ( $generator->language )) {
                    case 'EN' :
                        $pdi = new Stjerneportalen_Col8_FPDI_EN ();
                        break;
                    case 'DK' :
                        $pdi = new Stjerneportalen_Col8_FPDI_DK ();
                        break;
                }
                break;
            case C_AFFILIATE_ASTROCONSULTING :
                break;
            case C_AFFILIATE_DATASTAR :
                $pdi = new Datastar_Col8_FPDI_EN ();
                break;
            case C_AFFILIATE_SAMPLES :
                switch (strtoupper ( $generator->language )) {
                    case 'EN' :
                        $pdi = new Samples_Col8_FPDI_EN ();
                        break;
                }
                break;
            case C_AFFILIATE_HOROSCOPES_ARIES :
            case C_AFFILIATE_HOROSCOPES_TAURUS :
            case C_AFFILIATE_HOROSCOPES_GEMINI :
            case C_AFFILIATE_HOROSCOPES_CANCER :
            case C_AFFILIATE_HOROSCOPES_LEO :
            case C_AFFILIATE_HOROSCOPES_VIRGO :
            case C_AFFILIATE_HOROSCOPES_LIBRA :
            case C_AFFILIATE_HOROSCOPES_SCORPIO :
            case C_AFFILIATE_HOROSCOPES_SAGITTARIUS :
            case C_AFFILIATE_HOROSCOPES_CAPRICORN :
            case C_AFFILIATE_HOROSCOPES_AQUARIUS :
            case C_AFFILIATE_HOROSCOPES_PISCES :
                switch (strtoupper ( $generator->language )) {
                    case 'EN' :
                        $pdi = new SEO_Col8_FPDI_EN ( $orderitem->portalId );
                        break;
                }
                break;
            case C_AFFILIATE_ZODIA :
                $pdi = new Zodia_Col8_FPDI_EN ();
                break;
            case C_AFFILIATE_ALLER :
                $pdi = new Aller_Col8_FPDI_DK ();
                break;
            case C_AFFILIATE_PERNILLE_HOLM :
                $pdi = new PernilleHolm_Col8_FPDI_DK ();
                break;
            case C_AFFILIATE_HOROSCOPE_JUNKIE : // Added by Amit Parmar (04-Aug-2011)
                $pdi = new WOW_Col8_FPDI_EN ();
                break;
            case C_AFFILIATE_FACEBOOK : // Added by Amit Parmar (04-Aug-2011)
                $pdi = new WOW_Col8_FPDI_EN ();
                break;
            case C_AFFILIATE_GOOGLE_ADWORDS : // Added by Amit Parmar (08-Aug-2011)
                $pdi = new WOW_Col8_FPDI_EN ();
                //$pdi = new WOW_Col8_FPDI_NO();		// For Norwegian Testing - Amit Parmar (18-Nov-2011)
                break;
            case C_AFFILIATE_WELLBEING : // Added by Amit Parmar (18-Aug-2011)
                $pdi = new WOW_Col8_FPDI_EN ();
                break;
            case C_AFFILIATE_WOW_FREE : // Added by Amit Parmar (13-Sep-2011)
                $pdi = new WOW_Col8_FPDI_EN ();
                break;
            case C_AFFILIATE_HOROSCOPES_FOR_YOU : // Added by Amit Parmar (29-Sep-2011)
                $pdi = new WOW_Col8_FPDI_EN ();
                break;
            case C_AFFILIATE_ECLIPSEPSYCHICS : // Added by Amit Parmar (02-Nov-2011)
                $pdi = new WOW_Col8_FPDI_EN ();
                break;
            case C_AFFILIATE_MSN : // Added by Amit Parmar (05-Nov-2011)
                $pdi = new WOW_Col8_FPDI_DK ();
                break;
            case C_AFFILIATE_BT_DK : // Added by Amit Parmar (11-Nov-2011)
                $pdi = new BT_Col8_FPDI_DK ();
                break;
            case C_AFFILIATE_KONTURA : // Added by Amit Parmar (23-Nov-2011)
                $pdi = new Kontura_Col8_FPDI_NO ();
                //$pdi = new WOW_Col8_FPDI_NO();
                break;
            case C_AFFILIATE_ASCENDINGHEARTS : // Added by Amit Parmar (07-Dec-2011)
                $pdi = new Ascendinghearts_Col8_FPDI_EN ();
                break;
            default :
                $pdi = new WOW_Col8_FPDI_EN ();
                break;
        }

        $pdi->report_addressee =  sprintf("%s %s", trim ( $birthentry->first_name ),  trim ( $birthentry->last_name ));
        $pdi->cover_birthdate = trim ( $birthdate );
        $pdi->cover_birthplace = trim ( $birthplace );
        $pdi->cover_birthcountry = trim ( $birthcountry );

        switch ( $orderitem->product_item_id ) {
            case 13 :   // PERSONAL REPORT
                $start_month = intval ( substr ( $birthentry->start_date, 6, 2 ) );
                $start_year = intval ( substr ( $birthentry->start_date, 0, 4 ) );
                if ($start_month == 12 || $start_month == 11) {
                    $start_month = 1;
                    $start_year ++;
                }
                $date_start = sprintf ( '01-%02d-%04d', $start_month, $start_year );
                $date_end = sprintf ( '01-%02d-%04d', $start_month, ($start_year + $birthentry->duration) );
                $title = "Birth Analysis + Dynamic Analysis ($date_start - $date_end)";
                $pdi->table_of_contents = $report->generate->toc;
                break;
            case 17 :   // SEASONAL REPORT
                $title = "Calendar Report";
                $title = $productDescription->productName;
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

        //FOR PREVIEW REPORT
        if($orderitem->product_type == 4){

        }
        //FOR PREVIEW REPORT

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
        if ($orderitem->delivery_option == 1            /* email */) {
            $logger->debug ( 'Amanuensis::Framework - processing mail delivery' );
            switch ($orderitem->portalId) {
                case C_AFFILIATE_WOW :
                    $logger->debug ( 'Amanuensis::Framework - processing mail delivery for WOW(UK)' );
                    $email = new WowEmailDelivery_EN ();
                    break;
                case C_AFFILIATE_WOWDK :
                    $logger->debug ( 'Amanuensis::Framework - processing mail delivery for WOW(DK)' );
                    $email = new WowEmailDelivery_DK ();
                    break;
                case C_AFFILIATE_WOWSE :
                    $logger->debug ( 'Amanuensis::Framework - processing mail delivery for WOW(SE)' );
                    $email = new WowEmailDelivery_SE ();
                    break;
                case C_AFFILIATE_WOWNO :
                    $logger->debug ( 'Amanuensis::Framework - processing mail delivery for WOW(NO)' );
                    $email = new WowEmailDelivery_EN ();
                    break;
                case C_AFFILIATE_HOROSCOPES_ARIES :
                case C_AFFILIATE_HOROSCOPES_TAURUS :
                case C_AFFILIATE_HOROSCOPES_GEMINI :
                case C_AFFILIATE_HOROSCOPES_CANCER :
                case C_AFFILIATE_HOROSCOPES_LEO :
                case C_AFFILIATE_HOROSCOPES_VIRGO :
                case C_AFFILIATE_HOROSCOPES_LIBRA :
                case C_AFFILIATE_HOROSCOPES_SCORPIO :
                case C_AFFILIATE_HOROSCOPES_SAGITTARIUS :
                case C_AFFILIATE_HOROSCOPES_CAPRICORN :
                case C_AFFILIATE_HOROSCOPES_AQUARIUS :
                case C_AFFILIATE_HOROSCOPES_PISCES :
                    $logger->debug ( 'Amanuensis::Framework - processing mail delivery for SEO sites' );
                    $email = new SEOEmailDelivery_EN ();
                    break;
                case C_AFFILIATE_DATASTAR :
                    $logger->debug ( 'Amanuensis::Framework - processing mail delivery for Datastar/World Astro' );
                    $email = new DatastarEmailDelivery_EN ();
                    break;
                case C_AFFILIATE_ZODIA :
                    $logger->debug ( 'Amanuensis::Framework - processing mail delivery for Zodia' );
                    $email = new ZodiaEmailDelivery_EN ();
                    break;
                case C_AFFILIATE_ALLER :
                    $logger->debug ( 'Amanuensis::Framework - processing mail delivery for Aller' );
                    $email = new AllerEmailDelivery_DK ();
                    break;
                case C_AFFILIATE_PERNILLE_HOLM :
                    $logger->debug ( 'Amanuensis::Framework - processing mail delivery for Pernille Holm' );
                    $email = new PernilleHolmEmailDelivery_DK ();
                    break;
                case C_AFFILIATE_HOROSCOPE_JUNKIE : //Added by Amit Paramr (04-Aug-2011)
                    $logger->debug ( 'Amanuensis::Framework - processing mail delivery for Horoscope Junkie' );
                    $email = new WowEmailDelivery_EN ();
                    break;
                case C_AFFILIATE_FACEBOOK : //Added by Amit Paramr (04-Aug-2011)
                    $logger->debug ( 'Amanuensis::Framework - processing mail delivery for Facebook' );
                    $email = new WowEmailDelivery_EN ();
                    break;
                case C_AFFILIATE_GOOGLE_ADWORDS : //Added by Amit Paramr (08-Aug-2011)
                    $logger->debug ( 'Amanuensis::Framework - processing mail delivery for Google' );
                    $email = new WowEmailDelivery_EN ();
                    break;
                case C_AFFILIATE_WELLBEING : //Added by Amit Paramr (18-Aug-2011)
                    $logger->debug ( 'Amanuensis::Framework - processing mail delivery for Wellbeing' );
                    $email = new WowEmailDelivery_EN ();
                    break;
                case C_AFFILIATE_WOW_FREE : //Added by Amit Paramr (13-Sep-2011)
                    $logger->debug ( 'Amanuensis::Framework - processing mail delivery for WOWUK free report' );
                    $email = new WowEmailDelivery_EN ();
                    break;
                case C_AFFILIATE_HOROSCOPES_FOR_YOU : //Added by Amit Paramr (29-Sep-2011)
                    $logger->debug ( 'Amanuensis::Framework - processing mail delivery for HOROSCOPES4U free report' );
                    $email = new WowEmailDelivery_EN ();
                    break;
                case C_AFFILIATE_ECLIPSEPSYCHICS : //Added by Amit Paramr 02-Nov-2011
                    $logger->debug ( 'Amanuensis::Framework - processing mail delivery for ECLIPSEPSYCHICS paid report' );
                    $email = new WowEmailDelivery_EN ();
                    break;
                case C_AFFILIATE_MSN : //Added by Amit Paramr (05-NOV-2011)
                    $logger->debug ( 'Amanuensis::Framework - processing mail delivery for MSN(UK)' );
                    $email = new WowEmailDelivery_DK ();
                    break;
                case C_AFFILIATE_BT_DK : //Added by Amit Paramr (11-NOV-2011)
                    $logger->debug ( 'Amanuensis::Framework - processing mail delivery for BT' );
                    $email = new WowEmailDelivery_DK ();
                    break;
                case C_AFFILIATE_KONTURA : //Added by Amit Paramr (23-NOV-2011)
                    $logger->debug ( 'Amanuensis::Framework - processing mail delivery for KONTURA' );
                    $email = new KonturaEmailDelivery_No ();
                    $email->UserName = trim ( $reportoptionitem->name );
                    break;
                case C_AFFILIATE_ASCENDINGHEARTS : //Added by Amit Paramr (07-Dec-2011)
                    $logger->debug ( 'Amanuensis::Framework - processing mail delivery for ASCENDINGHEARTS' );
                    $email = new AscendingheartsEmailDelivery_EN ();
                    $email->UserName = trim ( $reportoptionitem->name );
                    break;
                default :
                    $logger->debug ( 'Amanuensis::Framework - falling through default case' );
                    $logger->debug ( sprintf ( "Amanuensis::Framework - portal id = %d", $orderitem->portalId ) );
                    $logger->debug ( sprintf ( "Amanuensis::Framework - delivery option = %d", $orderitem->delivery_option ) );
                    break;
            }
            $email->setOrderId ( $orderitem->order_id );
            $email->send ();
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
?>