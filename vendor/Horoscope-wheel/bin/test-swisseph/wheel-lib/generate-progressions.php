<?php
class GenerateProgressionsPDF extends CommonPDFHelper {

    //Progressed to Natal Aspects
    var $aspectProgressedToNatal = array();

    var $MMScale = 0.26;
    var $LeftMargin = 20;
    var $RightMargin = 20;
    var $TopMargin = 20;
    var $TableWidth = 170;
    var $TableHeight = 10;
    var $TableCellWidth = 42.5;
    var $TableColumns = 4;
    var $TableX = 20;
    var $TableY = 20;

    var $TopAspects;
    var $AspectsList;

    var $bDay;
    var $bMonth;
    var $bYear;

    var $startYear;
    var $PreviousYear;
    var $NextYear;
    var $CurrntYear;


    //     var $NameOfPlanets = array(
    //         "1000" => "Sun",
    //         "1001" => "Moon",
    //         "1002" => "Mercury",
    //         "1003" => "Venus",
    //         "1004" => "Mars",
    //         "1005" => "Jupiter",
    //         "1006" => "Saturn",
    //         "1007" => "Uranus",
    //         "1008" => "Neptune",
    //         "1009" => "Pluto",
    //         "1010" => "N.Node",
    //         "1011" => "S.Node", /* put here to stop the nag messages, not used */
    //         "1012" => "Ascendant",
    //         "1013" => "Midheaven", /* "MC", */
    //         "1014" => "IC",
    //         "1015" => "Descendant",
    //     	"1016" => "Chiron"
    //     );

    //     var $NameOfAspects = array(
    //         "000" => "Conjunction",
    //         "060" => "Sextile",
    //         "090" => "Square",
    //         "120" => "Trine",
    //         "180" => "Opposition"
    //     );

    //     //http://stackoverflow.com/questions/1384380/is-there-a-unicode-glyph-that-looks-like-a-key-icon
    //     var $WowSymbolFonts = array(
    //         /* planets */
    //         'Sun' => 184, 'Moon' => 155,
    //         'Mercury' => 190, 'Venus' => 177, 'Mars' => 161,
    //         'Jupiter' => 165, 'Saturn' => 123,
    //         'Uranus' => 134, 'Neptune' => 135, 'Pluto' => 136,
    //     	'Chiron' => 9897,
    //         /* nodes */
    //         'N.Node' => 168, 'S.Node' => 130,
    //         /* signs */
    //         'Aries' => 247, 'Taurus' => 154, 'Gemini' => 208, 'Cancer' => 152,
    //         'Leo' => 172, 'Virgo' => 170, 'Libra' => 171, 'Scorpio' => 133,
    //         'Sagittarius' => 125, 'Capricorn' => 131, 'Aquarius' => 139, 'Pisces' => 138,
    //         /* aspects */
    //         'Conjunction' => 180, 'Semisextile' => 222, 'Semisquare' => 188, 'Sextile' => 181, 'Square' => 185,
    //         'Trine' => 186, 'Sesquisquare' => 164, 'Quincunx' => 222 /* quincunx = inverted semisextile */,
    //         'Opposition' => 175,
    //         /* angles */
    //         'Ascendant' => 124, 'Descendant' => 254, 'MC' => 91, 'IC' => 93,
    //         /* retrograde */
    //         'Retrograde' => 182
    //     );

    function GenerateProgressionsPDF($birthDTO, $ordDate) {
        parent::FPDF('P', 'mm', 'A4');

        $this->LeftMargin = 20;
        $this->TopMargin = 20;

        $this->TableX = $this->LeftMargin + 20;
        $this->TableY = $this->TopMargin + 20;

        $this->TableWidth = 170;
        $this->TableHeight = 10 ;
        $this->TableCellWidth = 42.5;
        $this->SetMargins($this->LeftMargin, $this->TopMargin, $this->RightMargin);

        $this->bDay = $birthDTO->day;
        $this->bMonth = $birthDTO->month;
        $this->bYear = $birthDTO->year;

        $this->startYear = $ordDate->format('Y');         //Actual Code
        $this->startEnd = $ordDate->format('Y') + 2;      //Actual Code

        //									  MM = Month          DD = Day            YYYY = Year
        $this->PreviousYear = date("Y-m-d", mktime ( 0, 0, 0, $this->bMonth, $this->bDay, $this->startYear - 1 ) );
        $this->NextYear = date("Y-m-d", mktime ( 0, 0, 0, $this->bMonth, $this->bDay, $this->startYear + 1 ) );
        $this->CurrntYear = date("Y-m-d", mktime ( 0, 0, 0, $this->bMonth, $this->bDay, $this->startYear ) );

        global $Global_Progression_m_transit;
        global $Global_Progression_m_crossing;
        global $Global_Progression_TransitSortedList;
        global $Global_Prog_Direct_Retrograde_List;
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

                if(count($Global_Progression_TransitSortedList) > 0) {
                    reset($Global_Progression_TransitSortedList);
                    foreach($Global_Progression_TransitSortedList as $Key => $Item) {
                        if($Item['pt'] == $progressed_planet && $Item['asp'] == $progressed_aspect && $Item['pn'] == $natal_planet && $Item['hitdate'] >= $this->CurrntYear) {
                            $CheckDuplicate = true;
                            break;
                        }
                    }
                }

                if($CheckDuplicate == false) {
                    array_push($Global_Progression_TransitSortedList, array("pt" => $progressed_planet,
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

//      unset($Global_Progression_TransitSortedList);
//      $Global_Progression_TransitSortedList = $this->TopAspects;
// 		echo "<pre>";
// 		print_r($Global_Progression_m_transit);
// 		echo "</pre>";

        foreach ($Global_Progression_m_crossing as $progression) {
            $progressed_planet = substr($progression, 17, 4);
            $progressed_aspect = substr($progression, 21, 3);
            $natal_planet = substr($progression, 24, 4);
            $IsRatrograde = substr($progression, 29, 1);

            $PointArray = array();
            $PointArray = $this->CalculatingPoints($progressed_planet, $progressed_aspect, $natal_planet, substr($progression, 0, 10));

            if($progressed_aspect == "-->") {
                if($natal_planet != "S/D" && $natal_planet != "S/R") {
                    array_push($Global_Progression_TransitSortedList, array("pt" => $progressed_planet,
                            "asp" => $progressed_aspect,
                            "pn" => $natal_planet,
                            "start" => $PointArray['start'], 	//$start_date,
                            "planetrank" => $PointArray['planetrank'], 	//Planet rank to set Priority,
                            "aspectrank" => $PointArray['aspectrank'], 	//Aspect rank to set Priority,
                            "natalrank" => $PointArray['natalrank'], 	//Natal planet rank to set Priority,
                            "hitdate" =>  $PointArray['start'],
                            "enddate" =>  '',
                            'isplanet' => 0,
                            'aspecttype' => 'PR',
                            'totalrank' => $PointArray['totalrank'],
                            'IsRatrograde' => $IsRatrograde));
                }
                else {
                    $SignNo = $this->GetSignNoForRetrogradeDirect($PointArray['start'], $progressed_planet);

                    array_push($Global_Prog_Direct_Retrograde_List, array("pt" => $progressed_planet,
                            "asp" => $natal_planet,
                            "pn" => $SignNo,
                            "start" => $PointArray['start'], 	//$start_date,
                            'isplanet' => 1,
                            'aspecttype' => 'P'));
                }
            }
        }

        $this->sortProgressionByDate($Global_Progression_TransitSortedList);

        unset($Global_Progression_TransitSortedList);
        $Global_Progression_TransitSortedList = $this->AspectsList;
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

    function ConvertMMToPixels($MM) {
        return ($MM * 300) / 25.4;
    }

    function ConvertPixelsToMM($Pixel) {
        return ($Pixel * 25.4) / 300;
    }

    function SetProgressedToNatalAspect() {
        //     	$cw=array(
        //     			chr(0)=>750,chr(1)=>750,chr(2)=>750,chr(3)=>750,chr(4)=>750,chr(5)=>750,chr(6)=>750,chr(7)=>750,chr(8)=>750,chr(9)=>750,chr(10)=>750,chr(11)=>750,chr(12)=>750,chr(13)=>750,chr(14)=>750,chr(15)=>750,chr(16)=>750,chr(17)=>750,chr(18)=>750,chr(19)=>750,chr(20)=>750,chr(21)=>750,
        //     			chr(22)=>750,chr(23)=>750,chr(24)=>750,chr(25)=>750,chr(26)=>750,chr(27)=>750,chr(28)=>750,chr(29)=>750,chr(30)=>750,chr(31)=>750,' '=>250,'!'=>333,'"'=>408,'#'=>500,'$'=>500,'%'=>833,'&'=>778,'\''=>180,'('=>333,')'=>333,'*'=>500,'+'=>564,
        //     			','=>250,'-'=>333,'.'=>250,'/'=>278,'0'=>500,'1'=>500,'2'=>500,'3'=>500,'4'=>500,'5'=>500,'6'=>500,'7'=>500,'8'=>500,'9'=>500,':'=>278,';'=>278,'<'=>564,'='=>564,'>'=>1698,'?'=>444,'@'=>921,'A'=>722,
        //     			'B'=>667,'C'=>667,'D'=>722,'E'=>611,'F'=>556,'G'=>722,'H'=>722,'I'=>333,'J'=>389,'K'=>722,'L'=>611,'M'=>889,'N'=>722,'O'=>722,'P'=>556,'Q'=>722,'R'=>667,'S'=>556,'T'=>611,'U'=>722,'V'=>722,'W'=>944,
        //     			'X'=>722,'Y'=>722,'Z'=>611,'['=>1683,'\\'=>278,']'=>945,'^'=>469,'_'=>500,'`'=>333,'a'=>444,'b'=>500,'c'=>444,'d'=>500,'e'=>444,'f'=>333,'g'=>500,'h'=>500,'i'=>278,'j'=>278,'k'=>500,'l'=>278,'m'=>778,
        //     			'n'=>500,'o'=>500,'p'=>500,'q'=>500,'r'=>333,'s'=>389,'t'=>278,'u'=>500,'v'=>500,'w'=>722,'x'=>500,'y'=>500,'z'=>444,'{'=>752,'|'=>1514,'}'=>940,'~'=>794,chr(127)=>750,chr(128)=>750,chr(129)=>750,chr(130)=>896,chr(131)=>932,
        //     			chr(132)=>444,chr(133)=>952,chr(134)=>868,chr(135)=>772,chr(136)=>737,chr(137)=>1000,chr(138)=>944,chr(139)=>876,chr(140)=>889,chr(141)=>750,chr(142)=>750,chr(143)=>750,chr(144)=>750,chr(145)=>333,chr(146)=>333,chr(147)=>444,chr(148)=>444,chr(149)=>350,chr(150)=>752,chr(151)=>500,chr(152)=>997,chr(153)=>980,
        //     			chr(154)=>871,chr(155)=>963,chr(156)=>722,chr(157)=>750,chr(158)=>750,chr(159)=>722,chr(160)=>250,chr(161)=>852,chr(162)=>500,chr(163)=>500,chr(164)=>645,chr(165)=>792,chr(166)=>200,chr(167)=>500,chr(168)=>838,chr(169)=>760,chr(170)=>997,chr(171)=>956,chr(172)=>956,chr(173)=>333,chr(174)=>760,chr(175)=>653,
        //     			chr(176)=>400,chr(177)=>596,chr(178)=>300,chr(179)=>300,chr(180)=>638,chr(181)=>641,chr(182)=>895,chr(183)=>250,chr(184)=>956,chr(185)=>601,chr(186)=>642,chr(187)=>931,chr(188)=>648,chr(189)=>750,chr(190)=>696,chr(191)=>444,chr(192)=>722,chr(193)=>722,chr(194)=>722,chr(195)=>722,chr(196)=>722,chr(197)=>722,
        //     			chr(198)=>889,chr(199)=>667,chr(200)=>611,chr(201)=>611,chr(202)=>611,chr(203)=>611,chr(204)=>333,chr(205)=>333,chr(206)=>333,chr(207)=>333,chr(208)=>956,chr(209)=>722,chr(210)=>722,chr(211)=>722,chr(212)=>722,chr(213)=>722,chr(214)=>722,chr(215)=>564,chr(216)=>722,chr(217)=>722,chr(218)=>722,chr(219)=>722,
        //     			chr(220)=>722,chr(221)=>722,chr(222)=>642,chr(223)=>500,chr(224)=>444,chr(225)=>444,chr(226)=>444,chr(227)=>444,chr(228)=>444,chr(229)=>444,chr(230)=>667,chr(231)=>444,chr(232)=>444,chr(233)=>444,chr(234)=>444,chr(235)=>444,chr(236)=>278,chr(237)=>278,chr(238)=>278,chr(239)=>278,chr(240)=>696,chr(241)=>500,
        //     			chr(242)=>500,chr(243)=>500,chr(244)=>500,chr(245)=>500,chr(246)=>500,chr(247)=>864,chr(248)=>500,chr(249)=>500,chr(250)=>500,chr(251)=>500,chr(252)=>500,chr(253)=>500,chr(254)=>1698,chr(255)=>500);

        //     	foreach ($cw as $k => $v) {
        //     		$this->MultiCell(100, 2, $k . ' -- ' .chr($v) . ' -- ' . $v);
        //      		$this->Ln();
        //     	}
        global $NameOfPlanets;
        global $NameOfAspects;
        global $WowSymbolFonts;

        $this->SetFont('wows', '', 14);
        $this->Cell($this->TableCellWidth, $this->TableHeight, 'Progressed aspect to Natal', 0, 0, 'C', false);
        $this->Ln();
        $this->SetFont('wows', '', 12);

        foreach ($this->aspectProgressedToNatal as $key => $progressedAspect) {
            //%04d%03d%04d %s [Format]
            $solarPlanet = intval(substr($progressedAspect, 0, 4));
            $progressedAspectValue = substr($progressedAspect, 4, 3);
            $aspectToPlanet = intval(substr($progressedAspect, 7, 4));
            $orbs = substr($progressedAspect, 12);

            $this->Cell($this->TableCellWidth, $this->TableHeight,
                    chr($WowSymbolFonts[$NameOfPlanets[$solarPlanet]]) . ' ' .$NameOfPlanets[$solarPlanet] ,
                    1, 0, 'L', false);

            $this->SetX($this->LeftMargin + $this->TableCellWidth);
            $this->Cell($this->TableCellWidth, $this->TableHeight,
                    chr($WowSymbolFonts[$NameOfAspects[$progressedAspectValue]])  . ' ' . $NameOfAspects[$progressedAspectValue],
                    1, 0, 'L', false);

            $this->SetX($this->LeftMargin + $this->TableCellWidth * 2);
            $this->Cell($this->TableCellWidth, $this->TableHeight,
                    chr($WowSymbolFonts[$NameOfPlanets[$aspectToPlanet]]) . ' ' . $NameOfPlanets[$aspectToPlanet] ,
                    1, 0, 'L', false);

            $this->SetX($this->LeftMargin + $this->TableCellWidth * 3);
            $this->Cell($this->TableCellWidth, $this->TableHeight, $orbs, 1, 0, 'L', false);
            $this->Ln();
        }
    }

    function GenerateProgressionList() {
        $bDate = sprintf('%04d-%02d-%02d', $this->startYear, $this->bMonth, $this->bDay);
        $bDate .= ' to ' . sprintf('%04d-%02d-%02d', $this->startYear + 1, $this->bMonth, $this->bDay);

        $this->SetFont('wows', '', 14);
        $this->Cell($this->TableCellWidth, $this->TableHeight, 'Sir - Progressed aspects ( ' . $bDate . ' )', 0, 0, 'L', false);
        $this->Ln();

        $this->SetFont('wows', '', 14);
        $this->Cell($this->TableCellWidth, $this->TableHeight, 'Current Year Progression', 0, 0, 'L', false);
        $this->Ln();

        $this->Cell($this->TableCellWidth, $this->TableHeight, 'Progressed Planet', 1, 0, 'L', false);

        $this->SetX($this->LeftMargin + $this->TableCellWidth);
        $this->Cell($this->TableCellWidth, $this->TableHeight, 'Aspect', 1, 0, 'L', false);

        $this->SetX($this->LeftMargin + $this->TableCellWidth * 2);
        $this->Cell($this->TableCellWidth, $this->TableHeight, 'Natal Planet', 1, 0, 'L', false);

        $this->SetX($this->LeftMargin + $this->TableCellWidth * 3);
        $this->Cell($this->TableCellWidth, $this->TableHeight, 'Progression Date', 1, 0, 'L', false);
        $this->Ln();

        /***********************/
        global $NameOfPlanets;
        global $NameOfAspects;
        global $NameOfHouses;
        global $WowSymbolFonts;
        global $AspectRank;
        global $ProgressedNatalPlanetRank;
        global $ProgressedPlanetRank;
        global $Global_Progression_CurrentYear;

        $cYear = $this->CurrntYear;
        $newdate = strtotime ( '-6 month' , strtotime ( $cYear ) ) ;
        $newdate = date ( 'Y-m-d' , $newdate );
        $this->CurrntYear = $newdate;

        $cNext = $this->NextYear;
        $nextnewdate = strtotime ( '+6 month' , strtotime ( $cNext ) ) ;
        $nextnewdate = date ( 'Y-m-d' , $nextnewdate );
        $this->NextYear = $nextnewdate;

        foreach ($this->TopAspects as $key => $trWin) {
            $pt = trim( $trWin['pt'] );
            $asp = trim( $trWin['asp'] );
            $pn =  trim( $trWin['pn'] );

            $dt =  trim( $trWin['start'] );
            $rank = trim( $trWin['totalrank'] );

            if($dt >= $this->CurrntYear && $dt <= $this->NextYear ) {
                if($pt == $pn) {
                } else {
                    array_push($Global_Progression_CurrentYear, $trWin);

//					$this->Cell($this->TableCellWidth, $this->TableHeight,
//							$NameOfPlanets[$pt] . ' - ' . $ProgressedPlanetRank[$pt],
//							1, 0, 'L', false);
//
//					$this->SetX($this->LeftMargin + $this->TableCellWidth);
//					$this->Cell($this->TableCellWidth, $this->TableHeight,
//							chr($WowSymbolFonts[ $NameOfAspects[$asp]])  . ' ' . $NameOfAspects[$asp] . ' - ' . $AspectRank[$asp],
//							1, 0, 'L', false);
//
//					$this->SetX($this->LeftMargin + $this->TableCellWidth * 2);
//					if($trWin['isplanet'] == 1) {
//						$this->Cell($this->TableCellWidth, $this->TableHeight,
//								$ProgressedNatalPlanetRank[$pn] . ' - ' . $NameOfPlanets[$pn] . ' - ' . $rank,
//								1, 0, 'L', false);
//					}
//					else {
//						$this->Cell($this->TableCellWidth, $this->TableHeight,
//								$NameOfHouses[$pn] . ' - ' . $rank ,
//								1, 0, 'L', false);
//
//					}
//
//					$content = date('Y-M-d', mktime(0, 0, 0,substr($dt, 5,2),substr($dt, 8, 2),substr($dt, 0,4)) );
//
//					$this->SetX($this->LeftMargin + $this->TableCellWidth * 3);
//					$this->Cell($this->TableCellWidth, $this->TableHeight, $content, 1, 0, 'L', false);
//					$this->Ln();
                }
            }
        }
    }

    function FullListOfProgression() {
        $this->Ln();
        $this->Ln();

        $this->SetFont('wows', '', 14);
        $this->Cell($this->TableCellWidth, $this->TableHeight, 'Full List of Progression', 0, 0, 'L', false);
        $this->Ln();

        $this->Cell($this->TableCellWidth, $this->TableHeight, 'Progressed Planet', 1, 0, 'L', false);

        $this->SetX($this->LeftMargin + $this->TableCellWidth);
        $this->Cell($this->TableCellWidth, $this->TableHeight, 'Aspect', 1, 0, 'L', false);

        $this->SetX($this->LeftMargin + $this->TableCellWidth * 2);
        $this->Cell($this->TableCellWidth, $this->TableHeight, 'Natal Planet', 1, 0, 'L', false);

        $this->SetX($this->LeftMargin + $this->TableCellWidth * 3);
        $this->Cell($this->TableCellWidth, $this->TableHeight, 'Progression Date', 1, 0, 'L', false);
        $this->Ln();

        /***********************/
        global $NameOfPlanets;
        global $NameOfAspects;
        global $WowSymbolFonts;
        global $NameOfSigns;
        global $Global_Language;

        global $AspectRank;
        global $ProgressedNatalPlanetRank;
        global $ProgressedPlanetRank;

        foreach ($this->AspectsList as $key => $trWin) {
            $pt = trim( $trWin['pt'] );
            $asp = trim( $trWin['asp'] );
            $pn =  trim( $trWin['pn'] );
            $dt =  trim( $trWin['start'] );
            $rank = trim( $trWin['totalrank'] );

            $this->Cell($this->TableCellWidth, $this->TableHeight,
                    $NameOfPlanets[$pt] . ' - ' . $ProgressedPlanetRank[$pt],
                    1, 0, 'L', false);

            $this->SetX($this->LeftMargin + $this->TableCellWidth);

            if($asp != 'S/D' &&  $asp != 'S/R') {
                if($trWin['isplanet'] == 1) {
                    $this->Cell($this->TableCellWidth, $this->TableHeight,
                            chr($WowSymbolFonts[ $NameOfAspects[$asp]])  . ' ' . $NameOfAspects[$asp] . ' - ' . $AspectRank[$asp],
                            1, 0, 'L', false);
                }
                else {
                    $this->Cell($this->TableCellWidth, $this->TableHeight, 'Sign Changing ', 1, 0, 'L', false);
                }
            }
            else {
                $this->Cell($this->TableCellWidth, $this->TableHeight, ' ' . $NameOfAspects[$asp] , 1, 0, 'L', false);
            }

            $this->SetX($this->LeftMargin + $this->TableCellWidth * 2);

            if($asp != 'S/D' &&  $asp != 'S/R') {
                if($trWin['isplanet'] == 1) {
                    $this->Cell($this->TableCellWidth, $this->TableHeight, $ProgressedNatalPlanetRank[$pn] . ' - ' . $NameOfPlanets[$pn] . ' - ' . $rank, 1, 0, 'L', false);
                }
                else {
                    $this->Cell($this->TableCellWidth, $this->TableHeight, $NameOfSigns[$Global_Language][$pn] . ' - ' . $rank , 1, 0, 'L', false);
                }
            }

            $content = $dt;

            $this->SetX($this->LeftMargin + $this->TableCellWidth * 3);
            $this->Cell($this->TableCellWidth, $this->TableHeight, $content, 1, 0, 'L', false);
            $this->Ln();
        }
    }

    function CalculatingPoints($progressed_planet, $progressed_aspect, $natal_planet, $progressiondate) {
        global $logger;
        global $AspectRank;
        global $ProgressedNatalPlanetRank;
        global $ProgressedPlanetRank;

        $PP_Rank = 0;
        $NP_Rank = 0;
        $ASP_Rank = 0;

        if(array_key_exists($progressed_planet, $ProgressedPlanetRank)) {
            $PP_Rank = $ProgressedPlanetRank[$progressed_planet];
        }

        if(array_key_exists($natal_planet, $ProgressedNatalPlanetRank)) {
            $NP_Rank = $ProgressedNatalPlanetRank[$natal_planet];
        }

        if(array_key_exists($progressed_aspect, $AspectRank)) {
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

// 		if($progressed_planet == '1013' && $natal_planet == '1007') {
// 			echo "<pre>$progressed_planet, $progressed_aspect, $natal_planet [ $finalDate to $endDate ]<pre>";
// 		}

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
                return $a['totalrank'] > $b['totalrank'] ? -1 : 1;
            }
        }
        $this->TopAspects = array();
        //$sortedtransits = $this->msort($Global_Progression_TransitSortedList);
        $sortedtransits = $Global_Progression_TransitSortedList;
        usort($sortedtransits, 'sortByTotalRank');

        while (list($key, $value) = each($sortedtransits)) {
            array_push($this->TopAspects, $value);
        }

        /* sort the transits, removing duplicates along the way */
//     	$sortedtransits = array();
//     	for ($aspect = 0; $aspect < count($this->TopAspects); $aspect++) {
//     		$sortme = $this->TopAspects[$aspect]['asp'];
//     		$sortedtransits[$sortme] = $this->TopAspects[$aspect];
//     	}

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
                return $a['totalrank'] > $b['totalrank'] ? -1 : 1;
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