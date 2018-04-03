<?php
//required_once (INCLUDEPATH.'/functions.php');
//require_once (INCLUDEPATH . '/functions.php');
/**
 * DailyPersonalInvocationPipe
 *
 * The calculation engine is built on a wrapper around Walter Pullen's freeware
 * 'astrolog' program. This class provides a rich set of interfaces to astrolog
 * that can be consumed by other processes.
 *
 * Feature List
 * <ul>
 * </ul>
 *
 * @package AstrologAPI
 * @subpackage LowLevelAPI
 *
 * @author Andy Gray <parmaramit1111@gmail.com>
 * @copyright Copyright (c) 2011-2012 Amit Parmar and World of wisdom Inc.,
 * @version 1.0
 */
class DailyPersonalInvocationPipe {

    /**
     * Internal queue used to build up the parameter list for the call to Astrolog
     *
     * @access private
     * @var array
     */
    var $m_args;

    /**
     * Internal buffer used to retain the result of the call to Astrolog
     *
     * @access private
     * @var string
     */
    var $m_cache;

    /**
     * Internal error message
     *
     * @access private
     * @var string
     */
    var $m_error_message;

    /**
     * AstrologInvocationPipe
     *
     * This is the class constructor
     */
    /*function DailyPersonalInvocationPipe() {
    }*/
    function __construct() {
    }

    /**
     * AddArgument
     *
     * Add an argument to the parameter queue
     *
     * @todo convert to private internal method
     * @todo add return value (bool)
     * @todo set error state
     *
     * @access private
     * @param mixed argumentstring to be added
     * @return void nothing is returned
     */
    function addArgument( $arg ) {
        $this->m_args .= ' ' . trim($arg);
        return true;
    }

    /**
     * SetHouseSystem
     *
     * Define the house system to use for the calculation
     *
     * @access public
     * @param int $hs House System enumeration
     * @return bool true if valid else false with an error set, see error for more details
     */
    function SetHouseSystem( $hs ) {
        $iHs = intval($hs);
        if ( ($iHs < 1) || ($iHs > 14) ) {
            $this->SetError("Invalid house system");
            return false;
        }
        $this->addArgument( sprintf("-c %d", $iHs) );
        return true;
    }

    /**
     * ResetError
     *
     * Reset the error message
     */
    function resetError() {
        $this->SetError( "OK" );
    }

    /**
     * SetError
     *
     * Set an error message that can be queried by calling functions
     *
     * @param string $error Message indicating error cause
     * @return void None
     */
    function SetError( $message ) {
        $this->m_error_message = $message;
    }

    /**
     * CallAstrolog
     *
     * This function performs the call to Astrolog via a Unix OS pipe.
     * The function builds up the command string including all required
     * parameters taken from the local argument array and feeds that through the
     * pipe.
     *
     * It is important to ensure that this call is adequately protected as it
     * presents for the possibility of a serious security leak that can lead to
     * injection of content that can be passed down the pipe. It also requires
     * the PHP safe mode to be disabled.
     *
     * @todo convert to private internal method
     * @todo add return value (bool)
     * @todo set error state
     *
     * @access protected
     * @return bool returns true else false with an error set, see error for more details
     */
    function callAstrolog() {
        $this->m_cache = '';
        //echo INCLUDEPATH.'/functions.php'; die;
        
        //LOCAL TESTING
        //$cmd = ROOTPATH . 'a541/astrolog ' . $this->m_args . ' -YQ 0';


        $getVar = urlencode($this->m_args." -YQ 0");

        //$this->m_cache = file_get_contents("http://54.153.95.173/astro.php?q=".$getVar); //file_get_contents("http://54.67.50.240/astro.php?q=".$getVar);
        $this->m_cache = file_get_contents("http://52.52.17.200/astro.php?q=".$getVar); //file_get_contents("http://54.67.50.240/astro.php?q=".$getVar);
        //pr ($this->m_cache); die;
        //return $getOutput;


        //$this->m_cache = getAstrologData ($this->m_args);
        //SERVER
        //$cmd = '/var/www/vhosts/world-of-wisdom.com/httpdocs/a541/astrolog ' . $this->m_args . ' -YQ 0';
		//$cmd = $_SERVER['DOCUMENT_ROOT'] . '/a541/astrolog ' . $this->m_args . ' -YQ 0';
		/*$cmd = '/usr/local/astrolog/astrolog ' . $this->m_args . ' -YQ 0';		

        //$cmd = '/var/www/vhosts/world-of-wisdom.com/httpdocs/a541/astrolog -qb 2 6 1969 18.35 0:00 -1:00 12:10:00E 55:27:00N -R0 sun -RT0 sun -tr 2 2006 -sd';
        //$cmd = ROOTPATH . '/bin/astrolog ' . $this->m_args . ' -YQ 0';
        
        if( ($handle = popen( $cmd, 'r' )) === false ) {
            die('unable to open pipe');
        } else {
            if(feof($handle)) {

            }
        }
        while(!feof($handle)) {
            $this->m_cache .= fgets($handle,256);
        }*/
        /**
         * TODO
         * Manage pipe failure and empty cache
         */
        //pclose($handle);
    }

    /**
     * GetCache
     *
     * Return the cache where the result of the call to the platform call to Astrolog is buffered
     *
     * @return mixed Cache contents
     */
    function getCache() {
        return $this->m_cache;
    }

    /**
     * GetErrorMessage
     *
     * Return a descriptive error message
     *
     * @access public
     * @return string Descriptive error message
     */
    function getErrorMessage() {
        return $this->m_error_message;
    }

    /**
     * TearDown
     *
     * Destructor method.
     * This releases any resources held by this function
     */
    function teardown() {
        unset($this);
    }
};
?>