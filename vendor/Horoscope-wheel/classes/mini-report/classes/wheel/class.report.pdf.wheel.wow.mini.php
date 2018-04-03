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
	
	function PDF_Wheel_WOW() {
		$this->PDF_Wheel();
		$this->planet_longitude = array();
		$this->planet_in_house = array();
		$this->planet_aspects = array();
	}

	function ApplyCustomisations() {

		$this->margin_left			= 20;
		$this->margin_top			= 20;
		
		$this->text_font_family		= 'arial';
		$this->text_font_weight		= '';
		$this->text_font_size		= 10;
		
		$this->glyph_font_family	= 'wows';
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
		$this->table_user_info_height			= 21;		// 3 ROWS		
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
}
?>