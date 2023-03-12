<?php

namespace App\Http\Controllers;

use App\Models\data_jemaat;
use Illuminate\Http\Request;
use App\Models\master_pekerjaan;
use Yajra\DataTables\DataTables;
use App\Models\master_lingkungan;
use App\Models\master_pendidikan;
use App\Services\DataJemaatService;
use App\Http\Requests\DataJemaatStore;

class JemaatSimpatisanController extends Controller
{
    private $jemaatSrv;

    public function __construct(DataJemaatService $jemaatService)
    {
        $this->jemaatSrv = $jemaatService;
    }

    public function index()
    {
        return view('pages.jemaat.simpatisan.index');
    }

    public function ajax(Request $request)
    {
        $datajemaats = data_jemaat::with('lingkungan')
            ->isActive()->isSimpatisan()
            ->select('id', 'jemaat_nama', 'jemaat_nama_alias', 'jemaat_nomor_stambuk', 'id_lingkungan', 'jemaat_status_aktif');

        if ($request->ajax()) {
            return DataTables::of($datajemaats)
                ->editColumn('lingkungan', function ($datajemaats) {
                    return $datajemaats->id_lingkungan . ' - ' . $datajemaats->lingkungan->nama_lingkungan;
                })
                ->addColumn('jemaat_status_aktif', function ($datajemaats) {
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
        
        return view('pages.jemaat.simpatisan.create', compact('data_pendidikans','data_lingkungans','data_pekerjaans', 'dataKK', 'dataAyah', 'dataIbu'));
    }

    public function store(DataJemaatStore $request)
    {
        $this->jemaatSrv->storeDataJemaat($request);

        return redirect()->route('jemaat.simpatisan')->with(['success' => 'Data Jemaat Simpatisan berhasil di Tambahkan']);
    }
}
