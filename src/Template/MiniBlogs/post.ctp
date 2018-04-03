<?php use Cake\I18n\Time;?>
<article id="post-1180" class="post-1180 page type-page status-publish hentry">
	<div class="entry-content">
		<div class="mini_blogs_post">
			<div class=" et_pb_row et_pb_row_0">
				<div class="et_pb_column et_pb_column_4_4  et_pb_column_0">
					<div class="et_pb_text et_pb_module et_pb_bg_layout_light  et_pb_text_0">
						<h1 class="entry-title"><?= $post['title'];?></h1>
						<?php 
						$date = new Time($post['created']);
						?>
						<p class="post-meta"><span class="published"><?= date('F j, Y', strtotime( $date->i18nFormat('YY-MM-dd'))  ); ?></span> </p>
						
					</div>
					<!-- .et_pb_text --></div>
					<!-- .et_pb_column --></div>
					<!-- .et_pb_section -->

					<!-- .et_pb_section -->
					<div class="et_pb_section  et_pb_section_2 et_section_regular cmsPage">
						<div class="et_pb_row et_pb_row_1">

							<div class="et_pb_column et_pb_column_2_3  et_pb_column_1">
								<div class="et_pb_text et_pb_module et_pb_bg_layout_light et_pb_text_align_left  et_pb_text_1">
									<?= $post['description'];	?>
								</div>
								<!-- .et_pb_text --></div>

								<!-- .et_pb_column -->

								<div class="et_pb_column et_pb_column_1_3  et_pb_column_2">
									<?php 
										if (!empty($post['image'])) {
											echo $this->Html->image('/uploads/mini-blog/'.$post['image'], ['alt' => $post['title']]);
										} else {
											echo $this->Html->image('/images/no-image-available.jpeg', ['alt' => $post['title']]);
										}
									?>
								</div>
							</div>
							<!-- .et_pb_row --></div>
							<!-- .et_pb_section --></div>
						</div>
						<!-- .entry-content -->
					</article>
<!-- .et_pb_post -->