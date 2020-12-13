<?php

    namespace Engine\Library;

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
                    __DIR__.'/../../tmpl',
                ], );
                static::$client = new Environment($loader);

                $function = new TwigFunction('include', function ($className, ...$params) {
                    try {
                        $expParams = explode('::', $className);
                        $className = $expParams[0];
                        $function = !empty($expParams[1]) ? $expParams[1] : 'execute';

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
                foreach([
                    'BASE_URL' => BASE_URL,
                    'SERVER_ADDR' => $_SERVER['SERVER_ADDR'],
                ] as $key => $value) {
                    static::$client->addGlobal($key, $value);
                }
            }
            return static::$client;
        }

    }