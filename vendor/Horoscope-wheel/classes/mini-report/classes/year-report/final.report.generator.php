<?php

class MiniReportGenerator extends ReportGenerator {

	var $ReportHTML = '';
	var $OrderId;

	function __construct($YearBookText, $YearBookAgeText) {
		//parent::ReportGenerator($YearBookText, $YearBookAgeText);

		parent::FPDF('P', 'mm', 'A4');
		$this->Open();
		$this->AliasNbPages();

		$this->GenericHelper = new MiniReportGenericHelper();

		$this->ImgWidth = 350;
		$this->ImgHeight = 100;

		$this->YBookText = $YearBookText;
		$this->YBookAgeText = $YearBookAgeText;

		$this->LeftMargin = 16;
		$this->TopMargin = 16;

		$this->TableX = $this->LeftMargin + 16;
		$this->TableY = $this->TopMargin + 16;

		$this->TableWidth = 170;
		$this->TableHeight = 10;
		$this->TableCellWidth = 42.5;
		$this->SetMargins($this->LeftMargin, $this->TopMargin, $this->RightMargin);

		$this->ProccessedArray = array();
		$this->SaturnJupiterCrossing = array();
		$this->TopTheme = array();
		$this->TopThemeForNextYear = array();
		$this->AnnotationArray = array();

		$this->SetTopThemes();
		$this->SetSAandJUHouseCrossing();
	}

	public function SetTopThemes() {
		echo "<pre>********************************************************************** SetTopThemes()</pre>";
		global $Global_Natal_TransitSortedList;
		global $Global_N_TSLNextYear;
		global $Global_Progression_TransitSortedList;
		global $Global_NextYear;
		global $Global_CurrntYear;
		global $NameOfPlanets;
		
		$TopCount = 0;
		echo " == Global_CurrntYear :" . date("Y-M-d", strtotime($Global_CurrntYear));
		echo " == Global_NextYear :" . date("Y-M-d", strtotime($Global_NextYear)). "<br />";
		
		if( count($Global_Natal_TransitSortedList) > 0) {
			foreach ($Global_Natal_TransitSortedList as $values) {
				$tDate = $values['hitdate'];

				$PN = $values['pn'];
				if($PN != '1001' && $PN != '1012' && $PN != '1013' && $PN != '1014' && $PN != '1015') {
					if( $tDate >= $Global_CurrntYear && $tDate <= $Global_NextYear )
					{
						echo "***********<br />";
						echo $tDate . " == " .date("Y-M-d", strtotime($tDate)) . " => ";
						echo $NameOfPlanets[$values['pt']] . " = " . $values['asp'] . " = " .$NameOfPlanets[$PN] ."<br />";     
						array_push($this->TopTheme, $values);
						$TopCount++;
					}
				}

				if($TopCount == 3)
					break;
			}
		}
		//print_r($this->TopTheme);
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
			$PN = sprintf("%04d", $Item['pn']);
			$AspectType = $Item['aspecttype'];
			$IsPlanet = $Item['isplanet'];
			$IsRetrograde = $Item['isretrograde'];
	
			$DT = $Item['start'];
			$start = trim( $Item['start'] );
			$end = trim( $Item['end'] );
			$IsStayInHouse = trim( $Item['isstayinhouse']);
				
			//Keep tracking for House crossing for Saturn and Jupiter
			if($IsRetrograde == 'D') {
				//echo "<br /> $PT - $PN - $DT - $IsStayInHouse <br />";
				
				array_push($this->SaturnJupiterCrossing, array("pt" => $PT,
							"asp" => $ASP,
							"pn" => $PN,
							"start" => $start, 				//$start_date,
							"end" => $end, 					//$end_date,
							"isretrograde" => $IsRetrograde,
							"isplanet" => $IsPlanet,
							"isstayinhouse" => $IsStayInHouse,
							'signname' => trim( $Item['signname'])));
			}
		}
	}

	/**
	 * PRINTING Introduction Text and Age Text
	 */
	function SetIntroductionText() {
		echo "<pre>********************************************************************************************** SetIntroductionText()</pre>";
		global $FirstSectionPageNo;
		global $Global_Language;
		global $UserAge;
		global $IntroductionText;
		global $GenericText;
		global $Introduction_Generic_Text;

		$GenericHelper = new MiniReportGenericHelper();
		$this->PrintGenericIntroduction();

		$this->GenerateGenericAgeText($GenericHelper);
		$this->GenericGrowthText();

//		$this->GenericHelper->AddSubTitle(trim($Introduction_Generic_Text[$Global_Language]["YearReportGenericText"][2]));
		/** @todo: We need to display only 3 house crossing text for both */
		$this->SaturnAndJuputerThroughHouse();	

		$this->PrintThemeText();
		$this->PrintThemeTester();
		$this->PrintGenericSummary();
		
		$this->ReportHTML = $GenericHelper->ReportHTML;		
	}

	function SaturnAndJuputerThroughHouse() {
		echo "<pre>********************************************************************** SaturnAndJuputerThroughHouse()</pre>";
		global $AbbrPlanetToFullName;
		global $Connector;
		global $AspectTypes;
		global $AspectAbbr;
		global $Global_NextYear;
		global $Global_CurrntYear;
		global $Introduction_Generic_Text;
		global $Global_Language;

		$GenericClass = new MiniReportGenericHelper();
		$IsMoving = 0;
				
		echo "SaturnJupiterCrossing == " . count($this->SaturnJupiterCrossing) . "<br />";
		
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
				//if(strtotime($InDate) >= strtotime(str_replace("-", ".", $Global_CurrntYear)) && strtotime($InDate) <= strtotime(str_replace("-", ".", $Global_NextYear))) {
				
				/**
				 * Below line is commented to becuase it's give wrong Saturn and Jupiter possition.
				 */
				//if($InDate >= $Global_CurrntYear && $InDate <= $Global_NextYear) {
				if(($InDate >= $Global_CurrntYear || $IsStayInHouse == "N") && $InDate <= $Global_NextYear) {
					
					$SignName = $TrasitingPlanet['signname'];
						
					$InDateText = sprintf("%s - %04d", $this->GenericHelper->GetMonthNameFromDate($TrasitingPlanet['start']), $this->GenericHelper->GetYearFromDate($TrasitingPlanet['start']) );
					$OutDateText = sprintf("%s - %04d", $this->GenericHelper->GetMonthNameFromDate($TrasitingPlanet['end']), $this->GenericHelper->GetYearFromDate($TrasitingPlanet['end']) );

					if($IsStayInHouse == 'Y') {
						$LastP =  intval($PlanetN) - 1;
						if($LastP == 0) {
							$LastP = 12;
						}						
						$PlanetNText = sprintf("%02d-%02d", $LastP, $PlanetN);
					}
					else {
						$PlanetN = sprintf("%02d-%02d",$PlanetN , $PlanetN);
						$PlanetNText = sprintf("%02d-%02d",$PlanetN , $PlanetN);
					}

					$FinalDescription = $this->GenericHelper->GetSaturnANDJupiterHouseText($PlanetT, $PlanetN);

					echo "SU-1----------------$IsStayInHouse - $PlanetNText - $PlanetT, $PlanetN = $SignName = [ $InDateText - ". $TrasitingPlanet['start'] . " ] == >  [ $OutDateText - ". $TrasitingPlanet['end']. " ]<br />";
					
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

					//$GenericClass->FirstPagePrintSectionDescription($this, $Description );
					$this->GenericHelper->SectionContent($this, $Description );
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
				
				//$InDate = date("Y-m-d", substr($InDate, 0, 4), substr($InDate, 5, 2), substr($InDate, 7, 2));
				$InDate = new DateTime($InDate);				
				
				if($IsBack == 'D' && $PlanetT == 'SA') {
					if($InDate >= $Global_NextYear) {
						$SignName = $TrasitingPlanet['signname'];

						$PlanetN = sprintf("%02d",  $PlanetN - 1);
						if($PlanetN == 0) {
							$PlanetN = 12;
						}

						$OutDate = $InDate;
						$InDate = $this->GetEnteredDateforSaturn($PlanetN);

						$InDateText = sprintf("%s - %04d", $this->GenericHelper->GetMonthNameFromDate($TrasitingPlanet['start']), $this->GenericHelper->GetYearFromDate($TrasitingPlanet['start']) );
						$OutDateText = sprintf("%s - %04d", $this->GenericHelper->GetMonthNameFromDate( $TrasitingPlanet['end']), $this->GenericHelper->GetYearFromDate( $TrasitingPlanet['end']) );
							
						$PlanetN = sprintf("%02d-%02d",$PlanetN , $PlanetN);
						$PlanetNText = sprintf("%02d-%02d",$PlanetN , $PlanetN);
							
						//echo "SU----------------$PlanetT, $PlanetN = $SignName = [ $InDateText - ". $TrasitingPlanet['start'] . " ] == >  [ $OutDateText - ". $TrasitingPlanet['end']. " ]<br />";							
						echo "<br />****************************** <br />SU---------------- $PlanetT, $PlanetN = $SignName = <br />";  
						echo "[ InDateText: $InDateText - StDt : ". $TrasitingPlanet['start'] . " ] == >  [ OutDateText = $OutDateText - EnDt : ". $TrasitingPlanet['end']. " ]<br />";

						$FinalDescription = $this->GenericHelper->GetSaturnANDJupiterHouseText($PlanetT, $PlanetN);
							
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
							
						//$GenericClass->FirstPagePrintSectionDescription($this, $Description );
						$this->GenericHelper->SectionContent($this, $Description );
						break;
					}
				}
			}
		}

		//Check for Jupiter House Crossing
		$this->GenericHelper->AddSubTitle(trim($Introduction_Generic_Text[$Global_Language]["YearReportGenericText"][2]));
		
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
				$SignName = $TrasitingPlanet['signname'];
				
				$InDateText = sprintf("%s - %04d", $this->GenericHelper->GetMonthNameFromDate($TrasitingPlanet['start']), $this->GenericHelper->GetYearFromDate($TrasitingPlanet['start']) );
				$OutDateText = sprintf("%s - %04d", $this->GenericHelper->GetMonthNameFromDate($TrasitingPlanet['end']), $this->GenericHelper->GetYearFromDate($TrasitingPlanet['end']) );

				$FinalDescription = $this->GenericHelper->GetSaturnANDJupiterHouseText($PlanetT, $PlanetN);

				echo "JU----------------$PlanetT, $PlanetN = $SignName = [ $InDateText - ". $TrasitingPlanet['start'] . " ] == >  [ $OutDateText - ". $TrasitingPlanet['end']. " ]<br />";
				
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

				//$GenericClass->FirstPagePrintSectionDescription($this, $Description );
				$this->GenericHelper->SectionContent($this, $Description );
				$HowMany++;

				if($HowMany > 1) {
					break;
				}
			}
		}
	}


	private function PrintGenericIntroduction() {
		global $Introduction_Generic_Text;
		global $Global_Language;
		global $UserFName;
		global $DateOfBirth;
		global $Global_CurrntYear;
		global $Global_NextYear;

		$this->GenericHelper->PrintReportName($this, $Introduction_Generic_Text[$Global_Language]["YearReportTitle"]);
		$this->GenericHelper->PrintReportName($this, sprintf('%s: %s', $UserFName, date ( "F d, Y", strtotime($DateOfBirth))) );
		//$this->GenericHelper->PrintReportName($this, " " );
		
		$this->GenericHelper->PrintReportName($this, 
				sprintf($Introduction_Generic_Text[$Global_Language]["YearReportPeriod"], 
						date ( "F d, Y", strtotime($Global_CurrntYear)),
						date ( "F d, Y", strtotime($Global_NextYear))));
		
		$DearUserText = sprintf($Introduction_Generic_Text[$Global_Language]["YearReportIntroduction"], $UserFName);
		$this->GenericHelper->SectionHeading($this, $DearUserText);

		$this->GenericHelper->SectionContent($this, $Introduction_Generic_Text[$Global_Language]["YearReportIntroductionText"][0]);
		$this->GenericHelper->SectionContent($this, $Introduction_Generic_Text[$Global_Language]["YearReportIntroductionText"][1]);
	}

	private function PrintGenericSummary() {
		global $Introduction_Generic_Text;
		global $Global_Language;

		$this->GenericHelper->AddSubTitle(trim($Introduction_Generic_Text[$Global_Language]["YearReportGenericText"][4]));
		
		$this->GenericHelper->SectionContent($this, $Introduction_Generic_Text[$Global_Language]["YearReportSummaryText"][0]);
		$UpSellLink  = sprintf('<A HREF="http://astrowow.com/buy-astrology-reading/yearly-report?upordid=%s">%s<A/>', $this->OrderId, 
						trim($Introduction_Generic_Text[$Global_Language]["YearReportSummaryText"][1]));
		$this->GenericHelper->SectionContent($this, $UpSellLink);
		
		$this->GenericHelper->SectionContent($this, $Introduction_Generic_Text[$Global_Language]["YearReportSummaryText"][2]);
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
		global $Introduction_Generic_Text;
		global $Global_Language;
		
		$GenericTextGetter = new MiniReportGenericHelper();
		
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
			
			$TesterText = $this->GenericHelper->GetTesterText($PTAbbr, $AspectID, $PNAbbr, $AspectStrength);

			if($TesterText == "" && $AspectStrength == 'P-R') {
				$AspectStrength = 'P';
				$TesterText = $this->GenericHelper->GetTesterText($PTAbbr, $AspectID, $PNAbbr, $AspectStrength);
			}

			if($TesterText != "") {
				//Setting THEME title
				//$ThemeTitle = '#' . $AbbrPlanetToFullName[$pt] . '/' . $AspectStrength . $Connector[$asp] .  $AbbrPlanetToFullName[$pn] . '_Tester';
				$ThemeTitle = "";
				//$GenericTextGetter->FirstPagePrintSectionInternalTitle($this, $ThemeTitle);
				$this->GenericHelper->SectionContent($this, $ThemeTitle);
				//Theme Tester
				//$GenericTextGetter->FirstPagePrintSectionDescription($this, sprintf("- %s",  $TesterText));
				$this->GenericHelper->SectionContent($this, sprintf("- %s",  $TesterText));
			}
		}
	}
	

	function GenerateGenericAgeText() {
		echo "<pre>********************************************************************** GenerateGenericAgeText()</pre>";
		global $Global_Language;
		global $UserAge;
		global $Global_Natal_MObject;
		global $Introduction_Generic_Text;

		
		$GenericHelper = new MiniReportGenericHelper();
	
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
		$AgeDescription = str_replace("  ", " ", $AgeDescription);
		
		//$AgeTitle = sprintf("#Age_%02d_Generic", $UserAge);
		//$GenericHelper->FirstPagePrintSectionDescription($this, $AgeDescription);
		
		$this->GenericHelper->AddSubTitle(trim($Introduction_Generic_Text[$Global_Language]["YearReportGenericText"][0]));
		$GenericHelper->SectionContent($this, $AgeDescription);
	}
	
	function GenericGrowthText(){
		global $Global_Language;
		global $GenericText;
		global $Introduction_Generic_Text;
	
		//$GenericTextGetter = new MiniReportGenericHelper();
		$this->GenericHelper->AddSubTitle(trim($Introduction_Generic_Text[$Global_Language]["YearReportGenericText"][1]));
		$this->GenericHelper->SectionContent($this, $GenericText[$Global_Language]['Growth_text']);
	}
	
	function PrintThemeText() {
		global $Global_Language;
		global $GenericText;
		global $UserAge;
		global $Introduction_Generic_Text;
	
		$GenericTextGetter = new MiniReportGenericHelper();	
		$FinalText = str_replace('[age]', sprintf($GenericText[$Global_Language]['[age]'], $UserAge), $GenericText[$Global_Language]['3THEMES_1']);
		//Theme Tester

		$this->GenericHelper->AddSubTitle(trim($Introduction_Generic_Text[$Global_Language]["YearReportGenericText"][3]));
		$GenericTextGetter->SectionContent($this, $FinalText);
	}	

	function AcceptPageBreak() {
		$this->SetCol(0);
		$this->SetLeftMargin(16); // was 10 but need a 6mm gutter
		$this->SetY(62);
		return true;		// issue a page break
	}
}