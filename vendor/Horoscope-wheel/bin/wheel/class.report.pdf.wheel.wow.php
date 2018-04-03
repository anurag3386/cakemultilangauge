<?php
/*
 * File  : $id$
 * Author: Andy Gray <andy.gray@astro-consulting.co.uk>
 * 
 * Description
 * 
 * TODO
 * - remove customisations and replace with class extensions as originally
 *   intended and override default PDF settings within the functions
 * 
 * Modification History
 * $log$
 */

class PDF_Wheel_WOW extends PDF_Wheel {

	var $language;
	
	//function PDF_Wheel_WOW() {
	function __construct() {
		$this->PDF_Wheel();
		$this->planet_longitude = array();
		$this->planet_in_house = array();
		$this->planet_aspects = array();
	}

	function ApplyCustomisations($font='') {

		$this->margin_left			= 20;
		$this->margin_top			= 20;
		
		$this->text_font_family		= 'arial';
		$this->text_font_weight		= '';
		$this->text_font_size		= 10;
		
		$this->glyph_font_family	= ($font) ? $font : 'wows';
		$this->glyph_font_weight	= '';
		$this->glyph_font_size		= 10;
		
		/*
		 * Apply the Branding
		 */
		$this->zodiac_band_colour_map_enabled	= true;
		$this->zodiac_band_colour_map			= array(
			array( 0xFF, 0x00, 0x00 ),	/* Aries		*/
			array( 0xFF, 0x84, 0x00 ),	/* Taurus		*/
			array( 0xFF, 0xFF, 0x00 ),	/* Gemini		*/
			array( 0x84, 0xFF, 0x00 ),	/* Cancer		*/
			array( 0x00, 0xFF, 0x00 ),	/* Leo			*/
			array( 0x00, 0xFF, 0x84 ),	/* Virgo		*/
			array( 0x00, 0xFF, 0xFF ),	/* Libra		*/
			array( 0x00, 0x84, 0xFF ),	/* Scorpio		*/
			array( 0x00, 0x00, 0xFF ),	/* Sagittarius	*/
			array( 0x84, 0x00, 0xFF ),	/* Capricorn	*/
			array( 0xFF, 0x00, 0xFF ),	/* Aquarius		*/
			array( 0xFF, 0x00, 0x84 )	/* Pisces		*/
			);
		
		/*
		 * Fill in the central canvas
		 */
		$this->zodiac_band_canvas_enabled	= true;
		$this->zodiac_band_canvas_map		= array( 0xFF, 0xFF, 0xE7 );
		
		$this->zodiac_band_radius_major 	= $this->wheel_radius * 1.00;
		$this->zodiac_band_radius_minor		= $this->wheel_radius * 0.88;
		$this->zodiac_band_glyph_radius		= $this->zodiac_band_radius_major - (($this->zodiac_band_radius_major - $this->zodiac_band_radius_minor)/2);

		$this->zodiac_band_font_family		= $this->glyph_font_family;
		$this->zodiac_band_font_weight		= '';
		$this->zodiac_band_font_size		= 16;
		
		/*
		 * This is the symbol map for the WOWS font
		 * Any other font will require remapping.
		 */
		$this->symbol_map					= array(
			/* planets */
			184,155,
			190,177,161,
			165,123,
			134,135,136,
			/* nodes */
			168,130,
			/* signs */
			247,154,208,152,
			172,170,171,133,
			125,131,139,138,
			/* aspects */
			180,222,188,181,185,
			186,164, 222 /* quincunx = inverted semisextile */,175,
			/* angles */
			124,254,91,93,
			/* retrograde */
			182
			);

		$this->degree_tick_enabled			= true;
		$this->degree_tick_radius_base		= $this->wheel_radius * 0.88;
		$this->degree_tick_radius_minor		= $this->wheel_radius * 0.87;
		$this->degree_tick_radius_major		= $this->wheel_radius * 0.85;

		$this->planet_tick_enabled			= true;
		$this->planet_tick_radius_major		= $this->wheel_radius * 0.85;
		$this->planet_tick_radius_minor		= $this->wheel_radius * 0.83;
	
		$this->planet_glyph_radius_major	= $this->wheel_radius * 0.77;
		$this->planet_glyph_radius_minor	= $this->wheel_radius * 0.67;
		$this->planet_font_family			= $this->glyph_font_family;
		$this->planet_font_weight			= '';
		$this->planet_font_size				= 24;
	
		$this->angle_cusp_major				= $this->wheel_radius * 1.02;
		$this->angle_cusp_minor				= $this->wheel_radius * 0.94;
		$this->house_cusp_major				= $this->wheel_radius * 0.85;
		$this->house_cusp_minor				= $this->wheel_radius * 0.34;
		$this->house_number_radius_major	= $this->wheel_radius * 0.40;
		$this->house_number_radius_minor	= $this->wheel_radius * 0.34;
		$this->house_number_radius			= $this->wheel_radius * 0.37;
		$this->house_cusp_dashed			= false;
		$this->house_font_family			= $this->text_font_family;
		$this->house_font_weight			= '';
		$this->house_font_size				= 8;
	
		$this->aspect_line_radius			= $this->wheel_radius * 0.34;

		$this->table_user_info_origin_x			= $this->margin_left;
		$this->table_user_info_origin_y			= $this->margin_top;
		$this->table_user_info_width			= 180;
		$this->table_user_info_height			= 21;
		$this->table_user_info_font_family		= $this->text_font_family;
		$this->table_user_info_font_weight		= '';
		$this->table_user_info_font_size		= 12;

		$this->table_symbol_info_origin_x		= $this->margin_left;
		$this->table_symbol_info_origin_y		= $this->margin_top + 27;
		$this->table_symbol_info_width			= 27;
		$this->table_symbol_info_height			= 220;
		$this->table_symbol_info_font_family	= $this->glyph_font_family;
		$this->table_symbol_info_font_weight	= '';
		$this->table_symbol_info_font_size		= 7.5;

		$this->table_house_info_origin_x		= $this->margin_left + 30;
		$this->table_house_info_origin_y		= $this->margin_top + 177;
		$this->table_house_info_width			= 22;
		$this->table_house_info_height			= 70;
		$this->table_house_info_font_family		= $this->glyph_font_family;
		$this->table_house_info_font_weight		= '';
		$this->table_house_info_font_size		= 7.5;

		$this->table_planet_info_origin_x		= $this->margin_left + 140;
		$this->table_planet_info_origin_y		= $this->margin_top + 177;
		$this->table_planet_info_width			= 37;
		$this->table_planet_info_height			= 70;
		$this->table_planet_info_font_family	= $this->glyph_font_family;
		$this->table_planet_info_font_weight	= '';
		$this->table_planet_info_font_size		= 7.5;

		$this->table_aspect_info_origin_x		= $this->margin_left + 60;
		$this->table_aspect_info_origin_y		= $this->margin_top + 177;
		$this->table_aspect_info_width			= 70;
		$this->table_aspect_info_height			= 70;
		$this->table_aspect_info_font_family	= $this->glyph_font_family;
		$this->table_aspect_info_font_weight	= '';
		$this->table_aspect_info_font_size		= 7.5;
		$this->table_aspect_info_glyph_size		= 7.5;
		$this->table_aspect_info_orb_size		= 4;

	}
	
	function generateCoverPage() {
		$this->AddPage();
//		$this->Image(RESOURCE_PATH.'images/ppt_glass_design.jpg',-1,-1);
		$this->wheel_radius		= 50;
		$this->wheel_origin_x	= 210 - 10 - $this->wheel_radius;
		$this->wheel_origin_y	= 297 - 10 - $this->wheel_radius;
		$this->ApplyCustomisations();
		$this->drawWheelZodiac();
		$this->drawWheelHouses();
		$this->drawWheelPlanets();
	}
	
	function generateChartWheelPage() {
		$this->AddPage();
		$this->wheel_radius		= 70;
		$this->wheel_origin_x	= $this->margin_left + 125;	// 110;
		$this->wheel_origin_y	= $this->margin_top + 117;	// 97;
		$this->ApplyCustomisations();
		$this->drawWheelZodiac();
		$this->drawWheelDegrees();
		$this->drawWheelHouses();
		$this->drawWheelPlanets();
		$this->drawWheelAspects();
		$this->drawTableUserInfo( $this->language );
		$this->drawTableSymbolInfo( $this->language );
		$this->drawTableHouseInfo();
		$this->drawTablePlanetInfo();
		$this->drawTableAspectInfo();
	}

	function generateChartWheelXML($wheelData, $NatalWheelFileName) {
		//echo ROOTPATH.'/webroot/css/xml.css'; die;
		//echo $this->language; die;
		$this->wheel_radius		= 50;
		$this->wheel_origin_x	= 210 - 10 - $this->wheel_radius;
		$this->wheel_origin_y	= 297 - 10 - $this->wheel_radius;
		if ($this->language == 'en') {
            $wheel_info_symbols[$this->language] = array (
                // planets
                'Sun', 'Moon', 'Mercury', 'Venus',
                'Mars', 'Jupiter', 'Saturn', 'Uranus',
                'Neptune', 'Pluto', 'N.Node', 'S.Node',
                //'Ascendant', 'MC', 'IC', 'Descendant',
                // signs
                'Aries', 'Taurus', 'Gemini', 'Cancer',
                'Leo', 'Virgo', 'Libra', 'Scorpio',
                'Sagittarius', 'Capricorn', 'Aquarius', 'Pisces',
                // aspects
                'Conjunction', 'Semisextile', 'Semisquare',
                'Sextile', 'Square', 'Trine', 'Sesquisquare',
                'Quincunx', 'Opposition',
                // angles
                'Ascendant', 'Descendant', 'Medium Coeli',
                'Immum Coeli', 'Retrograde'
                );
        }
        if ($this->language == 'dk') {
            $wheel_info_symbols[$this->language] = array(
                // planets
                "Sol",      utf8_decode("Måne"),
                "Merkur",   "Venus",    "Mars",
                "Jupiter",  "Saturn",
                "Uranus",   "Neptun",   "Pluto",
                utf8_decode("N. Måneknude"),
                utf8_decode("S. Måneknude"),
                // signs
                utf8_decode("Vædder"),  "Tyr",      "Tvilling",     "Krebs",
                utf8_decode("Løve"),        "Jomfru",   utf8_decode("Vægt"),            "Skorpion",
                "Skytte",   "Stenbuk",  utf8_decode("Vandbærer"),   "Fiskene",
                // aspects
                "konjunktion",  "halvsekstil",      "halvkvadrat",  "sekstil",  "kvadrat",
                "trigon",       "seskvikvadrat",    "kvinkunx",     "opposition",
                // angles
                'Ascendant','Descendant','Medium Coeli','Immum Coeli',
                'Retrograd'
            );
        }

		$this->ApplyCustomisations('arial');
		//global $wheel_info_symbols;
		global $symbol_map;

		//$xml = new SimpleXMLElement('<xml version="1.0" encoding="UTF-8"/>'); // object declaraction
		error_reporting(0);
		$xml = new SimpleXMLElement('<xml version="1.0" encoding="UTF-8"/>'); // object declaraction
		/*<?xml-stylesheet type="text/css" href="cd_catalog.css"?>*/
		$csspath = ROOTPATH.'/webroot/css/xml.css';
		$xml->addAttribute('<?xml-stylesheet type=”text/css href=”.$csspath.”?>');
        $track = $xml->addChild('HoroscopeWheel');

        

        $track1 = $track->addChild('user_info');
        $track1->addChild('name', iconv("ISO-8859-1","UTF-8",$wheelData->table_user_info_fname));
        $node = $track1->addChild('birth_info');
        $node->addChild('day', iconv("ISO-8859-1","UTF-8",$wheelData->table_user_info_birth_weekday));
        $node->addChild('dateTime', "$wheelData->table_user_info_birth_date");
        $node->addChild('place', iconv("ISO-8859-1","UTF-8",$wheelData->table_user_info_birth_place));
        $node = $track1->addChild('house_system_info');
        $node->addChild('house', iconv("ISO-8859-1","UTF-8",$wheelData->table_user_info_house_system));
        $node->addChild('timezone', $wheelData->table_user_info_birth_timezone);
        $node->addChild('country', iconv("ISO-8859-1","UTF-8",$wheelData->table_user_info_birth_state));
        $node = $track1->addChild('orb_system_info');
        $node->addChild('orbit', iconv("ISO-8859-1","UTF-8",$wheelData->table_user_info_orb_system));
        $node->addChild('summertime', "$wheelData->table_user_info_birth_summertime");
        $node->addChild('coordinate', "$wheelData->table_user_info_birth_coords");


        //planets
        $node1 = $track->addChild('planets');
        for($i = 0; $i < 38; $i++) {
        	$node = $node1->addChild('planet');
        	/*$node->addChild('name', iconv("ISO-8859-1","UTF-8",$wheel_info_symbols[$this->language][$i]));
        	$node->addChild('icon', iconv("ISO-8859-1","UTF-8",chr($this->symbol_map[$i])));*/
        	$node->addChild('name', iconv("ISO-8859-1","UTF-8",$wheel_info_symbols[$this->language][$i]));
        	$node->addChild('icon', iconv("ISO-8859-1","UTF-8",chr($this->symbol_map[$i])));
        }

        //TableHouseInfo
        $node1 = $track->addChild('houses');
        for($i = 0; $i < 12; $i++) {
        	$node = $node1->addChild('house');
        	$specialCase = array(0, 3, 6,9);
        	$specialCaseValue = array(0 => 33, 3 => 36, 6 => 34, 9 => 35);
        	if(in_array($i, $specialCase)){
        		$node->addChild('cusp', iconv("ISO-8859-1","UTF-8",chr($this->symbol_map[$specialCaseValue[$i]])));
        	} else {
        		$node->addChild('cusp', ($i+1));
        	}
            $degrees = fmod($this->house_cusp_longitude[$i],30.0);
            $minutes = $degrees - intval($degrees);
            $minutes = $minutes * 0.6;
            $minutes = intval($minutes * 100);
            $degree = sprintf("%02d,%02d", $degrees, $minutes);
        	$node->addChild('cuspDegrees', $degree);
        	$sign = intval( $this->house_cusp_longitude[$i] / 30.0 );
        	$node->addChild('cuspSigns', iconv("ISO-8859-1","UTF-8",chr($this->symbol_map[$sign+12])));
        }

        //TablePlanetInfo
        $node1 = $track->addChild('planets_info');
        for($i = 0; $i < 12; $i++) {
        	$node = $node1->addChild('planet');
        	$node->addChild('planetGlyph', iconv("ISO-8859-1","UTF-8",chr($this->symbol_map[$i])));
        	$degrees = fmod($this->planet_longitude[$i],30.0);
        	$minutes = $degrees - intval($degrees);
            $minutes = $minutes * 0.6;
            $minutes = intval($minutes * 100);
            $degree = sprintf("%02d,%02d", $degrees, $minutes);
        	$node->addChild('longitude', $degree);
        	$sign = (int)($this->planet_longitude[$i] / 30.0);
        	$node->addChild('sign', iconv("ISO-8859-1","UTF-8",chr($this->symbol_map[$sign+12])));
        	$node->addChild('retrograde', chr(182));
        	$node->addChild('occupancy', $this->planet_in_house[$i]);
        }
		
		//TableAspectInfo
        $node1 = $track->addChild('aspects');
        $hr = $node1->addChild('aspect_horizontal_header');
        for($i = 0; $i < 15; $i++) {
        	// horizontal lines
        	if( $i > 0 && $i <= 12 ) {
        		$hr->addChild('title', iconv("ISO-8859-1","UTF-8",chr($this->symbol_map[$i-1])));
        	} elseif ($i == 13) {
        		$hr->addChild('title', iconv("ISO-8859-1","UTF-8",chr($this->symbol_map[$i+20])));
        	} elseif ($i == 14) {
        		$hr->addChild('title', iconv("ISO-8859-1","UTF-8",chr($this->symbol_map[$i+21])));
        	} else {
        	}
        }

        $vr = $node1->addChild('aspect_vertical_header');
        for($i = 0; $i < 15; $i++) {
        	// vertical lines
        	if( $i > 0 && $i <= 12 ) {
        		$vr->addChild('title', iconv("ISO-8859-1","UTF-8",chr($this->symbol_map[$i-1])));
        	} elseif ($i == 13) {
        		$vr->addChild('title', iconv("ISO-8859-1","UTF-8",chr($this->symbol_map[$i+20])));
        	} elseif ($i == 14) {
        		$vr->addChild('title', iconv("ISO-8859-1","UTF-8",chr($this->symbol_map[$i+21])));
        	} else {
        	}
        }

        $aspo = $node1->addChild('aspect_positions');
    	for($i=0; $i<count($this->planet_aspects); $i++) {
            $p1 = intval( substr($this->planet_aspects[$i],0,4)-1000 );
            $as = intval( substr($this->planet_aspects[$i],4,3) );
            $p2 = intval( substr($this->planet_aspects[$i],7,4)-1000 );
            $ao = trim( substr($this->planet_aspects[$i],12,5) );

            switch($as) {
                case 0:
                    $aspect_glyph = 24;
                    break;
                case 30:
                    $aspect_glyph = 25;
                    break;
                case 45:
                    $aspect_glyph = 26;
                    break;
                case 60:
                    $aspect_glyph = 27;
                    break;
                case 90:
                    $aspect_glyph = 28;
                    break;
                case 120:
                    $aspect_glyph = 29;
                    break;
                case 135:
                    $aspect_glyph = 30;
                    break;
                case 150:
                    $aspect_glyph = 31;
                    break;
                case 180:
                    $aspect_glyph = 32;
                    break;
            }
            if($p1 > $p2) {
            	$x = $this->table_aspect_info_origin_x + (($p1+1) * ($this->table_aspect_info_width / 15));
            	$y = $this->table_aspect_info_origin_y + (($p2+1) * ($this->table_aspect_info_height / 15));
                
            } else {
            	$x = $this->table_aspect_info_origin_x + (($p2+1) * ($this->table_aspect_info_width / 15));
            	$y = $this->table_aspect_info_origin_y + (($p1+1) * ($this->table_aspect_info_height / 15));
                
            }
            $node = $aspo->addChild('aspect_position');
            $node->addChild('x', $p2);
            $node->addChild('y', $p1);
            $node->addChild('icon', iconv("ISO-8859-1","UTF-8",chr($this->symbol_map[$aspect_glyph])));
            $node->addChild('text', $ao);
    	}

        //Horoscope wheel circle
        $node = $track->addChild('wheel');
        $node1 = $node->addChild('drawWheelZodiac');
        $this->drawWheelZodiacXml($node1);

        $node1 = $node->addChild('drawWheelDegrees');
        $degRad = (double)((pi()*2.0)/360.0);
        for( $cusp = 0; $cusp < 360; $cusp++ ) {
            $theta = ($degRad * fmod((180.0 - $cusp + $this->wheel_offset),360.0)); // degrees
            if( ($cusp % 5) == 0 ) {
                $x1 = $this->wheel_origin_x + ($this->degree_tick_radius_major * cos( $theta ));
                $y1 = $this->wheel_origin_y + ($this->degree_tick_radius_major * sin( $theta ));
            } else {
                $x1 = $this->wheel_origin_x + ($this->degree_tick_radius_minor * cos( $theta ));
                $y1 = $this->wheel_origin_y + ($this->degree_tick_radius_minor * sin( $theta ));
            }
            $x2 = $this->wheel_origin_x + ($this->degree_tick_radius_base * cos( $theta ));
            $y2 = $this->wheel_origin_y + ($this->degree_tick_radius_base * sin( $theta ));
            $cnode1 = $node1->addChild('drawWheelDegreesValue'.$cusp.'_x1', $x1);
            $cnode1 = $node1->addChild('drawWheelDegreesValue'.$cusp.'_x2', $x2);
            $cnode1 = $node1->addChild('drawWheelDegreesValue'.$cusp.'_y1', $y1);
            $cnode1 = $node1->addChild('drawWheelDegreesValue'.$cusp.'_y2', $y2);
        }
	       
        $node1 = $node->addChild('drawWheelHouses');
        $this->drawWheelHousesXml($node1);

        $node1 = $node->addChild('drawWheelPlanets');
        $this->drawWheelPlanetsXml($node1);

        $node1 = $node->addChild('drawWheelAspects');
        $this->drawWheelAspectsXml($node1);
        error_reporting(0);
        //$xml->asXML(sprintf('%s/wheel.xml', ROOTPATH));
        $xml->asXML($NatalWheelFileName);
	}

}
?>
