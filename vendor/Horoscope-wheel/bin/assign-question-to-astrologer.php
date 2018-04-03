<?php
echo "[ASSIGN QUESTION TO ASTROLOGER]  == ";

define('ROOTPATH', '/var/www/vhosts/world-of-wisdom.com/astrowow.com/');
require_once('/var/www/vhosts/world-of-wisdom.com/astrowow.com/config.php');

echo 'QUESTION Cron runs on . [ ' . date('l jS \of F Y h:i:s A').' ] \n';

$obj = new cDatabase();
$sql = " SELECT question_id,language  FROM `questions` ";

$where = " WHERE status ='".OPENED_QUESTIONS."'";
$sql = $sql.$where;
$query = $obj->db->query($sql);
$questions= $query->rows;
//print_r($questions);

$sql = " SELECT a.`astrologer_id`, a.`username`, a.`status` , language_id
FROM `astrologer` a
left join astrologer_language al on al.astrologer_id = a.astrologer_id ";

$where = " WHERE a.status ='1'";
$sql = $sql.$where;
$query = $obj->db->query($sql);
$astrolger= $query->rows;
//print_r($astrolger);

$sql = " SELECT * FROM `report_languages` ";
$where = " ";
$sql = $sql.$where;
$query = $obj->db->query($sql);
$languages= $query->rows;
//print_r($languages);


for($i=0;$i<count($languages);$i++)
{				
	$sql = " SELECT question_id,language  FROM `questions` ";
	$where = " WHERE status ='".OPENED_QUESTIONS."' and language='".$languages[$i]['report_language_id']."'";
	$sql = $sql.$where;
	$query = $obj->db->query($sql);
	$questions= $query->rows;
		
	for($j=0;$j<count($questions);$j++)
	{				
		$sql = " SELECT track_id, 	currently_assigned_astrolger ,	language_id ,	total_user  FROM `assiged_questions_track` ";
		$where = " WHERE language_id='".$languages[$i]['report_language_id']."'";
		$sql = $sql.$where;
		$query = $obj->db->query($sql);
		$assiged_questions_track= $query->rows;				
		
		$astrologer_id = 0;
		$astrologer_email = '';
		$track_id = 0;
		
		
		for($k=0;$k<count($assiged_questions_track);$k++)
		{
			$track_id = $assiged_questions_track[$k]['track_id'];
			
			$sql = " SELECT a.`astrologer_id`, a.`username`, a.`status` , language_id
			FROM `astrologer` a
			left join astrologer_language al on al.astrologer_id = a.astrologer_id ";
			
			$where = " WHERE a.status ='1' and language_id=".$languages[$i]['report_language_id']." 
			and a.`astrologer_id` > ".$assiged_questions_track[$k]['currently_assigned_astrolger']." limit 0,1";
			$sql = $sql.$where;
			$query = $obj->db->query($sql);
			$astrolger= $query->rows;
			
			if(count($astrolger)>0)
			{								
				$astrologer_id = $astrolger[0]['astrologer_id'];
				$astrologer_email = $astrolger[0]['username'];
			}
			else
			{
				$sql = " SELECT a.`astrologer_id`, a.`username`, a.`status` , language_id
				FROM `astrologer` a
				left join astrologer_language al on al.astrologer_id = a.astrologer_id ";
				
				$where = " WHERE a.status ='1' and language_id=".$languages[$i]['report_language_id']." limit 0,1";
				$sql = $sql.$where;
				$query = $obj->db->query($sql);
				$astrolger= $query->rows;
				
				if(count($astrolger)>0)
				{
					$astrologer_id 		= $astrolger[0]['astrologer_id'];
					$astrologer_email 	= $astrolger[0]['username'];
				}
			}
		}	
		
		if($astrologer_id > 0)
		{
			$sql = " insert into question_to_astrologer set
			question_id = '".$questions[$j]['question_id']."',
			astrologer_id = '".$astrologer_id."' ";
			$query = $obj->db->query($sql);
			//$assiged_questions_track= $query->rows;
						
			$sql = " update assiged_questions_track set currently_assigned_astrolger =".$astrologer_id;
			$where = " WHERE track_id='".$track_id."'";
			$sql = $sql.$where;
			$query = $obj->db->query($sql);
			//$assiged_questions_track= $query->rows;
			
			$sql = " update `questions`  set status = 3 ";
			$where = " WHERE question_id =".$questions[$j]['question_id'];
			$sql = $sql.$where;
			$query = $obj->db->query($sql);
			
			// send mail to astrologer
			$subject = 'New Question Assign to you';
			$to = $astrologer_email;
    			$body =  "New Question Assign to you\n";            			
			mail($to, $subject, $body);
		}
		else
		{
			$sql = " update assiged_questions_track set currently_assigned_astrolger =".$astrologer_id;
			$where = " WHERE track_id='".$track_id."'";
			$sql = $sql.$where;
			$query = $obj->db->query($sql);
			//$assiged_questions_track= $query->rows;						
		}
	}
}

?>
