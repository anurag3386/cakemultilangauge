<?php
/*
   This SQL query will create the table to store your object.

   CREATE TABLE `acsatlas` (
   `acsatlasid` int(11) NOT NULL auto_increment,
   `lkey` VARCHAR(255) NOT NULL,
   `placename` VARCHAR(255) NOT NULL,
   `region` VARCHAR(255) NOT NULL,
   `latitude` INT NOT NULL,
   `longitude` INT NOT NULL,
   `zone` INT NOT NULL,
   `type` INT NOT NULL, PRIMARY KEY  (`acsatlasid`)) ENGINE=MyISAM;
*/

/**
 * <b>acsatlas</b> class with integrated CRUD methods.
 * @author Php Object Generator
 * @version POG 3.0e / PHP5
 * @copyright Free for personal & commercial use. (Offered under the BSD license)
 * @link http://www.phpobjectgenerator.com/?language=php5&wrapper=pog&objectName=acsatlas&attributeList=array+%28%0A++0+%3D%3E+%27lkey%27%2C%0A++1+%3D%3E+%27placename%27%2C%0A++2+%3D%3E+%27region%27%2C%0A++3+%3D%3E+%27latitude%27%2C%0A++4+%3D%3E+%27longitude%27%2C%0A++5+%3D%3E+%27zone%27%2C%0A++6+%3D%3E+%27type%27%2C%0A%29&typeList=array+%28%0A++0+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++1+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++2+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++3+%3D%3E+%27INT%27%2C%0A++4+%3D%3E+%27INT%27%2C%0A++5+%3D%3E+%27INT%27%2C%0A++6+%3D%3E+%27INT%27%2C%0A%29
 */
include_once('class.pog_base.php');
class acsatlas extends POG_Base {
    public $acsatlasId = '';

    /**
     * @var VARCHAR(255)
     */
    public $lkey;

    /**
     * @var VARCHAR(255)
     */
    public $placename;

    /**
     * @var VARCHAR(255)
     */
    public $region;

    /**
     * @var INT
     */
    public $latitude;

    /**
     * @var INT
     */
    public $longitude;

    /**
     * @var INT
     */
    public $zone;

    /**
     * @var INT
     */
    public $type;

    public $pog_attribute_type = array(
            "acsatlasId" => array('db_attributes' => array("NUMERIC", "INT")),
            "lkey" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
            "placename" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
            "region" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
            "latitude" => array('db_attributes' => array("NUMERIC", "INT")),
            "longitude" => array('db_attributes' => array("NUMERIC", "INT")),
            "zone" => array('db_attributes' => array("NUMERIC", "INT")),
            "type" => array('db_attributes' => array("NUMERIC", "INT")),
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

    function acsatlas($lkey='', $placename='', $region='', $latitude='', $longitude='', $zone='', $type='') {
        $this->lkey = $lkey;
        $this->placename = $placename;
        $this->region = $region;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->zone = $zone;
        $this->type = $type;
    }


    /**
     * Gets object from database
     * @param integer $acsatlasId
     * @return object $acsatlas
     */
    function Get($acsatlasId) {
        $connection = Database::Connect();
        $this->pog_query = "select * from `acsatlas` where `acsatlasid`='".intval($acsatlasId)."' LIMIT 1";
        $cursor = Database::Reader($this->pog_query, $connection);
        while ($row = Database::Read($cursor)) {
            $this->acsatlasId = $row['acsatlasid'];
            $this->lkey = $this->Unescape($row['lkey']);
            $this->placename = $this->Unescape($row['placename']);
            $this->region = $this->Unescape($row['region']);
            $this->latitude = $this->Unescape($row['latitude']);
            $this->longitude = $this->Unescape($row['longitude']);
            $this->zone = $this->Unescape($row['zone']);
            $this->type = $this->Unescape($row['type']);
        }
        return $this;
    }


    /**
     * Returns a sorted array of objects that match given conditions
     * @param multidimensional array {("field", "comparator", "value"), ("field", "comparator", "value"), ...}
     * @param string $sortBy
     * @param boolean $ascending
     * @param int limit
     * @return array $acsatlasList
     */
    function GetList($fcv_array = array(), $sortBy='', $ascending=true, $limit='') {
        $connection = Database::Connect();
        $sqlLimit = ($limit != '' ? "LIMIT $limit" : '');
        $this->pog_query = "select * from `cities` ";
        $acsatlasList = Array();
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
            $sortBy = "cities.id";
        }
        $this->pog_query .= " order by ".$sortBy." ".($ascending ? "asc" : "desc")." $sqlLimit";
        $thisObjectName = get_class($this);
        $cursor = Database::Reader($this->pog_query, $connection);
        while ($row = Database::Read($cursor)) {
            $acsatlas = new $thisObjectName();
            $acsatlas->acsatlasId = $row['id'];
            //$acsatlas->lkey = $this->Unescape($row['lkey']);
            $acsatlas->placename = $this->Unescape($row['city']);
            $acsatlas->region = $this->Unescape($row['county']);
            $acsatlas->latitude = $this->Unescape($row['latitude']);
            $acsatlas->longitude = $this->Unescape($row['longitude']);
            $acsatlas->zone = $this->Unescape($row['zonetable']);
            $acsatlas->type = $this->Unescape($row['typetable']);
            $acsatlasList[] = $acsatlas;
        }
        return $acsatlasList;
    }


    /**
     * Saves the object to the database
     * @return integer $acsatlasId
     */
    function Save() {
        $connection = Database::Connect();
        $this->pog_query = "select `acsatlasid` from `acsatlas` where `acsatlasid`='".$this->acsatlasId."' LIMIT 1";
        $rows = Database::Query($this->pog_query, $connection);
        if ($rows > 0) {
            $this->pog_query = "update `acsatlas` set
                                `lkey`='".$this->Escape($this->lkey)."',
                                `placename`='".$this->Escape($this->placename)."',
                                `region`='".$this->Escape($this->region)."',
                                `latitude`='".$this->Escape($this->latitude)."',
                                `longitude`='".$this->Escape($this->longitude)."',
                                `zone`='".$this->Escape($this->zone)."',
                                `type`='".$this->Escape($this->type)."' where `acsatlasid`='".$this->acsatlasId."'";
        }
        else {
            $this->pog_query = "insert into `acsatlas` (`lkey`, `placename`, `region`, `latitude`, `longitude`, `zone`, `type` ) values (
                                '".$this->Escape($this->lkey)."',
                                '".$this->Escape($this->placename)."',
                                '".$this->Escape($this->region)."',
                                '".$this->Escape($this->latitude)."',
                                '".$this->Escape($this->longitude)."', 
                                '".$this->Escape($this->zone)."',
                                '".$this->Escape($this->type)."' )";
        }
        $insertId = Database::InsertOrUpdate($this->pog_query, $connection);
        if ($this->acsatlasId == "") {
            $this->acsatlasId = $insertId;
        }
        return $this->acsatlasId;
    }


    /**
     * Clones the object and saves it to the database
     * @return integer $acsatlasId
     */
    function SaveNew() {
        $this->acsatlasId = '';
        return $this->Save();
    }


    /**
     * Deletes the object from the database
     * @return boolean
     */
    function Delete() {
        $connection = Database::Connect();
        $this->pog_query = "delete from `acsatlas` where `acsatlasid`='".$this->acsatlasId."'";
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
            $pog_query = "delete from `acsatlas` where ";
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

    /**
     * Returns a sorted array of objects that match given conditions
     * @param multidimensional array {("field", "comparator", "value"), ("field", "comparator", "value"), ...}
     * @param string $sortBy
     * @param boolean $ascending
     * @param int limit
     * @return array $acsatlasList
     */
    function ExecuteCustomQuery($WhereCondition, $limit='') {
        $connection = Database::Connect();
        $sqlLimit = ($limit != '' ? "LIMIT $limit" : '');
        $this->pog_query = "SELECT * FROM `acsatlas` ";
        $this->pog_query .= " WHERE " . $WhereCondition;
        $acsatlasList = Array();

        //$this->pog_query .= " order by ".$sortBy." ".($ascending ? "asc" : "desc")." $sqlLimit";
        $this->pog_query .= $sqlLimit;
        
        $thisObjectName = get_class($this);
        $cursor = Database::Reader($this->pog_query, $connection);
        
        while ($row = Database::Read($cursor)) {
            $acsatlas = new $thisObjectName();
            $acsatlas->acsatlasId = $row['acsatlasid'];
            $acsatlas->lkey = $this->Unescape($row['lkey']);
            $acsatlas->placename = $this->Unescape($row['placename']);
            $acsatlas->region = $this->Unescape($row['region']);
            $acsatlas->latitude = $this->Unescape($row['latitude']);
            $acsatlas->longitude = $this->Unescape($row['longitude']);
            $acsatlas->zone = $this->Unescape($row['zone']);
            $acsatlas->type = $this->Unescape($row['type']);
            $acsatlasList[] = $acsatlas;
        }
        return $acsatlasList;
    }
}
?>
