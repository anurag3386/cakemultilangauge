<?php
/**
 * Report
 *
 * This class handles the generic production of a report. It manages chart
 * calculation and then parses the results of the chart analysis. The class
 * contains generic drivers that enable debugging to take place. It is
 * intended that the generic drivers are overloaded.
 *
 * @package Reports
 * @author Amit Parmar <parmaramit1111@gmail.com>
 * @copyright Copyright (c) 2008, World of Wisdom
 * @version 1.0
 *
 */

class Report {

    /**
     * Enumerated report type.
     * Valid types include
     * <ul>
     * <li>personal</li>
     * <li>career</li>
     * <li>pc</li>
     * <li>y3</li>
     * <li>pc3</li>
     * <li>seasonal</li>
     * <li>calendar</li>
     * </ul>
     *
     * @var string $type
     */
    var $type;		/* type of report, personal, career, etc */

    /**
     * Context
     * Valid types include
     * <ul>
     * <li>static</li>
     * <li>dynamic</li>
     * </ul>
     *
     * @var string $context
     */
    var $context;		/* context of process flow, static/dynamic */

    /**
     * Language to be used for content generation
     * <ul>
     * <li>English ('en')</li>
     * <li>Danish ('dk')</li>
     * <li>Other languages experimental at this stage</li>
     * </ul>
     *
     * @var string $language
     */
    var $language;	/* report language */

    /**
     * Generator
     *
     * @var mixed $generate
     */
    var $generate;	/* output text engine */

    /**
     *
     */
    var $analysis_context;

    /**
     * Chapter
     * The chapter value is used when managing the cross references
     * for planets in sign and in house. This is especially valid for
     * planets that are rulers or co-rulers as their sections will
     * have been published in the Life Path chapter
     */
    var $chapter;

    var $retrogradeDescriptionIndex;
    var $aspectStrengthDescriptionIndex;
    var $aspectTypeDescriptionIndex;

    /**
     * Constructor
     *
     * <ul>
     * <li>sets default language to English</li>
     * <li>sets default context to static</li>
     * <li>initialises retrograde, aspect strength and type content rotation indexes</li>
     * </ul>
     */
    function Report() {

        // instantiate the logger
        //$this->setReportType( $type );
        $this->setLanguage('en');

        // default is static
        $this->context = "static";

        $this->retrogradeDescriptionIndex = 0;
        $this->aspectStrengthDescriptionIndex = 0;
        $this->aspectTypeDescriptionIndex = 0;
    }

    /**
     * Set Language
     * - validate language setting
     * - set new language
     * - default setting = 'en'
     */
    function setLanguage( $language ) {
        $this->language = $language;
    }

    /**
     * run
     *
     * Process the data returned by the chart calculation and subsequent analysis.
     * Break down the analysis data into chapters and sections
     * Each chapter contains a number of sections
     * Each section contains a header context and the section content
     * The section header context contains retrograde, aspect type and aspect strength subsections
     * The section content contains professional, personal and general subsections
     */
    /* report requires analysis data */
    /* report requires content */
    /* report requires a generator */
    function run($analysis_context, $book, $generator ) {

        global $logger;
        $logger->debug("report::run");

        $this->analysis_context = $analysis_context;
        $this->generate = $generator;
        $this->language = $this->generate->language;

        /* clear content cache */
        $content = '';

        $this->generate->ReportHeader();

        $logger->debug("report::run - test for type != y3");

        if( $this->type != "y3" ) {
            /* For all report types (personal, career, pc and pc3) */
            $logger->debug("report::run - type != y3");
            while( $this->analysis_context->EOF() === false ) {
                $this->context = "static";
                $logger->debug("report::run - context = $this->context");
                $chapter = $this->getChapter();
                $logger->debug("report::run - chapter = $chapter");

                if( $this->analysis_context->BOF() === true ) {
                    /*
					 * For the first record I need to ensure that the chapter checking is skipped
					 * so this is a one off conditional
                    */
                    $logger->debug("report::run - introductory content");
                    $logger->debug("report::run - get section content");
                    $content .= $this->getSection();
                    $logger->debug("report::run - content = $content");
                } else { /* not BOF */
                    /*
					 * This is the main section that collects section records within the current
					 * chapter context.
                    */
                    $logger->debug("report::run - looking for new chapter");
                    $logger->debug("report::run - chapter[$chapter], lastChapter[$lastChapter]");
                    if( $chapter != $lastChapter ) {
                        /*
						 * This is the start of a new chapter and so we need to process the sections
						 * for the current chapter before we move on
                        */
                        $logger->debug("report::run - new chapter");
                        $logger->debug("report::run - calling analyseChapter in outer loop with content = $content");
                        $this->analyseChapter( $lastChapter, $content );

                        $logger->debug("report::run - reset content cache");
                        $content = "";	/* reset content cache */
                        $logger->debug("report::run - content = $content");

                        if( $this->type == "pc3" ) {                            /*
	       * process dynamic context for combination reports with trends
                            */
                            $logger->debug("report::run - pc3 context");
                            $this->context = "dynamic";
                            $logger->debug("report::run - context = $this->context");
                            $content = "";
                            $logger->debug("report::run - *** commented getSection() call ***");
                            //$content .= $this->getSection();
                            $logger->debug("report:run - content = $content");
                            $chapter_t = $lastChapter;
                            /*
	       * loop within current chapter context
                            */
                            while( $this->analysis_context->EOF() === false && $chapter_t == $lastChapter ) {
                                $logger->debug("report::run - not EOF and chapter_t[$chapter_t] == lastChapter[$lastChapter]");
                                $chapter_t = $this->getChapter();
                                $logger->debug("report::run - chapter_t now $chapter_t");
                                /*
	      	 * perform a chapter test to prevent transits appearing in previous section
                                */
                                if( $this->analysis_context->BOF() === true && $chapter_t == $lastChapter ) {
                                    /*
	      		 * first record only ...
                                    */
                                    $logger->debug("report::run - BOF and chapter_t[$chapter_t] == lastChapter[$lastChapter]");
                                    $logger->debug("report::run - get section content");
                                    $content .= $this->getSection();
                                    $logger->debug("report::run - content = $content");
                                } else {
                                    /*
	      		 * ... thereafter
                                    */
                                    $logger->debug("report::run - not BOF");
                                    if( $chapter_t != $lastChapter ) {
                                        /*
	      			 * analyse the chapter before moving to the new
                                        */
                                        $logger->debug("report::run - chapter_t[$chapter_t] != lastChapter[$lastChapter]");
                                        $logger->debug("calling analyseChapter in inner pc3 while loop with content = $content");
                                        $this->analyseChapter( $lastChapter, $content );
                                        $this->context = "static";
                                        $logger->debug("report::run - context = $this->context");
                                        $content = "";	/* reset content cache */
                                        $logger->debug("content = $content");
                                    } else {
                                        $logger->debug("report::run - chapter_t == lastChapter - no change");
                                        $logger->debug("report::run - get next section content");
                                        $content .= $this->getSection();
                                        $logger->debug("content = $content");
                                    } /* if chapter != last chapter */
                                } /* if-else not BOF */
                                // $lastChapter_t = $chapter_t;
                                /* DEVNOTE this is for dynamic reports only */
                                $logger->debug("report::run - *** consider getSection() call ***");
                                /* *** test, looks ok to retain *** */$content .= $this->getSection();
                                $logger->debug("report::run - get next analysis context");
                                $this->analysis_context->next();
                            } /* while */
                            /*
	       * at this point we have parsed all of the transit records for
	       * this chapter and passed the records to analyseChapter() to
	       * manage
                            */
                            $logger->debug("report::run - break from loop");
                            $lastChapter = $chapter_t;
                        } /* end of type = pc3 */
                        // DEVNOTE - back to static context here
                        $logger->debug("report::run - back to static context");
                    } /* chapter != last chapter */
                    $logger->debug("report::run - get next section content");
                    $content .= $this->getSection();
                    $logger->debug("content = $content");
                } /* else */
                $logger->debug("report::run - update lastChapter[$chapter]");
                $lastChapter = $chapter;
                $logger->debug("report::run - get next analysis context");
                /* Bug fix where chapter is a null string */
                if( empty($chapter) ) {
                    break;
                } /* Test this !!! */
                $this->analysis_context->next();
            } /* while not EOF */
            $logger->debug("report::run - analyse chapter");
            $this->analyseChapter( $lastChapter, $content );
        } else {
            /* special case for y3 dynamic report */
            $this->context = "dynamic";
            //print_r($this->analysis_context);
            while( $this->analysis_context->EOF() === false ) {
                $chapter = $this->getChapter();
                if( $this->analysis_context->BOF() === true ) {
                    $logger->debug("report::analyse - introductory content");
                    $content .= $this->getSection();
                } else {	/* not BOF */
                    if( $chapter != $lastChapter ) {
                        /* analyse the chapter before moving to the new */
                        $this->analyseChapter( $lastChapter, $content );
                        $content = "";	/* reset content cache */
                    }
                    $content .= $this->getSection();
                }
                $lastChapter = $chapter;
                $this->analysis_context->next();
            }
            $this->analyseChapter( $lastChapter, $content );
        }
        $this->generate->ReportTrailer();
    }

    /**
     * Analyse Chapter
     *
     * Generate the chapter heading and then iterate through the section records
     * performing the section analysis within each section of the chapter.
     *
     * @access private
     * @param Integer chapter
     * @param String content
     * @return void
     */
    function analyseChapter( $chapter, $content ) {

        global $logger;
        global $chapterHeadings;

        $logger->debug("report::analyseChapter, content = " . strlen($content)/48 . " sections, context = ".$this->context);
        if( $this->context != "dynamic" || $this->type == "y3" ) {
            $this->generate->ChapterHeader( $chapterHeadings[$this->language][$chapter]  );
        }
        for( $i = 0; $i < strlen($content) / 48; $i++ ) {
            $sectionContent = substr($content, ($i*48), 48);
            $this->analyseSection( $sectionContent );
        }
    }

    /**
     * Analyse Section
     *
     * Sections contain the meat of the report, the main interpretive text.
     * This method manages the structure of the section bringing in sub
     * sections as required by the section content.
     *
     * @access private
     * @param String content
     * @return void
     */
    function analyseSection( $content ) {
        global $logger;
        $logger->debug("report::analyseSection, content = $content");

        global $top_object1;
        global $top_connector;
        global $top_object2;
        global $top_retrograde;

        $sIndex = $this->getSectionHeaderSubject( $content );
        $cIndex = $this->getSectionHeaderConnector( $content );
        $oIndex = $this->getSectionHeaderObject( $content );

        /*
		 * If this is the S.Node then we have already managed content based
		 * on the N.Node.
        */
        if( $oIndex == "1011" ) {
            $logger->debug("report::analyseSection, ignoring S.Node as N.Node has been covered");
            return;
        }

        /* Report Section Header */
        if( $this->type == "y3" || ($this->type == "pc3" && $this->isDynamicAspect($content)) ) { /* *** test suggestion *** */
            if( $oIndex != 1014 /* IC */ && $oIndex != 1015 /* DC */ ) {
                $this->generate->SectionHeader
                        (
                        $sIndex, $cIndex, $oIndex,
                        $top_retrograde[$this->language][ $this->getSectionHeaderRetrograde( $content )],
                        "dynamic" /* $this->context */,
                        $this->type
                );
            }
        } else {
            $this->generate->SectionHeader
                    (
                    $sIndex, $cIndex, $oIndex,
                    $top_retrograde[$this->language][ $this->getSectionHeaderRetrograde( $content )],
                    "static" /* $this->context */,
                    $this->type
            );
        }

        /*
		 * dynamic section subhead
		 * trans = sIndex
		 * aspect = cIndex
		 * natal = oIndex
        */
        if( $this->type == "y3" || ($this->type == "pc3" && $this->isDynamicAspect($content)) ) { /* *** test suggestion *** */
            if( $oIndex != 1014 /* IC */ && $oIndex != 1015 /* DC */ ) {
                $start_date = $this->analysis_context->getTransitStartDate( $sIndex, $oIndex, $cIndex ); /* dd/mm/yyyy */
                $end_date = $this->analysis_context->getTransitEndDate( $sIndex, $oIndex, $cIndex );	 /* dd/mm/yyyy */
                $this->generate->SectionSubHead( $start_date, $end_date );
            }
        }

        /*
		 * Manage the main section content
        */
        $logger->debug("report::analyseSection - entering switch, oIndex=$oIndex");
        switch( substr($oIndex,0,2 ) ) {

            case "01":
            /*
				 * Planet in Sign
				 * Format = 01PP
				 * Where PP = 00 (Sun) through to 09 (Pluto), 10-11 (N/S Nodes) and 12 (Ascendant)
				 * Need to be aware of the cross referencing here as it is based
				 * on the subindexed digits which can have a disorienting effect
				 * if we are in the wrong chapter.
            */
                $logger->debug("Report::analyseSection - case planet in sign");
                $subject = intval( substr($sIndex,2,2) );
                $object = intval( substr($oIndex,2,2) );
                $logger->debug("Report::analyseSection - planet ($subject) in sign($object)");
                /* include in sign only and omit nodes */
                if( intval($subject) < 10 /* NNode */ ) {
                    $logger->debug("Report::AnalyseSection - retrograde state for [$subject] = ".$this->getSectionHeaderRetrograde( $content ));
                    $this->analyseSectionRetrogradeState
                            (
                            /* embedded call returns 0 or 1 (an index into the top_retrograde language table) */
                            (($this->getSectionHeaderRetrograde( $content ) == 1) ? true : false )
                    );
                }
                if( $subject != 11 /* S.Node */ ) {
                    if( $this->generate->toc_xref === true ) {
                        $this->generate->CrossReference( $sIndex, $cIndex, $oIndex, "static" );
                        $this->generate->toc_xref = false;
                    } else {
                        $this->analyseSign( $subject, $object );
                    }
                }
                break;

            case "00":
            /*
				 * Planet in House
				 * Format = 00PP
				 * Where PP = 00 (Sun) through to 09 (Pluto), 10-11 (N/S Nodes) and 12 (Ascendant)
				 * Need to be aware of the cross referencing here as it is based
				 * on the subindexed digits which can have a disorienting effect
				 * if we are in the wrong chapter.
            */
                $logger->debug("report:analyseSection - case planet in house");
                $subject = intval( substr($this->getSectionHeaderSubject( $content ),2,2) );
                $object = intval( substr($this->getSectionHeaderObject( $content ),2,2) );
                $logger->debug("report::analyseSection, planet ($subject) in house($object)");
                if( $subject != 11 /* S.Node */ ) {
                    if( $this->generate->toc_xref === true ) {
                        $this->generate->CrossReference( $sIndex, $cIndex, $oIndex, "static" );
                        $this->generate->toc_xref = false;
                    } else {
                        $this->analyseHouse( $subject, $object );
                    }
                }
                break;

            case "10":
            /*
				 * Planet by Aspect
				 * Format = 10PP
				 * Where PP = 01 (Sun) through to 10 (Pluto), 11-12 (N/S Nodes) and 13 (Ascendant)
				 * TODO: check for duplicates here rather than in the analysis code
            */
                $logger->debug("report::analyseSection - case planet in aspect");
                $subject = intval(substr($this->getSectionHeaderSubject( $content ),2,2 ));
                $object = intval(substr($this->getSectionHeaderObject( $content ),2,2 ));
                $aspect = intval($this->getSectionAspect( $content ));
                $logger->debug("report::analyseSection - planet ($subject) in aspect ($aspect) to planet ($object)");
                $strength = $this->getSectionAspectStrength( $content );
                $type = $this->getSectionAspectType( $content );
                $index = $this->getSectionIndex( $content );
                $logger->debug("report::analyseSection - case planet in aspect - check for duplicates");
                $subject++;
                $object++;
                /*
				 * We are only concerned with transits from Jupiter to Pluto
                */
                if( $this->type == "y3" || ( $this->type == "pc3" && $this->isDynamicAspect($content) ) ) {
                    $logger->debug("report::analyseSection - case planet in dynamic aspect");
                    if( (intval($subject) >= 6 /* Jupiter */ && intval($subject) <= 10 /* Pluto */) /*&& (intval($subject) > intval($object))*/) {
                        if( intval($object) == 15 /* IC */|| intval($object) == 16 /* DC */ ) {
                            $logger->debug("report::analyseSection - transiting planet ($subject) in aspect to angle ($object)");
                        } else {
                            $logger->debug("report::analyseSection - transiting planet ($subject) in aspect to natal planet ($subject)");
                            $this->analyseTransitingAspectType( $this->getSectionAspect($content) );
                            $this->analyseAspect( $subject, $object, $this->isDynamicAspect($content) );
                        }
                    }
                } else {
                    /*
					 * not y3 or dynamic pc3 context
                    */
                    $logger->debug("report::analyseSection - case planet in static aspect");
                    // tricky one because pc3 comes in here but only when dealing with the dynamic aspects
                    if( intval($subject) > intval($object) ) {
                        /*
						 * special case for the Ascendant as this appears at the start of the
						 * report before all planets and nodes.
                        */
                        if( $subject == 13 /* Asc */ ) {
                            $this->analyseAspectStrength( $strength, $index );
                            $this->analyseAspectType( $type, $index, $aspect );
                            $this->analyseAspect( $subject, $object, $this->isDynamicAspect($content) );
                        } else {
                            if( $subject != 12 /* S.Node */ ) {
                                $this->generate->CrossReference( $sIndex, $cIndex, $oIndex );
                            }
                        }
                    } else {
                        /*
						 * Another special case this time for the South Node. It seems stupid
						 * to display content for both the nodes. The South Node is dropped
						 * and the North Node compensates.
                        */
                        if( $subject != 12 /* S.Node */ ) {
                            $this->analyseAspectStrength( $strength, $index );
                            $this->analyseAspectType( $type, $index, $aspect );
                            $this->analyseAspect( $subject, $object, $this->isDynamicAspect($content) );
                        }
                    }
                }
                break;
            default:
                $logger->error("report::analyseSection, invalid case in switch statement");
                break;
        }
    }

    /**
     * GetRetrogradeDescriptionIndex
     *
     * Return the next index value in a rotation
     *
     * @return string Retrograde index expressed as a 2 digit numerical string
     */
    function getRetrogradeDescriptionIndex() {
        $descriptions = array ( 4, 5, 1 );
        return sprintf("%02d", $descriptions[ $this->retrogradeDescriptionIndex++ % 3]);
    }

    /*
	 * Analyse Section Retrograde State
	 * @param boolean retrograde motion state
    */
    function analyseSectionRetrogradeState( $retrograde ) {

        global $logger;
        $logger->debug("Report::analyseSectionRetrogradeState(retrograde=$retrograde");

        if( $retrograde === true ) {

            $logger->debug("Report::analyseSectionRetrogradeState - planet is retrograde");

            $chapter = "18";
            $code3="01";
            /* rotate content to avoid repetition */
            $code4 = $this->getRetrogradeDescriptionIndex();

            switch( $this->type ) {
                case "personal":
                    $book = "02";
                    break;
                case "career":
                case "pc":
                case "pc3":
                case "y3":
                    $book = "01";
                    break;
            }

            /*
			 * Retrograde_1 = book=1/2, chapter=18, code3=1, code4=4
			 * Retrograde_2 = book=1/2, chapter=18, code3=1, code4=5
			 * Retrograde_3 = book=1/2, chapter=18, code3=1, code4=1
            */
            $this->generate->RetrogradePreamble( $this->getBookParagraph( $book, $chapter, $code3, $code4, $this->language) );
        }
    }

    /**
     * getChapter
     *
     * getChapter returns the chapter string in the form 10001 through to
     * 10012
     *
     * @access private
     * @return String chapter number in the range 10001 to 10013 (5 characters)
     */
    function getChapter() {
        global $logger;
        $logger->debug("report::getChapter, context = $this->context, chapter = " . $this->analysis_context->getChapter());
        return $this->analysis_context->getChapter();
    }

    /**
     * getSection
     *
     * @access private
     * @return String returns the section context (48 characters)
     */
    function getSection() {
        global $logger;
        $logger->debug("report::getSection, context = $this->context, section = " . $this->analysis_context->getSection());
        return $this->analysis_context->getSection();
    }

    function getSectionHeaderSubject( $content ) {
        global $logger;
        $logger->debug("report::getSectionHeaderSubject returns " . substr($content,5,4));
        return substr($content,5,4);
    }

    function getSectionHeaderConnector( $content ) {
        global $logger;
        $logger->debug("report::getSectionHeaderConnector returns " . substr($content,9,3));
        return substr($content,9,3);
    }

    function getSectionHeaderObject( $content ) {
        global $logger;
        $logger->debug("report::getSectionHeaderObject returns " . substr($content,12,4));
        return substr($content,12,4);
    }

    function getSectionSubHeadStartDate( $content ) {
        return sprintf("%02d-%02d-%04d", substr($content,23,2), substr($content,25,2), substr($content,27,4));
    }

    function getSectionSubHeadEndDate( $content ) {
        return sprintf("%02d-%02d-%04d", substr($content,31,2), substr($content,33,2), substr($content,35,4));
    }

    /*
	 * @return integer 0=static, 1=dynamic
    */
    function getSectionContext( $content ) {
        return intval( substr($content,21,1) );
    }

    /**
     * @return boolean true if section context = 0
     */
    function isStaticAspect($content) {
        return ( $this->getSectionContext( $content ) == 0 );
    }

    /**
     * @return boolean true if section context = 1
     */
    function isDynamicAspect($content) {
        return ( $this->getSectionContext( $content ) == 1 );
    }

    /*
	 * Retrograde:
	 * - direct = 0
	 * - retrograde = 1
	 * This is used to index the $top_retrograde array
    */
    function getSectionHeaderRetrograde( $content ) {
        return substr($content,16,1);
    }

    function getSectionAspectStrength( $content ) {
        return substr($content,40,3);
    }

    function getSectionAspectType( $content ) {
        return substr($content,43,2);
    }

    function getSectionAspect( $content ) {
        return substr($content,9,3);
    }

    /**
     * DevNote - not really sure what to do with this but included for backwards
     * compatibility and not otherwise used
     * @param string content string
     * @return integer record index
     */
    function getSectionIndex( $content ) {
        return substr($content,45,3);
    }

    /*
	 * Originally setPlanet had parameters
	 * subject			section header subject
	 * object			section header object
	 * report type		n/a now held in class variables
	 * language			n/a now held in class variables
	 * pdf				n/a
	 * rpsign			n/a derive from object = { planet | sign | house }
	 * rpsign2			n/a derive from subject = { planet | sign | house }
	 * duplicate		? if this has 2 representations
	 * retrograde
	 * code1
	 * indexNumber
	 * asType
	 * connector
    */

    /*
	 * For Uranus, Neptune and Pluto there is a preamble to be considered for
	 * generational contexts. There is a bug in the existing PC report where
	 * the generational context is generated twice
    */
    /**
     * analyse sign placement
     *
     * analyse sign placement
     *
     * @param integer planet in range 0-9 (planets), 10 (n.node), 11 (s.node), 12 (asc)
     * @param integer sign in range 0-11
     * @result void
     */
    function analyseSign( $planet, $sign ) {

        global $logger;
        $logger->debug("report::analyseSign($planet,$sign)");

        if( $planet >= 0 && $planet < 7 ) {
            /*
			 * Sun to Saturn
            */
            $chapter = "01";
            $code3 = sprintf("%02d",($planet+1));
        } else {
            /*
			 * Uranus to Pluto - Collective planets
            */
            if( $planet >= 7 && $planet < 10 ) {
                $book = "10";
                $chapter = "01";
                $code3 = sprintf("%02d",($planet+1));
                /* code4 = signs 01-12 */
            } else {
                // nodes
                switch($planet) {
                    case 10:	// N.Node
                        $chapter = "01";
                        $code3 = sprintf("%02d", ($planet+1));
                        break;
                    case 12:	// Ascendant
                        $chapter = "02";
                        $code3 = "01";
                        break;
                    default:
                        break;
                }
            }
        }

        // sign is in the range 0-11 so add 1 and format
        $code4 = sprintf("%02d", ($sign+1));

        /*
		 * If this planet is a ruler or co-ruler then we need to use a cross reference unless
		 * we are in the ascendant context
        */
        /* TODO */

        if( $this->type != "y3" ) {

            /*
			 * manage collective planets
            */
            if( $planet >= 7 && $planet < 10 ) {
                //Uncommented By Amit
// 				$attitude = $this->getBookHeading( $book, $chapter, $code3, $code4, $this->language );
// 				$description = $this->getBookParagraph( $book, $chapter, $code3, $code4, $this->language );				
//				$this->generate->SectionContent( "COLLECTIVE", $attitude, $description, true /* preamble only */ );
                //Uncommented By Amit

                $this->generate->SectionContent( "COLLECTIVE", isset($attitude), isset($description), true /* preamble only */ );
            } else {
                /*
				 * generate the professional content
				 * note that collective content should not be included if this is a "pc" report to avoid duplication
                */
                if( $this->type == "career" || $this->type == "pc" || $this->type == "pc3") {
                    /* avoid overwriting book for collective planets */
                    if( $planet < 7 || $planet > 9 ) {
                        /* professional book */
                        $book = "01";
                    }
                    $attitude = $this->getBookHeading( $book, $chapter, $code3, $code4, $this->language );
                    $description = $this->getBookParagraph( $book, $chapter, $code3, $code4, $this->language );
                    $this->generate->SectionContent( "PROFESSIONAL", $attitude, $description );
                }

                /*
				 * generate the personal content
                */
                if( $this->type == "personal" || $this->type == "pc" || $this->type == "pc3" ) {
                    /* avoid overwriting book for collective planets */
                    if( $planet < 7 || $planet > 9 ) {
                        /* personal book */
                        $book = "02";
                    }
                    $attitude = $this->getBookHeading( $book, $chapter, $code3, $code4, $this->language );
                    $description = $this->getBookParagraph( $book, $chapter, $code3, $code4, $this->language );
                    $this->generate->SectionContent( "PERSONAL", $attitude, $description );
                }
            }
        } else {
            // y3 stuff here although n/a
        }
    }

    /*
	 * analyseHouse
	 *
	 * analyseHouse
	 *
	 * @param Integer house
	 * @param Integer sign
	 * @return void
    */
    function analyseHouse( $planet, $house ) {

        global $logger;
        $logger->debug("report::analyseHouse($planet,$house)");

        $chapter = "03";
        $code3 = sprintf("%02d", ($planet+1));
        $code4 = sprintf("%02d", $house);

        if( $this->context == "static" ) {
            /*
			 * generate the professional content
			 * note that collective content should not be included if this is a "pc" report to avoid duplication
            */
            if( $this->type == "career" || $this->type == "pc" || $this->type == "pc3" ) {
                /* professional */
                $book = "01";
                $attitude = $this->getBookHeading( $book, $chapter, $code3, $code4, $this->language );
                $description = $this->getBookParagraph( $book, $chapter, $code3, $code4, $this->language );
                $this->generate->SectionContent( "PROFESSIONAL", $attitude, $description );
            }

            /*
			 * generate the personal content
            */
            if( $this->type == "personal" || $this->type == "pc" || $this->type == "pc3" ) {
                /* personal */
                $book = "02";
                $attitude = $this->getBookHeading( $book, $chapter, $code3, $code4, $this->language );
                $description = $this->getBookParagraph( $book, $chapter, $code3, $code4, $this->language );
                $this->generate->SectionContent( "PERSONAL", $attitude, $description );
            }
        }

        /*
		 * generate the dynamic content
		 * - book = 01 (professional) 02 (personal)
		 * - chapter = 04
		 * - code3 = 01 (jupiter) 02 (saturn)
		 * - code4 = house 01-12
        */
        $logger->debug("Reports::analyseHouse - testing for dynamics, context=$this->context, type=$this->type");
        if( $this->context == "dynamic" && ($this->type == "y3" || $this->type == "pc3") ) {
            $logger->debug("Reports::analyseHouse: book=$book, chapter=$chapter, code3=$code3, code4=$code4, language=$this->language");
            $chapter = "04";
            $code3 = sprintf("%02d", ($planet-4));
            $code4 = sprintf("%02d", $house);
            /* professional */
            $book = "01";
            $attitude = $this->getBookHeading( $book, $chapter, $code3, $code4, $this->language );
            $description = $this->getBookParagraph( $book, $chapter, $code3, $code4, $this->language );
            $this->generate->SectionContent( "PROFESSIONAL", $attitude, $description );
            /* personal */
            $book = "02";
            $attitude = $this->getBookHeading( $book, $chapter, $code3, $code4, $this->language );
            $description = $this->getBookParagraph( $book, $chapter, $code3, $code4, $this->language );
            $this->generate->SectionContent( "PERSONAL", $attitude, $description );
        }
    }

    /**
     * analyseAspect
     *
     * analyseAspect
     *
     * @param Integer subject planet making the aspect (1-10,11-12,13)
     * @param Integer object planet being aspected (1-10,11-12)
     * @return void
     */
    function analyseAspect($subject,$object,$dynamic_context) {

        global $logger;
        $logger->debug("report::analyseAspect($subject,$object,$dynamic_context)");

        $chapter = "05";
        if( intval($subject) > intval($object) ) {
            // this is likely to be a duplicate unless it is the ascendant
            if( $subject == 13 ) {
                $chapter = "06";
                $code3 = sprintf("%02d",$object);
                $code4 = sprintf("%02d",1);	// ascendant
                $duplicate = false;
            } else {
                $code3 = sprintf("%02d",$object);
                $code4 = sprintf("%02d",$subject);
                if( $dynamic_context === true ) {
                    $duplicate = false;
                } else {
                    $duplicate = true;
                }
            }
        } else {
            if( $object == 14 /* MC */ ) {
                $chapter = "06";
                $code3 = sprintf("%02d",$subject);
                $code4 = sprintf("%02d",10);	// MC
                $duplicate = false;
            } else {
                $code3 = sprintf("%02d",$subject);
                $code4 = sprintf("%02d",$object);
                $duplicate = false;
            }
        }

        /*
		 * generate the personal content
        */
        $logger->debug("***** HACKHACK *** context = ".$this->context.", dynamic context = ".$dynamic_context);
        if( $dynamic_context === false ) {
            $logger->debug("***** HACKHACK *** in static context");

            /*
			 * generate the professional content
			 * note that collective content should not be included if this is a "pc" report to avoid duplication
            */
            if( $this->type == "career" || $this->type == "pc" || $this->type == "pc3" ) {
                $logger->debug("***** HACKHACK *** in static context for career, pc or pc3");
                $book = "01";
                if( $duplicate === false ) {
                    $attitude = $this->getBookHeading( $book, $chapter, $code3, $code4, $this->language );
                    $description = $this->getBookParagraph( $book, $chapter, $code3, $code4, $this->language );
                    $this->generate->SectionContent( "PROFESSIONAL", $attitude, $description );
                } else {
                    $this->generate->CrossReference( $subject, $connector, $object );
                }
            }

            /*
			 * generate the personal content
            */
            if( $this->type == "personal" || $this->type == "pc" || $this->type == "pc3" ) {
                $logger->debug("***** HACKHACK *** in static context for personal, pc or pc3");
                $book = "02";
                if( $duplicate === false ) {
                    $attitude = $this->getBookHeading( $book, $chapter, $code3, $code4, $this->language );
                    $description = $this->getBookParagraph( $book, $chapter, $code3, $code4, $this->language );
                    $this->generate->SectionContent( "PERSONAL", $attitude, $description );
                }
            }

            /*
			 * Bk Ch C3 C4    = Description
			 * 05 05 02 06-10 = Emotions    - Moon aspecting Jupiter/Pluto
			 * 06 05 03 06-10 = The Mind    - Mercury aspecting Jupite/Pluto
			 * 07 05 04 06-10 = Love        - Venus aspecting Jupiter/Pluto
			 * 08 05 05 06-10 = Sex & Power - Mars aspecting Jupiter/Pluto
			 * no book 9!
			 * 10 05 06 07-10 = Collective  - Jupiter aspecting Saturn/Pluto
			 * 10 05 07 08-10 = Collective  - Saturn aspecting Uranus/Pluto
			 * 10 05 08 09-10 = Collective  - Uranus aspecting Neptune/Pluto
			 * 10 05 09 10    = Collective  - Neptune aspecting Pluto
            */
            /* REMOVED on request
			 if( (int)$code3 >= 2 and (int)$code3 <= 8
			 && (int)$code4 >= 6 and (int)$code4 <= 10) {
			 switch( (int)$code3 ) {
			 case 2:   $label = "EMOTIONS";	$book2 = "05";	break;
			 case 3:	$label = "THE MIND";	$book2 = "06";	break;
			 case 4:	$label = "LOVE";	$book2 = "07";	break;
			 case 5:	$label = "SEX & POWER";	$book2 = "08";	break;
			 case 6:	$label = "COLLECTIVE";	$book2 = "10";	break;
			 case 7:	$label = "COLLECTIVE";	$book2 = "10";	break;
			 case 8:	$label = "COLLECTIVE";	$book2 = "10";	break;
			 }
			 $attitude = $this->getBookHeading( $book2, $chapter, $code3, $code4, $this->language );
			 $description = $this->getBookParagraph( $book2, $chapter, $code3, $code4, $this->language );
			 $this->generate->SectionContent( $label, $attitude, $description );
			 }
            */
        }

        /*
		 * generate the dynamic content
		 * TODO cross referencing is different in this case because the hard aspects need to be folded
        */
        $logger->debug("***** HACKHACK *** just before dynamic context");
        if( $dynamic_context === true && ($this->type == "y3" || $this->type == "pc3") ) {
            $logger->debug("***** HACKHACK *** in dynamic context");
            $logger->debug("report::analyseAspect($subject,$object), duplicate state = $duplicate");
            /*
			 * Chapter 08 = Transits
            */
            $chapter = "08";
            /*
			 * Transiting planet
			 * - 01 = Jupiter
			 * - 02 = Saturn
			 * - 03 = Uranus
			 * - 04 = Neptune
			 * - 05 = Pluto
            */
            $code3 = sprintf("%02d",$subject-5);
            /*
			 * natal or transited planet
			 * - 01 (Sun) through 10 (Pluto)
			 * - 11 (N.Node)
			 * - 12 (S.Node)
            */
            $code4 = sprintf("%02d",$object);
            /*
			 * professional context
            */
            $book = "01";
            $attitude = $this->getBookHeading( $book, $chapter, $code3, $code4, $this->language );
            $description = $this->getBookParagraph( $book, $chapter, $code3, $code4, $this->language );
            $this->generate->SectionContent( "PROFESSIONAL", $attitude, $description );
            /*
			 * personal context
            */
            $book = "02";
            $attitude = $this->getBookHeading( $book, $chapter, $code3, $code4, $this->language );
            $description = $this->getBookParagraph( $book, $chapter, $code3, $code4, $this->language );
            $this->generate->SectionContent( "PERSONAL", $attitude, $description );
        }
    }

    function getAspectStrengthDescriptionIndex() {
        global $logger;
        $logger->debug("report::getAspectStrengthDescrptionIndex($this->aspectStrengthDescriptionIndex)");
        $this->aspectStrengthDescriptionIndex++;
        return $this->aspectStrengthDescriptionIndex % 3;
    }

    function analyseAspectStrength($strength, $index ) {

        global $logger;
        $logger->debug("report::analyseAspectStrength($strength,$index)");

        /*
		 * set the book value to 02 (personal) or 01 (professional / combined)
        */
        $logger->error("report::analyseAspectStrength: type = $this->type");
        switch( $this->type ) {
            case "personal":
                $book = "02";
                break;
            case "career":
            case "pc":
            case "pc3":
                $book = "01";
                break;
            default:
                $logger->error(
                        "report::analyseAspectStrength - in default case within switch"
                );
                break;
        }

        /*
		 * chapter = 16 for personal / professional / combined types
        */
        $chapter = "16";

        /*
		 * Calculate the aspect strength from range 000 to 002
		 * 000 -> book = 01/02 chapter = 16 code3 = 01 code 4 = 03
		 * 001 -> book = 01/02 chapter = 16 code3 = 01 code 4 = 01
		 * 002 -> book = 01/02 chapter = 16 code3 = 01 code 4 = 02
        */
        $code3 = sprintf("%02d",intval($strength)+1); /* 0..2 */

        /* cycle descriptions */
        $code4 = sprintf("%02d",intval($this->getAspectStrengthDescriptionIndex())+1);

        $this->generate->AspectStrengthPreamble
                (
                $this->getBookParagraph
                (
                $book,
                $chapter,
                $code3,
                $code4,
                $this->language
                )
        );
    }

    function getAspectTypeDescriptionIndex() {
        $this->aspectTypeDescriptionIndex++;
        return $this->aspectTypeDescriptionIndex % 3;
    }

    /*
	 * Aspect type
	 * there is clearly work required to be done here as the text changes according to
	 * parameters that I need to discern. Initially a middle of the road approach will
	 * be taken whilst various reports are analysed to spot a trend.
	 *
	 * Conjunctions are tricky
	 * Soft aspects all seem to be the same
	 * Hard aspects all seem to be the same
	 *
	 * New engine sets aspect type = 0 (Conjunction), 1 (soft), 2 (hard)
    */
    /*
	 * Conjunction_1 appears across books 1-8 as chapter=17, code3=1, code4=1
	 * Conjunction_2 appears across books 1-8 as chapter=17, code3=1, code4=2
	 * Conjunction_3 appears across books 1-8 as chapter=17, code3=1, code4=3
	 *
	 * Sextile_1 appears across books 1-8 as chapter=17, code3=5, code4=1
	 * Sextile_2 appears across books 1-8 as chapter=17, code3=5, code4=1
	 * Sextile_3 appears across books 1-8 as chapter=17, code3=5, code4=1
	 *
	 * Square_1 appears across books 1-8 as chapter=17, code3=7/10, code4=1
	 * Square_2 appears across books 1-8 as chapter=17, code3=7, code4=2
	 * Square_3 appears across books 1-8 as chapter=17, code3=7, code4=3
	 *
	 * Trine_1 appears across books 1-8 as chapter=17, code3=9, code4=1
	 * Trine_2 appears across books 1-8 as chapter=17, code3=9, code4=2
	 * Trine_3 appears across books 1-8 as chapter=17, code3=9, code4=3 apart from book2 where code3=2
	 *
	 * Opposition_1 appears across books 1-8 as chapter=17, code3=13, code4=1
	 * Opposition_2 appears across books 1-8 as chapter=17, code3=13, code4=2
	 * Opposition_3 appears across books 1-8 as chapter=17, code3=13, code4=3
    */
    function analyseAspectType( $type, $index, $aspect ) {

        global $logger;
        $logger->debug("report::analyseAspectType($type,$index,$aspect)");

        /* select book base on report type */
        switch( $this->type ) {
            case "personal":
                $book = "02";
                break;
            case "career":
            case "pc":
            case "pc3":
                $book = "01";
                break;
            default:
                $logger->error("report::analyseAspectType - in default case within switch");
                break;
        }

        /* chapter 17 in all cases */
        $chapter = "17";

        /* code3 represents the aspect type */
        switch( $aspect ) {
            case '000':
                $code3 = "01";
                break;
            case '060':
                $code3 = "05";
                break;
            case '090':
                $code3 = "07";
                break;
            case '120':
                $code3 = "09";
                break;
            case '180':
                $code3 = "13";
                break;
            default:
            /* error */
                break;
        }

        /* 2,9 = good aspect between planets */
        /* 1 in all cases = conjunction shows a fusion of energies */
        /* 7 in all cases = difficulty in integrating */
        /* 9 in all cases apart from [2,17,2,3] = good aspect between planets (chapters 1,2,4-8) */
        #
        # TEMP CODE
        #	$code3 = $type + 1;
        #	$code3 = sprintf("%02d",$code3);
        switch( intval($type) ) {
            case 0:	/* conjunction */
                $code3 = '01';	/* HACK */
                break;
            case 1:	/* positive */
                if( $book == '02' ) {
                    $code3 = '02';
                } else {
                    $code3 = '09';
                }
                break;
            case 2:	/* negative */
                $code3 = '07';
                break;
            default:
                die("type = $type");
                break;
        }
        # END OF TEMP CODE

        /* not really sure what is going on here! */
        /* 3 = conjunction shows a fusion of energies */
        /* 2 = difficulty in integrating */
        /* 3 = good aspect between planets */
        #
        # TEMP CODE
        #	$code4 = ((($book + ($index+1)) %3 ) + 1 );
        #	$code4 = sprintf("%02d",$code4);
        switch( intval($type) ) {
            case 0:	/* conjunction */
                $code4 = '03';	/* HACK */
                break;
            case 1:	/* positive */
                $code4 = '03';
                break;
            case 2:	/* negative */
                $code4 = '02';
                break;
            default:
                die("type = $type");
                break;
        }
        # END OF TEMP CODE

        $this->generate->AspectTypePreamble
                (
                $this->getBookParagraph
                (
                $book,
                $chapter,
                $code3,
                $code4,
                $this->language
                )
        );
    }

    /**
     * rotateTransitingAspectTypeDescription
     *
     * @return void
     */
    function rotateTransitingAspectTypeDescription() {
        $this->transitingAspectTypeDescription++;
        $this->transitingAspectTypeDescription = $this->transitingAspectTypeDescription % 3;
    }

    /**
     * analyseTransitingAspectType
     *
     * @return void
     */
    function analyseTransitingAspectType( $type ) {

        global $logger;

        $logger->debug("report::analyseTransitingAspectType($type)");
        $book = "01";
        $chapter = "19";
        switch( $type) {
            case "000":
                $code3 = "01";
                $code4 = "02";
                break;
            case "090":
                $code3 = "07";
                $code4 = "01";
                break;
            case "180":
                $code3 = "13";
                $code4 = "02";
                break;
            default:
                $logger->error("report::analyseTransitingAspectType($type) - invalid aspect");
        }
        $this->generate->AspectTypePreamble
                (
                $this->getBookParagraph
                (
                $book,
                $chapter,
                $code3,
                $code4,
                $this->language
                )
        );
    }

    /**
     * getBookHeading
     *
     * @param string book expressed as %02d
     * @param string chapter expressd as %02d
     * @param string code3 expressed as %02d
     * @param string code4 expressed as %02d
     * @param string language expressed as %2s
     * @return string content for paragraph in the appropriate language
     */
    function getBookHeading( $book, $chapter, $code3, $code4, $language ) {

        global $logger;
        $logger->debug("Report::getBookHeading( $book, $chapter, $code3, $code4, $language )");

        switch(strtoupper(trim($language))) {
            case 'EN':
                $content = new BookUK();
                break;
            case 'DK':
                $content = new BookDK();
                break;
            case 'DU':
                $content = new BookDU();
                break;
            case 'DE':
                $content = new BookGE();
                break;
            case 'GR':
                $content = new BookGR();
                break;
            case 'SP':
                $content = new BookSP();
                break;
            case 'NO':
                $content = new BookNO();
                break;
            case 'SE':
                $content = new BookSW();
                break;
            default:
                $logger->error("Design fault = Report::getBookHeading");
                return "No rows returned for ($book, $chapter, $code3, $code4)!";
                break;
        }

        $booklist = $content->GetList
                (
                array(
                array(	'book',		'=',	$book		),
                array(	'chapter',	'=',	$chapter	),
                array(	'code3',	'=',	$code3		),
                array(	'code4',	'=',	$code4		)
        ));

        foreach( $booklist as $bookinstance ) {
            $logger->debug("Report::getBookHeading($bookinstance->attitude");
            return $bookinstance->attitude;
        }
        $logger->error("Design fault = Report::getBookHeading");
        return "No rows returned for ($book, $chapter, $code3, $code4)!";
    }

    /**
     * getBookParagraph
     *
     * @param string book expressed as %02d
     * @param string chapter expressd as %02d
     * @param string code3 expressed as %02d
     * @param string code4 expressed as %02d
     * @param string language expressed as %2s
     * @return string content for paragraph in the appropriate language
     */
    function getBookParagraph( $book, $chapter, $code3, $code4, $language ) {

        global $logger;
        $logger->debug("Report::getBookParagraph( $book, $chapter, $code3, $code4, $language )");

        switch(strtoupper(trim($language))) {
            case 'EN':
                $content = new BookUK();
                break;
            case 'DK':
                $content = new BookDK();
                break;
            case 'DU':
                $content = new BookDU();
                break;
            case 'DE':
                $content = new BookGE();
                break;
            case 'GR':
                $content = new BookGR();
                break;
            case 'SP':
                $content = new BookSP();
                break;
            case 'NO':
                $content = new BookNO();
                break;
            case 'SE':
                $content = new BookSW();
                break;
            default:
                $logger->error("Design fault = Report::getBookParagraph");
                return "No rows returned for ($book, $chapter, $code3, $code4)!";
                break;
        }

        $booklist = $content->GetList
                (
                array(
                array(	'book',		'=',	$book	),
                array(	'chapter',	'=',	$chapter),
                array(	'code3',	'=',	$code3	),
                array(	'code4',	'=',	$code4	)
        ));

        foreach( $booklist as $bookinstance ) {
            $logger->debug("Report::getBookParagraph($bookinstance->description");
            return $bookinstance->description;
        }
        $logger->error("Design fault = Report::getBookParagraph");
        return "No rows returned for ($book, $chapter, $code3, $code4)!";
    }
}
?>
