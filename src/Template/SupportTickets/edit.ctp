<?php use Cake\Routing\Router; ?>
<?php use Cake\I18n\I18n; ?>
<?php //if( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' ) { ?>
	<?= $this->Html->css(['magnific-popup']); ?>
	<?= $this->Html->script(['jquery.magnific-popup']); ?>
<?php /*} else { ?>
	<?php $this->Html->css(['lightbox.min']); ?>
	<?php $this->Html->script(['lightbox-plus-jquery.min']); ?>
<?php }*/ ?>
<?php
	$editTicketErrorDataSubject = !empty($this->request->session()->read('edit-support-ticket-data.subject')) ? $this->request->session()->read('edit-support-ticket-data.subject') : $this->request->data['subject'];
	$editTicketErrorDataDescription = !empty($this->request->session()->read('edit-support-ticket-data.description')) ? $this->request->session()->read('edit-support-ticket-data.description') : $this->request->data['description'];
	$editTicketErrorDataId = !empty($this->request->session()->read('edit-support-ticket-data.id')) ? $this->request->session()->read('edit-support-ticket-data.id') : $this->request->data['id'];
	$image = $this->Custom->getSupportTicketImage($editTicketErrorDataId);

?>
<div class="container">
	<div id="create-testimonial-form">
		<div class="row">
			<h1><?= __('AstroWow Support'); ?></h1>
			<?= $this->Flash->render();?>
			<div class="panel panel-primary">
				<div class="panel-heading"><?= __('Edit Ticket'); ?></div>
				<?= $this->Html->link(__('Open Tickets'), ['controller'=>'support-tickets', __('opened')], ['class' => 'btn btn-red elist']); ?>
				<div class="panel-body">
					<?= $this->Form->create($form, [/*'class' => 'form-horizontal', */'id' => 'support-tickets', 'enctype' => 'multipart/form-data', 'novalidate' => true]) ?>
						<?= $this->Form->hidden('id', ['value' => $editTicketErrorDataId]); ?>
						<div class="form-group">
							<label class="control-label col-sm-2" for="email"><?= __('Subject'); ?>* :</label>
							<div class="col-sm-10">
								<?= $this->Form->text('subject', ['class' => 'form-control validate[required]', 'placeholder' => __('Enter subject'), 'id' => 'subject', 'maxlength' => '128', 'value' => $editTicketErrorDataSubject]); ?>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-2" for="description"><?= __('Description'); ?>* :</label>
							<div class="col-sm-10">
								<?= $this->Form->textarea('description', ['id' => 'description', 'class' => 'form-control ticket_editor validate[required,minSize[100]]', 'value' => $editTicketErrorDataDescription]);?>
				      		</div>
				    	</div>
				    	<div class="form-group">
				      		<label class="control-label col-sm-2" for="description">Upload:</label>
				      		<div class="col-sm-10">          
				        		<?php echo $this->Form->file('file', ['class' => 'form-control']) ?>
				        		<?php //if( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' ) { ?>
				        			<?php if(!empty($image) && file_exists($_SERVER['DOCUMENT_ROOT'].'/webroot/uploads/tickets/'.$image)) { ?>
				        				<a class="test-popup-link" href="/uploads/tickets/<?= $image; ?>">
				        					<span><?= $this->Html->image('../uploads/tickets/'.$image, ['class' => 'zoom-image hw100']); ?></span>
				        				</a>
				        			<?php } ?>
				        		<?php /*} else { ?>
					        		<a class="example-image-link" href="/uploads/tickets/<?= $image; ?>" data-lightbox="example-1">
					        			<?php if(file_exists($_SERVER['DOCUMENT_ROOT'].'/webroot/uploads/tickets/'.$image)) { ?>
					        				<span><?= $this->Html->image('../uploads/tickets/'.$image, ['height' => '100px', 'width' => '100px']); ?></span>
					        			<?php } ?>
					        		</a>
					        	<?php }*/ ?>
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
	  	</div>
	</div>
</div>
<style type="text/css">
	/*.text-center { text-align: center; }
	.table-header-bg-clr{ background-color: #ffffff !important; }
	.active{background-color: #e4165b !important;}
	.active a{color: #FFF !important;}
	.hideNotificationDropdown{display: none !important;}
	.showNotificationDropdown{display: block !important;}*/
	.hw100{height: 100px; width: 100px;}
</style>
<!-- <script src="//cdn.tinymce.com/4/tinymce.min.js"></script> -->
<script src="//cdn.ckeditor.com/4.5.5/standard/ckeditor.js"></script>
<script type="text/javascript">
	/*tinymce.init({
        selector: 'textarea.ticket_editor'
   	});*/
   	CKEDITOR.replace('description');
   	<?php //if( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' ) { ?>
   		$(document).ready(function() {
  			$('.image-link').magnificPopup({type:'image'});
		});
		$('.test-popup-link').magnificPopup({
		  	type: 'image'
		});
		$('.test-popup-link').on('click', function(){
			$('#main-header').css('z-index', '999');
		});
   	<?php //} ?>
</script>