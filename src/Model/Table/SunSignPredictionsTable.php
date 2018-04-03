<?php 

	namespace App\Model\Table;
	use Cake\ORM\Table;

	class SunSignPredictionsTable extends Table { 
	
		public function initialize(array $config) {
			$this->belongsTo('SunSigns');
			$this->addBehavior('Timestamp');

		}

	}

?>