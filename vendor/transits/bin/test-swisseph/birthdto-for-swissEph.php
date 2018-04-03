<?php

/*
 * BirthDTO (Birth Data transfer object) 
 */
//class BirthDTO extends BirthData {
class BirthDTO {    

    var $birthDay;          // Day of birth
    var $birthMonth;        // Day of Month    
    var $birthYear;         // Day of Year
    var $birthHour;         
    var $birthMinute;
    var $birthSecond;
    var $IsTimedData;
    var $summerTimeOffSet;
    var $timeZoneOffSet;
    var $birthLongitude;
    var $birthLatitude;
    
    //For calulating Time zone differnce
    var $birthTimeDelta;
    var $birthTimeDelta_In_Hours;
    var $birthTimeDelta_In_Minutes;
    var $birthTimeZoneOffSet;
    
//For calulating Time zone differnce
    var $BirthDelta;
    var $TotalTimeDifference;
    var $TimeDefference_IN_Hours;
    var $TimeDefference_IN_Minutes;

    /**
     * Constructor with Parameters
     * $BirthDay        = [ String ]
     * $BirthMonth      = [ String 1 to 12 ]
     * $BirthYear       = [ Four charactor string year like 1982 or 2011 ]
     * $BirthHour       = [ Two charactor string ]
     * $BirthMinute     = [ Two charactor string ]
     * $BirthSecond     = [ Two charactor string ]
     * $IsTimedData     = [ true | false ] 
     * $SummerTime      = [ +/- HMM]
     * $TimeZone        = [ Hours + Minute + Second ]
     * $Longitude       = [ Degree and Minute - DDD.MM ]
     * $Latitude        = [ Degree and Minute - DD.MM ]     
     */
    function BirthDTO($BirthDay, $BirthMonth, $BirthYear, $BirthHour, $BirthMinute, $BirthSecond, $IsTimedData, $SummerTime, $TimeZone, $Longitude, $Latitude) {

        global $reportLogger;
        $reportLogger->debug("User Birth Information Start --------------------------");
        $reportLogger->debug("BirthDTO( BirthDate = " . $BirthDay . "-" . $BirthMonth . "-" . $BirthYear . ")");
        $reportLogger->debug("BirthDTO( BirthTime = " . $BirthMinute . ":" . $BirthSecond . ":" . $BirthSecond . " , Unknow Time = $IsTimedData )");
        $reportLogger->debug("BirthDTO( Summure Time Zone = $SummerTime )");
        $reportLogger->debug("BirthDTO( Time Zone = $TimeZone )");
        $reportLogger->debug("BirthDTO( Longitude = $Longitude, Latitude = $Latitude");

        $this->BirthDelta = floatval(0.0);

        $this->birthDay = intval($BirthDay);
        $this->birthMonth = intval($BirthMonth);
        $this->birthYear = intval($BirthYear);

        $this->IsTimedData = $IsTimedData;
        //Checking for User know the time or Unknow time if Unknow then Generating Chart based on solar chart
        if ($this->IsTimedData == true) {
            $reportLogger->debug("BirthDTO - Know Time");
            $this->birthHour = intval($BirthHour);
            $this->birthMinute = intval($BirthMinute);
            $this->birthSecond = intval($BirthSecond);
        } else {
            $reportLogger->debug("BirthDTO :: Untimed data");
            $this->birthHour = 12;
            $this->birthMinute = 0;
            $this->birthSecond = intval($BirthSecond);
        }

        $this->TotalTimeDifference = sprintf("%04d", abs($SummerTime));
        //Get the hours Time zone
        $this->TimeDefference_IN_Hours = substr($this->TotalTimeDifference, 0, 2);
        //Get the minutes Time zone
        $this->TimeDefference_IN_Minutes = substr($this->TotalTimeDifference, 2, 2);
        //settng up time zone off set
        $this->summerTimeOffSet = ((intval($SummerTime) < 0) ? '-' : '');
        $this->summerTimeOffSet .= sprintf("%d:%02d", $this->TimeDefference_IN_Hours, $this->TimeDefference_IN_Minutes);
        
        $this->birthTimeDelta = sprintf("%04d", abs($TimeZone));
        //Get the hours Time zone
        $this->birthTimeDelta_In_Hours = substr($this->birthTimeDelta, 0, 2);
        //Get the minutes Time zone
        $this->birthTimeDelta_In_Minutes = substr($this->birthTimeDelta, 2, 2);
        //settng up time zone off set
        $this->birthTimeZoneOffSet = ((intval($TimeZone) < 0) ? '-' : '');
        $this->birthTimeZoneOffSet .= sprintf("%d:%02d", $this->birthTimeDelta_In_Hours, $this->birthTimeDelta_In_Minutes);        
        //I forgot, why i have choose this variable duplication
        $this->timeZoneOffSet = ((intval($TimeZone) < 0) ? '-' : '');
        $this->timeZoneOffSet .= sprintf("%d:%02d", $this->birthTimeDelta_In_Hours, $this->birthTimeDelta_In_Minutes);

        $reportLogger->debug("BirthDTO :: summerTimeOffSet = $this->summerTimeOffSet");
        $reportLogger->debug("BirthDTO :: birthTimeZoneOffSet = $this->birthTimeZoneOffSet");

        $this->birthLongitude = floatval($Longitude);
        $this->birthLatitude = floatval($Latitude);

        $reportLogger->debug("User Birth Information End --------------------------");
    }

    /*
     * Generating Parameter List to get planetory position for birth data
     */
    function GenerateParametersList() {
        global $reportLogger;

        $utDateNow = strftime("%d.%m.%Y", mktime($this->birthHour, $this->birthMinute, $this->birthSecond, $this->birthMonth, $this->birthDay, $this->birthYear));
        $utNow = strftime("%H:%M:%S", mktime($this->birthHour, $this->birthMinute, $this->birthSecond, $this->birthMonth, $this->birthDay, $this->birthYear));

        /*
         *  exec("swetest
         *              -edir$sweph
         *              -b$utdatenow
         *              -ut$utnow
         *              -p0123456789DAttt
         *              -eswe
         *              -house$my_longitude,$my_latitude,$h_sys
         *              -flsj -g, -head",
         *              OutPut Param);
         */
        //$qbStr = '-edir -b' . $utdatenow . '-ut' . $utnow . ' -p0123456789DAttt -eswe -house$my_longitude,$my_latitude,$h_sys 
        //-fPlsj -g, -head"';
        $swissPath = ROOTPATH. "/bin/sweph/";        
        $returnParameterList = ' -edir'.$swissPath.' -b' . $utDateNow . '-ut' . $utNow . ' -p0123456789DAttt -eswe';
        $returnParameterList .= ' -house' . $this->formatingLongitude($this->birthLongitude) . ',' . $this->formatingLatitude($this->birthLatitude) . ',p';
        $returnParameterList .= ' -flsjP -g, -head"';

        $reportLogger->debug("BirthDTO::GenerateParametersList - $returnParameterList");
        return $returnParameterList;
    }

    /*
     * Formating Longitude for SwissEmph 
     */
    private function formatingLongitude($strLongitude) {
        $separator = ($strLongitude < 0) ? 'E' : 'W';

        $strLongitude = abs($strLongitude);
        $longitudeDegree = intval($strLongitude);
        $longitudeCoord = floatval($strLongitude - $longitudeDegree);
        $longitudeMinute = intval(floatval($strLongitude * 60.0));
        /*
         * @todo: Need to get the seconds later on
         */
        $longitudeSecond = 0;    
        return sprintf("%d:%02d:%02d%s", $longitudeDegree, $longitudeMinute, $longitudeSecond, $separator);
    }

     /*
     * Formating Latitude for SwissEmph 
     */
    private function formatingLatitude($strLongitude) {
        $separator = ($strLongitude < 0) ? 'S' : 'N';

        $strLongitude = abs($strLongitude);
        $longitudeDegree = intval($strLongitude);
        $longitudeCoord = floatval($strLongitude - $longitudeDegree);
        $longitudeMinute = intval(floatval($strLongitude * 60.0));
        /*
         * @todo: Need to get the seconds later on
         */
        $longitudeSecond = 0;

        return sprintf("%d:%02d:%02d%s", $longitudeDegree, $longitudeMinute, $longitudeSecond, $separator);
    }
}
?>