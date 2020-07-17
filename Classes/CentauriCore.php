<?php
namespace Centauri\Core;

use TYPO3\CMS\Core\Utility\GeneralUtility;

class CentauriCore
{
    /**
     * Core version of the system.
     * 
     * @var string
     */
    protected $version = "1.0";

    /** 
     * Absolute path from webserver to the extension itself - defined inside of the constructor.
     * 
     * @var string
     */
    protected $path = "";

    /**
     * Constructor for registering main functionality of this extension.
     * 
     * @return void
     */
    public function __construct()
    {
        $version = $this->version;
        // $this->checkUpdates($version);

        $this->path = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath("centauri_core");
        $this->loadAll();
    }

    /**
     * Loader for everything.
     * 
     * @return void
     */
    public function loadAll()
    {
        $path = $this->path;
        include $path . "Classes/Utility/DumpDieUtility.php";
    }

    /**
     * Returns the config file its content.
     * 
     * @return void
     */
    public function getConfig()
    {
        $configFile = GeneralUtility::getFileAbsFileName("EXT:centauri_core/Configuration/core.php");
        return (include $configFile);
    }

    private function checkUpdates($version)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => "TODO API URL FOR VERSION CHECK",
            CURLOPT_POST => 1
        ]);

        $latestversion = curl_exec($curl);
        curl_close($curl);

        $flashUtility = new \Centauri\Core\Utility\FlashUtility;

        if($version !== $latestversion) {
            return $flashUtility->message("Centauri Core (EXT:centauri_core) - Version", "Please update your current version ($version) up to the latest version ($latestversion)!", 1);
        } else {
            // return $flashUtility->message("Centauri Core", "Running latest version of Centauri Core.", 0);
        }

        return;
    }

    /**
     * Returns version of CentauriCore.
     * 
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }
}
