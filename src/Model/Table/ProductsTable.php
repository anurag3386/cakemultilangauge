<?php 

	namespace App\Model\Table;
	use Cake\ORM\Table;
	use Cake\Validation\Validator;

	class ProductsTable extends Table { 
	
		public function initialize(array $config) {
			$this->addBehavior('Translate', ['fields' =>
												 ['name', 'short_description', 'image', 'description', 'meta_title', 'meta_keywords', 'meta_description', 'seo_url']]);
			$this->addBehavior('Timestamp');
			$this->hasMany('ProductPrices');
			$this->belongsTo('Categories');
            $this->hasOne('PreviewReports');
            $this->hasMany('Orders');
            $this->hasMany('ProductLanguages');

		}

		public function validationDefault(Validator $validator) {
			$validator = new Validator();
			$validator->notEmpty("name", 'This field is required');	
			$validator->notEmpty("short_description", 'This field is required');
			$validator->notEmpty("description", 'This field is required');
			$validator->notEmpty("category_id", 'This field is required');
			$validator->notEmpty("seo_url", 'This field is required');
			$validator->notEmpty("pr_number", 'This field is required');
          
          // Modify this code in case of any confusion for Dansih mandatory fields

			$translationValidator = new Validator();
		    $translationValidator->requirePresence('name') 
		        				 ->notEmpty('short_description') 
		        				 ->notEmpty('description');
		
		    $validator->addNestedMany('_translations', $translationValidator)
		      	      //->requirePresence('_translations') // Commented by Stan Field on 5 April 2017 10:34 PM
		        	  ->notEmpty('_translations');
		  // End Here      	  
		
			return $validator;	
		}

	}


?>
