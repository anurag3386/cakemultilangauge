<?php 
	namespace App\Model\Table;
	use Cake\ORM\Table;
	use Cake\Validation\Validator;

	class BirthDetailsTable extends Table { 

		public function initialize(array $config) {
			$this->addBehavior('Timestamp');
		}	
	
		public function validationDefault(Validator $validator) {
			$validator = new Validator();
			$validator->notEmpty("datepicker", 'This field is required');
			$validator->notEmpty("country_id", 'This field is required');
			$validator->notEmpty("city_id", 'This field is required');
			$validator->notEmpty('date', 'This field is required');
		  //	$validator->notEmpty ('time', 'This field is required');
			return $validator;	
		}

	}

?>