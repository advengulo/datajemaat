<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatInaktif extends Model
{
    protected $fillable = ['no_stambuk', 'jemaat_keterangan_status', 'jemaat_tanggal_status', 'jemaat_tanggal_dikebumikan', 'jemaat_pindah_ke'];
    protected $table = 'data_riwayat_inaktif';
    public $timestamps = false;

    protected $casts = [
        'jemaat_tanggal_status' => 'datetime',
        'jemaat_tanggal_dikebumikan' => 'datetime',
    ];
}
