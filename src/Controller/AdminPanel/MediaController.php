<?php 

	namespace App\Controller\AdminPanel;
	use App\Controller\AppController;
	use Cake\ORM\TableRegistry;
	use Cake\I18n\I18n;
	use Cake\Utility\Inflector;

	class MediaController extends AppController
	{

		public $paginate = [
			'limit' => 25,
			'order' => ['Media.sort_order' => 'asc']
		];
     	private $uploadDir = WWW_ROOT.'uploads'.DS.'media'.DS;
		public function initialize() {
			parent::initialize();
			$this->Auth->config('checkAuthIn', 'Controller.initialize');
			$this->loadComponent('Paginator');
			$this->loadModel('Categories');
			$this->loadComponent('FileUpload.FileUpload',[
	            'defaultThumb'=>[
	                'sm' => [100,100]
	            ],
	            'uploadDir' => $this->uploadDir,
	            'maintainAspectRation'=>true
	        ]);
			$this->viewBuilder()->layout('admin');
		}

		public function index() {	
			$this->set('data', $this->paginate());
		}

		

		public function add() {

		     $entity = $this->Media->newEntity();
		     $categoryOptions = $this->Categories->find('list')
		                                         ->where(['Categories.slug IN ' => ['audio', 'video'] ])
		                                         ->toArray();
		     $this->set(compact('categoryOptions'));
	         $this->set('form', $entity);
	         if ($this->request->is('post') ) {
	           if(!empty($this->request->data))
	           {
	        	 if(is_array($this->request->data['path']))
	         	 {

                   if(file_exists($this->uploadDir . $this->request->data['path']['name']))
					{
						$this->Flash->error(__('File already exists'));
						$this->request->data['category_id'] = "";
 					}
					else
					{

						$ext = pathinfo($this->request->data['path']['name'], PATHINFO_EXTENSION);
						if( strtolower(trim($ext)) == 'ogg'  || strtolower(trim($ext)) == 'mp3' || strtolower(trim($ext)) == 'wav' )
						{
						$this->request->data['path'] = $this->FileUpload->doFileUpload($this->request->data['path']);
    	            	$entity = $this->Media->patchEntity($entity, $this->request->data, ['translations' => true ]);
		                  if($this->Media->save($entity))
		                  {
		                  	  $this->Flash->success(__('Data has been saved successfully.'));
			                  return $this->redirect(['action' => 'index']);
		                  }
		                  else
		                  {
		                  	 $this->Flash->error(__('Unable to save data.'));
		                  }
		               } else {
		               	    $this->request->data['category_id'] = '';
						    return $this->Flash->error('Wrong audio file format. Only mp3, ogg and wav audio formats are allowed');
						}
                    }
                }
                else{

						  $entity = $this->Media->patchEntity($entity, $this->request->data, ['translations' => true ]);
		                  if($this->Media->save($entity))
		                  {
		                  	  $this->Flash->success(__('Data has been saved successfully.'));
			                  return $this->redirect(['action' => 'index']);
		                  }
		                  else
		                  {
		                  	 $this->Flash->error(__('Unable to save data.'));
		                  }
                    }
	         }
	         else
	         {
	         	 $this->Flash->error(__('Maximum file size allowed is 2Mb.'));
	         }
	     }
	         
		}

		public function edit($id) {
			$entity = $this->Media->find('translations', ['conditions' => ['id' => $id] ] )->first();
			$categoryOptions = $this->Categories->find('list')
												->where(['Categories.slug IN ' => ['audio', 'video'] ])
												->toArray();
        	$this->set(compact('categoryOptions'));
        	$this->set('form', $entity);

        	if ($this->request->is(['put', 'post'])) {
        		if(!empty($this->request->data)) {
		        	if ($this->request->data['category'] == 24) { // For only Audio file
	        			if($this->request->data['path']['name']) {
	         	 			$ext = pathinfo($this->request->data['path']['name'], PATHINFO_EXTENSION);
	         	 			if( strtolower(trim($ext)) == 'ogg'  || strtolower(trim($ext)) == 'mp3' || strtolower(trim($ext)) == 'wav' ) {
	         	 				//die('reach');
	         	 				$this->request->data['path'] = $this->FileUpload->doFileUpload($this->request->data['path']);
	         	 				@$this->deleteImage($this->request->data['prev_image']);
	         	 			} else {
	         	 				return $this->Flash->error('Wrong audio file format. Only mp3, ogg and wav audio formats are allowed');
	         	 			}
	         	 		} else {
	         	 			$this->request->data['path'] = $this->request->data['prev_image'];
	         	 		}
	         	 	}
				
					$entity = $this->Media->patchEntity($entity, $this->request->data, [ 'translations' => true ]);
					if ($this->Media->save($entity)) {
						$this->Flash->success(__('Data has been saved successfully.'));
						return $this->redirect(['action' => 'index']);
					} else {
						$this->Flash->error(__('Unable to save data.'));
					}
				} else {
					$this->Flash->error(__('Maximum file size allowed is 2Mb.'));
				}
			}
		}

		public function change() {
			$this->autoRender = false;	
			if($this->request->is('post')) {
				$query = $this->Media->query();
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
                echo '<a href="javascript:changeStatus(\'media\', '.$id.','.$stVal.');" class="'.$class.'"">'.$stType.'</a>';
                exit();
			}
		}
		private function deleteImage( $image_path ) {
			@unlink( $this->uploadDir.$image_path );
			//echo $this->uploadDir.$image_path;
			//die('reach');
			//@unlink( $this->uploadDir.'thumb/sm/'.$image_path );
		}

	}

?>