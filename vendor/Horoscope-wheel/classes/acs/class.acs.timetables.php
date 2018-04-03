<?php
/**
 * Script: /classes/acs/class.acs.timetables.php
 * Author: Andy Gray
 *
 * Description
 * Manage time tables
 *
 * Modification History
 * - initial spike
 */

class AcsTimetables {

	protected $m_zone_offset;
	protected $m_type_offset;
	protected $m_tables;
	protected $m_birthdate;

	public function AcsTimetables() {

		global $logger;
		//$logger->debug('AcsTimetables::AcsTimetables - entering');

		if( ($fp = fopen(ROOTPATH."/vendor/Horoscope-wheel/var/lib/acs/TimeTabs.txt","r")) === false ) {
			$logger->error("AcsTimetables::AcsTimetables - unable to open Timetables");
			return;
		}

		$this->m_tables = array();

		$row = 0;
		while( ($data = fgetcsv($fp, 256, ",")) !== false ) {
			if( count($data) == 1 ) {
				/* Table id */
				//$tables = split(" ", trim($data[0]));
				$tables = explode(" ", trim($data[0]));
				$table_number = intval($tables[1]);
			}
			if( count($data) == 2 ) {
				if( $data[0] != "2147483647" ) {
					/* check for end of table marker */
					array_push($this->m_tables,   array("table" => intval($table_number),  
														"change" => array(
													       "date" => $data[0],
													       "offset" => intval($data[1]))
													    ));
				}
			}
		}
		// $logger->debug('AcsTimeTables::AcsTimetables - number of tables is ' . count($this->m_tables));
		// $logger->debug('AcsTimetables::AcsTimetables - leaving');
	}

	/*
	 * expect date in the form YYYY-MM-DD HH:MM:SS
	 */
	public function setBirthdate($birthdate) {
		global $logger;
		// $logger->debug('AcsTimetables::setBirthdate - entering');
		$this->m_birthdate = ( intval(substr($birthdate,0,4)) * 624000 ) +
							 ( intval(substr($birthdate,5,2)) * 48000 ) +
							 ( intval(substr($birthdate,8,2)) * 1500 ) +
							 ( intval(substr($birthdate,11,2)) * 60 ) +
							 ( intval(substr($birthdate,14,2)) );
		// $logger->debug('AcsTimetables::setBirthdate - leaving');
	}

	public function getZoneOffset($zone) {
		global $logger;
		// $logger->debug("AcsTimetables::getZoneOffset - entering, zone = $zone");
		if( $zone < 12000 ) {
			/*
			 * The time zone is the hours from Greenwich of the zone times 900.
			 * This allows zones that have fractional hours to be represented as integers.
			 * For example, US Eastern Time is 4500 (5 times 900).  India time is -4950 (-5.5 times 900).
			 * Zones west of Greenwich are positive, east of Greenwich are negative.
			 */
			$this->m_zone_offset = floatval($zone) / 900.0; /* done deal, sorted */
			// $logger->debug("AcsTimetables::getZoneOffset - Zone is " . sprintf("%5.2f", $this->m_zone_offset) . " hours from GMT.");
		} else {
			$zone -= 12000; /* strip the zone offset to get the table number */
			/*
			 * if the date is before the 1st entry then this is local mean time
			 */
			$undefined = true;
			$acs_offset = 0;
			// $logger->debug("AcsTimetables::getZoneOffset - zone table($zone) has ".count($this->m_tables[$zone])." rows");
			foreach( $this->m_tables as $t ) {

				if( $t['table'] != $zone ) continue;

				//print_r($t);

				$acs_date = intval($t['change']['date']);

				// $logger->debug("AcsTimetables::getZoneOffset - birthdate=".$this->m_birthdate.", date=".$acs_date.", offset=".$acs_offset);

				if( $this->m_birthdate < $acs_date ) {
					// $logger->debug("AcsTimetables::getZoneOffset - birthdate after reference date");
					if( $undefined === true ) {
						/* undefined so refer as local mean time */
						// $logger->debug("AcsTimetables::getZoneOffset - undefined timezone, using LMT");
						$this->m_zone_offset = 0;
						return $this->m_zone_offset;
					} else {
						// $logger->debug("AcsTimetables::getZoneOffset - defined timezone");
						$this->m_zone_offset = (($acs_offset == 0) ? 0 : ($acs_offset / 900));
						return $this->m_zone_offset;
					}
				}
				/* update the offset value and iterate further */
				$acs_offset = intval($t['change']['offset']);
				$undefined = false;
			}
			$this->m_zone_offset = (($acs_offset == 0) ? 0 : ($acs_offset / 900));
		}
		// $logger->debug("AcsTimetables::getZoneOffset - using Zone as ".sprintf("%5.2f hours from GMT",$this->m_zone_offset));
		return $this->m_zone_offset;
	}

	public function getTypeOffset($type) {
		global $logger;
		// $logger->debug("AcsTimetables::getTypeOffset - (step 7) - entering with type value = $type.");
		// $logger->debug("AcsTimetables::getTypeOffset - (step 7) - time type for location is " . $type . ".");
		if( $type < 50 ) {
			/*
			 * If the type value < 50 then the types resolves itself directly via step14
			 */
			// $logger->debug("AcsTimetables::getTypeOffset - (step 8) - time type for location is < 50 so resolves directly.");
			switch( $type ) {
				case 4:
					// $logger->debug("AcsTimetables::getTypeOffset - (step 14) - [TODO] subtract 30 minutes from zone value");
					// $logger->debug("AcsTimetables::getTypeOffset - (step 14) - set the type to Standard Time.");
					$type = 0;
					return;
				case 6:
					// $logger->debug("AcsTimetables::getTypeOffset - (step 15) - [TODO] subtract 20 minutes from zone value");
					// $logger->debug("AcsTimetables::getTypeOffset - (step 15) - set the type to Standard Time.");
					$type = 0;
					break;
				case 7:
					// $logger->debug("AcsTimetables::getTypeOffset - (step 16) - [TODO] subtract 40 minutes from zone value");
					// $logger->debug("AcsTimetables::getTypeOffset - (step 16) - set the type to Standard Time.");
					$type = 0;
					break;
				default:
					//$logger->error("AcsTimetables::getTypeOffset - ERROR!!! - default case entered");
					break;
			}
		} else {
			if( $type > 30000 ) {
				/* special rules apply */
				// $logger->debug("AcsTimetables::getTypeOffset - (step 9) - time type > 30000 so special rules apply.");
				switch( $type ) {
					case 30002:
						// if zone is 4500 (Eastern) then set type = 0 (Standard) else set to 1 (Daylight)
						// $logger->debug("AcsTimetables::getTypeOffset - (step 18) - checking zone value");
						// $logger->debug("AcsTimetables::getTypeOffset - (step 18) - [TODO] zone value == 4500 (Eastern) so type is 0 (Standard)");
						// $logger->debug("AcsTimetables::getTypeOffset - (step 18) - [TODO] zone value != 4500 (Eastern) so type is 1 (Daylight)");
						break;
					case 30003:
						// if zone is 4500 (Eastern) then set type = 0 (Standard) else set to 30001 (can we fall through here?)
						// $logger->debug("AcsTimetables::getTypeOffset - (step 19) - checking zone value");
						// $logger->debug("AcsTimetables::getTypeOffset - (step 19) - [TODO] zone value == 4500 (Eastern) so type is 0 (Standard)");
						// $logger->debug("AcsTimetables::getTypeOffset - (step 19) - [TODO] zone value != 4500 (Eastern) so type is 30001 (Special)");
						break;
					case 30001:
						// this is plain stoopid!
						// before 1987 if the date is before the last Sunday in April or after the last Sunday in October then set to 0 (Standard)
						// after 1987 if the date is before the first Sunday in April or after the last Sunday in October then set to 0 (Standard)
						// $logger->debug("AcsTimetables::getTypeOffset - (step 20) - on US standard table");
						// $logger->debug("AcsTimetables::getTypeOffset - (step 20) - [TODO] test pre 1987 (last Sunday in April)");
						// $logger->debug("AcsTimetables::getTypeOffset - (step 20) - [TODO] test pre 1987 (last Sunday in October)");
						// $logger->debug("AcsTimetables::getTypeOffset - (step 20) - [TODO] zone value != 4500 (Eastern) so type is 30001 (Special)");
						break;
				}
			} else {
				// $logger->debug("AcsTimetables::getTypeOffset - (step 10) - time type > 50 and < 30000 (so no special rules apply)");
				// $logger->debug("AcsTimetables::getTypeOffset - (step 10) - time type refers to table ($type - 50) = " . ($type - 50) . ".");
				$this->table_scan( $type );
				switch( $this->m_type_offset ) {
					case 0: /* Standard Time */
						// $logger->debug("AcsTimetables::getTypeOffset - Standard Time");
						$this->m_type_offset = 0;
						break;
					case 1: /* Daylight Savings Time */
						// $logger->debug("AcsTimetables::getTypeOffset - Daylight Savings Time");
						$this->m_type_offset = 1;
						break;
					case 2: /* War Time */
						// $logger->debug("AcsTimetables::getTypeOffset - War Time");
						$this->m_type_offset = 2;
						break;
					case 3: /* Double Summer Time */
						// $logger->debug("AcsTimetables::getTypeOffset - Double Summer Time");
						$this->m_type_offset = 2;
						break;
					case 4: /* Local Mean Time */
						// $logger->debug("AcsTimetables::getTypeOffset - Local Mean Time");
						$this->m_type_offset = 0;
						break;
					default:
						// $logger->debug("AcsTimetables::getTypeOffset - Error - Invalid Time");
						$this->m_type_offset = 0;
						break;
				}
				return $this->m_type_offset;
			}
		}
	}

	private function table_scan( $type ) {

		global $logger;
		// $logger->debug("AcsTimetables::table_scan - entering, type = $type");
		// $logger->debug("AcsTimetables::table_scan - (step 10) - subtract 50 from type");
		$type -= 50;
		// $logger->debug("AcsTimetables::table_scan - (step 10) - type table($type) has ".count($this->m_tables[$type])." rows");

		$undefined = true;
		$acs_offset = 0;
		$this->m_type_offset = $acs_offset;

		foreach( $this->m_tables as $t ) {

			if( $t['table'] == $type ) {

				$acs_date = intval($t['change']['date']);

				// $logger->debug("AcsTimetables::table_scan - (step 11) - birthdate=".$this->m_birthdate.", date=".$acs_date.", offset=".$acs_offset);

				if( $this->m_birthdate < $acs_date ) {
					// $logger->debug("AcsTimetables::table_scan - (step 10) - birthdate lies before the reference date");
					if( $undefined === true ) {
						/* undefined so refer as local mean time */
						// $logger->debug("AcsTimetables::table_scan - (step 10) - date lies prior to first table entry");
						// $logger->debug("AcsTimetables::table_scan - (step 10) - returning type 4 (LMT)");
						$this->m_type_offset = 4;
						return;
					} else {
						if( $acs_offset > 50 ) {
	      // $logger->debug("AcsTimetables::table_scan - (step 10) - $acs_offset > 50 - recursing");
	      $this->table_scan( $acs_offset );
	      return;
						} else {
	      // $logger->debug("AcsTimetables::table_scan - (step 10) - $acs_offset < 50 - returning with a defined timezone");
	      $this->m_type_offset = $acs_offset;
	      return;
						}
					}
				} else {
					/* update the offset value and iterate further */
					$acs_offset = intval($t['change']['offset']);
					// $logger->debug("AcsTimetables::table_scan - (step 12) - offset = $acs_offset");
					if( $acs_offset > 50 ) {
						// $logger->debug("AcsTimetables::table_scan - (step 12) - $acs_offset > 50 - recursing");
						$this->table_scan( $acs_offset );
						return;
					}
					$undefined = false;
				}
			} /* end if */
		} /* end foreach */
		// $logger->debug("AcsTimetables::table_scan - fallthrough - acs_offset = $acs_offset, returning uncontrolled");
		$this->m_type_offset = $acs_offset;
	}

};

class AcsTimetablesTestHarness extends AcsTimetables {

	public function AcsTimetablesTestHarness() {
		$this->AcsTimetables();
	}

	public function test($birthdate,$zone,$type,$expect) {
		$this->setBirthdate($birthdate);
		$zone = $this->getZoneOffset($zone);
		$type = $this->getTypeOffset($type);
		echo "<tr>";
		echo "<td width='180px'>$birthdate</td>";
		echo "<td width='50px' align='right'>".sprintf("%5.2f",$zone)."</td>";
		echo "<td width='50px' align='right'>".sprintf("%5.2f",$type)."</td>";
		echo "<td width='50px' align='right'>".sprintf("%5.2f",($zone-$type))."</td>";
		echo "<td width='120px' align='right'>Expect: ".sprintf("%5.2f",$expect)."</td";
		if( $expect == ($zone - $type ) ) {
			echo "<td>Pass</td>";
		} else {
			echo "<td>Fail</td>";
		}
		echo "</tr>";
	}

};

?>
