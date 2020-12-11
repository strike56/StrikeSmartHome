<?php

    namespace Engine\Content\Blocks;

    use Engine\Modules\Tmpl;
    use Exception;

    class Clock {

        const MONTHS = [
            1 => 'Января',
            2 => 'Февраля',
            3 => 'Марта',
            4 => 'Апреля',
            5 => 'Мая',
            6 => 'Июня',
            7 => 'Июля',
            8 => 'Августа',
            9 => 'Сентября',
            10 => 'Октября',
            11 => 'Ноября',
            12 => 'Декабря'
        ];

        const DAYS = [
            'Воскресенье',
            'Понедельник',
            'Вторник',
            'Среда',
            'Четверг',
            'Пятница',
            'Суббота',
        ];

        public static function execute() {
            try {
                $tmpl = Tmpl::client()->load('content/blocks/clock.twig');
                return $tmpl->render(static::data());
            } catch (Exception $e) {
                print 'ERROR: ['.$e->getMessage().']';
            }
        }

        public static function data()
        {
            return [
                'day' => date('j').' '.
                    static::MONTHS[date('n')].', '.
                    static::DAYS[date('w')],
            ];
        }

    }