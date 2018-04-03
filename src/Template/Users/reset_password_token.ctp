<article id="post-1328" class="post-1328 page type-page status-publish hentry">
	
	<div class="entry-content">
		<div class="et_pb_section  et_pb_section_0 et_section_regular">
			<?php echo $this->Flash->render();?>


			<div class=" et_pb_row et_pb_row_0">
				
				<div class="et_pb_column et_pb_column_4_4  et_pb_column_0">

					<div class="et_pb_newsletter et_pb_login clearfix et_pb_module et_pb_bg_layout_dark et_pb_text_align_left  et_pb_login_0" style="background-color: #c43a5c;">
						<div class="et_pb_newsletter_description">
							<h2><?= __('Change Your Password');?></h2>


						</div>

						<div class="et_pb_newsletter_form et_pb_login_form">

   						    <?php 
								echo $this->Form->create($form, ['id' => 'reset_password_token']);
							?>
							<p>
								<?php echo $this->Form->hidden('reset_password_token', [ 'value' => $this->request->params['pass'][0]]); ?>

								<label><?= __('New Password');?> </label>

								<?php echo $this->Form->text('password',  array('type' => 'password', 'label' => 'Password','type' => 'password', 'value' => '' , 'class' => ' validate[required]' , 'id' => 'password' )  ); ?>
							</p>
							<p>


								<label><?= __('Confirm Password');?> </label>
								<?php echo $this->Form->text('confirm_passwd',  array('type' => 'password', 'label' => 'Confirm Password', 'between' => '<br />', 'type' => 'password', 'class' => ' validate[required ,equals[password] ]') ); ?>

							</p>
							<p>
								<?php echo $this->Form->submit(__('Change Password'), array('class' => 'et_pb_newsletter_button et_pb_button', 'id' => 'submit')); ?>
							</p>
							<?php echo $this->Form->end(); ?>

						</div>
					</div>
				</div> <!-- .et_pb_column -->

			</div> <!-- .et_pb_row -->

		</div> <!-- .et_pb_section -->
	</div> <!-- .entry-content -->


</article>
