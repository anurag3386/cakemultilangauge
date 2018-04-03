<?php 

	namespace App\Model\Table;
	use Cake\ORM\Table;

	class DeliveryOptionsTable extends Table { 
	
		public function initialize(array $config) {
			$this->addBehavior('Timestamp');
		   	$this->hasMany('Orders',[
                  'foreignKey' => 'delivery_option',
                  'bindingKey' =>  'id' 
		   		]);
	
		}

	}

?>