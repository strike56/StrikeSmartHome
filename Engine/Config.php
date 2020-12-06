<?php

    namespace Engine;

    class Config {

        public static function load()
        {
            return [
                'db' => [
                    'dbname' => 'home',
                    'user' => 'root',
                    'password' => 'spstilsm',
                    'host' => 'localhost',
                    'driver' => 'pdo_mysql',
                ],
                'cache' => 'redis://localhost',
                'WebSocket' => [
                    'url' => 'websocket://0.0.0.0:9201',
                    'ssl' => [
                        'local_cert'  => __DIR__.'/../keys/server.sert',
                        'local_pk'    => __DIR__.'/../keys/server.key',
                        'verify_peer' => false,
                    ],
                ],
                'OneWire' => [
                    'url' => 'ow-stream://192.168.0.2:3000',
                ]
            ];
        }

        public static function get(... $val)
        {
            static $values;
            if (!$values) $values = static::load();

            $vals = $values;
            foreach($val as $key) {
                if (!isset($vals[$key])) return null;
                $vals = $vals[$key];
            }

            return $vals;

        }
    }