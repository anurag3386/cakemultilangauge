<?php
if( ! ini_get('date.timezone') ) {
	//date_default_timezone_set('GMT');
	date_default_timezone_set('UTC');
}
if (!defined('ROOTPATH')) {
	define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/');
}
// Configuration
require_once(ROOTPATH.'config.php');
// Startup
require_once(ROOTPATH.'startup.php');



$language->load("free_horoscope/free-horoscope",$config->get('config_language'));

//require_once(ROOTPATH."helper/date-function.php");

class DateFunction {
	public $returnValue = array();
	public function GetCurrentDateInfo($date) {

		//echo strtotime($date);
		//echo date('F jS, Y',strtotime($date));

		$CurrentDate =$this->GetCurrentDate($date);
		$Yesterday = $this->GetYesterday($date);
		$Tommorrow = $this->GetTommorrow($date);

		$Daily = array(
				"Daily"=>array(
						"CurrentDate"=>$CurrentDate,
						"Yesterday"=>$Yesterday,
						"Tommorrow"=>$Tommorrow)
		);

		$CurrentWeek =$this->GetCurrentWeek($date);
		$PrevWeek = $this->GetPreviousWeek($date);
		$NextWeek = $this->GetNextWeek($date);

		$Weekly = array(
				"Weekly"=>array(
						"CurrentWeek"=>$CurrentWeek,
						"PrevWeek"=>$PrevWeek,
						"NextWeek"=>$NextWeek)
		);

		$CurrentMonth =$this->GetCurrentMonth($date);
		$PrevMonth = $this->GetPreviousMonth($date);
		$NextMonth = $this->GetNextMonth($date);

		$Monthly = array(
				"Monthly"=>array(
						"CurrentMonth"=>$CurrentMonth,
						"PrevMonth"=>$PrevMonth,
						"NextMonth"=>$NextMonth)
		);
		 
		$CurrentYear =$this->GetCurrentYear($date);
		$PrevYear = $this->GetPreviousYear($date);
		$NextYear = $this->GetNextYear($date);

		$Yearly = array(
				"Yearly"=>array(
						"CurrentYear"=>$CurrentYear,
						"PrevYear"=>$PrevYear,
						"NextYear"=>$NextYear)
		);

		$result = array_merge($Daily, $Weekly);
		$result = array_merge($result, $Monthly);
		$result = array_merge($result, $Yearly);

		//echo '<pre>';
		//print_r($result);
		//echo '</pre>';
		// echo json_encode($Daily);

		echo json_encode($result);
	}

	public function GetCurrentDate($date) {
		$dd = date('F jS, Y');
		$monthname = explode(",",$dd);
		$monthname1 = explode(" " ,$monthname[0]);
		global $language;
		$MonthNameArray = $language->get('MONTHNAMEARRAY');

		$first_month1 = $MonthNameArray[$monthname1[0]];
		return $first_month1." ".$monthname1[1].",".$monthname[1] ;
			
		//return date('F d, Y',strtotime($date));
	}

	public function GetYesterday($date) {

		$dd = date('F d, Y',strtotime($date) - ( 60 * 60 * 24));
		$monthname = explode(",",$dd);
		$monthname1 = explode(" " ,$monthname[0]);
		global $language;
		$MonthNameArray = $language->get('MONTHNAMEARRAY');

		$first_month1 = $MonthNameArray[$monthname1[0]];
		return $first_month1." ".$monthname1[1].",".$monthname[1] ;
	}

	public function GetTommorrow($date) {

		$dd = date('F d, Y',strtotime($date) + ( 60 * 60 * 24));
		$monthname = explode(",",$dd);
		$monthname1 = explode(" " ,$monthname[0]);
		global $language;
		$MonthNameArray = $language->get('MONTHNAMEARRAY');

		$first_month1 = $MonthNameArray[$monthname1[0]];
		return $first_month1." ".$monthname1[1].",".$monthname[1] ;
	}

	public function GetCurrentWeek($date) {
		$monday = date( 'F d, Y', strtotime( 'monday this week', strtotime($date)));
		$saturday = date( 'F d, Y', strtotime( 'saturday this week +1 day', strtotime($date)));
		
		$x = date( 'F d, Y', strtotime( 'monday this week', strtotime($date)));
		$monthname = explode(",",$x);
		$monthname1 = explode(" " ,$monthname[0]);
	
		global $language;
		$MonthNameArray = $language->get('MONTHNAMEARRAY');
	
		$first_month1 = $MonthNameArray[$monthname1[0]];
		$x = $first_month1." ".$monthname1[1].",".$monthname[1] ;

		$y = date( 'F d, Y', strtotime( 'saturday this week +1 day', strtotime($date)));
		$monthname11 = explode(",",$y);
		$monthname2 = explode(" " ,$monthname11[0]);
	
		global $language;
		$MonthNameArray1 = $language->get('MONTHNAMEARRAY');
	
		$first_month2 = $MonthNameArray1[$monthname2[0]];
		$y = $first_month2." ".$monthname2[1].",".$monthname11[1];
		return $x." - ".$y;
	}
	
	public function GetCurrentWeekOLD_02JAN2016($date) {
		$data = date('Y-m-d', strtotime($date));

		$d = new DateTime( $data );
		$d->setISODate(date('Y', strtotime($date)), date('W', strtotime($date)));

		$d->modify( 'monday this week' );
		$x = $d->format( 'F d, Y' );
		$monthname = explode(",",$x);
		$monthname1 = explode(" " ,$monthname[0]);
		
		global $language;
		$MonthNameArray = $language->get('MONTHNAMEARRAY');

		$first_month1 = $MonthNameArray[$monthname1[0]];
		$x = $first_month1." ".$monthname1[1].",".$monthname[1] ;
		$d->modify( 'sunday this week' );
		$y = $d->format( 'F d, Y' );
		$monthname11 = explode(",",$y);
		$monthname2 = explode(" " ,$monthname11[0]);

		global $language;
		$MonthNameArray1 = $language->get('MONTHNAMEARRAY');

		$first_month2 = $MonthNameArray1[$monthname2[0]];
		//$y = $first_month2." ".$monthname2[1].",".$monthname11[1] ;
		$y = $first_month2." ".$monthname2[1].",".$monthname11[1];
		return $x." - ".$y;	
	}

	public function GetLastWeekSunday($date){
		$data = date('Y-m-d', strtotime($date));

		$d = new DateTime( $data );
		$d->setISODate(date('Y', strtotime($date)), date('W', strtotime($date)));
		$d->modify( 'monday this week' );
		$d->modify( '-1 day' );
		//$x = $d->format( 'F d, Y' );
		$x = $d->format( 'Y-m-d' );
		
		//$x = date('Y-m-d', strtotime($date));
		if(date('l', strtotime($date)) == "Sunday") {
			$x = date('Y-m-d', strtotime('this Sunday', strtotime($date)));
		} else {
			$x = date('Y-m-d', strtotime('last Sunday', strtotime($date)));
		}
		return $x;
	}


	public function GetPreviousWeek($date) {
		$monday = date( 'F d, Y', strtotime( 'previous sunday monday this week', strtotime($date)));
		$saturday = date( 'F d, Y', strtotime( 'previous sunday saturday this week +1 day', strtotime($date)));
		
		$x = date( 'F d, Y', strtotime( 'previous sunday monday this week', strtotime($date)));
		$monthname = explode(",",$x);
		$monthname1 = explode(" " ,$monthname[0]);
		global $language;
		$MonthNameArray = $language->get('MONTHNAMEARRAY');

		$y = date( 'F d, Y', strtotime( 'previous sunday saturday this week +1 day', strtotime($date)));
		$monthname11 = explode(",",$y);
		$monthname2 = explode(" " ,$monthname11[0]);
		global $language;
		$MonthNameArray1 = $language->get('MONTHNAMEARRAY');

		$first_month2 = $MonthNameArray1[$monthname1[0]];
		$y = $first_month2." ".$monthname2[1].",".$monthname11[1] ;
		return $x." - ".$y;
	}

	public function GetNextWeek($date) {
		$monday = date( 'F d, Y', strtotime( 'next sunday +1 day', strtotime($date)));
		$saturday = date( 'F d, Y', strtotime( 'next sunday +7 day', strtotime($date)));
		
		$x = date( 'F d, Y', strtotime( 'next sunday +1 day', strtotime($date)));
		$monthname = explode(",",$x);
		$monthname1 = explode(" " ,$monthname[0]);
		global $language;
		$MonthNameArray = $language->get('MONTHNAMEARRAY');
		$first_month1 = $MonthNameArray[$monthname1[0]];
		$x = $first_month1." ".$monthname1[1].",".$monthname[1] ;
		
		$y = date( 'F d, Y', strtotime( 'next sunday +7 day', strtotime($date)));
		$monthname11 = explode(",",$y);
		$monthname2 = explode(" " ,$monthname11[0]);
		global $language;
		$MonthNameArray1 = $language->get('MONTHNAMEARRAY');

		$first_month2 = $MonthNameArray1[$monthname1[0]];
		$y = $first_month2." ".$monthname2[1].",".$monthname11[1] ;
		return $x." - ".$y;
	}

	public function GetCurrentMonth($date) {
		// echo date('F jS, Y', strtotime('first day of this month'))." - ". date('F jS, Y', strtotime('last day of this month'));

		$data = date('Y-m-d', strtotime($date));

		$d = new DateTime( $data );
		//$d->setISODate(date('Y', strtotime($date)), date('W', strtotime($date)));
		//$d->setISODate(date('Y', strtotime($date)));

		$d->modify( 'first day of this month' );
		//print_r($d);
		$x = $d->format( 'F d, Y' );
		$monthname = explode(",",$x);
		$monthname1 = explode(" " ,$monthname[0]);
		global $language;
		$MonthNameArray = $language->get('MONTHNAMEARRAY');

		$first_month1 = $MonthNameArray[$monthname1[0]];
		$x = $first_month1." ".$monthname1[1].",".$monthname[1] ;
		$d->modify( 'last day of this month' );
		$y = $d->format( 'F d, Y' );
		$monthname11 = explode(",",$y);
		$monthname2 = explode(" " ,$monthname11[0]);
		global $language;
		$MonthNameArray1 = $language->get('MONTHNAMEARRAY');

		$first_month2 = $MonthNameArray1[$monthname1[0]];
		$y = $first_month2." ".$monthname2[1].",".$monthname11[1] ;

		return $x." - ".$y;
	}

	public function GetPreviousMonth($date) {
		// echo date('F jS, Y', strtotime('first day of last month'))." - ". date('F jS, Y', strtotime('last day of last month'));
		$data = date('Y-m-d', strtotime($date));

		$d = new DateTime( $data );
		//$d->setISODate(date('Y', strtotime($date)), date('W', strtotime($date)));

		$d->modify( 'first day of last month' );
		$x = $d->format( 'F d, Y' );
		$monthname = explode(",",$x);
		$monthname1 = explode(" " ,$monthname[0]);
		global $language;
		$MonthNameArray = $language->get('MONTHNAMEARRAY');

		$first_month1 = $MonthNameArray[$monthname1[0]];
		$x = $first_month1." ".$monthname1[1].",".$monthname[1] ;
		$d->modify( 'last day of this month' );
		$y = $d->format( 'F d, Y' );
		$monthname11 = explode(",",$y);
		$monthname2 = explode(" " ,$monthname11[0]);
		global $language;
		$MonthNameArray1 = $language->get('MONTHNAMEARRAY');

		$first_month2 = $MonthNameArray1[$monthname1[0]];
		$y = $first_month2." ".$monthname2[1].",".$monthname11[1] ;

		return $x." - ".$y;
	}

	public function GetNextMonth($date) {
		// echo date('F jS, Y', strtotime('first day of next month'))." - ". date('F jS, Y', strtotime('last day of next month'));
		$data = date('Y-m-d', strtotime($date));

		$d = new DateTime( $data );
		//$d->setISODate(date('Y', strtotime($date)), date('W', strtotime($date)));

		$d->modify( 'first day of next month' );
		$x = $d->format( 'F d, Y' );
		$monthname = explode(",",$x);
		$monthname1 = explode(" " ,$monthname[0]);
		global $language;
		$MonthNameArray = $language->get('MONTHNAMEARRAY');

		$first_month1 = $MonthNameArray[$monthname1[0]];
		$x = $first_month1." ".$monthname1[1].",".$monthname[1] ;
		$d->modify( 'last day of this month' );
		$y = $d->format( 'F d, Y' );
		$monthname11 = explode(",",$y);
		$monthname2 = explode(" " ,$monthname11[0]);
		global $language;
		$MonthNameArray1 = $language->get('MONTHNAMEARRAY');

		$first_month2 = $MonthNameArray1[$monthname1[0]];
		$y = $first_month2." ".$monthname2[1].",".$monthname11[1] ;
		return $x." - ".$y;
	}

	public function GetCurrentYear($date) {
		// echo date('F jS, Y', strtotime('1/1 this year'))." - ". date('F jS, Y',strtotime('1/1 next year -1 day'));
		$data = date('Y-m-d', strtotime($date));

		//		$d = new DateTime( $data );
		//		$d->setDate(date('Y', strtotime($date)),1, date('d', strtotime($date)));
		//		$d->modify( 'first day of this year' );
		//		$x = $d->format( 'F d, Y' );
		//		//$d->modify( 'last day of this year' );
		//		//$d->modify( '1/1 this year -1 day' );
		//
		//		$d->modify( '12/31 next year -1 day' );
		//		$y = $d->format( 'F d, Y' );
		//$language->load("free_horoscope/free-horoscope",$config->get('config_language'));
		
		$year = date('Y') ; // Get current year and subtract 1
		//$year1 = date('Y') +1 ;
		$year1 = date('Y');
		global $language;
		$MonthNameArray = $language->get('MONTHNAMEARRAY');
			
		$first_month1 = $MonthNameArray['January'];
	 	$first_month2 = $MonthNameArray['December'];
		$x = "{$first_month1} 1, {$year}";
		$y = "{$first_month2} 31, {$year1}";

		//$result =


		return $x." - ".$y;
	}

	public function GetPreviousYear($date) {
		global $language;
		$MonthNameArray = $language->get('MONTHNAMEARRAY');
				
		// Get date
		$data = new DateTime( date('Y-m-d', strtotime($date)));
		
		// Format to get date
		$year = $data->format('Y') - 1;
				
		$YearStart = sprintf("%s 1, %s", $MonthNameArray['January'], $year);
		$YearEnd = sprintf("%s 31, %s", $MonthNameArray['December'], $year);
		return sprintf("%s - %s",$YearStart, $YearEnd);
	}
	
	public function GetPreviousYear_OLD($date) {
		// echo date('F jS, Y', strtotime('1/1 last year'))." - ". date('F jS, Y', strtotime('1/1 this year -1 day'));
		$data = date('Y-m-d', strtotime($date));

		$d = new DateTime( $data );
		$d->setDate(date('Y', strtotime($date))-1,1, date('d', strtotime($date)));
		$d->modify( 'first day of this year' );
		$x = $d->format( 'F d, Y' );
		$monthname = explode(",",$x);
		$monthname1 = explode(" " ,$monthname[0]);
		global $language;
		$MonthNameArray = $language->get('MONTHNAMEARRAY');

		$first_month1 = $MonthNameArray[$monthname1[0]];
		$x = $first_month1." 1,".$monthname[1] ;
		//$d->modify( 'last day of this year' );
		$d->modify( '12/31 next year -1 day' );
		$y = $d->format( 'F d, Y' );
		$monthname11 = explode(",",$y);
		$monthname2 = explode(" " ,$monthname11[0]);
		global $language;
		$MonthNameArray1 = $language->get('MONTHNAMEARRAY');

		$first_month2 = $MonthNameArray1[$monthname1[0]];
		$y = $first_month2." 31,".$monthname11[1] ;

		return $x." - ".$y;
	}
	

	public function GetNextYear($date) {
		global $language;
		$MonthNameArray = $language->get('MONTHNAMEARRAY');
				
		// Get date
		$data = new DateTime( date('Y-m-d', strtotime($date)));
		
		// Format to get date
		$year = $data->format('Y') + 1;
				
		$YearStart = sprintf("%s 1, %s", $MonthNameArray['January'], $year);
		$YearEnd = sprintf("%s 31, %s", $MonthNameArray['December'], $year);
		return sprintf("%s - %s",$YearStart, $YearEnd);
	}

	public function GetNextYear_OLD($date) {
		// echo date('F jS, Y', strtotime('1/1 next year'))." - ". date('F jS, Y', strtotime('12/31 next year '));
		$data = date('Y-m-d', strtotime($date));

		$d = new DateTime( $data );
		$d->setDate(date('Y', strtotime($date))+1,1, date('d', strtotime($date)));
		$d->modify( 'first day of this year' );
		$x = $d->format( 'F d, Y' );
		$monthname = explode(",",$x);
		$monthname1 = explode(" " ,$monthname[0]);
		global $language;
		$MonthNameArray = $language->get('MONTHNAMEARRAY');

		$first_month1 = $MonthNameArray[$monthname1[0]];
		$x = $first_month1." 1,".$monthname[1] ;
		//$d->modify( 'last day of this year' );
		$d->modify( '12/31 next year -1 day' );
		$y = $d->format( 'F d, Y' );
		$monthname11 = explode(",",$y);
		$monthname2 = explode(" " ,$monthname11[0]);
		global $language;
		$MonthNameArray1 = $language->get('MONTHNAMEARRAY');

		$first_month2 = $MonthNameArray1[$monthname1[0]];
		$y = $first_month2." 31,".$monthname11[1] ;

		return $x." - ".$y;
	}

	public function GetCallender($cMonth,$cYear,$prev_month,$next_month,$prev_year,$next_year,$code=1,$month_name) {
		if (!defined('BASEURL')) {
			$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
			define("BASEURL", $protocol . $_SERVER['SERVER_NAME']. "/");
		}
		global $language;

		$MonthNameArray1 = $language->get('MONTHNAMEARRAY');
		$month_name1 = $MonthNameArray1[$month_name];
		$str = '';

		$str .='<div id="inner-cal-to-bg">';
		//$str .='<tr>';
		// $str .='<td>';
		$str .='<div class="inner-cal-arrow-left">';
		$str .='<a href="javascript:void(0);" onclick="GetCallender('.$prev_month.','.$prev_year.','.$code.');">';
		$str .='<img src="'.BASEURL.'images/arrow-left.png" width="12" height="15" /> ';
		$str .='</a>';
		$str .='</div>';
		$str .='<div class="cal-text-midd">';
		$str .=$month_name1;
		$str .='</div>';
		$str .='<div class="inner-cal-arrow-right">';
		$str .='<a href="javascript:void(0);" onclick="GetCallender('.$next_month.','.$next_year.','.$code.');">';
		$str .='<img src="'.BASEURL.'images/cal-arrow-right.png" width="12" height="15" />';
		$str .=' </a>';
		$str .='</div>';
		// $str .='</td>';
		// $str .='</tr>';
		$str .='</div>';

		$str .='<table width="100%" border="0" cellspacing="0" cellpadding="0" class="caltable-s ">';
		$str .='<tr class="cal-heading-s" >';
		$str .='  <td align="center" valign="middle" class="cal-alltd-w"><strong>SUN</strong></td>';
		$str .='  <td align="center" valign="middle" class="cal-alltd-w"><strong>MON</strong></td>';
		$str .='  <td align="center" valign="middle" class="cal-alltd-w"><strong>TUE</strong></td>';
		$str .='  <td align="center" valign="middle" class="cal-alltd-w"><strong>WED</strong></td>';
		$str .='  <td align="center" valign="middle" class="cal-alltd-w"><strong>THU</strong></td>';
		$str .='  <td align="center" valign="middle" class="cal-alltd-w"><strong>FRI</strong></td>';
		$str .='  <td align="center" valign="middle" class="cal-alltd-w"><strong>SAT</strong></td>';
		$str .='</tr>';

		$timestamp = mktime(0,0,0,$cMonth,1,$cYear);
		$maxday = date("t",$timestamp);
		$thismonth = getdate ($timestamp);
		$startday = $thismonth['wday'];
		for ($i=0; $i<($maxday+$startday); $i++) {
			if(($i % 7) == 0 ) {
				$str .= "<tr  class='inner-caltd-s'>";
			}

			if($i < $startday) {
				$str .= '<td align="center" valign="middle" class="inner-cal-alltd inner-caltd-rgtborder">&nbsp;</td>';
			}
			else {
				if(($i % 7) == 6 ) {
					$str .= '<td align="center" valign="middle" class="cal-alltd-l" id="td_'.($i - $startday + 1).'" onclick="getTransist('.($i - $startday + 1).','.$cMonth.','.$cYear.');" style="cursor:pointer;">';
					//$str .= "<a href='javascript:void(0);' onclick='getTransist(".($i - $startday + 1).",".$cMonth.",".$cYear.");'>";
					$str .= ($i - $startday + 1) ;
					//$str .= "</a>";
					$str .= "</td>";
				}
				else {
					$str .= '<td align="center" valign="middle" class="cal-alltd inner-caltd-rgtborder-s" id="td_'.($i - $startday + 1).'" onclick="getTransist('.($i - $startday + 1).','.$cMonth.','.$cYear.');" style="cursor:pointer;">';
					//$str .= "<a href='javascript:void(0);'  onclick='getTransist(".($i - $startday + 1).",".$cMonth.",".$cYear.");'>";
					$str .= ($i - $startday + 1) ;
					//$str .= "</a>";
					$str .= "</td>";
				}
			}

			if(($i % 7) == 6 ) {
				$str .= "</tr>";
			}
		}

		$str .='</table>';
		$str .='<div class="clear"></div>';
		$str .='<div id="dvGeneralContent"  class="dvGeneralContent">   ';
		$str .='</div>';
		$str .='<div class="clear"></div>';
		return $str;
	}

	public function GetCallenderGeneralInfluence($cMonth,$cYear,$prev_month,$next_month,$prev_year,$next_year,$code=1,$month_name) {
		if (!defined('BASEURL')) {
			//define("BASEURL", "http://" . $_SERVER['SERVER_NAME']. "/astrowow/");
			$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
			define("BASEURL", $protocol . $_SERVER['SERVER_NAME']. "/");
		}
		global $language;
		$month_name = $language->get($month_name);
		$str = '';

		$str .='<div id="cal-to-bg">';
		//$str .='<tr>';
		// $str .='<td>';
		$str .='<div class="cal-arrow-left">';
		$str .='<a href="javascript:void(0);" onclick="GetCallenderGeneralInfluence('.$prev_month.','.$prev_year.','.$code.');">';
		$str .='<img src="'.BASEURL.'images/arrow-left.png" width="12" height="15" /> ';
		$str .='</a>';
		$str .='</div>';
		$str .='<div class="cal-text-midd">';
		$str .=$month_name;
		$str .='</div>';
		$str .='<div class="cal-arrow-right">';
		$str .='<a href="javascript:void(0);" onclick="GetCallenderGeneralInfluence('.$next_month.','.$next_year.','.$code.');">';
		$str .='<img src="'.BASEURL.'images/cal-arrow-right.png" width="12" height="15" />';
		$str .=' </a>';
		$str .='</div>';
		// $str .='</td>';
		// $str .='</tr>';
		$str .='</div>';

		$str .='<table width="100%" border="0" cellspacing="0" cellpadding="0" class="caltable-s ">';
		$str .='<tr class="cal-heading-s" >';
		$str .='  <td align="center" valign="middle" class="cal-alltd-w"><strong>SUN</strong></td>';
		$str .='  <td align="center" valign="middle" class="cal-alltd-w"><strong>MON</strong></td>';
		$str .='  <td align="center" valign="middle" class="cal-alltd-w"><strong>TUE</strong></td>';
		$str .='  <td align="center" valign="middle" class="cal-alltd-w"><strong>WED</strong></td>';
		$str .='  <td align="center" valign="middle" class="cal-alltd-w"><strong>THU</strong></td>';
		$str .='  <td align="center" valign="middle" class="cal-alltd-w"><strong>FRI</strong></td>';
		$str .='  <td align="center" valign="middle" class="cal-alltd-w"><strong>SAT</strong></td>';
		$str .='</tr>';

		$timestamp = mktime(0,0,0,$cMonth,1,$cYear);
		$maxday = date("t",$timestamp);
		$thismonth = getdate ($timestamp);
		$startday = $thismonth['wday'];
		for ($i=0; $i<($maxday+$startday); $i++) {
			if(($i % 7) == 0 ) {
				$str .= "<tr  class='caltd-s'>";
			}

			if($i < $startday) {
				$str .= '<td align="center" valign="middle" class="index-cal-alltd index-caltd-rgtborder">&nbsp;</td>';
			}
			else {
				if(($i % 7) == 6 ) {
					$str .= '<td align="center" valign="middle" class="cal-alltd-s" id="td_'.($i - $startday + 1).'" onclick="getGeneralInfluence('.($i - $startday + 1).','.$cMonth.','.$cYear.');" style="cursor:pointer;" >';
					//$str .= "<a href='javascript:void(0);' onclick='getGeneralInfluence(".($i - $startday + 1).",".$cMonth.",".$cYear.");'>";
					$str .= ($i - $startday + 1) ;
					//$str .= "</a>";
					$str .= "</td>";
				}
				else {
					$str .= '<td align="center" valign="middle" class="cal-alltd caltd-rgtborder-s" id="td_'.($i - $startday + 1).'" onclick="getGeneralInfluence('.($i - $startday + 1).','.$cMonth.','.$cYear.');" style="cursor:pointer;">';
					//$str .= "<a href='javascript:void(0);'  onclick='getGeneralInfluence(".($i - $startday + 1).",".$cMonth.",".$cYear.");'>";
					$str .= ($i - $startday + 1) ;
					//$str .= "</a>";
					$str .= "</td>";
				}
			}

			if(($i % 7) == 6 ) {
				$str .= "</tr>";
			}
		}

		$str .='</table>';
		$str .='<div class="clear"></div>';
		$str .='<div id="dvGeneralInfluenceContent" class="dvGeneralInfluenceContent">   ';
		$str .='</div>';
		$str .='<div class="clear"></div>';
		return $str;
	}

	function GetWeekList($CurrentDate) {
		$d = strtotime($CurrentDate);
		$start_week = strtotime("previous sunday previous sunday",$d);		
		$PrevWK = date("Y-m-d", $start_week);
		
		$d = strtotime($CurrentDate);
		//$d = strtotime("+1 week -1 day", $CurrentDate);
		$start_week = strtotime("next sunday next sunday",$d);	
		$NextWK = date("Y-m-d", $start_week);

		$d = strtotime($CurrentDate);
		$start_week = strtotime("previous sunday",$d);
		$CurrentWK = date("Y-m-d", $start_week);

		$Weekly = array(
				"PrevWeek" => $PrevWK,
				"CurrentWeek" => $CurrentWK,
				"NextWeek" => $NextWK
		);
		echo json_encode($Weekly);
		
	}
	
	function GetWeekListOLD($CurrentDate) {
		//PREVIOUS WEEK SUNDAY date
		$PrevWeek = date('Y-m-d', strtotime($CurrentDate));
		$d = new DateTime( $PrevWeek );
		
		if(date('W', strtotime($PrevWeek)) == 1) {
			$d->setISODate(date('Y', strtotime($PrevWeek)) - 1, date('W', strtotime($PrevWeek)));
		} else {
			$d->setISODate(date('Y', strtotime($PrevWeek)) - 1, date('W', strtotime($PrevWeek)));
		}
		
		$d->modify( '-1 week' );
		//$d->modify( 'last sunday' );
		$PrevWeek = $d->format( 'Y-m-d' );

		//NEXT WEEK SUNDAY date
		$NextWeek = date('Y-m-d', strtotime($CurrentDate));
		$d = new DateTime( $NextWeek );
		$d->setISODate(date('Y', strtotime($NextWeek)), date('W', strtotime($NextWeek)));
		$d->modify( '+1 week' );
		//$d->modify( 'last sunday' );
		$NextWeek = $d->format( 'Y-m-d' );

		//CURRENT WEEK SUNDAY date
		$CurrentWeek = date('Y-m-d', strtotime($CurrentDate));

		$d = new DateTime( $CurrentWeek );
		$d->setISODate(date('Y', strtotime($CurrentWeek)), date('W', strtotime($CurrentWeek)));

		$d->modify( 'last sunday this week' );
		$CurrentWeek = $d->format( 'Y-m-d' );

		$Weekly = array(
				"PrevWeek" => $PrevWeek,
				"CurrentWeek" => $CurrentWeek,
				"NextWeek" => $NextWeek
		);
		echo json_encode($Weekly);
	}
}


if(isset($_REQUEST['task'])) {
	//print_r($_REQUEST);

	if($_REQUEST['task'] == "GetCurrentDateInfo") {
		$objDateFunction = new DateFunction();
		try {
			$objDateFunction->GetCurrentDateInfo($_REQUEST['a']);
		} catch (Exception $ex){
			echo $ex->getMessage();
		}
	}
	else if ($_REQUEST['task'] == "GetCallender") {

		$monthNames = Array("January", "February", "March", "April", "May", "June", "July",
				"August", "September", "October", "November", "December");

		if (!isset($_REQUEST["month"])) $_REQUEST["month"] = date("n");
		if (!isset($_REQUEST["year"])) $_REQUEST["year"] = date("Y");
		if (!isset($_REQUEST["code"])) $_REQUEST["code"] = 1;

		$cMonth = $_REQUEST["month"];
		$cYear = $_REQUEST["year"];
		$code = $_REQUEST["code"];

		$prev_year = $cYear;
		$next_year = $cYear;
		$prev_month = $cMonth-1;
		$next_month = $cMonth+1;

		if ($prev_month == 0 ) {
			$prev_month = 12;
			$prev_year = $cYear - 1;
		}
		if ($next_month == 13 ) {
			$next_month = 1;
			$next_year = $cYear + 1;
		}

		$date = date('Y-m-d',strtotime(date("Y").'-'.($cMonth)));
		$data = date('Y-m-d');

		$d = new DateTime( $data );
		$d->setISODate(date('Y', strtotime($date)), date('W', strtotime($date)));
		//$d->modify( '+1 month' );
		$month_name = $d->format( 'F' );
		// global $language;
		// $month_name = $language->get($month_name);
		$objDateFunction = new DateFunction();
		$result = $objDateFunction->GetCallender($cMonth,$cYear,$prev_month,$next_month,$prev_year,$next_year,$code,$monthNames[$cMonth-1]);

		echo json_encode($result);
	}
	else if ($_REQUEST['task'] == "GetCallenderGeneralInfluence") {
		$monthNames = Array("January", "February", "March", "April", "May", "June", "July",
				"August", "September", "October", "November", "December");

		if (!isset($_REQUEST["month"])) $_REQUEST["month"] = date("n");
		if (!isset($_REQUEST["year"])) $_REQUEST["year"] = date("Y");
		if (!isset($_REQUEST["code"])) $_REQUEST["code"] = 1;

		$cMonth = $_REQUEST["month"];
		$cYear = $_REQUEST["year"];
		$code = $_REQUEST["code"];

		$prev_year = $cYear;
		$next_year = $cYear;
		$prev_month = $cMonth-1;
		$next_month = $cMonth+1;

		if ($prev_month == 0 ) {
			$prev_month = 12;
			$prev_year = $cYear - 1;
		}
		if ($next_month == 13 ) {
			$next_month = 1;
			$next_year = $cYear + 1;
		}

		$date = date('Y-m-d',strtotime(date("Y").'-'.($cMonth)));
		$data = date('Y-m-d');

		$d = new DateTime( $data );
		$d->setISODate(date('Y', strtotime($date)), date('W', strtotime($date)));
		//$d->modify( '+1 month' );
		$month_name = $d->format( 'F' );

		$objDateFunction = new DateFunction();
		$result = $objDateFunction->GetCallenderGeneralInfluence($cMonth,$cYear,$prev_month,$next_month,$prev_year,$next_year,$code,$monthNames[$cMonth-1]);
		echo json_encode($result);
	}
	else if($_REQUEST['task'] == "GetWeekList") {
		$objDateFunction = new DateFunction();
		$objDateFunction->GetWeekList($_REQUEST['currentdate']);
	}
}

//Get Start and End Days of week for a Given date


function x_week_range($date) {
	$ts = strtotime($date);
	$start = (date('w', $ts) == 0) ? $ts : strtotime('last monday', $ts);
	//echo strtotime('monday this week');
	echo date('Y-m-d', strtotime('monday this week'));
	echo date('Y-m-d', strtotime('sunday this week'));
	return array(date('Y-m-d', $start),
			date('Y-m-d', strtotime('next sunday', $start)));
}

//list($start_date, $end_date) = x_week_range(date("Y-m-d"));
//echo $start_date." - ". $end_date;
//echo date('F jS, Y', strtotime('monday this week'))." - ". date('F jS, Y', strtotime('sunday this week'));

// echo date('F jS, Y', strtotime('first day of this month'))." - ". date('F jS, Y', strtotime('last day of this month'));


// echo date('F jS, Y', strtotime('1/1 this year'))." - ". date('F jS, Y', strtotime('1/1 next year -1 day'));
//echo date('F jS, Y');

?>