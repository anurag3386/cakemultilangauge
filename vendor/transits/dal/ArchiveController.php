<?php
header('Content-Type: text/html; charset=utf-8');
error_reporting ( E_ALL );

include_once("../../bal/include.php");
require_once(ROOTPATH."dal/horoscopeRepository.php");
require_once(ROOTPATH."dto/sunsignDTO.php");
require_once(ROOTPATH."helper/date-function.php");
require_once(ROOTPATH."dal/SunsignArchiveRepo.php");


class ArchiveController {

    public $language;
    public $SunSignId;
    public $ArchiveDate;
    public $Scope;
    public $Prediction;

    /*
	* scope 1 = Daily
	* scope 2 = weekly
	* scope 3 = monthly
	* scope 4 = yearly
    */

    public function __construct($language = 'en') {

        $this->language = strtolower ( $language );
    }

    public function setArchiveParams($SunSignId = 0, $Scope = 4, $date = "") {

    	$this->SunSignId = $SunSignId;
    	$this->Scope = $Scope;
    	$this->ArchiveDate = $date;
    }

   	public function getArchiveContent() {

   		// $sql = "SELECT Prediction FROM sunsignprediction WHERE ";
   		// $sql .= "SunSignId=".$this->SunSignId." AND";
   		// $sql .= "Scope=".$this->Scope." AND";
   		// $sql .= "ScheduleDate=".$this->ArchiveDate." ";

   		$params = array(
	   			'sunsignid' => $this->SunSignId,
	   			'scope' => $this->Scope,
	   			'date' => $this->ArchiveDate
   			);
      // echo "params: ";
      // var_dump($params);
      // die();

   		$archive = new SunsignArchiveRepo();
		
   		echo $this->Prediction = $archive->getContent($params);exit;

      if($this->Prediction=="") {
        return "We are working hard to deliver the content. Coming soon!";
      }
      else return $this->Prediction;
   		// return results to View
   	}

}

?>