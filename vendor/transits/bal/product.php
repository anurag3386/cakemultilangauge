<?php
error_reporting ( E_ALL );
require_once("include.php");
require_once(DALPATH."/productRepository.php");

if (!class_exists('Product')) {

	class Product {
		public function GetProductByCategoryId($category_id,$locale) {
			$productRepository = new ProductRepository();
			try {
				$result = $productRepository->GetProductListByCategoryId($category_id,$locale);

				foreach($result as $Key => $Item){
					$result[$Key]['productName'] = trim(stripcslashes(html_entity_decode(utf8_decode(utf8_encode($Item['productName'])))));
				}
				print_r($result);
				//exit;
				//return ($result);
			}
			catch(Exception $ex) {
				die($ex->getMessage());
			}
		}

		public function GetProductListByCategoryId($category_id,$locale) {
			//echo 'call';
			$productRepository = new ProductRepository();
			try {
				$result = $productRepository->GetProductListByCategoryId($category_id,$locale);

				foreach($result as $Key => $Item){
					$result[$Key]['productName'] = trim(stripcslashes(html_entity_decode(utf8_decode(utf8_encode($Item['productName'])))));
					//echo $Item['productName'] . "<br />";
				}
				return ($result);
			}
			catch(Exception $ex) {
				die($ex->getMessage());
			}
		}

		public function GetProductListWithDetailsByCategoryId($category_id,$locale) {
			$productRepository = new ProductRepository();
			try {
				$result = $productRepository->GetProductListWithDetailsByCategoryId($category_id,$locale);
				//print_r($result);
				//exit;
				return ($result);
			}
			catch(Exception $ex) {
				die($ex->getMessage());
			}
		}

		public function GetProductDetailsByProductId($product_id,$locale) {
			$productRepository = new ProductRepository();
			try {
				$result = $productRepository->GetProductDetailsByProductId($product_id,$locale);
				//print_r($result);
				//exit;
				return ($result);
			}
			catch(Exception $ex) {
				die($ex->getMessage());
			}
		}

		public function GetProductItemsDetailsByProductId($product_id,$locale) {
			$productRepository = new ProductRepository();
			try {
				$result = $productRepository->GetProductItemsDetailsByProductId($product_id,$locale);
				//print_r($result);
				//exit;
				return ($result);
			}
			catch(Exception $ex) {
				die($ex->getMessage());
			}
		}

		public function GetProductScreenshotByProductId($product_id,$locale) {
			$productRepository = new ProductRepository();
			try {
				$result = $productRepository->GetProductScreenshotByProductId($product_id,$locale);
				//print_r($result);
				//exit;
				return ($result);
			}
			catch(Exception $ex) {
				die($ex->getMessage());
			}
		}

		public function GetProductCelebritySampleByProductId($product_id,$locale) {
			$productRepository = new ProductRepository();
			try {
				$result = $productRepository->GetProductCelebritySampleByProductId($product_id,$locale);
				//print_r($result);
				//exit;
				return ($result);
			}
			catch(Exception $ex) {
				die($ex->getMessage());
			}
		}

		public function GetProductPrice($product_item_id) {
			$productRepository = new ProductRepository();
			try {
				$result = $productRepository->GetProductPrice($product_item_id);
				//print_r($result);
				//exit;
				return ($result);
			}
			catch(Exception $ex) {
				die($ex->getMessage());
			}
		}

		public function GetProductFreeDownloads($product_id) {
			$productRepository = new ProductRepository();
			try {
				$result = $productRepository->GetProductFreeDownloads($product_id);
				//print_r($result);
				//exit;
				return ($result);
			}
			catch(Exception $ex) {
				die($ex->getMessage());
			}
		}

		public function GetProductFreeDownloadsById($product_download_id) {
			$productRepository = new ProductRepository();
			try {
				$result = $productRepository->GetProductFreeDownloadsById($product_download_id);
				//print_r($result);
				//exit;
				return ($result);
			}
			catch(Exception $ex) {
				die($ex->getMessage());
			}
		}

		public function GetProductFreeDownloadsByProductItemId($product_items_id,$language) {
			$productRepository = new ProductRepository();
			try {
				$result = $productRepository->GetProductFreeDownloadsByProductItemId($product_items_id,$language);
				//print_r($result);
				//exit;
				return ($result);
			}
			catch(Exception $ex) {
				die($ex->getMessage());
			}
		}

		public function GetProductBuyCD($product_id,$category_id,$language_code) {
			$productRepository = new ProductRepository();
			try {
				$result = $productRepository->GetProductBuyCD($product_id,$category_id,$language_code);
				//print_r($result);
				//exit;
				return ($result);
			}
			catch(Exception $ex) {
				die($ex->getMessage());
			}
		}

		public function GetProductForRegisterSharware($product_id,$category_id,$language_code) {
			$productRepository = new ProductRepository();
			try {
				$result = $productRepository->GetProductForRegisterSharware($product_id,$category_id,$language_code);
				//print_r($result);
				//exit;
				return ($result);
			}
			catch(Exception $ex) {
				die($ex->getMessage());
			}
		}

		public function GetProductBundle($category_id,$language_code) {
			$productRepository = new ProductRepository();
			try {
				$result = $productRepository->GetProductBundle($category_id,$language_code);
				$returnValue = array();
				if(count($result)>0) {
					$productBundleArray = array();
					for($i=0;$i<count($result);$i++) {
						$productBundle = $result[$i];
						$productPrice = $productRepository->GetProductBundlePrice($result[$i]['product_bundle_id']);

						//$mergeArray = array_merge($productBundle,$productPrice);
						$mergeArray = array();
						$mergeArray[] = $productBundle;
						$mergeArray[] = $productPrice;
						$returnValue[] = $mergeArray;
					}
				}
				//print_r($result);
				//exit;
				return ($returnValue);
			}
			catch(Exception $ex) {
				die($ex->getMessage());
			}
		}

		public function GetProductBuyReport($product_id,$category_id,$language_code) {
			$productRepository = new ProductRepository();
			try {
				$result = $productRepository->GetProductBuyReport($product_id,$language_code);
				//print_r($result);
				//exit;
				return ($result);
			}
			catch(Exception $ex) {
				die($ex->getMessage());
			}
		}

		public function GetProductItemsById($product_id,$language_code) {
			$productRepository = new ProductRepository();
			try {
				$result = $productRepository->GetProductItemsById($product_id,$language_code);
				/*$returnValue = array();
				 if(count($result)>0)
				 {
				$productBundleArray = array();
				for($i=0;$i<count($result);$i++)
				{
				$productBundle = $result[$i];
				$productPrice = $productRepository->GetProductItemsPriceById($result[$i]['product_items_id']);

				$mergeArray = array();
				$mergeArray[] = $productBundle;
				$mergeArray[] = $productPrice;
				$returnValue[] = $mergeArray;
				}
				}*/
				//print_r($result);
				//exit;
				return ($result);

			}
			catch(Exception $ex) {
				die($ex->getMessage());
			}
		}

		public function GetProductItemsByProductItemsId($product_items_id,$language_code) {
			$productRepository = new ProductRepository();
			try {
				$result = $productRepository->GetProductItemsByProductItemsId($product_items_id,$language_code);
				return ($result);

			}
			catch(Exception $ex) {
				die($ex->getMessage());
			}
		}

		public function GetProductTestimonial($product_items_id,$language_code) {
			$productRepository = new ProductRepository();
			try {
				$result = $productRepository->GetProductTestimonial($product_items_id,$language_code);
				return ($result);

			}
			catch(Exception $ex) {
				die($ex->getMessage());
			}
		}

		public function GetProductLanguageAwailableList($product_items_id) {
			$productRepository = new ProductRepository();
			try {
				$result = $productRepository->GetProductLanguageAwailableList($product_items_id);
				return ($result);
			}
			catch(Exception $ex) {
				die($ex->getMessage());
			}
		}

		public function GetCurrencyList() {
			$productRepository = new ProductRepository();
			try {
				$result = $productRepository->GetCurrencyList();
				return ($result);

			}
			catch(Exception $ex) {
				die($ex->getMessage());
			}
		}

		public function GetConsultationList($product_id,$languageId) {
			$productRepository = new ProductRepository();
			$resultConsultation = array();
			try {
				$result = $productRepository->GetConsultationList($product_id,$languageId);

				if(count($result)>0) {
					//echo count($result);
					$product_item_id = 0;
					for($i=0;$i<count($result);$i++) {
						if($product_item_id == $result[$i]['product_items_id']) {
							//echo 'if<br>';
							$objPrice = new ConsultationPriceDTO();
							$objPrice->currency_code = $result[$i]['code'];
							$objPrice->currency_id = $result[$i]['currency_id'];
							$objPrice->currency_name = $result[$i]['currency_name'];
							$objPrice->currency_symbol = $result[$i]['symbol'];
							$objPrice->price 			= $result[$i]['price_1'];
							$objPrice->product_price_id = $result[$i]['product_price_id'];

							$obj->price[] = $objPrice;

							if($i == count($result)-1) {
								$resultConsultation[] = $obj;
							}
						}
						else {
							//echo 'else<br>';
							if($product_item_id != 0) {
								//echo 'else else<br>';
								$resultConsultation[] = $obj;
							}
							$product_item_id = $result[$i]['product_items_id'];
							$obj = new ConsultationDTO();
							$obj->product_item_id = $result[$i]['product_items_id'];
							$obj->product_name = $result[$i]['product_name'];

							$objPrice = new ConsultationPriceDTO();
							$objPrice->currency_code = $result[$i]['code'];
							$objPrice->currency_id = $result[$i]['currency_id'];
							$objPrice->currency_name = $result[$i]['currency_name'];
							$objPrice->currency_symbol = $result[$i]['symbol'];
							$objPrice->price 			= $result[$i]['price_1'];
							$objPrice->product_price_id = $result[$i]['product_price_id'];

							$obj->price[] = $objPrice;
						}

					}
				}

				return ($resultConsultation);

			}
			catch(Exception $ex) {
				die($ex->getMessage());
			}
		}

		public function GetProductIdFromProductItemId($ProductItemsID) {
			$productRepository = new ProductRepository();
			try {
				return $productRepository->GetProductIdFromProductItemId($ProductItemsID);
			}
			catch(Exception $ex) {
				die($ex->getMessage());
			}
		}

		public function GetDiscountedPricesByProductAndCurrency($ProductItemId, $CurrencyId) {
			$productRepository = new ProductRepository();
			try {
				return $productRepository->GetDiscountedPricesByProductAndCurrency($ProductItemsID);
			}
			catch(Exception $ex) {
				die($ex->getMessage());
			}
		}

		public function GetProductIdByPageURL($PageURL, $LanguageCode) {
			$productRepository = new ProductRepository();
			try {
				return $productRepository->GetProductIdByPageURL($PageURL, $LanguageCode);
			}
			catch(Exception $ex) {
				die($ex->getMessage());
			}
		}

		public function GetMetaData($ProductOrPageId, $Locale) {
			$productRepository = new ProductRepository();
			try {
				$result = $productRepository->GetMetaData($ProductOrPageId, $Locale);
				return ($result);
			}
			catch(Exception $ex) {
				die($ex->getMessage());
			}
		}

		public function GetProductWithDefaultCurrency($product_id,$category_id,$language_code) {
			$productRepository = new ProductRepository();
			try {
				$result = $productRepository->GetProductWithDefaultCurrency($product_id,$category_id,$language_code);
				return ($result);
			}
			catch(Exception $ex) {
				die($ex->getMessage());
			}
		}

		public function GetProductForRegisterSharwareWithDefaultCurrency($product_id,$category_id,$language_code) {
			$productRepository = new ProductRepository();
			try {
				$result = $productRepository->GetProductForRegisterSharwareWithDefaultCurrency($product_id,$category_id,$language_code);
				//print_r($result);
				return ($result);
			}
			catch(Exception $ex) {
				die($ex->getMessage());
			}
		}

		public function GetProductBuyReportWithDefaultCurrency($product_id,$category_id,$language_code) {
			$productRepository = new ProductRepository();
			try {
				$result = $productRepository->GetProductBuyReportWithDefaultCurrency($product_id,$language_code);
				//print_r($result);
				//exit;
				return ($result);
			}
			catch(Exception $ex) {
				die($ex->getMessage());
			}
		}

		public function GetProductBundlePriceWithDefaultCurrency($CategoryId, $LanguageCode) {
			$productRepository = new ProductRepository();
			try {
				$result = $productRepository->GetProductBundle($CategoryId, $LanguageCode);
				$returnValue = array();
				if(count($result)>0) {
					$productBundleArray = array();
					for($i=0;$i<count($result);$i++) {
						$productBundle = $result[$i];
						$productPrice = $productRepository->GetProductBundlePriceWithDefaultCurrency($result[$i]['product_bundle_id'], $LanguageCode);

						$mergeArray = array();
						$mergeArray[] = $productBundle;
						$mergeArray[] = $productPrice;
						$returnValue[] = $mergeArray;
					}
				}
				return ($returnValue);
			}
			catch(Exception $ex) {
				die($ex->getMessage());
			}
		}

		public function GetProductBundleIdFromProductItemId($ProductItemsID, $CategoryType, $LanguageId) {
			$productRepository = new ProductRepository();
			return $productRepository->GetProductBundleIdFromProductItemId($ProductItemsID, $CategoryType, $LanguageId);
		}
		
		public function GetProductBundleByWithPriceDefaultCurrency($ProductItemID, $CategoryType, $LanguageId) {
			$productRepository = new ProductRepository();
			try {
				$result = $productRepository->GetProductBundleById($ProductItemID, $CategoryType, $LanguageId);
								
				$returnValue = array();
				
				if(count($result)>0) {
					
					$productBundleArray = array();
					for($i=0;$i<count($result);$i++) {
						$productBundle = $result[$i];
						$productPrice = $productRepository->GetProductBundlePriceWithDefaultCurrency($result[$i]['product_bundle_id'], $LanguageId);
		
						$mergeArray = array();
						$mergeArray[] = $productBundle;
						$mergeArray[] = $productPrice;
						$returnValue[] = $mergeArray;
					}
				}
				return ($returnValue);
			}
			catch(Exception $ex) {
				die($ex->getMessage());
			}
		}
		
	}
}

if(isset($_REQUEST['task'])) {
	//print_r($_REQUEST);
	if($_REQUEST['task'] == 'GetProductListByCategoryId') {
		$language = 'en';
		if(isset($_REQUEST['language'])) {
			$language = $_REQUEST['language'];
		}
		$objProduct = new Product();
		$result = $objProduct->GetProductListByCategoryId($_REQUEST['category'],$language);

		header('Content-Type: text/html; charset=utf-8');
		echo json_encode($result);
	}
}


if (!class_exists('ConsultationDTO')) {
	class ConsultationDTO {

		function __construct() {
			$this->product_id='';
			$this->product_name='';
			$this->discription='';
			$this->product_item_id='';
			$this->price = array();
		}

		public $product_id;
		public $product_name;
		public $discription;
		public $product_item_id;
		public $price;
	}
}

if (!class_exists('ConsultationPriceDTO')) {
	class ConsultationPriceDTO {
		function __construct() {
			$this->product_price_id='';
			$this->price='';
			$this->currency_id='';
			$this->currency_name='';
			$this->currency_symbol='';
			$this->currency_code='';
			$this->name='';
		}
		public $product_price_id;
		public $price;
		public $currency_id;
		public $currency_name;
		public $currency_symbol;
		public $currency_code;
		public $name;
	}
}
?>
