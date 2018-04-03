<?php //pr ($mydata); die; ?>
<article id="post-1178" class="post-1178 page type-page status-publish hentry full_des_content">
	<div class="entry-content">
		<div class="et_pb_row et_pb_row_1 pageTitle">
			<h1><?= __('Full Transit Description');?></h1>
		</div>

		<div class="et_pb_section  et_pb_section_2 et_section_regular cmsPage">
			<div id="timeline" class=" et_pb_row et_pb_row_1">
				<div class="et_pb_column   et_pb_column_1">
					<div class="et_pb_text et_pb_module et_pb_bg_layout_light et_pb_text_align_left  et_pb_text_1">
						<?php
							if (!empty($mydata['FullDesc'])) {
								echo "<div style='text-align: left;'><h3 style='color:#e4165b;'>".$mydata['Title']."</h3>";
								echo "<div>(".$mydata['FinalReplaceDate'].")</div><br/>";
								echo "<div>".$mydata['FullDesc']."</div></div>";
							}
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
</article>
<!-- .et_pb_post -->
<p>&nbsp;</p>
<!-- #main-content -->