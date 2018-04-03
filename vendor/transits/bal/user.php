<?php 
require_once("include.php");
require_once(DALPATH."/userRepository.php");
//require_once(ROOTPATH."dto/Enum.php");
//require_once(ROOTPATH."dto/userDTO.php");

/*if(!@include("dal/userRepository.php"))
 {
//throw new Exception("Failed to include 'userRepository.php'");
require_once("../dal/userRepository.php");
}*/

if(!@include("dto/Enum.php")) {
	//throw new Exception("Failed to include 'userRepository.php'");
	require_once(DTOPATH."/Enum.php");
}

if(!@include("dto/userDTO.php")) {
	//throw new Exception("Failed to include 'userRepository.php'");
	require_once(DTOPATH."/userDTO.php");
}

if (!class_exists('genericMail')) {
	require_once(HELPERPATH."/mail/genericMail.php");
}

require_once(BALPATH."/order.php");
require_once(BALPATH."/subscription.php");

class User {
	public function validateUserLogon($username,$password) {
		$userRepository = new UserRepository();
		//$returnValue = $userRepository->validateUserLogon($username,$password);
		$returnValue = $userRepository->validateUserLogon($username,md5($password));
		//print_r($returnValue);
		if(count($returnValue) > 0) {
			$host  = $_SERVER['HTTP_HOST'];
			//$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
			$uri   = DIR_FRONT;
			$extra = 'my-astropage.php';


			setcookie("UserId", $returnValue['UserId'], time()+(60*60*24*30),'/',$host);  				/* expire in 1 month */
			setcookie("UserGroupId", $returnValue['UserGroupId'], time()+(60*60*24*30),'/',	$host);  	/* expire in 1 month */
			setcookie("UserEmail", $returnValue['UserName'], time()+(60*60*24*30),'/',$host);  			/* expire in 1 month */
			setcookie("UserLogon",$returnValue['UserId']);												/* At end of session */

			//header("Location: http://$host$uri/$extra");
			return true;
		}
		else {
			return false;
		}
	}
	
	
	public function validateUserForMobilesLogon($username, $password) {
		$userRepository = new UserRepository();		
		$returnValue = $userRepository->validateUserLogon($username, md5($password));
		
		if(count($returnValue) > 0) {			
			$ReturnArray["UserId"] = $returnValue['UserId'];  			/* expire in 1 month */
			$ReturnArray["UserGroupId"] = $returnValue['UserGroupId'];  		/* expire in 1 month */
			$ReturnArray["UserEmail"] = $returnValue['UserName'];  			/* expire in 1 month */
			$ReturnArray["UserLogon"] = $returnValue['UserId'];				/* At end of session */
			
			//header("Location: http://$host$uri/$extra");
			return $ReturnArray;
		}
		else {
			return false;
		}
	}


	public function IsLogon() {
		if(isset($_COOKIE['UserId']) && !empty($_COOKIE['UserId'])) {
			return true;
		}
		else {
			$host  = $_SERVER['HTTP_HOST'];
			$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
			$extra = 'logon.php';
			header("Location: http://$host$uri/$extra");
			exit;
		}
	}

	public function Logout($returnURL = '') {
		$host  = $_SERVER['HTTP_HOST'];

		setcookie("UserId", false, time()-36000000,'/');
		setcookie("UserGroupId", false, time()-36000000,'/');
		setcookie("UserEmail", false, time()-36000000,'/');
		
		setcookie("UserId", false, time()-36000000,'/',$host);
		setcookie("UserGroupId", false, time()-36000000,'/',$host);
		setcookie("UserEmail", false, time()-36000000,'/',$host);
		
		setcookie("UserId", false, time()-36000000,'/','.'.$host);
		setcookie("UserGroupId", false, time()-36000000,'/','.'.$host);
		setcookie("UserEmail", false, time()-36000000,'/','.'.$host);

		$_COOKIE['UserId'] = '';
		$_COOKIE['UserGroupId'] = '';
		$_COOKIE['UserEmail'] = '';

		unset($_COOKIE['UserId']);
		unset($_COOKIE['UserGroupId']);
		unset($_COOKIE['UserEmail']);		
	
		// unset cookies
		if (isset($_SERVER['HTTP_COOKIE'])) {
			$cookies = explode(';', $_SERVER['HTTP_COOKIE']);
			foreach($cookies as $cookie) {
				$parts = explode('=', $cookie);
				$name = trim($parts[0]);
				setcookie($name, false, time()-100000);
				setcookie($name, false, time()-100000, '/');
				setcookie($name, false, time()-100000, '/', $_SERVER['HTTP_HOST']);
				setcookie($name, false, time()-100000, '/', '.'.$_SERVER['HTTP_HOST']);
			}
		}		
// 		print_r($_COOKIE);
// 		exit;
		$host  = $_SERVER['HTTP_HOST'];
		$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
		$extra = 'index.php';
		
		if(empty($returnURL)) {
			header("Location: http://$host$uri/$extra");
		}
		else {
			header("Location: ".$returnURL);
		}
		exit;
	}

	public function IsEmailAvailable($email) {
		$userRepository = new UserRepository();
		$returnValue = $userRepository->IsEmailAvailable($email);
		//print_r($returnValue);
		if(count($returnValue) > 0) {
			return false;
		}
		else {
			return true;
		}
	}

	public function SaveUser($data) {
		$userRepository = new UserRepository();
		$returnValue = $userRepository->SaveUser($data);
		return $returnValue;
	}

	public function SaveUserProfile($data) {
		$userRepository = new UserRepository();
		$returnValue = $userRepository->SaveUserProfile($data);
		return $returnValue;
	}

	public function SaveUserBirthDetail($data) {
		$userRepository = new UserRepository();
		$returnValue = $userRepository->SaveUserBirthDetail($data);
		return $returnValue;
	}

	public function SaveUserHororyDetail($data) {
		$userRepository = new UserRepository();
		$returnValue = $userRepository->SaveUserHororyDetail($data);
		return $returnValue;
	}

	public function UpdateUserProfile($data) {
		$userRepository = new UserRepository();
		$returnValue = $userRepository->UpdateUserProfile($data);
		return $returnValue;
	}

	public function UpdateUserBirthDetail($data) {
		$userRepository = new UserRepository();
		$returnValue = $userRepository->UpdateUserBirthDetail($data);
		return $returnValue;
	}

	public function UpdateUserHororyDetail($data) {
		$userRepository = new UserRepository();
		$returnValue = $userRepository->UpdateUserHororyDetail($data);
		return $returnValue;
	}

	public function UpdateUserPassword($password,$email) {
		$userRepository = new UserRepository();
		//$returnValue = $userRepository->UpdateUserPassword($password,$email);
		$returnValue = $userRepository->UpdateUserPassword(md5($password),$email);
		return $returnValue;
	}

	public function GetUserProfileDetailByUserId($user_id) {
		$userRepository = new UserRepository();
		$returnValue = $userRepository->GetUserProfileDetailByUserId($user_id);
		return $returnValue;
	}

	public function GetUserDetail($user_id, $userType='') {
		$userRepository = new UserRepository();
		$returnValue = $userRepository->GetUserDetail($user_id, $userType);
		return $returnValue;
	}

	public function GetUserBirthDetailByUserId($user_id, $userType='') {
		$userRepository = new UserRepository();
		$returnValue = $userRepository->GetUserBirthDetailByUserId($user_id, $userType);
		return $returnValue;
	}

	public function GetUserHororyDetailByUserId($user_id) {
		$userRepository = new UserRepository();
		$returnValue = $userRepository->GetUserHororyDetailByUserId($user_id);
		return $returnValue;
	}
	public function SaveUserOptin($user_id,$data) {
		$userRepository = new UserRepository();
		$returnValue = $userRepository->SaveUserOptin($user_id,$data);
		return $returnValue;
	}

	public function GetUserDetailForMail($user_id) {
		$userRepository = new UserRepository();
		$returnValue = $userRepository->GetUserDetailForMail($user_id);
		return $returnValue;
	}

	public function CheckPassword($user_id, $password) {
		$userRepository = new UserRepository();
		//$returnValue = $userRepository->CheckPassword($user_id, $password);
		$returnValue = $userRepository->CheckPassword($user_id, md5($password));
		return $returnValue;
	}

	public function ChangePassword($user_id, $password) {
		$userRepository = new UserRepository();
		//$returnValue = $userRepository->ChangePassword($user_id, $password);
		$returnValue = $userRepository->ChangePassword($user_id, md5($password));
		return $returnValue;
	}

	public function updatePassword($email, $password) {
		$userRepository = new UserRepository();
		//$returnValue = $userRepository->updatePassword($email, $password);
		$returnValue = $userRepository->updatePassword($email, md5($password));
		return $returnValue;
	}

	public function SavePerson($data,$user_id) {
		$userRepository = new UserRepository();
		$returnValue = $userRepository->SavePerson($data,$user_id);
		return $returnValue;
	}

	public function GetAnotherPersonListByUserId($user_id) {
		$userRepository = new UserRepository();
		$returnValue = $userRepository->GetAnotherPersonListByUserId($user_id);
		return $returnValue;
	}

	public function GetPersonDetailByPersonId($person_id) {
		$userRepository = new UserRepository();
		$returnValue = $userRepository->GetPersonDetailByPersonId($person_id);
		return $returnValue;
	}
	public function GetUserDetailByUserId($user_id) {
		$userRepository = new UserRepository();
		$returnValue = $userRepository->GetUserDetailByUserId($user_id);
		return $returnValue;
	}

	public function GetUserOptinDetailByUserEmail($email) {
		$userRepository = new UserRepository();
		$returnValue = $userRepository->GetUserOptinDetailByUserEmail($email);
		return $returnValue;
	}

	public function SaveUserUnsubscribeDetail($user_id,$name_of_optin,$reason) {
		$userRepository = new UserRepository();
		$returnValue = $userRepository->SaveUserUnsubscribeDetail($user_id,$name_of_optin,$reason);
		return $returnValue;
	}

	public function UpdateUserOptin($user_id,$data) {
		$userRepository = new UserRepository();
		$returnValue = $userRepository->UpdateUserOptin($user_id,$data);
		return $returnValue;
	}

	public function DeleteUserAccount($user_id) {
		$host  = $_SERVER['HTTP_HOST'];

		setcookie("UserId", "", time()-36000000,'/',$host);
		setcookie("UserGroupId", "", time()-36000000,'/',$host);
		setcookie("UserEmail", "", time()-36000000,'/',$host);

		$_COOKIE['UserId'] = '';
		$_COOKIE['UserGroupId'] = '';
		$_COOKIE['UserEmail'] = '';

		$userRepository = new UserRepository();
		$returnValue = $userRepository->DeleteUserAccount($user_id);
		return $returnValue;
	}

	public function GetPasswordByUserName($username) {
		$userRepository = new UserRepository();
		$returnValue = $userRepository->GetPasswordByUserName($username);
		return $returnValue;
	}

	public function SaveUserToken($user_id,$token) {
		$userRepository = new UserRepository();
		$returnValue = $userRepository->SaveUserToken($user_id,$token);
		return $returnValue;
	}

	public function UpdateUserToken($user_id,$token_id) {
		$userRepository = new UserRepository();
		$returnValue = $userRepository->UpdateUserToken($user_id,$token_id);
		return $returnValue;
	}

	public function GetUserToken($token) {
		$userRepository = new UserRepository();
		$returnValue = $userRepository->GetUserToken($token);
		return $returnValue;
	}
	
	public function UpdateUserGroup($UserId, $EMail, $GroupId) {
		$userRepository = new UserRepository();
		$returnValue = $userRepository->UpdateUserGroup($UserId, $EMail, $GroupId);
		return $returnValue;
	}

	public function GetUserIds($UserId) {
		$userRepository = new UserRepository();
		$returnValue = $userRepository->GetUserIds($UserId);
		return $returnValue;
	}
	
	public function GetUserDetailByUserIdForSignUp($user_id) {
		$userRepository = new UserRepository();
		$returnValue = $userRepository->GetUserDetailByUserIdForSignUp($user_id);
		return $returnValue;
	}
	

	public function SaveUserBirthDetailStep1($data) {
		$userRepository = new UserRepository();
		$returnValue = $userRepository->SaveUserBirthDetailStep1($data);
		return $returnValue;
	}
	
	public function GetSubScribeListByUserId($user_id) {
		$userRepository = new UserRepository();
		$returnValue = $userRepository->GetSubScribeListByUserId($user_id);
		return $returnValue;
	}
}


if(isset($_REQUEST['task'])) {

	$userObj = new User();
	if($_REQUEST['task'] == "IsEmailAvailable") {

		$email = $_REQUEST['email'];

		if($userObj->IsEmailAvailable($email)) {
			echo 'true';
		}
		else {
			echo 'false';
		}
	}
	else if($_REQUEST['task'] == "SaveUser") {
		$userDTO = new userDTO();
		$userDTO->user_name = $_REQUEST['txtEmail'];
		$userDTO->password = md5($_REQUEST['txtPassword']);
		$userDTO->language = $_REQUEST['ddLanguage'];
		$userDTO->user_group = Enum::$userGroupList['Registered'];
		$userDTO->status = 1;
		$userDTO->portal_id = 0;

		$returnValue = $userObj->SaveUser($userDTO);


		/* AJAX check  */
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			/*if($returnValue)
			 {
			echo $returnValue;
			}
			else
			{
			echo '';
			}	*/

			if($returnValue > 0) {
				$host  = $_SERVER['HTTP_HOST'];
				//$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
				$uri   = DIR_FRONT;
				$extra = 'my-account.php';

				setcookie("UserId", $returnValue, time()+(60*60*24*30),'/',$host);  /* expire in 1 month */
				setcookie("UserGroupId", $userDTO->user_group, time()+(60*60*24*30),'/',$host);  /* expire in 1 month */
				setcookie("UserEmail", $userDTO->user_name, time()+(60*60*24*30),'/',$host);  /* expire in 1 month */

				//header("Location: http://$host$uri/$extra");
				echo 'true';
			}
			else {
				echo 'Error Saving Data';
			}
		}
		else {
		}
	}
	else if($_REQUEST['task'] == "UserSignup") {
		/*echo "<pre>";
		 print_r($_REQUEST);
		exit;*/
		try {
			$userDTO = new userDTO();
			$userDTO->user_name = $_REQUEST['txtEmail'];
			$userDTO->password = md5($_REQUEST['txtPassword']);
			$userDTO->language = $_REQUEST['ddLanguage'];
			$userDTO->user_group = Enum::$userGroupList['Registered'];
			$userDTO->status = 1;
			$userDTO->portal_id = 2;

			$user_id = $userObj->SaveUser($userDTO);

			if($user_id > 0) {
				$rec = array(
						'to'	=> $userDTO->user_name,
				);

				$data = array(
						'subject'			=> "Welcome to Astrowow.com‏",
						'mailtext'			=> "<h1>Hello,</h1>",
						'type'				=> "html",
						//'attachment'		=> array( 'testfile.html' ),
						'language_id'		=> $_REQUEST['ddLanguage'],
						'password'		=> $_REQUEST['txtPassword'],
						'username'		=> $_REQUEST['txtEmail'],
						'name'			=> $_REQUEST['txtFirstName']." ".$_REQUEST['txtLastName'],
						'sitelink'		=> BASEURL
				);
				SendUserEmail($data,$rec);
			}

			$optin = '';
			if(isset($_REQUEST['chkDailySunsign']) && !empty($_REQUEST['chkDailySunsign'])) {
				$optin .= $_REQUEST['chkDailySunsign'].',';
			}
			if(isset($_REQUEST['chkWeeklySunsign']) && !empty($_REQUEST['chkWeeklySunsign'])) {
				$optin .= $_REQUEST['chkWeeklySunsign'].',';
			}
			if(isset($_REQUEST['chkDailyPersonalHoroscope']) && !empty($_REQUEST['chkDailyPersonalHoroscope'])) {
				$optin .= $_REQUEST['chkDailyPersonalHoroscope'].',';
			}
			if(isset($_REQUEST['chkAstrologyArticles']) && !empty($_REQUEST['chkAstrologyArticles'])) {
				$optin .= $_REQUEST['chkAstrologyArticles'].',';
			}
			if(isset($_REQUEST['chkSpecialOffers']) && !empty($_REQUEST['chkSpecialOffers'])) {
				$optin .= $_REQUEST['chkSpecialOffers'];
			}

			$returnValue = $userObj->SaveUserOptin($user_id,$optin);

			$objUserProfileDTO = new userProfileDTO();
			$objUserProfileDTO->UserId = $user_id;
			$objUserProfileDTO->FirstName =$_REQUEST['txtFirstName'];
			$objUserProfileDTO->LastName =$_REQUEST['txtLastName'];
			$objUserProfileDTO->Gender = $_REQUEST['rdoGender'];
			$objUserProfileDTO->city = $_REQUEST['txtBirthCity'];
			$objUserProfileDTO->state = $_REQUEST['hdnState'];
			$objUserProfileDTO->country = $_REQUEST['ddBirthCountry'];

			$returnValue = $userObj->SaveUserProfile($objUserProfileDTO);

			$objUserbIRTHDTO = new userBirthDetailDTO();

			$objUserbIRTHDTO->UserId = $user_id;
			$objUserbIRTHDTO->Day = $_REQUEST['ddDay'];
			$objUserbIRTHDTO->Month = $_REQUEST['ddMonth'];
			$objUserbIRTHDTO->Year = $_REQUEST['ddYear'];
			$objUserbIRTHDTO->Hours = $_REQUEST['birthhour'];
			$objUserbIRTHDTO->Minutes = $_REQUEST['birthminute'];
			//$objUserbIRTHDTO->Seconds = $_REQUEST['txtUserEmail'];
			if($_REQUEST['birthhour'] == "-1") {
				//$objUserbIRTHDTO->unTimed = 0;
				$objUserbIRTHDTO->unTimed = 1;
			}
			else {
				//$objUserbIRTHDTO->unTimed = 1;
				$objUserbIRTHDTO->unTimed = 0;
			}

			$objUserbIRTHDTO->sunsign = CalculateSunsignFromDate($_REQUEST['ddMonth'],$_REQUEST['ddDay']);
			$objUserbIRTHDTO->country = $_REQUEST['ddBirthCountry'];
			$objUserbIRTHDTO->state = $_REQUEST['hdnState'];
			$objUserbIRTHDTO->country_name = $_REQUEST['hdncurrent_country_name'];
			$objUserbIRTHDTO->city = $_REQUEST['txtBirthCity'];

			//$objUserbIRTHDTO->GMT = $_REQUEST['txtUserEmail'];
			//$objUserbIRTHDTO->zoneref = $_REQUEST['txtUserEmail'];
			//$objUserbIRTHDTO->summerref = $_REQUEST['txtUserEmail'];
			$objUserbIRTHDTO->longitude = $_REQUEST['hdnLongitude'];
			$objUserbIRTHDTO->latitude = $_REQUEST['hdnLatitude'];
			//$objUserbIRTHDTO->CreatedDate = $_REQUEST['txtUserEmail'];
			//$objUserbIRTHDTO->ModifiedDate = $_REQUEST['txtUserEmail'];
			//$objUserbIRTHDTO->CreatedBy = $_REQUEST['txtUserEmail'];
			//$objUserbIRTHDTO->ModifiedBy = $_REQUEST['txtUserEmail'];

			$returnValue = $userObj->SaveUserBirthDetail($objUserbIRTHDTO);

			if($returnValue > 0) {
				$host  = $_SERVER['HTTP_HOST'];
				//$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
				$uri   = DIR_FRONT;
				//$extra = 'my-astropage.php';
				$extra = 'thank-you-user-registration.php';

				setcookie("UserId", $user_id, time()+(60*60*24*30),'/',$host);  /* expire in 1 month */
				setcookie("UserGroupId", $userDTO->user_group, time()+(60*60*24*30),'/',$host);  /* expire in 1 month */
				setcookie("UserEmail", $userDTO->user_name, time()+(60*60*24*30),'/',$host);  /* expire in 1 month */

				header("Location: http://$host$uri/$extra");
				//echo 'true';
			}
			else {
				$request_url = $_SERVER['HTTP_REFERER'];
				echo
				"<html>".
				"<body onLoad=\"document.forms['user_form'].submit();\">".
				"<form method=\"post\" name=\"user_form\" action=\"".$request_url."\">";

				echo "<input type=\"hidden\" name=\"error\" value=\"Error Saving Data\"/>";

				echo 		  "</form>".			  "</body>".		  "</html>";
			}
		}
		catch(Exception $ex) {
			$request_url = $_SERVER['HTTP_REFERER'];
			echo
			"<html>".
			"<body onLoad=\"document.forms['user_form'].submit();\">".
			"<form method=\"post\" name=\"user_form\" action=\"".$request_url."\">";

			echo "<input type=\"hidden\" name=\"error\" value=\"".print_r($ex)."\"/>";

			echo 		  "</form>".			  "</body>".		  "</html>";
		}
	}
	else if($_REQUEST['task'] == "SignInUser") {
		$txtEmail = isset($_REQUEST['txtEmail']) ? $_REQUEST['txtEmail'] : '';
		$txtPassword = isset($_REQUEST['txtPassword']) ? $_REQUEST['txtPassword'] : '';
		
		if($txtEmail == ""){
			echo "false";
			die();
		}
		if($txtPassword == ""){
			echo "false";
			die();
		}
		
		if($userObj->validateUserLogon($txtEmail, $txtPassword)) {
			echo 'true';
		}
		else {
			echo 'false';
		}
	}
	else if($_REQUEST['task'] == "GetPassword") {
		echo 'in';
		$sender = "astrowow-team@astrowow.com";
		/*$rec = array(
			'to'	=> $_REQUEST['txtEmail'].', He <dhruv.sarvaiya@n-techcorporate.com>',
		);*/
		$rec = array(
				'to'	=> 'dhruv.sarvaiya@n-techcorporate.com',
		);

		$data = array(
				'subject'	=> "This is a testmail",
				'mailtext'	=> "<h1>Welcome to generic test Mail.</h1>",
				'type'		=> "html",
				'attachment'	=> array( 'testfile.html' )
		);

		if ( genericMail::sendmail( $sender, $rec, $data ) ) {
			print "Mail was send successfull ... \n";
		} else {
			print "Hmm .. there could be a Problem ... \n";
		}
	}
	else if($_REQUEST['task'] == "SaveUserHororyDetail") {
		$objUserHororyDTO = new userHororyDetailDTO();
		$objUserHororyDTO->Address 		= $_REQUEST['txtCurrentAddress'];
		$objUserHororyDTO->city 		= $_REQUEST['txtCurrentCity'];
		$objUserHororyDTO->country 		= $_REQUEST['ddCurrentCountry'];
		$objUserHororyDTO->state 		= $_REQUEST['hdnCurrentState'];
		$objUserHororyDTO->UserId 		= $_REQUEST['user_id'];
		$objUserHororyDTO->latitude 	= $_REQUEST['hdnCurrentLatitude'];
		$objUserHororyDTO->longitude 	= $_REQUEST['hdnCurrentLongitude'];
		$objUserHororyDTO->country_name 	= $_REQUEST['hdncurrent_country_name'];


		if(isset($_REQUEST['hdnUserHororyId']) && !empty($_REQUEST['hdnUserHororyId'])) {
			// update
			$objUserHororyDTO->UserHororyId = $_REQUEST['hdnUserHororyId'];
			$result = $userObj->UpdateUserHororyDetail($objUserHororyDTO);
			if($result > 0) {
				$message = "Horary Detail Updated Successfully";
			}
			else {
				$message = "Error occured while saving data";
			}
		}
		else {
			// insert
			$objUserHororyDTO->UserHororyId = 0;
			$result = $userObj->saveUserHororyDetail($objUserHororyDTO);
			if($result > 0) {
				$message = "Horary Detail Saved Successfully";
			}
			else {
				$message = "Error occured while saving data";
			}
		}
		$request_url = '../my-account.php';
		echo
		"<html>".
		"<body onLoad=\"document.forms['user_form'].submit();\">".
		"<form method=\"post\" name=\"user_form\" action=\"".$request_url."\">";

		echo "<input type=\"hidden\" name=\"message\" value=\"".$message."\"/>";

		echo 		  "</form>".			  "</body>".		  "</html>";
	}
	else if($_REQUEST['task'] == "SaveUserPersonalDetail") {
		$userProfileDto = new userProfileDTO();
		$userProfileDto->FirstName 		= $_REQUEST['txtFirstName'];
		$userProfileDto->LastName 		= $_REQUEST['txtLastName'];
		$userProfileDto->UserId 		= $_REQUEST['user_id'];
		$userProfileDto->Gender 		= $_REQUEST['rdoGender'];
		$userProfileDto->Address 		= $_REQUEST['txtAdd1'];
		$userProfileDto->city 			= $_REQUEST['txtCity'];
		$userProfileDto->state 			= $_REQUEST['txtState'];
		$userProfileDto->country 		= $_REQUEST['txtCountry'];
		$userProfileDto->Phone 			= $_REQUEST['txtTelephone'];
		$userProfileDto->Zip 			= $_REQUEST['txtPostcode'];

		if(isset($_REQUEST['hdnUserProfileId']) && !empty($_REQUEST['hdnUserProfileId'])) {
			// update
			$userProfileDto->UserProfileId 		= $_REQUEST['hdnUserProfileId'];
			$result = $userObj->UpdateUserProfile($userProfileDto);
			if($result > 0) {
				$message = "Profile Detail Updated Successfully";
			}
			else {
				$message = "Error occured while saving data";
			}
		}
		else {
			// insert
			$userProfileDto->UserProfileId 		= 0;
			$result = $userObj->SaveUserProfile($userProfileDto);
			if($result > 0) {
				$message = "Profile Detail Saved Successfully";
			}
			else {
				$message = "Error occured while saving data";
			}
		}
		$request_url = '../my-account.php';
		echo
		"<html>".
		"<body onLoad=\"document.forms['user_form'].submit();\">".
		"<form method=\"post\" name=\"user_form\" action=\"".$request_url."\">";

		echo "<input type=\"hidden\" name=\"message\" value=\"".$message."\"/>";

		echo 		  "</form>".			  "</body>".		  "</html>";
	}
	else if($_REQUEST['task'] == "SaveUserBirthDetail") {
		$userBirthDto = new userBirthDetailDTO();
		$userBirthDto->UserId			= $_REQUEST['user_id'];
		$userBirthDto->city			= $_REQUEST['txtBirthCity'];
		$userBirthDto->country			= $_REQUEST['ddBirthCountry'];
		$userBirthDto->state			= $_REQUEST['hdnState'];
		$userBirthDto->Minutes			= $_REQUEST['birthminute'];
		$userBirthDto->Hours			= $_REQUEST['birthhour'];
		$userBirthDto->latitude			= $_REQUEST['hdnLatitude'];
		$userBirthDto->longitude		= $_REQUEST['hdnLongitude'];
		$userBirthDto->Day			= $_REQUEST['ddDay'];
		$userBirthDto->Month			= $_REQUEST['ddMonth'];
		$userBirthDto->Year			= $_REQUEST['ddYear'];
		$userBirthDto->country_name		= $_REQUEST['hdnContryName'];
		$userBirthDto->sunsign = CalculateSunsignFromDate($_REQUEST['ddMonth'],$_REQUEST['ddDay']);

		if($_REQUEST['birthhour'] == "-1") {
			$userBirthDto->unTimed = 1;
		}
		else {
			$userBirthDto->unTimed = 0;
		}

		if(isset($_REQUEST['hdnUserBirthDetailId']) && !empty($_REQUEST['hdnUserBirthDetailId'])) {
			// update
			$userBirthDto->UserBirthDetailId = $_REQUEST['hdnUserBirthDetailId'];
			$result = $userObj->updateUserBirthDetail($userBirthDto);
			if($result > 0) {
				$message = "Birth Detail Updated Successfully";
			}
			else {
				$message = "Error occured while saving data";
			}
		}
		else {
			// insert
			$userBirthDto->UserBirthDetailId = 0;
			$result = $userObj->SaveUserBirthDetail($userBirthDto);
			if($result > 0) {
				$message = "Birth Detail Saved Successfully";
			}
			else {
				$message = "Error occured while saving data";
			}
		}

		$objOrder = new Order();
		$objOrder->UpdateOrderForPreviewReport($userBirthDto);

		$request_url = '../my-account.php';
		echo
		"<html>".
		"<body onLoad=\"document.forms['user_form'].submit();\">".
		"<form method=\"post\" name=\"user_form\" action=\"".$request_url."\">";

		echo "<input type=\"hidden\" name=\"message\" value=\"".$message."\"/>";

		echo 		  "</form>".			  "</body>".		  "</html>";

	}
	else if($_REQUEST['task'] == "ChangePassword") {
		$result = $userObj->CheckPassword($_REQUEST['user_id'],$_REQUEST['txtOldPassword']);
		//print_r($result);
		if($result) {
			$result = $userObj->ChangePassword($_REQUEST['user_id'],$_REQUEST['txtNewPassword']);
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
	else if($_REQUEST['task'] == "ForgetPassword") {
		$email = $_REQUEST['email'];

		if(!$userObj->IsEmailAvailable($email)) {
			//echo 'true';
			$user_result = $userObj->GetPasswordByUserName($email);

			if(!empty($user_result)) {
				$user_id = $user_result['UserId'];
				$token = md5(uniqid(rand(),1));

				$result = $userObj->SaveUserToken($user_id,$token);
				$reset_password_link = BASEURL.'reset-password.php?token='.$token;

				$password = $result['Password'];
				//$_REQUEST['language_id'] = $result['DefaultLanguage'];
				$rec = array(
						'to'	=> $email,
				);

				$data = array(
						'subject'				=> "Astrowow.com Password Reset Link",
						'mailtext'				=> "",
						'type'					=> "html",
						'reset_password_link'	=> $reset_password_link,
						'username'				=> $email,
						'language_id' 			=> $user_result['DefaultLanguage'],
						'name' 					=> $user_result['FirstName'],
						'sitelink'				=> BASEURL
						//'sitelink'				=> "http://www.atrowow.com/"

				);
				SendResetPasswordLinkToUser($data,$rec);
				echo '0';
			}
			else {
				echo '2';
			}
		}
		else {
			echo '1';
		}
	}
	else if($_REQUEST['task'] == "SavePersonDetail") {
		//echo '<pre>';
		//print_r($_REQUEST);
		//exit;
		$user_id = $_REQUEST['user_id'];
		if(empty($_REQUEST['person_id'])) {
			// Insert
			$userDTO = new userDTO();
			//$userDTO->user_name = $_REQUEST['txtUserEmail'];
			$userDTO->user_name = $_REQUEST['txtFirstName'];
			$userDTO->password = rand_passwd_generator();
			$userDTO->language = 'en'; // For now we use static value
			$userDTO->user_group = Enum::$userGroupList['AnotherPerson'];
			$userDTO->status = 0;
			$userDTO->portal_id = 2;

			$person_id = $userObj->SavePerson($userDTO,$user_id);
		}
		else {
			$person_id = $_REQUEST['person_id'];
			// Update
		}

		if(!empty($person_id)) {
			$objUserProfileDTO = new userProfileDTO();
			$objUserProfileDTO->UserId = $person_id;
			$objUserProfileDTO->FirstName =$_REQUEST['txtFirstName'];
			$objUserProfileDTO->LastName =$_REQUEST['txtLastName'];
			$objUserProfileDTO->Gender = $_REQUEST['rdoGender'];

			if(!empty($_REQUEST['hdnUserProfileId'])) {
				$objUserProfileDTO->UserProfileId 		= $_REQUEST['hdnUserProfileId'];
				$result = $userObj->UpdateUserProfile($objUserProfileDTO);
			}
			else {
				$result = $userObj->SaveUserProfile($objUserProfileDTO);
			}

			$objUserbIRTHDTO = new userBirthDetailDTO();
			$objUserbIRTHDTO->UserId = $person_id;
			$objUserbIRTHDTO->Day = $_REQUEST['ddDay'];
			$objUserbIRTHDTO->Month = $_REQUEST['ddMonth'];
			$objUserbIRTHDTO->Year = $_REQUEST['ddYear'];
			$objUserbIRTHDTO->Hours = $_REQUEST['birthhour'];
			$objUserbIRTHDTO->Minutes = $_REQUEST['birthminute'];
			if($_REQUEST['birthhour'] == "-1") {
				//$objUserbIRTHDTO->unTimed = 0;
				$objUserbIRTHDTO->unTimed = 1;
			}
			else {
				//$objUserbIRTHDTO->unTimed = 1;
				$objUserbIRTHDTO->unTimed = 0;
			}
			$objUserbIRTHDTO->sunsign = CalculateSunsignFromDate($_REQUEST['ddMonth'],$_REQUEST['ddDay']);
			$objUserbIRTHDTO->country = $_REQUEST['ddBirthCountry'];
			$objUserbIRTHDTO->state = $_REQUEST['hdnState'];
			$objUserbIRTHDTO->country_name = $_REQUEST['hdn_country_name'];
			$objUserbIRTHDTO->city = $_REQUEST['txtBirthCity'];

			//$objUserbIRTHDTO->GMT = $_REQUEST['txtUserEmail'];
			//$objUserbIRTHDTO->zoneref = $_REQUEST['txtUserEmail'];
			//$objUserbIRTHDTO->summerref = $_REQUEST['txtUserEmail'];
			$objUserbIRTHDTO->longitude = $_REQUEST['hdnLongitude'];
			$objUserbIRTHDTO->latitude = $_REQUEST['hdnLatitude'];
			//$objUserbIRTHDTO->CreatedDate = $_REQUEST['txtUserEmail'];
			//$objUserbIRTHDTO->ModifiedDate = $_REQUEST['txtUserEmail'];
			//$objUserbIRTHDTO->CreatedBy = $_REQUEST['txtUserEmail'];
			//$objUserbIRTHDTO->ModifiedBy = $_REQUEST['txtUserEmail'];



			if(!empty($_REQUEST['hdnUserBirthDetailId'])) {
				$objUserbIRTHDTO->UserBirthDetailId = $_REQUEST['hdnUserBirthDetailId'];
				$result = $userObj->updateUserBirthDetail($objUserbIRTHDTO);
			}
			else {
				$returnValue = $userObj->SaveUserBirthDetail($objUserbIRTHDTO);
			}

			// make payment
			if(isset($_REQUEST['makePayment']) && $_REQUEST['makePayment'] == 1) {
				// first create order
				$_REQUEST['ddProductType'] = 6;
				$_REQUEST['ddLanguage'] = 'en';
				$_REQUEST['txtUserEmail'] = $_COOKIE['UserEmail'];

				// Order Status 1 = awaiting for payment, 2 = payment accepted

				$objOrderDTO = new orderDTO();

				$objOrderDTO->delivery_option = 1;
				$objOrderDTO->user_id = $user_id;
				$objOrderDTO->product_item_id = $_REQUEST['product_id'];
				$objOrderDTO->price = $_REQUEST['hdnPrice'];
				if(isset($data['discount'])) {
					$objOrderDTO->discount = $_REQUEST['hdnDiscount'];
				}
				$objOrderDTO->order_date = date('Y-m-d');
				$objOrderDTO->order_status = '1';
				$objOrderDTO->product_type = 6;
				$objOrderDTO->currency_code = $_REQUEST['hdnCurrencyCode'];

				$objOrderDTO->email_id = $_REQUEST['txtUserEmail'];

				$objOrder = new Order();
				$returnValue = $objOrder->SaveOrder($objOrderDTO);

				if($returnValue > 0) {
					//insert data into user subscription
					$subscriptionUserDTO = new SubscriptionUserDTO();
					$objSubscription = new Subscription();
					$start = date('Y-m-d');
					$d = new DateTime( $start );
					//$d->setDate(date('Y', 'W', (date('d')+90)));
					$d->modify( "+30" );
					$end = $d->format( 'Y-m-d' );

					$objSubscription->user_id = $person_id;
					$objSubscription->subscription_id = 7;
					$objSubscription->start_date = $start;
					$objSubscription->end_date = $end;
					$objSubscription->status = 1;

					$subscription_user_id = $objSubscription->SaveSubscribedUser($objSubscription);


					$order_id = $returnValue;
					$queryString = "action=process";
					$queryString .= '&orderid='.$order_id;
					//$queryString .= '&product_item_id='.$_REQUEST['hdnId'];

					$queryString .= '&currency_code='.$_REQUEST['hdnCurrencyCode'];
					$queryString .= '&product_name='.$_REQUEST['product_name'];
					$queryString .= '&product_price='.$_REQUEST['hdnPrice'];
					$queryString .= '&product_discount='.$_REQUEST['hdnDiscount'];
					$queryString .= '&product_shipping_charge=0';
					$queryString .= '&order_id='.$order_id;
					$queryString .= '&prefix=AWRP-';
					$queryString .= '&product_item_id=AWRP-'.$order_id;
					$queryString .= '&user_id='.$subscription_user_id;
					$extra = '../helper/paypal/add-another/paypal.php?'.$queryString;
					//$m_url_success = ROOTPATH.'helper/paypal.php';
					//$m_url_failure = $s['HTTP_REFERER'];

					$host  = $_SERVER['HTTP_HOST'];
					$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
					header("Location: http://$host$uri/$extra");
					exit;
				}
			}


			$uri   = BASEURL.'my-astropage.php';
			header("Location: $uri");
			//echo 'call';
			exit;
		}




	}
	else if($_REQUEST['task'] == "GetAnotherPersonListByUserId") {
		$user_id = $_REQUEST['user_id'];
		$result = $userObj->GetAnotherPersonListByUserId($user_id);
		echo json_encode($result);
	}
	else if($_REQUEST['task'] == 'signupFromGC') {
		//print_r($_REQUEST);
		//$arr = unserialize($_REQUEST['requestData']);
		//$test = explode("&", $_REQUEST['requestData']);
		//print_r($test);

		$userDTO = new userDTO();
		$userDTO->user_name = $_REQUEST['txtEmail'];
		$userDTO->password = $_REQUEST['txtPassword'];
		$userDTO->language = $_REQUEST['ddLanguage'];
		$userDTO->user_group = Enum::$userGroupList['Registered'];
		$userDTO->status = 1;
		$userDTO->portal_id = 2;

		$user_id = $userObj->SaveUser($userDTO);

		if($user_id > 0) {
			$rec = array(
					'to'	=> $userDTO->user_name,
			);

			$data = array(
					'subject'			=> "Welcome to Astrowow.com‏",
					'mailtext'			=> "<h1>Hello,</h1>",
					'type'                      => "html",
					//'attachment'		=> array( 'testfile.html' ),
					'language_id'		=> $_REQUEST['ddLanguage'],
					'password'                  => $_REQUEST['txtPassword'],
					'username'                  => $_REQUEST['txtEmail'],
					'name'			=> $_REQUEST['txtFirstName']." ".$_REQUEST['txtLastName'],
					'sitelink'                  => BASEURL

			);

			SendUserEmail($data,$rec);
		}


		$optin = '';
		if(isset($_REQUEST['chkDailySunsign']) && !empty($_REQUEST['chkDailySunsign'])) {
			$optin .= $_REQUEST['chkDailySunsign'].',';
		}
		if(isset($_REQUEST['chkWeeklySunsign']) && !empty($_REQUEST['chkWeeklySunsign'])) {
			$optin .= $_REQUEST['chkWeeklySunsign'].',';
		}
		if(isset($_REQUEST['chkDailyPersonalHoroscope']) && !empty($_REQUEST['chkDailyPersonalHoroscope'])) {
			$optin .= $_REQUEST['chkDailyPersonalHoroscope'].',';
		}
		if(isset($_REQUEST['chkAstrologyArticles']) && !empty($_REQUEST['chkAstrologyArticles'])) {
			$optin .= $_REQUEST['chkAstrologyArticles'].',';
		}
		if(isset($_REQUEST['chkSpecialOffers']) && !empty($_REQUEST['chkSpecialOffers'])) {
			$optin .= $_REQUEST['chkSpecialOffers'];
		}

		$returnValue = $userObj->SaveUserOptin($user_id,$optin);

		$objUserProfileDTO = new userProfileDTO();
		$objUserProfileDTO->UserId = $user_id;
		$objUserProfileDTO->FirstName =$_REQUEST['txtFirstName'];
		$objUserProfileDTO->LastName =$_REQUEST['txtLastName'];
		//$objUserProfileDTO->Gender = $_REQUEST['rdoGender'];
		$objUserProfileDTO->city = $_REQUEST['txtBirthCity'];
		$objUserProfileDTO->state = $_REQUEST['hdnState'];
		$objUserProfileDTO->country = $_REQUEST['ddBirthCountry'];

		$returnValue = $userObj->SaveUserProfile($objUserProfileDTO);

		$objUserbIRTHDTO = new userBirthDetailDTO();

		$objUserbIRTHDTO->UserId = $user_id;
		$objUserbIRTHDTO->Day = $_REQUEST['ddDay'];
		$objUserbIRTHDTO->Month = $_REQUEST['ddMonth'];
		$objUserbIRTHDTO->Year = $_REQUEST['ddYear'];
		$objUserbIRTHDTO->Hours = $_REQUEST['birthhour'];
		$objUserbIRTHDTO->Minutes = $_REQUEST['birthminute'];
		if($_REQUEST['birthhour'] == "-1") {
			//            $objUserbIRTHDTO->unTimed = 0;
			$objUserbIRTHDTO->unTimed = 1;
		}
		else {
			//            $objUserbIRTHDTO->unTimed = 1;
			$objUserbIRTHDTO->unTimed = 0;
		}
		$objUserbIRTHDTO->sunsign = CalculateSunsignFromDate($_REQUEST['ddMonth'],$_REQUEST['ddDay']);
		$objUserbIRTHDTO->country = $_REQUEST['ddBirthCountry'];
		$objUserbIRTHDTO->state = $_REQUEST['hdnState'];
		$objUserbIRTHDTO->country_name = $_REQUEST['hdncurrent_country_name'];
		$objUserbIRTHDTO->city = $_REQUEST['txtBirthCity'];
		$objUserbIRTHDTO->longitude = $_REQUEST['hdnLongitude'];
		$objUserbIRTHDTO->latitude = $_REQUEST['hdnLatitude'];

		$returnValue = $userObj->SaveUserBirthDetail($objUserbIRTHDTO);

		if($returnValue > 0) {
			$host  = $_SERVER['HTTP_HOST'];
			setcookie("UserId", $user_id, time()+(60*60*24*30),'/',$host);  /* expire in 1 month */
			setcookie("UserGroupId", $userDTO->user_group, time()+(60*60*24*30),'/',$host);  /* expire in 1 month */
			setcookie("UserEmail", $userDTO->user_name, time()+(60*60*24*30),'/',$host);  /* expire in 1 month */
			echo 'true';
		}
		else {
			echo 'false';
		}
	}
	else if($_REQUEST['task'] == 'unsubscribe') {
		$user_id 					= $_REQUEST['user_id'];

		/*$chkDailySunsign 			= $_REQUEST['chkDailySunsign'];
		 $chkWeeklySunsign 			= $_REQUEST['chkWeeklySunsign'];
		$chkDailyPersonalHoroscope 	= $_REQUEST['chkDailyPersonalHoroscope'];
		$chkAstrologyArticles 		= $_REQUEST['chkAstrologyArticles'];
		$chkSpecialOffers 			= $_REQUEST['chkSpecialOffers'];*/

		$reason 					= $_REQUEST['txtReson'];
		$txtFirstName 				= $_REQUEST['txtFirstName'];

		$optin = '';
		$name_of_optin = '';
		if(isset($_REQUEST['chkDailySunsign']) && !empty($_REQUEST['chkDailySunsign'])) {
			$optin .= $_REQUEST['chkDailySunsign'].',';
			$name_of_optin = 'Daily Sunsign';
		}
		if(isset($_REQUEST['chkWeeklySunsign']) && !empty($_REQUEST['chkWeeklySunsign'])) {
			$optin .= $_REQUEST['chkWeeklySunsign'].',';
			$name_of_optin .= ' , Weekly Sunsign';
		}
		if(isset($_REQUEST['chkDailyPersonalHoroscope']) && !empty($_REQUEST['chkDailyPersonalHoroscope'])) {
			$optin .= $_REQUEST['chkDailyPersonalHoroscope'].',';
			$name_of_optin .= ' , Daily Personal Horoscope';
		}
		if(isset($_REQUEST['chkAstrologyArticles']) && !empty($_REQUEST['chkAstrologyArticles'])) {
			$optin .= $_REQUEST['chkAstrologyArticles'].',';
			$name_of_optin .= ' , Astrology Articles';
		}
		if(isset($_REQUEST['chkSpecialOffers']) && !empty($_REQUEST['chkSpecialOffers'])) {
			$optin .= $_REQUEST['chkSpecialOffers'];
			$name_of_optin .= ' , Special Offers';
		}

		$returnValue = $userObj->UpdateUserOptin($user_id,$optin);

		if($returnValue == 1) {
			$returnValue = $userObj->SaveUserUnsubscribeDetail($user_id,$name_of_optin,$reason);
		}

		if(empty($returnValue)) {
			echo 'false';
		}
		else {
			echo 'true';
		}
	}
	else if($_REQUEST['task'] == 'subscribeEmail') {
		$user_id 					= $_REQUEST['user_id'];
		$optin = '';
		if(isset($_REQUEST['chkDailySunsign']) && !empty($_REQUEST['chkDailySunsign'])) {
			$optin .= $_REQUEST['chkDailySunsign'].',';
		}
		if(isset($_REQUEST['chkWeeklySunsign']) && !empty($_REQUEST['chkWeeklySunsign'])) {
			$optin .= $_REQUEST['chkWeeklySunsign'].',';
		}
		if(isset($_REQUEST['chkDailyPersonalHoroscope']) && !empty($_REQUEST['chkDailyPersonalHoroscope'])) {
			$optin .= $_REQUEST['chkDailyPersonalHoroscope'].',';
		}
		if(isset($_REQUEST['chkAstrologyArticles']) && !empty($_REQUEST['chkAstrologyArticles'])) {
			$optin .= $_REQUEST['chkAstrologyArticles'].',';
		}
		if(isset($_REQUEST['chkSpecialOffers']) && !empty($_REQUEST['chkSpecialOffers'])) {
			$optin .= $_REQUEST['chkSpecialOffers'];
		}
		//echo $optin;
		$returnValue = $userObj->SaveUserOptin($user_id,$optin);

		if(empty($returnValue)) {
			$message = 'Error occured while saving optin data';
		}
		else {
			$message = 'Thank you, changes are done successfully';
		}
		// exit;
		$request_url = '../my-account.php';
		echo
		"<html>".
		"<body onLoad=\"document.forms['user_form'].submit();\">".
		"<form method=\"post\" name=\"user_form\" action=\"".$request_url."\">";

		echo "<input type=\"hidden\" name=\"message\" value=\"".$message."\"/>";

		echo 		  "</form>".			  "</body>".		  "</html>";
	}
	else if($_REQUEST['task'] == 'ResetPassword') {
		$result = $userObj->ChangePassword($_REQUEST['user_id'],$_REQUEST['txtNewPassword']);
		
		try{
			$userObj->UpdateUserToken($_POST['user_id'],$_POST['token']);
		} catch (Exception $ex){
		}
		
		if($result) {
			echo '0';
		}
		else {
			echo '2';
		}
	}
	else if($_REQUEST['task'] == 'UserSignUpStep1') {
		try {
			$userDTO = new userDTO();
			$userDTO->user_name = $_REQUEST['txtEmail1'];
			$userDTO->password = md5($_REQUEST['txtPassword']);
			$userDTO->language = $_REQUEST['ddLanguage'];
			$userDTO->user_group = Enum::$userGroupList['Signup'];
			$userDTO->status = 1;
			$userDTO->portal_id = 2;

			$user_id = $userObj->SaveUser($userDTO);

			if($user_id > 0) {
				$rec = array('to'	=> $userDTO->user_name);

				$data = array(
						'subject'			=> "Welcome to Astrowow.com‏",
						'mailtext'			=> "<h1>Hello,</h1>",
						'type'				=> "html",
						'language_id'		=> $_REQUEST['ddLanguage'],
						'password'			=> $_REQUEST['txtPassword'],
						'username'			=> $_REQUEST['txtEmail1'],
						'name'				=> $_REQUEST['txtFirstName1']." ".$_REQUEST['txtLastName1'],
						'sitelink'			=> BASEURL
				);
				SendUserEmail($data,$rec);
			}

			$optin = '';
			if(isset($_REQUEST['chkDailySunsign']) && !empty($_REQUEST['chkDailySunsign'])) {
				$optin .= $_REQUEST['chkDailySunsign'].',';
			}
			if(isset($_REQUEST['chkWeeklySunsign']) && !empty($_REQUEST['chkWeeklySunsign'])) {
				$optin .= $_REQUEST['chkWeeklySunsign'].',';
			}
			if(isset($_REQUEST['chkDailyPersonalHoroscope']) && !empty($_REQUEST['chkDailyPersonalHoroscope'])) {
				$optin .= $_REQUEST['chkDailyPersonalHoroscope'].',';
			}
			if(isset($_REQUEST['chkAstrologyArticles']) && !empty($_REQUEST['chkAstrologyArticles'])) {
				$optin .= $_REQUEST['chkAstrologyArticles'].',';
			}
			if(isset($_REQUEST['chkSpecialOffers']) && !empty($_REQUEST['chkSpecialOffers'])) {
				$optin .= $_REQUEST['chkSpecialOffers'];
			}

			$returnValue = $userObj->SaveUserOptin($user_id,$optin);

			$objUserProfileDTO = new userProfileDTO();
			$objUserProfileDTO->UserId = $user_id;
			$objUserProfileDTO->FirstName =$_REQUEST['txtFirstName1'];
			$objUserProfileDTO->LastName =$_REQUEST['txtLastName1'];
			$objUserProfileDTO->Gender = $_REQUEST['rdoGender1'];
			$objUserProfileDTO->city = NULL;
			$objUserProfileDTO->state = NULL;
			$objUserProfileDTO->country = NULL;

			$returnValue = $userObj->SaveUserProfile($objUserProfileDTO);

			$objUserbIRTHDTO = new userBirthDetailDTO();

			$objUserbIRTHDTO->UserId = $user_id;
			$objUserbIRTHDTO->Day = NULL;
			$objUserbIRTHDTO->Month = NULL;
			$objUserbIRTHDTO->Year = NULL;
			$objUserbIRTHDTO->Hours = NULL;
			$objUserbIRTHDTO->Minutes = NULL;

			$objUserbIRTHDTO->unTimed = 0;
			
			$objUserbIRTHDTO->sunsign = NULL;
			$objUserbIRTHDTO->country = NULL;
			$objUserbIRTHDTO->state = NULL;
			$objUserbIRTHDTO->country_name = NULL;
			$objUserbIRTHDTO->city = NULL;
			$objUserbIRTHDTO->longitude = NULL;
			$objUserbIRTHDTO->latitude = NULL;
			
			$returnValue = $userObj->SaveUserBirthDetailStep1($objUserbIRTHDTO);

			if($returnValue > 0) {
				$host  = $_SERVER['HTTP_HOST'];
				$uri   = DIR_FRONT;
				$extra = 'thank-you-user-registration.php';

				setcookie("UserId", $user_id, time()+(60*60*24*30),'/',$host);  						/* Expire in 1 month */
				setcookie("UserGroupId", $userDTO->user_group, time() + (60*60*24*30), '/', $host);  		/* Expire in 1 month */
				setcookie("UserEmail", $userDTO->user_name, time() + (60*60*24*30), '/', $host);  			/* Expire in 1 month */
				
				$UserIDArray = array("UserId" => $user_id, "Code" => 0, "Message" => "");
				echo json_encode($UserIDArray);
			}
			else {
				$UserIDArray = array("UserId" => 0, "Code" => 1, "Message" => "Error while saving data");
				echo json_encode($UserIDArray);
			}
		}
		catch(Exception $ex) {
			$UserIDArray = array("UserId" => 0, "Code" => 1, "Message" => "Error while saving data");
			echo json_encode($UserIDArray);
		}
	}
	else if($_REQUEST['task'] == 'UserSignUpStep2') {
		try {			
			$UserId = trim($_REQUEST['hdnUserId']);
			$EMail = trim($_REQUEST['txtEmail']);
			$GroupId = Enum::$userGroupList['Registered'];
			
			$UserBirthDetailId = "";
			$UserProfileId = "";
			
			//$userObj->UpdateUserGroup($UserId, $EMail, $GroupId);
			$userObj->UpdateUserGroup($UserId, $EMail, 3);
			$ResultSetId = $userObj->GetUserIds($UserId);
						
			foreach($ResultSetId as $Item){				
				$UserBirthDetailId = $Item['UserBirthDetailId'];
				$UserProfileId =  $Item['UserProfileId'];
			}
				
			$optin = '';
			if(isset($_REQUEST['chkDailySunsign']) && !empty($_REQUEST['chkDailySunsign'])) {
				$optin .= $_REQUEST['chkDailySunsign'].',';
			}
			if(isset($_REQUEST['chkWeeklySunsign']) && !empty($_REQUEST['chkWeeklySunsign'])) {
				$optin .= $_REQUEST['chkWeeklySunsign'].',';
			}
			if(isset($_REQUEST['chkDailyPersonalHoroscope']) && !empty($_REQUEST['chkDailyPersonalHoroscope'])) {
				$optin .= $_REQUEST['chkDailyPersonalHoroscope'].',';
			}
			if(isset($_REQUEST['chkAstrologyArticles']) && !empty($_REQUEST['chkAstrologyArticles'])) {
				$optin .= $_REQUEST['chkAstrologyArticles'].',';
			}
			if(isset($_REQUEST['chkSpecialOffers']) && !empty($_REQUEST['chkSpecialOffers'])) {
				$optin .= $_REQUEST['chkSpecialOffers'];
			}

			$returnValue = $userObj->SaveUserOptin($UserId, $optin);

			$objUserProfileDTO = new userProfileDTO();
			$objUserProfileDTO->UserId = $UserId;
			$objUserProfileDTO->UserProfileId = $UserProfileId;
			$objUserProfileDTO->FirstName = $_REQUEST['txtFirstName'];
			$objUserProfileDTO->LastName = $_REQUEST['txtLastName'];
			$objUserProfileDTO->Gender = $_REQUEST['rdoGender'];
			$objUserProfileDTO->city = $_REQUEST['txtBirthCity'];
			$objUserProfileDTO->state = $_REQUEST['hdnState'];
			$objUserProfileDTO->country = $_REQUEST['ddBirthCountry'];

//			$returnValue = $userObj->SaveUserProfile($objUserProfileDTO);
			$returnValue = $userObj->UpdateUserProfile($objUserProfileDTO);

			$objUserbIRTHDTO = new userBirthDetailDTO();
			
			$objUserbIRTHDTO->UserId = $UserId;
			$objUserbIRTHDTO->UserBirthDetailId = $UserBirthDetailId;
			$objUserbIRTHDTO->Day = $_REQUEST['ddDay'];
			$objUserbIRTHDTO->Month = $_REQUEST['ddMonth'];
			$objUserbIRTHDTO->Year = $_REQUEST['ddYear'];
			$objUserbIRTHDTO->Hours = $_REQUEST['birthhour'];
			$objUserbIRTHDTO->Minutes = $_REQUEST['birthminute'];

			if($_REQUEST['birthhour'] == "-1") {
				$objUserbIRTHDTO->unTimed = 1;
			}
			else {
				$objUserbIRTHDTO->unTimed = 0;
			}

			$objUserbIRTHDTO->sunsign = CalculateSunsignFromDate($_REQUEST['ddMonth'],$_REQUEST['ddDay']);
			$objUserbIRTHDTO->country = $_REQUEST['ddBirthCountry'];
			$objUserbIRTHDTO->state = $_REQUEST['hdnState'];
			$objUserbIRTHDTO->country_name = $_REQUEST['hdncurrent_country_name'];
			$objUserbIRTHDTO->city = $_REQUEST['txtBirthCity'];

			$objUserbIRTHDTO->longitude = $_REQUEST['hdnLongitude'];
			$objUserbIRTHDTO->latitude = $_REQUEST['hdnLatitude'];

			//$returnValue = $userObj->SaveUserBirthDetail($objUserbIRTHDTO);
			$returnValue = $userObj->UpdateUserBirthDetail($objUserbIRTHDTO);
	
			if($returnValue > 0) {
				$host  = $_SERVER['HTTP_HOST'];
				$uri   = DIR_FRONT;
				$extra = 'thank-you-user-registration.php';
	
				setcookie("UserId", $UserId, time()+(60*60*24*30),'/',$host);  						/* Expire in 1 month */
				setcookie("UserGroupId", $GroupId, time() + (60*60*24*30), '/', $host);  		/* Expire in 1 month */
				setcookie("UserEmail", $EMail, time() + (60*60*24*30), '/', $host);  			/* Expire in 1 month */
	
				header("Location: http://$host$uri$extra");				
			}
			else {
				$request_url = $_SERVER['HTTP_REFERER'];
				echo "<html>".
						"<body onLoad=\"document.forms['user_form'].submit();\">".
						"<form method=\"post\" name=\"user_form\" action=\"".$request_url."\">";
				echo 		"<input type=\"hidden\" name=\"error\" value=\"Error Saving Data\"/>";
				echo 	"</form>".
						"</body>".
						"</html>";
			}
		}
		catch(Exception $ex) {
			$request_url = $_SERVER['HTTP_REFERER'];
			//print_r($ex);
			echo "<html>".
					"<body onLoad=\"document.forms['user_form'].submit();\">".
					"<form method=\"post\" name=\"user_form\" action=\"".$request_url."\">";
			echo 		"<input type=\"hidden\" name=\"error\" value=\"".print_r($ex)."\"/>";
			echo	"</form>".
					"</body>".
					"</html>";			
		}
	}
}
else {

	if(isset($_REQUEST['signin_submit']) && $_REQUEST['signin_submit'] == 'Sign in') {
		$userObj = new User();
		$userObj->validateUserLogon($_POST['username'],$_POST['password']);
	}
	else if(isset($_REQUEST['btnForgetPwd']) && $_REQUEST['btnForgetPwd'] == 'Get Password') {

	}

}


function SendUserEmail($data,$rec) {


	$sender = "astrowow-team@astrowow.com";


	if ( genericMail::SendWellComeEmailOnSignup( $sender, $rec, $data ) ) {
		//print "Mail was send successfull ... \n";
	} else {
		//print "Hmm .. there could be a Problem ... \n";
	}
}


function rand_passwd_generator( $length = 8, $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789@#$%' ) {
	return substr( str_shuffle( $chars ), 0, $length );
}


function SendPasswordToUserInEmail($data,$rec) {
	$sender = "astrowow-team@astrowow.com";


	if ( genericMail::SendPasswordToUserInEmail( $sender, $rec, $data ) ) {
		//print "Mail was send successfull ... \n";
	} else {
		//print "there could be a Problem ... \n";
	}
}

function SendResetPasswordLinkToUser($data,$recipient) {
	$sender = "astrowow-team@astrowow.com";

	if ( genericMail::SendResetPasswordLinkToUser( $sender, $recipient, $data ) ) {
		//print "Mail was send successfull ... \n";
	} else {
		//print "there could be a Problem ... \n";
	}
}
?>
