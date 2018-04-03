<?php 

	namespace App\Model\Table;
	use Cake\ORM\Table;

	class LanguagesTable extends Table { 
	
		public function initialize(array $config) {
			$this->addBehavior('Timestamp');
			$this->hasMany('Orders',['foreignKey' => 'language_id']);
		}

	}

?>