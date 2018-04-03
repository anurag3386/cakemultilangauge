<?php use Cake\Cache\Cache; ?>
<?php
    $session = $this->request->session();
    $amount = $session->read('Elite.Elite Customer Amount');
    $sign = $session->read('Elite.Elite Currency Sign');
    $vat = !empty($session->read('Elite.priceDetail.vat')) ? $session->read('Elite.priceDetail.vat') : 0;
?>
<article id="post-1159" class="post-1159 page type-page status-publish hentry">            
    <div class="entry-content">
        <div class="et_pb_section  et_pb_section_0 et_section_regular">
            <div class=" et_pb_row et_pb_row_0">
                <div class="et_pb_column et_pb_column_4_4  et_pb_column_0">
                    <div class="et_pb_code et_pb_module  et_pb_code_0">
                        <!-- <form name="frmCheckOutStepOne" id="frmCheckOutStepOne" method="post" class="checkout_form" action=""> -->
                        <?= $this->Form->create ('EliteUsers', ['id'=>'frmCheckOutStepOne', 'class'=>'checkout_form']); ?>
                            <div class="et_pb_section checkout_header et_pb_section_0 et_section_regular">
                                <div class=" et_pb_row et_pb_row_0">
                                    <div class="et_pb_column et_pb_column_4_4  et_pb_column_0">
                                        <div class="et_pb_code et_pb_module  et_pb_code_0">
                                        	<div class="checkoutLeft">
                                            	<h3 class="checkout_title"><?= __('Elite Customer');?></h3>
                                        	</div>
                                            <?php /* <h3 class="checkout_price"><?= __('Total Price:');?> <strong><?= $sign.$amount; ?></strong></h3> */ ?>
                                            <?php 
                                                //changed by Anurag Dubey nov 14 2017
                                                
                                            ?>

                                            <div class="total_price_section">
                                            <?php if($vat) { ?>
                                                    <h3 class="checkout_price">
                                                        <?= __('Price');?>: <?= $session->read('Elite.priceDetail.currency_symbol').$this->Custom->formatPrice($session->read('Elite.priceDetail.price'),$session->read('Elite.priceDetail.currency_symbol')); /*$this->Custom->removeSpace($session->read('Elite.priceDetail.price'));*/ ?>
                                                    </h3>
                                                    <h4 class="">
                                                        <?= __('VAT').': '.$session->read('Elite.priceDetail.currency_symbol').$this->Custom->formatPrice($session->read('Elite.priceDetail.vat'),$session->read('Elite.priceDetail.currency_symbol')); /*$this->Custom->removeSpace($session->read('Elite.priceDetail.vat'));*/ ?>
                                                    </h4>
                                                    <h5 class="">
                                                        <?= __('Total Price');?>: <strong><?php echo $session->read('Elite.priceDetail.currency_symbol').$this->Custom->formatPrice($session->read('Elite.priceDetail.total_price'),$session->read('Elite.priceDetail.currency_symbol')); /*$this->Custom->removeSpace($session->read('Elite.priceDetail.total_price'));*/
                                                        ?></strong>
                                                    </h5>
                                            <?php } else { ?>
                                                <h5 class="">
                                                    <?= __('Total Price');?>: <strong><?= $sign.$this->Custom->formatPrice($amount,$session->read('Elite.priceDetail.currency_symbol')); /*$amount;*/ ?></strong>
                                                </h5>
                                            <?php } ?>
                                            </div>
                                            <p></p>

                                            <div class="two_steps">
                                                <div class="checkoutProgressBox">
                                                    <div class="progressCol active">
                                                        <div class="stepNo"><span>1</span></div>
                                                        <div class="stepName"><?= __("General Info"); ?></div>
                                                    </div>
                                                    <div class="progressCol">
                                                        <div class="stepNo"><span>2</span></div>
                                                        <div class="stepName"><?= __("Payment Method"); ?></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <br class="clearfix"><br>
                                            <hr>
                                        </div> <!-- .et_pb_code -->
                                    </div> <!-- .et_pb_column -->
                                </div> <!-- .et_pb_row -->
                            </div>
                            <div id="checkout_1_wrapper">
                                <br>
                                <h2><?= __('General Information');?></h2>
                                <label for=""><?= __('First Name');?></label>
                                <input name="firstname" id="firstname" tabindex="2" class="inputLarge" placeholder="John" value="<?= $eliteUserDetails['first_name']; ?>" type="text" readonly = 'readonly'><br>
                                <label for=""><?= __('Last Name');?></label>
                                <input name="lastname" id="lastname" tabindex="3" class="inputLarge" placeholder="Doe" value="<?= $eliteUserDetails['last_name']; ?>" type="text" readonly = 'readonly'><br>
                                <label for=""><?= __('Email');?></label>
                                <input name="email_id" id="email_id" tabindex="4" class="inputLarge" placeholder="youremail@example.com" value="<?= $email['username']; ?>" type="text" readonly = 'readonly'><br>
                                <input name="pt" id="pt" value="S2tjM0kvbWJWMDE2QmlVcmROUWgzUT09" type="hidden">
                                <input name="oic" id="oic" value="0" type="hidden">
                                <input name="am" id="am" value="s" type="hidden">
                                <input name="currency" id="currency" value="1" type="hidden">
                                <input name="elitemodule" id="elitemodule" value="S2tjM0kvbWJWMDE2QmlVcmROUWgzUT09" type="hidden">
                            </div>
                            <div class="et_pb_section  et_pb_section_2 et_section_regular">
                                <div class=" et_pb_row et_pb_row_2">
                                    <div class="et_pb_column et_pb_column_4_4  et_pb_column_2">
                                        <div class="et_pb_text et_pb_module et_pb_bg_layout_light et_pb_text_align_right checkout_footer et_pb_text_2">
                                            <hr>
                                            <button type="submit" name="btnStartOrderProcess" id="btnStartOrderProcess" class="btn btn-red"><?= __('Personal Info');?> <i class="fa fa-long-arrow-right" aria-hidden="true"></i></button>
                                        </div> <!-- .et_pb_text -->
                                    </div> <!-- .et_pb_column -->
                                </div> <!-- .et_pb_row -->
                            </div>
                        <!-- </form> -->
                        <?= $this->Form->end(); ?>
                    </div> <!-- .et_pb_code -->
                </div> <!-- .et_pb_column -->
            </div> <!-- .et_pb_row -->
		</div>
    </div>
</article>
                