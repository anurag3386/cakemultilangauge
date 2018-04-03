<?php
/**
 * Birthdata class 
 * @package Data Generation  of AstrologAPI
 *
 * @author Parmar Amit <parmaramit1111@gmail.com>
 * @version 1.0
 */
class GenerateAstrologBirthData extends BirthData {

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
     *  $date		= YYYYMMDD
     *  $time		= HH:MM
     *  $timed_data       = true/false
     *  $summertime	= +/-HMM
     *  $timezone         = HHMM
     *  $longitude	= DDD.MM
     *  $latitude 	= DD.MM
     */
    function GenerateAstrologBirthData($date, $time, $timed_data, $summertime, $timezone, $longitude, $latitude) {
        /* date */
        $this->day	= intval(substr($date,6,2));
        $this->month	= intval(substr($date,4,2));
        $this->year	= intval(substr($date,0,4));

        /* check month is valid */
        if( $this->month < 1 || $this->month > 12 ) {
            $logger->debug("AstrologBirthData::AstrologBirthData - invalid month");
        }
        /* check whole date is valid */
        /* TODO */

        /* time */
        $this->timed_data = $timed_data;
        if( $this->timed_data === true ) {
            $this->hour         = intval(substr($time,0,2));
            $this->minute	= intval(substr($time,3,2));
        } else {
            /* noon day chart to reduce error on cuspal chart */
            $this->hour         = 12;
            $this->minute	= 0;
        }

        /* daylight savings offset */
//        $timediff = sprintf("%04d", abs($summertime));
//        $timediff_hh = substr($timediff,0,2);
//        $timediff_mm = substr($timediff,2,2);
//        $this->m_summertime_offset = ((intval($summertime) < 0) ? '-' : '');
//        $this->m_summertime_offset .= sprintf("%d:%02d", $timediff_hh, $timediff_mm);
//
//        /* timezone offset */
//        $timedelta = sprintf("%04d", abs($timezone));
//        $timedelta_hh = substr($timedelta,0,2);
//        $timedelta_mm = substr($timedelta,2,2);
//        $this->m_timezone_offset = ((intval($timezone) < 0) ? '-' : '');
//        $this->m_timezone_offset .= sprintf("%d:%02d", $timedelta_hh, $timedelta_mm);

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


        $this->delta	= floatval(0.0);

        /* coordinates */
        $this->longitude = floatval($longitude);
        $this->latitude	 = floatval($latitude);
    }

    /**
     * QaSwitchFormat
     *
     * returns a formatted argument string
     *
     * @todo review this as it is really a low level API formatting function. Perhaps pass the object to the API to etract what it requires.
     */
    function qaSwitchFormat() {
        /* trial */
        $qbStr = sprintf('-qb %d %d %d %d.%02d %s %s %s %s',
                            $this->month, $this->day, $this->year,
                            $this->hour, $this->minute,
                            $this->m_summertime_offset,
                            $this->m_timezone_offset,
                            $this->parseCoords( $this->longitude, true ),
                            $this->parseCoords( $this->latitude, false )
        );        
        return $qbStr;
    }

    private function parseCoords($coord,$long=true) {
        if( $long === true ) {
            $hemi = ($coord < 0) ? 'E' : 'W';
        } else {
            $hemi = ($coord < 0) ? 'S' : 'N';
        }
        $coord = abs($coord);
        $degree = intval($coord);
        $coord = floatval($coord - $degree);
        $minute = intval( floatval($coord * 60.0) );
        $second = 0;        
        return sprintf("%d:%02d:%02d%s",$degree,$minute,$second,$hemi);
    }
};
?>