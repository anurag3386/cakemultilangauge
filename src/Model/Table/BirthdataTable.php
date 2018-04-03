<?php 
	namespace App\Model\Table;
	use Cake\ORM\Table;
	use Cake\Validation\Validator;

	class BirthdataTable extends Table { 
	
		public function initialize(array $config) {
			$this->addBehavior('Timestamp');

		}

		public function validationDefault(Validator $validator) {
			$validator = new Validator();
			$validator->notEmpty("day", 'This field is required')
					  ->notEmpty('month', 'This field is required')
                      ->notEmpty('year', 'This field is required')
                      ->notEmpty('hour', 'This field is required')
                      ->notEmpty('minute', 'This field is required')
                      ->notEmpty('zoneref', 'This field is required')
                      ->notEmpty('summerref', 'This field is required')
                      ->notEmpty('place', 'This field is required')
                      ->notEmpty('state', 'This field is required')
                      ->notEmpty('longitude', 'This field is required')
                      ->notEmpty('latitude', 'This field is required')
                      ->notEmpty('order_id', 'This field is required')
                      ->notEmpty('first_name', 'This field is required')
                      ->notEmpty('last_name', 'This field is required')
                      ->notEmpty('gender', 'This field is required');

			return $validator;	
		}

	}
?>