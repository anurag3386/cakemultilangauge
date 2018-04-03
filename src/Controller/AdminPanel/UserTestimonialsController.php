<?php 

	namespace App\Controller\AdminPanel;
	use App\Controller\AppController;
	use Cake\ORM\TableRegistry;
	//use Cake\Utility\Inflector;

	class UserTestimonialsController extends AppController {
 
      	public $paginate = [
			'limit' => 25,
			'order' => ['id' => 'DESC'],
			'join' => [
							'Products' => [
								'table' => 'products',
								'conditions' => [
									'Products.id = UserTestimonials.product_id'
								]
							]
					  ],
			'fields' => ['UserTestimonials.id', 'UserTestimonials.first_name', 'UserTestimonials.last_name', 'UserTestimonials.user_profile', 'UserTestimonials.website', 'UserTestimonials.status', 'UserTestimonials.product_id', 'UserTestimonials.created', 'Products.id', 'Products.name']
		];

		
		public function initialize() {
			parent::initialize();
			$this->Auth->config('checkAuthIn', 'Controller.initialize');
			$this->loadComponent('Paginator');
			$this->viewBuilder()->layout('admin');
			$this->loadModel('I18n');
		}

		public function index() {
			$this->set('data', $this->paginate());
		}



		public function add() {
		    $entity = $this->UserTestimonials->newEntity();
   	        $this->set('form', $entity);
	        if ($this->request->is('post')) {
	        	if (empty($this->request->data['content'])) {
	        		$this->request->session()->write('testimonial-data', $this->request->data);
					$this->Flash->error(__('Unable to save data. Please fill all the required fields for English.'));
					return $this->redirect(['controller' => 'user-testimonials', 'action' => 'add']);
				}
				if (empty($this->request->data['_translations']['da']['content'])) {
	        		$this->request->session()->write('testimonial-data', $this->request->data);
					$this->Flash->error(__('Unable to save data. Please fill all the required fields for Danish.'));
					return $this->redirect(['controller' => 'user-testimonials', 'action' => 'add']);
				}
				if (empty($this->request->data['first_name']) || empty($this->request->data['product_id'])) {
	        		$this->request->session()->write('testimonial-data', $this->request->data);
					$this->Flash->error(__('Unable to save data. Please fill all the required fields in Details tab.'));
					return $this->redirect(['controller' => 'user-testimonials', 'action' => 'add']);
				}
				$userTestimo['first_name'] = addslashes($this->request->data['first_name']);
				$userTestimo['last_name'] = addslashes($this->request->data['last_name']);
				$userTestimo['user_profile'] = addslashes($this->request->data['user_profile']);
				$userTestimo['website'] = addslashes($this->request->data['website']);
				$userTestimo['product_id'] = $this->request->data['product_id'];
				$userTestimo['content'] = addslashes($this->request->data['content']);

				$userTestimoDanish['locale'] = 'da';
				$userTestimoDanish['model'] = 'UserTestimonials';
				$userTestimoDanish['field'] = 'content';
				$userTestimoDanish['content'] = addslashes($this->request->data['_translations']['da']['content']);
				$data = $this->UserTestimonials->patchEntity($entity, $userTestimo);
				if ($this->UserTestimonials->save($data)) {
					$userTestimoDanish['foreign_key'] = $data->id;
					$this->loadModel('I18n');
					$entity1 = $this->I18n->newEntity();
					$dataDanish = $this->I18n->patchEntity($entity1, $userTestimoDanish);
					$this->I18n->save($dataDanish);
					if (!empty($this->request->session()->read('testimonial-data'))) {
						$this->request->session()->delete('testimonial-data');
					}
					$this->request->session()->delete('testimonial-data');
	                $this->Flash->success(__('Data has been saved successfully.'));
	                return $this->redirect(['action' => 'index']);
            	} else {
		            $this->Flash->error(__('Unable to save data. Please fill all the required fields.'));
  		    	}
			}
			$this->loadModel('Products');
			$products = $this->Products->find()->where(['Products.category_id IN' => [1, 2, 13, 20], 'Products.status' => 1])
												->join([
													'Categories' => [
														'table' => 'categories',
														'conditions' => ['Categories.id = Products.category_id', 'Categories.status = 1']
													]
												])
												->select(['Products.id', 'Products.name', 'Categories.id', 'Categories.name'])->toArray();
			$productArr = array ();
			foreach ($products as $key => $value) {
				$productArr[$value['id']] = $value['name'].' - '.$value['Categories']['name'];
			}
			$this->set(compact('productArr'));
		}

		public function edit($id) {
			if (!isset($id) && empty($id)) {
				throw new NotFoundException('Testimonial not found');
			}
			$entity = $this->UserTestimonials->find()->where(['id' => $id])->first();
			$this->loadModel('I18n');
			$entity1 = $this->I18n->find()->where(['foreign_key' => $id, 'model' => 'UserTestimonials', 'locale' => 'da', 'field' => 'content'])->select(['content'])->first();
			$this->set('form', $entity);
			$this->set('daform', $entity1);
			if ($this->request->is(['put', 'post'])) {
				if (empty($this->request->data['content'])) {
	        		$this->request->session()->write('testimonial-data', $this->request->data);
					$this->Flash->error(__('Unable to save data. Please fill all the required fields for English.'));
					return $this->redirect(['controller' => 'user-testimonials', 'action' => 'edit', $id]);
				}
				if (empty($this->request->data['first_name']) || empty($this->request->data['product_id'])) {
	        		$this->request->session()->write('testimonial-data', $this->request->data);
					$this->Flash->error(__('Unable to save data. Please fill all the required fields in Details tab.'));
					return $this->redirect(['controller' => 'user-testimonials', 'action' => 'edit', $id]);
				}
				if (empty($this->request->data['_translations']['da']['content'])) {
	        		$this->request->session()->write('testimonial-data', $this->request->data);
					$this->Flash->error(__('Unable to save data. Please fill all the required fields for Danish.'));
					return $this->redirect(['controller' => 'user-testimonials', 'action' => 'edit', $id]);
				}

				$testimonialdata['first_name'] = addslashes($this->request->data['first_name']);
				$testimonialdata['last_name'] = addslashes($this->request->data['last_name']);
				$testimonialdata['user_profile'] = addslashes($this->request->data['user_profile']);
				$testimonialdata['website'] = addslashes($this->request->data['website']);
				$testimonialdata['product_id'] = $this->request->data['product_id'];
				$testimonialdata['content'] = addslashes($this->request->data['content']);
				// foreach ($testimonialdata as $key => $value) {
				// 	if (empty($value)) {
				// 		unset($testimonialdata[$key]);
				// 	}
				// }
				$testimonialdata['id'] = $id;
				$data = $this->UserTestimonials->patchEntity($entity, $testimonialdata);

				if ($this->UserTestimonials->save($data)) {
					$existancy = $this->I18n->find()->where(['locale' => 'da', 'model' => 'UserTestimonials', 'field' => 'content', 'foreign_key' => $id])->first();
					if (!empty($existancy)) {
						$danishContent = addslashes($this->request->data['_translations']['da']['content']);
						$query = $this->I18n->query();
	      				$updateData = $query->update()
					                        ->set(['content' => $danishContent])
					                        ->where(['locale' => 'da', 'model' => 'UserTestimonials', 'field' => 'content', 'foreign_key' => $id])
					                        ->execute();
					} else {
						$danishArr['locale'] = 'da';
						$danishArr['model'] = 'UserTestimonials';
						$danishArr['field'] = 'content';
						$danishArr['foreign_key'] = $id;
						$danishArr['content'] = addslashes($this->request->data['_translations']['da']['content']);
						$I18nEntity = $this->I18n->newEntity();
						$danishData = $this->I18n->patchEntity($I18nEntity, $danishArr);
						//$danishData = $this->I18n->patchEntity($I18nEntity, $danishArr);
						$this->I18n->save($danishData);
					}
					$this->request->session()->delete('testimonial-data');
	                $this->Flash->success(__('Data has been saved successfully.'));
	                return $this->redirect(['controller' => 'user-testimonials', 'action' => 'index']);
	            } else {
	            	$this->Flash->error(__('Unable to save data. Please fill all the required fields.'));
	            }
			}
			$this->loadModel('Products');
			$products = $this->Products->find()->where(['Products.category_id IN' => [1, 2, 13, 20], 'Products.status' => 1])
												->join([
													'Categories' => [
														'table' => 'categories',
														'conditions' => ['Categories.id = Products.category_id', 'Categories.status = 1']
													]
												])
												->select(['Products.id', 'Products.name', 'Categories.id', 'Categories.name'])->toArray();
			$productArr = array ();
			foreach ($products as $key => $value) {
				$productArr[$value['id']] = $value['name'].' - '.$value['Categories']['name'];
			}
			$this->set(compact('productArr'));
			$this->render('add');
		}

		function delete($id) {
			if (!isset($id) && empty($id)) {
				throw new NotFoundException('Testimonial not found');
			}
			$this->autoRender = false;	
			$entity = $this->UserTestimonials->get($id);
			if ($this->UserTestimonials->delete ($entity)) {
				$query = $this->I18n->query();
  				$deleteData = $query->delete()
			                        ->where(['locale' => 'da', 'model' => 'UserTestimonials', 'field' => 'content', 'foreign_key' => $id])
			                        ->execute();
			    $this->Flash->success(__('Testimonial has been deleted successfully.'));
	            return $this->redirect(['controller' => 'user-testimonials', 'action' => 'index']);
			} else {
				$this->Flash->error(__('Something went wrong.'));
	            return $this->redirect(['controller' => 'user-testimonials', 'action' => 'index']);
			}
		}

		function change() {
			$this->autoRender = false;	
			if($this->request->is('post')) {
				$query = $this->UserTestimonials->query();
				$status = $this->request->data['val'];
				$id = $this->request->data['id'];
				$query->update()
						->set(['status' => $status])
						->where(['id' => $id])
						->execute();
				$class="btn btn-danger btn-xs";
                $stType="Inactive";
                $stVal=1;
                if($status == 1) {
                  $class="btn btn-success btn-xs";
                  $stType="Active";
                  $stVal=2;
                }
                echo '<a href="javascript:changeStatus(\'products\', '.$id.','.$stVal.');" class="'.$class.'"">'.$stType.'</a>';
                exit();
			}
		}
	}
?>