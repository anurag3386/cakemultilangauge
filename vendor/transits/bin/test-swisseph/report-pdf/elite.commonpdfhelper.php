<?php

/**
 * @author: Olivier
 * @license: Freeware
 * Description
 * This extension adds bookmark support. The method to add a bookmark is:

 * function Bookmark(string txt [, int level [, float y]])

 * txt: the bookmark title.
 * level: the bookmark level (0 is top level, 1 is just below, and so on).
 * y: the y position of the bookmark destination in the current page. -1 means the current position. Default value: 0.
 * 
 */

//Stream handler to read from global variables
class VariableStream {

    var $varname;
    var $position;

    function stream_open($path, $mode, $options, &$opened_path) {
        $url = parse_url($path);
        $this->varname = $url['host'];
        if (!isset($GLOBALS[$this->varname])) {
            trigger_error('Global variable ' . $this->varname . ' does not exist', E_USER_WARNING);
            return false;
        }
        $this->position = 0;
        return true;
    }

    function stream_read($count) {
        $ret = substr($GLOBALS[$this->varname], $this->position, $count);
        $this->position += strlen($ret);
        return $ret;
    }

    function stream_eof() {
        return $this->position >= strlen($GLOBALS[$this->varname]);
    }

    function stream_tell() {
        return $this->position;
    }

    function stream_seek($offset, $whence) {
        if ($whence == SEEK_SET) {
            $this->position = $offset;
            return true;
        }
        return false;
    }

    function stream_stat() {
        return array();
    }
}

//class PDF_Bookmark extends FPDF {
class PDF_Bookmark extends fpdi {

    // bookmark support

    var $outlines = array();
    var $OutlineRoot;

    function Bookmark($txt, $level=0, $y=0) {
 //       stream_wrapper_register('var', 'VariableStream');

        if ($y == -1)
            $y = $this->GetY();

        $this->outlines[] = array('t' => $txt, 'l' => $level, 'y' => $y, 'p' => $this->PageNo());
      
    }

    function _putbookmarks() {

        $nb = count($this->outlines);

        if ($nb == 0)
            return;

        $lru = array();

        $level = 0;

        foreach ($this->outlines as $i => $o) {

            if ($o['l'] > 0) {

                $parent = $lru[$o['l'] - 1];

                //Set parent and last pointers

                $this->outlines[$i]['parent'] = $parent;

                $this->outlines[$parent]['last'] = $i;

                if ($o['l'] > $level) {

                    //Level increasing: set first pointer

                    $this->outlines[$parent]['first'] = $i;
                }
            }

            else
                $this->outlines[$i]['parent'] = $nb;

            if ($o['l'] <= $level and $i > 0) {

                //Set prev and next pointers

                $prev = $lru[$o['l']];

                $this->outlines[$prev]['next'] = $i;

                $this->outlines[$i]['prev'] = $prev;
            }

            $lru[$o['l']] = $i;

            $level = $o['l'];
        }

        //Outline items

        $n = $this->n + 1;

        foreach ($this->outlines as $i => $o) {

            $this->_newobj();

            $this->_out('<</Title ' . $this->_textstring($o['t']));

            $this->_out('/Parent ' . ($n + $o['parent']) . ' 0 R');

            if (isset($o['prev']))
                $this->_out('/Prev ' . ($n + $o['prev']) . ' 0 R');

            if (isset($o['next']))
                $this->_out('/Next ' . ($n + $o['next']) . ' 0 R');

            if (isset($o['first']))
                $this->_out('/First ' . ($n + $o['first']) . ' 0 R');

            if (isset($o['last']))
                $this->_out('/Last ' . ($n + $o['last']) . ' 0 R');

            $this->_out(sprintf('/Dest [%d 0 R /XYZ 0 %.2f null]', 1 + 2 * $o['p'], ($this->h - $o['y']) * $this->k));

            $this->_out('/Count 0>>');

            $this->_out('endobj');
            //echo "TEST== ".  $o['p'] . "<br />";
        }

        //Outline root

        $this->_newobj();

        $this->OutlineRoot = $this->n;

        $this->_out('<</Type /Outlines /First ' . $n . ' 0 R');

        $this->_out('/Last ' . ($n + $lru[0]) . ' 0 R>>');

        $this->_out('endobj');
    }

    function _putresources() {

        parent::_putresources();

        $this->_putbookmarks();
    }

    function _putcatalog() {

        parent::_putcatalog();

        if (count($this->outlines) > 0) {

            $this->_out('/Outlines ' . $this->OutlineRoot . ' 0 R');

            $this->_out('/PageMode /UseOutlines');
        }
    }

    function Circle($x, $y, $r, $style='') {

        $this->Ellipse($x, $y, $r, $r, $style);
    }

    function Ellipse($x, $y, $rx, $ry, $style='D') {



        if ($style == 'F')
            $op = 'f';

        elseif ($style == 'FD' or $style == 'DF')
            $op = 'B';

        else
            $op = 'S';



        $lx = 4 / 3 * (M_SQRT2 - 1) * $rx;

        $ly = 4 / 3 * (M_SQRT2 - 1) * $ry;

        $k = $this->k;

        $h = $this->h;

        $this->_out(sprintf('%.2f %.2f m %.2f %.2f %.2f %.2f %.2f %.2f c', ($x + $rx) * $k, ($h - $y) * $k, ($x + $rx) * $k, ($h - ($y - $ly)) * $k, ($x + $lx) * $k, ($h - ($y - $ry)) * $k, $x * $k, ($h - ($y - $ry)) * $k));

        $this->_out(sprintf('%.2f %.2f %.2f %.2f %.2f %.2f c', ($x - $lx) * $k, ($h - ($y - $ry)) * $k, ($x - $rx) * $k, ($h - ($y - $ly)) * $k, ($x - $rx) * $k, ($h - $y) * $k));

        $this->_out(sprintf('%.2f %.2f %.2f %.2f %.2f %.2f c', ($x - $rx) * $k, ($h - ($y + $ly)) * $k, ($x - $lx) * $k, ($h - ($y + $ry)) * $k, $x * $k, ($h - ($y + $ry)) * $k));

        $this->_out(sprintf('%.2f %.2f %.2f %.2f %.2f %.2f c %s', ($x + $lx) * $k, ($h - ($y + $ry)) * $k, ($x + $rx) * $k, ($h - ($y + $ly)) * $k, ($x + $rx) * $k, ($h - $y) * $k, $op));
    }

    function Sector($xc, $yc, $r, $a, $b, $style='FD', $cw=true, $o=90) {

        if ($cw) {

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

        $a = fmod($a, 360.0) + 360.0;

        $b = fmod($b, 360.0) + 360.0;

        if ($a > $b) {

            $b +=360.0;
        }

        // end of andy's modification

        $b = $b / 360 * 2 * M_PI;

        $a = $a / 360 * 2 * M_PI;

        $d = $b - $a;

        if ($d == 0) {

            $d = 2 * M_PI;
        }

        $k = $this->k;

        $hp = $this->h;

        if ($style == 'F')
            $op = 'f';

        elseif ($style == 'FD' or $style == 'DF')
            $op = 'b';

        else
            $op = 's';

        if (sin($d / 2)) {

            $MyArc = 4 / 3 * (1 - cos($d / 2)) / sin($d / 2) * $r;
        }

        //first put the center

        $this->_out(sprintf('%.2f %.2f m', ($xc) * $k, ($hp - $yc) * $k));

        //put the first point

        $this->_out(sprintf('%.2f %.2f l', ($xc + $r * cos($a)) * $k, (($hp - ($yc - $r * sin($a))) * $k)));

        //draw the arc

        if ($d < M_PI / 2) {

            $this->_Arc($xc + $r * cos($a) + $MyArc * cos(M_PI / 2 + $a), $yc - $r * sin($a) - $MyArc * sin(M_PI / 2 + $a), $xc + $r * cos($b) + $MyArc * cos($b - M_PI / 2), $yc - $r * sin($b) - $MyArc * sin($b - M_PI / 2), $xc + $r * cos($b), $yc - $r * sin($b)
            );
        } else {

            $b = $a + $d / 4;

            $MyArc = 4 / 3 * (1 - cos($d / 8)) / sin($d / 8) * $r;

            $this->_Arc($xc + $r * cos($a) + $MyArc * cos(M_PI / 2 + $a), $yc - $r * sin($a) - $MyArc * sin(M_PI / 2 + $a), $xc + $r * cos($b) + $MyArc * cos($b - M_PI / 2), $yc - $r * sin($b) - $MyArc * sin($b - M_PI / 2), $xc + $r * cos($b), $yc - $r * sin($b)
            );

            $a = $b;

            $b = $a + $d / 4;

            $this->_Arc($xc + $r * cos($a) + $MyArc * cos(M_PI / 2 + $a), $yc - $r * sin($a) - $MyArc * sin(M_PI / 2 + $a), $xc + $r * cos($b) + $MyArc * cos($b - M_PI / 2), $yc - $r * sin($b) - $MyArc * sin($b - M_PI / 2), $xc + $r * cos($b), $yc - $r * sin($b)
            );

            $a = $b;

            $b = $a + $d / 4;

            $this->_Arc($xc + $r * cos($a) + $MyArc * cos(M_PI / 2 + $a), $yc - $r * sin($a) - $MyArc * sin(M_PI / 2 + $a), $xc + $r * cos($b) + $MyArc * cos($b - M_PI / 2), $yc - $r * sin($b) - $MyArc * sin($b - M_PI / 2), $xc + $r * cos($b), $yc - $r * sin($b)
            );

            $a = $b;

            $b = $a + $d / 4;

            $this->_Arc($xc + $r * cos($a) + $MyArc * cos(M_PI / 2 + $a), $yc - $r * sin($a) - $MyArc * sin(M_PI / 2 + $a), $xc + $r * cos($b) + $MyArc * cos($b - M_PI / 2), $yc - $r * sin($b) - $MyArc * sin($b - M_PI / 2), $xc + $r * cos($b), $yc - $r * sin($b)
            );
        }

        //terminate drawing

        $this->_out($op);
    }

    function _Arc($x1, $y1, $x2, $y2, $x3, $y3) {

        $h = $this->h;

        $this->_out(sprintf('%.2f %.2f %.2f %.2f %.2f %.2f c', $x1 * $this->k, ($h - $y1) * $this->k, $x2 * $this->k, ($h - $y2) * $this->k, $x3 * $this->k, ($h - $y3) * $this->k));
    }

    function SetDash($black=false, $white=false) {

        if ($black and $white)
            $s = sprintf('[%.3f %.3f] 0 d', $black * $this->k, $white * $this->k);

        else
            $s = '[] 0 d';

        $this->_out($s);
    }

    //Adding Dynamic image to PDF
    function PDF_MemImage($orientation='P', $unit='mm', $format='A4') {
        $this->FPDF($orientation, $unit, $format);
        
        $existed = in_array("var", stream_get_wrappers());
        if ($existed) {
        	stream_wrapper_unregister("var");
        }
        //Register var stream protocol
        stream_wrapper_register('var', 'VariableStream');
        
        if ($existed) {
        	stream_wrapper_restore("var");
        }
    }

    function MemImage($data, $x=null, $y=null, $w=0, $h=0, $link='') {
    	$existed = in_array("var", stream_get_wrappers());
    	if ($existed) {
    		stream_wrapper_unregister("var");
    	}
    	//Register var stream protocol
    	stream_wrapper_register('var', 'VariableStream');
    	
        //Display the image contained in $data
        $v = 'img' . md5($data);
        $GLOBALS[$v] = $data;
        $a = getimagesize('var://' . $v);
        if (!$a)
            $this->Error('Invalid image data');
        $type = substr(strstr($a['mime'], '/'), 1);
        $this->Image('var://' . $v, $x, $y, $w, $h, $type, $link);
        unset($GLOBALS[$v]);
        
        if ($existed) {
        	stream_wrapper_restore("var");
        }
    }

    function GDImage($im, $x=null, $y=null, $w=0, $h=0, $link='') {
        //Display the GD image associated to $im
        ob_start();
        imagepng($im);
        $data = ob_get_clean();
        $this->MemImage($data, $x, $y, $w, $h, $link);
    }
    
    function ConvertMMToPixels($MM) {
    	//return $MM * 72 / 25.4;
    	return $MM * 300 / 25.4;
    }
    
    function ConvertPixelsToMM($Pixels) {
    	//return $Pixels * 25.4 / 72;
    	return $Pixels * 25.4 / 300;
    }

    //Adding Dynamic image to PDF
}

/**
 * Description of CommonPDFHelper
 *
 * @author Amit Parmar
 * 
 */
//class CommonPDFHelper extends PDF_Bookmark {
class CommonPDFHelper extends PDF_Index {	
	
	function subWrite($h, $txt, $link='', $subFontSize=12, $subOffset=0)
	{
		// resize font
		$subFontSizeold = $this->FontSizePt;
		$this->SetFontSize($subFontSize);
	
		// reposition y
		$subOffset = ((($subFontSize - $subFontSizeold) / $this->k) * 0.3) + ($subOffset / $this->k);
		$subX        = $this->x;
		$subY        = $this->y;
		$this->SetXY($subX, $subY - $subOffset);
	
		//Output text
		$this->Write($h, $txt, $link);
	
		// restore y position
		$subX        = $this->x;
		$subY        = $this->y;
		$this->SetXY($subX,   $subY + $subOffset);
	
		// restore font size
		$this->SetFontSize($subFontSizeold);
	}	
	
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

class PDF_Index extends PDF_Bookmark
{

 	function AddPage($orientation='', $size='') {
 		//echo "<pre>*****GetThemeName() ** $orientation,$size</pre>";
        parent::AddPage($orientation,$size);
        
        /**
        $ThemeName = $this->GetThemeName();    
                
        $this->setSourceFile($ThemeName);
        $template = $this->ImportPage(1, '');
        $this->useTemplate($template);
        */
    }
	
    private function GetThemeName(){
    	echo "<pre>*****GetThemeName()</pre>";
    	$ThemeName = $GLOBALS['ThemePageArray']['Default'];
    	global $SectionPageNo;
    	global $SectionName;
    	global $ProcessedTheme;    	
    	
    	if(count($SectionPageNo) > 0 && is_array($SectionPageNo)) {
			$LastKey = end($SectionPageNo);
			$Name = $SectionName[$LastKey];
			
			if(count($ProcessedTheme) > 0 && is_array($ProcessedTheme)) {
				if($ProcessedTheme['Name'] == $Name && $ProcessedTheme['PageNo'] != $this->PageNo()) {
					$Name = sprintf("%s_BG", $ProcessedTheme['Name']);
				}
			}
			
			$ProcessedTheme = array('PageNo' => $this->PageNo(), 'Name' => str_replace('_BG', '', $Name) );
			$ThemeName = $GLOBALS['ThemePageArray'][$Name];
			
// 			echo "<pre>******* <br />LastKey : $LastKey : Name :: " .$Name ." PageNo : ". $this->PageNo()."</pre>";
// 			echo "<pre>$ThemeName</pre>";
// 			echo "<pre>";
// 			print_r($ProcessedTheme);
// 			echo "</pre>";
    	}
    	
    	return $ThemeName;
    }
	
	//http://www.fpdf.org/en/script/script13.php
	function CreateIndex() {
		echo "<pre>*****************************CreateIndex()</pre>";
		global $GenericText;
		global $Global_Language;
		
		$this->SetXY(0, 20);
		$this->SetAutoPageBreak(true, 20);
		$this->AddPage();
		$this->SetAutoPageBreak(true, 20);
		$this->SetX(0);
		$this->SetY(20);
		
	    //Index title
	    $this->SetFontSize(12);
	    //$this->Cell(0,5,'Index of Annotations',0,1,'C');
	    $this->Cell(0,5,$GenericText[$Global_Language]['Annotations'],0,1,'C');
	    $this->SetFontSize(10);
	    $this->Ln(3);
	
	    $size=sizeof($this->outlines);
	    $PageCellSize=$this->GetStringWidth('p. '.$this->outlines[$size-1]['p'])+2;
	    
	    for ($i=0;$i<$size;$i++){
	    	
	        //Offset
	        $level=$this->outlines[$i]['l'];
	        if($level>0)
	            $this->Cell($level*8);
	
	        //Caption
	        $str= sprintf("%s. %s", $i +1 , $this->outlines[$i]['t']);
	        $strsize=$this->GetStringWidth($str);
	        $avail_size=$this->w-$this->lMargin-$this->rMargin-$PageCellSize-($level*8)-4;
	        while ($strsize>=$avail_size){
	            $str=substr($str,0,-1);
	            $strsize=$this->GetStringWidth($str);
	        }
	        $this->Cell($strsize+2,$this->FontSize+2, $str);
	
	        //Filling dots
	        $w=$this->w-$this->lMargin-$this->rMargin-$PageCellSize-($level*8)-($strsize+2);
	        $nb=$w/$this->GetStringWidth('.');
	        $dots=str_repeat('.',$nb);
	        $this->Cell($w,$this->FontSize+2,$dots,0,0,'R');
	
	         //Page number
	        //$this->Cell($PageCellSize,$this->FontSize+2,'p. '.$this->outlines[$i]['p'],0,1,'R');
	        $NewPage =  $this->outlines[$i]['p'];
	        $NewPage = intval($NewPage ) + 3;
	        $this->Cell($PageCellSize,$this->FontSize+2,'p. '.$NewPage,0,1,'R');
	        
	        if($this->GetY() > 260) {
	        	echo "<pre>GetY ".$this->GetY()." AND GetX " .$this->GetX() . "</pre>";
	        	$this->SetXY(0, 20);
	        	$this->AddPage();
	        	$this->SetAutoPageBreak(true, 20);
				$this->SetX(0);
				$this->SetY(20);				
	        }
	    }
	}	
}

class CubicSplines {
	protected $aCoords;
	protected $aCrdX;
	protected $aCrdY;
	protected $aSplines = array();
	protected $iMinX;
	protected $iMaxX;
	protected $iStep;

	protected function prepareCoords(&$aCoords, $iStep, $iMinX = -1, $iMaxX = -1) {
		$this->aCrdX = array();
		$this->aCrdY = array();
		$this->aCoords = array();

		ksort($aCoords);
		foreach ($aCoords as $x => $y) {
			$this->aCrdX[] = $x;
			$this->aCrdY[] = $y;
		}

		$this->iMinX = $iMinX;
		$this->iMaxX = $iMaxX;

		if ($this->iMinX == -1)
			$this->iMinX = min($this->aCrdX);
		if ($this->iMaxX == -1)
			$this->iMaxX = max($this->aCrdX);

		$this->iStep = $iStep;
	}

	public function setInitCoords(&$aCoords, $iStep = 1, $iMinX = -1, $iMaxX = -1) {
		$this->aSplines = array();

		if (count($aCoords) < 4) {
			return false;
		}

		$this->prepareCoords($aCoords, $iStep, $iMinX, $iMaxX);
		$this->buildSpline($this->aCrdX, $this->aCrdY, count($this->aCrdX));
	}

	public function processCoords() {
		for ($x = $this->iMinX; $x <= $this->iMaxX; $x += $this->iStep) {
			$this->aCoords[$x] = $this->funcInterp($x);
		}

		return $this->aCoords;
	}

	private function buildSpline($x, $y, $n) {
		for ($i = 0; $i < $n; ++$i) {
			$this->aSplines[$i]['x'] = $x[$i];
			$this->aSplines[$i]['a'] = $y[$i];
		}

		$this->aSplines[0]['c'] = $this->aSplines[$n - 1]['c'] = 0;
		$alpha[0] = $beta[0] = 0;
		for ($i = 1; $i < $n - 1; ++$i) {
			$h_i = $x[$i] - $x[$i - 1];
			$h_i1 = $x[$i + 1] - $x[$i];
			$A = $h_i;
			$C = 2.0 * ($h_i + $h_i1);
			$B = $h_i1;
			$F = 6.0 * (($y[$i + 1] - $y[$i]) / $h_i1 - ($y[$i] - $y[$i - 1]) / $h_i);
			$z = ($A * $alpha[$i - 1] + $C);
			$alpha[$i] = - $B / $z;
			$beta[$i] = ($F - $A * $beta[$i - 1]) / $z;
		}

		for ($i = $n - 2; $i > 0; --$i) {
			$this->aSplines[$i]['c'] = $alpha[$i] * $this->aSplines[$i + 1]['c'] + $beta[$i];
		}

		for ($i = $n - 1; $i > 0; --$i) {
			$h_i = $x[$i] - $x[$i - 1];
			$this->aSplines[$i]['d'] = ($this->aSplines[$i]['c'] - $this->aSplines[$i - 1]['c']) / $h_i;
			$this->aSplines[$i]['b'] = $h_i * (2.0 * $this->aSplines[$i]['c'] + $this->aSplines[$i - 1]['c']) / 6.0 + ($y[$i] - $y[$i - 1]) / $h_i;
		}
	}

	private function funcInterp($x) {
		$n = count($this->aSplines);
		if ($x <= $this->aSplines[0]['x'])  {
			$s = $this->aSplines[1];
		} else {
			if ($x >= $this->aSplines[$n - 1]['x']) {
				$s = $this->aSplines[$n - 1];
			} else {
				$i = 0;
				$j = $n - 1;
				while ($i + 1 < $j) {
					$k = $i + ($j - $i) / 2;
					if ($x <= $this->aSplines[$k]['x']) {
						$j = $k;
					} else {
						$i = $k;
					}
				}

				$s = $this->aSplines[$j];
			}
		}

		$dx = ($x - $s['x']);
		return $s['a'] + ($s['b'] + ($s['c'] / 2.0 + $s['d'] * $dx / 6.0) * $dx) * $dx;
	}
}
?>