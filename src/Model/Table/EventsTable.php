<?php 

	namespace App\Model\Table;
	use Cake\ORM\Table;
	use Cake\Validation\Validator;

	class EventsTable extends Table { 
	
		public function initialize(array $config) {
			$this->addBehavior('Translate', ['fields' =>
												 ['title', 'date', 'time', 'place', 'description']]);
			$this->addBehavior('Timestamp');
		}

		public function validationDefault(Validator $validator) {
			$validator = new Validator();
			$validator->notEmpty("title", 'This field is required');	
			$validator->notEmpty("date", 'This field is required');	
			$validator->notEmpty("time", 'This field is required');	
			$validator->notEmpty("place", 'This field is required');	
			$validator->notEmpty("description", 'This field is required');
			return $validator;	
		}

	}

?>