<?php

    namespace Engine\Traits;

    use Engine\Core;

    trait Pulse {

        public function pulse($value) {
            Core::action([
                "source" => "device",
                "device" => $this->getDevice(),
                "type" => $this->getDevice()->getType(),
                "data" => [
                    "pulse" => $value,
                ],
            ]);
        }

    }