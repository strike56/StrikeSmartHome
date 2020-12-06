<?php

    namespace Engine;

    use Exception;
    use \Doctrine\DBAL\DriverManager;

    class Db {

        private static ?object $client = null;

        public static function client()
        {

            if (empty(static::$client)) {
                try {
                    static::$client = DriverManager::getConnection(Config::get('db'));
                } catch (Exception $e) {
                    return null;
                }
            }
            return static::$client;
        }

    }