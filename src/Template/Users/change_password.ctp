<?php echo $this->Html->css(['innerpage-custom']); ?>
<div class="et_pb_row et_pb_row_0">
	<div class="common-box box-shadow editprofileBox">
		<div class="common-box-one">											
			<div class="clear"></div>
			<h2 class="profileName">
				<?= $this->Html->image ('../images/icon-report.png', ['title'=>'Change Password', 'alt'=>'Change Password']); ?>
				<?= __($header); ?>
			</h2>
			<br>
			<div class="text-center"><?php echo $this->Flash->render();?></div>
			<!-- <div class="error" style="margin-left: 18%;"></div> -->
			<br>
			<form name="frmChangePwd" id="frmChangePwd" method="post">

				<div class="sign-up-text"><?= __('Old Password');?>* :</div>
				<input type="password" name="old_password" id="oldPassword" class="sign-up-input validate[required, minSize[6]]" maxlength="20" placeholder="<?php echo __('Old Password') ?>">
				<div class="clear"></div>

				<div class="sign-up-text"><?= __('New Password');?> * :</div>
				<input type="password" name="password" id="txtPassword" placeholder="<?php echo __('New Password') ?>" maxlength="20" class="sign-up-input validate[required, minSize[6]]">
				<div class="clear"></div>
				
				<div class="sign-up-text"><?= __('Confirm Password');?> * :</div>
				<input type="password" name="txtCPassword" id="txtCPassword" maxlength="20" placeholder="<?php echo __('Confirm Password');?>" class="sign-up-input validate[required,equals[txtPassword], minSize[6]]">
				<div class="clear"></div>

				<div class="clear"></div>
				<input type="hidden" id="hdnIsEmailAvailable" name="hdnIsEmailAvailable" value="">
				<input type="hidden" id="hdnUserId" name="hdnUserId" value="44329">
					<input type="submit" name="btnChangePwd" id="btnChangePwd" value="<?= __('Change Password');?>" class="sign-up-text sign-up-button input-purple" style="cursor: pointer;">
			</form>							
				
		</div>
	</div>
</div>
