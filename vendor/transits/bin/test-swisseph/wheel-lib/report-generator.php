<?php
/**
 * @name: WOW Astrology
 * @package: WOW Astrology
 * @author: Amit Parmar <parmaramit1111@gmail.com>
 *
 *  @copyright WOW Inc,. <ard@world-of-wisdom.com>
 *
 *  The ReportGenerator class is use full to generat final version of the report PDF.
 */


require_once (ROOTPATH . '/bin/test-swisseph/jpgraph-2-3-3/src/jpgraph.php');
require_once (ROOTPATH . '/bin/test-swisseph/jpgraph-2-3-3/src/jpgraph_line.php');
require_once (ROOTPATH . '/bin/test-swisseph/jpgraph-2-3-3/src/jpgraph_scatter.php');
require_once (ROOTPATH . '/bin/test-swisseph/jpgraph-2-3-3/src/jpgraph_regstat.php');
require_once (ROOTPATH . '/bin/test-swisseph/jpgraph-2-3-3/src/jpgraph_date.php');
require_once (ROOTPATH . '/bin/test-swisseph/jpgraph-2-3-3/src/jpgraph_utils.inc.php');

require_once (ROOTPATH . '/bin/test-swisseph/jpgraph-2-3-3/src/jpgraph_bar.php');

class ReportGenerator extends CommonPDFHelper {

	var $ImgWidth = 550;
	var $ImgHeight = 80;
	
	var $YBookText;
	var $YBookAgeText;

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

	var $CollectivePlanet = array("1005", "1006", "1007", "1008", "1009", "1010", "1011", "1016");
	//"1005" => "Jupiter", "1006" => "Saturn", "1007" => "Uranus", "1008" => "Neptune", "1009" => "Pluto", "1010" => "N.Node", "1011" => "S.Node", "1016" => "Chiron",

	var $DoNotIncludePlanet = array("1000", "1001", "1002", "1003", "1004", "1012", "1013", "1014", "1015",								//Planet
			"0001", "0002", "0003", "0004", "0005", "0006", "0007", "0008", "0009", "0010", "0011", "0012",		//House
			"0101", "0102", "0103", "0104", "0105", "0106", "0107", "0108", "0109", "0110", "0111", "0112");	//Sign
	//"1000" => "Sun", "1001" => "Moon","1002"	=>	"Mercury", "1003"	=>	"Venus", "1004"	=>	"Mars", "1012" => "Ascendant","1013" => "Midheaven", /* "MC", */

	/** Theme selector Variables**/
	var $TopTheme;

	/** Hold the Themes for Next Year **/
	var $TopThemeForNextYear;

	/**
	 *
	 * @var Array
	 * @uses array(
	 * 			pt = 'Transiting Planet number',
	 * 			asp = 'Transiting aspect',
	 * 			pn = 'Natal Planet number',
	 * 			aspecttype = 'TR - Trasit | PR - Progression | SR - Solar Return',
	 * 			mainheading = 'Section Heading or Name (like Life path, Career etc,.)'
	 * 			content = 'Section heading content',
	 * 			'subsection' = array (same)
	 * 			)
	 */
	var $SectionHeadingContents; // Its array


	/**
	 *
	 * Hold the Solar return internal aspect with sign and house position
	 * @var Array()
	 * @uses array(
	 * 			pt = 'Aspecting Planet number',
	 * 			asp = 'Aspect',
	 * 			pn = 'Aspected Planet number',
	 * 			pts = 'Aspecting Planet Sign'
	 * 			pth = 'Aspecting Planet House'
	 * 			pns = 'Aspected Planet Sign'
	 * 			pnh = 'Aspected Planet House'
	 * 			content = 'Section heading content',
	 * 			'subsection' = array (same)
	 * 			)
	 */
	var $SRInternalAspect;

	var $CollectiveArray;

	var $SolarHouseCups;

	//Store Saturn and Juptire House crossing details
	var $SaturnJupiterCrossing;

	var $MoonPosition;

	var $GenericHelper;

	var $ProccessedArray ;

	/**
	 * Hold the list of Full Annotation
	 * @var ArrayObject
	 */
	var $AnnotationArray;


	/****/

	function ReportGenerator($YearBookText, $YearBookAgeText) {
		parent::FPDF('P', 'mm', 'A4');		
		$this->GenericHelper = new GenericHouseAndSignFinder();
		
// 		$this->ImgWidth = 550;		
// 		$this->ImgHeight = 130;

		$this->ImgWidth = 350;
		$this->ImgHeight = 100;
		
		$this->YBookText = $YearBookText;
		$this->YBookAgeText = $YearBookAgeText;

		$this->LeftMargin = 20;
		$this->TopMargin = 20;

		$this->TableX = $this->LeftMargin + 20;
		$this->TableY = $this->TopMargin + 20;

		$this->TableWidth = 170;
		$this->TableHeight = 10;
		$this->TableCellWidth = 42.5;
		$this->SetMargins($this->LeftMargin, $this->TopMargin, $this->RightMargin);

//		$this->Open();
//		$this->AliasNbPages();
//		$this->AddFont('wows', '', 'wows.php');            // text
//		$this->AddFont('ww_rv1', '', 'ww_rv1.php');        // graphics
//		$this->SetFont('wows', '', 12);
//		$this->SetDisplayMode('fullpage');
//		$this->SetAutoPageBreak(true, 20);
//		$this->AddPage();
		
		$this->ProccessedArray = array();
		$this->SaturnJupiterCrossing = array();
		$this->TopTheme = array();
		$this->TopThemeForNextYear = array();
		$this->AnnotationArray = array();

		$this->SetSolarReturnHouseRuler();
		$this->SolarReturnInternalAspects();

		$this->SetTopThemes();
		$this->ScanSections();

		global $Global_Progression_TransitSortedList;
		global $Global_NextYear;
		global $Global_CurrntYear;

		$cYear = $Global_CurrntYear;
		$newdate = strtotime ( '-6 month' , strtotime ( $cYear ) ) ;
		$newdate = date ( 'Y-m-d' , $newdate );
		$cYear = $newdate;

		$cNext = $Global_NextYear;
		$nextnewdate = strtotime ( '+6 month' , strtotime ( $cNext ) ) ;
		$nextnewdate = date ( 'Y-m-d' , $nextnewdate );
		$cNext = $nextnewdate;
		$SwapArray = array();

		foreach($Global_Progression_TransitSortedList as $Key => $Item) {
			$tDate = $Item['hitdate'];

			if( $tDate >= $cYear && $tDate <= $cNext )
			{
				array_push($SwapArray, $Item);
			}
		}
		unset($Global_Progression_TransitSortedList);
		$Global_Progression_TransitSortedList = $SwapArray;
		unset($SwapArray);				 
	}

	function SetTopThemes() {
		echo "<pre>********************************************************************** SetTopThemes()</pre>";
		global $Global_Natal_TransitSortedList;
		global $Global_N_TSLNextYear;
		global $Global_Progression_TransitSortedList;
		global $Global_NextYear;
		global $Global_CurrntYear;

// 		$cYear = $Global_CurrntYear;
// 		$newdate = strtotime ( '-6 month' , strtotime ( $cYear ) ) ;
// 		$newdate = date ( 'Y-m-d' , $newdate );
// 		$CurrntYear = $newdate;
	
// 		$cNext = $Global_NextYear;
// 		$nextnewdate = strtotime ( '+6 month' , strtotime ( $cNext ) ) ;
// 		$nextnewdate = date ( 'Y-m-d' , $nextnewdate );
// 		$NextYear = $nextnewdate;

		//We dont need Current year progression that
		unset($cYear);
		unset($cNext);
		unset($NextYear);
		unset($CurrntYear);

		$cYear = $Global_CurrntYear;
		$newdate = date ( 'Y-m-d' , strtotime( $cYear ));
		$CurrntYear = $newdate;

		$cNext = $Global_NextYear;
		$nextnewdate = date ( 'Y-m-d' , strtotime( $cNext ) );
		$NextYear = $nextnewdate;
		
		$TopCount = 0;
		if( count($Global_Natal_TransitSortedList) > 0) {
			foreach ($Global_Natal_TransitSortedList as $values) {
				$tDate = $values['hitdate'];

				if( $tDate >= $Global_CurrntYear && $tDate <= $Global_NextYear )
				{
					array_push($this->TopTheme, $values);					
					$TopCount++;					
				}
				if($TopCount == 2)
					break;
			}
		}

		if(count($Global_Progression_TransitSortedList) > 0) {
			$TopCount = 0;			
			foreach($Global_Progression_TransitSortedList as $values) {
				$tDate = $values['hitdate'];
				$PT = $values['pt'];				
				if($PT != '1013' && $PT != '1014') {
					if( $tDate >= $CurrntYear && $tDate <= $NextYear && $values['pt'] != '1001' )
					{							
						array_push($this->TopTheme, $values);
						$TopCount++;						
					}
				}
				if($TopCount == 1)
					break;
			}
		}
		//else {
		if($TopCount <= 0) {
			unset($this->TopTheme);
			$this->TopTheme = array();
			$TopCount = 0;
			foreach ($Global_Natal_TransitSortedList as $values) {
				$tDate = $values['hitdate'];

				if( $tDate >= $Global_CurrntYear && $tDate <= $Global_NextYear )
				{
					array_push($this->TopTheme, $values);
					$TopCount++;
				}
				if($TopCount == 3)
					break;
			}
		}

		/** SETTING THEMES FOR NEXT YEAR **/
		$cNext = $Global_NextYear;
		$nextnewdate = strtotime ( '+1 year' , strtotime ( $cNext ) ) ;
		$nextnewdate = date ( 'Y-m-d' , $nextnewdate );
		$NextToNextYear = $nextnewdate;

		$TopCount = 0;
		if( count($Global_N_TSLNextYear) > 0) {
			foreach ($Global_N_TSLNextYear as $values) {
				$tDate = $values['hitdate'];
				if( $tDate >= $Global_NextYear )
				{
					array_push($this->TopThemeForNextYear, $values);
					$TopCount++;
				}
				if($TopCount == 2)
					break;
			}
		}
		//$nextnewdate = strtotime ( '+6 month' , strtotime ( $cNext ) ) ;
		$nextnewdate = strtotime ( '+1 year' , strtotime ( $cNext ) ) ;
		$nextnewdate = date ( 'Y-m-d' , $nextnewdate );
		$NextToNextYear = $nextnewdate;

		if(count($Global_Progression_TransitSortedList) > 0) {
			$TopCount = 0;
			foreach($Global_Progression_TransitSortedList as $values) {
				$tDate = $values['hitdate'];
				$PT = $values['pt'];
				$PN = $values['pn'];
				
				if($PT != '1001') {
					if($PT != '1015' && $PT != '1014' && $PN != '1015' && $PN != '1014' ) {
						if( $tDate >= $Global_NextYear && $tDate <= $NextToNextYear )
						{
							array_push($this->TopThemeForNextYear, $values);
							$TopCount++;
						}
					}
				}

				if($TopCount == 1)
					break;
			}
		}

		if($TopCount <= 0) {
			unset($this->TopThemeForNextYear);
			$this->TopThemeForNextYear = array();
			$TopCount = 0;
			foreach ($Global_N_TSLNextYear as $values) {
				$tDate = $values['hitdate'];

				if( $tDate >= $Global_NextYear && $tDate <= $NextToNextYear )
				{
					array_push($this->TopThemeForNextYear, $values);
					$TopCount++;
				}
				if($TopCount == 3)
					break;
			}
		}
	}

	protected function SetCollectiveSection() {
		echo "<pre>********************************************************************** SetCollectiveSection()</pre>";
		global $Global_Natal_TransitSortedList;
		global $Global_CurrntYear;
		global $Global_NextYear;
		global $CollectivePlanetRank;
		$this->CollectiveArray = array();

		$IsSectionHeading = false;

		foreach($Global_Natal_TransitSortedList as $Key => $Item) {
			$PT = $Item['pt'];
			$ASP = $Item['asp'];
			$PN = $Item['pn'];
			$AspectType = $Item['aspecttype'];
			$IsPlanet = $Item['isplanet'];
			$DT = trim( $Item['hitdate'] );
			$Rank = trim( $Item['totalrank'] );

			$InnerSectionContent = array();

			if($DT >= $Global_CurrntYear && $DT <= $Global_NextYear ) {
				if(in_array ($PT , $this->CollectivePlanet)) {
					$start = trim( $Item['start'] );
					$end = trim( $Item['end'] );
					$hitdate =trim( $Item['hitdate'] );

					if( !in_array ( $PN , $this->DoNotIncludePlanet ) ) {
						$planetrank = $CollectivePlanetRank[$Item['pt']];
						$planetrank1 = $CollectivePlanetRank[$Item['pn']];
						$aspectrank = trim( $Item['aspectrank'] );

						$totalrank = $planetrank + $planetrank1 + $aspectrank;
							
						array_push($this->CollectiveArray, array("pt" => $PT,
															"asp" => $ASP,
															"pn" => $PN,
															"planetrank" => $planetrank, 	//Planet rank to set Priority,
															"aspectrank" => $aspectrank, 	//Aspect rank to set Priority,
															"start" => $start, 				//$start_date,
															"end" => $end, 					//$end_date,
															"hitdate" => $DT,
															'isplanet' => $IsPlanet,
															'aspecttype' => $AspectType,
															'totalrank' => $totalrank,
															"xaxis" => $Item['xaxis'],
															"yaxis" => $Item['yaxis'],
															"maxorb" => $Item['maxorb'],
															"minorb" => $Item['minorb'],
															"test" => $Item['test']));
						//array_push($this->CollectiveArray, $Item);
					}
				}
			}
		}

		$this->CollectiveArray = $this->SortCollectiveSection($this->CollectiveArray);

		if(count($this->CollectiveArray) > 0) {

			global $Global_Natal_TransitSortedList;
			global $SectionHeadings;
			global $Global_CurrntYear;
			global $Global_NextYear;
			global $NameOfPlanets;

			$IsSectionHeading = false;

			foreach($this->CollectiveArray as $Key => $Item) {
				$PT = $Item['pt'];
				$ASP = $Item['asp'];
				$PN = $Item['pn'];
				$AspectType = $Item['aspecttype'];
				$IsPlanet = $Item['isplanet'];
				$DT = trim( $Item['hitdate'] );
				$Rank = trim( $Item['totalrank'] );
					
				$aspectrank = trim( $Item['aspectrank'] );
				$planetrank = $CollectivePlanetRank[$Item['pt']];
				$planetrank1 = $CollectivePlanetRank[$Item['pn']];
					
				$InnerSectionContent = array();

				if($DT >= $Global_CurrntYear && $DT <= $Global_NextYear) {

					$IItem = array();

					$IItem = $this->GetSectionHeadline($PT, $PN);

					$IsSectionHeading = $IItem['IsSectionHeading'];
					$Content = $IItem['IItem'];

					if($IsSectionHeading == true) {
						unset($IItem);
						unset($InnerSectionContent);

						$InnerSectionContent = array();
						$InnerSectionContent = $this->GetMajorTransitForCollectiveSection($InnerSectionContent, $this->CollectiveArray);

						if(count($InnerSectionContent) < 2) {
							$InnerSectionContent = $this->GetMajorProgressionForCollectiveSection($PT, $InnerSectionContent);
						}
							
						if(count($InnerSectionContent) < 2) {
							$InnerSectionContent = $this->GetMajorSolarReturnForCollectiveSection($InnerSectionContent);
						}

						$SRToNatalTop = array();
						$SRToNatalTop = $this->GetMajorSolarReturnForCollectiveSection($SRToNatalTop);

						$InternalSRToSR = array();
						$InternalSRToSR = $this->GetMajorInternalAspectSRCollectiveSection($InternalSRToSR);

						$PRIngrass = array();
						$PRIngrass = $this->GetPlanetIngrassCollectiveSection($PT, $PRIngrass);

						array_push($this->SectionHeadingContents, array ('pt' => $PT,
																		'asp' => $ASP,
																		'pn' => $PN,
																		'isplanet' => $IsPlanet,
																		'hitdate' => $DT,
																		'aspecttype' => $AspectType,
																		'totalrank' => $Rank,
																		'hitcounter' => $Item['test']['hitcounter'],
																		'mainheading' => 'Collective trends',
																		'content' => $Content,
																		'subsection' => $InnerSectionContent,
																		'srtonatal' => $SRToNatalTop,
																		'srtosr' => $InternalSRToSR,
																		'pringrass' => $PRIngrass,
																		"xaxis" => $Item['xaxis'],
																		"yaxis" => $Item['yaxis'],
																		"maxorb" => $Item['maxorb'],
																		"minorb" => $Item['minorb'],));
						break;
					}
				}
			}
		}
	}

	protected function SetSAandJUHouseCrossing() {
		echo "<pre>********************************************************************** SetSAandJUHouseCrossing()</pre>";
		global $Global_SaturnJupiterCrossing;
		global $Global_CurrntYear;
		global $Global_NextYear;
		global $Global_PreviousYear;

		foreach($Global_SaturnJupiterCrossing as $Key => $Item) {
			$PT = $Item['pt'];
			$ASP = $Item['asp'];
			$PN = $Item['pn'];
			$AspectType = $Item['aspecttype'];
			$IsPlanet = $Item['isplanet'];
			$IsRetrograde = $Item['isretrograde'];

			$DT = $Item['start'];
			$start = trim( $Item['start'] );
			$end = trim( $Item['end'] );
			$IsStayInHouse = trim( $Item['isstayinhouse']);

			//Keep tracking for House crossing for Saturn and Jupiter
			if($IsRetrograde == 'D') {
				array_push($this->SaturnJupiterCrossing, array("pt" => $PT,
											"asp" => $ASP,
											"pn" => $PN,
											"start" => $start, 				//$start_date,
											"end" => $end, 					//$end_date,
											"isretrograde" => $IsRetrograde,
											"isplanet" => $IsPlanet,
											"isstayinhouse" => $IsStayInHouse));
			}
		}
	}

	/**
	 * ScanSections() function scan the each section and create the Headline for each section
	 */
	protected function ScanSections() {
		echo "<pre>********************************************************************** ScanSections()</pre>";
		global $chapterHeadings;
		global $Global_Language;
		global $SectionRelatedPlanets;
		global $Global_Natal_TransitSortedList;

		$this->SectionHeadingContents = array();

		foreach ($chapterHeadings[$Global_Language] as $key => $values) {
			if(!is_array($SectionRelatedPlanets[$key])) {
				$this->GenerateHeadlinesForTransit($SectionRelatedPlanets[$key], $values);
			}
		}
		$this->SetCollectiveSection();
		$this->SetSAandJUHouseCrossing();
	}

	/**
	 * GenerateHeadlinesForTransit() function set the appropriate headline based on specified argument
	 * @param string $TransitingPlanet - Transiting Planet number (4 charctor string [1000,1001,1002 etc])
	 * @param string $SectionName - Section heading string
	 */
	protected function GenerateHeadlinesForTransit($TransitingPlanet, $SectionName) {
		global $Global_Natal_TransitSortedList;
		global $SectionHeadings;
		global $Global_CurrntYear;
		global $Global_NextYear;
		global $NameOfPlanets;

		$IsSectionHeading = false;

		foreach($Global_Natal_TransitSortedList as $Key => $Item) {
			$PT = $Item['pt'];
			$ASP = $Item['asp'];
			$PN = $Item['pn'];
			$AspectType = $Item['aspecttype'];
			$IsPlanet = $Item['isplanet'];
			$DT = trim( $Item['hitdate'] );
			$Rank = trim( $Item['totalrank'] );

			$InnerSectionContent = array();

			if($DT >= $Global_CurrntYear && $DT <= $Global_NextYear ) {
				$IItem = array();

				if($PT == $TransitingPlanet && $IsPlanet == 1 && $PT != $PN) {
					$IItem = $this->GetSectionHeadline($TransitingPlanet, $PN);

					$IsSectionHeading = $IItem['IsSectionHeading'];
					$Content = $IItem['IItem'];
				}
				else if($PN == $TransitingPlanet && $IsPlanet == 1) {

					$IItem = $this->GetSectionHeadline($TransitingPlanet, $PT);

					$IsSectionHeading = $IItem['IsSectionHeading'];
					$Content = $IItem['IItem'];

					$PT = $Item['pn'];
					$PN = $Item['pt'];
				}
				else {
					$IsSectionHeading = false;
				}

				if($IsSectionHeading == true) {
					unset($IItem);
					unset($InnerSectionContent);

					$InnerSectionContent = array();
					$InnerSectionContent = $this->GetMajorTransitForSection($TransitingPlanet, $InnerSectionContent);

					// 					if(count($InnerSectionContent) < 2) {
					$InnerSectionContent =  $this->GetMajorProgressionForSection($TransitingPlanet, $InnerSectionContent);
					// 					}

					$MoonPosition = array();
					//					if(count($InnerSectionContent) < 2) {
					if($TransitingPlanet == '1001'){
						$InnerSectionContent = $this->GetMoonProgressedPosition($TransitingPlanet, $InnerSectionContent);
					}
					else {
						$InnerSectionContent = $this->GetSolarReturnSignPosition($TransitingPlanet, $InnerSectionContent);
						//$InnerSectionContent = $this->GetMajorSolarReturnForSection($TransitingPlanet, $InnerSectionContent);
					}
					//					}

					if(($PT == '1012' || $PT == '1013') && (count($InnerSectionContent) < 2)) {
						$InnerSectionContent = $this->GenerateTransitForACandMC($TransitingPlanet, $InnerSectionContent);

					}

					$SRToNatalTop = array();
					$SRToNatalTop = $this->GetMajorSolarReturnForSection($TransitingPlanet, $SRToNatalTop);

					$InternalSRToSR = array();
					$InternalSRToSR = $this->GetMajorInternalAspectSR($TransitingPlanet, $InternalSRToSR);

					$PRIngrass = array();
					$PRIngrass = $this->GetPlanetIngrass($TransitingPlanet, $PRIngrass);

					array_push($this->SectionHeadingContents, array('pt' => $PT,
					'asp' => $ASP,
					'pn' => $PN,
					'isplanet' => $IsPlanet,
					'hitdate' => $DT,
					'aspecttype' => $AspectType,
					'totalrank' => $Rank,
					'hitcounter' => $Item['test']['hitcounter'],
					'mainheading' => $SectionName,
					'content' => $Content,
					'subsection' => $InnerSectionContent,
					'srtonatal' => $SRToNatalTop,
					'srtosr' => $InternalSRToSR,
					'pringrass' => $PRIngrass,
					'moonposition' => $MoonPosition));
					break;
				}
			}
		}

		if($IsSectionHeading == false) {
			$this->GenerateHeadlinesForProgression($TransitingPlanet, $SectionName);
		}
	}

	protected function GetSectionHeadline($TransitingPlanet, $AspectingPlanet) {
		global $SectionHeadings;

		$ItemArray = array('IItem' => '', 'IsSectionHeading' => false);

		reset($SectionHeadings);
		foreach ($SectionHeadings as $InnerKey => $InnerItem) {
			if($InnerKey == $TransitingPlanet) {
				foreach($InnerItem as $K => $IItem) {
					if($K == $AspectingPlanet) {
						$ItemArray["IItem"] = $IItem;
						$ItemArray["IsSectionHeading"] = true;
						break;
					}
				}
			}
		}
		return $ItemArray;
	}

	/**
	 * GenerateHeadlinesForProgression() function set the appropriate headline based on specified argument
	 * @param string $TransitingPlanet - Transiting Planet number (4 charctor string [1000,1001,1002 etc])
	 * @param string $SectionName - Section heading string
	 */
	protected function GenerateHeadlinesForProgression($TransitingPlanet, $SectionName) {
		global $Global_Progression_TransitSortedList;
		global $SectionHeadings;
		global $Global_CurrntYear;
		global $Global_NextYear;

		$cYear = $Global_CurrntYear;
		$newdate = strtotime ( '-6 month' , strtotime ( $cYear ) ) ;
		$newdate = date ( 'Y-m-d' , $newdate );
		$CurrntYear = $newdate;
			
		$cNext = $Global_NextYear;
		$nextnewdate = strtotime ( '+6 month' , strtotime ( $cNext ) ) ;
		$nextnewdate = date ( 'Y-m-d' , $nextnewdate );
		$NextYear = $nextnewdate;

		$IsSectionHeading = false;

		if($TransitingPlanet != '1001') {
			foreach($Global_Progression_TransitSortedList as $Key => $Item) {
				$PT = $Item['pt'];
				$ASP = $Item['asp'];
				$PN = $Item['pn'];
				$AspectType = $Item['aspecttype'];
				$IsPlanet = $Item['isplanet'];
				$DT = trim( $Item['hitdate'] );
				$Rank = trim( $Item['totalrank'] );

				if($DT >= $CurrntYear && $DT <= $NextYear ) {

					if($PT == $TransitingPlanet && $IsPlanet == 1 && $PT != $PN) {
						reset($SectionHeadings);
						foreach ($SectionHeadings as $InnerKey => $InnerItem) {
							foreach($InnerItem as $K => $IItem) {
								if($K == $PN) {
									$InnerSectionContent = array();

									$InnerSectionContent = $this->GetMajorTransitForSection($TransitingPlanet, $InnerSectionContent);

									//								if(count($InnerSectionContent) < 2) {
									$InnerSectionContent = $this->GetMajorProgressionForSection($TransitingPlanet, $InnerSectionContent);
									//								}

									//								if(count($InnerSectionContent) < 2) {
									if($TransitingPlanet == '1001') {
										$InnerSectionContent = $this->GetMoonProgressedPosition($TransitingPlanet, $InnerSectionContent);
									}
									else {
										$InnerSectionContent = $this->GetSolarReturnSignPosition($TransitingPlanet, $InnerSectionContent);
										//$InnerSectionContent = $this->GetMajorSolarReturnForSection($TransitingPlanet, $InnerSectionContent);
									}
									//								}

									if(($PT == '1012' || $PT == '1013') && (count($InnerSectionContent) < 2)) {
										$InnerSectionContent = $this->GenerateTransitForACandMC($TransitingPlanet, $InnerSectionContent);
									}

									$SRToNatalTop = array();
									$SRToNatalTop = $this->GetMajorSolarReturnForSection($TransitingPlanet, $SRToNatalTop);

									$InternalSRToSR = array();
									$InternalSRToSR = $this->GetMajorInternalAspectSR($TransitingPlanet, $InternalSRToSR);

									$PRIngrass = array();
									$PRIngrass = $this->GetPlanetIngrass($TransitingPlanet, $PRIngrass);

									$MoonPosition = array();
									//if($TransitingPlanet == '1001'){
									//	$MoonPosition = $this->GetMoonProgressedPosition($TransitingPlanet);
									//}

									array_push($this->SectionHeadingContents, array('pt' => $PT,
									'asp' => $ASP,
									'pn' => $PN,
									'isplanet' => $IsPlanet,
									'hitdate' => $DT,
									'aspecttype' => $AspectType,
									'totalrank' => $Rank,
									'hitcounter' => array(),
									'mainheading' => $SectionName,
									'content' => $IItem,
									'subsection' => $InnerSectionContent,
									'srtonatal' => $SRToNatalTop,
									'srtosr' => $InternalSRToSR,
									'pringrass' => $PRIngrass,
									'moonposition' => $MoonPosition));

									unset($InnerSectionContent);

									$IsSectionHeading = true;
									break;
								}
							}
							if($IsSectionHeading == true) {
								break;
							}
						}
						if($IsSectionHeading == true)
							break;
					}
				}
			}
		}

		if($IsSectionHeading == false) {
			$this->GenerateHeadlinesForSolarReturn($TransitingPlanet, $SectionName);
		}
	}


	protected function GenerateTransitForACandMC($TransitingPlanet, $InnerSectionContent) {
		global $Global_Solar_TransitSortedList;
		global $SectionHeadings;

		$IsSectionHeading = false;
		$TPlanet = '';
		if ( $TransitingPlanet == '1012' ) {
			$TPlanet = $this->SolarHouseCups[0];
		}
		else if ( $TransitingPlanet == '1013' ) {
			$TPlanet = $this->SolarHouseCups[9];
		}
		else {
			$TPlanet = $this->SolarHouseCups[$TransitingPlanet];
			//$TPlanet = $TransitingPlanet;
		}

		$PT = $TransitingPlanet;
		$ASP = "-1";
		$PN = $TPlanet;
		$AspectType = 'SRRULER';
		$IsPlanet = 1;
		$InnerContent = '';
		$InnerSectionContent = $this->GetMajorTransitForSection($TPlanet, $InnerSectionContent);

		return $InnerSectionContent;
	}

	/**
	 * GenerateHeadlinesForSolarReturn() function set the appropriate headline based on specified argument
	 * @param string $TransitingPlanet - Transiting Planet number (4 charctor string [1000,1001,1002 etc])
	 * @param string $SectionName - Section heading string
	 */
	protected function GenerateHeadlinesForSolarReturn($TransitingPlanet, $SectionName) {
		global $Global_Solar_TransitSortedList;
		global $SectionHeadings;
		global $AbbrPlanetToFullName;
				
		$IsSectionHeading = false;
		$TPlanet = '';
		if ( $TransitingPlanet == '1012' ) {
			$TPlanet = $this->SolarHouseCups[0];
		}
		else if ( $TransitingPlanet == '1013' ) {
			$TPlanet = $this->SolarHouseCups[9];
		}
		else {
			//$TPlanet = $this->SolarHouseCups[$TransitingPlanet];
			$gHelper =  new GenericHouseAndSignFinder();
			$signNo = array("" => 0, "AR" => 1, "TA" => 2, "GE" => 3, "CN" => 4, "LE" => 5, "VI" => 6, "LI" => 7, "SC" => 8, "SG" => 9, "CP" => 10, "AQ" => 11, "PI" => 12);
			$CheckForME = $AbbrPlanetToFullName[$TransitingPlanet];
			$SignHouse = $gHelper->GetSolarReturnSignAndHousePosition($CheckForME);			
			$SignAbbr = $signNo[$SignHouse['sign']];			
			$TPlanet = $this->SolarHouseCups[$SignAbbr];
			//$TPlanet = $TransitingPlanet;
		}

		$PT = $TransitingPlanet;
		$ASP = "-1";
		$PN = $TPlanet;
		$AspectType = 'SR';
		$IsPlanet = 1;
		$InnerContent = '';
		$InnerSectionContent = array();
		$InnerSectionContent = $this->GetMajorTransitForSection($TransitingPlanet, $InnerSectionContent);

		//		if(count($InnerSectionContent) < 2) {
		$InnerSectionContent = $this->GetMajorProgressionForSection($TransitingPlanet, $InnerSectionContent);
		//		}

		//		if(count($InnerSectionContent) < 2) {
		if($TransitingPlanet == '1001') {
			$InnerSectionContent = $this->GetMoonProgressedPosition($TransitingPlanet, $InnerSectionContent);
		}
		else {
			$InnerSectionContent = $this->GetSolarReturnSignPosition($TransitingPlanet, $InnerSectionContent);
			//$InnerSectionContent = $this->GetMajorSolarReturnForSection($TransitingPlanet, $InnerSectionContent);
		}
		//		}

		if(($PT == '1012' || $PT == '1013') && (count($InnerSectionContent) < 2)){
			$InnerSectionContent = $this->GenerateTransitForACandMC($TransitingPlanet, $InnerSectionContent);
		}

		reset($SectionHeadings);
		foreach ($SectionHeadings as $InnerKey => $InnerItem) {
			if($InnerKey == $TransitingPlanet) {
				foreach($InnerItem as $K => $IItem) {
					if($K == $PN) {
						$InnerContent = $IItem;
						break;
					}
				}
			}
		}

		$SRToNatalTop = array();
		$SRToNatalTop = $this->GetMajorSolarReturnForSection($TransitingPlanet, $SRToNatalTop);

		$InternalSRToSR = array();
		$InternalSRToSR = $this->GetMajorInternalAspectSR($TransitingPlanet, $InternalSRToSR);

		$PRIngrass = array();
		$PRIngrass = $this->GetPlanetIngrass($TransitingPlanet, $PRIngrass);
			
		$MoonPosition = array();
		//		if($TransitingPlanet == '1001'){
		//			$MoonPosition = $this->GetMoonProgressedPosition($TransitingPlanet);
		//		}

		array_push($this->SectionHeadingContents, array ('pt' => $PT,
													'asp' => $ASP,
													'pn' => $PN,
													'isplanet' => $IsPlanet,
													'hitdate' => '',
													'aspecttype' => $AspectType,
													'totalrank' => '0',
													'hitcounter' => array(),
													'mainheading' => $SectionName,
													'content' => $InnerContent,
													'subsection' => $InnerSectionContent,
													'srtonatal' => $SRToNatalTop,
													'srtosr' => $InternalSRToSR,
													'pringrass' => $PRIngrass,
													'moonposition' => $MoonPosition));
	}

	/**
	 * GetMajorTransitForSection() function fetch the major 2 influences of the specified sections
	 * @param string $TransitPlanet
	 */
	protected function GetMajorTransitForSection($TransitPlanet , $InnerSectionContent, $SetAspectType = '', $IsCollective = false) {
		global $Global_Natal_TransitSortedList;
		global $SectionHeadings;
		global $Global_CurrntYear;
		global $Global_NextYear;
		global $NameOfPlanets;

		$DoNotIncludePlanet = array("1000", "1001", "1002", "1003", "1004", "1012", "1013" );
		//"1000" => "Sun", "1001" => "Moon", "1012" => "Ascendant","1013" => "Midheaven", /* "MC", */
		//"1002"	=>	"Mercury", "1003"	=>	"Venus", "1004"	=>	"Mars",

		$SectionTransit = array();
		$SectionTransit = $InnerSectionContent;
		$SectionCount = 0;

		foreach($Global_Natal_TransitSortedList as $Key => $Item) {
			$PT = $Item['pt'];
			$ASP = $Item['asp'];
			$PN = $Item['pn'];
			$AspectType = $Item['aspecttype'];
			$IsPlanet = $Item['isplanet'];
			$DT = trim( $Item['hitdate'] );
			$Rank = trim( $Item['totalrank'] );

			if($SetAspectType != '') {
				$AspectType = $SetAspectType;
			}

			if( $DT >= $Global_CurrntYear && $DT <= $Global_NextYear ) {
				if($PT == $TransitPlanet) {
					if($IsCollective == true) {
						if( !in_array($PN, $DoNotIncludePlanet) ) {
							$SectionTransit = $this->AddTransitToSummarySection($SectionTransit, $Item, $SetAspectType);
							$SectionCount++;
						}
					}
					else {
						$SectionTransit = $this->AddTransitToSummarySection($SectionTransit, $Item, $SetAspectType);
						$SectionCount++;
					}
				}
				else if($PN == $TransitPlanet) {
					$PT = $Item['pn'];
					$PN = $Item['pt'];

					if($IsCollective) {
						if( !in_array($PN, $DoNotIncludePlanet)) {
							$SectionTransit = $this->AddTransitToSummarySection($SectionTransit, $Item, $SetAspectType, true);
							$SectionCount++;
						}
					}
					else {
						$SectionTransit = $this->AddTransitToSummarySection($SectionTransit, $Item, $SetAspectType, true);
						$SectionCount++;
					}
				}
				//else {
					if($TransitPlanet == '1001' && $PN == '1014' && $ASP == '000') {
						$PT = $Item['pn'];
						$PN = $Item['pt'];

						if($IsCollective) {
							if( !in_array($PN, $DoNotIncludePlanet)) {
								$SectionTransit = $this->AddTransitToSummarySection($SectionTransit, $Item, $SetAspectType, true);
								$SectionCount++;
							}
						}
						else {
							$SectionTransit = $this->AddTransitToSummarySection($SectionTransit, $Item, $SetAspectType, true);
							$SectionCount++;
						}
					}
					if($TransitPlanet == '1003' && $PN == '1015' && $ASP == '000') {
						$PT = $Item['pn'];
						$PN = $Item['pt'];
							
						if($IsCollective) {
							if( !in_array($PN, $DoNotIncludePlanet)) {
								$SectionTransit = $this->AddTransitToSummarySection($SectionTransit, $Item, $SetAspectType, true);
								$SectionCount++;
							}
						}
						else {
							$SectionTransit = $this->AddTransitToSummarySection($SectionTransit, $Item, $SetAspectType, true);
							$SectionCount++;
						}
					}
				//}
			}
			if($SectionCount > 2)
				break;
			// 			if(count($SectionTransit) >= 2)
			// 				break;
		}
		return $SectionTransit;
	}

	function AddTransitToSummarySection($SectionTransit, $Item, $SetAspectType, $IsSwap = false){
		$PT = $Item['pt'];
		$ASP = $Item['asp'];
		$PN = $Item['pn'];
		$AspectType = $Item['aspecttype'];
		$IsPlanet = $Item['isplanet'];
		$DT = trim( $Item['hitdate'] );
		$Rank = trim( $Item['totalrank'] );

		if($SetAspectType != '') {
			$AspectType = $SetAspectType;
		}

		if($IsSwap) {
			$PT = $Item['pn'];
			$PN = $Item['pt'];
		}

		array_push($SectionTransit, array('pt' => $PT,
										'asp' => $ASP,
										'pn' => $PN,
										'isplanet' => $IsPlanet,
										'hitdate' => $DT,
										'aspecttype' => $AspectType,
										'totalrank' => $Rank,
										"xaxis" => $Item['xaxis'],
										"yaxis" => $Item['yaxis'],
										"maxorb" => $Item['maxorb'],
										"minorb" => $Item['minorb'],
										'hitcounter' => $Item['test']['hitcounter'],
										'mainheading' => 'This contect will come from Database',
										'content' => 'This contect will come from Database'));
		return $SectionTransit;
	}

	/**
	 * GetMajorTransitForCollectiveSection() function fetch the major 2 influences of the specified sections
	 * @param string $TransitPlanet
	 */
	protected function GetMajorTransitForCollectiveSection($InnerSectionContent, $CollectiveArray) {
		//echo '<pre>************** GetMajorTransitForCollectiveSection()</pre>';
		global $Global_Natal_TransitSortedList;
		global $SectionHeadings;
		global $Global_CurrntYear;
		global $Global_NextYear;
		global $NameOfPlanets;

		$SectionTransit = array();
		$SectionTransit = $InnerSectionContent;
		$SectionCount = 0;

		foreach($CollectiveArray as $Key => $Item) {
			$PT = $Item['pt'];
			$ASP = $Item['asp'];
			$PN = $Item['pn'];
			$AspectType = $Item['aspecttype'];
			$IsPlanet = $Item['isplanet'];
			$DT = trim( $Item['hitdate'] );
			$Rank = trim( $Item['totalrank'] );

			if( $DT >= $Global_CurrntYear && $DT <= $Global_NextYear ) {
				if( !in_array($PN, $this->DoNotIncludePlanet) ) {
					array_push($SectionTransit, array(
												'pt' => $PT,
												'asp' => $ASP,
												'pn' => $PN,
												'isplanet' => $IsPlanet,
												'hitdate' => $DT,
												'aspecttype' => $AspectType,
												'totalrank' => $Rank,
												"xaxis" => $Item['xaxis'],
												"yaxis" => $Item['yaxis'],
												"maxorb" => $Item['maxorb'],
												"minorb" => $Item['minorb'],
												'hitcounter' => $Item['test']['hitcounter'],
												'mainheading' => 'This contect will come from Database',
												'content' => 'This contect will come from Database')
												);
					$SectionCount++;
				}
			}
			if($SectionCount > 1)
				break;
// 			if(count($SectionTransit) >= 2)
// 				break;
		}
		return $SectionTransit;
	}

	/**
	 * GetMajorProgressionForCollectiveSection() function fetch the major 2 influences of the specified sections
	 * @param string $ProgressedPlanet
	 */
	protected function GetMajorProgressionForCollectiveSection($ProgressedPlanet, $InnerSectionContent) {
		global $Global_Progression_TransitSortedList;
		global $SectionHeadings;
		global $Global_CurrntYear;
		global $Global_NextYear;

		$cYear = $Global_CurrntYear;
		$newdate = strtotime ( '-6 month' , strtotime ( $cYear ) ) ;
		$newdate = date ( 'Y-m-d' , $newdate );
		$CurrntYear = $newdate;
			
		$cNext = $Global_NextYear;
		$nextnewdate = strtotime ( '+6 month' , strtotime ( $cNext ) ) ;
		$nextnewdate = date ( 'Y-m-d' , $nextnewdate );
		$NextYear = $nextnewdate;
			
		$SectionTransit = array();
		$SectionTransit = $InnerSectionContent;
		$SectionCount = 0;

		foreach($Global_Progression_TransitSortedList as $Key => $Item) {
			$PT = $Item['pt'];
			$ASP = $Item['asp'];
			$PN = $Item['pn'];
			$AspectType = $Item['aspecttype'];
			$IsPlanet = $Item['isplanet'];
			$DT = trim( $Item['hitdate'] );
			$Rank = trim( $Item['totalrank'] );

			if($DT >= $CurrntYear && $DT <= $NextYear ) {
				if( in_array($PT, $this->CollectivePlanet) ) {
					if( !in_array($PN, $this->DoNotIncludePlanet) ) {
						array_push($SectionTransit, array(
						'pt' => $PT,
						'asp' => $ASP,
						'pn' => $PN,
						'isplanet' => $IsPlanet,
						'hitdate' => $DT,
						'aspecttype' => $AspectType,
						'totalrank' => $Rank,
						'hitcounter' => array(),
						'mainheading' => 'This contect will come from Database',
						'content' => 'This contect will come from Database')
						);
						$SectionCount++;
					}
				}
			}
			if($SectionCount > 1)
				break;
			// 			if(count($InnerSectionContent) >= 2)
			// 				break;
		}

		return $SectionTransit;
	}

	/**
	 * GetMajorProgressionForSection() function fetch the major 2 influences of the specified sections
	 * @param string $ProgressedPlanet
	 */
	protected function GetMajorProgressionForSection($ProgressedPlanet, $InnerSectionContent) {
		global $Global_Progression_TransitSortedList;
		global $SectionHeadings;
		global $Global_CurrntYear;
		global $Global_NextYear;

		$cYear = $Global_CurrntYear;
		$newdate = strtotime ( '-6 month' , strtotime ( $cYear ) ) ;
		$newdate = date ( 'Y-m-d' , $newdate );
		$CurrntYear = $newdate;
			
		$cNext = $Global_NextYear;
		$nextnewdate = strtotime ( '+6 month' , strtotime ( $cNext ) ) ;
		$nextnewdate = date ( 'Y-m-d' , $nextnewdate );
		$NextYear = $nextnewdate;
			
		$SectionTransit = array();
		$SectionTransit = $InnerSectionContent;
		$SectionCount = 0;

		foreach($Global_Progression_TransitSortedList as $Key => $Item) {
			$PT = $Item['pt'];
			$ASP = $Item['asp'];
			$PN = $Item['pn'];
			$AspectType = $Item['aspecttype'];
			$IsPlanet = $Item['isplanet'];
			$DT = trim( $Item['hitdate'] );
			$Rank = trim( $Item['totalrank'] );

			if($IsPlanet == 1 && ($DT >= $CurrntYear && $DT <= $NextYear)) {
				if($PT == $ProgressedPlanet && $PT != $PN) {
					array_push($SectionTransit, array('pt' => $PT,
													'asp' => $ASP,
													'pn' => $PN,
													'isplanet' => $IsPlanet,
													'hitdate' => $DT,
													'aspecttype' => $AspectType,
													'totalrank' => $Rank,
													'hitcounter' => array(),
													'mainheading' => 'This contect will come from Database',
													'content' => 'This contect will come from Database'));
					$SectionCount++;
				}
			}
			if($SectionCount > 1)
				break;
			// 			if(count($InnerSectionContent) >= 2)
			// 				break;
		}
		return $SectionTransit;
	}

	/**
	 * GetMajorSolarReturnForCollectiveSection() function fetch the major 2 influences of the specified sections
	 * @param Array $InnerSectionContent
	 */
	protected function GetMajorSolarReturnForCollectiveSection($InnerSectionContent) {
		global $Global_Solar_TransitSortedList;
		$SectionTransit = array();
		$SectionTransit = $InnerSectionContent;
		$SectionCount = 0;

		foreach($Global_Solar_TransitSortedList as $Key => $Item) {
			$PT = $Item['pt'];
			$ASP = $Item['asp'];
			$PN = $Item['pn'];
			$AspectType = $Item['aspecttype'];
			$IsPlanet = $Item['isplanet'];
			$Rank = trim( $Item['totalrank'] );

			if( in_array($PT, $this->CollectivePlanet) ) {
				if( !in_array($PN, $this->DoNotIncludePlanet) ) {
					array_push($SectionTransit, array(
					'pt' => $PT,
					'asp' => $ASP,
					'pn' => $PN,
					'isplanet' => $IsPlanet,
					'hitdate' => '',
					'aspecttype' => $AspectType,
					'totalrank' => $Rank,
					'hitcounter' => array(),
					'mainheading' => 'This contect will come from Database',
					'content' => 'This contect will come from Database')
					);
					$SectionCount++;
				}
			}
			if($SectionCount > 1)
				break;
			// 			if(count($SectionTransit) >= 2)
			// 				break;
		}

		return $SectionTransit;
	}

	/**
	 * GetMajorSolarReturnForSection() function fetch the major 2 influences of the specified sections
	 * @param string $SolarReturnPlanet
	 */
	protected function GetMajorSolarReturnForSection($SolarReturnPlanet, $InnerSectionContent) {
		global $Global_Solar_TransitSortedList;
		$SectionTransit = array();
		$SectionTransit = $InnerSectionContent;
		$SectionCount = 0;

		foreach($Global_Solar_TransitSortedList as $Key => $Item) {
			$PT = $Item['pt'];
			$ASP = $Item['asp'];
			$PN = $Item['pn'];
			$AspectType = $Item['aspecttype'];
			$IsPlanet = $Item['isplanet'];
			$Rank = trim( $Item['totalrank'] );

			if($PT == $SolarReturnPlanet && $IsPlanet == 1 && $PT != $PN) {
				array_push($SectionTransit, array(
				'pt' => $PT,
				'asp' => $ASP,
				'pn' => $PN,
				'isplanet' => $IsPlanet,
				'hitdate' => '',
				'aspecttype' => $AspectType,
				'totalrank' => $Rank,
				'hitcounter' => array(),
				'mainheading' => 'This contect will come from Database',
				'content' => 'This contect will come from Database')
				);
				$SectionCount++;
			}
			if($SectionCount > 1)
				break;
			// 			if(count($SectionTransit) >= 2)
			// 				break;
		}

		return $SectionTransit;
	}

	/**
	 * GetMajorInternalAspectSRCollectiveSection() return major aspect to Solar return to chart
	 * @param Array $InternalSRToSR
	 */
	protected function GetMajorInternalAspectSRCollectiveSection($InternalSRToSR) {
		$SectionTransit = array();
		$SectionTransit = $InternalSRToSR;
		$SectionCount = 0;
		reset($this->SRInternalAspect);

		foreach($this->SRInternalAspect as $Key => $Item) {
			$PT = $Item['pt'];
			$ASP = $Item['asp'];
			$PN = $Item['pn'];
			$AspectType = $Item['aspecttype'];
			$IsPlanet = $Item['isplanet'];
			$Rank = trim( $Item['totalrank'] );

			if( in_array($PT, $this->CollectivePlanet) ) {
				if( !in_array($PN, $this->DoNotIncludePlanet) ) {
					array_push($SectionTransit, $Item);
					$SectionCount++;
				}
			}
			if($SectionCount >= 4)
				break;
			// 			if(count($SectionTransit) >= 4)
			// 				break;
		}

		return $SectionTransit;
	}


	protected function GetMajorInternalAspectSR($TransitingPlanet, $InternalSRToSR) {
		$SectionTransit = array();
		$SectionTransit = $InternalSRToSR;
		$SectionCount = 0;
		reset($this->SRInternalAspect);

		foreach($this->SRInternalAspect as $Key => $Item) {
			$PT = $Item['pt'];
			$ASP = $Item['asp'];
			$PN = $Item['pn'];
			$AspectType = $Item['aspecttype'];
			$IsPlanet = $Item['isplanet'];
			$Rank = trim( $Item['totalrank'] );

			if($PT == $TransitingPlanet) {
				array_push($SectionTransit, $Item);
				$SectionCount++;
			}
			if($SectionCount >= 4)
				break;
			// 			if(count($SectionTransit) >= 4)
			// 				break;
		}

		//if( count( $SectionTransit ) == 0 && ( $TransitingPlanet == '1012' || $TransitingPlanet == '1013' ) ) {
		if( $TransitingPlanet == '1012' || $TransitingPlanet == '1013' ) {

			$TPlanet = '';
			reset($this->SRInternalAspect);
			if ( $TransitingPlanet == '1012' ) {
				$TPlanet = $this->SolarHouseCups[0];
			}
			else if ( $TransitingPlanet == '1013' ) {
				$TPlanet = $this->SolarHouseCups[9];
			}

			foreach($this->SRInternalAspect as $Key => $Item) {
				$PT = $Item['pt'];
				$ASP = $Item['asp'];
				$PN = $Item['pn'];
				$AspectType = 'SRRULER';
				$IsPlanet = $Item['isplanet'];
				$Rank = trim( $Item['totalrank'] );

				if($PT == $TPlanet || $PN == $TPlanet) {
					array_push($SectionTransit, $Item);
					$SectionCount++;
				}
				if($SectionCount >= 4)
					break;
				// 				if(count($SectionTransit) >= 4)
				// 					break;
			}
		}

		return $SectionTransit;
	}

	/**
	 * GetPlanetIngrassCollectiveSection() Searching for Sign ingrass by progression
	 * @param $PRIngrass
	 */
	protected function GetPlanetIngrassCollectiveSection($PRIngrass){
		global $Global_Progression_TransitSortedList;
		global $SectionHeadings;
		global $Global_CurrntYear;
		global $Global_NextYear;

		$cYear = $Global_CurrntYear;
		$newdate = strtotime ( '-6 month' , strtotime ( $cYear ) ) ;
		$newdate = date ( 'Y-m-d' , $newdate );
		$CurrntYear = $newdate;
			
		$cNext = $Global_NextYear;
		$nextnewdate = strtotime ( '+6 month' , strtotime ( $cNext ) ) ;
		$nextnewdate = date ( 'Y-m-d' , $nextnewdate );
		$NextYear = $nextnewdate;
			
		$SectionTransit = array();
		$SectionTransit = $PRIngrass;
		$SectionCount = 0;

		foreach($Global_Progression_TransitSortedList as $Key => $Item) {
			$PT = $Item['pt'];
			$ASP = $Item['asp'];
			$PN = $Item['pn'];
			$AspectType = $Item['aspecttype'];
			$IsPlanet = $Item['isplanet'];
			$DT = trim( $Item['hitdate'] );
			$Rank = trim( $Item['totalrank'] );

			if($DT >= $CurrntYear && $DT <= $NextYear ) {
				if( in_array($PT, $this->CollectivePlanet) ) {
					if( !in_array($PN, $this->DoNotIncludePlanet) ) {
						if($ASP == '-->') {
							array_push($SectionTransit, array('pt' => $PT,
							'asp' => $ASP,
							'pn' => $PN,
							'isplanet' => $IsPlanet,
							'hitdate' => $DT,
							'aspecttype' => $AspectType,
							'totalrank' => $Rank,
							'hitcounter' => array(),
							'mainheading' => 'This contect will come from Database',
							'content' => 'This contect will come from Database'));
							$SectionCount++;
						}
					}
				}
			}
		}

		return $SectionTransit;
	}


	/**
	 * GetPlanetIngrass() Searching for Sign ingrass by progression
	 * @param $TransitingPlanet
	 * @param $PRIngrass
	 */
	protected function GetPlanetIngrass($IngrassingPlanet, $PRIngrass){
		global $Global_Progression_TransitSortedList;
		global $SectionHeadings;
		global $Global_CurrntYear;
		global $Global_NextYear;

		$cYear = $Global_CurrntYear;
		$newdate = strtotime ( '-6 month' , strtotime ( $cYear ) ) ;
		$newdate = date ( 'Y-m-d' , $newdate );
		$CurrntYear = $newdate;
			
		$cNext = $Global_NextYear;
		$nextnewdate = strtotime ( '+6 month' , strtotime ( $cNext ) ) ;
		$nextnewdate = date ( 'Y-m-d' , $nextnewdate );
		$NextYear = $nextnewdate;
			
		$SectionTransit = array();
		$SectionTransit = $PRIngrass;
		$SectionCount = 0;

		foreach($Global_Progression_TransitSortedList as $Key => $Item) {
			$PT = $Item['pt'];
			$ASP = $Item['asp'];
			$PN = $Item['pn'];
			$AspectType = $Item['aspecttype'];
			$IsPlanet = $Item['isplanet'];
			$DT = trim( $Item['hitdate'] );
			$Rank = trim( $Item['totalrank'] );

			if($DT >= $CurrntYear && $DT <= $NextYear ) {
				if($PT == $IngrassingPlanet && $ASP == '-->') {
					array_push($SectionTransit, array('pt' => $PT,
					'asp' => $ASP,
					'pn' => $PN,
					'isplanet' => $IsPlanet,
					'hitdate' => $DT,
					'aspecttype' => $AspectType,
					'totalrank' => $Rank,
					'hitcounter' => array(),
					'mainheading' => 'This contect will come from Database',
					'content' => 'This contect will come from Database'));
					$SectionCount++;
				}
			}
		}

		return $SectionTransit;
	}

	/**
	 * GetMoonProgressedPosition() function will return prograssed moon house and sign position
	 * @param stirng $PlanetNumber
	 */
	protected function GetMoonProgressedPosition($PlanetNumber, $ProgressedPlanetPosition) {
		echo "<pre>********************************************************************** GetMoonProgressedPosition()</pre>";
		global $Global_Progression_MObject;
		global $NameOfPlanets;

		$this->MoonPosition = array();

		if(count($Global_Progression_MObject) > 0) {
			if(array_key_exists($NameOfPlanets[$PlanetNumber], $Global_Progression_MObject)) {
				$HouseNo = sprintf('%04d', $Global_Progression_MObject[$NameOfPlanets[$PlanetNumber]]['house']);
				$SignNo = sprintf('%04d', $Global_Progression_MObject[$NameOfPlanets[$PlanetNumber]]['sign'] + 101);

				array_push($ProgressedPlanetPosition, array('pt' =>  $PlanetNumber,			//'Progressed planet number',
				'asp' => '',						//'No Need',
				'pn' => $SignNo,					//'Sign name',
				'isplanet' => 1,					//Always Planet
				'aspecttype' => 'PRSIGN',			//Progressed planet in Sign
				'pts' => $SignNo,					//'Progressed Planet in Sign',
				'pth' => $HouseNo,					//'Progressed Planet in House',
				'mainheading' => '',
				'hitdate' => '',
				'content' => 'Content from database',
				'totalrank' => 0));
				//array_push($MoonPosition, array('house' => $HouseNo, 'sign' => $SignNo));
				$this->MoonPosition['house'] = $HouseNo;
				$this->MoonPosition['sign'] = $SignNo;
			}
		}
		return $ProgressedPlanetPosition;
	}

	function PrintTop3Themes() {

		$this->SetFont('wows', '', 14);
		$this->Cell($this->TableCellWidth, $this->TableHeight, 'Top 3 Theme - Sir', 0, 0, 'L', false);
		$this->Ln();

		$this->Cell($this->TableCellWidth, $this->TableHeight, 'Transiting Planet', 1, 0, 'L', false);

		$this->SetX($this->LeftMargin + $this->TableCellWidth);
		$this->Cell($this->TableCellWidth, $this->TableHeight, 'Aspect', 1, 0, 'L', false);

		$this->SetX($this->LeftMargin + $this->TableCellWidth * 2);
		$this->Cell($this->TableCellWidth, $this->TableHeight, 'Natal Planet', 1, 0, 'L', false);

		$this->SetX($this->LeftMargin + $this->TableCellWidth * 3);
		$this->Cell($this->TableCellWidth, $this->TableHeight, 'Hitting Date', 1, 0, 'L', false);
		$this->Ln();

		global $AspectRank;
		global $NatalPlanetRank;
		global $TransitingPlanetRank;
		global $WowSymbolFonts;
		global $NameOfPlanets;
		global $NameOfHouses;
		global $NameOfAspects;
		global $Global_PreviousYear;				//Trancking last year data  (like 16-July-2011)
		global $Global_NextYear;					//Trancking next year data  (like 16-July-2013)
		global $Global_CurrntYear;					//Trancking next year data  (like 16-July-2012)

		foreach ($this->TopTheme as $key => $trWin) {
			$pt = trim( $trWin['pt'] );
			$asp = trim( $trWin['asp'] );
			$pn =  trim( $trWin['pn'] );
			$aspecttype =  trim( $trWin['aspecttype'] );
			$dt = trim( $trWin['hitdate'] );

			$rank = trim( $trWin['totalrank'] );
			//Column 1 = Transiting Planet
			$this->Cell($this->TableCellWidth, $this->TableHeight, $NameOfPlanets[$pt] . ' - ' . $TransitingPlanetRank[$pt], 1, 0, 'L', false);

			//Column 2 = Aspect
			$this->SetX($this->LeftMargin + $this->TableCellWidth);
			$this->Cell($this->TableCellWidth, $this->TableHeight, chr($WowSymbolFonts[ $NameOfAspects[$asp]])  . ' ' . $NameOfAspects[$asp] . ' - ' . $AspectRank[$asp], 1, 0, 'L', false);

			//Column 3 = Natal Planet
			$this->SetX($this->LeftMargin + $this->TableCellWidth * 2);
			$this->Cell($this->TableCellWidth, $this->TableHeight, $NatalPlanetRank[$pn] . ' - ' . $NameOfPlanets[$pn] . ' - ' . $rank, 1, 0, 'L', false);

			//Column 4 = Hit
			$content = $dt . " $aspecttype";
			$this->SetX($this->LeftMargin + $this->TableCellWidth * 3);
			$this->Cell($this->TableCellWidth, $this->TableHeight, $content, 1, 0, 'L', false);
			$this->Ln();

			if($aspecttype == 'TR') {
				$HitDates = '';
				if(count($trWin['test']['hitcounter']) > 0){
					foreach($trWin['test']['hitcounter'] as $KeyDate) {
						if(isset($HitDates) && $HitDates != '') {
							$HitDates .= ' = ';
						}

						$HitDates .= $KeyDate;
					}

					$this->SetX($this->LeftMargin);
					$this->Cell($this->TableCellWidth * 4 , $this->TableHeight, $HitDates, 1, 0, 'L', false);
					$this->Ln();
				}
			}
		}
	}

	/**
	 * PrintReportData() function print the section headlines and section wise transit, progression and solar return
	 */
	function PrintReportData() {
		//$this->AddPage();
		$this->Ln();
		$this->Cell($this->TableCellWidth * 4, $this->TableHeight,	'Section Headinlines', 1, 1, 'L', false);  	// PRINT SECTION HEADING
		$this->Ln();

		global $Global_Language;
		global $AspectRank;
		global $NatalPlanetRank;
		global $TransitingPlanetRank;
		global $WowSymbolFonts;
		global $NameOfPlanets;
		global $NameOfHouses;
		global $NameOfAspects;
		global $transiting_aspects;

		foreach ($this->SectionHeadingContents as $key => $trWin) {
			$pt = trim( $trWin['pt'] );
			$asp = trim( $trWin['asp'] );
			$pn =  trim( $trWin['pn'] );
			$MainHeading = trim( $trWin['mainheading'] );
			$Maincontent = $trWin['content'] != '' ? trim( $trWin['content'] ) : $trWin['content'];
			$AspectType = trim( $trWin['aspecttype'] );
			$Rank = trim( $trWin['totalrank'] );

			$isPlanet = trim( $trWin['isplanet'] );

			$dt = trim( $trWin['hitdate'] );

			$this->Cell($this->TableCellWidth * 4, $this->TableHeight,	$MainHeading, 1, 0, 'L', false);  	// PRINT SECTION HEADING
			$this->Ln();
			$this->Cell($this->TableCellWidth * 4, $this->TableHeight,	$Maincontent, 1, 0, 'L', false);	// SECTION HEADLINE
			$this->Ln();

			if($AspectType == 'TR') {
				$HeadlineBasedOn = $NameOfPlanets[$pn] . ' ' . $transiting_aspects[$Global_Language][$asp] . ' ' . $NameOfPlanets[$pt] . ' -' . $NameOfAspects[$asp];
				$HeadlineBasedOn .=' - ' . $AspectType . ' Dt- ' . $dt . ' Rank- ' . $Rank;
			}
			else {
				$HeadlineBasedOn = $NameOfPlanets[$pt] . ' ' . $transiting_aspects[$Global_Language][$asp] . ' ' . $NameOfPlanets[$pn] . ' -' . $NameOfAspects[$asp];
				$HeadlineBasedOn .=' - ' . $AspectType . ' Dt- ' . $dt . ' Rank- ' . $Rank;
			}

			$this->Cell($this->TableCellWidth * 4, $this->TableHeight,	$HeadlineBasedOn, 1, 0, 'L', false);	// SECTION HEADLINE
			$this->Ln();

			if(is_array($trWin['subsection'])) {

				foreach ($trWin['subsection'] as $K => $InnerItems) {

					if(is_array($InnerItems)) {
						$this->PrintSubSection($InnerItems, $K);
					}

				}
			}

			if(array_key_exists('srtonatal', $trWin)) {
				if(is_array($trWin['srtonatal']) && count($trWin['srtonatal']) > 0) {
					$SRtoSR = 'Solar Return to Natal ( TOP 2 aspects )';

					$this->Cell($this->TableCellWidth * 4, $this->TableHeight,	$SRtoSR, 1, 0, 'L', false);	// SECTION HEADLINE
					$this->Ln();

					foreach ($trWin['srtonatal'] as $K => $SrToSrItem) {

						if(is_array($SrToSrItem)) {
							$this->PrintSubSection($SrToSrItem, $K);
						}

					}
				}
			}

			if(array_key_exists('srtosr', $trWin)) {
				if(is_array($trWin['srtosr']) && count($trWin['srtosr']) > 0) {
					$SRtoSR = 'Solar Return TOP 4 aspects (Internal Chart)';

					$this->Cell($this->TableCellWidth * 4, $this->TableHeight,	$SRtoSR, 1, 0, 'L', false);	// SECTION HEADLINE
					$this->Ln();

					foreach ($trWin['srtosr'] as $K => $SrToSrItem) {

						if(is_array($SrToSrItem)) {
							$this->PrintSolarReturnInternalAspects($SrToSrItem, $K);
						}

					}
				}
			}

			if(array_key_exists('pringrass', $trWin)) {
				if(is_array($trWin['pringrass']) && count($trWin['pringrass']) > 0) {
					$SPRIngrass = 'Sign Ingrass by prograssion';

					$this->Cell($this->TableCellWidth * 4, $this->TableHeight,	$SPRIngrass, 1, 0, 'L', false);	// SECTION HEADLINE
					$this->Ln();

					foreach ($trWin['pringrass'] as $K => $PRIngrassItem) {

						if(is_array($PRIngrassItem)) {
							$this->PrintIngrassSign($PRIngrassItem, $K);
						}

					}
				}
			}
			//			if(array_key_exists('moonposition', $trWin)) {
			//				if(is_array($trWin['moonposition']) && count($trWin['moonposition']) > 0) {
			//					$SPRIngrass = 'Moon Position in Prograssed chart';
			//
			//					$this->Cell($this->TableCellWidth * 4, $this->TableHeight,	$SPRIngrass, 1, 0, 'L', false);	// SECTION HEADLINE
			//					$this->Ln();
			//
			//					foreach ($trWin['moonposition'] as $K => $PRIngrassItem) {
			//						if(is_array($PRIngrassItem)) {
			//							$this->MoonPosition($PRIngrassItem, $K);
			//						}
			//					}
			//				}
			//			}

			$this->Ln();
		}
	}

	/**
	 * PrintSubSection() display Section's internal aspect list
	 * @param Array $SubSection
	 * @param Integer $Index
	 */
	protected function PrintSubSection($SubSection, $Index) {
		global $Global_Language;
		global $AspectRank;
		global $NatalPlanetRank;
		global $TransitingPlanetRank;
		global $WowSymbolFonts;
		global $NameOfPlanets;
		global $NameOfHouses;
		global $NameOfAspects;
		global $transiting_aspects;

		$Index++;
		$AspectType = trim( $SubSection['aspecttype'] );

		$pt = sprintf("%04d", trim( $SubSection['pt'] ) );
		$asp = trim( $SubSection['asp'] );
		$pn =  sprintf("%04d", trim( $SubSection['pn'] ) );
		$MainHeading = trim( $SubSection['mainheading'] );
		$Maincontent = trim( $SubSection['content'] );
		$isPlanet = trim( $SubSection['isplanet'] );
		$dt = trim( $SubSection['hitdate'] );
		$Rank = trim( $SubSection['totalrank'] );

		if($AspectType == 'TR') {
			$HeadlineBasedOn = '  '. $Index. ' = '. $dt .' '. $NameOfPlanets[$pn] . ' ' . $transiting_aspects[$Global_Language][$asp] . ' ' . $NameOfPlanets[$pt] . ' -' . $NameOfAspects[$asp];
			$HeadlineBasedOn .= " - $AspectType Rank- $Rank";
		}
		elseif($AspectType == 'SRSIGN' || $AspectType == 'PRSIGN') {
			$pn =  sprintf("%04d", $pn + 1);
			$HeadlineBasedOn = '  '. $Index. ' = '. $NameOfPlanets[$pt] . ' in ' . $NameOfPlanets[$pn];
		}
		else {
			$HeadlineBasedOn = '  '. $Index. ' = '. $dt .' '. $NameOfPlanets[$pt] . ' ' . $transiting_aspects[$Global_Language][$asp] . ' ' . $NameOfPlanets[$pn] . ' -' . $NameOfAspects[$asp];
			$HeadlineBasedOn .= " - $AspectType Rank- $Rank";
		}
		$this->Cell($this->TableCellWidth * 4, $this->TableHeight,	$HeadlineBasedOn, 1, 0, 'L', false);	// SECTION HEADLINE
		$this->Ln();

		if($AspectType == 'TR') {
			$HitDates = '';
			if(count($SubSection['hitcounter']) > 1){
				foreach($SubSection['hitcounter'] as $KeyDate) {
					if(isset($HitDates) && $HitDates != '') {
						$HitDates .= ' = ';
					}

					$HitDates .= $KeyDate;
				}
				$HitDates = "       " . $HitDates;
				$this->SetX($this->LeftMargin);
				$this->Cell($this->TableCellWidth * 4 , $this->TableHeight, $HitDates, 1, 0, 'L', false);
				$this->Ln();
			}
		}
	}


	/**
	 * PrintSolarReturnInternalAspects
	 * @param Array $SubSection
	 * @param Integer $Index
	 */
	protected function PrintSolarReturnInternalAspects($SubSection, $Index) {
		global $Global_Language;
		global $AspectRank;
		global $NatalPlanetRank;
		global $TransitingPlanetRank;
		global $WowSymbolFonts;
		global $NameOfPlanets;
		global $NameOfHouses;
		global $NameOfAspects;
		global $transiting_aspects;

		$Index++;

		$pt = trim( $SubSection['pt'] );
		$asp = trim( $SubSection['asp'] );
		$pn =  trim( $SubSection['pn'] );
		//$PtInSign = sprintf('%04d', trim( $SubSection['pts'] ));
		$PtInSign = trim( $SubSection['pts'] );
		$PtInHouse = trim( $SubSection['pth'] );
			
		//$PnInSign = sprintf('%04d', trim( $SubSection['pns'] ));
		$PnInSign = trim( $SubSection['pns'] );
		$PnInHouse = trim( $SubSection['pnh'] );
			
		$AspectType = trim( $SubSection['aspecttype'] );
		$Rank = trim( $SubSection['totalrank'] );

		reset($NameOfPlanets);
		reset($NameOfAspects);

		$PTInfo = $NameOfPlanets[$pt] . " - $PtInHouse - " . $NameOfPlanets[$PtInSign];
		$PNInfo = $NameOfPlanets[$pn] . " - $PnInHouse - " . $NameOfPlanets[$PnInSign];

		$HeadlineBasedOn = '  '. $Index. '= '. $PTInfo . ' ' . $transiting_aspects[$Global_Language][$asp] . ' ' . $PNInfo . ' -' . $NameOfAspects[$asp];
		$HeadlineBasedOn .= " - Rank- $Rank";

		$this->Cell($this->TableCellWidth * 4, $this->TableHeight,	$HeadlineBasedOn, 1, 0, 'L', false);	// SECTION HEADLINE
		$this->Ln();
	}

	/**
	 * PrintIngrassSign() Display Ingrass
	 * @param Array $SubSection
	 * @param Integer $Index
	 */
	protected function PrintIngrassSign($SubSection, $Index) {
		global $Global_Language;
		global $AspectRank;
		global $NatalPlanetRank;
		global $TransitingPlanetRank;
		global $WowSymbolFonts;
		global $NameOfPlanets;
		global $NameOfHouses;
		global $NameOfAspects;
		global $transiting_aspects;

		$Index++;

		$pt = trim( $SubSection['pt'] );
		$asp = trim( $SubSection['asp'] );
		$pn =  trim( $SubSection['pn'] );
		$dt = trim( $SubSection['hitdate'] );

		$AspectType = trim( $SubSection['aspecttype'] );
		$Rank = trim( $SubSection['totalrank'] );

		reset($NameOfPlanets);
		reset($NameOfAspects);

		$HeadlineBasedOn = $NameOfPlanets[$pt] . ' - ' . $transiting_aspects[$Global_Language][$asp] .' - ' . $NameOfPlanets[$pn];
		$HeadlineBasedOn .= ' Dt: '	. $dt;

		$this->Cell($this->TableCellWidth * 4, $this->TableHeight,	$HeadlineBasedOn, 1, 0, 'L', false);	// SECTION HEADLINE
		$this->Ln();
	}

	/**
	 * MoonPosition() Display Ingrass
	 * @param Array $SubSection
	 */
	protected function MoonPosition($SubSection) {
		global $Global_Language;
		global $AspectRank;
		global $NatalPlanetRank;
		global $TransitingPlanetRank;
		global $WowSymbolFonts;
		global $NameOfPlanets;
		global $NameOfHouses;
		global $NameOfAspects;
		global $transiting_aspects;

		$House =  sprintf('%04d', trim( $SubSection['house'] ));
		$SingNo = sprintf('%04d', trim( $SubSection['sign'] ) + 101 );

		reset($NameOfPlanets);
		reset($NameOfHouses);

		$HeadlineBasedOn = 'Moon in ' . $NameOfHouses[$House] . ' - ' . $NameOfPlanets[$SingNo];

		$this->Cell($this->TableCellWidth * 4, $this->TableHeight,	$HeadlineBasedOn, 1, 0, 'L', false);	// SECTION HEADLINE
		$this->Ln();
	}

	/**
	 * PrintSolarReturnTopAspect() function print the Solar return top 4 aspects
	 */
	function PrintSolarReturnTopAspect() {
		$this->Ln();
		$this->Cell($this->TableCellWidth * 4, $this->TableHeight,	'Solar return internal Aspects - TOP 4', 1, 1, 'L', false);  	// PRINT SECTION HEADING
		$this->Ln();

		global $Global_Language;
		global $AspectRank;
		global $NatalPlanetRank;
		global $TransitingPlanetRank;
		global $WowSymbolFonts;
		global $NameOfPlanets;
		global $NameOfHouses;
		global $NameOfAspects;
		global $transiting_aspects;
		$TableCellWidth = 21.25;

		$this->SetFont('wows', '', 12);
		//COL 1
		$this->SetX($this->LeftMargin);
		$this->Cell($this->TableCellWidth + $TableCellWidth , $this->TableHeight, 'Planet 1 - House - Sign', 1, 0, 'L', false);

		//COL 2
		$this->SetX($this->LeftMargin + $this->TableCellWidth +  $TableCellWidth);
		$this->Cell($TableCellWidth, $this->TableHeight, 'Aspect', 1, 0, 'L', false);

		//COL 3
		$this->SetX($this->LeftMargin + $this->TableCellWidth +  $TableCellWidth + $TableCellWidth);
		$this->Cell($this->TableCellWidth + $TableCellWidth, $this->TableHeight, 'Planet 2 - House - Sign', 1, 0, 'L', false);

		//COL 4
		$this->SetX($this->LeftMargin + $this->TableCellWidth + $this->TableCellWidth + $TableCellWidth + $TableCellWidth + $TableCellWidth);
		$this->Cell($TableCellWidth, $this->TableHeight, 'RANK', 1, 0, 'L', false);
		$this->Ln();

		foreach ($this->SRInternalAspect as $key => $trWin) {
			$pt = trim( $trWin['pt'] );
			$asp = trim( $trWin['asp'] );
			$pn =  trim( $trWin['pn'] );
			$PtInSign = sprintf('%04d', trim( $trWin['pts'] ));
			$PtInHouse = trim( $trWin['pth'] );

			$PnInSign = sprintf('%04d', trim( $trWin['pns'] ));
			$PnInHouse = trim( $trWin['pnh'] );

			$AspectType = trim( $trWin['aspecttype'] );
			$Rank = trim( $trWin['totalrank'] );
			//$isPlanet = trim( $trWin['isplanet'] );

			reset($NameOfPlanets);
			reset($NameOfAspects);


			$PTInfo = $NameOfPlanets[$pt] . " - $PtInHouse - " . $NameOfPlanets[$PtInSign];
			$PNInfo = $NameOfPlanets[$pn] . " - $PnInHouse - " . $NameOfPlanets[$PnInSign];

			//First Planet name (Col 1)
			$this->SetX($this->LeftMargin);
			$this->Cell($this->TableCellWidth + $TableCellWidth * 2, $this->TableHeight, $PTInfo, 1, 0, 'L', false);

			//Aspect (Col 2)
			$this->SetX($this->LeftMargin + $this->TableCellWidth +  $TableCellWidth);
			$this->Cell($TableCellWidth, $this->TableHeight, $NameOfAspects[$asp], 1, 0, 'L', false);

			//Second Planet name  (Col 3)
			$this->SetX($this->LeftMargin + $this->TableCellWidth +  $TableCellWidth + $TableCellWidth);
			$this->Cell($this->TableCellWidth + $TableCellWidth * 2, $this->TableHeight, $PNInfo, 1, 0, 'L', false);

			//RANK  (Col 4)
			$this->SetX($this->LeftMargin + $this->TableCellWidth + $this->TableCellWidth + $TableCellWidth + $TableCellWidth + $TableCellWidth);
			$this->Cell($TableCellWidth, $this->TableHeight, $Rank, 1, 0, 'L', false);
			$this->Ln();
		}
	}

	/**
	 * GetSolarReturnSignPosition() return the sign, house and Planet position of planet from solar return
	 * @param $SearchSignForMe
	 */
	function GetSolarReturnSignPosition($SearchSignForMe, $SolarReturnSignPosition) {
		global $Global_Language;
		global $global_Solar_HouseCups;
		global $global_Solar_PlanetLongitude;
		global $Global_Solar_MObject;
		global $SignRulers;
		global $NameOfSigns;
		global $NameOfPlanets;
		global $planets;

		if($SearchSignForMe != '') {
			$PT = $SearchSignForMe;
			$ASP = '000';
			$PN = 'NA';
			$IsPlanet = '0';
			$AspectType = 'SRSIGN';
			$TotalRank = 0;

			$PTSH = $this->GetSingAndHouse($SearchSignForMe);
			array_push($SolarReturnSignPosition, array('pt' =>  $PT,								//'Solar Return number',
			'asp' => $ASP,								//'No Need',
			'pn' => $PTSH["sing"],						//'NO Need',
			'isplanet' => $IsPlanet,					//Always Planet
			'aspecttype' => $AspectType,				//Solar return planet in Sign
			'pts' => $PTSH["sing"],						//'Solar return Planet in Sign',
			'pth' => $PTSH["house"],					//'Solar return Planet in House',
			'mainheading' => '',
			'hitdate' => '',
			'content' => 'Content from database',
			'totalrank' => $TotalRank));
		}

		return $SolarReturnSignPosition;
	}


	protected function SetSolarReturnHouseRuler() {
		global $Global_Language;
		global $global_Solar_HouseCups;
		global $global_Solar_PlanetLongitude;
		global $Global_Solar_MObject;
		global $SignRulers;
		global $NameOfSigns;
		global $NameOfPlanets;
		global $planets;

		$this->SolarHouseCups = array();
		for ($i = 0; $i < 12; $i++) {
			reset($SignRulers);
			//For capturing Zodia index
			$sign = intval($global_Solar_HouseCups[$i] / 30.0);
			$this->SolarHouseCups[$i] = $SignRulers[$sign];
		}

		for ($planetIndex = 0; $planetIndex < 12; $planetIndex++) {
			reset($SignRulers);

			$signNo = $Global_Solar_MObject[$planets[$planetIndex]]['sign'];
			if($signNo > 0) {
				$this->SolarHouseCups[$planetIndex + 1000] = $SignRulers[$signNo - 1];
			}
			else{
				$this->SolarHouseCups[$planetIndex + 1000] = $SignRulers[$signNo];
			}
		}

		ksort($this->SolarHouseCups);
	}


	/**
	 * SolarReturnInternalAspects() function print of Solar Return Internal aspects
	 */
	function SolarReturnInternalAspects() {
		echo "<pre>********************************************************************** SolarReturnInternalAspects()</pre>";
		global $Global_Solar_InternalAspect;

		$this->SRInternalAspect = array();

		foreach($Global_Solar_InternalAspect as $Key => $Item) {
			$PT = trim( $Item['pt'] );
			$ASP = trim( $Item['asp'] );
			$PN = trim( $Item['pn'] );
			$IsPlanet = $Item['isplanet'];
			$AspectType = $Item['aspecttype'];
			$TotalRank = $Item['totalrank'];

			$PTSH = $this->GetSingAndHouse($PT);
			$PNSH = $this->GetSingAndHouse($PN);

			array_push($this->SRInternalAspect, array('pt' =>  $PT,							//'Aspecting Planet number',
			'asp' => $ASP,						//'Aspect',
			'pn' => $PN,						//'Aspected Planet number',
			'isplanet' => $IsPlanet,
			'aspecttype' => $AspectType,
			'pts' => $PTSH["sing"],				//'Aspecting Planet Sign',
			'pth' => $PTSH["house"],				//'Aspecting Planet House',
			'pns' => $PNSH["sing"],				//'Aspected Planet Sign',
			'pnh' => $PNSH["house"],				//'Aspected Planet House',
			'content' => 'Content from database',
			'totalrank' => $TotalRank));
		}
	}

	/**
	 * GetSingAndHouse() this function returns the Solar return House and Sign Position
	 * @param string $PlanetName
	 * @return Array string
	 */
	protected function GetSingAndHouse($PlanetName) {
		global $Global_Solar_MObject;
		global $NameOfPlanets;
		$SingAndHouse = array("sing" => 0, "house" => 0);

		$PlanetName = str_replace('.', '', $PlanetName);

		if($PlanetName == '1012' || $PlanetName == '1013' ){
			$HouseNo = 1;
			if($PlanetName == '1013') {
				$HouseNo = 10;
			}
			$SignNo = $this->GetSolarReturnHouseSignPosition($HouseNo);
			$SingAndHouse["sing"] = $SignNo;
			$SingAndHouse["house"] = '0';
		}
		else {
			if ( array_key_exists( $PlanetName, $NameOfPlanets ) ) {
				$PName = str_replace('.', '', $NameOfPlanets[$PlanetName]);
				if( array_key_exists( $PName, $Global_Solar_MObject ) ) {
					$SingAndHouse["sing"] = sprintf('%04d', intval($Global_Solar_MObject[$PName]['sign']) + 101);
					$SingAndHouse["house"] = $Global_Solar_MObject[$PName]['house'];
				}
			}
		}

		return $SingAndHouse;
	}

	function SortCollectiveSection($SortTransit) {
		$sortedtransits = array();
		$TopAspects = array();
			
		$sortedtransits = $this->msort($SortTransit);

		while (list($key, $value) = each($sortedtransits)) {
			$i = $key;
			array_push($TopAspects, $value);
		}
		return $TopAspects;
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

	//PRINTING Introduction Text and Age Text
	function SetIntroductionText() {
		echo "<pre>********************************************************************** SetIntroductionText()</pre>";
		global $Global_Language;
		global $UserAge;
		global $IntroductionText;
		global $GenericText;

		$GenericHelper = new GenericHouseAndSignFinder();

		foreach($IntroductionText[$Global_Language] as $Key => $Value) {
			if($Key > 0) {
				$GenericHelper->PrintSectionDescription($this, $Value);
			}
		}
		$this->AddPage();
		$GenericHelper->PrintIntroctionTitle($this, $IntroductionText[$Global_Language]["Introduction"]);	//For 1st page of the Year Introduction
		
		$this->GenerateGenericAgeText();
		$this->GenerateGenericPlutoException();

		$this->GenericGrowthText();
		/**
		 * @todo: We need to display only 3 house crossing text for both
		*/
		$this->SaturnAndJuputerThroughHouse();
		$this->PrintThemeText();
		$this->PrintThemeTester();
		$this->GenericTheme2text();

		/**
		 * @todo: Need to add the Pluto exception here
		 * create separate funtion for Pluto exception
		*/

		$this->LifePathSection();

		$this->CareerSection();

		$this->Personaldevelopment();

		$this->FeelingsAndSecurity();

		$this->MentalDevelopment();

		$this->ValuesANDLoveANDRelationships();

		$this->WillpowerANDAssertion();
			
		$this->CollectiveTrends();
			
		$this->PrintNextYearTheme();
			
		$this->PrintAnnotation();
	}

	function GenerateGenericAgeText() {
		echo "<pre>********************************************************************** GenerateGenericAgeText()</pre>";
		global $Global_Language;
		global $UserAge;
		global $Global_Natal_MObject;

		$GenericHelper = new GenericHouseAndSignFinder();

		$AgeDescription = '';
		$FindingPararm = '';
		$SplitedArray = array();
		
		$orderStateList = $this->YBookAgeText->GetList( array( array('age_no', '=', $UserAge) ) );

		foreach ($orderStateList as $sItem) {
			$AgeDescription = $sItem->age_text;
			$FindingPararm = $sItem->find_param;
		}
		
		if($FindingPararm != "") {
			$SplitedArray = $GenericHelper->SplitParamFinderArray($FindingPararm);
			$SplitedArray = $GenericHelper->GetHouseAndSignText($SplitedArray);
		}
		
		foreach ($SplitedArray as $Key => $Item) {
			$Item = ' ' . $Item . ' ';
			$AgeDescription = str_replace($Key, $Item, $AgeDescription);
		}

		//$AgeTitle = sprintf("#Age_%02d_Generic", $UserAge);
		//$GenericHelper->PrintSectionInternalTitle($this, $AgeTitle);
		$GenericHelper->PrintSectionDescription($this, $AgeDescription);
	}

	/**
	 * GenerateGenericPlutoException() Special case for the Pluto Square Pluto
	 */
	function GenerateGenericPlutoException() {
		echo "<pre>********************************************************************** GenerateGenericPlutoException()</pre>";
		global $Global_Language;
		global $UserAge;
		global $IsPlutoSquare;

		if($IsPlutoSquare == true) {
			$AgeDescription = '';
			$FindingPararm = '';

			$orderStateList = $this->YBookAgeText->GetList( array( array('age_no', '=', 0) ) );

			foreach ($orderStateList as $sItem) {
				$AgeDescription = $sItem->age_text;
				$FindingPararm = $sItem->find_param;
			}

			$SplitedArray = $this->GenericHelper->SplitParamFinderArray($FindingPararm);
			$SplitedArray = $this->GenericHelper->GetHouseAndSignText($SplitedArray);

			foreach ($SplitedArray as $Key => $Item) {
				$Item = ' ' . $Item . ' ';
				$AgeDescription = str_replace($Key, $Item, $AgeDescription);
			}

// 			$AgeTitle = "#Age_generic_PL";
// 			$this->GenericHelper->PrintSectionInternalTitle($this, $AgeTitle);
			$this->GenericHelper->PrintSectionDescription($this, $AgeDescription);
		}
	}

	function GenericGrowthText(){
		global $Global_Language;
		global $GenericText;
		global $UserAge;

		$GenericTextGetter = new GenericHouseAndSignFinder();
		//$GenericTextGetter->PrintSectionInternalTitle($this, '#Growth_text');
		$GenericTextGetter->PrintSectionDescription($this, $GenericText[$Global_Language]['Growth_text']);
	}

	function PrintThemeText(){
		global $Global_Language;
		global $GenericText;
		global $UserAge;

		$GenericTextGetter = new GenericHouseAndSignFinder();

		//$GenericTextGetter->PrintSectionInternalTitle($this, '#3THEMES_1');

		$FinalText = str_replace('[age]', sprintf($GenericText[$Global_Language]['[age]'], $UserAge), $GenericText[$Global_Language]['3THEMES_1']);
		//Theme Tester
		$GenericTextGetter->PrintSectionDescription($this, $FinalText);
	}

	function PrintThemeTester() {
		echo "<pre>********************************************************************** PrintThemeTester()</pre>";
		global $AspectRank;
		global $NatalPlanetRank;
		global $TransitingPlanetRank;
		global $WowSymbolFonts;
		global $NameOfPlanets;
		global $NameOfHouses;
		global $NameOfAspects;
		global $Global_PreviousYear;				//Trancking last year data  (like 16-July-2011)
		global $Global_NextYear;					//Trancking next year data  (like 16-July-2013)
		global $Global_CurrntYear;					//Trancking next year data  (like 16-July-2012)
		global $AbbrPlanetToFullName;
		global $Connector;
		global $AspectTypes;
		global $AspectAbbr;

		foreach ($this->TopTheme as $key => $trWin) {
			$pt = trim( $trWin['pt'] );
			$asp = trim( $trWin['asp'] );
			$pn =  trim( $trWin['pn'] );
			$aspecttype =  trim( $trWin['aspecttype'] );
			$dt = trim( $trWin['hitdate'] );

			$PTAbbr = $AbbrPlanetToFullName[$pt];
			$PNAbbr =  $AbbrPlanetToFullName[$pn];
			$AspectID = $AspectTypes[$asp];

			if(array_key_exists($aspecttype, $AspectAbbr)){
				$AspectStrength = $AspectAbbr[$aspecttype];
			}
			else {
				$AspectStrength ='T';
			}

			$GenericTextGetter = new GenericHouseAndSignFinder();
			$TesterText = $GenericTextGetter->GetTesterText($PTAbbr, $AspectID, $PNAbbr, $AspectStrength);

			if($TesterText == "" && $AspectStrength == 'P-R') {
				$AspectStrength = 'P';
				$TesterText = $GenericTextGetter->GetTesterText($PTAbbr, $AspectID, $PNAbbr, $AspectStrength);
			}

			if($TesterText != "") {
				//Setting THEME title
				//$ThemeTitle = '#' . $AbbrPlanetToFullName[$pt] . '/' . $AspectStrength . $Connector[$asp] .  $AbbrPlanetToFullName[$pn] . '_Tester';
				$ThemeTitle = "";
				$GenericTextGetter->PrintSectionInternalTitle($this, $ThemeTitle);
				//Theme Tester
				$GenericTextGetter->PrintSectionDescription($this, sprintf("- %s",  $TesterText));
			}
		}
	}

	function GenericTheme2text(){
		global $Global_Language;
		global $GenericText;
		$GenericClass = new GenericHouseAndSignFinder();

		//$GenericClass->PrintSectionInternalTitle($this, '#3THEMES_2');
		$GenericClass->PrintSectionDescription($this, $GenericText[$Global_Language]['3THEMES_2']);
	}

	function SaturnAndJuputerThroughHouse() {
		echo "<pre>********************************************************************** SaturnAndJuputerThroughHouse()</pre>";
		global $AbbrPlanetToFullName;
		global $Connector;
		global $AspectTypes;
		global $AspectAbbr;
		global $Global_NextYear;
		global $Global_CurrntYear;

		$GenericClass = new GenericHouseAndSignFinder();
		$IsMoving = 0;		
		
		//Check for Saturn House Crossing
		foreach ($this->SaturnJupiterCrossing as $Key => $TrasitingPlanet) {
			$PlanetT =  $AbbrPlanetToFullName[$TrasitingPlanet['pt']];
			$AspectType = $Connector[$TrasitingPlanet['asp']];
			$PlanetN = sprintf("%02d", $TrasitingPlanet['pn']);
			$InDate = $TrasitingPlanet['start'];	//2011-07-05	[YYYY-MM-DD]
			$OutDate = $TrasitingPlanet['end'];		//2012-05-23	[YYYY-MM-DD]
			$PlanetNText = $PlanetN;
			$IsBack = $TrasitingPlanet['isretrograde'];
			$IsStayInHouse = $TrasitingPlanet['isstayinhouse'];

			if($IsBack == 'D' && $PlanetT == 'SA') {
				//if($OutDate <= $Global_NextYear) {
				if($InDate >= $Global_CurrntYear && $InDate <= $Global_NextYear) {
					
					$InDateText = sprintf("%s - %04d", $GenericClass->GetMonthNameFromDate($TrasitingPlanet['start']), $GenericClass->GetYearFromDate($TrasitingPlanet['start']) );
					$OutDateText = sprintf("%s - %04d", $GenericClass->GetMonthNameFromDate($TrasitingPlanet['end']), $GenericClass->GetYearFromDate($TrasitingPlanet['end']) );
					
					if($IsStayInHouse == 'Y') {
						$LastP =  intval($PlanetN) - 1;
						if($LastP == 0)
							$LastP = 12;
						$PlanetNText = sprintf("%02d-%02d", $LastP, $PlanetN);
					}
					else {
						$PlanetN = sprintf("%02d-%02d",$PlanetN , $PlanetN);
						$PlanetNText = sprintf("%02d-%02d",$PlanetN , $PlanetN);
					}

					$FinalDescription = $GenericClass->GetSaturnANDJupiterHouseText($PlanetT, $PlanetN);

					$Description =  $FinalDescription['DescriptionText'];

					foreach($FinalDescription['FindingPararm'] as $Key => $Item) {
						if (preg_match("(SA_in)", $Item) || preg_match("(JU_in)", $Item) ) {
							//$Description = str_replace($Item, '#'. $InDateText .'#', $Description);
							$Description = str_replace($Item, $InDateText, $Description);
						}
						else if ( preg_match("(SA_out)", $Item) || preg_match("(JU_out)", $Item) ){
							//$Description = str_replace($Item, '#'. $OutDateText .'#', $Description);
							$Description = str_replace($Item, $OutDateText , $Description);
						}
					}

					//$FinalTitle = sprintf("#%s/T%s", $PlanetT, $PlanetNText);
					//$GenericClass->PrintSectionInternalTitle($this, $FinalTitle);
					$GenericClass->PrintSectionDescription($this, $Description);
					$IsMoving++;
				}
			}
		}

		if($IsMoving == 0){
			//Check for Saturn House Crossing
			foreach ($this->SaturnJupiterCrossing as $Key => $TrasitingPlanet) {
				$PlanetT =  $AbbrPlanetToFullName[$TrasitingPlanet['pt']];
				$AspectType = $Connector[$TrasitingPlanet['asp']];
				$PlanetN = sprintf("%02d",  $TrasitingPlanet['pn']);
				$InDate = $TrasitingPlanet['start'];	//2011-07-05	[YYYY-MM-DD]
				$OutDate = $TrasitingPlanet['end'];		//2012-05-23	[YYYY-MM-DD]				
				$PlanetNText = $PlanetN;
				$IsBack = $TrasitingPlanet['isretrograde'];
				$IsStayInHouse = $TrasitingPlanet['isstayinhouse'];
					
				if($IsBack == 'D' && $PlanetT == 'SA') {
					//if($OutDate <= $Global_NextYear) {
					if($InDate >= $Global_NextYear) {
						$PlanetN = sprintf("%02d",  $PlanetN - 1);
						if($PlanetN == 0) {
							$PlanetN = 12;
						}

						$OutDate = $InDate;
						$InDate = $this->GetEnteredDateforSaturn($PlanetN);						

						$InDateText = sprintf("%s - %04d", $GenericClass->GetMonthNameFromDate($InDate), $GenericClass->GetYearFromDate($InDate) );
						$OutDateText = sprintf("%s - %04d", $GenericClass->GetMonthNameFromDate($OutDate), $GenericClass->GetYearFromDate($OutDate) );
							
						$PlanetN = sprintf("%02d-%02d",$PlanetN , $PlanetN);
						$PlanetNText = sprintf("%02d-%02d",$PlanetN , $PlanetN);
							
							
						$FinalDescription = $GenericClass->GetSaturnANDJupiterHouseText($PlanetT, $PlanetN);
							
						$Description =  $FinalDescription['DescriptionText'];
							
						foreach($FinalDescription['FindingPararm'] as $Key => $Item) {
							if (preg_match("(SA_in)", $Item) || preg_match("(JU_in)", $Item) ) {
								//$Description = str_replace($Item, '#'. $InDateText .'#', $Description);
								$Description = str_replace($Item, $InDateText, $Description);
							}
							else if ( preg_match("(SA_out)", $Item) || preg_match("(JU_out)", $Item) ){
								//$Description = str_replace($Item, '#'. $OutDateText .'#', $Description);
								$Description = str_replace($Item, $OutDateText, $Description);
							}
						}
							
						//$FinalTitle = sprintf("#%s/T%s", $PlanetT, $PlanetNText);
						//$GenericClass->PrintSectionInternalTitle($this, $FinalTitle);
						$GenericClass->PrintSectionDescription($this, $Description);
						break;
					}
				}
			}
		}

		//Check for Jupiter House Crossing

		$HowMany = 0;
		foreach ($this->SaturnJupiterCrossing as $Key => $TrasitingPlanet) {
			$PlanetT =  $AbbrPlanetToFullName[$TrasitingPlanet['pt']];
			$AspectType = $Connector[$TrasitingPlanet['asp']];
			$PlanetN = sprintf("%02d", $TrasitingPlanet['pn']);
			$InDate = $TrasitingPlanet['start'];	//2011-07-05	[YYYY-MM-DD]
			$OutDate = $TrasitingPlanet['end'];		//2012-05-23	[YYYY-MM-DD]
			$PlanetNText = $PlanetN;
			$IsBack = $TrasitingPlanet['isretrograde'];
			$IsStayInHouse = $TrasitingPlanet['isstayinhouse'];			

			if($IsBack == 'D' &&  $PlanetT == 'JU') {
				//if($OutDate <= $Global_NextYear) {
				//if($OutDate >= $Global_CurrntYear) {				
					$InDateText = sprintf("%s - %04d", $GenericClass->GetMonthNameFromDate($TrasitingPlanet['start']), $GenericClass->GetYearFromDate($TrasitingPlanet['start']) );
					$OutDateText = sprintf("%s - %04d", $GenericClass->GetMonthNameFromDate($TrasitingPlanet['end']), $GenericClass->GetYearFromDate($TrasitingPlanet['end']) );
	
					$FinalDescription = $GenericClass->GetSaturnANDJupiterHouseText($PlanetT, $PlanetN);
	
					$Description =  $FinalDescription['DescriptionText'];
	
					foreach($FinalDescription['FindingPararm'] as $Key => $Item) {
						if (preg_match("(SA_in)", $Item) || preg_match("(JU_in)", $Item) ) {
							//$Description = str_replace($Item, '#'. $InDateText .'#', $Description);
							$Description = str_replace($Item, $InDateText, $Description);
						}
						else if ( preg_match("(SA_out)", $Item) || preg_match("(JU_out)", $Item) ) {
							//$Description = str_replace($Item, '#'. $OutDateText .'#', $Description);
							$Description = str_replace($Item, $OutDateText, $Description);
						}
					}
	
					//$FinalTitle = sprintf("#%s/T%s", $PlanetT, $PlanetNText);
					//$GenericClass->PrintSectionInternalTitle($this, $FinalTitle);
					$GenericClass->PrintSectionDescription($this, $Description);
					$HowMany++;
						
					if($HowMany > 1) {
						break;
					}
				//}
			}
		}
	}

	function GetEnteredDateforSaturn($PlanetN) {
		$OutDate = '';
		global $AbbrPlanetToFullName;
		
		reset($this->SaturnJupiterCrossing);
		foreach ($this->SaturnJupiterCrossing as $Key => $TrasitingPlanet) {
			$PlanetT =  $AbbrPlanetToFullName[$TrasitingPlanet['pt']];
			$PN = sprintf("%02d", $TrasitingPlanet['pn']);

			if($PlanetT == 'SA' && $PN == $PlanetN) {
				//$OutDate = $TrasitingPlanet['end'];		//2012-05-23	[YYYY-MM-DD]
				$OutDate = $TrasitingPlanet['start'];		//2012-05-23	[YYYY-MM-DD]				
				break;
			}
		}
		return $OutDate;
	}

	function LifePathSection() {
		echo '<pre>********************************************************************** LifePathSection()</pre>';

		$GenericHelper = new GenericHouseAndSignFinder();

		$PageNo = $this->PageNo();
		$GLOBALS['SectionPageNo'][$this->PageNo()] = 1;
		$this->AddPage();
		//$GenericHelper->SetNewSectionBG($this, $PageNo, 'LifePath');

		$GLOBALS['SectionHeadingColor'][0] = 151;
		$GLOBALS['SectionHeadingColor'][1] = 89;
		$GLOBALS['SectionHeadingColor'][2] = 184;
		$this->SectionHeadlines($GenericHelper, '1012', 1);	//For Life Path section

		$this->PrintSectionIntroduction($GenericHelper, '1012');	//Generic Introduction text of the Section

		/**
		 * @todo: Checking for Solar return AS on Cups. Create separate function to pull the text and print it
		*/
		$this->CheckingOnCups('1012');

		$this->PrintProgressedPlanetChangeSign('1012');					//Checking if any Planet Change the sign then printing relavant Text

		$this->PrintASandMCSignPosition($GenericHelper, '1012', '10001');			//Printing House and Sign Position text

		$this->PrintSolarReturnToRadixTransit($GenericHelper, '10001', '1012');		//Printing Solar Return to Radix Transit or Aspects

		$this->PrintASandMCHouseRuler($GenericHelper, '10001', '1012');				//Printing House Ruler Text and House and Sign Position

		$this->PrintSolarReturnToSR($GenericHelper, '10001', '1012');				//Printing Solar Return Internal aspects

		$this->PrintTransit($GenericHelper, '10001', '1012');						//Printing Transit

		$this->PrintProgressedAspect($GenericHelper, '10001', '1012');				//Printing Progression to Radix

		$this->GenericSummarySection($GenericHelper, '10001', '1012');				//Printing Generic Summary Section
	}

	function CareerSection() {
		echo '<pre>********************************************************************** CareerSection()</pre>';
		$GenericHelper = new GenericHouseAndSignFinder();

		$GLOBALS['SectionPageNo'][$this->PageNo()] = 2;
		$this->AddPage();

		$GLOBALS['SectionHeadingColor'][0] = 15;
		$GLOBALS['SectionHeadingColor'][1] = 61;
		$GLOBALS['SectionHeadingColor'][2] = 157;
		$this->SectionHeadlines($GenericHelper, '1013', 2);	//For Life Path section

		$this->PrintSectionIntroduction($GenericHelper, '1013');	//Generic Introduction text of the Section

		/**
		 * @todo: Checking for Solar return AS on Cups. Create separate function to pull the text and print it
		*/
		$this->CheckingOnCups('1013');

		$this->PrintProgressedPlanetChangeSign('1013');								//Checking if any Planet Change the sign then printing relavant Text

		$this->PrintASandMCSignPosition($GenericHelper, '1013', '10002');			//Printing House and Sign Position text

		$this->PrintSolarReturnToRadixTransit($GenericHelper, '10002', '1013');		//Printing Solar Return to Radix Transit or Aspects

		$this->PrintASandMCHouseRuler($GenericHelper, '10002', '1013');				//Printing House Ruler Text and House and Sign Position

		$this->PrintSolarReturnToSR($GenericHelper, '10002', '1013');				//Printing Solar Return Internal aspects

		$this->PrintTransit($GenericHelper, '10002', '1013');						//Printing Transit

		$this->PrintProgressedAspect($GenericHelper, '10002', '1013');				//Printing Progression to Radix

		$this->GenericSummarySection($GenericHelper, '10002', '1013');				//Printing Generic Summary Section
	}

	function Personaldevelopment() {
		echo '<pre>********************************************************************** Personaldevelopment()</pre>';
		$GenericHelper = new GenericHouseAndSignFinder();

		$GLOBALS['SectionPageNo'][$this->PageNo()] = 3;
		$this->AddPage();

		$this->SectionHeadlines($GenericHelper, '1000', 2);			//For Personal development section

		$this->PrintSectionIntroduction($GenericHelper, '1000');	//Generic Introduction text of the Section

		$this->PrintRadixSignAndHouseText($GenericHelper, '1000');	//Generict Setion to Print the Sign and House Section text

		$this->PrintPlanetHouserPositionInSR($GenericHelper, '1000');			//Print the Text of the House Position in Solar Return

		$this->PrintProgressedPlanetChangeSign('1000');					//Checking if any Planet Change the sign then printing relavant Text

		$this->PrintASandMCSignPosition($GenericHelper, '1000');	//Printing House and Sign Position text

		$this->PrintSolarReturnToRadixTransit($GenericHelper, '10003', '1000');		//Printing Solar Return to Radix Transit or Aspects

		$this->PrintASandMCHouseRuler($GenericHelper, '10003', '1000');				//Printing House Ruler Text and House and Sign Position

		$this->PrintSolarReturnToSR($GenericHelper, '10003', '1000');				//Printing Solar Return Internal aspects

		$this->PrintTransit($GenericHelper, '10003', '1000');						//Printing Transit

		$this->PrintProgressedAspect($GenericHelper, '10003', '1000');				//Printing Progression to Radix

		$this->GenericSummarySection($GenericHelper, '10003', '1000');				//Printing Generic Summary Section
	}

	function FeelingsAndSecurity() {
		echo '<pre>********************************************************************** FeelingsAndSecurity()</pre>';
		$GenericHelper = new GenericHouseAndSignFinder();

		$GLOBALS['SectionPageNo'][$this->PageNo()] = 4;
		$this->AddPage();

		$this->SectionHeadlines($GenericHelper, '1001', 4);			//For Personal development section

		$this->PrintSectionIntroduction($GenericHelper, '1001');	//Generic Introduction text of the Section

		$this->PrintRadixSignAndHouseText($GenericHelper, '1001');	//Generict Setion to Print the Sign and House Section text

		$this->PrintPlanetHouserPositionInSR($GenericHelper, '1001');			//Print the Text of the House Position in Solar Return

		$this->PrintSolarReturnToRadixTransit($GenericHelper, '10004', '1001');		//Printing Solar Return to Radix Transit or Aspects

		$this->PrintASandMCHouseRuler($GenericHelper, '10004', '1001');				//Printing House Ruler Text and House and Sign Position

		$this->PrintSolarReturnToSR($GenericHelper, '10004', '1001');				//Printing Solar Return Internal aspects

		$this->PrintProgressedPlanetChangeSign('1001');								//Checking if any Planet Change the sign then printing relavant Text

		$this->PrintProgressedAspect($GenericHelper, '10004', '1001');				//Printing Progression to Radix

		$this->PrintTransit($GenericHelper, '10004', '1001');						//Printing Transit

		$this->GenericSummarySection($GenericHelper, '10004', '1001');				//Printing Generic Summary Section
	}

	function MentalDevelopment(){
		echo '<pre>********************************************************************** MentalDevelopment()</pre>';
		$GenericHelper = new GenericHouseAndSignFinder();

		$GLOBALS['SectionPageNo'][$this->PageNo()] = 5;
		$this->AddPage();

		$this->SectionHeadlines($GenericHelper, '1002', 5);			//For Personal development section

		$this->PrintSectionIntroduction($GenericHelper, '1002');	//Generic Introduction text of the Section

		$this->PrintRadixSignAndHouseText($GenericHelper, '1002');	//Generict Setion to Print the Sign and House Section text

		$this->PrintPlanetHouserPositionInSR($GenericHelper, '1002');				//Print the Text of the House Position in Solar Return

		$this->PrintProgressedPlanetChangeSign('1002');					//Checking if any Planet Change the sign then printing relavant Text

		$this->PrintIsRetrogradeOrDirect('1002');					//This Function Print the Text for if any Planet goes Direct or Retrograde

		$this->PrintSolarReturnToRadixTransit($GenericHelper, '10005', '1002');		//Printing Solar Return to Radix Transit or Aspects

		$this->PrintSolarReturnToSR($GenericHelper, '10005', '1002');				//Printing Solar Return Internal aspects

		$this->PrintTransit($GenericHelper, '10005', '1002');						//Printing Transit

		$this->PrintProgressedAspect($GenericHelper, '10005', '1002');				//Printing Progression to Radix

		$this->GenericSummarySection($GenericHelper, '10005', '1002');				//Printing Generic Summary Section
	}

	function ValuesANDLoveANDRelationships() {
		echo '<pre>********************************************************************** ValuesANDLoveANDRelationships()</pre>';
		$GenericHelper = new GenericHouseAndSignFinder();

		$GLOBALS['SectionPageNo'][$this->PageNo()] = 6;
		$this->AddPage();

		$this->SectionHeadlines($GenericHelper, '1003', 6);			//For Personal development section

		$this->PrintSectionIntroduction($GenericHelper, '1003');	//Generic Introduction text of the Section

		$this->PrintRadixSignAndHouseText($GenericHelper, '1003');	//Generict Setion to Print the Sign and House Section text

		$this->PrintPlanetHouserPositionInSR($GenericHelper, '1003');				//Print the Text of the House Position in Solar Return

		$this->PrintProgressedPlanetChangeSign('1003');					//Checking if any Planet Change the sign then printing relavant Text

		$this->PrintIsRetrogradeOrDirect('1003');					//This Function Print the Text for if any Planet goes Direct or Retrograde

		$this->PrintSolarReturnToRadixTransit($GenericHelper, '10006', '1003');		//Printing Solar Return to Radix Transit or Aspects

		$this->PrintSolarReturnToSR($GenericHelper, '10006', '1003');				//Printing Solar Return Internal aspects

		$this->PrintTransit($GenericHelper, '10006', '1003');						//Printing Transit

		$this->PrintProgressedAspect($GenericHelper, '10006', '1003');				//Printing Progression to Radix

		$this->GenericSummarySection($GenericHelper, '10006', '1003');				//Printing Generic Summary Section
	}

	function WillpowerANDAssertion() {
		echo '<pre>********************************************************************** WillpowerANDAssertion()</pre>';
		$GenericHelper = new GenericHouseAndSignFinder();

		$GLOBALS['SectionPageNo'][$this->PageNo()] = 7;
		$this->AddPage();

		$this->SectionHeadlines($GenericHelper, '1004', 7);			//For Personal development section

		$this->PrintSectionIntroduction($GenericHelper, '1004');	//Generic Introduction text of the Section

		$this->PrintRadixSignAndHouseText($GenericHelper, '1004');	//Generict Setion to Print the Sign and House Section text

		$this->PrintPlanetHouserPositionInSR($GenericHelper, '1004');				//Print the Text of the House Position in Solar Return

		$this->PrintProgressedPlanetChangeSign('1004');					//Checking if any Planet Change the sign then printing relavant Text

		$this->PrintIsRetrogradeOrDirect('1004');					//This Function Print the Text for if any Planet goes Direct or Retrograde

		$this->PrintSolarReturnToRadixTransit($GenericHelper, '10007', '1004');		//Printing Solar Return to Radix Transit or Aspects

		$this->PrintSolarReturnToSR($GenericHelper, '10007', '1004');				//Printing Solar Return Internal aspects

		$this->PrintTransit($GenericHelper, '10007', '1004');						//Printing Transit

		$this->PrintProgressedAspect($GenericHelper, '10007', '1004');				//Printing Progression to Radix

		$this->GenericSummarySection($GenericHelper, '10007', '1004');				//Printing Generic Summary Section
	}

	function CollectiveTrends(){
		echo '<pre>********************************************************************** CollectiveTrends()</pre>';
		$GenericHelper = new GenericHouseAndSignFinder();

		$GLOBALS['SectionPageNo'][$this->PageNo()] = 8;
		$this->AddPage();

		$this->SectionHeadlinesForCollectiveSection($GenericHelper, '1005', 8);			//For Collective Trends section

		$this->PrintSectionIntroduction($GenericHelper, '1005');						//Generic Introduction text of the Section
		
		$this->PrintCollectiveSectionTransit('10008');						//Printing Transit
		
		//Printing Moon Phases
		//	@todo: Create function for Moon Phases
		$this->PrintMoonPhase('10008');

		$this->PrintNodeHouseCrossing('10008');								//Printing Node House Crossing
		
		$this->PrintCollectiveSectionSumary();								//Summary Section with Tester and Generict Text

		$GLOBALS['SectionPageNo'][$this->PageNo()] = 0;
	}

	/**
	 * Print Section Headlines
	 * @param $PrintSectionFor
	 */
	function SectionHeadlines($GenericHelper, $PrintSectionFor, $SectionNo) {
		echo '<pre>*********************************** SectionHeadlines()</pre>';
		global $AbbrPlanetToFullName;
		global $Connector;
		global $AspectAbbr;

		$AspectStrength = 'T';

		foreach ($this->SectionHeadingContents as $key => $trWin) {
			$pt = trim( $trWin['pt'] );

			if($PrintSectionFor == $pt)	{
				$asp = trim( $trWin['asp'] );
				$pn =  trim( $trWin['pn'] );
				$MainHeading = trim( $trWin['mainheading'] );
				$AspectType = trim( $trWin['aspecttype'] );
				$isPlanet = trim( $trWin['isplanet'] );
				$Maincontent = $trWin['content'] != '' ? trim( $trWin['content'] ) : $trWin['content'];

				$GenericHelper->PrintChapterHeader($this, $MainHeading);

				if(array_key_exists($AspectType, $AspectAbbr)) {
					$AspectStrength = $AspectAbbr[$AspectType];
				}
				else {
					$AspectStrength ='T';
				}

				if($AspectType == 'TR') {
					$ThemeTitle = '#' . $AbbrPlanetToFullName[$pt] . '/' . $AspectStrength . $Connector[$asp] .  $AbbrPlanetToFullName[$pn];
				}
				else {
					$ThemeTitle = '#' . $AbbrPlanetToFullName[$pt] . '/' . $AspectStrength . $Connector[$asp] .  $AbbrPlanetToFullName[$pn];
				}

				//$GenericHelper->PrintSectionInternalTitle($this, $ThemeTitle);
				$GenericHelper->PrintSectionDescription($this, $Maincontent);
				break;
			}
		}
	}

	/**
	 *
	 * @param GenericHouseAndSignFinder class Object $GenericHelper
	 * @param PlanetCode $PrintSectionFor
	 * @param Section No $SectionNo
	 */
	function SectionHeadlinesForCollectiveSection($GenericHelper, $PrintSectionFor, $SectionNo){
		global $AbbrPlanetToFullName;
		global $Connector;
		global $AspectAbbr;

		$AspectStrength = 'T';
		foreach ($this->SectionHeadingContents as $key => $trWin) {
			$pt = trim( $trWin['pt'] );

			if(in_array($pt, $this->CollectivePlanet)) {
				//				if($pt >= 1005 && $pt <= 1011) {
				$asp = trim( $trWin['asp'] );
				$pn =  trim( $trWin['pn'] );
				$MainHeading = trim( $trWin['mainheading'] );
				$AspectType = trim( $trWin['aspecttype'] );
				$isPlanet = trim( $trWin['isplanet'] );
				$Maincontent = $trWin['content'] != '' ? trim( $trWin['content'] ) : $trWin['content'];

				$GenericHelper->PrintChapterHeader($this, $MainHeading);

				if(array_key_exists($AspectType, $AspectAbbr)){
					$AspectStrength = $AspectAbbr[$AspectType];
				}
				else {
					$AspectStrength ='T';
				}

				if($AspectType == 'TR') {
					$ThemeTitle = '#' . $AbbrPlanetToFullName[$pt] . '/' . $AspectStrength . $Connector[$asp] .  $AbbrPlanetToFullName[$pn];
				}
				else {
					$ThemeTitle = '#' . $AbbrPlanetToFullName[$pt] . '/' . $AspectStrength . $Connector[$asp] .  $AbbrPlanetToFullName[$pn];
				}

				//$GenericHelper->PrintSectionInternalTitle($this, $ThemeTitle);
				$GenericHelper->PrintSectionDescription($this, $Maincontent);
				break;
				//				}
			}
		}
	}

	/**
	 * PrintSectionIntroduction() function will print the General introduction text for Each Section
	 * @param $GenericHelper
	 * @param $GetIntroductionForME
	 */
	function PrintSectionIntroduction($GenericHelper, $GetIntroductionForME){
		echo "<pre>*********************************** PrintSectionIntroduction()</pre>";
		global $AbbrPlanetToFullName;

		if(in_array($GetIntroductionForME, $this->CollectivePlanet)) {
			//$GenericHelper->PrintSectionInternalTitle($this, '#Outer_Planets_general');
			$GenericHelper->PrintSectionDescription($this, $GenericHelper->GetCollectiveSectionGeneral());
		}
		else
		{
			//$GenericHelper->PrintSectionInternalTitle($this, sprintf('#%s_general', $AbbrPlanetToFullName[$GetIntroductionForME]));
			$GenericHelper->PrintSectionDescription($this, $GenericHelper->GetSectionIntroductionText($GetIntroductionForME));
		}
	}


	/**
	 * @todo: on cups checking is not implemented
	 */
	function CheckingOnCups($CheckForME) {
		echo '<pre>*********************************** CheckingOnCups()</pre>';
		global $AbbrPlanetToFullName;
		global $UserAge;
		global $GenericText;
		global $Global_Language;
		global $AspectTypes;
		global $Connector;
		global $NameOfPlanets;
		global $AspectTypes;
		global $ChapterNameBasedOnPlanet;
		global $AnnotationText;
		global $global_Solar_HouseCups;

		$ChapterNo = $ChapterNameBasedOnPlanet[$CheckForME];
		$PlanetCode1 = $AbbrPlanetToFullName[$CheckForME];

		$SignCups = array();
		/* add the sign cusps */
		for ($cusp = 0; $cusp < 12; $cusp++) {
			$angle = fmod(($cusp + 1) * 30.0, 360.0);
			$SignCups[$cusp] = $angle;
		}

		$Degrees = 0;
		$SignNo = 0;
		if($CheckForME == '1012') {
			//$Degrees = fmod($global_Solar_HouseCups[0], 30.0);
			$Degrees = $global_Solar_HouseCups[0];
			$SignNo = intval($global_Solar_HouseCups[0] / 30.0);
		}
		if($CheckForME == '1013') {
			//$Degrees = fmod($global_Solar_HouseCups[9], 30.0);
			$Degrees = $global_Solar_HouseCups[9];
			$SignNo = intval($global_Solar_HouseCups[9] / 30.0);
		}

		if($Degrees > -1){
			$SignCupsDegree = $SignCups[$SignNo];
			$AfterCups = $SignCupsDegree + 3;
			$BeforCups = $SignCupsDegree - 3;
			//Below code demostrarte If and AS/MC is on 357 degree the we have to check between > 357 < 363			
			if($SignCupsDegree == 0){
				$BeforCups = 360.0 + $SignCupsDegree - 3;
				$AfterCups = 360.0 + $SignCupsDegree + 3;
			}
						
			$SignNo = intval($SignNo) + 101;
						 
			if(($Degrees >= $BeforCups && $Degrees <= $SignCupsDegree) || ($Degrees >= $SignCupsDegree && $Degrees <= $AfterCups)) {
				$MoveTo = intval($SignNo)  + 1;
				
				if($MoveTo == 113) {
					//$MoveTo = 112;
					$MoveTo = 101;
				}
				
				$PlanetCode2 = sprintf("%s-%s", $AbbrPlanetToFullName[sprintf("%04d", $SignNo)], $AbbrPlanetToFullName[sprintf("%04d", $MoveTo)]);
				$PlanetCodeForAnno =$AbbrPlanetToFullName[sprintf("%04d", $MoveTo)];
									
				$ReturnArray = $this->GenericHelper->GetTextFromDB($ChapterNo, $PlanetCode1, $PlanetCode2, 'CNG', 'SR-CNG');

				if(isset($ReturnArray[0]) && isset($ReturnArray[0]['description'])) {
					//$this->GenericHelper->PrintSectionInternalTitle($this, sprintf('#%s_sr_cusp', $PlanetCode1));
					$this->GenericHelper->PrintSectionDescription($this, $GenericText[$Global_Language][sprintf('%s_sr_cusp', $PlanetCode1)]);

					//$this->GenericHelper->PrintSectionInternalTitle($this, sprintf('#%s/sr%s', $PlanetCode1, $PlanetCode2));

					//Preparing Annotation Text
					$AnnotationTitle = sprintf("%s ", trim($AnnotationText[$Global_Language][$PlanetCode1]));
					$AnnotationTitle .= sprintf("%s ", isset ($AnnotationText[$Global_Language]["SOLARR"]) ? trim($AnnotationText[$Global_Language]["SOLARR"]) : '');
					$AnnotationTitle .= sprintf("%s ", trim($AnnotationText[$Global_Language]['>']));
					$AnnotationTitle .= sprintf("%s ", trim($AnnotationText[$Global_Language][$PlanetCodeForAnno]));
					$Link = $this->AddLink();
					array_push($this->AnnotationArray, array("Title" => $AnnotationTitle, 'link' => $Link));
					$this->Bookmark($AnnotationTitle);
					//Preparing Annotation Text

					$this->GenericHelper->PrintSectionDescription($this, $ReturnArray[0]['description'], count($this->AnnotationArray), $Link);

				}
			}
		}
	}

	function PrintProgressedPlanetChangeSign($CheckForME) {
		echo '<pre>*********************************** PrintProgressedPlanetChangeSign()</pre>';
		global $Global_Progression_TransitSortedList;
		global $AbbrPlanetToFullName;
		global $UserAge;
		global $GenericText;
		global $Global_Language;
		global $AspectTypes;
		global $Connector;
		global $NameOfPlanets;
		global $AspectTypes;
		global $ChapterNameBasedOnPlanet;
		global $Gender;
		global $Global_CurrntYear;
		global $Global_NextYear;

		$cNext = $Global_NextYear;
		$cYear = $Global_CurrntYear;

		if($CheckForME != '1001') {
			$cYear = $Global_CurrntYear;
			$newdate = strtotime ( '-6 month' , strtotime ( $cYear ) ) ;
			$newdate = date ( 'Y-m-d' , $newdate );
			$cYear = $newdate;

			$cNext = $Global_NextYear;
			$nextnewdate = strtotime ( '+6 month' , strtotime ( $cNext ) ) ;
			$nextnewdate = date ( 'Y-m-d' , $nextnewdate );
			$cNext = $nextnewdate;
		}

		$ChapterNo = $ChapterNameBasedOnPlanet[$CheckForME];
		$PlanetCode1 = $AbbrPlanetToFullName[$CheckForME];
			
		if(is_array($Global_Progression_TransitSortedList)) {
			$IsThereRow = 0;

			//First Check for Sign Changing than
			foreach($Global_Progression_TransitSortedList as $Key => $Item) {
				$PT = $Item['pt'];
				$ASP =$Item['asp'];
				$PN = $Item['pn'];
				$IngressDate = $Item['start'];  		//2008-10-23 [YYYY-MM-DD]
				$IsPlanet = $Item['isplanet'];
				$AspectType = $Item['aspecttype'];  	//PR
				$IsRatrograde = $Item['IsRatrograde'];  //PR

				if($CheckForME == $PT && $ASP == '-->' && ($IngressDate >= $cYear && $IngressDate <= $cNext)) {
					if(intval($PN) > 100 && intval($PN) < 1000 ) {
						$PlanetCode2 = $AbbrPlanetToFullName[$PN];

						if(intval($PN) == 101) {
							$PreviousPlCode = sprintf("%04d", 112);
						}
						else {
							$PreviousPlCode = sprintf("%04d", intval($PN) - 1);
						}

						$PlanetCode2 = sprintf("%s-%s", $AbbrPlanetToFullName[$PreviousPlCode], $AbbrPlanetToFullName[$PN]);
							
						$ReturnArray = $this->GenericHelper->GetTextFromDB($ChapterNo, $PlanetCode1, $PlanetCode2, 'CON', 'P');

						if( is_array($ReturnArray) && !isset($ReturnArray[0]['description']) ) {
							$ReturnArray = $this->GenericHelper->GetTextFromDB($ChapterNo, $PlanetCode1, $PlanetCode2, 'CNG', 'P-CNG');
						}

						if(count($ReturnArray) > 0 && $ReturnArray[0]['description'] != '') {
							$this->PrintDescriptionTexForPrograssionSignHouse($ReturnArray, $PlanetCode1, $PlanetCode2, $IngressDate);
						}
						$IsThereRow++;
					}
				}
			}

			if($IsThereRow == 0) {
				reset($Global_Progression_TransitSortedList);

				//If any planet did not cross house during the current year than display current year house and sign text
				foreach($Global_Progression_TransitSortedList as $Key => $Item) {
					if($IsThereRow > 0)
						break;

					$PT = $Item['pt'];
					$ASP =$Item['asp'];
					$PN = $Item['pn'];
					$IngressDate = $Item['start'];  //2008-10-23 [YYYY-MM-DD]
					$IsPlanet = $Item['isplanet'];
					$AspectType = $Item['aspecttype'];  //PR
					$IsRatrograde = $Item['IsRatrograde'];  //PR

					if($CheckForME == $PT && $ASP == '-->' && $IngressDate >= $cNext) {
						if(intval($PN) > 100 && intval($PN) < 1000) {
							if(intval($PN) == 101) {
								$PlanetCode2 = sprintf("%04d", 112);
							}
							else {								
								$PlanetCode2 = sprintf("%04d", intval($PN) - 1);
							}							
							//$PlanetCode2 = sprintf("%04d", intval($PN) - 1);
							$PlanetCode2 = $AbbrPlanetToFullName[$PlanetCode2];

							$PlanetCode2 = sprintf("%s-%s", $PlanetCode2, $PlanetCode2);
							$ReturnArray = $this->GenericHelper->GetTextFromDB($ChapterNo, $PlanetCode1, $PlanetCode2, 'CON', 'P');

							if( is_array($ReturnArray) && !isset($ReturnArray[0]['description']) ) {
								$ReturnArray = $this->GenericHelper->GetTextFromDB($ChapterNo, $PlanetCode1, $PlanetCode2, 'CNG', 'P-CNG');
							}

							if(count($ReturnArray) > 0 && $ReturnArray[0]['description'] != '') {
								$this->PrintDescriptionTexForPrograssionSignHouse($ReturnArray, $PlanetCode1, $PlanetCode2, $IngressDate);
							}							
							$IsThereRow++;
							break;
						}
					}
				}
			}

			$IsThereRow = 0;
			reset($Global_Progression_TransitSortedList);
			//If there House Crossing
			foreach($Global_Progression_TransitSortedList as $Key => $Item) {
				$PT = $Item['pt'];
				$ASP =$Item['asp'];
				$PN = $Item['pn'];
				$IngressDate = $Item['start'];  //2008-10-23 [YYYY-MM-DD]
				$IsPlanet = $Item['isplanet'];
				$AspectType = $Item['aspecttype'];  //PR
				$IsRatrograde = $Item['IsRatrograde'];  //PR

				if($CheckForME == $PT && $ASP == '-->' && ($IngressDate >= $cYear && $IngressDate <= $cNext)) {
					if(intval($PN) < 100) {
						if(intval($PN) == 1) {
							$PreviousPlCode = sprintf("%04d", 12);
						}
						else {
							$PreviousPlCode = sprintf("%04d", intval($PN) - 1);
						}
						$PlanetCode2 = sprintf("%02d-%02d", $PreviousPlCode, $PN);

						$ReturnArray = $this->GenericHelper->GetTextFromDB($ChapterNo, $PlanetCode1, $PlanetCode2, 'CON', 'P');

						if( is_array($ReturnArray) && !isset($ReturnArray[0]['description']) ) {
							$ReturnArray = $this->GenericHelper->GetTextFromDB($ChapterNo, $PlanetCode1, $PlanetCode2, 'CNG', 'P-CNG');
						}

						if(count($ReturnArray) > 0 && $ReturnArray[0]['description'] != '') {
							$this->PrintDescriptionTexForPrograssionSignHouse($ReturnArray, $PlanetCode1, $PlanetCode2, $IngressDate);
						}						
						$IsThereRow++;
					}
				}
			}

			////If there is no house changing than display current house text
			if($IsThereRow == 0) {
				reset($Global_Progression_TransitSortedList);

				//If any planet did not cross house during the current year than display current year house and sign text
				foreach($Global_Progression_TransitSortedList as $Key => $Item) {

					if($IsThereRow > 0)
						break;

					$PT = $Item['pt'];
					$ASP =$Item['asp'];
					$PN = $Item['pn'];
					$IngressDate = $Item['start'];  //2008-10-23 [YYYY-MM-DD]
					$IsPlanet = $Item['isplanet'];
					$AspectType = $Item['aspecttype'];  //PR
					$IsRatrograde = $Item['IsRatrograde'];  //PR

					if($CheckForME == $PT && $ASP == '-->' && $IngressDate >= $cNext ) {
						if(intval($PN) < 100) {
							if(intval($PN) == 1) {
								$PlanetCode2 = sprintf("%02d", 12);
							}
							else {
								$PlanetCode2 = sprintf("%02d", intval($PN) - 1);
							}
														
							$PlanetCode2 = sprintf("%s-%s", $PlanetCode2, $PlanetCode2);
							
							$ReturnArray = $this->GenericHelper->GetTextFromDB($ChapterNo, $PlanetCode1, $PlanetCode2, 'CON', 'P');

							if( is_array($ReturnArray) && !isset($ReturnArray[0]['description']) ) {
								$ReturnArray = $this->GenericHelper->GetTextFromDB($ChapterNo, $PlanetCode1, $PlanetCode2, 'CNG', 'P-CNG');
							}

							if(count($ReturnArray) > 0 && $ReturnArray[0]['description'] != '') {
								$this->PrintDescriptionTexForPrograssionSignHouse($ReturnArray, $PlanetCode1, $PlanetCode2, $IngressDate);
							}
							$IsThereRow++;
							break;
						}
					}
				}
			}
		}
	}

	function PrintDescriptionTexForPrograssionSignHouse($ReturnArray, $PlanetCode1, $PlanetCode2, $IngressDate) {
		global $Gender;
		$IngressDate = sprintf("%s %s", $this->GenericHelper->GetMonthNameFromDate($IngressDate), $this->GenericHelper->GetYearFromDate($IngressDate));
		//#NE/t-AS
		//$ThemeTitle = sprintf("#%s/p/%s", $PlanetCode1, $PlanetCode2);
		$ThemeTitle = "";

		$this->GenericHelper->PrintSectionInternalTitle($this, $ThemeTitle);

		$Description = $ReturnArray[0]['description'];
		$Description = str_replace('[ingress_year]', " $IngressDate ", $Description);
		$Description = str_replace('[ingress_date]', " $IngressDate ", $Description);
		$Description = str_replace('[ingress_month]', " $IngressDate ", $Description);
		
		//$Description = str_replace('[ingress_year]', "# $IngressDate #", $Description);
		//$Description = str_replace('[ingress_date]', "# $IngressDate #", $Description);
		//$Description = str_replace('[ingress_month]', "# $IngressDate #", $Description);

		if($Gender == "M") {
			$Description = preg_replace('/\[female](.*?)\[end]/','', $Description);
		}
		else {
			$Description = preg_replace('/\[male](.*?)\[end]/','', $Description);
		}

		$this->GenericHelper->PrintSectionDescription($this, $Description);
	}

	function PrintIsRetrogradeOrDirect($CheckForME) {
		echo '<pre>*********************************** PrintIsRetrogradeOrDirect()</pre>';
		global $Global_Prog_Direct_Retrograde_List;
		global $AbbrPlanetToFullName;
		global $UserAge;
		global $GenericText;
		global $AnnotationText;
		global $Global_Language;		
		global $AspectTypes;
		global $Connector;
		global $NameOfPlanets;
		global $AspectTypes;
		global $ChapterNameBasedOnPlanet;
		global $Gender;
		global $Global_PreviousYear;				//Trancking last year data  (like 16-July-2011)
		global $Global_NextYear;					//Trancking next year data  (like 16-July-2013)
		
		$CheckME_VE_MA = array('1002', '1003', '1004');

		$ChapterNo = $ChapterNameBasedOnPlanet[$CheckForME];
		$PlanetCode1 = $AbbrPlanetToFullName[$CheckForME];

		if(in_array($CheckForME, $CheckME_VE_MA)) {

			if(is_array($Global_Prog_Direct_Retrograde_List)) {
				foreach($Global_Prog_Direct_Retrograde_List as $Key => $Item) {
					$PT = $Item['pt'];

					if($PT == $CheckForME) {					
						
						$ASP = $Item['asp'];			// S/D = S/R
						$DRDate = $Item['start'];
						$PN = $Item['pn'];
						$PlanetDirection = 'D';
						
						if($DRDate >= $Global_PreviousYear && $DRDate <=  $Global_NextYear ) {
							echo "$PT = $PN [ $DRDate <=  $Global_PreviousYear = $Global_NextYear ]<br />";
							
							if($PN == "") {
								$ArraySignHouse = $this->GenericHelper->GetPrograssedPlanetSignAndHousePosition($PlanetCode1);
							}
							else {
								$ArraySignHouse['sign'] = $AbbrPlanetToFullName[$PN];
							}
							
							if(count($ArraySignHouse) > 0 && isset($ArraySignHouse['sign'])) {
								$PlanetCode2 = $ArraySignHouse['sign'];							
								if(strlen($ArraySignHouse['sign']) > 2) {
									$PlanetCode2 = $AbbrPlanetToFullName[$ArraySignHouse['sign']];
								}
	
								//#VE/p_AR_direct
								//$ThemeTitle = sprintf('#%s/p_%s_direct', $PlanetCode1, $PlanetCode2);
								$ThemeTitle = "";
	
								if($ASP == 'S/R') {
									$PlanetDirection = 'R';
									//$ThemeTitle = sprintf('#%s/p_%s_retro', $PlanetCode1, $PlanetCode2);
									$ThemeTitle = "";
								}
	
								$Description = $this->GenericHelper->GetPlanetDirectAndRetrogradeText($ChapterNo, $PlanetCode1, 'CON', $PlanetCode2, 'P', $PlanetDirection);
								if($Description == '' && !isset($Description)) {
									$Description = $this->GenericHelper->GetPlanetDirectAndRetrogradeText($ChapterNo, $PlanetCode1, 'CON', $PlanetCode2, 'P-R', $PlanetDirection);
								}						
								
								if($Description != "") {
									
									$DRDate = $this->GenericHelper->GetFullDateWithMonth($DRDate);
	
									$this->GenericHelper->PrintSectionInternalTitle($this, $ThemeTitle);
	
// 									$Description = str_replace('[st_direct_date]', "# $DRDate #", $Description);
// 									$Description = str_replace('[st_retro_date] ', "# $DRDate #", $Description);
									$Description = str_replace('[st_direct_date]', " $DRDate ", $Description);
									$Description = str_replace('[st_retro_date] ', " $DRDate ", $Description);
	
	
									//Preparing Annotation Text
									$AnnotationTitle = sprintf("%s ", trim($AnnotationText[$Global_Language][$PlanetCode1]));
									if($ASP == 'S/R') {
										$AnnotationTitle .= sprintf("%s ", isset ($AnnotationText[$Global_Language]["_retro_generic"]) ? trim($AnnotationText[$Global_Language]["_retro_generic"]) : '');
									}
									else {
										$AnnotationTitle .= sprintf("%s ", isset ($AnnotationText[$Global_Language]["_direct_generic"]) ? trim($AnnotationText[$Global_Language]["_direct_generic"]) : '');
									}							
									$AnnotationTitle .= sprintf("%s ", trim($AnnotationText[$Global_Language][$PlanetCode2]));
									
									$Link = $this->AddLink();
									array_push($this->AnnotationArray, array("Title" => $AnnotationTitle, 'link' => $Link));
									$this->Bookmark($AnnotationTitle);
									//Preparing Annotation Text
									
									$this->GenericHelper->PrintSectionDescription($this, $Description, count($this->AnnotationArray), $Link);								
								}
							}
						}
					}
				}
			}
		}
	}

	function GetSolarReturnHouseSignPosition($HouseNo) {
		global $global_Solar_HouseCups;
		$sign = 1;
		$HouseNo = $HouseNo - 1;
		for ($i = 0; $i < 12; $i++) {
			if($HouseNo == $i) {
				$sign = intval($global_Solar_HouseCups[$i] / 30.0);
				//	            $degrees = fmod($global_Solar_HouseCups[$i], 30.0);
				//	            $minutes = $degrees - intval($degrees);
				//	            $minutes = $minutes * 0.6;
				//	            $minutes = intval($minutes * 100);
				//	            $degree = sprintf("%02d,%02d", $degrees, $minutes);
				//	            echo $degree .'<br />';
				break;
			}
		}
		return sprintf('%04d', $sign + 101);
	}

	function PrintASandMCSignPosition($GenericHelper, $CheckForME, $ChapterNo = '10001') {
		echo '<pre>*********************************** PrintASandMCSignPosition()</pre>';
		global $AbbrPlanetToFullName;
		global $UserAge;
		global $GenericText;
		global $Global_Language;
		global $AnnotationText;
		global $Connector;

		reset($AbbrPlanetToFullName);
		reset($GenericText);

		$HouseNo = 1;
		if($CheckForME == '1013') {
			$HouseNo = 10;
		}
		$SignNo = $this->GetSolarReturnHouseSignPosition($HouseNo);

		$PlanetCode1 = $AbbrPlanetToFullName[trim($CheckForME)];
		$PlanetCode2 = $AbbrPlanetToFullName[trim($SignNo)];

		//$ChapterNo, $PlanetCode1, $PlanetCode2, $AspectType, $AspectIdentifier
		$ReturnArray = $GenericHelper->GetTextFromDB($ChapterNo, $PlanetCode1, $PlanetCode2, 'CON', 'SR-SR');

		if(count($ReturnArray) > 0) {				
			//Preparing Annotation Text
			$AnnotationTitle = sprintf("%s ", trim($AnnotationText[$Global_Language][$PlanetCode1]));
			$AnnotationTitle .= sprintf("%s ", isset ($AnnotationText[$Global_Language]["SOLARR"]) ? trim($AnnotationText[$Global_Language]["SOLARR"]) : '');
			$AnnotationTitle .= sprintf("%s ", trim($AnnotationText[$Global_Language][$Connector['000']]));
			$AnnotationTitle .= sprintf("%s ", trim($AnnotationText[$Global_Language][$PlanetCode2]));
			$Link = $this->AddLink();
			array_push($this->AnnotationArray, array("Title" => $AnnotationTitle, 'link' => $Link));
			$this->Bookmark($AnnotationTitle);
			//Preparing Annotation Text
				
//			$GenericHelper->PrintSectionInternalTitle($this,  sprintf('#%s/sr_%s', $PlanetCode1, $PlanetCode2));

			$Description = str_replace('[age]', sprintf($GenericText[$Global_Language]['[age]'], $UserAge), $ReturnArray[0]['description']);
			$GenericHelper->PrintSectionDescription($this,  $Description, count($this->AnnotationArray), $Link);
		}
	}

	/**
	 * Printing Solar Return or Horory to Radix aspects
	 * @param $GenericHelper
	 * @param $ChapterNo
	 * @param $CheckForME
	 */
	function PrintSolarReturnToRadixTransit($GenericHelper, $ChapterNo, $CheckForME) {
		echo '<pre>*********************************** PrintSolarReturnToRadixTransit()</pre>';

		global $AbbrPlanetToFullName;
		global $UserAge;
		global $GenericText;
		global $Global_Language;
		global $Global_Solar_TransitSortedList;
		global $AspectTypes;
		global $Connector;
		global $AnnotationText;

		reset($Global_Solar_TransitSortedList);

		$DisplayOnlyOne = 0;
		foreach ($Global_Solar_TransitSortedList as $Key => $Item){
			If( $Item['pt'] == $CheckForME ) {
				$PlanetCode1 = $AbbrPlanetToFullName[$CheckForME];
				$PlanetCode2 = $AbbrPlanetToFullName[$Item['pn']];
				$AspectCode  = $Item['asp'];
				$AspectType  = $AspectTypes[$Item['asp']];
				$IsPlanet = $Item['isplanet'];

				if($IsPlanet == 0) {
					$PlanetCode2 = sprintf('#%04d', $PlanetCode2);
				}
				//$ChapterNo, $PlanetCode1, $PlanetCode2, $AspectType, $AspectIdentifier
				$ReturnArray = $GenericHelper->GetTextFromDB($ChapterNo, $PlanetCode1, $PlanetCode2, $AspectType, 'SR-R');

				if(count($ReturnArray) > 0 && $ReturnArray[0]['description'] != '') {
					#AS/sr+VE/r
					//$ThemeTitle = sprintf('#%s/sr/%s/%s/r', $PlanetCode1, $Connector[$AspectCode], $PlanetCode2);
					$ThemeTitle = "";
					$GenericHelper->PrintSectionInternalTitle($this, $ThemeTitle);

//				$Description = str_replace('[age]', sprintf($GenericText[$Global_Language]['[age]'], $UserAge), $ReturnArray[0]['description']);

					//Preparing Annotation Text
					$AnnotationTitle = sprintf("%s ", trim($AnnotationText[$Global_Language][$PlanetCode1]));
					$AnnotationTitle .= sprintf("%s ", isset ($AnnotationText[$Global_Language]["SOLARR"]) ? trim($AnnotationText[$Global_Language]["SOLARR"]) : '');
					$AnnotationTitle .= sprintf("%s ", trim($AnnotationText[$Global_Language][$Connector[$AspectCode]]));
					$AnnotationTitle .= sprintf("%s ", trim($AnnotationText[$Global_Language][$PlanetCode2]));
					$AnnotationTitle .= sprintf("%s ", trim($AnnotationText[$Global_Language]["R"]));
					$Link = $this->AddLink();
					array_push($this->AnnotationArray, array("Title" => $AnnotationTitle, 'link' => $Link));
					$this->Bookmark($AnnotationTitle);
					//Preparing Annotation Text

					$Description = $ReturnArray[0]['description'];
					$GenericHelper->PrintSectionDescription($this, $Description, count($this->AnnotationArray), $Link);

					$DisplayOnlyOne = $DisplayOnlyOne + 1;
				}

				//We need to display only one aspect
				if( $DisplayOnlyOne > 0 )
					break;
			}
		}
	}

	/**
	 * Printing AS and MC House Ruler interpretation Text
	 * @param $GenericHelper
	 * @param $ChapterNo
	 * @param $CheckForME
	 */
	function PrintASandMCHouseRuler($GenericHelper, $ChapterNo, $CheckForME){
		echo '<pre>*********************************** PrintASandMCHouseRuler()</pre>';
		global $AbbrPlanetToFullName;
		global $UserAge;
		global $GenericText;
		global $Global_Language;
		global $AspectTypes;
		global $Connector;
		global $Global_Solar_MObject;
		global $global_Solar_HouseCups;
		global $NameOfPlanets;

		$RulerTextIdentifier = array("1000"	=>	'SU-LE',	//"Sun",
				"1001"	=>	'MO-CN',	//"Moon",
				"1002"	=>	'ME-GE',	//"Mercury",
				"1003"	=>	'VE-TA',	//"Venus",
				"1004"	=>	'MA-AR',	//"Mars",
				"1005"	=>	'JU-SG',	//"Jupiter",
				"1006"	=>	'SA-CP',	//"Saturn",
				"1007"	=>	'',			//"Uranus",
				"1008"	=>	'',			//"Neptune",
				"1009"	=>	'');		//"Pluto"

		$RularPlanet = '';
		$SingNo = '0000';
		if ( $CheckForME == '1012' ) {
			$RularPlanet = $this->SolarHouseCups[0];
			$SingNo = intval($global_Solar_HouseCups[0] / 30.0);
		}
		else if ( $CheckForME == '1013' ) {
			$RularPlanet = $this->SolarHouseCups[9];
			$SingNo = intval($global_Solar_HouseCups[9] / 30.0);
		}
		else {
			$RularPlanet = $this->SolarHouseCups[$CheckForME];
		}
		$SingNo = sprintf('%04d', intval($SingNo) + 101);

		$PlanetCode1 = $AbbrPlanetToFullName[$CheckForME];
		$PlanetCode2 = sprintf('%s-%s', $AbbrPlanetToFullName[$RularPlanet], $AbbrPlanetToFullName[$SingNo]);
		//$PlanetCode2 = $GenericHelper->GetRulerPlanetSignCode($RularPlanet, $global_Solar_HouseCups);

		//											$ChapterNo, $PlanetCode1, $PlanetCode2, $AspectType, $AspectIdentifier
		$ReturnArray = $GenericHelper->GetTextFromDB($ChapterNo, $PlanetCode1, $PlanetCode2, 'CON', 'SR-RL');

		if(count($ReturnArray) > 0 && $ReturnArray[0]['description'] != '') {
			#AS/sr_ruler_JU/SG
// 			$ThemeTitle = sprintf('#%s/sr_ruler_/%s', $PlanetCode1, $PlanetCode2);
// 			$GenericHelper->PrintSectionInternalTitle($this, $ThemeTitle);

			$Description = $ReturnArray[0]['description'];
			$GenericHelper->PrintSectionDescription($this, $Description);
		}

		unset($ReturnArray);
		$HouseNo =  $Global_Solar_MObject[$NameOfPlanets[$RularPlanet]]['house'];
		$SingNo = intval($Global_Solar_MObject[$NameOfPlanets[$RularPlanet]]['sign']) + 101;

		/** Fetching House and Sign Position of the Ruler and Printing it Text */
		//Fetching Sign Text
		$PlanetCode1 = $AbbrPlanetToFullName[$CheckForME];
		$PlanetCode2 =  $AbbrPlanetToFullName[sprintf('%04d',$SingNo)];

		$ReturnArray = $GenericHelper->GetTextFromDB($ChapterNo, $PlanetCode1, $PlanetCode2, 'CON', 'SR-SRL');

		if(count($ReturnArray) > 0 && $ReturnArray[0]['description'] != '') {
			#ASruler/sr_02
// 			$ThemeTitle = sprintf('#%s/ruler/sr_%s', $PlanetCode1, $PlanetCode2);
// 			$GenericHelper->PrintSectionInternalTitle($this, $ThemeTitle);

			$Description = $ReturnArray[0]['description'];
			$GenericHelper->PrintSectionDescription($this, $Description);
		}

		//Fetching House Text
		$PlanetCode1 = $AbbrPlanetToFullName[$CheckForME];
		$PlanetCode2 = sprintf('%02d', $HouseNo);

		//										$ChapterNo, $PlanetCode1, $PlanetCode2, $AspectType, $AspectIdentifier
		$ReturnArray = $GenericHelper->GetTextFromDB($ChapterNo, $PlanetCode1, $PlanetCode2, 'CON', 'SR-HRL');

		if(count($ReturnArray) > 0 && $ReturnArray[0]['description'] != '') {
			#ASruler/sr_PI
// 			$ThemeTitle = sprintf('#%s/sr_ruler_/%s', $PlanetCode1, $PlanetCode2);
// 			$GenericHelper->PrintSectionInternalTitle($this, $ThemeTitle);

			$Description = $ReturnArray[0]['description'];
			$GenericHelper->PrintSectionDescription($this, $Description);
		}
		/** Fetching House and Sign Position of the Ruler and Printing it Text */
	}

	/**
	 * Printing Interpretation for the Solar Return  or Horory internal aspects
	 * @param $GenericHelper
	 * @param $ChapterNo
	 * @param $CheckForME
	 */
	function PrintSolarReturnToSR($GenericHelper, $ChapterNo, $CheckForME) {
		echo '<pre>*********************************** PrintSolarReturnToSR()</pre>';
		global $AbbrPlanetToFullName;
		global $UserAge;
		global $GenericText;
		global $Global_Language;
		global $AspectTypes;
		global $Connector;
		global $Global_Solar_MObject;
		global $NameOfPlanets;
		global $AspectTypes;
		global $AnnotationText;

		$SolarReturnArray = array();
		//						Sun,    Moon,   Mercury, Venus, Jupiter N.Node, S.Node
		$PositiveArray = array('1000', '1001', '1002', '1003', '1005', '1010', '1011');
		//						Mars,  Saturn, Uranus, Neptune, Pluto or Chiron
		$NegativeArray = array('1004', '1006', '1007', '1008', '1009', '1010', '1016');

		reset($this->SectionHeadingContents);

		foreach ($this->SectionHeadingContents as $key => $trWin) {
			$Planet1 = trim( $trWin['pt'] );
			if($CheckForME == $Planet1) {
				if(is_array($trWin['srtosr'])) {
					$SolarReturnArray = $trWin['srtosr'];
				}
				break;
			}
		}
		$IsPositive = 0;

		if(is_array($SolarReturnArray)) {
			$AspectStrenght = 'SR-SR';

			foreach ($SolarReturnArray as $key => $Item) {
				$PT = trim( $Item['pt'] );
				$ASP = trim( $Item['asp'] );
				$PN =  trim( $Item['pn'] );
				$AspectType = trim( $Item['aspecttype'] );
				$ConnectorASP = $Connector[$ASP];

				if($ConnectorASP == "0") {
					$IsPositive = $IsPositive + 1;
						
					$PlanetCode1 = $AbbrPlanetToFullName[$CheckForME];
					$PlanetCode2 =  $AbbrPlanetToFullName[sprintf('%04d',$PN)];
					$AspectID = $AspectTypes[$ASP];
						
					//Fetching Sign Text
					if($PT == $CheckForME) {
						$AspectStrenght = 'SR-SR';
					} else {
						$AspectStrenght = 'SR-RL';

						$RularPlanet = '';
						if ( $CheckForME == '1012') {
							$RularPlanet = $this->SolarHouseCups[0];
						}
						if ( $CheckForME == '1013') {
							$RularPlanet = $this->SolarHouseCups[9];
						}
						if($PT == $RularPlanet){
							$PlanetCode2 = $AbbrPlanetToFullName[sprintf('%04d',$PN)];
						}
						else {
							$PlanetCode2 = $AbbrPlanetToFullName[$PT];
						}
					}
					$ReturnArray = $GenericHelper->GetTextFromDB($ChapterNo, $PlanetCode1, $PlanetCode2, $AspectID, $AspectStrenght);
						
					if(count($ReturnArray) > 0 && $ReturnArray[0]['description'] != '') {
						if($IsPositive == 1) {
							#AS/sr+NE/sr
// 							$GenericHelper->PrintSectionInternalTitle($this, "#Conjunctions");
								
							$ThemeTitle = $GenericText[$Global_Language]['Conjunctions'];
							$GenericHelper->PrintSectionDescription($this, $ThemeTitle);
							$ThemeTitle = '';
						}

						#AS/sr+NE/sr
						if($PT == $CheckForME) {
							$AspectStrenght = 'SR-SR';
							//$ThemeTitle = sprintf('#%s/sr%s%s/sr', $PlanetCode1, $Connector[$ASP], $PlanetCode2);
							$ThemeTitle = "";

							//Preparing Annotation Text
							$AnnotationTitle = sprintf("%s ", trim($AnnotationText[$Global_Language][$PlanetCode1]));
							$AnnotationTitle .= sprintf("%s ", isset ($AnnotationText[$Global_Language]["SOLARR"]) ? trim($AnnotationText[$Global_Language]["SOLARR"]) : '');
							$AnnotationTitle .= sprintf("%s ", trim($AnnotationText[$Global_Language][$Connector[$ASP]]));
							$AnnotationTitle .= sprintf("%s ", trim($AnnotationText[$Global_Language][$PlanetCode2]));
							$AnnotationTitle .= sprintf("%s ", trim($AnnotationText[$Global_Language]["SOLARR"]));
							//Preparing Annotation Text

						} else {
							$AspectStrenght = 'SR-RL';
							//$ThemeTitle = sprintf('#%s/ruler/sr%s%s/sr', $PlanetCode1, $Connector[$ASP], $PlanetCode2);
							$ThemeTitle = "";

							//Preparing Annotation Text
							$AnnotationTitle = sprintf("%s ", trim($AnnotationText[$Global_Language][$PlanetCode1]));
							$AnnotationTitle .= sprintf("%s ", isset ($AnnotationText[$Global_Language]["SOLARR"]) ? trim($AnnotationText[$Global_Language]["SOLARR"]) : '');
							$AnnotationTitle .= "ruler ";
							$AnnotationTitle .= sprintf("%s ", trim($AnnotationText[$Global_Language][$Connector[$ASP]]));
							$AnnotationTitle .= sprintf("%s ", trim($AnnotationText[$Global_Language][$PlanetCode2]));
							$AnnotationTitle .= sprintf("%s ", trim($AnnotationText[$Global_Language]["SOLARR"]));
							//Preparing Annotation Text
						}

						//Preparing Annotation Text
						$Link = $this->AddLink();
						array_push($this->AnnotationArray, array("Title" => $AnnotationTitle, 'link' => $Link));
						$this->Bookmark($AnnotationTitle);
						//Preparing Annotation Text

						$GenericHelper->PrintSectionInternalTitle($this, $ThemeTitle);

						$Description = $ReturnArray[0]['description'];

						if(array_key_exists(sprintf("SR-SR-%s%s%s", $PlanetCode1, $ASP, $PlanetCode2), $this->ProccessedArray))
						{
							//$Description = sprintf("Please read page %s",  $this->ProccessedArray[sprintf("SR-SR-%s%s%s", $PlanetCode1, $ASP, $PlanetCode2)]['page']);
							$Description = '';
						}

						$GenericHelper->PrintSectionDescription($this, $Description, count($this->AnnotationArray), $Link);

						//Priting House and Sign Text
						$this->SoralReturnAspectedPlanetHouseAndSignText($GenericHelper, $PlanetCode2);

						//Planet-Aspect-Planet
						$this->ProccessedArray[sprintf("SR-SR-%s%s%s", $PlanetCode1, $ASP, $PlanetCode2)] = array('page' => $this->PageNo(), 'title' => $ThemeTitle);
					}
				}
			}

			$IsPositive = 0;
			foreach ($SolarReturnArray as $key => $Item) {
				$PT = trim( $Item['pt'] );
				$ASP = trim( $Item['asp'] );
				$PN =  trim( $Item['pn'] );
				$AspectType = trim( $Item['aspecttype'] );
				$ConnectorASP = $Connector[$ASP];

				// 				if($ConnectorASP == '0') {
				// 					if(in_array($PT, $NegativeArray)){
				// 						$ConnectorASP = '-';
					// 					}
					// 				}

					if($ConnectorASP == "-") {
						$IsPositive = $IsPositive + 1;

						$PlanetCode1 = $AbbrPlanetToFullName[$CheckForME];
						$PlanetCode2 =  $AbbrPlanetToFullName[sprintf('%04d',$PN)];
						$AspectID = $AspectTypes[$ASP];

						IF($PT == $CheckForME) {
							$AspectStrenght = 'SR-SR';
						} else {
							$AspectStrenght = 'SR-RL';

							$RularPlanet = '';
							if ( $CheckForME == '1012') {
								$RularPlanet = $this->SolarHouseCups[0];
							}
							if ( $CheckForME == '1013') {
								$RularPlanet = $this->SolarHouseCups[9];
							}
							if($PT == $RularPlanet){
								$PlanetCode2 = $AbbrPlanetToFullName[sprintf('%04d',$PN)];
							}
							else {
								$PlanetCode2 = $AbbrPlanetToFullName[$PT];
							}
						}
						//Fetching Sign Text
						$ReturnArray = $GenericHelper->GetTextFromDB($ChapterNo, $PlanetCode1, $PlanetCode2, $AspectID, $AspectStrenght);

						if(count($ReturnArray) > 0 && $ReturnArray[0]['description'] != '') {
							if($IsPositive == 1) {
								#AS/sr+NE/sr
								//$GenericHelper->PrintSectionInternalTitle($this, "#Negative");

								$ThemeTitle = $GenericText[$Global_Language]['Negative'];
								$GenericHelper->PrintSectionDescription($this, $ThemeTitle);
								$ThemeTitle = '';
							}
							#AS/sr+NE/sr
							if($PT == $CheckForME) {
								$AspectStrenght = 'SR-SR';
								//$ThemeTitle = sprintf('#%s/sr%s%s/sr', $PlanetCode1, $Connector[$ASP], $PlanetCode2);
								$ThemeTitle = "";

								//Preparing Annotation Text
								$AnnotationTitle = sprintf("%s ", trim($AnnotationText[$Global_Language][$PlanetCode1]));
								$AnnotationTitle .= sprintf("%s ", isset ($AnnotationText[$Global_Language]["SOLARR"]) ? trim($AnnotationText[$Global_Language]["SOLARR"]) : '');
								$AnnotationTitle .= sprintf("%s ", trim($AnnotationText[$Global_Language][$Connector[$ASP]]));
								$AnnotationTitle .= sprintf("%s ", trim($AnnotationText[$Global_Language][$PlanetCode2]));
								$AnnotationTitle .= sprintf("%s ", trim($AnnotationText[$Global_Language]["SOLARR"]));
								//Preparing Annotation Text

							} else {
								//$ThemeTitle = sprintf('%s/ruler/sr%s%s/sr', $PlanetCode1, $Connector[$ASP], $PlanetCode2);
								$ThemeTitle = "";
								$AspectStrenght = 'SR-RL';

								//Preparing Annotation Text
								$AnnotationTitle = sprintf("%s ", trim($AnnotationText[$Global_Language][$PlanetCode1]));
								$AnnotationTitle .= sprintf("%s ", isset ($AnnotationText[$Global_Language]["SOLARR"]) ? trim($AnnotationText[$Global_Language]["SOLARR"]) : '');
								$AnnotationTitle .= "ruler ";
								$AnnotationTitle .= sprintf("%s ", trim($AnnotationText[$Global_Language][$Connector[$ASP]]));
								$AnnotationTitle .= sprintf("%s ", trim($AnnotationText[$Global_Language][$PlanetCode2]));
								$AnnotationTitle .= sprintf("%s ", trim($AnnotationText[$Global_Language]["SOLARR"]));
								//Preparing Annotation Text
							}

							//Preparing Annotation Text
							$Link = $this->AddLink();
							array_push($this->AnnotationArray, array("Title" => $AnnotationTitle, 'link' => $Link));
							$this->Bookmark($AnnotationTitle);
							//Preparing Annotation Text

							$GenericHelper->PrintSectionInternalTitle($this, $ThemeTitle);

							$Description = $ReturnArray[0]['description'];
							if(array_key_exists(sprintf("SR-SR-%s%s%s", $CheckForME, $ASP, $PN), $this->ProccessedArray))
							{
								//$Description = sprintf("Please read page %s",  $this->ProccessedArray[sprintf("SR-SR-%s%s%s", $CheckForME, $ASP, $PN)]['page']);
								$Description = "";
							}
							$GenericHelper->PrintSectionDescription($this, $Description, count($this->AnnotationArray), $Link);

							//Priting House and Sign Text
							$this->SoralReturnAspectedPlanetHouseAndSignText($GenericHelper, $PlanetCode2);

							//Planet-Aspect-Planet
							$this->ProccessedArray[sprintf("SR-SR-%s%s%s", $CheckForME, $ASP, $PN)] = array('page' => $this->PageNo(), 'title' => $ThemeTitle);
						}
					}
			}

			$IsPositive = 0;
			foreach ($SolarReturnArray as $key => $Item) {
				$PT = trim( $Item['pt'] );
				$ASP = trim( $Item['asp'] );
				$PN =  trim( $Item['pn'] );
				$AspectType = trim( $Item['aspecttype'] );
				$ConnectorASP = $Connector[$ASP];

				// 				if($ConnectorASP == '0') {
				// 					if(in_array($PT, $PositiveArray)){
				// 						$ConnectorASP = '+';
					// 					}
					// 				}

					if($ConnectorASP == "+") {
						$IsPositive = $IsPositive + 1;

						$PlanetCode1 = $AbbrPlanetToFullName[$CheckForME];
						$PlanetCode2 =  $AbbrPlanetToFullName[sprintf('%04d',$PN)];
						$AspectID = $AspectTypes[$ASP];

						//Fetching Sign Text
						if($PT == $CheckForME) {
							$AspectStrenght = 'SR-SR';
						} else {
							$AspectStrenght = 'SR-RL';
							$RularPlanet = '';
							if ( $CheckForME == '1012') {
								$RularPlanet = $this->SolarHouseCups[0];
							}
							if ( $CheckForME == '1013') {
								$RularPlanet = $this->SolarHouseCups[9];
							}
							if($PT == $RularPlanet){
								$PlanetCode2 = $AbbrPlanetToFullName[sprintf('%04d',$PN)];
							}
							else {
								$PlanetCode2 = $AbbrPlanetToFullName[$PT];
							}
						}

						$ReturnArray = $GenericHelper->GetTextFromDB($ChapterNo, $PlanetCode1, $PlanetCode2, $AspectID, $AspectStrenght);

						if(count($ReturnArray) > 0 && $ReturnArray[0]['description'] != '') {
							if($IsPositive == 1) {
								#AS/sr+NE/sr
								//$GenericHelper->PrintSectionInternalTitle($this, "#Positive");

								$ThemeTitle = $GenericText[$Global_Language]['Positive'];
								$GenericHelper->PrintSectionDescription($this, $ThemeTitle);
								$ThemeTitle = '';
							}

							#AS/sr+NE/sr
							if($PT == $CheckForME) {
								$AspectStrenght = 'SR-SR';
								//$ThemeTitle = sprintf('#%s/sr%s%s/sr', $PlanetCode1, $Connector[$ASP], $PlanetCode2);
								$ThemeTitle = "";
									
								//Preparing Annotation Text
								$AnnotationTitle = sprintf("%s ", trim($AnnotationText[$Global_Language][$PlanetCode1]));
								$AnnotationTitle .= sprintf("%s ", isset ($AnnotationText[$Global_Language]["SOLARR"]) ? trim($AnnotationText[$Global_Language]["SOLARR"]) : '');
								$AnnotationTitle .= sprintf("%s ", trim($AnnotationText[$Global_Language][$Connector[$ASP]]));
								$AnnotationTitle .= sprintf("%s ", trim($AnnotationText[$Global_Language][$PlanetCode2]));
								$AnnotationTitle .= sprintf("%s ", trim($AnnotationText[$Global_Language]["SOLARR"]));
								//Preparing Annotation Text
									
							} else {
								$AspectStrenght = 'SR-RL';
								//$ThemeTitle = sprintf('#%s/ruler/sr%s%s/sr', $PlanetCode1, $Connector[$ASP], $PlanetCode2);
								$ThemeTitle = "";
									
								//Preparing Annotation Text
								$AnnotationTitle = sprintf("%s ", trim($AnnotationText[$Global_Language][$PlanetCode1]));
								$AnnotationTitle .= sprintf("%s ", isset ($AnnotationText[$Global_Language]["SOLARR"]) ? trim($AnnotationText[$Global_Language]["SOLARR"]) : '');
								$AnnotationTitle .= "ruler ";
								$AnnotationTitle .= sprintf("%s ", trim($AnnotationText[$Global_Language][$Connector[$ASP]]));
								$AnnotationTitle .= sprintf("%s ", trim($AnnotationText[$Global_Language][$PlanetCode2]));
								$AnnotationTitle .= sprintf("%s ", trim($AnnotationText[$Global_Language]["SOLARR"]));
								//Preparing Annotation Text
							}

							//Preparing Annotation Text
							$Link = $this->AddLink();
							array_push($this->AnnotationArray, array("Title" => $AnnotationTitle, 'link' => $Link));
							$this->Bookmark($AnnotationTitle);
							//Preparing Annotation Text

							$GenericHelper->PrintSectionInternalTitle($this, $ThemeTitle);

							$Description = $ReturnArray[0]['description'];

							if(array_key_exists(sprintf("SR-SR-%s%s%s", $CheckForME, $ASP, $PN), $this->ProccessedArray))
							{
								//$Description = sprintf("Please read page %s",  $this->ProccessedArray[sprintf("SR-SR-%s%s%s", $CheckForME, $ASP, $PN)]['page']);
								$Description = "";
							}

							$GenericHelper->PrintSectionDescription($this, $Description, count($this->AnnotationArray), $Link);

							//Priting House and Sign Text
							$this->SoralReturnAspectedPlanetHouseAndSignText($GenericHelper, $PlanetCode2);

							//Planet-Aspect-Planet
							$this->ProccessedArray[sprintf("SR-SR-%s%s%s", $CheckForME, $ASP, $PN)] = array('page' => $this->PageNo(), 'title' => $ThemeTitle);
						}
					}
			}
		}
	}

	function SoralReturnAspectedPlanetHouseAndSignText($GenericHelper, $CheckForME) {
		echo '<pre>********************************************** SoralReturnAspectedPlanetHouseAndSignText()</pre>';
		global $AbbrPlanetToFullName;
		global $ChapterNameBasedOnPlanet;
		global $AbbrPlanetToCode;
		global $AnnotationText;
		global $Global_Language;
		global $UserAge;
		global $GenericText;

		$SignHouse = $GenericHelper->GetSolarReturnSignAndHousePosition($CheckForME);

		$HouseNo =  $SignHouse['house'];
		$SignAbbr = $SignHouse['sign'];
		$ChapterNo = $ChapterNameBasedOnPlanet[$AbbrPlanetToCode[$CheckForME]];

		//Placing House Text
		$ReturnArray = $GenericHelper->GetTextFromDB($ChapterNo, $CheckForME, $HouseNo, 'CON', 'SR-SRA');

		//if(count($ReturnArray) > 0 && $ReturnArray[0]['description'] == '') {
		if(!isset($ReturnArray[0]) && !isset($ReturnArray[0]['description'])) {
			unset($ReturnArray);
			$ReturnArray = $GenericHelper->GetTextFromDB($ChapterNo, $CheckForME, $HouseNo, 'CON', 'SR-SR');
		}

		if(count($ReturnArray) > 0 && $ReturnArray[0]['description'] != '') {
			//$ThemeTitle = sprintf('#%s/sr_%s/', $CheckForME, $HouseNo);
			$ThemeTitle = "";
			$Description = $ReturnArray[0]['description'];
				
			$Description = str_replace('[age]', sprintf($GenericText[$Global_Language]['[age]'], $UserAge), $Description);
				
			$Link = '';
			$LinkNumber = 0;
			if(array_key_exists(sprintf("SR-SR-%s000%s", $CheckForME, $HouseNo), $this->ProccessedArray))
			{
				//$Description = sprintf("Please read page %s",  $this->ProccessedArray[sprintf("SR-SR-%s000%s", $CheckForME, $HouseNo)]['page']);
				$Description = "";
				$LinkNumber = intval(array_search(sprintf("SR-SR-%s000%s", $CheckForME, $HouseNo), array_keys($this->ProccessedArray))) + 1;
			}
			else {
				//Preparing Annotation Text
				$AnnotationTitle = sprintf("%s ", isset ($AnnotationText[$Global_Language]["SOLARR_B"]) ? trim($AnnotationText[$Global_Language]["SOLARR_B"]) : '');
				$AnnotationTitle .= sprintf("%s ", trim($AnnotationText[$Global_Language][$CheckForME]));
				$AnnotationTitle .= sprintf("%s ", trim($AnnotationText[$Global_Language][$HouseNo]));
				//Preparing Annotation Text

				//Preparing Annotation Text
				$Link = $this->AddLink();
				array_push($this->AnnotationArray, array("Title" => $AnnotationTitle, 'link' => $Link));
				$this->Bookmark($AnnotationTitle);
				$LinkNumber = count($this->AnnotationArray);
				//Preparing Annotation Text
			}

			$GenericHelper->PrintSectionInternalTitle($this, $ThemeTitle);
			$GenericHelper->PrintSectionDescription($this, $Description, $LinkNumber, $Link);

			$this->ProccessedArray[sprintf("SR-SR-%s000%s", $CheckForME, $HouseNo)] = array('page' => $this->PageNo(), 'title' => $ThemeTitle);
		}

		// 		if(count($ReturnArray) > 0 && $ReturnArray[0]['description'] != '') {
		// 			$ThemeTitle = sprintf('#%s/sr_%s/', $CheckForME, $HouseNo);
		// 			$Description = $ReturnArray[0]['description'];

		// 			$GenericHelper->PrintSectionInternalTitle($this, $ThemeTitle);
		// 			$GenericHelper->PrintSectionDescription($this, $Description);

		// 			array_push($this->ProccessedArray[sprintf("SR-SR-%s000%s", $CheckForME, $HouseNo)], array('page' => $this->PageNo()));
		// 		}
		// 		else {
		// 			unset($ReturnArray);
		// 			$ReturnArray = $GenericHelper->GetTextFromDB($ChapterNo, $CheckForME, $HouseNo, 'CON', 'SR-SR');
		// 			if(count($ReturnArray) > 0 && $ReturnArray[0]['description'] != '') {

		// 				$ThemeTitle = sprintf('#%s/sr_%s/', $CheckForME, $HouseNo);
		// 				$Description = $ReturnArray[0]['description'];

		// 				$GenericHelper->PrintSectionInternalTitle($this, $ThemeTitle);
		// 				$GenericHelper->PrintSectionDescription($this, $Description);
		// 			}
		// 		}

		//PRINT Sign Text
		// 		unset($ReturnArray);
		// 		$ReturnArray = $GenericHelper->GetTextFromDB($ChapterNo, $CheckForME, $SignAbbr, 'CON', 'SR-SRA');

		// 		if(count($ReturnArray) > 0 && $ReturnArray[0]['description'] == '') {
		// 			unset($ReturnArray);
		// 			$ReturnArray = $GenericHelper->GetTextFromDB($ChapterNo, $CheckForME, $SignAbbr, 'CON', 'SR-SR');
		// 		}

		// 		if(count($ReturnArray) > 0 && $ReturnArray[0]['description'] != '') {
		// 			$ThemeTitle = sprintf('#%s/sr_%s/', $CheckForME, $SignAbbr);
		// 			$Description = $ReturnArray[0]['description'];

		// 			$GenericHelper->PrintSectionInternalTitle($this, $ThemeTitle);

		// 			if(array_key_exists(sprintf("SR-SR-%s000%s", $CheckForME, $SignAbbr), $this->ProccessedArray))
		// 			{
		// 				$Description = sprintf("Please read page %s",  $this->ProccessedArray[sprintf("SR-SR-%s000%s", $CheckForME, $HouseNo)]['page']);
			// 			}

			// 			$GenericHelper->PrintSectionDescription($this, $Description);
			// 		}
		}

		function SoralReturnMarSignText($CheckForME) {
			echo '<pre>********************************************** SoralReturnMarSignText()</pre>';
			global $AbbrPlanetToFullName;
			global $ChapterNameBasedOnPlanet;
			global $AbbrPlanetToCode;
			global $AnnotationText;
			global $Global_Language;

			$PlanetCode1 = $AbbrPlanetToFullName[$CheckForME];
			$SignHouse = $this->GenericHelper->GetSolarReturnSignAndHousePosition($PlanetCode1);

			$HouseNo =  $SignHouse['house'];
			$SignAbbr = $SignHouse['sign'];
			$ChapterNo = $ChapterNameBasedOnPlanet[$AbbrPlanetToCode[$PlanetCode1]];

			//Placing House Text
			$ReturnArray = $this->GenericHelper->GetTextFromDB($ChapterNo, $PlanetCode1, $SignAbbr, 'CON', 'SR-SRA');

			//if(count($ReturnArray) > 0 && $ReturnArray[0]['description'] == '') {
			if(!isset($ReturnArray[0]) && !isset($ReturnArray[0]['description'])) {
				unset($ReturnArray);
				$ReturnArray = $this->GenericHelper->GetTextFromDB($ChapterNo, $PlanetCode1, $SignAbbr, 'CON', 'SR-SR');
			}

			if(count($ReturnArray) > 0 && $ReturnArray[0]['short_text'] != '') {
				//$ThemeTitle = sprintf('#%s/sr_%s/_short', $PlanetCode1, $SignAbbr);
				$ThemeTitle = "";
				$Description = $ReturnArray[0]['short_text'];

				$Link = '';
				$ProccessedKey = array_keys($this->ProccessedArray);
				
				if(array_key_exists(sprintf("SR-SR-short-%s000%s", $PlanetCode1, $SignAbbr), $ProccessedKey))
				{
					//$Description = sprintf("Please read page %s",  $this->ProccessedArray[sprintf("SR-SR-short-%s000%s", $PlanetCode1, $HouseNo)]['page']);
					$Description = "";
				}
				else {
					//Preparing Annotation Text
					$AnnotationTitle = sprintf("%s ", isset ($AnnotationText[$Global_Language]["SOLARR_B"]) ? trim($AnnotationText[$Global_Language]["SOLARR_B"]) : '');
					$AnnotationTitle .= sprintf("%s ", trim($AnnotationText[$Global_Language][$PlanetCode1]));
					$AnnotationTitle .= sprintf("%s ", trim($AnnotationText[$Global_Language][$HouseNo]));
					//Preparing Annotation Text

					//Preparing Annotation Text
					$Link = $this->AddLink();
					array_push($this->AnnotationArray, array("Title" => $AnnotationTitle, 'link' => $Link));
					$this->Bookmark($AnnotationTitle);
					//Preparing Annotation Text
				}

				$this->GenericHelper->PrintSectionInternalTitle($this, $ThemeTitle);
				$this->GenericHelper->PrintSectionDescription($this, $Description, count($this->AnnotationArray), $Link);

				$this->ProccessedArray[sprintf("SR-SR-short-%s000%s", $PlanetCode1, $HouseNo)] = array('page' => $this->PageNo(), 'title' => $ThemeTitle);
			}
		}

		/**
		 *
		 * @param Helper Class Object $GenericHelper
		 * @param Chapter code $ChapterNo
		 * @param Questioning Planet $CheckForME
		 */
		function PrintTransit($GenericHelper, $ChapterNo, $CheckForME) {
			echo '<pre>*********************************** PrintTransit()</pre>';
			global $AbbrPlanetToFullName;
			global $UserAge;
			global $GenericText;
			global $Global_Language;
			global $AspectTypes;
			global $Connector;
			global $Global_Natal_TransitSortedList;
			global $NameOfPlanets;
			global $AspectTypes;

			$GraphParam = array();

			reset($AbbrPlanetToFullName);
			$PlanetCode1 = $AbbrPlanetToFullName[$CheckForME];
			$IndexTrack = 0;
			reset($Global_Natal_TransitSortedList);

			foreach($Global_Natal_TransitSortedList as $Key => $Item) {
				$Planet = $Item['pt'];
				$ASP = trim( $Item['asp'] );
				$PN =  trim( $Item['pn'] );
				$AspectType = $AspectTypes[$ASP];
				$IsPlanet = trim( $Item['isplanet'] );

				if($IsPlanet == 1 && $Planet != $PN) {
					reset($AbbrPlanetToFullName);
					$PlanetCode2 = $AbbrPlanetToFullName[$PN];
					$StartDate = trim( $Item['start'] );
					$EndDate = trim( $Item['end'] );
					$GraphParam["xaxis"] = $Item['xaxis'];
					$GraphParam["yaxis"] = $Item['yaxis'];
					$GraphParam["maxorb"] = $Item['maxorb'];
					$GraphParam["minorb"] = $Item['minorb'];
					
					/** @todo CHECK for Date HERE */
					$StartDate = ($StartDate != '' ? sprintf("%s %s", $GenericHelper->GetMonthNameFromDate($StartDate), $GenericHelper->GetYearFromDate($StartDate)) : '');
					$EndDate = ($EndDate != '' ? sprintf("%s %s", $GenericHelper->GetMonthNameFromDate($EndDate), $GenericHelper->GetYearFromDate($EndDate)) : '');

// 					if($CheckForME == '1000' && $Planet == '1005' && $PN == '1000'){
// 						echo "<pre>$Planet = $PN =  $StartDate : $EndDate [".$Item['start'] ." =" . $Item['end']."]";
// 						echo "</pre>";
// 					}
					
					$FinalReplaceDate = sprintf('%s %s %s %s', $GenericText[$Global_Language]['FromConnector'], $StartDate, $GenericText[$Global_Language]['TOConnector'], $EndDate);
					
					if(($Planet == $CheckForME || $PN == $CheckForME) ||
							($CheckForME == '1001' && $PN == '1014' && $ASP == '000') ||		//Transit to IC comes in Moon Section
							($CheckForME == '1003' && $PN == '1015' && $ASP == '000')			
							) {		//Transit to DC comes in Venus Section
						if($IndexTrack == 0) {
							//Print Generic Transit Script
							if(array_key_exists(sprintf("Transits_%s", $PlanetCode1) , $GenericText[$Global_Language])) {
								$this->Ln();
								//$GenericHelper->PrintSectionInternalTitle($this, sprintf("Transits_%s", $PlanetCode1));
								$GenericHelper->PrintSectionDescription($this, $GenericText[$Global_Language][sprintf("Transits_%s", $PlanetCode1)]);
								$this->Ln();
							}
						}
						$IndexTrack++;
					}
					
					
					if($Planet == $CheckForME) {
						$ChapterNo = $GLOBALS["ChapterNameBasedOnPlanet"][$CheckForME];
						$this->SetGenericTransitText($GenericHelper, $ChapterNo, $PlanetCode1, $PlanetCode2, $AspectType, $FinalReplaceDate, $GraphParam, true);
					}
					else if ($PN == $CheckForME) {						
						$PlanetCode1 = $AbbrPlanetToFullName[$Planet];
						$PlanetCode2 = $AbbrPlanetToFullName[$CheckForME];								
						$ChapterNo = $GLOBALS["ChapterNameBasedOnPlanet"][$Planet];
						$this->SetGenericTransitText($GenericHelper, $ChapterNo, $PlanetCode1, $PlanetCode2, $AspectType, $FinalReplaceDate, $GraphParam, true);
					}
						
					if($CheckForME == '1001' && $PN == '1014' && $ASP == '000') {
						$ChapterNo = $GLOBALS["ChapterNameBasedOnPlanet"][$CheckForME];
						$PlanetCode1 = $AbbrPlanetToFullName[$Planet];
						$PlanetCode2 = $AbbrPlanetToFullName[$PN];

						$ChapterNo = $GLOBALS["ChapterNameBasedOnPlanet"][$Planet];
						$this->SetGenericTransitText($GenericHelper, $ChapterNo, $PlanetCode1, $PlanetCode2, $AspectType, $FinalReplaceDate, $GraphParam, true);
					}
						
					if($CheckForME == '1003' && $PN == '1015' && $ASP == '000') {
						$ChapterNo = $GLOBALS["ChapterNameBasedOnPlanet"][$Planet];
						$PlanetCode1 = $AbbrPlanetToFullName[$Planet];
						$PlanetCode2 = $AbbrPlanetToFullName[$PN];
							
						$ChapterNo = $GLOBALS["ChapterNameBasedOnPlanet"][$Planet];
						$this->SetGenericTransitText($GenericHelper, $ChapterNo, $PlanetCode1, $PlanetCode2, $AspectType, $FinalReplaceDate, $GraphParam, true);
					}
				}
					
				unset($PlanetCode2);
				unset($Planet);
				unset($ASP);
				unset($PN);
				unset($AspectType);
				unset($IsPlanet);
				unset($StartDate);
				unset($EndDate);
			}
		}

		/**
		 * PrintCollectiveSectionTransit() function is design only for Collevtive trasit
		 * @param Chapter CODE $ChapterNo
		 */
		function PrintCollectiveSectionTransit($ChapterNo) {
			echo '<pre>*********************************** PrintCollectiveSectionTransit()</pre>';
			global $AbbrPlanetToFullName;
			global $UserAge;
			global $GenericText;
			global $Global_Language;
			global $AspectTypes;
			global $Connector;
			global $Global_Natal_TransitSortedList;
			global $NameOfPlanets;
			global $AspectTypes;
			$GraphParam = array();
			
			$TrackIndex = 1;

			//foreach($Global_Natal_TransitSortedList as $Key => $Item) {
			foreach($this->CollectiveArray as $Key => $Item) {
				$Planet = $Item['pt'];
				$ASP = trim( $Item['asp'] );
				$PN =  trim( $Item['pn'] );
				$AspectType = $AspectTypes[$ASP];
				$IsPlanet = trim( $Item['isplanet'] );

				if($IsPlanet == 1 && in_array($Planet, $this->CollectivePlanet) && in_array($PN, $this->CollectivePlanet)) {
					$PlanetCode1 = $AbbrPlanetToFullName[$Planet];
					$PlanetCode2 = $AbbrPlanetToFullName[$PN];
					$ChapterNo = $GLOBALS["ChapterNameBasedOnPlanet"][$Planet];
					
					$GraphParam["xaxis"] = $Item['xaxis'];
					$GraphParam["yaxis"] = $Item['yaxis'];
					$GraphParam["maxorb"] = $Item['maxorb'];
					$GraphParam["minorb"] = $Item['minorb'];

					$StartDate = trim( $Item['start'] );
					$EndDate = trim( $Item['end'] );

					//$StartDate = ($StartDate != '' ? $this->GenericHelper->GetFullDateWithMonth($StartDate) : '');
					//$EndDate = ($EndDate != '' ? $this->GenericHelper->GetFullDateWithMonth($EndDate) : '');

					$StartDate = ($StartDate != '' ? sprintf("%s %s", $this->GenericHelper->GetMonthNameFromDate($StartDate), $this->GenericHelper->GetYearFromDate($StartDate)) : '');
					$EndDate = ($EndDate != '' ? sprintf("%s %s", $this->GenericHelper->GetMonthNameFromDate($EndDate), $this->GenericHelper->GetYearFromDate($EndDate)) : '');

					$FinalReplaceDate = sprintf('%s %s %s',$StartDate, $GenericText[$Global_Language]['TOConnector'], $EndDate);
					
					$this->SetGenericTransitText($this->GenericHelper, $ChapterNo, $PlanetCode1, $PlanetCode2, $AspectType, $FinalReplaceDate, $GraphParam, true);
					$TrackIndex++;

					if($TrackIndex > 3) {
						break;
					}
				}
			}
		}

	function PrintNodeHouseCrossing($ChapterNo){
		echo '<pre>*********************************** PrintNodeHouseCrossing()</pre>';
		global $AbbrPlanetToFullName;
		global $UserAge;
		global $GenericText;
		global $Global_Language;
		global $AspectTypes;
		global $Connector;
		global $Global_Node_m_transit;
		global $NameOfPlanets;
		global $AspectTypes;
		global $Global_Natal_m_crossing;
		global $Global_NextYear;					//Trancking next year data  (like 16-July-2013)
		global $Global_CurrntYear;					//Trancking current year data  -Date of birth  (like 16-July-2012)

		$IndexTrack = 0;
		$GraphArray = array();
		
		foreach($Global_Node_m_transit as $Key => $Item) {
			$Planet = $Item['pt'];
			$ASP = trim( $Item['asp'] );
			$PN =  trim( $Item['pn'] );
			$AspectType = $AspectTypes[$ASP];
			$IsPlanet = trim( $Item['isplanet'] );
			$StartDate = trim( $Item['hitdate'] );

			if($Planet == '1010' && intval($PN) <= 100) {
				if($StartDate >= $Global_CurrntYear && $StartDate <= $Global_NextYear ) {
					if($IndexTrack == 0) {
						//Print Title
						//$this->GenericHelper->PrintSectionInternalTitle($this, "Transiting_Nodes");
						//Print Description
						$this->GenericHelper->PrintSectionDescription($this, $GenericText[$Global_Language]["Transiting_Nodes"]);
						$IndexTrack++;
					}
					

					$PlanetCode1 = $AbbrPlanetToFullName[$Planet];
					$PlanetCode2 = sprintf("%02d", $PN);
					$ChapterNo = $GLOBALS["ChapterNameBasedOnPlanet"][$Planet];

					$StartDate = ($StartDate != '' ? $this->GenericHelper->GetFullDateWithMonth($StartDate) : '');

					$FinalReplaceDate = sprintf('%s %s',$StartDate, $GenericText[$Global_Language]['TOConnector']);
					$this->SetGenericTransitText($this->GenericHelper, $ChapterNo, $PlanetCode1, $PlanetCode2, $AspectType, $FinalReplaceDate, $GraphArray, true);
				}
			}
		}

		//House crossing is not happened in year
		if($IndexTrack == 0) {
			foreach($Global_Node_m_transit as $Key => $Item) {
				$Planet = $Item['pt'];
				$ASP = trim( $Item['asp'] );
				$PN =  trim( $Item['pn'] );
				$AspectType = $AspectTypes[$ASP];
				$IsPlanet = trim( $Item['isplanet'] );
				$StartDate = trim( $Item['hitdate'] );
					
				if(intval($PN) == 1) {
					$PN = 12;
				}
				else {
					$PN = intval($PN) - 1;
				}
					
				if($Planet == '1010' && intval($PN) <= 100) {
					if($StartDate >= $Global_NextYear ) {
						if($IndexTrack == 0) {
							//Print Title
							//$this->GenericHelper->PrintSectionInternalTitle($this, "Transiting_Nodes");
							//Print Description
							$this->GenericHelper->PrintSectionDescription($this, $GenericText[$Global_Language]["Transiting_Nodes"]);
							$IndexTrack++;
						}

						$PlanetCode1 = $AbbrPlanetToFullName[$Planet];
						$PlanetCode2 = sprintf("%02d", $PN);
						$ChapterNo = $GLOBALS["ChapterNameBasedOnPlanet"][$Planet];

						$StartDate = ($StartDate != '' ? $this->GenericHelper->GetFullDateWithMonth($StartDate) : '');

						$FinalReplaceDate = sprintf('%s %s',$StartDate, $GenericText[$Global_Language]['TOConnector']);
						$this->SetGenericTransitText($this->GenericHelper, $ChapterNo, $PlanetCode1, $PlanetCode2, $AspectType, $FinalReplaceDate, $GraphArray, true);
					}
				}
				if($IndexTrack > 0) {
					break;
				}
			}
		}
	}

		//Its not catching the Transiting Node through the Houses that's why I have commented this code and created new function
		// function PrintNodeHouseCrossing($ChapterNo){
		// 	echo '<pre>*********************************** PrintNodeHouseCrossing()</pre>';
		// 	global $AbbrPlanetToFullName;
		// 	global $UserAge;
		// 	global $GenericText;
		// 	global $Global_Language;
		// 	global $AspectTypes;
		// 	global $Connector;
		// 	global $Global_Natal_TransitSortedList;
		// 	global $NameOfPlanets;
		// 	global $AspectTypes;
		// 	global $Global_Natal_m_crossing;
		// 	$IndexTrack = 0;

		// 	reset($Global_Natal_TransitSortedList);

		// 	foreach($Global_Natal_TransitSortedList as $Key => $Item) {
		// 		$Planet = $Item['pt'];
		// 		$ASP = trim( $Item['asp'] );
		// 		$PN =  trim( $Item['pn'] );
		// 		$AspectType = $AspectTypes[$ASP];
		// 		$IsPlanet = trim( $Item['isplanet'] );

		// 		if($Planet == '1010') {
		// 			echo "<pre>$Planet : $ASP : $PN : $AspectType : $IsPlanet</pre>";
		// 		}

		// 		if($Planet == '1010' && intval($PN) <= 100) {
			
		// 			if($IndexTrack == 0) {
		// 				//Print Title
		// 				$this->GenericHelper->PrintSectionInternalTitle($this, "Transiting_Nodes");
		// 				//Print Description
		// 				$this->GenericHelper->PrintSectionDescription($this, $GenericText[$Global_Language]["Transiting_Nodes"]);
		// 				$IndexTrack++;
		// 			}

		// 			$PlanetCode1 = $AbbrPlanetToFullName[$Planet];
		// 			$PlanetCode2 = sprintf("%02d", $PN);
		// 			$ChapterNo = $GLOBALS["ChapterNameBasedOnPlanet"][$Planet];

		// 			$StartDate = trim( $Item['hitdate'] );
		// 			$StartDate = ($StartDate != '' ? $this->GenericHelper->GetFullDateWithMonth($StartDate) : '');

		// 			$FinalReplaceDate = sprintf('%s %s %s',$StartDate, $GenericText[$Global_Language]['TOConnector'], $EndDate);
		// 			$this->SetGenericTransitText($this->GenericHelper, $ChapterNo, $PlanetCode1, $PlanetCode2, $AspectType, $FinalReplaceDate, true);
		// 		}
		// 	}
		// }

		function SetGenericTransitText($GenericHelper, $ChapterNo, $PlanetCode1, $PlanetCode2, $AspectType, $FinalReplaceDate, $GraphParam, $PrintOnlyTitle = false) {
			global $Global_Language;
			global $AnnotationText;

			$Connector = array('CON' => '0', 'POS' => '+', 'NEG' => '-');
			$ReturnArray = $GenericHelper->GetTextFromDB($ChapterNo, $PlanetCode1, $PlanetCode2, $AspectType, 'T');	

			if(count($ReturnArray) > 0 && $ReturnArray[0]['description'] != '') {
				//#NE/t-AS
				$ThemeTitle = "";
// 				$ThemeTitle = sprintf('#%s/t%s%s', $PlanetCode1, $Connector[$AspectType], $PlanetCode2);
// 				$GenericHelper->PrintSectionInternalTitle($this, $ThemeTitle);

				if($ReturnArray[0]['title']) {
					//$GenericHelper->PrintSectionDescription($this, $ReturnArray[0]['title']);
					$GenericHelper->PrintSectionInternalTitle($this, $ReturnArray[0]['title']);
					
				}									
				
				if($PrintOnlyTitle == true) {
					$Description = $ReturnArray[0]['description'];
					$Description = str_replace('[transitperiod]', " $FinalReplaceDate ", $Description);
					//$Description = str_replace('[transitperiod]', "# $FinalReplaceDate #", $Description);

					if(array_key_exists(sprintf("T-%s-%s-%s", $PlanetCode1, $AspectType, $PlanetCode2), $this->ProccessedArray))
					{
						//$Description = sprintf("Please read page %s",  $this->ProccessedArray[sprintf("T-%s-%s-%s", $PlanetCode1, $AspectType, $PlanetCode2)]['page']);
						$Description = "";
					}

					//Preparing Annotation Text
					$AnnotationTitle = sprintf("%s ", trim($AnnotationText[$Global_Language][$PlanetCode1]));
					$AnnotationTitle .= sprintf("%s ", isset ($AnnotationText[$Global_Language]["TR"]) ? trim($AnnotationText[$Global_Language]["TR"]) : '');
					$AnnotationTitle .= sprintf("%s ", trim($AnnotationText[$Global_Language][$Connector[$AspectType]]));
					$AnnotationTitle .= sprintf("%s ", trim($AnnotationText[$Global_Language][$PlanetCode2]));
					$AnnotationTitle .= sprintf("%s ", trim($AnnotationText[$Global_Language]["R"]));
					$Link = $this->AddLink();
					array_push($this->AnnotationArray, array("Title" => $AnnotationTitle, 'link' => $Link));
					$this->Bookmark($AnnotationTitle);
					//Preparing Annotation Text					
					
					//$this->AddGraphtoPDF($GraphParam);					
					
					$GenericHelper->PrintSectionDescription($this, $Description, count($this->AnnotationArray), $Link);

					//$GenericHelper->PrintSectionDescription($this, $Description);

					$this->ProccessedArray[sprintf("T-%s-%s-%s", $PlanetCode1, $AspectType, $PlanetCode2)] = array('page' => $this->PageNo(), 'title' => $ThemeTitle);
				}
			}
		}

		/**
		 * PrintProgressedAspect() Print Progression
		 * @param Helper class $GenericHelper
		 * @param string $ChapterNo
		 * @param string $CheckForME
		 */
		function PrintProgressedAspect($GenericHelper, $ChapterNo, $CheckForME) {
			echo '<pre>*********************************** PrintProgressedAspect()</pre>';
			global $AbbrPlanetToFullName;
			global $UserAge;
			global $GenericText;
			global $Global_Language;
			global $AspectTypes;
			global $Connector;
			global $Global_Progression_TransitSortedList;
			global $NameOfPlanets;
			global $AspectTypes;
			$PlanetCode1 = $AbbrPlanetToFullName[$CheckForME];
			global $Global_CurrntYear;
			global $Global_NextYear;
			$FetchLastText = 'N';
			$FetchNextText = 'N';

			$cNext = $Global_NextYear;
			$cYear = $Global_CurrntYear;

			if($CheckForME != '1001') {
				$cYear = $Global_CurrntYear;
				$newdate = strtotime ( '-6 month' , strtotime ( $cYear ) ) ;
				$newdate = date ( 'Y-m-d' , $newdate );
				$cYear = $newdate;

				$cNext = $Global_NextYear;
				$nextnewdate = strtotime ( '+6 month' , strtotime ( $cNext ) ) ;
				$nextnewdate = date ( 'Y-m-d' , $nextnewdate );
				$cNext = $nextnewdate;
			}

			$TrackIndex = 0;
			$cYear = strtotime( $cYear );
			$cNext = strtotime( $cNext );
					
			foreach($Global_Progression_TransitSortedList as $Key => $Item) {
				$Planet = $Item['pt'];
				$ASP = trim( $Item['asp'] );
				$PN =  trim( $Item['pn'] );				
				$ASPConnector = $Connector[$ASP];
				$AspectType = $AspectTypes[$ASP];								
				$IsPlanet = trim( $Item['isplanet'] );
				$RadixDate = trim( $Item['start'] );
				$PRDate = '';
				
				if(isset( $Item['enddate'])) {
					$PRDate = trim( $Item['enddate'] );
					$PRDate = ($PRDate != '' ? sprintf("%s %s", $GenericHelper->GetMonthNameFromDate($PRDate), $GenericHelper->GetYearFromDate($PRDate)) : '');
					//echo "<pre>$Planet = $PN = PRDate : $PRDate</pre>";
				}
				
				//$PRDate = trim( $Item['end'] );
				if($IsPlanet == 1 && ( strtotime ( $RadixDate ) >= $cYear && strtotime ( $RadixDate ) <= $cNext )) {
					
					if($TrackIndex == 0 && $Planet == $CheckForME) {				
						//Print Generic Progressed Theme
						if(array_key_exists(sprintf("Progressed_%s", $PlanetCode1) , $GenericText[$Global_Language])) {
							//Print Title
							//$GenericHelper->PrintSectionInternalTitle($this, sprintf("Progressed_%s", $PlanetCode1));

							//Print Description
							$Content = $GenericText[$Global_Language][sprintf("Progressed_%s", $PlanetCode1)];
							$Content = str_replace('[age]', sprintf($GenericText[$Global_Language]['[age]'], $UserAge), $Content);
							
							$this->Ln();
							$GenericHelper->PrintSectionDescription($this, $Content);
							$this->Ln();
						}
						$TrackIndex++;
					}
					
					if($Planet == $CheckForME) {
						$PlanetCode2 = $AbbrPlanetToFullName[$PN];

						if($RadixDate <= $Global_CurrntYear) {
							$FetchLastText = 'Y';
						}

						if($RadixDate >= $Global_NextYear) {
							$FetchNextText = 'Y';
						}

						$RadixDate = ($RadixDate != '' ? sprintf("%s %s", $GenericHelper->GetMonthNameFromDate($RadixDate), $GenericHelper->GetYearFromDate($RadixDate)) : '');

						$ChapterNo = $GLOBALS["ChapterNameBasedOnPlanet"][$CheckForME];
						//$this->SetGenericProgressedText($GenericHelper, $ChapterNo, $PlanetCode1, $PlanetCode2, $AspectType, $RadixDate);
						$this->SetGenericProgressedText($ChapterNo, $PlanetCode1, $PlanetCode2, $AspectType, $RadixDate, $PRDate, $FetchLastText, $FetchNextText, $ASPConnector);
					}

					//Moon section also contains the IC
					if($CheckForME == '1001' && $Planet == '1014' && $ASP == '000') {
						$PlanetCode2 = $AbbrPlanetToFullName[$PN];

						if($RadixDate <= $Global_CurrntYear) {
							$FetchLastText = 'Y';
						}

						if($RadixDate >= $Global_NextYear) {
							$FetchNextText = 'Y';
						}

						$RadixDate = ($RadixDate != '' ? sprintf("%s %s", $GenericHelper->GetMonthNameFromDate($RadixDate), $GenericHelper->GetYearFromDate($RadixDate)) : '');

						$ChapterNo = $GLOBALS["ChapterNameBasedOnPlanet"][$CheckForME];
						//$this->SetGenericProgressedText($GenericHelper, $ChapterNo, $PlanetCode1, $PlanetCode2, $AspectType, $RadixDate);
						$this->SetGenericProgressedText($ChapterNo, $PlanetCode1, $PlanetCode2, $AspectType, $RadixDate, $PRDate, $FetchLastText, $FetchNextText, $ASPConnector);
					}

					//Venus section also contains the DS = Descendant
					if ($CheckForME == '1003' && $Planet == '1015' && $ASP == '000') {
						$PlanetCode2 = $AbbrPlanetToFullName[$PN];

						if($RadixDate <= $Global_CurrntYear) {
							$FetchLastText = 'Y';
						}

						if($RadixDate >= $Global_NextYear) {
							$FetchNextText = 'Y';
						}

						$RadixDate = ($RadixDate != '' ? sprintf("%s %s", $GenericHelper->GetMonthNameFromDate($RadixDate), $GenericHelper->GetYearFromDate($RadixDate)) : '');

						$ChapterNo = $GLOBALS["ChapterNameBasedOnPlanet"][$CheckForME];
						//$this->SetGenericProgressedText($GenericHelper, $ChapterNo, $PlanetCode1, $PlanetCode2, $AspectType, $RadixDate);
						$this->SetGenericProgressedText($ChapterNo, $PlanetCode1, $PlanetCode2, $AspectType, $RadixDate, $PRDate, $FetchLastText, $FetchNextText, $ASPConnector);
					}

					$FetchNextText = 'N';
					$FetchLastText = 'N';
				}
			}
		}

		function SetGenericProgressedText($ChapterNo, $PlanetCode1, $PlanetCode2, $AspectType, $AspectRadixHitDate, $AspectPRHitDate = '', $FetchLastText = 'N', $FetchNextText = 'N', $PassedConnector = '+') {
			global $Gender;
			global $AnnotationText;
			global $Connector;
			global $Global_Language;

			$ReturnArray = $this->GenericHelper->GetTextFromDB($ChapterNo, $PlanetCode1, $PlanetCode2, $AspectType, 'P');

			if(count($ReturnArray) == 0) {
				$ReturnArray = $this->GenericHelper->GetTextFromDB($ChapterNo, $PlanetCode1, $PlanetCode2, $AspectType, 'P-R');
			}
						
			if(count($ReturnArray) > 0 && $ReturnArray[0]['description'] != '') {
				//#NE/t-AS
				//Comment this To remove the Title
				
				//$ThemeTitle = sprintf('%s/p%s%s', $PlanetCode1, $PassedConnector, $PlanetCode2);
				$ThemeTitle = "";				
				$this->SetFont( 'arial', 'B', 11);
				$this->SetTextColor( $GLOBALS['ContentColor'][0], $GLOBALS['ContentColor'][1], $GLOBALS['ContentColor'][2]);
				$this->SetFillColor( 0xcc, 0xcc, 0xcc );				
				$this->GenericHelper->PrintSectionInternalTitle($this, $ThemeTitle);
				
				$this->SetFont( 'arial', '', 10);
				$this->SetFillColor( 0xFF, 0xFF, 0xFF );
				$this->SetTextColor( 0x00, 0x00, 0x00 );

				if($FetchLastText == 'Y') {
					$this->PrintGenericProgressedText('lastyear');
				}

				if($FetchNextText == 'Y') {
					$this->PrintGenericProgressedText('nextyear');
				}

				$TransitTitle = ($ReturnArray[0]['title'] != '' ? $ReturnArray[0]['title'] : '');

				if($TransitTitle != '') {
					$this->GenericHelper->PrintSectionDescription($this, $TransitTitle);
				}

				$Description = $ReturnArray[0]['description'];
				$Description = str_replace('[pr_aspect_radix]', " $AspectRadixHitDate ", $Description);
				$Description = str_replace('[pr_aspect_pr]', " $AspectPRHitDate ", $Description);

				if($Gender == "M"){
					//$Description = preg_replace('^[female]*[end]$', '', $Description);
					$Description = preg_replace('/\[female](.*?)\[end]/','', $Description);
				}
				else {
					//$Description = preg_replace('^[male]*[end]$', '', $Description);
					$Description = preg_replace('/\[male](.*?)\[end]/','', $Description);
				}

				//Preparing Annotation Text
				$AnnotationTitle = sprintf("%s ", trim($AnnotationText[$Global_Language][$PlanetCode1]));
				$AnnotationTitle .= sprintf("%s ", isset ($AnnotationText[$Global_Language]["PR"]) ? trim($AnnotationText[$Global_Language]["PR"]) : '');
				$AnnotationTitle .= sprintf("%s ", trim($AnnotationText[$Global_Language][$AspectType]));
				$AnnotationTitle .= sprintf("%s ", trim($AnnotationText[$Global_Language][$PlanetCode2]));
				$AnnotationTitle .= sprintf("%s ", trim($AnnotationText[$Global_Language]["R"]));
				$Link = $this->AddLink();
				array_push($this->AnnotationArray, array("Title" => $AnnotationTitle, 'link' => $Link));
				$this->Bookmark($AnnotationTitle);
				//Preparing Annotation Text

				$this->GenericHelper->PrintSectionDescription($this, $Description, count($this->AnnotationArray), $Link);

				//$SignANDHouse = $this->GenericHelper->GetPrograssedPlanetSignAndHousePosition($PlanetCode2);
				$SignANDHouse = $this->GenericHelper->GetNatalSignAndHousePosition($PlanetCode2);
					
				if(count($SignANDHouse) > 0) {
					//$ThemeTitle = sprintf('#%s%s_pr_aspect',$PlanetCode2, $SignANDHouse['house']);
					$ThemeTitle = "";
					$this->PrintHouseText($this->GenericHelper, $ThemeTitle, $PlanetCode2, $SignANDHouse['house'], 'CON', 'P-R');
				}
				$this->Ln();
			}
		}

		/**
		 * PrintGenericProgressedText() function will print generic progression text for next and previous year
		 * @param string $LastNext
		 */
		function PrintGenericProgressedText($LastNext) {
			global $UserAge;
			global $GenericText;
			global $Global_Language;

			$FinalText  = $GenericText[$Global_Language][sprintf("Progression_%s", $LastNext)];
			$FinalText = str_replace('[age]', sprintf($GenericText[$Global_Language]['[age]'], $UserAge), $FinalText);

			$this->GenericHelper->PrintSectionDescription($this, $FinalText);
		}

		/**
		 * PrintHouseText() function will print the house text for any planet
		 * @param $GenericHelper
		 * @param $ThemeTitle
		 * @param $PlanetCode1
		 * @param $SignANDHouse
		 * @param $AspectType
		 * @param $AspectStraight
		 */
		function PrintHouseText($GenericHelper, $ThemeTitle, $PlanetCode1, $SignANDHouse, $AspectType, $AspectStraight) {
			global $ChapterNameBasedOnPlanet;
			global $AbbrPlanetToCode;
			global $AnnotationText;
			global $Global_Language;
			global $Connector;
			global $AspectTypes;

			$ChapterNo = $ChapterNameBasedOnPlanet[$AbbrPlanetToCode[$PlanetCode1]];

			$ReturnArray = $GenericHelper->GetTextFromDB($ChapterNo, $PlanetCode1, $SignANDHouse, $AspectType, $AspectStraight);			
			
			//if(count($ReturnArray) == 0 && !isset($ReturnArray[0]) && $ReturnArray[0]['description'] == '') {				
			if(count($ReturnArray) == 0 || empty($ReturnArray)) {
				//if(!isset($ReturnArray[0]) && $ReturnArray[0]['description'] == '') {
					$ReturnArray = $GenericHelper->GetTextFromDB($ChapterNo, $PlanetCode1, $SignANDHouse, $AspectType, 'P-RH');
				//}				
			}

			//if(count($ReturnArray) > 0 && $ReturnArray[0]['description'] != '') {
			if(isset($ReturnArray[0]) && $ReturnArray[0]['description'] != '') {				
				$GenericHelper->PrintSectionInternalTitle($this, $ThemeTitle);

				$Description = $ReturnArray[0]['description'];

				if(array_key_exists(sprintf("PRH-%s000%s", $PlanetCode1, $SignANDHouse), $this->ProccessedArray))
				{
					//$Description = sprintf("Please read page %s",  $this->ProccessedArray[sprintf("PRH-%s000%s", $PlanetCode1, $SignANDHouse)]['page']);
					$Description = "";
				}
					
				//Preparing Annotation Text
				$AnnotationTitle = sprintf("%s ", trim($AnnotationText[$Global_Language][$PlanetCode1]));
				$AnnotationTitle .= sprintf("%s ", trim($AnnotationText[$Global_Language][$SignANDHouse]));
				$AnnotationTitle .= sprintf("%s ", trim($AnnotationText[$Global_Language]["R"]));
				$Link = $this->AddLink();
				array_push($this->AnnotationArray, array("Title" => $AnnotationTitle, 'link' => $Link));
				$this->Bookmark($AnnotationTitle);
				//Preparing Annotation Text

				$GenericHelper->PrintSectionDescription($this, $Description, count($this->AnnotationArray), $Link);

				$this->ProccessedArray[sprintf("PRH-%s000%s", $PlanetCode1, $SignANDHouse)] = array('page' => $this->PageNo(), 'title' => $ThemeTitle);
			}
		}

		/**
		 * GenericSummarySection() function will print the Last summary section for all section
		 * @param $GenericHelper
		 * @param $ChapterNo
		 * @param $CheckForME
		 */
		function GenericSummarySection($GenericHelper, $ChapterNo, $CheckForME) {
			echo '<pre>*********************************** GenericSummarySection()</pre>';
			global $AbbrPlanetToFullName;
			global $UserAge;
			global $GenericText;
			global $Global_Language;
			global $AspectTypes;
			global $Connector;
			global $ChapterNameBasedOnPlanet;
			global $NameOfPlanets;
			global $AspectTypes;
			global $AspectAbbr;
			global $AspectConnector;
			$CheckPoint = 0;

			$PlanetCode1 = $AbbrPlanetToFullName[$CheckForME];

			//Print Generic Transit Script
			if(array_key_exists(sprintf("Summary_%s", $PlanetCode1) , $GenericText[$Global_Language])) {
				//Print Title
				//$GenericHelper->PrintSectionInternalTitle($this, sprintf("Summary_%s", $PlanetCode1));

				$Content = $GenericHelper->GetSectionGenericSummaryText($CheckForME);
				$Content = str_replace('[age]', sprintf($GenericText[$Global_Language]['[age]'], $UserAge), $Content);

				$GenericHelper->PrintSectionDescription($this, $Content);
			}

			foreach ($this->SectionHeadingContents as $key => $trWin) {				
				if(trim( $trWin['pt'] ) == $CheckForME && is_array($trWin['subsection']) ) {					
					foreach ($trWin['subsection'] as $K => $SubSection) {							
						if(is_array($SubSection)) {
							$AspectType = trim( $SubSection['aspecttype'] );
							$Planet = sprintf("%04d", trim( $SubSection['pt'] ) );
							$ASP = trim( $SubSection['asp'] );
							$PN =  sprintf("%04d", trim( $SubSection['pn'] ) );
							$isPlanet = trim( $SubSection['isplanet'] );
															
							if($isPlanet == 1 && intval($PN) >= 1000 && $Planet == $CheckForME) {
								if($CheckForME == '1001' && ($AspectType == 'PR' || $AspectType == 'P')) {
									//There is no progressed moon short text.
									break;
								}
								$this->PrintGenericSectionText($SubSection);
								$CheckPoint++;
							}
														
							if($isPlanet == 1 && intval($PN) >= 1000 && $CheckForME == '1001' && $Planet == '1014' && $ASP = '000') {
								
								if($CheckForME == '1001' && ($AspectType == 'PR' || $AspectType == 'P')) {
									//There is no progressed moon short text.
									break;
								}
								$this->PrintGenericSectionText($SubSection);
								$CheckPoint++;
							}
							
							if($isPlanet == 1 && intval($PN) >= 1000 && $CheckForME == '1003' && $Planet == '1015' && $ASP = '000') {
								if($CheckForME == '1001' && ($AspectType == 'PR' || $AspectType == 'P')) {
									//There is no progressed moon short text.
									break;
								}
								$this->PrintGenericSectionText($SubSection);
								$CheckPoint++;
							}
							

							if($isPlanet == 0 && intval($PN) < 1000 && $Planet == $CheckForME && $AspectType == 'SRSIGN') {
								$this->PrintGenerictSectionSignHouseText($SubSection);
								$CheckPoint++;
							}

							if($CheckPoint >= 5) {
								break;
							}
						}
					}
					break;
				}
			}

			if($CheckForME == '1001' && $CheckPoint <= 3) {		//IF there is no any Major Transit Then we put the Progressed Moon Sign and House Text
				$this->PrograssedMoonInSignHouse();				//Printing the Prograssed Moon Sign and House Text
			}

			//If there is one progression or transing than display Solar return sign possition text
			if($CheckForME == '1004' && $CheckPoint <= 2) {
				$this->SoralReturnMarSignText($CheckForME);
			}
		}

		/**
		 * PrintGenericSectionText() print the Solar return Summary Short text
		 * @param Array $SubSection
		 */
		function PrintGenericSectionText($SubSection) {
			echo '<pre>*********************************** PrintGenericSectionText()</pre>';
			global $AbbrPlanetToFullName;
			global $UserAge;
			global $GenericText;
			global $Global_Language;
			global $AspectTypes;
			global $Connector;
			global $ChapterNameBasedOnPlanet;
			global $NameOfPlanets;
			global $AspectTypes;
			global $AspectAbbr;
			global $AspectConnector;

			$AspectType = trim( $SubSection['aspecttype'] );
			$Planet = sprintf("%04d", trim( $SubSection['pt'] ) );
			$ASP = trim( $SubSection['asp'] );
			$PN =  sprintf("%04d", trim( $SubSection['pn'] ) );
			$isPlanet = trim( $SubSection['isplanet'] );

			$ChapterNo = $ChapterNameBasedOnPlanet[$Planet];
			$PlanetCode1 = $AbbrPlanetToFullName[$Planet];
			$PlanetCode2 = $AbbrPlanetToFullName[$PN];
			$FindAspect = $AspectTypes[$ASP];					// CON | NEG | POS
			$AspectStrength =  $AspectAbbr[$AspectType];		// T   | P-R | SR-SR
			$AspectConn = 't';									// t   | r   | sr

			if(array_key_exists($AspectType, $AspectConnector)) {
				$AspectConn = $AspectConnector[ $AspectType];	//t | r | sr
			}

			$ShortText = $this->GenericHelper->GetShortText($ChapterNo, $PlanetCode1, $FindAspect, $PlanetCode2, $AspectStrength);

			if($ShortText == '') {
				if($AspectStrength == 'T') {
					$ChapterNo = $ChapterNameBasedOnPlanet[$PN];
					$ShortText = $this->GenericHelper->GetShortText($ChapterNo, $PlanetCode2, $FindAspect, $PlanetCode1, $AspectStrength);
					//$ThemeTitle = sprintf('#%s/%s%s%s_short',$PlanetCode2, $AspectConn, $Connector[$ASP], $PlanetCode1);
					$ThemeTitle = "";
				}
					
				if($AspectStrength == 'P' || $AspectStrength == 'P-R') {
					//$ThemeTitle = sprintf('#%s/%s%s%s_short',$PlanetCode1, $AspectConn, $Connector[$ASP], $PlanetCode2);
					$ThemeTitle = "";

					$ShortText = $this->GenericHelper->GetShortText($ChapterNo, $PlanetCode1, $FindAspect, $PlanetCode2, 'P');

					if(strlen(trim($ShortText)) == 0 && trim($ShortText) == '') {
						$ShortText = $this->GenericHelper->GetShortText($ChapterNo, $PlanetCode1, $FindAspect, $PlanetCode2, 'P-R');
					}
				}
					
				if(strlen(trim($ShortText)) > 0 && trim($ShortText) != '') {
					//$ThemeTitle = sprintf('#%s/%s%s%s_short',$PlanetCode1, $AspectConn, $Connector[$ASP], $PlanetCode2);

					$this->GenericHelper->PrintSectionInternalTitle($this, $ThemeTitle);
					$this->GenericHelper->PrintSectionDescription($this, $ShortText);
				}
			}
			else {
					
				//$ThemeTitle = sprintf('#%s/%s%s%s_short',$PlanetCode1, $AspectConn, $Connector[$ASP], $PlanetCode2);
				$ThemeTitle = "";
					
				$this->GenericHelper->PrintSectionInternalTitle($this, $ThemeTitle);
				$this->GenericHelper->PrintSectionDescription($this, $ShortText);
			}
		}

		/**
		 * PrintGenerictSectionSignHouseText() function print the Solar Return Summary Short text for Sign and House
		 * @param Array $SubSection
		 */
		function PrintGenerictSectionSignHouseText($SubSection) {
			echo '<pre>*********************************** PrintGenerictSectionSignHouseText()</pre>';
			global $AbbrPlanetToFullName;
			global $UserAge;
			global $GenericText;
			global $Global_Language;
			global $AspectTypes;
			global $Connector;
			global $ChapterNameBasedOnPlanet;
			global $NameOfPlanets;
			global $AspectTypes;
			global $AspectAbbr;
			global $AspectConnector;
			global $AnnotationText;

			$AspectType = trim( $SubSection['aspecttype'] );
			$Planet = sprintf("%04d", trim( $SubSection['pt'] ) );
			$ASP = trim( $SubSection['asp'] );
			$SignNo =  sprintf("%04d", trim( $SubSection['pn'] ) );
			$HouseNo =  sprintf("%02d", trim( $SubSection['pth'] ) );
			$isPlanet = trim( $SubSection['isplanet'] );

			$ChapterNo = $ChapterNameBasedOnPlanet[$Planet];
			$PlanetCode1 = $AbbrPlanetToFullName[$Planet];
			$PlanetCode2 = $AbbrPlanetToFullName[$SignNo];			//Print Sign Text
			$FindAspect = $AspectTypes[$ASP];					// CON | NEG | POS
			$AspectStrength =  $AspectAbbr[$AspectType];		// T   | P-R | SR-SR
			$AspectConn = 't';									// t   | r   | sr

			if(array_key_exists($AspectType, $AspectConnector)) {
				$AspectConn = $AspectConnector[ $AspectType ];	//t | r | sr
			}

			if($PlanetCode1 != 'SU') {
				//Print Sign Short Text First
				$ShortText = $this->GenericHelper->GetShortText($ChapterNo, $PlanetCode1, $FindAspect, $PlanetCode2, $AspectStrength);
					
				if($ShortText != '') {


					$Link = '';
					$LinkNumber ='';
					if(array_key_exists(sprintf("SR-SR-short-%s000%s", $PlanetCode1, $PlanetCode2), $this->ProccessedArray))
					{
						$LinkNumber = intval(array_search(sprintf("SR-SR-short-%s000%s", $PlanetCode1, $PlanetCode2), array_keys($this->ProccessedArray))) + 1;
					}
					else {
						//Preparing Annotation Text
						$AnnotationTitle = sprintf("%s ", isset ($AnnotationText[$Global_Language]["SOLARR_B"]) ? trim($AnnotationText[$Global_Language]["SOLARR_B"]) : '');
						$AnnotationTitle .= sprintf("%s ", trim($AnnotationText[$Global_Language][$PlanetCode1]));
						$AnnotationTitle .= sprintf("%s ", trim($AnnotationText[$Global_Language][$PlanetCode2]));
						//Preparing Annotation Text

						//Preparing Annotation Text
						$Link = $this->AddLink();
						array_push($this->AnnotationArray, array("Title" => $AnnotationTitle, 'link' => $Link));
						$this->Bookmark($AnnotationTitle);
						//Preparing Annotation Text
						$this->ProccessedArray[sprintf("SR-SR-short-%s000%s", $PlanetCode1, $PlanetCode2)] = array('page' => $this->PageNo(), 'title' => $AnnotationTitle);
					}

					//$ThemeTitle = sprintf('#%s/sr_%s_short',$PlanetCode1, $PlanetCode2);
					$ThemeTitle = "";

					$this->GenericHelper->PrintSectionInternalTitle($this, $ThemeTitle);
					$this->GenericHelper->PrintSectionDescription($this, $ShortText, $LinkNumber, $Link);
				}
			}

			if($PlanetCode1 != 'AS' && $PlanetCode1 != 'MC' && $PlanetCode1 != 'SU') {
				//Print House Short Text Second
				$PlanetCode2 = $HouseNo;			//Print Sign Text
				$ShortText = $this->GenericHelper->GetShortText($ChapterNo, $PlanetCode1, $FindAspect, $PlanetCode2, $AspectStrength);

				if(strlen(trim($ShortText))  == 0 || trim($ShortText) === '') {

					$DescriptionArray = $this->GenericHelper->GetTextFromDB($ChapterNo, $PlanetCode1, $PlanetCode2, $FindAspect, $AspectStrength);

					if(isset($DescriptionArray[0])) {
						$Link = '';
						$LinkNumber = '';
							
						if(array_key_exists(sprintf("SR-SR-%s000%s", $PlanetCode1, $PlanetCode2), $this->ProccessedArray))
						{
							//$ShortText = sprintf("Please read page %s",  $this->ProccessedArray[sprintf("SR-SR-%s000%s", $PlanetCode1, $PlanetCode2)]['page']);
							$ShortText = "";
							$LinkNumber = intval(array_search(sprintf("SR-SR-%s000%s", $PlanetCode1, $PlanetCode2), array_keys($this->ProccessedArray))) + 1;
						}
						else {
							//Preparing Annotation Text
							$AnnotationTitle = sprintf("%s ", isset ($AnnotationText[$Global_Language]["SOLARR_B"]) ? trim($AnnotationText[$Global_Language]["SOLARR_B"]) : '');
							$AnnotationTitle .= sprintf("%s ", trim($AnnotationText[$Global_Language][$PlanetCode1]));
							$AnnotationTitle .= sprintf("%s ", trim($AnnotationText[$Global_Language][$PlanetCode2]));
							//Preparing Annotation Text

							//Preparing Annotation Text
							$Link = $this->AddLink();
							array_push($this->AnnotationArray, array("Title" => $AnnotationTitle, 'link' => $Link));
							$this->Bookmark($AnnotationTitle);
							//Preparing Annotation Text

							$ShortText = $DescriptionArray[0]['description'];
							$this->ProccessedArray[sprintf("SR-SR-%s000%s", $PlanetCode1, $PlanetCode2)] = array('page' => $this->PageNo(), 'title' => $AnnotationTitle);
						}
					}
				}
					
				if(strlen(trim($ShortText)) > 0 && trim($ShortText) != '') {
					//$ThemeTitle = sprintf('#%s/sr_%s_short [There is no Short Text. There only Description]',$PlanetCode1, $PlanetCode2);
					$ThemeTitle = "";

					$this->GenericHelper->PrintSectionInternalTitle($this, $ThemeTitle);
					//$this->GenericHelper->PrintSectionDescription($this, $ShortText);
					$this->GenericHelper->PrintSectionDescription($this, $ShortText, $LinkNumber, $Link);
				}
			}
		}

	function PrograssedMoonInSignHouse() {
		echo '<pre>*********************************** PrograssedMoonInSignHouse()</pre>';
		global $Global_Progression_TransitSortedList;
		global $AbbrPlanetToFullName;
		global $UserAge;
		global $GenericText;
		global $Global_Language;
		global $AspectTypes;
		global $Connector;
		global $NameOfPlanets;
		global $AspectTypes;
		global $ChapterNameBasedOnPlanet;
		global $Gender;
		global $Global_CurrntYear;
		global $Global_NextYear;

		$CheckForME = '1001';
		$cNext = $Global_NextYear;
		$cYear = $Global_CurrntYear;

		if($CheckForME != '1001') {
			$cYear = $Global_CurrntYear;
			$newdate = strtotime ( '-6 month' , strtotime ( $cYear ) ) ;
			$newdate = date ( 'Y-m-d' , $newdate );
			$cYear = $newdate;

			$cNext = $Global_NextYear;
			$nextnewdate = strtotime ( '+6 month' , strtotime ( $cNext ) ) ;
			$nextnewdate = date ( 'Y-m-d' , $nextnewdate );
			$cNext = $nextnewdate;
		}

		$ChapterNo = $ChapterNameBasedOnPlanet[$CheckForME];
		$PlanetCode1 = $AbbrPlanetToFullName[$CheckForME];

		if(is_array($Global_Progression_TransitSortedList)) {
			$IsThereRow = 0;

			//First Check for Sign Changing than
			foreach($Global_Progression_TransitSortedList as $Key => $Item) {
				$PT = $Item['pt'];
				$ASP =$Item['asp'];
				$PN = $Item['pn'];
				$IngressDate = $Item['start'];  		//2008-10-23 [YYYY-MM-DD]
				$IsPlanet = $Item['isplanet'];
				$AspectType = $Item['aspecttype'];  	//PR
				$IsRatrograde = $Item['IsRatrograde'];  //PR

				if($CheckForME == $PT && $ASP == '-->' && ($IngressDate >= $cYear && $IngressDate <= $cNext)) {
					if(intval($PN) > 100 && intval($PN) < 1000) {
						$PlanetCode2 = $AbbrPlanetToFullName[$PN];

						if(intval($PN) == 101) {
							$PreviousPlCode = sprintf("%04d", 112);
						}
						else {
							$PreviousPlCode = sprintf("%04d", intval($PN) - 1);
						}

						$PlanetCode2 = sprintf("%s-%s", $AbbrPlanetToFullName[$PreviousPlCode], $AbbrPlanetToFullName[$PN]);

						$ReturnArray = $this->GenericHelper->GetTextFromDB($ChapterNo, $PlanetCode1, $PlanetCode2, 'CON', 'P');

						if( is_array($ReturnArray) && !isset($ReturnArray[0]['short_text']) ) {
							$ReturnArray = $this->GenericHelper->GetTextFromDB($ChapterNo, $PlanetCode1, $PlanetCode2, 'CNG', 'P-CNG');
						}

						if(count($ReturnArray) > 0 && $ReturnArray[0]['short_text'] != '') {
							//$ThemeTitle = sprintf('#%s/p%s',$PlanetCode1 ,$PlanetCode2);
							$ThemeTitle = "";
								
							$this->GenericHelper->PrintSectionInternalTitle($this, $ThemeTitle);
							$this->GenericHelper->PrintSectionDescription($this, $ReturnArray[0]['short_text']);
						}

						$IsThereRow++;
					}
				}
			}

			if($IsThereRow == 0) {
				reset($Global_Progression_TransitSortedList);

				//If any planet did not cross house during the current year than display current year house and sign text
				foreach($Global_Progression_TransitSortedList as $Key => $Item) {
					if($IsThereRow > 0)
						break;

					$PT = $Item['pt'];
					$ASP =$Item['asp'];
					$PN = $Item['pn'];
					$IngressDate = $Item['start'];  //2008-10-23 [YYYY-MM-DD]
					$IsPlanet = $Item['isplanet'];
					$AspectType = $Item['aspecttype'];  //PR
					$IsRatrograde = $Item['IsRatrograde'];  //PR

					if($CheckForME == $PT && $ASP == '-->' && $IngressDate >= $cNext) {
						if(intval($PN) > 100 && intval($PN) < 1000) {
							$PlanetCode2 = sprintf("%04d", intval($PN) - 1);
							$PlanetCode2 = $AbbrPlanetToFullName[$PlanetCode2];

							$PlanetCode2 = sprintf("%s-%s", $PlanetCode2, $PlanetCode2);
							$ReturnArray = $this->GenericHelper->GetTextFromDB($ChapterNo, $PlanetCode1, $PlanetCode2, 'CON', 'P');

							if( is_array($ReturnArray) && !isset($ReturnArray[0]['short_text']) ) {
								$ReturnArray = $this->GenericHelper->GetTextFromDB($ChapterNo, $PlanetCode1, $PlanetCode2, 'CNG', 'P-CNG');
							}

							if(count($ReturnArray) > 0 && $ReturnArray[0]['short_text'] != '') {
								//$ThemeTitle = sprintf('#%s/p%s',$PlanetCode1 ,$PlanetCode2);
								$ThemeTitle = "";

								$this->GenericHelper->PrintSectionInternalTitle($this, $ThemeTitle);
								$this->GenericHelper->PrintSectionDescription($this, $ReturnArray[0]['short_text']);
							}
							$IsThereRow++;
						}
					}
				}
			}
				
			$IsThereRow = 0;
			reset($Global_Progression_TransitSortedList);
			//If there House Crossing
			foreach($Global_Progression_TransitSortedList as $Key => $Item) {
				$PT = $Item['pt'];
				$ASP =$Item['asp'];
				$PN = $Item['pn'];
				$IngressDate = $Item['start'];  //2008-10-23 [YYYY-MM-DD]
				$IsPlanet = $Item['isplanet'];
				$AspectType = $Item['aspecttype'];  //PR
				$IsRatrograde = $Item['IsRatrograde'];  //PR

				if($CheckForME == $PT && $ASP == '-->' && ($IngressDate >= $cYear && $IngressDate <= $cNext)) {
					if($PN < 100) {
						if(intval($PN) == 1) {
							$PreviousPlCode = sprintf("%04d", 12);
						}
						else {
							$PreviousPlCode = sprintf("%04d", intval($PN) - 1);
						}
						$PlanetCode2 = sprintf("%02d-%02d", $PreviousPlCode, $PN);

						$ReturnArray = $this->GenericHelper->GetTextFromDB($ChapterNo, $PlanetCode1, $PlanetCode2, 'CON', 'P');

						if( is_array($ReturnArray) && !isset($ReturnArray[0]['short_text']) ) {
							$ReturnArray = $this->GenericHelper->GetTextFromDB($ChapterNo, $PlanetCode1, $PlanetCode2, 'CNG', 'P-CNG');
						}

						if(count($ReturnArray) > 0 && $ReturnArray[0]['short_text'] != '') {
							//$ThemeTitle = sprintf('#%s/p%s',$PlanetCode1 ,$PlanetCode2);							
							$ThemeTitle = "";
								
							$this->GenericHelper->PrintSectionInternalTitle($this, $ThemeTitle);
							$this->GenericHelper->PrintSectionDescription($this, $ReturnArray[0]['short_text']);
						}
						$IsThereRow++;
					}
				}
			}
				
			//If there is no house changing than display current house text
			if($IsThereRow == 0) {
				reset($Global_Progression_TransitSortedList);

				//If any planet did not cross house during the current year than display current year house and sign text
				foreach($Global_Progression_TransitSortedList as $Key => $Item) {

					if($IsThereRow > 0)
						break;

					$PT = $Item['pt'];
					$ASP =$Item['asp'];
					$PN = $Item['pn'];
					$IngressDate = $Item['start'];  //2008-10-23 [YYYY-MM-DD]
					$IsPlanet = $Item['isplanet'];
					$AspectType = $Item['aspecttype'];  //PR
					$IsRatrograde = $Item['IsRatrograde'];  //PR

					if($CheckForME == $PT && $ASP == '-->' && $IngressDate >= $cNext) {
						if(intval($PN) < 100) {
							if(intval($PN) == 1) {
								$PlanetCode2 = sprintf("%02d", 12);
							}
							else {
								$PlanetCode2 = sprintf("%02d", intval($PN) - 1);
							}

							$PlanetCode2 = sprintf("%s-%s", $PlanetCode2, $PlanetCode2);
							$ReturnArray = $this->GenericHelper->GetTextFromDB($ChapterNo, $PlanetCode1, $PlanetCode2, 'CON', 'P');

							if( is_array($ReturnArray) && !isset($ReturnArray[0]['short_text']) ) {
								$ReturnArray = $this->GenericHelper->GetTextFromDB($ChapterNo, $PlanetCode1, $PlanetCode2, 'CNG', 'P-CNG');
							}

							if(count($ReturnArray) > 0 && $ReturnArray[0]['short_text'] != '') {
								//$ThemeTitle = sprintf('#%s/p%s',$PlanetCode1 ,$PlanetCode2);
								$ThemeTitle = "";

								$this->GenericHelper->PrintSectionInternalTitle($this, $ThemeTitle);
								$this->GenericHelper->PrintSectionDescription($this, $ReturnArray[0]['short_text']);
							}
							$IsThereRow++;
						}
					}
				}
			}
		}
	}

	/**
	 * PrintCollectiveSectionSumary() function will print the Tester and summary text for the Collective Section
	 */
	function PrintCollectiveSectionSumary() {
		echo '<pre>*********************************** PrintCollectiveSectionSumary()</pre>';
		global $Global_Natal_TransitSortedList;
		global $AbbrPlanetToFullName;
		global $UserAge;
		global $GenericText;
		global $Global_Language;
		global $AspectTypes;
		global $Connector;
		global $ChapterNameBasedOnPlanet;
		global $NameOfPlanets;
		global $AspectTypes;
		global $AspectAbbr;
		global $AspectConnector;
		$TrackIndex = 0;
		//Print Generic Transit Script
		if(array_key_exists("Summary_outers" , $GenericText[$Global_Language])) {
			//Print Title
			//$this->GenericHelper->PrintSectionInternalTitle($this, "Summary_outers" );

			$Content = $GenericText[$Global_Language]['Summary_outers'];
			$Content = str_replace('[age]', sprintf($GenericText[$Global_Language]['[age]'], $UserAge), $Content);

			$this->GenericHelper->PrintSectionDescription($this, $Content);
		}

		//foreach($Global_Natal_TransitSortedList as $Key => $Item) {
		foreach($this->CollectiveArray as $Key => $Item) {				
			$Planet = $Item['pt'];
			$ASP = trim( $Item['asp'] );
			$PN =  trim( $Item['pn'] );
			//$AspectType = $AspectTypes[$ASP];
			$AspectType = trim( $Item['aspecttype'] );
			$IsPlanet = trim( $Item['isplanet'] );

			if($IsPlanet == 1 && in_array($Planet, $this->CollectivePlanet) && in_array($PN, $this->CollectivePlanet)) {
				$PlanetCode1 = $AbbrPlanetToFullName[$Planet];
				$PlanetCode2 = $AbbrPlanetToFullName[$PN];
				$ChapterNo = $GLOBALS["ChapterNameBasedOnPlanet"][$Planet];

				$FindAspect = $AspectTypes[$ASP];					// CON | NEG | POS
				$AspectStrength =  $AspectAbbr[$AspectType];		// T |  P-R | SR-SR
				$AspectConn = 't';									// t | r | sr

				if(array_key_exists($AspectType, $AspectConnector)){
					$AspectConn = $AspectConnector[ $AspectType];	//t | r | sr
				}

				$ShortText = $this->GenericHelper->GetShortText($ChapterNo, $PlanetCode1, $FindAspect, $PlanetCode2, $AspectStrength);

				if($ShortText == '') {
					$ChapterNo = $ChapterNameBasedOnPlanet[$PN];
					$ShortText = $this->GenericHelper->GetShortText($ChapterNo, $PlanetCode2, $FindAspect, $PlanetCode1, $AspectStrength);

					//$ThemeTitle = sprintf('#%s/%s%s%s_short',$PlanetCode2, $AspectConn, $Connector[$ASP], $PlanetCode1);
					$ThemeTitle = "";
					$this->GenericHelper->PrintSectionInternalTitle($this, $ThemeTitle);

					$this->GenericHelper->PrintSectionDescription($this, $ShortText);
				}
				else {
					//$ThemeTitle = sprintf('#%s/%s%s%s_short',$PlanetCode1, $AspectConn, $Connector[$ASP], $PlanetCode2);
					$ThemeTitle = "";
					$this->GenericHelper->PrintSectionInternalTitle($this, $ThemeTitle);

					$this->GenericHelper->PrintSectionDescription($this, $ShortText);
				}

				//$this->SetGenericTransitText($this->GenericHelper, $ChapterNo, $PlanetCode1, $PlanetCode2, $AspectType, $FinalReplaceDate, true);
				$TrackIndex++;

				if($TrackIndex > 2) {
					break;
				}
			}
		}

		//Print Generic Transit Script
		if(array_key_exists("Summary_" , $GenericText[$Global_Language])) {
			//Print Title
			//$this->GenericHelper->PrintSectionInternalTitle($this, "Summary" );

			$Content = $GenericText[$Global_Language]['Summary_'];
			$Content = str_replace('[age]', sprintf($GenericText[$Global_Language]['[age]'], $UserAge), $Content);

			$this->GenericHelper->PrintSectionDescription($this, $Content);
		}
	}
	
	/**
	 * PrintNextYearTheme() Function will print the Next Year Transit
	 */
	function PrintNextYearTheme() {
		echo '<pre>*********************************** PrintNextYearTheme()()</pre>';
		global $AbbrPlanetToFullName;
		global $UserAge;
		global $GenericText;
		global $Global_Language;
		global $AspectTypes;
		global $Connector;
		global $ChapterNameBasedOnPlanet;
		global $NameOfPlanets;
		global $AspectTypes;
		global $AspectAbbr;
		global $AspectConnector;
	
		//Print Generic Transit Script
		if(array_key_exists("Next_Year_1" , $GenericText[$Global_Language])) {
			//Print Title
			//$this->GenericHelper->PrintSectionInternalTitle($this, "Next_Year_1" );			
	
			$Content = $GenericText[$Global_Language]['Next_Year_1'];
			$Content = str_replace('[age_next_year]', sprintf($GenericText[$Global_Language]['[age]'], intval($UserAge) + 1), $Content);
	
			$this->GenericHelper->PrintSectionDescription($this, $Content);
		}
	
		foreach ($this->TopThemeForNextYear as $key => $trWin) {
			$AspectType = trim( $trWin['aspecttype'] );
			$Planet = sprintf("%04d", trim( $trWin['pt'] ) );
			$ASP = trim( $trWin['asp'] );
			$PN =  sprintf("%04d", trim( $trWin['pn'] ) );
			$isPlanet = trim( $trWin['isplanet'] );
	
			if($isPlanet == 1 && intval($PN) >= 1000) {
	
				$ChapterNo = $ChapterNameBasedOnPlanet[$Planet];
				$PlanetCode1 = $AbbrPlanetToFullName[$Planet];
				$PlanetCode2 = $AbbrPlanetToFullName[$PN];
				$FindAspect = $AspectTypes[$ASP];					// CON | NEG | POS
				$AspectStrength =  $AspectAbbr[$AspectType];		// T |  P-R | SR-SR
				$AspectConn = 't';									// t | r | sr
	
				if(array_key_exists($AspectType, $AspectConnector)){
					$AspectConn = $AspectConnector[ $AspectType];	//t | r | sr
				}
	
				$ShortText = $this->GenericHelper->GetTesterTextNextYear($ChapterNo, $PlanetCode1, $FindAspect, $PlanetCode2, $AspectStrength);
								
				if($ShortText == '') {
					if($AspectStrength == 'P-R') {
						$AspectStrength = 'P';
						$ShortText = $this->GenericHelper->GetTesterTextNextYear($ChapterNo, $PlanetCode1, $FindAspect, $PlanetCode2, $AspectStrength);
					}
	
					if($ShortText == '') {
						$ChapterNo = $ChapterNameBasedOnPlanet[$PN];
						$ShortText = $this->GenericHelper->GetTesterTextNextYear($ChapterNo, $PlanetCode2, $FindAspect, $PlanetCode1, $AspectStrength);
					}
	
					//$ThemeTitle = sprintf('#%s/%s%s%s_tester',$PlanetCode2, $AspectConn, $Connector[$ASP], $PlanetCode1);
					$ThemeTitle = "";
					$this->GenericHelper->PrintSectionInternalTitle($this, $ThemeTitle);
	
					$this->GenericHelper->PrintSectionDescription($this, $ShortText);
				}
				else {
	
					//$ThemeTitle = sprintf('#%s/%s%s%s_tester',$PlanetCode1, $AspectConn, $Connector[$ASP], $PlanetCode2);
					$ThemeTitle = "";
					$this->GenericHelper->PrintSectionInternalTitle($this, $ThemeTitle);
	
					$this->GenericHelper->PrintSectionDescription($this, $ShortText);
				}
				unset($ChapterNo);
				unset($PlanetCode1);
				unset($FindAspect);
				unset($PlanetCode2);
				unset($ShortText);
			}
		}
	
		//Print Generic Transit Script
		if(array_key_exists("Final_Summary" , $GenericText[$Global_Language])) {
			//Print Title
			//$this->GenericHelper->PrintSectionInternalTitle($this, "Final_Summary" );			
	
			$Content = $GenericText[$Global_Language]['Final_Summary'];
			$Content = str_replace('[age]', sprintf($GenericText[$Global_Language]['[age]'], $UserAge), $Content);
	
			$this->GenericHelper->PrintSectionDescription($this, $Content);
		}
	}
	
	function PrintRadixSignAndHouseText($GenericHelper, $CheckForMe) {
		echo '<pre>*********************************** PrintRadixSignAndHouseText()</pre>';
		global $AbbrPlanetToFullName;
		global $Global_Language;
		global $Connector;
		global $ChapterNameBasedOnPlanet;
	
		$ChapterNo = $ChapterNameBasedOnPlanet[$CheckForMe];
		$PlanetCode1 = $AbbrPlanetToFullName[$CheckForMe];
	
		$ArraySignHouse = $GenericHelper->GetNatalSignAndHousePosition($PlanetCode1);
		$HouseNo = $ArraySignHouse['house'];
		$SignNo = $ArraySignHouse['sign'];
		$ThemeTitle = '';
		$Description = '';
	
		//PRINTING the Sign Text
		if($SignNo != '') {
			//$PlanetCode2  = $AbbrPlanetToFullName[$SignNo];
			$PlanetCode2  = $SignNo;
	
			$DescriptionSignArray = $GenericHelper->GetTextFromDB($ChapterNo, $PlanetCode1, $PlanetCode2, 'CON', 'R-SIGN');
	
			if( is_array($DescriptionSignArray)  )  {
				if(isset( $DescriptionSignArray[0])) {
					//#SUPIX [With your sun in your birth chart in Pisces ]
					//$ThemeTitle = sprintf('#%s%sX [%s]',$PlanetCode1 ,$PlanetCode2, $DescriptionSignArray[0]['title']);
					$ThemeTitle = sprintf(' %s ', $DescriptionSignArray[0]['title']);
					$Description = $DescriptionSignArray[0]['description'];
				}
			}
		}
	
		//PRINTING the House Text
		if($HouseNo != '') {
			$PlanetCode2  = $HouseNo;
			$DescriptionHouseArray = $GenericHelper->GetTextFromDB($ChapterNo, $PlanetCode1, $PlanetCode2, 'CON', 'R-HOUSE');
	
			if(is_array($DescriptionHouseArray) ) {
				if(isset( $DescriptionHouseArray[0])){
					//#SU03X [and in the 3rd House ]
					//$ThemeTitle .= sprintf(' #%s%sX [%s]', $PlanetCode1 ,$PlanetCode2, $DescriptionHouseArray[0]['title']);
					$ThemeTitle .= sprintf(' %s ', $DescriptionHouseArray[0]['title']);
					$Description .= sprintf(' %s ', $DescriptionHouseArray[0]['description']);
				}
			}
		}
	
		if($ThemeTitle != "" && $Description != ""){
			//$GenericHelper->PrintSectionInternalTitle($this, $ThemeTitle);
			// 		$GenericHelper->PrintSectionDescription($this, $ThemeTitle);
			// 		$GenericHelper->PrintSectionDescription($this, $Description);	
			$GenericHelper->PrintSectionDescription($this, $ThemeTitle . ' ' . $Description);
		}
	}
	
	function PrintPlanetHouserPositionInSR($GenericHelper, $CheckForMe){
		echo '<pre>*********************************** PrintPlanetHouserPositionInSR()</pre>';
	
		//if($CheckForMe != '1000')  { //SUN
		global $AbbrPlanetToFullName;
		global $Global_Language;
		global $Connector;
		global $GenericText;
		global $UserAge;
		global $ChapterNameBasedOnPlanet;
		global $AnnotationText;
	
		$ChapterNo = $ChapterNameBasedOnPlanet[$CheckForMe];
		$PlanetCode1 = $AbbrPlanetToFullName[$CheckForMe];
	
		$ArraySignHouse = $GenericHelper->GetSolarReturnSignAndHousePosition($PlanetCode1);
		$HouseNo = $ArraySignHouse['house'];
		$SignNo = $ArraySignHouse['sign'];
		
		//PRINTING the House Text
		if($SignNo != '' && $CheckForMe != '1000') {
			$PlanetCode2  = $SignNo;
	
			$DescriptionArray = $GenericHelper->GetTextFromDB($ChapterNo, $PlanetCode1, $PlanetCode2, 'CON', 'SR-SR');
	
			if(is_array($DescriptionArray) ) {
				//#SU/sr_02
				//$ThemeTitle = sprintf('#%s/sr_%s',$PlanetCode1 ,$PlanetCode2);
				$ThemeTitle = "";
				$GenericHelper->PrintSectionInternalTitle($this, $ThemeTitle);
	
				if(isset($DescriptionArray[0])){
					//Preparing Annotation Text
					$AnnotationTitle = sprintf("%s ", trim($AnnotationText[$Global_Language][$PlanetCode1]));
					$AnnotationTitle .= sprintf("%s ", isset ($AnnotationText[$Global_Language]["SOLARR"]) ? trim($AnnotationText[$Global_Language]["SOLARR"]) : '');
					$AnnotationTitle .= sprintf("%s ", trim($AnnotationText[$Global_Language][$Connector['000']]));
					$AnnotationTitle .= sprintf("%s ", trim($AnnotationText[$Global_Language][$PlanetCode2]));
					$Link = $this->AddLink();
					array_push($this->AnnotationArray, array("Title" => $AnnotationTitle, 'link' => $Link));
					$this->Bookmark($AnnotationTitle);
					//Preparing Annotation Text
	
					//echo "<pre>$ChapterNo -- $PlanetCode1 -- $PlanetCode2 -- 'CON' -- 'SR-SR'</pre>";
					$Description = str_replace('[age]', sprintf($GenericText[$Global_Language]['[age]'], $UserAge), $DescriptionArray[0]['description']);
					$GenericHelper->PrintSectionDescription($this, $Description, count($this->AnnotationArray), $Link);
	
					$this->ProccessedArray[sprintf("SR-SR-%s000%s", $PlanetCode1, $PlanetCode2)] = array('page' => $this->PageNo(), 'title' => $ThemeTitle);
				}
			}
		}
	
		//PRINTING the House Text
		if($HouseNo != '') {
			$PlanetCode2  = $HouseNo;
	
			$DescriptionArray = $GenericHelper->GetTextFromDB($ChapterNo, $PlanetCode1, $PlanetCode2, 'CON', 'SR-SR');
	
			if(is_array($DescriptionArray) ) {
				//#SU/sr_02
				//$ThemeTitle = sprintf('#%s/sr_%s',$PlanetCode1 ,$PlanetCode2);
				$ThemeTitle = "";
				$GenericHelper->PrintSectionInternalTitle($this, $ThemeTitle);
	
				if(isset($DescriptionArray[0])){
					//Preparing Annotation Text
					$AnnotationTitle = sprintf("%s ", trim($AnnotationText[$Global_Language][$PlanetCode1]));
					$AnnotationTitle .= sprintf("%s ", isset ($AnnotationText[$Global_Language]["SOLARR"]) ? trim($AnnotationText[$Global_Language]["SOLARR"]) : '');
					$AnnotationTitle .= sprintf("%s ", trim($AnnotationText[$Global_Language][$Connector['000']]));
					$AnnotationTitle .= sprintf("%s ", trim($AnnotationText[$Global_Language][$PlanetCode2]));
					$Link = $this->AddLink();
					array_push($this->AnnotationArray, array("Title" => $AnnotationTitle, 'link' => $Link));
					$this->Bookmark($AnnotationTitle);
					//Preparing Annotation Text
	
					$Description = str_replace('[age]', sprintf($GenericText[$Global_Language]['[age]'], $UserAge), $DescriptionArray[0]['description']);
					$GenericHelper->PrintSectionDescription($this, $Description, count($this->AnnotationArray), $Link);
	
					$this->ProccessedArray[sprintf("SR-SR-%s000%s", $PlanetCode1, $PlanetCode2)] = array('page' => $this->PageNo(), 'title' => $ThemeTitle);
				}
			}
		}
		//}
	}
	
	function PrintAnnotation() {
		global  $AnnotationText;
		
		$this->AddPage();
		//$this->GenericHelper->PrintSectionInternalTitle($this, "Annotation Text for Solar Return, Transit and Progression");
		
		// 		if(count($this->AnnotationArray) > 0){
		// 			foreach ($this->AnnotationArray as $Key => $Item){
		// 				$Text = sprintf("%s. %s", intval($Key) + 1, $Item['Title']);
		// 				$this->SetLink($Item['link'], $this->GetY(), $this->PageNo());
		// 				$this->GenericHelper->PrintAnnotation($this, $Text, $Item['link']);
		// 			}
		// 		}
		$this->Bookmark('Index');
		$this->CreateIndex();
	}

	/**
	 * PrintMoonPhase() function print the Prograssed Moon different phase
	 */
	function PrintMoonPhase() {
		global $GenericText;
		global $Global_Language;
		global $UserAge;
	
		$MoonInfo = $this->GenericHelper->GetPrograssedPlanetDegree('MO');
		$SunInfo = $this->GenericHelper->GetPrograssedPlanetDegree('SU');
		$MoonLongitude = 0;
		$SunLongitude = 0;
		$MoonDegree = 0;
		$SunDegree = 0;
	
		if(count($MoonInfo) > 0){
			$MoonLongitude = $MoonInfo['longitude'];
			$Degrees = fmod($MoonLongitude, 30.0);
			$Minutes = $Degrees - intval($Degrees);
			$Minutes = $Minutes * 0.6;
			$Minutes = intval($Minutes * 100);
			$MoonDegree = sprintf("%02d.%02d", $Degrees, $Minutes);
		}
	
		if(count($SunInfo) > 0) {
			$SunLongitude = $SunInfo['longitude'];
			$Degrees = fmod($SunLongitude, 30.0);
			$Minutes = $Degrees - intval($Degrees);
			$Minutes = $Minutes * 0.6;
			$Minutes = intval($Minutes * 100);
			$SunDegree = sprintf("%02d.%02d", $Degrees, $Minutes);
		}
	
		//$Difference = $MoonDegree - $SunDegree;
		$Difference = $MoonLongitude - $SunLongitude;
	
		if($Difference < 0)
			$Difference += 360.0;
	
		if($Difference > 360)
			$Difference -= 360.0;
	
		$DiffMin = $Difference - intval($Difference);
	
		if($DiffMin > 60) {
			$Min =	$DiffMin - 60;
			$Difference = $Difference + 1 + $Min;
		}
	
		$MoonPhase = $this->GetMoonPhase($Difference);
	
		if($MoonPhase != "") {
			//$this->GenericHelper->PrintSectionInternalTitle($this, $MoonPhase);
	
			$Description = $GenericText[$Global_Language][$MoonPhase];
			$Description = str_replace('[age]', sprintf($GenericText[$Global_Language]['[age]'], $UserAge), $Description);
	
			$this->GenericHelper->PrintSectionDescription($this, $Description);
		}
	}

	function GetMoonPhase($Difference) {
	
		if($Difference > 0 && $Difference <= 45) {
			return "Progressed_Moon_New";
		}
		else if($Difference > 45 && $Difference <= 90) {
			return "Progressed_Moon_Phase_Crescent";
		}
		else if($Difference > 91 && $Difference <= 135) {
			return "Progressed_Moon_Phase_First_Quarter";
		}
		else if($Difference > 135 && $Difference <= 180){
			return "Progressed_Moon_Phase_Gibbous";
		}
		else if($Difference > 180 && $Difference <= 215) {
			return "Progressed_Moon_Phase_Full";
		}
		else if($Difference > 215 && $Difference <= 270) {
			return "Progressed_Moon_Phase_Disseminating";
		}
		else if($Difference > 270 && $Difference <= 315) {
			return "Progressed_Moon_Phase_Last_Quarter";
		}
		else if($Difference > 315 && $Difference <= 359) {
			return "Progressed_Moon_Phase_Balsamic";
		}
		return "Progressed_Moon_New";
	}
	
	function AddGraphtoPDF($trWin) {
// 		//http://www.fpdf.de/downloads/addons/48/
// 		//http://www.fpdf.de/downloads/addons
// 		//http://www.fpdf.de/forum/showthread.php?t=3738
// 		echo '<pre>*********************************** AddGraphtoPDF()</pre>';
	
// 		//$charturl = urlencode ("http://chart.apis.google.com/chart?cht=lc&chs=600x200&chm=d,0000FF,0,-2,4,0&chd=s:gXiVcdlhUagRacRTpfgueZedRZYbgYrfObMTSWfYYPTPUdceTiYpZeorgnlXTTfndecdVcmcVZTXiZptVZZJcPVaQaWeeeXawhdXcbjo0sohfyndxqdgbQQedfZdTUNQNGQUUUMQJdVZfdelljxsnvoXfyroigVTfOVQUOQYRMIKOMNTOTNVKZXQhnZelsqkfnTaackPPaVUSONNOHIHDAHKEDEDACAIEPEGCPGIDGIIGJHEBEACHCBCAABEFEFFDLLFSHFCHBGEDFHGCEEEHFJDIGNKCIQKGSKFIMJJYhhdeomlmipnmbfYkRPXWXUieROfcTQKLKKObZcjTncmjgnXrnfXgUglroWfgbmnZbLJHSWPTWMQQVUTQRVQQXZYkchsoofjtn7xYSsafkjlw9ddfiaOXxdeTPTafbQaqUiYccRZpYfddir0Wcci0obcYSjhTTSWSePRLbnxeogVdUcaooSbPVaTamujZSRdkhVgWOWmbidd");
// 		// 		$charturl = "http://chart.apis.google.com/chart?cht=lc&chs=600x200&chm=d,0000FF,0,-2,4,0&chd=s:gXiVcdlhUagRacRTpfgueZedRZYbgYrfObMTSWfYYPTPUdceTiYpZeorgnlXTTfndecdVcmcVZTXiZptVZZJcPVaQaWeeeXawhdXcbjo0sohfyndxqdgbQQedfZdTUNQNGQUUUMQJdVZfdelljxsnvoXfyroigVTfOVQUOQYRMIKOMNTOTNVKZXQhnZelsqkfnTaackPPaVUSONNOHIHDAHKEDEDACAIEPEGCPGIDGIIGJHEBEACHCBCAABEFEFFDLLFSHFCHBGEDFHGCEEEHFJDIGNKCIQKGSKFIMJJYhhdeomlmipnmbfYkRPXWXUieROfcTQKLKKObZcjTncmjgnXrnfXgUglroWfgbmnZbLJHSWPTWMQQVUTQRVQQXZYkchsoofjtn7xYSsafkjlw9ddfiaOXxdeTPTafbQaqUiYccRZpYfddir0Wcci0obcYSjhTTSWSePRLbnxeogVdUcaooSbPVaTamujZSRdkhVgWOWmbidd";
// 		// 		$this->Image($charturl, null, null, 180, 60, 'PNG');

		//Uncomment from her to add the graph
		
		if(!function_exists("_setDate")) {
			function _setDate($aVal) {
				return sprintf('%s', substr($aVal, 0, 4));
			}
		}
	
		if(isset($trWin['yaxis']) && isset($trWin['xaxis']) ) {
			if (count($trWin['yaxis']) > 0 && count($trWin['xaxis']) > 0) {
				$datay = $trWin['yaxis'];
				$datax = $trWin['xaxis'];
				
				$MaxDate =  max($trWin['xaxis']);
				$MinDate =  min($trWin['xaxis']);	
				$maxY = $trWin['maxorb'];
				$minY = $trWin['minorb'];
				
				if(abs($maxY) >= 0 && abs($minY) >= 0) {
					
					$tmp = array();
					for($i = 0; $i < count($datay); $i++) {
						if(is_numeric($datay[$i])) {
							$tmp[] = $maxY - $datay[$i];
						}
						else {
							$tmp[] = $datay[$i];
						}
					}
					unset($datay);
					$datay = $tmp;
					
					$maxY = max($maxY, $minY);
					$minY = min($maxY, $minY);
				
				
					$TickInterval = count($datax);				
					if($TickInterval > 365)
						$TickInterval = intval($TickInterval / 365);
					else if($TickInterval > 11){
						$TickInterval = intval($TickInterval / 12) + 1;
					}
					
					// Apply this format to all time values in the data to prepare it to be display
					$graph = new Graph($this->ImgWidth, $this->ImgHeight);
					$graph->SetScale("datlin", $minY, $maxY);
					//			SetMargin($left, $right, $top, $bottom)
					$graph->img->SetMargin(10,10,0,60);	
					$graph->img->SetAntiAliasing(TRUE);
								
					$graph->xaxis->SetTickLabels($datax);
					//$graph->xaxis->SetFont(FF_FONT2);
					$graph->xaxis->SetLabelAngle(35);
					$graph->xaxis->SetTextLabelInterval($TickInterval);
					$graph->xaxis->SetLabelFormatCallback('_setDate');
					//$graph->xaxis->SetLabelFormatString('Y',true);
					$graph->xaxis->HideLabels(false);
					$graph->xaxis->HideTicks(false, false);
					
					$graph->yaxis->SetFont(FF_FONT2);
					$graph->yaxis->SetColor("black", "white");
					$graph->yaxis->HideLabels(true);
					$graph->yaxis->HideTicks(true, true);
							
					$graph->img->SetAlphaBlending(FALSE);
					$graph->SetBox(true);
					$graph->SetClipping(true);
		
					$oCurve = new CubicSplines();
					if ($oCurve) {
						$oCurve->setInitCoords($datay, 0.001);
						$r = $oCurve->processCoords();
						unset($datay);
						$datay = $r;
					}
		
					$p1 = new LinePlot($datay);
					$graph->Add($p1);
					$p1->SetFillColor('red');
					$p1->SetColor('red');
					$graph->SetColor('white');				
					$graph->SetBackgroundGradient('white','white',GRAD_HOR,BGRAD_PLOT);
		
					$test = $graph->Stroke(_IMG_HANDLER);
					
					$OldY = $this->GetY();
					$OldX = $this->GetX();					
		
					if($OldY >= 255) {
						$this->AddPage();
						$OldY = 21;
					}
					
					//$this->Multicell($this->ConvertPixelsToMM($this->ImgWidth), 20, $this->GDImage($test, 20, $OldY));
					$this->Multicell($this->ConvertPixelsToMM($this->ImgWidth), 21, 
											$this->GDImage($test, 21, $OldY));
					//$this->Multicell(146, 21, $this->GDImage($test, 21, $OldY));
					$this->Ln();
		
					//Releasing Memories
					imagedestroy($test);
		
					unset($test);
					unset($graph);
					unset($datay);
					unset($maxY);
					unset($minY);
				}
			}
		}		
	}
	
// 	function AddGraphtoPDF($trWin) {
// 		//http://www.fpdf.de/downloads/addons/48/	
// 		//http://www.fpdf.de/downloads/addons
// 		//http://www.fpdf.de/forum/showthread.php?t=3738
// 		echo '<pre>*********************************** AddGraphtoPDF()</pre>';
		
// 		//$charturl = urlencode ("http://chart.apis.google.com/chart?cht=lc&chs=600x200&chm=d,0000FF,0,-2,4,0&chd=s:gXiVcdlhUagRacRTpfgueZedRZYbgYrfObMTSWfYYPTPUdceTiYpZeorgnlXTTfndecdVcmcVZTXiZptVZZJcPVaQaWeeeXawhdXcbjo0sohfyndxqdgbQQedfZdTUNQNGQUUUMQJdVZfdelljxsnvoXfyroigVTfOVQUOQYRMIKOMNTOTNVKZXQhnZelsqkfnTaackPPaVUSONNOHIHDAHKEDEDACAIEPEGCPGIDGIIGJHEBEACHCBCAABEFEFFDLLFSHFCHBGEDFHGCEEEHFJDIGNKCIQKGSKFIMJJYhhdeomlmipnmbfYkRPXWXUieROfcTQKLKKObZcjTncmjgnXrnfXgUglroWfgbmnZbLJHSWPTWMQQVUTQRVQQXZYkchsoofjtn7xYSsafkjlw9ddfiaOXxdeTPTafbQaqUiYccRZpYfddir0Wcci0obcYSjhTTSWSePRLbnxeogVdUcaooSbPVaTamujZSRdkhVgWOWmbidd");
// // 		$charturl = "http://chart.apis.google.com/chart?cht=lc&chs=600x200&chm=d,0000FF,0,-2,4,0&chd=s:gXiVcdlhUagRacRTpfgueZedRZYbgYrfObMTSWfYYPTPUdceTiYpZeorgnlXTTfndecdVcmcVZTXiZptVZZJcPVaQaWeeeXawhdXcbjo0sohfyndxqdgbQQedfZdTUNQNGQUUUMQJdVZfdelljxsnvoXfyroigVTfOVQUOQYRMIKOMNTOTNVKZXQhnZelsqkfnTaackPPaVUSONNOHIHDAHKEDEDACAIEPEGCPGIDGIIGJHEBEACHCBCAABEFEFFDLLFSHFCHBGEDFHGCEEEHFJDIGNKCIQKGSKFIMJJYhhdeomlmipnmbfYkRPXWXUieROfcTQKLKKObZcjTncmjgnXrnfXgUglroWfgbmnZbLJHSWPTWMQQVUTQRVQQXZYkchsoofjtn7xYSsafkjlw9ddfiaOXxdeTPTafbQaqUiYccRZpYfddir0Wcci0obcYSjhTTSWSePRLbnxeogVdUcaooSbPVaTamujZSRdkhVgWOWmbidd";
// // 		$this->Image($charturl, null, null, 180, 60, 'PNG');
// 		if(!function_exists("_setDate")) {
// 			function _setDate($aVal) {
// 				return sprintf('%s', substr($aVal, 1, 4));
// 			}
// 		}
		
// 		if(isset($trWin['yaxis']) && isset($trWin['xaxis']) ) {
// 			if (count($trWin['yaxis']) > 0 && count($trWin['xaxis']) > 0) {
// 				$datay = $trWin['yaxis'];
// 				$datax = $trWin['xaxis'];
				
// 				$MaxDate =  max($trWin['xaxis']);
// 				$MixDate =  min($trWin['xaxis']);				
// 				$MaxDate = min($MaxDate, $MinDate);
// 				$MinDate = max($MaxDate, $MinDate);
	
// 				$maxY = $trWin['maxorb'];
// 				$minY = $trWin['minorb'];
				
// 				$maxY = max($minY, $maxY);				
// 				$minY = min($minY, $maxY);
				
// 				$tmp = array();
				
// 				for($i = 0; $i < count($datay); $i++) {
// 					if(is_numeric($datay[$i])) {
// 						//$tmp[] = $datay[$i] * -1;
// 						$tmp[] = $maxY - $datay[$i];
// 					}
// 					else {
// 						$tmp[] = $datay[$i];
// 					}
// 				}
// 				unset($datay);
// 				$datay = $tmp;				
// 				$maxY = max($datay);
// 				$minY = min($datay);
				
// 				// Apply this format to all time values in the data to prepare it to be display						
// 				$graph = new Graph($this->ImgWidth, $this->ImgHeight);		
				
// 				//$graph->SetScale("datlin", $minY, $maxY);
// 				//$graph->SetScale("datlin", min($minY, $maxY) , max($minY, $maxY));			
// 				$graph->SetScale("datlin", $minY, $maxY);
// 				//$graph->SetScale("textlin");
				
// 				$graph->img->SetMargin(30,20,30,20);
	
// 				$graph->img->SetAntiAliasing(TRUE);
// 				$graph->SetAxisStyle(AXSTYLE_BOXOUT);			
				
// 				$graph->xaxis->SetTickLabels($datax);
// 				$graph->yaxis->SetLabelFormatCallback("_setDate");
// 	 			$graph->xaxis->SetTextLabelInterval(4);
// 	// 			$graph->xaxis->SetLabelFormatString('Y',true);
// 	// 			$graph->xaxis->SetFont(FF_FONT0,FS_NORMAL,8);	
					
// 	// 			$graph->xaxis->SetLabelPos(SIDE_UP);
// 	// 			$graph->xaxis->SetTickSide(SIDE_DOWN);
				
// 	// 			$graph->yscale->ticks->Set(10,1);			
// 	// 			$graph->yaxis->SetTextTickInterval(4);
				
// 				$graph->img->SetAlphaBlending(FALSE);
// 				$graph->SetBox(true);
// 				$graph->SetClipping(true);
				
// 				$oCurve = new CubicSplines();
// 				if ($oCurve) {
// 					$oCurve->setInitCoords($datay, 0.001);
// 					$r = $oCurve->processCoords();
// 					unset($datay);
// 					$datay = $r;				
// 				}
				
// 				$p1 = new LinePlot($datay);
// 				$graph->Add($p1);		
// 				$p1->SetFillColor('red');
// 				$p1->SetColor('red');
// 				$graph->SetColor('red');
// 				$graph->SetBackgroundGradient('white','white',GRAD_HOR,BGRAD_PLOT);
				
// 				$test = $graph->Stroke(_IMG_HANDLER);	
// 				$OldY = $this->GetY();
// 				$OldX = $this->GetX();
					
// 	// 			$content = "datay: " .count($datay). " = ";
// 	// 			$content .= "datax: " .count($datax);			
// 	// 			$this->Multicell(180, 3, $content);
				
// 				if($OldY >= 255) {
// 					$this->AddPage();
// 					$OldY = 20;
// 				}
				
// 				//$this->Multicell($this->ConvertPixelsToMM($this->ImgWidth), $this->ConvertPixelsToMM($this->ImgHeight), $this->GDImage($test, 10, $OldY));
// 				$this->Multicell($this->ConvertPixelsToMM($this->ImgWidth), 20, $this->GDImage($test, 20, $OldY));
// 				$this->Ln();
	
// 				//Releasing Memories
// 				imagedestroy($test);
	
// 				unset($test);
// 				unset($graph);
// 				unset($datay);
// 				unset($maxY);
// 				unset($minY);
// 			}
// 		}
// 	}	
}
?>