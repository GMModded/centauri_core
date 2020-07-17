<?php
defined("TYPO3_MODE") || die("Access denied.");

call_user_func(
    function()
    {
        $CentauriCore = new Centauri\Core\CentauriCore();
        // $CentauriCore->loadAll();

        \Centauri\Core\Service\ModelService::generate(
            /** EXT_KEY - The extension key itself */
            "centauri_core",

            // Namespace and Model Class Name
            "Centauri\\Core\\Domain\\Model",
            "Product",

            // Properties
            [
                "string" => [
                    "name" => "''",
                    "description" => "''"
                ],
    
                "\TYPO3\CMS\Extbase\Domain\Model\FileReference" => [
                    "image" => "null"
                ]
            ]
        );
    }
);
