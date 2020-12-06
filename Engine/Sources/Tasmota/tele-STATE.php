<?php

    $data = [];
    if (!empty($content['Wifi'])) {

        $downTime = $content['Wifi']['Downtime'] ?? false;
        if ($downTime) {
            if (preg_match('/^([0-9]+)T([0-9]+):([0-9]+):([0-9]+)$/', $downTime, $matches)) {
                $downTime = [
                    "days" => $matches[1],
                    "hours" => $matches[2],
                    "minutes" => $matches[3],
                    "seconds" => $matches[4],
                ];
            }
        }

        $data['WiFi'] = [
            'ap' => $content['Wifi']['AP'] === 1,
            'ssid' => $content['Wifi']['SSId'] ?? false,
            'mac' => $content['Wifi']['BSSId'] ?? false,
            'channel' => $content['Wifi']['Channel'] ?? false,
            'rssi' => $content['Wifi']['RSSI'] ?? false,
            'signal' => $content['Wifi']['Signal'] ?? false,
            'linkCount' => $content['Wifi']['LinkCount'] ?? 0,
            'downTime' => $downTime,
        ];
    }

    $currentTime = !empty($content['time']) ? str_replace('T', ' ', $content['time']) : false;
    $upTime = $content['Uptime'] ?? false;
    if ($upTime) {
        if (preg_match('/^([0-9]+)T([0-9]+):([0-9]+):([0-9]+)$/', $upTime, $matches)) {
            $upTime = [
                "days" => $matches[1],
                "hours" => $matches[2],
                "minutes" => $matches[3],
                "seconds" => $matches[4],
                'total' => $content['UptimeSec'] ?? false,
            ];
        }
    }
    $data['currentState'] = [
        'time' => $currentTime,
        'upTime' => $upTime,
        'heap' => $content['heap'] ?? false,
        'sleepMode' => $content['SleepMode'] ?? false,
        'sleep' => $content['Sleep'] ?? false,
        'loadAvg' => $content['LoadAvg'] ?? false,
    ];
    $data['mqtt'] = [
        'linkCount' => $content['MqttCount'] ?? false,
    ];

    return $data;