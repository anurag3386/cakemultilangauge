<?php 

	namespace App\Model\Table;
	use Cake\ORM\Table;

	class PreviewReportsTable extends Table { 
	
		public function initialize(array $config) {
			$this->addBehavior('Translate', ['fields' => ['title', 'pdf', 'image']]);
			$this->addBehavior('Timestamp');
			$this->hasOne('Products');

		}

	}

?>