<?php
  /**
   * Script: class.acs.state.php
   * Author: Andy Gray
   *
   * Description
   *
   * Modification History
   */

class ACSState {

  var $id;
  var $abbrev;
  var $name;

  function ACSState( $id, $name ) {
    $this->id = $id;
    $this->name = trim($name);
  }

  function getId() {
    return $this->id;
  }

  function getName() {
    return $this->name;
  }

  function getAbbrev() {
    $abbrev = chr(65+(intval($this->id/26))) . chr(65+(intval($this->id%26)));
    return $abbrev;
  }

  function teardown() {
    unset($this);
  }

  };

?>
