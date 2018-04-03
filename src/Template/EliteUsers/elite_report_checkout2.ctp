<?php use Cake\Cache\Cache; ?>
<?php
    $session = $this->request->session();
    $form = 'EliteMemberReportDetailsForUser';
    $order = $session->read('EliteReportOrder');

    $seo_url = (isset($order['ProductDetail']['seo_url']) && !empty($order['ProductDetail']['seo_url'])) ? $order['ProductDetail']['seo_url'] : '';
    $product_name = $session->read('EliteReportOrder.ProductDetail.name');
    $pages = $session->read('EliteReportOrder.ProductDetail.pages');
    $image = $session->read('EliteReportOrder.ProductDetail.image');
    $price = trim($session->read('EliteReportOrder.price'));
    $product_id = trim($session->read('EliteReportOrder.product_id'));

    $currencySyb = $session->read('EliteReportOrder.currencySymbol');
    $email = $session->read('EliteReportOrder.UserDetail.email');
    $first_name = $session->read('EliteReportOrder.UserDetail.first_name');
    $last_name = $session->read('EliteReportOrder.UserDetail.last_name');
    $name_on_report = ucwords($first_name.' '.$last_name);

    /* Filled data */
    $selectedGender = !empty($session->read('EliteReportOrder.BirthData.gender')) ? $session->read('EliteReportOrder.BirthData.gender') : '';
    $selectedDOB = !empty($session->read('EliteReportOrder.BirthData.birth_date')) ? $session->read('EliteReportOrder.BirthData.birth_date') : '';
    $selectedHours = !empty($session->read('EliteReportOrder.BirthData.hours')) ? $session->read('EliteReportOrder.BirthData.hours') : '';
    $selectedMinutes = !empty($session->read('EliteReportOrder.BirthData.minutes')) ? $session->read('EliteReportOrder.BirthData.minutes') : '';
    $selectedCountry = !empty($session->read('EliteReportOrder.BirthData.country_id')) ? $session->read('EliteReportOrder.BirthData.country_id') : '';
    $selectedCity = !empty($session->read('EliteReportOrder.BirthData.city')) ? $session->read('EliteReportOrder.BirthData.city') : '';
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
                        


                    <?= $this->Flash->render(); ?>
                    <?php echo $this->Form->create($form,  ['id' => 'step-2', 'class' => 'checkout_form', 'url' => [ 'controller' => 'elite-users', 'action' => 'elite-customer-checkout-step2'] ])?>
                        <div class="common_checkout_first">
                            <div class="et_pb_section checkout_header et_pb_section_0 et_section_regular">
                                <div class="et_pb_text et_pb_module et_pb_bg_layout_light et_pb_text_align_left  et_pb_text_0">
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
                                            

                                    <div class="checkoutProgressBox">
                                        <div class="progressCol complete">
                                            <div class="stepNo"><span>1</span></div>
                                            <div class="stepName"><?= __("General Info"); ?></div>
                                        </div>
                                        <div class="progressCol active">
                                            <div class="stepNo"><span>2</span></div>
                                            <div class="stepTitle"><?= __("Personal Info"); ?></div>
                                        </div>
                                        <div class="progressCol">
                                            <div class="stepNo"><span>3</span></div>
                                            <div class="stepTitle"><?= __("Payment Method"); ?></div>
                                        </div>          
                                    </div>
                                    <p></p><hr>
                                </div> <!-- .et_pb_text -->
                            </div>
                        </div>

                        <div id="checkout_2_wrapper">
                            <label for="name_on_report"><?= __('Name on Report'); ?></label>
                            <?php echo $this->Form->text('name_on_report', [ 'id' => 'name_on_report', 'tabindex' => '2', 'class' =>'input-sm validate[required]', 'placeholder' => 'John' , 'default' => $name_on_report, 'disabled' => 'disabled', 'style' => 'background-color: #eee', 'value' => $name_on_report ] ); ?>
                            <br><br>
                            <?php echo $this->Form->hidden('email', [ 'value'=> $email ]) ?>
                            <?php echo $this->Form->hidden('first_name', [ 'value'=> $first_name ]) ?>
                            <?php echo $this->Form->hidden('last_name', [ 'value'=> $last_name ]) ?>
                            <label for=""><?= __('Gender'); ?></label>
                            <?php $options = ['M' => __('Male'), 'F' => __('Female')];
                                echo $this->Form->select('gender', $options, ['class' => 'validate[required]', 'empty' => __('Select Gender'), 'default' => $selectedGender] ); ?>
                            <br>
                            <label for="birth_day"><?= __('Date of Birth'); ?></label>
                            <?php echo $this->Form->text('birth_date', ['class' => 'validate[required]', 'id' => 'datepicker' , 'autocomplete' => 'off', 'readonly' => 'readonly', 'placeholder' => 'Date of Birth', 'value' => $selectedDOB]); ?>
                            <br>
                            <?php //if( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' || $_SERVER['REMOTE_ADDR'] == '103.248.117.10' ) {
                                if ($product_id == 19) { ?>
                                    <label for="age"><?= __('Choose Age for the report');?></label>
                                    <?php
                                        $ageOptions = array();
                                        for ($i=13; $i <= 100; $i++) {
                                            $ageOptions[$i] = $i;
                                        }
                                        echo $this->Form->select('age', $ageOptions, ['id' => 'age' , 'class' => 'validate[required] age' , 'empty' => __('Choose Age for the report'), 'default' => 'Age'/*, 'style' => ['width:47%']*/]);
                                }
                            //} ?>
                            <div class="userAge"></div>
                            <br>
                            <label for="hours"><?= __('Time of Birth'); ?></label>
                            <?php   $hourOtions = [ '-1' => __('Unknown Time'), '00' => '00 a.m. (00.00)', '01' => '01 a.m. (01.00)', '02' => '02 a.m. (02.00)', '03' => '03 a.m. (03.00)', '04' => '04 a.m. (04.00)', '05' => '05 a.m. (05.00)', '06' => '06 a.m. (06.00)', '07' => '07 a.m. (07.00)', '08' => '08 a.m. (08.00)', '09' => '09 a.m. (09.00)', '10' => '10 a.m. (10.00)', '11' => '11 a.m. (11.00)', '12' => '12 p.m. (12.00)', '13' => '01 p.m. (13.00)', '14' => '02 p.m. (14.00)', '15' => '03 p.m. (15.00)', '16' => '04 p.m. (16.00)', '17' => '05 p.m. (17.00)', '18' => '06 p.m. (18.00)', '19' => '07 p.m. (19.00)', '20' => '08 p.m. (20.00)', '21' => '09 p.m. (21.00)', '22' => '10 p.m. (22.00)', '23' => '11 p.m. (23.00)' ]; ?>
                            <?php echo $this->Form->select('hours', $hourOtions, ['id' => 'hours' , 'class' => 'validate[required] hour' , 'empty' => __('Hour'), 'value' => $selectedHours, 'style' => ['width:47%'] ]); ?>
                            <?php $minOptions=[ '-1' => __('Unknown Time'), '00' => '00', '01' => '01', '02' => '02', '03' => '03', '04' => '04', '05' => '05', '06' => '06', '07' => '07', '08' => '08', '09' => '09', '10' => '10', '11' => '11', '12' => '12', '13' => '13', '14' => '14', '15' => '15', '16' => '16', '17' => '17', '18' => '18', '19' => '19', '20' => '20', '21' => '21', '22' => '22', '23' => '23', '24' => '24', '25' => '25', '26' => '26', '27' => '27', '28' => '28', '29' => '29', '30' => '30', '31' => '31', '32' => '32', '33' => '33', '34' => '34', '35' => '35', '36' => '36', '37' => '37', '38' => '38', '39' => '39', '40' => '40', '41' => '41', '42' => '42', '43' => '43', '44' => '44', '45' => '45', '46' => '46', '47' => '47', '48' => '48', '49' => '49', '50' => '50', '51' => '51', '52' => '52', '53' => '53', '54' => '54', '55' => '55', '56' => '56', '57' => '57', '58' => '58', '59' => '59' ] ;?>
                            <?php echo $this->Form->select('minutes' , $minOptions , ['id' => 'minutes' , 'class' => ' validate[required] minute' , 'empty' => __('Minute'), 'value' => $selectedMinutes , 'style' => ['width:47%; margin-left:5%']])?>
                            <br>
                            <label for="country"><?= __('US State / Country of Birth'); ?></label>
                            <?php echo $this->Form->select('country_id', $countryOptions, ['id' => 'country',  'class' => 'validate[required]', 'empty' => __('Select Country'), 'value' => $selectedCountry, 'onChange' => "hideExtraFields('suggesstion-box', 'search-box')"  ] ); ?>
                            <br>
                            <label for="city"><?= __('Birth City'); ?></label>
                            <?php echo $this->Form->text( 'city', ['id' => 'search-box', 'placeholder' => __('Enter City'), 'autocomplete' => 'off', 'class' => ' validate[required] ui-autocomplete-input', 'value' => $selectedCity])?>
                            <div id="loading" style="display:none; text-align: center;">
                                <?php echo $this->Html->image('calendar-loading.gif', ['alt' => 'Loading']);?>
                            </div>
                            <div id="suggesstion-box"></div>
                            <br>
                            <?php echo $this->Form->hidden('city_id', ['id' => 'city_id']);  ?>
                            <br>
                            <p><cite><?= __('(Enter first few letters of birth town and click on town in list)'); ?></cite></p>
                        </div>
                        <div class="et_pb_section checkout_header et_pb_section_0 et_section_regular">
                            <div class="et_pb_text et_pb_module et_pb_bg_layout_light et_pb_text_align_right checkout_footer et_pb_text_2"> <hr>
                                <?php echo $this->Html->link('<i class="fa fa-long-arrow-left" aria-hidden="true"></i> '.__('Back'),['controller' => 'elite-users', 'action' => 'elite-report-checkout'] ,[  'class' => 'btn btn-red check_back' , 'escape' => false]);
                                    echo $this->Form->button(__('Payment Method').' <i class="fa fa-long-arrow-right" aria-hidden="true"></i>', [ 'id' => 'submit', 'class' => 'btn btn-red', 'value' => 'Go to payment', 'type' => 'submit']); ?>
                            </div>
                        </div>
                    <?php echo $this->Form->end();?>





                    </div> <!-- .et_pb_code -->
                </div> <!-- .et_pb_column -->
            </div> <!-- .et_pb_row -->
        </div> <!-- .et_pb_section -->
        </div> <!-- .et_pb_section -->
    </div> <!-- .entry-content -->
</article> <!-- .et_pb_post -->

<script>
    $(document).ready(function(){
        $('#hours').change( function () {
            var hours = $('#hours').val();
            if(hours == -1){
                $('#minutes').val('-1');
            }
        });

        $("#search-box").keyup( function (event) {
            event.preventDefault();
            $('.error').hide();
            getCities('country', 'search-box', 'suggesstion-box', 'city_id', "<?php echo $this->Url->build(['controller' => 'Users', 'action' => 'cities' ])?>");
        });



    /*
     * To show essential report message according to age and dob
     * Created By : Kingslay 
     * Created Date : May 06, 2017 
     */
    $('#age').on('change', function() {
        var report_data = $("#datepicker").val();
        if(report_data!=''){
            showData ();
        }
    });

    $('#datepicker').on('change', function() {
        var product_id = "<?= $product_id; ?>";
        if (product_id == 19) {
            showData ();
        }
    });

    function showData () {
        var age = $('#age').val();
        if (age == '') {
            return false;
        }
        var dob = $('#datepicker').val();
        var fulldob = dob.split('/');
        var endYear1 = (parseInt(fulldob[2]) + parseInt(age));
        var endYear2 = endYear1 + 1;
        var day = fulldob[0];
        var month = fulldob[1];
        var startdate = day+'-'+month+'-'+endYear1;
        var enddate = day+'-'+month+'-'+endYear2;
        $('.userAge').html('Report Start from '+startdate+' to '+enddate);
    }
    //END


   });
</script>