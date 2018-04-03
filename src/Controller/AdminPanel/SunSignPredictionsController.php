<?php 
    namespace App\Controller\AdminPanel;
	use App\Controller\AppController;
	use Cake\ORM\TableRegistry;
	use Cake\Event\Event;

    class SunSignPredictionsController extends AppController {
		public $paginate = [
			'limit' => 25,
			'order' => ['SunSignPrediction.id' => 'desc'],
			'maxLimit' => 25
		];

		private $uploadDir = WWW_ROOT.'uploads'.DS.'sunsigns'.DS.'files'.DS;
		private $scope;
		private $schedule_date;
		private $language='en';
		private $sign=0;
        private $content;

		public function initialize() {
			parent::initialize();
			$this->Auth->config('checkAuthIn', 'Controller.initialize');
			$this->viewBuilder()->layout('admin');
			$this->loadComponent('FileUpload.FileUpload',[
	            'uploadDir' => $this->uploadDir
	        ]);
	        $this->loadModel('Languages');
	        $this->loadModel('SunSigns');
		}

	
		public function index() {
			$signs = array ('aries', 'taurus', 'gemini', 'cancer', 'leo', 'virgo', 'libra', 'scorpio', 'sagittarius', 'capricorn', 'aquarius', 'pisces' );
			$entity = $this->SunSignPredictions->newEntity();
            if($this->request->is('post')) {
                if($this->request->data['prediction-file']) {
    				if(file_exists($this->uploadDir . $this->request->data['prediction-file']['name'])) {
						$this->Flash->error(__('File already exists'));
						$this->redirect(['controller' => 'SunSignPredictions', 'action'=>'index']);
					} else {
						$sunsign = $this->getUpload($this->request->data['prediction-file']);
						$sunsignData['schedule_date'] = $sunsign->schedule_date;
						$sunsignData['language'] = $sunsign->language;
						$sunsignData['content'] = $sunsign->content;
						$sunsignData['scope'] = $sunsign->scope;
						$this->set('sunsign', $sunsignData);
				    }
				}            	
            }
            $this->set('form', $entity);
        }

	function getUpload($predictioFile) {
		try {
			$filename = trim ( strtolower ( basename ( $predictioFile['name'] ) ) );
			
			/** daily context = DDMMYY_LL.txt **/
			if (is_numeric ( substr ( $filename, 0, 6 ) ) && substr ( $filename, 6, 1 ) == '_') {
				$this->scope = 1; 
				/** daily content **/
				$day = intval ( substr ( $filename, 0, 2 ) );
				$month = intval ( substr ( $filename, 2, 2 ) );
				$year = 2000 + intval ( substr ( $filename, 4, 2 ) );
				$this->schedule_date = sprintf ( "%04d-%02d-%02d", $year, $month, $day );
				switch (strtolower ( substr ( $filename, 7, 2 ) )) {
					case 'uk' :
						$this->language = 'en';
						break;
					case 'dk' :
						$this->language = 'dk';
						break;
					case 'se' :
						$this->language = 'se';
						break;
					case 'fr' :
						$this->language = 'fr';
						break;
					default:
				  /* error */
			      $this->Flash->error(__("Unrecognised language in filename. " . "Format is 'DDMMYY_LL.txt' " . "where LL in UK, DK, FR, or SE."));

			      return $this->redirect(['controller' => 'SunSignPredictions', 'action'=> 'index']);
				  
				}
				if($this->parseUploadFile($predictioFile)) {
					return $this;
				} else {
					$this->Flash->error(__('problem to parse file'));
					return $this->redirect(['controller' => 'SunSignPredictions', 'action'=> 'index']);
				}
			}
				
			/** weekly context = Week_WWMMXX_LL.txt **/

            elseif (strtolower ( substr ( $filename, 0, 4 ) ) == 'week' && substr ( $filename, 4, 1 ) == '_' && is_numeric ( substr ( $filename, 5, 6 ) ) && substr ( $filename, 11, 1 ) == '_') {
				/** it's likely to be English **/
				$this->scope = 2; 
				/** weekly content **/
				switch (strtolower ( substr ( $filename, 12, 2 ) )) {
					case 'uk' :
						$this->language = 'en';
						break;
					case 'dk' :
						$this->language = 'dk';
						break;
					case 'se' :
						$this->language = 'se';
						break;
					case 'fr' :
						$this->language = 'fr';
						break;

						/** AG - new children's weekly **/
					case 'nd' :
						$this->scope = 7;
						$this->language = 'dk';
						break;
					default:
	  					/* error */
	  			 		$this->Flash->error(__( "Unrecognised language in filename. " . "Format is 'weekWW_YYYY_LL.txt' " . "where LL in UK, DK, FR, or SE + special ND case."));
	  			 		return $this->redirect(['controller' => 'SunSignPredictions', 'action'=> 'index']);
				}
				$this->validateLanguage ( strtolower ( substr ( $filename, 12, 2 ) ), 'week_WWMMYY_LL.txt' );
				$week = intval ( substr ( $filename, 5, 2 ) );
				$month = intval(substr($filename, 7, 2));
				$year = 2000 + intval ( substr ( $filename, 9, 2 ) );
			
				/** Below line is added to adjust the Week number to match Weekly data **/
				//$week = $week + 1;
				$this->schedule_date = $this->WeekToScheduleDate ( $week, $month, $year );
				if($this->parseUploadFile ($predictioFile)) {
					return $this;
				} else {
					die( 'Problem to parse file');
				}
			}
				
			/** monthly context = monthMM_YYYY_LL.txt **/
			elseif (strtolower ( substr ( $filename, 0, 5 ) ) == 'month' && is_numeric ( substr ( $filename, 5, 2 ) ) && substr ( $filename, 7, 1 ) == '_' && is_numeric ( substr ( $filename, 8, 4 ) )) {
				$this->scope = 3; 
				/** monthly content **/
					
				$day = 1;
				$month = intval ( substr ( $filename, 5, 2 ) );
				$year = intval ( substr ( $filename, 8, 4 ) );
				$this->schedule_date = sprintf ( "%04d-%02d-%02d", $year, $month, $day );
				$this->validateLanguage ( strtolower ( substr ( $filename, 13, 2 ) ), 'monthMM_YYYY_LL.txt' );
				if($this->parseUploadFile ($predictioFile)) {
					return $this;
				} else {
					$this->Flash->error(__( 'Problem to parse file'));
					return $this->redirect(['controller' => 'SunSignPredictions', 'action'=> 'index']);
				}
			}
				
			/** yearly context = year2009_LL.txt **/
			elseif (strtolower(substr($filename, 0, 4 )) == 'year' && is_numeric ( substr ( $filename, 4, 4 )) && substr ( $filename, 8, 1 ) == '_') {
			 	$this->scope = 4;
				/** yearly content **/
				$year = intval ( substr ( $filename, 4, 4 ) );
				$this->schedule_date = sprintf ( "%04d-01-01", $year );
				$this->validateLanguage ( strtolower ( substr ( $filename, 9, 2 ) ), 'yearYYYY_LL.txt' );
				if($this->parseUploadFile ($predictioFile)) {
					return $this;
				} else {
					return NULL;
				}
			} else {
				$this->Flash->error(__( 'Incorrect file format.'));
				return $this->redirect(['controller' => 'SunSignPredictions', 'action'=> 'index']);	
			}
	    }
		catch(Exception $ex) {
			$this->Flash->error($ex->getMessage());
		}
	}

	private function parseUploadFile($predictioFile) {
		try {
			// check if records already inserted
			$result = $this->getContent($this);
			if($result > 0) {
				$this->Flash->error(__("Record already inserted!"));
				return false;
			} else {
				$type = explode('/',$this->request->data['prediction-file']['type']);
				if($type[0] != 'text') {
					$this->Flash->error(__('Only text files are allowed'));
					return $this->redirect(['controller' => 'SunSignPredictions', 'action'=> 'index']);
				}
				$this->FileUpload->doFileUpload($predictioFile);

				$filename = $this->uploadDir.$predictioFile['name'];
				$contents = file_get_contents($filename);
					
				if ($contents === false) 
				{
					$this->Flash->error(__("Unable to read data file $filename!"));
				}
				$records = explode("\n", $contents);
				$arrayValue = array();
				$sunsign='';
				$temp='';
				$new = 0;
				$sign_count = 0;
				$array[][2] = array();
				for ($i=1; $i<sizeof($records); $i++) {
					$pos2 = strpos($records[$i], "&");
					if($pos2 == 0) {
						$content1 = explode("&",$records[$i]);
						if(count($content1)>1) {
							$content2 = explode("=",$content1[1]);
							$value = trim($content2[0]);
							$content = $content2[1];
							$new = 1;
						} else {
							$value = '';
							$content = $records[$i];
							$new = 2;
						}
					}

					if($new == 1) {
						$sign_count++;
						$array[$sign_count][0] = $value;
						$array[$sign_count][1] = utf8_encode($content);
					} else {
						$value = trim($content);
						if(empty($value)) {
							if(isset($array[$sign_count][1])) {
								$content = $array[$sign_count][1]."<br><br>";
							} else {
								$content = "<br><br>";
							}
							$array[$sign_count][1] = $content;
						} else {
							$content = $array[$sign_count][1].utf8_encode($content);
							$array[$sign_count][1] = $content;
						}
					}
				}
					
				for($i=1;$i<count($array);$i++) {
					$sign = array("sign"=> $i,"content"=>$array[$i][1]);
					$arrayValue[] = $sign;
				}
				try {
					if( $this->saveFileContentToDatabase($arrayValue, $this)) {
						$this->content = $arrayValue;
						return true;
					} else {
						$this->Flash->error(__("An error occured"));
					}
				} 
				catch(Exception $ex)
				{
				  $this->Flash->error(__($ex->getMessage()));
				}
			}
		}
		catch(Exception $ex)
		{
			die($ex->getMessage());
		}
	}


	public function getContent($data)
	{
        $count = $this->SunSignPredictions->find('all')
                                  ->where(['scope' => $data->scope, 'sun_sign_id'=> $data->sign, 'schedule_date'=>$data->schedule_date, 'language' => $data->language])
                                  ->count();
        return $count;
	}

	public function saveFileContentToDatabase($arrayValue,$sign) {
		try {
			if(count($arrayValue)>=12) {
				for($i=0;$i<count($arrayValue);$i++) {
					$query = $this->SunSignPredictions->query();
					$query->insert(['sun_sign_id', 'scope', 'prediction', 'schedule_date', 'language'])
					    ->values([
					        'sun_sign_id' => $arrayValue[$i]['sign'],
					        'scope' => $sign->scope,
					        //'prediction' => strip_tags($arrayValue[$i]['content'], '<br/>'), //Changed by Krishna Gupta to remove <br> tags
					        'prediction' => str_replace('<br><br><br><br>', '', $arrayValue[$i]['content']), //Changed by Krishna Gupta to remove <br> tags
					        'schedule_date' => $sign->schedule_date,
					        'language' =>$sign->language
 					    ])
 					    ->execute();
				}
			} else {
				return false;
			}
		}
		catch(Exception $ex)
		{
			$this->Flash->error(__($ex->getMessage()));
		}
		return true;	
	}
	

	private function validateLanguage($language, $format) {
		switch ($language) {
			case 'uk' :
				$this->language = 'en';
				break;
			case 'dk' :
			case 'nd' :
				$this->language = 'dk';
				break;
			case 'se' :
				$this->language = 'se';
				break;
			default:
				/* error */
				$this->Flash->error(__( "Unrecognised language (" . $language . ") in filename. " . "Format is '" . $format . "' " . "where LL in UK, DK, or SE."));
		}
	}

	/*** Week number to scheduled_date ***/
	private function WeekToScheduleDate($week, $month, $year)
	{
        $date = $year."-".$month."-".$week;
    	return date('Y-m-d', strtotime('-1 days', strtotime($date)));
	}
	
	/*
	 * This function is used to add Sun sign predictions.
	 * Created By : Sehdev Singh
	 * Created On : Nov 26, 2016
	 * Last Modified: Nov 28, 2016
	 */
	public function add() {
		$entity = $this->SunSignPredictions->newEntity();
		$sunSignOptions = $this->SunSigns->find('list')
										->order(['id' => 'ASC']);

		if($this->request->is('post')) {
			$data = $this->request->data;
          	$data['schedule_date'] = $this->formatDashDateForDb($data['schedule_date']);
            $data['startDate']     = $this->formatDashDateForDb($data['startDate']);
            $data['endDate']		= $this->formatDashDateForDb($data['endDate']);
            $scope = $data['scope'];
           	switch($scope) {
           		case 2:
           			//$data['schedule_date'] =  date('Y-m-d', strtotime('-1 days', strtotime($this->request->data['startDate'])));
           			/*
           			 * Schedule date for weekly horoscope were inserted wrong so I've to modify this code
           			 * Developer : Kingslay
           			 * Modified date : April 14, 2017
           			 */
           			$data['schedule_date'] = date('Y-m-d', strtotime('last Sunday', strtotime(str_replace('/', '-', $this->request->data['startDate']))));
           			// END
           			break;
          	 	case 3:
          	 		$data['schedule_date'] = date('Y-m-01', strtotime($data['schedule_date']));
          	 		break;
          	 	case 4:
          	 		$data['schedule_date'] = date('Y-01-01', strtotime($data['schedule_date']));
          	 		break;
          	}

          	$prediction = $this->SunSignPredictions->find('all')
                                                    ->where(['schedule_date' => $data['schedule_date'], 'language' => $data['language'], 'sun_sign_id'=> $data['sun_sign_id'], 'scope' => $scope])
                                                    ->first();

            if(!empty($prediction)) {
            	$entity = $this->SunSignPredictions->get($prediction['id']);
            } else {
            	$entiy = $this->SunSignPredictions->newEntity();
            }

            $entity = $this->SunSignPredictions->patchEntity($entity, $data);
            if($this->SunSignPredictions->save($entity)) {
            	$this->Flash->success(__('Data added succesfully'));
        		$this->redirect(['controller'=>'SunSignPredictions', 'action' => 'add']);
        	} else {
        		$this->Flash->error(__('Error saving data'));
        	}
        }
        $this->set(compact('sunSignOptions'));
        $this->set('form',$entity);

    }

 /**
   This function is used to search Sun sign predictions from the database.
   Created By : Sehdev Singh
   Created On : Nov 26, 2016
   Last Modified: Nov 28, 2016
 **/

 public function searchPrediction()
 {
 		  $entity = $this->SunSignPredictions->newEntity();
          $sunSignOptions = $this->SunSigns->find('list');
          
          if($this->request->is('post'))
          {
			$data = $this->request->data;
			$data['schedule_date'] = $this->formatDashDateForDb($data['schedule_date']);
            $data['startDate']     = $this->formatDashDateForDb($data['startDate']);
            $data['endDate']		= $this->formatDashDateForDb($data['endDate']);
            $scope = $data['scope'];
        	switch($scope)
          	 {
          	 	case 1:  
          	 	              $data['schedule_date'] = $data['schedule_date'];
          	 	              break;
          	 	case 2:      
          	 	              $data['schedule_date'] =  date('Y-m-d', strtotime('-1 days', strtotime($this->request->data['startDate'])));
          	 	              break;
          	 	case 3:       
          	 	              $data['schedule_date'] = date('Y-m-01', strtotime($data['schedule_date']));
          	 	              break;

          	 	case 4:       $data['schedule_date'] = date('Y-01-01', strtotime($data['schedule_date']));
          	 	              break;

          	 }

            $prediction = $this->SunSignPredictions->find('all')
            									   ->contain(['SunSigns'])
			                                       ->where(['sun_sign_id' => $data['sun_sign_id'], 'scope' => $data['scope'], 'language' => $data['language'], 'schedule_date' => $data['schedule_date']])
			                                       ->select(['id', 'schedule_date', 'SunSigns.name'])
			                                       ->first();

            if(empty($prediction))
            {
            	$this->Flash->error('No Data Found');
            }                         
           else
            {
                $this->set(compact('prediction'));
			}

          }

          $this->set(compact('sunSignOptions'));
          $this->set('form', $entity);

 }
 /**
   This function is used to edit Sun sign predictions.
   Created By : Sehdev Singh
   Created On : Nov 28, 2016
 **/
public function edit($id = 0)
 { 
    $entity = $this->SunSignPredictions->get($id);
    $sunSignOptions = $this->SunSigns->find('list');
    if($this->request->is(['post', 'put']))
    {
        $data = $this->request->data;
	    $scope = $data['scope'];
		switch($scope)
      	 {
      	 	case 1:  
      	 	              $data['schedule_date'] = $data['schedule_date'];
      	 	              break;
      	 	case 2:      
      	 	              if(isset($data['startDate']) && !empty($data['startDate']))
      	 	              {
        	 	            $data['schedule_date'] =  date('Y-m-d', strtotime('-1 days', strtotime($this->request->data['startDate'])));
      	 	              }
      	 	              else
      	 	              {
      	 	                $data['schedule_date'] = date('Y-m-d', strtotime($data['schedule_date']));
     	 	              }
      	 	              break;
      	 	case 3:       
      	 	              $data['schedule_date'] = date('Y-m-01', strtotime($data['schedule_date']));
      	 	              break;

      	 	case 4:       $data['schedule_date'] = date('Y-01-01', strtotime($data['schedule_date']));
      	 	              break;

      	 }

      	 $this->SunSignPredictions->patchEntity($entity, $data);
	     if ($this->SunSignPredictions->save($entity)) 
	     {
            $this->Flash->success(__('Sun sign prediction has been updated.'));
         }
         else
         {
            $this->Flash->error(__('Unable to update sun sign prediction.'));
         } 
    }
    $this->set(compact('sunSignOptions'));
	$this->set('form', $entity);
 }
}
?>