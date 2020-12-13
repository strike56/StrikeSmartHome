<?php

    namespace Engine\Traits;

    use Engine\Core;
    use Engine\Library\Store;

    trait Brightness {

        public function brightness($value) {
            Store::setDevice('brightness', $value);
            Core::action([
                "source" => "device",
                "device" => $this->getDevice(),
                "type" => $this->getDevice()->getType(),
                "data" => [
                    "brightness" => $value,
                ]
            ]);

        }

    }