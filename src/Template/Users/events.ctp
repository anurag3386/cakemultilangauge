		
<article id="post-1182" class="post-1182 page type-page status-publish hentry">
	<div class="entry-content">
	 <div class="parent">
		<div class="et_pb_section et_pb_fullwidth_section  et_pb_section_0 et_section_regular">
			<div class="et_pb_module et-waypoint et_pb_fullwidth_image et_pb_animation_fade_in  et_pb_fullwidth_image_0 et-animated">
				<?php echo $this->Html->image('adrian_banner.jpg',['alt'=>'Adrian']);?>
			</div>
         </div>
		</div> <!-- .et_pb_section -->

		<div class="et_pb_section  et_pb_section_1 et_section_regular" id="bottom-space">

			<div class=" et_pb_row et_pb_row_0">

				<div class="et_pb_column et_pb_column_4_4  et_pb_column_0">

					<div class="et_pb_text et_pb_module et_pb_bg_layout_light et_pb_text_align_center  et_pb_text_0">

						<h1><?= __('Courses & Events');?></h1>
						<h3><?= __('Adrian is holding talks at the following congresses');?>:</h3>

					</div> <!-- .et_pb_text -->
				</div> <!-- .et_pb_column -->

			</div> <!-- .et_pb_row -->


			<?php 
			if(!empty($events)):

				$i=0;

			foreach($events as $event):

				if( $i % 2 == 0 ):
					?>
				<div class=" et_pb_row et_pb_row_1">

					<?php 
					endif;
					++$i;

					?>
					<div class="events_col_<?php echo $i?>">
					<div class="et_pb_column et_pb_column_1_2  et_pb_column_<?php echo $i?>">

						<div class="et_pb_text et_pb_module et_pb_bg_layout_light et_pb_text_align_left  et_pb_text_<?php echo $i?>">

						<h3><?php echo ucwords($event['title'])?></h3>
							<?php if($event['image']!='') { ?>
								<p><img style="max-width:480px;" src="/uploads/events/<?php echo $event['image']?>" alt="<?php echo $event['title']; ?>" /></p>
							<?php } ?>
							<p><?php echo $event['date']?> at <?php echo $event['time']?></p>
							<em><?php echo ucwords($event['place']);?></em></p>

							<?php echo ucfirst($event['description']);?>

						</div> <!-- .et_pb_text -->
					</div> <!-- .et_pb_column -->
					</div>

					<?php if( $i % 2 ==0 ):?>
					</div> <!-- .et_pb_row -->
				<?php endif;?>

				<?php 
				if($i==2):
					$i=0;
				endif;

				?>
			<?php   endforeach; ?>
		<?php else: ?>
			<h1> <?= __('No Events Found');?></h1>
		<?php endif;?>



	</div> <!-- .et_pb_section -->

	<?php echo $this->element('books');?>

</div> <!-- .entry-content -->


</article> <!-- .et_pb_post -->

</div> <!-- #main-content -->
<style type="text/css" id="et-builder-advanced-style">

	.et_pb_image_1 { margin-left: 0; }
	.et_pb_image_0 { margin-left: 0; }
	.et_pb_section_2 { background-color:#dcdce5; }
</style>