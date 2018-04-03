<?php 

	namespace App\Model\Table;
	use Cake\ORM\Table;

	class EmailTemplatesTable extends Table { 
	
		public function initialize(array $config) {
			$this->addBehavior('Translate', ['fields' => ['name', 'content']]);
			$this->addBehavior('Timestamp');
		}

	}

?>