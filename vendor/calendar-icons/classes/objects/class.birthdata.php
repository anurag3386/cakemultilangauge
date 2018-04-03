<?php
/*
	This SQL query will create the table to store your object.

	CREATE TABLE `birthdata` (
	`birthdataid` int(11) NOT NULL auto_increment,
	`day` TINYINT NOT NULL,
	`month` TINYINT NOT NULL,
	`year` INT NOT NULL,
	`untimed` CHAR(255) NOT NULL,
	`hour` TINYINT NOT NULL,
	`minute` TINYINT NOT NULL,
	`gmt` CHAR(255) NOT NULL,
	`zoneref` INT NOT NULL,
	`summerref` INT NOT NULL,
	`place` VARCHAR(255) NOT NULL,
	`state` VARCHAR(255) NOT NULL,
	`longitude` FLOAT NOT NULL,
	`latitude` FLOAT NOT NULL,
	`orderid` VARCHAR(255) NOT NULL,
	`first_name` VARCHAR(255) NOT NULL,
	`last_name` VARCHAR(255) NOT NULL,
	`duration` INT NOT NULL,
	`start_date` DATE NOT NULL, PRIMARY KEY  (`birthdataid`)) ENGINE=MyISAM;
*/

/**
 * <b>birthdata</b> class with integrated CRUD methods.
 * @author Php Object Generator
 * @version POG 3.0f / PHP5
 * @copyright Free for personal & commercial use. (Offered under the BSD license)
 * @link http://www.phpobjectgenerator.com/?language=php5&wrapper=pog&objectName=birthdata&attributeList=array+%28%0A++0+%3D%3E+%27day%27%2C%0A++1+%3D%3E+%27month%27%2C%0A++2+%3D%3E+%27year%27%2C%0A++3+%3D%3E+%27untimed%27%2C%0A++4+%3D%3E+%27hour%27%2C%0A++5+%3D%3E+%27minute%27%2C%0A++6+%3D%3E+%27gmt%27%2C%0A++7+%3D%3E+%27zoneref%27%2C%0A++8+%3D%3E+%27summerref%27%2C%0A++9+%3D%3E+%27place%27%2C%0A++10+%3D%3E+%27state%27%2C%0A++11+%3D%3E+%27longitude%27%2C%0A++12+%3D%3E+%27latitude%27%2C%0A++13+%3D%3E+%27orderid%27%2C%0A++14+%3D%3E+%27first_name%27%2C%0A++15+%3D%3E+%27last_name%27%2C%0A++16+%3D%3E+%27duration%27%2C%0A++17+%3D%3E+%27start_date%27%2C%0A%29&typeList=array+%28%0A++0+%3D%3E+%27TINYINT%27%2C%0A++1+%3D%3E+%27TINYINT%27%2C%0A++2+%3D%3E+%27INT%27%2C%0A++3+%3D%3E+%27CHAR%28255%29%27%2C%0A++4+%3D%3E+%27TINYINT%27%2C%0A++5+%3D%3E+%27TINYINT%27%2C%0A++6+%3D%3E+%27CHAR%28255%29%27%2C%0A++7+%3D%3E+%27INT%27%2C%0A++8+%3D%3E+%27INT%27%2C%0A++9+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++10+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++11+%3D%3E+%27FLOAT%27%2C%0A++12+%3D%3E+%27FLOAT%27%2C%0A++13+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++14+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++15+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++16+%3D%3E+%27INT%27%2C%0A++17+%3D%3E+%27DATE%27%2C%0A%29
 */
include_once('class.pog_base.php');
class birthdata extends POG_Base {
    //function __Constructor () {}

    public $birthdataId = '';

    /**
     * @var TINYINT
     */
    public $day;

    /**
     * @var TINYINT
     */
    public $month;

    /**
     * @var INT
     */
    public $year;

    /**
     * @var CHAR(255)
     */
    public $untimed;

    /**
     * @var TINYINT
     */
    public $hour;

    /**
     * @var TINYINT
     */
    public $minute;

    /**
     * @var CHAR(255)
     */
    public $gmt;

    /**
     * @var INT
     */
    public $zoneref;

    /**
     * @var INT
     */
    public $summerref;

    /**
     * @var VARCHAR(255)
     */
    public $place;

    /**
     * @var VARCHAR(255)
     */
    public $state;

    /**
     * @var FLOAT
     */
    public $longitude;

    /**
     * @var FLOAT
     */
    public $latitude;

    /**
     * @var VARCHAR(255)
     */
    public $orderid;

    /**
     * @var VARCHAR(255)
     */
    public $first_name;

    /**
     * @var VARCHAR(255)
     */
    public $last_name;

    /**
     * @var INT
     */
    public $duration;

    /**
     * @var DATE
     */
    public $start_date;

    public $pog_attribute_type = array(
            "birthdataId" => array('db_attributes' => array("NUMERIC", "INT")),
            "day" => array('db_attributes' => array("NUMERIC", "TINYINT")),
            "month" => array('db_attributes' => array("NUMERIC", "TINYINT")),
            "year" => array('db_attributes' => array("NUMERIC", "INT")),
            "untimed" => array('db_attributes' => array("TEXT", "CHAR", "255")),
            "hour" => array('db_attributes' => array("NUMERIC", "TINYINT")),
            "minute" => array('db_attributes' => array("NUMERIC", "TINYINT")),
            "gmt" => array('db_attributes' => array("TEXT", "CHAR", "255")),
            "zoneref" => array('db_attributes' => array("NUMERIC", "INT")),
            "summerref" => array('db_attributes' => array("NUMERIC", "INT")),
            "place" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
            "state" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
            "longitude" => array('db_attributes' => array("NUMERIC", "FLOAT")),
            "latitude" => array('db_attributes' => array("NUMERIC", "FLOAT")),
            "orderid" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
            "first_name" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
            "last_name" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
            "duration" => array('db_attributes' => array("NUMERIC", "INT")),
            "start_date" => array('db_attributes' => array("NUMERIC", "DATE")),
    );
    public $pog_query;


    /**
     * Getter for some private attributes
     * @return mixed $attribute
     */
    public function __get($attribute) {
        if (isset($this->{"_".$attribute})) {
            return $this->{"_".$attribute};
        }
        else {
            return false;
        }
    }

    /*function birthdata ($day='', $month='', $year='', $untimed='', $hour='', $minute='', $gmt='', $zoneref='', $summerref='', $place='', $state='', $longitude='', $latitude='', $orderid='', $first_name='', $last_name='', $duration='', $start_date='') {*/
    function __Constructor ($day='', $month='', $year='', $untimed='', $hour='', $minute='', $gmt='', $zoneref='', $summerref='', $place='', $state='', $longitude='', $latitude='', $orderid='', $first_name='', $last_name='', $duration='', $start_date='') {
        $this->day = $day;
        $this->month = $month;
        $this->year = $year;
        $this->untimed = $untimed;
        $this->hour = $hour;
        $this->minute = $minute;
        $this->gmt = $gmt;
        $this->zoneref = $zoneref;
        $this->summerref = $summerref;
        $this->place = $place;
        $this->state = $state;
        $this->longitude = $longitude;
        $this->latitude = $latitude;
        $this->orderid = $orderid;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->duration = $duration;
        $this->start_date = $start_date;
    }


    /**
     * Gets object from database
     * @param integer $birthdataId
     * @return object $birthdata
     */
    function Get($birthdataId) {
        $connection = Database::Connect();
        $this->pog_query = "select * from `birthdata` where `birthdataid`='".intval($birthdataId)."' LIMIT 1";
		
		$cursor = Database::Reader($this->pog_query, $connection);
        while ($row = Database::Read($cursor)) {
            $this->birthdataId = $row['birthdataid'];
            $this->day = $this->Unescape($row['day']);
            $this->month = $this->Unescape($row['month']);
            $this->year = $this->Unescape($row['year']);
            $this->untimed = $this->Unescape($row['untimed']);
            $this->hour = $this->Unescape($row['hour']);
            $this->minute = $this->Unescape($row['minute']);
            $this->gmt = $this->Unescape($row['gmt']);
            $this->zoneref = $this->Unescape($row['zoneref']);
            $this->summerref = $this->Unescape($row['summerref']);
            $this->place = $this->Unescape($row['place']);
            $this->state = $this->Unescape($row['state']);
            $this->longitude = $this->Unescape($row['longitude']);
            $this->latitude = $this->Unescape($row['latitude']);
            $this->orderid = $this->Unescape($row['orderid']);
            $this->first_name = $this->Unescape($row['first_name']);
            $this->last_name = $this->Unescape($row['last_name']);
            $this->duration = $this->Unescape($row['duration']);
            $this->start_date = $row['start_date'];
        }
        return $this;
    }


    /**
     * Returns a sorted array of objects that match given conditions
     * @param multidimensional array {("field", "comparator", "value"), ("field", "comparator", "value"), ...}
     * @param string $sortBy
     * @param boolean $ascending
     * @param int limit
     * @return array $birthdataList
     */
    function GetList($fcv_array = array(), $sortBy='', $ascending=true, $limit='') {
        $connection = Database::Connect();
        $sqlLimit = ($limit != '' ? "LIMIT $limit" : '');
        $this->pog_query = "select * from `birthdata` ";
        $birthdataList = Array();
        if (sizeof($fcv_array) > 0) {
            $this->pog_query .= " where ";
            for ($i=0, $c=sizeof($fcv_array); $i<$c; $i++) {
                if (sizeof($fcv_array[$i]) == 1) {
                    $this->pog_query .= " ".$fcv_array[$i][0]." ";
                    continue;
                }
                else {
                    if ($i > 0 && sizeof($fcv_array[$i-1]) != 1) {
                        $this->pog_query .= " AND ";
                    }
                    if (isset($this->pog_attribute_type[$fcv_array[$i][0]]['db_attributes']) && $this->pog_attribute_type[$fcv_array[$i][0]]['db_attributes'][0] != 'NUMERIC' && $this->pog_attribute_type[$fcv_array[$i][0]]['db_attributes'][0] != 'SET') {
                        if ($GLOBALS['configuration']['db_encoding'] == 1) {
                            $value = POG_Base::IsColumn($fcv_array[$i][2]) ? "BASE64_DECODE(".$fcv_array[$i][2].")" : "'".$fcv_array[$i][2]."'";
                            $this->pog_query .= "BASE64_DECODE(`".$fcv_array[$i][0]."`) ".$fcv_array[$i][1]." ".$value;
                        }
                        else {
                            $value =  POG_Base::IsColumn($fcv_array[$i][2]) ? $fcv_array[$i][2] : "'".$this->Escape($fcv_array[$i][2])."'";
                            $this->pog_query .= "`".$fcv_array[$i][0]."` ".$fcv_array[$i][1]." ".$value;
                        }
                    }
                    else {
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
                }
                else {
                    $sortBy = "$sortBy ";
                }
            }
            else {
                $sortBy = "$sortBy ";
            }
        }
        else {
            $sortBy = "birthdataid";
        }
        $this->pog_query .= " order by ".$sortBy." ".($ascending ? "asc" : "desc")." $sqlLimit";
        $thisObjectName = get_class($this);
        $cursor = Database::Reader($this->pog_query, $connection);
        while ($row = Database::Read($cursor)) {
            $birthdata = new $thisObjectName();
            $birthdata->birthdataId = $row['birthdataid'];
            $birthdata->day = $this->Unescape($row['day']);
            $birthdata->month = $this->Unescape($row['month']);
            $birthdata->year = $this->Unescape($row['year']);
            $birthdata->untimed = $this->Unescape($row['untimed']);
            $birthdata->hour = $this->Unescape($row['hour']);
            $birthdata->minute = $this->Unescape($row['minute']);
            $birthdata->gmt = $this->Unescape($row['gmt']);
            $birthdata->zoneref = $this->Unescape($row['zoneref']);
            $birthdata->summerref = $this->Unescape($row['summerref']);
            $birthdata->place = $this->Unescape($row['place']);
            $birthdata->state = $this->Unescape($row['state']);
            $birthdata->longitude = $this->Unescape($row['longitude']);
            $birthdata->latitude = $this->Unescape($row['latitude']);
            $birthdata->orderid = $this->Unescape($row['orderid']);
            $birthdata->first_name = $this->Unescape($row['first_name']);
            $birthdata->last_name = $this->Unescape($row['last_name']);
            $birthdata->duration = $this->Unescape($row['duration']);
            $birthdata->start_date = $row['start_date'];
            $birthdataList[] = $birthdata;
        }
        return $birthdataList;
    }


    /**
     * Saves the object to the database
     * @return integer $birthdataId
     */
    function Save() {
        $connection = Database::Connect();
        $this->pog_query = "select `birthdataid` from `birthdata` where `birthdataid`='".$this->birthdataId."' LIMIT 1";
        $rows = Database::Query($this->pog_query, $connection);
        if ($rows > 0) {
            $this->pog_query = "update `birthdata` set
			`day`='".$this->Escape($this->day)."',
			`month`='".$this->Escape($this->month)."',
			`year`='".$this->Escape($this->year)."',
			`untimed`='".$this->Escape($this->untimed)."',
			`hour`='".$this->Escape($this->hour)."',
			`minute`='".$this->Escape($this->minute)."',
			`gmt`='".$this->Escape($this->gmt)."',
			`zoneref`='".$this->Escape($this->zoneref)."',
			`summerref`='".$this->Escape($this->summerref)."',
			`place`='".$this->Escape($this->place)."',
			`state`='".$this->Escape($this->state)."',
			`longitude`='".$this->Escape($this->longitude)."',
			`latitude`='".$this->Escape($this->latitude)."',
			`orderid`='".$this->Escape($this->orderid)."',
			`first_name`='".$this->Escape($this->first_name)."',
			`last_name`='".$this->Escape($this->last_name)."',
			`duration`='".$this->Escape($this->duration)."',
			`start_date`='".$this->start_date."' where `birthdataid`='".$this->birthdataId."'";
        }
        else {
            $this->pog_query = "insert into `birthdata` (`day`, `month`, `year`, `untimed`, `hour`, `minute`, `gmt`, `zoneref`, `summerref`, `place`, `state`, `longitude`, `latitude`, `orderid`, `first_name`, `last_name`, `duration`, `start_date` ) values (
			'".$this->Escape($this->day)."',
			'".$this->Escape($this->month)."',
			'".$this->Escape($this->year)."',
			'".$this->Escape($this->untimed)."',
			'".$this->Escape($this->hour)."',
			'".$this->Escape($this->minute)."',
			'".$this->Escape($this->gmt)."',
			'".$this->Escape($this->zoneref)."',
			'".$this->Escape($this->summerref)."',
			'".$this->Escape($this->place)."',
			'".$this->Escape($this->state)."',
			'".$this->Escape($this->longitude)."',
			'".$this->Escape($this->latitude)."',
			'".$this->Escape($this->orderid)."',
			'".$this->Escape($this->first_name)."',
			'".$this->Escape($this->last_name)."',
			'".$this->Escape($this->duration)."',
			'".$this->start_date."' )";
        }
        $insertId = Database::InsertOrUpdate($this->pog_query, $connection);
        if ($this->birthdataId == "") {
            $this->birthdataId = $insertId;
        }
        return $this->birthdataId;
    }


    /**
     * Clones the object and saves it to the database
     * @return integer $birthdataId
     */
    function SaveNew() {
        $this->birthdataId = '';
        return $this->Save();
    }


    /**
     * Deletes the object from the database
     * @return boolean
     */
    function Delete() {
        $connection = Database::Connect();
        $this->pog_query = "delete from `birthdata` where `birthdataid`='".$this->birthdataId."'";
        return Database::NonQuery($this->pog_query, $connection);
    }


    /**
     * Deletes a list of objects that match given conditions
     * @param multidimensional array {("field", "comparator", "value"), ("field", "comparator", "value"), ...}
     * @param bool $deep
     * @return
     */
    function DeleteList($fcv_array) {
        if (sizeof($fcv_array) > 0) {
            $connection = Database::Connect();
            $pog_query = "delete from `birthdata` where ";
            for ($i=0, $c=sizeof($fcv_array); $i<$c; $i++) {
                if (sizeof($fcv_array[$i]) == 1) {
                    $pog_query .= " ".$fcv_array[$i][0]." ";
                    continue;
                }
                else {
                    if ($i > 0 && sizeof($fcv_array[$i-1]) !== 1) {
                        $pog_query .= " AND ";
                    }
                    if (isset($this->pog_attribute_type[$fcv_array[$i][0]]['db_attributes']) && $this->pog_attribute_type[$fcv_array[$i][0]]['db_attributes'][0] != 'NUMERIC' && $this->pog_attribute_type[$fcv_array[$i][0]]['db_attributes'][0] != 'SET') {
                        $pog_query .= "`".$fcv_array[$i][0]."` ".$fcv_array[$i][1]." '".$this->Escape($fcv_array[$i][2])."'";
                    }
                    else {
                        $pog_query .= "`".$fcv_array[$i][0]."` ".$fcv_array[$i][1]." '".$fcv_array[$i][2]."'";
                    }
                }
            }
            return Database::NonQuery($pog_query, $connection);
        }
    }
}
?>