<?php

namespace App\Http\Controllers;

use App\Models\data_jemaat;
use App\Models\master_pendidikan;
use App\Models\master_lingkungan;
use App\Models\master_pekerjaan;
use App\Models\DataKeluarga;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {   
        $thisyear = Carbon::now()->year; 
        $totalYearsShowing = 4; // How many year will be showing //TODO : config DB
        $years = [];
        $laki = []; 
        $perempuan = [];
        $total = [];

        for ($i = 0; $i < $totalYearsShowing; $i++){
            $year = $thisyear - ($totalYearsShowing - $i - 1); 
            $years[$i] = $year;
            $laki[$i] = count(DB::select("SELECT CAST(SUBSTRING(jemaat_tanggal_bergabung, 1, 4) AS UNSIGNED), jemaat_status_aktif, jemaat_jenis_kelamin FROM data_jemaats where jemaat_status_aktif = 't' AND jemaat_jenis_kelamin = 'l' AND CAST(SUBSTRING(jemaat_tanggal_bergabung, 1, 4) AS UNSIGNED) = '$year'"));
            $perempuan[$i] = count(DB::select("SELECT CAST(SUBSTRING(jemaat_tanggal_bergabung, 1, 4) AS UNSIGNED), jemaat_status_aktif, jemaat_jenis_kelamin FROM data_jemaats where jemaat_status_aktif = 't' AND jemaat_jenis_kelamin = 'p' AND CAST(SUBSTRING(jemaat_tanggal_bergabung, 1, 4) AS UNSIGNED) = '$year'"));
            $total[$i] = $laki[$i] + $perempuan[$i]; 
        }

        $data = [];

        //count Total jemaat
        $data['total_jemaat'] = data_jemaat::where('jemaat_status_aktif','t')->count();
        //count total jemaat simpatisan
        $data['total_jemaat_simpatisan'] = data_jemaat::isSimpatisan()->isActive()->count();
        //count Total kepala keluarga
        $data['total_kk'] = data_jemaat::where('jemaat_kk_status', '=', true)->where('jemaat_status_aktif', 't')->count();
        //count Total lingkungan                
        $data['total_lingkungan'] = master_lingkungan::count();
        //count Total jemaat yang bergabung di tahun ini
        $data['total_bergabung_thisyear'] = count(DB::select("SELECT CAST(SUBSTRING(jemaat_tanggal_bergabung, 1, 4) AS UNSIGNED), jemaat_status_aktif FROM data_jemaats where jemaat_status_aktif = 't' AND CAST(SUBSTRING(jemaat_tanggal_bergabung, 1, 4) AS UNSIGNED) = '$thisyear'"));
        //count Total seluruh Laki-laki
        $data['total_laki_laki'] = data_jemaat::where('jemaat_jenis_kelamin', 'l')->where('jemaat_status_aktif','t')->count();
        //count Total seluruh Perempuan
        $data['total_perempuan'] = data_jemaat::where('jemaat_jenis_kelamin', 'p')->where('jemaat_status_aktif','t')->count();

        return view('pages.index', compact('years','laki','perempuan','thisyear','data', 'total'));
    }

    static function countNewDataToday()
    {
        $today = Carbon::today();
        return $total = data_jemaat::where('created_at',$today)->count();
    }
    static function countNewDataWeekly()
    {
        $today = Carbon::today();
        $data_jemaat = data_jemaat::where('created_at', '>', $today->subDays(7))->get();
        $totalCount = $data_jemaat->count(); //Should return your total number of events from past 7 days
        $response = array();
        $i = 0;
        while ($i < 7) {
            $dayOfWeek = $today->subDays($i);
            $totaldataweekly = $data_jemaat->where('created_at', $dayOfWeek);
            $response[$dayOfWeek] = $eventsForThisDay->count();
            $i++;
        }
        return $response;
    }
}
