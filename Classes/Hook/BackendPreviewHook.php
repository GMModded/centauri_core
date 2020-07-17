<?php
namespace Centauri\Core\Hook;

use Centauri\Core\Utility\FlashUtility;
use TYPO3\CMS\Backend\View\PageLayoutView;
use TYPO3\CMS\Backend\View\PageLayoutViewDrawItemHookInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class BackendPreviewHook implements PageLayoutViewDrawItemHookInterface {
    /**
     * Main extension where to find templates.
     * 
     * @var string
     */
    protected $mainEXT = "centauri_backend";

    /**
     * Arrays of CTypes from elements which - used for template names.
     * 
     * @var array
     */
    protected $CTypes = [
        "ce_headerdescription",
        "grid_container"
    ];

    /**
     * Inline-Records to automatically fetch them - useful for also a preview of those e.g. slider-items etc.
     * 
     * @var array
     */
    protected $IRREs = [
        // Tablename => Fieldname
        // "products" => "parentid",
        // "slideritems" => "parentid"
    ];

    /**
     * Determines whether to show the default header-palette value inside the backend preview or not.
     * 
     * @var boolean
     */
    protected $showHeaderFieldInPreview = false;

    /**
     * Image field names to automatically fetch and assign them to the given templates.
     * 
     * @var array
     */
    protected $imageFieldNames = [
        "ce_image"
    ];

    /**
     * Same as the $imageFieldNames just for Inline-Records.
     * 
     * @var array
     */
    protected $IRREsImageFieldNames = [
        "icon"
    ];

    /**
     * Rendering for custom content elements.
     *
     * @param PageLayoutView $parentObject
     * @param bool $drawItem
     * @param string $headerContent
     * @param string $itemContent
     * @param array $row
     * 
     * @return void
     */
    public function preProcess(PageLayoutView &$parentObject, &$drawItem, &$headerContent, &$itemContent, array &$row) {
        $CType = $row["CType"];

        if(!in_array($CType, $this->CTypes)) {
            return;
        }

        $templatesPath = "EXT:" . $this->mainEXT . "/Resources/Private/Backend/Templates";
        $layoutsPath = "EXT:" . $this->mainEXT . "/Resources/Private/Backend/Layouts";
        $partialsPath = "EXT:" . $this->mainEXT . "/Resources/Private/Backend/Partials";

        $templateFile = ucfirst(str_replace("ce_", "", $CType));

        $file = GeneralUtility::getFileAbsFileName($templatesPath . "/$templateFile.html");

        if(!file_exists($file)) {
            $flashUtility = new FlashUtility;
            $flashUtility->message("BackendPreviewHook - EXT:centauri_core", "Backend-Template: $file does not exists!", 1);
            return;
        }

        $drawItem = false;

        if($this->showHeaderFieldInPreview) {
            if(isset($row["header"]) && !empty($row["header"])) {
                $headerContent = "<strong>" . htmlspecialchars($row["header"]) . "</strong><br />";
            }
        }

        // Fetching all Files by its uid of this $CType
        $files = [];

        foreach($this->imageFieldNames as $fieldName) {
            $files[$fieldName] = \Centauri\Core\Utility\FileUtility::findFilesBy($row["uid"], "tt_content", $fieldName);
        }

        $row["files"] = $files;

        // Fetching all IRREs if exists of this $CType
        $IRREs = [];
        foreach($this->IRREs as $tableName => $fieldName) {
            $IRREs[$tableName] = \Centauri\Core\Utility\IRREUtility::findByUid($row["uid"], $tableName, $fieldName)->fetchAll();
        }
        foreach($IRREs as $tableName => $items) {
            $itemFiles = [];

            foreach($items as $index => $item) {
                foreach($this->IRREsImageFieldNames as $IRREFieldName) {
                    $itemFiles = \Centauri\Core\Utility\IRREUtility::findByUid($item["uid"], $tableName, $IRREFieldName)->fetchAll();

                    if(count($itemFiles) == 1) {
                        $IRREs[$tableName][$index][$IRREFieldName] = $itemFiles[0];
                    } else {
                        $IRREs[$tableName][$index][$IRREFieldName] = $itemFiles;
                    }
                }
            }
        }

        $row["IRREs"] = $IRREs;

        $rendered = \Centauri\Core\Utility\StandaloneViewUtility::render(
            [
                "Templates" => $templatesPath,
                "Layouts" => $layoutsPath,
                "Partials" => $partialsPath
            ],

            $templateFile,

            [
                "row" => $row
            ],

            true
        );

        // Rendern
        $itemContent = $parentObject->linkEditContent($rendered, $row);
    }
}
