<?php

namespace Lib;

/**
 * Description of Sanitize
 *
 * @author Edily Cesar Medule (edilycesar@gmail.com) <your.name at your.org>
 */
class Sanitize
{

    public static function sanitizeAll($array)
    {
        $array2 = array();
        foreach ($array as $key => $value) {
            if (is_string($value)) {
                $array2[$key] = self::sanitizeString($value);
            } else {
                $array2[$key] = self::sanitizeAll($value);
            }
        }
        return $array2;
    }

    public static function sanitizeString($value)
    {
        $value = trim($value);
        $value = addslashes($value);
        return $value;
    }

}
