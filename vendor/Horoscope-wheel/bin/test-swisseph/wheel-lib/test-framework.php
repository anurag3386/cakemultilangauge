<?php

/*
 * Script: amanuensis.framework.php
 * Author: Andy Gray <andy.gray@astro-consulting.co.uk>
 *
 * Description
 *
 *
 * Requires
 * - affiliate_id
 * - affiliate collate path
 * - affiliate logger config xml path
 * - affiliate logger tag
 *
 * Modification History
 * - initial spike
 */

if (isset($debug_error_reporting) && $debug_error_reporting === true) {
    error_reporting(E_ALL);
} else {
    error_reporting(0);
}

/* path definitions */
if (!defined('ROOTPATH')) {
    define('ROOTPATH', '/home/29078/domains/world-of-wisdom.com');
}
define('CLASSPATH', ROOTPATH . '/classes');
define('LIBPATH', ROOTPATH . '/lib');
define('SPOOLPATH', ROOTPATH . '/var/spool');

/* font information */
define('FPDF_FONTPATH', LIBPATH . '/fpdf/fonts/');
include_once(LIBPATH . '/fpdf/fpdf.php');
include_once(LIBPATH . '/fpdi/fpdi.php');

require_once(CLASSPATH . '/collate/class.collate.php');
require_once(CLASSPATH . '/collate/class.collate.de.php');
require_once(CLASSPATH . '/collate/class.collate.dk.php');
require_once(CLASSPATH . '/collate/class.collate.en.php');
require_once(CLASSPATH . '/collate/class.collate.se.php');
require_once(CLASSPATH . '/collate/' . $affiliate_collate_path);

/* language resources */
require_once(ROOTPATH . '/include/lang/en.php');
require_once(ROOTPATH . '/include/lang/dk.php');
require_once(ROOTPATH . '/include/lang/du.php');
require_once(ROOTPATH . '/include/lang/ge.php');
/* add greek		 */
require_once(ROOTPATH . '/include/lang/no.php');
/* add portugese	 */
require_once(ROOTPATH . '/include/lang/sp.php');
require_once(ROOTPATH . '/include/lang/sw.php');

/* POG data */
require_once(CLASSPATH . '/configuration.php');
require_once(CLASSPATH . '/objects/class.database.php');
require_once(CLASSPATH . '/objects/class.pog_base.php');

require_once(CLASSPATH . '/objects/class.state.php');
require_once(CLASSPATH . '/objects/class.order.php');
require_once(CLASSPATH . '/objects/class.transaction.php');
require_once(CLASSPATH . '/objects/class.portal.php');
require_once(CLASSPATH . '/objects/class.product.php');
require_once(CLASSPATH . '/objects/class.birthdata.php');
require_once(CLASSPATH . '/objects/class.reportoption.php');
require_once(CLASSPATH . '/objects/class.emailaddress.php');

/* ACS Atlas */
require_once(CLASSPATH . '/objects/class.acs.atlas.php');
require_once(CLASSPATH . '/acs/class.acs.statelist.php');

/* HI report content */
require_once(CLASSPATH . '/objects/class.bookdk.php');
require_once(CLASSPATH . '/objects/class.astrowow.shorttermtrend_dk.php');
require_once(CLASSPATH . '/objects/class.bookuk.php');
require_once(CLASSPATH . '/objects/class.astrowow.shorttermtrend.php');
require_once(CLASSPATH . '/objects/class.bookdu.php'); /* TODO - need to port short term trends */
require_once(CLASSPATH . '/objects/class.bookge.php'); /* TODO - need to port short term trends */
require_once(CLASSPATH . '/objects/class.bookgr.php'); /* TODO - need to port short term trends */
require_once(CLASSPATH . '/objects/class.bookno.php'); /* TODO - need to port short term trends */
require_once(CLASSPATH . '/objects/class.booksp.php'); /* TODO - need to port short term trends */
require_once(CLASSPATH . '/objects/class.booksw.php'); /* TODO - need to port short term trends */

/* astrolog data */
// targetted for development action
// generate XML
require_once(CLASSPATH . '/astrolog/api.php');
require_once(CLASSPATH . '/astrolog/birthdata.php');
require_once(CLASSPATH . '/astrolog/pipe.php');

/* WOW data */
// targetted for development action
// deprecate FPDF and use TCPDF instead
// use Zend_Translate for embedded strings
// deprecate Netsity data format and use XML instead
include_once(CLASSPATH . '/wow/birthanalysis.php');  // deprecate
include_once(CLASSPATH . '/wow/dynamicanalysis.php');  // deprecate
include_once(CLASSPATH . '/wow/report.php');    // rewrite for XML
include_once(CLASSPATH . '/wow/report.pdf.generic.php');
include_once(CLASSPATH . '/wow/generator/default/report.pdf.rc1.php');

/* WOW Calendar/Seasonal reports */
include_once(CLASSPATH . '/wow/forecast.php');
include_once(CLASSPATH . '/wow/generator/default/forecast.pdf.php');

/* WOW Wheel */
/* Developer Note - these are temporary until the issues are iron out */
require_once (ROOTPATH . '/bin/wheel/class.report.pdf.wheel.rc1.php');
include_once(ROOTPATH . '/bin/wheel/class.report.pdf.wheel.wow.php');

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

// targetted for development action
// deprecate and use Zend_Log instead
define('LOG4PHP_DIR', LIBPATH . '/log4php-0.9/src/log4php');
define('LOG4PHP_CONFIGURATION', ROOTPATH . '/var/lib/log4php/amanuensis/' . $affiliate_logger_tag . '.xml'); /* find a home for this */
require_once(LOG4PHP_DIR . '/LoggerManager.php');
$logger = & LoggerManager::getLogger('amanuensis::' . $affiliate_logger_tag);

/* * ************************************************************************ */

/* build a table of states */
// targetted for development action
// work with constants
$logger->debug("Amanuensis::Framework - build table of states");
$state = new State();
$states = array();
$statelist = $state->GetList
                (
                array(
                    array('stateId', '>', 0)
                )
);
foreach ($statelist as $item) {
    $states[$item->name] = $item->stateId;
}

/* look for queued orders */
$logger->debug("Amanuensis::Framework - look for queued orders");
$order = new Order();
$orderqueue = $order->GetList
                (
                array(
                    array('state', '=', 3), /* queued */
                    array('portalid', '=', intval($affiliate_id)),
                    array('productid', '=', 16) /* Year report */
                )
);
//echo count($orderqueue);
$logger->debug('Amanuensis::Framework For YR - order queue has ' . count($orderqueue) . ' orders');
/* only process orders in the queued state */
if (count($orderqueue) > 0) {
    $logger->debug('Amanuensis::Framework - order queue has ' . count($orderqueue) . ' orders');

    $birthdata = new BirthData();
    $reportoption = new ReportOption();
    $product = new Product();

    // is this still required?
    // where is this used
    $planets = array(
        'Sun', 'Moon',
        'Mercury', 'Venus', 'Mars',
        'Jupiter', 'Saturn',
        'Uranus', 'Neptune', 'Pluto',
        'NNode', 'SNode',
        'Ascendant', 'MC', 'IC', 'Descendant'
    );


    /* cycle through queued orders */
    foreach ($orderqueue as $orderitem) {
        $logger->debug('Amanuensis::Framework - starting to process order');
        //error_log("\nFramework: time now is " . strftime("%c") . "\n",3,"/home/29078/data/log/amanuensis.log");
        //error_log("Framework: processing order ID=$orderitem->orderId\n",3,"/home/29078/data/log/amanuensis.log");

        error_log("\n\nFramework: time now is " . strftime("%c") . "\n", 3, ROOTPATH . "/data/log/amanuensis.log");
        error_log("\n\nFramework: processing order ID=$orderitem->orderId\n", 3, ROOTPATH . "/data/log/amanuensis.log");

        /* update state = processing */
        $logger->debug('Amanuensis::Framework - setting order state - processing');
        $orderitem->state = $states['processing'];
        $orderitem->save();

        /* update transactions */
        $logger->debug('Amanuensis::Framework - creating new transaction - processing');
        $transaction = new Transaction();
        $transaction->orderId = $orderitem->orderId;
        $transaction->state = $orderitem->state;
        $transaction->timestamp = strftime("%Y-%m-%d %H:%M:%S");
        $transaction->save();

        /* look for the birth data component */
        $logger->debug('Amanuensis::Framework - processing birthdata');
        $birthdatalist = $birthdata->GetList
                        (
                        array(
                            array('orderId', '=', $orderitem->orderId)
                        /* may need to constrain later when non report options are available */
                        )
        );

        /* there should be exactly 1 result */
        if (count($birthdatalist) != 1) {
            $logger->debug('Amanuensis::Framework - multiple or no birthdata found');
        } else {
            $logger->debug('Amanuensis::Framework - birthdata found');
        }

        foreach ($birthdatalist as $birthentry) {

            $formattedDate = sprintf("%04d%02d%02d",
                            $birthentry->year,
                            $birthentry->month,
                            $birthentry->day
            );
            $logger->debug('Amanuensis::Framework - formatted date = ' . $formattedDate);

            $formattedTime = sprintf("%02d:%02d",
                            $birthentry->hour,
                            $birthentry->minute
            );
            $logger->debug('Amanuensis::Framework - formatted time = ' . $formattedTime);

            /*
             * added for solar chart
             */
            $timed_data = ((trim($birthentry->untimed) == 'N') ? true : false );
            $logger->debug("Amanuensis::Framework - timed data = [" . trim($birthentry->untimed) . "]");

            $data = new AstrologBirthData
                            (
                            /* date	 */ $formattedDate,
                            /* time	 */ $formattedTime,
                            /* timed data  */ $timed_data,
                            /* summertime	 */ $birthentry->summerref,
                            /* timezone	 */ $birthentry->zoneref,
                            /* longitude	 */ $birthentry->longitude,
                            /* latitude	 */ $birthentry->latitude
            );
        } /* birthdata */

        /* look for the report options */
        $logger->debug('Amanuensis::Framework - processing report options');
        $reportoptionlist = $reportoption->GetList
                        (
                        array(
                            array('orderId', '=', $orderitem->orderId)
                ));

        /* there should be exactly 1 result */
        if (count($reportoptionlist) != 1) {
            $logger->debug('Amanuensis::Framework - multiple or no reportoptions found');
        } else {
            $logger->debug('Amanuensis::Framework - report options found');
            foreach ($reportoptionlist as $reportoptionitem) {

                /* create the generator */
                /* note that this is overlayed in the PDF generator */
                switch (strtoupper($reportoptionitem->paper_size)) {
                    case 'A41PCOL': /* A4, portrait, single column */
                        $logger->debug('Amanuensis::Framework - creating PDF generator for A41PCOL');
                        $generator = new PDFGenerator($reportoptionitem->paper_size);
                        break;
                    case 'A42PCOL': /* A4, portrait, 2 column */
                        $logger->debug('Amanuensis::Framework - creating PDF generator for A42PCOL');
                        $generator = new PDFGenerator($reportoptionitem->paper_size);
                        break;
                    case 'US1PCOL': /* US letter, portrait, single column */
                        $logger->debug('Amanuensis::Framework - creating PDF generator for US1PCOL');
                        $generator = new PDFGenerator($reportoptionitem->paper_size);
                        break;
                    case 'US2PCOL': /* US letter, portrait, 2 column */
                        $logger->debug('Amanuensis::Framework - creating PDF generator for US2PCOL');
                        $generator = new PDFGenerator($reportoptionitem->paper_size);
                        break;
                    default:
                        $logger->debug('Amanuensis::Framework - creating PDF generator for default case');
                        $generator = new PDFGenerator($reportoptionitem->paper_size);
                        break;
                }

                $generator->language = $reportoptionitem->language;
                $logger->debug('Amanuensis::Framework - generator language set to ' . $generator->language);
                switch (strtoupper($generator->language)) {
                    case 'EN':
                        $logger->debug('Amanuensis::Framework - generator using English Book');
                        $book = new BookUK();
                        $shorttermtrend = new ShortTermTrend();
                        break;
                    case 'DK':
                        $logger->debug('Amanuensis::Framework - generator using Danish Book');
                        $book = new BookDK();
                        $shorttermtrend = new ShortTermTrend_DK();
                        break;
                    case 'DU':
                        $logger->debug('Amanuensis::Framework - generator using Dutch Book');
                        $book = new BookDU();
                        $shorttermtrend = new ShortTermTrend(); /* use UK trends until translation exists */
                        break;
                    case 'DE':
                        $logger->debug('Amanuensis::Framework - generator using German Book');
                        $book = new BookGE();
                        $shorttermtrend = new ShortTermTrend(); /* use UK trends until translation exists */
                        break;
                    case 'GR':
                        $logger->debug('Amanuensis::Framework - generator using Greek Book');
                        $book = new BookGR();
                        $shorttermtrend = new ShortTermTrend(); /* use UK trends until translation exists */
                        break;
                    case 'NO':
                        $logger->debug('Amanuensis::Framework - generator using Norwegian Book');
                        $book = new BookNO();
                        $shorttermtrend = new ShortTermTrend(); /* use UK trends until translation exists */
                        break;
                    case 'SP':
                        $logger->debug('Amanuensis::Framework - generator using Spanish Book');
                        $book = new BookSP();
                        $shorttermtrend = new ShortTermTrend(); /* use UK trends until translation exists */
                        break;
                    case 'SE':
                        $logger->debug('Amanuensis::Framework - generator using Swedish Book');
                        $book = new BookSW();
                        $shorttermtrend = new ShortTermTrend(); /* use UK trends until translation exists */
                        break;
                    default:
                        $logger->error('Failed to determine which Book to use');
                        /* update state */
                        $logger->debug('Amanuensis::Framework - setting order state - orphaned');
                        $orderitem->state = $states['orphaned'];
                        $orderitem->save();

                        /* update transactions */
                        $logger->debug('Amanuensis::Framework - creating new transaction - orphaned');
                        $transaction = new Transaction();
                        $transaction->orderId = $orderitem->orderId;
                        $transaction->state = $orderitem->state;
                        $transaction->timestamp = strftime("%Y-%m-%d %H:%M:%S");
                        $transaction->save();
                        die("failed to find language");
                }
            } /* of report option context */
        }

        /*
         * Static reports are	: personal, career, pc	(supported)
         * Dynamic reports are	: y3, pc3				(unsupported)
         * Calendar reports are	: calendar				(supported)
         * Seasonal reports are	: seasonal				(supported)
         */
        $logger->debug('Amanuensis::Framework - processing report');
        switch ($product->Get($orderitem->productId)->name) {
            case 'personal':
            case 'career':
            case 'year':
            case 'pc':
                $report = new Report();
                $product->Get($orderitem->productId);
                $report->type = $product->name;
                $logger->debug('Amanuensis::Framework - report type = ' . $report->type);
                $analysis_context = new BirthAnalysis($data);
                $report->run
                        (
                        /* report requires analysis data */ $analysis_context,
                        /* report requires content */ $book,
                        /* report requires a generator */ $generator
                );
                $report->generate->pdf->Output(sprintf("%s/%d.report.pdf", SPOOLPATH, $orderitem->orderId));
                $report->generate->pdf->Close();
                break;

            /*
             * start date set to the start of next month
             * duration set to 1 year
             */
            case 'y3':
                $report = new Report();
                $report->type = $product->name;
                $logger->debug('Amanuensis::Framework - report type = ' . $report->type);
                /* start the report at the beginning of next month */
                $start_month = intval(substr($reportoptionitem->start_date, 6, 2));
                $start_year = intval(substr($reportoptionitem->start_date, 0, 4));
                if ($start_month == 12) {
                    $start_month = 1;
                    $start_year++;
                }
                $start_date = sprintf('%04d-%02d-01 00:00:00', $start_year, $start_month);
                $analysis_context = new DynamicAnalysis(
                                /* report type	 */ $report->type,
                                /* birth data	 */ $data,
                                /* start date	 */ $start_date, /* start of next month */
                                /* duration		 */ 3, /* year */
                                /* debug		 */ false
                );
                $report->run(
                        /* report requires analysis data */ $analysis_context,
                        /* report requires content */ $book,
                        /* report requires a generator */ $generator
                );
                $report->generate->pdf->Output(
                        sprintf("%s/%d.report.pdf", SPOOLPATH, $orderitem->orderId)
                );
                $report->generate->pdf->Close();
                break;

            case 'pc3':
                $report = new Report();
                $report->type = $product->name;
                $logger->debug('Amanuensis::Framework - report type = ' . $report->type);
                /* start the report at the beginning of next month */
                $start_month = intval(substr($reportoptionitem->start_date, 6, 2));
                $start_year = intval(substr($reportoptionitem->start_date, 0, 4));
                if ($start_month == 12) {
                    $start_month = 1;
                    $start_year++;
                }
                $start_date = sprintf('%04d-%02d-01 00:00:00', $start_year, $start_month);
                $analysis_context = new DynamicAnalysis(
                                /* report type	 */ $report->type,
                                /* birth data	 */ $data,
                                /* start date	 */ $start_date,
                                /* duration	 */ $reportoptionitem->duration,
                                /* debug	 */ false
                );
                $report->run(
                        /* report requires analysis data */ $analysis_context,
                        /* report requires content */ $book,
                        /* report requires a generator */ $generator
                );
                $report->generate->pdf->Output(
                        sprintf("%s/%d.report.pdf", SPOOLPATH, $orderitem->orderId)
                );
                $report->generate->pdf->Close();
                break;

            case 'calendar':
                $report = new Forecast();
                $generator = new PDF_Forecast();
                $logger->debug("Amanuensis::Framework - generator for calendar report");
                $report->trend = $shorttermtrend;
                $report->run(
                        /* birth data	 */ $data,
                        /* start date	 */ $reportoptionitem->start_date,
                        /* duration		 */ $reportoptionitem->duration,
                        /* seasonal		 */ false,
                        /* language		 */ $reportoptionitem->language,
                        /* generator	 */ $generator
                );
                $report->generator->pdf->Output(
                        sprintf("%s/%d.report.pdf", SPOOLPATH, $orderitem->orderId)
                );
                $report->generator->pdf->Close();
                break;

            case 'seasonal':
                $report = new Forecast();
                $generator = new PDF_Forecast();
                $logger->debug("Amanuensis::Framework - generator for seasonal report");
                $report->run(
                        /* birth data	 */ $data,
                        /* start date	 */ $reportoptionitem->start_date,
                        /* duration		 */ $reportoptionitem->duration,
                        /* seasonal		 */ true,
                        /* language		 */ $reportoptionitem->language,
                        /* generator	 */ $generator
                );
                $report->generator->pdf->Output(
                        sprintf("%s/%d.report.pdf", SPOOLPATH, $orderitem->orderId)
                );
                $report->generator->pdf->Close();
                break;

            default:
                /* update state */
                $logger->debug('Amanuensis::Framework - setting order state - orphaned');
                $orderitem->state = $states['orphaned'];
                $orderitem->save();

                /* update transactions */
                $logger->debug('Amanuensis::Framework - creating new transaction - orphaned');
                $transaction = new Transaction();
                $transaction->orderId = $orderitem->orderId;
                $transaction->state = $orderitem->state;
                $transaction->timestamp = strftime("%Y-%m-%d %H:%M:%S");
                $transaction->save();
                die("failed to find report type");
                break;
        }



        /*
         * This is using the old code for now until the issues are further investigated
         */
        $logger->debug('Amanuensis::Framework - processing wheel');
        $wheel = new PDF_Wheel_WOW();
        $wheel->language = $reportoptionitem->language;

        $wheel->Open();
        $wheel->AddFont('wows', '', 'wows.php');            // text
        $wheel->AddFont('ww_rv1', '', 'ww_rv1.php');        // graphics
        $wheel->SetDisplayMode('fullpage');
        $wheel->SetAutoPageBreak(false);

        $chart = new AstrologChartAPI($data);

        /* NOTE - this is repetition, take from previous context */
        /* get the planetary context - longitude and house occupancy */
        for ($planet = 0/* Sun */; $planet < 12/* S.Node */; $planet++) {
            $wheel->planet_longitude[$planet] = $chart->m_object[$planets[$planet]]['longitude'];
            $wheel->planet_in_house[$planet] = $chart->m_object[$planets[$planet]]['house'];
            $wheel->planet_retrograde[$planet] = $chart->m_object[$planets[$planet]]['retrograde'];
        }

        /* now go for the aspects */
        for ($asp = 0; $asp < count($chart->m_aspect); $asp++) {
            array_push($wheel->planet_aspects, $chart->m_aspect[$asp]);
            $logger->debug($chart->m_aspect[$asp]);
        }

        for ($house = 0; $house < 12; $house++) {
            $wheel->house_cusp_longitude[$house] = $chart->m_object['cusp'][$house + 1];
        }

        if ($timed_data === true) {
            $birthdate = sprintf("%02d/%02d/%04d %02d:%02d",
                            $birthentry->day, $birthentry->month, $birthentry->year,
                            $birthentry->hour, $birthentry->minute);
        } else {
            $birthdate = sprintf("%02d/%02d/%04d",
                            $birthentry->day, $birthentry->month, $birthentry->year);
        }

        /* get place information */
        $acsatlas = new ACSAtlas();
        $acsatlas->get($birthentry->place);
        //$fullbirthplace = split(">", $acsatlas->placename);
        $fullbirthplace = preg_split("%^/>/$%", $acsatlas->placename);
        //$birthplace = $acsatlas->placename;
        $birthplace = trim($fullbirthplace[0]);

        /* get state information */
        $acsstatelist = new ACSStatelist();
        $birthcountry = $acsstatelist->getStateNameByAbbrev(substr($acsatlas->lkey, 0, 2));

        $coords = sprintf(
                        "%2d%s%02d %3d%s%02d",
                        abs(intval($birthentry->latitude)),
                        (($birthentry->latitude >= 0) ? 'N' : 'S'),
                        abs(intval((($birthentry->latitude - intval($birthentry->latitude)) * 60))),
                        abs(intval($birthentry->longitude)),
                        (($birthentry->longitude >= 0) ? 'W' : 'E'),
                        abs(intval((($birthentry->longitude - intval($birthentry->longitude)) * 60)))
        );

        $timedeltaStr = sprintf("%04d", intval(abs($birthentry->zoneref)));
        $timedelta = sprintf(
                        "%d:%02d",
                        intval(substr($timedeltaStr, 0, 2)),
                        intval(substr($timedeltaStr, 2, 2))
        );

        $timediffStr = sprintf("%04d", intval(abs($birthentry->summerref)));
        $timediff = sprintf(
                        "%d:%02d",
                        intval(substr($timediffStr, 0, 2)),
                        intval(substr($timediffStr, 2, 2))
        );

        $wheel->wheel_offset = $wheel->house_cusp_longitude[0];

        //$wheel->table_user_info_fname		= utf8_decode(trim($reportoptionitem->name));
        $wheel->table_user_info_fname = trim($reportoptionitem->name);
        $wheel->table_user_info_lname = "";
        $wheel->table_user_info_birth_weekday = $wheel_top_weekdays[strtolower($reportoptionitem->language)]
                [JDDayOfWeek(cal_to_jd(CAL_GREGORIAN, $birthentry->month, $birthentry->day, $birthentry->year), 1)];
        $wheel->table_user_info_birth_date = $birthdate;
        $wheel->table_user_info_birth_place = trim($birthplace);
        $wheel->table_user_info_birth_state = trim($birthcountry);
        $wheel->table_user_info_birth_coords = $coords;
        $wheel->table_user_info_birth_timezone = $timedelta;
        $wheel->table_user_info_birth_summertime = $timediff;
        $wheel->table_user_info_house_system = (($timed_data === true) ? 'Placidus' : 'Solar');
        $wheel->table_user_info_orb_system = $wheel_top_orbsensitivity[strtolower($reportoptionitem->language)];

        $wheel->generateChartWheelPage();
        $wheel->Output(sprintf("%s/%d.wheel.pdf", SPOOLPATH, $orderitem->orderId));

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
        $logger->debug('Amanuensis::Framework - processing assembly');
        switch ($orderitem->portalId) {
            case 0: /* Test */
            case C_AFFILIATE_WOW:
                $pdi = new WOW_Col8_FPDI_EN();
                break;
             case C_AFFILIATE_WOW_FREE:
                $pdi = new WOW_Col8_FPDI_EN();
                break;
            default:
                $pdi = new WOW_Col8_FPDI_EN();
                break;
        }

        $pdi->report_addressee = trim($reportoptionitem->name);
        $pdi->cover_birthdate = trim($birthdate);
        $pdi->cover_birthplace = trim($birthplace);
        $pdi->cover_birthcountry = trim($birthcountry);

        switch ($product->Get($orderitem->productId)->name) {
            case "personal":
            case "career":
            case "pc":
                $title = "Birth Analysis";
                $pdi->table_of_contents = $report->generate->toc;
                break;
            case "y3":
                $start_month = intval(substr($reportoptionitem->start_date, 6, 2));
                $start_year = intval(substr($reportoptionitem->start_date, 0, 4));
                if ($start_month == 12) {
                    $start_month = 1;
                    $start_year++;
                }
                $date_start = sprintf('01-%02d-%04d', $start_month, $start_year);
                $date_end = sprintf('01-%02d-%04d', $start_month, ($start_year + $reportoptionitem->duration));
                $title = "Dynamic Analysis ($date_start - $date_end)";
                $pdi->table_of_contents = $report->generate->toc;
                break;
            case "pc3":
                $start_month = intval(substr($reportoptionitem->start_date, 6, 2));
                $start_year = intval(substr($reportoptionitem->start_date, 0, 4));
                if ($start_month == 12) {
                    $start_month = 1;
                    $start_year++;
                }
                $date_start = sprintf('01-%02d-%04d', $start_month, $start_year);
                $date_end = sprintf('01-%02d-%04d', $start_month, ($start_year + $reportoptionitem->duration));
                $title = "Birth Analysis + Dynamic Analysis ($date_start - $date_end)";
                $pdi->table_of_contents = $report->generate->toc;
                break;
            case "seasonal":
                $title = "Seasonal Report";
                break;
            case "calendar":
                $title = "Calendar Report";
                break;
            case "year":
                $title = "Year Report";
                break;
            default:
                break;
        }

        /* footer stuff */
        $pdi->report_title = $title;

        $pdi->orderId = $orderitem->orderId;

        $pdi->Assemble();
        $pdi->Output(sprintf("%s/%d.bundle.pdf", SPOOLPATH, $orderitem->orderId));
        $pdi->closeParsers();

        /* update state */
        $logger->debug('Amanuensis::Framework - setting order state - ready');
        $orderitem->state = $states['ready'];
        $orderitem->save();

        /* update transaction */
        $logger->debug(sprintf("Amanuensis::Framework - creating transaction, order state = %d", $orderitem->state));
        $transaction->state = $orderitem->state;
        $logger->debug(sprintf("Amanuensis::Framework - creating transaction, timestamp = %s", strftime("%Y-%m-%d %H:%M:%S")));
        $transaction->timestamp = strftime("%Y-%m-%d %H:%M:%S");
        $logger->debug('Amanuensis::Framework - creating transaction, savenew');
        $transaction->savenew();
        $logger->debug('Amanuensis::Framework - creating transaction, done');

        /* if delivery method is email then deliver the report */
        $logger->debug('Amanuensis::Framework - checking delivery option');
        if ($orderitem->delivery_option == 1 /* email */) {
            $logger->debug('Amanuensis::Framework - processing mail delivery');
            switch ($orderitem->portalId) {
                case C_AFFILIATE_WOW:
                    $logger->debug('Amanuensis::Framework - processing mail delivery for WOW(UK)');
                    $email = new WowEmailDelivery_EN();
                    break;
                case C_AFFILIATE_WOW_FREE:     //Added by Amit Paramr (13-Sep-2011)
                    $logger->debug('Amanuensis::Framework - processing mail delivery for WOWUK free report');
                    $email = new WowEmailDelivery_EN();
                    break;
                default:
                    $logger->debug('Amanuensis::Framework - falling through default case');
                    $logger->debug(sprintf("Amanuensis::Framework - portal id = %d", $orderitem->portalId));
                    $logger->debug(sprintf("Amanuensis::Framework - delivery option = %d", $orderitem->delivery_option));
                    break;
            }
            $email->setOrderId($orderitem->orderId);
            $email->send();
        } else {
            $logger->debug('Amanuensis::Framework - no processing mail delivery required');
        } /* mail delivery context */
    } /* end of foreach orderitem */

    /* GC */
    unset($pdi);
    unset($book);
    unset($report);
} else {
    // $logger->debug('Amanuensis::Framework - order queue is empty');
}
?>