<?php
if (!class_exists('cDatabase')) {
    if(!include("cDatabase.php")) {
        require_once("../cDatabase.php");
    }
}
if (!defined('CLASSPATH')) {
    define('CLASSPATH',	ROOTPATH.'/classes');
}
require_once(CLASSPATH.'/configuration.php');
require_once(CLASSPATH.'/objects/class.database.php');
require_once(CLASSPATH.'/objects/class.pog_base.php');


class ACSRepository {
    public function GetACSDetails($country_abrevation,$place_name, $limit = 10) {

        $connection = Database::Connect();
        $pog_query = "select * ".
                "from `acsatlas` ".
                "where lkey like '".strtoupper($country_abrevation)."%' ".
                "and lower(placename) like '".strtolower($place_name)."%' ".
                "order by placename ".
                "limit $limit";

        $cursor = Database::Reader($pog_query, $connection);
        $ItemsList = array();

        while ($row = Database::Read($cursor)) {
            $latitude = floatval($row['latitude']);
            $longitude = floatval($row['longitude']);
            $tmp = array();
            $tmp["acsatlasid"] = $row['acsatlasid'];
            $tmp["placename"] = $row['placename'];
            $tmp["region"] = $row['region'] ;
            $tmp["clatitude"] = $latitude;
            $tmp["clongitude"] = $longitude;
            $tmp["latitude"] = $row['latitude'];
            $tmp["longitude"] = $row['longitude'];
            $tmp["zone"] = $row['zone'];
            $tmp["type"] = $row['type'];

            $ItemsList[] = $tmp;
        }
        return $ItemsList;
//        $obj = new cDatabase();
//        $sql =" SELECT placename, region, latitude, longitude,zone,`type` FROM orm_acsatlas" ;
//        $sql .=" where lower(lkey) LIKE '".strtolower($country_abrevation)."%' AND lower(placename) LIKE '".strtolower($place_name)."%'";
//        $sql .=" ORDER BY placename";
//        $sql .=" LIMIT ".$limit;
//        echo $sql;
//        try {
//            $query = $obj->db->query($sql);
//            return $query->rows;
//        }
//        catch(Exception $e){
//            echo $e->getMessage();
//        }        
    }

    public function GetACSDataRow($sql) {
        $obj = new cDatabase();
        $query = $obj->db->query($sql);
	return $query;
        //return $query->rows;
    }
}
?>
