<?php

    namespace Engine\Sources\WebSocket;

    class Refresh {

        private ?object $parent = null;

        public function __construct($parent)
        {
            $this->parent = $parent;
        }

        public function execute($data) {
            $result = [];
            foreach($data ?? [] as $row) {
                $className = '\\Engine\\Modules\\'.$row['action'];
                if (class_exists($className)) {
                    $result[$row['id']] = $className::update();
                }
            }
            $this->parent->send('REFRESH', $result);
        }

    }