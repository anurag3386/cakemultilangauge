<?php

namespace App\Controller;
use App\Controller\AppController;
use Cake\Routing\Router;
use Cake\Mailer\Email;
use Cake\I18n\I18n;
use Cake\Cache\Cache;


class EmailTemplatesController extends AppController
{
    public function initialize()
     {
       parent::initialize();
       if ($this->request->session()->read('locale') == 'en') 
       {
           I18n::locale('en_US');
       }
       elseif ($this->request->session()->read('locale') == 'da')
       {
          I18n::locale('da');   
       }
       $this->loadModel('Products');
       $this->loadModel('Categories');

     }

	private function replaceTemplateVariables($data)
	{
			$data = str_replace('{IMAGES_URL}', TEMPLATE_IMAGES_URL, $data);
			$data = str_replace('{SITE_URL}', SITE_URL, $data);
			return $data;
	}

    public function sendWelcomeEmailOnSignup($recipient, $data) 
    {
			$subject = $data['subject'];
		    $template = $this->getTemplate("welcome_email");
            if($template)
            {
       			$body = html_entity_decode($template['content']);
		    }
            else
            {
           		$body = $data['mailtext'];
    			$body .='<p>Welcome and Thank you for signup with Astrowow.com !</p> ';
				$body .='<p>Now you can sign with below noted username and password</p> ';
				$body .='<p>Username: {USERNAME}</p>';
				$body .='<p>Password: {PASSWORD}</p>';
				$body .='<p>{SITE_URL}</p>';
            }
                $body = str_replace('{PASSWORD}', $data['password'], $body);
				$body = str_replace('{USERNAME}', $data['username'], $body);
				$body = str_replace('{NAME}', $data['name'], $body);
                $body = str_replace('{FACEBOOK}', FACEBOOK, $body);
                $body = str_replace('{TWITTER}', TWITTER, $body);
                $body = str_replace('{LINKEDIN}', LINKEDIN, $body);
	            $body = $this->replaceTemplateVariables($body);
	     		return $this->sendMail($recipient, $subject, $body);
    }

    public function sendReportsOrderEmail($recipient, $data) 
    {

            $locale = $this->request->session()->read('locale');
            $page_locale = ($locale == 'da') ? 'dk/' : ''; 

            $subject = $data['subject'];
            $template = $this->getTemplate("order_confirmation_for_reports");

            if($template)
            {
                $body = html_entity_decode($template['content']);
            }
            $body = str_replace('{PRODUCT_NAME}', $data['product_name'], $body);
            $body = str_replace('{NAME}', $data['name'], $body);
            
            $body = str_replace('{FACEBOOK}', FACEBOOK, $body);
            $body = str_replace('{TWITTER}', TWITTER, $body);
            $body = str_replace('{LINKEDIN}', LINKEDIN, $body);


            $body = $this->replaceTemplateVariables($body);
            $productId = $data['product_id'];
            $products  = $this->Products->find('translations')
                                        ->contain(['Categories'])
                                        ->where(['Products.id NOT IN' => [$productId, 23], 'Products.status' => 1, 'Categories.slug' => 'reports'])
                                        ->select(['Products.name', 'Products.image', 'Products.seo_url', 'Products.id'])-> toArray();
          
            $links = "";
            $len = count($products);
            $i = 0;
            foreach($products as $product)
            {
                ++$i;
            $links.= "<a href=".SITE_URL.$page_locale.__('astrology-reports')."/".__d('default', $product['seo_url'])."/".__('full-reports')." target='_blank'>".$product['name']."</a>";

             if($i < $len)
             {
                $links.= " - ";
             }
            }

            $body  = str_replace("{OTHER_REPORTS}", $links, $body);
            return $this->sendMail($recipient, $subject, $body);    
    }

    public function OrderConfirmationforSoftwarefreeVersion($recipient, $data) 
    {
            $subject = $data['subject'];
            $template = $this->getTemplate("order_confirmation_for_software_free_version");
            if($template)
            {
                $body = html_entity_decode($template['content']);
            }
            $body = str_replace('{PRODUCT_NAME}', $data['product_name'], $body);
            $body = str_replace('{USERNAME}', $data['username'], $body);
            $body = str_replace('{NAME}', $data['name'], $body);
            $body = str_replace('{EMAIL_ID}', $data['username'], $body);
            $body = str_replace('{CATEGORY}', $data['category'], $body);
            $url  = Router::url(['controller' => 'Products', 'action' => 'detail', $data['seo_url'], $data['product_id']]); 

            $url = SITE_URL.$url;
            $body = str_replace('{BUY_CD_LINK}', $url, $body);
            $body = str_replace('{DOWNLOAD_LINK}', $data['url'], $body);
            $body = $this->replaceTemplateVariables($body);
            return $this->sendMail($recipient, $subject, $body);
    }


    public function  orderConfirmationforSoftwareCD($recipient, $data) 
    {

            $subject = $data['subject'];
            $template = $this->getTemplate("order_confirmation_for_software_cd");
            if($template)
            {
                $body = html_entity_decode($template['content']);
            }
                      
            $body  = str_replace('{PRODUCT_NAME}', $data['product_name'], $body);
            $body  = str_replace('{USERNAME}', $data['username'], $body);
            $body  = str_replace('{NAME}', $data['name'], $body);
            $body  = str_replace('{EMAIL_ID}', $data['username'], $body);
            $body  = $this->replaceTemplateVariables($body);
            $url   = Router::url(['controller' => 'Pages', 'action' => 'menu-pages', 'privacy-policy']);
            $body  = str_replace("{PRIVACY_POLICY}", $url , $body);
            $this->sendMail($recipient, $subject, $body);

    }
    
    // public function  instructionForSoftwareCD($recipient, $data) 
    // {

    //         $subject = $data['subject'];
    //         $template = $this->getTemplate("instruction_for_software_cd");
    //         if($template)
    //         {
    //             $body = html_entity_decode($template['content']);
    //         }
                      
    //         $body  = str_replace('{PRODUCT_NAME}', $data['product_name'], $body);
    //         $body  = str_replace('{USERNAME}', $data['username'], $body);
    //         $body  = str_replace('{NAME}', $data['name'], $body);
    //         $body  = str_replace('{EMAIL_ID}', $data['username'], $body);
    //         $body  = $this->replaceTemplateVariables($body);
    //         $url   = Router::url(['controller' => 'Pages', 'action' => 'menu-pages', 'privacy-policy']);
    //         $body  = str_replace("{PRIVACY_POLICY}", $url , $body);
    //         $this->sendMail($recipient, $subject, $body);

    // }



     public function  orderConfirmationforShareware($recipient, $data) 
    {

            $subject = $data['subject'];
            $template = $this->getTemplate("order_confirmation_for_registered_shareware");
            if($template)
            {
                $body = html_entity_decode($template['content']);
            }
            $body = str_replace('{PRODUCT_NAME}', $data['product_name'], $body);
            $body = str_replace('{USERNAME}', $data['username'], $body);
            $body = str_replace('{NAME}', $data['name'], $body);
            $body = str_replace('{EMAIL_ID}', $data['username'], $body);
            $otherProducts = $this->getOtherProducts($data['category_slug'], $data['product_id']);
       
            $i = 1; 
            foreach($otherProducts as $product)
            {
               $body = str_replace("{SOFTWARE_LINK_$i}", SITE_URL."products/".$product['seo_url']."/".$product['id'], $body);
               $body = str_replace("{SOFTWARE_TEXT_$i}", $product['name'], $body);
               $body = str_replace("{SOFTWARE_IMAGE_$i}", PRODUCT_IMAGES_URL."/".$product['image'], $body);
               ++$i;
            }
            $body = str_replace('{FACEBOOK_LINK}', '', $body);
            $body = $this->replaceTemplateVariables($body);
            $url   = Router::url(['controller' => 'Pages', 'action' => 'menu-pages', 'privacy-policy']);
            $body  = str_replace("{PRIVACY_POLICY}", $url , $body);
            return $this->sendMail($recipient, $subject, $body);
    }


    public function sendMailToAdmin($subject, $body, $loversReport = false)
    {
        if ( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' || $_SERVER['REMOTE_ADDR'] == '47.9.199.112' ) {
            $to = array('gabriela@123789.org', 'kingslay@123789.org', 'k.krishnagupta@gmail.com'/*, 'stanfield@123789.org'*/);
            $cc = 'kingslay@123789.org';
        } else {
    	   $to = 'ard@astrowow.com'; //ADMIN_NAME;
           $cc = 'jette@rybak.dk';
        }
    	$email = new Email();
        if ($loversReport) {
    	    $email->emailFormat('html')
		      ->subject($subject)
		      ->to($to)
              ->cc($cc);
        } else {
            $email->emailFormat('html')
              ->subject($subject)
              ->to($to);
        }
		return $email->send($body);
    }

    private function getTemplate($shortCode)
    {
    	$template = $this->EmailTemplates->find('translations')
    	                    ->where(['EmailTemplates.short_code' => $shortCode])
    	                    ->select(['EmailTemplates.content', 'variables'])
    	                    ->first();
	    if(!empty($template))
	    {
           return $template;
        }
        else
        {
        	return false;
        }
    }

    private function getOtherProducts($categorySlug, $productId)
    {

       $products  = $this->Products->find('all')
                                   ->contain(['Categories'])
                                   ->where(['Products.id !=' => $productId, 'Products.status' => 1, 'Categories.slug' => $categorySlug ])
                                   ->select(['Products.name', 'Products.image', 'Products.seo_url', 'Products.id'])
                                   ->order(['Products.id' => 'DESC' ]);
       if(!$products->isEmpty())
       {
          return $products;
       }
       else
       {
        return false;
       }

    }

   public function sendForgotPasswordEmail($recipient, $data)
   {
     $subject = $data['subject'];
     $template = $this->getTemplate("forgot_password");
     if($template)
     {
        $body = html_entity_decode($template['content']);
     }
     $link = "<a href='".$data['link']."'>".$data['link']."</a>";
     $body = str_replace('{NAME}', $data['name'], $body);
     $body = str_replace('{URL}', $link, $body);
     return $this->sendMail($recipient, $subject, $body);

   }
   public function passwordChangedEmail($data)
   {
     $subject = $data['subject'];
     $template = $this->getTemplate("password_changed");
     if($template)
     {
        $body = html_entity_decode($template['content']);
     }
     $body = str_replace('{NAME}', $data['name'], $body);
     return $this->sendMail($data['recipient'], $subject, $body);
   }



   public function orderConfirmationforSkype($recipient, $data) 
   {
     $subject = $data['subject'];
     $template = $this->getTemplate("astrology_consultation");
     if($template)
     {
        $body = html_entity_decode($template['content']);
     }
     
     $body = str_replace('{NAME}', $data['name'], $body);
     $body = str_replace('{FACEBOOK}', FACEBOOK, $body);
     $body = str_replace('{TWITTER}', TWITTER, $body);
     $body = str_replace('{LINKEDIN}', LINKEDIN, $body);

     return $this->sendMail($recipient, $subject, $body);

   }
}
?>