<?php 
namespace App\Controller;
use Cake\I18n\I18n;

class MiniBlogsController extends AppController
{
     public  $paginate = [
            'order' => ['MiniBlogs.sort_order' => 'asc', 'MiniBlogs.created' => 'DESC'],
            'limit' => 20,
            'conditions' => ['MiniBlogs.status' => 1]
          ];
   
    public function initialize()
    {
		parent::initialize();
		$this->loadModel('Birthdata');
 		$this->loadModel('Cities');
    	$this->loadModel('Countries');
		$this->loadComponent('Paginator');
		$this->loadModel('I18n');
		$this->viewBuilder()->layout('home');
    }

	public function index() {
    $this->set('blogs', $this->paginate());
    $meta['title'] = 'Your Personalized horoscope, horoscope wheel, astrology report and astrology software'; // Page Title
    $meta['description'] = 'Read the your daily, weekly, monthly and yearly sun sign, astrology report and astrology software, horoscope, long-term trend, horoscope wheel.';
	  $meta['keywords'] = 'sun sign, horoscopes, horoscope wheel, astrology report, astrology';
    $canonical['en'] = SITE_URL.'mini-blogs';
    $canonical['da'] = SITE_URL.'dk/mini-blogs/';
    $this->set(compact('canonical'));
	  $this->set(compact('meta'));
	}

	public function post( $slug = null) {
    if( $slug == null) {
      return $this->redirect(['controller' => 'pages', 'action' => 'index']);
    }
    $slugEN = $slugDK = '';
    if( strtolower( substr( I18n::locale(), 0, 2) ) == 'da') {
      $translatedData = $this->I18n->find()
	     						                ->where(['model' => 'MiniBlogs', 'field' => 'slug', 'content' => $slug ])
              	     						  ->select(['foreign_key'])
              	      						->first();
      $id = $translatedData['foreign_key'];
      $post = $this->MiniBlogs->get($id);
      //if($this->checkIp()) {
        $slugDK = $slug;
        I18n::locale('en');
        $postSlugEN = $this->MiniBlogs->find()->where(['MiniBlogs.id' => $id])->select(['MiniBlogs.slug'])->first();
        $slugEN = $postSlugEN['slug'];
        I18n::locale('da');
      //}
    } else {
      $post = $this->MiniBlogs->find('all')
            									->where( ['MiniBlogs.slug' => $slug] )
            									->first();
      //if($this->checkIp()) {
        $slugEN = $slug;
        $danishSlug = $this->I18n->find()
                                  ->where(['model' => 'MiniBlogs', 'field' => 'slug', 'foreign_key' => $post['id'], 'locale' => 'da' ])
                                  ->select(['content'])
                                  ->first();
        $slugDK = $danishSlug['content'];
      //}
    }

	  $meta['title'] = 'Your Personalized horoscope, horoscope wheel, astrology report and astrology software'; // Page Title
	  $meta['description'] = 'Read the your daily, weekly, monthly and yearly sun sign, astrology report and astrology software, horoscope, long-term trend, horoscope wheel.';
	  $meta['keywords'] = 'sun sign, horoscopes, horoscope wheel, astrology report, astrology';

    if($this->checkIp()) {
      $canonical['en'] = SITE_URL.'mini-blogs/post/'.$slugEN;
      $canonical['da'] = SITE_URL.'dk/mini-blogs/stolpe/'.$slugDK;
    } else {
  	  $canonical['en'] = SITE_URL.'mini-blogs/post/'.$slug;
  	  $canonical['da'] = SITE_URL.'dk/mini-blogs/stolpe/'.$slug;
    }

	  $this->set(compact('canonical'));
	  $this->set(compact('meta'));
    $this->set(compact( 'post' ) );
	}

	public function testZone()
	{
		//Configure::write('debug', 2);
		$this->loadModel('TemporaryOrders');
		$temp_order = $this->TemporaryOrders->find()
		                    ->where( ['TemporaryOrders.id' => 226/*148*/ ] )
		                    ->first()
		                    ->toArray();
		//var_dump($temp_order);

    
		
		$birthDetails =   $this->saveBirthData($temp_order['birth_date'], $temp_order['birth_time'], $temp_order['city_id'], $temp_order['country_id'], 'test', 'test', 'test', 'test', 21);




	}



	protected function saveBirthData($birthDate, $birthTime, $cityId, $countryId, $firstName, $lastName, $nameOnReport, $gender, $orderId)
 {  


 	$this->loadModel('Birthdata');
 	$this->loadModel('Cities');
    $this->loadModel('Countries');
      $birthdata['year'] = date('Y', strtotime($birthDate));
      $birthdata['month'] = date('m', strtotime($birthDate));
      $birthdata['day'] = date('d', strtotime($birthDate));


      if( $birthTime != NULL )
      {
              $birthdata['hour'] = date('H', strtotime($birthTime));
              $birthdata['minute'] = date('i', strtotime($birthTime));
      }
      else
      {
              $birthdata['hour'] = 00;
              $birthdata['minute'] = 00;
              $birthdata['untimed'] = 1;
      }


      $cityData = $this->Cities->find('all')
                               ->contain('Countries')
                               ->where(['Cities.id' => $cityId, 'Countries.id' => $countryId])
                               ->select(['Countries.name','Countries.abbr', 'Cities.city', 'Cities.county', 'Cities.longitude', 'Cities.latitude'])
                               ->first();

      $birthdata['city'] = $cityData['city'];
      $birthdata['state'] = $cityData['county'];
      $birthdata['country'] = $cityData['cities']['name'];
      $birthdata['place'] = $cityData['city'];
      $birthdata['longitude'] = $cityData['longitude'];
      $birthdata['latitude'] = $cityData['latitude'];
      $birthdata['first_name'] = $firstName;
      $birthdata['last_name'] = $lastName;
      $birthdata['name_on_report'] = $nameOnReport;
      $birthdata['start_date'] = date('Y-m-d');
      $birthdata['gender'] = $gender;
      $birthdata['state'] = $cityData['cities']['abbr'];
      $birthdata['city_name'] = $cityData['city'];
      $birthdata['duration']  = 3;
      $birthdata['birth_detail']['country_id'] = $countryId;
      $birthdata['minutes'] = $birthdata['minute'];
      $birthdata['hours'] = $birthdata['hour'];

     // var_dump($birthdata);

      $zone_data = $this->SetLatLong($birthdata);
      //var_dump($zone_data);

      $birthdata['zoneref'] = $birthdata['birth_detail']['zone'];
      $birthdata['summerref'] = $birthdata['birth_detail']['type'];
      /*if ($_SERVER['REMOTE_ADDR'] == '103.254.97.14') {
        echo "<pre>"; print_r($birthdata); die;
      }*/
      /*if ($_SERVER['REMOTE_ADDR'] == '103.254.97.14') {
        echo "Longitude => <pre>"; print_r($birthdata); die;
      }*/
      var_dump($birthdata['zoneref']);
      var_dump($birthdata['summerref']);
      die('reach');      

      unset($birthdata['city']);
      unset($birthdata['country']);
      $birthdata['order_id'] = $orderId;
      $birthDetails = $this->Birthdata->newEntity();
      $birthDetails = $this->Birthdata->patchEntity($birthDetails, $birthdata);    

     // return $birthDetails;
 }

function SetLatLong(&$bData) {

    $Longitude = 0;
    $Latitude  = 0;
    if($bData['latitude'] > -90 && $bData['latitude'] < 90) {
        $Latitude = $bData['latitude'] * 3600;
    } else {
        $Latitude = $bData['latitude'];
        $bData['latitude'] = $bData['latitude'] / 3600;
    }

    if($bData['longitude'] > -180 && $bData['longitude'] < 180) {
        $Longitude = $bData['longitude'] * 3600;
    } else {
        $Longitude = $bData['longitude'];
        $bData['longitude'] = $bData['longitude'] / 3600;
    }
    

    if (isset($bData['status']) && !empty($bData['status'])) {
      $cond = ['Countries.id' => $bData['country_id'] ];
    } else {
      $cond = ['Countries.id' => $bData['birth_detail']['country_id'] ];
    }

    $birthplace = $bData['city_name'];

    $countryData = $this->Countries->find('all')
                    ->where([$cond])
                    //->where(['Countries.id' => $bData['birth_detail']['country_id'] ])
                    ->first();
    
    /*$birthplace = $bData['city_name'];
    $countryData = $this->Countries->find('all')
                    ->where(['Countries.id' => $bData['birth_detail']['country_id'] ])
                    ->first();*/

    $countryAbbr = $countryData['abbr'];
    $placeList = $this->Cities->find('all')->where(['latitude'  => $Latitude, 'longitude' => $Longitude, 'city' => $bData['place']])->first();
    
    
    //if ( $placeList->count()  > 0) {
        //foreach($placeList  as $pItem) {
          if (!empty($placeList)) {
            $pItem = $placeList;
            /*$fullbirthplace = explode ( ">", $pItem->city );
            if (count ( $fullbirthplace ) > 0) {
                $birthplace = trim ( $fullbirthplace [0] );
            } else {
                $birthplace = trim( $pItem->city );
            }*/
            $countryDetail = $this->Countries->find('all')
                                        ->where(['Countries.id' => $pItem->country_id])
                                        ->select(['Countries.abbr', 'Countries.name'])
                                        ->first();
            $countryAbbr = $countryDetail['abbr'];
            $country_name = $countryDetail['name'];
          }
        //}
    //}
   
   

    $Location = sprintf( "%s, %s", $birthplace, $country_name);


    $IsThere = $this->GetSummerTimeZoneANDTimeZone($Location, $bData);
    
    if(count($IsThere) > 0 ) {
        if (isset($bData['status']) && !empty($bData['status'])) {
          $bData['zone'] = $IsThere['m_timezone_offset'];
          $bData['type'] = $IsThere['m_summertime_offset'];
        } else {
          $bData['birth_detail']['zone'] = $IsThere['m_timezone_offset'];
          $bData['birth_detail']['type'] = $IsThere['m_summertime_offset'];
        }
        //pr ($bData); die;
    }
    else {

    }
}


function GetSummerTimeZoneANDTimeZone($location, $data) {


	//var_dump($location);

    $this->loadModel('Cities');

	$countryInfo  = explode(',',$location);
	$country_name =  $countryInfo[1];
	$cityName     = $countryInfo[0];
	//$cityName = preg_replace("/[^{$allow}a-zA-Z0-9]/", '', $cityName);
   //var_dump($country_name)."<br>";
   //var_dump($cityName);

    //$this->query( "SET CHARACTER SET utf8;" );
    $query = $this->Cities->find('all')
                          ->where(['country' => trim($countryInfo[1]), 'city' => trim($countryInfo[0])])
                          ->first();
    

     $TimeZoneArray = array();

     // $output = $this->getAcsatlasData("action=lookup_city&country=$location");
 //            $city_info = unserialize($output);
     $city_info = $query;
   
        
          if (!$city_info) {
                 return $TimeZoneArray;
             }

     //extract($city_info);
    

     $month = $data['month'];
     $day = $data['day'];
     $year = $data['year'];
     $minutes = $data['minutes'];
     $hours = $data['hours'];
     $zonetable = $city_info['zonetable'];
     $typetable =  $city_info['typetable'];
     
     $output = $this->getAcsatlasData("action=time_change_lookup&month=$month&day=$day&year=$year&hour=$hours&minute=$minutes&zonetable=$zonetable&typetable=$typetable");

             $time_info = unserialize($output);
             if (!$time_info) {
                 return $TimeZoneArray;
             }    

        extract($time_info);

    if($type >= 0) {
            //Get the offset in hours from UTC
            $time_types = array(0,1,1,2); //assume $time_type < 4
            $offset = ($zone/900) - $time_types[$type];
            /*if (isset($data['status']) && !empty($data['status'])) {
                $ActualZoneValue = number_format(floatval( ($zone/900) ), 2);
                $ActualZoneValue = ( -1 * $ActualZoneValue );
            } else {*/
            $ActualZoneValue = number_format(floatval( ($zone/900) ), 2);
            
            //}
            $ZoneValue = abs( number_format(floatval( ($zone/900) ), 2) );
            $tmpZone = intval($ZoneValue);
            $tmpZoneDiff = number_format( floatval(  $ZoneValue - $tmpZone ), 2 );
            $FinalZone = $ZoneValue;
            
            if($tmpZoneDiff > 0.0 &&  $tmpZoneDiff <= 0.50 ){
                $FinalZone = number_format( floatval( $tmpZone + 0.30 ), 2);
            } else if($tmpZoneDiff >= 0.51 && $tmpZoneDiff <= 1 ){
                $FinalZone = number_format( floatval( $tmpZone + 0.45 ), 2);
            }

            if( $ActualZoneValue < 0) {
                $TimeZoneArray["m_timezone_offset"] = number_format(-1 * floatval( $FinalZone ), 2);
            } else {
                $TimeZoneArray["m_timezone_offset"] = number_format(floatval( $FinalZone ), 2);
            }
            $TimeZoneArray["m_summertime_offset"] = number_format( floatval( $time_types[$type] ), 2);

            }
    return $TimeZoneArray;
}


}
?>