<?php

class ACSTimeTable {

  private $zone;
  private $type;

  public function __construct( $zone, $type ) {
    $this->zone = $zone;
    $this->type = $type;
    $this->findZone();
    $this->findType();
  }

  private function findZone() {
    /* step 2
     If the zone is < 12000, go to step 7
    */
    if( $this->zone < 12000 ) {
      /* step 7 */
      return;
    } else {
      /* step 3
       Subtract 12000 from the zone and look up that table number
      */
      $this->zone -= 12000;
      $this->scanZoneTable();
    }
  }

  private function scanZoneTable() {
    /* step 4
     Look through the dates in the table until you find a pair between which
     the date for which you are searching falls.  If your date is before the
     first date in the table, the zone is undefined (i.e., the city was not
     yet using Standard Time); set the zone to the longitude multiplied by 60
     (to make it equivalent to hours from Greenwich * 900) and go to step 7.
    */
  }

  private function findType() {
    if( $this->type < 50 ) {
      /* goto step 14 */
      /* step 14 */
      switch( $this->type ) {
      case 4:
	/* If the type is 4, subtract 450 (1/2 hour) from the zone and set
	 the type to 0 (Standard).  You are done.
	*/
	$this->zone -= 450;
	$this->type = 0;
	return;
      case 6:
	/* If the type is 6, subtract 300 (20 mins) from the zone and set
	 the type to 0 (Standard).  You are done.
	*/
	$this->zone -= 300;
	$this->type = 0;
	return;
      case 8:
	/* If the type is 8, subtract 600 (40 mins) from the zone and set
	 the type to 0 (Standard).  You are done.
	*/
	$this->zone -= 600;
	$this->type = 0;
	return;
      default:
	die( "Error in findType - default case found in step 14\n");
      }
      /* NOTREACHED */
    } else {
      if( $this->type > 30000 ) {
	/* goto step 18 */
	/* step 18 */
	switch( $this->type ) {
	case 30002:
	  if( $this->zone == 4500 ) {
	    $this->type = 0; /* Standard */
	  } else {
	    $this->type = 1; /* Daylight */
	  }
	  return;
	case 30003:
	  if( $this->zone == 4500 ) {
	    $this->type = 0; /* Standard */
	    return;
	  }
	  $this->type = 30001;
	  /* allow to fall through from case 30003 to case 30001 here */
	case 30001:
	default:
	}
      } else {
	/* step 10 */
	$this->type -= 50;
      }
    }
  }

  };

?>
