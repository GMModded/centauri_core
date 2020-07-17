<?php

return [
    "exclude" => 1,
    "label" => "Image",

    "config" => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig('{FIELDNAME}', [
        "minitems" => 0,
        "maxitems" => 1,

        "appearance" => [
            "createNewRelationLinkTitle" => "LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:images.addFileReference"
        ],

        /**
         * custom configuration for displaying fields in the overlay/reference table
         * to use the imageoverlayPalette instead of the basicoverlayPalette
         */
        "overrideChildTca" => [
            "types" => [
                "0" => [
                    "showitem" => "
                        --palette--;LLL:EXT:lang/Resources/Private/Language/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                        --palette--;;filePalette"
                ],
                \TYPO3\CMS\Core\Resource\File::FILETYPE_TEXT => [
                    "showitem" => "
                        --palette--;LLL:EXT:lang/Resources/Private/Language/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                        --palette--;;filePalette"
                ],
                \TYPO3\CMS\Core\Resource\File::FILETYPE_IMAGE => [
                    "showitem" => "
                        --palette--;LLL:EXT:lang/Resources/Private/Language/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                        --palette--;;filePalette"
                ],
                \TYPO3\CMS\Core\Resource\File::FILETYPE_AUDIO => [
                    "showitem" => "
                        --palette--;LLL:EXT:lang/Resources/Private/Language/locallang_tca.xlf:sys_file_reference.audioOverlayPalette;audioOverlayPalette,
                        --palette--;;filePalette"
                ],
                \TYPO3\CMS\Core\Resource\File::FILETYPE_VIDEO => [
                    "showitem" => "
                        --palette--;LLL:EXT:lang/Resources/Private/Language/locallang_tca.xlf:sys_file_reference.videoOverlayPalette;videoOverlayPalette,
                        --palette--;;filePalette"
                ],
                \TYPO3\CMS\Core\Resource\File::FILETYPE_APPLICATION => [
                    "showitem" => "
                        --palette--;LLL:EXT:lang/Resources/Private/Language/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                        --palette--;;filePalette"
                ]
            ],
        ],
    ])
];
