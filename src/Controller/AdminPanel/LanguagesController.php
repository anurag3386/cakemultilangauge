<?php 

	namespace App\Controller\AdminPanel;
	use App\Controller\AppController;
	use Cake\ORM\TableRegistry;

	class LanguagesController extends AppController
	{

		public $paginate = [
			'limit' => 25,
			'order' => ['Languages.sort_order' => 'asc']
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
		
		    $entity = $this->Languages->newEntity();

	        if ($this->request->is('post')) {
	           	$data = $this->Languages->patchEntity($entity, $this->request->data);
	            if ($this->Languages->save($data)) {
	                $this->Flash->success(__('Data has been saved successfully.'));
	                return $this->redirect(['action' => 'index']);
	            }
	            $this->Flash->error(__('Unable to save data.'));
	        }

	        $this->set('form', $entity);
		}

		public function edit($id) {

			$entity = $this->Languages->get($id);
			$this->set('data', $entity);

			if ($this->request->is('post')) { 
				$data = $this->Languages->patchEntity($entity, $this->request->data);
				if ($this->Languages->save($data)) {
	                $this->Flash->success(__('Data has been saved successfully.'));
	                return $this->redirect(['action' => 'index']);
	            }
	            $this->Flash->error(__('Unable to save data.'));
			}
			
			$entity = $this->Languages->newEntity();
			$this->set('form', $entity);
		}

		public function change() {
			$this->autoRender = false;				
			if($this->request->is('post')) {
				$query = $this->Languages->query();
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
                echo '<a href="javascript:changeStatus(\'languages\', '.$id.','.$stVal.');" class="'.$class.'"">'.$stType.'</a>';
                exit();
			}
		}

	}

?>