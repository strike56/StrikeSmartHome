<?php

    /* @var array $content */
    /* @var object $device */

    $data = [];
    if (!empty($content['POWER'])) {
        $content = $content['POWER'];
        $data = array_merge($data, include __DIR__ . '/stat-POWER.php');
    }

    if (isset($content['Dimmer'])) {
        $data = array_merge($data, [
            'brightness' => $content['Dimmer'],
        ]);
    }

    if (!empty($device->getData()['output'])) {
        $data = array_merge($data, $device->getData()['output']);
    }

    return $data;