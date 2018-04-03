<?php use Cake\I18n\I18n;?>
<?php use Cake\Routing\Router;?>
<?php $locale = I18n::locale();?>
<div class="our_software_section">

<div id="our_software" class="et_pb_section  et_pb_section_2 et_pb_with_background et_section_regular" style="background-color:#65a4c7;">
<div class="our_software_1">
	<div class=" et_pb_row et_pb_row_4">
		<div class="et_pb_column et_pb_column_4_4  et_pb_column_8">
			<div class="et_pb_text et_pb_module et_pb_bg_layout_dark et_pb_text_align_center  et_pb_text_4">
				<h2><?= __('OUR SOFTWARE');?></h2>
			</div> <!-- .et_pb_text -->
		</div> <!-- .et_pb_column -->
	</div> <!-- .et_pb_row -->
	</div>
<div class="our_software_2">

	<div class=" et_pb_row et_pb_row_5">
		<?php $products = $this->Custom->getProducts('software', SOFTWARE_CD);?>
		<?php foreach($products as $product)
		{
			?>

			<div class="et_pb_column et_pb_column_1_3  et_pb_column_9">
				<div class="et_pb_module et-waypoint et_pb_image et_pb_animation_top et_pb_image_4 et_always_center_on_mobile et-animated">
					<?php
						//if( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' ) {
							echo $this->Html->image('/uploads/products/'.$product['image'], ['alt'=> __($product['name'])]);
						/*} else {
							echo $this->Html->image('/uploads/products/'.$product['image'], ['alt'=> $product['name']]);
						}*/
					?>

				</div>
				<div class="et_pb_text et_pb_module et_pb_bg_layout_dark et_pb_text_align_center  et_pb_text_5">
					<h4><?php echo ucwords($product['name']);?></h4>
					<p><?php 


 								   $content = strip_tags( $product['short_description'] );
                                   $length  = strlen($content);
                                   if( $length >= 100 )
                                   {
                                   $pos=strpos($content, ' ', 100);
                                   $softwareData = substr($content, 0, $pos );
                                   echo __d('default', $softwareData).'...';
                                   }
                  	?></p>
					<h5>
						<?php echo $product['currency']['symbol'].$product['product_prices']['total_price']?>                            <span>(<?= __('30 DAYS FREE TRIAL'); ?>)</span></h5>
						<p>
							<?php
							//if ($_SERVER['REMOTE_ADDR'] == '103.254.97.14') {
								if ($this->request->session()->read('locale') == 'da') {
                                	echo $this->Html->link( __('View Details'), Router::url('/dk/'.__('astrology-software').'/'. __d('default', $product['seo_url']). '/'.__('software-cd'), true));
                              	} else {
                                	echo $this->Html->link( __('View Details'), Router::url('/'.__('astrology-software').'/'. __d('default', $product['seo_url']). '/'.__('software-cd'), true));
                              	}
							/*} else {
								echo $this->Html->link(__('View Details'), ['controller' => 'Products', 'action' => 'detail', $product['seo_url'],'software-cd', $product['id']]);
							}*/ ?>
					
						</p>
					</div> <!-- .et_pb_text -->
				</div> <!-- .et_pb_column -->
				<?php }?>

			</div> <!-- .et_pb_row -->
			</div>
<div class="our_software_3">

			<div class=" et_pb_row et_pb_row_6">
				<div class="et_pb_column et_pb_column_4_4  et_pb_column_12">
					<div class="et_pb_text et_pb_module et_pb_bg_layout_dark et_pb_text_align_center  et_pb_text_8">

						<?php
						//if ($_SERVER['REMOTE_ADDR'] == '103.254.97.14') {
							if ($this->request->session()->read('locale') == 'da') {
	                        	echo $this->Html->link( __('View All Our Software'), Router::url('/dk/'.__('astrology-software'), true), ['style' => 'color: #fff; text-decoration: underline;']);
	                      	} else {
	                        	echo $this->Html->link( __('View All Our Software'), Router::url('/'.__('astrology-software'), true), ['style' => 'color: #fff; text-decoration: underline;']);
	                      	}
                      	/*} else {
							echo $this->Html->link(__('View All Our Software'), ['controller' => 'Products', 'action' => 'astrology', 'software'], ['style' => 'color: #fff; text-decoration: underline;']);
						}*/
						?>
					
							</div> <!-- .et_pb_text -->
						</div> <!-- .et_pb_column -->
					</div> <!-- .et_pb_row -->
					</div>
				</div>
</div>