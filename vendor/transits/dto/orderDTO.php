<?php
if(!class_exists("orderDTO")) {
	class orderDTO {
		function __construct() {
			$this->order_id="";
			$this->product_item_id="";
			$this->price="";
			$this->discount="";
			$this->user_id="";
			$this->delivery_option="";
			$this->order_status="";
			$this->order_date="";
			$this->confirm_payment_date="";
			$this->product_type="";
			$this->chk_for_register="0";
			$this->currency_code="USD";
			$this->shipping_charge="0";
			$this->email_id="";
			$this->language_code="en";
			$this->portalid= 2;
			$this->payment_method = 1;
		}
		public $order_id;
		public $product_item_id;
		public $price;
		public $discount;
		public $user_id;
		public $delivery_option;
		public $order_status;
		public $order_date;
		public $confirm_payment_date;
		public $product_type;
		public $chk_for_register;
		public $currency_code;
		public $shipping_charge;
		public $email_id;
		public $language_code;
		public $portalid;
		public $payment_method;

	}
}
if(!class_exists("orderShippingDTO")) {
	class orderShippingDTO {
		function __construct() {
			$this->shipping_id="";
			$this->order_id="";
			$this->user_id="";
			$this->first_name="";
			$this->last_name="";
			$this->address_1="";
			$this->address_2="";
			$this->city="";
			$this->state="";
			$this->country="";
			$this->postal_code="";
			$this->phone="";
		}

		public $shipping_id;
		public $order_id;
		public $user_id;
		public $first_name;
		public $last_name;
		public $address_1;
		public $address_2;
		public $city;
		public $state;
		public $country;
		public $postal_code;
		public $phone;
	}
}

if(!class_exists("userBirthDataDTO")) {

	class userBirthDataDTO {
		function __construct() {
			$this->birthdataid='';
			$this->day='';
			$this->month='';
			$this->year='';
			$this->untimed='';
			$this->hour='';
			$this->minute='';
			$this->gmt='';
			$this->zoneref='';
			$this->summerref='';
			$this->place='';
			$this->state='';
			$this->longitude='';
			$this->latitude='';
			$this->orderid='';
			$this->first_name='';
			$this->last_name='';
			$this->gender = '';
		}


		public $birthdataid;
		public $day;
		public $month;
		public $year;
		public $untimed;
		public $hour;
		public $minute;
		public $gmt;
		public $zoneref;
		public $summerref;
		public $place;
		public $state;
		public $longitude;
		public $latitude;
		public $orderid;
		public $first_name;
		public $last_name;
		public $gender;
	}
}

if(!class_exists("loversDataDTO")) {
	class loversDataDTO {
		function __construct() {
			$this->lovers_report_data_id='';
			$this->person_name='';
			$this->gender='';
			$this->order_id='';
			$this->birth_data_id='';
		}

		public $lovers_report_data_id;
		public $person_name;
		public $gender;
		public $order_id;
		public $birth_data_id;
	}
}
?>