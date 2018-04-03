<?php
/**
 * @copyright : Astrowow and N-Tech Technologies Pvt.
 * 
 * @filesource : lovematachRepository.php
 * 
 * Holds the Lovematch opertions
 * 
 */

date_default_timezone_set ( 'America/Los_Angeles' );
if (!class_exists('cDatabase')) {
	if(!include("cDatabase.php")) {
		require_once("cDatabase.php");
	}
}

if(!class_exists("LoveMatchRepository")){
	class LoveMatchRepository {
		
		function __construct() {		
		}
		
		public static function GetMatchingData($loveMatchDTO) {
			$obj = new cDatabase();
			$SQL = "SELECT `matchid`, `sign_1`, `sign_2`, `match_text`, `language_code` FROM `love_match`";
			$WHERE = sprintf(" WHERE sign_1 = '%s' AND sign_2 = '%s' AND language_code = '%s'", strtolower($loveMatchDTO->Sign_1), strtolower($loveMatchDTO->Sign_2), strtolower($loveMatchDTO->LanguageCode));
			
			$SQL = $SQL.$WHERE;			
			$query = $obj->db->query($SQL);			
			return $query->rows;
		}
	}
}

if(!class_exists("LoveMatchDTO")) {
	class LoveMatchDTO {
		
		var $MatchId;
		var $Sign_1;
		var $Sign_2;
		var $LanguageCode;
		var $MatchingDescription;

// 		function __construct($MatchId = 0, $Sign_1 = '', $Sign_2 = '', $LanguageCode = '', $MatchingDescription = '') {
			
// 			$this->MatchId = ( isset($MatchId) ? $MatchId : $MatchId );
// 			$this->Sign_1 = ( isset($Sign_1) ? $Sign_1 : 'ar');
// 			$this->Sign_2 = ( isset($Sign_2) ? $Sign_2 : 'ar' );
// 			$this->LanguageCode = ( isset($LanguageCode) ? $LanguageCode : 'en' );
// 			$this->MatchingDescription= ( isset($MatchingDescription) ? $MatchingDescription : '' );			
// 		}
		
		function __construct() {				
		}
	}
}
?>