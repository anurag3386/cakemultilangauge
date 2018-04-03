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
   }
   
   public $language;
   public $user_id;
   public $status; 
   public $resultState; 
   public $user_group; 
   public $portal_id; 
   public $user_name; 
   public $password; 
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
		$this->City='';
		$this->State='';
		$this->Country='';
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
	public $City;
	public $State;
	public $Country;
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
		$this->ZoneRef='';
		$this->SummerTimeZoneRef='';
		$this->Longitute='';
		$this->Lagitute='';
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
	public $ZoneRef;
	public $SummerTimeZoneRef;
	public $Longitute;
	public $Lagitute;
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
		$this->City='';
		$this->State='';
		$this->Country='';
		$this->Longitute='';
		$this->Lagitute='';
		$this->country_name='';
	}
	
	public $UserHororyId;
	public $UserId;
	public $Address;
	public $City;
	public $State;
	public $Country;
	public $Longitute;
	public $Lagitute;
	public $country_name;
	
}
?>