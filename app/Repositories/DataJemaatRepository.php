<?php

namespace App\Repositories;

use DB, Helper;
use App\Models\data_jemaat;
use App\Models\DataKeluarga;

class DataJemaatRepository
{
    public function storeDataJemaat($data, $nomorStambuk = null)
    {
        // Support both array and Request object
        $isRequest = is_object($data);
        $getData = function($key, $default = null) use ($data, $isRequest) {
            if ($isRequest) {
                return $data->$key ?? $data->input($key, $default);
            }
            return $data[$key] ?? $default;
        };

        // Generate nomor stambuk if not provided
        if (!$nomorStambuk) {
            $nomorStambuk = $this->generateNomorStambuk($data);
        }

        DB::beginTransaction();
        try {
            $jemaat = data_jemaat::create([
                'jemaat_nomor_stambuk' => $nomorStambuk,
                'jemaat_nama' => $getData('jemaat_nama'),
                'jemaat_gelar_depan' => $getData('jemaat_gelar_depan'),
                'jemaat_gelar_belakang' => $getData('jemaat_gelar_belakang'),
                'jemaat_nama_alias' => $getData('jemaat_nama_alias'),
                'jemaat_tempat_lahir' => $getData('jemaat_tempat_lahir'),
                'jemaat_tanggal_lahir' => Helper::dateFormat($getData('jemaat_tanggal_lahir')),
                'jemaat_jenis_kelamin' => $getData('jemaat_jenis_kelamin'),
                'jemaat_status_perkawinan' => $getData('jemaat_status_perkawinan'),
                'jemaat_tanggal_perkawinan' => Helper::dateFormat($getData('jemaat_tanggal_perkawinan')),
                'jemaat_tanggal_baptis' => Helper::dateFormat($getData('jemaat_tanggal_baptis')),
                'jemaat_tanggal_sidi' => Helper::dateFormat($getData('jemaat_tanggal_sidi')),
                'jemaat_tanggal_bergabung' => Helper::dateFormat($getData('jemaat_tanggal_bergabung')) ?? "2018-12-31",
                'id_pendidikan_akhir' => $getData('id_pendidikan_akhir'),
                'id_lingkungan' => $getData('id_lingkungan'),
                'jemaat_alamat_rumah' => $getData('jemaat_alamat_rumah'),
                'jemaat_nomor_hp' => $getData('jemaat_nomor_hp'),
                'jemaat_email' => $getData('jemaat_email'),
                'id_pekerjaan' => $getData('id_pekerjaan'),
                'jemaat_status_dikeluarga' => $getData('jemaat_status_dikeluarga'),
                'jemaat_status_aktif' => "t",
                'jemaat_kk_status' => $getData('jemaat_kk_status', false),
                'jemaat_golongan_darah' => $getData('jemaat_golongan_darah'),
                'is_simpatisan' => $getData('jemaat_simpatisan', false),
            ]);

            $jemaat->id_parent = $getData('id_parent');
            if ($getData('jemaat_kk_status') == true) {
                $jemaat->id_parent = $jemaat->id;
            }

            $jemaat->save();

            DataKeluarga::create([
                'no_stambuk' => $nomorStambuk,
                'nama_ayah' => $getData('namaAyah'),
                'id_ayah' => $getData('id_ayah'),
                'nama_ibu' => $getData('namaIbu'),
                'id_ibu' => $getData('id_ibu'),
                'status_hubungan' => 1,
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw new \Exception('Error: Unable to save data - ' . $e->getMessage());
        }

        return $jemaat;
    }

    private function generateNomorStambuk($data)
    {
        $isRequest = is_object($data);
        $getData = function($key, $default = null) use ($data, $isRequest) {
            if ($isRequest) {
                return $data->$key ?? $data->input($key, $default);
            }
            return $data[$key] ?? $default;
        };

        $nomorStambuk = "";
        $isExistNomorStambuk = false;
        $tempIncr = 0;
        $jenisKelamin = Helper::transformGenderToInt($getData('jemaat_jenis_kelamin'));
        $tanggalLahir = Helper::yearMonthDayDateFormat($getData('jemaat_tanggal_lahir'));
        $tanggalBaptis = Helper::yearMonthDateFormat($getData('jemaat_tanggal_baptis'));
        $increment = Helper::incrementPadRight($tempIncr, 3);

        do {
            $tempIncr++;
            $increment = Helper::incrementPadRight($tempIncr, 3);
            $nomorStambuk = $tanggalLahir . $tanggalBaptis . $jenisKelamin . $increment;
            $isExistNomorStambuk = Helper::checkIfExistNomorStambuk($nomorStambuk);
        } while ($isExistNomorStambuk);

        return $nomorStambuk;
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