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

	var $ReportHTML = '';

	/**
	 * @param none
	 * @return PDF_Report
	 */
	function PDFReportGenerator( $paper_size ) {

		$this->Language = 'en';
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

		$this->pdf->FPDF('P','mm','A4');

		$this->pdf->column_width = 180 ;
		$this->pdf->SetAutoPageBreak(true);

		$this->pdf->Open();
		$this->pdf->AliasNbPages();
		$this->pdf->AddPage();

		$this->pdf->AddFont('taurus','','taurus.php');
		$this->pdf->AddFont('calligraphic421','','Calligraphic421.php');

		$this->chapter_heading_font_family	= 'arial';	//'taurus';
		$this->chapter_heading_font_weight	= 'B';
		$this->chapter_heading_font_size	= 26;
		//$this->chapter_heading_font_colour	= array( 0x00, 0x00, 0x00 );
		$this->chapter_heading_font_colour	= array( 47, 56, 111 );			/** #2f386f = Dark Blue */
		$this->chapter_heading_line_height	= 20;
		$this->chapter_heading_border		= 0;
		$this->chapter_heading_align		= 'L';
		$this->chapter_heading_fill			= false;
		$this->chapter_heading_fill_colour	= array( 0x00, 0x00, 0x00 );

		$this->section_heading_font_family	= 'arial';	//'calligraphic421';
		$this->section_heading_font_weight	= 'B';
		$this->section_heading_font_size	= 16;
		//$this->section_heading_font_colour	= array( 0x00, 0x00, 0x00 );
		$this->section_heading_font_colour	= array( 60, 60, 59 );		/** #3c3c3b = Dark Gray */
		$this->section_heading_line_height	= 15;
		$this->section_heading_border		= 'TB';
		$this->section_heading_align		= 'L';
		$this->section_heading_fill			= false;
		$this->section_heading_fill_colour	= array( 0x00, 0x00, 0x00 );

		$this->section_attitude_font_family	= 'arial';	//'calligraphic421';
		$this->section_attitude_font_weight	= '';
		$this->section_attitude_font_size	= 16;
		//$this->section_attitude_font_colour	= array( 0x00, 0x00, 0x00 );
		$this->section_attitude_font_colour	= array( 60, 60, 59 ); 	/** #3c3c3b = Dark Gray */
		$this->section_attitude_line_height	= 7;
		$this->section_attitude_border		= 0;
		$this->section_attitude_align		= 'J';
		$this->section_attitude_fill		= false;
		$this->section_attitude_fill_colour	= array( 0x00, 0xFF, 0xFF );

		$this->section_content_font_family	= 'arial';	//'calligraphic421';
		$this->section_content_font_weight	= '';
		$this->section_content_font_size	= 14;
		$this->section_content_font_colour	= array( 60, 60, 59 ); 	/** #3c3c3b = Dark Gray */
		$this->section_content_line_height	= 7;
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
	function ReportHeader($ContextText = "") {
		$this->pdf->SetFont
		(
				$this->chapter_heading_font_family,
				$this->chapter_heading_font_weight,
				$this->chapter_heading_font_size
		);

		$this->pdf->SetTextColor
		(
				$this->chapter_heading_font_colour[0],
				$this->chapter_heading_font_colour[1],
				$this->chapter_heading_font_colour[2]
		);

		$this->pdf->SetFillColor
		(
				$this->chapter_heading_fill_colour[0],
				$this->chapter_heading_fill_colour[1],
				$this->chapter_heading_fill_colour[2]
		);

		$this->pdf->MultiCell
		(
				$this->pdf->column_width,
				$this->chapter_heading_line_height,
				$ContextText,
				$this->chapter_heading_border,
				$this->chapter_heading_align,
				(($this->chapter_heading_fill === true) ? 1 : 0)
		);

		$this->ReportHTML .= $this->AddHeading($ContextText);

		$this->pdf->Ln(2);
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
		/**  Add a bookmark within the PDF document */
		//$this->pdf->Bookmark( $chapter, 0, -1 );

		/** Add a bookmark within the table of contents */
		array_push ( $this->toc, array ( "chapter" => $chapter, "page" => $this->pdf->PageNo() ) );

		$this->pdf->SetFont
		(
				$this->chapter_heading_font_family,
				$this->chapter_heading_font_weight,
				$this->chapter_heading_font_size
		);

		$this->pdf->SetTextColor
		(
				$this->chapter_heading_font_colour[0],
				$this->chapter_heading_font_colour[1],
				$this->chapter_heading_font_colour[2]
		);

		$this->pdf->SetFillColor
		(
				$this->chapter_heading_fill_colour[0],
				$this->chapter_heading_fill_colour[1],
				$this->chapter_heading_fill_colour[2]
		);

		/**
		 * at this point look at the page position and determine whether the chapter
		 * heading and a section heading with subheading will fit before the end of
		 * the page is reached. If it is not possible, then this needs to be floated
		 * to the start of the next page
		 *
		 * spec required is
		 * - chapter_heading_line_height +
		 * - section_heading_line_height +
		 * - section_attitude_line_height +
		 * - section_content_line_height
		 *
		 * also be mindful of the page foot offset
		*/
		/**
		 * We dont want to print the Section Heading
		 $this->pdf->MultiCell
		 (
		 $this->pdf->column_width,
		 $this->chapter_heading_line_height,
		 $chapter,
		 $this->chapter_heading_border,
		 $this->chapter_heading_align,
		 (($this->chapter_heading_fill === true) ? 1 : 0)
		 );
		 $this->pdf->Ln(2);
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

		global $logger;
		global $top_object1;
		global $top_connector;
		global $top_object2;

		/** convert from an index into a String */
		$subject	= $top_object1		[$this->Language][ $sIndex ];
		$connector	= $top_connector	[$this->Language][ $cIndex ];
		$object	    = $top_object2		[$this->Language][ $oIndex ];

		/**
		 * All section headers are bookmarked in the table of contents so that
		 * I can cross reference where duplication exists. In all cases the
		 * bookmark should be in ascending planetary order with the exception
		 * of the ascendant which is not cross referenced
		 *
		 * Dev Note - this needs to take into account the encoding used for
		 * transiting aspects
		 */

		/**
		 * New developer note
		 * - what is the context
		 * --> static (TODO)
		 * ----> can we fit the section header + 1st line here? No, then float
		 * --> dynamic (TODO)
		 * ----> can we fit the section header, subheader, graph and 1st line? No, then float
		 * - need to work out where we are here
		 * --> associate the page number with the section header
		 * ==> $xref[$sIndex][$cIndex][$oIndex]['pno'] = $this->pdf->PageNo()
		 */
		/** PDFReportGenerator::generateSectionHeader - consider widow/orphan processing" */

		if( $dynamic_context == "static" /* personal, career, pc reports */ ) {
			array_push ( $this->pagemark, array ( sprintf("%04d-%03d-%04d", $sIndex, $cIndex, $oIndex) => $this->pdf->PageNo() ) );
		} else /* y3 or pc3 reports */ {
			array_push ( $this->pagemark_t, array ( sprintf("%04d-%03d-%04d", $sIndex, $cIndex, $oIndex) => $this->pdf->PageNo() ) );
		}

		/** If the subject is the N.Node then we need to pay attention to the S.Node here. */
		if( $sIndex == "1010" ) {
			/** PDFReportGenerator::generateSectionHeader - sIndex is the Node ($sIndex)"); */
			/** select S.Node */
			$subject2 = $top_object1 [$this->Language][ $sIndex+1 ];

			/** invert the aspect as the S.Node aspect is relative */
			switch($cIndex) {
				case '000':	$cIndex = '180'; break;
				case '030':	$cIndex = '150'; break;
				case '045':	$cIndex = '135'; break;
				case '060':	$cIndex = '120'; break;
				case '090':	$cIndex = '090'; break;
				case '120':	$cIndex = '060'; break;
				case '135':	$cIndex = '045'; break;
				case '150':	$cIndex = '030'; break;
				case '180':	$cIndex = '000'; break;
				case '200':
					// manage the object
					switch( substr($oIndex,0,2 ) ) {
						case '01': /* In Sign */
							switch(substr($oIndex,2,2 )) {
								case '00': $oIndex = "0106"; break;
								case '01': $oIndex = "0107"; break;
								case '02': $oIndex = "0108"; break;
								case '03': $oIndex = "0109"; break;
								case '04': $oIndex = "0110"; break;
								case '05': $oIndex = "0111"; break;
								case '06': $oIndex = "0100"; break;
								case '07': $oIndex = "0101"; break;
								case '08': $oIndex = "0102"; break;
								case '09': $oIndex = "0103"; break;
								case '10': $oIndex = "0104"; break;
								case '11': $oIndex = "0105"; break;
							}
							break;
						case '00': /* In House */
							switch(substr($oIndex,2,2 )) {
								case '00': $oIndex = "0006"; break;
								case '01': $oIndex = "0007"; break;
								case '02': $oIndex = "0008"; break;
								case '03': $oIndex = "0009"; break;
								case '04': $oIndex = "0010"; break;
								case '05': $oIndex = "0011"; break;
								case '06': $oIndex = "0000"; break;
								case '07': $oIndex = "0001"; break;
								case '08': $oIndex = "0002"; break;
								case '09': $oIndex = "0003"; break;
								case '10': $oIndex = "0004"; break;
								case '11': $oIndex = "0005"; break;
							}
							break;
					}
					break;
			}

			$connector2	= $top_connector [$this->Language][ $cIndex ];
			$object2 = $top_object2 [$this->Language][ $oIndex ];

			if( $type == "y3" || ($type == "pc3" && $dynamic_context == "dynamic" ) ) {
				$text = "$subject transiting $connector $object, $subject2 $connector2 $object2";
			} else {
				$text = "$subject $connector $object, $subject2 $connector2 $object2";
			}
			/** end of S.Node relative aspect */
		} else {
			/** for all bar S.Node */
			if( $sIndex != "1011" ) {

				/** TODO: the pc3 report picks up the transiting clause in the natal section */
				if( $type == "y3" || ($type == "pc3" && $dynamic_context == "dynamic" )) {
					$text = "$subject transiting $connector $object";
				} else {
					$text = "$subject $connector $object";
					/** for planet in sign only, add retrograde suffix if applicable */
					if( substr($oIndex,0,2) == "01" && $retrograde != '' ) {
						$text .= ' Retrograde';
					}
				}
			} else {
				//$text = "S.Node is Deprecated";
				$this->preamble_content = "";
				$this->preamble_shown = false;
				return;
			}
		}

		//$this->pdf->Bookmark($text,1,-1);

		$this->pdf->SetFont
		(
				$this->section_heading_font_family,
				$this->section_heading_font_weight,
				$this->section_heading_font_size
		);

		$this->pdf->SetTextColor
		(
				$this->section_heading_font_colour[0],
				$this->section_heading_font_colour[1],
				$this->section_heading_font_colour[2]
		);

		/** Make a decision at this point whether we can fit the header, subheader
		 * and graph if a dynamic records and the first line of the paragraph content before we go any further
		 *
		 * A4 page height = 297, bottom margin = 30 => 267 ~ 270
		*/
		/** PDFReportGenerator::generateSectionHeader - widow/orphan check for $text" */
		/** PDFReportGenerator::generateSectionHeader - y position = ".$this->pdf->getY()  */
		/** PDFReportGenerator::generateSectionHeader - section head needs ".$this->section_heading_line_height */

		if( ($this->pdf->getY() + ($this->section_heading_line_height*2)) > 275 ) {
			/** PDFReportGenerator::generateSectionHeader - adjusting y position" */
			$this->pdf->setY( $this->pdf->getY() + ($this->section_heading_line_height*2) );
			/** PDFReportGenerator::generateSectionHeader - new y position = ".$this->pdf->getY() */
		}

		/** capture the page number
		 * TODO - determine whether this ought to be before or after the MultiCell call */
		/** PDFRep-ortGenerator::generateSectionHeader - pre MultiCell page = ".$this->pdf->PageNo() */

		/**
		 * We don't want to print the Sub title on each internal section
		 * For e.g :
		 * Sun In Aries
		 * Venus in Leo
		 */
		/**
		 $this->pdf->MultiCell
		 (
		 $this->pdf->column_width,
		 $this->section_heading_line_height,
		 $text,
		 $this->section_heading_border,
		 $this->section_heading_align,
		 (($this->section_heading_fill === true) ? 1 : 0)
		 );
		 $this->pdf->Write($this->section_heading_line_height, trim($text));
		 $this->pdf->Ln(2);
		 */
		$this->pdf->Ln(1);

		$this->preamble_content = "";
		$this->preamble_shown = false;
	}

	function SectionSubHead( $start_date, $end_date ) {
		$this->pdf->SetFont
		(
				$this->section_heading_font_family,
				$this->section_heading_font_weight,
				$this->section_heading_font_size
		);

		$this->pdf->SetTextColor
		(
				$this->section_heading_font_colour[0],
				$this->section_heading_font_colour[1],
				$this->section_heading_font_colour[2]
		);

		/** Development Note. The arrow needs to be a symbol rather than "->" as it gets mangled */
		$this->pdf->MultiCell
		(
				$this->pdf->column_width,
				$this->section_heading_line_height,
				strtoupper( sprintf("%s -> %s", $start_date, $end_date ) ),
				/* top/bottom border */	$this->section_heading_border,
				/* align left */		$this->section_heading_align,
				/* transparent fill */	(($this->section_heading_fill === true) ? 1 : 0)
		);
		//		$this->pdf->Write($this->section_heading_line_height, trim(strtoupper( sprintf("%s -> %s", $start_date, $end_date ) )));

		$this->pdf->Ln(2);
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

		//		$this->pdf->Write($this->section_attitude_line_height, trim($content));
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
		$this->ReportHTML .= $this->AddSection(trim($content));
		$this->pdf->Ln();
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

		$this->pdf->SetFont
		(
				$this->section_heading_font_family,
				$this->section_heading_font_weight,
				$this->section_heading_font_size
		);

		$this->pdf->SetTextColor
		(
				$this->section_heading_font_colour[0],
				$this->section_heading_font_colour[1],
				$this->section_heading_font_colour[2]
		);

		$this->pdf->SetFillColor
		(
				$this->section_heading_fill_colour[0],
				$this->section_heading_fill_colour[1],
				$this->section_heading_fill_colour[2]
		);

		$this->pdf->MultiCell
		(
				$this->pdf->column_width,
				$this->section_content_line_height,
				$Introduction_Generic_Text[$this->Language]["Introduction"],
				$this->section_content_border,						/* no border */
				$this->section_attitude_align,						/* align left */
				(($this->chapter_heading_fill === true) ? 1 : 0)    /* transparent fill */
		);

		$this->ReportHTML .= $this->AddSubHeading($Introduction_Generic_Text[$this->Language]["Introduction"]);

		$this->pdf->Ln(5);

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

		$this->pdf->SetFillColor
		(
				$this->section_content_fill_colour[0],
				$this->section_content_fill_colour[1],
				$this->section_content_fill_colour[2]
		);

		/**
		 * at this point look at the page position and determine whether the chapter
		 * heading and a section heading with subheading will fit before the end of
		 * the page is reached. If it is not possible, then this needs to be floated
		 * to the start of the next page
		 *
		 * spec required is
		 * - chapter_heading_line_height +
		 * - section_heading_line_height +
		 * - section_attitude_line_height +
		 * - section_content_line_height
		 *
		 * also be mindful of the page foot offset
		*/
		for($Index = 0; $Index < count($Introduction_Generic_Text[$this->Language]["IntroductionText"]); $Index++) {

			$this->pdf->MultiCell
			(
					$this->pdf->column_width,
					$this->section_attitude_line_height,
					trim($Introduction_Generic_Text[$this->Language]["IntroductionText"][$Index]),
					/* no border */			$this->section_attitude_border,
					/* align left */		$this->section_attitude_align,
					/* transparent fill */	(($this->section_attitude_fill === true) ? 1 : 0)
			);
			$this->ReportHTML .= $this->AddSection(trim($Introduction_Generic_Text[$this->Language]["IntroductionText"][$Index]));
			$this->pdf->Ln(2);
		}

		$this->DrawSeperator();
	}

	function DrawSeperator() {
		$this->pdf->Ln(3);

		$this->pdf->SetDrawColor(
				$this->chapter_heading_font_colour[0],
				$this->chapter_heading_font_colour[1],
				$this->chapter_heading_font_colour[2]
		);

		$this->pdf->SetFillColor
		(
				$this->chapter_heading_font_colour[0],
				$this->chapter_heading_font_colour[1],
				$this->chapter_heading_font_colour[2]
		);
		$this->pdf->SetTextColor
		(
				$this->chapter_heading_font_colour[0],
				$this->chapter_heading_font_colour[1],
				$this->chapter_heading_font_colour[2]
		);

		$this->pdf->Rect($this->pdf->GetX() + 1, $this->pdf->GetY(), 8, 1.5, "FD");
		$this->pdf->Ln(5);

		$this->pdf->SetDrawColor(
				$this->section_content_font_colour[0],
				$this->section_content_font_colour[1],
				$this->section_content_font_colour[2]
		);

		$this->pdf->SetFillColor
		(
				$this->section_content_font_colour[0],
				$this->section_content_font_colour[1],
				$this->section_content_font_colour[2]
		);
		$this->pdf->SetTextColor
		(
				$this->section_content_font_colour[0],
				$this->section_content_font_colour[1],
				$this->section_content_font_colour[2]
		);
	}

	/**
	 * IntroductionSection() Print the Generic Introduction Text at start of the Report.
	 */
	function GenericIntroductionSection($PlanetNo, $SignNo) {
		global $Introduction_Generic_Text;
		global $top_object2;

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
				trim($Introduction_Generic_Text[$this->Language]["PlanetIntroductionText"][$PlanetNo][0]),
				/* no border */			$this->section_attitude_border,
				/* align left $this->section_attitude_align */		'C',
				/* transparent fill */	(($this->section_attitude_fill === true) ? 1 : 0)
		);
		$this->ReportHTML .= $this->AddSection(trim($Introduction_Generic_Text[$this->Language]["PlanetIntroductionText"][$PlanetNo][0]));
		$this->pdf->Ln(4);

		$this->pdf->SetFont
		(
				$this->section_attitude_font_family,
				$this->section_attitude_font_weight,
				$this->section_attitude_font_size
		);

		$Content1 = sprintf(trim($Introduction_Generic_Text[$this->Language]["PlanetIntroductionText"][$PlanetNo][1]),
				$top_object2[$this->Language][$PlanetNo], $top_object2[$this->Language][$SignNo]);

		$this->pdf->MultiCell
		(
				$this->pdf->column_width,
				$this->section_content_line_height,
				$Content1,
				/* no border */			$this->section_attitude_border,
				/* align left */		$this->section_attitude_align,
				/* transparent fill */	(($this->section_attitude_fill === true) ? 1 : 0)
		);
		$this->ReportHTML .= $this->AddSection(trim($Content1));

		$this->pdf->Ln(4);

		$this->pdf->SetFont
		(
				$this->section_attitude_font_family,
				$this->section_attitude_font_weight,
				$this->section_attitude_font_size
		);

		$Content2 = sprintf(trim($Introduction_Generic_Text[$this->Language]["PlanetIntroductionText"][$PlanetNo][2]),
				$top_object2[$this->Language][$PlanetNo], $top_object2[$this->Language][$SignNo]);

		$this->pdf->MultiCell
		(
				$this->pdf->column_width,
				$this->section_attitude_line_height,
				$Content2,
				/* no border */			$this->section_attitude_border,
				/* align left $this->section_attitude_align */		'C',
				/* transparent fill */	(($this->section_attitude_fill === true) ? 1 : 0)
		);
		$this->ReportHTML .= $this->AddSection(trim($Content2));
		$this->pdf->Ln(4);
	}

	/**
	 * GenericAspectIntroductionSection() Print the Generic Introduction Text at start of the Report.
	 */
	function GenericAspectIntroductionSection($PlanetNo, $Aspect, $NatalPlanetNo) {
		//echo "<pre>********** GenericAspectIntroductionSection()</pre>";
		global $Introduction_Generic_Text;
		global $top_object1, $top_connector, $top_object2;

		$this->pdf->SetFont
		(
				$this->section_content_font_family,
				$this->section_content_font_weight,
				$this->section_content_font_size
		);

		$this->pdf->SetFillColor
		(
				$this->section_content_fill_colour[0],
				$this->section_content_fill_colour[1],
				$this->section_content_fill_colour[2]
		);

		$Content2 = sprintf(trim($Introduction_Generic_Text[$this->Language]["AspectIntroductionText"][0]),
				$top_object1[$this->Language][$PlanetNo],
				$top_connector[$this->Language][$Aspect],
				$top_object2[$this->Language][$NatalPlanetNo]);

		$this->pdf->MultiCell
		(
				$this->pdf->column_width,
				$this->section_attitude_line_height,
				trim($Content2),
				/* no border */			$this->section_attitude_border,
				/* align left $this->section_attitude_align*/		'L',
				/* transparent fill */	(($this->section_attitude_fill === true) ? 1 : 0)
		);
		$this->ReportHTML .= $this->AddSection(trim($Content2));

		$this->pdf->Ln(4);

		$this->pdf->SetFont
		(
				$this->section_attitude_font_family,
				$this->section_heading_font_weight,
				$this->section_attitude_font_size
		);

		$this->pdf->SetTextColor
		(
				$this->section_heading_font_colour[0],
				$this->section_heading_font_colour[1],
				$this->section_heading_font_colour[2]
		);

		$Content1 = sprintf(trim($Introduction_Generic_Text[$this->Language]["AspectIntroductionText"][1]),
				$top_object1[$this->Language][$PlanetNo],
				$top_connector[$this->Language][$Aspect],
				$top_object2[$this->Language][$NatalPlanetNo]);

		$this->pdf->MultiCell
		(
				$this->pdf->column_width,
				$this->section_content_line_height,
				$Content1,
				/* no border */			$this->section_attitude_border,
				/* align left */		$this->section_attitude_align,
				/* transparent fill */	(($this->section_attitude_fill === true) ? 1 : 0)
		);

		$this->ReportHTML .= $this->AddSubHeading(trim($Content1));
		$this->pdf->Ln(3);
	}

	/**
	 * GenericAspectIntroductionMergingText() Print the Generic Text at middle of two paragraph.
	 */
	function GenericAspectIntroductionMergingText() {
		//echo "<pre>********** GenericAspectIntroductionSection()</pre>";
		global $Introduction_Generic_Text;
		global $top_object1, $top_connector, $top_object2;

		$this->pdf->SetFont
		(
				$this->section_heading_font_family,
				$this->section_heading_font_weight,
				$this->section_heading_font_size
		);

		$this->pdf->SetTextColor
		(
				$this->section_heading_font_colour[0],
				$this->section_heading_font_colour[1],
				$this->section_heading_font_colour[2]
		);

		$this->pdf->MultiCell
		(
				$this->pdf->column_width,
				$this->section_content_line_height + 2,
				trim($Introduction_Generic_Text[$this->Language]["AspectIntroductionText"][2]),
				/* no border */			$this->section_attitude_border,
				/* align left */		$this->section_attitude_align,
				/* transparent fill */	(($this->section_attitude_fill === true) ? 1 : 0)
		);
		$this->ReportHTML .= $this->AddSubHeading(trim($Introduction_Generic_Text[$this->Language]["AspectIntroductionText"][2]));
	}

	/**
	 * GenericTransitIntroductionSection() Print the Generic Introduction Text at start of the Report.
	 */
	function GenericTransitIntroductionSection($UserName, $PlanetNo, $Aspect, $NatalPlanetNo, $StartDate, $EndDate) {
		global $Introduction_Generic_Text;
		global $top_object1, $top_connector, $top_object2;
		//echo "<pre>********** GenericAspectIntroductionSection()</pre>";
		echo "$PlanetNo, $Aspect, $NatalPlanetNo, $StartDate, $EndDate <br />";

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

		$this->pdf->SetFillColor
		(
				$this->section_content_fill_colour[0],
				$this->section_content_fill_colour[1],
				$this->section_content_fill_colour[2]
		);

		$this->pdf->MultiCell
		(
				$this->pdf->column_width,
				$this->section_attitude_line_height,
				trim($Introduction_Generic_Text[$this->Language]["TransitIntroductionText"][0]),
				/* no border */			$this->section_attitude_border,
				/* align left $this->section_attitude_align */		'L',
				/* transparent fill */	(($this->section_attitude_fill === true) ? 1 : 0)
		);
		$this->ReportHTML .= $this->AddSection(trim($Introduction_Generic_Text[$this->Language]["TransitIntroductionText"][0]));

		$this->pdf->Ln(4);
			
		$Content1 = sprintf(trim($Introduction_Generic_Text[$this->Language]["TransitIntroductionText"][1]),
				$top_object1[$this->Language][$PlanetNo],
				$top_connector[$this->Language][$Aspect],
				$top_object2[$this->Language][$NatalPlanetNo]);
			
		$this->pdf->MultiCell
		(
				$this->pdf->column_width,
				$this->section_attitude_line_height,
				$Content1,
				/* no border */			$this->section_attitude_border,
				/* align left */		$this->section_attitude_align,
				/* transparent fill */	(($this->section_attitude_fill === true) ? 1 : 0)
		);
		$this->ReportHTML .= $this->AddSection(trim($Content1));
		$this->pdf->Ln(3);

		$this->pdf->SetFont
		(
				$this->section_heading_font_family,
				$this->section_heading_font_weight,
				$this->section_heading_font_size
		);

		$this->pdf->SetTextColor
		(
				$this->section_heading_font_colour[0],
				$this->section_heading_font_colour[1],
				$this->section_heading_font_colour[2]
		);

		$this->pdf->MultiCell
		(
				$this->pdf->column_width,
				$this->section_attitude_line_height,
				trim($Introduction_Generic_Text[$this->Language]["TransitIntroductionText"][2]),
				/* no border */			$this->section_attitude_border,
				/* align left $this->section_attitude_align */		'L',
				/* transparent fill */	(($this->section_attitude_fill === true) ? 1 : 0)
		);
		$this->ReportHTML .= $this->AddSubHeading(trim($Introduction_Generic_Text[$this->Language]["TransitIntroductionText"][2]));
	}

	/**
	 * GenericTransitIntroductionMergingText() Print the Generic Text at middle of two paragraph.
	 */
	function GenericTransitIntroductionMergingText() {
		//echo "<pre>********** GenericAspectIntroductionSection()</pre>";
		global $Introduction_Generic_Text;
		global $top_object1, $top_connector, $top_object2;

		$this->pdf->SetFont
		(
				$this->section_heading_font_family,
				$this->section_heading_font_weight,
				$this->section_heading_font_size
		);

		$this->pdf->SetTextColor
		(
				$this->section_heading_font_colour[0],
				$this->section_heading_font_colour[1],
				$this->section_heading_font_colour[2]
		);

		$this->pdf->MultiCell
		(
				$this->pdf->column_width,
				$this->section_content_line_height + 2,
				trim($Introduction_Generic_Text[$this->Language]["TransitIntroductionText"][3]),
				/* no border */			$this->section_attitude_border,
				/* align left */		$this->section_attitude_align,
				/* transparent fill */	(($this->section_attitude_fill === true) ? 1 : 0)
		);
		$this->ReportHTML .= $this->AddSubHeading(trim($Introduction_Generic_Text[$this->Language]["TransitIntroductionText"][3]));
	}


	/**
	 * GenericSummary() Print the Generic Summary Text at end of the Report.
	 */
	function GenericSummary($OrderId) {
		global $Introduction_Generic_Text;

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

		$this->pdf->SetFillColor
		(
				$this->section_content_fill_colour[0],
				$this->section_content_fill_colour[1],
				$this->section_content_fill_colour[2]
		);

		$this->pdf->MultiCell
		(
				$this->pdf->column_width,
				$this->section_attitude_line_height,
				trim($Introduction_Generic_Text[$this->Language]["SummaryText"][0]),
				$this->chapter_heading_border,
				"L",
				(($this->chapter_heading_fill === true) ? 1 : 0)
		);
		$this->ReportHTML .= $this->AddSection(trim($Introduction_Generic_Text[$this->Language]["SummaryText"][0]));

		$this->pdf->Ln(3);

		/*
		 $this->pdf->MultiCell
		(
				$this->pdf->column_width,
				$this->section_attitude_line_height,
				trim($Introduction_Generic_Text[$this->Language]["SummaryText"][1]),
				$this->section_attitude_border,
				"L",
				(($this->section_attitude_fill === true) ? 1 : 0)
		);
		*/

		$UpSellLink = sprintf('<A HREF="http://astrowow.com/buy-astrology-reading/character-and-destiny-report?upordid=%s">%s<A/>', $OrderId, trim($Introduction_Generic_Text[$this->Language]["SummaryText"][1]));
		$OrderIdLink = sprintf('http://astrowow.com/buy-astrology-reading/character-and-destiny-report?upordid=%s', $OrderId);

		$this->ReportHTML .= $this->AddSection(trim($UpSellLink));

		$this->pdf->WriteHTML($UpSellLink);
			
		$this->pdf->Ln(5);

		$this->pdf->SetTextColor
		(
				$this->section_content_font_colour[0],
				$this->section_content_font_colour[1],
				$this->section_content_font_colour[2]
		);


		$this->pdf->MultiCell
		(
				$this->pdf->column_width,
				$this->section_attitude_line_height,
				trim($Introduction_Generic_Text[$this->Language]["SummaryText"][2]),
				/* no border */			$this->section_attitude_border,
				/* align left */		"L",
				/* transparent fill */	(($this->section_attitude_fill === true) ? 1 : 0)
		);

		$this->ReportHTML .= $this->AddSection(trim($Introduction_Generic_Text[$this->Language]["SummaryText"][2]));
	}

	public function AddHeading($Content) {
		$ReturnHTML = "";
		$ReturnHTML = sprintf("<h1>%s</h1>", $Content);
		return $ReturnHTML;
	}

	public function AddSubHeading($Content) {
		$ReturnHTML = "";
		$ReturnHTML = sprintf("<p class='subHeading'><strong>%s</strong></p>", $Content);
		return $ReturnHTML;
	}

	public function AddSection($Content) {
		$ReturnHTML = "";
		$ReturnHTML = sprintf("<p class='sectionContent'>%s</p>", $Content);
		return $ReturnHTML;
	}
};

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
			$this->Cell(50, 10, 'AstroWOW Inc,.', 0, false, 'L', 0, '', 0, false, 'T', 'M');
				
			// Site link
			$this->Cell(50, 10, 'AstroWOW.com', 0, false, 'C', 0, '', 0, false, 'T', 'M');
				
			// Page number
			$this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
		}
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
		} else if($this->ReportType == "seasonal") {
			$img_file = ROOTPATH . '/bin/pages/mini-reports/r-and-r/Calendar_report_cover.jpg';
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