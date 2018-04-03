<?php
// error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
// ini_set("display_errors", 1);

error_reporting(0);
@ini_set("display_errors", 0);

try {
    if(!defined('ROOTPATH')) {
        define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/');

        if(!require_once(ROOTPATH."config.php")) {
            //throw new Exception("Failed to include 'userRepository.php'");
            require_once("config.php");
        }

        if(!include(ROOTPATH."/library/db.php")) {
            //throw new Exception("Failed to include 'userRepository.php'");
            require_once("library/db.php");
        }
        //$test = new DB();
    }
    if (!defined('BASEURL')) {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
        define("BASEURL", $protocol . $_SERVER['SERVER_NAME']. "/");
    }

    /*if (!defined('CONFIG_INCLUDED')) {

        if(!require_once(ROOTPATH."/vendor/Horoscope-wheel/config.php")) { 
            //throw new Exception("Failed to include 'userRepository.php'");
            require_once("config.php");
        }
    } */

    require_once(CLASSPATH."/PDO.config.inc.php");
    
    if(!class_exists('DB')) {
        //echo 'class not exist';
        require_once(LIBRARYPATH."/db.php");
    }

    if (!class_exists('cDatabase')) {
        require_once(DALPATH."/cDatabase.php");
    }

    if (!class_exists('ProductRepository')) {
        require_once(DALPATH."/productRepository.php");
    }

    if (!class_exists('OrderRepository')) {
        require_once(DALPATH."/orderRepository.php");
    }

    if (!class_exists('UserRepository')) {
        require_once(DALPATH."/userRepository.php");
    }

    if (!class_exists('ACSRepository')) {
        require_once(DALPATH."/acsRepository.php");
    }

    if (!class_exists('LanguageRepository')) {
        require_once(DALPATH."/languageRepository.php");
    }

    if (!class_exists('genericMail')) {
        require_once(HELPERPATH."/mail/genericMail.php");
    }

    if (!class_exists('Subscription')) {
        require_once(BALPATH."/subscription.php");
    }

    if (!class_exists('AcsTimetables')) {
        require_once(CLASSPATH."/acs/class.acs.timetables.php");
    }

    if (!class_exists('emailTemplate')) {
        require_once(BALPATH."/emailTemplate.php");
    }

    if (!class_exists('EmailTemplateRepository')) {
        require_once(DALPATH."/emailTemplateRepository.php");
    }
    
}
catch(Exception $ex) {
    throw $ex;
}

function CalculateSunsignFromDate($month,$day) {
    //echo "<pre> " . $day . " - " . $month .  " </pre>";
    if(!empty($month) && !empty($day)) {
        if(($month==1 && $day>=20)||($month==2 && $day<=18)) {
            //echo "<pre> " . $day . " - " . $month .  " = 11 </pre>";
            return "11"; // "Aquarius";
        }
        else if(($month==2 && $day>=19 )||($month==3 && $day<=20)) {
            //echo "<pre> " . $day . " - " . $month .  " = 12 </pre>";
            return "12"; // "Pisces";
        }
        else if(($month==3 && $day>=21)||($month==4 && $day<=19)) {
            //echo "<pre> " . $day . " - " . $month .  " = 1 </pre>";
            return "1";  // "Aries";
        }
        else if(($month==4 && $day>=20)||($month==5 && $day<=20)) {
            //echo "<pre> " . $day . " - " . $month .  " = 2 </pre>";
            return "2";  // "Taurus";
        }
        else if(($month==5 && $day>=21)||($month==6 && $day<=20)) {
            //echo "<pre> " . $day . " - " . $month .  " = 3 </pre>";
            return "3";  // "Gemini";
        }
        else if(($month==6 && $day>=21)||($month==7 && $day<=22)) {
            //echo "<pre> " . $day . " - " . $month .  " = 4 </pre>";
            return "4";  // "Cancer";
        }
        else if(($month==7 && $day>=23)||($month==8 && $day<=22)) {
            //echo "<pre> " . $day . " - " . $month .  " = 5 </pre>";
            return "5";  // "Leo";
        }
        else if(($month==8 && $day>=23)||($month==9 && $day<=22)) {
            //echo "<pre> " . $day . " - " . $month .  " = 6 </pre>";
            return "6";  // "Virgo";
        }
        else if(($month==9 && $day>=23)||($month==10 && $day<=22)) {
            //echo "<pre> " . $day . " - " . $month .  " = 7 </pre>";
            return "7";  // "Libra";
        }
        else if(($month==10 && $day>23)||($month==11 && $day<=21)) {
            //echo "<pre> " . $day . " - " . $month .  " = 8 </pre>";
            return "8";  // "Scorpio";
        }
        else if(($month==11 && $day>=22)||($month==12 && $day<=21)) {
            //echo "<pre> " . $day . " - " . $month .  " = 9 </pre>";
            return "9";  // "Sagittarius";
        }
        else if(($month==12 && $day>=22)||($month==1 && $day<=19)) {
            //echo "<pre> " . $day . " - " . $month .  " = 10 </pre>";
            return "10";  // "Capricorn";
        }
    }
    else {
        return 1;
    }
}
?>
