<?php use Cake\Cache\Cache;?>
<?php use Cake\I18n\I18n;?>
<?php use Cake\Routing\Router;?>
<?php $locale = strtolower( substr(I18n::locale(), 0, 2 ) );?>
<?php $page_locale = ($locale == 'da') ? 'dk/' : ''; ?>
<article id="post-1178" class="post-1178 page type-page status-publish hentry">
  <div class="entry-content">
    <div class="freehoroscope-first">
    <div class="et_pb_section  et_pb_section_1 et_section_regular freeHoroscope">
      <div class=" et_pb_row et_pb_row_0">
        <div class="et_pb_column et_pb_column_4_4  et_pb_column_0">
          <div class="et_pb_text et_pb_module et_pb_bg_layout_light et_pb_text_align_center  et_pb_text_0">
            <h1><?= __('Free Horoscopes');?></h1>
           <!--  <div class="advertising_banner">
            <?php 
                echo $this->Html->image('sample_ad.jpg', ['alt' =>'sample_ad', 'class' => 'alignright size-full wp-image-375']);
            ?>
            </div> -->
          </div>
          <!-- .et_pb_text --></div>
          <!-- .et_pb_column --></div>
          <!-- .et_pb_row --></div>
          </div>

          <!-- .et_pb_section -->
          <div class="freehoroscope-second">
          <div class="et_pb_section  et_pb_section_2 et_section_regular freeHoroscope freehoroscope-top">
            <div id="timeline" class=" et_pb_row et_pb_row_1">
              <div class="et_pb_column   et_pb_column_1">


                <div class="et_pb_text et_pb_module et_pb_bg_layout_light et_pb_text_align_left  et_pb_text_1">
              <div class="col_1_sunsign">
                  <?php if( !empty($allsunsigns)):?>
                    <?php

                    foreach($allsunsigns as $sunsign):
                     $name = $sunsign['name'];
                   ?>


                   <div class="sunsignBox">
<!--                    <div class="sunsign_inner">
 -->                    <div class="sunsignImg"><?php 

                    // echo $this->Html->link($this->Html->image('/uploads/sunsigns/'.$sunsign['icon']),       [ 'controller' => 'SunSigns', 'action' => 'index', strtolower($name), __('daily-horoscope')], array('escape' => false, 'target' => '_blank'));

                      //echo $this->Html->image('/uploads/sunsigns/'.$sunsign['icon']);

                    ?>

                    <?php
                      if( $locale == 'da' )
                      {
                    ?>
                      <a href="<?php echo Router::url('/', true).'dk/'.__d('default', 'sun-signs').'/'.__d('default', strtolower($sunsign['name'])).'/'. __('daily-horoscope').'#dag-fanen'?>">
                        <img src="<?php echo Router::url('/', true).'uploads/sunsigns/'.$sunsign['icon']?>" alt='<?= __('horoscope for')." ".__($sunsign['name'])?>'/>  
                      </a>
                  <?php
                   }
                   else
                   {
                  ?>
                      <a href="<?php echo Router::url('/', true).__d('default', 'sun-signs').'/'.__d('default', strtolower($sunsign['name'])).'/'. __('daily-horoscope').'#today-tab'?>">
                        <img src="<?php echo Router::url('/', true).'webroot/uploads/sunsigns/'.$sunsign['icon']?>" alt="<?= __('horoscope for')." ".__($sunsign['name'])?>"/>
                      </a>
                  <?php 
                  }
                  ?>
                    </div>
                    <div >
                      <h2 class="sunsignName">

                      <?php
                        if( $locale == 'da')
                        {
                          ?>
                            <a href="<?php echo Router::url('/', true).'dk/'.__d('default', 'sun-signs').'/'.__d('default', strtolower($sunsign['name'])).'/'. __('daily-horoscope').'#dag-fanen'?>"><?= __d('default', $sunsign['name']);?></a>

                          <?php
                        }
                        else
                        {
                          ?>
                            <a href="<?php echo Router::url('/', true).__d('default', 'sun-signs').'/'.__d('default', strtolower($sunsign['name'])).'/'. __('daily-horoscope').'#today-tab'?>"><?= __d('default', $sunsign['name']);?></a>

                        <?php
                        }
                      ?>


                       </h2>
                      <p>
                          <?php 

                           // limiting number of chars
                           $content = $sunsign['characteristics'];
                           $pos=strpos($content, ' ', 80);
                           $horoscopeData = substr($content,0,$pos );
                           echo  __d('default', $horoscopeData).' '; 
                       ?>
                        <?php 
                       
                        if( $locale == 'da')
                        {
                          ?>
                            <a href="<?php echo Router::url('/', true).'dk/'.__d('default', 'sun-signs').'/'.__d('default', strtolower($sunsign['name'])).'/'. __('daily-horoscope').'#dag-fanen'?>"><?= __('...Read More');?></a>

                          <?php
                        }
                        else
                        {
                          ?>
                            <a href="<?php echo Router::url('/', true).__d('default', 'sun-signs').'/'.__d('default', strtolower($sunsign['name'])).'/'. __('daily-horoscope').'#today-tab'?>"><?= __('...Read More');?></a>

                        <?php
                        }
                     
                    ?>
                      </p>
                      <p>
                      <ul>
                       <li>
                       <a href="<?php echo Router::url('/', true).$page_locale.__d('default', 'sun-signs').'/'.__d('default', strtolower($sunsign['name'])).'/'. __('daily-horoscope').'#'.__('today-tab')?>"><?= __('Free daily horoscope');?> <?= __d('default', $sunsign['name']);?></a>
                       </li>
                       <li>
                        <a href="<?php echo Router::url('/', true).$page_locale.__d('default', 'sun-signs').'/'.__d('default', strtolower($sunsign['name'])).'/'. __('weekly-horoscope').'#'.__('weekly-tab')?>"><?= __('Free weekly horoscope');?> <?= __d('default', $sunsign['name']);?></a>
                       </li>
                       <li>
                        <a href="<?php echo Router::url('/', true).$page_locale.__d('default', 'sun-signs').'/'.__d('default', strtolower($sunsign['name'])).'/'. __('monthly-horoscope').'#'.__('monthly-tab')?>"><?= __('Free monthly horoscope')?> <?= __d('default', $sunsign['name']);?></a>
                       </li>
                       <li>
                        <a href="<?php echo Router::url('/', true).$page_locale.__d('default', 'sun-signs').'/'.__d('default', strtolower($sunsign['name'])).'/'. __('yearly-horoscope').'#'.__('yearly-tab')?>"><?= __('Free yearly horoscope')?> <?= __d('default', $sunsign['name']);?></a>

                      
                       </li>
                       <li>
                        <a href="<?php echo Router::url('/', true).$page_locale.__d('default', 'sun-signs').'/'.__d('default', strtolower($sunsign['name'])).'/'. __('characteristics').'#'.__('characteristics-tab')?>"><?= __d('default', $sunsign['name']);?> <?= __('Characteristics')?></a>
                       </li>
                       <li>
                        <a href="<?php echo Router::url('/', true).$page_locale.__d('default', 'sun-signs').'/'.__d('default', strtolower($sunsign['name'])).'/'. __('celebrity').'#'.__('celebrity-tab')?>"><?= __d('default', $sunsign['name']);?> <?= __('Celebrity')?></a>
                       </li>
                      </ul>
                     
                     </p>
                     

                   </div>
                 </div>

               <?php endforeach;?>
             <?php else: ?>
              <h5> No Sunsign Found</h5>
            <?php endif;?>

            
            <?php 
              /*
               * Google Adsense
               * Created by : Kingslay 
               * Created by : March 29, 2017 
               */
            echo $this->Element('GoogleAdSense/google-add-horoscope'); ?>
          </div>
          <div class="ad_sense">
          <?php
              /*
               * Google Adsense
               * Created by : Kingslay 
               * Created by : March 29, 2017 
               */
              echo $this->Element('GoogleAdSense/large-adsense-horoscopes');
              ?>
          </div>
          </div>

          <!-- .et_pb_text --></div>

          <!-- .et_pb_column --></div>
          <!-- .et_pb_row --></div>

          <!-- Google Adsense -->
        <!--   <div class="sml-common-box box-shadow">
              
          </div> -->
          
          </div>
          <!-- .et_pb_section --></div>
          <?php // echo $this->Element('products/our_reports');?>
          <?php //echo $this->Element('products/our_software');?>
          <?php 
              if(!$user = $this->request->session()->read('user_id')):
          ?>
            <?php echo $this->element('newsletter'); ?>
          <?php endif;?>


          <!-- .entry-content --></article>
          <!-- .et_pb_post -->

<!-- #main-content -->