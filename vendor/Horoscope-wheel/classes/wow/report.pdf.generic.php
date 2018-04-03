<?php
/*
 * Created on 25-Aug-2005
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

/**
 * @package Generators
 * @subpackage PDF
 * @author Andy Gray <andy.gray@astro-consulting.co.uk>
 * @copyright Copyright (c) 2005, Andy Gray
 */
class PDF_BOOKMARK extends FPDF
{
  // bookmark support
  var $outlines=array();
  var $OutlineRoot;

  function Bookmark($txt,$level=0,$y=0) {
    if($y==-1)
      $y=$this->GetY();
    $this->outlines[]=array('t'=>$txt,'l'=>$level,'y'=>$y,'p'=>$this->PageNo());
  }

  function _putbookmarks()
  {
    $nb=count($this->outlines);
    if($nb==0)
      return;
    $lru=array();
    $level=0;
    foreach($this->outlines as $i=>$o)
      {
	if($o['l']>0)
          {
	    $parent=$lru[$o['l']-1];
	    //Set parent and last pointers
	    $this->outlines[$i]['parent']=$parent;
	    $this->outlines[$parent]['last']=$i;
	    if($o['l']>$level)
              {
		//Level increasing: set first pointer
		$this->outlines[$parent]['first']=$i;
              }
          }
	else
	  $this->outlines[$i]['parent']=$nb;
	if($o['l']<=$level and $i>0)
          {
	    //Set prev and next pointers
	    $prev=$lru[$o['l']];
	    $this->outlines[$prev]['next']=$i;
	    $this->outlines[$i]['prev']=$prev;
          }
	$lru[$o['l']]=$i;
	$level=$o['l'];
      }
    //Outline items
    $n=$this->n+1;
    foreach($this->outlines as $i=>$o)
      {
	$this->_newobj();
	$this->_out('<</Title '.$this->_textstring($o['t']));
	$this->_out('/Parent '.($n+$o['parent']).' 0 R');
	if(isset($o['prev']))
	  $this->_out('/Prev '.($n+$o['prev']).' 0 R');
	if(isset($o['next']))
	  $this->_out('/Next '.($n+$o['next']).' 0 R');
	if(isset($o['first']))
	  $this->_out('/First '.($n+$o['first']).' 0 R');
	if(isset($o['last']))
	  $this->_out('/Last '.($n+$o['last']).' 0 R');
	$this->_out(sprintf('/Dest [%d 0 R /XYZ 0 %.2f null]',1+2*$o['p'],($this->h-$o['y'])*$this->k));
	$this->_out('/Count 0>>');
	$this->_out('endobj');
      }
    //Outline root
    $this->_newobj();
    $this->OutlineRoot=$this->n;
    $this->_out('<</Type /Outlines /First '.$n.' 0 R');
    $this->_out('/Last '.($n+$lru[0]).' 0 R>>');
    $this->_out('endobj');
  }

  function _putresources()
  {
    parent::_putresources();
    $this->_putbookmarks();
  }
  
  function _putcatalog()
  {
    parent::_putcatalog();
    if(count($this->outlines)>0)
      {
	$this->_out('/Outlines '.$this->OutlineRoot.' 0 R');
	$this->_out('/PageMode /UseOutlines');
      }
  }

  function Circle($x,$y,$r,$style='') {
    $this->Ellipse($x,$y,$r,$r,$style);
  }

  function Ellipse($x,$y,$rx,$ry,$style='D') {

    if($style=='F')
      $op='f';
    elseif($style=='FD' or $style=='DF')
      $op='B';
    else
      $op='S';

    $lx=4/3*(M_SQRT2-1)*$rx;
    $ly=4/3*(M_SQRT2-1)*$ry;
    $k=$this->k;
    $h=$this->h;
    $this->_out(sprintf('%.2f %.2f m %.2f %.2f %.2f %.2f %.2f %.2f c',
			($x+$rx)*$k,($h-$y)*$k,
			($x+$rx)*$k,($h-($y-$ly))*$k,
			($x+$lx)*$k,($h-($y-$ry))*$k,
			$x*$k,($h-($y-$ry))*$k));
    $this->_out(sprintf('%.2f %.2f %.2f %.2f %.2f %.2f c',
			($x-$lx)*$k,($h-($y-$ry))*$k,
			($x-$rx)*$k,($h-($y-$ly))*$k,
			($x-$rx)*$k,($h-$y)*$k));
    $this->_out(sprintf('%.2f %.2f %.2f %.2f %.2f %.2f c',
			($x-$rx)*$k,($h-($y+$ly))*$k,
			($x-$lx)*$k,($h-($y+$ry))*$k,
			$x*$k,($h-($y+$ry))*$k));
    $this->_out(sprintf('%.2f %.2f %.2f %.2f %.2f %.2f c %s',
			($x+$lx)*$k,($h-($y+$ry))*$k,
			($x+$rx)*$k,($h-($y+$ly))*$k,
			($x+$rx)*$k,($h-$y)*$k,
			$op));
  }

  function Sector($xc, $yc, $r, $a, $b, $style='FD', $cw=true, $o=90) {
    if($cw){
      $d = $b;
      $b = $o - $a;
      $a = $o - $d;
    } else {
      $b += $o;
      $a += $o;
    }
    // ****************************
    // Andy's Notes
    // The following is commented out as it falls on integer boundaries where
    // I need to maintain the calculation in the real domain
    //    $a = ($a%360)+360;
    //    $b = ($b%360)+360;
    //    if ($a > $b) {
    //      $b +=360;
    //		}
    // Replacement code using fmod rather than %
    $a = fmod($a,360.0)+360.0;
    $b = fmod($b,360.0)+360.0;
    if ($a > $b) {
      $b +=360.0;
    }
    // end of andy's modification
    $b = $b/360*2*M_PI;
    $a = $a/360*2*M_PI;
    $d = $b-$a;
    if ($d == 0 ) {
      $d =2*M_PI;
    }
    $k = $this->k;
    $hp = $this->h;
    if($style=='F')
      $op='f';
    elseif($style=='FD' or $style=='DF')
      $op='b';
    else
      $op='s';
    if (sin($d/2)) {
      $MyArc = 4/3*(1-cos($d/2))/sin($d/2)*$r;
    }
    //first put the center
    $this->_out(sprintf('%.2f %.2f m',($xc)*$k,($hp-$yc)*$k));
    //put the first point
    $this->_out(sprintf('%.2f %.2f l',($xc+$r*cos($a))*$k,(($hp-($yc-$r*sin($a)))*$k)));
    //draw the arc
    if ($d < M_PI/2) {
      $this->_Arc($xc+$r*cos($a)+$MyArc*cos(M_PI/2+$a),
		  $yc-$r*sin($a)-$MyArc*sin(M_PI/2+$a),
		  $xc+$r*cos($b)+$MyArc*cos($b-M_PI/2),
		  $yc-$r*sin($b)-$MyArc*sin($b-M_PI/2),
		  $xc+$r*cos($b),
		  $yc-$r*sin($b)
		  );
    } else {
      $b = $a + $d/4;
      $MyArc = 4/3*(1-cos($d/8))/sin($d/8)*$r;
      $this->_Arc($xc+$r*cos($a)+$MyArc*cos(M_PI/2+$a),
		  $yc-$r*sin($a)-$MyArc*sin(M_PI/2+$a),
		  $xc+$r*cos($b)+$MyArc*cos($b-M_PI/2),
		  $yc-$r*sin($b)-$MyArc*sin($b-M_PI/2),
		  $xc+$r*cos($b),
		  $yc-$r*sin($b)
		  );
      $a = $b;
      $b = $a + $d/4;
      $this->_Arc($xc+$r*cos($a)+$MyArc*cos(M_PI/2+$a),
		  $yc-$r*sin($a)-$MyArc*sin(M_PI/2+$a),
		  $xc+$r*cos($b)+$MyArc*cos($b-M_PI/2),
		  $yc-$r*sin($b)-$MyArc*sin($b-M_PI/2),
		  $xc+$r*cos($b),
		  $yc-$r*sin($b)
		  );
      $a = $b;
      $b = $a + $d/4;
      $this->_Arc($xc+$r*cos($a)+$MyArc*cos(M_PI/2+$a),
		  $yc-$r*sin($a)-$MyArc*sin(M_PI/2+$a),
		  $xc+$r*cos($b)+$MyArc*cos($b-M_PI/2),
		  $yc-$r*sin($b)-$MyArc*sin($b-M_PI/2),
		  $xc+$r*cos($b),
		  $yc-$r*sin($b)
		  );
      $a = $b;
      $b = $a + $d/4;
      $this->_Arc($xc+$r*cos($a)+$MyArc*cos(M_PI/2+$a),
		  $yc-$r*sin($a)-$MyArc*sin(M_PI/2+$a),
		  $xc+$r*cos($b)+$MyArc*cos($b-M_PI/2),
		  $yc-$r*sin($b)-$MyArc*sin($b-M_PI/2),
		  $xc+$r*cos($b),
		  $yc-$r*sin($b)
		  );
    }
    //terminate drawing
    $this->_out($op);
  }

  function _Arc($x1, $y1, $x2, $y2, $x3, $y3 ) {
    $h = $this->h;
    $this->_out(sprintf('%.2f %.2f %.2f %.2f %.2f %.2f c',
			$x1*$this->k,
			($h-$y1)*$this->k,
			$x2*$this->k,
			($h-$y2)*$this->k,
			$x3*$this->k,
			($h-$y3)*$this->k));
  }

  function SetDash($black=false,$white=false) {
    if($black and $white)
      $s=sprintf('[%.3f %.3f] 0 d',$black*$this->k,$white*$this->k);
    else
      $s='[] 0 d';
    $this->_out($s);
  }
}

/**
 * @package Generators
 * @subpackage PDF
 * @author Andy Gray <andy.gray@astro-consulting.co.uk>
 * @copyright Copyright (c) 2005, Andy Gray
 */
class PDF_A4 extends PDF_BOOKMARK
{
  var $report_title		= 'Sample Report';
  var $report_addressee	= 'Sample Customer';
  var $franchise_holder;
  var $franchise_website	= 'www.world-of-wisdom.com';
  var $isregistered		= false;
	
  function SetReportTitle( $title ) {
    $this->report_title = trim($title);
  }
	
  function SetReportAddressee( $name ) {
    $this->report_addressee = trim($name);
  }
	
  function SetFranchiseHolder( $name ) {
    $this->isregistered = true;
    $this->franchise_holder = trim($name);
  }
	
  function SetFranchiseWebsite( $site ) {
    $this->franchise_website = trim($site);
  }
	
  // the width and height do not seem to be used here
  // function Justify($text,$w = 90,$h = 5) {
  function Justify($text,$w = 87,$h = 5) {
    $this->SetTextColor(128,0,0);
    $this->MultiCell(
		     87,	 // width
		     5,		 // height
		     $text,	 // content
		     0,		 // dunno
		     'J'	 // alignment
		     );
  }
	
  function Header() {
    global $logger;
    $logger->debug("PDF_A4::Header");
    if($this->PageNo() == 1) {
      $this->SetTopMargin(20); /* was 32 */ /* was 16 */
      $this->SetY(20); /* was 32 */ /* was 16 */
    }
    $this->SetLeftMargin(16); // was 10 but need a 6mm gutter
    $logger->debug("PDF_A4::Header - page = ".$this->PageNo().", XOffset = ". $this->GetX() .", YOffset = ".$this->GetY());
    $this->SetAutoPageBreak( true, 32 ); /* this was previously 30 */
  }
}

/**
 * @package Generators
 * @subpackage PDF
 * @author Andy Gray <andy.gray@astro-consulting.co.uk>
 * @copyright Copyright (c) 2005, Andy Gray
 */
class PDF_A4_2COL extends PDF_A4 {

  var $col=0;
  var $column_width = 87; // was 90 pre gutter

  function SetCol($col) {
    //Move position to a column
    $this->col=$col;
    $this->SetLeftMargin(113); // was 110 but now need the gutter
    $this->SetX($col);
  }
	
  function AcceptPageBreak() {
    if($this->col<1) {			 // i.e. = 0
      //Go to next column
      $this->SetCol($this->col+1);
      $this->SetTopMargin(20); /* was 32 */
      $this->SetY(20); /* was 32 */
      return false;		// don't issue a page break
    } else {
      // at this point we are at the foot of the right hand column
      // need to reset the column as we will be starting the left hand column of the new page
      // need to reset the margin to the left hand default
      // need to grant the page break - return true
      $this->SetCol(0); 
      $this->SetLeftMargin(16); // was 10 but need a 6mm gutter
      $this->SetY(62);
      return true;		// issue a page break
    }
  }
}

/**
 * @package Generators
 * @subpackage PDF
 * @author Andy Gray <andy.gray@astro-consulting.co.uk>
 * @copyright Copyright (c) 2005, Andy Gray
 */
class PDF_USLetter extends PDF_BOOKMARK
{
  var $report_title		= 'Sample Report';
  var $report_addressee	= 'Sample Customer';
  var $franchise_holder;
  var $franchise_website	= 'www.world-of-wisdom.com';
  var $isregistered		= false;
	
  function SetReportTitle( $title ) {
    $this->report_title = trim($title);
  }
	
  function SetReportAddressee( $name ) {
    $this->report_addressee = trim($name);
  }
	
  function SetFranchiseHolder( $name ) {
    $this->isregistered = true;
    $this->franchise_holder = trim($name);
  }
	
  function SetFranchiseWebsite( $site ) {
    $this->franchise_website = trim($site);
  }
	
  // the width and height do not seem to be used here
  function Justify($text,$w = 90,$h = 5) {
    $this->SetTextColor(128,0,0);
    $this->MultiCell(
		     90,					 // width
		     5,					 // height
		     $text,			 // content
		     0,					 // dunno
		     'J'					 // alignment
		     );
  }

	
  function Header() {
    if($this->PageNo() == 1) {
      $this->SetTopMargin(16);
      $this->SetY(16);
    }
    $this->SetAutoPageBreak( true, 30 );
  }
}

/**
 * @package Generators
 * @subpackage PDF
 * @author Andy Gray <andy.gray@astro-consulting.co.uk>
 * @copyright Copyright (c) 2005, Andy Gray
 */
class PDF_USLetter_2COL extends PDF_USLetter {

  var $col=0;
  var $column_width = 90;

  function SetCol($col) {
    //Move position to a column
    $this->col=$col;
    $this->SetLeftMargin(110);
    $this->SetX($col);
  }
	
  function AcceptPageBreak() {
    if($this->col<1) {			 // i.e. = 0
      //Go to next column
      $this->SetCol($this->col+1);
      $this->SetTopMargin(32);
      $this->SetY(32);
      return false;		// don't issue a page break
    } else {
      $this->SetCol(0);
      $this->SetLeftMargin(10);
      $this->SetY(62);
      return true;		// issue a page break
    }
  }
}

?>
