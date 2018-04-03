<?php 

	namespace App\Model\Table;
	use Cake\ORM\Table;

	class CategoriesTable extends Table { 
	
		public function initialize(array $config) {
			$this->addBehavior('Translate', ['fields' => ['name']]);
			$this->addBehavior('Timestamp');
			$this->hasMany('Products');

		}

	}

?>