<?php

    namespace Engine;

    include __DIR__.'/../vendor/autoload.php';

    use \Workerman\Worker;
    use \Engine\Platforms\Mqtt;
    use \Engine\Platforms\OneWire;
    use \Engine\Platforms\WebSocket;
    use \Engine\Platforms\Timer;

    class Server {


        public static function init()
        {
            //Timer::init();
            WebSocket::init();
            //Mqtt::init();
            //OneWire::init();
        }

        public static function run() {
            Worker::runAll();
        }

    }
