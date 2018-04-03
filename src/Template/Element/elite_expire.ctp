<?php use Cake\Cache\Cache;?>
<?php
	$expireOn = date ('F d, Y', $this->request->session()->read('EliteMemberDetail.expireOn'));
    
    // Memcache
    //$expireOn = date ('F dS, Y', Cache::read('EliteMemberDetail.expireOn'));

	if ($this->request->session()->read('Auth.User.role') == 'elite') {
		
		//if (strtolower(Cache::read('Auth.User')['role']) == 'elite') {

	echo "<div class='eliteUserName'>".strtoupper(__('welcome '). $this->request->session()->read('name'))." (".__('Membership Expires on').": ".$expireOn.")</div>";

		//echo "<div class='eliteUserName'>".strtoupper(__('welcome '). Cache::read('name'))." (".__('Membership Expires on:')." ".$expireOn.")</div>";
	}
	//elite_expire	
?>
