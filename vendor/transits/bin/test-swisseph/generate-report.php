<?php

/**
 * @script          : generate-report.php
 * @developer       : Amit Parmar
 * @Copy Right      : World of wisdom Inc
 *  
 * @description : 
 *      Its scheduler layer to initiat the report generation and set the queue to gengerate year report.
 * 
 * Common Terms :
 *      ProductID = 16 ( Year Report )
 *      State     = 3  ( Newly Queued order )  
 * 
 * Report generation steps: 
 * 
 * Step 1 :->       Get all the new order and State list 
 *                  Product ID : 16
 * 
 * Step 2 :->       Fetching the current processing order "BIRTH INFO"
 * 
 * Step 3 :->       Updating status of the order to "PROCESSING"
 *              
 * Step 4 :->       Adding new entry to Transaction manage processing queue time
 * 
 * Step 5 :->       Processing current "BIRTH INFO"
 *                  [ Calculating Summe time zone and formatting Longitude and Latitude for further process. ]
 * 
 * Step 5.1 :->     Send all the "BIRTH INFO" to SwissEph to generate the Array of the planatory position with the house cups
 * 
 * Step 6 :->       Getting the place name and country name to place inside the report PDF
 * 
 * Step 6.1 :->     Placing "BIRTH INFO" to report's 1st Page.
 * 
 * Step 6.2 :->     Now processing the SwissEph return Data to generate "HOROSCOPE" wheel in 2nd page of the report
 * 
 * Step 6.3 :->     Now Processing the Aspect and place next to "HOROSCOPE" wheel.
 * 
 * Step 7 :->       Now Calulate "TRANSIT" for 3 year (Previous | Current | Next years)
 * 
 * Step 7.1 :->     Draw TRANSIT graph on PDF and display table
 * 
 * Step 8 :->       Calculate Solar Return
 * 
 * Step 8.1 :->     Draw solar return Wheel
 * 
 * Step 8.2 :->     Calculate Aspect to ( Solar return to Natal chart )
 * 
 * Step 8.2 :->     Display Solar return table with aspect
 * 
 * Step 9 :->       Calculate Horory Chart and Draw wheel
 * 
 * Step 9.1 :->     Calculate aspect to ( Horory to Natal chart )
 *  
 * */
if (isset($debug_error_reporting) && $debug_error_reporting === true) {
    error_reporting(E_ALL);
} else {
    error_reporting(0);
}

/**
 * Setting you root path.
 * */
if (!defined('ROOTPATH')) {
    //define('ROOTPATH', '/home/29078/domains/world-of-wisdom.com');
    define('ROOTPATH', '/www/wow-year');
}

//Initializing the Classes and library and defult variables
define('CLASSPATH', ROOTPATH . '/classes');
define('LIBPATH', ROOTPATH . '/lib');
define('SPOOLPATH', ROOTPATH . '/var/spool');

//Including FPDI library
require_once(LIBPATH . '/fpdi/fpdi.php');

/* POG data (Data Access Layer) */
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


/* Swiss EPHE */
// targetted for development action  generate XML
require_once(ROOTPATH . 'bin/test-swisseph/birthdto.php');
require_once(ROOTPATH . 'bin/test-swisseph/swisseph-services.php');
require_once(ROOTPATH . 'bin/test-swisseph/swisseph-calculator.php');

require_once(CLASSPATH . '/wow/report.php');				// rewrite for XML
require_once(CLASSPATH . '/wow/report.pdf.generic.php');
require_once(CLASSPATH . '/wow/generator/default/report.pdf.rc1.php');

/* WOW Wheel */
/* Developer Note - these are temporary until the issues are iron out */

require_once(ROOTPATH . '/bin/wheel/class.report.pdf.wheel.rc1.php');

require_once(ROOTPATH . '/bin/wheel/class.report.pdf.wheel.wow.php');

/* * * Client specific Email setup ** */
require_once(CLASSPATH . '/delivery/email/class.delivery.email.php');
require_once(CLASSPATH . '/delivery/email/class.delivery.email.wow.en.php');

// deprecate and use Zend_Log instead
define('LOG4PHP_DIR', LIBPATH . '/log4php-0.9/src/log4php');
define('LOG4PHP_CONFIGURATION', ROOTPATH . '/var/lib/log4php/swissephe/wowuk.xml');

require_once(LOG4PHP_DIR . '/LoggerManager.php');
$logger = & LoggerManager::getLogger('swissephe::wowuk');
$reportLogger = & LoggerManager::getLogger('swissephe::wowuk');


/*
 * Step 1 :->       Get all the new order and State list  
 */
$reportLogger->debug("SwissEphe::Year Report:- Generating states table");

$state = new State();
$states = array();
$statelist = $state->GetList(array(array('stateId', '>', 0)));

foreach ($statelist as $item) {
    $states[$item->name] = $item->stateId;
}

/* * Now checking for newly queued year report * */
$reportLogger->debug("SwissEphe::Year Report:- Get all new report");

$order = new Order();
$orderQueue = $order->GetList(array(array('state', '=', 3), array('portalid', '=', intval($affiliate_id)), array('productid', '=', 16)));
/* 3 = Queued       | Taking care for generic                      |  Year report */
//Taking care of null or Zero order

if (count($orderQueue) > 0) {
    $reportLogger->debug('SwissEphe::Year Report:- Total queued order :- [ ' . count($orderQueue) . ' ] ');

    $birthData = new BirthData();
    $reportOption = new ReportOption();
    $product = new Product();

    //Loop through all the newly created orders
    foreach ($orderQueue as $currentOrder) {
        // Creating Error Log entry
        $reportLogger->debug('SwissEphe::Year Report:-  Processing Year Report');
        error_log("\n\n SwissEphe::Year Report:-  Processing Time : " . strftime("%c") . "\n", 3, ROOTPATH . "/data/log/swissephe.log");
        error_log("\n\n SwissEphe::Year Report:-  Current Order ID= $currentOrder->orderId \n", 3, ROOTPATH . "/data/log/swissephe.log");

        /*
         * Step 2 :->   Fetching the current processing order "BIRTH INFO"
         *              NOW Fetching member 
         */
        $reportLogger->debug('SwissEphe::Year Report:-  Getting Birth details and processing');
        $birthDataList = $birthData->GetList(array(array('orderId', '=', $currentOrder->orderId)));

        /*
         * Step 3 :->   Updating status of the order to "PROCESSING"
         * Now Updating the Order Status QUEUE to PROCESSING
         */
        $reportLogger->debug('SwissEphe::Year Report:-  Updating Order Status - PROCESSING');
        $currentOrder->state = $states['processing'];
        $currentOrder->save();

        /*
         * Step 4 :->   Adding new entry to Transaction manage processing queue time
         * Creating Trasations Log for proccessing report *        
         */
        $currentOrderTransaction = new Transaction();
        $currentOrderTtransaction->orderId = $currentOrder->orderId;
        $currentOrderTransaction->state = $currentOrder->state;
        $currentOrderTransaction->timestamp = strftime("%Y-%m-%d %H:%M:%S");
        $currentOrderTransaction->save();

        /*
         * Step 5 :->   Get report options and processing current "BIRTH INFO"
         *              [ Calculating Summe time zone and formatting Longitude and Latitude for further process. ]         
         */

        $reportOptionList = $reportOption->GetList(array(array('orderId', '=', $currentOrder->orderId)));

        $reportLogger->debug('swissephe::Year-Framework :- Getting Birth details and processing \n');

        $birthdatalist = $birthData->GetList(array(array('orderId', '=', $currentOrder->orderId)));

        foreach ($birthdatalist as $birthentry) {
            $isKnowTime = ((trim($birthentry->untimed) == 'N') ? true : false );
            $reportLogger->debug("swissephe::Year-Framework :- timed data = [" . trim($birthentry->untimed) . "]\n");

            //($BirthDay, $BirthMonth, $BirthYear, $BirthHour, $BirthMinute, $BirthSecond, $IsTimedData, $SummerTime, $TimeZone, $Longitude, $Latitude)
            $birthDTO = new BirthDTO(
                            $birthentry->day, $birthentry->month, $birthentry->year, //Birth Date
                            $birthentry->hour, $birthentry->minute, 0, //Birth Time
                            $isKnowTime,                        //User know his/her birth time
                            $birthentry->summerref,             //Calulated Summer time zone
                            $birthentry->zoneref,               //Calulated time zone
                            $birthentry->longitude,             //Birth place longitude
                            $birthentry->latitude);             //Birth place latitude            
        }        
        if (count($reportOptionList) != 1) {
            $reportLogger->debug('swissephe::Year-Framework :- multiple or no reportoptions found \n');
        } else {
            $reportLogger->debug('swissephe::Year-Framework :- report options found \n');

            foreach ($reportOptionList as $reportOptionItem) {
//                echo '<pre>';
//                print_r($reportOptionItem);
//                echo '</pre>';
                
                /* create the generator */
                /* note that this is overlayed in the PDF generator */
                switch (strtoupper($reportOptionItem->paper_size)) {
                    case 'A41PCOL': /* A4, portrait, single column */
                        $reportLogger->debug('swissephe::Year-Framework :- creating PDF generator for A41PCOL \n');
                        $generator = new PDFGenerator($reportOptionItem->paper_size);
                        break;
                    case 'A4': /* A4, portrait, 2 column */
                        $reportLogger->debug('swissephe::Year-Framework :- creating PDF generator for A42PCOL \n');
                        $generator = new PDFGenerator($reportOptionItem->paper_size);
                        break;
                    default:
                        $reportLogger->debug('swissephe::Year-Framework :- creating PDF generator for default case \n');
                        $generator = new PDFGenerator($reportOptionItem->paper_size);
                        break;
                }

                $generator->language = $reportOptionItem->language;
                $reportLogger->debug('swissephe::Year-Framework :- generator language set to ' . $generator->language . ' \n');

                switch (strtoupper($generator->language)) {
                    
                } /* of report option context */
            }

            /*             * ********************** NEW CODE ****************** */

            /*
             * This is using the old code for now until the issues are further investigated
             */
            $reportLogger->debug('swissephe::Year-Framework :- processing wheel \n');
            $wheel = new PDF_Wheel_WOW();
            $wheel->language = $reportOptionItem->language;

            $wheel->Open();
            $wheel->AddFont('wows', '', 'wows.php');            // text
            $wheel->AddFont('ww_rv1', '', 'ww_rv1.php');        // graphics
            $wheel->SetDisplayMode('fullpage');
            $wheel->SetAutoPageBreak(false);

            $chart = new SwissEphemerisServices($data);

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
                $reportLogger->debug($chart->m_aspect[$asp]);
            }

            for ($house = 0; $house < 12; $house++) {
                $wheel->house_cusp_longitude[$house] = $chart->m_object['cusp'][$house + 1];
            }

            if ($timed_data === true) {
                $birthdate = sprintf("%02d/%02d/%04d %02d:%02d", $birthentry->day, $birthentry->month, $birthentry->year, $birthentry->hour, $birthentry->minute);
            } else {
                $birthdate = sprintf("%02d/%02d/%04d", $birthentry->day, $birthentry->month, $birthentry->year);
            }

            /* get place information */
            $acsatlas = new ACSAtlas();
            $acsatlas->get($birthentry->place);
            $fullbirthplace = split(">", $acsatlas->placename);
            //$birthplace = $acsatlas->placename;
            $birthplace = trim($fullbirthplace[0]);

            /* get state information */
            $acsstatelist = new ACSStatelist();
            $birthcountry = $acsstatelist->getStateNameByAbbrev(substr($acsatlas->lkey, 0, 2));

            $coords = sprintf(
                    "%2d%s%02d %3d%s%02d", abs(intval($birthentry->latitude)), (($birthentry->latitude >= 0) ? 'N' : 'S'), abs(intval((($birthentry->latitude - intval($birthentry->latitude)) * 60))), abs(intval($birthentry->longitude)), (($birthentry->longitude >= 0) ? 'W' : 'E'), abs(intval((($birthentry->longitude - intval($birthentry->longitude)) * 60)))
            );

            $timedeltaStr = sprintf("%04d", intval(abs($birthentry->zoneref)));
            $timedelta = sprintf(
                    "%d:%02d", intval(substr($timedeltaStr, 0, 2)), intval(substr($timedeltaStr, 2, 2))
            );

            $timediffStr = sprintf("%04d", intval(abs($birthentry->summerref)));
            $timediff = sprintf(
                    "%d:%02d", intval(substr($timediffStr, 0, 2)), intval(substr($timediffStr, 2, 2))
            );

            $wheel->wheel_offset = $wheel->house_cusp_longitude[0];

            //$wheel->table_user_info_fname		= utf8_decode(trim($reportOptionItem->name));
            $wheel->table_user_info_fname = trim($reportOptionItem->name);
            $wheel->table_user_info_lname = "";
            $wheel->table_user_info_birth_weekday = $wheel_top_weekdays[strtolower($reportOptionItem->language)]
                    [JDDayOfWeek(cal_to_jd(CAL_GREGORIAN, $birthentry->month, $birthentry->day, $birthentry->year), 1)];
            $wheel->table_user_info_birth_date = $birthdate;
            $wheel->table_user_info_birth_place = trim($birthplace);
            $wheel->table_user_info_birth_state = trim($birthcountry);
            $wheel->table_user_info_birth_coords = $coords;
            $wheel->table_user_info_birth_timezone = $timedelta;
            $wheel->table_user_info_birth_summertime = $timediff;
            $wheel->table_user_info_house_system = (($timed_data === true) ? 'Placidus' : 'Solar');
            $wheel->table_user_info_orb_system = $wheel_top_orbsensitivity[strtolower($reportOptionItem->language)];

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
            $reportLogger->debug('swissephe::Year-Framework :- processing assembly \n');
            switch ($orderitem->portalId) {
                case 0: /* Test */
                case C_AFFILIATE_WOW:
                    $pdi = new WOW_Col8_FPDI_EN();
                    break;
                default:
                    $pdi = new WOW_Col8_FPDI_EN();
                    break;
            }
            $pdi->report_addressee = trim($reportOptionItem->name);
            $pdi->cover_birthdate = trim($birthdate);
            $pdi->cover_birthplace = trim($birthplace);
            $pdi->cover_birthcountry = trim($birthcountry);

            switch ($product->Get($orderitem->productId)->name) {                
                case "pc":
                    $title = "Birth Analysis";
                    $pdi->table_of_contents = $report->generate->toc;
                    break;
                case "year":
                    $start_month = intval(substr($reportOptionItem->start_date, 6, 2));
                    $start_year = intval(substr($reportOptionItem->start_date, 0, 4));
                    if ($start_month == 12) {
                        $start_month = 1;
                        $start_year++;
                    }
                    $date_start = sprintf('01-%02d-%04d', $start_month, $start_year);
                    $date_end = sprintf('01-%02d-%04d', $start_month, ($start_year + $reportOptionItem->duration));
                    $title = "Dynamic Analysis ($date_start - $date_end)";
                    $pdi->table_of_contents = $report->generate->toc;
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
            $reportLogger->debug('swissephe::Year-Framework :- setting order state - ready');
            $orderitem->state = $states['ready'];
            $orderitem->save();

            /* update transaction */
            $reportLogger->debug(sprintf("swissephe::Year-Framework :- creating transaction, order state = %d", $orderitem->state));
            $transaction->state = $orderitem->state;
            $reportLogger->debug(sprintf("swissephe::Year-Framework :- creating transaction, timestamp = %s", strftime("%Y-%m-%d %H:%M:%S")));
            $transaction->timestamp = strftime("%Y-%m-%d %H:%M:%S");
            $reportLogger->debug('swissephe::Year-Framework :- creating transaction, savenew');
            $transaction->savenew();
            $reportLogger->debug('swissephe::Year-Framework :- creating transaction, done');

            /* if delivery method is email then deliver the report */
            $reportLogger->debug('swissephe::Year-Framework :- checking delivery option');
            if ($orderitem->delivery_option == 1 /* email */) {
                $reportLogger->debug('swissephe::Year-Framework :- processing mail delivery');
                switch ($orderitem->portalId) {
                    case C_AFFILIATE_WOW:
                        $reportLogger->debug('swissephe::Year-Framework :- processing mail delivery for WOW(UK)');
                        $email = new WowEmailDelivery_EN();
                        break;

                    default:
                        $reportLogger->debug('swissephe::Year-Framework :- falling through default case');
                        $reportLogger->debug(sprintf("swissephe::Year-Framework :- portal id = %d", $orderitem->portalId));
                        $reportLogger->debug(sprintf("swissephe::Year-Framework :- delivery option = %d", $orderitem->delivery_option));
                        break;
                }
                $email->setOrderId($orderitem->orderId);
                $email->send();
            } else {
                $reportLogger->debug('swissephe::Year-Framework :- no processing mail delivery required');
            } /* mail delivery context */
        } /* end of foreach orderitem */

        /* GC */
        unset($pdi);
        unset($book);
        unset($report);
    }
}
?>