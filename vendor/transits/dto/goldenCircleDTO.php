<?php

class GoldenCircleDTO
{

}


class QuestionDTO
{
	function __construct() {
      $this->question_id='0';
	  $this->question='';
	  $this->price='';
	  $this->question_time='';
	  $this->status='';
	  $this->is_block='0';
	  $this->user_id='0';
	  $this->email='';
	  $this->name='';
	  $this->language='';
	  $this->priority='0';
	  $this->order_id='0';
	  $this->price_id='0';
	}
	
	public $question_id;
	public $question;
	public $price;
	public $question_time;
	public $status;
	public $is_block;
	public $user_id;
	public $email;
	public $name;
	public $language;
	public $priority;
	public $order_id; 	
	public $price_id;
}


class QuestionUserBirthDetailDTO
{
	function __construct() {
      $this->question_birth_detail_id='0';
	  $this->question_id='0';
	  $this->name='';
	  $this->birth_date='';
	  $this->hours='';
	  $this->minute='';
	  $this->untimed='';
	  $this->country='';
	  $this->state='';
	  $this->city='';
	  $this->timezone='';
	  $this->sunsign='';
	  $this->wheel_image_id='0';
	  $this->longitude='';
	  $this->latitude='';
	  $this->zoneRef='';
	  $this->summerTimeZoneRef='';
	  $this->country_name='';
	}
	
	public $question_birth_detail_id;
	public $question_id;
	public $name;
	public $birth_date;
	public $hours;
	public $minute;
	public $untimed;
	public $country;
	public $state;
	public $city;
	public $timezone;
	public $sunsign;
	public $wheel_image_id;
	public $longitude;
	public $latitude;
	public $zoneRef;
	public $summerTimeZoneRef;
	public $country_name;
}


class QuestionHororyDetailDTO
{
	function __construct() {
      $this->question_horory_detail_id='0';
	  $this->question_id='0';
	  $this->country='';
	  $this->state='';
	  $this->city='';
	  $this->timezone='';
	  $this->horory_image_id='0';
	  $this->longitude='';
	  $this->latitude='';
	  $this->year='';
	  $this->month='';
	  $this->day='';
	  $this->hours='';
	  $this->minute='';
	  $this->second='';
	  $this->zoneRef='';
	  $this->summerTimeZoneRef='';
	  $this->country_name='';
	}
	
	public $question_horory_detail_id;
	public $question_id;
	public $country;
	public $state;
	public $city;
	public $timezone;
	public $horory_image_id;
	public $longitude;
	public $latitude;
	public $year;
	public $month;
	public $day;
	public $hours;
	public $minute;
	public $second;
	public $zoneRef;
	public $summerTimeZoneRef;
	public $country_name;
}


class QuestionTimeDTO
{
	function __construct() {
      $this->question_time_id='0';
	  $this->question_id='0';
	  $this->time_limit='0';
	  $this->answer_time_limit='0';
	  $this->time_remaining='0';
	}
	
	public $question_time_id;
	public $question_id;
	public $time_limit;
	public $answer_time_limit;
	public $time_remaining;
}




class AnswerDTO
{
	function __construct() {
      $this->answer_id='0';
	  $this->question_id='0';
	  $this->answer='';
	  $this->astrologer_id='0';
	  $this->answered_in_time='0';
	}
	
	public $answer_id;
	public $question_id;
	public $answer;
	public $astrologer_id;
	public $answered_in_time;
}


	
	
?>