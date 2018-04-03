<?php 

	namespace App\Controller\AdminPanel;
	use App\Controller\AppController;
	use Cake\ORM\TableRegistry;

	class MenusController extends AppController
	{
		
		public $paginate = [
			'limit' => 25,
			'conditions' => ['Menus.menu_type' => 'bottom',
						'Menus.menu_id != ' => 0
					],
			'order' => ['Menus.sort_order' => 'asc']
		];

		public function initialize() {
			parent::initialize();
			$this->Auth->config('checkAuthIn', 'Controller.initialize');
			$this->loadComponent('Paginator');
			$this->viewBuilder()->layout('admin');
			$this->loadModel('Pages');
		}

		public function index() {
			$list = $this->Menus->recover();
			$this->set('data', $this->paginate());
		}

		private function makeFooterMenuUrl($data){
			return str_replace(' ', '-', strtolower($data));
		}

		public function add() {
		
			$entity = $this->Menus->newEntity();
			
			/*$menuOptions = $this->Menus->find('list', ['conditions' => ['menu_type' => 'bottom', 'status' => 1, 'menu_id' => 0], 'order' => ['id' => 'asc']])->toArray();*/
			$menuOptions = $this->Menus->find('list', ['conditions' => ['Menus.menu_type' => 'bottom', 'Menus.status' => 1, 'Menus.menu_id IS' => NULL], 'order' => ['Menus.id' => 'asc']])->toArray();
			$pageOptions = $this->Pages->find('list', ['conditions' => ['status' => 1], 'order' => ['title' => 'asc'] ] );

	        if ($this->request->is('post')) {
	        	if(empty($this->request->data['url'])){
	        		$this->request->data['url'] = $this->makeFooterMenuUrl($this->request->data['title']);
	        	}
	        	$data = $this->Menus->patchEntity($entity, $this->request->data, ['translations' => true]);
	        	
	            if ($this->Menus->save($data)) {
	                $this->Flash->success(__('Data has been saved successfully.'));
	                return $this->redirect(['action' => 'index']);
	            }
	            $this->Flash->error(__('Unable to save data. Please fill all the required fields.'));
	        }

	       
	        $this->set('form', $entity);
	        $this->set(compact('menuOptions'));
	        $this->set(compact('pageOptions'));
		}

		public function edit($id) {

			$entity = $this->Menus->find('translations', ['conditions' => ['Menus.id' => $id] ] )->first();
			$this->set('data', $entity);

			$menuOptions = $this->Menus->find('list', ['conditions' => ['Menus.menu_type' => 'bottom', 'Menus.status' => 1, 'Menus.menu_id IS' => NULL], 'order' => ['Menus.id' => 'asc']])->toArray();
			$pageOptions = $this->Pages->find('list', ['conditions' => ['Pages.status' => 1], 'order' => ['Pages.title' => 'asc'] ] );

	        if ($this->request->is('post')) {
	        	if(empty($this->request->data['url'])){
	        		$this->request->data['url'] = $this->makeFooterMenuUrl($this->request->data['title']);
	        	}
	        	$data = $this->Menus->patchEntity($entity, $this->request->data, ['translations' => true]);

	            if ($this->Menus->save($data)) {
	                $this->Flash->success(__('Data has been saved successfully.'));
	                return $this->redirect(['action' => 'index']);
	            }
	            $this->Flash->error(__('Unable to save data. Please fill all the required fields.'));
	        }

	        $entity = $this->Menus->newEntity();
	        $this->set('form', $entity);

	        $this->set(compact('menuOptions'));
	        $this->set(compact('pageOptions'));
			
		}

		public function change() {
			$this->autoRender = false;	
			if($this->request->is('post')) {
				$query = $this->Menus->query();
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
                echo '<a href="javascript:changeStatus(\'menus\', '.$id.','.$stVal.');" class="'.$class.'"">'.$stType.'</a>';
                exit();
			}
		}

	}

?>