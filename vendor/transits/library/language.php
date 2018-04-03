<?php
class Language {
	private $default = 'en';
	private $directory;
	private $data = array();
 
	public function __construct($directory) {
		$this->directory = $directory;
	}
	
  	public function get($key) {
   		return (isset($this->data[$key]) ? $this->data[$key] : $key);
  	}
	
	public function load($filename,$locale) {
		$file = DIR_LANGUAGE . $this->directory . $locale.'/' . $filename . '.php';
    	
		if (file_exists($file)) { 
			$_ = array();
	  		
			require($file);
			//print_r($_);
			$this->data = array_merge($this->data, $_);
			
			return $this->data;
		}
		
		$file = DIR_LANGUAGE . $this->default . '/' . $filename . '.php';
		
		if (file_exists($file)) {
			$_ = array();
	  		
			require($file);
		
			$this->data = array_merge($this->data, $_);
			
			return $this->data;
		} else {
			trigger_error('Error: Could not load language ' . $filename . '!');
			exit();
		}
  	}
}
?>