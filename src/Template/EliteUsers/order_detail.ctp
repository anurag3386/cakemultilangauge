<?php echo $this->Html->css('elite-innerpage-custom'); ?>
<article id="post-1159" class="post-1159 page type-page status-publish hentry">
	<div class="entry-content">
		<?php echo $this->element ('elite_expire'); ?>
			<div class="div-outer inn-container">
				<div class="div-clear">
					<div class="div-inner">
						<div class="common-content-left floatL">
							<div class="common-box box-shadow margin0">
								<div class="common-box-one order-detail-table">
									<div></div>
									<h2>
										<img src="<?= $this->request->webroot; ?>images/icon-report.png" alt="Order Detail" title="Order Detail" /><?= __('Order Detail'); ?>
										 ( <?= $this->Html->link (__('Back to order history'), ['controller' => 'elite-users', 'action' => 'order-list']) ?> ) 
									</h2>
						
									<div class="common-box-content">
										<?php if(!empty($OrderDetails)) { ?>
											<table style="width: 100% !important;">
												<tr>
													<td width="35%"><label><strong><?= __('Order No'); ?>:</strong></label></td>
													<td width="65%"><?= $OrderDetails['payer_order_id'] ?></td>
												</tr>
												<tr>
													<td><label><strong><?= __('Order Date'); ?>:</strong></label></td>
													<td>
														<?php
															if (!empty ($this->request->session()->read('locale')) && ($this->request->session()->read('locale') == 'da')) {
																$order_date = str_replace('/', '-', $OrderDetails['order_date']);
																echo date('d-m-Y', strtotime($order_date));
															} else {
																echo date('d-m-Y', strtotime($OrderDetails['order_date']));
															}
														?>	
														<?php //echo date("d-m-Y", strtotime($OrderDetails['order_date'])) ?>
													</td>
												</tr>
												<tr>
													<td><label><strong><?= __('Order Status'); ?>:</strong></label></td>
													<td>
													<?php echo __d('default', ucwords($OrderDetails['States']['name']));  ?></td>
												</tr>
												<tr>
													<td><label><strong><?= __('PRODUCT NAME'); ?>:</strong></label></td>
													<td><?= __d('default', $OrderDetails['Products']['name']); ?></td>
												</tr>
												<tr>
													<td><label><strong><?= __('LANGUAGE'); ?>:</strong></label></td>
													<td><?= $OrderDetails['Languages']['name']; ?></td>
												</tr>
												<tr><td colspan="2"><h2 class="customer_bdetails"><?= __('Customer Birth Detail'); ?></h2></td></tr>
												<tr>
													<td><label><strong><?= __('Customer Full Name'); ?>:</strong></label></td>
													<td><?= ucwords(sprintf("%s %s" , $OrderDetails['BirthData']['first_name'], $OrderDetails['BirthData']['last_name'])); ?></td>
												</tr>
												<tr>
													<td><label><strong><?= __('Gender'); ?>:</strong></label></td>
													<td>
														<?php
															if (strtolower($OrderDetails['Profiles']['gender']) == 'f') {
																echo __("Female");
															} else {
																echo __("Male");
															}
														?>
													</td>
												</tr>								
												<tr>
													<td><label><strong><?= __('Date of Birth'); ?>:</strong></label></td>
													<td>
														<?= date("d-m-Y", strtotime($OrderDetails['BirthDetail']['date'])) ?>
													</td>
												</tr>
												<tr>
													<td><label><strong><?= __('Time of Birth'); ?>:</strong></label></td>
													<td><?= date('g:i A', strtotime($OrderDetails['BirthDetail']['time'])); ?></td>
												</tr>
												<tr>
													<td><label><strong><?= __('Birth City'); ?>:</strong></label></td>
													<td><?= $OrderDetails['Cities']['city']; ?></td>
												</tr>
												<tr>
													<td><label><strong><?= __('Birth Country/State'); ?>:</strong></label></td>
													<td><?= $OrderDetails['Countries']['name']; ?></td>
												</tr>
												<tr>
													<td><label><strong><?= __('Time Zone'); ?>:</strong></label></td>
													<td>

														<?php
	                                                        $timezoneName = '';
	                                                        if (isset($OrderDetails['Countries']['name']) && !empty($OrderDetails['Countries']['name'])) {
	                                                            $twoLetterAbbreviationOfCountry = $this->Custom->countryShortCode ($OrderDetails['Countries']['name']);
	                                                            if (!empty($twoLetterAbbreviationOfCountry)) {
	                                                                $arr = DateTimeZone::listIdentifiers(DateTimeZone::PER_COUNTRY, $twoLetterAbbreviationOfCountry);
	                                                                foreach ($arr as $key => $value) {
	                                                                    if (!empty($value)) {
	                                                                        $timezoneName = $value;
	                                                                    }
	                                                                }
	                                                            } else {
	                                                                $timezoneName = $this->Custom->userTimeZoneName ($TimeZoneData['timezone']);
	                                                            }
	                                                        } else {
	                                                            $timezoneName = $this->Custom->userTimeZoneName ($TimeZoneData['timezone']);
	                                                        }
	                                                        echo $timezoneName." (".$TimeZoneData['timezone'].")";
                                                    	?>

														<?php
                                                        	/*list($hours, $minutes) = explode(':', $TimeZoneData['timezone']);
                                                        	$seconds = $hours * 60 * 60 + $minutes * 60;
                                                        	// Get timezone name from seconds
                                                        	$tz = timezone_name_from_abbr('', $seconds, 1);
	                                                        // Workaround for bug #44780
	                                                        if($tz === false) $tz = timezone_name_from_abbr('', $seconds, 0);
                                                        	echo $tz." ( ".$TimeZoneData['timezone']." )";*/
                                                    	?>
													</td>
												</tr>
												<tr>
													<td><label><strong><?= __('Summer Time Zone'); ?>:</strong></label></td>
													<td>
														<?php echo $TimeZoneData['summerreff']; ?>
													</td>
												</tr>
											</table>
										<?php } ?>
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