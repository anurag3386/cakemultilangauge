<?php 

	namespace App\Controller\AdminPanel;
	use App\Controller\AppController;
	use Cake\ORM\TableRegistry;

	class EventsController extends AppController
	{

		public $paginate = [
			'limit' => 25,
			'order' => ['Events.sort_order' => 'asc']
		];

		private $uploadDir = WWW_ROOT.'uploads'.DS.'events'.DS;

		public function initialize() {
			parent::initialize();

			$this->Auth->config('checkAuthIn', 'Controller.initialize');
			$this->loadComponent('Paginator');
			$this->viewBuilder()->layout('admin');

			$this->loadComponent('FileUpload.FileUpload',[
	            'defaultThumb'=>[
	                'sm' => [100,100]
	            ],
	            'uploadDir' => $this->uploadDir,
	            'maintainAspectRation'=>true
	        ]);
		}

		public function index() {	
			$this->set('data', $this->paginate());
		}

		public function add() {
		
		    $entity = $this->Events->newEntity();
   
	        if ($this->request->is('post')) {

				if($this->request->data['image']) {
					$this->request->data['image'] = $this->FileUpload->doFileUpload($this->request->data['image']);
				}
				
				$data = $this->Events->patchEntity($entity, $this->request->data, ['translations' => true]);

				 if ($this->Events->save($data)) {
	                $this->Flash->success(__('Data has been saved successfully.'));
	                return $this->redirect(['action' => 'index']);
	            }
	            $this->Flash->error(__('Unable to save data. Please fill all the required fields.'));
			}

	        $this->set('form', $entity);

		}

		public function edit($id) {

			$entity = $this->Events->find('translations', ['conditions' => ['id' => $id] ] )->first();
			$this->set('data', $entity);

			if ($this->request->is('post')) {
				
				if($this->request->data['image']['name']) {
					$this->request->data['image'] = $this->FileUpload->doFileUpload($this->request->data['image']);
					@$this->deleteImage($this->request->data['prev_image']);
				}
				else {
					$this->request->data['image'] = $this->request->data['prev_image'];
				}
				unset($this->request->data['prev_image']);
				
				$data = $this->Events->patchEntity($entity, $this->request->data, ['translations' => true]);

				 if ($this->Events->save($data)) {
	                $this->Flash->success(__('Data has been saved successfully.'));
	                return $this->redirect(['action' => 'index']);
	            }
	            $this->Flash->error(__('Unable to save data. Please fill all the required fields.'));
			}
			
			$entity = $this->Events->newEntity();
			$this->set('form', $entity);
		}

		public function change() {
			$this->autoRender = false;	
			if($this->request->is('post')) {
				$query = $this->Events->query();
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
                echo '<a href="javascript:changeStatus(\'events\', '.$id.','.$stVal.');" class="'.$class.'"">'.$stType.'</a>';
                exit();
			}
		}

		private function deleteImage( $image_path ) {
			@unlink( $this->uploadDir.$image_path );
			@unlink( $this->uploadDir.'thumb/sm/'.$image_path );
		}


	}

?>