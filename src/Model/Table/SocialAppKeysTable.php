<?php 

	namespace App\Model\Table;
	use Cake\ORM\Table;
	use Cake\Validation\Validator;

	class SocialAppKeysTable extends Table { 
	
		public function initialize(array $config) {
		    $this->addBehavior('Timestamp');
		}

		public function validationDefault(Validator $validator) {
			$validator = new Validator();
			$validator->notEmpty("name", 'This field is required');	
			$validator->notEmpty("app_key", 'This field is required');	
			$validator->notEmpty("app_secret", 'This field is required');
			return $validator;	
		}

	}

?>