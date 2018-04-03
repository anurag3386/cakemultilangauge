<?php

/**
 * SwissEphemerisInvocationPipe
 *
 * The calculation engine is built on a wrapper around
 * 'Swiss Ephemeris' program.
 *
 * Feature List
 * <ul></ul>
 *
 * @package SwissEphemerisInvocationPipe 
 *
 * @author Amit Parmar <amit.parmar@n-techcorporate.com>
 * @copyright Copyright (c) 2011-2012 Amit Parmar
 * @version 1.0
 */
class SwissEphemerisCalculator {
    //put your code here

    /**
     * Internal queue used to build up the parameter list for the call to Swiss Ephemeris
     *
     * @access private
     * @var array
     */
    var $m_args;
    /**
     * Internal buffer used to retain the result of the call to Swiss Ephemeris
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
     * Swiss EphemerisInvocationPipe
     *
     * This is the class constructor
     */
    function SwissEphemerisInvocationPipe() {
        global $reportLogger;
        $reportLogger->debug("SwissEphemerisInvocationPipe::SwissEphemerisInvocationPipe \n");
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
    function AddArgument($arg) {
        global $reportLogger;
        $reportLogger->debug("SwissEphemerisInvocationPipe::addArgument($arg) \n");
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
    function SetHouseSystem($hs) {
        global $reportLogger;
        $iHs = intval($hs);
        if (($iHs < 1) || ($iHs > 14)) {
            $this->SetError("Invalid house system");
            return false;
        }
        $this->addArgument(sprintf("-c %d", $iHs));
        return true;
    }

    /**
     * ResetError
     *
     * Reset the error message
     */
    function ResetError() {
        $this->SetError("OK");
    }

    /**
     * SetError
     *
     * Set an error message that can be queried by calling functions
     *
     * @param string $error Message indicating error cause
     * @return void None
     */
    function SetError($message) {
        global $reportLogger;
        $this->m_error_message = $message;
        $reportLogger->debug("SwissEphemerisInvocationPipe::SetError - $message \n");
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
    function RunSwissEphemeris() {
        
        global $reportLogger;
        $reportLogger->debug("SwissEphemerisInvocationPipe::RunSwissEphemeris() \n");
        $this->m_cache = '';        
        $out ='';
        //$cmd = '/www/wow-year/classes/swiss-ephemeris' . $this->m_args . ' -flsj -g, -head';         
        $cmd = ROOTPATH. '/bin/sweph/swetest ' . $this->m_args . ' ' . $out;

        $reportLogger->debug("SwissEphemerisInvocationPipe::execute: cmd = " . $cmd);

        if (($handle = popen($cmd, 'r')) === false) {
            $reportLogger->debug("SwissEphemerisInvocationPipe::execute: unable to open pipe, aborting");
            die('unable to open pipe');
        } else {

            $reportLogger->debug("SwissEphemerisInvocationPipe::execute: pipe open, handle=$handle");

            if (feof($handle)) {

                $reportLogger->debug("SwissEphemerisInvocationPipe::execute: feof returns true");
            }
        }
        echo '<br />---------------- <br />';
        echo '$out - ' . $out .' <br />';
        echo ' ---------------- <br />';
        while (!feof($handle)) {
            $this->m_cache .= fgets($handle, 256);
        }
        echo $this->m_cache;
        /*
         * TODO
         * Manage pipe failure and empty cache
         */
        pclose($handle);
    }

    /**
     * GetCache
     *
     * Return the cache where the result of the call to the platform call to Astrolog is buffered
     *
     * @return mixed Cache contents
     */
    function GetCache() {
        global $reportLogger;
        $reportLogger->debug("SwissEphemerisInvocationPipe::getCache() \n");
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
    function GetErrorMessage() {
        return $this->m_error_message;
    }

    /**
     * TearDown
     *
     * Destructor method.
     * This releases any resources held by this function
     */
    function Destroy() {
        unset($this);
    }

}
?>