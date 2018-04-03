<?php
/**
 * Description of BermanBraunFinalBundleGeneraterPDF
 *      Creating Final bunch of the PDF files and send in Email.
 *
 * @name BermanBraunFinalBundleGeneraterPDF
 * @author Amit Parmar <parmaramit1111@gmail.com>
 * @package PDF
 * @copyright World of wisdom and Amit Parmar <parmaramit1111@gmail.com>
 *
 */
class EliteFinalBundleGeneraterPDF extends fpdi {

	//var $FranchiseName = 'BermanBraun.com';
	var $FranchiseName = 'Essential Year Report';
	var $FranchiseLink = "www.Astrowow.com";

	var $CoverPageText = '';
	var $FranchiseId = 0;
	var $PortalUserId = 0;

	var $CurrentOrderId;
	var $UserFullName;
	var $BirthDate;
	var $BirthTime;
	var $BirthPlace;
	var $Age;

	var $SupplementaryPlanetTemplate;
	var $SupplementarySignTemplate;
	var $SupplementaryHouseTemplate;
	var $SupplementaryAspectTemplate;

	var $ImportCustomCoverPage;
	
	function EliteFinalBundleGeneraterPDF() {
		global $logger;
		$logger->debug('FinalBundleGeneraterPDF() - Initialization | ');
		$this->fpdi("P", "mm", "A4");

		$this->SupplementaryPlanetTemplate = ROOTPATH.'/bin/pages/Planets.pdf';
		$this->SupplementarySignTemplate   = ROOTPATH.'/bin/pages/Signs.pdf';
		$this->SupplementaryHouseTemplate  = ROOTPATH.'/bin/pages/Houses.pdf';
		$this->SupplementaryAspectTemplate = ROOTPATH.'/bin/pages/Aspects.pdf';
		
		$this->ImportCustomCoverPage = '';
	}

	function Header() {
		parent::Header();
		global $Global_PreviousYear;				//Trancking last year data  (like 16-July-2011)
		global $Global_NextYear;					//Trancking next year data  (like 16-July-2013)
		global $Global_CurrntYear;					//Trancking current year data  -Date of birth  (like 16-July-2012)

		if($this->PageNo() > 1) {
			$this->SetRightMargin(20);
			$this->SetLeftMargin(20);
			$this->SetFont('Helvetica', 'I', 10);
			$this->SetY(10);
			$this->SetFillColor(0, 0, 0);
			$this->Cell(0, 2, 'Year report for ' . $this->UserFullName ." ($Global_CurrntYear to $Global_NextYear) " , 0, 0, 'C', 0);
			$this->SetFillColor(255, 255, 255);
			$this->Ln();

			$this->SetLineWidth(0.3);
			$this->Line(0, 15, 200, 15);
			/* set margin from bottom of the page */
			$this->SetAutoPageBreak(true, 20); /* was 30 */
		}
	}

	function Footer() {
		parent::Footer();

		if($this->PageNo() > 1) {
			$this->SetRightMargin(20);
			$this->SetLeftMargin(20);

			$this->SetFont('Helvetica', 'I', 8);
			$this->SetTextColor(0, 0, 0);
			$this->SetY(-8);
			$this->Cell(0, 3, $this->FranchiseName, 0, 0, 'L', 0);
			$this->SetY(-8);
			$this->Cell(0, 3, $this->FranchiseLink, 0, 0, 'C', 0);
			$this->SetY(-8);
			$this->Cell(0, 3, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'R', 0);

			$this->SetLineWidth(0.3);
			$this->Line(20, 288, 195, 288);
		}
	}

	function FinalBundling() {
		$this->SetFranchiseName();
		$this->ImportCoverPage();
		$this->AddWheelPage();
		$this->AdddFinalReportContentPage();
		$this->Explanation();
	}

	private function AddTableOfContent() {
		echo "<pre>********************************************************************** AddTableOfContent()</pre>";
		global $TableOfContent;
		global $finalBundlePDF;
		global $FirstSectionPageNo;

		$TotalNoPage = $finalBundlePDF->PageNo();

		if(isset($TableOfContent) && count($TableOfContent) > 0) {
			$finalBundlePDF->AddPage();
			echo "<pre>********************************************************************** AddTableOfContent()_1------</pre>";
			$finalBundlePDF->SetMargins(20, 20, 20);
			$finalBundlePDF->SetY(20);
			$finalBundlePDF->SetX(0);
				
			$this->SetFont('Helvetica','B',12);
			$this->SetTextColor(0,0,0);
				
			$finalBundlePDF->MultiCell(180, 5, strtoupper("Table of Contents"), /* no border */			0, /* align justified */	'C', /* transparent fill */ 	0);
			$this->SetFont('Helvetica','',10);
			$finalBundlePDF->Ln(5);
				
				
			$PageCellSize= $finalBundlePDF->GetStringWidth('p. '.$TableOfContent[count($TableOfContent) - 1]['PageNo']) + 2;

			for($i = 0; $i < count($TableOfContent); $i++) {
				echo "<pre>*********************** " . $i ." ***</pre>";
				//Caption
				$str = sprintf("%s. %s", $i + 1 , $TableOfContent[$i]['ChapterName']);
				$strsize = $finalBundlePDF->GetStringWidth($str);

				$avail_size= 210 - 20 - 20 - $PageCellSize - (1*8) - 4;
					
				while ($strsize>=$avail_size){
					$str=substr($str,0,-1);
					$strsize=$this->GetStringWidth($str);
				}

				//Filling dots
				$w = 210 - 20 - 20 - $PageCellSize - (1*8) - ($strsize+2);
				if($finalBundlePDF->GetStringWidth('.') > 0) {
					$nb = $w / $finalBundlePDF->GetStringWidth('.');
				} else {
					$nb = $w / 1;
				}
				$dots = str_repeat('.',$nb);

				//Page number
				$NewPage =  $TableOfContent[$i]['PageNo'];
				$NewPage = intval($NewPage ) + $TotalNoPage + 1;
				$FinalContent = $str.$dots.' p. '.$NewPage;

				$finalBundlePDF->Cell(190, $this->FontSize + 8, $FinalContent, 0, 1, 'L');
					
				if($finalBundlePDF->GetY() > 270 ) {
					$this->AddPage();
				}
			}
		}
	}

	private function AddWheelPage() {
		$ImportFileName = sprintf("%s/%d.wheel.pdf", SPOOLPATH, $this->CurrentOrderId);
		$this->ImportPageFromPDFFiles($ImportFileName);
	}

	private function AddTransitGraphPage() {
		$ImportFileName = sprintf("%s/%d.transitgraph.pdf", SPOOLPATH, $this->CurrentOrderId);
		$this->ImportPageFromPDFFiles($ImportFileName);
	}

	private function AddSolarRetunWheelPage() {
		$ImportFileName = sprintf("%s/%d.wheelSolarReturn.pdf", SPOOLPATH, $this->CurrentOrderId);
		$this->ImportPageFromPDFFiles($ImportFileName);
	}

	private function AddHoraryWheelPage() {
		$ImportFileName = sprintf("%s/%d.wheelHorary.pdf", SPOOLPATH, $this->CurrentOrderId);
		$this->ImportPageFromPDFFiles($ImportFileName);
	}

	private function AddHoraryWheelAspectContentPage() {
		$ImportFileName = sprintf("%s/%d.HoraryContent.pdf", SPOOLPATH, $this->CurrentOrderId);
		$this->ImportPageFromPDFFiles($ImportFileName);
	}

	private function AddSolarRetunAspectContentPage() {
		$ImportFileName = sprintf("%s/%d.solarReturn.pdf", SPOOLPATH, $this->CurrentOrderId);
		$this->ImportPageFromPDFFiles($ImportFileName);
	}

	private function AddProgressedWheelPage() {
		$ImportFileName = sprintf("%s/%d.wheelProgression.pdf", SPOOLPATH, $this->CurrentOrderId);
		$this->ImportPageFromPDFFiles($ImportFileName);
	}

	private function AdddFinalReportContentPage() {
		$ImportFileName = sprintf("%s/%d.FinalReport.pdf", SPOOLPATH, $this->CurrentOrderId);
		$this->ImportPageFromPDFFiles($ImportFileName);
		//$this->ImportThemeFromPDFFiles($ImportFileName);
	}


	private function AddProgressedAspectContentPage() {
		$ImportFileName = sprintf("%s/%d.ProgressionContent.pdf", SPOOLPATH, $this->CurrentOrderId);
		$this->ImportPageFromPDFFiles($ImportFileName);
	}

	private function ImportPageFromPDFFiles($ImportFileName) {
		global $finalBundlePDF;

		if (file_exists($ImportFileName)) {
			//add a page to the initiated pdf file
			$finalBundlePDF->AddPage();

			//Set the sourcefile, returns the total number of pages in the source pdf file
			$TotalPages = $finalBundlePDF->setSourceFile($ImportFileName);

			for ($currentIndex = 1; $currentIndex <= $TotalPages; $currentIndex++) {
				//Import a particular page from the source file
				$currentImportedPage = $finalBundlePDF->importPage($currentIndex);

				//create a new pdf for consecutive pages that are to be imported
				if ($currentIndex > 1) {
					$finalBundlePDF->AddPage();
				}

				// use the imported page and place it at point 0,0 with a width of 210 and height of 297 mm
				$finalBundlePDF->useTemplate($currentImportedPage, 0, 0, 210, 297);

				//Left, Top, [Right] Just because in safe side
				$finalBundlePDF->SetMargins(0.50, 0.50);
			}
		}
	}

	//This function is not working its not adding Theme file
	private function ImportThemeFromPDFFiles($ImportFileName) {
		global $finalBundlePDF;
		global $SectionPageNo;
		global $SectionName;
		global $ThemePageArray;
			
		echo "<pre>";
		print_r($SectionPageNo);
		echo "</pre>";

		$LastThemePage = $ThemePageArray['Default'];
		$LastThemeSection = 'Default';
			
		if (file_exists($ImportFileName)) {
			//add a page to the initiated pdf file
			$finalBundlePDF->AddPage();

			//Set the sourcefile, returns the total number of pages in the source pdf file
			$TotalPages = $finalBundlePDF->setSourceFile($ImportFileName);

			for ($currentIndex = 1; $currentIndex <= $TotalPages; $currentIndex++) {
				//Import a particular page from the source file
				if($currentIndex <= 2){
					$CurrentThemePage = $finalBundlePDF->setSourceFile($ThemePageArray['Default']);
					$currentImportedPage = $finalBundlePDF->importPage($CurrentThemePage, '');
				}
				else {
					if(array_key_exists($currentIndex, $SectionPageNo)) {
						echo "<pre>Its IF :: ".$SectionName[$SectionPageNo[$currentIndex]]."</pre>";
							
						$LastThemeSection = $SectionName[$SectionPageNo[$currentIndex]];
						$LastThemePage = $ThemePageArray[$SectionName[$SectionPageNo[$currentIndex]]];
						$CurrentThemePage = $finalBundlePDF->setSourceFile($LastThemePage);
						$currentImportedPage = $finalBundlePDF->importPage($CurrentThemePage, '');
					}
					else {
						$SectionBGPath = sprintf('%s_BG', $LastThemeSection);
						$SectionBGPath = $ThemePageArray[$LastThemeSection];
						$CurrentThemePage = $finalBundlePDF->setSourceFile($SectionBGPath);
						$currentImportedPage = $finalBundlePDF->importPage($CurrentThemePage, '');
					}
				}

				// use the imported page and place it at point 0,0 with a width of 210 and height of 297 mm
				$finalBundlePDF->useTemplate($currentImportedPage, 0, 0, 210, 297);

				//Left, Top, [Right] Just because in safe side
				$finalBundlePDF->SetMargins(0.50, 0.50);
			}
		}
	}

	private function ImportCoverPage() {
		$this->SetCoverPageTheme();
		$this->SetReportHeading();
		$this->SetMemberDetail();
	}

	//Setup main page after Cover Page
	private function SetCoverPageTheme() {
		global $finalBundlePDF;
		global $ThemePageArray;

		$finalBundlePDF->AddPage();
		$TotalPages = $finalBundlePDF->setSourceFile($ThemePageArray['CoverPage']);
		//		$TotalPages = $finalBundlePDF->setSourceFile(ROOTPATH . '/bin/pages/bermanbraun/BB_Cover_Essential.pdf');

		$currentImportedPage = $finalBundlePDF->importPage($TotalPages, '');

		// use the imported page and place it at point 0,0 with a width of 210 and height of 297 mm
		$finalBundlePDF->useTemplate($currentImportedPage, 0, 0, 210, 297);

		//Left, Top, [Right] Just because in safe side
		$finalBundlePDF->SetMargins(0.50, 0.50);
	}

	private function SetReportHeading() {
		global $Global_Language;
		global $GenericText;
		global $finalBundlePDF;

		$finalBundlePDF->SetLeftMargin(15);
		$finalBundlePDF->SetY(45);

		// 		$finalBundlePDF->SetFont( 'Times', '', 29);
		// 		$finalBundlePDF->SetTextColor(226, 181, 250);
		// 		$StringLenght = intval( $finalBundlePDF->GetStringWidth($GenericText[$Global_Language]['ReportAuthorName']) ) + 10;
		// 		$finalBundlePDF->MultiCell($StringLenght, 7, utf8_decode( $GenericText[$Global_Language]['ReportAuthorName'] ),  /* no border */			0, /* align justified */	'L', /* transparent fill */ 	0);
		// 		//$finalBundlePDF->Cell(0, 0, utf8_decode( $GenericText[$Global_Language]['ReportAuthorName'] ), 0, 0, 'L', 0);
		// 		$finalBundlePDF->Ln();

		$finalBundlePDF->SetFont( 'Times', '', 29);
		//$finalBundlePDF->SetTextColor(255, 255, 255);  //WHITE color
		$finalBundlePDF->SetTextColor(226, 181, 250);	// PURPLE
		$StringLenght = intval( $finalBundlePDF->GetStringWidth($GenericText[$Global_Language]['ReportTitle']) ) + 10;
		$finalBundlePDF->MultiCell($StringLenght, 6, utf8_decode( $GenericText[$Global_Language]['ReportTitle'] ),  /* no border */			0, /* align justified */	'L', /* transparent fill */ 	0);
		//$finalBundlePDF->Cell(0, 0, utf8_decode( $GenericText[$Global_Language]['ReportTitle'] ), 0, 0, 'L', 0);
		$finalBundlePDF->Ln();

		$finalBundlePDF->SetFont( 'Times', 'I', 10);
		$finalBundlePDF->SetTextColor(255, 255, 255);  //WHITE color
		$StringLenght = intval( $finalBundlePDF->GetStringWidth($GenericText[$Global_Language]['ReportDesign']) ) + 10;
		$finalBundlePDF->MultiCell($StringLenght, 5, utf8_decode( $GenericText[$Global_Language]['ReportDesign'] ),  /* no border */			0, /* align justified */	'L', /* transparent fill */ 	0);
		$finalBundlePDF->Ln();
	}

	private function SetMemberDetail() {
		global $GenericText;
		global $finalBundlePDF;
		global $Global_Language;

		$finalBundlePDF->SetFont( 'Times', '', 18);
		$finalBundlePDF->SetTextColor(226, 181, 250);
		$StringLenght = intval( $finalBundlePDF->GetStringWidth($GenericText[$Global_Language]['CoverReportForLabel']) ) + 10;
		$finalBundlePDF->MultiCell($StringLenght, 6, utf8_decode( $GenericText[$Global_Language]['CoverReportForLabel'] ),  /* no border */			0, /* align justified */	'L', /* transparent fill */ 	0);
		$finalBundlePDF->Ln();

		$finalBundlePDF->SetFont( 'Times', '', 15);
		$finalBundlePDF->SetTextColor(255, 255, 255);
		$this->UserFullName = sprintf('%s : %s',  $GenericText[$Global_Language]['CoverNameLabel'], $this->UserFullName);
		$StringLenght = intval( $finalBundlePDF->GetStringWidth($this->UserFullName) ) + 10;
		$finalBundlePDF->MultiCell($StringLenght, 5, utf8_decode( $this->UserFullName ),  /* no border */			0, /* align justified */	'L', /* transparent fill */ 	0);
		$finalBundlePDF->Ln();

		$this->BirthDate = sprintf('%s : %s',  $GenericText[$Global_Language]['CoverDateLabel'], $this->BirthDate);
		$StringLenght = intval( $finalBundlePDF->GetStringWidth($this->BirthDate) ) + 10;
		$finalBundlePDF->MultiCell($StringLenght, 5, $this->BirthDate,  /* no border */			0, /* align justified */	'L', /* transparent fill */ 	0);
		$finalBundlePDF->Ln();

		$this->BirthTime = sprintf('%s : %s',  $GenericText[$Global_Language]['CoverTimeLabel'], $this->BirthTime);
		$StringLenght = intval( $finalBundlePDF->GetStringWidth($this->BirthTime) ) + 10;
		$finalBundlePDF->MultiCell($StringLenght, 5, $this->BirthTime,  /* no border */			0, /* align justified */	'L', /* transparent fill */ 	0);
		$finalBundlePDF->Ln();

		$this->BirthPlace = sprintf('%s : %s',  $GenericText[$Global_Language]['CoverPlaceLabel'], $this->BirthPlace);
		$StringLenght = intval( $finalBundlePDF->GetStringWidth($this->BirthPlace) ) + 10;
		$finalBundlePDF->MultiCell($StringLenght, 5, $this->BirthPlace,  /* no border */			0, /* align justified */	'L', /* transparent fill */ 	0);
		$finalBundlePDF->Ln();

		global $UserAge;
		$this->Age = sprintf('%s : %s',  $GenericText[$Global_Language]['CoverAgeLabel'], $UserAge);
		$StringLenght = intval( $finalBundlePDF->GetStringWidth($this->Age) ) + 10;
		$finalBundlePDF->MultiCell($StringLenght, 5, $this->Age,  /* no border */			0, /* align justified */	'L', /* transparent fill */ 	0);
		$finalBundlePDF->Ln();
	}

	/**
	 * Setup the cover page for Affiliates
	 */
	public function SetupAffiliatePages() {
		echo "<pre>************************SetupAffiliatePages()</pre>";
		$this->ImportPageFromPDFFiles(ROOTPATH . '/bin/pages/bermanbraun/BB_Cover_Essential.pdf');
	}
		
	/**
	 * Add pages at the end of the bundle to explain some of the esoteric astrological language
	 */
	function Explanation() {
		$this->ImportPageFromPDFFiles($this->SupplementaryPlanetTemplate);
		$this->ImportPageFromPDFFiles($this->SupplementarySignTemplate);
		$this->ImportPageFromPDFFiles($this->SupplementaryHouseTemplate);
		$this->ImportPageFromPDFFiles($this->SupplementaryAspectTemplate);
	}
	
	private function SetFranchiseName() {
		//NOW Fetching the Portal Setting for Header and Footer
		global $db;
	
		$SQLQuery = "SELECT portalname, portalurl, introduction_text, cover_page_pdf FROM `elite_user_portal_settings` WHERE userid = :UserId AND portalid = :PortalId";
		$queryParams = array(':UserId' => $this->PortalUserId, ':PortalId' => $this->FranchiseId);
	
		$objDB  = $db->prepare($SQLQuery);
		$objDBResult = $objDB->execute($queryParams);
		$eLitePortalRow = $objDB->fetchAll();
			
		if($eLitePortalRow) {
			foreach ($eLitePortalRow as $CurrentPortalRow) {
				$this->FranchiseName = $CurrentPortalRow['portalname'];
				$this->FranchiseLink =  $CurrentPortalRow['portalurl'];
				$this->CoverPageText =  isset($CurrentPortalRow['introduction_text']) ? $CurrentPortalRow['introduction_text'] : "";
				
				/** 
				 * Custom PDF Cover page import feature is added on 02-Dec-2015
				 * 
				 * Importing new Cover Page Uploaded by user from "Customize Report" section.
				 */	
				if(isset($CurrentPortalRow['cover_page_pdf']) && $CurrentPortalRow['cover_page_pdf'] != "") {					
					$this->ImportCustomCoverPage = ROOTPATH.$CurrentPortalRow['cover_page_pdf'];
					$this->ImportPageFromPDFFiles($this->ImportCustomCoverPage);
				}
			}
		}
		//NOW Fetching the Portal Setting for Header and Footer
	}	
}
?>