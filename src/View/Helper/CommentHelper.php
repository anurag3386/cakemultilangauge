<?php
namespace App\View\Helper;
use Cake\View\Helper;
use Cake\ORM\TableRegistry;
use Cake\I18n\I18n;

class CommentHelper extends Helper
{
    public function getComments($id)
    {
    	$SupportTickets = TableRegistry::get('SupportTickets');
    	$data           = $SupportTickets->find('all')
    	 								 ->contain('CommentFiles')
    	                                 ->where(['SupportTickets.parent_id' => $id])
				    	                 ->order(['SupportTickets.created' => 'ASC'])
				    	                 ->toArray();
    	return $data;                   
    }

    public function getLastComment($id) {
      $SupportTickets = TableRegistry::get('SupportTickets');
      $data = $SupportTickets->find()->select(['SupportTickets.created'])->where(['OR' => ['SupportTickets.id' => $id, 'SupportTickets.parent_id' => $id]])->order(['SupportTickets.created' => 'DESC'])->first();
      if (!empty($data)) {
        return date('d/m/Y h:i A', strtotime($data['created']));
      } else {
        return false;
      }
   		//$node = $SupportTickets->get($id);
		/*if($SupportTickets->childCount($node) > 0)
		{
			$descendants = $SupportTickets->find('children', ['for' => $id])->toArray();
			return ( date('d/m/Y H:i:s', strtotime ( end($descendants)['created']) ) );
    	}
		else
		{
           return false;
		}*/
    }

    public function getLastCommentedBy($id) {
      $SupportTickets = TableRegistry::get('SupportTickets');
      $data = $SupportTickets->find()->select(['SupportTickets.commented_by'])->where(['OR' => ['SupportTickets.id' => $id, 'SupportTickets.parent_id' => $id]])->order(['SupportTickets.created' => 'DESC'])->first();
      if ($data['commented_by'] == 2) {
        return 'User';
      } else {
        return 'Admin';
      }
    }

}