<?php use Cake\Routing\Router; ?>
<?php use Cake\I18n\I18n; ?>
<?php //use Cake\I18n\Time; ?>
<?php //echo '<pre>'; print_r($this->request->params['status']); die; ?>
<div class="container">
	<div id="create-testimonial-form">
		<div class="row">
			<h1><?= __('AstroWow Support'); ?></h1>
			<div class="intro-text"><?= __('If you have any problems with this website, the software, reports or payment issues, please fill out a support ticket. We endeavor to reply to all support issues within 48 hours during the working week.'); ?></div><br/>
			<?= $this->Flash->render();?>
			<?php
			$showTicketForm = false;
			if(!isset($this->request->params['status'])) {
				$showTicketForm = true;
			} else {
				if( (trim($this->request->params['status']) == __('opened'))) {
					$showTicketForm = true;
				}
			}

			if($showTicketForm) {
				$createTicketErrorDataSubject = !empty($this->request->session()->read('create-support-ticket-data.subject')) ? $this->request->session()->read('create-support-ticket-data.subject') : '';
				$createTicketErrorDataDescription = !empty($this->request->session()->read('create-support-ticket-data.description')) ? $this->request->session()->read('create-support-ticket-data.description') : '';
				?>


				<div class="notifications-container">
					<div class="notificationList">
						<div class="notification-bell">
							<p>
								<?php
									if($this->request->session()->read('userNotification.count')){
										echo $this->request->session()->read('userNotification.count');
									} else {
										if(count($this->request->session()->read('userNotification.data'))) {
											echo count($this->request->session()->read('userNotification.data'));
										} else {
											echo '0';
										}
									}
								?>
							</p>
						</div>
					</div>
					<div class="dropdown hideNotificationDropdown">
		  				<a id="dLabel" role="button" data-toggle="dropdown" data-target="#" href="/page.html">
		    				<i class="glyphicon glyphicon-bell"></i>
		  				</a>
		  
		  				<ul class="dropdown-menu notifications" role="menu" aria-labelledby="dLabel">
		    				<li class="divider"></li>
		    				<div class="notifications-wrapper">
						      	<ul class="user-notification-list">
						        <?php foreach ($this->request->session()->read('userNotification.data') as $key => $value) { ?>
						          	<li>
						          		<?php
						          			$notificationLink = '';
						          			if($this->request->session()->read('locale') == 'dk' || $this->request->session()->read('locale') == 'da') {
						          				$notificationLink = Router::url('/', true).'dk/'.__('support-tickets').'/'.__($value['status']).'/'.base64_encode($value['support_ticket_id']);
						          			} else {
						          				$notificationLink = Router::url('/', true).__('support-tickets').'/'.__($value['status']).'/'.base64_encode($value['support_ticket_id']);
						          			}
						          		?>
						            	<a class="content" href="<?= $notificationLink; ?>">
						              		<div class="notification-item">
						                		<h4 class="item-title"><?php echo $subject = (strlen($value['subject']) > 40) ? substr($value['subject'],0,40).'...' : $value['subject']; ?></h4>
						                		<p class="item-info"><?php echo $description = (strlen($value['description']) > 50) ? substr($value['description'],0,50).'...' : $value['description']; ?></p>
						                		<p class="item-info2"><?= 'By Admin on '.date('M d, Y h:i A', strtotime($value['created'])); ?></p>
						              		</div>
						            	</a>
						          	</li>
						        <?php } ?>
						      	</ul>
		    				</div>
		    				<li class="divider"></li>
		  				</ul>
					</div>
				</div>








				<div class="panel panel-primary">
					<div class="panel-heading"><?= __('Create Ticket'); ?></div>
					<div class="panel-body">
						<?= $this->Form->create($form, [/*'class' => 'form-horizontal', */'id' => 'support-tickets', 'enctype' => 'multipart/form-data', 'novalidate' => true]) ?>
							<div class="form-group">
								<label class="control-label col-sm-2" for="email"><?= __('Subject'); ?>* :</label>
								<div class="col-sm-10">
									<?= $this->Form->text('subject', ['class' => 'form-control validate[required]', 'placeholder' => __('Enter subject'), 'id' => 'subject', 'maxlength' => '128', 'value' => $createTicketErrorDataSubject]); ?>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-2" for="description"><?= __('Description'); ?>* :</label>
								<div class="col-sm-10">
									<?= $this->Form->textarea('description', ['id' => 'description', 'class' => 'form-control ticket_editor validate[required,minSize[100]]', 'value' => $createTicketErrorDataDescription]);?>
					      		</div>
					    	</div>
					    	<div class="form-group">
					      		<label class="control-label col-sm-2" for="description">Upload:</label>
					      		<div class="col-sm-10">          
					        		<?php echo $this->Form->file('file', ['class' => 'form-control']) ?>
					      		</div>
					    	</div>
					    	<div class="form-group">
					    		<div class="col-sm-offset-2 col-sm-10">
					    			<?= $this->Form->submit(__('Submit'), ['class' => 'btn btn-default']);?>
					      		</div>
					    	</div>
					 	<?= $this->Form->end();?>
					</div>
		   		</div>
	   		<?php } ?>

	  	</div>
	</div>

	<?php if(!empty($opened) ) { ?>
	<div id="open-tickets">
	  	<div class="row">
	  		<div class="ticket-title">
	  			<h2><?= __('Open Tickets'); ?></h2>
	  			<?= $this->Html->link(ucwords(__('closed tickets')), ['controller'=>'support-tickets', /*'action' => __('index'), */__('closed')], ['class' => 'btn btn-red', 'style' => ['float: rigth;']]); ?>
	  			
	  			<!-- <a class="btn btn-red">Closed Tickets</a> -->
	  		</div>
	  		<div id="no-more-tables">
		  		<table class="col-md-12 table-bordered table-striped table-condensed cf">
	                <thead class="cf">
	                    <tr>
	                        <th class="numeric">#</th>
	                        <th class="numeric"><?= __('Subject') ;?></th>
	                        <th class="numeric"><?= __('Status'); ?></th>
	                        <th class="numeric"><?= __('Created On'); ?></th>
	                        <th class="numeric"><?= __('Last Commented On'); ?></th>
	                        <th class="numeric"><?= __('Action'); ?></th>
	                    </tr>
	                </thead>
	                <tbody>
	                <?php if(count($opened)) {
				    		$i = 1;
				    		foreach ($opened as $odata) { ?>
	                    		<tr>
	                        		<td data-title="#" class="numeric"><?= $i; ?></td>
			                        <td data-title="<?= __('Subject');?>" class="numeric">
			                            <?= ucfirst( $odata['subject'] );?>
			                        </td>
			                        <td data-title="<?= __('Status');?>" class="numeric">
			                            <?= __($approval_status[$odata['approved']]); ?>
			                        </td>
	                        		<td data-title="<?= __('Created On');?>" class="numeric">
	                            		<?php
	                            			if($this->request->session()->read('locale') == 'da') {
            									I18n::locale('en');
                								echo date('d/m/Y h:i A', strtotime($odata['created']));
                    							I18n::locale('da');
              								} else {
	                            				echo date('d/m/Y h:i A', strtotime($odata['created']));
	                            			}
	                            		?>
	                        		</td>
			                        <td data-title="<?= __('Last Commented On');?>" class="numeric">
			                            <?php
											$lastCommentedOn = $this->Comment->getLastComment($odata['id']);

											if($this->request->session()->read('locale') == 'da') {
            									I18n::locale('en');
                								if($lastCommentedOn == false) {
													echo date('d/m/Y h:i A', strtotime($odata['created']));
												} else {
													echo $lastCommentedOn;
												}
                    							I18n::locale('da');
              								} else {
	                            				if($lastCommentedOn == false) {
													echo date('d/m/Y h:i A', strtotime($odata['created']));
												} else {
													echo $lastCommentedOn;
												}
	                            			}

											/*if($lastCommentedOn == false) {
												echo date('d/m/Y h:i A', strtotime($odata['created']));
											} else {
												echo $lastCommentedOn;
											}*/
										?>
			                        </td>
	                        		<td data-title="<?= __('Action');?>" class="numeric">
	                            		<?php
	                            			//if( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' ) {
	                            				if($odata['approved'] == 2) {
													echo $this->Html->link(__('<i class="fa fa-pencil-square-o"></i>'), ['controller' => 'support-tickets', 'action' => 'edit', base64_encode($odata['id'])], ['title' => 'Ticket Detail', 'escape' => false] ).'&nbsp;&nbsp;&nbsp;';

	                            					echo $this->Html->link(__('<i class="fa fa-trash"></i>'), ['controller' => 'support-tickets', 'action' => 'delete', __('opened') , base64_encode($odata['id'])], ['title' => 'Ticket Detail', 'escape' => false] );
												}
	                            			//}
											if($odata['approved'] == 1) {
												echo $this->Html->link(__('<i class="fa fa-expand"></i>'), ['controller' => 'support-tickets', 'action' => 'view', __('opened') , base64_encode($odata['id'])], ['title' => 'Ticket Detail', 'target' => '_blank', 'escape' => false] );
											}
										?>
	                        		</td>
	                    		</tr>
	                <?php $i++; } } else { ?>
	                			<tr>
	                				<td data-title='' class="numeric" colspan="6" style="text-align: center; padding-left: 0px !important;">
	                                    <?= __(ucwords('No data found')); ?>
	                                </td>
	                           	</tr>
	                <?php } ?>
	                </tbody>
	            </table>
	            <?php if ($this->Paginator->params()['count'] > $this->Paginator->params()['perPage']) { ?>
                    <div class="support-ticket-box-footer clearfix">
			            <ul class="pagination pagination-sm no-margin pull-right">
			            	<?php 
			            	$options = array('url'=> array('controller' => 'support-tickets', __('opened') ));
			            	$this->Paginator->options($options);
			            	//$this->Paginator->options['url'] = ['controller'=>'support-tickets', __('opened')]; ?>
				          	<?= $this->Paginator->prev(' << '); ?>
				          	<?= $this->Paginator->numbers(); ?>
				          	<?= $this->Paginator->next(' >> '); ?>
				        </ul>
			        </div>
		        <?php } ?>
	        </div>
	  	</div>
	</div>
	<?php } ?>
	
	<?php if(!empty($closed)) {?>
	<div id="closed-tickets">
	  	<div class="row">
	  		<!-- <h2>Closed Tickets</h2> -->
	  		<div class="ticket-title">
	  			<h2><?= ucwords(__('closed tickets')) ?></h2>
	  			<?= $this->Html->link(__('Open Tickets'), ['controller'=>'support-tickets', __('opened')], ['class' => 'btn btn-red']); ?>
	  			<!-- <a class="btn btn-red">Closed Tickets</a> -->
	  		</div>
	  		<div id="no-more-tables">
		  		<table class="col-md-12 table-bordered table-striped table-condensed cf">
	                <thead class="cf">
	                    <tr>
	                        <th class="numeric">#</th>
	                        <th class="numeric"><?= __('Subject') ;?></th>
	                        <th class="numeric"><?= __('Status'); ?></th>
	                        <th class="numeric"><?= __('Created On'); ?></th>
	                        <th class="numeric"><?= __('Last Commented On'); ?></th>
	                        <th class="numeric"><?= __('Action'); ?></th>
	                    </tr>
	                </thead>
	                <tbody>
	                <?php if(count($closed)) {
				    		$j = 1;
				    		foreach ($closed as $cdata) { ?>
	                    		<tr>
	                        		<td data-title="#" class="numeric"><?= $j; ?></td>
			                        <td data-title="<?= __('Subject');?>" class="numeric">
			                            <?= ucfirst( $cdata['subject'] );?>
			                        </td>
			                        <td data-title="<?= __('Status');?>" class="numeric">
			                            <?= __($approval_status[$cdata['approved']]); ?>
			                        </td>
	                        		<td data-title="<?= __('Created On');?>" class="numeric">
	                            		<?php
	                            			if($this->request->session()->read('locale') == 'da') {
            									I18n::locale('en');
												echo date('d/m/Y h:i A', strtotime($cdata['created']));
                    							I18n::locale('da');
              								} else {
												echo date('d/m/Y h:i A', strtotime($cdata['created']));
	                            			}
	                            		?>
	                        		</td>


			                        <td data-title="<?= __('Last Commented On');?>" class="numeric">
			                            <?php
			                            	if($this->request->session()->read('locale') == 'da') {
            									I18n::locale('en');
												echo date('d/m/Y h:i A', strtotime($cdata['modified']));
                    							I18n::locale('da');
              								} else {
												echo date('d/m/Y h:i A', strtotime($cdata['modified']));
	                            			}
			                            	//date('d/m/Y h:i A', strtotime($cdata['modified']));
			                            ?>
			                        </td>
	                        		<td data-title="<?= __('Action');?>" class="numeric">
	                        			<?php
	                        				/*if( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' ) {
	                            				echo $this->Html->link(__('<i class="fa fa-pencil-square-o"></i>'), ['controller' => 'support-tickets', 'action' => 'view', __('opened') , base64_encode($cdata['id'])], ['title' => 'Edit Ticket', 'target' => '_blank', 'escape' => false] ).'&nbsp;&nbsp;&nbsp;';
	                            			}*/
	                        			?>
	                            		<?= $this->Html->link(__('<i class="fa fa-expand"></i>'), ['controller' => 'support-tickets', 'action' => 'view', __('closed') , base64_encode($cdata['id'])], ['title' => 'Ticket Detail', 'target' => '_blank', 'escape' => false] ); ?>
	                        		</td>
	                    		</tr>
	                <?php $j++; } } else {?>
	                			<tr>
	                				<td data-title='' class="numeric" colspan="6" style="text-align: center; padding-left: 0px !important;">
	                                    <?= __(ucwords('No data found')); ?>
	                                </td>
	                           	</tr>
	                <?php } ?>
	                </tbody>
	            </table>
	            <?php if ($this->Paginator->params()['count'] > $this->Paginator->params()['perPage']) { ?>
                    <div class="support-ticket-box-footer clearfix">
			            <ul class="pagination pagination-sm no-margin pull-right">
			            	<?php 
			            	$options = array('url'=> array('controller' => 'support-tickets', __('opened') ));
			            	$this->Paginator->options($options);
			            	//$this->Paginator->options['url'] = ['controller'=>'support-tickets', __('closed')]; ?>
			            	<?php //$this->Paginator->options['url'] = ['controller'=>'support-tickets', __('closed')]; ?>
			            	<?= $this->Paginator->prev(' << '); ?>
				          	<?= $this->Paginator->numbers(); ?>
				          	<?= $this->Paginator->next(' >> '); ?>
				        </ul>
			        </div>
		        <?php } ?>
	        </div>
	    </div>
    </div>

    <?php } /**/ ?>
</div>
<style type="text/css">
	.text-center { text-align: center; }
	.table-header-bg-clr{ background-color: #ffffff !important; }
	/*.active{background-color: #e4165b !important;}
	.active a{color: #FFF !important;}*/
	.hideNotificationDropdown{display: none !important;}
	.showNotificationDropdown{display: block !important;}
	.message{text-align: center;}
	.message.success{margin-top: 0px; margin-bottom: 15px;}
</style>
<!-- <script src="//cdn.tinymce.com/4/tinymce.min.js"></script> -->
<script src="//cdn.ckeditor.com/4.5.5/standard/ckeditor.js"></script>
<script type="text/javascript">
	$('document').ready(function(){
		if("<?= $this->request->session()->read('userNotification.count'); ?>" > 0) {
			var count = 0;
			$('.notificationList').on('click', function(){
				count += 1;
				if(!(count%2) && ((count%2) == 0)) {
					$(".dropdown").removeClass("showNotificationDropdown");
					$(".dropdown").addClass("hideNotificationDropdown");
				} else {
					$(".dropdown").removeClass("hideNotificationDropdown");
					$(".dropdown").addClass("showNotificationDropdown");
				}
			});
		}
	});

	/*tinymce.init({
        selector: 'textarea.ticket_editor'
   	});*/
   	CKEDITOR.replace('description');

</script>