<?php
  /**
   * Script: gr.iso8859_7.php
   * Author: Andy Gray
   */

function code2utf($num){
  if($num<128) {
    //    error_log( sprintf("%02x", $num), 3, '/home/29078/data/log/greek.log');
    return chr($num);
  }
  if($num<1024) {
    //    error_log( sprintf("%02x", (($num>>6)+192)), 3, '/home/29078/data/log/greek.log');
    //    error_log( sprintf("%02x", (($num&63)+128)), 3, '/home/29078/data/log/greek.log');
    return chr(($num>>6)+192).chr(($num&63)+128);
  }
  if($num<32768) {
    return chr(($num>>12)+224).chr((($num>>6)&63)+128).chr(($num&63)+128);
  }
  if($num<2097152) {
    return chr($num>>18+240).chr((($num>>12)&63)+128).chr(($num>>6)&63+128). chr($num&63+128);
  }
  return '';
  }

function iso_8859_7_to_unicode( $str ) {
  global $logger;
  $logger->debug("iso_8859_7_to_unicode - entering as ISO-8859-7");
  $content = '';
  for( $i = 0; $i < strlen($str); $i++ ) {
    $char = substr($str,$i,1);
    $ord = ord($char);
    if( $ord > 127 ) {
      $ord += 720; // map to UCS greek range (U+0310..03FF) - AG - correct code appears here
    }
    $content .= code2utf($ord);
  }
  $logger->debug("iso_8859_7_to_unicode - leaving as UTF8");
  return $content;
}

/*
 * dump content to a file which can be imported into tcpdf/examples/example_008.php
 * this works so why doesn't the rest of it do the same!!!
 */
function dump_iso( $content ) {
  $f = "/home/29078/data/log/greek.sample.txt";
  error_log($content."\n",3,$f);
}

/*
 * dump content to a file which can be imported into tcpdf/examples/example_008.php
 * this works so why doesn't the rest of it do the same!!!
 */
function dump_xml( $tag, $content ) {
  $f = "/home/29078/data/log/greek.sample.xml";
  error_log("<".$tag.">".'content omitted'."</".$tag.">\n",3,$f);
}
