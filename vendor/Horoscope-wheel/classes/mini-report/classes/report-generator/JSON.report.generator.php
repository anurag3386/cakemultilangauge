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
 * @author Amit Parmar <amit@ntechcorporate.com>
 * @copyright Copyright (c) 2014-2015, World of Wisdom
 * @version 1.0
 *
 */
class Report {
	
	var $JSONReportContent = array();

	/**
	 * Enumerated report type.
	 * Valid types include
	 * <ul>
	 * 	<li>Personal Mini report with Option A</li>
	 * 	<li>Personal Mini report with Option B</li>
	 * </ul>
	 *
	 * @var string $type
	 */
	var $ReportType;		/* Mini Report [ Option A ] AND [ Option B ] */

	/**
	 * Context
	 * Valid types include
	 * <ul>
	 * 	<li>Dynamic</li>
	 * </ul>
	 *
	 * @var string $context
	 */
	var $Context;		/* context of process flow, static/dynamic */

	/**
	 * Language to be used for content generation
	 * <ul>
	 * 	<li>English ('en')</li>
	 * 	<li>Other languages experimental at this stage</li>
	 * </ul>
	 *
	 * @var string $language
	 */
	var $Language;	/* Report Language */

	/**
	 * Generator
	 *
	 * @var mixed $Generator
	 */
	var $Generator;	/* OutPut Text Engine. This is most critical task of the report */

	/**
	 * This is the context of the Birth Analyzer
	 */
	var $Analysis_Context;

	/**
	 * Chapter
	 * The chapter value is used when managing the cross references
	 * for planets in sign and in house. This is especially valid for
	 * planets that are rulers or co-rulers as their sections will
	 * have been published in the Life Path chapter
	 */
	var $Chapter;

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
	function Report($Name = '') {
		$this->setLanguage('en');

		// default is static
		$this->Context = "static";

		$this->retrogradeDescriptionIndex = 0;
		$this->aspectStrengthDescriptionIndex = 0;
		$this->aspectTypeDescriptionIndex = 0;
		
		$this->UserName = $Name;
	}

	/**
	 * Set Language
	 * - validate language setting
	 * - set new language
	 * - default setting = 'en'
	 */
	function setLanguage( $language ) {
		$this->Language = $language;
	}

	/**
	 * Process the data returned by the chart calculation and subsequent analysis.
	 * Break down the analysis data into chapters and sections
	 * Each chapter contains a number of sections
	 * Each section contains a header context and the section content
	 * The section header context contains retrograde, aspect type and aspect strength subsections
	 * The section content contains professional, personal and general subsections
	 *
	 * @param [ Object ] $AnalysisContext  [ Report requires analysis data ]
	 * @param [ Object ] $Book			   [ Report requires content       ]
	 * @param [ Object ] $Generator		   [ report requires a generator   ]
	 */
	function Run( $AnalysisContext, $Book,  $Generator) {
		//echo "<pre>********************* Run()</pre>";
		$this->Analysis_Context = $AnalysisContext;
		$this->Generator = $Generator;
		$this->Language = $this->Generator->Language;

		/* clear content cache */
		$content = '';

		$this->Generator->ReportHeader();
		//$this->Generator->IntroductionSection();

		$this->Analysis_Context->BOF();
		
		$rTotalRecord = (strlen($this->Analysis_Context->Current_AnalysisContext) / 48 );
		$rIndex = 1;
		$LastChapter = "";
		$ContextArray = array(0 => "static", 1 => "dynamic", 2 => "dynamic-t");
				
		while( $this->Analysis_Context->EOF() === false ) {
			$this->Context = "static";
			$content = "";
			$chapter = $this->getChapter();			
			$content .= $this->getSection();
			
			/** 
			 * 100101008060100910000000000000000000000000001004		== STATIC
			 * 100101008060100910000100000000000000000000001004		== DYANMIC
			 * 100031006180110100000201028201409082015000202005		== DYANMIC-T
			 * 1234567890123456789012345678901234567890123456789
			 */
			
			//0 = static, 1 = dynamic, , 2 = dynamic-t
			$ContextIndex = $this->getSectionContext($content);
			$ContextType =  $ContextArray[$ContextIndex];
			$this->Context = $ContextType;
			
			/**
			 * 1st Record will be SUN In Sign/House
			 * 2nd Record will be MERCURY In Sign/House 
			 * 3rd Record will be Strongest Aspect 
			 * 4rd Record will be Strongest Transit 
			 */
			//if( $rIndex == ($rTotalRecord - 1) && $this->ReportType == "mini-b") {	// This is Aspect
			if( $rIndex == 3 && $this->ReportType == "mini-b") {	// This is 	// This is Aspect
				$ContextType = 'dynamic';
			} else if( $rIndex == 4 && $this->ReportType == "mini-b") {	// This is Transit
				$ContextType = 'dynamic-t';
			}
			
			//echo "<pre>AnalyseChapterMiniReports()***************$chapter | $content | $ContextType == [ $ContextIndex ] ( $rTotalRecord )</pre>";			
			$this->AnalyseChapterMiniReports( $chapter, $content, $ContextType );		
		
			$this->Analysis_Context->GetNext();
			$rIndex++;
		}
		
		$this->Generator->ReportTrailer();
		//$this->Generator->GenericSummary();		
	}
	
	function AnalyseChapterMiniReports($chapter, $content, $ContextType) {
		//echo "<pre>*************************AnalyseChapterMiniReports() IN</pre>";		
		global $chapterHeadings;
		global $top_object1;
		
		if( $chapter >= 0 && (strtolower($ContextType) == "static" && ($this->ReportType == "mini-a" || $this->ReportType == "mini-b")) ) {		
			//echo "chapter:" .$chapterHeadings[$this->Language][$chapter] ."<br />";
			
			$this->Generator->ChapterHeader( $chapterHeadings[$this->Language][$chapter]  );			
			/** Printing out Planet Generic Text */
			$sectionContent = substr($content, (0*48), 48);
			$sIndex = $this->getSectionHeaderSubject( $sectionContent );
			$cIndex = $this->getSectionHeaderConnector( $sectionContent );
			$oIndex = $this->getSectionHeaderObject( $sectionContent );
		
			if($sIndex != "") {			
				if(intval($sIndex) <= 1005) {
					/** Printing out Planet Generic Text */
					$this->JSONReportContent = array_merge($this->JSONReportContent, $this->Generator->GenericIntroductionSection( $sIndex, $oIndex ));
				}
			}
		}
		
		if( strtolower($ContextType) == strtolower("dynamic") && strtolower($this->ReportType) == "mini-b") {
			/** Printing out Planet Generic Text */
			$sectionContent = substr($content, (0*48), 48);
			$sIndex = $this->getSectionHeaderSubject( $sectionContent );
			$cIndex = $this->getSectionHeaderConnector( $sectionContent );
			$oIndex = $this->getSectionHeaderObject( $sectionContent );			
			$oIndex = intval( substr($oIndex,2,2 ) ) + 1000;
			
			$this->JSONReportContent = array_merge($this->JSONReportContent, $this->Generator->GenericAspectIntroductionSection($sIndex, $cIndex, $oIndex));
		}
		
		if( strtolower($ContextType) == "dynamic-t" && strtolower($this->ReportType) == "mini-b") {
			/** Printing out Planet Generic Text */
			$sectionContent = substr($content, (0*48), 48);
			$sIndex = $this->getSectionHeaderSubject( $sectionContent );
			$cIndex = $this->getSectionHeaderConnector( $sectionContent );
			$oIndex = $this->getSectionHeaderObject( $sectionContent );
			$oIndex = intval( substr($oIndex,2,2 ) ) + 1000;
			
			$StartDate = $this->getSectionSubHeadStartDate($sectionContent);
			$EndDate = $this->getSectionSubHeadEndDate($sectionContent);
			
// 			echo "<pre>$sectionContent ==== ContextType ==> $ContextType</pre>";
// 			echo "<pre>$sIndex == $cIndex == $oIndex == $StartDate == $EndDate</pre>";
			
			$this->JSONReportContent = array_merge($this->JSONReportContent, 
					$this->Generator->GenericTransitIntroductionSection($this->UserName, $sIndex, $cIndex, $oIndex, $StartDate, $EndDate));			
		}
		
		for( $i = 0; $i < strlen($content) / 48; $i++ ) {
			$sectionContent = substr($content, ($i*48), 48);
			$this->analyseSection( $sectionContent );
		}
	}

	/**
	 * Analyse Chapter
	 *
	 * Generator the chapter heading and then iterate through the section records
	 * performing the section analysis within each section of the chapter.
	 *
	 * @access private
	 * @param Integer chapter
	 * @param String content
	 * @return void
	 */
	function analyseChapter( $chapter, $content ) {
		global $chapterHeadings;

		if( $chapter > 0 && ($this->Context == "static" || ($this->ReportType == "mini-a" || $this->ReportType == "mini-b")) ) {

			$this->Generator->ChapterHeader( $chapterHeadings[$this->Language][$chapter]  );
				
			/** Printing out Planet Generic Text */
			$sectionContent = substr($content, (0*48), 48);
			$sIndex = $this->getSectionHeaderSubject( $sectionContent );

			if($sIndex != "") {
				if($sIndex < 1005) {
					/** Printing out Planet Generic Text */
					$this->Generator->GenericIntroductionSection( $sIndex  );
				} else {
					/** Printing out Planet Generic Text */
					$this->Generator->GenericAspectIntroductionSection();
				}
			}
		}

		if($this->isDynamicTransit($content)) {
			$this->Context = "dynamic-t";
		}

		if( strtolower($this->Context) == strtolower("dynamic-t") && strtolower($this->ReportType) == strtolower("mini-b")) {
			/** Printing out Planet Generic Text */
			$sectionContent = substr($content, (0*48), 48);
			$sIndex = $this->getSectionHeaderSubject( $sectionContent );
			$this->Generator->GenericTransitIntroductionSection();
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
		//echo "<pre>*************************analyseSection()</pre>";
		global $top_object1;
		global $top_connector;
		global $top_object2;
		global $top_retrograde;

		$sIndex = $this->getSectionHeaderSubject( $content );
		$cIndex = $this->getSectionHeaderConnector( $content );
		$oIndex = $this->getSectionHeaderObject( $content );
		
		$checkOIndex = $oIndex;
		
		switch( substr($checkOIndex,0,2 ) ) {
			case "11":
				$oIndex = intval($oIndex) - 100;
				break; 
		}

		/**
		* If this is the S.Node then we have already managed content based on the N.Node.
		*/
		if( $oIndex == "1011" ) {
			/* "report::analyseSection, ignoring S.Node as N.Node has been covered" */
			return;
		}
						
		/* Manage the main section content */
		switch( substr($checkOIndex,0,2 ) ) {
			case "01":
				/*
				 * Planet in Sign
				 * Format = 01PP
				 * Where PP = 00 (Sun) through to 09 (Pluto), 10-11 (N/S Nodes) and 12 (Ascendant)
				 * Need to be aware of the cross referencing here as it is based
				 * on the subindexed digits which can have a disorienting effect
				 * if we are in the wrong chapter.
				 */
				
				//"Report::analyseSection - case planet in sign");
				$subject = intval( substr( $sIndex, 2, 2 ) );
				$object = intval( substr( $oIndex, 2, 2 ) );

				//"Report::analyseSection - planet ($subject) in sign($object)");
				/* include in sign only and omit nodes */
				if( intval($subject) < 10 /* NNode */ ) {
					$this->analyseSectionRetrogradeState
					(
							/* embedded call returns 0 or 1 (an index into the top_retrograde language table) */
							( ( $this->getSectionHeaderRetrograde ( $content ) == 1 ) ? true : false )
					);
				}
								
				if( $subject != 11 /* S.Node */ ) {			
					if( $this->Generator->toc_xref === true ) {
						$this->Generator->CrossReference( $sIndex, $cIndex, $oIndex, "static" );
						$this->Generator->toc_xref = false;
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

				//report:analyseSection - case planet in house
				$subject = intval ( substr( $this->getSectionHeaderSubject ( $content ), 2, 2 ) );
				$object = intval ( substr( $this->getSectionHeaderObject ( $content ), 2, 2 ) );

				if( $subject != 11 /* S.Node */ ) {
					if( $this->Generator->toc_xref === true ) {
						$this->Generator->CrossReference( $sIndex, $cIndex, $oIndex, "static" );
						$this->Generator->toc_xref = false;
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
				//report::analyseSection - case planet in aspect
				$subject = intval(substr($this->getSectionHeaderSubject( $content ),2,2 ));
				$object = intval(substr($this->getSectionHeaderObject( $content ),2,2 ));
				$aspect = intval($this->getSectionAspect( $content ));

				//report::analyseSection - planet ($subject) in aspect ($aspect) to planet ($object)
				$strength = $this->getSectionAspectStrength( $content );
				$type = $this->getSectionAspectType( $content );
				$index = $this->getSectionIndex( $content );

				//report::analyseSection - case planet in aspect - check for duplicates
				$subject++;
				$object++;
								
				/**  We are only concerned with transits from Jupiter to Pluto **/
				if( $this->ReportType == "mini-b" && $this->isDynamicAspect($content) ) {				
					if( ( intval( $subject ) >= 6 /* Jupiter */ && intval ( $subject ) <= 10 /* Pluto */ ) ) {
						if( intval($object) == 15 /* IC */|| intval($object) == 16 /* DC */ ) {
							//Report::analyseSection - transiting planet ($subject) in aspect to angle ($object)
						} else {
							//Report::analyseSection - transiting planet ($subject) in aspect to natal planet ($subject)	
							$this->analyseAspect( $subject, $object, $this->isDynamicAspect($content) );
						}
					}
				} else {
					//Report::analyseSection - case planet in static aspect					
					if( intval($subject) > intval($object) ) {
						/** special case for the Ascendant as this appears at the start of the report before all planets and nodes. **/
						if( $subject == 13 /* Asc */ ) {
							$this->analyseAspect( $subject, $object, $this->isDynamicAspect($content) );
						} else {
							if( $subject != 12 /* S.Node */ ) {
								//$this->Generator->CrossReference( $sIndex, $cIndex, $oIndex );
							}
						}
					} else {
						/** Another special case this time for the South Node. It seems stupid
						 to display content for both the nodes. The South Node is dropped and the North Node compensates. **/
						if( $subject != 12 /* S.Node */ ) {
							$this->analyseAspect( $subject, $object, $this->isDynamicAspect($content) );
						}
					}
				}
				break;
			case "11":
				/*
				 * Planet by Transit
				 * Format = 10PP
				 * Where PP = 01 (Sun) through to 10 (Pluto), 11-12 (N/S Nodes) and 13 (Ascendant)
				 * TODO: check for duplicates here rather than in the analysis code
				 */
				//report::analyseSection - case planet in aspect
				$subject = intval(substr($this->getSectionHeaderSubject( $content ),2,2 ));
				$object = intval(substr($this->getSectionHeaderObject( $content ),2,2 ));
				$aspect = intval($this->getSectionAspect( $content ));

				//report::analyseSection - planet ($subject) in aspect ($aspect) to planet ($object)
				$strength = $this->getSectionAspectStrength( $content );
				$type = $this->getSectionAspectType( $content );
				$index = $this->getSectionIndex( $content );

				//report::analyseSection - case planet in aspect - check for duplicates
				$subject++;
				$object++;
				/**  We are only concerned with transits from Jupiter to Pluto **/
				if( $this->ReportType == "mini-b" && $this->isDynamicTransit($content) ) {
					//Report::analyseSection - case planet in dynamic aspect
					if( ( intval( $subject ) >= 6 /* Jupiter */ && intval ( $subject ) <= 10 /* Pluto */ ) ) {
						if( intval($object) == 15 /* IC */|| intval($object) == 16 /* DC */ ) {
							//Report::analyseSection - transiting planet ($subject) in aspect to angle ($object)
						} else {
							//Report::analyseSection - transiting planet ($subject) in aspect to natal planet ($subject)
							$StartDate = $this->getSectionSubHeadStartDate($content);
							$EndDate = $this->getSectionSubHeadEndDate($content);
							
							$this->JSONReportContent["strongest-transit"]["StartDate"] = $StartDate;
							$this->JSONReportContent["strongest-transit"]["EndDate"] = $EndDate;							
							$this->analyseTransit( $subject, $object, $this->isDynamicTransit($content) );
						}
					}
				} else {
					echo "<pre> Planet by Transit ELSE </pre>";
					//Report::analyseSection - case planet in static aspect
					//Tricky one because pc3 comes in here but only when dealing with the dynamic aspects
					if( intval($subject) > intval($object) ) {
						/** special case for the Ascendant as this appears at the start of the report before all planets and nodes. **/
						if( $subject == 13 /* Asc */ ) {
							$this->analyseTransit( $subject, $object, $this->isDynamicTransit($content) );
						} else {
							if( $subject != 12 /* S.Node */ ) {
							}
						}
					} else {
						/** Another special case this time for the South Node. It seems stupid
							to display content for both the nodes. The South Node is dropped and the North Node compensates. **/
						if( $subject != 12 /* S.Node */ ) {
							$this->analyseTransit( $subject, $object, $this->isDynamicTransit($content) );
						}
					}
				}
				break;
			default:
				//Report::analyseSection, invalid case in switch statement
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

	/**
	 * Analyse Section Retrograde State
	 * @param boolean retrograde motion state
	 */
	function analyseSectionRetrogradeState( $retrograde ) {
		if( $retrograde === true ) {
			//Report::analyseSectionRetrogradeState - planet is retrograde

			$chapter = "18";
			$code3="01";

			/* Rotate content to avoid repetition */
			$code4 = $this->getRetrogradeDescriptionIndex();

			switch( $this->ReportType ) {
				case "personal":
				case "mini-a":
				case "mini-b":
					$book = "02";
					break;
				case "career":
				case "pc":
				case "pc3":
				case "y3":
					$book = "01";
					break;
			}

			/**
			 * Retrograde_1 = book=1/2, chapter=18, code3=1, code4=4
			 * Retrograde_2 = book=1/2, chapter=18, code3=1, code4=5
			 * Retrograde_3 = book=1/2, chapter=18, code3=1, code4=1
			 */
			$this->Generator->RetrogradePreamble( $this->getBookParagraph( $book, $chapter, $code3, $code4, $this->Language) );
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
		return $this->Analysis_Context->getChapter();
	}

	/**
	 * getSection
	 *
	 * @access private
	 * @return String returns the section context (48 characters)
	 */
	function getSection() {
		//Report::getSection, context = $this->Context, section = " . $this->Analysis_Context->getSection()
		return $this->Analysis_Context->getSection();
	}

	function getSectionHeaderSubject( $content ) {
		//Report::getSectionHeaderSubject returns " . substr($content,5,4);
		return substr($content, 5, 4);
	}

	function getSectionHeaderConnector( $content ) {
		//report::getSectionHeaderConnector returns " . substr($content,9,3);
		return substr($content,9,3);
	}

	function getSectionHeaderObject( $content ) {
		//Report::getSectionHeaderObject returns " . substr($content,12,4);
		return substr($content,12,4);
	}

	function getSectionSubHeadStartDate( $content ) {
		//DD-MM-YYYY
		return sprintf("%02d-%02d-%04d", substr($content,23,2), substr($content,25,2), substr($content,27,4));
	}

	function getSectionSubHeadEndDate( $content ) {
		//DD-MM-YYYY
		return sprintf("%02d-%02d-%04d", substr($content,31,2), substr($content,33,2), substr($content,35,4));
	}

	/**
	 * @param String $content
	 * @return integer 0=static, 1=dynamic, , 2=dynamic-t
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

	/**
	 * @return boolean true if section context = 1
	 */
	function isDynamicTransit($content) {
		return ( $this->getSectionContext( $content ) == 2 );
	}

	/**
	 * Retrograde:
	 * - direct = 0
	 * - retrograde = 1
	 * This is used to index the $top_retrograde array
	 */
	function getSectionHeaderRetrograde( $content ) {
		return substr($content, 16, 1);
	}

	function getSectionAspectStrength( $content ) {
		return substr($content, 40, 3);
	}

	function getSectionAspectType( $content ) {
		return substr($content, 43, 2);
	}

	function getSectionAspect( $content ) {
		return substr($content, 9, 3);
	}

	/**
	 * DevNote - not really sure what to do with this but included for backwards
	 * compatibility and not otherwise used
	 * @param string content string
	 * @return integer record index
	 */
	function getSectionIndex( $content ) {
		return substr($content, 45, 3);
	}

	/**
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
	 *
	 * For Uranus, Neptune and Pluto there is a preamble to be considered for
	 * generational contexts. There is a bug in the existing PC report where the generational context is Generatord twice
	 *
	 * analyse sign placement
	 *
	 * @param integer planet in range 0-9 (planets), 10 (n.node), 11 (s.node), 12 (asc)
	 * @param integer sign in range 0-11
	 * @result void
	 */
	function analyseSign( $planet, $sign ) {
		//echo "<pre>**********************************analyseSign( $planet, $sign )</pre>";
		if( $planet >= 0 && $planet < 7 ) {
			/**  Sun to Saturn **/
			$chapter = "01";
			$code3 = sprintf("%02d",($planet+1));
		} else {
			/** Uranus to Pluto - Collective planets */
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

		/** sign is in the range 0-11 so add 1 and format **/
		$code4 = sprintf("%02d", ($sign+1));

		/** If this planet is a ruler or co-ruler then we need to use a cross reference unless we are in the ascendant context **/
		/* TODO */
		if( $this->ReportType != "y3" ) {
			/**  manage collective planets **/
			if( $planet >= 7 && $planet < 10 ) {
				$this->Generator->SectionContent( "COLLECTIVE", isset($attitude), isset($description), true /* preamble only */ );
			} else {				
				/** Generator the personal content **/
				if( $this->ReportType == "mini-a" || $this->ReportType == "mini-b") {
					/* avoid overwriting book for collective planets */
					if( $planet < 7 || $planet > 9 ) {
						/* personal book */
						$book = "02";
					}
						
					$attitude = $this->getBookHeading( $book, $chapter, $code3, $code4, $this->Language );
					$description = $this->getBookParagraph( $book, $chapter, $code3, $code4, $this->Language );
					//echo "<pre>Attitude=> $attitude</pre>";
					
					global $top_object1;
					$KeyName = sprintf("%s-in-sign",$top_object1[$this->Language][$planet+1000]);					
					$this->JSONReportContent[$KeyName]["content"] = $description;
					
					$this->Generator->SectionContent( "PERSONAL", $attitude, $description );
				}
			}
		} else {
			// y3 stuff here although n/a
		}
	}

	/**
	 *
	 * analyseHouse
	 *
	 * @param Integer house
	 * @param Integer sign
	 * @return void
	 */
	function analyseHouse( $planet, $house ) {
		$chapter = "03";
		$code3 = sprintf("%02d", ($planet+1));
		$code4 = sprintf("%02d", $house);

		if( $this->Context == "static" ) {
			/** Generator the personal content **/
			if( $this->ReportType == "mini-a" || $this->ReportType == "mini-b") {
				/* personal */
				$book = "02";
				$attitude = $this->getBookHeading( $book, $chapter, $code3, $code4, $this->Language );
				$description = $this->getBookParagraph( $book, $chapter, $code3, $code4, $this->Language );
				
				//echo "<pre>Attitude=> $attitude :: $description</pre>";
				
				$this->Generator->SectionContent( "PERSONAL", $attitude, $description );
			}
		}

		/**
		 * Generator the dynamic content
		 * - Book = 	01 (Professional) 02 (Personal)
		 * - Chapter =  04
		 * - Code3 = 	01 (jupiter) 02 (saturn)
		 * - Code4 = 	house 01-12
		 */
		//Reports::analyseHouse - testing for dynamics, context=$this->Context, type=$this->ReportType
		//if( $this->Context == "dynamic" && ($this->ReportType == "y3" || $this->ReportType == "pc3") ) {
		if( $this->Context == "dynamic" && ($this->ReportType == "mini-a" || $this->ReportType == "mini-b")) {
			$logger->debug("Reports::analyseHouse: book=$book, chapter=$chapter, code3=$code3, code4=$code4, language=$this->Language");
			$chapter = "04";
			$code3 = sprintf("%02d", ($planet-4));
			$code4 = sprintf("%02d", $house);
								
			/* personal */
			$book = "02";
			$attitude = $this->getBookHeading( $book, $chapter, $code3, $code4, $this->Language );
			$description = $this->getBookParagraph( $book, $chapter, $code3, $code4, $this->Language );
			//echo "<pre>Attitude=> $attitude :: $description</pre>";
			
			$this->Generator->SectionContent( "PERSONAL", $attitude, $description );
		}
	}

	/**
	 * analyseAspect
	 *
	 * @param Integer subject planet making the aspect (1-10,11-12,13)
	 * @param Integer object planet being aspected (1-10,11-12)
	 * @return void
	 */
	function analyseAspect($subject, $object, $dynamic_context) {
		$chapter = "05";

		if( intval($subject) > intval($object) ) {
			/**  this is likely to be a duplicate unless it is the ascendant	**/
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

		/** Generator the personal content **/
		/** *** HACKHACK *** context = ".$this->Context.", dynamic context = ".$dynamic_context **/
		if( $dynamic_context === false ) {
			/***** HACKHACK *** in static context" ***/

			/** Generator the personal content **/
			if( $this->ReportType == "mini-a" || $this->ReportType == "mini-b") {
				/***** HACKHACK *** in static context for personal, pc or pc3" **/
				$book = "02";
				if( $duplicate === false ) {
					$attitude = $this->getBookHeading( $book, $chapter, $code3, $code4, $this->Language );
					$description = $this->getBookParagraph( $book, $chapter, $code3, $code4, $this->Language );										
					$this->Generator->SectionContent( "PERSONAL", $attitude, $description );

					global $top_object1;
					$this->JSONReportContent["strongest-aspect"]["content"] = utf8_encode( $description );
				}
			}

			/**
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
		}

		/** Generator the dynamic content
		 * TODO cross referencing is different in this case because the hard aspects need to be folded
		 */
		/***** HACKHACK *** just before dynamic context" **/
		if( $dynamic_context === true && $this->ReportType == "mini-b" ) {

			/***** HACKHACK *** in dynamic context" **/
			/* report::analyseAspect($subject,$object), duplicate state = $duplicate" */
				
			/** Chapter 08 = Transits */
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
				
			/**
			 * natal or transited planet
			 * - 01 (Sun) through 10 (Pluto)
			 * - 11 (N.Node)
			 * - 12 (S.Node)
			*/
			$code4 = sprintf("%02d",$object);
											
			/** personal context **/
			$book = "02";
			$attitude = $this->getBookHeading( $book, $chapter, $code3, $code4, $this->Language );
			$description = $this->getBookParagraph( $book, $chapter, $code3, $code4, $this->Language );
			$this->Generator->SectionContent( "PERSONAL", $attitude, $description );
	
			global $top_object1;
			$this->JSONReportContent["strongest-aspect"]["content"] = utf8_encode( $description );
		}
	}
	
	/**
	 * analyseTransit
	 *
	 * @param Integer subject planet making the aspect (1-10,11-12,13)
	 * @param Integer object planet being aspected (1-10,11-12)
	 * @return void
	 */
	function analyseTransit($subject, $object, $dynamic_context) {
		$chapter = "05";
	
		if( intval($subject) > intval($object) ) {
			/**  this is likely to be a duplicate unless it is the ascendant	**/
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
	
		/** Generator the personal content **/
		/** *** HACKHACK *** context = ".$this->Context.", dynamic context = ".$dynamic_context **/
		if( $dynamic_context === false ) {
			/***** HACKHACK *** in static context" ***/
	
			/** Generator the personal content **/
			if( $this->ReportType == "mini-a" || $this->ReportType == "mini-b") {
				/***** HACKHACK *** in static context for personal, pc or pc3" **/
				$book = "02";
				if( $duplicate === false ) {
					$attitude = $this->getBookHeading( $book, $chapter, $code3, $code4, $this->Language );
					$description = $this->getBookParagraph( $book, $chapter, $code3, $code4, $this->Language );
					$this->Generator->SectionContent( "PERSONAL", $attitude, $description );
	
					global $top_object1;
					$this->JSONReportContent["strongest-transit"]["content"] = utf8_encode( $description );
				}
			}
	
			/**
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
		}
	
		/** Generator the dynamic content
		 * TODO cross referencing is different in this case because the hard aspects need to be folded
		 */
		/***** HACKHACK *** just before dynamic context" **/
		if( $dynamic_context === true && $this->ReportType == "mini-b" ) {
	
			/***** HACKHACK *** in dynamic context" **/
			/* report::analyseAspect($subject,$object), duplicate state = $duplicate" */
	
			/** Chapter 08 = Transits */
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
	
			/**
			 * natal or transited planet
			 * - 01 (Sun) through 10 (Pluto)
			 * - 11 (N.Node)
			 * - 12 (S.Node)
			*/
			$code4 = sprintf("%02d",$object);
				
			/** personal context **/
			$book = "02";
			$attitude = $this->getBookHeading( $book, $chapter, $code3, $code4, $this->Language );
			$description = $this->getBookParagraph( $book, $chapter, $code3, $code4, $this->Language );
			$this->Generator->SectionContent( "PERSONAL", $attitude, $description );
			
			//echo "<pre>JSONReportContent :::: $book, $chapter, $code3, $code4, $this->Language :::: $description :::</pre>";
			global $top_object1;
		
			$this->JSONReportContent["strongest-transit"]["content"] = utf8_encode( $description );
		}
	}

	function getAspectStrengthDescriptionIndex() {
		$this->aspectStrengthDescriptionIndex++;
		return $this->aspectStrengthDescriptionIndex % 3;
	}

	function analyseAspectStrength($strength, $index ) {
		/** set the book value to 02 (personal) or 01 (professional / combined) **/

		/*report::analyseAspectStrength: type = $this->ReportType" **/
		switch( $this->ReportType ) {
			case "personal":
			case "mini-a":
			case "mini-b":
				$book = "02";
				break;
			case "career":
			case "pc":
			case "pc3":
				$book = "01";
				break;
			default:
				/** report::analyseAspectStrength - in default case within switch" **/
				break;
		}

		/** chapter = 16 for personal / professional / combined types ***/
		$chapter = "16";

		/**
		 * Calculate the aspect strength from range 000 to 002
		 * 000 -> book = 01/02 chapter = 16 code3 = 01 code 4 = 03
		 * 001 -> book = 01/02 chapter = 16 code3 = 01 code 4 = 01
		 * 002 -> book = 01/02 chapter = 16 code3 = 01 code 4 = 02
		 */
		$code3 = sprintf("%02d",intval($strength)+1); /* 0..2 */

		/** Cycle Descriptions **/
		$code4 = sprintf("%02d",intval($this->getAspectStrengthDescriptionIndex())+1);

		$this->Generator->AspectStrengthPreamble
		(
				$this->getBookParagraph
				(
						$book,
						$chapter,
						$code3,
						$code4,
						$this->Language
				)
		);
	}

	function getAspectTypeDescriptionIndex() {
		$this->aspectTypeDescriptionIndex++;
		return $this->aspectTypeDescriptionIndex % 3;
	}

	/**
	 * Aspect type
	 *  there is clearly work required to be done here as the text changes according to
	 *  parameters that I need to discern. Initially a middle of the road approach will
	 *  be taken whilst various reports are analysed to spot a trend.
	 *
	 *  Conjunctions are tricky
	 *  Soft aspects all seem to be the same
	 *  Hard aspects all seem to be the same
	 *
	 *  New engine sets aspect type = 0 (Conjunction), 1 (soft), 2 (hard)
	 *
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
	 *
	 */
	function analyseAspectType( $type, $index, $aspect ) {
		/** select book base on report type **/
		switch( $this->ReportType ) {
			case "personal":
			case "mini-a":
			case "mini-b":
				$book = "02";
				break;
			case "career":
			case "pc":
			case "pc3":
				$book = "01";
				break;
			default:
				/** report::analyseAspectType - in default case within switch **/
				break;
		}

		/** chapter 17 in all cases **/
		$chapter = "17";

		/** code3 represents the aspect type **/
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

		/** 2,9 = good aspect between planets **/
		/** 1 in all cases = conjunction shows a fusion of energies **/
		/** 7 in all cases = difficulty in integrating **/
		/** 9 in all cases apart from [2,17,2,3] = good aspect between planets (chapters 1,2,4-8) **/
		# TEMP CODE
		#	$code3 = $type + 1;
		#	$code3 = sprintf("%02d",$code3);
		switch( intval($type) ) {
			case 0:					/* conjunction */
				$code3 = '01';		/* HACK */
				break;
			case 1:					/* positive */
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

		/** not really sure what is going on here! 			**/
		/** 3 = conjunction shows a fusion of energies 		**/
		/** 2 = difficulty in integrating 					**/
		/** 3 = good aspect between planets 				**/
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

		$this->Generator->AspectTypePreamble
		(
			$this->getBookParagraph
			(
					$book,
					$chapter,
					$code3,
					$code4,
					$this->Language
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
				/** report::analyseTransitingAspectType($type) - invalid aspect" **/
		}

		$this->Generator->AspectTypePreamble
		(
				$this->getBookParagraph
				(
						$book,
						$chapter,
						$code3,
						$code4,
						$this->Language
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
				/**Design fault = Report::getBookHeading" **/
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
				)
		);

		foreach( $booklist as $bookinstance ) {
			/** Report::getBookHeading($bookinstance->attitude") **/
			return $bookinstance->attitude;
		}
		/** Design fault = Report::getBookHeading") **/
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
				)
		);

		foreach( $booklist as $bookinstance ) {
			/** Report::getBookParagraph($bookinstance->description") **/
			return $bookinstance->description;
		}
		/** "Design fault = Report::getBookParagraph") **/
		return "No rows returned for ($book, $chapter, $code3, $code4)!";
	}
}