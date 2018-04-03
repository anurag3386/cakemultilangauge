<?php
if (!class_exists('cDatabase')) {
	if(!include("cDatabase.php")) {
		require_once("../cDatabase.php");
	}
}

if(!class_exists("MicroBlogRepository")) {
	class MicroBlogRepository {

		var $MyPDO;

		public function __construct() {
			$this->MyPDO = $GLOBALS["db"];
		}

		function GetTopBlogs($Locale = 'en') {
			$SQLQuery = "SELECT  
							m.`blogid`, m.`languageid`, m.`title`, m.`description`, m.`imageid`, m.`created_by`, m.`createddate`, 
							m.`status`, up.firstname, up.lastname
						FROM `micro_blog` m, `language` l, userprofile up, user u 
						WHERE  l.language_id  = m.languageid AND up.userid = u.userid AND up.userid = m.created_by AND u.userid = m.created_by AND u.status = 1
							AND m.status = 1 AND l.code = :Locale 
						ORDER BY blogid DESC
						LIMIT 0, 2";
			
			$objMyPDO = $this->MyPDO->prepare($SQLQuery);
				
			$objMyPDO->bindParam(':Locale', $Locale);
				
			$objMyPDO->execute();
			return $objMyPDO->fetchAll ( PDO::FETCH_ASSOC );
						
// 			$obj = new cDatabase();
// 			$SQL = "SELECT  m.`blogid`, m.`languageid`, m.`title`, m.`description`, m.`imageid`, m.`created_by`, m.`createddate`, m.`status`,
// 					up.firstname, up.lastname
// 					FROM `micro_blog` m, `language` l, userprofile up, user u ";

// 			$Where = " WHERE  l.language_id  = m.languageid AND up.userid = u.userid AND up.userid = m.created_by AND u.userid = m.created_by AND u.status = 1 ";
// 			$Where .= " AND m.status = 1 AND l.code = '$Locale' ";
				
// 			$OrderBy = " ORDER BY blogid DESC";
// 			$Limit = " LIMIT 0, 2";
				
// 			$SQL =  $SQL.$Where.$OrderBy.$Limit;
// 			$query = $obj->db->query($SQL);
// 			return $query->rows;
		}


		function GetBlogList($Locale = 'en', $PageIndex = 1, $ItemsPerPage = 20) {
			$Temp = intval($PageIndex) - 1;
			$ItemsPerPage = intval($ItemsPerPage);
			
			$SQLQuery = "SELECT
							m.`blogid`, m.`languageid`, m.`title`, m.`description`, m.`imageid`, m.`created_by`, m.`createddate`,
							m.`status`, up.firstname, up.lastname
						FROM `micro_blog` m, `language` l, userprofile up, user u
						WHERE  l.language_id  = m.languageid AND up.userid = u.userid AND up.userid = m.created_by AND u.userid = m.created_by AND u.status = 1
							AND m.status = 1 AND l.code = :Locale
						ORDER BY blogid DESC
						LIMIT :TempPage, :ItemsPerPage";
				
			$objMyPDO = $this->MyPDO->prepare($SQLQuery);
			
			$objMyPDO->bindParam(':Locale', $Locale);
			$objMyPDO->bindValue(':TempPage', $Temp, PDO::PARAM_INT);
			$objMyPDO->bindValue(':ItemsPerPage', $ItemsPerPage, PDO::PARAM_INT);
			
			$objMyPDO->execute();
			return $objMyPDO->fetchAll ( PDO::FETCH_ASSOC );
						
// 			$obj = new cDatabase();
// 			$SQL = "SELECT  m.`blogid`, m.`languageid`, m.`title`, m.`description`, m.`imageid`, m.`created_by`, m.`createddate`, m.`status`,
// 					up.firstname, up.lastname
// 					FROM `micro_blog` m, `language` l, userprofile up, user u ";
// 			$Where = " WHERE  l.language_id  = m.languageid AND up.userid = u.userid AND up.userid = m.created_by AND u.userid = m.created_by AND u.status = 1 ";
// 			$Where .= " AND m.status = 1 AND l.code = '$Locale' ";
// 			$OrderBy = " ORDER BY blogid DESC ";
// 			$Temp = intval($PageIndex) - 1;
// 			$Limit = " LIMIT $Temp, $ItemsPerPage";

// 			$SQL =  $SQL.$Where.$OrderBy.$Limit;
// 			$query = $obj->db->query($SQL);
// 			return $query->rows;
		}

		function GetBlogListCount($Locale = 'en') {
			$SQLQuery = "SELECT count(m.`blogid`) AS rCount FROM `micro_blog` m, `language` l, userprofile up, user u
						WHERE  l.language_id  = m.languageid AND up.userid = u.userid AND up.userid = m.created_by AND u.userid = m.created_by AND u.status = 1 
								AND m.status = 1 AND l.code = :Locale";
			
			$objMyPDO = $this->MyPDO->prepare($SQLQuery);
				
			$objMyPDO->bindParam(':Locale', $Locale);
				
			$objMyPDO->execute();
			return $objMyPDO->fetchColumn ();
			
// 			$obj = new cDatabase();
// 			$SQL = "SELECT count(m.`blogid`) AS rCount
// 					FROM `micro_blog` m, `language` l, userprofile up, user u ";
// 			$Where = " WHERE  l.language_id  = m.languageid AND up.userid = u.userid AND up.userid = m.created_by AND u.userid = m.created_by AND u.status = 1 ";
// 			$Where .= " AND m.status = 1 AND l.code = '$Locale' ";
// 			$SQL =  $SQL.$Where;
// 			$query = $obj->db->query($SQL);
// 			return $query->row['rCount'];
		}

		public function GetImageById($FileID) {
			$SQLQuery = "SELECT originalName,name,path,size,mimeType,status FROM files WHERE file_id = :file_id";				
			$objMyPDO = $this->MyPDO->prepare($SQLQuery);			
			$objMyPDO->bindParam(':file_id', $FileID);			
			$objMyPDO->execute();
			return $objMyPDO->fetchAll ( PDO::FETCH_ASSOC );
		}


		function GetBlogByID($BlogId = 0) {
			$SQLQuery = "SELECT  
								m.`blogid`, m.`languageid`, m.`title`, m.`description`, m.`imageid`, m.`created_by`, 
								m.`createddate`, m.`status`, up.firstname, up.lastname
					FROM `micro_blog` m, `language` l, userprofile up, user u 
					WHERE  l.language_id  = m.languageid AND up.userid = u.userid AND up.userid = m.created_by AND u.userid = m.created_by AND u.status = 1 
							AND m.status = 1 AND m.`blogid` =  :BlogId";
			$objMyPDO = $this->MyPDO->prepare($SQLQuery);
			$objMyPDO->bindParam(':BlogId', $BlogId);
			$objMyPDO->execute();
			return $objMyPDO->fetchAll ( PDO::FETCH_ASSOC );
		}
	}
}
?>