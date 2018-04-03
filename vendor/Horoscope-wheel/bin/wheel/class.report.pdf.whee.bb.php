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

class PDF_Wheel_BB extends PDF_Wheel_WOW {

	var $language;
	
	function PDF_Wheel_BB() {
		$this->PDF_Wheel_WOW();		
	}
	
	/**
	 *
	 */
	function drawTableUserInfo($lang) {
	
		global $wheel_top_housesystem;
		global $wheel_top_orbsensitivity;
		global $wheel_top_timezone;
		global $wheel_top_summertime;
	
		$this->SetFont(
				$this->table_user_info_font_family, $this->table_user_info_font_weight, $this->table_user_info_font_size
		);
	
		$this->Rect(
				$this->table_user_info_origin_x, $this->table_user_info_origin_y, $this->table_user_info_width, $this->table_user_info_height
		);
		$this->Line(/* 25% */
				($this->table_user_info_origin_x + ($this->table_user_info_width / 4)), $this->table_user_info_origin_y, ($this->table_user_info_origin_x + ($this->table_user_info_width / 4)), ($this->table_user_info_origin_y + $this->table_user_info_height)
		);
		$this->Line(/* 50% */
				($this->table_user_info_origin_x + ($this->table_user_info_width / 2)), $this->table_user_info_origin_y, ($this->table_user_info_origin_x + ($this->table_user_info_width / 2)), ($this->table_user_info_origin_y + $this->table_user_info_height)
		);
		$this->Line(/* 75% */
				($this->table_user_info_origin_x + (3 * $this->table_user_info_width / 4)), $this->table_user_info_origin_y, ($this->table_user_info_origin_x + (3 * $this->table_user_info_width / 4)), ($this->table_user_info_origin_y + $this->table_user_info_height)
		);
		$this->Line(/* 33% */
				($this->table_user_info_origin_x + ($this->table_user_info_width / 4)), $this->table_user_info_origin_y + ($this->table_user_info_height / 3), ($this->table_user_info_origin_x + $this->table_user_info_width), $this->table_user_info_origin_y + ($this->table_user_info_height / 3)
		);
		$this->Line(/* 66% */
				($this->table_user_info_origin_x + ($this->table_user_info_width / 4)), $this->table_user_info_origin_y + (2 * $this->table_user_info_height / 3), ($this->table_user_info_origin_x + $this->table_user_info_width), $this->table_user_info_origin_y + (2 * $this->table_user_info_height / 3)
		);
	
		/*
		 * TODO
		* Look at the length of the name
		* if it can be split then manage the name on 2 lines
		* else consider font size management
		*/
		// fname
		$this->SetXY(
				$this->table_user_info_origin_x, $this->table_user_info_origin_y
		);
		$this->MultiCell(
				($this->table_user_info_width / 4), ($this->table_user_info_height / 3), $this->table_user_info_fname, 0, 2, 'C', 0
		);
	
		// lname OLD
		// Date Of Birth
		$this->SetXY(
				$this->table_user_info_origin_x, $this->table_user_info_origin_y + ($this->table_user_info_height / 3)
		);
		$this->Cell(($this->table_user_info_width / 4), ($this->table_user_info_height / 3), $this->table_user_info_lname, 0, 2, 'C', 0);
	
	
		// Weekday
		$this->SetXY($this->table_user_info_origin_x + ($this->table_user_info_width / 4), $this->table_user_info_origin_y);
		//NEW
		$this->Cell(($this->table_user_info_width / 4), ($this->table_user_info_height / 3), $this->table_user_info_birth_date, 0, 2, 'C', 0);
	
		// House System
		$this->SetXY($this->table_user_info_origin_x + ($this->table_user_info_width / 2), $this->table_user_info_origin_y);
		$this->Cell(($this->table_user_info_width / 4), ($this->table_user_info_height / 3), sprintf("%s: %s", $wheel_top_timezone[$lang], $this->table_user_info_birth_timezone), 0, 2, 'C', 0);
	
		// Orb Setting
		$this->SetXY($this->table_user_info_origin_x + (3 * $this->table_user_info_width / 4), $this->table_user_info_origin_y);
		//SUN IN
		$this->SetFont($this->table_planet_info_font_family, $this->table_user_info_font_weight, $this->table_user_info_font_size);
		$this->Cell(($this->table_user_info_width / 4), ($this->table_user_info_height / 3), chr(184) . " in " . chr($this->symbol_map[$this->table_user_info_birth_weekday]), 0, 2, 'C', 0);
		$this->SetFont($this->table_user_info_font_family, $this->table_user_info_font_weight, $this->table_user_info_font_size);
	
		// Date of Birth
		$this->SetXY($this->table_user_info_origin_x + $this->table_user_info_width / 4, $this->table_user_info_origin_y + ($this->table_user_info_height / 3));
		//NEW
		$this->Cell(($this->table_user_info_width / 4), ($this->table_user_info_height / 3), $this->table_user_info_birth_place, 0, 2, 'C', 0);
	
		// Timezone
		$this->SetXY($this->table_user_info_origin_x + $this->table_user_info_width / 2, $this->table_user_info_origin_y + ($this->table_user_info_height / 3));
		$this->Cell(($this->table_user_info_width / 4), ($this->table_user_info_height / 3), sprintf("%s: %s", $wheel_top_summertime[$lang], $this->table_user_info_birth_summertime), 0, 2, 'C', 0);
	
		// Summertime;
		$this->SetXY($this->table_user_info_origin_x + (3 * $this->table_user_info_width / 4), $this->table_user_info_origin_y + ($this->table_user_info_height / 3));
		//MOON IN
		$this->SetFont($this->table_planet_info_font_family, $this->table_user_info_font_weight, $this->table_user_info_font_size);
		$this->Cell(($this->table_user_info_width / 4), ($this->table_user_info_height / 3), chr(155) . " in " . chr($this->symbol_map[$this->table_user_info_house_system]), 0, 2, 'C', 0);
		$this->SetFont($this->table_user_info_font_family, $this->table_user_info_font_weight, $this->table_user_info_font_size);
	
		/* Birth Place */
		/* mismatched parentheses reported here !!! */
		$this->SetXY($this->table_user_info_origin_x + ($this->table_user_info_width / 4), $this->table_user_info_origin_y + (2 * ($this->table_user_info_height / 3)));
		$this->Cell(($this->table_user_info_width / 4), ($this->table_user_info_height / 3), $this->table_user_info_birth_state, 0, 2, 'C', 0);
	
		// Birth State
		$this->SetXY($this->table_user_info_origin_x + $this->table_user_info_width / 2, $this->table_user_info_origin_y + (2 * $this->table_user_info_height / 3));
		$this->Cell(($this->table_user_info_width / 4), ($this->table_user_info_height / 3), $this->table_user_info_birth_coords, 0, 2, 'C', 0);
	
		$this->SetXY($this->table_user_info_origin_x + (3 * $this->table_user_info_width / 4), $this->table_user_info_origin_y + (2 * $this->table_user_info_height / 3));
		//ASC In
		$this->SetFont($this->table_planet_info_font_family, $this->table_user_info_font_weight, $this->table_user_info_font_size);
		$this->Cell(($this->table_user_info_width / 4), ($this->table_user_info_height / 3), chr(124) . " in ". chr($this->symbol_map[$this->table_user_info_orb_system]), 0, 2, 'C', 0);
		$this->SetFont($this->table_user_info_font_family, $this->table_user_info_font_weight, $this->table_user_info_font_size);
	}
}
?>