<?php 
        $order = $this->request->session()->read('Order');
        $seo_url = $order['seo_url'];
        if($seo_url == 'comprehensive-lovers-report' || $seo_url == 'astrologi-og-parforhold-rapport')
        {
          echo $this->Element('products/lovers_form');  
        }
        else
        {
            echo $this->Element('products/other_forms');
        }

?>

