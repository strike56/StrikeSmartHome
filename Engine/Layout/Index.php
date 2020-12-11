<?php

    namespace Engine\Layout;

    use Engine\Modules\Tmpl;
    use Exception;

    class Index {

        public static function execute() {
            try {
                $tmpl = Tmpl::client()->load('index.twig');
                return $tmpl->render();
            } catch (Exception $e) {
                print 'ERROR: ['.$e->getMessage().']';
            }
        }

    }