<?php
/**
 * AstrologAPIBridge
 *
 * Communication bridge between Astrolog and PHP script
 *
 * @package AstrologAPIBridge
 *
 * @author Amit Parmar <amit@n-techcorporate.com>
 * @version 1.0
 */

class AstrologAPIBridge {

	/**
	 * @var $bridge_pipe
	 */
	var $bridge_pipe;

	/**
	 * Holds data for Its SOLAR or PLACADIAS
	 * Known OR UnKnown time
	 * Defaults: YES
	 * @var $bridge_timeddata
	 */
	var $bridge_timeddata;

	/**
	 * Planet Objects
	 * @var $bridge_object
	 */
	var $bridge_object;

	/**
	 * Aspects number/name
	 * @var $bridge_aspect
	 */
	var $bridge_aspect;

	/**
	 *
	 * Transits number/name
	 * @var $bridge_transit
	 */
	var $bridge_transit;

	/**
	 * Houser cusp or crossings.
	 * @var bridge_crossings
	 */
	var $bridge_crossings;

	/**
	 * List of ephemeris for sevral years
	 * @var $bridge_ephemeris
	 */
	var $bridge_ephemeris;

	/**
	 * Date wise Transit List
	 * @var $bridge_transit_window;
	 */
	var $bridge_transit_window;

	/**
	 * Constructor
	 *
	 * @param Class: Birth data object
	 * @param Bool: Calculate Chart data =
	 * @return AstrologAPIBridge
	 */
	function AstrologAPIBridge($BirthData, $IsNotSolarChart = true) {

		$this->bridge_timeddata = $BirthData->timed_data;

		if ($IsNotSolarChart === true) {

			if ($this->bridge_timeddata === true)
			{
				$this->CalculatePlacidusChart( $BirthData );
			}
			else
			{
				$this->CalculateSolarChart ( $BirthData );
			}
			$this->CalculateAspects ( $BirthData );
		}
	}

	/**
	 * Calculate a basic chart
	 *
	 * Calculate a basic chart based on chart data parameter set
	 *
	 * @param mixed Chart data
	 * @result void Calculated chart data contained within object instance
	 */
	function CalculatePlacidusChart($BirthData) {

		$this->bridge_pipe = new AstrologInvocationPipe ();

		//Data formatted to understand by ASTROLOG commandline
		$this->bridge_pipe->addArgument ( $BirthData->qaSwitchFormat () );

		//Location Diplay format : Degree
		$this->bridge_pipe->addArgument ( '-sd' );

		//Finally calling commandline to execute the command
		$this->bridge_pipe->callAstrolog ();
		$lines = explode ( "\n", $this->bridge_pipe->getCache () );

		for($line = 0; $line < count ( $lines ); $line ++) {
			$this->getPlanetaryLongitude ( $lines [$line] );
		}
		foreach ( $lines as $line ) {
			$this->getHouseCuspLongitude ( $line );
		}
		$this->bridge_pipe->teardown ();
	}

	/**
	 * CalculateSolarChart
	 *
	 * Untimed charts are calculated by canculating a noon chart for the date of
	 * birth and then adjusting the chart so that the 1st house cusp lies at the
	 * start of the Sun sign. An equal house system is used. All occupancy for
	 * planets in houses is adjusted to new house configuration
	 *
	 * @param BirthData $BirthData
	 */
	function CalculateSolarChart($BirthData) {

		$this->bridge_pipe = new AstrologInvocationPipe ();

		//Formating Birth Data and force NOON CHART = Equal House chart
		$this->bridge_pipe->addArgument ( $BirthData->qaSwitchFormat () );

		//House system type
		$this->bridge_pipe->addArgument ( '-c 12' );

		//Display Location type : Degree
		$this->bridge_pipe->addArgument ( '-sd' );

		//Finally executing Commandline
		$this->bridge_pipe->callAstrolog ();

		$lines = explode ( "\n", $this->bridge_pipe->getCache () );
		for($line = 0; $line < count ( $lines ); $line ++) {
			$this->getPlanetaryLongitude ( $lines [$line] );
		}
		foreach ( $lines as $line ) {
			$this->getHouseCuspLongitude ( $line );
		}
		$this->bridge_pipe->teardown ();
	}

	/**
	 *
	 * @param BirthData Object $BirthData
	 */
	function CalculateAspects($BirthData) {
		$this->bridge_pipe = new AstrologInvocationPipe ();

		// Formating Birth data
		$this->bridge_pipe->addArgument ( $BirthData->qaSwitchFormat () );
		
		if ($this->bridge_timeddata === true) {		
		} else {
			//House system type
			$this->bridge_pipe->addArgument ( '-c 12' );
		}
		
		//Setting up the ANGLES as objects
		$this->bridge_pipe->addArgument ( '=C' );

		//Setup Aspects Type to PTOLEMAIC
		$this->bridge_pipe->addArgument ( '-A 5' );

		//SETTING UP THE ORBS
		//We prefered to set as normal or standard orbs setting
		$this->bridge_pipe->addArgument ( '-YAo 1 5	 9.0  9.0 6.0  6.0 4.0' ); 		//STANARD
		//$this->bridge_pipe->addArgument('-YAo 1 5	 5.0  4.0 3.0  2.0 2.0');		//STRONG
		//$this->bridge_pipe->addArgument('-YAo 1 5	 9.0  8.0 6.0  6.0 4.0');		//MEDIUM
		//$this->bridge_pipe->addArgument('-YAo 1 5	14.0 12.0 9.0 11.0 7.0');		//WEAK

		//MAXIMUM ORBS setup, Orbs Must not go beyond below setting
		$this->bridge_pipe->addArgument ( '-YAm 1 10	360 360 360 360 360 360 360 360 360 360' );
		$this->bridge_pipe->addArgument ( '-YAm 11 20	360 360 360 360 360   3   3   3   3   3' );
		$this->bridge_pipe->addArgument ( '-YAm 21 32	360 360 360 360 360 360 360 360 360 360 360 360' );
		$this->bridge_pipe->addArgument ( '-YAm 33 41	360 360 360 360 360 360 360 360 360' );

		//Planet aspect orb, This is additioinal setting to setup the orb for planet
		$this->bridge_pipe->addArgument ( '-YAd 1 10	0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0' );
		$this->bridge_pipe->addArgument ( '-YAd 11 20	0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0' );
		$this->bridge_pipe->addArgument ( '-YAd 21 32	0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0' );

		//ALLOWED aspected object planet, house and node
		$this->bridge_pipe->addArgument ( '-YR  1 10 0 0 0 0 0 0 0 0 0 0' ); /* main planets enabled */
		$this->bridge_pipe->addArgument ( '-YR 11 20 1 1 1 1 1 0 1 1 1 1' ); /* node enabled */
		$this->bridge_pipe->addArgument ( '-YR 21 32 0 1 1 1 1 1 1 1 1 1 1 1' ); /* ascendant enabled */
		$this->bridge_pipe->addArgument ( '-YR 33 41 1 1 1 1 1 1 1 1 1' ); /* all else disabled */

		//SET Request type to generate only ASPECT
		$this->bridge_pipe->addArgument ( '-a' );

		//EXECUTE the final command to get list of Aspect
		$this->bridge_pipe->callAstrolog ();
		$lines = explode ( "\n", $this->bridge_pipe->getCache () );
		echo "<pre>";
		//echo $this->bridge_pipe->m_args . "<br />";
		//print_r($lines);
		
		$this->getAspects ( $lines );
		$this->bridge_pipe->teardown ();
	}

	/**
	 * CalculateDynamicAspects
	 *
	 * Calculate transits to a basic chart.
	 *
	 * The transits are cached in the bridge_transits array and cusp crossings in the bride_crossings array.
	 *
	 * @param BirthData $BirthData
	 * @param int $StartYear
	 * @param int $Duration
	 */
	function CalculateDynamicAspects($BirthData, $StartYear, $Duration) {
		/**
		 * create the Astrolog low level API instance
		 */
		$this->bridge_pipe = new AstrologInvocationPipe ();

		/* format the birth data */
		$this->bridge_pipe->addArgument ( $BirthData->qaSwitchFormat () );

		/* include the angles as objects */
		$this->bridge_pipe->addArgument ( '=C' );

		/* ptolemaic aspects only used */
		$this->bridge_pipe->addArgument ( '-A 5' );

		/* orbs used */
		$this->bridge_pipe->addArgument ( '-YAo 1 5 8.0 8.0 6.0 5.0 5.0' );

		/* max permissable orbs */
		$this->bridge_pipe->addArgument ( '-YAm  1 10 360 360 360 360 360 360 360 360 360 360' );
		$this->bridge_pipe->addArgument ( '-YAm 11 20 360 360 360 360 360   3   3   3   3   3' );
		$this->bridge_pipe->addArgument ( '-YAm 21 32 360 360 360 360 360 360 360 360 360 360 360 360' );
		$this->bridge_pipe->addArgument ( '-YAm 33 41 360 360 360 360 360 360 360 360 360' );

		/* planet aspect orb additions */
		$this->bridge_pipe->addArgument ( '-YAd  1 10 1.0 1.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0' );
		$this->bridge_pipe->addArgument ( '-YAd 11 20 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0' );
		$this->bridge_pipe->addArgument ( '-YAd 21 32 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0' );

		/* just in case the aspect angles are not defined ... */
		// $this->bridge_pipe->addArgument('-YAa 1 5	0.0 180.0 90.0 120.0 60.0');


		/* manage aspected object settings */
		$this->bridge_pipe->addArgument ( '-YR  1 10 0 0 0 0 0 0 0 0 0 0' ); /* main planets enabled */
		$this->bridge_pipe->addArgument ( '-YR 11 20 1 1 1 1 1 0 1 1 1 1' ); /* node enabled */
		$this->bridge_pipe->addArgument ( '-YR 21 32 0 1 1 1 1 1 1 1 1 0 1 1' ); /* ascendant/MC enabled */
		$this->bridge_pipe->addArgument ( '-YR 33 41 1 1 1 1 1 1 1 1 1' ); /* all else disabled */

		/* manage transiting object settings */
		$this->bridge_pipe->addArgument ( '-YRT 1 10  1 1 1 1 1 0 0 0 0 0' );
		$this->bridge_pipe->addArgument ( '-YRT 11 20 1 1 1 1 1 1 1 1 1 1' );
		$this->bridge_pipe->addArgument ( '-YRT 21 32 1 1 1 1 1 1 1 1 1 1 1 1' );
		$this->bridge_pipe->addArgument ( '-YRT 33 41 1 1 1 1 1 1 1 1 1' );

		/* request aspect generation */
		$this->bridge_pipe->addArgument ( sprintf ( "-tY %d %d", $StartYear, $Duration ) );

		/* finally call Astrolog */
		$this->bridge_pipe->callAstrolog ();
		$lines = explode ( "\n", $this->bridge_pipe->getCache () );

		$this->bridge_transit = array ();
		$this->getCalendarAspects ( $lines, true, false );
		$this->bridge_pipe->teardown ();
	}

	/**
	 * calcTransitEntryPoint
	 * Subtract the orb value from the planetary longitude and search for transits from
	 * one planet to that point
	 */
	function calcTransitEntryPoint() {

		/*
		 * determine the aspects
		*/
		$this->bridge_pipe = new AstrologInvocationPipe ();

		/* format the birth data */
		$this->bridge_pipe->addArgument ( $BirthData->qaSwitchFormat () );

		/* include the angles as objects */
		$this->bridge_pipe->addArgument ( '=C' );

		/* ptolemaic aspects only used */
		$this->bridge_pipe->addArgument ( '-A 5' );

		/*
		 * orbs used
		* here we section out the orbs we don't want by setting them to -1
		*/
		$this->bridge_pipe->addArgument ( '-YAo 1 5 -1 -1 -1 -1 -1' );
		$this->bridge_pipe->addArgument ( '-Ao 1 5 -1 -1 -1 -1 -1' );

		/* max permissable orbs */
		$this->bridge_pipe->addArgument ( '-YAm  1 10 360 360 360 360 360 360 360 360 360 360' );
		$this->bridge_pipe->addArgument ( '-YAm 11 20 360 360 360 360 360   3   3   3   3   3' );
		$this->bridge_pipe->addArgument ( '-YAm 21 32 360 360 360 360 360 360 360 360 360 360 360 360' );
		$this->bridge_pipe->addArgument ( '-YAm 33 41 360 360 360 360 360 360 360 360 360' );

		/* planet aspect orb additions */
		$this->bridge_pipe->addArgument ( '-YAd  1 10 1.0 1.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0' );
		$this->bridge_pipe->addArgument ( '-YAd 11 20 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0' );
		$this->bridge_pipe->addArgument ( '-YAd 21 32 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0' );

	}

	Function calcCalendarAspects($BirthData, $StartYear, $Duration) {
		/*
		 * determine the aspects
		*/
		$this->bridge_pipe = new AstrologInvocationPipe ();

		/* format the birth data */
		$this->bridge_pipe->addArgument ( $BirthData->qaSwitchFormat () );

		/* include the angles as objects */
		$this->bridge_pipe->addArgument ( '=C' );

		/* ptolemaic aspects only used */
		$this->bridge_pipe->addArgument ( '-A 5' );

		/* orbs used */
		$this->bridge_pipe->addArgument ( '-YAo 1 5 8.0 8.0 6.0 5.0 5.0' );

		/* max permissable orbs */
		$this->bridge_pipe->addArgument ( '-YAm  1 10 360 360 360 360 360 360 360 360 360 360' );
		$this->bridge_pipe->addArgument ( '-YAm 11 20 360 360 360 360 360   3   3   3   3   3' );
		$this->bridge_pipe->addArgument ( '-YAm 21 32 360 360 360 360 360 360 360 360 360 360 360 360' );
		$this->bridge_pipe->addArgument ( '-YAm 33 41 360 360 360 360 360 360 360 360 360' );

		/* planet aspect orb additions */
		$this->bridge_pipe->addArgument ( '-YAd  1 10 1.0 1.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0' );
		$this->bridge_pipe->addArgument ( '-YAd 11 20 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0' );
		$this->bridge_pipe->addArgument ( '-YAd 21 32 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0' );

		/* just in case the aspect angles are not defined ... */
		// $this->bridge_pipe->addArgument('-YAa 1 5	0.0 180.0 90.0 120.0 60.0');


		/* manage aspected object settings */
		$this->bridge_pipe->addArgument ( '-YR  1 10 0 0 0 0 0 0 0 0 0 0' ); /* main planets enabled */
		$this->bridge_pipe->addArgument ( '-YR 11 20 1 1 1 1 1 0 1 1 1 1' ); /* node enabled */
		$this->bridge_pipe->addArgument ( '-YR 21 32 0 1 1 1 1 1 1 1 1 0 1 1' ); /* ascendant/MC enabled */
		$this->bridge_pipe->addArgument ( '-YR 33 41 1 1 1 1 1 1 1 1 1' ); /* all else disabled */

		/* manage transiting object settings */
		$this->bridge_pipe->addArgument ( '-YRT 1 10  0 1 0 0 0 0 0 0 0 0' );
		$this->bridge_pipe->addArgument ( '-YRT 11 20 1 1 1 1 1 1 1 1 1 1' );
		$this->bridge_pipe->addArgument ( '-YRT 21 32 1 1 1 1 1 1 1 1 1 1 1 1' );
		$this->bridge_pipe->addArgument ( '-YRT 33 41 1 1 1 1 1 1 1 1 1' );

		/* request aspect generation */
		$this->bridge_pipe->addArgument ( sprintf ( "-tY %d %d", $StartYear, $Duration ) );

		/* finally call Astrolog */
		$this->bridge_pipe->callAstrolog ();
		$lines = explode ( "\n", $this->bridge_pipe->getCache () );
		$this->bridge_aspect = array ();
		$this->getCalendarAspects ( $lines, false, false );
		$this->bridge_pipe->teardown ();
	}

	/**
	 * calcCrossingAspects
	 */
	function calcCrossingAspects($BirthData, $StartYear, $Duration) {
		/*
		 * determine the aspects
		*/
		$this->bridge_pipe = new AstrologInvocationPipe ();

		/* format the birth data */
		$this->bridge_pipe->addArgument ( $BirthData->qaSwitchFormat () );

		/* include the angles as objects */
		$this->bridge_pipe->addArgument ( '=C' );

		/* ptolemaic aspects only used */
		$this->bridge_pipe->addArgument ( '-A 1' );

		/* orbs used */
		$this->bridge_pipe->addArgument ( '-YAo 1 5 8.0 8.0 6.0 5.0 5.0' );

		/* max permissable orbs */
		$this->bridge_pipe->addArgument ( '-YAm  1 10 360 360 360 360 360 360 360 360 360 360' );
		$this->bridge_pipe->addArgument ( '-YAm 11 20 360 360 360 360 360   3   3   3   3   3' );
		$this->bridge_pipe->addArgument ( '-YAm 21 32 360 360 360 360 360 360 360 360 360 360 360 360' );
		$this->bridge_pipe->addArgument ( '-YAm 33 41 360 360 360 360 360 360 360 360 360' );

		/* planet aspect orb additions */
		$this->bridge_pipe->addArgument ( '-YAd  1 10 1.0 1.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0' );
		$this->bridge_pipe->addArgument ( '-YAd 11 20 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0' );
		$this->bridge_pipe->addArgument ( '-YAd 21 32 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0' );

		/* just in case the aspect angles are not defined ... */
		// $this->bridge_pipe->addArgument('-YAa 1 5	0.0 180.0 90.0 120.0 60.0');


		/* manage aspected object settings */
		$this->bridge_pipe->addArgument ( '-YR  1 10 1 1 1 1 1 1 1 1 1 1' ); /* main planets enabled */
		$this->bridge_pipe->addArgument ( '-YR 11 20 1 1 1 1 1 1 1 1 1 1' ); /* node enabled */
		$this->bridge_pipe->addArgument ( '-YR 21 32 0 0 0 0 0 0 0 0 0 0 0 0' ); /* ascendant/MC enabled */
		$this->bridge_pipe->addArgument ( '-YR 33 41 1 1 1 1 1 1 1 1 1' ); /* all else disabled */

		/* manage transiting object settings */
		$this->bridge_pipe->addArgument ( '-YRT 1 10  1 1 1 1 1 0 0 1 1 1' );
		$this->bridge_pipe->addArgument ( '-YRT 11 20 1 1 1 1 1 1 1 1 1 1' );
		$this->bridge_pipe->addArgument ( '-YRT 21 32 1 1 1 1 1 1 1 1 1 1 1 1' );
		$this->bridge_pipe->addArgument ( '-YRT 33 41 1 1 1 1 1 1 1 1 1' );

		/* request aspect generation */
		$this->bridge_pipe->addArgument ( sprintf ( "-tY %d %d", $StartYear, $Duration ) );

		/* finally call Astrolog */
		$this->bridge_pipe->callAstrolog ();
		$lines = explode ( "\n", $this->bridge_pipe->getCache () );
		$this->bridge_crossings = array ();
		$this->getCalendarAspects ( $lines, true, true );
		$this->bridge_pipe->teardown ();
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
	 * @param int $Duration up to 3 years
	 */
	function calcEphemeris($StartYear, $Duration) {
		/*
		 * we are going to calculate the planetary positions from the start of
		* the current year to the end of the final (duration+1) year
		*/
		$this->bridge_ephemeris = array ();
		$lines = array ();
		for($i = 0; $i <= $Duration; $i ++) {

			$this->bridge_pipe = new AstrologInvocationPipe ();

			$this->bridge_pipe->addArgument ( sprintf ( "-qy %04d", $StartYear + $i ) );
			$this->bridge_pipe->addArgument ( "-Ey" ); /* whole year     */
			$this->bridge_pipe->addArgument ( "-R0" ); /* restrict all   */
			$this->bridge_pipe->addArgument ( "-R 6" ); /* enable jupiter */
			$this->bridge_pipe->addArgument ( "-R 7" ); /* enable saturn  */
			$this->bridge_pipe->addArgument ( "-R 8" ); /* enable uranus  */
			$this->bridge_pipe->addArgument ( "-R 9" ); /* enable neptune */
			$this->bridge_pipe->addArgument ( "-R 10" ); /* enable pluto   */

			/* finally call Astrolog */
			$this->bridge_pipe->callAstrolog ();
			$lines = array_merge ( $lines, explode ( "\n", $this->bridge_pipe->getCache () ) );
			$this->bridge_pipe->teardown ();
		}

		$this->getEphemeris ( $StartYear, $lines );
	}

	function calcACG($BirthData) {
		/*
		 * determine the planetary and house cusp longitudes along with related
		* context information
		*/
		$this->bridge_pipe = new AstrologInvocationPipe ();

		/* format the birth data */
		$this->bridge_pipe->addArgument ( $BirthData->qaSwitchFormat () );

		/* set step size */
		$this->bridge_pipe->addArgument ( '-L 1' );

		/* finally call Astrolog */
		$this->bridge_pipe->callAstrolog ();
		$lines = explode ( "\n", $this->bridge_pipe->getCache () );

		$acg_mc = array ();
		$acg_as = array ();
		$acg_ds = array ();

		foreach ( $lines as $line ) {
			/* look for the MC lines */
			if (substr ( $line, 0, 7 ) == 'Midheav') {
				/* scan the line */
				//				found MC - $line"
				//				add planet conjunct MS lines
				for($i = 0; $i < 10; $i ++) {
					$longitude = intval ( substr ( $line, (9 + ($i * 4)), 3 ) );
					$hemisphere = substr ( $line, (12 + ($i * 4)), 1 );
					if ($hemisphere == 'w') {
						$longitude = 0 - $longitude;
					}
					array_push ( $acg_mc, array ("planet" => intval ( $i ), "longitude" => intval ( $longitude ), "valid" => true ) );
				}
			}
			/* look for the Asc lines */
			if (substr ( $line, 0, 3 ) == 'Asc') {

				$latitude = intval ( trim ( substr ( $line, 4, 2 ) ) );

				$hemi_ns = substr ( $line, 6, 1 );

				if ($hemi_ns == 's') {
					$latitude = 0 - $latitude;
				}
				for($i = 0; $i < 10; $i ++) {
					$longitude = trim ( substr ( $line, (9 + ($i * 4)), 3 ) );
					$hemisphere = trim ( substr ( $line, (12 + ($i * 4)), 1 ) );
					if ($longitude != '--') {
						if ($hemisphere == 'w') {
							$longitude = 0 - $longitude;
						}
						$valid = true;
					} else {
						$valid = false;
					}
					array_push ( $acg_as, array ("planet" => $i, "longitude" => intval ( $longitude ), "latitude" => intval ( $latitude ), "valid" => $valid ) );
				}
			}
			/* look for the Dsc lines */
			if (substr ( $line, 0, 3 ) == 'Dsc') {
				$latitude = intval ( trim ( substr ( $line, 4, 2 ) ) );
				$hemi_ns = substr ( $line, 6, 1 );
				if ($hemi_ns == 's') {
					$latitude = 0 - $latitude;
				}
				for($i = 0; $i < 10; $i ++) {
					$longitude = trim ( substr ( $line, (9 + ($i * 4)), 3 ) );
					$hemisphere = trim ( substr ( $line, (12 + ($i * 4)), 1 ) );
					if ($longitude != '--') {
						if ($hemisphere == 'w') {
							$longitude = 0 - $longitude;
						}
						$valid = true;
					} else {
						$valid = false;
					}
					array_push ( $acg_ds, array ("planet" => $i, "longitude" => $longitude, "latitude" => $latitude, "valid" => $valid ) );
				}
			}
		}
		return array ("mc" => $acg_mc, "as" => $acg_as, "ds" => $acg_ds );
	}

	function useStandardOrbSetting() {

		/* use minimum (angle) setting */
		$this->bridge_pipe->addArgument ( '-YAo 1 5 4.0 4.0 4.0 4.0 4.0' ); /* base setting for ptolemaic aspects	*/

		/* max permissable orbs */
		$this->bridge_pipe->addArgument ( '-YAm  1 10 360 360 360 360 360 360 360 360 360 360' );
		$this->bridge_pipe->addArgument ( '-YAm 11 20 360 360 360 360 360   3   3   3   3   3' );
		$this->bridge_pipe->addArgument ( '-YAm 21 32 360 360 360 360 360 360 360 360 360 360 360 360' );
		$this->bridge_pipe->addArgument ( '-YAm 33 41 360 360 360 360 360 360 360 360 360' );

		/* planet aspect orb additions */
		$this->bridge_pipe->addArgument ( '-YAd  1 10 5.0 5.0 5.0 5.0 5.0 5.0 5.0 5.0 5.0 5.0' ); /* lift planets to 9 degrees	*/
		$this->bridge_pipe->addArgument ( '-YAd 11 20 0.0 0.0 0.0 0.0 0.0 2.0 0.0 0.0 0.0 0.0' ); /* lift node to 6 degrees		*/
		$this->bridge_pipe->addArgument ( '-YAd 21 32 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0' ); /* angles already at 4 degrees	*/
	}

	/*
	 #----------111111111122222222223333333333444444444455555555556666666666777777777
	#0123456789012345678901234567890123456789012345678901234567890123456789012345678
	#Jupiter   : 144.508 R + 1:11' (-) [ 9th] [R-] -0.120  -  House cusp  6:  41.058
	*/
	function getPlanetaryLongitude($line) {

		$planet = trim ( substr ( $line, 0, 10 ) );
		switch ($planet) {
			case 'Sun' :
			case 'Moon' :
			case 'Mercury' :
			case 'Venus' :
			case 'Mars' :
			case 'Jupiter' :
			case 'Saturn' :
			case 'Uranus' :
			case 'Neptune' :
			case 'Pluto' :
				$this->bridge_object [$planet] ['longitude'] = floatval ( trim ( substr ( $line, 12, 7 ) ) );
				$this->bridge_object [$planet] ['retrograde'] = ((substr ( $line, 20, 1 ) == 'R') ? true : false);
				$this->bridge_object [$planet] ['house'] = intval ( trim ( substr ( $line, 35, 2 ) ) );
				$this->bridge_object [$planet] ['solar'] = intval ( (($this->bridge_object [$planet] ['house'] + 12 - $this->bridge_object ['Sun'] ['house']) % 12) + 1 );
				break;
			case 'Node':
				/* North Node */
				$this->bridge_object ['N' . $planet] ['longitude'] = floatval ( trim ( substr ( $line, 12, 7 ) ) );
				$this->bridge_object ['N' . $planet] ['retrograde'] = ((substr ( $line, 20, 1 ) == 'R') ? true : false);
				$this->bridge_object ['N' . $planet] ['house'] = intval ( trim ( substr ( $line, 35, 2 ) ) );
				/* South node */
				if ($this->bridge_object ['N' . $planet] ['longitude'] >= 180.0) {
					$this->bridge_object ['S' . $planet] ['longitude'] = $this->bridge_object ['N' . $planet] ['longitude'] - 180.0;
				} else {
					$this->bridge_object ['S' . $planet] ['longitude'] = $this->bridge_object ['N' . $planet] ['longitude'] + 180.0;
				}
				$this->bridge_object ['S' . $planet] ['retrograde'] = $this->bridge_object ['N' . $planet] ['retrograde'];
				if ($this->bridge_object ['N' . $planet] ['house'] > 6) {
					$this->bridge_object ['S' . $planet] ['house'] = $this->bridge_object ['N' . $planet] ['house'] - 6;
				} else {
					$this->bridge_object ['S' . $planet] ['house'] = $this->bridge_object ['N' . $planet] ['house'] + 6;
				}
				break;
			default :
				return;
		}
	}

	/*
	 #----------111111111122222222223333333333444444444455555555556666666666777777777
	#0123456789012345678901234567890123456789012345678901234567890123456789012345678
	#Jupiter   : 144.508 R + 1:11' (-) [ 9th] [R-] -0.120  -  House cusp  6:  41.058
	*/
	function getHouseCuspLongitude($line) {

		/*
		 * TODO: manage untimed charts
		* - Ascendant set to initial degree of Sun Sign
		* - equal houses used
		*/
		if (trim ( substr ( $line, 57, 10 ) ) == 'House cusp') {
			if ($this->bridge_timeddata === true) {
				$this->bridge_object ['cusp'] [intval ( trim ( substr ( $line, 68, 2 ) ) )] = floatval ( trim ( substr ( $line, 72, 7 ) ) );
			} else {
				/*
				 * Cusp  1 = 30 * Sun sign
				* so 342 degrees = 11
				* add 12 = 23, subtract
				*/
				$cusp = intval ( trim ( substr ( $line, 68, 2 ) ) );
				$suncusp = intval ( ($this->bridge_object ['Sun'] ['longitude'] / 30.0) );
				$this->bridge_object ['cusp'] [$cusp] = ((($suncusp * 30) + (($cusp - 1) * 30)) % 360);
			}
		}
	}

	/*
	 #----------111111111122222222223333333333444444444455555555556666666666777777777
	#0123456789012345678901234567890123456789012345678901234567890123456789012345678
	#  1:  Saturn [Lib] Con [Lib] Neptune    - orb: +0:26' - power: 24.14
	*/
	function getAspects($lines) {		
		$this->bridge_aspect = array ();
		$planets = array ("1000" => "Sun", "1001" => "Moon", "1002" => "Mercury", "1003" => "Venus", "1004" => "Mars", "1005" => "Jupiter", "1006" => "Saturn", "1007" => "Uranus", "1008" => "Neptune", "1009" => "Pluto", "1010" => "Node", "1012" => "Ascendant", "1013" => "Midheaven",	/* "MC", */
				"1014" => "IC", "1015" => "Descendant" );
		$aspects = array ("000" => "Con", "060" => "Sex", "090" => "Squ", "120" => "Tri", "180" => "Opp" );

		/*
		 *iterate through the results
		*/
		for($line = 0; $line < count ( $lines ); $line ++) {

			/* skip the lead-in */
			if (substr ( $lines [$line], 3, 1 ) != ':') {
				continue;
			}

			/*
			 * isolate the subject
			*/
			$subject = trim ( substr ( $lines [$line], 4, 8 ) );
			$subjectvalue = 0;
			for($i = 0; $i < count ( $planets ); $i ++) {
				if ($i >= 11) {
					$i ++;
				}
				if ($planets [(1000 + $i)] == $subject) {
					$subjectvalue = intval ( (1000 + $i) );
					break;
				}
			}

			/*
			 * isolate the aspect
			* - added orb here for the wheel's aspect grid
			*/
			$aspect = trim ( substr ( $lines [$line], 18, 5 ) );
			$aspect_orb = trim ( substr ( $lines [$line], 47, 5 ) );
			$aspectvalue = - 1;
			reset ( $aspects );
			while ( $aspectname = current ( $aspects ) ) {
				if ($aspectname == $aspect) {
					$aspectvalue = intval ( key ( $aspects ) );
					break;
				}
				next ( $aspects );
			}

			/*
			 * isolate the object
			*/
			$object = trim ( substr ( $lines [$line], 28, 11 ) );
			$objectvalue = 0;
			for($i = 0; $i < count ( $planets ); $i ++) {

				if ($i >= 11) {
					$i ++;
				}

				/* look for a string match */
				if ($planets [(1000 + $i)] == $object) {
					if ($object == 'Ascendant') {

						$objectvalue = $subjectvalue;
						$subjectvalue = intval ( (1000 + $i) );
					} else {
						$objectvalue = intval ( (1000 + $i) );
					}
					break;
				} else {
					// something has appeared that we are not prepared for ... error */
				}
			}

			/*
			 * store the aspect
			* @todo vet the orbs and restrict further if required
			*/
			if ($subjectvalue != 0 and $aspectvalue != - 1 and $objectvalue != 0) {
				/* tack the orb to the end for the wheel's aspect grid */
				array_push ( $this->bridge_aspect, sprintf ( "%04d%03d%04d %s", $subjectvalue, $aspectvalue, $objectvalue, $aspect_orb ) );
			} else {
				//"failed to parse aspect line, line=" . $lines [$line]
				//"failed to parse aspect line"
			}
		}
	}

	/*
	 #12/30/2005 10:35am trans Mercury (Sag) Sex natal (Lib) Jupiter
	#12/30/2005 10:35am trans Mercury (Sag) Sex natal (Lib) Midheaven
	#12/30/2005 10:35am trans Mercury (Sag) Sex natal (Lib) Ascendant
	#----------111111111122222222223333333333444444444455555555556666666666777777777
	#0123456789012345678901234567890123456789012345678901234567890123456789012345678
	*/
	function getCalendarAspects($lines, $dynamic = false, $crossing = false) {
		global $AspectRank;
		global $NatalPlanetRank;
		global $TransitingPlanetRank;		
		
		$planets = array ("1000" => "Sun", "1001" => "Moon", "1002" => "Mercury", "1003" => "Venus", "1004" => "Mars", "1005" => "Jupiter", "1006" => "Saturn", "1007" => "Uranus", "1008" => "Neptune", "1009" => "Pluto", "1010" => "Node", "1011" => "SNode",		/* put here to stop the nag messages, not used */
				"1012" => "Ascendant", "1013" => "Midheaven",	/* "MC", */
				"1014" => "IC", "1015" => "Descendant" );
		
		$houses = array ("0001" => "Ascendant", "0002" => "2nd Cusp", "0003" => "3rd Cusp", "0004" => "IC", "0005" => "5th Cusp", "0006" => "6th Cusp", "0007" => "Descendant", "0008" => "8th Cusp", "0009" => "9th Cusp", "0010" => "Midheaven", "0011" => "11th Cusp", "0012" => "12th Cusp" );
		$aspects = array ("000" => "Con", "060" => "Sex", "090" => "Squ", "120" => "Tri", "180" => "Opp" );
		
		/* iterate through the results */
		foreach ( $lines as $line ) {
			/* manage last (blank) line */
			if (strlen ( $line ) < 1) {
				continue;
			}

			/* look for the transiting planet */
			$trans = trim ( substr ( $line, 25, 8 ) );
			for($i = 0; $i < count ( $planets ); $i ++) {
				if ($i >= 11) {
					$i ++;
				}
				if ($planets [(1000 + $i)] == $trans) {
					$transvalue = intval ( (1000 + $i) );
					break;
				}
			}

			/*
			 * look for the natal object
			* take special care where returns are encountered
			*/
			if ($crossing === true) {
				$natal = trim ( substr ( $line, 55, 10 ) );
				for($i = 0; $i < count ( $houses ); $i ++) {
					/* if( $i >= 11 ) { $i++; } */
					if ($houses [sprintf ( "%04d", ($i + 1) )] == $natal) {
						$natalvalue = intval ( $i + 1 );
						//Transited CUSP = $natalvalue, transiting planet = $transvalue
						break; /* from for loop */
					}
				}
			} else {
				/* look for the natal object */
				$natal = trim ( substr ( $line, 55, 10 ) );
				//Transited object = $natal
				for($i = 0; $i < count ( $planets ); $i ++) {
					if ($planets [sprintf ( "%04d", (1000 + $i) )] == substr ( $natal, 0, strlen ( $planets [sprintf ( "%04d", (1000 + $i) )] ) )) {
						$natalvalue = intval ( (1000 + $i) );
						break;
					} else {
						// oops
					}
				}
			}

			$aspect = trim ( substr ( $line, 39, 3 ) );
			reset ( $aspects );
			while ( $aspectname = current ( $aspects ) ) {
				if ($aspectname == $aspect) {
					$aspectvalue = intval ( key ( $aspects ) );
					break;
				}
				next ( $aspects );
			}

			$TP_Rank = array_key_exists(sprintf("%04d", $transvalue), $TransitingPlanetRank) ? $TransitingPlanetRank[sprintf("%04d", $transvalue)] : 0;
			$NP_Rank = array_key_exists(sprintf("%04d", $natalvalue), $NatalPlanetRank) ? $NatalPlanetRank[sprintf("%04d", $natalvalue)] : 0;
			$ASP_Rank = array_key_exists(sprintf("%03d", $aspectvalue), $AspectRank) ? $AspectRank[sprintf("%03d", $aspectvalue)] : 0;
			
			$FinalPoint =  $TP_Rank + $NP_Rank + $ASP_Rank;
			
			
			if ($dynamic === false) {
				echo "<pre>**Static PUSH::: T:$transvalue : A:$aspectvalue : N:$natalvalue</pre>";
				//Static PUSH
				array_push ( $this->bridge_aspect,		/* seasonal/calendars */
				sprintf ( "%04d-%02d-%02d %02d:%02d %04d%03d%04d=%02d",
				/* date			*/	intval ( substr ( $line, 6, 4 ) ), intval ( substr ( $line, 0, 2 ) ), intval ( substr ( $line, 3, 2 ) ),
				/* time			*/	intval ( substr ( $line, 11, 2 ) ), intval ( substr ( $line, 14, 2 ) ),
				/* transiting	*/	$transvalue,
				/* aspect		*/	$aspectvalue,
				/* natal		*/	$natalvalue,
				/* Total Point	*/	$FinalPoint ) );
			} else {
				if ($crossing === true) {
					//PUSH CROSSING Aspect
					array_push ( $this->bridge_crossings,		/* Jupiter/Saturn house crossing */
					sprintf ( "%04d-%02d-%02d %02d:%02d %04d%03d%04d=%02d",
					/* date			*/	intval ( substr ( $line, 6, 4 ) ), intval ( substr ( $line, 3, 2 ) ), intval ( substr ( $line, 0, 2 ) ),
					/* time			*/	intval ( substr ( $line, 11, 2 ) ), intval ( substr ( $line, 14, 2 ) ),
					/* transiting	*/	$transvalue,
					/* aspect		*/	$aspectvalue,
					/* natal		*/	$natalvalue,
					/* Total Point	*/	$FinalPoint ) );

				} else {
					//PUSH Transit"
					$TransitValues = sprintf ( "%04d-%02d-%02d %02d:%02d %04d%03d%04d=%02d",
						/* date			*/	intval ( substr ( $line, 6, 4 ) ), intval ( substr ( $line, 3, 2 ) ), intval ( substr ( $line, 0, 2 ) ),
						/* time			*/	intval ( substr ( $line, 11, 2 ) ), intval ( substr ( $line, 14, 2 ) ),
						/* transiting	*/	$transvalue,
						/* aspect		*/	$aspectvalue,
						/* natal		*/	$natalvalue,
						/* Total Point	*/	$FinalPoint );  
					array_push ( $this->bridge_transit,		$TransitValues ); /* Transiting aspects */
					//echo "<pre>$TransitValues</pre>";							
				}
			}
		}
	}

	/**
	 * getTransitStartDate
	 *
	 * Starting at the start date, check whether the transit is within orb then
	 * track forwards until the transit first appears in orb
	 *
	 * @param int $transit
	 * @param int $natal
	 * @param int $aspect
	 */
	function getTransitStartDate($transit, $natal, $aspect) {		
		$date = $this->bridge_transit_window [$transit] [$aspect] [$natal] ['start'];
		
		echo "***getTransitStartDate: $transit == $aspect == $natal == $date<br />";
		return sprintf ( "%02d-%02d-%04d", substr ( $date, 6, 2 ), substr ( $date, 4, 2 ), substr ( $date, 0, 4 ) );
	}

	/**
	 * getTransitEndDate
	 */
	function getTransitEndDate($transit, $natal, $aspect) {
		
		$date = $this->bridge_transit_window [$transit] [$aspect] [$natal] ['end'];
		echo "***getTransitEndDate: $transit == $aspect == $natal == $date<br />";
		return sprintf ( "%02d-%02d-%04d", substr ( $date, 6, 2 ), substr ( $date, 4, 2 ), substr ( $date, 0, 4 ) );
	}

	/*
	 #Mo/Dy/Yr  Jupi   Satu   Uran   Nept   Plut
	#12/ 1/ 9 20Aq53  2Li58 22Pi42.23Aq53  2Cp11
	#----------111111111122222222223333333333444444444455555555556666666666777777777
	#0123456789012345678901234567890123456789012345678901234567890123456789012345678

	*/
	function getEphemeris($start_date, $lines) {
		foreach ( $lines as $line ) {
			/* look for main ephemeris data lines only including pluto retrograde flag */
			if (strlen ( $line ) == 44) {
				/* get the date */
				$edate = $this->getEphemerisData ( $start_date, $line );
				$date = sprintf ( "%04d%02d%02d", $edate ['year'], $edate ['month'], $edate ['day'] );
				$this->bridge_ephemeris [$date] = $edate ['planets'];
			}
		}
	}

	function getEphemerisData($start_date, $line) {
		/* date related */
		$month = intval ( trim ( substr ( $line, 0, 2 ) ) );
		$day = intval ( trim ( substr ( $line, 3, 2 ) ) );
		$year = intval ( trim ( substr ( $line, 6, 2 ) ) );

		/* planet related */
		$planet ['Jupiter'] = $this->getEphemerisObjectData ( $start_date, substr ( $line, 9, 7 ) );
		$planet ['Saturn'] = $this->getEphemerisObjectData ( $start_date, substr ( $line, 16, 7 ) );
		$planet ['Uranus'] = $this->getEphemerisObjectData ( $start_date, substr ( $line, 23, 7 ) );
		$planet ['Neptune'] = $this->getEphemerisObjectData ( $start_date, substr ( $line, 30, 7 ) );
		$planet ['Pluto'] = $this->getEphemerisObjectData ( $start_date, substr ( $line, 37, 7 ) );

		return array ('year' => (2000 + $year), 'month' => $month, 'day' => $day, 'planets' => $planet );
	}

	function getEphemerisObjectData($start_date, $line) {
		$signs = array ('Ar' => 0, 'Ta' => 30, 'Ge' => 60, 'Cn' => 90, 'Le' => 120, 'Vi' => 150, 'Li' => 180, 'Sc' => 210, 'Sg' => 240, 'Cp' => 270, 'Aq' => 300, 'Pi' => 330 );

		/* extract sign */
		$sign = substr ( $line, 2, 2 );
		$signoffset = $signs [$sign];
		if ($signoffset < 0 || $signoffset > 330) {
			// error
		}

		/* determine degree */
		$degree = 360 + $signoffset + intval ( trim ( substr ( $line, 0, 2 ) ) );
		$minute = intval ( trim ( substr ( $line, 4, 2 ) ) );
		$minute = intval ( floatval ( $minute ) * (100.0 / 60.0) );

		/* determine motion */
		$retrograde = (substr ( $line, 6, 0 ) == '.') ? true : false;

		return array ('longitude' => $degree + ($minute / 100.0), 'retrograde' => $retrograde );
	}

	/**
	 * getEphemerisTransitWindow
	 *
	 * @param int $trans transiting planet
	 * @param int $aspect transiting aspect
	 * @param int $object transited object 10xx (planet) 01xx (sign) 00xx (house)
	 */
	function getEphemerisTransitWindow($trans, $aspect, $object) {
		$pl_array = array ('Sun', 'Moon', 'Mercury', 'Venus', 'Mars', 'Jupiter', 'Saturn', 'Uranus', 'Neptune', 'Pluto', 'NNode', 'SNode' );

		/* if the object value >= 1000 then this is a planetary transit */
		if (intval ( $object ) >= 1000) {
			$natal = floatval ( $this->bridge_object [$pl_array [$object - 1000]] ['longitude'] );
		} else {
			if (intval ( $object ) < 100) {
				/* if the object value < 100 then this is a house cusp transit */
				$natal = floatval ( $this->bridge_object ['cusp'] [intval ( $object )] );
			} else {
				/* if the object value < 1000 and >= 100 then this is a sign ingress (if conjunction) */
				if (intval ( $aspect ) == 0) {
				} else {
					/* don't want to know */
				}
			}
		}
		$transit_lo_app = ($natal - floatval ( $aspect )) - 3.0;
		if ($transit_lo_app < 0.0) {
			$transit_lo_app += 360.0;
		}
		$transit_hi_app = ($natal - floatval ( $aspect )) + 3.0;
		if ($transit_hi_app < 0.0) {
			$transit_hi_app += 360.0;
		}
		$transit_lo_sep = ($natal + floatval ( $aspect )) - 3.0;
		if ($transit_lo_sep > 360.0) {
			$transit_lo_sep -= 360.0;
		}
		$transit_hi_sep = ($natal + floatval ( $aspect )) + 3.0;
		if ($transit_hi_sep > 360.0) {
			$transit_hi_sep -= 360.0;
		}

		$start_date = '';
		$end_date = '';

		unset ( $start_date );
		reset ( $this->bridge_ephemeris );

		/* scan the ephemeris table */
		for($i = 0; $i < count ( $this->bridge_ephemeris ); $i ++) {
			$line = each ( $this->bridge_ephemeris );
			/* get the date */
			$date = $line [0];
			/* get the longitude of the transiting planet */
			$transit = floatval ( $line [1] [$pl_array [$trans - 1000]] ['longitude'] - 360.0 );
			/* check whether we are in a transit window */

			if (($transit >= $transit_lo_app && $transit <= $transit_hi_app) || ($transit >= $transit_lo_sep && $transit <= $transit_hi_sep)) {
				if (isset ( $start_date ) === false) {
					$start_date = $date;
				}
				/* keep a running track of the end date */
				$end_date = $date;
				$orb = $transit - $natal;
			}
		}
		return array ("start" => $start_date, "end" => $end_date, "data" => "not available yet" );
	}

	/*
	 * scan through the ephemeris for the first date where the transiting object
	* makes the required aspect to the target object. This scan is performed in
	* the forward direction
	*/
	function getEphemerisTransitStartDate($trans, $aspect, $object) {
		return $this->bridge_transit_window [$trans] [$aspect] [$object] ['start'];
	}

	/*
	 * scan through the ephemeris for the first date where the transiting object
	* makes the required aspect to the target object. This scan is performed in
	* the reverse direction.
	*/
	function getEphemerisTransitEndDate($trans, $aspect, $object) {
		return $this->bridge_transit_window [$trans] [$aspect] [$object] ['end'];
	}

	function getEphemerisTransitImageData($trans, $aspect, $object) {
		return $this->bridge_transit_window [$trans] [$aspect] [$object] ['data'];
	}

	/*
	 * return the longitude of the requested object
	* - start of the objects = 9
	* - length of object = 7 characters (15Pi15.) where the last character is a dot
	*/
	function getEphemerisObject($line, $object) {

		$signs = array ();

		/* ephemeris string starts after the date */
		$ephemeris = substr ( $line, 9 );
		/* look for the object segment within the ephemeris string */
		$object_location = substr ( $ephemeris, ($object * 7), 6 );
		/* split the components and convert to a decimal */
		$object_degree = intval ( substr ( $object_location, 0, 2 ) );
		$object_sign = substr ( $object_location, 2, 2 );
		$object_mins = intval ( substr ( $object_location, 4, 2 ) );
		$object_longitude = ((floatval ( $object_sign ) * 30.0) + floatval ( $object_degree ) + floatval ( floatval ( $object_mins ) * 100.0 / 60.0 ));
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
	function calcDynamicAspects($BrithData, $StartYear, $Duration) {
		/**
		 * create the Astrolog low level API instance
		*/
		$this->bridge_pipe = new AstrologInvocationPipe ();
	
		/* format the birth data */
		$this->bridge_pipe->addArgument ( $BrithData->qaSwitchFormat () );
	
		/* include the angles as objects */
		$this->bridge_pipe->addArgument ( '=C' );
	
		/* ptolemaic aspects only used */
		$this->bridge_pipe->addArgument ( '-A 5' );
	
		/* orbs used */
		$this->bridge_pipe->addArgument ( '-YAo 1 5 8.0 8.0 6.0 5.0 5.0' );
	
		/* max permissable orbs */
		$this->bridge_pipe->addArgument ( '-YAm  1 10 360 360 360 360 360 360 360 360 360 360' );
		$this->bridge_pipe->addArgument ( '-YAm 11 20 360 360 360 360 360   3   3   3   3   3' );
		$this->bridge_pipe->addArgument ( '-YAm 21 32 360 360 360 360 360 360 360 360 360 360 360 360' );
		$this->bridge_pipe->addArgument ( '-YAm 33 41 360 360 360 360 360 360 360 360 360' );
	
		/* planet aspect orb additions */
		$this->bridge_pipe->addArgument ( '-YAd  1 10 1.0 1.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0' );
		$this->bridge_pipe->addArgument ( '-YAd 11 20 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0' );
		$this->bridge_pipe->addArgument ( '-YAd 21 32 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0' );
	
		/* just in case the aspect angles are not defined ... */
		// $this->bridge_pipe->addArgument('-YAa 1 5	0.0 180.0 90.0 120.0 60.0');
	
	
		/* manage aspected object settings */
		$this->bridge_pipe->addArgument ( '-YR  1 10 0 0 0 0 0 0 0 0 0 0' ); /* main planets enabled */
		$this->bridge_pipe->addArgument ( '-YR 11 20 1 1 1 1 1 0 1 1 1 1' ); /* node enabled */
		$this->bridge_pipe->addArgument ( '-YR 21 32 0 1 1 1 1 1 1 1 1 0 1 1' ); /* ascendant/MC enabled */
		$this->bridge_pipe->addArgument ( '-YR 33 41 1 1 1 1 1 1 1 1 1' ); /* all else disabled */
	
		/* manage transiting object settings */
		$this->bridge_pipe->addArgument ( '-YRT 1 10  1 1 1 1 1 0 0 0 0 0' );
		$this->bridge_pipe->addArgument ( '-YRT 11 20 1 1 1 1 1 1 1 1 1 1' );
		$this->bridge_pipe->addArgument ( '-YRT 21 32 1 1 1 1 1 1 1 1 1 1 1 1' );
		$this->bridge_pipe->addArgument ( '-YRT 33 41 1 1 1 1 1 1 1 1 1' );
	
		/* request aspect generation */
		$this->bridge_pipe->addArgument ( sprintf ( "-tY %d %d", $StartYear, $Duration ) );
	
		/* finally call Astrolog */
		$this->bridge_pipe->callAstrolog ();
		$lines = explode ( "\n", $this->bridge_pipe->getCache () );

// 		echo "<pre>";
// 		print_r($lines);
// 		echo "</pre>";
		$this->bridge_transit = array ();
		$this->getCalendarAspects ( $lines, true, false );
		$this->bridge_pipe->teardown ();
	}
};
