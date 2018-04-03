<?php 

	namespace App\Model\Table;
	use Cake\ORM\Table;

	class TemporaryOrdersTable extends Table { 
	
		public function initialize(array $config) {
			$this->hasMany('TemporaryLoversReportData');
			$this->addBehavior('Timestamp');

		}

	}

?>