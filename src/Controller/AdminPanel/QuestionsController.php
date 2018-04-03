<?php 
	namespace App\Controller\AdminPanel;
	use App\Controller\AppController;
	use Cake\ORM\TableRegistry;
	class QuestionsController extends AppController{
		public $paginate = [
			'limit' => 25,
			'order' => ['Questions.sort_order' => 'asc']
		];
		public function initialize() {
			parent::initialize();
			$this->Auth->config('checkAuthIn', 'Controller.initialize');
			$this->loadComponent('Paginator');
			$this->Auth->config('checkAuthIn', 'Controller.initialize');
			$this->viewBuilder()->layout('admin');
			$this->loadModel('Answers');
		}

		public function index() {	
			$this->set('data', $this->paginate());
		}

		public function add() {
			$entity = $this->Questions->newEntity($this->request->data,['associated' => ['Answers']]);
			if ($this->request->is('post')) {
				$errorStatus			=	false;
				$counter	=	0;
				$answers_sunsign_array		=	array();
				$sun_sign_code	 	=	$this->request->data['sun_sign_code'];
				if($sun_sign_code){
					$question_number						=	$this->getSunSignQuestionUniqueNumber($sun_sign_code);
					$this->request->data['question_number']	=	$question_number;
					$question_code							=	'#'.$sun_sign_code.'_'.$question_number;
					$this->request->data['question_code']		=	$question_code;
					
					foreach($this->request->data['answers'] as $key => $item) {
						$answer_code 											= 	$question_code.'-'.$item['sun_sign_code'];
						$this->request->data['answers'][$counter]['answer_code']	= 	$answer_code;
						if(!in_array($item['sun_sign_code'],$answers_sunsign_array)){
							$answers_sunsign_array[]			=	$item['sun_sign_code'];
						}else{
							$errorStatus			=	true;
							$this->Flash->error(__('Unable to save data. Answers can\'t belong to same sun sign.'));
						}
						$counter++;
					}
				}
				$data = $this->Questions->patchEntity($entity, $this->request->data, ['translations' => true,'associated' => ['Answers']]);
				if($errorStatus==false){
					if ($result 	= 	$this->Questions->save($data, ['associated' => ['Answers']])) {
						$this->Flash->success(__('Data has been saved successfully.'));
						return $this->redirect(['action' => 'index']);
					}
					$this->Flash->error(__('Unable to save data. Please fill all the required fields.'));
				}
				
			}
			$sunSignOptions = $this->getSunSignList();
			$this->set(compact('sunSignOptions'));
			$this->set('form', $entity);
		}
		public function edit($id) {
			$entity = $this->Questions->find('translations',['conditions' => ['Questions.id' => $id]])->contain(['Answers' => function ($query) {return $query->find('translations'); }])->first();
			$this->set('data', $entity);
			if ($this->request->is('post')) { 
				$errorStatus		=	 false;
				$counter	=	0;
				$answers_sunsign_array	=	array();
				
				$sun_sign_code = $this->request->data['sun_sign_code'];
				$old_sun_sign_code = $this->request->data['old_sun_sign_code'];
				if($sun_sign_code && $old_sun_sign_code!=$sun_sign_code){
					$question_number						=	$this->getSunSignQuestionUniqueNumber($sun_sign_code);
					$this->request->data['question_number']	=	$question_number;
					$question_code							=	'#'.$sun_sign_code.'_'.$question_number;
					$this->request->data['question_code']		=	$question_code;
				}else{
					$question_number				=	$entity['question_number'];
					$question_code					=	'#'.$sun_sign_code.'_'.$question_number;
				}
				
				
				foreach($this->request->data['answers'] as $key => $item) {
					$answer_code 											= 	$question_code.'-'.$item['sun_sign_code'];
					$this->request->data['answers'][$counter]['answer_code']	= 	$answer_code;
					if(!in_array($item['sun_sign_code'],$answers_sunsign_array)){
						$answers_sunsign_array[]			=	$item['sun_sign_code'];
					}else{
						$errorStatus			=	true;
						$this->Flash->error(__('Unable to save data. Answers can\'t belong to same sun sign.'));
					}
					$counter++;
				}
				if($errorStatus==false){
					$data = $this->Questions->patchEntity($entity, $this->request->data, ['translations' => true,'associated' => ['Answers']]);
					if ($this->Questions->save($data, ['associated' => ['Answers']])) {
						$this->Flash->success(__('Data has been saved successfully.'));
						return $this->redirect(['action' => 'index']);
					}
					$this->Flash->error(__('Unable to save data. Please fill all the required fields.'));
				}
			}
			$entity = $this->Questions->newEntity();
			
			$sunSignOptions = $this->getSunSignList();
			$this->set(compact('sunSignOptions'));
			$this->set('form', $entity);
			
		}
		public function change() {
			$this->autoRender = false;	
			if($this->request->is('post')) {
				$query = $this->Questions->query();
				$status = $this->request->data['val'];
				$id = $this->request->data['id'];
				$query->update()  
						->set(['status' => $status])
						->where(['id' => $id])
						->execute();
				$class="btn btn-danger btn-xs";
				$stType="Inactive";
				$stVal=1;
				if($status == 1)
				{
					$class="btn btn-success btn-xs";
					$stType="Active";
					$stVal=2;
				}
				echo '<a href="javascript:changeStatus(\'questions\', '.$id.','.$stVal.');" class="'.$class.'">'.$stType.'</a>';
				exit();
			}
		}
		function getSunSignQuestionUniqueNumber($sun_sign_code='AR'){
			$TotalSunsignQuesions 	= 	$this->Questions->find('all')
									->where(['sun_sign_code' => $sun_sign_code])
									->count();
			$question_number 	=	$TotalSunsignQuesions +1;	
			$question_number	=	str_pad($question_number, 2, '0', STR_PAD_LEFT);	
			$question_number	=	$this->checkUniqueNumber($sun_sign_code,$question_number);
			return $question_number;	
		}
		function checkUniqueNumber($sun_sign_code,$question_number){
			$TotalSunsignQuesions 	= 	$this->Questions->find('all')
									->where(['sun_sign_code' => $sun_sign_code,'question_number'=>$question_number])
									->count();
			if($TotalSunsignQuesions > 0){
				$question_number 		=	(int)$question_number;
				$question_number		=	$question_number +1; 	
				$question_number		=	str_pad($question_number, 2, '0', STR_PAD_LEFT);	
				$question_number	=	$this->checkUniqueNumber($sun_sign_code,$question_number);
			}			
			return $question_number;	
		}
		public function getSunSignList(){
			$sunSineArr			=	array();
			$sunSineArr['AR']		=	'Aries';
			$sunSineArr['TA']		=	'Taurus';
			$sunSineArr['GE']		=	'Gemini';
			$sunSineArr['CN']		=	'Cancer';
			$sunSineArr['LE']		=	'Leo';
			$sunSineArr['VI']		=	'Vigro';
			$sunSineArr['LI']		=	'Libra';
			$sunSineArr['SC']		=	'Scorpio';
			$sunSineArr['SG']		=	'Sagittarius';
			$sunSineArr['CP']		=	'Capricorn';
			$sunSineArr['AQ']		=	'Aquarius';
			$sunSineArr['PI']		=	'Pisces';
			return $sunSineArr;
		}
		public function getSunsineName($code='AR'){
			$array	=	$this->getSunSignList();
			return $array[$code];
		}
		
	}

?>