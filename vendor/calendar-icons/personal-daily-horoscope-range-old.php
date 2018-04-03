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
/*if ( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' ) {
    echo $user_id; die;
}*/
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

//$result = $objUserSubscription->IsSubscribeUser($person_id, $userType);
//$subscriptionDetail = $objUserSubscription->customUserDetail ($person_id, $userType);
//echo date ('F', $subscriptionDetail['start_date']); pr ($subscriptionDetail); die;
/*if($result) {
    $IsSubscribedUser = 1;
}*/

$selectedLanguage = trim($lang);
if ($selectedLanguage == 'da') {
    $selectedLanguage = 'dk';
}

/*$selectedLanguage = !empty($this->request->session()->read('locale')) ? $this->request->session()->read('locale') : 'en';
if ($selectedLanguage == 'da') {
    $selectedLanguage = 'dk';
}*/
//$selectedLanguage = (isset($_GET['language']) && !empty($_GET['language'])) ? strtolower($_GET['language']) : $selectedLanguage;
if ($selectedLanguage == 'dk') {
    $MonthNameArray = array('January' => 'Januar', 'February' => 'Februar', 'March' => 'Marts', 'April' => 'April', 'May' => 'Maj', 'June' => 'Juni', 'July' => 'Juli', 'August' => 'August', 'September' => 'September', 'October' => 'Oktober', 'November' => 'November', 'December' => 'December',
            //Short Error
            /*'Jan' => 'Jan', 'Feb' => 'Feb', 'Mar' => 'Mar', 'Apr' => 'Apr', 'May' => 'May', 'Jun' => 'Jun', 'Jul' => 'Jul', 'Aug' => 'Aug', 'Sep' => 'Sep', 'Oct' => 'Oct', 'Nov' => 'Nov', 'Dec' => 'Dec'*/);
    $MonthPosArray = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
    $DayNameArray = array('Sunday' => 'Sunday', 'Monday' =>'Monday', 'Tuesday' => 'Tuesday', 'Wednesday' => 'Wednesday', 'Thursday' => 'Thursday', 'Friday' => 'Friday', 'Saturday' => 'Saturday',
    //Short name
    'Sun' => 'Sol', 'Mon' =>'Mon', 'Tue' => 'Tue', 'Wed' => 'Ons', 'Thu' => 'Thu', 'Fri' => 'fre', 'Sat' => 'Sat');
} else {
    $MonthNameArray = array('January' => 'January', 'February' => 'February', 'March' => 'March', 'April' => 'April', 'May' => 'May', 'June' => 'June', 'July' => 'July', 'August' => 'August', 'September' => 'September', 'October' => 'October', 'November' => 'November', 'December' => 'December',
    		//Short Error
    		/*'Jan' => 'Jan', 'Feb' => 'Feb', 'Mar' => 'Mar', 'Apr' => 'Apr', 'May' => 'May', 'Jun' => 'Jun', 'Jul' => 'Jul', 'Aug' => 'Aug', 'Sep' => 'Sep', 'Oct' => 'Oct', 'Nov' => 'Nov', 'Dec' => 'Dec'*/);
    $MonthPosArray = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
    $DayNameArray = array('Sunday' => 'Sunday', 'Monday' =>'Monday', 'Tuesday' => 'Tuesday', 'Wednesday' => 'Wednesday', 'Thursday' => 'Thursday', 'Friday' => 'Friday', 'Saturday' => 'Saturday',
	//Short name
	'Sun' => 'Sun', 'Mon' =>'Mon', 'Tue' => 'Tue', 'Wed' => 'Wed', 'Thu' => 'Thu', 'Fri' => 'Fri', 'Sat' => 'Sat');
}
$personal_horoscope_help_text = "The Horoscope Calendar is not the same as your daily sun sign horoscope. These texts are based on your birth planets and therefore are unique to you. The free subscription provides horoscopes 5 days in advance. To get the complete 3 month calendar, please click on the subscribe button.";

$ASTROPAGE_PERSONAL_HOROSCOPE_TEXT = 'Below you can see short term and long term influences day by day on your personal birth chart. You can use the questions and answers to clarify issues that arise.';

$FOR_TEXT = 'for';

/*if ( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' ) {
    echo '<pre>'; print_r($MonthNameArray); die;
}*/

?>

<input type="hidden" id="person_id" name="person_id" value="<?php echo $person_id;?>" />
	<?php 
        //echo date ('F', $subscriptionDetail['start_date']);
        $subscriptionDetail['start_date'] = strtotime('01-'.date('m-Y', $startDateInMS));
        $subscriptionDetail['end_date'] = strtotime(date('t-m-Y', $endDateInMS));
        $first_month = date ('F', $subscriptionDetail['start_date']); //date('F', mktime(0, 0, 0, 12, 10)); //date("F");
        $last_month = date ('F', $subscriptionDetail['end_date']);
        $_SESSION['calendar_start_date'] = $subscriptionDetail['start_date'];
        $_SESSION['calendar_end_date'] = $subscriptionDetail['end_date'];

        //$date = date('Y-m-d',strtotime(date("Y").'-'.(date("n")).'-1'));
        $data = date('Y-m-d', $subscriptionDetail['start_date']);
        //$currentMonth = date ('F');

        $last_month_status = 0;
        function checkLastMonth ($month, $lmonth) {
            if ( (strtolower($lmonth) == strtolower($month)) ) {
                return true;
            }
            return false;
        }


        $d = new DateTime( $data );
        $d->modify( 'first day of next month' );
        //$second_month = $d->format( 'F' );
        if (!$last_month_status) {
            if ( checkLastMonth($d->format( 'F' ), $last_month) ) {
                $last_month_status = 1;
            }
            $second_month = $d->format( 'F' );
        }

        $d1 = new DateTime( $d->format( 'Y-m-d' ) );
        $d1->modify( 'first day of next month' );
        //$third_month = $d1->format( 'F' );
        if (!$last_month_status) {
            if ( checkLastMonth($d1->format( 'F' ), $last_month) ) {
                $last_month_status = 1;
            }
            $third_month = $d1->format( 'F' );
        }

        $d2 = new DateTime( $d1->format( 'Y-m-d' ) );
        $d2->modify( 'first day of next month' );
        //$fourth_month = $d2->format( 'F' );
        if (!$last_month_status) {
            if ( checkLastMonth($d2->format( 'F' ), $last_month) ) {
                $last_month_status = 1;
            }
            $fourth_month = $d2->format( 'F' );
        }

        $d3 = new DateTime( $d2->format( 'Y-m-d' ) );
        $d3->modify( 'first day of next month' );
        //$fifth_month = $d3->format( 'F' );
        if (!$last_month_status) {
            if ( checkLastMonth($d3->format( 'F' ), $last_month) ) {
                $last_month_status = 1;
            }
            $fifth_month = $d3->format( 'F' );
        }

        $d4 = new DateTime( $d3->format( 'Y-m-d' ) );
        $d4->modify( 'first day of next month' );
        //$sixth_month = $d4->format( 'F' );
        if (!$last_month_status) {
            if ( checkLastMonth($d4->format( 'F' ), $last_month) ) {
                $last_month_status = 1;
            }
            $sixth_month = $d4->format( 'F' );
        }

        $d5 = new DateTime( $d4->format( 'Y-m-d' ) );
        $d5->modify( 'first day of next month' );
        //$seventh_month = $d5->format( 'F' );
        if (!$last_month_status) {
            if ( checkLastMonth($d5->format( 'F' ), $last_month) ) {
                $last_month_status = 1;
            }
            $seventh_month = $d5->format( 'F' );
        }

        $d6 = new DateTime( $d5->format( 'Y-m-d' ) );
        $d6->modify( 'first day of next month' );
        //$eighth_month = $d6->format( 'F' );
        if (!$last_month_status) {
            if ( checkLastMonth($d6->format( 'F' ), $last_month) ) {
                $last_month_status = 1;
            }
            $eighth_month = $d6->format( 'F' );
        }

        $d7 = new DateTime( $d6->format( 'Y-m-d' ) );
        $d7->modify( 'first day of next month' );
        //$nineth_month = $d7->format( 'F' );
        if (!$last_month_status) {
            if ( checkLastMonth($d7->format( 'F' ), $last_month) ) {
                $last_month_status = 1;
            }
            $nineth_month = $d7->format( 'F' );
        }

        $d8 = new DateTime( $d7->format( 'Y-m-d' ) );
        $d8->modify( 'first day of next month' );
        //$tenth_month = $d8->format( 'F' );
        if (!$last_month_status) {
            if ( checkLastMonth($d8->format( 'F' ), $last_month) ) {
                $last_month_status = 1;
            }
            $tenth_month = $d8->format( 'F' );
        }

        $d9 = new DateTime( $d8->format( 'Y-m-d' ) );
        $d9->modify( 'first day of next month' );
        //$eleventh_month = $d9->format( 'F' );
        if (!$last_month_status) {
            if ( checkLastMonth($d9->format( 'F' ), $last_month) ) {
                $last_month_status = 1;
            }
            $eleventh_month = $d9->format( 'F' );
        }

        $d10 = new DateTime( $d9->format( 'Y-m-d' ) );
        $d10->modify( 'first day of next month' );
        //$twelth_month = $d10->format( 'F' );
        if (!$last_month_status) {
            if ( checkLastMonth($d10->format( 'F' ), $last_month) ) {
                $last_month_status = 1;
            }
            $twelth_month = $d10->format( 'F' );
        }

        $d11 = new DateTime( $d10->format( 'Y-m-d' ) );
        $d11->modify( 'first day of next month' );
        //$twelth_month = $d10->format( 'F' );
        if (!$last_month_status) {
            if ( checkLastMonth($d11->format( 'F' ), $last_month) ) {
                $last_month_status = 1;
            }
            $thirteenth_month = $d11->format( 'F' );
        }

        $calMonth = date ('n', $subscriptionDetail['start_date']); //date("n");
        $calYear = date ('Y', $subscriptionDetail['start_date']);
        
        function monthYear ($calMonth, $calYear) {
            $calMonth = $calMonth + 1;
            $prev_month = $calMonth-1;
            $next_month = $calMonth+1;

            if ($prev_month == 0 ) {
                $prev_month = 12;
                $calYear = $calYear - 1;
            }
            if ($calMonth >= 13 ) {
                $calMonth = 1;
                $calYear = $calYear + 1;
            }
            return $calYear;
        }

        $monthLimit = 0;

        if (isset($first_month) && !empty($first_month)) {
            if( array_key_exists($first_month, $MonthNameArray) ) {
                $first_month = $MonthNameArray[$first_month];
                $mpos = array_search(ucfirst($first_month), $MonthPosArray);
                if (($mpos+1) == 12) {
                    $mycalYear = $calYear;
                    $calYear = $calYear+1;
                } else {
                    $calYear = $mycalYear = monthYear (($mpos+1), $calYear);
                }
                $monthhh1 = $first_month.' - '.$mycalYear;
                $monthLimit = 1;
            }
        }
        
        if (isset($second_month) && !empty($second_month)) {
            if( array_key_exists($second_month, $MonthNameArray) ) {
            	$second_month = $MonthNameArray[$second_month];
                $mpos = array_search(ucfirst($second_month), $MonthPosArray);
                if (($mpos+1) == 12) {
                    $mycalYear = $calYear;
                    $calYear = $calYear+1;
                } else {
                    $calYear = $mycalYear = monthYear (($mpos+1), $calYear);
                }
                $monthhh2 = $second_month.' - '.$mycalYear;
                $monthLimit = 2;
            }
        }
        
        if (isset($third_month) && !empty($third_month)) {
            if( array_key_exists($third_month, $MonthNameArray) ) {
            	$third_month = $MonthNameArray[$third_month];
                $mpos = array_search(ucfirst($third_month), $MonthPosArray);
                if (($mpos+1) == 12) {
                    $mycalYear = $calYear;
                    $calYear = $calYear+1;
                } else {
                    $calYear = $mycalYear = monthYear (($mpos+1), $calYear);
                }
                $monthhh3 = $third_month.' - '.$mycalYear;
                $monthLimit = 3;
            }
        }

        if (isset($fourth_month) && !empty($fourth_month)) {
            if( array_key_exists($fourth_month, $MonthNameArray) ) {
                $fourth_month = $MonthNameArray[$fourth_month];
                $mpos = array_search(ucfirst($fourth_month), $MonthPosArray);
                if (($mpos+1) == 12) {
                    $mycalYear = $calYear;
                    $calYear = $calYear+1;
                } else {
                    $calYear = $mycalYear = monthYear (($mpos+1), $calYear);
                }
                $monthhh4 = $fourth_month.' - '.$mycalYear;
                $monthLimit = 4;
            }
        }

        if (isset($fifth_month) && !empty($fifth_month)) {
            if( array_key_exists($fifth_month, $MonthNameArray) ) {
                $fifth_month = $MonthNameArray[$fifth_month];
                $mpos = array_search(ucfirst($fifth_month), $MonthPosArray);
                if (($mpos+1) == 12) {
                    $mycalYear = $calYear;
                    $calYear = $calYear+1;
                } else {
                    $calYear = $mycalYear = monthYear (($mpos+1), $calYear);
                }
                $monthhh5 = $fifth_month.' - '.$mycalYear;
                $monthLimit = 5;
            }
        }

        if (isset($sixth_month) && !empty($sixth_month)) {
            if( array_key_exists($sixth_month, $MonthNameArray) ) {
                $sixth_month = $MonthNameArray[$sixth_month];
                $mpos = array_search(ucfirst($sixth_month), $MonthPosArray);
                if (($mpos+1) == 12) {
                    $mycalYear = $calYear;
                    $calYear = $calYear+1;
                } else {
                    $calYear = $mycalYear = monthYear (($mpos+1), $calYear);
                }
                $monthhh6 = $sixth_month.' - '.$mycalYear;
                $monthLimit = 6;
            }
        }
        if (isset($seventh_month) && !empty($seventh_month)) {
            if( array_key_exists($seventh_month, $MonthNameArray) ) {
                $seventh_month = $MonthNameArray[$seventh_month];
                $mpos = array_search(ucfirst($seventh_month), $MonthPosArray);
                if (($mpos+1) == 12) {
                    $mycalYear = $calYear;
                    $calYear = $calYear+1;
                } else {
                    $calYear = $mycalYear = monthYear (($mpos+1), $calYear);
                }
                $monthhh7 = $seventh_month.' - '.$mycalYear;
                $monthLimit = 7;
            }
        }
        
        if (isset($eighth_month) && !empty($eighth_month)) {
            if( array_key_exists($eighth_month, $MonthNameArray) ) {
                $eighth_month = $MonthNameArray[$eighth_month];
                $mpos = array_search(ucfirst($eighth_month), $MonthPosArray);
                if (($mpos+1) == 12) {
                    $mycalYear = $calYear;
                    $calYear = $calYear+1;
                } else {
                    $calYear = $mycalYear = monthYear (($mpos+1), $calYear);
                }
                $monthhh8 = $eighth_month.' - '.$mycalYear;
                $monthLimit = 8;
            }
        }

        if (isset($nineth_month) && !empty($nineth_month)) {
            if( array_key_exists($nineth_month, $MonthNameArray) ) {
                $nineth_month = $MonthNameArray[$nineth_month];
                $mpos = array_search(ucfirst($nineth_month), $MonthPosArray);
                if (($mpos+1) == 12) {
                    $mycalYear = $calYear;
                    $calYear = $calYear+1;
                } else {
                    $calYear = $mycalYear = monthYear (($mpos+1), $calYear);
                }
                $monthhh9 = $nineth_month.' - '.$mycalYear;
                $monthLimit = 9;
            }
        }

        if (isset($tenth_month) && !empty($tenth_month)) {
            if( array_key_exists($tenth_month, $MonthNameArray) ) {
                $tenth_month = $MonthNameArray[$tenth_month];
                $mpos = array_search(ucfirst($tenth_month), $MonthPosArray);
                if (($mpos+1) == 12) {
                    $mycalYear = $calYear;
                    $calYear = $calYear+1;
                } else {
                    $calYear = $mycalYear = monthYear (($mpos+1), $calYear);
                }
                $monthhh10 = $tenth_month.' - '.$mycalYear;
                $monthLimit = 10;
            }
        }

        /*if( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' ) {
            echo '<pre>'; print_r($MonthNameArray); echo $nineth_month.' => '.$tenth_month.' => '.$mycalYear.' => '.$calYear; die;
        }*/

        if (isset($eleventh_month) && !empty($eleventh_month)) {
            if( array_key_exists($eleventh_month, $MonthNameArray) ) {
                $eleventh_month = $MonthNameArray[$eleventh_month];
                $mpos = array_search(ucfirst($eleventh_month), $MonthPosArray);
                if (($mpos+1) == 12) {
                    $mycalYear = $calYear;
                    $calYear = $calYear+1;
                } else {
                    $calYear = $mycalYear = monthYear (($mpos+1), $calYear);
                }
                $monthhh11 = $eleventh_month.' - '.$mycalYear;
                $monthLimit = 11;
            }
        }

        if (isset($twelth_month) && !empty($twelth_month)) {
            if( array_key_exists($twelth_month, $MonthNameArray) ) {
                $twelth_month = $MonthNameArray[$twelth_month];
                $mpos = array_search(ucfirst($twelth_month), $MonthPosArray);
                if (($mpos+1) == 12) {
                    $mycalYear = $calYear;
                    $calYear = $calYear+1;
                } else {
                    $calYear = $mycalYear = monthYear (($mpos+1), $calYear);
                }
                $monthhh12 = $twelth_month.' - '.$mycalYear;
                $monthLimit = 12;
            }
        }

        if (isset($thirteenth_month) && !empty($thirteenth_month)) {
            if( array_key_exists($thirteenth_month, $MonthNameArray) ) {
                $thirteenth_month = $MonthNameArray[$thirteenth_month];
                $mpos = array_search(ucfirst($thirteenth_month), $MonthPosArray);
                if (($mpos+1) == 12) {
                    $mycalYear = $calYear;
                    $calYear = $calYear+1;
                } else {
                    $calYear = $mycalYear = monthYear (($mpos+1), $calYear);
                }
                $monthhh13 = $thirteenth_month.' - '.$mycalYear;
                $monthLimit = 13;
            }
        }

        /*if( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' ) {
            echo $monthhh1.' => '.$monthhh2.' => '.$monthhh3.' => '.$monthhh4.' => '.$monthhh5.' => '.$monthhh6.' => '.$monthhh7.' => '.$monthhh8.' => '.$monthhh9.' => '.$monthhh10.' => '.$monthhh11.' => '.$monthhh12; die;
        }*/

        $ApiKey = md5('astrowow.com');
        $UserId = $user_id;
        $PDate = '';
        $PDate = date('m-d-Y', $subscriptionDetail['start_date']);
	    $calendarResponse = '';

        $calendarResponse .= "<div class='tab03-outer floatL'>";

        
            $calendarResponse .= "<div class='tabify04-outer'>
        		<ul id='tabbed04' class='tabify04'>
                    <li class='previousMonth' myAttr='".$monthLimit."'> <img src=".BASEURL."webroot/img/left.png> </li>";
                    if (isset($monthhh1) && !empty($monthhh1)) {
        		      $calendarResponse .= "<li data-mod='".$monthLimit."' class='tablinks active' onclick='tabbed_calendar(1, ".$monthLimit.")' ref='1' style='display: none;'>".ucfirst($monthhh1)."</li>";
                    }
                    if (isset($monthhh2) && !empty($monthhh2)) {
        		      $calendarResponse .= "<li class='tablinks' onclick='tabbed_calendar(2, ".$monthLimit.")' ref='2' style='display: none;'>".ucfirst($monthhh2)."</li>";
                    }
                    if (isset($monthhh3) && !empty($monthhh3)) {
                        $calendarResponse .= "<li class='tablinks' onclick='tabbed_calendar(3, ".$monthLimit.")' ref='3' style='display: none;'>".ucfirst($monthhh3)."</li>";
                    }
                    if (isset($monthhh4) && !empty($monthhh4)) {
                        $calendarResponse .= "<li class='tablinks' onclick='tabbed_calendar(4, ".$monthLimit.")' ref='4' style='display: none;'>".ucfirst($monthhh4)."</li>";
                    }
                    if (isset($monthhh5) && !empty($monthhh5)) {
                        $calendarResponse .= "<li class='tablinks' onclick='tabbed_calendar(5, ".$monthLimit.")' ref='5' style='display: none;'>".ucfirst($monthhh5)."</li>";
                    }
                    if (isset($monthhh6) && !empty($monthhh6)) {
                        $calendarResponse .= "<li class='tablinks' onclick='tabbed_calendar(6, ".$monthLimit.")' ref='6' style='display: none;'>".ucfirst($monthhh6)."</li>";
                    }
                    if (isset($monthhh7) && !empty($monthhh7)) {
                        $calendarResponse .= "<li class='tablinks' onclick='tabbed_calendar(7, ".$monthLimit.")' ref='7' style='display: none;'>".ucfirst($monthhh7)."</li>";
                    }
                    if (isset($monthhh8) && !empty($monthhh8)) {
                        $calendarResponse .= "<li class='tablinks' onclick='tabbed_calendar(8, ".$monthLimit.")' ref='8' style='display: none;'>".ucfirst($monthhh8)."</li>";
                    }
                    if (isset($monthhh9) && !empty($monthhh9)) {
                        $calendarResponse .= "<li class='tablinks' onclick='tabbed_calendar(9, ".$monthLimit.")' ref='9' style='display: none;'>".ucfirst($monthhh9)."</li>";
                    }
                    if (isset($monthhh10) && !empty($monthhh10)) {
                        $calendarResponse .= "<li class='tablinks' onclick='tabbed_calendar(10, ".$monthLimit.")' ref='10' style='display: none;'>".ucfirst($monthhh10)."</li>";
                    }
                    if (isset($monthhh11) && !empty($monthhh11)) {
                        $calendarResponse .= "<li class='tablinks' onclick='tabbed_calendar(11, ".$monthLimit.")' ref='11' style='display: none;'>".ucfirst($monthhh11)."</li>";
                    }
                    if (isset($monthhh12) && !empty($monthhh12)) {
                        $calendarResponse .= "<li class='tablinks' onclick='tabbed_calendar(12, ".$monthLimit.")' ref='12' style='display: none;'>".ucfirst($monthhh12)."</li>";
                    }
                    //if( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' ) {
                        if (isset($monthhh13) && !empty($monthhh13)) {
                            $calendarResponse .= "<li class='tablinks' onclick='tabbed_calendar(13, ".$monthLimit.")' ref='13' style='display: none;'>".ucfirst($monthhh13)."</li>";
                        }
                    //}

                    $calendarResponse .= "<li class='nextMonth' onclick='tabbed_calendar(2, ".$monthLimit.")' ref='2'> <img src=".BASEURL."webroot/img/right.png> </li>
        		    <!-- <li><a href=".BASEURL."users/removeFromSubscription>Unsubscribe</a></li> -->
        		</ul>
                <div id='first_month' class='tabify04-content' ref='1' style='display: none;'>";
    	
        	$monthNames = Array("January", "February", "March", "April", "May", "June", "July",
                        "August", "September", "October", "November", "December");

            $cMonth = date ('n', $subscriptionDetail['start_date']); //date("n");
            $cYear = date ('Y', $subscriptionDetail['start_date']); //date("Y");


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

            /*if( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' ) {
            	echo $PDate; die;
            }*/
            $jsonObject = predictionIcons ($ApiKey, $PDate, $user_id, $selectedLanguage);
            //pr ($jsonObject); die;
            //$jsonObject = getMonthlyPOSNEGIcons($ApiKey, $PDate, $user_id);
            

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
                        if (validDateForShowPrediction($dateWithLink)) {
                            $calendarResponse .= "<a class='lnkDaily' onclick='button_onClick($dateWithLink1)' id='".$dateWithLink."' href='javascript:void(0);' >";
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
                        if (validDateForShowPrediction($dateWithLink)) {
                            $calendarResponse .= "<a class='lnkDaily' onclick='button_onClick($dateWithLink1)' id='".$dateWithLink."' href='javascript:void(0);'>";
                        } else {
                            $calendarResponse .= "<a class='lnkDaily' id='".$dateWithLink."' href='javascript:void(0);'>";
                        }



                        /*if( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' ) {
                            $calendarResponse .= "<a class='lnkDaily' onclick='button_onClick($dateWithLink1)' id='".$dateWithLink."' href='javascript:void(0);'>";
                        } else {
                            $calendarResponse .= "<a class='lnkDaily' onclick='button_onClick($dateWithLink1)' id='".$dateWithLink."' href='javascript:void(0);'>";
                        }*/
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
            /*if( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' ) {
                echo '<pre>'; print_r ($calendarResponse); die;
            }*/
            if (isset($second_month) && !empty($second_month)) {
                $calendarResponse .= "<div id='second_month' class='tabify04-content' ref='2' style='display: none;'>";
		        $cMonth = date ('n', $subscriptionDetail['start_date'])+1; //date("n") + 1;

                $prev_year = $cYear;
                $next_year = $cYear;
                $prev_month = $cMonth-1;
                $next_month = $cMonth+1;

                if ($cMonth >= 13 ) {
                    $cMonth = 1;
                    $cYear = $cYear + 1;
                }

                $calendarResponse .= "<table width='100%' border='0' cellspacing='0' cellpadding='0' class='caltable'>
                    <tr class='cal-heading-user'>
                        <td align='center' valign='middle' class='cal-alltd-user'>".$DayNameArray['Sun']."</td>
                        <td align='center' valign='middle' class='cal-alltd-user'>".$DayNameArray['Mon']."</td>
                        <td align='center' valign='middle' class='cal-alltd-user'>".$DayNameArray['Tue']."</td>
                        <td align='center' valign='middle' class='cal-alltd-user'>".$DayNameArray['Wed']."</td>
                        <td align='center' valign='middle' class='cal-alltd-user'>".$DayNameArray['Thu']."</td>
                        <td align='center' valign='middle' class='cal-alltd-user'>".$DayNameArray['Fri']."</td>
                        <td align='center' valign='middle' class='cal-alltd-user'>".$DayNameArray['Sat']."</td>
                    </tr>";

        	    $timestamp = mktime(0,0,0,$cMonth,1,$cYear);
                $maxday = date("t",$timestamp);
                $thismonth = getdate ($timestamp);
                $startday = $thismonth['wday'];
                
                $PDate = '';
                $PDate = sprintf("%02d-%02d-%04d", $cMonth, 1, $cYear);                    
                require_once (BALPATH . "/calendar.icon.functions.php");

                $jsonObject = predictionIcons ($ApiKey, $PDate, $user_id, $selectedLanguage);
                        
                for ($i=0; $i<($maxday+$startday); $i++) {
                    $imageCount = 0;
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
                                    array_push($PositiveImage, sprintf("<img src=\"%s\" class=\"imgCalenderIcon\" width=\"30\" height=\"30\" alt=\"%s\" onmouseover = 'showToolTipOnCalendarIcons (\"%s\", \"%s\", \"%s\", \"%s\");' onmouseout = 'hideToolTipOnCalendarIcons ();' />",
                                    $Item[$Index]['Icons'],
                                    $Item[$Index]['Title'],
                                    $Item[$Index]['Icons'],
                                    $Item[$Index]['Title'],
                                    $Item[$Index]['TransitTitle'],
                                    $TodaysId));
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

                    if(($i % 7) == 0 ) {
                        $calendarResponse .= "<tr  class='caltd-user'>";
                    }

                    if($i < $startday) {
                        $calendarResponse .= "<td class='cal-alltd-user caltd-rgtborder-user'></td>";
                    } else {
                        if(($i % 7) == 6 ) {
                            $calendarResponse .= "<td align='center' valign='middle'  class='cal-alltd-user '>";
                            $dateWithLink = $cMonth.'-'.($i - $startday + 1).'-'.$cYear;
                            $dateWithLink1 = '"'.$dateWithLink.'"';
                            $calendarResponse .= "<div class='calendarIconTooltip' id=".$dateWithLink." ref=".$imageCount."></div>";
                            //$calendarResponse .=  "<a class='lnkDaily' onclick='button_onClick($dateWithLink1)' id='".$dateWithLink."' href='javascript:void(0);'>";
                            if (validDateForShowPrediction($dateWithLink)) {
                                $calendarResponse .= "<a class='lnkDaily' onclick='button_onClick($dateWithLink1)' id='".$dateWithLink."' href='javascript:void(0);' myattrr='".dateFormating($dateWithLink)."'>";
                            } else {
                                $calendarResponse .= "<a class='lnkDaily' id='".$dateWithLink."' href='javascript:void(0);'>";
                            }
                            $calendarResponse .=  $ImgIconTag; 
                            $calendarResponse .=  "</a>";
                            $calendarResponse .=  "</td>";
                        } else {
                            $calendarResponse .=  "<td align='center' valign='middle'  class='cal-alltd-user caltd-rgtborder-user'>";
                            $dateWithLink = $cMonth.'-'.($i - $startday + 1).'-'.$cYear;
                            $dateWithLink1 = '"'.$dateWithLink.'"';
                            $calendarResponse .= "<div class='calendarIconTooltip' id=".$dateWithLink." ref=".$imageCount."></div>";
                            //$calendarResponse .=  "<a class='lnkDaily' onclick='button_onClick($dateWithLink1)' id='".$dateWithLink."' href='javascript:void(0);'>";
                            if (validDateForShowPrediction($dateWithLink)) {
                                $calendarResponse .= "<a class='lnkDaily' onclick='button_onClick($dateWithLink1)' id='".$dateWithLink."' href='javascript:void(0);'>";
                            } else {
                                $calendarResponse .= "<a class='lnkDaily' id='".$dateWithLink."' href='javascript:void(0);'>";
                            }
                            $calendarResponse .=  $ImgIconTag;
                            $calendarResponse .=  "</a>";
                            $calendarResponse .=  "</td>";
                        }
                    }

                    if(($i % 7) == 6 ) {
                        $calendarResponse .=  "</tr>";
                    }
                }
                $calendarResponse .= "</table></div>";
            }

            if (isset($third_month) && !empty($third_month)) {
                $calendarResponse .= "<div id='third_month' class='tabify04-content' ref='3' style='display: none;'>";
        		$cMonth = $cMonth + 1;
                
                $prev_year = $cYear;
                $next_year = $cYear;
                $prev_month = $cMonth-1;
                $next_month = $cMonth+1;

                if ($prev_month == 0 ) {
                    $prev_month = 12;
                    $prev_year = $cYear - 1;
                }
                if ($cMonth >= 13 ) {
                    $cMonth = 1;
                    $cYear = $cYear + 1;
                }
                $calendarResponse .= "<table width='100%' border='0' cellspacing='0' cellpadding='0' class='caltable'>
                    <tr class='cal-heading-user'>
                        <td align='center' valign='middle' class='cal-alltd-user'>".$DayNameArray['Sun']."</td>
                        <td align='center' valign='middle' class='cal-alltd-user'>".$DayNameArray['Mon']."</td>
                        <td align='center' valign='middle' class='cal-alltd-user'>".$DayNameArray['Tue']."</td>
                        <td align='center' valign='middle' class='cal-alltd-user'>".$DayNameArray['Wed']."</td>
                        <td align='center' valign='middle' class='cal-alltd-user'>".$DayNameArray['Thu']."</td>
                        <td align='center' valign='middle' class='cal-alltd-user'>".$DayNameArray['Fri']."</td>
                        <td align='center' valign='middle' class='cal-alltd-user'>".$DayNameArray['Sat']."</td>
                    </tr>";
                $timestamp = mktime(0,0,0,$cMonth,1,$cYear);
                $maxday = date("t",$timestamp);
                $thismonth = getdate ($timestamp);
                $startday = $thismonth['wday'];
                $PDate = '';
                $PDate = sprintf("%02d-%02d-%04d", $cMonth, 1, $cYear);                    
                require_once (BALPATH . "/calendar.icon.functions.php");
                $jsonObject = predictionIcons ($ApiKey, $PDate, $user_id, $selectedLanguage);
            
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
                                        array_push($PositiveImage, sprintf("<img src=\"%s\" class=\"imgCalenderIcon\" width=\"30\" height=\"30\" alt=\"%s\" onmouseover = 'showToolTipOnCalendarIcons (\"%s\", \"%s\", \"%s\", \"%s\");' onmouseout = 'hideToolTipOnCalendarIcons ();' />",
                                        $Item[$Index]['Icons'],
                                        $Item[$Index]['Title'],
                                        $Item[$Index]['Icons'],
                                        $Item[$Index]['Title'],
                                        $Item[$Index]['TransitTitle'],
                                        $TodaysId));
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
            				$ImgIconTag = sprintf($IconTable, "&nbsp;", ($i - $startday + 1), "&nbsp;");
            			}

                        if(($i % 7) == 0 ) {
                            $calendarResponse .= "<tr  class='caltd-user'>";
                        }

                        if($i < $startday) {
                            $calendarResponse .= "<td class='cal-alltd-user caltd-rgtborder-user'></td>";
                        } else {
                            if(($i % 7) == 6 ) {
                                $calendarResponse .= "<td align='center' valign='middle' class='cal-alltd-user '>";
                                $dateWithLink = $cMonth.'-'.($i - $startday + 1).'-'.$cYear;
                                $dateWithLink1 = '"'.$dateWithLink.'"';
                                $calendarResponse .= "<div class='calendarIconTooltip' id=".$dateWithLink." ref=".$imageCount."></div>";
                                //$calendarResponse .= "<a class='lnkDaily' onclick='button_onClick($dateWithLink1)' id='".$dateWithLink."' href='javascript:void(0);'>";
                                if (validDateForShowPrediction($dateWithLink)) {
                                    $calendarResponse .= "<a class='lnkDaily' onclick='button_onClick($dateWithLink1)' id='".$dateWithLink."' href='javascript:void(0);'>";
                                } else {
                                    $calendarResponse .= "<a class='lnkDaily' id='".$dateWithLink."' href='javascript:void(0);'>";
                                }
                                $calendarResponse .= $ImgIconTag;
                                $calendarResponse .= "</a>";
                                $calendarResponse .= "</td>";
                            } else {
                                $calendarResponse .= "<td align='center' valign='middle' class='cal-alltd-user caltd-rgtborder-user'>";
                                $dateWithLink = $cMonth.'-'.($i - $startday + 1).'-'.$cYear;
                                $dateWithLink1 = '"'.$dateWithLink.'"';
                                $calendarResponse .= "<div class='calendarIconTooltip' id=".$dateWithLink." ref=".$imageCount."></div>";
                                //$calendarResponse .= "<a class='lnkDaily' onclick='button_onClick($dateWithLink1)' id='".$dateWithLink."' href='javascript:void(0);'>";
                                if (validDateForShowPrediction($dateWithLink)) {
                                    $calendarResponse .= "<a class='lnkDaily' onclick='button_onClick($dateWithLink1)' id='".$dateWithLink."' href='javascript:void(0);'>";
                                } else {
                                    $calendarResponse .= "<a class='lnkDaily' id='".$dateWithLink."' href='javascript:void(0);'>";
                                }
                                $calendarResponse .= $ImgIconTag;
                                $calendarResponse .= "</a>";
                                $calendarResponse .= "</td>";
                            }
                        }
                    }
                    if(($i % 7) == 6 ) {
                        $calendarResponse .= "</tr>";
                    }
                }
                $calendarResponse .= "</table></div>";
            }
            if (isset($fourth_month) && !empty($fourth_month)) {
                $calendarResponse .= "<div id='fourth_month' class='tabify04-content' ref='4' style='display: none;'>";
                $cMonth = $cMonth + 1;
                
                $prev_year = $cYear;
                $next_year = $cYear;
                $prev_month = $cMonth-1;
                $next_month = $cMonth+1;

                if ($prev_month == 0 ) {
                    $prev_month = 12;
                    $prev_year = $cYear - 1;
                }
                if ($cMonth >= 13 ) {
                    $cMonth = 1;
                    $cYear = $cYear + 1;
                }
                $calendarResponse .= "<table width='100%' border='0' cellspacing='0' cellpadding='0' class='caltable'>
                    <tr class='cal-heading-user'>
                        <td align='center' valign='middle' class='cal-alltd-user'>".$DayNameArray['Sun']."</td>
                        <td align='center' valign='middle' class='cal-alltd-user'>".$DayNameArray['Mon']."</td>
                        <td align='center' valign='middle' class='cal-alltd-user'>".$DayNameArray['Tue']."</td>
                        <td align='center' valign='middle' class='cal-alltd-user'>".$DayNameArray['Wed']."</td>
                        <td align='center' valign='middle' class='cal-alltd-user'>".$DayNameArray['Thu']."</td>
                        <td align='center' valign='middle' class='cal-alltd-user'>".$DayNameArray['Fri']."</td>
                        <td align='center' valign='middle' class='cal-alltd-user'>".$DayNameArray['Sat']."</td>
                    </tr>";
                $timestamp = mktime(0,0,0,$cMonth,1,$cYear);
                $maxday = date("t",$timestamp);
                $thismonth = getdate ($timestamp);
                $startday = $thismonth['wday'];
                $PDate = '';
                $PDate = sprintf("%02d-%02d-%04d", $cMonth, 1, $cYear);                    
                require_once (BALPATH . "/calendar.icon.functions.php");
                $jsonObject = predictionIcons ($ApiKey, $PDate, $user_id, $selectedLanguage);
                
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
                                        array_push($PositiveImage, sprintf("<img src=\"%s\" class=\"imgCalenderIcon\" width=\"30\" height=\"30\" alt=\"%s\" onmouseover = 'showToolTipOnCalendarIcons (\"%s\", \"%s\", \"%s\", \"%s\");' onmouseout = 'hideToolTipOnCalendarIcons ();' />",
                                        $Item[$Index]['Icons'],
                                        $Item[$Index]['Title'],
                                        $Item[$Index]['Icons'],
                                        $Item[$Index]['Title'],
                                        $Item[$Index]['TransitTitle'],
                                        $TodaysId));
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
                            $ImgIconTag = sprintf($IconTable, "&nbsp;", ($i - $startday + 1), "&nbsp;");
                        }

                        if(($i % 7) == 0 ) {
                            $calendarResponse .= "<tr  class='caltd-user'>";
                        }

                        if($i < $startday) {
                            $calendarResponse .= "<td class='cal-alltd-user caltd-rgtborder-user'></td>";
                        } else {
                            if(($i % 7) == 6 ) {
                                $calendarResponse .= "<td align='center' valign='middle' class='cal-alltd-user '>";
                                $dateWithLink = $cMonth.'-'.($i - $startday + 1).'-'.$cYear;
                                $dateWithLink1 = '"'.$dateWithLink.'"';
                                $calendarResponse .= "<div class='calendarIconTooltip' id=".$dateWithLink." ref=".$imageCount."></div>";
                                //$calendarResponse .= "<a class='lnkDaily' onclick='button_onClick($dateWithLink1)' id='".$dateWithLink."' href='javascript:void(0);'>";
                                if (validDateForShowPrediction($dateWithLink)) {
                                    $calendarResponse .= "<a class='lnkDaily' onclick='button_onClick($dateWithLink1)' id='".$dateWithLink."' href='javascript:void(0);'>";
                                } else {
                                    $calendarResponse .= "<a class='lnkDaily' id='".$dateWithLink."' href='javascript:void(0);'>";
                                }
                                $calendarResponse .= $ImgIconTag;
                                $calendarResponse .= "</a>";
                                $calendarResponse .= "</td>";
                            } else {
                                $calendarResponse .= "<td align='center' valign='middle' class='cal-alltd-user caltd-rgtborder-user'>";
                                $dateWithLink = $cMonth.'-'.($i - $startday + 1).'-'.$cYear;
                                $dateWithLink1 = '"'.$dateWithLink.'"';
                                $calendarResponse .= "<div class='calendarIconTooltip' id=".$dateWithLink." ref=".$imageCount."></div>";
                                //$calendarResponse .= "<a class='lnkDaily' onclick='button_onClick($dateWithLink1)' id='".$dateWithLink."' href='javascript:void(0);'>";
                                if (validDateForShowPrediction($dateWithLink)) {
                                    $calendarResponse .= "<a class='lnkDaily' onclick='button_onClick($dateWithLink1)' id='".$dateWithLink."' href='javascript:void(0);'>";
                                } else {
                                    $calendarResponse .= "<a class='lnkDaily' id='".$dateWithLink."' href='javascript:void(0);'>";
                                }
                                $calendarResponse .= $ImgIconTag;
                                $calendarResponse .= "</a>";
                                $calendarResponse .= "</td>";
                            }
                        }
                    }
                    if(($i % 7) == 6 ) {
                        $calendarResponse .= "</tr>";
                    }
                }
                $calendarResponse .= "</table></div>";
            }
            if (isset($fifth_month) && !empty($fifth_month)) {
                $calendarResponse .= "<div id='fifth_month' class='tabify04-content' ref='5' style='display: none;'>";
                $cMonth = $cMonth + 1;
                
                $prev_year = $cYear;
                $next_year = $cYear;
                $prev_month = $cMonth-1;
                $next_month = $cMonth+1;

                if ($prev_month == 0 ) {
                    $prev_month = 12;
                    $prev_year = $cYear - 1;
                }
                if ($cMonth >= 13 ) {
                    $cMonth = 1;
                    $cYear = $cYear + 1;
                }
                $calendarResponse .= "<table width='100%' border='0' cellspacing='0' cellpadding='0' class='caltable'>
                    <tr class='cal-heading-user'>
                        <td align='center' valign='middle' class='cal-alltd-user'>".$DayNameArray['Sun']."</td>
                        <td align='center' valign='middle' class='cal-alltd-user'>".$DayNameArray['Mon']."</td>
                        <td align='center' valign='middle' class='cal-alltd-user'>".$DayNameArray['Tue']."</td>
                        <td align='center' valign='middle' class='cal-alltd-user'>".$DayNameArray['Wed']."</td>
                        <td align='center' valign='middle' class='cal-alltd-user'>".$DayNameArray['Thu']."</td>
                        <td align='center' valign='middle' class='cal-alltd-user'>".$DayNameArray['Fri']."</td>
                        <td align='center' valign='middle' class='cal-alltd-user'>".$DayNameArray['Sat']."</td>
                    </tr>";
                $timestamp = mktime(0,0,0,$cMonth,1,$cYear);
                $maxday = date("t",$timestamp);
                $thismonth = getdate ($timestamp);
                $startday = $thismonth['wday'];
                $PDate = '';
                $PDate = sprintf("%02d-%02d-%04d", $cMonth, 1, $cYear);                    
                require_once (BALPATH . "/calendar.icon.functions.php");
                $jsonObject = predictionIcons ($ApiKey, $PDate, $user_id, $selectedLanguage);
            
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
                                        array_push($PositiveImage, sprintf("<img src=\"%s\" class=\"imgCalenderIcon\" width=\"30\" height=\"30\" alt=\"%s\" onmouseover = 'showToolTipOnCalendarIcons (\"%s\", \"%s\", \"%s\", \"%s\");' onmouseout = 'hideToolTipOnCalendarIcons ();' />",
                                        $Item[$Index]['Icons'],
                                        $Item[$Index]['Title'],
                                        $Item[$Index]['Icons'],
                                        $Item[$Index]['Title'],
                                        $Item[$Index]['TransitTitle'],
                                        $TodaysId));
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
                            $ImgIconTag = sprintf($IconTable, "&nbsp;", ($i - $startday + 1), "&nbsp;");
                        }

                        if(($i % 7) == 0 ) {
                            $calendarResponse .= "<tr  class='caltd-user'>";
                        }

                        if($i < $startday) {
                            $calendarResponse .= "<td class='cal-alltd-user caltd-rgtborder-user'></td>";
                        } else {
                            if(($i % 7) == 6 ) {
                                $calendarResponse .= "<td align='center' valign='middle' class='cal-alltd-user '>";
                                $dateWithLink = $cMonth.'-'.($i - $startday + 1).'-'.$cYear;
                                $dateWithLink1 = '"'.$dateWithLink.'"';
                                $calendarResponse .= "<div class='calendarIconTooltip' id=".$dateWithLink." ref=".$imageCount."></div>";
                                //$calendarResponse .= "<a class='lnkDaily' onclick='button_onClick($dateWithLink1)' id='".$dateWithLink."' href='javascript:void(0);'>";
                                if (validDateForShowPrediction($dateWithLink)) {
                                    $calendarResponse .= "<a class='lnkDaily' onclick='button_onClick($dateWithLink1)' id='".$dateWithLink."' href='javascript:void(0);'>";
                                } else {
                                    $calendarResponse .= "<a class='lnkDaily' id='".$dateWithLink."' href='javascript:void(0);'>";
                                }
                                $calendarResponse .= $ImgIconTag;
                                $calendarResponse .= "</a>";
                                $calendarResponse .= "</td>";
                            } else {
                                $calendarResponse .= "<td align='center' valign='middle' class='cal-alltd-user caltd-rgtborder-user'>";
                                $dateWithLink = $cMonth.'-'.($i - $startday + 1).'-'.$cYear;
                                $dateWithLink1 = '"'.$dateWithLink.'"';
                                $calendarResponse .= "<div class='calendarIconTooltip' id=".$dateWithLink." ref=".$imageCount."></div>";
                                //$calendarResponse .= "<a class='lnkDaily' onclick='button_onClick($dateWithLink1)' id='".$dateWithLink."' href='javascript:void(0);'>";
                                if (validDateForShowPrediction($dateWithLink)) {
                                    $calendarResponse .= "<a class='lnkDaily' onclick='button_onClick($dateWithLink1)' id='".$dateWithLink."' href='javascript:void(0);'>";
                                } else {
                                    $calendarResponse .= "<a class='lnkDaily' id='".$dateWithLink."' href='javascript:void(0);'>";
                                }
                                $calendarResponse .= $ImgIconTag;
                                $calendarResponse .= "</a>";
                                $calendarResponse .= "</td>";
                            }
                        }
                    }
                    if(($i % 7) == 6 ) {
                        $calendarResponse .= "</tr>";
                    }
                }
                $calendarResponse .= "</table></div>";
            }
            if (isset($sixth_month) && !empty($sixth_month)) {
                $calendarResponse .= "<div id='sixth_month' class='tabify04-content' ref='6' style='display: none;'>";
                $cMonth = $cMonth + 1;
                
                $prev_year = $cYear;
                $next_year = $cYear;
                $prev_month = $cMonth-1;
                $next_month = $cMonth+1;

                if ($prev_month == 0 ) {
                    $prev_month = 12;
                    $prev_year = $cYear - 1;
                }
                if ($cMonth >= 13 ) {
                    $cMonth = 1;
                    $cYear = $cYear + 1;
                }
                $calendarResponse .= "<table width='100%' border='0' cellspacing='0' cellpadding='0' class='caltable'>
                    <tr class='cal-heading-user'>
                        <td align='center' valign='middle' class='cal-alltd-user'>".$DayNameArray['Sun']."</td>
                        <td align='center' valign='middle' class='cal-alltd-user'>".$DayNameArray['Mon']."</td>
                        <td align='center' valign='middle' class='cal-alltd-user'>".$DayNameArray['Tue']."</td>
                        <td align='center' valign='middle' class='cal-alltd-user'>".$DayNameArray['Wed']."</td>
                        <td align='center' valign='middle' class='cal-alltd-user'>".$DayNameArray['Thu']."</td>
                        <td align='center' valign='middle' class='cal-alltd-user'>".$DayNameArray['Fri']."</td>
                        <td align='center' valign='middle' class='cal-alltd-user'>".$DayNameArray['Sat']."</td>
                    </tr>";
                $timestamp = mktime(0,0,0,$cMonth,1,$cYear);
                $maxday = date("t",$timestamp);
                $thismonth = getdate ($timestamp);
                $startday = $thismonth['wday'];
                $PDate = '';
                $PDate = sprintf("%02d-%02d-%04d", $cMonth, 1, $cYear);                    
                require_once (BALPATH . "/calendar.icon.functions.php");
                $jsonObject = predictionIcons ($ApiKey, $PDate, $user_id, $selectedLanguage);
            
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
                                        array_push($PositiveImage, sprintf("<img src=\"%s\" class=\"imgCalenderIcon\" width=\"30\" height=\"30\" alt=\"%s\" onmouseover = 'showToolTipOnCalendarIcons (\"%s\", \"%s\", \"%s\", \"%s\");' onmouseout = 'hideToolTipOnCalendarIcons ();' />",
                                        $Item[$Index]['Icons'],
                                        $Item[$Index]['Title'],
                                        $Item[$Index]['Icons'],
                                        $Item[$Index]['Title'],
                                        $Item[$Index]['TransitTitle'],
                                        $TodaysId));
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
                            $ImgIconTag = sprintf($IconTable, "&nbsp;", ($i - $startday + 1), "&nbsp;");
                        }

                        if(($i % 7) == 0 ) {
                            $calendarResponse .= "<tr  class='caltd-user'>";
                        }

                        if($i < $startday) {
                            $calendarResponse .= "<td class='cal-alltd-user caltd-rgtborder-user'></td>";
                        } else {
                            if(($i % 7) == 6 ) {
                                $calendarResponse .= "<td align='center' valign='middle' class='cal-alltd-user '>";
                                $dateWithLink = $cMonth.'-'.($i - $startday + 1).'-'.$cYear;
                                $dateWithLink1 = '"'.$dateWithLink.'"';
                                $calendarResponse .= "<div class='calendarIconTooltip' id=".$dateWithLink." ref=".$imageCount."></div>";
                                //$calendarResponse .= "<a class='lnkDaily' onclick='button_onClick($dateWithLink1)' id='".$dateWithLink."' href='javascript:void(0);'>";
                                if (validDateForShowPrediction($dateWithLink)) {
                                    $calendarResponse .= "<a class='lnkDaily' onclick='button_onClick($dateWithLink1)' id='".$dateWithLink."' href='javascript:void(0);'>";
                                } else {
                                    $calendarResponse .= "<a class='lnkDaily' id='".$dateWithLink."' href='javascript:void(0);'>";
                                }
                                $calendarResponse .= $ImgIconTag;
                                $calendarResponse .= "</a>";
                                $calendarResponse .= "</td>";
                            } else {
                                $calendarResponse .= "<td align='center' valign='middle' class='cal-alltd-user caltd-rgtborder-user'>";
                                $dateWithLink = $cMonth.'-'.($i - $startday + 1).'-'.$cYear;
                                $dateWithLink1 = '"'.$dateWithLink.'"';
                                $calendarResponse .= "<div class='calendarIconTooltip' id=".$dateWithLink." ref=".$imageCount."></div>";
                                //$calendarResponse .= "<a class='lnkDaily' onclick='button_onClick($dateWithLink1)' id='".$dateWithLink."' href='javascript:void(0);'>";
                                if (validDateForShowPrediction($dateWithLink)) {
                                    $calendarResponse .= "<a class='lnkDaily' onclick='button_onClick($dateWithLink1)' id='".$dateWithLink."' href='javascript:void(0);'>";
                                } else {
                                    $calendarResponse .= "<a class='lnkDaily' id='".$dateWithLink."' href='javascript:void(0);'>";
                                }
                                $calendarResponse .= $ImgIconTag;
                                $calendarResponse .= "</a>";
                                $calendarResponse .= "</td>";
                            }
                        }
                    }
                    if(($i % 7) == 6 ) {
                        $calendarResponse .= "</tr>";
                    }
                }
                $calendarResponse .= "</table></div>";
            }
            if (isset($seventh_month) && !empty($seventh_month)) {
                $calendarResponse .= "<div id='seventh_month' class='tabify04-content' ref='7' style='display: none;'>";
                $cMonth = $cMonth + 1;
                
                $prev_year = $cYear;
                $next_year = $cYear;
                $prev_month = $cMonth-1;
                $next_month = $cMonth+1;

                if ($prev_month == 0 ) {
                    $prev_month = 12;
                    $prev_year = $cYear - 1;
                }
                if ($cMonth >= 13 ) {
                    $cMonth = 1;
                    $cYear = $cYear + 1;
                }
                $calendarResponse .= "<table width='100%' border='0' cellspacing='0' cellpadding='0' class='caltable'>
                    <tr class='cal-heading-user'>
                        <td align='center' valign='middle' class='cal-alltd-user'>".$DayNameArray['Sun']."</td>
                        <td align='center' valign='middle' class='cal-alltd-user'>".$DayNameArray['Mon']."</td>
                        <td align='center' valign='middle' class='cal-alltd-user'>".$DayNameArray['Tue']."</td>
                        <td align='center' valign='middle' class='cal-alltd-user'>".$DayNameArray['Wed']."</td>
                        <td align='center' valign='middle' class='cal-alltd-user'>".$DayNameArray['Thu']."</td>
                        <td align='center' valign='middle' class='cal-alltd-user'>".$DayNameArray['Fri']."</td>
                        <td align='center' valign='middle' class='cal-alltd-user'>".$DayNameArray['Sat']."</td>
                    </tr>";
                $timestamp = mktime(0,0,0,$cMonth,1,$cYear);
                $maxday = date("t",$timestamp);
                $thismonth = getdate ($timestamp);
                $startday = $thismonth['wday'];
                $PDate = '';
                $PDate = sprintf("%02d-%02d-%04d", $cMonth, 1, $cYear);                    
                require_once (BALPATH . "/calendar.icon.functions.php");
                $jsonObject = predictionIcons ($ApiKey, $PDate, $user_id, $selectedLanguage);
            
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
                                        array_push($PositiveImage, sprintf("<img src=\"%s\" class=\"imgCalenderIcon\" width=\"30\" height=\"30\" alt=\"%s\" onmouseover = 'showToolTipOnCalendarIcons (\"%s\", \"%s\", \"%s\", \"%s\");' onmouseout = 'hideToolTipOnCalendarIcons ();' />",
                                        $Item[$Index]['Icons'],
                                        $Item[$Index]['Title'],
                                        $Item[$Index]['Icons'],
                                        $Item[$Index]['Title'],
                                        $Item[$Index]['TransitTitle'],
                                        $TodaysId));
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
                            $ImgIconTag = sprintf($IconTable, "&nbsp;", ($i - $startday + 1), "&nbsp;");
                        }

                        if(($i % 7) == 0 ) {
                            $calendarResponse .= "<tr  class='caltd-user'>";
                        }

                        if($i < $startday) {
                            $calendarResponse .= "<td class='cal-alltd-user caltd-rgtborder-user'></td>";
                        } else {
                            if(($i % 7) == 6 ) {
                                $calendarResponse .= "<td align='center' valign='middle' class='cal-alltd-user '>";
                                $dateWithLink = $cMonth.'-'.($i - $startday + 1).'-'.$cYear;
                                $dateWithLink1 = '"'.$dateWithLink.'"';
                                $calendarResponse .= "<div class='calendarIconTooltip' id=".$dateWithLink." ref=".$imageCount."></div>";
                                //$calendarResponse .= "<a class='lnkDaily' onclick='button_onClick($dateWithLink1)' id='".$dateWithLink."' href='javascript:void(0);'>";
                                if (validDateForShowPrediction($dateWithLink)) {
                                    $calendarResponse .= "<a class='lnkDaily' onclick='button_onClick($dateWithLink1)' id='".$dateWithLink."' href='javascript:void(0);'>";
                                } else {
                                    $calendarResponse .= "<a class='lnkDaily' id='".$dateWithLink."' href='javascript:void(0);'>";
                                }
                                $calendarResponse .= $ImgIconTag;
                                $calendarResponse .= "</a>";
                                $calendarResponse .= "</td>";
                            } else {
                                $calendarResponse .= "<td align='center' valign='middle' class='cal-alltd-user caltd-rgtborder-user'>";
                                $dateWithLink = $cMonth.'-'.($i - $startday + 1).'-'.$cYear;
                                $dateWithLink1 = '"'.$dateWithLink.'"';
                                $calendarResponse .= "<div class='calendarIconTooltip' id=".$dateWithLink." ref=".$imageCount."></div>";
                                //$calendarResponse .= "<a class='lnkDaily' onclick='button_onClick($dateWithLink1)' id='".$dateWithLink."' href='javascript:void(0);'>";
                                if (validDateForShowPrediction($dateWithLink)) {
                                    $calendarResponse .= "<a class='lnkDaily' onclick='button_onClick($dateWithLink1)' id='".$dateWithLink."' href='javascript:void(0);'>";
                                } else {
                                    $calendarResponse .= "<a class='lnkDaily' id='".$dateWithLink."' href='javascript:void(0);'>";
                                }
                                $calendarResponse .= $ImgIconTag;
                                $calendarResponse .= "</a>";
                                $calendarResponse .= "</td>";
                            }
                        }
                    }
                    if(($i % 7) == 6 ) {
                        $calendarResponse .= "</tr>";
                    }
                }
                $calendarResponse .= "</table></div>";
            }
            if (isset($eighth_month) && !empty($eighth_month)) {
                $calendarResponse .= "<div id='eighth_month' class='tabify04-content' ref='8' style='display: none;'>";
                $cMonth = $cMonth + 1;
                
                $prev_year = $cYear;
                $next_year = $cYear;
                $prev_month = $cMonth-1;
                $next_month = $cMonth+1;

                if ($prev_month == 0 ) {
                    $prev_month = 12;
                    $prev_year = $cYear - 1;
                }
                if ($cMonth >= 13 ) {
                    $cMonth = 1;
                    $cYear = $cYear + 1;
                }
                $calendarResponse .= "<table width='100%' border='0' cellspacing='0' cellpadding='0' class='caltable'>
                    <tr class='cal-heading-user'>
                        <td align='center' valign='middle' class='cal-alltd-user'>".$DayNameArray['Sun']."</td>
                        <td align='center' valign='middle' class='cal-alltd-user'>".$DayNameArray['Mon']."</td>
                        <td align='center' valign='middle' class='cal-alltd-user'>".$DayNameArray['Tue']."</td>
                        <td align='center' valign='middle' class='cal-alltd-user'>".$DayNameArray['Wed']."</td>
                        <td align='center' valign='middle' class='cal-alltd-user'>".$DayNameArray['Thu']."</td>
                        <td align='center' valign='middle' class='cal-alltd-user'>".$DayNameArray['Fri']."</td>
                        <td align='center' valign='middle' class='cal-alltd-user'>".$DayNameArray['Sat']."</td>
                    </tr>";
                $timestamp = mktime(0,0,0,$cMonth,1,$cYear);
                $maxday = date("t",$timestamp);
                $thismonth = getdate ($timestamp);
                $startday = $thismonth['wday'];
                $PDate = '';
                $PDate = sprintf("%02d-%02d-%04d", $cMonth, 1, $cYear);                    
                require_once (BALPATH . "/calendar.icon.functions.php");
                $jsonObject = predictionIcons ($ApiKey, $PDate, $user_id, $selectedLanguage);
            
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
                                        array_push($PositiveImage, sprintf("<img src=\"%s\" class=\"imgCalenderIcon\" width=\"30\" height=\"30\" alt=\"%s\" onmouseover = 'showToolTipOnCalendarIcons (\"%s\", \"%s\", \"%s\", \"%s\");' onmouseout = 'hideToolTipOnCalendarIcons ();' />",
                                        $Item[$Index]['Icons'],
                                        $Item[$Index]['Title'],
                                        $Item[$Index]['Icons'],
                                        $Item[$Index]['Title'],
                                        $Item[$Index]['TransitTitle'],
                                        $TodaysId));
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
                            $ImgIconTag = sprintf($IconTable, "&nbsp;", ($i - $startday + 1), "&nbsp;");
                        }

                        if(($i % 7) == 0 ) {
                            $calendarResponse .= "<tr  class='caltd-user'>";
                        }

                        if($i < $startday) {
                            $calendarResponse .= "<td class='cal-alltd-user caltd-rgtborder-user'></td>";
                        } else {
                            if(($i % 7) == 6 ) {
                                $calendarResponse .= "<td align='center' valign='middle' class='cal-alltd-user '>";
                                $dateWithLink = $cMonth.'-'.($i - $startday + 1).'-'.$cYear;
                                $dateWithLink1 = '"'.$dateWithLink.'"';
                                $calendarResponse .= "<div class='calendarIconTooltip' id=".$dateWithLink." ref=".$imageCount."></div>";
                                //$calendarResponse .= "<a class='lnkDaily' onclick='button_onClick($dateWithLink1)' id='".$dateWithLink."' href='javascript:void(0);'>";
                                if (validDateForShowPrediction($dateWithLink)) {
                                    $calendarResponse .= "<a class='lnkDaily' onclick='button_onClick($dateWithLink1)' id='".$dateWithLink."' href='javascript:void(0);'>";
                                } else {
                                    $calendarResponse .= "<a class='lnkDaily' id='".$dateWithLink."' href='javascript:void(0);'>";
                                }
                                $calendarResponse .= $ImgIconTag;
                                $calendarResponse .= "</a>";
                                $calendarResponse .= "</td>";
                            } else {
                                $calendarResponse .= "<td align='center' valign='middle' class='cal-alltd-user caltd-rgtborder-user'>";
                                $dateWithLink = $cMonth.'-'.($i - $startday + 1).'-'.$cYear;
                                $dateWithLink1 = '"'.$dateWithLink.'"';
                                $calendarResponse .= "<div class='calendarIconTooltip' id=".$dateWithLink." ref=".$imageCount."></div>";
                                //$calendarResponse .= "<a class='lnkDaily' onclick='button_onClick($dateWithLink1)' id='".$dateWithLink."' href='javascript:void(0);'>";
                                if (validDateForShowPrediction($dateWithLink)) {
                                    $calendarResponse .= "<a class='lnkDaily' onclick='button_onClick($dateWithLink1)' id='".$dateWithLink."' href='javascript:void(0);'>";
                                } else {
                                    $calendarResponse .= "<a class='lnkDaily' id='".$dateWithLink."' href='javascript:void(0);'>";
                                }
                                $calendarResponse .= $ImgIconTag;
                                $calendarResponse .= "</a>";
                                $calendarResponse .= "</td>";
                            }
                        }
                    }
                    if(($i % 7) == 6 ) {
                        $calendarResponse .= "</tr>";
                    }
                }
                $calendarResponse .= "</table></div>";
            }
            if (isset($nineth_month) && !empty($nineth_month)) {
                $calendarResponse .= "<div id='nineth_month' class='tabify04-content' ref='9' style='display: none;'>";
                $cMonth = $cMonth + 1;
                
                $prev_year = $cYear;
                $next_year = $cYear;
                $prev_month = $cMonth-1;
                $next_month = $cMonth+1;

                if ($prev_month == 0 ) {
                    $prev_month = 12;
                    $prev_year = $cYear - 1;
                }
                if ($cMonth >= 13 ) {
                    $cMonth = 1;
                    $cYear = $cYear + 1;
                }
                $calendarResponse .= "<table width='100%' border='0' cellspacing='0' cellpadding='0' class='caltable'>
                    <tr class='cal-heading-user'>
                        <td align='center' valign='middle' class='cal-alltd-user'>".$DayNameArray['Sun']."</td>
                        <td align='center' valign='middle' class='cal-alltd-user'>".$DayNameArray['Mon']."</td>
                        <td align='center' valign='middle' class='cal-alltd-user'>".$DayNameArray['Tue']."</td>
                        <td align='center' valign='middle' class='cal-alltd-user'>".$DayNameArray['Wed']."</td>
                        <td align='center' valign='middle' class='cal-alltd-user'>".$DayNameArray['Thu']."</td>
                        <td align='center' valign='middle' class='cal-alltd-user'>".$DayNameArray['Fri']."</td>
                        <td align='center' valign='middle' class='cal-alltd-user'>".$DayNameArray['Sat']."</td>
                    </tr>";
                $timestamp = mktime(0,0,0,$cMonth,1,$cYear);
                $maxday = date("t",$timestamp);
                $thismonth = getdate ($timestamp);
                $startday = $thismonth['wday'];
                $PDate = '';
                $PDate = sprintf("%02d-%02d-%04d", $cMonth, 1, $cYear);                    
                require_once (BALPATH . "/calendar.icon.functions.php");
                $jsonObject = predictionIcons ($ApiKey, $PDate, $user_id, $selectedLanguage);
            
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
                                        array_push($PositiveImage, sprintf("<img src=\"%s\" class=\"imgCalenderIcon\" width=\"30\" height=\"30\" alt=\"%s\" onmouseover = 'showToolTipOnCalendarIcons (\"%s\", \"%s\", \"%s\", \"%s\");' onmouseout = 'hideToolTipOnCalendarIcons ();' />",
                                        $Item[$Index]['Icons'],
                                        $Item[$Index]['Title'],
                                        $Item[$Index]['Icons'],
                                        $Item[$Index]['Title'],
                                        $Item[$Index]['TransitTitle'],
                                        $TodaysId));
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
                            $ImgIconTag = sprintf($IconTable, "&nbsp;", ($i - $startday + 1), "&nbsp;");
                        }

                        if(($i % 7) == 0 ) {
                            $calendarResponse .= "<tr  class='caltd-user'>";
                        }

                        if($i < $startday) {
                            $calendarResponse .= "<td class='cal-alltd-user caltd-rgtborder-user'></td>";
                        } else {
                            if(($i % 7) == 6 ) {
                                $calendarResponse .= "<td align='center' valign='middle' class='cal-alltd-user '>";
                                $dateWithLink = $cMonth.'-'.($i - $startday + 1).'-'.$cYear;
                                $dateWithLink1 = '"'.$dateWithLink.'"';
                                $calendarResponse .= "<div class='calendarIconTooltip' id=".$dateWithLink." ref=".$imageCount."></div>";
                                //$calendarResponse .= "<a class='lnkDaily' onclick='button_onClick($dateWithLink1)' id='".$dateWithLink."' href='javascript:void(0);'>";
                                if (validDateForShowPrediction($dateWithLink)) {
                                    $calendarResponse .= "<a class='lnkDaily' onclick='button_onClick($dateWithLink1)' id='".$dateWithLink."' href='javascript:void(0);'>";
                                } else {
                                    $calendarResponse .= "<a class='lnkDaily' id='".$dateWithLink."' href='javascript:void(0);'>";
                                }
                                $calendarResponse .= $ImgIconTag;
                                $calendarResponse .= "</a>";
                                $calendarResponse .= "</td>";
                            } else {
                                $calendarResponse .= "<td align='center' valign='middle' class='cal-alltd-user caltd-rgtborder-user'>";
                                $dateWithLink = $cMonth.'-'.($i - $startday + 1).'-'.$cYear;
                                $dateWithLink1 = '"'.$dateWithLink.'"';
                                $calendarResponse .= "<div class='calendarIconTooltip' id=".$dateWithLink." ref=".$imageCount."></div>";
                                //$calendarResponse .= "<a class='lnkDaily' onclick='button_onClick($dateWithLink1)' id='".$dateWithLink."' href='javascript:void(0);'>";
                                if (validDateForShowPrediction($dateWithLink)) {
                                    $calendarResponse .= "<a class='lnkDaily' onclick='button_onClick($dateWithLink1)' id='".$dateWithLink."' href='javascript:void(0);'>";
                                } else {
                                    $calendarResponse .= "<a class='lnkDaily' id='".$dateWithLink."' href='javascript:void(0);'>";
                                }
                                $calendarResponse .= $ImgIconTag;
                                $calendarResponse .= "</a>";
                                $calendarResponse .= "</td>";
                            }
                        }
                    }
                    if(($i % 7) == 6 ) {
                        $calendarResponse .= "</tr>";
                    }
                }
                $calendarResponse .= "</table></div>";
            }
            if (isset($tenth_month) && !empty($tenth_month)) {
                $calendarResponse .= "<div id='tenth_month' class='tabify04-content' ref='10' style='display: none;'>";
                $cMonth = $cMonth + 1;
                
                $prev_year = $cYear;
                $next_year = $cYear;
                $prev_month = $cMonth-1;
                $next_month = $cMonth+1;

                if ($prev_month == 0 ) {
                    $prev_month = 12;
                    $prev_year = $cYear - 1;
                }
                if ($cMonth >= 13 ) {
                    $cMonth = 1;
                    $cYear = $cYear + 1;
                }
                $calendarResponse .= "<table width='100%' border='0' cellspacing='0' cellpadding='0' class='caltable'>
                    <tr class='cal-heading-user'>
                        <td align='center' valign='middle' class='cal-alltd-user'>".$DayNameArray['Sun']."</td>
                        <td align='center' valign='middle' class='cal-alltd-user'>".$DayNameArray['Mon']."</td>
                        <td align='center' valign='middle' class='cal-alltd-user'>".$DayNameArray['Tue']."</td>
                        <td align='center' valign='middle' class='cal-alltd-user'>".$DayNameArray['Wed']."</td>
                        <td align='center' valign='middle' class='cal-alltd-user'>".$DayNameArray['Thu']."</td>
                        <td align='center' valign='middle' class='cal-alltd-user'>".$DayNameArray['Fri']."</td>
                        <td align='center' valign='middle' class='cal-alltd-user'>".$DayNameArray['Sat']."</td>
                    </tr>";
                $timestamp = mktime(0,0,0,$cMonth,1,$cYear);
                $maxday = date("t",$timestamp);
                $thismonth = getdate ($timestamp);
                $startday = $thismonth['wday'];
                $PDate = '';
                $PDate = sprintf("%02d-%02d-%04d", $cMonth, 1, $cYear);                    
                require_once (BALPATH . "/calendar.icon.functions.php");
                $jsonObject = predictionIcons ($ApiKey, $PDate, $user_id, $selectedLanguage);
            
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
                                        array_push($PositiveImage, sprintf("<img src=\"%s\" class=\"imgCalenderIcon\" width=\"30\" height=\"30\" alt=\"%s\" onmouseover = 'showToolTipOnCalendarIcons (\"%s\", \"%s\", \"%s\", \"%s\");' onmouseout = 'hideToolTipOnCalendarIcons ();' />",
                                        $Item[$Index]['Icons'],
                                        $Item[$Index]['Title'],
                                        $Item[$Index]['Icons'],
                                        $Item[$Index]['Title'],
                                        $Item[$Index]['TransitTitle'],
                                        $TodaysId));
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
                            $ImgIconTag = sprintf($IconTable, "&nbsp;", ($i - $startday + 1), "&nbsp;");
                        }

                        if(($i % 7) == 0 ) {
                            $calendarResponse .= "<tr  class='caltd-user'>";
                        }

                        if($i < $startday) {
                            $calendarResponse .= "<td class='cal-alltd-user caltd-rgtborder-user'></td>";
                        } else {
                            if(($i % 7) == 6 ) {
                                $calendarResponse .= "<td align='center' valign='middle' class='cal-alltd-user '>";
                                $dateWithLink = $cMonth.'-'.($i - $startday + 1).'-'.$cYear;
                                $dateWithLink1 = '"'.$dateWithLink.'"';
                                $calendarResponse .= "<div class='calendarIconTooltip' id=".$dateWithLink." ref=".$imageCount."></div>";
                                //$calendarResponse .= "<a class='lnkDaily' onclick='button_onClick($dateWithLink1)' id='".$dateWithLink."' href='javascript:void(0);'>";
                                if (validDateForShowPrediction($dateWithLink)) {
                                    $calendarResponse .= "<a class='lnkDaily' onclick='button_onClick($dateWithLink1)' id='".$dateWithLink."' href='javascript:void(0);'>";
                                } else {
                                    $calendarResponse .= "<a class='lnkDaily' id='".$dateWithLink."' href='javascript:void(0);'>";
                                }
                                $calendarResponse .= $ImgIconTag;
                                $calendarResponse .= "</a>";
                                $calendarResponse .= "</td>";
                            } else {
                                $calendarResponse .= "<td align='center' valign='middle' class='cal-alltd-user caltd-rgtborder-user'>";
                                $dateWithLink = $cMonth.'-'.($i - $startday + 1).'-'.$cYear;
                                $dateWithLink1 = '"'.$dateWithLink.'"';
                                $calendarResponse .= "<div class='calendarIconTooltip' id=".$dateWithLink." ref=".$imageCount."></div>";
                                //$calendarResponse .= "<a class='lnkDaily' onclick='button_onClick($dateWithLink1)' id='".$dateWithLink."' href='javascript:void(0);'>";
                                if (validDateForShowPrediction($dateWithLink)) {
                                    $calendarResponse .= "<a class='lnkDaily' onclick='button_onClick($dateWithLink1)' id='".$dateWithLink."' href='javascript:void(0);'>";
                                } else {
                                    $calendarResponse .= "<a class='lnkDaily' id='".$dateWithLink."' href='javascript:void(0);'>";
                                }
                                $calendarResponse .= $ImgIconTag;
                                $calendarResponse .= "</a>";
                                $calendarResponse .= "</td>";
                            }
                        }
                    }
                    if(($i % 7) == 6 ) {
                        $calendarResponse .= "</tr>";
                    }
                }
                $calendarResponse .= "</table></div>";
            }
            if (isset($eleventh_month) && !empty($eleventh_month)) {
                $calendarResponse .= "<div id='eleventh_month' class='tabify04-content' ref='11' style='display: none;'>";
                $cMonth = $cMonth + 1;
                
                $prev_year = $cYear;
                $next_year = $cYear;
                $prev_month = $cMonth-1;
                $next_month = $cMonth+1;

                if ($prev_month == 0 ) {
                    $prev_month = 12;
                    $prev_year = $cYear - 1;
                }
                if ($cMonth >= 13 ) {
                    $cMonth = 1;
                    $cYear = $cYear + 1;
                }
                $calendarResponse .= "<table width='100%' border='0' cellspacing='0' cellpadding='0' class='caltable'>
                    <tr class='cal-heading-user'>
                        <td align='center' valign='middle' class='cal-alltd-user'>".$DayNameArray['Sun']."</td>
                        <td align='center' valign='middle' class='cal-alltd-user'>".$DayNameArray['Mon']."</td>
                        <td align='center' valign='middle' class='cal-alltd-user'>".$DayNameArray['Tue']."</td>
                        <td align='center' valign='middle' class='cal-alltd-user'>".$DayNameArray['Wed']."</td>
                        <td align='center' valign='middle' class='cal-alltd-user'>".$DayNameArray['Thu']."</td>
                        <td align='center' valign='middle' class='cal-alltd-user'>".$DayNameArray['Fri']."</td>
                        <td align='center' valign='middle' class='cal-alltd-user'>".$DayNameArray['Sat']."</td>
                    </tr>";
                $timestamp = mktime(0,0,0,$cMonth,1,$cYear);
                $maxday = date("t",$timestamp);
                $thismonth = getdate ($timestamp);
                $startday = $thismonth['wday'];
                $PDate = '';
                $PDate = sprintf("%02d-%02d-%04d", $cMonth, 1, $cYear);                    
                require_once (BALPATH . "/calendar.icon.functions.php");
                $jsonObject = predictionIcons ($ApiKey, $PDate, $user_id, $selectedLanguage);
            
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
                                        array_push($PositiveImage, sprintf("<img src=\"%s\" class=\"imgCalenderIcon\" width=\"30\" height=\"30\" alt=\"%s\" onmouseover = 'showToolTipOnCalendarIcons (\"%s\", \"%s\", \"%s\", \"%s\");' onmouseout = 'hideToolTipOnCalendarIcons ();' />",
                                        $Item[$Index]['Icons'],
                                        $Item[$Index]['Title'],
                                        $Item[$Index]['Icons'],
                                        $Item[$Index]['Title'],
                                        $Item[$Index]['TransitTitle'],
                                        $TodaysId));
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
                            $ImgIconTag = sprintf($IconTable, "&nbsp;", ($i - $startday + 1), "&nbsp;");
                        }

                        if(($i % 7) == 0 ) {
                            $calendarResponse .= "<tr  class='caltd-user'>";
                        }

                        if($i < $startday) {
                            $calendarResponse .= "<td class='cal-alltd-user caltd-rgtborder-user'></td>";
                        } else {
                            if(($i % 7) == 6 ) {
                                $calendarResponse .= "<td align='center' valign='middle' class='cal-alltd-user '>";
                                $dateWithLink = $cMonth.'-'.($i - $startday + 1).'-'.$cYear;
                                $dateWithLink1 = '"'.$dateWithLink.'"';
                                $calendarResponse .= "<div class='calendarIconTooltip' id=".$dateWithLink." ref=".$imageCount."></div>";
                                //$calendarResponse .= "<a class='lnkDaily' onclick='button_onClick($dateWithLink1)' id='".$dateWithLink."' href='javascript:void(0);'>";
                                if (validDateForShowPrediction($dateWithLink)) {
                                    $calendarResponse .= "<a class='lnkDaily' onclick='button_onClick($dateWithLink1)' id='".$dateWithLink."' href='javascript:void(0);'>";
                                } else {
                                    $calendarResponse .= "<a class='lnkDaily' id='".$dateWithLink."' href='javascript:void(0);'>";
                                }
                                $calendarResponse .= $ImgIconTag;
                                $calendarResponse .= "</a>";
                                $calendarResponse .= "</td>";
                            } else {
                                $calendarResponse .= "<td align='center' valign='middle' class='cal-alltd-user caltd-rgtborder-user'>";
                                $dateWithLink = $cMonth.'-'.($i - $startday + 1).'-'.$cYear;
                                $dateWithLink1 = '"'.$dateWithLink.'"';
                                $calendarResponse .= "<div class='calendarIconTooltip' id=".$dateWithLink." ref=".$imageCount."></div>";
                                //$calendarResponse .= "<a class='lnkDaily' onclick='button_onClick($dateWithLink1)' id='".$dateWithLink."' href='javascript:void(0);'>";
                                if (validDateForShowPrediction($dateWithLink)) {
                                    $calendarResponse .= "<a class='lnkDaily' onclick='button_onClick($dateWithLink1)' id='".$dateWithLink."' href='javascript:void(0);'>";
                                } else {
                                    $calendarResponse .= "<a class='lnkDaily' id='".$dateWithLink."' href='javascript:void(0);'>";
                                }
                                $calendarResponse .= $ImgIconTag;
                                $calendarResponse .= "</a>";
                                $calendarResponse .= "</td>";
                            }
                        }
                    }
                    if(($i % 7) == 6 ) {
                        $calendarResponse .= "</tr>";
                    }
                }
                $calendarResponse .= "</table></div>";
            }
            if (isset($twelth_month) && !empty($twelth_month)) {
                $calendarResponse .= "<div id='twelth_month' class='tabify04-content' ref='12' style='display: none;'>";
                $cMonth = $cMonth + 1;
                
                $prev_year = $cYear;
                $next_year = $cYear;
                $prev_month = $cMonth-1;
                $next_month = $cMonth+1;

                if ($prev_month == 0 ) {
                    $prev_month = 12;
                    $prev_year = $cYear - 1;
                }
                if ($cMonth >= 13 ) {
                    $cMonth = 1;
                    $cYear = $cYear + 1;
                }
                $calendarResponse .= "<table width='100%' border='0' cellspacing='0' cellpadding='0' class='caltable'>
                    <tr class='cal-heading-user'>
                        <td align='center' valign='middle' class='cal-alltd-user'>".$DayNameArray['Sun']."</td>
                        <td align='center' valign='middle' class='cal-alltd-user'>".$DayNameArray['Mon']."</td>
                        <td align='center' valign='middle' class='cal-alltd-user'>".$DayNameArray['Tue']."</td>
                        <td align='center' valign='middle' class='cal-alltd-user'>".$DayNameArray['Wed']."</td>
                        <td align='center' valign='middle' class='cal-alltd-user'>".$DayNameArray['Thu']."</td>
                        <td align='center' valign='middle' class='cal-alltd-user'>".$DayNameArray['Fri']."</td>
                        <td align='center' valign='middle' class='cal-alltd-user'>".$DayNameArray['Sat']."</td>
                    </tr>";
                $timestamp = mktime(0,0,0,$cMonth,1,$cYear);
                $maxday = date("t",$timestamp);
                $thismonth = getdate ($timestamp);
                $startday = $thismonth['wday'];
                $PDate = '';
                $PDate = sprintf("%02d-%02d-%04d", $cMonth, 1, $cYear);                    
                require_once (BALPATH . "/calendar.icon.functions.php");
                $jsonObject = predictionIcons ($ApiKey, $PDate, $user_id, $selectedLanguage);
            
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
                                        array_push($PositiveImage, sprintf("<img src=\"%s\" class=\"imgCalenderIcon\" width=\"30\" height=\"30\" alt=\"%s\" onmouseover = 'showToolTipOnCalendarIcons (\"%s\", \"%s\", \"%s\", \"%s\");' onmouseout = 'hideToolTipOnCalendarIcons ();' />",
                                        $Item[$Index]['Icons'],
                                        $Item[$Index]['Title'],
                                        $Item[$Index]['Icons'],
                                        $Item[$Index]['Title'],
                                        $Item[$Index]['TransitTitle'],
                                        $TodaysId));
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
                            $ImgIconTag = sprintf($IconTable, "&nbsp;", ($i - $startday + 1), "&nbsp;");
                        }

                        if(($i % 7) == 0 ) {
                            $calendarResponse .= "<tr  class='caltd-user'>";
                        }

                        if($i < $startday) {
                            $calendarResponse .= "<td class='cal-alltd-user caltd-rgtborder-user'></td>";
                        } else {
                            if(($i % 7) == 6 ) {
                                $calendarResponse .= "<td align='center' valign='middle' class='cal-alltd-user '>";
                                $dateWithLink = $cMonth.'-'.($i - $startday + 1).'-'.$cYear;
                                $dateWithLink1 = '"'.$dateWithLink.'"';
                                $calendarResponse .= "<div class='calendarIconTooltip' id=".$dateWithLink." ref=".$imageCount."></div>";
                                //$calendarResponse .= "<a class='lnkDaily' onclick='button_onClick($dateWithLink1)' id='".$dateWithLink."' href='javascript:void(0);'>";
                                if (validDateForShowPrediction($dateWithLink)) {
                                    $calendarResponse .= "<a class='lnkDaily' onclick='button_onClick($dateWithLink1)' id='".$dateWithLink."' href='javascript:void(0);'>";
                                } else {
                                    $calendarResponse .= "<a class='lnkDaily' id='".$dateWithLink."' href='javascript:void(0);'>";
                                }
                                $calendarResponse .= $ImgIconTag;
                                $calendarResponse .= "</a>";
                                $calendarResponse .= "</td>";
                            } else {
                                $calendarResponse .= "<td align='center' valign='middle' class='cal-alltd-user caltd-rgtborder-user'>";
                                $dateWithLink = $cMonth.'-'.($i - $startday + 1).'-'.$cYear;
                                $dateWithLink1 = '"'.$dateWithLink.'"';
                                $calendarResponse .= "<div class='calendarIconTooltip' id=".$dateWithLink." ref=".$imageCount."></div>";
                                //$calendarResponse .= "<a class='lnkDaily' onclick='button_onClick($dateWithLink1)' id='".$dateWithLink."' href='javascript:void(0);'>";
                                if (validDateForShowPrediction($dateWithLink)) {
                                    $calendarResponse .= "<a class='lnkDaily' onclick='button_onClick($dateWithLink1)' id='".$dateWithLink."' href='javascript:void(0);'>";
                                } else {
                                    $calendarResponse .= "<a class='lnkDaily' id='".$dateWithLink."' href='javascript:void(0);'>";
                                }
                                $calendarResponse .= $ImgIconTag;
                                $calendarResponse .= "</a>";
                                $calendarResponse .= "</td>";
                            }
                        }
                    }
                    if(($i % 7) == 6 ) {
                        $calendarResponse .= "</tr>";
                    }
                }
                $calendarResponse .= "</table></div>";
            }

            if (isset($thirteenth_month) && !empty($thirteenth_month)) {
                $calendarResponse .= "<div id='thirteenth_month' class='tabify04-content' ref='13' style='display: none;'>";
                $cMonth = $cMonth + 1;
                
                $prev_year = $cYear;
                $next_year = $cYear;
                $prev_month = $cMonth-1;
                $next_month = $cMonth+1;

                if ($prev_month == 0 ) {
                    $prev_month = 12;
                    $prev_year = $cYear - 1;
                }
                if ($cMonth >= 13 ) {
                    $cMonth = 1;
                    $cYear = $cYear + 1;
                }
                $calendarResponse .= "<table width='100%' border='0' cellspacing='0' cellpadding='0' class='caltable'>
                    <tr class='cal-heading-user'>
                        <td align='center' valign='middle' class='cal-alltd-user'>".$DayNameArray['Sun']."</td>
                        <td align='center' valign='middle' class='cal-alltd-user'>".$DayNameArray['Mon']."</td>
                        <td align='center' valign='middle' class='cal-alltd-user'>".$DayNameArray['Tue']."</td>
                        <td align='center' valign='middle' class='cal-alltd-user'>".$DayNameArray['Wed']."</td>
                        <td align='center' valign='middle' class='cal-alltd-user'>".$DayNameArray['Thu']."</td>
                        <td align='center' valign='middle' class='cal-alltd-user'>".$DayNameArray['Fri']."</td>
                        <td align='center' valign='middle' class='cal-alltd-user'>".$DayNameArray['Sat']."</td>
                    </tr>";
                $timestamp = mktime(0,0,0,$cMonth,1,$cYear);
                $maxday = date("t",$timestamp);
                $thismonth = getdate ($timestamp);
                $startday = $thismonth['wday'];
                $PDate = '';
                $PDate = sprintf("%02d-%02d-%04d", $cMonth, 1, $cYear);                    
                require_once (BALPATH . "/calendar.icon.functions.php");
                $jsonObject = predictionIcons ($ApiKey, $PDate, $user_id, $selectedLanguage);
            
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
                                        array_push($PositiveImage, sprintf("<img src=\"%s\" class=\"imgCalenderIcon\" width=\"30\" height=\"30\" alt=\"%s\" onmouseover = 'showToolTipOnCalendarIcons (\"%s\", \"%s\", \"%s\", \"%s\");' onmouseout = 'hideToolTipOnCalendarIcons ();' />",
                                        $Item[$Index]['Icons'],
                                        $Item[$Index]['Title'],
                                        $Item[$Index]['Icons'],
                                        $Item[$Index]['Title'],
                                        $Item[$Index]['TransitTitle'],
                                        $TodaysId));
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
                            $ImgIconTag = sprintf($IconTable, "&nbsp;", ($i - $startday + 1), "&nbsp;");
                        }

                        if(($i % 7) == 0 ) {
                            $calendarResponse .= "<tr  class='caltd-user'>";
                        }

                        if($i < $startday) {
                            $calendarResponse .= "<td class='cal-alltd-user caltd-rgtborder-user'></td>";
                        } else {
                            if(($i % 7) == 6 ) {
                                $calendarResponse .= "<td align='center' valign='middle' class='cal-alltd-user '>";
                                $dateWithLink = $cMonth.'-'.($i - $startday + 1).'-'.$cYear;
                                $dateWithLink1 = '"'.$dateWithLink.'"';
                                $calendarResponse .= "<div class='calendarIconTooltip' id=".$dateWithLink." ref=".$imageCount."></div>";
                                //$calendarResponse .= "<a class='lnkDaily' onclick='button_onClick($dateWithLink1)' id='".$dateWithLink."' href='javascript:void(0);'>";
                                if (validDateForShowPrediction($dateWithLink)) {
                                    $calendarResponse .= "<a class='lnkDaily' onclick='button_onClick($dateWithLink1)' id='".$dateWithLink."' href='javascript:void(0);'>";
                                } else {
                                    $calendarResponse .= "<a class='lnkDaily' id='".$dateWithLink."' href='javascript:void(0);'>";
                                }
                                $calendarResponse .= $ImgIconTag;
                                $calendarResponse .= "</a>";
                                $calendarResponse .= "</td>";
                            } else {
                                $calendarResponse .= "<td align='center' valign='middle' class='cal-alltd-user caltd-rgtborder-user'>";
                                $dateWithLink = $cMonth.'-'.($i - $startday + 1).'-'.$cYear;
                                $dateWithLink1 = '"'.$dateWithLink.'"';
                                $calendarResponse .= "<div class='calendarIconTooltip' id=".$dateWithLink." ref=".$imageCount."></div>";
                                //$calendarResponse .= "<a class='lnkDaily' onclick='button_onClick($dateWithLink1)' id='".$dateWithLink."' href='javascript:void(0);'>";
                                if (validDateForShowPrediction($dateWithLink)) {
                                    $calendarResponse .= "<a class='lnkDaily' onclick='button_onClick($dateWithLink1)' id='".$dateWithLink."' href='javascript:void(0);'>";
                                } else {
                                    $calendarResponse .= "<a class='lnkDaily' id='".$dateWithLink."' href='javascript:void(0);'>";
                                }
                                $calendarResponse .= $ImgIconTag;
                                $calendarResponse .= "</a>";
                                $calendarResponse .= "</td>";
                            }
                        }
                    }
                    if(($i % 7) == 6 ) {
                        $calendarResponse .= "</tr>";
                    }
                }
                $calendarResponse .= "</table></div>";
            }
    		$calendarResponse .= "</div>
        	<div id='tab-content-container'>        
            <div id='dvResult'>
            </div>
            </div>";






        $calendarResponse .= "<form id='frmDailyPersonal' action=".BASEURL."'/bin/daily-personal/generate.daily.personal.php' method='POST'>
            <input type='hidden' id='apikey' value=".md5('astrowow.com')." />
            <input type='hidden' id='userid' value=".$person_id." />
            <input type='hidden' id='pDate' value='' />
        </form>
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

?>