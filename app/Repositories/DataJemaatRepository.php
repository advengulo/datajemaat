<?php

namespace App\Repositories;

use DB, Helper;
use App\Models\data_jemaat;
use App\Models\DataKeluarga;

class DataJemaatRepository
{
    public function storeDataJemaat($request, $nomorStambuk)
    {
        DB::beginTransaction();
        try {
            $jemaat = data_jemaat::create([
                'jemaat_nomor_stambuk' => $nomorStambuk,
                'jemaat_nama' => $request->jemaat_nama,
                'jemaat_gelar_depan' => $request->jemaat_gelar_depan,
                'jemaat_gelar_belakang' => $request->jemaat_gelar_belakang,
                'jemaat_nama_alias' => $request->jemaat_nama_alias,
                'jemaat_tempat_lahir' => $request->jemaat_tempat_lahir,
                'jemaat_tanggal_lahir' => Helper::dateFormat($request->jemaat_tanggal_lahir),
                'jemaat_jenis_kelamin' => $request->jemaat_jenis_kelamin,
                'jemaat_status_perkawinan' => $request->jemaat_status_perkawinan,
                'jemaat_tanggal_perkawinan' => Helper::dateFormat($request->jemaat_tanggal_perkawinan),
                'jemaat_tanggal_baptis' => Helper::dateFormat($request->jemaat_tanggal_baptis),
                'jemaat_tanggal_sidi' => Helper::dateFormat($request->jemaat_tanggal_sidi),            
                'jemaat_tanggal_bergabung' => Helper::dateFormat($request->jemaat_tanggal_bergabung) ?? "2018-12-31",
                'id_pendidikan_akhir' => $request->id_pendidikan_akhir,
                'id_lingkungan' => $request->id_lingkungan,
                'jemaat_alamat_rumah' => $request->jemaat_alamat_rumah,
                'jemaat_nomor_hp' => $request->jemaat_nomor_hp,
                'jemaat_email' => $request->jemaat_email,
                'id_pekerjaan' => $request->id_pekerjaan,
                'jemaat_status_dikeluarga' => $request->jemaat_status_dikeluarga,
                'jemaat_status_aktif' => "t",
                'jemaat_kk_status' => $request->jemaat_kk_status ?? false,
                'jemaat_golongan_darah' => $request->jemaat_golongan_darah,
            ]);

            $jemaat->id_parent = $request->id_parent;
            if ($request->jemaat_kk_status == true) {
                $jemaat->id_parent = $jemaat->id;
            }

            $jemaat->save();

            DataKeluarga::create([
                'no_stambuk' => $nomorStambuk,
                'nama_ayah' => $request->namaAyah,
                'id_ayah' => $request->id_ayah,
                'nama_ibu' => $request->namaIbu,
                'id_ibu' => $request->id_ibu,
                'status_hubungan' => 1,
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with(['error' => 'Error ada kesalahan perubahan data']);
        }

        return $jemaat;
    }

    public function updateDataJemaat($input, $id)
    {
        $jemaat = data_jemaat::find($id);

        DB::beginTransaction();

        try {
            $jemaat->fill($input);
            $jemaat->jemaat_tanggal_lahir = Helper::dateFormat($input['jemaat_tanggal_lahir']);
            $jemaat->jemaat_tanggal_baptis = Helper::dateFormat($input['jemaat_tanggal_baptis']);
            $jemaat->jemaat_tanggal_sidi = Helper::dateFormat($input['jemaat_tanggal_sidi']);
            $jemaat->jemaat_tanggal_bergabung = Helper::dateFormat($input['jemaat_tanggal_bergabung']);
            $jemaat->jemaat_tanggal_perkawinan = Helper::dateFormat($input['jemaat_tanggal_perkawinan']);
            $jemaat->save();
            
            $dataKeluarga = DataKeluarga::findByNoStambuk($jemaat->jemaat_nomor_stambuk)->first();
            $dataKeluarga->nama_ayah = $input['namaAyah'];
            $dataKeluarga->nama_ibu = $input['namaIbu'];
            $dataKeluarga->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            throw new InvalidArgumentException('Data tidak dapat diubah');
        }

        return $jemaat;
    }

    public function updateNoStambukInDataKeluarga($old, $nowNomorStambuk)
    {
        $data = DataKeluarga::findByNoStambuk($old)->first();
        $data->no_stambuk = $nowNomorStambuk;
        $data->save();
    }

    public function updateNomorStambuk($id, $newNomorStambuk)
    {
        $jemaat = data_jemaat::find($id);
        $jemaat->jemaat_nomor_stambuk = $newNomorStambuk;
        $jemaat->save();
    }
}