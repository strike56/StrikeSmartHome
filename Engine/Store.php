<?php

    namespace Engine;

    class Store {

        private static array $devices = [];

        public static function getDevice($uid): array
        {
            if (!empty(static::$devices[$uid])) {
                return static::$devices[$uid];
            }
            return [];
        }

        public static function setDevice($uid, $data, $value = null)
        {
            if (is_string($data)) {
                $data = [ $data => $value];
            }
            if (empty(static::$devices[$uid])) {
                static::$devices[$uid] = [];
            }
            foreach($data as $k => $v) {
                static::$devices[$uid][$k] = $v;
            }
        }

    }
