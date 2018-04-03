<?php

if (!class_exists('cDatabase')) {
    if(!include("cDatabase.php")) 
	{		
		require_once("../cDatabase.php");
	}
}

class LanguageRepository 
{
	public function GetById($language_id)
	{
		try
		{
			$obj = new cDatabase();
			 
			$sql = " select language_id,status,name,code,locale,directory,filename,image ";
			$sql .= " ,(select currency_id from currency_to_language where language_id = ".$language_id.") as 'currency_id'";
			$sql .= " from language ";
			$where = " where 1=1 and status = 1 and language_id = ".$language_id;
			$sql = $sql.$where;
			$query = $obj->db->query($sql);
			return $query->rows;
		}
		catch(Exception $ex)
		{
			//$userDetail->resultState->code=1;
			//$userDetail->resultState->message=$ex;
			die($ex->getMessage());
		}			
	}
	
	public function GetList()
	{
		try
		{
			$obj = new cDatabase();
			 
			$sql = " select language_id,status,name,code,locale,directory,filename,image from language ";
			$where = " where 1=1 and status = 1";
			$sql = $sql.$where;
			$query = $obj->db->query($sql);
			return $query->rows;
		}
		catch(Exception $ex)
		{
			//$userDetail->resultState->code=1;
			//$userDetail->resultState->message=$ex;
			die($ex->getMessage());
		}			
	}
	

}
?>