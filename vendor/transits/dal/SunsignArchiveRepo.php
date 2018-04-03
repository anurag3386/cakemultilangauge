<?php
require_once("cDatabase.php");
require_once(ROOTPATH."dal/horoscopeRepository.php");
require_once(ROOTPATH."helper/date-function.php");

class SunsignArchiveRepo {
	
	public function __construct() {
		// ...
		$this->language = "en";
        $this->sign = "";
        $this->content = array();
        $this->schedule_date='';
	}

	public function getContent($params)
	{

		if(!empty($params))
		{
			//$sql = "SELECT * FROM sunsignprediction WHERE SunSignId=".mysql_real_escape_string($params['sunsignid'])." AND ScheduleDate='".mysql_real_escape_string($params['date'])."' AND Scope='".mysql_real_escape_string($params['scope'])."' ";
			//$sql = "SELECT * FROM sunsignprediction WHERE SunSignId=1 AND ScheduleDate='2012-10-03' ";
			
			//$obj = new cDatabase();
			//$result = $obj->db->query($sql);
			
			$this->scope = mysql_real_escape_string($params['scope']);
			$this->sign = mysql_real_escape_string($params['sunsignid']);
			$dt = explode("-",mysql_real_escape_string($params['date']));
			$this->schedule_date = sprintf ("%04d-%02d-%02d", $dt[0],$dt[1], $dt[2] );        
			
			if(mysql_real_escape_string($params['scope'])==2)
			{
				
				define('ONE_DAY', 60*60*24);
				$WeekDayNumber = date('w', strtotime($params['date']));
				$dt = explode("-",date("Y-m-d",strtotime(mysql_real_escape_string($params['date'])) - ($WeekDayNumber )*ONE_DAY));
				$datefuncion = new DateFunction();
        		$this->schedule_date = $datefuncion->GetLastWeekSunday(sprintf ("%04d-%02d-%02d", $dt[0],$dt[1], $dt[2] ));       
			}
			
			
			$sunsignRepository = new Horoscope();
			$result = $sunsignRepository->getContent($this);

			
			//$db = mysqli_connect('localhost', 'astrowow_demo', 'crackjack25', 'astrowow_demo');
			//$result = mysqli_query($db, $sql);
			
			if(!$result) return "We are working hard to deliver the content. Coming soon!";
		
			//while($row = mysqli_fetch_assoc($result))
			foreach($result as $row)
			{
				return strip_tags($row['Prediction']);
			}
		}
	}
}

?>
