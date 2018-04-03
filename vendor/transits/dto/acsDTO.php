<?php


class AcsDTO
{
	function __construct() {
      $this->placename='';
	  $this->region='';
	  $this->latitude='';		 
	  $this->longitude='';
	  $this->zone='';
	  $this->type='';	  
	  $this->calLatitude='';		 
	  $this->calLongitude='';
   }
   
	public $placename;
	public $region;
	public $latitude;
	public $longitude;
	public $calLatitude;
	public $calLongitude;
	public $zone;
	public $type;
}

?>