<?php

/**
 * Description of FinalBundleGeneraterPDF
 *      Creating Final bunch of the PDF files and send in Email.
 *
 * @name FinalBundleGeneraterPDF
 * @author Amit Parmar <parmaramit1111@gmail.com>
 * @package PDF
 * @copyright World of wisdom and Amit Parmar <parmaramit1111@gmail.com>
 */
class FinalBundleGeneraterPDF extends fpdi {

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
	
	function FinalBundleGeneraterPDF() {
		global $logger;
		$logger->debug('FinalBundleGeneraterPDF() - Initialization | ');

		$this->fpdi("P", "mm", "A4");
		
		$this->SupplementaryPlanetTemplate = ROOTPATH.'/bin/pages/Planets.pdf';
		$this->SupplementarySignTemplate   = ROOTPATH.'/bin/pages/Signs.pdf';
		$this->SupplementaryHouseTemplate  = ROOTPATH.'/bin/pages/Houses.pdf';
		$this->SupplementaryAspectTemplate = ROOTPATH.'/bin/pages/Aspects.pdf';
	}

	function Header() {
		parent::Header();
		global $Global_PreviousYear;				//Trancking last year data  (like 16-July-2011)
		global $Global_NextYear;					//Trancking next year data  (like 16-July-2013)
		global $Global_CurrntYear;					//Trancking current year data  -Date of birth  (like 16-July-2012)
		$this->SetRightMargin(20);
		$this->SetLeftMargin(20);
		$this->SetFont('Helvetica', 'I', 10);
		$this->SetY(10);
		$this->SetFillColor(0, 0, 0);
		$this->Cell(0, 2, 'Year report for ' . $this->UserFullName ." ($Global_CurrntYear to $Global_NextYear) " , 0, 0, 'C', 0);
		$this->SetFillColor(255, 255, 255);
		$this->Ln();

		$this->SetLineWidth(0.3);
		$this->Line(20, 15, 200, 15);
		/* set margin from bottom of the page */
		$this->SetAutoPageBreak(true, 20); /* was 30 */
	}

	function Footer() {
		parent::Footer();

		$this->SetRightMargin(20);
		$this->SetLeftMargin(20);

		$this->SetFont('Helvetica', 'I', 8);
		$this->SetTextColor(0, 0, 0);
		$this->SetY(-8);
		$this->Cell(0, 3, 'World of Wisdom', 0, 0, 'L', 0);
		$this->SetY(-8);
		$this->Cell(0, 3, 'www.world-of-wisdom.com', 0, 0, 'C', 0);
		$this->SetY(-8);
		$this->Cell(0, 3, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'R', 0);

		$this->SetLineWidth(0.3);
		$this->Line(20, 288, 195, 288);
	}

	function FinalBundling() {
		global $logger;
		$logger->debug('FinalBundleGeneraterPDF::FinalBundling()  - Creating Bundle | ');
		
		/** Adding Newly design Cover Page **/
		$this->ImportNewCoverPage();
		
		$this->ImportCoverPage();
		$this->AddWheelPage();
//        $this->AddTransitGraphPage();
//		$this->AddSolarRetunWheelPage();
//        $this->AddSolarRetunAspectContentPage();
//        $this->AddHoraryWheelPage();
//        $this->AddHoraryWheelAspectContentPage();
//        $this->AddProgressedWheelPage();
//        $this->AddProgressedAspectContentPage();
		$this->AdddFinalReportContentPage();
		$this->Explanation();
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

	private function AddWheelPage() {
		global $logger;
		$logger->debug('FinalBundleGeneraterPDF::AddWheelPage()  - Importing Wheel Page | ');

		$ImportFileName = sprintf("%s/%d.wheel.pdf", SPOOLPATH, $this->CurrentOrderId);
		$this->ImportPageFromPDFFiles($ImportFileName);

		$logger->debug('FinalBundleGeneraterPDF::AddWheelPage()  - Leaving AddWheelPage() | ');
	}

	private function AddTransitGraphPage() {
		global $logger;
		$logger->debug('FinalBundleGeneraterPDF::AddTransitGraphPage()  - Importing Transit graph page | ');

		$ImportFileName = sprintf("%s/%d.transitgraph.pdf", SPOOLPATH, $this->CurrentOrderId);
		$this->ImportPageFromPDFFiles($ImportFileName);

		$logger->debug('FinalBundleGeneraterPDF::AddTransitGraphPage()  - Leaving AddTransitGraphPage() | ');
	}

	private function AddSolarRetunWheelPage() {
		global $logger;
		$logger->debug('FinalBundleGeneraterPDF::AddSolarRetunWheelPage()  - Importing Solar Return Wheel Page | ');
			
		$ImportFileName = sprintf("%s/%d.wheelSolarReturn.pdf", SPOOLPATH, $this->CurrentOrderId);
		$this->ImportPageFromPDFFiles($ImportFileName);
			
		$logger->debug('FinalBundleGeneraterPDF::AddSolarRetunWheelPage()  - Leaving AddSolarRetunWheelPage() | ');
	}

	private function AddHoraryWheelPage() {
		global $logger;
		$logger->debug('FinalBundleGeneraterPDF::AddHoraryWheelPage()  - Importing Horary Wheel Page | ');

		$ImportFileName = sprintf("%s/%d.wheelHorary.pdf", SPOOLPATH, $this->CurrentOrderId);
		$this->ImportPageFromPDFFiles($ImportFileName);

		$logger->debug('FinalBundleGeneraterPDF::AddHoraryWheelPage()  - Leaving AddHoraryWheelPage() | ');
	}

	private function AddHoraryWheelAspectContentPage() {
		global $logger;
		$logger->debug('FinalBundleGeneraterPDF::AddHoraryWheelAspectContentPage()  - Importing Horary Aspect Content Page | ');

		$ImportFileName = sprintf("%s/%d.HoraryContent.pdf", SPOOLPATH, $this->CurrentOrderId);
		$this->ImportPageFromPDFFiles($ImportFileName);

		$logger->debug('FinalBundleGeneraterPDF::AddHoraryWheelAspectContentPage()  - Leaving AddHoraryWheelAspectContentPage() | ');
	}

	private function AddSolarRetunAspectContentPage() {
		global $logger;
		$logger->debug('FinalBundleGeneraterPDF::AddSolarReturnPage()  - Importing Solar return page | ');

		$ImportFileName = sprintf("%s/%d.solarReturn.pdf", SPOOLPATH, $this->CurrentOrderId);
		$this->ImportPageFromPDFFiles($ImportFileName);

		$logger->debug('FinalBundleGeneraterPDF::AddSolarReturnPage()  - Leaving AddSolarReturnPage() | ');
	}

	private function AddProgressedWheelPage() {
		global $logger;
		$logger->debug('FinalBundleGeneraterPDF::AddProgressedWheelPage()  - Importing Progressed Wheel Page | ');

		$ImportFileName = sprintf("%s/%d.wheelProgression.pdf", SPOOLPATH, $this->CurrentOrderId);
		$this->ImportPageFromPDFFiles($ImportFileName);

		$logger->debug('FinalBundleGeneraterPDF::AddProgressedWheelPage()  - Leaving AddProgressedWheelPage() | ');
	}

	private function AdddFinalReportContentPage() {
		global $logger;
		$logger->debug('FinalBundleGeneraterPDF::AdddFinalReportContentPage()  - Importing Introduction Page | ');

		$ImportFileName = sprintf("%s/%d.FinalReport.pdf", SPOOLPATH, $this->CurrentOrderId);
		$this->ImportPageFromPDFFiles($ImportFileName);
		//$this->ImportThemeFromPDFFiles($ImportFileName);
			
		$logger->debug('FinalBundleGeneraterPDF::AdddFinalReportContentPage()  - Leaving AdddFinalReportContentPage() | ');
	}


	private function AddProgressedAspectContentPage() {
		global $logger;
		$logger->debug('FinalBundleGeneraterPDF::AddProgressedAspectContentPage()  - Importing Horary Aspect Content Page | ');

		$ImportFileName = sprintf("%s/%d.ProgressionContent.pdf", SPOOLPATH, $this->CurrentOrderId);
		$this->ImportPageFromPDFFiles($ImportFileName);

		$logger->debug('FinalBundleGeneraterPDF::AddProgressedAspectContentPage()  - Leaving AddProgressedAspectContentPage() | ');
	}

	private function ImportPageFromPDFFiles($ImportFileName) {
		global $finalBundlePDF;
		global $logger;
		$logger->debug('Col8_FPDI::Import_Page - entering');

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
		global $logger;
		$logger->debug('ImportThemeFromPDFFiles - entering');
			
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
	
	private function SetCoverPageTheme(){
		echo "<pre>************************SetCoverPageTheme()</pre>";
		global $finalBundlePDF;
		global $ThemePageArray;
		
		$finalBundlePDF->AddPage();
		$TotalPages = $finalBundlePDF->setSourceFile($ThemePageArray['CoverPage']);
		
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
	 * ImportNewCoverPage()
	 * Adding newly design Cover Pages.
	 */
	private function ImportNewCoverPage() {
		global $Global_Language;
	
		if($Global_Language == "en") {
			$ImportFileName = ROOTPATH. "/var/reports/covers/year-ahead-en.pdf";
			echo $ImportFileName  . " -------- .";
			$this->ImportPageFromPDFFiles($ImportFileName);
		} else if($Global_Language == "dk") {
			$ImportFileName = ROOTPATH. "/var/reports/covers/year-ahead-dk.pdf";
			echo $ImportFileName  . " -------- .";
			$this->ImportPageFromPDFFiles($ImportFileName);
		} else {
			echo "No File Found -------- .";
		}
	}
}
?>