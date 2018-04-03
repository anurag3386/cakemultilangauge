<?php
/**
 * Holds the Language Specific Data
 */
$_['UNAUTHORIEDACCESS'] = "Invalid API Key. Authentication failed";
$_['INVALIDPARAM'] = "Invalid parameters";
$_['NOTRANSITFOUND'] = "Der er ingen vigtige transitter i dag";
$_['VALIDPARAM'] = "Ok";
$_['TRANSITING'] = "transit";
$_['NATALPLANET'] = html_entity_decode(utf8_decode(utf8_encode( "i fødselshoroskopet" ))); //"in birth horoscope";
$_['USERNOTFOUND'] = "User not found";
//Jupiter transit in harmony with Mercury in birth horoscope

$PlanetNames =array (
        /*Planets*/
//         "1000" => "Sun",
//         "1001" => "Moon",
//         "1002" => "Mercury",
//         "1003" => "Venus",
//         "1004" => "Mars",
//         "1005" => "Jupiter",
//         "1006" => "Saturn",
//         "1007" => "Uranus",
//         "1008" => "Neptune",
//         "1009" => "Pluto",
//         "1010" => "Node",
//         "1011" => "SNode",		/* put here to stop the nag messages, not used */
//         "1012" => "Ascendant",
//         "1013" => "Midheaven",	/* "MC", */
//         "1014" => "IC",
//         "1015" => "Descendant",		
		"1000"	=>	"Sol",
		"1001"	=>	html_entity_decode(utf8_decode(utf8_encode( "Måne" ))),
		"1002"	=>	"Merkur",
		"1003"	=>	"Venus",
		"1004"	=>	"Mars",
		"1005"	=>	"Jupiter",
		"1006"	=>	"Saturn",
		"1007"	=>	"Uranus",
		"1008"	=>	"Neptun",
		"1009"	=>	"Pluto",
		"1010"	=>	html_entity_decode(utf8_decode(utf8_encode( "Nordlige Måneknude" ))),
		"1011"	=>	html_entity_decode(utf8_decode(utf8_encode( "Sydlige Måneknude" ))),
		"1012"	=>	"Ascendanten",
		"1013"	=>	"MC",
		"1014"	=>	"IC",
		"1015"	=>	"Descendanten",
        /*Signs*/
// 	"0100"	=>	"Aries",
// 	"0101"	=>	"Taurus",
// 	"0102"	=>	"Gemini",
// 	"0103"	=>	"Cancer",
// 	"0104"	=>	"Leo",
// 	"0105"	=>	"Virgo",
// 	"0106"	=>	"Libra",
// 	"0107"	=>	"Scorpio",
// 	"0108"	=>	"Sagittarius",
// 	"0109"	=>	"Capricorn",
// 	"0110"	=>	"Aquarius",
// 	"0111"	=>	"Pisces",
	"0100"	=>	html_entity_decode(utf8_decode(utf8_encode( "Vædder" ))),
	"0101"	=>	"Tyr",
	"0102"	=>	"Tvilling",
	"0103"	=>	"Krebs",
	"0104"	=>	html_entity_decode(utf8_decode(utf8_encode( "Løve" ))),
	"0105"	=>	"Jomfru",
	"0106"	=>	html_entity_decode(utf8_decode(utf8_encode( "Vægt" ))),
	"0107"	=>	"Skorpion",
	"0108"	=>	"Skytte",
	"0109"	=>	"Stenbuk",
	"0110"	=>	html_entity_decode(utf8_decode(utf8_encode( "Vandbærer" ))),
	"0111"	=>	"Fiskene",

	/*Houses*/
	"0001"	=>	"i 1. hus", 	//"1st House",
	"0002"	=>	"i 2. hus", 	//"2nd House",
	"0003"	=>	"i 3. hus", 	//"3rd House",
	"0004"	=>	"i 4. hus", 	//"4th House",
	"0005"	=>	"i 5. hus", 	//"5th House",
	"0006"	=>	"i 6. hus", 	//"6th House",
	"0007"	=>	"i 7. hus", 	//"7th House",
	"0008"	=>	"i 8. hus", 	//"8th House",
	"0009"	=>	"i 9. hus", 	//"9th House",
	"0010"	=>	"i 10. hus", 	//"10th House",
	"0011"	=>	"i 11. hus", 	//"11th House",
	"0012"	=>	"i 12. hus", 	//"12th House",
        /*Aspects*/
    "000"	=>	"i konjunktion med",
	"060"	=>	"i harmoni med",	//"in harmony with",
	"090"	=>	html_entity_decode(utf8_decode(utf8_encode( "i spænding til" ))), 	//"in tension with",
	"120"	=>	html_entity_decode(utf8_decode(utf8_encode( "i harmoni med" ))),	//"in harmony with",
	"180"	=>	html_entity_decode(utf8_decode(utf8_encode( "i spænding til" ))),  	//"in tension with",
//  "000"	=>	"conjoins",
//	"060"	=>	"positive",
//	"090"	=>	"challenges",
//	"120"	=>	"positive",
//	"180"	=>	"challenges",
    "CON"	=>	" ",
    "POS"	=>	"i harmoni med",	//"in harmony with",
	"NEG"	=>	html_entity_decode(utf8_decode(utf8_encode( "i spænding til" ))),  	//"in tension with",
    );


$IconTitle =  array(
		"AS_POS" => "Positive outlook",								//  1
		"AS_NEG" => "Some glitches on the path",					//	2
		"MC_POS" => "Achievement of goals",							//	3
		"MC_NEG" => "Some domestic or career glitches",				//	4
		"SU_POS" => "Strong self-affirmation",						//	5
		"SU_NEG" => "Some uncertainty about self",					//	6
		"MO_POS" =>	"Cozy and comfortable",							//	7
		"MO_NEG" => "Some emotional discomfort",					//	8
		"ME_POS" => "Good communication",							//	9
		"ME_NEG" => "Some mental distraction",						//	10
		"VE_POS" => "Love and pleasure",							//	11
		"VE_NEG" => "Sensory or material indulgence",				//	12
		"MA_POS" => "Decisiveness and mastery",						//	13
		"MA_NEG" => "Some tension",									//	14
		"JU_POS" => "Insight, optimism and fortune",				//	15
		"JU_NEG" => "Overestimation, bad judgment",					//	16
		"SA_POS" => "Results through discipline and planning",		//	17
		"SA_NEG" => "Setbacks requiring hard work",					//	18
		"UR_POS" => "Exciting insights, social and travel activity",		//	19
		"UR_NEG" => "Unwelcome surprise, disassociation",					//	20
		"NE_POS" => "Openness to the magic of the universe, compassion",	//	21
		"NE_NEG" => "Awareness of sadness and suffering",			//	22
		"PL_POS" => "Transformation and empowerment",				//	23
		"PL_NEG" => "Elimination of what is useless",				//	24
		"NN_POS" => "Positive environmental influences",			//	25
		"NN_NEG" => "Negative environmental influences",			//	26
);    
?>