<?php

    namespace Engine;

    use \Twig\Loader\FilesystemLoader;
    use \Twig\Environment;
    use \Twig\TwigFunction;
    use Exception;

    class Tmpl {

        private static ?Environment $client = null;

        public static function client()
        {
            if (empty(static::$client)) {
                $loader = new FilesystemLoader([
                    __DIR__.'/../tmpl',
                ], );
                static::$client = new Environment($loader);

                $function = new TwigFunction('include', function ($className, ...$params) {
                    try {
                        [$className, $function] = explode('::', $className);
                        if (empty($function)) $function = 'execute';
                        if (class_exists($className)) {
                            $object = new $className;
                            if (method_exists($object, $function)) {
                                print $object->$function($params);
                            }
                        }
                    } catch (Exception $e) {
                        print "ERROR: ".$e->getMessage();
                    }
                });
                static::$client->addFunction($function);
                static::$client->addGlobal('BASE_URL', BASE_URL);
            }
            return static::$client;
        }

    }