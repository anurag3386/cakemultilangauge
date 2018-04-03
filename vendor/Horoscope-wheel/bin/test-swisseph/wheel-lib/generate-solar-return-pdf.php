<?php
class GenerateSolarReturnPDF extends CommonPDFHelper {

    //Natal, Solar Return and Horary Aspect
    var $SolarReturnAspects;
    var $HoraryAspects;
    var $SolarReturnAndHoraryAspects;
    var $SolarReturnToNatalAspects;		//For cross aspects (Solar return to Natal)
    var $HoraryToNatalAspects;			//For cross aspects (Horary to Natal)	
    var $SolarReturnDate;
    var $SolarReturnTime;
    
    var $HoraryPlaceName;
    var $HoraryCountry;
    var $HoraryLongitude;
    var $HoraryLatitude;
    var $HoraryTime;
        
    var $TopAspects;
    var $AspectsList;
    
    //Natal, Solar Return and Horary Aspect 

    var $MMScale = 0.26;
    var $LeftMargin = 20;
    var $RightMargin = 20;
    var $TopMargin = 20;
    var $TableWidth = 170;
    var $TableHeight = 70;
    var $TableCellWidth = 42.5;
    var $TableColumns = 4;
    var $TableX = 20;
    var $TableY = 20;
    
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
        "1015" => "Descendant"
    );
    
    var $NameOfAspects = array(
        "000" => "Conjunction",
        "060" => "Sextile",
        "090" => "Square",
        "120" => "Trine",
        "180" => "Opposition"
    );
    
    var $WowSymbolFonts = array(
        /* planets */
        'Sun' => 184, 'Moon' => 155,
        'Mercury' => 190, 'Venus' => 177, 'Mars' => 161,
        'Jupiter' => 165, 'Saturn' => 123,
        'Uranus' => 134, 'Neptune' => 135, 'Pluto' => 136,
        /* nodes */
        'N.Node' => 168, 'S.Node' => 130,
        /* signs */
        'Aries' => 247, 'Taurus' => 154, 'Gemini' => 208, 'Cancer' => 152,
        'Leo' => 172, 'Virgo' => 170, 'Libra' => 171, 'Scorpio' => 133,
        'Sagittarius' => 125, 'Capricorn' => 131, 'Aquarius' => 139, 'Pisces' => 138,
        /* aspects */
        'Conjunction' => 180, 'Semisextile' => 222, 'Semisquare' => 188, 'Sextile' => 181, 'Square' => 185,
        'Trine' => 186, 'Sesquisquare' => 164, 'Quincunx' => 222 /* quincunx = inverted semisextile */, 
        'Opposition' => 175,
        /* angles */
        'Ascendant' => 124, 'Descendant' => 254, 'MC' => 91, 'IC' => 93,
        /* retrograde */
        'Retrograde' => 182
    );
    

    function GenerateSolarReturnPDF($birthDTO) {
        parent::FPDF('P', 'mm', 'A4');
        $this->SolarReturnAspects = array();
        
        $this->HoraryAspects = array();
        $this->SolarReturnAndHoraryAspects = array(); 	//This is not require now because we don't need to calculate Solar return to Horary aspects
        
        $this->SolarReturnToNatalAspects = array();
        $this->HoraryToNatalAspects = array();

        $this->LeftMargin = 20;
        $this->TopMargin = 20;

        $this->TableX = $this->LeftMargin + 20;
        $this->TableY = $this->TopMargin + 20;

        $this->TableWidth = 170;
        $this->TableHeight = 10;
        $this->TableCellWidth = 42.5;
        $this->SetMargins($this->LeftMargin, $this->TopMargin, $this->RightMargin);
    }

     function SetSolarReturnAspect() {
        $this->AddPage();

        $this->SetFont('wows', '', 14);        
        $this->Cell($this->TableCellWidth, $this->TableHeight, 'Solar Return Aspects', 0, 0, 'C', false);
        $this->Ln();
        $this->SetFont('wows', '', 12);
        $SRDate = sprintf('%2d/%02d/%4d', substr($this->SolarReturnDate, 6,2), substr($this->SolarReturnDate, 4,2),substr($this->SolarReturnDate, 0,4));
        
        $this->Cell($this->TableCellWidth * 4, $this->TableHeight, 
                    'Solar Return Date Time : ' . $SRDate . ' ' . strtoupper( $this->SolarReturnTime ), 
                    0, 0, 'L', false);
        $this->Ln();
                
        foreach ($this->SolarReturnAspects as $key => $solaraspect) {
            //%04d%03d%04d %s [Format]
            $solarPlanet = intval(substr($solaraspect, 0, 4));
            $solarAspectValue = substr($solaraspect, 4, 3);
            $aspectToPlanet = intval(substr($solaraspect, 7, 4));
            $orbs = substr($solaraspect, 12);

            $this->Cell($this->TableCellWidth, $this->TableHeight, 
                    chr($this->WowSymbolFonts[$this->NameOfPlanets[$solarPlanet]]) . ' ' .$this->NameOfPlanets[$solarPlanet] , 
                    1, 0, 'L', false);

            $this->SetX($this->LeftMargin + $this->TableCellWidth);
            $this->Cell($this->TableCellWidth, $this->TableHeight, 
                    chr($this->WowSymbolFonts[$this->NameOfAspects[$solarAspectValue]])  . ' ' . $this->NameOfAspects[$solarAspectValue], 
                    1, 0, 'L', false);

            $this->SetX($this->LeftMargin + $this->TableCellWidth * 2);
            $this->Cell($this->TableCellWidth, $this->TableHeight, 
                        chr($this->WowSymbolFonts[$this->NameOfPlanets[$aspectToPlanet]]) . ' ' . $this->NameOfPlanets[$aspectToPlanet] , 
                        1, 0, 'L', false);

            $this->SetX($this->LeftMargin + $this->TableCellWidth * 3);
            $this->Cell($this->TableCellWidth, $this->TableHeight, $orbs, 1, 0, 'L', false);
            $this->Ln();
            if ($this->GetY() > 260) {
                $this->AddNewPage();
            }
        }
    }
   
    function SetHoraryAspect() {
        $this->AddPage();

        $this->SetFont('wows', '', 14);        
        $this->Cell($this->TableCellWidth, $this->TableHeight, 'Horary Aspects', 0, 0, 'C', false);
        $this->Ln();
        $this->SetFont('wows', '', 12);
        $this->Cell(0, $this->TableHeight, 
                    'Name Place : ' . $this->HoraryPlaceName, 
                    0, 0, 'L', false);
        $this->Ln();
        $this->Cell(0, $this->TableHeight, 
                    'Country, Longitude and Latitide :' . $this->HoraryCountry . ' ' . $this->HoraryLongitude . ' ' . $this->HoraryLatitude, 
                    0, 0, 'L', false);
        $this->Ln();
        $this->Cell(0, $this->TableHeight, 
                    'Order Time : ' . $this->HoraryTime,
                    0, 0, 'L', false);
        $this->Ln();
                        
        foreach ($this->HoraryAspects as $key => $solaraspect) {
            //%04d%03d%04d %s [Format]
            $solarPlanet = intval(substr($solaraspect, 0, 4));
            $solarAspectValue = substr($solaraspect, 4, 3);
            $aspectToPlanet = intval(substr($solaraspect, 7, 4));
            $orbs = substr($solaraspect, 12);

            $this->Cell($this->TableCellWidth, $this->TableHeight, 
                    chr($this->WowSymbolFonts[$this->NameOfPlanets[$solarPlanet]]) . ' ' .$this->NameOfPlanets[$solarPlanet] , 
                    1, 0, 'L', false);

            $this->SetX($this->LeftMargin + $this->TableCellWidth);
            $this->Cell($this->TableCellWidth, $this->TableHeight, 
                    chr($this->WowSymbolFonts[$this->NameOfAspects[$solarAspectValue]])  . ' ' . $this->NameOfAspects[$solarAspectValue], 
                    1, 0, 'L', false);

            $this->SetX($this->LeftMargin + $this->TableCellWidth * 2);
            $this->Cell($this->TableCellWidth, $this->TableHeight, 
                        chr($this->WowSymbolFonts[$this->NameOfPlanets[$aspectToPlanet]]) . ' ' . $this->NameOfPlanets[$aspectToPlanet] , 
                        1, 0, 'L', false);

            $this->SetX($this->LeftMargin + $this->TableCellWidth * 3);
            $this->Cell($this->TableCellWidth, $this->TableHeight, $orbs, 1, 0, 'L', false);
            $this->Ln();
            if ($this->GetY() > 260) {
                $this->AddNewPage();
            }
        }
    }
    
    function SetSolarReturnToHoraryAspect() {
        $this->AddPage();

        $this->SetFont('wows', '', 14);        
        $this->Cell($this->TableCellWidth, $this->TableHeight, 'Solar Return to Horary Aspects', 0, 0, 'C', false);
        $this->Ln();
        $this->SetFont('wows', '', 12);
                                
        foreach ($this->SolarReturnAndHoraryAspects as $key => $solaraspect) {
            //%04d%03d%04d %s [Format]
            $solarPlanet = intval(substr($solaraspect, 0, 4));
            $solarAspectValue = substr($solaraspect, 4, 3);
            $aspectToPlanet = intval(substr($solaraspect, 7, 4));
            $orbs = substr($solaraspect, 12);

            $this->Cell($this->TableCellWidth, $this->TableHeight, 
                    chr($this->WowSymbolFonts[$this->NameOfPlanets[$solarPlanet]]) . ' ' .$this->NameOfPlanets[$solarPlanet] , 
                    1, 0, 'L', false);

            $this->SetX($this->LeftMargin + $this->TableCellWidth);
            $this->Cell($this->TableCellWidth, $this->TableHeight, 
                    chr($this->WowSymbolFonts[$this->NameOfAspects[$solarAspectValue]])  . ' ' . $this->NameOfAspects[$solarAspectValue], 
                    1, 0, 'L', false);

            $this->SetX($this->LeftMargin + $this->TableCellWidth * 2);
            $this->Cell($this->TableCellWidth, $this->TableHeight, 
                        chr($this->WowSymbolFonts[$this->NameOfPlanets[$aspectToPlanet]]) . ' ' . $this->NameOfPlanets[$aspectToPlanet] , 
                        1, 0, 'L', false);

            $this->SetX($this->LeftMargin + $this->TableCellWidth * 3);
            $this->Cell($this->TableCellWidth, $this->TableHeight, $orbs, 1, 0, 'L', false);
            $this->Ln();
            if ($this->GetY() > 260) {
                $this->AddNewPage();
            }
        }
    }
    
    /**
     * SetHoraryToNatal()
     * Show contect of Horary to Natal aspects
     */
    function SetHoraryToNatal() {
    	//$this->AddPage();
    
    	$this->SetFont('wows', '', 14);
    	$this->Cell($this->TableCellWidth, $this->TableHeight, 'Horary to Natal Aspects', 0, 0, 'C', false);
    	$this->Ln();
    	$this->SetFont('wows', '', 12);
    
    	foreach ($this->HoraryToNatalAspects as $key => $Horaryaspect) {
    		//%04d%03d%04d %s [Format]
    		$solarPlanet = intval(substr($Horaryaspect, 0, 4));
    		$solarAspectValue = substr($Horaryaspect, 4, 3);
    		$aspectToPlanet = intval(substr($Horaryaspect, 7, 4));
    		$orbs = substr($Horaryaspect, 12);
    
    		$this->Cell($this->TableCellWidth, $this->TableHeight,
    				chr($this->WowSymbolFonts[$this->NameOfPlanets[$solarPlanet]]) . ' ' .$this->NameOfPlanets[$solarPlanet] ,
    				1, 0, 'L', false);
    
    		$this->SetX($this->LeftMargin + $this->TableCellWidth);
    		$this->Cell($this->TableCellWidth, $this->TableHeight,
    				chr($this->WowSymbolFonts[$this->NameOfAspects[$solarAspectValue]])  . ' ' . $this->NameOfAspects[$solarAspectValue],
    				1, 0, 'L', false);
    
    		$this->SetX($this->LeftMargin + $this->TableCellWidth * 2);
    		$this->Cell($this->TableCellWidth, $this->TableHeight,
    				chr($this->WowSymbolFonts[$this->NameOfPlanets[$aspectToPlanet]]) . ' ' . $this->NameOfPlanets[$aspectToPlanet] ,
    				1, 0, 'L', false);
    
    		$this->SetX($this->LeftMargin + $this->TableCellWidth * 3);
    		$this->Cell($this->TableCellWidth, $this->TableHeight, $orbs, 1, 0, 'L', false);
    		$this->Ln();
//     		if ($this->GetY() > 260) {
//     			$this->AddNewPage();
//     		}
    	}
    }
    
    /**
     * SetSolarReturnToNatal()
     * Show contect of Solar return to Natal aspects
     */
    function SetSolarReturnToNatal() {
    	//$this->AddPage();
    
    	$this->SetFont('wows', '', 14);
    	$this->Cell($this->TableCellWidth, $this->TableHeight, 'Solar Return to Natal Aspects', 0, 0, 'C', false);
    	$this->Ln();
    	$this->SetFont('wows', '', 12);
    
    	foreach ($this->SolarReturnToNatalAspects as $key => $solaraspect) {
    		//%04d%03d%04d %s [Format]
    		$solarPlanet = intval(substr($solaraspect, 0, 4));
    		$solarAspectValue = substr($solaraspect, 4, 3);
    		$aspectToPlanet = intval(substr($solaraspect, 7, 4));
    		$orbs = substr($solaraspect, 12);
    
    		$this->Cell($this->TableCellWidth, $this->TableHeight,
    				chr($this->WowSymbolFonts[$this->NameOfPlanets[$solarPlanet]]) . ' ' .$this->NameOfPlanets[$solarPlanet] ,
    				1, 0, 'L', false);
    
    		$this->SetX($this->LeftMargin + $this->TableCellWidth);
    		$this->Cell($this->TableCellWidth, $this->TableHeight,
    				chr($this->WowSymbolFonts[$this->NameOfAspects[$solarAspectValue]])  . ' ' . $this->NameOfAspects[$solarAspectValue],
    				1, 0, 'L', false);
    
    		$this->SetX($this->LeftMargin + $this->TableCellWidth * 2);
    		$this->Cell($this->TableCellWidth, $this->TableHeight,
    				chr($this->WowSymbolFonts[$this->NameOfPlanets[$aspectToPlanet]]) . ' ' . $this->NameOfPlanets[$aspectToPlanet] ,
    				1, 0, 'L', false);
    
    		$this->SetX($this->LeftMargin + $this->TableCellWidth * 3);
    		$this->Cell($this->TableCellWidth, $this->TableHeight, $orbs, 1, 0, 'L', false);
    		$this->Ln();
//     		if ($this->GetY() > 260) {
//     			$this->AddNewPage();
//     		}
    	}
    }
    
    function AddNewPage() {
        $this->AddPage("P", "A4");
        $this->SetMargins($this->LeftMargin, $this->TopMargin, $this->RightMargin);
    }

    function ConvertMMToPixels($MM) {
        return ($MM * 300) / 25.4;
    }

    function ConvertPixelsToMM($Pixel) {
        return ($Pixel * 25.4) / 300;
    }
    
    
    function PrintListOfSortedTransit(){
    	$this->Ln();
    	$this->Ln();
    
    	$this->SetFont('wows', '', 14);
    	$this->Cell($this->TableCellWidth, $this->TableHeight, 'Prioritize list for Solar Return to Natal', 0, 0, 'L', false);
    	$this->Ln();
    
    	$this->Cell($this->TableCellWidth, $this->TableHeight, 'Solar R Planet', 1, 0, 'L', false);
    
    	$this->SetX($this->LeftMargin + $this->TableCellWidth);
    	$this->Cell($this->TableCellWidth, $this->TableHeight, 'Aspect', 1, 0, 'L', false);
    
    	$this->SetX($this->LeftMargin + $this->TableCellWidth * 2);
    	$this->Cell($this->TableCellWidth, $this->TableHeight, 'Natal Planet', 1, 0, 'L', false);
    
    	$this->SetX($this->LeftMargin + $this->TableCellWidth * 3);
    	$this->Cell($this->TableCellWidth, $this->TableHeight, 'Total Points', 1, 0, 'L', false);
    	$this->Ln();
    
    	/***********************/
    	global $NameOfPlanets;
    	global $NameOfAspects;
    	global $WowSymbolFonts;
    	global $NameOfSigns;
        	
    	global $AspectRank;
    	global $NatalPlanetRank;
    	global $TransitingPlanetRank;
    	
    	global $Global_Solar_TransitSortedList;
    
    	foreach ($Global_Solar_TransitSortedList as $key => $trWin) {
    		$pt = trim( $trWin['pt'] );
    		$asp = trim( $trWin['asp'] );
    		$pn =  trim( $trWin['pn'] );    		
    		$rank = trim( $trWin['totalrank'] );
    		$Orbs_Rank = trim( $trWin['Orbs_Rank'] );
    		$aspectrank = trim( $trWin['aspectrank']); 	//Aspect rank to set Priority,
    		
    		
    		//$this->Cell($this->TableCellWidth, $this->TableHeight, $NameOfPlanets[$pt] . ' - ' . $TransitingPlanetRank[$pt], 1, 0, 'L', false);
    		$this->Cell($this->TableCellWidth, $this->TableHeight, $NameOfPlanets[$pt], 1, 0, 'L', false);
    
    		$this->SetX($this->LeftMargin + $this->TableCellWidth);
    
    		if($trWin['isplanet'] == 1) {
    			//$this->Cell($this->TableCellWidth, $this->TableHeight, chr($WowSymbolFonts[ $NameOfAspects[$asp]])  . ' ' . $NameOfAspects[$asp] . ' - ' . $AspectRank[$asp], 1, 0, 'L', false);
    			$this->Cell($this->TableCellWidth, $this->TableHeight, chr($WowSymbolFonts[$NameOfAspects[$asp]])  . ' ' . $NameOfAspects[$asp] . ' - ' . $aspectrank, 1, 0, 'L', false);
    		}
    		else
    		{
    			$this->Cell($this->TableCellWidth, $this->TableHeight, 'Sign Changing ', 1, 0, 'L', false);
    		}
    
    		$this->SetX($this->LeftMargin + $this->TableCellWidth * 2);
    		if($trWin['isplanet'] == 1) {
    			//$this->Cell($this->TableCellWidth, $this->TableHeight, $NameOfPlanets[$pn] . ' - ' . $NatalPlanetRank[$pn] . ' - ' . trim( $trWin['obrs'] ), 1, 0, 'L', false);
    			$this->Cell($this->TableCellWidth, $this->TableHeight, $NameOfPlanets[$pn] . ' - ' . trim( $trWin['obrs'] ), 1, 0, 'L', false);
    		}
    		else {
    			//$this->Cell($this->TableCellWidth, $this->TableHeight, $NameOfSigns['en'][$pn] . ' - ' . trim( $trWin['obrs'] ). ' - ' . $rank , 1, 0, 'L', false);
    			$this->Cell($this->TableCellWidth, $this->TableHeight, $NameOfSigns['en'][$pn] . ' - ' . trim( $trWin['obrs'] ) , 1, 0, 'L', false);
    		}    
    		
    		$content = $Orbs_Rank . ' - ' .$rank;    
    		$this->SetX($this->LeftMargin + $this->TableCellWidth * 3);
    		$this->Cell($this->TableCellWidth, $this->TableHeight, $content, 1, 0, 'L', false);
    		$this->Ln();
    	}
    }
    
    
    function PrioritizingTransit() {
    	echo "<pre>********************************************************************** PrioritizingTransit()</pre>";
    	unset($this->AspectsList);    	
    	$this->AspectsList = array();
    	
    	foreach ($this->SolarReturnToNatalAspects as $progression) {
    		$SP_planet = substr($progression, 0, 4);
    		$SP_aspect = substr($progression, 4, 3);
    		$natal_planet = substr($progression, 7, 4);
    		$Orbs = trim(substr($progression, 12, 4));   		
    		    		
    		$PointArray = array();
    		$PointArray = $this->CalculatingPoints($SP_planet, $SP_aspect, $natal_planet, $Orbs);
    	
    		array_push($this->AspectsList, array("pt" => $SP_planet,
    				"asp" => $SP_aspect,
    				"pn" => $natal_planet,
    				"planetrank" => $PointArray['planetrank'], 	//Planet rank to set Priority,
    				"aspectrank" => $PointArray['aspectrank'], 	//Aspect rank to set Priority,
    				"natalrank" => $PointArray['natalrank'], 	//Natal planet rank to set Priority,
    				'isplanet' => 1,
    				'aspecttype' => 'SR',
    				'totalrank' => $PointArray['totalrank'],
    				'obrs' => $Orbs,
    				'Orbs_Rank' => $PointArray['Orbs_Rank']));
    		
    	}
   
    	$this->sortByRank($this->AspectsList);
    	
    	global $Global_Solar_TransitSortedList;    	
    	$Global_Solar_TransitSortedList = $this->TopAspects;
    }
    
    function PrioritizingHororyTransit() {
    	unset($this->AspectsList);
    	unset($this->TopAspects);
    	
    	$this->AspectsList = array();
    
    	foreach ($this->HoraryToNatalAspects as $progression) {
    		$SP_planet = substr($progression, 0, 4);
    		$SP_aspect = substr($progression, 4, 3);
    		$natal_planet = substr($progression, 7, 4);
    
    		$PointArray = array();
    		$PointArray = $this->CalculatingPoints($SP_planet, $SP_aspect, $natal_planet);
    
    		array_push($this->AspectsList, array("pt" => $SP_planet,
    				"asp" => $SP_aspect,
    				"pn" => $natal_planet,
    				"planetrank" => $PointArray['planetrank'], 	//Planet rank to set Priority,
    				"aspectrank" => $PointArray['aspectrank'], 	//Aspect rank to set Priority,
    				"natalrank" => $PointArray['natalrank'], 	//Natal planet rank to set Priority,    				
    				'isplanet' => 1,
    				'aspecttype' => 'SR',
    				'totalrank' => $PointArray['totalrank'],
    				'Orbs_Rank' => $PointArray['Orbs_Rank']));
    	}
    
    	$this->sortByRank($this->AspectsList);
    
    	global $Global_Horory_TransitSortedList;
    	$Global_Horory_TransitSortedList = $this->TopAspects;
    }
    
	function PrioritizingInternalAspect() {	
		echo "<pre>********************************************************************** PrioritizingInternalAspect()</pre>";
		$AspectsList = array();
    	$TopAspects= array();
    	
    	foreach ($this->SolarReturnAspects as $SolarReturn) {
    		$SP_planet = substr($SolarReturn, 0, 4);
    		$SP_aspect = substr($SolarReturn, 4, 3);
    		$SP_InnerPlanet = substr($SolarReturn, 7, 4);
    
    		$PointArray = array();
    		$PointArray = $this->CalculatingPoints($SP_planet, $SP_aspect, $SP_InnerPlanet);
						
    		array_push($AspectsList, array("pt" => $SP_planet,
					    					"asp" => $SP_aspect,
					    					"pn" => $SP_InnerPlanet,
					    					"planetrank" => $PointArray['planetrank'], 	//Planet rank to set Priority,
					    					"aspectrank" => $PointArray['aspectrank'], 	//Aspect rank to set Priority,
					    					"natalrank" => $PointArray['natalrank'], 	//Natal planet rank to set Priority,    				
					    					'isplanet' => 1,
					    					'aspecttype' => 'SRCTR',
					    					'totalrank' => $PointArray['totalrank'],
					    					'Orbs_Rank' => $PointArray['Orbs_Rank']));
    	}   
    	
    	global $Global_Solar_InternalAspect;
    	$Global_Solar_InternalAspect = $this->sortInternalAspectByRank($AspectsList);
    }
        
    function CalculatingPoints($SR_planet, $SP_aspect, $natal_planet, $Orbs = 0) {
    	global $logger;
		global $AspectRank;
		global $NatalPlanetRank;
		global $TransitingPlanetRank;				
    
    	$SP_Rank = 0;
    	$NP_Rank = 0;
    	$ASP_Rank = 0;
    	$Orbs_Rank = 0;
    
    	if(array_key_exists($SR_planet, $TransitingPlanetRank)){
    		//$SP_Rank = $TransitingPlanetRank[$SR_planet];
    		$SP_Rank = 0;
    	}
    
    	if(array_key_exists($natal_planet, $NatalPlanetRank)){
    		//$NP_Rank = $NatalPlanetRank[$natal_planet];
    		$NP_Rank = 0;
    	}
    
    	if(array_key_exists($SP_aspect, $AspectRank)) {
    		$ASP_Rank = $AspectRank[$SP_aspect];
    	}
    	//$ASP_Rank = 10;
    	
    	$Orbs_Rank = $this->FindPointsBasedOnRanks($Orbs, $SP_aspect);
    	
    	//Calculate Total
    	$TotalRank = $SP_Rank + $ASP_Rank + $NP_Rank + $Orbs_Rank;
    
    	//echo "$SR_planet - $SP_aspect - $natal_planet " .  $finalDate . '<br />';    
    	return array("totalrank" => $TotalRank, "aspectrank" => $ASP_Rank, "planetrank" => $SP_Rank, "natalrank"  => $NP_Rank, "Orbs_Rank" => $Orbs_Rank);
    }
    
    function sortInternalAspectByRank($Solar_TransitSortedList){
    	$sortedtransits = array();
    	$Aspects = array();
    	
    	$sortedtransits = $this->msort($Solar_TransitSortedList);
    
    	while (list($key, $value) = each($sortedtransits)) {
    		$i = $key;    		
    		array_push($Aspects, $value);
    	}
    	return $Aspects;
    }
    
    function sortByRank($Solar_TransitSortedList) {
    	$sortedtransits = array();
    	unset($this->TopAspects);
    	$this->TopAspects = array();
    	
    	$sortedtransits = $this->msort($Solar_TransitSortedList);
    
    	while (list($key, $value) = each($sortedtransits)) {
    		$i = $key;    		
    		array_push($this->TopAspects, $value);
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
	 * FindPointsBasedOnRanks() returns the Points based on Orbs
	 * @param Numbers $Orbs
	 * @param Numbers $ASP_Rank
	 * @return number
	 */
	function FindPointsBasedOnRanks($Orbs, $ASP_Rank) {
// 		"000" => 10,	//"Conjunction",
// 		"060" => 3,		//"Sextile",
// 		"090" => 6,		//"Square",
// 		"120" => 8,		//"Trine",
// 		"180" => 8,		//"Opposition"
		$ReturnPoint = 0;
		
		if($ASP_Rank == '000'){
			if($Orbs >= 0 && $Orbs <= 2) {
				$ReturnPoint = 5;
			}
			else if($Orbs > 2 && $Orbs <= 4) {
				$ReturnPoint = 4;
			}
			else if($Orbs > 4 && $Orbs <= 6) {
				$ReturnPoint = 3;
			}
			else if($Orbs > 6 && $Orbs <= 8) {
				$ReturnPoint = 2;
			}
			else if($Orbs > 8 && $Orbs <= 10) {
				$ReturnPoint = 1;
			}	
			else if($Orbs > 10) {
				$ReturnPoint = 1;
			}		
		}
		else if($ASP_Rank == '060'){
			if($Orbs >= 0 && $Orbs <= 0.8) {
				$ReturnPoint = 7;
			}
			else if($Orbs > 0.8 && $Orbs <= 1.6) {
				$ReturnPoint = 6;
			}
			else if($Orbs > 1.6 && $Orbs <= 2.4) {
				$ReturnPoint = 5;
			}
			else if($Orbs > 2.4 && $Orbs <= 3.2) {
				$ReturnPoint = 4;
			}
			else if($Orbs > 3.2 && $Orbs <= 4) {
				$ReturnPoint = 3;
			}	
			else if($Orbs >  4) {
				$ReturnPoint = 0;
			}		
		}
		else if($ASP_Rank == '090'){
			if($Orbs >= 0 && $Orbs <= 1.2) {
				$ReturnPoint = 7;
			}
			else if($Orbs > 1.2 && $Orbs <= 2.4) {
				$ReturnPoint = 6;
			}
			else if($Orbs > 2.4 && $Orbs <= 3.6) {
				$ReturnPoint = 5;
			}
			else if($Orbs > 3.6 && $Orbs <= 4.8) {
				$ReturnPoint = 4;
			}
			else if($Orbs > 4.8 && $Orbs <= 6) {
				$ReturnPoint = 3;
			}
			else if($Orbs > 6) {
				$ReturnPoint = 1;
			}
		}
		else if($ASP_Rank == '120' || $ASP_Rank == '180') {
			if($Orbs >= 0 && $Orbs <= 1.6) {
				$ReturnPoint = 7;
			}
			else if($Orbs > 1.6 && $Orbs <= 3.2) {
				$ReturnPoint = 6;
			}
			else if($Orbs > 3.2 && $Orbs <= 4.8) {
				$ReturnPoint = 5;
			}
			else if($Orbs > 4.8 && $Orbs <= 6.4) {
				$ReturnPoint = 4;
			}
			else if($Orbs > 6.4 && $Orbs <= 8) {
				$ReturnPoint = 2;
			}
			else if($Orbs > 8) {
				$ReturnPoint = 1;
			}
		}
		
		return $ReturnPoint;
	}
}
?>