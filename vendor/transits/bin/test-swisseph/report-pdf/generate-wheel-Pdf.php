<?php
/**
 * @name:       Generate-Wheel-PDF
 * @version:    1.0 
 * 
 * @author:     Amit Parmar <parmaramit1111@gmail.com>
 * @package:    Generate-wheel-pdf.php
 * @category:   Generic Class for generating Wheel
 * 
 * @copyright: Amit Parmar and World-of-wisdom ( 2010-11 )
 *   
 * Description: Requirement for Drawing wheel
 * 
 * Step 1: Set size of the wheel
 * Step 2: Set Wheel color
 * Step 3: Set the planet, langiture and cups array
 * 
 *      NOTE: Approch to divide wheel drawing into small functions
 * Step 4: First draw outer wheel ( Zodia sign )
 * Step 5: Second draw inner house cups and Asc, MC etc,.
 * Step 6: Third draw inner house cups number wheel
 * Step 7: Now draw aspect lines and planet position 
 * 
 */
class GenerateWheelPDF extends CommonPDFHelper {

    //Region: For private variables [Start]
    /**
     * @var int
     */
    private $PageLeftMaring = 20;

    /**
     *
     * @var int 
     */
    private $PageTopMaring = 20;

    /**
     * $FontNameForText
     * @var String [Font name | Default = arial ]
     */
    private $FontNameForText = 'arial';

    /**
     * $FontWeightForText
     * @var String [B=Bold, I=Italic, U=Underline] 
     */
    private $FontWeightForText = '';

    /**
     * $FontSizeForText
     * @var int 
     */
    private $FontSizeForText = 10;

    /**
     * $FontFamilyOfGlyph
     * @var string [For Symbol created by Adrian sir ] 
     * [Default set to wows]
     */
    private $FontFamilyOfGlyph = 'wows';

    /**
     *  $FontWeightForGlyph
     * @var String [B=Bold, I=Italic, U=Underline] 
     */
    private $FontWeightForGlyph = '';

    /**
     * $FontSizeForGlyph
     * @var int 
     */
    private $FontSizeForGlyph = 10;

    //Variables for Zodia wheel [Start]

    /**
     * $WheelSizeInRadius
     * @var int
     */
    private $WheelSizeInRadius;

    /**
     * $WheelOffSetSize
     * @var int
     */
    private $WheelOffSetSize;

    /**
     * $WheelPositionLeft_X
     *  X possition
     * @var int 
     */
    private $WheelPositionLeft_X;

    /**
     * $WheelPositionTop_Y;
     *  Y possition
     * @var int
     */
    private $WheelPositionTop_Y;

    /**
     * $ZodiacCupsSizeOuter
     *  Managing outer line drawing
     *  Zodia cups
     * @var type 
     */
    private $ZodiacCupsSizeOuter;

    /**
     * $ZodiacCupsSizeInner
     *  Managing inner line drawing
     *  Zodia cups
     * @var type 
     */
    private $ZodiacCupsSizeInner;

    /**
     * $ZodiacCupsLayout;
     * @var array of color code
     */
    private $ZodiacCupsLayout = array(0xFF, 0xFF, 0xE7);

    /**
     *
     * @var array [hold the segment color for Zodia wheel] 
     */
    private $ZodiacCupsColours;

    //Variables for Zodia wheel [End]
    //Region: For private variables [End]    

    function GenerateWheelPDF() {
        //$this->AddFont('wows', '', 'wows.php');            // text
        //$this->AddFont('ww_rv1', '', 'ww_rv1.php');        // graphics
        //$this->AddFont('taurus', '', 'taurus.php');
        //$this->AddFont('calligraphic421', '', 'Calligraphic421.php');
        //$this->SetDisplayMode('fullpage');
        //$this->SetAutoPageBreak(false);
//        $this->FontNameForText = 'arial';
//        $this->FontWeightForText = '';
//        $this->FontSizeForText = 10;
//
//        $this->FontFamilyOfGlyph = 'wows';
//        $this->FontWeightForGlyph = '';
//        $this->FontSizeForGlyph = 10;
//
//        $this->WheelSizeInRadius = 50;
//        $this->WheelPositionLeft_X = 150;
//        $this->WheelPositionTop_Y = 237;

        /* 0 = Aries | 1 = Taurus | 2 = Gemini | 3 = Cancer | 4 = Leo | 5 = Virgo 
         * 6 = Libra | 7 = Scorpio | 8 = Sagittarius | 9 = Capricorn | 10 = Aquarius | 11 Picses
         */
//        $this->ZodiacCupsColours = array(
//                        array(0xFF, 0x00, 0x00), array(0xFF, 0x84, 0x00), array(0xFF, 0xFF, 0x00),
//                        array(0x84, 0xFF, 0x00), array(0x00, 0xFF, 0x00), array(0x00, 0xFF, 0x84),
//                        array(0x00, 0xFF, 0xFF), array(0x00, 0x84, 0xFF), array(0x00, 0x00, 0xFF),
//                        array(0x84, 0x00, 0xFF), array(0xFF, 0x00, 0xFF), array(0xFF, 0x00, 0x84));
//        $this->AddPage();
//        
//        for ($indexCusp = 0; $indexCusp < 12; $indexCusp++) {
//            $angle = ($indexCusp * 30.0);
//            
//            $this->SetFillColor(
//                    $this->ZodiacCupsColours[$indexCusp][0], 
//                    $this->ZodiacCupsColours[$indexCusp][1], 
//                    $this->ZodiacCupsColours[$indexCusp][2]);
//
//            //($Center, $Radius, $StartAngle, $EndAngle, $DrawStyle, $Direction, $Origin)
//            $this->Sector(
//                    $this->WheelPositionLeft_X, $this->WheelPositionTop_Y,
//                    $this->ZodiacCupsSizeOuter,
//                    $angle,
//                    fmod(($angle + 30.0), 360.0),
//                    'F',
//                    false,
//                    fmod((180.0 - $this->WheelOffSetSize), 360.0));
//        }
    }

    public function GenerateZodiacWheel() {
        $this->AddPage();

        $degRad = (double) ((pi() * 2.0) / 360.0);

        //Now draw Zodia cups with color
        for ($indexCusp = 0; $indexCusp < 12; $indexCusp++) {
            $angle = ($indexCusp * 30.0);

            $this->SetFillColor(
                    $this->ZodiacCupsColours[$indexCusp][0], $this->ZodiacCupsColours[$indexCusp][1], $this->ZodiacCupsColours[$indexCusp][2]);

            //($Center, $Radius, $StartAngle, $EndAngle, $DrawStyle, $Direction, $Origin)
            $this->Sector(
                    /* centre */ $this->WheelPositionLeft_X, $this->WheelPositionTop_Y,
                    /* radius */ $this->ZodiacCupsSizeOuter,
                    /* start */ $angle,
                    /* end */ fmod(($angle + 30.0), 360.0),
                    /* style */ 'F',
                    /* direction */ false,
                    /* origin */ fmod((180.0 - $this->WheelOffSetSize), 360.0)
            );
        }

        //$this->SetFillColor(0xFF, 0xFF, 0xFF);
        $this->SetFillColor(0, 0, 0);

        $this->Ellipse($this->WheelPositionLeft_X, $this->WheelPositionTop_Y, $this->ZodiacCupsSizeOuter, $this->ZodiacCupsSizeOuter, 'D');

        $this->SetFillColor($this->ZodiacCupsLayout[0], $this->ZodiacCupsLayout[1], $this->ZodiacCupsLayout[2]);

        $this->Ellipse($this->WheelPositionLeft_X, $this->WheelPositionTop_Y, $this->ZodiacCupsSizeInner, $this->ZodiacCupsSizeInner, 'DF');
    }

}
?>