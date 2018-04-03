<article id="post-1328" class="post-1328 page type-page status-publish hentry">
		
					<div class="entry-content">
					<div class="et_pb_section  et_pb_section_0 et_section_regular">
				
				 <div style="text-align:center; color: red"><?= $this->Flash->render() ?></div>
					
					<div class=" et_pb_row et_pb_row_0">
				<div class="et_pb_column et_pb_column_4_4  et_pb_column_0">


				
				<div class="et_pb_newsletter et_pb_login clearfix et_pb_module et_pb_bg_layout_dark et_pb_text_align_left  et_pb_login_0" style="background-color: #c43a5c;">
				<div class="et_pb_newsletter_description">
					<h2><?= __('Forgot Password');?></h2>
					

				</div>
				
				<div class="et_pb_newsletter_form et_pb_login_form">

				
				<?php echo $this->Form->create('forgot-password', ['id' => 'step-1'])?>
					
						<p>
							<label class="et_pb_contact_form_label" for="user_login" style="display: none;"><?= __('Username');?></label>
							
					<?php 
  					     echo $this->Form->text('username', [ 'class' =>'input', 'id' => 'username' , 'placeholder' => __('Enter Username'), 'class' => 'validate[required]', 'maxlength' => 255]);
					?>
							
						</p>
					
					
						<p>
							<button type="submit" class="et_pb_newsletter_button et_pb_button"><?= __('Submit');?></button>
							
						</p>
					<?php echo $this->Form->end();?>
				</div>
			</div>
			</div> <!-- .et_pb_column -->
					
			</div> <!-- .et_pb_row -->
				
			</div> <!-- .et_pb_section -->
					</div> <!-- .entry-content -->

				
				</article>