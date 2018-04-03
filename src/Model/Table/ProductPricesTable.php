<?php 

	namespace App\Model\Table;
	use Cake\ORM\Table;
	use Cake\Validation\Validator;

	class ProductPricesTable extends Table { 
	
		public function initialize(array $config) {
			$this->addBehavior('Timestamp');
			$this->belongsTo('Products');
			$this->belongsTo('Currencies');
		}

		public function validationDefault(Validator $validator) {
			$validator = new Validator();
			$validator->notEmpty("usd", 'This field is required');	
			$validator->notEmpty("gbp", 'This field is required');
			$validator->notEmpty("dkk", 'This field is required');
			$validator->notEmpty("eur", 'This field is required');
			$validator->notEmpty("sek", 'This field is required');
			return $validator;	
		}

	}

?>