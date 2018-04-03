<?php
//require_once("resultStateDTO.php");
if(!class_exists("SubscriptionDTO")) {
	class SubscriptionDTO
	{
		function __construct() {
			$this->subscription_id=0;
			$this->product_id=0;
			$this->early_expiry_days=0;
			$this->late_expiry_days=0;
			$this->is_free_subscription=1;
			$this->duration=1;
			$this->product_name='';
			$this->discription='';
			$this->image_name='';
			$this->image_path='';

			//$this->resultState = new ResultStateDTO();
			$this->subscription_price = array();
		}
			
		public $subscription_id;

		public $parent_subscription_id;
		public $product_id;
		public $early_expiry_days;
		public $late_expiry_days;
		public $is_free_subscription;
		public $duration;
		public $subscription_price;
		public $product_name;
		public $discription;
		public $image_name;
		public $image_path;
	}
}

if(!class_exists("SubscriptionPriceDTO")) {
	class SubscriptionPriceDTO
	{
		function __construct() {
			$this->subscription_id=0;
			$this->subscription_price_id=0;
			$this->currency_id=0;
			$this->price=0;
			$this->renew_price=0;
			$this->upgrade_price=0;

			$this->currency_name=0;
			$this->currency_symbol=0;
			$this->currency_code=0;
		}

		public $subscription_id;
		public $subscription_price_id;
		public $currency_id;
		public $price;
		public $renew_price;
		public $upgrade_price;

		public $currency_name;
		public $currency_symbol;
		public $currency_code;
	}
}
if(!class_exists("SubscriptionStatusDTO")) {
	class SubscriptionStatusDTO
	{
		function __construct() {
			$this->subscription_status_id=0;
			$this->name='';
			$this->description='';
			$this->language_id=0;
		}

		public $subscription_status_id;
		public $name;
		public $description;
		public $language_id;

	}
}

if(!class_exists("SubscriptionUserDTO")) {
	class SubscriptionUserDTO
	{
		public $subscription_user_id;
		public $subscription_id;
		public $user_id;
		public $start_date;
		public $end_date;
		public $status;
		public $orderId;
		public $payPalSubScrId;

		function __construct() {
			$this->orderId = 0;
			$this->payPalSubScrId = '';
		}
	}
}
?>