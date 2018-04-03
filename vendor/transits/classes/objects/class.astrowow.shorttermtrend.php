<?php
/*
	This SQL query will create the table to store your object.

	CREATE TABLE `shorttermtrend` (
	`shorttermtrendid` int(11) NOT NULL auto_increment,
	`trendtext` TEXT NOT NULL,
	`txtques1` TEXT NOT NULL,
	`txtques2` TEXT NOT NULL,
	`txtques3` TEXT NOT NULL,
	`txtans1` TEXT NOT NULL,
	`txtans2` TEXT NOT NULL,
	`txtans3` TEXT NOT NULL, PRIMARY KEY  (`shorttermtrendid`)) ENGINE=MyISAM;
*/

/**
* <b>shorttermtrend</b> class with integrated CRUD methods.
* @author Php Object Generator
* @version POG 3.0d / PHP5
* @copyright Free for personal & commercial use. (Offered under the BSD license)
* @link http://www.phpobjectgenerator.com/?language=php5&wrapper=pog&objectName=shorttermtrend&attributeList=array+%28%0A++0+%3D%3E+%27trendtext%27%2C%0A++1+%3D%3E+%27txtques1%27%2C%0A++2+%3D%3E+%27txtques2%27%2C%0A++3+%3D%3E+%27txtques3%27%2C%0A++4+%3D%3E+%27txtans1%27%2C%0A++5+%3D%3E+%27txtans2%27%2C%0A++6+%3D%3E+%27txtans3%27%2C%0A%29&typeList=array+%28%0A++0+%3D%3E+%27TEXT%27%2C%0A++1+%3D%3E+%27TEXT%27%2C%0A++2+%3D%3E+%27TEXT%27%2C%0A++3+%3D%3E+%27TEXT%27%2C%0A++4+%3D%3E+%27TEXT%27%2C%0A++5+%3D%3E+%27TEXT%27%2C%0A++6+%3D%3E+%27TEXT%27%2C%0A%29
*/
include_once('class.pog_base.php');
class shorttermtrend extends POG_Base
{
	public $shorttermtrendId = '';

	/**
	 * @var TEXT
	 */
	public $trendtext;
	
	/**
	 * @var TEXT
	 */
	public $txtques1;
	
	/**
	 * @var TEXT
	 */
	public $txtques2;
	
	/**
	 * @var TEXT
	 */
	public $txtques3;
	
	/**
	 * @var TEXT
	 */
	public $txtans1;
	
	/**
	 * @var TEXT
	 */
	public $txtans2;
	
	/**
	 * @var TEXT
	 */
	public $txtans3;
	
	public $pog_attribute_type = array(
		"shorttermtrendId" => array('db_attributes' => array("NUMERIC", "INT")),
		"trendtext" => array('db_attributes' => array("TEXT", "TEXT")),
		"txtques1" => array('db_attributes' => array("TEXT", "TEXT")),
		"txtques2" => array('db_attributes' => array("TEXT", "TEXT")),
		"txtques3" => array('db_attributes' => array("TEXT", "TEXT")),
		"txtans1" => array('db_attributes' => array("TEXT", "TEXT")),
		"txtans2" => array('db_attributes' => array("TEXT", "TEXT")),
		"txtans3" => array('db_attributes' => array("TEXT", "TEXT")),
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
	
	function shorttermtrend($trendtext='', $txtques1='', $txtques2='', $txtques3='', $txtans1='', $txtans2='', $txtans3='')
	{
		$this->trendtext = $trendtext;
		$this->txtques1 = $txtques1;
		$this->txtques2 = $txtques2;
		$this->txtques3 = $txtques3;
		$this->txtans1 = $txtans1;
		$this->txtans2 = $txtans2;
		$this->txtans3 = $txtans3;
	}
	
	
	/**
	* Gets object from database
	* @param integer $shorttermtrendId 
	* @return object $shorttermtrend
	*/
	function Get($shorttermtrendId)
	{
		$connection = Database::Connect();
		$this->pog_query = "select * from `shorttermtrend` where `shorttermtrendid`='".intval($shorttermtrendId)."' LIMIT 1";
		$cursor = Database::Reader($this->pog_query, $connection);
		while ($row = Database::Read($cursor))
		{
			$this->shorttermtrendId = $row['shorttermtrendid'];
			$this->trendtext = $this->Unescape($row['trendtext']);
			$this->txtques1 = $this->Unescape($row['txtques1']);
			$this->txtques2 = $this->Unescape($row['txtques2']);
			$this->txtques3 = $this->Unescape($row['txtques3']);
			$this->txtans1 = $this->Unescape($row['txtans1']);
			$this->txtans2 = $this->Unescape($row['txtans2']);
			$this->txtans3 = $this->Unescape($row['txtans3']);
		}
		return $this;
	}
	
	
	/**
	* Returns a sorted array of objects that match given conditions
	* @param multidimensional array {("field", "comparator", "value"), ("field", "comparator", "value"), ...} 
	* @param string $sortBy 
	* @param boolean $ascending 
	* @param int limit 
	* @return array $shorttermtrendList
	*/
	function GetList($fcv_array = array(), $sortBy='', $ascending=true, $limit='')
	{
		$connection = Database::Connect();
		$sqlLimit = ($limit != '' ? "LIMIT $limit" : '');
		$this->pog_query = "select * from `shorttermtrend` ";
		$shorttermtrendList = Array();
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
			$sortBy = "shorttermtrendid";
		}
		$this->pog_query .= " order by ".$sortBy." ".($ascending ? "asc" : "desc")." $sqlLimit";
		$thisObjectName = get_class($this);
		$cursor = Database::Reader($this->pog_query, $connection);
		while ($row = Database::Read($cursor))
		{
			$shorttermtrend = new $thisObjectName();
			$shorttermtrend->shorttermtrendId = $row['shorttermtrendid'];
			$shorttermtrend->trendtext = $this->Unescape($row['trendtext']);
			$shorttermtrend->txtques1 = $this->Unescape($row['txtques1']);
			$shorttermtrend->txtques2 = $this->Unescape($row['txtques2']);
			$shorttermtrend->txtques3 = $this->Unescape($row['txtques3']);
			$shorttermtrend->txtans1 = $this->Unescape($row['txtans1']);
			$shorttermtrend->txtans2 = $this->Unescape($row['txtans2']);
			$shorttermtrend->txtans3 = $this->Unescape($row['txtans3']);
			$shorttermtrendList[] = $shorttermtrend;
		}
		return $shorttermtrendList;
	}
	
	
	/**
	* Saves the object to the database
	* @return integer $shorttermtrendId
	*/
	function Save()
	{
		$connection = Database::Connect();
		$this->pog_query = "select `shorttermtrendid` from `shorttermtrend` where `shorttermtrendid`='".$this->shorttermtrendId."' LIMIT 1";
		$rows = Database::Query($this->pog_query, $connection);
		if ($rows > 0)
		{
			$this->pog_query = "update `shorttermtrend` set 
			`trendtext`='".$this->Escape($this->trendtext)."', 
			`txtques1`='".$this->Escape($this->txtques1)."', 
			`txtques2`='".$this->Escape($this->txtques2)."', 
			`txtques3`='".$this->Escape($this->txtques3)."', 
			`txtans1`='".$this->Escape($this->txtans1)."', 
			`txtans2`='".$this->Escape($this->txtans2)."', 
			`txtans3`='".$this->Escape($this->txtans3)."' where `shorttermtrendid`='".$this->shorttermtrendId."'";
		}
		else
		{
			$this->pog_query = "insert into `shorttermtrend` (`trendtext`, `txtques1`, `txtques2`, `txtques3`, `txtans1`, `txtans2`, `txtans3` ) values (
			'".$this->Escape($this->trendtext)."', 
			'".$this->Escape($this->txtques1)."', 
			'".$this->Escape($this->txtques2)."', 
			'".$this->Escape($this->txtques3)."', 
			'".$this->Escape($this->txtans1)."', 
			'".$this->Escape($this->txtans2)."', 
			'".$this->Escape($this->txtans3)."' )";
		}
		$insertId = Database::InsertOrUpdate($this->pog_query, $connection);
		if ($this->shorttermtrendId == "")
		{
			$this->shorttermtrendId = $insertId;
		}
		return $this->shorttermtrendId;
	}
	
	
	/**
	* Clones the object and saves it to the database
	* @return integer $shorttermtrendId
	*/
	function SaveNew()
	{
		$this->shorttermtrendId = '';
		return $this->Save();
	}
	
	
	/**
	* Deletes the object from the database
	* @return boolean
	*/
	function Delete()
	{
		$connection = Database::Connect();
		$this->pog_query = "delete from `shorttermtrend` where `shorttermtrendid`='".$this->shorttermtrendId."'";
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
			$pog_query = "delete from `shorttermtrend` where ";
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
