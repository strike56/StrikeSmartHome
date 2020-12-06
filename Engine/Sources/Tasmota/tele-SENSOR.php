<?php

    /* @var array $content */

    $data = ['sensor' => []];

    if (!empty($content['ENERGY'])) {
        $data['sensor']['energy'] = [
            'power' => $content['ENERGY']['Power'] ?? false,
            'apparentPower' => $content['ENERGY']['ApparentPower'] ?? false,
            'reactivePower' => $content['ENERGY']['ReactivePower'] ?? false,
            'factor' => $content['ENERGY']['Factor'] ?? false,
            'voltage' => $content['ENERGY']['Voltage'] ?? false,
            'current' => $content['ENERGY']['Current'] ?? false,
        ];
        $data['sensor']['energyHistory'] = [
            'startTime' => $content['ENERGY']['TotalStartTime'] ?? false,
            'total' => $content['ENERGY']['Total'] ?? false,
            'today' => $content['ENERGY']['Today'] ?? false,
            'yesterday' => $content['ENERGY']['Yesterday'] ?? false,
            'period' => $content['ENERGY']['Period'] ?? false,
        ];
    }

    return $data;