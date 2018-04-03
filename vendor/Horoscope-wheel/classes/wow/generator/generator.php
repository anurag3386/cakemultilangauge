<?php
  /**
   * Generator
   *
   * @package Generators
   * @author Andy Gray <andy.gray@astro-consulting.co.uk>
   * @version 1.0
   */
class Generator {

  function Generator() {
    // pass
  }
	 		
  function ReportHeader() {
    global $logger;
    $logger->debug("Generator::ReportHeader");
  }
	    
  /*
   * It is expected that this method is overloaded by the report generators
   */    
  function ChapterHeader( $chapter ) {
    global $logger;
    global $chapterHeadings;
    $logger->debug("Generator::ChapterHeader for chapter = $chapter, text = " . $chapterHeadings[$this->language][$chapter]);
    print($chapterHeadings[$this->language][$chapter] . "\n");
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
    print("$subject $connector $object $retrograde\n");
  }

  /**
   * SectionSubHead
   */
  function SectionSubHead( $start_date, $end_date ) {
    global $logger;
    $logger->debug("Generator::SectionSubHead, start=$start_date, end=$end_date");
    print( sprintf("%s -> %s", $start_date, $end_date ));
  }
    
  /**
   * RetrogradePreamble
   */
  function RetrogradePreamble( $content ) {
    global $logger;
    $logger->debug("Generator::RetrogradePreamble");
    print("- retrograde preamble\n");
    print($content);
  }
    
  /**
   * AspectStrengthPreamble
   */
  function AspectStrengthPreamble( $content ) {
    global $logger;
    $logger->debug("Generator::AspectStrengthPreamble");
    print("- aspect strength preamble\n");
    print($content);
  }
    
  /**
   * AspectTypePreamble
   */
  function AspectTypePreamble( $content ) {
    global $logger;
    $logger->debug("Generator::AspectTypePreamble");
    print("- aspect type preamble\n");
    print($content);
  }
    
  /**
   * SectionContent
   */
  function SectionContent( $context, $attitude, $description ) {
    global $logger;
    $logger->debug("Generator::SectionContent");
    print($context . " - " . $attitude . " " . $description);
  }
    
  /**
   * ReportTrailer
   */
  function ReportTrailer() {
    global $logger;
    $logger->debug("Generator::ReportTrailer");
  }
};
?>