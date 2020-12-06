<?php

    namespace Engine\Platforms;

    use \Workerman\Worker;
    use \Workerman\Mqtt\Client;
    use \Engine\Sources\Tasmota;
    use \Engine\Sources\WThermostat;

    class Mqtt {
        protected static Client $client;
        protected static Worker $server;

        public static function init()
        {
            self::$server = new Worker();
            self::$server->name = 'Mqtt';
            self::$server->onWorkerStart = [ self::class, 'start' ];
        }

        public static function start()
        {
            self::$client = new Client('mqtt://192.168.0.2:1883');
            self::$client->onConnect = [Mqtt::class, 'onConnect'];
            self::$client->onMessage = [Mqtt::class, 'onMessage'];
            self::$client->connect();
        }

        public static function onConnect($mqtt)
        {
            $mqtt->publish('workerman-mqtt-client', 'START');
            $mqtt->subscribe('#');
        }

        public static function onMessage( $topic, $content )
        {
            if ($source = static::getSourceObject($topic)) {
                $source->receive($topic, $content);
            }
        }

        public static function getSourceObject($topic)
        {
            $source = explode('/', $topic)[0];
            switch(strtolower($source)) {
                case 'tasmota': return new Tasmota();
                case 'wthermostat': return new WThermostat();
            }
            return null;
        }

        public static function send($topic, $message)
        {
            self::$client->publish($topic, $message);
        }
    }

