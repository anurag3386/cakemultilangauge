<?php

class BB_Forecast extends Forecast {

	var $MonthArray = array();
	
	/**
     * Constructor
     */
    function BB_Forecast() {
        parent::Forecast();
        $this->MonthArray = array(
        		0 => '',
        		1 => 'Jan',
        		2 => 'Feb',
        		3 => 'Mar',
        		4 => 'Apr',
        		5 => 'May',
        		6 => 'Jun',
        		7 => 'Jul',
        		8 => 'Aug',
        		9 => 'Sep',
        		10 => 'Oct',
        		11 => 'Nov',
        		12 => 'Dec');
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
            /* Q&A?		*/      $seasonal = true,
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
//            echo "<pre>Calender Trand <br />";
//            print_r($chart->m_aspect[$i]);
//            echo "</pre>";
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
//                echo "<pre>Calender Trand $calendartrend</pre>";
                $year		= intval( substr($calendartrend,0,4) );	// (YYYY)-mm-dd
                $month		= intval( substr($calendartrend,5,2) );	// yyyy-(MM)-dd
                $day		= intval( substr($calendartrend,8,2) );	// yyyy-mm-(DD)
                $logger->debug(sprintf("%02d-%02d-%04d", $day, $month, $year));
//                 $this->generator->generateSectionHeaderDate(
//                         sprintf("%02d-%02d-%04d", $month, $day, $year)
//                 );
                
                $this->generator->generateSectionHeaderDate(
                		sprintf("%s %02d, %04d", $this->MonthArray[intval($month)], $day, $year)
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
}
?>
