<?php

class GenerateCurrentYearProgressions {

    //Progressed to Natal Aspects
    var $aspectProgressedToNatal = array();
    var $transit_window = array();
    
    var $TopAspects;
    var $AspectsList;

    var $bDay;
    var $bMonth;
    var $bYear;

    var $startYear;
    var $PreviousYear;
    var $NextYear;
    var $CurrntYear;

    function GenerateCurrentYearProgressions($birthDTO, $ordDate) {
        $this->bDay = $birthDTO->day;
        $this->bMonth = $birthDTO->month;
        $this->bYear = $birthDTO->year;

        $this->startYear = $ordDate->format('Y');         //Actual Code
        $this->startEnd = $ordDate->format('Y') + 2;      //Actual Code

        //									  MM = Month          DD = Day            YYYY = Year
        $this->PreviousYear = date("Y-m-d", mktime ( 0, 0, 0, $this->bMonth, $this->bDay, $this->startYear - 1 ) );
        $this->NextYear = date("Y-m-d", mktime ( 0, 0, 0, $this->bMonth, $this->bDay, $this->startYear + 1 ) );
        $this->CurrntYear = date("Y-m-d", mktime ( 0, 0, 0, $this->bMonth, $this->bDay, $this->startYear ) );
        
        $Global_PreviousYear = date("Y-m-d", mktime ( 0, 0, 0, $this->bMonth, $this->bDay, $this->startYear - 1 ) );
        $Global_NextYear = date("Y-m-d", mktime ( 0, 0, 0, $this->bMonth, $this->bDay, $this->startYear + 1 ) );
        $Global_CurrntYear = date("Y-m-d", mktime ( 0, 0, 0, $this->bMonth, $this->bDay, $this->startYear ) );
        
        global $Global_Progression_m_transit;
        global $Global_Progression_m_crossing;
        global $Global_Progression_TransitSortedList;
        global $Global_Prog_Direct_Retrograde_List;
        
        $asForProgression = new AstrologServices($birthDTO, false);
        
        $asForProgression->CalculateDynamicProgression($birthDTO, date('Y', strtotime( $Global_PreviousYear )), 3);
        $Global_Progression_m_transit = $asForProgression->m_transit;
        
        //Calculating Sign Crossing
        $asForProgression->CalculateDynamicProgressionIngress($birthDTO, date('Y', strtotime( $Global_PreviousYear )) , 3);
        $Sign =  $asForProgression->m_crossing;
        
        //Calculating House Crossing
        unset($asForProgression->m_crossing);
        $asForProgression->CalcPrograssedHouseCrossing($birthDTO, date('Y', strtotime( $Global_PreviousYear )), 3);
        $House = $asForProgression->m_crossing;
        $Global_Progression_m_crossing = array_merge($Sign, $House);
        
        $Global_Progression_m_transit = array_merge($Global_Progression_m_transit, $Global_Progression_m_crossing);
        
        unset($asForProgression->m_transit);
        $asForProgression->CalculateProgressedToProgressed($birthDTO, date('Y', strtotime( $Global_PreviousYear )), 99);
        $Global_ProgressedToProgressed = $asForProgression->m_transit;               
        
        $SamePlanets = array('1000', '1001', '1004');

        $CheckDuplicate = false;        
        foreach ($Global_Progression_m_transit as $progression) {
            $CheckDuplicate = false;

            //				   12345678901234567890123456789012345
            //$progression  =  2006-20-04 06:36 10040601012 D

            $progressed_planet = substr($progression, 17, 4);
            $progressed_aspect = substr($progression, 21, 3);
            $natal_planet = substr($progression, 24, 4);
            $IsRatrograde = substr($progression, 29, 1);       
            
            //if(in_array($progressed_planet, $SamePlanets) &&  in_array($natal_planet, $SamePlanets)) {
            if(($progressed_planet == '1000' && $natal_planet == '1000') ||
                    ($progressed_planet == '1004' && $natal_planet == '1004')) {
                //($progressed_planet == '1001' && $natal_planet == '1001') ||
                //We dont need MOON to MOON, SUN to SUN and MARS to MARS progression
            }
            else {
                $PointArray = array();
                $PointArray = $this->CalculatingPoints($progressed_planet, $progressed_aspect, $natal_planet, substr($progression, 0, 10));

                $retunPlanet = $this->CheckDCandICPrograssed($progressed_planet, $natal_planet ,$progressed_aspect);

                if(count($retunPlanet) > 0) {
                    $natal_planet = $retunPlanet[0];
                    $progressed_aspect =$retunPlanet[1];
                }

                if(count($this->transit_window) > 0) {
                    reset($this->transit_window);
                    foreach($this->transit_window as $Key => $Item) {
                        //if($Item['pt'] == $progressed_planet && $Item['asp'] == $progressed_aspect && $Item['pn'] == $natal_planet && $Item['hitdate'] >= $this->CurrntYear) {
                    	if($Item['pt'] == $progressed_planet && $Item['asp'] == $progressed_aspect && $Item['pn'] == $natal_planet && $Item['hitdate'] == $PointArray['start']) {
                            $CheckDuplicate = true;
                            break;
                        }
                    }
                }

                if($CheckDuplicate == false) {
                    array_push($this->transit_window, array("pt" => $progressed_planet,
                            "asp" => $progressed_aspect,
                            "pn" => $natal_planet,
                            "start" => $PointArray['start'], 	//$start_date,
                            "planetrank" => $PointArray['planetrank'], 	//Planet rank to set Priority,
                            "aspectrank" => $PointArray['aspectrank'], 	//Aspect rank to set Priority,
                            "natalrank" => $PointArray['natalrank'], 	//Natal planet rank to set Priority,
                            "hitdate" =>  $PointArray['start'],
                            "enddate" =>  $PointArray['end'],
                            'isplanet' => 1,
                            'aspecttype' => 'PR',
                            'totalrank' => $PointArray['totalrank'],
                            'IsRatrograde' => $IsRatrograde));                    
                }                
            }
        }

        $this->sortProgressionByRank();

        foreach ($Global_Progression_m_crossing as $progression) {
            $progressed_planet = substr($progression, 17, 4);
            $progressed_aspect = substr($progression, 21, 3);
            $natal_planet = substr($progression, 24, 4);
            $IsRatrograde = substr($progression, 29, 1);

            $PointArray = array();
            $PointArray = $this->CalculatingPoints($progressed_planet, $progressed_aspect, $natal_planet, substr($progression, 0, 10));

            if($progressed_aspect == "-->") {
                if($natal_planet != "S/D" && $natal_planet != "S/R") {
                    array_push($this->transit_window, array("pt" => $progressed_planet,
                            "asp" => $progressed_aspect,
                            "pn" => $natal_planet,
                            "start" => $PointArray['start'], 				//$start_date,
                            "planetrank" => $PointArray['planetrank'], 		//Planet rank to set Priority,
                            "aspectrank" => $PointArray['aspectrank'], 		//Aspect rank to set Priority,
                            "natalrank" => $PointArray['natalrank'], 		//Natal planet rank to set Priority,
                            "hitdate" =>  $PointArray['start'],
                            "enddate" =>  '',
                            'isplanet' => 0,
                            'aspecttype' => 'PR',
                            'totalrank' => $PointArray['totalrank'],
                            'IsRatrograde' => $IsRatrograde));

                }
                else {
                    $SignNo = $this->GetSignNoForRetrogradeDirect($PointArray['start'], $progressed_planet);
						
                    array_push($Global_Prog_Direct_Retrograde_List, 
                    					array("pt" => $progressed_planet,
                            					"asp" => $natal_planet,
                            					"pn" => $SignNo,
                            					"start" => $PointArray['start'], 	//$start_date,
                            					'isplanet' => 1,
                            					'aspecttype' => 'P'));
                }
            }            
        }
        
        $this->sortProgressionByDate($this->transit_window);

        unset($this->transit_window);
        $this->transit_window = array();
        $this->transit_window = $this->AspectsList;
    }

    function GetSignNoForRetrogradeDirect($StartDate, $CheckPlanet) {
        global $Global_Progression_m_crossing;
        $ReturnSignNo;
        foreach ($Global_Progression_m_crossing as $progression) {
            //				   12345678901234567890123456789012345
            //$progression  =  2006-20-04 06:36 10040601012 D

            $progressed_planet = substr($progression, 17, 4);
            $progressed_aspect = substr($progression, 21, 3);
            $natal_planet = intval( substr($progression, 24, 4) );
            $pyear = intval(trim(substr($progression, 0, 4)));
            $pmonth = intval(trim(substr($progression, 8, 2)));
            $pday = intval(trim(substr($progression, 5, 2)));
            //									     MM = Month             DD = Day               YYYY = Year
            $finalDate = date("Y-m-d", mktime ( 0, 0, 0, $pmonth, $pday, $pyear ));

            if($progressed_planet == $CheckPlanet && $finalDate >= $StartDate && $natal_planet < 113) {
                $ReturnSignNo = sprintf("%04", $natal_planet - 1);
                break;
            }
        }
        return $ReturnSignNo;
    }
    
    function CalculatingPoints($progressed_planet, $progressed_aspect, $natal_planet, $progressiondate) {
        global $logger;
        global $AspectRank;
        global $ProgressedNatalPlanetRank;
        global $ProgressedPlanetRank;

        $PP_Rank = 0;
        $NP_Rank = 0;
        $ASP_Rank = 0;

        if(is_array($ProgressedPlanetRank) && array_key_exists($progressed_planet, $ProgressedPlanetRank)) {
            $PP_Rank = $ProgressedPlanetRank[$progressed_planet];
        }

        if(is_array($ProgressedNatalPlanetRank) && array_key_exists($natal_planet, $ProgressedNatalPlanetRank)) {
            $NP_Rank = $ProgressedNatalPlanetRank[$natal_planet];
        }

        if(is_array($AspectRank) && array_key_exists($progressed_aspect, $AspectRank)) {
            $ASP_Rank = $AspectRank[$progressed_aspect];
        }
        if($progressed_aspect == '-->') {
            $ASP_Rank = 20;
        }

        //Create Date from string
        $pyear = intval(trim(substr($progressiondate, 0, 4)));
        $pmonth = intval(trim(substr($progressiondate, 8, 2)));
        $pday = intval(trim(substr($progressiondate, 5, 2)));

        $date = new DateTime();
        //$year , $month , $day
        $date->setDate($pyear, $pmonth, $pday);
        $finalDate = $date->format("Y-m-d");

        $endDate = $this->GetEndDateForProgression($progressed_planet, $progressed_aspect, $natal_planet);

        //Calculate Total
        $TotalRank = intval($PP_Rank) + intval($ASP_Rank) + intval($NP_Rank);

        return array("start" => $finalDate, "end" => $endDate, "totalrank" => $TotalRank, "aspectrank" => $ASP_Rank, "planetrank" => $PP_Rank, "natalrank"  => $NP_Rank);
    }

    function GetEndDateForProgression($progressed_planet, $progressed_aspect, $natal_planet) {
        global $Global_ProgressedToProgressed;
        $EndDate = '';
        $CheckForUs = sprintf("%s%s%s", $progressed_planet, $progressed_aspect, $natal_planet);
        $CheckForUsUlatu = sprintf("%s%s%s", $natal_planet, $progressed_aspect, $progressed_planet);

        //12345678901234567890123456789012345
        //2108-14-11 00:08 10010901016 D
        if (is_array($Global_ProgressedToProgressed)) {
            foreach($Global_ProgressedToProgressed as $Item) {
                $LookForMeHere =  trim(substr($Item, 16, 12));

                if($LookForMeHere == $CheckForUs || $CheckForUsUlatu == $LookForMeHere) {
                    $pyear = intval(trim(substr($Item, 0, 4)));
                    $pmonth = intval(trim(substr($Item, 8, 2)));
                    $pday = intval(trim(substr($Item, 5, 2)));

                    $date = new DateTime();
                    //$year , $month , $day
                    $date->setDate($pyear, $pmonth, $pday);

                    $EndDate = $date->format("Y-m-d");
                    break;
                }
            }
        }
        return $EndDate;
    }

    function CheckDCandICPrograssed($TransitingPlanet, $AspectingPlanet, $Aspect) {

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

    function sortProgressionByRank() {
        global $Global_Progression_TransitSortedList;

        $sortedtransits = array();

        unset($this->TopAspects);

        if(!function_exists("sortByTotalRank")) {
            function sortByTotalRank($a, $b) {
                return $a['hitdate'] < $b['hitdate'] ? -1 : 1;
            }
        }
        $this->TopAspects = array();
        //$sortedtransits = $this->msort($Global_Progression_TransitSortedList);
        $sortedtransits = $this->transit_window;
        usort($sortedtransits, 'sortByTotalRank');

        while (list($key, $value) = each($sortedtransits)) {
            array_push($this->TopAspects, $value);
        }

        unset($this->TopAspects);
        reset($sortedtransits);

        $this->TopAspects = array();
        $this->TopAspects = $sortedtransits;
    }

    function sortProgressionByDate($Global_Progression_TransitSortedList) {
        $sortedtransits = array();

        unset($this->AspectsList);

        if(!function_exists("sortByTotalRank")) {
            function sortByTotalRank($a, $b) {
                return $a['hitdate'] < $b['hitdate'] ? -1 : 1;
            }
        }

        $this->AspectsList = array();
        //$sortedtransits = $this->mascsort($Global_Progression_TransitSortedList, 'start');
        $sortedtransits = $Global_Progression_TransitSortedList;
        usort($sortedtransits, 'sortByTotalRank');

        while (list($key, $value) = each($sortedtransits)) {
            array_push($this->AspectsList, $value);
        }

        unset($this->AspectsList);
        reset($sortedtransits);

        $this->AspectsList = array();
        $this->AspectsList = $sortedtransits;
    }

    function mascsort($array, $id="totalrank") {
        $temp_array = array();
        while(count($array)>0) {
            $lowest_id = 0;
            $index=0;
            foreach ($array as $item) {
                if (isset($item[$id]) && $array[$lowest_id][$id]) {
                    if ($item[$id] < $array[$lowest_id][$id]) {
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
}
?>