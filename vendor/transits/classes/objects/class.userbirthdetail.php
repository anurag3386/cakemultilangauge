<?php
/*
	This SQL query will create the table to store your object.

	CREATE TABLE `userbirthdetail` (
	`UserBirthDetailId` int(11) NOT NULL auto_increment,
	`UserId` TINYINT NOT NULL,
	`Day` INT NOT NULL,
	`Month` INT NOT NULL,
	`Year` INT NOT NULL,
	`Hours` INT NOT NULL,
	`Minutes` INT NOT NULL,
	`Seconds` INT NOT NULL,
	`unTimed` CHAR(255) NOT NULL,
	`GMT` VARCHAR(255) NOT NULL,
	`ZoneRef` DECIMAL NOT NULL,
	`SummerTimeZoneRef` DECIMAL NOT NULL,
	`Longitute` DOUBLE NOT NULL,
	`Lagitute` DOUBLE NOT NULL,
	`CreatedDate` DATETIME NOT NULL,
	`ModifiedDate` DATETIME NOT NULL,
	`CreatedBy` INT NOT NULL,
	`ModifiedBy` INT NOT NULL,
	`country` VARCHAR(255) NOT NULL,
	`state` VARCHAR(255) NOT NULL,
	`city` VARCHAR(255) NOT NULL,
	`country_name` VARCHAR(255) NOT NULL,
	`sunsign` INT NOT NULL, PRIMARY KEY  (`UserBirthDetailId`)) ENGINE=MyISAM;
*/

/**
 * <b>userbirthdetail</b> class with integrated CRUD methods.
 * @author Php Object Generator
 * @version POG 3.0f / PHP5
 * @copyright Free for personal & commercial use. (Offered under the BSD license)
 * @link http://www.phpobjectgenerator.com/?language=php5&wrapper=pog&objectName=userbirthdetail&attributeList=array+%28%0A++0+%3D%3E+%27UserId%27%2C%0A++1+%3D%3E+%27Day%27%2C%0A++2+%3D%3E+%27Month%27%2C%0A++3+%3D%3E+%27Year%27%2C%0A++4+%3D%3E+%27Hours%27%2C%0A++5+%3D%3E+%27Minutes%27%2C%0A++6+%3D%3E+%27Seconds%27%2C%0A++7+%3D%3E+%27nTimed%27%2C%0A++8+%3D%3E+%27GMT%27%2C%0A++9+%3D%3E+%27ZoneRef%27%2C%0A++10+%3D%3E+%27SummerTimeZoneRef%27%2C%0A++11+%3D%3E+%27Longitute%27%2C%0A++12+%3D%3E+%27Lagitute%27%2C%0A++13+%3D%3E+%27CreatedDate%27%2C%0A++14+%3D%3E+%27ModifiedDate%27%2C%0A++15+%3D%3E+%27CreatedBy%27%2C%0A++16+%3D%3E+%27ModifiedBy%27%2C%0A++17+%3D%3E+%27country%27%2C%0A++18+%3D%3E+%27state%27%2C%0A++19+%3D%3E+%27city%27%2C%0A++20+%3D%3E+%27country_name%27%2C%0A++21+%3D%3E+%27sunsign%27%2C%0A%29&typeList=array+%28%0A++0+%3D%3E+%27TINYINT%27%2C%0A++1+%3D%3E+%27INT%27%2C%0A++2+%3D%3E+%27INT%27%2C%0A++3+%3D%3E+%27INT%27%2C%0A++4+%3D%3E+%27INT%27%2C%0A++5+%3D%3E+%27INT%27%2C%0A++6+%3D%3E+%27INT%27%2C%0A++7+%3D%3E+%27CHAR%28255%29%27%2C%0A++8+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++9+%3D%3E+%27DECIMAL%27%2C%0A++10+%3D%3E+%27DECIMAL%27%2C%0A++11+%3D%3E+%27DOUBLE%27%2C%0A++12+%3D%3E+%27DOUBLE%27%2C%0A++13+%3D%3E+%27DATETIME%27%2C%0A++14+%3D%3E+%27DATETIME%27%2C%0A++15+%3D%3E+%27INT%27%2C%0A++16+%3D%3E+%27INT%27%2C%0A++17+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++18+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++19+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++20+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++21+%3D%3E+%27INT%27%2C%0A%29
 */
include_once('class.pog_base.php');
class userbirthdetail extends POG_Base {
    public $UserBirthDetailId = '';

    /**
     * @var TINYINT
     */
    public $UserId;

    /**
     * @var INT
     */
    public $Day;

    /**
     * @var INT
     */
    public $Month;

    /**
     * @var INT
     */
    public $Year;

    /**
     * @var INT
     */
    public $Hours;

    /**
     * @var INT
     */
    public $Minutes;

    /**
     * @var INT
     */
    public $Seconds;

    /**
     * @var CHAR(255)
     */
    public $unTimed;

    /**
     * @var VARCHAR(255)
     */
    public $GMT;

    /**
     * @var DECIMAL
     */
    public $ZoneRef;

    /**
     * @var DECIMAL
     */
    public $SummerTimeZoneRef;

    /**
     * @var DOUBLE
     */
    public $Longitute;

    /**
     * @var DOUBLE
     */
    public $Lagitute;

    /**
     * @var DATETIME
     */
    public $CreatedDate;

    /**
     * @var DATETIME
     */
    public $ModifiedDate;

    /**
     * @var INT
     */
    public $CreatedBy;

    /**
     * @var INT
     */
    public $ModifiedBy;

    /**
     * @var VARCHAR(255)
     */
    public $country;

    /**
     * @var VARCHAR(255)
     */
    public $state;

    /**
     * @var VARCHAR(255)
     */
    public $city;

    /**
     * @var VARCHAR(255)
     */
    public $country_name;

    /**
     * @var INT
     */
    public $sunsign;

    public $pog_attribute_type = array(
            "UserBirthDetailId" => array('db_attributes' => array("NUMERIC", "INT")),
            "UserId" => array('db_attributes' => array("NUMERIC", "TINYINT")),
            "Day" => array('db_attributes' => array("NUMERIC", "INT")),
            "Month" => array('db_attributes' => array("NUMERIC", "INT")),
            "Year" => array('db_attributes' => array("NUMERIC", "INT")),
            "Hours" => array('db_attributes' => array("NUMERIC", "INT")),
            "Minutes" => array('db_attributes' => array("NUMERIC", "INT")),
            "Seconds" => array('db_attributes' => array("NUMERIC", "INT")),
            "unTimed" => array('db_attributes' => array("TEXT", "CHAR", "255")),
            "GMT" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
            "ZoneRef" => array('db_attributes' => array("NUMERIC", "DECIMAL")),
            "SummerTimeZoneRef" => array('db_attributes' => array("NUMERIC", "DECIMAL")),
            "Longitute" => array('db_attributes' => array("NUMERIC", "DOUBLE")),
            "Lagitute" => array('db_attributes' => array("NUMERIC", "DOUBLE")),
            "CreatedDate" => array('db_attributes' => array("TEXT", "DATETIME")),
            "ModifiedDate" => array('db_attributes' => array("TEXT", "DATETIME")),
            "CreatedBy" => array('db_attributes' => array("NUMERIC", "INT")),
            "ModifiedBy" => array('db_attributes' => array("NUMERIC", "INT")),
            "country" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
            "state" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
            "city" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
            "country_name" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
            "sunsign" => array('db_attributes' => array("NUMERIC", "INT")),
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

    function userbirthdetail($UserId='', $Day='', $Month='', $Year='', $Hours='', $Minutes='', $Seconds='', $unTimed='', $GMT='', $ZoneRef='', $SummerTimeZoneRef='', $Longitute='', $Lagitute='', $CreatedDate='', $ModifiedDate='', $CreatedBy='', $ModifiedBy='', $country='', $state='', $city='', $country_name='', $sunsign='') {
        $this->UserId = $UserId;
        $this->Day = $Day;
        $this->Month = $Month;
        $this->Year = $Year;
        $this->Hours = $Hours;
        $this->Minutes = $Minutes;
        $this->Seconds = $Seconds;
        $this->unTimed = $unTimed;
        $this->GMT = $GMT;
        $this->ZoneRef = $ZoneRef;
        $this->SummerTimeZoneRef = $SummerTimeZoneRef;
        $this->Longitute = $Longitute;
        $this->Lagitute = $Lagitute;
        $this->CreatedDate = $CreatedDate;
        $this->ModifiedDate = $ModifiedDate;
        $this->CreatedBy = $CreatedBy;
        $this->ModifiedBy = $ModifiedBy;
        $this->country = $country;
        $this->state = $state;
        $this->city = $city;
        $this->country_name = $country_name;
        $this->sunsign = $sunsign;
    }


    /**
     * Gets object from database
     * @param integer $UserBirthDetailId
     * @return object $userbirthdetail
     */
    function Get($UserBirthDetailId) {
        $connection = Database::Connect();
        $this->pog_query = "select * from `userbirthdetail` where `UserBirthDetailId`='".intval($UserBirthDetailId)."' LIMIT 1";
        $cursor = Database::Reader($this->pog_query, $connection);
        while ($row = Database::Read($cursor)) {
            $this->UserBirthDetailId = $row['UserBirthDetailId'];
            $this->UserId = $this->Unescape($row['UserId']);
            $this->Day = $this->Unescape($row['Day']);
            $this->Month = $this->Unescape($row['Month']);
            $this->Year = $this->Unescape($row['Year']);
            $this->Hours = $this->Unescape($row['Hours']);
            $this->Minutes = $this->Unescape($row['Minutes']);
            $this->Seconds = $this->Unescape($row['Seconds']);
            $this->unTimed = $this->Unescape($row['unTimed']);
            $this->GMT = $this->Unescape($row['GMT']);
            $this->ZoneRef = $this->Unescape($row['ZoneRef']);
            $this->SummerTimeZoneRef = $this->Unescape($row['SummerTimeZoneRef']);
            $this->Longitute = $this->Unescape($row['Longitute']);
            $this->Lagitute = $this->Unescape($row['Lagitute']);
            $this->CreatedDate = $row['CreatedDate'];
            $this->ModifiedDate = $row['ModifiedDate'];
            $this->CreatedBy = $this->Unescape($row['CreatedBy']);
            $this->ModifiedBy = $this->Unescape($row['ModifiedBy']);
            $this->country = $this->Unescape($row['country']);
            $this->state = $this->Unescape($row['state']);
            $this->city = $this->Unescape($row['city']);
            $this->country_name = $this->Unescape($row['country_name']);
            $this->sunsign = $this->Unescape($row['sunsign']);
        }
        return $this;
    }


    /**
     * Returns a sorted array of objects that match given conditions
     * @param multidimensional array {("field", "comparator", "value"), ("field", "comparator", "value"), ...}
     * @param string $sortBy
     * @param boolean $ascending
     * @param int limit
     * @return array $userbirthdetailList
     */
    function GetList($fcv_array = array(), $sortBy='', $ascending=true, $limit='') {
        $connection = Database::Connect();
        $sqlLimit = ($limit != '' ? "LIMIT $limit" : '');
        $this->pog_query = "select birth_details.*, profiles.user_id as profile_user_id, profiles.city_id, cities.id as city, cities.city as cityname, cities.county as state, cities.country, cities.latitude, cities.longitude, cities.typetable, cities.zonetable
            FROM `birth_details`
            JOIN profiles ON birth_details.user_id=profiles.user_id
            JOIN cities ON cities.id = birth_details.city_id";
        $userbirthdetailList = Array();
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
            $sortBy = "birth_details.id";
        }
        echo $this->pog_query .= " order by ".$sortBy." ".($ascending ? "asc" : "desc")." $sqlLimit"; die;
        $thisObjectName = get_class($this);
        $cursor = Database::Reader($this->pog_query, $connection);
        while ($row = Database::Read($cursor)) {
            //pr($row);
            $dob = explode('-', $row['date']);
            $day = $dob[2];
            $month = $dob[1];
            $year = $dob[0];
            $dob_time = explode(':', $row['time']);
            $hours = $dob_time[0];
            $minutes = $dob_time[1];
            $userbirthdetail = new $thisObjectName();
            $userbirthdetail->UserBirthDetailId = $row['id'];
            $userbirthdetail->UserId = $this->Unescape($row['user_id']);
            $userbirthdetail->Day = $this->Unescape($day);
            $userbirthdetail->Month = $this->Unescape($month);
            $userbirthdetail->Year = $this->Unescape($year);
            $userbirthdetail->Hours = $this->Unescape($hours);
            $userbirthdetail->Minutes = $this->Unescape($minutes);
            $userbirthdetail->Seconds = 00; //$this->Unescape($row['Seconds']);
            $userbirthdetail->unTimed = 0; //$this->Unescape($row['unTimed']);
            $userbirthdetail->GMT = ''; //$this->Unescape($row['GMT']);
            $userbirthdetail->ZoneRef = $this->Unescape($row['zone']);
            $userbirthdetail->SummerTimeZoneRef = $this->Unescape($row['type']);
            $userbirthdetail->Longitute = $this->Unescape($row['longitude']);
            $userbirthdetail->Lagitute = $this->Unescape($row['latitude']);
            $userbirthdetail->typetable = $this->Unescape($row['typetable']);
            $userbirthdetail->zonetable = $this->Unescape($row['zonetable']);
            $userbirthdetail->CreatedDate = $row['created'];
            $userbirthdetail->ModifiedDate = $row['modified'];
            //$userbirthdetail->CreatedBy = $this->Unescape($row['CreatedBy']);
            //$userbirthdetail->ModifiedBy = $this->Unescape($row['ModifiedBy']);
            $userbirthdetail->country = $this->Unescape($row['country']);
            $userbirthdetail->state = $this->Unescape($row['state']);
            $userbirthdetail->city = $this->Unescape($row['city']);
            //$userbirthdetail->cityname = $this->Unescape($row['cityname']);
            $userbirthdetail->country_name = $this->Unescape($row['country']); //$this->Unescape($row['country_name']);
            $userbirthdetail->sunsign = $this->Unescape($row['sun_sign_id']);
            //pr ($userbirthdetail); die();
            $userbirthdetailList[] = $userbirthdetail;
        }
        return $userbirthdetailList;
    }


    function GetListBasedOnSelectedUser ($userType = 'user', $fcv_array = array(), $sortBy='', $ascending=true, $limit='') {
        $connection = Database::Connect();
        $sqlLimit = ($limit != '' ? "LIMIT $limit" : '');
        if (strtolower($userType) == 'anotherperson') {
            $this->pog_query = "select another_persons.*, cities.id as city, cities.city as cityname, cities.county as state, cities.country, cities.latitude, cities.longitude, cities.typetable, cities.zonetable
            FROM `another_persons`
            JOIN cities ON cities.id = another_persons.city_id";
        } else {
            $this->pog_query = "select birth_details.*, profiles.user_id as profile_user_id, profiles.city_id, cities.id as city, cities.city as cityname, cities.county as state, cities.country, cities.latitude, cities.longitude, cities.typetable, cities.zonetable
            FROM `birth_details`
            JOIN profiles ON birth_details.user_id=profiles.user_id
            JOIN cities ON cities.id = birth_details.city_id";
        }
        $userbirthdetailList = Array();
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
        } else {
            if (strtolower($userType) == 'anotherperson') {
                $sortBy = "another_persons.id";
            } else {
                $sortBy = "birth_details.id";
            }
        }
        $this->pog_query .= " order by ".$sortBy." ".($ascending ? "asc" : "desc")." $sqlLimit";
        $thisObjectName = get_class($this);
        $cursor = Database::Reader($this->pog_query, $connection);

        while ($row = Database::Read($cursor)) {
            $userbirthdetail = new $thisObjectName();
            
            if (strtolower($userType) == 'anotherperson') {
                $dob = explode('-', $row['dob']);
                $userbirthdetail->UserId = $this->Unescape($row['added_by']);
            } else {
                $dob = explode('-', $row['date']);
                //$userbirthdetail->UserBirthDetailId = $row['id'];
                $userbirthdetail->UserId = $this->Unescape($row['user_id']);
                $userbirthdetail->sunsign = $this->Unescape($row['sun_sign_id']);
            }
            $day = $dob[2];
            $month = $dob[1];
            $year = $dob[0];
            $dob_time = explode(':', $row['time']);
            $hours = $dob_time[0];
            $minutes = $dob_time[1];
            
            //$userbirthdetail->UserBirthDetailId = $row['id'];
            //$userbirthdetail->UserId = $this->Unescape($row['user_id']);
            $userbirthdetail->Day = $this->Unescape($day);
            $userbirthdetail->Month = $this->Unescape($month);
            $userbirthdetail->Year = $this->Unescape($year);
            $userbirthdetail->Hours = $this->Unescape($hours);
            $userbirthdetail->Minutes = $this->Unescape($minutes);
            $userbirthdetail->Seconds = 00; //$this->Unescape($row['Seconds']);
            $userbirthdetail->unTimed = 0; //$this->Unescape($row['unTimed']);
            $userbirthdetail->GMT = ''; //$this->Unescape($row['GMT']);
            $userbirthdetail->ZoneRef = $this->Unescape($row['zone']);
            $userbirthdetail->SummerTimeZoneRef = $this->Unescape($row['type']);
            $userbirthdetail->Longitute = $this->Unescape($row['longitude']);
            $userbirthdetail->Lagitute = $this->Unescape($row['latitude']);
            $userbirthdetail->typetable = $this->Unescape($row['typetable']);
            $userbirthdetail->zonetable = $this->Unescape($row['zonetable']);
            $userbirthdetail->CreatedDate = $row['created'];
            $userbirthdetail->ModifiedDate = $row['modified'];
            //$userbirthdetail->CreatedBy = $this->Unescape($row['CreatedBy']);
            //$userbirthdetail->ModifiedBy = $this->Unescape($row['ModifiedBy']);
            $userbirthdetail->country = $this->Unescape($row['country']);
            $userbirthdetail->state = $this->Unescape($row['state']);
            $userbirthdetail->city = $this->Unescape($row['city']);
            //$userbirthdetail->cityname = $this->Unescape($row['cityname']);
            $userbirthdetail->country_name = $this->Unescape($row['country']); //$this->Unescape($row['country_name']);
            //$userbirthdetail->sunsign = $this->Unescape($row['sun_sign_id']);
            //pr ($userbirthdetail); die();
            $userbirthdetailList[] = $userbirthdetail;
        }
        return $userbirthdetailList;
    }


    /**
     * Saves the object to the database
     * @return integer $UserBirthDetailId
     */
    function Save() {
        $connection = Database::Connect();
        $this->pog_query = "select `UserBirthDetailId` from `userbirthdetail` where `UserBirthDetailId`='".$this->UserBirthDetailId."' LIMIT 1";
        $rows = Database::Query($this->pog_query, $connection);
        if ($rows > 0) {
            $this->pog_query = "update `userbirthdetail` set
			`UserId`='".$this->Escape($this->UserId)."',
			`Day`='".$this->Escape($this->Day)."',
			`Month`='".$this->Escape($this->Month)."',
			`Year`='".$this->Escape($this->Year)."',
			`Hours`='".$this->Escape($this->Hours)."',
			`Minutes`='".$this->Escape($this->Minutes)."',
			`Seconds`='".$this->Escape($this->Seconds)."',
			`unTimed`='".$this->Escape($this->unTimed)."',
			`GMT`='".$this->Escape($this->GMT)."',
			`ZoneRef`='".$this->Escape($this->ZoneRef)."',
			`SummerTimeZoneRef`='".$this->Escape($this->SummerTimeZoneRef)."',
			`Longitute`='".$this->Escape($this->Longitute)."',
			`Lagitute`='".$this->Escape($this->Lagitute)."',
			`CreatedDate`='".$this->CreatedDate."',
			`ModifiedDate`='".$this->ModifiedDate."',
			`CreatedBy`='".$this->Escape($this->CreatedBy)."',
			`ModifiedBy`='".$this->Escape($this->ModifiedBy)."',
			`country`='".$this->Escape($this->country)."',
			`state`='".$this->Escape($this->state)."',
			`city`='".$this->Escape($this->city)."',
			`country_name`='".$this->Escape($this->country_name)."',
			`sunsign`='".$this->Escape($this->sunsign)."' where `UserBirthDetailId`='".$this->UserBirthDetailId."'";
        }
        else {
            $this->pog_query = "insert into `userbirthdetail` (`UserId`, `Day`, `Month`, `Year`, `Hours`, `Minutes`, `Seconds`, `unTimed`, `GMT`, `ZoneRef`, `SummerTimeZoneRef`, `Longitute`, `Lagitute`, `CreatedDate`, `ModifiedDate`, `CreatedBy`, `ModifiedBy`, `country`, `state`, `city`, `country_name`, `sunsign` ) values (
			'".$this->Escape($this->UserId)."',
			'".$this->Escape($this->Day)."',
			'".$this->Escape($this->Month)."',
			'".$this->Escape($this->Year)."',
			'".$this->Escape($this->Hours)."',
			'".$this->Escape($this->Minutes)."',
			'".$this->Escape($this->Seconds)."',
			'".$this->Escape($this->unTimed)."',
			'".$this->Escape($this->GMT)."',
			'".$this->Escape($this->ZoneRef)."',
			'".$this->Escape($this->SummerTimeZoneRef)."',
			'".$this->Escape($this->Longitute)."',
			'".$this->Escape($this->Lagitute)."',
			'".$this->CreatedDate."',
			'".$this->ModifiedDate."',
			'".$this->Escape($this->CreatedBy)."',
			'".$this->Escape($this->ModifiedBy)."',
			'".$this->Escape($this->country)."',
			'".$this->Escape($this->state)."',
			'".$this->Escape($this->city)."',
			'".$this->Escape($this->country_name)."',
			'".$this->Escape($this->sunsign)."' )";
        }
        $insertId = Database::InsertOrUpdate($this->pog_query, $connection);
        if ($this->UserBirthDetailId == "") {
            $this->UserBirthDetailId = $insertId;
        }
        return $this->UserBirthDetailId;
    }


    /**
     * Clones the object and saves it to the database
     * @return integer $UserBirthDetailId
     */
    function SaveNew() {
        $this->UserBirthDetailId = '';
        return $this->Save();
    }


    /**
     * Deletes the object from the database
     * @return boolean
     */
    function Delete() {
        $connection = Database::Connect();
        $this->pog_query = "delete from `userbirthdetail` where `UserBirthDetailId`='".$this->UserBirthDetailId."'";
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
            $pog_query = "delete from `userbirthdetail` where ";
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