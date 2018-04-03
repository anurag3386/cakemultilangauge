<?php

if (!class_exists('cDatabase')) {
	if(!include("cDatabase.php")) {
		require_once("../cDatabase.php");
	}
}

class EliteUserRepository {
	public function IsEmailAvailable($EMail) {
		$obj = new cDatabase();
		if(!empty($EMail)) {
			$SQL = " SELECT UserId FROM user ";
			$WHERE = " WHERE UserName = '".$EMail."' AND UserGroupId = 8";
			$SQL = $SQL.$WHERE;			
			$query = $obj->db->query($SQL);
			return $query->rows;
		}
		else {
			return false;
		}
	}

	public function SaveUser($data) {
		$obj = new cDatabase();
		$sql = " Insert into user ";
		$sql .=" (UserName,Password,Status,DefaultLanguage,PortalId,UserGroupId,CreatedDate,ModifiedDate,CreatedBy,ModifiedBy) ";
		$sql .=" value(";
		$sql .=" '".$obj->db->escape($data->user_name)."', ";
		$sql .=" '".$obj->db->escape($data->password)."', ";
		$sql .=" '".$data->status."', ";
		$sql .=" '".$data->language."', ";
		$sql .=" '".$data->portal_id."', ";
		$sql .=" '".$data->user_group."', ";
		$sql .=" '".date('Y-m-d h:i:s')."', ";
		$sql .=" '".date('Y-m-d h:i:s')."', ";
		$sql .=" '1', ";
		$sql .=" '1' ";
		$sql .=" )";
			

		$query = $obj->db->query($sql);
		if($query) {
			$userId = $obj->db->getLastId();
		}
		else {
			$userId = 0;
		}
		return $userId;
	}

	public function validateUserLogon($userName,$password) {
		try {
			$obj = new cDatabase();

			$sql = "select UserId,Status,UserGroupId,UserName from user where UserName = '".$userName."' and Password = '".$password."' AND UserGroupId = 8";

			$query = $obj->db->query($sql);
			return $query->row;
		}
		catch(Exception $ex) {
			//$userDetail->resultState->code=1;
			//echo $userDetail->resultState->message=$ex;
		}
		return array();
		//return $userDetail;
	}

	public function SaveUserProfile($data) {
		$obj = new cDatabase();
		$sql = " Insert into userprofile ";
		$sql .=" (UserId, FirstName, LastName, Gender, Phone, Address, City, State, Country, Zip, CreatedDate, ModifiedDate, CreatedBy, ModifiedBy) ";
		$sql .=" value(";
		$sql .=" '".$data->UserId."', ";
		$sql .=" '".$obj->db->escape($data->FirstName)."', ";
		$sql .=" '".$obj->db->escape($data->LastName)."', ";
		$sql .=" '".$data->Gender."', ";
		$sql .=" '".$obj->db->escape($data->Phone)."', ";
		$sql .=" '".$obj->db->escape($data->Address)."', ";
		$sql .=" '".$obj->db->escape($data->city)."', ";
		$sql .=" '".$obj->db->escape($data->state)."', ";
		$sql .=" '".$obj->db->escape($data->country)."', ";
		$sql .=" '".$obj->db->escape($data->Zip)."', ";
		$sql .=" '".date('Y-m-d')."', ";
		$sql .=" '".date('Y-m-d')."', ";
		$sql .=" '1', ";
		$sql .=" '1' ";
		$sql .=" )";

		$query = $obj->db->query($sql);
		if($query) {
			$UserProfileId = $obj->db->getLastId();
		}
		else {
			$UserProfileId = 0;
		}
		return $UserProfileId;
	}

	public function SaveUserBirthDetail($data) {
		$data = $this->GetLocationInformation($data);

		$obj = new cDatabase();
		$sql = " Insert into userbirthdetail ";
		$sql .=" (UserId, Day, Month, Year, Hours, Minutes, Seconds, unTimed, GMT, ZoneRef,SummerTimeZoneRef,Longitute,Lagitute, CreatedDate, ModifiedDate, CreatedBy, ModifiedBy, country_name,sunsign,city ,country,state) ";
		$sql .=" value(";
		$sql .=" '".$data->UserId."', ";
		$sql .=" '".$data->Day."', ";
		$sql .=" '".$data->Month."', ";
		$sql .=" '".$data->Year."', ";
		$sql .=" '".$data->Hours."', ";
		$sql .=" '".$data->Minutes."', ";
		$sql .=" '".$data->Seconds."', ";
		$sql .=" '".$data->unTimed."', ";
		$sql .=" '".$data->GMT."', ";
		$sql .=" '".$data->zoneref."', ";
		$sql .=" '".$data->summerref."', ";
		$sql .=" '".$data->longitude."', ";
		$sql .=" '".$data->latitude."', ";
		$sql .=" '".date('Y-m-d')."', ";
		$sql .=" '".date('Y-m-d')."', ";
		$sql .=" '1', ";
		$sql .=" '1', ";
		$sql .=" '".$obj->db->escape($data->country_name)."', ";
		$sql .=" '".$data->sunsign."' ,";
		$sql .=" '".$obj->db->escape($data->city)."' ,";
		$sql .=" '".$obj->db->escape($data->country)."' ,";
		$sql .=" '".$obj->db->escape($data->state)."' ";
		$sql .=" )";

		$query = $obj->db->query($sql);
		if($query) {
			$UserBirthDetailId = $obj->db->getLastId();
		}
		else {
			$UserBirthDetailId = 0;
		}
		return $UserBirthDetailId;
	}

	public function SaveUserHororyDetail($data) {
		$data = $this->GetLocationInformation($data);

		$obj = new cDatabase();
		$sql = " Insert into userhorory ";
		$sql .=" (UserId, Address, City, State, Country, CreatedDate, ModifiedDate, CreatedBy, ModifiedBy, Longitute, Lagitute, country_name) ";
		$sql .=" value(";
		$sql .=" '".$data->UserId."', ";
		$sql .=" '".$obj->db->escape($data->Address)."', ";
		$sql .=" '".$obj->db->escape($data->city)."', ";
		$sql .=" '".$obj->db->escape($data->state)."', ";
		$sql .=" '".$obj->db->escape($data->country)."', ";
		$sql .=" '".date('Y-m-d')."', ";
		$sql .=" '".date('Y-m-d')."', ";
		$sql .=" '1', ";
		$sql .=" '1' ,";
		$sql .=" '".$data->longitude."', ";
		$sql .=" '".$data->latitude."', ";
		$sql .=" '".$obj->db->escape($data->country_name)."' ";
		$sql .=" )";

		$query = $obj->db->query($sql);
		if($query) {
			$UserHororyDetailId = $obj->db->getLastId();
		}
		else {
			$UserHororyDetailId = 0;
		}
		return $UserHororyDetailId;
	}

	public function UpdateUserProfile($data) {
		$obj = new cDatabase();
		$sql = " update userprofile set ";
		$sql .="FirstName = '".$data->FirstName."',
				LastName = '".$data->LastName."',
				Gender = '".$data->Gender."',
				Phone = '".$data->Phone."',
				Address = '".$data->Address."',
				City = '".$data->city."',
				State = '".$data->state."',
				Country = '".$data->country."',
				Zip = '".$data->Zip."',
				ModifiedDate = '".date('Y-m-d')."',
				ModifiedBy = '".$data->UserId."'";
		$sql .=" where UserProfileId =".$data->UserProfileId." and UserId = ".$data->UserId;


		$query = $obj->db->query($sql);
		if($query) {
			return true;
		}
		else {
			return false;
		}
	}

	public function UpdateUserBirthDetail($data) {
		$data = $this->GetLocationInformation($data);

		//print_r($data);
		$obj = new cDatabase();
		$sql = " update userbirthdetail set";
		$sql .=" Day = '".$data->Day."',
				Month = '".$data->Month."',
				Year = '".$data->Year."',
				Hours = '".$data->Hours."',
				Minutes = '".$data->Minutes."',
				Seconds = '".$data->Seconds."',
				unTimed = '".$data->unTimed."',
				GMT = '".$data->GMT."',
				ZoneRef = '".$data->zoneref."',
				SummerTimeZoneRef = '".$data->summerref."',
				Longitute = '".$data->longitude."',
				Lagitute = '".$data->latitude."',
				city = '".$data->city."',
				country = '".$data->country."',
				ountry_name = '".$data->country_name."',
				state = '".$data->state."',
				ModifiedDate = '".date('Y-m-d')."',
				sunsign = 	'".$data->sunsign."',
				ModifiedBy = '".$data->UserId."' ";
		$sql .=" WHERE UserId = ".$data->UserId." and UserBirthDetailId=".$data->UserBirthDetailId;

		$query = $obj->db->query($sql);

		if($query) {
			return true;
		}
		else {
			return false;
		}
	}

	public function UpdateUserHororyDetail($data) {
		$data = $this->GetLocationInformation($data);

		$obj = new cDatabase();

		$sql = " Update userhorory set";
		$sql .="Address = '".$data->Address."',
				City = '".$data->city."',
				State = '".$data->state."',
				Country = '".$data->country."',
				Longitute = '".$data->longitude."',
				Lagitute = '".$data->latitude."',
				ModifiedDate = '".date('Y-m-d')."',
				country_name = '".$data->country_name."',
				ModifiedBy = '".$data->UserId."'";
		$sql .=" where UserHororyId =".$data->UserHororyId." and UserId = ".$data->UserId;

		$query = $obj->db->query($sql);
		if($query) {
			return true;
		}
		else {
			return false;
		}
	}

	public function UpdateUserPassword($password,$email) {
		$obj = new cDatabase();
		$sql = " update user set";
		$sql .=" Password = '".$password."'";
		$sql .=" where UserName = ".$email;
		$query = $obj->db->query($sql);
		if($query) {
			return true;
		}
		else {
			return false;
		}

	}

	public function GetUserProfileDetailByUserId($user_id) {
		$obj = new cDatabase();

		$sql =" SELECT UserProfileId,UserId, FirstName, LastName, Gender, Phone, Address, City, State, Country, Zip, CreatedDate, ModifiedDate, CreatedBy, ModifiedBy from  userprofile";
		$sql .=" where UserId = ".$user_id;

		$query = $obj->db->query($sql);
		return $query->rows;
	}

	public function GetUserDetail($user_id) {
		$obj = new cDatabase();

		$sql = " SELECT UserId,UserName,Status,DefaultLanguage,PortalId,UserGroupId from user";
		$sql .=" where UserId = ".$user_id;

		$query = $obj->db->query($sql);
		return $query->rows;
	}

	public function GetUserHororyDetailByUserId($user_id) {
		$obj = new cDatabase();

		$sql =" SELECT UserHororyId, Address,City,State,Country, Longitute, Lagitute, country_name FROM userhorory";
		$sql .=" where UserId = ".$user_id;

		$query = $obj->db->query($sql);
		return $query->rows;
	}

	public function GetUserBirthDetailByUserId($user_id) {
		$obj = new cDatabase();

		$sql ="SELECT UserBirthDetailId,UserId,`Day`,`Month`,`Year`,`Hours`,`Minutes`,`Seconds`,unTimed,GMT, ZoneRef,
				SummerTimeZoneRef, Longitute, Lagitute, country,state,city, country_name, sunsign FROM userbirthdetail";
		$sql .=" where UserId = ".$user_id;

		$query = $obj->db->query($sql);
		return $query->rows;
	}

	public function SaveUserOptin($user_id,$data) {
		$obj = new cDatabase();

		$sql = " delete from user_optin where user_id=".$user_id;
		$query = $obj->db->query($sql);

		$option = explode(",", $data);
		for($i=0;$i<count($option);$i++) {
			if(!empty($option[$i]))
			{
				//$obj = new cDatabase();
				$sql = " Insert into user_optin ";
				$sql .=" (user_id, optin_option_id) ";
				$sql .=" value(";
				$sql .=" '".$user_id."' ,";
				$sql .=" '".$option[$i]."' ";
				$sql .=" )";

				$query = $obj->db->query($sql);
				if($query) {
					$UserHororyDetailId = $obj->db->getLastId();
				}
				else {
					$UserHororyDetailId = 0;
				}
			}
		}
		return true;
	}

	public function GetUserDetailForMail($user_id) {
		$obj = new cDatabase();

		$sql ="select u.UserName,up.FirstName, up.LastName, up.Gender
				from user u
				left join userprofile up on up.UserId = u.UserId";
		$sql .=" where u.UserName = '".$user_id."'";

		$query = $obj->db->query($sql);
		return $query->rows;
	}

	public function CheckPassword($user_id, $password) {
		$obj = new cDatabase();
		$sql = "SELECT count(UserId) as count
				FROM `user`
					
				where Password = '".$password."' and UserId = ".$user_id." ";
		$query = $obj->db->query($sql);
		return $query->row['count'];
	}

	public function ChangePassword($user_id, $password) {
		$obj = new cDatabase();
		$sql = " update `user`  SET  Password = '".$password."' where UserId = ".$user_id."";

		$query = $obj->db->query($sql);
		if($query) {
			$result = 1;
		}
		else {
			$result = 0;
		}
		return $result;
	}

	public function updatePassword($email, $password) {
		$obj = new cDatabase();
		$sql = " update `user`  SET  Password = '".$password."' where UserName = '".$email."'";

		$query = $obj->db->query($sql);
		if($query) {
			$result = 1;
		}
		else {
			$result = 0;
		}
		return $result;
	}

	public function GetUserToAssignPreviewReport() {
		$obj = new cDatabase();
		$sql = " SELECT UserId FROM `user` WHERE `preview_report` = '' AND UserId !=1";

		$query = $obj->db->query($sql);
		return $query->rows;
	}

	public function UpdateUserToAssignPreviewReport() {
		$obj = new cDatabase();
		$sql = " update `user`  SET  Password = '".$password."' where UserName = '".$email."'";

		$query = $obj->db->query($sql);
	}

	public function SavePerson($data,$user_id) {
		$obj = new cDatabase();
		$sql = " Insert into user ";
		$sql .=" (UserName,Password,Status,DefaultLanguage,PortalId, UserGroupId, CreatedDate, ModifiedDate, CreatedBy, ModifiedBy, preview_report, parent_user_id) ";
		$sql .=" value(";
		$sql .=" '".$data->user_name."', ";
		$sql .=" '".$data->password."', ";
		$sql .=" '".$data->status."', ";
		$sql .=" '".$data->language."', ";
		$sql .=" '".$data->portal_id."', ";
		$sql .=" '".$data->user_group."', ";
		$sql .=" '".date('Y-m-d')."', ";
		$sql .=" '".date('Y-m-d')."', ";
		$sql .=" '1', ";
		$sql .=" '1', ";
		$sql .=" '-1', ";
		$sql .=" '".$user_id."' ";
		$sql .=" )";

		$query = $obj->db->query($sql);
		if($query) {
			$person_id = $obj->db->getLastId();
		}
		else {
			$person_id = 0;
		}
		return $person_id;
	}

	public function GetAnotherPersonListByUserId($user_id) {
		$obj = new cDatabase();

		$sql = "select a.UserId, b.FirstName, b.LastName , (select user_id from subscription_user where user_id = a.UserId and status = 5) as subscribe from user a
				left join userprofile b on b.UserId = a.UserId
				where a.UserId = ".$user_id." or parent_user_id =".$user_id. " order by a.UserId";
		$query = $obj->db->query($sql);
		return $query->rows;
	}

	public function GetPersonDetailByPersonId($person_id) {
		$obj = new cDatabase();

		$sql = "select a.UserId, a.UserName, b.UserProfileId,b.FirstName,b.LastName,b.Gender,
				c.UserBirthDetailId,c.Day,c.Month,c.Year,c.Hours,c.Minutes 	,c.Seconds 	,c.unTimed 	,c.GMT 	,c.ZoneRef
				,c.SummerTimeZoneRef 	,c.Longitute 	,c.Lagitute 	,c.CreatedDate ,c.ModifiedDate 	,c.CreatedBy
				,c.ModifiedBy 	,c.country 	,c.state 	,c.city 	,c.country_name 	,c.sunsign
				from user a
				left join userprofile b on b.UserId = a.UserId
				left join userbirthdetail c on c.UserId = a.UserId
				where a.UserId = ".$person_id;
		$query = $obj->db->query($sql);
		return $query->rows;
	}


	public function GetUserDetailByUserId($user_id) {
		$obj = new cDatabase();

		$sql = "SELECT u.UserId,u.UserName,u.Status,u.DefaultLanguage,u.PortalId,u.UserGroupId,up.FirstName,up.LastName, up.Gender from `user` u";
		$sql .=" left join userprofile up on up.UserId = u.UserId ";
		$sql .=" where u.UserId = ".$user_id;

		$query = $obj->db->query($sql);
		return $query->rows;
	}

	public function GetLocationInformation($data) {
		$Longitude = $data->longitude;
		$Latitude = $data->latitude;

		if($Latitude < -90 && $Latitude > 90) {
			$Latitude = $Latitude * 3600;
			//$data->latitude = $Latitude;
		}

		if($Longitude < -180 && $Longitude > 180) {
			$Longitude = $Longitude * 3600;
			//$data->longitude = $Longitude;
		}

		$sql =  "select * from `acsatlas`".
				" where upper(placename)='".strtoupper($data->city)."' ".
				" and longitude ='".$Longitude."'".
				" and latitude ='".$Latitude."'".
				" order by lkey";
		$ACSRep = new  ACSRepository();

		$Location = sprintf( "%s, %s",	$data->city, $data->country_name);

		$IsThere = $this->GetSummerTimeZoneANDTimeZone($Location, $data);

		if(count($IsThere) > 0 ) {
			$data->zoneref = $IsThere['m_timezone_offset'];
			$data->summerref = $IsThere['m_summertime_offset'];
		}
		else {
			$Result = $ACSRep->GetACSDataRow($sql);

			if($Result) {
				$acsTimeTable = new AcsTimetables();
				$acsTimeTable->setBirthdate( sprintf("%04d-%02d-%02d %02d:%02d:%02d",
						$data->Year, $data->Month, $data->Day,
						$data->Hours, $data->Minutes, 0) );

				$data->zoneref = $acsTimeTable->getZoneOffset($Result[0]['zone']);
				$data->summerref = $acsTimeTable->getTypeOffset($Result[0]['type']);

			}
		}

		return $data;
	}

	public function GetSummerTimeZoneANDTimeZone($location, $data) {
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
			//            $time_info = acs_time_change_lookup($this->m_birth_month, $this->m_birth_day, $this->m_birth_year,
			//                    $this->m_birth_hour, $this->m_birth_minute, $zonetable, $typetable);

			$time_info = acs_time_change_lookup($data->Month, $data->Day, $data->Year,
					$data->Hours, $data->Minutes, $zonetable, $typetable);

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

				$TimeZoneArray["m_timezone_offset"] = number_format(floatval( ($zone/900) ), 2);
				$TimeZoneArray["m_summertime_offset"] = number_format( floatval( $time_types[$type] ), 2);
			}
		}

		return $TimeZoneArray;
	}

	public function GetUserOptinDetailByUserEmail($email)
	{
		$obj = new cDatabase();

		$sql = "SELECT o.optin_option_id, o.user_id
				FROM `user_optin` o
				left join user u on u.UserId = o.user_id
				where u.UserName = '".$email."'";
		$query = $obj->db->query($sql);
		return $query->rows;
	}

	public function SaveUserUnsubscribeDetail($user_id,$name_of_optin,$reason)
	{
		$obj = new cDatabase();
		$sql = "insert into user_unsubscribe_detail ( `user_id`, `name_of_optin`, `reason`,`date_added`)";
		$sql .=" value(";
		$sql .=" '".$user_id."', ";
		$sql .=" '".$name_of_optin."', ";
		$sql .=" '".$reason."', ";
		$sql .=" '".date('Y-m-d h:m:s')."' ";
		$sql .=" )";

		$query = $obj->db->query($sql);
		if($query)
		{
			$id = $obj->db->getLastId();
		}
		else
		{
			$id = 0;
		}
		return $id;
	}

	public function UpdateUserOptin($user_id,$data)
	{
		$obj = new cDatabase();
		$returnValue = 0;


		$option = explode(",", $data);
		for($i=0;$i<count($option);$i++)
		{
			if(!empty($option[$i]))
			{
				$sql = " delete from user_optin where user_id=".$user_id." and optin_option_id = ".$option[$i];
				$query = $obj->db->query($sql);
			}
			$returnValue = 1;
		}
		return $returnValue;
	}

	public function DeleteUserAccount($user_id)
	{
		$obj = new cDatabase();
		$sql = "update `user` set Status = 0 where UserId = ".$user_id;

		$query = $obj->db->query($sql);
		return $user_id;
	}
	 
	public function GetPasswordByUserName($userName)
	{
		//$userDetail = new userDTO();
		try
		{
			$obj = new cDatabase();
			//print_r($obj->db);
				
			$sql = "select u.UserId,u.Password, u.DefaultLanguage , up.FirstName, up.LastName from `user` u left join userprofile up on u.UserId = up.UserId where u.UserName = '".$userName."' and u.Status = 1";
			$query = $obj->db->query($sql);
			return $query->row;
		}
		catch(Exception $ex)
		{
			//$userDetail->resultState->code=1;
			//$userDetail->resultState->message=$ex;
		}
		return '';
		//return $userDetail;
	}

	public function SaveUserToken($user_id,$token)
	{
		try
		{
			$obj = new cDatabase();
				
			$sql = "insert into user_token set
					user_id = ".$user_id.",
							token ='".$token."'";
				
			$query = $obj->db->query($sql);
			if($query)
			{
				$id = $obj->db->getLastId();
			}
			else
			{
				$id = 0;
			}
			return $id;
		}
		catch(Exception $ex)
		{
			return 0;
		}
	}

	public function UpdateUserToken($user_id,$token_id)
	{
		try
		{
			$obj = new cDatabase();
				
			$sql = "update user_token set
					count = 1 where
					user_id = ".$user_id."
							and token_id =".$token_id;
				
			$query = $obj->db->query($sql);
			if($query)
			{
				$id = 1;
			}
			else
			{
				$id = 0;
			}
			return $id;
		}
		catch(Exception $ex)
		{
			return 0;
		}
	}

	public function GetUserToken($token)
	{
		try
		{
			$obj = new cDatabase();
				
			$sql = "select token_id,user_id,token from user_token where
					count = 0 and token ='".$token."'";
				
			$query = $obj->db->query($sql);
			return $query->row;
		}
		catch(Exception $ex)
		{
			return 0;
		}
	}

	public function UpdateUserGroup($UserId, $EMail, $GroupId) {
		$obj = new cDatabase();
		try
		{
			$sql = " UPDATE user SET";
			$sql .=" UserGroupId = '".$GroupId."'";			
			$sql .=" WHERE UserId = '" .$UserId . "'";
			//$sql .=" WHERE UserName = '".$obj->db->escape($EMail) ."' AND UserId = '" .$UserId . "'";			
			$query = $obj->db->query($sql);

			if($query) {				
				return true;
			}
			else {
				return false;
			}
		}
		catch(Exception $ex)
		{
			return false;
		}
	}

	public function GetUserIds($UserId){
		$obj = new cDatabase();

		try
		{
			$sql = "SELECT u.UserId, UserBirthDetailId, UserProfileId
					FROM `user` u, `userbirthdetail` ubd, `userprofile` up
					WHERE u.UserId = ubd.UserId AND up.UserId = ubd.UserId AND u.UserId = up.UserId AND u.UserId = " . $UserId;

			$query = $obj->db->query($sql);
			return $query->rows;
		}
		catch(Exception $ex)
		{
			return 0;
		}
	}


	public function GetUserDetailByUserIdForSignUp($UserId) {
		$obj = new cDatabase();

		$SQL = "SELECT u.UserId,u.UserName,u.Status,u.DefaultLanguage ";
		$SQL .= "FROM `user` u LEFT JOIN `membership_orders` mo on mo.user_id = u.UserId ";
		$WHERE = "WHERE u.UserGroupId = 8 AND mo.order_status = 9 AND u.UserId = $UserId ";
		$SQL = $SQL . $WHERE;
		$query = $obj->db->query($SQL);
		return $query->rows;
	}


	public function SaveUserBirthDetailStep1($data) {
		//$data = $this->GetLocationInformation($data);

		$obj = new cDatabase();
		$sql = " Insert into userbirthdetail ";
		$sql .=" (UserId, Day, Month, Year, Hours, Minutes, Seconds, unTimed, GMT, ZoneRef,SummerTimeZoneRef,Longitute,Lagitute, CreatedDate, ModifiedDate, CreatedBy, ModifiedBy, country_name,sunsign,city ,country,state) ";
		$sql .=" value(";
		$sql .=" '".$data->UserId."', ";
		$sql .=" '".$data->Day."', ";
		$sql .=" '".$data->Month."', ";
		$sql .=" '".$data->Year."', ";
		$sql .=" '".$data->Hours."', ";
		$sql .=" '".$data->Minutes."', ";
		$sql .=" '".$data->Seconds."', ";
		$sql .=" '".$data->unTimed."', ";
		$sql .=" '".$data->GMT."', ";
		$sql .=" '".$data->zoneref."', ";
		$sql .=" '".$data->summerref."', ";
		$sql .=" '".$data->longitude."', ";
		$sql .=" '".$data->latitude."', ";
		$sql .=" '".date('Y-m-d')."', ";
		$sql .=" '".date('Y-m-d')."', ";
		$sql .=" '1', ";
		$sql .=" '1', ";
		$sql .=" '".$obj->db->escape($data->country_name)."', ";
		$sql .=" '".$data->sunsign."' ,";
		$sql .=" '".$obj->db->escape($data->city)."' ,";
		$sql .=" '".$obj->db->escape($data->country)."' ,";
		$sql .=" '".$obj->db->escape($data->state)."' ";
		$sql .=" )";

		$query = $obj->db->query($sql);
		if($query) {
			$UserBirthDetailId = $obj->db->getLastId();
		}
		else {
			$UserBirthDetailId = 0;
		}
		return $UserBirthDetailId;
	}


	public function GetSubScribeListByUserId($user_id) {
		$obj = new cDatabase();
	
		$sql = "SELECT a.UserId, b.FirstName, b.LastName , (select user_id from subscription_user where user_id = a.UserId and status = 5) as subscribe 
				FROM user a left join userprofile b on b.UserId = a.UserId
				WHERE (a.UserId = '$user_id' OR parent_user_id = '$user_id') 
						AND a.UserId NOT IN (select user_id from subscription_user where user_id = a.UserId and status = 5) 
				order by a.UserId";
		$query = $obj->db->query($sql);
		return $query->rows;
	}
	
	public function CheckEliteSubscription($UserId) {
		$obj = new cDatabase();
	
		$SQL = "SELECT count(mo.user_id) AS rCount FROM `membership_orders` mo ";
		$WHERE = "WHERE mo.membership_enddate >= '".date('Y-m-d')."' AND mo.user_id = $UserId ";
		$SQL = $SQL . $WHERE;
		$query = $obj->db->query($SQL);
		return $query->row['rCount'];
	}
	
	public function GetPortalSettings($UserId) {
		$obj = new cDatabase();
	
		$SQL = "SELECT `settingid`, `userid`, `portalid`, `portalname`, `portalurl`, `introduction_text`, `cover_page_pdf` FROM `elite_user_portal_settings` mo ";
		$WHERE = "WHERE userid = $UserId ";
		$SQL = $SQL . $WHERE;
		$query = $obj->db->query($SQL);
		return $query->rows;
	}
	

	public function UpdatePortalSettings($PostArray) {
		$obj = new cDatabase();
	
		$SQL = "UPDATE `elite_user_portal_settings` SET "; 
		$SQL .= "`portalname` = '".$obj->db->escape($PostArray['PortalName'])."', ";
		$SQL .= "`portalurl` = '".$obj->db->escape($PostArray['PortalURL'])."', ";
		$SQL .= "`introduction_text` = '".$obj->db->escape($PostArray['CoverPageText'])."', ";
		$SQL .= "`cover_page_pdf` = '".$obj->db->escape($PostArray['fuCoverPage'])."' ";
		$WHERE = "WHERE `userid` = '".$obj->db->escape($PostArray['UserId'])."' AND `portalid` = '".$obj->db->escape($PostArray['PortalId'])."'";
		$SQL = $SQL . $WHERE;
		
		$query = $obj->db->query($SQL);
		if($query) {				
			return true;
		}
		else {
			return false;
		}
	}
}
?>