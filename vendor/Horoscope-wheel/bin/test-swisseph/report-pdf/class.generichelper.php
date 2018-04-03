<?php
/**
 *
 * Its Common class to hold all common function. Its also usefull to retrive the Solar return house and Natal house position
 * and also Text from the Databases.
 *
 * @name: WOW Astrology
 * @package: WOW Astrology
 * @author: Amit Parmar <parmaramit1111@gmail.com>
 *
 * @copyright WOW Inc,. <ard@world-of-wisdom.com>
 *
 */

class GenericHouseAndSignFinder {

	function GetSolarReturnSignAndHousePosition($PlanetAbbrName) {
		global $Global_Solar_MObject;
		global $AbbrPlanetToFullName;
		$ArraySignHouse = array();
		//Fetching Full name of the Planet
		$PlanetName = str_replace('.', '', $AbbrPlanetToFullName[$PlanetAbbrName]);
		
		//Now Checking Planet House and Sign
		if(array_key_exists($PlanetName, $Global_Solar_MObject)){
			$ArraySignHouse = array(
								'house' => sprintf("%02d", $Global_Solar_MObject[$PlanetName]['house']),
								'sign' => $AbbrPlanetToFullName[sprintf("%04d", $Global_Solar_MObject[$PlanetName]['sign'] + 101)]);
		}
		return $ArraySignHouse;
	}

	function GetNatalSignAndHousePosition($PlanetAbbrName) {
		global $Global_Natal_MObject;
		global $AbbrPlanetToFullName;
		$ArraySignHouse = array();
		//Fetching Full name of the Planet		
		$PlanetName = $AbbrPlanetToFullName[$PlanetAbbrName];
				
		if($PlanetAbbrName == 'NN' || $PlanetAbbrName == 'SN' ){
			$PlanetName = str_replace('.', '', $PlanetName);
		}
		
		//Now Checking Planet House and Sign
		if(array_key_exists($PlanetName, $Global_Natal_MObject)){
			$ArraySignHouse = array(
								'house' => sprintf("%02d", $Global_Natal_MObject[$PlanetName]['house']),
								'sign' => $AbbrPlanetToFullName[sprintf("%04d", $Global_Natal_MObject[$PlanetName]['sign'] + 101)]);
		}		
		return $ArraySignHouse;
	}
	
	function GetPrograssedPlanetSignAndHousePosition($PlanetAbbrName) {
		global $Global_Progression_MObject;
		global $AbbrPlanetToFullName;
		$ArraySignHouse = array();
		//Fetching Full name of the Planet
		$PlanetName = $AbbrPlanetToFullName[$PlanetAbbrName];
	
		//Now Checking Planet House and Sign
		if(array_key_exists($PlanetName, $Global_Progression_MObject)){
			$ArraySignHouse = array(
					'house' => sprintf("%02d", $Global_Progression_MObject[$PlanetName]['house']),
					'sign' => $AbbrPlanetToFullName[sprintf("%04d", $Global_Progression_MObject[$PlanetName]['sign'] + 101)]);
		}
		return $ArraySignHouse;
	}
	
	/**
	 * 
	 * @param string $PlanetAbbrName
	 * @return ArrayObject
	 */
	function GetPrograssedPlanetDegree($PlanetAbbrName) {
		global $Global_Progression_MObject;
		global $AbbrPlanetToFullName;
		$ArraySignHouse = array();
		//Fetching Full name of the Planet
		$PlanetName = $AbbrPlanetToFullName[$PlanetAbbrName];
		
		//Now Checking Planet House and Sign
		if(array_key_exists($PlanetName, $Global_Progression_MObject)){
			$ArraySignHouse = array(
					'longitude' => sprintf("%s", $Global_Progression_MObject[$PlanetName]['longitude']),
					'retrograde' => sprintf("%s", $Global_Progression_MObject[$PlanetName]['retrograde']),
					'house' => sprintf("%02d", $Global_Progression_MObject[$PlanetName]['house']),
					'sign' => $AbbrPlanetToFullName[sprintf("%04d", $Global_Progression_MObject[$PlanetName]['sign'] + 101)]);
		}
		return $ArraySignHouse;
	}

	function SplitParamFinderArray($ParamFinder){
		$FinalArray = preg_split("/(,)/i", $ParamFinder);
		return $FinalArray;
	}

	function GetAbbrNameFromParamFinder($ParamFinder) {
		return substr($ParamFinder, 1, 2);
	}

	/**
	 * GetMonthNameFromDate function will return the Name of Month from the given date. The Name will be Language specific.
	 * Date formate Must be [YYYY-MM-DD]
	 * @param $SearchingDate
	 */
	function GetMonthNameFromDate($SearchingDate) {
		if(isset($SearchingDate) && $SearchingDate != '') {
			global $MonthNameForContent;
			global $Global_Language;
			
			$MonthNumber = substr($SearchingDate, 5, 2);		
			return $MonthNameForContent[$Global_Language][$MonthNumber];
		}
		else {
			return '';
		}
	}
	
	function GetFullDateWithMonth($SearchingDate) {
		$FinalDate = $this->GetMonthNameFromDate($SearchingDate);
		$FinalDate = sprintf($FinalDate. ' %02d,', $this->GetDayFromDate($SearchingDate));
		$FinalDate = sprintf($FinalDate. ' %04d', $this->GetYearFromDate($SearchingDate)); 
		return $FinalDate;
	}

	/**
	 * GetYearFromDate function will return the Year from the given date.
	 * Date formate Must be [YYYY-MM-DD]
	 * @param $SearchingDate
	 */
	function GetYearFromDate($SearchingDate) {
		return substr($SearchingDate, 0, 4);
	}
	
	/**
	 * GetYearFromDate function will return the Day from the given date.
	 * Date formate Must be [YYYY-MM-DD] 
	 * @param string $SearchingDate
	 */
	function GetDayFromDate($SearchingDate){
		return substr($SearchingDate, 9, 2);
	}

	function IsSign($ParamFinder) {
		if (preg_match("(_sign_)", $ParamFinder)) {
			return true;
		}
		else {
			return false;
		}
	}

	function GetHouseAndSignText($SplitedArray){
		$PlanetAbbr = '';
		$House = '';
		$Sign = '';
		$NewArray;
		for($Index = 0; $Index < count($SplitedArray); $Index++) {
			$PlanetAbbr = $this->GetAbbrNameFromParamFinder($SplitedArray[$Index]);
			$SignHouse = $this->GetNatalSignAndHousePosition($PlanetAbbr);
			
			if($this->IsSign($SplitedArray[$Index]) ==  true) {
				$Sign = $SignHouse['sign'];
				$NewArray[$SplitedArray[$Index]] = $this->GetMergingAgeText($PlanetAbbr, $Sign, 'AGE');
			}
			else {
				$House =  $SignHouse['house'];
				$NewArray[$SplitedArray[$Index]] = $this->GetMergingAgeText($PlanetAbbr, $House, 'AGE');
			}
		}
		return $NewArray;
	}

	function GetMergingAgeText($PlaneName, $SignHouseName, $AspectStrength) {
		global $YBookText;
		$Description = '';
		$YBookTextList = $YBookText->GetList(
						array(
							array('planet_code1', '=', $PlaneName),
							array('aspect_id', '=', 'CON'),
							array('planet_code12', '=', $SignHouseName),
							array('aspect_type', '=', $AspectStrength)
						)
				);

		foreach ($YBookTextList as $sItem) {
			$Description = strlen(trim($sItem->description)) > 0  ? trim( $sItem->description ) : '';			
		}
		return $Description;
	}

	function GetTesterText($PlaneName, $AspectType = 'CON', $SignHouseName, $AspectStrength = 'T') {
		global $YBookText;

		$TesterText = '';
		$YBookTextList = $YBookText->GetList(
			array(
				array('planet_code1', '=', $PlaneName),
				array('aspect_id', '=', $AspectType),
				array('planet_code12', '=', $SignHouseName),
				array('aspect_type', '=', $AspectStrength)
			)
		);
				
		foreach ($YBookTextList as $sItem) {
			$TesterText = strlen(trim($sItem->tester_text)) > 0  ? trim( $sItem->tester_text ) : '';
		}

		return $TesterText;
	}
	
	function GetTesterTextNextYear($ChapterNo, $PlanetCode1, $AspectType = 'CON', $PlanetCode2, $AspectStrength = 'T') {
		global $YBookText;
	
		$TesterText = '';
		$YBookTextList = $YBookText->GetList(
				array(
					array('chapter_id', '=', $ChapterNo),
					array('planet_code1', '=', $PlanetCode1),
					array('aspect_id', '=', $AspectType),
					array('planet_code12', '=', $PlanetCode2),
					array('aspect_type', '=', $AspectStrength)
				)
		);
	
		foreach ($YBookTextList as $sItem) {
			$TesterText = strlen(trim($sItem->tester_text)) > 0  ? trim( $sItem->tester_text ) : '';
		}
	
		return $TesterText;
	}
	
	function GetShortText($ChapterNo, $PlanetCode1, $AspectType = 'CON', $PlanetCode2, $AspectStrength = 'T') {
		global $YBookText;

		$ShortText = '';
		$YBookTextList = $YBookText->GetList(
			array(
				array('chapter_id', '=', $ChapterNo),
				array('planet_code1', '=', $PlanetCode1),
				array('aspect_id', '=', $AspectType),
				array('planet_code12', '=', $PlanetCode2),
				array('aspect_type', '=', $AspectStrength)
			)
		);

		foreach ($YBookTextList as $sItem) {
			$ShortText = strlen(trim($sItem->short_text)) > 0  ? '- ' . trim( $sItem->short_text ) : '';
		}
		return $ShortText;
	}
	
	function GetPlanetDirectAndRetrogradeText($ChapterNo, $PlanetCode1, $AspectType = 'CON', $PlanetCode2, $AspectStrength = 'T', $PlanetDirection) {
		global $YBookText;
	
		$Description = '';
		$YBookTextList = $YBookText->GetList(
				array(
						array('chapter_id', '=', $ChapterNo),
						array('planet_code1', '=', $PlanetCode1),
						array('aspect_id', '=', $AspectType),
						array('planet_code12', '=', $PlanetCode2),
						array('aspect_type', '=', $AspectStrength),
						array('planet_direction', '=', $PlanetDirection)
				)
		);
	
		foreach ($YBookTextList as $sItem) {			
			$Description = strlen(trim($sItem->description)) > 0  ? trim( $sItem->description ) : ''; 
		}
		return $Description;
	}

	/**
	 * Retrive the Text for the Saturn and Jupite planet and return Description and replacing parameter array
	 *
	 * @param $PlanetCode   [Planet Code]
	 * @param $HouseNo		[House Number]
	 * @return [ array("DescriptionText" => $DescriptionText, 'FindingPararm' => $SplitedArray) ]
	 */
	function GetSaturnANDJupiterHouseText($PlanetCode, $HouseNo){
		global $YBookText;
		$FindingPararm;
		$DescriptionText = '';

		$YBookTextList = $YBookText->GetList(
								array(
									array('chapter_id', '=', '10008'),
									array('planet_code1', '=', $PlanetCode),
									array('aspect_id', '=', 'CON'),
									array('planet_code12', '=', $HouseNo),
									array('aspect_type', '=', 'T')
								)
							);

		foreach ($YBookTextList as $sItem) {
			$DescriptionText = strlen(trim($sItem->description)) > 0  ? trim( $sItem->description ) : '';
			$FindingPararm = $sItem->find_param;
		}

		$SplitedArray = $this->SplitParamFinderArray($FindingPararm);
		return array("DescriptionText" => $DescriptionText, 'FindingPararm' => $SplitedArray);
	}

	/**
	 * GetSectionName() function return the Name of the section. Its Language Specific
	 * @param $SectionNo
	 */
	function GetSectionName($SectionNo) {
		global $Global_Language;
		global $chapterHeadings;

		return $chapterHeadings[$Global_Language][$SectionNo];
	}

	/**
	 * GetSectionIntroductionText() function find the text for the Section Introductioin. Its Language specific.
	 * @param $FindTextFor  [ Planet code ]
	 * @return Text or string
	 */
	function GetSectionIntroductionText($FindTextFor) {
		global $Global_Language;
		global $GenericText;
		global $AbbrPlanetToFullName;

		return $GenericText[$Global_Language][$AbbrPlanetToFullName[$FindTextFor] . '_general'];
	}

	/**
	 * GetSectionGenericProgressedText() function find the text for the Progression Introductioin. Its Language specific.
	 * @param $FindTextFor  [ Planet code ]
	 * @return Text or string
	 */
	function GetSectionGenericProgressedText($FindTextFor) {
		global $Global_Language;
		global $GenericText;
		global $AbbrPlanetToFullName;

		return $GenericText[$Global_Language]['Progressed_' . $AbbrPlanetToFullName[$FindTextFor]];
	}

	/**
	 * GetSectionGenericTransitsText() function find the text for the Transit Introductioin. Its Language specific.
	 * @param $FindTextFor  [ Planet code ]
	 * @return Text or string
	 */
	function GetSectionGenericTransitsText($FindTextFor){
		global $Global_Language;
		global $GenericText;
		global $AbbrPlanetToFullName;

		return $GenericText[$Global_Language]['Transits_' . $AbbrPlanetToFullName[$FindTextFor]];
	}

	/**
	 * GetSectionGenericSummaryText() function find the text for the Transit Introductioin. Its Language specific.
	 * @param $FindTextFor  [ Planet code ]
	 * @return Text or string
	 */
	function GetSectionGenericSummaryText($FindTextFor) {
		global $Global_Language;
		global $GenericText;
		global $AbbrPlanetToFullName;

		return $GenericText[$Global_Language]['Summary_' . $AbbrPlanetToFullName[$FindTextFor]];
	}
	
	/**
	 * GetCollectiveSectionGeneral() function find the text for the Transit Introductioin. Its Language specific.
	 * @return Text or string
	 */
	function GetCollectiveSectionGeneral(){
		global $Global_Language;
		global $GenericText;
		global $AbbrPlanetToFullName;
	
		return $GenericText[$Global_Language]['Outer_Planets_general'];
	}

	/**
	 * GetSectionGenericTransitsText() function find the text for the Transit Introductioin. Its Language specific.
	 * @param $FindTextFor  [ Planet code ]
	 * @return Text or string
	 */
	function GetGenericPositiveNegitiveText($GetMe){
		global $Global_Language;
		global $GenericText;
		global $AbbrPlanetToFullName;
		IF($GetME == 'Negative'){
			return $GenericText[$Global_Language]['Negative'];
		}
		else{
			return $GenericText[$Global_Language]['Positive'];
		}
	}
	
	function GetTextFromDB($ChapterNo, $PlanetCode1, $PlanetCode2, $AspectType, $AspectIdentifier) {
		global $YBookText;
		$FindingPararm;
		$DescriptionArray = array();

		$YBookTextList = $YBookText->GetList(
							array(
								array('chapter_id', '=', $ChapterNo),
								array('planet_code1', '=', $PlanetCode1),
								array('aspect_id', '=', $AspectType),
								array('planet_code12', '=', $PlanetCode2),
								array('aspect_type', '=', $AspectIdentifier)
							)
						);

		foreach ($YBookTextList as $sItem) {
			array_push($DescriptionArray, array('description' => strlen(trim($sItem->description)) > 0  ? trim( $sItem->description ) : '',
											'title' => strlen(trim($sItem->title)) > 0  ? trim( $sItem->title ) : '',
											'short_text' => strlen(trim($sItem->short_text)) > 0  ? '- ' . trim( $sItem->short_text ) : '',
											'tester_text' => strlen(trim($sItem->tester_text)) > 0  ? trim( $sItem->tester_text ) : '',
											'chapter_name' => strlen(trim($sItem->chapter_name)) > 0  ? trim( $sItem->chapter_name ) : '',
											'find_param' => $this->SplitParamFinderArray($sItem->find_param),
				)
			);
		}

		return $DescriptionArray;
	}	

	function PrintIntroctionTitle($PDFClass, $Content) {
		if($Content != "") {
			$PDFClass->SetFont( 'arial', 'B', 17);	
			//$PDFClass->SetTextColor( $GLOBALS['SectionHeadingColor'][0], $GLOBALS['SectionHeadingColor'][1], $GLOBALS['SectionHeadingColor'][2]);
			$PDFClass->SetFillColor( 255, 255, 255 );
				
			//$PDFClass->SetY(50);
			$StrLen = intval( $PDFClass->GetStringWidth($Content) ) + 10;
			$PDFClass->MultiCell(180, 7, $Content,  /* no border */			0, /* align justified */	'C', /* transparent fill */ 	0);
			$PDFClass->Ln();
		}
	}
	
	function PrintChapterHeader($PDFClass, $Content) {
		if($Content != "") {
			$PDFClass->SetFont( 'arial', 'B', 16);
	    	//$PDFClass->SetTextColor( 0x00, 0x00, 0x00 );
	    	//$PDFClass->SetFillColor( 0xcc, 0xcc, 0xcc );
	    	
			$PDFClass->SetTextColor( $GLOBALS['SectionHeadingColor'][0], $GLOBALS['SectionHeadingColor'][1], $GLOBALS['SectionHeadingColor'][2]);
			$PDFClass->SetFillColor( 255, 255, 255 );
			
			$PDFClass->SetY(50);
			$StrLen = intval( $PDFClass->GetStringWidth($Content) ) + 10;		
			$PDFClass->MultiCell(180, 7, $Content,  /* no border */			0, /* align justified */	'L', /* transparent fill */ 	0);
	    	//$PDFClass->Write(5, $Content);
			$PDFClass->Ln();
		}
	}
	
	function PrintSectionInternalTitle($PDFClass, $Content) {
		if($Content != "") {
			$PDFClass->SetFont( 'arial', 'B', 11);
	    	//$PDFClass->SetTextColor( 0x00, 0x00, 0x00 );
			//$PDFClass->SetFillColor( 0xcc, 0xcc, 0xcc );
			
			$PDFClass->SetTextColor( $GLOBALS['ContentColor'][0], $GLOBALS['ContentColor'][1], $GLOBALS['ContentColor'][2]);
			$PDFClass->SetFillColor( 0xcc, 0xcc, 0xcc );
	    	
			$PDFClass->MultiCell(180, 2, $Content,  /* no border */			0, /* align justified */	'L', /* transparent fill */ 	0);
	    	//$PDFClass->Write(2, $Content);
			$PDFClass->Ln();
		}
	}
	
	function PrintSectionDescription($PDFClass, $Content, $AnnotationNo = '', $Link = '') {	 
		global $SectionPageNo;
		$RightMargin = 20;
		if($Content != "") {
			$PDFClass->SetFont( 'arial', '', 10);
			$PDFClass->SetFillColor( 0xFF, 0xFF, 0xFF );
			$PDFClass->SetTextColor( 0x00, 0x00, 0x00 );		   	
			 
			if(count($SectionPageNo) > 0 && is_array($SectionPageNo)) {
				$LastPageNo = intval(end(array_keys($SectionPageNo))) + 1;
				if($LastPageNo == $PDFClass->PageNo()) {
					$CurrentY = $PDFClass->GetY();
					if($CurrentY >= 50 && $CurrentY <= 180 ){
						$RightMargin = 85;
					}
				}
			}
			
			$PDFClass->SetRightMargin($RightMargin);
			
			//$PDFClass->MultiCell(180, 5, $Content,  /* no border */			0, /* align justified */	'L', /* transparent fill */ 	0);		
	    	$PDFClass->SetX(20);
			$PDFClass->Write(5, $Content);
			
			if($AnnotationNo != "" && intval($AnnotationNo) >= 0) {
				$Link = $PDFClass->AddLink();
				$PDFClass->subWrite(5, $AnnotationNo, $Link, 6, 4);			
			}
			$PDFClass->Ln(7);
		}
	}
	
	function PrintAnnotation($PDFClass, $Content, $Link) {
		$PDFClass->SetFont( 'arial', '', 10);
		$PDFClass->SetTextColor( 0x00, 0x00, 0x00 );
		$PDFClass->SetFillColor( 0xcc, 0xcc, 0xcc );
		
		$lnk = $PDFClass->AddLink();
		
		$PDFClass->SetLink($Link, $PDFClass->GetY(), $PDFClass->PageNo());
		//$PDFClass->Write(5, $Content, $Link);
		
		$PDFClass->MultiCell(180, 2, $Content,  /* no border */			0, /* align justified */	'L', /* transparent fill */ 	0);		
		$PDFClass->Ln();
	}
	
	/**
	 * GetRulerPlanetSignCode() function to Get the House Ruler sign text
	 * @param String $RularPlanet
	 * @param Array $CheckInGlobalArray
	 */
	function GetRulerPlanetSignCode($RularPlanet, $CheckInGlobalArray){
		global $NameOfPlanets;
		global $AbbrPlanetToFullName;		
		$SingNo = '';
		$FinalCode = '';
		if(array_key_exists($RularPlanet, $NameOfPlanets) ) {
			if($RularPlanet == '1012'){
				$SingNo = intval($CheckInGlobalArray[0] / 30.0);
			}
			else {
				$SingNo = intval($CheckInGlobalArray[9] / 30.0);
			}
				$SingNo = sprintf('%04d', intval($SingNo) + 101);				
				$FinalCode = sprintf('%s-%s', $AbbrPlanetToFullName[$RularPlanet], $AbbrPlanetToFullName[$SingNo]);
		}		
		return $FinalCode;
	}

	function SetNewSectionBG($PDF, $PageNo, $ChapterName) {
		global $ThemePageArray;
		$pagecount = $PDF->setSourceFile($ThemePageArray[$ChapterName]);
		$tplidx = $PDF->importPage(1, '');
		$PDF->useTemplate($tplidx, 0, 0, 210, 297);
	}
	
	function SetPageBG($PDF, $PageNo, $ChapterName) {
		global $ThemePageArray;
		if($PageNo != $PDF->PageNo()) {
			$pagecount = $PDF->setSourceFile($ThemePageArray[$ChapterName]);
			$tplidx = $PDF->importPage(1, '');
			$PDF->useTemplate($tplidx, 0, 0, 210, 297);
		}
	}
	


	function min_mod() {
		$args = func_get_args();
		if(is_array($args)) {
			//if (!count($args[0]))
			if (!count($args))
				return false;
			else {
				$min = false;
//				foreach ($args[0] AS $value) {
				foreach ($args AS $value) {				
					if (is_numeric($value)) {
						$curval = floatval($value);
						if ($curval < $min || $min === false)
							if ($curval > -1)
							$min = $curval;
					}
				}
			}
		}
		else{
			return false;
		}
	
		return $min;
	}
	
	function max_mod() {
		$args = func_get_args();
		if(is_array($args)) {
			//if (!count($args[0]))
			if (!count($args))
				return false;
			else {
				$max = false;
				//foreach ($args[0] AS $value) {
				foreach ($args AS $value) {
					if (is_numeric($value)) {
						$curval = floatval($value);
						if ($curval > $max || $max === false)
							$max = $curval;
					}
				}
			}
		}
		else {
			return false;
		}
	
		return $max;
	}
	
}
?>