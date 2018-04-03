<?php 
	namespace App\Controller\AdminPanel;
	use App\Controller\AppController;
	use Cake\ORM\TableRegistry;
	use Cake\Utility\Inflector;

	class ProductsController extends AppController
	{

		public $paginate = [
			'limit' => 25,
			'contain' => ['ProductPrices', 'Categories'],
			'order' => ['Products.id' => 'DESC']
		];

		private $uploadDir = WWW_ROOT.'uploads'.DS.'products'.DS;

		public function initialize() {
			parent::initialize();

			$this->Auth->config('checkAuthIn', 'Controller.initialize');

			$this->loadComponent('Paginator');
			$this->viewBuilder()->layout('admin');
			$this->loadModel('Categories');
			$this->loadModel('Languages');
			$this->loadModel('ProductLanguages');
			$this->loadModel('ProductTypes');
			$this->loadModel('ProductPrices');
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
		
		    $entity = $this->Products->newEntity();

		    $categoryOptions = $this->Categories->find('list', ['conditions' => ['status' => 1] ])->toArray();
		    
            // Added by: Stan Field
		    $languageOptions = $this->Languages->find('list')
		                                       ->where(['language_category IN' => ['1', '2'], 'status' => 1])
		                                       ->toArray();

            $bundleCategory  = $this->getCategoryIdBySlug('software');

            $productOptions  = $this->Products->find('list')
		                                      ->where(['category_id' => $bundleCategory['id']])
		                                      ->toArray();

		                                                                          

		    
	        if ($this->request->is('post')) {

	        	if(isset($this->request->data['parent_id']) && !empty($this->request->data['parent_id']))
	        	{
	        	  $this->request->data['parent_id'] = implode(',', $this->request->data['parent_id']);
	            }
	            
            	//$seo_url    = $this->request->data['seo_url'];
				$name 	      = $this->request->data['name'];
				$check_seo    = $this->validateSeo($name, 0);
				$name_da   = $this->request->data['_translations']['da']['name'];
				$check_seo_da = $this->validateDanishTitle($name_da, 0);

				if($check_seo == 0 && $check_seo_da == 0 ) {						
					if($this->request->data['image']) {
						$this->request->data['image'] = $this->FileUpload->doFileUpload($this->request->data['image']);
					}

					/*if($this->request->data['image_detail']) {
						$this->request->data['image_detail'] = $this->FileUpload->doFileUpload($this->request->data['image_detail']);
					}*/

					// Added by Stan Field
					$this->request->data['seo_url']  = strtolower( trim( Inflector::slug($this->request->data['name']) ) );
					$this->request->data['_translations']['da']['seo_url'] = strtolower( trim( Inflector::slug($this->request->data['_translations']['da']['name']) ) );


					$data = $this->Products->patchEntity($entity, $this->request->data, ['translations' => true ]);
					$result = $this->Products->save($data);

					if ($result) {


					$record_id=$result->id;

					if($this->request->data['category_id'] != 2  && $this->request->data['category_id'] != 1 && $this->request->data['category_id'] != 20  )
					 {
					 	for ($i = 6; $i <= 10; $i++)  
    	 				unset($this->request->data['product_price'][$i]);
					 }
					 
					foreach($this->request->data['product_price'] as $key => $item) {
						$item['product_id'] = $record_id;
						$priceArr[$key] = $item;
					}

					$prices = TableRegistry::get('ProductPrices');
			    	$entities = $prices->newEntities($priceArr);
					$result = $prices->saveMany($entities);
                    
                    $languageArray = [];
					foreach($this->request->data['language_id'] as $key => $languageItem) {
						$languageArray[$key]['product_id'] = $record_id;
						$languageArray[$key]['language_id'] = $languageItem;
					}
                	$entities = $this->ProductLanguages->newEntities($languageArray);
					$result = $this->ProductLanguages->saveMany($entities);
					$this->request->session()->delete('productType');
					$this->request->session()->delete('categoryType');
	                $this->Flash->success(__('Data has been saved successfully.'));
	                return $this->redirect(['action' => 'index']);
		            }
		            else
		            {
	            	 $this->request->data['category_id'] = "";
   	  	             $this->Flash->error(__('Unable to save data. Please fill all the required fields.'));	
		            }
		            
				}
				else {
					$this->Flash->error(__('Unable to save data. Product name is already being used.'));
				}
	        }

	        $this->set('form', $entity);
			$this->set(compact('categoryOptions'));
			$this->set(compact('productOptions'));
			$this->set(compact('languageOptions'));
			

		}

		public function edit($id) {
			
			$entity = $this->Products->find('translations', 
				['conditions' => ['Products.id' => $id] ]
				)->first();	

 			// Added by: Stan Field
		    $languageOptions = $this->Languages->find('list')
		                                       ->where(['language_category IN' => ['1', '2'], 'status' => 1])
		                                       ->toArray();

			// Added by: Stan Field
		    $selectedLanguages = $this->ProductLanguages->find('all')
		    											->select(['language_id'])
				                                        ->where(['product_id' => $id])
				                                        ->toArray();

			$bundleCategory  = $this->getCategoryIdBySlug('software');
			$productOptions    = $this->Products->find('list')
		                                        ->where(['category_id' => $bundleCategory['id']])
		                                        ->toArray();
           $this->set('data', $entity);

			$categoryOptions = $this->Categories->find('list', ['conditions'=>['status'=>1]])->toArray();

			if ($this->request->is(['post', 'put'])) {

              //  $this->request->data['category_id'] = @implode(',', $this->request->data['category_id']);

				if(isset($this->request->data['parent_id']) && !empty($this->request->data['parent_id']))
	        	{
	        	  $this->request->data['parent_id'] = implode(',', $this->request->data['parent_id']);
	            }

                //$seo_url = $this->request->data['seo_url'];
                 $name = $this->request->data['name'];
   				 $check_seo = $this->validateSeo($name, $id);

   				 $name_da = $this->request->data['_translations']['da']['name'];
   				 $check_seo_da = $this->validateDanishTitle($name_da, $id);


				if($check_seo == 0 && $check_seo_da == 0) {						
					if($this->request->data['image']['name']) {
						$this->request->data['image'] = $this->FileUpload->doFileUpload($this->request->data['image']);
						@$this->deleteImage($this->request->data['prev_image']);
					}
					else {
						$this->request->data['image'] = $this->request->data['prev_image'];
					}

					if($this->request->data['_translations']['da']['image']['name']) {
						$this->request->data['_translations']['da']['image'] = $this->FileUpload->doFileUpload($this->request->data['_translations']['da']['image']);
						@$this->deleteImage($this->request->data['prev_image_da']);
					}
					else {
						$this->request->data['_translations']['da']['image'] = $this->request->data['prev_image_da'];
					}


					/*if($this->request->data['image_detail']['name']) {

						$this->request->data['image_detail'] = $this->FileUpload->doFileUpload($this->request->data['image_detail']);
						@$this->deleteImage($this->request->data['prev_image_detail']);
					}
					else {
						$this->request->data['image_detail'] = $this->request->data['prev_image_detail'];
					}*/
					unset($this->request->data['prev_image']);
					unset($this->request->data['prev_image_da']);

					//unset($this->request->data['prev_image_detail']);
					//Stan Field
					$this->request->data['seo_url']  = strtolower( trim( Inflector::slug($this->request->data['name']) ) );
					$this->request->data['_translations']['da']['seo_url'] = strtolower( trim( Inflector::slug($this->request->data['_translations']['da']['name']) ) );

					/*if( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' ) {
						echo '<pre>'; print_r ($this->request->data);
						//$this->request->data['ProductPrices'] = $this->request->data['product_price'];
						//unset($this->request->data['product_price']);
					}*/

					$data = $this->Products->patchEntity($entity, $this->request->data, ['translations' => true]);

					if( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' ) {
						//echo 'Patched Data => <pre>'; print_r ($data); //die;
					}

					 if ($this->Products->save($data)) {
                        
					 	$priceArr = $this->request->data['product_price'];					 	

						$ProductPrices = TableRegistry::get('ProductPrices');
						foreach($priceArr as $item) {
							
							$entity = $ProductPrices->get($item['price_id']);
							$data = $this->Products->patchEntity($entity, $item);
							$ProductPrices->save($data);
						}	


                       $productLanguages = $this->ProductLanguages->find('all')
                                              					  ->where(['product_id' => $id]);

                      
                        foreach ($productLanguages as $languages ) 
                        {
                          $proLanguages = $this->ProductLanguages->get($languages['id']);
                          $this->ProductLanguages->delete($proLanguages);

                        }                      					  

						$languageArray = [];
						foreach($this->request->data['language_id'] as $key => $languageItem) {
							$languageArray[$key]['product_id'] = $id;
							$languageArray[$key]['language_id'] = $languageItem;
						}

	                	$entities = $this->ProductLanguages->newEntities($languageArray);
						$result = $this->ProductLanguages->saveMany($entities);
                	
	                $this->Flash->success(__('Data has been saved successfully.'));
		                return $this->redirect(['action' => 'index']);
		            }
		            $this->Flash->error(__('Unable to save data. Please fill all the required fields.'));
				}
				else { 
					$this->Flash->error(__('Unable to save data. Product name is already being used.'));
				}	
	        }
			
			//$entity = $this->Products->newEntity();
			$this->set('form', $entity);
			$this->set(compact('categoryOptions'));
			$this->set(compact('selectedLanguages'));
			$this->set(compact('languageOptions'));
     		$this->set(compact('productOptions'));

		}

		public function change() {
			$this->autoRender = false;	
			if($this->request->is('post')) {
				$query = $this->Products->query();
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
                echo '<a href="javascript:changeStatus(\'products\', '.$id.','.$stVal.');" class="'.$class.'"">'.$stType.'</a>';
                exit();
			}
		}

		// private function validateSeo($url, $id) {
		// 	$query = $this->Products->find('all', ['conditions' => ['seo_url' => $url, 'id !=' => $id]]);
		// 	$number = $query->count();
		// 	return $number;
		// }

		private function validateSeo($url, $id) {
			$query = $this->Products->find('all', ['conditions' => ['name' => $url, 'id !=' => $id]]);
			$number = $query->count();
     		return $number;
		}



		private function deleteImage( $image_path ) {
			@unlink( $this->uploadDir.$image_path );
			@unlink( $this->uploadDir.'thumb/sm/'.$image_path );
		}

		private function validateDanishTitle($title, $id)
    	{
	    	$query = $this->I18n->find()
	    						->where(['field' => 'name', 'content' => $title, 'foreign_key !=' => $id, 'model' => 'Products']) ;
			$number = $query->count();
			return $number;
	    }
 
	  
}


?>
