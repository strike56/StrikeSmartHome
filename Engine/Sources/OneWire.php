<?php

    namespace Engine\Sources;

    use Engine\Device;

    class OneWire {

        public function receive($uid)
        {
            $device = static::getDevice($uid);
        }

        private static function getDevice($uid)
        {
            $uid = str_replace('.', '-', $uid);
            $devices = Device::create($uid);
            return $devices ? array_shift($devices) : null;
        }


    }
