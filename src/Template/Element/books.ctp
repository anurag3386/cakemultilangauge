<div class="astro_books">
<div id="home_books" class="et_pb_section  et_pb_section_6 et_pb_with_background et_section_regular">
		<div class=" et_pb_row et_pb_row_9">
			<div class="et_pb_column et_pb_column_4_4  et_pb_column_13">
				<div class="et_pb_text et_pb_module et_pb_bg_layout_light et_pb_text_align_left  et_pb_text_6">
					<h2><?php echo __('ASTROLOGY BOOKS');?><cite><?php echo __('by Adrian Duncan');?></cite></h2>

				</div> <!-- .et_pb_text -->
			</div> <!-- .et_pb_column -->

		</div> <!-- .et_pb_row -->
		<!-- .et_pb_row -->
        <?php $books = $this->Custom->getBooks();?>
		<?php if(!$books->isEmpty()):?>

			<?php foreach($books as $book):?>
				<div class="et_pb_row et_pb_row_10 et_pb_row_1-4_3-4">
					<div class="et_pb_column et_pb_column_1_4  et_pb_column_14">
						<div class="et_pb_module et-waypoint et_pb_image et_pb_animation_left et_pb_image_3 et_always_center_on_mobile et-animated">
							<?php echo $this->Html->image('/uploads/books/'.$book->image,['alt'=>$book->title])?>
						</div>
					</div>
					<!-- .et_pb_column -->
					<div class="et_pb_column et_pb_column_3_4  et_pb_column_15">

						<div class="et_pb_text et_pb_module et_pb_bg_layout_light et_pb_text_align_left  et_pb_text_7">

							<h4><?php echo $book->title?></h4>
							<p>
								<cite><?= __('by');?> <?php echo $book->author;?></cite>
							</p>
							 <p><?php echo $book->description;?></p>
							<p>
								<strong>$<?php echo $book->price;?></strong>
								<?php echo $this->Html->link($book->button_text,$book->url,['class'=>'btn btn-red','target'=>'_blank'])?>
								<!--a href="#" class="btn btn-red">Go to Amazon</a-->
								<label>
									<?php echo $book->discount_text;?></label>
								</p>

							</div>
							<!-- .et_pb_text -->
						</div>
						<!-- .et_pb_column -->

					</div>

				<?php endforeach;?>
			<?php else:?>
				<p><?= __('No Books Found');?></p>
			<?php endif;?>

		</div> <!-- .et_pb_section -->
</div>