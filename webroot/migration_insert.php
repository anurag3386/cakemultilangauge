<?php 

	$db = new mysqli('astrowow-mysql.cwlq0bny8ltt.us-west-1.rds.amazonaws.com', 'awswow', 'Main4itl123', 'astronew_db');
	if ($db->connect_error) {
		die('Connect Error (' . $db->connect_errno . ') ');
	}

	$db->set_charset('utf8');


	$content = file_get_contents("http://216.245.193.174/results.json");
	$data = json_decode($content, true);
	//echo '<pre>'; print_r($data); die;

	$finalArr = array();
	$i = 1;
	foreach( $data as $key => $item ) {
		echo $i;
		echo "<br />";
		$i++; 
		$username = $item['username'];
		$user_type = $item['user_type'];
		$previous_id = $item['previous_user_id'];
		$previous_db_id = $item['previous_db_id'];
		$previous_subscription_id = $item['previous_subscription_id'];
		$amount = $item['price'];
		$currency_code = $item['currency_code'];

		$check_qry = $db->query("SELECT id FROM users WHERE username = '$username' AND  previous_db_id = $previous_id ");
		if( $check_qry->num_rows == 1 ) {

			$start_date = strtotime($item['start_date']);
			$end_date = strtotime($item['end_date']);

			$row = $check_qry->fetch_assoc();
			$user_id = $row['id'];
			$query = " INSERT INTO subscribes ( 
						user_id, user_type, amount, currency, start_date, end_date, status, created, modified, previous_db_id, previous_subscription_id)
					VALUES (
						'$user_id', '$user_type', '$amount', '$currency_code', '$start_date', '$end_date', 1, NOW(), NOW(), $previous_db_id, '$previous_subscription_id') ";
						echo "<br />";
			$insert = $db->query($query);
		}
		
	}

	
	die;
	/*foreach( $data as $key => $item ) {

		//users table
		$username = $item['username'];
		//$username = 'king11@123789.org';
		$password = $item['password'];
		$role = $item['role'];
		$status  = $item['status'];
		$step  = $item['step'];
		$portal_id = 2;
		$previous_db_id = $item['previous_db_id'];
		$created = $item['created']; 
		$modified = $item['modified']; 

		//profiles table
		$pfirst_name = $item['pfirst_name'];
		$plast_name = $item['plast_name'];
		$pgender = $item['pgender'];
		$pphone = $item['pphone'];
		$paddress = $item['paddress'];
		$pcity = $item['pcity'];
		$pstate = $item['pstate'];
		$pcountry = $item['pcountry'];
		$pzip = $item['pzip'];	
		$planguage_id = $item['planguage_id'];		

		$check_qry = $db->query("SELECT id FROM users WHERE username = '$username' ");
		if( $check_qry->num_rows == 0 ) {
			 $query = " INSERT INTO users ( 
						username, password, role, status, step, portal_id, created, modified, preview_report, previous_db_id, old_password, flag)
					VALUES (
						'$username', '$password', '$role', $status, $step, $portal_id, '$created', '$modified', 23, $previous_db_id, '$password', 1) ";
			$insert = $db->query($query);
			
			$user_id = $db->insert_id;

			if( $role == 'elite' ) {
				$start_date = time(); 
				$end_date = strtotime('+1 years'); 

				$equery = " INSERT INTO elite_members ( 
						user_id, currency_code, amount, start_date, end_date, created, modified)
					VALUES (
						'$user_id', 'DKK', '0.00', '$start_date', '$end_date', '$start_date', '$start_date') ";
				$einsert = $db->query($equery);
			}

			echo $user_id." Added\n";

			if( $user_id > 0 ) {

				$city_id = $country_id = 0;
				if( strlen($pcountry) > 0 ) {
					$country_id = getCountryByName($pcountry);
					if( $country_id > 0 ) {
						$city_id = getCity($pcity, $country_id);
					}	
				}
				
				

				$query_1 = " INSERT INTO profiles ( 
						user_id, first_name, last_name, gender, phone, address, country_id, city_id, zip, language_id, created, modified)
					VALUES (
						$user_id, '$pfirst_name', '$plast_name', '$pgender', '$pphone', '$paddress', $country_id, $city_id, '$pzip', $planguage_id, '$created', '$modified' ) ";
				$insert_1 = $db->query($query_1);

				echo $user_id." Profile Added  <br />";

				if( $step == 2 ) { //check for user birth details
					$bcountry = $item['bcountry'];
					$bcountry_name = $item['bcountry_name'];
					$bcity = $item['bcity'];
					$bstate = $item['bstate'];
					$bdate = $item['bdate'];
					$btime = $item['btime'];
					$bsun_sign_id = $item['bsun_sign_id'];
					$bzone = $item['bzone'];
					$btype = $item['btype'];
					$bday = getDayFromDate($item['bdate']);

					$city_id = $country_id = 0;
					if( strlen($bcountry_name) > 0 ) {
						$country_id = getCountryByName($bcountry_name);
						if( $country_id > 0 ) {
							$city_id = getCity($bcity, $country_id);
						}
					}
					

				    $query_2 = " INSERT INTO birth_details ( 
						user_id, country_id, city_id, `date`, day, `time`, sun_sign_id, created, modified, zone, type)
					VALUES (
						$user_id, $country_id, $city_id, '$bdate', '$bday', '$btime', $bsun_sign_id, '$created', '$modified', '$bzone', '$btype') ";
					$insert_2 = $db->query($query_2);

					echo $user_id." Birthdetail Added <br />";
				}


			}			

		} else { 
			$row_check = $check_qry->fetch_assoc();
			echo "<br />Username: $username already exists in users table at row: ".$row_check['id'];
			echo "<br />";		
		}				
	}*/

	function getCountryByName( $name ) {

		global $db;
		
		$id = 0;
		$fetch = $db->query("SELECT id FROM countries WHERE lower(name) LIKE '%".$name."%' LIMIT 1");
		if( $fetch->num_rows > 0 ) {
			$row = $fetch->fetch_assoc();
			$id = $row['id'];
		}
		return $id;	
	} 


	function getCity( $name, $country_id ) {

		global $db;
		
		$id = 0;
		$fetch = $db->query("SELECT id FROM cities WHERE lower(city) LIKE '%".$name."%' AND country_id = $country_id LIMIT 1");
		if( $fetch->num_rows > 0 ) {
			$row = $fetch->fetch_assoc();
			$id = $row['id'];
		}
		return $id;	
	} 

	function getDayFromDate( $date ) {
		
		$day = '';
		$timestamp = strtotime($date);
		if( $timestamp ) {
			$day = date('l', $timestamp);
		}
		return $day;
	}

?>