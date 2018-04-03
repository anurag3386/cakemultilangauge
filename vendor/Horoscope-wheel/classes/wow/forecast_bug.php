<?php
/*
 * File  : $id$
 * Author: Andy Gray <andy.gray@astro-consulting.co.uk>
 * 
 * Description
 * The Forecast class is the base class for managing monthly and annual
 * trends
 * - seasonal extends forecast, duration = months
 * - calendar extends seasonal, duration = years
 * 
 * Development Note
 * - getshorttrend		-> new ShortTrend()
 * - astrogettrendtext	-> new TrendText()	takes	trans, natal, aspect
 * - astrogetquestion	-> new Question()	takes	trans, natal, aspect, sequence
 * - astrogetanswer		-> new Answer()		takes	trans, natal, aspect, sequence
 *
 * Modification History
 * $log$
 * 
*/

/**
 * @package Reports
 * @author Andy Gray <andy.gray@astro-consulting.co.uk>
 * @copyright Copyright (c) 2005-2008, Andy Gray
 * @copyright Copyright (c) 2008, World of Wisdom
 * @version 1.0
 */
class Forecast {

    var $type;				/* type of report, monthly, yearly */
    var $language;			/* report language */
    var	$generator;			/* output text engine */
    var $trend;
    var $m_lastdisplayedtrend;

    /**
     * Constructor
     */
    function Forecast() {
        global $logger;
        $logger->debug("Forecast::Forecast - entering");
        //$this->trend = new ShortTermTrend();
        $this->m_lastdisplayedtrend = '0000-00-00';
        $logger->debug("Forecast::Forecast - leaving");
    }

    /**
     * Monthly Trends with Question and Answers
     *
     * Monthly trends show the trends applicable for each day of the given
     * month with 3 questions and answers that are applicable to each trend.
     * The number of months defaults to 1 month but is configurable
     *
     * Example
     * 03/03/2006
     * Sun transiting conjoins Sun
     * 12:00 Happy Birthday! Some more text goes here
     * Q1 ...
     * A1 ...
     * Q2 ...
     * A2 ...
     * Q3 ...
     * A3 ...
     */
    function run(
    /* birth data	*/	$data,
            /* start date	*/	$start_date,		/* YYYY-MM-DD */
            /* duration		*/	$duration,
            /* Q&A?			*/	$seasonal = true,
            /* language		*/	$language,
            /* generator	*/	$generator
    ) {

        global $top_object1;
        global $transiting_aspects;
        global $logger;

        switch( strtolower( $language ) ) {
            case 'danish':
            case 'dk':
                $this->trend = new ShortTermTrend_DK();
                break;
			case 'sp':
				$this->trend = new Shorttermtrend_SP();
			 break;
            case 'english':
            case 'en':
            default:
                $this->trend = new ShortTermTrend();
                break;
        }

        if( $generator === false ) {
            $this->generator = $this;
        } else {
            $this->generator = $generator;
        }

        /* extract the start data components */
        /* TODO: check whether we start on the day or the start of the next month */
        $year	= intval( substr($start_date,0,4) );
        $month	= intval( substr($start_date,5,2) );
        $day	= intval( substr($start_date,8,2) );

        $chart = new AstrologChartAPI( $data, false );
        $chart->calcCalendarAspects(
                /* birth data	*/	$data,
                /* start year	*/	$year,
                /* duration		*/	2		// (($seasonal === true) ? 2 : ($duration+1))
        );

#	    $planetNames = array(
#			"Sun",		"Moon",
#			"Mercury",	"Venus",	"Mars",
#			"Jupiter",	"Saturn",
#			"Uranus",	"Neptune",	"Pluto",
#			"N. Node",	"S. Node",
#			"Ascendant","MC"
#			);

        $this->generator->generateReportHeader();

        /* iterate through the trends
     * - YYYY-MM-DD HH:MM PPPPAAAPPPP
     * - ----------111111111122222222223
     * - 0123456789012345678901234567890
        */

        for( $i = 0; $i < count($chart->m_aspect); $i++) {

            $calendartrend = $chart->m_aspect[$i];

            /* if the data is prior to the starting date or a late date then skip */
            if( $this->validStart( $calendartrend, $start_date, $duration, $seasonal) === false ) {
                continue;
            }

            /*
       * keep a note of the current trend date.
       * if it is the same as the previous trend then we needn't display it
       * as it would otherwise be repetitive
            */
            if( $this->m_lastdisplayedtrend != substr( $calendartrend, 0, 10 ) ) {
                $year		= intval( substr($calendartrend,0,4) );	// (YYYY)-mm-dd
                $month		= intval( substr($calendartrend,5,2) );	// yyyy-(MM)-dd
                $day		= intval( substr($calendartrend,8,2) );	// yyyy-mm-(DD)
                $logger->debug(sprintf("%02d-%02d-%04d", $day, $month, $year));
                $this->generator->generateSectionHeaderDate(
                        sprintf("%02d-%02d-%04d", $day, $month, $year)
                );
            }
            $this->m_lastdisplayedtrend = substr( $calendartrend, 0, 10 );

            $time	= trim( substr($calendartrend,11,5) );	// YYYY-MM-DD (HH:MM):SS
            $trend	= trim( substr($calendartrend,17) );
            $ptrans = intval( substr($trend,0,4) );
            $pnatal = intval( substr($trend,7,4) );
            $aspect = intval( substr($trend,4,3) );

            /* heading */
            /* TODO - bind to translations in language files - DONE */
            /* NEXT - reduce into a single case with default as error */
            switch( strtoupper($language) ) {
					
                case 'ENGLISH':
                case 'EN':
                    $logger->debug("bind to English language");
                    $logger->debug("ptrans=$ptrans, aspect=".intval(substr($trend,4,3)).", pnatal=$pnatal");
                    $this->generator->generateSectionTitle(
                            sprintf("Transiting %s %s your %s",
                            $top_object1		['en'][ sprintf("%04d", $ptrans	) ],					/* transiting planet	*/
                            $transiting_aspects	['en'][ sprintf("%03d", intval(substr($trend,4,3))) ],	/* aspect				*/
                            $top_object1		['en'][ sprintf("%04d", $pnatal	) ]						/* natal planet			*/
                    ));
                    break;
                case 'DANISH':
                case 'DK':
                    $this->generator->generateSectionTitle(
                            sprintf("Transit %s %s din %s",
                            $top_object1		['dk'][ sprintf("%04d", $ptrans	) ],					/* transiting planet	*/
                            $transiting_aspects	['dk'][ sprintf("%03d", intval(substr($trend,4,3))) ],	/* aspect				*/
                            $top_object1		['dk'][ sprintf("%04d", $pnatal	) ]						/* natal planet			*/
                    ));
                    break;
					case 'SPANISH':
                	case 'SP':
					$this->generator->generateSectionTitle(
                            sprintf("Transit %s %s sp %s",
                            $top_object1		['dk'][ sprintf("%04d", $ptrans	) ],					/* transiting planet	*/
                            $transiting_aspects	['dk'][ sprintf("%03d", intval(substr($trend,4,3))) ],	/* aspect				*/
                            $top_object1		['dk'][ sprintf("%04d", $pnatal	) ]						/* natal planet			*/
                    ));
                    break;
                default:
                    $logger->error("invalid language option");
            }
			
            /* paragraph */
            $logger->debug("calling getTrendText with ptrans=$ptrans, aspect=$aspect, pnatal=$pnatal");

            $this->getTrendText( $ptrans, $pnatal, $aspect );

            /* if this is the seasonal report then we add questions and answers */
            if( $seasonal === true ) {
                for( $sequence = 0; $sequence < 3; $sequence++ ) {
                    $this->getQuestion( $sequence, $ptrans, $pnatal, $aspect );
                    $this->getAnswer( $sequence, $ptrans, $pnatal, $aspect );
                }
            }
        }
        $this->generator->generateReportTrailer();
    }

    function validStart( $calendartrend, $start_date, $duration, $seasonal ) {

        $year		= intval( substr($start_date,0,4) );
        $month		= intval( substr($start_date,5,2) );
        $day		= intval( substr($start_date,8,2) );

        if( $day <= cal_days_in_month(CAL_GREGORIAN, $month, $year) ) {
            $startdate	= mktime(0,0,0, $month, $day, $year);
        } else {
            $startdate	= mktime(0,0,0, $month, cal_days_in_month(CAL_GREGORIAN, $month, $year), $year);
        }

        /* if this is a calendar then we are looking at duration in years, else in months */
        if( $seasonal === true ) {
            /* seasonal, therefore duration is in months */
            $month += $duration;
            if( $month > 12 ) {
                $month -= 12;
                $year++;
            }
            if( $day <= cal_days_in_month(CAL_GREGORIAN, $month, $year) ) {
                $enddate = mktime(0,0,0, $month, $day, $year);
            } else {
                $enddate = mktime(0,0,0, $month, cal_days_in_month(CAL_GREGORIAN, $month, $year), $year);
            }
        } else {
            /* calendar, therefore duration can be 1, 3, 6 or 12 months */
            $month += $duration;
            if( intval($duration) == 12 ) {
                $year++;
            }
            if( $day <= cal_days_in_month(CAL_GREGORIAN, $month, $year) ) {
                $enddate = mktime(0,0,0, $month, $day, $year);
            } else {
                $enddate = mktime(0,0,0, $month, cal_days_in_month(CAL_GREGORIAN, $month, $year), $year);
            }
        }

        $year		= intval( substr($calendartrend,0,4) );
        $month		= intval( substr($calendartrend,5,2) );
        $day		= intval( substr($calendartrend,8,2) );
        $trenddate	= mktime(0,0,0,$month,$day,$year);

        /* trend must be after the start date and before the end date */
        if( intval($trenddate) < intval($startdate) ) {
            return false;
        }

        if( intval($trenddate) > intval($enddate) ) {
            return false;
        }
        return true;
    }

    function getAspect( $text ) {
        /* look for the aspect */
        switch( intval(substr($text,4,3)) ) {
            case 0:	return	'Conjoins';
            case 60:	return	'Positive';
            case 90:	return	'Challenges';
            case 120:	return	'Positive';
            case 180:	return	'Challenges';
            default:
                $logger->debug("getAspect: text=$text");
                $logger->fatal("invalid aspect in getAspect");
                die("invalid aspect in getAspect");
        }
    }

    function getTrendText( $trans, $natal, $aspect ) {

        if( $trans == 1001 /* Moon */ ) {
            // error
        }

        /* patch for single node entry, so 1010 = node, 1011 = asc, 1012 = mc */
        if( $natal > 1010 /* nodes */ ) {
            $natal--;
        }

        switch( $aspect ) {
            case 0:				$aspindex = 0;
                break;
            case 60: case 120:	$aspindex = 1;
                break;
            case 90: case 180:	$aspindex = 2;
                break;
            default:
            // error
        }

        if( $trans == 1000 /* Sun */ ) {
            $trendindex = (($aspindex * 13) + ($natal - 1000));
        } else {
            /* Mercury to Pluto */
            $trendindex = (39 + ((($trans - 1002) * 39) + ($aspindex * 13) + ($natal - 1000)));
        }

        $this->trend->Get( intval($trendindex+1) );
        $this->generator->generateSectionContent( $this->trend->trendtext );
    }

    function getQuestion( $sequence, $trans, $natal, $aspect ) {
        // Removed at request of ARD
        //$content  = sprintf("Q%d. ",$sequence+1);
        switch($sequence+1) {
            case 1:	$content = $this->trend->txtques1;
                break;
            case 2:	$content = $this->trend->txtques2;
                break;
            case 3:	$content = $this->trend->txtques3;
                break;
        }
        $this->generator->generateSectionContent( $content );
    }

    function getAnswer( $sequence, $trans, $natal, $aspect ) {
        // Removed at request of ARD
        //$content  = sprintf("A%d. ",$sequence+1);
        switch($sequence+1) {
            case 1:	$content = $this->trend->txtans1;
                break;
            case 2:	$content = $this->trend->txtans2;
                break;
            case 3:	$content = $this->trend->txtans3;
                break;
        }
        $this->generator->generateSectionContent( $content );
    }

    function generateReportHeader() {
        // pass
    }

    function generateSectionHeaderDate( $datetime ) {
        //$this->logger->debug( $datetime );
        //echo "<p><strong>$datetime</strong></p>\n";
        echo $datetime . " ";
    }

    function generateSectionTitle( $title = "TODO: Title" ) {
        //$this->logger->debug( $title );
        //echo "<p><em>$title</em></p>\n";
        echo $title . "\n";
    }

    function generateSectionContent( $content ) {
        //$this->logger->debug( $content );
        //echo "<p>$content</p>\n";
        echo $content . "\n";
    }

    function generateReportTrailer() {
    }
}
?>
