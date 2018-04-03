<?php
/**
 * Script:
 * Author:
 *
 * Description
 */
 
class XMLGenerator {

	const CONTEXT_RECLEN = 48;
	
	const REC_OFFSET_CHAPTER = 0;
	const REC_OFFSET_SUBJECT = 5;
	const REC_OFFSET_CONNECTOR = 9;
	const REC_OFFSET_OBJECTSCOPE = 12;
	const REC_OFFSET_OBJECTVALUE = 14;
	const REC_OFFSET_RETROGRADE = 16;
	const REC_OFFSET_DYNAMIC = 21;
	const REC_OFFSET_STARTDATE = 23;
	const REC_OFFSET_ENDDATE = 31;
	const REC_OFFSET_ASPECTSTRENGTH = 40;
	const REC_OFFSET_ASPECTTYPE = 43;
	const REC_OFFSET_RECNO = 45;
	
	const LEN_OFFSET_CHAPTER = 5;
	const LEN_OFFSET_SUBJECT = 4;
	const LEN_OFFSET_CONNECTOR = 3;
	const LEN_OFFSET_OBJECTSCOPE = 2;
	const LEN_OFFSET_OBJECTVALUE = 2;
	const LEN_OFFSET_RETROGRADE = 1;
	const LEN_OFFSET_DYNAMIC = 1;
	const LEN_OFFSET_STARTDATE = 8;
	const LEN_OFFSET_ENDDATE = 8;
	const LEN_OFFSET_ASPECTSTRENGTH = 3;
	const LEN_OFFSET_ASPECTTYPE = 2;
	const LEN_OFFSET_RECNO = 3;

		private $_context;
	private $_iChapter; // iterator
	
	private $_xml;
	
	public function __construct( $context ) {
		$this->_context = $context;
		$this->_xml = new MyXmlWriter();
	}
	
	public function run() {
		$this->_xml->push('report');
		$this->_xml->element('copyright',"This document is copyright World of Wisdom and Astro Consulting UK. All Rights Reserved.");
		$this->_xml->push('chapters');
		$lastChapter = 0;
		/* iterate through records */
		for( $this->_iChapter = 0; $this->_iChapter < ( strlen($this->_context) / self::CONTEXT_RECLEN ); $this->_iChapter++ ) {
			/*
			 * Check whether this is a new chapter and therefore requires section/chapter closure
			 * Check whether we are at the start of the analysis also
			 */
			if( $lastChapter != $this->_getChapter() ) {
				if( $this->_BOF() === false ) {
					$this->_xml->pop(); // section
					$this->_xml->pop(); // chapter
				}
				/* create a new chapter context */
				$this->_xml->push('chapter', array( 'id' => $this->_getChapter() ) );
				$this->_xml->element('title', $this->_getChapterTitle());
				$this->_xml->push('sections');
			}
			
			/* manage section */
			if( $this->_isContainer() === true ) {
				/* this is a sign or house containment */
				if( $this->_isSignContainment() === true ) {
					/* sign */
					$this->_xml->push('section', array( 'type' => 'containment', 'container' => 'sign' ));
					$this->_xml->element('object', $this->_getObjectName(), array( 'id' => $this->_getSubject() ) );
					$this->_xml->element('sign', $this->_getSignName(), array( 'id' => $this->_getObjectValue() ) );
					$this->_xml->pop(); // section
				} else {
					/* house */
					$this->_xml->push('section', array( 'type' => 'containment', 'container' => 'house' ));
					$this->_xml->element('object', $this->_getObjectName(), array( 'id' => $this->_getSubject() ) );
					$this->_xml->element('house', $this->_getObjectValue(), array( 'id' => $this->_getObjectValue() ) );
					$this->_xml->pop(); // section
				}
			} else {
				/* this is an aspect or a transit */
				if( $this->_isDynamic() === true ) {
	echo substr( $this->_context, ($this->_iChapter * self::CONTEXT_RECLEN), self::CONTEXT_RECLEN ) . "\n";
					/* transit */
					$this->_xml->push('section', array( 'type' => 'transit' ) );
					$this->_xml->element('orb', $this->_getAspectName(), array( 'angle' => $this->_getConnector() ) );
					$this->_xml->element('type', $this->_getAspectType() );
					$this->_xml->element('strength', $this->_getAspectStrength() );
					$this->_xml->push('objects');
					$this->_xml->element('aspecting', $this->_getObjectName() );
					$this->_xml->element('aspected', $this->_getObjectName( false ) );
					$this->_xml->pop(); // objects
					$this->_xml->element('startdate', $this->_getStartDate() );
					$this->_xml->element('enddate', $this->_getEndDate() );
					$this->_xml->pop(); // section
				} else {
					/* aspect */
					$this->_xml->push('section', array( 'type' => 'aspect' ) );
					$this->_xml->element('orb', $this->_getAspectName(), array( 'angle' => $this->_getConnector() ) );
					$this->_xml->element('type', $this->_getAspectType() );
					$this->_xml->element('strength', $this->_getAspectStrength() );
					$this->_xml->push('objects');
					$this->_xml->element('aspecting', $this->_getObjectName() );
					$this->_xml->element('aspected', $this->_getObjectName( false ) );
					$this->_xml->pop(); // objects
					$this->_xml->pop(); // section
				}
			}
			$lastChapter = $this->_getChapter();
		}
		$this->_xml->pop(); // chapters
		$this->_xml->pop(); // report
		return $this->_xml->getXml();
	}
	
	private function _BOF() {
		return ($this->_iChapter == 0);
	}
	
	/**
	 * @return integer
	 */
	private function _getChapter() {
		return intval( substr( $this->_context, ($this->_iChapter * self::CONTEXT_RECLEN ), self::LEN_OFFSET_CHAPTER ) );
	}
	
	private function _getChapterTitle() {
		$titles = array(
			10001	=>	"Life Path",
			10002	=>	"Identity",
			10003	=>	"Emotions",
			10004	=>	"Mentality",
			10005	=>	"Values",
			10006	=>	"Drives",
			10007	=>	"Wisdom",
			10008	=>	"Challenges",
			10009	=>	"Originality",
			10010	=>	"Transcendence",
			10011	=>	"Transformation",
			10012	=>	"Destiny"
		);
		return $titles[ $this->_getChapter() ];
	}
	
	/**
	 * @return integer
	 */
	private function _getSubject() {
		return intval( substr( $this->_context, ($this->_iChapter * self::CONTEXT_RECLEN) + self::REC_OFFSET_SUBJECT, self::LEN_OFFSET_SUBJECT ) );
	}
	
	private function _getObjectName( $subject = true ) {
		$objects = array(
			1000 => 'Sun',
			1001 => 'Moon',
			1002 => 'Mercury',
			1003 => 'Venus',
			1004 => 'Mars',
			1005 => 'Jupiter',
			1006 => 'Saturn',
			1007 => 'Uranus',
			1008 => 'Neptune',
			1009 => 'Pluto',
			1010 => 'North Node',
			1011 => 'South Node',
			1012 => 'Ascendant',
			1013 => 'MC',
			1014 => 'IC',
			1015 => 'Descendant'
		);
		return $objects[ (($subject === true) ? $this->_getSubject() : ($this->_getObjectValue() + 1000)) ];
	}
	
	/**
	 * @return integer
	 */
	private function _getConnector() {
		return intval( substr( $this->_context, ($this->_iChapter * self::CONTEXT_RECLEN) + self::REC_OFFSET_CONNECTOR, self::LEN_OFFSET_CONNECTOR ) );
	}
	
	/**
	 * @return boolean
	 */
	private function _isContainer() {
		if( $this->_getConnector() == 200 ) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * @return integer
	 */
	private function _getObjectScope() {
		return intval( substr( $this->_context, ($this->_iChapter * self::CONTEXT_RECLEN) + self::REC_OFFSET_OBJECTSCOPE, self::LEN_OFFSET_OBJECTSCOPE ) );
	}
	
	private function _isSignContainment() {
		return ($this->_getObjectScope() == 1);
	}
	
	private function _isAspect() {
		return ($this->_getObjectScope() == 10);
	}

	private function _getAspectName() {
		$aspects = array(
			0	=>	"Conjunction",
			30	=>	"Semisextile",
			60	=>	"Sextile",
			120	=>	"Trine",
			45	=>	"Semisquare",
			90	=>	"Square",
			135	=>	"Sesquiquadrate",
			150	=>	"Quincunx",
			180	=>	"Opposition",
		);
		return $aspects[ $this->_getConnector() ];
	}
		
	private function _getObjectValue() {
		return intval( substr( $this->_context, ($this->_iChapter * self::CONTEXT_RECLEN) + self::REC_OFFSET_OBJECTVALUE, self::LEN_OFFSET_OBJECTVALUE ) );
	}

	private function _getSignName() {
		$signs = array('Aries','Taurus','Gemini','Cancer','Leo','Virgo','Libra','Scorpio','Sagittarius','Capricorn','Aquarius','Pisces');
		return $signs[ $this->_getObjectValue() ];
	}
		
	private function _isRetrograde() {
		if( intval( substr( $this->_context, ($this->_iChapter * self::CONTEXT_RECLEN) + self::REC_OFFSET_RETROGRADE, self::LEN_OFFSET_RETROGRADE ) ) == 1 ) {
			return true;
		} else {
			return false;
		}
	}
	
	private function _isDynamic() {
		if( intval( substr( $this->_context, ($this->_iChapter * self::CONTEXT_RECLEN) + self::REC_OFFSET_DYNAMIC, self::LEN_OFFSET_DYNAMIC ) ) == 1 ) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * @return string date expressed as DD-MM-YYYY
	 */
	private function _getStartDate() {
		$date = substr( $this->_context, ($this->_iChapter * self::CONTEXT_RECLEN) + self::REC_OFFSET_STARTDATE, self::LEN_OFFSET_STARTDATE );
		return sprintf("%02d-%02d-%04d", intval(substr($date,2,2)), intval(substr($date,0,2)), intval(substr($date,4,4)) );
	}
	
	/**
	 * @return string date expressed as DD-MM-YYYY
	 */
	private function _getEndDate() {
		$date = substr( $this->_context, ($this->_iChapter * self::CONTEXT_RECLEN) + self::REC_OFFSET_ENDDATE, self::LEN_OFFSET_ENDDATE );
		return sprintf("%02d-%02d-%04d", intval(substr($date,2,2)), intval(substr($date,0,2)), intval(substr($date,4,4)) );
	}
	
	private function _getAspectStrength() {
		return intval( substr( $this->_context, ($this->_iChapter * self::CONTEXT_RECLEN) + self::REC_OFFSET_ASPECTSTRENGTH, self::LEN_OFFSET_ASPECTSTRENGTH ) );
	}
	
	private function _getAspectType() {
		return intval( substr( $this->_context, ($this->_iChapter * self::CONTEXT_RECLEN) + self::REC_OFFSET_ASPECTTYPE, self::LEN_OFFSET_ASPECTTYPE ) );
	}
	
	private function _getRecordIndex() {
	}
	
};

?>
