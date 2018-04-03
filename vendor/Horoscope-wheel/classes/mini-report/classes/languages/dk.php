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
$page_number_name['dk']= array("Page"	=>		"Page ");


$Introduction_Generic_Text["dk"]["BirthReport"] = "Birth Report";

$Introduction_Generic_Text["dk"]["Introduction"] = "Hej %s,";
$Introduction_Generic_Text["dk"]["IntroductionText"][0] = utf8_decode("Tak for din bestilling af en mini fødselshoroskop-analyse, som jeg er sikker på vil hjælpe dig sætte pris på harmonien mellem dit liv og universet, hvilket også kan inspirere dig til at igangsætte positive forandringer i dit liv.");
$Introduction_Generic_Text["dk"]["IntroductionText"][1] = utf8_decode("Da jeg designede den korte rapport, fokuserede jeg på det karaktertræk, som er helt unikt for dig. Men - endnu vigtigere - analyserede jeg også dit horoskop for at finde den astrologiske indflydelse, som har den stærkeste effekt på din fremtid her og nu. Således kan du planlægge tingene, overvinde udfordringerne og optimere dit held. Hav en god rejse...");

//SUN
$Introduction_Generic_Text["dk"]["PlanetIntroductionText"]["1000"][0] = "Your Sun Sign";
$Introduction_Generic_Text["dk"]["PlanetIntroductionText"]["1000"][1] = "The first question that astrology answers for you is \"who am I?\" Not who you \"think\" you are but the person you are when you are all alone at home in the dark.
I'm talking about the \"true self\" you've had inside since childhood.  It makes up the bulk of your self esteem and how you feel about yourself every day.
The more you express your inner \"authentic self\" the more you will succeed and others will respect you.
When you discover the secrets of who you are you begin to maximize your strengths.  You stop wasting time with weaknesses - even if you didn't know you have them!
For you, your %s is in the sign of %s.";
$Introduction_Generic_Text["dk"]["PlanetIntroductionText"]["1000"][2] = "What Your %s In %s Says About You...";

//Mercury
$Introduction_Generic_Text["dk"]["PlanetIntroductionText"]["1002"][0] = "Your Mercury Sign";
$Introduction_Generic_Text["dk"]["PlanetIntroductionText"]["1002"][1] = "One of the best talents you have is how you communicate. This starts with your family, friends and teachers early in life.  Later on the way you learn, get social, and talk matters the most!
It's how you get the financial freedom and romance you want in life. You must work on your strengths and not focus on your weakness. That way you will not get upset and never have to doubt yourself or worry anymore! Now, there is one area you are amazing at and is what you should be focusing on.
This of course comes from your %s in %s.";
$Introduction_Generic_Text["dk"]["PlanetIntroductionText"]["1002"][2] = "What Your %s In %s Says About You...";

//ASPECTS
//$Introduction_Generic_Text["dk"]["AspectIntroductionText"][0] = "The strongest influence in your birth horoscope is \"%s %s %s\". This is both a great talent and a great challenge. Know yourself, and you will be able to optimize the talent and overcome the challenges.";
$Introduction_Generic_Text["dk"]["AspectIntroductionText"][0] = utf8_decode("Den stærkeste indflydelse i dit fødselshoroskop er \"%s %s %s\". Det er både et stort talent og en stor udfordring. Når du forstår denne indflydelse, får du bedre styr på den.");
$Introduction_Generic_Text["dk"]["AspectIntroductionText"][1] = utf8_decode("Hvordan denne indflydelse påvirker dit personlige liv:");
$Introduction_Generic_Text["dk"]["AspectIntroductionText"][2] = utf8_decode("Hvordan denne indflydelse påvirker dit professionelle liv:");

//TRANSIT
$Introduction_Generic_Text["dk"]["TransitIntroductionText"][0] = utf8_decode("Jo ældre du bliver, jo bedre bliver du til at administrere dette karaktertræk og til at forvandle uønsket adfærd. Universet er i konstant udvikling, og det er du også. I astrologi, når man ønsker at forstå personlig udvikling, kigger man på planeternes bevægelser i forhold til fødselshoroskopet (transitter). Læs videre for at se den allerstærkeste transit i dit liv lige nu. Når du forstår denne indflydelse, så vil du opdage præcis hvordan du kan få det meste ud af nuværende muligheder.");
//$Introduction_Generic_Text["dk"]["TransitIntroductionText"][1] = "The most powerful influence in your life right now is \"%s %s %s\". Transits tend to last for about a year, and there are three intense peaks of power during that period. If you don't get it right first time, understanding the following trend will certainly help you get it right the last time!";
$Introduction_Generic_Text["dk"]["TransitIntroductionText"][1] = utf8_decode("Den stærkeste indflydelse i dit liv lige nu er %s i %s til %s. En transit plejer at vare ca. et år, og der er normalt tre stærke udsving i løbet af året. Hvis du ikke har fattet hvad du skal gøre første gang, så har du sikkert forstået det i slutningen af perioden, så du klarer det sidste gang!");
$Introduction_Generic_Text["dk"]["TransitIntroductionText"][2] = utf8_decode('Hvordan denne indflydelse påvirker dit personlige liv:');
$Introduction_Generic_Text["dk"]["TransitIntroductionText"][3] = utf8_decode('Hvordan denne indflydelse påvirker dit professionelle liv:');

$Introduction_Generic_Text["dk"]["SummaryText"][0] = utf8_decode("Nu har du lært noget om, hvad er enestående i din karakter, og også noget om, hvad gør den nuværende periode speciel. Men denne korte rapport har ikke taget i betragtning to meget vigtige faktorer, som kan gøre en horoskopanalyse endnu mere præcis. Nå du også tilføjer dit fødselstidspunkt og fødselssted, så kan planeterne placeres i de tolv huse i horoskopet. Med denne information kan man få en rigtig præcis beskrivelse af dine udfordringer og styrker i forbindelse med arbejde, kærlighed, sex, tilknytning og meget mere. Du får her en mulighed for at få den udvidede fortolkning af dit horoskop - en omfattende analyse på ca. 20+ sider leveret via e-mail.");
$Introduction_Generic_Text["dk"]["SummaryText"][1] = utf8_decode("Klik her for at få den udvidede rapport");
$Introduction_Generic_Text["dk"]["SummaryText"][2] = utf8_decode("Held og lykke i fremtiden - din astrolog Adrian");