<article id="post-1184" class="post-1184 page type-page status-publish hentry">
	<div class="entry-content">
	<div class="parent">
		<div class="et_pb_section et_pb_fullwidth_section  et_pb_section_0 et_section_regular">
			<div class="et_pb_module  et_pb_fullwidth_image et_pb_animation_fade_in  et_pb_fullwidth_image_0">
				<?php echo $this->Html->image('/layout/adrian_banner.jpg', ['alt' => 'Adrian']);?>
			</div>
		</div> <!-- .et_pb_section -->
    </div>
    <div class="child">
		<div class="et_pb_section  et_pb_section_1 et_section_regular">
			<div class=" et_pb_row et_pb_row_0">
				<h1><?php echo __('AstroWOW Media'); ?></h1>
				<div class="et_pb_column et_pb_column_1_2  et_pb_column_0">
					<div class="et_pb_text et_pb_module et_pb_bg_layout_light et_pb_text_align_center  et_pb_text_0">
						<h3><?= __('Audio');?></h3>
					</div> <!-- .et_pb_text -->

					<?php 
					if(!empty($audios))
					{
						foreach($audios as $audio)
						{

							?>
							<div class="et_pb_audio_module clearfix et_pb_module et_pb_bg_layout_dark et_pb_audio_no_image  et_pb_audio_0" style="background-color: #E4165B;">
								<div class="et_pb_audio_module_content et_audio_container">
									<h2><?php echo $audio['name'];?></h2>
									<br><br>
									<audio id="player2" src="<?php echo $this->request->webroot?>uploads/media/<?php echo $audio['path']?>" type="audio/mp3" controls="controls">		
									</audio>	



								</div>
							</div>

							<?php }
						}
						?>

					</div> <!-- .et_pb_column -->
					<div class="et_pb_column et_pb_column_1_2  et_pb_column_1 media-section">
						<div class="et_pb_text et_pb_module et_pb_bg_layout_light et_pb_text_align_center  et_pb_text_1">
							<h3><?= __('Video');?></h3>
						</div> <!-- .et_pb_text -->

						<div class="et_pb_text et_pb_module et_pb_bg_layout_light et_pb_text_align_left  et_pb_text_2">


							<?php if(!empty($videos))
							{
								foreach($videos as $video)
								{

									?>


									<p><strong><?php echo $video['name'];?></strong><br />
										<!-- <em>Yearly</em><br /> -->
										<iframe width="640" height="480" src="https://www.youtube.com/embed/<?php echo $video['path']?>" frameborder="0" allowfullscreen></iframe>

									</p>
									<?php 
								}
							}
							?>




						</div> <!-- .et_pb_text -->
					</div> <!-- .et_pb_column -->


				</div> <!-- .et_pb_row -->
			</div> <!-- .et_pb_section -->
		</div>
		</div> <!-- .entry-content -->
	</article> <!-- .et_pb_post -->
	<script>
		$('audio,video').mediaelementplayer();
	</script>