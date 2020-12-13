<?php

    namespace Engine\Sources;

    use Engine\Device;

    class WebSocket {

        private ?object $connection = null;

        public function setConnection(object $connections)
        {
            $this->connection = $connections;
        }

        public function receive($content)
        {
           $content = json_decode($content, true);
           $action = $content['action'] ?? null;
           $data = $content['data'] ?? null;

           $className = '\\Engine\\Sources\\WebSocket\\'.$action;
           if (class_exists($className)) {
               (new $className($this))->execute($data);
           }
        }

        public function send($action, $content)
        {
            $data = [
                'action' => $action,
                'data' => $content,
            ];
            $data = json_encode($data);
            $this->connection->send($data);
        }


    }
