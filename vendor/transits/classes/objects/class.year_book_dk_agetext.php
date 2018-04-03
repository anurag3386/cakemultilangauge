<?php
/*
	This SQL query will create the table to store your object.

	CREATE TABLE `year_book_dk_agetext` (
	`age_id` int(11) NOT NULL auto_increment,
	`age_no` BIGINT NOT NULL,
	`age_text` MEDIUMTEXT NOT NULL,
	`find_param` MEDIUMTEXT NOT NULL, PRIMARY KEY  (`age_id`)) ENGINE=MyISAM;
*/

/**
* <b>year_book_en_agetext</b> class with integrated CRUD methods.
* @author Php Object Generator
* @version POG 3.0f / PHP5
* @copyright Free for personal & commercial use. (Offered under the BSD license)
* @link http://www.phpobjectgenerator.com/?language=php5&wrapper=pog&objectName=year_book_en_agetext&attributeList=array+%28%0A++0+%3D%3E+%27age_no%27%2C%0A++1+%3D%3E+%27age_text%27%2C%0A++2+%3D%3E+%27find_param%27%2C%0A%29&typeList=array+%28%0A++0+%3D%3E+%27BIGINT%27%2C%0A++1+%3D%3E+%27MEDIUMTEXT%27%2C%0A++2+%3D%3E+%27MEDIUMTEXT%27%2C%0A%29
*/
include_once('class.pog_base.php');
class year_book_dk_agetext extends POG_Base
{
	public $age_Id = '';

	/**
	 * @var BIGINT
	 */
	public $age_no;
	
	/**
	 * @var MEDIUMTEXT
	 */
	public $age_text;
	
	/**
	 * @var MEDIUMTEXT
	 */
	public $find_param;
	
	public $pog_attribute_type = array(
		"age_Id" => array('db_attributes' => array("NUMERIC", "INT")),
		"age_no" => array('db_attributes' => array("NUMERIC", "BIGINT")),
		"age_text" => array('db_attributes' => array("TEXT", "MEDIUMTEXT")),
		"find_param" => array('db_attributes' => array("TEXT", "MEDIUMTEXT")),
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
	
	function year_book_dk_agetext($age_no='', $age_text='', $find_param='')
	{
		$this->age_no = $age_no;
		$this->age_text = $age_text;
		$this->find_param = $find_param;
	}
	
	
	/**
	* Gets object from database
	* @param integer $age_Id 
	* @return object $year_book_dk_agetext
	*/
	function Get($age_Id)
	{
		$connection = Database::Connect();
		$this->pog_query = "select * from `year_book_dk_agetext` where `age_id`='".intval($age_Id)."' LIMIT 1";
		$cursor = Database::Reader($this->pog_query, $connection);
		while ($row = Database::Read($cursor))
		{
			$this->age_Id = $row['age_id'];
			$this->age_no = $this->Unescape($row['age_no']);
			$this->age_text = $this->Unescape($row['age_text']);
			$this->find_param = $this->Unescape($row['find_param']);
		}
		return $this;
	}
	
	
	/**
	* Returns a sorted array of objects that match given conditions
	* @param multidimensional array {("field", "comparator", "value"), ("field", "comparator", "value"), ...} 
	* @param string $sortBy 
	* @param boolean $ascending 
	* @param int limit 
	* @return array $year_book_dk_agetextList
	*/
	function GetList($fcv_array = array(), $sortBy='', $ascending=true, $limit='')
	{
		$connection = Database::Connect();
		$sqlLimit = ($limit != '' ? "LIMIT $limit" : '');
		$this->pog_query = "select * from `year_book_dk_agetext` ";
		$year_book_dk_agetextList = Array();
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
			$sortBy = "age_id";
		}
		$this->pog_query .= " order by ".$sortBy." ".($ascending ? "asc" : "desc")." $sqlLimit";
		$thisObjectName = get_class($this);
		$cursor = Database::Reader($this->pog_query, $connection);
		while ($row = Database::Read($cursor))
		{
			$year_book_dk_agetext = new $thisObjectName();
			$year_book_dk_agetext->age_Id = $row['age_id'];
			$year_book_dk_agetext->age_no = $this->Unescape($row['age_no']);
			$year_book_dk_agetext->age_text = $this->Unescape($row['age_text']);
			$year_book_dk_agetext->find_param = $this->Unescape($row['find_param']);
			$year_book_dk_agetextList[] = $year_book_dk_agetext;
		}
		return $year_book_dk_agetextList;
	}
	
	
	/**
	* Saves the object to the database
	* @return integer $age_Id
	*/
	function Save()
	{
		$connection = Database::Connect();
		$this->pog_query = "select `age_id` from `year_book_dk_agetext` where `age_id`='".$this->age_Id."' LIMIT 1";
		$rows = Database::Query($this->pog_query, $connection);
		if ($rows > 0)
		{
			$this->pog_query = "update `year_book_dk_agetext` set 
			`age_no`='".$this->Escape($this->age_no)."', 
			`age_text`='".$this->Escape($this->age_text)."', 
			`find_param`='".$this->Escape($this->find_param)."' where `age_id`='".$this->age_Id."'";
		}
		else
		{
			$this->pog_query = "insert into `year_book_dk_agetext` (`age_no`, `age_text`, `find_param` ) values (
			'".$this->Escape($this->age_no)."', 
			'".$this->Escape($this->age_text)."', 
			'".$this->Escape($this->find_param)."' )";
		}
		$insertId = Database::InsertOrUpdate($this->pog_query, $connection);
		if ($this->age_Id == "")
		{
			$this->age_Id = $insertId;
		}
		return $this->age_Id;
	}
	
	
	/**
	* Clones the object and saves it to the database
	* @return integer $age_Id
	*/
	function SaveNew()
	{
		$this->age_Id = '';
		return $this->Save();
	}
	
	
	/**
	* Deletes the object from the database
	* @return boolean
	*/
	function Delete()
	{
		$connection = Database::Connect();
		$this->pog_query = "delete from `year_book_dk_agetext` where `age_id`='".$this->age_Id."'";
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
			$pog_query = "delete from `year_book_dk_agetext` where ";
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