<?php

namespace App\Services;

use DB, Helper;
use App\Models\data_jemaat;
use Illuminate\Http\Request;
use App\Repositories\DataJemaatRepository;


class DataJemaatService
{
    private $jemaatRepo;

    public function __construct(DataJemaatRepository $jemaatRepo)
    {
        $this->jemaatRepo = $jemaatRepo;
    }

    public function storeDataJemaat($request)
    {
        $nomorstambuk = $this->generateNomorStambuk($request);
        $jemaat = $this->jemaatRepo->storeDataJemaat($request, $nomorstambuk);

        return $jemaat;
    }

    public function updateDataJemaat($request, $id)
    {
        $input = $request->all();

        $jemaat = $this->jemaatRepo->updateDataJemaat($input, $id);

        if ($jemaat->wasChanged('jemaat_tanggal_lahir') || $jemaat->wasChanged('jemaat_tanggal_baptis') || $jemaat->wasChanged('jemaat_jenis_kelamin')) {
            // Generate new nomor stambuk
            $newNomorStambuk = $this->generateNomorStambuk($input);

            // Update nomor stambuk in table Data_Jemaat
            $this->jemaatRepo->updateNomorStambuk($id, $newNomorStambuk);

            // Update nomor stambuk in table DataKeluarga
            $this->jemaatRepo->updateNoStambukInDataKeluarga($input['jemaat_nomor_stambuk'], $newNomorStambuk);
        }

        if ($jemaat->jemaat_kk_status == true && $jemaat->wasChanged('id_lingkungan')) {
            data_jemaat::getChildByParentId($jemaat->id_parent)->update(['id_lingkungan' => $input['id_lingkungan']]);
        }

        if ($jemaat->jemaat_kk_status == true && $jemaat->wasChanged('jemaat_alamat_rumah')) {
            data_jemaat::getChildByParentId($jemaat->id_parent)->update(['jemaat_alamat_rumah' => $input['jemaat_alamat_rumah']]);
        }

        return $jemaat;
    }

    public function generateNomorStambuk($input)
    {
        $nomorStambuk = "";
        $isExistNomorStambuk = false;
        $tempIncr = 0;
        $jenisKelamin = Helper::transformGenderToInt($input['jemaat_jenis_kelamin']);
        $tanggalLahir = Helper::yearMonthDayDateFormat($input['jemaat_tanggal_lahir']);
        $tanggalBaptis = Helper::yearMonthDateFormat($input['jemaat_tanggal_baptis']);
        $increment = Helper::incrementPadRight($tempIncr, 3);

        do {
            $tempIncr++;
            $increment = Helper::incrementPadRight($tempIncr, 3);
            $nomorStambuk = $tanggalLahir . $tanggalBaptis . $jenisKelamin . $increment;
            $isExistNomorStambuk = Helper::checkIfExistNomorStambuk($nomorStambuk);
        } while ($isExistNomorStambuk);

        return $nomorStambuk;
    }
}
