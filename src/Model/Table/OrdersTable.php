<?php 

	namespace App\Model\Table;
	use Cake\Validation\Validator;
	use Cake\ORM\Table;

	class OrdersTable extends Table { 
	
		public function initialize(array $config) {
			$this->addBehavior('Timestamp');
			$this->belongsTo('Products');
			$this->belongsTo('DeliveryOptions',['propertyName' => 'delivery_options',
					'foreignKey' => 'delivery_option',
					]);
		
			$this->belongsTo('Currencies');


			$this->belongsTo('States',[
              'foreignKey' => 'order_status'

				]);
			$this->belongsTo('Users',[
                   'foreignKey'   => 'user_id'
				 ]);

			$this->belongsTo('Languages',[
                   'foreignKey'   => 'language_id'
				 ]);

			$this->belongsTo('Profiles',[
                   'foreignKey'   => 'user_id',
                   'bindingKey'   => 'user_id'
				 ]);

			$this->belongsTo('GuestUserProductDetails',[
                   'foreignKey'   => 'email',
                   'bindingKey'   => 'email'
				 ]);

			$this->belongsTo('ProductTypes',[
                   'foreignKey'   => 'product_type',
                   'propertyName' => 'product_types'
				 ]);
			$this->hasOne('Birthdata', ['dependent' => true, 'cascadeCallbacks' => true]);

			$this->hasOne('OrderShippings');
            $this->hasOne('OrderTransactions');
			$this->belongsTo('Subscribes',[
                   'foreignKey'   => 'order_id'
				 ]);
		}

		public function validationDefault(Validator $validator) {
			$validator = new Validator();
			$validator->notEmpty("email", 'This field is required')
					  ->email('email', 'Email id is not valid')
					  ->notEmpty('product_id', 'This field is required')
                      ->notEmpty('price', 'This field is required')
                      ->notEmpty('user_id', 'This field is required')
                      ->notEmpty('product_type', 'This field is required')
                      ->notEmpty('language_id', 'This field is required')
                      ->notEmpty('portal_id', 'This field is required');

			return $validator;	
		}


	}

?>