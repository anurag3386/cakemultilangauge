<?php use Cake\Routing\Router;?>
<!-- Below is the Bing Ads UET tag tracking code Start -->
<img src="//bat.bing.com/action/0?ti=5668891&Ver=2" height="0" width="0" style="display:none; visibility: hidden;" />
<!-- Below is the Bing Ads UET tag tracking code End -->
<?php 
//pr($meta);
/*if( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' ) {
      
    }*/

//pr($this->request->params);
  /* To show dynamic meta keywords */
  if( isset($page) && !empty($page)) {
    echo "<title>".$page['meta_title']."</title>\n";
    //echo $this->Html->meta('title', $page['meta_title'] )."\n";
    echo $this->Html->meta('keywords', $page['meta_keywords'] )."\n";
    echo $this->Html->meta('description', $page['meta_description'] )."\n";
    $dynamicPagesDanish = array ('free-astropage' => 'gratis-astropage', 'consultation' => 'konsultation', 'biography' => 'biografi', 'about-astrology' => 'om-astrologi', 'about-astrowow' => 'om-astrowow', 'privacy-policy' => 'fortrolighedspolitik', 'terms-of-use' => 'betingelser-for-brug', 'contact-us' => 'kontakt-os');
    if (array_key_exists(strtolower(trim($page['seo_url'])), $dynamicPagesDanish)) {
      $page['seo_url_da'] = $dynamicPagesDanish[strtolower(trim($page['seo_url']))];
    } else {
      $page['seo_url_da'] = $page['seo_url'];
    }
    ?>
    <link rel="canonical" href="<?= Router::url($this->request->here, true); ?>"/>
    <link rel="alternate" hreflang="en" href="<?= Router::url('/', true).$page['seo_url']; ?>"/>
    <link rel="alternate" hreflang="da-dk" href="<?= Router::url('/', true).'dk/'.$page['seo_url_da']; ?>"/>
<?php } elseif(isset( $meta ) && !empty($meta)) { ?>
    <title><?= $meta['title'];?></title>
    <meta name="description" content="<?= $meta['description']?>" />
    <?php if(isset($meta['keywords']) ){?>
    <meta name="keywords" content="<?= $meta['keywords']?>" />
    <?php }?>
<?php
  // this is use dto set canonical
  if( isset($canonical)  && !empty($canonical) ) {
    //if( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' ) {
    $headerLang = $this->request->session()->read('locale');
    if ( (strtolower(trim($headerLang))) == 'da' ) {
      $canonicalUrl = $canonical['da'];
    } else {
      $canonicalUrl = $canonical['en'];
    } ?>
    <link rel="canonical" href="<?= $canonicalUrl; ?>"/>
    <?php /*} else { ?>
      <link rel="canonical" href="<?= Router::url($this->request->here, true); ?>"/>
      <?php }*/ ?>
      <!-- Hreflang Tag & Canonical -->
      <link rel="alternate" hreflang="en" href="<?= canonicalAndHrefLang($canonical['en']); ?>"/>
      <link rel="alternate" hreflang="da-dk" href="<?= canonicalAndHrefLang($canonical['da']); ?>"/>
      <!-- Hreflang Tag & Canonical -->
    <?php
  } else { ?>
    <link rel="alternate" hreflang="en" href="<?= Router::url($this->request->here, true); ?>"/>
    <link rel="alternate" hreflang="da-dk" href="<?= Router::url($this->request->here, true); ?>"/>
  <?php }
} else { ?>
    <title>Astrowow.com - Astrology, free horoscopes, sun sign, Astrology reading, Astrology software</title>
    <meta name="description" content="Astrowow.com provides free horoscopes, Astrology reading, Astrologer, Astrology Software, Astrology prediction, Daily, weekly, monthly horoscopes for aries, taurus, gemini, cancer, leo, virgo, libra, scorpio, sagittarius, capricorn, aquarius, pisces, sun sign" />
    <meta name="keywords" content="Free Astrology, Horoscope, Horoscope Reading,  Aries, taurus, gemini, cancer, leo, virgo, libra, scorpio, sagittarius, capricorn, aquarius, pisces,  daily horoscope, weekly horoscope, monthly horoscope,online astrology" />
    <link rel="canonical" href="<?= Router::url($this->request->here, true); ?>"/>
    <link rel="alternate" hreflang="en" href="<?= Router::url($this->request->here, true); ?>"/>
    <?php //if( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' ) { ?>
      <link rel="alternate" hreflang="da-dk" href="<?php echo Router::url($this->request->here, true); ?>"/>
    <?php /*} else { ?>
      <link rel="alternate" hreflang="da-dk" href="<?= Router::url($this->request->here, true); ?>"/>
    <?php }*/ ?>
    
<?php } ?>

<?php /*if (!empty($purchaseEvent)) {
  echo $purchaseEvent;
} else*///if (!empty($this->request->session()->read('purchaseEvent'))) {
  /*if( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' ) {
    echo $this->request->params['action']; //die;//pr($this->request->session()->read('purchaseEvent'));
  }*/
  $controllerAndActionArr = ['thanks', 'thankYouFreeTrial', 'softwareThankYou', 'consultationThankYou', 'thankYou', 'thankYouForReportPurchase'];
  ?>
  <?php if (!empty($this->request->session()->read('purchaseEvent'))) { ?>
    <!-- Purchase Event Code -->
    <script>fbq('track', 'Purchase', {value: '<?php echo $this->request->session()->read("purchaseEvent.Price"); ?>', currency: '<?php echo $this->request->session()->read('purchaseEvent.Currency'); ?>' });</script>
    <!-- Purchase Event Code END -->
    <?php
      $selectedLocale = !empty($this->request->session()->read('locale')) ? $this->request->session()->read('locale') : 'en';
      if($selectedLocale == 'da') {
        $selectedLocale = 'dk';
      }
    ?>
    <!-- Google Code for AstroWOW Report Orders Conversion Page -->
    <script type="text/javascript">
      var google_conversion_id = '1072504019';
      var google_conversion_language = "<?php echo $selectedLocale; ?>";
      var google_conversion_format = "3";
      var google_conversion_color = "ffffff";
      var google_conversion_label = "4aVkCL-U-G8Q07m0_wM";
      var google_conversion_value = "<?php echo $pr = !empty($this->request->session()->read('purchaseEvent.Price')) ? $this->request->session()->read('purchaseEvent.Price') : 0.00; ?>";
      var google_conversion_currency = "<?php echo $cur = !empty($this->request->session()->read('purchaseEvent.Currency')) ? $this->request->session()->read('purchaseEvent.Currency') : 'USD'; ?>";
      var google_remarketing_only = false;
    </script>
    <script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js"></script>
    <noscript>
      <div style="display:inline;">
        <img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/1072504019/?value=<?php echo $pr;//echo $this->request->session()->read("purchaseEvent.Price"); ?>&amp;currency_code=<?php echo $cur;//echo $this->request->session()->read('purchaseEvent.Currency'); ?>&amp;label=4aVkCL-U-G8Q07m0_wM&amp;guid=ON&amp;script=0"/>
      </div>
    </noscript>
    <!-- Google Code for AstroWOW Report Orders Conversion Page End -->


    <!-- Bing Conversion Code(Apply in all thank you pages) Start -->
    <script>(function(w,d,t,r,u){var f,n,i;w[u]=w[u]||[],f=function(){var o={ti:"5668891"};o.q=w[u],w[u]=new UET(o),w[u].push("pageLoad")},n=d.createElement(t),n.src=r,n.async=1,n.onload=n.onreadystatechange=function(){var s=this.readyState;s&&s!=="loaded"&&s!=="complete"||(f(),n.onload=n.onreadystatechange=null)},i=d.getElementsByTagName(t)[0],i.parentNode.insertBefore(n,i)})(window,document,"script","//bat.bing.com/bat.js","uetq");</script>
    <noscript><img src="//bat.bing.com/action/0?ti=5668891&Ver=2" height="0" width="0" style="display:none; visibility: hidden;" /></noscript>
    <script>
      window.uetq = window.uetq || [];
      window.uetq.push({ 'gv': <?= $pr; ?>, 'gc': "<?= $cur; ?>"});
    </script>
    <!-- Bing Conversion Code(Apply in all thank you pages) End -->
  <?php $this->request->session()->delete('purchaseEvent'); } ?>


<?php if ( (strtolower($this->request->params['controller']) == 'pages') && (strtolower($this->request->params['action']) == 'index')) { ?>
  <meta name="msvalidate.01" content="30D9EF15C0B07EEC91D6EBD116060E62" />
<?php } ?>

  <!-- Facebook Pixel Code -->
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window,document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', 
'141535063043889'); 
fbq('track', 
'PageView');
</script>
<noscript>
<img 
height="1" width="1" 
src="https://www.facebook.com/tr?id=141535063043889&ev=PageView
&noscript=1"/>
</noscript>
<!-- End Facebook Pixel Code -->

<!--Lucky Orange Code -->
<script type='text/javascript'>
window.__lo_site_id = 80619;
 
 (function() {
  var wa = document.createElement('script'); wa.type = 'text/javascript'; wa.async = true;
  wa.src = 'https://d10lpsik1i8c69.cloudfront.net/w.js';
  var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(wa, s);
   })();
 </script>
<!--Lucky Orange Code -->


 <script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-37322831-1', 'auto');
  ga('send', 'pageview');

</script>

<!-- <script src='https://www.google.com/recaptcha/api.js'></script> -->

<?= $this->Html->meta('favicon.ico','/favicon.ico',  ['type' => 'icon'])."\n";?>
<?php
         echo $this->Html->charset()."\n";
         echo $this->Html->css(['css_002','custom','style_002','shortcodes','shortcodes_responsive','all', 'mediaelementplayer.min.css', 'responsiveTabs', 'jquery.bxslider', 'responsive-tabs.css']);
?>

<?php if( isset($sunsigns) && count($sunsigns) == 1 ):?>

<meta property="og:title" content="FreeHoroscope -  <?php echo ucwords($sunsigns->name); ?>"/>
<!-- <meta property="og:image" content="<?php //echo Router::url('/',true).'uploads/sunsigns/'.$sunsigns->avatar ?>"/> -->

<?php
  if ($_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12') {
        //pr($SunSignPredictions);die;
   
  }
?>

<!-- <meta property="og:image" content="http://astro-new.newsoftdemo.info/img/test.png"/> -->
<!-- <meta property="og:image" content="https://atstrowow.com/img/astrowow_app.png"/> -->
<!-- <meta property="og:image" content="https://www.planwallpaper.com/static/images/2ba7dbaa96e79e4c81dd7808706d2bb7_large.jpeg"/> -->
<meta id="img" property="og:image" content="<?= Router::url('/', true).'img/social-sunsign-icon/'.strtolower($sunsigns->name).'.jpg' ?>"/>
<meta id="des" property="og:description" content="<?php echo strip_tags($SunSignPredictions->prediction);?>"/>
<meta id="content" property="og:url" content="<?= Router::url($this->request->here, true); ?>"/>

<meta porperty="og:image:width" content="400"/>
<meta porperty="og:image:height" content="300"/>
<?php endif;?>

  <link rel="stylesheet" href="<?php echo $this->request->webroot; ?>plugins/timepicker/bootstrap-timepicker.min.css">
 <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
 <link rel="stylesheet" href="<?php echo $this->request->webroot; ?>plugins/validation/validationEngine.jquery.css">


  <script src="<?php echo $this->request->webroot?>plugins/jQuery/jquery-2.2.3.min.js"></script>
  <script src="<?php echo $this->request->webroot; ?>plugins/timepicker/bootstrap-timepicker.min.js"></script>
  <script src="<?php echo $this->request->webroot?>js/jquery.bxslider.js"></script>
  
  <script src="<?php echo $this->request->webroot?>js/jquery.multipurpose_tabcontent.js"></script>

  <script src="<?php echo $this->request->webroot?>js/mediaelement-and-player.min.js"></script>
  <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
  <!-- Date Picker -->
<link rel="stylesheet" href="<?php echo $this->request->webroot; ?>plugins/datepicker/datepicker3.css">
  <!-- Daterange picker -->
<link rel="stylesheet" href="<?php echo $this->request->webroot; ?>plugins/daterangepicker/daterangepicker.css">



<?php
  function canonicalAndHrefLang ($data) {
    $returnData = explode('#', $data);
    return trim($returnData[0]);
  }

  /*function danishHrefLang ($paramsData, $currentURL) {
    $danishAction = from_camel_case($paramsData['action']);
    $danishURL = Router::url('/', true).'dk/'.__(strtolower($paramsData['controller'])).'/'.__($danishAction);
    if (!empty($paramsData['pass'])) {
      
    }
    echo $danishURL; die;
  }

  function from_camel_case ($input) {
    preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $input, $matches);
    $ret = $matches[0];
    $customAction = '';
    foreach ($ret as $values) {
      $customAction .= strtolower($values).'-';
    }
    return rtrim($customAction,"-");
  }*/
?>