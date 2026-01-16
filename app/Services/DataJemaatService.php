<?php

namespace App\Services;

use DB, Helper;
use App\Models\data_jemaat;
use Illuminate\Http\Request;
use App\Repositories\DataJemaatRepository;
use App\Services\Workflow\JemaatDraftService;
use Illuminate\Support\Facades\Auth;


class DataJemaatService
{
    private $jemaatRepo;
    private $draftService;

    public function __construct(
        DataJemaatRepository $jemaatRepo,
        JemaatDraftService $draftService
    ) {
        $this->jemaatRepo = $jemaatRepo;
        $this->draftService = $draftService;
    }

    public function storeDataJemaat($data): array
    {
        // Convert Request object to array if needed
        $dataArray = is_object($data) ? $data->all() : $data;

        if ($this->canPublishDirectly()) {
            // Superadmin: direct insert
            $jemaat = $this->jemaatRepo->storeDataJemaat($data);
            return ['jemaat' => $jemaat, 'isDraft' => false];
        } else {
            // Non-superadmin: create draft and submit for review
            $draft = $this->draftService->createJemaatDraft($dataArray);
            $this->draftService->submitForReview($draft);
            return ['draft' => $draft, 'isDraft' => true];
        }
    }

    public function updateDataJemaat($data, $id): array
    {
        // Convert Request object to array if needed
        $dataArray = is_object($data) ? $data->all() : $data;

        if ($this->canPublishDirectly()) {
            // Superadmin: direct update
            $jemaat = $this->jemaatRepo->updateDataJemaat($dataArray, $id);

            if ($jemaat->wasChanged('jemaat_tanggal_lahir') || $jemaat->wasChanged('jemaat_tanggal_baptis') || $jemaat->wasChanged('jemaat_jenis_kelamin')) {
                // Generate new nomor stambuk
                $newNomorStambuk = $this->generateNomorStambuk($dataArray);

                // Update nomor stambuk in table Data_Jemaat
                $this->jemaatRepo->updateNomorStambuk($id, $newNomorStambuk);

                // Update nomor stambuk in table DataKeluarga
                $this->jemaatRepo->updateNoStambukInDataKeluarga($dataArray['jemaat_nomor_stambuk'], $newNomorStambuk);
            }

            if ($jemaat->jemaat_kk_status == true && $jemaat->wasChanged('id_lingkungan')) {
                data_jemaat::getChildByParentId($jemaat->id_parent)->update(['id_lingkungan' => $dataArray['id_lingkungan']]);
            }

            if ($jemaat->jemaat_kk_status == true && $jemaat->wasChanged('jemaat_alamat_rumah')) {
                data_jemaat::getChildByParentId($jemaat->id_parent)->update(['jemaat_alamat_rumah' => $dataArray['jemaat_alamat_rumah']]);
            }

            return ['jemaat' => $jemaat, 'isDraft' => false];
        } else {
            // Non-superadmin: create draft and submit for review
            $draft = $this->draftService->createJemaatDraft($dataArray, $id);
            $this->draftService->submitForReview($draft);
            return ['draft' => $draft, 'isDraft' => true];
        }
    }

    private function canPublishDirectly(): bool
    {
        return Auth::check() && Auth::user()->hasRole('superadmin');
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
