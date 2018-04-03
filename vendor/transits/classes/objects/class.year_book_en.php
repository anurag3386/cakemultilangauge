<?php
/*
	This SQL query will create the table to store your object.

	CREATE TABLE `year_book_en` (
	`year_book_id` int(11) NOT NULL auto_increment,
	`chapter_id` BIGINT NOT NULL,
	`planet_code1` VARCHAR(255) NOT NULL,
	`aspect_id` VARCHAR(255) NOT NULL,
	`planet_code12` VARCHAR(255) NOT NULL,
	`aspect_strength` VARCHAR(255) NOT NULL,
	`planet_direction` VARCHAR(255) NOT NULL,
	`aspect_type` VARCHAR(255) NOT NULL,
	`description` MEDIUMTEXT NOT NULL,
	`title` MEDIUMTEXT NOT NULL,
	`short_text` MEDIUMTEXT NOT NULL,
	`tester_text` MEDIUMTEXT NOT NULL,
	`chapter_name` MEDIUMTEXT NOT NULL,
	`find_param` MEDIUMTEXT NOT NULL, PRIMARY KEY  (`year_book_id`)) ENGINE=MyISAM;
*/

/**
* <b>year_book_en</b> class with integrated CRUD methods.
* @author Php Object Generator
* @version POG 3.0f / PHP5
* @copyright Free for personal & commercial use. (Offered under the BSD license)
* @link http://www.phpobjectgenerator.com/?language=php5&wrapper=pog&objectName=year_book_en&attributeList=array+%28%0A++0+%3D%3E+%27chapter_id%27%2C%0A++1+%3D%3E+%27planet_code1%27%2C%0A++2+%3D%3E+%27aspect_id%27%2C%0A++3+%3D%3E+%27planet_code12%27%2C%0A++4+%3D%3E+%27aspect_strength%27%2C%0A++5+%3D%3E+%27planet_direction%27%2C%0A++6+%3D%3E+%27aspect_type%27%2C%0A++7+%3D%3E+%27description%27%2C%0A++8+%3D%3E+%27title%27%2C%0A++9+%3D%3E+%27short_text%27%2C%0A++10+%3D%3E+%27tester_text%27%2C%0A++11+%3D%3E+%27chapter_name%27%2C%0A%29&typeList=array+%28%0A++0+%3D%3E+%27BIGINT%27%2C%0A++1+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++2+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++3+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++4+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++5+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++6+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++7+%3D%3E+%27MEDIUMTEXT%27%2C%0A++8+%3D%3E+%27MEDIUMTEXT%27%2C%0A++9+%3D%3E+%27MEDIUMTEXT%27%2C%0A++10+%3D%3E+%27MEDIUMTEXT%27%2C%0A++11+%3D%3E+%27MEDIUMTEXT%27%2C%0A%29
*/
//include_once('class.POG_Base.php');
include_once('class.pog_base.php');
class year_book_en extends POG_Base
{
	public $year_book_id = '';

	/**
	 * @var BIGINT
	 */
	public $chapter_id;
	
	/**
	 * @var VARCHAR(255)
	 */
	public $planet_code1;
	
	/**
	 * @var VARCHAR(255)
	 */
	public $aspect_id;
	
	/**
	 * @var VARCHAR(255)
	 */
	public $planet_code12;
	
	/**
	 * @var VARCHAR(255)
	 */
	public $aspect_strength;
	
	/**
	 * @var VARCHAR(255)
	 */
	public $planet_direction;
	
	/**
	 * @var VARCHAR(255)
	 */
	public $aspect_type;
	
	/**
	 * @var MEDIUMTEXT
	 */
	public $description;
	
	/**
	 * @var MEDIUMTEXT
	 */
	public $title;
	
	/**
	 * @var MEDIUMTEXT
	 */
	public $short_text;
	
	/**
	 * @var MEDIUMTEXT
	 */
	public $tester_text;
	
	/**
	 * @var MEDIUMTEXT
	 */
	public $chapter_name;
	
	/**
	 * @var MEDIUMTEXT
	 */
	public $find_param;
	
	public $pog_attribute_type = array(
		"year_book_id" => array('db_attributes' => array("NUMERIC", "INT")),
		"chapter_id" => array('db_attributes' => array("NUMERIC", "BIGINT")),
		"planet_code1" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
		"aspect_id" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
		"planet_code12" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
		"aspect_strength" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
		"planet_direction" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
		"aspect_type" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
		"description" => array('db_attributes' => array("TEXT", "MEDIUMTEXT")),
		"title" => array('db_attributes' => array("TEXT", "MEDIUMTEXT")),
		"short_text" => array('db_attributes' => array("TEXT", "MEDIUMTEXT")),
		"tester_text" => array('db_attributes' => array("TEXT", "MEDIUMTEXT")),
		"chapter_name" => array('db_attributes' => array("TEXT", "MEDIUMTEXT")),
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
	
	//function year_book_en($chapter_id='', $planet_code1='', $aspect_id='', $planet_code12='', $aspect_strength='', $planet_direction='', $aspect_type='', $description='', $title='', $short_text='', $tester_text='', $chapter_name='', $find_param='')
	function __construct ($chapter_id='', $planet_code1='', $aspect_id='', $planet_code12='', $aspect_strength='', $planet_direction='', $aspect_type='', $description='', $title='', $short_text='', $tester_text='', $chapter_name='', $find_param='')
	{
		$this->chapter_id = $chapter_id;
		$this->planet_code1 = $planet_code1;
		$this->aspect_id = $aspect_id;
		$this->planet_code12 = $planet_code12;
		$this->aspect_strength = $aspect_strength;
		$this->planet_direction = $planet_direction;
		$this->aspect_type = $aspect_type;
		$this->description = $description;
		$this->title = $title;
		$this->short_text = $short_text;
		$this->tester_text = $tester_text;
		$this->chapter_name = $chapter_name;
		$this->find_param = $find_param;
	}
	
	
	/**
	* Gets object from database
	* @param integer $year_book_id
	* @return object $year_book_en
	*/
	function Get($year_book_id)
	{
		$connection = Database::Connect();
		$this->pog_query = "select * from `year_book_en` where `year_book_id`='".intval($year_book_id)."' LIMIT 1";
		$cursor = Database::Reader($this->pog_query, $connection);
		if ( $cursor){
			while ($row = Database::Read($cursor))
			{
				$this->year_book_id = $row['year_book_id'];
				$this->chapter_id = $this->Unescape($row['chapter_id']);
				$this->planet_code1 = $this->Unescape($row['planet_code1']);
				$this->aspect_id = $this->Unescape($row['aspect_id']);
				$this->planet_code12 = $this->Unescape($row['planet_code12']);
				$this->aspect_strength = $this->Unescape($row['aspect_strength']);
				$this->planet_direction = $this->Unescape($row['planet_direction']);
				$this->aspect_type = $this->Unescape($row['aspect_type']);
				$this->description = $this->Unescape($row['description']);
				$this->title = $this->Unescape($row['title']);
				$this->short_text = $this->Unescape($row['short_text']);
				$this->tester_text = $this->Unescape($row['tester_text']);
				$this->chapter_name = $this->Unescape($row['chapter_name']);
				$this->find_param = $this->Unescape($row['find_param']);
			}
		}
		else{
			echo "Could not successfully run query ($sql) from DB: " . mysql_error();
			exit;
		}
		return $this;
	}
	
	
	/**
	* Returns a sorted array of objects that match given conditions
	* @param multidimensional array {("field", "comparator", "value"), ("field", "comparator", "value"), ...} 
	* @param string $sortBy 
	* @param boolean $ascending 
	* @param int limit 
	* @return array $year_book_enList
	*/
	function GetList($fcv_array = array(), $sortBy='', $ascending=true, $limit='') {
		$connection = Database::Connect();
		$sqlLimit = ($limit != '' ? "LIMIT $limit" : '');
		$this->pog_query = "select * from `year_book_en` ";
		$year_book_enList = Array();
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

							//$this->pog_query .= "`".$fcv_array[$i][0]."` ".$fcv_array[$i][1]." ".$value;
							if(strtolower($fcv_array[$i][1]) == strtolower('LIKE')) {
								$this->pog_query .= "`".$fcv_array[$i][0]."` ".$fcv_array[$i][1]." '%".$fcv_array[$i][2] ."%'";
							} else{
								$this->pog_query .= "`".$fcv_array[$i][0]."` ".$fcv_array[$i][1]." ".$value;
							}
						}
					} else {
						$value = POG_Base::IsColumn($fcv_array[$i][2]) ? $fcv_array[$i][2] : "'".$fcv_array[$i][2]."'";
						
						if(strtolower($fcv_array[$i][1]) == strtolower('LIKE')) {
							$this->pog_query .= "`".$fcv_array[$i][0]."` ".$fcv_array[$i][1]." '%".$fcv_array[$i][2] ."%'";
						} else {
							$this->pog_query .= "`".$fcv_array[$i][0]."` ".$fcv_array[$i][1]." ".$value;
						}
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
			$sortBy = "year_book_id";
		}
		$this->pog_query .= " order by ".$sortBy." ".($ascending ? "asc" : "desc")." $sqlLimit";
		$thisObjectName = get_class($this);
		
		//echo '<pre>' .$this->pog_query . '</pre>';		
		
		$cursor = Database::Reader($this->pog_query, $connection);
		
		if ( $cursor){
			while ($row = Database::Read($cursor)) {
				$year_book_en = new $thisObjectName();
				$year_book_en->year_book_id = $row['year_book_id'];
				$year_book_en->chapter_id = $this->Unescape($row['chapter_id']);
				$year_book_en->planet_code1 = $this->Unescape($row['planet_code1']);
				$year_book_en->aspect_id = $this->Unescape($row['aspect_id']);
				$year_book_en->planet_code12 = $this->Unescape($row['planet_code12']);
				$year_book_en->aspect_strength = $this->Unescape($row['aspect_strength']);
				$year_book_en->planet_direction = $this->Unescape($row['planet_direction']);
				$year_book_en->aspect_type = $this->Unescape($row['aspect_type']);
				$year_book_en->description = $this->Unescape($row['description']);
				$year_book_en->title = $this->Unescape($row['title']);
				$year_book_en->short_text = $this->Unescape($row['short_text']);
				$year_book_en->tester_text = $this->Unescape($row['tester_text']);
				$year_book_en->chapter_name = $this->Unescape($row['chapter_name']);
				$year_book_en->find_param = $this->Unescape($row['find_param']);
				$year_book_enList[] = $year_book_en;
			}
		} else{
			echo "Could not successfully run query ($sql) from DB: " . mysql_error();
			exit;
		}
		return $year_book_enList;
	}
	
	
	/**
	* Saves the object to the database
	* @return integer $year_book_id
	*/
	function Save()
	{
		$connection = Database::Connect();
		$this->pog_query = "select `year_book_id` from `year_book_en` where `year_book_id`='".$this->year_book_id."' LIMIT 1";
		$rows = Database::Query($this->pog_query, $connection);
		if ($rows > 0)
		{
			$this->pog_query = "update `year_book_en` set 
			`chapter_id`='".$this->Escape($this->chapter_id)."', 
			`planet_code1`='".$this->Escape($this->planet_code1)."', 
			`aspect_id`='".$this->Escape($this->aspect_id)."', 
			`planet_code12`='".$this->Escape($this->planet_code12)."', 
			`aspect_strength`='".$this->Escape($this->aspect_strength)."', 
			`planet_direction`='".$this->Escape($this->planet_direction)."', 
			`aspect_type`='".$this->Escape($this->aspect_type)."', 
			`description`='".$this->Escape($this->description)."', 
			`title`='".$this->Escape($this->title)."', 
			`short_text`='".$this->Escape($this->short_text)."', 
			`tester_text`='".$this->Escape($this->tester_text)."', 
			`chapter_name`='".$this->Escape($this->chapter_name)."',
			`find_param`='".$this->Escape($this->find_param)."', where `year_book_id`='".$this->year_book_id."'";
		}
		else
		{
			$this->pog_query = "insert into `year_book_en` (`chapter_id`, `planet_code1`, `aspect_id`, `planet_code12`, `aspect_strength`, `planet_direction`, `aspect_type`, `description`, `title`, `short_text`, `tester_text`, `chapter_name`, `find_param` ) values (
			'".$this->Escape($this->chapter_id)."', 
			'".$this->Escape($this->planet_code1)."', 
			'".$this->Escape($this->aspect_id)."', 
			'".$this->Escape($this->planet_code12)."', 
			'".$this->Escape($this->aspect_strength)."', 
			'".$this->Escape($this->planet_direction)."', 
			'".$this->Escape($this->aspect_type)."', 
			'".$this->Escape($this->description)."', 
			'".$this->Escape($this->title)."', 
			'".$this->Escape($this->short_text)."', 
			'".$this->Escape($this->tester_text)."', 
			'".$this->Escape($this->chapter_name)."', 
			'".$this->Escape($this->find_param)."' )";
		}
		$insertId = Database::InsertOrUpdate($this->pog_query, $connection);
		if ($this->year_book_id == "")
		{
			$this->year_book_id = $insertId;
		}
		return $this->year_book_id;
	}
	
	
	/**
	* Clones the object and saves it to the database
	* @return integer $year_book_id
	*/
	function SaveNew()
	{
		$this->year_book_id = '';
		return $this->Save();
	}
	
	
	/**
	* Deletes the object from the database
	* @return boolean
	*/
	function Delete()
	{
		$connection = Database::Connect();
		$this->pog_query = "delete from `year_book_en` where `year_book_id`='".$this->year_book_id."'";
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
			$pog_query = "delete from `year_book_en` where ";
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
