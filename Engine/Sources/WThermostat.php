<?php

    namespace Engine\Sources;

    use Engine\Device;
    use Engine\Helper;

    class WThermostat
    {
        public function receive($topic, $content)
        {
            [$sourceType, $uid, $command] = explode('/', $topic, 3);
            if (preg_match('/^\{/', $content)) {
                $content = json_decode($content, true);
            }

            $device = $this->getDevice($uid);
            if ($device) {
                $command = str_replace('/', '-', $command);
                $fName = __DIR__ . '/WThermostat/' . $command . '.php';
                if (is_readable($fName)) {
                    if ($deviceType = $device->getType()) {
                        $deviceType->input(include $fName);
                    }
                } else {
                    print "Unknown request: " . $command . ' [' . json_encode($content) . ']' . PHP_EOL;
                }
            } else {
                print "Unknown device: " . $uid . ' [' . json_encode($content) . ']' . PHP_EOL;
            }
        }

        private static function getDevice($uid)
        {
            $devices = Device::create($uid);
            return $devices ? array_shift($devices) : null;
        }

    }