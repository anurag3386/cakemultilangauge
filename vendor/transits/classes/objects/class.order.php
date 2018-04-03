<?php
/*
	This SQL query will create the table to store your object.

	CREATE TABLE `order` (
	`order_id` int(11) NOT NULL auto_increment,
	`product_item_id` VARCHAR(255) NOT NULL,
	`portalid` INT NOT NULL,
	`price` DECIMAL NOT NULL,
	`discount` DECIMAL NOT NULL,
	`user_id` INT NOT NULL,
	`delivery_option` INT NOT NULL,
	`order_status` TINYINT NOT NULL,
	`order_date` DATETIME NOT NULL,
	`confirm_payment_date` DATETIME NOT NULL,
	`product_type` VARCHAR(255) NOT NULL,
	`chk_for_register` TINYINT NOT NULL,
	`currency_code` VARCHAR(255) NOT NULL,
	`shipping_charge` DECIMAL NOT NULL,
	`email_id` VARCHAR(255) NOT NULL,
	`language_code` VARCHAR(255) NOT NULL, PRIMARY KEY  (`order_id`)) ENGINE=MyISAM;
*/

/**
 * <b> order</b> class with integrated CRUD methods.
 * @author Php Object Generator
 * @version POG 3.0f / PHP5
 * @copyright Free for personal & commercial use. (Offered under the BSD license)
 * @link http://www.phpobjectgenerator.com/?language=php5&wrapper=pog&objectName=+order&attributeList=array+%28%0A++0+%3D%3E+%27product_item_id%27%2C%0A++1+%3D%3E+%27portalid%27%2C%0A++2+%3D%3E+%27price%27%2C%0A++3+%3D%3E+%27discount%27%2C%0A++4+%3D%3E+%27user_id%27%2C%0A++5+%3D%3E+%27delivery_option%27%2C%0A++6+%3D%3E+%27order_status%27%2C%0A++7+%3D%3E+%27order_date%27%2C%0A++8+%3D%3E+%27confirm_payment_date%27%2C%0A++9+%3D%3E+%27product_type%27%2C%0A++10+%3D%3E+%27chk_for_register%27%2C%0A++11+%3D%3E+%27currency_code%27%2C%0A++12+%3D%3E+%27shipping_charge%27%2C%0A++13+%3D%3E+%27email_id%27%2C%0A++14+%3D%3E+%27language_code%27%2C%0A%29&typeList=array+%28%0A++0+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++1+%3D%3E+%27INT%27%2C%0A++2+%3D%3E+%27DECIMAL%27%2C%0A++3+%3D%3E+%27DECIMAL%27%2C%0A++4+%3D%3E+%27INT%27%2C%0A++5+%3D%3E+%27INT%27%2C%0A++6+%3D%3E+%27TINYINT%27%2C%0A++7+%3D%3E+%27DATETIME%27%2C%0A++8+%3D%3E+%27DATETIME%27%2C%0A++9+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++10+%3D%3E+%27TINYINT%27%2C%0A++11+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++12+%3D%3E+%27DECIMAL%27%2C%0A++13+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++14+%3D%3E+%27VARCHAR%28255%29%27%2C%0A%29
 */
include_once('class.pog_base.php');
class  order extends POG_Base {
    public $order_id = '';

    /**
     * @var VARCHAR(255)
     */
    public $product_item_id;

    /**
     * @var INT
     */
    public $portalid;

    /**
     * @var DECIMAL
     */
    public $price;

    /**
     * @var DECIMAL
     */
    public $discount;

    /**
     * @var INT
     */
    public $user_id;

    /**
     * @var INT
     */
    public $delivery_option;

    /**
     * @var TINYINT
     */
    public $order_status;

    /**
     * @var DATETIME
     */
    public $order_date;

    /**
     * @var DATETIME
     */
    public $confirm_payment_date;

    /**
     * @var VARCHAR(255)
     */
    public $product_type;

    /**
     * @var TINYINT
     */
    public $chk_for_register;

    /**
     * @var VARCHAR(255)
     */
    public $currency_code;

    /**
     * @var DECIMAL
     */
    public $shipping_charge;

    /**
     * @var VARCHAR(255)
     */
    public $email_id;

    /**
     * @var VARCHAR(255)
     */
    public $language_code;

    public $pog_attribute_type = array(
            "order_id" => array('db_attributes' => array("NUMERIC", "INT")),
            "product_item_id" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
            "portalid" => array('db_attributes' => array("NUMERIC", "INT")),
            "price" => array('db_attributes' => array("NUMERIC", "DECIMAL")),
            "discount" => array('db_attributes' => array("NUMERIC", "DECIMAL")),
            "user_id" => array('db_attributes' => array("NUMERIC", "INT")),
            "delivery_option" => array('db_attributes' => array("NUMERIC", "INT")),
            "order_status" => array('db_attributes' => array("NUMERIC", "TINYINT")),
            "order_date" => array('db_attributes' => array("TEXT", "DATETIME")),
            "confirm_payment_date" => array('db_attributes' => array("TEXT", "DATETIME")),
            "product_type" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
            "chk_for_register" => array('db_attributes' => array("NUMERIC", "TINYINT")),
            "currency_code" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
            "shipping_charge" => array('db_attributes' => array("NUMERIC", "DECIMAL")),
            "email_id" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
            "language_code" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
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

    function  order($product_item_id='', $portalid='', $price='', $discount='', $user_id='', $delivery_option='', $order_status='', $order_date='', $confirm_payment_date='', $product_type='', $chk_for_register='', $currency_code='', $shipping_charge='', $email_id='', $language_code='') {
        $this->product_item_id = $product_item_id;
        $this->portalid = $portalid;
        $this->price = $price;
        $this->discount = $discount;
        $this->user_id = $user_id;
        $this->delivery_option = $delivery_option;
        $this->order_status = $order_status;
        $this->order_date = $order_date;
        $this->confirm_payment_date = $confirm_payment_date;
        $this->product_type = $product_type;
        $this->chk_for_register = $chk_for_register;
        $this->currency_code = $currency_code;
        $this->shipping_charge = $shipping_charge;
        $this->email_id = $email_id;
        $this->language_code = $language_code;
    }


    /**
     * Gets object from database
     * @param integer $order_id
     * @return object $ order
     */
    function Get($order_id) {
        $connection = Database::Connect();
        $this->pog_query = "select * from `order` where `order_id`='".intval($order_id)."' LIMIT 1";
        $cursor = Database::Reader($this->pog_query, $connection);
        while ($row = Database::Read($cursor)) {
            $this->order_id = $row['order_id'];
            $this->product_item_id = $this->Unescape($row['product_item_id']);
            $this->portalid = $this->Unescape($row['portalid']);
            $this->price = $this->Unescape($row['price']);
            $this->discount = $this->Unescape($row['discount']);
            $this->user_id = $this->Unescape($row['user_id']);
            $this->delivery_option = $this->Unescape($row['delivery_option']);
            $this->order_status = $this->Unescape($row['order_status']);
            $this->order_date = $row['order_date'];
            $this->confirm_payment_date = $row['confirm_payment_date'];
            $this->product_type = $this->Unescape($row['product_type']);
            $this->chk_for_register = $this->Unescape($row['chk_for_register']);
            $this->currency_code = $this->Unescape($row['currency_code']);
            $this->shipping_charge = $this->Unescape($row['shipping_charge']);
            $this->email_id = $this->Unescape($row['email_id']);
            $this->language_code = $this->Unescape($row['language_code']);
        }
        return $this;
    }


    /**
     * Returns a sorted array of objects that match given conditions
     * @param multidimensional array {("field", "comparator", "value"), ("field", "comparator", "value"), ...}
     * @param string $sortBy
     * @param boolean $ascending
     * @param int limit
     * @return array $ orderList
     */
    function GetList($fcv_array = array(), $sortBy='', $ascending=true, $limit='') {
        $connection = Database::Connect();
        $sqlLimit = ($limit != '' ? "LIMIT $limit" : '');
        $this->pog_query = "select * from `order` ";
        $orderList = Array();
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
                            if(strtolower( $fcv_array[$i][1] ) == 'in') {
                                $value = POG_Base::IsColumn($fcv_array[$i][2]) ? "BASE64_DECODE(".$fcv_array[$i][2].")" : $fcv_array[$i][2];
                            }
                            else {
                                $value = POG_Base::IsColumn($fcv_array[$i][2]) ? "BASE64_DECODE(".$fcv_array[$i][2].")" : "'".$fcv_array[$i][2]."'";
                            }
                            
                            $this->pog_query .= "BASE64_DECODE(`".$fcv_array[$i][0]."`) ".$fcv_array[$i][1]." ".$value;                            
                        }
                        else {
                            if(strtolower( $fcv_array[$i][1] ) == 'in') {
                                $value =  POG_Base::IsColumn($fcv_array[$i][2]) ? $fcv_array[$i][2] : $this->Escape($fcv_array[$i][2]);
                            }
                            else {
                                $value =  POG_Base::IsColumn($fcv_array[$i][2]) ? $fcv_array[$i][2] : "'".$this->Escape($fcv_array[$i][2])."'";
                            }
                            
                            $this->pog_query .= "`".$fcv_array[$i][0]."` ".$fcv_array[$i][1]." ".$value;
                        }
                    }
                    else {
                        if(strtolower( $fcv_array[$i][1] ) == 'in') {
                            $value = POG_Base::IsColumn($fcv_array[$i][2]) ? $fcv_array[$i][2] : $fcv_array[$i][2];
                        }
                        else {
                            $value = POG_Base::IsColumn($fcv_array[$i][2]) ? $fcv_array[$i][2] : "'".$fcv_array[$i][2]."'";
                        }
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
            $sortBy = "order_id";
        }
        $this->pog_query .= " order by ".$sortBy." ".($ascending ? "asc" : "desc")." $sqlLimit";
        //echo $this->pog_query;
        
        $thisObjectName = get_class($this);
        $cursor = Database::Reader($this->pog_query, $connection);
        while ($row = Database::Read($cursor)) {
            $order = new $thisObjectName();
            $order->order_id = $row['order_id'];
            $order->product_item_id = $this->Unescape($row['product_item_id']);
            $order->portalid = $this->Unescape($row['portalid']);
            $order->price = $this->Unescape($row['price']);
            $order->discount = $this->Unescape($row['discount']);
            $order->user_id = $this->Unescape($row['user_id']);
            $order->delivery_option = $this->Unescape($row['delivery_option']);
            $order->order_status = $this->Unescape($row['order_status']);
            $order->order_date = $row['order_date'];
            $order->confirm_payment_date = $row['confirm_payment_date'];
            $order->product_type = $this->Unescape($row['product_type']);
            $order->chk_for_register = $this->Unescape($row['chk_for_register']);
            $order->currency_code = $this->Unescape($row['currency_code']);
            $order->shipping_charge = $this->Unescape($row['shipping_charge']);
            $order->email_id = $this->Unescape($row['email_id']);
            $order->language_code = $this->Unescape($row['language_code']);            
            $orderList[] = $order;
        }
        return $orderList;
    }


    /**
     * Saves the object to the database
     * @return integer $order_id
     */
    function Save() {
        $connection = Database::Connect();
        $this->pog_query = "select `order_id` from `order` where `order_id`='".$this->order_id."' LIMIT 1";
        $rows = Database::Query($this->pog_query, $connection);
        if ($rows > 0) {
            $this->pog_query = "update `order` set
			`product_item_id`='".$this->Escape($this->product_item_id)."',
			`portalid`='".$this->Escape($this->portalid)."',
			`price`='".$this->Escape($this->price)."',
			`discount`='".$this->Escape($this->discount)."',
			`user_id`='".$this->Escape($this->user_id)."',
			`delivery_option`='".$this->Escape($this->delivery_option)."',
			`order_status`='".$this->Escape($this->order_status)."',
			`order_date`='".$this->order_date."',
			`confirm_payment_date`='".$this->confirm_payment_date."',
			`product_type`='".$this->Escape($this->product_type)."',
			`chk_for_register`='".$this->Escape($this->chk_for_register)."',
			`currency_code`='".$this->Escape($this->currency_code)."',
			`shipping_charge`='".$this->Escape($this->shipping_charge)."',
			`email_id`='".$this->Escape($this->email_id)."',
			`language_code`='".$this->Escape($this->language_code)."' where `order_id`='".$this->order_id."'";
        }
        else {
            $this->pog_query = "insert into `order` (`product_item_id`, `portalid`, `price`, `discount`, `user_id`, `delivery_option`, `order_status`, `order_date`, `confirm_payment_date`, `product_type`, `chk_for_register`, `currency_code`, `shipping_charge`, `email_id`, `language_code` ) values (
			'".$this->Escape($this->product_item_id)."',
			'".$this->Escape($this->portalid)."',
			'".$this->Escape($this->price)."',
			'".$this->Escape($this->discount)."',
			'".$this->Escape($this->user_id)."',
			'".$this->Escape($this->delivery_option)."',
			'".$this->Escape($this->order_status)."',
			'".$this->order_date."',
			'".$this->confirm_payment_date."',
			'".$this->Escape($this->product_type)."',
			'".$this->Escape($this->chk_for_register)."',
			'".$this->Escape($this->currency_code)."',
			'".$this->Escape($this->shipping_charge)."',
			'".$this->Escape($this->email_id)."',
			'".$this->Escape($this->language_code)."' )";
        }
        $insertId = Database::InsertOrUpdate($this->pog_query, $connection);
        if ($this->order_id == "") {
            $this->order_id = $insertId;
        }
        return $this->order_id;
    }


    /**
     * Clones the object and saves it to the database
     * @return integer $order_id
     */
    function SaveNew() {
        $this->order_id = '';
        return $this->Save();
    }


    /**
     * Deletes the object from the database
     * @return boolean
     */
    function Delete() {
        $connection = Database::Connect();
        $this->pog_query = "delete from `order` where `order_id`='".$this->order_id."'";
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
            $pog_query = "delete from `order` where ";
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