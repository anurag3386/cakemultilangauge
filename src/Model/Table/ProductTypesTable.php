<?php 

	namespace App\Model\Table;
	use Cake\ORM\Table;

	class ProductTypesTable extends Table { 
	
		public function initialize(array $config) {
			$this->addBehavior('Timestamp');
	 	     $this->hasMany('Orders',['foreignKey' => 'product_type',
             		
             	]);
            
		}

	}

?>