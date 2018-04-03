<?php 
/**
 * @filesource birthanalyzer.php
 * @author Amit Parmar <amit@n-techcorporate.com>
 * @copyright Copyright (c) 2014-2015 World of Wisdom, Astrowow And Amit Parmar
 * @version 1.0
 *
 * @Desc
 * 	<p>
 * 		This is the Brith Analyzer class which collects the date from the Astrolog and
 * 		do the basic ANALYSIS and produce the RAW result to calling function to generate final output
 * </p>
 */


$PlanetsArray  = array('Dummy', 'Sun', 'Moon', 'Mercury', 'Venus', 'Mars', 'Jupiter', 'Saturn', 'Uranus', 'Neptune', 'Pluto', 'NNode', 'SNode');
$SignsArray    = array('Aries', 'Taurus', 'Gemini', 'Cancer', 'Leo', 'Virgo', 'Libra', 'Scorpio', 'Sagittarius', 'Capricorn', 'Aquarius', 'Pisces');
$RulersArray   = array(5, 4, 3, 2, 1, 3, 4, 5, 6, 7, 7, 6);
$CorulersArray = array(0, 0, 0, 0, 0, 0, 0, 10, 0, 0, 8, 9);


class BirthAnalyzer extends AstrologAPIBridge {

	var $AnalysisType = 'mini-a';

	var $Current_Chapter;
	var $Current_Planet;
	var $Current_AspectReference;
	var $Current_AspectORB;
	var $Current_RecordContext;
	var $Current_RecordContextValue;
	var $Current_Retrograde;
	var $Current_RecordNum;

	var $Current_ChartRuler;
	var $Current_ChartCoruler;

	var $Current_AnalysisContext;
	var $Current_AnalysisContextIndex;
	var $Current_AnalysisContextDebug;

	var $isTimedData;

	var $PlanetsList;

	var $CurrntYear, $NextYear;
	var $FinalTransitArray = array();

	/** PL>NE>UR>SA>JU */
	var $PlanetsPointsArray  = array(
			1000	=>	0,				/** SUN */
			1001	=>	0,				/** Moon */
			1002	=>	0,				/** Mercury */
			1003	=>	0,				/** Venus */
			1004	=>	0,				/** Mars */
			1005	=>	1,				/** Jupiter */
			1006	=>	2,				/** Saturn */
			1007	=>	3,				/** Uranus */
			1008	=>	4,				/** Neptune */
			1009	=>	5,				/** Pluto */
			1010	=>	0,				/** N.Node */
			1011	=>	0,				/** S.Node */
			1012	=>	0,				/** Ascendant */
			1013	=>	0,				/** Midheaven */
			1014	=>	0,				/** IC */
			1015	=>	0);				/** Descendant */

	/** 0 - 180 - 120 - 90 - 60 */
	var $AspectPointsArray  = array(
			0	=>	5,				/** Conjunction */
			180	=>	4,				/** Opposition */
			120	=>	3,				/** Trine */
			90	=>	2,				/** Square */
			60	=>	1);				/** Sextile */

	var $AllowTransitToPlanet = array(
			1000,				/** SUN */
			1002,				/** Mercury */
			1003,				/** Venus */
			1004,				/** Mars */
			1005,				/** Jupiter */
			1006,				/** Saturn */
			1007,				/** Uranus */
			1008,				/** Neptune */
			1009,				/** Pluto */);

	function BirthAnalyzer($BirthData, $StartYear, $Duration = 3, $ReportType, $OrderDate, $IsDebug = false) {
		global $PlanetsArray;
		global $SignsArray;
		global $RulersArray;
		global $CorulersArray;

		//strtotime('2013-01-19 01:23:42')
		$ReportYear = substr($StartYear, 0, 4);
		$TempDate = strtotime(sprintf("%04d-%02d-%02d 00:00:00", $ReportYear, $BirthData->month, $BirthData->day));
		$TempDate = strtotime("-6 month", $TempDate);
		//$this->CurrntYear = date("Y-m-d", $TempDate);
		// 		$TempDate = strtotime("+6 month", $TempDate);
		// 		$this->NextYear = date("Y-m-d", strtotime("+1 year", $TempDate));

		$OrderDate = strtotime(sprintf("%04d-%02d-%02d", date("Y", strtotime($OrderDate)), date("m", strtotime($OrderDate)), date("d", strtotime($OrderDate))));
		$this->CurrntYear = date("Y-m-d", strtotime("-3	month", $OrderDate));
		$this->NextYear = date("Y-m-d", $OrderDate);

		echo "<pre> " .date('Y-m-d', $OrderDate). " ::: $this->CurrntYear ::: $this->NextYear :: </pre> ";

		$this->AnalysisType = $ReportType;

		//Generating Birth chart
		$this->AstrologAPIBridge( $BirthData );

		//Initialise variables to default values
		$this->Current_AnalysisContextDebug = $IsDebug;
		$this->Current_AnalysisContext = '';

		$this->isTimedData = $BirthData->timed_data;  // isTimedData == TRUE [Normal chart] || FALSE [Solar Chart]

		$this->PlanetsList = array(
				'Dummy',							// DUMMY ENTRY to manage planet index
				'Sun',		'Moon',					// PERSONAL PLANETS
				'Mercury',	'Venus',	'Mars',		// PERSONAL PLANETS
				'Jupiter',	'Saturn',
				'Uranus',	'Neptune',	'Pluto',
				'NNode',	'SNode'
		);
		$this->Current_RecordNum = 0;

		$this->SortAspects();
		//$this->FindClosetAspect();

		/** build the ephemeris data array */
		$this->calcEphemeris( $StartYear, $Duration );
		/**
		 * Calculate the dynamic aspects look for transits to natal planets sort transits into ascending natal, transit planetary order
		 * remove duplicate entries
		*/
		$this->calcDynamicAspects( $BirthData, $StartYear, $Duration );

		/**
		 * iterate through the sorted transits
		 * determine the transit windows
		 * capture the window start and end dates
		 * capture the dynamic graph data
		*/
		/** %04d-%02d-%02d %02d:%02d %04d%03d%04d=%02d **/
		/** 2014-06-24 12:00 PPPPAAAPPPP=09 **/
		/** 0123456789123456789012345678901 [31 CHAR] **/
		$rIndex = 0;
		foreach( $this->bridge_transit as $transit) {
			$Original = substr($transit,0,28);

			$transiting_planet = substr($transit,17,4);
			$transiting_aspect = substr($transit,21,3);
			$natal_planet = substr($transit,24,4);
			$TotalPoints = substr($transit, strlen($transit) - 2, 2);

			$tDate = trim( substr($transit, 0, 10) );
			//									     MM = Month             DD = Day               YYYY = Year
			$tDate = date("Y-m-d", mktime ( 0, 0, 0, substr( $tDate, 8, 2), substr( $tDate, 5, 2), substr( $tDate, 0, 4) ));
				
			if($transiting_planet > 1004 && $transiting_planet < 1010) {
				if($natal_planet >= 1000 && $natal_planet <= 1009) {
					//$ArrayList = $this->getEphemerisTransitWindow($transiting_planet,$transiting_aspect,$natal_planet);
					$ArrayList = $this->FindTransitAndTotalRank($transiting_planet, $transiting_aspect, $natal_planet, $tDate, $TotalPoints);
						
					$this->bridge_transit_window[$transiting_planet][$transiting_aspect][$natal_planet] =  $ArrayList["object"];
					$this->bridge_transit[$rIndex] = sprintf("%s=%02d", $Original, $ArrayList["totalpoint"]);
				}
			}

			$rIndex++;
		}

		$this->SortPlanetTransit();

		$this->packPlanets();

		if(strtolower($this->AnalysisType) == 'mini-b') {
			$this->packTransitingPlanets();
		}
		$this->GoToFirst();
	}

	/**
	 * Setup the Recorder
	 */
	function packPlanets() {
		/** echo "<pre>********************* packPlanets()</pre>"; **/
		/**
		 * 1.1.1. "1000"	=>	"Sun",		[ MINI-A | MINI-B ]
		 * 1.1.2. "1001"	=>	"Moon",  	[We dont want MOON]
		 * 1.1.3. "1002"	=>	"Mercury",	[ MINI-A | MINI-B ]
		 */
		/**
		 for( $PlanetIndex = 1; $PlanetIndex <= 3; $PlanetIndex++ ) {
			if(($PlanetIndex) == 2) {
			} else {
			$this->Current_Chapter = $PlanetIndex;
			$this->Current_Retrograde = $this->bridge_object[$this->PlanetsList[$PlanetIndex]]['retrograde'] === true ? 1 : 0;

			$this->PackPlanetInSign($PlanetIndex);
			}
			}
			*/

		if(strtolower($this->AnalysisType) == 'mini-b') {
			for( $PlanetIndex = 1; $PlanetIndex <= 9; $PlanetIndex++ ) {
				if(($PlanetIndex - 1) == 1) {
				} else {
					$this->Current_Chapter = $PlanetIndex;
					$this->Current_Retrograde = $this->bridge_object[$this->PlanetsList[$PlanetIndex]]['retrograde'] === true ? 1 : 0;
					$this->packPlanetInAspect($PlanetIndex - 1);
				}
			}
		}
	}

	function packTransitingPlanets() {
		/** echo "<pre>********************* packTransitingPlanets()</pre>"; **/

		for( $PlanetIndex = 1; $PlanetIndex < 10; $PlanetIndex++ ) {
			$this->Current_Chapter = $PlanetIndex;
			$this->Current_Retrograde = $this->bridge_object[$this->PlanetsList[$PlanetIndex]]['retrograde'] === true ? 1 : 0;

			$this->PackTransitingPlanetByAspect($PlanetIndex - 1);
		}
	}

	/*
	 #YYYY-MM-DD HH:MM PPPPAAAPPPP
	#----------1111111111222222222233333333334444444444555555555566666666667
	#01234567890123456789012345678901234567890123456789012345678901234567890
	#CCCCCPPPPAAAPPPPR000010DDMMYYYYDDMMYYYY0SSSTTXXX
	*/
	function PackTransitingPlanetByAspect($planet) {
		/* loop through the transits */
		for( $aspect = 0; $aspect < count($this->bridge_transit); $aspect++ ) {

			/* if this is the natal planet, then we are interested */
			if( intval(substr($this->bridge_transit[$aspect], 24, 4)) == (1000 + $planet) ) {

				$this->Current_Planet = intval(substr($this->bridge_transit[$aspect],17,4))-1000;
				$this->Current_AspectReference = intval(substr($this->bridge_transit[$aspect],21,3));
				$this->Current_RecordContext = 11;
				$this->Current_RecordContextValue = sprintf("%02d", intval(intval(substr($this->bridge_transit[$aspect],24,4)))-1000);;

				//$this->m_planet = intval(substr($this->bridge_transit[$aspect],17,4))-1000;
				//$this->m_aspect_ref = intval(substr($this->bridge_transit[$aspect],21,3));
				//$this->m_record_context = 10;
				//$this->m_record_context_value = sprintf("%02d", intval(intval(substr($this->bridge_transit[$aspect],24,4)))-1000);

				$fmtStartDate = $this->bridge_transit_window[$this->Current_Planet+1000][sprintf("%03d",$this->Current_AspectReference)][$planet+1000]['start'];

				$start_date = sprintf("%02d%02d%04d",
						/* day	*/		intval( substr( $fmtStartDate, 4, 2 )),
						/* month	*/	intval( substr( $fmtStartDate, 6, 2 )),
						/* year	*/		intval( substr( $fmtStartDate, 0, 4 ))
				);

				//$fmtEndDate = $this->bridge_transit_window[$this->m_planet][sprintf("%03d",$this->m_aspect_ref)][$planet]['end'];
				//CHANGE BY AMIT PARMAR
				$fmtEndDate = $this->bridge_transit_window[sprintf("%04d",$this->Current_Planet+1000)][sprintf("%03d",$this->Current_AspectReference)][sprintf("%04d",$planet+1000)]['end'];

				$end_date = sprintf("%02d%02d%04d",
						/* day	*/			intval( substr( $fmtEndDate, 4, 2 )),
						/* month	*/		intval( substr( $fmtEndDate, 6, 2 )),
						/* year	*/			intval( substr( $fmtEndDate, 0, 4 ))
				);

				/* look for repeat aspects */
				$this->PackDynamicRecord( $start_date, $end_date );
			}
		}
	}

	function PackDynamicRecord( $StartDate, $EndDate ) {
		/* production formatting */
		if($this->Current_AnalysisContextDebug === false) {
			$fmtstr = "%05d%04d%03d%02d%02d%d%d%03d%d%d%08d%08d%d%03d%02d%03d";
		} else {
			/* development formatting */
			$fmtstr = "%05d-%04d-%03d-%02d%02d-%d-%d-%03d-%d-%d-%08d-%08d-%d-%03d-%02d-%03d\n";
		}

		$IsRetrograde = $this->Current_Retrograde;

		$AspectStrength = 2;	/* default to mild */

		switch( $this->Current_AspectReference ) {
			case 0:		$AspectStrength = 0;			break;
			case 60: case 120:	$AspectStrength = 1;	break;	/* return no soft aspects */
			case 90: case 180:	$AspectStrength = 2;	break;
			default:
				// error
				$AspectStrength = 0;
		}


		switch( $this->Current_AspectReference ) {
			case 0: 						/** Conjunction */
				$Aspect_Type = 0;
				break;
			case 60: 						/** Sextile */
				$Aspect_Type = 1;
				break;
			case 90:						/** Squre */
			case 120:						/** Trine */
				$Aspect_Type = 2;
				break;
			case 180:						/** Opposition */
				$Aspect_Type = 2;
				break;
			default:
				//ERROR
				$Aspect_Type = 0;
		}

		$NewRecord = sprintf( $fmtstr,
				$this->Current_Chapter + 10001,
				$this->Current_Planet + 1000,
				$this->Current_AspectReference,
				$this->Current_RecordContext,
				$this->Current_RecordContextValue,
				$IsRetrograde,						/* retrograde */
				0,									/* appear before */
				0,									/* page no */
				2,									/* static = 0, dynamic = 1 and dynamic-t = 2*/
				0,									/* include next */
				$StartDate,							/* DDMMYYYY start date */
				$EndDate,							/* DDMMYYYY end date */
				0,									/* repeat in future */
				$AspectStrength,					/* aspect strength	{ 0 => strong,		1 => medium,	2 => weak	} */
				$Aspect_Type,						/* aspect type		{ 0 => conjoins,	1 => positive,	2 => negative	} */
				$this->Current_RecordNum++
		);

		$this->Current_AnalysisContext .= $NewRecord;
	}

	/**
	 * Planet in Sign
	 * Format = 01PP
	 * Where PP = 00 (Sun) through to 09 (Pluto), 10-11 (N/S Nodes) and 12 (Ascendant)
	 * Need to be aware of the cross referencing here as it is based
	 * on the subindexed digits which can have a disorienting effect
	 * if we are in the wrong chapter.
	 *
	 * @param Integer $PlanetNo [ 1 .. TO .. 10 ]
	 */
	function PackPlanetInSign($PlanetNo ) {		//1 .. 10
		$this->Current_Planet = ($PlanetNo-1);
		$this->Current_AspectReference = "200";
		$this->Current_AspectORB = 0.0;
		$this->Current_RecordContext = 1;

		$PlanetDegree = floatval($this->bridge_object[$this->PlanetsList[$PlanetNo]]['longitude']);

		$PlanetInSign = intval( ( floatval ( $PlanetDegree ) / 30.0 ) );
		$this->Current_RecordContextValue = sprintf("%02d", $PlanetInSign);
		$this->PackRecord();
	}


	/**
	 * Checking for Planet in House
	 * @param Integer $PlanetNo  [ 1 .. TO .. 10 ]
	 */
	function PackPlanetInHouse($PlanetNo) {

		$this->Current_AspectReference = "200";
		$this->Current_AspectORB = 0.0;
		$this->Current_RecordContext = 0;
		$this->Current_RecordContextValue =sprintf("%02d", intval($this->bridge_object[$this->PlanetsList[$PlanetNo]]['house']));

		$this->PackRecord();
	}

	/**
	 * Go through the Aspect and setup for the report text
	 * @param Integer $PlanetNo [ 1 .. TO .. 10 ]
	 */
	function PackPlanetInAspect($PlanetNo) {
		/** echo "<pre>******************************* PackPlanetInAspect() $PlanetNo</pre>"; **/

		for( $Aspect = 0; $Aspect < count($this->bridge_aspect); $Aspect++ ) {
			if( intval( substr ( $this->bridge_aspect[$Aspect], 0, 4 ) ) == ( 1000 + $PlanetNo ) ) {

				$this->Current_Planet = ($PlanetNo);
				$this->bridge_aspect_ref = intval( substr ( $this->bridge_aspect[$Aspect], 4, 3) );
				$this->Current_AspectReference = intval( substr ( $this->bridge_aspect[$Aspect], 4, 3) );
				$this->Current_AspectORB = substr( $this->bridge_aspect[$Aspect], 12, 5 );
				$this->Current_RecordContext = 10;
				$this->Current_RecordContextValue = sprintf( "%02d", intval ( intval ( substr ( $this->bridge_aspect[$Aspect], 7, 4 ) ) ) - 1000 );

				$this->PackRecord();
				//$this->packAspectRecord();
			}
		}
	}

	/**
	 * Sort the Aspects and also removing duplicates aspects
	 * Array (
		[0] => 10000901009 +0:22
		[1] => 10040001006 +4:13
		[2] => 10031801008 -0:16
		[3] => 10040001009 +3:46
		[4] => 10020901006 +2:24
		[5] => 10031201009 -1:01
		[6] => 10020001010 +0:58
		[7] => 10000901004 -3:24
		[8] => 10080601009 +0:45 )
		1000 = SUN
		1002 = Mercury
		1003 = Venus
		1004 = Mars
		1005 = Jupiter
		1006 = Saturn
		1007 = Uranus
		1008 = Neptune
		1009 = Pluto
	 */
	function SortAspects() {
		global $top_object1;
		global $top_object2;
		global $top_connector;
		
		$OPArray = array(1000, 1002, 1003, 1004);
		$NPArray = array(1005, 1006, 1007, 1008, 1009);
		//Con => Opp => Tri => Squ => Sex
		$OrbArray = array("000" => 10, "180" => 9, "120" => 8, "090" => 7, "060" => 6);

		$sortedaspects = array();
		$linqArray = array();
		
		for( $aspect = 0; $aspect < count($this->bridge_aspect); $aspect++ ) {
			$OuterPlanet = substr($this->bridge_aspect[$aspect],0,4);
			$AspectType = substr($this->bridge_aspect[$aspect],4,3);
			$NatalPlanet = substr($this->bridge_aspect[$aspect],7,4);
			$Orb = substr($this->bridge_aspect[$aspect],13);
				
			if(in_array($OuterPlanet, $OPArray) && in_array($NatalPlanet, $NPArray)) {
				//$sortedaspects[substr($this->bridge_aspect[$aspect],0,4).substr($this->bridge_aspect[$aspect],7,4)] = $this->bridge_aspect[$aspect];
				//$sortedaspects[substr($this->bridge_aspect[$aspect],13)] = $this->bridge_aspect[$aspect];

				$Orb =  abs(floatval( str_replace(":", ".",  $Orb)));
				$COrbRank = $OrbArray[$AspectType];

				$obj = array( new AspectsObjects($OuterPlanet, $NatalPlanet, $AspectType, $Orb, $COrbRank , $this->bridge_aspect[$aspect]));
				$linqArray = array_merge($linqArray, $obj);
				
				reset($top_object1);
				reset($top_object2);
				reset($top_connector);
				echo "$Orb == $COrbRank == ". $top_object1['en'][sprintf("%04d",$OuterPlanet)] . " = ". $top_connector['en'][sprintf("%03d",$AspectType)] . " = ". $top_object2['en'][sprintf("%04d",$NatalPlanet)] . " <br />";
			}
		}
		
		$itsMe = from('$a')->in($linqArray)
							->thenByDescending('$a => $a->NewOrbs')
							->select('$a');
// 		echo "<pre>ORB RANK <br />";
// 		print_r($itsMe);
		
		$itsMe = from('$a')->in($linqArray)
							->thenByDescending('$a => $a->NewOrbs')
							->take(1)
							->select('$a');
		print_r($itsMe);
		
// 		$itsMe = from('$a')->in($linqArray)
// 				->thenByDescending('$a => $a->OrbRank')
// 				->thenBy('$a => $a->Orbs')
// 				->take(1)
// 				->select('$a');

		unset($this->bridge_aspect);
		$this->bridge_aspect = array();

		foreach($itsMe as $key => $item) {
			array_push($this->bridge_aspect, $item->ResultSet);
		}
		return;

		/** We dont required following code now. [16-Nov-2014]. Because we have implemented LINQ
		 ksort($sortedaspects);

		 reset($sortedaspects);
		 $rIndex = 0;

		 while( list($key,$value) = each($sortedaspects) ) {
			if(substr($value, 0, 4) != "1001") {
			array_push($this->bridge_aspect, $value);
			$rIndex++;
			}
			if($rIndex > 0)
				break;
			}
			*/
	}

	function SortTransits() {
		/* sort the transits, removing duplicates along the way */
		$sortedtransits = array();
		for( $aspect = 0; $aspect < count($this->bridge_transit); $aspect++ ) {
			$sortedtransits[substr($this->bridge_transit[$aspect],17,11)] = $this->bridge_transit[$aspect];
		}
		ksort($sortedtransits);

		unset($this->bridge_transit);
		$this->bridge_transit = array();
		reset($sortedtransits);
		while( list($key,$value) = each($sortedtransits) ) {
			array_push(
			$this->bridge_transit,
			$value
			);
		}
	}

	/**
	 * PackRecord function will help us to setup for Report text generation
	 *
	 * There are 0 to 48 Char
	 *
	 * 12345-1234-123-12-12-1-1-123-1-1-123456789.123456-1-123-12-123
	 * 00000-0000-000-00-00-0-0-000-0-0-0000000000000000-0-000-00-000
	 *
	 * 00 -  5 - Principle planet or object
	 * 05 -  4 - Secondary planet or object
	 * 09 -  3 - Connection (aspect or in/200)
	 * 12 -  2 - Scope (sign, house, aspect)
	 * 14 -  2 - Scope context (wrt above)
	 * 16 -  1 - Retrograde
	 * 17 -  1 - Appear before (duplicate)
	 * 18 -  3 - Page number
	 * 21 -  1 - Static
	 * 22 -  1 - Include next
	 * 23 - 16 - Start/End date
	 * 39 -  1 - Repeated in future
	 * 40 -  3 - Aspect Strength
	 * 43 -  2 - Aspect Type
	 * 45 -  3 - Record Index
	 */
	function packRecord() {
		/* Production Formatting */
		if($this->Current_AnalysisContextDebug === false) {
			//	 	12345-1234-123-12-12-1-1-123-1-1-123456789.123456-1-123-12-123
			$fmtstr = "%05d%04d%03d%02d%02d%d%d0000000000000000000000%03d%02d%03d";
		} else {
			/* Development Formatting */
			//			12345-1234-123-12-12-1-1-123-1-1-123456789.123456-1-123-12-123
			$fmtstr = "%05d-%04d-%03d-%02d%02d-%d-%d-0000000000000000000000-%03d-%02d-%03d\n";
		}

		$isRetrograde = $this->Current_Retrograde;

		/**
		 * TODO - this needs to be addressed once the PC3 report is stable valid set = 1..3
		 */
		if( substr($this->Current_AspectORB, 0, 1) == '+' ) {
			//Aspect is applying
		} else {
			//Aspect is Separating
		}

		/*
		 * TODO - this is still 0 (Conjunct), 1 (soft), 2 (hard). need to extract actual aspects
		* Suggest line up with Astrolog settings
		* 1 (Conjunction), 2 (Opposition), 3 (Square), 4 (Trine), 5 (Sextile)
		*/
		/**
		 *  TODO - this is still 0 (Conjunct), 1 (soft), 2 (hard). need to extract actual aspects
		 * Suggest line up with Astrolog settings
		 * 1 [ Conjunction ] , 2 [ Opposition ], 3 [ Square ], 4 [ Trine ], 5 [ Sextile ]
		 *
		 */
		switch( $this->Current_AspectReference ) {
			case 0: 						/* Conjunction */

				$Aspect_Type = 0;
				/**
				 * Aspect is hardcoded to 9.0 at the moment
				 * - STRONG < 2.0
				 * - MEDIUM < 5.0
				 * - WEAK   >= 5.0
				 */
				switch( intval( substr($this->Current_AspectORB,1,1) ) ) {
					case 0: case 1:
						//Aspect is Strong
						$Aspect_Strength = 0;
						break;
					case 2: case 3: case 4:
						//Aspect is Medium
						$Aspect_Strength = 1;
						break;
					default:
						//Aspect is Weak");
						$Aspect_Strength = 2;
						break;
				}
				break;
			case 60: /* Sextile */
				/**
				 * Aspect is hardcoded to 4.0 at the moment
				 * - STRONG < 1.0
				 * - MEDIUM < 3.0
				 * - WEAK   >= 3.0
				 */
				$Aspect_Type = 1;
				switch( intval( substr($this->Current_AspectORB,1,1) ) ) {
					case 0:
						//Aspect is Strong");
						$Aspect_Strength = 0;
						break;
					case 1: case 2:
						//Aspect is Medium");
						$Aspect_Strength = 1;
						break;
					default:
						//Aspect is Weak");
						$Aspect_Strength = 2;
						break;
				}
				break;
			case 90:
			case 120:
				/**
				 * Aspect is hardcoded to 6.0 at the moment
				 * - STRONG < 2.0
				 * - MEDIUM < 4.0
				 * - WEAK   >= 4.0
				 */
				$Aspect_Type = 2;
				switch( intval( substr($this->Current_AspectORB,1,1) ) ) {
					case 0: case 1:
						//Aspect is Strong
						$Aspect_Strength = 0;
						break;
					case 2: case 3:
						//Aspect is Medium
						$Aspect_Strength = 1;
						break;
					default:
						//Aspect is Weak
						$Aspect_Strength = 2;
						break;
				}
				break;
			case 180:
				/**
				 * Aspect is hardcoded to 9.0 at the moment
				 * - STRONG < 2.0
				 * - MEDIUM < 5.0
				 * - WEAK   >= 5.0
				 */
				$Aspect_Type = 2;
				switch( intval( substr($this->Current_AspectORB,1,1) ) ) {
					case 0: case 1:
						//Aspect is Strong
						$Aspect_Strength = 0;
						break;
					case 2: case 3: case 4:
						//Aspect is Medium
						$Aspect_Strength = 1;
						break;
					default:
						//Aspect is Weak");
						$Aspect_Strength = 2;
						break;
				}
				break;
			default:
				//ERROR
				$Aspect_Type = 0;
				$Aspect_Strength = 0; /* Not Applicable */
		}

		/* Manage Duplicate Sections */
		$isDuplicate = 0;
		if( $this->Current_Chapter > 0 && $this->Current_AspectReference == 200 ) {
			if( ( ( $this->Current_Planet + 1 ) == $this->Current_ChartRuler ) || ( ( $this->Current_Planet + 1 ) == $this->Current_ChartCoruler ) ) {
				$isDuplicate = 1;
			}
		}

		$NewRecord = sprintf ( $fmtstr,
				$this->Current_Chapter + 10001,
				$this->Current_Planet + 1000,
				$this->Current_AspectReference,
				$this->Current_RecordContext,
				$this->Current_RecordContextValue,
				$isRetrograde,							/* Retrograde */
				$isDuplicate,
				$Aspect_Strength,						/* Aspect Strength	{ 0 => STRONG,		1 => MEDIUM,	2 => WEAK	} */
				$Aspect_Type,							/* Aspect Type		{ 0 => CONJOINS,	1 => POSITIVE,	2 => NEGATIVE } */
				$this->Current_RecordNum++ );

		$this->Current_AnalysisContext .= $NewRecord;
	}


	/**
	 * PackRecord function will help us to setup for Report text generation
	 *
	 * There are 0 to 48 Char
	 *
	 * 12345-1234-123-12-12-1-1-123-1-1-123456789.123456-1-123-12-123
	 * 00000-0000-000-00-00-0-0-000-0-0-0000000000000000-0-000-00-000
	 *
	 * 00 -  5 - Principle planet or object
	 * 05 -  4 - Secondary planet or object
	 * 09 -  3 - Connection (aspect or in/200)
	 * 12 -  2 - Scope (sign, house, aspect)
	 * 14 -  2 - Scope context (wrt above)
	 * 16 -  1 - Retrograde
	 * 17 -  1 - Appear before (duplicate)
	 * 18 -  3 - Page number
	 * 21 -  1 - Static
	 * 22 -  1 - Include next
	 * 23 - 16 - Start/End date
	 * 39 -  1 - Repeated in future
	 * 40 -  3 - Aspect Strength
	 * 43 -  2 - Aspect Type
	 * 45 -  3 - Record Index
	 */
	function packAspectRecord() {
		/* Production Formatting */
		if($this->Current_AnalysisContextDebug === false) {
			//	 	12345-1234-123-12-12-1-1-123-1-1-123456789.123456-1-123-12-123
			$fmtstr = "%05d%04d%03d%02d%02d%d%d0001000000000000000000%03d%02d%03d";
		} else {
			/* Development Formatting */
			//			12345-1234-123-12-12-1-1-123-1-1-123456789.123456-1-123-12-123
			$fmtstr = "%05d-%04d-%03d-%02d%02d-%d-%d-0001000000000000000000-%03d-%02d-%03d\n";
		}

		$isRetrograde = $this->Current_Retrograde;

		/**
		 * TODO - this needs to be addressed once the PC3 report is stable valid set = 1..3
		 */
		if( substr($this->Current_AspectORB, 0, 1) == '+' ) {
			//Aspect is applying
		} else {
			//Aspect is Separating
		}

		/*
		 * TODO - this is still 0 (Conjunct), 1 (soft), 2 (hard). need to extract actual aspects
		* Suggest line up with Astrolog settings
		* 1 (Conjunction), 2 (Opposition), 3 (Square), 4 (Trine), 5 (Sextile)
		*/
		/**
		 *  TODO - this is still 0 (Conjunct), 1 (soft), 2 (hard). need to extract actual aspects
		 * Suggest line up with Astrolog settings
		 * 1 [ Conjunction ] , 2 [ Opposition ], 3 [ Square ], 4 [ Trine ], 5 [ Sextile ]
		 *
		 */
		switch( $this->Current_AspectReference ) {
			case 0: 						/* Conjunction */

				$Aspect_Type = 0;
				/**
				 * Aspect is hardcoded to 9.0 at the moment
				 * - STRONG < 2.0
				 * - MEDIUM < 5.0
				 * - WEAK   >= 5.0
				 */
				switch( intval( substr($this->Current_AspectORB,1,1) ) ) {
					case 0: case 1:
						//Aspect is Strong
						$Aspect_Strength = 0;
						break;
					case 2: case 3: case 4:
						//Aspect is Medium
						$Aspect_Strength = 1;
						break;
					default:
						//Aspect is Weak");
						$Aspect_Strength = 2;
						break;
				}
				break;
			case 60: /* Sextile */
				/**
				 * Aspect is hardcoded to 4.0 at the moment
				 * - STRONG < 1.0
				 * - MEDIUM < 3.0
				 * - WEAK   >= 3.0
				 */
				$Aspect_Type = 1;
				switch( intval( substr($this->Current_AspectORB,1,1) ) ) {
					case 0:
						//Aspect is Strong");
						$Aspect_Strength = 0;
						break;
					case 1: case 2:
						//Aspect is Medium");
						$Aspect_Strength = 1;
						break;
					default:
						//Aspect is Weak");
						$Aspect_Strength = 2;
						break;
				}
				break;
			case 90:
			case 120:
				/**
				 * Aspect is hardcoded to 6.0 at the moment
				 * - STRONG < 2.0
				 * - MEDIUM < 4.0
				 * - WEAK   >= 4.0
				 */
				$Aspect_Type = 2;
				switch( intval( substr($this->Current_AspectORB,1,1) ) ) {
					case 0: case 1:
						//Aspect is Strong
						$Aspect_Strength = 0;
						break;
					case 2: case 3:
						//Aspect is Medium
						$Aspect_Strength = 1;
						break;
					default:
						//Aspect is Weak
						$Aspect_Strength = 2;
						break;
				}
				break;
			case 180:
				/**
				 * Aspect is hardcoded to 9.0 at the moment
				 * - STRONG < 2.0
				 * - MEDIUM < 5.0
				 * - WEAK   >= 5.0
				 */
				$Aspect_Type = 2;
				switch( intval( substr($this->Current_AspectORB,1,1) ) ) {
					case 0: case 1:
						//Aspect is Strong
						$Aspect_Strength = 0;
						break;
					case 2: case 3: case 4:
						//Aspect is Medium
						$Aspect_Strength = 1;
						break;
					default:
						//Aspect is Weak");
						$Aspect_Strength = 2;
						break;
				}
				break;
			default:
				//ERROR
				$Aspect_Type = 0;
				$Aspect_Strength = 0; /* Not Applicable */
		}

		/* Manage Duplicate Sections */
		$isDuplicate = 0;
		if( $this->Current_Chapter > 0 && $this->Current_AspectReference == 200 ) {
			if( ( ( $this->Current_Planet + 1 ) == $this->Current_ChartRuler ) || ( ( $this->Current_Planet + 1 ) == $this->Current_ChartCoruler ) ) {
				$isDuplicate = 1;
			}
		}

		$NewRecord = sprintf ( $fmtstr,
				$this->Current_Chapter + 10001,
				$this->Current_Planet + 1000,
				$this->Current_AspectReference,
				$this->Current_RecordContext,
				$this->Current_RecordContextValue,
				$isRetrograde,							/* Retrograde */
				$isDuplicate,
				$Aspect_Strength,						/* Aspect Strength	{ 0 => STRONG,		1 => MEDIUM,	2 => WEAK	} */
				$Aspect_Type,							/* Aspect Type		{ 0 => CONJOINS,	1 => POSITIVE,	2 => NEGATIVE } */
				$this->Current_RecordNum++ );

		$this->Current_AnalysisContext .= $NewRecord;
	}

	/* Iteration functions */
	function GoToFirst() {
		/** echo "<pre>********************* GoToFirst()</pre>"; **/
		$this->Current_AnalysisContextIndex = 0;
		return $this->GetCurrent();
	}

	function GetCurrent() {
		return substr($this->Current_AnalysisContext, $this->Current_AnalysisContextIndex, 48);
	}

	function GetNext() {
		$this->Current_AnalysisContextIndex += 48;
	}

	function BOF() {
		return ( $this->Current_AnalysisContextIndex == 0 );
	}

	function EOF() {
		return ( $this->Current_AnalysisContextIndex == strlen($this->Current_AnalysisContext) );
	}

	/**
	 * Access Current Records
	 */
	function GetAnalysisContext() {
		return $this->Current_AnalysisContext;
	}

	function GetChapter() {
		return substr( $this->GetCurrent(), 0, 5 );
	}

	function GetSection() {
		return $this->GetCurrent();
	}

	function FindClosetAspect() {
		global $PlanetsArray;
		$sortedaspects = array();

		for( $aspect = 0; $aspect < count($this->bridge_aspect); $aspect++ ) {

			$OuterPlanet = substr($this->bridge_aspect[$aspect],0,4);
			$NatalPlanet = substr($this->bridge_aspect[$aspect],7,4);
				
			if(($OuterPlanet >= 1000 && $OuterPlanet <= 1009) &&
					($NatalPlanet >= 1006 && $NatalPlanet <= 1009)) {
				//($NatalPlanet != 1002 && $NatalPlanet != 1012 && $NatalPlanet != 1013)

				$Aspect = substr($this->bridge_aspect[$aspect],4,3);

				$Orb = floatval(str_replace(":", ".", substr($this->bridge_aspect[$aspect],13)));

				$OrbPoint= $this->GetPointsBasedOnOrb($Aspect, $Orb);
				reset($this->PlanetsPointsArray);
				$TPoint = $this->PlanetsPointsArray[$NatalPlanet];
				$FinalPoints = $TPoint + $OrbPoint;
				$sortedaspects[$FinalPoints] = $this->bridge_aspect[$aspect];
			}
		}
		krsort($sortedaspects, SORT_NUMERIC );

		print_r($sortedaspects);

		unset($this->bridge_aspect);
		$this->bridge_aspect = array();

		reset($sortedaspects);
		$rIndex = 0;
		while( list($key,$value) = each($sortedaspects) ) {
			array_push($this->bridge_aspect, $value);
			$rIndex++;
			if($rIndex > 0)
				break;
		}
	}

	function GetPointsBasedOnOrb($Current_AspectReference, $Current_AspectORB) {
		$Aspect_Strength = 0;
		switch( $Current_AspectReference ) {
			case 0: 						/* Conjunction */
				$Aspect_Type = 0;
				/**
				 * Aspect is hardcoded to 9.0 at the moment
				 * - STRONG < 2.0
				 * - MEDIUM < 5.0
				 * - WEAK   >= 5.0
				 */
				switch( $Current_AspectORB ) {
					case 0:
						$Aspect_Strength = 6;
						break;
					case 1:
						/** Aspect is Strong */
						$Aspect_Strength = 5;
						break;
					case 2: case 3: case 4:
						/** Aspect is Medium */
						$Aspect_Strength = 3;
						break;
					default:
						/** Aspect is Weak */
						$Aspect_Strength = 1;
						break;
				}
				break;
			case 60: /* Sextile */
				/**
				 * Aspect is hardcoded to 4.0 at the moment
				 * - STRONG < 1.0
				 * - MEDIUM < 3.0
				 * - WEAK   >= 3.0
				 */
				$Aspect_Type = 1;
				switch( $Current_AspectORB ) {
					case 0:
						/** Aspect is Strong */
						$Aspect_Strength = 5;
						break;
					case 1: case 2:
						/** Aspect is Medium */
						$Aspect_Strength = 3;
						break;
					default:
						/** Aspect is Weak */
						$Aspect_Strength = 1;
						break;
				}
				break;
			case 90:
			case 120:
				/**
				 * Aspect is hardcoded to 6.0 at the moment
				 * - STRONG < 2.0
				 * - MEDIUM < 4.0
				 * - WEAK   >= 4.0
				 */
				$Aspect_Type = 2;
				switch( $Current_AspectORB ) {
					case 0: case 1:
						/** Aspect is Strong */
						$Aspect_Strength = 5;
						break;
					case 2: case 3:
						/** Aspect is Medium	*/
						$Aspect_Strength = 3;
						break;
					default:
						/** Aspect is Weak */
						$Aspect_Strength = 1;
						break;
				}
				break;
			case 180:
				/**
				 * Aspect is hardcoded to 9.0 at the moment
				 * - STRONG < 2.0
				 * - MEDIUM < 5.0
				 * - WEAK   >= 5.0
				 */
				$Aspect_Type = 2;
				switch( $Current_AspectORB ) {
					case 0: case 1:
						/** Aspect is Strong **/
						$Aspect_Strength = 5;
						break;
					case 2: case 3: case 4:
						/** Aspect is Medium **/
						$Aspect_Strength = 3;
						break;
					default:
						/** Aspect is Weak" */
						$Aspect_Strength = 1;
						break;
				}
				break;
			default:
				$Aspect_Strength = 0; /* Not Applicable */
		}

		return $Aspect_Strength;
	}

	function FindTransitAndTotalRank($trans, $aspect, $object, $hittingDate, $TotalPoints) {
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
		$transiting_planet =  $trans;
		$transiting_aspect = $aspect;
		$natal_planet = $object;

		$DateArray = array();
		$checkCount = $transiting_planet . $transiting_aspect . $natal_planet;

		$pl_array = array('Sun', 'Moon', 'Mercury', 'Venus', 'Mars', 'Jupiter', 'Saturn', 'Uranus', 'Neptune', 'Pluto', 'NNode', 'SNode', 'Ascendant', 'Midheaven', 'IC', 'Descendant', 'Chiron');

		unset ( $start_date );
		reset ( $this->bridge_ephemeris );

		/* if the object value >= 1000 then this is a planetary transit */
		if (intval ( $object ) >= 1000) {
			$natal = floatval ( $this->bridge_object [$pl_array [$object - 1000]] ['longitude'] );
		} else {
			if (intval ( $object ) < 100) {
				/* if the object value < 100 then this is a house cusp transit */
				$natal = floatval ( $this->bridge_object ['cusp'] [intval ( $object )] );
			} else {
				/* if the object value < 1000 and >= 100 then this is a sign ingress (if conjunction) */
				if (intval ( $aspect ) == 0) {
				} else {
					/* don't want to know */
				}
			}
		}

		$transit_lo_app = ($natal - floatval ( $aspect )) - 3.0;
		if ($transit_lo_app < 0.0) {
			$transit_lo_app += 360.0;
		}
		$transit_hi_app = ($natal - floatval ( $aspect )) + 3.0;
		if ($transit_hi_app < 0.0) {
			$transit_hi_app += 360.0;
		}
		$transit_lo_sep = ($natal + floatval ( $aspect )) - 3.0;
		if ($transit_lo_sep > 360.0) {
			$transit_lo_sep -= 360.0;
		}
		$transit_hi_sep = ($natal + floatval ( $aspect )) + 3.0;
		if ($transit_hi_sep > 360.0) {
			$transit_hi_sep -= 360.0;
		}

		$start_date = '';
		$end_date = '';

		unset ( $start_date );
		reset ( $this->bridge_ephemeris );

		/* scan the ephemeris table */
		for($i = 0; $i < count ( $this->bridge_ephemeris ); $i ++) {
			$line = each ( $this->bridge_ephemeris );
			/* get the date */
			$tDate = $line [0];

			/* get the longitude of the transiting planet */
			$transit = floatval ( $line [1] [$pl_array [$trans - 1000]] ['longitude'] - 360.0 );
			/* check whether we are in a transit window */

			if (($transit >= $transit_lo_app && $transit <= $transit_hi_app) || ($transit >= $transit_lo_sep && $transit <= $transit_hi_sep)) {
				if (isset ( $start_date ) === false) {
					$start_date = $tDate;
				}
				/* keep a running track of the end date */
				$end_date = $tDate;
				$orb = $transit - $natal;
			}
		}

		if(is_array($this->bridge_transit)) {
			foreach ($this->bridge_transit as $transit) {
				$tDate = trim( substr($transit, 0, 10) );
				//									     MM = Month             DD = Day               YYYY = Year
				$tDate = date("Y-m-d", mktime ( 0, 0, 0, substr( $tDate, 8, 2), substr( $tDate, 5, 2), substr( $tDate, 0, 4) ));

				if($checkCount == substr($transit, 17, 11)) {
					if( $tDate >= $this->CurrntYear && $tDate < $this->NextYear )
					{
						$RankForMultipleOccurrence++;
					}
				}
			}
		}

		$TP_Rank = array_key_exists($transiting_planet, $TransitingPlanetRank) ? $TransitingPlanetRank[$transiting_planet] : 0;
		$NP_Rank = array_key_exists($natal_planet, $NatalPlanetRank) ? $NatalPlanetRank[$natal_planet] : 0;
		$ASP_Rank = array_key_exists($transiting_aspect, $AspectRank) ? $AspectRank[$transiting_aspect] : 0;

		$FinalPoint =  $TotalPoints + $RankForMultipleOccurrence;
		return array("totalpoint" => $FinalPoint, "object" => array("start" => $start_date, "end" => $end_date, "data" => "not available yet" ));
	}

	/**
	 * Sort the Transit and also removing duplicates aspects
	 * Array
	 * (
	 * 		[0] => 2014-17-06 09:37 10050001000=25
	 * 		[1] => 2014-22-04 08:46 10050001002=24
	 * 		[2] => 2016-25-12 08:00 10050001004=24
	 * 		[3] => 2016-27-11 04:04 10050001006=22
	 * )
	 */
	function SortPlanetTransit() {

		$sortedaspects = array();
		$linqArray = array();

		for( $transit = 0; $transit < count($this->bridge_transit); $transit++ ) {
			$transiting_planet = substr($this->bridge_transit[$transit], 17,4);
			$transiting_aspect = substr($this->bridge_transit[$transit], 21,3);
			$natal_planet = substr($this->bridge_transit[$transit],24,4);

			$tDate = trim( substr($this->bridge_transit[$transit], 0, 10) );
			//									     MM = Month             DD = Day               YYYY = Year
			$tDate = date("Y-m-d", mktime ( 0, 0, 0, substr( $tDate, 8, 2), substr( $tDate, 5, 2), substr( $tDate, 0, 4) ));

			$TotalPoints = floatval(substr($this->bridge_transit[$transit], 19,2));
			if( $tDate >= $this->CurrntYear && $tDate <= $this->NextYear )
			{
				//echo  $transit ."====". date("d F Y" ,strtotime($tDate )) ."<br />";
				if(in_array($natal_planet, $this->AllowTransitToPlanet)) {
					$TotalPoints = substr($this->bridge_transit[$transit], strlen($this->bridge_transit[$transit]) - 2, 2);
					$sortedaspects[$TotalPoints] = $this->bridge_transit[$transit];

					$obj = array( new TransitObjects($transiting_planet, $natal_planet, $transiting_aspect, $tDate, $TotalPoints, $this->bridge_transit[$transit]));
						
					$linqArray = array_merge($linqArray, $obj);					
				}
			}
		}

		$Result = from('$a')->in($linqArray)
		//->where('$a => strtotime($a->TDate) >= '. "'".strtotime($this->CurrntYear)."'" . ' && strtotime($a->TDate) <= '. "'".strtotime($this->NextYear)."'")
					->where('$a => strtotime($a->TDate) <= '. "'".strtotime($this->NextYear)."'")
					->orderByDescending('$a => $a->PlanetCode1')
					->thenByDescending('$a => $a->TDate')
					->thenByDescending('$a => $a->TotalRank')
					->take(1)
					->select('$a');

		unset($this->bridge_transit);
		$this->bridge_transit = array();

		foreach($Result as $key => $item) {
			array_push($this->bridge_transit, $item->ResultSet);
		}

		// 		krsort($sortedaspects, SORT_NUMERIC );

		// 		unset($this->bridge_transit);
		// 		$this->bridge_transit = array();

		// 		reset($sortedaspects);
		// 		$rIndex = 0;
		// 		while( list($key,$value) = each($sortedaspects) ) {
		// 			array_push($this->bridge_transit, $value);
		// 			$rIndex++;
		// 			if($rIndex > 0)
			// 				break;
			// 		}
	}
}