<?php

    include "controller/IdentificationController.php";

    return [
        "Config" => [
            "ControllerDir" => "/controller"
        ],
        "Controllers" => [
            "Identification" => [
                "Methods" => [
                    "Register" => [
                        "ExternalAccess" => true
                    ]
                ],
                "Controller" => IdentificationController::class
            ]

        ]
    ];