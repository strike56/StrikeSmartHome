<?php

    namespace Engine;

    class Type {

        private $device = null;

        public function input($params)
        {
            foreach($params as $funName => $funParams) {
                if (method_exists($this, $funName)) {
                    $this->$funName($funParams);
                }
            }
        }

        public function setDevice(Device $device)
        {
            $this->device = $device;
        }

        public function getDevice()
        {
            return $this->device;
        }

    }
