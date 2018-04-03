<?php 

	namespace App\Controller\AdminPanel;
	use App\Controller\AppController;
	use Cake\ORM\TableRegistry;

	class SocialAppKeysController extends AppController
	{

		 public $paginate = [
		 	'limit' => 25,
		 	'order' => ['SocialAppKeys.sort_order' => 'asc']
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

		public function add() 
		{
		
		    $entity = $this->SocialAppKeys->newEntity();
   
	        if ($this->request->is('post')) {
				
			$data = $this->SocialAppKeys->patchEntity($entity, $this->request->data);

	  	 	   if ($this->SocialAppKeys->save($data)) 
	  	 	   {
	                 $this->Flash->success(__('Data has been saved successfully.'));
	                 return $this->redirect(['action' => 'index']);
	            }
	             $this->Flash->error(__('Unable to save data. Please fill all the required fields.'));
			 }

	         $this->set('form', $entity);

		}

		public function edit($id) {
               $entity = $this->SocialAppKeys->get($id);
			
			 if ($this->request->is(['post', 'put'])) {
			 	$data = $this->SocialAppKeys->patchEntity($entity, $this->request->data);
				 if ($this->SocialAppKeys->save($data)) {
	                $this->Flash->success(__('Data has been saved successfully.'));
	                return $this->redirect(['action' => 'index']);
	            }
	            $this->Flash->error(__('Unable to save data. Please fill all the required fields.'));
			 }
			  $this->set('form', $entity);
		}

		public function change() {
			$this->autoRender = false;	
			if($this->request->is('post')) {
				$query = $this->SocialAppKeys->query();
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
                echo '<a href="javascript:changeStatus(\'social-app-keys\', '.$id.','.$stVal.');" class="'.$class.'"">'.$stType.'</a>';
                exit();
			}
		}

	}

?>