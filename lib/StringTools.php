<?php

namespace Lib;

/**
 * Description of Sanitize
 *
 * @author Edily Cesar Medule (edilycesar@gmail.com) <your.name at your.org>
 */
class StringTools
{

    public function replace($search, $replace, $subject)
    {
        return str_replace($search, $replace, $subject);
    }

    public function trim($value = "")
    {
        return trim($value);
    }

    public function removeLastChar($string)
    {
        return substr($string, 0, strlen($string) - 1);
    }

    public function removeEndComma($string)
    {
        $len = strlen($string);
        if (substr($string, $len - 1, 1) == ",") {
            $string = substr($string, 0, $len - 1);
        }
        return $string;
    }

    public function addSlashes($array)
    {
        $array2 = array();
        foreach ($array as $key => $value) {
            if (is_string($value)) {
                $array2[$key] = addslashes($value);
            } else {
                $array2[$key] = $value;
            }
        }
        return $array2;
    }

    public function onlyChar($string)
    {
        $string = preg_replace("/[^:\/ à-úÀ-Úa-zA-Z0-9]/", "", $string);
        return $string;
    }

    public function killSpecialChars($string)
    {
        return preg_replace(array("/(á|à|ã|â|ä)/", "/(Á|À|Ã|Â|Ä)/", "/(é|è|ê|ë)/", "/(É|È|Ê|Ë)/", "/(í|ì|î|ï)/", "/(Í|Ì|Î|Ï)/", "/(ó|ò|õ|ô|ö)/", "/(Ó|Ò|Õ|Ô|Ö)/", "/(ú|ù|û|ü)/", "/(Ú|Ù|Û|Ü)/", "/(ñ)/", "/(Ñ)/", "/(ç)/", "/(Ç)/"), explode(" ", "a A e E i I o O u U n N c C"), $string);
    }

    public function stringfy(array $values)
    {
        foreach ($values as $key => $value) {
            $values[$key] = "'" . $value . "'";
        }
        return $values;
    }

}
