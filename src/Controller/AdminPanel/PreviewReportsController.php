<?php 

	namespace App\Controller\AdminPanel;
	use App\Controller\AppController;
	use Cake\ORM\TableRegistry;
	use Cake\I18n\I18n;

	class PreviewReportsController extends AppController
	{

		public $paginate = [
			'limit' => 25,
			'order' => ['PreviewReports.sort_order' => 'asc']
		];

		private $uploadDir = WWW_ROOT.'uploads'.DS.'preview_reports'.DS;

		public function initialize() {
			parent::initialize();
			$this->Auth->config('checkAuthIn', 'Controller.initialize');
			$this->loadComponent('Paginator');
			$this->loadModel('Products');
			$this->viewBuilder()->layout('admin');
			$this->loadComponent('FileUpload.FileUpload',[
	            'defaultThumb'=>[
	                'sm' => [100,100]
	            ],
	            'uploadDir' => $this->uploadDir,
	            'maintainAspectRation'=>true,
	            'allowedTypes' => array('pdf' => array('application/pdf'))
	        ]);

		}

		public function index() {	
			$this->set('data', $this->paginate());
		}

		public function add() {
		
		    $entity = $this->PreviewReports->newEntity();
            $products = $this->Products->find('translations')
                                       ->contain('Categories')
                                       ->where(['Categories.name' => 'Reports', 'Products.status' => 1, 'Categories.status' => 1])
                                       ->select(['id', 'name'])
                                       ->toArray();

	        if ($this->request->is('post')) {

	        	if($this->request->data['image']) {
					$this->request->data['image'] = $this->FileUpload->doFileUpload($this->request->data['image']);

				}
				if($this->request->data['pdf']) {
					$this->request->data['pdf'] = $this->FileUpload->doFileUpload($this->request->data['pdf']);

				}

				if($this->request->data['_translations']['da']['image']) {
					$this->request->data['_translations']['da']['image'] = $this->FileUpload->doFileUpload($this->request->data['_translations']['da']['image']);

				}
				if($this->request->data['_translations']['da']['pdf']) {
					$this->request->data['_translations']['da']['pdf'] = $this->FileUpload->doFileUpload($this->request->data['_translations']['da']['pdf']);

				}

	           	$data = $this->PreviewReports->patchEntity($entity, $this->request->data, [ 'translations' => true ]);
	            if ($this->PreviewReports->save($data)) {
					 $this->Flash->success(__('Data has been saved successfully.'));
	                return $this->redirect(['action' => 'index']);
	            }
	            $this->Flash->error(__('Unable to save data.'));
	        }
	        $this->set(compact('products'));
	        $this->set('form', $entity);
		}

		public function edit($id) {

			$entity = $this->PreviewReports->find('translations', ['conditions' => ['id' => $id] ] )->first();

			

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

				if($this->request->data['pdf']['name']) {
					$this->request->data['pdf'] = $this->FileUpload->doFileUpload($this->request->data['pdf']);
					@$this->deleteImage($this->request->data['prev_pdf']);
				}
				else {
					$this->request->data['pdf'] = $this->request->data['prev_pdf'];
				}
				unset($this->request->data['prev_pdf']);

				if($this->request->data['_translations']['da']['image']['name']) {
					$this->request->data['_translations']['da']['image'] = $this->FileUpload->doFileUpload($this->request->data['_translations']['da']['image']);
					@$this->deleteImage($this->request->data['_translations']['da']['prev_image']);
				}
				else {
					$this->request->data['_translations']['da']['image'] = $this->request->data['_translations']['da']['prev_image'];
				}
				unset($this->request->data['_translations']['da']['prev_image']);

				if($this->request->data['_translations']['da']['pdf']['name']) {
					$this->request->data['_translations']['da']['pdf'] = $this->FileUpload->doFileUpload($this->request->data['_translations']['da']['pdf']);
					@$this->deleteImage($this->request->data['_translations']['da']['prev_pdf']);
				}
				else {
					$this->request->data['_translations']['da']['pdf'] = $this->request->data['_translations']['da']['prev_pdf'];
				}
				unset($this->request->data['_translations']['da']['prev_pdf']);
            
				$data = $this->PreviewReports->patchEntity($entity, $this->request->data, [ 'translations' => true ]);

				if ($this->PreviewReports->save($data)) {
	                $this->Flash->success(__('Data has been saved successfully.'));
	                return $this->redirect(['action' => 'index']);
	            }
	            $this->Flash->error(__('Unable to save data.'));
			}
			
			$entity = $this->PreviewReports->newEntity();
			$this->set('form', $entity);
		}

		public function change() {
			$this->autoRender = false;	
			if($this->request->is('post')) {
				$query = $this->PreviewReports->query();
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
                echo '<a href="javascript:changeStatus(\'preview-reports\', '.$id.','.$stVal.');" class="'.$class.'"">'.$stType.'</a>';
                exit();
			}
		}

		private function deleteImage( $image_path ) {
			@unlink( $this->uploadDir.$image_path );
			@unlink( $this->uploadDir.'thumb/sm/'.$image_path );
		}

	}

?>