<?php

    /* @var array $content */

    $upTime = $content['uptime'] ?? false;
    if ($upTime) {
        $dtF = new \DateTime('@0');
        $dtT = new \DateTime("@".$upTime);
        $upTime = [
            "days" => $dtF->diff($dtT)->format('%a'),
            "hours" => $dtF->diff($dtT)->format('%h'),
            "minutes" => $dtF->diff($dtT)->format('%i'),
            "seconds" => $dtF->diff($dtT)->format('%s'),
            'total' => $upTime,
        ];
    }
    $data['currentState'] = [
        "ntpServer" => $content["ntpServer"] ?? false,
        'time' => $content["epochTimeLocalFormatted"] ?? false,
        'upTime' => $upTime,
    ];

    return $data;