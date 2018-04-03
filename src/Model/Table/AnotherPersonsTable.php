<?php

	namespace App\Model\Table;
	use Cake\ORM\Table;
	use Cake\Validation\Validator;

	class AnotherPersonsTable extends Table {

		public function initialize(array $config) {
			$this->addBehavior('Timestamp');
		}

		public function validationDefault(Validator $validator) {
			$validator = new Validator();
			$validator->notEmpty("fname", 'This field is required')
					  ->notEmpty('lname', 'This field is required')
					  ->notEmpty('gender', 'This field is required')
					  ->notEmpty('dob', 'This field is required')
					  ->notEmpty ('time', 'Please fill')
					  ->notEmpty ('country_id', 'Please fill')
					  ->notEmpty ('city_id', 'Please fill');
			return $validator;	
		}

	}

?>