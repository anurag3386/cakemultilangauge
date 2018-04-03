<?php 

	namespace App\Model\Table;
	use Cake\ORM\Table;
	use Cake\Validation\Validator;

	class ProfilesTable extends Table {

	    public function  initialize(array $config)
        {
            parent::initialize($config);
            $this->addBehavior('Timestamp');
            $this->hasMany('Orders',['foreignKey' => 'user_id',
            		'bindingKey' => 'user_id'/*,
            		'dependent' => true,
    				'cascadeCallbacks' => true,*/
            	]);
            //$this->hasOne('AnotherPersons',['bindingKey' =>'added_by']);
        }

        public function validationDefault(Validator $validator) {
			$validator = new Validator();
			$validator->notEmpty("language_id", 'This field is required');
			$validator->notEmpty("first_name", 'This field is required');
			$validator->notEmpty("last_name", 'This field is required');
			$validator->notEmpty("gender", 'This field is required');
			$validator->notEmpty("address", 'This field is required');		
			$validator->notEmpty("country_id", 'This field is required');
			$validator->notEmpty("city_id", 'This field is required');
			$validator->notEmpty("state", 'This field is required');
			$validator->notEmpty("zip", 'This field is required');
			return $validator;	
		}

	}

?>