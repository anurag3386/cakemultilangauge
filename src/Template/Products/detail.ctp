<?php use Cake\Routing\Router;?>
<?php use Cake\Cache\Cache; ?>
<?php use Cake\I18n\I18n;?>
<?php $locale = strtolower( substr( I18n::locale(), 0, 2) );?>
<?php $category = $this->Custom->getCategory($data['category_id']);?>
<article id="post-41" class="post-41 page type-page status-publish hentry">
  <div class="entry-content">
    <?php
      /*
       * Added by kingslay
       */
      $class = '';
      if(strtolower($category) == 'reports' ) {
        $class = ' report_detail';
      } else {
        $class = ' soft_detail';
      }
    ?>
    <div class="product-details<?php echo $class; ?>">
      <?php 
        // This div is used for reports section
        if(strtolower($category) ==  'reports' )
        echo "<div class='reports_parent'>";
      ?>
      <div class="detail-first">
        <div class="et_pb_section et_pb_fullwidth_section  et_pb_section_0 et_section_regular">
          <div class="et_pb_fullwidth_code et_pb_module  et_pb_fullwidth_code_0">
            <div class="entry-content">
              <div class="et_pb_section report_details et_pb_section_0 et_section_regular">
                <div class="detail_page_common">
                  <div class="et_pb_row et_pb_row_0 et_pb_row_1-4_3-4 et_pb_row_1-4 buy-left-column">
                    <?php
                      if(strtolower($category) == 'software' || strtolower($category) == 'software-bundle') {
                        if($data['productType'] == SHAREWARE) {
                          $type =  " shareware"; 
                        } else {
                          $type = " CD";
                        }
                      } else {
                        $type =  '';
                      } 
                      if( strtolower($category) == 'software' || strtolower($category) == 'software-bundle' ) {
                        echo $this->element('products/softwares', ['type' => $type, 'category' => $category]);
                      } else {
                        echo $this->element('products/reports');
                      }
                    ?>
                    <!--  <em style="text-align:left;"> -->

                    <!-- .et_pb_column -->
                    <div class="productDetailRight">
                      <h1>
                        <?php echo $data['name']; ?><span style="font-size: 12px; text-transform: none;">&nbsp;&nbsp;<?php echo __('(Windows only)');?></span>
                      </h1>
                      <p><?php echo $data['short_description']; ?></p>
                      <?php echo $this->element('products/paypal_seal'); ?>
                    </div>
                    <div class="sample_reports_section">
                      <div class="et_pb_column et_pb_column_3_4  et_pb_column_1">
                        <?php if( strtolower($category) != 'software' && strtolower($category) != 'software-bundle' ) { ?>
                          <div class="et_pb_text et_pb_module et_pb_bg_layout_light et_pb_text_align_left  et_pb_text_1">
                            <h4 class="text_left"><?= __('SAMPLE REPORTS');?></h4>
                            <p>
                              <?php
                                if(isset($data['preview_report']['pdf']) && !empty($data['preview_report']['pdf'])) {
                                  echo $this->Html->link(__('View Full Sample'), "/uploads/preview_reports/".$data['preview_report']['pdf'], ['class' => 'text_right', 'target' => '_blank']);
                                }
                              ?>
                            </p>
                          </div>
                          <!-- .et_pb_text -->

                          <!-- .et_pb_gallery -->
                          <div id="preview_gallery" class="et_pb_module et_pb_gallery et_pb_gallery_0 et_pb_gallery_grid et_pb_bg_layout_light clearfix" style="display: block;">
                            <div class="et_pb_gallery_items et_post_gallery">
                              <!-- .et_pb_gallery_items -->
                              <?php
                                $preview_reports = $this->Custom->getPreviewReports();
                                if(!empty($preview_reports)) {
                                  foreach($preview_reports as $report) {
                                    if( isset($report['pdf']) && !empty($report['pdf']) ) {
                                      $title = $report['title'];
                                      $report_path = $this->request->webroot.'uploads'.DS.'preview_reports'.DS;
                                      $image_path  = $this->request->webroot.'uploads'.DS.'preview_reports'.DS.'thumb'.DS.'sm'.DS;
                                      $pdf_file = $report_path.$report['pdf'];
                                      $image_file = $image_path.$report['image'];
                              ?>
                                      <div class="et_pb_gallery_item et_pb_grid_item et_pb_bg_layout_light">
                                        <div class="et_pb_gallery_image portrait">
                                          <a href="<?php echo $pdf_file; ?>" title="<?php echo $title; ?>" target="_blank">
                                            <?php echo '<br /><img src="'.$image_file.'" alt="No image available" />'; ?>
                                            <span class="et_overlay"></span>
                                          </a>
                                        </div>
                                      </div>
                              <?php
                                    }
                                  }
                                }
                              ?>
                              <!-- .et_pb_gallery_items -->
                            </div>
                          </div>
                      <?php
                        } else {
                          echo $this->Form->hidden('category_id',     ['value' => $data['category_id']] );
                          echo $this->Form->hidden('delivery_option', ['value' => $deliveryOption['id'], 'id' => 'delivery_opt'] );
                        }
                      ?>
                        <div class="et_pb_text et_pb_module et_pb_bg_layout_light et_pb_text_align_left  et_pb_text_2">
                          <?php echo $data['description']; ?>
                        </div>


                        
                        
                          <?php //if( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' ) {
                            if(strtolower($category) == 'software' || strtolower($category) == 'software-bundle') {
                              echo '<div>'.$this->Html->link(ucwords(__('support')), ['controller' => 'support-tickets','action' => 'index'], ['class' => 'btn btn-red support-ticket-link']).'</div>';
                          } //}?>
                          <?php if (!empty($testimonialsList) && is_array($testimonialsList)) { ?>
                            <div class="testimonial-popup" style="display: none;">
                              <div class="modal" id="demo">
                                <div class="modal-container">
                                  <a href="#modal-close" id="close-popup">
                                    <?= $this->Html->image('cross-img.png', ['title' => 'Close']); ?>
                                  </a>
                                  <div class="testimonial-inner">
                                  <?php //if (!empty($testimonialsList) && is_array($testimonialsList)) { ?>
                                    <div id="testimonial-title"><?= __('Read what users of').' '.$data['name'].' '.__('say'); ?></div>
                                    <?php foreach ($testimonialsList as $key => $value) { ?>
                                      <div>
                                        <span id="left-quote">
                                            <?= $this->Html->image('../images/open-quot.png', ['width' => '18px', 'style' => ['border : none; box-shadow : none;']]); ?>
                                        </span>
                                        <p>
                                            <?= strip_tags(stripslashes($value['content'])); ?>
                                        </p>
                                        <span id="right-quote">
                                            <?= $this->Html->image('../images/close-quot.png', ['width' => '18px', 'style' => ['border : none; box-shadow : none; float : right; margin-top : -15px;']]); ?>
                                        </span>
                                        <p class="testimonial-user-details">
                                            <b>
                                                <?php
                                                    $userdetail = ucwords(stripslashes($value['first_name'].' '.$value['last_name']));
                                                    if (!empty($value['user_profile'])) {
                                                        $userdetail .= ', '.stripslashes($value['user_profile']);
                                                    }
                                                    if (!empty($value['website'])) {
                                                        $userdetail .= ' at '.stripslashes($value['website']);
                                                    }
                                                    echo $userdetail;
                                                ?>
                                            </b>
                                        </p>
                                      </div>
                                      <hr>
                                  <?php } //} ?>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <?php
                            //if( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' ) {
                              /*if($this->request->session()->read('locale') == 'da' || $this->request->session()->read('locale') == 'dk'){
                                $rNameExploded = explode(' ', strtolower($data['name']));
                                $rNameSliced = array_slice($rNameExploded, 0, -1);
                                $rname = ucfirst(implode(" ", $rNameSliced).' rapporten');
                              } else {
                                $rname = $data['name'];
                              }*/
                            /*} else {
                              $rname = $data['name'];
                            }*/
                            ?>
                            <a href="#demo" id="demotest"><?= __('Read what users of').' '.$data['name'].' '.__('say'); ?></a>
                          <?php } ?>
                        



                        <!-- .et_pb_text -->
                        <!-- .et_pb_testimonial -->
                        <!-- .et_pb_text -->
                        <div class="et_pb_text et_pb_module et_pb_bg_layout_light product_well no_para et_pb_text_4">
                          <h3>
                            <?php echo $data['name']; ?>
                          </h3>
                          <?php echo $this->Form->hidden('type', ['value' => $type, 'id' => 'type']); ?>
                          <p><strong id="strPrice"><?php echo $this->Custom->removeSpace($data['product_price'],$locale); ?> </strong><em>(<?= __('Inclusive VAT');?>)</em></p>
                          <?php
                            if(strtolower(trim($type)) == 'shareware') {
                              $btnText = "Buy/Register". $type;
                            } elseif(strtolower(trim($type)) == 'cd') /*elseif(trim($type) == 'software-cd')*/ {
                              $btnText = "Buy Software". $type;
                            } else {
                              $btnText = 'Buy Now';
                            }
                            echo $this->Form->submit(__($btnText), ['class' => 'btn btn-red btn-text', 'id' => 'btnSubmitBottom']); 
                            echo $this->Form->end(); 
                          ?>
                        </div>
                        <hr>
                        <!-- .et_pb_text -->
                        <?php if($category == 'software'):?>
                          <!-- Bundle products starts from here-->
                          <!-- This section is used for listing on software product page -->
                          <div style="height: 127px; padding-top: 20px;">
                            <p>
                              <h3 style="text-align: center;"><?= __('BUY AS A COMBO');?></h3>
                              <h4 style="color: #e4165b; text-align: center;"> <?= __('Astrology software packages- Save up to');?> <span style="font-size:30px">25%</span></h4>
                            </p>
                          </div>
                          <div class="software-bundle">
                            <?php
                              if(strtolower(trim($type)) == 'cd') {
                                $categoryType      = SOFTWARE_CD;
                                $bundleProductType = 'software-cd';
                              } elseif(strtolower(trim($type)) == 'shareware') {
                                $categoryType      = SHAREWARE;
                                $bundleProductType = 'shareware';
                              }
                            ?>
                            <?php $bundleProducts = $this->Custom->getBundleProducts('software-bundle', $categoryType, $data['id']); ?>
                            <?php foreach($bundleProducts as $bProduct) { ?>
                              <div class="et_pb_text et_pb_module et_pb_bg_layout_light product_well no_para et_pb_text_4">
                                <h3>
                                  <?php echo $bProduct['name']."<span class = 'type' style='font-size : 22px'></span>"; ?>
                                </h3>
                                <?php echo $this->Form->hidden('type', ['value' => $type, 'id' => 'type']);
                                  $page_locale = ($locale == 'da') ? 'dk/' : ''; 
                                  echo $this->Html->link( __('Buy Software CD'), Router::url('/'.$page_locale.__('astrology-software').'/'. $bProduct['seo_url']. '/'.SOFTWARE_CD_SLUG , true), ['class' => 'btn btn-red btn-text']);
                                  // echo $this->Html->link(__('Buy Software CD'), ['controller' => 'Products', 'action' => 'detail', $bProduct['seo_url'],SOFTWARE_CD_SLUG], ['class' => 'btn btn-red btn-text']);
                                ?>
                                <br/>
                                <?php
                                  echo $this->Html->link( __('Buy/Register shareware'), Router::url('/'.$page_locale.__('astrology-software').'/'. $bProduct['seo_url']. '/'.SHAREWARE_SLUG , true), ['class' => 'btn btn-red btn-text']);
                                  // echo $this->Html->link(__('Buy/Register shareware'), ['controller' => 'Products', 'action' => 'detail', $bProduct['seo_url'],SHAREWARE_SLUG], ['class' => 'btn btn-red btn-text']);
                                ?>
                              </div>
                            <?php } ?>
                            <!-- .et_pb_text -->
                            <!-- This section is used for software bundle page -->
                            <?php elseif($category == 'software-bundle'):?>
                              <div class="software-bundle" style="padding-top:36px">
                                <?php
                                  if(strtolower(trim($type)) == 'cd') {
                                    $categoryType = SOFTWARE_CD;
                                    $bundleProductType = 'software-cd';
                                  } elseif(strtolower(trim($type)) == 'shareware') {
                                    $categoryType = SHAREWARE;
                                    $bundleProductType = 'shareware';
                                  }
                                ?>
                                <?php $bundleProducts = $this->Custom->getBundleProducts('software-bundle', $categoryType, $data['id']);?>
                                <?php foreach($bundleProducts as $bProduct) { ?>
                                  <div class="et_pb_text et_pb_module et_pb_bg_layout_light product_well no_para et_pb_text_4">
                                    <h3>
                                      <?php echo $bProduct['name']."<span class = 'type' style='font-size : 22px'>".$type."</span>"; ?>
                                    </h3>
                                    <?php echo $this->Form->hidden('type', ['value' => $type, 'id' => 'type']);?>
                                    <strong id="bprice<?php echo $bProduct['id']; ?>">
                                      <?php
                                        if( $bProduct['product_prices']['discount_total_price']  == 0 ) {
                                          echo $bProduct['currency']['symbol'].$bProduct['product_prices']['total_price'];
                                        } else {
                                          echo $bProduct['currency']['symbol'].$bProduct['product_prices']['discount_total_price']; 
                                        }
                                      ?>
                                    </strong>
                                    <span>
                                      <?php
                                        //if( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' ) {
                                        if ($this->request->session()->read('locale') == 'da') {
                                          echo $this->Html->link( __('View Details'), Router::url('/dk/'.__('astrology-software').'/'. __d('default', $bProduct['seo_url']). '/'.__d('default', $bundleProductType), true), ['class' => 'btn btn-red btn-text', 'id' => 'blink'.$bProduct['id']]);
                                        } else {
                                          echo $this->Html->link( __('View Details'), Router::url('/'.__('astrology-software').'/'. __d('default', $bProduct['seo_url']). '/'.__d('default', $bundleProductType), true), ['class' => 'btn btn-red btn-text', 'id' => 'blink'.$bProduct['id']]);
                                        }
                                        //echo '<br> SEO URL => '.$bProduct['seo_url'].'<br/>';
                                        //echo $this->Html->link(__('View Details'), /*['controller' => 'users', 'action' => 'login']*/['controller' => 'products', 'action' => 'detail', $bProduct['seo_url'], $bundleProductType, $bProduct['id']], ['class' => 'btn btn-red btn-text', 'id' => 'blink'.$bProduct['id']]);
                                        /*} else {
                                          echo $this->Html->link(__('View Details'), ['controller' => 'Products', 'action' => 'detail', $bProduct['seo_url'],$bundleProductType, $bProduct['id']], ['class' => 'btn btn-red btn-text', 'id' => 'blink'.$bProduct['id']]);
                                        }*/
                                      ?>
                                    </span>
                                  </div>
                                <?php } ?>
                              <?php endif;?>
                            </div>
                            <!-- End Here-->
                          </div>
                          <!-- .et_pb_column -->
                          <!-- </em> -->
                          <?php /* if( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' ) { ?>
                          <div class="testimonialSection">
                            <h3 style="color: #e4165b;"><em>Testimonials</em></h3>
                            <div>
                              <?php
                                if (!empty($testimonials) && is_array($testimonials)) {
                                  $count = count($testimonials);
                                  foreach ($testimonials as $key => $value) {
                                    if ($key > 1) {
                                      break;
                                    }
                              ?>
                                    <ul>
                                      <li>
                                        <span>
                                          <?= $this->Html->image('../images/open-quot.png', ['width' => '25px', 'style' => ['border : none; box-shadow : none;']]); ?>
                                        </span>
                                        <div class="clear"></div>
                                        <div style="margin-left:25px;margin-right:38px; margin-top:-9px;">
                                          <?= $value['content']; ?>
                                        </div>
                                        <span>
                                          <?= $this->Html->image('../images/close-quot.png', ['width' => '25px', 'style' => ['border : none; box-shadow : none; float : right; margin-top : -15px;']]); ?>
                                        </span>
                                        <div class="clear"></div>
                                        <strong style="float:right;font-size:14px;color: #e4165b;">
                                          <?php $userdetail = ucwords($value['first_name'].' '.$value['last_name']);
                                              if (!empty($value['user_profile'])) {
                                                $userdetail .= ', '.$value['user_profile'];
                                              }
                                              if (!empty($value['website'])) {
                                                $userdetail .= ' at '.$value['website'];
                                              }
                                              echo $userdetail;
                                          ?>
                                        </strong>
                                      </li>
                                    </ul>
                                  <?php
                                  }
                                  if ($count > 2) {
                                    if ($this->request->session()->read('locale') == 'da') { ?>
                                      <a target = '_blank' href="<?= Router::url('/', true).'dk/bruger-bedømmelse/'.__($this->request->params['pass'][0]); ?>"><?= __('View more'); ?></a>
                                    <?php } else { ?>
                                      <a target = '_blank' href="<?= Router::url('/', true).'user-testimonial/'.__($this->request->params['pass'][0]); ?>" ><?= __('View more'); ?></a>
                                    <?php }
                                  }
                                }
                              ?>
                            </div>
                          </div>
                          <?php } */ ?>

                        </div>
                      </div>
                      <!-- <em style="text-align:left;"> -->
                      <!-- .et_pb_row -->
                      <!-- </em> -->
                    </div>
                  </div>
                  <?php
                    if($category == 'reports'):
                      echo $this->element('products/other_reports');
                    endif;
                    if($category == 'software'):
                      echo $this->element('products/our_software');
                    endif;
                    if(!$user = $this->request->session()->read('user_id')):
                      echo $this->element('newsletter');
                    endif;
                  ?>
                </div>
              </div>
            </div>
          </div>
          <?php
            if(strtolower($category) ==  'reports' )
         	    echo "</div>";
          ?>
        </div>
      </div>
    </div>
</article>

<style type="text/css">
  p {
  margin-top: 0;
}

.modal-container {
  position: fixed;
  background-color: #fff;
  width: 70%;
  max-width: 400px;
  left: 50%;
  padding: 20px;
  border-radius: 5px;

  -webkit-transform: translate(-50%, -200%);
  -ms-transform: translate(-50%, -200%);
  transform: translate(-50%, -200%);

  -webkit-transition: -webkit-transform 200ms ease-out;
  transition: transform 200ms ease-out;
}

.modal:before {
  content: "";
  position: fixed;
  display: none;
  background-color: rgba(0,0,0,.8);
  top: 0;
  left: 0;
  height: 100%;
  width: 100%;
}

.modal:target:before {
  display: block;
}

.modal:target .modal-container {
  top: 20%;

  -webkit-transform: translate(-50%, 0%);
  -ms-transform: translate(-50%, 0%);
  transform: translate(-50%, 0%);
}

#modal-close {}
</style>

<script type="text/javascript">
  /*$(document).ready(function () {
  });*/
  $('#demotest').on('click', function () {
    $('body').addClass('height-scroll');
    $('.testimonial-popup').css('display', 'block');
      if (screen.width < 768) {
        $('#main-header').css('z-index', '9');
      } else {
        $('#main-header').css('z-index', '999999');
      }
  });
  $('#close-popup').on('click', function () {
    $('body').removeClass('height-scroll');
    $('.testimonial-popup').css('display', 'none');
    $('#main-header').css('z-index', '999999');
  });
</script>