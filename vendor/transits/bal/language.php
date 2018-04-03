<?php 
error_reporting ( E_ALL );
require_once("include.php");

/*if(!@include("dal/languageRepository.php"))
 {
//throw new Exception("Failed to include 'userRepository.php'");
require_once(ROOTPATH."dal/languageRepository.php");
}*/

if(!@include(DTOPATH."/languageDTO.php"))
{
	//throw new Exception("Failed to include 'userRepository.php'");
	require_once(DTOPATH."/languageDTO.php");
}
//if(!class_exists("Languages")) {
	class Languages
	{

		public function GetById($language_id)
		{
			$languageRepository = new LanguageRepository();
			$languageDTOObj = new LanguageDTO();
			try
			{
				$result = $languageRepository->GetById($language_id);
				if(!empty($result) and count($result)>0)
				{
					$languageDTOObj->language_id = $result[0]['language_id'];
					$languageDTOObj->code = $result[0]['code'];
					$languageDTOObj->currency = $result[0]['currency_id'];
					$languageDTOObj->directory = $result[0]['directory'];
					$languageDTOObj->filename = $result[0]['filename'];
					$languageDTOObj->image = $result[0]['image'];
					$languageDTOObj->locale = $result[0]['locale'];
					$languageDTOObj->name = $result[0]['name'];
					$languageDTOObj->status = $result[0]['status'];
				}
			}
			catch(Exception $ex)
			{
				//$userDetail->resultState->code=1;
				//$userDetail->resultState->message=$ex;
				//die($ex->getMessage());
			}
			return json_encode($languageDTOObj);
		}

		public function GetList()
		{
			$languageRepository = new LanguageRepository();
			$languageDTO = array();
			try
			{
				$result = $languageRepository->GetList();

					
				if(empty($result))
				{
				}
				else
				{
					for($i=0;$i<count($result);$i++)
					{
						$languageDTOObj = new LanguageDTO();
						$languageDTOObj->language_id = $result[$i]['language_id'];
						$languageDTOObj->code = $result[$i]['code'];
						//$languageDTOObj->currency = $result[$i]['currency'];
						$languageDTOObj->directory = $result[$i]['directory'];
						$languageDTOObj->filename = $result[$i]['filename'];
						$languageDTOObj->image = $result[$i]['image'];
						$languageDTOObj->locale = $result[$i]['locale'];
						$languageDTOObj->name = $result[$i]['name'];
						$languageDTOObj->status = $result[$i]['status'];
							
						$languageDTO[] =$languageDTOObj;
					}

				}
				//print_r($languageDTO);
				return json_encode($languageDTO);
			}
			catch(Exception $ex)
			{
				//$userDetail->resultState->code=1;
				//$userDetail->resultState->message=$ex;
				die($ex->getMessage());
			}
		}
	}
//}

/**
 * A better alternative (RFC 2109 compatible) to the php setcookie() function
 *
 * @param string Name of the cookie
 * @param string Value of the cookie
 * @param int Lifetime of the cookie
 * @param string Path where the cookie can be used
 * @param string Domain which can read the cookie
 * @param bool Secure mode?
 * @param bool Only allow HTTP usage?
 * @return bool True or false whether the method has successfully run
 */
function createCookie($name, $value='', $maxage=0, $path='', $domain='', $secure=false, $HTTPOnly=false)
{
	$ob = ini_get('output_buffering');

	// Abort the method if headers have already been sent, except when output buffering has been enabled
	if ( headers_sent() && (bool) $ob === false || strtolower($ob) == 'off' )
		return false;

	if ( !empty($domain) )
	{
		// Fix the domain to accept domains with and without 'www.'.
		if ( strtolower( substr($domain, 0, 4) ) == 'www.' ) $domain = substr($domain, 4);
		// Add the dot prefix to ensure compatibility with subdomains
		if ( substr($domain, 0, 1) != '.' ) $domain = '.'.$domain;

		// Remove port information.
		$port = strpos($domain, ':');

		if ( $port !== false ) $domain = substr($domain, 0, $port);
	}

	// Prevent "headers already sent" error with utf8 support (BOM)
	//if ( utf8_support ) header('Content-Type: text/html; charset=utf-8');

	header('Set-Cookie: '.rawurlencode($name).'='.rawurlencode($value)
	.(empty($domain) ? '' : '; Domain='.$domain)
	.(empty($maxage) ? '' : '; Max-Age='.$maxage)
	.(empty($path) ? '' : '; Path='.$path)
	.(!$secure ? '' : '; Secure')
	.(!$HTTPOnly ? '' : '; HttpOnly'), false);
	return true;
}


if(isset($_REQUEST['task'])) {
	if( strtolower( trim($_REQUEST['task']) ) == strtolower('ChangeLanguage' ) ) {
		if (isset($_REQUEST['selectedlanguage'])) {			
			// unset cookies
			if (isset($_SERVER['HTTP_COOKIE'])) {
// 				$cookies = explode(';', $_SERVER['HTTP_COOKIE']);
// 				foreach($cookies as $cookie) {
// 					$parts = explode('=', $cookie);
// 					$name = trim($parts[0]);
// 					$ExpireTime = (60 * 60 * 24 * 31) * (-1);					
// 					setcookie($name, false, time()-$ExpireTime);
// 					setcookie($name, false, time()-$ExpireTime, '/');
//  					setcookie($name, false, time()-$ExpireTime, '/', $_SERVER['HTTP_HOST']);
//  					setcookie($name, false, time()-$ExpireTime, '/', '.'.$_SERVER['HTTP_HOST']);
// 				}
				
				$ExpireTime = (60 * 60 * 24 * 31) * (-1);
				setcookie("language", false, time()-$ExpireTime);
				setcookie("language", false, time()-$ExpireTime, '/');
				setcookie("language", false, time()-$ExpireTime, '/', $_SERVER['HTTP_HOST']);
				setcookie("language", false, time()-$ExpireTime, '/', '.'.$_SERVER['HTTP_HOST']);
			}	
			unset($_COOKIE['language']);					
				
			//header('Content-Type: application/json; charset=utf-8');
			
			try {
				
				setcookie('language', trim($_REQUEST['selectedlanguage']), time() + 60 * 60 * 24 * 30, '/');
				//createCookie('language', trim($_REQUEST['selectedlanguage']), time() + 60 * 60 * 24 * 30, '/', $_SERVER['HTTP_HOST']);
				
				if(isset($_COOKIE['language'])) {
					echo json_encode(array('lang' => $_COOKIE['language'], "error" => "0"));
					exit;					
				}
				else {
					//ob_end_clean();					
					echo json_encode(array('lang' => trim($_REQUEST['selectedlanguage']), "error" => "0"));
					exit;
				}
			}
			catch(Exception $ex) {
				//ob_end_clean();
				echo json_encode(array('lang' => $ex->getMessage(), "error" => "1"));
				exit;
			}
		}
	}
}
?>
