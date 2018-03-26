<?php

namespace Lib;

/**
 * Description of Sanitize
 *
 * @author Edily Cesar Medule (edilycesar@gmail.com) <your.name at your.org>
 */
class UrlTools
{
    public function encode($string)
    {
        $string = trim($string);
        $len = strlen($string);
        $string2 = "";
        for ($i=0; $i<$len; $i++){
            $char = substr($string, $i, 1);
            $string2 .= "-" . ord($char);
        }
        return $string2;
    }
    
    public function decode($string)
    {
        $array = explode("-", $string);
        $len = count($array);
        $string2 = "";
        for ($i=0; $i<$len; $i++){
            $cod = (int)$array[$i];
            $string2 .= chr($cod);
        }
        return trim($string2);
    }
}
