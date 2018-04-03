<article id="post-1309" class="post-1309 page type-page status-publish hentry">
	<div class="entry-content">
		<div class="et_pb_section et_pb_fullwidth_section  et_pb_section_0 et_section_regular">
			<div class="et_pb_fullwidth_code et_pb_module  et_pb_fullwidth_code_0">
				<div class="et_pb_section  et_pb_section_0 et_section_regular">
					<div id="checkout_5" class=" et_pb_row et_pb_row_0">
						<div class="et_pb_column et_pb_column_4_4  et_pb_column_0">
							<div class="et_pb_text et_pb_module et_pb_bg_layout_light et_pb_text_align_center  et_pb_text_0">
								<p>
									<?php 
									echo $this->Html->image('ico_complete.png', ['alt' => 'ico_complete', 'width'=> '94', 'height' => '94', 'class' => 'aligncenter size-full wp-image-348'])
									?>
									<br><br>
									<h2 class="intro"><?= __('Please wait your download start in');?> <span id="counter">5</span> <?= __('seconds');?>.</h2>
									<br><br> 
									<p><?= __('If download is not started automatically');?>, <?php echo $this->Html->link(__('click here'), $link);?> <?= __('to manually download it.');?></p>
								</div> <!-- .et_pb_text -->
							</div> <!-- .et_pb_column -->
						</div> <!-- .et_pb_row -->
					</div>
				</div> <!-- .et_pb_fullwidth_code -->
			</div> <!-- .et_pb_section -->
		</div> <!-- .entry-content -->
  </article> <!-- .et_pb_post -->
<script>
$(document).ready(function(){
  downloadSoftware('<?php echo $link?>');	
  })
</script>