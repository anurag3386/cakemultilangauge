<?php 

	namespace App\Controller;
	use App\Controller\AppController;
	use Cake\ORM\TableRegistry;
	use Cake\Event\Event;
    use Cake\Network\Exception\NotFoundException;
    use Cake\Cache\Cache;
    use Cake\I18n\I18n;

	class ProductsController extends AppController {
		public function initialize() {
			parent::initialize();
			$this->viewBuilder()->layout('home');
			$this->loadModel('Currencies');
			$this->loadModel('Languages');
			$this->loadModel('ProductPrices');
			$this->loadModel('ProductTypes');
			$this->loadModel('DeliveryOptions');
			$this->loadModel('ProductLanguages');
			$this->loadModel('I18n');
			
			if ($this->request->session()->read('locale') == 'en')  {
             I18n::locale('en_US');
            } elseif ($this->request->session()->read('locale') == 'da') {
             I18n::locale('da');   
            }
		}

	    public function beforeFilter(Event $event) {
    		$this->Auth->allow();
		    if (in_array($this->request->action, ['getProductPrice'])) {
		        $this->eventManager()->off($this->Csrf);
		    }
 		}

 		public function astrologyReports() {
 			$reports = $this->Products->find('all')
                                      ->contain(['Categories', 'PreviewReports'])
                                      ->where(['Categories.name' => 'reports', 'Products.status' => 1])
                                      ->select(['Products.category_id', 'Products.id', 'Products.name', 'Products.short_description','Products.description','Products.pages', 'Products.image', 'Products.seo_url', 'PreviewReports.pdf'])
                                      ->order(['Products.sort_order' => 'ASC']);
            if (strtolower($this->request->session()->read('locale')) == 'da') {
            	$meta['title'] = 'Præcis Astrologi Rapport | Personlig Detaljeret Astrologi Rapport & Gratis prøver'; // Page Title
	        	$meta['description'] = 'Astrowow.com tilbyder nøjagtig tolkinger astrologi rapport fra fødselsdato. Få daglig personlig astrologi kalender, kærlighed horoskop, rapport kompatibilitet baseret på din fødsels planeter.';
            } else {
				//$meta['title'] = 'Astrology Report | Horoscope Report Online - Get Free Samples of Astrology Report'; // Page Title
				//$meta['title'] = 'Free Astrology Reports Online : Get Free Samples of Personalized Astrology Reports'; // Page Title
				$meta['title']			= 'Personal Astrology Reports Online - Get Free Personalized Astrology Sample Report'; // Page Title
				
	        	//$meta['description'] = 'Astrowow.com offers accurate reading, astrology report, horoscope report  by date of birth. Get daily detailed personal astrology calendar report, love horoscope report, compatibility report based on your birth planets.';
	        	//$meta['description'] = 'Astrowow.com offers accurate reading, free astrology report and horoscope by date of birth. Get daily detailed personalized astrology report, love horoscope report, astrology prediction by date of birth.';
	        	$meta['description']	= 'Astrowow offers free sample of daily horoscopes and personal astrology reports by date of birth. To get a detailed personalized astrology report place an order now.';
	    	}
	        //$meta['keywords'] = 'online horoscope, Astrology calendar, Astrology compatibility, personal horoscope, year horoscope';
	        $meta['keywords'] = 'online horoscope,  personalized Astrology Reports,  online astrology reports, personal horoscope,  horoscope reports';

	        $canonical['en'] = SITE_URL.'astrology-reports';
        	$canonical['da'] = SITE_URL.'dk/astrologi-rapport';
       
        	$this->set(compact('canonical', 'meta'));
            $this->set(compact('reports')) ;                        

 		}

 		/* This function is used for softwares and sharewares products 
           Created By   : Stan Field
           Last Modified: 19th Dec 2016 
 		*/
 		public function astrology($category = 'software') {
 			// For making seo friendly urls
 			$entity = $this->Products->newEntity();
            $category = ($category == 'astrology-software') ? 'software' : $category;
 			$products = $this->Products->find('all')
                                       ->contain(['Categories'])
                                       ->where(['Categories.slug' => $category])
                                       ->select(['Products.category_id', 'Products.id', 'Products.name', 'Products.short_description', 'Products.image', 'Products.seo_url']);
            if($this->request->is('post')) {
            	$data = $this->request->data;
			   	$this->request->session()->write('Order', $data);
			   	return $this->redirect([ 'controller' => 'Orders', 'action' => 'download-free-trial']);
            }
            if (strtolower($this->request->session()->read('locale')) == 'da') {
            	$meta['title'] = 'Gratis prøveversion astrologi software med horoskop tolkning over 100.000 kopier solgt'; // Page Title
		        $meta['description'] = 'Ingen forudgående kendskab til astrologi er påkrævet. WOW astrologi software er tilgængeligt på flere sprog.';
		    } else {
	            $meta['title'] = 'Astrology Software with Interpretation Software over 1,000,000/ copies sold'; //'Free Trial, Astrology Software, with Horoscope Interpretation, over 1,000,000/ copies sold'; // Page Title
		        $meta['description'] = 'Free trial Wow Astrology Software available in multiple languages.  All WOW software is interpretation software. You can access free trial astrology software online ..download it now.'; //'No previous knowledge of astrological techniques is required. Wow Astrology Software available in multiple languages. You can access our astrology software online on this site and download it now.';
		        $meta['keywords'] = 'Horoscope Software, Astrological Software, online astrology, astrology software online, astrology software cd';
	        }
	        $canonical['en'] = SITE_URL.'astrology-software';
        	$canonical['da'] = SITE_URL.'dk/astrologi-software';
            $this->set(compact('canonical', 'meta'));
            $this->set(compact('products')) ;                        
            $this->set('form', $entity);
 		}

        public function detail($name = NULL, $productType = NULL, $id = NULL) {
    		$this->set('testimonialsList', $this->userTestimonial($name, 1));
        	if( $name == NULL ||  $productType == NULL ):
				throw new NotFoundException('Product not found');
    		endif;
            $entity = $this->Products->newEntity();

			if ( $this->request->is('post') ) {
			   $data = $this->request->data;
			   /* Checking Product Category*/
			   if(strtolower($data['category']) == 'software' || strtolower($data['category'] == 'software-bundle')) {
	             $this->request->session()->write('SOrder', $data);
				 return $this->redirect([ 'controller' => 'Orders', 'action' => 'software-checkout-step-1']);
	           } elseif($data['category'] == 'free-trial') {
	           	$this->request->session()->write('Order', $data);
	           	return $this->redirect([ 'controller' => 'Orders', 'action' => 'download-free-trial']);
	           } else {
	             $this->request->session()->write('Order', $data);
				     return $this->redirect([ 'controller' => 'Orders', 'action' => 'checkout-step-1']);
	           }
			}

			if( strtolower(substr(I18n::locale(), 0, 2)) == 'da') {
	     	    $field = 'Products_seo_url_translation.content';
                $productType = __($productType);
	     	} else {
	     		$field = 'Products.seo_url';
			}

			$data = $this->Products->find('translations', 
						['conditions' => [ $field => $name] ]
						)
					->contain([
					    'PreviewReports' => function ($query) {
					     return $query->find('translations')
					                  ->select('pdf');
					    }
					])
					->first();

			if(empty($data)) {
				return $this->redirect(['controller' => 'products', 'action' => 'astrology-reports']);
			}

			$id =  $data['id'];
			$productTypeId = $this->ProductTypes->find('all')
			                                    ->select(['id'])
			                                    ->where(['slug' => $productType])
			                                    ->first();
			if(empty($productTypeId)) {
				return $this->redirect(['controller' => 'products', 'action' => 'astrology-reports']);
			}
			
			$currencyId =  (strtolower( substr( I18n::locale(), 0, 2 ) ) == 'en') ? 1 : 3;
			$priceInfo = explode("-", $this->getProductPrice( $data['id'], $currencyId, $productTypeId['id'] ) );
			if($priceInfo[1] !='') {
				$data['original_price'] = $priceInfo[1];
            }

          	$data['product_price'] = $priceInfo[0];
		    $data['seo_url']       = $data['seo_url'];
		    $data['productType']   = $productTypeId['id'];

			$CurrencyData = $this->Currencies->find('all', ['conditions' => ['status' =>  1 ] ])->toArray();
			
			foreach ( $CurrencyData as $currency ) { 
				$CurrencyOptions[$currency['id']] = $currency['name'].'('.$currency['symbol'].')'; 
			}
			
            $LanguageOptions = $this->ProductLanguages->find('all')
                                                      ->contain(['Languages'])
                                    				  ->where(['ProductLanguages.product_id' => $id])
                                    				  ->select(['ProductLanguages.language_id', 'Languages.name' ])
                                    				  ->toArray();
            
            if($productType == 'shareware') {
               $deliverySlug = 'email';
            } else {
               $deliverySlug = 'by-post';
            }

            $deliveryOption  = $this->DeliveryOptions->find('all')
							                         ->where(['slug' => $deliverySlug])
							                         ->select(['id'])
							                         ->first();
			$meta = $canonical = array ();
            // This is used to set meta data and canonical tags
            // Created By: Stan Field
            // Creadted On: March 31, 2017
            // Last Modified: March 31, 2017
            if( $data['seo_url'] == 'astrology-calendar-report' || $data['seo_url'] == 'astrokalender-rapport') {
            	if (strtolower($this->request->session()->read('locale')) == 'da') {
            		$meta['title'] = 'Fri 1 år Personlig Astrologi Kalender baseret på din fødselshoroskop'; // Page Title
			        $meta['description'] = 'Astrologi Kalender rapporten indeholder daglige, ugentlige og månedlige astrologiske indflydelser med omfattende transitter og fortolkninger i spørgsmål / svar-format.';
            	} else {
		            $meta['title'] = 'Free 1 Year Personal Astrology Calendar based on your Birth Planets'; // Page Title
			        $meta['description'] = 'The Astrology Calendar report provides daily, weekly and monthly astrological calendars,with comprehensive transits and interpretation in question/answer format.';
			    }
		        $meta['keywords'] = 'Astrology calendar, Daily horoscope, personal horoscope, free horoscopes';
		        $canonical['en'] = SITE_URL.'astrology-reports/astrology-calendar-report/full-reports';
	        	$canonical['da'] = SITE_URL.'dk/astrologi-rapport/astrokalender-rapport/fuld-rapport';
            } elseif( $data['seo_url'] == 'character-and-destiny-report' || $data['seo_url'] == 'karakter-og-skaebne-rapport' ) {
            	if (strtolower($this->request->session()->read('locale')) == 'da') {
            		$meta['title'] = 'Gratis astrologi rapport, din personlige astrologi'; // Page Title
			        $meta['description'] = 'Få personlig astrologi rapport, personlighed beskrivelse, personlig astrologi ved fødselsdato, personlighed med astrologi diagram med to år astrologi forudsigelser';
            	} else {
		 			$meta['title'] = 'Free Trials Destiny, Personal Astrology Report & Your Personality Chart'; // Page Title
			        $meta['description'] = 'Get your personal astrology report,  free destiny trials, personality chart reading, personal astrology by date of birth, personality chart astrology with two year astrology prediction';
			    }
		        $meta['keywords'] = 'Destiny trials report, my personal astrology, personality chart reading, personal astrology by date of birth, personality chart astrology';
		        $canonical['en'] = SITE_URL.'astrology-reports/character-and-destiny-report/full-reports';
	        	$canonical['da'] = SITE_URL.'dk/astrologi-rapport/karakter-og-skaebne-rapport/fuld-rapport';
            } elseif( $data['seo_url'] == 'comprehensive-lovers-report' || $data['seo_url'] == 'astrologi-og-parforhold-rapport' ) {
            	if (strtolower($this->request->session()->read('locale')) == 'da') {
            		$meta['title'] = 'Kærlighedshoroskop der giver oversigt over om I passer sammen - Astrologi og parforhold'; // Page Title
		            $meta['description'] = 'Horoskop der giver overblik over om I passer sammen, kærligheds astrologi rapporter som er tilgængelige på 5 sprog. Sammenlign mellem jeres horoskoper på baggrund af 7 parametre der beskriver harmoni / spænding, varme, romantik, seksualitet og familie.';
            	} else {
	 			$meta['title'] = 'Astrology Love Reports & Relationship Compatibility Chart by Birth Date'; // Page Title
		        $meta['description'] = 'Astrology love reports and horoscope relationship compatibility by birth date are available in 5 languages, compare the horoscopes with 7 parameters of harmony/tension, warmth, romance, sexuality and family.';
		        $meta['keywords'] = 'love astrology, love horoscope, horoscope compatibility, love match, love calculator';
		        }
		        $canonical['en'] = SITE_URL.'astrology-reports/character-and-destiny-report/full-reports';
	        	$canonical['da'] = SITE_URL.'dk/astrologi-rapport/karakter-og-skaebne-rapport/fuld-rapport';
            } elseif( $data['seo_url'] == 'essential-year-ahead-report' || $data['seo_url'] == 'livsbogen-ar-for-ar')
			{
            	if (strtolower($this->request->session()->read('locale')) == 'da') {
            		$meta['title'] = 'Årlig astrologi rapport baseret på dit fødselshoroskop'; // Page Title
		        	$meta['description'] = 'Årlig astrologi rapport der præcist forudsiger vigtige ændringer på syv større områder i dit liv - karriere, livsvej, karakter, følelsesmæssige, intellektuelle liv, kærlighedsliv og sexliv.';
            	} else
            	{
					//$meta['title'] = 'Yearly Astrology Reading Report based on your Birth Chart'; //'free year horoscope reading based on your birth planets - Astrowow.com'; // Page Title
					$meta['title']			= 'Yearly Astrology Reports - Based on your Birth Chart';
					
					$meta['description'] 	= 'Essential Yearly Astrology Report will accurately predict important changes in seven major areas in your life - career, life path, character, emotional life, intellectual life, love life and sex life.'; //'Get free best astrology report, your free personal year horoscope reading based on your birth planets - Astrowow.com. Get free preview now.';
					$meta['keywords'] = 'Year Horoscope, personal horoscope, astrology reading, astrology chart';
		        }

		        $canonical['en'] = SITE_URL.'astrology-reports/essential-year-ahead-report/full-reports';
	        	$canonical['da'] = SITE_URL.'dk/astrologi-rapport/livsbogen-ar-for-ar/fuld-rapport';
            } elseif( $data['seo_url'] == 'horoscope-interpreter' || $data['seo_url'] == 'horoskop-fortolker') {

            	if (strtolower($this->request->session()->read('locale')) == 'da') {
            		$meta['title'] = 'Horoskop fortolkeren - software fra World of Wisdom af Adrian Duncan'; // Page Title
		        $meta['description'] = 'Horoskop fortolker fra World of Wisdom, en af de allerførste Windows astrologi programmer på markedet. Denne horoskop software er oversat til 12 sprog, og er den mest solgte astrologiske software i verden.';
            	} else
            	{

	 			$meta['title'] = 'Horoscope Interpreter & Software from World of Wisdom by Adrian Duncan'; //'Free astrology software with interpretation'; // Page Title
		        $meta['description'] = 'Horoscope Interpreter from World of Wisdom, one of the very first Windows astrology programs on the market. This horoscope software, translated into 12 languages, and is the most sold astrological software in the world.'; //'Best selling astrology interpretation software since 1995, developed by astrologer Adrian Duncan';
		        $meta['keywords'] = 'Horoscope reading, astrology reading, horoscope software, astrology interpretation software, astrology chart, astrology prediction, astrology software CD, online astrology software';
		        }

		        $canonical['en'] = SITE_URL.'astrology-software/horoscope-interpreter/'.$productType;
	        	$canonical['da'] = SITE_URL.'dk/astrologi-software/horoskop-fortolker/'.$productType;
            } elseif( $data['seo_url'] == 'astrology-calendar' || $data['seo_url'] == 'astrokalenderen' ) {

            	if (strtolower($this->request->session()->read('locale')) == 'da') {
            		$meta['title'] = 'World of Wisdom astrologi kalender software baseret på dine fødsels planeter'; // Page Title
		            $meta['description'] = 'World of Wisdom AstroKalender softwareprogrammet viser en fortolkning af planeternes indflydelser. Bestil astrologi kalender software for at få dit unikke daglige personlige horoskop baseret på dine fødsels planeter.';
            	}
				else
				{
					//$meta['title'] 		= 'World of Wisdom Astrology Calendar Software based on Birth Planets'; // Page Title
					$meta['title'] 			= 'Astrology Calendar Software - Download Free Trial Now';
					
					//$meta['description'] = 'WoW Astrology calendar software program displays an interpretation of the planetary influence. Astrology Calendar software to get your unique daily personal horoscope based on your birth planets.';
					$meta['description']	= 'Astrowow offers a World of Wisdom Astrology Calendar software to help you understand the astrological impacts going on around you.Download your free trial now.';
					
					$meta['keywords'] 		= 'Astrological calendar, horoscope calendar, daily horoscope, Astrology Calendar, personal horoscope, astrology horoscope';
		        }
				$canonical['en'] = SITE_URL.'astrology-software/astrology-calendar/'.$productType;
	        	$canonical['da'] = SITE_URL.'dk/astrologi-software/astrokalenderen/'.$productType;
            } elseif( $data['seo_url'] == 'astrology-for-lovers' || $data['seo_url'] == 'astrologi-og-parforhold') {

            	if (strtolower($this->request->session()->read('locale')) == 'da') {
            		$meta['title'] = 'Kærligheds horoskoper - Astrologi og parforhold'; // Page Title
		            $meta['description'] = 'Astrologi og parforholds programmet fra World of Wisdom. Denne astrologi software belyser dit kærlighedsliv, og du kan generere ubegrænsede horoskoper, og ubegrænsede parforholds rapporter.';
            	} 
            	else
            	{
	 			$meta['title'] = 'Astrology for Lovers Software & Astrology Love Compatibility Software'; //'Free Astrology compatibility software based on birth planets'; // Page Title
		        $meta['description'] = 'The Astrology for Lovers program from World of Wisdom. With this relationship astrology software, you can generate unlimited horoscopes, and love compatibility reports.'; //'Free trial version of world\'s most unique astrology compatibility software based on birth planets, with 7 astrology compatibility graphs';
		        $meta['keywords'] = 'compatibility astrology, compatibility horoscope, horoscope compatibillity, love astrology, love compatibility, love calculator';
		        }
		        
	            $canonical['en'] = SITE_URL.'astrology-software/astrology-for-lovers/'.$productType;
	        	$canonical['da'] = SITE_URL.'dk/astrologi-software/astrologi-og-parforhold/'.$productType;
            } elseif( $data['seo_url'] == 'interpreter-lovers' || $data['seo_url'] == 'horoskop-fortolker-astrologi-og-parforhold') {
	 			$meta['title'] = 'Free Astrology compatibility software based on birth planets'; // Page Title
		        $meta['description'] = 'Free trial version of world\'s most unique astrology compatibility software based on birth planets, with 7 astrology compatibility graphs';
		        $meta['keywords'] = 'compatibility astrology, compatibility horoscope, horoscope compatibillity, love astrology, love compatibility, love calculator';
	            $canonical['en'] = SITE_URL.'astrology-software/interpreter-lovers/'.$productType;
	        	$canonical['da'] = SITE_URL.'dk/astrologi-software/horoskop-fortolker-astrologi-og-parforhold/'.$productType;
            } elseif( $data['seo_url'] == 'interpreter-calendar' || $data['seo_url'] == 'horoskop-fortolker-astrologisk-kalender') {
	 			$meta['title'] = 'Free Astrology compatibility software based on birth planets'; // Page Title
		        $meta['description'] = 'Free trial version of world\'s most unique astrology compatibility software based on birth planets, with 7 astrology compatibility graphs';
		        $meta['keywords'] = 'compatibility astrology, compatibility horoscope, horoscope compatibillity, love astrology, love compatibility, love calculator';
	            $canonical['en'] = SITE_URL.'astrology-software/interpreter-calendar/'.$productType;
	        	$canonical['da'] = SITE_URL.'dk/astrologi-software/horoskop-fortolker-astrologisk-kalender/'.$productType;
            } elseif( $data['seo_url'] == 'calendar-lovers' || $data['seo_url'] == 'astrologisk-kalender-astrologi-og-parforhold') {
	 			$meta['title'] = 'Free Astrology compatibility software based on birth planets'; // Page Title
		        $meta['description'] = 'Free trial version of world\'s most unique astrology compatibility software based on birth planets, with 7 astrology compatibility graphs';
		        $meta['keywords'] = 'compatibility astrology, compatibility horoscope, horoscope compatibillity, love astrology, love compatibility, love calculator';
	            $canonical['en'] = SITE_URL.'astrology-software/calendar-lovers/'.$productType;
	        	$canonical['da'] = SITE_URL.'dk/astrologi-software/astrologisk-kalender-astrologi-og-parforhold/'.$productType;
            } elseif( $data['seo_url'] == 'interpreter-calendar-lovers' || $data['seo_url'] == 'horoskop-fortolker-astrologisk-kalender-astrologi-og-parforhold') {
	 			$meta['title'] = 'Free Astrology compatibility software based on birth planets'; // Page Title
		        $meta['description'] = 'Free trial version of world\'s most unique astrology compatibility software based on birth planets, with 7 astrology compatibility graphs';
		        $meta['keywords'] = 'compatibility astrology, compatibility horoscope, horoscope compatibillity, love astrology, love compatibility, love calculator';
	            $canonical['en'] = SITE_URL.'astrology-software/interpreter-calendar-lovers/'.$productType;
	        	$canonical['da'] = SITE_URL.'dk/astrologi-software/horoskop-fortolker-astrologisk-kalender-astrologi-og-parforhold/'.$productType;
            }


            if ($this->checkIp()) {
	            $this->loadModel('UserTestimonials');
	    		$testimonials = $this->UserTestimonials->find()->where(['UserTestimonials.product_id' => $id, 'UserTestimonials.status' => 1])->order(['UserTestimonials.created' => 'DESC'])->toArray();
	    		$this->set(compact('testimonials'));
	    		//pr ($testimonials); die;
	    	}

            $this->set( compact('canonical', 'meta', 'deliveryOption', 'data', 'CurrencyOptions', 'LanguageOptions'));
            /*$this->set(compact('deliveryOption'));
			$this->set(compact('data'));
			$this->set(compact('CurrencyOptions'));
			$this->set(compact('LanguageOptions'));*/
            $this->set('form', $entity);
            $this->set('current_product_id', $id);
		}

		public function getProductPrice( $product_id = 0, $currency_id = 0 , $product_type_id = 0) {
			if ($this->request->is('post')) { 
				$this->viewBuilder()->layout('ajax');
				$this->autoRender = false;	
				$product_id       = $this->request->data['product'];
				$currency_id      = $this->request->data['currency'];
				$product_type_id  = $this->request->data['product_type_id'];
			}
			$data = $this->ProductPrices->find()
			    ->hydrate(false)
			    ->join([
			        'currency' => [
			            'table' => 'currencies',
			            'type' => 'INNER',
			            'conditions' => [
			                'currency.id ' => $currency_id,
			                'ProductPrices.product_id' => $product_id,
			                'ProductPrices.product_type_id' => $product_type_id,
			                'currency.id = ProductPrices.currency_id',
			            ] 
			        ],
			    ])->select('currency.symbol')
			    ->select(['ProductPrices.total_price', 'ProductPrices.discount_total_price', 'ProductPrices.discount_price'])
			    ->first();
            $original_price = "";
            //if($this->checkIp()){
    		    if($data['discount_price'] > 0) {
    		      //changed by Anurag Dubey 13-nov-2017
    		      
                    if(strtolower($data['currency']['symbol'])=='kr.') {
                        $original_price  = $data['currency']['symbol'].' '.round($data['total_price'],0);
                        $product_price   = $data['currency']['symbol'].' '.round($data['discount_total_price'],0);
                    } else {
                        $original_price  = $data['currency']['symbol'].' '.sprintf('%0.2f',$data['total_price']);
                        $product_price   = $data['currency']['symbol'].' '.sprintf('%0.2f',$data['discount_total_price']);
                    }
                    
                } else {
                    if(strtolower($data['currency']['symbol'])=='kr.') {
                        $product_price   = $data['currency']['symbol'].' '.round($data['total_price'],0);   
                    } else {
                        $product_price   = $data['currency']['symbol'].' '.sprintf('%0.2f',$data['total_price']);   
                    }
                }
            //} 
            
		    
		    if ($this->request->is('post')) { 
				echo $product_price."-".$original_price;
				exit;
			}
			return $product_price."-".$original_price;
		}
        
    function getSoftwareBundleProductsPrice() {
    	if ($this->request->is('post')) {
			$this->viewBuilder()->layout('ajax');
			$this->autoRender = false;	
			$product_id       = $this->request->data['product'];
			$currency_id      = $this->request->data['currency'];
			$product_type_id  = $this->request->data['product_type_id'];

			$data = $this->ProductPrices->find()
									    ->hydrate(false)
									    ->contain('Products')
									    ->join([
									        'currency'  => [
									            'table' => 'currencies',
									            'type'  => 'INNER',
									            'conditions' => [
									                'currency.id ' => $currency_id,
									                'ProductPrices.product_id !=' => $product_id,
									                'ProductPrices.product_type_id' => $product_type_id,
									                'currency.id = ProductPrices.currency_id',
									            ] 
									        ],
									    ])
									    ->select('currency.symbol')
									    ->where(['Products.category_id' => SOFTWARE_BUNDLE])
									    ->select(['ProductPrices.total_price', 'ProductPrices.discount_total_price', 'ProductPrices.product_id', 'Products.seo_url'])->toArray() ;
		    echo json_encode($data);
		    exit();							    
		}
         
    }

    function userTestimonial ($seo_url = NULL, $returnValue = 0) {
    	if( $seo_url == NULL ) {
    		throw new NotFoundException('Product testimonials not found');
    	}
    	$this->loadModel('UserTestimonials');
    	if( strtolower( substr( I18n::locale(), 0, 2) ) == 'da') {
     	    $field = 'Products_seo_url_translation.content';
     	} else {
     		$field = 'Products.seo_url';
		}
		$product_id = $this->Products->find('translations', ['conditions' => [ $field => $seo_url] ])->select(['id', 'name'])->first();
    	$product_name = $product_id['name'];
    	$user_testimonial_details = $this->UserTestimonials->find('translations', ['conditions' => [ 'product_id' => $product_id['id'], 'UserTestimonials.status' => 1] ])->toArray();
    	//$user_testimonial_details = $this->UserTestimonials->find()->where(['product_id' => $product_id['id'], 'UserTestimonials.status' => 1])->toArray();
    	if ($returnValue) {
    		return $user_testimonial_details;
    	} else {
    		$this->set(compact('user_testimonial_details', 'product_name'));
    	}
    }
	         
}

?>