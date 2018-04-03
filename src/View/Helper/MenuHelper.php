<?php
namespace App\View\Helper;
use Cake\View\Helper;
use Cake\ORM\TableRegistry;
use Cake\I18n\I18n;

class MenuHelper extends Helper
{

 public function findChild($id=null,$type)
 {
    $Menus = TableRegistry::get('Menus');
   	$childMenus = $Menus->find('translations', ['conditions' => ['menu_type' => $type, 'status' => 1, 'menu_id '=> $id], 'order' => ['sort_order' => 'asc']])
   	                    ->toArray();
    return $childMenus;
 }

 public function getProducts($url = null)
 {
      $products = TableRegistry::get('Products');
      $productDetails = $products->find()
                				 ->where([ 'seo_url' => $url ])
                				 ->first();
      return $productDetails;

 }

}