<?php


namespace App\Http\Controllers;

use DataTables;
use App\Models\data_jemaat;
use App\Models\DataKeluarga;
use Illuminate\Http\Request;
use App\Models\RiwayatInaktif;
use App\Models\master_pekerjaan;
use App\Exports\DataJemaatExport;
use App\Models\master_lingkungan;
use App\Models\master_pendidikan;
use Illuminate\Support\Facades\DB;
use App\Services\DataJemaatService;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\DataJemaatStore;

class DataJemaatController extends Controller
{
    private $jemaatSrv;

    public function __construct(DataJemaatService $jemaatService)
    {
        $this->jemaatSrv = $jemaatService;
    }

    public function index()
    {
        return view('pages.jemaat.data-jemaat');
    }

    public function ajax(Request $request)
    {
        $datajemaats = data_jemaat::with('lingkungan')
            ->isActive()->isSimpatisan(false)
            ->select('data_jemaats.*');
        
        if ($request->ajax()) {
            return DataTables::of($datajemaats)
                ->addColumn('jemaat_status_aktif', function () {
                    return '<span class="label label-primary">Aktif</span>';
                })
                ->addColumn('action', function ($data) {
                    $button = '<a href="' . Route('profiledetail', $data->id) . '" target="_blank" class="btn btn-icon btn-sm btn-primary" id="btnDetail" data-toggle="tooltip" data-placement="top" title="Lihat"><i class="fa fa-eye" style="width: 20px;"></i>Lihat</a>';
                    $button .= '&nbsp;&nbsp;';
                    $button .= '<a href="' . Route('jemaateditprofile', $data->id) . '" target="_blank" class="btn btn-icon btn-sm btn-warning" id="btnEdit" data-toggle="tooltip" data-placement="top" title="Edit"><i i class="fa fa-edit" style="width:20px"></i>Edit</a>';
                    return $button;
                })
                ->rawColumns(['action', 'jemaat_status_aktif'])
                ->addIndexColumn()
                ->make(true);
        }
    }

    public function create()
    {
        $data_pendidikans = master_pendidikan::all();
        $data_lingkungans = master_lingkungan::all();
        $data_pekerjaans = master_pekerjaan::all();
        $dataKK = data_jemaat::where('jemaat_kk_status', true)->where('jemaat_status_aktif', 't')->get();
        $dataAyah = data_jemaat::where('jemaat_status_aktif','t')
            ->where('jemaat_jenis_kelamin', 'l')
            ->where('jemaat_status_perkawinan', '!=', 2)
            ->get();
        $dataIbu = data_jemaat::where('jemaat_status_aktif','t')
            ->where('jemaat_jenis_kelamin', 'p')
            ->where('jemaat_status_perkawinan', '!=', 2)
            ->get();
        
        return view('pages.jemaat.tambah-jemaat', compact('data_pendidikans','data_lingkungans','data_pekerjaans', 'dataKK', 'dataAyah', 'dataIbu'));
    }

    public function store(DataJemaatStore $request)
    {
        $this->jemaatSrv->storeDataJemaat($request);

        return redirect()->route('datajemaat')->with(['success' => 'Data Jemaat berhasil di Tambahkan']);
    }

    public function show(data_jemaat $data_jemaat)
    {
        $parent = $data_jemaat->id_parent;
        $idjemaat = $data_jemaat->id;
        $dataKeluarga = DataKeluarga::with('ayah', 'ibu')->where('no_stambuk', '=', $data_jemaat->jemaat_nomor_stambuk)
            ->where('status_hubungan', '=', 1)->first();

        $kepalaKeluarga = data_jemaat::where('id_parent', $parent)->where('jemaat_kk_status', true)->first();
            
        return view('pages.jemaat.profile-jemaat', compact('data_jemaat', 'dataKeluarga', 'kepalaKeluarga'));
    }

    public function edit(data_jemaat $data_jemaat)
    {
        $this->$data_jemaat = $data_jemaat->id;
        $no_stambuk = $data_jemaat->jemaat_nomor_stambuk;
        $data_pendidikans = master_pendidikan::all();
        $data_lingkungans = master_lingkungan::all();
        $data_pekerjaans = DB::table('master_pekerjaans')
            ->orderBy('jenis_pekerjaan', 'asc')->get();
        $data_keluarga = DataKeluarga::where('no_stambuk', '=', $no_stambuk)
            ->first();
        $dataKK = data_jemaat::where('jemaat_kk_status', true)->where('jemaat_status_aktif', 't')->get();

        return view('pages.jemaat.edit-jemaat', compact('data_jemaat', 'data_pendidikans','data_lingkungans', 'data_pekerjaans', 'data_keluarga','dataKK'));      
    }

    public function update(Request $request, $id)
    {
        $jemaat = $this->jemaatSrv->updateDataJemaat($request, $id);

        if(!$jemaat->wasChanged()){
            return back()->with([
                'update' => [
                    'type' => 'warning',
                    'message' => 'Tidak ada perubahan'
                ]
            ]);
        }

        return back()->with([
            'update' => [
                'type' => 'info',
                'message' => 'Data Jemaat berhasil di ubah'
            ]
        ]);
    }

    public function destroy(data_jemaat $data_jemaat, $id)
    {
        $data_jemaat = data_jemaat::find($id);
        
        $data_jemaat->update([
            'jemaat_status_aktif' => "del",
        ]);
        return redirect()->route('datajemaat')->with(['delete' => 'Data Jemaat berhasil di hapus']);
    }

    public function updateStatusPindah(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $data_jemaat = data_jemaat::find($id);
            if($data_jemaat->jemaat_kk_status == true){
                $dataKeluargas = data_jemaat::where('id_parent', $id)->get();
                foreach($dataKeluargas as $dataKeluarga){
                    $dataKeluarga->update([
                        'jemaat_status_aktif' => 'f',
                    ]);
                    RiwayatInaktif::create([
                        'no_stambuk' => $dataKeluarga->jemaat_nomor_stambuk,
                        'jemaat_keterangan_status' => "Pindah",
                        'jemaat_tanggal_status' => request('jemaat_tanggal_status'),
                        'jemaat_pindah_ke' => request('jemaat_pindah_ke')
                    ]);
                } 
            }
            else{
                $data_jemaat->jemaat_status_aktif = 'f';
                $data_jemaat->save();
                RiwayatInaktif::create([
                    'no_stambuk' => $data_jemaat->jemaat_nomor_stambuk,
                    'jemaat_keterangan_status' => "Pindah",
                    'jemaat_tanggal_status' => request('jemaat_tanggal_status'),
                    'jemaat_pindah_ke' => request('jemaat_pindah_ke')
                ]);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with(['error' => 'Error ada kesalahan perubahan data']);
        }
        return back()->with(['update' => 'Data Jemaat berhasil di ubah']);
    }

    public function updateStatusMeninggal(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $data_jemaat = data_jemaat::find($id);
            //Cari apakah data tunggal, sehingga tidak perlu mengubah otomatis kepala keluarga
            $isSingleData = data_jemaat::where('id_parent', $data_jemaat->id_parent)->where('jemaat_status_aktif', 't')
                            ->count();
            $data_jemaat->jemaat_status_aktif = 'f';
            $data_jemaat->save();
            RiwayatInaktif::create([
                'no_stambuk' => $data_jemaat->jemaat_nomor_stambuk,
                'jemaat_keterangan_status' => "Meninggal",
                'jemaat_tanggal_status' => request('jemaat_tanggal_status'),
                'jemaat_tanggal_dikebumikan' => request('jemaat_tanggal_dikebumikan')
            ]);

            if($isSingleData > 1){
                if($data_jemaat->jemaat_status_dikeluarga == 1 && $data_jemaat->jemaat_status_perkawinan == 1){
                    $dataSI = data_jemaat::where('id_parent', $data_jemaat->id_parent)
                        ->where('jemaat_status_dikeluarga','2')    
                        ->first();

                    if(!empty($dataSI)){
                        if($dataSI->jemaat_jenis_kelamin == 'l'){
                            $dataSI->jemaat_status_perkawinan = 3; //Duda
                            $dataSI->save();
                        }
                        else{
                            $dataSI->jemaat_status_perkawinan = 4; //Janda
                            $dataSI->save();
                        }
                    }
                    
                }
                else if($data_jemaat->jemaat_status_dikeluarga == 2 && $data_jemaat->jemaat_status_perkawinan == 1){
                    $dataSI = data_jemaat::where('id_parent', $data_jemaat->id_parent)
                        ->where('jemaat_status_dikeluarga','1')    
                        ->first();

                    if(!empty($dataSI)){
                        if($dataSI->jemaat_jenis_kelamin == 'l'){
                            $dataSI->jemaat_status_perkawinan = 3; //Duda
                            $dataSI->save();
                        }
                        else{
                            $dataSI->jemaat_status_perkawinan = 4; //Janda
                            $dataSI->save();
                        }
                    }
                }
    
                if($data_jemaat->jemaat_kk_status == true){
                    $switchKK = data_jemaat::where('id_parent', $id)
                        ->where('jemaat_status_aktif', 't')
                        ->orderBy('jemaat_status_dikeluarga','asc')
                        ->orderBy('jemaat_tanggal_lahir','asc')
                        ->first();

                    $isSibling = false;
                    if($switchKK->jemaat_status_dikeluarga == 3){
                        $isSibling = true;
                    }
                    
                    $dataKeluargas = data_jemaat::where('id_parent', $id)->get();
                    foreach($dataKeluargas as $dataKeluarga){
                        $dataKeluarga->id_parent = $switchKK->id; //Ganti id parent ke id_kk baru
                        if($isSibling == true && $dataKeluarga->jemaat_status_dikeluarga == 3){ //check if siblings and family member is 3 (anak)
                            $dataKeluarga->jemaat_status_dikeluarga = 5; //if new parent is Anak, change another anak to Saudara Kandung
                        }
                        $dataKeluarga->save();
                    } 

                    $switchKK->jemaat_status_dikeluarga = 1;
                    $switchKK->jemaat_kk_status = true;
                    $switchKK->save();
                }
            }

            
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with(['error' => 'Error ada kesalahan perubahan data']);
        }

        return back()->with(['update' => 'Data Jemaat berhasil di Update']);
    }

    public function jadikankk(Request $request, $id)
    {
        $data_jemaat = data_jemaat::find($id);
        if($data_jemaat->jemaat_kk_status == true){
            return back()->with(['warning' => 'Data Jemaat sudah berstatus kepala keluarga']);
        }
        $data_jemaat->update([
            'jemaat_kk_status' => true,
            'jemaat_status_dikeluarga' => 1,
            'id_parent' => $id
        ]);

        return back()->with(['update' => 'Data Jemaat berhasil dijadikan Kepala Keluarga']);
    }

    public function exportDataJemaat()
    {
        $now = \Carbon\Carbon::now()->format('Y-m-d H:i:s');
        return Excel::download(new DataJemaatExport, 'Data Jemaat ' .$now. '.xlsx');
    }

    public function updateDataKeluarga(Request $request, $id)
    {
        $data = DataKeluarga::findOrFail($id);

        $data->nama_ayah = $request->nama_ayah;
        $data->nama_ibu = $request->nama_ibu;
        $data->save();
    }

    public static function nonLingkungan(Request $request)
    {
        $datajemaats = data_jemaat::lingkunganNull()->isActive();

        if($request->ajax()){  
            return DataTables::of($datajemaats)
                ->addColumn('kepala_keluarga', function($datajemaats) { 
                    return $datajemaats->jemaat_kk_status ? "Ya" : "";
                })
                ->addColumn('jemaat_status_aktif', function($datajemaats) { return '<span class="label label-primary">Aktif</span>'; })
                ->addColumn('action', function($data){
                    $button = '<a href="'. Route('profiledetail', $data->id) .'" target="_blank" class="btn btn-icon btn-sm btn-primary" id="btnDetail" data-toggle="tooltip" data-placement="top" title="Lihat"><i class="fa fa-eye" style="width: 20px;"></i>Lihat</a>';
                    $button .= '&nbsp;&nbsp;';
                    $button .= '<a href="'. Route('jemaateditprofile', $data->id) .'" target="_blank" class="btn btn-icon btn-sm btn-warning" id="btnEdit" data-toggle="tooltip" data-placement="top" title="Edit"><i i class="fa fa-edit" style="width:20px"></i>Edit</a>';
                    return $button;
                })
                ->rawColumns(['action','jemaat_status_aktif'])
                ->addIndexColumn()
                ->make(true);
        }
            
        return view('pages.jemaat.non_lingkungan');

    }

    public function updateStatusSimpatisan($id)
    {
        $data_jemaat = data_jemaat::find($id);

        if ($data_jemaat->is_simpatisan) {
            return back()->with(['warning' => 'Data jemaat merupakan jemaat simpatisan']);
        }

        DB::beginTransaction();
        try {
            if ($data_jemaat->jemaat_kk_status == true) {
                $dataKeluargas = data_jemaat::where('id_parent', $id)->get();
                foreach ($dataKeluargas as $dataKeluarga) {
                    $dataKeluarga->update([
                        'is_simpatisan' => true,
                    ]);
                }
            } else {
                $data_jemaat->is_simpatisan = true;
                $data_jemaat->save();
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with(['error' => 'Error ada kesalahan perubahan data']);
        }
        return back()->with(['update' => 'Data Jemaat berhasil di ubah']);
    }
}
