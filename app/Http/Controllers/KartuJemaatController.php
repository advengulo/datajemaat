<?php

namespace App\Http\Controllers;

use App\Models\data_jemaat;
use App\Models\master_pendidikan;
use App\Models\master_lingkungan;
use App\Models\master_pekerjaan;
use App\Models\DataKeluarga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;
use App\Models\NomorKartu;
use Storage;
use File;
use ZipArchive;
use DataTables;

class KartuJemaatController extends Controller
{
    /**
     * Show the application Kartu Jemaat Menu.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public static function index(Request $request)
    {
        $datajemaats = data_jemaat::with('pekerjaan','lingkungan','kartukeluarga')
            ->where('jemaat_kk_status', '=', true)
            ->where('jemaat_status_aktif','t');

        // Apply lingkungan scope for lingkungan admin
        $datajemaats = auth()->user()->applyLingkunganScope($datajemaats, 'id_lingkungan');

        if($request->ajax()){  
            return DataTables::of($datajemaats)
                ->editColumn('lingkungan', function($datajemaats) { 
                    return $datajemaats->lingkungan->nama_lingkungan ;
                })
                ->editColumn('pekerjaan', function($datajemaats) {
                    return $datajemaats->pekerjaan->jenis_pekerjaan;
                })
                ->editColumn('nomor_kartu', function($datajemaats){
                    return $datajemaats->kartukeluarga->nomor_kartu ?? "0";
                })
                ->addColumn('action', function($data){
                    $button = '<a href="'. Route('lihatdatakk', $data) .'" target="_blank" class="btn btn-icon btn-sm btn-primary" id="btnDetail" data-toggle="tooltip" data-placement="top" title="Lihat" style="padding:5px"><i class="fa fa-eye" style="width: 20px;"></i>Lihat</a>';
                    $button .= '&nbsp;&nbsp;';
                    $button .= '<a href="'. Route('cetakpdf', $data) .'" target="_blank" class="btn btn-icon btn-sm btn-success" id="btnEdit" data-toggle="tooltip" data-placement="top" title="Edit" style="padding:5px"><i i class="fa fa-print" style="width:20px"></i>Cetak</a>';
                    return $button;
                })
            ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }

        $namaLingkungan = master_lingkungan::orderBy('nomor_lingkungan', 'ASC')->get()->groupBy('nama_lingkungan');

        return view('pages.kartujemaat.kartujemaat', compact('namaLingkungan'));
    }

    public function show(data_jemaat $data_jemaat)
    {
        // Check if user has access to this jemaat's lingkungan
        if (!auth()->user()->hasGlobalLingkunganAccess()) {
            if (!auth()->user()->hasLingkunganAccess($data_jemaat->id_lingkungan)) {
                abort(403, 'Anda tidak memiliki akses ke lingkungan ini.');
            }
        }

        $idparent = $data_jemaat->id;
        $isNomorKartu = NomorKartu::where('no_stambuk', $data_jemaat->jemaat_nomor_stambuk)->first();
        
        $lastId = NomorKartu::orderBy('id', 'desc')->first();
        if($isNomorKartu == null){
            $nomor_kartu = $this->generateNomorKartu($lastId, $data_jemaat->jemaat_nomor_stambuk);
        }
        else{
            $nomor_kartu = $isNomorKartu->nomor_kartu;
        }

        $dataKartuKeluargas = $this->getDataKeluargaKK($idparent);
        
        $customPaper = array(0,0,609.44,935.43);

        $pdf = PDF::loadView('pages.kartujemaat.pdf-view',
            compact('data_jemaat','dataKartuKeluargas','nomor_kartu'))
                ->setPaper($customPaper, 'landscape');
        
        return $pdf->stream();
        
    }

    public function cetak_pdf(data_jemaat $data_jemaat)
    {
        // Check if user has access to this jemaat's lingkungan
        if (!auth()->user()->hasGlobalLingkunganAccess()) {
            if (!auth()->user()->hasLingkunganAccess($data_jemaat->id_lingkungan)) {
                abort(403, 'Anda tidak memiliki akses ke lingkungan ini.');
            }
        }

        $idparent = $data_jemaat->id;
        $isNomorKartu = NomorKartu::where('no_stambuk', $data_jemaat->jemaat_nomor_stambuk)->first();

        $lastId = NomorKartu::orderBy('id', 'desc')->first();
        if($isNomorKartu == null){
            $nomor_kartu = $this->generateNomorKartu($lastId, $data_jemaat->jemaat_nomor_stambuk);
        }
        else{
            $nomor_kartu = $isNomorKartu->nomor_kartu;
        }

        $dataKartuKeluargas = $this->getDataKeluargaKK($idparent);

        $name = $data_jemaat->id_lingkungan . '_' . $data_jemaat->jemaat_nama . '.pdf';
        $customPaper = array(0,0,609.44,935.43);

        $pdf = PDF::loadView('pages.kartujemaat.pdf-view',
            compact('data_jemaat','dataKartuKeluargas','nomor_kartu'))
                ->setPaper($customPaper, 'landscape');
        
        return $pdf->download($name);
    }

    public function downloadZip(Request $request)
    {
        set_time_limit(300);
        $namaLingkungan = request('lingkungan');

        $dataAllKk = data_jemaat::with('lingkungan')
                        ->whereHas('lingkungan', function($q) use ($namaLingkungan){
                            $q->where('nama_lingkungan', $namaLingkungan);
                        })
                        ->where('jemaat_kk_status', '=', true)
                        ->where('jemaat_status_aktif','t');

        // Apply lingkungan scope for lingkungan admin
        $dataAllKk = auth()->user()->applyLingkunganScope($dataAllKk, 'id_lingkungan');
        $dataAllKk = $dataAllKk->get();

        foreach($dataAllKk as $data_jemaat){
            $idparent = $data_jemaat->id;
            $isNomorKartu = NomorKartu::where('no_stambuk', $data_jemaat->jemaat_nomor_stambuk)->first();
            
            $lastId = NomorKartu::orderBy('id', 'desc')->first();
            if($isNomorKartu == null){
                $nomor_kartu = $this->generateNomorKartu($lastId, $data_jemaat->jemaat_nomor_stambuk);
            }
            else{
                $nomor_kartu = $isNomorKartu->nomor_kartu;
            }

            $dataKartuKeluargas = $this->getDataKeluargaKK($idparent);            
            
            $name = $data_jemaat->id_lingkungan . '_' . $data_jemaat->jemaat_nama . '.pdf';
            $customPaper = array(0,0,609.44,935.43);

            $pdf = PDF::loadView('pages.kartujemaat.pdf-view',
                compact('data_jemaat','dataKartuKeluargas','nomor_kartu'))
                    ->setPaper($customPaper, 'landscape');
            Storage::put('public/kartu-keluarga/'.$namaLingkungan.'/'.$name, $pdf->output());
        }

        $zip = new ZipArchive;
   
        $fileName = $namaLingkungan.'-kartu-jemaat.zip';
   
        if ($zip->open(storage_path('app/public/kartu-keluarga/' . $fileName), ZipArchive::CREATE) === TRUE)
        {
            $files = File::files(storage_path('app/public/kartu-keluarga/' . $namaLingkungan));
   
            foreach ($files as $key => $value) {
                $relativeNameInZipFile = basename($value);
                $zip->addFile($value, $relativeNameInZipFile);
            }
             
            $zip->close();
        }
        return response()->download(storage_path('app/public/kartu-keluarga/' . $fileName));
    }

    public function generateNomorKartu($lastId, $jemaat_nomor_stambuk)
    {
        $nomor_kartu = str_pad(($lastId->id+1), 6, '0', STR_PAD_LEFT);
        $nomor = new NomorKartu;
        $nomor->no_stambuk = $jemaat_nomor_stambuk;
        $nomor->nomor_kartu = $nomor_kartu;
        $nomor->save();
    }

    public function getDataKeluargaKK($idparent)
    {
        return data_jemaat::with('datakeluarga.ayah.riwayatinaktif', 'datakeluarga.ibu.riwayatinaktif')
            ->where('id_parent', '=', $idparent)
            ->where('jemaat_status_aktif', '=', 't')
            ->orderBy('jemaat_status_dikeluarga', 'ASC')
            ->orderBy('jemaat_tanggal_lahir', 'ASC')
            ->get();
    }
    
}
