<?php echo $this->Html->css(['elite-innerpage-custom']); ?>
<?= $this->Html->script(['common_front']); ?>
<?php use Cake\Routing\Router; ?>
<?php $coverPageImage = !empty ($this->request->data['cover_page']) ? $this->request->data['cover_page'] : ''; ?>
<article id="post-1159" class="post-1159 page type-page status-publish hentry">
	<div class="entry-content">
		<?php echo $this->element ('elite_expire'); ?>
		<div class="div-outer inn-container">
		<?= $this->Flash->render() ?>
			<div class="div-clear">
				<div class="div-inner">
					<div class="common-content-left floatL">
						<div class="common-box box-shadow margin0">
							<div class="common-box-one">
								<div class="clear"></div>
								<h2 class="profileName">
									<?= $this->Html->image ('../images/icon-report.png', ['title'=>'Change Password', 'alt'=>'Change Password']); ?>
									<?= __('Customize Report');?>
								</h2>
								<div class="error" style="text-align: center; color: red;"></div>
								<?= $this->Form->create (/*'EliteMembers'*/$entity, ['id' => 'customizeReport', 'enctype' => 'multipart/form-data']); ?>

								<?php 
									if (!empty($this->request->data['cover_page'])) {
										echo $this->Form->hidden ('existing_coverPage', ['value' => $this->request->data['cover_page']]);
									}
								?>
								<div class="sign-up-text"><b><?= __('Portal/Company Name');?>*</b> : </div>
									<?= $this->Form->input ('company_name', ['class' => 'sign-up-input validate[required]', 'div' => false, 'label' => false, 'maxlength'=>100, 'placeholder'=>__('Portal/Company Name')]); ?>
								<div class="clear"></div>
								
								<div class="sign-up-text"><b><?= __('URL');?></b> : </div>
									<?= $this->Form->input ('url', ['div' => false, 'class' => 'sign-up-input', 'label' => false, 'maxlength'=>50, 'placeholder'=> __('Website Url')]); ?>
								<div class="clear"></div>
								
								<div class="sign-up-text"><b><?= __('Footer');?></b> : </div>
									<?= $this->Form->input ('footer', ['type' => 'textarea', 'class' => 'sign-up-input', 'div' => false, 'label' => false, 'maxlength'=>500, 'placeholder'=>__('Footer text on reports')]); ?>
								<div class="clear"></div>
								
								<div class="sign-up-text"><b><?= __('Cover Page PDF');?></b> : </div>
									<?= $this->Form->input ('cover_page', ['type' => 'file', 'class' => 'sign-up-input', 'div' => false, 'label' => false, 'style' => 'width: 185px']); ?>
								<?php if (!empty($coverPageImage)) { ?>
									<?php if (file_exists(WWW_ROOT.'uploads/elite-user-coverPage/'.$coverPageImage)) { ?>
										<div>
											<a href="<?= Router::url('/', true);?>uploads/elite-user-coverPage/<?= $coverPageImage; ?>" target="_blank"><?= __('View'); ?></a>
										</div>
								<?php } } ?>
								<div class="clear"></div>

								<div class="clear"></div>
								<input type="submit" name="btnChangePwd" id="btnChangePwd" value="<?= __('Submit'); ?>" class="btn btn-red" style="cursor: pointer;">
								<?= $this->Form->end (); ?>
							</div>
						</div>
					</div>
					<?= $this->element ('elite_member_products'); ?>
				</div>
			</div>
		</div>
	</div>
</article>

<script type="text/javascript">
    $(document).ready(function() {
		$('#customizeReport').submit(function () {
			/*if ($('#company-name').val() == '') {
				$('.error').text ('Please enter your company name');
				return false;
			}*/
			var url = $('#url').val();
			if (url != '') {
				if (!is_valid_url (url)) {
					$('.error').text ('Please enter a valid URL');
					return false;
				}
			}
			var coverpage = $('#cover-page').val();
			if (coverpage != '') {
				var ext = coverpage.split('.').pop();
				if (ext != 'pdf') {

					<?php if( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' ) { ?>
							$('.error').text ("<?php echo __('File extension must be PDF'); ?>");
						<?php } else { ?>
							$('.error').text ('File extension must be PDF');
						<?php } ?>
					return false;
				}
			}
		});
	});

	function is_valid_url (url) {
    	return /^(http(s)?:\/\/)?(www\.)?[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/.test(url);
	}
</script>
<style type="text/css">
	.message { text-align: center; }
</style>