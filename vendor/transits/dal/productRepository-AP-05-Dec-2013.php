<?php
try {
	//require_once(ROOTPATH."dal/cDatabase.php");
	if (!class_exists('cDatabase')) {
		if(!include("cDatabase.php")) {
			require_once("../cDatabase.php");
		}
	}

	if (!class_exists('ProductRepository')) {

		class ProductRepository {
			public function getContent($data) {

			}

			public function GetProductByCategoryId($category_id) {
			}

			public function GetProductListByCategoryId($category_id,$locale) {
				$obj = new cDatabase();
				$sql = 'SELECT p.product_id ,pd.productName,pd.language_id, pd.page_url
						FROM product p
						LEFT JOIN product_description pd ON pd.product_id = p.product_id
						LEFT JOIN `language` l ON l.language_id = pd.language_id ';
				$where = " WHERE p.category_id = ".$category_id." AND l.CODE = '".$locale."' order by p.sortOrder";
				$sql = $sql.$where;
				$query = $obj->db->query($sql);
				return $query->rows;
			}

			public function GetProductDetailsByProductId($product_id,$locale) {
				$obj = new cDatabase();
				$sql = 'SELECT p.product_id ,pd.productName,pd.language_id ,pd.description, f.NAME,f.path, f2.NAME as name2,f2.path as path2, pd.page_url,
						pd.metaDescription, pd.page_title, pd.metaKeywords
						FROM product p
						LEFT JOIN product_description pd ON pd.product_id = p.product_id
						LEFT JOIN `language` l ON l.language_id = pd.language_id
						LEFT JOIN files f ON f.file_id = p.image_id
						LEFT JOIN files f2 ON f2.file_id = p.image_id_2';
				$where = " WHERE p.product_id = '".$product_id."' AND l.CODE = '".$locale."'";
				$orderBy = ' ORDER BY p.sortOrder';
				$sql = $sql.$where.$orderBy;
				$query = $obj->db->query($sql);
				return $query->rows;
			}

			public function GetProductListWithDetailsByCategoryId($category_id,$locale) {
				$obj = new cDatabase();
				$sql = 'SELECT p.product_id ,pd.productName,pd.language_id ,pd.short_description, f.NAME,f.path, pd.page_url
						FROM product p
						LEFT JOIN product_description pd ON pd.product_id = p.product_id
						LEFT JOIN `language` l ON l.language_id = pd.language_id
						LEFT JOIN files f ON f.file_id = p.image_id ';
				$where = " WHERE p.category_id = ".$category_id." AND l.CODE = '".$locale."' order by p.sortOrder";
				$sql = $sql.$where;
				$query = $obj->db->query($sql);
				return $query->rows;
			}

			public function GetProductItemsDetailsByProductId($product_id,$locale) {
				$obj = new cDatabase();
				$sql = 'SELECT p.* ,pd.*
						FROM product p
						LEFT JOIN product_description pd ON pd.product_id = p.product_id
						LEFT JOIN `language` l ON l.language_id = pd.language_id
						LEFT JOIN files f ON f.file_id = p.image_id ';
				$where = " WHERE p.product_id = '".$product_id."' AND l.CODE = '".$locale."' order by p.sortOrder";
				$sql = $sql.$where;
				$query = $obj->db->query($sql);
				return $query->rows;
			}

			public function GetProductScreenshotByProductId($product_id,$locale) {
				$obj = new cDatabase();
				$sql = 'SELECT ps.description , f.NAME,f.path
						FROM product_screenshot ps
						INNER JOIN files f ON f.file_id = ps.file_id
						LEFT JOIN `language` l ON l.language_id = ps.language_id ';
				$where = " WHERE ps.product_id = '".$product_id."' AND l.CODE = '".$locale."'";
				$sql = $sql.$where;
				$query = $obj->db->query($sql);
				return $query->rows;
			}

			public function GetProductCelebritySampleByProductId($product_id,$locale) {
				$obj = new cDatabase();
				$sql = 'SELECT ps.title , f.NAME,f.path
						FROM product_sample ps
						INNER JOIN files f ON f.file_id = ps.file_id
						LEFT JOIN `language` l ON l.language_id = ps.language_id ';
				$where = " WHERE ps.product_id = '".$product_id."' AND l.CODE = '".$locale."'";
				$sql = $sql.$where;
				$query = $obj->db->query($sql);
				return $query->rows;
			}

			public function GetProductFreeDownloads($product_id) {
				$obj = new cDatabase();
				$sql = 'SELECT pd.LANGUAGE,f.NAME,f.path,f.size,pd.product_download_id
						FROM product_download pd LEFT JOIN files f ON f.file_id = pd.file_id ';
				$where = " WHERE pd.product_id = '".$product_id."' ";
				$sql = $sql.$where;
				$query = $obj->db->query($sql);
				return $query->rows;
			}

			public function GetProductFreeDownloadsById($product_download_id) {
				$obj = new cDatabase();
				$sql = 'SELECT pd.LANGUAGE,f.NAME,f.path,f.size,pd.product_download_id
						FROM product_download pd LEFT JOIN files f ON f.file_id = pd.file_id ';
				$where = " WHERE pd.product_download_id = ".$product_download_id." ";
				$sql = $sql.$where;
				$query = $obj->db->query($sql);
				return $query->rows;
			}

			public function GetProductFreeDownloadsByProductItemId($product_items_id,$language) {
				$obj = new cDatabase();
				/*$sql = 'SELECT p.product_id,pd.LANGUAGE,f.NAME,f.path,f.size,pd.product_download_id
				 FROM product_items p
				LEFT JOIN product_download pd ON pd.product_id = p.product_id
				LEFT JOIN files f ON f.file_id = pd.file_id ';
				$where = " WHERE p.product_items_id  = ".$product_items_id." ";*/

				$sql = 'SELECT p.product_id,pd.LANGUAGE,f.NAME,f.path,f.size,pd.product_download_id
						FROM product_items p
						LEFT JOIN product_download pd ON pd.product_id = p.product_id
						LEFT JOIN files f ON f.file_id = pd.file_id ';
				$where = " WHERE pd.LANGUAGE IS NOT NULL AND p.product_items_id  = '".$product_items_id."' and TRIM(lcase(pd.LANGUAGE)) = TRIM(lcase('".$language."'))";

				$sql = $sql.$where;
				$query = $obj->db->query($sql);
				return $query->rows;
			}

			public function GetProductBuyCD($product_id,$category_id,$language_code) {
				$obj = new cDatabase();
				$sql = 'SELECT p.product_items_id,p.sku, p.quantity, p.isShiping, p.isSubtractStock, p.stock_status_id,
						p.STATUS ,pid.NAME AS product_name
						FROM product_items p
						LEFT JOIN product_items_description pid ON pid.product_items_id = p.product_items_id
						LEFT JOIN `language` l ON l.language_id = pid.language_id ';
				$where = "  WHERE p.product_id = '".$product_id."' AND p.category_id = ".$category_id." AND l.CODE = '".$language_code."'";
				$sql = $sql.$where;
				$query = $obj->db->query($sql);
				$result = array();
				$result = $query->rows;
				if(count($query->rows)>0) {
					$product_items_id = $query->rows[0]['product_items_id'];

					$sql = 'SELECT pr.product_price_id, pr.price_1, pr.discounted_price_1, pr.price_2, pr.discounted_price_2, pr.packagePostalCharge,
							pr.currency_id,c.NAME AS currency_name, c.symbol, c.code
							FROM product_price pr
							LEFT JOIN currency c ON c.currency_id = pr.currency_id ';
					$where = "  WHERE pr.product_items_id ='".$product_items_id."' " ;
					$sql = $sql.$where;
					$query = $obj->db->query($sql);
					//$result[] = $query->rows;
					$result = array_merge($result,$query->rows);
				}
				return $result;

			}

			public function GetProductForRegisterSharware($product_id,$category_id,$language_code) {
				$obj = new cDatabase();
				$sql = 'SELECT p.product_items_id,p.sku, p.quantity, p.isShiping, p.isSubtractStock, p.stock_status_id,
						p.STATUS ,pid.NAME AS product_name
						FROM product_items p
						LEFT JOIN product_items_description pid ON pid.product_items_id = p.product_items_id
						LEFT JOIN `language` l ON l.language_id = pid.language_id ';
				$where = "  WHERE p.product_id = '".$product_id."' AND p.category_id = ".$category_id." AND l.CODE = '".$language_code."'";
				$sql = $sql.$where;
				$query = $obj->db->query($sql);
				$result = array();
				$result = $query->rows;
				if(count($query->rows)>0) {
					$product_items_id = $query->rows[0]['product_items_id'];

					$sql = 'SELECT pr.product_price_id, pr.price_1, pr.discounted_price_1, pr.price_2, pr.discounted_price_2, pr.packagePostalCharge,
							pr.currency_id,c.NAME AS currency_name, c.symbol, c.code
							FROM product_price pr
							LEFT JOIN currency c ON c.currency_id = pr.currency_id ';
					$where = "  WHERE pr.product_items_id =".$product_items_id ;
					$sql = $sql.$where;
					$query = $obj->db->query($sql);
					//$result[] = $query->rows;
					$result = array_merge($result,$query->rows);
				}
				return $result;

			}

			public function GetProductBundle($category_id,$language_code) {
				$obj = new cDatabase();
				$sql = 'SELECT pb.product_bundle_id,pb.NAME,pb.product_items_ids
						FROM product_bundle pb
						LEFT JOIN `language` l ON l.language_id = pb.language_id ';
				$where = "  WHERE pb.category_id = ".$category_id." AND l.CODE = '".$language_code."'";
				$sql = $sql.$where;
				$query = $obj->db->query($sql);
				
				return $query->rows;
			}

			public function GetProductBundlePrice($product_bundle_id) {
				$obj = new cDatabase();
				$sql = 'SELECT pr.product_bundle_id,pr.price,pr.currency_id, c.NAME,c.symbol, c.code, pr.package_postal_charge
						FROM product_bundle_price pr
						LEFT JOIN currency c ON c.currency_id = pr.currency_id ';
				$where = "  WHERE pr.product_bundle_id = '".$product_bundle_id."'";
				$sql = $sql.$where;
				$query = $obj->db->query($sql);

				return $query->rows;
			}

			public function GetProductBuyReport($product_id,$language_code) {
				$obj = new cDatabase();
				$sql = 'SELECT p.product_items_id,p.sku, p.quantity, p.isShiping, p.isSubtractStock, p.stock_status_id,
						p.STATUS ,pid.NAME AS product_name,pid.short_description,p.category_id
						FROM product_items p
						LEFT JOIN product_items_description pid ON pid.product_items_id = p.product_items_id
						LEFT JOIN `language` l ON l.language_id = pid.language_id ';
				$where = "  WHERE p.product_id = '".$product_id."' AND l.CODE = '".$language_code."'";
				$sql = $sql.$where;

				$query = $obj->db->query($sql);
				$result = array();
				$result = $query->rows;
				if(count($query->rows)>0) {
					$product_items_id = $query->rows[0]['product_items_id'];

					$sql = 'SELECT pr.product_price_id, pr.price_1, pr.discounted_price_1, pr.price_2, pr.discounted_price_2, pr.packagePostalCharge,
							pr.currency_id,c.NAME AS currency_name, c.symbol, c.code
							FROM product_price pr
							LEFT JOIN currency c ON c.currency_id = pr.currency_id ';
					$where = "  WHERE pr.product_items_id =".$product_items_id ;
					$sql = $sql.$where;
					$query = $obj->db->query($sql);
					//$result[] = $query->rows;
					$result = array_merge($result,$query->rows);
				}
				return $result;

			}

			public function GetProductItemsById($product_id,$language_code) {
				$obj = new cDatabase();
				$sql = 'SELECT p.product_items_id, p.category_id,p.isShiping FROM product_items p ';
				$where = "  WHERE p.product_id = '".$product_id."' order by p.category_id";
				$sql = $sql.$where;
				$query = $obj->db->query($sql);

				return $query->rows;
			}

			public function GetProductItemsPriceById($product_items_id) {
				$obj = new cDatabase();
				$sql = 'SELECT pr.product_price_id, pr.price_1, pr.price_2, pr.packagePostalCharge,
						pr.currency_id,c.NAME AS currency_name, c.symbol, c.code
						FROM product_price pr
						LEFT JOIN currency c ON c.currency_id = pr.currency_id ';
				$where = "  WHERE pr.product_items_id = '".$product_items_id ."'";
				$sql = $sql.$where;
				$query = $obj->db->query($sql);

				return $query->rows;
			}

			public function GetProductItemsByProductItemsId($product_items_id,$language_code) {
				$obj = new cDatabase();
				$sql = 'SELECT p.product_items_id, p.category_id,p.isShiping, pid.name FROM product_items p
						LEFT JOIN product_items_description pid ON pid.product_items_id = p.product_items_id
						LEFT JOIN `language` l ON l.language_id = pid.language_id';
				$where = "  WHERE p.product_items_id = '".$product_items_id."' AND l.CODE = '".$language_code."' order by p.category_id";
				$sql = $sql.$where;
				$query = $obj->db->query($sql);

				return $query->rows;
			}

			public function GetProductTestimonial($product_items_id, $language_code) {
				$obj = new cDatabase();
				$sql = 'SELECT t.testimonial_id, td.name, td.description
						FROM testimonial t
						LEFT JOIN `testimonial_description` td ON t.testimonial_id = td.testimonial_id
						left join language l on l.language_id = t.language_id';
				$where = "  WHERE t.product_id = '".$product_items_id."' AND t.status =1 AND l.CODE = '".$language_code."' ORDER BY t.sort_order";
				$sql = $sql.$where;
				$query = $obj->db->query($sql);

				return $query->rows;
			}

			public function GetProductLanguageAwailableList($product_items_id) {
				$obj = new cDatabase();
				$sql = 'SELECT pl.report_language_id,rl.name, rl.code FROM `product_language` pl
						left join report_languages rl on pl.report_language_id = rl.report_language_id';
				// $where = "  where pl.product_id = '".$product_items_id."' ";
				if(!empty($product_items_id)) {
					$where = "  where pl.product_id = '".$product_items_id."' ";
				}
				else {
					$where = " where 1=1";
				}
				$sql = $sql.$where;
				$query = $obj->db->query($sql);

				return $query->rows;
			}

			public function GetCurrencyList() {
				$obj = new cDatabase();
				$sql = 'select currency_id ,	name ,	symbol, 	code from currency';
				$where = "  ";
				$sql = $sql.$where;
				$query = $obj->db->query($sql);

				return $query->rows;
			}

			public function GetConsultationList($product_id,$language_code) {
				$obj = new cDatabase();



				$sql = 'SELECT p.product_items_id,p.sku, p.quantity, p.isShiping, p.isSubtractStock, p.stock_status_id,
						p.STATUS ,pid.NAME AS product_name,pid.short_description,p.category_id,pr.product_price_id, pr.price_1,
						pr.price_2, pr.packagePostalCharge,
						pr.currency_id,c.NAME AS currency_name, c.symbol, c.code
						FROM product_items p
						LEFT JOIN product_items_description pid ON pid.product_items_id = p.product_items_id
						LEFT JOIN `language` l ON l.language_id = pid.language_id
						left join product_price pr on pr.product_items_id = p.product_items_id
						LEFT JOIN currency c ON c.currency_id = pr.currency_id ';
				$where = "  WHERE p.product_id = '".$product_id."' AND l.CODE = '".$language_code."'";
				$sql = $sql.$where;
				$query = $obj->db->query($sql);

				$result = $query->rows;

				return $result;
			}

			public function GetConsultationPriceListById($product_items_id) {
				$obj = new cDatabase();
				//$product_items_id = $query->rows[0]['product_items_id'];

				$sql = 'SELECT pr.product_price_id, pr.price_1, pr.price_2, pr.packagePostalCharge,
						pr.currency_id,c.NAME AS currency_name, c.symbol, c.code
						FROM product_price pr
						LEFT JOIN currency c ON c.currency_id = pr.currency_id ';
				$where = "  WHERE pr.product_items_id =".$product_items_id ;
				$sql = $sql.$where;
				$query = $obj->db->query($sql);
				//$result[] = $query->rows;
				$result = array_merge($result,$query->rows);
			}

			public function GetProductIdFromProductItemId($ProductItemsID) {
				$obj = new cDatabase();
				$sql = 'SELECT product_id FROM `product_items` ';
				$where = "  WHERE product_items_id =" .$ProductItemsID. " LIMIT 1";
				$sql = $sql.$where;
				$query = $obj->db->query($sql);
				return $query->row['product_id'];
			}

			public function GetDiscountedPricesByProductAndCurrency($ProductItemId, $CurrencyId) {
				$obj = new cDatabase();

				$sql = 'SELECT price_1, discounted_price_1, price_2, discounted_price_2, packagePostalCharge FROM `product_price` ';
				$where = "  WHERE product_items_id = '".$ProductItemId."' AND currency_id = '".$CurrencyId."'";
				$sql = $sql.$where;
				$query = $obj->db->query($sql);

				$result = $query->rows;

				return $result;
			}

			public function GetProductIdByPageURL($PageURL, $LanguageCode) {
				$obj = new cDatabase();
					
				$sql = 'SELECT pd.product_id FROM `product_description` pd LEFT JOIN `language` l ON l.language_id = pd.language_id';
				$where = "  WHERE page_url = '".$PageURL."' AND l.CODE = '".$LanguageCode."'";
				$sql = $sql.$where;
				$query = $obj->db->query($sql);
				$result = isset($query->row['product_id']) ? $query->row['product_id'] : 0;
				return $result;
			}

			public function GetMetaData($ProductOrPageId, $Locale) {
				$obj = new cDatabase();
				$sql = "SELECT  TRIM(s.page_title) as page_title, TRIM(s.page_description) as metaDescription, TRIM(s.page_keyword) as metaKeywords
						FROM `seo_meta_tags` s, product p, product_description pd, language l ";
				$where = " WHERE p.product_id = pd.product_id AND pd.page_url = s.page_url AND l.language_id = s.language_id AND ";
				$where .= " pd.product_id = '$ProductOrPageId' AND  lower(l.CODE) = lower('$Locale') AND
				TRIM(s.page_title) <> '' AND  TRIM(s.page_description) <> '' AND TRIM(s.page_keyword)  <> '' ";
				$sql = $sql.$where;
				$query = $obj->db->query($sql);

				if(count($query->rows) == 0) {
					$sql = 'SELECT TRIM(pd.page_title) as page_title, TRIM(pd.metaDescription) as metaDescription, TRIM(pd.metaKeywords) as metaKeywords
							FROM product p
							LEFT JOIN product_description pd ON pd.product_id = p.product_id
							LEFT JOIN `language` l ON l.language_id = pd.language_id ';
					$where = " WHERE (p.product_id = '".$ProductOrPageId."' OR lower(pd.page_url) = lower('".$ProductOrPageId."')) AND lower(l.CODE) = lower('".$Locale."') AND
							(TRIM(pd.page_title) <> '' AND  TRIM(pd.metaDescription) <> '' AND TRIM(pd.metaKeywords)  <> '' AND
							TRIM(pd.page_title) IS NOT NULL AND  TRIM(pd.metaDescription) IS NOT NULL AND TRIM(pd.metaKeywords) IS NOT NULL ) ";
					$sql = $sql.$where;
					$query = $obj->db->query($sql);
				}
				return $query->rows;
			}

			public function GetProductWithDefaultCurrency($product_id,$category_id,$language_code) {
				$obj = new cDatabase();
				$sql = 'SELECT p.product_items_id,p.sku, p.quantity, p.isShiping, p.isSubtractStock, p.stock_status_id,
						p.STATUS ,pid.NAME AS product_name
						FROM product_items p
						LEFT JOIN product_items_description pid ON pid.product_items_id = p.product_items_id
						LEFT JOIN `language` l ON l.language_id = pid.language_id ';
				$where = "  WHERE p.product_id = '".$product_id."' AND p.category_id = ".$category_id." AND l.CODE = '".$language_code."'";
				$sql = $sql.$where;
				$query = $obj->db->query($sql);
				$result = array();
				$result = $query->rows;
				if(count($query->rows)>0) {
					$product_items_id = $query->rows[0]['product_items_id'];
					$sql = 'SELECT pr.product_price_id, pr.price_1, pr.discounted_price_1, pr.price_2, pr.discounted_price_2, pr.packagePostalCharge,
							pr.currency_id,c.NAME AS currency_name, c.symbol, c.code, l.CODE AS LCode, cl.isdefault
							FROM  currency c, `language` l, currency_to_language cl, product_price pr ';
					$where = "WHERE pr.product_items_id = '".$product_items_id."' AND l.CODE = '".$language_code."' AND pr.currency_id = cl.currency_id AND
							c.currency_id = cl.currency_id AND l.language_id = cl.language_id AND c.currency_id = pr.currency_id";
					$sql = $sql.$where;
					$query = $obj->db->query($sql);
					//echo $sql;
					//$result[] = $query->rows;
					$result = array_merge($result,$query->rows);
				}
				return $result;
					
			}

			public function GetProductForRegisterSharwareWithDefaultCurrency($product_id,$category_id,$language_code) {
				$obj = new cDatabase();
				$sql = 'SELECT p.product_items_id,p.sku, p.quantity, p.isShiping, p.isSubtractStock, p.stock_status_id,
						p.STATUS ,pid.NAME AS product_name
						FROM product_items p
						LEFT JOIN product_items_description pid ON pid.product_items_id = p.product_items_id
						LEFT JOIN `language` l ON l.language_id = pid.language_id ';
				$where = "  WHERE p.product_id = '".$product_id."' AND p.category_id = ".$category_id." AND l.CODE = '".$language_code."'";
				$sql = $sql.$where;
				$query = $obj->db->query($sql);
				$result = array();
				$result = $query->rows;
				if(count($query->rows)>0) {
					$product_items_id = $query->rows[0]['product_items_id'];

					$sql = 'SELECT pr.product_price_id, pr.price_1, pr.discounted_price_1, pr.price_2, pr.discounted_price_2, pr.packagePostalCharge,
							pr.currency_id,c.NAME AS currency_name, c.symbol, c.code, l.CODE AS LCode, cl.isdefault
							FROM  currency c, `language` l, currency_to_language cl, product_price pr ';
					$where = "WHERE pr.product_items_id = '".$product_items_id."' AND l.CODE = '".$language_code."' AND pr.currency_id = cl.currency_id AND
							c.currency_id = cl.currency_id AND l.language_id = cl.language_id AND c.currency_id = pr.currency_id";
					$sql = $sql.$where;
					$query = $obj->db->query($sql);
					//$result[] = $query->rows;
					$result = array_merge($result,$query->rows);
				}
				return $result;
					
			}

			public function GetProductBuyReportWithDefaultCurrency($product_id,$language_code) {
				$obj = new cDatabase();
				$sql = 'SELECT p.product_items_id,p.sku, p.quantity, p.isShiping, p.isSubtractStock, p.stock_status_id,
						p.STATUS ,pid.NAME AS product_name,pid.short_description,p.category_id
						FROM product_items p
						LEFT JOIN product_items_description pid ON pid.product_items_id = p.product_items_id
						LEFT JOIN `language` l ON l.language_id = pid.language_id ';
				$where = "  WHERE p.product_id = '".$product_id."' AND l.CODE = '".$language_code."'";
				$sql = $sql.$where;
					
				$query = $obj->db->query($sql);
				$result = array();
				$result = $query->rows;
				if(count($query->rows)>0) {
					$product_items_id = $query->rows[0]['product_items_id'];

					$sql = 'SELECT pr.product_price_id, pr.price_1, pr.discounted_price_1, pr.price_2, pr.discounted_price_2, pr.packagePostalCharge,
							pr.currency_id,c.NAME AS currency_name, c.symbol, c.code, l.CODE AS LCode, cl.isdefault
							FROM  currency c, `language` l, currency_to_language cl, product_price pr ';
					$where = "WHERE pr.product_items_id = '".$product_items_id."' AND l.CODE = '".$language_code."' AND pr.currency_id = cl.currency_id AND
							c.currency_id = cl.currency_id AND l.language_id = cl.language_id AND c.currency_id = pr.currency_id";
					$sql = $sql.$where;
					$query = $obj->db->query($sql);
					$result = array_merge($result,$query->rows);
				}
				return $result;
					
			}

			public function GetProductBundlePriceWithDefaultCurrency($ProductBundleId, $LanguageCode) {
				$obj = new cDatabase();
				$sql = 'SELECT pr.product_bundle_id,pr.price,pr.currency_id, c.NAME,c.symbol, c.code, pr.package_postal_charge, isdefault,
							   pr.discounted_price
						FROM currency_to_language cl, product_bundle_price pr, currency c, language l ';
				$where = "WHERE cl.currency_id = pr.currency_id AND cl.currency_id = c.currency_id AND pr.currency_id = c.currency_id
				AND cl.currency_id = c.currency_id AND cl.language_id = l.language_id AND l.code = '$LanguageCode' AND
				pr.product_bundle_id = '$ProductBundleId'";

				$sql = $sql.$where;
				//echo $sql ."<br/>";
				$query = $obj->db->query($sql);
					
				return $query->rows;
			}

		}
	}
}
catch(Exception $ex) {
	print_r($ex);
}
?>