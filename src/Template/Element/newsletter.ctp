<?php use Cake\I18n\I18n;?>
<?php 
$locale = strtolower( substr(I18n::locale(), 0, 2) );
$locale = ($locale == 'da') ? 'dk' : 'en';
?>
<div class="newsletter_common">
<div class="et_pb_section  et_pb_section_7 et_section_regular">
	<div class=" et_pb_row et_pb_row_12">
		<div class="et_pb_column et_pb_column_4_4  et_pb_column_18">
			<div class="et_pb_text et_pb_module et_pb_bg_layout_light et_pb_text_align_center  et_pb_text_9">

				<p>
					<?php echo $this->Html->image('astrowow_logo_white.png', ['alt'=>'astrowow_logo_white','class'=>'aligncenter size-full wp-image-111', 'width'=>'309', 'height'=>'77'])?>
					<!--img src="files/images/astrowow_logo_white.png" alt="astrowow_logo_white" class="aligncenter size-full wp-image-111"  width="309" height="77"-->
				</p>
				
				<p style="font-size: 24px; margin:30px;"><?= __('Enter your email address to sign up for free astrology reports and more');?>...</p>
				<div id="home-sign-up-email">

					<?php 
					//echo $this->Form->create('Sign Up', ['url'=>$locale.'/users/sign-up' , 'id' => 'form2']); 
					echo $this->Form->create('Sign Up', ['url'=>['controller' => 'Users', 'action' =>'sign-up'] , 'id' => 'form2']); 
					?>

					<?php echo $this->Form->text('uname', ['placeholder'=>'youremail@example.com', 'id'=>'uname'])?>

					<?php echo $this->Form->button(__('SIGN UP'), ['class'=>'btn btn-red btn-large'])?>

					<?php echo $this->Form->end();?>
			</div>

		</div> <!-- .et_pb_text -->
	</div> <!-- .et_pb_column -->

</div> <!-- .et_pb_row -->

</div> <!-- .et_pb_section -->
</div>