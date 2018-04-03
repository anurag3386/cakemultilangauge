<?php
if (!class_exists('cDatabase')) {
    if(!include("cDatabase.php")) 
	{		
		require_once("../cDatabase.php");
	}
}


class EmailTemplateRepository
{
	public function GetEmailTemplateById($id,$language_id)
	{		
		$obj = new cDatabase();
		if(!empty($id))
		{
			/*$sql = "select email_template_id, 	template_name, 	content, 	language_id, 	status  from email_templates ";
			$where = " where email_template_id='".$id."' and language_id = ".$language_id;*/
			$sql = "SELECT e.email_template_id, ec.language_id, e.template_name, ec.content, ec.subject FROM `email_templates` e
					LEFT JOIN email_template_content ec ON ec.email_template_id = e.email_template_id  
					left join language l on l.code = '".$language_id."' ";
			$where = " where e.email_template_id='".$id."' and ec.language_id = l.language_id";
			$sql = $sql.$where;			
			$query = $obj->db->query($sql);
			return $query->row;
		}
		else
		{
			return false;
		}
	}
}
?>