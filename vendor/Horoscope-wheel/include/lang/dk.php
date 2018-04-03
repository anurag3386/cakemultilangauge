<?php
/*
 * Script: include/lang/dk.php
 * Author: Andy Gray <andy.gray@astro-consulting.co.uk>
 *
 * Description
 * Danish strings
 *
 * Modification History
 * - initial spike
 */

$chapterHeadings['dk'] = array(
	"10001"	=>	"Livsbane",
	"10002"	=>	"Identitet",
	"10003"	=>	utf8_decode("Følelser"),
	"10004"	=>	"Mentalitet",
	"10005"	=>	utf8_decode("Værdier"),
	"10006"	=>	"Fremdrift",
	"10007"	=>	"Visdom",
	"10008"	=>	"Udfordringer",
	"10009"	=>	"Originalitet",
	"10010"	=>	"Transcendens",
	"10011"	=>	"Forvandling",
	"10012"	=>	"Destiny"
	);
	
$top_object1['dk'] = array(
	"1000"	=>	"Sol",
	"1001"	=>	utf8_decode("Måne"),
	"1002"	=>	"Merkur",
	"1003"	=>	"Venus",
	"1004"	=>	"Mars",
	"1005"	=>	"Jupiter",
	"1006"	=>	"Saturn",
	"1007"	=>	"Uranus",
	"1008"	=>	"Neptun",
	"1009"	=>	"Pluto",
	"1010"	=>	utf8_decode("N. Måneknude"),
	"1011"	=>	utf8_decode("S. Måneknude"),
	"1012"	=>	"Ascendant",
	"1013"	=>	"MC",
	"1014"	=>	"IC",
	"1015"	=>	"Descendant"
	);
	
$top_connector['dk'] = array(
	"000"=>"konjunktion",
	"030"=>"halvsekstil",
	"060"=>"sekstil",
	"120"=>"trigon",
	"045"=>"halvkvadrat",
	"090"=>"kvadrat",
	"135"=>"seskvikvadrat",
	"150"=>"kvinkunx",
	"180"=>"opposition",
	"200"=>"i"
	);
	
$top_sign['dk'] = array(
	"1000"=>"planet",
	"1001"=>"planet",
	"1002"=>"planet",
	"1003"=>"planet",
	"1004"=>"planet",
	"1005"=>"planet",
	"1006"=>"planet",
	"1007"=>"planet",
	"1008"=>"planet",
	"1009"=>"planet",
	"1010"=>"planet",
	"1011"=>"planet",
	"1012"=>"planet",
	"1013"=>"planet",
	"1014"=>"planet",
	"1015"=>"planet",
	"0100"=>"sign",
	"0101"=>"sign",
	"0102"=>"sign",
	"0103"=>"sign",
	"0104"=>"sign",
	"0105"=>"sign",
	"0106"=>"sign",
	"0107"=>"sign",
	"0108"=>"sign",
	"0109"=>"sign",
	"0110"=>"sign",
	"0111"=>"sign",
	"0001"=>"house",
	"0002"=>"house",
	"0003"=>"house",
	"0004"=>"house",
	"0005"=>"house",
	"0006"=>"house",
	"0007"=>"house",
	"0008"=>"house",
	"0009"=>"house",
	"0010"=>"house",
	"0011"=>"house",
	"0012"=>"house"
	);
	
$top_object2['dk'] = array(
	"1000"=>"Sol",
	"1001"=>utf8_decode("Måne"),
	"1002"=>"Merkur",
	"1003"=>"Venus",
	"1004"=>"Mars",
	"1005"=>"Jupiter",
	"1006"=>"Saturn",
	"1007"=>"Uranus",
	"1008"=>"Neptun",
	"1009"=>"Pluto",
	"1010"=>utf8_decode("N. Måneknude"),
	"1011"=>utf8_decode("S. Måneknude"),
	"1012"=>"Ascendant",
	"1013"=>"Midheaven",
	"1014"=>"IC",
	"1015"=>"Descendant",
	"0100"=>utf8_decode("Vædder"),
	"0101"=>"Tyr",
	"0102"=>"Tvilling",
	"0103"=>"Krebs",
	"0104"=>utf8_decode("Løve"),
	"0105"=>"Jomfru",
	"0106"=>utf8_decode("Vægt"),
	"0107"=>"Skorpion",
	"0108"=>"Skytte",
	"0109"=>"Stenbuk",
	"0110"=>utf8_decode("Vandbærer"),
	"0111"=>"Fiskene",
	"0001"=>"1. Hus",
	"0002"=>"2. Hus",
	"0003"=>"3. Hus",
	"0004"=>"4. Hus",
	"0005"=>"5. Hus",
	"0006"=>"6. Hus",
	"0007"=>"7. Hus",
	"0008"=>"8. Hus",
	"0009"=>"9. Hus",
	"0010"=>"10. Hus",
	"0011"=>"11. Hus",
	"0012"=>"12. Hus"
	);
	
$top_pno['dk'] = array(
	/* planets */
	"1000"=>"01",
	"1001"=>"02",
	"1002"=>"03",	
	"1003"=>"04",
	"1004"=>"05",
	"1005"=>"06",
	"1006"=>"07",
	"1007"=>"08",
	"1008"=>"09",
	"1009"=>"10",
	"1010"=>"10",	/* unexpected */
	"1011"=>"11",
	"1012"=>"00",	/* unexpected */
	"1013"=>"13",
	"1014"=>"14",
	"1015"=>"15",
	/* signs */
	"0100"=>"01",
	"0101"=>"02",
	"0102"=>"03",
	"0103"=>"04",
	"0104"=>"05",
	"0105"=>"06",
	"0106"=>"07",
	"0107"=>"08",
	"0108"=>"09",
	"0109"=>"10",
	"0110"=>"11",
	"0111"=>"12",
	/* houses */
	"0001"=>"01",
	"0002"=>"02",
	"0003"=>"03",
	"0004"=>"04",
	"0005"=>"05",
	"0006"=>"06",
	"0007"=>"07",
	"0008"=>"08",
	"0009"=>"09",
	"0010"=>"10",
	"0011"=>"11",
	"0012"=>"12"
	);

$top_p3['dk'] = array(
	"10001"=>"01",
	"10002"=>"01",
	"10003"=>"02",
	"10004"=>"03",
	"10005"=>"04",
	"10006"=>"05",
	"10007"=>"06",
	"10008"=>"07",
	"10009"=>"08",
	"10010"=>"09",
	"10011"=>"10",
	"10012"=>"11"
	);

$top_p1['dk'] = array(
	"1000"=>"02",
	"1001"=>"02",
	"1002"=>"02",
	"1003"=>"02",
	"1004"=>"02",
	"1005"=>"02",
	"1006"=>"02",
	"1007"=>"02",
	"1008"=>"01",
	"1009"=>"01",
	"1010"=>"01",
	"1011"=>"02",
	"1012"=>"02",
	"1013"=>"13",
	"1014"=>"14",
	"1015"=>"15",
	
	"0100"=>"01",
	"0101"=>"02",
	"0102"=>"03",
	"0103"=>"04",
	"0104"=>"05",
	"0105"=>"06",
	"0106"=>"07",
	"0107"=>"08",
	"0108"=>"09",
	"0109"=>"10",
	"0110"=>"11",
	"0111"=>"12",
	"0001"=>"01",
	"0002"=>"02",
	"0003"=>"03",
	"0004"=>"04",
	"0005"=>"05",
	"0006"=>"06",
	"0007"=>"07",
	"0008"=>"08",
	"0009"=>"09",
	"0010"=>"10",
	"0011"=>"11",
	"0012"=>"12"
	);

$top_p2['dk'] = array(
	"10001"=>"02",
	"10002"=>"03",
	"10003"=>"03",
	"10004"=>"03",
	"10005"=>"03",
	"10006"=>"03",
	"10007"=>"03",
	"10008"=>"03",
	"10009"=>"03",
	"10010"=>"03",
	"10011"=>"03",
	"10012"=>"03"
	);

$top_retrograde['dk'] = array(
	"0"=>'',
	"1"=>"Retrograd"
	);

$setreport['dk'] = array("Behage laese side");

$bSigns['dk'] = array(
	utf8_decode("Vædder"),"Tyr","Tvilling","Krebs",
	utf8_decode("Løve"),"Jomfru",utf8_decode("Vægt"),"Skorpion",
	"Skytte","Stenbuk",utf8_decode("Vandbærer"),"Fiskene"
	);

$transiting_aspects['dk'] = array(
	"000"	=>	"konjunktion",
	"060"	=>	"positiv",
	"090"	=>	"udfordrer",
	"120"	=>	"positiv",
	"180"	=>	"udfordrer"
	);
	
/* House system is Placidus unless untimed in which case it is Equal */
$wheel_top_housesystem['dk']	= "Placidus";
$wheel_top_orbsensitivity['dk']	= "Svag Orbis";
$wheel_top_timezone['dk']	= "Tidszone";
$wheel_top_summertime['dk']	= "Sommertid";

$wheel_top_weekdays['dk'] = array(
	'Sunday' => utf8_decode('Søndag'), 
	'Monday' => 'Mandag',
	'Tuesday' => 'Tirsdag',
	'Wednesday' => 'Onsdag',
	'Thursday' => 'Torsdag', 
	'Friday' => 'Fredag',
	'Saturday' => utf8_decode('Lørdag')
	);

$wheel_info_symbols['dk'] = array(
	/* planets */
	"Sol",		utf8_decode("Måne"),
	"Merkur",	"Venus",	"Mars",
	"Jupiter",	"Saturn",
	"Uranus",	"Neptun",	"Pluto",
	utf8_decode("N. Måneknude"),
	utf8_decode("S. Måneknude"),
	/* signs */
	utf8_decode("Vædder"),	"Tyr",		"Tvilling",		"Krebs",
	utf8_decode("Løve"),		"Jomfru",	utf8_decode("Vægt"),			"Skorpion",
	"Skytte",	"Stenbuk",	utf8_decode("Vandbærer"),	"Fiskene",
	/* aspects */
	"konjunktion",	"halvsekstil",		"halvkvadrat",	"sekstil",	"kvadrat",
	"trigon",		"seskvikvadrat",	"kvinkunx",		"opposition",
	/* angles */
	'Ascendant','Descendant','Medium Coeli','Immum Coeli',
	'Retrograd'
	);
	
/*
 * The following are order form labels
 */	
 
$order_form_labels['dk']['name'] = "Navn";
$order_form_labels['dk']['email']		= "Email";
$order_form_labels['dk']['emailconfirm']	= "Confirm";

$orderform_labels['dk']['name']			= "Name";
$orderform_labels['dk']['email']		= "Email";
$orderform_labels['dk']['emailconfirm']	= "Confirm";

// Added By Amit Parmar (On 08-Feb-2012)

/*
 * Report Header title
*/
$report_header['dk'] = array(
		"Birth-Analysis" 				=> 	"Birth Analysis",
		"Dynamic-Analysis" 				=> 	"Dynamic Analysis ",
		"Birth-and-Dynamic-Analysis" 	=> 	"Birth Analysis + Dynamic Analysis ",
		"Seasonal-Report" 				=> 	"Seasonal Report",
		"Calendar-Report" 				=> 	"Calendar Report");

/*
 * Attitude Title Informations
*/
$attitude_title['dk'] = array(
		"PERSONAL"		=>		"PERSONAL",
		"PROFESSIONAL"	=>		"PROFESSIONAL",
		"COLLECTIVE"	=>		"COLLECTIVE",
		"EMOTIONS"		=>		"EMOTIONS",
		"THE-MIND"  	=>  	"THE MIND",
		"LOVE"			=>		"LOVE",
		"SEX-AND-POWER" => 		"SEX & POWER");

/*
 * Trasit connector (Sun transiting trine Pluto)
*/
$trasit_connector['dk'] = array(
		"transiting" 	=>		"transiting");

/*
 * Retrograde connector
*/
$retrograde_connector['dk'] = array(
		"Retrograde" => " Retrograde");

/*
 * Registered and Unregistered franchise
*/
$isRegistered['dk'] = array(
		"Registered"	=>		"Registered:",
		"Unregistered" 	=> 		"Unregistered");

/*
 * Page number
*/
$page_number_name['dk']= array(
		"Page"	=>		"Page ");
?>