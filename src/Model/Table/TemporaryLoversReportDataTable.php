<?php 

	namespace App\Model\Table;
	use Cake\ORM\Table;

	class TemporaryLoversReportDataTable extends Table { 
	
		public function initialize(array $config) {
			$this->belongsTo('TemporaryOrders');
			$this->addBehavior('Timestamp');

		}

	}

	

?>
