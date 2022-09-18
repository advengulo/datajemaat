<?php

namespace App\Helpers;

use App\Models\data_jemaat;
use App\Models\RiwayatInaktif;

class Helper
{
    public static function dateFormat($value)
    {
        if(!empty($value)){
            return date('Y-m-d', strtotime($value));
        }

        return null;
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

    public static function incrementPadRight($number, $lenght)
    {
        return str_pad($number, $lenght, "0", STR_PAD_LEFT);
    }

    public static function transformGenderToInt($gender)
    {
        if($gender == "p"){
            return data_jemaat::PEREMPUAN;
        }

        return data_jemaat::LAKI_LAKI;
    }

    public static function checkIfExistNomorStambuk($nomorStambuk)
    {
        $check =  data_jemaat::where('jemaat_nomor_stambuk', $nomorStambuk)->first();

        if(empty($check)){
            return false;
        }

        return true;
    }

    public static function isJemaatPassedAway($nomorStambuk)
    {
        $data = RiwayatInaktif::where('no_stambuk', $nomorStambuk)
            ->where('jemaat_keterangan_status', 'Meninggal')->first();

        if(empty($data)){
            return false;
        }

        return $data;
    }
}