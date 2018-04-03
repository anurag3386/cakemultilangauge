<?php use Cake\Cache\Cache; ?>
<?php 
    $session = $this->request->session();
    $form = 'EliteUserReport';
    $product_name = $session->read('EliteReportOrder.ProductDetail.name');
    $pages = $session->read('EliteReportOrder.ProductDetail.pages');
    $image = $session->read('EliteReportOrder.ProductDetail.image');
    $price = trim($session->read('EliteReportOrder.price'));
    $currencySyb = $session->read('EliteReportOrder.currencySymbol');

    $muyemail = !empty($session->read('EliteReportOrder.UserDetail.email')) ? $session->read('EliteReportOrder.UserDetail.email') : '';
    $fname = !empty($session->read('EliteReportOrder.UserDetail.first_name')) ? $session->read('EliteReportOrder.UserDetail.first_name') : '';
    $lname = !empty($session->read('EliteReportOrder.UserDetail.last_name')) ? $session->read('EliteReportOrder.UserDetail.last_name') : '';
    $vat = !empty($session->read('EliteReportOrder.priceDetail.vat')) ? $session->read('EliteReportOrder.priceDetail.vat') : 0;
?>
<article id="post-1159" class="post-1159 page type-page status-publish hentry">
    <div class="entry-content">
        <?php //echo $this->element ('elite_expire'); ?>
        <div class="custom_report_checkout_class">
        <div class="et_pb_section  et_pb_section_0 et_section_regular">
            <div class=" et_pb_row et_pb_row_0">
                <div class="et_pb_column et_pb_column_4_4  et_pb_column_0">
                    <div class="et_pb_code et_pb_module  et_pb_code_0">
                        <?php echo $this->Form->create($form,  ['id' => 'step-1', 'class' => 'checkout_form', 'url' => ['controller' => 'elite-users', 'action' => 'elite-report-checkout2']])?>
                        <div class="additional_div">
                            <div class="et_pb_section checkout_header et_pb_section_0 et_section_regular">
                                <div class=" et_pb_row et_pb_row_0">
                                    <div class="et_pb_column et_pb_column_4_4  et_pb_column_0">
                                        <div class="et_pb_code et_pb_module  et_pb_code_0">
                                            <div class="checkoutLeft">
                                                <h3 class="checkout_title"><?php echo ucwords($product_name)?></h3>
                                                <cite><?php echo __('Approx').' '.$pages.' '.__('pages'); ?></cite>
                                                <p><cite><?= __('Deliver through Email'); ?></cite></p>
                                                <?php echo $this->Html->image("/uploads/products/".$image, [ 'class' => 'size-thumbnail wp-image-164', 'alt'=>'' ])?>
                                            </div>
                                            <?php 
                                                //changed by Anurag Dubey nov 14 2017
                                                
                                            ?>
                                            <div class="total_price_section">
                                            <?php if($vat) { ?>
                                                    <h3 class="checkout_price">
                                                        <?= __('Price');?>: <?= $session->read('EliteReportOrder.priceDetail.currency_symbol').$this->Custom->formatPrice($session->read('EliteReportOrder.priceDetail.price'),$session->read('EliteReportOrder.priceDetail.currency_symbol')); /*$this->Custom->removeSpace($session->read('EliteReportOrder.priceDetail.price'));*/ ?>
                                                    </h3>
                                                    <h4 class="">
                                                        <?= __('VAT').': '.$session->read('EliteReportOrder.priceDetail.currency_symbol').$this->Custom->formatPrice($session->read('EliteReportOrder.priceDetail.vat'),$session->read('EliteReportOrder.priceDetail.currency_symbol')); /*$this->Custom->removeSpace($session->read('EliteReportOrder.priceDetail.vat'));*/ ?>
                                                    </h4>
                                                    <h5 class="">
                                                        <?= __('Total Price');?>: <strong><?php echo $session->read('EliteReportOrder.priceDetail.currency_symbol').$this->Custom->formatPrice($session->read('EliteReportOrder.priceDetail.total_price'),$session->read('EliteReportOrder.priceDetail.currency_symbol')); /*$this->Custom->removeSpace($session->read('EliteReportOrder.priceDetail.total_price'));*/
                                                        ?></strong>
                                                    </h5>
                                            <?php } else { ?>
                                                <h5 class="">
                                                    <?= __('Total Price');?>: <strong><?php echo $currencySyb.$this->Custom->formatPrice($price,$currencySyb);  /*$this->Custom->removeSpace($price);*/
                                                    ?></strong>
                                                </h5>
                                            <?php } ?>

                                            </div>
                                            
                                            <?php /*echo $this->Html->image('step-1-3.png', ['class' => 'aligncenter size-full wp-image-247 checkout_progress_meter', 'width'=> '1278', 'height' => '74', 'alt'=> '']); ?>
                                            <ul class="software_checkout_progress_labels">
                                                <li class="checkout_current"><?= __('General Info'); ?></li>

                                                <li><?= __('Personal Info'); ?></li>
                                                <li><?= __('Payment Method'); ?></li>
                                            </ul>
                                            <?php */ ?>
                                            <div class="checkoutProgressBox">
                                                <div class="progressCol active">
                                                    <div class="stepNo"><span>1</span></div>
                                                    <div class="stepName"><?= __("General Info"); ?></div>
                                                </div>
                                                <div class="progressCol">
                                                    <div class="stepNo"><span>2</span></div>
                                                    <div class="stepTitle"><?= __("Personal Info"); ?></div>
                                                </div>
                                                <div class="progressCol">
                                                    <div class="stepNo"><span>3</span></div>
                                                    <div class="stepTitle"><?= __("Payment Method"); ?></div>
                                                </div>          
                                            </div>

                                            <hr>
                                        </div> <!-- .et_pb_code -->
                                    </div> <!-- .et_pb_column -->
                                </div> <!-- .et_pb_row -->
                            </div>
                        </div>
                        <div id="checkout_1_wrapper">
                            <h2><?= __('General Information'); ?></h2>
                            <span style="color:red; text-align:center"><?php echo $this->Flash->render();?></span>
                            <label for=""><?= __('First Name'); ?></label>
                            <?php echo $this->Form->text('profile.first_name', [ 'id' => 'firstname', 'tabindex' => '2', 'class' =>'inputLarge validate[required]', 'placeholder' => 'John', 'value' => $fname] )
                            ?>
                            <br>

                            <label for=""><?= __('Last Name'); ?></label>
                            <?php echo $this->Form->text('profile.last_name', [ 'id' => 'last_name', 'tabindex' => '3', 'class' =>'inputLarge validate[required]', 'placeholder' => 'Doe', 'value' => $lname] );
                            ?>
                            <br>

                            <label for=""><?= __('Email'); ?></label>
                            <?php echo $this->Form->text('username', [ 'id' => 'username', 'tabindex' => '4', 'class' =>'inputLarge validate[required, custom[email]]', 'placeholder' => 'youremail@example.com', 'value' => $muyemail ] );
                            ?>
                            <br>
                        </div>
                        <div class="et_pb_section  et_pb_section_2 et_section_regular paddNone">
                            <div class=" et_pb_row et_pb_row_2">
                                <div class="et_pb_column et_pb_column_4_4  et_pb_column_2">
                                    <div class="et_pb_text et_pb_module et_pb_bg_layout_light et_pb_text_align_right checkout_footer et_pb_text_2">
                                        <hr>
                                        <?php 
                                        echo  $this->Form->button(__('Personal Info').' <i class="fa fa-long-arrow-right" aria-hidden="true"></i>', [ 'id' => 'submit', 'class' => 'btn btn-red', 'type' => 'submit' ]);
                                        ?>
                                    </div> <!-- .et_pb_text -->
                                </div> <!-- .et_pb_column -->
                            </div> <!-- .et_pb_row -->
                        </div>
                        <?php echo $this->Form->end(); ?>
                    </div> <!-- .et_pb_code -->
                </div> <!-- .et_pb_column -->
            </div> <!-- .et_pb_row -->
        </div> <!-- .et_pb_section -->
        </div>
    </div> <!-- .entry-content -->
</article> <!-- .et_pb_post -->