<?php use Cake\Cache\Cache; ?>
<?php use Cake\I18n\I18n;?>
<?php $locale = strtolower(I18n::locale()); ?>
<?php 
        $order = $this->request->session()->read('Order');
        $loversData = $this->request->session()->read('loversData');
 
        $person1 = $this->Custom->setLoversData($loversData['person_1_birth_date'], $loversData['person_1_hours'], $loversData['person_1_minutes'], $loversData['person_1_country_id'], $loversData['person_1_first_name'], $loversData['person_1_last_name'], $loversData['person_1_gender'], $loversData['person_1_city'], $loversData['person_1_city_id']);

        $person2 = $this->Custom->setLoversData($loversData['person_2_birth_date'], $loversData['person_2_hours'], $loversData['person_2_minutes'], $loversData['person_2_country_id'], $loversData['person_2_first_name'], $loversData['person_2_last_name'], $loversData['person_2_gender'], $loversData['person_2_city'], $loversData['person_2_city_id']);

        $price = $order['price'];
        $image = $order['image'];
        $pages = $order['pages'];
        $product_name  = $order['product_name'];
        $language_id   = $order['language_id'];
        $seo_url       = $order['seo_url'];
        $product_id    = $order['product_id'];
        $username      = $this->request->session()->read('username');
        $translate     = $this->Custom->getTranslation('Products',['Products.id' => $product_id], ['image', 'name'] );
?>
<article id="post-256" class="post-256 page type-page status-publish hentry">
   <div class="entry-content">
       <div class="et_pb_section  et_pb_section_0 et_section_regular checkout-pages">
           <div class=" et_pb_row et_pb_row_0">

            <div class="et_pb_column et_pb_column_4_4  et_pb_column_0">
                <div class="et_pb_code et_pb_module  et_pb_code_0">
                    <div class="et_pb_section checkout_header et_pb_section_0 et_section_regular">
                        <div class="et_pb_text et_pb_module et_pb_bg_layout_light et_pb_text_align_left  et_pb_text_0">
                         <div class="checkoutLeft">
                            <h3 class="checkout_title"><?php echo  ucwords($translate['name']) ;?></h3>
                            <cite><?= __('Approx');?> <?php echo $pages?> <?= __('pages');?></cite>
                            
                            <?php echo $this->Html->image("/uploads/products/".$translate['image'], [ 'class' => 'size-thumbnail wp-image-164', 'alt'=> $translate['name'] ])?>    

                           
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
                        <hr>
                    </div> <!-- .et_pb_text -->
                </div>
                <div id="checkout_2_wrapper" class="lovers-form-header">
                <div class="text_center" id="flash_error"><?php echo $this->Flash->render();?></div>
                   <?php echo $this->Form->create($form,  ['id' => 'step-2', 'class' => 'checkout_form' ])?>
                    <div class="lovers_form">
                    <h3><center><strong><?= __('Person 1');?></strong></center></h3>
                    <label for="first_name"><?= __('First Name');?></label>
                    <?php echo $this->Form->text('person_1_first_name', [ 'id' => 'person_1_first_name', 'tabindex' => '2', 'class' =>'input-sm validate[required]', 'placeholder' => 'John' , 'maxlength' => 60, 'default' => $person1['first_name'] ]);
                    ?>
                    <label for="last_name"><?= __('Last Name');?></label>
                    <?php echo $this->Form->text('person_1_last_name', [ 'id' => 'person_1_last_name', 'tabindex' => '3', 'class' =>'input-sm validate[required]', 'placeholder' => 'Doe' , 'maxlength' => 60 , 'default' => $person1['last_name'] ] );
                    ?>
                    <br>
                    <br>

                    <?php echo $this->Form->hidden('username', [ 'value'=> $username ]) ?>
                    <?php echo $this->Form->hidden('profile.language_id', [ 'value'=> $language_id ]) ?>


                    <label for=""><?= __('Gender');?></label>

                    <?php
                        // if($locale == 'en_us')
                        // {
                        //   $options = ['M' => 'Male', 'F' => 'Female'];
                        // }
                        // else
                        // {
                        //  $options = ['M' => 'Han', 'F' => 'Kvinde'];   
                        // }

                      $options = ['M' => __('Male'), 'F' => __('Female')];

                    echo $this->Form->select('person_1_gender', $options, ['class' => 'validate[required]down', 'empty' => __('Select Gender'),  'tabindex' => '4', 'default' => $person1['gender'] ] ); 

                    ?>


                    <br>

                    <label for="birth_day"><?= __('Date of Birth');?></label>
                    <?php 

                    echo $this->Form->text('person_1_birth_date', ['class' => 'validate[required]', 'id' => 'person-1' , 'autocomplete' => 'off' ,  'tabindex' => '5', 'readonly' => 'readonly', 'default' => $person1['birth_date']] ); 


                    ?>

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
                    <?php   echo $this->Form->select('person_1_hours', $hourOtions, ['id' => 'person_1_hours' , 'class' => 'validate[required] hour down' , 'empty' => __('Hour') ,  'tabindex' => '6', 'style' => ['width:47%'], 'default' => $person1['hours'] ]); ?>


                    <?php $minOptions=[
                    '-1' => 'Unknown Time',
                    '00' => '00', '01' => '01', '02' => '02', '03' => '03', '04' => '04', '05' => '05',
                    '06' => '06', '07' => '07', '08' => '08', '09' => '09', '10' => '10', '11' => '11',
                    '12' => '12', '13' => '13', '14' => '14', '15' => '15', '16' => '16', '17' => '17',
                    '18' => '18', '19' => '19', '20' => '20', '21' => '21', '22' => '22', '23' => '23',
                    '24' => '24', '25' => '25', '26' => '26', '27' => '27', '28' => '28', '29' => '29',
                    '30' => '30', '31' => '31', '32' => '32', '33' => '33', '34' => '34', '35' => '35',
                    '36' => '36', '37' => '37', '38' => '38', '39' => '39', '40' => '40', '41' => '41', 
                    '42' => '42', '43' => '43', '44' => '44', '45' => '45', '46' => '46', '47' => '47',
                    '48' => '48', '49' => '49', '50' => '50', '51' => '51', '52' => '52', '53' => '53',  
                    '54' => '54', '55' => '55', '56' => '56', '57' => '57', '58' => '58', '59' => '59'

                    ] ;?>
                    <?php echo $this->Form->select('person_1_minutes' , $minOptions , ['id' => 'person_1_minutes' , 'class' => ' validate[required] minute down' , 'empty' => __('Minute') ,  'tabindex' => '7', 'style' => ['width:46% ; margin-left:5%'], 'default' => $person1['minutes'] ])?>

                    <br>

        
                    <label for="country"><?= __('US State / Country of Birth');?></label>
                    <?php echo $this->Form->select('person_1_country_id', $countryOptions, ['id' => 'country-1',  'class' => 'validate[required] down', 'empty' => 'Select Country' ,  'tabindex' => '8', 'default' => $person1['country_id'], 'onChange'=>'hideExtraFields(\'suggesstion-box-1\', \'search-box-1\')'  ] ); ?>
                    <br>
                    <label for="city"><?= __('Birth City');?></label>
                    <?php echo $this->Form->text( 'person_1_city', ['id' => 'search-box-1', 'placeholder' => 'Enter City', 'autocomplete' => 'off', 'class' => ' validate[required] ui-autocomplete-input' ,  'tabindex' => '9', 'default' => $person1['city'] ])?>

                    <div id="loading-1" style="display:none">
                    <?php echo $this->Html->image('calendar-loading.gif', ['alt' => 'Loading']);?>
                    </div>

                    <div id="suggesstion-box-1">
                    </div>
                    <br>
                    <?php echo $this->Form->hidden('person_1_city_id', ['id' => 'city_id_1', 'default' => $person1['city_id']]);  ?>
                    <br>
                    <p><cite><?= __('(Enter first few letters of birth town and click on town in list)');?></cite></p>

</div>
<!-- person 2 starts here -->
                <div class="lovers_form">
                   <h3><center><strong><?= __('Person 2');?></strong></center></h3>


                    <label for="first_name"><?= __('First Name');?></label>
                    <?php echo $this->Form->text('person_2_first_name', [ 'id' => 'person_2_firstname', 'tabindex' => '10', 'class' =>'input-sm validate[required]', 'placeholder' => 'John', 'maxlength' => 60, 'default' => $person2['first_name'] ]);
                    ?>
                    <label for="last_name"><?= __('Last Name');?></label>
                    <?php echo $this->Form->text('person_2_last_name', [ 'id' => 'person_2_lastname', 'tabindex' => '11', 'class' =>'input-sm validate[required]', 'placeholder' => 'Doe' , 'maxlength' => 60, 'default' => $person2['last_name']] );
                    ?>
                    <br>
                    <br>
                   
                    <label for=""><?= __('Gender');?></label>
                    <?php
                        //  $options = ['M' => __('Male'), 'F' => __('Female')];

                        echo $this->Form->select('person_2_gender', $options, ['class' => 'validate[required] down', 'empty' => __('Select Gender') ,  'tabindex' => '12' , 'default' => $person2['gender'] ] ); 

                    ?>
                    <br>
                    <label for="birth_day"><?= __('Date of Birth');?></label>
                    <?php 
                    echo $this->Form->text('person_2_birth_date', ['class' => 'validate[required]', 'id' => 'person-2' , 'autocomplete' => 'off' ,  'tabindex' => '13', 'readonly' => 'readonly' , 'default' => $person2['birth_date'] ] ); 
                    ?>
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
                    <?php   echo $this->Form->select('person_2_hours', $hourOtions, ['id' => 'person_2_hours' , 'class' => 'validate[required] hour down' , 'empty' => __('Hour') ,  'tabindex' => '14', 'style' => ['width:47%'], 'default' => $person2['hours'] ]); ?>


                    <?php $minOptions=[
                    '-1' => 'Unknown Time',
                    '00' => '00', '01' => '01', '02' => '02', '03' => '03', '04' => '04', '05' => '05',
                    '06' => '06', '07' => '07', '08' => '08', '09' => '09', '10' => '10', '11' => '11',
                    '12' => '12', '13' => '13', '14' => '14', '15' => '15', '16' => '16', '17' => '17',
                    '18' => '18', '19' => '19', '20' => '20', '21' => '21', '22' => '22', '23' => '23',
                    '24' => '24', '25' => '25', '26' => '26', '27' => '27', '28' => '28', '29' => '29',
                    '30' => '30', '31' => '31', '32' => '32', '33' => '33', '34' => '34', '35' => '35',
                    '36' => '36', '37' => '37', '38' => '38', '39' => '39', '40' => '40', '41' => '41', 
                    '42' => '42', '43' => '43', '44' => '44', '45' => '45', '46' => '46', '47' => '47',
                    '48' => '48', '49' => '49', '50' => '50', '51' => '51', '52' => '52', '53' => '53',  
                    '54' => '54', '55' => '55', '56' => '56', '57' => '57', '58' => '58', '59' => '59'

                    ] ;?>
                    <?php echo $this->Form->select('person_2_minutes' , $minOptions , ['id' => 'person_2_minutes' , 'class' => ' validate[required] minute down' , 'empty' => __('Minute') ,  'tabindex' => '15', 'style' => ['width:46% ; margin-left:5%'], 'default' => $person2['minutes'] ])?>

                    <br>

        <label for="country"><?= __('US State / Country of Birth');?></label>
        <?php echo $this->Form->select('person_2_country_id', $countryOptions, ['id' => 'country-2',  'class' => 'validate[required] down', 'empty' => 'Select Country' ,  'tabindex' => '16' , 'default' => $person2['country_id'], 'onChange'=>'hideExtraFields(\'suggesstion-box-2\', \'search-box-2\')'] ); ?>

        <br>

        <label for="city"><?= __('Birth City');?></label>

        <?php echo $this->Form->text( 'person_2_city', ['id' => 'search-box-2', 'placeholder' => 'Enter City', 'autocomplete' => 'off', 'class' => ' validate[required] ui-autocomplete-input' ,  'tabindex' => '17', 'default' => $person2['city']])?>

        <div id="loading-2" style="display:none">
        <?php echo $this->Html->image('calendar-loading.gif', ['alt' => 'Loading']);?>
        </div>


        <div id="suggesstion-box-2">

        </div>
        <br>
        <?php echo $this->Form->hidden('person_2_city_id', ['id' => 'city_id_2', 'default' => $person2['city_id']]);  ?>
        <br>
        <p><cite><?= __('(Enter first few letters of birth town and click on town in list)');?></cite></p>
   </div>
  </div>
    <div class="et_pb_section checkout_header et_pb_section_0 et_section_regular">
        <div class="et_pb_text et_pb_module et_pb_bg_layout_light et_pb_text_align_right checkout_footer et_pb_text_2">
            <hr>
            <?php 
               echo  $this->Html->link('<i class="fa fa-long-arrow-left" aria-hidden="true"></i> '.__('Back'),[ 'controller' => 'Orders', 'action' => 'checkout-step-1'] ,[  'class' => 'btn btn-red check_back', 'escape' => false ]);
           
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
</div> <!-- .entry-content -->
</article> <!-- .et_pb_post -->
<script>
    $(document).ready(function(){
         $('#person_1_hours').change(function(){
             var hours = $(this).val();
             if(hours == -1){
               $('#person_1_minutes').val('-1');
             }
         })
         $('#person_2_hours').change(function(){
             var hours = $(this).val();
             if(hours == -1){
               $('#person_2_minutes').val('-1');
             }
         })
 
         $("#search-box-1").keyup(function(event){
            event.preventDefault();
            $('#flash_error').hide();
            getCities('country-1', 'search-box-1', 'suggesstion-box-1','city_id_1', "<?php echo $this->Url->build(['controller' => 'Users', 'action' => 'cities' ])?>");
          
            });

            $("#search-box-2").keyup(function(event){
            event.preventDefault();
            $('#flash_error').hide();
            getCities('country-2', 'search-box-2', 'suggesstion-box-2','city_id_2', "<?php echo $this->Url->build(['controller' => 'Users', 'action' => 'cities' ])?>");
              
            });

    });
</script>