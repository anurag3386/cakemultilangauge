<?php
/**
 * Generate Current Year Progression
 * 
 */

class CurrentYearProgression {
	//Progressed to Natal Aspects
	var $aspectProgressedToNatal = array();
	
	var $TopAspects;
	var $AspectsList;
	
	var $bDay;
	var $bMonth;
	var $bYear;
	
	var $startYear;
	var $PreviousYear;
	var $NextYear;
	var $CurrntYear;
	
	function CurrentYearProgression($birthDTO, $ordDate) {
		$this->bDay = $birthDTO->day;
		$this->bMonth = $birthDTO->month;
		$this->bYear = $birthDTO->year;
	
		$this->startYear = $ordDate->format('Y');         //Actual Code
		$this->startEnd = $ordDate->format('Y') + 2;      //Actual Code
	
		//									  MM = Month          DD = Day            YYYY = Year
		$this->PreviousYear = date("Y-m-d", mktime ( 0, 0, 0, $this->bMonth, $this->bDay, $this->startYear - 1 ) );
		$this->NextYear = date("Y-m-d", mktime ( 0, 0, 0, $this->bMonth, $this->bDay, $this->startYear + 1) );
		$this->CurrntYear = date("Y-m-d", mktime ( 0, 0, 0, $this->bMonth, $this->bDay, $this->startYear ) );
		
		$Global_PreviousYear = date("Y-m-d", mktime ( 0, 0, 0, $this->bMonth, $this->bDay, $this->startYear - 1) );
		$Global_NextYear = date("Y-m-d", mktime ( 0, 0, 0, $this->bMonth, $this->bDay, $this->startYear + 1 ) );
		$Global_CurrntYear = date("Y-m-d", mktime ( 0, 0, 0, $this->bMonth, $this->bDay, $this->startYear ) );

		global $Global_Progression_MObject;
		global $Global_Progression_m_transit;
		global $Global_Progression_m_crossing;
		global $Global_Progression_TransitSortedList;
		global $Global_Prog_Direct_Retrograde_List;
		
		$progressedAspectPDF = new GenerateCurrentYearProgressions($birthDTO, $ordDate);	
		$Global_Progression_TransitSortedList = $progressedAspectPDF->transit_window;
 		
	}
} 
?>