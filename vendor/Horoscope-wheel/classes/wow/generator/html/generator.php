<?php
  /**
   * HTML Generator
   *
   * @package Generators
   * @author Andy Gray <andy.gray@astro-consulting.co.uk>
   * @version 1.0
   */
class Generator {

  /**
   * Content
   */
  var $content;
  var $preamble;

  function Generator() {
    
    /* initialise content */
    $this->content = '';
    $this->preamble = '';
  }
	 		
  function ReportHeader() {
    global $logger;
    $logger->debug("Generator::ReportHeader");
    $this->content .= '<html>';
    $this->content .= '<head>';
    $this->content .= '<title>Full Astrological Report including Transits</title>';
    $this->content .= '</head>';

    $this->content .= '<body>';
    /* add table of contents using anchors here */
  }
	    
  /*
   * It is expected that this method is overloaded by the report generators
   */    
  function ChapterHeader( $chapter ) {
    global $logger;
    global $chapterHeadings;
    $logger->debug("Generator::ChapterHeader for chapter = $chapter, text = " . $chapterHeadings[$this->language][$chapter]);
    $this->content .= '<h1>' . $chapterHeadings[$this->language][$chapter] . '</h1>';
    /* add a name anchor here - use chapter number */
  }
    
  /**
   * Section Header
   *
   * This may appear in 2 forms
   * - <Planet|Angle> <aspect> <Planet|Angle>
   * - <Planet|Angle> in <Sign|House>  
   * Todo - need to consider retrograde label text generation
   */
  function SectionHeader( $subject, $connector, $object, $retrograde  ) {
    global $logger;
    $logger->debug("Generator::SectionHeader, p1=$subject, connector=$connector, object=$object");
    $this->content .= '<h1>' . "$subject $connector $object $retrograde" . '</h1>';
  }

  /**
   * SectionSubHead
   */
  function SectionSubHead( $start_date, $end_date ) {
    global $logger;
    $logger->debug("Generator::SectionSubHead, start=$start_date, end=$end_date");
    $this->content .= '<h2>' . sprintf("%s -> %s", $start_date, $end_date ) . '</h2>';
  }
    
  /**
   * RetrogradePreamble
   */
  function RetrogradePreamble( $content ) {
    global $logger;
    $logger->debug("Generator::RetrogradePreamble");
    $this->preamble .= '<span id="rx">' . $content . '</span>';
  }
    
  /**
   * AspectStrengthPreamble
   */
  function AspectStrengthPreamble( $content ) {
    global $logger;
    $logger->debug("Generator::AspectStrengthPreamble");
    $this->preamble .= '<span id="aspect-strength">' . $content . '</span>';
  }
    
  /**
   * AspectTypePreamble
   */
  function AspectTypePreamble( $content ) {
    global $logger;
    $logger->debug("Generator::AspectTypePreamble");
    $this->preamble .= '<span id="aspect-type">' . $content . '</span>';
    $this->content .= '<p id="preamble">' . $this->preamble . '</p>';
  }
    
  /**
   * SectionContent
   */
  function SectionContent( $context, $attitude, $description ) {
    global $logger;
    $logger->debug("Generator::SectionContent");
    $this->content .= '<p>' . '<span id="attitude">' . $attitude . '</span>' . '<span id="description">' . $description . '</span>' . '</p>';
  }
    
  /**
   * ReportTrailer
   */
  function ReportTrailer() {
    global $logger;
    $logger->debug("Generator::ReportTrailer");
    $this->content .= '</body>';
    $this->content .= '</html>';

    /* now write the html report to ~/spool/report-<orderid>.html */
  }
};
?>