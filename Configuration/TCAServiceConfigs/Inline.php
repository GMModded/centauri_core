<?php

return [
    "ctrl" => [
        "title"	=> "{TITLE_FIELD}",
        "label" => "{LABEL_FIELD}",
        "tstamp" => "tstamp",
        "crdate" => "crdate",
        "cruser_id" => "cruser_id",
        "versioningWS" => 1,
        "languageField" => "sys_language_uid",
        "transOrigPointerField" => "l10n_parent",
        "transOrigDiffSourceField" => "l10n_diffsource",
        "delete" => "deleted",
        "sortby" => "sorting",
        "enablecolumns" => [
            "disabled" => "hidden",
            "starttime" => "starttime",
            "endtime" => "endtime",
        ],
        "searchFields" => "{SEARCH_FIELDS}"
    ],
    "interface" => [
        "showRecordFieldList" => "sys_language_uid, l10n_diffsource, hidden, {SHOW_ITEMS}",
    ],
    "types" => [
        "1" => [
            "showitem" => "
                sys_language_uid,
                l10n_diffsource,
                hidden,
                {SHOW_ITEMS},

                --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access,
                    starttime,
                    endtime
                "
        ],
    ],
    "columns" => [
        "sys_language_uid" => [
            "exclude" => 1,
            "label" => "LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.language",
            "config" => [
                "type" => "select",
                "renderType" => "selectSingle",
                "special" => "languages",
                "items" => [
                    [
                        "LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.allLanguages",
                        -1,
                        "flags-multiple"
                    ],
                ],
                "default" => 0,
            ],
        ],
        "l10n_diffsource" => [
            "config" => [
                "type" => "passthrough",
            ],
        ],
        "t3ver_label" => [
            "label" => "LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.versionLabel",
            "config" => [
                "type" => "input",
                "size" => 30,
                "max" => 255,
            ],
        ],
        "hidden" => [
            "exclude" => 1,
            "label" => "LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.hidden",
            "config" => [
                "type" => "check",
                "items" => [
                    "1" => [
                        "0" => "LLL:EXT:core/locallang_core.xlf:labels.enabled"
                    ]
                ],
            ],
        ],
        "starttime" => [
            "exclude" => 1,
            "label" => "LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.starttime",
            "config" => [
                "type" => "input",
                "size" => 13,
                "default" => 0,
                "eval" => "datetime",
                "behaivour" => [
                    "allowLanguageSynchronization" => 1
                ]
            ]
        ],
        "endtime" => [
            "exclude" => 1,
            "label" => "LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.endtime",
            "config" => [
                "type" => "input",
                "size" => 13,
                "default" => 0,
                "eval" => "datetime",
                "range" => [
                    "upper" => mktime(0, 0, 0, 1, 1, 2040)
                ],
                "behaivour" => [
                    "allowLanguageSynchronization" => 1
                ]
            ],
        ],

        "parentid" => [
            "config" => [
                "type" => "select",
                "renderType" => "selectSingle",
                "items" => [
                    0 => [
                        0 => "",
                        1 => 0,
                    ],
                ],
                "foreign_table" => "tt_content",
                "foreign_table_where" => "AND tt_content.pid=###CURRENT_PID### AND tt_content.sys_language_uid IN (-1,###REC_FIELD_sys_language_uid###)",
            ],
        ],
        "parenttable" => [
            "config" => [
                "type" => "passthrough"
            ]
        ]
    ],
];

/** For backwards-compatibility */
if(\Smedia\SmediaCore\Utility\Typo3VersionUtility::getMajorVersion() == 8) {
    $ctrlArr["columns"]["sys_language_uid"]["label"] = "LLL:EXT:lang/locallang_general.xlf:LGL.language";
    $ctrlArr["columns"]["sys_language_uid"]["config"]["items"][0][0] = "LLL:EXT:lang/locallang_general.xlf:LGL.allLanguages";

    $ctrlArr["columns"]["hidden"]["label"] = "LLL:EXT:lang/locallang_general.xlf:LGL.hidden";
    $ctrlArr["columns"]["hidden"]["config"]["items"]["1"]["0"] = "LLL:EXT:lang/locallang_core.xlf:labels.enabled";

    $ctrlArr["columns"]["starttime"]["label"] = "LLL:EXT:lang/locallang_general.xlf:LGL.starttime";
    $ctrlArr["columns"]["endtime"]["label"] = "LLL:EXT:lang/locallang_general.xlf:LGL.endtime";
}

return $ctrlArr;
