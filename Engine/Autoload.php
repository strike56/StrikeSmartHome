<?php

    namespace Engine;

    include_once __DIR__.'/../vendor/autoload.php';

    class Autoloader {
        /**
         * Load files by namespace.
         *
         * @param string $name
         * @return boolean
         */
        public static function loadByNamespace($name)
        {
            $class_path = \str_replace('\\', \DIRECTORY_SEPARATOR, $name);
            if (\strpos($name, 'Engine\\') === 0) {
                $class_file = __DIR__ . \substr($class_path, \strlen('Engine')) . '.php';
            } else {
                return false;
            }

            if (\is_file($class_file)) {
                require_once($class_file);
                if (\class_exists($name, false)) {
                    return true;
                }
            }
            return false;
        }
    }

    spl_autoload_register('\Engine\Autoloader::loadByNamespace');
