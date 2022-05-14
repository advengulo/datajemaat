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

        if($jemaat->wasChanged('jemaat_tanggal_lahir') || $jemaat->wasChanged('jemaat_tanggal_baptis') || $jemaat->wasChanged('jemaat_jenis_kelamin')){
            // Generate new nomor stambuk
            $newNomorStambuk = $this->generateNomorStambuk($input);

            // Update nomor stambuk in table Data_Jemaat
            $this->jemaatRepo->updateNomorStambuk($id, $newNomorStambuk);

            // Update nomor stambuk in table DataKeluarga
            $this->jemaatRepo->updateNoStambukInDataKeluarga($input['jemaat_nomor_stambuk'], $newNomorStambuk);
        }

        if($jemaat->jemaat_kk_status == true && $jemaat->wasChanged('id_lingkungan')){
            $this->changeFamilyZone($jemaat->id_parent, $input['id_lingkungan']);
        }

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

    public function changeFamilyZone($id, $id_lingkungan)
    {
        $familys = $this->getFamilyData($id)->get();
        foreach($familys as $fam){
            $fam->update([
                'id_lingkungan' => $id_lingkungan,
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