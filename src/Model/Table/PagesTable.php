<?php 

	namespace App\Model\Table;
	use Cake\ORM\Table;
	use Cake\Validation\Validator;

	class PagesTable extends Table { 
	
		public function initialize(array $config) {
			$this->addBehavior('Translate', ['fields' =>
												 ['title', 'body', 'meta_title', 'meta_keywords', 'meta_description']]);
			$this->addBehavior('Timestamp');
		}

		public function validationDefault(Validator $validator) {
			$validator = new Validator();
			$validator->notEmpty("title", 'This field is required');	
			$validator->notEmpty("url", 'This field is required');	
			$validator->notEmpty("body", 'This field is required');
			return $validator;	
		}

	}

?>