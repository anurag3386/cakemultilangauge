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

if(!class_exists("GroupOnRepository")){
	class GroupOnRepository {
		
		function __construct() {
				
		}
		
		public static function SaveGroupCodeWithOrder($OrderId, $GroupCode) {
			$obj = new cDatabase();
			$SQL = "UPDATE `group_code` SET orderid = $OrderId, status = 1";
			$WHERE = sprintf(" WHERE code = '%s'", trim($GroupCode));
						
			$SQL = $SQL.$WHERE;			
			$query = $obj->db->query($SQL);			
			return $query->rows;
		}
	}
}
?>