<?php
error_reporting ( E_ALL );
date_default_timezone_set ( 'America/Los_Angeles' );
require_once("include.php");

if (!class_exists('GoldenCircleRepository')) {
    require_once(DALPATH."/goldenCircleRepository.php");
}


if (!class_exists('QuestionDTO')) {
    require_once(DTOPATH."/goldenCircleDTO.php");
}


if (!class_exists('orderDTO')) {
    require_once(ROOTPATH."dto/orderDTO.php");
}

if (!class_exists('Order')) {
    require_once(ROOTPATH."bal/order.php");
}



class GoldenCircle {
    public function GetPriceList() {
        $goldenCircleRepository = new GoldenCircleRepository();
        try {
            $result = $goldenCircleRepository->GetPriceList();
            return ($result);

        }
        catch(Exception $ex) {
            die($ex->getMessage());
        }
    }

    public function SaveQuestionDetail($data) {
        $goldenCircleRepository = new GoldenCircleRepository();
        try {
            $result = $goldenCircleRepository->SaveQuestionDetail($data);
            return ($result);

        }
        catch(Exception $ex) {
            die($ex->getMessage());
        }
    }

    public function SaveQuestionUserBirthDetail($data) {
        $goldenCircleRepository = new GoldenCircleRepository();
        try {
            $result = $goldenCircleRepository->SaveQuestionUserBirthDetail($data);
            return ($result);

        }
        catch(Exception $ex) {
            die($ex->getMessage());
        }
    }

    public function SaveQuestionUserHororyDetail($data) {
        $goldenCircleRepository = new GoldenCircleRepository();
        try {
            $result = $goldenCircleRepository->SaveQuestionUserHororyDetail($data);
            return ($result);

        }
        catch(Exception $ex) {
            die($ex->getMessage());
        }
    }

    public function SaveQuestionTimeDetail($question_id,$priority) {
        $goldenCircleRepository = new GoldenCircleRepository();
        try {
            $result = $goldenCircleRepository->SaveQuestionTimeDetail($question_id,$priority);
            return ($result);

        }
        catch(Exception $ex) {
            die($ex->getMessage());
        }
    }

    public function UpdateQuestion($question_id,$status,$order_id) {
        $goldenCircleRepository = new GoldenCircleRepository();
        try {
            $result = $goldenCircleRepository->UpdateQuestion($question_id,$status,$order_id);
            return ($result);

        }
        catch(Exception $ex) {
            die($ex->getMessage());
        }
    }

    public function validateAstrologerLogon($username,$password) {
        $goldenCircleRepository = new GoldenCircleRepository();

        $returnValue = $goldenCircleRepository->validateAstrologerLogon($username,$password);
        //print_r($returnValue);
        if(count($returnValue) > 0) {
            $host  = $_SERVER['HTTP_HOST'];

            setcookie("AstrologerId", $returnValue['astrologer_id'], time()+(60*60*24*30),'/',$host);  /* expire in 1 month */
            //setcookie("UserGroupId", $returnValue['UserGroupId'], time()+(60*60*24*30),'/',	$host);  /* expire in 1 month */
            setcookie("AstrologerEmail", $returnValue['username'], time()+(60*60*24*30),'/',$host);  /* expire in 1 month */

            return true;
        }
        else {
            return false;
        }


    }

    public function UpdateQuestionTime($question_id,$time) {
        $goldenCircleRepository = new GoldenCircleRepository();
        try {
            $result = $goldenCircleRepository->UpdateQuestion($question_id,$time);
            return ($result);

        }
        catch(Exception $ex) {
            die($ex->getMessage());
        }
    }

    public function GetNewQuestions($astrologer_id,$items_per_page = 10,$start = 0) {
        $goldenCircleRepository = new GoldenCircleRepository();
        try {
            $result = $goldenCircleRepository->GetNewQuestions($astrologer_id,$items_per_page,$start);
            if(count($result)>0) {
                for($i=0;$i<count($result);$i++) {
                    $a = $result[$i]['question_answer_time_limit'] + $result[$i]['assign_time'];
                    $result[$i]['question_answer_time_limit'] = date('G:i a', $a);
                    //                                          YYYY/MM/DD hh:mm:ss
                    $result[$i]['remaining _booking_time'] = date('Y/m/d H:i:s', $a);
                    //echo '<br>';
                    $b = $a - time() ;
                    //echo '<br>';
                    //echo time();
                    //echo '<br>';
                    if($a < 0) {
                        $time = '00:00:00';
                        $time1 = date('Y/m/d H:i:s');
                    }
                    else {
                        $hours      = floor($b /3600);
                        $minutes    = intval(($b/60) % 60);
                        $seconds    = intval($b % 60);
                        $time = $hours.':'.$minutes.':'.$seconds;
                        //$time = date('G:i:s', $b+time());

                        //echo date("d-m-y H:i:s ",$b+time());
                        //echo '<br>';
                        //echo date("d-m-y H:i:s ",time());
                        $time1 = date('Y/m/d') .' '.  $time;
                    }
                    $result[$i]['time_remaining'] = $time;
                    $result[$i]['time_remaining_toanswser'] = $time1;
                }
            }
            return ($result);
        }
        catch(Exception $ex) {
            die($ex->getMessage());
        }
    }

    public function GetNewQuestionCountForPaging($astrologer_id) {
        $goldenCircleRepository = new GoldenCircleRepository();
        try {
            $result = $goldenCircleRepository->GetNewQuestionCountForPaging($astrologer_id);
            return ($result);
        }
        catch(Exception $ex) {
            die($ex->getMessage());
        }
    }

    public function GetBlockedQuestions($astrologer_id,$items_per_page = 10,$start = 0) {
        $goldenCircleRepository = new GoldenCircleRepository();
        try {
            $result = $goldenCircleRepository->GetBlockedQuestions($astrologer_id,$items_per_page,$start);
            if(count($result)>0) {
                for($i=0;$i<count($result);$i++) {
                    $a = $result[$i]['question_answer_time_limit'] + $result[$i]['assign_time'];
                    $result[$i]['question_answer_time_limit'] = date('G:i a', $a);
                    $result[$i]['remaining _booking_time'] = date('Y/m/d H:i:s', $a);
                    $b = $a - time() ;

                    if($b < 0) {
                        $time = '00:00:00';
                        $time1 = date('Y/m/d H:i:s');
                    }
                    else {
                        //$time = date('G:i:s', ($b+time()));
                        $hours      = floor($b /3600);
                        $minutes    = intval(($b/60) % 60);
                        $seconds    = intval($b % 60);
                        $time = $hours.':'.$minutes.':'.$seconds;
                        $time1 = date('Y/m/d') .' '.  $time;
                    }
                    $result[$i]['time_remaining'] = $time;
                    $result[$i]['time_remaining_toanswser'] = $time1;
                }
            }
            return ($result);
        }
        catch(Exception $ex) {
            die($ex->getMessage());
        }
    }

    public function GetAnsweredQuestions($astrologer_id,$items_per_page = 10,$start = 0) {
        $goldenCircleRepository = new GoldenCircleRepository();
        try {
            $result = $goldenCircleRepository->GetAnsweredQuestions($astrologer_id,$items_per_page,$start);
            return ($result);
        }
        catch(Exception $ex) {
            die($ex->getMessage());
        }
    }

    public function GetHighPriorityQuestions($astrologer_id,$items_per_page = 10,$start = 0) {
        $goldenCircleRepository = new GoldenCircleRepository();
        try {
            $result = $goldenCircleRepository->GetHighPriorityQuestions($astrologer_id,$items_per_page,$start);
            for($i=0;$i<count($result);$i++) {
                $a = $result[$i]['question_answer_time_limit'] + $result[$i]['assign_time'];
                $b = $a - time() ;

                if($b < 0) {
                    $time = '00:00:00';
                }
                else {
                    //$time = date('G:i:s', $b);
                    $hours      = floor($b /3600);
                    $minutes    = intval(($b/60) % 60);
                    $seconds    = intval($b % 60);
                    $time = $hours.':'.$minutes.':'.$seconds;
                }
                $result[$i]['time_remaining'] = $time;
            }
            return ($result);
        }
        catch(Exception $ex) {
            die($ex->getMessage());
        }
    }

    public function GetLowPriorityQuestions($astrologer_id,$items_per_page = 10,$start = 0) {
        $goldenCircleRepository = new GoldenCircleRepository();
        try {
            $result = $goldenCircleRepository->GetLowPriorityQuestions($astrologer_id,$items_per_page,$start);
            for($i=0;$i<count($result);$i++) {
                $a = $result[$i]['question_answer_time_limit'] + $result[$i]['assign_time'];
                $b = $a - time() ;

                if($b < 0) {
                    $time = '00:00:00';
                }
                else {
                    //$time = date('G:i:s', $b);
                    $hours      = floor($b /3600);
                    $minutes    = intval(($b/60) % 60);
                    $seconds    = intval($b % 60);
                    $time = $hours.':'.$minutes.':'.$seconds;
                }
                $result[$i]['time_remaining'] = $time;
            }
            return ($result);
        }
        catch(Exception $ex) {
            die($ex->getMessage());
        }
    }

    public function GetMediumPriorityQuestions($astrologer_id,$items_per_page = 10,$start = 0) {
        $goldenCircleRepository = new GoldenCircleRepository();
        try {
            $result = $goldenCircleRepository->GetMediumPriorityQuestions($astrologer_id,$items_per_page,$start);
            for($i=0;$i<count($result);$i++) {
                $a = $result[$i]['question_answer_time_limit'] + $result[$i]['assign_time'];
                $b = $a - time() ;

                if($b < 0) {
                    $time = '00:00:00';
                }
                else {
                    //$time = date('G:i:s', $b);
                    $hours      = floor($b /3600);
                    $minutes    = intval(($b/60) % 60);
                    $seconds    = intval($b % 60);
                    $time = $hours.':'.$minutes.':'.$seconds;
                }
                $result[$i]['time_remaining'] = $time;
            }
            return ($result);
        }
        catch(Exception $ex) {
            die($ex->getMessage());
        }
    }

    public function makeQuestionsBlock($questionIds) {
        $goldenCircleRepository = new GoldenCircleRepository();
        try {
            $result = $goldenCircleRepository->makeQuestionsBlock($questionIds);
            return ($result);
        }
        catch(Exception $ex) {
            die($ex->getMessage());
        }
    }
    public function UpdateStatusVacation($astrologer_id, $status) {
        $goldenCircleRepository = new GoldenCircleRepository();
        try {
            $result = $goldenCircleRepository->UpdateStatusVacation($astrologer_id, $status);
            return ($result);
        }
        catch(Exception $ex) {
            die($ex->getMessage());
        }
    }

    public function GetDetailById($astrologer_id) {
        $goldenCircleRepository = new GoldenCircleRepository();
        try {
            $result = $goldenCircleRepository->GetDetailById($astrologer_id);
            return ($result);
        }
        catch(Exception $ex) {
            die($ex->getMessage());
        }
    }

    public function GetQuestionDetail($question_id) {
        $goldenCircleRepository = new GoldenCircleRepository();
        try {
            $result = $goldenCircleRepository->GetQuestionDetail($question_id);
            if(count($result)>0) {
                for($i=0;$i<count($result);$i++) {
                    $a = $result['Question'][0]['question_answer_time_limit'] + $result['Question'][0]['assign_time'];
                    //echo '<br>';
                    $b = $a - time() ;
                    //echo '<br>';
                    //echo time();
                    //echo '<br>';
                    if($a < 0) {
                        $time = '00:00:00';
                    }
                    else {
                        $hours      = floor($b /3600);
                        $minutes    = intval(($b/60) % 60);
                        $seconds    = intval($b % 60);
                        $time = $hours.':'.$minutes.':'.$seconds;
                        //$time = date('G:i:s', $b+time());

                        //echo date("d-m-y H:i:s ",$b+time());
                        //echo '<br>';
                        //echo date("d-m-y H:i:s ",time());
                    }
                    $result['Question'][0]['time_remaining'] = $time;
                }
            }
            //print_r($result);exit;
            return ($result);
        }
        catch(Exception $ex) {
            die($ex->getMessage());
        }
    }

    public function postAnswer($question_id,$answer,$astrologer_id) {
        $goldenCircleRepository = new GoldenCircleRepository();
        try {
            $result = $goldenCircleRepository->postAnswer($question_id,$answer,$astrologer_id);
            return ($result);
        }
        catch(Exception $ex) {
            die($ex->getMessage());
        }
    }

    public function GetQuestionCount($astrologer_id) {
        $goldenCircleRepository = new GoldenCircleRepository();
        try {
            $result = $goldenCircleRepository->GetQuestionCount($astrologer_id);
            return ($result);
        }
        catch(Exception $ex) {
            die($ex->getMessage());
        }
    }

    public function GetQuestionCountForPaging($astrologer_id) {
        $goldenCircleRepository = new GoldenCircleRepository();
        try {
            $result = $goldenCircleRepository->GetQuestionCountForPaging($astrologer_id);
            return ($result);
        }
        catch(Exception $ex) {
            die($ex->getMessage());
        }
    }

    public function GetQuestions($astrologer_id) {
        $goldenCircleRepository = new GoldenCircleRepository();
        try {
            $result = $goldenCircleRepository->GetQuestions($astrologer_id);
            return ($result);
        }
        catch(Exception $ex) {
            die($ex->getMessage());
        }
    }



    public function GetBlockedQuestionCountForPaging($astrologer_id) {
        $goldenCircleRepository = new GoldenCircleRepository();
        try {
            $result = $goldenCircleRepository->GetBlockedQuestionCountForPaging($astrologer_id);
            return ($result);
        }
        catch(Exception $ex) {
            die($ex->getMessage());
        }
    }

    public function GetAnsweredQuestionCountForPaging($astrologer_id) {
        $goldenCircleRepository = new GoldenCircleRepository();
        try {
            $result = $goldenCircleRepository->GetAnsweredQuestionCountForPaging($astrologer_id);
            return ($result);
        }
        catch(Exception $ex) {
            die($ex->getMessage());
        }
    }

    public function GetHighQuestionCountForPaging($astrologer_id) {
        $goldenCircleRepository = new GoldenCircleRepository();
        try {
            $result = $goldenCircleRepository->GetHighQuestionCountForPaging($astrologer_id);
            return ($result);
        }
        catch(Exception $ex) {
            die($ex->getMessage());
        }
    }

    public function GetMediumQuestionCountForPaging($astrologer_id) {
        $goldenCircleRepository = new GoldenCircleRepository();
        try {
            $result = $goldenCircleRepository->GetMediumQuestionCountForPaging($astrologer_id);
            return ($result);
        }
        catch(Exception $ex) {
            die($ex->getMessage());
        }
    }

    public function GetLowQuestionCountForPaging($astrologer_id) {
        $goldenCircleRepository = new GoldenCircleRepository();
        try {
            $result = $goldenCircleRepository->GetLowQuestionCountForPaging($astrologer_id);
            return ($result);
        }
        catch(Exception $ex) {
            die($ex->getMessage());
        }
    }

    public function GetQuestionListByUserId($user_id,$items_per_page,$start) {
        $goldenCircleRepository = new GoldenCircleRepository();
        try {
            $result = $goldenCircleRepository->GetQuestionListByUserId($user_id,$items_per_page,$start);
            return ($result);
        }
        catch(Exception $ex) {
            die($ex->getMessage());
        }
    }
    public function GetQuestionListCountByUserId($user_id) {
        $goldenCircleRepository = new GoldenCircleRepository();
        try {
            $result = $goldenCircleRepository->GetQuestionListCountByUserId($user_id);
            return ($result);
        }
        catch(Exception $ex) {
            die($ex->getMessage());
        }
    }

    public function CheckPassword($astrologer_id,$password) {
        $goldenCircleRepository = new GoldenCircleRepository();
        try {
            $result = $goldenCircleRepository->CheckPassword($astrologer_id,$password);
            return ($result);
        }
        catch(Exception $ex) {
            die($ex->getMessage());
        }
    }

    public function ChangeAstrologerPassword($astrologer_id,$password) {
        $goldenCircleRepository = new GoldenCircleRepository();
        try {
            $result = $goldenCircleRepository->ChangeAstrologerPassword($astrologer_id,$password);
            return ($result);
        }
        catch(Exception $ex) {
            die($ex->getMessage());
        }
    }

    public function RateAnswer($answer_id,$rate) {
        $goldenCircleRepository = new GoldenCircleRepository();
        try {
            $result = $goldenCircleRepository->RateAnswer($answer_id,$rate);
            return ($result);
        }
        catch(Exception $ex) {
            die($ex->getMessage());
        }
    }
    public function GetQuestionPrice($priority,$currency_id) {
        $goldenCircleRepository = new GoldenCircleRepository();
        try {
            $result = $goldenCircleRepository->GetQuestionPrice($priority,$currency_id);
            return ($result);
        }
        catch(Exception $ex) {
            die($ex->getMessage());
        }
    }
    public function makeQuestionsReject($questionIds,$astrologer_id) {
        $goldenCircleRepository = new GoldenCircleRepository();
        try {
            $result = $goldenCircleRepository->makeQuestionsReject($questionIds,$astrologer_id);
            return ($result);
        }
        catch(Exception $ex) {
            die($ex->getMessage());
        }
    }

    public function GetCommissionMonthWiseByAstrologerId($astrologer_id) {
        $goldenCircleRepository = new GoldenCircleRepository();
        try {
            $result = $goldenCircleRepository->GetCommissionMonthWiseByAstrologerId($astrologer_id);
            return ($result);
        }
        catch(Exception $ex) {
            die($ex->getMessage());
        }
    }

    public function GetCommissionMonthAndYearWiseByAstrologerId($astrologer_id,$year,$month) {
        $goldenCircleRepository = new GoldenCircleRepository();
        try {
            $result = $goldenCircleRepository->GetCommissionMonthAndYearWiseByAstrologerId($astrologer_id,$year,$month);
            return ($result);
        }
        catch(Exception $ex) {
            die($ex->getMessage());
        }
    }

    public function GetAllQuestions($astrologer_id,$items_per_page,$start) {
        $goldenCircleRepository = new GoldenCircleRepository();
        try {
            $result = $goldenCircleRepository->GetAllQuestions($astrologer_id,$items_per_page,$start); {
                for($i=0;$i<count($result);$i++) {
                    $a = $result[$i]['question_answer_time_limit'] + $result[$i]['assign_time'];
                    $result[$i]['question_answer_time_limit'] = date('G:i a', $a);
                    $b = $a - time() ;

                    if($b < 0) {
                        $time = '00:00:00';
                    }
                    else {
                        //$time = date('G:i:s', ($b+time()));
                        $hours      = floor($b /3600);
                        $minutes    = intval(($b/60) % 60);
                        $seconds    = intval($b % 60);
                        $time = $hours.':'.$minutes.':'.$seconds;
                    }
                    $result[$i]['time_remaining'] = $time;
                }
            }
            return ($result);
        }
        catch(Exception $ex) {
            die($ex->getMessage());
        }
    }

    public function GetDetailOfAstrologerById($id) {
        $goldenCircleRepository = new GoldenCircleRepository();
        return ($goldenCircleRepository->GetDetailOfAstrologerById($id));
    }

    public function SaveAstrologerDetail($data) {
        $goldenCircleRepository = new GoldenCircleRepository();
        return ($goldenCircleRepository->SaveAstrologerDetail($data));
    }

    public function SaveAstrologerLanguage($language, $astrologer_id) {
        $goldenCircleRepository = new GoldenCircleRepository();
        return ($goldenCircleRepository->SaveAstrologerLanguage($language, $astrologer_id));
    }
    public function UpdateAlternateEmailAddress($email,$astrologer_id) {
        $goldenCircleRepository = new GoldenCircleRepository();
        return ($goldenCircleRepository->UpdateAlternateEmailAddress($email,$astrologer_id));
    }
    public function UpdateEmergencyContactDetail($data) {
        $goldenCircleRepository = new GoldenCircleRepository();
        return ($goldenCircleRepository->UpdateEmergencyContactDetail($data));
    }

    public function GetRejectedQuestionCount($astrologer_id) {
        $goldenCircleRepository = new GoldenCircleRepository();
        try {
            $result = $goldenCircleRepository->GetRejectedQuestionCount($astrologer_id);
            return ($result);
        }
        catch(Exception $ex) {
            die($ex->getMessage());
        }
    }

    public function GetBirthDetailByQuestionId($QuestionId) {
        $goldenCircleRepository = new GoldenCircleRepository();
        try {
            $result = $goldenCircleRepository->GetBirthDetailByQuestionId($QuestionId);
            return ($result);
        }
        catch(Exception $ex) {
            die($ex->getMessage());
        }
    }

    public function GetHororaryDetailByQuestionId($QuestionId) {
        $goldenCircleRepository = new GoldenCircleRepository();
        try {
            $result = $goldenCircleRepository->GetHororaryDetailByQuestionId($QuestionId);
            return ($result);
        }
        catch(Exception $ex) {
            die($ex->getMessage());
        }
    }

    public  function GetContactModeByAstrologerId($AstrologerId){
        $goldenCircleRepository = new GoldenCircleRepository();
        try {
            $result = $goldenCircleRepository->GetContactModeByForAstrologer($AstrologerId);
            return ($result);
        }
        catch(Exception $ex) {
            die($ex->getMessage());
        }
    }
    
    public function GetCurrencyListByLanguage($LanguageID) {
    	$goldenCircleRepository = new GoldenCircleRepository();
    	try {
    		$result = $goldenCircleRepository->GetCurrencyListByLanguage($LanguageID);
    		return ($result);    
    	}
    	catch(Exception $ex) {
    		die($ex->getMessage());
    	}
    }
}



if(isset($_REQUEST['task'])) {
    if($_REQUEST['task'] == 'AskQuestion') {
        $time = $_REQUEST['current_time'];


        //echo date('Y-m-d H:i:s',$time);
        //exit;

        /*echo '<pre>';
		print_r($_REQUEST);
		echo '</pre>';
		exit;*/

        $objQuestionDTO = new QuestionDTO();
        $objQuestionUserBirthDetailDTO = new QuestionUserBirthDetailDTO();
        $objQuestionHororyDetailDTO = new QuestionHororyDetailDTO();
        $objQuestionTimeDTO = new QuestionTimeDTO();
        //$objAnswerDTO = new AnswerDTO();
        $objQuestion = new GoldenCircle();
        $question_id = 0;


        // SAVE QUESTION DETAIL CODE START HERE

        /*if($_REQUEST['ddAskQuestionFor'] == 1)
		{
			$name = $_REQUEST['txtName'];
		}
		else
		{
			$name = $_REQUEST['txtNameOther'];
		}		*/

        $name = $_REQUEST['txtName'];

        $objQuestionDTO->question_id		='0';
        $objQuestionDTO->question			=$_REQUEST['txtQuestion'];
        $objQuestionDTO->price				=$_REQUEST['question_price'];
        $objQuestionDTO->question_time		=$time;
        $objQuestionDTO->status				='0';
        $objQuestionDTO->is_block			='0';
        $objQuestionDTO->user_id			=$_REQUEST['user_id'];
        $objQuestionDTO->email				=$_REQUEST['UserEmail'];
        $objQuestionDTO->name				=$name;
        $objQuestionDTO->language			=$_REQUEST['ddLanguage'];
        $objQuestionDTO->priority			=$_REQUEST['question_priority'];
        $objQuestionDTO->price_id			=$_REQUEST['price_id'];



        $result = $objQuestion->SaveQuestionDetail($objQuestionDTO);

        if($result > 0) {
            $question_id = $result;
        }
        else {
            $message = "Error occured while saving data";
        }

        // SAVE QUESTION DETAIL CODE ENDS HERE


        // SAVE QUESTION USER BIRTH DETAIL CODE START HERE


        $birthMonth = $_REQUEST['ddMonth'];
        $birthDay = $_REQUEST['ddDay'];

        $objQuestionUserBirthDetailDTO->name			=$_REQUEST['txtName'];
        $objQuestionUserBirthDetailDTO->birth_date		=$_REQUEST['ddYear']."-".$_REQUEST['ddMonth']."-".$_REQUEST['ddDay'];
        if(empty($_REQUEST['birthhour'])) {
            $objQuestionUserBirthDetailDTO->hours		=0;
            $objQuestionUserBirthDetailDTO->minute		=0;
            $objQuestionUserBirthDetailDTO->untimed		=1;
        }
        else {
            $objQuestionUserBirthDetailDTO->hours		=$_REQUEST['birthhour'];
            $objQuestionUserBirthDetailDTO->minute		=$_REQUEST['birthminute'];
            $objQuestionUserBirthDetailDTO->untimed		=0;
        }

        $objQuestionUserBirthDetailDTO->country			=$_REQUEST['ddBirthCountryOther'];
        $objQuestionUserBirthDetailDTO->state			=$_REQUEST['hdnState'];
        $objQuestionUserBirthDetailDTO->city			=$_REQUEST['txtBirthCity'];
        //$objQuestionUserBirthDetailDTO->timezone		=0;
        //$objQuestionUserBirthDetailDTO->wheel_image_id	=$_REQUEST[''];
        $objQuestionUserBirthDetailDTO->longitude		=$_REQUEST['hdnLongitude'];
        $objQuestionUserBirthDetailDTO->latitude		=$_REQUEST['hdnLatitude'];

        $objQuestionUserBirthDetailDTO->country_name		=$_REQUEST['birth_country'];
        $objQuestionUserBirthDetailDTO->zoneRef				='';
        $objQuestionUserBirthDetailDTO->summerTimeZoneRef	='';


        $sunsign =CalculateSunsignFromDate($birthMonth,$birthDay);
        $objQuestionUserBirthDetailDTO->sunsign						=$sunsign;
        $objQuestionUserBirthDetailDTO->question_birth_detail_id	='0';
        $objQuestionUserBirthDetailDTO->question_id					=$question_id;
        $objQuestionUserBirthDetailDTO->sunsign						=$sunsign;

        $result = $objQuestion->SaveQuestionUserBirthDetail($objQuestionUserBirthDetailDTO);

        if(empty($result)) {
            $message = "Error occured while saving data";
        }

        // SAVE QUESTION USER BIRTH DETAIL CODE ENDS HERE


        // SAVE QUESTION USER HORORY DETAIL CODE START HERE

        $currentDate = $time;

        $currentDateArray = explode(" ",$currentDate);
        $currentDate = explode("-",$currentDateArray[0]);
        $currentTime = explode(":",$currentDateArray[1]);

        $objQuestionHororyDetailDTO->question_horory_detail_id	='0';
        $objQuestionHororyDetailDTO->question_id				=$question_id;
        //$objQuestionHororyDetailDTO->country					=$_REQUEST['current_birth_country'];
        $objQuestionHororyDetailDTO->state						=$_REQUEST['hdnCurrentState'];
        $objQuestionHororyDetailDTO->city						=$_REQUEST['txtCurrentBirthCity'];
        $objQuestionHororyDetailDTO->timezone					=$_REQUEST['current_time_zone'];
        //$objQuestionHororyDetailDTO->horory_image_id			=GetWheelImage();
        $objQuestionHororyDetailDTO->longitude					=$_REQUEST['hdnCurrentLongitude'];
        $objQuestionHororyDetailDTO->latitude					=$_REQUEST['hdnCurrentLatitude'];
        $objQuestionHororyDetailDTO->year						=$currentDate[0];
        $objQuestionHororyDetailDTO->month						=$currentDate[1];
        $objQuestionHororyDetailDTO->day						=$currentDate[2];
        $objQuestionHororyDetailDTO->hours						=$currentTime[0];
        $objQuestionHororyDetailDTO->minute						=$currentTime[1];
        $objQuestionHororyDetailDTO->second						=$currentTime[2];

        $objQuestionHororyDetailDTO->country					=$_REQUEST['ddCurrentBirthCountry'];
        $objQuestionHororyDetailDTO->country_name				=$_REQUEST['current_birth_country'];
        $objQuestionHororyDetailDTO->zoneRef					='';
        $objQuestionHororyDetailDTO->summerTimeZoneRef			='';



        $result = $objQuestion->SaveQuestionUserHororyDetail($objQuestionHororyDetailDTO);
        if(empty($result)) {
            $message = "Error occured while saving data";
        }

        // SAVE QUESTION USER HORORY DETAIL CODE ENDS HERE


        // SAVE QUESTION TIME DETAIL CODE START HERE

        $objQuestionHororyDetailDTO->question_time_id		='0';
        $objQuestionHororyDetailDTO->question_id			=$question_id;
        $objQuestionHororyDetailDTO->time_limit			=$_REQUEST['time_value'];
        $objQuestionHororyDetailDTO->answer_time_limit	=$_REQUEST['time_value'];
        $objQuestionHororyDetailDTO->time_remaining		=$_REQUEST['time_value'];

        $result = $objQuestion->SaveQuestionTimeDetail($question_id,$_REQUEST['question_priority']);
        if(empty($result)) {
            $message = "Error occured while saving data";
        }

        // SAVE QUESTION TIME DETAIL CODE ENDS HERE



        // SAVE ORDER CODE START HERE
        $currency_code 	= $_REQUEST['currency_code'];
        $price 			= $_REQUEST['question_price'];
        $user_id 			= $_REQUEST['user_id'];
        $user_email 		= $_REQUEST['UserEmail'];


        $objOrderDTO = new orderDTO();

        $objOrderDTO->delivery_option 	= 2;
        $objOrderDTO->user_id 			= $user_id;
        $objOrderDTO->product_item_id 	= 'GCQUE';
        $objOrderDTO->price 				= $price;
        $objOrderDTO->discount 			= 0;
        $objOrderDTO->order_date 			= date('Y-m-d');
        $objOrderDTO->order_status 		= '1';
        $objOrderDTO->product_type 		= 10;
        $objOrderDTO->currency_code 		= $currency_code;
        $objOrderDTO->chk_for_register 	= 0;
        $objOrderDTO->shipping_charge 	= 0;
        $objOrderDTO->email_id 			= $user_email;
        $objOrderDTO->language_code 		= $_REQUEST['language_name'];

        $objOrder = new Order();
        $result 	= $objOrder->SaveOrder($objOrderDTO);

        //echo 'code execution break';
        //exit;
        if(empty($result)) {
            $message = "Error occured while saving data";
        }
        else {
            $order_id = $result;

            $result = $objQuestion->UpdateQuestion($question_id,1,$order_id);

            // SEND CONFIRMATION MAIL TO USER
            //SendMailToUserAboutQuestionOrderPlaced($data);

            $paypal_url = '../helper/paypal/golden-circle/paypal.php';

            // SEND REQUIRED DETAIL AND REDIRECT USER TO PAYPAL FOR PAYMENT
            $queryString = "action=process";
            $queryString .= '&orderid='.$order_id;
            //$queryString .= '&product_item_id='.$_REQUEST['hdnId'];

            $queryString .= '&currency_code='.$currency_code;
            $queryString .= '&product_name='.'Golden Circle Question';
            $queryString .= '&product_price='.$price;
            $queryString .= '&product_discount='.'0';
            $queryString .= '&product_shipping_charge='.'0';
            $queryString .= '&prefix=GCQUE-';
            $queryString .= '&product_item_id=GCQUE-'.$order_id;
            $queryString .= '&user_id='.$user_id;
            $extra = $paypal_url.'?'.$queryString;

            $host  = $_SERVER['HTTP_HOST'];
            $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
            header("Location: http://$host$uri/$extra");
            exit;
        }
    }
    else if($_REQUEST['task'] == "SignInAstrologer") {
        $objQuestion = new GoldenCircle();
        if($objQuestion->validateAstrologerLogon($_REQUEST['txtEmail'],md5($_REQUEST['txtPassword']))) {
            echo 'true';
        }
        else {
            echo 'false';
        }
    }
    else if($_REQUEST['task'] == "GetNewQuestions") {
        $objQuestion = new GoldenCircle();
        $result = $objQuestion->GetNewQuestions($_REQUEST['astrologer_id'],$_REQUEST['items_per_page'],$_REQUEST['start']);

        echo json_encode($result);
    }
    else if($_REQUEST['task'] == "GetBlockedQuestions") {
        $objQuestion = new GoldenCircle();
        $result = $objQuestion->GetBlockedQuestions($_REQUEST['astrologer_id'],$_REQUEST['items_per_page'],$_REQUEST['start']);

        echo json_encode($result);
    }
    else if($_REQUEST['task'] == "GetAnsweredQuestions") {
        $objQuestion = new GoldenCircle();
        $result = $objQuestion->GetAnsweredQuestions($_REQUEST['astrologer_id'],$_REQUEST['items_per_page'],$_REQUEST['start']);

        echo json_encode($result);
    }
    else if($_REQUEST['task'] == "GetHighPriorityQuestions") {
        $objQuestion = new GoldenCircle();
        $result = $objQuestion->GetHighPriorityQuestions($_REQUEST['astrologer_id'],$_REQUEST['items_per_page'],$_REQUEST['start']);

        echo json_encode($result);
    }
    else if($_REQUEST['task'] == "GetLowPriorityQuestions") {
        $objQuestion = new GoldenCircle();
        $result = $objQuestion->GetLowPriorityQuestions($_REQUEST['astrologer_id'],$_REQUEST['items_per_page'],$_REQUEST['start']);

        echo json_encode($result);
    }
    else if($_REQUEST['task'] == "GetMediumPriorityQuestions") {
        $objQuestion = new GoldenCircle();
        $result = $objQuestion->GetMediumPriorityQuestions($_REQUEST['astrologer_id'],$_REQUEST['items_per_page'],$_REQUEST['start']);

        echo json_encode($result);
    }
    else if($_REQUEST['task'] == "Logout") {

        $host  = $_SERVER['HTTP_HOST'];

        $_COOKIE['AstrologerId'] = '';
        $_COOKIE['AstrologerEmail'] = '';

        setcookie("AstrologerId", '', time()-36000000,'/',$host);  /* expire in 1 month */
        setcookie("AstrologerEmail", '', time()-36000000,'/',$host);  /* expire in 1 month */

        return json_encode(true);
    }
    else if($_REQUEST['task'] == "makeQuestionsBlock") {

        $objQuestion = new GoldenCircle();
        //$arrayValue = explode(",", $_REQUEST['questionIds']);
        $result = $objQuestion->makeQuestionsBlock($_REQUEST['questionIds']);

        echo json_encode($result);
    }

    else if($_REQUEST['task'] == "UpdateStatusVacation") {
        $objQuestion = new GoldenCircle();
        //$arrayValue = explode(",", $_REQUEST['questionIds']);
        $result = $objQuestion->UpdateStatusVacation($_REQUEST['astrologer_id'],$_REQUEST['status']);

        echo json_encode($result);
    }
    else if($_REQUEST['task'] == "GetQuestionDetail") {
        $objQuestion = new GoldenCircle();
        //$arrayValue = explode(",", $_REQUEST['questionIds']);
        $result = $objQuestion->GetQuestionDetail($_REQUEST['question_id']);

        echo json_encode($result);
    }
    else if($_REQUEST['task'] == "postAnswer") {
        $objQuestion = new GoldenCircle();
        //$arrayValue = explode(",", $_REQUEST['questionIds']);

        $result = $objQuestion->postAnswer($_REQUEST['question_id'],$_REQUEST['answer'],$_REQUEST['astrologer_id']);
        $QuestionDetail = $objQuestion->GetQuestionDetail($_REQUEST['question_id']);
        if(count($QuestionDetail['Question'])>0) {
            $email_id = $QuestionDetail['Question'][0]['email'];
            $question = $QuestionDetail['Question'][0]['question'];
            $language = $QuestionDetail['Question'][0]['language'];
            $name = $QuestionDetail['Question'][0]['name'];

            if(!empty($email_id)) {
                SendQuestionAnsweredEmail($email_id,$question,$language,$name);
            }
        }

        echo json_encode($result);
    }
    else if($_REQUEST['task'] == "GetQuestionCount") {
        $objQuestion = new GoldenCircle();
        $result = $objQuestion->GetQuestionCount($_REQUEST['astrologer_id']);
        echo json_encode($result);
    }
    else if($_REQUEST['task'] == "GetQuestionCountForPaging") {
        $objQuestion = new GoldenCircle();
        if(empty($_REQUEST['question_type'])) {
            $result = $objQuestion->GetQuestionCountForPaging($_REQUEST['astrologer_id']);
        }
        else if($_REQUEST['question_type'] == 1) {
            $result = $objQuestion->GetNewQuestionCountForPaging($_REQUEST['astrologer_id']);
        }
        else if($_REQUEST['question_type'] == 2) {
            $result = $objQuestion->GetBlockedQuestionCountForPaging($_REQUEST['astrologer_id']);
        }
        else if($_REQUEST['question_type'] == 3) {
            $result = $objQuestion->GetAnsweredQuestionCountForPaging($_REQUEST['astrologer_id']);
        }
        else if($_REQUEST['question_type'] == 4) {
            $result = $objQuestion->GetHighQuestionCountForPaging($_REQUEST['astrologer_id']);
        }
        else if($_REQUEST['question_type'] == 5) {
            $result = $objQuestion->GetMediumQuestionCountForPaging($_REQUEST['astrologer_id']);
        }
        else if($_REQUEST['question_type'] == 6) {
            $result = $objQuestion->GetLowQuestionCountForPaging($_REQUEST['astrologer_id']);
        }
        echo json_encode($result);
    }
    else if($_REQUEST['task'] == "GetQuestions") {
        $objQuestion = new GoldenCircle();
        //$result = $objQuestion->GetQuestions($_REQUEST['astrologer_id']);
        if(empty($_REQUEST['question_type'])) {
            $result = $objQuestion->GetQuestions($_REQUEST['astrologer_id']);
        }
        else if($_REQUEST['question_type'] == 1) {
            $result = $objQuestion->GetNewQuestions($_REQUEST['astrologer_id']);
        }
        else if($_REQUEST['question_type'] == 2) {
            $result = $objQuestion->GetBlockedQuestions($_REQUEST['astrologer_id']);
        }
        else if($_REQUEST['question_type'] == 3) {
            $result = $objQuestion->GetAnsweredQuestions($_REQUEST['astrologer_id']);
        }
        else if($_REQUEST['question_type'] == 4) {
            $result = $objQuestion->GetHighPriorityQuestions($_REQUEST['astrologer_id']);
        }
        else if($_REQUEST['question_type'] == 5) {
            $result = $objQuestion->GetMediumPriorityQuestions($_REQUEST['astrologer_id']);
        }
        else if($_REQUEST['question_type'] == 6) {
            $result = $objQuestion->GetLowPriorityQuestions($_REQUEST['astrologer_id']);
        }
        echo json_encode($result);
    }
    else if($_REQUEST['task'] == "GetQuestionListCountByUserId") {
        $objQuestion = new GoldenCircle();
        $result = $objQuestion->GetQuestionListCountByUserId($_REQUEST['user_id']);
        echo json_encode($result);
    }
    else if($_REQUEST['task'] == "GetQuestionListByUserId") {
        $objQuestion = new GoldenCircle();
        $result = $objQuestion->GetQuestionListByUserId($_REQUEST['user_id'],$_REQUEST['items_per_page'],$_REQUEST['start']);
        echo json_encode($result);
    }
    else if($_REQUEST['task'] == "ChangeAstrologerPassword") {
        $objQuestion = new GoldenCircle();
        $result = $objQuestion->CheckPassword($_REQUEST['astrologer_id'],md5($_REQUEST['txtOldPassword']));
        //print_r($result);
        if($result) {
            $result = $objQuestion->ChangeAstrologerPassword($_REQUEST['astrologer_id'],md5($_REQUEST['txtNewPassword']));
            if($result) {
                echo 'true';
            }
            else {
                echo 'false';
            }
        }
        else {
            echo 'false';
        }
    }

    else if($_REQUEST['task'] == "RateAnswer") {
        $objQuestion = new GoldenCircle();
        $result = $objQuestion->RateAnswer($_REQUEST['answer_id'],$_REQUEST['rate']);
        //print_r($result);
        if($result) {
            echo 'true';
        }
        else {
            echo 'false';
        }
    }
    else if($_REQUEST['task'] == "GetQuestionPrice") {
        $objQuestion = new GoldenCircle();
        $result = $objQuestion->GetQuestionPrice($_REQUEST['priority'],$_REQUEST['currency_id']);
        echo json_encode($result);
    }
    else if($_REQUEST['task'] == "makeQuestionsReject") {

        $objQuestion = new GoldenCircle();
        //$arrayValue = explode(",", $_REQUEST['questionIds']);
        $result = $objQuestion->makeQuestionsReject($_REQUEST['questionIds'],$_REQUEST['astrologer_id']);

        echo json_encode($result);
    }

    else if($_REQUEST['task'] == "GetCommissionMonthWiseByAstrologerId") {
        $objQuestion = new GoldenCircle();
        $result = $objQuestion->GetCommissionMonthWiseByAstrologerId($_REQUEST['astrologer_id']);
        echo json_encode($result);
    }
    else if($_REQUEST['task'] == "GetCommissionMonthAndYearWiseByAstrologerId") {
        $objQuestion = new GoldenCircle();
        $result = $objQuestion->GetCommissionMonthAndYearWiseByAstrologerId($_REQUEST['astrologer_id'],$_REQUEST['year'],$_REQUEST['month']);
        echo json_encode($result);
    }
    else if($_REQUEST['task'] == "SaveAstrologerDetail") {
        //print_r($_REQUEST);
        $objQuestion = new GoldenCircle();
        $vacationStatus = 0;
        if(isset($_REQUEST['chkOnVacation'])) {
            $vacationStatus = 1;
        }
        $result = $objQuestion->UpdateStatusVacation($_REQUEST['AstrologerId'],$vacationStatus);

        if(isset($_REQUEST['selectedLanguages'])) {
            $result = $objQuestion->SaveAstrologerLanguage($_REQUEST['selectedLanguages'],$_REQUEST['AstrologerId']);
        }

        $result = $objQuestion->UpdateAlternateEmailAddress($_REQUEST['txtSecondaryEamil'],$_REQUEST['AstrologerId']);

        $result = $objQuestion->UpdateEmergencyContactDetail($_REQUEST);


        echo "<html>".
                "<body onLoad=\"document.forms['download_form'].submit();\">".
                    "<form method=\"post\" name=\"download_form\" action=\"../golden-circle/astrologer/myaccount.php\">" .
                        "<input type=\"hidden\" name=\"message\" value=\"Detail Save Successfully\"/>" .
                        // echo "<input type=\"hidden\" name=\"product_item_id\" value=\"".$_REQUEST['hdnId']."\"/>";
                    "</form>".
                "</body>".
            "</html>";
        exit;

        //header("Location: http://".$_REQUEST['returnURL']);
        //exit;
        //$objQuestion = new GoldenCircle();
        //$result = $objQuestion->SaveAstrologerDetail($_REQUEST);
    }

}

function GetWheelImage() {
    //return 'file_id';
}

function SendQuestionAnsweredEmail($user_email,$question,$language,$name) {


    $sender = "admin@n-techcorporate.com";
    $rec = array(
            'to'	=>$user_email
    );

    $data = array(
            'subject'			=> "Your Question for Golden Circle is answered",
            'mailtext'			=> "",
            'type'				=> "html",
            //'attachment'		=> array( 'testfile.html' ),
            'question'			=> $question,
            'name'				=> $name,
            'language'			=> $language
    );

    //$to = $data['user_email'];
    // TODO :: for now we send mail derectly, letter on it will fired from generic mail class
    //$subject = "Your Payment of order".$data['order_id']." accepted";
    //$body =  "Payment Accepted\n>";
    //$body =  "Order No:".$data['order_id'];

    //mail($to, $subject, $body);

    if ( genericMail::SendQuestionAnsweredEmail( $sender, $rec, $data ) ) {
        //print "Mail was send successfull ... \n";
    } else {
        //print "Hmm .. there could be a Problem ... \n";
    }
}
?>