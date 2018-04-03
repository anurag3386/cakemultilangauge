<?php
/*
	This SQL query will create the table to store your object.

	CREATE TABLE `transaction` (
	`transactionid` int(11) NOT NULL auto_increment,
	`orderid` int(11) NOT NULL,
	`state` INT NOT NULL,
	`timestamp` DATETIME NOT NULL, INDEX(`orderid`), PRIMARY KEY  (`transactionid`)) ENGINE=MyISAM;
*/

/**
* <b>transaction</b> class with integrated CRUD methods.
* @author Php Object Generator
* @version POG 3.0d / PHP5
* @copyright Free for personal & commercial use. (Offered under the BSD license)
* @link http://www.phpobjectgenerator.com/?language=php5&wrapper=pog&objectName=transaction&attributeList=array+%28%0A++0+%3D%3E+%27order%27%2C%0A++1+%3D%3E+%27state%27%2C%0A++2+%3D%3E+%27timestamp%27%2C%0A%29&typeList=array+%28%0A++0+%3D%3E+%27BELONGSTO%27%2C%0A++1+%3D%3E+%27INT%27%2C%0A++2+%3D%3E+%27DATETIME%27%2C%0A%29
*/
include_once('class.pog_base.php');
class transaction extends POG_Base
{
	public $transactionId = '';

	/**
	 * @var INT(11)
	 */
	public $orderId;
	
	/**
	 * @var INT
	 */
	public $state;
	
	/**
	 * @var DATETIME
	 */
	public $timestamp;
	
	public $pog_attribute_type = array(
		"transactionId" => array('db_attributes' => array("NUMERIC", "INT")),
		"order" => array('db_attributes' => array("OBJECT", "BELONGSTO")),
		"state" => array('db_attributes' => array("NUMERIC", "INT")),
		"timestamp" => array('db_attributes' => array("TEXT", "DATETIME")),
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
	
	function transaction($state='', $timestamp='')
	{
		$this->state = $state;
		$this->timestamp = $timestamp;
	}
	
	
	/**
	* Gets object from database
	* @param integer $transactionId 
	* @return object $transaction
	*/
	function Get($transactionId)
	{
		$connection = Database::Connect();
		$this->pog_query = "select * from `transaction` where `transactionid`='".intval($transactionId)."' LIMIT 1";
		$cursor = Database::Reader($this->pog_query, $connection);
		while ($row = Database::Read($cursor))
		{
			$this->transactionId = $row['transactionid'];
			$this->orderId = $row['orderid'];
			$this->state = $this->Unescape($row['state']);
			$this->timestamp = $row['timestamp'];
		}
		return $this;
	}
	
	
	/**
	* Returns a sorted array of objects that match given conditions
	* @param multidimensional array {("field", "comparator", "value"), ("field", "comparator", "value"), ...} 
	* @param string $sortBy 
	* @param boolean $ascending 
	* @param int limit 
	* @return array $transactionList
	*/
	function GetList($fcv_array = array(), $sortBy='', $ascending=true, $limit='')
	{
		$connection = Database::Connect();
		$sqlLimit = ($limit != '' ? "LIMIT $limit" : '');
		$this->pog_query = "select * from `transaction` ";
		$transactionList = Array();
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
			$sortBy = "transactionid";
		}
		$this->pog_query .= " order by ".$sortBy." ".($ascending ? "asc" : "desc")." $sqlLimit";
		$thisObjectName = get_class($this);
		$cursor = Database::Reader($this->pog_query, $connection);
		while ($row = Database::Read($cursor))
		{
			$transaction = new $thisObjectName();
			$transaction->transactionId = $row['transactionid'];
			$transaction->orderId = $row['orderid'];
			$transaction->state = $this->Unescape($row['state']);
			$transaction->timestamp = $row['timestamp'];
			$transactionList[] = $transaction;
		}
		return $transactionList;
	}
	
	
	/**
	* Saves the object to the database
	* @return integer $transactionId
	*/
	function Save()
	{
		$connection = Database::Connect();
		$this->pog_query = "select `transactionid` from `transaction` where `transactionid`='".$this->transactionId."' LIMIT 1";
		$rows = Database::Query($this->pog_query, $connection);
		if ($rows > 0)
		{
			$this->pog_query = "update `transaction` set 
			`orderid`='".$this->orderId."', 
			`state`='".$this->Escape($this->state)."', 
			`timestamp`='".$this->timestamp."' where `transactionid`='".$this->transactionId."'";
		}
		else
		{
			$this->pog_query = "insert into `transaction` (`orderid`, `state`, `timestamp` ) values (
			'".$this->orderId."', 
			'".$this->Escape($this->state)."', 
			'".$this->timestamp."' )";
		}
		$insertId = Database::InsertOrUpdate($this->pog_query, $connection);
		if ($this->transactionId == "")
		{
			$this->transactionId = $insertId;
		}
		return $this->transactionId;
	}
	
	
	/**
	* Clones the object and saves it to the database
	* @return integer $transactionId
	*/
	function SaveNew()
	{
		$this->transactionId = '';
		return $this->Save();
	}
	
	
	/**
	* Deletes the object from the database
	* @return boolean
	*/
	function Delete()
	{
		$connection = Database::Connect();
		$this->pog_query = "delete from `transaction` where `transactionid`='".$this->transactionId."'";
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
			$pog_query = "delete from `transaction` where ";
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
}
?>