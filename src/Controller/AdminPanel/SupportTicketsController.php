<?php
	namespace App\Controller\AdminPanel;
	use App\Controller\AppController;
	use Cake\ORM\TableRegistry;
	use Cake\I18n\I18n;

	class SupportTicketsController extends AppController {
		private $uploadDir = WWW_ROOT.'uploads'.DS.'tickets'.DS;	
		public function initialize() {
			parent::initialize();
			$this->loadComponent('Paginator');
			$this->loadModel('CommentFiles');
			$this->Auth->config('checkAuthIn', 'Controller.initialize');
			$this->viewBuilder()->layout('admin');
			$this->loadComponent('FileUpload.FileUpload',[
	            'defaultThumb'=>[
	                'sm' => [100,100]
	            ],
	            'uploadDir' => $this->uploadDir,
	            'maintainAspectRation'=>true
        	]);
        	$approval_status = ['Rejected', 'Approved', 'Pending'];
        	$this->set(compact('approval_status'));
		}

		public function openTickets() {
			$session = $this->request->session();
	  	    $entity  = $this->SupportTickets->newEntity();
			$opened_paginate = [
					'join' => [
						'Users' => [
							'table' => 'users',
							'conditions' => ['Users.id = SupportTickets.user_id']
						],
						'Profiles' => [
							'table' => 'profiles',
							'conditions' => ['Profiles.user_id = SupportTickets.user_id']
						]
					],
					'fields' => ['SupportTickets.id', /*'recentActivity' => 'MAX(SupportTickets.created)', */'SupportTickets.user_id', 'SupportTickets.subject', 'SupportTickets.description', 'SupportTickets.commented_by', 'SupportTickets.status', 'SupportTickets.handled_by', 'SupportTickets.approved', 'SupportTickets.approved_on', 'SupportTickets.parent_id'/*, 'SupportTickets.lft', 'SupportTickets.rght'*/, 'SupportTickets.created', 'SupportTickets.modified', 'Users.username', 'Profiles.first_name', 'Profiles.last_name'],
					'conditions' => ['AND' => ['SupportTickets.status' => '1', 'parent_id IS ' => NULL] ],
					'order' => ['SupportTickets.id' => 'DESC', 'SupportTickets.created' => 'DESC'], 
					'limit' => 25
			];
			//pr($opened_paginate); die;
	  	    $this->set('opened', $this->Paginator->paginate($this->SupportTickets->find(), $opened_paginate));
	  	  	$this->set('form', $entity);
		}

		public function closedTickets() {	
			$session = $this->request->session();
	  	    $entity  = $this->SupportTickets->newEntity();
			$closed_paginate = [
					'join' => [
						'Users' => [
							'table' => 'users',
							'conditions' => ['Users.id = SupportTickets.user_id']
						],
						'Profiles' => [
							'table' => 'profiles',
							'conditions' => ['Profiles.user_id = SupportTickets.user_id']
						]
					],
					'fields' => ['SupportTickets.id', 'SupportTickets.subject', 'SupportTickets.created', 'SupportTickets.modified', 'Users.username', 'Profiles.first_name', 'Profiles.last_name'],
					'conditions' => ['AND' => ['SupportTickets.status' => '2', 'parent_id IS ' => NULL] ],
					'order' => ['SupportTickets.id' => 'DESC'],
					'limit' => 25
			];
			/*$data = $this->Paginator->paginate($this->SupportTickets->find(), $closed_paginate);
			pr($data); die;*/
	  	    $this->set('closed', $this->Paginator->paginate($this->SupportTickets->find(), $closed_paginate));	
	  	    $this->set('form', $entity);
		}

		public function delete( $status = null, $id = null) {
			if(!empty($id)) {
				$entity = $this->SupportTickets->find()->select(['id'])->where(['OR' => ['id' => $id, 'parent_id' => $id]])->toArray();
				$this->loadModel('CommentFiles');
				foreach ($entity as $key => $value) {
					$cRecord = $this->CommentFiles->find()->select(['id', 'file', 'support_ticket_id'])->where(['support_ticket_id' => $value['id']])->first();
					if(!empty($cRecord)) {
						unlink($_SERVER['DOCUMENT_ROOT'].'/webroot/uploads/tickets/'.$cRecord['file']);
						$CommentFilesEntity = $this->CommentFiles->get($cRecord['id']);
              			$this->CommentFiles->delete($CommentFilesEntity);
					}
					$SupportTicketsEntity = $this->SupportTickets->get($value['id']);
          			$this->SupportTickets->delete($SupportTicketsEntity);
				}
				//if ($this->SupportTickets->delete($entity)) {
	     	    	$this->Flash->success('Record has been deleted');
	   		    //}
	   		} else {
	   			$this->Flash->error('Something went wrong.');
	   		}

	   		if( strtolower($status) == 'opened' ) {
    			return $this->redirect(['controller' => 'support-tickets', 'action' => 'open-tickets']);
    		} else {
     		 return $this->redirect(['controller' => 'support-tickets', 'action' => 'closed-tickets']);
    		}

		}

		public function view($status, $id) {
			$entity = $this->SupportTickets->newEntity();
			if($this->request->is(['post']) && isset($this->request->data['comment'])) { // On submit comment form
				$ticketData = $this->SupportTickets->get($id);
				
				if($ticketData['approved'] == 2) {
					$this->request->session()->write('commentData', addslashes($this->request->data['description']));
					//$this->request->session()->write('commentData', $this->request->data['description']);
					$this->Flash->error( __('Please select Ticket Approval status first.'));
					return $this->redirect(['controller' => 'support-tickets', 'action' => 'view', $status, $id]);
				}

				if(empty($this->request->data['description'])) {
					//$this->request->session()->write('commentData', addslashes($this->request->data['description']));
					$this->Flash->error( __('Please write something into the comment box.'));
					return $this->redirect(['controller' => 'support-tickets', 'action' => 'view', $status, $id]);
				} else {
					$this->request->data['description'] = addslashes($this->request->data['description']);
				}
				$data = $this->request->data;
				$data['approved'] = 1;
				$data['admin_message_read'] = 1;
				$data['approved_on'] = date('Y-m-d H:i:s');
				$ticketData = $this->SupportTickets->get($id);
				$data['user_id'] = $ticketData['user_id'];

				if($data['file']['name'] != '') {
					$fileType = explode('/', $data['file']['type']);
					$type = $fileType[1];
					if($type == 'pdf' || $type == 'jpg' || $type == 'png' || $type == 'gif' || $type == 'docx' || $type == 'doc' || $type == 'jpeg') {
						$file['file'] = $this->FileUpload->doFileUpload($data['file']);
	                } else {
	                	$this->Flash->error( __('You uploaded a wrong format') );
	                	$this->redirect(['controller' => 'support-tickets', 'action' => 'view', $status, $id]);
	                }
				}
				$entity = $this->SupportTickets->patchEntity( $entity , $data);
				
				if( $this->SupportTickets->save($entity) ) {
					if($data['file']['name'] != '') {
					 	$file['support_ticket_id'] = $entity->id;
					 	$fileEntity = $this->CommentFiles->newEntity();
		                $fileEntity = $this->CommentFiles->patchEntity($fileEntity, $file);
		                $this->CommentFiles->save($fileEntity);
		                
		            }
		            $this->request->session()->delete('commentData');

		            /*Send notification mail to user start*/
		            $ThankyouMailOtherActionsTable = TableRegistry::get('ThankyouMailOtherActions');
                	$ThankyouMailOtherActionsEntity = $ThankyouMailOtherActionsTable->newEntity();
					$this->loadModel('Profiles');
					if($ticketData['locale'] == 2){
						$this->loadModel('I18n');
						$emailTemplate = $this->I18n->find()->select(['content'])->where(['I18n.locale' => 'da', 'I18n.model' => 'EmailTemplates', 'I18n.foreign_key' => '32', 'I18n.field' => 'content'])->first();
						$mailSubject = 'Svar på dit support spørgsmål';
					} else {
						$this->loadModel('EmailTemplates');
		        		$emailTemplate = $this->EmailTemplates->find()->select(['content'])->where(['short_code' => 'support_ticket_reply_to_user'])->first();
						$mailSubject = 'Reply on Support ticket';
		        	}
		        	$userNameData = $this->Profiles->find()
		        									->join([
		        											'Users' => [
		        												'table' => 'users',
		        												'type' => 'inner',
		        												'conditions' => [
		        													'Users.id = Profiles.user_id'
		        												]
		        											]
		        										])
		        									->select(['Profiles.first_name', 'Profiles.last_name', 'Users.username'])
		        									->where(['Profiles.user_id' => $ticketData['user_id']])->first();
	        		$name = ucwords($userNameData['first_name'].' '.$userNameData['last_name']);
	        		$msgBody = str_replace('{NAME}', $name, $emailTemplate['content']);
	        		$msgBody = str_replace('{SUBJECT}', $ticketData['subject'], $msgBody);
	        		$msgBody = str_replace('{COMMENT}', stripslashes($this->request->data['description']), $msgBody);
					$ThankyouMailOtherActionsDataArr['subject'] = $mailSubject.' - AstroWow';
					$ThankyouMailOtherActionsDataArr['send_to'] = $userNameData['Users']['username'];
					$ThankyouMailOtherActionsDataArr['content'] = $msgBody;
					$ThankyouMailOtherActionsDataArr['action'] = 'Support Ticket Reply';
					$ThankyouMailOtherActionsDataArr['mail_status'] = 0;
					$ThankyouMailOtherActionsDataArr['created'] = date('Y-m-d H:i:s');
                  	$ThankyouMailOtherActionsDataArr['modified'] = date('Y-m-d H:i:s');

                  	$thank_you = $ThankyouMailOtherActionsTable->patchEntity($ThankyouMailOtherActionsEntity, $ThankyouMailOtherActionsDataArr);
                  	$ThankyouMailOtherActionsTable->save($thank_you);
		            /*Send notification mail to user end*/

					$this->Flash->success( __('Your comment has been posted.'));
					return $this->redirect(['controller' => 'support-tickets', 'action' => 'view', $status, $id]);
				} else {
					$data = $this->getTicketDetails($this->request->data['parent_id']);
					$this->Flash->error( __('Please your comment section.') );
				}
			} elseif($this->request->is(['post'])) { //On submit handleBy form
				$entity = $this->SupportTickets->get($id);
                $data = $this->request->data;
                if(empty($data['status'])) {
                	unset($data['status']);
                }
                if(empty($data['handled_by'])) {
                	unset($data['handled_by']);
                }

                $supportTicketSubject = $this->SupportTickets->find()->where(['SupportTickets.id' => $data['support_ticket_id']])->select(['subject', 'handled_by'])->first();

                $this->SupportTickets->patchEntity($entity, $data);
		        if ($this->SupportTickets->save($entity)) {

		        	// Send mail if support ticket is assigned to anybody
		        	if($supportTicketSubject['handled_by'] != $data['handled_by']) { //if new assigned and already assigned user is different
			        	$this->loadModel('EmailTemplates');
			        	$emailTemplate = $this->EmailTemplates->find()->select(['content'])->where(['short_code' => 'support_ticket_handle_by'])->first();
			        	/*$supportTicketHandleByEmail = [1 => 12619, 2 => 616, 3 => 17];
			        	//$supportTicketHandleByEmail = [1 => 7008, 2 => 7008, 3 => 7008];
			        	$this->loadModel('Users');
		        		$userdetail = $this->Users->find()->where(['Users.id' => $supportTicketHandleByEmail[$data['handled_by']]])->contain(['Profiles'])->select(['Profiles.first_name', 'Profiles.last_name', 'Users.username'])->first();*/
		        		$userdetail = array();
		        		if($data['handled_by'] == 1) {
		        			$userdetail['Users']['username'] = 'astrowow@nethues.com';
		        			$userdetail['Profiles']['first_name'] = 'Nethues';
		        			$userdetail['Profiles']['last_name'] = 'Group';
		        		} elseif ($data['handled_by'] == 2) {
		        			$userdetail['Users']['username'] = 'ard@world-of-wisdom.com';
		        			$userdetail['Profiles']['first_name'] = 'Adrian';
		        			$userdetail['Profiles']['last_name'] = 'Duncan';
		        		} elseif ($data['handled_by'] == 3) {
		        			$userdetail['Users']['username'] = 'gabriela@123789.org'; //'kingslay@123789.org';
		        			$userdetail['Profiles']['first_name'] = 'Krishn';
		        			$userdetail['Profiles']['last_name'] = 'Kumar';
		        		} else {
		        			$userdetail['Users']['username'] = 'krishn@nethues.com';
		        			$userdetail['Profiles']['first_name'] = 'Krishn';
		        			$userdetail['Profiles']['last_name'] = 'Kumar';
		        		}


		        		$supportTicketSubject = $this->SupportTickets->find()->where(['SupportTickets.id' => $data['support_ticket_id']])->select(['subject'])->first();
		        		$to = $userdetail['username'];
		        		$name = $userdetail['profile']['first_name'].' '.$userdetail['profile']['last_name'].',';
		        		$msgBody = str_replace('{NAME}', $name, $emailTemplate['content']);
		        		$msgBody = str_replace('{SUBJECT}', $supportTicketSubject['subject'], $msgBody);
						$this->sendMail($to, __('A new Support ticket assigned - AstroWow'), $msgBody);
					}
					//END

		        	$this->Flash->success(__('Support ticket has been assigned successfully.'));
		            if( strtolower($status) == 'opened' ) {
		            	return $this->redirect(['controller' => 'support-tickets', 'action' => 'open-tickets']);
		            } else {
		            	return $this->redirect(['controller' => 'support-tickets', 'action' => 'closed-tickets']);
		            }
				}
				$this->Flash->error(__('Unable to update your article.'));
			} else { // For default
				$data = $this->getTicketDetails($id);
				$query = $this->SupportTickets->query();
          		$updateData = $query->update()
                        ->set(['admin_message_read' => 1])
                        ->where(['OR' => ['id' => $id, 'parent_id' => $id]])
                        ->execute();
                $this->checkUnreadMessage();
	        }
            $this->set(compact('data')); 
            $this->set('form', $entity);
            $this->set('id', $id);
            $this->set('status', $status);
		}

		private function getTicketDetails($id){
			return $this->SupportTickets->find('all')
			    							 ->contain(['CommentFiles'])
		                                     ->hydrate(false)
		                                     ->join([
		                                            'profiles' => [
		                                                'table' => 'profiles',
		                                                'type' => 'INNER',
		                                                'conditions' => [
		                                                    'profiles.user_id = SupportTickets.user_id',
		                                                ] 
		                                            ]

        	                                   ])
	                                    ->where(['SupportTickets.id' => $id])
	                                    ->select(['profiles.first_name', 'profiles.last_name','SupportTickets.subject', 'SupportTickets.description', 'SupportTickets.commented_by', 'SupportTickets.status', 'SupportTickets.created', 'SupportTickets.approved', 'CommentFiles.file', 'SupportTickets.handled_by'])
	                                    ->first();
		}
	
		public function change() {
			$this->autoRender = false;	
			if($this->request->is('post')) {
				$query = $this->SupportTickets->query();
				$status = $this->request->data['val'];
				$id = $this->request->data['id'];
  				switch ($status) {
  					case '0':
  							$newStatus = 1;
  							break;
 					case '1':
 							$newStatus = 0;
 							break;
  				}
				$query->update()  
						->set(['approved' => $newStatus, 'approved_on' => date('Y-m-d H:i:s')])
						->where(['id' => $id])
						->execute();
				
				if ($newStatus) {
					$details = $this->SupportTickets->find()->where(['SupportTickets.id' => $id])->join([
																						'Profiles' => [
																							'table' => 'profiles',
																							'conditions' => ['SupportTickets.user_id = Profiles.user_id']
																						],
																						'Users' => [
																							'table' => 'users',
																							'conditions' => ['SupportTickets.user_id = Users.id']
																						]
																					])
																					->select(['Profiles.id', 'Profiles.user_id', 'Profiles.first_name', 'Profiles.last_name', 'SupportTickets.id', 'SupportTickets.user_id', 'SupportTickets.subject', 'Users.id', 'Users.username'])
																					->first();
					$name = ucwords($details['Profiles']['first_name'].' '.$details['Profiles']['last_name']).',';
					$to = $details['Users']['username'];
					$this->loadModel('EmailTemplates');
					$emailTemplate = $this->EmailTemplates->find()->where(['short_code' => 'support_ticket_notification_for_user'])->select(['content', 'short_code'])->first();
					$msg = str_replace('{NAME}', $name, $emailTemplate['content']);
					$msg = str_replace('{SUBJECT}', $details['subject'], $msg);
					$this->sendMail($to, 'Support ticket approved - AstroWow', $msg);
				}
				
				if($status == 0) {
  				  echo '<a href="javascript:changeApproveStatus('.$id.','.$newStatus.');" "><i class="fa fa-heart" style="color:green !important"></a>';
	 	        } else {
	 	          echo '<a href="javascript:changeApproveStatus('.$id.','.$newStatus.');" "><i class="fa fa-heart"></a>';
	 	        }
                exit();
	        }
		}

		function updateTicketStatus ($field, $ticketid, $value) {
			$data = [$field => $value, 'modified' => date('Y-m-d H:i:s')];
			if (strtolower($field) == 'approved') {
				$data = [$field => $value, 'approved_on' => date('Y-m-d H:i:s'), 'modified' => date('Y-m-d H:i:s')];
			}
			$this->autoRender = false;
			$query = $this->SupportTickets->query();
          	$updateData = $query->update()
                        ->set($data)
                        ->where(['id' => $ticketid])
                        ->execute();
            if ($updateData) {
            	if ($field == 'handled_by' || $field == 'approved') {
	            	if ($this->sendNotificationAboutSupportTicket($field, $value, $ticketid)) {
	            		echo 'yes'; die;
	            	} else {
	            		echo 'no'; die;
	            	}
	            } else {
	            	echo 'yes'; die;
	            }
            } else {
            	echo 'no'; die;
            }
		}


		protected function sendNotificationAboutSupportTicket ($field, $value, $ticketid) {
			$sendMailStatus = false;
			if ($field == 'handled_by') {
	        	$supportTicketHandleByEmail = [1 => 12619, 2 => 7008, 3 => 16399];
	        	$this->loadModel('EmailTemplates');
	        	$emailTemplate = $this->EmailTemplates->find()->select(['content'])->where(['short_code' => 'support_ticket_handle_by'])->first();

	        	$this->loadModel('Users');
	    		$userdetail = $this->Users->find()->where(['Users.id' => $supportTicketHandleByEmail[$value]])->contain(['Profiles'])->select(['Profiles.first_name', 'Profiles.last_name', 'Users.username'])->first();
	    		$supportTicketSubject = $this->SupportTickets->find()->where(['SupportTickets.id' => $ticketid])->select(['subject'])->first();

	    		$ticketCreatorUserDetails = $this->SupportTickets->find()
	    														->join([
	    																'Users' => [
	    																	'table' => 'users',
	    																	'conditions' => ['Users.id = SupportTickets.user_id']
	    																],
	    																'Profiles' => [
	    																	'table' => 'profiles',
	    																	'conditions' => ['Profiles.user_id = SupportTickets.user_id']
	    																]
	    															])
	    														->where(['SupportTickets.id' => $ticketid])
	    														->select(['Users.username', 'Profiles.first_name', 'Profiles.last_name'])
	    														->first();
	    		$to = $userdetail['username'];
	    		$name = $userdetail['profile']['first_name'].' '.$userdetail['profile']['last_name'].',';
	    		$msgBody = str_replace('{NAME}', $name, $emailTemplate['content']);
	    		$msgBody = str_replace('{SUBJECT}', $supportTicketSubject['subject'], $msgBody);
	    		$msgBody = str_replace('{USER_NAME}', ucwords($ticketCreatorUserDetails['Profiles']['first_name'].' '.$ticketCreatorUserDetails['Profiles']['last_name']), $msgBody);
	    		$msgBody = str_replace('{USER_EMAIL}', $ticketCreatorUserDetails['Users']['username'], $msgBody);
	    		$msgBody = str_replace('{FACEBOOK}', 'https://www.facebook.com/adrian.duncan.399', $msgBody);
				$msgBody = str_replace('{TWITTER}', 'https://twitter.com/AdrianDuncan', $msgBody);
				$msgBody = str_replace('{LINKEDIN}', 'https://www.linkedin.com/in/adrian-duncan-22a675/', $msgBody);
				$sendMailStatus = $this->sendMail($to, 'A new Support ticket has been assigned - AstroWow', $msgBody);
			} else {
				$getSupportTicketLang = $this->SupportTickets->find()->where(['SupportTickets.id' => $ticketid])->first();
				
				switch ($value) {
  					case '0':
  							$newStatus = 'rejected';
  							if($getSupportTicketLang['locale'] == 2){
  								$newStatus = 'afvist';
  							}
  							break;
 					case '1':
 							$newStatus = 'approved';
  							if($getSupportTicketLang['locale'] == 2){
  								$newStatus = 'godkendt';
  							}
 							break;
  				}
				$details = $this->SupportTickets->find()->where(['SupportTickets.id' => $ticketid])->join([
																					'Profiles' => [
																						'table' => 'profiles',
																						'conditions' => ['SupportTickets.user_id = Profiles.user_id']
																					],
																					'Users' => [
																						'table' => 'users',
																						'conditions' => ['SupportTickets.user_id = Users.id']
																					]
																				])
																				->select(['Profiles.id', 'Profiles.user_id', 'Profiles.first_name', 'Profiles.last_name', 'SupportTickets.id', 'SupportTickets.user_id', 'SupportTickets.subject', 'Users.id', 'Users.username'])
																				->first();
				$name = ucwords($details['Profiles']['first_name'].' '.$details['Profiles']['last_name']).',';
				$to = $details['Users']['username'];
				
				if($getSupportTicketLang['locale'] == 2){
					$this->loadModel('I18n');
					$mailSubj = 'Dit support spørgsmål er ';
					$emailTemplate = $this->I18n->find()->where(['I18n.locale' => 'da', 'I18n.model' => 'EmailTemplates', 'I18n.foreign_key' => 19, 'I18n.field' => 'content'])->select(['content'])->first();
				} else {
					$this->loadModel('EmailTemplates');
					$mailSubj = 'Support ticket has been ';
					$emailTemplate = $this->EmailTemplates->find()->where(['short_code' => 'support_ticket_notification_for_user'])->select(['content', 'short_code'])->first();
				}
				
				

				$msg = str_replace('{NAME}', $name, $emailTemplate['content']);
				$msg = str_replace('{SUBJECT}', $details['subject'], $msg);
				$msg = str_replace('{STATUS}', $newStatus, $msg);
				$msg = str_replace('{STATUS_HEADER}', ucfirst($newStatus), $msg);
				$msg = str_replace('{FACEBOOK}', 'https://www.facebook.com/adrian.duncan.399', $msg);
				$msg = str_replace('{TWITTER}', 'https://twitter.com/AdrianDuncan', $msg);
				$msg = str_replace('{LINKEDIN}', 'https://www.linkedin.com/in/adrian-duncan-22a675/', $msg);

				$sendMailStatus = $this->sendMail($to, $mailSubj.$newStatus.' - AstroWow', $msg);
			}
			//echo 'HEEEEEEEEEEEE => '.$sendMailStatus; die;
			if ($sendMailStatus) {
				echo 'yes'; die;
			} else {
				echo 'no'; die;
			}
		}
	}
?>
