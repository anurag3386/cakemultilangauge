<?php
/*
	This SQL query will create the table to store your object.

	CREATE TABLE `user_year_report_transit` (
	`user_year_report_transitid` int(11) NOT NULL auto_increment,
	`userid` INT NOT NULL,
	`hittingdate` DATE NOT NULL,
	`startdate` DATE NOT NULL,
	`enddate` DATE NOT NULL,
	`year_book_id` BIGINT NOT NULL,
	`aspecttype` VARCHAR(255) NOT NULL,
	`aspect` VARCHAR(255) NOT NULL,
	`prtopr_date` DATE NOT NULL, PRIMARY KEY  (`user_year_report_transitid`)) ENGINE=MyISAM;
*/

/**
* <b>user_year_report_transit</b> class with integrated CRUD methods.
* @author Php Object Generator
* @version POG 3.2 / PHP5
* @copyright Free for personal & commercial use. (Offered under the BSD license)
* @link  http://www.phpobjectgenerator.com/?language=php5&wrapper=pog&objectName=user_year_report_transit&attributeList=array+%28%0A++0+%3D%3E+%27UserId%27%2C%0A++1+%3D%3E+%27HittingDate%27%2C%0A++2+%3D%3E+%27StartDate%27%2C%0A++3+%3D%3E+%27EndDate%27%2C%0A++4+%3D%3E+%27year_book_id%27%2C%0A++5+%3D%3E+%27AspectType%27%2C%0A++6+%3D%3E+%27Aspect%27%2C%0A++7+%3D%3E+%27PRTOPR_Date%27%2C%0A%29&typeList=array+%28%0A++0+%3D%3E+%27INT%27%2C%0A++1+%3D%3E+%27DATE%27%2C%0A++2+%3D%3E+%27DATE%27%2C%0A++3+%3D%3E+%27DATE%27%2C%0A++4+%3D%3E+%27BIGINT%27%2C%0A++5+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++6+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++7+%3D%3E+%27DATE%27%2C%0A%29
*/
include_once('class.pog_base.php');
class user_year_report_transit extends POG_Base
{
	function __cConstructor () {
		
	}

	public $user_year_report_transitId = '';

	/**
	 * @var INT
	 */
	public $UserId;
	
	/**
	 * @var DATE
	 */
	public $HittingDate;
	
	/**
	 * @var DATE
	 */
	public $StartDate;
	
	/**
	 * @var DATE
	 */
	public $EndDate;
	
	/**
	 * @var BIGINT
	 */
	public $year_book_id;
	
	/**
	 * @var VARCHAR(255)
	 */
	public $AspectType;
	
	/**
	 * @var VARCHAR(255)
	 */
	public $Aspect;
	
	/**
	 * @var DATE
	 */
	public $PRTOPR_Date;
	
	public $pog_attribute_type = array(
		"user_year_report_transitId" => array('db_attributes' => array("NUMERIC", "INT")),
		"UserId" => array('db_attributes' => array("NUMERIC", "INT")),
		"HittingDate" => array('db_attributes' => array("NUMERIC", "DATE")),
		"StartDate" => array('db_attributes' => array("NUMERIC", "DATE")),
		"EndDate" => array('db_attributes' => array("NUMERIC", "DATE")),
		"year_book_id" => array('db_attributes' => array("NUMERIC", "BIGINT")),
		"AspectType" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
		"Aspect" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
		"PRTOPR_Date" => array('db_attributes' => array("NUMERIC", "DATE")),
		);
	public $pog_query;
	
	
	/**
	* Getter for some private attributes
	* @return mixed $attribute
	*/
	public function __get($attribute)
	{
		if (isset($this->{"_".$attribute}))
		{
			return $this->{"_".$attribute};
		}
		else
		{
			return false;
		}
	}
	
	function user_year_report_transit($UserId='', $HittingDate='', $StartDate='', $EndDate='', $year_book_id='', $AspectType='', $Aspect='', $PRTOPR_Date='')
	{
		$this->UserId = $UserId;
		$this->HittingDate = $HittingDate;
		$this->StartDate = $StartDate;
		$this->EndDate = $EndDate;
		$this->year_book_id = $year_book_id;
		$this->AspectType = $AspectType;
		$this->Aspect = $Aspect;
		$this->PRTOPR_Date = $PRTOPR_Date;
	}
	
	
	/**
	* Gets object from database
	* @param integer $user_year_report_transitId 
	* @return object $user_year_report_transit
	*/
	function Get($user_year_report_transitId)
	{
		$connection = Database::Connect();
		$this->pog_query = "select * from `user_year_report_transit` where `user_year_report_transitid`='".intval($user_year_report_transitId)."' LIMIT 1";
		$cursor = Database::Reader($this->pog_query, $connection);
		while ($row = Database::Read($cursor))
		{
			$this->user_year_report_transitId = $row['user_year_report_transitid'];
			$this->UserId = $this->Unescape($row['userid']);
			$this->HittingDate = $row['hittingdate'];
			$this->StartDate = $row['startdate'];
			$this->EndDate = $row['enddate'];
			$this->year_book_id = $this->Unescape($row['year_book_id']);
			$this->AspectType = $this->Unescape($row['aspecttype']);
			$this->Aspect = $this->Unescape($row['aspect']);
			$this->PRTOPR_Date = $row['prtopr_date'];
		}
		return $this;
	}
	
	
	/**
	* Returns a sorted array of objects that match given conditions
	* @param multidimensional array {("field", "comparator", "value"), ("field", "comparator", "value"), ...} 
	* @param string $sortBy 
	* @param boolean $ascending 
	* @param int limit 
	* @return array $user_year_report_transitList
	*/
	function GetList($fcv_array = array(), $sortBy='', $ascending=true, $limit='') {
		$connection = Database::Connect();
		$sqlLimit = ($limit != '' ? "LIMIT $limit" : '');
		$this->pog_query = "select * from `user_year_report_transit` ";
		$user_year_report_transitList = Array();

		if (sizeof($fcv_array) > 0) {
			$this->pog_query .= " where ";
			for ($i=0, $c=sizeof($fcv_array); $i<$c; $i++) {
				if (sizeof($fcv_array[$i]) == 1) {
					$this->pog_query .= " ".$fcv_array[$i][0]." ";
					continue;
				} else {
					if ($i > 0 && sizeof($fcv_array[$i-1]) != 1) {
						$this->pog_query .= " AND ";
					}
					if (isset($this->pog_attribute_type[$fcv_array[$i][0]]['db_attributes']) && $this->pog_attribute_type[$fcv_array[$i][0]]['db_attributes'][0] != 'NUMERIC' && $this->pog_attribute_type[$fcv_array[$i][0]]['db_attributes'][0] != 'SET') {
						
						if ($GLOBALS['configuration']['db_encoding'] == 1) {
							$value = POG_Base::IsColumn($fcv_array[$i][2]) ? "BASE64_DECODE(".$fcv_array[$i][2].")" : "'".$fcv_array[$i][2]."'";
							$this->pog_query .= "BASE64_DECODE(`".$fcv_array[$i][0]."`) ".$fcv_array[$i][1]." ".$value;
						} else {
							$value =  POG_Base::IsColumn($fcv_array[$i][2]) ? $fcv_array[$i][2] : "'".$this->Escape($fcv_array[$i][2])."'";
							$this->pog_query .= "`".$fcv_array[$i][0]."` ".$fcv_array[$i][1]." ".$value;
						}
					} else {
						$value = POG_Base::IsColumn($fcv_array[$i][2]) ? $fcv_array[$i][2] : "'".$fcv_array[$i][2]."'";
						$this->pog_query .= "`".$fcv_array[$i][0]."` ".$fcv_array[$i][1]." ".$value;
					}
				}
			}
		}
		if ($sortBy != '') {
			if (isset($this->pog_attribute_type[$sortBy]['db_attributes']) && $this->pog_attribute_type[$sortBy]['db_attributes'][0] != 'NUMERIC' && $this->pog_attribute_type[$sortBy]['db_attributes'][0] != 'SET') {
				if ($GLOBALS['configuration']['db_encoding'] == 1) {
					$sortBy = "BASE64_DECODE($sortBy) ";
				} else {
					$sortBy = "$sortBy ";
				}
			} else {
				$sortBy = "$sortBy ";
			}
		} else {
			$sortBy = "user_year_report_transit.id";
		}
		$this->pog_query .= " order by ".$sortBy." ".($ascending ? "asc" : "desc")." $sqlLimit";
		$thisObjectName = get_class($this);
		$cursor = Database::Reader($this->pog_query, $connection);
		while ($row = Database::Read($cursor)) {
			$user_year_report_transit = new $thisObjectName();
			$user_year_report_transit->user_year_report_transitId = $row['id']; //$row['user_year_report_transitId'];
			$user_year_report_transit->UserId = $this->Unescape($row['user_id']);
			$user_year_report_transit->HittingDate = $row['HittingDate'];
			$user_year_report_transit->StartDate = $row['StartDate'];
			$user_year_report_transit->EndDate = $row['EndDate'];
			$user_year_report_transit->year_book_id = $this->Unescape($row['year_book_id']);
			$user_year_report_transit->AspectType = $this->Unescape($row['AspectType']);
			$user_year_report_transit->Aspect = $this->Unescape($row['Aspect']);
			$user_year_report_transit->PRTOPR_Date = $row['PRTOPR_Date'];
			$user_year_report_transitList[] = $user_year_report_transit;
		}
		return $user_year_report_transitList;
	}
	
	
	/**
	* Saves the object to the database
	* @return integer $user_year_report_transitId
	*/
	function Save()
	{
		$connection = Database::Connect();
		$rows = 0;
		if ($this->user_year_report_transitId!=''){
			$this->pog_query = "select `user_year_report_transitid` from `user_year_report_transit` where `user_year_report_transitid`='".$this->user_year_report_transitId."' LIMIT 1";
			$rows = Database::Query($this->pog_query, $connection);
		}
		if ($rows > 0)
		{
			$this->pog_query = "update `user_year_report_transit` set 
			`userid`='".$this->Escape($this->UserId)."', 
			`user_type`='".$this->Escape($this->UserType)."', 
			`hittingdate`='".$this->HittingDate."', 
			`startdate`='".$this->StartDate."', 
			`enddate`='".$this->EndDate."', 
			`year_book_id`='".$this->Escape($this->year_book_id)."', 
			`aspecttype`='".$this->Escape($this->AspectType)."', 
			`aspect`='".$this->Escape($this->Aspect)."', 
			`prtopr_date`='".$this->PRTOPR_Date."' where `user_year_report_transitid`='".$this->user_year_report_transitId."'";
		}
		else
		{
			$this->pog_query = "insert into `user_year_report_transit` (`user_id`, `user_type`, `hittingdate`, `startdate`, `enddate`, `year_book_id`, `aspecttype`, `aspect`, `prtopr_date` ) values (
			'".$this->Escape($this->UserId)."', 
			'".$this->Escape($this->UserType)."', 
			'".$this->HittingDate."', 
			'".$this->StartDate."', 
			'".$this->EndDate."', 
			'".$this->Escape($this->year_book_id)."', 
			'".$this->Escape($this->AspectType)."', 
			'".$this->Escape($this->Aspect)."', 
			'".$this->PRTOPR_Date."' )";
		}
		$insertId = Database::InsertOrUpdate($this->pog_query, $connection);
		if ($this->user_year_report_transitId == "")
		{
			$this->user_year_report_transitId = $insertId;
		}
		return $this->user_year_report_transitId;
	}
	
	
	/**
	* Clones the object and saves it to the database
	* @return integer $user_year_report_transitId
	*/
	function SaveNew()
	{
		$this->user_year_report_transitId = '';
		return $this->Save();
	}
	
	
	/**
	* Deletes the object from the database
	* @return boolean
	*/
	function Delete()
	{
		$connection = Database::Connect();
		$this->pog_query = "delete from `user_year_report_transit` where `user_year_report_transitid`='".$this->user_year_report_transitId."'";
		return Database::NonQuery($this->pog_query, $connection);
	}
	
	
	/**
	* Deletes a list of objects that match given conditions
	* @param multidimensional array {("field", "comparator", "value"), ("field", "comparator", "value"), ...} 
	* @param bool $deep 
	* @return 
	*/
	function DeleteList($fcv_array)
	{
		if (sizeof($fcv_array) > 0)
		{
			$connection = Database::Connect();
			$pog_query = "delete from `user_year_report_transit` where ";
			for ($i=0, $c=sizeof($fcv_array); $i<$c; $i++)
			{
				if (sizeof($fcv_array[$i]) == 1)
				{
					$pog_query .= " ".$fcv_array[$i][0]." ";
					continue;
				}
				else
				{
					if ($i > 0 && sizeof($fcv_array[$i-1]) !== 1)
					{
						$pog_query .= " AND ";
					}
					if (isset($this->pog_attribute_type[$fcv_array[$i][0]]['db_attributes']) && $this->pog_attribute_type[$fcv_array[$i][0]]['db_attributes'][0] != 'NUMERIC' && $this->pog_attribute_type[$fcv_array[$i][0]]['db_attributes'][0] != 'SET')
					{
						$pog_query .= "`".$fcv_array[$i][0]."` ".$fcv_array[$i][1]." '".$this->Escape($fcv_array[$i][2])."'";
					}
					else
					{
						$pog_query .= "`".$fcv_array[$i][0]."` ".$fcv_array[$i][1]." '".$fcv_array[$i][2]."'";
					}
				}
			}
			return Database::NonQuery($pog_query, $connection);
		}
	}
}
?>