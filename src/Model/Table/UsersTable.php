<?php

	namespace App\Model\Table;
	use Cake\ORM\Table;
	use Cake\Validation\Validator;
	use Cake\Auth\DefaultPasswordHasher;

	class UsersTable extends Table {

		public function initialize(array $config) {
			$this->displayField('username');
			$this->hasOne('Profiles', ['dependent' => true, 'cascadeCallbacks' => true]);
			$this->hasOne('Profiles');
			$this->hasOne('BirthDetails');
			$this->hasOne('Subscriptions');
			$this->addBehavior('Timestamp');
            $this->hasOne('BirthDetails',

                [
                    'foreignKey' => 'user_id'

                ]);

            $this->hasMany('Orders',
           			[
           			   'foreignKey' => 'user_id'
           			]
            	);



            $this->hasOne('Subscriptions',

                [
                    'foreignKey' => 'user_id'

                ]);
		}

		public function validationDefault(Validator $validator) {
			$validator = new Validator();

			$validator->notEmpty("username", 'This field is required')
					  ->notEmpty('profile.city_id', 'This field is required')
					  ->notEmpty('birth_detail.city_id', 'This field is required')
					  ->notEmpty('city', 'This field is required')
					  ->notEmpty ('first_name', 'Please fill')
					  ;
			/*$validator->add('old_password', [
					'length' => [
	                    'rule' => ['minLength', 6],
	                    'message' => 'The old password have to be at least 6 characters!',
                	],
                	'matchOldPassword' => [
	                    'rule' => [$this, 'matchOldPassword'],
	                    'required' => true,
	                    'message' => 'The old password does not match!',
                	]
				]);*/

			return $validator;	
		}

		/*public function matchOldPassword ($value, $context) {
			$hasher = new DefaultPasswordHasher();
			$data = $this->find()->select(['id', 'password'])->where(['id' => $context['data']['id']])->first();
			echo $hasher->hash($context['data']['old_password']).'<br>';
			echo $data['password']; die;
			if ($data['password'] == $hasher->hash($context['data']['old_password'])) { 
	             return true;
	        } else {
	         return false;
	        }
		}*/


	}

?>