<?php
/* - MAIN - */
/*
 * Parameters
 * - q - partial match for placename
 * - s - fully qualified statename
 * - callback - required for JSONP to work cross domain
*/


class Suggest {

    public function GetSugestedPlaceName($q,$s,$callback = '',$limit) {
        //echo 'call';
        $arrayValue = array();
        try {
            header("Content-Type: text/javascript; charset=UTF-8");

            $acsRepository = new ACSRepository();
            $result = $acsRepository->GetACSDetails($q,$s,$limit);

            if(count($result)>0) {
                for($i=0;$i<count($result);$i++) {
                    $objAcsDTO = new AcsDTO();
                    
                    $objAcsDTO->placename = utf8_encode($result[$i]['placename']);
                    $objAcsDTO->region = utf8_encode($result[$i]['region']);
                    $objAcsDTO->latitude = $result[$i]['latitude'];
                    $objAcsDTO->longitude = $result[$i]['longitude'];

                    $latitude = floatval($result[$i]['latitude'] / 3600.0);
                    $latitude = trim( sprintf ( "%2d%s%02d", abs ( intval ($latitude) ), ( ( $latitude >= 0 ) ? 'N' : 'S' ), abs ( intval ( ( ( $latitude - intval ( $latitude ) ) * 60) ) ) ) );
                    $objAcsDTO->calLatitude = $latitude;

                    $longitude = floatval($result[$i]['longitude'] / 3600.0);
                    $longitude = trim( sprintf ( "%3d%s%02d", abs ( intval ( $longitude ) ), (($longitude >= 0) ? 'W' : 'E' ), abs ( intval ( ( ( $longitude - intval ( $longitude ) ) * 60) ) ) ) );
                    $objAcsDTO->calLongitude = $longitude;

                    $objAcsDTO->zone = $result[$i]['zone'];
                    $objAcsDTO->type = $result[$i]['type'];
                    $arrayValue[] = $objAcsDTO;
                    //echo json_encode(utf8_encode( $result[$i]['placename'] ) . "\n");
                    //echo $result[$i]['placename']."\n";
                }
            }
            //print_r($arrayValue);
            //$returnValue = array("result"=>$arrayValue);
            //return $returnValue;
            echo json_encode($arrayValue);
        }
        catch(Exception $ex) {
            //$arrayValue[] = $ex;
            return $arrayValue;
        }
    }
}
?>