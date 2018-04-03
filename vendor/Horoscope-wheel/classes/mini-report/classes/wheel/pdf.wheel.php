<?php
/*
 * File  : class.report.pdf.wheel.php
 * Author: Andy Gray <andy.gray@astro-consulting.co.uk>
 * 
 * Description
 * This class contains the generic components required in order to build a
 * PDF report page. It is expected that the class is extended to provide a
 * specific report style.
 * 
 * Todo List
 * User information table
 * - Name needs to be managed in the user details table at the top of the page (medium, coding)
 * Wheel
 * - Glyph conjunction spacing (high, design + coding)
 * - Glyph concentricity (radius from centre) (medium, configuration)
 * Y Glyph colour (low, coding)
 * Y Glyph size needs adjusting by 33% for N.Node and S.Node
 * - House numbering background colour should be grey (low, coding)
 * - Arrowheads on angles (medium, coding)
 * - Adjust sign colours further (low, configuration)
 * Glyph information table
 * - Glyph font size needs to be increased fractionally (low, configuration)
 * - Text font is serif, should be sans serif (low, configuration)
 * - The quincunx glyph is missing from the character set, may be able to poach from another or invert the semi-sextile (medium, coding)
 * House cusp details table
 * Y Degree/minutes separator is �.�, should be �,� (low, coding)
 * - Sign column not centred (low, coding)
 * Aspects table
 * - Table incomplete (perhaps) (medium, coding)
 * Y Orbs to replace placeholder xx.xx (high, coding)
 * Planetary details table
 * Y Degree/minutes separator is �.�, should be �,� (low, coding)
 * - Retrograde column suspect (medium, coding)
 *
 * Modification History
 * - initial spike
 * 
 */

class PDF_Wheel extends PDF_Bookmark {

	var $wheel_origin_x;
	var $wheel_origin_y;
	var $wheel_radius;
	var $wheel_offset;
	
	var $symbol_map;

	var $planet_longitude;
	var $planet_in_house;
	var $planet_aspects;
	var $house_cusp_longitude;
	var $house_tenants;
	// aspects

	var $zodiac_band_colour_map_enabled = true;
	var $zodiac_colour_map;	// TODO change to zodiac_band_colour_map
	var $zodiac_band_canvas_enabled = true;
	var $zodiac_band_canvas_map;
	var $zodiac_band_radius_major;
	var $zodiac_band_radius_minor;
	var $zodiac_band_glyph_radius;
	var $zodiac_band_offset;
	var $zodiac_band_font_family;
	var $zodiac_band_font_weight;
	var $zodiac_band_font_size;
	
	var $degree_tick_enabled = true;
	var $degree_tick_radius_base;
	var $degree_tick_radius_minor;
	var $degree_tick_radius_major;
	
	var $planet_tick_enabled = true;
	var $planet_tick_radius_major;
	var $planet_tick_radius_minor;
	var $planet_tick_colour;
	
	var $planet_glyph_radius;
	var $planet_glyph_colour;
	
	var $angle_cusp_major;
	var $angle_cusp_minor;
	var $house_cusp_major;
	var $house_cusp_minor;
	var $house_cusp_dashed = false;
#	var $house_cusp_longitude;
	
	var $aspect_line_radius;
	
	var $table_user_info_fname;
	var $table_user_info_lname;
	var $table_user_info_birth_weekday;
	var $table_user_info_birth_date;
	var $table_user_info_birth_place;
	var $table_user_info_birth_state;
	var $table_user_info_birth_coords;
	var $table_user_info_birth_timezone;
	var $table_user_info_birth_summertime;
	var $table_user_info_house_system;
	var $table_user_info_orb_system;
	

	function PDF_Wheel() {
		$this->planet_longitude = array();
		$this->planet_in_house = array();
		$this->planet_aspects = array();
	}
	
	/**
	 * 
	 */
	function drawWheelZodiac() {

		$degRad = (double)((pi()*2.0)/360.0);

		/*
		 * If the colour map is enabled then we draw a segmented map using
		 * the zodiac colour map colour definitions.
		 */
		if( $this->zodiac_band_colour_map_enabled === true ) {

			/*
			 * draw the segmented map
			 */
			for( $cusp = 0; $cusp < 12; $cusp++ ) {
	
				$angle = ($cusp * 30.0);
	
				$this->SetFillColor(
		  			$this->zodiac_band_colour_map[$cusp][0],
		  			$this->zodiac_band_colour_map[$cusp][1],
		  			$this->zodiac_band_colour_map[$cusp][2]
		  			);
	
				/* treat this as a regular pie chart */
				$this->Sector(
					/* centre */	$this->wheel_origin_x,
									$this->wheel_origin_y,
					/* radius */	$this->zodiac_band_radius_major,
					/* start */		$angle,
					/* end */		fmod(($angle+30.0),360.0),
					/* style */		'F',
					/* direction */	false,
					/* origin */ 	fmod((180.0 - $this->wheel_offset), 360.0)
					);
			}
			
			$this->SetFillColor( 0xFF, 0xFF, 0xFF );
			$this->Circle(
				$this->wheel_origin_x,
				$this->wheel_origin_y,
				$this->zodiac_band_radius_major,
				'D');

			/*
			 * fill in the centre if required
			 */
			if( $this->zodiac_band_canvas_enabled === true ) {

				$this->SetFillColor(
		  			$this->zodiac_band_canvas_map[0],
		  			$this->zodiac_band_canvas_map[1],
		  			$this->zodiac_band_canvas_map[2]
					);

				$this->Circle(
					$this->wheel_origin_x,
					$this->wheel_origin_y,
					$this->zodiac_band_radius_minor,
					'DF');
			}
		}
		
		/*
		 * add the sign cusps
		 */
		for( $cusp = 0; $cusp < 12; $cusp++ ) {
			$angle = fmod((180.0 - (($cusp+1) * 30.0) + $this->wheel_offset), 360.0);
			$this->SetFillColor(255,255,255);
			$theta = ($degRad * $angle);
			$x1 = $this->wheel_origin_x + ($this->zodiac_band_radius_major * cos( $theta ));
			$y1 = $this->wheel_origin_y + ($this->zodiac_band_radius_major * sin( $theta ));
			$x2 = $this->wheel_origin_x + ($this->zodiac_band_radius_minor * cos( $theta ));
			$y2 = $this->wheel_origin_y + ($this->zodiac_band_radius_minor * sin( $theta ));
			$this->line($x1,$y1,$x2,$y2);
		}

	    $this->SetFont(
	    	$this->zodiac_band_font_family,
	    	$this->zodiac_band_font_weight,
	    	$this->zodiac_band_font_size
	    	);

		for( $cusp = 0; $cusp < 12; $cusp++ ) {	/* C_MAX_HOUSES */

			$angle = fmod((180.0 - (($cusp+1) * 30.0) + $this->wheel_offset), 360.0);
			$angle = fmod(($angle + 15.0), 360.0);
      		$theta = ($degRad * $angle);
			$glyph_width = $this->GetStringWidth( chr($this->symbol_map[$cusp+12]) );
			$this->SetXY(
				$this->wheel_origin_x + ($this->zodiac_band_glyph_radius * cos( $theta )) - ($glyph_width/2),
				$this->wheel_origin_y + ($this->zodiac_band_glyph_radius * sin( $theta ))
				);
			$this->Cell(
				$glyph_width,
				0,
				chr($this->symbol_map[$cusp+12]),	/* C_SYMBOL_MAP_HOUSE_OFFSET */
				0,0,'C',0
				);
    	}

	}

	/**
	 * 
	 */
	function drawWheelDegrees() {

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
			$this->Line($x1,$y1,$x2,$y2);
		}
	}

	/**
	 * drawWheelHouses
	 * 
	 * This function draws the house cusps, the angles and applies the arrow
	 * heads to the ascendant and MC angles.
	 */
	function drawWheelHouses() {

		$degRad = (double)((pi()*2.0)/360.0);

		// the house background is a creamy colour
		//$this->SetFillColor(0xFF,0xFF,0xE7);
		//$this->Circle($this->wheel_origin_x,$this->wheel_origin_y,$this->house_cusp_major,'F');

	    $this->SetFont(
	    	$this->house_font_family,
	    	$this->house_font_weight,
	    	$this->house_font_size
	    	);
			
		for( $cusp = 0; $cusp < 12; $cusp++ ) {
			$angle = fmod( (180 - $this->house_cusp_longitude[$cusp] + $this->wheel_offset), 360.0 );
			$theta = ($degRad * ($angle));
			$x1 = $this->wheel_origin_x + ($this->house_cusp_minor * cos( $theta ));
			$y1 = $this->wheel_origin_y + ($this->house_cusp_minor * sin( $theta ));
			if( $cusp == 0 || $cusp == 3 || $cusp == 6 || $cusp == 9 ) {
				$x2 = $this->wheel_origin_x + ($this->angle_cusp_major * cos( $theta ));
				$y2 = $this->wheel_origin_y + ($this->angle_cusp_major * sin( $theta ));
			} else {
				$x2 = $this->wheel_origin_x + ($this->house_cusp_major * cos( $theta ));
				$y2 = $this->wheel_origin_y + ($this->house_cusp_major * sin( $theta ));
			}
			
			/*
			 * If this is the Asc or MC then we add a rather tasteful arrow
			 * head to the line
			 */
			if( $cusp == 0 || $cusp == 3 || $cusp == 6 || $cusp == 9 ) {
				$this->SetLineWidth(0.5);	// apply thickness
			}
			$this->Line($x1,$y1,$x2,$y2);
			$this->SetLineWidth(0.2);		// restore default thickness
			
			/*
			 * If this is the Asc or MC then we add a rather tasteful arrow
			 * head to the line
			 */
			if( $cusp == 0 || $cusp == 9 ) {
				// draw arrow head
			}

			/*
			 * The house number sits in the centre of the house so I need to
			 * work out the bounding cusps and then bisect the interval.
			 */
#			if( $cusp > 0 ) {
#				$precusp = $this->house_cusp_longitude[$cusp-1] + 360.0;
#				$postcusp = $this->house_cusp_longitude[$cusp] + 360.0;
#			} else {
#				$precusp = $this->house_cusp_longitude[11] + 360.0;
#				$postcusp = $this->house_cusp_longitude[0] + 360.0;
#			}
			if( $cusp < 11 ) {
				$precusp = $this->house_cusp_longitude[$cusp] + 360.0;
				$postcusp = $this->house_cusp_longitude[$cusp+1] + 360.0;
			}
			if( $cusp == 11 ) {
				$precusp = $this->house_cusp_longitude[$cusp] + 360.0;
				$postcusp = $this->house_cusp_longitude[0] + 360.0;
			}
			// add adjustment for when we cross the aries point
			if( $postcusp < $precusp ) {
				$postcusp += 360.0;
			}
			$cusp_interval = $postcusp - $precusp;
			$angle = fmod((180.0 - ( $precusp + ($cusp_interval / 2)) + $this->wheel_offset), 360.0);
			$theta = ($degRad * ($angle));

			$width = $this->GetStringWidth( ($cusp+1) );
			$this->SetXY(
				$this->wheel_origin_x + ($this->house_number_radius * cos( $theta )) - ($width/2),
				$this->wheel_origin_y + ($this->house_number_radius * sin( $theta ))
				);
			$this->Cell($width,0,($cusp+1),0,0,'C',0);
		}

		$this->SetFillColor(255,255,255);
		$this->Circle($this->wheel_origin_x,$this->wheel_origin_y,$this->house_cusp_major,'D');
		$this->Circle($this->wheel_origin_x,$this->wheel_origin_y,$this->house_cusp_minor,'D');
		$this->Circle($this->wheel_origin_x,$this->wheel_origin_y,$this->house_number_radius_major,'D');
		$this->Circle($this->wheel_origin_x,$this->wheel_origin_y,$this->house_number_radius_minor,'D');
}

	/**
	 * 
	 * Managing Stellia
	 * - work out how many planets are in a given house
	 *   - if it is the only one then place it directly
	 *   - be prepared to drop the S.Node
	 * - sort the planets into ascending order (watch for the aries point)
	 * - work out the distance between the planets
	 *   - if the distance > width of the glyph then place directly
	 *   - if the distance = or is close to = then adjust the gap
	 * - work out the width of the house
	 */
	function drawWheelPlanets() {

		$degRad = (double)((pi()*2.0)/360.0);

	    $this->SetFont(
	    	$this->planet_font_family,
	    	$this->planet_font_weight,
	    	$this->planet_font_size
	    	);

# degrees of separation = 5
# sort planets into ascending longitude
$planet_spacing = array();
for( $i = 0; $i < 12; $i++ ) {
	$planet_spacing[$i] = $this->planet_longitude[$i];
}
asort($planet_spacing);	/* sort by longitude -> array[planet] = longitude */
$planet_mapping = array();
$space_mapping = array();
$radius_mapping = array();
$i = 0;
foreach($planet_spacing as $planet => $longitude) {
	$planet_mapping[$planet] = $i;		// [0..11] <- actual planet
	$space_mapping[$i] = $longitude;	// [0..11] -> actual/adjusted longitude
	$radius_mapping[$i] = $this->planet_glyph_radius_major;
	$i++;
}
$distance_separator = 6;
//
// thought
// instead of the wrap round consider duplicating the 1st 5 entries at the end 
// and adjusting for the aries point
//
// for( $i = 0; $i < (12+5); $++ ) {
for( $i = 0; $i < 11; $i++ ) {
	if( $i == 0 ) {
		// special case, need to consider wrap round
		$interval = ($space_mapping[0] - $space_mapping[11]);
	} else {
		/* single planet lookahead */
		$interval = ($space_mapping[$i+1] - $space_mapping[$i]);
		if( $interval < $distance_separator ) {
			/* look for 3 planet stellium */
			$temp = $interval;
			$interval = ($space_mapping[$i+2] - $space_mapping[$i]);
			if( $interval < $distance_separator ) {
				/* look for 4 planet stellium */
				$temp = $interval;
				$interval = ($space_mapping[$i+3] - $space_mapping[$i]);
				if( $interval < $distance_separator ) {
					/* look for 5 planet stellium */
					$temp = $interval;
					$interval = ($space_mapping[$i+4] - $space_mapping[$i]);
					if( $interval < $distance_separator ) {
						/*
						 * this is about as deep as we will be getting
						 * p1 moved to lesser degree and left in outer radius
						 * p2 moved to lesser degree and moved to inner radius
						 * p3 moved to midpoint and left in outer radius
						 * p4 moved to greater degree and moved to inner radius
						 * p5 moved to greater degree and left in outer radius
						 */
						$temp = $interval;
						$interval = ($space_mapping[$i+5] - $space_mapping[$i]);
						if( $interval < $distance_separator ) {
							/*
							 * 6 planets in conjunction
							 * it really is getting muddy here as we lose sight of the order of the planets
							 *
							 * Test data = 5-FEB-1962 @ 00:00
							 */
							$space_mapping[$i] = (($space_mapping[$i] + ($interval/2.0)) - ($distance_separator/2));
							$radius_mapping[$i] = $this->planet_glyph_radius_major;
							$space_mapping[$i+1] = (($space_mapping[$i+1] + ($interval/2.0)) - ($distance_separator/2));
							$radius_mapping[$i+1] = $this->planet_glyph_radius_minor;
							$space_mapping[$i+2] = (($space_mapping[$i+2] - ($interval/2.0)) + ($distance_separator/2));
							$radius_mapping[$i+2] = $this->wheel_radius * 0.57;
							$space_mapping[$i+3] = (($space_mapping[$i+3] - ($interval/2.0)) + ($distance_separator/2));
							$radius_mapping[$i+3] = $this->planet_glyph_radius_minor;
							$space_mapping[$i+4] = (($space_mapping[$i+4] - ($interval/2.0)) + ($distance_separator/2));
							$radius_mapping[$i+4] = $this->planet_glyph_radius_major;
							$space_mapping[$i+5] = (($space_mapping[$i+5] - ($interval/2.0)) + ($distance_separator/2));
							$radius_mapping[$i+5] = $this->wheel_radius * 0.57;
							$i += 5;
						} else {
							/*
							 * 5 planets in conjunction
							 * p1 moved to lesser degree and left in outer radius
							 * p2 moved to lesser degree and moved to inner radius
							 * p3 moved to midpoint and left in outer radius
							 * p4 moved to greater degree and moved to inner radius
							 * p5 moved to greater degree and left in outer radius
							 */
							$interval = $temp;
							$space_mapping[$i] = (($space_mapping[$i] + ($interval/2.0)) - ($distance_separator/2));
							$radius_mapping[$i] = $this->planet_glyph_radius_major;
							$space_mapping[$i+1] = (($space_mapping[$i+1] + ($interval/2.0)) - ($distance_separator/2));
							$radius_mapping[$i+1] = $this->planet_glyph_radius_minor;
							$space_mapping[$i+2] = (($space_mapping[$i+2] - ($interval/2.0)) + ($distance_separator/2));
							$radius_mapping[$i+2] = $this->planet_glyph_radius_major;
							$space_mapping[$i+3] = (($space_mapping[$i+3] - ($interval/2.0)) + ($distance_separator/2));
							$radius_mapping[$i+3] = $this->planet_glyph_radius_minor;
							$space_mapping[$i+4] = (($space_mapping[$i+4] - ($interval/2.0)) + ($distance_separator/2));
							$radius_mapping[$i+4] = $this->planet_glyph_radius_major;
							$i += 4;
						}
					} else {
						/*
						 * 4 planets in conjunction
						 * p1 moved to lesser degree and left in outer radius
						 * p2 moved to lesser degree and moved to inner radius
						 * p3 moved to greater degree and left in outer radius
						 * p4 moved to greater degree and moved to inner radius
						 *
						 * Test data: 6-FEB-1969 @ 18:35
						 */
						$interval = $temp;
						$space_mapping[$i] = (($space_mapping[$i] + ($interval/2.0)) - ($distance_separator/2));
						$radius_mapping[$i] = $this->planet_glyph_radius_major;
						$space_mapping[$i+1] = (($space_mapping[$i+1] + ($interval/2.0)) - ($distance_separator/2));
						$radius_mapping[$i+1] = $this->planet_glyph_radius_minor;
						$space_mapping[$i+2] = (($space_mapping[$i+2] - ($interval/2.0)) + ($distance_separator/2));
						$radius_mapping[$i+2] = $this->planet_glyph_radius_major;
						$space_mapping[$i+3] = (($space_mapping[$i+3] - ($interval/2.0)) + ($distance_separator/2));
						$radius_mapping[$i+3] = $this->planet_glyph_radius_minor;
						$i += 3;
					}
				} else {
					/*
					 * 3 planets in conjunction
					 * p1 moved to lesser degree and left in outer radius
					 * p2 moved to p1/p3 midpoint and moved to inner radius
					 * p3 moved to greater degree and left in outer radius
				 	 * skip 2 planets in control loop
					 *
					 * Test data: 2-FEB-1960 @ 14:35
					 */
					$interval = $temp;
					$space_mapping[$i] = (($space_mapping[$i] + ($interval/2.0)) - ($distance_separator/2));
					$radius_mapping[$i] = $this->planet_glyph_radius_major;
					$space_mapping[$i+1] = ($space_mapping[$i+1] + ($interval/2.0));
					$radius_mapping[$i+1] = $this->planet_glyph_radius_minor;
					$space_mapping[$i+2] = (($space_mapping[$i+2] - ($interval/2.0)) + ($distance_separator/2));
					$radius_mapping[$i+2] = $this->planet_glyph_radius_major;
					$i += 2;
				}
			} else {
				/*
				 * 2 planets in conjunction
				 * p1 moved to lesser degree and left in outer radius
				 * p2 moved to greater degree and moved to inner radius
				 * skip 1 planet in control loop
				 *
				 * Test data: 3-MAR-1956 @ 00:47
				 * Test data: 6-FEB-1962 @ 18:35 bug in the venus/node conjunction - not recognised
				 */
				$interval = $temp;
				$space_mapping[$i] = (($space_mapping[$i] + ($interval/2.0)) - ($distance_separator/2));
				$radius_mapping[$i] = $this->planet_glyph_radius_major;
				$space_mapping[$i+1] = (($space_mapping[$i+1] - ($interval/2.0)) + ($distance_separator/2));
				$radius_mapping[$i+1] = $this->planet_glyph_radius_minor;
				$i++;
			}
		} else {
			/* nothing to do */
		}
	}	
}

		for($i = 0; $i < 12; $i++) {

			/* reduce the glyph size for N.Node and S.Node */
			if( $i == 10 /* N.Node */ || $i == 11 /* S.Node */ ) {
				$this->SetFont(
					$this->planet_font_family,
					$this->planet_font_weight,
					$this->planet_font_size * 0.6
					);
			}

			// draw the position
			$angle = fmod( (180 - $this->planet_longitude[$i] + $this->wheel_offset), 360.0 );
			$theta = ($degRad * $angle);
			$x1 = $this->wheel_origin_x + ($this->planet_tick_radius_major * cos( $theta ));
			$y1 = $this->wheel_origin_y + ($this->planet_tick_radius_major * sin( $theta ));
			$x2 = $this->wheel_origin_x + ($this->planet_tick_radius_minor * cos( $theta ));
			$y2 = $this->wheel_origin_y + ($this->planet_tick_radius_minor * sin( $theta ));
			/*
			 * The lines are always placed in their absolute position regardless of glyph positioning
			 * TODO add configuration for the colour of the line
			 */
			$this->Line($x1,$y1,$x2,$y2);

			// draw the glyph
			$glyph_width = $this->GetStringWidth( chr($this->symbol_map[$i]));

			$angle = fmod( (180 - $space_mapping[ $planet_mapping[$i] ] + $this->wheel_offset), 360.0 );
			$theta = ($degRad * $angle);
			$x1 = $this->wheel_origin_x + ($this->planet_tick_radius_major * cos( $theta ));
			$y1 = $this->wheel_origin_y + ($this->planet_tick_radius_major * sin( $theta ));
			$x2 = $this->wheel_origin_x + ($this->planet_tick_radius_minor * cos( $theta ));
			$y2 = $this->wheel_origin_y + ($this->planet_tick_radius_minor * sin( $theta ));

#			$this->SetXY(
#				$this->wheel_origin_x + ($this->planet_glyph_radius_major * cos( $theta )) - ($glyph_width/2),
#				$this->wheel_origin_y + ($this->planet_glyph_radius_major * sin( $theta ))
#			);
			$this->SetXY(
				$this->wheel_origin_x + ($radius_mapping[ $planet_mapping[$i] ] * cos( $theta )) - ($glyph_width/2),
				$this->wheel_origin_y + ($radius_mapping[ $planet_mapping[$i] ] * sin( $theta ))
			);
			/*
			 * TODO add configuration for the colour of the glyph
			 */
			$this->SetTextColor(0,0,255);
			$this->Cell($glyph_width,0,chr($this->symbol_map[$i]),0,0,'C',0);
			$this->SetTextColor(0,0,0);
		}
	}

	/**
	 * 
	 */
	function drawWheelAspects() {

		$degRad = (double)((pi()*2.0)/360.0);

		// the aspect background is white
		$this->SetFillColor(0xFF,0x00,0x00);
		$this->Circle($this->wheel_origin_x,$this->wheel_origin_y,$this->house_cusp_minor,'F');

		for($i=0; $i<count($this->planet_aspects); $i++) {

			$p1 = (int)substr($this->planet_aspects[$i],0,4)-1000;
			$as = (int)substr($this->planet_aspects[$i],4,3);
			$p2 = (int)substr($this->planet_aspects[$i],7,4)-1000;
			
			switch($as) {
			case 0:
			case 30:
			case 60:
			case 120:
				$this->SetDrawColor(0x00,0x00,0xFF);
				break;
			case 45:
			case 90:
			case 135:
			case 180:
				$this->SetDrawColor(0xFF,0x00,0x00);
				break;
			case 150:
				$this->SetDrawColor(0x00,0xFF,0x00);
				break;
			}
						
			$theta = ($degRad * fmod((180.0 - $this->planet_longitude[$p1] + $this->wheel_offset), 360.0) ); // degrees
			$x1 = $this->wheel_origin_x + ($this->aspect_line_radius * cos( $theta ));
			$y1 = $this->wheel_origin_y + ($this->aspect_line_radius * sin( $theta ));
  
			$theta = ($degRad * fmod((180.0 - $this->planet_longitude[$p2] + $this->wheel_offset), 360.0) ); // degrees
			$x2 = $this->wheel_origin_x + ($this->aspect_line_radius * cos( $theta ));
			$y2 = $this->wheel_origin_y + ($this->aspect_line_radius * sin( $theta ));
			$this->Line($x1,$y1,$x2,$y2);
		}
		$this->SetDrawColor(0,0,0);
	}

	/**
	 * 
	 */
	function drawTableUserInfo( $lang ) {

		global $wheel_top_housesystem;
		global $wheel_top_orbsensitivity;
		global $wheel_top_timezone;
		global $wheel_top_summertime;

		$this->SetFont(
			$this->table_user_info_font_family,
			$this->table_user_info_font_weight,
			$this->table_user_info_font_size
			);
		
		$this->Rect(
			$this->table_user_info_origin_x,
			$this->table_user_info_origin_y,
			$this->table_user_info_width,
			$this->table_user_info_height
			);
		$this->Line(	/* 25% */
			($this->table_user_info_origin_x + ($this->table_user_info_width/4)),
			$this->table_user_info_origin_y,
			($this->table_user_info_origin_x + ($this->table_user_info_width/4)),
			($this->table_user_info_origin_y + $this->table_user_info_height)
			);
		$this->Line(	/* 50% */
			($this->table_user_info_origin_x + ($this->table_user_info_width/2)),
			$this->table_user_info_origin_y,
			($this->table_user_info_origin_x + ($this->table_user_info_width/2)),
			($this->table_user_info_origin_y + $this->table_user_info_height)
			);
		$this->Line(	/* 75% */
			($this->table_user_info_origin_x + (3*$this->table_user_info_width/4)),
			$this->table_user_info_origin_y,
			($this->table_user_info_origin_x + (3*$this->table_user_info_width/4)),
			($this->table_user_info_origin_y + $this->table_user_info_height)
			);
		$this->Line(	/* 33% */
			($this->table_user_info_origin_x + ($this->table_user_info_width/4)),
			$this->table_user_info_origin_y + ($this->table_user_info_height/3),
			($this->table_user_info_origin_x + $this->table_user_info_width),
			$this->table_user_info_origin_y + ($this->table_user_info_height/3)
			);
		$this->Line(	/* 66% */
			($this->table_user_info_origin_x + ($this->table_user_info_width/4)),
			$this->table_user_info_origin_y + (2*$this->table_user_info_height/3),
			($this->table_user_info_origin_x + $this->table_user_info_width),
			$this->table_user_info_origin_y + (2*$this->table_user_info_height/3)
			);

		// fname
		$this->SetXY(
			$this->table_user_info_origin_x,
			$this->table_user_info_origin_y
			);
		$this->Cell(
			($this->table_user_info_width/4),
			($this->table_user_info_height/3),
			$this->table_user_info_fname,
			0, 2, 'C', 0
			);
		// lname
		$this->SetXY(
			$this->table_user_info_origin_x,
			$this->table_user_info_origin_y + ($this->table_user_info_height/3)
			);
		$this->Cell(
			($this->table_user_info_width/4),
			($this->table_user_info_height/3),
			$this->table_user_info_lname,
			0, 2, 'C', 0
			);
		// Weekday
		$this->SetXY(
			$this->table_user_info_origin_x + ($this->table_user_info_width / 4),
			$this->table_user_info_origin_y
			);
		$this->Cell(
			($this->table_user_info_width / 4),
			($this->table_user_info_height / 3),
			$this->table_user_info_birth_weekday, 0, 2, 'C', 0
			);
		// House System
		$this->SetXY(
			$this->table_user_info_origin_x + ($this->table_user_info_width / 2),
			$this->table_user_info_origin_y
			);
		$this->Cell(
			($this->table_user_info_width / 4),
			($this->table_user_info_height / 3),
			$this->table_user_info_house_system,
			0, 2, 'C', 0
			);
		// Orb Setting
		$this->SetXY(
			$this->table_user_info_origin_x + (3*$this->table_user_info_width/4),
			$this->table_user_info_origin_y
			);
		$this->Cell(
			($this->table_user_info_width/4),
			($this->table_user_info_height/3),
			$this->table_user_info_orb_system,
			0, 2, 'C', 0
			);
		// Date of Birth
		$this->SetXY(
			$this->table_user_info_origin_x + $this->table_user_info_width/4,
			$this->table_user_info_origin_y + ($this->table_user_info_height/3)
			);
		$this->Cell(
			($this->table_user_info_width/4),
			($this->table_user_info_height/3),
			$this->table_user_info_birth_date,
			0, 2, 'C', 0
			);
    // Timezone
		$this->SetXY(
			$this->table_user_info_origin_x + $this->table_user_info_width/2,
			$this->table_user_info_origin_y + ($this->table_user_info_height/3)
			);
		$this->Cell(
			($this->table_user_info_width/4),
			($this->table_user_info_height/3),
			sprintf("%s: %s", $wheel_top_timezone[$lang], $this->table_user_info_birth_timezone),
			0, 2, 'C', 0
			);
//    $this->summertime;
		$this->SetXY(
			$this->table_user_info_origin_x + (3*$this->table_user_info_width/4),
			$this->table_user_info_origin_y + ($this->table_user_info_height/3)
			);
		$this->Cell(
			($this->table_user_info_width/4),
			($this->table_user_info_height/3),
			sprintf("%s: %s", $wheel_top_summertime[$lang], $this->table_user_info_birth_summertime),
			0, 2, 'C', 0
			);
//    $this->birth_place;
		$this->SetXY(
			$this->table_user_info_origin_x + $this->table_user_info_width/4,
			$this->table_user_info_origin_y + (2*$this->table_user_info_height/3)
			);
		$this->Cell(
			($this->table_user_info_width/4),
			($this->table_user_info_height/3),
			$this->table_user_info_birth_place,
			0, 2, 'C', 0
			);
//		$this->birth_state;
		$this->SetXY(
			$this->table_user_info_origin_x + $this->table_user_info_width/2,
			$this->table_user_info_origin_y + (2*$this->table_user_info_height/3)
			);
		$this->Cell(
			($this->table_user_info_width/4),
			($this->table_user_info_height/3),
			$this->table_user_info_birth_state,
			0, 2, 'C', 0
			);
//    $this->birth_coordinates;
		$this->SetXY(
			$this->table_user_info_origin_x + (3*$this->table_user_info_width/4),
			$this->table_user_info_origin_y + (2*$this->table_user_info_height/3)
			);
		$this->Cell(
			($this->table_user_info_width/4),
			($this->table_user_info_height/3),
			$this->table_user_info_birth_coords,
			0, 2, 'C', 0
			);
	}

	/**
	 * 
	 */
	function drawTableSymbolInfo( $lang ) {

		global $wheel_info_symbols;
#		$symbols = array(
#			'Sun','Moon',
#			'Mercury','Venus','Mars',
#			'Jupiter','Saturn',
#			'Uranus','Neptune','Pluto',
#			'N.Node','S.Node',
#			'Aries','Taurus','Gemini','Cancer',
#			'Leo','Virgo','Libra','Scorpio',
#			'Sagittarius','Capricorn','Aquarius','Pisces',
#			'Conjunction','Semisextile','Semisquare','Sextile','Square',
#			'Trine','Sesquisquare','Quincunx','Opposition',
#			'Ascendant','Descendant','Medium Coeli','Immum Coeli',
#			'Retrograde'
#			);

		$this->Rect(
			$this->table_symbol_info_origin_x,
			$this->table_symbol_info_origin_y,
			$this->table_symbol_info_width,
			$this->table_symbol_info_height
			);
		$this->Line(
			$this->table_symbol_info_origin_x + ($this->table_symbol_info_width/3),
			$this->table_symbol_info_origin_y,
			$this->table_symbol_info_origin_x + ($this->table_symbol_info_width/3),
			$this->table_symbol_info_origin_y + $this->table_symbol_info_height);
		for($i = 0; $i < 38; $i++) {
			$this->Line(
				$this->table_symbol_info_origin_x,
				($this->table_symbol_info_origin_y + ($i * ($this->table_symbol_info_height/38))),
				$this->table_symbol_info_origin_x + $this->table_symbol_info_width,
				($this->table_symbol_info_origin_y + ($i * ($this->table_symbol_info_height/38)))
				);
			// draw the glyph
			$this->SetFont(
				$this->table_symbol_info_font_family,
				$this->table_symbol_info_font_weight,
				$this->table_symbol_info_font_size
				);
			$this->SetXY(
				$this->table_symbol_info_origin_x,
				($this->table_symbol_info_origin_y + ($i * ($this->table_symbol_info_height/38)))
				);
			$this->Cell(
				($this->table_symbol_info_width/3),
				($this->table_symbol_info_height/38),
				chr($this->symbol_map[$i]),
				0, 2, 'C', 0
				);
			// draw the explanation
//			$this->SetFont('Arial','',7.5);
			$this->SetXY(
				($this->table_symbol_info_origin_x + ($this->table_symbol_info_width/3)),
				($this->table_symbol_info_origin_y + ($i * ($this->table_symbol_info_height/38)))
				);
			$this->Cell(
				(2 * $this->table_symbol_info_width/3),
				($this->table_symbol_info_height/38),
				$wheel_info_symbols[$lang][$i],
				0, 2, 'L', 0
				);
		}
	}

	/**
	 * 
	 */
	function drawTableHouseInfo() {

		$this->Rect(
			$this->table_house_info_origin_x,
			$this->table_house_info_origin_y,
			$this->table_house_info_width,
			$this->table_house_info_height
			);
		$this->Line(
			$this->table_house_info_origin_x + ($this->table_house_info_width * 0.3),
			$this->table_house_info_origin_y,
			$this->table_house_info_origin_x + ($this->table_house_info_width * 0.3),
			$this->table_house_info_origin_y + $this->table_house_info_height
			);
		$this->Line(
			$this->table_house_info_origin_x + ($this->table_house_info_width * 0.7),
			$this->table_house_info_origin_y,
			$this->table_house_info_origin_x + ($this->table_house_info_width * 0.7),
			$this->table_house_info_origin_y + $this->table_house_info_height
			);

		for($i = 0; $i < 12; $i++) {

			$this->Line(
				$this->table_house_info_origin_x,
				($this->table_house_info_origin_y + ($i * ($this->table_house_info_height/12))),
				$this->table_house_info_origin_x + $this->table_house_info_width,
				($this->table_house_info_origin_y + ($i * ($this->table_house_info_height/12)))
				);

			$this->SetFont(
				$this->table_house_info_font_family,
				$this->table_house_info_font_weight,
				$this->table_house_info_font_size
			);
			$this->SetXY(
				$this->table_house_info_origin_x,
				($this->table_house_info_origin_y + ($i * ($this->table_house_info_height/12)))
				);
			switch($i) {
			case '0':
				$this->Cell(
					($this->table_house_info_width / 3),
					($this->table_house_info_height / 12),
					chr($this->symbol_map[33]),
					0, 2, 'C', 0
					);
				break;
			case '3':
				$this->Cell(
					($this->table_house_info_width / 3),
					($this->table_house_info_height / 12),
					chr($this->symbol_map[36]),
					0, 2, 'C', 0
					);
				break;
			case '6':
				$this->Cell(
					($this->table_house_info_width / 3),
					($this->table_house_info_height / 12),
					chr($this->symbol_map[34]),
					0, 2, 'C', 0
					);
				break;
			case '9':
				$this->Cell(
					($this->table_house_info_width / 3),
					($this->table_house_info_height / 12),
					chr($this->symbol_map[35]),
					0, 2, 'C', 0
					);
				break;
			default:
				$this->Cell(
					($this->table_house_info_width / 3),
					($this->table_house_info_height / 12),
					($i+1),
					0, 2, 'C', 0
					);
			}
			
			// 2nd column - cusp degrees
			$degrees = fmod($this->house_cusp_longitude[$i],30.0);
			//$minutes = astrogetminutes($degrees);
			$minutes = $degrees - intval($degrees);
			$minutes = $minutes * 0.6;
			$minutes = intval($minutes * 100);
			$degree = sprintf("%02d,%02d", $degrees, $minutes);
			
			$this->SetXY(
				($this->table_house_info_origin_x + ($this->table_house_info_width * 0.3)),
				($this->table_house_info_origin_y + ($i * ($this->table_house_info_height / 12)))
				);
			$this->Cell(
				($this->table_house_info_width * 0.4),
				($this->table_house_info_height / 12),
				$degree,
				0, 2, 'R', 0
				);

			// 3rd column - cusp signs
			$sign = (int)($this->house_cusp_longitude[$i] / 30.0);
			$this->SetXY(
				($this->table_house_info_origin_x + ($this->table_house_info_width * 0.7)),
				($this->table_house_info_origin_y + ($i * ($this->table_house_info_height / 12)))
				);
			$this->Cell(
				($this->table_house_info_width * 0.4),
				($this->table_house_info_height / 12),
				chr($this->symbol_map[$sign+12]),
				0, 2, 'C', 0
				);
		}
	}

	/**
	 * 
	 */
	function drawTablePlanetInfo() {

		$this->Rect(
			$this->table_planet_info_origin_x,
			$this->table_planet_info_origin_y,
			$this->table_planet_info_width,
			$this->table_planet_info_height
			);

		$this->Line(
			$this->table_planet_info_origin_x + ($this->table_planet_info_width * 0.18),
			$this->table_planet_info_origin_y,
			$this->table_planet_info_origin_x + ($this->table_planet_info_width * 0.18),
			$this->table_planet_info_origin_y + $this->table_planet_info_height
			);
		$this->Line(
			$this->table_planet_info_origin_x + ($this->table_planet_info_width * 0.46),
			$this->table_planet_info_origin_y,
			$this->table_planet_info_origin_x + ($this->table_planet_info_width * 0.46),
			$this->table_planet_info_origin_y + $this->table_planet_info_height
			);
		$this->Line(
			$this->table_planet_info_origin_x + ($this->table_planet_info_width * 0.64),
			$this->table_planet_info_origin_y,
			$this->table_planet_info_origin_x + ($this->table_planet_info_width * 0.64),
			$this->table_planet_info_origin_y + $this->table_planet_info_height
			);
		$this->Line(
			$this->table_planet_info_origin_x + ($this->table_planet_info_width * 0.82),
			$this->table_planet_info_origin_y,
			$this->table_planet_info_origin_x + ($this->table_planet_info_width * 0.82),
			$this->table_planet_info_origin_y + $this->table_planet_info_height
			);

		$this->SetFont(
			$this->table_planet_info_font_family,
			$this->table_planet_info_font_weight,
			$this->table_planet_info_font_size
		);

		// for each planet
		for($i = 0; $i < 12; $i++) {
	  		$this->Line(
	  			$this->table_planet_info_origin_x,
	  			($this->table_planet_info_origin_y + ($i * ($this->table_planet_info_height / 12))),
				$this->table_planet_info_origin_x + $this->table_planet_info_width,
				($this->table_planet_info_origin_y + ($i * ($this->table_planet_info_height / 12 )))
  				);
			// planet glyph
			$this->SetXY(
				$this->table_planet_info_origin_x,
				($this->table_planet_info_origin_y + ($i * ($this->table_planet_info_height / 12)))
				);
			$this->Cell(
				($this->table_planet_info_width * 0.18),
				($this->table_planet_info_height / 12),
				chr( $this->symbol_map[$i] ),
				0, 2, 'C', 0
				);
			// longitude
			$degrees = fmod($this->planet_longitude[$i],30.0);
			//$minutes = astrogetminutes($degrees);
			$minutes = $degrees - intval($degrees);
			$minutes = $minutes * 0.6;
			$minutes = intval($minutes * 100);
			$degree = sprintf("%02d,%02d", $degrees, $minutes);
			$this->SetXY(
				($this->table_planet_info_origin_x + ($this->table_planet_info_width * 0.18)),
				($this->table_planet_info_origin_y + ($i * ($this->table_planet_info_height / 12)))
				);
			$this->Cell(
				($this->table_planet_info_width * 0.28),
				($this->table_planet_info_height / 12),
				$degree,
				0, 2, 'C', 0
				);
			// sign
			$sign = (int)($this->planet_longitude[$i] / 30.0);
			$this->SetXY(
				($this->table_planet_info_origin_x + ($this->table_planet_info_width * 0.46)),
				($this->table_planet_info_origin_y + ($i * ($this->table_planet_info_height / 12)))
				);
			$this->Cell(
				($this->table_planet_info_width * 0.18),
				($this->table_planet_info_height / 12),
				chr($this->symbol_map[$sign+12]),
				0, 2, 'C', 0
				);
			// retrograde
		//	$this->SetXY(
		//		($this->table_planet_info_origin_x + ($this->table_planet_info_origin_width * 0.64)),
		//		($this->table_planet_info_origin_y + ($i * ($this->table_planet_info_height / 12)))
		//		);
		//	$this->Cell(
		//		($this->table_planet_info_width * 0.18),
		//		($this->table_planet_info_height / 12),
		//		chr(53), /* change to $this->symbol_map[] */
		//		0, 2, 'C', 0
		//		);
			// house
			$this->SetXY(
				($this->table_planet_info_origin_x + ($this->table_planet_info_width * 0.82)),
				($this->table_planet_info_origin_y + ($i * ($this->table_planet_info_height / 12)))
				);
			$this->Cell(
				($this->table_planet_info_width * 0.18),
				($this->table_planet_info_height / 12),
				$this->planet_in_house[$i],
				0, 2, 'C', 0
				);
		}
	}

	/**
	 * 
	 */
	function drawTableAspectInfo() {
	  global $logger;

		$this->Rect(
			$this->table_aspect_info_origin_x,
			$this->table_aspect_info_origin_y,
			$this->table_aspect_info_width,
			$this->table_aspect_info_height
			);

		$this->SetFont(
			$this->table_aspect_info_font_family,
			$this->table_aspect_info_font_weight,
			$this->table_aspect_info_font_size
		);

		for($i = 0; $i < 15; $i++) {
			
	  		// vertical lines
			$this->Line(
				$this->table_aspect_info_origin_x + ($i * ($this->table_aspect_info_width / 15)),
				$this->table_aspect_info_origin_y,
				$this->table_aspect_info_origin_x + ($i * ($this->table_aspect_info_width / 15)),
				$this->table_aspect_info_origin_y + $this->table_aspect_info_height
				);

			$this->SetXY(
				$this->table_aspect_info_origin_x,
				($this->table_aspect_info_origin_y + ($i * ($this->table_aspect_info_height / 15)))
				);
				
			if( $i > 0 && $i <= 12 ) {
        		$this->Cell(
        			($this->table_aspect_info_width / 15),
        			($this->table_aspect_info_height / 15),
        			chr($this->symbol_map[$i-1]),
        			0, 2, 'C', 0
        			);
			} elseif ( $i == 13 ) {
        		$this->Cell(
        			($this->table_aspect_info_width / 15),
        			($this->table_aspect_info_height / 15),
        			chr($this->symbol_map[$i+20]),
        			0, 2, 'C', 0
        			);
			} elseif ( $i == 14 ) {
        		$this->Cell(
        			($this->table_aspect_info_width / 15),
        			($this->table_aspect_info_height / 15),
        			chr($this->symbol_map[$i+21]),
        			0, 2, 'C', 0
        			);
			} else {
				// do nothing for 0
			}

  			// horizontal lines
			$this->Line(
				$this->table_aspect_info_origin_x,
				$this->table_aspect_info_origin_y + ($i * ($this->table_aspect_info_height / 15)),
				($this->table_aspect_info_origin_x + $this->table_aspect_info_width),
				$this->table_aspect_info_origin_y + ($i * ($this->table_aspect_info_height / 15))
				);

			$this->SetXY(
				($this->table_aspect_info_origin_x + ($i * ($this->table_aspect_info_width / 15))),
				$this->table_aspect_info_origin_y
				);
			if( $i > 0 && $i <= 12 ) {
		        $this->Cell(
		        	($this->table_aspect_info_width / 15),
		        	($this->table_aspect_info_height / 15),
		        	chr($this->symbol_map[$i-1]),
		        	0, 2, 'C', 0
		        	);
			} elseif ( $i == 13 ) {
        		$this->Cell(
        			($this->table_aspect_info_width / 15),
        			($this->table_aspect_info_height/ 15),
        			chr($this->symbol_map[$i+20]),
        			0, 2, 'C', 0
        			);
			} elseif ( $i == 14 ) {
        		$this->Cell(
        			($this->table_aspect_info_width / 15),
        			($this->table_aspect_info_height / 15),
        			chr($this->symbol_map[$i+21]),
        			0, 2, 'C', 0
        			);
			} else {
				// do nothing for 0
			}
		}

		for($i=0; $i<count($this->planet_aspects); $i++) {
		  $logger->debug("AspectTableDebug: aspects = $this->planet_aspects[$i]");
			$p1 = (int)substr($this->planet_aspects[$i],0,4)-1000;
			$as = (int)substr($this->planet_aspects[$i],4,3);
			$p2 = (int)substr($this->planet_aspects[$i],7,4)-1000;
			$ao = trim( substr($this->planet_aspects[$i],12,5) );
			$logger->debug("AspectTableDebug: p1[$p1], as[$as], p2[$p2]");

			/* this section can be removed if the orb transports */
			if( $this->planet_longitude[$p1] > $this->planet_longitude[$p2] ) {
				$aspect_distance = abs($this->planet_longitude[$p1] - $this->planet_longitude[$p2]);
			} else {
				$aspect_distance = abs($this->planet_longitude[$p2] - $this->planet_longitude[$p1]);
			}
			$aspect_distance = fmod($aspect_distance, (double)$as);
			$logger->debug("p1.long = $this->planet_longitude[$p1]");
			$logger->debug("p2.long = $this->planet_longitude[$p2]");
			$logger->debug("aspdist = $aspect_distance");
			if( $as != 0 ) {
				if( $aspect_distance > 15 ) {
					// approaching
					$aspect_distance -= $as;
				} else {
					// separating
				}
			} else {
				// hmmm - thought required
			}
			
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
						
			// an example - develop to gather aspects
	    	$this->SetFont(
				$this->table_aspect_info_font_family,
				$this->table_aspect_info_font_weight,
				$this->table_aspect_info_glyph_size
				);
			if($p1 > $p2) {
		    	$this->SetXY(
		     		($this->table_aspect_info_origin_x + (($p1+1) * ($this->table_aspect_info_width / 15))),
		     		($this->table_aspect_info_origin_y + (($p2+1) * ($this->table_aspect_info_height / 15)))
		    		);
			} else {
		    	$this->SetXY(
		     		($this->table_aspect_info_origin_x + (($p2+1) * ($this->table_aspect_info_width / 15))),
		     		($this->table_aspect_info_origin_y + (($p1+1) * ($this->table_aspect_info_height / 15)))
		    		);
			}
	    	$this->Cell(
	    		($this->table_aspect_info_width / 15),
	    		($this->table_aspect_info_height / 30),
	    		chr($this->symbol_map[$aspect_glyph]),
	    		0, 2, 'C', 0
	    		);
	    	$this->SetFont(
	    		$this->table_aspect_info_font_family,
	    		$this->table_aspect_info_font_weight,
	    		$this->table_aspect_info_orb_size);
	    	$this->Cell(
	    		($this->table_aspect_info_width / 15),
	    		($this->table_aspect_info_height / 30),
				$ao /* sprintf("%5.2f",$aspect_distance) */,
				0, 2, 'C', 0
				);
		}
	}
}
?>