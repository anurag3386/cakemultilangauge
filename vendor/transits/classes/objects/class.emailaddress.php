<?php
/*
	This SQL query will create the table to store your object.

	CREATE TABLE `emailaddress` (
	`emailaddressid` int(11) NOT NULL auto_increment,
	`address` VARCHAR(255) NOT NULL, PRIMARY KEY  (`emailaddressid`)) ENGINE=MyISAM;
*/

/**
* <b>emailaddress</b> class with integrated CRUD methods.
* @author Php Object Generator
* @version POG 3.0d / PHP5
* @copyright Free for personal & commercial use. (Offered under the BSD license)
* @link http://www.phpobjectgenerator.com/?language=php5&wrapper=pog&objectName=emailaddress&attributeList=array+%28%0A++0+%3D%3E+%27address%27%2C%0A++1+%3D%3E+%27reportoption%27%2C%0A%29&typeList=array+%28%0A++0+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++1+%3D%3E+%27HASMANY%27%2C%0A%29
*/
include_once('class.pog_base.php');
class emailaddress extends POG_Base
{
	public $emailaddressId = '';

	/**
	 * @var VARCHAR(255)
	 */
	public $address;
	
	/**
	 * @var private array of reportoption objects
	 */
	private $_reportoptionList = array();
	
	public $pog_attribute_type = array(
		"emailaddressId" => array('db_attributes' => array("NUMERIC", "INT")),
		"address" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
		"reportoption" => array('db_attributes' => array("OBJECT", "HASMANY")),
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
	
	function emailaddress($address='')
	{
		$this->address = $address;
		$this->_reportoptionList = array();
	}
	
	
	/**
	* Gets object from database
	* @param integer $emailaddressId 
	* @return object $emailaddress
	*/
	function Get($emailaddressId)
	{
		$connection = Database::Connect();
		$this->pog_query = "select * from `emailaddress` where `emailaddressid`='".intval($emailaddressId)."' LIMIT 1";
		$cursor = Database::Reader($this->pog_query, $connection);
		while ($row = Database::Read($cursor))
		{
			$this->emailaddressId = $row['emailaddressid'];
			$this->address = $this->Unescape($row['address']);
		}
		return $this;
	}
	
	
	/**
	* Returns a sorted array of objects that match given conditions
	* @param multidimensional array {("field", "comparator", "value"), ("field", "comparator", "value"), ...} 
	* @param string $sortBy 
	* @param boolean $ascending 
	* @param int limit 
	* @return array $emailaddressList
	*/
	function GetList($fcv_array = array(), $sortBy='', $ascending=true, $limit='')
	{
		$connection = Database::Connect();
		$sqlLimit = ($limit != '' ? "LIMIT $limit" : '');
		$this->pog_query = "select * from `emailaddress` ";
		$emailaddressList = Array();
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
			$sortBy = "emailaddressid";
		}
		$this->pog_query .= " order by ".$sortBy." ".($ascending ? "asc" : "desc")." $sqlLimit";
		$thisObjectName = get_class($this);
		$cursor = Database::Reader($this->pog_query, $connection);
		while ($row = Database::Read($cursor))
		{
			$emailaddress = new $thisObjectName();
			$emailaddress->emailaddressId = $row['emailaddressid'];
			$emailaddress->address = $this->Unescape($row['address']);
			$emailaddressList[] = $emailaddress;
		}
		return $emailaddressList;
	}
	
	
	/**
	* Saves the object to the database
	* @return integer $emailaddressId
	*/
	function Save($deep = true)
	{
		$connection = Database::Connect();
		$this->pog_query = "select `emailaddressid` from `emailaddress` where `emailaddressid`='".$this->emailaddressId."' LIMIT 1";
		$rows = Database::Query($this->pog_query, $connection);
		if ($rows > 0)
		{
			$this->pog_query = "update `emailaddress` set 
			`address`='".$this->Escape($this->address)."'where `emailaddressid`='".$this->emailaddressId."'";
		}
		else
		{
			$this->pog_query = "insert into `emailaddress` (`address`) values (
			'".$this->Escape($this->address)."')";
		}
		$insertId = Database::InsertOrUpdate($this->pog_query, $connection);
		if ($this->emailaddressId == "")
		{
			$this->emailaddressId = $insertId;
		}
		if ($deep)
		{
			foreach ($this->_reportoptionList as $reportoption)
			{
				$reportoption->emailaddressId = $this->emailaddressId;
				$reportoption->Save($deep);
			}
		}
		return $this->emailaddressId;
	}
	
	
	/**
	* Clones the object and saves it to the database
	* @return integer $emailaddressId
	*/
	function SaveNew($deep = false)
	{
		$this->emailaddressId = '';
		return $this->Save($deep);
	}
	
	
	/**
	* Deletes the object from the database
	* @return boolean
	*/
	function Delete($deep = false, $across = false)
	{
		if ($deep)
		{
			$reportoptionList = $this->GetReportoptionList();
			foreach ($reportoptionList as $reportoption)
			{
				$reportoption->Delete($deep, $across);
			}
		}
		$connection = Database::Connect();
		$this->pog_query = "delete from `emailaddress` where `emailaddressid`='".$this->emailaddressId."'";
		return Database::NonQuery($this->pog_query, $connection);
	}
	
	
	/**
	* Deletes a list of objects that match given conditions
	* @param multidimensional array {("field", "comparator", "value"), ("field", "comparator", "value"), ...} 
	* @param bool $deep 
	* @return 
	*/
	function DeleteList($fcv_array, $deep = false, $across = false)
	{
		if (sizeof($fcv_array) > 0)
		{
			if ($deep || $across)
			{
				$objectList = $this->GetList($fcv_array);
				foreach ($objectList as $object)
				{
					$object->Delete($deep, $across);
				}
			}
			else
			{
				$connection = Database::Connect();
				$pog_query = "delete from `emailaddress` where ";
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
	
	
	/**
	* Gets a list of reportoption objects associated to this one
	* @param multidimensional array {("field", "comparator", "value"), ("field", "comparator", "value"), ...} 
	* @param string $sortBy 
	* @param boolean $ascending 
	* @param int limit 
	* @return array of reportoption objects
	*/
	function GetReportoptionList($fcv_array = array(), $sortBy='', $ascending=true, $limit='')
	{
		$reportoption = new reportoption();
		$fcv_array[] = array("emailaddressId", "=", $this->emailaddressId);
		$dbObjects = $reportoption->GetList($fcv_array, $sortBy, $ascending, $limit);
		return $dbObjects;
	}
	
	
	/**
	* Makes this the parent of all reportoption objects in the reportoption List array. Any existing reportoption will become orphan(s)
	* @return null
	*/
	function SetReportoptionList(&$list)
	{
		$this->_reportoptionList = array();
		$existingReportoptionList = $this->GetReportoptionList();
		foreach ($existingReportoptionList as $reportoption)
		{
			$reportoption->emailaddressId = '';
			$reportoption->Save(false);
		}
		$this->_reportoptionList = $list;
	}
	
	
	/**
	* Associates the reportoption object to this one
	* @return 
	*/
	function AddReportoption(&$reportoption)
	{
		$reportoption->emailaddressId = $this->emailaddressId;
		$found = false;
		foreach($this->_reportoptionList as $reportoption2)
		{
			if ($reportoption->reportoptionId > 0 && $reportoption->reportoptionId == $reportoption2->reportoptionId)
			{
				$found = true;
				break;
			}
		}
		if (!$found)
		{
			$this->_reportoptionList[] = $reportoption;
		}
	}
}
?>
