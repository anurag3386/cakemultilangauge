<?php 

	namespace App\Controller\AdminPanel;
	use App\Controller\AppController;
	use Cake\ORM\TableRegistry;
	use Cake\I18n\I18n;
	use Cake\Utility\Inflector;

	class CategoriesController extends AppController
	{

		public $paginate = [
			'limit' => 25,
			'order' => ['Categories.sort_order' => 'asc']
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
		
		    $entity = $this->Categories->newEntity();
	        if ($this->request->is('post')) {
	        	$name = trim($this->request->data['name']);
	        	$this->request->data['slug'] = Inflector::slug(strtolower($name) );
	           	$data = $this->Categories->patchEntity($entity, $this->request->data, [ 'translations' => true ]);
	            if ($this->Categories->save($data)) {
					 $this->Flash->success(__('Data has been saved successfully.'));
	                return $this->redirect(['action' => 'index']);
	            }
	            $this->Flash->error(__('Unable to save data.'));
	        }
	        $this->set('form', $entity);
		}

		public function edit($id) {

			$entity = $this->Categories->find('translations', ['conditions' => ['id' => $id] ] )->first();
			$this->set('data', $entity);

			if ($this->request->is('post')) { 
				$data = $this->Categories->patchEntity($entity, $this->request->data, [ 'translations' => true ]);
				if ($this->Categories->save($data)) {
	                $this->Flash->success(__('Data has been saved successfully.'));
	                return $this->redirect(['action' => 'index']);
	            }
	            $this->Flash->error(__('Unable to save data.'));
			}
			
			$entity = $this->Categories->newEntity();
			$this->set('form', $entity);
		}

		public function change() {
			$this->autoRender = false;	
			if($this->request->is('post')) {
				$query = $this->Categories->query();
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
                echo '<a href="javascript:changeStatus(\'categories\', '.$id.','.$stVal.');" class="'.$class.'"">'.$stType.'</a>';
                exit();
			}
		}

	}

?>