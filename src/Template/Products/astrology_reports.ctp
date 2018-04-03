<?php use Cake\Cache\Cache;?>
<?php
      use Cake\Routing\Router;
      $locale = $this->request->session()->read('locale');
      $page_locale = ($locale == 'da') ? 'dk/' : ''; 
?>
<article id="post-39" class="post-39 page type-page status-publish hentry">
   <div class="entry-content">
       <div class="et_pb_section et_pb_fullwidth_section  et_pb_section_0 et_section_regular ourreportInner">
           <div class="et_pb_fullwidth_code et_pb_module  et_pb_fullwidth_code_0">
            <div class="astrology_section_common">
            <div class="et_pb_section  et_pb_section_0 et_section_regular">
                <div class=" et_pb_row et_pb_row_1">
                    <div class="et_pb_column et_pb_column_4_4  et_pb_column_2">
                        <div class="et_pb_text et_pb_module et_pb_bg_layout_light et_pb_text_align_center  et_pb_text_5">
                            <!-- <h2>< ?= __('OUR REPORTS');?></h2> -->
                            <?php if( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' ) { ?>
                              <h1><?= __('ASTROLOGY REPORTS');?></h1>
                            <?php } else { ?>
                              <h2><?= __('ASTROLOGY REPORTS');?></h2>
                            <?php } ?>

                            <?= __('Designed and written by astrologer Adrian Ross Duncan, our reports was designed to give you a fully immersive experience of astrology and of your own personal horoscope. We believe that astrology should be presented in an easily accessible way.');?>
                            <br><br>
                            <hr>
                            <br><br>
                        </div>
                        <!-- .et_pb_text -->
                    </div>
                    <!-- .et_pb_column -->
                </div>
                <?php 

 
                if( !$reports->isEmpty() ):
                   foreach($reports as $report):
                    if($report['id'] != 23)
                    {
                    ?>
                    <?php
                      /*
                       * Removed lovers report from report listing page for elite member
                       * Created By : Krishan Gupta
                       * Created Date : March 22, 2017
                       */
                      if ( !empty($this->request->session()->read('Auth.User.role')) && ($this->request->session()->read('Auth.User.role') == 'elite') && ($report['id'] == 5)) {
                          continue;
                      }
                      // END
                    ?>
                <div class=" et_pb_row et_pb_row_0 et_pb_row_padding" style="padding:2% 0px !important">
                    <div class="column-one">
                     <div class="et_pb_column et_pb_column_1_3 et_pb_column_0">
                        <div class="et_pb_module et-waypoint et_pb_image et_pb_animation_left et_pb_image_0 et_always_center_on_mobile et-animated">
                         <?php 
                         echo $this->Html->image("/uploads/products/".$report['image'] , [ 'alt' => $report['name']]);  
                         ?>
                     </div>
                 </div> <!-- .et_pb_column -->
                 </div>

                <div class="column-two">

                 <div class="et_pb_column et_pb_column_2_3  et_pb_column_1">
                    <div class="et_pb_text et_pb_module et_pb_bg_layout_light et_pb_text_align_left product_summary et_pb_text_0">
                        <h3 class="capital_text"><?php echo $report['name'] ?></h3>
                        <p><cite><?= __('Approx');?> <?php echo $report['pages']?> <?= __('pages');?></cite></p>
                        <p>
                        
                         <?php 

                           $content = strip_tags( $report['short_description'] );

                          //if ( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' ) {
                            $length  =  count(explode(' ', $content));
                            $limit = 50;
                          /*} else {
                            $length  =  substr_count($content, ' ');
                            $limit = 150;
                          }*/

                          //$length  =  substr_count($content, ' ');
                          if( $length >= $limit ) {

                            //if ( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' ) {
                              $reportData = implode(' ', array_slice(explode(' ', $content), 0, 50));
                            /*} else {
                              $pos = strpos ($content, ' ', 150);
                              $reportData = substr($content,0,$pos );
                            }*/
                            echo __d('default', $reportData).'...';
                            echo "<strong>
                            <em>";
                           //echo $this->Html->link(' '.__('READ MORE'), ['controller' => 'Products', 'action' => 'detail', $report['seo_url'], __d('full-reports'), $report['id'] ]);

                          echo $this->Html->link( __('READ MORE'), Router::url('/'.$page_locale.__('astrology-reports').'/'. __d('default', $report['seo_url']). '/'.__('full-reports') , true));

                         echo "</em>
                         </strong>";
                           }
                           else
                           {
                            echo strip_tags( $report['short_description'] );
                           }
                         ?>
                         
                     </p>
                     <p>
                       <?php 
                       if(!empty($report['preview_report']['pdf']))
                       {
                          echo  $this->Html->link(__('View Sample'), "/uploads/preview_reports/".$report['preview_report']['pdf'], ['class' => 'btn btn-red-border', 'target' => '_blank']);
                      }

                      ?>
                      &nbsp;&nbsp;&nbsp;

                      <?php 
                      //echo $this->Html->link(__('Order Now'), ['controller' => 'Products', 'action' => 'detail', $report['seo_url'], __('full-reports')], ['class' => 'btn btn-red']);


                        echo $this->Html->link( __('Order Now'), Router::url('/'.$page_locale.__('astrology-reports').'/'. __d('default', $report['seo_url']). '/'.__('full-reports') , true), ['class' => 'btn btn-red']);

                      ?>



                  </p>
              </div> <!-- .et_pb_text -->
          </div> <!-- .et_pb_column -->
</div>

      </div>

     <?php }?> 
  <?php endforeach; ?>
<?php else:?>
    <h4><?= __('No Reports Available'); ?></h4>
<?php endif;?>

  <div class=" et_pb_row et_pb_row_0 et_pb_row_padding" style="padding:2% 0px !important">
      <?php 
      /*
       * Google Adsense
       * Created by : Kingslay 
       * Created by : March 29, 2017 
       */
      echo $this->Element('GoogleAdSense/google-add-horoscope'); ?>
  </div>

</div>
</div>
<?php //echo $this->Element('products/our_software');?>

<?php 
  if( !$user = $this->request->session()->read('user_id')):
  // Memcache
  //if( !$user = Cache::read('user_id')):
  echo $this->Element('newsletter');
endif;
?>



</div> <!-- .et_pb_fullwidth_code -->

</div> <!-- .et_pb_section -->
</div> <!-- .entry-content -->


</article> <!-- .et_pb_post -->