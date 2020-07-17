<?php

use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

if(!function_exists("dd")) {
    /**
     * dd function
     * 
     * @param $value
     * @param boolean $debug
     * @param boolean $die
     * 
     * @return void
     */
    function dd($value = null, $debug = false, $die = true)
    {
        if($debug) {
            debug($value);
        } else {
            DebuggerUtility::var_dump($value);
        }

        if($die) {
            die;
        }
    }
}

if(!function_exists("dump")) {
    /**
     * dump function
     * 
     * @param $value
     * @param boolean $debug
     * 
     * @return void
     */
    function dump($value = null, $debug = false)
    {
        if($debug) {
            debug($value);
        } else {
            DebuggerUtility::var_dump($value);
        }
    }
}
