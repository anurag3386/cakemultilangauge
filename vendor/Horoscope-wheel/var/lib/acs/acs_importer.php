<?php
  /**
   * Script: ACSImporter.php
   * Author: Andy Gray
   *
   * Description
   * Imports the ACS atlas
   * ACS atlas source in KeyAtlas.txt
   * DB target = ACSPlace
   *
   * Method
   * clear all rows in table
   * foreach( line in KeyAtlas.txt ) {
   * - create an ACSPlace object
   * - save to database
   * --> create ACSPlaceDBobject
   * --> map locationkey to DB
   * --> map place name to DB
   * --> map region name to DB
   * --> map longitude to DB
   * --> map latitude to DB
   * --> map zone table to DB
   * --> map type table to DB
   * --< savenew
   * - teardown object
   * }
   *
   * Modification History
   * - initial spike
   */

  /* path definitions */
define('ROOTPATH','/home/29078/users/.home/domains/world-of-wisdom.com');
define('CLASSPATH',ROOTPATH.'/classes');
define('LIBPATH',ROOTPATH.'/lib');

/* POG data */
require_once(CLASSPATH.'/configuration.php');
require_once(CLASSPATH.'/objects/class.database.php');
require_once(CLASSPATH.'/objects/class.pog_base.php');

require_once(CLASSPATH.'/objects/class.acsatlas.php');

$keyatlas = ROOTPATH . '/var/lib/acs/KeyAtlas.txt';
$timetables = ROOTPATH . '/var/lib/acs/TimeTabs.txt';

/* main */
importACSAtlas( $keyatlas );
// TODO - importACSTimeTables();
die("Import completed\n");

function importACSAtlas( $keyatlas ) {
  
  $fp = fopen( $keyatlas, "r" );
  if( $fp === false ) {
    die("failed to open KeyAtlas\n");
  }
  
  $rows = 0;
  $atlas = new ACSAtlas();
  while( ($line = fgets($fp)) !== false ) {
    /* read a row */
    list(
	 $location_key,
	 $placename,
	 $regionname,
	 $latitude,
	 $longitude,
	 $zone,
	 $type
	 ) = split(':',$line);
    /* map attributes */
    $atlas->lkey = $location_key;
    $atlas->placename = $placename;
    $atlas->region = $regionname;
    $atlas->latitude = $latitude;
    $atlas->longitude = $longitude;
    $atlas->zone = $zone;
    $atlas->type = $type;
    $atlas->savenew();
    /* increment row count */
    $rows++;
  }
  fclose($fp);
  echo "KeyAtlas import complete, $rows added\n";
}

function importTimeTables() {
}

?>
