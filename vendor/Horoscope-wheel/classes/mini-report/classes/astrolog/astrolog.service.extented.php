<?php

class AstrologServicesExtented extends AstrologServices {

	function __construct($birthDTO,  $calcChartData = true) {
		parent::AstrologServices($birthDTO, $calcChartData );
	}

	/**
	 * Calculate Ephemeris Tables
	 *
	 * The dynamic aspect reports require start and end dates for the transits so
	 * here we precalculate all aspects for Jupiter..Pluto for a period of years
	 * and cache the locations for lookup
	 *
	 * Note: there is a bug in astrolog where 3 year tables are used, specifically for
	 * 2008 for 3 years, Jupiter transits Capricorn, Taurus, Virso, Taurus in consecutive
	 * months !!!
	 *
	 * Consider caching in the database for next 5 years or so
	 *
	 * @param int $start_date
	 * @param int $duration up to 3 years
	 */
	function calcEphemeris($start_year, $duration) {
		/*
		 * we are going to calculate the planetary positions from the start of
		* the current year to the end of the final (duration+1) year
		*/
		$this->m_ephemeris = array();
		$lines = array();
		for ($i = 0; $i <= $duration; $i++) {

			$this->m_pipe = new AstrologCalculator();

			$this->m_pipe->addArgument(sprintf("-qy %04d", $start_year + $i));
			$this->m_pipe->addArgument("-Ey");   /* whole year     */
			$this->m_pipe->addArgument("-R0");   /* restrict all   */

			$this->m_pipe->addArgument("-R 6");  /* enable jupiter */
			$this->m_pipe->addArgument("-R 7");  /* enable saturn  */
			$this->m_pipe->addArgument("-R 8");  /* enable uranus  */
			$this->m_pipe->addArgument("-R 9");  /* enable neptune */
			$this->m_pipe->addArgument("-R 10"); /* enable pluto   */

			/* finally call Astrolog */
			$this->m_pipe->callAstrolog();
			$lines = array_merge($lines, explode("\n", $this->m_pipe->getCache()));
			$this->m_pipe->teardown();
		}

		$this->getEphemeris($start_year, $lines);
	}


	/**
	 #Mo/Dy/Yr  Jupi   Satu   Uran   Nept   Plut
	 #12/ 1/ 9 20Aq53  2Li58 22Pi42.23Aq53  2Cp11
	 #----------111111111122222222223333333333444444444455555555556666666666777777777
	 #0123456789012345678901234567890123456789012345678901234567890123456789012345678
	 **/
	function getEphemeris_NEW($start_date, $lines) {

		foreach ($lines as $line) {
			/* look for main ephemeris data lines only including pluto retrograde flag */
			if (strlen($line) == 44) {
				/* get the date */
				$tmp = array( $this->getEphemerisData($start_date, $line));
				$this->m_ephemeris = array_merge($this->m_ephemeris, $tmp);
			}
		}
	}

	function getEphemerisData_NEW($start_date, $line) {
		/* date related */
		$month = intval(trim(substr($line, 0, 2)));
		$day = intval(trim(substr($line, 3, 2)));
		$year = intval(trim(substr($line, 6, 2)));
		$year1 = intval(trim(substr($start_date, 0, 2)));
		$FinalYear = sprintf("%02d%02d", $year1, $year);

		$EphemerisObj = new EphemerisObjects(
				$this->getEphemerisObjectData($start_date, substr($line, 9, 7)),
				$this->getEphemerisObjectData($start_date, substr($line, 16, 7)),
				$this->getEphemerisObjectData($start_date, substr($line, 23, 7)),
				$this->getEphemerisObjectData($start_date, substr($line, 30, 7)),
				$this->getEphemerisObjectData($start_date, substr($line, 37, 7)),
				date("Y-m-d", mktime ( 0, 0, 0, $month, $day, $year ) ));

		return $EphemerisObj;
		/**
		 return array (
		 'cdate' => date("Y-m-d", mktime ( 0, 0, 0, $month, $day, $year ) ),
		 'year' => $FinalYear,
		 'month' => $month,
		 'day' => $day,
		 'Jupiter' => $this->getEphemerisObjectData($start_date, substr($line, 9, 7)),
		 'Saturn' => $this->getEphemerisObjectData($start_date, substr($line, 16, 7)),
		 'Uranus' => $this->getEphemerisObjectData($start_date, substr($line, 23, 7)),
		 'Neptune' => $this->getEphemerisObjectData($start_date, substr($line, 30, 7)),
		 'Pluto' => $this->getEphemerisObjectData($start_date, substr($line, 37, 7))
		 );
		 **/
	}

	function getEphemerisObjectData_NEW($start_date, $line) {
		$signs = array('Ar' => 0, 'Ta' => 30, 'Ge' => 60, 'Cn' => 90, 'Le' => 120, 'Vi' => 150,
				'Li' => 180, 'Sc' => 210, 'Sg' => 240, 'Cp' => 270, 'Aq' => 300, 'Pi' => 330
		);

		/* extract sign */
		$sign = substr($line, 2, 2);
		$signoffset = $signs[$sign];
		if ($signoffset < 0 || $signoffset > 330) {
			// error
		}

		/* determine degree */
		$degree = 360 + $signoffset + intval(trim(substr($line, 0, 2)));
		$minute = intval(trim(substr($line, 4, 2)));
		$minute = intval(floatval($minute) * (100.0 / 60.0));

		/* determine motion */
		$retrograde = ( substr($line, 6, 0) == '.' ) ? true : false;

		return new LongitudeObject($degree + ($minute / 100.0), $retrograde, $sign);
	}


	/**
	 * calcCrossingAspects
	 */
	function calcCrossingAspects($data, $start_year, $duration) {
		global $logger;
		$logger->debug("AstrologServices::calcCrossingAspects()");

		/** determine the aspects */
		$this->m_pipe = new AstrologCalculator();

		/* format the birth data */
		$this->m_pipe->addArgument($data->qaSwitchFormat());

		if ($data->timed_data == false) {
			/* specify whole house system */
			$this->m_pipe->addArgument('-c 12');
			$this->m_pipe->addArgument('-sr');
		}

		/* include the angles as objects */
		$this->m_pipe->addArgument('=C');

		/* ptolemaic aspects only used */
		$this->m_pipe->addArgument('-A 1');

		/* orbs used */
		$this->m_pipe->addArgument('-YAo 1 5 8.0 8.0 6.0 5.0 5.0');

		/* max permissable orbs */
		$this->m_pipe->addArgument('-YAm  1 10 360 360 360 360 360 360 360 360 360 360');
		$this->m_pipe->addArgument('-YAm 11 20 360 360 360 360 360   3   3   3   3   3');
		$this->m_pipe->addArgument('-YAm 21 32 360 360 360 360 360 360 360 360 360 360 360 360');
		$this->m_pipe->addArgument('-YAm 33 41 360 360 360 360 360 360 360 360 360');

		/* planet aspect orb additions */
		$this->m_pipe->addArgument('-YAd  1 10 1.0 1.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0');
		$this->m_pipe->addArgument('-YAd 11 20 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0');
		$this->m_pipe->addArgument('-YAd 21 32 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0');

		/* just in case the aspect angles are not defined ... */
		// $this->m_pipe->addArgument('-YAa 1 5	0.0 180.0 90.0 120.0 60.0');

		/* manage aspected object settings */
		$this->m_pipe->addArgument('-YR  1 10 1 1 1 1 1 1 1 1 1 1');  		/* main planets enabled */
		$this->m_pipe->addArgument('-YR 11 20 1 1 1 1 1 1 1 1 1 1');  		/* node enabled */
		$this->m_pipe->addArgument('-YR 21 32 0 0 0 0 0 0 0 0 0 0 0 0'); 	/* ascendant/MC enabled */
		$this->m_pipe->addArgument('-YR 33 41 1 1 1 1 1 1 1 1 1');   		/* all else disabled */

		/* manage transiting object settings */
		$this->m_pipe->addArgument('-YRT 1 10  1 1 1 1 1 0 0 1 1 1');
		$this->m_pipe->addArgument('-YRT 11 20 1 1 1 1 1 0 1 1 1 1');
		$this->m_pipe->addArgument('-YRT 21 32 1 1 1 1 1 1 1 1 1 1 1 1');
		$this->m_pipe->addArgument('-YRT 33 41 1 1 1 1 1 1 1 1 1');

		/* request aspect generation */
		$this->m_pipe->addArgument(sprintf("-tY %d %d", $start_year, $duration));

		/* finally call Astrolog */
		$this->m_pipe->callAstrolog();
		$logger->debug("AstrologServices::calcCrossingAspects: cache=" . $this->m_pipe->getCache());
		$lines = explode("\n", $this->m_pipe->getCache());
		$this->m_crossing = array();

		// 		echo "CROSSING : calcCrossingAspects()<BR />";
		// 		echo $this->m_pipe->m_args . "<br />";
		// 		print_r($lines);

		$this->getCalendarAspects($lines, true, true);
		$this->m_pipe->teardown();
	}

	/**
	 * calcDynamicAspects
	 *
	 * Calculate transits to a basic chart.
	 *
	 * The transits are cached in the m_transits array and cusp crossings in the
	 * m_crossings array.
	 *
	 * @param BirthData $data
	 * @param int $start_year
	 * @param int $duration
	 */
	function calcDynamicAspects($data, $start_year, $duration) {
		/**
		 * create the Astrolog low level API instance
		 */
		$this->m_pipe = new AstrologCalculator();

		/* format the birth data */
		$this->m_pipe->addArgument($data->qaSwitchFormat());

		if ($data->timed_data == false) {
			/* specify whole house system */
			//$this->m_pipe->addArgument('-c 12');
		}

		/* include the angles as objects */
		$this->m_pipe->addArgument('=C');

		/* ptolemaic aspects only used */
		$this->m_pipe->addArgument('-A 5');

		/* orbs used */
		$this->m_pipe->addArgument('-YAo 1 5 8.0 8.0 6.0 5.0 5.0');

		/* max permissable orbs */
		$this->m_pipe->addArgument('-YAm  1 10 360 360 360 360 360 360 360 360 360 360');
		$this->m_pipe->addArgument('-YAm 11 20 360 360 360 360 360   3   3   3   3   3');
		$this->m_pipe->addArgument('-YAm 21 32 360 360 360 360 360 360 360 360 360 360 360 360');
		$this->m_pipe->addArgument('-YAm 33 41 360 360 360 360 360 360 360 360 360');

		/* planet aspect orb additions */
		$this->m_pipe->addArgument('-YAd  1 10 1.0 1.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0');
		$this->m_pipe->addArgument('-YAd 11 20 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0');
		$this->m_pipe->addArgument('-YAd 21 32 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0');

		/* just in case the aspect angles are not defined ... */
		// $this->m_pipe->addArgument('-YAa 1 5	0.0 180.0 90.0 120.0 60.0');

		/* manage aspected object settings */
		$this->m_pipe->addArgument('-YR  1 10 0 0 0 0 0 0 0 0 0 0');  /* main planets enabled */
		$this->m_pipe->addArgument('-YR 11 20 1 1 1 1 1 0 1 1 1 1');  /* node enabled */
		$this->m_pipe->addArgument('-YR 21 32 0 1 1 1 1 1 1 1 1 0 1 1'); /* ascendant/MC enabled */
		$this->m_pipe->addArgument('-YR 33 41 1 1 1 1 1 1 1 1 1');   /* all else disabled */

		/* manage transiting object settings */
		$this->m_pipe->addArgument('-YRT 1 10  1 1 1 1 1 0 0 0 0 0');
		$this->m_pipe->addArgument('-YRT 11 20 0 1 1 1 1 1 1 1 1 1');
		$this->m_pipe->addArgument('-YRT 21 32 1 1 1 1 1 1 1 1 1 1 1 1');
		$this->m_pipe->addArgument('-YRT 33 41 1 1 1 1 1 1 1 1 1');

		/* request aspect generation */
		$this->m_pipe->addArgument(sprintf("-tY %d %d", $start_year, $duration));

		/* finally call Astrolog */
		$this->m_pipe->callAstrolog();
		$lines = explode("\n", $this->m_pipe->getCache());

		$this->m_transit = array();

		//Only check the Transit for planets
		$this->getCalendarAspects($lines, true, false);
		$this->m_pipe->teardown();
	}


	/**
	 * calcCrossingAspects
	 */
	function calcCrossingAspectsForNode($data, $start_year, $duration) {
		global $logger;
		$logger->debug("AstrologServices::calcCrossingAspects()");

		/*
		 * determine the aspects
		*/
		$this->m_pipe = new AstrologCalculator();

		/* format the birth data */
		$this->m_pipe->addArgument($data->qaSwitchFormat());

		if ($data->timed_data == false) {
			/* specify whole house system */
			$this->m_pipe->addArgument('-c 12');
		}
		/* include the angles as objects */
		$this->m_pipe->addArgument('=C');

		/* ptolemaic aspects only used */
		$this->m_pipe->addArgument('-A 1');

		/* Time Zone Set up*/
		//http://www.bonniehill.net/pages.aux/astrology/astrolog/Config.DAT
		//$this->m_pipe->addArgument('-z0 ' . $data->m_summertime_offset); 		//Default Daylight time setting   [0 standard, 1 daylight]
		//$this->m_pipe->addArgument('-z ' . $data->m_timezone_offset);  			//Default time zone               [hours before GMT      ]

		/* orbs used */
		$this->m_pipe->addArgument('-YAo 1 5 8.0 8.0 6.0 5.0 5.0');

		/* max permissable orbs */
		$this->m_pipe->addArgument('-YAm  1 10 360 360 360 360 360 360 360 360 360 360');
		$this->m_pipe->addArgument('-YAm 11 20 360 360 360 360 360   3   3   3   3   3');
		$this->m_pipe->addArgument('-YAm 21 32 360 360 360 360 360 360 360 360 360 360 360 360');
		$this->m_pipe->addArgument('-YAm 33 41 360 360 360 360 360 360 360 360 360');

		/* planet aspect orb additions */
		$this->m_pipe->addArgument('-YAd  1 10 1.0 1.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0');
		$this->m_pipe->addArgument('-YAd 11 20 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0');
		$this->m_pipe->addArgument('-YAd 21 32 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0');

		/* just in case the aspect angles are not defined ... */
		// $this->m_pipe->addArgument('-YAa 1 5	0.0 180.0 90.0 120.0 60.0');

		/* manage aspected object settings */
		$this->m_pipe->addArgument('-YR 1 10  1 1 1 1 1 1 1 1 1 1');  		/* main planets enabled */
		$this->m_pipe->addArgument('-YR 11 20 1 1 1 1 1 1 1 1 1 1');  		/* node enabled */
		$this->m_pipe->addArgument('-YR 21 32 0 0 0 0 0 0 0 0 0 0 0 0'); 	/* ascendant/MC enabled */
		$this->m_pipe->addArgument('-YR 33 41 1 1 1 1 1 1 1 1 1');   		/* all else disabled */

		/* manage transiting object settings */
		$this->m_pipe->addArgument('-YRT 1 10  1 1 1 1 1 1 1 1 1 1');
		$this->m_pipe->addArgument('-YRT 11 20 1 1 1 1 1 0 1 1 1 1');
		$this->m_pipe->addArgument('-YRT 21 32 1 1 1 1 1 1 1 1 1 1 1 1');
		$this->m_pipe->addArgument('-YRT 33 41 1 1 1 1 1 1 1 1 1');

		/* request aspect generation */
		$this->m_pipe->addArgument(sprintf("-tY %d %d", $start_year, $duration));

		$this->m_pipe->addArgument('-YR0 0 0');

		/* finally call Astrolog */
		$this->m_pipe->callAstrolog();
		$logger->debug("AstrologServices::calcCrossingAspects: cache=" . $this->m_pipe->getCache());
		$lines = explode("\n", $this->m_pipe->getCache());

		$this->m_crossing = array();
		$this->getCalendarAspects($lines, true, true);
		$this->m_pipe->teardown();
	}
	
	
	/**
	 * calcCrossingAspects
	 */
	function calcHouseCrossingAspects($data, $start_year, $duration) {
		global $logger;
		$logger->debug("AstrologServices::calcCrossingAspects()");
	
		/*
		 * we are going to calculate the planetary positions from the start of
		* the current year to the end of the final (duration+1) year
		*/
		$this->m_ephemeris = array();
		$lines = array();
		for ($i = 0; $i <= $duration; $i++) {

			$this->m_pipe = new AstrologCalculator();

			$this->m_pipe->addArgument(sprintf("-qy %04d", $start_year + $i));
			$this->m_pipe->addArgument("-Ey");   /* whole year     */
			$this->m_pipe->addArgument("-R0");   /* restrict all   */

			$this->m_pipe->addArgument("-R 6");  /* enable jupiter */
			$this->m_pipe->addArgument("-R 7");  /* enable saturn  */
			//$this->m_pipe->addArgument("-P");    /* enable saturn  */
			
			/* finally call Astrolog */
			$this->m_pipe->callAstrolog();
			$lines = array_merge($lines, explode("\n", $this->m_pipe->getCache()));
			$this->m_pipe->teardown();
		}

		$this->SAandJUCrossing = array();
		$this->getEphemerisSAandJU($start_year, $lines);
		$this->m_pipe->teardown();
		//print_r($this->SAandJUCrossing);
	}

	/**
	 * calcHouseCrossingAspects() function calulates House enters and leaves date for Saturn and Jupitar planet
	 * @param $data
	 * @param $start_year
	 * @param $duration
	 */
	function calcHouseCrossingAspects_OLD($data, $start_year, $duration) {
		global $logger;
		$logger->debug("AstrologServices::calcCrossingAspects()");

		/*
		 * determine the aspects
		*/
		$this->m_pipe = new AstrologCalculator();

		/* format the birth data */
		$this->m_pipe->addArgument($data->qaSwitchFormat());

		if ($data->timed_data == false) {
			/* specify whole house system */
			$this->m_pipe->addArgument('-c equal');

			/* specify whole house system */
			$this->m_pipe->addArgument('-c 12');
			$this->m_pipe->addArgument('-sr');

		}

		/* Time Zone Set up*/
		//http://www.bonniehill.net/pages.aux/astrology/astrolog/Config.DAT
		$this->m_pipe->addArgument('-z0 ' . $data->m_summertime_offset); 		//Default Daylight time setting   [0 standard, 1 daylight]
		$this->m_pipe->addArgument('-z ' . $data->m_timezone_offset);  			//Default time zone               [hours before GMT      ]

		/* include the angles as objects */
		$this->m_pipe->addArgument('=C');
			
		/* ptolemaic aspects only used */
		$this->m_pipe->addArgument('-A 5');

		/* orbs used */
		$this->m_pipe->addArgument('-YAo 1 5 9.0 0.0 0.0 0.0 0.0');

		/* request aspect generation */
		$this->m_pipe->addArgument(sprintf("-tY %d %d", $start_year, $duration));

		///FULL COMMAND '-tY 2010 5 -RT0 6 7 -R0 -RC -C';
		$this->m_pipe->addArgument("-RT0 6 7");  // FOR SATURN AND JUPITAR

		$this->m_pipe->addArgument("-R0");  	// RESTRICT ALL OTHERE PLANETS

		$this->m_pipe->addArgument("-RC");  	// RESTRICT ALL OTHERE MINOR PLANETs

		/* finally call Astrolog */
		$this->m_pipe->callAstrolog();
		$logger->debug("AstrologServices::calcCrossingAspects: cache=" . $this->m_pipe->getCache());
		$lines = explode("\n", $this->m_pipe->getCache());

		echo "************ calcHouseCrossingAspects() <br />";
		echo $this->m_pipe->m_args . "<br />";
		//print_r($lines);

		$this->SAandJUCrossing = array();
		$this->getCalendarHouseAspects($lines, true, true);
		$this->m_pipe->teardown();
	}


	/**
	 # - 12/30/2005 10:35am trans Mercury (Sag) Sex natal (Lib) Jupiter
	 # - 12/30/2005 10:35am trans Mercury (Sag) Sex natal (Lib) Midheaven
	 # - 12/30/2005 10:35am trans Mercury (Sag) Sex natal (Lib) Ascendant
	 # - ----------111111111122222222223333333333444444444455555555556666666666777777777
	 # - 0123456789012345678901234567890123456789012345678901234567890123456789012345678
	*/
	function getCalendarAspects($lines, $dynamic = false, $crossing = false) {
		$planets = array(
				"1000" => "Sun",
				"1001" => "Moon",
				"1002" => "Mercury",
				"1003" => "Venus",
				"1004" => "Mars",
				"1005" => "Jupiter",
				"1006" => "Saturn",
				"1007" => "Uranus",
				"1008" => "Neptune",
				"1009" => "Pluto",
				"1010" => "Node",
				"1011" => "S.Node",      /* put here to stop the nag messages, not used */
				"1012" => "Ascendant",
				"1013" => "Midheaven",  /* "MC", */
				"1014" => "IC",
				"1015" => "Descendant",
				"1016" => "Chiron"
		);

		$houses = array(
				"0001" => "Ascendant",
				"0002" => "2nd Cusp",
				"0003" => "3rd Cusp",
				"0004" => "IC",
				"0005" => "5th Cusp",
				"0006" => "6th Cusp",
				"0007" => "Descendant",
				"0008" => "8th Cusp",
				"0009" => "9th Cusp",
				"0010" => "Midheaven",
				"0011" => "11th Cusp",
				"0012" => "12th Cusp"
		);

		$aspects = array(
				"000" => "Con",
				"060" => "Sex",
				"090" => "Squ",
				"120" => "Tri",
				"180" => "Opp"
		);

		/* iterate through the results */
		foreach ($lines as $line) {

			/* manage last (blank) line */
			if (strlen($line) < 1) {
				continue;
			}

			/* look for the transiting planet */
			$trans = trim(substr($line, 25, 8));
			/** AstrologServices::getCalendarAspects - transiting planet = $trans" **/
			for ($i = 0; $i < count($planets); $i++) {
				// 				if ($i >= 11) {
				// 					$i++;
				// 				}
				if ($planets[(1000 + $i)] == $trans) {
					$transvalue = intval((1000 + $i));
					break;
				}
			}

			/*
			 * look for the natal object
			* take special care where returns are encountered
			*/
			if ($crossing === true) {
				$natal = trim(substr($line, 55, 10));
				for ($i = 0; $i < count($houses); $i++) {
					/* if( $i >= 11 ) { $i++; } */
					if ($houses[sprintf("%04d", ($i + 1))] == $natal) {
						$natalvalue = intval($i + 1);
						/** AstrologServices::getCalendarAspects - transited cusp = $natalvalue, transiting planet = $transvalue" **/
						break; /* from for loop */
					}
				}
			} else {
				/* look for the natal object */
				$natal = trim(substr($line, 55, 10));

				/** AstrologServices::getCalendarAspects - transited object = $natal **/
				for ($i = 0; $i < count($planets); $i++) {
					/* if( $i >= 11 ) { $i++; } */
					/**
					 * The following works on the basis that we look for the polled object using the length of the string in the planets table.
					 * This should prevent the (<object> Return) string factoring in the equation
					 */
					if ($planets[sprintf("%04d", (1000 + $i))] == substr($natal, 0, strlen($planets[sprintf("%04d", (1000 + $i))]))) {
						$natalvalue = intval((1000 + $i));
						break;
					} else {
						// oops
					}
				}
			}

			$aspect = trim(substr($line, 39, 3));
			reset($aspects);
			while ($aspectname = current($aspects)) {
				if ($aspectname == $aspect) {
					$aspectvalue = intval(key($aspects));
					break;
				}
				next($aspects);
			}

			if ($dynamic === false) {
				$logger->debug("AstrologServices::getCalendarAspects - static push");
				array_push(
				$this->m_aspect, /* seasonal/calendars */ sprintf("%04d-%02d-%02d %02d:%02d %04d%03d%04d",
				/* date			 */ intval(substr($line, 6, 4)), intval(substr($line, 0, 2)), intval(substr($line, 3, 2)),
				/* time			 */ intval(substr($line, 11, 2)), intval(substr($line, 14, 2)),
				/* transiting    */ $transvalue,
				/* aspect		 */ $aspectvalue,
				/* natal		 */ $natalvalue
				)
				);
			} else {
				if ($crossing === true) {
					array_push(
					$this->m_crossing, /* Jupiter/Saturn house crossing */ sprintf("%04d-%02d-%02d %02d:%02d %04d%03d%04d",
					/* date			 */ intval(substr($line, 6, 4)), intval(substr($line, 3, 2)), intval(substr($line, 0, 2)),
					/* time			 */ intval(substr($line, 11, 2)), intval(substr($line, 14, 2)),
					/* transiting	 */ $transvalue,
					/* aspect		 */ $aspectvalue,
					/* natal		 */ $natalvalue
					)
					);
				} else {
					array_push(
					$this->m_transit, /* Transiting aspects */ sprintf("%04d-%02d-%02d %02d:%02d %04d%03d%04d",
					/* date			 */ intval(substr($line, 6, 4)), intval(substr($line, 3, 2)), intval(substr($line, 0, 2)),
					/* time			 */ intval(substr($line, 11, 2)), intval(substr($line, 14, 2)),
					/* transiting	 */ $transvalue,
					/* aspect		 */ $aspectvalue,
					/* natal		 */ $natalvalue
					)
					);

				}
			}
		}
	}
	
	/**
	 	# => Mo/Dy/Yr  Jupi   Satu 
	    # =>  1/ 1/12  0Ta25 28Li17.
	    # =>  1/ 2/12  0Ta26 28Li20.
	    # =>  1/ 3/12  0Ta28 28Li24. 
	    # =>  1/ 4/12  0Ta30 28Li27
	    # =>  1/ 5/12  0Ta32 28Li31 
		# => 0123456789012345678901234567890123456789012345678901234567890123456789012345678
		
		( [.] dot ) mentioned that its retrograde
	*/
	function getEphemerisSAandJU($start_date, $lines) {
		global $logger;
		$logger->debug("AstrologServices::getEphemeris");
		foreach ($lines as $line) {
			/* look for main ephemeris data lines only including pluto retrograde flag */
			if (strlen($line) == 23) {
				/* get the date */
				$edate = $this->getEphemerisDataSAandJU($start_date, $line);
				$date = sprintf("%04d%02d%02d", $edate['year'], $edate['month'], $edate['day']);
				$this->SAandJUCrossing[$date] = $edate['planets'];
			}
		}
	}
	
	function getEphemerisDataSAandJU($start_date, $line) {
	
		global $logger;
		$logger->debug("AstrologServices::getEphemerisData");
	
		/* date related */
		$month = intval(trim(substr($line, 0, 2)));
		$day = intval(trim(substr($line, 3, 2)));
		$year = intval(trim(substr($line, 6, 2)));
		$year1 = intval(trim(substr($start_date, 0, 2)));
		$FinalYear = sprintf("%02d%02d", $year1, $year);
		
// 		if($FinalYear >= 2014 && $FinalYear <= 2014) {
// 			echo "$FinalYear-$month-$day -> $line<br />";
// 		}
		
		/* planet related */
		$planet['Jupiter'] = $this->getEphemerisObjectDataSAandJU($start_date, substr($line, 9, 7));
		$planet['Saturn'] = $this->getEphemerisObjectDataSAandJU($start_date, substr($line, 16, 7));
	
		return array (
				//'year' => (2000 + $year),
				'year' => $FinalYear,
				'month' => sprintf("%02d", $month),
				'day' => sprintf("%02d", $day),
				'planets' => $planet
		);
	}


	function getEphemerisObjectDataSAandJU($start_date, $line) {
		global $logger;
		
		$logger->debug("AstrologServices::getEphemerisObjectData");
	
		$signs = array('Ar' => 0, 'Ta' => 30, 'Ge' => 60, 'Cn' => 90, 'Le' => 120, 'Vi' => 150,
				'Li' => 180, 'Sc' => 210, 'Sg' => 240, 'Cp' => 270, 'Aq' => 300, 'Pi' => 330
		);
		
		$signnoarray = array('Ar' => 0, 'Ta' => 1, 'Ge' => 2, 'Cn' => 3, 'Le' => 4, 'Vi' => 5,
				'Li' => 6, 'Sc' => 7, 'Sg' => 8, 'Cp' => 9, 'Aq' => 10, 'Pi' => 11
		);	
	
		/* extract sign */
		$sign = substr($line, 2, 2);
		$signoffset = $signs[$sign];		
		$signno = $signnoarray[$sign];
		
		if ($signoffset < 0 || $signoffset > 330) {
			// error
		}
	
		/* determine degree */
		$degree = 360 + $signoffset + intval(trim(substr($line, 0, 2)));
		$minute = intval(trim(substr($line, 4, 2)));
		$minute = intval(floatval($minute) * (100.0 / 60.0));
		$position = intval(trim(substr($line, 0, 2))) . "." .intval(trim(substr($line, 4, 2)));
		
		/* determine motion */
		$retrograde = ( substr($line, 6, 1) == '.' ) ? true : false;
	
		return array(
				'position' => floatval( trim ($position) ),
				'longitude' => $degree + ($minute / 100.0),
				'retrograde' => $retrograde,
				'signno' => $signno,
				'signname' => $sign
		);
	}
}