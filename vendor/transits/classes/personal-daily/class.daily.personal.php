<?php
require_once (CLASSPATH . '/objects/class.birthdata.php');          //POG CLASS ADDED
/**
 * DailyPersonalData class hold the actuall data of the Daily pesonal Prediction
 *
 * @author Amit Parmar <parmaramit1111@gmail.com> 
 */
class DailyPersonalData {

    /**
     * This will hold the Prediction date
     * @var Date as String  (MM-dd-YYYY) American Date format
     */
    public $PredictionDate;

    /**
     * Holds the Title of the Prediction text (For e.g Saturn transiting Natal Sun)
     * @var String
     */
    public $Title;

    /**
     * Holds the Prediction description
     * @var String
     */
    public $Description;

    /**
     * Hold the Question related to prediction
     * @var String
     */
    public $Quetion1;

    /**
     * Hold the Answer related to Question 1
     * @var String
     */
    public $Answer1;

    /**
     * Hold the Question related to prediction
     * @var String
     */
    public $Quetion2;

    /**
     * Hold the Answer related to Question 2
     * @var String
     */
    public $Answer2;

    /**
     * Hold the Question related to prediction
     * @var String
     */
    public $Quetion3;

    /**
     * Hold the Answer related to Question 3
     * @var String
     */
    public $Answer3;

    /**
     * Initially setup the Daily Personal data
     *
     * @param String $PredictionDate
     * @param String $Title
     * @param String $Description
     * @param String $Quetion1
     * @param String $Answer1
     * @param String $Quetion2
     * @param String $Answer2
     * @param String $Quetion3
     * @param String $Answer3
     */
    public function __construct ($PredictionDate = '', $Title = '', $Description = '', $Quetion1 = '', $Answer1 = '', $Quetion2 = '', $Answer2 = '', $Quetion3 = '', $Answer3 = '') {
        $this->PredictionDate = $PredictionDate;
        $this->Title = $Title;
        $this->Description = $Description;
        $this->Quetion1 = $Quetion1;
        $this->Answer1 = $Answer1;
        $this->Quetion2 = $Quetion2;
        $this->Answer2 = $Answer2;        
        $this->Quetion3 = $Quetion3;
        $this->Answer3 = $Answer3;
    }
}

/**
 * Description of dailypersonalclass
 *
 * @author Amit Parmar <parmaramit1111@gmail.com>
 */
class DailyPersonal {
    //put your code here

    /**
     * This will holds the Error code
     * @var String
     */
    public $Code;

    /**
     * This will holds the Error Message
     * @var String 
     */
    public $Message;


    /**
     * Holds the Prediction Array
     *
     * @var DailyPersonalData class
     */
    public $DailyPredictionData;    

    public function __construct ($Code, $Message) {
        $this->Code = $Code;
        $this->Message = $Message;

        if($Code == 0) {
            $this->DailyPredictionData = array();
        }
    }
    
    public function AddPredictionData($DailyPersonalData) {
        array_push($this->DailyPredictionData, $DailyPersonalData);        
    }
}

class DailyBirthData extends birthdata {
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
    function DailyBirthData($date, $time, $timed_data, $summertime, $timezone, $longitude, $latitude) {
        /* date */
        $this->day	= intval(substr($date,6,2));
        $this->month	= intval(substr($date,4,2));
        $this->year	= intval(substr($date,0,4));

        /* check month is valid */
        if( $this->month < 1 || $this->month > 12 ) {

        }
        /* check whole date is valid */
        /* TODO */

        /* time */
        $this->timed_data = $timed_data;
        if( $this->timed_data === true ) {
            $this->hour	= intval(substr($time,0,2));
            $this->minute	= intval(substr($time,3,2));
        } else {
            /* noon day chart to reduce error on cuspal chart */
            $this->hour	= 12;
            $this->minute = 0;
        }
        //echo "$summertime =  $timezone<br />";
        /* daylight savings offset */
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

        /* daylight savings offset */
//        $timediff = floatval(abs($summertime));
//        $timediff_hh = substr($timediff,0,2);
//        $timediff_mm = substr($timediff,2,2);
//        $this->m_summertime_offset = ((intval($summertime) < 0) ? '-' : '');
//        $this->m_summertime_offset .= sprintf("%d:%02d", $timediff_hh, $timediff_mm);
        
//        $timedelta = sprintf("%04d", abs($timezone));
//        $timedelta_hh = substr($timedelta,0,2);
//        $timedelta_mm = substr($timedelta,2,2);
//        $this->m_timezone_offset = ((intval($timezone) < 0) ? '-' : '');
//        $this->m_timezone_offset .= sprintf("%d:%02d", $timedelta_hh, $timedelta_mm);

        $this->delta	= floatval(0.0);

        /* coordinates */
        $this->longitude	= floatval($longitude / 3600);
        $this->latitude         = floatval($latitude / 3600);
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
}
?>