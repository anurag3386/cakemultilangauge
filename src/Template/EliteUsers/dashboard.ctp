<?php use Cake\Cache\Cache; ?>
<?php echo $this->Html->css('elite-innerpage-custom'); ?>
<article id="post-1159" class="post-1159 page type-page status-publish hentry">
	<div class="entry-content">
<?php echo $this->element ('elite_expire'); ?>
			<div class="div-outer inn-container">
				<?= $this->Flash->render(); ?>
				<div class="div-clear">
					<div class="div-inner">
						<div class="common-content-left floatL">				
							<div class="common-box box-shadow margin0">					
								<div class="common-box-one">
			    					<h2>
			    						<?= $this->Html->image ('../images/icon-report.png', array ('alt' => 'Our Reports', 'title' => 'Our Reports')); ?>
			    						<?= __('Astrology Reports'); ?>
			    					</h2>
			    					<?php foreach ($products_detail as $key => $productlist) { ?>
			    					<div class="common-box">
			        					<div class="common-box-content">
			            					<ul>
			                					<li>
		                        					<img src="<?= $this->request->webroot; ?>uploads/products/<?= $productlist['image'] ?>" alt="<?= $productlist['name'] ?>" title="<?= $productlist['name'] ?>" width="112px" height="118px">
			                    					<span class="head" style="color: #333;">
								                    	<?php echo $productlist['name']; ?>
			                    					</span>
			                    					<?php echo $productlist['description']; ?>
			                    					<div class="clear"></div>
			                    					<ul class="btn-sml">
														<li>
															<?= $this->Form->create ('Order', ['url' => ['controller' => 'elite-users', 'action' => 'elite-report-checkout']]); ?>
																<?= $this->Form->hidden ('product_id', ['value' => $productlist['id']]); ?>
																<?= $this->Form->hidden ('user_id', ['value' => $user_id]); ?>
																<?php $email = $this->request->session()->read('Auth.User.username'); ?>
																<?= $this->Form->hidden ('price', ['value' => $productlist['ProductPrices']['total_price']]); ?>
																<?= $this->Form->hidden ('email', ['value' => $email]); ?>
																<?= $this->Form->hidden ('delivery_option', ['value' => $deliveryOption['id']]); ?>
																<?= $this->Form->hidden ('order_status', ['value' => $states['id']]); ?>
																<?= $this->Form->hidden ('order_date', ['value' => time()]); ?>
																<?= $this->Form->hidden ('confirm_payment_date', ['value' => time()]); ?>
																<?= $this->Form->hidden ('product_type', ['value' => 8]); ?>
																<?= $this->Form->hidden ('chk_for_register', ['value' => 0]); ?>
																<?= $this->Form->hidden ('currency_id', ['value' => $selectedCurrencyDetail['id']]); ?>
																<?= $this->Form->hidden ('shipping_charge', ['value' => 0]); ?>
																<?= $this->Form->hidden ('language_id', ['value' => 1]); ?>
																<?= $this->Form->hidden ('portal_id', ['value' => 2]); ?>
																<?= $this->Form->hidden ('currencySymbol', ['value' => $selectedCurrencyDetail['symbol']]); ?>
																<?= $this->Form->hidden ('payment_method', ['value' => 1]); ?>
																<?= $this->Form->button (__('Buy Now for ').$selectedCurrencyDetail['symbol'].$this->Custom->formatPrice($productlist['ProductPrices']['total_price'],$selectedCurrencyDetail['symbol']), ['class' => 'btn btn-red']).' ('.__('Inclusive VAT').')';
																?>
								                    		<?= $this->Form->end (); ?>
			                        					</li>                        
			                    					</ul>
			                					</li>
			                				</ul>
			                			</div>
			                		</div>
			                		<?php } ?>
								</div>
							</div>
							<div class="clear"></div>
						</div>
						<?= $this->element ('elite_member_products'); ?>
					</div>
				</div>
			</div>
	</div>
</article>
<style type="text/css">
	.message {text-align: center;}
	.success { color: green; }
	.error { color: red; }
</style>