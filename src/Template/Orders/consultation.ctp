	<?php use Cake\Routing\Router; ?>
  <?php use Cake\I18n\I18n;?>
  <?php use Cake\Cache\Cache;?>
  <?php $locale = strtolower(I18n::locale());?>
  <article id="post-51" class="post-51 page type-page status-publish hentry">
  <div class="entry-content">
 
  <div class="parent">
    <div class="et_pb_section et_pb_fullwidth_section  et_pb_section_0 et_section_regular">
      <div class="et_pb_module et-waypoint et_pb_fullwidth_image et_pb_animation_fade_in  et_pb_fullwidth_image_0 et-animated">
        <?php echo $this->Html->image('adrian_banner.jpg',['alt'=>'']);?>
      </div>
    </div> <!-- .et_pb_section -->
   </div>

    <div class="consultation_top_section">
    <div class="et_pb_section  et_pb_section_1 et_section_regular">
      <div class=" et_pb_row et_pb_row_0">
        <div class="et_pb_column et_pb_column_4_4  et_pb_column_0">
          <div class="et_pb_text et_pb_module et_pb_bg_layout_light et_pb_text_align_center  et_pb_text_0">
            <h1><?php
             if( $locale == 'en_us' )
             {
               echo __('Telephone/Skype Consultations');
             }
             else
             {
               echo 'Konsultationer';
             }

             ?></h1>
 
          </div>
          <!-- .et_pb_text --></div>
          <!-- .et_pb_column --></div>
          <!-- .et_pb_row --></div>

          </div>
          <div class="consultation_section_bottom">
          <div class="et_pb_section  et_pb_section_3 et_section_regular cmsPage">
            <div class=" et_pb_row et_pb_row_3">
               <div class="consultation_col_1">

              <div class="et_pb_column et_pb_column_1_2  et_pb_column_4">
                <div class="et_pb_code et_pb_module  et_pb_code_0">
                  <?php 
                   if( $locale == 'en_us' )
                   {
                     echo  __('Generally I work with face to face consultations, but after receiving many requests from people around the world who have heard my talks or seen my latest book (Astrology: Transformation & Empowerment), I have decided to start doing a limited number of telephone consultations every month.');
                   }
                   else
                   {
                     echo 'Jeg er kendt fra fjernsynsprogrammet Den 6. sans, fra "Adrians verdenshjørne" i astrologibladet Stjernerne og fra foredrag verden over. Desuden har jeg skrevet astrologiprogrammerne World of Wisdom. Jeg grundlagde AstrologSkolen, som uddannede astrologere fra 1990 – 1997, og er forfatter til "Det astrologiske urværk" som på engelsk hedder "Doing Time on Planet Earth".';
                   }

                  ?><p></p>
                  <p>
                  <?php 

                    if( $locale == 'en_us' )
                    {
                      echo  __('With the advent of Skype it is possible to really offer an astrological service that is worthwhile, especially if the client has a web cam which helps capture a lot of nuances in the consultation. You can order a consultation at the bottom of the page. The waiting time is approximately two weeks. I will contact you via email after you have made your payment. More info? E-mail me.');
                    }
                    else
                    {
                      echo 'Mit speciale er den personlige konsultation, og min seneste bog "Astrology: Transformation & Empowerment" er en lærebog om avancerede teknikker i den astrologiske konsultation, respekteret og brugt af astrologiske institutioner verden over.';
                    }
                    
                  ?><br>
                    <br><br>
                  </p>
                  <?php 
                    if($locale == 'en_us')
                    {
                  ?>
                  <h3><?= __('Here\'s how it works');?>:</h3><br>
                  <ul class="checklist-white"><li><?= __('Consultations with the US take place weekdays at an agreed time dependant on your US time zone. With the Far East, Australia and New Zealand consultations are in the afternoon or evening. With Europe and the UK, any time between 7 a.m. and 2 p.m.');?></li><br>
                    <li><?= __('Consultations last a maximum of 60 minutes.');?></li><br>
                    <li><?= __('Consultations are recorded (if Skype is used) and sent as a CD. You are encouraged to install Skype and use a webcam!');?></li><br>
                    <li><?= __('I will skype or ring you at the appointed time - all call charges are paid by me if phone is used. (Landline only... not cell).');?></li><br>
                    <li><?= __('The cost of consultations can be seen in Dollars, Euros or Pounds at the bottom of the page. Payment is in advance. Follow up consultations are about 30% cheaper.');?></li><br>
                   </ul>
                   <?php }?>

                 </div> <!-- .et_pb_code -->
                 </div>




               </div> <!-- .et_pb_column -->
               <div class="consultation_col_2">
               <div class="et_pb_column et_pb_column_1_2  et_pb_column_5">

               <div class="et_pb_code et_pb_module  et_pb_code_1">
                <h3><?php
                 if($locale == 'en_us')
                 {
                  echo  __('Prior to the consultation you will need to');
                 }
                 else
                 {
                  echo 'Når du bestiller en konsultation hos mig får du';
                 }

                 ?>:</h3>
                <ul class="checklist-white"><br>
                <?php 
                 if($locale == 'en_us')
                 {
                ?>
                  <li><?= __('Mail your birth day, birth time and birth place.');?></li><br>
                  <li><?= __('Mail your telephone number, skype address and postal address.');?></li><br>
                  <li><?= __('Pay for the consultation in advance.');?></li><br>
                  <li><?= __('Book a weekday that fits for us both.')?></li><br>
               <?php }
               else
                 {
                  ?>
                  <li><?= 'Konkrete svar på konkrete spørgsmål';?></li><br>
                  <li><?= 'Teknikker for at ændre uhensigtsmæssig adfærd';?></li><br>
                  <li><?= 'En vision af en fremtid for dig, hvor du er bedre i stand til at opnå det du vil';?></li><br>
                  <?php

                 }?>

              </ul><p>
                 <?php 
                  if($locale != 'en_us'):
                  ?>
                <?= 'Konsultationen tager udgangspunkt i der, hvor du står her og nu. Samtidigt får du en forståelse af årsagerne til din nuværende situation, og en plan for din fremtid.';?>
               <?php
                  endif;
                ?>
              </p>
              <p></p>

               <?php 
               if($locale == 'en_us')
               {
               ?>
                  <h3><?= __('I will study your birth horoscope together with transits and progressions, and I also make the consultation chart for the moment the phone call begins. This enables me to');?>:</h3><p></p>
                  <p></p>
                  <ul class="checklist-white"><br>
                  <li><?= __('Describe your current situation and the events leading up to it');?></li><br>
                  <li><?= __('Explain how the situation will develop and how you can get optimal results');?></li><br>
                  <li><?= __('Examine difficulties from your past and relate them to challenges in the present');?></li><br>
                  <li><?= __('Evoke your strongest resources and work out the best strategy for the future');?></li><br>
                  <li><?= __('Deal with whatever is important to you professionally, in relationships and as regards personal development.');?> </li><br>
                </ul>
            <?php 
               }
             ?>

          </div> <!-- .et_pb_code -->
        </div> <!-- .et_pb_column -->
     </div>
      </div> <!-- .et_pb_row -->

    </div> <!-- .et_pb_section -->
    </div>

 <div class="consultation_bottom">
    <div class="et_pb_section  et_pb_section_4 et_section_regular orderConsultation" >



      <div class=" et_pb_row et_pb_row_4">

        <div class="et_pb_column et_pb_column_4_4  et_pb_column_6">

          <div class="et_pb_text et_pb_module et_pb_bg_layout_light et_pb_text_align_center  et_pb_text_4">

            <h3><?= __('Order Your Consultation');?></h3>

          </div> <!-- .et_pb_text -->
        </div> <!-- .et_pb_column -->

      </div> <!-- .et_pb_row -->





      <div class=" et_pb_row et_pb_row_5">
      <div class="consultation_col_3">
        <div class="et_pb_column et_pb_column_1_2  et_pb_column_7">

          <div class="et_pb_code et_pb_module  et_pb_code_2">

            <?php echo $this->Form->create($form, ['id' => 'frmCheckOutConsultation'])?>

      <!--   <form name="frmCheckOutConsultation" id="frmCheckOutConsultation" method="post" action="/consultation-checkout-step-1/">
    -->

  <?php 
       if( $locale == 'en_us' )
       {
         $default = 9;

         $currencyRadioButton =  $this->Form->input('currency_id', [
            'type' => 'radio',
            'options' => $currencyOptions,
            'templates' => [
                'nestingLabel' => '{{hidden}}<label{{attrs}}>{{text}}</label>{{input}}',
                'radioWrapper' => '<li>{{label}}<div class="check"></div></li>'
            ],
            'value' => '1',
            'label' => false
        ]);


        // $currencyRadioButton = $this->Form->radio('currency_id',$currencyOptions, [ 'value' => 1 , 'id'=> 'currency_id']
        //   );

         echo $this->Form->hidden('language_id', ['value' => ENGLISH]);
       }
       else
       {
         $default = 21;
         // $currencyRadioButton = $this->Form->radio('currency_id',$currencyOptions, [ 'value' => 3 , 'id'=> 'currency_id']
         //  );

         $currencyRadioButton =  $this->Form->input('currency_id', [
            'type' => 'radio',
            'options' => $currencyOptions,
            'templates' => [
                'nestingLabel' => '{{hidden}}<label{{attrs}}>{{text}}</label>{{input}}',
                'radioWrapper' => '<li>{{label}}<div class="check"></div></li>'
            ],
            'value' => '3',
            'label' => false
        ]);

         echo $this->Form->hidden('language_id', ['value' => DANISH]);
       }
  ?>

    <?php echo $this->Form->select('category_id', $categoryOptions, ['id' => 'category_id', 'default' => $default , 'class' => 'down']);?>


    <div class="currency-dropdown">
      <p><strong><?= __('Please choose your currency');?>:</strong></p>
      <br>
      <div class="redo">
     <ul>
        <?php

          echo $currencyRadioButton;
        ?>
        </ul>
        </div>
      </div>

      <p id="dvError"></p>
      <p><strong> <?= __('Consultation Type');?>:</strong></p>
<div class="redo">
  <ul>
      <?php 
      $i = 0 ;
      foreach( $priceInfo as $product)
      {

        if($product['ProductPrices']['discount_total_price'] == 0)
        {
         $productPrice     = $product['ProductPrices']['total_price'];
        }
        else
        {
         $productPrice     = $product['ProductPrices']['discount_total_price']; 
        }
        
        $currencySymbol   = $product['currency']['symbol'];
        $currencyCode     = $product['currency']['code'];
        $currencyId       = $product['currency']['id'];
        $productName      = $product['name'];

        if($i == 0)
        {
          $checked = "checked = 'checked'";
        }
        else
        {
          $checked = "";
        }
        ?>
        <p> 
         <li>

          <input name="consult_product" id="product_id_<?php echo $i?>" value="<?php echo $product['id']?>" <?php echo $checked;?> type="radio" onclick = "setConsultationData(this.value, <?php echo $i?>)"  >
           <!-- <label for="product_id_<?php echo $i?>"><?= $productName; ?> </label>
 -->
           <label for="product_id_<?php echo $i?>">
            <span id="consult-title-<?php echo $i?>"><?php echo __d('default', ucwords($product['name']));?></span> 

            <span class="lblCurrencyConsultation_1" id="spnSymbol_<?php echo $i?>" style="display: inline;">(<?php echo $currencySymbol.$productPrice;?>)
            </span> 

          </label>  

            <div class="check"></div>
         </li>

          <?php echo $this->Form->hidden('price'.$i, ['id' => 'checkout-price-'.$i, 'value' => $productPrice]); ?>
          <?php echo $this->Form->hidden('product_name'.$i, ['value' => $productName, 'id' => 'product_name_'. $i]); ?>

         <!--  <label for="consultation_14">
            <span id="consult-title-<?php echo $i?>"><?php echo __d('default', ucwords($product['name']));?></span> 

            <span class="lblCurrencyConsultation_1" id="spnSymbol_<?php echo $i?>" style="display: inline;">(<?php echo $currencySymbol.$productPrice;?>)
            </span> 

          </label>   -->

          <?php 
          $i++;

        }

        ?>
        <?php echo $this->Form->hidden('counter', ['id' => 'counter', 'value'=>0]); ?>

        <?php echo $this->Form->hidden('product_id', ['id' => 'product_id', 'value'=>$priceInfo[0]['id']]); ?>
        <?php echo $this->Form->hidden('price', ['id' => 'checkout-price', 'value' => $priceInfo[0]['ProductPrices']['total_price']]); ?>
        <?php echo $this->Form->hidden('currencyCode', ['class' => 'checkout-currency-code', 'value' => $priceInfo[0]['currency']['code']]); ?>
        <?php echo $this->Form->hidden('currency_symbol', ['class' => 'checkout-currency-symbol', 'value'=> $priceInfo[0]['currency']['symbol'] ]); ?>
        <?php echo $this->Form->hidden('product_name', [ 'id' => 'product_name', 'value' => $priceInfo[0]['name']]); ?>
        <?php echo $this->Form->hidden('url', ['value' => Router::url($this->request->here, true)])?>
      </p>

      <div id="consultation" style="display:none">
        <p> 
        <li>
          <input name="consult_product" id="product_id_2" type="radio" onclick = "setConsultationData(this.value, 2)" >
           <label for="product_id_2">
            <span id="consult-title-2"></span> 
            <span class="lblCurrencyConsultation_1" id="spnSymbol_2" style="display: inline;">
            </span> 
            <?php echo $this->Form->hidden('price2', ['id' => 'checkout-price-2']); ?>
            <?php echo $this->Form->hidden('product_name2', [ 'id' => 'product_name_2']); ?>
          </label>  
              <div class="check"></div>

          </li>
         <!--  <label for="consultation_14">
            <span id="consult-title-2"></span> 
            <span class="lblCurrencyConsultation_1" id="spnSymbol_2" style="display: inline;">
            </span> 
            <?php echo $this->Form->hidden('price2', ['id' => 'checkout-price-2']); ?>
            <?php echo $this->Form->hidden('product_name2', [ 'id' => 'product_name_2']); ?>
          </label>   -->
        </p>
      </div>
      </ul>
 </div>

      <p>
        <?php echo $this->Form->submit(__('Order Consultation'), ['class' => 'btn btn-red']);?>
        </p>
        <?php echo $this->Form->end();?>
        <?php 
               $order['url']   = Router::url($this->request->here(), true);
               $this->request->session()->write('Order' , $order);
        ?>  
      </div> <!-- .et_pb_code -->
    </div> <!-- .et_pb_column -->
    </div>

    <div class="consultation_col_4">
    <div class="et_pb_column et_pb_column_1_2  et_pb_column_8">

    <div class="et_pb_module et-waypoint et_pb_image et_pb_animation_right et_pb_image_1 et_always_center_on_mobile et-animated">

     <?php echo $this->Html->image('adrian_2.jpg',['alt'=>'']);?>

     <!-- <img src="Introduction%20_%20AstroWow_files/adrian_2.jpg" alt=""> -->

   </div><div class="et_pb_text et_pb_module et_pb_bg_layout_light et_pb_text_align_left  et_pb_text_5">

   <p>“<?= __('Generally I work with face to face consultations, but after receiving many requests from people around the world who have heard my talks or seen my latest book (Astrology: Transformation & Empowerment), I have decided to start doing a limited number of telephone consultations every month.');?>”</p>

  </div> <!-- .et_pb_text -->
</div> <!-- .et_pb_column -->
</div>

</div> <!-- .et_pb_row -->

</div> <!-- .et_pb_section -->
 
</div>

<?php //echo $this->element('books');?>



</div> <!-- .entry-content -->


</article> <!-- .et_pb_post -->
