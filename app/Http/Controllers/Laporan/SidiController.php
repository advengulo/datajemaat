<?php

namespace App\Http\Controllers\Laporan;

use Carbon\Carbon;
use App\Models\data_jemaat;
use App\Models\master_lingkungan;
use Illuminate\Http\Request;
use DataTables;
use App\Http\Controllers\Controller;

class SidiController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sidi(Request $request)
    {
        $lingkungan = master_lingkungan::all();
        $total = [];
        $jumlah = 0;
        $l = 0;
        $p = 0;
        $kk = 0;
        foreach($lingkungan as $d){
            //count Jumlah Kepala keluarga setiap lingkungan
            $jumlahKK[$d->nomor_lingkungan]['jumlahKK'] = data_jemaat::where('id_lingkungan', $d->nomor_lingkungan)
                ->where('jemaat_kk_status', true)->where('jemaat_status_aktif','t')->count();
            
            //count Jumlah jiwa laki/perempuan tiap lingkungan, Dewasa >17 Tahun
            $DL = 0; //DL Dewasa Laki laki
            $DP = 0; //DP Dewasa Perempuan
            $tempOfJumlahJiwa = data_jemaat::where('id_lingkungan', $d->nomor_lingkungan)
                ->where('jemaat_status_aktif','t')->get();
                    foreach($tempOfJumlahJiwa as $data){
                        if($data->jemaat_tanggal_sidi != null || $data->countAge() >= 17){
                            if($data->jemaat_jenis_kelamin == 'l')
                                $DL += 1;
                            else
                                $DP += 1;
                        }
                    }

            $jumlahJiwa[$d->nomor_lingkungan]['dewasa']['l'] = $DL;
            $jumlahJiwa[$d->nomor_lingkungan]['dewasa']['p'] = $DP;

            $jumlah += $DL+$DP;
            $kk += $jumlahKK[$d->nomor_lingkungan]['jumlahKK'];
            $l += $DL;
            $p += $DP;
        }
        $total['jumlah'] = $jumlah;
        $total['kk'] = $kk;
        $total['l'] = $l;
        $total['p'] = $p;

        return view('pages.laporan.sidi', compact('lingkungan', 'jumlahKK', 'jumlahJiwa', 'total'));
    }

    public function nama(Request $request)
    {   
        $datajemaats = data_jemaat::select('id', 'jemaat_nama', 'jemaat_nama_alias', 'jemaat_jenis_kelamin','jemaat_tanggal_lahir', 'id_lingkungan')
            ->where('jemaat_status_aktif','t')
            ->where(function ($q){
                $q->where('jemaat_tanggal_sidi', '!=', null)
                ->orWhere('jemaat_tanggal_lahir', '<=', Carbon::now()->subYears(17));
            })
            ->orderBy('id_lingkungan', 'ASC')
            ->orderBy('id_parent', 'asc')
            ->orderBy('jemaat_status_dikeluarga');

        if($request->ajax()){  
            return DataTables::of($datajemaats)
                ->editColumn('lingkungan', function($datajemaats) { 
                    return $datajemaats->lingkungan->nama_lingkungan ;
                })
                ->editColumn('jemaat_jenis_kelamin', function($datajemaats) { 
                    return $datajemaats->jemaat_jenis_kelamin == 'l' ? "L" : "P";
                })
                ->addIndexColumn()
                ->make(true);
        }

        return view('pages.laporan.data-sidi');
    }
}
