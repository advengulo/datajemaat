<?php

namespace App\Repositories;

use App\Models\DataKeluarga;

class DataJemaatRepository
{
    public function updateDataKeluarga($input, $noStambuk)
    {
        $dataKeluarga = DataKeluarga::findByNoStambuk($noStambuk)->first();
        $dataKeluarga->nama_ayah = $input['namaAyah'];
        $dataKeluarga->nama_ibu = $input['namaIbu'];
        $dataKeluarga->update();
    }
    public function updateNoStambukInDataKeluarga($old, $new)
    {
        $data = DataKeluarga::findByNoStambuk($old)->first();
        $data->no_stambuk = $new;
        $data->save();
    }
}