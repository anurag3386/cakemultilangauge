<?php 

	namespace App\Controller\AdminPanel;
	use App\Controller\AppController;
	use Cake\ORM\TableRegistry;
	use Cake\I18n\I18n;

	class EmailTemplatesController extends AppController
	{

		public $paginate = [
			'limit' => 25,
			'order' => ['EmailTemplates.sort_order' => 'asc']
		];

		public function initialize() {
			parent::initialize();
			$this->Auth->config('checkAuthIn', 'Controller.initialize');
			$this->loadComponent('Paginator');
			$this->viewBuilder()->layout('admin');
		}

		public function index() {	
			$this->set('data', $this->paginate());
		}

		public function add() {
		
		    $entity = $this->EmailTemplates->newEntity();

	        if ($this->request->is('post')) {

	        	$name = trim($this->request->data['name']);
	        	$this->request->data['short_code'] = preg_replace('#[ -]+#', '_', strtolower($name) );
	  
	           	$data = $this->EmailTemplates->patchEntity($entity, $this->request->data, [ 'translations' => true ]);
	            if ($this->EmailTemplates->save($data)) {
					 $this->Flash->success(__('Data has been saved successfully.'));
	                return $this->redirect(['action' => 'index']);
	            }
	            $this->Flash->error(__('Unable to save data.'));
	        }
	        $this->set('form', $entity);
		}

		public function edit($id) {

			$entity = $this->EmailTemplates->find('translations', ['conditions' => ['id' => $id] ] )->first();
			$this->set('data', $entity);
	
			if ($this->request->is('post')) { 
				$data = $this->EmailTemplates->patchEntity($entity, $this->request->data, [ 'translations' => true ]);
				if ($this->EmailTemplates->save($data)) {
	                $this->Flash->success(__('Data has been saved successfully.'));
	                return $this->redirect(['action' => 'index']);
	            }
	            $this->Flash->error(__('Unable to save data.'));
			}
			
			$entity = $this->EmailTemplates->newEntity();
			$this->set('form', $entity);
		}

		public function fetch() {
			$this->autoRender = false;	
			if ($this->request->is('ajax') ) {
				$id = $this->request->data['id'];
				$data = $this->EmailTemplates->get($id, ['fields' => ['content'] ]);
				$data = $this->replaceTemplateVariables($data['content']);
				echo $data;
				exit;
			}
		}

		private function replaceTemplateVariables($data)
		{
			$data = str_replace('{IMAGES_URL}', TEMPLATE_IMAGES_URL, $data);
			$data = str_replace('{SITE_URL}', SITE_URL, $data);
			return $data;
		}

	}

?>