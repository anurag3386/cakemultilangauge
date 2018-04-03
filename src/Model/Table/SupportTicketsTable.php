<?php 

	namespace App\Model\Table;
	use Cake\ORM\Table;
	use Cake\Validation\Validator;

	class SupportTicketsTable extends Table { 
	
		public function initialize(array $config) {
		    $this->addBehavior('Timestamp');
		    $this->hasOne('CommentFiles');
    	    //$this->addBehavior('Tree');
		}

		public function validationDefault(Validator $validator) {
			$validator = new Validator();
			$validator->notEmpty("subject", 'This field is required');	
			$validator->notEmpty("description", 'This field is required');	
			return $validator;	
		}

	}

?>