<?php
class WOW_TCPDF extends TCPDF {

	var $ReportType = 'personal';

	var $FranchiseHolder = "AstroWOW Inc,.";
	var $FranchiseWebsite = "Astrowow.com";
	var $ReportLanguage = 'en';

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

	public function SetReportLanguage ($_CurrentReportLanguage){
		$this->ReportLanguage = $_CurrentReportLanguage;
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
		echo $this->ReportType . " ->ReportType <br />";
		if($this->ReportType == "personal") {
			$img_file = ROOTPATH . '/bin/pages/mini-reports/character-report-cover-'.$this->ReportLanguage.'.jpg';
			
		} else if($this->ReportType == "year") {
			$img_file = ROOTPATH . '/bin/pages/mini-reports/year-ahead-report-cover-'.$this->ReportLanguage.'.jpg';
		} else if($this->ReportType == "seasonal") {
			$img_file = ROOTPATH . '/bin/pages/mini-reports/astrology-calendar-report-'.$this->ReportLanguage.'.jpg';
		} else {
			$img_file = ROOTPATH . '/bin/pages/mini-reports/character-report-cover-'.$this->ReportLanguage.'.jpg';
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