<?php

namespace Lib;

/**
 * Description of Sanitize
 *
 * @author Edily Cesar Medule (edilycesar@gmail.com) <your.name at your.org>
 */
class ArrayTools
{

    public static function arrayList($array)
    {
        $array2 = array();
        foreach ($array as $key => $value) {            
            if (is_string($value)) {
                $value = trim($value);
                $array2[$key] = addslashes($value);
            } else {
                $array2[$key] = $value;
            }
        }
        return $array2;
    }
    
    public static function trimRemoveValueEmpty($array)
    {
        $array2 = array();
        foreach ($array as $key => $value) {            
            if (is_string($value)) {
                $value = trim($value);
                if ($value != '') {
                    $array2[$key] = $value;
                }
            } else {
                $array2[$key] = $value;
            }
        }
        return $array2;
    }

}
