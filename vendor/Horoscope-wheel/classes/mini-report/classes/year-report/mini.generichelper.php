<?php
class MiniReportGenericHelper extends GenericHouseAndSignFinder {

	var $chapter_heading_font_family;
	var $chapter_heading_font_weight;
	var $chapter_heading_font_size;
	var $chapter_heading_font_colour;
	var $chapter_heading_border;
	var $chapter_heading_align;
	var $chapter_heading_fill;
	var $chapter_heading_fill_colour;

	var $section_heading_font_family;
	var $section_heading_font_weight;
	var $section_heading_font_size;
	var $section_heading_font_colour;

	var $section_attitude_font_family;
	var $section_attitude_font_weight;
	var $section_attitude_font_size;
	var $section_attitude_font_colour;

	var $section_content_font_family;
	var $section_content_font_weight;
	var $section_content_font_size;
	var $section_content_font_colour;

	var $ReportHTML = '';
	
	function __construct(){
		$this->ColumnWidth = 180;

		$this->chapter_heading_font_family	= 'arial';
		$this->chapter_heading_font_weight	= 'B';
		$this->chapter_heading_font_size	= 16;
		$this->chapter_heading_font_colour	= array( 47, 56, 111 );			/** #2f386f = Dark Blue */
		$this->chapter_heading_line_height	= 10;
		$this->chapter_heading_border		= 0;
		$this->chapter_heading_align		= 'L';
		$this->chapter_heading_fill			= false;
		$this->chapter_heading_fill_colour	= array( 0x00, 0x00, 0x00 );

		$this->section_heading_font_family	= 'arial';
		$this->section_heading_font_weight	= 'B';
		$this->section_heading_font_size	= 14;
		$this->section_heading_font_colour	= array( 60, 60, 59 );		/** #3c3c3b = Dark Gray */
		$this->section_heading_line_height	= 7;
		$this->section_heading_border		= 'TB';
		$this->section_heading_align		= 'L';
		$this->section_heading_fill			= false;
		$this->section_heading_fill_colour	= array( 0x00, 0x00, 0x00 );

		$this->section_attitude_font_family	= 'arial';
		$this->section_attitude_font_weight	= '';
		$this->section_attitude_font_size	= 14;
		$this->section_attitude_font_colour	= array( 60, 60, 59 ); 	/** #3c3c3b = Dark Gray */
		$this->section_attitude_line_height	= 7;
		$this->section_attitude_border		= 0;
		$this->section_attitude_align		= 'J';
		$this->section_attitude_fill		= false;
		$this->section_attitude_fill_colour	= array( 0x00, 0xFF, 0xFF );

		$this->section_content_font_family	= 'arial';
		$this->section_content_font_weight	= '';
		$this->section_content_font_size	= 12;
		$this->section_content_font_colour	= array( 60, 60, 59 ); 	/** #3c3c3b = Dark Gray */
		$this->section_content_line_height	= 7;
		$this->section_content_border		= 0;
		$this->section_content_align		= 'J';
		$this->section_content_fill			= false;
		$this->section_content_fill_colour	= array( 0xFF, 0xFF, 0xFF );
		
		$this->ReportHTML = '<style>
				h1 { color: #2f386f; font-family: arial; font-size: 28pt; line-height: 1em; font-weight:bold; vertical-align: baseline; }
				p.subHeading { color: #3c3c3b; font-family: arial; font-size: 20pt; font-weight:bold; line-height: 1em; vertical-align: baseline; }
				p.sectionContent { color: #9d9d9c; font-family: arial; font-size: 14pt; font-weight: 600; line-height: 1em; vertical-align: baseline; }
				</style>';
	}

	function FirstPagePrintSectionInternalTitle($PDFClass, $Content) {
		if($Content != "") {
			$PDFClass->SetTextColor(
					$this->section_heading_font_colour[0],
					$this->section_heading_font_colour[1],
					$this->section_heading_font_colour[2]
			);
			$PDFClass->SetFont(
					$this->section_heading_font_family,
					$this->section_heading_font_weight,
					$this->section_heading_font_size
			);
			$PDFClass->SetFillColor(
					$this->section_heading_font_colour[0],
					$this->section_heading_font_colour[1],
					$this->section_heading_font_colour[2]
			);			
			
// 			$PDFClass->SetFont( 'arial', 'B', 11);
// 			$PDFClass->SetTextColor( $GLOBALS['ContentColor'][0], $GLOBALS['ContentColor'][1], $GLOBALS['ContentColor'][2]);
// 			$PDFClass->SetFillColor( 0xcc, 0xcc, 0xcc );

			$PDFClass->MultiCell($this->ColumnWidth, 2, $Content,  /* no border */			0, /* align justified */	'L', /* transparent fill */ 	0);

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
			$PDFClass->MultiCell($this->ColumnWidth, 5, trim($Content),  /* no border */			0, /* align justified */	'L', /* transparent fill */ 	0);

			if($AnnotationNo != "" && intval($AnnotationNo) >= 0) {
				$Link = $PDFClass->AddLink();
				$PDFClass->subWrite(5, $AnnotationNo, $Link, 6, 4);
			}

			$PDFClass->Ln(2);
		}
	}

	function FirstPagePrintSectionDescription($PDFClass, $Content, $AnnotationNo = '', $Link = '') {
		if($Content != "") {
			$PDFClass->SetTextColor(
					$this->section_heading_font_colour[0],
					$this->section_heading_font_colour[1],
					$this->section_heading_font_colour[2]
			);
			$PDFClass->SetFont(
					$this->section_heading_font_family,
					$this->section_heading_font_weight,
					$this->section_heading_font_size
			);
			$PDFClass->SetFillColor(
					$this->section_heading_font_colour[0],
					$this->section_heading_font_colour[1],
					$this->section_heading_font_colour[2]
			);

			$PDFClass->MultiCell($this->ColumnWidth, 5, trim($Content),  /* no border */			0, /* align justified */	'L', /* transparent fill */ 	0);

			$PDFClass->Ln(2);
		}
	}


	function PrintReportName($PDFClass, $Content, $AnnotationNo = '', $Link = '') {
		if($Content != "") {
			$PDFClass->SetTextColor(
					$this->chapter_heading_font_colour[0],
					$this->chapter_heading_font_colour[1],
					$this->chapter_heading_font_colour[2]
			);
			$PDFClass->SetFont(
					$this->chapter_heading_font_family,
					$this->chapter_heading_font_weight,
					$this->chapter_heading_font_size
			);
			$PDFClass->SetFillColor(
					$this->chapter_heading_font_colour[0],
					$this->chapter_heading_font_colour[1],
					$this->chapter_heading_font_colour[2]
			);

			$PDFClass->MultiCell($this->ColumnWidth, 7, trim($Content),  /* no border */			0, /* align justified */	'L', /* transparent fill */ 	0);
			
			$this->ReportHTML .= $this->AddHeading(trim($Content));
		}
	}
	
	function SectionHeading($PDFClass, $Content, $AnnotationNo = '', $Link = '') {
		if($Content != "") {
			$PDFClass->SetTextColor(
					$this->section_heading_font_colour[0],
					$this->section_heading_font_colour[1],
					$this->section_heading_font_colour[2]
			);
			$PDFClass->SetFont(
					$this->section_heading_font_family,
					$this->section_heading_font_weight,
					$this->section_heading_font_size
			);
			$PDFClass->SetFillColor(
					$this->section_heading_font_colour[0],
					$this->section_heading_font_colour[1],
					$this->section_heading_font_colour[2]
			);
	
			$PDFClass->MultiCell($this->ColumnWidth, 7, trim($Content),  /* no border */			0, /* align justified */	'L', /* transparent fill */ 	0);
		
			$this->ReportHTML .= $this->AddSubHeading(trim($Content));
		}
	}
	
	function SectionContent($PDFClass, $Content, $AnnotationNo = '', $Link = '') {
		if($Content != "") {
			$PDFClass->SetTextColor(
					$this->section_content_font_colour[0],
					$this->section_content_font_colour[1],
					$this->section_content_font_colour[2]
			);
			$PDFClass->SetFont(
					$this->section_content_font_family,
					$this->section_content_font_weight,
					$this->section_content_font_size
			);
			$PDFClass->SetFillColor(
					$this->section_content_font_colour[0],
					$this->section_content_font_colour[1],
					$this->section_content_font_colour[2]
			);
	
			$PDFClass->MultiCell($this->ColumnWidth, 6, trim($Content),  /* no border */			0, /* align justified */	'L', /* transparent fill */ 	0);
			$PDFClass->Ln(2);
			
			$this->ReportHTML .= $this->AddSection(trim($Content));
		}
	}
	
	public function AddHeading($Content) {
		
		$ReturnHTML = "";
		$ReturnHTML = sprintf("<h1>%s</h1>", $Content);
		
		global $ReportHTMLGlobal;
		$ReportHTMLGlobal .= $ReturnHTML;
		
		return $ReturnHTML;
	}
	
	public function AddSubHeading($Content) {
		$ReturnHTML = "";
		//$ReturnHTML = sprintf("<p class='subHeading'><strong>%s</strong></p>", $Content);
		$ReturnHTML = sprintf("<h2>%s</h2>", $Content);
		
		global $ReportHTMLGlobal;
		$ReportHTMLGlobal .= $ReturnHTML;
		
		return $ReturnHTML;
	}	

	public function AddSubTitle($Content) {
		$ReturnHTML = "";
    	//$ReturnHTML = sprintf("<h3><strong>%s</strong></h3>", $Content);
    	$ReturnHTML = sprintf("<h3>%s</h3>", $Content);
	
		global $ReportHTMLGlobal;
		$ReportHTMLGlobal .= $ReturnHTML;
		return $ReturnHTML;
	}
	
	public function AddSection($Content) {
		$ReturnHTML = "";
		//$ReturnHTML = sprintf("<p class='sectionContent'>%s</p>", $Content);
		$ReturnHTML = sprintf("<h4>%s</h4>", $Content);
		
		global $ReportHTMLGlobal;
		$ReportHTMLGlobal .= $ReturnHTML;
		
		return $ReturnHTML;
	}	
	

	function GetNatalSignAndHousePosition($PlanetAbbrName) {
		global $Global_Natal_MObject;
		global $AbbrPlanetToFullName;
		global $HouseCuspLongitude;
		
		$ArraySignHouse = array();
		//Fetching Full name of the Planet
		$PlanetName = $AbbrPlanetToFullName[$PlanetAbbrName];
	
		if($PlanetAbbrName == 'NN' || $PlanetAbbrName == 'SN' ){
			$PlanetName = str_replace('.', '', $PlanetName);
		}
		
		//Now Checking Planet House and Sign
		if(array_key_exists($PlanetName, $Global_Natal_MObject)) {
			$signno = (int) ($Global_Natal_MObject[$PlanetName]['longitude'] / 30.0);
			
			if(array_key_exists($signno, $HouseCuspLongitude) ){
				$ArraySignHouse = array(
						'house' => sprintf("%02d", $HouseCuspLongitude[$signno] + 1),
						'sign' => $AbbrPlanetToFullName[sprintf("%04d", $signno + 101)]);	
			}
			else {			
				$ArraySignHouse = array(
					'house' => sprintf("%02d", $Global_Natal_MObject[$PlanetName]['house']),
					'sign' => $AbbrPlanetToFullName[sprintf("%04d", $Global_Natal_MObject[$PlanetName]['sign'] + 101)]);
			}
		}
	
		return $ArraySignHouse;
	}
}


class MyTCPDF extends TCPDF {
	//Page header
	public function Header() {
	}

	// Page footer
	public function Footer() {
		// Position at 15 mm from bottom
		$this->SetY(-15);
		// Set font
		$this->SetFont('helvetica', 'I', 8);
		$this->setColor('text', 157, 157, 156);

		if($this->PageNo() > 1) {
			// Company Name
			//$this->Cell(50, 10, 'AstroWOW Inc,.', 0, false, 'L', 0, '', 0, false, 'T', 'M');
			$this->Cell(50, 10, '', 0, false, 'L', 0, '', 0, false, 'T', 'M');

			// Site link
			$this->Cell(50, 10, 'AstroWOW.com', 0, false, 'C', 0, '', 0, false, 'T', 'M');

			// Page number
			$this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
		}
	}	

	public function SetCoverPage($ReportLang = 'en'){
		$this->AddPage();
	
		// get the current page break margin
		$bMargin = $this->getBreakMargin();
		// get current auto-page-break mode
		$auto_page_break = $this->getAutoPageBreak();
		// disable auto-page-break
		$this->SetAutoPageBreak(false, 0);
	
		// set bacground image
		if($ReportLang == 'en') {
			$img_file = ROOTPATH . '/bin/pages/mini-reports/year-ahead-report-cover-en.jpg';
		} else {
			$img_file = ROOTPATH . '/bin/pages/mini-reports/year-ahead-report-cover-dk.jpg';
		}
			
		$this->Image($img_file, 0, 0, 210, 297, '', '', '', false, 600, '', false, false, 0);
	
		// restore auto-page-break status
		$this->SetAutoPageBreak($auto_page_break, $bMargin);
		// set the starting point for the page content
		$this->setPageMark();
	}
	
}


class RandR_TCPDF extends TCPDF {

	var $ReportType = 'personal';

	var $FranchiseHolder = "R & R Music";
	var $FranchiseWebsite = "randrmusic.com";

	public function __construct($orientation='P', $unit='mm', $format='A4', $unicode=true, $encoding='UTF-8', $diskcache=false, $pdfa=false)
	{
		parent::__construct($orientation, $unit, $format, $unicode, $encoding, $diskcache, $pdfa);

		// set header and footer fonts
		$this->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$this->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

		// set default monospaced font
		$this->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		// set margins
		$this->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$this->SetHeaderMargin(PDF_MARGIN_HEADER);
		$this->SetFooterMargin(PDF_MARGIN_FOOTER);

		// set auto page breaks
		$this->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);					// set font
		$this->SetFont('dejavusans', '', 14, '', true);
	}

	public function SetFranchise($CFranchiseHolder, $CFranchiseWebsite) {
		$this->FranchiseHolder = $CFranchiseHolder;
		$this->FranchiseWebsite= $CFranchiseWebsite;
	}

	public function SetReportType ($_CurrentReportType){
		$this->ReportType = $_CurrentReportType;
	}

	public function SetCoverPage(){
		$this->AddPage();

		// get the current page break margin
		$bMargin = $this->getBreakMargin();
		// get current auto-page-break mode
		$auto_page_break = $this->getAutoPageBreak();
		// disable auto-page-break
		$this->SetAutoPageBreak(false, 0);

		// set bacground image
		if($this->ReportType == "personal") {
			$img_file = ROOTPATH . '/bin/pages/mini-reports/r-and-r/Birth_report_cover.jpg';
		} else if($this->ReportType == "year") {
			$img_file = ROOTPATH . '/bin/pages/mini-reports/r-and-r/Year_report_cover.jpg';
		} else {
			$img_file = ROOTPATH . '/bin/pages/mini-reports/r-and-r/Calendar_report_cover.jpg';
		}

		$this->Image($img_file, 0, 0, 210, 297, '', '', '', false, 600, '', false, false, 0);

		// restore auto-page-break status
		$this->SetAutoPageBreak($auto_page_break, $bMargin);
		// set the starting point for the page content
		$this->setPageMark();
	}

	public function PrintReportContent($ReportHTML) {
		$this->AddPage();
		$this->writeHTML( $ReportHTML, true, false, true, false, '');
		$this->lastPage();
	}

	//Page header
	public function Header() {
	}

	// Page footer
	public function Footer() {
		// Position at 15 mm from bottom
		$this->SetY(-15);
		// Set font
		$this->SetFont('helvetica', 'I', 8);
		$this->setColor('text', 157, 157, 156);

		if($this->PageNo() > 1) {
			// Company Name
			$this->Cell(50, 10, $this->FranchiseHolder, 0, false, 'L', 0, '', 0, false, 'T', 'M');

			// Site link
			$this->Cell(50, 10, $this->FranchiseWebsite, 0, false, 'C', 0, '', 0, false, 'T', 'M');

			// Page number
			$this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
		}
	}
}