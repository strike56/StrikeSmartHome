<?php

    namespace Engine\Platforms;

    use \Workerman\Worker;
    use \Workerman\Timer;

    class OneWire {

        protected static Worker $server;
        protected static object $client;

        public static function init()
        {
            self::$server = new Worker();
            self::$server->onWorkerStart = [ self::class, 'start' ];
        }

        public static function start()
        {
            self::$client = new \Engine\Api\OneWire();
            Timer::add(1, function() {
                OneWire::tick();
            });
        }

        public static function onMessage($uid)
        {
            $source = new \Engine\Sources\OneWire();
            $source->receive($uid);
        }

        public static function tick()
        {
            $data = self::$client->load_alarm();
            foreach($data as $uid) {
                static::onMessage($uid);
            }
        }
    }
