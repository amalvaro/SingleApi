<?php

    /*
        Method parameter collection:
        - "Type" => "number/text",
        - "Expression" => "",
        - "MinLength" => 0,
        - "MaxLength" => 0,
        - "Min" => 0,
        - "Max" => 0,
        - "Action" => function($value) { return custom_condition($value) == true; }
    */

    return [
        "Config" => [
            "ControllerDir" => "./controller/"
        ],
        "Controllers" => [
            "IdentificationController" => include_once "routes/IdentificationRouteParams.php"
        ]
    ];