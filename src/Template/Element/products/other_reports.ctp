<?php use Cake\Cache\Cache; ?>
<?php use Cake\I18n\I18n; ?>
<?php use Cake\Routing\Router;?>
<?php $locale = I18n::locale(); ?>


<?php
    $page_locale = ($locale == 'da') ? 'dk/' : ''; 
?>
<div class="our_reports_section">

<div class="et_pb_section  et_pb_section_1 et_pb_with_background et_section_regular" style="background-color: #e5e5ed;">
<div class=" et_pb_row et_pb_row_1">
<div class="et_pb_column et_pb_column_4_4  et_pb_column_2">
<div class="et_pb_text et_pb_module et_pb_bg_layout_light et_pb_text_align_center  et_pb_text_5">
<h2><?= __('OTHER REPORTS');?></h2>
<?= __('Designed and written by astrologer Adrian Ross Duncan, our reports was designed to give you a fully immersive experience of astrology and of your own personal horoscope. We believe that astrology should be presented in an easily accessible way.');?>
<br><br>
</div>
<!-- .et_pb_text -->
</div>
<!-- .et_pb_column -->
</div>
<!-- .et_pb_row -->
<div class=" et_pb_row et_pb_row_2 other_report_sec">
 <?php

  if(isset($this->request->query) && !empty($this->request->query) )
  {
       $order_id = explode( '-', $this->request->query('orderid') );
       $product = $order_id[1];
  }
  else
  {
      $product  = $current_product_id ;
  }

  $reportType = '';
  if ($this->request->session()->read('Auth.User.role') == 'elite') 
  {
      $reportType = 'elite-full-report';
  } else {
      $reportType = 'full-reports';
  }
          $other_reports = $this->Report->getOtherReports($product);
          foreach($other_reports as $other_report):
            /*
             * Removed lovers report from report listing page for elite member
             * Created By : Krishan Gupta
             * Created Date : March 22, 2017
             */
            // if ( !empty($this->request->session()->read('Auth.User.role')) && ($this->request->session()->read('Auth.User.role') == 'elite') && ($other_report['id'] == 5)) {
            //     continue;
            // }
            // END
 ?>
<div class="et_pb_column et_pb_column_1_3 et_pb_column_3 view_detail">
<div class="et_pb_module et-waypoint et_pb_image et_pb_animation_top et_pb_image_1 et_always_center_on_mobile et-animated">

<?php
      //if( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' ) {
        echo $this->Html->link( $this->Html->image("/uploads/products/".$other_report['image'], ['alt' => __($other_report['name'])]) , Router::url('/'.$page_locale.__('astrology-reports').'/'. __d('default', $other_report['seo_url']). '/'.__($reportType), true), ['escape' => false]);
      /*} else {
        echo $this->Html->link( $this->Html->image("/uploads/products/".$other_report['image']) , Router::url('/'.$page_locale.__('astrology-reports').'/'. __d('default', $other_report['seo_url']). '/'.__($reportType) , true), ['escape' => false]);
      }*/
      //echo $this->Html->image("/uploads/products/".$other_report['image'], [ 'url' => ['controller' => 'Products', 'action' => 'detail',  $other_report['seo_url'],  $reportType,  $other_report['id']] ,  'alt' => '']);
?>
</div>
<div class="et_pb_text et_pb_module et_pb_bg_layout_light et_pb_text_align_center  et_pb_text_6">
<h4><?php echo $other_report['name'] ?></h4>
<p>
<strong><?php echo $other_report['currency']['symbol'].$other_report['product_prices']['total_price']?></strong>                        <br>
<?php 


 // echo $this->Html->link(__('View Details'), ['controller' => 'Products', 'action' => 'detail',  $other_report['seo_url'], $reportType, $other_report['id'] ], ['class' => 'btn btn-red']);
   
 echo $this->Html->link(__('View Details') , Router::url('/'.$page_locale.__('astrology-reports').'/'. __d('default', $other_report['seo_url']). '/'.__('full-reports') , true) ,['class' => 'btn btn-red']);

 ?>
</p>
</div>
<!-- .et_pb_text -->
</div>
<?php endforeach;?>

</div>
<!-- .et_pb_row -->
</div>
</div>