<?php 

	namespace App\Model\Table;
	use Cake\ORM\Table;
	use Cake\Validation\Validator;

	class MiniBlogsTable extends Table { 
	
		public function initialize(array $config) {
			$this->addBehavior('Translate', ['fields' =>
												 ['title', 'description', 'image', 'meta_title', 'meta_keywords', 'meta_description', 'slug']
												 ]);
			$this->addBehavior('Timestamp');
		}

		public function validationDefault(Validator $validator) {
			$validator = new Validator();
			$validator->notEmpty("title", 'This field is required');	
			$validator->notEmpty("description", 'This field is required');	
			/*$validator->notEmpty('image', __('Please choose image'))
			          ->requirePresence('image', 'create')
        		      ->add('image', 'validFormat', [
											            'rule' => ['custom', '([^\s]+(\.(?i)(jpg|png|gif|jpeg))$)'], 
											            'message' => __('These files extension are allowed: .jpg, .png, .gif,jpeg')
											        ]);*/

			$translationValidator = new Validator();
		    $translationValidator->requirePresence('title') 
		        				 ->notEmpty('title') 
		        				 ->notEmpty('description');
		        
			/*$translationValidator->notEmpty('image', __('Please choose image'))
						         ->requirePresence('image', 'create')
        		      			 ->add('image', 'validFormat', [
											            'rule' => ['custom', '([^\s]+(\.(?i)(jpg|png|gif|jpeg))$)'], 
											            'message' => __('These files extension are allowed: .jpg, .png, .gif,jpeg')
											        ]);*/

	      
		    $validator->addNestedMany('_translations', $translationValidator)
		      	      ->requirePresence('_translations')
		        	  ->notEmpty('_translations');
		    return $validator;	
		}

	}

?>