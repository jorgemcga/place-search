<?php
namespace Lib;

/**
 * Description of DateTools
 *
 * @author Edily Cesar Medule (edilycesar@gmail.com) <your.name at your.org>
 */
class DateTools
{
    public function currentUs()
    {
        return date('Y-m-d H:i:s');
    }
    
    public function validateUs($date)
    {
        $pattern = "/^20[0-9]{2}-[0-9]{2}-[0-9]{2}\ [0-9]{2}:[0-9]{2}:[0-9]{2}$/";
        return preg_match($pattern, $date) === false ? false : true;        
    }
    
    public function toBr($date, $showHour = true)
    {
        if (empty($date)) {
            return null;
        }
        
        // 2015-11-04 09:00:56
        $y = substr($date, 0, 4);
        $m = substr($date, 5, 2);
        $d = substr($date, 8, 2);
        $h = substr($date, 11, 2);
        $i = substr($date, 14, 2);
        $s = substr($date, 17, 2);
        $hour = $h . ":" . $i . ":" . $s;
        $date = $d . "-" . $m . "-" . $y;
        $date .= $showHour === true ? " " . $hour : "";
        return $date;
    }
    
    public function toUs($date, $showHour = true)
    {
        if (empty($date)) {
            return null;
        }
        
        // 30-06-1980
        
        $d = substr($date, 0, 2);
        $m = substr($date, 3, 2);
        $y = substr($date, 6, 4);
        
        $h = substr($date, 11, 2);
        $i = substr($date, 14, 2);
        $s = substr($date, 17, 2);
        $hour = $h . ":" . $i . ":" . $s;
        $date = $y . "-" . $m . "-" . $d;
        
        $date .= $showHour === true ? " " . $hour : "";
        return $date;
    }
}
