<?php
/**
 * @package Generators
 * @subpackage PDF
 * @author Andy Gray <andy.gray@astro-consulting.co.uk>
 * @copyright Copyright (c) 2005-2008, Andy Gray
 */
class PDFGenerator {

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

    /**
     * @param none
     * @return PDF_Report
     */
    function PDFGenerator( $paper_size='a4' ) {

        global $logger;
        $logger->debug("PDFGenerator::PDFGenerator");

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
            /* this is where the a4 descriptor gets picked up - doh! */
                $this->pdf = new PDF_A4_2COL();
                break;
        }

        $this->pdf->FPDF();
        $this->pdf->Open();
        $this->pdf->AliasNbPages();
        $this->pdf->setAutoPageBreak(true,16); /* test */
        $this->pdf->AddPage();
        $this->setPageFormatting();
    }

    /**
     * setPageFormatting
     *
     * set the page formatting to suit the deployment. This function is intended
     * to be overloaded
     */
    function setPageFormatting() {

        global $logger;
        $logger->debug("PDFGenerator::PDFGenerator");

        $this->pdf->AddFont('taurus','','taurus.php');
        $this->pdf->AddFont('calligraphic421','','Calligraphic421.php');

        $this->chapter_heading_font_family	= 'arial';	//'taurus';
        $this->chapter_heading_font_weight	= 'B';
        $this->chapter_heading_font_size	= 16;
        $this->chapter_heading_font_colour	= array( 0x00, 0x00, 0x00 );
        $this->chapter_heading_line_height	= 10;
        $this->chapter_heading_border	= 1;
        $this->chapter_heading_align	= 'C';
        $this->chapter_heading_fill		= true;
        $this->chapter_heading_fill_colour	= array( 0xcc, 0xcc, 0xcc );

        $this->section_heading_font_family	= 'arial';	//'calligraphic421';
        $this->section_heading_font_weight	= '';
        $this->section_heading_font_size	= 12;
        $this->section_heading_font_colour	= array( 0x00, 0x00, 0x00 );
        $this->section_heading_line_height	= 6;
        $this->section_heading_border	= 0; // was 'TB';
        $this->section_heading_align	= 'L';
        $this->section_heading_fill		= false;
        $this->section_heading_fill_colour	= array( 0x00, 0x00, 0x00 );

        $this->section_attitude_font_family	= 'arial';	//'calligraphic421';
        $this->section_attitude_font_weight	= '';
        $this->section_attitude_font_size	= 9;
        $this->section_attitude_font_colour	= array( 0x00, 0x00, 0x00 );
        $this->section_attitude_line_height	= 5;
        $this->section_attitude_border	= 0;
        $this->section_attitude_align	= 'J';
        $this->section_attitude_fill	= false;
        $this->section_attitude_fill_colour	= array( 0x00, 0xFF, 0xFF );

        $this->section_content_font_family	= 'arial';	//'calligraphic421';
        $this->section_content_font_weight	= '';
        $this->section_content_font_size	= 9;
        $this->section_content_font_colour	= array( 0x00, 0x00, 0x00 );
        $this->section_content_line_height	= 5;
        $this->section_content_border	= 0;
        $this->section_content_align	= 'J';
        $this->section_content_fill		= false;
        $this->section_content_fill_colour	= array( 0xFF, 0xFF, 0xFF );

        $this->preamble_content = "";
        $this->preamble_shown = false;
        $this->pagemark = array();
        $this->pagemark_t = array();
        $this->toc = array();
        $this->toc_xref = false;
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
        global $logger;
        $logger->debug("PDFGenerator::generateReportHeader");
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

        global $logger;

        $logger->debug("PDFGenerator::generateChapterHeader, chapter = $chapter");

        /*
		 * Add a bookmark within the PDF document
        */
        $this->pdf->Bookmark($chapter,0,-1);

        /*
		 * Add a bookmark within the table of contents
        */
        array_push
                (
                $this->toc,
                array(
                "chapter" => $chapter,
                "page" => $this->pdf->PageNo()
                )
        );

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

        /*
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

        $logger->debug("PDFGenerator::generateSectionHeader, sIndex = $sIndex, cIndex = $cIndex, oIndex = $oIndex, rx = $retrograde, dynamic_context = $dynamic_context");

        if($oIndex == '0000') {
            $oIndex = $oIndex + 1000;            
        }
        
        // convert from an index into a String
        $subject	= $top_object1		[$this->language][ $sIndex ];
        $connector	= $top_connector	[$this->language][ $cIndex ];
        $object		= $top_object2		[$this->language][ $oIndex ];

        //Changed By Amit
//        if($oIndex == '0000') {
//            $object	= $top_object2		[$this->language][ $oIndex + 1000 ];
//        }
//        else {
//            $object	= $top_object2		[$this->language][ $oIndex ];
//        }

        /*
         * All section headers are bookmarked in the table of contents so that
         * I can cross reference where duplication exists. In all cases the
         * bookmark should be in ascending planetary order with the exception
         * of the ascendant which is not cross referenced
         *
         * Dev Note - this needs to take into account the encoding used for
         * transiting aspects
         */
        /*
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
        $logger->debug("PDFGenerator::generateSectionHeader - consider widow/orphan processing");

        /*
         * If the subject is the N.Node then we need to pay attention
         * to the S.Node here.
         */
        if( $sIndex == "1010" ) {
            $logger->debug("PDFGenerator::generateSectionHeader - sIndex is the Node ($sIndex)");
            /* select S.Node */
            $subject2 = $top_object1 [$this->language][ $sIndex+1 ];
            
            /* invert the aspect as the S.Node aspect is relative */
            switch($cIndex) {
                case '000':	$cIndex = '180';
                    break;
                case '030':	$cIndex = '150';
                    break;
                case '045':	$cIndex = '135';
                    break;
                case '060':	$cIndex = '120';
                    break;
                case '090':	$cIndex = '090';
                    break;
                case '120':	$cIndex = '060';
                    break;
                case '135':	$cIndex = '045';
                    break;
                case '150':	$cIndex = '030';
                    break;
                case '180':	$cIndex = '000';
                    break;
                case '200':
                // manage the object
                    switch( substr($oIndex,0,2 ) ) {
                        case '01': /* In Sign */
                            switch(substr($oIndex,2,2 )) {
                                case '00': $oIndex = "0106";
                                    break;
                                case '01': $oIndex = "0107";
                                    break;
                                case '02': $oIndex = "0108";
                                    break;
                                case '03': $oIndex = "0109";
                                    break;
                                case '04': $oIndex = "0110";
                                    break;
                                case '05': $oIndex = "0111";
                                    break;
                                case '06': $oIndex = "0100";
                                    break;
                                case '07': $oIndex = "0101";
                                    break;
                                case '08': $oIndex = "0102";
                                    break;
                                case '09': $oIndex = "0103";
                                    break;
                                case '10': $oIndex = "0104";
                                    break;
                                case '11': $oIndex = "0105";
                                    break;
                            }
                            break;
                        case '00': /* In House */
                            switch(substr($oIndex,2,2 )) {
                                case '00': $oIndex = "0006";
                                    break;
                                case '01': $oIndex = "0007";
                                    break;
                                case '02': $oIndex = "0008";
                                    break;
                                case '03': $oIndex = "0009";
                                    break;
                                case '04': $oIndex = "0010";
                                    break;
                                case '05': $oIndex = "0011";
                                    break;
                                //case '06': $oIndex = "0000";
                                case '06': $oIndex = "0012";
                                    break;
                                case '07': $oIndex = "0001";
                                    break;
                                case '08': $oIndex = "0002";
                                    break;
                                case '09': $oIndex = "0003";
                                    break;
                                case '10': $oIndex = "0004";
                                    break;
                                case '11': $oIndex = "0005";
                                    break;
                            }
                            break;
                    }
                    break;
            }
            
            $connector2	= $top_connector [$this->language][ $cIndex ];
            $object2 = $top_object2 [$this->language][ $oIndex ];
            if( $type == "y3" || ($type == "pc3" && $dynamic_context == "dynamic" ) ) {
                $text = "$subject transiting $connector $object, $subject2 $connector2 $object2";
            } else {
                $text = "$subject $connector $object, $subject2 $connector2 $object2";
            }
            /* end of S.Node relative aspect */
        } else {
            /*  for all bar S.Node */
            if( $sIndex != "1011" ) {
                /** TODO: the pc3 report picks up the transiting clause in the natal section */
                if( $type == "y3" || ($type == "pc3" && $dynamic_context == "dynamic" )) {
                    $text = "$subject transiting $connector $object";
                } else {
                    $text = "$subject $connector $object";
                    /*
					 * for planet in sign only, add retrograde suffix if applicable
                    */
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

        /*
		 * Make a decision at this point whether we can fit the header, subheader
		 * and graph if a dynamic records and the first line of the paragraph content
		 * before we go any further
		 *
		 * A4 page height = 297, bottom margin = 30 => 267 ~ 270
        */
        $logger->debug("PDFGenerator::generateSectionHeader - widow/orphan check for $text");
        $logger->debug("PDFGenerator::generateSectionHeader - y position = ".$this->pdf->getY());
        $logger->debug("PDFGenerator::generateSectionHeader - section head needs ".$this->section_heading_line_height);

        if( ($this->pdf->getY() + ($this->section_heading_line_height*2)) > 275 ) {
            $logger->debug("PDFGenerator::generateSectionHeader - adjusting y position");
            $this->pdf->setY( $this->pdf->getY() + ($this->section_heading_line_height*2) );
            $logger->debug("PDFGenerator::generateSectionHeader - new y position = ".$this->pdf->getY());
        }

        /*
		 * capture the page number
		 * TODO - determine whether this ought to be before or after the MultiCell call
        */
        $logger->debug("PDFGenerator::generateSectionHeader - pre MultiCell page = ".$this->pdf->PageNo());

        $this->pdf->Bookmark($text,1,-1);

        /* manage cross reference table */
        if( $dynamic_context == "static" /* personal, career, pc reports */ ) {
            $logger->debug("PDFGenerator::generateSectionHeader - XREF mark - " . sprintf("%04d-%03d-%04d", $sIndex, $cIndex, $oIndex));
            if( $cIndex == "200" /* In */ ) {
                /*
				 * ONLY look for planet IN sign or planet IN house for now
                */
                if( empty( $this->pagemark[ sprintf("%04d-%03d-%04d", $sIndex, $cIndex, $oIndex) ] ) ) {
                    /*
					 * not registered so record page number
                    */
                    $this->pagemark[ sprintf("%04d-%03d-%04d", $sIndex, $cIndex, $oIndex) ] = $this->pdf->PageNo();
                    $logger->debug("PDFGenerator::generateSectionHeader - XREF mark - this was empty, now set to " .
                            $this->pagemark[ sprintf("%04d-%03d-%04d", $sIndex, $cIndex, $oIndex) ]);
                } else {
                    /*
					 * already here no nothing to do
                    */
                    $logger->debug("PDFGenerator::generateSectionHeader - XREF mark - this is already set to " .
                            $this->pagemark[ sprintf("%04d-%03d-%04d", $sIndex, $cIndex, $oIndex) ]);
                    $logger->debug("PDFGenerator::generateSectionHeader - XREF mark - setting toc_xref");
                    $this->toc_xref = true;
                }
            }
        } else /* y3 or pc3 reports */ {
            $logger->debug("PDFGenerator::generateSectionHeader - XREF mark (pc3) - " . sprintf("%04d-%03d-%04d", $sIndex, $cIndex, $oIndex));
            if( $cIndex == "200" /* In */ ) {
                /*
				 * ONLY look for planet IN sign or planet IN house for now
                */
                if( empty( $this->pagemark[ sprintf("%04d-%03d-%04d", $sIndex, $cIndex, $oIndex) ] ) ) {
                    /*
					 * not registered so record page number
                    */
                    $this->pagemark[ sprintf("%04d-%03d-%04d", $sIndex, $cIndex, $oIndex) ] = $this->pdf->PageNo();
                    $logger->debug("PDFGenerator::generateSectionHeader - XREF mark [pc3] - this was empty, now set to " .
                            $this->pagemark[ sprintf("%04d-%03d-%04d", $sIndex, $cIndex, $oIndex) ]);
                } else {
                    /*
					 * already here no nothing to do
                    */
                    $logger->debug("PDFGenerator::generateSectionHeader - XREF mark [pc3] - this is already set to " .
                            $this->pagemark[ sprintf("%04d-%03d-%04d", $sIndex, $cIndex, $oIndex) ]);
                }
            } else {
                $this->pagemark_t[ sprintf("%04d-%03d-%04d", $sIndex, $cIndex, $oIndex) ] = $this->pdf->PageNo();
            }
        }

        $this->pdf->MultiCell
                (
                $this->pdf->column_width,
                $this->section_heading_line_height,
                $text,
                /* top/bottom border */	$this->section_heading_border,
                /* align left */		$this->section_heading_align,
                /* transparent fill */	(($this->section_heading_fill === true) ? 1 : 0)
        );
        $logger->debug("PDFGenerator::generateSectionHeader - post MultiCell page = ".$this->pdf->PageNo());
        $this->pdf->Ln(2);

        $this->preamble_content = "";
        $this->preamble_shown = false;
    }

    function SectionSubHead( $start_date, $end_date ) {
        global $logger;
        $logger->debug("PDFGenerator::generateSectionSubHead, startdate = $start_date, enddate = $end_date");
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

        /*
		 * Development Note
		 * The arrow needs to be a symbol rather than "->" as it gets mangled
        */
        $this->pdf->MultiCell
                (
                $this->pdf->column_width,
                $this->section_heading_line_height,
                strtoupper( sprintf("%s -> %s", $start_date, $end_date ) ),
                /* top/bottom border */	$this->section_heading_border,
                /* align left */		$this->section_heading_align,
                /* transparent fill */	(($this->section_heading_fill === true) ? 1 : 0)
        );
        $this->pdf->Ln(2);
    }

    function RetrogradePreamble( $content ) {
        global $logger;
        $logger->debug("PDFGenerator::generateRetrogradePreamble, content = $content");
        $this->preamble_content .= trim($content);
    }

    function AspectStrengthPreamble( $content ) {
        global $logger;
        $logger->debug("PDFGenerator::generateAspectStrengthPreamble, content = $content");
        $this->preamble_content .= " " . trim($content);
    }

    function AspectTypePreamble( $content ) {
        global $logger;
        $logger->debug("PDFGenerator::generateAspectTypePreamble, content = $content");
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
    function SectionContent( $context, $attitude, $description, $preamble_only = false ) {
        global $logger;
        $logger->debug("PDFGenerator::generateSectionContent, content = $context, attitude = $attitude, description = $description");
        if( $this->preamble_content != "" && $this->preamble_shown === false ) {
            $this->Paragraph( trim($this->preamble_content) );
            $this->preamble_shown = true;
        }
        if( $preamble_only === false ) {
            $this->Attitude( $context . " - " . $attitude );
            $this->Description( $description );
        }
    }

    /*
	 function CrossReference( $subject, $connector, $object, $dynamic_context = false ) {
	 global $logger;
	 $logger->debug("PDFGenerator::generateCrossReference, subject = $subject, connector = $connector, object = $object, dynamic_context = $dynamic_context");
	 $this->CrossReference($subject, $connector, $object, $dynamic_context);
	 }
    */

    function ReportTrailer() {
        global $logger;
        $logger->debug("PDFGenerator::generateReportTrailer");
    }

    function Paragraph( $text ) {
        global $logger;
        $logger->debug("PDFGenerator::Paragraph, text = $text");
        $this->Description($text);
    }

    function Attitude( $content ) {

        global $logger;
        $logger->debug("PDFGenerator::Attitude, content = $content");
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
    }

    function Description( $content ) {

        global $logger;
        $logger->debug("PDFGenerator::Description");

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

        global $logger;
        global $top_object1;
        global $top_connector;
        global $top_object2;

        $logger->debug("PDFGenerator::CrossReference, XREF set - sIndex = $sIndex, cIndex = $cIndex, oIndex = $oIndex, dynamic_context = $dynamic_context");

        if($oIndex == '0000') {
            $oIndex = $oIndex + 1000;
        }

        // convert from an index into a String
        $subject	= $top_object1		[$this->language][ $sIndex ];
        $connector	= $top_connector	[$this->language][ $cIndex ];
        $object		= $top_object2		[$this->language][ $oIndex ];

        //Changed By Amit
// 		if($oIndex == '0000'){
// 			$object	= $top_object2		[$this->language][ $oIndex + 1000 ];
// 		}
// 		else {
// 			$object	= $top_object2		[$this->language][ $oIndex ];
// 		}

// 		if(array_key_exists($oIndex, $top_object2)){
// 			$object	= $top_object2		[$this->language][ $oIndex ];
// 		}
// 		else {
// 			$object = '';
// 		}


        if( $this->toc_xref === true ) {
            $logger->debug("PDFGenerator::CrossReference, XREF set - page number found");
            $pno = $this->pagemark[ sprintf("%04d-%03d-%04d", $sIndex, $cIndex, $oIndex) ];
        } else {
            $logger->debug("PDFGenerator::CrossReference, XREF set - no page number found");
        }

        $logger->debug("PDFGenerator::CrossReference, pno = $pno");

        /* Tactical - move to language files !!! */
        $config['en']['xref'] = 'Please read page ';
        $config['de']['xref'] = 'Bitte liest Seite ';
        $config['se']['xref'] = 'Behaga lasa sida '; /* hoop required !!! */
		$config['dk']['xref'] = 'Læs venligst side';
		$config['sp']['xref'] = 'Por favor, lea la página';

        if( $cIndex == '200' ) {
            $content = $config[ strtolower($this->language) ]['xref'] . $pno . " : $subject $connector $object";
        } else {
            if( $sIndex > $oIndex ) {
                $content = $config[ strtolower($this->language) ]['xref'] . $pno . " : $object $connector $subject";
            } else {
                $content = $config[ strtolower($this->language) ]['xref'] . $pno . " : $subject $connector $object";
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
};
?>