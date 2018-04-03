<?php 
	namespace App\Controller\AdminPanel;
	use App\Controller\AppController;
	use Cake\ORM\TableRegistry;
	use Cake\I18n\I18n;

	class OrdersController extends AppController
	{ 
		
		public function initialize()
		{
			parent::initialize();
			$this->Auth->config('checkAuthIn', 'Controller.initialize');
      $this->loadModel('Categories');
 			$this->loadModel('Products');
 			$this->loadModel('States');
 			$this->loadModel('DeliveryOptions');
 			$this->loadComponent('Paginator');
		  $this->loadModel('Portals');
      $this->loadModel('Users');
      $this->loadModel('Birthdata');
      $this->viewBuilder()->layout('admin');
		}

    public function index() {
	    $entity = $this->Orders->newEntity();
	    $categoryOptions  = $this->Categories->find('list')
                                         ->where(['Categories.status' => 1, 'Categories.id != ' => 26])
                                         ->order(['Categories.sort_order' => 'ASC']);

    	$deliveryOptions = $this->DeliveryOptions->find('list')
                                               	->order(['name' => 'ASC'])
                                                ->toArray();

        $statusOptions = $this->States->find('list')
                                	->where(['name IN' => ['queued', 'closed', 'processing', 'new']])
                            	    ->order(['name' => 'ASC'])
                        	        ->toArray();

        $portalOptions = $this->Portals->find('list')
                                       ->order(['name' => 'ASC'])->toArray();

        $paginate = [
        				'contain' => ['Products', 'DeliveryOptions',  'Currencies', 'States', 'Profiles', 'Users', 'ProductTypes', 'GuestUserProductDetails', 'Birthdata'],
                        'order' => ['Orders.created' => 'desc'],
                        'conditions'=> ['Orders.product_id != ' => 23, 'Orders.product_type != ' => 12, 'Orders.order_by' => 0 ],
                        'fields' => ['Orders.id', 'Orders.user_id', 'Orders.price', 'Currencies.symbol', 'GuestUserProductDetails.first_name', 'GuestUserProductDetails.last_name', 'Profiles.first_name', 'Profiles.last_name', 'Orders.order_date', 'Products.name', 'Orders.email', 'ProductTypes.name', 'States.name', 'States.id', 'Orders.payer_order_id','Orders.another_person', 'Birthdata.first_name', 'Birthdata.last_name', 'Birthdata.name_on_report', 'Products.category_id'],
                        'group' => ['Orders.id']
                    ];
        $paginate['limit'] = 25 ;
        $category = $this->request->query('category_id');

        if($this->request->is('GET') && isset($category)) {
        	$this->request->data = $this->request->query;
        	//pr ($this->request->data); //die;
        	$search_txt = $this->request->query('search_txt');
        	$paginate['conditions']['OR']['Users.username LIKE '] = '%'.$search_txt.'%';
        	$paginate['conditions']['OR']['Profiles.first_name LIKE '] = '%'.$search_txt.'%';
        	$paginate['conditions']['OR']['Profiles.last_name LIKE '] = '%'.$search_txt.'%';
        	$paginate['conditions']['OR']['Orders.payer_order_id LIKE'] = '%'.$search_txt.'%';
        	$paginate['conditions']['OR']['GuestUserProductDetails.email LIKE'] = '%'.$search_txt.'%';
        	$paginate['conditions']['OR']['GuestUserProductDetails.first_name LIKE'] = '%'.$search_txt.'%';
        	$paginate['conditions']['OR']['GuestUserProductDetails.last_name LIKE'] = '%'.$search_txt.'%';
        	
            if(!empty($this->request->query('start-date')) || !empty($this->request->query('end-date')) ) {
            	if (empty($this->request->query('start-date'))) {
            		$this->Flash->error(__('Please enter start date'));
              	return $this->redirect([ 'controller' => 'orders', 'action' => 'index']);
            	}
            	if (empty($this->request->query('end-date'))) {
            		$this->Flash->error(__('Please enter end date'));
              	return $this->redirect([ 'controller' => 'orders', 'action' => 'index']);
            	}

              //$dateRange = explode(' - ', $dateRange);
              $startRange = explode('/', $this->request->query('start-date'));
              $startRange = $startRange[2].'-'.$startRange[1].'-'.$startRange[0].' 00:00:00';
              $endRange = explode('/', $this->request->query('end-date'));
              $endRange = $endRange[2].'-'.$endRange[1].'-'.$endRange[0].' 23:59:59';
              //echo strtotime($startRange).' => '.strtotime($endRange); die;

              if (strtotime($startRange) > strtotime($endRange)) {
                $this->Flash->error(__('End date must be greater than start date'));
                return $this->redirect([ 'controller' => 'orders', 'action' => 'index']);
              }

            	//$dateRange = explode(' - ', $dateRange);
            	/*$startRange = explode('/', $this->request->query('start-date'));
            	$startRange = $startRange[2].'-'.$startRange[1].'-'.$startRange[0].' 00:00:00';
            	$endRange = explode('/', $this->request->query('end-date'));
            	$endRange = $endRange[2].'-'.$endRange[1].'-'.$endRange[0].' 00:00:00';*/
            	$paginate['conditions']['AND']['Orders.order_date >='] = $startRange;
            	$paginate['conditions']['AND']['Orders.order_date <='] = $endRange;
            }

        	if($categoryId = $this->request->query('category_id')) {
        		$paginate['conditions']['AND']['Products.category_id '] = $categoryId;
        	}
        	if($userId = $this->request->query('user_id')) {
        		$paginate['conditions']['AND']['Orders.user_id '] = $userId;
        	}
        	if($status = $this->request->query('status' )) {
        		$paginate['conditions']['AND']['Orders.order_status '] = $status;
        	}
            if($deliveryOption =  $this->request->query('deliveryOption')) {
            	$paginate['conditions']['AND']['Orders.delivery_option '] = $deliveryOption;
            }
            if($portalOption = $this->request->query('portal')) {
            	$paginate['conditions']['AND']['Orders.portal_id'] = $portalOption;
            }
            if($month = $this->request->query('month')) {
            	$date = date_parse($month);
            	$paginate['conditions']['AND']['MONTH(Orders.order_date)'] = $date['month'];
            	$paginate['conditions']['AND']['YEAR(Orders.order_date)']  = $date['year'];
            }

            /*$dadada = $this->Paginator->paginate($this->Orders->find(), $paginate);

            pr($paginate); pr($dadada); die;*/
        }
            


        $this->set('form', $entity);
        $this->set(compact('statusOptions'));
        $this->set(compact('deliveryOptions'));
        $this->set(compact('categoryOptions'));
        $this->set(compact('portalOptions'));
        $this->set('orders', $this->Paginator->paginate($this->Orders->find(), $paginate));
    }

    

    public function view($id = 0)
    { 
          $orderDetail = $this->Orders->find('all')
                                      ->where(['Orders.payer_order_id' => $id])
                                      ->contain(['Products', 'DeliveryOptions', 'Currencies', 'States', 'Profiles', 'Languages', 'OrderTransactions', 'OrderShippings', 'ProductTypes', 'GuestUserProductDetails', 'Birthdata'])
                                      ->select(['Orders.payer_order_id', 'Orders.id', 'Orders.email', 'Orders.order_date', 'price', 'DeliveryOptions.name', 'States.name','States.id', 'Currencies.symbol', 'Products.name', 'Languages.name', 'Profiles.first_name', 'Profiles.last_name', 'OrderTransactions.payment_date', 'OrderShippings.address_1', 'OrderShippings.address_2', 'OrderShippings.city','OrderShippings.phone', 'OrderShippings.state', 'OrderShippings.country', 'OrderShippings.postal_code', 'ProductTypes.name', 'Products.seo_url', 'Orders.user_id', 'GuestUserProductDetails.first_name', 'GuestUserProductDetails.last_name', 'Products.image', 'Birthdata.first_name', 'Birthdata.last_name', 'Birthdata.name_on_report', 'Orders.another_person', 'Products.category_id'])
                                      ->first();

         $statusOptions = $this->States->find('list')
                                                  ->where(['name IN' => ['queued', 'closed', 'processing', 'new']])
                                                  ->order(['name' => 'ASC'])
                                                  ->toArray();

         $this->set(compact('statusOptions'));
         $this->set(compact('orderDetail'));

    }


    



    public function change() {

      $this->autoRender = false;  
      if($this->request->is('post')) {
        $status = $this->request->data['val'];
          $id = $this->request->data['id'];
          $entity = $this->Orders->newEntity();
          $entity->id = $id;
          $entity->order_status = $status;

          if($this->Orders->save($entity))
          {
            echo "true";
          }            
          else
          {
            echo  "false";
          }
         exit();
      }
    }


    public function astroclockOrderList() {
      $entity = $this->Orders->newEntity();
      $statusOptions = $this->States->find('list')
                                ->where(['name IN' => ['queued', 'closed', 'processing', 'new']])
                                ->order(['name' => 'ASC'])
                                ->toArray();
      $paginate = [
                    'contain' => ['Products',  'Currencies', 'States', 'ProductTypes', 'Birthdata'],
                      'order' => ['Orders.created' => 'desc'],
                      'conditions'=> ['Orders.product_id != ' => 23, 'Orders.product_type' => 12, 'Orders.order_by' => 1 ],
                      'fields' => ['Orders.id', 'Orders.product_id', 'Orders.user_id', 'Orders.price', 'Currencies.symbol', 'Orders.order_date', 'Products.name', 'Orders.email', 'ProductTypes.name', 'States.name', 'States.id', 'Orders.payer_order_id','Orders.another_person', 'Birthdata.first_name', 'Birthdata.last_name', 'Birthdata.name_on_report', 'Products.category_id'],
                      'group' => ['Orders.id']
                  ];
      $paginate['limit'] = 25;

      if($this->request->is('GET')) {
        $this->request->data = $this->request->query;
        $search_txt = $this->request->query('search_txt');

        $paginate['conditions']['OR']['Orders.email LIKE '] = '%'.$search_txt.'%';
        $paginate['conditions']['OR']['Birthdata.first_name LIKE '] = '%'.$search_txt.'%';
        $paginate['conditions']['OR']['Birthdata.last_name LIKE '] = '%'.$search_txt.'%';
        $paginate['conditions']['OR']['Orders.payer_order_id LIKE'] = '%'.$search_txt.'%';        
        if(!empty($this->request->query('start-date')) || !empty($this->request->query('end-date')) ) {
          if (empty($this->request->query('start-date'))) {
            $this->Flash->error(__('Please enter start date'));
            return $this->redirect([ 'controller' => 'orders', 'action' => 'index']);
          }
          if (empty($this->request->query('end-date'))) {
            $this->Flash->error(__('Please enter end date'));
            return $this->redirect([ 'controller' => 'orders', 'action' => 'index']);
          }

          $startRange = explode('/', $this->request->query('start-date'));
          $startRange = $startRange[2].'-'.$startRange[1].'-'.$startRange[0].' 00:00:00';
          $endRange = explode('/', $this->request->query('end-date'));
          $endRange = $endRange[2].'-'.$endRange[1].'-'.$endRange[0].' 23:59:59';

          if (strtotime($startRange) > strtotime($endRange)) {
            $this->Flash->error(__('End date must be greater than start date'));
            return $this->redirect([ 'controller' => 'orders', 'action' => 'index']);
          }
          $paginate['conditions']['AND']['Orders.order_date >='] = $startRange;
          $paginate['conditions']['AND']['Orders.order_date <='] = $endRange;
        }

        if($userId = $this->request->query('user_id')) {
          $paginate['conditions']['AND']['Orders.user_id '] = $userId;
        }
        if($status = $this->request->query('status' )) {
          $paginate['conditions']['AND']['Orders.order_status '] = $status;
        }
        if($portalOption = $this->request->query('portal')) {
          $paginate['conditions']['AND']['Orders.portal_id'] = $portalOption;
        }
        if($month = $this->request->query('month')) {
          $date = date_parse($month);
          $paginate['conditions']['AND']['MONTH(Orders.order_date)'] = $date['month'];
          $paginate['conditions']['AND']['YEAR(Orders.order_date)']  = $date['year'];
        }
      }
      $this->set('form', $entity);
      $this->set(compact('statusOptions'));
      $this->set('orders', $this->Paginator->paginate($this->Orders->find(), $paginate));
    }


    public function astroclockOrderDetail($id = 0) {
      $orderDetail = $this->Orders->find('all')
                                  ->where(['Orders.payer_order_id' => $id])
                                  ->contain(['Products', 'DeliveryOptions', 'States', 'Languages', 'OrderTransactions', 'ProductTypes', 'Birthdata'])
                                  ->select(['Orders.payer_order_id', 'Orders.id', 'Orders.email', 'Orders.order_date', 'DeliveryOptions.name', 'States.name','States.id', 'Products.name', 'Languages.name', 'OrderTransactions.payment_date', 'ProductTypes.name', 'Products.seo_url', 'Orders.user_id', 'Products.image', 'Birthdata.first_name', 'Birthdata.last_name', 'Birthdata.name_on_report', 'Products.category_id'])
                                  ->first();
      $statusOptions = $this->States->find('list')
                                    ->where(['name IN' => ['queued', 'closed', 'processing', 'new']])
                                    ->order(['name' => 'ASC'])
                                    ->toArray();
      $this->set(compact('statusOptions', 'orderDetail'));
    }

    /*
     * This function used to place a free mini report order using Admin panel
     * Created Date : October 06, 2017
     * Created By : Krishan Kumar <Kingslay@123789.org>
     */
    function generateMiniReport (){
      $form = $this->Orders->newEntity();
      $this->loadModel('Countries');
      $birthCitiesOptions = '';
      $countryOptions = $this->Countries->find('list', ['order' => ['name' => 'asc'] ])->toArray();
      $productsList = $this->Products->find('list', ['order' => ['name' => 'asc'] ])->where(['Products.category_id' => 26, 'Products.status' => 1])->toArray();
      $this->set(compact('form', 'birthCitiesOptions', 'countryOptions', 'productsList'));
    }

    /*function getCities() {
      $this->autoRender = false;  
      if($this->request->is('ajax')) {
        $id = $this->request->data['id'];
        $this->loadModel('Cities');
        $cityOptions = $this->Cities->find('all', ['conditions' => ['country_id' => $id] ])->toArray();

        $html = '<option>Select City</option>';
        foreach($cityOptions as $key=>$data) {

          $latitude = floatval($data['latitude'] / 3600.0);
          $latitude = trim( sprintf ( "%2d%s%02d", abs ( intval ($latitude) ), ( ( $latitude >= 0 ) ? 'N' : 'S' ), abs ( intval ( ( ( $latitude - intval ( $latitude ) ) * 60) ) ) ) );

          $longitude = floatval($data['longitude'] / 3600.0);
          $longitude = trim( sprintf ( "%3d%s%02d", abs ( intval ( $longitude ) ), (($longitude >= 0) ? 'W' : 'E' ), abs ( intval ( ( ( $longitude - intval ( $longitude ) ) * 60) ) ) ) );

          $html .= '<option value="'.$data['id'].'">'.$data['city']. ', '. $data['county'] . ' [' . $latitude . ' '. $longitude .' ]</option>'; 
          
        } 
        echo $html;
        exit();
      }
    }*/


}
?>
