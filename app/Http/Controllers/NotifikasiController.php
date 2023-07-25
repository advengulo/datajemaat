<?php

namespace App\Http\Controllers;

use App\Models\Config;
use App\Models\data_jemaat;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    private $badge;

    private $warningTahunlahir;


    public function __construct()
    {
        $this->badge = '<span><i class="fa fa-exclamation-circle" style="color:red"></i></span>';
        $this->warningTahunlahir = Config::where("name", "warning_tahun_lahir")->first();
    }
    
    public function index()
    {
        $notif['nonLingkungan'] = $this->menuNonLingkungan();
        $notif['dataWarning'] = $this->menuWarning();
        
        return $notif;
    }

    private function menuNonLingkungan()
    {
        $countNonLingkungan = data_jemaat::lingkunganNull()->isActive()->count();
        
        if($countNonLingkungan > 0){
            return $this->badge;
        }
        
        return "";
    }

    private function menuWarning()
    {
        $countWarningTanggalLahir = data_jemaat::getWarningTanggalLahir($this->warningTahunlahir->value)->count();

        if($countWarningTanggalLahir > 0){
            return $this->badge;
        }

        $countWarningDuplicateData = data_jemaat::getDuplicateData()->count();
        if($countWarningDuplicateData > 0){
            return $this->badge;
        }

        return "";
    }
}
