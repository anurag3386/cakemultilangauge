<?php

class BB_DynamicAnalysis extends DynamicAnalysis {
	
	var $MonthArray = array();
	
    function BB_DynamicAnalysis( $type, $data, $start_year, $duration, $debug = false ) {
        parent::DynamicAnalysis( $type, $data, $start_year, $duration, false );
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
     * getTransitStartDate
     *
     * Starting at the start date, check whether the transit is within orb then
     * track forwards until the transit first appears in orb
     *
     * @param int $transit
     * @param int $natal
     * @param int $aspect
     */
    function getTransitStartDate($transit, $natal, $aspect) {
    	global $logger;
    	$logger->debug ( "AstrologChartAPI::getTransitStartDate( $transit, $natal, $aspect )" );
    	$date = $this->m_transit_window [$transit] [$aspect] [$natal] ['start'];
    	return sprintf ( "%s %02d, %04d", $this->MonthArray[intval ( substr ( $date, 4, 2 ) ) ], substr ( $date, 6, 2 ), substr ( $date, 0, 4 ) );
    }
    
    /**
     * getTransitEndDate
     * BB -> date format [mm-dd-yyyy]
     */
    function getTransitEndDate($transit, $natal, $aspect) {
    	global $logger;
    	$logger->debug ( "AstrologChartAPI::getTransitEndDate( $transit, $natal, $aspect )" );
    	$date = $this->m_transit_window [$transit] [$aspect] [$natal] ['end'];
    	
    	
    	return sprintf ( "%s %02d, %04d", $this->MonthArray[intval ( substr ( $date, 4, 2 ) ) ], substr ( $date, 6, 2 ), substr ( $date, 0, 4 ) );
    }
};
?>