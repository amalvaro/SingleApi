<?php

    /*
        Method parameter collection:
        - "Type" => "number/text",
        - "Expression" => "",
        - "MinLength" => 0,
        - "MaxLength" => 0,
        - "Min" => 0,
        - "Max" => 0,
        - "Action" => function($value) { return true; }
    */

    return [
        "Config" => [
            "ControllerDir" => "./controller/"
        ],
        "Controllers" => [
            "IdentificationController" => [
                "Methods" => [
                    "Register" => [
                        "ExternalAccess" => true,
                        "ParamCount" => 2,
                        "ParamCollection" => [
                            0 => ["Type" => "text", "MinLength" => 5, "MaxLength" => 10]
                        ]
                    ]
                ],
            ]

        ]
    ];