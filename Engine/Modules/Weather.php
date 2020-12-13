<?php

    namespace Engine\Modules;

    use Engine\Library\Cache;
    use Engine\Library\Tmpl;
    use Exception;

    class Weather {

        const DAYS = [
            'Вс',
            'Пн',
            'Вт',
            'Ср',
            'Чт',
            'Пт',
            'Сб',
        ];

        const MONTHS = [
            1 => 'Янв',
            2 => 'Фев',
            3 => 'Мар',
            4 => 'Апр',
            5 => 'Мая',
            6 => 'Июн',
            7 => 'Июл',
            8 => 'Авг',
            9 => 'Сен',
            10 => 'Окт',
            11 => 'Ноя',
            12 => 'Дек'
        ];


        public static function execute() {
            try {
                $tmpl = Tmpl::client()->load('modules/weather.twig');
                return $tmpl->render();
            } catch (Exception $e) {
                print 'ERROR: ['.$e->getMessage().']';
            }
        }

        public static function update()
        {
            $forecast = [];
            for($i = 1; $i <= 4; $i++) {
                $time = time() + ($i * 24 * 60 * 60);

                $forecast[] = [
                    'weekday' =>
                        static::DAYS[date('w', $time)].', '.
                        date('j', $time).' '.
                        static::MONTHS[date('n', $time)],
                    'image' => '/home/img/weather/3/Sunny.png',
                    'temp' => '-10°/12°'
                ];
            }

            $data = Cache::get('current-weather');
            return [
                'image' => '/home/img/weather/3/'.($data['icon'] ?? ''),
                'humi' => ($data['hum'] ?? '—').', '.($data['pressure'] ?? '—').' кПа',
                'wind' => $data['wind'] ?? '—',
                'temp' => '-0.8°C',
                'city' => 'Улброка',
                'cond' => ($data['cond'] ?? '—').', (облачность '.($data['cloud'] ?? '—').'%)',
                'forecast' => $forecast,
            ];
        }

    }