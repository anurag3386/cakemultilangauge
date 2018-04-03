<?php 

	namespace App\View\Helper;
	use Cake\View\Helper;
	use Cake\ORM\TableRegistry;

	class CustomHelper extends Helper {

		public function dateFormat($unformatted_date) {
			return date('d-m-Y', strtotime($unformatted_date));
		}
        
        /*
          This function is used to format date to  dd/mm/yyy 
          Created By: Stan Field
          Created On: 07 Jan 2017 
        */
        public function newDateFormat($unformatted_date)
        {
			return date('d/m/Y', strtotime($unformatted_date));
        }
  
		public function dayDateFormat($unformatted_date, $day) {
			return date('Y-m-d', strtotime($unformatted_date)).' - '.$day;
		}

        /*
          This function is used to format date to  dd/mm/yyy 
          Created By: Stan Field
          Created On: 07 Jan 2017 
        */
        public function newDayDateFormat($unformatted_date, $day) {
			return date('d/m/Y', strtotime($unformatted_date)).' - '.$day;
		}



		public function getUserAge($dob) {
			return date_diff(date_create($dob), date_create('now'))->y.' yrs';
		}

		public function dateTimeFormat($unformatted_date, $unformatted_time) {
			$date =  $unformatted_date.' '.$unformatted_time;

			return date('d-m-Y || H:i:s', strtotime($date));
		}

		 /*
          This function is used to format date and time to  dd/mm/yyy 
          Created By: Stan Field
          Created On: 07 Jan 2017 
        */
          public function newDateTimeFormat($unformatted_date, $unformatted_time) {
			$date =  $unformatted_date.' '.$unformatted_time;

			return date('d/m/Y || H:i:s', strtotime($date));
		}


		public function getLanguages($serialize_string) {
			
			$Languages = TableRegistry::get('Languages');

			$array = unserialize($serialize_string);
			$data = '';
			foreach($array as $key => $item) {
				$result = $Languages->get( $item, ['fields' => ['name'] ] );
				$data .= ', '.$result['name'];
			}
			return ltrim($data,', ');
		}

		public function getCategory($id) {
			$Categories = TableRegistry::get('Categories');
			$result = $Categories->get($id, ['fields' => ['slug'] ])->toArray();
			return $result['slug'];		

		}

		public function getMenuName($id) {
			$Categories = TableRegistry::get('Menus');
			$result = $Categories->get($id, ['fields' => ['title'] ])->toArray();
			return $result['title'];		

		}

		public function getLanguageName($id) {
		
			$Languages = TableRegistry::get('Languages');
			$result = $Languages->get( $id, ['fields' => ['name'] ] );
			return $result['name'];		
		}

		public function getCurrencies() {

			$Currencies = TableRegistry::get('Currencies');

			$data = $Currencies->find('all', 
					['conditions' => ['Currencies.status' => 1], 
					'order' => ['Currencies.id' => 'asc'],
					'fields' => ['Currencies.id', 'Currencies.code', 'Currencies.name', 'Currencies.symbol']
					 ] )->toArray();
			return $data;
		}

		public function getProductPrice($product_id, $currency_id, $productTypeId) {
			$ProductPrices = TableRegistry::get('ProductPrices');
			$data = $ProductPrices->find('all', 
						['conditions' => ['ProductPrices.currency_id' => $currency_id,'ProductPrices.product_type_id' => $productTypeId, 'ProductPrices.product_id' => $product_id]])->first();//->toArray();
			return $data;

		}

		public function getTomorrowDate()
		{
			return date("F j, Y", strtotime("tomorrow"));
		}
		public function getYesterdayDate()
		{
			return date("F j, Y", strtotime("yesterday"));

		}

		public function getPreviousWeek()
		{
    		//return date('F j, Y',strtotime('last monday -7 days')) ." - ". date('F j, Y',strtotime('last sunday '));
    		//return date('M j, Y',strtotime('last monday -7 days')) ." - ". date('M j, Y',strtotime('last sunday '));
    		return date('M j',strtotime('last monday -7 days')) ." - ". date('M j, Y',strtotime('last sunday '));
		}
		
		public function getPreviousWeekSunday()
		{
    		$firstDayOfLastWeek = mktime(0,0,0,date("m"),date("d")-date("w")-7);
            return date("Y-m-d",$firstDayOfLastWeek);
		}

		public function getNextWeek()
		{
			
			//return date( 'M j, Y', strtotime( 'monday next week' ) ) ." - ". date( 'M j, Y', strtotime( 'sunday next week' ) );
			return date( 'M j', strtotime( 'monday next week' ) ) ." - ". date( 'M j, Y', strtotime( 'sunday next week' ) );
		}
		public function getNextWeekSunday()
		{

			return date('Y-m-d',strtotime('next sunday '));
		}
        public function getCurrentWeek()
        {
        	//return  date('M j, Y', strtotime( "monday" )) ." - ". date('M j, Y', strtotime( "next Sunday" ));
        	return  date('M j', strtotime( "monday this week" )) ." - ". date('M j, Y', strtotime( "next Sunday" ));
        }

        public function getCurrentMonth()
        {
        	//return date('F 01, Y') . ' - ' . date('F t, Y') ;
        	//return date('M 01, Y') . ' - ' . date('M t, Y') ;
        	return date('M 01') . ' - ' . date('M t, Y') ;
        }

        public function getPreviousMonth()
        {
        	//return date('F 01, Y',strtotime('last month')) . ' - '. date('F t, Y',strtotime('last month')) ;
        	//return date('M 01, Y',strtotime('last month')) . ' - '. date('M t, Y',strtotime('last month')) ;
        	//return date('M 01',strtotime('last month')) . ' - '. date('M t, Y',strtotime('last month')) ;
        	return date('M 01',strtotime('first day of previous month')) . ' - '. date('M t, Y',strtotime('last day of previous month')) ;
        }
        public function getNextMonth()
        {
        	//return date('F 01, Y',strtotime('next month')) . ' - '. date('F t, Y',strtotime('next month')) ;

        	// Commented on 31 Jan 2017
        	//return date('M 01, Y',strtotime('next month')) . ' - '. date('M t, Y',strtotime('next month')) ;

        	//return date('M 01, Y',strtotime('first day of +1 month')) . ' - '. date('M t, Y',strtotime('first day of +1 month')) ;
        	return date('M 01',strtotime('first day of +1 month')) . ' - '. date('M t, Y',strtotime('first day of +1 month')) ;
        	
        }
        
        public function getCurrentYear()
        {
        	   $year = date('Y') ;
			//   return  date('M j, Y', mktime(0,0,0,1,1,$year)) ." - ".date('M j, Y',mktime(0,0,0,1,0,$year+1));
			   return  date('M j', mktime(0,0,0,1,1,$year)) ." - ".date('M j, Y',mktime(0,0,0,1,0,$year+1));
			
        }
        public function getPreviousYear()
        {
        	    $year = date('Y') - 1; 
				
				//return  date('M j, Y', mktime(0,0,0,1,1,$year)) ." - ".date('M j, Y',mktime(0,0,0,1,0,$year+1));
				return  date('M j', mktime(0,0,0,1,1,$year)) ." - ".date('M j, Y',mktime(0,0,0,1,0,$year+1));
        }

        public function getNextYear()
        {
        	$year = date('Y') + 1; 
	
	//		return  date('M j, Y', mktime(0,0,0,1,1,$year)) ." - ".date('M j, Y',mktime(0,0,0,1,0,$year+1));
			return  date('M j', mktime(0,0,0,1,1,$year)) ." - ".date('M j, Y',mktime(0,0,0,1,0,$year+1));
        }

		public function getPreviewReports() {
			$PreviewReports = TableRegistry::get('PreviewReports');
			$data = $PreviewReports->find('all', ['conditions' => ['PreviewReports.status' => 1], 'order' => ['PreviewReports.sort_order' =>  'ASC'] ])->toArray();
			return $data;
		}

		public function getCityInfo($id = NULL)
		{

			$City     = TableRegistry::get('Cities');
			$cityInfo = $City->find('all')
						           ->where(['id' => $id])
						           ->select(['latitude', 'longitude', 'city', 'county'])
						           ->first();

			            $latitude = floatval($cityInfo['latitude'] / 3600.0);
                        $latitude = trim( sprintf ( "%2d%s%02d", abs ( intval ($latitude) ), ( ( $latitude >= 0 ) ? 'N' : 'S' ), abs ( intval ( ( ( $latitude - intval ( $latitude ) ) * 60) ) ) ) );
                        $longitude = floatval($cityInfo['longitude'] / 3600.0);
                        $longitude = trim( sprintf ( "%3d%s%02d", abs ( intval ( $longitude ) ), (($longitude >= 0) ? 'W' : 'E' ), abs ( intval ( ( ( $longitude - intval ( $longitude ) ) * 60) ) ) ) );

            return  $cityInfo['city']. ' , '. $cityInfo['county'] . ' [' . $latitude . ' '. $longitude .' ]' ;


		}
        //changed by anurag dubey on 10/nov/2017
		public function removeSpace($str,$locale = 'en')
  		{
  		    list($curr,$n_price) = @explode(' ',$str);
  		    if($curr!='kr.'){
		        return str_replace(' ', '', $str);
            } else {
                $n_price = @round($n_price);
                return $curr.$n_price;
            }
            
  		}
          
        
    	function getEpayPriceFormat ($price,$curr = null) {
    	   //changed by anurag dubey on 13-nov-2017
    	   if( $_SERVER['REMOTE_ADDR'] == '103.254.97.14' || $_SERVER['REMOTE_ADDR'] == '103.248.117.12' || $_SERVER['REMOTE_ADDR'] == '47.9.199.112')  { echo $curr;
    	       if(strtolower($curr)=='dkk'){
    	           $price = round($price,0);
    	       } 
                return str_replace('.', '', sprintf('%0.2f', $price));  
            } else {
               
                return str_replace('.', '', sprintf('%0.2f', $price));
            }
    	}


    	function getProducts($categorySlug, $productType)
    	{
            $products        = TableRegistry::get('Products');
   		    $products_detail = $products->find('all')
		                                ->hydrate(false)
		                                ->contain(['Categories'])
		                                ->join([


		                                            'product_prices' => [
		                                                'table' => 'product_prices',
		                                                'type' => 'INNER',
		                                                'conditions' => [
		                                                   
		                                                    'product_prices.product_id = Products.id',
		                                                ] 
		                                            ],

		                                            'currency' => [
		                                                'table' => 'currencies',
		                                                'type' => 'INNER',
		                                                'conditions' => [
		                                                    
		                                                    'currency.id = product_prices.currency_id',
		                                                ] 
		                                            ]
  
        	                                   ])
	                                    ->select([ 'Products.id', 'Products.short_description', 'Products.name', 'Products.image', 'Products.pages', 'Products.seo_url', 'product_prices.total_price', 'currency.symbol'])

	                                    ->where(['Categories.slug' => $categorySlug, 'product_prices.product_type_id' => $productType,  'currency.symbol' => '$', 'Products.status' => '1'] )
	                                    ->toArray();
   
            return $products_detail;

    	}


function getBundleProducts($categorySlug, $productType, $productId)
    	{
            $products        = TableRegistry::get('Products');
   		    $products_detail = $products->find('all')
		                                ->hydrate(false)
		                                ->contain(['Categories'])
		                                ->join([


		                                            'product_prices' => [
		                                                'table' => 'product_prices',
		                                                'type' => 'INNER',
		                                                'conditions' => [
		                                                   
		                                                    'product_prices.product_id = Products.id',
		                                                ] 
		                                            ],

		                                            'currency' => [
		                                                'table' => 'currencies',
		                                                'type' => 'INNER',
		                                                'conditions' => [
		                                                    
		                                                    'currency.id = product_prices.currency_id',
		                                                ] 
		                                            ]
  
        	                                   ])
	                                    ->select([ 'Products.id', 'Products.short_description', 'Products.name', 'Products.image', 'Products.pages', 'Products.seo_url', 'product_prices.total_price','product_prices.discount_total_price',  'currency.symbol'])

	                                    ->where(['Categories.slug' => $categorySlug, 'product_prices.product_type_id' => $productType,  'currency.symbol' => '$', 'Products.status' => '1', 'Products.id !=' => $productId])
	                                    ->toArray();
   
            return $products_detail;

    	}

    function getProductDetail ($id=null) {
		$ProductTypes =  TableRegistry::get('Products');
		$productTypes =  $ProductTypes->find()
                                      ->where(['Products.id' => $id ])
                                      ->first ();
        return $productTypes;
	}




  
	function getProductTypes($slug)
	{
          $ProductTypes =  TableRegistry::get('ProductTypes');
          $productTypes =  $ProductTypes->find('list')
                                        ->where(['ProductTypes.slug IN' => $slug ])
                                        ->toArray();
          return $productTypes;
	}
   
   function getLanguaguesByProductId($productId)
   {
	      $ProductLanguages = TableRegistry::get('ProductLanguages');
	      $languages = $ProductLanguages->find('all')
	                                           ->contain(['Languages'])
	                                		   ->where(['ProductLanguages.product_id' => $productId])
	                                    	   ->select(['ProductLanguages.language_id', 'Languages.name' ])
	                                    	   ->toArray();
	      return $languages;

                                    	   
   }

   public function getCurrencyDetail ($id) {

			$Currencies = TableRegistry::get('Currencies');
        	$data = $Currencies->find()->where(['Currencies.id'=>$id])->first();
			return $data;
		}

   public function getCountryData($countryId)
   {
     		$Countries = TableRegistry::get('Countries');
        	$data = $Countries->find()->where(['Countries.id'=>$countryId])->first();
			return $data; 
   }

   public function getBooks()
   {        
   	        $Books = TableRegistry::get('Books');
   	        $books = $Books->find('all',['limit'=>2, 'order'=>['Books.id'=>'desc']])
   	                        ->where([ 'Books.status' => 1]);
   	        return $books;
   }

   public function getLoversData($orderId)
   {
            $BirthData = TableRegistry::get('Birthdata');
            $birthData = $BirthData->find('all')->where(['Birthdata.order_id' => $orderId]);
            return $birthData; 
   }

   public function getTimezoneAndSummerTimezoneOnDashboard ($tzone) {

        $TimeZone =  number_format(abs($tzone), 2);
        $timedelta_hh = intval($TimeZone );
        $timedelta_mm = number_format( substr($TimeZone , strpos($TimeZone , '.', 0) + 1, 2), 2);
        $tmpMM = number_format( substr($TimeZone , strpos($TimeZone , '.', 0) + 1, 2), 2);
        if($tmpMM != "") {
          if(intval($tmpMM) > 0 && intval($tmpMM) <= 50) {
            $timedelta_mm = 30;
          } else if(intval($tmpMM) > 50 && intval($tmpMM) <= 100) {
            $timedelta_mm = 45;
          }
        }
        $timediff = number_format(floatval(abs(0.00)), 2);
        $timediff_hh = intval($timediff);
        $timediff_mm = number_format( substr( $timediff, strpos($timediff, '.', 0) + 1, 2), 2);
        $tzDetail = array();
        $tzDetail['timezone'] = sprintf("%02d:%02d", $timedelta_hh, $timedelta_mm);
        $tzDetail['summerreff'] = sprintf("%d:%02d", $timediff_hh, $timediff_mm);
        return $tzDetail;
    }

   public function getCountryDataByAbbreviation($abbr)
   {
     		$Countries = TableRegistry::get('Countries');
        	$data = $Countries->find()->where(['Countries.abbr'=>$abbr])->first();
			return $data; 
   }
  
  // return full name
  function getFullName($firstname, $lastname)
  {
    return ucwords($firstname ." ". $lastname);
  }


  function countryShortCode ($CName) {
  	/*$countrynames = array( "AF"=>"Afghanistan", "AX"=>"\xc3\x85land Islands", "AL"=>"Albania", "DZ"=>"Algeria", "AS"=>"American Samoa", "AD"=>"Andorra", "AO"=>"Angola", "AI"=>"Anguilla", "AQ"=>"Antarctica", "AG"=>"Antigua and Barbuda", "AR"=>"Argentina", "AM"=>"Armenia", "AW"=>"Aruba", "AU"=>"Australia", "AT"=>"Austria", "AZ"=>"Azerbaijan", "BS"=>"Bahamas", "BH"=>"Bahrain", "BD"=>"Bangladesh", "BB"=>"Barbados", "BY"=>"Belarus", "BE"=>"Belgium", "BZ"=>"Belize", "BJ"=>"Benin", "BM"=>"Bermuda", "BT"=>"Bhutan", "BO"=>"Bolivia, Plurinational State of", "BQ"=>"Bonaire, Sint Eustatius and Saba", "BA"=>"Bosnia and Herzegovina", "BW"=>"Botswana", "BV"=>"Bouvet Island", "BR"=>"Brazil", "IO"=>"British Indian Ocean Territory", "BN"=>"Brunei Darussalam", "BG"=>"Bulgaria", "BF"=>"Burkina Faso", "BI"=>"Burundi", "KH"=>"Cambodia", "CM"=>"Cameroon", "CA"=>"Canada", "CV"=>"Cabo Verde", "KY"=>"Cayman Islands", "CF"=>"Central African Republic", "TD"=>"Chad", "CL"=>"Chile", "CN"=>"China", "CX"=>"Christmas Island", "CC"=>"Cocos (Keeling) Islands", "CO"=>"Colombia", "KM"=>"Comoros", "CG"=>"Congo", "CD"=>"Congo, The Democratic Republic of the", "CK"=>"Cook Islands", "CR"=>"Costa Rica", "CI"=>"C\xc3\xb4te d'Ivoire", "HR"=>"Croatia", "CU"=>"Cuba", "CW"=>"Cura\xc3\xa7ao", "CY"=>"Cyprus", "CZ"=>"Czech Republic", "DK"=>"Denmark", "DJ"=>"Djibouti", "DM"=>"Dominica", "DO"=>"Dominican Republic", "EC"=>"Ecuador", "EG"=>"Egypt", "SV"=>"El Salvador", "GQ"=>"Equatorial Guinea", "ER"=>"Eritrea", "EE"=>"Estonia", "ET"=>"Ethiopia", "FK"=>"Falkland Islands (Malvinas)", "FO"=>"Faroe Islands", "FJ"=>"Fiji", "FI"=>"Finland", "FR"=>"France", "GF"=>"French Guiana", "PF"=>"French Polynesia", "TF"=>"French Southern Territories", "GA"=>"Gabon", "GM"=>"Gambia", "GE"=>"Georgia", "DE"=>"Germany", "GH"=>"Ghana", "GI"=>"Gibraltar", "GR"=>"Greece", "GL"=>"Greenland", "GD"=>"Grenada", "GP"=>"Guadeloupe", "GU"=>"Guam", "GT"=>"Guatemala", "GG"=>"Guernsey", "GN"=>"Guinea", "GW"=>"Guinea-Bissau", "GY"=>"Guyana",
"HT"=>"Haiti",
"HM"=>"Heard Island and McDonald Islands",
"VA"=>"Holy See",
"HN"=>"Honduras",
"HK"=>"Hong Kong",
"HU"=>"Hungary",
"IS"=>"Iceland",
"IN"=>"India",
"ID"=>"Indonesia",
"IR"=>"Iran, Islamic Republic of",
"IQ"=>"Iraq",
"IE"=>"Ireland",
"IM"=>"Isle of Man",
"IL"=>"Israel",
"IT"=>"Italy",
"JM"=>"Jamaica",
"JP"=>"Japan",
"JE"=>"Jersey",
"JO"=>"Jordan",
"KZ"=>"Kazakhstan",
"KE"=>"Kenya",
"KI"=>"Kiribati",
"KP"=>"Korea, Democratic People's Republic of",
"KR"=>"Korea, Republic of",
"KW"=>"Kuwait",
"KG"=>"Kyrgyzstan",
"LA"=>"Lao People's Democratic Republic",
"LV"=>"Latvia",
"LB"=>"Lebanon",
"LS"=>"Lesotho",
"LR"=>"Liberia",
"LY"=>"Libya",
"LI"=>"Liechtenstein",
"LT"=>"Lithuania",
"LU"=>"Luxembourg",
"MO"=>"Macao",
"MK"=>"Macedonia, The Former Yugoslav Republic of",
"MG"=>"Madagascar",
"MW"=>"Malawi",
"MY"=>"Malaysia",
"MV"=>"Maldives",
"ML"=>"Mali",
"MT"=>"Malta",
"MH"=>"Marshall Islands",
"MQ"=>"Martinique",
"MR"=>"Mauritania",
"MU"=>"Mauritius",
"YT"=>"Mayotte",
"MX"=>"Mexico",
"FM"=>"Micronesia, Federated States of",
"MD"=>"Moldova, Republic of",
"MC"=>"Monaco",
"MN"=>"Mongolia",
"ME"=>"Montenegro",
"MS"=>"Montserrat",
"MA"=>"Morocco",
"MZ"=>"Mozambique",
"MM"=>"Myanmar",
"NA"=>"Namibia",
"NR"=>"Nauru",
"NP"=>"Nepal",
"NL"=>"Netherlands",
"NC"=>"New Caledonia",
"NZ"=>"New Zealand",
"NI"=>"Nicaragua",
"NE"=>"Niger",
"NG"=>"Nigeria",
"NU"=>"Niue",
"NF"=>"Norfolk Island",
"MP"=>"Northern Mariana Islands",
"NO"=>"Norway",
"OM"=>"Oman",
"PK"=>"Pakistan",
"PW"=>"Palau",
"PS"=>"Palestine, State of",
"PA"=>"Panama",
"PG"=>"Papua New Guinea",
"PY"=>"Paraguay",
"PE"=>"Peru",
"PH"=>"Philippines",
"PN"=>"Pitcairn",
"PL"=>"Poland",
"PT"=>"Portugal",
"PR"=>"Puerto Rico",
"QA"=>"Qatar",
"RE"=>"R\xc3\xa9union",
"RO"=>"Romania",
"RU"=>"Russian Federation",
"RW"=>"Rwanda",
"BL"=>"Saint Barth\xc3\xa9lemy",
"SH"=>"Saint Helena, Ascension and Tristan Da Cunha",
"KN"=>"Saint Kitts and Nevis",
"LC"=>"Saint Lucia",
"MF"=>"Saint Martin (French part)",
"PM"=>"Saint Pierre and Miquelon",
"VC"=>"Saint Vincent and the Grenadines",
"WS"=>"Samoa",
"SM"=>"San Marino",
"ST"=>"Sao Tome and Principe",
"SA"=>"Saudi Arabia",
"SN"=>"Senegal",
"RS"=>"Serbia",
"SC"=>"Seychelles",
"SL"=>"Sierra Leone",
"SG"=>"Singapore",
"SX"=>"Sint Maarten (Dutch part)",
"SK"=>"Slovakia",
"SI"=>"Slovenia",
"SB"=>"Solomon Islands",
"SO"=>"Somalia",
"ZA"=>"South Africa",
"GS"=>"South Georgia and the South Sandwich Islands",
"SS"=>"South Sudan",
"ES"=>"Spain",
"LK"=>"Sri Lanka",
"SD"=>"Sudan",
"SR"=>"Suriname",
"SJ"=>"Svalbard and Jan Mayen",
"SZ"=>"Swaziland",
"SE"=>"Sweden",
"CH"=>"Switzerland",
"SY"=>"Syrian Arab Republic",
"TW"=>"Taiwan, Province of China",
"TJ"=>"Tajikistan",
"TZ"=>"Tanzania, United Republic of",
"TH"=>"Thailand",
"TL"=>"Timor-Leste",
"TG"=>"Togo",
"TK"=>"Tokelau",
"TO"=>"Tonga",
"TT"=>"Trinidad and Tobago",
"TN"=>"Tunisia",
"TR"=>"Turkey",
"TM"=>"Turkmenistan",
"TC"=>"Turks and Caicos Islands",
"TV"=>"Tuvalu",
"UG"=>"Uganda",
"UA"=>"Ukraine",
"AE"=>"United Arab Emirates",
"GB"=>"United Kingdom of Great Britain and Northern Ireland",
"US"=>"United States of America",
"UM"=>"United States Minor Outlying Islands",
"UY"=>"Uruguay",
"UZ"=>"Uzbekistan",
"VU"=>"Vanuatu",
"VE"=>"Venezuela, Bolivarian Republic of",
"VE"=>"Venezuela",
"VN"=>"Viet Nam",
"VG"=>"Virgin Islands, British",
"VI"=>"Virgin Islands, U.S.",
"WF"=>"Wallis and Futuna",
"EH"=>"Western Sahara",
"YE"=>"Yemen",
"ZM"=>"Zambia",
"ZW"=>"Zimbabwe"
);*/
$countrynames = array('AL' => 'Alabama', 'AK' => 'Alaska', 'AZ' => 'Arizona', 'AR' => 'Arkansas', 'CA' => 'California', 'CO' => 'Colorado', 'CT' => 'Connecticut', 'DE' => 'Delaware', 'DC' => 'Dist of Columbia', 'FL' => 'Florida', 'GA' => 'Georgia', 'HI' => 'Hawaii', 'ID' => 'Idaho', 'IL' => 'Illinois', 'IN' => 'Indiana', 'IA' => 'Iowa', 'KS' => 'Kansas', 'KY' => 'Kentucky', 'LA' => 'Louisiana', 'ME' => 'Maine', 'MD' => 'Maryland', 'MA' => 'Massachusetts', 'MI' => 'Michigan', 'MN' => 'Minnesota', 'MS' => 'Mississippi', 'MO' => 'Missouri', 'MT' => 'Montana', 'NE' => 'Nebraska', 'NV' => 'Nevada', 'NH' => 'New Hampshire', 'NJ' => 'New Jersey', 'NM' => 'New Mexico', 'NY' => 'New York', 'NC' => 'North Carolina', 'ND' => 'North Dakota', 'OH' => 'Ohio', 'OK' => 'Oklahoma', 'OR' => 'Oregon', 'PA' => 'Pennsylvania', 'PR' => 'Puerto Rico', 'RI' => 'Rhode Island', 'SC' => 'South Carolina', 'SD' => '	South Dakota', 'TN' => 'Tennessee', 'TX' => 'Texas', 'UT' => 'Utah', 'VT' => 'Vermont', 'VA' => 'Virginia', 'WA' => 'Washington', 'WV' => 'West Virginia', 'WI' => 'Wisconsin', 'WY' => 'Wyoming', 'AF' => 'Afghanistan', 'AL' => 'Albania', 'DZ' => 'Algeria', 'AD' => 'Andorra', 'AO' => 'Angola', 'AI' => 'Anguilla', 'AG' => 'Antigua & Barbuda', 'AR' => 'Argentina', 'AM' => 'Armenia', 'AU' => 'Australia', 'AT' => 'Austria', 'AZ' => 'Azerbaijan', 'BS' => 'Bahamas', 'BH' => 'Bahrain', 'BD' => 'Bangladesh', 'BB' => 'Barbados', 'B' => 'Belarus', 'BE' => 'Belgium' , 'BZ' => 'Belize', 'BJ' => 'Benin', 'BM' => 'Bermuda', 'BT' => 'Bhutan', 'BO' => 'Bolivia', 'BA' => 'Bosnia & Herzegovina', 'BW' => 'Botswana', 'BR' => 'Brazil', 'BN' => 'Brunei', 'BG' => 'Bulgaria', 'BF' => 'Burkina Faso', 'BI' => 'Burundi', 'KH' => 'Cambodia', 'CM' => 'Cameroon', 'CA' => 'Canada', 'CV' => 'Cape Verde', 'KY' => 'Cayman Islands', 'CF' => 'Central African Republic', 'TD' => 'Chad' , 'CL' => 'Chile', 'CN' => 'China', 'CO' => 'Colombia', 'KM' => 'Comoros', 'CG' => 'Congo', 'CD' => 'Congo Democratic Republic', 'CK' => 'Cook Islands', 'CR' => 'Costa Rica', 'HR' => 'Croatia', 'CU' => 'Cuba', 'CY' => 'Cyprus', 'CZ' => 'Czech Republic', 'DK' => 'Denmark' , 'DJ' => 'Djibouti', 'DM' => 'Dominica', 'DO' => 'Dominican Republic', 'TP' => 'East Timor', 'EC' => 'Ecuador', 'EG' => 'Egypt', 'SV' => 'El Salvador', 'GQ' => 'Equatorial Guinea', 'ER' => 'Eritrea', 'EE' => 'Estonia', 'ET' => 'Ethiopia', 'FO' => 'Faeroe Islands', 'FK' => 'Falkland Islands', 'FJ' => 'Fiji', 'FI' => 'Finland', 'FR' => 'France', 'GF' => 'French Guiana', 'PF' => 'French Polynesia', 'GA' => 'Gabon', 'GM' => 'Gambia', 'GE' => 'Georgia', 'DE' => 'Germany', 'GH' => 'Ghana', 'GI' => 'Gibraltar', 'GR' => 'Greece', 'GL' => 'Greenland', 'GD' => 'Grenada', 'GP' => 'Guadeloupe', 'GU' => 'Guam', 'GT' => 'Guatemala', 'GG' => 'Guernsey', 'GN' => 'Guinea', 'GW' => 'Guinea-Bissau', 'GY' => 'Guyana', 'HT' => 'Haiti', 'HN' => 'Honduras', 'HU' => 'Hungary', 'IS' => 'Iceland', 'IN' => 'India', 'ID' => 'Indonesia', 'IR' => 'Iran', 'IQ' => 'Iraq', 'IE' => 'Ireland', 'IL' => 'Israel', 'IT' => 'Italy', 'CI' => 'Ivory Coast', 'JM' => 'Jamaica', 'JP' => 'Japan', 'JE' => 'Jersey', 'JO' => 'Jordan', 'KZ' => 'Kazakhstan', 'KE' => 'Kenya', 'KI' => 'Kiribati', 'NK' => 'Korea, North', 'SK' => 'Korea, South', 'KW' => 'Kuwait', 'KG' => 'Kyrgyzstan', 'LA' => 'Laos', 'LV' => 'Latvia', 'LB' => 'Lebanon', 'LS' => 'Lesotho', 'LR' => 'Liberia', 'LY' => 'Libya', 'LI' => 'Liechtenstein', 'LT' => 'Lithuania', 'LU' => 'Luxembourg', 'MO' => 'Macao', 'MK' => 'Macedonia', 'MG' => 'Madagascar', 'MW' => 'Malawi', 'MY' => 'Malaysia', 'MV' => 'Maldives', 'ML' => 'Mali', 'MT' => 'Malta', 'IM' => 'Man, Isle of'/*'Isle of Man'*/, 'MH' => 'Marshall Islands', 'MQ' => 'Martinique', 'MR' => 'Mauritania', 'MU' => 'Mauritius', 'YT' => 'Mayotte', 'MX' => 'Mexico', 'FM' => 'Micronesia', 'MD' => 'Moldova', 'MC' => 'Monaco', 'MN' => 'Mongolia', 'MS' => 'Montserrat', 'MA' => 'Morocco', 'MZ' => 'Mozambique', 'MM' => 'Myanmar',  'NA' => 'Namibia', 'NR' => 'Nauru', 'NP' => 'Nepal', 'NL' => 'Netherlands', 'AN' => 'Netherlands Antilles', 'NC' => 'New Caledonia', 'NZ' => 'New Zealand', 'NI' => 'Nicaragua', 'NE' => 'Niger', 'NG' => 'Nigeria', 'NU' => 'Niue', 'NF' => 'Norfolk Island', 'MP' => 'Northern Mariana Islands', 'NO' => 'Norway', 'OM' => 'Oman', 'PK' => 'Pakistan', 'PW' => 'Palau', 'PA' => 'Panama', 'PG' => 'Papua New Guinea', 'PY' => 'Paraguay', 'PE' => 'Peru', 'PH' => 'Philippines', 'PN' => 'Pitcairn', 'PL' => 'Poland', 'PT' => 'Portugal', 'QA' => 'Qatar', 'RE' => 'Reunion', 'RO' => 'Romania', 'RU' => 'Russia', 'RW' => 'Rwanda', 'SH' => 'Saint Helena', 'KN' => 'Saint Kitts-Nevis', 'LC' => 'Saint Lucia', 'PM' => 'Saint Pierre and Miquelon', 'VC' => 'Saint Vincent and Grenadines', 'WS' => 'Samoa, Western', 'AS' => 'Samoa, American', 'SM' => 'San Marino', 'ST' => 'Sao Tome and Principe', 'SA' => 'Saudi Arabia', 'SN' => 'Senegal', 'SC' => 'Seychelles', 'SL'=> 'Sierra Leone', 'SG' => 'Singapore', 'SK' => 'Slovakia', 'SI' => 'Slovenia', 'SB' => 'Solomon Islands', 'SO' => 'Somalia', 'ZA' => 'South Africa', 'GS' => 'South Georgia', 'ES' => 'Spain', 'LK' => 'Sri Lanka', 'SD' => 'Sudan', 'SR' => 'Suriname', 'SZ'  => 'Swaziland', 'SE' => 'Sweden', 'CH' => 'Switzerland', 'SY' => 'Syria', 'TW' => 'Taiwan', 'TJ' => 'Tajikistan', 'TZ' => 'Tanzania', 'TH' => 'Thailand', 'TG' => 'Togo', 'TK' => 'Tokelau Islands', 'TO' => 'Tonga', 'TT' => 'Trinidad and Tobago', 'TN' => 'Tunisia', 'TR' => 'Turkey', 'TM' => 'Turkmenistan', 'TC' => 'Turks and Caicos', 'TV' => 'Tuvalu', 'UG' => 'Uganda', 'UA' => 'Ukraine', 'AE' => 'United Arab Emirates', 'GB' => 'United Kingdom', 'UY' => 'Uruguay', 'UZ' => 'Uzbekistan', 'VU' => 'Vanuatu', 'VE' => 'Venezuela', 'VN' => 'Vietnam', 'VG' => 'Virgin Islands', 'WF' => 'Wallis and Futuna', 'YE' => 'Yemen', 'YU' => 'Yugoslavia', 'ZM' => 'Zambia', 'ZW' => 'Zimbabwe');


	return array_search ($CName, $countrynames);
  }

  function userTimeZoneName ($tiezone) {
  		list($hours, $minutes) = explode(':', $tiezone);
	    $seconds = $hours * 60 * 60 + $minutes * 60;
	    // Get timezone name from seconds
	    $tz = timezone_name_from_abbr('', $seconds, 1);
	    // Workaround for bug #44780
	    if($tz === false)
	    	$tz = timezone_name_from_abbr('', $seconds, 0);
	    return $tz;
  }


//This function is called on lovers checkout step 2 to set data
  public function setLoversData($birthDate, $hours, $minutes, $countryId, $firstName, $lastName, $gender, $city, $cityId)
        {
          $person['first_name']   = !empty($firstName) ? $firstName : '';
          $person['last_name']   = !empty($lastName) ? $lastName : '';  
          $person['gender'] = !empty($gender) ? $gender : '';          
          $person['birth_date'] = !empty($birthDate) ? $birthDate : '';
          $person['hours'] = !empty($hours) ? $hours : '';
          $person['minutes'] = !empty($minutes) ? $minutes : '';
          $person['country_id'] = !empty($countryId) ? $countryId : '';
          $person['city_id'] = !empty($cityId) ? $cityId : '';
          
          if( !empty($city) )
          {
			$person['city'] = (strpos($city, '[') != false) ? $city : ''; 
          }
          else
          {
          	$person['city'] = '';
          }
          
          return $person;
        }

/*This function is used to get translation from the database
  Created By: Stan Field
  Created On: 2nd March 2017
  Last Modified: 2nd March 2017
*/

 public function getTranslation($model, $conditions, $fields)
 {
 	 $Model = TableRegistry::get($model);
     $data = $Model->find('all')->where($conditions)->select($fields)->first();
     return $data;

 }

 public function getAnotherPersonDetail($id)
 {
 	$AnotherPersons = TableRegistry::get('AnotherPersons');
 	$data = $AnotherPersons->find('all')
 						   ->join([
                                     'profiles' => [
                                         'table' => 'profiles',
                                         'conditions' => [
		                                                   
		                                                    'profiles.user_id = AnotherPersons.added_by',
		                                                ] 
                                     ]
 						   	])
   						   ->where(['AnotherPersons.id' => $id])
 	                       ->select(['AnotherPersons.fname', 'AnotherPersons.lname', 'profiles.first_name', 'profiles.last_name'])
 	                       ->first();

 	 if(!empty($data))
 	 {
 	 	return $data;
 	 }
 	 else{
 	 	return false;
 	 }

 }

 /* This function is used to get particular field from specific controller in English*/

   function getEnglishTranslation($controller, $field, $id)
   {

   	   $Controller = TableRegistry::get($controller);
   	   $Controller->locale('en_US');
   	   $data       = $Controller->find()
   	                            ->where([$controller.'.id' => $id])
   	                            ->select([ $field ])
   	                            ->first();
   	    if(!empty($data))
   	    {
   	    	return $data[$field];
   	    }                        

   } 

	// This function is used to format price to upto 2 decimal points
   	function formatPrice( $price, $curr = null ) {
   		$formatedPrice = sprintf('%0.2f', $price);
        if(strtolower($curr) =='kr.'){
            $formatedPrice = @round($formatedPrice,0);
        } 
       	return $formatedPrice;
   	}
   

    /*
     * To get slug for mini-blog section in header section
     * Created By : Kingslay
     * Created Date : May 09, 2017
     */
    function getMiniBlogSeoUrlTranslate ($seo_url) {
   		$language = 'da';
   		if (strtolower($this->request->session()->read('locale')) == 'en') {
	   		$miniBlogs = TableRegistry::get('MiniBlogs');
	   		$mini_blog_id = $miniBlogs->find()
	   										->join([
	   												'I18n' => [
	   													'table' => 'i18n',
	   													'conditions' => ['I18n.foreign_key = MiniBlogs.id', 'I18n.model' => 'MiniBlogs', 'I18n.field' => 'slug', 'I18n.locale' => $language]
	   												]
	   											])
	   										->where(['MiniBlogs.slug' => $seo_url])
	   										->select(['MiniBlogs.id', 'MiniBlogs.slug', 'I18n.content'])
	   										->first();
	   		return $mini_blog_id['I18n']['content'];
	   	} else {
	   		$miniBlogs = TableRegistry::get('I18n');
	   		$mini_blog_id = $miniBlogs->find()
	   										->join([
	   												'MiniBlogs' => [
	   													'table' => 'mini_blogs',
	   													'conditions' => ['MiniBlogs.id = I18n.foreign_key']
	   												]
	   											])
	   										->where(['I18n.content' => $seo_url, 'I18n.model' => 'MiniBlogs', 'I18n.field' => 'slug', 'I18n.locale' => $language])
	   										->select(['I18n.id', 'I18n.foreign_key', 'MiniBlogs.slug'])
	   										->first();
	   		return $mini_blog_id['MiniBlogs']['slug'];
	   	} 
    }
    /*
     * To get seo url of product for testimonials from danish to English section in header section
     * Created By : Kingslay
     * Created Date : May 12, 2017
     */
    function getEnglishTranslationOfProduct ($seo_url) {
    	$translation = TableRegistry::get('I18n');
    	$translationData = $translation->find()
    									->join([
    										'Products' => [
    											'table' => 'products',
    											'conditions' => ['Products.id = I18n.foreign_key']
    										]
    									])
    									->where(['I18n.locale' => 'da', 'I18n.model' => 'Products', 'I18n.field' => 'seo_url', 'I18n.content' => $seo_url])
    									->select(['Products.seo_url'])
    									->first();
  		return $translationData['Products']['seo_url'];
    }

    /*
     * To get Image name on support ticket edit action
     * Created By : Kingslay
     * Created Date : July 25, 2017
     */
    function getSupportTicketImage ($supportTicketId) {
    	$translation = TableRegistry::get('CommentFiles');
    	$data = $translation->find()->select(['CommentFiles.file'])->where(['CommentFiles.support_ticket_id' => $supportTicketId])->first();
    	return $data['file'];
    }

}

	


?>