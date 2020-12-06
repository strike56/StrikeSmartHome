<?php

    namespace Engine;

    class Helper {

        public static function diffArray($array1, $array2)
        {
            $result = [];

            foreach ($array1 as $key => $value) {
                if (!is_array($array2) || !array_key_exists($key, $array2)) {
                    $result[$key] = $value;
                    continue;
                }

                if (is_array($value)) {
                    $recursiveArrayDiff = static::diffArray($value, $array2[$key]);

                    if (count($recursiveArrayDiff)) {
                        $result[$key] = $recursiveArrayDiff;
                    }

                    continue;
                }

                if ($value != $array2[$key]) {
                    $result[$key] = $value;
                }
            }

            return $result;
        }

    }
