<?php
require_once("resultStateDTO.php");

class userDTO
{
	function __construct() {
      $this->language="en";
	  $this->user_id=0;
	  $this->status='';	
	  $this->resultState = new ResultStateDTO();
	  $this->user_group=0;
	  $this->portal_id=0;
	  $this->user_name='';
	  $this->password='';
	  $this->preview_report='';
	  $this->parent_user_id='';
	   	
   }
   
   public $language;
   public $user_id;
   public $status; 
   public $resultState; 
   public $user_group; 
   public $portal_id; 
   public $user_name; 
   public $password; 
   public $preview_report; 
   public $parent_user_id; 
}

class userProfileDTO
{
	function __construct() {
		$this->UserProfileId='';
		$this->UserId='';
		$this->FirstName='';
		$this->LastName='';
		$this->Gender='';
		$this->Phone='';
		$this->Address='';
		$this->city='';
		$this->state='';
		$this->country='';
		$this->Zip='';
		$this->CreatedDate='';
		$this->ModifiedDate='';
		$this->CreatedBy='';
		$this->ModifiedBy='';
	}
	
	public $UserProfileId;
	public $UserId;
	public $FirstName;
	public $LastName;
	public $Gender;
	public $Phone;
	public $Address;
	public $city;
	public $state;
	public $country;
	public $Zip;
	public $CreatedDate;
	public $ModifiedDate;
	public $CreatedBy;
	public $ModifiedBy;
}

class userBirthDetailDTO
{
	function __construct() {
		$this->UserBirthDetailId='';
		$this->UserId='';
		$this->Day='';
		$this->Month='';
		$this->Year='';
		$this->Hours='';
		$this->Minutes='';
		$this->Seconds='';
		$this->unTimed='';
		$this->GMT='';
		$this->zoneref='';
		$this->summerref='';
		$this->longitude='';
		$this->latitude='';
		$this->CreatedDate='';
		$this->ModifiedDate='';
		$this->CreatedBy='';
		$this->ModifiedBy='';
		
		$this->country='';
		$this->state='';
		$this->city='';
		$this->country_name='';
		$this->sunsign='';
	}
	
	
	public $UserBirthDetailId;
	public $UserId;
	public $Day;
	public $Month;
	public $Year;
	public $Hours;
	public $Minutes;
	public $Seconds;
	public $unTimed;
	public $GMT;
	public $zoneref;
	public $summerref;
	public $longitude;
	public $latitude;
	public $CreatedDate;
	public $ModifiedDate;
	public $CreatedBy;
	public $ModifiedBy;
	
	public $country;
	public $state;
	public $city;
	public $country_name;
	public $sunsign;
}


class userHororyDetailDTO
{
	function __construct() {
		$this->UserHororyId='';
		$this->UserId='';
		$this->Address='';
		$this->city='';
		$this->state='';
		$this->country='';
		$this->longitude='';
		$this->latitude='';
		$this->country_name='';
		
		$this->zoneref='';
		$this->summerref='';
		$this->Day='';
		$this->Month='';
		$this->Year='';
		$this->Hours='';
		$this->Minutes='';
		$this->Seconds='';
	}
	
	public $UserHororyId;
	public $UserId;
	public $Address;
	public $city;
	public $state;
	public $country;
	public $longitude;
	public $latitude;
	public $country_name;
	
	public $zoneref;
	public $summerref;
	public $Day;
	public $Month;
	public $Year;
	public $Hours;
	public $Minutes;
	public $Seconds;
	
}
?>