<?php

    namespace Engine\Sources;

    use Engine\Platforms\Mqtt;
    use Engine\Device;
    use Engine\Helper;

    class Tasmota {

        public function receive($topic, $content) {
            [$sourceType, $uid, $command] = explode('/', $topic, 3);
            if (preg_match('/^\{/', $content)) {
                $content = json_decode($content, true);
            }

            $device = $this->getDevice($uid, $content);
            if ($device) {
                $command = str_replace('/', '-', $command);
                $fName = __DIR__ . '/Tasmota/' . $command . '.php';
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

        public function send($uid, $content)
        {
            if (isset($content['on'])) {
                $topic = 'tasmota/' . $uid . '/cmnd/POWER';
                Mqtt::send($topic, ($content['on'] ? 'ON' : 'OFF'));
            }
        }

        private static function getDevice($uid, $content)
        {
            $devices = Device::create($uid);
            return self::deviceDetect($devices, $content);
        }

        private static function deviceDetect($devices, $content)
        {
            foreach($devices as $device) {
                $data = $device->getData();
                if (!($data['inputDetector'] ?? false)) {
                    return $device;
                }
                foreach($data['inputDetector'] ?? [] as $detectorRow) {
                    if (!Helper::diffArray($detectorRow['input'] , $content)) {
                        $device->setOutput($detectorRow['output'] ?? false);
                        return $device;
                    }
                }
            }
            return null;
        }

    }
