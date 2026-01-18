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
            ->select('data_jemaats.*');

        // Apply lingkungan scope for lingkungan admin
        $datajemaats = auth()->user()->applyLingkunganScope($datajemaats, 'id_lingkungan');

        if ($request->ajax()) {
            return DataTables::of($datajemaats)
                ->addColumn('jemaat_status_aktif', function () {
                    return '<span class="label label-primary">Aktif</span> - <span class="label label-success">Simpatisan</span>';
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

        // Filter lingkungan based on user access
        if (auth()->user()->hasGlobalLingkunganAccess()) {
            $data_lingkungans = master_lingkungan::all();
        } else {
            $lingkunganIds = auth()->user()->getLingkunganIds();
            $data_lingkungans = master_lingkungan::whereIn('id', $lingkunganIds)->get();
        }

        $data_pekerjaans = master_pekerjaan::all();

        // Apply lingkungan scope to jemaat queries
        $dataKK = data_jemaat::where('jemaat_kk_status', true)
            ->where('jemaat_status_aktif', 't');
        $dataKK = auth()->user()->applyLingkunganScope($dataKK, 'id_lingkungan');
        $dataKK = $dataKK->get();

        $dataAyah = data_jemaat::where('jemaat_status_aktif','t')
            ->where('jemaat_jenis_kelamin', 'l')
            ->where('jemaat_status_perkawinan', '!=', 2);
        $dataAyah = auth()->user()->applyLingkunganScope($dataAyah, 'id_lingkungan');
        $dataAyah = $dataAyah->get();

        $dataIbu = data_jemaat::where('jemaat_status_aktif','t')
            ->where('jemaat_jenis_kelamin', 'p')
            ->where('jemaat_status_perkawinan', '!=', 2);
        $dataIbu = auth()->user()->applyLingkunganScope($dataIbu, 'id_lingkungan');
        $dataIbu = $dataIbu->get();

        return view('pages.jemaat.simpatisan.create', compact('data_pendidikans','data_lingkungans','data_pekerjaans', 'dataKK', 'dataAyah', 'dataIbu'));
    }

    public function store(DataJemaatStore $request)
    {
        $this->jemaatSrv->storeDataJemaat($request);

        return redirect()->route('jemaat.simpatisan')->with(['success' => 'Data Jemaat Simpatisan berhasil di Tambahkan']);
    }
}
