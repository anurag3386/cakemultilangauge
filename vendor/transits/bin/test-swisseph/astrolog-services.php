<?php

// How to create Cron in php
//
// http://www.web-site-scripts.com/knowledge-base/article/AA-00487/0/Setup-Cron-job-on-Windows-7-Vista-2008.html
//
// http://network.acquia.com/documentation/getting-started/appendix/localhost-config

//http://www.world-of-wisdom.com/06_affiliates/wowuk/admin/download.php?id=9163

/**
 * SwissEphemerisAPI
 *
 * Wrapper class for Swiss Ephemeris API
 *
 * @package SwissEphemerisAPI
 *
 * @author Amit Parmar <amit.parmar@n-techcorporate.com>
 * @version 1.0
 */
class AstrologServices {

	/**
	 * @var $m_pipe
	 */
	var $m_pipe;

	/**
	 * @var $m_timeddata
	 */
	var $m_timeddata; /* defaults to yes	 */

	/**
	 * @var $m_object
	 */
	var $m_object; /* planetary objects	 */

	/**
	 * @var $m_aspect
	 */
	var $m_aspect; /* aspects		 */

	/**
	 * @var $m_transit
	 */
	var $m_transit; /* transits		 */

	/**
	 * @var m_crossing
	 */
	var $m_crossing; /* house cusp crossings	 */

	/**
	 * @var $m_ephemeris
	 */
	var $m_ephemeris;     /* cached ephemeris data */

	/**
	 * @var $m_transit_window;
	 */
	var $m_transit_window;

	/**
	 * $solarReturnDate
	 *
	 * @var string
	 */
	var $solarReturnDate;

	/**
	 * $solarReturnTime
	 *
	 * @var string
	 */
	var $solarReturnTime;

	/**
	 * $solarChart
	 * @var array of plantory position
	 */
	var $solarChart;

	/**
	 * $horaryChart
	 * @var array of plantory position
	 */
	var $horaryChart;

	/**
	 * $natalChart
	 * @var array of natal (birth chart) aspects
	 */
	var $natalChart;

	/**
	 * $progressionChart
	 * @var array of progressio
	 */
	var $progressionChart;

	/**
	 * $aspectSolarToHorary
	 * @var array of aspect Solar Return to Horary
	 */
	var $aspectSolarToHorary = array();

	/**
	 * $aspectSolarToNatal
	 * @var array of aspect Solar Return to Natal
	 */
	var $aspectSolarToNatal = array();

	/**
	 * $aspectHoraryToNatal
	 * @var array of aspect Horary to Natal
	 */
	var $aspectHoraryToNatal = array();

	/**
	 * $solarReturnAspects
	 * @var array of Solar retun aspects
	 */
	var $solarReturnAspects = array();

	/**
	 * $horaryAspects;
	 * @var array of Horary aspects
	 */
	var $horaryAspects;

	/**
	 * $aspectProgressedToNatal
	 * @var array of aspect Progressed to Natal
	 */
	var $aspectProgressedToNatal = array();
	
	/**
	 * $SAandJUCrossing List of Saturn and Jupitar House Crossing
	 * @var Array
	 */
	var $SAandJUCrossing = array();

	/**
	 * Constructor
	 *
	 * @param mixed birthdata
	 * @param bool calculate chart data
	 * @return AstrologServices
	 */
	//function AstrologServices($data, $calcChartData = true) {
	function __construct ($data, $calcChartData = true) {

		global $logger;
		$logger->debug("AstrologServices::AstrologServices");
		//        $this->m_aspect = array();
		//        $this->m_object = array();
		
		$this->m_timeddata = $data->timed_data;
		if ($calcChartData === true) {
			if ($this->m_timeddata === true) {
				$this->calcChart($data);
			} else {
				$this->calcSolarChart($data);
			}
			$this->calcAspects($data);
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
	function calcChart($data) {
		global $logger;
		$logger->debug("AstrologServices::calcChart()");

		/*
		 * determine the planetary and house cusp longitudes along with related
		* context information
		*/
		$this->m_pipe = new AstrologCalculator();

		/* format the birth data */
		$this->m_pipe->addArgument($data->qaSwitchFormat());

		/* display locations in degrees */
		$this->m_pipe->addArgument('-sd');
		
		/* finally call Astrolog */
		$this->m_pipe->callAstrolog();
				 
		$logger->debug("AstrologServices::calcChart: cache=" . $this->m_pipe->getCache());

		$lines = explode("\n", $this->m_pipe->getCache());
		for ($line = 0; $line < count($lines); $line++) {
			//$this->getPlanetaryLongitude($lines[$line]);
			$this->getPlanetaryLongitudeLocalSystem(($lines[$line]));
		}

		foreach ($lines as $line) {
			//$this->getHouseCuspLongitude($line);
			$this->getHouseCuspLongitudeLocalSystem($line);
		}
		
		$this->natalChart = $this->m_object;

		$this->m_pipe->teardown();
	}

	/**
	 * calcSolarChart
	 *
	 * Untimed charts are calculated by canculating a noon chart for the date of
	 * birth and then adjusting the chart so that the 1st house cusp lies at the
	 * start of the Sun sign. An equal house system is used. All occupancy for
	 * planets in houses is adjusted to new house configuration
	 *
	 * @param BirthData $data
	 */
	function calcSolarChart($data) {

		global $logger;
		$logger->debug("AstrologServices::calcSolarChart()");

		/*
		 * determine the planetary and house cusp longitudes along with related
		* context information
		*/
		$this->m_pipe = new AstrologCalculator();

		/*
		 * format the birth data
		* Note: the chart should be calculated for noon as that will reduce the error
		* for cuspal charts.
		*/
		/* TODO: force noon day chart data here */
		$this->m_pipe->addArgument($data->qaSwitchFormat());

		/* specify whole house system */
		$this->m_pipe->addArgument('-c 12');

		/* display locations in degrees */
		$this->m_pipe->addArgument('-sd');

		/* finally call Astrolog */
		$this->m_pipe->callAstrolog();
		$logger->debug("AstrologServices::calcSolarChart: cache=" . $this->m_pipe->getCache());
		$lines = explode("\n", $this->m_pipe->getCache());

		for ($line = 0; $line < count($lines); $line++) {
			$this->getPlanetaryLongitude($lines[$line]);
		}
		foreach ($lines as $line) {
			$this->getHouseCuspLongitude($line);
		}

		$this->natalChart = $this->m_object;

		/* adjust cusp longitude degrees to revised boundaries. Minutes will be 00 */
		$this->m_pipe->teardown();
	}

	/**
	 * calcAspects
	 */
	function calcAspects($data) {
		global $logger;
		$logger->debug("AstrologServices::calcAspects()");

		/*
		 * determine the aspects
		*/
		$this->m_pipe = new AstrologCalculator();

		/* format the birth data */
		$this->m_pipe->addArgument($data->qaSwitchFormat());

		/* include the angles as objects */
		$this->m_pipe->addArgument('=C');

		/* ptolemaic aspects only used */
		$this->m_pipe->addArgument('-A 5');

		/* orbs used */
		$this->m_pipe->addArgument('-YAo 1 5	 9.0  9.0 6.0  6.0 4.0');           /* standard	 */
		#	$this->m_pipe->addArgument('-YAo 1 5	 5.0  4.0 3.0  2.0 2.0');		/* strong	*/
		#	$this->m_pipe->addArgument('-YAo 1 5	 9.0  8.0 6.0  6.0 4.0');		/* medium	*/
		#	$this->m_pipe->addArgument('-YAo 1 5	14.0 12.0 9.0 11.0 7.0');		/* weak		*/

		/* max permissable orbs */
		$this->m_pipe->addArgument('-YAm 1 10	360 360 360 360 360 360 360 360 360 360');
		$this->m_pipe->addArgument('-YAm 11 20	360 360 360 360 360   3   3   3   3   3');
		$this->m_pipe->addArgument('-YAm 21 32	360 360 360 360 360 360 360 360 360 360 360 360');
		$this->m_pipe->addArgument('-YAm 33 41	360 360 360 360 360 360 360 360 360');

		/* planet aspect orb additions */
		$this->m_pipe->addArgument('-YAd 1 10	0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0');
		$this->m_pipe->addArgument('-YAd 11 20	0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0');
		$this->m_pipe->addArgument('-YAd 21 32	0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0');

		/* just in case the aspect angles are not defined ... */
		// $this->m_pipe->addArgument('-YAa 1 5	0.0 180.0 90.0 120.0 60.0');

		/* manage aspected object settings */
		$this->m_pipe->addArgument('-YR  1 10 0 0 0 0 0 0 0 0 0 0');        /* main planets enabled */
		$this->m_pipe->addArgument('-YR 11 20 1 1 1 1 1 0 1 1 1 1');        /* node enabled */
		$this->m_pipe->addArgument('-YR 21 32 0 1 1 1 1 1 1 1 1 1 1 1');    /* ascendant enabled */
		$this->m_pipe->addArgument('-YR 33 41 1 1 1 1 1 1 1 1 1');          /* all else disabled */

		/* request aspect generation */
		$this->m_pipe->addArgument('-a');

		/* finally call Astrolog */
		$this->m_pipe->callAstrolog();
		$logger->debug("AstrologServices::calcAspects: cache=" . $this->m_pipe->getCache());
		$lines = explode("\n", $this->m_pipe->getCache());
		$this->getAspects($lines);

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
		global $logger;
		$logger->debug("AstrologServices::calcDynamicAspects()");

		/**
		 * create the Astrolog low level API instance
		 */
		$this->m_pipe = new AstrologCalculator();

		/* format the birth data */
		$this->m_pipe->addArgument($data->qaSwitchFormat());

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
		$this->m_pipe->addArgument('-YRT 11 20 1 1 1 1 1 1 1 1 1 1');
		$this->m_pipe->addArgument('-YRT 21 32 1 1 1 1 1 1 1 1 1 1 1 1');
		$this->m_pipe->addArgument('-YRT 33 41 1 1 1 1 1 1 1 1 1');

		/* request aspect generation */
		$this->m_pipe->addArgument(sprintf("-tY %d %d", $start_year, $duration));

		/* finally call Astrolog */
		$this->m_pipe->callAstrolog();
		$logger->debug("AstrologServices::calcDynamicAspects: cache=" . $this->m_pipe->getCache());
		$lines = explode("\n", $this->m_pipe->getCache());		
		
		$this->m_transit = array();
		
		//Only check the Transit for planets
		$this->getCalendarAspects($lines, true, false);
		$this->m_pipe->teardown();		
	}

	/**
	 * calcTransitEntryPoint
	 * Subtract the orb value from the planetary longitude and search for transits from
	 * one planet to that point
	 */
	function calcTransitEntryPoint() {
		global $logger;
		$logger->debug("AstrologServices::calcDynamicAspects()");

		/*
		 * determine the aspects
		*/
		$this->m_pipe = new AstrologCalculator();

		/* format the birth data */
		$this->m_pipe->addArgument($data->qaSwitchFormat());

		/* include the angles as objects */
		$this->m_pipe->addArgument('=C');

		/* ptolemaic aspects only used */
		$this->m_pipe->addArgument('-A 5');

		/*
		 * orbs used
		* here we section out the orbs we don't want by setting them to -1
		*/
		$this->m_pipe->addArgument('-YAo 1 5 -1 -1 -1 -1 -1');
		$this->m_pipe->addArgument('-Ao 1 5 -1 -1 -1 -1 -1');

		/* max permissable orbs */
		$this->m_pipe->addArgument('-YAm  1 10 360 360 360 360 360 360 360 360 360 360');
		$this->m_pipe->addArgument('-YAm 11 20 360 360 360 360 360   3   3   3   3   3');
		$this->m_pipe->addArgument('-YAm 21 32 360 360 360 360 360 360 360 360 360 360 360 360');
		$this->m_pipe->addArgument('-YAm 33 41 360 360 360 360 360 360 360 360 360');

		/* planet aspect orb additions */
		$this->m_pipe->addArgument('-YAd  1 10 1.0 1.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0');
		$this->m_pipe->addArgument('-YAd 11 20 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0');
		$this->m_pipe->addArgument('-YAd 21 32 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0');
	}

	/**
	 * calcTransitExitPoint
	 * Subtract the orb value from the planetary longitude and search for transits from
	 * one planet to that point
	 */
	function calcTransitExitPoint() {

	}

	Function calcCalendarAspects($data, $start_year, $duration) {
		global $logger;
		$logger->debug("AstrologServices::calcCalendarAspects()");

		/*
		 * determine the aspects
		*/
		$this->m_pipe = new AstrologCalculator();

		/* format the birth data */
		$this->m_pipe->addArgument($data->qaSwitchFormat());

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
		$this->m_pipe->addArgument('-YRT 1 10  0 1 0 0 0 0 0 0 0 0');
		$this->m_pipe->addArgument('-YRT 11 20 1 1 1 1 1 1 1 1 1 1');
		$this->m_pipe->addArgument('-YRT 21 32 1 1 1 1 1 1 1 1 1 1 1 1');
		$this->m_pipe->addArgument('-YRT 33 41 1 1 1 1 1 1 1 1 1');

		/* request aspect generation */
		$this->m_pipe->addArgument(sprintf("-tY %d %d", $start_year, $duration));

		/* finally call Astrolog */
		$this->m_pipe->callAstrolog();
		$logger->debug("AstrologServices::calcCalendarAspects: cache=" . $this->m_pipe->getCache());
		$lines = explode("\n", $this->m_pipe->getCache());
		$this->m_aspect = array();
		$this->getCalendarAspects($lines, false, false);
		$this->m_pipe->teardown();
	}

	/**
	 * calcCrossingAspects
	 */
	function calcCrossingAspects($data, $start_year, $duration) {
		global $logger;
		$logger->debug("AstrologServices::calcCrossingAspects()");

		/*
		 * determine the aspects
		*/
		$this->m_pipe = new AstrologCalculator();

		/* format the birth data */
		$this->m_pipe->addArgument($data->qaSwitchFormat());

		/* Time Zone Set up*/
		//http://www.bonniehill.net/pages.aux/astrology/astrolog/Config.DAT
//		$this->m_pipe->addArgument('-z0 ' . $data->m_summertime_offset); 		//Default Daylight time setting   [0 standard, 1 daylight]
//		$this->m_pipe->addArgument('-z ' . $data->m_timezone_offset);  			//Default time zone               [hours before GMT      ]
		
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
		$this->m_pipe->addArgument('-YR  1 10 1 1 1 1 1 1 1 1 1 1');  /* main planets enabled */
		$this->m_pipe->addArgument('-YR 11 20 1 1 1 1 1 1 1 1 1 1');  /* node enabled */
		$this->m_pipe->addArgument('-YR 21 32 0 0 0 0 0 0 0 0 0 0 0 0'); /* ascendant/MC enabled */
		$this->m_pipe->addArgument('-YR 33 41 1 1 1 1 1 1 1 1 1');   /* all else disabled */

		/* manage transiting object settings */
		$this->m_pipe->addArgument('-YRT 1 10  1 1 1 1 1 0 0 1 1 1');
		$this->m_pipe->addArgument('-YRT 11 20 1 1 1 1 1 1 1 1 1 1');
		$this->m_pipe->addArgument('-YRT 21 32 1 1 1 1 1 1 1 1 1 1 1 1');
		$this->m_pipe->addArgument('-YRT 33 41 1 1 1 1 1 1 1 1 1');

		/* request aspect generation */
		$this->m_pipe->addArgument(sprintf("-tY %d %d", $start_year, $duration));

		/* finally call Astrolog */
		$this->m_pipe->callAstrolog();
		$logger->debug("AstrologServices::calcCrossingAspects: cache=" . $this->m_pipe->getCache());
		$lines = explode("\n", $this->m_pipe->getCache());		
		
		$this->m_crossing = array();
		$this->getCalendarAspects($lines, true, true);
		$this->m_pipe->teardown();
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
		global $logger;
		$logger->debug("AstrologServices::calcEphemeris($start_year,$duration)");

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
			$logger->debug("AstrologServices::calcEphemeris: cache=" . $this->m_pipe->getCache());
			$lines = array_merge($lines, explode("\n", $this->m_pipe->getCache()));
			$this->m_pipe->teardown();
		}
		$this->getEphemeris($start_year, $lines);
	}

	function calcACG($data) {
		global $logger;
		$logger->debug("AstrologServices::calcACG()");
		/*
		 * determine the planetary and house cusp longitudes along with related
		* context information
		*/
		$this->m_pipe = new AstrologCalculator();

		/* format the birth data */
		$this->m_pipe->addArgument($data->qaSwitchFormat());

		/* set step size */
		$this->m_pipe->addArgument('-L 1');

		/* finally call Astrolog */
		$this->m_pipe->callAstrolog();
		$logger->debug("AstrologServices::calcACG: cache=" . $this->m_pipe->getCache());
		$lines = explode("\n", $this->m_pipe->getCache());

		$acg_mc = array();
		$acg_as = array();
		$acg_ds = array();

		foreach ($lines as $line) {
			/* look for the MC lines */
			if (substr($line, 0, 7) == 'Midheav') {
				/* scan the line */
				$logger->debug("AstrologServices::calcACG - found MC - $line");
				$logger->debug('/* add planet conjunct MS lines */');
				for ($i = 0; $i < 10; $i++) {
					$longitude = intval(substr($line, (9 + ($i * 4)), 3));
					$hemisphere = substr($line, (12 + ($i * 4)), 1);
					if ($hemisphere == 'w') {
						$longitude = 0 - $longitude;
					}
					array_push(
							$acg_mc, array(
									"planet" => intval($i),
									"longitude" => intval($longitude),
									"valid" => true
							)
					);
				}
			}
			/* look for the Asc lines */
			if (substr($line, 0, 3) == 'Asc') {
				$logger->debug("in line - $line");
				$latitude = intval(trim(substr($line, 4, 2)));
				$logger->debug("- latitude = $latitude");
				$hemi_ns = substr($line, 6, 1);
				$logger->debug("- hemisphere = $hemi_ns");
				if ($hemi_ns == 's') {
					$latitude = 0 - $latitude;
				}
				for ($i = 0; $i < 10; $i++) {
					$longitude = trim(substr($line, (9 + ($i * 4)), 3));
					$logger->debug("- longitude[$i] = $longitude");
					$hemisphere = trim(substr($line, (12 + ($i * 4)), 1));
					if ($longitude != '--') {
						if ($hemisphere == 'w') {
							$longitude = 0 - $longitude;
						}
						$valid = true;
					} else {
						$valid = false;
					}
					array_push(
							$acg_as, array(
									"planet" => $i,
									"longitude" => intval($longitude),
									"latitude" => intval($latitude),
									"valid" => $valid
							)
					);
				}
			}
			/* look for the Dsc lines */
			if (substr($line, 0, 3) == 'Dsc') {
				$latitude = intval(trim(substr($line, 4, 2)));
				$hemi_ns = substr($line, 6, 1);
				if ($hemi_ns == 's') {
					$latitude = 0 - $latitude;
				}
				for ($i = 0; $i < 10; $i++) {
					$longitude = trim(substr($line, (9 + ($i * 4)), 3));
					$hemisphere = trim(substr($line, (12 + ($i * 4)), 1));
					if ($longitude != '--') {
						if ($hemisphere == 'w') {
							$longitude = 0 - $longitude;
						}
						$valid = true;
					} else {
						$valid = false;
					}
					array_push(
							$acg_ds, array(
									"planet" => $i,
									"longitude" => $longitude,
									"latitude" => $latitude,
									"valid" => $valid
							)
					);
				}
			}
		}
		return array(
				"mc" => $acg_mc,
				"as" => $acg_as,
				"ds" => $acg_ds
		);
	}

	function useStandardOrbSetting() {

		/* use minimum (angle) setting */
		$this->m_pipe->addArgument('-YAo 1 5 4.0 4.0 4.0 4.0 4.0');  /* base setting for ptolemaic aspects	 */

		/* max permissable orbs */
		$this->m_pipe->addArgument('-YAm  1 10 360 360 360 360 360 360 360 360 360 360');
		$this->m_pipe->addArgument('-YAm 11 20 360 360 360 360 360   3   3   3   3   3');
		$this->m_pipe->addArgument('-YAm 21 32 360 360 360 360 360 360 360 360 360 360 360 360');
		$this->m_pipe->addArgument('-YAm 33 41 360 360 360 360 360 360 360 360 360');

		/* planet aspect orb additions */
		$this->m_pipe->addArgument('-YAd  1 10 5.0 5.0 5.0 5.0 5.0 5.0 5.0 5.0 5.0 5.0');   /* lift planets to 9 degrees	 */
		$this->m_pipe->addArgument('-YAd 11 20 0.0 0.0 0.0 0.0 0.0 2.0 0.0 0.0 0.0 0.0');   /* lift node to 6 degrees		 */
		$this->m_pipe->addArgument('-YAd 21 32 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0'); /* angles already at 4 degrees	 */
	}

	function useStrongOrbSetting() {

	}

	function useMediumOrbSetting() {

	}

	function useWeakOrbSetting() {

	}

	/*
	 #----------111111111122222222223333333333444444444455555555556666666666777777777
	 # 0123456789012345678901234567890123456789012345678901234567890123456789012345678
	 # Satu: 191.319   + 2:26' (e) [ 1st house] [d] +0.055  -  House cusp  7: 358.002
     # Uran:   4.548 R - 0:45' (-) [ 7th house] [-] -0.006  -  House cusp  8:  21.047
     # Venu: 106.227   + 0:26' (-) [10th house] [-] +1.228  -  House cusp  4: 267.316
	*/
	function getPlanetaryLongitudeLocalSystem($line){
		global $logger;
		#		$logger->debug("AstrologServices::getPlanetaryLongitude( $line )");
		$sPlanet = array(
				'Sun' => 'Sun',
				'Moon' => 'Moon',
				'Merc' => 'Mercury',
				'Venu' => 'Venus',
				'Mars' => 'Mars',
				'Jupi' => 'Jupiter',
				'Satu' => 'Saturn',
				'Uran' => 'Uranus',
				'Nept' => 'Neptune',
				'Plut' => 'Pluto',
				'Chir' => 'Chiron',
				'Node' => 'Node',
				'S.No' => 'Node');
		 
		$planet = trim(substr($line, 0, 10));
		$findDot = trim(substr($planet, 9, 1));

		if($findDot == '.' || trim(substr($line, 5, 1)) == ':') {
			$planet = trim(substr($line, 0, 4));
			if(array_key_exists(trim($planet), $sPlanet)) {
				$planet = $sPlanet[$planet];
			}
		}
		 
		switch ($planet) {
			case 'Sun': case 'Moon':
			case 'Mercury': case 'Venus': case 'Mars':
			case 'Jupiter': case 'Saturn':
			case 'Uranus': case 'Neptune': case 'Pluto': case 'Chiron':
				$signno = (int)(floatval(trim(substr($line, 12-6, 7))) / 30.0);
				$this->m_object[$planet]['longitude'] = floatval(trim(substr($line, 12-6, 7)));
				$this->m_object[$planet]['retrograde'] = ((substr($line, 20-6, 1) == 'R') ? true : false);
				$this->m_object[$planet]['house'] = intval(trim(substr($line, 35-6, 2)));
				$this->m_object[$planet]['solar'] = intval((($this->m_object[$planet]['house'] + 12 - $this->m_object['Sun']['house']) % 12) + 1);
				$this->m_object[$planet]['sign'] = $signno;
				
				$logger->debug("AstrologServices::getPlanetaryLongitude - " .
						sprintf("%10s: long=%6.2f, rx=%d, house=%d, solar house=%d",
								$planet, $this->m_object[$planet]['longitude'], $this->m_object[$planet]['retrograde'], $this->m_object[$planet]['house'], $this->m_object[$planet]['solar']));
				break;
			case 'Node':
				$signno = (int)(floatval(trim(substr($line, 12-6, 7))) / 30.0);
				/* North Node */
				$this->m_object['N' . $planet]['longitude'] = floatval(trim(substr($line, 12-6, 7)));
				$this->m_object['N' . $planet]['retrograde'] = ((substr($line, 20-6, 1) == 'R') ? true : false);
				$this->m_object['N' . $planet]['house'] = intval(trim(substr($line, 35-6, 2)));
				$this->m_object['N' . $planet]['sign'] = $signno;
				
				/* South node */
				if ($this->m_object['N' . $planet]['longitude'] >= 180.0) {
					$this->m_object['S' . $planet]['longitude'] = $this->m_object['N' . $planet]['longitude'] - 180.0;
				} else {
					$this->m_object['S' . $planet]['longitude'] = $this->m_object['N' . $planet]['longitude'] + 180.0;
				}
				$this->m_object['S' . $planet]['retrograde'] = $this->m_object['N' . $planet]['retrograde'];
				if ($this->m_object['N' . $planet]['house'] > 6) {
					$this->m_object['S' . $planet]['house'] = $this->m_object['N' . $planet]['house'] - 6;
				} else {
					$this->m_object['S' . $planet]['house'] = $this->m_object['N' . $planet]['house'] + 6;
				}
				if ($this->m_object['N' . $planet]['sign'] > 6) {
					$this->m_object['S' . $planet]['sign'] = $this->m_object['N' . $planet]['sign'] - 6;
				} else {
					$this->m_object['S' . $planet]['sign'] = $this->m_object['N' . $planet]['sign'] + 6;
				}
				
				$logger->debug("AstrologServices::getPlanetaryLongitude - " . sprintf("%10s: long=%6.2f, rx=%d, house=%d", $planet, $this->m_object['N' . $planet]['longitude'], $this->m_object['N' . $planet]['retrograde'], $this->m_object['N' . $planet]['house']));
				$logger->debug("AstrologServices::getPlanetaryLongitude - " . sprintf("%10s: long=%6.2f, rx=%d, house=%d", $planet, $this->m_object['S' . $planet]['longitude'], $this->m_object['S' . $planet]['retrograde'], $this->m_object['S' . $planet]['house']));
				break;
			default:
				return;
		}
	}

	/*
	 #----------111111111122222222223333333333444444444455555555556666666666777777777
	#0123456789012345678901234567890123456789012345678901234567890123456789012345678
	#Jupiter   : 144.508 R + 1:11' (-) [ 9th] [R-] -0.120  -  House cusp  6:  41.058
	*/

	function getPlanetaryLongitude($line) {

		global $logger;
		#		$logger->debug("AstrologServices::getPlanetaryLongitude( $line )");
		$sPlanet = array(
				'Sun' => 'Sun',
				'Moon' => 'Moon',
				'Merc' => 'Mercury',
				'Venu' => 'Venus',
				'Mars' => 'Mars',
				'Jupi' => 'Jupiter',
				'Satu' => 'Saturn',
				'Uran' => 'Uranus',
				'Nept' => 'Neptune',
				'Plut' => 'Pluto',
				'Chir' => 'Chiron',
				'Node' => 'Node',
				'S.No' => 'Node');

		$planet = trim(substr($line, 0, 10));

		switch ($planet) {
			case 'Sun': case 'Moon':
			case 'Mercury': case 'Venus': case 'Mars':
			case 'Jupiter': case 'Saturn':
			case 'Uranus': case 'Neptune': case 'Pluto': case 'Chiron':
				$this->m_object[$planet]['longitude'] = floatval(trim(substr($line, 12, 7)));
				$this->m_object[$planet]['retrograde'] = ((substr($line, 20, 1) == 'R') ? true : false);
				$this->m_object[$planet]['house'] = intval(trim(substr($line, 35, 2)));
				$this->m_object[$planet]['solar'] = intval((($this->m_object[$planet]['house'] + 12 - $this->m_object['Sun']['house']) % 12) + 1);
				$logger->debug("AstrologServices::getPlanetaryLongitude - " .
						sprintf("%10s: long=%6.2f, rx=%d, house=%d, solar house=%d",
								$planet, $this->m_object[$planet]['longitude'], $this->m_object[$planet]['retrograde'], $this->m_object[$planet]['house'], $this->m_object[$planet]['solar']));
				break;
			case 'Node':
				/* North Node */
				$this->m_object['N' . $planet]['longitude'] = floatval(trim(substr($line, 12, 7)));
				$this->m_object['N' . $planet]['retrograde'] = ((substr($line, 20, 1) == 'R') ? true : false);
				$this->m_object['N' . $planet]['house'] = intval(trim(substr($line, 35, 2)));
				/* South node */
				if ($this->m_object['N' . $planet]['longitude'] >= 180.0) {
					$this->m_object['S' . $planet]['longitude'] = $this->m_object['N' . $planet]['longitude'] - 180.0;
				} else {
					$this->m_object['S' . $planet]['longitude'] = $this->m_object['N' . $planet]['longitude'] + 180.0;
				}
				$this->m_object['S' . $planet]['retrograde'] = $this->m_object['N' . $planet]['retrograde'];
				if ($this->m_object['N' . $planet]['house'] > 6) {
					$this->m_object['S' . $planet]['house'] = $this->m_object['N' . $planet]['house'] - 6;
				} else {
					$this->m_object['S' . $planet]['house'] = $this->m_object['N' . $planet]['house'] + 6;
				}
				$logger->debug("AstrologServices::getPlanetaryLongitude - " . sprintf("%10s: long=%6.2f, rx=%d, house=%d", $planet, $this->m_object['N' . $planet]['longitude'], $this->m_object['N' . $planet]['retrograde'], $this->m_object['N' . $planet]['house']));
				$logger->debug("AstrologServices::getPlanetaryLongitude - " . sprintf("%10s: long=%6.2f, rx=%d, house=%d", $planet, $this->m_object['S' . $planet]['longitude'], $this->m_object['S' . $planet]['retrograde'], $this->m_object['S' . $planet]['house']));
				break;
			default:
				return;
		}

	}

	/*
	 #----------111111111122222222223333333333444444444455555555556666666666777777777
	#0123456789012345678901234567890123456789012345678901234567890123456789012345678
	#Jupi: 297.630 R - 0:29' (d) [ 4th house] [e] -0.129  -  House cusp  6: 340.256
	#Jupiter   : 144.508 R + 1:11' (-) [ 9th] [R-] -0.120  -  House cusp  6:  41.058
	*/
	function getHouseCuspLongitudeLocalSystem($line) {

		global $logger;
		#		$logger->debug("AstrologServices::getHouseCuspLongitude( $line )");

		/*
		 * TODO: manage untimed charts
		* - Ascendant set to initial degree of Sun Sign
		* - equal houses used
		*/
		if (trim(substr($line, 56, 10)) == 'House cusp') {
			if ($this->m_timeddata === true) {
				$this->m_object['cusp'][intval(trim(substr($line, 67, 2)))] = floatval(trim(substr($line, 71, 7)));
			} else {
				/*
				 * Cusp  1 = 30 * Sun sign
				* so 342 degrees = 11
				* add 12 = 23, subtract
				*/
				$cusp = intval(trim(substr($line, 67, 2)));
				$logger->debug("wackamole: cusp = $cusp");
				$suncusp = intval(($this->m_object['Sun']['longitude'] / 30.0));
				$logger->debug("wackamole: suncusp = $suncusp");
				$this->m_object['cusp'][$cusp] = ((($suncusp * 30) + (($cusp - 1) * 30)) % 360);
				$logger->debug("wackamole: new house cusp = " . $this->m_object['cusp'][$cusp]);
			}
		}
	}

	/*
	 #----------111111111122222222223333333333444444444455555555556666666666777777777
	#0123456789012345678901234567890123456789012345678901234567890123456789012345678
	#Jupiter   : 144.508 R + 1:11' (-) [ 9th] [R-] -0.120  -  House cusp  6:  41.058
	*/

	function getHouseCuspLongitude($line) {

		global $logger;
		#		$logger->debug("AstrologServices::getHouseCuspLongitude( $line )");

		/*
		 * TODO: manage untimed charts
		* - Ascendant set to initial degree of Sun Sign
		* - equal houses used
		*/
		if (trim(substr($line, 57, 10)) == 'House cusp') {
			if ($this->m_timeddata === true) {
				$this->m_object['cusp'][intval(trim(substr($line, 68, 2)))] = floatval(trim(substr($line, 72, 7)));
			} else {
				/*
				 * Cusp  1 = 30 * Sun sign
				* so 342 degrees = 11
				* add 12 = 23, subtract
				*/
				$cusp = intval(trim(substr($line, 68, 2)));
				$logger->debug("wackamole: cusp = $cusp");
				$suncusp = intval(($this->m_object['Sun']['longitude'] / 30.0));
				$logger->debug("wackamole: suncusp = $suncusp");
				$this->m_object['cusp'][$cusp] = ((($suncusp * 30) + (($cusp - 1) * 30)) % 360);
				$logger->debug("wackamole: new house cusp = " . $this->m_object['cusp'][$cusp]);
			}
		}
	}

	/*
	 #----------111111111122222222223333333333444444444455555555556666666666777777777
	#0123456789012345678901234567890123456789012345678901234567890123456789012345678
	#  1:  Saturn [Lib] Con [Lib] Neptune    - orb: +0:26' - power: 24.14
	*/

	function getAspects($lines) {
		global $logger;
		$logger->debug("AstrologServices::getAspects");

		$this->m_aspect = array();

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
				"1012" => "Ascendant",
				"1013" => "Midheaven", /* "MC", */
				"1014" => "IC",
				"1015" => "Descendant"
		);
		$aspects = array(
				"000" => "Con",
				"060" => "Sex",
				"090" => "Squ",
				"120" => "Tri",
				"180" => "Opp"
		);

		/*
		 * iterate through the results
		*/
		for ($line = 0; $line < count($lines); $line++) {

			/* skip the lead-in */
			if (substr($lines[$line], 3, 1) != ':') {
				continue;
			}

			/*
			 * isolate the subject
			*/
			$subject = trim(substr($lines[$line], 4, 8));
			$subjectvalue = 0;
			for ($i = 0; $i < count($planets); $i++) {
				if ($i >= 11) {
					$i++;
				}
				if ($planets[(1000 + $i)] == $subject) {
					$subjectvalue = intval((1000 + $i));
					break;
				}
			}

			/*
			 * isolate the aspect
			* - added orb here for the wheel's aspect grid
			*/
			$aspect = trim(substr($lines[$line], 18, 5));
			$aspect_orb = trim(substr($lines[$line], 47, 5));
			$aspectvalue = -1;
			reset($aspects);
			while ($aspectname = current($aspects)) {
				if ($aspectname == $aspect) {
					$aspectvalue = intval(key($aspects));
					break;
				}
				next($aspects);
			}

			/*
			 * isolate the object
			*/
			$object = trim(substr($lines[$line], 28, 11));
			$objectvalue = 0;
			for ($i = 0; $i < count($planets); $i++) {

				if ($i >= 11) {
					$i++;
				}

				/* look for a string match */
				if ($planets[(1000 + $i)] == $object) {
					$logger->debug("AstrologServices::getAspects - found subject[$subjectvalue], object[$objectvalue]");
					if ($object == 'Ascendant') {
						$logger->debug("AstrologServices::getAspects - reversing objects for Ascendant context");
						$objectvalue = $subjectvalue;
						$subjectvalue = intval((1000 + $i));
					} else {
						$objectvalue = intval((1000 + $i));
					}
					$logger->debug("AstrologServices::getAspects - result subject[$subjectvalue], object[$objectvalue]");
					break;
				} else {
					// something has appeared that we are not prepared for ... error */
				}
			}

			/*
			 * store the aspect
			* @todo vet the orbs and restrict further if required
			*/
			if ($subjectvalue != 0 and $aspectvalue != -1 and $objectvalue != 0) {
				/* tack the orb to the end for the wheel's aspect grid */
				array_push($this->m_aspect, sprintf("%04d%03d%04d %s", $subjectvalue, $aspectvalue, $objectvalue, $aspect_orb));

			} else {
				$logger->debug("failed to parse aspect line, line=" . $lines[$line]);
				$logger->error("failed to parse aspect line");
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
		global $logger;
		$logger->debug("AstrologServices::getCalendarAspects");
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
				"1011" => "SNode",      /* put here to stop the nag messages, not used */
				"1012" => "Ascendant",
				"1013" => "Midheaven",  /* "MC", */
				"1014" => "IC",
				"1015" => "Descendant"
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
			$logger->debug("AstrologServices::getCalendarAspects - transiting planet = $trans");
			for ($i = 0; $i < count($planets); $i++) {
				if ($i >= 11) {
					$i++;
				}
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
						$logger->debug("AstrologServices::getCalendarAspects - transited cusp = $natalvalue, transiting planet = $transvalue");
						break; /* from for loop */
					}
				}
			} else {
				/* look for the natal object */
				$natal = trim(substr($line, 55, 10));

				$logger->debug("AstrologServices::getCalendarAspects - transited object = $natal");
				for ($i = 0; $i < count($planets); $i++) {
					/* if( $i >= 11 ) { $i++; } */
					/*
					 * The following works on the basis that we look for the polled object
					* using the length of the string in the planets table. This should
					* prevent the (<object> Return) string factoring in the equation
					*/
					if ($planets[sprintf("%04d", (1000 + $i))] == substr($natal, 0, strlen($planets[sprintf("%04d", (1000 + $i))]))) {
						$natalvalue = intval((1000 + $i));
						#$logger->error("natalvalue=$natalvalue");
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
								/* transiting            */ $transvalue,
								/* aspect		 */ $aspectvalue,
								/* natal		 */ $natalvalue
						)
				);
			} else {
				if ($crossing === true) {
					$logger->debug("AstrologServices::getCalendarAspects - push crossing aspect");
					array_push(
							$this->m_crossing, /* Jupiter/Saturn house crossing */ sprintf("%04d-%02d-%02d %02d:%02d %04d%03d%04d",
									/* date			 */ intval(substr($line, 6, 4)), intval(substr($line, 3, 2)), intval(substr($line, 0, 2)),
									/* time			 */ intval(substr($line, 11, 2)), intval(substr($line, 14, 2)),
									/* transiting	 */ $transvalue,
									/* aspect		 */ $aspectvalue,
									/* natal		 */ $natalvalue
							)
					);
					$logger->debug("AstrologServices::getCalendarAspects - crossing = " .
							sprintf("%04d-%02d-%02d %02d:%02d %04d%03d%04d",
									/* date			 */ intval(substr($line, 6, 4)), intval(substr($line, 3, 2)), intval(substr($line, 0, 2)),
									/* time			 */ intval(substr($line, 11, 2)), intval(substr($line, 14, 2)),
									/* transiting	 */ $transvalue,
									/* aspect		 */ $aspectvalue,
									/* natal		 */ $natalvalue
							)
					);
				} else {
					$logger->debug("AstrologServices::getCalendarAspects - push transit");
					array_push(
							$this->m_transit, /* Transiting aspects */ sprintf("%04d-%02d-%02d %02d:%02d %04d%03d%04d",
									/* date			 */ intval(substr($line, 6, 4)), intval(substr($line, 3, 2)), intval(substr($line, 0, 2)),
									/* time			 */ intval(substr($line, 11, 2)), intval(substr($line, 14, 2)),
									/* transiting	 */ $transvalue,
									/* aspect		 */ $aspectvalue,
									/* natal		 */ $natalvalue
							)
					);
					$logger->debug("AstrologServices::getCalendarAspects - transit = " .
							sprintf("%04d-%02d-%02d %02d:%02d %04d%03d%04d",
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
		global $logger;
		$logger->debug("AstrologServices::getTransitStartDate( $transit, $natal, $aspect )");
		$date = $this->m_transit_window[$transit][$aspect][$natal]['start'];
		return sprintf("%02d-%02d-%04d", substr($date, 6, 2), substr($date, 4, 2), substr($date, 0, 4));
	}

	/**
	 * getTransitEndDate
	 */
	function getTransitEndDate($transit, $natal, $aspect) {
		global $logger;
		$logger->debug("AstrologServices::getTransitEndDate( $transit, $natal, $aspect )");
		$date = $this->m_transit_window[$transit][$aspect][$natal]['end'];
		return sprintf("%02d-%02d-%04d", substr($date, 6, 2), substr($date, 4, 2), substr($date, 0, 4));
	}

	/*
	 #Mo/Dy/Yr  Jupi   Satu   Uran   Nept   Plut
	#12/ 1/ 9 20Aq53  2Li58 22Pi42.23Aq53  2Cp11
	#----------111111111122222222223333333333444444444455555555556666666666777777777
	#0123456789012345678901234567890123456789012345678901234567890123456789012345678

	*/

	function getEphemeris($start_date, $lines) {
		global $logger;
		$logger->debug("AstrologServices::getEphemeris");
		foreach ($lines as $line) {
			/* look for main ephemeris data lines only including pluto retrograde flag */
			if (strlen($line) == 44) {
				/* get the date */
				$edate = $this->getEphemerisData($start_date, $line);
				$date = sprintf("%04d%02d%02d", $edate['year'], $edate['month'], $edate['day']);
				$this->m_ephemeris[$date] = $edate['planets'];
			}
		}
	}

	function getEphemerisData($start_date, $line) {

		global $logger;
		$logger->debug("AstrologServices::getEphemerisData");

		/* date related */
		$month = intval(trim(substr($line, 0, 2)));
		$day = intval(trim(substr($line, 3, 2)));
		$year = intval(trim(substr($line, 6, 2)));

		/* planet related */
		$planet['Jupiter'] = $this->getEphemerisObjectData($start_date, substr($line, 9, 7));
		$planet['Saturn'] = $this->getEphemerisObjectData($start_date, substr($line, 16, 7));
		$planet['Uranus'] = $this->getEphemerisObjectData($start_date, substr($line, 23, 7));
		$planet['Neptune'] = $this->getEphemerisObjectData($start_date, substr($line, 30, 7));
		$planet['Pluto'] = $this->getEphemerisObjectData($start_date, substr($line, 37, 7));

		return array(
				'year' => (2000 + $year),
				'month' => $month,
				'day' => $day,
				'planets' => $planet
		);
	}

	function getEphemerisObjectData($start_date, $line) {
		global $logger;
		$logger->debug("AstrologServices::getEphemerisObjectData");

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

		return array(
				'longitude' => $degree + ($minute / 100.0),
				'retrograde' => $retrograde,
		);
	}

	/**
	 * getEphemerisTransitWindow
	 *
	 * @param int $trans transiting planet
	 * @param int $aspect transiting aspect
	 * @param int $object transited object 10xx (planet) 01xx (sign) 00xx (house)
	 */
	function getEphemerisTransitWindow($trans, $aspect, $object) {
		global $logger;
		$logger->debug("AstrologServices::getEphemerisTransitWindow($trans,$aspect,$object)");
		$pl_array = array('Sun', 'Moon', 'Mercury', 'Venus', 'Mars', 'Jupiter', 'Saturn', 'Uranus', 'Neptune', 'Pluto', 'NNode', 'SNode', "Ascendant", "Midheaven", "IC","Descendant");
		//$pl_array = array('Sun', 'Moon', 'Mercury', 'Venus', 'Mars', 'Jupiter', 'Saturn', 'Uranus', 'Neptune', 'Pluto', 'NNode', 'SNode', "Ascendant", "Midheaven", "IC","Descendant");

		/* if the object value >= 1000 then this is a planetary transit */
		if (intval($object) >= 1000) {
			$logger->debug("AstrologServices::getEphemerisTransitWindow($trans,$aspect,$object) - aspect to planet");

			if ($object < 1012 /* ASC */) {
				$natal = floatval($this->m_object[$pl_array[$object - 1000]]['longitude']);
			} else {
				if ($object == 1012 /* ASC */) {
					//print("AstrologServices::getEphemerisTransitWindow($trans,$aspect,$object) - aspect to ASC\n");
					$natal = floatval($this->m_object['cusp'][1]);
				}
				if ($object == 1013 /* MC */) {
					//print("AstrologServices::getEphemerisTransitWindow($trans,$aspect,$object) - aspect to MC\n");
					$natal = floatval($this->m_object['cusp'][10]);
				}
			}
		} else {
			if (intval($object) < 100) {
				/* if the object value < 100 then this is a house cusp transit */
				$logger->debug("AstrologServices::getEphemerisTransitWindow($trans,$aspect,$object) - aspect to cusp");
				$natal = floatval($this->m_object['cusp'][intval($object)]);
			} else {
				/* if the object value < 1000 and >= 100 then this is a sign ingress (if conjunction) */
				if (intval($aspect) == 0) {
					$logger->debug("AstrologServices::getEphemerisTransitWindow($trans,$aspect,$object) - sign ingress");
				} else {
					/* don't want to know */
				}
			}
		}

		$logger->debug("AstrologServices::getEphemerisTransitWindow - natal = " . sprintf("%5.2f", $natal));
		$transit_lo_app = ($natal - floatval($aspect)) - 3.0;
		if ($transit_lo_app < 0.0) {
			$transit_lo_app += 360.0;
		}
		$transit_hi_app = ($natal - floatval($aspect)) + 3.0;
		if ($transit_hi_app < 0.0) {
			$transit_hi_app += 360.0;
		}
		$transit_lo_sep = ($natal + floatval($aspect)) - 3.0;
		if ($transit_lo_sep > 360.0) {
			$transit_lo_sep -= 360.0;
		}
		$transit_hi_sep = ($natal + floatval($aspect)) + 3.0;
		if ($transit_hi_sep > 360.0) {
			$transit_hi_sep -= 360.0;
		}
		$logger->debug("AstrologServices::getEphemerisTransitWindow - loapp=$transit_lo_app, hiapp=$transit_hi_app, losep=$transit_lo_sep, hisep=$transit_hi_sep");
		unset($start_date);
		reset($this->m_ephemeris);

		/* scan the ephemeris table */
		for ($i = 0; $i < count($this->m_ephemeris); $i++) {
			$line = each($this->m_ephemeris);
			/* get the date */
			$date = $line[0];
			/* get the longitude of the transiting planet */
			$transit = floatval($line[1][$pl_array[$trans - 1000]]['longitude'] - 360.0);
			/* check whether we are in a transit window */
			if (($transit >= $transit_lo_app && $transit <= $transit_hi_app) || ($transit >= $transit_lo_sep && $transit <= $transit_hi_sep)) {
				if (isset($start_date) === false) {
					$logger->debug("AstrologServices::getEphemerisTransitWindow - transit = " . sprintf("%5.2f", $transit));
					$start_date = $date;
				}
				/* keep a running track of the end date */
				$end_date = $date;
				$orb = $transit - $natal;
			}
		}

		$logger->debug("AstrologServices::getEphemerisTransitWindow - start date = $start_date, end date = $end_date");
		return array(
				"start" => $start_date,
				"end" => $end_date,
				"data" => "not available yet"
		);
	}

	/*
	 * scan through the ephemeris for the first date where the transiting object
	* makes the required aspect to the target object. This scan is performed in
	* the forward direction
	*/

	function getEphemerisTransitStartDate($trans, $aspect, $object) {
		global $logger;
		$logger->debug("AstrologServices::getEphemerisTransitStartDate");
		return $this->m_transit_window[$trans][$aspect][$object]['start'];
	}

	/*
	 * scan through the ephemeris for the first date where the transiting object
	* makes the required aspect to the target object. This scan is performed in
	* the reverse direction.
	*/

	function getEphemerisTransitEndDate($trans, $aspect, $object) {
		global $logger;
		$logger->debug("AstrologServices::getEphemerisTransitEndDate");
		return $this->m_transit_window[$trans][$aspect][$object]['end'];
	}

	function getEphemerisTransitImageData($trans, $aspect, $object) {
		global $logger;
		$logger->debug("AstrologServices::getEphemerisTransitImageData");
		return $this->m_transit_window[$trans][$aspect][$object]['data'];
	}

	/*
	 * return the longitude of the requested object
	* - start of the objects = 9
	* - length of object = 7 characters (15Pi15.) where the last character is a dot
	*/

	function getEphemerisObject($line, $object) {

		$signs = array();

		/* ephemeris string starts after the date */
		$ephemeris = substr($line, 9);
		/* look for the object segment within the ephemeris string */
		$object_location = substr($ephemeris, ($object * 7), 6);
		/* split the components and convert to a decimal */
		$object_degree = intval(substr($object_location, 0, 2));
		$object_sign = substr($object_location, 2, 2);
		$object_mins = intval(substr($object_location, 4, 2));
		$object_longitude = ((floatval($object_sign) * 30.0) + floatval($object_degree) + floatval(floatval($object_mins) * 100.0 / 60.0));
	}

	/*
	 # $ astrolog -n -d
	# (Wed)  5/ 6/1953  3:47am     Sun (Tau) Sex (Can) Uranus
	# (Wed)  5/ 6/1953  1:20pm     Sun (Tau) Squ (Aqu) Moon (Half Moon)
	# (Thu) 12/13/2007  7:01am    Moon (Cap) --> Aquarius
	#
	# Look for the VOC moon prior to the sign ingress, e.g.
	# 00:00 Moon VOC until 07:01
	# 00.00 Moon enters Aquarius
	*/

	function getDailyAspects() {

	}

	/*     * *** Solar return and Horary Calculations ************* */

	/**
	 * CalculateSolarReturnTime()
	 * Calculate the Solar Return Time and calulate the Aspect to Natal chart
	 */
	function CalculateSolarReturnTime($birthDTO, $year) {
		global $logger;
		$logger->debug("AstrologServices::CalculateSolarReturnTime()");

		$this->m_pipe = new AstrologCalculator();

		/* format the birth data */
		$this->m_pipe->addArgument($birthDTO->qaSwitchFormat());

		/* Time Zone Set up*/
		//http://www.bonniehill.net/pages.aux/astrology/astrolog/Config.DAT
		$this->m_pipe->addArgument('-z0 ' . $birthDTO->m_summertime_offset); 		//Default Daylight time setting   [0 standard, 1 daylight]
		$this->m_pipe->addArgument('-z ' . $birthDTO->m_timezone_offset);  			//Default time zone               [hours before GMT      ]
		
		//RESTRICTING ALL the other planets  (-R0 sun -RT0 sun) 
		$this->m_pipe->addArgument("-R0 sun -RT0 sun");
		
		/* Requesting TRANSIT FOR SUN -solar return */
		//$this->m_pipe->addArgument("-ty " . $year);
		$this->m_pipe->addArgument("-tr $birthDTO->month $year");

		$this->m_pipe->addArgument("-sd");

		/* finally call Astrolog */
		$this->m_pipe->callAstrolog();

		$logger->debug("AstrologServices::calcDynamicAspects: cache=" . $this->m_pipe->getCache());
		$lines = explode("\n", $this->m_pipe->getCache());

		$this->GetSolarReturnTimeAndDate($lines);

		$this->m_pipe->teardown();
	}

	/*
	 #  1/14/2011  3:43pm trans     Sun (Cap) Opp natal (Can) Sun
	#  3/15/2011  1:32am trans     Sun (Pis) Tri natal (Can) Sun
	#  4/14/2011 10:00am trans     Sun (Ari) Squ natal (Can) Sun
	#  5/15/2011  6:51am trans     Sun (Tau) Sex natal (Can) Sun
	#  7/17/2011 12:19am trans     Sun (Can) Con natal (Can) Sun (Solar Return)
	#  9/17/2011  8:39am trans     Sun (Vir) Sex natal (Can) Sun
	# 10/17/2011  8:34pm trans     Sun (Lib) Squ natal (Can) Sun
	# 11/16/2011  8:19pm trans     Sun (Sco) Tri natal (Can) Sun
	# 0123456789012345678901234567890123456789012345678901234567890123456789012	   
	*/
	function GetSolarReturnTimeAndDate($lines) {
		foreach ($lines as $line) {
			if (strlen($line) > 58) {
				if (strtolower(substr($line, 60, 12)) === 'solar return') {
					//Setting Date.
					$this->solarReturnDate = intval(substr($line, 6, 4));
					$this->solarReturnDate .= sprintf("%02d", intval(substr($line, 0, 2)));
					$this->solarReturnDate .= intval(substr($line, 3, 2));

					//Setting Time
					$this->solarReturnTime = substr($line, 11, 7);
					//$this->solarReturnTime = DATE("H:i", STRTOTIME('14:41 AM'));
					break;
				}
			}
		}
	}
	 
	/**
	 * CalculateSolarReturnChart()
	 *
	 * Calculate Solar Return
	 *
	 * @param BirthData $data
	 */
	function CalculateSolarReturnChart($data) {
		global $logger;
		$logger->debug("AstrologServices::CalculateSolarReturnChart()");

		$this->m_pipe = new AstrologCalculator();

		/* format the birth data */
		$this->m_pipe->addArgument($data->qaSwitchFormatForSolarReturn($this->solarReturnDate, $this->solarReturnTime));

		/* display locations in degrees */
		$this->m_pipe->addArgument('-sd');

		/* finally call Astrolog */
		$this->m_pipe->callAstrolog();

		$logger->debug("AstrologServices::calcDynamicAspects: cache=" . $this->m_pipe->getCache());
		$lines = explode("\n", $this->m_pipe->getCache());
		
		$this->solarChart = array();
		for ($line = 0; $line < count($lines); $line++) {		
			//$this->getPlanetaryLongitude($lines[$line]);   				//FOR SERVER
			$this->getPlanetaryLongitudeLocalSystem(($lines[$line]));		//FOR LOCAL TESTING
		}
		
		foreach ($lines as $line) {
			//$this->getHouseCuspLongitude($line);						//FOR SERVER
			$this->getHouseCuspLongitudeLocalSystem($line);				//FOR LOCAL TESTING
		}

		$this->solarChart = $this->m_object;

		//$this->getAspects($lines);
		$this->GetAspectForSolarReturn($lines);

		$this->m_pipe->teardown();
	}

	/**
	 * CalculateSolarReturn()
	 *
	 * Calculate Solar Return aspects
	 *
	 * @param BirthData $data
	 * @param int $year
	 */
	function CalculateSolarReturn($data, $year) {
		global $logger;
		$logger->debug("AstrologServices::CalculateSolarReturn()");
		 
		$this->m_pipe = new AstrologCalculator();

		/* format the birth data */
		$this->m_pipe->addArgument($data->qaSwitchFormatForSolarReturn($this->solarReturnDate, $this->solarReturnTime));

		/* include the angles as objects */
// 		$this->m_pipe->addArgument('=C');

// 		/* ptolemaic aspects only used */
// 		$this->m_pipe->addArgument('-A 5');

// /* orbs used */
// 		$this->m_pipe->addArgument('-YAo 1 5	 9.0  9.0 6.0  6.0 4.0');  /* standard	 */
// 		#	$this->m_pipe->addArgument('-YAo 1 5	 5.0  4.0 3.0  2.0 2.0');		/* strong	*/
// 		#	$this->m_pipe->addArgument('-YAo 1 5	 9.0  8.0 6.0  6.0 4.0');		/* medium	*/
// 			$this->m_pipe->addArgument('-YAo 1 5	14.0 12.0 9.0 11.0 7.0');		/* weak		*/
// 			$this->m_pipe->addArgument('-YAo 1 5	10.0 8.0 8.0 6.0 3.0');		/* Customised Orbs		*/
 			$this->m_pipe->addArgument('-YAo 1 5	9.0 8.0 8.0 6.0 3.0');		/* Customised Orbs		*/

// 		/* max permissable orbs */
// 		$this->m_pipe->addArgument('-YAm 1 10	360 360 360 360 360 360 360 360 360 360');
// 		$this->m_pipe->addArgument('-YAm 11 20	360 360 360 360 360   3   3   3   3   3');
// 		$this->m_pipe->addArgument('-YAm 21 32	360 360 360 360 360 360 360 360 360 360 360 360');
// 		$this->m_pipe->addArgument('-YAm 33 41	360 360 360 360 360 360 360 360 360');

// 		/* planet aspect orb additions */
// 		$this->m_pipe->addArgument('-YAd 1 10	0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0');
// 		$this->m_pipe->addArgument('-YAd 11 20	0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0');
// 		$this->m_pipe->addArgument('-YAd 21 32	0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0');

/* just in case the aspect angles are not defined ... */
// 		$this->m_pipe->addArgument('-YAa 1 5	0.0 180.0 90.0 120.0 60.0');

		/* manage aspected object settings */
		$this->m_pipe->addArgument('-YR  1 10 0 0 0 0 0 0 0 0 0 0');  /* main planets enabled */
		$this->m_pipe->addArgument('-YR 11 20 1 1 1 1 1 0 0 1 1 1');  /* node enabled */
		$this->m_pipe->addArgument('-YR 21 32 0 1 1 1 1 1 1 1 1 1 1 1'); /* ascendant enabled */
		$this->m_pipe->addArgument('-YR 33 41 1 1 1 1 1 1 1 1 1');   /* all else disabled */

		/* display locations in degrees */
		$this->m_pipe->addArgument('-sd');
		
		/* request aspect generation */		
		$this->m_pipe->addArgument('-a');
 
		/* finally call Astrolog */
		$this->m_pipe->callAstrolog();
		$logger->debug("AstrologServices::calcAspects: cache=" . $this->m_pipe->getCache());
		$lines = explode("\n", $this->m_pipe->getCache());
				
		$this->GetAspectForSolarReturn($lines);
				
		$this->m_pipe->teardown();
	}

	/**
	 * CalculateHorary()
	 * CalculateHorary chart data
	 */
	function CalculateHorary($birthDTO) {
		global $logger;
		$logger->debug("AstrologServices::CalculateSolarReturnCrossAspect()");

		$this->m_pipe = new AstrologCalculator();

		/* format the birth data */
		//$this->m_pipe->addArgument($birthDTO->qaSwitchFormatForSolarReturn($this->solarReturnDate, $this->solarReturnTime));
		$this->m_pipe->addArgument($birthDTO->qaSwitchFormat());

		/* display locations in degrees */
		$this->m_pipe->addArgument('-sd');

		/* finally call Astrolog */
		$this->m_pipe->callAstrolog();
		$logger->debug("AstrologServices::CalculateHorary: cache=" . $this->m_pipe->getCache());
		$lines = explode("\n", $this->m_pipe->getCache());
		$this->horaryChart = array();

		for ($line = 0; $line < count($lines); $line++) {
			$this->getPlanetaryLongitude($lines[$line]);
		}

		foreach ($lines as $line) {
			$this->getHouseCuspLongitude($line);
		}
		$this->horaryChart = $this->m_object;

		$this->GetAspectForSolarReturn($lines);
		$this->m_pipe->teardown();
	}

	/**
	 * CalculateSolarReturnToNatalAspects()
	 * Calculating Aspect Solar return to Natal
	 */
	function CalculateSolarReturnToNatalAspects() {
		global $logger;
		$logger->debug("AstrologServices::CalculateSolarReturnToNatalAspects()");
		 
		$this->m_pipe = new AstrologCalculator();
		 
		/* format the birth data */
		$this->m_pipe->addArgument($birthDTO->qaSwitchFormatForSolarReturn($this->solarReturnDate, $this->solarReturnTime));
		//$this->m_pipe->addArgument($birthDTO->qaSwitchFormat());
		 
		/* include the angles as objects */
		$this->m_pipe->addArgument('=C');
		 
		/* ptolemaic aspects only used */
		$this->m_pipe->addArgument('-A 5');
		 
		/* orbs used */
		$this->m_pipe->addArgument('-YAo 1 5	 9.0  9.0 6.0  6.0 4.0');  /* standard	 */
		 
		/* max permissable orbs */
		$this->m_pipe->addArgument('-YAm 1 10	360 360 360 360 360 360 360 360 360 360');
		$this->m_pipe->addArgument('-YAm 11 20	360 360 360 360 360   3   3   3   3   3');
		$this->m_pipe->addArgument('-YAm 21 32	360 360 360 360 360 360 360 360 360 360 360 360');
		$this->m_pipe->addArgument('-YAm 33 41	360 360 360 360 360 360 360 360 360');
		 
		/* planet aspect orb additions */
		$this->m_pipe->addArgument('-YAd 1 10	0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0');
		$this->m_pipe->addArgument('-YAd 11 20	0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0');
		$this->m_pipe->addArgument('-YAd 21 32	0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0');
		 
		/* manage aspected object settings */
		$this->m_pipe->addArgument('-YR  1 10 0 0 0 0 0 0 0 0 0 0');  	/* main planets enabled */
		$this->m_pipe->addArgument('-YR 11 20 1 1 1 1 1 0 1 1 1 1');  	/* node enabled */
		$this->m_pipe->addArgument('-YR 21 32 0 1 1 1 1 1 1 1 1 1 1 1'); /* ascendant enabled */
		$this->m_pipe->addArgument('-YR 33 41 1 1 1 1 1 1 1 1 1');   /* all else disabled */
		 
		/* request aspect generation */
		$this->m_pipe->addArgument('-a');
		 
		/* finally call Astrolog */
		$this->m_pipe->callAstrolog();
		$logger->debug("AstrologServices::calcAspects: cache=" . $this->m_pipe->getCache());
		 
		$lines = explode("\n", $this->m_pipe->getCache());
		 
		$this->GetAspectForSolarReturn($lines);
		$this->CalulateAspectToSolarReturnToHorary();
		$this->m_pipe->teardown();
	}


	/**
	 * CalculateSolarReturnCrossAspect
	 * Calculate Cross Aspect to Solar return to Horary
	 */
	function CalculateHoraryAspects($birthDTO) {
		global $logger;
		$logger->debug("AstrologServices::CalculateSolarReturnCrossAspect()");

		$this->m_pipe = new AstrologCalculator();

		/* format the birth data */
		//$this->m_pipe->addArgument($birthDTO->qaSwitchFormatForSolarReturn($this->solarReturnDate, $this->solarReturnTime));
		$this->m_pipe->addArgument($birthDTO->qaSwitchFormat());

		/* include the angles as objects */
		$this->m_pipe->addArgument('=C');

		/* ptolemaic aspects only used */
		$this->m_pipe->addArgument('-A 5');

		/* orbs used */
		$this->m_pipe->addArgument('-YAo 1 5	 9.0  9.0 6.0  6.0 4.0');  /* standard	 */

		/* max permissable orbs */
		$this->m_pipe->addArgument('-YAm 1 10	360 360 360 360 360 360 360 360 360 360');
		$this->m_pipe->addArgument('-YAm 11 20	360 360 360 360 360   3   3   3   3   3');
		$this->m_pipe->addArgument('-YAm 21 32	360 360 360 360 360 360 360 360 360 360 360 360');
		$this->m_pipe->addArgument('-YAm 33 41	360 360 360 360 360 360 360 360 360');

		/* planet aspect orb additions */
		$this->m_pipe->addArgument('-YAd 1 10	0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0');
		$this->m_pipe->addArgument('-YAd 11 20	0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0');
		$this->m_pipe->addArgument('-YAd 21 32	0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0');

		/* manage aspected object settings */
		$this->m_pipe->addArgument('-YR  1 10 0 0 0 0 0 0 0 0 0 0');  /* main planets enabled */
		$this->m_pipe->addArgument('-YR 11 20 1 1 1 1 1 0 1 1 1 1');  /* node enabled */
		$this->m_pipe->addArgument('-YR 21 32 0 1 1 1 1 1 1 1 1 1 1 1'); /* ascendant enabled */
		$this->m_pipe->addArgument('-YR 33 41 1 1 1 1 1 1 1 1 1');   /* all else disabled */

		/* request aspect generation */
		$this->m_pipe->addArgument('-a');

		/* finally call Astrolog */
		$this->m_pipe->callAstrolog();
		$logger->debug("AstrologServices::calcAspects: cache=" . $this->m_pipe->getCache());

		$lines = explode("\n", $this->m_pipe->getCache());

		//Calulate Horary aspects
		$this->GetAspectForSolarReturn($lines);
		//$this->CalulateAspectToSolarReturnToHorary();
		//Calulate cross aspect to Natal
		$this->CalulateAspectHoraryToNatal();
		$this->m_pipe->teardown();
	}

	/**
	 * CalulateAspectForSolarReturnToNatal()
	 * Cross aspecting form Solar return to natal chart
	 * Function is use to check cross aspect to natal chart
	 */
	function CalulateAspectForSolarReturnToNatal() {
		global $logger;

		if ($this->m_timeddata === true) {
			$loopCount = 11;
			//$loopCount = 15;
		} else {
			$loopCount = 12;
		}

		$this->aspectSolarToNatal = array();

		$planetName = array(
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
				"1010" => "NNode",
				"1011" => "SNode",
				"1012" => "Ascendant",
				"1013" => "Midheaven", /* "MC", */
				"1014" => "IC",
				"1015" => "Descendant"
		);
		$aspectName = array(
				"000" => "Con",
				"060" => "Sex",
				"090" => "Squ",
				"120" => "Tri",
				"180" => "Opp"
		);
		$aspectOrbs = array(
				"000" => 10,	//"Conjunction",
				"060" => 3,		//"Sextile",
				"090" => 6,		//"Square",
				"120" => 8,		//"Trine",
				"180" => 8,		//"Opposition"
		);		
		
		for ($outerIndex = 0; $outerIndex < $loopCount; $outerIndex++) {
			for ($innerIndex = 0; $innerIndex < $loopCount; $innerIndex++) {
				$aspectIndex = "000";
				$degree = Abs($this->natalChart[$planetName[$outerIndex + 1000]]['longitude'] - $this->solarChart[$planetName[$innerIndex + 1000]]['longitude']);

				if ($degree > 180) {
					$degree = 360 - $degree;
				}				

				// set orb - 8 if Sun or Moon, 6 if not Sun or Moon
//				if ($outerIndex == 12 || $innerIndex == 12) {
//					$orb = 3;
//				} elseif ($outerIndex == 14 || $innerIndex == 14) {
//					$orb = 3;
//				} elseif ($outerIndex == 0 || $outerIndex == 1 || $innerIndex == 0 || $innerIndex == 1) {
//					$orb = 10;
//				} else {
//					$orb = 10;
//				}
				
				//Checking orb based on Aspect order
				if ($degree <= $aspectOrbs['000']) {																	//Conjunction
					$aspectIndex = '000';
					$degreeX = $degree;
				} elseif (($degree <= (60 + $aspectOrbs['060'])) And ($degree >= (60 - $aspectOrbs['060']))) {			//Sextile
					$aspectIndex = '060';
					$degreeX = $degree - 60;
				} elseif (($degree <= (90 + $aspectOrbs['090'])) And ($degree >= (90 - $aspectOrbs['090']))) {			//Square	
					$aspectIndex = '090';
					$degreeX = $degree - 90;
				} elseif (($degree <= (120 + $aspectOrbs['120'])) And ($degree >= (120 - $aspectOrbs['120']))) {		//Trine
					$aspectIndex = '120';
					$degreeX = $degree - 120;
				} elseif ($degree >= (180 - $aspectOrbs['180'])) {														//Opposition
					$aspectIndex = '180';
					$degreeX = 180 - $degree;
				} else {
					$aspectIndex = -1;
				}
				
// is there an aspect within orb?
//				if ($degree <= $orb) {
//					$aspectIndex = '000';
//					$degreeX = $degree;
//				} elseif (($degree <= (60 + $orb)) And ($degree >= (60 - $orb))) {
//					$aspectIndex = '060';
//					$degreeX = $degree - 60;
//				} elseif (($degree <= (90 + $orb)) And ($degree >= (90 - $orb))) {
//					$aspectIndex = '090';
//					$degreeX = $degree - 90;
//				} elseif (($degree <= (120 + $orb)) And ($degree >= (120 - $orb))) {
//					$aspectIndex = '120';
//					$degreeX = $degree - 120;
//				} elseif ($degree >= (180 - $orb)) {
//					$aspectIndex = '180';
//					$degreeX = 180 - $degree;					
//				} else {
//					$aspectIndex = -1;
//				}
				
				if($innerIndex == 0 && $outerIndex == 0){
					$aspectIndex = -1;
				}
				
				//if ($aspectIndex > 0 And $innerIndex != $outerIndex) {
				if ($aspectIndex >= 0 && $innerIndex != $outerIndex) {
					// aspect exists
					array_push($this->aspectSolarToNatal, sprintf("%04d%03d%04d %.2f", $innerIndex + 1000, $aspectIndex, $outerIndex + 1000, abs($degreeX)));
					$logger->debug("CalulateAspectForSolarReturnToNatal() :: " . sprintf("%04d%03d%04d %.2f", $innerIndex + 1000, $aspectIndex, $outerIndex + 1000, abs($degreeX)));
				}
			}
		}
		
		//CHECKING ASPECT FOR Ascendant
		for ($innerIndex = 0; $innerIndex < $loopCount; $innerIndex++) {
			$aspectIndex = "000";
			$degree = Abs($this->natalChart[$planetName[$innerIndex + 1000]]['longitude'] - $this->solarChart['cusp'][1]);

			if ($degree > 180) {
				$degree = 360 - $degree;
			}

			// set orb - 8 if Sun or Moon, 6 if not Sun or Moon
//			if ($innerIndex == 12) {
//				$orb = 3;
//			} elseif ($innerIndex == 14) {
//				$orb = 3;
//			} elseif ($innerIndex == 0 || $innerIndex == 1) {
//				$orb = 8;
//			} else {
//				$orb = 10;
//			}
			
			if ($degree <= $aspectOrbs['000']) {
				$aspectIndex = '000';
				$degreeX = $degree;
			} elseif (($degree <= (60 + $aspectOrbs['060'])) And ($degree >= (60 - $aspectOrbs['060']))) {
				$aspectIndex = '060';
				$degreeX = $degree - 60;
			} elseif (($degree <= (90 + $aspectOrbs['090'])) And ($degree >= (90 - $aspectOrbs['090']))) {
				$aspectIndex = '090';
				$degreeX = $degree - 90;
			} elseif (($degree <= (120 + $aspectOrbs['120'])) And ($degree >= (120 - $aspectOrbs['120']))) {
				$aspectIndex = '120';
				$degreeX = $degree - 120;
			} elseif ($degree >= (180 - $aspectOrbs['180'])) {
				$aspectIndex = '180';
				$degreeX = 180 - $degree;
			} else{
				$aspectIndex = -1;
			}

			// is there an aspect within orb?
//			if ($degree <= $orb) {
//				$aspectIndex = '000';
//				$degreeX = $degree;
//			} elseif (($degree <= (60 + $orb)) And ($degree >= (60 - $orb))) {
//				$aspectIndex = '060';
//				$degreeX = $degree - 60;
//			} elseif (($degree <= (90 + $orb)) And ($degree >= (90 - $orb))) {
//				$aspectIndex = '090';
//				$degreeX = $degree - 90;
//			} elseif (($degree <= (120 + $orb)) And ($degree >= (120 - $orb))) {
//				$aspectIndex = '120';
//				$degreeX = $degree - 120;
//			} elseif ($degree >= (180 - $orb)) {
//				$aspectIndex = '180';
//				$degreeX = 180 - $degree;
//			} else{
//				$aspectIndex = -1;
//			}

			//if ($aspectIndex > 0 And $innerIndex != $outerIndex) {
			if ($aspectIndex >= 0 ) {					
				// aspect exists
				array_push($this->aspectSolarToNatal, sprintf("%04d%03d%04d %.2f", 1012, $aspectIndex, $innerIndex + 1000, abs($degreeX)));
				$logger->debug("CalulateAspectForSolarReturnToNatal() :: " . sprintf("%04d%03d%04d %.2f", 1012, $aspectIndex, $outerIndex + 1000, abs($degreeX)));
			}
		}		
		
		//CHECKING ASPECT FOR Midheaven
		for ($innerIndex = 0; $innerIndex < $loopCount; $innerIndex++) {
			$aspectIndex = "000";
			$degree = Abs($this->natalChart[$planetName[$innerIndex + 1000]]['longitude'] - $this->solarChart['cusp'][10]);

			if ($degree > 180) {
				$degree = 360 - $degree;
			}

			// set orb - 8 if Sun or Moon, 6 if not Sun or Moon
//			if ($innerIndex == 12) {
//				$orb = 3;
//			} elseif ($innerIndex == 14) {
//				$orb = 3;
//			} elseif ($innerIndex == 0 || $innerIndex == 1) {
//				$orb = 8;
//			} else {
//				$orb = 10;
//			}

			if ($degree <= $aspectOrbs['000']) {
				$aspectIndex = '000';
				$degreeX = $degree;
			} elseif (($degree <= (60 + $aspectOrbs['060'])) And ($degree >= (60 - $aspectOrbs['060']))) {
				$aspectIndex = '060';
				$degreeX = $degree - 60;
			} elseif (($degree <= (90 + $aspectOrbs['090'])) And ($degree >= (90 - $aspectOrbs['090']))) {
				$aspectIndex = '090';
				$degreeX = $degree - 90;
			} elseif (($degree <= (120 + $aspectOrbs['120'])) And ($degree >= (120 - $aspectOrbs['120']))) {
				$aspectIndex = '120';
				$degreeX = $degree - 120;
			} elseif ($degree >= (180 - $aspectOrbs['180'])) {
				$aspectIndex = '180';
				$degreeX = 180 - $degree;
			} else {
				$aspectIndex = -1;
			}	

			// is there an aspect within orb?
//			if ($degree <= $orb) {
//				$aspectIndex = '000';
//				$degreeX = $degree;
//			} elseif (($degree <= (60 + $orb)) And ($degree >= (60 - $orb))) {
//				$aspectIndex = '060';
//				$degreeX = $degree - 60;
//			} elseif (($degree <= (90 + $orb)) And ($degree >= (90 - $orb))) {
//				$aspectIndex = '090';
//				$degreeX = $degree - 90;
//			} elseif (($degree <= (120 + $orb)) And ($degree >= (120 - $orb))) {
//				$aspectIndex = '120';
//				$degreeX = $degree - 120;
//			} elseif ($degree >= (180 - $orb)) {
//				$aspectIndex = '180';
//				$degreeX = 180 - $degree;
//			} else {
//				$aspectIndex = -1;
//			}			

			if ($aspectIndex >= 0) {
				// aspect exists
				array_push($this->aspectSolarToNatal, sprintf("%04d%03d%04d %.2f", 1013, $aspectIndex, $innerIndex + 1000, abs($degreeX)));
				$logger->debug("CalulateAspectForSolarReturnToNatal() :: " . sprintf("%04d%03d%04d %.2f", 1013, $aspectIndex, $outerIndex + 1000, abs($degreeX)));
			}
		}
	}

	function CalulateAspectHoraryToNatal() {
		global $logger;

		if ($this->m_timeddata === true) {
			$loopCount = 11;
			//$loopCount = 15;
		} else {
			$loopCount = 12;
		}

		$this->aspectHoraryToNatal = array();

		$planetName = array(
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
				"1010" => "NNode",
				"1011" => "SNode",
				"1012" => "Ascendant",
				"1013" => "Midheaven", /* "MC", */
				"1014" => "IC",
				"1015" => "Descendant"
		);
		$aspectName = array(
				"000" => "Con",
				"060" => "Sex",
				"090" => "Squ",
				"120" => "Tri",
				"180" => "Opp"
		);

		for ($outerIndex = 0; $outerIndex < $loopCount; $outerIndex++) {
			for ($innerIndex = 0; $innerIndex < $loopCount; $innerIndex++) {
				$aspectIndex = "000";

				$degree = Abs($this->natalChart[$planetName[$outerIndex + 1000]]['longitude'] - $this->horaryChart[$planetName[$innerIndex + 1000]]['longitude']);

				if ($degree > 180) {
					$degree = 360 - $degree;
				}

				// set orb - 8 if Sun or Moon, 6 if not Sun or Moon
				if ($outerIndex == 12 Or $innerIndex == 12) {
					$orb = 3;
				} elseif ($outerIndex == 14 Or $innerIndex == 14) {
					$orb = 3;
				} elseif ($outerIndex == 0 Or $outerIndex == 1 Or $innerIndex == 0 Or $innerIndex == 1) {
					$orb = 8;
				} else {
					$orb = 6;
				}

				// is there an aspect within orb?
				if ($degree <= $orb) {
					$aspectIndex = '000';
					$degreeX = $degree;
				} elseif (($degree <= (60 + $orb)) And ($degree >= (60 - $orb))) {
					$aspectIndex = '060';
					$degreeX = $degree - 60;
				} elseif (($degree <= (90 + $orb)) And ($degree >= (90 - $orb))) {
					$aspectIndex = '090';
					$degreeX = $degree - 90;
				} elseif (($degree <= (120 + $orb)) And ($degree >= (120 - $orb))) {
					$aspectIndex = '120';
					$degreeX = $degree - 120;
				} elseif ($degree >= (180 - $orb)) {
					$aspectIndex = '180';
					$degreeX = 180 - $degree;
				}

				if ($aspectIndex > 0 And $innerIndex != $outerIndex) {
					// aspect exists
					array_push($this->aspectHoraryToNatal, sprintf("%04d%03d%04d %.2f", $innerIndex + 1000, $aspectIndex, $outerIndex + 1000, abs($degreeX)));
					$logger->debug("CalulateAspectHoraryToNatal() :: " . sprintf("%04d%03d%04d %.2f", $innerIndex + 1000, $aspectIndex, $outerIndex + 1000, abs($degreeX)));
				}
			}
		}
	}

	function CalulateAspectToSolarReturnToHorary() {
		global $logger;

		if ($this->m_timeddata === true) {
			$loopCount = 11;
			//$loopCount = 15;
		} else {
			$loopCount = 12;
		}

		$this->aspectSolarToHorary = array();

		$planetName = array(
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
				"1010" => "NNode",
				"1011" => "SNode",
				"1012" => "Ascendant",
				"1013" => "Midheaven", /* "MC", */
				"1014" => "IC",
				"1015" => "Descendant"
		);
		$aspectName = array(
				"000" => "Con",
				"060" => "Sex",
				"090" => "Squ",
				"120" => "Tri",
				"180" => "Opp"
		);

		for ($outerIndex = 0; $outerIndex < $loopCount; $outerIndex++) {
			for ($innerIndex = 0; $innerIndex < $loopCount; $innerIndex++) {
				$aspectIndex = "000";

				$degree = Abs($this->solarChart[$planetName[$outerIndex + 1000]]['longitude'] - $this->horaryChart[$planetName[$innerIndex + 1000]]['longitude']);

				if ($degree > 180) {
					$degree = 360 - $degree;
				}

				// set orb - 8 if Sun or Moon, 6 if not Sun or Moon
				if ($outerIndex == 12 Or $innerIndex == 12) {
					$orb = 3;
				} elseif ($outerIndex == 14 Or $innerIndex == 14) {
					$orb = 3;
				} elseif ($outerIndex == 0 Or $outerIndex == 1 Or $innerIndex == 0 Or $innerIndex == 1) {
					$orb = 8;
				} else {
					$orb = 6;
				}

				// is there an aspect within orb?
				if ($degree <= $orb) {
					$aspectIndex = '000';
					$degreeX = $degree;
				} elseif (($degree <= (60 + $orb)) And ($degree >= (60 - $orb))) {
					$aspectIndex = '060';
					$degreeX = $degree - 60;
				} elseif (($degree <= (90 + $orb)) And ($degree >= (90 - $orb))) {
					$aspectIndex = '090';
					$degreeX = $degree - 90;
				} elseif (($degree <= (120 + $orb)) And ($degree >= (120 - $orb))) {
					$aspectIndex = '120';
					$degreeX = $degree - 120;
				} elseif ($degree >= (180 - $orb)) {
					$aspectIndex = '180';
					$degreeX = 180 - $degree;
				}

				if ($aspectIndex > 0 And $innerIndex != $outerIndex) {
					// aspect exists
					array_push($this->aspectSolarToHorary, sprintf("%04d%03d%04d %.2f", $innerIndex + 1000, $aspectIndex, $outerIndex + 1000, abs($degreeX)));
					$logger->debug("CalulateAspectToSolarReturnToHorary() :: " . sprintf("%04d%03d%04d %.2f", $innerIndex + 1000, $aspectIndex, $outerIndex + 1000, abs($degreeX)));
				}
			}
		}
	}

	function GetAspectForSolarReturn($lines) {
		global $logger;
		$logger->debug("AstrologServices::getAspects");

		$this->m_aspect = array();

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
				"1012" => "Ascendant",
				"1013" => "Midheaven", /* "MC", */
				"1014" => "IC",
				"1015" => "Descendant"
		);
		$aspects = array(
				"000" => "Con",
				"060" => "Sex",
				"090" => "Squ",
				"120" => "Tri",
				"180" => "Opp"
		);

		/*
		 * iterate through the results
		*/
		for ($line = 0; $line < count($lines); $line++) {
			/* skip the lead-in */
			if (substr($lines[$line], 3, 1) != ':') {
				continue;
			}

			/*
			 * isolate the subject
			*/
			$subject = trim(substr($lines[$line], 4, 8));
			$subjectvalue = 0;
			for ($i = 0; $i < count($planets); $i++) {
				if ($i >= 11) {
					$i++;
				}
				if ($planets[(1000 + $i)] == $subject) {
					$subjectvalue = intval((1000 + $i));
					break;
				}
			}

			/*
			 * isolate the aspect
			* - added orb here for the wheel's aspect grid
			*/
			$aspect = trim(substr($lines[$line], 18, 5));
			$aspect_orb = trim(substr($lines[$line], 47, 5));
			$aspectvalue = -1;
			reset($aspects);

			while ($aspectname = current($aspects)) {
				if ($aspectname == $aspect) {
					$aspectvalue = intval(key($aspects));
					break;
				}
				next($aspects);
			}

			/*
			 * isolate the object
			*/
			$object = trim(substr($lines[$line], 28, 11));
			$objectvalue = 0;

			for ($i = 0; $i < count($planets); $i++) {
				if ($i >= 11) {
					$i++;
				}
				/* look for a string match */
				if ($planets[(1000 + $i)] == $object) {
					$logger->debug("AstrologServices::getAspects - found subject[$subjectvalue], object[$objectvalue]");
					if ($object == 'Ascendant') {
						$logger->debug("AstrologServices::getAspects - reversing objects for Ascendant context");
						$objectvalue = $subjectvalue;
						$subjectvalue = intval((1000 + $i));
					} else {
						$objectvalue = intval((1000 + $i));
					}
					$logger->debug("AstrologServices::getAspects - result subject[$subjectvalue], object[$objectvalue]");
					break;
				} else {
					// something has appeared that we are not prepared for ... error */
				}
			}

			/*
			 * store the aspect
			* @todo vet the orbs and restrict further if required
			*/
			if ($subjectvalue != 0 and $aspectvalue != -1 and $objectvalue != 0) {
				//For solar return aspects
				//array_push($this->solarReturnAspects, sprintf("%04d%03d%04d %s", $subjectvalue, $aspectvalue, $objectvalue, $aspect_orb));
				array_push($this->m_aspect, sprintf("%04d%03d%04d %s", $subjectvalue, $aspectvalue, $objectvalue, $aspect_orb));
			} else {
				$logger->debug("failed to parse aspect line, line=" . $lines[$line]);
				$logger->error("failed to parse aspect line");
			}
		}
	}
	/**************   Solar return and Horary Calculations   **************/


	function CalculatePrograssionsChart($birthDTO, $day, $month, $year) {
		global $logger;
		$logger->debug("AstrologServices::CalculatePrograssionsChart()");

		$this->m_pipe = new AstrologCalculator();

		/* format the birth data */
		$this->m_pipe->addArgument($birthDTO->qaSwitchFormat());

		/* Time Zone Set up*/
		//http://www.bonniehill.net/pages.aux/astrology/astrolog/Config.DAT
		$this->m_pipe->addArgument('-z0 ' . $birthDTO->m_summertime_offset); 	//Default Daylight time setting   [0 standard, 1 daylight]
		$this->m_pipe->addArgument('-z ' . $birthDTO->m_timezone_offset);  		//Default time zone               [hours before GMT      ]

		/*Requesting Prograssion */
		$this->m_pipe->addArgument(sprintf("-p %s %s %s",$month, $day, $year ));		//Cast 2ndary progressed chart for date.
		 
		/* display locations in degrees */
		$this->m_pipe->addArgument('-sd');
		 
		/* finally call Astrolog */
		$this->m_pipe->callAstrolog();

		$logger->debug("AstrologServices::CalculatePrograssionsChart: cache=" . $this->m_pipe->getCache());
		$lines = explode("\n", $this->m_pipe->getCache());

		for ($line = 0; $line < count($lines); $line++) {
			//$this->getPlanetaryLongitude($lines[$line]);				//FOR SERVER 
			$this->getPlanetaryLongitudeLocalSystem(($lines[$line]));	//FOR LOCAL SYSTEM
		}

		foreach ($lines as $line) {	
			//$this->getHouseCuspLongitude($line);				//FOR SERVER 
			$this->getHouseCuspLongitudeLocalSystem($line);		//FOR LOCAL SYSTEM
		}
		
		$this->progressionChart = $this->m_object;

		$this->m_pipe->teardown();
		 
		$this->CalculateProgressionAspects($birthDTO, $day, $month, $year);
	}

	/**
	 * CalculateProgressionAspects
	 * @param $birthDTO
	 * @param $day
	 * @param $month
	 * @param $year
	 */
	function CalculateProgressionAspects($birthDTO, $day, $month, $year) {
		global $logger;
		$logger->debug("AstrologServices::CalculateProgressionAspects()");
		/*
		 * determine the aspects
		*/
		$this->m_pipe = new AstrologCalculator();

		/* format the birth data */
		$this->m_pipe->addArgument($birthDTO->qaSwitchFormat());
		 
		/* Time Zone Set up*/
		$this->m_pipe->addArgument('-z0 ' . $birthDTO->m_summertime_offset); 			//Default Daylight time setting   [0 standard, 1 daylight]
		$this->m_pipe->addArgument('-z ' . $birthDTO->m_timezone_offset);  				//Default time zone               [hours before GMT      ]
		 
		/*Requesting Prograssion */
		$this->m_pipe->addArgument(sprintf("-p %s %s %s",$month, $day, $year ));		//Cast 2ndary progressed chart for date.

		/* include the angles as objects */
		$this->m_pipe->addArgument('=C');

		/* ptolemaic aspects only used */
		$this->m_pipe->addArgument('-A 5');

		/* orbs used */
		$this->m_pipe->addArgument('-YAo 1 5	 9.0  9.0 6.0  6.0 4.0');                       /* standard	 */

		/* max permissable orbs */
		$this->m_pipe->addArgument('-YAm 1 10	360 360 360 360 360 360 360 360 360 360');
		$this->m_pipe->addArgument('-YAm 11 20	360 360 360 360 360   3   3   3   3   3');
		$this->m_pipe->addArgument('-YAm 21 32	360 360 360 360 360 360 360 360 360 360 360 360');
		$this->m_pipe->addArgument('-YAm 33 41	360 360 360 360 360 360 360 360 360');

		/* planet aspect orb additions */
		$this->m_pipe->addArgument('-YAd 1 10	0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0');
		$this->m_pipe->addArgument('-YAd 11 20	0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0');
		$this->m_pipe->addArgument('-YAd 21 32	0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0');

		/* manage aspected object settings */
		$this->m_pipe->addArgument('-YR  1 10 0 0 0 0 0 0 0 0 0 0');        /* main planets enabled */
		$this->m_pipe->addArgument('-YR 11 20 1 1 1 1 1 0 1 1 1 1');        /* node enabled */
		$this->m_pipe->addArgument('-YR 21 32 0 1 1 1 1 1 1 1 1 1 1 1');    /* ascendant enabled */
		$this->m_pipe->addArgument('-YR 33 41 1 1 1 1 1 1 1 1 1');          /* all else disabled */

		/* request aspect generation */
		$this->m_pipe->addArgument('-a');

		/* finally call Astrolog */
		$this->m_pipe->callAstrolog();
		$logger->debug("AstrologServices::CalculateProgressionAspects: cache=" . $this->m_pipe->getCache());
		$lines = explode("\n", $this->m_pipe->getCache());
		 
		$this->getAspects($lines);
		$this->m_pipe->teardown();
	}

	/**
	 * ProgressionToNatalAspects()
	 * 		Cakculate Progressed to Natal aspects
	 * @param $birthDTO
	 * @param $day
	 * @param $month
	 * @param $year
	 */
	function ProgressionToNatalAspects($birthDTO, $day, $month, $year) {
		global $logger;
		$logger->debug("AstrologServices::ProgressionToNatalAspects()");
		/*
		 * determine the aspects
		*/
		$this->m_pipe = new AstrologCalculator();

		/* format the birth data */
		$this->m_pipe->addArgument($birthDTO->qaSwitchFormat());

		/* Time Zone Set up*/
		$this->m_pipe->addArgument('-z0 ' . $birthDTO->m_summertime_offset); 			//Default Daylight time setting   [0 standard, 1 daylight]
		$this->m_pipe->addArgument('-z ' . $birthDTO->m_timezone_offset);  				//Default time zone               [hours before GMT      ]
		 
		/* orbs used */
		$this->m_pipe->addArgument('-YAo 1 5 8.0 8.0 6.0 5.0 5.0');
		 
		/* manage aspected object settings */
		$this->m_pipe->addArgument('-YR  1 10 0 0 0 0 0 0 0 0 0 0');  /* main planets enabled */
		$this->m_pipe->addArgument('-YR 11 20 1 1 1 1 1 0 1 1 1 1');  /* node enabled */
		$this->m_pipe->addArgument('-YR 21 32 0 1 1 1 1 1 1 1 1 1 1 1'); /* ascendant enabled */
		$this->m_pipe->addArgument('-YR 33 41 1 1 1 1 1 1 1 1 1');   /* all else disabled */
		 
		/*Requesting aspect from progressed to natal */
		$this->m_pipe->addArgument(sprintf("-Tp %s %s %s", $month, $day, $year ));
		$this->m_pipe->addArgument('-sd');

		/* finally call Astrolog */
		$this->m_pipe->callAstrolog();
		$logger->debug("AstrologServices::ProgressionToNatalAspects: cache=" . $this->m_pipe->getCache());
		$lines = explode("\n", $this->m_pipe->getCache());
		
		$this->GenerateProgressedToNatalAspectList($lines);
		$this->m_pipe->teardown();
	}

	function CalculateDynamicProgression($birthDTO, $StartYear, $Duration){
		global $logger;
		$logger->debug("AstrologServices::ProgressionToNatalAspects()");
		/*
		 * determine the progression
		*/
		$lines = array();
		$this->m_pipe = new AstrologCalculator();
		 
		/* format the birth data */
		$this->m_pipe->addArgument($birthDTO->qaSwitchFormat());
		 
		/* Time Zone Set up*/
		$this->m_pipe->addArgument('-z ' . $birthDTO->m_timezone_offset);  				//Default time zone               [hours before GMT      ]
		$this->m_pipe->addArgument('-z0 ' . $birthDTO->m_summertime_offset); 			//Default Daylight time setting   [0 standard, 1 daylight]

		/* include the angles as objects */
		$this->m_pipe->addArgument('=C');
		 
		/* ptolemaic aspects only used */
		$this->m_pipe->addArgument('-A 5');
		
		/* orbs used */
		$this->m_pipe->addArgument('-YAo 1 5 8.0 8.0 6.0 5.0 5.0');
		 
		/* manage aspected object settings ( DEFAULT PROGRESSION RESTRICTIONS ) */
		$this->m_pipe->addArgument('-YRP 1 10   0 0 0 0 0 0 0 0 0 0');			/* Planets */
		$this->m_pipe->addArgument('-YRP 11 20  0 1 1 1 1 0 1 1 1 1');			/* Minor planets */
		$this->m_pipe->addArgument('-YRP 21 32  0 1 1 1 1 1 1 1 1 0 1 1');		/* House cusps */
		$this->m_pipe->addArgument('-YRP 33 41  1 1 1 1 1 1 1 1 1 ');			/* Uranians */
		 
		/* manage transiting object settings ( DEFAULT TRANSIT RESTRICTIONS )*/
		$this->m_pipe->addArgument('-YRT 1 10   0 0 0 0 0 0 0 0 0 0');			/* Planets */
		$this->m_pipe->addArgument('-YRT 11 20  0 1 1 1 1 0 1 1 1 1');			/* Minor planets */
		$this->m_pipe->addArgument('-YRT 21 32  0 1 1 1 1 1 1 1 1 0 1 1');		/* House cusps */
		$this->m_pipe->addArgument('-YRT 33 41  1 1 1 1 1 1 1 1 1 ');			/* Uranians */
		$this->m_pipe->addArgument('-YR0 0 0');									/* Restrict sign, direction changes */
		 
		$this->m_pipe->addArgument(sprintf("-tpY %s %s", $StartYear, $Duration));
		 
		/* finally call Astrolog */
		$this->m_pipe->callAstrolog();
		$logger->debug("AstrologServices::ProgressionToNatalAspects: cache=" . $this->m_pipe->getCache());
		$lines = explode("\n", $this->m_pipe->getCache());		
		
		$this->m_transit = array();
		//Only check the Progression for planets
		$this->GetCalendarProgressedAspects($lines, true, false);
		
		$this->m_pipe->teardown();
	}

	function CalculateDynamicProgressionIngress($birthDTO, $StartYear, $Duration){
		global $logger;
		$logger->debug("AstrologServices::ProgressionToNatalAspects()");
		/*
		 * determine the progression
		*/
		$lines = array();
		$this->m_pipe = new AstrologCalculator();

		/* format the birth data */
		$this->m_pipe->addArgument($birthDTO->qaSwitchFormat());
		
		/* include the angles as objects */
		$this->m_pipe->addArgument('=C');
			
		/* ptolemaic aspects only used */
		$this->m_pipe->addArgument('-A 5');
		
		/* orbs used */
		$this->m_pipe->addArgument('-YAo 1 5 0.0 0.0 0.0 0.0 0.0');
		
		/* Time Zone Set up*/
		$this->m_pipe->addArgument('-z ' . $birthDTO->m_timezone_offset);  				//Default time zone               [hours before GMT      ]
		$this->m_pipe->addArgument('-z0 ' . $birthDTO->m_summertime_offset); 			//Default Daylight time setting   [0 standard, 1 daylight]

		/* manage aspected object settings ( DEFAULT PROGRESSION RESTRICTIONS ) */
		$this->m_pipe->addArgument('-YRP 1 10   0 0 0 0 0 0 0 0 0 0');			/* Planets */
		$this->m_pipe->addArgument('-YRP 11 20  0 1 1 1 1 1 1 1 1 1');			/* Minor planets */
		$this->m_pipe->addArgument('-YRP 21 32  0 0 0 0 0 0 0 0 0 0 0 0');		/* House cusps */
		$this->m_pipe->addArgument('-YRP 33 41  1 1 1 1 1 1 1 1 1 ');			/* Uranians */

		/* manage transiting object settings ( DEFAULT TRANSIT RESTRICTIONS )*/
		$this->m_pipe->addArgument('-YRT 1 10   0 0 0 0 0 0 0 0 0 0');			/* Planets */
		$this->m_pipe->addArgument('-YRT 11 20  0 1 1 1 1 1 1 1 1 1');			/* Minor planets */
		$this->m_pipe->addArgument('-YRT 21 32  0 0 0 0 0 0 0 0 0 0 0 0');		/* House cusps */
		$this->m_pipe->addArgument('-YRT 33 41  1 1 1 1 1 1 1 1 1 ');			/* Uranians */
		$this->m_pipe->addArgument('-YR0 0 0');									/* Restrict sign, direction changes */

		$this->m_pipe->addArgument(sprintf("-dpY %s %s", $StartYear, $Duration));
		
		/* finally call Astrolog */
		$this->m_pipe->callAstrolog();
		
		$logger->debug("AstrologServices::ProgressionToNatalAspects: cache=" . $this->m_pipe->getCache());
		$lines = explode("\n", $this->m_pipe->getCache());

		$this->m_crossing = array();
		
		//Only check the Progression for planets
		$this->GetProgressedSignChaging($lines);
		 
		$this->m_pipe->teardown();
	}
	
	/**
	 * 	CalcPrograssedHouseCrossing() Calculating House crossing
	 * @param Class $birthDTO
	 * @param Start Year $StartYear
	 * @param Total Duration $Duration
	 */
	function CalcPrograssedHouseCrossing($birthDTO, $StartYear, $Duration){
		global $logger;
		$logger->debug("AstrologServices::ProgressionToNatalAspects()");
		/*
		 * determine the progression
		*/
		$lines = array();
		$this->m_pipe = new AstrologCalculator();
			
		/* format the birth data */
		$this->m_pipe->addArgument($birthDTO->qaSwitchFormat());
			
		/* Time Zone Set up*/
		$this->m_pipe->addArgument('-z ' . $birthDTO->m_timezone_offset);  				//Default time zone               [hours before GMT      ]
		$this->m_pipe->addArgument('-z0 ' . $birthDTO->m_summertime_offset); 			//Default Daylight time setting   [0 standard, 1 daylight]
	
		/* include the angles as objects */
		$this->m_pipe->addArgument('=C');
			
		/* ptolemaic aspects only used */
		$this->m_pipe->addArgument('-A 5');
	
		/* orbs used */
		$this->m_pipe->addArgument('-YAo 1 5 8.0 0.0 0.0 0.0 0.0');
			
		/* manage aspected object settings ( DEFAULT PROGRESSION RESTRICTIONS ) */
		$this->m_pipe->addArgument('-YRP 1 10   0 0 0 0 0 0 0 0 0 0');			/* Planets */
		$this->m_pipe->addArgument('-YRP 11 20  0 1 1 1 1 0 1 1 1 1');			/* Minor planets */
		$this->m_pipe->addArgument('-YRP 21 32  0 0 0 0 0 0 0 0 0 0 0 0');		/* House cusps */
		$this->m_pipe->addArgument('-YRP 33 41  1 1 1 1 1 1 1 1 1 ');			/* Uranians */
			
		/* manage transiting object settings ( DEFAULT TRANSIT RESTRICTIONS )*/
		$this->m_pipe->addArgument('-YRT 1 10   0 0 0 0 0 0 0 0 0 0');			/* Planets */
		$this->m_pipe->addArgument('-YRT 11 20  0 1 1 1 1 0 1 1 1 1');			/* Minor planets */
		$this->m_pipe->addArgument('-YRT 21 32  0 0 0 0 0 0 0 0 0 0 0 0');		/* House cusps */
		$this->m_pipe->addArgument('-YRT 33 41  1 1 1 1 1 1 1 1 1 ');			/* Uranians */
		$this->m_pipe->addArgument('-YR0 0 0');									/* Restrict sign, direction changes */
			
		$this->m_pipe->addArgument(sprintf("-tpY %s %s", $StartYear, $Duration));		
		
		/* finally call Astrolog */
		$this->m_pipe->callAstrolog();
		$logger->debug("AstrologServices::ProgressionToNatalAspects: cache=" . $this->m_pipe->getCache());
		$lines = explode("\n", $this->m_pipe->getCache());
		
		$this->m_crossing = array();
		//Only check the Progression for planets
		$this->GetProgressedHouseChaging($lines);				
		$this->m_pipe->teardown();
	}
	

   /**
	* Total Char in one line (70) with space
	* for planet transit
	* 12345678901234567890123456789012345678901234567890123456789012345678901234567890
	*  4/20/2006  6:36pm progr    Mars (Leo) Sex natal (Lib) Ascendant
	*  5/ 6/2006  1:42am progr Mercury (Lib) Sex natal (Leo) Pluto
	* 10/22/2006  6:41am progr   Venus (Lib) Squ natal [Cap] Jupiter
	* 12/27/2009  3:36pm progr Midheav (Vir) Sex natal (Can) Uranus
	* 11/22/2012  8:42am progr Ascenda (Sco) Squ natal (Leo) Pluto
	*
	*/
	function GetCalendarProgressedAspects($lines, $dynamic = false, $crossing = false) {
		global $logger;
		$logger->debug("AstrologServices::getCalendarAspects");
		$progressedplanets = array(
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
				"1011" => "SNode",      /* put here to stop the nag messages, not used */
				"1012" => "Ascenda",	/* Ascendant "AS"  */
				"1013" => "Midheav",  	/* Midheaven "MC" */
				"1014" => "IC",
				"1015" => "Descendant"
		);
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
				"1011" => "SNode",      /* put here to stop the nag messages, not used */
				"1012" => "Ascenda",	/* Ascendant "AS"  */
				"1013" => "Midheaven",  	/* Midheaven "MC" */
				"1014" => "IC",
				"1015" => "Descendant"
		);
		$signs = array(
				"0001" => "Aries",
				"0002" => "Taurus",
				"0003" => "Gemini",
				"0004" => "Cancer",
				"0005" => "Leo",
				"0006" => "Virgo",
				"0007" => "Libra",
				"0008" => "Scorpio",
				"0009" => "Sagittarius",
				"0010" => "Capricorn",
				"0011" => "Aquarius",
				"0012" => "Pisces"
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
			$IsRatrograde = trim(substr($line, 33, 1));
			
			if($IsRatrograde == "(") {
				$IsRatrograde = 'D';
			}
			else if($IsRatrograde == "[") {
				$IsRatrograde = 'R';
			}
			else if($IsRatrograde == "<") {
				$IsRatrograde = 'D';
			}
			else{
				$IsRatrograde = 'D';
			}
				
			$logger->debug("AstrologServices::GetCalendarProgressedAspects - progressed planet = $trans");
			for ($i = 0; $i < count($progressedplanets); $i++) {
				if ($progressedplanets[(1000 + $i)] == $trans) {
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
				for ($i = 0; $i < count($signs); $i++) {
					/* if( $i >= 11 ) { $i++; } */
					if ($signs[sprintf("%04d", ($i + 1))] == $natal) {
						$natalvalue = intval($i + 1);
						$logger->debug("AstrologServices::GetCalendarProgressedAspects - transited cusp = $natalvalue, transiting planet = $transvalue");
						break; /* from for loop */
					}
				}
			} else {
				/* look for the natal object */
				$natal = trim(substr($line, 55, 10));

				$logger->debug("AstrologServices::GetCalendarProgressedAspects - transited object = $natal");
				for ($i = 0; $i < count($planets); $i++) {
					/* if( $i >= 11 ) { $i++; } */
					/*
					 * The following works on the basis that we look for the polled object
					* using the length of the string in the planets table. This should
					* prevent the (<object> Return) string factoring in the equation
					*/
					if ($planets[sprintf("%04d", (1000 + $i))] == substr($natal, 0, strlen($planets[sprintf("%04d", (1000 + $i))]))) {
						$natalvalue = intval((1000 + $i));
						#$logger->error("natalvalue=$natalvalue");
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
				$logger->debug("AstrologServices::GetCalendarProgressedAspects - static push");
				array_push(
						$this->m_aspect, /* seasonal/calendars */ sprintf("%04d-%02d-%02d %02d:%02d %04d%03d%04d %s",
								/* date			 */ intval(substr($line, 6, 4)), intval(substr($line, 0, 2)), intval(substr($line, 3, 2)),
								/* time			 */ intval(substr($line, 11, 2)), intval(substr($line, 14, 2)),
								/* transiting    */ $transvalue,
								/* aspect		 */ $aspectvalue,
								/* natal		 */ $natalvalue,
								/* Ratrograde	 */ $IsRatrograde
						)
				);
			} else {
				if ($crossing === true) {
					$logger->debug("AstrologServices::GetCalendarProgressedAspects - push crossing aspect");
					array_push(
							$this->m_crossing, /* Jupiter/Saturn house crossing */ sprintf("%04d-%02d-%02d %02d:%02d %04d%03d%04d %s",
									/* date			 */ intval(substr($line, 6, 4)), intval(substr($line, 3, 2)), intval(substr($line, 0, 2)),
									/* time			 */ intval(substr($line, 11, 2)), intval(substr($line, 14, 2)),
									/* transiting	 */ $transvalue,
									/* aspect		 */ $aspectvalue,
									/* natal		 */ $natalvalue,
									/* Ratrograde	 */ $IsRatrograde
									/* Last dot for sign changing		 */
							)
					);
					$logger->debug("AstrologServices::GetCalendarProgressedAspects - sign changing - crossing = " .
							sprintf("%04d-%02d-%02d %02d:%02d %04d%03d%04d %s",
									/* date			 */ intval(substr($line, 6, 4)), intval(substr($line, 3, 2)), intval(substr($line, 0, 2)),
									/* time			 */ intval(substr($line, 11, 2)), intval(substr($line, 14, 2)),
									/* transiting	 */ $transvalue,
									/* aspect		 */ $aspectvalue,
									/* natal		 */ $natalvalue,
									/* Ratrograde	 */ $IsRatrograde
									/* Last dot for sign changing		 */
							)
					);
				} else {
					$logger->debug("AstrologServices::GetCalendarProgressedAspects - push progression");
					array_push(
							$this->m_transit, /* Transiting aspects */ sprintf("%04d-%02d-%02d %02d:%02d %04d%03d%04d %s",
									/* date			 */ intval(substr($line, 6, 4)), intval(substr($line, 3, 2)), intval(substr($line, 0, 2)),
									/* time			 */ intval(substr($line, 11, 2)), intval(substr($line, 14, 2)),
									/* transiting	 */ $transvalue,
									/* aspect		 */ $aspectvalue,
									/* natal		 */ $natalvalue,
									/* Ratrograde	 */ $IsRatrograde
							)
					);
					$logger->debug("AstrologServices::GetCalendarProgressedAspects - progression = " .
							sprintf("%04d-%02d-%02d %02d:%02d %04d%03d%04d %s",
									/* date			 */ intval(substr($line, 6, 4)), intval(substr($line, 3, 2)), intval(substr($line, 0, 2)),
									/* time			 */ intval(substr($line, 11, 2)), intval(substr($line, 14, 2)),
									/* transiting	 */ $transvalue,
									/* aspect		 */ $aspectvalue,
									/* natal		 */ $natalvalue,
									/* Ratrograde	 */ $IsRatrograde
							)
					);
				}
			}
		}
	}
	
	/**
	 * Total Char in one line (70) with space
	 *
	 * for Sign crossing or ingrass
	 * 12345678901234567890123456789012345678901234567890123456789012345678901234567890
	 * 08/18/2010  3:45am progr    Moon (Can) Con natal (Can) Midheaven
	 * 04/29/2013  5:40pm progr    Moon (Leo) Con natal (Leo) 11th Cusp
	 * 05/10/2015  5:20pm progr    Moon (Vir) Con natal (Vir) 12th Cusp
	 */
	function GetProgressedHouseChaging($lines) {
		global $logger;		
		
		$logger->debug("AstrologServices::getCalendarAspects");
		
		$progressedplanets = array(
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
				"1011" => "SNode",      /* put here to stop the nag messages, not used */
				"1012" => "Ascenda",	/* Ascendant "AS"  */
				"1013" => "Midheav",  	/* Midheaven "MC" */
				"1014" => "IC",
				"1015" => "Descendant"
		);
		$Houses = array(
				"0001" => "Ascendant",
				"0002" => "2nd Cusp",
				"0003" => "3rd Cusp",
				"0004" => "IC",
				"0005" => "5th Cusp",
				"0006" => "6th Cusp",
				"0007" => "Descendant",
				"0008" => "8th Cusp",
				"0009" => "Midheaven",
				"0010" => "10th Cusp",
				"0011" => "11th Cusp",
				"0012" => "12nd Cusp");

		$IsThere =  0;
		$IsHouse =  0;
		/* iterate through the results */
		foreach ($lines as $line) {
			$IsThere = 0;
			
			/* manage last (blank) line */
			if (strlen($line) < 1) {
				continue;
			}
	
			/* look for the transiting planet */
			$trans = trim(substr($line, 25, 8));
			$IsRatrograde = trim(substr($line, 33, 1));
				
			//echo "<pre>" . $IsRatrograde . "  =  " . $line . "<pre>";
				
			if($IsRatrograde == "(") {
				$IsRatrograde = 'D';
			}
			else if($IsRatrograde == "[") {
				$IsRatrograde = 'R';
			}
			else if($IsRatrograde == "<") {
				$IsRatrograde = 'D';
			}
			else {
				$IsRatrograde = 'D';
			}
				
			$logger->debug("AstrologServices::GetCalendarProgressedAspects - progressed planet = $trans");
			for ($i = 0; $i < count($progressedplanets); $i++) {
				if ($progressedplanets[(1000 + $i)] == $trans) {
					$transvalue = intval((1000 + $i));
					$IsThere++;
					break;
				}
			}
			
			if($IsThere > 0) {
				/* look for the natal object take special care where returns are encountered */
				$signArrow = trim(substr($line, 39, 3));
				$house = '';
								
				if ($signArrow === 'Con') {
					$house = trim(substr($line, 55, 12));
					$signStringArray = explode(' ', $house);
		
					if(count($signStringArray) > 0){
						$house = trim($signStringArray[0]);
					}
					$IsHouse = 0;
					for ($i = 0; $i < count($Houses); $i++) {
						if ($Houses[sprintf("%04d", ($i + 1))] == $house) {
							$natalvalue = sprintf("%04d", intval($i + 1));
							$logger->debug("AstrologServices::GetCalendarProgressedAspects - progressed sign = $natalvalue, progressed planet = $transvalue");
							$IsHouse++;
							break; /* from for loop */
						}
					}
					
					if($IsHouse > 0) {
						// 12345678901234567890123456789012345678901234567890123456789012345678901234567890
						// 08/18/2010  3:45am progr    Moon (Can) Con natal (Can) Midheaven
						$Date = sprintf("%04d-%02d-%02d", intval(substr($line, 6, 4)), intval(substr($line, 3, 2)), intval(substr($line, 1, 2)));
						$Time = sprintf("%02d:%02d", intval(substr($line, 12, 2)), intval(substr($line, 14, 2)));
						
						$logger->debug("AstrologServices::GetCalendarProgressedAspects - push sign crossing");
						array_push($this->m_crossing,   /* Jupiter/Saturn house crossing */ sprintf("%s %s %04d-->%04d %s",
														/* date			 */ $Date,
														/* time			 */ $Time,
														/* transiting	 */ $transvalue,
														/* Sign change arrow		 */
														/* natal		 */ $natalvalue,
														/* Last dot for sign changing		 */
														$IsRatrograde) );
						$logger->debug("AstrologServices::GetCalendarProgressedAspects - sign changing - crossing = " .
								sprintf("%s %s %04d-->%04d %s",
										/* date			 */ $Date,
										/* time			 */ $Time,
										/* transiting	 */ $transvalue,
										/* Sign change arrow		 */
										/* natal		 */ $natalvalue,
										/* Last dot for sign changing		 */
										$IsRatrograde
								)
						);
					}
				}
			}
		}
	}

   /**
	* Total Char in one line (70) with space
	*
	* for Sign crossing or ingrass
	* 12345678901234567890123456789012345678901234567890123456789012345678901234567890
	* (Thu) 10/23/2008  5:32am progr   Venus (Lib) --> Scorpio
	* (Wed)  1/ 7/2009  2:56pm progr Mercury (Lib) Sex (Leo) Pluto
	* (Sat)  8/22/2009  2:55am progr     Sun (Vir) Tri [Cap] Jupiter
	* (Sat) 11/10/2012 12:39pm progr Jupiter  S/D	[Moves Direct]	
	* (Wed)  4/ 1/2015  6:05am progr Mercury  S/R   [Moves Retrograde]
	*/
	function GetProgressedSignChaging($lines){
		global $logger;
		$logger->debug("AstrologServices::getCalendarAspects");
		$progressedplanets = array(
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
				"1011" => "SNode",      /* put here to stop the nag messages, not used */
				"1012" => "Ascenda",	/* Ascendant "AS"  */
				"1013" => "Midheav",  	/* Midheaven "MC" */
				"1014" => "IC",
				"1015" => "Descendant"
		);
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
				"1011" => "SNode",      /* put here to stop the nag messages, not used */
				"1012" => "Ascenda",	/* Ascendant "AS"  */
				"1013" => "Midheaven",  	/* Midheaven "MC" */
				"1014" => "IC",
				"1015" => "Descendant"
		);
		$signs = array(
				"0101" => "Aries",
				"0102" => "Taurus",
				"0103" => "Gemini",
				"0104" => "Cancer",
				"0105" => "Leo",
				"0106" => "Virgo",
				"0107" => "Libra",
				"0108" => "Scorpio",
				"0109" => "Sagittarius",
				"0110" => "Capricorn",
				"0111" => "Aquarius",
				"0112" => "Pisces"
		);
		 
		/* iterate through the results */
		foreach ($lines as $line) {
			 
			/* manage last (blank) line */
			if (strlen($line) < 1) {
				continue;
			}
			 
			/* look for the transiting planet */
			$trans = trim(substr($line, 30, 8));
			$IsRatrograde = trim(substr($line, 39, 1));
			
			//echo "<pre>" . $IsRatrograde . "  =  " . $line . "<pre>";
			
			if($IsRatrograde == "(") {
				$IsRatrograde = 'D';				
			}
			else if($IsRatrograde == "[") {
				$IsRatrograde = 'R';
			}
			else if($IsRatrograde == "<") {
				$IsRatrograde = 'D';
			}
			else {
				$IsRatrograde = 'D';
			}
			
			$logger->debug("AstrologServices::GetCalendarProgressedAspects - progressed planet = $trans");
			for ($i = 0; $i < count($progressedplanets); $i++) {
				if ($progressedplanets[(1000 + $i)] == $trans) {
					$transvalue = intval((1000 + $i));
					break;
				}
			}
			 
			/*
			 * look for the natal object
			* take special care where returns are encountered
			*/
			$signArrow = trim(substr($line, 45, 3));
			$sign = '';
			if ($signArrow === '-->') {
				$sign = trim(substr($line, 49, 12));
				$signStringArray = explode(' ', $sign);
				
				if(count($signStringArray) > 0){
					$sign = trim($signStringArray[0]);
				}
				
				for ($i = 0; $i < count($signs); $i++) {
					if ($signs[sprintf("%04d", (100 + $i + 1))] == $sign) {
						$natalvalue = 100 + intval($i + 1);
						$logger->debug("AstrologServices::GetCalendarProgressedAspects - progressed sign = $natalvalue, progressed planet = $transvalue");
						break; /* from for loop */
					}
				}
			
				$logger->debug("AstrologServices::GetCalendarProgressedAspects - push sign crossing");
				array_push(
						$this->m_crossing, /* Jupiter/Saturn house crossing */ sprintf("%04d-%02d-%02d %02d:%02d %04d-->%04d %s",
								/* date			 */ intval(substr($line, 12, 4)), intval(substr($line, 9, 2)), intval(substr($line, 6, 2)),
								/* time			 */ intval(substr($line, 11, 2)), intval(substr($line, 14, 2)),
								/* transiting	 */ $transvalue,
								/* Sign change arrow		 */
								/* natal		 */ $natalvalue,
								/* Last dot for sign changing		 */
								$IsRatrograde
						)
				);
				$logger->debug("AstrologServices::GetCalendarProgressedAspects - sign changing - crossing = " .
						sprintf("%04d-%02d-%02d %02d:%02d %04d-->%04d %s",
								/* date			 */ intval(substr($line, 12, 4)), intval(substr($line, 9, 2)), intval(substr($line, 6, 2)),
								/* time			 */ intval(substr($line, 11, 2)), intval(substr($line, 14, 2)),
								/* transiting	 */ $transvalue,
								/* Sign change arrow		 */
								/* natal		 */ $natalvalue,
								/* Last dot for sign changing		 */
								$IsRatrograde
						)
				);
			}
			else if($signArrow == 'S/D' || $signArrow == 'S/R') {
				$logger->debug("AstrologServices::GetCalendarProgressedAspects - push Any Planet Goes Direct or Retrograde");
				array_push(
					$this->m_crossing,  sprintf("%04d-%02d-%02d %02d:%02d %04d-->%s",
								/* date			 */ intval(substr($line, 12, 4)), intval(substr($line, 9, 2)), intval(substr($line, 6, 2)),
								/* time			 */ intval(substr($line, 11, 2)), intval(substr($line, 14, 2)),
								/* transiting	 */ $transvalue,
								/* 'S/R' - 'S/D' */ $signArrow
						)
				);
				$logger->debug("AstrologServices::GetCalendarProgressedAspects - push Any Planet Goes Direct or Retrograde = " .
						sprintf("%04d-%02d-%02d %02d:%02d %04d-->%s",
								/* date			 */ intval(substr($line, 12, 4)), intval(substr($line, 9, 2)), intval(substr($line, 6, 2)),
								/* time			 */ intval(substr($line, 11, 2)), intval(substr($line, 14, 2)),
								/* transiting	 */ $transvalue,
								/* 'S/R' - 'S/D' */ $signArrow
						)
				);
			}
		}
	}
	 

	/*
	 * Total Char in one line (79) with space
	*
	* 012345678901234567890123456789012345678901234567890123456789012345678901234567890
	*   1: progr   Pluto (Lib) Squ natal (Can) Sun       - sep 0:54' - power:266.25
	*   2: progr  Chiron (Tau) Con natal (Tau) Moon      - sep 2:58' - power:117.97
	*   3: progr   Pluto (Lib) Con natal (Lib) Pluto     - sep 0:26' - power:117.26 R
	*   4: progr Neptune [Sag] Con natal [Sag] Neptune   - sep 0:30' - power:104.46 R
	*   5: progr  Uranus (Sag) Con natal [Sag] Uranus    - app 0:13' - power: 96.90 R
	*
	*/
	function GenerateProgressedToNatalAspectList($lines){
		global $logger;
		$logger->debug("AstrologServices::GenerateProgressedToNatalAspectList()");
		 
		$this->aspectProgressedToNatal = array();
		 
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
				"1012" => "Ascendant",
				"1013" => "Midheaven", /* "MC", */
				"1014" => "IC",
				"1015" => "Descendant",
				"1016" => "Chiron");
		 
		$aspects = array("000" => "Con", "060" => "Sex", "090" => "Squ", "120" => "Tri", "180" => "Opp");
		 
		for ($line = 0; $line < count($lines); $line++) {
			if (substr($lines[$line], 3, 1) != ':') {
				continue;
			}
			$subject = trim(substr($lines[$line], 11, 8));		//Progressed Planet Name
			$aspect = trim(substr($lines[$line], 25, 4));		//Aspect
			$object = trim(substr($lines[$line], 41, 11));		//Natal Object
			$aspect_orb = trim(substr($lines[$line], 57, 4));	//Aspect Orbs

			//Fetch Aspecting planet (Progressed planets)
			$subject = trim(substr($lines[$line], 11, 8));
			$subjectvalue = 0;
			for ($i = 0; $i < count($planets); $i++) {
				if ($i >= 11) {
					$i++;
				}
				if ($planets[(1000 + $i)] == $subject) {
					$subjectvalue = intval((1000 + $i));
					break;
				}
			}

			//Fetch Aspect values
			$aspect = trim(substr($lines[$line], 25, 4));
			$aspect_orb = trim(substr($lines[$line], 57, 4));
			$aspectvalue = -1;
			reset($aspects);
			while ($aspectname = current($aspects)) {
				if ($aspectname == $aspect) {
					$aspectvalue = intval(key($aspects));
					break;
				}
				next($aspects);
			}
			//Fetching Natal planet name
			$object = trim(substr($lines[$line], 41, 10));
			$objectvalue = 0;

			for ($i = 0; $i < count($planets); $i++) {
				if ($i >= 11) {
					$i++;
				}
				 
				/* look for a string match */
				if (strtolower( $planets[(1000 + $i)] ) == strtolower( trim($object) )) {
					$logger->debug("AstrologServices::getAspects - found subject[$subjectvalue], object[$objectvalue]");

					if ($object == 'Ascendant') {
						$logger->debug("AstrologServices::getAspects - reversing objects for Ascendant context");
						$objectvalue = $subjectvalue;
						$subjectvalue = intval((1000 + $i));
					} else {
						$objectvalue = intval((1000 + $i));
					}
					$logger->debug("AstrologServices::getAspects - result subject[$subjectvalue], object[$objectvalue]");
					break;
				} else {
					// something has appeared that we are not prepared for ... error */
				}
			}

			//store the aspect
			if ($subjectvalue != 0 and $aspectvalue != -1 and $objectvalue != 0) {
				/* tack the orb to the end for the wheel's aspect grid */
				array_push($this->aspectProgressedToNatal, sprintf("%04d%03d%04d %s", $subjectvalue, $aspectvalue, $objectvalue, $aspect_orb));

			} else {
				$logger->debug("failed to parse aspect line, line=" . $lines[$line]);
				$logger->error("failed to parse aspect line");
			}
		}
	}
	
	/**
	 * calcHouseCrossingAspects() function calulates House enters and leaves date for Saturn and Jupitar planet
	 * @param $data	
	 * @param $start_year
	 * @param $duration
	 */
	function calcHouseCrossingAspects($data, $start_year, $duration) {
		global $logger;
		$logger->debug("AstrologServices::calcCrossingAspects()");

		/*
		 * determine the aspects
		 */
		$this->m_pipe = new AstrologCalculator();

		/* format the birth data */
		$this->m_pipe->addArgument($data->qaSwitchFormat());

		/* Time Zone Set up*/
		//http://www.bonniehill.net/pages.aux/astrology/astrolog/Config.DAT
		$this->m_pipe->addArgument('-z0 ' . $data->m_summertime_offset); 		//Default Daylight time setting   [0 standard, 1 daylight]
		$this->m_pipe->addArgument('-z ' . $data->m_timezone_offset);  			//Default time zone               [hours before GMT      ]
			
		/* request aspect generation */
		$this->m_pipe->addArgument(sprintf("-tY %d %d", $start_year, $duration));
		
		///FULL COMMAND '-tY 2010 5 -RT0 6 7 -R0 -RC -C';
		$this->m_pipe->addArgument("-RT0 6 7");  // FOR SATURN AND JUPITAR
		
		$this->m_pipe->addArgument("-R0");  	// RESTRICT ALL OTHERE PLANETS
		
		$this->m_pipe->addArgument("-RC");  	// RESTRICT ALL OTHERE MINOR PLANETs
		
		$this->m_pipe->addArgument("-C");   	// INCLUDE ONLY HOUSE CUSPS

		/* finally call Astrolog */
		$this->m_pipe->callAstrolog();
		$logger->debug("AstrologServices::calcCrossingAspects: cache=" . $this->m_pipe->getCache());
		$lines = explode("\n", $this->m_pipe->getCache());
			
		$this->SAandJUCrossing = array();
		$this->getCalendarHouseAspects($lines, true, true);
		$this->m_pipe->teardown();
	}
	
	/*
     # 2/ 5/2010  3:02am trans Jupiter (Pis) Tri natal (Can) Midheaven
     # 2/23/2010  4:20am trans  Saturn [Lib] Con natal (Lib) Ascendant	
     # 2/23/2010  4:20am trans  Saturn [Lib] Opp natal (Ari) Descendant
     # 3/ 2/2010  6:22am trans Jupiter (Pis) Opp natal (Vir) 12th Cusp
     # 3/ 2/2010  6:22am trans Jupiter (Pis) Con natal (Pis) 6th Cusp
     # 5/18/2010  7:25pm trans Jupiter (Pis) Tri natal (Sco) 3rd Cusp
     #10/18/2010  7:25pm trans Jupiter (Pis) Sex natal (Tau) 9th Cusp
	 #----------111111111122222222223333333333444444444455555555556666666666777777777
	 #0123456789012345678901234567890123456789012345678901234567890123456789012345678
	 *
	 * Where there is "[Square]" bracket That means that planet goes RETROGRADE (Moves back)
	 */
	function getCalendarHouseAspects($lines) {
		global $logger;
		$logger->debug("AstrologServices::getCalendarAspects");
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
				"1011" => "SNode",      /* put here to stop the nag messages, not used */
				"1012" => "Ascendant",
				"1013" => "Midheaven",  /* "MC", */
				"1014" => "IC",
				"1015" => "Descendant"
		);
		$houses = array (
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
			$logger->debug("AstrologServices::getCalendarAspects - transiting planet = $trans");
			for ($i = 0; $i < count($planets); $i++) {
				if ($i >= 11) {
					$i++;
				}
				if ($planets[(1000 + $i)] == $trans) {
					$transvalue = intval((1000 + $i));
					break;
				}
			}

			/*
			 * look for the natal object
			* take special care where returns are encountered
			*/
			$natal = trim(substr($line, 55, 10));
			for ($i = 0; $i < count($houses); $i++) {
				/* if( $i >= 11 ) { $i++; } */
				if ($houses[sprintf("%04d", ($i + 1))] == $natal) {
					$natalvalue = intval($i + 1);
					$logger->debug("AstrologServices::getCalendarAspects - transited cusp = $natalvalue, transiting planet = $transvalue");
					break; /* from for loop */
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
			
			$IsRetrograde = (trim(substr($line, 33, 1)) == '[' ? 'R' : 'D');			
			
			//FOR House crossing we are just trageting Conjuction [CON] [000]			
			if($aspectvalue == '000') {
				$logger->debug("AstrologServices::getCalendarAspects - push crossing aspect");
				
				array_push(
						$this->SAandJUCrossing, /* Jupiter/Saturn house crossing */ sprintf("%04d-%02d-%02d %02d:%02d %04d%03d%04d %s",
								/* date			 */ intval(substr($line, 6, 4)), intval(substr($line, 3, 2)), intval(substr($line, 0, 2)),
								/* time			 */ intval(substr($line, 11, 2)), intval(substr($line, 14, 2)),
								/* transiting	 */ $transvalue,
								/* aspect		 */ $aspectvalue,
								/* natal		 */ $natalvalue,
								/* Retrograde	 */ $IsRetrograde
						)
				);
				$logger->debug("AstrologServices::getCalendarAspects - crossing = " .
						sprintf("%04d-%02d-%02d %02d:%02d %04d%03d%04d %s",
								/* date			 */ intval(substr($line, 6, 4)), intval(substr($line, 3, 2)), intval(substr($line, 0, 2)),
								/* time			 */ intval(substr($line, 11, 2)), intval(substr($line, 14, 2)),
								/* transiting	 */ $transvalue,
								/* aspect		 */ $aspectvalue,
								/* natal		 */ $natalvalue,
								/* Retrograde	 */ $IsRetrograde
						)
				);
			}
		}
	}
}
?>