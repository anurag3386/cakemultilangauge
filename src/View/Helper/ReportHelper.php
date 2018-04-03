<?php
namespace App\View\Helper;
use Cake\View\Helper;
use Cake\ORM\TableRegistry;

class ReportHelper extends Helper
{
 

 public function getReports()
 {
    $product_type_id = $this->getProductTypeIdBasedOnUserType ();
    $products = TableRegistry::get('Products');
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
                                            ],

                                            'preview_reports' => [
                                                'table' => 'preview_reports',
                                                 'type' => 'INNER',
                                                 'conditions' => [
                                                                    'preview_reports.product_id = Products.id'
                                                                ]        
                                                           ]

                                   ])
                                ->select([ 'Products.id', 'Products.short_description', 'Products.name', 'Products.image', 'Products.pages', 'Products.seo_url', 'product_prices.total_price', 'product_prices.product_type_id', 'currency.symbol', 'preview_reports.pdf'])
                                ->where(['Categories.slug' => 'reports', 'currency.symbol' => '$', 'Products.status' => '1', 'product_prices.product_type_id' => $product_type_id] )
                                ->limit (4)
                                ->order(['Products.id' => 'DESC'])
                                ->toArray();
   
                          return $products_detail;
 }

public function getOtherReports($id)
{
      $product_type_id = $this->getProductTypeIdBasedOnUserType ();
      $products = TableRegistry::get('Products');
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
                                                ],

                                       ])
                                   ->select([ 'Products.id',  'Products.name', 'Products.image', 'Products.pages', 'Products.seo_url', 'product_prices.total_price', 'currency.symbol'])
                                   ->where(['Categories.slug' => 'reports', 'currency.symbol' => '$', 'Products.status' => '1' , 'Products.id NOT IN' => $id, 'product_type_id' => $product_type_id] )
                                   ->order(['Products.id' => 'DESC'])
                                   ->toArray();
    
   
      return $products_detail;
}
 
function getProductTypeIdBasedOnUserType () {
  $usertype = !empty ($this->request->session()->read('Auth.User.role')) ? $this->request->session()->read('Auth.User.role') : 'user';
  if ($usertype == 'elite') {
    $products_type_id = 8;
  } else {
    $products_type_id = 5;
  }
  return $products_type_id;
}



}