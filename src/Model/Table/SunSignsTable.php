<?php 

	namespace App\Model\Table;
	use Cake\ORM\Table;

	class SunSignsTable extends Table { 
	
		public function initialize(array $config) {
			$this->addBehavior('Timestamp');
			$this->hasMany('SunSignPredictions');

		}

	}

?>