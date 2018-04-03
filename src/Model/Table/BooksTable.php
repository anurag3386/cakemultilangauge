<?php 

	namespace App\Model\Table;
	use Cake\ORM\Table;
	use Cake\Validation\Validator;

	class BooksTable extends Table { 
	
		public function initialize(array $config) {
			$this->addBehavior('Translate', ['fields' =>
												 ['title', 'price', 'discount_text', 'button_text', 'description']]);
			$this->addBehavior('Timestamp');
		}

		public function validationDefault(Validator $validator) {
			$validator = new Validator();
			$validator->notEmpty("title", 'This field is required');	
			$validator->notEmpty("price", 'This field is required');	
			$validator->notEmpty("button_text", 'This field is required');
			$validator->notEmpty("description", 'This field is required');
			$validator->notEmpty("url", 'This field is required');
			return $validator;	
		}

	}

?>