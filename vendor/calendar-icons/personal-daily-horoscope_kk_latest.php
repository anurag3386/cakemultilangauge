<?php

use Cake\Cache\Cache;
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
if ($_SERVER['SERVER_NAME'] == 'localhost') {
    define( 'ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/astrowow' );
    define("BASEURL", $protocol . $_SERVER['SERVER_NAME']. "/astrowow/");
} else {
    define( 'ROOTPATH', $_SERVER['DOCUMENT_ROOT'] );
    define("BASEURL", $protocol . $_SERVER['SERVER_NAME']. "/");
}
define ( 'PERSONAL_HOROSCOPE_IMAGE_PATH', $_SERVER['DOCUMENT_ROOT'] . '/astrowow/' );

define ( 'VENDORPATH', ROOTPATH . '/vendor/calendar-icons' );
define ( 'LANGUAGEPATH', VENDORPATH . '/language' );
define ( 'BALPATH', VENDORPATH . '/bal' );
define ( 'DTOPATH', VENDORPATH . '/dto' );
define ( 'CLASSPATH', VENDORPATH . '/classes' );
define ( 'LIBRARYPATH', VENDORPATH . '/library' );
define ( 'DALPATH', VENDORPATH . '/dal' );
define ( 'HELPERPATH', VENDORPATH . '/helper' );


require_once BALPATH .'/calendar.icon.functions.php';
require_once BALPATH .'/subscription.php';

$user_id = !empty($this->request->session()->read('Auth.User.id')) ? $this->request->session()->read('Auth.User.id') : $this->request->session()->read('user_id');
$user_id = !empty($this->request->session()->read('selectedUser')) ? $this->request->session()->read('selectedUser') : $user_id;

$person_id = $user_id;
$userType = '';
if (strpos($person_id, '_') !== false) {
    $anthrId = explode('_', $person_id);
    $userType = 'anotherPerson';
    $person_id = $anthrId[1];
}
$IsSubscribedUser = 0;

$objUserSubscription = new Subscription();

$result = $objUserSubscription->IsSubscribeUser($person_id, $userType);
$subscriptionDetail = $objUserSubscription->customUserDetail ($person_id, $userType);

if($result) {
    $IsSubscribedUser = 1;
}

$selectedLanguage = $lang;
if ($selectedLanguage == 'da') {
    $selectedLanguage = 'dk';
}

if ($selectedLanguage == 'dk') {
    $MonthNameArray = array('January' => 'januar', 'February' => 'februar', 'March' => 'marts', 'April' => 'April',
            'May' => 'Kan', 'June' => 'juni', 'July' => 'juli', 'August' => 'august', 'September' => 'september', 'October' => 'oktober', 'November' => 'november', 'December' => 'december',
            //Short Error
            'Jan' => 'Jan', 'Feb' => 'Feb', 'Mar' => 'Mar', 'Apr' => 'Apr', 'May' => 'May', 'Jun' => 'Jun', 'Jul' => 'Jul', 'Aug' => 'Aug', 'Sep' => 'Sep', 'Oct' => 'Oct', 'Nov' => 'Nov', 'Dec' => 'Dec');
    $MonthPosArray = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
    $DayNameArray = array('Sunday' => 'Sunday', 'Monday' =>'Monday', 'Tuesday' => 'Tuesday', 'Wednesday' => 'Wednesday', 'Thursday' => 'Thursday', 'Friday' => 'Friday', 'Saturday' => 'Saturday',
    //Short name
    'Sun' => 'Sol', 'Mon' =>'Mon', 'Tue' => 'Tue', 'Wed' => 'Ons', 'Thu' => 'Thu', 'Fri' => 'Fre', 'Sat' => 'Sat');
} else {
    $MonthNameArray = array('January' => 'January', 'February' => 'February', 'March' => 'March', 'April' => 'April',
    		'May' => 'May', 'June' => 'June', 'July' => 'July', 'August' => 'August', 'September' => 'September', 'October' => 'October', 'November' => 'November', 'December' => 'December',
    		//Short Error
    		'Jan' => 'Jan', 'Feb' => 'Feb', 'Mar' => 'Mar', 'Apr' => 'Apr', 'May' => 'May', 'Jun' => 'Jun', 'Jul' => 'Jul', 'Aug' => 'Aug', 'Sep' => 'Sep', 'Oct' => 'Oct', 'Nov' => 'Nov', 'Dec' => 'Dec');
    $MonthPosArray = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
    $DayNameArray = array('Sunday' => 'Sunday', 'Monday' =>'Monday', 'Tuesday' => 'Tuesday', 'Wednesday' => 'Wednesday', 'Thursday' => 'Thursday', 'Friday' => 'Friday', 'Saturday' => 'Saturday',
	//Short name
	'Sun' => 'Sun', 'Mon' =>'Mon', 'Tue' => 'Tue', 'Wed' => 'Wed', 'Thu' => 'Thu', 'Fri' => 'Fri', 'Sat' => 'Sat');
}
$personal_horoscope_help_text = "The Horoscope Calendar is not the same as your daily sun sign horoscope. These texts are based on your birth planets and therefore are unique to you. The free subscription provides horoscopes 5 days in advance. To get the complete 3 month calendar, please click on the subscribe button.";

$ASTROPAGE_PERSONAL_HOROSCOPE_TEXT = 'Below you can see short term and long term influences day by day on your personal birth chart. You can use the questions and answers to clarify issues that arise.';

$FOR_TEXT = 'for';

?>

<input type="hidden" id="person_id" name="person_id" value="<?php echo $person_id;?>" />
	<?php
        $first_month = date ('n', $subscriptionDetail['start_date']);
        $last_month = date ('n', $subscriptionDetail['end_date']);

        $year1 = date('Y', $subscriptionDetail['start_date']);
        $year2 = date('Y', $subscriptionDetail['end_date']);
        $month1 = date('m', $subscriptionDetail['start_date']);
        $month2 = date('m', $subscriptionDetail['end_date']);
        $diff = (($year2 - $year1) * 12) + ($month2 - $month1);

        $total_months = $diff; //($last_month - $first_month); die;

        
        $first_month_date = date('Y-m', $subscriptionDetail['start_date']).'-01';
        $num_months = $total_months;
        $current_month = date('Y-m').'-01';

        /*for ($count = 0; $count <= $num_months; $count++) {
            $temp_month = array();
            $temp_month['month_number'] = date('n', strtotime($first_month_date.' + '.$count.' Months'));
            $temp_month['month_name'] = date('F - Y', strtotime($first_month_date.' + '.$count.' Months'));
            $nextMonthsArr[] = $temp_month;
        }*/

        //echo '<pre>'; print_r($nextMonthsArr); die;

        $ApiKey = md5('astrowow.com');
        $UserId = $user_id;
        $PDate = '';
        
	    $calendarResponse = '';

        if (isset($monthNo) && !empty($monthNo)) {
            $monthNo = $monthNo;
            $PDate = date('m-Y', strtotime($first_month_date.' + '.($monthNo-1).' Months'));
            $explodedPDate = explode('-', $PDate);
            $PDate = $explodedPDate[0].'-01-'.$explodedPDate[1];
        } else {
            $_SESSION['calendar_start_date'] = $subscriptionDetail['start_date'];
            $_SESSION['calendar_end_date'] = $subscriptionDetail['end_date'];
            $currYear = date ('Y');
            $currMonth = date ('m');
            $monthNo = (($currYear - $year1) * 12) + ($currMonth - $month1);
            if (!$monthNo) {
                $monthNo = 1;
            }
            $PDate = date('m-d-Y', $subscriptionDetail['start_date']);
        }
        $nextMonth = $monthNo+1;

        $monthLimit = 13;

        $calendarResponse .= "<div class='tab03-outer floatL'>";

        if ($IsSubscribedUser) {
            $calendarResponse .= "<div class='tabify04-outer'>
        		<ul id='tabbed04' class='tabify04'>";
                    if ($monthNo > 1) {
                        $calendarResponse .= "<li class='previousMonth' onclick='nextMonthData(".($monthNo-1).")'> <img src=".BASEURL."webroot/img/left.png> </li>";
                    }
                    $i = 1;
                    
                    /*foreach($nextMonthsArr as $key => $next_month) {
                        if( $i <= 13 ) {
                         $calendarResponse .= "<li class='tablinks' ref='$i' style='display: none;'>".$next_month['month_name']."</li>";
                        }
                        $i++;
                    }*/
                    $calendarResponse .= "<li class='tablinks active' ref='".$monthNo."'>".date('F - Y', strtotime($first_month_date.' + '.($monthNo-1).' Months'))."</li>";

                    if ($monthNo < $monthLimit) {
                        $calendarResponse .= "<li class='nextMonth nayamonth' onclick='nextMonthData(".$nextMonth.")' ref='2'> <img src=".BASEURL."webroot/img/right.png> </li>";
                    }
        		$calendarResponse .= "</ul>
                <div id='first_month' class='tabify04-content' ref='1'>";

            $cMonth = date('n', strtotime($first_month_date.' + '.($monthNo-1).' Months'));
            $cYear = date('Y', strtotime($first_month_date.' + '.($monthNo-1).' Months'));
 
            $calendarResponse .= "<table width='100%' border='0' cellspacing='0' cellpadding='0' class='caltable-user'>
                <tr class='cal-heading-user'>
                    <td align='center' valign='middle' class='cal-alltd-user'>".$DayNameArray['Sun']."</td>
                    <td align='center' valign='middle'  class='cal-alltd-user'>".$DayNameArray['Mon']."</td>
                    <td align='center' valign='middle'  class='cal-alltd-user'>".$DayNameArray['Tue']."</td>
                    <td align='center' valign='middle' class='al-alltd-user'>".$DayNameArray['Wed']."</td>
                    <td align='center' valign='middle' class='cal-alltd-user'>".$DayNameArray['Thu']."</td>
                    <td align='center' valign='middle' class='cal-alltd-user'>".$DayNameArray['Fri']."</td>
                    <td align='center' valign='middle' class='cal-alltd-user'>".$DayNameArray['Sat']."</td>
                </tr>";
    			
    	    $timestamp = mktime(0,0,0,$cMonth,1,$cYear);
            $maxday = date("t",$timestamp);
            $thismonth = getdate ($timestamp);
            $startday = $thismonth['wday'];

            require_once (BALPATH . "/calendar.icon.functions.php");

            $jsonObject = predictionIcons ($ApiKey, $PDate, $user_id, $selectedLanguage, 2); //pm option =2 to filter dates
         
            for ($i=0; $i<($maxday+$startday); $i++) {
                $imageCount = 0;
                if(($i % 7) == 0 ) {
                    $calendarResponse .= "<tr  class='caltd-user'>";
                }
                if($i < $startday) {
                    $calendarResponse .= "<td class='cal-alltd-user caltd-rgtborder-user'>&nbsp;</td>";
                } else {
        			$TodaysId = sprintf("%04d-%02d-%02d", $cYear, $cMonth, ($i - $startday + 1));    
        			$ImgIconTag = '';
                    
        			$IconTable = "<table class='calIcons'>
        					<tr class='Icon'><td>%s</td></tr>
    						<tr class='Text'><td><span>%s</span></td></tr>
    						<tr class='Icon bottom'><td>%s</td></tr>
					     </table>";

                    if(isset($jsonObject['DailyPredictionData']) && array_key_exists($TodaysId, $jsonObject['DailyPredictionData'])) {
        				$PositiveImage = array();
        				$NagetiveImage = array();
        				$Item = $jsonObject['DailyPredictionData'][$TodaysId];
                        $imageCount = count($Item);
        				if(is_array($Item)) {
                            for ($Index = 0; $Index < count($Item); $Index++) {
        						$TransitTitle = $Item[$Index]['Title']. "<br />" . $Item[$Index]['TransitTitle'];
        						$TransitTitle = "<img src=".$Item[$Index]['Icons']." alt=".$TransitTitle." />";

                                if($Item[$Index]['AspectType'] == "POS" ) {
                                    array_push($PositiveImage, sprintf("<img src=\"%s\" class=\"imgCalenderIcon\" width=\"30\" height=\"30\" alt=\"%s\" onmouseover = 'showToolTipOnCalendarIcons (\"%s\", \"%s\", \"%s\", \"%s\");' onmouseout='hideToolTipOnCalendarIcons ();' />",
                                        $Item[$Index]['Icons'],
                                        $Item[$Index]['Title'],
                                        $Item[$Index]['Icons'],
                                        $Item[$Index]['Title'],
                                        $Item[$Index]['TransitTitle'],
                                        $TodaysId
                                    ));
                                } else {
                                    array_push($NagetiveImage, sprintf("<img src=\"%s\" class=\"imgCalenderIcon\" width=\"30\" height=\"30\" alt=\"%s\" onmouseover = 'showToolTipOnCalendarIcons (\"%s\", \"%s\", \"%s\", \"%s\");' onmouseout = 'hideToolTipOnCalendarIcons ();' />",
                                    $Item[$Index]['Icons'],
                                    $Item[$Index]['Title'],
                                    $Item[$Index]['Icons'],
                                    $Item[$Index]['Title'],
                                    $Item[$Index]['TransitTitle'],
                                    $TodaysId));
						      }
					       }
				        }

        				$PosIconTag = implode('', $PositiveImage);
        				$NegIconTag = implode('', $NagetiveImage);
        				$ImgIconTag = sprintf($IconTable, $PosIconTag, ($i - $startday + 1), $NegIconTag);
        				unset($PositiveImage);
        				unset($NagetiveImage);
                    } else {
				        $ImgIconTag = sprintf($IconTable, '&nbsp;', ($i - $startday + 1), '&nbsp;');
                    }

        			if(($i % 7) == 6 ) {
                        $dateWithLink = $cMonth.'-'.($i - $startday + 1).'-'.$cYear;
                        $activeClass = '';
                        if (checkActiveDate($dateWithLink)) {
                            $activeClass = ' calendarActiveClass';
                        }
                        $calendarResponse .= "<td align='center' valign='middle' class='cal-alltd-user".$activeClass."'>";
                        $dateWithLink1 = '"'.$dateWithLink.'"';
                        $calendarResponse .= "<div class='calendarIconTooltip' id=".$dateWithLink." ref=".$imageCount."></div>";
                        if (validDateForShowPrediction($dateWithLink)) {
                            $calendarResponse .= "<a class='lnkDaily' onclick='button_onClick($dateWithLink1)' id='".$dateWithLink."' href='javascript:void(0);'>";
                        } else {
                            $calendarResponse .= "<a class='lnkDaily' id='".$dateWithLink."' href='javascript:void(0);'>";
                        }
                        $calendarResponse .= $ImgIconTag."</a></td>";
                        /*$calendarResponse .= "</a>";
                        $calendarResponse .= "</td>";*/
                    } else {
                        $dateWithLink = $cMonth.'-'.($i - $startday + 1).'-'.$cYear;
                        $activeClass = '';
                        if (checkActiveDate($dateWithLink)) {
                            $activeClass = ' calendarActiveClass';
                        }
                        $calendarResponse .= "<td align='center' valign='middle' class='cal-alltd-user".$activeClass."'>";
                        $dateWithLink1 = '"'.$dateWithLink.'"';
                        $calendarResponse .= "<div class='calendarIconTooltip' id=".$dateWithLink." ref=".$imageCount."></div>";
                        if (validDateForShowPrediction($dateWithLink)) {
                            $calendarResponse .= "<a class='lnkDaily' onclick='button_onClick($dateWithLink1)' id='".$dateWithLink."' href='javascript:void(0);'>";
                        } else {
                            $calendarResponse .= "<a class='lnkDaily' id='".$dateWithLink."' href='javascript:void(0);'>";
                        }
                        $calendarResponse .= $ImgIconTag."</a></td>";
                        /*$calendarResponse .= "</a>";
                        $calendarResponse .= "</td>";*/
                    }
                }
                if(($i % 7) == 6 ) {
                    $calendarResponse .= "</tr>";
                }
            }
            $calendarResponse .= "</table></div></div>";
	    } else {
            $calendarResponse .= "<div class='tabify03-outer'>
                <ul id='tabbed031' class='tabify03'>";

			$TodaysDate = date('m-d-Y');
            $date = date('M-d-y');            
            $LanguageId = $selectedLanguage; //(!empty($this->request->session()->read('locale'))) ? $this->request->session()->read('locale') : 'en';
            $DayCount = 5;
            if ($LanguageId == 'da') {
                $LanguageId = 'dk';
                $DayCount = 3;
                $date = date('M-d-y', strtotime("-1 day"));
            }
            
            for($i = 0; $i < $DayCount; $i++) {
            	$PrintLink = '';
                if($TodaysDate == date('m-d-Y', strtotime($date) + ( 60 * 60 * 24)*$i)) {
                	$PrintLink = '<li class="active">';
                    $PrintLink .= '<a class="lnkDaily" onclick="button_onClick()" id="'.date('m-d-Y', strtotime($date) + ( 60 * 60 * 24)*$i).'" href="javascript:void(0);">';
                    $PrintLink .= date('d', strtotime($date) + ( 60 * 60 * 24)*$i);
                    $PrintLink .= '</a></li>';
				}
                else {
                	$PrintLink = '<li>';
                    $PrintLink .= '<a class="lnkDaily" onclick="button_onClick()" id="'.date('m-d-Y', strtotime($date) + ( 60 * 60 * 24)*$i).'" href="javascript:void(0);">';
                    $PrintLink .= date('d', strtotime($date) + ( 60 * 60 * 24)*$i);
                    $PrintLink .= '</a></li>';
				}
                $calendarResponse .= $PrintLink;
                //echo $PrintLink;
			}
            $calendarResponse .= "</ul>
                <div class='floatR'>
                <span class='txt-white'>";
        		if($person_id == $user_id && isset($IsSubscribedUser) && empty($IsSubscribedUser)) {
                    $calendarResponse .= "|&nbsp;&nbsp";
                    $calendarResponse .= "<a href=".BASEURL."'/users/subscribe' target='_blank' title='Subscribe Now'>".BASEURL."'/users/subscribe'</a>";
                }
                $calendarResponse .= "</span>
                </div>
            </div>
            <div id='tab-content-container'>        
                <div id='dvResult'>
                </div>
            </div>";
        }
        $calendarResponse .= "
    </div>";
    //return $calendarResponse;
    echo $calendarResponse; die;

    function dateFormating ($date) { //$date m-d-Y
        $date = explode('-', $date);
        return strtotime($date['1'].'-'.$date['0'].'-'.$date['2']);
    }


                        
    function validDateForShowPrediction ($selectedDate) {
        $selectedDate = dateFormating($selectedDate);
        if ($selectedDate >= $_SESSION['calendar_start_date'] && $_SESSION['calendar_end_date'] >= $selectedDate) {
            return true;
        }
        return false;
    }

    function checkActiveDate ($selectedDate) {
        $selectedDate = dateFormating($selectedDate);
        if ($selectedDate == date('d-m-Y')) {
            return true;
        }
        return false;
    }

?>