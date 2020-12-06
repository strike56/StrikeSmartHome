<?php

    /* @var array $content */

    $data = ['sensor' => [
        "thermostat" => [
            "switchBackToAuto" => $content["switchBackToAuto"] ?? false,
            "temperature" => $content["temperature"] ?? false,
            "targetTemperature" => $content["targetTemperature"] ?? false,
            "deviceOn" => $content["deviceOn"] ?? false,
            "schedulesMode" => $content["schedulesMode"] ?? false,
            "holdState" => $content["holdState"] ?? false,
            "ecoMode" => $content["ecoMode"] ?? false,
            "locked" => $content["locked"] ?? false,
            "mode" => $content["mode"] ?? false,
            "action" => $content["action"] ?? false,
            "state" => $content["state"] ?? false,
        ],
    ]];

    return $data;