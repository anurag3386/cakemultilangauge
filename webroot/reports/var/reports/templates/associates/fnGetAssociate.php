<?php
  /**
   * Script: fnGetAssociate.php
   * Author: Andy Gray
   *
   * Description
   * Return associate context
   */

function fnGetAssociate( $affiliate ) {

  switch( $affiliate ) {

  case 'datastar':
    $affiliate_name = 'Datastar';
    $affiliate_language = 'en';
    $affiliate_url = 'http://www.example.com';
    break;

  case 'micheleknight':
    $affiliate_name = 'Michele Knight';
    $affiliate_language = 'en';
    $affiliate_url = 'http://www.micheleknight.co.uk';
    break;

  case 'pegasus':
    $affiliate_name = 'Pegasus Associates';
    $affiliate_language = 'en';
    $affiliate_url = 'http://www.pegasus.com.au';
    break;

  case 'zodia':
    $affiliate_name = 'Zodia.tv';
    $affiliate_language = 'en';
    $affiliate_url = 'http://www.zodia.tv';
    $affiliate_post = '/06_affiliates/zodia/post.php';
    break;

    /* UNIT TESTING PSEUDO ASSOCIATES */

  case 'unittest_en': 
    $affiliate_name = 'Unit Testing (Lang/EN)';
    $affiliate_language = 'en';
    $affiliate_post = '/06_affiliates/sme/dump.php';
    break;
   
  case 'unittest_dk':
    $affiliate_name = 'Unit Testing (Lang/DK)';
    $affiliate_language = 'dk';
    break;
   
  case 'unittest_se':
    $affiliate_name = 'Unit Testing (Lang/SE)';
    $affiliate_language = 'se';
    break;
   
    /* Default if none of the above */

  default:
    $affiliate_name = 'World of Wisdom Associates'; 
    $affiliate_language = 'en';
    break;
  }

  return $context;
  }

?>