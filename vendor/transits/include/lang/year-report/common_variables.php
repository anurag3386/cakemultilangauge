<?php
/*
 * Script: include/lang/year-report/common_variables.php
 * Author: Amit Parmar <parmaramit1111@gmail.com>
 *
 * Description
 * 	Holds the all common variable declarations
 */

$UserAge;
$Gender = 'M';
$IsLocal =  "Yes";
$Global_Top_Theme = array();					//Holds the Top 3 major influance
$IsPlutoSquare = false;							//IF Pluto square Pluto then its True else false

//NATAL CHART Object
$Global_Natal_MObject = array();				//Natal Wheel details
$Global_Natal_TransitSortedList = array();		//Sorted Transit list with point
$Global_Natal_Transit_Window = array();			//Transit list of 2 year
$Global_Natal_m_transit = array();				//Transit list of 2 year
$Global_Natal_m_crossing  = array();			//House crossing list of 2 year
$Global_SaturnJupiterCrossing = array();		//House Crossing list for Saturn and Jupitar
$Global_N_TSLNextYear = array();				//Sorted Transit list For Next Year
$Global_Node_m_transit = array();				//House crossing for North Node

//Progression Chart Object
$Global_Progression_MObject = array();					//Natal Wheel details
$Global_Progression_TransitSortedList = array();		//Sorted Transit list with point
$Global_Progression_Transit_Window = array();			//Transit list of 2 year
$Global_Progression_m_transit = array();				//Transit list of 2 year
$Global_Progression_m_crossing  = array();				//Sign crossing list of 2 year
$Global_Prog_Direct_Retrograde_List = array();			//Holds the List of the Direct and Retrograde Planet list
$Global_Progression_CurrentYear = array();				//Sorted Transit list with point
$Global_ProgressedToProgressed = array();				//Its holds the date for Progressed to Progressed Planets

//Solar Chart Object
$Global_Solar_MObject = array();					//Solar return Wheel details
$Global_Solar_TransitSortedList = array();			//Sorted Transit list with point solar return to  Natal or Radix aspect
$Global_Solar_m_transit = array();					//Transit list
$Global_Solar_m_crossing  = array();				//Sign crossing list
$global_Solar_HouseCups = array();					//Checking for Solar Return Rulers
$global_Solar_PlanetLongitude = array();			//Checking for Solar Return Rulers
$Global_Solar_InternalAspect = array();				//Holds the Solar Return Internal Aspects

//Solar Horory Object
$Global_Horory_MObject = array();					//Horory return Wheel details
$Global_Horory_TransitSortedList = array();			//Sorted Transit list with point
$Global_Horory_m_transit = array();					//Transit list
$Global_Horory_m_crossing  = array();				//Sign crossing list

$Global_PreviousYear;				//Trancking last year data  (like 16-July-2011)
$Global_NextYear;					//Trancking next year data  (like 16-July-2013)
$Global_CurrntYear;					//Trancking current year data  -Date of birth  (like 16-July-2012)

$Global_Language = 'en';
/*  Printing CODE Samples   */
$Connector = array(
		0	=>	"0",
		"000"	=>	"0",
		"060"	=>	"+",
		"090"	=>	"-",
		"120"	=>	"+",
		"180"	=>	"-",
		"-->" 	=>  "ingrass",
		"S/D" => "moves direct",
		"S/R" => "moves retrograde",
		"-1"    =>  "");

//Retrives from Database
$AspectTypes = array(
		"000"	=>	"CON",
		"060"	=>	"POS",
		"090"	=>	"NEG",
		"120"	=>	"POS",
		"180"	=>	"NEG",
		"-->" 	=>  "CON",
		"S/D" => "moves direct",
		"S/R" => "moves retrograde",
		""      =>  "CON",
		"NA"    =>  "CON");

//Retrives from Database
$AspectAbbr = array(
		"TR"	=>	"T",
		"PR"	=>	"P-R",
		"SR"	=>	"SR-SR",
		"SRSIGN" => "SR-SR");

//Retrives from Database
$AspectConnector = array(
		"TR"	=>	"t",
		"PR"	=>	"p",
		"SR"	=>	"sr",
		"SRSIGN" => "sr");

/*  Printing CODE Samples   */

$NameOfPlanets = array(
		"1000" => "Sun",
		"1001" => "Moon",
		"1002" => "Mercury",
		"1003" => "Venus",
		"1004" => "Mars",
		"1005" => "Jupiter",
		"1006" => "Saturn",
		"1007" => "Uranus",
		"1008" => "Neptune",
		"1009" => "Pluto",
		"1010" => "N.Node",
		"1011" => "S.Node", /* put here to stop the nag messages, not used */
		"1012" => "Ascendant",
		"1013" => "Midheaven", /* "MC", */
		"1014" => "IC",
		"1015" => "Descendant",
		"1016" => "Chiron",
		/** SIGN NAME **/
		"0101" => "Aries",
		"0102" => "Taurus",
		"0103" => "Gemini",
		"0104" => "Cancer",
		"0105" => "Leo",
		"0106" => "Virgo",
		"0107" => "Libra",
		"0108" => "Scorpio",
		"0109" => "Sagittarius",
		"0110" => "Capricorn",
		"0111" => "Aquarius",
		"0112" => "Pisces",
		/** HOUSE CUPS NAME **/
		"0001" => "1st House",
		"0002" => "2nd House",
		"0003" => "3rd House",
		"0004" => "4th House",
		"0005" => "3th House",
		"0006" => "6th House",
		"0007" => "7th House",
		"0008" => "8th House",
		"0009" => "9th House",
		"0010" => "10th House",
		"0011" => "11th House",
		"0012" => "12th House"
);

$NameOfAspects = array(
		"000" => "Conjunction",
		"060" => "Sextile",
		"090" => "Square",
		"120" => "Trine",
		"180" => "Opposition",
		"-->" => 'ingrass',
		"S/D" => "moves direct",
		"S/R" => "moves retrograde",
		"-1"  => ""
);

$NameOfHouses = array(
		"0001" => "1st House",
		"0002" => "2nd House",
		"0003" => "3rd House",
		"0004" => "4th House",
		"0005" => "3th House",
		"0006" => "6th House",
		"0007" => "7th House",
		"0008" => "8th House",
		"0009" => "9th House",
		"0010" => "10th House",
		"0011" => "11th House",
		"0012" => "12th House");


//http://stackoverflow.com/questions/1384380/is-there-a-unicode-glyph-that-looks-like-a-key-icon
$WowSymbolFonts = array(
/* planets */
		'Sun' => 184, 'Moon' => 155,
		'Mercury' => 190, 'Venus' => 177, 'Mars' => 161,
		'Jupiter' => 165, 'Saturn' => 123,
		'Uranus' => 134, 'Neptune' => 135, 'Pluto' => 136,
		'Chiron' => 9911, /* 9911 - 9897*/
		/* nodes */
		'N.Node' => 168, 'S.Node' => 130,
		/* signs */
		'Aries' => 247, 'Taurus' => 154, 'Gemini' => 208, 'Cancer' => 152,
		'Leo' => 172, 'Virgo' => 170, 'Libra' => 171, 'Scorpio' => 133,
		'Sagittarius' => 125, 'Capricorn' => 131, 'Aquarius' => 139, 'Pisces' => 138,
		/* aspects */
		'Conjunction' => 180, 'Semisextile' => 222, 'Semisquare' => 188, 'Sextile' => 181, 'Square' => 185,
		'Trine' => 186, 'Sesquisquare' => 164, 'Quincunx' => 222 /* quincunx = inverted semisextile */,
		'Opposition' => 175,
		/* angles */
		'Ascendant' => 124, 'Descendant' => 254, 'MC' => 91, 'IC' => 93,
		/* retrograde */
		'Retrograde' => 182
);


$SectionRelatedPlanets = array(
		"10001"	=>	"1012",		// Ascendant = AS
		"10002"	=>	"1013",		// Midheaven = MC
		"10003"	=>	"1000",		// SUN
		"10004"	=>	"1001",		// MOON
		"10005"	=>	"1002",		// Mercury
		"10006"	=>	"1003",		// Venus
		"10007"	=>	"1004",		// Mars
		"10008"	=>	array("1005", "1006", "1007", "1008", "1009", "1010")
		//"10008"	=>	array(Jupiter, Saturn, Uranus, Neptune, "Pluto, N.Node)
);

$ChapterNameBasedOnPlanet = array (
		"1012"  => "10001",		// Ascendant = AS
		"1013"  => "10002",		// Midheaven = MC
		"1014"  => "10002",		// IC
		"1015"  => "10001",		// Descendant = DS
		"1000"  => "10003",		// SUN
		"1001"  => "10004",		// MOON
		"1002"  => "10005",		// Mercury
		"1003"  => "10006",		// Venus
		"1004"  => "10007",		// Mars
		"1005"  => "10008",		// Jupitar
		"1006"  => "10008",		// Saturn
		"1007"  => "10008",		// Urabus
		"1008"  => "10008",		// Neptune
		"1009"  => "10008",		// Pluto
		"1010"  => "10008",		// N.Node
		"1011"  => "10008",		// S.Node
		"1016"  => "10008");	// Chiron

/***************************************/
$SignRulers = array(
		0 => '1004',		// "Aries"       => "Mars"
		1 => '1003',		// "Taurus"	     => "Venus"
		2 => '1002',		// "Gemini"      => "Mercury"
		3 => '1001',		// "Cancer"      => "Moon"
		4 => '1000',		// "Leo"         => "Sun"
		5 => '1002',		// "Virgo"       => "Mercury"
		6 => '1003',		// "Libra"	     => "Venus"
		7 => '1004',		// "Scorpio"     => "Mars"
		8 => '1005',		// "Sagittarius" => "Jupiter"
		9 => '1006',		// "Capricorn"   => "Saturn"
		10 => '1006',		// "Aquarius"    => "Saturn"
		11 => '1005',		// "Pisces"      => "Jupiter"
);

/****** Variables for Setting the Rank ******************/

//PL = 1 -> NE = 2 ->  UR = 3 -> SA = 4 -> JU = 5
//$PlanetRanks
$TransitingPlanetRank = array (
		/*****Planets and Houses ***/
		"1000" => 0,	//"Sun",
		"1001" => 2,	//"Moon",
		"1002" => 3,	//"Mercury",
		"1003" => 4,	//"Venus",
		"1004" => 5,	//"Mars",
		"1005" => 6,	//"Jupiter",
		"1006" => 7,	//"Saturn",
		"1007" => 8,	//"Uranus",
		"1008" => 9,	//"Neptune",
		"1009" => 10, 	//"Pluto",
		"1010" => 0,	//"N.Node",
		"1011" => 0,	//"S.Node",
		"1012" => 0,	//"Ascendant" "AC",
		"1013" => 0,	//"Midheaven" "MC",
		"1014" => 0,	//"IC",
		"1015" => 0,	//"Descendant" "DC"
		"1016" => 1,	//"Chiron" "CH"
		/*****Houses ***/
		"0001" => 0,	//"1st House",
		"0002" => 0,	//"2nd House",
		"0003" => 0,	//"3rd HOuse",
		"0004" => 0,	//"4th House",
		"0005" => 0,	//"5th House",
		"0006" => 0,	//"6th House",
		"0007" => 0,	//"7th House",
		"0008" => 0,	//"8th House",
		"0009" => 0, 	//"9th House",
		"0010" => 0,	//"10th House",
		"0011" => 0,	//"11th House",
		"0012" => 0,	//"12th House"
);

//Conjunction = 1 -> Opposition = 2 -> Trine = 3 -> Square = 4 -> Sextile = 5
//$RankForTransits
$AspectRank = array(
		"000" =>  10,  	//"Conjunction",
		"060" =>  6,	//"Sextile",
		"090" =>  7,	//"Square",
		"120" =>  8,	//"Trine",
		"180" =>  9,	//"Opposition"
		"S/D" => "moves direct",
		"S/R" => "moves retrograde",
		""    => ""
);

//AS > DS > MC > IC > conjunction only
//SU > MO > ME > VE > MA
//$PlanetToCheckForTransit
$NatalPlanetRank = array (
		/*****Planets and Houses ***/
		"1000" => 9,	//"Sun",
		"1001" => 9,	//"Moon",
		"1002" => 8,	//"Mercury",
		"1003" => 8,	//"Venus",
		"1004" => 8,	//"Mars",
		"1005" => 7,	//"Jupiter",
		"1006" => 6,	//"Saturn",
		"1007" => 5,	//"Uranus",
		"1008" => 4,	//"Neptune",
		"1009" => 3, 	//"Pluto",
		"1010" => 1,	//"N.Node",
		"1011" => 1,	//"S.Node",
		"1012" => 10,	//"Ascendant" "AC"
		"1013" => 10,	//"Midheaven" "MC",
		"1014" => 10,	//"IC",
		"1015" => 10,	//"Descendant" "DC"
		"1016" => 1,	//"Chiron" "CH"
		/*****Houses ***/
		"0001" => 0,	//"1st House",
		"0002" => 0,	//"2nd House",
		"0003" => 0,	//"3rd HOuse",
		"0004" => 0,	//"4th House",
		"0005" => 0,	//"5th House",
		"0006" => 0,	//"6th House",
		"0007" => 0,	//"7th House",
		"0008" => 0,	//"8th House",
		"0009" => 0, 	//"9th House",
		"0010" => 0,	//"10th House",
		"0011" => 0,	//"11th House",
		"0012" => 0,	//"12th House"
);

/* PROGRESSED PLANET RANKING */
//AS = 10 -> MC = 10 -> MA = 9 -> VE = 9 -> SU = 8 -> ME = 8
$ProgressedPlanetRank = array (
/*****Planets and Houses ***/
		"1000" => 8,	//"Sun",
		"1001" => 0,	//"Moon",
		"1002" => 8,	//"Mercury",
		"1003" => 9,	//"Venus",
		"1004" => 9,	//"Mars",
		"1005" => 0,	//"Jupiter",
		"1006" => 0,	//"Saturn",
		"1007" => 0,	//"Uranus",
		"1008" => 0,	//"Neptune",
		"1009" => 0, 	//"Pluto",
		"1010" => 0,	//"N.Node",
		"1011" => 0,	//"S.Node",
		"1012" => 10,	//"Ascendant" "AC"
		"1013" => 10,	//"Midheaven" "MC",
		"1014" => 0,	//"IC",
		"1015" => 0,	//"Descendant" "DC"
		"1016" => 0,	//"Chiron" "CH"
		/*****Houses ***/
		"0001" => 0,	//"1st House",
		"0002" => 0,	//"2nd House",
		"0003" => 0,	//"3rd HOuse",
		"0004" => 0,	//"4th House",
		"0005" => 0,	//"5th House",
		"0006" => 0,	//"6th House",
		"0007" => 0,	//"7th House",
		"0008" => 0,	//"8th House",
		"0009" => 0, 	//"9th House",
		"0010" => 0,	//"10th House",
		"0011" => 0,	//"11th House",
		"0012" => 0,	//"12th House"
);

/*  PL=10 NE=10 UR=10 SA=10 JU=10 MA=8 VE=8 ME=8 SU=8 AS=7 MC=7 CH=6 NN=6 */
$ProgressedNatalPlanetRank = array (
		/*****Planets and Houses ***/
		"1000" => 9,	//"Sun",
		"1001" => 0,	//"Moon",
		"1002" => 9,	//"Mercury",
		"1003" => 9,	//"Venus",
		"1004" => 9,	//"Mars",
		"1005" => 10,	//"Jupiter",
		"1006" => 10,	//"Saturn",
		"1007" => 10,	//"Uranus",
		"1008" => 10,	//"Neptune",
		"1009" => 10, 	//"Pluto",
		"1010" => 7,	//"N.Node",
		"1011" => 0,	//"S.Node",
		"1012" => 8,	//"Ascendant" "AC"
		"1013" => 8,	//"Midheaven" "MC",
		"1014" => 0,	//"IC",
		"1015" => 0,	//"Descendant" "DC"
		"1016" => 7,	//"Chiron"
		/*****Houses ***/
		"0001" => 0,	//"1st House",
		"0002" => 0,	//"2nd House",
		"0003" => 0,	//"3rd HOuse",
		"0004" => 0,	//"4th House",
		"0005" => 0,	//"5th House",
		"0006" => 0,	//"6th House",
		"0007" => 0,	//"7th House",
		"0008" => 0,	//"8th House",
		"0009" => 0, 	//"9th House",
		"0010" => 0,	//"10th House",
		"0011" => 0,	//"11th House",
		"0012" => 0,	//"12th House"
);

/*  PL=10 NE=9 UR=8 SA=7 JU=6 NN=5 */
$CollectivePlanetRank = array (/*****Planets ***/
		"1005" => 6,	//"Jupiter",
		"1006" => 7,	//"Saturn",
		"1007" => 8,	//"Uranus",
		"1008" => 9,	//"Neptune",
		"1009" => 10, 	//"Pluto",
		"1010" => 5,	//"N.Node",
		"1011" => 5,	//"S.Node"
);

$AbbrPlanetToCode = array(
		"SU"	=>	1000,
		"MO"	=>	1001,
		"ME"	=>	1002,
		"VE"	=>	1003,
		"MA"	=>	1004,
		"JU"	=>	1005,
		"SA"	=>	1006,
		"UR"	=>	1007,
		"NE"	=>	1008,
		"PL"	=>	1009,
		"NN"	=>	1010,
		"SN"	=>	1011,
		"AS"	=>	1012,
		"MC"	=>	1013,
		"IC"	=>	1014,
		"DS"	=>	1015,
		"CH" 	=>  1016);

//Planet,Sign abbrivation to full name
$AbbrPlanetToFullName = array(
		//	Planet code to Abbr name
		"0000" => "SU",
		"1000" => "SU",
		"1001" => "MO",
		"1002" => "ME",
		"1003" => "VE",
		"1004" => "MA",
		"1005" => "JU",
		"1006" => "SA",
		"1007" => "UR",
		"1008" => "NE",
		"1009" => "PL",
		"1010" => "NN",
		"1011" => "SN",
		"1012" => "AS",
		"1013" => "MC",
		"1014" => "IC",
		"1015" => "DS",
		"1016" => "CH",
		//Signs
		"0101" => "AR",
		"0102" => "TA",
		"0103" => "GE",
		"0104" => "CN",
		"0105" => "LE",
		"0106" => "VI",
		"0107" => "LI",
		"0108" => "SC",
		"0109" => "SG",
		"0110" => "CP",
		"0111" => "AQ",
		"0112" => "PI",
		"SU"	=>	"Sun",
		"MO"	=>	"Moon",
		"ME"	=>	"Mercury",
		"VE"	=>	"Venus",
		"MA"	=>	"Mars",
		"JU"	=>	"Jupiter",
		"SA"	=>	"Saturn",
		"UR"	=>	"Uranus",
		"NE"	=>	"Neptune",
		"PL"	=>	"Pluto",
		"NN"	=>	"N.Node",
		"SN"	=>	"S.Node",
		"AS"	=>	"Ascendant",
		"MC"	=>	"Midheaven",
		"IC"	=>	"IC",
		"DS"	=>	"Descendant",
		"CH" 	=>  "Chiron",
		//Signs
		"AR"	=>	"Aries",
		"TA"	=>	"Taurus",
		"GE"	=>	"Gemini",
		"CN"	=>	"Cancer",
		"LE"	=>	"Leo",
		"VI"	=>	"Virgo",
		"LI"	=>	"Libra",
		"SC"	=>	"Scorpio",
		"SG"	=>	"Sagittarius",
		"CP"	=>	"Capricorn",
		"AQ"	=>	"Aquarius",
		"PI"	=>	"Pisces",
		//Planet Full name to Abbr
		"Sun"        => "SU",
		"Moon"       => "MO",
		"Mercury"    => "ME",
		"Venus"      => "VE",
		"Mars"       => "MA",
		"Jupiter"    => "JU",
		"Saturn"     => "SA",
		"Uranus"     => "UR",
		"Neptune"    => "NE",
		"Pluto"      => "PL",
		"N.Node"     => "NN",
		"S.Node"     => "SN",
		"Ascendant"  => "AS",
		"Midheaven"  => "MC",
		"IC"         => "IC",
		"Descendant" => "DC",
		//Signs
		"Aries"       => "AR",
		"Taurus"      => "TA",
		"Gemini"      => "GE",
		"Cancer"      => "CN",
		"Leo"         => "LE",
		"Virgo"       => "VI",
		"Libra"       => "LI",
		"Scorpio"     => "SC",
		"Sagittarius" => "SG",
		"Capricorn"   => "CP",
		"Aquarius"    => "AQ",
		"Pisces"      => "PI"
		);


/********************************  THEME Integration ********************************/
$SectionHeadingColor = array(151, 89, 184);
$ContentColor = array(90, 90, 90);
$SectionPageNo = array();
$ProcessedTheme = array();

$ThemePageArray = array();
$ThemePageArray['CoverPage'] = BINPATH . '/pages/year_report_theme/Instroduction.pdf';

$ThemePageArray['Default'] = BINPATH . '/pages/year_report_theme/1_LifePath_BG.pdf';
$ThemePageArray['Default_BG'] = BINPATH . '/pages/year_report_theme/1_LifePath_BG.pdf';

$ThemePageArray['LifePath'] = BINPATH . '/pages/year_report_theme/1_LifePath.pdf';
$ThemePageArray['LifePath_BG'] = BINPATH . '/pages/year_report_theme/1_LifePath_BG.pdf';

$ThemePageArray['Career'] = BINPATH . '/pages/year_report_theme/2_Career.pdf';
$ThemePageArray['Career_BG'] = BINPATH . '/pages/year_report_theme/2_Career_BG.pdf';

$ThemePageArray['PersonalDevelopment'] = BINPATH . '/pages/year_report_theme/3_PersonalDevelopment.pdf';
$ThemePageArray['PersonalDevelopment_BG'] = BINPATH . '/pages/year_report_theme/3_PersonalDevelopment_BG.pdf';

$ThemePageArray['FeelingsAndSecurity'] = BINPATH . '/pages/year_report_theme/4_FeelingsAndSecurity.pdf';
$ThemePageArray['FeelingsAndSecurity_BG'] = BINPATH . '/pages/year_report_theme/4_FeelingsAndSecurity_BG.pdf';

$ThemePageArray['MentalDevelopment'] = BINPATH . '/pages/year_report_theme/5_MentalDevelopment.pdf';
$ThemePageArray['MentalDevelopment_BG'] = BINPATH . '/pages/year_report_theme/5_MentalDevelopment_BG.pdf';

$ThemePageArray['ValuesLoveAndRelationships'] = BINPATH . '/pages/year_report_theme/6_ValuesLoveAndRelationships.pdf';
$ThemePageArray['ValuesLoveAndRelationships_BG'] = BINPATH . '/pages/year_report_theme/6_ValuesLoveAndRelationships_BG.pdf';

$ThemePageArray['WillpowerAndAssertion'] = BINPATH . '/pages/year_report_theme/7_WillpowerAndAssertion.pdf';
$ThemePageArray['WillpowerAndAssertion_BG'] = BINPATH . '/pages/year_report_theme/7_WillpowerAndAssertion_BG.pdf';

$ThemePageArray['CollectiveTrends'] = BINPATH . '/pages/year_report_theme/8_CollectiveTrends.pdf';
$ThemePageArray['CollectiveTrends_BG'] = BINPATH . '/pages/year_report_theme/8_CollectiveTrends_BG.pdf';

$SectionName = array(0 => 'Default',
		1 => 'LifePath',
		2 => 'Career',
		3 => 'PersonalDevelopment',
		4 => 'FeelingsAndSecurity',
		5 => 'MentalDevelopment',
		6 => 'ValuesLoveAndRelationships',
		7 => 'WillpowerAndAssertion',
		8 => 'CollectiveTrends');

/********************************  THEME Integration ********************************/
?>