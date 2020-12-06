<?php

    namespace Engine;

    class Device {

        private ?array $data = null;
        private ?Type $type = null;

        public function __construct($data = null)
        {
            if (is_array($data)) {
                $this->data = $data;
            } elseif (is_string($data)) {
                $devices = static::getDeviceData($data);
                if ($devices) {
                    $this->data = $devices[0];
                }
            }
            $this->type = $this->getType();
        }

        public static function create($uid)
        {
            $devices = static::getDeviceData($uid);
            foreach($devices as $k => $device) {
                $devices[$k] = new Device($device);
            }
            return $devices;
        }

        public function getData()
        {
            return $this->data;
        }

        public function setOutput($output, $force = false)
        {
            if (!$force && !empty($this->data['output'])) return;
            $this->data['output'] = $output;
        }

        public function getType()
        {
            if (!$this->type && $typeName = $this->data['type'] ?? false) {
                $className = '\\Engine\\Types\\' . $typeName;
                $this->type = new $className;
                $this->type->setDevice($this);
            }
            return $this->type;
        }

        public static function getDeviceData($uid)
        {
            $fName = __DIR__ . '/../Devices/' . $uid . '.php';
            if (is_readable($fName)) {
                include $fName;
            }
            if (empty($devices)) $devices = [];
            return array_values($devices);
        }

        public function send($params)
        {
            if ($source = $this->data['source'] ?? false) {
                $className = '\\Engine\\Sources\\'.$source;
                $sourceObject = new $className();
                $sourceObject->send($this->data['id'], $params);
            }
        }

    }
