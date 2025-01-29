<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\data_jemaat;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\KepalaKeluargaExport;
use App\Exports\KepalaKeluargaSimpatisanExport;
use DataTables;


class KepalaKeluargaController extends Controller
{
    public static function index(Request $request)
    {
        $datajemaats = data_jemaat::with('pekerjaan','lingkungan')->where('jemaat_kk_status', '=', true)
                        ->where('jemaat_status_aktif','t');

        if($request->ajax()){
            return DataTables::of($datajemaats)
                ->editColumn('lingkungan', function($datajemaats) {
                    return $datajemaats->lingkungan->nama_lingkungan ;
                })
                ->editColumn('pekerjaan', function($datajemaats) {
                    return $datajemaats->pekerjaan->jenis_pekerjaan;
                })
                ->addColumn('action', function($data){
                    $button = '<a href="'. Route('profiledetail', $data->id) .'" target="_blank" class="btn btn-icon btn-sm btn-primary" id="btnDetail" data-toggle="tooltip" data-placement="top" title="Lihat"><i class="fa fa-eye" style="width: 20px;"></i>Lihat</a>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }

        return view('pages.kepalakeluarga.data-kk');
    }

    public function exportDataKK()
    {
        $now = \Carbon\Carbon::now()->format('Y-m-d H:i:s');
        return Excel::download(new KepalaKeluargaExport, 'Data Kepala Keluarga ' .$now. '.xlsx');
    }

    public static function kepalaKeluargaSimpatisan(Request $request)
    {
        $datajemaats = data_jemaat::with('pekerjaan','lingkungan')
            ->isKepalaKeluarga()
            ->isSimpatisan()
            ->isActive();

        if($request->ajax()){
            return DataTables::of($datajemaats)
                ->editColumn('lingkungan', function($datajemaats) {
                    return $datajemaats->lingkungan->nama_lingkungan ;
                })
                ->editColumn('pekerjaan', function($datajemaats) {
                    return $datajemaats->pekerjaan->jenis_pekerjaan;
                })
                ->addColumn('action', function($data){
                    $button = '<a href="'. Route('profiledetail', $data->id) .'" target="_blank" class="btn btn-icon btn-sm btn-primary" id="btnDetail" data-toggle="tooltip" data-placement="top" title="Lihat"><i class="fa fa-eye" style="width: 20px;"></i>Lihat</a>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }

        return view('pages.kepalakeluarga.data-kk-simpatisan');
    }

    public function exportDataKKSimpatisan()
    {
        $now = \Carbon\Carbon::now()->format('Y-m-d H:i:s');
        return Excel::download(new KepalaKeluargaSimpatisanExport, 'Data Kepala Keluarga Simpatisan ' .$now. '.xlsx');
    }
}
