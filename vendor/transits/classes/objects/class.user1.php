<?php
/*
	This SQL query will create the table to store your object.

	CREATE TABLE `user` (
	`UserId` int(11) NOT NULL auto_increment,
	`UserName` TINYTEXT NOT NULL,
	`Password` TINYTEXT NOT NULL,
	`Status` TINYINT NOT NULL,
	`DefaultLanguage` VARCHAR(255) NOT NULL,
	`PortalId` INT NOT NULL,
	`UserGroupId` INT NOT NULL,
	`CreatedDate` DATETIME NOT NULL,
	`ModifiedDate` DATETIME NOT NULL,
	`CreatedBy` INT NOT NULL,
	`ModifiedBy` INT NOT NULL,
	`preview_report` VARCHAR(255) NOT NULL,
	`parent_user_id` INT NOT NULL, PRIMARY KEY  (`UserId`)) ENGINE=MyISAM;
*/

/**
 * <b>user</b> class with integrated CRUD methods.
 * @author Php Object Generator
 * @version POG 3.0f / PHP5
 * @copyright Free for personal & commercial use. (Offered under the BSD license)
 * @link http://www.phpobjectgenerator.com/?language=php5&wrapper=pog&objectName=user&attributeList=array+%28%0A++0+%3D%3E+%27UserName%27%2C%0A++1+%3D%3E+%27Password%27%2C%0A++2+%3D%3E+%27Status%27%2C%0A++3+%3D%3E+%27DefaultLanguage%27%2C%0A++4+%3D%3E+%27PortalId%27%2C%0A++5+%3D%3E+%27UserGroupId%27%2C%0A++6+%3D%3E+%27CreatedDate%27%2C%0A++7+%3D%3E+%27ModifiedDate%27%2C%0A++8+%3D%3E+%27CreatedBy%27%2C%0A++9+%3D%3E+%27ModifiedBy%27%2C%0A++10+%3D%3E+%27preview_report%27%2C%0A++11+%3D%3E+%27parent_user_id%27%2C%0A%29&typeList=array+%28%0A++0+%3D%3E+%27TINYTEXT%27%2C%0A++1+%3D%3E+%27TINYTEXT%27%2C%0A++2+%3D%3E+%27TINYINT%27%2C%0A++3+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++4+%3D%3E+%27INT%27%2C%0A++5+%3D%3E+%27INT%27%2C%0A++6+%3D%3E+%27DATETIME%27%2C%0A++7+%3D%3E+%27DATETIME%27%2C%0A++8+%3D%3E+%27INT%27%2C%0A++9+%3D%3E+%27INT%27%2C%0A++10+%3D%3E+%27VARCHAR%28255%29%27%2C%0A++11+%3D%3E+%27INT%27%2C%0A%29
 */
include_once('class.pog_base.php');
class POGUserClass extends POG_Base {
    public $UserId = '';

    /**
     * @var TINYTEXT
     */
    public $UserName;

    /**
     * @var TINYTEXT
     */
    public $Password;

    /**
     * @var TINYINT
     */
    public $Status;

    /**
     * @var VARCHAR(255)
     */
    public $DefaultLanguage;

    /**
     * @var INT
     */
    public $PortalId;

    /**
     * @var INT
     */
    public $UserGroupId;

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
    public $preview_report;

    /**
     * @var INT
     */
    public $parent_user_id;

    public $pog_attribute_type = array(
            "UserId" => array('db_attributes' => array("NUMERIC", "INT")),
            "UserName" => array('db_attributes' => array("TEXT", "TINYTEXT")),
            "Password" => array('db_attributes' => array("TEXT", "TINYTEXT")),
            "Status" => array('db_attributes' => array("NUMERIC", "TINYINT")),
            "DefaultLanguage" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
            "PortalId" => array('db_attributes' => array("NUMERIC", "INT")),
            "UserGroupId" => array('db_attributes' => array("NUMERIC", "INT")),
            "CreatedDate" => array('db_attributes' => array("TEXT", "DATETIME")),
            "ModifiedDate" => array('db_attributes' => array("TEXT", "DATETIME")),
            "CreatedBy" => array('db_attributes' => array("NUMERIC", "INT")),
            "ModifiedBy" => array('db_attributes' => array("NUMERIC", "INT")),
            "preview_report" => array('db_attributes' => array("TEXT", "VARCHAR", "255")),
            "parent_user_id" => array('db_attributes' => array("NUMERIC", "INT")),
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

    function POGUserClass($UserName='', $Password='', $Status='', $DefaultLanguage='', $PortalId='', $UserGroupId='', $CreatedDate='', $ModifiedDate='', $CreatedBy='', $ModifiedBy='', $preview_report='', $parent_user_id='') {
        $this->UserName = $UserName;
        $this->Password = $Password;
        $this->Status = $Status;
        $this->DefaultLanguage = $DefaultLanguage;
        $this->PortalId = $PortalId;
        $this->UserGroupId = $UserGroupId;
        $this->CreatedDate = $CreatedDate;
        $this->ModifiedDate = $ModifiedDate;
        $this->CreatedBy = $CreatedBy;
        $this->ModifiedBy = $ModifiedBy;
        $this->preview_report = $preview_report;
        $this->parent_user_id = $parent_user_id;
    }


    /**
     * Gets object from database
     * @param integer $UserId
     * @return object $user
     */
    function Get($UserId, $userType='') {
        $connection = Database::Connect();
        //$this->pog_query = "select * from `users` where `id`='".intval($UserId)."' LIMIT 1";
        if (!empty($userType)) {
            $selectedUserId = explode('_', $UserId);
            $this->pog_query = "select * from `another_persons`
                            where `another_persons`.`id`='".intval($selectedUserId[1])."' LIMIT 1";
        } else {
            $this->pog_query = "select * from `users` 
                            JOIN profiles ON profiles.user_id = users.id
                            JOIN languages ON languages.id = profiles.language_id
                            where `users`.`id`='".intval($UserId)."' LIMIT 1";
        }
        //$this->pog_query = "select * from `users` JOIN profiles on profiles.user_id = users.id where `id`='".intval($UserId)."' LIMIT 1";
        $cursor = Database::Reader($this->pog_query, $connection);
        while ($row = Database::Read($cursor)) {
            if (!empty($userType)) {
                $this->UserId = $row['id'];
                $this->UserName = $this->Unescape($row['fname']).' '.$this->Unescape($row['lname']);
                $this->Password = ''; //$this->Unescape($row['password']);
                $this->Status = 1; //$this->Unescape($row['status']);
                $selectedLanguage = (isset($_SESSION['locale']) && !empty($_SESSION['locale'])) ? $_SESSION['locale'] : 'en';
                if ($selectedLanguage == 'da') {
                    $selectedLanguage = 'dk';
                }
                $this->DefaultLanguage = $selectedLanguage; //!empty($this->request->sessio()->read('locale')) ? $this->request->sessio()->read('locale') : 'en';

                $this->PortalId = 2; //$this->Unescape($row['portal_id']);
                $this->CreatedDate = $row['created'];
                $this->ModifiedDate = $row['modified'];
                $this->preview_report = ''; //$this->Unescape($row['preview_report']);
            } else {
                $this->UserId = $row['id'];
                $this->UserName = $this->Unescape($row['first_name']).' '.$this->Unescape($row['last_name']);
                $this->Password = $this->Unescape($row['password']);
                $this->Status = $this->Unescape($row['status']);
                $this->DefaultLanguage = $this->Unescape($row['code']);
                $this->PortalId = $this->Unescape($row['portal_id']);
                //$this->UserGroupId = $this->Unescape($row['UserGroupId']);
                $this->CreatedDate = $row['created'];
                $this->ModifiedDate = $row['modified'];
                //$this->CreatedBy = $this->Unescape($row['CreatedBy']);
                //$this->ModifiedBy = $this->Unescape($row['ModifiedBy']);
                $this->preview_report = $this->Unescape($row['preview_report']);
                //$this->parent_user_id = $this->Unescape($row['parent_user_id']);
            }
        }
        return $this;
    }


    /**
     * Returns a sorted array of objects that match given conditions
     * @param multidimensional array {("field", "comparator", "value"), ("field", "comparator", "value"), ...}
     * @param string $sortBy
     * @param boolean $ascending
     * @param int limit
     * @return array $userList
     */
    function GetList($fcv_array = array(), $sortBy='', $ascending=true, $limit='') {
        $connection = Database::Connect();
        $sqlLimit = ($limit != '' ? "LIMIT $limit" : '');
        $this->pog_query = "select * from `user` ";
        $userList = Array();
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
            $sortBy = "UserId";
        }
        $this->pog_query .= " order by ".$sortBy." ".($ascending ? "asc" : "desc")." $sqlLimit";
        $thisObjectName = get_class($this);
        $cursor = Database::Reader($this->pog_query, $connection);
        while ($row = Database::Read($cursor)) {
            $user = new $thisObjectName();
            $user->UserId = $row['UserId'];
            $user->UserName = $this->Unescape($row['UserName']);
            $user->Password = $this->Unescape($row['Password']);
            $user->Status = $this->Unescape($row['Status']);
            $user->DefaultLanguage = $this->Unescape($row['DefaultLanguage']);
            $user->PortalId = $this->Unescape($row['PortalId']);
            $user->UserGroupId = $this->Unescape($row['UserGroupId']);
            $user->CreatedDate = $row['CreatedDate'];
            $user->ModifiedDate = $row['ModifiedDate'];
            $user->CreatedBy = $this->Unescape($row['CreatedBy']);
            $user->ModifiedBy = $this->Unescape($row['ModifiedBy']);
            $user->preview_report = $this->Unescape($row['preview_report']);
            $user->parent_user_id = $this->Unescape($row['parent_user_id']);
            $userList[] = $user;
        }
        return $userList;
    }


    /**
     * Saves the object to the database
     * @return integer $UserId
     */
    function Save() {
        $connection = Database::Connect();
        $this->pog_query = "select `UserId` from `user` where `UserId`='".$this->UserId."' LIMIT 1";
        $rows = Database::Query($this->pog_query, $connection);
        if ($rows > 0) {
            $this->pog_query = "update `user` set
			`UserName`='".$this->Escape($this->UserName)."',
			`Password`='".$this->Escape($this->Password)."',
			`Status`='".$this->Escape($this->Status)."',
			`DefaultLanguage`='".$this->Escape($this->DefaultLanguage)."',
			`PortalId`='".$this->Escape($this->PortalId)."',
			`UserGroupId`='".$this->Escape($this->UserGroupId)."',
			`CreatedDate`='".$this->CreatedDate."',
			`ModifiedDate`='".$this->ModifiedDate."',
			`CreatedBy`='".$this->Escape($this->CreatedBy)."',
			`ModifiedBy`='".$this->Escape($this->ModifiedBy)."',
			`preview_report`='".$this->Escape($this->preview_report)."',
			`parent_user_id`='".$this->Escape($this->parent_user_id)."' where `UserId`='".$this->UserId."'";
        }
        else {
            $this->pog_query = "insert into `user` (`UserName`, `Password`, `Status`, `DefaultLanguage`, `PortalId`, `UserGroupId`, `CreatedDate`, `ModifiedDate`, `CreatedBy`, `ModifiedBy`, `preview_report`, `parent_user_id` ) values (
			'".$this->Escape($this->UserName)."',
			'".$this->Escape($this->Password)."',
			'".$this->Escape($this->Status)."',
			'".$this->Escape($this->DefaultLanguage)."',
			'".$this->Escape($this->PortalId)."',
			'".$this->Escape($this->UserGroupId)."',
			'".$this->CreatedDate."',
			'".$this->ModifiedDate."',
			'".$this->Escape($this->CreatedBy)."',
			'".$this->Escape($this->ModifiedBy)."',
			'".$this->Escape($this->preview_report)."',
			'".$this->Escape($this->parent_user_id)."' )";
        }
        $insertId = Database::InsertOrUpdate($this->pog_query, $connection);
        if ($this->UserId == "") {
            $this->UserId = $insertId;
        }
        return $this->UserId;
    }


    /**
     * Clones the object and saves it to the database
     * @return integer $UserId
     */
    function SaveNew() {
        $this->UserId = '';
        return $this->Save();
    }


    /**
     * Deletes the object from the database
     * @return boolean
     */
    function Delete() {
        $connection = Database::Connect();
        $this->pog_query = "delete from `user` where `UserId`='".$this->UserId."'";
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
            $pog_query = "delete from `user` where ";
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