<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of birthtransit-calculation
 *
 * @author Amit Parmar
 */

$BirthPlanets = array('Dummy', 'Sun', 'Moon', 'Mercury', 'Venus', 'Mars', 'Jupiter', 'Saturn', 'Uranus', 'Neptune', 'Pluto', 'NNode', 'SNode');
$signs = array('Aries', 'Taurus', 'Gemini', 'Cancer', 'Leo', 'Virgo', 'Libra', 'Scorpio', 'Sagittarius', 'Capricorn', 'Aquarius', 'Pisces');
$rulers = array(5, 4, 3, 2, 1, 3, 4, 5, 6, 7, 7, 6);
$corulers = array(0, 0, 0, 0, 0, 0, 0, 10, 0, 0, 8, 9);

class BirthTransitCalculation extends AstrologServices {

    var $m_chapter;
    var $m_planet;
    var $m_aspect_ref;
    var $m_aspect_orb;
    var $m_record_context;
    var $m_record_context_value;
    var $m_retrograde;
    var $m_record_num;
    var $m_chart_ruler;
    var $m_chart_coruler;
    var $m_analysis_context;
    var $m_analysis_context_index;
    var $m_analysis_context_debug;
    var $m_timed_data;
    var $m_planets;

    function BirthTransitCalculation($data, $debug = false) {

        global $logger;
        global $BirthPlanets;
        global $signs;
        global $rulers;
        global $corulers;

        $logger->debug("BirthTransitCalculation::BirthTransitCalculation");
        $this->AstrologChartAPI($data);
        $this->m_analysis_context_debug = $debug;
        /* initialise the context */
        $this->m_analysis_context = '';
        $this->m_timed_data = $data->timed_data;
        if ($this->m_timed_data === true) {
            $logger->debug("BirthTransitCalculation::BirthTransitCalculation - using timed data");
        } else {
            $logger->debug("BirthTransitCalculation::BirthTransitCalculation - using untimed data (solar chart)");
        }
        $this->m_planets = array(
            'Dummy',
            'Sun', 'Moon',
            'Mercury', 'Venus', 'Mars',
            'Jupiter', 'Saturn',
            'Uranus', 'Neptune', 'Pluto',
            'NNode', 'SNode'
        );

        $this->m_record_num = 0;
        $this->sortAspects();
        /* the ascendant is meaningless for a slar chart */
        if ($this->m_timed_data === true) {
            $this->packAscendant();
        }
        $this->packPlanets();
        $this->first();
    }

    function packAscendant() {

        global $logger;
        global $signs;
        global $rulers;
        global $corulers;

        $logger->debug("BirthTransitCalculation::packAscendant");
        $this->m_chapter = 0;
        $degree = floatval($this->m_object['cusp'][1]);
        $logger->debug("BirthTransitCalculation::packAscendant, degree = $degree");
        $sign = intval((floatval($degree) / 30.0));

        $this->packAscInSign();
        $this->packAscInAspect();
        $this->m_retrograde = $this->m_object[$this->m_planets[$rulers[$sign]]]['retrograde'] === true ? 1 : 0;
        $this->packPlanetInSign($rulers[$sign]);
        $this->packPlanetInHouse($rulers[$sign]);
        $this->m_chart_ruler = $rulers[$sign];
        if ($corulers[$sign] > 0) {
            $this->m_retrograde = $this->m_object[$this->m_planets[$corulers[$sign]]]['retrograde'] === true ? 1 : 0;
            $this->packPlanetInSign($corulers[$sign]);
            $this->packPlanetInHouse($corulers[$sign]);
            $this->m_chart_coruler = $corulers[$sign];
        }
    }

    function packPlanets() {

        global $logger;

        $logger->debug("BirthTransitCalculation::packPlanets");
        for ($planet = 1; $planet < 12; $planet++) {
            $this->m_chapter = $planet;
            $logger->debug("BirthTransitCalculation::packPlanets - planet is " . $BirthPlanets[$planet]);
            $this->m_retrograde = $this->m_object[$this->m_planets[$planet]]['retrograde'] === true ? 1 : 0;
            $this->packPlanetInSign($planet);
            $this->packPlanetInHouse($planet);
            $this->packPlanetInAspect($planet - 1);
        }
    }

    function packAscInSign() {

        global $logger;
        global $signs;

        $logger->debug("BirthTransitCalculation::packAscInSign");
        $this->m_planet = 12;
        $this->m_aspect_ref = "200";
        $this->m_aspect_orb = 0.0;
        $this->m_record_context = 1;
        $degree = floatval($this->m_object['cusp'][1]);
        $logger->debug("BirthTransitCalculation::packAscInSign, degree = $degree");
        $sign = intval((floatval($degree) / 30.0));
        $logger->debug("BirthTransitCalculation::packAscInSign, sign = $sign");
        $this->m_record_context_value = sprintf("%02d", $sign);
        $this->packRecord();
    }

    function packAscInAspect() {

        global $logger;

        $logger->debug("BirthTransitCalculation::packAscInAspect");
        for ($aspect = 0; $aspect < count($this->m_aspect); $aspect++) {
            if (intval(substr($this->m_aspect[$aspect], 0, 4)) == (1012)) {
                $this->m_aspect_ref = intval(substr($this->m_aspect[$aspect], 4, 3));
                $this->m_aspect_orb = substr($this->m_aspect[$aspect], 12, 5);
                $this->m_record_context = 10;
                $this->m_record_context_value = sprintf("%02d", intval(intval(substr($this->m_aspect[$aspect], 7, 4))) - 1000);
                $this->packRecord();
            }
        }
    }

    /*
     * Planet in Sign
     * Format = 01PP
     * Where PP = 00 (Sun) through to 09 (Pluto), 10-11 (N/S Nodes) and 12 (Ascendant)
     * Need to be aware of the cross referencing here as it is based
     * on the subindexed digits which can have a disorienting effect
     * if we are in the wrong chapter.
     */

    function packPlanetInSign($planet /* 1..10 */) {

        global $logger;
        global $signs;

        $logger->debug("BirthTransitCalculation::packPlanetInSign($planet) => $this->m_planets[$planet]");
        $this->m_planet = ($planet - 1);
        $this->m_aspect_ref = "200";
        $this->m_aspect_orb = 0.0;
        $this->m_record_context = 1;
        $degree = floatval($this->m_object[$this->m_planets[$planet]]['longitude']);
        $logger->debug("BirthTransitCalculation::packPlanetInSign(degree = $degree)");
        $sign = intval((floatval($degree) / 30.0));
        $logger->debug("BirthTransitCalculation::packPlanetInSign(sign = $sign)");
        $logger->debug("BirthTransitCalculation::packPlanetInSign(retrograde = [" . $this->m_retrograde . "])");
        $this->m_record_context_value = sprintf("%02d", $sign);
        $this->packRecord();
    }

    function packPlanetInHouse($planet) {

        global $logger;

        $logger->debug("BirthTransitCalculation::packPlanetInhouse($planet)");
        $this->m_aspect_ref = "200";
        $this->m_aspect_orb = 0.0;
        $this->m_record_context = 0;
        $this->m_record_context_value = sprintf("%02d", intval($this->m_object[$this->m_planets[$planet]]['house']));
        $this->packRecord();
    }

    function packPlanetInAspect($planet) {

        global $logger;

        $logger->debug("BirthTransitCalculation::packPlanetInAspect($planet)");
        for ($aspect = 0; $aspect < count($this->m_aspect); $aspect++) {
            if (intval(substr($this->m_aspect[$aspect], 0, 4)) == (1000 + $planet)) {
                $this->m_aspect_ref = intval(substr($this->m_aspect[$aspect], 4, 3));
                $this->m_aspect_orb = substr($this->m_aspect[$aspect], 12, 5);
                $this->m_record_context = 10;
                $this->m_record_context_value = sprintf("%02d", intval(intval(substr($this->m_aspect[$aspect], 7, 4))) - 1000);
                $this->packRecord();
            }
        }
    }

    /*
     * packRecord
     *
     * 00 -  5 - Principle planet or object
     * 05 -  4 - Secondary planet or object
     * 09 -  3 - Connection (aspect or in/200)
     * 12 -  2 - Scope (sign, house, aspect)
     * 14 -  2 - Scope context (wrt above)
     * 16 -  1 - Retrograde
     * 17 -  1 - Appear before (duplicate)
     * 18 -  3 - Page number
     * 21 -  1 - static
     * 22 -  1 - include next
     * 23 - 16 - start/end date
     * 39 -  1 - repeated in future
     * 40 -  3 - aspect strength
     * 43 -  2 - aspect type
     * 45 -  3 - record index
     */

    function packRecord() {

        global $logger;

        $logger->debug("BirthTransitCalculation::packRecord");
        /* production formatting */
        if ($this->m_analysis_context_debug === false) {
            $fmtstr = "%05d%04d%03d%02d%02d%d%d0000000000000000000000%03d%02d%03d";
        } else {
            /* development formatting */
            $fmtstr = "%05d-%04d-%03d-%02d%02d-%d-%d-0000000000000000000000-%03d-%02d-%03d\n";
        }

        $retrograde = $this->m_retrograde;

        /*
         * TODO - this needs to be addressed once the PC3 report is stable
         * valid set = 1..3
         */
        $logger->debug("BirthTransitCalculation::packRecord - aspect=$this->m_aspect_ref");
        $logger->debug("BirthTransitCalculation::packRecord - aspect orb = " . $this->m_aspect_orb);
        if (substr($this->m_aspect_orb, 0, 1) == '+') {
            $logger->debug("BirthTransitCalculation::packRecord - aspect is applying");
        } else {
            $logger->debug("BirthTransitCalculation::packRecord - aspect is separating");
        }

        /*
         * TODO - this is still 0 (Conjunct), 1 (soft), 2 (hard). need to extract actual aspects
         * Suggest line up with Astrolog settings
         * 1 (Conjunction), 2 (Opposition), 3 (Square), 4 (Trine), 5 (Sextile)
         */
        switch ($this->m_aspect_ref) {
            case 0: /* Conjunction */
                /*
                 * Aspect is hardcoded to 9.0 at the moment
                 * - strong < 2.0
                 * - medium < 5.0
                 * - weak >= 5.0
                 */
                $logger->debug("BirthTransitCalculation::packRecord - conjunction");
                $aspect_type = 0;
                switch (intval(substr($this->m_aspect_orb, 1, 1))) {
                    case 0: case 1:
                        $logger->debug("BirthTransitCalculation::packRecord - aspect is strong");
                        $aspect_strength = 0;
                        break;
                    case 2: case 3: case 4:
                        $logger->debug("BirthTransitCalculation::packRecord - aspect is medium");
                        $aspect_strength = 1;
                        break;
                    default:
                        $logger->debug("BirthTransitCalculation::packRecord - aspect is weak");
                        $aspect_strength = 2;
                        break;
                }
                break;
            case 60: /* Sextile */
                /*
                 * Aspect is hardcoded to 4.0 at the moment
                 * - strong < 1.0
                 * - medium < 3.0
                 * - weak >= 3.0
                 */
                $aspect_type = 1;
                switch (intval(substr($this->m_aspect_orb, 1, 1))) {
                    case 0:
                        $logger->debug("BirthTransitCalculation::packRecord - aspect is strong");
                        $aspect_strength = 0;
                        break;
                    case 1: case 2:
                        $logger->debug("BirthTransitCalculation::packRecord - aspect is medium");
                        $aspect_strength = 1;
                        break;
                    default:
                        $logger->debug("BirthTransitCalculation::packRecord - aspect is weak");
                        $aspect_strength = 2;
                        break;
                }
                break;
            case 90:
                /*
                 * Aspect is hardcoded to 6.0 at the moment
                 * - strong < 2.0
                 * - medium < 4.0
                 * - weak >= 4.0
                 */
                $aspect_type = 2;
                switch (intval(substr($this->m_aspect_orb, 1, 1))) {
                    case 0: case 1:
                        $logger->debug("BirthTransitCalculation::packRecord - aspect is strong");
                        $aspect_strength = 0;
                        break;
                    case 2: case 3:
                        $logger->debug("BirthTransitCalculation::packRecord - aspect is medium");
                        $aspect_strength = 1;
                        break;
                    default:
                        $logger->debug("BirthTransitCalculation::packRecord - aspect is weak");
                        $aspect_strength = 2;
                        break;
                }
                break;
            case 120:
                /*
                 * Aspect is hardcoded to 6.0 at the moment
                 * - strong < 2.0
                 * - medium < 4.0
                 * - weak >= 4.0
                 */
                $aspect_type = 1;
                switch (intval(substr($this->m_aspect_orb, 1, 1))) {
                    case 0: case 1:
                        $logger->debug("BirthTransitCalculation::packRecord - aspect is strong");
                        $aspect_strength = 0;
                        break;
                    case 2: case 3:
                        $logger->debug("BirthTransitCalculation::packRecord - aspect is medium");
                        $aspect_strength = 1;
                        break;
                    default:
                        $logger->debug("BirthTransitCalculation::packRecord - aspect is weak");
                        $aspect_strength = 2;
                        break;
                }
                break;
            case 180:
                /*
                 * Aspect is hardcoded to 9.0 at the moment
                 * - strong < 2.0
                 * - medium < 5.0
                 * - weak >= 5.0
                 */
                $aspect_type = 2;
                switch (intval(substr($this->m_aspect_orb, 1, 1))) {
                    case 0: case 1:
                        $logger->debug("BirthTransitCalculation::packRecord - aspect is strong");
                        $aspect_strength = 0;
                        break;
                    case 2: case 3: case 4:
                        $logger->debug("BirthTransitCalculation::packRecord - aspect is medium");
                        $aspect_strength = 1;
                        break;
                    default:
                        $logger->debug("BirthTransitCalculation::packRecord - aspect is weak");
                        $aspect_strength = 2;
                        break;
                }
                break;
            default:
                // error
                $aspect_type = 0;
                $aspect_strength = 0; /* not applicable */
        }

        /*
         * manage duplicate sections
         */
        $duplicate = 0;
        if ($this->m_chapter > 0 && $this->m_aspect_ref == 200) {
            if ((($this->m_planet + 1) == $this->m_chart_ruler) || (($this->m_planet + 1) == $this->m_chart_coruler)) {
                $duplicate = 1;
            }
        }
        $logger->debug("BirthTransitCalculation::packRecord - planet(" . ($this->m_planet + 1) . "), context(" . $this->m_record_context . "), duplicate(" . $duplicate . ")");

        $record = sprintf
                ($fmtstr, $this->m_chapter + 10001, $this->m_planet + 1000, $this->m_aspect_ref, $this->m_record_context, $this->m_record_context_value, $retrograde, /* retrograde */ $duplicate, $aspect_strength, /* aspect strength	{ 0 => strong,		1 => medium,	2 => weak	} */ $aspect_type, /* aspect type		{ 0 => conjoins,	1 => positive,	2 => negative	} */ $this->m_record_num++
        );
        $logger->debug("BirthTransitCalculation::packRecord - record = " .
                sprintf
                        ("%05d-%04d-%03d-%02d%02d-%d-%d-0000000000000000000000-%03d-%02d-%03d", $this->m_chapter + 10001, $this->m_planet + 1000, $this->m_aspect_ref, $this->m_record_context, $this->m_record_context_value, $retrograde, /* retrograde */ $duplicate, $aspect_strength, /* aspect strength	{ 0 => strong,		1 => medium,	2 => weak		}	 */ $aspect_type, /* aspect type		{ 0 => conjoins,	1 => positive,	2 => negative	}	 */ $this->m_record_num - 1 /* takes in to account the previous autoincrement							 */
                )
        );
        $this->m_analysis_context .= $record;
    }

    /* access */

    function getAnalysisContext() {
        global $logger;
        $logger->debug("analysis_context = $this->m_analysis_context");
        return $this->m_analysis_context;
    }

    function getChapter() {
        return substr($this->current(), 0, 5);
    }

    function getSection() {
        return $this->current();
    }

    /**
     * sortAspects
     */
    function sortAspects() {
        /* sort the aspects, removing duplicates along the way */
        global $logger;
        $logger->debug("BirthTransitCalculation::sortAspects");
        $sortedaspects = array();
        for ($aspect = 0; $aspect < count($this->m_aspect); $aspect++) {
            $sortedaspects[substr($this->m_aspect[$aspect], 0, 4) . substr($this->m_aspect[$aspect], 7, 4)] = $this->m_aspect[$aspect];
        }
        ksort($sortedaspects);

        unset($this->m_aspect);
        $this->m_aspect = array();
        reset($sortedaspects);
        while (list($key, $value) = each($sortedaspects)) {
            array_push(
                    $this->m_aspect, $value
            );
        }
    }

    /* iteration */

    function first() {
        $this->m_analysis_context_index = 0;
        return $this->current();
    }

    function current() {
        return substr($this->m_analysis_context, $this->m_analysis_context_index, 48);
    }

    function next() {
        $this->m_analysis_context_index += 48;
    }

    function BOF() {
        return ( $this->m_analysis_context_index == 0 );
    }

    function EOF() {
        return ( $this->m_analysis_context_index == strlen($this->m_analysis_context) );
    }

    /*
     * update page number
     */

    function updatePageNo($pno) {
        $this->m_analysis_context =
                sprintf("%s%03d%s", substr($this->m_analysis_context, 0, ($this->m_analysis_context_index + 18)), intval($pno), substr($this->m_analysis_context, ($this->m_analysis_context_index + 21), ( strlen($this->m_analysis_context) - ($this->m_analysis_context_index + 21))
                )
        );
    }
}
?>