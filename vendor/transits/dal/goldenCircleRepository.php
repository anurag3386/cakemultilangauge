<?php
date_default_timezone_set ( 'America/Los_Angeles' );
if (!class_exists('cDatabase')) {
	if(!include("cDatabase.php")) {
		require_once("../cDatabase.php");
	}
}

class GoldenCircleRepository {
	public function GetPriceList() {
		$obj = new cDatabase();
		$sql = 'SELECT cp.question_price_id ,cp.price ,cp.priority ,cp.currency_id ,cp.status , c.name, c.symbol, c.code, cp.time_mode, cp.time_value
				FROM `question_price` cp
				left join currency c on cp.currency_id = c.currency_id';
		$where = "  ";
		$sql = $sql.$where;
		$query = $obj->db->query($sql);

		return $query->rows;
	}

	public function SaveQuestionDetail($data) {
		$obj = new cDatabase();
		$sql = " Insert into `questions`  SET
				question = '" . $obj->db->escape($data->question) . "',
				price = '" . $obj->db->escape($data->price) . "',
				question_time = '" . $obj->db->escape($data->question_time) . "',
				status = '" . $obj->db->escape($data->status) . "',
				is_block = '" . $obj->db->escape($data->is_block) . "',
				user_id = '" . $obj->db->escape($data->user_id) . "',
				email = '" . $obj->db->escape($data->email) . "',
				name = '" . $obj->db->escape($data->name) . "',
				language = '" . $obj->db->escape($data->language) . "',
				price_id = '" . $obj->db->escape($data->price_id) . "',
				priority = '" . $obj->db->escape($data->priority) . "'";
		$query = $obj->db->query($sql);
		if($query) {
			$question_id = $obj->db->getLastId();
		}
		else {
			$question_id = 0;
		}
		return $question_id;

	}

	public function SaveQuestionUserBirthDetail($data) {
		$data = $this->GetLocationInformation($data);

		$obj = new cDatabase();
		$sql = " Insert into `question_user_birth_detail`  SET
				question_id = '" . $obj->db->escape($data->question_id) . "',
				name = '" . $obj->db->escape($data->name) . "',
				birth_date = '" . $obj->db->escape($data->birth_date) . "',
				hours = '" . $obj->db->escape($data->hours) . "',
				min = '" . $obj->db->escape($data->minute) . "',
				untimed = '" . $obj->db->escape($data->untimed) . "',
				country = '" . $obj->db->escape($data->country) . "',
				state = '" . $obj->db->escape($data->state) . "',
				city = '" . $obj->db->escape($data->city) . "',
				timezone = '" . $obj->db->escape($data->timezone) . "',
				sunsign = '" . $obj->db->escape($data->sunsign) . "',
				wheel_image_id = '" . $obj->db->escape($data->wheel_image_id) . "',
				longitude = '" . $obj->db->escape($data->longitude) . "',
				latitude = '" . $obj->db->escape($data->latitude) . "',

				country_name = '" . $obj->db->escape($data->country_name) . "',
				summerTimeZoneRef = '" . $obj->db->escape($data->summerTimeZoneRef) . "',
				zoneRef = '" . $obj->db->escape($data->zoneRef) . "'";

		$query = $obj->db->query($sql);
		if($query) {
			$question_birth_detail_id = $obj->db->getLastId();
		}
		else {
			$question_birth_detail_id = 0;
		}
		return $question_birth_detail_id;
	}

	public function SaveQuestionUserHororyDetail($data) {
		$data = $this->GetLocationInformation($data);

		$obj = new cDatabase();
		$sql = " Insert into `question_user_horory_detail`  SET
				question_id = '" . $obj->db->escape($data->question_id) . "',
				country = '" . $obj->db->escape($data->country) . "',
				state = '" . $obj->db->escape($data->state) . "',
				city = '" . $obj->db->escape($data->city) . "',
				timezone = '" . $obj->db->escape($data->timezone) . "',
				horory_image_id = '" . $obj->db->escape($data->horory_image_id) . "',
				longitude = '" . $obj->db->escape($data->longitude) . "',
				latitude = '" . $obj->db->escape($data->latitude) . "',
				year = '" . $obj->db->escape($data->year) . "',
				month = '" . $obj->db->escape($data->month) . "',
				day = '" . $obj->db->escape($data->day) . "',
				hours = '" . $obj->db->escape($data->hours) . "',
				min = '" . $obj->db->escape($data->minute) . "',
				second = '" . $obj->db->escape($data->second) . "',
				
				country_name = '" . $obj->db->escape($data->country_name) . "',
				summerTimeZoneRef = '" . $obj->db->escape($data->summerTimeZoneRef) . "',
				zoneRef = '" . $obj->db->escape($data->zoneRef) . "'";

		$query = $obj->db->query($sql);
		if($query) {
			$question_horory_detail_id = $obj->db->getLastId();
		}
		else {
			$question_horory_detail_id = 0;
		}
		return $question_horory_detail_id;
	}

	public function SaveQuestionTimeDetail($question_id,$priority) {
		$question_time = $priority*24;
		$question_answer_limit = '';
		if($priority == 1) {
			//$question_answer_limit = 12*60*60;
			$question_answer_limit = 18*60*60;
		}
		else if($priority == 3) {
			$question_answer_limit = 48*60*60;
			//$question_answer_limit = 24*60*60;
			//$question_answer_limit = $question_answer_limit*60*60;
		}
		$obj = new cDatabase();
		$sql = " Insert into `question_time`  SET
				question_id 				= '" . $obj->db->escape($question_id) . "',
				question_priority 			= '" . $obj->db->escape($priority) . "',
				question_time 				= '" . $obj->db->escape($question_time) . "',
				question_answer_time_limit 	= '" . $obj->db->escape($question_answer_limit) . "',
				assign_time 				= '',
				total_assign 				= '1'
				";

		$query = $obj->db->query($sql);
		if($query) {
			$question_time_id = $obj->db->getLastId();
		}
		else {
			$question_time_id = 0;
		}
		return $question_time_id;
	}

	public function UpdateQuestion($question_id,$status,$order_id) {
		$obj = new cDatabase();
		$sql = " update `questions`  SET  ";

		if(isset($question_id) && !empty($question_id)) {
			$sql .= "status = '" . $obj->db->escape($status) . "',";
			$sql .= "order_id = '" . $obj->db->escape($order_id) . "'";
			$sql .= " where question_id = ".$question_id;
		}
		else if(isset($order_id) && !empty($order_id)) {
			$sql .= "status = '" . $obj->db->escape($status) . "'";
			$sql .= " where order_id = ".$order_id;
		}

		$query = $obj->db->query($sql);
		if($query) {
			$question_id = 1;
		}
		else {
			$question_id = 0;
		}
		return $question_id;
	}

	public function validateAstrologerLogon($userName,$password) {
		try {
			$obj = new cDatabase();
			$sql = "select astrologer_id,status,username from astrologer where username = '".$userName."' and password = '".$password."'";
			$query = $obj->db->query($sql);
			return $query->row;
		}
		catch(Exception $ex) {

		}
		$a = array();
		return $a;
	}

	public function GetNewQuestions($astrologer_id,$items_per_page,$start) {
		try {
			$obj = new cDatabase();
			$sql = "SELECT q.question_id,q.question,q.status,q.is_block,q.user_id,q.language,q.priority,up.FirstName,up.LastName,
					rl.name, q.question_time, qt.assign_time, qt.question_answer_time_limit , 'time_remaining', qt.question_time, q.question_time as 'question_ask_time'
					FROM `questions` q
					left join user u on u.UserId = q.user_id
					left join userprofile up on up.UserId = q.user_id
					left join `report_languages` rl on rl.report_language_id = q.language
						
					left join question_to_astrologer qa on qa.question_id = q.question_id
					left join question_time qt on qt.question_id = q.question_id
						
					where q.status = 3 and q.is_block = 0 and astrologer_id = ".$astrologer_id."
							order by q.question_id desc
							limit ".$start.",".$items_per_page;
			$query = $obj->db->query($sql);
			return $query->rows;
		}
		catch(Exception $ex) {

		}
		$a = array();
		return $a;
	}

	public function GetBlockedQuestions($astrologer_id,$items_per_page,$start) {
		try {
			$obj = new cDatabase();
			$sql = "SELECT q.question_id,q.question,q.status,q.is_block,q.user_id,q.language,q.priority,up.FirstName,up.LastName,
					rl.name, q.question_time, qt.assign_time, qt.question_answer_time_limit , 'time_remaining', qt.question_time , q.question_time as 'question_ask_time'
					FROM `questions` q
					left join user u on u.UserId = q.user_id
					left join userprofile up on up.UserId = q.user_id
					left join `report_languages` rl on rl.report_language_id = q.language
						
					left join question_to_astrologer qa on qa.question_id = q.question_id
					left join question_time qt on qt.question_id = q.question_id
					where q.status = 3 and q.is_block = 1 and astrologer_id = ".$astrologer_id."
							order by q.question_id desc limit ".$start.",".$items_per_page;
			$query = $obj->db->query($sql);
			return $query->rows;
		}
		catch(Exception $ex) {

		}
		$a = array();
		return $a;
	}

	public function GetAnsweredQuestions($astrologer_id,$items_per_page,$start) {
		try {
			$obj = new cDatabase();
			$sql = "SELECT q.question_id,q.question,q.status,q.is_block,q.user_id,q.language,q.priority,up.FirstName,up.LastName, rl.name, q.question_time
					FROM `questions` q
							left join `answer` a on a.question_id = q.question_id 
							left join user u on u.UserId = q.user_id
							left join userprofile up on up.UserId = q.user_id
							left join `report_languages` rl on rl.report_language_id = q.language								
							left join question_to_astrologer qa on qa.question_id = q.question_id ";
			$Where = "WHERE q.status = 4 AND q.is_block IN (0, 1) AND a.astrologer_id = ".$astrologer_id."
							order by q.question_id desc limit ".$start.",".$items_per_page;
			
// 			$Where = "WHERE q.status = 4 and q.is_block = 1 and astrologer_id = ".$astrologer_id."
// 							order by q.question_id desc limit ".$start.",".$items_per_page;
			$sql = $sql.$Where;
			$query = $obj->db->query($sql);
			return $query->rows;
		}
		catch(Exception $ex) {

		}
		$a = array();
		return $a;
	}

	public function GetHighPriorityQuestions($astrologer_id,$items_per_page,$start) {
		try {
			$obj = new cDatabase();
			$sql = "SELECT q.question_id,q.question,q.status,q.is_block,q.user_id,q.language,q.priority,up.FirstName,up.LastName,
					rl.name, q.question_time, qt.assign_time, qt.question_answer_time_limit , 'time_remaining', qt.question_time
					FROM `questions` q
						left join user u on u.UserId = q.user_id
						left join userprofile up on up.UserId = q.user_id
						left join `report_languages` rl on rl.report_language_id = q.language
							
						left join question_to_astrologer qa on qa.question_id = q.question_id
						left join question_time qt on qt.question_id = q.question_id
					where q.status = 3 and q.priority = 1 and q.is_block = 1 and astrologer_id = ".$astrologer_id."
							order by q.question_id desc limit ".$start.",".$items_per_page;
			$query = $obj->db->query($sql);
			return $query->rows;
		}
		catch(Exception $ex) {

		}
		$a = array();
		return $a;
	}

	public function GetLowPriorityQuestions($astrologer_id,$items_per_page,$start) {
		try {
			$obj = new cDatabase();
			$sql = "SELECT q.question_id,q.question,q.status,q.is_block,q.user_id,q.language,q.priority,up.FirstName,up.LastName,
					rl.name, q.question_time, qt.assign_time, qt.question_answer_time_limit , 'time_remaining', qt.question_time
					FROM `questions` q
					left join user u on u.UserId = q.user_id
					left join userprofile up on up.UserId = q.user_id
					left join `report_languages` rl on rl.report_language_id = q.language
						
					left join question_to_astrologer qa on qa.question_id = q.question_id
					left join question_time qt on qt.question_id = q.question_id
					where q.status = 3 and q.priority = 3 and q.is_block = 1 and astrologer_id = ".$astrologer_id."
							order by q.question_id desc limit ".$start.",".$items_per_page;
			$query = $obj->db->query($sql);
			return $query->rows;
		}
		catch(Exception $ex) {

		}
		$a = array();
		return $a;
	}

	public function GetMediumPriorityQuestions($astrologer_id,$items_per_page,$start) {
		try {
			$obj = new cDatabase();
			$sql = "SELECT q.question_id,q.question,q.status,q.is_block,q.user_id,q.language,q.priority,up.FirstName,up.LastName,
					rl.name, q.question_time, qt.assign_time, qt.question_answer_time_limit , 'time_remaining', qt.question_time
					FROM `questions` q
					left join user u on u.UserId = q.user_id
					left join userprofile up on up.UserId = q.user_id
					left join `report_languages` rl on rl.report_language_id = q.language
						
					left join question_to_astrologer qa on qa.question_id = q.question_id
					left join question_time qt on qt.question_id = q.question_id
					where q.status = 3 and q.priority = 2 and q.is_block = 1 and astrologer_id = ".$astrologer_id."
							order by q.question_id desc limit ".$start.",".$items_per_page;
			$query = $obj->db->query($sql);
			return $query->rows;
		}
		catch(Exception $ex) {

		}
		$a = array();
		return $a;
	}

	public function makeQuestionsBlock($questionIds) {
		$obj = new cDatabase();
		$sql = " update `questions`  SET  ";
		$sql .= "	is_block = 1";
		$sql .= " where question_id in ('".$questionIds."')";

		$query = $obj->db->query($sql);
		if($query) {
			$question_id = 1;
		}
		else {
			$question_id = 0;
		}
		return $question_id;
	}

	public function UpdateStatusVacation($astrologer_id, $status) {
		$obj = new cDatabase();
		$sql = " update `astrologer_profile`  SET  ";
		$sql .= "	on_vacation = ".$status;
		$sql .= " where astrologer_id ='".$astrologer_id."'";

		$query = $obj->db->query($sql);
		if($query) {
			$question_id = 1;
		}
		else {
			$question_id = 0;
		}
		return $question_id;
	}

	public function GetDetailById($astrologer_id) {
		try {
			$obj = new cDatabase();

			$sql = " SELECT a.astrologer_id, a.username,a.secondary_email,a.status , ap.astrologer_profile_id,ap.first_name,
					ap.last_name, ap.gender, ap.address, ap.city, ap.state, ap.country, ap.biography, ap.image, ap.timezone, ap.on_vacation,
					ap.contact_number, ac.contact_id, ac.contact_mode, ac.detail, f.path,f.name, f.file_id
					FROM `astrologer` a
					left join astrologer_profile ap on ap.astrologer_id = a.astrologer_id
					left join astrologer_contact_on_emergency ac on ac.astrologer_id = a.astrologer_id
					left join files f on f.file_id = ap.image";
			$where = " WHERE a.astrologer_id = ".$astrologer_id;
			$sql = $sql.$where;
			$query = $obj->db->query($sql);
			return $query->rows;
		}
		catch(Exception $ex) {
			//$userDetail->resultState->code=1;
			//$userDetail->resultState->message=$ex;
			die($ex->getMessage());
		}
	}

	public function GetQuestionDetail($id) {
		$obj = new cDatabase();
		$sql = " SELECT
				q.`question_id`, q.`question`, q.`question_time`, q.`status`, q.`is_block`, q.`user_id`, q.`email`, q.`name`, q.`language`, q.`priority`, q.`order_id`,q.`price_id`, qp.time_mode, qp.time_value, qt.assign_time, qt.question_answer_time_limit , 'time_remaining', qt.question_time , q.question_time as 'question_ask_time' ,
				qp.price, qp.currency_id
				FROM `questions` q
				left join `question_price` qp on qp.question_price_id = q.price_id
				left join question_time qt on qt.question_id = q.question_id";
		$where = " where q.question_id=".$id;
		$sql = $sql.$where;
		$query = $obj->db->query($sql);

		$questions = $query->rows;


		$sql = " SELECT qbd.name, qbd.birth_date, qbd.hours, qbd.min, qbd.untimed, qbd.country, qbd.state, qbd.city, qbd.timezone, qbd.sunsign, qbd.longitude, qbd.latitude ,qbd.country_name
				FROM `question_user_birth_detail` qbd ";
		$where = " where qbd.question_id= ".$id;
		$sql = $sql.$where;
		$query = $obj->db->query($sql);

		$question_user_birth_detail = $query->rows;



		$sql = " select country, 	state 	,city ,	timezone ,	horory_image_id ,	longitude, 	latitude ,	year ,	month ,	day ,	hours,	min, 	second ,country_name
				FROM `question_user_horory_detail`  ";
		$where = " where question_id=".$id;
		$sql = $sql.$where;
		$query = $obj->db->query($sql);

		$question_user_horory_detail = $query->rows;

		$sql = " select answer_id, 	question_id,	answer, 	astrologer_id ,		answered_in_time , 	rating
				FROM `answer`  ";
		$where = " where question_id=".$id;
		$sql = $sql.$where;
		$query = $obj->db->query($sql);

		$question_answer_detail = $query->rows;

		$returnValue = array("Question"=>$questions, "BirthDetail"=>$question_user_birth_detail, "HororyDetail"=>$question_user_horory_detail, "Answer"=>$question_answer_detail);

		return $returnValue;
	}

	public function postAnswer($question_id,$answer,$astrologer_id) {

		$obj = new cDatabase();


		$sql = "select question_id from `answer` where question_id = ".$question_id;
		$query = $obj->db->query($sql);
		//print_r($query->row);
		//print_r(count($query->rows)) ;
		//return 1;
		if( count($query->rows) > 0) {
			return 1;

		}
		else {
			$sql = " Insert into `answer`  SET
					question_id = '" . $obj->db->escape($question_id) . "',
							answer = '" . $obj->db->escape($answer) . "',
									astrologer_id = '" . $obj->db->escape($astrologer_id) . "',
											answered_in_time = ''";
			$query = $obj->db->query($sql);
			if($query) {
				$answer_id = $obj->db->getLastId();

				$sql = " update `questions`  SET  ";
				$sql .= "status = '4'";
				$sql .= " where question_id = ".$question_id;
				$query = $obj->db->query($sql);
			}
			else {
				$answer_id = 0;
			}
			return $answer_id;
		}
	}

	public function GetQuestionCount($astrologer_id) {
		$obj = new cDatabase();

		$sql = "select
				(select count(q.question_id) from questions q
				left join `report_languages` rl on rl.report_language_id = q.language
				left join question_to_astrologer qa on qa.question_id = q.question_id
				where q.status = 3 and is_block = 0 and astrologer_id = ".$astrologer_id.") as 'new_question' ,

				(select count(q.question_id)  from questions q
				left join `report_languages` rl on rl.report_language_id = q.language
				left join question_to_astrologer qa on qa.question_id = q.question_id
				where q.status = 3 and is_block = 1 and astrologer_id = ".$astrologer_id.") as 'blocked_question',

				(select count(q.question_id)  from questions q
				left join `report_languages` rl on rl.report_language_id = q.language
				left join question_to_astrologer qa on qa.question_id = q.question_id
				where q.status = 4 and is_block = 1 and astrologer_id = ".$astrologer_id.")as 'answered_question',

				(select count(q.question_id)  from questions q
				left join `report_languages` rl on rl.report_language_id = q.language
				left join question_to_astrologer qa on qa.question_id = q.question_id
				where q.status = 3 and  (is_block = 1 or is_block = 0)  and priority = 1 and astrologer_id = ".$astrologer_id.")as 'high_priority',

				(select count(q.question_id)  from questions q
				left join `report_languages` rl on rl.report_language_id = q.language
				left join question_to_astrologer qa on qa.question_id = q.question_id
				where q.status = 3 and  (is_block = 1 or is_block = 0)  and priority = 2 and astrologer_id = ".$astrologer_id.")as 'medium_priority',

				(select count(q.question_id)  from questions q
				left join `report_languages` rl on rl.report_language_id = q.language
				left join question_to_astrologer qa on qa.question_id = q.question_id
				where q.status = 3 and (is_block = 1 or is_block = 0) and priority = 3 and astrologer_id = ".$astrologer_id.")as 'low_priority'

				from questions
				group by new_question";

		$query = $obj->db->query($sql);
		return $query->row;
	}

	public function GetQuestionCountForPaging($astrologer_id) {
		$obj = new cDatabase();
		$sql = "SELECT count(q.question_id) as count
				FROM `questions` q
				left join question_to_astrologer qa on qa.question_id = q.question_id
				where 1 = 1 and astrologer_id = ".$astrologer_id;
		$query = $obj->db->query($sql);
		//print_r($query->row);
		//echo count($query->row);
		return $query->row['count'];
		//return $query->row;
	}

	public function GetQuestions($astrologer_id) {
		$obj = new cDatabase();
		$sql = "SELECT q.question_id,q.question,q.status,q.is_block,q.user_id,q.language,q.priority,up.FirstName,up.LastName,
				rl.name
				FROM `questions` q
				left join user u on u.UserId = q.user_id
				left join userprofile up on up.UserId = q.user_id
				left join `report_languages` rl on rl.report_language_id = q.language
					
				left join question_to_astrologer qa on qa.question_id = q.question_id
				where 1 = 1 and astrologer_id = ".$astrologer_id."
						order by q.question_id desc ";
		$query = $obj->db->query($sql);
		return $query->rows;
	}

	public function GetNewQuestionCountForPaging($astrologer_id) {
		$obj = new cDatabase();
		$sql = "SELECT count(q.question_id) as count
				FROM `questions` q
				left join question_to_astrologer qa on qa.question_id = q.question_id
				where 1 = 1 and q.status = 3 and q.is_block = 0 and astrologer_id = ".$astrologer_id;
		$query = $obj->db->query($sql);
		//print_r($query->row);
		//echo count($query->row);
		return $query->row['count'];
		//return $query->row;
	}

	public function GetBlockedQuestionCountForPaging($astrologer_id) {
		$obj = new cDatabase();
		$sql = "SELECT count(q.question_id) as count
				FROM `questions` q
				left join question_to_astrologer qa on qa.question_id = q.question_id
				where 1 = 1 and q.status = 3 and q.is_block = 1 and astrologer_id = ".$astrologer_id;
		$query = $obj->db->query($sql);
		return $query->row['count'];
	}

	public function GetAnsweredQuestionCountForPaging($astrologer_id) {
		$obj = new cDatabase();
		$sql = "SELECT count(q.question_id) as count
				FROM `questions` q
						left join question_to_astrologer qa on qa.question_id = q.question_id
				where 1 = 1 AND q.status = 4 AND q.is_block IN (0, 1) AND astrologer_id = ".$astrologer_id;
		$query = $obj->db->query($sql);
		return $query->row['count'];
	}

	public function GetHighQuestionCountForPaging($astrologer_id) {
		$obj = new cDatabase();
		$sql = "SELECT count(q.question_id) as count
				FROM `questions` q
				left join question_to_astrologer qa on qa.question_id = q.question_id
				where 1 = 1 and q.status = 3 and q.priority = 1 and q.is_block = 1 and astrologer_id = ".$astrologer_id;
		$query = $obj->db->query($sql);
		return $query->row['count'];
	}

	public function GetMediumQuestionCountForPaging($astrologer_id) {
		$obj = new cDatabase();
		$sql = "SELECT count(q.question_id) as count
				FROM `questions` q
				left join question_to_astrologer qa on qa.question_id = q.question_id
				where 1 = 1 and q.status = 3 and q.priority = 2 and q.is_block = 1 and astrologer_id = ".$astrologer_id;
		$query = $obj->db->query($sql);
		//print_r($query->row);
		//echo count($query->row);
		return $query->row['count'];
		//return $query->row;
	}

	public function GetLowQuestionCountForPaging($astrologer_id) {
		$obj = new cDatabase();
		$sql = "SELECT count(q.question_id) as count
				FROM `questions` q
				left join question_to_astrologer qa on qa.question_id = q.question_id
				where 1 = 1 and q.status = 3 and q.priority = 3 and q.is_block = 1 and astrologer_id = ".$astrologer_id;
		$query = $obj->db->query($sql);
		//print_r($query->row);
		//echo count($query->row);
		return $query->row['count'];
		//return $query->row;
	}

	public function GetQuestionListByUserId($user_id,$items_per_page,$start) {
		$obj = new cDatabase();
		$sql = "SELECT q.question_id,q.question,q.status,q.is_block,q.user_id,q.language,q.priority,up.FirstName,up.LastName,
				rl.name
				FROM `questions` q
				left join user u on u.UserId = q.user_id
				left join userprofile up on up.UserId = q.user_id
				left join `report_languages` rl on rl.report_language_id = q.language
				where 1 = 1 and user_id = ".$user_id."
						order by q.question_id desc
						limit ".$start.",".$items_per_page."
								";
		$query = $obj->db->query($sql);
		return $query->rows;
	}

	public function GetQuestionListCountByUserId($user_id) {
		$obj = new cDatabase();
		$sql = "SELECT count(q.question_id) as count
				FROM `questions` q
					
				where 1 = 1 and user_id = ".$user_id."
						order by q.question_id desc ";
		$query = $obj->db->query($sql);
		return $query->row['count'];
	}

	public function CheckPassword($astrologer_id,$password) {
		$obj = new cDatabase();
		$sql = "SELECT count(astrologer_id) as count
				FROM `astrologer`
					
				where password = '".$password."' and astrologer_id = ".$astrologer_id." ";
		$query = $obj->db->query($sql);
		return $query->row['count'];
	}

	public function ChangeAstrologerPassword($astrologer_id,$password) {

		$obj = new cDatabase();
		$sql = " update `astrologer`  SET  password = '".$password."' where astrologer_id = ".$astrologer_id."";

		$query = $obj->db->query($sql);
		if($query) {
			$question_id = 1;
		}
		else {
			$question_id = 0;
		}
		return $question_id;

	}

	public function RateAnswer($answer_id,$rate) {

		$obj = new cDatabase();
		$sql = " update `answer`  SET  rating = '".$rate."' where answer_id = ".$answer_id."";

		$query = $obj->db->query($sql);
		if($query) {
			$answer_id = 1;
		}
		else {
			$answer_id = 0;
		}
		return $answer_id;

	}

	public function GetLocationInformation($data) {
		$Longitude = $data->longitude;
		$Latitude = $data->latitude;

		if($Latitude > 90) {
			$Latitude = $Latitude * 3600;
			//$data->latitude = $Latitude;
		}

		if($Longitude > 180) {
			$Longitude = $Longitude * 3600;
			//$data->longitude = $Longitude;
		}

		$sql =  "select * from `orm_acsatlas`".
				" where upper(placename)='".strtoupper($data->country)."' ".
				" and longitude ='".$Longitude."'".
				" and latitude ='".$Latitude."'".
				" order by lkey";
		$ACSRep = new  ACSRepository();

		$Location = sprintf( "%s, %s",	$data->country, $data->state);

		$IsThere = $this->GetSummerTimeZoneANDTimeZone($Location);

		if(count($IsThere) > 0 ) {
			$data->zoneRef = $IsThere['m_timezone_offset'];
			$data->summerTimeZoneRef = $IsThere['m_summertime_offset'];
		}
		else {
			$Result = $ACSRep->GetACSDataRow($sql);

			if($Result) {
				$acsTimeTable = new AcsTimetables();
				if(isset($data->birth_date)) {
					$date = explode("-", $data->birth_date);
					$acsTimeTable->setBirthdate( sprintf("%04d-%02d-%02d %02d:%02d:%02d",
							$date[0], $date[1], $date[2],
							$data->hours, $data->min, 0) );
				}
				else {
					$acsTimeTable->setBirthdate( sprintf("%04d-%02d-%02d %02d:%02d:%02d",
							$data->year, $data->month, $data->day,
							$data->hours, $data->min, 0) );
				}


				$data->ZoneRef = $acsTimeTable->getZoneOffset($Result[0]['zone']);
				$data->SummerTimeZoneRef = $acsTimeTable->getTypeOffset($Result[0]['type']);
			}
		}
		return $data;
	}

	public function GetSummerTimeZoneANDTimeZone($location) {
		$TimeZoneArray = array();

		if(extension_loaded('acsatlas')) {
			//Get the city info
			$city_info = acs_lookup_city($location);

			if (!$city_info) {
				return $TimeZoneArray;
				//die('The city lookup was unsuccessful.');
			}
			extract($city_info);
			// $city_info = Array (
			// [city_index] => 4360
			// [country_index] => 4
			// [city] => Pomona
			// [county] => Los Angeles
			// [country] => California
			// [countydup] => 37
			// [latitude] => 122599
			// [longitude] => 423905
			// [typetable] => 83
			// [zonetable] => 7200)

			//Get the time zone info
			//$time_info = acs_time_change_lookup($month, $day, $year, $hour, $minute, $zonetable, $typetable);
			$time_info = acs_time_change_lookup($this->m_birth_month, $this->m_birth_day, $this->m_birth_year,
					$this->m_birth_hour, $this->m_birth_minute, $zonetable, $typetable);
			if (!$time_info) {
				return $TimeZoneArray;
				//die('The time zone lookup was unsuccessful.');
			}
			extract($time_info);
			// $time_info = Array  (
			// [zone] => 7200
			// [type] => 1
			// [abbr] => PDT
			// [flagout] => 0)

			if($type >= 0) {
				//Get the offset in hours from UTC
				$time_types = array(0,1,1,2); //assume $time_type < 4
				$offset = ($zone/900) - $time_types[$type];

				//$this->m_timezone_offset = $timetables->getZoneOffset($place->zone);
				//$this->m_summertime_offset = $timetables->getTypeOffset($place->type);

				$TimeZoneArray["m_timezone_offset"] = ($zone/900);
				$TimeZoneArray["m_summertime_offset"] = $time_types[$type];
			}
		}

		return $TimeZoneArray;
	}

	public function GetQuestionPrice($priority,$currency_id) {
		$obj = new cDatabase();
		$sql = 'SELECT cp.question_price_id ,cp.price ,cp.priority ,cp.currency_id ,cp.status , c.name, c.symbol, c.code, cp.time_mode, cp.time_value
				FROM `question_price` cp
				left join currency c on cp.currency_id = c.currency_id';
		$where = "  where cp.currency_id=".$currency_id." and cp.priority=".$priority;
		$sql = $sql.$where;
		$query = $obj->db->query($sql);

		return $query->rows;
	}

	public function makeQuestionsReject($questionIds,$astrologer_id) {
		//echo $questionIds;
		$obj = new cDatabase();
		$sql = " update `questions`  SET  ";
		$sql .= "	is_block = 0 , status = 2";
		$sql .= " where question_id in ('".$questionIds."')";

		$query = $obj->db->query($sql);
		if($query) {
			$sql = " delete from  `question_to_astrologer`    ";
			$sql .= " where question_id in ('".$questionIds."')";

			$query = $obj->db->query($sql);
			$question_id = 1;

			$sql = " Insert into `question_reject`  SET
					question_id = '" . $obj->db->escape($questionIds) . "',
							astrologer_id = '" . $obj->db->escape($astrologer_id) . "'";
			$query = $obj->db->query($sql);
		}
		else {
			$question_id = 0;
		}
		return $question_id;
	}

	public function GetCommissionMonthWiseByAstrologerId($astrologer_id) {
		$obj = new cDatabase();

		$sql = "SELECT q.question_id, q.price,q.question_time, ROUND(sum(q.price*0.7),2) as 'totel', qa.astrologer_id,
				ap.first_name,ap.last_name,MONTHNAME(q.question_time) as 'month', count(q.question_id) as 'total_question',
				o.order_id, o.currency_code, q.priority, MONTH(q.question_time) as 'month_count',
				YEAR(q.question_time) as 'year_count',
				ROUND(SUM( IF( o.currency_code = 'DKK',(q.price*0.7),0)),2) as 'DKK',
				ROUND(SUM( IF( o.currency_code = 'USD',(q.price*0.7),0)),2) as 'USD',
				ROUND(SUM( IF( o.currency_code = 'EUR',(q.price*0.7),0)),2) as 'EUR',
				ROUND(SUM( IF( o.currency_code = 'GBP',(q.price*0.7),0)),2) as 'GBP'
				FROM `questions` q
				left join question_to_astrologer qa on qa.question_id = q.question_id
				left join astrologer_profile ap on ap.astrologer_id = qa.astrologer_id
				left join `order` o on o.order_id = q.order_id
				left join currency c on c.code = o.currency_code";

		$where = " where status = 4  and (MONTH(q.question_time) <=   MONTH(NOW())) ";
		$where .= "and qa.astrologer_id ='".$astrologer_id."' GROUP BY MONTH(q.question_time)";
		$sql = $sql.$where;
		$query = $obj->db->query($sql);

		return $query->rows;
	}

	public function GetCommissionMonthAndYearWiseByAstrologerId($astrologer_id,$year,$month) {
		$obj = new cDatabase();

		$sql = "SELECT q.question_id, q.price, q.question_time, (q.price * 0.7 ) AS 'totel',  qa.astrologer_id, ap.first_name,
						ap.last_name, concat(MONTHNAME( q.question_time ),', ',YEAR( q.question_time ) )AS 'month', o.order_id, o.currency_code, q.priority, q.question,
						ROUND(IF( o.currency_code = 'DKK', (q.price * 0.7), 0 ),2) AS 'DKK',
						ROUND(IF( o.currency_code = 'USD', (q.price * 0.7), 0 ),2) AS 'USD',
						ROUND(IF( o.currency_code = 'EUR', (q.price * 0.7), 0 ),2) AS 'EUR',
						ROUND(IF( o.currency_code = 'GBP', (q.price * 0.7), 0 ),2) AS 'GBP'
				FROM `questions` q
						LEFT JOIN question_to_astrologer qa ON qa.question_id = q.question_id
						LEFT JOIN astrologer_profile ap ON ap.astrologer_id = qa.astrologer_id
						LEFT JOIN `order` o ON o.order_id = q.order_id
						LEFT JOIN currency c ON c.code = o.currency_code";
		$where = " WHERE q.status = 4 AND q.is_block IN (0, 1) AND qa.astrologer_id =".$astrologer_id;
		
		if(!empty($month)) {
			$where .= " and MONTH( q.question_time ) = ".$month;
		}
		if(!empty($year)) {
			$where .= " and YEAR(q.question_time) =".$year;
		}
		
		$orderBy = ' ORDER BY q.question_time DESC';
		$sql = $sql.$where.$orderBy;
		$query = $obj->db->query($sql);

		return $query->rows;
	}

	public function GetAllQuestions($astrologer_id,$items_per_page,$start) {
		try {
			$obj = new cDatabase();
			$sql = "SELECT q.question_id,q.question,q.status,q.is_block,q.user_id,q.language,q.priority,up.FirstName,up.LastName,
					rl.name, q.question_time, qt.assign_time, qt.question_answer_time_limit , 'time_remaining', qt.question_time , q.question_time as 'question_ask_time'
					FROM `questions` q
					left join user u on u.UserId = q.user_id
					left join userprofile up on up.UserId = q.user_id
					left join `report_languages` rl on rl.report_language_id = q.language
						
					left join question_to_astrologer qa on qa.question_id = q.question_id
					left join question_time qt on qt.question_id = q.question_id
					where q.status in (3,4) and q.is_block = 1 and astrologer_id = ".$astrologer_id."
							order by q.status asc limit ".$start.",".$items_per_page;
			$query = $obj->db->query($sql);
			return $query->rows;
		}
		catch(Exception $ex) {

		}
		$a = array();
		return $a;
	}

	public function GetDetailOfAstrologerById($astrologer_id) {
		try {
			$obj = new cDatabase();

			$sql = " SELECT a.astrologer_id, a.username,a.secondary_email,a.status , ap.astrologer_profile_id,ap.first_name,
					ap.last_name, ap.gender, ap.address, ap.city, ap.state, ap.country, ap.biography, ap.image, ap.timezone, ap.on_vacation,
					ap.contact_number, ac.contact_id, ac.contact_mode, ac.detail, f.path,f.name, f.file_id
					, GROUP_CONCAT(rl.name) as language, GROUP_CONCAT(rl.report_language_id) as language_id
					FROM `astrologer` a
					left join astrologer_profile ap on ap.astrologer_id = a.astrologer_id
					left join astrologer_contact_on_emergency ac on ac.astrologer_id = a.astrologer_id
					left join files f on f.file_id = ap.image
					left join `astrologer_language` al on al.`astrologer_id` = a.`astrologer_id`
					left join `report_languages` rl on rl.report_language_id = al.`language_id` ";
			$where = " WHERE a.astrologer_id = ".$astrologer_id;
			$where .= "  GROUP BY a.`astrologer_id`";
			$sql = $sql.$where;
			$query = $obj->db->query($sql);
			return $query->rows;
		}
		catch(Exception $ex) {
			//$userDetail->resultState->code=1;
			//$userDetail->resultState->message=$ex;
			die($ex->getMessage());
		}
	}

	public function SaveAstrologerLanguage($language, $astrologer_id) {

		try {
			$obj = new cDatabase();

			$sql = " delete from astrologer_language where astrologer_id =".$astrologer_id;
			$query = $obj->db->query($sql);

			for($i=0;$i<count($language);$i++) {
				$sql = " Insert into astrologer_language ";
				$sql .= " (astrologer_id ,	language_id)";
				$sql .= " values(
			  	'".$obj->db->escape($astrologer_id)."',
			  			'".$obj->db->escape($language[$i])."')";

				$query = $obj->db->query($sql);
			}

			return true;
		}
		catch(Exception $ex) {
			//$userDetail->resultState->code=1;
			//$userDetail->resultState->message=$ex;
			die($ex->getMessage());
		}
	}

	public function UpdateEmergencyContactDetail($data) {
		try {
			$obj = new cDatabase();

			$sql = "DELETE FROM `astrologer_contact_on_emergency` WHERE astrologer_id = '".$obj->db->escape($data['AstrologerId'])."'";
			$query = $obj->db->query($sql);

			//            $sql =  " update astrologer_contact_on_emergency ";
			//            $sql .= " set
			//			contact_mode      = '".$obj->db->escape($data['mode_of_contact'])."',
			//			detail 		  = '".$obj->db->escape($data['txtContactDetail'])."' ";
			//            $sql .= " where astrologer_id = '".$obj->db->escape($data['AstrologerId'])."'";
			$sql = " Insert into `astrologer_contact_on_emergency`  SET
					contact_mode    = '" . $obj->db->escape($data['mode_of_contact']) . "',
							detail          = '" .       $obj->db->escape($data['txtContactDetail']) . "',
									astrologer_id   = '".$obj->db->escape($data['AstrologerId'])."'";

			$query = $obj->db->query($sql);

			if($query) {
				$astrologer_id = $obj->db->getLastId();
			}
			else {
				$astrologer_id = 0;
			}
			return $astrologer_id;
		}
		catch(Exception $ex) {
			die($ex->getMessage());
			return false;
		}
	}

	public function UpdateAlternateEmailAddress($email,$astrologer_id) {
		try {
			$obj = new cDatabase();

			$sql = " update astrologer ";
			$sql .= " set ";
			$sql .= " secondary_email ='".$obj->db->escape($email)."'";
			$sql .= " where astrologer_id = '".$obj->db->escape($astrologer_id)."'";

			$query = $obj->db->query($sql);

			if($query) {
				$astrologer_id = $astrologer_id;
			}
			else {
				$astrologer_id = 0;
			}
			return $astrologer_id;
		}
		catch(Exception $ex) {
			die($ex->getMessage());
			return false;
		}
	}

	public function GetRejectedQuestionCount($astrologer_id) {
		$obj = new cDatabase();
		$sql = "SELECT count(question_id) as count
				FROM `question_reject`
				where  astrologer_id = ".$astrologer_id;
		$query = $obj->db->query($sql);
		return $query->row['count'];
	}

	/**
	 * GetBirthDetailByQuestionId() returns the Birth Data
	 * @param Int $QuestionId
	 * @return Birth Detail Object
	 */
	public function GetBirthDetailByQuestionId($QuestionId) {
		$obj = new cDatabase();

		$sql = "SELECT * FROM `question_user_birth_detail` WHERE question_id = $QuestionId";
		$query = $obj->db->query($sql);

		return $query->rows;
	}

	/**
	 * GetHororaryDetailByQuestionId() returns the Birth Data
	 * @param Int $QuestionId
	 * @return Birth Detail Object
	 */
	public function GetHororaryDetailByQuestionId($QuestionId) {
		$obj = new cDatabase();

		$sql = "SELECT * FROM `question_user_horory_detail` WHERE question_id = $QuestionId";
		$query = $obj->db->query($sql);

		return $query->rows;
	}

	public function GetContactModeByForAstrologer($AstrologerId) {
		$obj = new cDatabase();

		$sql = "SELECT * FROM `astrologer_contact_on_emergency` WHERE astrologer_id = $AstrologerId";
		$query = $obj->db->query($sql);

		return $query->rows;
	}	

	public function GetCurrencyListByLanguage($LanguageID) {
		$obj = new cDatabase();
		$sql = "SELECT c.currency_id, c.name ,c.symbol, c.code, cl.isdefault  "; 
		$sql .= "FROM currency c, `question_price` cp, `currency_to_language` cl, `language` l ";
		$where = "WHERE cl.currency_id = c.currency_id AND cl.currency_id = cp.currency_id AND cp.currency_id = c.currency_id AND";
		$where .= " l.language_id = cl.language_id AND l.code = '".$LanguageID."'";
		$groupby = 'GROUP BY c.name ORDER BY c.currency_id';
		
		$sql = $sql.$where.$groupby ;
		$query = $obj->db->query($sql);
	
		return $query->rows;
	}
}
?>