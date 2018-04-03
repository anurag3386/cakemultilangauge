<?php
  /**
   * Script: /var/lib/reports/en/collate.intro.page.content.season.php
   * Author: Andy Gray
   *
   * Description
   * English report introductory page content included by affiliate collate classes
   */

$this->SetLeftMargin(16);
$this->SetY(30);

//$this->IntroductionHeader( 'HOW TO READ YOUR ASTRO CALENDAR REPORT' );
//											 Personal 3-Month Day-To-Day Planner
$this->IntroductionHeader( 'HOW TO READ YOUR PERSONAL 3-MONTH DAY-TO-DAY PLANNER' );

$this->IntroductionParagraph
(
 "The Personal 3-Month Day-To-Day Planner enables you to plan your moves and perfect your timing in your love life,  ".
 "your relationships and your career. This report is based on the movement of the planets in a  ".
 "designated period in relation to your birth horoscope. Transits of all the planets are mapped, ".
 "and the precise influence of how these connect to your birth chart is described in a short and concise text. "
 );

$this->IntroductionParagraph
(
 "However, it is not enough to read about an influence. What is important is to know how to act. ".
 "The Personal 3-Month Day-To-Day Planner helps you by imagining what kind of questions you might ask yourself as a result ".
 "of the astrological influence. And providing answers. What would happen for example, if the planet of love, ".
 "Venus, came to conjoin Mars in your horoscope? The Personal 3-Month Day-To-Day Planner describes this influence in this way:"
 );

$this->IntroductionParagraph
(
 "Love and Excitement: You are turned on today. Dance, romance and make love. Be assertive and forget the finer sensibilities for a while. ".
 "Enjoy your desires. " 
 );

$this->IntroductionParagraph
(
 utf8_decode("It then goes on to suggest that you may ask the question: \"Is it OK to flirt?\" and the answer is clearly: ".
 "\"If you don't flirt today, you'll never flirt. The suggestion of sex definitely spices up life, and today you should be thinking ".
 "about how to be more alive.\"")
 );

$this->IntroductionParagraph
(
 "By using a question and answer technique in the reports you, the reader, have to really think about what you are feeling, ".
 "and if you are in doubt, then the answers will enable you to follow what you unconsciously know is the right path. ".
 "In this way you can use the report to plan ahead, avoiding days when negative trends decrease your chances of success, ".
 "and making the most of days that are astrologically favorable for you."
 );

$this->IntroductionParagraph
(
  "Whilst this report shows trends that last from just one day to several months, it is important to also understand your  ".
 "personal birth chart in depth. If you have not already had one made, it is really worth ordering our My Life Path Report & Birth Chart.  ".
 "This describes your character in detail, both in relationships and in your career, and it also contains are more detailed  ".
 "description of long-term trends that are affecting your life in the years ahead. If you would like to learn more about your  ".
 "horoscope, a concise description of the planets, signs, aspects and houses is included at the end of this report."
 );

$this->IntroductionParagraph
(
 "Some things to remember when you read your report:"
 );

$this->IntroductionParagraph
(
 "- Your birthday: The program registers when the Sun returns to the exact place it was when you were born. ".
	"In some cases this can happen on the day before or after your official birthday!"
 );

$this->IntroductionParagraph
(
 "- If you find there are days when there are no influences, this is because no planets activate your birth horoscope that day (although the Moon will)."
 );

$this->IntroductionParagraph
(
 "- The influence of the Sun, Mercury, Venus and Mars is very transient, whilst that of Jupiter, Saturn, Uranus, ".
 "Neptune and Pluto can last many months. All the interpretations take this time element into consideration."
 );

$this->IntroductionParagraph
(
 "- Retrograde movement: Occasionally the planets appear to stop and move backwards ".
 "(owing to their relative movement in relation to that of the Earth). This can mean that the same ".
 "influence will be shown more than once in the report."
 );

$this->Ln(1);

$this->IntroductionParagraph
(
		"You may notice contradictions in your report; these reflect your complex personality. ".
		"For instance, you may be a wallflower in certain situations, but other circumstances might ".
		"bring out your chatty side. While your chart may provide helpful insights, it cannot predict the future. ".
		"You are ultimately the master of your own fate."
);

$this->Ln(2);

$this->IntroductionHeader( 'ABOUT THE AUTHOR' );
$this->IntroductionParagraph
(
	"This report is written by Adrian Ross Duncan, former editor of the Astrological Association Journal. ".
	"Adrian is author of \"Doing Time on Planet Earth\" (Element Books 1990) and \"Astrology: Transformation and Empowerment\" (Red Wheel/Weiser 2002). ".
	"His astrology software Horoscope Interpreter, Astrology for Lovers, and The Astrological Calendar, and much more, ".
	"are available from www.astrowow.com"
);


?>