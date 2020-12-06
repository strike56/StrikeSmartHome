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