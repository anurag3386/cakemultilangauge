<?php 

	namespace App\Controller\AdminPanel;
	use App\Controller\AppController;
	use Cake\ORM\TableRegistry;

	class PagesController extends AppController
	{

		public $paginate = [
			'limit' => 25,
			'order' => ['Pages.sort_order' => 'asc']
		];

		public function initialize() {
			parent::initialize();
			$this->Auth->config('checkAuthIn', 'Controller.initialize');
			$this->loadComponent('Paginator');
            $this->Auth->config('checkAuthIn', 'Controller.initialize');
			$this->viewBuilder()->layout('admin');
		}

		public function index() {	
			$this->set('data', $this->paginate());
		}

		public function add() {
		
		    $entity = $this->Pages->newEntity();

	        if ($this->request->is('post')) {
	        	$seo_url = $this->request->data['seo_url'];
				$check_seo = $this->validateSeo($seo_url, 0);
				if($check_seo == 0) {		
					$data = $this->Pages->patchEntity($entity, $this->request->data, ['translations' => true]);
		           
		            if ($this->Pages->save($data)) {
		                $this->Flash->success(__('Data has been saved successfully.'));
		                return $this->redirect(['action' => 'index']);
		            }
	            	$this->Flash->error(__('Unable to save data. Please fill all the required fields.'));
	            }
	            else {
					$this->Flash->error(__('Unable to save data. SEO URL is already being used.'));
				}	
	        }

	        $this->set('form', $entity);
		}

		public function edit($id) {

			$entity = $this->Pages->find('translations', ['conditions' => ['Pages.id' => $id] ] )->first();
			$this->set('data', $entity);

			if ($this->request->is('post')) { 

				$seo_url = $this->request->data['seo_url'];

				$check_seo = $this->validateSeo($seo_url, $id);

				if($check_seo == 0) {

					//unset($this->request->data['seo_url']);
					if(empty($this->request->data['seo_url'])) {
						unset($this->request->data['seo_url']);
					}

					$data = $this->Pages->patchEntity($entity, $this->request->data, ['translations' => true]);
					if ($this->Pages->save($data)) {
		                $this->Flash->success(__('Data has been saved successfully.'));
		                return $this->redirect(['action' => 'index']);
		            }
		            $this->Flash->error(__('Unable to save data. Please fill all the required fields.'));
		        }
		        else {
					$this->Flash->error(__('Unable to save data. SEO URL is already being used.'));
				}	    
			}
			
			$entity = $this->Pages->newEntity();
			$this->set('form', $entity);
		}

		public function change() {
			$this->autoRender = false;	
			if($this->request->is('post')) {
				$query = $this->Pages->query();
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
                echo '<a href="javascript:changeStatus(\'pages\', '.$id.','.$stVal.');" class="'.$class.'">'.$stType.'</a>';
                exit();
			}
		}

		private function validateSeo($url, $id) {
			$query = $this->Pages->find('all', ['conditions' => ['seo_url' => $url, 'Pages.id !=' => $id]]);
			$number = $query->count();
			return $number;
		}


	}

?>