<?php

    namespace Engine\Platforms;

    use Workerman\Worker;

    class WebSocket {
        protected static Worker $server;

        public static function init()
        {
            self::$server = new Worker('websocket://0.0.0.0:9201');
            self::$server->onConnect = [WebSocket::class, 'onConnect'];
            self::$server->onMessage = [WebSocket::class, 'onMessage'];
            self::$server->onClose = [WebSocket::class, 'onClose'];
        }

        public static function onConnect($connection)
        {
            print 'websocket client CONNECT'.PHP_EOL;
        }

        public static function onMessage( $connection, $data )
        {
            print 'websocket client MESSAGE: '.json_encode($data).PHP_EOL;
        }

        public static function onClose( $connection )
        {
            print 'websocket client CLOSE'.PHP_EOL;
        }

    }

