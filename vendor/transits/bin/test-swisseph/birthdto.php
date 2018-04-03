<?php

class BirthDTO {

    /**
     * @var int $day
     */
    var $day;

    /**
     * @var int $month
     */
    var $month;

    /**
     * @var int $year
     */
    var $year;

    /**
     * @var int $hour Valid range = 0 .. 23
     */
    var $hour;

    /**
     * @var int $minute Valid range = 0 .. 59
     */
    var $minute;

    /**
     * @var bool $timed_data
     */
    var $timed_data;

    /**
     * @var real $m_summertime_offset
     */
    var $m_summertime_offset;

    /**
     * @var real $m_timezone_offset
     */
    var $m_timezone_offset;

    /**
     * @var real $longitude
     */
    var $longitude;

    /**
     * @var real $latitude
     */
    var $latitude;

    /**
     * Constructor
     *
     */
    function BirthDTO(
    $date /* YYYYMMDD */, $time /* HH:MM */, $timed_data /* true/false */, $summertime /* +/-HMM */, $timezone /* HHMM */, $longitude /* DDD.MM */, $latitude /* DD.MM */) {
        global $logger; 
        /*
         * date
         */
        $this->day = intval(substr($date, 6, 2));
        $this->month = intval(substr($date, 4, 2));
        $this->year = intval(substr($date, 0, 4));
        /* check month is valid */
        if ($this->month < 1 || $this->month > 12) {
            $logger->debug("BirthDTO::BirthDTO - invalid month");
        }
        /* check whole date is valid */
        /* TODO */

        /* time */
        $this->timed_data = $timed_data;
        if ($this->timed_data === true) {
            //$logger->debug("BirthDTO::BirthDTO - timed data");
            $this->hour = intval(substr($time, 0, 2));
            $this->minute = intval(substr($time, 3, 2));
        } else {
            /* noon day chart to reduce error on cuspal chart */
            $logger->debug("BirthDTO::BirthDTO - untimed data - solar chart in effect");
            $this->hour = 12;
            $this->minute = 0;
        }

         /* daylight savings offset */
//        $timediff = number_format(floatval(abs($summertime)), 2);
//        $timediff_hh = intval($timediff);
//        $timediff_mm = number_format( substr( $timediff, strpos($timediff, '.', 0) + 1, 2), 2);
        
//        $this->m_summertime_offset = ((intval($summertime) < 0) ? '-' : '');
//        $this->m_summertime_offset .= sprintf("%d:%02d", $timediff_hh, $timediff_mm);

        /* timezone offset */
//        $ZoneValue = abs( number_format(floatval( ($timezone) ), 2) );
//        $tmpZone = intval($ZoneValue);
//        $tmpZoneDiff = number_format( floatval(  $ZoneValue - $tmpZone ), 2 );
//        $FinalZone = $ZoneValue;
//
//        $timedelta =  number_format($tmpZone, 2);
//        $timedelta_hh = $tmpZone;
//        $timedelta_mm = 00;
//
//        if($tmpZoneDiff > 0.0 &&  $tmpZoneDiff <= 0.50 ) {
//            $FinalZone = number_format( floatval( $tmpZone + 0.30 ), 2);
//            $timedelta_mm = 30;
//        }
//        else if($tmpZoneDiff >= 0.51 && $tmpZoneDiff <= 1 ){
//            $FinalZone = number_format( floatval( $tmpZone + 0.45 ), 2);
//            $timedelta_mm = 45;
//        }

        //Adpated Code
//        $timediff_hh = intval( $summertime );
//        $timediff_mm = intval( floatval( floor( ($summertime - intval($summertime)) * 100.0) ) );
//        $timediff_mm = intval( floatval($timediff_mm) * (60.0 / 100.0) );               /* convert to minutes */
//
//        $this->m_summertime_offset = ((intval($summertime) < 0) ? '-' : '');
//        $this->m_summertime_offset .= sprintf("%d:%02d", $timediff_hh, $timediff_mm);
//
//        $timedelta_hh = intval( $timezone );
//        $timedelta_mm = intval( floatval( floor( ($timezone - intval($timezone)) * 100.0) ) );
//        $timedelta_mm = intval( floatval($timedelta_mm) * (60.0 / 100.0) );               /* convert to minutes */
//        $FinalZone = sprintf("%d%.02d", $timedelta_hh, abs($timedelta_mm) );
//
//        $this->m_timezone_offset = ((intval($timezone) < 0) ? '-' : '');
//        $this->m_timezone_offset .= sprintf("%02d:%02d", $timedelta_hh, $timedelta_mm);
       //Adpated Code
  
//        $this->m_summertime_offset = str_replace(".", ":", $summertime);
//        $this->m_timezone_offset = str_replace(".", ":", $timezone);

        $timediff = number_format(floatval(abs($summertime)), 2);
        $timediff_hh = intval($timediff);
        $timediff_mm = number_format( substr( $timediff, strpos($timediff, '.', 0) + 1, 2), 2);
        $this->m_summertime_offset = ((intval($summertime) < 0) ? '-' : '');
        $this->m_summertime_offset .= sprintf("%d:%02d", $timediff_hh, $timediff_mm);

        /* timezone offset */
        $timedelta =  number_format(abs($timezone), 2);
        $timedelta_hh = intval($timedelta);
        $timedelta_mm = number_format( substr($timedelta, strpos($timedelta, '.', 0) + 1, 2), 2);

        $this->m_timezone_offset = ((intval($timezone) < 0) ? '-' : '');
        $this->m_timezone_offset .= sprintf("%02d:%02d", $timedelta_hh, $timedelta_mm);
        //echo "BIRTHDATA :: $timezone :: " . $this->m_timezone_offset;
        $this->delta = floatval(0.0);

        /* coordinates */
        $this->longitude = floatval($longitude);
        $this->latitude = floatval($latitude);
        //$logger->debug("BirthDTO::BirthDTO - ".$this);
    }

    /**
     * QaSwitchFormat
     *
     * returns a formatted argument string
     *
     * @todo review this as it is really a low level API formatting function. 
     * Perhaps pass the object to the API to etract what it requires.
     */
    function qaSwitchFormat() {
        /* trial */
        global $logger;
        //$logger->debug("BirthDTO::qaSwitchFormat - summertime_offset = $this->m_summertime_offset");
        //$logger->debug("BirthDTO::qaSwitchFormat - timezone_offset = $this->m_timezone_offset");
        $qbStr = sprintf('-qb %d %d %d %d.%02d %s %s %s %s', $this->month, $this->day, $this->year, $this->hour, $this->minute, $this->m_summertime_offset, $this->m_timezone_offset, $this->parseCoords($this->longitude, true), $this->parseCoords($this->latitude, false));
        //$logger->debug("BirthDTO::qaSwitchFormat - $qbStr");
        return $qbStr;
    }
    
    function qaSwitchFormatForSolarReturn($ReturnDate, $ReturnTime)
    {
        $ReturnMonth = intval(substr($ReturnDate, 4, 2)); 
        $ReturnDay= sprintf("%02d", intval(substr($ReturnDate, 6, 2)));
        $ReturnYear= intval(substr($ReturnDate, 0, 4)); 
        $ReturnHour = intval(substr($ReturnTime, 0, 2));
        $ReturnMinute = intval(substr($ReturnTime, 3, 2));
        $AMPM = substr($ReturnTime, 5, 2);
                
//          if(strtolower($AMPM) === 'pm')
//          {
//              $ReturnHour =  intval($ReturnHour) - 12;
//          }
        $ReturnHour = intval(DATE("H", STRTOTIME($ReturnTime)));
                
        $ReturnMinute = floatval(DATE("i", STRTOTIME($ReturnTime)));
        $ReturnSecound = 25;
        
        //echo $ReturnTime . ' ' .$ReturnMinute. ' ' . $this->m_summertime_offset . '<br />';
        
        global $logger;
        $logger->debug("BirthDTO::qaSwitchFormatForSolarReturn - summertime_offset = $this->m_summertime_offset");
        $logger->debug("BirthDTO::qaSwitchFormatForSolarReturn - timezone_offset = $this->m_timezone_offset");
        //$qbStr = sprintf('-qb %d %d %d %d.%02d.%02d %s %s %s %s', $ReturnMonth, $ReturnDay, $ReturnYear, $ReturnHour, $ReturnMinute, $ReturnSecound, $this->m_summertime_offset, $this->m_timezone_offset, $this->parseCoords($this->longitude, true), $this->parseCoords($this->latitude, false));
        //This is main line 
        //$qbStr = sprintf('-qb %d %d %d %d.%02d %s %s %s %s', $ReturnMonth, $ReturnDay, $ReturnYear, $ReturnHour, $ReturnMinute, $ReturnSecound, $this->m_summertime_offset, $this->m_timezone_offset, $this->parseCoords($this->longitude, true), $this->parseCoords($this->latitude, false));
        
        //Not working
        //$qbStr = sprintf('-qb %d %d %d %d.%02d.%s %s %s %s', $ReturnMonth, $ReturnDay, $ReturnYear, $ReturnHour, $ReturnMinute, $ReturnSecound, $this->m_summertime_offset, $this->m_timezone_offset, $this->parseCoords($this->longitude, true), $this->parseCoords($this->latitude, false));
        $qbStr = sprintf('-qb %d %d %d %d.%02d %s %s %s %s', $ReturnMonth, $ReturnDay, $ReturnYear, $ReturnHour, $ReturnMinute, $this->m_summertime_offset, $this->m_timezone_offset, $this->parseCoords($this->longitude, true), $this->parseCoords($this->latitude, false));
        $logger->debug("BirthDTO::qaSwitchFormat - $qbStr");
        return $qbStr;
    }
    
	function subtractTime($hours=0, $minutes=0, $seconds=0, $months=0, $days=0, $years=0)	
	{	
		$totalHours = date('H') - $hours;		
		$totalMinutes = date('i') - $minutes;		
		$totalSeconds = date('s') - $seconds;		
		$totalMonths = date('m') - $months;		
		$totalDays = date('d') - $days;		
		$totalYears = date('Y') - $years;
				
		$timeStamp = mktime($totalHours, $totalMinutes, $totalSeconds, $totalMonths, $totalDays, $totalYears);
		
		$myTime = date('Y-m-d H:i:s A', $timeStamp);		
		return $myTime;	
	}

    private function parseCoords($coord, $long=true) {
        global $logger;
        //$logger->debug("BirthDTO::parseCoords - entering");
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
        //$logger->debug("BirthDTO::parseCoords - leaving");
        return sprintf("%d:%02d:%02d%s", $degree, $minute, $second, $hemi);
    }
    
    protected function SetSummerTimeZone()
    {
    	$strSign =  $this->m_timezone_offset;    
    }
}

?>