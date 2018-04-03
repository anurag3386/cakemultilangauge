<?php use Cake\Cache\Cache; ?>
<?php use Cake\I18n\I18n;?>
<?php $locale = strtolower(I18n::locale()); ?>
<?php 
$order           = $this->request->session()->read('Order');
$price           = $order['price'];
$image           = $order['image'];
$pages           = $order['pages'];
$seo_url         = $order['seo_url'];
$language_id     = $order['language_id'];
$product_name    = $order['product_name'];
$username        = $this->request->session()->read('username');
$last_name       = $this->request->session()->read('last_name');
$first_name      = $this->request->session()->read('first_name');
$age = !empty($this->request->session()->read('Order.age')) ? $this->request->session()->read('Order.age') : '';


//pr($order);
//pr($userData);

$monthArr = array(1 => __('January'), 2 => __('February'), 3 => __('March'), 4 => __('April'), 5 => __('May'), 6 => __('June'), 7 => __('July'), 8 => __('August'), 9 => __('September'), 10 => __('October'), 11 => __('November'), 12 => __('December'));

if(isset($order['gender']) && !empty($order['gender']))
{
$gender          = (isset($order['gender']) && !empty($order['gender']) )     ? $order['gender']     : '';
$dob             = (isset($order['birth_date']) && !empty($order['birth_date']) ) ? $this->Custom->newDateFormat($order['birth_date']) : '';
$cityId          = (isset($order['city_id']) && !empty($order['city_id']) )    ? $order['city_id']    : '';
$countryId       = (isset($order['country_id']) && !empty($order['country_id'])) ? $order['country_id'] : '';
}

elseif(isset($userData) && !empty($userData))
{
$gender          = (isset($order['gender']) && !empty($order['gender']) )     ? $order['gender']     : $userData['profile']['gender'];
$dob             = (isset($order['birth_date']) && !empty($order['birth_date'])  ) ? $order['birth_date'] : $userData['birth_detail']['date'];
$cityId          = (isset($order['city_id']) && !empty($order['city_id'])  )    ? $order['city_id']    : $userData['birth_detail']['city_id'];
$countryId       = (isset($order['country_id']) && !empty($order['country_id']) ) ? $order['country_id'] : $userData['birth_detail']['country_id'];
$dob             = date('d/m/Y',strtotime($dob));
}
else
{
 $gender    = '' ;
 $dob       = '' ;
 $countryId = '' ;
 $cityId    = '' ;
}



$name_on_report  = (isset($order['name_on_report']) && !empty(trim( $order['name_on_report']) ) ) ? $order['name_on_report'] : ($first_name." ".$last_name);

if(isset($order['birth_time']))
{
    $birthTime   = explode(':', $order['birth_time']);
}
elseif(isset($userData['birth_detail']['time']) && !empty($userData['birth_detail']['time']))
{

    $birthTime   = explode(":", $userData['birth_detail']['time']);
}
else
{
     $birthTime = "00:00";
}
$hourValue   = sprintf("%02d", $birthTime[0]) ;
$minuteValue = sprintf("%02d", $birthTime[1]);
?>
<article id="post-256" class="post-256 page type-page status-publish hentry">
 <div class="entry-content">
 <div class="reports_checkout_1">
     <div class="et_pb_section  et_pb_section_0 et_section_regular checkout-pages">
         <div class=" et_pb_row et_pb_row_0">
            <div class="et_pb_column et_pb_column_4_4  et_pb_column_0">
                <div class="et_pb_code et_pb_module  et_pb_code_0">
                    <div class="et_pb_section checkout_header et_pb_section_0 et_section_regular top-section">
                        <div class="et_pb_text et_pb_module et_pb_bg_layout_light et_pb_text_align_left  et_pb_text_0">
                          <div class="checkoutLeft">
                 <?php 
                   $product_id  = $order['product_id'];
                   $translate = $this->Custom->getTranslation('Products',['Products.id' => $product_id], ['image', 'name'] );
               ?>
                <h3 class="checkout_title"><?php echo ucwords($translate['name']) ;?></h3>
                            
                            <cite><?= __('Approx');?> <?php echo $pages?> <?= __('pages');?></cite>
                            
                            <p><cite><?= __('Deliver through Email');?></cite></p>

               <?php 
                            echo $this->Html->image("/uploads/products/".$translate['image'], [ 'class' => 'size-thumbnail wp-image-164', 'alt'=> $translate['name'] ])
                            ?>    

                           
                            </div>
                                            <div class="total_price_section">
                                              <?php if($finalPrice['vat'] > 0)
                                                    {//changed by anurag dubey 13-nov-2017
                                                        ?>
                                              <h3 class="checkout_price">
                                                <?= __('Price');?>: <?= $finalPrice['currency_symbol'].$this->Custom->formatPrice($finalPrice['price'],$finalPrice['currency_symbol']); ?>
                                                </h3>
                                                <h4 class="">
                                                <?= __('VAT').': '.$finalPrice['currency_symbol'].$this->Custom->formatPrice($finalPrice['vat'],$finalPrice['currency_symbol']);
                                                ?></h4>
                                                <?php
                                                    }
                                                ?>
                                                <h5 class="">
                                                <?= __('Total Price');?>: <strong><?php echo $finalPrice['currency_symbol'].$this->Custom->formatPrice($finalPrice['total_price'],$finalPrice['currency_symbol']);?></strong
                                                >
                                                </h5>
                                            </div>

                            <p>
                            <div class="checkoutProgressBox">
                            <div class="progressCol complete">
                            <div class="stepNo"><span>1</span></div>
                            <div class="stepName"><?= __('General Info');?></div>
                            </div>
                            <div class="progressCol active">
                            <div class="stepNo"><span>2</span></div>
                            <div class="stepName"><?= __('Personal Info');?></div>
                            </div>  
                            <div class="progressCol">
                            <div class="stepNo"><span>3</span></div>
                            <div class="stepTitle"><?= __('Payment Method');?></div>
                            </div>          
                            </div>


                        </p>
<hr>
</div> <!-- .et_pb_text -->
</div>

<div id="checkout_2_wrapper">

    <div class="text_center" id="flash_error"><?php echo $this->Flash->render();?></div>
    <?php echo $this->Form->create($form,  ['id' => 'step-2', 'class' => 'checkout_form' ])?>
    <label for="name_on_report"><?= __('Name on Report');?></label>
    <?php echo $this->Form->text('name_on_report', [ 'id' => 'name_on_report', 'tabindex' => '2', 'class' =>'input-sm validate[required]', 'placeholder' => 'John', 'readonly' =>'readonly', 'maxlength' => 60 , 'default' => $name_on_report ] );
    ?>
    <br>
    <br>
    <?php echo $this->Form->hidden('username', [ 'value'=> $username ]) ?>
    <?php echo $this->Form->hidden('profile.first_name', [ 'value'=> $first_name ]) ?>
    <?php echo $this->Form->hidden('profile.last_name', [ 'value'=> $last_name ]) ?>
    <?php echo $this->Form->hidden('profile.language_id', [ 'value'=> $language_id ]) ?>
    <label for=""><?= __('Gender');?></label>
    <?php
    /*if($locale == 'en_us')
    {*/
      $options = ['M' => __('Male'), 'F' => __('Female')];
    /*}
    else
    {
     $options = ['M' => 'Han', 'F' => 'Kvinde'];   
    }*/
    echo $this->Form->select('gender', $options, ['class' => 'validate[required]', 'empty' => __('Select Gender'), 'default' => $gender] ); 
    ?>
    <br>
    <label for="birth_day"><?= __('Date of Birth');?></label>
    <?php 
    echo $this->Form->text('birth_date', ['class' => 'validate[required]', 'id' => 'report-datepicker' , 'autocomplete' => 'off', 'value' => $dob , 'readonly' => 'readonly']);     ?>
    <?php //if( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' || $_SERVER['REMOTE_ADDR'] == '103.248.117.10' ) {
        if ($product_id == 19) { ?>
    <br>
    <label for="age"><?php echo __('Choose Age for the report');?></label>
    <?php
    $defaultSelected = 'Age';
    if ($age) {
        $defaultSelected = $age;
    }

    $ageOptions = array();
    for ($i=13; $i <= 100; $i++) {
        $ageOptions[$i] = $i;
    }
    echo $this->Form->select('age', $ageOptions, ['id' => 'age' , 'class' => 'validate[required] age' , 'empty' => __('Choose Age for the report'), 'default' => $defaultSelected/*, 'style' => ['width:47%']*/]); } //} ?>

    <div class="userAge"></div>

    <br>
    <label for="hours"><?= __('Birth Time');?></label>
    <?php   $hourOtions = [
    '-1' => __('Unknown Time'),
    '00' => '00 a.m. (00.00)',                '01' => '01 a.m. (01.00)',
    '02' => '02 a.m. (02.00)',                '03' => '03 a.m. (03.00)',
    '04' => '04 a.m. (04.00)',                '05' => '05 a.m. (05.00)',
    '06' => '06 a.m. (06.00)',                '07' => '07 a.m. (07.00)',
    '08' => '08 a.m. (08.00)',                '09' => '09 a.m. (09.00)',
    '10' => '10 a.m. (10.00)',                '11' => '11 a.m. (11.00)',
    '12' => '12 p.m. (12.00)',                '13' => '01 p.m. (13.00)',
    '14' => '02 p.m. (14.00)',                '15' => '03 p.m. (15.00)',
    '16' => '04 p.m. (16.00)',                '17' => '05 p.m. (17.00)',
    '18' => '06 p.m. (18.00)',                '19' => '07 p.m. (19.00)',
    '20' => '08 p.m. (20.00)',                '21' => '09 p.m. (21.00)',
    '22' => '10 p.m. (22.00)',                '23' => '11 p.m. (23.00)'

    ]; ?>
    <?php   echo $this->Form->select('hours', $hourOtions, ['id' => 'hours' , 'class' => 'validate[required] hour' , 'empty' => __('Hour'), 'default' => $hourValue, 'style' => ['width:47%']]); ?>
    <?php $minOptions=[
    '-1' => __('Unknown Time'),
    '00' => '00',     '01' => '01',     '02' => '02',     '03' => '03',     '04' => '04',     '05' => '05',
    '06' => '06',     '07' => '07',     '08' => '08',     '09' => '09',     '10' => '10',     '11' => '11',
    '12' => '12',     '13' => '13',     '14' => '14',     '15' => '15',     '16' => '16',     '17' => '17',
    '18' => '18',     '19' => '19',     '20' => '20',     '21' => '21',     '22' => '22',     '23' => '23',
    '24' => '24',     '25' => '25',     '26' => '26',     '27' => '27',     '28' => '28',     '29' => '29',
    '30' => '30',     '31' => '31',     '32' => '32',     '33' => '33',     '34' => '34',     '35' => '35',
    '36' => '36',     '37' => '37',     '38' => '38',     '39' => '39',     '40' => '40',     '41' => '41',
    '42' => '42',     '43' => '43',     '44' => '44',     '45' => '45',     '46' => '46',     '47' => '47',
    '48' => '48',     '49' => '49',     '50' => '50',     '51' => '51',     '52' => '52',     '53' => '53',
    '54' => '54',     '55' => '55',     '56' => '56',     '57' => '57',     '58' => '58',     '59' => '59'

    ] ;?>
    <?php echo $this->Form->select('minutes' , $minOptions , ['id' => 'minutes' , 'class' => ' validate[required] minute' , 'empty' => __('Minute'), 'default' => $minuteValue, 'style' => ['width:47%; margin-left:5%']])?>

    <br>
        
        <label for="country"><?= __('US State / Country of Birth');?></label>
        <?php echo $this->Form->select('birth_detail.country_id', $countryOptions, ['id' => 'country',  'class' => 'validate[required]', 'empty' => __('Select Country'), 'default' => $countryId, 'onChange' => "hideExtraFields('suggesstion-box', 'search-box')" ] ); ?>
        <br>
        <?php 
          if(!empty($cityId))
          {
            $cityInfo = $this->Custom->getCityInfo($cityId);
          }
          else
          {
            $cityInfo = "";
          }
      ?>
        <label for="city"><?= __('Birth City');?></label>

        <?php echo $this->Form->text( 'city', ['id' => 'search-box',  'autocomplete' => 'off', 'class' => ' validate[required] ui-autocomplete-input', 'value' => $cityInfo, 'maxlength' => 100])?>
        <div id="loading" style="display:none; text-align:center">
            <?php echo $this->Html->image('calendar-loading.gif', ['alt' => 'Loading']);?>
        </div>
        <div id="suggesstion-box">
        </div>
        <br>
        <?php echo $this->Form->hidden('birth_detail.city_id', ['id' => 'city_id', 'value' => $cityId]);  ?>
        <br>
        <p id="bottom-text"><cite><?= __('(Enter first few letters of birth town and click on town in list)');?></cite></p>
        <br>
        <br>

    </div>
    <div class="et_pb_section checkout_header et_pb_section_0 et_section_regular">
        <div class="et_pb_text et_pb_module et_pb_bg_layout_light et_pb_text_align_right checkout_footer et_pb_text_2 footer-center">
            <hr>
            <?php 
            echo  $this->Html->link('<i class="fa fa-long-arrow-left" aria-hidden="true"></i>'.__(' Back'),['controller' => 'Orders', 'action' => 'checkout-step-1'] ,[  'class' => 'btn btn-red check_back', 'escape' => false ]);
            ?>

            <?php 
            echo  $this->Form->button(__('Payment Method'). ' <i class="fa fa-long-arrow-right" aria-hidden="true"></i>', [ 'id' => 'submit', 'class' => 'btn btn-red', 'type' => 'submit' ]);
            ?>
            <?php echo $this->Form->end();?> 
        </div>
    </div>
</div> <!-- .et_pb_code -->
</div> <!-- .et_pb_column -->
</div> <!-- .et_pb_row -->
</div> <!-- .et_pb_section -->
</div>
</div> <!-- .entry-content -->
</article> <!-- .et_pb_post -->
<script>
    $(document).ready(function(){
     $('#hours').change(function(){
         var hours = $('#hours').val();
         if(hours == -1){
           $('#minutes').val('-1');
       }
   })
    $("#search-box").keyup(function(event){
        $('#flash_error').hide();
        event.preventDefault();
        getCities('country', 'search-box', 'suggesstion-box', 'city_id', "<?php echo $this->Url->build(['controller' => 'Users', 'action' => 'cities' ])?>");

    });

    /*
     * To show essential report message according to age and dob
     * Created By : Kingslay 
     * Created Date : May 05, 2017 
     */
    $('#age').on('change', function() {
        var report_data = $("#report-datepicker").val();
        if(report_data!=''){
            showData ();
        }
    });

    $('#report-datepicker').on('change', function() {
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
        var dob = $('#report-datepicker').val();
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