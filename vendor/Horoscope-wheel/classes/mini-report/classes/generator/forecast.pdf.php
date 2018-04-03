<?php
/*
 * File  : $id$
 * Author: Andy Gray <andy.gray@astro-consulting.co.uk>
 * 
 * Description
 * 
 * Modification History
 * $log$
 * 
*/

/**
 * @package Generators
 * @subpackage PDF
 * @author Andy Gray <andy.gray@astro-consulting.co.uk>
 * @copyright Copyright (c) 2005-2008, Andy Gray
 */
class PDF_Forecast extends Forecast {

    var $pdf;
    var $preamble_content;
    var $preamble_shown;

    var $chapter_heading_font_family;
    var $chapter_heading_font_weight;
    var $chapter_heading_font_size;
    var $chapter_heading_font_colour;
    var $chapter_heading_line_height;
    var $chapter_heading_border;
    var $chapter_heading_align;
    var $chapter_heading_fill;
    var $chapter_heading_fill_colour;

    var $section_heading_font_family;
    var $section_heading_font_weight;
    var $section_heading_font_size;
    var $section_heading_font_colour;
    var $section_heading_line_height;
    var $section_heading_border;
    var $section_heading_align;
    var $section_heading_fill;
    var $section_heading_fill_colour;

    var $section_content_font_family;
    var $section_content_font_weight;
    var $section_content_font_size;
    var $section_content_font_colour;
    var $section_content_line_height;
    var $section_content_border;
    var $section_content_align;
    var $section_content_fill;
    var $section_content_fill_colour;

    var $ReportHTML = '';

    /**
     * @param none
     * @return PDF_Report
     */
    function PDF_Forecast() {

        global $logger;
        $logger->debug("PDF_Forecast::PDF_Forecast - entering");
        $logger->debug("PDF_Forecast::PDF_Forecast - creating A4 2COL object");
        $this->pdf = new PDF_A4_2COL();
        $logger->debug("PDF_Forecast::PDF_Forecast - creating FPDF object");
        $this->pdf->FPDF();
        $this->pdf->Open();
        $this->pdf->AliasNbPages();
        $this->pdf->AddPage();

        $this->chapter_heading_font_family	= 'arial';
        $this->chapter_heading_font_weight	= 'B';
        $this->chapter_heading_font_size	= 16;
        //$this->chapter_heading_font_colour	= array( 0x00, 0x00, 0x00 );
        $this->chapter_heading_font_colour	= array( 47, 56, 111 );			/** #2f386f = Dark Blue */
        $this->chapter_heading_line_height	= 8;
        $this->chapter_heading_border		= 0;
        $this->chapter_heading_align		= 'L';
        $this->chapter_heading_fill			= false;
        $this->chapter_heading_fill_colour	= array( 0x00, 0xFF, 0x00 );

        $this->section_heading_font_family	= 'arial';
        //$this->section_heading_font_weight	= 'I';
        $this->section_heading_font_weight	= '';
        $this->section_heading_font_size	= 14;
        //$this->section_heading_font_colour	= array( 0x00, 0x00, 0x00 );
        $this->section_heading_font_colour	= array( 60, 60, 59 );		/** #3c3c3b = Dark Gray */
        $this->section_heading_line_height	= 7;
        $this->section_heading_border		= 0;
        $this->section_heading_align		= 'L';
        $this->section_heading_fill			= false;
        $this->section_heading_fill_colour	= array( 0x00, 0xFF, 0x00 );

        $this->section_content_font_family	= 'arial';
        $this->section_content_font_weight	= '';
        $this->section_content_font_size	= 14;
        //$this->section_content_font_colour	= array( 0x00, 0x00, 0x00 );
        $this->section_content_font_colour	= array( 60, 60, 59 ); 	/** #3c3c3b = Dark Gray */
        $this->section_content_line_height	= 6;
        $this->section_content_border		= 0;
        $this->section_content_align		= 'L';
        $this->section_content_fill			= false;
        $this->section_content_fill_colour	= array( 0xFF, 0xFF, 0xFF );

        $logger->debug("PDF_Forecast::PDF_Forecast - calling parent constructor");
        $this->Forecast();
        $this->preamble_content = "";
        $this->preamble_shown = false;
        $this->pagemark = array();
        
        $this->ReportHTML = '<style>
				h1 {
							color: #2f386f;
							font-family: arial;
							font-size: 28pt;
							line-height: 1em;
							font-weight:bold;
							vertical-align: baseline;
				}
							p.subHeading {
							color: #3c3c3b;
							font-family: arial;
							font-size: 20pt;
							font-weight:bold;
							line-height: 1em;
							vertical-align: baseline;
				}
							p.sectionContent {
							color: #9d9d9c;
							font-family: arial;
							font-size: 14pt;
							font-weight: 600;
							line-height: 1em;
							vertical-align: baseline;
				}
				</style>';
        
        $this->ReportHTML = '<style>
				h1 { color: #2f386f; font-family: arial; font-size: 20pt; font-weight:bold; vertical-align: baseline;
					line-height: normal; margin: 0; padding: 0 }
				h2 { color: #3c3c3b; font-family: arial; font-size: 16pt; font-weight: 800;
    				vertical-align: baseline; line-height: normal; margin: 0px 0px 0px 0px; padding: 0px 0px 0px 0px; }
				h3 { color: #3c3c3b; font-family: arial; font-size: 16pt; font-weight: 600;
    				vertical-align: baseline; line-height: 19pt; margin: 0px 0px 0px 0px; padding: 0px 0px 0px 0px; }
				h4 { color: #3c3c3b; font-family: arial; font-size: 14pt; font-weight: 400;
    				vertical-align: baseline; line-height: normal; margin: 0px 0px 0px 0px; padding: 0px 0px 0px 0px; }
				h5 { color: #3c3c3b; font-family: arial; font-size: 14pt; font-weight: 400;
    				vertical-align: baseline; line-height: normal; margin: 0px 0px 0px 0px; padding: 0px 0px 0px 0px; }
				h6 { color: #3c3c3b; font-family: arial; font-size: 14pt; font-weight: 400;
    				vertical-align: baseline; line-height: normal; margin: 0px 0px 0px 0px; padding: 0px 0px 0px 0px; }
				.subHeading { color: #3C3C3B; font-family: arial; font-size: 18pt; font-weight:800; vertical-align: baseline;
							line-height: normal; margin: 0px 0px 0px 0px; padding: 0px 0px 0px 0px; }
				.sectionContent { color: #9d9d9c; font-family: arial; font-size: 14pt; font-weight: 400; vertical-align: baseline;
							line-height: normal; margin: 0px 0px 0px 0px; padding: 0px 0px 0px 0px; }
				</style>';
        
        $logger->debug("PDF_Forecast::PDF_Forecast - leaving");
    }

    /**
     * generateReportHeader
     *
     * generateReportHeader
     *
     * @param none
     * @return void
     */
    function generateReportHeader() {
    }

    /**
     * generateChapterHeader
     *
     * generateChapterHeader
     *
     * @param Integer chapter
     * @return void
     */
    function generateSectionHeaderDate( $datetime ) {

        $this->pdf->Bookmark($datetime,0,-1);

        $this->pdf->SetFont(
                $this->chapter_heading_font_family,
                $this->chapter_heading_font_weight,
                $this->chapter_heading_font_size
        );

        $this->pdf->SetTextColor(
                $this->chapter_heading_font_colour[0],
                $this->chapter_heading_font_colour[1],
                $this->chapter_heading_font_colour[2]
        );

        $this->pdf->SetFillColor(
                $this->chapter_heading_fill_colour[0],
                $this->chapter_heading_fill_colour[1],
                $this->chapter_heading_fill_colour[2]
        );

        $this->pdf->MultiCell(
                $this->pdf->column_width,
                $this->chapter_heading_line_height,
                $datetime,
                $this->chapter_heading_border,
                $this->chapter_heading_align,
                (($this->chapter_heading_fill === true) ? 1 : 0)
        );
        $this->pdf->Ln(1);
        
        $this->ReportHTML .= $this->AddSubTitle(trim($datetime));
    }

    /**
     * generateSectionHeader
     *
     * generateSectionHeader
     *
     * @param String subject
     * @param String connector
     * @param String object
     * @param Boolean retrograde
     * @return void
     */
    function generateSectionTitle( $text  ) {

        //$this->pdf->Bookmark($text,1,-1);

        $this->pdf->SetFont(
                $this->section_heading_font_family,
                $this->section_heading_font_weight,
                $this->section_heading_font_size
        );

        $this->pdf->SetTextColor(
                $this->section_heading_font_colour[0],
                $this->section_heading_font_colour[1],
                $this->section_heading_font_colour[2]
        );

        $this->pdf->MultiCell(
                $this->pdf->column_width,
                $this->section_heading_line_height,
                trim($text),
                /* top/bottom border */	$this->section_heading_border,
                /* align left */		$this->section_heading_align,
                /* transparent fill */	(($this->section_heading_fill === true) ? 1 : 0)
        );
        $this->pdf->Ln(2);
        $this->ReportHTML .= $this->AddSectionTitle(trim($text));
    }

    function generateSectionContent( $text ) {
        $this->Paragraph( trim($text) );
    }

    function generateSectionContentQuestion( $question ) {
        $this->Paragraph( trim($question) );
    }

    function generateSectionContentAnswer( $answer ) {
        $this->Paragraph( trim($answer) );
    }

    function Paragraph( $text ) {

        $this->pdf->SetFont(
                $this->section_content_font_family,
                $this->section_content_font_weight,
                $this->section_content_font_size
        );

        $this->pdf->SetTextColor(
                $this->section_content_font_colour[0],
                $this->section_content_font_colour[1],
                $this->section_content_font_colour[2]
        );

        $this->pdf->MultiCell(
                $this->pdf->column_width,
                $this->section_content_line_height,
                trim($text),
                /* no border */			$this->section_content_border,
                /* align justified */	$this->section_content_align,
                /* transparent fill */ 	(($this->section_content_fill === true) ? 1 : 0)
        );
        $this->pdf->Ln(2);
        
        $this->ReportHTML .= $this->AddSection(trim($text));
    }    

    function generateQuestionContent( $text ) {
    	
    	$this->pdf->Ln(1);
    	$this->pdf->SetFont(
    			$this->section_heading_font_family,
    			"B",
    			$this->section_heading_font_size
    	);
    	
    	$this->pdf->SetTextColor(
    			$this->section_heading_font_colour[0],
    			$this->section_heading_font_colour[1],
    			$this->section_heading_font_colour[2]
    	);
    	
    	$this->pdf->MultiCell(
    			$this->pdf->column_width,
    			$this->section_heading_line_height,
    			trim($text),
    			/* top/bottom border */	$this->section_heading_border,
    			/* align left */		$this->section_heading_align,
    			/* transparent fill */	(($this->section_heading_fill === true) ? 1 : 0)
    	);
    	$this->pdf->Ln(2);
    	$this->ReportHTML .= $this->AddSubHeading(trim($text));
    }    

    public function AddHeading($Content) {
    	$ReturnHTML = "";
    	$ReturnHTML = sprintf("<h1>%s</h1>", $Content);
    	return $ReturnHTML;
    }
    
    public function AddSubHeading($Content) {
    	$ReturnHTML = "";
    	$ReturnHTML = sprintf("<h2 style='margin: 10px 0px 0px 0px; padding: 0px;'><strong>%s</strong></h2>", $Content);    	
    	return $ReturnHTML;
    }
    
    public function AddSubTitle($Content) {
    	$ReturnHTML = "";
    	$ReturnHTML = sprintf("<h3><strong>%s</strong></h3>", $Content);
    	return $ReturnHTML;
    }
    
    public function AddSection($Content) {
    	$ReturnHTML = "";
    	$ReturnHTML = sprintf("<h6>%s</h6>", $Content);    	
    	return $ReturnHTML;
    }
    
    public function AddSectionTitle($Content) {
    	$ReturnHTML = "";
    	$ReturnHTML = sprintf("<h6><i>%s</i></h6>", $Content);
    	return $ReturnHTML;
    }
}
?>
