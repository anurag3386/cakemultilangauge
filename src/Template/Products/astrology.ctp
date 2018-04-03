<?php use Cake\I18n\I18n;?>
<?php use Cake\Routing\Router;?>
<?php $locale = strtolower( substr(I18n::locale(),0 ,2 )) ;?>
<?php $page_locale = ($locale == 'da') ? 'dk/' : ''; ?>
<article id="post-49" class="post-49 page type-page status-publish hentry">
   <div class="entry-content">
       <div class="et_pb_section et_pb_fullwidth_section  et_pb_section_0 et_section_regular">
           <div class="et_pb_fullwidth_code et_pb_module  et_pb_fullwidth_code_0">
           <div class="softwares_section_common">
            <div class="et_pb_section  et_pb_section_0 et_section_regular">
                <div class=" et_pb_row et_pb_row_1 et_pb_row_padding">
                    <div class="et_pb_column et_pb_column_4_4  et_pb_column_2">
                        <div class="et_pb_text et_pb_module et_pb_bg_layout_light et_pb_text_5">
                            <h1><?= __('World of Wisdom Astrology Software');?></h1>
                            <br>
                            <h3>
                              <?php
                                echo __('What makes astrology software from WOW unique?');
                              ?>
                            </h3>
                            <p>
                              <?php 
                                  echo __('What makes World of Wisdom astrology software unique? All WOW software is interpretation software, which means that apart from the automatic calculation of accurate horoscopes from anywhere in the world, each and every astrological influence has a detailed interpretation connected with it. You can in fact click anywhere on the horoscope wheel, and an astrological interpretation can immediately be seen in the interpretation window.');
                                ?>
                            </p>



                               <p>
                               <?php 
                                    echo __('Furthermore, these programs are advanced astrology prediction software, which enables you to understand exactly what is going on in your life here and now. This astrology software is available for download as a free trial, and you can use it for a month before having to buy a registration key. You can access our astrology software online on this site and download it now. World of Wisdom programs are professional astrology software which also provides detailed astrology reports, which you can give freely to friends and family. You can also buy a license to sell the astrology reports professionally.');
                                ?>
                               </p>
                               <p>
                               <?php
                                  echo __('Despite providing accurate horoscope calculation and complete, detailed astrology reports of 25+ pages in length, World of Wisdom astrology software is inexpensive and amazingly user-friendly. Whether you are a beginner or an expert, using these astrology programs is pleasurable and without unnecessary complication or technicalities.');
                               ?>
                                 
                               </p>
                               </div>
                               <!-- .et_pb_text -->
                           </div>
                           <!-- .et_pb_column -->
                       </div>
                       <?php 
                       $productType  = $this->request->params['pass'][0];
                       if( !$products->isEmpty() ):
                           foreach($products as $product):
                            ?>
                        <div class=" et_pb_row et_pb_row_0 et_pb_row_padding" style="padding:2% 0px !important">
                        <div class="software_column_1">
                            <div class="et_pb_column et_pb_column_1_3 et_pb_column_0">
                                <div class="et_pb_module et-waypoint et_pb_image et_pb_animation_left et_pb_image_0 et_always_center_on_mobile et-animated">
                                  <?php 
                                  echo $this->Html->image("/uploads/products/".$product['image'] , [ 'alt' => $product['name'], 'class' => 'img-responsive']);  
                                  ?>
                              </div>
                          </div> <!-- .et_pb_column -->
                          </div>



                          <div class="software_column_2">
                          <div class="et_pb_column et_pb_column_2_3  et_pb_column_1">
                            <div class="et_pb_text et_pb_module et_pb_bg_layout_light et_pb_text_align_left product_summary et_pb_text_0">
                                <h3 class="capital_text"><?php echo $product['name'] ?><span style="font-size: 12px; text-transform: none;">&nbsp;&nbsp;<?php echo __('(Windows only)');?></span></h3>
                                <p>
                                
                                  <?php
                                  $content = strip_tags( $product['short_description'] );
                                  //if ( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' ) {
                                    $length  =  count(explode(' ', $content));
                                    $limit = 50;
                                  /*} else {
                                    $length  = strlen($content);
                                    $limit = 200;
                                  }*/
                                  if( $length >= $limit ) {
                                    //if ( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' ) {
                                      $softwareData = implode(' ', array_slice(explode(' ', $content), 0, 50));
                                    /*} else {
                                      $pos=strpos($content, ' ', 200);
                                      $softwareData = substr($content, 0, $pos );
                                    }*/
                                    echo __d('default', $softwareData).'...';
                                    echo "<strong><em>";
                                    echo $this->Html->link(' '.__('READ MORE'), ['controller' => 'Products', 'action' => 'detail', $product['seo_url'], 'software-cd' ]);
                                    echo "</em>
                                    </strong>";
                                  } else {
                                    echo strip_tags( $product['short_description'] );
                                  }

                                  ?>

                              </p>
                              <div class="free_trial_buy">

                               <?php 
                               if($productType != 'software-bundle')
                               {

                                  $default = ($locale == 'en') ? 1 : 2;
                                  echo $this->Form->create($form, ['class' => 'download_free_trial']);
                                  echo $this->Form->hidden('product_type', ['value' => FREE_TRIAL]);
                                  echo $this->Form->hidden('category', ['value' => 'free-trial']);
                                  echo $this->Form->hidden('language_id', [ 'class' => 'language_id', 'value' => $default]);
                                  echo $this->Form->hidden('product_id', ['value' => $product['id']]);
                                  echo $this->Form->submit(__('30 Days Free Trial'), ['class' => 'btn btn-red']);
                                  echo $this->Form->end();
                                ?>
            
                                <?php 
                               //  echo $this->Html->link(__('30 Days Free Trial'), ['controller' => 'Orders', 'action' => 'download-free-trial'], ['class' => 'btn btn-red', 'target' => '_blank']);
                                ?>


                                <?php 


                             }
                             ?>

                             &nbsp;&nbsp;&nbsp;
                             <?php 
                             //echo $this->Html->link(__('Buy/Register shareware'), ['controller' => 'Products', 'action' => 'detail', $product['seo_url'],'shareware' ], ['class' => 'btn  btn-red-border']);

                               echo $this->Html->link( __('Buy/Register shareware'), Router::url('/'.$page_locale.__('astrology-software').'/'. __d('default', $product['seo_url']). '/shareware' , true), ['class' => 'btn  btn-red-border']);
                           ?>
                             
                                                          &nbsp;&nbsp;&nbsp;
                             <?php 

                             //echo $this->Html->link(__('Buy Software CD'), ['controller' => 'Products', 'action' => 'detail', $product['seo_url'],'software-cd' ], ['class' => 'btn btn-red']);

                              echo $this->Html->link( __('Buy Software CD'), Router::url('/'.$page_locale.__('astrology-software').'/'. __d('default', $product['seo_url']). '/software-cd' , true), ['class' => 'btn btn-red']);
                           ?>
                         </div>

                     </div> <!-- .et_pb_text -->
                 </div> <!-- .et_pb_column -->
                 </div>

             </div>

         <?php endforeach; ?>
     <?php else:?>
        <h4><?= __('No Softwares Available');?></h4>
    <?php endif;?>

    <div class=" et_pb_row et_pb_row_3" style="padding:2% 0px !important">
     <div class="software_column_1">

      <div class="et_pb_column et_pb_column_1_3 et_pb_column_0">
            <div class="et_pb_module et-waypoint et_pb_image et_pb_animation_left et_pb_image_0 et_always_center_on_mobile et-animated">
             <?php 
             echo $this->Html->image("/uploads/products/software_2.png" , [ 'alt' => 'Astrology Software', 'class' => 'img-responsive']);  
             ?>


         </div>
     </div> <!-- .et_pb_column -->
  </div>
    <div class="software_column_2">

     <div class="et_pb_column et_pb_column_2_3  et_pb_column_1">
        <div class="et_pb_text et_pb_module et_pb_bg_layout_light et_pb_text_align_left product_summary et_pb_text_0">
            <h3 class="capital_text"><?= __('Free AstroClock iOS App');?></h3>
            <p><?= __('The AstroClock has been a labor of love, and the first version is now available on the App Store');?>...
            <strong>
            <em>
                <a target="_blank" href="https://itunes.apple.com/us/app/astrowow-astroclock/id1001024725?mt=8"><?= __('READ MORE');?></a>
                </em>
                </strong>
            </p>
                <div class="ios_software">

            <p> 
                <a target="_blank" href="https://itunes.apple.com/us/app/astrowow-astroclock/id1001024725?mt=8" class="btn btn-red-border"><?= __('Download on iOS Store');?></a>&nbsp;&nbsp;
                <div><a title="Instruction video for AstroClock" target="_blank" href="https://www.youtube.com/watch?v=HTH8G7ZlRt0"><span style="color: #000000;text-decoration: underline;"><strong><?= __('Instruction video for AstroClock'); ?></strong></span></a></div>&nbsp;&nbsp;&nbsp;
                <div class="apple-icon">
                  <a href="https://itunes.apple.com/us/app/astrowow-astroclock/id1001024725?mt=8" target="_blank">
                    <?php echo $this->Html->image("/uploads/products/icon_app_store.png" , [ 'alt' => '', 'class' => 'img-responsive']); ?>
                  </a>
                </div>

            </p>
        </div> <!-- .et_pb_text -->
</div>

    </div> <!-- .et_pb_column -->
  </div>


</div>
</div>

</div>
</div> <!-- .et_pb_fullwidth_code -->

</div> <!-- .et_pb_section -->
</div> <!-- .entry-content -->


</article> <!-- .et_pb_post -->
