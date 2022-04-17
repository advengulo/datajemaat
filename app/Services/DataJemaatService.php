<?php

namespace App\Services;

use App\Models\data_jemaat;
use DB, Helper;
use App\Repositories\DataJemaatRepository;


class DataJemaatService
{
    private $jemaatRepo;

    public function __construct(DataJemaatRepository $jemaatRepo)
    {
        $this->jemaatRepo = $jemaatRepo;
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
            $jemaat->jemaat_tanggal_bergabung = Helper::dateFormat($input['jemaat_tanggal_bergabung']);
            $jemaat->jemaat_tanggal_perkawinan = Helper::dateFormat($input['jemaat_tanggal_perkawinan']);
            $jemaat->save();
            $this->updateDataKeluarga($input, $jemaat->jemaat_nomor_stambuk);

            if($jemaat->wasChanged('jemaat_tanggal_lahir') || $jemaat->wasChanged('jemaat_tanggal_baptis') || $jemaat->wasChanged('jemaat_jenis_kelamin')){
                // Generate new nomor stambuk
                $noStambukNew = $this->generateNomorStambuk($input);
                $jemaat->jemaat_nomor_stambuk = $noStambukNew;
                $jemaat->save();
    
                // Update nomor stambuk in table DataKeluarga
                $this->updateStambukDataKeluarga($input['jemaat_nomor_stambuk'], $noStambukNew);
            }
    
            if($jemaat->jemaat_kk_status == true && $jemaat->wasChanged('id_lingkungan')){
                $this->changeFamilyZone($id);
            }
        } catch (Exception $e) {
            DB::rollBack();

            throw new InvalidArgumentException('Data tidak dapat diubah');
        }

        DB::commit();

        return $jemaat;
    }

    public function generateNomorStambuk($input)
    {
        $nomorStambuk = "";
        $checkIfExist = false;
        $tempIncr = 0;
        $jenisKelamin = $this->intGender($input['jemaat_jenis_kelamin']);
        $tanggalLahir = Helper::yearMonthDayDateFormat($input['jemaat_tanggal_lahir']);
        $tanggalBaptis = Helper::yearMonthDateFormat($input['jemaat_tanggal_baptis']);
        $increment = $this->incrementPadRight($tempIncr, 3);

        do {
            $tempIncr++;
            $increment = $this->incrementPadRight($tempIncr, 3);
            $nomorStambuk = $tanggalLahir . $tanggalBaptis . $jenisKelamin . $increment;
            $checkIfExist = $this->checkIfExistNomorStambuk($nomorStambuk);
        } while ($checkIfExist != false);

        return $nomorStambuk;
    }

    public function changeFamilyZone($id)
    {
        $familys = $this->getFamilyData($id);
        foreach($familys as $fam){
            $fam->update([
                'id_lingkungan' => request('id_lingkungan'),
            ]);
        }
    }

    public function checkIfExistNomorStambuk($nomorStambuk)
    {
        $check =  data_jemaat::where('jemaat_nomor_stambuk', $nomorStambuk)->first();

        if(empty($check)){
            return false;
        }

        return true;
    }

    public function updateStambukDataKeluarga($old, $new)
    {
        return $this->jemaatRepo->updateNoStambukInDataKeluarga($old, $new);
    }

    public function updateDataKeluarga($input, $noStambuk)
    {
        return $this->jemaatRepo->updateDataKeluarga($input, $noStambuk);
    }

    private function getFamilyData($id)
    {
        return data_jemaat::getChildByParentId($id);
    }

    private function intGender($gender)
    {
        if($gender == "p"){
            return 2;
        }

        return 1;
    }

    private function incrementPadRight($number, $lenght)
    {
        return str_pad($number, $lenght, "0", STR_PAD_LEFT);
    }
}