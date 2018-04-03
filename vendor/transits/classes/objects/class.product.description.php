<?php
/*
	This SQL query will create the table to store your object.

	CREATE TABLE `product_description` (
	`product_description_id` int(11) NOT NULL auto_increment,
	`product_id` INT NOT NULL,
	`language_id` INT NOT NULL,
	`productName` VARCHAR(255) NOT NULL,
	`short_description` VARCHAR(255) NOT NULL,
	`description` VARCHAR(255) NOT NULL,
	`metaDescription` VARCHAR(255) NOT NULL,
	`metaKeywords` VARCHAR(255) NOT NULL, PRIMARY KEY  (`product_description_id`)) ENGINE=MyISAM;
*/

/**
 * <b>product_description</b> class with integrated CRUD methods.
 * @author Php Object Generator
 * @version POG 3.0f / PHP5
 * @copyright Free for personal & commercial use. (Offered under the BSD license)
 * @link http://www.phpobjectgenerator.com/?language=php5&wrapper=pog&objectName=product_description&attributeList=array+%28%0A++0+%3D%3E+%27product_id%27%2C%0A++1+%3D%3E+%27language_id%27%2C%0A++2+%3D%3E+%27productName%27%2C%0A++3+%3D%3E+%27short_description%27%2C%0A++4+%3D%3E+%27description%27%2C%0A++5+%3D%3E+%27metaDescription%27%2C%0A++6+%3D%3E+%27metaKeywords%27%2C%0A%29&typeList=array+%28%0A++0+%3D%3E+%27INT%27%2C%0A++1+%3D%3E+%27INT%27%2C%0A++2+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++3+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++4+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++5+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++6+%3D%3E+%27VARCHAR%28255%29%27%2C%0A%29
 */
include_once('class.pog_base.php');
class product_description extends POG_Base {
    public $product_description_id = '';

    /**
     * @var INT
     */
    public $product_id;

    /**
     * @var INT
     */
    public $language_id;

    /**
     * @var VARCHAR(255)
     */
    public $productName;

    /**
     * @var VARCHAR(255)
     */
    public $short_description;

    /**
     * @var VARCHAR(255)
     */
    public $description;

    /**
     * @var VARCHAR(255)
     */
    public $metaDescription;

    /**
     * @var VARCHAR(255)
     */
    public $metaKeywords;

    public $pog_attribute_type = array(
            "product_description_id" => array('db_attributes' => array("NUMERIC", "INT")),
            "product_id" => array('db_attributes' => array("NUMERIC", "INT")),
            "language_id" => array('db_attributes' => array("NUMERIC", "INT")),
            "productName" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
            "short_description" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
            "description" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
            "metaDescription" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
            "metaKeywords" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
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

    function product_description($product_id='', $language_id='', $productName='', $short_description='', $description='', $metaDescription='', $metaKeywords='') {
        $this->product_id = $product_id;
        $this->language_id = $language_id;
        $this->productName = $productName;
        $this->short_description = $short_description;
        $this->description = $description;
        $this->metaDescription = $metaDescription;
        $this->metaKeywords = $metaKeywords;
    }


    /**
     * Gets object from database
     * @param integer $product_description_id
     * @return object $product_description
     */
    function Get($product_description_id) {
        $connection = Database::Connect();
        $this->pog_query = "select * from `product_description` where `product_description_id`='".intval($product_description_id)."' LIMIT 1";
        $cursor = Database::Reader($this->pog_query, $connection);
        while ($row = Database::Read($cursor)) {
            $this->product_description_id = $row['product_description_id'];
            $this->product_id = $this->Unescape($row['product_id']);
            $this->language_id = $this->Unescape($row['language_id']);
            $this->productName = $this->Unescape($row['productName']);
            $this->short_description = $this->Unescape($row['short_description']);
            $this->description = $this->Unescape($row['description']);
            $this->metaDescription = $this->Unescape($row['metaDescription']);
            $this->metaKeywords = $this->Unescape($row['metaKeywords']);
        }
        return $this;
    }


    /**
     * Returns a sorted array of objects that match given conditions
     * @param multidimensional array {("field", "comparator", "value"), ("field", "comparator", "value"), ...}
     * @param string $sortBy
     * @param boolean $ascending
     * @param int limit
     * @return array $product_descriptionList
     */
    function GetList($fcv_array = array(), $sortBy='', $ascending=true, $limit='') {
        $connection = Database::Connect();
        $sqlLimit = ($limit != '' ? "LIMIT $limit" : '');
        $this->pog_query = "select * from `product_description` ";
        $product_descriptionList = Array();
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
            $sortBy = "product_description_id";
        }
        $this->pog_query .= " order by ".$sortBy." ".($ascending ? "asc" : "desc")." $sqlLimit";
        $thisObjectName = get_class($this);
        $cursor = Database::Reader($this->pog_query, $connection);
        while ($row = Database::Read($cursor)) {
            $product_description = new $thisObjectName();
            $product_description->product_description_id = $row['product_description_id'];
            $product_description->product_id = $this->Unescape($row['product_id']);
            $product_description->language_id = $this->Unescape($row['language_id']);
            $product_description->productName = $this->Unescape($row['productName']);
            $product_description->short_description = $this->Unescape($row['short_description']);
            $product_description->description = $this->Unescape($row['description']);
            $product_description->metaDescription = $this->Unescape($row['metaDescription']);
            $product_description->metaKeywords = $this->Unescape($row['metaKeywords']);
            $product_descriptionList[] = $product_description;
        }
        return $product_descriptionList;
    }


    /**
     * Saves the object to the database
     * @return integer $product_description_id
     */
    function Save() {
        $connection = Database::Connect();
        $this->pog_query = "select `product_description_id` from `product_description` where `product_description_id`='".$this->product_description_id."' LIMIT 1";
        $rows = Database::Query($this->pog_query, $connection);
        if ($rows > 0) {
            $this->pog_query = "update `product_description` set
			`product_id`='".$this->Escape($this->product_id)."',
			`language_id`='".$this->Escape($this->language_id)."',
			`productName`='".$this->Escape($this->productName)."',
			`short_description`='".$this->Escape($this->short_description)."',
			`description`='".$this->Escape($this->description)."',
			`metaDescription`='".$this->Escape($this->metaDescription)."',
			`metaKeywords`='".$this->Escape($this->metaKeywords)."' where `product_description_id`='".$this->product_description_id."'";
        }
        else {
            $this->pog_query = "insert into `product_description` (`product_id`, `language_id`, `productName`, `short_description`, `description`, `metaDescription`, `metaKeywords` ) values (
			'".$this->Escape($this->product_id)."',
			'".$this->Escape($this->language_id)."',
			'".$this->Escape($this->productName)."',
			'".$this->Escape($this->short_description)."',
			'".$this->Escape($this->description)."',
			'".$this->Escape($this->metaDescription)."',
			'".$this->Escape($this->metaKeywords)."' )";
        }
        $insertId = Database::InsertOrUpdate($this->pog_query, $connection);
        if ($this->product_description_id == "") {
            $this->product_description_id = $insertId;
        }
        return $this->product_description_id;
    }


    /**
     * Clones the object and saves it to the database
     * @return integer $product_description_id
     */
    function SaveNew() {
        $this->product_description_id = '';
        return $this->Save();
    }


    /**
     * Deletes the object from the database
     * @return boolean
     */
    function Delete() {
        $connection = Database::Connect();
        $this->pog_query = "delete from `product_description` where `product_description_id`='".$this->product_description_id."'";
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
            $pog_query = "delete from `product_description` where ";
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
     * Gets object from database
     * @param integer $product_id
     * @return object $product_description
     */
    function GetProductById($product_id) {
        $connection = Database::Connect();
        $this->pog_query = "select * from `product_description` where `product_id`='".intval($product_id)."' LIMIT 1";
        $cursor = Database::Reader($this->pog_query, $connection);
        while ($row = Database::Read($cursor)) {
            $this->product_description_id = $row['product_description_id'];
            $this->product_id = $this->Unescape($row['product_id']);
            $this->language_id = $this->Unescape($row['language_id']);
            $this->productName = $this->Unescape($row['productName']);
            $this->short_description = $this->Unescape($row['short_description']);
            $this->description = $this->Unescape($row['description']);
            $this->metaDescription = $this->Unescape($row['metaDescription']);
            $this->metaKeywords = $this->Unescape($row['metaKeywords']);
        }
        return $this;
    }

}
?>