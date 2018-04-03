<?php
/**
 * Script: /var/lib/reports/dk/collate.intro.page.content.php
 * Author: Amit Parmar
 *
 * Description
 * Danish report introductory page content included by affiliate collate classes
 */

$this->SetLeftMargin(16);
$this->SetY(30);

$this->IntroductionHeader( utf8_decode('ASTROKALENDER 3-MÅNEDERS RAPPORT') );
$this->IntroductionParagraph ( utf8_decode( 'Astrokalenderen fra World of Wisdom gør det muligt for dig at planlægge i en periode på 3 '.
        'måneder, hvor du kan time dine gode perioder i dine nære relationer samt dit karriere. '.
        'Denne rapport er baseret på planeternes bevægelser i denne periode i relation til '.
        'fødselshoroskopet (som er vist som en farvetegning i begyndelsen af rapporten). Transitter '.
        'fra alle planeterne bliver beregnet og deres indflydelse på dit fødselshoroskop bliver '.
        'beskrevet i en kort og præcis tekst.') );
$this->Ln(8);

$this->IntroductionParagraph( utf8_decode( 'Men ikke desto mindre er det ikke nok kun at læse om en indflydelse. Det, der er vigtigt at '.
        'vide, er hvordan man skal handle. Der er tider hvor det er vigtigt at handle her og nu, og '.
        'tider hvor det er klogt at holde lav profil eller forblive passiv. Astrokalender-rapporter hjælper '.
        'dig med at stille dig den slags spørgsmål, som måske ligger dig på sinde på grund af den '.
        'astrologiske indflydelse, samt det at komme med svarene.') );
$this->Ln(8);

$this->IntroductionParagraph( utf8_decode( 'Hvad kan der for eksempel ske, når kærlighedsplaneten Venus danner en konjunktion til '.
        'Mars i dit ') .' horoskop? ' . utf8_decode('Astrokalender rapporten hjælper dig med at beskrive denne indflydelse:') );

$this->IntroductionParagraph( utf8_decode('Kærlighed og Spænding: Du er romantisk indstillet i dag: Skab kontakt, dans og nyd '.
        'kærligheden. Vær opsøgende og glem tilbageholdenheden. Nyd tiltrækningen. '.
        'Rapporten fortsætter med at foreslå dette spørgsmål: ').'"Er det OK at flirte?" Svaret er klart: '.
        '"Hvis du ikke flirter i dag,'.utf8_decode( 'hvornår vil du så flirte. Der er erotiske gnister i luften og i dag '.
        'gælder det om at nyde livet fuldt ud.').'"' );
$this->Ln(8);

$this->IntroductionParagraph( utf8_decode('Ved at gøre brug af spørgsmål-og-svar teknikken i rapporterne, så må du som læser tænke '.
        'over hvordan du egentlig føler, og hvis du er i tvivl, så vil de svar sætte dig i stand til at følge '.
        'den vej, som du ubevidst godt ved er den rigtige vej. På den måde kan du bruge rapporten '.
        'til at planlægge fremad, undgå dage med negative indflydelser og forøge dine chancer for '.
        'succes, og få det meste ud af de dage, der er gode for dig.') );
$this->Ln(8);

$this->IntroductionParagraph( utf8_decode('Mens rapporten viser indflydelser, som varer fra en dag til flere adskillige måneder, er det '.
        'vigtigt også at forstå dit personlige fødselshoroskop i dybden. Hvis du ikke allerede har fået '.
        'lavet et, er det virkelig det værd at bestille World of Wisdoms fødselshoroskop rapport. Den '.
        'beskriver din karakter både i forhold og i din karriere; den indeholder også mere detaljerede '.
        'beskrivelser af længerevarende indflydelser, som påvirker dit liv i de kommende år.') );
$this->Ln(8);

$this->IntroductionHeader( utf8_decode('Værd at huske når du læser rapporten:') );

$this->IntroductionParagraph( utf8_decode('* Din fødselsdag: Programmet registrerer når Solen vender tilbage til præcis det samme '.
        'sted den var, da du blev født. I nogle tilfælde kan det ske en dag før eller efter din officielle fødselsdag!') );
$this->Ln(8);

$this->IntroductionParagraph( utf8_decode('* Måske vil der være dage, hvor der ikke er nogen indflydelser; det er fordi der ikke er '.
        'nogle planeter, der aktiverer dit fødselshoroskop den dag.') );
$this->Ln(8);

$this->IntroductionParagraph( utf8_decode('* Indflydelsen fra Solen, Merkur, Venus, og Mars er meget flygtige, mens indflydelsen fra '.
        'Jupiter, Saturn, Uranus, Neptun og Pluto kan vare i flere måneder. Alle disse tolkninger '.
        'tager tidselementet med i overvejelsen.') );
$this->Ln(8);

$this->IntroductionParagraph( utf8_decode('* Retrograde bevægelser: Undertiden "stopper planeterne op" og bevæger sig "baglæns" '.
        '(på grund af deres relative bevægelse i forhold til Jorden). Det kan betyde at den samme '.
        'indflydelse bliver vist mere end en gang i rapporten.') );
?>