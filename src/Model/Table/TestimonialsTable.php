<?php 

	namespace App\Model\Table;
	use Cake\ORM\Table;
	use Cake\Validation\Validator;

	class TestimonialsTable extends Table { 
	
		public function initialize(array $config) {
			$this->addBehavior('Translate', ['fields' =>
												 ['name', 'profile', 'description']]);
			$this->addBehavior('Timestamp');
		}

		public function validationDefault(Validator $validator) {
			$validator = new Validator();
			$validator->notEmpty("name", 'This field is required');	
			$validator->notEmpty("profile", 'This field is required');	
			$validator->notEmpty("description", 'This field is required');
			return $validator;	
		}

	}

?>