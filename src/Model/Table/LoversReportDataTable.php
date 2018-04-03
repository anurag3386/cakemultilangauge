<?php 

	namespace App\Model\Table;
	use Cake\ORM\Table;
	use Cake\Validation\Validator;

	class LoversReportDataTable extends Table { 
	
		public function initialize(array $config) {
			$this->addBehavior('Timestamp');

		}

  public function validationDefault(Validator $validator) 
     {
		 $validator = new Validator();
         $validator->notEmpty('person_name')
                   ->notEmpty('gender')
                   ->notEmpty('order_id')
                   ->notEmpty('birth_data_id');
      
     	 return $validator;
     }
	}

?>