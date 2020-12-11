<?php

    namespace Engine\Modules;

    use Symfony\Component\Cache\Adapter\RedisAdapter;
    use Engine\Config;
    use Exception;

    class Cache {

        private static ?object $client = null;

        public static function client()
        {
            if (empty(static::$client)) {
                try {
                    $connection = RedisAdapter::createConnection(Config::get('cache'));
                    static::$client = new RedisAdapter($connection);

                } catch (Exception $e) {
                    return null;
                }
            }
            return static::$client;
        }

        public static function get($key)
        {
            try {
                if ($client = static::client()) {
                    $item = $client->getItem($key);
                    return $item->get();
                }
            } catch (Exception $e) {}
            return null;
        }

        public static function set($key, $val, $expires)
        {
            try {
                if ($client = static::client()) {
                    $item = $client->getItem($key);
                    $item->set($val)->expiresAfter($expires);
                    $client->save($item);
                    return true;
                }
            } catch (Exception $e) {}
            return false;
        }
    }