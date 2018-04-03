<?php
error_reporting ( E_ALL );
//require_once("include.php");
//require_once(ROOTPATH."dal/emailTemplateRepository.php");

class emailTemplate
{
	public function GetEmailTemplateById($id,$language_id)
	{		
		$objEmailTemplateRepository = new EmailTemplateRepository();
		$result = $objEmailTemplateRepository->GetEmailTemplateById($id,$language_id);
		return $result;
	}
}
?>