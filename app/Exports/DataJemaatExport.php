<?php

namespace App\Exports;

use App\Models\data_jemaat as DataJemaat;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class DataJemaatExport implements FromCollection, WithHeadings, WithMapping, WithColumnFormatting, ShouldAutoSize
{
    private $i = 1;
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return DataJemaat::where('jemaat_status_aktif','t')
            ->with('pekerjaan','pendidikan', 'statusdikeluarga','lingkungan','datakeluarga')
            ->orderBy('id_lingkungan','asc')
            ->orderBy('id_parent','asc')
            ->orderBy('jemaat_status_dikeluarga','asc')
            ->get();
    }

    public function map($datajemaat): array
    {
        return [
            $this->i++,
            $datajemaat->jemaat_nomor_stambuk .' ',
            $this->namaGelar($datajemaat->jemaat_nama, $datajemaat->jemaat_gelar_depan, $datajemaat->jemaat_gelar_belakang),
            $datajemaat->jemaat_nama_alias,
            $datajemaat->jemaat_alamat_rumah,
            $datajemaat->statusdikeluarga->nama_status_dikeluarga,
            $datajemaat->lingkungan->nomor_lingkungan .' - '. $datajemaat->lingkungan->nama_lingkungan,
            $datajemaat->jemaat_jenis_kelamin == 'l' ? 'Laki-laki' : 'Perempuan',
            $datajemaat->jemaat_tempat_lahir,
            Date::dateTimeToExcel($datajemaat->jemaat_tanggal_lahir),
            $this->transformDate($datajemaat->jemaat_tanggal_baptis),
            $this->transformDate($datajemaat->jemaat_tanggal_sidi),
            $this->transformMarriage($datajemaat->jemaat_status_perkawinan),
            $this->transformDate($datajemaat->jemaat_tanggal_perkawinan),
            $datajemaat->pekerjaan->jenis_pekerjaan,
            $datajemaat->pendidikan->nama_pendidikan,
            $datajemaat->datakeluarga->nama_ayah,
            $datajemaat->datakeluarga->nama_ibu,
            ""
        ];
    }

    public function headings(): array
    {
        return [
            'NO',
            'NOMOR STAMBUK',
            'NAMA JEMAAT',
            'NAMA ALIAS',
            'ALAMAT',
            'STATUS DIKELUARGA',
            'LINGKUNGAN',
            'JENIS KELAMIN',
            'TEMPAT LAHIR',
            'TANGGAL LAHIR',
            'TANGGAL BAPTIS',
            'TANGGAL SIDI',
            'STATUS PERKAWINAN',
            'TANGGAL PERKAWINAN',
            'PEKERJAAN',
            'PENDIDKAN TERAKHIR',
            'NAMA AYAH',
            'NAMA IBU',
            'KETERANGAN'
        ];
    }

    public function columnFormats(): array
    {
        return [
            'J' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'K' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'L' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'N' => NumberFormat::FORMAT_DATE_DDMMYYYY,
        ];
    }

    public function transformDate($value){
        if(!strlen($value)) return null;
    
        return Date::dateTimeToExcel($value);
    }
    public function transformMarriage($value)
    {
        if($value == 1){
            return "Menikah";
        }
        elseif($value == 2){
            return "Belum Menikah";
        }
        elseif($value == 3){
            return "Duda";
        }
        elseif($value == 4){
            return "Janda";
        }
    }
    public function namaGelar($nama, $gelarDepan, $gelarBelakang)
    {
        if($gelarDepan != null && $gelarBelakang != null){
            return $gelarDepan . '. ' .$nama . ', ' . $gelarBelakang; 
        }
        elseif($gelarDepan == null && $gelarBelakang != null){
            return $nama . ', ' . $gelarBelakang; 
        }
        elseif($gelarDepan != null && $gelarBelakang == null){
            return $gelarDepan . '. ' .$nama; 
        }
        else{
            return $nama;
        }
    }
}
