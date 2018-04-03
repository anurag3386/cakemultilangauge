<?php
  /**
   * Script: /var/lib/reports/en/collate.intro.page.content.php
   * Author: Andy Gray
   *
   * Description
   * English report introductory page content included by affiliate collate classes
   */

$this->SetLeftMargin(16);
$this->SetY(30);

$this->IntroductionHeader( utf8_decode('ANALISIS ASTROLOGICO') );
$this->IntroductionParagraph
(utf8_decode("Este horóscopo es su huella cósmica personal. Ninguna otra persona tendrá la misma combinación de influencias, a menos que haya nacido exactamente en el mismo instante y en el mismo lugar que usted. Con 10 planetas, 12 signos del zodiaco y 12 casas, se pueden dar aproximadamente 1500 combinaciones simples en el horóscopo, y si añadimos a ello los diferentes grados y combinaciones entre los planetas, hay decenas de millares de influencias que deberán tenerse en cuenta en cualquier interpretación astrológica.") );

$this->IntroductionHeader( utf8_decode( 'LAS DISTINTAS SECCIONES' ) );
$this->IntroductionParagraph
(
 utf8_decode("World of Wisdom está diseñado para simplificar ese proceso. Indica, por ejemplo, las fuerzas relativas de distintas influencias planetarias para ofrecer una mejor evaluación al usuario. Además, el informe escrito está dividido en las secciones de identidad, emociones, mentalidad, amor y sexualidad, evitando asφ posibles contradicciones en la descripción del carácter, aunque el lector con más experiencia debe tener en cuenta que pueden surgir contradicciones. Los seres humanos son complejos y tienen muchas características contradictorias. Las descripciones de carácter totalmente opuestas reflejaron dilemas internos o conflictos externos reproducidos en el comportamiento de los demás.")
 );

$this->IntroductionHeader( utf8_decode('PERFIL PROFESIONAL O PERSONAL') );
$this->IntroductionParagraph
(
 utf8_decode("Cada interpretación se divide en dos apartados principales: el profesional y el personal. El profesional responde a la pregunta: \"¿Qué capacidades tengo y cómo puedo sacarles partido?\" El personal está basado en las relaciones humanas y en condicionantes psicológicos ocultos, y responde a la pregunta: \"¿Cómo puedo mejorar en este campo?\"")
 );

$this->IntroductionHeader( utf8_decode('PASADO, PRESENTE Y FUTURO') );
$this->IntroductionParagraph
(
 utf8_decode("Este módulo básico de World of Wisdom también contiene un módulo dinámico y por lo tanto va más allá de un análisis estético del carácter personal. Al activar la opción del análisis dinámico, se describe el horóscopo a través del tiempo. Mediante el análisis de las tendencias actuales, el lector tendrá una visión de los acontecimientos del pasado reciente, de lo que pasa en el presente y de las tendencias para el futuro. Esperamos que estas interpretaciones puedan ofrecer a las personas que no tienen tiempo para profundizar en el estudio de la astrología la oportunidad de tener una visión más clara tanto de su vida profesional como personal.")
 );

$this->IntroductionHeader( 'THE AUTHOR' );
$this->IntroductionParagraph
(
 "This report is designed and written by Adrian Ross Duncan, ".
 "former editor of the Astrological Association Journal, ".
 "who has worked as a full time astrologer since 1985. ".
 "Adrian is author of \"Doing Time on Planet Earth\" (Element Books 1990) ".
 "and \"Astrology: Transformation and Empowerment\" (Red Wheel/Weiser 2002). ".
 "His astrology software Horoscope Interpreter, Astrology for Lovers, and The Astrological Calendar ".
 "are available from www.worldofwisdom.co.uk"
 );

$this->IntroductionParagraph
(
 "It is our hope that the written report will give those who do not have the time to bury themselves ".
 "in the study of astrology the opportunity of gaining insight into both their professional and personal life. ".
 "If you wish to learn a little more about this report, ".
 "you are encouraged to read the Introduction to Astrology at the end of the report, ".
 "which describes the general influence of the 12 signs, the planets and the 12 houses"
 );

$this->IntroductionHeader( 'TABLE OF CONTENTS' );

?>