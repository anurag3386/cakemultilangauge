<?php
error_reporting ( E_ALL );
require_once("include.php");
if(!@include("dto/acsDTO.php")) 
{	
	require_once(DTOPATH."/acsDTO.php");
}
class acsAtlas
{
	public function GetACSDetails($q,$s,$limit)
	{
		$arrayValue = array();
		$acsRepository = new ACSRepository();
		$result = $acsRepository->GetACSDetails($q,$s,$limit);
		
		if(count($result)>0)
		{						
			for($i=0;$i<count($result);$i++)
			{
				$objAcsDTO = new AcsDTO();
				$objAcsDTO->placename = $result[$i]['placename'];
				$objAcsDTO->region = $result[$i]['region'];
				//$objAcsDTO->latitude = $result[$i]['latitude'];
				//$objAcsDTO->longitude = $result[$i]['longitude'];
				$objAcsDTO->zone = $result[$i]['zone'];
				$objAcsDTO->type = $result[$i]['type'];
				
				$latitude = floatval($result[$i]['latitude'] / 3600.0);	
				$objAcsDTO->latitude = 	$latitude;		
				$latitude = trim( sprintf ( "%2d%s%02d", abs ( intval ($latitude) ), ( ( $latitude >= 0 ) ? 'N' : 'S' ), abs ( intval ( ( ( $latitude - intval ( $latitude ) ) * 60) ) ) ) );
				$objAcsDTO->calLatitude = $latitude;
				
				$longitude = floatval($result[$i]['longitude'] / 3600.0);
				$objAcsDTO->longitude = $longitude;
				$longitude = trim( sprintf ( "%3d%s%02d", abs ( intval ( $longitude ) ), (($longitude >= 0) ? 'W' : 'E' ), abs ( intval ( ( ( $longitude - intval ( $longitude ) ) * 60) ) ) ) );				
				$objAcsDTO->calLongitude = $longitude;
				
				$arrayValue[] = $objAcsDTO;	
					
			}
		}
				
		return $arrayValue;
	}
}
?>
