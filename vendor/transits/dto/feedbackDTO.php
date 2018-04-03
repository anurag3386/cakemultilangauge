<?php
require_once("resultStateDTO.php");

class feedbackDTO
{
	function __construct() {
      $this->language="en";
	  $this->user_id=0;
	  $this->status='';	
	  $this->resultState = new ResultStateDTO();
	  $this->user_group=0;
	  $this->portal_id=0;
	  $this->user_name='';
	  $this->password='';
	  $this->preview_report='';
	  $this->parent_user_id='';
	   	
   }
   
   public $language;
   public $user_id;
   public $status; 
   public $resultState; 
   public $user_group; 
   public $portal_id; 
   public $user_name; 
   public $password; 
   public $preview_report; 
   public $parent_user_id; 
}

?>