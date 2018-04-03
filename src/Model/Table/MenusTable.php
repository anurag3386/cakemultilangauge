<?php 

	namespace App\Model\Table;
	use Cake\ORM\Table;
	use Cake\Validation\Validator;

	class MenusTable extends Table { 
	
		public function initialize(array $config) {


            $this->addBehavior('Tree', [
                'parent' => 'menu_id' // Use this instead of parent_id

            ]);




            $this->belongsTo('ParentMenus', [
                'className' => 'Menus',
                'foreignKey' => 'menu_id'
            ]);
            $this->hasMany('ChildMenus', [
                'className' => 'Menus',
                'foreignKey' => 'menu_id'
            ]);


            $this->addBehavior('Translate', ['fields' =>
												 ['title']]);
			$this->addBehavior('Timestamp');
			$this->addBehavior('Tree', [
				'parent' => 'menu_id',
				'recoverOrder' => ['sort_order' => 'ASC'] ]);
		}

		public function validationDefault(Validator $validator) {
			$validator = new Validator();
			$validator->notEmpty("title", 'This field is required');	
			$validator->notEmpty("menu_id", 'This field is required');	
			return $validator;	
		}

	}

?>