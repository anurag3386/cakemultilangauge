<?php echo $this->Html->css('elite-innerpage-custom'); ?>
<?php use Cake\Routing\Router; ?>
<?php //pr ($orders); die;
$selectedLocale = !empty($this->request->session()->read('locale')) ? strtolower($this->request->session()->read('locale')) : 'en'; ?>
<article id="post-1159" class="post-1159 page type-page status-publish hentry">
	<div class="entry-content">
	<?php echo $this->element ('elite_expire'); ?>
			<div class="div-outer inn-container" id="order-list-container">
				<div><?= $this->Flash->render(); ?></div>
				<div class="div-clear">
					<div class="div-inner">
						<div class="common-content-left floatL">
							<div class="common-box box-shadow margin0">
								<div class="common-box-one" id="order-list-common-box-one">
									<h2>
										<?= $this->Html->image ('../images/icon-report.png', ['title'=>'Change Password', 'alt'=>'Our Reports', 'title'=>'Our Reports']); ?>
										<?= __('Order History'); ?>
									</h2>
									<div class="common-box-content">
										<div class="dvClear"></div>
										<div id="dvSearchResult" class="tblViewOrderHistory">
											<?php //if (!empty($orders)) { ?>
											<?php if (!$orders->isEmpty()) { ?>
												<div id="no-more-tables">
                                            		<table class="col-md-12 table-bordered table-striped table-condensed cf">
		                                                <thead class="cf">
		                                                    <tr>
		                                                        <th class="numeric"><?= __('Order Id');?></th>
		                                                        <th class="numeric"><?= __('User Name');?></th>
		                                                        <th class="numeric"><?= __('Product Name');?></th>
		                                                        <th class="numeric"><?= __('Order Date');?></th>
		                                                        <th class="numeric"><?= __('Order Status');?></th>
		                                                        <th class="numeric"><?= __('Operations');?></th>
		                                                    </tr>
		                                                </thead>
                                                		<tbody>
                                                    		<?php foreach ($orders as $order) { ?>
                                                    			<tr>
                                                        			<td data-title="<?= __('Order Id');?>" class="numeric">
                                                            			<?php $encodedProductId = base64_encode($order->id); ?>
																		<?php
																			//if( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' ) {
																				if ($selectedLocale == 'da') {
																					$detailpath = Router::url('/', true).'dk/'.__('elite-users').'/'.__('order-detail').'/'.$encodedProductId;
																				} else {
																					$detailpath = Router::url('/', true).__('elite-users').'/'.__('order-detail').'/'.$encodedProductId;
																				} ?>
																				<a href="<?php echo $detailpath; ?>"><?php echo $order->payer_order_id; ?></a>
																		<?php
																			/*} else {
																				echo $this->Html->link ($order->payer_order_id, ['controller' => 'elite-users', 'action' => 'order-detail', $encodedProductId]);
																			}*/
																		?>
                                                        			</td>
			                                                        <td data-title="<?= __('User Name');?>" class="numeric">
			                                                            <?= ucwords($order['BirthData']['first_name'].' '.$order['BirthData']['last_name']); ?>
			                                                        </td>

			                                                        <?php $prodtcDe = array();
																		$prodtcDe = $this->Custom->getProductDetail ($order->product_id); ?>
																		<?php if (!empty($prodtcDe)) { ?>
																			<td data-title="<?= __('Product Name');?>" class="numeric">
																				<?= __d('default', $prodtcDe['name']); ?>
																			</td>
																		<?php } else { ?>
																			<td data-title="<?= __('Product Name');?>" class="numeric">NA</td>
																		<?php } ?>

			                                                        <td data-title="<?= __('Order Date');?>" class="numeric">
			                                                            <?php
																			if ($selectedLocale == 'da') {
																				$order_date = str_replace('/', '-', $order->order_date);
																				echo date('d-m-Y', strtotime($order_date));
																			} else {
																				echo date('d-m-Y', strtotime($order->order_date));
																			}
																		?>
			                                                        </td>
			                                                        <td data-title="<?= __('Order Status');?>" class="numeric">
			                                                            <?php
																			if (strtolower($states[$order->order_status]) == 'queued') {
																				echo __('New');
																			} else {
																				echo __d('default', ucfirst($states[$order->order_status]));
																			}
																		?>
			                                                        </td>
			                                                        <td data-title="<?= __('Operations');?>" class="numeric">
			                                                            <?php
																			if ($order['order_status'] == 9) {
																				if ($selectedLocale == 'da') {
																					$downloadPath = Router::url('/', true).'dk/'.__('elite-users').'/'.__('download').'/'.$encodedProductId;
																				} else {
																					$downloadPath = Router::url('/', true).__('elite-users').'/'.__('download').'/'.$encodedProductId;
																				} ?>
																				<a href="<?= $downloadPath; ?>"><?= __('DOWNLOAD'); ?></a>
																				<?php //echo $this->Html->link (__('DOWNLOAD'), ['controller' => 'elite-users', 'action' => 'download', $encodedProductId]).' | ';
																				echo ' | ';

																				/*if( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' || $_SERVER['HTTP_X_FORWARDED_FOR'] == '103.254.97.14' || $_SERVER['HTTP_X_FORWARDED_FOR'] == '103.248.117.12' ) {*/
																					if ($selectedLocale == 'da') {
																						$regenerateLink = Router::url('/', true).'dk/'.__('elite-users').'/'.__('regenerate').'/'.$encodedProductId;
																					} else {
																						$regenerateLink = Router::url('/', true).__('elite-users').'/'.__('regenerate').'/'.$encodedProductId;
																					} ?>
																					<a href="<?= $regenerateLink; ?>"><?= __('REGENERATE'); ?></a>
																					<?php //echo $this->Html->link (__('REGENERATE'), ['controller' => 'elite-users', 'action' => 'regenerate', '?' => ['q' => $encodedProductId, 'apikey' => md5('astrowow.com')]]).' | ';
																				/*} else {
																					echo $this->Html->link (__('REGENERATE'), ['controller' => 'elite-users', 'action' => 'regenerate', '?' => ['q' => $encodedProductId, 'apikey' => md5('astrowow.com')]]).' | ';
																				}*/
																				echo ' | ';
																			}
																			echo $this->Html->link (__('VIEW'), ['controller' => 'elite-users', 'action' => 'order-detail', $encodedProductId]);
																		?>
			                                                        </td>
                                                    			</tr>
                                                    		<?php } ?>
                                                		</tbody>
                                            		</table>
                                        		</div>

												<?php /* ?>
												<table>	
													<tbody>
														<tr style="background-color: #e4165b; color: #ffffff;">
															<td width="5%"><?= __('Order Id');?></td>
															<td width="10%"><?= __('User Name');?></td>
															<td width="35%"><?= __('Product Name');?></td>
															<td width="20%"><?= __('Order Date');?></td>
															<td width="5%"><?= __('Order Status');?></td>
															<td width="25%"><?= __('Operations');?></td>
														</tr>
														<?php foreach ($orders as $order) { ?>
															<tr>
																<td>
																	<?php $encodedProductId = base64_encode($order->id); ?>
																	<?= $this->Html->link ($order->payer_order_id, ['controller' => 'elite-users', 'action' => 'order-detail', $encodedProductId/*'?' => ['q' => $encodedProductId]*//*]); ?>
																</td>
																<td>
																	<?= ucwords($order['BirthData']['first_name'].' '.$order['BirthData']['last_name']); ?>
																</td>
																<?php $prodtcDe = array();
																$prodtcDe = $this->Custom->getProductDetail ($order->product_id); ?>
																<?php if (!empty($prodtcDe)) { ?>
																	<td><?= __d('default', $prodtcDe['name']); ?></td>
																<?php } else { ?>
																	<td>NA</td>
																<?php } ?>
																<td>
																	<?php
																		if (!empty ($this->request->session()->read('locale')) && ($this->request->session()->read('locale') == 'da')) {
																			$order_date = str_replace('/', '-', $order->order_date);
																			echo date('d-m-Y', strtotime($order_date));
																		} else {
																			echo date('d-m-Y', strtotime($order->order_date));
																		}
																	?>
																</td>
																<td>
																	<?php
																		if (strtolower($states[$order->order_status]) == 'queued') {
																			echo __('New');
																		} else {
																			echo __d('default', ucfirst($states[$order->order_status]));
																		}
																	?>
																</td>
																<td>
																	<?php
																		if ($order['order_status'] == 9) {
																			echo $this->Html->link (__('DOWNLOAD'), ['controller' => 'elite-users', 'action' => 'download', '?' => ['q' => $encodedProductId, 'apikey' => md5('astrowow.com')]]).' | ';

																			echo $this->Html->link (__('REGENERATE'), ['controller' => 'elite-users', 'action' => 'regenerate', '?' => ['q' => $encodedProductId, 'apikey' => md5('astrowow.com')]]).' | ';
																		}
																		echo $this->Html->link (__('VIEW'), ['controller' => 'elite-users', 'action' => 'order-detail', $encodedProductId/*'?' => ['q' => $encodedProductId]*//*]);
																	?>
																</td>
															</tr>
														<?php } ?>
													</tbody>
												</table>
												<?php */ ?>
												<?php if ($this->Paginator->params()['count'] > $this->Paginator->params()['perPage']) { ?>
				                                    <div class="box-footer clearfix">
				                                        <ul class="pull-right reportsPagination">
				                                          <?php echo $this->Paginator->prev(' < '); ?>
				                                          <?php echo $this->Paginator->numbers(); ?>
				                                          <?php echo $this->Paginator->next(' > '); ?>
				                                        </ul>
				                                    </div>
	                                   			<?php } ?>
	                                   		<?php } else { ?>
	                                   			<div id="no-more-tables">
		                                            <table class="col-md-12 table-bordered table-striped table-condensed cf">
		                                                <thead class="cf">
		                                                    <tr>
		                                                        <th class="numeric"><?= __('Order Id');?></th>
		                                                        <th class="numeric"><?= __('User Name');?></th>
		                                                        <th class="numeric"><?= __('Product Name');?></th>
		                                                        <th class="numeric"><?= __('Order Date');?></th>
		                                                        <th class="numeric"><?= __('Order Status');?></th>
		                                                        <th class="numeric"><?= __('Operations');?></th>
		                                                    </tr>
		                                                </thead>
		                                                <tbody>
		                                                    <tr>
		                                                        <td data-title='' class="numeric" colspan="6" style="text-align: center; padding-left: 0px !important; background-color: #fff;">
		                                                            <?= __('Currently you do not have any purchased reports.'); ?>
		                                                        </td>
		                                                    </tr>
		                                                </tbody>
		                                            </table>
		                                        </div>
	                                   		<?php } ?>
										</div>
									</div>
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
	.message { text-align: center; }
	.success { color: green; }
	.error { color: red; }
</style>