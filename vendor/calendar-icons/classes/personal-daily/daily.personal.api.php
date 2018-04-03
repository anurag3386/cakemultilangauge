<?php
/**
 * DailyPersonalChartAPI
 *
 * Wrapper class for Astrolog
 *
 * @package AstrologAPI
 *
 * @author Amit Parmar <parmaramit1111@gmail.com>
 * @version 1.0
 */
class DailyPersonalChartAPI {

    /**
     * @var $m_pipe
     */
    var $m_pipe;

    /**
     * @var $m_timeddata
     */
    var $m_timeddata; /* defaults to yes	*/

    /**
     * @var $m_object
     */
    var $m_object; /* planetary objects	*/

    /**
     * @var $m_aspect
     */
    var $m_aspect; /* aspects		*/

    /**
     * @var $m_transit
     */
    var $m_transit; /* transits		*/

    /**
     * @var m_crossing
     */
    var $m_crossings; /* house cusp crossings	*/

    /**
     * @var $m_ephemeris
     */
    var $m_ephemeris; /* cached ephemeris data */

    /**
     * @var $m_transit_window;
     */
    var $m_transit_window;

    /**
     * Constructor
     *
     * @param mixed birthdata
     * @param bool calculate chart data
     * @return AstrologChartAPI
     */
    //function DailyPersonalChartAPI($data, $calcChartData = true) {
    function __constructor ($data, $calcChartData = true) {

        $this->m_timeddata = $data->timed_data;
        if ($calcChartData === true) {
            if ($this->m_timeddata === true) {
                $this->calcChart ( $data );
            } else {
                $this->calcSolarChart ( $data );
            }
            $this->calcAspects ( $data );
        }
        // $this->calcDynamicAspects($data);
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
        /* determine the planetary and house cusp longitudes along with related context information */
        $this->m_pipe = new DailyPersonalInvocationPipe ();

        /* format the birth data */
        $this->m_pipe->addArgument ( $data->qaSwitchFormat () );

        /* display locations in degrees */
        $this->m_pipe->addArgument ( '-sd' );

        /* finally call Astrolog */
        $this->m_pipe->callAstrolog ();
        $lines = explode ( "\n", $this->m_pipe->getCache () );

        for($line = 0; $line < count ( $lines ); $line ++) {
            $this->getPlanetaryLongitude ( $lines [$line] );
        }
        foreach ( $lines as $line ) {
            $this->getHouseCuspLongitude ( $line );
        }
        $this->m_pipe->teardown ();
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
        /*
		 * determine the planetary and house cusp longitudes along with related
		 * context information
        */
        $this->m_pipe = new DailyPersonalInvocationPipe ();

        /*
		 * format the birth data
		 * Note: the chart should be calculated for noon as that will reduce the error
		 * for cuspal charts.
        */
        /* TODO: force noon day chart data here */
        $this->m_pipe->addArgument ( $data->qaSwitchFormat () );

        /* specify whole house system */
        $this->m_pipe->addArgument ( '-c 12' );

        /* display locations in degrees */
        $this->m_pipe->addArgument ( '-sd' );

        /* finally call Astrolog */
        $this->m_pipe->callAstrolog ();

        $lines = explode ( "\n", $this->m_pipe->getCache () );
        for($line = 0; $line < count ( $lines ); $line ++) {
            $this->getPlanetaryLongitude ( $lines [$line] );
        }
        foreach ( $lines as $line ) {
            $this->getHouseCuspLongitude ( $line );
        }
        /* adjust cusp longitude degrees to revised boundaries. Minutes will be 00 */
        $this->m_pipe->teardown ();
    }

    /**
     * calcAspects
     */
    function calcAspects($data) {
        /* determine the aspects */
        $this->m_pipe = new DailyPersonalInvocationPipe ();

        /* format the birth data */
        $this->m_pipe->addArgument ( $data->qaSwitchFormat () );

        /* include the angles as objects */
        $this->m_pipe->addArgument ( '=C' );

        /* ptolemaic aspects only used */
        $this->m_pipe->addArgument ( '-A 5' );

        /* orbs used */
        $this->m_pipe->addArgument ( '-YAo 1 5	 9.0  9.0 6.0  6.0 4.0' ); /* standard	*/
        #	$this->m_pipe->addArgument('-YAo 1 5	 5.0  4.0 3.0  2.0 2.0');		/* strong	*/
        #	$this->m_pipe->addArgument('-YAo 1 5	 9.0  8.0 6.0  6.0 4.0');		/* medium	*/
        #	$this->m_pipe->addArgument('-YAo 1 5	14.0 12.0 9.0 11.0 7.0');		/* weak		*/


        /* max permissable orbs */
        $this->m_pipe->addArgument ( '-YAm 1 10	360 360 360 360 360 360 360 360 360 360' );
        $this->m_pipe->addArgument ( '-YAm 11 20	360 360 360 360 360   3   3   3   3   3' );
        $this->m_pipe->addArgument ( '-YAm 21 32	360 360 360 360 360 360 360 360 360 360 360 360' );
        $this->m_pipe->addArgument ( '-YAm 33 41	360 360 360 360 360 360 360 360 360' );

        /* planet aspect orb additions */
        $this->m_pipe->addArgument ( '-YAd 1 10	0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0' );
        $this->m_pipe->addArgument ( '-YAd 11 20	0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0' );
        $this->m_pipe->addArgument ( '-YAd 21 32	0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0' );

        /* just in case the aspect angles are not defined ... */
        // $this->m_pipe->addArgument('-YAa 1 5	0.0 180.0 90.0 120.0 60.0');


        /* manage aspected object settings */
        $this->m_pipe->addArgument ( '-YR  1 10 0 0 0 0 0 0 0 0 0 0' ); /* main planets enabled */
        $this->m_pipe->addArgument ( '-YR 11 20 1 1 1 1 1 0 1 1 1 1' ); /* node enabled */
        $this->m_pipe->addArgument ( '-YR 21 32 0 1 1 1 1 1 1 1 1 1 1 1' ); /* ascendant enabled */
        $this->m_pipe->addArgument ( '-YR 33 41 1 1 1 1 1 1 1 1 1' ); /* all else disabled */

        /* request aspect generation */
        $this->m_pipe->addArgument ( '-a' );

        /* finally call Astrolog */
        $this->m_pipe->callAstrolog ();
        $lines = explode ( "\n", $this->m_pipe->getCache () );
        $this->getAspects ( $lines );
        $this->m_pipe->teardown ();
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
        /** create the Astrolog low level API instance */
        $this->m_pipe = new DailyPersonalInvocationPipe ();

        /* format the birth data */
        $this->m_pipe->addArgument ( $data->qaSwitchFormat () );

        /* include the angles as objects */
        $this->m_pipe->addArgument ( '=C' );

        /* ptolemaic aspects only used */
        $this->m_pipe->addArgument ( '-A 5' );

        /* orbs used */
        $this->m_pipe->addArgument ( '-YAo 1 5 8.0 8.0 6.0 5.0 5.0' );

        /* max permissable orbs */
        $this->m_pipe->addArgument ( '-YAm  1 10 360 360 360 360 360 360 360 360 360 360' );
        $this->m_pipe->addArgument ( '-YAm 11 20 360 360 360 360 360   3   3   3   3   3' );
        $this->m_pipe->addArgument ( '-YAm 21 32 360 360 360 360 360 360 360 360 360 360 360 360' );
        $this->m_pipe->addArgument ( '-YAm 33 41 360 360 360 360 360 360 360 360 360' );

        /* planet aspect orb additions */
        $this->m_pipe->addArgument ( '-YAd  1 10 1.0 1.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0' );
        $this->m_pipe->addArgument ( '-YAd 11 20 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0' );
        $this->m_pipe->addArgument ( '-YAd 21 32 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0' );

        /* just in case the aspect angles are not defined ... */
        // $this->m_pipe->addArgument('-YAa 1 5	0.0 180.0 90.0 120.0 60.0');


        /* manage aspected object settings */
        $this->m_pipe->addArgument ( '-YR  1 10 0 0 0 0 0 0 0 0 0 0' ); /* main planets enabled */
        $this->m_pipe->addArgument ( '-YR 11 20 1 1 1 1 1 0 1 1 1 1' ); /* node enabled */
        $this->m_pipe->addArgument ( '-YR 21 32 0 1 1 1 1 1 1 1 1 0 1 1' ); /* ascendant/MC enabled */
        $this->m_pipe->addArgument ( '-YR 33 41 1 1 1 1 1 1 1 1 1' ); /* all else disabled */

        /* manage transiting object settings */
        $this->m_pipe->addArgument ( '-YRT 1 10  1 1 1 1 1 0 0 0 0 0' );
        $this->m_pipe->addArgument ( '-YRT 11 20 1 1 1 1 1 1 1 1 1 1' );
        $this->m_pipe->addArgument ( '-YRT 21 32 1 1 1 1 1 1 1 1 1 1 1 1' );
        $this->m_pipe->addArgument ( '-YRT 33 41 1 1 1 1 1 1 1 1 1' );

        /* request aspect generation */
        $this->m_pipe->addArgument ( sprintf ( "-tY %d %d", $start_year, $duration ) );

        /* finally call Astrolog */
        $this->m_pipe->callAstrolog ();
        $lines = explode ( "\n", $this->m_pipe->getCache () );

        $this->m_transit = array ();
        $this->getCalendarAspects ( $lines, true, false );
        $this->m_pipe->teardown ();
    }

    /**
     * calcTransitEntryPoint
     * Subtract the orb value from the planetary longitude and search for transits from
     * one planet to that point
     */
    function calcTransitEntryPoint() {
        /* determine the aspects */
        $this->m_pipe = new DailyPersonalInvocationPipe ();

        /* format the birth data */
        $this->m_pipe->addArgument ( $data->qaSwitchFormat () );

        /* include the angles as objects */
        $this->m_pipe->addArgument ( '=C' );

        /* ptolemaic aspects only used */
        $this->m_pipe->addArgument ( '-A 5' );

        /* orbs used  here we section out the orbs we don't want by setting them to -1 */
        $this->m_pipe->addArgument ( '-YAo 1 5 -1 -1 -1 -1 -1' );
        $this->m_pipe->addArgument ( '-Ao 1 5 -1 -1 -1 -1 -1' );

        /* max permissable orbs */
        $this->m_pipe->addArgument ( '-YAm  1 10 360 360 360 360 360 360 360 360 360 360' );
        $this->m_pipe->addArgument ( '-YAm 11 20 360 360 360 360 360   3   3   3   3   3' );
        $this->m_pipe->addArgument ( '-YAm 21 32 360 360 360 360 360 360 360 360 360 360 360 360' );
        $this->m_pipe->addArgument ( '-YAm 33 41 360 360 360 360 360 360 360 360 360' );

        /* planet aspect orb additions */
        $this->m_pipe->addArgument ( '-YAd  1 10 1.0 1.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0' );
        $this->m_pipe->addArgument ( '-YAd 11 20 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0' );
        $this->m_pipe->addArgument ( '-YAd 21 32 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0' );
    }

    function calcCalendarAspects($data, $start_year, $duration) {
        /* determine the aspects */
        $this->m_pipe = new DailyPersonalInvocationPipe ();

        /* format the birth data */
        $this->m_pipe->addArgument ( $data->qaSwitchFormat () );

        /* include the angles as objects */
        $this->m_pipe->addArgument ( '=C' );

        /* ptolemaic aspects only used */
        $this->m_pipe->addArgument ( '-A 5' );

        /* orbs used */
        $this->m_pipe->addArgument ( '-YAo 1 5 8.0 8.0 6.0 5.0 5.0' );

        /* max permissable orbs */
        $this->m_pipe->addArgument ( '-YAm  1 10 360 360 360 360 360 360 360 360 360 360' );
        $this->m_pipe->addArgument ( '-YAm 11 20 360 360 360 360 360   3   3   3   3   3' );
        $this->m_pipe->addArgument ( '-YAm 21 32 360 360 360 360 360 360 360 360 360 360 360 360' );
        $this->m_pipe->addArgument ( '-YAm 33 41 360 360 360 360 360 360 360 360 360' );

        /* planet aspect orb additions */
        $this->m_pipe->addArgument ( '-YAd  1 10 1.0 1.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0' );
        $this->m_pipe->addArgument ( '-YAd 11 20 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0' );
        $this->m_pipe->addArgument ( '-YAd 21 32 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0' );

        /* just in case the aspect angles are not defined ... */
        // $this->m_pipe->addArgument('-YAa 1 5	0.0 180.0 90.0 120.0 60.0');

        /* manage aspected object settings */
        $this->m_pipe->addArgument ( '-YR  1 10 0 0 0 0 0 0 0 0 0 0' ); /* main planets enabled */
        $this->m_pipe->addArgument ( '-YR 11 20 1 1 1 1 1 0 1 1 1 1' ); /* node enabled */
        $this->m_pipe->addArgument ( '-YR 21 32 0 1 1 1 1 1 1 1 1 0 1 1' ); /* ascendant/MC enabled */
        $this->m_pipe->addArgument ( '-YR 33 41 1 1 1 1 1 1 1 1 1' ); /* all else disabled */

        /* manage transiting object settings */
        $this->m_pipe->addArgument ( '-YRT 1 10  0 1 0 0 0 0 0 0 0 0' );
        $this->m_pipe->addArgument ( '-YRT 11 20 1 1 1 1 1 1 1 1 1 1' );
        $this->m_pipe->addArgument ( '-YRT 21 32 1 1 1 1 1 1 1 1 1 1 1 1' );
        $this->m_pipe->addArgument ( '-YRT 33 41 1 1 1 1 1 1 1 1 1' );

        /* request aspect generation */
        $this->m_pipe->addArgument ( sprintf ( "-tY %d %d", $start_year, $duration ) );

        /* finally call Astrolog */
        $this->m_pipe->callAstrolog ();
        $lines = explode ( "\n", $this->m_pipe->getCache () );
        $this->m_aspect = array ();
        $this->getCalendarAspects ( $lines, false, false );
        $this->m_pipe->teardown ();
    }

    /**
     * calcCrossingAspects
     */
    function calcCrossingAspects($data, $start_year, $duration) {
        /* determine the aspects */
        $this->m_pipe = new DailyPersonalInvocationPipe ();

        /* format the birth data */
        $this->m_pipe->addArgument ( $data->qaSwitchFormat () );

        /* include the angles as objects */
        $this->m_pipe->addArgument ( '=C' );

        /* ptolemaic aspects only used */
        $this->m_pipe->addArgument ( '-A 1' );

        /* orbs used */
        $this->m_pipe->addArgument ( '-YAo 1 5 8.0 8.0 6.0 5.0 5.0' );

        /* max permissable orbs */
        $this->m_pipe->addArgument ( '-YAm  1 10 360 360 360 360 360 360 360 360 360 360' );
        $this->m_pipe->addArgument ( '-YAm 11 20 360 360 360 360 360   3   3   3   3   3' );
        $this->m_pipe->addArgument ( '-YAm 21 32 360 360 360 360 360 360 360 360 360 360 360 360' );
        $this->m_pipe->addArgument ( '-YAm 33 41 360 360 360 360 360 360 360 360 360' );

        /* planet aspect orb additions */
        $this->m_pipe->addArgument ( '-YAd  1 10 1.0 1.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0' );
        $this->m_pipe->addArgument ( '-YAd 11 20 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0' );
        $this->m_pipe->addArgument ( '-YAd 21 32 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0' );

        /* just in case the aspect angles are not defined ... */
        // $this->m_pipe->addArgument('-YAa 1 5	0.0 180.0 90.0 120.0 60.0');


        /* manage aspected object settings */
        $this->m_pipe->addArgument ( '-YR  1 10 1 1 1 1 1 1 1 1 1 1' ); /* main planets enabled */
        $this->m_pipe->addArgument ( '-YR 11 20 1 1 1 1 1 1 1 1 1 1' ); /* node enabled */
        $this->m_pipe->addArgument ( '-YR 21 32 0 0 0 0 0 0 0 0 0 0 0 0' ); /* ascendant/MC enabled */
        $this->m_pipe->addArgument ( '-YR 33 41 1 1 1 1 1 1 1 1 1' ); /* all else disabled */

        /* manage transiting object settings */
        $this->m_pipe->addArgument ( '-YRT 1 10  1 1 1 1 1 0 0 1 1 1' );
        $this->m_pipe->addArgument ( '-YRT 11 20 1 1 1 1 1 1 1 1 1 1' );
        $this->m_pipe->addArgument ( '-YRT 21 32 1 1 1 1 1 1 1 1 1 1 1 1' );
        $this->m_pipe->addArgument ( '-YRT 33 41 1 1 1 1 1 1 1 1 1' );

        /* request aspect generation */
        $this->m_pipe->addArgument ( sprintf ( "-tY %d %d", $start_year, $duration ) );

        /* finally call Astrolog */
        $this->m_pipe->callAstrolog ();
        $lines = explode ( "\n", $this->m_pipe->getCache () );
        $this->m_crossing = array ();
        $this->getCalendarAspects ( $lines, true, true );
        $this->m_pipe->teardown ();
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
        /* we are going to calculate the planetary positions from the start of the current year to the end of the final (duration+1) year */
        $this->m_ephemeris = array ();
        $lines = array ();
        for($i = 0; $i <= $duration; $i ++) {

            $this->m_pipe = new DailyPersonalInvocationPipe ();

            $this->m_pipe->addArgument ( sprintf ( "-qy %04d", $start_year + $i ) );
            $this->m_pipe->addArgument ( "-Ey" ); /* whole year     */
            $this->m_pipe->addArgument ( "-R0" ); /* restrict all   */
            $this->m_pipe->addArgument ( "-R 6" ); /* enable jupiter */
            $this->m_pipe->addArgument ( "-R 7" ); /* enable saturn  */
            $this->m_pipe->addArgument ( "-R 8" ); /* enable uranus  */
            $this->m_pipe->addArgument ( "-R 9" ); /* enable neptune */
            $this->m_pipe->addArgument ( "-R 10" ); /* enable pluto   */

            /* finally call Astrolog */
            $this->m_pipe->callAstrolog ();
            $lines = array_merge ( $lines, explode ( "\n", $this->m_pipe->getCache () ) );
            $this->m_pipe->teardown ();
        }
        $this->getEphemeris ( $start_year, $lines );
    }

    function calcACG($data) {
        /* determine the planetary and house cusp longitudes along with related context information */
        $this->m_pipe = new DailyPersonalInvocationPipe ();

        /* format the birth data */
        $this->m_pipe->addArgument ( $data->qaSwitchFormat () );

        /* set step size */
        $this->m_pipe->addArgument ( '-L 1' );

        /* finally call Astrolog */
        $this->m_pipe->callAstrolog ();
        $lines = explode ( "\n", $this->m_pipe->getCache () );

        $acg_mc = array ();
        $acg_as = array ();
        $acg_ds = array ();

        foreach ( $lines as $line ) {
            /* look for the MC lines */
            if (substr ( $line, 0, 7 ) == 'Midheav') {
                /* scan the line */
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
        $this->m_pipe->addArgument ( '-YAo 1 5 4.0 4.0 4.0 4.0 4.0' ); /* base setting for ptolemaic aspects	*/

        /* max permissable orbs */
        $this->m_pipe->addArgument ( '-YAm  1 10 360 360 360 360 360 360 360 360 360 360' );
        $this->m_pipe->addArgument ( '-YAm 11 20 360 360 360 360 360   3   3   3   3   3' );
        $this->m_pipe->addArgument ( '-YAm 21 32 360 360 360 360 360 360 360 360 360 360 360 360' );
        $this->m_pipe->addArgument ( '-YAm 33 41 360 360 360 360 360 360 360 360 360' );

        /* planet aspect orb additions */
        $this->m_pipe->addArgument ( '-YAd  1 10 5.0 5.0 5.0 5.0 5.0 5.0 5.0 5.0 5.0 5.0' ); /* lift planets to 9 degrees	*/
        $this->m_pipe->addArgument ( '-YAd 11 20 0.0 0.0 0.0 0.0 0.0 2.0 0.0 0.0 0.0 0.0' ); /* lift node to 6 degrees		*/
        $this->m_pipe->addArgument ( '-YAd 21 32 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0' ); /* angles already at 4 degrees	*/
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
                $this->m_object [$planet] ['longitude'] = floatval ( trim ( substr ( $line, 12, 7 ) ) );
                $this->m_object [$planet] ['retrograde'] = ((substr ( $line, 20, 1 ) == 'R') ? true : false);
                $this->m_object [$planet] ['house'] = intval ( trim ( substr ( $line, 35, 2 ) ) );
                $this->m_object [$planet] ['solar'] = intval ( (($this->m_object [$planet] ['house'] + 12 - $this->m_object ['Sun'] ['house']) % 12) + 1 );
                break;
            case 'Node':
            /* North Node */
                $this->m_object ['N' . $planet] ['longitude'] = floatval ( trim ( substr ( $line, 12, 7 ) ) );
                $this->m_object ['N' . $planet] ['retrograde'] = ((substr ( $line, 20, 1 ) == 'R') ? true : false);
                $this->m_object ['N' . $planet] ['house'] = intval ( trim ( substr ( $line, 35, 2 ) ) );
                /* South node */
                if ($this->m_object ['N' . $planet] ['longitude'] >= 180.0) {
                    $this->m_object ['S' . $planet] ['longitude'] = $this->m_object ['N' . $planet] ['longitude'] - 180.0;
                } else {
                    $this->m_object ['S' . $planet] ['longitude'] = $this->m_object ['N' . $planet] ['longitude'] + 180.0;
                }
                $this->m_object ['S' . $planet] ['retrograde'] = $this->m_object ['N' . $planet] ['retrograde'];
                if ($this->m_object ['N' . $planet] ['house'] > 6) {
                    $this->m_object ['S' . $planet] ['house'] = $this->m_object ['N' . $planet] ['house'] - 6;
                } else {
                    $this->m_object ['S' . $planet] ['house'] = $this->m_object ['N' . $planet] ['house'] + 6;
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
            if ($this->m_timeddata === true) {
                $this->m_object ['cusp'] [intval ( trim ( substr ( $line, 68, 2 ) ) )] = floatval ( trim ( substr ( $line, 72, 7 ) ) );
            } else {
                /*
				 * Cusp  1 = 30 * Sun sign
				 * so 342 degrees = 11
				 * add 12 = 23, subtract
                */
                $cusp = intval ( trim ( substr ( $line, 68, 2 ) ) );

                $suncusp = intval ( ($this->m_object ['Sun'] ['longitude'] / 30.0) );
                $this->m_object ['cusp'] [$cusp] = ((($suncusp * 30) + (($cusp - 1) * 30)) % 360);
            }
        }
    }

    /*
	 #----------111111111122222222223333333333444444444455555555556666666666777777777
	 #0123456789012345678901234567890123456789012345678901234567890123456789012345678
	 #  1:  Saturn [Lib] Con [Lib] Neptune    - orb: +0:26' - power: 24.14
    */
    function getAspects($lines) {
        $this->m_aspect = array ();
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
                array_push ( $this->m_aspect, sprintf ( "%04d%03d%04d %s", $subjectvalue, $aspectvalue, $objectvalue, $aspect_orb ) );
            } else {
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
                        break; /* from for loop */
                    }
                }
            } else {
                /* look for the natal object */
                $natal = trim ( substr ( $line, 55, 10 ) );
                for($i = 0; $i < count ( $planets ); $i ++) {
                    /* if( $i >= 11 ) { $i++; } */
                    /*
		     			 * The following works on the basis that we look for the polled object
		     			 * using the length of the string in the planets table. This should
		     			 * prevent the (<object> Return) string factoring in the equation
                    */
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

            if ($dynamic === false) {
                array_push ( $this->m_aspect,		/* seasonal/calendars */
                        sprintf ( "%04d-%02d-%02d %02d:%02d %04d%03d%04d",
                        /* date			*/	intval ( substr ( $line, 6, 4 ) ), intval ( substr ( $line, 0, 2 ) ), intval ( substr ( $line, 3, 2 ) ),
                        /* time			*/	intval ( substr ( $line, 11, 2 ) ), intval ( substr ( $line, 14, 2 ) ),
                        /* transiting	*/	$transvalue,
                        /* aspect		*/	$aspectvalue,
                        /* natal		*/	$natalvalue ) );
            } else {
                if ($crossing === true) {
                    array_push ( $this->m_crossing,		/* Jupiter/Saturn house crossing */
                            sprintf ( "%04d-%02d-%02d %02d:%02d %04d%03d%04d",
                            /* date			*/	intval ( substr ( $line, 6, 4 ) ), intval ( substr ( $line, 3, 2 ) ), intval ( substr ( $line, 0, 2 ) ),
                            /* time			*/	intval ( substr ( $line, 11, 2 ) ), intval ( substr ( $line, 14, 2 ) ),
                            /* transiting	*/	$transvalue,
                            /* aspect		*/	$aspectvalue,
                            /* natal		*/	$natalvalue ) );
                } else {
                    if($natalvalue != 1012 && $natalvalue != 1013 ) {
                        array_push ( $this->m_transit,		/* Transiting aspects */
                                sprintf ( "%04d-%02d-%02d %02d:%02d %04d%03d%04d",
                                /* date			*/	intval ( substr ( $line, 6, 4 ) ), intval ( substr ( $line, 3, 2 ) ), intval ( substr ( $line, 0, 2 ) ),
                                /* time			*/	intval ( substr ( $line, 11, 2 ) ), intval ( substr ( $line, 14, 2 ) ),
                                /* transiting	*/	$transvalue,
                                /* aspect		*/	$aspectvalue,
                                /* natal		*/	$natalvalue ) );
                    }
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
        $date = $this->m_transit_window [$transit] [$aspect] [$natal] ['start'];
        return sprintf ( "%02d-%02d-%04d", substr ( $date, 6, 2 ), substr ( $date, 4, 2 ), substr ( $date, 0, 4 ) );
    }

    /**
     * getTransitEndDate
     */
    function getTransitEndDate($transit, $natal, $aspect) {
        $date = $this->m_transit_window [$transit] [$aspect] [$natal] ['end'];
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
                $this->m_ephemeris [$date] = $edate ['planets'];
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
            //$natal = floatval ( $this->m_object [$pl_array [$object - 1000]] ['longitude'] );

            //Added By Amit Parmar On 24-Nov-2012
            if(intval ( $object ) >= 1012) {
                if(intval ( $object ) == 1012) {
                    $natal = floatval ( $this->m_object [$pl_array [1]] ['longitude'] );
                }
                else if(intval ( $object ) == 1013) {
                    $natal = floatval ( $this->m_object [$pl_array [10]] ['longitude'] );
                }
            }
            else {
                $natal = floatval ( $this->m_object [$pl_array [$object - 1000]] ['longitude'] );
            }

        } else {
            if (intval ( $object ) < 100) {
                /* if the object value < 100 then this is a house cusp transit */
                $natal = floatval ( $this->m_object ['cusp'] [intval ( $object )] );
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
        reset ( $this->m_ephemeris );


        /* scan the ephemeris table */
        for($i = 0; $i < count ( $this->m_ephemeris ); $i ++) {
            $line = each ( $this->m_ephemeris );
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
        return $this->m_transit_window [$trans] [$aspect] [$object] ['start'];
    }

    /*
	 * scan through the ephemeris for the first date where the transiting object
	 * makes the required aspect to the target object. This scan is performed in
	 * the reverse direction.
    */
    function getEphemerisTransitEndDate($trans, $aspect, $object) {
        return $this->m_transit_window [$trans] [$aspect] [$object] ['end'];
    }

    function getEphemerisTransitImageData($trans, $aspect, $object) {
        return $this->m_transit_window [$trans] [$aspect] [$object] ['data'];
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
//    function getDailyAspects() {
//    }

    function getDailyAspects($data, $month, $day, $year, $duration) {
        /* Determine the Aspects */
        $this->m_pipe = new DailyPersonalInvocationPipe ();

        /* Format the Birth Data */
        $this->m_pipe->addArgument ( $data->qaSwitchFormat () );

        /* Include the Angles As Objects */
        $this->m_pipe->addArgument ( '=C' );

        /* Ptolemaic Aspects Only Used */
        $this->m_pipe->addArgument ( '-A 5' );

        /* Orbs Used */
        $this->m_pipe->addArgument ( '-YAo 1 5 8.0 8.0 6.0 5.0 5.0' );

        /* Max Permissable Orbs */
        $this->m_pipe->addArgument ( '-YAm  1 10 360 360 360 360 360 360 360 360 360 360' );
        $this->m_pipe->addArgument ( '-YAm 11 20 360 360 360 360 360   3   3   3   3   3' );
        $this->m_pipe->addArgument ( '-YAm 21 32 360 360 360 360 360 360 360 360 360 360 360 360' );
        $this->m_pipe->addArgument ( '-YAm 33 41 360 360 360 360 360 360 360 360 360' );

        /* Planet Aspect Orb Additions */
        $this->m_pipe->addArgument ( '-YAd  1 10 1.0 1.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0' );
        $this->m_pipe->addArgument ( '-YAd 11 20 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0' );
        $this->m_pipe->addArgument ( '-YAd 21 32 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0' );

        /* Manage Aspected Object Settings */
        $this->m_pipe->addArgument ( '-YR  1 10 0 0 0 0 0 0 0 0 0 0' );             /* Main Planets Enabled */
        $this->m_pipe->addArgument ( '-YR 11 20 1 1 1 1 1 0 1 1 1 1' );             /* Node Enabled */
        $this->m_pipe->addArgument ( '-YR 21 32 0 1 1 1 1 1 1 1 1 0 1 1' );         /* Ascendant/MC Enabled */
        $this->m_pipe->addArgument ( '-YR 33 41 1 1 1 1 1 1 1 1 1' );               /* All Else Disabled */

        /* Manage Transiting Object Settings */
        $this->m_pipe->addArgument ( '-YRT 1 10  0 1 0 0 0 0 0 0 0 0' );
        $this->m_pipe->addArgument ( '-YRT 11 20 1 1 1 1 1 1 1 1 1 1' );
        $this->m_pipe->addArgument ( '-YRT 21 32 1 1 1 1 1 1 1 1 1 1 1 1' );
        $this->m_pipe->addArgument ( '-YRT 33 41 1 1 1 1 1 1 1 1 1' );

        /* request aspect generation */
        //$this->m_pipe->addArgument ( sprintf ( "-tdY %d %d", $start_year, $duration ) );
        //                                      -T MONTH DAY YEAR
        //$this->m_pipe->addArgument ( sprintf ( "-T %d %d %d", $month, $day, $year ) );
        $this->m_pipe->addArgument ( sprintf ( "-t %d %d", $month, $year) );

        /* finally call Astrolog */
        $this->m_pipe->callAstrolog ();
        
        $lines = explode ( "\n", $this->m_pipe->getCache () );
        $this->m_aspect = array ();
        
        $this->getDailyCalendarAspects ( $lines, false, false );

        //$this->getDailyPersonalAspects ( $lines, false, false );
        $this->m_pipe->teardown ();
    }

    function getDailyMoonAspects($data, $month, $day, $year, $duration) {
        /* determine the aspects */
        $this->m_pipe = new DailyPersonalInvocationPipe ();

        /* format the birth data */
        $this->m_pipe->addArgument ( $data->qaSwitchFormat () );

        /* include the angles as objects */
        $this->m_pipe->addArgument ( '=C' );

        /* ptolemaic aspects only used */
        $this->m_pipe->addArgument ( '-A 5' );

        /* orbs used */
        $this->m_pipe->addArgument ( '-YAo 1 5 8.0 8.0 6.0 5.0 5.0' );

        /* max permissable orbs */
        $this->m_pipe->addArgument ( '-YAm  1 10 360 360 360 360 360 360 360 360 360 360' );
        $this->m_pipe->addArgument ( '-YAm 11 20 360 360 360 360 360   3   3   3   3   3' );
        $this->m_pipe->addArgument ( '-YAm 21 32 360 360 360 360 360 360 360 360 360 360 360 360' );
        $this->m_pipe->addArgument ( '-YAm 33 41 360 360 360 360 360 360 360 360 360' );

        /* planet aspect orb additions */
        $this->m_pipe->addArgument ( '-YAd  1 10 1.0 1.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0' );
        $this->m_pipe->addArgument ( '-YAd 11 20 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0' );
        $this->m_pipe->addArgument ( '-YAd 21 32 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0 0.0' );

        /* just in case the aspect angles are not defined ... */
        // $this->m_pipe->addArgument('-YAa 1 5	0.0 180.0 90.0 120.0 60.0');

        /* manage aspected object settings */
        $this->m_pipe->addArgument ( '-YR  1 10 0 0 0 0 0 0 0 0 0 0' ); /* main planets enabled */
        $this->m_pipe->addArgument ( '-YR 11 20 1 1 1 1 1 0 1 1 1 1' ); /* node enabled */
        $this->m_pipe->addArgument ( '-YR 21 32 0 1 1 1 1 1 1 1 1 0 1 1' ); /* ascendant/MC enabled */
        $this->m_pipe->addArgument ( '-YR 33 41 1 1 1 1 1 1 1 1 1' ); /* all else disabled */

        /* manage transiting object settings */
        $this->m_pipe->addArgument ( '-YRT 1 10  1 0 1 1 1 1 1 1 1 1' );
        $this->m_pipe->addArgument ( '-YRT 11 20 1 1 1 1 1 1 1 1 1 1' );
        $this->m_pipe->addArgument ( '-YRT 21 32 1 1 1 1 1 1 1 1 1 1 1 1' );
        $this->m_pipe->addArgument ( '-YRT 33 41 1 1 1 1 1 1 1 1 1' );

        /* request aspect generation */
        //$this->m_pipe->addArgument ( sprintf ( "-tdY %d %d", $start_year, $duration ) );
        //                                      -T MONTH DAY YEAR
        $this->m_pipe->addArgument ( sprintf ( "-T %d %d %d", $month, $day, $year ) );
        //$this->m_pipe->addArgument ( sprintf ( "-t %d %d", $month, $year) );

        /* finally call Astrolog */
        $this->m_pipe->callAstrolog ();

        $lines = explode ( "\n", $this->m_pipe->getCache () );
        $this->m_aspect = array ();

        $this->getDailyPersonalAspects ( $lines, false, false );
        $this->m_pipe->teardown ();        
    }

    /**
     *  Astrolog 5.41G chart for
     *  Fri Jul 16, 1982 12:00:00pm (ST +0:00 GMT)  68:58:00E 22:14:00N
     *  Transits for
     *  Mon Nov 26, 2012 11:59:00pm (ST +2:00 GMT)  26:43:00E 58:23:00N
     *    1: trans Neptune (Pis) Tri natal (Sco) Jupiter   - app 0:31' - power: 18.07
     *    2: trans Neptune (Pis) Squ natal [Sag] Uranus    - app 0:23' - power: 15.94
     *    3: trans  Uranus [Ari] Opp natal (Lib) Midheaven - app 1:29' - power: 14.61
     *    4: trans     Sun (Sag) Sex natal (Lib) Midheaven - sep 1:49' - power:  2.48
     *
     *--12345678901234567890123456789012345678901234567890123456789012345678901234567890 [Total 85 CHAR]
     *
     * @param Line array $lines
     * @param Boolean $dynamic
     * @param Boolean $crossing
     */
    function getDailyPersonalAspects($lines, $dynamic = false, $crossing = false) {
        $planets = array ("1000" => "Sun",          "1001" => "Moon",          "1002" => "Mercury",
                          "1003" => "Venus",        "1004" => "Mars",          "1005" => "Jupiter",
                          "1006" => "Saturn",       "1007" => "Uranus",         "1008" => "Neptune",
                          "1009" => "Pluto",        "1010" => "Node",           "1011" => "SNode",		/* put here to stop the nag messages, not used */
                          "1012" => "Ascendant",    "1013" => "Midheaven",	/* "MC", */
                          "1014" => "IC",           "1015" => "Descendant" );
        
        $houses = array ("0001" => "Ascendant",     "0002" => "2nd Cusp",       "0003" => "3rd Cusp",
                         "0004" => "IC",            "0005" => "5th Cusp",       "0006" => "6th Cusp",
                         "0007" => "Descendant",    "0008" => "8th Cusp",       "0009" => "9th Cusp",
                         "0010" => "Midheaven",     "0011" => "11th Cusp",      "0012" => "12th Cusp" );

        $aspects = array ("000" => "Con", "060" => "Sex", "090" => "Squ", "120" => "Tri", "180" => "Opp" );

        $signs = array ("Ari" => "0100",      "Tau" => "0101",          "Gem" => "0102",
                        "Can" => "0103",      "Leo" => "0104",          "Vir" => "0105",
                        "Lib" => "0106",      "Sco" => "0107",          "Sag" => "0108",
                        "Cap" => "0109",      "Aqu" => "0110",          "Pis" => "0111",);

        /* iterate through the results */
        foreach ( $lines as $line ) {
            
            /* manage last (blank) line */
            if (strlen ( $line ) < 1) {
                continue;
            }
            $IsThereAnyTransit = trim ( substr ( $line, 5, 5 ) );            

            if($IsThereAnyTransit == 'trans') {
                /* look for the transiting planet */

                $trans = trim ( substr ( $line, 11, 8 ) );
                for($i = 0; $i < count ( $planets ); $i ++) {
                    if ($i >= 11) {
                        $i ++;
                    }
                    if ($planets [(1000 + $i)] == $trans) {
                        $transvalue = intval ( (1000 + $i) );
                        break;
                    }
                }
                $transSign = trim ( substr ( $line, 20, 3 ) );
                $signArrayKey = array_keys($signs);
                if(in_array( $transSign, $signArrayKey)){
                    $transsignvalue = $signs[$transSign];
                }                

                /* look for the natal object take special care where returns are encountered */
                if ($crossing === true) {
                    $natal = trim ( substr ( $line, 41, 10 ) );
                    for($i = 0; $i < count ( $houses ); $i ++) {
                        /* if( $i >= 11 ) { $i++; } */
                        if ($houses [sprintf ( "%04d", ($i + 1) )] == $natal) {
                            $natalvalue = intval ( $i + 1 );
                            break; /* from for loop */
                        }
                    }
                    $natalSign = trim ( substr ( $line, 36, 3 ) );
                    unset($signArrayKey);
                    $signArrayKey = array_keys($signs);
                    if(in_array( $transSign, $signArrayKey)){
                        $natalsignvalue = $signs[$natalSign];
                    }
                    
                } else {
                    /* look for the natal object */
                    $natal = trim ( substr ( $line, 41, 10 ) );
                    for($i = 0; $i < count ( $planets ); $i ++) {
                        /* if( $i >= 11 ) { $i++; } */
                        
                        /**
                         * The following works on the basis that we look for the polled object using the length of the string in the planets table. This should
                         *  prevent the (<object> Return) string factoring in the equation
                         */
                        if ($planets [sprintf ( "%04d", (1000 + $i) )] == substr ( $natal, 0, strlen ( $planets [sprintf ( "%04d", (1000 + $i) )] ) )) {
                            $natalvalue = intval ( (1000 + $i) );
                            break;
                        } else {
                            // oops
                        }
                    }
                    
                    $natalSign = trim ( substr ( $line, 36, 3 ) );
                    unset($signArrayKey);
                    $signArrayKey = array_keys($signs);
                    if(in_array( $transSign, $signArrayKey)){
                        $natalsignvalue = $signs[$natalSign];
                    }

                }

                $aspect = trim ( substr ( $line, 25, 3 ) );
                reset ( $aspects );
                while ( $aspectname = current ( $aspects ) ) {
                    if ($aspectname == $aspect) {
                        $aspectvalue = intval ( key ( $aspects ) );
                        break;
                    }
                    next ( $aspects );
                }

                if ($dynamic === false) {
                    array_push ( $this->m_aspect,		/* seasonal/calendars */
                            sprintf ( "%04d[%04d]%03d%04d[%04d]",
                            $transvalue,                                                                                                /* TRANSITING   */
                            $transsignvalue,                                                                                            /* TRANSITING PLANET In SIGN   */
                            $aspectvalue,                                                                                               /* ASPECT       */
                            $natalvalue,                                                                                                /* NATAL        */
                            $natalsignvalue ) );                                                                                        /* NATAL PLANET In SIGN         */
                } else {
                    if ($crossing === true) {
                        array_push ( $this->m_crossing,		/* Jupiter/Saturn house crossing */
                                sprintf (  "%04d[%04d]%03d%04d[%04d]",
                                $transvalue,                                                                                                /* TRANSITING   */
                                $transsignvalue,                                                                                            /* TRANSITING PLANET In SIGN   */
                                $aspectvalue,                                                                                               /* ASPECT       */
                                $natalvalue,                                                                                                /* NATAL        */
                                $natalsignvalue ) );                                                                                        /* NATAL PLANET In SIGN         */
                    } else {
                        if($natalvalue != 1012 && $natalvalue != 1013 ) {
                            array_push ( $this->m_transit,		/* Transiting aspects */
                                    sprintf (  "%04d[%04d]%03d%04d[%04d]",
                                    $transvalue,                                                                                                /* TRANSITING   */
                                    $transsignvalue,                                                                                            /* TRANSITING PLANET In SIGN   */
                                    $aspectvalue,                                                                                               /* ASPECT       */
                                    $natalvalue,                                                                                            /* NATAL        */
                                    $natalsignvalue ) );                                                                                        /* NATAL PLANET In SIGN         */
                        }
                    }
                }
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
    function getDailyCalendarAspects($lines, $dynamic = false, $crossing = false) {
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
                        break; /* from for loop */
                    }
                }
            } else {
                /* look for the natal object */
                $natal = trim ( substr ( $line, 55, 10 ) );
                for($i = 0; $i < count ( $planets ); $i ++) {
                    /* if( $i >= 11 ) { $i++; } */
                    /*
		     			 * The following works on the basis that we look for the polled object
		     			 * using the length of the string in the planets table. This should
		     			 * prevent the (<object> Return) string factoring in the equation
                    */
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

            if ($dynamic === false) {
                array_push ( $this->m_aspect,		/* seasonal/calendars */
                        sprintf ( "%04d-%02d-%02d %02d:%02d %04d%03d%04d",
                        /* date			*/	intval ( substr ( $line, 6, 4 ) ), intval ( substr ( $line, 0, 2 ) ), intval ( substr ( $line, 3, 2 ) ),
                        /* time			*/	intval ( substr ( $line, 11, 2 ) ), intval ( substr ( $line, 14, 2 ) ),
                        /* transiting	*/	$transvalue,
                        /* aspect		*/	$aspectvalue,
                        /* natal		*/	$natalvalue ) );
            } else {
                if ($crossing === true) {
                    array_push ( $this->m_crossing,		/* Jupiter/Saturn house crossing */
                            sprintf ( "%04d-%02d-%02d %02d:%02d %04d%03d%04d",
                            /* date			*/	intval ( substr ( $line, 6, 4 ) ), intval ( substr ( $line, 3, 2 ) ), intval ( substr ( $line, 0, 2 ) ),
                            /* time			*/	intval ( substr ( $line, 11, 2 ) ), intval ( substr ( $line, 14, 2 ) ),
                            /* transiting	*/	$transvalue,
                            /* aspect		*/	$aspectvalue,
                            /* natal		*/	$natalvalue ) );
                } else {
                    if($natalvalue != 1012 && $natalvalue != 1013 ) {
                        array_push ( $this->m_transit,		/* Transiting aspects */
                                sprintf ( "%04d-%02d-%02d %02d:%02d %04d%03d%04d",
                                /* date			*/	intval ( substr ( $line, 6, 4 ) ), intval ( substr ( $line, 3, 2 ) ), intval ( substr ( $line, 0, 2 ) ),
                                /* time			*/	intval ( substr ( $line, 11, 2 ) ), intval ( substr ( $line, 14, 2 ) ),
                                /* transiting	*/	$transvalue,
                                /* aspect		*/	$aspectvalue,
                                /* natal		*/	$natalvalue ) );
                    }
                }
            }
        }
    }
};
?>