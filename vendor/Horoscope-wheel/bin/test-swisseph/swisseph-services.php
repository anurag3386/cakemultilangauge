<?php

// How to create Cron in php
//
// http://www.web-site-scripts.com/knowledge-base/article/AA-00487/0/Setup-Cron-job-on-Windows-7-Vista-2008.html
//
// http://network.acquia.com/documentation/getting-started/appendix/localhost-config


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
class SwissEphemerisServices {
    //put your code here

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
    var $m_crossings; /* house cusp crossings	 */
    /**
     * @var $m_ephemeris
     */
    var $m_ephemeris;     /* cached ephemeris data */
    /**
     * @var $m_transit_window;
     */
    var $m_transit_window;

    /**
     * Constructor
     *
     * @param mixed birthdata
     * @param bool calculate chart data
     * @return SwissEphemerisAPI
     */
    function SwissEphemerisServices($data, $calcChartData = true) {
        global $reportLogger;
        $reportLogger->debug("SwissEphemerisAPI::SwissEphemerisAPI \n");
        $this->m_timeddata = $data->IsTimedData;
               
        if ($calcChartData === true) {
            if ($this->m_timeddata === true) {
                $this->CalculateChart($data);
            } else {
                $this->CalculateSolarChart($data);
            }
            $this->CalculateAspects($data);
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
    function CalculateChart($data) {
        global $reportLogger;
        $reportLogger->debug("SwissEphemerisAPI::CalculateChart() \n");

        /*
         * determine the planetary and house cusp longitudes along with related
         * context information
         */
        $this->m_pipe = new SwissEphemerisCalculator();
        //echo $data->GenerateParametersList();
        /* format the birth data */
        $this->m_pipe->AddArgument($data->GenerateParametersList());

        /* display locations in degrees */
        //$this->m_pipe->AddArgument('-sd');

        /* finally call Astrolog */
        $this->m_pipe->RunSwissEphemeris();

        $reportLogger->debug("SwissEphemerisAPI::calcChart: cache=" . $this->m_pipe->getCache() . ' \n');

        $lines = explode("\n", $this->m_pipe->getCache());

        for ($line = 0; $line < count($lines); $line++) {            
            $this->getPlanetaryLongitude($lines[$line]);
        }

        foreach ($lines as $line) {
            $this->GetHouseCuspLongitude($line);
        }
        $this->m_pipe->Destroy();
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
    function CalculateSolarChart($data) {

        global $reportLogger;
        $reportLogger->debug("SwissEphemerisAPI::CalculateSolarChart() \n");

        /*
         * determine the planetary and house cusp longitudes along with related
         * context information
         */
        $this->m_pipe = new SwissEphemerisCalculator();

        /*
         * format the birth data
         * Note: the chart should be calculated for noon as that will reduce the error
         * for cuspal charts.
         */
        /* TODO: force noon day chart data here */
        $this->m_pipe->AddArgument($data->GenerateParametersList());

        /* specify whole house system */
        $this->m_pipe->AddArgument('-c 12');

        /* display locations in degrees */
        $this->m_pipe->AddArgument('-sd');

        /* finally call Astrolog */
        $this->m_pipe->RunSwissEphemeris();
        $reportLogger->debug("SwissEphemerisAPI::calcSolarChart: cache=" . $this->m_pipe->getCache() . ' \n');
        $lines = explode("\n", $this->m_pipe->getCache());
        for ($line = 0; $line < count($lines); $line++) {
            $this->getPlanetaryLongitude($lines[$line]);
        }
        foreach ($lines as $line) {
            $this->GetHouseCuspLongitude($line);
        }
        /* adjust cusp longitude degrees to revised boundaries. Minutes will be 00 */
        $this->m_pipe->Destroy();
    }

    /**
     * calcAspects
     */
    function CalculateAspects($data) {
        global $reportLogger;
        $reportLogger->debug("SwissEphemerisAPI::CalculateAspects()" . ' \n');

        /*
         * determine the aspects
         */
        $this->m_pipe = new SwissEphemerisCalculator();

        /* format the birth data */
        $this->m_pipe->AddArgument($data->GenerateParametersList());

        /* include the angles as objects */
        $this->m_pipe->AddArgument('=C');

        /* ptolemaic aspects only used */
        $this->m_pipe->AddArgument('-A 5');

        /* orbs used */
        $this->m_pipe->AddArgument('-YAo 1 5	 9.0  9.0 6.0  6.0 4.0');  /* standard	 */
        #	$this->m_pipe->AddArgument('-YAo 1 5	 5.0  4.0 3.0  2.0 2.0');		/* strong	*/
        #	$this->m_pipe->AddArgument('-YAo 1 5	 9.0  8.0 6.0  6.0 4.0');		/* medium	*/
        #	$this->m_pipe->AddArgument('-YAo 1 5	14.0 12.0 9.0 11.0 7.0');		/* weak		*/

        /* max permissable orbs */
        $this->m_pipe->AddArgument('-YAm 1 10	360 360 360 360 360 360 360 360 360 360');
        $this->m_pipe->AddArgument('-YAm 11 20	360 360 360 360 360   3   3   3   3   3');
        $this->m_pipe->AddArgument('-YAm 21 32	360 360 360 360 360 360 360 360 360 360 360 360');
        $this->m_pipe->AddArgument('-YAm 33 41	360 360 360 360 360 360 360 360 360');

        /* planet aspect orb additions */
        $this->m_pipe->AddArgument('-YAd 1 10	0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0');
        $this->m_pipe->AddArgument('-YAd 11 20	0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0');
        $this->m_pipe->AddArgument('-YAd 21 32	0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0');

        /* just in case the aspect angles are not defined ... */
        // $this->m_pipe->AddArgument('-YAa 1 5	0.0 180.0 90.0 120.0 60.0');

        /* manage aspected object settings */
        $this->m_pipe->AddArgument('-YR  1 10 0 0 0 0 0 0 0 0 0 0');  /* main planets enabled */
        $this->m_pipe->AddArgument('-YR 11 20 1 1 1 1 1 0 1 1 1 1');  /* node enabled */
        $this->m_pipe->AddArgument('-YR 21 32 0 1 1 1 1 1 1 1 1 1 1 1'); /* ascendant enabled */
        $this->m_pipe->AddArgument('-YR 33 41 1 1 1 1 1 1 1 1 1');   /* all else disabled */

        /* request aspect generation */
        $this->m_pipe->AddArgument('-a');

        /* finally call Astrolog */
        $this->m_pipe->RunSwissEphemeris();
        $reportLogger->debug("SwissEphemerisAPI::calcAspects: cache=" . $this->m_pipe->getCache() . ' \n');
        $lines = explode("\n", $this->m_pipe->getCache());
        $this->getAspects($lines);
        $this->m_pipe->Destroy();
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
        global $reportLogger;
        $reportLogger->debug("SwissEphemerisAPI::calcDynamicAspects() . ' \n'");

        /**
         * create the Astrolog low level API instance
         */
        $this->m_pipe = new SwissEphemerisCalculator();

        /* format the birth data */
        $this->m_pipe->AddArgument($data->GenerateParametersList());

        /* include the angles as objects */
        $this->m_pipe->AddArgument('=C');

        /* ptolemaic aspects only used */
        $this->m_pipe->AddArgument('-A 5');

        /* orbs used */
        $this->m_pipe->AddArgument('-YAo 1 5 8.0 8.0 6.0 5.0 5.0');

        /* max permissable orbs */
        $this->m_pipe->AddArgument('-YAm  1 10 360 360 360 360 360 360 360 360 360 360');
        $this->m_pipe->AddArgument('-YAm 11 20 360 360 360 360 360   3   3   3   3   3');
        $this->m_pipe->AddArgument('-YAm 21 32 360 360 360 360 360 360 360 360 360 360 360 360');
        $this->m_pipe->AddArgument('-YAm 33 41 360 360 360 360 360 360 360 360 360');

        /* planet aspect orb additions */
        $this->m_pipe->AddArgument('-YAd  1 10 1.0 1.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0');
        $this->m_pipe->AddArgument('-YAd 11 20 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0');
        $this->m_pipe->AddArgument('-YAd 21 32 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0');

        /* just in case the aspect angles are not defined ... */
        // $this->m_pipe->AddArgument('-YAa 1 5	0.0 180.0 90.0 120.0 60.0');

        /* manage aspected object settings */
        $this->m_pipe->AddArgument('-YR  1 10 0 0 0 0 0 0 0 0 0 0');  /* main planets enabled */
        $this->m_pipe->AddArgument('-YR 11 20 1 1 1 1 1 0 1 1 1 1');  /* node enabled */
        $this->m_pipe->AddArgument('-YR 21 32 0 1 1 1 1 1 1 1 1 0 1 1'); /* ascendant/MC enabled */
        $this->m_pipe->AddArgument('-YR 33 41 1 1 1 1 1 1 1 1 1');   /* all else disabled */

        /* manage transiting object settings */
        $this->m_pipe->AddArgument('-YRT 1 10  1 1 1 1 1 0 0 0 0 0');
        $this->m_pipe->AddArgument('-YRT 11 20 1 1 1 1 1 1 1 1 1 1');
        $this->m_pipe->AddArgument('-YRT 21 32 1 1 1 1 1 1 1 1 1 1 1 1');
        $this->m_pipe->AddArgument('-YRT 33 41 1 1 1 1 1 1 1 1 1');

        /* request aspect generation */
        $this->m_pipe->AddArgument(sprintf("-tY %d %d", $start_year, $duration));

        /* finally call Astrolog */
        $this->m_pipe->RunSwissEphemeris();
        $reportLogger->debug("SwissEphemerisAPI::calcDynamicAspects: cache=" . $this->m_pipe->getCache(). ' \n');
        $lines = explode("\n", $this->m_pipe->getCache());
        $this->m_transit = array();
        $this->getCalendarAspects($lines, true, false);
        $this->m_pipe->Destroy();
    }

    /**
     * calcTransitEntryPoint
     * Subtract the orb value from the planetary longitude and search for transits from
     * one planet to that point
     */
    function calcTransitEntryPoint() {
        global $reportLogger;
        $reportLogger->debug("SwissEphemerisAPI::calcDynamicAspects()". ' \n');

        /*
         * determine the aspects
         */
        $this->m_pipe = new SwissEphemerisCalculator();

        /* format the birth data */
        $this->m_pipe->AddArgument($data->GenerateParametersList());

        /* include the angles as objects */
        $this->m_pipe->AddArgument('=C');

        /* ptolemaic aspects only used */
        $this->m_pipe->AddArgument('-A 5');

        /*
         * orbs used
         * here we section out the orbs we don't want by setting them to -1
         */
        $this->m_pipe->AddArgument('-YAo 1 5 -1 -1 -1 -1 -1');
        $this->m_pipe->AddArgument('-Ao 1 5 -1 -1 -1 -1 -1');

        /* max permissable orbs */
        $this->m_pipe->AddArgument('-YAm  1 10 360 360 360 360 360 360 360 360 360 360');
        $this->m_pipe->AddArgument('-YAm 11 20 360 360 360 360 360   3   3   3   3   3');
        $this->m_pipe->AddArgument('-YAm 21 32 360 360 360 360 360 360 360 360 360 360 360 360');
        $this->m_pipe->AddArgument('-YAm 33 41 360 360 360 360 360 360 360 360 360');

        /* planet aspect orb additions */
        $this->m_pipe->AddArgument('-YAd  1 10 1.0 1.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0');
        $this->m_pipe->AddArgument('-YAd 11 20 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0');
        $this->m_pipe->AddArgument('-YAd 21 32 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0');
    }

    /**
     * calcTransitExitPoint
     * Subtract the orb value from the planetary longitude and search for transits from
     * one planet to that point
     */
    function calcTransitExitPoint() {

    }

    Function calcCalendarAspects($data, $start_year, $duration) {
        global $reportLogger;
        $reportLogger->debug("SwissEphemerisAPI::calcCalendarAspects()". ' \n');

        /*
         * determine the aspects
         */
        $this->m_pipe = new SwissEphemerisCalculator();

        /* format the birth data */
        $this->m_pipe->AddArgument($data->GenerateParametersList());

        /* include the angles as objects */
        $this->m_pipe->AddArgument('=C');

        /* ptolemaic aspects only used */
        $this->m_pipe->AddArgument('-A 5');

        /* orbs used */
        $this->m_pipe->AddArgument('-YAo 1 5 8.0 8.0 6.0 5.0 5.0');

        /* max permissable orbs */
        $this->m_pipe->AddArgument('-YAm  1 10 360 360 360 360 360 360 360 360 360 360');
        $this->m_pipe->AddArgument('-YAm 11 20 360 360 360 360 360   3   3   3   3   3');
        $this->m_pipe->AddArgument('-YAm 21 32 360 360 360 360 360 360 360 360 360 360 360 360');
        $this->m_pipe->AddArgument('-YAm 33 41 360 360 360 360 360 360 360 360 360');

        /* planet aspect orb additions */
        $this->m_pipe->AddArgument('-YAd  1 10 1.0 1.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0');
        $this->m_pipe->AddArgument('-YAd 11 20 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0');
        $this->m_pipe->AddArgument('-YAd 21 32 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0');

        /* just in case the aspect angles are not defined ... */
        // $this->m_pipe->AddArgument('-YAa 1 5	0.0 180.0 90.0 120.0 60.0');

        /* manage aspected object settings */
        $this->m_pipe->AddArgument('-YR  1 10 0 0 0 0 0 0 0 0 0 0');  /* main planets enabled */
        $this->m_pipe->AddArgument('-YR 11 20 1 1 1 1 1 0 1 1 1 1');  /* node enabled */
        $this->m_pipe->AddArgument('-YR 21 32 0 1 1 1 1 1 1 1 1 0 1 1'); /* ascendant/MC enabled */
        $this->m_pipe->AddArgument('-YR 33 41 1 1 1 1 1 1 1 1 1');   /* all else disabled */

        /* manage transiting object settings */
        $this->m_pipe->AddArgument('-YRT 1 10  0 1 0 0 0 0 0 0 0 0');
        $this->m_pipe->AddArgument('-YRT 11 20 1 1 1 1 1 1 1 1 1 1');
        $this->m_pipe->AddArgument('-YRT 21 32 1 1 1 1 1 1 1 1 1 1 1 1');
        $this->m_pipe->AddArgument('-YRT 33 41 1 1 1 1 1 1 1 1 1');

        /* request aspect generation */
        $this->m_pipe->AddArgument(sprintf("-tY %d %d", $start_year, $duration));

        /* finally call Astrolog */
        $this->m_pipe->RunSwissEphemeris();
        $reportLogger->debug("SwissEphemerisAPI::calcCalendarAspects: cache=" . $this->m_pipe->getCache(). ' \n');
        $lines = explode("\n", $this->m_pipe->getCache());
        $this->m_aspect = array();
        $this->getCalendarAspects($lines, false, false);
        $this->m_pipe->Destroy();
    }

    /**
     * calcCrossingAspects
     */
    function calcCrossingAspects($data, $start_year, $duration) {
        global $reportLogger;
        $reportLogger->debug("SwissEphemerisAPI::calcCrossingAspects()". ' \n');

        /*
         * determine the aspects
         */
        $this->m_pipe = new SwissEphemerisCalculator();

        /* format the birth data */
        $this->m_pipe->AddArgument($data->GenerateParametersList());

        /* include the angles as objects */
        $this->m_pipe->AddArgument('=C');

        /* ptolemaic aspects only used */
        $this->m_pipe->AddArgument('-A 1');

        /* orbs used */
        $this->m_pipe->AddArgument('-YAo 1 5 8.0 8.0 6.0 5.0 5.0');

        /* max permissable orbs */
        $this->m_pipe->AddArgument('-YAm  1 10 360 360 360 360 360 360 360 360 360 360');
        $this->m_pipe->AddArgument('-YAm 11 20 360 360 360 360 360   3   3   3   3   3');
        $this->m_pipe->AddArgument('-YAm 21 32 360 360 360 360 360 360 360 360 360 360 360 360');
        $this->m_pipe->AddArgument('-YAm 33 41 360 360 360 360 360 360 360 360 360');

        /* planet aspect orb additions */
        $this->m_pipe->AddArgument('-YAd  1 10 1.0 1.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0');
        $this->m_pipe->AddArgument('-YAd 11 20 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0');
        $this->m_pipe->AddArgument('-YAd 21 32 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0');

        /* just in case the aspect angles are not defined ... */
        // $this->m_pipe->AddArgument('-YAa 1 5	0.0 180.0 90.0 120.0 60.0');

        /* manage aspected object settings */
        $this->m_pipe->AddArgument('-YR  1 10 1 1 1 1 1 1 1 1 1 1');  /* main planets enabled */
        $this->m_pipe->AddArgument('-YR 11 20 1 1 1 1 1 1 1 1 1 1');  /* node enabled */
        $this->m_pipe->AddArgument('-YR 21 32 0 0 0 0 0 0 0 0 0 0 0 0'); /* ascendant/MC enabled */
        $this->m_pipe->AddArgument('-YR 33 41 1 1 1 1 1 1 1 1 1');   /* all else disabled */

        /* manage transiting object settings */
        $this->m_pipe->AddArgument('-YRT 1 10  1 1 1 1 1 0 0 1 1 1');
        $this->m_pipe->AddArgument('-YRT 11 20 1 1 1 1 1 1 1 1 1 1');
        $this->m_pipe->AddArgument('-YRT 21 32 1 1 1 1 1 1 1 1 1 1 1 1');
        $this->m_pipe->AddArgument('-YRT 33 41 1 1 1 1 1 1 1 1 1');

        /* request aspect generation */
        $this->m_pipe->AddArgument(sprintf("-tY %d %d", $start_year, $duration));

        /* finally call Astrolog */
        $this->m_pipe->RunSwissEphemeris();
        $reportLogger->debug("SwissEphemerisAPI::calcCrossingAspects: cache=" . $this->m_pipe->getCache(). ' \n');
        $lines = explode("\n", $this->m_pipe->getCache());
        $this->m_crossing = array();
        $this->getCalendarAspects($lines, true, true);
        $this->m_pipe->Destroy();
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
        global $reportLogger;
        $reportLogger->debug("SwissEphemerisAPI::calcEphemeris($start_year,$duration)". ' \n');

        /*
         * we are going to calculate the planetary positions from the start of
         * the current year to the end of the final (duration+1) year
         */
        $this->m_ephemeris = array();
        $lines = array();
        for ($i = 0; $i <= $duration; $i++) {

            $this->m_pipe = new SwissEphemerisCalculator();

            $this->m_pipe->AddArgument(sprintf("-qy %04d", $start_year + $i));
            $this->m_pipe->AddArgument("-Ey");   /* whole year     */
            $this->m_pipe->AddArgument("-R0");   /* restrict all   */
            $this->m_pipe->AddArgument("-R 6");  /* enable jupiter */
            $this->m_pipe->AddArgument("-R 7");  /* enable saturn  */
            $this->m_pipe->AddArgument("-R 8");  /* enable uranus  */
            $this->m_pipe->AddArgument("-R 9");  /* enable neptune */
            $this->m_pipe->AddArgument("-R 10"); /* enable pluto   */

            /* finally call Astrolog */
            $this->m_pipe->RunSwissEphemeris();
            $reportLogger->debug("SwissEphemerisAPI::calcEphemeris: cache=" . $this->m_pipe->getCache(). ' \n');
            $lines = array_merge($lines, explode("\n", $this->m_pipe->getCache()));
            $this->m_pipe->Destroy();
        }
        $this->getEphemeris($start_year, $lines);
    }

    function calcACG($data) {
        global $reportLogger;
        $reportLogger->debug("SwissEphemerisAPI::calcACG(). ' \n'");
        /*
         * determine the planetary and house cusp longitudes along with related
         * context information
         */
        $this->m_pipe = new SwissEphemerisCalculator();

        /* format the birth data */
        $this->m_pipe->AddArgument($data->GenerateParametersList());

        /* set step size */
        $this->m_pipe->AddArgument('-L 1');

        /* finally call Astrolog */
        $this->m_pipe->RunSwissEphemeris();
        $reportLogger->debug("SwissEphemerisAPI::calcACG: cache=" . $this->m_pipe->getCache(). ' \n');
        $lines = explode("\n", $this->m_pipe->getCache());

        $acg_mc = array();
        $acg_as = array();
        $acg_ds = array();

        foreach ($lines as $line) {
            /* look for the MC lines */
            if (substr($line, 0, 7) == 'Midheav') {
                /* scan the line */
                $reportLogger->debug("SwissEphemerisAPI::calcACG - found MC - $line". ' \n');
                $reportLogger->debug('/* add planet conjunct MS lines */'. ' \n');
                for ($i = 0; $i < 10; $i++) {
                    $longitude = intval(substr($line, (9 + ($i * 4)), 3));
                    $hemisphere = substr($line, (12 + ($i * 4)), 1);
                    if ($hemisphere == 'w') {
                        $longitude = 0 - $longitude;
                    }
                    array_push(
                            $acg_mc,
                            array(
                                "planet" => intval($i),
                                "longitude" => intval($longitude),
                                "valid" => true
                            )
                    );
                }
            }
            /* look for the Asc lines */
            if (substr($line, 0, 3) == 'Asc') {
                $reportLogger->debug("in line - $line". ' \n');
                $latitude = intval(trim(substr($line, 4, 2)));
                $reportLogger->debug("- latitude = $latitude". ' \n');
                $hemi_ns = substr($line, 6, 1);
                $reportLogger->debug("- hemisphere = $hemi_ns". ' \n');
                if ($hemi_ns == 's') {
                    $latitude = 0 - $latitude;
                }
                for ($i = 0; $i < 10; $i++) {
                    $longitude = trim(substr($line, (9 + ($i * 4)), 3));
                    $reportLogger->debug("- longitude[$i] = $longitude");
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
                            $acg_as,
                            array(
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
                            $acg_ds,
                            array(
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
        $this->m_pipe->AddArgument('-YAo 1 5 4.0 4.0 4.0 4.0 4.0');  /* base setting for ptolemaic aspects	 */

        /* max permissable orbs */
        $this->m_pipe->AddArgument('-YAm  1 10 360 360 360 360 360 360 360 360 360 360');
        $this->m_pipe->AddArgument('-YAm 11 20 360 360 360 360 360   3   3   3   3   3');
        $this->m_pipe->AddArgument('-YAm 21 32 360 360 360 360 360 360 360 360 360 360 360 360');
        $this->m_pipe->AddArgument('-YAm 33 41 360 360 360 360 360 360 360 360 360');

        /* planet aspect orb additions */
        $this->m_pipe->AddArgument('-YAd  1 10 5.0 5.0 5.0 5.0 5.0 5.0 5.0 5.0 5.0 5.0');   /* lift planets to 9 degrees	 */
        $this->m_pipe->AddArgument('-YAd 11 20 0.0 0.0 0.0 0.0 0.0 2.0 0.0 0.0 0.0 0.0');   /* lift node to 6 degrees		 */
        $this->m_pipe->AddArgument('-YAd 21 32 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0'); /* angles already at 4 degrees	 */
    }

    function useStrongOrbSetting() {
        
    }

    function useMediumOrbSetting() {
        
    }

    function useWeakOrbSetting() {
        
    }

    /*
      #----------111111111122222222223333333333444444444455555555556666666666777777777
      #0123456789012345678901234567890123456789012345678901234567890123456789012345678
      #Jupiter   : 144.508 R + 1:11' (-) [ 9th] [R-] -0.120  -  House cusp  6:  41.058
     */

    function getPlanetaryLongitude($line) {

        global $reportLogger;
        #$reportLogger->debug("SwissEphemerisAPI::getPlanetaryLongitude( $line )");

        $planet = trim(substr($line, 0, 10));
        switch ($planet) {
            case 'Sun': case 'Moon':
            case 'Mercury': case 'Venus': case 'Mars':
            case 'Jupiter': case 'Saturn':
            case 'Uranus': case 'Neptune': case 'Pluto':
                $this->m_object[$planet]['longitude'] = floatval(trim(substr($line, 12, 7)));
                $this->m_object[$planet]['retrograde'] = ((substr($line, 20, 1) == 'R') ? true : false);
                $this->m_object[$planet]['house'] = intval(trim(substr($line, 35, 2)));
                $this->m_object[$planet]['solar'] = intval((($this->m_object[$planet]['house'] + 12 - $this->m_object['Sun']['house']) % 12) + 1);
                $reportLogger->debug("SwissEphemerisAPI::getPlanetaryLongitude - " . sprintf("%10s: long=%6.2f, rx=%d, house=%d, solar house=%d  \n",
                                $planet,
                                $this->m_object[$planet]['longitude'],
                                $this->m_object[$planet]['retrograde'],
                                $this->m_object[$planet]['house'],
                                $this->m_object[$planet]['solar']
                ));
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
                $reportLogger->debug("SwissEphemerisAPI::getPlanetaryLongitude - " . sprintf("%10s: long=%6.2f, rx=%d, house=%d  \n",
                                $planet,
                                $this->m_object['N' . $planet]['longitude'],
                                $this->m_object['N' . $planet]['retrograde'],
                                $this->m_object['N' . $planet]['house']
                ));
                $reportLogger->debug("SwissEphemerisAPI::getPlanetaryLongitude - " . sprintf("%10s: long=%6.2f, rx=%d, house=%d \n",
                                $planet,
                                $this->m_object['S' . $planet]['longitude'],
                                $this->m_object['S' . $planet]['retrograde'],
                                $this->m_object['S' . $planet]['house']
                ));
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

    function GetHouseCuspLongitude($line) {

        global $reportLogger;
        #		$reportLogger->debug("SwissEphemerisAPI::GetHouseCuspLongitude( $line )");

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
                $reportLogger->debug("wackamole: cusp = $cusp \n");
                $suncusp = intval(($this->m_object['Sun']['longitude'] / 30.0));
                $reportLogger->debug("wackamole: suncusp = $suncusp \n");
                $this->m_object['cusp'][$cusp] = ((($suncusp * 30) + (($cusp - 1) * 30)) % 360);
                $reportLogger->debug("wackamole: new house cusp = " . $this->m_object['cusp'][$cusp] . ' \n');
            }
        }
    }

    /*
      #----------111111111122222222223333333333444444444455555555556666666666777777777
      #0123456789012345678901234567890123456789012345678901234567890123456789012345678
      #  1:  Saturn [Lib] Con [Lib] Neptune    - orb: +0:26' - power: 24.14
     */
    function getAspects($lines) {
        global $reportLogger;
        $reportLogger->debug("SwissEphemerisAPI::getAspects  \n");
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
                    $reportLogger->debug("SwissEphemerisAPI::getAspects - found subject[$subjectvalue], object[$objectvalue]" .' \n');
                    if ($object == 'Ascendant') {
                        $reportLogger->debug("SwissEphemerisAPI::getAspects - reversing objects for Ascendant context \n");
                        $objectvalue = $subjectvalue;
                        $subjectvalue = intval((1000 + $i));
                    } else {
                        $objectvalue = intval((1000 + $i));
                    }
                    $reportLogger->debug("SwissEphemerisAPI::getAspects - result subject[$subjectvalue], object[$objectvalue]" . ' \n');
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
                $reportLogger->debug("failed to parse aspect line, line=" . $lines[$line] . ' \n');
                $reportLogger->error("failed to parse aspect line \n");
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
        global $reportLogger;
        $reportLogger->debug("SwissEphemerisAPI::getCalendarAspects \n");
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
            "1011" => "SNode", /* put here to stop the nag messages, not used */
            "1012" => "Ascendant",
            "1013" => "Midheaven", /* "MC", */
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
            $reportLogger->debug("SwissEphemerisAPI::getCalendarAspects - transiting planet = $trans \n");
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
                        $reportLogger->debug("SwissEphemerisAPI::getCalendarAspects - transited cusp = $natalvalue, transiting planet = $transvalue \n");
                        break; /* from for loop */
                    }
                }
            } else {
                /* look for the natal object */
                $natal = trim(substr($line, 55, 10));
                $reportLogger->debug("SwissEphemerisAPI::getCalendarAspects - transited object = $natal \n");
                for ($i = 0; $i < count($planets); $i++) {
                    /* if( $i >= 11 ) { $i++; } */
                    /*
                     * The following works on the basis that we look for the polled object
                     * using the length of the string in the planets table. This should
                     * prevent the (<object> Return) string factoring in the equation
                     */
                    if ($planets[sprintf("%04d", (1000 + $i))] == substr($natal, 0, strlen($planets[sprintf("%04d", (1000 + $i))]))) {
                        $natalvalue = intval((1000 + $i));
                        #				$reportLogger->error("natalvalue=$natalvalue");
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
                $reportLogger->debug("SwissEphemerisAPI::getCalendarAspects - static push \n");
                array_push(
                        $this->m_aspect, /* seasonal/calendars */
                        sprintf("%04d-%02d-%02d %02d:%02d %04d%03d%04d",
                                /* date			 */ intval(substr($line, 6, 4)), intval(substr($line, 0, 2)), intval(substr($line, 3, 2)),
                                /* time			 */ intval(substr($line, 11, 2)), intval(substr($line, 14, 2)),
                                /* transiting	 */ $transvalue,
                                /* aspect		 */ $aspectvalue,
                                /* natal		 */ $natalvalue
                        )
                );
            } else {
                if ($crossing === true) {
                    $reportLogger->debug("SwissEphemerisAPI::getCalendarAspects - push crossing aspect \n");
                    array_push(
                            $this->m_crossing, /* Jupiter/Saturn house crossing */
                            sprintf("%04d-%02d-%02d %02d:%02d %04d%03d%04d",
                                    /* date			 */ intval(substr($line, 6, 4)), intval(substr($line, 3, 2)), intval(substr($line, 0, 2)),
                                    /* time			 */ intval(substr($line, 11, 2)), intval(substr($line, 14, 2)),
                                    /* transiting	 */ $transvalue,
                                    /* aspect		 */ $aspectvalue,
                                    /* natal		 */ $natalvalue
                            )
                    );
                    $reportLogger->debug("SwissEphemerisAPI::getCalendarAspects - crossing = " .
                            sprintf("%04d-%02d-%02d %02d:%02d %04d%03d%04d  \n",
                                    /* date			 */ intval(substr($line, 6, 4)), intval(substr($line, 3, 2)), intval(substr($line, 0, 2)),
                                    /* time			 */ intval(substr($line, 11, 2)), intval(substr($line, 14, 2)),
                                    /* transiting	 */ $transvalue,
                                    /* aspect		 */ $aspectvalue,
                                    /* natal		 */ $natalvalue
                            )
                    );
                } else {
                    $reportLogger->debug("SwissEphemerisAPI::getCalendarAspects - push transit \n");
                    array_push(
                            $this->m_transit, /* Transiting aspects */
                            sprintf("%04d-%02d-%02d %02d:%02d %04d%03d%04d \n",
                                    /* date			 */ intval(substr($line, 6, 4)), intval(substr($line, 3, 2)), intval(substr($line, 0, 2)),
                                    /* time			 */ intval(substr($line, 11, 2)), intval(substr($line, 14, 2)),
                                    /* transiting	 */ $transvalue,
                                    /* aspect		 */ $aspectvalue,
                                    /* natal		 */ $natalvalue
                            )
                    );
                    $reportLogger->debug("SwissEphemerisAPI::getCalendarAspects - transit = " .
                            sprintf("%04d-%02d-%02d %02d:%02d %04d%03d%04d \n",
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
        global $reportLogger;
        $reportLogger->debug("SwissEphemerisAPI::getTransitStartDate( $transit, $natal, $aspect ) \n");
        $date = $this->m_transit_window[$transit][$aspect][$natal]['start'];
        return sprintf("%02d-%02d-%04d", substr($date, 6, 2), substr($date, 4, 2), substr($date, 0, 4));
    }

    /**
     * getTransitEndDate
     */
    function getTransitEndDate($transit, $natal, $aspect) {
        global $reportLogger;
        $reportLogger->debug("SwissEphemerisAPI::getTransitEndDate( $transit, $natal, $aspect ) \n");
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
        global $reportLogger;
        $reportLogger->debug("SwissEphemerisAPI::getEphemeris \n");
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

        global $reportLogger;
        $reportLogger->debug("SwissEphemerisAPI::getEphemerisData \n");

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

        global $reportLogger;
        $reportLogger->debug("SwissEphemerisAPI::getEphemerisObjectData \n");

        $signs = array(
            'Ar' => 0,
            'Ta' => 30,
            'Ge' => 60,
            'Cn' => 90,
            'Le' => 120,
            'Vi' => 150,
            'Li' => 180,
            'Sc' => 210,
            'Sg' => 240,
            'Cp' => 270,
            'Aq' => 300,
            'Pi' => 330);

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
        global $reportLogger;
        $reportLogger->debug("SwissEphemerisAPI::getEphemerisTransitWindow($trans,$aspect,$object) \n");
        $pl_array = array('Sun', 'Moon', 'Mercury', 'Venus', 'Mars', 'Jupiter', 'Saturn', 'Uranus', 'Neptune', 'Pluto', 'NNode', 'SNode');
        /* if the object value >= 1000 then this is a planetary transit */
        if (intval($object) >= 1000) {
            $reportLogger->debug("SwissEphemerisAPI::getEphemerisTransitWindow($trans,$aspect,$object) - aspect to planet \n");
            $natal = floatval($this->m_object[$pl_array[$object - 1000]]['longitude']);
        } else {
            if (intval($object) < 100) {
                /* if the object value < 100 then this is a house cusp transit */
                $reportLogger->debug("SwissEphemerisAPI::getEphemerisTransitWindow($trans,$aspect,$object) - aspect to cusp \n");
                $natal = floatval($this->m_object['cusp'][intval($object)]);
            } else {
                /* if the object value < 1000 and >= 100 then this is a sign ingress (if conjunction) */
                if (intval($aspect) == 0) {
                    $reportLogger->debug("SwissEphemerisAPI::getEphemerisTransitWindow($trans,$aspect,$object) - sign ingress \n");
                } else {
                    /* don't want to know */
                }
            }
        }
        $reportLogger->debug("SwissEphemerisAPI::getEphemerisTransitWindow - natal = " . sprintf("%5.2f", $natal) . ' \n');
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
        $reportLogger->debug("SwissEphemerisAPI::getEphemerisTransitWindow - loapp=$transit_lo_app, hiapp=$transit_hi_app, losep=$transit_lo_sep, hisep=$transit_hi_sep \n");
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
                    $reportLogger->debug("SwissEphemerisAPI::getEphemerisTransitWindow - transit = " . sprintf("%5.2f", $transit).' \n');
                    $start_date = $date;
                }
                /* keep a running track of the end date */
                $end_date = $date;
                $orb = $transit - $natal;
            }
        }
        $reportLogger->debug("SwissEphemerisAPI::getEphemerisTransitWindow - start date = $start_date, end date = $end_date \n");
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
        global $reportLogger;
        $reportLogger->debug("SwissEphemerisAPI::getEphemerisTransitStartDate \n");
        return $this->m_transit_window[$trans][$aspect][$object]['start'];
    }

    /*
     * scan through the ephemeris for the first date where the transiting object
     * makes the required aspect to the target object. This scan is performed in
     * the reverse direction.
     */
    function getEphemerisTransitEndDate($trans, $aspect, $object) {
        global $reportLogger;
        $reportLogger->debug("SwissEphemerisAPI::getEphemerisTransitEndDate \n");
        return $this->m_transit_window[$trans][$aspect][$object]['end'];
    }

    function getEphemerisTransitImageData($trans, $aspect, $object) {
        global $reportLogger;
        $reportLogger->debug("SwissEphemerisAPI::getEphemerisTransitImageData \n");
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
}
?>
