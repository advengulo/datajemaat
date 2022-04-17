<?php

namespace App\Helpers;

class Helper
{
    public static function test()
    {
        return "Test";
    }

    public static function dateFormat($value)
    {
        if(!empty($value)){
            return date('Y-m-d', strtotime($value));
        }

        return $value;
    }

    public static function yearMonthDayDateFormat($date)
    {
        $date = date('Ymd', strtotime($date));
        return $date;
    }

    public static function yearMonthDateFormat($date)
    {
        if($date != null){
            $date = date('Ym', strtotime($date));
            return $date;
        }

        return '000000';
    }
}