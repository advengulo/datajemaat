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

class KepalaKeluargaSimpatisanExport implements FromCollection, WithHeadings, WithMapping, WithColumnFormatting, ShouldAutoSize
{
    private $i = 1;
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return DataJemaat::where('jemaat_status_aktif','t')->where('jemaat_kk_status', true)
            ->where('is_simpatisan', true)
            ->with('pekerjaan','pendidikan', 'statusdikeluarga','lingkungan','datakeluarga')
            ->orderBy('id_lingkungan','asc')
            ->orderBy('jemaat_nama','asc')
            ->get();
    }

    public function map($datajemaat): array
    {
        return [
            $this->i++,
            $datajemaat->jemaat_nomor_stambuk .' ',
            $datajemaat->jemaat_nama,
            $datajemaat->jemaat_nama_alias,
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
            'STATUS DIKELUARGA',
            'LINGKUNGAN',
            'JENIS KELAMIN',
            'TEMPAT LAHIR',
            'TANGGAL LAHIR',
            'BAPTIS',
            'SIDI',
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
            'G' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'H' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'I' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'J' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'L' => NumberFormat::FORMAT_DATE_DDMMYYYY,
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
}
