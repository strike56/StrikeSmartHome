<?php

    $devices = [

        [
            "id" => "pult-openclose-1",
            "type" => "Remote",
            'source' => 'Tasmota',
            'inputDetector' => [
                [
                    "input" => [ "RfReceived" => [ "Data" => "B63614"] ],
                    "output" => [ "pulse" => 'A' ],
                ],
                [
                    "input" => [ "RfReceived" => [ "Data" => "B63618"] ],
                    "output" => [ "pulse" => 'C' ],
                ],
            ],
        ],

        [
            "id" => "pult-openclose-2",
            "type" => "Remote",
            "source" => "Tasmota",
            "inputDetector" => [
                [
                    "input" => [ "RfReceived" => [ "Data" => "B63611"] ],
                    "output" => [ "pulse" => 'B' ],
                ],
                [
                    "input" => [ "RfReceived" => [ "Data" => "B63612"] ],
                    "output" => [ "pulse" => 'D' ],
                ],
            ],
        ],

    ];
