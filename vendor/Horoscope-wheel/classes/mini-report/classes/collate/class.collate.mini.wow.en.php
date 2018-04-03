<?php
/**
 * Script: class.collate.samples.en.php
 * Author: Andy Gray
 *
 * Description
 * Samples_Col8_FPDI_EN extends Col8_FPDI_EN extends Col8_FPDI
 */

class MINI_ASTROWOW_Col8_FPDI_EN extends Col8_FPDI_EN {

	var $franchise_holder = "Adrian";
	var $franchise_website = "www.world-of-wisdom.com";
	var $IncorporateWith = 'World of wisdom';
	
	var $table_of_contents;
	var $introduction_context;
	
	/**
	 * Constructor
	 */
	public function MINI_ASTROWOW_Col8_FPDI_EN() {
		$this->Col8_FPDI_EN();
	}

	/*
	 * this is where it is all put together
	*/
	function Assemble() {
		//$this->Wheel();
		$this->Report();
	}	

	/*
	 * The report pages are currently managed within the amanuensis main code
	* need to move that within this class
	*/
	function Report() {
		$file = sprintf("%s/%d.report.pdf", SPOOLPATH, $this->orderId);
		$this->import_page($file);	
	}
};

?>
