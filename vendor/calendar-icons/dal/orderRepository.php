<?php
use Aws\CloudFront\Exception\Exception;

if (!class_exists('cDatabase')) {
    if(!include("cDatabase.php")) {
        //require_once("../cDatabase.php");
        require_once(DALPATH."cDatabase.php");
    }
}
//if (!class_exists('ACSStateList')) {
//    if(!include(ROOTPATH ."/classes/acs/class.acs.statelist.php")) {
//        //require_once("../cDatabase.php");
//        require_once(ROOTPATH ."/classes/acs/class.acs.statelist.php");
//    }
//}
if (!class_exists('ACSStateList')) {
    if(!include(HELPERPATH ."/acs/ACSStateList.php")) {
        //require_once("../cDatabase.php");
        require_once(HELPERPATH ."/acs/ACSStateList.php");
    }
}

class OrderRepository {
	
	var $MyPDO;
	
	public function __construct() {
		$this->MyPDO = $GLOBALS["db"]; 
	}
	
    public function SaveOrder($data) {
    	/*
    	$SQLQuery = "INSERT INTO `order` ";
    	$SQLQuery .= "(product_item_id, price,discount, user_id, delivery_option, order_status, order_date, confirm_payment_date, product_type, chk_for_register, 
    				   currency_code, shipping_charge, email_id, language_code, portalid, payment_method ) ";
    	$SQLQuery .= " VALUES ";
    	$SQLQuery .= "(:product_item_id, :price, :discount, :user_id, :delivery_option, :order_status, :order_date, :confirm_payment_date, :product_type, :chk_for_register, 
    				   :currency_code, :shipping_charge, :email_id, :language_code, :portalid, :payment_method )";
    	*/
    	$SQLQuery = "INSERT INTO `order` ";
    	$SQLQuery .= "(product_item_id, price,discount, user_id, delivery_option, order_status, order_date, product_type, chk_for_register,
    				   currency_code, shipping_charge, email_id, language_code, portalid, payment_method ) ";
    	$SQLQuery .= " VALUES ";
    	$SQLQuery .= "(:product_item_id, :price, :discount, :user_id, :delivery_option, :order_status, :order_date, :product_type, :chk_for_register,
    				   :currency_code, :shipping_charge, :email_id, :language_code, :portalid, :payment_method )";
    	
    	$objMyPDO = $this->MyPDO->prepare($SQLQuery);
    	
    	$objMyPDO->bindParam(':product_item_id', $data->product_item_id);
    	$objMyPDO->bindParam(':price', $data->price);
    	$objMyPDO->bindParam(':discount', $data->discount);
    	$objMyPDO->bindParam(':user_id', $data->user_id);
    	$objMyPDO->bindParam(':delivery_option', $data->delivery_option);
    	$objMyPDO->bindParam(':order_status', $data->order_status);
    	$objMyPDO->bindParam(':order_date', $data->order_date);
    	//$objMyPDO->bindParam(':confirm_payment_date', $data->confirm_payment_date);
    	$objMyPDO->bindParam(':product_type', $data->product_type);
    	$objMyPDO->bindParam(':chk_for_register', $data->chk_for_register);
    	$objMyPDO->bindParam(':currency_code', $data->currency_code);
  
    	if(isset($data->shipping_charge) && $data->shipping_charge != "") {
    		$data->shipping_charge = 0;
    	}
    	$objMyPDO->bindParam(':shipping_charge', $data->shipping_charge);
    	
    	$objMyPDO->bindParam(':email_id', $data->email_id);
    	$objMyPDO->bindParam(':language_code', $data->language_code);
    	$objMyPDO->bindParam(':portalid', $data->portalid);
    	$objMyPDO->bindParam(':payment_method', $data->payment_method);
    	
    	$objMyPDO->execute();    	
    	$order_id = $this->MyPDO->lastInsertId();
    	return $order_id;
    }

    public function SaveOrderShipping($data) {
    	$SQLQuery = "INSERT INTO `order_shipping` (order_id, user_id, first_name, last_name, address_1, address_2, city, state, country, postal_code, phone) ";
    	$SQLQuery .= " VALUES ";
    	$SQLQuery .= "(:order_id, :user_id, :first_name, :last_name, :address_1, :address_2, :city, :state, :country, :postal_code, :phone) ";
    	
    	$objMyPDO = $this->MyPDO->prepare($SQLQuery);

    	$objMyPDO->bindParam(':order_id', $data->order_id);
    	$objMyPDO->bindParam(':user_id', $data->user_id);
    	$objMyPDO->bindParam(':first_name', $data->first_name);
    	$objMyPDO->bindParam(':last_name', $data->last_name);
    	$objMyPDO->bindParam(':address_1', $data->address_1);
    	$objMyPDO->bindParam(':address_2', $data->address_2);
    	$objMyPDO->bindParam(':city', $data->city);
    	$objMyPDO->bindParam(':state', $data->state);
    	$objMyPDO->bindParam(':country', $data->country);
    	$objMyPDO->bindParam(':postal_code', $data->postal_code);
    	$objMyPDO->bindParam(':phone', $data->phone);
    	
    	$objMyPDO->execute();
    	$shipping_id = $this->MyPDO->lastInsertId();
    	return $shipping_id;
	}

    public function UpdateOrderData($data) {
    	$SQLQuery = "UPDATE `order` SET 
    					product_item_id = :product_item_id, user_id = :user_id, delivery_option = :delivery_option, 
    					order_status = :order_status, order_date = :order_date, 
    					language_code = :language_code, currency_code = :currency_code, email_id = :email_id ";
    	
    	if(isset($data->confirm_payment_date) && $data->confirm_payment_date > 0) {
    		$SQLQuery .= ", confirm_payment_date = :confirm_payment_date ";
    	}
    	
    	if(isset($data->discount) && $data->discount > 0) {
    		$SQLQuery .= ", discount = :discount ";
    	}
    	if(isset($data->shipping_charge) && $data->shipping_charge > 0) {
    		$SQLQuery .= ", shipping_charge = :shipping_charge ";
    	}    	
    	$SQLQuery .= "WHERE order_id = :order_id ";
    	
    	$objMyPDO = $this->MyPDO->prepare($SQLQuery);

    	$objMyPDO->bindParam(':product_item_id', $data->product_item_id);				//1
    	$objMyPDO->bindParam(':user_id', $data->user_id);								//2
    	$objMyPDO->bindParam(':delivery_option', $data->delivery_option);				//3
    	$objMyPDO->bindParam(':order_status', $data->order_status);						//4
    	$objMyPDO->bindParam(':order_date', $data->order_date);							//5   	

    	if(isset($data->confirm_payment_date) && $data->confirm_payment_date != "") {
    		$objMyPDO->bindParam(':confirm_payment_date', $data->confirm_payment_date);		//6
    	}
    	
    	$objMyPDO->bindParam(':language_code', $data->language_code);					//7
    	$objMyPDO->bindParam(':currency_code', $data->currency_code);					//8
    	$objMyPDO->bindParam(':email_id', $data->email_id);								//9
    	
    	if(isset($data->discount) && $data->discount > 0) {
    		$objMyPDO->bindParam(':discount', $data->order_id);							//10
    	}
    	if(isset($data->shipping_charge) && $data->shipping_charge > 0) {
    		$objMyPDO->bindParam(':shipping_charge', $data->shipping_charge);			//11
    	}     	
    	
    	$objMyPDO->bindParam(':order_id', $data->order_id);								//12
    	
    	try {
    		$objMyPDO->execute();
    		return true;
    	} catch (Exception $ex) {
    		return false;
    	}
    }

    public function UpdateOrderShipping($data) {
    	$SQLQuery = "UPDATE `order_shipping` SET
						user_id = :user_id, first_name = :first_name,
						last_name = :last_name,  address_1 = :address_1,  address_2 = :address_2,
						city = :city,  state = :state, country = :country,
						postal_code = :postal_code, phone = :phone
					WHERE shipping_id = :shipping_id AND order_id = :order_id ";
    	 
    	$objMyPDO = $this->MyPDO->prepare($SQLQuery);
    	 
    	$objMyPDO->bindParam(':user_id', $data->user_id);
    	$objMyPDO->bindParam(':first_name', $data->first_name);
    	$objMyPDO->bindParam(':last_name', $data->last_name);
    	$objMyPDO->bindParam(':address_1', $data->address_1);
    	$objMyPDO->bindParam(':address_2', $data->address_2);
    	$objMyPDO->bindParam(':city', $data->city);
    	$objMyPDO->bindParam(':state', $data->state);
    	$objMyPDO->bindParam(':country', $data->country);
    	$objMyPDO->bindParam(':postal_code', $data->postal_code);
    	$objMyPDO->bindParam(':phone', $data->phone);
    	$objMyPDO->bindParam(':order_id', $data->order_id);
    	$objMyPDO->bindParam(':shipping_id', $data->shipping_id);
    	 
    	try {
    		$objMyPDO->execute();
    		return true;
    	} catch (Exception $ex){
    		return false;
    	}
    }

    public function GetOrderDetailByOrderId($order_id) {
    	$SQLQuery = "SELECT
						order_id, product_item_id, price, discount, user_id, delivery_option,
						order_status, order_date, language_code, confirm_payment_date, product_type, chk_for_register,
						currency_code, shipping_charge, email_id
					FROM `order`
					WHERE order_id = :order_id ";
    	 
    	$objMyPDO = $this->MyPDO->prepare($SQLQuery);
    	
    	$objMyPDO->bindParam(':order_id', $order_id);
    	 
    	$objMyPDO->execute();
    	
    	return $objMyPDO->fetchAll ( PDO::FETCH_ASSOC );
    	    	
//         $obj = new cDatabase();
//         $sql =" select order_id, product_item_id, price, discount, user_id, delivery_option, order_status, order_date,language_code," ;
//         $sql .=" confirm_payment_date,product_type,chk_for_register,currency_code,shipping_charge,email_id from `order` where order_id=".$order_id;
//         $query = $obj->db->query($sql);
//         return $query->rows;
    }

    public function GetOrderShippingDetailByOrderId($order_id) {
    	$SQLQuery = "SELECT
						shipping_id, order_id, user_id, first_name, last_name, address_1, address_2, city, state, country, postal_code, phone
					FROM `order_shipping`
					WHERE order_id = :order_id ";
    	
    	$objMyPDO = $this->MyPDO->prepare($SQLQuery);
    	$objMyPDO->bindParam(':order_id', $order_id);
    	
    	$objMyPDO->execute();
    	 
    	return $objMyPDO->fetchAll ( PDO::FETCH_ASSOC );
    	
//         $obj = new cDatabase();
//         $sql =" select shipping_id,order_id,user_id,first_name,last_name,address_1,address_2,city,state,country,postal_code,phone" ;
//         $sql .=" from order_shipping where order_id=".$order_id;
//         $query = $obj->db->query($sql);
//         return $query->rows;
    }

    public function UpdateOrderStatus($order_id, $status) {
    }

    public function SaveBirthData($data) {
        $data = $this->GetLocationInformation($data);
        
        $SQLQuery = "INSERT INTO `birthdata`
				(day, month, year, untimed, hour, minute, gmt, zoneref, summerref, place, state, longitude, latitude, orderid, first_name, last_name, start_date, Gender)
			VALUE
				(:day, :month, :year, :untimed, :hour, :minute, :gmt, :zoneref, :summerref, :place, :state, :longitude, :latitude, :orderid, :first_name, :last_name, :start_date, :Gender) ";
        
        $objMyPDO = $this->MyPDO->prepare($SQLQuery);
        $objMyPDO->bindParam(':day', $data->day);
        $objMyPDO->bindParam(':month', $data->month);
        $objMyPDO->bindParam(':year', $data->year);
        $objMyPDO->bindParam(':untimed', $data->untimed);
        $objMyPDO->bindParam(':hour', $data->hour);
        $objMyPDO->bindParam(':minute', $data->minute);
        $objMyPDO->bindParam(':gmt', $data->gmt);
        $objMyPDO->bindParam(':zoneref', $data->zoneref);
        $objMyPDO->bindParam(':summerref', $data->summerref);
        $objMyPDO->bindParam(':place', $data->place);
        $objMyPDO->bindParam(':state', $data->state);
        $objMyPDO->bindParam(':longitude', $data->longitude);
        $objMyPDO->bindParam(':latitude', $data->latitude);
        $objMyPDO->bindParam(':orderid', $data->orderid);
        $objMyPDO->bindParam(':first_name', $data->first_name);
        $objMyPDO->bindParam(':last_name', $data->last_name);
        $objMyPDO->bindParam(':start_date', $data->start_date);
        $objMyPDO->bindParam(':Gender', $data->gender);
        
        $objMyPDO->execute();
        
        $birthdataid = $this->MyPDO->lastInsertId();
        return $birthdataid;
	}

    public function UpdateBirthData($data) {
        $data = $this->GetLocationInformation($data);
        
        $SQLQuery = "UPDATE `birthdata` SET
				day = :day, month = :month, year = :year, untimed = :untimed, hour = :hour, minute = :minute, gmt = :gmt, zoneref = :zoneref, summerref = :summerref, place = :place, state = :state, longitude = :longitude, latitude = :latitude, first_name = :first_name, last_name = :last_name, start_date = :start_date, Gender = :Gender
			WHERE orderid = :orderid AND birthdataid = :birthdataid";
        
        $objMyPDO = $this->MyPDO->prepare($SQLQuery);
        $objMyPDO->bindParam(':day', $data->day);
        $objMyPDO->bindParam(':month', $data->month);
        $objMyPDO->bindParam(':year', $data->year);
        $objMyPDO->bindParam(':untimed', $data->untimed);
        $objMyPDO->bindParam(':hour', $data->hour);
        $objMyPDO->bindParam(':minute', $data->minute);
        $objMyPDO->bindParam(':gmt', $data->gmt);
        $objMyPDO->bindParam(':zoneref', $data->zoneref);
        $objMyPDO->bindParam(':summerref', $data->summerref);
        $objMyPDO->bindParam(':place', $data->place);
        $objMyPDO->bindParam(':state', $data->state);
        $objMyPDO->bindParam(':longitude', $data->longitude);
        $objMyPDO->bindParam(':latitude', $data->latitude);
        $objMyPDO->bindParam(':first_name', $data->first_name);
        $objMyPDO->bindParam(':last_name', $data->last_name);
        $objMyPDO->bindParam(':start_date', $data->start_date);
        $objMyPDO->bindParam(':Gender', $data->gender);
        $objMyPDO->bindParam(':orderid', $data->orderid);
        $objMyPDO->bindParam(':birthdataid', $data->birthdataid);
        
        try {
        	$objMyPDO->execute();
        	return true;
        } catch (Exception $ex) {
        	return false;
        }
    }

    public function GetUserBirthDetailByOrderId($order_id) {
    	$SQLQuery = "SELECT
						birthdataid, day, month, year, untimed, hour, minute, gmt, zoneref, summerref, place, state, longitude, 
    					latitude, orderid, first_name, last_name ,start_date, Gender
					FROM `birthdata` WHERE orderid = :orderid ";
    	
    	$objMyPDO = $this->MyPDO->prepare($SQLQuery);
    	
    	$objMyPDO->bindParam(':orderid', $order_id);
    	
    	$objMyPDO->execute();
    	return $objMyPDO->fetchAll ( PDO::FETCH_ASSOC );
    	
//     	$obj = new cDatabase();
//         $sql = " Select  ";
//         $sql .=" birthdataid, day,month,year,untimed,hour,minute,gmt,zoneref,summerref,place,state,longitude,latitude,orderid, first_name, last_name ,start_date, Gender";
//         $sql .=" from birthdata where orderid = ".$order_id;
//         $query = $obj->db->query($sql);
//         return $query->rows;
    }

    public function SaveLoversData($data) {
    	$SQLQuery = "INSERT INTO `lovers_report_data` ";
    	$SQLQuery .= "( person_name, gender, order_id, birth_data_id ) ";
    	$SQLQuery .= " VALUES ";
    	$SQLQuery .= "( :person_name, :gender, :order_id, :birth_data_id )";
    	
    	$objMyPDO = $this->MyPDO->prepare($SQLQuery);
    	
    	$objMyPDO->bindParam(':person_name', $data->person_name);
    	$objMyPDO->bindParam(':gender', $data->gender);
    	$objMyPDO->bindParam(':order_id', $data->order_id);
    	$objMyPDO->bindParam(':birth_data_id', $data->birth_data_id);
    	
    	$objMyPDO->execute();
    	$lovers_report_data_id = $this->MyPDO->lastInsertId();
    	return $lovers_report_data_id;    	
    }

    public function UpdateLoversData($data) {
    	$SQLQuery = "UPDATE `lovers_report_data` SET
						person_name = :person_name, gender = :gender 
					WHERE order_id = :order_id AND birth_data_id = :birth_data_id  ";
		
		$objMyPDO = $this->MyPDO->prepare($SQLQuery);
		
		$objMyPDO->bindParam(':person_name', $data->person_name);
		$objMyPDO->bindParam(':gender', $data->gender);
		$objMyPDO->bindParam(':order_id', $data->order_id);
		$objMyPDO->bindParam(':birth_data_id', $data->birth_data_id);
		
		try {
			$objMyPDO->execute(); 
			return true;
		} catch ( Exception $ex ){
			return false;
		}
    }

    public function GetLoversData($order_id) {
    	$SQLQuery = "SELECT
				birthdataid, day, month, year, untimed, hour, minute, gmt, zoneref, summerref, place, state, longitude, latitude, 
    			orderid, first_name, last_name , person_name, lr.gender, lovers_report_data_id
			FROM `birthdata`
					LEFT JOIN `lovers_report_data` lr ON lr.birth_data_id = birthdata.birthdataid
			WHERE orderid = :orderid ";
    	
    	$objMyPDO = $this->MyPDO->prepare($SQLQuery);
    	
    	$objMyPDO->bindParam(':orderid', $order_id);
    	
    	$objMyPDO->execute();
    	return $objMyPDO->fetchAll ( PDO::FETCH_ASSOC );
    	
//         $obj = new cDatabase();
//         $sql = " Select  ";
//         $sql .=" birthdataid, day,month,year,untimed,hour,minute,gmt,zoneref,summerref,place,state,longitude,latitude,orderid, first_name, last_name , person_name, lr.gender, lovers_report_data_id ";
//         $sql .=" FROM birthdata LEFT JOIN lovers_report_data lr ON lr.birth_data_id = birthdata.birthdataid";
//         $sql .=" where orderid = ".$order_id;
//         $query = $obj->db->query($sql);
//         return $query->rows;
    }

    public function UpdateOrderStatusFromIPN($order_id,$status) {
        $OrderStatus = $this->SelectOrderStatusForReports($order_id, $status);
        
        $SQLQuery = "UPDATE `order` SET
				order_status = :order_status, confirm_payment_date = :confirm_payment_date
			WHERE order_id = :order_id ";
        
        $objMyPDO = $this->MyPDO->prepare($SQLQuery);
        $ConfirmPaymentDate = date("Y-m-d");
        $objMyPDO->bindParam(':order_status', $OrderStatus);
        $objMyPDO->bindParam(':confirm_payment_date', $data->gender);
        $objMyPDO->bindParam(':order_id', $order_id);
        
        try {
        	$objMyPDO->execute();
        	return true;
        } catch ( Exception $ex ){
        	return false;
        }
    }

    public function SelectOrderStatusForReports($order_id, $status){
        $OrderStatus = $status;
        $SQLQuery = "SELECT product_item_id As ProductID FROM `order` WHERE order_id = :orderid ";
        
        $objMyPDO = $this->MyPDO->prepare($SQLQuery);
        $objMyPDO->bindParam(':orderid', $order_id);
        
        $objMyPDO->execute();
        $results = $objMyPDO->fetchAll ( PDO::FETCH_ASSOC );
        if($status == 2 || $status == 12) {
        	if(count($results) > 0){
        		foreach ($results as $item) {
        			$ProductID = $item['ProductID'];
        			if($ProductID == 13 || $ProductID == 17 || $ProductID == 19){
        				$OrderStatus = 12;
        			}
        		}
        	}
        }
        return $OrderStatus;
        
//         $obj = new cDatabase();
//         $sql = " SELECT product_item_id As ProductID FROM `order` WHERE order_id = ".$order_id;

//         if($status == 2 || $status == 12) {
//             $query = $obj->db->query($sql);
//             $ProductID = $query->row['ProductID'];
//             if($ProductID == 13 || $ProductID == 17 || $ProductID == 19){
//                 $OrderStatus = 12;
//             }
//         }

//         return $OrderStatus;
    }

    /*public function GetUserIdByOrderId($order_id)
	{
		$obj = new cDatabase();
		$sql = " Select  user_id from `order` where order_id = ".$order_id;	
		$query = $obj->db->query($sql);
		return $query->rows;
	}*/

    public function GetOrderCountForUser($user_id) {
    	$SQLQuery = "SELECT  count(order_id) as count FROM `order` WHERE user_id = :user_id ";
    	
    	$objMyPDO = $this->MyPDO->prepare($SQLQuery);
    	$objMyPDO->bindParam(':user_id', $user_id);
    	
    	$objMyPDO->execute();
    	$tmpCount = $objMyPDO->fetchColumn ();
    	
    	return $tmpCount;
    	 
    	
//         $obj = new cDatabase();
//         $sql = "SELECT count(order_id) as count FROM `order` where user_id = '".$user_id."' ";
//         $query = $obj->db->query($sql);
//         return $query->row['count'];
    }

    public function GetOrderListForUser($user_id,$items_per_page,$start) {
    	$SQLQuery = "SELECT
				order_id, price, user_id, order_status, order_date, product_type, portalid , pid.name, pd.productName, c.symbol
			FROM `order` o
					LEFT JOIN product_items_description pid ON pid.product_items_id = o.product_item_id
					LEFT JOIN product_description pd ON pd.product_id = o.product_item_id
					LEFT JOIN currency c ON c.code = o.currency_code
			WHERE user_id = :user_id AND o.product_type not in(1,4,5,10)
			GROUP BY o.order_id
			ORDER BY o.order_date DESC
			LIMIT 0, 15";
    	
    	$objMyPDO = $this->MyPDO->prepare($SQLQuery);
    	$objMyPDO->bindParam(':user_id', $user_id);
    	
    	$objMyPDO->execute();
    	return $objMyPDO->fetchAll ( PDO::FETCH_ASSOC );
    	 
    	
//         $obj = new cDatabase();
//         $sql = " SELECT order_id, price, user_id, order_status, order_date, product_type, portalid , pid.name, pd.productName, c.symbol
// 				FROM `order` o
// 				left join product_items_description pid on pid.product_items_id = o.product_item_id
// 				left join product_description pd on pd.product_id = o.product_item_id
// 				left join currency c on c.code = o.currency_code
// 				where user_id =". $user_id." and o.product_type not in(1,4,5,10) 
// 				GROUP BY o.order_id ORDER BY o.order_date DESC ";
// //limit ".$start.",".$items_per_page;	
//         $query = $obj->db->query($sql);
//         return $query->rows;
    }

    public function GetOrderDetailForUser($user_id,$order_id) {
        $obj = new cDatabase();

        $sql = " SELECT `order_id`, `product_item_id`, `price`, `discount`, `user_id`, `delivery_option`, `order_status`, `order_date`,
		`confirm_payment_date`, `product_type`, `chk_for_register`, `currency_code`, `shipping_charge`, `email_id`, `language_code`, `portalid`
		FROM `order`
		where order_id = ".$order_id;
        $query = $obj->db->query($sql);
        $orderDetail = $query->row;


        $sql = " SELECT `shipping_id` , `order_id` , `user_id` , `first_name` , `last_name` , `address_1` , `address_2` , `city` , `state` , `country` , `postal_code` , `phone`
		FROM `order_shipping`
		WHERE order_id =".$order_id;
        $query = $obj->db->query($sql);
        $shippingDetail = $query->row;


        $sql = " SELECT `birthdataid` , `day` , `month` , `year` , `untimed` , `hour` , `minute` , `gmt` , `zoneref` , `summerref` , `place` , `state` , `longitude` , `latitude` , `orderid` , `first_name` , `last_name`
		FROM `birthdata`
		WHERE orderid =".$order_id;
        $query = $obj->db->query($sql);
        $birthDetail = $query->rows;

        $returnValue = array("orderDetail"=>$orderDetail, "shippingDetail"=>$shippingDetail, "birthDetail"=>$birthDetail);

        return $returnValue;
    }

    public function GetUserIdByOrderId($order_id) {
    	$SQLQuery = "SELECT user_id FROM `order` WHERE order_id = :order_id ";
    	
    	$objMyPDO = $this->MyPDO->prepare($SQLQuery);
    	$objMyPDO->bindParam(':order_id', $order_id);
    	
    	$objMyPDO->execute();
    	$tmpCount = $objMyPDO->fetchColumn ();
    	 
    	return $tmpCount;
    	 
    	
//     	$obj = new cDatabase();

//         try {
//             $sql = " SELECT user_id FROM `order` WHERE order_id =".$order_id;
//             $query = $obj->db->query($sql);
//             $result = $query->row;
//             return $query->row['user_id'];
//         }
//         catch(Exception $ex) {
//             return $ex->getMessage();
//         }
    }

    public function GetPurchasedReportCountByUserId($user_id,$language_code) {
    	$SQLQuery = "SELECT count(o.order_id) as count
			FROM `order` o
					LEFT JOIN `product_items` pi ON pi.product_items_id = o.product_item_id
					LEFT JOIN  product p ON p.product_id = pi.product_id
					LEFT JOIN `product_description` pd ON pd.product_id = p.product_id
					LEFT JOIN `language` l ON l.language_id = pd.language_id
					LEFT JOIN `files` f ON f.file_id = p.image_id
			WHERE o.product_type in(5) and o.order_status = 9 and l.code = :language_code and o.user_id = :user_id";
    	
    	$objMyPDO = $this->MyPDO->prepare($SQLQuery);
    	$objMyPDO->bindParam(':language_code', $language_code);
    	$objMyPDO->bindParam(':user_id', $user_id);
    	
    	$objMyPDO->execute();
    	$tmpCount = $objMyPDO->fetchColumn ();
    	return $tmpCount;
    	
//         $obj = new cDatabase();
//         $sql = " SELECT count(o.order_id) as count FROM `order` o
// 				left join  `product_items` pi on pi.product_items_id = o.product_item_id 
// 				left join product p on p.product_id = pi.product_id
// 				left join `product_description` pd on pd.product_id = p.product_id
// 				left join `language` l on l.language_id = pd.language_id
// 				left join `files` f on f.file_id = p.image_id
// 				WHERE o.product_type in(5) and o.order_status = 9 and l.code = '".$language_code."' and o.user_id=".$user_id;

//         $query = $obj->db->query($sql);
//         $result = $query->row;
//         return $query->row['count'];
    }

    public function GetPurchasedReportByUserId($user_id, $language, $items_per_page, $start) {
    	$SQLQuery = "SELECT
						o.order_id,o.user_id,o.product_item_id ,pi.product_id, pd.productName , p.image_id, f.name, f.path,o.order_date
					FROM `order` o
							LEFT JOIN `product_items` pi ON pi.product_items_id = o.product_item_id
							LEFT JOIN `product` p ON p.product_id = pi.product_id
							LEFT JOIN `product_description` pd ON pd.product_id = p.product_id
							LEFT JOIN `language` l ON l.language_id = pd.language_id
							LEFT JOIN `files` f ON f.file_id = p.image_id
					WHERE o.product_type in(5) AND o.order_status = 9 AND l.code = :language_code AND o.user_id = :user_id
					ORDER BY o.order_date DESC
					LIMIT :StartIndex, :ItemsPerPage ";
    	
    	$objMyPDO = $this->MyPDO->prepare($SQLQuery);
    	$startIndex = intval($start);
    	$itemsPerPage = intval($items_per_page);
    	
    	$objMyPDO->bindParam(':language_code', $language);
    	$objMyPDO->bindParam(':user_id', $user_id);
    	$objMyPDO->bindValue(':StartIndex', $startIndex, PDO::PARAM_INT);
    	$objMyPDO->bindValue(':ItemsPerPage', $itemsPerPage, PDO::PARAM_INT);
    	
    	$objMyPDO->execute();
    	return $objMyPDO->fetchAll ( PDO::FETCH_ASSOC );
    	 
    	
//         $obj = new cDatabase();
//         $sql = " SELECT o.order_id,o.user_id,o.product_item_id ,pi.product_id, pd.productName , p.image_id, f.name, f.path,o.order_date
// 				FROM `order` o
// 				left join  `product_items` pi on pi.product_items_id = o.product_item_id 
// 				left join product p on p.product_id = pi.product_id
// 				left join `product_description` pd on pd.product_id = p.product_id
// 				left join `language` l on l.language_id = pd.language_id
// 				left join `files` f on f.file_id = p.image_id
// 				WHERE o.product_type in(5) and o.order_status = 9 and l.code = '".$language."' and o.user_id =".$user_id." ORDER BY o.order_date DESC limit ".$start.",".$items_per_page;	
//         $query = $obj->db->query($sql);
//         $result = $query->rows;
//         return $result;
    }


    public function GetPreviewReportByUserId($user_id,$language) {
    	$SQLQuery = "SELECT
				o.order_id,o.user_id,o.product_item_id ,pi.product_id, pd.productName , p.image_id, f.name, f.path,o.order_date
			FROM `order` o
					LEFT JOIN  `product_items` pi ON pi.product_items_id = o.product_item_id
					LEFT JOIN product p ON p.product_id = pi.product_id
					LEFT JOIN `product_description` pd ON pd.product_id = p.product_id
					LEFT JOIN `language` l ON l.language_id = pd.language_id
					LEFT JOIN `files` f ON f.file_id = p.image_id
			WHERE o.product_type in(4) and o.order_status IN (5 , 9) and o.delivery_option = 3  and l.code = :language_code and o.user_id = :user_id ";
    	
    	$objMyPDO = $this->MyPDO->prepare($SQLQuery);
    	$objMyPDO->bindParam(':language_code', $language);
    	$objMyPDO->bindParam(':user_id', $user_id);
    	
    	$objMyPDO->execute();
    	return $objMyPDO->fetchAll ( PDO::FETCH_ASSOC );
    	 
    	
//         $obj = new cDatabase();
//         $sql = " SELECT o.order_id,o.user_id,o.product_item_id ,pi.product_id, pd.productName , p.image_id, f.name, f.path,o.order_date
// 				FROM `order` o
// 				left join  `product_items` pi on pi.product_items_id = o.product_item_id 
// 				left join product p on p.product_id = pi.product_id
// 				left join `product_description` pd on pd.product_id = p.product_id
// 				left join `language` l on l.language_id = pd.language_id
// 				left join `files` f on f.file_id = p.image_id
// 				WHERE o.product_type in(4) and o.order_status IN (5 , 9) and o.delivery_option = 3  and l.code = '".$language."' and o.user_id =".$user_id;
//         $query = $obj->db->query($sql);
//         $result = $query->rows;
//         return $result;
    }

    public function GetLocationInformation($data) {
        $Longitude = $data->longitude;
        $Latitude = $data->latitude;

        if($Latitude < -90 && $Latitude > 90) {
            $Latitude = $Latitude * 3600;
            //$data->latitude = $Latitude;
        }

        if($Longitude < -180 && $Longitude > 180) {
            $Longitude = $Longitude * 3600;
            //$data->longitude = $Longitude;
        }

        $sql =  "select * from `acsatlas`".
                " where upper(placename)='".strtoupper($data->place)."' ".
                " and longitude ='".$Longitude."'".
                " and latitude ='".$Latitude."'".
                " order by lkey";
        $ACSRep = new  ACSRepository();

        $ACSCountry = new ACSStateList();
        $CountryName = $ACSCountry->getStateNameByAbbrev($data->state);

        //$Location = sprintf( "%s, %s",	$data->place, $data->state);
        $Location = sprintf( "%s, %s",	$data->place, $CountryName);

        $IsThere = $this->GetSummerTimeZoneANDTimeZone($Location, $data);

        if(count($IsThere) > 0 ) {
            $data->zoneref = $IsThere['m_timezone_offset'];
            $data->summerref = $IsThere['m_summertime_offset'];
        }
        else {
            $Result = $ACSRep->GetACSDataRow($sql);

            if($Result) {
                $acsTimeTable = new AcsTimetables();
                $acsTimeTable->setBirthdate( sprintf("%04d-%02d-%02d %02d:%02d:%02d",
                        $data->year, $data->month, $data->day,
                        $data->hour, $data->minute, 0) );

                $data->zoneref = $acsTimeTable->getZoneOffset($Result[0]['zone']);
                $data->summerref = $acsTimeTable->getTypeOffset($Result[0]['type']);
            }
        }
        return $data;
    }

    public function GetSummerTimeZoneANDTimeZone($location, $data) {
        $TimeZoneArray = array();

        if(extension_loaded('acsatlas')) {
            //Get the city info
            //error_log(sprintf("Checking ACS Location :: %s\n", $location), 1, "parmaramit1111@gmail.com");
            $city_info = acs_lookup_city($location);

            if (!$city_info) {
                return $TimeZoneArray;
                //die('The city lookup was unsuccessful.');
            }
            extract($city_info);
            // $city_info = Array (
            // [city_index] => 4360
            // [country_index] => 4
            // [city] => Pomona
            // [county] => Los Angeles
            // [country] => California
            // [countydup] => 37
            // [latitude] => 122599
            // [longitude] => 423905
            // [typetable] => 83
            // [zonetable] => 7200)

            //Get the time zone info
            //$time_info = acs_time_change_lookup($month, $day, $year, $hour, $minute, $zonetable, $typetable);
//            $time_info = acs_time_change_lookup($this->m_birth_month, $this->m_birth_day, $this->m_birth_year,
//                    $this->m_birth_hour, $this->m_birth_minute, $zonetable, $typetable);

            $time_info = acs_time_change_lookup($data->month, $data->day, $data->year,
                    $data->hour, $data->minute, $zonetable, $typetable);

            if (!$time_info) {
                return $TimeZoneArray;
                //die('The time zone lookup was unsuccessful.');
            }
            extract($time_info);
            // $time_info = Array  (
            // [zone] => 7200
            // [type] => 1
            // [abbr] => PDT
            // [flagout] => 0)

            if($type >= 0) {
                //Get the offset in hours from UTC
                $time_types = array(0,1,1,2); //assume $time_type < 4
                $offset = ($zone/900) - $time_types[$type];

                //$this->m_timezone_offset = $timetables->getZoneOffset($place->zone);
                //$this->m_summertime_offset = $timetables->getTypeOffset($place->type);

                $TimeZoneArray["m_timezone_offset"] = ($zone/900);
                $TimeZoneArray["m_summertime_offset"] = $time_types[$type];
            }
        }

        return $TimeZoneArray;
    }

    public function SaveOrderToken($order_id,$token,$product_item_id) {
    	$SQLQuery = "INSERT INTO `order_token` (order_id, token, product_item_id) VALUES (:order_id, :token, :product_item_id)";
    	$objMyPDO = $this->MyPDO->prepare($SQLQuery);
    	
    	$objMyPDO->bindParam(':order_id', $order_id);
    	$objMyPDO->bindParam(':token', $token);
    	$objMyPDO->bindParam(':product_item_id', $product_item_id);
    	
    	$objMyPDO->execute();
    	$id = $this->MyPDO->lastInsertId();
    	return $id;
    	
//         $obj = new cDatabase();
//         $sql = "insert into order_token set order_id='".$order_id."',";
//         $sql .= " token='".$token."',";
//         $sql .= " product_item_id='".$product_item_id."'";

//         $query = $obj->db->query($sql);
//         if($query) {
//             $id = $obj->db->getLastId();
//         } else {
//             $id = 0;
//         }
//         return $id;
    }

    public function GetOrderToken($token) {
    	$SQLQuery = "SELECT ot.token_id, ot.order_id, ot.token, ot.download_count, ot.product_item_id , o.language_code
			FROM order_token ot LEFT JOIN `order` o on o.order_id = ot.order_id
			WHERE ot.token = :token ";
    	$objMyPDO = $this->MyPDO->prepare($SQLQuery);
    	
    	$objMyPDO->bindParam(':token', $token);
    	$objMyPDO->execute();
    	
    	return $objMyPDO->fetch();
    	 
    	
//         $obj = new cDatabase();
//         /*$sql = "select token_id, order_id,token,download_count,product_item_id from order_token";
//         $sql .= " where token='".$token."'";*/
//         $sql = "select ot.token_id, ot.order_id, ot.token, ot.download_count, ot.product_item_id , o.language_code
// 				from order_token ot
// 				left join `order` o on o.order_id = ot.order_id";
//         $sql .= " where ot.token='".$token."' ";
//         $query = $obj->db->query($sql);
//         $result = $query->row;
//         return $result;
    }

    public function UpdateOrderToken($download_count,$token) {
    	$SQLQuery = "UPDATE `order_token` SET download_count = :download_count WHERE token = :token";
    	$objMyPDO = $this->MyPDO->prepare($SQLQuery);
    	
    	$objMyPDO->bindParam(':token', $token);
    	$objMyPDO->bindParam(':download_count', $download_count);
    	
    	$objMyPDO->execute();
    	return true;
    	
//         $obj = new cDatabase();
//         $sql = "update order_token set download_count='".$download_count."'";
//         $sql .= " where token='".$token."'";
//         $query = $obj->db->query($sql);

//         return true;
    }

    public function SaveOrderTransactionDetail($data) {
    	$SQLQuery = "INSERT INTO `transactions`
				(PaymentStatus, PaymentDate, PaymentDetail, OrderID, FullName, PayerEmail, Amount, CurrencyCode, PaypalTxnID)
			VALUES
				(:PaymentStatus, :PaymentDate, :PaymentDetail, :OrderID, :FullName, :PayerEmail, :Amount, :CurrencyCode, :PaypalTxnID) ";
    	
    	$objMyPDO = $this->MyPDO->prepare($SQLQuery);
    	
    	$objMyPDO->bindParam(':PaymentStatus', $data->PaymentStatus);
    	$objMyPDO->bindParam(':PaymentDate', $data->PaymentDate);
    	$objMyPDO->bindParam(':PaymentDetail', $data->PaymentDetail);
    	$objMyPDO->bindParam(':OrderID', $data->OrderID);
    	$objMyPDO->bindParam(':FullName', $data->FullName);
    	$objMyPDO->bindParam(':PayerEmail', $data->PayerEmail);
    	$objMyPDO->bindParam(':Amount', $data->Amount);
    	$objMyPDO->bindParam(':CurrencyCode', $data->CurrencyCode);
    	$objMyPDO->bindParam(':PaypalTxnID', $data->PaypalTxnID);
    	
    	$objMyPDO->execute();
    	
    	return true;
    	
//     	   $obj = new cDatabase();
//         $sql = "insert into `transactions` set ";
//         $sql .= " PaymentStatus='".$data->PaymentStatus."',";
//         $sql .= " PaymentDate='".$data->PaymentDate."',";
//         $sql .= " PaymentDetail='".$data->PaymentDetail."',";
//         $sql .= " OrderID='".$data->OrderID."',";
//         $sql .= " FullName='".$obj->db->escape($data->FullName)."',";
//         $sql .= " PayerEmail='".$data->PayerEmail."',";
//         $sql .= " Amount='".$data->Amount."',";
//         $sql .= " CurrencyCode='".$data->CurrencyCode."',";
//         $sql .= " PaypalTxnID='".$data->PaypalTxnID."'";
//         $query = $obj->db->query($sql);
//         return true;
    }

    public function GetOrderIdToUpdatePreviewReportOrder($user_id) {
    	$SQLQuery = "SELECT o.order_id FROM `order` o LEFT JOIN `user` u ON u.UserId = o.user_id
			WHERE o.delivery_option = 3 AND u.status = 1 and o.user_id = :user_id ";
    	$objMyPDO = $this->MyPDO->prepare($SQLQuery);
    	
    	$objMyPDO->bindParam(':user_id', $user_id);
    	$objMyPDO->execute();
    	return $objMyPDO->fetchAll ( PDO::FETCH_ASSOC );
    	
//         $obj = new cDatabase();
//         $sql = "select o.order_id
// 				from `order` o
// 				left join `user` u on u.UserId = o.user_id
// 				where o.delivery_option = 3 and u.status = 1 and o.user_id = ".$user_id;
//         $query = $obj->db->query($sql);
//         $result = $query->rows;
//         return $result;
    }

    public function UpdateOrderStatusForPreviewReport($order_id) {
    	$SQLQuery = "UPDATE `order` SET order_status = 12 WHERE order_id = :order_id";
    	$objMyPDO = $this->MyPDO->prepare($SQLQuery);
    	
    	$objMyPDO->bindParam(':order_id', $order_id);
    	$objMyPDO->execute();
    	return true;
    	
    }

    public function UpdateOrderBirthDataForPreviewReport($data) {
        $data = $this->GetLocationInformation($data);

        /*
        $SQLQuery = "UPDATE `birthdata` SET
						day = :day, month = :month, year = :year, untimed = :untimed, hour = :hour, minute = :minute,
						gmt = :gmt, zoneref = :zoneref, summerref = :summerref, place = :place, state = :state, longitude = :longitude, 
        				latitude = :latitude, Gender = :Gender
					WHERE orderid = :orderid ";
        */
        
        $SQLQuery = "UPDATE `birthdata` SET
						day = :day, month = :month, year = :year, untimed = :untimed, hour = :hour, minute = :minute,
						gmt = :gmt, zoneref = :zoneref, summerref = :summerref, place = :place, state = :state, longitude = :longitude,
        				latitude = :latitude ";
		$SQLQueryWhere = " WHERE orderid = :orderid ";
        
        if(isset($data->gender) && $data->gender != "") {
        	$SQLQuery .= ", Gender = :Gender ";
        } 
        
        $SQLQuery = $SQLQuery .$SQLQueryWhere;
        
        $objMyPDO = $this->MyPDO->prepare($SQLQuery);
        $objMyPDO->bindParam(':day', $data->day);
        $objMyPDO->bindParam(':month', $data->month);
        $objMyPDO->bindParam(':year', $data->year);
        $objMyPDO->bindParam(':untimed', $data->untimed);
        $objMyPDO->bindParam(':hour', $data->hour);
        $objMyPDO->bindParam(':minute', $data->minute);
        $objMyPDO->bindParam(':gmt', $data->gmt);
        $objMyPDO->bindParam(':zoneref', $data->zoneref);
        $objMyPDO->bindParam(':summerref', $data->summerref);
        $objMyPDO->bindParam(':place', $data->place);
        $objMyPDO->bindParam(':state', $data->state);
        $objMyPDO->bindParam(':longitude', $data->longitude);
        $objMyPDO->bindParam(':latitude', $data->latitude);

        if(isset($data->gender) && $data->gender != "") {
        	$objMyPDO->bindParam(':Gender', $data->gender);
        }
        
        $objMyPDO->bindParam(':orderid', $data->orderid);
        
        try {
        	$objMyPDO->execute();
        	return true;
        } catch (Exception $ex) {
        	return false;
        }        
//         $obj = new cDatabase();
//         $sql = " update birthdata set";
//         $sql .=" day = '".$data->day."', month = '".$data->month."', year = '".$data->year."', untimed = '".$data->untimed."', hour = '".$data->hour."', minute = '".$data->minute."', 
// 				gmt = '".$data->gmt."', zoneref = '".$data->zoneref."', summerref = '".$data->summerref."',
// 				place = '".$data->place."', state = '".$data->state."', longitude = '".$data->longitude."', latitude = '".$data->latitude."', Gender = '".$data->gender."'  ";
//         $sql .=" where orderid = ".$data->orderid;        
//         $query = $obj->db->query($sql);
//         if($query) 
//             return true;
//         else 
//             return false;        
    }


    public function GetOrderDetailToSendPaymentAcceptMailForReport($order_id,$language_code) {
        $obj = new cDatabase();
        $sql =" SELECT o.order_id, o.product_item_id, o.language_code, o.email_id, o.product_type,
				o.delivery_option, pi.product_id, pd.productName, bd.first_name, bd.last_name
				FROM `order` o
				left join birthdata bd on bd.orderid = o.order_id
				left join product_items pi on pi.product_items_id = o.product_item_id
				left join product_description pd on pd.product_id = pi.product_id
				left join `language` l on l.language_id = pd.language_id
				where o.order_id = ".$order_id." and l.code = '".$language_code."'";
        $query = $obj->db->query($sql);
        return $query->row;
    }


     public function GetOrderTransactionDetailById($OrderId) {
        $obj = new cDatabase();
        $sql = "SELECT PaypalTxnID FROM `transactions` WHERE OrderID='".$OrderId."'";
        $query = $obj->db->query($sql);

        return $query->row;
    }    

    public function GetFreeSoftwareDownloadByUserId($UserId, $Language) {
    	$obj = new cDatabase();
    
    	$sql = "SELECT o.order_id,o.user_id,o.product_item_id ,pi.product_id, pd.productName , p.image_id, f.name, f.path, o.order_date, ot.token
				FROM `order` o
						left join `product_items` pi on pi.product_items_id = o.product_item_id
						left join  product p on p.product_id = pi.product_id
						left join `product_description` pd on pd.product_id = p.product_id
						left join `language` l on l.language_id = pd.language_id
						left join `files` f on f.file_id = p.image_id
    					left join `order_token` ot on ot.order_id = o.order_id
				WHERE o.product_type = 1 AND o.order_status IN (9) AND o.delivery_option = 3  AND l.code = '".$Language."' AND o.user_id =".$UserId;
    	
    	$query = $obj->db->query($sql);
    	$result = $query->rows;
    	return $result;    	
    }
    
    public function SaveUpsaleOrder($NewOrderId, $OldOrderId) {
    	
    	$AddId = 0;
    	$SQLQuery = "INSERT INTO `upsale_orders` (`order_id`, `new_order_id`) value (:order_id, :new_order_id)";
    	
    	$objMyPDO = $this->MyPDO->prepare($SQLQuery);
    	
    	$objMyPDO->bindParam(':order_id', $OldOrderId);
    	$objMyPDO->bindParam(':new_order_id', $NewOrderId);
    	
    	$objMyPDO->execute();
    	$AddId = $this->MyPDO->lastInsertId();
    	return $AddId;
    	
//     	$obj = new cDatabase();
//     	$AddId = 0;
//     	$sql = "INSERT INTO `upsale_orders` (`order_id`, `new_order_id`) value ($OldOrderId,$NewOrderId)";
// 		try {   	 
//     		$query = $obj->db->query($sql);
//     		if($query) {
//     			$AddId = $obj->db->getLastId();
//     		}
// 		} catch(Exception $ex) {
// 			$AddId = 0;
// 		}
// 		return $AddId;
    }
    
    public function GetOrderDetailForReport($OrderID) {
    	$SQLQuery = "SELECT
				o.order_id, o.product_item_id, b.first_name, b.last_name, o.email_id,
				o.language_code, o.price, o.discount, o.currency_code
			FROM `order` o, birthdata b
			WHERE o.order_id = b.orderid AND o.order_id = :order_id ";
    	
    	$objMyPDO = $this->MyPDO->prepare ( $SQLQuery );
    	$objMyPDO->bindParam(':order_id', $OrderID);
    	$objMyPDO->execute ();
    	
    	return $objMyPDO->fetchAll ( PDO::FETCH_ASSOC );
    	 
    	
//     	$obj = new cDatabase();
//     	$sql = "SELECT  o.order_id, o.product_item_id, b.first_name, b.last_name, o.email_id, o.language_code, o.price, o.discount, o.currency_code    				
// 				FROM `order` o, birthdata b WHERE o.order_id = b.orderid AND o.order_id = ".$OrderID;
//     	$query = $obj->db->query($sql);
//     	$result = $query->rows;
//     	return $result;
    }
    

    public function GetOrderDetailForSoftware($OrderID) {
    	$SQLQuery = "SELECT
				o.order_id, o.product_item_id, b.first_name, b.last_name, o.email_id, o.language_code,
				o.price, o.discount, o.currency_code, o.product_type
			FROM `order` o LEFT JOIN order_shipping b ON o.order_id = b.order_id
			WHERE o.order_id = :order_id ";
    	
    	$objMyPDO = $this->MyPDO->prepare ( $SQLQuery );
    	$objMyPDO->bindParam(':order_id', $OrderID);
    	$objMyPDO->execute ();
    	
    	return $objMyPDO->fetchAll ( PDO::FETCH_ASSOC );
    	
//     	$obj = new cDatabase();
//     	$sql = "SELECT  o.order_id, o.product_item_id, b.first_name, b.last_name, o.email_id, o.language_code, o.price, o.discount, o.currency_code, o.product_type
// 				FROM `order` o LEFT JOIN order_shipping b ON o.order_id = b.order_id 
//     			WHERE o.order_id = ".$OrderID;
    	
//     	$query = $obj->db->query($sql);
//     	$result = $query->rows;
//     	return $result;
    }

    public function GetBundleOrderDetailToSendPaymentAcceptMailForReport($order_id, $language_code, $CategoryId = 7) {
    	$obj = new cDatabase();    	
    	$sql =" SELECT 	o.order_id, o.product_item_id, o.language_code, o.email_id, o.product_type, pi.category_id, 
						o.delivery_option, pi.product_bundle_id AS product_id, pi.NAME productName, bd.first_name, bd.last_name
				FROM `order` o
							LEFT JOIN order_shipping bd on bd.order_id = o.order_id
							LEFT JOIN `product_bundle` pi on pi.product_items_ids = o.product_item_id
							LEFT JOIN `language` l on l.language_id = pi.language_id 
				WHERE o.order_id = $order_id AND l.code = '$language_code' AND pi.category_id = $CategoryId ";
    	$query = $obj->db->query($sql);
    	return $query->row;
    }    
}
?>
