<?php 

	namespace App\Model\Table;
	use Cake\ORM\Table;
	use Cake\Validation\Validator;

	class MediaTable extends Table { 
	
		public function initialize(array $config) {
			$this->addBehavior('Translate', ['fields' => ['name']]);
            $this->addBehavior('Timestamp');
		}

		public function validationDefault(Validator $validator) {
			$validator = new Validator();
			$validator->notEmpty("category_id", 'This field is required');	
			$validator->notEmpty("_translations.da.name", 'This field is required');	
			$validator->notEmpty("_translations.da.name", 'This field is required');	
			//$validator->notEmpty("path", 'This field is required');	
			$validator->notEmpty("sort_order", 'This field is required');	


			return $validator;	
		}

	}

?>