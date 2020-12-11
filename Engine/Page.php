<?php

    namespace Engine;

    use Exception;
    use Engine\Modules\Tmpl;

    define('BASE_URL', '/home');
    define('BASE_URL_REGX', preg_quote(BASE_URL, '/'));

    class Page {

        const MAPS = [
            ['pattern' => '/^'.BASE_URL_REGX.'\//',  'action' => 'Engine\\Layout\\Index' ],
        ];

        private static function getMapClass($url)
        {
            foreach(static::MAPS as $map) {
                if (preg_match($map['pattern'], $url, $matches)) {
                    return $map ?? null;
                }
            }
            return null;
        }

        private static function containerWrap($content, $container)
        {
            if (!is_null($content)) {
                if ($container !== false) {
                    $container = $container ?? 'main';
                    $tmplName = 'layout/'.$container.'.twig';
                    try {
                        $tmpl = Tmpl::client()->load($tmplName);
                        $content = $tmpl->render(['content' => $content]);
                    } catch (Exception $e) {
                        print 'ERROR: ['.$e->getMessage().']';
                    }
                }
            }
            return $content;
        }

        public static function run($url)
        {
            try {
                $map = static::getMapClass($url);
                if (!$map || empty($map['action'])) return null;
                [$className, $function] = explode('::', $map['action']);
                if (!$function) $function = 'execute';

                if (class_exists($className) && method_exists($className, $function)) {
                    $content = $className::$function();
                    $content = static::containerWrap($content, $map['container'] ?? null);
                    return $content;
                }
            } catch (Exception $e) {
                print 'ERROR: ['.$e->getMessage().']';
            }
            return null;
        }

    }
