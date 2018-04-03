<?php
date_default_timezone_set ( 'America/Los_Angeles' );

//define('ROOTPATH', '/var/www/vhosts/world-of-wisdom.com/astrowow.com/');
define('ROOTPATH', '/home/astrowow/public_html/');
//define('ROOTPATH', '/var/www/astrowow/');
if (!defined('BASEURL')) {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
    define("BASEURL", $protocol . $_SERVER['SERVER_NAME']. "/");
}
//define('BASEURL', 'http://www.astrowow.com/');

require_once(ROOTPATH .'cron/config.php');
echo '===  QUESTION Cron runs on . [ ' . date('l jS \of F Y h:i:s A').' ] === ';

//Fetching Open Quetion(s). New arrived question(s)
$obj = new cDatabase();
$sql = " SELECT question_id,language  FROM `questions` ";
$where = " WHERE status ='".OPENED_QUESTIONS."'";
$sql = $sql.$where;

$query = $obj->db->query($sql);
$questions= $query->rows;

echo "<pre>=== QUERY (". $sql .") ===</pre>";
echo "<pre>=== TOTAL NUNBER OF QUESTIONS (". count($questions) .") ===</pre>";

//*********************************************************//
//Fecthing total Astrologer language wise

$sql = "SELECT a.`astrologer_id`, a.`username`, a.`status` , language_id 
        FROM `astrologer` a left join
             astrologer_language al on al.astrologer_id = a.astrologer_id ";
$where = " WHERE a.status ='1'";
$sql = $sql.$where;


$query = $obj->db->query($sql);
$astrolger = $query->rows;
echo "<pre>=== QUERY (". $sql .") ===</pre>";
echo "<pre>=== TOTAL NUNBER OF Astrologers (". count($astrolger) .") ===</pre>";

//*********************************************************//
//Fecthing total language

$sql = " SELECT * FROM `report_languages` ";
$where = " ";
$sql = $sql.$where;
$query = $obj->db->query($sql);
$languages= $query->rows;
echo "<pre>=== QUERY (". $sql .") ===</pre>";
echo "<pre>=== TOTAL NUNBER OF LANGUAGE (". count($languages) .") ===</pre>";

for($i=0;$i<count($languages);$i++) {
    unset($obj);
    $obj = new cDatabase();
    $sql = " SELECT question_id,language  FROM `questions` ";
    $where = " WHERE status ='".OPENED_QUESTIONS."' and language='".$languages[$i]['report_language_id']."'";
    $sql = $sql.$where;
    $query = $obj->db->query($sql);
    $questions= $query->rows;
//    echo "<pre>=== QUERY (". $sql .") ===</pre>";
//    echo "<pre>=== TOTAL OPEN QUESTIONS (". count($questions) .") ===</pre>";
    echo "\\n==== Language Name :: " . $languages[$i]['name'] . " ===== \\n";
    for($j=0;$j<count($questions);$j++) {
        unset($obj);
        $obj = new cDatabase();
        $sql = " SELECT track_id, 	currently_assigned_astrolger ,	language_id ,	total_user  FROM `assiged_questions_track` ";
        $where = " WHERE language_id='".$languages[$i]['report_language_id']."'";
        $sql = $sql.$where;
        $query = $obj->db->query($sql);
        $assiged_questions_track= $query->rows;

        $astrologer_id = 0;
        $astrologer_email = '';
        $track_id = 0;

        for($k=0;$k<count($assiged_questions_track);$k++) {
            $track_id = $assiged_questions_track[$k]['track_id'];

            $sql = " SELECT a.`astrologer_id`, a.`username`, a.`status` , language_id
                     FROM `astrologer` a left join astrologer_language al on al.astrologer_id = a.astrologer_id ";
            $where = " WHERE a.status ='1' and language_id=".$languages[$i]['report_language_id']."
			and a.`astrologer_id` > ".$assiged_questions_track[$k]['currently_assigned_astrolger']." limit 0,1";
            
            $sql = $sql.$where;
            $query = $obj->db->query($sql);
            $astrolger= $query->rows;

            if(count($astrolger)>0) {
                $astrologer_id = $astrolger[0]['astrologer_id'];
                $astrologer_email = $astrolger[0]['username'];
            }
            else {
                $sql = " SELECT a.`astrologer_id`, a.`username`, a.`status` , language_id
                        FROM `astrologer` a left join astrologer_language al on al.astrologer_id = a.astrologer_id ";
                $where = " WHERE a.status ='1' and language_id=".$languages[$i]['report_language_id']." order by a.astrologer_id asc limit 0,1";

                $sql = $sql.$where;
                $query = $obj->db->query($sql);
                $astrolger= $query->rows;                
                if(count($astrolger)>0) {
                    $astrologer_id 	= $astrolger[0]['astrologer_id'];
                    $astrologer_email 	= $astrolger[0]['username'];
                }
            }
        }

        if($astrologer_id > 0) {
            $sql = "insert into question_to_astrologer set question_id = '".$questions[$j]['question_id']."', astrologer_id = '".$astrologer_id."' ";
            $query = $obj->db->query($sql);
            //$assiged_questions_track= $query->rows;

            $sql = " update assiged_questions_track set currently_assigned_astrolger =".$astrologer_id;
            $where = " WHERE track_id='".$track_id."'";
            $sql = $sql.$where;
            $query = $obj->db->query($sql);
            //$assiged_questions_track= $query->rows;

            $sql = " update `questions`  set status = 3 ";
            $where = " WHERE question_id =".$questions[$j]['question_id'];
            $sql = $sql.$where;
            $query = $obj->db->query($sql);

            $AssignTime = time();
            $sql = "UPDATE `question_time` SET assign_time = ". $AssignTime ;
            $where = " WHERE question_id =".$questions[$j]['question_id'];
            $sql = $sql.$where;
            $query = $obj->db->query($sql);

            $sql = "SELECT * FROM `question_time` " ;
            $where = " WHERE question_id =".$questions[$j]['question_id'];
            $sql = $sql.$where;
            $query = $obj->db->query($sql);
            $QuestionTime = $query->rows;
            $QuestionPriority = $QuestionTime[0]["question_priority"];

            if($QuestionPriority == 1) {
                $PRIORITY = "High Priority";
                $BookingHours = 60 * 60 * 6;
                $RemainingHours = 60 * 60 * 18;
                $AssignTime = time();
                $BookingTime = time() + $BookingHours;
                $RemainingTime = time() + $RemainingHours;
                $FinalBookingTime = time() + $BookingHours - $BookingHours;
                $FinalRemainingTime = $RemainingTime - $BookingTime;
            }
            else {
                $PRIORITY = "Normal Priority";
                $BookingHours = 60 * 60 * 12;
                $RemainingHours = 60 * 60 * 48;
                $AssignTime = time();
                $BookingTime = time() + $BookingHours;
                $RemainingTime = time() + $RemainingHours;
                $FinalBookingTime = time() + $BookingHours - $BookingHours;
                $FinalRemainingTime = $RemainingTime - $BookingTime;
            }

            //FETCHING ASTROLOGER NAME
            $sql = " SELECT ap.`first_name` FROM `astrologer_profile` ap ";
            $where = " WHERE astrologer_id=".$astrologer_id." LIMIT 0,1";

            $sql = $sql.$where;
            $query = $obj->db->query($sql);
            $astrologerProfile = $query->rows;

            /**
             * @todo: Language code id not available in "Report_Language" Table need to cross verify with language table.
             */
            $emailData = array('sitelink' => "http://www.astrowow.com/",
                          'name' => $astrologerProfile[0]['first_name'],
                          'PRIORITY' => $PRIORITY,
                          //'BOOKTIME' => date("F j, Y, h:i:s T", $BookingTime),
                          'BOOKTIME' => date("h:i:s", strtotime( $FinalBookingTime )) ,
                          'REMAININGTIME' => date("h:i:s",  $FinalRemainingTime),
                          'locale' => 'en-US',
                          //'language_id' => $languages[$i]['report_language_id'],
                          'language_id' => 'en',
                          'subject' => "New Question Assign to you",
                          'type' => "html");
            
            $SendTo = array(
                $astrologer_email => $astrologerProfile[0]['first_name']
                //"parmaramit1111@gmail.com" =>  $astrologerProfile[0]['first_name']
            );

            if ( genericMail::SendQuestionAllotmentToAstrologer($SendTo, $emailData) ) {
                print "Mail was send successfull ... [ $astrologer_email ($astrologer_id)]<br />\n";
            } else {
                print "Hmm .. there could be a Problem ... [ $astrologer_email  ($astrologer_id) ] <br />\n";
            }
            // send mail to astrologer
//            $subject = 'New Question Assign to you';
//            $to = $astrologer_email;
//            $body =  "New Question Assign to you\n";
            //mail($to, $subject, $body);
            //mail("parmaramit1111@gmail.com", $subject, $body);
            echo '<pre>===  Email is sent ===</pre>';
        }
        else {
            $sql = " update assiged_questions_track set currently_assigned_astrolger =".$astrologer_id;
            $where = " WHERE track_id='".$track_id."'";
            $sql = $sql.$where;
            $query = $obj->db->query($sql);
            //$assiged_questions_track= $query->rows;
        }
    }
}
echo '<pre>=== CRON is END GOLDEN CIRCLE ===</pre>';
?>