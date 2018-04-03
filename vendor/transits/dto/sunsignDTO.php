<?php
require_once("resultStateDTO.php");
class sunsignDTO
{
	function __construct() {
      $this->language="en";
	  $this->sign=0;
	  $this->content='';	
	  $this->Characteristics = '';
	  $this->Celebrity = '';
	  $this->resultState = new ResultStateDTO();
	  $this->scheduleDate = date("Y-m-d");
   }
   
   public $language;
   public $sign;
   public $content; 
   public $resultState;
   public $Characteristics;
   public $Celebrity;
   public $scheduleDate;
}
?>