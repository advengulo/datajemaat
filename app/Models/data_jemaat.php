<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class data_jemaat extends Model
{

    protected $fillable = [
        'id','jemaat_nomor_stambuk','jemaat_nama','jemaat_gelar_depan','jemaat_gelar_belakang','jemaat_nama_alias','jemaat_tempat_lahir','jemaat_tanggal_lahir','jemaat_jenis_kelamin','jemaat_tanggal_baptis','jemaat_tanggal_sidi','jemaat_status_perkawinan','jemaat_tanggal_perkawinan','id_pendidikan_akhir','id_lingkungan','jemaat_tanggal_bergabung','jemaat_alamat_rumah','jemaat_nomor_hp','jemaat_email','jemaat_status_aktif','jemaat_keterangan_status','jemaat_tanggal_status','id_pekerjaan','jemaat_status_dikeluarga','id_parent','jemaat_golongan_darah','jemaat_kk_status',

    ];

    protected $dates=[
        'jemaat_tanggal_lahir', 'jemaat_tanggal_status', 'jemaat_tanggal_baptis', 'jemaat_tanggal_sidi', 'jemaat_tanggal_bergabung','jemaat_tanggal_perkawinan'
    ];
    
    public function getAge(){        
        if($this->jemaat_keterangan_status=='Meninggal'){
            $diff  = date_diff( $this->jemaat_tanggal_lahir, $this->jemaat_tanggal_status->addDay() );
           
            return $diff->format('%y Tahun, %m Bulan, %d Hari');
        }
        else
            return $this->jemaat_tanggal_lahir->diff(Carbon::now())
                ->format('%y Tahun, %m Bulan, %d Hari');
    }

    public function countAge(){        
        return $this->jemaat_tanggal_lahir->diff(Carbon::now())->y;
    }

    public function getYearBergabung(){
        return $this->jemaat_tanggal_bergabung->year;
    }

    public function pendidikan()
    {
        return $this->belongsTo('App\Models\master_pendidikan', 'id_pendidikan_akhir');
    }

    public function lingkungan()
    {
        return $this->belongsTo('\App\Models\master_lingkungan', 'id_lingkungan');
    }

    public function pekerjaan()
    {
        return $this->belongsTo('\App\Models\master_pekerjaan', 'id_pekerjaan');
    }
    public function statusdikeluarga()
    {
        return $this->belongsTo('\App\Models\master_status_dikeluarga', 'jemaat_status_dikeluarga');
    }
    public function datakeluarga()
    {
        return $this->belongsTo('\App\Models\DataKeluarga', 'jemaat_nomor_stambuk', 'no_stambuk');
    }
    public function kartukeluarga()
    {
        return $this->belongsTo('\App\Models\NomorKartu', 'jemaat_nomor_stambuk', 'no_stambuk');
    }
    public function riwayatinaktif()
    {
        return $this->belongsTo('\App\Models\RiwayatInaktif', 'jemaat_nomor_stambuk', 'no_stambuk');
    }
    
}
