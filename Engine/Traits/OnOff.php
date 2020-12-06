<?php

    namespace Engine\Traits;

    use \Engine\Core;
    use \Engine\Store;

    trait OnOff {

        public function on($status) {
            Store::setDevice('on', $status);
            Core::action([
                "source" => "device",
                "device" => $this->getDevice(),
                "type" => $this->getDevice()->getType(),
                "data" => [
                    "on" => $status,
                ]
            ]);
        }

    }