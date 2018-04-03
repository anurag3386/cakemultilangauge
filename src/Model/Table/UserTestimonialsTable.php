<?php 

	namespace App\Model\Table;
	use Cake\ORM\Table;
	use Cake\Validation\Validator;

	class UserTestimonialsTable extends Table { 
	
		public function initialize(array $config) {
			$this->addBehavior('Translate', ['fields' => ['content']]);
			$this->addBehavior('Timestamp');
		}

	}

?>