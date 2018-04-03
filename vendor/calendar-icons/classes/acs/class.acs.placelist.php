<?php
  /**
   * Script: class.acs.placelist.php
   * Author: Andy Gray
   *
   * Description
   *
   * Modification History
   */

class ACSPlaceList extends ACS {

  var $places = array();

  /**
   * Constructor
   * Build an array of places based on country code (location key)
   */
  function ACSPlaceList( $stateabbrev, $truncated=false ) {
    $atlas = new ACSAtlas();
    $connection = Database::Connect();
    $pog_query = "select * from `acsatlas` where lkey like '".$stateabbrev."%' order by lkey";
    $cursor = Database::Reader($pog_query, $connection);
    while ($row = Database::Read($cursor)) {
      if( $truncated === true ) {
	$fullplace = split(">",$row['placename']);
	$placename = trim($fullplace[0]);
      } else {
	$placename = trim($row['placename']);
      }
      array_push
	(
	 $this->places,
	 array
	 (
	  "id" => $row['acsatlasid'],
	  "lkey" => $row['lkey'],
	  "name" => $placename
	  )
	 );
    }
  }

  function get() {
    return $this->places; /* array of arrays */
  }

  function getXML() {
    $xml = new MyXmlWriter();
    $xml->push('places');
    foreach( $this->places as $place ) {
      $xml->push('place');
      $xml->element('placeid',$place['id']);
      $location_key = $place['lkey'];
      $xml->raw_element('placekey',$location_key);
      $placename = $place['name'];
      if( mb_detect_encoding($place['name']) == 'UTF-8' ) {
	$placename = utf8_encode($placename);
      }
      $xml->raw_element('placename',$placename);
      $xml->pop();
    } /* end while */
    $xml->pop();
    return $xml->getXML();
  }

  function getJSON() {
    return json_encode( $this->places );
  }

  function teardown() {
    unset($this);
  }

  };

?>
