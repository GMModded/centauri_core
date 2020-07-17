<?php
namespace Centauri\Core\Service;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;

class TCAService {
    /**
     * Path to the TCA Service Config files.
     * 
     * @var string
     */
    protected static $TCAServiceConfigs = __DIR__ . "/../../Configuration/TCAServiceConfigs";

    /**
     * Returns the array of a configured TCA type
     * 
     * @param string $type Image/Input/Select/... (TCA config-type)
     * @param string $fieldname Fieldname (column inside your table where it gets saved e.g. ce_header)
     * @param string $label Label over your field (e.g. Header)
     * @param $additionalConfig When overwriting the global configuration for extra values
     * 
     * @return boolean|array
     */
    public static function findConfigByType($type, $fieldname, $label = "", $additionalConfig = []) {
        $FlashUtility = GeneralUtility::makeInstance(\Centauri\Core\Utility\FlashUtility::class);

        $filepath = __DIR__ . "/../../Configuration/TCAServiceConfigs/$type.php";
        $realpath = realpath($filepath);

        $field = null;

        if(!realpath(self::$TCAServiceConfigs)) {
            $FlashUtility->message("TCAService - Missing Configs-Directory", "Following directory missing: " . self::$TCAServiceConfigs, 2);
            return;
        }

        if(!$realpath) {
            $FlashUtility->message("TCAService - Type: $type.php does not exists!", "Create this file in order to make it working!", 3);
        } else {
            $config = include $filepath;


            /** When Image has been requested - setting inside the config (only if it exists) the foreign_match_fields-fieldname to the given one */
            if($type == "Image") {
                $config["config"]["foreign_match_fields"]["fieldname"] ? $config["config"]["foreign_match_fields"]["fieldname"] = $fieldname : null;

                if(!empty($additionalConfig)) {
                    foreach($additionalConfig as $key => $value) {
                        $config["config"][$key] = $value;
                    }
                }
            }


            /** Replacing all necessary <{VARIABLES}> inside the Inline.php file with proper values given by $additionalConfig */
            if($type == "Inline") {
                $items = $additionalConfig["columns"];
                $nitems = "";

                if(!isset($items)) {
                    $FlashUtility->message("TCAService - Type: $type.php", "AdditionalConfig: 'columns' not found!", 2);
                    return;
                }

                /** Looping through $items for showitems for IRRE */
                $size = count($items);
                $i = 1;
                foreach($items as $item => $value) {
                    if($i == $size) {
                        $nitems .= $item;
                    } else {
                        $nitems .= $item . ", ";
                        $i++;
                    }
                }

                /** Overwriting placeholder variables inside the Inline.php file */
                $config["ctrl"]["title"] = $additionalConfig["title"];
                $config["ctrl"]["label"] = $additionalConfig["label"];
                $config["ctrl"]["searchFields"] = $nitems;

                $config["interface"]["showRecordFieldList"] = str_replace("{SHOW_ITEMS}", $nitems, $config["interface"]["showRecordFieldList"]);
                $config["types"]["1"]["showitem"] = str_replace("{SHOW_ITEMS}", $nitems, $config["types"]["1"]["showitem"]);

                foreach($additionalConfig["columns"] as $key => $column) {
                    $config["columns"][$key] = $column;
                }
            }


            /** If there's no foreign_table in $additionalConfig it will throw a FlashMessage - else overwriting it inside $config */
            if($type == "InlineItem") {
                if(!isset($additionalConfig["foreign_table"])) {
                    $FlashUtility->message("TCAService - Type: $type.php", "AdditionalConfig missing 'foreign_table' value!", 2);
                    return;
                } else {
                    $config["config"]["foreign_table"] = $additionalConfig["foreign_table"] ?? "";
                }
            }


            /** Slug fields */
            if($type == "Slug") {
                if(!isset($additionalConfig["generatorOptions"]["fields"])) {
                    $FlashUtility->message("TCAService - Type: $type.php", "AdditionalConfig missing 'generatorOptions[fields]' for Slug-field as value!", 2);
                    return;
                } else {
                    $config["config"]["generatorOptions"]["fields"] = $additionalConfig["generatorOptions"]["fields"] ?? "";
                }
            }


            /** Setting the key of $field to the configured array (which has been before manipulated) */
            $field[$fieldname] = $config;

            /** Label if there's no given when calling this static method, it will use the one inside the config file itself */
            if($label != "") $field[$fieldname]["label"] = $label;

            /** And in addition looping $additionalConfig and setting key => value to the one for the field */
            if(!empty($additionalConfig)) {
                foreach($additionalConfig as $a => $b) {
                    $field[$fieldname]["config"][$a] = $b;
                }
            }
        }

        return $field[$fieldname];
    }

    /**
     * Adds our content elements (inside BE as CType) to the select dropdown.
     * 
     * @param array $CTypes - e.g. ["Header Teaser Image" => ce_headerteaserimage]
     * @param boolean $backendPreviewHook Whether the given CType-values (e.g. ce_headerteaserimage) should also be registered into the backend preview hook.
     * 
     * @return void
     */
    public static function addSelectItems($CTypes, $backendPreviewHook = false) {
        foreach($CTypes as $key => $CType) {
            \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTcaSelectItem(
                "tt_content",
                "CType",
                [
                    $key,
                    $CType
                ],
                "--div--",
                "after"
            );

            if($backendPreviewHook) {
                $GLOBALS["TYPO3_CONF_VARS"]["SC_OPTIONS"]["cms/layout/class.tx_cms_layout.php"]["tt_content_drawItem"][$CType] = "Centauri\\Core\\Hook\\BackendPreviewHook";
            }
        }
    }

    /**
     * Shows the backend fields when creating a new record.
     * 
     * @param string $CType (e.g. ce_headerteaserimage or ce_slider)
     * @param string $fields (e.g. ce_header;Header,ce_rte;RTE,)
     * 
     * @return void
     */
    public static function showFields($CType, $fields) {
        $showitem =
            "--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.general;general,
                --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xml:palette.header;header,

                " . $fields . "
                    parentid;,
                    parenttable;,
                " . "
            --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
                --palette--;;language,
            "
        ;

        $GLOBALS["TCA"]["tt_content"]["types"][$CType]["showitem"] = $showitem;
    }

    /**
     * Overrides columns which has been defined inside tt_content.php (e.g. ce_select["config"]["items"].
     * 
     * @param string $CType Self-explaning but could be e.g. "ce_header.
     * @param string $overrides The string of the column overrides.
     * 
     * @return void
     */
    public static function columnsOverridesField($CType, $overrides) {
        $GLOBALS["TCA"]["tt_content"]["types"][$CType]["columnsOverrides"] = $overrides;
    }

    /**
     * Custom way of registration tt_content palettes.
     * 
     * @param string $palette Unique name of your palette.
     * @param string $fields E.g. "ce_header;Label,ce_rte;RTE," etc.
     * @param array $additionalArr In case of use for additional values.
     * 
     * @return void
     */
    public static function registerPalette($palette, $fields, $additionalArr = []) {
        $array = [
            "showitem" => $fields,
            "canNotCollapse" => "1"
        ];

        if(!empty($additionalArr)) {
            $array = array_merge($additionalArr);
        }

        $GLOBALS["TCA"]["tt_content"]["palettes"][$palette] = $array;
    }

    /**
     * Returns you the page contents of a domain model by its pid, the given column name and the value for the column in tt_content.
     * 
     * @param string|int $pid PageID
     * @param string $column table-column field
     * @param string $value value of the given $column-field
     * 
     * @return \Doctrine\DBAL\Driver\Statement
     */
    public function findPageContentBy($pid, $column, $value) {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable("tt_content");

        return $queryBuilder
            ->select("*")
            ->from("tt_content")
            ->where(
                $queryBuilder->expr()->eq("pid", $queryBuilder->createNamedParameter($pid)),
                $queryBuilder->expr()->eq($column, $queryBuilder->createNamedParameter($value))
            )
        ->execute();
    }
}
