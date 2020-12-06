<?php

    namespace Engine\Platforms;

    use Workerman\Worker;

    class Timer {
        protected static Worker $server;

        public static function init()
        {
            self::$server = new Worker();
            self::$server->name = 'Timer';
            self::$server->onWorkerStart = [ self::class, 'start' ];
        }

        public static function start()
        {
            \Workerman\Timer::add(1, function() {
                Timer::tick();
            });
        }

        public static function tick()
        {
        }
    }

