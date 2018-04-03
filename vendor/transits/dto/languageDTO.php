<?php
//require_once("resultStateDTO.php");
class LanguageDTO
{
	function __construct() {
      $this->language_id="";
	  $this->status=0;
	  $this->name='';	
	  $this->code='';	
	  $this->locale='';	
	  $this->directory='';	
	  $this->filename='';	
	  $this->currency='';	
	  $this->image='';	
	  //$this->resultState = new ResultStateDTO();
   }
   
   public $language_id;
   public $status;
   public $name; 
   public $code; 
   public $locale;
   public $directory;
   public $filename;
   public $currency;
   public $image;
   //public $resultState;
}
?>