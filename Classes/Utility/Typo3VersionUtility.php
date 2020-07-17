<?php
namespace Centauri\Core\Utility;

/**
 * Used to get the major version of the current used TYPO3 application.
 * 
 * @deprecated in TYPO3 9 and above.
 * @see \TYPO3\CMS\Core\Information\Typo3Version Class instead of this.
 * 
 * The core class itself has the following 2 constants:
 * - VERSION (e.g. 10.3.0-dev)
 * - BRANCH (e.g. 10.3)
 * 
 * As the following public member functions:
 * - getVersion() - returns the const VERSION.
 * - getMajorVersion() - returns actually the major version of getVersion by splitting it.
 */
class Typo3VersionUtility
{
    /**
     * This method will return the major version of this TYPO3 application.
     * 
     * @return int
     */
    public static function getMajorVersion()
    {
        $t3ver = TYPO3_version;

        if(strpos($t3ver, ".")) {
            $t3ver = explode(".", $t3ver)[0];
        }

        return (int) $t3ver;
    }
}
