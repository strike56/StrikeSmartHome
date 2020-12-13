<?php

    namespace Engine\Sources\WebSocket;

    class Ping {

        public function execute($data) {
            var_dump('PING');
        }

    }