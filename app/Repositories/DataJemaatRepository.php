<?php

namespace App\Repositories;

use DB, Helper;
use App\Models\data_jemaat;
use App\Models\DataKeluarga;

class DataJemaatRepository
{
    

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