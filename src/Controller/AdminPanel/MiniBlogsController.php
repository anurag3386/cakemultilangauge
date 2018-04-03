<?php 

	namespace App\Controller\AdminPanel;
	use App\Controller\AppController;
	use Cake\ORM\TableRegistry;
	use Cake\Utility\Inflector;

	class MiniBlogsController extends AppController
	{
 
      public $paginate = [
			'limit' => 25,
			'order' => ['MiniBlogs.sort_order' => 'asc', 'MiniBlogs.created' => 'DESC']
		];

		private $uploadDir = WWW_ROOT.'uploads'.DS.'mini-blog'.DS;

		public function initialize() {
			parent::initialize();

			$this->Auth->config('checkAuthIn', 'Controller.initialize');
			$this->loadComponent('Paginator');
			$this->viewBuilder()->layout('admin');
			$this->loadModel('I18n');

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
		    $entity = $this->MiniBlogs->newEntity();
   	        $this->set('form', $entity);
	        if ($this->request->is('post')) {
	        	if (empty($this->request->data['title']) || empty($this->request->data['description'])) {
	        		$this->request->session()->write('mini-blog-data', $this->request->data);
					$this->Flash->error(__('Unable to save data. Please fill all the required fields for English.'));
					return $this->redirect(['controller' => 'mini-blogs', 'action' => 'add']);
				}
				if (empty($this->request->data['_translations']['da']['title']) || empty($this->request->data['_translations']['da']['description'])) {
	        		$this->request->session()->write('mini-blog-data', $this->request->data);
					$this->Flash->error(__('Unable to save data. Please fill all the required fields for Danish.'));
					return $this->redirect(['controller' => 'mini-blogs', 'action' => 'add']);
				}
	        	$title          = $this->request->data['title'];
	        	$check_title    = $this->validatetitle( trim( $title ) , 0);
                $title_da		= $this->request->data['_translations']['da']['title'];

                if(isset($title_da) && !empty($title_da)) {
 	              $check_title_da    = $this->validateDanishTitle( trim( $title_da ) , 0);
                } else {
                  $check_title_da    = 0;
                }

                if( $check_title == 0 && $check_title_da == 0 ) {
					if(!empty($this->request->data['image']['name'])) {
						//echo $this->request->data['image']['type'];
						//echo "Here=>".strpos($this->request->data['image']['type'], 'image/');
						$extension = pathinfo($this->request->data['image']['name'], PATHINFO_EXTENSION);
	    				if( $extension == 'jpg' || $extension == 'png' || $extension == 'jpeg' || $extension == 'gif'  ) {
						  $this->request->data['image'] = $this->FileUpload->doFileUpload($this->request->data['image']);
					    } else {
					   	  return $this->Flash->error(__('Unable to save data. Only jpg, png, gif, jpeg file extensions are allowed'));
					    }
					}
				
					if(!empty($this->request->data['_translations']['da']['image']['name'])) {
						$extension = pathinfo($this->request->data['_translations']['da']['image']['name'], PATHINFO_EXTENSION);
	    				if( $extension == 'jpg' || $extension == 'png' || $extension == 'jpeg' || $extension == 'gif'  ) {
							$this->request->data['_translations']['da']['image'] = $this->FileUpload->doFileUpload($this->request->data['_translations']['da']['image']);
						} else {
					   	 	return  $this->Flash->error(__('Unable to save data. Only jpg, png, gif, jpeg file extensions are allowed'));
					    }
					}
				
					$this->request->data['slug']  = strtolower( trim( Inflector::slug($this->request->data['title']) ) );
					$this->request->data['title'] = ucwords( trim( $this->request->data['title'] ) );
					$this->request->data['_translations']['da']['title'] = ucwords( trim( $this->request->data['_translations']['da']['title'] ) );
					$this->request->data['_translations']['da']['slug'] = strtolower( trim( Inflector::slug($this->request->data['_translations']['da']['title']) ) );
					
					$data = $this->MiniBlogs->patchEntity($entity, $this->request->data, ['translations' => true]);
					if ($this->MiniBlogs->save($data)) {
						if (!empty($this->request->session()->read('mini-blog-data'))) {
							$this->request->session()->delete('mini-blog-data');
						}
		                $this->Flash->success(__('Data has been saved successfully.'));
		                return $this->redirect(['action' => 'index']);
	            	} else {
  		            	$this->Flash->error(__('Unable to save data. Please fill all the required fields.'));
  		        	}
  		    	} else {
  		     		$this->Flash->error(__('Unable to save data. English or Danish title is already being used.'));
  		     	}
			}
		}

		public function edit($id) {
			$entity = $this->MiniBlogs->find('translations', ['conditions' => ['id' => $id] ] )->first();
			$this->set('form', $entity);
			if ($this->request->is(['put', 'post'])) {
				if (empty($this->request->data['title']) || empty($this->request->data['description'])) {
					$this->Flash->error(__('Unable to save data. Please fill all the required fields for English.'));
					return $this->redirect(['controller' => 'mini-blogs', 'action' => 'edit', $id]);
				}
				if (empty($this->request->data['_translations']['da']['title']) || empty($this->request->data['_translations']['da']['description'])) {
					$this->Flash->error(__('Unable to save data. Please fill all the required fields for Danish.'));
					return $this->redirect(['controller' => 'mini-blogs', 'action' => 'edit', $id]);
				}

				$title = $this->request->data['title'];
				$check_title = $this->validateTitle(trim( $title ), $id);
				
				$title_da		= $this->request->data['_translations']['da']['title'];
                if(isset($title_da) && !empty($title_da))
                {
 	              $check_title_da    = $this->validateDanishTitle( trim( $title_da ) , $id);
                }
                else
                {
                  $check_title_da    = 0;

                }

				if($check_title == 0 && $check_title_da == 0) {

				
				if(!empty($this->request->data['image']['name'])) {
					$extension = pathinfo($this->request->data['image']['name'], PATHINFO_EXTENSION);
    				if( $extension == 'jpg' || $extension == 'png' || $extension == 'jpeg' || $extension == 'gif'  )
					{
					   $this->request->data['image'] = $this->FileUpload->doFileUpload($this->request->data['image']);
					   @$this->deleteImage($this->request->data['prev_image']);
 					} 
				    else
				    {
				   	  return $this->Flash->error(__('Unable to save data. Only jpg, png, gif, jpeg file extensions are allowed'));
				    }

				}
				else {
					$this->request->data['image'] = $this->request->data['prev_image'];
				}
				if(!empty($this->request->data['_translations']['da']['image']['name'])) {
					$extension = pathinfo($this->request->data['_translations']['da']['image']['name'], PATHINFO_EXTENSION);
    				if( $extension == 'jpg' || $extension == 'png' || $extension == 'jpeg' || $extension == 'gif'  )
					{
						$this->request->data['_translations']['da']['image'] = $this->FileUpload->doFileUpload($this->request->data['_translations']['da']['image']);
						@$this->deleteImage($this->request->data['prev_image_da']);
					}
					else
					{
 						return  $this->Flash->error(__('Unable to save data. Only jpg, png, gif, jpeg file extensions are allowed'));
					}

						
				}
					else {
						$this->request->data['_translations']['da']['image'] = $this->request->data['prev_image_da'];
				}

				unset($this->request->data['prev_image']);
				unset($this->request->data['prev_image_da']);
				$this->request->data['slug'] = strtolower( trim( Inflector::slug($this->request->data['title']) ) );
				$this->request->data['title'] = ucwords( trim( $this->request->data['title'] ) );
				$this->request->data['_translations']['da']['title'] = ucwords( trim( $this->request->data['_translations']['da']['title'] ) );
								
				$this->request->data['_translations']['da']['slug'] = strtolower( trim( Inflector::slug($this->request->data['_translations']['da']['title']) ) );
								
				$data = $this->MiniBlogs->patchEntity($entity, $this->request->data, ['translations' => true]);

				if ($this->MiniBlogs->save($data)) {
	                $this->Flash->success(__('Data has been saved successfully.'));
	                return $this->redirect(['action' => 'index']);
	            } else {
	            	$this->Flash->error(__('Unable to save data. Please fill all the required fields.'));
	            }
	          }
	          else
	          {
	          	$this->Flash->error(__('Unable to save data. Title is already being used.'));
	          }
			}
			
		}

		public function change() {
			$this->autoRender = false;	
			if($this->request->is('post')) {
				$query = $this->MiniBlogs->query();
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
                echo '<a href="javascript:changeStatus(\'mini-blogs\', '.$id.','.$stVal.');" class="'.$class.'"">'.$stType.'</a>';
                exit();
			}
		}

	private function deleteImage( $image_path ) {
			@unlink( $this->uploadDir.$image_path );
			@unlink( $this->uploadDir.'thumb/sm/'.$image_path );
		}

	private function validateTitle($title, $id) {
			$query = $this->MiniBlogs->find('all', ['conditions' => ['title' => $title, 'MiniBlogs.id !=' => $id]]);
			$number = $query->count();
			return $number;
		}

    private function validateDanishTitle($title, $id)
    {
    	$query = $this->I18n->find()
    						->where(['field' => 'title', 'content' => $title, 'foreign_key !=' => $id, 'model' => 'MiniBlogs']) ;
		$number = $query->count();
		return $number;
    }
 
 



  

	  
	}
?>