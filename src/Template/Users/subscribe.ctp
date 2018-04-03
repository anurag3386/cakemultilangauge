<?php
	//ini_set("session.cache_limiter", "must-revalidate");
	/*ini_set('session.cache_limiter','public');
	session_cache_limiter(false);*/
?>
<?php use Cake\Routing\Router; ?>
<?php use Cake\I18n\I18n;?>
<?php $locale = strtolower( substr(I18n::locale(), 0, 2 ) );?>
<?= $this->Html->css ('innerpage-custom'); ?>
<?= $this->Html->script ('innerpage-custom'); ?>
<div class="div-outer" id="subscribe_banner">
	<div class="div-outer" id="subs_img">
	<div class="et_pb_section">
	<div class="container">
	<div class="container_subscribe">
		<div class="div-outer banner-inn">
			<div class="message" style="text-align: center; color: #c43a5c;"><?php //echo $this->Flash->render() ?></div>
			<div class="div-clear">
				<div class="banner-inn-sub">
					<h2><?= __('Subscribe Now');?></h2>
					<div class="clear"></div>
					<p class="subtitle"><?= __('By subscribing for your personalized 1 year Astrology Calendar, you can now channel the planetary energies to your advantage');?></p>			
				</div>
			</div>
		</div>
		<div class="div-clear">
			<div>
				<!-- Left side form Start -->
				<?php if(isset($subscribe) && !empty($subscribe)) {
					$price = $subscribe['price'];
					$price = $this->Custom->getEpayPriceFormat($price); //str_replace('.', '', $price);
					$base_path = Router::url('/', true);
					$cancel = $base_path. "users/subscribe";
					
					$acceptUrl = $callback = $base_path."users/thank-you?utm_nooverride=1";
					//$callback = $base_path."users/thank-you";

					if( $locale == 'da')
					{
						$cancel = $base_path. "dk/brugere/tilmeld";
	  				 	$acceptUrl = $callback = $base_path."dk/brugere/tak?utm_nooverride=1";
					 	//$callback = $base_path."dk/brugere/tak";
					}
             

					$currency = $subscribe['currency'];
					$order_id = $subscribe['order_id'];

					$params = array('merchantnumber' => MERCHANT_ID, 'amount' => $price, 'currency' => $currency, 'instantcallback' => 1, 'orderid' => $order_id, 'ownreceipt' => 1, 'windowstate'=>3, 'accepturl'=>$acceptUrl, 'cancelurl'=> $cancel);
				?>
				<div id="checkout_4_wrapper">
					<div class="active">
						<script charset="UTF-8" src="./Checkout Step 4 _ AstroWow_files/paymentwindow.js" type="text/javascript"></script>
						<div id="payment-div">
							<script charset="UTF-8" src="https://ssl.ditonlinebetalingssystem.dk/integration/ewindow/paymentwindow.js" type="text/javascript"></script>
							<div id="payment-div"></div>
							<script type="text/javascript">
								paymentwindow = new PaymentWindow({
									<?php
										foreach ($params as $key => $value) {
											echo "'" . $key . "': \"" . $value . "\",\n";
										}
									?>
									'hash': "<?php echo md5(implode("", array_values($params)) . "AstrowowNethues"); ?>",
								});
								paymentwindow.append('payment-div');
								paymentwindow.open();
							</script>
						</div>
  					</div>
				</div>
				<?php } else { ?>

				<div class="left_form subscribe_form">
					<div style="margin-top: 20px;">
						<table id="tblRecSignUp" style="width:100%">
							<tbody>
								<tr>
									<td class="firstTD" style="width: 40%;" valign="top"><label for=""><?= __('Choose Currency');?></label></td>
									<td style="width: 1%;" valign="top">:</td>
									<td style="width: 59%;">
										<div class="redo">
										<ul>
										<?php 
										$ischecked = 0;
										//pr ($products); die;
										foreach ($products as $key => $value) {
											if ($value['product_prices']['total_price'] > 0) {
												$checked = '';
												if (!$ischecked) {
													$checked = 'checked';
													$ischecked = 1;
												}
                                                //changed by anurag dubey Nov-14-2017
                                               if(strtolower($value['currency']['symbol'])=='kr.'){
                                                    $value['product_prices']['total_price'] = round($value['product_prices']['total_price'],0);
                                                }
             									?>
											<input name="hdnCurrencyCode_1" id="hdnCurrencyCode_1" value="USD" type="hidden">
											<span class="txt-purple">
												<li>
													<input class="rdoCurrency" value="<?= $value['currency']['symbol'].$value['product_prices']['total_price'].'-'.$value['currency']['code']; ?>" name="RadioCurrency" id="symbol<?= $key; ?>" type="radio" <?= $checked; ?>>
													<label for="symbol<?= $key; ?>">
														<input name="hdCurrencyID_0&gt;" id="hdCurrencyID_0" value="1" type="hidden" >
														<?= $value['currency']['name'].'('.$value['currency']['symbol'].')'; ?>
													</label>
													<div class="check"></div>

                                                </li>
												<?php /* ?>
												<label>
													<input class="rdoCurrency" value="<?= $value['currency']['symbol'].$value['product_prices']['total_price'].'-'.$value['currency']['code']; ?>" name="RadioCurrency" id="usdollar" type="radio" <?= $checked; ?>>

													<input name="hdCurrencyID_0&gt;" id="hdCurrencyID_0" value="1" type="hidden" >
													<?= $value['currency']['name'].'('.$value['currency']['symbol'].')'; ?>
												</label>
												<?php */ ?>
											</span><br>
										<?php } 
										} ?>
										</ul>
										</div>
										<input name="hdnCurrencyCode_3" id="hdnCurrencyCode_3" value="GBP" type="hidden">
									</td>
								</tr>		
							</tbody>
						</table>

						<?php echo $this->Form->create ($entity, ['id'=>'formCalendarSubscription']); ?>
							<table id="tblRecSignUp" style="width:100%">		
								<tbody>
								<tr>
									<td colspan="3" height="5"></td>
								</tr>
									<tr>
										<td class="firstTD" style="width: 40%;">	
											<label><?= __('Buy Subscription for');?></label>
										</td>
										<td style="width: 1%;">:</td>
										<td style="width: 59%;">
											<?php
												$selectedUser = !empty($this->request->session()->read('Auth.User.id')) ? $this->request->session()->read('Auth.User.id') : '';
												$selectedUser = !empty($this->request->session()->read('user_id')) ? $this->request->session()->read('user_id') : $selectedUser;
												$selectedUser = !empty($this->request->session()->read('selectedUser')) ? $this->request->session()->read('selectedUser') : $selectedUser;
												if (strpos($selectedUser, '_') !== false) {
											        $selectedUser = str_replace('anotherPerson', 'AP', $selectedUser);
      											}
											?>
											<?php echo $this->Form->select ('user_id', $users, ['class' => 'select-sm', 'default' => $selectedUser]); ?>
											<?php echo $this->Form->input ('user_type', ['value'=>'user', 'type'=>'hidden']); ?>
										</td>
									</tr>
									<tr>
									<td colspan="3" height="5"></td>
								</tr>	
									<tr>
										<td class="firstTD" style="width: 40%;"><label><?= __('Price');?></label></td>
										<td style="width: 1%;">:</td>
										<td style="width: 59%;">
											<input name="product_id" id="product_id" value="19" type="hidden"> 
											<p>
												<!-- <span id="spnSymbol_1" class="currencySymbol currency_1 txt-purple" style="display: none; float: left;">$</span> --> 
												<!-- <span id="spnPrice_1" style="display: none; float: left;" class="currencyAmount currency_1 txt-purple">
												</span> -->
												<!-- <input id="hdnspnPrice_1" name="hdnspnPrice_1" value="3.85" type="hidden"> -->
												<span class="currencySymbol currency_3 txt-purple" id='symbol_price'>
												<!-- <span id="spnSymbol_3" class="currencySymbol currency_3 txt-purple" style="">£</span>
												<span id="spnPrice_3" style="" class="currencyAmount currency_3 txt-purple">

												19.95</span>
												 -->
												</span>
												<!-- <input id="hdnspnPrice_3" name="hdnspnPrice_3" value="3.35" type="hidden">
												<input id="hndSubId_0" value="7" type="hidden"> -->								
											</p>
											<?php
												echo $this->Form->input('CurrencyCode', ["name"=>"hdnCurrencyCode", "id"=>"hdnCurrencyCode", "type"=>"hidden", "value"=>"GBP"]);
												echo $this->Form->input('Price', ["name"=>"hdnPrice", "type"=>"hidden", "id"=>"hdnPrice", "value"=>"19.95"]);
											?>	
										</td>
									</tr>
									<tr>
									<td colspan="3" height="5"></td>
								</tr>
									<tr>
										<td colspan="3">
											<table>
												<tbody>
													<tr>
														<td class="checkbox_btn" valign="top" align="left">
															<label for="chkTermsAndCondiction"><p>
																<input class="validate[required] checkbox" name="chkTermsAndCondiction" id="chkTermsAndCondiction" value="1" type="checkbox">
																<?= __('By checking, I agree to the Astrowow');?>
																<?php echo $this->Html->link(__('Terms of Use'), ['controller'=>'Pages', 'action'=>'menu-pages', 'terms-of-use'], ['target' => '_blank']); ?>, Astrowow 
																<?php echo $this->Html->link(__('Privacy Policy'), ['controller'=>'Pages', 'action'=>'menu-pages', 'privacy-policy'], ['target' => '_blank']); ?> <?= __('and Communications Terms.');?>
															</p></label>
														</td>
													</tr>
												</tbody>
											</table>
										</td>
									</tr>
									<tr>
									<td colspan="3" height="5"></td>
								</tr>
									<tr>	
										<td colspan="2" style="width: 40%;"></td>
										<td style="width: 60%;" align="left">
											<?= $this->Form->input (__('Buy Now'), ['class' => 'sign-up-button', 'type' => 'submit', 'id' => 'btnSignup']); ?>
										</td>
									</tr>
									<tr>
										<td colspan="3">
											<br>
												<?= $this->Html->image ('Logos-creditcard.jpg', ['id' => 'imgCreditCard']); ?>
											<br>
											<small>
												<strong><?= __('Payment');?>:</strong> PayPal, MasterCard, Visa, JCB, Maestro, American Express, UnionPay
											</small>
										</td>
									</tr>
								</tbody>
							</table>
						<!-- </form> -->
						<?php echo $this->Form->end(); ?>
					</div>
				</div>
				<!-- Left side form end -->
				
				<!-- Middle separator start -->
				<div class="middle_separator"></div>
				<!-- Right side content End -->
				<?php } ?>
			</div>
		</div>
		</div>
		</div>
		<div class="clearfix"></div>
		</div>
	</div>
	<div class="clearfix"></div>
</div>
