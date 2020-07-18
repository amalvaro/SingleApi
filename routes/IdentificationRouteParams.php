<?php
    return [
        "Dependency" => [
            "UserRepository" => function() { return "first"; }
        ],
        "Methods" => [
            "Register" => [
                /* Dependencies append before method params */
                "Dependency" => [
                    "UserRepository" => function() { return "second"; }
                ],
                "ExternalAccess" => true,
                "ParamCount" => 2,
                "ParamCollection" => [
                    0 => ["Type" => "text", "MinLength" => 5, "MaxLength" => 10]
                ]
            ]
        ],
    ];