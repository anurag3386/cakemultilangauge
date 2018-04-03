<?php 
	namespace App\Model\Table;
	use Cake\ORM\Table;
	use Cake\Validation\Validator;
	class QuestionsTable extends Table { 
		public function initialize(array $config) {
			$this->addBehavior('Translate', ['fields' => ['title']]);
			$this->addBehavior('Timestamp');
			$this->hasMany('Answers', [
				'foreignKey' => 'question_id',
				'dependent' => true,
			]);
		}
		public function validationDefault(Validator $validator) {
			$validator = new Validator();
			$validator->notEmpty("title", 'This field is required');	
			$validator->notEmpty("sun_sign_code", 'This field is required');	
			return $validator;	
		}
	}
?>