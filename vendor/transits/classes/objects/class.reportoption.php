<?php
/*
	This SQL query will create the table to store your object.

	CREATE TABLE `reportoption` (
	`reportoptionid` int(11) NOT NULL auto_increment,
	`orderid` int(11) NOT NULL,
	`name` VARCHAR(255) NOT NULL,
	`start_date` DATE NOT NULL,
	`duration` TINYINT NOT NULL,
	`language` char(2) NOT NULL,
	`paper_size` char(2) NOT NULL,
	`emailaddressid` int(11) NOT NULL, INDEX(`orderid`,`emailaddressid`), PRIMARY KEY  (`reportoptionid`)) ENGINE=MyISAM;
*/

/**
* <b>reportoption</b> class with integrated CRUD methods.
* @author Php Object Generator
* @version POG 3.0d / PHP5
* @copyright Free for personal & commercial use. (Offered under the BSD license)
* @link http://www.phpobjectgenerator.com/?language=php5&wrapper=pog&objectName=reportoption&attributeList=array+%28%0A++0+%3D%3E+%27order%27%2C%0A++1+%3D%3E+%27name%27%2C%0A++2+%3D%3E+%27start_date%27%2C%0A++3+%3D%3E+%27duration%27%2C%0A++4+%3D%3E+%27language%27%2C%0A++5+%3D%3E+%27paper_size%27%2C%0A++6+%3D%3E+%27emailaddress%27%2C%0A%29&typeList=array+%28%0A++0+%3D%3E+%27BELONGSTO%27%2C%0A++1+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++2+%3D%3E+%27DATE%27%2C%0A++3+%3D%3E+%27TINYINT%27%2C%0A++4+%3D%3E+%27char%282%29%27%2C%0A++5+%3D%3E+%27char%282%29%27%2C%0A++6+%3D%3E+%27BELONGSTO%27%2C%0A%29
*/
include_once('class.pog_base.php');
class reportoption extends POG_Base
{
	public $reportoptionId = '';

	/**
	 * @var INT(11)
	 */
	public $orderId;
	
	/**
	 * @var VARCHAR(255)
	 */
	public $name;
	
	/**
	 * @var DATE
	 */
	public $start_date;
	
	/**
	 * @var TINYINT
	 */
	public $duration;
	
	/**
	 * @var char(2)
	 */
	public $language;
	
	/**
	 * @var char(2)
	 */
	public $paper_size;
	
	/**
	 * @var INT(11)
	 */
	public $emailaddressId;
	
	public $pog_attribute_type = array(
		"reportoptionId" => array('db_attributes' => array("NUMERIC", "INT")),
		"order" => array('db_attributes' => array("OBJECT", "BELONGSTO")),
		"name" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
		"start_date" => array('db_attributes' => array("NUMERIC", "DATE")),
		"duration" => array('db_attributes' => array("NUMERIC", "TINYINT")),
		"language" => array('db_attributes' => array("TEXT", "CHAR", "2")),
		"paper_size" => array('db_attributes' => array("TEXT", "CHAR", "2")),
		"emailaddress" => array('db_attributes' => array("OBJECT", "BELONGSTO")),
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
	
	function reportoption($name='', $start_date='', $duration='', $language='', $paper_size='')
	{
		$this->name = $name;
		$this->start_date = $start_date;
		$this->duration = $duration;
		$this->language = $language;
		$this->paper_size = $paper_size;
	}
	
	
	/**
	* Gets object from database
	* @param integer $reportoptionId 
	* @return object $reportoption
	*/
	function Get($reportoptionId)
	{
		$connection = Database::Connect();
		$this->pog_query = "select * from `reportoption` where `reportoptionid`='".intval($reportoptionId)."' LIMIT 1";
		$cursor = Database::Reader($this->pog_query, $connection);
		while ($row = Database::Read($cursor))
		{
			$this->reportoptionId = $row['reportoptionid'];
			$this->orderId = $row['orderid'];
			$this->name = $this->Unescape($row['name']);
			$this->start_date = $row['start_date'];
			$this->duration = $this->Unescape($row['duration']);
			$this->language = $this->Unescape($row['language']);
			$this->paper_size = $this->Unescape($row['paper_size']);
			$this->emailaddressId = $row['emailaddressid'];
		}
		return $this;
	}
	
	
	/**
	* Returns a sorted array of objects that match given conditions
	* @param multidimensional array {("field", "comparator", "value"), ("field", "comparator", "value"), ...} 
	* @param string $sortBy 
	* @param boolean $ascending 
	* @param int limit 
	* @return array $reportoptionList
	*/
	function GetList($fcv_array = array(), $sortBy='', $ascending=true, $limit='')
	{
		$connection = Database::Connect();
		$sqlLimit = ($limit != '' ? "LIMIT $limit" : '');
		$this->pog_query = "select * from `reportoption` ";
		$reportoptionList = Array();
		if (sizeof($fcv_array) > 0)
		{
			$this->pog_query .= " where ";
			for ($i=0, $c=sizeof($fcv_array); $i<$c; $i++)
			{
				if (sizeof($fcv_array[$i]) == 1)
				{
					$this->pog_query .= " ".$fcv_array[$i][0]." ";
					continue;
				}
				else
				{
					if ($i > 0 && sizeof($fcv_array[$i-1]) != 1)
					{
						$this->pog_query .= " AND ";
					}
					if (isset($this->pog_attribute_type[$fcv_array[$i][0]]['db_attributes']) && $this->pog_attribute_type[$fcv_array[$i][0]]['db_attributes'][0] != 'NUMERIC' && $this->pog_attribute_type[$fcv_array[$i][0]]['db_attributes'][0] != 'SET')
					{
						if ($GLOBALS['configuration']['db_encoding'] == 1)
						{
							$value = POG_Base::IsColumn($fcv_array[$i][2]) ? "BASE64_DECODE(".$fcv_array[$i][2].")" : "'".$fcv_array[$i][2]."'";
							$this->pog_query .= "BASE64_DECODE(`".$fcv_array[$i][0]."`) ".$fcv_array[$i][1]." ".$value;
						}
						else
						{
							$value =  POG_Base::IsColumn($fcv_array[$i][2]) ? $fcv_array[$i][2] : "'".$this->Escape($fcv_array[$i][2])."'";
							$this->pog_query .= "`".$fcv_array[$i][0]."` ".$fcv_array[$i][1]." ".$value;
						}
					}
					else
					{
						$value = POG_Base::IsColumn($fcv_array[$i][2]) ? $fcv_array[$i][2] : "'".$fcv_array[$i][2]."'";
						$this->pog_query .= "`".$fcv_array[$i][0]."` ".$fcv_array[$i][1]." ".$value;
					}
				}
			}
		}
		if ($sortBy != '')
		{
			if (isset($this->pog_attribute_type[$sortBy]['db_attributes']) && $this->pog_attribute_type[$sortBy]['db_attributes'][0] != 'NUMERIC' && $this->pog_attribute_type[$sortBy]['db_attributes'][0] != 'SET')
			{
				if ($GLOBALS['configuration']['db_encoding'] == 1)
				{
					$sortBy = "BASE64_DECODE($sortBy) ";
				}
				else
				{
					$sortBy = "$sortBy ";
				}
			}
			else
			{
				$sortBy = "$sortBy ";
			}
		}
		else
		{
			$sortBy = "reportoptionid";
		}
		$this->pog_query .= " order by ".$sortBy." ".($ascending ? "asc" : "desc")." $sqlLimit";
		$thisObjectName = get_class($this);
		$cursor = Database::Reader($this->pog_query, $connection);
		while ($row = Database::Read($cursor))
		{
			$reportoption = new $thisObjectName();
			$reportoption->reportoptionId = $row['reportoptionid'];
			$reportoption->orderId = $row['orderid'];
			$reportoption->name = $this->Unescape($row['name']);
			$reportoption->start_date = $row['start_date'];
			$reportoption->duration = $this->Unescape($row['duration']);
			$reportoption->language = $this->Unescape($row['language']);
			$reportoption->paper_size = $this->Unescape($row['paper_size']);
			$reportoption->emailaddressId = $row['emailaddressid'];
			$reportoptionList[] = $reportoption;
		}
		return $reportoptionList;
	}
	
	
	/**
	* Saves the object to the database
	* @return integer $reportoptionId
	*/
	function Save()
	{
		$connection = Database::Connect();
		$this->pog_query = "select `reportoptionid` from `reportoption` where `reportoptionid`='".$this->reportoptionId."' LIMIT 1";
		$rows = Database::Query($this->pog_query, $connection);
		if ($rows > 0)
		{
			$this->pog_query = "update `reportoption` set 
			`orderid`='".$this->orderId."', 
			`name`='".$this->Escape($this->name)."', 
			`start_date`='".$this->start_date."', 
			`duration`='".$this->Escape($this->duration)."', 
			`language`='".$this->Escape($this->language)."', 
			`paper_size`='".$this->Escape($this->paper_size)."', 
			`emailaddressid`='".$this->emailaddressId."' where `reportoptionid`='".$this->reportoptionId."'";
		}
		else
		{
			$this->pog_query = "insert into `reportoption` (`orderid`, `name`, `start_date`, `duration`, `language`, `paper_size`, `emailaddressid` ) values (
			'".$this->orderId."', 
			'".$this->Escape($this->name)."', 
			'".$this->start_date."', 
			'".$this->Escape($this->duration)."', 
			'".$this->Escape($this->language)."', 
			'".$this->Escape($this->paper_size)."', 
			'".$this->emailaddressId."' )";
		}
		$insertId = Database::InsertOrUpdate($this->pog_query, $connection);
		if ($this->reportoptionId == "")
		{
			$this->reportoptionId = $insertId;
		}
		return $this->reportoptionId;
	}
	
	
	/**
	* Clones the object and saves it to the database
	* @return integer $reportoptionId
	*/
	function SaveNew()
	{
		$this->reportoptionId = '';
		return $this->Save();
	}
	
	
	/**
	* Deletes the object from the database
	* @return boolean
	*/
	function Delete()
	{
		$connection = Database::Connect();
		$this->pog_query = "delete from `reportoption` where `reportoptionid`='".$this->reportoptionId."'";
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
			$pog_query = "delete from `reportoption` where ";
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
	
	
	/**
	* Associates the order object to this one
	* @return boolean
	*/
	function GetOrder()
	{
		$order = new order();
		return $order->Get($this->orderId);
	}
	
	
	/**
	* Associates the order object to this one
	* @return 
	*/
	function SetOrder(&$order)
	{
		$this->orderId = $order->orderId;
	}
	
	
	/**
	* Associates the emailaddress object to this one
	* @return boolean
	*/
	function GetEmailaddress()
	{
		$emailaddress = new emailaddress();
		return $emailaddress->Get($this->emailaddressId);
	}
	
	
	/**
	* Associates the emailaddress object to this one
	* @return 
	*/
	function SetEmailaddress(&$emailaddress)
	{
		$this->emailaddressId = $emailaddress->emailaddressId;
	}
}
?>