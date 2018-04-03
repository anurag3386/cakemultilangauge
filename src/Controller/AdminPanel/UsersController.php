<?php

	namespace App\Controller\AdminPanel;

	use App\Controller\AppController;
	use Cake\Auth\DefaultPasswordHasher;
	use Cake\ORM\TableRegistry;
	use Cake\Routing\Router;

	class UsersController extends AppController
	{
		public 	$paginate = [
			'limit' => 25,
			'contain' => ['Profiles', 'BirthDetails'],
			'conditions' => ['Users.role ' => 'user'],
			'order' => ['Users.id' => 'desc']
		];

		private $uploadDir = WWW_ROOT.'uploads'.DS.'profiles'.DS;

		public function initialize() {
			parent::initialize();

			$this->Auth->config('checkAuthIn', 'Controller.initialize');
			$this->viewBuilder()->layout('admin');
			$this->loadModel('Languages');
			$this->loadModel('Countries');
			$this->loadModel('Timezones');
			$this->loadModel('Cities');
			$this->loadModel('Orders');
			$this->loadComponent('Paginator');
			$this->loadComponent('FileUpload.FileUpload',[
	            'defaultThumb'=>[
	                'sm' => [100,100]
	            ],
	            'uploadDir' => $this->uploadDir,
	            'maintainAspectRation'=>true
	        ]);

		}

		public function index() {
			$entity = $this->Users->newEntity();
			$conditions = ['AND' => ['Users.role !=' => 'admin'] ];
			$paginate = [
				//'contain' => ['Profiles', 'BirthDetails'],
				//'conditions' => ['AND' => ['Users.role !=' => 'admin'] ],
				'order' => ['Users.id' => 'desc']
			];
			if ($search_txt = $this->request->query('search_txt') ) {
                $this->request->data = $this->request->query;
				/*$paginate['conditions']['OR']['Users.username LIKE '] = '%'.$search_txt.'%';
				$paginate['conditions']['OR']['Profiles.first_name LIKE '] = '%'.$search_txt.'%';
				$paginate['conditions']['OR']['Profiles.last_name LIKE '] = '%'.$search_txt.'%';
				$paginate['conditions']['OR']['Profiles.phone LIKE '] = '%'.$search_txt.'%';
				$paginate['conditions']['OR']['BirthDetails.date LIKE '] = '%'.$search_txt.'%';
				$paginate['conditions']['OR']['Users.created LIKE '] = '%'.$search_txt.'%';*/

				$conditions['OR']['Users.username LIKE '] = '%'.$search_txt.'%';
				$conditions['OR']['Profiles.first_name LIKE '] = '%'.$search_txt.'%';
				$conditions['OR']['Profiles.last_name LIKE '] = '%'.$search_txt.'%';
				$conditions['OR']['Profiles.phone LIKE '] = '%'.$search_txt.'%';
				$conditions['OR']['BirthDetails.date LIKE '] = '%'.$search_txt.'%';
				$conditions['OR']['Users.created LIKE '] = '%'.$search_txt.'%';

				$paginate['limit'] = 25 ;
			} else { 
				$paginate['limit'] = 25;
			}
			//if( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' ) {
	                   	$joinData = $this->Users->find()
			                    ->join([
			                      'Profiles' => [
			                      				'table' => 'profiles',
			                      				'type' => 'INNER',
			                      				'conditions' => [
			                      					'Profiles.user_id = Users.id'
			                      				]
			                      			],
			                        'BirthDetails' => [
			                        				'table' => 'birth_details',
			                        				'type' => 'INNER',
			                        				'conditions' => [
			                        					'BirthDetails.user_id = Users.id'/*,
			                        					'Users.step = 2'*/
			                        				]
			                        			],
			                       	'Languages' => [
			                       					'table' => 'languages',
			                       					'type' => 'INNER',
			                       					'conditions' => ['Languages.id = Profiles.language_id']
			                       				]
			                    ])
			                    ->select([ 'Users.id', 'Users.username', 'Users.step', 'Users.status', 'Users.created', 'Profiles.user_id', 'Profiles.first_name', 'Profiles.last_name', 'Profiles.phone', 'Profiles.gender', 'Profiles.language_id', 'BirthDetails.user_id', 'BirthDetails.date', 'BirthDetails.time', 'Languages.id', 'Languages.name'])
			                    ->where($conditions);
				$data = $this->Paginator->paginate($joinData, $paginate);
			//}
			$this->set(compact('data'));
			//$this->set('data', $this->Paginator->paginate($this->Users->find(), $paginate) );
		    $this->set('form', $entity);
		}


		public function astrologers() {
			$paginate = [
				'limit' => 10,
				'contain' => ['Astrologers'],
				'conditions' => ['Users.role' => 'astrologer'],
				'order' => ['Users.username' => 'asc']
			];

			$this->set('data', $this->Paginator->paginate($this->Users->find(), $paginate) );
		}

		public function login() {

			$this->viewBuilder()->layout('admin_login');

			if ($this->request->is('post')) {
	            $user = $this->Auth->identify();
	            if ($user) {

	            	$data = $this->Users->get($user['id'], ['contain' => 'Profiles'])->toArray();
	            	$this->request->session()->write('name', $data['profile']['first_name'] .' '.$data['profile']['last_name']);
	                $this->Auth->setUser($user);
	                return $this->redirect($this->Auth->redirectUrl());
	            }
	            $this->Flash->error(__('Invalid username or password, try again'), ['key' => 'auth']);
      	    }
		}

		public function logout()
	    {
	    	$this->Flash->success(__('You have been logged out successfully.'), ['key' => 'auth']);
	        return $this->redirect($this->Auth->logout());
	    }

		public function add() {

		    $entity = $this->Users->newEntity();


			$countryOptions = $this->Countries->find('list', ['order' => ['name' => 'asc'] ])->toArray();
			$languageOptions = $this->Languages->find('list', ['conditions' => ['status'=>1]])->toArray();
	        if ($this->request->is('post')) {
	
	        	$this->request->data['birth_detail']['datepicker'] = $this->formatAdminDateForDb($this->request->data['birth_detail']['datepicker']);
	        	
	        	$username = $this->request->data['username'];
				$check_user = $this->validateUser($username, 0);

				if($check_user == 0) {

	              if(isset($this->request->data['birth_detail']['datepicker']) && !empty($this->request->data['birth_detail']['datepicker']))
	               {
					$datepicker = explode(' - ', $this->request->data['birth_detail']['datepicker']);
					$this->request->data['birth_detail']['date'] = $datepicker[0];
					$this->request->data['birth_detail']['day'] = $datepicker[1];
					$dateArr = explode('-', $datepicker[0]);
					unset($this->request->data['birth_detail']['datepicker']);

					$this->request->data['birth_detail']['sun_sign_id'] = 
												$this->calculateSunsignFromDate($dateArr[1], $dateArr[2]);

					$this->request->data['birth_detail']['time'] = $this->request->data['birth_detail']['timepicker'];
					unset($this->request->data['birth_detail']['timepicker']);
                   }
					$this->request->data['subscription']['daily_sun_sign'] = 1;

					if(!empty($this->request->data['profile']['city_id']) || !empty($this->request->data['birth_detail']['city']))
					{
								$data = $this->Users->patchEntity($entity, $this->request->data, ['associated' => ['Profiles', 'BirthDetails',  'Subscriptions']] );
					           
								if ($this->Users->save($data)) {
					                $this->Flash->success(__('Data has been saved successfully.'));
					                return $this->redirect(['action' => 'index']);
				            	}
				            	else
				            	{  
				            		
				            	$this->request->data['birth_detail']['country_id'] = "";
	                 	        $this->request->data['birth_detail']['city_id'] = "";
			            		$this->request->data['birth_detail']['datepicker'] = $this->request->data['birth_detail']['date']." - ". $this->request->data['birth_detail']['day'];
			            		$this->request->data['birth_detail']['timepicker'] = $this->request->data['time'];
			            		$this->Flash->error(__('Unable to save data. Please enter all the details (login/profile/birth) before submitting.'));
				            	}

	                 }
	                 else
	                 {     
	                 	   $this->request->data['birth_detail']['country_id'] = "";
	                 	   $this->request->data['birth_detail']['city_id'] = "";
	                 	   $this->request->data['birth_detail']['datepicker'] = $this->request->data['birth_detail']['date']." - ". $this->request->data['birth_detail']['day'];
     	            	   $this->request->data['birth_detail']['timepicker'] = $this->request->data['birth_detail']['time'];
	           		       $this->Flash->error(__('Unable to save data. Please enter all the details (login/profile/birth) before submitting.'));
	           	     }
				}
				else {
					$this->Flash->error(__('Unable to save data. This username already exists in the database.'));
				}
	        	
	        }
	        $this->set('form', $entity);
	        $this->set(compact('countryOptions'));
	        $this->set(compact('languageOptions'));
	        
		}


		public function edit($id) {
			
			$entity = $this->Users->get($id, ['contain' => ['Profiles', 'BirthDetails', 'Subscriptions']]);
		    $this->set('data', $entity);

		    $citiesOptions = $this->createCityList($entity['profile']['country_id']);
		    $birthCitiesOptions = $this->createCityList($entity['birth_detail']['country_id']);
			$countryOptions = $this->Countries->find('list', ['order' => ['name' => 'asc'] ])->toArray();
			$languageOptions = $this->Languages->find('list', ['conditions' => ['status'=>1]])->toArray();

	        if ($this->request->is('post')) {
        
	        	$this->request->data['birth_detail']['datepicker'] = $this->formatAdminDateForDb($this->request->data['birth_detail']['datepicker']);
	        	$username = $this->request->data['username'];
				$check_user = $this->validateUser($username, $id);

				if($check_user == 0) {

					$datepicker = explode(' - ', $this->request->data['birth_detail']['datepicker']);
					$this->request->data['birth_detail']['date'] = $datepicker[0];
					$this->request->data['birth_detail']['day'] = $datepicker[1];
					$dateArr = explode('-', $datepicker[0]);
					unset($this->request->data['birth_detail']['datepicker']);

					$this->request->data['birth_detail']['sun_sign_id'] = 
												$this->calculateSunsignFromDate($dateArr[1], $dateArr[2]);
	
					$this->request->data['birth_detail']['time'] = $this->request->data['birth_detail']['timepicker'];
					unset($this->request->data['birth_detail']['timepicker']);

					if($this->request->data['password'] == '') {
						unset($this->request->data['password']);
					}

					$data = $this->Users->patchEntity($entity, $this->request->data, ['associated' => ['Profiles', 'BirthDetails']] );

					if ($this->Users->save($data)) {
		                $this->Flash->success(__('Data has been saved successfully.'));
		                return $this->redirect(['action' => 'index']);
	            	}
	           		$this->Flash->error(__('Unable to save data. Please enter all the details (login/profile/birth) before submitting.'));
				}
				else {
					$this->Flash->error(__('Unable to save data. This username already exists in the database.'));
				}
	        	
	        }

	        $entity = $this->Users->newEntity();
	        $this->set('form', $entity);

	        $this->set(compact('citiesOptions'));
	        $this->set(compact('birthCitiesOptions'));
	        $this->set(compact('countryOptions'));
	        $this->set(compact('languageOptions'));
	        
		}

		private function createCityList($country_id)
		 {
		 	$cityOptions = $this->Cities->find('all', 
		    							[ 'conditions' => 
		    								['country_id' => $country_id ] ] )->toArray();


		    $citiesOptions = array();
		   	foreach($cityOptions as $key=>$data) {

				$latitude = floatval($data['latitude'] / 3600.0);
				$latitude = trim( sprintf ( "%2d%s%02d", abs ( intval ($latitude) ), ( ( $latitude >= 0 ) ? 'N' : 'S' ), abs ( intval ( ( ( $latitude - intval ( $latitude ) ) * 60) ) ) ) );

				$longitude = floatval($data['longitude'] / 3600.0);
				$longitude = trim( sprintf ( "%3d%s%02d", abs ( intval ( $longitude ) ), (($longitude >= 0) ? 'W' : 'E' ), abs ( intval ( ( ( $longitude - intval ( $longitude ) ) * 60) ) ) ) );

				$citiesOptions[$data['id']] = $data['city']. ', '. $data['county'] . ' [' . $latitude . ' '. $longitude .' ]';	
					
			}	
			return $citiesOptions;
		 }


		public function addAstrologer() {

		    $entity = $this->Users->newEntity();

			$countryOptions = $this->Countries->find('list')->toArray();
			$languageOptions = $this->Languages->find('list', ['conditions' => ['status'=>1]])->toArray();
			$timezoneOptions = $this->Timezones->find('list')->toArray();

	        if ($this->request->is('post')) {

				if($this->request->data['astrologer']['image']) {
					$this->request->data['astrologer']['image'] = $this->FileUpload->doFileUpload($this->request->data['astrologer']['image']);

				}
				$this->request->data['astrologer']['languages']=serialize($this->request->data['astrologer']['languages']);

	        	$data = $this->Users->patchEntity($entity, $this->request->data, ['associated' => ['Astrologers']] );

	        	if ($this->Users->save($data)) {
	                $this->Flash->success(__('Data has been saved successfully.'));
	                return $this->redirect(['action' => 'astrologers']);
	            }
	            $this->Flash->error(__('Unable to save data.'));
	        }
	        $this->set('form', $entity);
	        $this->set( compact('countryOptions') );
	        $this->set( compact('languageOptions') );
	        $this->set( compact('timezoneOptions') );


		}


		public function editAstrologer($id) {

		    $entity = $this->Users->get($id, ['contain' => ['Astrologers']]);
		    $this->set('data', $entity);

			$countryOptions = $this->Countries->find('list')->toArray();
			$languageOptions = $this->Languages->find('list', ['conditions' => ['status'=>1]])->toArray();
			$timezoneOptions = $this->Timezones->find('list')->toArray();

	        if ($this->request->is('post')) {

	        	if( $this->request->data['astrologer']['image']['name'] ) {
					$this->request->data['astrologer']['image'] = $this->FileUpload->doFileUpload($this->request->data['astrologer']['image']);
					@$this->deleteImage($this->request->data['astrologer']['prev_image']);
				} else {
					if($this->request->data['astrologer']['delete_image'] == 1) {
						$this->deleteImage($this->request->data['astrologer']['prev_image']);
						$this->request->data['astrologer']['image'] = '';
					}
					else {
						$this->request->data['astrologer']['image'] = $this->request->data['astrologer']['prev_image'];
					}
				}
				unset($this->request->data['astrologer']['prev_image']);

				$this->request->data['astrologer']['languages']=serialize($this->request->data['astrologer']['languages']);

	        	$data = $this->Users->patchEntity($entity, $this->request->data, ['associated' => ['Astrologers']] );

	        	if ($this->Users->save($data)) {
	                $this->Flash->success(__('Data has been saved successfully.'));
	                return $this->redirect(['action' => 'astrologers']);
	            }
	            $this->Flash->error(__('Unable to save data.'));
	        }
	        $entity = $this->Users->newEntity();
	        $this->set('form', $entity);
	        $this->set( compact('countryOptions') );
	        $this->set( compact('languageOptions') );
	        $this->set( compact('timezoneOptions') );


		}

		public function dashboard() {
			$total_users = $this->Users->find('all')->where(['Users.role !=' => 'admin'])->count();
			$totalOrders = $this->Orders->find('all')->where(['Orders.product_id !=' => 23])->count();
			$this->set(compact('totalOrders'));
			$this->set(compact('total_users'));

		}

		/*
		 * Used to respond ajax request for chart
		 * Created By : Kingslay
		 * Created Date : July 05, 2017
		 * Last Modified Date : July 07, 2017 (Kingslay)
		 */
		public function calendarSubscribers() {
			if($this->request->is('ajax') ) {
				$this->autoRender = false;
				if($this->request->data['adminSelection']==2){
					$horoscopeCalendarSubscriptionChart = $this->softExitUserChart($this->request->data['type']);
				} elseif ($this->request->data['adminSelection']==3){
					$horoscopeCalendarSubscriptionChart = $this->combineChartData($this->request->data['type']);
				} else {
					$horoscopeCalendarSubscriptionChart = $this->horoscopeCalendarSubscriptionChart($this->request->data['type']);
				}
				print_r(json_encode($horoscopeCalendarSubscriptionChart, true));
			}
		}

		
		/*
		 * Used to get data of soft exit user's registration for chart
		 * Created By : Kingslay
		 * Created Date : July 06, 2017
		 * Last Modified Date : July 07, 2017 (Kingslay)
		 */
		protected function softExitUserChart ($type=1) {
			$data = array();
			$limit = $increamentType = '';
			if($type == 1) {
				$limit = 6;
				$increamentType = 'days';
				$date = date('Y-m-d', strtotime('-'.$limit.' days'));
				$selectArr = ['Date' => 'FROM_UNIXTIME(IpAddresses.created, "%Y-%m-%d")', 'RecordCount' => 'count(id)'];
				$conditionsArr = ['FROM_UNIXTIME(IpAddresses.created) >=' => $date];
				$groupbyArr = 'FROM_UNIXTIME(IpAddresses.created, "%Y-%m-%d")';
				$orderbyArr = ['IpAddresses.created' => 'ASC'];
				$subscribes = $this->getChartData('IpAddresses', $selectArr, $conditionsArr, $groupbyArr, $orderbyArr);
				$data = $this->dailyDataFormationForGoogleChart($limit, $date, $increamentType, $subscribes);
			} elseif($type == 2){
				$limit = 4;
				$increamentType = 'weeks';
				$date = date('W')-4;
				$selectArr = ['Date' => 'FROM_UNIXTIME(IpAddresses.created, "%Y-%m-%d")', 'week' => 'WEEK(FROM_UNIXTIME(created, "%Y-%m-%d"), 3)', 'RecordCount' => 'count(id)'];
				$conditionsArr = ['WEEK(FROM_UNIXTIME(IpAddresses.created), 3) >=' => $date];
				$groupbyArr = 'WEEK(FROM_UNIXTIME(created, "%Y-%m-%d"), 3)';
				$orderbyArr = ['WEEK(FROM_UNIXTIME(created, "%Y-%m-%d"), 3)' => 'ASC'];
				$subscribes = $this->getChartData('IpAddresses', $selectArr, $conditionsArr, $groupbyArr, $orderbyArr);
				$data = $this->weeklyDataFormationForGoogleChart($limit, $date, $subscribes);
			} elseif($type == 3){
				$limit = 11;
				$increamentType = 'months';
				$date = date('Y-m-01', strtotime('-'.$limit.' months'));
				$selectArr = ['Date' => 'FROM_UNIXTIME(IpAddresses.created, "%Y-%m-%d")', 'month' => 'MONTH(FROM_UNIXTIME(created, "%Y-%m-%d"))', 'RecordCount' => 'count(id)'];
				$conditionsArr = ['FROM_UNIXTIME(IpAddresses.created) >=' => $date];
				$groupbyArr = 'MONTH(FROM_UNIXTIME(created, "%Y-%m-%d"))';
				$orderbyArr = ['MONTH(FROM_UNIXTIME(created, "%Y-%m-%d"))' => 'ASC'];
				$subscribes = $this->getChartData('IpAddresses', $selectArr, $conditionsArr, $groupbyArr, $orderbyArr);
				$data = $this->monthlyDataFormationForGoogleChart($limit, $date, $increamentType, $subscribes);
			} elseif($type == 4){
				$limit = 5;
				$increamentType = 'years';
				$date = date('Y-01-01', strtotime('-'.$limit.' years'));
				$selectArr = ['Date' => 'FROM_UNIXTIME(IpAddresses.created, "%Y-01-01")', 'year' => 'YEAR(FROM_UNIXTIME(created, "%Y-%m-%d"))', 'RecordCount' => 'count(id)'];
				$conditionsArr = ['FROM_UNIXTIME(IpAddresses.created) >=' => $date];
				$groupbyArr = 'YEAR(FROM_UNIXTIME(created, "%Y-%m-%d"))';
				$orderbyArr = ['YEAR(FROM_UNIXTIME(created, "%Y-%m-%d"))' => 'ASC'];
				$subscribes = $this->getChartData('IpAddresses', $selectArr, $conditionsArr, $groupbyArr, $orderbyArr);
				$data = $this->yearlyDataFormationForGoogleChart($limit, $date, $increamentType, $subscribes);
			}
			return $data;
		}

		/*
		 * Get data from database tables for chart
		 * Created By : Kingslay
		 * Created Date : July 07, 2017
		 * Last Modified Date : July 07, 2017 (Kingslay)
		 */
		private function getChartData($model, $selectArr, $conditionsArr, $groupbyArr, $orderbyArr){
			$this->loadModel($model);
			return $this->{$model}->find()
	                               ->select($selectArr)
	                               ->where($conditionsArr)
	                               ->group($groupbyArr)
	                               ->order($orderbyArr)
	                               ->toArray();
	    }

	    /*
	     * Manipulating daily chart data according to google chart
		 * Created By : Kingslay
		 * Created Date : July 07, 2017
		 * Last Modified Date : July 07, 2017 (Kingslay)
	     */
	    private function dailyDataFormationForGoogleChart ($limit, $date, $increamentType, $subscribes) {
	    	for ($i=0; $i < ($limit+1); $i++) {
				$dailyDate = $date;
				$dailyDate = date('Y-m-d', strtotime($date.' + '.$i.' '.$increamentType));
				if(count($subscribes)) {
					for ($j=0; $j < count($subscribes); $j++) {
						$data[$i]['date'] = date('d/m/Y', strtotime($dailyDate));
						$data[$i]['value'] = 0;
						if ($dailyDate == $subscribes[$j]['Date']) {
							$data[$i]['date'] = date('d/m/Y', strtotime($dailyDate));
							$data[$i]['value'] = $subscribes[$j]['RecordCount'];
							break;
						}
					}
				} else {
					$data[$i]['date'] = date('d/m/Y', strtotime($dailyDate));
					$data[$i]['value'] = 0;
				}
			}
			return $data;
	    }

	    /*
	     * Manipulating weekly chart data according to google chart
		 * Created By : Kingslay
		 * Created Date : July 07, 2017
		 * Last Modified Date : July 07, 2017 (Kingslay)
	     */
	    private function weeklyDataFormationForGoogleChart ($limit, $date, $subscribes) {
	    	for ($i=0; $i < ($limit+1); $i++) {
				$dailyDate = $date+$i;
				if(count($subscribes)) {
					for($j=0; $j < count($subscribes); $j++){
						$data[$i]['date'] = $this->ordinal_suffix($dailyDate).' Week';
						$data[$i]['value'] = 0;
						if ($dailyDate == $subscribes[$j]['week']) {
							$data[$i]['date'] = $this->ordinal_suffix($subscribes[$j]['week']).' Week';
							$data[$i]['value'] = $subscribes[$j]['RecordCount'];
							break;
						}
					}
				} else {
					$data[$i]['date'] = $this->ordinal_suffix($dailyDate).' Week';
					$data[$i]['value'] = 0;
				}
			}
			return $data;
	    }

	    /*
	     * Manipulating monthly chart data according to google chart
		 * Created By : Kingslay
		 * Created Date : July 07, 2017
		 * Last Modified Date : July 07, 2017 (Kingslay)
	     */
	    private function monthlyDataFormationForGoogleChart ($limit, $date, $increamentType, $subscribes) {
	    	for ($i=0; $i < ($limit+1); $i++) {
				$dailyDate = $date;
				$rangeDailyDate = date('d-m-Y', strtotime($date.' + '.$i.' '.$increamentType));
				if(count($subscribes)) {
					for ($j=0; $j < count($subscribes); $j++) {
						$data[$i]['date'] = date('M', strtotime($date.' + '.$i.' '.$increamentType));
						$data[$i]['value'] = 0;
						if (strpos($subscribes[$j]['Date'], date('Y-m', strtotime($date.' + '.$i.' '.$increamentType))) !== false) {
							$data[$i]['date'] = date('M', strtotime($date.' + '.$i.' '.$increamentType));
							$data[$i]['value'] = $subscribes[$j]['RecordCount'];
							break;
						}
					}
				} else {
					$data[$i]['date'] = date('M', strtotime($date.' + '.$i.' '.$increamentType));
					$data[$i]['value'] = 0;
				}
			}
			return $data;
	    }

	    /*
	     * Manipulating yearly chart data according to google chart
		 * Created By : Kingslay
		 * Created Date : July 07, 2017
		 * Last Modified Date : July 07, 2017 (Kingslay)
	     */
	    private function yearlyDataFormationForGoogleChart ($limit, $date, $increamentType, $subscribes) {
	    	for ($i=0; $i < ($limit+1); $i++) {
				$dailyDate = $date;
				$dailyDate = date('Y', strtotime($date.' + '.$i.' '.$increamentType));
				if(count($subscribes)) {
					for ($j=0; $j < count($subscribes); $j++) {
						$data[$i]['date'] = $dailyDate;
						$data[$i]['value'] = 0;
						if ($dailyDate == $subscribes[$j]['year']) {
							$data[$i]['date'] = $dailyDate;
							$data[$i]['value'] = $subscribes[$j]['RecordCount'];
							break;
						}
					}
				} else {
					$data[$i]['date'] = $dailyDate;
					$data[$i]['value'] = 0;
				}
			}
			return $data;
	    }

	    /*
		 * Used to get data of horoscope calendar subscribers for chart
		 * Created By : Kingslay
		 * Created Date : July 05, 2017
		 * Last Modified Date : July 07, 2017 (Kingslay)
		 */
		protected function horoscopeCalendarSubscriptionChart ($type=1) {
			$data = array();
			$limit = $increamentType = '';
			if($type == 1) {
				$limit = 6;
				$increamentType = 'days';
				$date = date('Y-m-d', strtotime('-'.$limit.' days'));
				$selectArr = ['Date' => 'DATE(Orders.order_date)', 'RecordCount' => 'count(id)'];
				$conditionsArr = ['DATE(Orders.order_date) >=' => $date, 'Orders.product_id' => 53];
				$groupbyArr = 'DATE(Orders.order_date)';
				$orderbyArr = ['DATE(Orders.order_date)' => 'ASC'];
				$subscribes = $this->getChartData('Orders', $selectArr, $conditionsArr, $groupbyArr, $orderbyArr);
				$data = $this->dailyDataFormationForGoogleChart($limit, $date, $increamentType, $subscribes);
			} elseif($type == 2){
				$limit = 4;
				$increamentType = 'weeks';
				$date = date('W')-4;
				$selectArr = ['Date' => 'DATE(Orders.order_date)', 'week' => 'WEEK(Orders.order_date, 3)', 'RecordCount' => 'count(id)'];
				$conditionsArr = ['WEEK(Orders.order_date, 3) >=' => $date, 'Orders.product_id' => 53];
				$groupbyArr = 'WEEK(Orders.order_date, 3)';
				$orderbyArr = ['WEEK(Orders.order_date, 3)' => 'ASC'];
				$subscribes = $this->getChartData('Orders', $selectArr, $conditionsArr, $groupbyArr, $orderbyArr);
				$data = $this->weeklyDataFormationForGoogleChart($limit, $date, $subscribes);
			} elseif($type == 3){
				$limit = 11;
				$increamentType = 'months';
				$date = date('Y-m-01', strtotime('-'.$limit.' months'));
				$selectArr = ['Date' => 'DATE(Orders.order_date)', 'week' => 'MONTH(Orders.order_date)', 'RecordCount' => 'count(id)'];
				$conditionsArr = ['DATE(Orders.order_date) >=' => $date, 'Orders.product_id' => 53];
				$groupbyArr = 'MONTH(Orders.order_date)';
				$orderbyArr = ['MONTH(Orders.order_date)' => 'ASC'];
				$subscribes = $this->getChartData('Orders', $selectArr, $conditionsArr, $groupbyArr, $orderbyArr);
				$data = $this->monthlyDataFormationForGoogleChart($limit, $date, $increamentType, $subscribes);
			} elseif($type == 4){
				$limit = 5;
				$increamentType = 'years';
				$date = date('Y-01-01', strtotime('-'.$limit.' years'));
				$selectArr = ['Date' => 'DATE_FORMAT(Orders.order_date, "%Y-01-01")', 'year' => 'YEAR(Orders.order_date)', 'RecordCount' => 'count(id)'];
				$conditionsArr = ['DATE(Orders.order_date) >=' => $date, 'Orders.product_id' => 53];
				$groupbyArr = 'YEAR(Orders.order_date)';
				$orderbyArr = ['YEAR(Orders.order_date)' => 'ASC'];
				$subscribes = $this->getChartData('Orders', $selectArr, $conditionsArr, $groupbyArr, $orderbyArr);
				$data = $this->yearlyDataFormationForGoogleChart($limit, $date, $increamentType, $subscribes);
			}
			return $data;
		}

		/*
		 * Add week position for weekly data for chart
		 * Created By : Kingslay
		 * Created Date : July 05, 2017
		 * Last Modified Date : July 07, 2017 (Kingslay)
		 */
		private function ordinal_suffix($num){
     		switch($num % 10){
	            case 1: return $num.'st';
	            case 2: return $num.'nd';
	            case 3: return $num.'rd';
    		}
    		return $num.'th';
		}


		public function editProfile() {
			$id = $this->Auth->user('id');
			$entity = $this->Users->get($id, ['contain' => 'Profiles']);
			$this->set('data', $entity);

			if($this->request->is('post')) {

				$old_pass = $this->request->data['old_pass'];
				$hash_pass = $entity->password;
				if( $this->validatePass($old_pass, $hash_pass) == 1)
				{
					if( $this->request->data['new_pass'] != '') {
						$this->request->data['password'] = $this->request->data['new_pass'];
					}

					unset($this->request->data['old_pass']);
					unset($this->request->data['new_pass']);
					unset($this->request->data['new_cpass']);

					$data = $this->Users->patchEntity($entity, $this->request->data, ['associated' => 'Profiles']);
					if( $this->Users->save($data) ) {
						 $this->Flash->success(__('Data has been saved successfully.'));
	                	 return $this->redirect(['action' => 'edit_profile']);
					}
				}
				else {
					$this->Flash->error(__('Incorrect old password!'));
				}
			}

			$entity = $this->Users->newEntity();
			$this->set('form', $entity);
		}

		private function validatePass($pass, $hash_pass) {
			$check = (new DefaultPasswordHasher)->check($pass, $hash_pass);
			return $check;
		}

		private function deleteImage( $image_path ) {
			@unlink( $this->uploadDir.$image_path );
			@unlink( $this->uploadDir.'thumb/sm/'.$image_path );
		}

		private function validateUser($username, $id) {
			$query = $this->Users->find('all', ['conditions' => ['username' => $username, 'id !=' => $id]]);
			$number = $query->count();
			return $number;
		}

		public function changeAstrologer() {
			$this->autoRender = false;
			if($this->request->is('post')) {
				$query = $this->Users->query();
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
                echo '<a href="javascript:changeAstrologerStatus(\'users\', '.$id.','.$stVal.');" class="'.$class.'"">'.$stType.'</a>';
			}
		}

		public function change() {
			$this->autoRender = false;	
			if($this->request->is('post')) {
				$query = $this->Users->query();
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
                echo '<a href="javascript:changeStatus(\'users\', '.$id.','.$stVal.');" class="'.$class.'"">'.$stType.'</a>';
                exit();
			}
		}

		public function getCities() {
			$this->autoRender = false;	
			if($this->request->is('ajax')) {
				$id = $this->request->data['id'];
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
		}


   function uploadImageTinymce()
   {
   	  //  $this->viewBuilder->layout(false);
     	$this->autoRender = false;
     	$url = Router::url('/', true);

        /*******************************************************
         * Only these origins will be allowed to upload images *
         ******************************************************/
        $accepted_origins = array($url);

        /*********************************************
         * Change this line to set the upload folder *
         *********************************************/
        //$imageFolder = "images/";

       // $url = Router::url('/', true);
        //$imageFolder = $url."layout/";

        $imageFolder = WWW_ROOT.'layout'.DS;

        reset ($_FILES);
        $temp = current($_FILES);
        if (is_uploaded_file($temp['tmp_name'])){
          // if (isset($_SERVER['HTTP_ORIGIN'])) {
          //   // same-origin requests won't set an origin. If the origin is set, it must be valid.
          //   if (in_array($_SERVER['HTTP_ORIGIN'], $accepted_origins)) {
          //     header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
          //   } else {
          //     header("HTTP/1.0 403 Origin Denied");
          //     return;
          //   }
          // }

          /*
            If your script needs to receive cookies, set images_upload_credentials : true in
            the configuration and enable the following two headers.
          */
          // header('Access-Control-Allow-Credentials: true');
          // header('P3P: CP="There is no P3P policy."');

          // Sanitize input
          if (preg_match("/([^\w\s\d\-_~,;:\[\]\(\).])|([\.]{2,})/", $temp['name'])) {
              header("HTTP/1.0 500 Invalid file name.");
              return;
          }

          // Verify extension
          if (!in_array(strtolower(pathinfo($temp['name'], PATHINFO_EXTENSION)), array("gif", "jpg", "png"))) {
              header("HTTP/1.0 500 Invalid extension.");
              return;
          }

          // Accept upload if there was no origin, or if it is an accepted origin
          $filetowrite = $imageFolder . $temp['name'];
          move_uploaded_file($temp['tmp_name'], $filetowrite);

          // Respond to the successful upload with JSON.
          // Use a location key to specify the path to the saved image resource.
          // { location : '/your/uploaded/image/file'}

          $path = Router::url('/',true)."layout/".$temp['name'];
          echo json_encode(array('location' => $path));
          exit;
        } else {
          // Notify editor that the upload failed
          header("HTTP/1.0 500 Server Error");
          exit;
        }

   }

   function userLoginByAdmin ($id) {
   		//echo $id; die;
   		$session = $this->request->session();
   		//$this->loadModel('Users');
   		$checkUser = $this->Users->findById($id)->toArray();
   		//echo '<pre>'; print_r ($checkUser); die;
   		//echo '<pre>'; print_r ($session->read());
   		if (!empty($checkUser) && is_array($checkUser)) {
            $session->write('Auth.User', $checkUser);
   			$this->loadModel ('Profiles');
   			$this->loadModel ('BirthDetails');
   			$profileData = $this->Profiles->find()->where(['user_id' => $id])->first();
   			//pr ($profileData);
   			$session->write('name', $profileData['profile']['first_name'] .' '.$profileData['profile']['last_name']);
            $session->write('user_id', $checkUser['id']);
            // Setting cookie for blog page
            setcookie("user_id", $checkUser['id'], time()+3600, "/", "",  0);

            $session->write('Auth.UserProfile', $profileData);
            $BirthDetails = $this->BirthDetails->find()->where(['user_id' => $user['id']])->first();
            //pr ($BirthDetails); die;
            $session->write('Auth.BirthDetails', $BirthDetails);
            $session->write('selectedUser', $data['id']);

            $Countries = $this->Countries->find()->where(['id' => $BirthDetails['country_id'], 'Countries.status' => 1])->first();
            $session->write('Auth.Country', $Countries);

            $Cities = $this->Cities->find()->where(['id' => $BirthDetails['city_id']])->first();
            $session->write('Auth.City', $Cities);
   			//$this->redirect (['controller' => 'users', 'action' => 'dashboard']);

   		} else {
   			$this->Flash->error(__('User not exists.'));
   			return $this->redirect (['controller' => 'users', 'action' => 'index']);
   		}
   }

   	function softExitUsers () {
   		$this->loadModel('IpAddresses');
   		$conditions = ['AND' => ['Users.role !=' => 'admin'] ];
   		
   		$paginate = [
				
				'limit' => 25
			];
			/* $conc = concat([
            'Profiles.first_name' => 'identifier', ' ',
            'Profiles.last_name' => 'identifier'
         ]);*/
		
		if ($search_txt = $this->request->query('search_txt') ) {
            $this->request->data = $this->request->query;
			$conditions['OR']['Users.username LIKE '] = '%'.$search_txt.'%';
			$conditions['OR']['Profiles.first_name LIKE '] = '%'.$search_txt.'%';
			$conditions['OR']['Profiles.last_name LIKE '] = '%'.$search_txt.'%';
			//$conditions['OR'][$conc]='%'.$search_txt.'%';
			

			//print_r($conditions);
			//$paginate['limit'] = 25 ;
		}/* else { 
			$paginate['limit'] = 25;
		}*/
   		$output = $this->IpAddresses->find()
   									->join([
			                      		'Users' => [
		                      				'table' => 'users',
		                      				'type' => 'INNER',
		                      				'conditions' => [
			                      				'Users.id = IpAddresses.user_id'
			                      			]
			                      			
			                      		],
			                      		'Profiles' => [
		                      				'table' => 'profiles',
		                      				'type' => 'INNER',
		                      				'conditions' => [
			                      				'Profiles.user_id = IpAddresses.user_id'
			                      			]
			                      		],
			                        	'BirthDetails' => [
	                        				'table' => 'birth_details',
	                        				'type' => 'INNER',
	                        				'conditions' => [
	                        					'BirthDetails.user_id = IpAddresses.user_id'
	                        				]
	                        			],
	                        			'Languages' => [
	                       					'table' => 'languages',
	                       					'type' => 'INNER',
	                       					'conditions' => ['Languages.id = Profiles.language_id']
	                       				]
			                    	])
			                    	->select(['Users.id', 'Users.username', 'Users.step', 'Users.status', 'Users.created', 'IpAddresses.user_id', 'IpAddresses.ip', 'Profiles.user_id', 'Profiles.first_name', 'Profiles.last_name', 'Profiles.phone', 'Profiles.gender', 'Profiles.language_id', 'BirthDetails.user_id', 'BirthDetails.date', 'BirthDetails.time', 'Languages.id', 'Languages.name'])
			                    	->where($conditions)
			                    	->order(['Users.created' => 'DESC']);


			                    	
			                    	//->toArray();
			                    	
		$count = count($output->toArray());
		$entity = $this->IpAddresses->newEntity();
		$output = $this->Paginator->paginate($output, $paginate);

		//pr($output); die;
		$this->set(compact('output', 'count', 'entity'));
   		//pr($output); die;
   	}



}

?>
