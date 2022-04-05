<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\master_lingkungan;
use App\Models\data_jemaat;

class LingkunganMasterController extends Controller
{
    public function index()
    {
        $data_lingkungan = master_lingkungan::all();

        return view('pages.data-master.lingkungan', compact('data_lingkungan'));
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nomor_lingkungan' => 'required|numeric',
            'nama_lingkungan' => 'required',
            'nama_snk' => 'required',
        ],    
        [
            'nomor_lingkungan.required' => 'Nomor Lingkungan harus diisi',
            'nomor_lingkungan.numeric' => 'Nomor Lingkungan harus angka',
            'nama_lingkungan.required' => 'Nama Lingkungan harus diisi',
            'nama_snk.required' => 'Nama Satua Niha Keriso harus diisi',
        ]);

        master_lingkungan::updateOrCreate(['nomor_lingkungan' => $request->nomor_lingkungan],
                ['nama_lingkungan' => $request->nama_lingkungan, 'nama_snk' => $request->nama_snk]);        
   
        return back()->with(['success' => 'Data Lingkungan ditambahkan']);        
    }

    public function update(Request $request, $id)
    {
        $lingkungan = master_lingkungan::findOrFail($id);
        $lingkungan->nama_snk = $request->nama_snk;
        $lingkungan->update();

        return back()->with(['success' => 'Nama SNK berhasil diupdate']);
    }

    public function destroy($id)
    {
        $lingkungan = master_lingkungan::find($id);
        $checkLingkunganDataJemaat = data_jemaat::where('id_lingkungan', $lingkungan->id)->first();

        if($checkLingkunganDataJemaat){
            return back()->with(['error' => 'Data Lingkungan terkait dengan data Jemaat']); 
        }
        else{
            $lingkungan->delete();
        }

        return back()->with(['warning' => 'Data Lingkungan dihapus']); 
    }
}
