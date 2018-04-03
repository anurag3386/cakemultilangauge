<?php
/*
 * Script: classes/wow/dynamicanalysis.php
 * Author: Andy Gray <andy.gray@astro-consulting.co.uk>
 *
 * Description
 *
 * Modification History
 * - initial spike
*/

$planets = array('Dummy','Sun','Moon','Mercury','Venus','Mars','Jupiter','Saturn','Uranus','Neptune','Pluto','NNode','SNode');
$signs = array('Aries','Taurus','Gemini','Cancer','Leo','Virgo','Libra','Scorpio','Sagittarius','Capricorn','Aquarius','Pisces');
$rulers = array(5,4,3,2,1,3,4,5,6,7,7,6);
$corulers = array(0,0,0,0,0,0,0,10,0,0,8,9);
/**
 * DynamicAnalysis
 *
 * The DynamicAnalysis class produces a packed data recordset for onward analysis and
 * report generation.
 *
 * @author Andy Gray <andy.gray@astro-consulting.co.uk>
 * @copyright Copyright (c) 2005-2008 Andy Gray
 * @copyright Copyright (c) 2008 World of Wisdom
 * @version 1.0
 */
class DynamicAnalysis extends BirthAnalysis
{
    function DynamicAnalysis( $type, $data, $start_year, $duration, $debug = false ) {
        global $logger;
        global $planets;
        global $signs;
        global $rulers;
        global $corulers;

        $logger->debug("DynamicAnalysis::DynamicAnalysis");
        $this->AstrologChartAPI( $data );
        $this->m_analysis_context_debug = $debug;
        /* initialise the context */
        $this->m_analysis_context = '';
        $this->m_timed_data = $data->timed_data;
        if( $this->m_timed_data === true ) {
            $logger->debug("DynamicAnalysis::BirthAnalysis - using timed data");
        } else {
            $logger->debug("DynamicAnalysis::BirthAnalysis - using untimed data (solar chart)");
        }
        $this->m_planets = array(
                'Dummy',
                'Sun',		'Moon',
                'Mercury',	'Venus',	'Mars',
                'Jupiter',	'Saturn',
                'Uranus',	'Neptune',	'Pluto',
                'NNode',	'SNode'
        );

		
        $this->m_record_num = 0;
        $this->sortAspects();
        if( $type == 'pc3' && $this->m_timed_data === true ) {
            $this->packAscendant();
        }
        /*
			      * build the ephemeris data array
        */
        $this->calcEphemeris( $start_year, $duration );
        /*
			      * calculate the dynamic aspects
			      * look for transits to natal planets
			      * sort transits into ascending natal, transit planetary order
			      * remove duplicate entries
        */        
        $this->calcDynamicAspects( $data, $start_year, $duration );
        $this->sortTransits();
        /*
			      * iterate through the sorted transits
			      * determine the transit windows
			      * capture the window start and end dates
			      * capture the dynamic graph data
        */
        foreach( $this->m_transit as $transit) {
            $transiting_planet = substr($transit,17,4);
            $transiting_aspect = substr($transit,21,3);
            $natal_planet = substr($transit,24,4);
            $logger->debug( sprintf("DynamicAnalysis::DynamicAnalysis - %s - transiting[%d] aspect[%d] natal[%d]", $transit, $transiting_planet, $transiting_aspect, $natal_planet));
            $this->m_transit_window[$transiting_planet][$transiting_aspect][$natal_planet] = $this->getEphemerisTransitWindow($transiting_planet,$transiting_aspect,$natal_planet);
        }
        /*
			      * calculate the house cusp crossing aspects
			      * look for transits to natal house cusps
			      * sort transits into ascending tranit planetary order
			      * remove duplicate entries
        */
        $this->calcCrossingAspects( $data, $start_year, $duration );
        $this->sortCrossings();
        /* NEW CODE FOR TRANSIT WINDOWS WRT CUSPS */
        foreach( $this->m_crossing as $transit ) {
            $transiting_planet = substr($transit,17,4);
            $transiting_aspect = substr($transit,21,3);
            $natal_cusp = substr($transit,24,4);
            $logger->debug(
                    sprintf("DynamicAnalysis::DynamicAnalysis - %s - transiting[%d] aspect[%d] natal cusp[%d]",
                    $transit,
                    $transiting_planet,
                    $transiting_aspect,
                    $natal_cusp)
            );
            $this->m_transit_window[$transiting_planet][$transiting_aspect][$natal_cusp] = $this->getEphemerisTransitWindow($transiting_planet,$transiting_aspect,$natal_cusp);
        }
        /* END OF NEW CODE */
        /* find windows for crossings */
        for( $planet = 1; $planet < 12; $planet++ ) {
            $this->m_chapter = $planet;
            $logger->debug("DynamicAnalysis::DynamicAnalysis - planet is ".$this->m_planets[$planet]);
            $this->m_retrograde = (($this->m_object[$this->m_planets[$planet]]['retrograde'] === true) ? 1 : 0);
            if( $type == 'pc3' ) {
                $this->packPlanetInSign($planet);
                if( $this->m_timed_data === true ) {
                    $this->packPlanetInHouse($planet);
                }
                $this->packPlanetInAspect($planet - 1);
            }
            $this->packTransitingPlanetByAspect($planet-1);
            if( $this->m_timed_data === true ) {
                if( $planet >= 6 || $planet <= 10 ) {
                    $this->packTransitingPlanetByHouse($planet - 1);	/* Jupiter through to Pluto only */
                }
            }
        }
        $this->first();
    }

    function packTransitsToAscendant() {
        global $logger;
        global $planets;
        $logger->debug("DynamicAnalysis::packTransitsToAscendant");
        /* loop through the chapters looking for outer planet transits (Jupiter->Pluto) */
        $this->m_chapter = 10001; /* Identity/Ascendant */
        $this->packTransitingPlanetByAspect(12);
    }

    function packPlanets() {
        global $logger;
        $logger->debug("DynamicAnalysis::packPlanets - ?deprecated");
        global $planets;
        for( $planet = 1; $planet < 12; $planet++ ) {
            $this->m_chapter = $planet;
            $this->m_retrograde = $this->m_object[$planets[$planet]]['retrograde'] === true ? 1 : 0;
            $this->packPlanetInSign($planet);
            $this->packPlanetInHouse($planet);
            $this->packPlanetInAspect($planet - 1);
            $this->packTransitingPlanetByAspect($planet-1);
            if( $planet == 6 || $planet == 7 ) {
                $this->packTransitingPlanetByHouse($planet - 1);	/* Jupiter & Saturn only */
            }
        }
    }

    function packTransitingPlanets() {
        global $logger;
        global $planets;
        for( $planet = 1; $planet < 12; $planet++ ) {
            $this->m_chapter = $planet;
            $this->m_retrograde = $this->m_object[$planets[$planet]]['retrograde'] === true ? 1 : 0;
            $this->packTransitingPlanetByAspect($planet-1);
            if( $planet == 6 || $planet == 7 ) {
                $this->packTransitingPlanetByHouse($planet - 1);	/* Jupiter & Saturn only */
            }
        }
    }

    /*
	 #YYYY-MM-DD HH:MM PPPPAAAPPPP
	 #----------1111111111222222222233333333334444444444555555555566666666667
	 #01234567890123456789012345678901234567890123456789012345678901234567890
	 #CCCCCPPPPAAAPPPPR000010DDMMYYYYDDMMYYYY0SSSTTXXX
    */
    function packTransitingPlanetByAspect($planet) {
        global $logger;
        global $planets;

        $logger->debug("DynamicAnalysis::packTransitingPlanetByAspect($planet)");
        $logger->debug("DynamicAnalysis::packTransitingPlanetByAspect - there are ".count($this->m_transit)." transits");

        /* loop through the transits */
        for( $aspect = 0; $aspect < count($this->m_transit); $aspect++ ) {

            /* if this is the natal planet, then we are interested */
            // $logger->debug("DynamicAnalysis::packTransitingPlanetByAspect - looking for transits to natal planet($planet)");
            // $logger->debug("DynamicAnalysis::packTransitingPlanetByAspect - aspectline = ".$this->m_transit[$aspect]."!");
            if( intval(substr($this->m_transit[$aspect],24,4)) == (1000+$planet) ) {

                $this->m_planet = intval(substr($this->m_transit[$aspect],17,4))-1000;
                $logger->debug("DynamicAnalysis::packTransitingPlanetByAspect - planet = $this->m_planet");
                $this->m_aspect_ref = intval(substr($this->m_transit[$aspect],21,3));
                $logger->debug("DynamicAnalysis::packTransitingPlanetByAspect - quarry = ".substr($this->m_transit[$aspect],21,3));
                $logger->debug("DynamicAnalysis::packTransitingPlanetByAspect - aspect = $this->m_aspect_ref");
                $this->m_record_context = 10;
                $this->m_record_context_value = sprintf("%02d", intval(intval(substr($this->m_transit[$aspect],24,4)))-1000);

                $fmtStartDate = $this->m_transit_window[$this->m_planet+1000][sprintf("%03d",$this->m_aspect_ref)][$planet+1000]['start'];
                $logger->debug("DynamicAnalysis::packTransitingPlanetByAspect - formatted start date  $fmtStartDate");
                $start_date = sprintf("%02d%02d%04d",
                        /* day	*/	intval( substr( $fmtStartDate, 4, 2 )),
                        /* month	*/	intval( substr( $fmtStartDate, 6, 2 )),
                        /* year	*/	intval( substr( $fmtStartDate, 0, 4 ))
                );

                //$fmtEndDate = $this->m_transit_window[$this->m_planet+1000][sprintf("%03d",$this->m_aspect_ref)][$planet+1000]['end'];
                //CHANGE BY AMIT PARMAR
                $fmtEndDate = $this->m_transit_window[sprintf("%04d",$this->m_planet+1000)][sprintf("%03d",$this->m_aspect_ref)][sprintf("%04d",$planet+1000)]['end'];

                $logger->debug("DynamicAnalysis::packTransitingPlanetByAspect - formatted end date  $fmtEndDate");
                $end_date = sprintf("%02d%02d%04d",
                        /* day	*/	intval( substr( $fmtEndDate, 4, 2 )),
                        /* month	*/	intval( substr( $fmtEndDate, 6, 2 )),
                        /* year	*/	intval( substr( $fmtEndDate, 0, 4 ))
                );

                /* look for repeat aspects */
                $this->packDynamicRecord( $start_date, $end_date );
            }
        }
    }

    /*
	 #YYYY-MM-DD HH:MM PPPPAAAPPPP
	 #----------1111111111222222222233333333334444444444555555555566666666667
	 #01234567890123456789012345678901234567890123456789012345678901234567890
	 #CCCCCPPPPAAAPPPPR000010DDMMYYYYDDMMYYYY0SSSTTXXX
    */
    function packTransitingPlanetByHouse($planet) {
        global $logger;
        $logger->debug("DynamicAnalysis::packTransitingPlanetByHouse($planet)");

        $logger->debug("DynamicAnalysis::packTransitingPlanetByHouse - scanning through ".count($this->m_crossing)." crossing aspects");
        for( $aspect = 0; $aspect < count($this->m_crossing); $aspect++ ) {

            $logger->debug("DynamicAnalysis::packTransitingPlanetByHouse - looking at ".$this->m_crossing[$aspect]);

            /* look for a house cusp transit */
            if( intval(substr($this->m_crossing[$aspect],17,4)) == (1000+$planet) ) {

                $this->m_planet = intval(substr($this->m_crossing[$aspect],17,4))-1000;
                $logger->debug("DynamicAnalysis::packTransitingPlanetByHouse - crossing aspect = ".$this->m_crossing[$aspect]." - looking at substr 21 for 3 characters");
                $this->m_aspect_ref = intval(substr($this->m_crossing[$aspect],21,3)); /* [AG] changed to 21 from 4 */
                $logger->debug("DynamicAnalysis::packTransitingPlanetByHouse - aspect ref set to ".$this->m_aspect_ref);
                $this->m_record_context = 0;
                $this->m_record_context_value = sprintf("%02d", intval(intval(substr($this->m_crossing[$aspect],24,4))));
                $start_date = sprintf("%02d%02d%04d",
                        /* day		*/	intval( substr( $this->m_crossing[$aspect], 5, 2 )),
                        /* month	*/	intval( substr( $this->m_crossing[$aspect], 8, 2 )),
                        /* year		*/	intval( substr( $this->m_crossing[$aspect], 0, 4 ))
                );
                /* make the end date the same as the start date ***FOR NOW*** */
                $end_date = sprintf("%02d%02d%04d",
                        /* day		*/	intval( substr( $this->m_crossing[$aspect], 5, 2 )),
                        /* month	*/	intval( substr( $this->m_crossing[$aspect], 8, 2 )),
                        /* year		*/	intval( substr( $this->m_crossing[$aspect], 0, 4 ))
                );

                /* reduce repetition */
                $this->packDynamicRecord( $start_date, $end_date );
            }
        }
    }

    function packDynamicRecord( $start_date, $end_date ) {

        global $logger;
        $logger->debug("DynamicAnalysis::packDynamicRecord($start_date,$end_date");
        /* production formatting */
        if($this->m_analysis_context_debug === false) {
            $fmtstr = "%05d%04d%03d%02d%02d%d%d%03d%d%d%08d%08d%d%03d%02d%03d";
        } else {
            /* development formatting */
            $fmtstr = "%05d-%04d-%03d-%02d%02d-%d-%d-%03d-%d-%d-%08d-%08d-%d-%03d-%02d-%03d\n";
        }

        $retrograde = $this->m_retrograde;

        $aspect_strength = 2;	/* default to mild */

        $logger->debug("DynamicAnalysis::packDynamicRecord - aspect ref = ".$this->m_aspect_ref);
        switch( $this->m_aspect_ref ) {
            case 0:		$aspect_type = 0;
                break;
            case 60: case 120:	$aspect_type = 1;
                return;
                break;	/* no soft aspects */
            case 90: case 180:	$aspect_type = 2;
                break;
            default:
            // error
                $aspect_type = 0;
        }

        $logger->debug("DynamicAnalysis::packDynamicRecord - aspect ref = ".$this->m_aspect_ref);
        $record = sprintf( $fmtstr,
                $this->m_chapter + 10001,
                $this->m_planet + 1000,
                $this->m_aspect_ref,
                $this->m_record_context,
                $this->m_record_context_value,
                $retrograde,		/* retrograde */
                0,			/* appear before */
                0,			/* page no */
                1,			/* static = 0, dynamic = 1 */
                0,			/* include next */
                $start_date,		/* DDMMYYYY start date */
                $end_date,		/* DDMMYYYY end date */
                0,			/* repeat in future */
                $aspect_strength,	/* aspect strength	{ 0 => strong,		1 => medium,	2 => weak	} */
                $aspect_type,		/* aspect type		{ 0 => conjoins,	1 => positive,	2 => negative	} */
                $this->m_record_num++
        );

        $logger->debug("DynamicAnalysis::packDynamicRecord - record = $record");

        $this->m_analysis_context .= $record;
    }

    function sortTransits() {
        /* sort the transits, removing duplicates along the way */
        $sortedtransits = array();
        for( $aspect = 0; $aspect < count($this->m_transit); $aspect++ ) {
            $sortedtransits[substr($this->m_transit[$aspect],17,11)] = $this->m_transit[$aspect];
        }
        ksort($sortedtransits);

        unset($this->m_transit);
        $this->m_transit = array();
        reset($sortedtransits);
        while( list($key,$value) = each($sortedtransits) ) {
            array_push(
                    $this->m_transit,
                    $value
            );
        }
    }

    function sortCrossings() {
        /* sort the transits */
        $sortedtransits = array();
        for( $aspect = 0; $aspect < count($this->m_crossing); $aspect++ ) {
            $sortedtransits[substr($this->m_crossing[$aspect],17,11)] = $this->m_crossing[$aspect];
        }
        krsort($sortedtransits);
        //print_r($sortedtransits);

        unset($this->m_crossing);
        $this->m_crossing = array();
        for( $aspect = 0; $aspect < count($sortedtransits); $aspect++ ) {
            array_push(
                    $this->m_crossing,
                    array_pop( $sortedtransits )
            );
        }
        //print_r($this->m_crossing);
    }
}
?>