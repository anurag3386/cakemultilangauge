<?php use Cake\Cache\Cache; ?>
<?php
	if (!empty($this->request->session()->read('locale')) && (strtolower($this->request->session()->read('locale')) == 'da')) {
		$slan = 'dk';
	} else {
		$slan = 'en';
	}
?>
<!-- Google Code for AstroWOW Report Orders Conversion Page -->
<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 1072504019;
var google_conversion_language = "<?= $slan; ?>"; //"<?php //echo $lan = (!empty($this->request->session()->read('locale')) && ($this->request->session()->read('locale') == 'da')) ? 'dk' : 'en' ; ?>";
var google_conversion_format = "3";
var google_conversion_color = "ffffff";
var google_conversion_label = "4aVkCL-U-G8Q07m0_wM";
var google_remarketing_only = false;
/* ]]> */
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/1072504019/?label=4aVkCL-U-G8Q07m0_wM&amp;guid=ON&amp;script=0"/>
</div>
</noscript>
<!-- Google Code for AstroWOW Report Conversion Page END-->

<article id="post-1272" class="post-1272 page type-page status-publish hentry">
	<div class="entry-content">
		<div class="et_pb_section  et_pb_section_0 et_section_regular">
			<div class=" et_pb_row et_pb_row_0">
				<div class="et_pb_column et_pb_column_4_4  et_pb_column_0">
					<div class="et_pb_code et_pb_module  et_pb_code_0">
						<div class="et_pb_section  et_pb_section_0 et_section_regular">
							<div id="checkout_5" class=" et_pb_row et_pb_row_0">
								<div class="et_pb_column et_pb_column_4_4  et_pb_column_0">
									<div class="et_pb_text et_pb_module et_pb_bg_layout_light et_pb_text_align_center  et_pb_text_0">
										<p>
 										<?php 
											echo $this->Html->image('ico_complete.png', ['alt' => 'ico_complete', 'width'=> '94', 'height' => '94', 'class' => 'aligncenter size-full wp-image-348'])
										?>
										</p>
										<h2 class="intro">
										<?= __('Thank you, we have received your payment');?></h2>
										<p><span><?= __('Please note your Order No');?>: <b><?php echo $order_id ?></b> <?= __('and Transaction No');?>: <b><?php echo $txnid ?></b> </span></p>
									</div> <!-- .et_pb_text -->
								</div> <!-- .et_pb_column -->
							</div> <!-- .et_pb_row -->
						</div>
					</div> <!-- .et_pb_code -->
				</div> <!-- .et_pb_column -->

			</div> <!-- .et_pb_row -->

		</div> <!-- .et_pb_section -->

		<?php echo $this->element('products/other_reports'); ?>
		<!-- .et_pb_section -->
		<?php //if(!$user = $this->request->session()->read('user_id')):?>

	    <?php //if(!$user = Cache::read('user_id')):?>		
			<?php //echo $this->element('newsletter'); ?>
		<?php //endif;?>

	</div> <!-- .entry-content -->


				</article> <!-- .et_pb_post -->