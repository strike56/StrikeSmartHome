<?php

    namespace Engine\Platforms;

    use \Workerman\Worker;
    use \Workerman\Timer;
    use \Engine\Server;

    class OneWire {

        protected static Worker $server;

        public static function init()
        {
            self::$server = new Worker();
            self::$server->onWorkerStart = [ self::class, 'start' ];
        }

        public static function start()
        {
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
            $data = Server::$kernel->api->ow->load_alarm();
            foreach($data as $uid) {
                static::onMessage($uid);
            }
        }
    }
