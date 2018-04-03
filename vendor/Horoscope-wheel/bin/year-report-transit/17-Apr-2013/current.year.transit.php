<?php
/** 
 *
 * @name: current.year.transit.php
 * @package: Curren Year Transit list
 *  
 *
 * @copyright: Astrowow and World-Of-Wisdom Inc.,
 *
 * Calculate the Current Year transit for the Each User
 */
//extends CommonPDFHelper
class CurrentYearTransit  {

    var $astrologService;
    var $bDay;
    var $bMonth;
    var $bYear;
    var $orderDate;
    var $start_date = '';
    var $end_date = '';
    var $startYear;
    var $startEnd;
    var $transit_window = array();
    var $transit_window_Next = array();
    var $transit_window_node = array();
    var $xaxis = array();
    var $yaxis = array();

    var $TopAspects = array();

    var $PlanetsOrbs = array(
            "1005" => 10, //"Jupiter",
            "1006" => 7,  //"Saturn",
            "1007" => 5,  //"Uranus",
            "1008" => 3,  //"Neptune",
            "1009" => 3,  //"Pluto"
            "1010" => 3,  //"NNode"
            "1011" => 3,  //"SNode"
            "1012" => 3,  //"AS"
            "1013" => 3,  //"DS"
            "1014" => 3,  //"MC"
            "1015" => 3,  //"IC"
            "1016" => 3   //"Chiron"
    );

    var $NameOfPlanets = array(
            "1000" => "Sun",
            "1001" => "Moon",
            "1002" => "Mercury",
            "1003" => "Venus",
            "1004" => "Mars",
            "1005" => "Jupiter",
            "1006" => "Saturn",
            "1007" => "Uranus",
            "1008" => "Neptune",
            "1009" => "Pluto",
            "1010" => "N.Node",
            "1011" => "S.Node", /* put here to stop the nag messages, not used */
            "1012" => "Ascendant",
            "1013" => "Midheaven", /* "MC", */
            "1014" => "IC",
            "1015" => "Descendant",
            //Houses
            "0001" => "1st House",
            "0002" => "2nd House",
            "0003" => "3rd House",
            "0004" => "4th House",
            "0005" => "3th House",
            "0006" => "6th House",
            "0007" => "7th House",
            "0008" => "8th House",
            "0009" => "9th House",
            "0010" => "10th House",
            "0011" => "11th House",
            "0012" => "12th House");

    var $NameOfAspects = array(
            "000" => "Conjunction",
            "060" => "Sextile",
            "090" => "Square",
            "120" => "Trine",
            "180" => "Opposition");

    var $NameOfHouses = array(
            "0001" => "1st House",
            "0002" => "2nd House",
            "0003" => "3rd House",
            "0004" => "4th House",
            "0005" => "3th House",
            "0006" => "6th House",
            "0007" => "7th House",
            "0008" => "8th House",
            "0009" => "9th House",
            "0010" => "10th House",
            "0011" => "11th House",
            "0012" => "12th House");
    
    var $PreviousYear;
    var $NextYear;
    var $CurrntYear;

    function CurrentYearTransit($birthDTO, $ordDate) {
        echo "<pre>*************************************************** CurrentYearTransit()</pre>";
        echo $ordDate->format('Y-m-d H:i:s');        
        $this->orderDate = $ordDate;

        $start_date = '';
        $end_date = '';
        $transit_window = array();

        $this->bDay = $birthDTO->day;
        $this->bMonth = $birthDTO->month;
        $this->bYear = $birthDTO->year;

        $astrologService = new AstrologServices($birthDTO);

        $this->startYear = $ordDate->format('Y');         //Actual Code
        $this->startEnd = $ordDate->format('Y') + 2;      //Actual Code

//                                                  MM = Month          DD = Day            YYYY = Year
        $this->PreviousYear = date("Y-m-d", mktime ( 0, 0, 0, $this->bMonth, $this->bDay, $this->startYear - 1 ) );
        $this->NextYear = date("Y-m-d", mktime ( 0, 0, 0, $this->bMonth, $this->bDay, $this->startYear + 1 ) );
        $this->CurrntYear = date("Y-m-d", mktime ( 0, 0, 0, $this->bMonth, $this->bDay, $this->startYear ) );

        $astrologService->calcEphemeris($ordDate->format('Y') -1, 4);   //Actual Code

        /* look for transits */        
        $astrologService->calcDynamicAspects($birthDTO, $this->startYear - 1, 4);

        //Listing Current Year Transit
        $CollectivePlanet = array("1005", "1006", "1007", "1008", "1009");
        $CheckDuplicate = false;
        foreach ($astrologService->m_transit as $transit) {
            $CheckDuplicate = false;

            $transiting_planet = substr($transit, 17, 4);
            $transiting_aspect = substr($transit, 21, 3);
            $natal_planet = substr($transit, 24, 4);

            if(in_array ($transiting_planet , $CollectivePlanet)) {
                $tDate = trim( substr($transit, 0, 10) );
                //									     MM = Month             DD = Day               YYYY = Year
                $tDate = date("Y-m-d", mktime ( 0, 0, 0, substr( $tDate, 8, 2), substr( $tDate, 5, 2), substr( $tDate, 0, 4) ));

                $startEndDate = array();
                $startEndDate = $this->findTransits($transiting_planet, $transiting_aspect, $natal_planet, $astrologService, substr($transit, 0, 10));

                $retunPlanet = $this->CheckDCandICTransit($transiting_planet, $natal_planet ,$transiting_aspect);

                if(count($retunPlanet) > 0) {
                    $natal_planet = $retunPlanet[0];
                    $transiting_aspect =$retunPlanet[1];
                }

                reset($this->transit_window);

                foreach($this->transit_window as $Key => $Item) {
                    if($Item['pt'] == $transiting_planet && $Item['asp'] == $transiting_aspect && $Item['pn'] == $natal_planet &&  $Item['hitdate']  >= $this->CurrntYear) {
                        $CheckDuplicate = true;
                        break;
                    }
                }

                if($CheckDuplicate == false) {
                    if($startEndDate['start'] != '' && $startEndDate['end']  != '') {
                        array_push($this->transit_window, array("pt" => $transiting_planet,
                                "asp" => $transiting_aspect,
                                "pn" => $natal_planet,
                                "planetrank" => $startEndDate['planetrank'], 	//Planet rank to set Priority,
                                "aspectrank" => $startEndDate['aspectrank'], 	//Aspect rank to set Priority,
                                "start" => $startEndDate['start'], 				//$start_date,
                                "end" => $startEndDate['end'], 					//$end_date,
                                "hitdate" => $tDate,
                                'isplanet' => 1,
                                'aspecttype' => 'TR',
                                'totalrank' => $startEndDate['totalrank'],
                                "test" => array(
                                        'test' => $transiting_planet . '-' . $transiting_aspect . '-' . $natal_planet . '-' .substr($transit, 0, 10). '-'. $startEndDate['totalrank'],
                                        "hitcounter" => $startEndDate['hitcounter'],
                                        'isplanet' => 1)));
                    }
                }
            }
        }

        $astrologService->calcCrossingAspects($birthDTO, $this->startYear - 1, 5);

        //NEW CODE FOR TRANSIT WINDOWS WRT CUSPS
        foreach ($astrologService->m_crossing as $transit) {
            $CheckDuplicate = false;
            $transiting_planet = substr($transit, 17, 4);
            $transiting_aspect = substr($transit, 21, 3);
            $natal_cusp = substr($transit, 24, 4);

            $tDate = trim( substr($transit, 0, 10) );
//									     MM = Month             DD = Day               YYYY = Year
            $tDate = date("Y-m-d", mktime ( 0, 0, 0, substr( $tDate, 8, 2), substr( $tDate, 5, 2), substr( $tDate, 0, 4) ));

            $startEndDate = array();
            $startEndDate = $this->findTransits($transiting_planet, $transiting_aspect, $natal_cusp, $astrologService, substr($transit, 0, 10));

            reset($this->transit_window);

            foreach($this->transit_window as $Key => $Item) {
                if($Item['pt'] == $transiting_planet && $Item['asp'] == $transiting_aspect && $Item['pn'] == $natal_cusp && $Item['hitdate'] >= $this->CurrntYear) {
                    $CheckDuplicate = true;
                    break;
                }
            }

            if($CheckDuplicate == false) {
                array_push($this->transit_window, array("pt" => $transiting_planet,
                        "asp" => $transiting_aspect,
                        "pn" => $natal_cusp,
                        "planetrank" => $startEndDate['planetrank'], 	//Planet rank to set Priority,
                        "aspectrank" => $startEndDate['aspectrank'], 	//Aspect rank to set Priority,
                        "start" => $startEndDate['start'], 				//$start_date,
                        "end" => $startEndDate['end'], 					//$end_date,
                        "hitdate" => $tDate,
                        'isplanet' => 0,
                        'aspecttype' => 'TR',
                        'totalrank' => $startEndDate['totalrank'],
                        "test" => array(
                                'test' => $transiting_planet . '-' . $transiting_aspect . '-' . $natal_cusp . '-' .substr($transit, 0, 10). '-'. $startEndDate['totalrank'],
                                "hitcounter" => $startEndDate['hitcounter'],
                                'isplanet' => 0)));
            }

        }

        //Setting the Priorities for Transit
        $this->sortTransitByPlanetNameAndAspects();

        //Calculating North Node House crossing
        $this->GetNodeHouseCrossing($birthDTO);

        //Setup up the Global Variable to design report text
        //global $Global_Natal_TransitSortedList;		//Sorted Transit list with point
        global $Global_Natal_Transit_Window;			//Transit list of 2 year
        global $Global_Natal_m_transit;					//Transit list of 2 year
        global $Global_Natal_m_crossing;				//House crossing list of 2 year

        //$Global_Natal_TransitSortedList = $this->TopAspects;
        $Global_Natal_Transit_Window = $this->transit_window;		//Both House and Planet Crossing
        $Global_Natal_m_transit = $astrologService->m_transit;		//Planet Crossing
        $Global_Natal_m_crossing = $astrologService->m_crossing;	//House Crossing
        //Setup up the Global Variable to design report text

        //FOR SATURN AND JUPITAR
//        $astrologService->calcHouseCrossingAspects($birthDTO, $this->startYear - 2, 10);
//        $this->GenerateHouseCrossingList($astrologService->SAandJUCrossing);
    }

    function sortTransits($astrologService) {
        /* sort the transits, removing duplicates along the way */
        $sortedtransits = array();
        for ($aspect = 0; $aspect < count($astrologService->m_transit); $aspect++) {
            $sortedtransits[substr($astrologService->m_transit[$aspect], 17, 11)] = $astrologService->m_transit[$aspect];
        }
        ksort($sortedtransits);

        unset($astrologService->m_transit);
        $astrologService->m_transit = array();
        reset($sortedtransits);
        while (list($key, $value) = each($sortedtransits)) {
            array_push($astrologService->m_transit, $value);
        }
    }

    function sortCrossings($astrologService) {
        /* sort the transits */
        $sortedtransits = array();
        for ($aspect = 0; $aspect < count($astrologService->m_crossing); $aspect++) {
            $sortedtransits[substr($astrologService->m_crossing[$aspect], 17, 11)] = $astrologService->m_crossing[$aspect];
        }
        krsort($sortedtransits);
        //print_r($sortedtransits);

        unset($astrologService->m_crossing);
        $astrologService->m_crossing = array();
        for ($aspect = 0; $aspect < count($sortedtransits); $aspect++) {
            array_push(
                    $astrologService->m_crossing, array_pop($sortedtransits)
            );
        }
    }
    
    function min_mod() {
        $args = func_get_args();

        if (!count($args[0]))
            return false;
        else {
            $min = false;
            foreach ($args[0] AS $value) {
                if (is_numeric($value)) {
                    $curval = floatval($value);
                    if ($curval < $min || $min === false)
                        if ($curval > -1)
                            $min = $curval;
                }
            }
        }

        return $min;
    }

    function max_mod() {
        $args = func_get_args();

        if (!count($args[0]))
            return false;
        else {
            $max = false;
            foreach ($args[0] AS $value) {
                if (is_numeric($value)) {
                    $curval = floatval($value);
                    if ($curval > $max || $max === false)
                        $max = $curval;
                }
            }
        }

        return $max;
    }

    /*
	 * findTransits
	* - find start and end dates
	* - generate dynamic image trend data
    */
    function findTransits($transiting_planet, $transiting_aspect, $natal_planet, $astrologService, $hittingDate) {
        //echo "<pre>*************************************************** findTransits()</pre>";
        global $logger;
        global $AspectRank;
        global $NatalPlanetRank;
        global $TransitingPlanetRank;

        $RankNumber = 6;
        $RankForPlanet = 6;
        $RankForMultipleOccurrence = 0;
        $TP_Rank = 0;
        $NP_Rank = 0;
        $ASP_Rank = 0;

        $start_date;
        $end_date;

        //Tracking the Total transit in current year
        $DateArray = array();
        $RankForMultipleOccurrence = 0;
        $checkCount = $transiting_planet . $transiting_aspect . $natal_planet;

        if(is_array($astrologService->m_transit)) {
            foreach ($astrologService->m_transit as $transit) {
                $tDate = trim( substr($transit, 0, 10) );
                //									     MM = Month             DD = Day               YYYY = Year
                $tDate = date("Y-m-d", mktime ( 0, 0, 0, substr( $tDate, 8, 2), substr( $tDate, 5, 2), substr( $tDate, 0, 4) ));

                if($checkCount == substr($transit, 17, 11)) {
                    if( $tDate >= $this->CurrntYear && $tDate < $this->NextYear ) {
                        array_push($DateArray, $tDate);
                        $RankForMultipleOccurrence++;

                        if($checkCount == '10090901009') {
                            $GLOBALS['IsPlutoSquare'] = true;
                        }
                    }
                }
            }
        }

        if($RankForMultipleOccurrence >= 1 && $RankForMultipleOccurrence < 2 ) {
            if($transiting_planet == '1006' && $transiting_planet == '1005' ) { //Saturn and Jupiter (2 or 3 time)
                $RankForMultipleOccurrence = 1;
            }
            else {
                $RankForMultipleOccurrence = 1;
            }
        }

        else if($RankForMultipleOccurrence >= 2 && $RankForMultipleOccurrence < 3) {
            $RankForMultipleOccurrence = 3;
        }
        else if($RankForMultipleOccurrence >= 3 ) {
            $RankForMultipleOccurrence = 5;
        }

        //Tracking the Total transit in current year
        $pl_array = array('Sun', 'Moon', 'Mercury', 'Venus', 'Mars', 'Jupiter', 'Saturn', 'Uranus', 'Neptune', 'Pluto', 'NNode', 'SNode', 'Ascendant', 'Midheaven', 'IC', 'Descendant', 'Chiron');

        /*
		 * sort out the window that we are looking for
		* this depends on both the aspect (approaching or separating) and the orb in use
        */
        if ($natal_planet >= 1000) {
            if ($natal_planet < 1012 /* ASC */) {
                $natal = floatval($astrologService->m_object[$pl_array[$natal_planet - 1000]]['longitude']);
            } else {
                if ($natal_planet == 1012 /* ASC */) {
                    $natal = floatval($astrologService->m_object['cusp'][1]);
                }
                if ($natal_planet == 1013 /* MC */) {
                    $natal = floatval($astrologService->m_object['cusp'][10]);
                }
            }
        } else {
            if (intval($natal_planet) < 100) {
                /* if the object value < 100 then this is a house cusp transit */
                $natal = floatval($astrologService->m_object['cusp'][intval($natal_planet)]);
            }
        }

        $orbSetup = $this->PlanetsOrbs[$transiting_planet];
        $transit_lo_app = ($natal - floatval($transiting_aspect)) - $orbSetup;      //- 3.0;
        if ($transit_lo_app < 0.0) {
            $transit_lo_app += 360.0;
        }
        $transit_hi_app = ($natal - floatval($transiting_aspect)) + $orbSetup;      //+ 3.0;
        if ($transit_hi_app < 0.0) {
            $transit_hi_app += 360.0;
        }
        $transit_lo_sep = ($natal + floatval($transiting_aspect)) - $orbSetup;      //- 3.0;
        if ($transit_lo_sep > 360.0) {
            $transit_lo_sep -= 360.0;
        }
        $transit_hi_sep = ($natal + floatval($transiting_aspect)) + $orbSetup;      //+ 3.0;
        if ($transit_hi_sep > 360.0) {
            $transit_hi_sep -= 360.0;
        }

        unset($start_date);
        /* this is for the graph */
        unset($maxOrb);
        unset($ZeroLevel);
        unset($minOrb);
        unset($avg);
        unset($TestMaxOrb);

        $maxOrb = max($transit_hi_app, $transit_hi_sep);
        $ZeroLevel = $maxOrb - $orbSetup;
        $minOrb = $ZeroLevel;
        $avg = ($maxOrb + $minOrb) / 2;
        $TestMaxOrb = array();
        $xaxis = array();
        $yaxis = array();
        /* this is for the graph */

        if (is_array($astrologService->m_ephemeris)) {
            reset($astrologService->m_ephemeris);
        }
        $EndDatesArray = array();

        /* scan the ephemeris table */
        for ($i = 0; $i < count($astrologService->m_ephemeris); $i++) {

            $line = each($astrologService->m_ephemeris);

            /* get the date */
            $date = $line[0];

            $year = intval(trim(substr($date, 0, 4)));
            $month = intval(trim(substr($date, 4, 2)));
            $day = intval(trim(substr($date, 6, 2)));
            $col1 = date("Y-m-d", mktime ( 0, 0, 0, $month, $day, $year ));
            $xDate = date("Y-m-d", mktime ( 0, 0, 0, $month, $day, $year ));

            $AllPlanetKey  = array_keys($line[1]);

            /* get the longitude of the transiting planet */
            if(in_array($pl_array[$transiting_planet - 1000], $AllPlanetKey)) {

                $transit = round(floatval($line[1][$pl_array[$transiting_planet - 1000]]['longitude'] - 360.0), 1);

                $transit_lo_app = round(min($transit_lo_app , $transit_hi_app), 1);
                $transit_hi_app = round(max($transit_lo_app , $transit_hi_app), 1);

                $transit_lo_sep = round(min($transit_lo_sep , $transit_hi_sep), 1);
                $transit_hi_sep = round(max($transit_lo_sep , $transit_hi_sep), 1);

                //Changed 03-Oct-2012
                if ( ($transit >= $transit_lo_app && $transit <= $transit_hi_app) ||
                        ($transit >= $transit_lo_sep && $transit <= $transit_hi_sep) ) {

                    $logger->debug("-->>> $date - in orb, transit = $transit");
                    if (isset($start_date) === false) {
                        $start_date = $col1;
                    }

                    $TP_Rank = $TransitingPlanetRank[$transiting_planet];
                    $NP_Rank = $NatalPlanetRank[$natal_planet];

                    if($transiting_aspect == '180' && $natal_planet == '1012') {
                        $ASP_Rank = $AspectRank['000'];
                    }
                    else if($transiting_aspect == '180' && $natal_planet == '1013') {
                        $ASP_Rank = $AspectRank['000'];
                    }
                    else {
                        $ASP_Rank = $AspectRank[$transiting_aspect];
                    }

                    if(array_key_exists($transiting_planet, $TransitingPlanetRank)) {
                        $RankForPlanet = $TransitingPlanetRank[$transiting_planet];
                    }

                    if (in_array($natal_planet, $NatalPlanetRank)) {
                        $RankNumber = $AspectRank[$transiting_aspect];
                    }

                    /* keep a running track of the end date */
                    $end_date = $col1;

                    if(count($EndDatesArray) > 1 ) {
                        $EndDatesArray[count($EndDatesArray) - 1] = $end_date;
                    }
                    else {
                        $EndDatesArray[0] = $end_date;
                    }

                    if(array_key_exists($hittingDate, $DateArray)) {
                        $NextStartDate  = $DateArray[$hittingDate];
                        $NextStartDate  = next($DateArray);
                    }

                    unset($col2);
                    unset($orb);
                    unset($maxOrb);
                    unset($ZeroLevel);
                    unset($minOrb);
                    $orb = $transit;
                    $maxOrb;

                    if ($transit >= $transit_lo_app && $transit <= $transit_hi_app) {
                        $maxOrb = max($transit_hi_app, $transit_lo_app);
                    }
                    else if($transit >= $transit_lo_sep && $transit <= $transit_hi_sep) {
                        $maxOrb = max($transit_lo_sep, $transit_hi_sep);
                    }

                    $ZeroLevel = round($maxOrb - $orbSetup, 2);
                    $minOrb = $ZeroLevel;

                    if ($orb >= $ZeroLevel) {
                        $orb = $orb - $ZeroLevel;
                        array_push($TestMaxOrb, $orb);
                    } else {
                        $orb = $ZeroLevel - $orb;
                    }
                }
            }
        }

        $start_date = isset($start_date) ? $start_date : '';
        if(count($EndDatesArray) > 0 ) {
            sort($EndDatesArray);
            $end_date = $EndDatesArray[0];
        }
        else {
            $end_date = isset($end_date) ? $end_date : '';
        }

        $TotalRank = intval($TP_Rank) + intval($NP_Rank) + intval($ASP_Rank) + intval($RankForMultipleOccurrence);

        return array("start" => $start_date, "end" => $end_date, "hitcounter" => $DateArray,
                "totalrank" => $TotalRank, "aspectrank" => $RankNumber, "planetrank" => $RankForPlanet);
    }

    /**
     *
     * @param Number $transiting_planet
     * @param Number $transiting_aspect
     * @param Number $natal_planet
     * @param Class $astrologService
     * @param Date  $hittingDate
     * @return Array <string, unknown> Ambigous <number, string>
     */
    function findTransitsNextYear($transiting_planet, $transiting_aspect, $natal_planet, $astrologService, $hittingDate) {
        global $logger;
        global $AspectRank;
        global $NatalPlanetRank;
        global $TransitingPlanetRank;

        $RankNumber = 6;
        $RankForPlanet = 6;
        $RankForMultipleOccurrence = 0;
        $TP_Rank = 0;
        $NP_Rank = 0;
        $ASP_Rank = 0;

        $nextnewdate = strtotime ( '+1 year' , strtotime (  $this->NextYear ) ) ;
        $nextnewdate = date ( 'Y-m-d' , $nextnewdate );
        $NextToNextYear = $nextnewdate;

        $start_date;
        $end_date;

        $pl_array = array('Sun', 'Moon', 'Mercury', 'Venus', 'Mars', 'Jupiter', 'Saturn', 'Uranus', 'Neptune', 'Pluto', 'NNode', 'SNode', 'Ascendant', 'Midheaven', 'IC', 'Descendant', 'Chiron');

        /*
		 * sort out the window that we are looking for
		* this depends on both the aspect (approaching or separating) and the orb in use
        */
        if ($natal_planet >= 1000) {
            if ($natal_planet < 1012 /* ASC */) {
                $natal = floatval($astrologService->m_object[$pl_array[$natal_planet - 1000]]['longitude']);
            } else {
                if ($natal_planet == 1012 /* ASC */) {
                    $natal = floatval($astrologService->m_object['cusp'][1]);
                }
                if ($natal_planet == 1013 /* MC */) {
                    $natal = floatval($astrologService->m_object['cusp'][10]);
                }
            }
        } else {
            if (intval($natal_planet) < 100) {
                /* if the object value < 100 then this is a house cusp transit */
                $natal = floatval($astrologService->m_object['cusp'][intval($natal_planet)]);
            }
        }

        $orbSetup = $this->PlanetsOrbs[$transiting_planet];
        $transit_lo_app = ($natal - floatval($transiting_aspect)) - $orbSetup;      //- 3.0;
        if ($transit_lo_app < 0.0) {
            $transit_lo_app += 360.0;
        }
        $transit_hi_app = ($natal - floatval($transiting_aspect)) + $orbSetup;      //+ 3.0;
        if ($transit_hi_app < 0.0) {
            $transit_hi_app += 360.0;
        }
        $transit_lo_sep = ($natal + floatval($transiting_aspect)) - $orbSetup;      //- 3.0;
        if ($transit_lo_sep > 360.0) {
            $transit_lo_sep -= 360.0;
        }
        $transit_hi_sep = ($natal + floatval($transiting_aspect)) + $orbSetup;      //+ 3.0;
        if ($transit_hi_sep > 360.0) {
            $transit_hi_sep -= 360.0;
        }

        unset($start_date);

        if (is_array($astrologService->m_ephemeris)) {
            reset($astrologService->m_ephemeris);
        }
        $EndDatesArray = array();

        /* scan the ephemeris table */
        for ($i = 0; $i < count($astrologService->m_ephemeris); $i++) {

            $line = each($astrologService->m_ephemeris);

            /* get the date */
            $date = $line[0];

            $year = intval(trim(substr($date, 0, 4)));
            $month = intval(trim(substr($date, 4, 2)));
            $day = intval(trim(substr($date, 6, 2)));
            $col1 = date("Y-m-d", mktime ( 0, 0, 0, $month, $day, $year ));

            $AllPlanetKey  = array_keys($line[1]);
            /* get the longitude of the transiting planet */
            if(in_array($pl_array[$transiting_planet - 1000], $AllPlanetKey)) {

                $transit = floatval($line[1][$pl_array[$transiting_planet - 1000]]['longitude'] - 360.0);

                $transit_lo_app = round(min($transit_lo_app , $transit_hi_app), 1);
                $transit_hi_app = round(max($transit_lo_app , $transit_hi_app), 1);

                $transit_lo_sep = round(min($transit_lo_sep , $transit_hi_sep), 1);
                $transit_hi_sep = round(max($transit_lo_sep , $transit_hi_sep), 1);

                //Changed 03-Oct-2012
                if ( ($transit >= $transit_lo_app && $transit <= $transit_hi_app) ||
                        ($transit >= $transit_lo_sep && $transit <= $transit_hi_sep) ) {

// 				if ((($transit >= $transit_lo_app && $transit <= $transit_hi_app) || ($transit >= $transit_hi_app && $transit <=  $transit_lo_app)) ||
// 						(($transit >= $transit_lo_sep && $transit <= $transit_hi_sep) || ($transit >= $transit_hi_sep && $transit <= $transit_lo_sep))) {
                    $logger->debug("-->>> $date - in orb, transit = $transit");

                    if (isset($start_date) === false) {
                        //$start_date = $date;
                        $start_date = date("Y-m-d", mktime ( 0, 0, 0, $month, $day, $year ));
                    }

                    $TP_Rank = $TransitingPlanetRank[$transiting_planet];
                    $NP_Rank = $NatalPlanetRank[$natal_planet];

                    if($transiting_aspect == '180' && $natal_planet == '1012') {
                        $ASP_Rank = $AspectRank['000'];
                    }
                    else if($transiting_aspect == '180' && $natal_planet == '1013') {
                        $ASP_Rank = $AspectRank['000'];
                    }
                    else {
                        $ASP_Rank = $AspectRank[$transiting_aspect];
                    }

                    if(array_key_exists($transiting_planet, $TransitingPlanetRank)) {
                        $RankForPlanet = $TransitingPlanetRank[$transiting_planet];
                    }

                    if (in_array($natal_planet, $NatalPlanetRank)) {
                        $RankNumber = $AspectRank[$transiting_aspect];
                    }

                    /* keep a running track of the end date */
                    $end_date = $col1;

                    if(count($EndDatesArray) > 1 ) {
                        $EndDatesArray[count($EndDatesArray) - 1] = $end_date;
                    }
                    else {
                        $EndDatesArray[0] = $end_date;
                    }

                    //$orb = $transit - $natal;
                    $orb = $transit;
                    $col2 = $orb;

                    unset($maxOrb);
                    unset($ZeroLevel);
                    unset($minOrb);

                    $col2 = $orb;
                }
            }
        }

        $start_date = isset($start_date) ? $start_date : '';
        if(count($EndDatesArray) > 0 ) {
            sort($EndDatesArray);
            $end_date = $EndDatesArray[0];
        }
        else {
            $end_date = isset($end_date) ? $end_date : '';
        }
        $DateArray = array();


        $RankForMultipleOccurrence = 0;
        $checkCount = $transiting_planet . $transiting_aspect . $natal_planet;

        foreach ($astrologService->m_transit as $transit) {
            $tDate = trim( substr($transit, 0, 10) );
            //									     MM = Month             DD = Day               YYYY = Year
            $tDate = date("Y-m-d", mktime ( 0, 0, 0, substr( $tDate, 8, 2), substr( $tDate, 5, 2), substr( $tDate, 0, 4) ));

            if($checkCount == substr($transit, 17, 11)) {
                if( $tDate >= $this->NextYear && $tDate < $NextToNextYear ) {
                    array_push($DateArray, $tDate);
                    $RankForMultipleOccurrence++;
                }
            }
        }

        if($RankForMultipleOccurrence >= 1 && $RankForMultipleOccurrence < 2 ) {
            if($transiting_planet == '1006' && $transiting_planet == '1005' ) { //Saturn and Jupiter (2 or 3 time)
                $RankForMultipleOccurrence = 1;
            }
            else {
                $RankForMultipleOccurrence = 1;
            }
        }

        else if($RankForMultipleOccurrence >= 2 && $RankForMultipleOccurrence < 3) {
            $RankForMultipleOccurrence = 3;
        }
        else if($RankForMultipleOccurrence >= 3 ) {
            $RankForMultipleOccurrence = 5;
        }

        $TotalRank = intval($TP_Rank) + intval($NP_Rank) + intval($ASP_Rank) + intval($RankForMultipleOccurrence);
        return array("start" => $start_date, "end" => $end_date, "hitcounter" => $DateArray, "totalrank" => $TotalRank, "aspectrank" => $RankNumber, "planetrank" => $RankForPlanet);
    }

    function CheckDCandICTransit($TransitingPlanet, $AspectingPlanet, $Aspect) {
        if($Aspect == '180' && $AspectingPlanet == '1012') {
            return array('1015', sprintf("%03d", 0));
        }
        else if($Aspect == '180' && $AspectingPlanet == '1013') {
            return array('1014', sprintf("%03d", 0));
        }
        else {
            return;
        }
    }   

    function sortTransitByPlanetNameAndAspects() {
        global $Global_Natal_TransitSortedList;
        global $Global_N_TSLNextYear;
        global $NameOfPlanets;
        $sortedtransits = array();

        $nextnewdate = strtotime ( '+1 year' , strtotime (  $this->NextYear ) ) ;
        $nextnewdate = date ( 'Y-m-d' , $nextnewdate );
        $NextToNextYear = $nextnewdate;

        unset($this->TopAspects);

        if(!function_exists("sortTransitByTotalRank")) {
            function sortTransitByTotalRank($a, $b) {
                return $a['totalrank'] > $b['totalrank'] ? -1 : 1;
            }
        }

        $this->TopAspects = array();
        //$sortedtransits = $this->msort($this->transit_window);
        $sortedtransits = $this->transit_window;
        usort($sortedtransits, 'sortTransitByTotalRank');

        $isThere = false;

        while (list($key, $value) = each($sortedtransits)) {
            array_push($this->TopAspects, $value['test']);
            $isThere = false;

            $dt =  trim( $value['hitdate'] );
            $tDate =	$dt;

            if(count($Global_Natal_TransitSortedList) > 0) {
                reset($Global_Natal_TransitSortedList);

                //Checking for duplicate Aspect
                foreach ($Global_Natal_TransitSortedList as $addedList) {

                    if( $addedList['hitdate'] >= $this->CurrntYear && $addedList['hitdate'] < $this->NextYear ) {
                        if($addedList['pt'] == $value['pt'] && $addedList['asp'] == $value['asp'] && $addedList['pn'] == $value['pn']) {
                            $isThere = true;
                        }
                    }
                }
            }

            if( $tDate >= $this->CurrntYear && $tDate < $this->NextYear ) {
                if($isThere == false) {
                    array_push($Global_Natal_TransitSortedList, $value);
                }
            }
        }

        /* sort the transits, removing duplicates along the way */
        $sortedtransits = array();
        for ($aspect = 0; $aspect < count($this->TopAspects); $aspect++) {
            $sortme = substr($this->TopAspects[$aspect]['test'], 0, 13);
            $sortedtransits[$sortme] = $this->TopAspects[$aspect];
        }
        //array_unique($sortedtransits);
        unset($this->TopAspects);
        reset($sortedtransits);

        $this->TopAspects = array();
        $this->TopAspects = $sortedtransits;
    }

    function sortNextYearTransitByPlanetNameAndAspects() {
        global $Global_N_TSLNextYear;
        global $NameOfPlanets;
        $sortedtransits = array();

        $nextnewdate = strtotime ( '+1 year' , strtotime (  $this->NextYear ) ) ;
        $nextnewdate = date ( 'Y-m-d' , $nextnewdate );
        $NextToNextYear = $nextnewdate;

        if(!function_exists("sortNextYearTransitByTotalRank")) {
            function sortNextYearTransitByTotalRank($a, $b) {
                return $a['totalrank'] > $b['totalrank'] ? -1 : 1;
            }
        }

        $NextTopAspects = array();
        $sortedtransits = $this->transit_window_Next;
        usort($sortedtransits, 'sortNextYearTransitByTotalRank');

        $isThere = false;

        while (list($key, $value) = each($sortedtransits)) {
            array_push($NextTopAspects, $value['test']);
            $isThere = "false";

            $dt =  trim( $value['hitdate'] );
            $tDate =	$dt;

            if(count($Global_N_TSLNextYear) > 0) {
                reset($Global_N_TSLNextYear);
                //Checking for duplicate Aspect
                foreach ($Global_N_TSLNextYear as $addedList) {
                    if( $tDate >= $this->NextYear && $tDate <= $NextToNextYear) {
                        if($addedList['pt'] == $value['pt'] && $addedList['asp'] == $value['asp'] && $addedList['pn'] == $value['pn']) {
//                          echo $tDate . ' - '. $dt . ' - ' . $this->NextYear . ' '. $NameOfPlanets[$addedList['pt']] ." - ". $addedList['asp']." - ". $NameOfPlanets[$addedList['pn']] . '<br />';
                            $isThere = "true";
                        }
                    }
                }
            }

            if($tDate >= $this->NextYear && $tDate <= $NextToNextYear) {
                if($isThere == "false") {
                    array_push($Global_N_TSLNextYear, $value);
                }
            }
        }
    }

    function msort($array, $id="totalrank") {
        $temp_array = array();
        while(count($array)>0) {
            $lowest_id = 0;
            $index=0;
            foreach ($array as $item) {
                if (isset($item[$id]) && $array[$lowest_id][$id]) {
                    if ($item[$id] > $array[$lowest_id][$id]) {
                        $lowest_id = $index;
                    }
                }
                $index++;
            }
            $temp_array[] = $array[$lowest_id];
            $array = array_merge(array_slice($array, 0,$lowest_id), array_slice($array, $lowest_id+1));
        }
        return $temp_array;
    }

    /**
     * Sorting and One dimensional Array with Specified Key Values in internal array
     * @param $array	Array Collection
     * @param $id		Sory By
     */
    function msortASC($array, $id) {

        if(!function_exists("sortTransitByStartDate")) {
            function sortTransitByStartDate($a, $b) {
                return $a['start'] < $b['start'] ? -1 : 1;
            }
        }
        $temp_array = $array;
        usort($temp_array, "sortTransitByStartDate");
        return $temp_array;
    }


    /**
     *
     * GenerateHouseCrossingList()
     *
     * @param $CrossingList
     *
     * 2009-20-02 08:48 10050000005 D
     * 1234567890123456789012345678901234567890
     */
    function GenerateHouseCrossingList($CrossingList) {
        global $Global_SaturnJupiterCrossing;
        global $Global_CurrntYear;
        global $Global_NextYear;
        global $Global_PreviousYear;

        $SAArray = array();
        $JUArray = array();
        foreach ($CrossingList as $transit) {
            $transiting_planet = substr($transit, 17, 4);
            $transiting_aspect = substr($transit, 21, 3);
            $natal_cusp = substr($transit, 24, 4);
            $IsRetrograde = substr($transit, 29, 1);

            $tDate = trim( substr($transit, 0, 10) );
//									     MM = Month             DD = Day               YYYY = Year
            $tDate = date("Y-m-d", mktime ( 0, 0, 0, substr( $tDate, 8, 2), substr( $tDate, 5, 2), substr( $tDate, 0, 4) ));

            if($tDate >= $Global_CurrntYear && $IsRetrograde == 'D') {
                $EndDate = $this->GetLeavesDateForCups($CrossingList, $transiting_planet, $natal_cusp);

                if($transiting_planet == '1005') {
                    if($tDate >= $Global_CurrntYear) {
                        $JUArray = $this->AddTOSAandJUArray($JUArray, $transiting_planet, $transiting_aspect, $natal_cusp, $tDate, $EndDate['enddate'], $IsRetrograde, $EndDate['stayinhouse']);
                    }
                }
                else {
                    $SAArray = $this->AddTOSAandJUArray($SAArray, $transiting_planet, $transiting_aspect, $natal_cusp, $tDate, $EndDate['enddate'], $IsRetrograde, $EndDate['stayinhouse']);
                }
            }
        }

        if(count($JUArray) == 1) {
            $pn = sprintf('%04d', intval( $JUArray[0]['pn']) - 1 );
            reset($CrossingList);
            foreach ($CrossingList as $transit) {
                $transiting_planet = substr($transit, 17, 4);
                $transiting_aspect = substr($transit, 21, 3);
                $natal_cusp = sprintf('%04d', substr($transit, 24, 4));
                $IsRetrograde = substr($transit, 29, 1);

                $tDate = trim( substr($transit, 0, 10) );
                //									     MM = Month             DD = Day               YYYY = Year
                $tDate = date("Y-m-d", mktime ( 0, 0, 0, substr( $tDate, 8, 2), substr( $tDate, 5, 2), substr( $tDate, 0, 4) ));
                if($transiting_planet == '1005' && $natal_cusp == $pn) {
                    $EndDate = $this->GetLeavesDateForCups($CrossingList, $transiting_planet, $natal_cusp);
                    $JUArray = $this->AddTOSAandJUArray($JUArray, $transiting_planet, $transiting_aspect, $natal_cusp, $tDate, $EndDate['enddate'], $IsRetrograde, $EndDate['stayinhouse']);
                    break;
                }
            }
        }

        $TmpArray =$this->msortASC($JUArray, 'start');
        unset($JUArray);
        $JUArray = $TmpArray;
        reset($JUArray);

        //Now Checking for Last House checking
        foreach ($JUArray as $Key => $Item) {
            if($Key == 0) {
                $PT = $Item['pt'];
                $ASP = $Item['asp'];
                $PN = sprintf('%04d', intval( $Item['pn']) - 1 );
                $AspectType = $Item['aspecttype'];
                $IsPlanet = $Item['isplanet'];
                $IsRetrograde = $Item['isretrograde'];
                $DT = $Item['start'];
                $start = trim( $Item['start'] );
                $end = trim( $Item['end'] );
                $IsStayInHouse = trim( $Item['isstayinhouse']);

                $StartDate = $this->GetLastEnterDateForCups($CrossingList, $PT, $PN);
                if($StartDate['startdate'] != '') {
                    $JUArray = $this->AddTOSAandJUArray($JUArray, $PT, $ASP, $PN, $StartDate['startdate'], $start, $IsRetrograde, 'N');
                }
                break;
            }
        }

        $TmpArray =$this->msortASC($JUArray, 'start');
        unset($JUArray);
        $JUArray = $TmpArray;

        $TmpArray =$this->msortASC($SAArray, 'start');
        unset($SAArray);
        $SAArray = $TmpArray;

        //Now Checking for Last House checking
        foreach ($SAArray as $Key => $Item) {
            if($Key == 0) {
                $PT = $Item['pt'];
                $ASP = $Item['asp'];
                $CheckPN = sprintf('%04d', intval( $Item['pn']) - 1 );                
                $PN = sprintf('%04d', $CheckPN);
                $AspectType = $Item['aspecttype'];
                $IsPlanet = $Item['isplanet'];
                $IsRetrograde = $Item['isretrograde'];
                $DT = $Item['start'];
                $start = trim( $Item['start'] );
                $end = trim( $Item['end'] );
                $IsStayInHouse = trim( $Item['isstayinhouse']);

                $StartDate = $this->GetLastEnterDateForCups($CrossingList, $PT, $CheckPN);
                if($StartDate['startdate'] != '') {
                    $SAArray = $this->AddTOSAandJUArray($SAArray, $PT, $ASP, $PN, $StartDate['startdate'], $start, $IsRetrograde, 'N');
                }
                break;
            }
        }

        $TmpArray =$this->msortASC($SAArray, 'start');
        unset($SAArray);
        $SAArray = $TmpArray;

        $Global_SaturnJupiterCrossing = array_merge($SAArray, $JUArray);
    }

    function AddTOSAandJUArray($SAandJU, $TPlanet, $TAspect, $NatalCusp, $TSDate, $TEDate, $IsRetrograde, $IsStayInHouse) {

        array_push($SAandJU, array("pt" => $TPlanet,
                "asp" => $TAspect,
                "pn" => $NatalCusp,
                "start" => $TSDate, 					//Enters in House Cups,
                "end" => $TEDate, 		//Leaves House Cups,
                "isretrograde" => $IsRetrograde,
                "isstayinhouse" => $IsStayInHouse,
                'isplanet' => 0,
                'aspecttype' => 'TR')
        );
        return $SAandJU;
    }

    /**
     * GetLeavesDateForCups() function returns the end date for the House crossing
     * @param $CrossingList		List of house crossing
     * @param $Planet 	Planet that cross the house
     * @param $CuspNo	Looking for next house cups
     */
    function GetLeavesDateForCups($CrossingList, $Planet, $CuspNo) {
        global $Global_NextYear;
        $tDate = '';
        $StayInHouse = 'Y';
        foreach ($CrossingList as $transit) {
            $transiting_planet = substr($transit, 17, 4);
            $transiting_aspect = substr($transit, 21, 3);
            $natal_cusp = substr($transit, 24, 4);
            $IsRetrograde = substr($transit, 29, 1);

            if($transiting_planet == $Planet && intval($natal_cusp) == intval($CuspNo) + 1) {
                $tDate = trim( substr($transit, 0, 10) );

                //									     MM = Month             DD = Day               YYYY = Year
                $tDate = date("Y-m-d", mktime ( 0, 0, 0, substr( $tDate, 8, 2), substr( $tDate, 5, 2), substr( $tDate, 0, 4) ));
                if($tDate >= $Global_NextYear) { //&& $IsRetrograde == 'D'
                    $StayInHouse = 'Y';
                }
                else {
                    $StayInHouse = 'N';
                }

                //echo '$tDate :: ' . $tDate. '      $Global_NextYear :: ' . $Global_NextYear .'      $StayInHouse :: '. $StayInHouse.'<br />';
                break;
            }
        }
        return array('enddate' => $tDate, 'stayinhouse' => $StayInHouse);
    }

    /**
     * 		GetLastEnterDateForCups() function returns the start date for the House crossing
     * @param $CrossingList		List of house crossing
     * @param $Planet 	Planet that cross the house
     * @param $CuspNo	Looking for Last house cups
     */
    function GetLastEnterDateForCups($CrossingList, $Planet, $CuspNo) {
        global $Global_NextYear;
        $tDate = '';
        $StayInHouse = 'Y';
        foreach ($CrossingList as $transit) {
            $transiting_planet = substr($transit, 17, 4);
            $transiting_aspect = substr($transit, 21, 3);
            $natal_cusp = substr($transit, 24, 4);
            $IsRetrograde = substr($transit, 29, 1);

            if($transiting_planet == $Planet && intval($natal_cusp) == intval($CuspNo)) {
                $tDate = trim( substr($transit, 0, 10) );
                //									     MM = Month             DD = Day               YYYY = Year
                $tDate = date("Y-m-d", mktime ( 0, 0, 0, substr( $tDate, 8, 2), substr( $tDate, 5, 2), substr( $tDate, 0, 4) ));
                if($tDate >= $Global_NextYear) { //&& $IsRetrograde == 'D'
                    $StayInHouse = 'Y';
                }
                else {
                    $StayInHouse = 'N';
                }

                //echo '$tDate :: ' . $tDate. '      $Global_NextYear :: ' . $Global_NextYear .'      $StayInHouse :: '. $StayInHouse.'<br />';
                break;
            }
        }
        return array('startdate' => $tDate, 'stayinhouse' => $StayInHouse);
    }

    /**
     * GetNodeHouseCrossing() get the house crossing for Nodes
     */
    private function GetNodeHouseCrossing($birthDTO) {
        $this->transit_window_node = array();
        $CheckDuplicate = false;
        $astrologService = new AstrologServices($birthDTO);

        $astrologService->calcCrossingAspectsForNode($birthDTO, $this->startYear - 1, 5);

        //NEW CODE FOR TRANSIT WINDOWS WRT CUSPS
        foreach ($astrologService->m_crossing as $transit) {
            $CheckDuplicate = false;
            $transiting_planet = substr($transit, 17, 4);
            $transiting_aspect = substr($transit, 21, 3);
            $natal_cusp = substr($transit, 24, 4);

            $tDate = trim( substr($transit, 0, 10) );
            //									     MM = Month             DD = Day               YYYY = Year
            $tDate = date("Y-m-d", mktime ( 0, 0, 0, substr( $tDate, 8, 2), substr( $tDate, 5, 2), substr( $tDate, 0, 4) ));

            $startEndDate = array();
            $startEndDate = $this->findTransits($transiting_planet, $transiting_aspect, $natal_cusp, $astrologService, substr($transit, 0, 10));

            reset($this->transit_window_node);

            foreach($this->transit_window_node as $Key => $Item) {
                if($Item['pt'] == $transiting_planet && $Item['asp'] == $transiting_aspect && $Item['pn'] == $natal_cusp && $Item['hitdate'] >= $this->CurrntYear) {
                    $CheckDuplicate = true;
                    break;
                }
            }

            if($CheckDuplicate == false) {
                array_push($this->transit_window_node, array("pt" => $transiting_planet,
                        "asp" => $transiting_aspect,
                        "pn" => $natal_cusp,
                        "planetrank" => $startEndDate['planetrank'], 	//Planet rank to set Priority,
                        "aspectrank" => $startEndDate['aspectrank'], 	//Aspect rank to set Priority,
                        "start" => $startEndDate['start'], 				//$start_date,
                        "end" => $startEndDate['end'], 					//$end_date,
                        "hitdate" => $tDate,
                        'isplanet' => 0,
                        'aspecttype' => 'TR',
                        'totalrank' => $startEndDate['totalrank'],
                        "test" => array(
                                'test' => $transiting_planet . '-' . $transiting_aspect . '-' . $natal_cusp . '-' .substr($transit, 0, 10). '-'. $startEndDate['totalrank'],
                                "hitcounter" => $startEndDate['hitcounter'],
                                'isplanet' => 0)));
            }
        }

        global $Global_Node_m_transit;
        $Global_Node_m_transit = $this->transit_window_node;
    }
}
?>