<?php
  /**
   * Script: class.acs.php
   * Author: Andy Gray
   */

class ACS {

  var $fatlas = NULL;

  /**
   * Constructor
   * Initially file based, will use the database instead
   */
  public function ACS() {
    if( ($this->fatlas = fopen(ROOTPATH.'/html/wsapi/acs/KeyAtlas.txt',"r")) === false ) {
      echo "unable to open data file\n";
      return;
    }
  }

  public function teardown() {
    fclose($this->fatlas);
  }

  }; /* class end */

?>
