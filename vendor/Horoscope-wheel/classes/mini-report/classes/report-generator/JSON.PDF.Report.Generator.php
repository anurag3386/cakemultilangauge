<?php
/**
 * @package ReportGenerators
 * @subpackage PDF
 * @author Amit Parmar <amit@ntechcorporate.com>
 * @copyright Copyright (c) 2014-2015, World of wisdom Inc,.
 */

class PDFReportGenerator {

	var $pdf;
	var $toc;
	var $toc_xref;
	var $pagemark;
	var $pagemark_t;
	var $preamble_content;
	var $preamble_shown;

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

	var $Language;

	/**
	 * @param none
	 * @return PDF_Report
	 */
	function PDFReportGenerator( $paper_size ) {

		$this->Language = 'en';

		switch(strtoupper($paper_size)) {
			case 'A41PCOL':	/* A4, portrait, single column */
				$this->pdf = new PDF_A4();
				$generator = new PDFGenerator();
				break;
			case 'A42PCOL':	/* A4, portrait, 2 column */
				$this->pdf = new PDF_A4_2COL();
				$generator = new PDFGenerator();
				break;
			case 'US1PCOL':	/* US letter, portrait, single column */
				$this->pdf = new PDF_USLetter();
				break;
			case 'US2PCOL':	/* US letter, portrait, 2 column */
				$this->pdf = new PDF_USLetter_2COL();
				break;
			default:
				$this->pdf = new PDF_A4();
				echo "<pre>$paper_size</pre>";
				break;
		}
		
		 
		$this->pdf->FPDF();
		$this->pdf->column_width = 180;
		$this->pdf->SetAutoPageBreak(true);
		$this->pdf->Open();
		$this->pdf->AliasNbPages();
		$this->pdf->AddPage();
		$this->pdf->AddFont('taurus','','taurus.php');
		$this->pdf->AddFont('calligraphic421','','Calligraphic421.php');

		
		$this->chapter_heading_font_family	= 'arial';	//'taurus';
		$this->chapter_heading_font_weight	= '';
		$this->chapter_heading_font_size	= 16;
		$this->chapter_heading_font_colour	= array( 0x00, 0x00, 0x00 );
		$this->chapter_heading_line_height	= 10;
		$this->chapter_heading_border		= 0;
		$this->chapter_heading_align		= 'C';
		$this->chapter_heading_fill			= false;
		$this->chapter_heading_fill_colour	= array( 0x00, 0x00, 0x00 );

		$this->section_heading_font_family	= 'arial';	//'calligraphic421';
		$this->section_heading_font_weight	= '';
		$this->section_heading_font_size	= 12;
		$this->section_heading_font_colour	= array( 0x00, 0x00, 0x00 );
		$this->section_heading_line_height	= 6;
		$this->section_heading_border		= 'TB';
		$this->section_heading_align		= 'L';
		$this->section_heading_fill			= false;
		$this->section_heading_fill_colour	= array( 0x00, 0x00, 0x00 );

		$this->section_attitude_font_family	= 'arial';	//'calligraphic421';
		$this->section_attitude_font_weight	= '';
		$this->section_attitude_font_size	= 9;
		$this->section_attitude_font_colour	= array( 0x00, 0x00, 0x00 );
		$this->section_attitude_line_height	= 5;
		$this->section_attitude_border		= 0;
		$this->section_attitude_align		= 'J';
		$this->section_attitude_fill		= false;
		$this->section_attitude_fill_colour	= array( 0x00, 0xFF, 0xFF );

		$this->section_content_font_family	= 'arial';	//'calligraphic421';
		$this->section_content_font_weight	= '';
		$this->section_content_font_size	= 9;
		$this->section_content_font_colour	= array( 0x00, 0x00, 0x00 );
		$this->section_content_line_height	= 5;
		$this->section_content_border		= 0;
		$this->section_content_align		= 'J';
		$this->section_content_fill			= false;
		$this->section_content_fill_colour	= array( 0xFF, 0xFF, 0xFF );

		//		$this->Report();
		$this->preamble_content = "";
		$this->preamble_shown = false;
		$this->pagemark = array();
		$this->pagemark_t = array();
		$this->toc = array();
	}

	/**
	 * generateReportHeader
	 *
	 * generateReportHeader
	 *
	 * @param none
	 * @return void
	 */
	function ReportHeader() {
	}

	/**
	 * generateChapterHeader
	 *
	 * generateChapterHeader
	 *
	 * @param Integer chapter
	 * @return void
	 */
	function ChapterHeader( $chapter ) {
		/**
		 * @todo: We don't required the Chapter Heading Parameter. Because We are generating JSON Array
		 */
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
	function SectionHeader( $sIndex, $cIndex, $oIndex, $retrograde, $dynamic_context, $type  ) {
		/**
		 * @todo: We don't requires to generate the Section Header. Because we are generating JSON Array 
		 */
		$this->preamble_content = "";
		$this->preamble_shown = false;
	}

	function SectionSubHead( $start_date, $end_date ) {
		/**
		 * @todo: We don't requires to generate the Section Header. Because we are generating JSON Array
		 */
	}

	function RetrogradePreamble( $content ) {
		$this->preamble_content .= trim($content);
	}

	function AspectStrengthPreamble( $content ) {
		$this->preamble_content .= " " . trim($content);
	}

	function AspectTypePreamble( $content ) {
		$this->preamble_content .= " " . trim($content);
	}

	/**
	 * Generate Section Content
	 *
	 * Generate the main body of the section content. If this is a planetary
	 * aspect then I add a paragraph containing retrograde text, if applicable,
	 * the aspect strength and the aspect type. For the ascendant, planets and
	 * nodes by sign, house and aspect I include the main section content body.
	 *
	 * @access private
	 * @param string context
	 * @param string attitude
	 * @param string description
	 * @return void
	 */
	function SectionContent( $context, $attitude, $description ) {
		if( $this->preamble_content != "" && $this->preamble_shown === false ) {
			echo "<pre>PERSONAL: ".$this->preamble_content."</pre>";
			$this->Paragraph( trim($this->preamble_content) );			
			$this->preamble_shown = true;
		}
		
		/**
		 * We don't want to print the sub-title 
		 * LIKE below 
		 * :=: PERSONAL - IDENTITY: Sense of belonging.
		 * :=: PERSONAL - MENTALITY: Trust your intuition
		 */
		//$this->Attitude( $context . " - " . $attitude );
		$this->Description( $description );
	}

	function ReportTrailer() {
	}

	function Paragraph( $text ) {
		$this->Description($text);
	}

	function Attitude( $content ) {
		$this->pdf->SetFont
		(
				$this->section_attitude_font_family,
				$this->section_attitude_font_weight,
				$this->section_attitude_font_size
		);

		$this->pdf->SetTextColor
		(
				$this->section_attitude_font_colour[0],
				$this->section_attitude_font_colour[1],
				$this->section_attitude_font_colour[2]
		);
			
		$this->pdf->SetFillColor
		(
				$this->section_attitude_fill_colour[0],
				$this->section_attitude_fill_colour[1],
				$this->section_attitude_fill_colour[2]
		);

		$this->pdf->MultiCell
		(
				$this->pdf->column_width,
				$this->section_attitude_line_height,
				$content,
				/* no border */			$this->section_attitude_border,
				/* align left */		$this->section_attitude_align,
				/* transparent fill */	(($this->section_attitude_fill === true) ? 1 : 0)
		);

		$this->pdf->Ln(2);
	}

	function Description( $content ) {
		$this->pdf->SetFont
		(
				$this->section_content_font_family,
				$this->section_content_font_weight,
				$this->section_content_font_size
		);

		$this->pdf->SetTextColor
		(
				$this->section_content_font_colour[0],
				$this->section_content_font_colour[1],
				$this->section_content_font_colour[2]
		);
			
		$this->pdf->MultiCell
		(
				$this->pdf->column_width,
				$this->section_content_line_height,
				$content,
				/* no border */			$this->section_content_border,
				/* align justified */	$this->section_content_align,
				/* transparent fill */ 	(($this->section_content_fill === true) ? 1 : 0)
		);
		
		$this->pdf->Ln(2);
	}

	/**
	 * CrossReference
	 *
	 * CrossReference
	 *
	 * @param String content
	 * @return void
	 */
	function CrossReference( $sIndex, $cIndex, $oIndex, $dynamic_context ) {
		global $top_object1;
		global $top_connector;
		global $top_object2;

		/** convert from an index into a String */
		$subject	= $top_object1		[$this->Language][ $sIndex ];
		$connector	= $top_connector	[$this->Language][ $cIndex ];
		$object	    = $top_object2		[$this->Language][ $oIndex ];

		/*
		 * if the measure is object in sign or object in house then there are not
		* likely to be any juxtapositions to be considered. therefore we can just
		* set the reference directly
		*
		* If sIndex is a planet and is a chart [co]ruler then we will cenrtainly have dealt
		* with this already and so a cross reference comes in to play.
		*/
		if( $cIndex == '200' ) {
			/** PDFReportGenerator::CrossReference - object[$sIndex] in sign/house[$oIndex] **/
			/**  add conditional code here */
			$reference = sprintf("%04d-%03d-%04d", $sIndex, $cIndex, $oIndex );
		} else {
			/** with the exception of the ascendant and its rulers ...
			 * Pluto con Mars will have been previously defined as Mars con Pluto */
			$reference = sprintf("%04d-%03d-%04d",
					(($sIndex > $oIndex) ? $oIndex : $sIndex ),
					$cIndex,
					(($sIndex > $oIndex) ? $sIndex : $oIndex )
			);
		}

		/** PDFReportGenerator::CrossReference, context = $this->context, reference = $reference" */
		if( $dynamic_context == "static" /* $this->context == "static" */ ) {
			reset($this->pagemark);
			while ($pagemark = current($this->pagemark)) {
				echo "--> Comparing key(" . key($pagemark) . ") against reference($reference) for a static report\n";
				if( key($pagemark) == $reference ) {
					$pno = $pagemark[$reference];
					echo "--> Bingo, page reference = $pno\n";
				}
				next($this->pagemark);
			}
		} else {
			reset($this->pagemark_t);
			while ($pagemark = current($this->pagemark_t)) {
				echo "--> Comparing key(" . key($pagemark) . ") against reference($reference) for a dynamic report\n";
				if( key($pagemark) == $reference ) {
					$logger->debug("PDFReportGenerator::CrossReference, key = " . key($pagemark));
					$pno = $pagemark[$reference];
					echo "--> Bingo, page reference = $pno\n";
				}
				next($this->pagemark_t);
			}
		}

		/** PDFReportGenerator::CrossReference, pno = $pno" */

		if( $cIndex == '200' ) {
			$content = "Please read page " . $pno . " : $subject $connector $object";
		} else {
			if( $sIndex > $oIndex ) {
				$content = "Please read page " . $pno . " : $object $connector $subject";
			} else {
				$content = "Please read page " . $pno . " : $subject $connector $object";
			}
		}

		$this->Description($content);
	}

	/**
	 * GetTOC
	 *
	 * Returns the table of contents
	 */
	function getTOC() {
		return $this->toc;
	}

	/**
	 * IntroductionSection() Print the Generic Introduction Text at start of the Report.
	 */
	function IntroductionSection() {
		global $Introduction_Generic_Text;
		$IntroductionArray =  array();
		$IntroductionArray["Introduction"][0] = $Introduction_Generic_Text[$this->Language]["Introduction"];

		for($Index = 0; $Index < count($Introduction_Generic_Text[$this->Language]["IntroductionText"]); $Index++) {
			$IntroductionArray["Introduction"][$Index + 1] = trim($Introduction_Generic_Text[$this->Language]["IntroductionText"][$Index]);
		}
		return $IntroductionArray;
	}

	/**
	 * IntroductionSection() Print the Generic Introduction Text at start of the Report.
	 */
	function GenericIntroductionSection($PlanetNo , $SignNo) {
		global $Introduction_Generic_Text;
		global $top_object1, $top_object2;
		$PlanetGenericArray = array();
		
		$KeyName = sprintf("%s-in-sign",$top_object1[$this->Language][$PlanetNo]);
		$PlanetName = sprintf("%s",$top_object1[$this->Language][$PlanetNo]);
		$SignName = sprintf("%s",$top_object2[$this->Language][$SignNo]);
		
		$PlanetGenericArray[$KeyName]["planet"] = trim($Introduction_Generic_Text[$this->Language]["PlanetIntroductionText"][$PlanetNo][0]);
		$PlanetGenericArray[$KeyName]["title"] = sprintf(trim($Introduction_Generic_Text[$this->Language]["PlanetIntroductionText"][$PlanetNo][1]), $PlanetName, $SignName);
		$PlanetGenericArray[$KeyName]["sub-title"] = sprintf(trim($Introduction_Generic_Text[$this->Language]["PlanetIntroductionText"][$PlanetNo][2]), $PlanetName, $SignName);
		$PlanetGenericArray[$KeyName]["sign"] = trim($SignName);

		return $PlanetGenericArray;
	}

	/**
	 * GenericSummary() Print the Generic Summary Text at end of the Report.
	 */
	function GenericSummary() {
		global $Introduction_Generic_Text;
		
		$GenericSummaryArray = array();
		$GenericSummaryArray[0] = trim($Introduction_Generic_Text[$this->Language]["SummaryText"][0]);
		$GenericSummaryArray[1] = trim($Introduction_Generic_Text[$this->Language]["SummaryText"][1]);
		$GenericSummaryArray[2] = trim($Introduction_Generic_Text[$this->Language]["SummaryText"][2]);
		
		return $GenericSummaryArray;
	}
	
	/**
	 * GenericAspectIntroductionSection() Print the Generic Introduction Text at start of the Report.
	 */
	function GenericAspectIntroductionSection($PlanetNo, $Aspect, $NatalPlanetNo) {
		global $Introduction_Generic_Text;
		global $top_object1, $top_object2, $top_connector;
		$GenericAspectArray = array();
		
		$PlanetName = sprintf("%s",$top_object1[$this->Language][$PlanetNo]);
		$AspectType = sprintf("%s",$top_connector[$this->Language][$Aspect]);		
		$NatalPlanetName = sprintf("%s",$top_object2[$this->Language][$NatalPlanetNo]);
		
		$GenericAspectArray["strongest-aspect"]["title"] = trim($Introduction_Generic_Text[$this->Language]["AspectIntroductionText"][0]);
		$GenericAspectArray["strongest-aspect"]["generic-text"] = sprintf(trim($Introduction_Generic_Text[$this->Language]["AspectIntroductionText"][1]), $PlanetName, $AspectType, $NatalPlanetName);
		$GenericAspectArray["strongest-aspect"]["sub-title"] = sprintf(trim($Introduction_Generic_Text[$this->Language]["AspectIntroductionText"][2]), $PlanetName, $AspectType, $NatalPlanetName);
		$GenericAspectArray["strongest-aspect"]["planet"] = trim($PlanetName);
		$GenericAspectArray["strongest-aspect"]["aspecttype"] = trim($AspectType);
		$GenericAspectArray["strongest-aspect"]["natalplanet"] = trim($NatalPlanetName);
		
		return $GenericAspectArray;
	}
	
	/**
	 * GenericTransitIntroductionSection() Print the Generic Introduction Text at start of the Report.
	 */
	function GenericTransitIntroductionSection($CustomerName, $PlanetNo, $Aspect, $NatalPlanetNo, $StartDate, $EndDate) {
		global $Introduction_Generic_Text;
		global $top_object1, $top_object2, $top_connector;
		//echo "<pre>********** GenericAspectIntroductionSection()</pre>";

		$PlanetName = sprintf("%s",$top_object1[$this->Language][$PlanetNo]);
		$AspectType = sprintf("%s",$top_connector[$this->Language][$Aspect]);
		$NatalPlanetName = sprintf("%s",$top_object2[$this->Language][$NatalPlanetNo]);
		
		$GenericTransitArray = array();
		
		$StartDate = str_replace("-", "/", $StartDate);
		$EndDate = str_replace("-", "/", $EndDate);
				
		$GenericTransitArray["strongest-transit"]["title"] = trim($Introduction_Generic_Text[$this->Language]["TransitIntroductionText"][0]);
		$GenericTransitArray["strongest-transit"]["generic-text"] = sprintf(trim($Introduction_Generic_Text[$this->Language]["TransitIntroductionText"][1]),
															$CustomerName, $PlanetName, $AspectType, $NatalPlanetName, 
															date("M d, Y", strtotime($StartDate)), date("M d, Y", strtotime($EndDate)));
		
		$GenericTransitArray["strongest-transit"]["sub-title"] = sprintf(trim($Introduction_Generic_Text[$this->Language]["TransitIntroductionText"][2]),
																$PlanetName, $AspectType, $NatalPlanetName);
		
		$GenericTransitArray["strongest-transit"]["planet"] = trim($PlanetName);
		$GenericTransitArray["strongest-transit"]["aspecttype"] = trim($AspectType);
		$GenericTransitArray["strongest-transit"]["natalplanet"] = trim($NatalPlanetName);
		
		return $GenericTransitArray;		
	}
};