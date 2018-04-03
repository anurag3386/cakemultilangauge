<?php
require_once("cDatabase.php");
class Horoscope
{
	public function getContent($data)
	{
		$obj = new cDatabase();
		if(!empty($data))
		{
			$sql = " Select SunSignId,Scope,Prediction,ScheduleDate,Language from sunsignprediction ";
			$where = " where 1=1";
						
			if (isset($data->scope) && !empty($data->scope)) 
			{
				$where .= " and Scope=".$data->scope;
			}
			
			if (isset($data->sign) && !empty($data->sign)) 
			{
				$where .= " and SunSignId=".$data->sign;
			}
			
			if (isset($data->schedule_date) && !empty($data->schedule_date)) 
			{
				$where .= " and ScheduleDate='".$data->schedule_date."'";
			}
			
			if (isset($data->language) && !empty($data->language)) 
			{
				$where .= " and Language='".$data->language."'";
			}
			
			$sql = $sql.$where;
			//echo $sql; 
			$query = $obj->db->query($sql);
			//print_r($query);
			return $query->rows;
		}
	}
	
	public function getGeneralContent($data)
	{
		$obj = new cDatabase();
		if(!empty($data))
		{
			$sql = " Select GeneralPredictionId,Prediction,ScheduleDate,Language from general_prediction ";
			$where = " where 1=1";
												
			if (isset($data->schedule_date) && !empty($data->schedule_date)) 
			{
				$where .= " and ScheduleDate='".$data->schedule_date."'";
			}
			
			if (isset($data->language) && !empty($data->language)) 
			{
				$where .= " and Language='".$data->language."'";
			}
			
			$sql = $sql.$where;
			$query = $obj->db->query($sql);
			//print_r($query);
			return $query->rows;
		}
	}
}
?>