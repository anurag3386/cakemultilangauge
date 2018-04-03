<?php echo $this->Html->css(['elite-innerpage-custom']); ?>
<article id="post-1159" class="post-1159 page type-page status-publish hentry">
	<div class="entry-content">
		<?php echo $this->element ('elite_expire'); ?>

		<div class="div-outer inn-container">
			<div class="div-clear">
				<div class="div-inner">
					<div class="common-content-left floatL">
						<div class="common-box box-shadow margin0">
							<div class="common-box-one">
								<div class="clear"></div>
								<div class="text-center"><?php echo $this->Flash->render();?></div>
								<h2 class="profileName">
									<?= $this->Html->image ('../images/icon-report.png', ['title'=>'Change Password', 'alt'=>'Change Password']); ?>
									<?= __('Change Password'); ?>
								</h2>
								<br>
								<div class="error" style="text-align: center; color: red;"></div>
								<br>
								<form name="frmChangePwd" id="frmChangePwd" method="post">
									
									<div class="sign-up-text"><?= __('Old Password ');?>* :</div>
									<input type="password" name="old_password" id="oldPassword" class="sign-up-input validate[required]" maxlength="20" placeholder="Old Password">
									<div class="clear"></div>
								
									<div class="sign-up-text"><?= __('New Password ');?>* :</div>
									<input type="password" name="password" id="txtPassword" placeholder="New Password" class="sign-up-input validate[required, minSize[6]]" maxlength="20">
									<div class="clear"></div>
									
									<div class="sign-up-text"><?= __('Confirm Password');?> * :</div>
										<input type="password" name="txtCPassword" placeholder="Repeate New Password" id="txtCPassword" class="sign-up-input validate[required,equals[txtPassword], minSize[6]]" maxlength="20">
									<div class="clear"></div>

									<div class="clear"></div>
									<input type="hidden" id="hdnIsEmailAvailable" name="hdnIsEmailAvailable" value="">
									<input type="hidden" id="hdnUserId" name="hdnUserId" value="44329">
									<input type="submit" name="btnChangePwd" id="btnChangePwd" value="<?= __('Change Password'); ?>" class="btn btn-red" style="cursor: pointer;">
								</form>
							</div>
						</div>
					</div>
					<?= $this->element ('elite_member_products'); ?>
				</div>
			</div>
		</div>
	</div>
</article>
	