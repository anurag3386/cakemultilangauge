<?php
  /**
   * Script: class.acs.place.php
   * Author: Andy Gray
   *
   * Description
   *
   * Modification History
   */

class ACSPlace {

  private $mash;
  private $name;
  private $region;
  private $latitude;
  private $longitude;
  private $zone;
  private $type;

  public function ACSPlace( $data ) {
    $this->mash = $data[0];
    $this->name = trim($data[1]);
    $this->region = trim($data[2]);
    $this->latitude = intval($data[3]);
    $this->longitude = intval($data[4]);
    $this->zone = intval($data[5]);
    $this->type = intval($data[6]);
  }

  public function getLocationKey() {
    return $this->mash;
  }

  public function getName() {
    return $this->name;
  }

  public function getRegion() {
    return $this->region;
  }

  public function getStateAbbrev() {
    return substr($this->mash,0,2);
  }

  public function teardown() {
    unset($this);
  }

  /* date is the date for which adjustement is required */
  public function getTZInfo( $date ) {
    
    /* if zone < 12000 then it is unchanged */
    if( $this->zone < 12000 ) {
      /* unchanged */
    }
  }

  /*
   * If Field 6 is less than 12000, it is the time zone for the city, which has
   * not changed.  The time zone number is the hours from Greenwich times 900.  It
   * can be negative: positive numbers are zones west of Greenwich; negative
   * numbers are east of Greenwich.  Thus, for example, the value for US Eastern
   * Time is 4500; the value for Japan time (9 hours east of Greenwich) is -8100.
   * A value greater than 12000 is a table number in the time tables file.
   */
  private function findZone() {
    /* if zone < 12000 then it is unchanged */
    if( $this->zone < 12000 ) {
      /* zone is 900 * hours from GMT */
      return;
    }
    /* must be table number */
    $this->zone -= 12000;
  }

  };

?>
