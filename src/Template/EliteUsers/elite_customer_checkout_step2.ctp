<?php use Cake\Routing\Router; ?>
<?php use Cake\Cache\Cache; ?>
<?php use Cake\I18n\I18n;?>
<?php
    $locale = strtolower( substr(I18n::locale() , 0, 3) );
    $session = $this->request->session();
    //pr ($session->read()); die;
    $amount = $price;
    if (!empty($session->read('Elite.Elite Currency Code'))) {
        $currency = $session->read('Elite.Elite Currency Code');
        $sign = $session->read('Elite.Elite Currency Sign');
        $path = "elite-users/thank-you?utm_nooverride=1";
        $cancelpath = 'users/dashboard';

        if($locale == 'da') {
            $cancelpath = 'dk/brugere/instrumentbræt';
            $path = "dk/elite-brugere/tak?utm_nooverride=1";
        }
        $vat = !empty($session->read('Elite.priceDetail.vat')) ? $session->read('Elite.priceDetail.vat') : 0;

    } else {
        $currency = $this->Custom->getCurrencyDetail(trim($session->read('EliteReportOrder.currency_id')));
        $amount = trim($this->request->session()->read('EliteReportOrder.price'));
        $currency = $currency['code'];
        $sign = $session->read('EliteReportOrder.currencySymbol');
        $path = "elite-users/thank-you-for-report-purchase?utm_nooverride=1";
        $cancelpath = 'elite-users/dashboard';

        if( $locale == 'da' ) {
            $cancelpath = 'dk/elite-brugere/instrumentbræt';
            $path = "dk/elite-brugere/tak-for-rapport-køb?utm_nooverride=1";
        }
        $vat = !empty($session->read('EliteReportOrder.priceDetail.vat')) ? $session->read('EliteReportOrder.priceDetail.vat') : 0;

        /*if ($_SERVER[REMOTE_ADDR] == '103.254.97.14' || $_SERVER[REMOTE_ADDR] == '103.248.117.12') {
            $amount = 0.10;
            $currency = 'USD';
        }*/

    }
    /*if ($_SERVER[REMOTE_ADDR] == '103.254.97.14' || $_SERVER[REMOTE_ADDR] == '103.248.117.12') {
        echo $this->request->session()->read('locale'); die;
    }*/

    /*$step = 2;
    if (strpos($session->read('previousUrl'), 'elite-report-checkout2') !== false) {
        $step = 3;
    }*//* else {
        $step = 2;
    }*/

?>
<article id="post-1159" class="post-1159 page type-page status-publish hentry">            
    <div class="entry-content">
        <!-- <div class="et_pb_section  et_pb_section_0 et_section_regular"> -->
        <div class="custom_report_checkout_class">
        <div class="et_pb_section  et_pb_section_0 et_section_regular">
            <div class=" et_pb_row et_pb_row_0">
                <div class="et_pb_column et_pb_column_4_4  et_pb_column_0">
                    <div class="et_pb_code et_pb_module  et_pb_code_0">
                        <?php
                            $price = $amount;
                            $price = $this->Custom->getEpayPriceFormat($amount);
                            $base_path = Router::url('/', true);
                            $cancel = $base_path.$cancelpath; // "elite-users/dashboard";
                            $acceptUrl = $base_path.$path;
                            $callback = $base_path.$path;
                            

                            $currency = $currency;
                            $order_id = $order_id;

                            $params = array('merchantnumber' => MERCHANT_ID, 'amount' => $price, 'currency' => $currency, 'instantcallback' => 1, 'orderid' => $order_id, 'ownreceipt' => 1, 'windowstate'=>2, 'accepturl'=>$acceptUrl, 'cancelurl'=> $cancel, 'iframeheight' => 580);
                        ?>

                            <div class="et_pb_section checkout_header et_pb_section_0 et_section_regular">
                                <div class=" et_pb_row et_pb_row_0">
                                    <div class="et_pb_column et_pb_column_4_4  et_pb_column_0">
                                        <div class="et_pb_code et_pb_module  et_pb_code_0 additional_div">
                                            <?php if (!empty($session->read('Elite.Elite Currency Code'))) { //if ($step == 2) { ?>
                                                <div class="checkoutLeft">
                                                    <h3 class="checkout_title"><?= __('Elite Customer');?></h3>
                                                </div>
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
                                            

                                                <div class="two_steps">
                                                    <div class="checkoutProgressBox">
                                                        <div class="progressCol complete">
                                                            <div class="stepNo"><span>1</span></div>
                                                            <div class="stepName"><?= __("General Info"); ?></div>
                                                        </div>
                                                        <div class="progressCol active">
                                                            <div class="stepNo"><span>2</span></div>
                                                            <div class="stepName"><?= __("Payment Method"); ?></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } else {
                                                //if ($step == 3) {
                                                $pages = $this->request->session()->read('EliteReportOrder.ProductDetail.pages');
                                                $product_name = $this->request->session()->read('EliteReportOrder.ProductDetail.name'); ?>

                                                <h3 class="checkout_title"><?php echo ucwords($product_name)?></h3>
                                                <cite><?php echo __('Approx').' '.$pages.' '.__('pages'); ?></cite>
                                                <p><cite><?= __('Deliver through Email'); ?></cite></p>
                                            <?php 
                                                $image = $this->request->session()->read('EliteReportOrder.ProductDetail.image');
                                                echo $this->Html->image("/uploads/products/".$image, [ 'class' => 'size-thumbnail wp-image-164', 'alt'=>'' ]); ?>
                                                <?php /* <h3 class="checkout_price">
                                                    <?= __('Total Price'); ?>: <strong><?php echo $sign.$this->Custom->removeSpace($amount);?></strong>
                                                </h3> */ ?>

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
                                            

                                                <div class="checkoutProgressBox">
                                                    <div class="progressCol complete">
                                                        <div class="stepNo"><span>1</span></div>
                                                        <div class="stepName"><?= __("General Info"); ?></div>
                                                    </div>
                                                    <div class="progressCol complete step3">
                                                        <div class="stepNo"><span>2</span></div>
                                                        <div class="stepTitle"><?= __("Personal Info"); ?></div>
                                                    </div>
                                                    <div class="progressCol active">
                                                        <div class="stepNo"><span>3</span></div>
                                                        <div class="stepTitle"><?= __("Payment Method"); ?></div>
                                                    </div>          
                                                </div>
                                            <?php } //} ?>

                                            <br class="clearfix"><br>
                                            <hr>
                                        </div> <!-- .et_pb_code -->
                                    </div> <!-- .et_pb_column -->
                                </div> <!-- .et_pb_row -->
                            </div>

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
                                    <?php
                                    if (!empty($session->read('Elite.Elite Currency Code'))) {
                                    //if ($step == 2) {
                                        echo $this->Html->link('<i class="fa fa-long-arrow-left" aria-hidden="true"></i> '.__('Back'),['controller' => 'elite-users', 'action' => 'elite-customer-checkout'] ,[  'class' => 'btn btn-red check_back' , 'escape' => false]);
                                    } else {
                                        echo $this->Html->link('<i class="fa fa-long-arrow-left" aria-hidden="true"></i> '.__('Back'),['controller' => 'elite-users', 'action' => 'elite-report-checkout2'] ,[  'class' => 'btn btn-red check_back' , 'escape' => false]);
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div> <!-- .et_pb_code -->
                </div> <!-- .et_pb_column -->
            </div> <!-- .et_pb_row -->
		</div>
		</div>
    </div>
</article>

<style type="text/css">
    #epay_testmode_message { line-height: 10px !important; }
</style>