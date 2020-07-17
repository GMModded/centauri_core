<?php

return [
    "exclude" => 1,
    "label" => "Slug",

    "config" => [
        "type" => "slug",

        "fallbackCharacter" => "-",
        "eval" => "uniqueInSite",

        "generatorOptions" => [
            "fieldSeparator" => "/",
            "prefixParentPageSlug" => true,

            "fields" => [
                "title",
                "nav_title"
            ],

            "replacements" => [
                "/" => ""
            ]
        ]
    ]
];
