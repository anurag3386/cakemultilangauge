<?php
/*
	This SQL query will create the table to store your object.

	CREATE TABLE `portal` (
	`portalid` int(11) NOT NULL auto_increment,
	`name` VARCHAR(255) NOT NULL, PRIMARY KEY  (`portalid`)) ENGINE=MyISAM;
*/

/**
* <b>portal</b> class with integrated CRUD methods.
* @author Php Object Generator
* @version POG 3.0d / PHP5
* @copyright Free for personal & commercial use. (Offered under the BSD license)
* @link http://www.phpobjectgenerator.com/?language=php5&wrapper=pog&objectName=portal&attributeList=array+%28%0A++0+%3D%3E+%27name%27%2C%0A++1+%3D%3E+%27pricing%27%2C%0A++2+%3D%3E+%27order%27%2C%0A%29&typeList=array+%28%0A++0+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++1+%3D%3E+%27HASMANY%27%2C%0A++2+%3D%3E+%27HASMANY%27%2C%0A%29
*/
include_once('class.pog_base.php');
class portal extends POG_Base
{
	public $portalId = '';

	/**
	 * @var VARCHAR(255)
	 */
	public $name;
	
	/**
	 * @var private array of pricing objects
	 */
	private $_pricingList = array();
	
	/**
	 * @var private array of order objects
	 */
	private $_orderList = array();
	
	public $pog_attribute_type = array(
		"portalId" => array('db_attributes' => array("NUMERIC", "INT")),
		"name" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
		"pricing" => array('db_attributes' => array("OBJECT", "HASMANY")),
		"order" => array('db_attributes' => array("OBJECT", "HASMANY")),
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
	
	function portal($name='')
	{
		$this->name = $name;
		$this->_pricingList = array();
		$this->_orderList = array();
	}
	
	
	/**
	* Gets object from database
	* @param integer $portalId 
	* @return object $portal
	*/
	function Get($portalId)
	{
		$connection = Database::Connect();
		$this->pog_query = "select * from `portal` where `portalid`='".intval($portalId)."' LIMIT 1";
		$cursor = Database::Reader($this->pog_query, $connection);
		while ($row = Database::Read($cursor))
		{
			$this->portalId = $row['portalid'];
			$this->name = $this->Unescape($row['name']);
		}
		return $this;
	}
	
	
	/**
	* Returns a sorted array of objects that match given conditions
	* @param multidimensional array {("field", "comparator", "value"), ("field", "comparator", "value"), ...} 
	* @param string $sortBy 
	* @param boolean $ascending 
	* @param int limit 
	* @return array $portalList
	*/
	function GetList($fcv_array = array(), $sortBy='', $ascending=true, $limit='')
	{
		$connection = Database::Connect();
		$sqlLimit = ($limit != '' ? "LIMIT $limit" : '');
		$this->pog_query = "select * from `portal` ";
		$portalList = Array();
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
			$sortBy = "portalid";
		}
		$this->pog_query .= " order by ".$sortBy." ".($ascending ? "asc" : "desc")." $sqlLimit";
		$thisObjectName = get_class($this);
		$cursor = Database::Reader($this->pog_query, $connection);
		while ($row = Database::Read($cursor))
		{
			$portal = new $thisObjectName();
			$portal->portalId = $row['portalid'];
			$portal->name = $this->Unescape($row['name']);
			$portalList[] = $portal;
		}
		return $portalList;
	}
	
	
	/**
	* Saves the object to the database
	* @return integer $portalId
	*/
	function Save($deep = true)
	{
		$connection = Database::Connect();
		$this->pog_query = "select `portalid` from `portal` where `portalid`='".$this->portalId."' LIMIT 1";
		$rows = Database::Query($this->pog_query, $connection);
		if ($rows > 0)
		{
			$this->pog_query = "update `portal` set 
			`name`='".$this->Escape($this->name)."'where `portalid`='".$this->portalId."'";
		}
		else
		{
			$this->pog_query = "insert into `portal` (`name`) values (
			'".$this->Escape($this->name)."')";
		}
		$insertId = Database::InsertOrUpdate($this->pog_query, $connection);
		if ($this->portalId == "")
		{
			$this->portalId = $insertId;
		}
		if ($deep)
		{
			foreach ($this->_pricingList as $pricing)
			{
				$pricing->portalId = $this->portalId;
				$pricing->Save($deep);
			}
			foreach ($this->_orderList as $order)
			{
				$order->portalId = $this->portalId;
				$order->Save($deep);
			}
		}
		return $this->portalId;
	}
	
	
	/**
	* Clones the object and saves it to the database
	* @return integer $portalId
	*/
	function SaveNew($deep = false)
	{
		$this->portalId = '';
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
			$pricingList = $this->GetPricingList();
			foreach ($pricingList as $pricing)
			{
				$pricing->Delete($deep, $across);
			}
			$orderList = $this->GetOrderList();
			foreach ($orderList as $order)
			{
				$order->Delete($deep, $across);
			}
		}
		$connection = Database::Connect();
		$this->pog_query = "delete from `portal` where `portalid`='".$this->portalId."'";
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
				$pog_query = "delete from `portal` where ";
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
	* Gets a list of pricing objects associated to this one
	* @param multidimensional array {("field", "comparator", "value"), ("field", "comparator", "value"), ...} 
	* @param string $sortBy 
	* @param boolean $ascending 
	* @param int limit 
	* @return array of pricing objects
	*/
	function GetPricingList($fcv_array = array(), $sortBy='', $ascending=true, $limit='')
	{
		$pricing = new pricing();
		$fcv_array[] = array("portalId", "=", $this->portalId);
		$dbObjects = $pricing->GetList($fcv_array, $sortBy, $ascending, $limit);
		return $dbObjects;
	}
	
	
	/**
	* Makes this the parent of all pricing objects in the pricing List array. Any existing pricing will become orphan(s)
	* @return null
	*/
	function SetPricingList(&$list)
	{
		$this->_pricingList = array();
		$existingPricingList = $this->GetPricingList();
		foreach ($existingPricingList as $pricing)
		{
			$pricing->portalId = '';
			$pricing->Save(false);
		}
		$this->_pricingList = $list;
	}
	
	
	/**
	* Associates the pricing object to this one
	* @return 
	*/
	function AddPricing(&$pricing)
	{
		$pricing->portalId = $this->portalId;
		$found = false;
		foreach($this->_pricingList as $pricing2)
		{
			if ($pricing->pricingId > 0 && $pricing->pricingId == $pricing2->pricingId)
			{
				$found = true;
				break;
			}
		}
		if (!$found)
		{
			$this->_pricingList[] = $pricing;
		}
	}
	
	
	/**
	* Gets a list of order objects associated to this one
	* @param multidimensional array {("field", "comparator", "value"), ("field", "comparator", "value"), ...} 
	* @param string $sortBy 
	* @param boolean $ascending 
	* @param int limit 
	* @return array of order objects
	*/
	function GetOrderList($fcv_array = array(), $sortBy='', $ascending=true, $limit='')
	{
		$order = new order();
		$fcv_array[] = array("portalId", "=", $this->portalId);
		$dbObjects = $order->GetList($fcv_array, $sortBy, $ascending, $limit);
		return $dbObjects;
	}
	
	
	/**
	* Makes this the parent of all order objects in the order List array. Any existing order will become orphan(s)
	* @return null
	*/
	function SetOrderList(&$list)
	{
		$this->_orderList = array();
		$existingOrderList = $this->GetOrderList();
		foreach ($existingOrderList as $order)
		{
			$order->portalId = '';
			$order->Save(false);
		}
		$this->_orderList = $list;
	}
	
	
	/**
	* Associates the order object to this one
	* @return 
	*/
	function AddOrder(&$order)
	{
		$order->portalId = $this->portalId;
		$found = false;
		foreach($this->_orderList as $order2)
		{
			if ($order->orderId > 0 && $order->orderId == $order2->orderId)
			{
				$found = true;
				break;
			}
		}
		if (!$found)
		{
			$this->_orderList[] = $order;
		}
	}
}
?>