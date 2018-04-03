<?php use Cake\Cache\Cache;?>
<?php use Cake\Routing\Router; ?>
<?= $this->Html->script ('AC_RunActiveContent'); ?>
<?php 
if( isset($error)  && !empty($error) ):

 echo "<div class='page-error'><div class='error-text'>".__d('default', $error)."</div></div>";

else:

/*if( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' ) {
    pr($page); die;
}*/

     			$page['body'] = str_replace("{LAYOUT_IMAGES_URL}", $this->request->webroot."layout" , $page['body']);
				$page['body'] = str_replace("{TERM_OF_USE}", $this->Html->link('terms of use', [ 'controller' => 'Pages', 'action' => 'menu-pages', 'terms-of-use']) , $page['body']);

                 if(!$this->request->session()->read('user_id'))
                 {
                 	$link = "<div class='well'><h3>".__('Sign up for free and get your free astropage')."</h3>&nbsp;&nbsp;<p> 
                     ".$this->Html->link(__('SIGN UP'), ['controller' => 'Users', 'action' => 'sign-up'], ['class'=> 'btn btn-red'])."</p></div>";
                     $newsletter = $this->element('newsletter'); 
                 }
                 else
                 {
                    $link = "";
                    $newsletter = "";
                 }

                if(strpos($page['body'], '{BOOKS}') !== false) 
                 {
                    $booksSection = $this->element('books');   
                 }
                 else
                 {
                    $booksSection = "";
                 }

                 if(strpos($page['body'], '{OUR_REPORTS}') !== false) 
                 {
                    $reportsSection = $this->element('products/our_reports');   
                 }
                 else
                 {
                    $reportsSection = "";
                 }

                 if(strpos($page['body'], '{OUR_SOFTWARES}') !== false) 
                 {
                    $softwareSection = $this->element('products/our_software');   
                 }
                 else
                 {
                    $softwareSection = "";
                 }

                if( strpos($page['body'], '{ORDER_CONSULTATION}') !== false )
                {
                    $orderSection = $this->element('order_consultation');
                }
                else
                {
                    $orderSection = "";
                }



                 $page['body'] = str_replace('{ORDER_CONSULTATION}', $orderSection, $page['body']);

                 $page['body'] = str_replace('{OUR_SOFTWARES}', $softwareSection , $page['body']);
                 $page['body'] = str_replace('{OUR_REPORTS}', $reportsSection , $page['body']);
                 $page['body'] = str_replace('{BOOKS}', $booksSection , $page['body']);
                 $page['body'] = str_replace('{SIGN_UP}', $link , $page['body']);
                 $page['body'] = str_replace('{NEWSLETTER}', $newsletter , $page['body']);

   				 echo  "<div class='pages_center'>".$page['body']."</div>";

endif;

if (isset($pageName) && !empty($pageName)) { 
    if($pageName == 'meditation') { 
?>
    <script src="/audiojs/audio.min.js"></script>
    <script>
      audiojs.events.ready(function() {
        var as = audiojs.createAll();
      });
    </script>
<?php }
    if($pageName == 'free-astroclock-ios-app') { 
?>
   
    <link rel="stylesheet" href="/owl/owl.carousel.min.css">
    <link rel="stylesheet" href="/owl/theme.default.css">
    <script src="/owl/owl.carousel.min.js"></script>
    <script>
    $(document).ready(function() {
      var owl = jQuery('.owl-carousel');
      owl.owlCarousel({
        margin: 10,
        nav: true,
        loop: true,
        responsive: {
          0: {
            items: 1
          },
          600: {
            items: 2
          },
          1000: {
            items: 3
          }
        }
      })
    })
    </script>
<?php
    }
}
?>
