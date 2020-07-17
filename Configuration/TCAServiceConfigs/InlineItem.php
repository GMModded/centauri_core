<?php

return [
    "exclude" => 1,
    "label" => "Inline-Item",

    "config" => [
        "type" => "inline",

        "foreign_table" => "{FOREIGN_TABLE}",
        "foreign_field" => "parentid",

        "appearance" => [
            "collapseAll" => 1,
            "levelLinksPosition" => "top",
            "showSynchronizationLink" => 1,
            "showPossibleLocalizationRecords" => 1,
            "showAllLocalizationLink" => 1,
            "newRecordLinkAddTitle" => 1,
            "useSortable" => 1,
            "enabledControls" => 1,
            "fileUploadAllowed" => 1,
            "headerThumbnail" => 1
        ],
    ]
];
