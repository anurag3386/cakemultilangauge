<?php 

	namespace App\Model\Table;
	use Cake\ORM\Table;
	use Cake\Validation\Validator;

	class TempOrderShippingsTable extends Table { 
	
		public function initialize(array $config) {
			$this->addBehavior('Timestamp');

		}

  public function validationDefault(Validator $validator) 
     {
		 $validator = new Validator();
         $validator->notEmpty('order_id')
                   ->notEmpty('user_id')
                   ->notEmpty('first_name')
                   ->notEmpty('last_name')
                   ->notEmpty('address_1')
                   ->notEmpty('city')
  				   ->notEmpty('country')
  				   ->notEmpty('postal_code')
  				   ->notEmpty('phone') ;
      
     	 return $validator;
     }
	}

?>