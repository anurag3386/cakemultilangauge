<?php 

	namespace App\Model\Table;
	use Cake\ORM\Table;
	use Cake\Validation\Validator;

	class ProductLanguagesTable extends Table { 
	
		public function initialize(array $config) 
		{
			$this->addBehavior('Timestamp');
			$this->belongsTo('Products');
			$this->belongsTo('Languages');

		}
	}


?>
