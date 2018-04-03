<?php

/**
 * AstrologCalculator
 *
 * The calculation engine is built on a wrapper around
 * 'Swiss Ephemeris' program.
 *
 *
 * @package AstrologCalculator 
 *
 * @author Amit Parmar <amit.parmar@n-techcorporate.com>
 * @copyright Copyright (c) 2011-2012 Amit Parmar
 * @version 1.0
 */
class AstrologCalculator {

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
     * AstrologCalculator
     *
     * This is the class constructor
     */
    function AstrologCalculator() {
        global $logger;
        $logger->debug("AstrologCalculator::AstrologCalculator");
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
    function addArgument($arg) {
        global $logger;
        $logger->debug("AstrologCalculator::addArgument($arg)");
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
        global $logger;
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
    function resetError() {
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
        global $logger;
        $this->m_error_message = $message;
        $logger->debug("AstrologCalculator::SetError - $message");
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
        global $logger;
        $logger->debug("AstrologCalculator::callAstrolog()");
        $this->m_cache = '';
        
        //$cmd = '/home/29078/users/.home/domains/world-of-wisdom.com/bin/astrolog ' . $this->m_args . ' -YQ 0';
        //$cmd = '/home/29078/users/.home/domains/world-of-wisdom.com/bin/astrolog ' . $this->m_args . ' -YQ 0';
        /* Local */
        if($GLOBALS['IsLocal'] == 'No') {
             //$cmd = ROOTPATH . '/bin/astrolog ' . $this->m_args . ' -YQ 0';
             //$cmd = ROOTPATH . '/a541/astrolog ' . $this->m_args . ' -YQ 0';
             $cmd = ' /usr/bin/astrolog ' . $this->m_args . ' -YQ 0';
        }
        else {
            /*SERVER */
            //$cmd = ROOTPATH . '/a541/astrolog ' . $this->m_args . ' -YQ 0';
            $cmd = '/var/www/vhosts/world-of-wisdom.com/httpdocs/a541/astrolog ' . $this->m_args . ' -YQ 0';
            $cmd = ' /usr/bin/astrolog ' . $this->m_args . ' -YQ 0';            
        }
        
        $logger->debug("AstrologCalculator::execute: cmd = " . $cmd);                
        
        if (($handle = popen($cmd, 'r')) === false) {
            $logger->debug("AstrologCalculator::execute: unable to open pipe, aborting");
            die('unable to open pipe');
        } else {
            $logger->debug("AstrologCalculator::execute: pipe open, handle=$handle");            
            if (feof($handle)) {
                $logger->debug("AstrologCalculator::execute: feof returns true");
            }
        }
        while (!feof($handle)) {
            $this->m_cache .= fgets($handle, 256);            
        }        
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
    function getCache() {
        global $logger;
        $logger->debug("AstrologCalculator::getCache()");
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

}
?>