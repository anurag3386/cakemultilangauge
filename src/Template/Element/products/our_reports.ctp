<?php use Cake\I18n\I18n;?>
<?php $locale = I18n::locale();?>
<div class="our_reports_common">
 <div class="et_pb_section  et_pb_section_2 et_pb_with_background et_section_regular">
<div class="et_pb_section  et_pb_section_1 et_pb_with_background et_section_regular ourReport">
	<div class=" et_pb_row et_pb_row_2 paddNone">
		<div class="et_pb_column et_pb_column_4_4  et_pb_column_2">
			<div class="et_pb_text et_pb_module et_pb_bg_layout_light et_pb_text_align_center  et_pb_text_1">
				<!-- <h2>< ?= __('OUR REPORTS');?></h2> -->
				 <h2><?= __('ASTROLOGY REPORTS');?></h2> 
				<div class="topContent">
					<p><?= __('Designed and written by astrologer Adrian Ross Duncan, our reports was designed to give you a fully immersive experience of astrology and of your own personal horoscope. We believe that astrology should be presented in an easily accessible way.');?></p>
				</div>

			</div> <!-- .et_pb_text -->
		</div> <!-- .et_pb_column -->

	</div> <!-- .et_pb_row -->


	<div id="our_reports" class="et_pb_row et_pb_row_3 et_pb_row_4col">

		<?php 
		
		$reports = $this->Report->getReports();
		if( isset($reports) && !empty($reports) ):
			foreach($reports as $report):
				/*
	             * Removed lovers report from report listing page for elite member
	             * Created By : Krishan Gupta
	             * Created Date : March 22, 2017
	             */
	            /*if ( !empty($this->request->session()->read('Auth.User.role')) && ($this->request->session()->read('Auth.User.role') == 'elite') && ($report['id'] == 5)) {
	                continue;
	            }*/
	            // END
			?>
			<div class="et_pb_column et_pb_column_1_4  et_pb_column_3">
				<div class="et_pb_module et-waypoint et_pb_image et_pb_animation_top et_pb_image_0 et_always_center_on_mobile et-animated">
					<?php 
					echo $this->Html->image("/uploads/products/".$report['image'] , [ 'alt' => $report['name']]);  
					?>

				</div>

				<div class="et_pb_text et_pb_module et_pb_bg_layout_light et_pb_text_align_center  et_pb_text_2">

					<h3><?php echo $report['name'] ?></h3>
					<p><?= __('Approx'); ?> <?php echo $report['pages']?> <?= __('pages');?><br>
					<!--<p><?php //echo $report['currency']['symbol']." ".$report['product_prices']['total_price']?>
				</p>-->
				<!--a href="#" class="btn btn-white">View Sample</a-->
				<?php echo  $this->Html->link(__('View Sample'), "/uploads/preview_reports/".$report['preview_reports']['pdf'], ['class' => 'btn btn-red-border', 'target' => '_blank']);
				?><br/>	
				<?php 
				echo $this->Html->link(__('Buy Now'), ['controller' => 'Products', 'action' => 'detail', $report['seo_url'],__('full-reports')], ['class' => 'btn btn-red']);
				?>


			</p>

		</div> <!-- .et_pb_text -->
	</div> <!-- .et_pb_column -->

<?php endforeach;?>
<?php else:?>
	<h4><?= __('No Reports Available');?></h4>
<?php endif;?>

</div> <!-- .et_pb_row -->
<div class=" et_pb_row et_pb_row_3">

	
</div> <!-- .et_pb_column -->

</div> <!-- .et_pb_row -->

<!-- </div> -->
 <!-- .et_pb_section -->
 </div>
 </div>