<?php
class AspectsObjects {

	var $PlanetCode1;
	var $PlanetCode2;
	var $AspectType;
	var $Orbs;
	var $NewOrbs;
	var $OrbRank;
	var $ResultSet;

	var $OrbScore = 100;

	function __construct($Code1, $Code2, $AspType, $COrbs, $COrbRank, $ActualSet) {
		$this->PlanetCode1 = $Code1;
		$this->PlanetCode2 = $Code2;
		$this->AspectType = $AspType;
		$this->Orbs = floatval($COrbs);
		$this->OrbRank = floatval($COrbRank);
		$this->ResultSet = $ActualSet;

		$this->OrbScore = 100;
		$this->SetOrbWeight();
	}

	function SetOrbWeight() {

		switch ($this->AspectType) {
			case '000' :
				$this->SetConjunction();
				break;
			case '180' :
				$this->SetOpposition();
				break;
			case '120' :
				$this->SetTrine();
				break;
			case '060' :
				$this->SetSextile();
				break;
			case '090' :
				$this->SetSquare();
				break;
			default :
				$this->SetConjunction();
				break;
		}
	}

	function SetConjunction() {
		$this->OrbScore = 100;
		if(floatval($this->Orbs) >= 0 && floatval($this->Orbs) <= 1.00) {
			$this->NewOrbs = $this->OrbScore;
		} else if(floatval($this->Orbs)>= 1.01 && floatval($this->Orbs)<= 2.00) {
			$this->NewOrbs = $this->OrbScore - $this->OrbScore / 5;
		} else if(floatval($this->Orbs)>= 2.01 && floatval($this->Orbs)<= 3.00) {
			$this->NewOrbs = $this->OrbScore - ($this->OrbScore * 3 / 10);
		} else if(floatval($this->Orbs)>= 3.01 && floatval($this->Orbs)<= 4.00) {
			$this->NewOrbs = $this->OrbScore - ($this->OrbScore * 4 / 10);
		} else if(floatval($this->Orbs)>= 4.01 && floatval($this->Orbs)<= 5.00) {
			$this->NewOrbs = $this->OrbScore - ($this->OrbScore * 5 / 10);
		} else if(floatval($this->Orbs)>= 5.01 && floatval($this->Orbs)<= 6.00) {
			$this->NewOrbs = $this->OrbScore - ($this->OrbScore * 6 / 10);
		} else if(floatval($this->Orbs)>= 6.01 && floatval($this->Orbs)<= 7.00) {
			$this->NewOrbs = 30;
		} else if(floatval($this->Orbs)>= 7.01 && floatval($this->Orbs)<= 8.00) {
			$this->NewOrbs = 20;
		} else if(floatval($this->Orbs)>= 8.01 && floatval($this->Orbs)<= 9.00) {
			$this->NewOrbs = $this->OrbScore - ($this->OrbScore * 9 / 10);
		} else if(floatval($this->Orbs)>= 9.01) {
			$this->NewOrbs = 0;
		}
	}

	function SetOpposition() {
		$this->OrbScore = 80;
		if(floatval($this->Orbs)>= 0.00 && floatval($this->Orbs)<= 1.00) {
			$this->NewOrbs = $this->OrbScore;
		} else if(floatval($this->Orbs)>= 1.01 && floatval($this->Orbs)<= 2.00) {
			$this->NewOrbs = $this->OrbScore - ($this->OrbScore * 2 / 8);
		} else if(floatval($this->Orbs)>= 2.01 && floatval($this->Orbs)<= 3.00) {
			$this->NewOrbs = $this->OrbScore - ($this->OrbScore * 3 / 8);
		} else if(floatval($this->Orbs)>= 3.01 && floatval($this->Orbs)<= 4.00) {
			$this->NewOrbs = $this->OrbScore - ($this->OrbScore * 4 / 8);
		} else if(floatval($this->Orbs)>= 4.01 && floatval($this->Orbs)<= 5.00) {
			$this->NewOrbs = $this->OrbScore - ($this->OrbScore * 5 / 8);
		} else if(floatval($this->Orbs)>= 5.01 && floatval($this->Orbs)<= 6.00) {
			$this->NewOrbs = $this->OrbScore - ($this->OrbScore * 6 / 8);
		} else if(floatval($this->Orbs)>= 6.01 && floatval($this->Orbs)<= 7.00) {
			$this->NewOrbs = $this->OrbScore - ($this->OrbScore * 7 / 8);
		} else if(floatval($this->Orbs)>= 7.01 && floatval($this->Orbs)<= 8.00) {
			$this->NewOrbs = $this->OrbScore - ($this->OrbScore * 8 / 8);
		} else {
			$this->NewOrbs = 0;
		}
	}

	function SetTrine() {
		$this->SetOpposition();
	}

	function SetSquare() {
		$this->OrbScore = 60;
		
		echo "<br />***************************** SetSquare === " . floatval($this->Orbs). " ====";
		
		if(floatval($this->Orbs) >= 0.00 && floatval($this->Orbs) <= 1.00) {
			$this->NewOrbs = $this->OrbScore;
		} else if(floatval($this->Orbs) >= 1.01 && floatval($this->Orbs) <= 2.00) {
			$this->NewOrbs = $this->OrbScore - ($this->OrbScore * 2 / 6);
		} else if(floatval($this->Orbs) >= 2.01 && floatval($this->Orbs) <= 3.00) {
			$this->NewOrbs = $this->OrbScore - ($this->OrbScore * 3 / 6);
		} else if(floatval($this->Orbs) >= 3.01 && floatval($this->Orbs) <= 4.00) {
			$this->NewOrbs = $this->OrbScore - ($this->OrbScore * 4 / 6);
		} else if(floatval($this->Orbs) >= 4.01 && floatval($this->Orbs) <= 5.00) {
			$this->NewOrbs = $this->OrbScore - ($this->OrbScore * 5 / 6);
		} else if(floatval($this->Orbs) >= 5.01 ) {
			$this->NewOrbs = 0;
		} 
	}

	function SetSextile() {
		$this->OrbScore = 40;
		if(floatval($this->Orbs) >= 0 && floatval($this->Orbs) <= 1.00) {
			$this->NewOrbs = $this->OrbScore;
		} else if(floatval($this->Orbs) >= 1.01 && floatval($this->Orbs) <= 2.00) {
			$this->NewOrbs = $this->OrbScore - ($this->OrbScore * 2 / 4);
		} else if(floatval($this->Orbs) >= 2.01 && floatval($this->Orbs) <= 3.00) {
			$this->NewOrbs = $this->OrbScore - ($this->OrbScore * 3 / 4);
		} else if(floatval($this->Orbs) >= 3.01 ) {
			$this->NewOrbs = 0;
		}
	}
}

class TransitObjects {

	var $PlanetCode1;
	var $PlanetCode2;
	var $AspectType;
	var $TDate;
	var $TotalRank;
	var $ResultSet;

	function __construct($Code1, $Code2, $AspType, $TransitDate, $TotalPoints, $ActualSet) {
		$this->PlanetCode1 = intval($Code1);
		$this->PlanetCode2 = intval($Code2);
		$this->AspectType = intval($AspType);
		$this->TDate = $TransitDate;
		$this->TotalRank = intval($TotalPoints);
		$this->ResultSet = $ActualSet;
	}
}

class EphemerisObjects {

	var $Jupiter;
	var $Saturn;
	var $Uranus;
	var $Neptune;
	var $Pluto;
	var $TDate;

	function __construct($cJupiter, $cSaturn, $cUranus, $cNeptune, $cPluto, $cTDate) {
		$this->Jupiter = $cJupiter;
		$this->Saturn = $cSaturn;
		$this->Uranus = $cUranus;
		$this->Neptune = $cNeptune;
		$this->Pluto = $cPluto;
		$this->TDate = $cTDate;
	}
}

class LongitudeObject {
	var $Longitude;
	var $Retrograde;
	var $SignName;

	function __construct($cLongitude, $cRetrograde, $cSignName){
		$this->Longitude = floatval( $cLongitude );
		$this->Retrograde = $cRetrograde;
		$this->SignName = $cSignName;
	}
}