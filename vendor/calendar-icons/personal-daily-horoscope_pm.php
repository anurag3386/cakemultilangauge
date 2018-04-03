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

//$user_id = !empty($_SESSION['Auth']['User']['id']) ? $_SESSION['Auth']['User']['id'] : $_SESSION['user_id'];
$user_id = !empty($this->request->session()->read('Auth.User.id')) ? $this->request->session()->read('Auth.User.id') : $this->request->session()->read('user_id');
$user_id = !empty($this->request->session()->read('selectedUser')) ? $this->request->session()->read('selectedUser') : $user_id;
//$user_id = (Cache::read('Auth.User.id') !== FALSE) ? Cache::read('Auth.User.id') : Cache::read('user_id');

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
//echo date ('F', $subscriptionDetail['start_date']); pr ($subscriptionDetail); die;

if($result) {
    $IsSubscribedUser = 1;
}

$selectedLanguage = $lang;
if ($selectedLanguage == 'da') {
    $selectedLanguage = 'dk';
}

/*$selectedLanguage = !empty($this->request->session()->read('locale')) ? $this->request->session()->read('locale') : 'en';
if ($selectedLanguage == 'da') {
    $selectedLanguage = 'dk';
}*/
//$selectedLanguage = (isset($_GET['language']) && !empty($_GET['language'])) ? strtolower($_GET['language']) : $selectedLanguage;
if ($selectedLanguage == 'dk') {
    $MonthNameArray = array('January' => 'januar', 'February' => 'februar', 'March' => 'marts', 'April' => 'April',
            'May' => 'Kan', 'June' => 'juni', 'July' => 'juli', 'August' => 'august', 'September' => 'september', 'October' => 'oktober', 'November' => 'november', 'December' => 'december',
            //Short Error
            'Jan' => 'Jan', 'Feb' => 'Feb', 'Mar' => 'Mar', 'Apr' => 'Apr', 'May' => 'May', 'Jun' => 'Jun', 'Jul' => 'Jul', 'Aug' => 'Aug', 'Sep' => 'Sep', 'Oct' => 'Oct', 'Nov' => 'Nov', 'Dec' => 'Dec');
    $MonthPosArray = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
    $DayNameArray = array('Sunday' => 'Sunday', 'Monday' =>'Monday', 'Tuesday' => 'Tuesday', 'Wednesday' => 'Wednesday', 'Thursday' => 'Thursday', 'Friday' => 'Friday', 'Saturday' => 'Saturday',
    //Short name
    'Sun' => 'Sol', 'Mon' =>'Mon', 'Tue' => 'Tue', 'Wed' => 'Ons', 'Thu' => 'Thu', 'Fri' => 'fre', 'Sat' => 'Sat');
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
        //echo date ('F', $subscriptionDetail['start_date']);
        $first_month = date ('n', $subscriptionDetail['start_date']);
        $last_month = date ('n', $subscriptionDetail['end_date']);
        $total_months = ($last_month - $first_month);

        
        $first_month_date = date('Y-m', $subscriptionDetail['start_date']).'-01';
        $num_months = $total_months;
        $current_month = date('Y-m').'-01';

        for ($count = 0; $count <= $num_months; $count++) {
            $temp_month = array();
            $temp_month['month_number'] = date('n', strtotime($first_month_date.' + '.$count.' Months'));
            $temp_month['month_name'] = date('F - Y', strtotime($first_month_date.' + '.$count.' Months'));
            $nextMonthsArr[] = $temp_month;
        }

        //print_r($nextMonthsArr);
    
        $ApiKey = md5('astrowow.com');
        $UserId = $user_id;
        $PDate = '';
        $PDate = date('m-d-Y', $subscriptionDetail['start_date']);
	    $calendarResponse = '';

        $monthLimit = 12;

        $calendarResponse .= "<div class='tab03-outer floatL'>";

        if ($IsSubscribedUser) {
            $calendarResponse .= "<div class='tabify04-outer'>
        		<ul id='tabbed04' class='tabify04'>
                    <li class='previousMonth' myAttr='".$monthLimit."'> <img src=".BASEURL."webroot/img/left.png> </li>";
                   
                    $i = 1;
                    
                    foreach($nextMonthsArr as $key => $next_month) {
                        if( $i <= 12 ) {
                         $calendarResponse .= "<li class='tablinks' ref='$i' style='display: none;'>".$next_month['month_name']."</li>";
                        }
                        $i++;
                    }

                    $calendarResponse .= "<li class='nextMonth' onclick='tabbed_calendarPm(2)' ref='2'> <img src=".BASEURL."webroot/img/right.png> </li>
        		    <!-- <li><a href=".BASEURL."users/removeFromSubscription>Unsubscribe</a></li> -->
        		</ul>
                <div id='first_month' class='tabify04-content' ref='1' style='display: none;'>";
    
            $cMonth = date ('n', $subscriptionDetail['start_date']); //date("n");
            $cYear = date ('Y', $subscriptionDetail['start_date']); //date("Y");
 
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
                        $calendarResponse .= "<td align='center' valign='middle'  class='cal-alltd-user'>";
                        $dateWithLink = $cMonth.'-'.($i - $startday + 1).'-'.$cYear;
                        $dateWithLink1 = '"'.$dateWithLink.'"';
                        $calendarResponse .= "<div class='calendarIconTooltip' id=".$dateWithLink." ref=".$imageCount."></div>";
                        if ($dateWithLink1 >= date('m-d-Y', $_SESSION['calendar_start_date']) && $dateWithLink1 <= date('m-d-Y', $_SESSION['calendar_end_date'])) {
                            $calendarResponse .= "<a class='lnkDaily' onclick='button_onClick($dateWithLink1)' id='".$dateWithLink."' href='javascript:void(0);'>";
                        } else {
                            $calendarResponse .= "<a class='lnkDaily' id='".$dateWithLink."' href='javascript:void(0);'>";
                        }
                        $calendarResponse .= $ImgIconTag;
                        $calendarResponse .= "</a>";
                        $calendarResponse .= "</td>";
                    } else {
                        $calendarResponse .= "<td align='center' valign='middle'  class='cal-alltd-user '>";
                        $dateWithLink = $cMonth.'-'.($i - $startday + 1).'-'.$cYear;
                        $dateWithLink1 = '"'.$dateWithLink.'"';
                        $calendarResponse .= "<div class='calendarIconTooltip' id=".$dateWithLink." ref=".$imageCount."></div>";
                        if ($dateWithLink1 >= date('m-d-Y', $_SESSION['calendar_start_date']) && $dateWithLink1 <= date('m-d-Y', $_SESSION['calendar_end_date'])) {
                            $calendarResponse .= "<a class='lnkDaily' onclick='button_onClick($dateWithLink1)' id='".$dateWithLink."' href='javascript:void(0);'>";
                        } else {
                            $calendarResponse .= "<a class='lnkDaily' id='".$dateWithLink."' href='javascript:void(0);'>";
                        }
                        $calendarResponse .= $ImgIconTag;
                        $calendarResponse .= "</a>";
                        $calendarResponse .= "</td>";
                    }
                }
                if(($i % 7) == 6 ) {
                    $calendarResponse .= "</tr>";
                }
            }
            $calendarResponse .= "</table></div>";
         

       	$calendarResponse .= "</div>
        	<div id='tab-content-container'>        
            <div id='dvResult'>
            </div>
            </div>";
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
            
            /*if(isset($_COOKIE['language']) && !empty($_COOKIE['language'])) {
            	$LanguageId = $_COOKIE['language'];
            }*/
            
            /*if(strtolower($LanguageId) == "dk") {
				$DayCount = 3;
				$date = date('M-d-y', strtotime("-1 day"));				
			}*/
            
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
        $calendarResponse .= "<form id='frmDailyPersonal' action=".BASEURL."'/bin/daily-personal/generate.daily.personal.php' method='POST'>
            <input type='hidden' id='apikey' value=".md5('astrowow.com')." />
            <input type='hidden' id='userid' value=".$person_id." />
            <input type='hidden' id='pDate' value='' />
        </form>
    </div>";
    //return $calendarResponse;
    echo $calendarResponse; die;
?>