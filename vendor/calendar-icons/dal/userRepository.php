<?php
error_reporting(0);
@ini_set("display_errors", 0);

if (!class_exists('cDatabase')) {
	if(!include("cDatabase.php")) {
		require_once("../cDatabase.php");
	}
}

class UserRepository {
	
	var $MyPDO;
	
	public function __construct() {
		$this->MyPDO = $GLOBALS["db"];
	}

	/**
	 * Modified By : Krishna Gupta
	 * Modified Date : Nov. 16, 2016
	 */
	public function GetUserCityInfo ($country, $city) {
		$SQLQuery = "SELECT * from cities where country='".$country."' and city='".$city."'";
		$objMyPDO = $this->MyPDO->prepare($SQLQuery);
			
		$objMyPDO->bindParam(':id', $user_id);
		$objMyPDO->execute();
		return $objMyPDO->fetchAll ( PDO::FETCH_ASSOC );
	}
	
	public function IsEmailAvailable($email) {
		$obj = new cDatabase();
		if(!empty($email)) {
			
			$SQLQuery = "SELECT UserId FROM `user` WHERE UserName = :UserName";
			$objMyPDO = $this->MyPDO->prepare($SQLQuery);
			
			$objMyPDO->bindParam(':UserName', $email);
			
			$objMyPDO->execute();
			return $objMyPDO->fetchAll ( PDO::FETCH_ASSOC );
		}
		else {
			return false;
		}
	}

	public function SaveUser($data) {
		$userId = 0;
		$SQLQuery = "INSERT INTO `user`
				(UserName, Password, Status, DefaultLanguage, PortalId, UserGroupId, CreatedDate, ModifiedDate, CreatedBy, ModifiedBy)
			VALUES
				(:UserName, :Password, :Status, :DefaultLanguage, :PortalId, :UserGroupId, :CreatedDate, :ModifiedDate, :CreatedBy, :ModifiedBy)";
		$objMyPDO = $this->MyPDO->prepare($SQLQuery);
		
		$regDate = date('Y-m-d');
		$CreatedBy = 1;
		
		$objMyPDO->bindParam(':UserName', $data->user_name);
		$objMyPDO->bindParam(':Password', $data->password);
		$objMyPDO->bindParam(':Status', $data->status);
		$objMyPDO->bindParam(':DefaultLanguage', $data->language);
		$objMyPDO->bindParam(':PortalId', $data->portal_id);
		$objMyPDO->bindParam(':UserGroupId', $data->user_group);
		$objMyPDO->bindParam(':CreatedDate', $regDate);
		$objMyPDO->bindParam(':ModifiedDate', $regDate);
		$objMyPDO->bindParam(':CreatedBy', $CreatedBy);
		$objMyPDO->bindParam(':ModifiedBy', $CreatedBy);
		
		$objMyPDO->execute();
		$userId = $this->MyPDO->lastInsertId();
		return $userId;
	}

	public function validateUserLogon($userName,$password) {
		$SQLQuery = "SELECT UserId,Status,UserGroupId,UserName FROM `user` o WHERE UserName = :UserName and Password = :Password";
		
		$objMyPDO = $this->MyPDO->prepare ( $SQLQuery );
		$objMyPDO->bindParam(':UserName', $userName);
		$objMyPDO->bindParam(':Password', $password);
		$objMyPDO->execute ();
		
		return $objMyPDO->fetch();
		
// 		try {
// 			$obj = new cDatabase();

// 			$sql = "select UserId,Status,UserGroupId,UserName from user where UserName = '".$userName."' and Password = '".$password."'";
// 			$query = $obj->db->query($sql);
// 			return $query->row;
// 		}
// 		catch(Exception $ex) {
// 		}
// 		return '';
	}

	public function SaveUserProfile($data) {
		$UserProfileId = 0;
		$SQLQuery = "INSERT INTO `userprofile`
				(UserId, FirstName, LastName, Gender, Phone, Address, City, State, Country, Zip, CreatedDate, ModifiedDate, CreatedBy, ModifiedBy)
			VALUES
				(:UserId, :FirstName, :LastName, :Gender, :Phone, :Address, :City, :State, :Country, :Zip, :CreatedDate, :ModifiedDate, :CreatedBy, :ModifiedBy)";
		
		$objMyPDO = $this->MyPDO->prepare($SQLQuery);
		
		$regDate = date('Y-m-d');
		$CreatedBy = 1;
		$objMyPDO->bindParam(':UserId', $data->UserId);
		$objMyPDO->bindParam(':FirstName', $data->FirstName);
		$objMyPDO->bindParam(':LastName', $data->LastName);
		$objMyPDO->bindParam(':Gender', $data->Gender);
		$objMyPDO->bindParam(':Phone', $data->Phone);
		$objMyPDO->bindParam(':Address', $data->Address);
		$objMyPDO->bindParam(':City', $data->city);
		$objMyPDO->bindParam(':State', $data->state);
		$objMyPDO->bindParam(':Country', $data->country);
		$objMyPDO->bindParam(':Zip', $data->zip);
		$objMyPDO->bindParam(':CreatedDate', $regDate);
		$objMyPDO->bindParam(':ModifiedDate', $regDate);
		$objMyPDO->bindParam(':CreatedBy', $CreatedBy);
		$objMyPDO->bindParam(':ModifiedBy', $CreatedBy);
		
		$objMyPDO->execute();
		$UserProfileId = $this->MyPDO->lastInsertId();
		
		return $UserProfileId;
	}

	public function SaveUserBirthDetail($data) {
		$data = $this->GetLocationInformation($data);
		
		$UserBirthDetailId = 0;
		/*
		$SQLQuery = "INSERT INTO `userbirthdetail`
				(UserId, Day, Month, Year, Hours, Minutes, Seconds, unTimed, GMT, ZoneRef, SummerTimeZoneRef, Longitute, Lagitute, CreatedDate, ModifiedDate, CreatedBy, ModifiedBy, country_name, sunsign, city, country, state)
			VALUES
				(:UserId, :Day, :Month, :Year, :Hours, :Minutes, :Seconds, :unTimed, :GMT, :ZoneRef, :SummerTimeZoneRef, :Longitute, :Lagitute, :CreatedDate, :ModifiedDate, :CreatedBy, :ModifiedBy, :country_name, :sunsign, :city, :country, :state)";
		*/
		$SQLQuery = "INSERT INTO `userbirthdetail`
				(UserId, Day, Month, Year, Hours, Minutes, unTimed, GMT, ZoneRef, SummerTimeZoneRef, Longitute, Lagitute, CreatedDate, ModifiedDate, CreatedBy, ModifiedBy, country_name, sunsign, city, country, state)
			VALUES
				(:UserId, :Day, :Month, :Year, :Hours, :Minutes, :unTimed, :GMT, :ZoneRef, :SummerTimeZoneRef, :Longitute, :Lagitute, :CreatedDate, :ModifiedDate, :CreatedBy, :ModifiedBy, :country_name, :sunsign, :city, :country, :state)";
		
		$objMyPDO = $this->MyPDO->prepare($SQLQuery);
		
		$regDate = date('Y-m-d');
		$CreatedBy = 1;
		
		$objMyPDO->bindParam(':UserId', $data->UserId);
		$objMyPDO->bindParam(':Day', $data->Day);
		$objMyPDO->bindParam(':Month', $data->Month);
		$objMyPDO->bindParam(':Year', $data->Year);
		$objMyPDO->bindParam(':Hours', $data->Hours);
		$objMyPDO->bindParam(':Minutes', $data->Minutes);
		//$objMyPDO->bindParam(':Seconds', $data->Seconds);
		$objMyPDO->bindParam(':unTimed', $data->unTimed);
		$objMyPDO->bindParam(':GMT', $data->GMT);
		$objMyPDO->bindParam(':ZoneRef', $data->zoneref);
		$objMyPDO->bindParam(':SummerTimeZoneRef', $data->summerref);
		$objMyPDO->bindParam(':Longitute', $data->longitude);
		$objMyPDO->bindParam(':Lagitute', $data->latitude);
		$objMyPDO->bindParam(':CreatedDate', $regDate);
		$objMyPDO->bindParam(':ModifiedDate', $regDate);
		$objMyPDO->bindParam(':CreatedBy', $CreatedBy);
		$objMyPDO->bindParam(':ModifiedBy', $CreatedBy);
		$objMyPDO->bindParam(':country_name', $data->country_name);
		$objMyPDO->bindParam(':sunsign', $data->sunsign);
		$objMyPDO->bindParam(':city', $data->city);
		$objMyPDO->bindParam(':country', $data->country);
		$objMyPDO->bindParam(':state', $data->state);
		
		$objMyPDO->execute();
		$UserBirthDetailId = $this->MyPDO->lastInsertId();
		return $UserBirthDetailId;
	}

	public function SaveUserHororyDetail($data) {
		$data = $this->GetLocationInformation($data);

		$UserHororyDetailId = 0;
		$SQLQuery = "INSERT INTO `userhorory`
				(UserId, Address, City, State, Country, CreatedDate, ModifiedDate, CreatedBy, ModifiedBy,
				Longitute, Lagitute, country_name)
			VALUES
				(:UserId, :Address, :City, :State, :Country, :CreatedDate, :ModifiedDate, :CreatedBy, :ModifiedBy,
				:Longitute, :Lagitute, :country_name)";
		
		$objMyPDO = $this->MyPDO->prepare($SQLQuery);
		
		$regDate = date('Y-m-d');
		$CreatedBy = 1;
		$objMyPDO->bindParam(':UserId', $data->UserId);
		$objMyPDO->bindParam(':Address', $data->Address);
		$objMyPDO->bindParam(':City', $data->city);
		$objMyPDO->bindParam(':State', $data->state);
		$objMyPDO->bindParam(':Country', $data->country);
		$objMyPDO->bindParam(':CreatedDate', $regDate);
		$objMyPDO->bindParam(':ModifiedDate', $regDate);
		$objMyPDO->bindParam(':CreatedBy', $CreatedBy);
		$objMyPDO->bindParam(':ModifiedBy', $CreatedBy);
		$objMyPDO->bindParam(':Longitute', $data->longitude);
		$objMyPDO->bindParam(':Lagitute', $data->latitude);
		$objMyPDO->bindParam(':country_name', $data->country_name);
		
		$objMyPDO->execute();
		$UserHororyDetailId = $this->MyPDO->lastInsertId();
		return $UserHororyDetailId;		
	}

	public function UpdateUserProfile($data) {
		$SQLQuery = "UPDATE `userprofile` SET
						FirstName = :FirstName, LastName = :LastName, Gender = :Gender, Phone = :Phone,
						Address = :Address, City = :City, State = :State, Country = :Country, Zip = :Zip, 
						ModifiedDate = :ModifiedDate, ModifiedBy = :ModifiedBy
			WHERE UserProfileId = :UserProfileId AND UserId = :UserId ";
		
		$objMyPDO = $this->MyPDO->prepare($SQLQuery);
		
		$regDate = date('Y-m-d');
		
		$objMyPDO->bindParam(':FirstName', $data->FirstName);
		$objMyPDO->bindParam(':LastName', $data->LastName);
		$objMyPDO->bindParam(':Gender', $data->Gender);
		$objMyPDO->bindParam(':Phone', $data->Phone);
		$objMyPDO->bindParam(':Address', $data->Address);
		$objMyPDO->bindParam(':City', $data->city);
		$objMyPDO->bindParam(':State', $data->state);
		$objMyPDO->bindParam(':Country', $data->country);
		$objMyPDO->bindParam(':Zip', $data->Zip);
		$objMyPDO->bindValue(':ModifiedDate', $regDate, PDO::PARAM_STR);
		$objMyPDO->bindParam(':ModifiedBy', $data->UserId);
		$objMyPDO->bindParam(':UserProfileId', $data->UserProfileId);
		$objMyPDO->bindParam(':UserId', $data->UserId);

		$objMyPDO->execute();
			
		return true;
	}

	public function UpdateUserBirthDetail($data) {
		$data = $this->GetLocationInformation($data);

		$SQLQuery = "UPDATE `userbirthdetail` SET
				Day = :Day, Month = :Month, Year = :Year, Hours = :Hours, Minutes = :Minutes,
				unTimed = :unTimed, GMT = :GMT, ZoneRef = :ZoneRef, SummerTimeZoneRef = :SummerTimeZoneRef,
				Longitute = :Longitute, Lagitute = :Lagitute, city = :city, country = :country,
				country_name = :country_name, state = :state, ModifiedDate = :ModifiedDate, sunsign = :sunsign,
				ModifiedBy = :ModifiedBy
			WHERE UserId = :UserId and UserBirthDetailId = :UserBirthDetailId";
		
		$objMyPDO = $this->MyPDO->prepare($SQLQuery);
		
		$regDate = date('Y-m-d');
		
		$objMyPDO->bindParam(':Day', $data->Day);
		$objMyPDO->bindParam(':Month', $data->Month);
		$objMyPDO->bindParam(':Year', $data->Year);
		$objMyPDO->bindParam(':Hours', $data->Hours);
		$objMyPDO->bindParam(':Minutes', $data->Minutes);
		//$objMyPDO->bindParam(':Seconds', $data->Seconds);
		$objMyPDO->bindParam(':unTimed', $data->unTimed);
		$objMyPDO->bindParam(':GMT', $data->GMT);
		$objMyPDO->bindParam(':ZoneRef', $data->zoneref);
		$objMyPDO->bindParam(':SummerTimeZoneRef', $data->summerref);
		$objMyPDO->bindParam(':Longitute', $data->longitude);
		$objMyPDO->bindParam(':Lagitute', $data->latitude);
		$objMyPDO->bindParam(':city', $data->city);
		$objMyPDO->bindParam(':country', $data->country);
		$objMyPDO->bindParam(':country_name', $data->country_name);
		$objMyPDO->bindParam(':state', $data->state);
		$objMyPDO->bindParam(':ModifiedDate', $regDate);
		$objMyPDO->bindParam(':sunsign', $data->sunsign);
		$objMyPDO->bindParam(':ModifiedBy', $data->UserId);
		$objMyPDO->bindParam(':UserId', $data->UserId);
		$objMyPDO->bindParam(':UserBirthDetailId', $data->UserBirthDetailId);
		
		$objMyPDO->execute();
		
		return true;
	}

	public function UpdateUserHororyDetail($data) {
		$data = $this->GetLocationInformation($data);
		
		$SQLQuery = "UPDATE `userhorory` SET
				Address = :Address, City = :City, State = :State, Country = :Country,
				Longitute = :Longitute, Lagitute = :Lagitute, ModifiedDate = :ModifiedDate,
				country_name = :country_name, ModifiedBy = :ModifiedBy
			WHERE UserHororyId = :UserHororyId AND UserId = :UserId ";
		
		$objMyPDO = $this->MyPDO->prepare($SQLQuery);
		
		$regDate = date('Y-m-d');
		$objMyPDO->bindParam(':Address', $data->Address);
		$objMyPDO->bindParam(':City', $data->city);
		$objMyPDO->bindParam(':Country', $data->country);
		$objMyPDO->bindParam(':State', $data->state);
		$objMyPDO->bindParam(':Longitute', $data->longitude);
		$objMyPDO->bindParam(':Lagitute', $data->latitude);
		$objMyPDO->bindParam(':ModifiedDate', $regDate);
		$objMyPDO->bindParam(':country_name', $data->country_name);
		$objMyPDO->bindParam(':ModifiedBy', $data->UserId);
		$objMyPDO->bindParam(':UserId', $data->UserId);
		$objMyPDO->bindParam(':UserHororyId', $data->UserHororyId);
		
		$objMyPDO->execute();
		
		return true;
	}

	public function UpdateUserPassword($password,$email) {
		$SQLQuery = "UPDATE `user` SET Password = :Password WHERE UserName = :UserName ";
		
		$objMyPDO = $this->MyPDO->prepare($SQLQuery);
		
		$objMyPDO->bindParam(':Password', $password);
		$objMyPDO->bindParam(':UserName', $email);
		
		$objMyPDO->execute();
		return true;
	}

	public function GetUserProfileDetailByUserId($user_id) {
		$SQLQuery = "SELECT
					UserProfileId,UserId, FirstName, LastName, Gender, Phone, Address, City, State,
					Country, Zip, CreatedDate, ModifiedDate, CreatedBy, ModifiedBy
			FROM `userprofile`
			WHERE UserId = :UserId ";
		
		$objMyPDO = $this->MyPDO->prepare($SQLQuery);
		
		$objMyPDO->bindParam(':UserId', $user_id);
		$objMyPDO->execute();
		
		return $objMyPDO->fetchAll ( PDO::FETCH_ASSOC );
	}

	public function GetUserDetail($user_id, $userType) {
		/*$SQLQuery = "SELECT UserId,UserName,Status,DefaultLanguage,PortalId,UserGroupId
					FROM `user`
					WHERE UserId = :UserId ";*/
		if (!empty($userType)) {
			$SQLQuery = "SELECT * from another_persons where id=".$user_id;
		} else {
			$SQLQuery = "SELECT users.*, profiles.*, languages.name as language 
							from users
							JOIN profiles ON profiles.user_id = users.id
							JOIN languages ON profiles.language_id = languages.id
							where users.id=".$user_id;
		}
		$objMyPDO = $this->MyPDO->prepare($SQLQuery);
		$objMyPDO->bindParam(':UserId', $user_id);
		$objMyPDO->execute();
		return $objMyPDO->fetchAll ( PDO::FETCH_ASSOC );		
	}

	public function GetUserHororyDetailByUserId($user_id) {
		$SQLQuery = "SELECT UserHororyId, Address,City,State,Country, Longitute, Lagitute, country_name
					FROM `userhorory`
					WHERE UserId = :UserId ";
		
		$objMyPDO = $this->MyPDO->prepare($SQLQuery);
		
		$objMyPDO->bindParam(':UserId', $user_id);
		$objMyPDO->execute();
		return $objMyPDO->fetchAll ( PDO::FETCH_ASSOC );
	}

	public function GetUserBirthDetailByUserId($user_id, $userType) {
		/*$SQLQuery = "SELECT
						UserBirthDetailId,UserId,`Day`,`Month`,`Year`,`Hours`,`Minutes`,`Seconds`,unTimed,GMT, ZoneRef,
						SummerTimeZoneRef, Longitute, Lagitute, country,state,city, country_name, sunsign
					FROM `userbirthdetail`
					WHERE UserId = :UserId ";*/
		if (!empty($userType)) {
			$SQLQuery = "SELECT
						another_persons.*,
						cities.longitude, cities.latitude, cities.city as cityname, cities.county as state, cities.typetable,
						countries.name as countryname
					FROM `another_persons` 
					JOIN cities ON (another_persons.city_id = cities.id)
					JOIN countries ON (another_persons.country_id = countries.id)
					WHERE another_persons.id = ".$user_id;
		} else {
			$SQLQuery = "SELECT
						birth_details.*,
						cities.longitude, cities.latitude, cities.city as cityname, cities.county as state, cities.typetable,
						countries.name as countryname,
						sun_signs.name as sunSign
					FROM `birth_details` 
					JOIN cities ON (birth_details.city_id = cities.id)
					JOIN countries ON (birth_details.country_id = countries.id)
					JOIN sun_signs ON (birth_details.sun_sign_id = sun_signs.id)
					WHERE user_id = ".$user_id;
		}
		$objMyPDO = $this->MyPDO->prepare($SQLQuery);
		
		$objMyPDO->bindParam(':UserId', $user_id);
		$objMyPDO->execute();
		return $objMyPDO->fetchAll ( PDO::FETCH_ASSOC );		
	}

	public function SaveUserOptin($user_id,$data) {
		$SQLQuery = "DELETE FROM `user_optin` WHERE user_id = :UserId ";
		
		$objMyPDO = $this->MyPDO->prepare($SQLQuery);
		$objMyPDO->bindParam(':UserId', $user_id);
		$objMyPDO->execute();

		$option = explode(",", $data);
		
		for($i=0; $i < count($option); $i++) {
			if(!empty($option[$i]))
			{
				$SQLQuery = "INSERT INTO `user_optin` (user_id, optin_option_id) VALUES (:user_id, :optin_option_id) ";
				$objMyPDO = $this->MyPDO->prepare($SQLQuery);
				$objMyPDO->bindParam(':user_id', $user_id);
				$objMyPDO->bindParam(':optin_option_id', $option[$i]);
				
				$objMyPDO->execute();
				$UserHororyDetailId = $this->MyPDO->lastInsertId();
			}
		}
		return true;
	}

	public function GetUserDetailForMail($user_id) {
		$SQLQuery = "SELECT
				u.UserName,up.FirstName, up.LastName, up.Gender
			FROM `user` u LEFT JOIN `userprofile` up on up.UserId = u.UserId
			WHERE u.UserName = :UserId ";
		
		$objMyPDO = $this->MyPDO->prepare($SQLQuery);
		
		$objMyPDO->bindParam(':UserId', $user_id);
		$objMyPDO->execute();
		return $objMyPDO->fetchAll ( PDO::FETCH_ASSOC );
	}

	public function CheckPassword($user_id, $password) {
		$SQLQuery = "SELECT count(UserId) as count FROM `user` WHERE Password = :Password AND UserId = :UserId ";
		
		$objMyPDO = $this->MyPDO->prepare($SQLQuery);
		
		$objMyPDO->bindParam(':UserId', $user_id);
		$objMyPDO->bindParam(':Password', $password);
		$objMyPDO->execute();
		
		return $objMyPDO->fetchColumn ();
	}

	public function ChangePassword($user_id, $password) {
		$SQLQuery = "UPDATE `user` SET Password = :Password WHERE UserId = :UserId ";
		
		$objMyPDO = $this->MyPDO->prepare($SQLQuery);
		
		$objMyPDO->bindParam(':UserId', $user_id);
		$objMyPDO->bindParam(':Password', $password);
		$objMyPDO->execute();
		
		return true;
	}

	public function updatePassword($email, $password) {
		$SQLQuery = "UPDATE `user` SET Password = :Password WHERE UserName = :UserId ";
		
		$objMyPDO = $this->MyPDO->prepare($SQLQuery);
		
		$objMyPDO->bindParam(':UserId', $email);
		$objMyPDO->bindParam(':Password', $password);
		$objMyPDO->execute();
		return true;
	}

	public function GetUserToAssignPreviewReport() {
		$obj = new cDatabase();
		$sql = " SELECT UserId FROM `user` WHERE `preview_report` = '' AND UserId !=1";

		$query = $obj->db->query($sql);
		return $query->rows;
	}

	public function UpdateUserToAssignPreviewReport() {
		$SQLQuery = "UPDATE `user` SET Password = :Password WHERE UserName = :UserId ";
		
		$objMyPDO = $this->MyPDO->prepare($SQLQuery);
		
		$objMyPDO->bindParam(':UserId', $email);
		$objMyPDO->bindParam(':Password', $password);
		$objMyPDO->execute();		
	}

	public function SavePerson($data,$user_id) {
		$person_id = 0;
		$SQLQuery = "INSERT INTO `user`
				(UserName, Password, Status, DefaultLanguage, PortalId, UserGroupId, CreatedDate, ModifiedDate,
				CreatedBy, ModifiedBy, preview_report, parent_user_id)
			VALUES
				(:UserName, :Password, :Status, :DefaultLanguage, :PortalId, :UserGroupId, :CreatedDate, :ModifiedDate,
				:CreatedBy, :ModifiedBy, :preview_report, :parent_user_id)";
		$objMyPDO = $this->MyPDO->prepare($SQLQuery);
		
		$regDate = date('Y-m-d');
		$CreatedBy = 1;
		$PreView = -1;
		$objMyPDO->bindParam(':UserName', $data->user_name);
		$objMyPDO->bindParam(':Password', $data->password);
		$objMyPDO->bindParam(':Status', $data->status);
		$objMyPDO->bindParam(':DefaultLanguage', $data->language);
		$objMyPDO->bindParam(':PortalId', $data->portal_id);
		$objMyPDO->bindParam(':UserGroupId', $data->user_group);
		$objMyPDO->bindParam(':CreatedDate', $regDate);
		$objMyPDO->bindParam(':ModifiedDate', $regDate);
		$objMyPDO->bindParam(':CreatedBy', $CreatedBy);
		$objMyPDO->bindParam(':ModifiedBy', $CreatedBy);
		$objMyPDO->bindParam(':preview_report', $PreView);
		$objMyPDO->bindParam(':parent_user_id', $user_id);
		
		$objMyPDO->execute();
		$person_id = $this->MyPDO->lastInsertId();
		return $person_id;
	}

	public function GetAnotherPersonListByUserId($user_id) {
		/*
		$SQLQuery = "SELECT
				a.UserId, b.FirstName, b.LastName ,
				(select user_id from subscription_user where user_id = a.UserId and status = 5) as subscribe
			FROM user a LEFT JOIN userprofile b ON b.UserId = a.UserId
			WHERE (a.UserId = :UserId or parent_user_id = :UserId_One) AND a.Status = 1
			ORDER BY a.UserId ";
		*/
		$SQLQuery = "SELECT
						a.UserId, b.FirstName, b.LastName, s.user_id as subscribe
					FROM user a LEFT JOIN userprofile b ON b.UserId = a.UserId
						JOIN (SELECT user_id FROM subscription_user WHERE status = 5) s ON s.user_id = a.UserId
					WHERE (a.UserId = :UserId or parent_user_id = :UserId_One) AND a.Status = 1
					GROUP BY a.UserId
					ORDER BY a.UserId";
		
		$objMyPDO = $this->MyPDO->prepare($SQLQuery);
		
		$objMyPDO->bindParam(':UserId', $user_id);
		$objMyPDO->bindParam(':UserId_One', $user_id);
		
		$objMyPDO->execute();
		return $objMyPDO->fetchAll ( PDO::FETCH_ASSOC );
	}

	public function GetPersonDetailByPersonId($person_id) {
		$SQLQuery = "SELECT 
							a.UserId, a.UserName, b.UserProfileId,b.FirstName,b.LastName,b.Gender,
							c.UserBirthDetailId,c.Day,c.Month,c.Year,c.Hours,c.Minutes 	,c.Seconds 	,c.unTimed 	,c.GMT 	,c.ZoneRef,
							c.SummerTimeZoneRef 	,c.Longitute 	,c.Lagitute 	,c.CreatedDate ,c.ModifiedDate 	,c.CreatedBy,
							c.ModifiedBy 	,c.country 	,c.state 	,c.city 	,c.country_name 	,c.sunsign
				FROM `user` a
					LEFT JOIN `userprofile` b ON b.UserId = a.UserId
					LEFT JOIN `userbirthdetail` c ON c.UserId = a.UserId
			WHERE a.UserId = :UserId ";
		$objMyPDO = $this->MyPDO->prepare($SQLQuery);
		
		$objMyPDO->bindParam(':UserId', $person_id);
		
		$objMyPDO->execute();
		return $objMyPDO->fetchAll ( PDO::FETCH_ASSOC );
	}


	public function GetUserDetailByUserId($user_id) {
		$SQLQuery = "SELECT
						u.UserId,u.UserName,u.Status,u.DefaultLanguage,u.PortalId,u.UserGroupId,up.FirstName,up.LastName, up.Gender
					FROM `user` u
						LEFT JOIN  userprofile up on up.UserId = u.UserId 
					WHERE u.UserId = :UserId ";
		$objMyPDO = $this->MyPDO->prepare($SQLQuery);
		
		$objMyPDO->bindParam(':UserId', $user_id);
		
		$objMyPDO->execute();
		return $objMyPDO->fetchAll ( PDO::FETCH_ASSOC );
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
		$SQLQuery = "SELECT o.optin_option_id, o.user_id FROM `user_optin` o LEFT JOIN user u on u.UserId = o.user_id
			WHERE u.UserName = :UserName";
		$objMyPDO = $this->MyPDO->prepare($SQLQuery);
		
		$objMyPDO->bindParam(':UserName', $email);
		$objMyPDO->execute();
		
		return $objMyPDO->fetchAll ( PDO::FETCH_ASSOC );		
	}

	public function SaveUserUnsubscribeDetail($user_id,$name_of_optin,$reason)
	{
		$id = 0;
		$SQLQuery = "INSERT INTO `user_unsubscribe_detail` (`user_id`, `name_of_optin`, `reason`,`date_added`)
					VALUES (:user_id, :name_of_optin, :reason, :date_added) ";
		
		$objMyPDO = $this->MyPDO->prepare($SQLQuery);
		$regDate = date('Y-m-d h:m:s');
		
		$objMyPDO->bindParam(':user_id', $user_id);
		$objMyPDO->bindParam(':name_of_optin', $name_of_optin);
		$objMyPDO->bindParam(':reason', $reason);
		$objMyPDO->bindParam(':date_added', $regDate);
		$objMyPDO->execute();
		
		$id = $this->MyPDO->lastInsertId();
		return $id;
	}

	public function UpdateUserOptin($user_id,$data)
	{
		$returnValue = 0;
		$option = explode(",", $data);
		for($i=0;$i<count($option);$i++)
		{
			if(!empty($option[$i]))
			{
				$SQLQuery = "DELETE FROM `user_optin` WHERE user_id = :user_id AND optin_option_id = :optin_option_id ";
				
				$objMyPDO = $this->MyPDO->prepare($SQLQuery);
				
				$objMyPDO->bindParam(':user_id', $user_id);
				$objMyPDO->bindParam(':name_of_optin', $option[$i]);
				$objMyPDO->execute();				
			}
			$returnValue = 1;
		}
		return $returnValue;
	}

	public function DeleteUserAccount($user_id)
	{
		$SQLQuery = "UPDATE `user` SET Status = 0 WHERE UserId = :user_id";
		
		$objMyPDO = $this->MyPDO->prepare($SQLQuery);
		
		$objMyPDO->bindParam(':user_id', $user_id);
		$objMyPDO->execute();
		
		return $user_id;
	}
	 
	public function GetPasswordByUserName($userName)
	{
		$SQLQuery = "SELECT 
						u.UserId,u.Password, u.DefaultLanguage , up.FirstName, up.LastName 
					FROM `user` u LEFT JOIN `userprofile` up ON u.UserId = up.UserId 
					WHERE u.UserName = :UserName and u.Status = 1";
		
		$objMyPDO = $this->MyPDO->prepare($SQLQuery);
		$regDate = date('Y-m-d h:m:s');
		
		$objMyPDO->bindParam(':UserName', $userName);
		$objMyPDO->execute();
		return $objMyPDO->fetch();		
	}

	public function SaveUserToken($user_id,$token)
	{
		$SQLQuery = "INSERT INTO `user_token` (`user_id`, `token`) VALUES (:user_id, :token)";
		
		$objMyPDO = $this->MyPDO->prepare($SQLQuery);
		$regDate = date('Y-m-d h:m:s');
		
		$objMyPDO->bindParam(':user_id', $user_id);
		$objMyPDO->bindParam(':token', $token);
		try {
			$objMyPDO->execute();
			return $this->MyPDO->lastInsertId();;
		} catch(Exception $ex)	{
			return 0;
		}
	}

	public function UpdateUserToken($user_id, $token_id)
	{
		$SQLQuery = "UPDATE `user_token` SET count =1 WHERE `user_id` = :user_id AND `token` = :token";
		
		$objMyPDO = $this->MyPDO->prepare($SQLQuery);
		
		$objMyPDO->bindParam(':user_id', $user_id);
		$objMyPDO->bindParam(':token', $token_id);
		try {
			$objMyPDO->execute();
			return 1;
		}catch(Exception $ex){
			return 0;
		}
	}

	public function GetUserToken($token)
	{
		$SQLQuery = "SELECT token_id, user_id, token FROM `user_token` WHERE count = 0 AND token = :token ";
		
		$objMyPDO = $this->MyPDO->prepare ( $SQLQuery );
		
		$objMyPDO->bindParam(':token', $token);
		
		try
		{
			$objMyPDO->execute ();		
			return $objMyPDO->fetch();		
		} catch(Exception $ex) {
			return 0;
		}
	}

	public function UpdateUserGroup($UserId, $EMail, $GroupId) {
		
		$SQLQuery = "UPDATE `user` SET UserGroupId = :UserGroupId WHERE UserId = :UserId";
		
		$objMyPDO = $this->MyPDO->prepare ( $SQLQuery );
		$objMyPDO->bindParam(':UserGroupId', $GroupId);
		$objMyPDO->bindParam(':UserId', $UserId);
		
		try
		{
			$objMyPDO->execute ();
			return true;
		} catch(Exception $ex) {
			return false;
		}		
	}

	public function GetUserIds($UserId){
		$SQLQuery = "SELECT u.UserId, UserBirthDetailId, UserProfileId
				FROM `user` u, `userbirthdetail` ubd, `userprofile` up
				WHERE u.UserId = ubd.UserId AND up.UserId = ubd.UserId AND u.UserId = up.UserId AND u.UserId = :UserId ";
					
		$objMyPDO = $this->MyPDO->prepare ( $SQLQuery );
		$objMyPDO->bindParam(':UserId', $UserId);
		
		try
		{
			$objMyPDO->execute ();		
			return $objMyPDO->fetchAll ( PDO::FETCH_ASSOC );
		} catch(Exception $ex) {
			return 0;
		}
	}


	public function GetUserDetailByUserIdForSignUp($user_id) {
		
		$SQLQuery = "SELECT 
						u.*, up.*
					FROM `users` u LEFT JOIN `profiles` up on up.user_id = u.id 
					WHERE u.id = ".$user_id;
		
		$objMyPDO = $this->MyPDO->prepare ( $SQLQuery );
		$objMyPDO->bindParam(':UserId', $user_id);
		
		$objMyPDO->execute ();
		return $objMyPDO->fetchAll ( PDO::FETCH_ASSOC );
	}


	public function SaveUserBirthDetailStep1($data) {
		//$data = $this->GetLocationInformation($data);
		
		$UserBirthDetailId = 0;
		$SQLQuery = "INSERT INTO `userbirthdetail`
						(UserId, Day, Month, Year, Hours, Minutes, Seconds, unTimed, GMT, ZoneRef, SummerTimeZoneRef, Longitute, Lagitute, 
						CreatedDate, ModifiedDate, CreatedBy, ModifiedBy, country_name, sunsign, city, country, state)
					VALUES
						(:UserId, :Day, :Month, :Year, :Hours, :Minutes, :Seconds, :unTimed, :GMT, :ZoneRef, :SummerTimeZoneRef, :Longitute, :Lagitute, 
						:CreatedDate, :ModifiedDate, :CreatedBy, :ModifiedBy, :country_name, :sunsign, :city, :country, :state)";
		$objMyPDO = $this->MyPDO->prepare($SQLQuery);
		
		$regDate = date('Y-m-d');
		$CreatedBy = 1;
		
		$objMyPDO->bindParam(':UserId', $data->UserId);
		$objMyPDO->bindParam(':Day', $data->Day);
		$objMyPDO->bindParam(':Month', $data->Month);
		$objMyPDO->bindParam(':Year', $data->Year);
		$objMyPDO->bindParam(':Hours', $data->Hours);
		$objMyPDO->bindParam(':Minutes', $data->Minutes);
		$objMyPDO->bindParam(':Seconds', $data->Seconds);
		$objMyPDO->bindParam(':unTimed', $data->unTimed);
		$objMyPDO->bindParam(':GMT', $data->GMT);
		$objMyPDO->bindParam(':ZoneRef', $data->zoneref);
		$objMyPDO->bindParam(':SummerTimeZoneRef', $data->summerref);
		$objMyPDO->bindParam(':Longitute', $data->longitude);
		$objMyPDO->bindParam(':Lagitute', $data->latitude);
		$objMyPDO->bindParam(':CreatedDate', $regDate);
		$objMyPDO->bindParam(':ModifiedDate', $regDate);		
		$objMyPDO->bindParam(':CreatedBy', $CreatedBy);
		$objMyPDO->bindParam(':ModifiedBy', $CreatedBy);
		$objMyPDO->bindParam(':country_name', $data->country_name);
		$objMyPDO->bindParam(':sunsign', $data->sunsign);
		$objMyPDO->bindParam(':city', $data->city);
		$objMyPDO->bindParam(':country', $data->country);
		$objMyPDO->bindParam(':state', $data->state);
		
		$objMyPDO->execute();
		$UserBirthDetailId = $this->MyPDO->lastInsertId();

		return $UserBirthDetailId;		
	}

	public function GetSubScribeListByUserId($user_id) {
		$SQLQuery = "SELECT a.UserId, b.FirstName, b.LastName ,
						(SELECT user_id FROM subscription_user WHERE user_id = a.UserId AND status = 5 GROUP BY user_id) as subscribe
					FROM user a left join userprofile b on b.UserId = a.UserId
					WHERE (a.UserId = :UserId OR parent_user_id = :UserId_One
						OR a.UserId IN (SELECT user_id FROM subscription_user WHERE user_id = :UserId_Two AND status IN (1, 4, 0)
					GROUP BY user_id) )
					ORDER BY a.UserId ";
		
		$objMyPDO = $this->MyPDO->prepare($SQLQuery);
		
		$objMyPDO->bindParam(':UserId', $user_id);
		$objMyPDO->bindParam(':UserId_One', $user_id);
		$objMyPDO->bindParam(':UserId_Two', $user_id);
		
		$objMyPDO->execute();
		
		return $objMyPDO->fetchAll ( PDO::FETCH_ASSOC );
		
		
	
// 		$sql = "SELECT a.UserId, b.FirstName, b.LastName , 
// 					(select user_id from subscription_user where user_id = a.UserId and status = 5) as subscribe 
// 				FROM user a left join userprofile b on b.UserId = a.UserId
// 				WHERE (a.UserId = '$user_id' OR parent_user_id = '$user_id') 
// 						AND a.UserId NOT IN (select user_id from subscription_user where user_id = a.UserId and status = 5) 
// 				order by a.UserId";


// 		$sql = "SELECT a.UserId, b.FirstName, b.LastName ,
// 				(select user_id from subscription_user where user_id = a.UserId and status = 5 GROUP BY user_id) as subscribe
// 				FROM user a left join userprofile b on b.UserId = a.UserId
// 				WHERE (a.UserId = '$user_id' OR parent_user_id = '$user_id'
// 				OR a.UserId IN (select user_id from subscription_user where user_id = '$user_id' and status IN (1, 4, 0) GROUP BY user_id) )
// 				order by a.UserId";
		
				
// 		$sql =	"SELECT a.UserId, b.FirstName, b.LastName ,
// 						(select user_id from subscription_user where user_id = a.UserId and status = 5) as subscribe
// 				FROM user a 
// 						LEFT JOIN userprofile b ON b.UserId = a.UserId
// 						LEFT JOIN subscription_user s ON s.user_id = a.UserId AND s.status IN (1, 4, 0)
// 				WHERE (a.UserId = $user_id OR parent_user_id = $user_id) 
// 				GROUP BY a.UserId
// 				ORDER BY a.UserId";
		//echo $sql;		
	}	
}
?>
