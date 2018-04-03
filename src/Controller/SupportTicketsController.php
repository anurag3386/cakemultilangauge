<?php 
 namespace App\Controller;
 use Cake\Routing\Router;
 class SupportTicketsController extends AppController
 {
 	private $uploadDir = WWW_ROOT.'uploads'.DS.'tickets'.DS;
    public function initialize() {
		parent::initialize();
		$this->loadComponent('Paginator');
		$this->viewBuilder()->layout('');
		//$this->SupportTickets->recover();
		$this->loadModel('CommentFiles');
		$this->loadComponent('FileUpload.FileUpload',[
            'defaultThumb'=>[
                'sm' => [100,100]
            ],
            'uploadDir' => $this->uploadDir,
            'maintainAspectRation'=>true
        ]);
        $this->viewBuilder()->layout('home');
    	$approval_status = ['Rejected', 'Approved', 'Pending'];
    	$this->set(compact('approval_status'));
	}	

	public function index($type='') {
		$session = $this->request->session();
  	    $entity  = $this->SupportTickets->newEntity();
        $user_id = $session->read('user_id');
    	if( !isset($user_id) || empty($user_id)  ) {
    		$this->request->session()->write('support-ticket-url', Router::url());
	   		$this->Flash->error(__('Please login to access support ticket section') );
       		return $this->redirect(['controller' => 'users', 'action' => 'login']);
		}
		if(!empty($type) && ($type == __('closed'))) {
			$closed = [
					'conditions' => ['SupportTickets.status' => 2, 'parent_id IS' => NULL, 'user_id' => $user_id],
					'order' => ['SupportTickets.id' => 'DESC'], 
					'limit' => 5
			];
			$this->set('closed', $this->Paginator->paginate($this->SupportTickets->find(), $closed));
		} else {
			$opened = [
					'conditions' => ['SupportTickets.status' => 1, 'parent_id IS' => NULL, 'user_id' => $user_id],
					'order' => ['SupportTickets.id' => 'DESC'], 
					'limit' => 5
			];
			$this->set('opened', $this->Paginator->paginate($this->SupportTickets->find(), $opened));
    	}
		$this->set('form', $entity);
        
        if($this->request->is(['post'])) {
        	if(empty($this->request->data['description'])/* || (strlen($this->request->data['description']) < 100)*/) {
        		$this->request->session()->write('create-support-ticket-data', $this->request->data);
        		$this->Flash->error( __('Please fill all the fields') );
                return $this->redirect(['controller' => 'support-tickets', 'action' => 'index']);
        	}
			$data = $this->request->data;
			$data['user_id'] = $session->read('user_id');
			$data['commented_by'] = '2';
			$data['approved'] = '2';
			$data['user_message_read'] = 1;
			$data['mail_sent'] = 1;
			$data['locale'] = 1;
			if($this->request->session()->read('locale') == 'da'){
				$data['locale'] = 2;
			}

			if($data['file']['name'] != '') {
				$fileType = explode('/', $data['file']['type']);
				$type = $fileType[1];
				//if($type == 'pdf' || $type == 'jpg' || $type == 'png' || $type == 'gif' || $type == 'docx' || $type == 'doc' || $type == 'jpeg') {
				if($type == 'jpg' || $type == 'png' || $type == 'gif' || $type == 'jpeg') {
					$file['file'] = $this->FileUpload->doFileUpload($data['file']);
                } else {
                	$this->request->session()->write('create-support-ticket-data', $this->request->data);
                  	$this->Flash->error( __('Please upload a valid image format') );
                  	return $this->redirect(['controller' => 'support-tickets', 'action' => 'index']);
                }
			}
			$entity = $this->SupportTickets->patchEntity( $entity , $data);
			if( $this->SupportTickets->save($entity) ) {
				$this->request->session()->delete('create-support-ticket-data');
			 	if($data['file']['name'] != '') {
				 	$file['support_ticket_id'] = $entity->id;
				 	$fileEntity = $this->CommentFiles->newEntity();
	                $fileEntity = $this->CommentFiles->patchEntity($fileEntity, $file);
                    $this->CommentFiles->save($fileEntity);
	            }
	            $this->request->session()->delete('create-support-ticket-data');

	            //$this->sendSupportTicketMail('support_ticket_thank_you');


	            /*$this->loadModel('EmailTemplates');
	            $emailTemplate = $this->EmailTemplates->find()->where(['short_code' => 'support_ticket_thank_you'])->select(['content', 'short_code'])->first();*/
	            $name = ucwords($this->request->session()->read('Auth.UserProfile.first_name').' '.$this->request->session()->read('Auth.UserProfile.last_name'));
	            $to = $this->request->session()->read('Auth.User.username');

	            //$this->sendSupportTicketMail('support_ticket_thank_you', 'Support ticket has been raised successfully - AstroWow', $to, $name, $data['subject']);
	            //$this->sendSupportTicketMail('new_support_ticket_nofication_for_admin', 'A new support ticket has been raised', 'kingslay@123789.org', $name, $data['subject']);

	            /*$msg = str_replace('{NAME}', $name, $emailTemplate['content']);
	            $msg = str_replace('{SUBJECT}', $data['subject'], $msg);
	            $msg = str_replace('{FACEBOOK}', 'https://www.facebook.com/adrian.duncan.399', $msg);
				$msg = str_replace('{TWITTER}', 'https://twitter.com/AdrianDuncan', $msg);
				$msg = str_replace('{LINKEDIN}', 'https://www.linkedin.com/in/adrian-duncan-22a675/', $msg);
	            $this->sendMail($to, 'Support ticket has been raised successfully - AstroWow', $msg);*/


			 	$this->Flash->success( __("Thank you, your support ticket has been sent to administration") );
			 	return $this->redirect(['controller' => 'support-tickets', 'action' => 'index']);
			} else {
				$this->Flash->error( __('Please fill all the fields') );
			}
        }
	}

	private function sendSupportTicketMail($short_code, $subject, $to, $name, $ticketSubject) {
		$this->loadModel('EmailTemplates');
		$emailTemplate = $this->EmailTemplates->find()->where(['short_code' => $short_code])->select(['content'])->first();
		$msg = str_replace('{NAME}', $name, $emailTemplate['content']);
        $msg = str_replace('{SUBJECT}', $ticketSubject, $msg);
        $msg = str_replace('{EMAIL}', $this->request->session()->read('Auth.User.username'), $msg);
        $msg = str_replace('{FACEBOOK}', 'https://www.facebook.com/adrian.duncan.399', $msg);
		$msg = str_replace('{TWITTER}', 'https://twitter.com/AdrianDuncan', $msg);
		$msg = str_replace('{LINKEDIN}', 'https://www.linkedin.com/in/adrian-duncan-22a675/', $msg);
        if($this->sendMail($to, $subject, $msg)){
        	return true;
        } else {
        	return false;
        }
	}

	public function view($status, $id) {
		$id = base64_decode($id);
		$entity = $this->SupportTickets->newEntity();
		$session = $this->request->session();
		$user_id = $session->read('user_id');
		
		if( !isset($user_id) || empty($user_id)  ) {
			$this->request->session()->write('support-ticket-url', Router::url());
			$this->Flash->error(__('Please login to access support ticket section') );
        	return $this->redirect(['controller' => 'users', 'action' => 'login']);
		}

		$this->set('form', $entity);

		if($this->request->is(['post'])) {
			if(empty($this->request->data['description'])){
				$this->request->session()->write('comment-support-ticket-data', $this->request->data);
        		$this->Flash->error(__('Please fill all the fields'));
                return $this->redirect(['controller' => 'support-tickets', 'action' => 'view', $status, base64_encode($id)]);
			}
			$entity = $this->SupportTickets->newEntity();
        	$data = $this->request->data;
			$data['user_id'] = $session->read('user_id');
			$data['commented_by'] = '2';
			$data['user_message_read'] = 1;
			$ticketDetails = $this->SupportTickets->get($id);
			$data['subject'] = $ticketDetails['subject'];
			$data['locale'] = 1;
			if($this->request->session()->read('locale') == 'da'){
				$data['locale'] = 2;
			}

			if($data['file']['name'] != '') {
				$fileType = explode('/', $data['file']['type']);
				$type = $fileType[1];
				if($type == 'jpg' || $type == 'png' || $type == 'gif' || $type == 'jpeg') {
					$file['file'] = $this->FileUpload->doFileUpload($data['file']);
			    } else {
                  $this->Flash->error( __('Please upload a valid image format') );
                  $this->redirect(['controller' => 'support-tickets', 'action' => 'index']);
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
				
				if($this->checkIp()){
					$this->loadModel('Users');
					$this->loadModel('EmailTemplates');
					$supportTicketHandleByEmail = [1 => 12619, 2 => 7008, 3 => /*616*/16399];
					$handleByData = $this->Users->find()->select(['Users.username', 'Profiles.first_name', 'Profiles.last_name'])->contain(['Profiles'])->where(['Users.id' => $supportTicketHandleByEmail[$ticketDetails['handled_by']]])->first();
					$eTemplate = $this->EmailTemplates->find()->select(['EmailTemplates.content'])->where(['EmailTemplates.short_code' => 'user_comment_mail_to_handle_by'])->first();
					$myTemplate = str_replace('{NAME}', ucwords($handleByData['profile']['first_name'].' '.$handleByData['profile']['last_name']).',', $eTemplate['content']);
					$myTemplate = str_replace('{SUBJECT}', $ticketDetails['subject'], $myTemplate);
					$myTemplate = str_replace('{USER_NAME}', ucwords($this->request->session()->read('Auth.UserProfile.first_name').' '.$this->request->session()->read('Auth.UserProfile.last_name')), $myTemplate);
					$myTemplate = str_replace('{USER_EMAIL}', $this->request->session()->read('Auth.User.username'), $myTemplate);
					$userComment = implode(' ', array_slice(explode(' ', $this->request->data['description']), 0, 30));
					$userComment .= "<a href='https://www.astrowow.com/admin_panel/support-tickets/view/opened/$id'>More Info</a>";
					$myTemplate = str_replace('{USER_COMMENT}', $userComment, $myTemplate);
					$sendMailStatus = $this->sendMail($handleByData['username'], 'User reply on support ticket - AstroWow', $myTemplate);
				}

	            $this->request->session()->delete('comment-support-ticket-data');
			 	$this->Flash->success(__("Your comment has been posted."));
		 	    $this->redirect(['controller' => 'support-tickets', 'action' => 'view', $status, base64_encode($id)]);
		 	} else {
		 		$this->Flash->error( __('Please fill all the fields') );
		 	}
		} else {
			//$data = $this->getTicketDetails($id);
			$query = $this->SupportTickets->query();
      		$updateData = $query->update()
                    ->set(['user_message_read' => 1])
                    ->where(['OR' => ['id' => $id, 'parent_id' => $id]])
                    ->execute();
            $this->checkUnreadMessage();

			$checkStatus = (strtolower($status) == __('opened')) ? '1' : '2';
			$data = $this->SupportTickets->find('all')
			                               ->contain('CommentFiles')
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
		                                    ->where(['SupportTickets.id' => $id, 'SupportTickets.status' => $checkStatus, 'SupportTickets.user_id' => $user_id])
		                                    ->select(['profiles.first_name', 'profiles.last_name','SupportTickets.subject', 'SupportTickets.description', 'SupportTickets.commented_by', 'SupportTickets.status', 'SupportTickets.created', 'CommentFiles.file'])
		                                    ->first();
	    	if(empty($data)) {
	    		//$this->Flash->error( __('Please select support ticket properly.') );
	    		$this->Flash->error( __('You cannot access this support ticket.') );
	    		$this->redirect(['controller' => 'support-tickets', __($status)]);
	    	}
	    }
	    $this->set(compact('data'));
	    $this->set('status', $status);
	    $this->set('id', $id);	       
	}

	public function delete( $status = null, $id = null) {
		$this->autoRender = false;
		if(!empty($id)) {
			$id1 = base64_decode($id);
			if($status == __('opened')) {
				$checkRecord = $this->SupportTickets->find()->select(['id'])->where(['id' => $id1])->first();
				$this->loadModel('CommentFiles');
				$cRecord = $this->CommentFiles->find()->select(['id', 'file', 'support_ticket_id'])->where(['support_ticket_id' => $checkRecord['id']])->first();
				if(!empty($cRecord)) {
					unlink($_SERVER['DOCUMENT_ROOT'].'/webroot/uploads/tickets/'.$cRecord['file']);
					$CommentFilesEntity = $this->CommentFiles->get($cRecord['id']);
          			$this->CommentFiles->delete($CommentFilesEntity);
				}
				$SupportTicketsEntity = $this->SupportTickets->get($id1);
      			$this->SupportTickets->delete($SupportTicketsEntity);
	 	    	$this->Flash->success(__('Record has been deleted successfully'));
	 	    }/* else {
	 	    	$this->Flash->error('You can not delete closed ticket.');
	 	    }*/
   		} else {
   			$this->Flash->error(__('Something went wrong.'));
   		}
   		return $this->redirect(['controller' => 'support-tickets', 'action' => 'index']);
	}

	function edit ($id=NULL) {
		$session = $this->request->session();
		$user_id = $session->read('user_id');
		if( !isset($user_id) || empty($user_id)  ) {
			$this->request->session()->write('support-ticket-url', Router::url());
			$this->Flash->error(__('Please login to access support ticket section') );
        	return $this->redirect(['controller' => 'users', 'action' => 'login']);
		}
		if(!empty($id)) {
			$id = base64_decode($id);
		} else {
			$this->Flash->error( __('Something went wrong.') );
          	return $this->redirect(['controller' => 'support-tickets', 'action' => 'index']);
		}
		$session = $this->request->session();
  	    $user_id = $session->read('user_id');
		$entity = $this->SupportTickets->newEntity();
		$this->set('form', $entity);
		if($this->request->is(['post', 'put'])) {
			if(empty($this->request->data['description'])/* || (strlen($this->request->data['description']) < 100)*/) {
        		$this->request->session()->write('edit-support-ticket-data', $this->request->data);
        		//$this->Flash->error( __('Ticket description must be atleast 100 characters long.') );
                return $this->redirect(['controller' => 'support-tickets', 'action' => 'edit', base64_encode($id)]);
        	}
			$data = $this->request->data;
			$data['user_id'] = $user_id;
			$data['commented_by'] = '2';
			$data['approved'] = '2';
			$data['user_message_read'] = 1;
			$data['locale'] = 1;
			if($this->request->session()->read('locale') == 'da'){
				$data['locale'] = 2;
			}

			if($data['file']['name'] != '') {
				$fileType = explode('/', $data['file']['type']);
				$type = $fileType[1];
				//if($type == 'pdf' || $type == 'jpg' || $type == 'png' || $type == 'gif' || $type == 'docx' || $type == 'doc' || $type == 'jpeg') {
				if($type == 'jpg' || $type == 'png' || $type == 'gif' || $type == 'jpeg') {
					$file['file'] = $this->FileUpload->doFileUpload($data['file']);
                } else {
                  $this->Flash->error( __('Please upload a valid image format') );
                  $this->redirect(['controller' => 'support-tickets', 'action' => 'index']);
                }
			}
			$entity = $this->SupportTickets->patchEntity( $entity , $data);
			if( $this->SupportTickets->save($entity) ) {
				$this->request->session()->delete('edit-support-ticket-data');
				$this->loadModel('CommentFiles');
			 	$commentFilesExistancy = $this->CommentFiles->find()->where(['CommentFiles.support_ticket_id' => $entity->id])->first();
			 	if(empty($commentFilesExistancy)) {//Image not available before edit action
			 		if($data['file']['name'] != '') {// If image is uploaded on edit action
					 	$file['support_ticket_id'] = $entity->id;
					 	$fileEntity = $this->CommentFiles->newEntity();
		                $fileEntity = $this->CommentFiles->patchEntity($fileEntity, $file);
	                    $this->CommentFiles->save($fileEntity);
	            	}
			 	} else {//Image available before edit action
			 		if($data['file']['name'] != '') {// If image is uploaded on edit action
			 			unlink($_SERVER['DOCUMENT_ROOT'].'/webroot/uploads/tickets/'.$commentFilesExistancy['file']);//Remove old file from server
			 			$file['id'] = $commentFilesExistancy['id'];
					 	$file['support_ticket_id'] = $entity->id;
					 	$fileEntity = $this->CommentFiles->newEntity();
		                $fileEntity = $this->CommentFiles->patchEntity($fileEntity, $file);
	                    $this->CommentFiles->save($fileEntity);
	            	}
			 	}
			 	//$this->Flash->success( __("You have updated support ticket successfully.") );
			 	$this->Flash->success( __("Support ticket has been updated successfully.") );
			 	return $this->redirect(['controller' => 'support-tickets', 'action' => 'index']);
			} else {
				$this->Flash->error( __('Please fill all the fields') );
			}
		} else {
			if(!empty($id) && !is_null($id)){
				$this->request->session()->delete('edit-support-ticket-data');
				$checkRecord = $this->SupportTickets->find()
							->where(['SupportTickets.id' => $id, 'SupportTickets.user_id' => $user_id, 'SupportTickets.approved' => 2, 'SupportTickets.status' => 1])
							->first();
				//echo '<pre>'; print_r($checkRecord); die;
				if(empty($checkRecord)) {
					//$this->Flash->error('Ticket is not associated with you.');
					$this->Flash->error(__('You cannot access this support ticket.'));
	   				return $this->redirect(['controller' => 'support-tickets', 'action' => 'index']);
				} else {
					$this->request->data = $checkRecord;
				}

			} else {
	   			$this->Flash->error(__('Something went wrong.'));
	   			return $this->redirect(['controller' => 'support-tickets', 'action' => 'index']);
	   		}
	   	}
	}

 	
 }
?>