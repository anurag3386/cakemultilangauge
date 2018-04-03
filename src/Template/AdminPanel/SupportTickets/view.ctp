
<?= $this->Html->css(['lightbox.min']); ?>
<?= $this->Html->script(['lightbox-plus-jquery.min']); ?>

<?php //pr($data); die;
	$checkRedirectionArr = ['opened' => 1, 'closed' => 2];
	$checkRedirectionLinkArr = ['opened' => 'open-tickets', 'closed' => 'closed-tickets'];
	//pr($this->request->session()->read('commentData')); //die;
	$commentData = !empty($this->request->session()->read('commentData')) ? stripslashes($this->request->session()->read('commentData')) : '';
?>

<?php $protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://'; ?>
<div class="row">
	<div class="col-md-12">
		<div class="box">
			<!-- <div class="box-header with-border">
				<h3 class="box-title">View Comments</h3>
      		</div> -->
      		<div class="box-body">
      		<div class="support-ticket-view">
    			<!-- <div class="container"> -->
      				<div class="row">
      					<?php if(!empty($data) ) { ?>
      						<div class="box-header with-border">
								<h3 class="box-title"><?= 'Ticket - '.ucfirst($data['subject']); ?></h3>
      						</div>
							<!-- <h3><?php //echo ucfirst($data['subject']); ?></h3> -->
						<?php } ?>
      					<div id="support-ticket-success"></div>
      					<div id="support-ticket-error"></div>
      					<div class="support-ticket-comment-form">
	      					<?= $this->Form->create($form);?>
	      						<div class="col-md-4">
	      							<label>Ticket Status</label>
			  						<?php
			  							$options  = ['1' => 'Open', '2' => 'Closed'];
			  							echo $this->Form->select('status', $options, ['empty' => 'Change Status', 'default' => $data['status'], 'onchange' => 'supportTicketStatus();', 'class' => 'ticket-status-option form-control']);
			  						?>
	         					</div>

	      						<div class="col-md-4">
	      							<label>Ticket Handled By</label>
			  						<?php
			  							//$handled_by = (isset($data['handled_by']) ) ? $data['handled_by'] : '';
	         							$options  = ['1' => 'Nethues', '2' => 'Adrian', '3' => 'Other'];
	         							echo $this->Form->select('handled_by', $options, ['empty' => 'Handled by', 'default' => $data['handled_by'], 'onchange' => 'supportTicketHandleBy();', 'class' => 'ticket-handleby-option form-control']);
			  						?>
	         					</div>

	      						<div class="col-md-4">
      								<label>Ticket Approval</label>
      								<?php
      									$approval_status1 = ['Reject', 'Approve', 'Pending'];
	         							echo $this->Form->select('approval_status', $approval_status1, ['empty' => 'Ticket Approval Status', 'default' => $data['approved'], 'onchange' => 'approveTicket();', 'class' => 'ticket-approval-option form-control']);
		  							?>
	         					</div>
	         				<?= $this->Form->end();?>
	         			</div>
	         		</div>
    				<?php if(strtolower($status) == 'opened' ) {
    					/*$commentForm = ' hideForm';
    					if($data['approved'] == 1) {
    						$commentForm = ' showForm';
    					}*/
    				?>
    					<div class="row<?php //echo $commentForm; ?>" id="commentForm">
    						<div class="col-md-12">
								<div class="widget-area no-padding blank">
    								<h3>You may also add your comment(s) here: </h3>
									<div class="status-upload">
										<?= $this->Form->create($form, ['class' => 'form-horizontal', 'id' => 'step-1', 'enctype' => 'multipart/form-data', 'novalidate']) ?>
											<?php echo $this->Form->textarea('description', ['id' => 'description', 'class' => 'form-control ticket_editor validate[required]', 'value' => $commentData]);?>
											<?php /* ?>
											<div class="row">
							                  	<div class="col-md-12">
							                    	<div class="form-group">
							                      		<label>Description *</label>
														<?php echo $this->Form->textarea('description', ['class' => 'form-control html_editor validate[required]', 'maxlength' => 500]); ?>
													</div>
												</div>
											</div>
						   					<?php */ ?>
						   					<ul>
												<li>
													<label style="font-size: 18px;">Upload Image:</label>
													<?php echo $this->Form->file('file') ?>
													<?= $this->Form->hidden('parent_id', ['value' => $id]);?>
													<?= $this->Form->hidden('subject', ['value' => $data['subject']]);?>
													<?= $this->Form->hidden('handled_by', ['value' => $data['handled_by']]);?>
													<?= $this->Form->hidden('comment', ['value' => '1']);?>
												</li>
											</ul>
											<button type="submit" class="btn btn-primary comment-box-submit"><i class="fa fa-share"></i> Submit</button>
					 					<?= $this->Form->end();?>
									</div><!-- Status Upload  -->
								</div><!-- Widget Area -->
							</div>
    					</div>
    				<?php } ?>
					<div class="row">
					<!-- Contenedor Principal -->
    					<div class="comments-container">
    						<?php if(!empty($data) ) { ?>
									<h4>Comment History</h4>
								<ul id="comments-list" class="comments-list">
			  						<?php $comments = $this->Comment->getComments($id); ?>
									<li>
										<div class="comment-main-level">
											<!-- Avatar -->
											<div class="comment-avatar"><?= $this->Html->image('noavatar.png', ['alt' => '']); ?></div>
											<!-- Contenedor del Comentario -->
											<div class="comment-box">
												<div class="comment-head">
													<h6 class="comment-name"><?= ucfirst($data['profiles']['first_name']." ".$data['profiles']['last_name']); ?></h6>
													<span style="float: right;"><?= date('d/m/Y h:i A', strtotime( $data['created'] ) ) ?></span>
													<?php /* ?>
													<span id="status_<?php echo $id; ?>">
														<a href="javascript:changeApproveStatus(<?php echo $id; ?>,<?php echo $data['approved']; ?>);"><?php $data['approved']?>	<i <?= ($data['approved'] == 1)? "style='color: green !important'" : ''; ?> class="fa fa-heart"></i></a>
				                					</span>
				                					<?php */ ?>
												</div>
												<div class="comment-content col-md-9">
													<?= stripslashes($data['description']); ?>
												</div>
												<?php if(!empty($data['comment_file']['file'])) { ?>
	                								<div class="col-md-3 comment-content">
	                									<a class="example-image-link" href="/uploads/tickets/<?= $data['comment_file']['file']; ?>" data-lightbox="example-1">
	                										<img class="example-image imageHeightWidth" src="/uploads/tickets/<?= $data['comment_file']['file']; ?>" alt="image-1" title="Click to expand image"/>
	                									</a>
	                									<?php //$this->Html->link($this->Html->image('/uploads/tickets/'.$data['comment_file']['file'], ['class' => 'example-image', 'alt' => '','height' => '100', 'width' => '100']), ['class' => 'example-image-link', ]); ?>

	                        							<?php //echo $this->Html->image('/uploads/tickets/'.$data['comment_file']['file'], ['alt' => '','height' => '100', 'width' => '100']); ?>
	                        						</div>
	                        					<?php } ?>
											</div>
										</div>
										<?php foreach ($comments as $cdata) { ?>
	              							<ul class="comments-list reply-list">
												<!-- Respuestas de los comentarios -->
												<li>
													<!-- Avatar -->
													<div class="comment-avatar"><?= $this->Html->image('noavatar.png', ['alt' => '']); ?></div>
													<!-- Contenedor del Comentario -->
													<div class="comment-box">
														<div class="comment-head">
															<?php
																if($cdata['commented_by'] == 2) {
																	$uname = ucfirst($data['profiles']['first_name']." ".$data['profiles']['last_name']); 
																} else {
																	$uname = 'admin';
																}
															?>
															<h6 class="comment-name"><?= ucfirst($uname);?></h6>
															<span style="float: right;"><?= date('d/m/Y h:i A', strtotime( $cdata['created'] ) ) ?></span>
															<!-- <i class="fa fa-reply"></i> -->
															<?php /*
															<span id="status_<?php echo $cdata['id']; ?>">
				                  								<a href="javascript:changeApproveStatus(<?php echo $cdata['id']; ?>,<?php echo $cdata['approved']; ?>);" title="test"><?php $cdata['approved']?>	<i <?= ($cdata['approved'] == 1)? "style='color: green !important'" : ''; ?> class="fa fa-heart"></i></a>
				                							</span>
				                							*/ ?>
				                						</div>
				                						<div class="comment-content col-md-9">
				                							<?= stripslashes($cdata['description']); ?>
				                						</div>
				                						<?php if(!empty($cdata['comment_file']['file'])) { ?>
			                								<div class="col-md-3 comment-content">
			                									<a class="example-image-link" href="/uploads/tickets/<?= $cdata['comment_file']['file']; ?>" data-lightbox="example-1">
	                												<img class="example-image imageHeightWidth" src="/uploads/tickets/<?= $cdata['comment_file']['file']; ?>" alt="image-1" title="Click to expand image"/>
	                											</a>
			                									<?php //echo $this->Html->image('/uploads/tickets/'.$cdata['comment_file']['file'], ['alt' => '', 'height' => '100', 'width' => '100']);
			                									?>
			                								</div>
			                							<?php } ?>
													</div>
												</li>
											</ul>
	                  					<?php } ?>
	                  				</li>
	                  			</ul>
                  			<?php } else { echo "<p>No data found</p>"; } ?>
                  		</div>
                  	</div>
				</div>
				<!-- </div> -->
			</div>
    	<!-- /.box -->
    	</div>
	</div>
</div>
<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
<script>

$(document).ready(function() {
	$(".ticket-approval-option option:contains('Pending')").attr("disabled","disabled");
});

function supportTicketStatus () {
	var ticketStatus = $('.ticket-status-option').val();
	if (ticketStatus != '') {
		var ticketId = "<?= $id; ?>";
		var updateTicketStatusURL = "<?php echo $this->Url->build([ 'controller' => 'support-tickets', 'action' => 'updateTicketStatus']);?>/status/"+ticketId+"/"+ticketStatus;
	    var successMessage = 'Support ticket status has been changed successfully.';
	    var errorMessage = 'Unable to update tickets status.';
	    if(ticketStatus != "<?= $checkRedirectionArr[$this->request->params['pass'][0]]; ?>") {
	    	fireAjaxRequest(updateTicketStatusURL, successMessage, errorMessage, 'returnvalue');
		} else {
			fireAjaxRequest(updateTicketStatusURL, successMessage, errorMessage);
		}
	} else {
		$('#support-ticket-success').css('display', 'none');
		$('#support-ticket-error').css('display', 'block');
        $('#support-ticket-error').html('Please select proper option.');
        return false;
	}
}



function approveTicket () {
    var ticket_status = $('.ticket-approval-option').val();
    if (ticket_status != '') {
		var ticketId = "<?= $id; ?>";
		var updateTicketStatusURL = "<?php echo $this->Url->build([ 'controller' => 'support-tickets', 'action' => 'updateTicketStatus']);?>/approved/"+ticketId+"/"+ticket_status;
		if (ticket_status == 1) {
			var successMessage = 'Support ticket has been approved successfully.';
		} else {
			var successMessage = 'Support ticket has been rejected successfully.';
		}
	    var errorMessage = 'Unable to update tickets status.';
		fireAjaxRequest(updateTicketStatusURL, successMessage, errorMessage);
		/*if(ticket_status == 1) {
			$('#commentForm').removeClass('showForm');
			$('#commentForm').removeClass('hideForm');
			$('#commentForm').addClass('showForm');
		} else {
			$('#commentForm').removeClass('showForm');
			$('#commentForm').removeClass('hideForm');
			$('#commentForm').addClass('hideForm');
		}*/
	} else {
		$('#support-ticket-success').css('display', 'none');
		$('#support-ticket-error').css('display', 'block');
        $('#support-ticket-error').html('Please select proper option.');
        return false;
	}
}


function supportTicketHandleBy () {
	var ticket_status = $('.ticket-approval-option').val();
	if (ticket_status == 0 || ticket_status == 1 ) {
		var handled_by = $('.ticket-handleby-option').val();
		if (handled_by != '') {
			var ticketId = "<?= $id; ?>";
			var updateTicketStatusURL = "<?php echo $this->Url->build([ 'controller' => 'support-tickets', 'action' => 'updateTicketStatus']);?>/handled_by/"+ticketId+"/"+handled_by;
			var successMessage = 'Support ticket has been assigned successfully.';
		    var errorMessage = 'Unable to update tickets status.';
			fireAjaxRequest(updateTicketStatusURL, successMessage, errorMessage);
		} else {
			$('#support-ticket-success').css('display', 'none');
			$('#support-ticket-error').css('display', 'block');
	        $('#support-ticket-error').html('Please select proper option.');
	        return false;
		}
	} else {
		$('#support-ticket-success').css('display', 'none');
		$('#support-ticket-error').css('display', 'block');
        $('#support-ticket-error').html('Please select Ticket Approval status first.');
        return false;
	}
}


function fireAjaxRequest (updateTicketStatusURL, successMessage, errorMessage, returnvalue='') {
	$.ajax({
        type:"POST",
        url: updateTicketStatusURL,
        success: function(data) {
        	if(data == 'yes') {
        		$('#support-ticket-error').css('display', 'none');
        		$('#support-ticket-success').css('display', 'block');
        		$('#support-ticket-success').html(successMessage);
        		if (returnvalue != '') {
        			var returnURL = "<?php echo $this->Url->build([ 'controller' => 'support-tickets', 'action' => $checkRedirectionLinkArr[$this->request->params['pass'][0]]]);?>";
	        		setTimeout(function(){
	        			window.location.replace(returnURL);
	        		}, 1000);
	        	}
        	} else {
        		$('#support-ticket-success').css('display', 'none');
        		$('#support-ticket-error').css('display', 'block');
        		$('#support-ticket-error').html(errorMessage);
        	}
        }
    });
}
tinymce.init({
    selector: 'textarea.ticket_editor'
});
</script>

<style type="text/css">
	.hideForm{display: none;}
	.showForm{display: block;}
	#support-ticket-success{color: green; display: none; margin-bottom: 10px; padding-left: 15px; margin-top: 10px;}
	#support-ticket-error{color: red; display: none; margin-bottom: 10px; padding-left: 15px; margin-top: 10px;}
</style>