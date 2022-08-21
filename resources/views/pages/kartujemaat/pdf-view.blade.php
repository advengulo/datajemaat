<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <style type="text/css">
        /* Style Baru */
        @page {
            margin: 0.5cm 1cm 0.5cm 1cm;
            border : 0.5px solid red;
        }
        .tablepdf{
            font-family: Serif;
            border-collapse: collapse;
            width: 100%;
            padding : 0;
            margin : 0;
            clear: left;
        }
        .tablepdf td,
        .tablepdf th,
        .tablepdf tbody {
            border: 1px solid #000;
            font-size: 10px;
            padding: 0 0 0 2px;
        }

        .row {
            width: 100%;
        }
        .col-md-1{
            width: 8.333333333333333%;
            float:left;
        }
        .col-md-2{
            width: 16.66666666666667%;
            float:left;
        }
        .col-md-3{
            width: 25%;
            float:left;
        }
        .col-md-4{
            width: 33.33333333333333%;
            float:left;
        }
        .col-md-5{
            width: 41.66666666666666%;
            float:left;
        }
        .col-md-6{
            width: 50%;
            float:left;
        }

        #fixed {
            position: fixed;
            bottom: 98px;
            /* border : 1px solid greenyellow; */
        }
        
        /* Yang lama */
        h1{
            display: inline-block;
            font-size: 21px;
            font-weight:800;
            letter-spacing : -1px;
            transform: scale(1.13, 2.4);
        }
        .logo{
            width: 70px;
            height: 105px;
            margin: 0 0 0 40px;
        }

        .table {
            font-size: 11px;
        }
        .table tbody tr td {
            /* padding : 2px; */
            vertical-align: middle;            
        }
        #head{
            font-weight: bold;
            text-align: center;
        }
        #bluehead{
            background-color: rgba(146, 145, 145, 0.678);
        }
        #bluehead td{
            text-align: center;
            font-style: italic;
            font-size: 10px;
        }

       .table td[rowspan] {
            vertical-align: middle;
       }
       .table td[colspan] {
            vertical-align: middle;
       }
       .table tr td {
            vertical-align: middle;
       }
    </style>
</head>

<body style="text-transform: uppercase;">
<!-- Static Table Start -->
{{-- <div class="col-md-12"> --}}
    <div class="row">
        <div class="col-md-3">
            <img  src="img/logo-bnkp-pdf.jpg" class="logo" alt="">
        </div>
        <div class="col-md-6" style="text-align:center">
            <h1 style="margin-left:0.4cm; margin-top:0px; padding-top:0px">KARTU KELUARGA BNKP JEMAAT KOTA GUNUNGSITOLI</h1> <br>
            <h2 style="font-size: 18pt; font-weight:bold; text-align:center; margin-top:5px">NOMOR : {{$nomor_kartu}}</h2>
        </div>
    </div>

    <div class=row style="clear: both;">
        <div class="col-md-3" style="height:20px; width: 230px; padding-left:35px;">
            <h5 style="font-size:11px; margin-top:5px; ">NAMA KEPALA KELUARGA </h5> <br>
        </div>
        <div class="col-md-5" style="height:20px; padding-left:1px;">
            <h5 style="font-size:10pt; margin-top:5px; font-weight:900"><span style="font-weight:normal">:</span> {{$data_jemaat->jemaat_nama}} </h5> <br>
        </div>
        <div class="col-md-1" style="height:20px; width:137px; padding-left:30px;">
            <h5 style="font-size:11px; margin-top:5px; ">LINGKUNGAN NOMOR</h5> <br>
        </div>
        <div class="col-md-3" style="height:20px;">
            <h5 style="font-size:11px; margin-top:5px; ">: {{$data_jemaat->id_lingkungan}} {{$data_jemaat->lingkungan->nama_lingkungan}}</h5> <br>
        </div>
    </div>
    <div class="row" style="clear: both;">
        <div class="col-md-3" style="height:20px; width: 230px; padding-left:35px;">
            <h5 style="font-size:11px; margin-top:0px; ">ALAMAT LENGKAP/DESA/LURAH</h5> <br>
        </div>
        <div class="col-md-5" style="height:20px; padding-left:1px;">
            <h5 style="font-size:11px; margin-top:0px; ">: {{$data_jemaat->jemaat_alamat_rumah}}</h5> <br>
        </div>
        <div class="col-md-1" style="height:20px; width:137px; padding-left:30px;">
            <h5 style="font-size:11px; margin-top:0px; ">SNK Lingkungan</h5> <br>
        </div>
        <div class="col-md-3" style="height:20px;">
            <h5 style="font-size:11px; margin-top:0px; ">: SNK.{{$data_jemaat->lingkungan->nama_snk}}</h5> <br>
        </div>
    </div>
  
    <table class="tablepdf">
        <tbody>
            <tr id="head">
                <td  rowspan="2" width="3%">NO URUT </td>
                <td  rowspan="2" width="22%">NAMA LENGKAP <div style="line-height:40%;">
                    <br>
                </div> NAMA PANGGILAN ALIAS</td>
                <td  rowspan="2" width="11%">STATUS HUBUNGAN DENGAN KEPALA KELUARGA</td>
                <td  rowspan="2" width="11%">JENIS KELAMIN <br> L/P </td>
                <td  colspan="2"> LAHIR</td>
                <td colspan="2">TGL/BLN/THN </td>
                <td  rowspan="2" width="9%"> STATUS PERKAWINAN</td>
                <td  rowspan="2" width="11%"> TANGGAL PERKAWINAN</td>
            </tr>
            <tr id="head">
                <td  width="11%">TEMPAT </td>
                <td  width="11%">TGL/BLN/THN</td>
                <td  width="11%">BAPTIS</td>
                <td  width="11%">SIDI</td>
            </tr>
            <tr id="bluehead">
                <td></td>
                <td>1</td>
                <td>2</td>
                <td>3</td>
                <td>4</td>
                <td>5</td>
                <td>6</td>
                <td>7</td>
                <td>8</td>
                <td>9</td>
            </tr>

            <?php $i=1 ?>
            @foreach ($dataKartuKeluargas as $dataKartuKeluarga)
            <tr>
                <td style="text-align:center;" @if($dataKartuKeluarga->jemaat_nama_alias != null) rowspan="2" @endif>{{$i}}</td>
                <td>
                    @if($dataKartuKeluarga->jemaat_gelar_depan !="" && $dataKartuKeluarga->jemaat_gelar_belakang !=""){{$dataKartuKeluarga->jemaat_gelar_depan}}.{{$dataKartuKeluarga->jemaat_nama}},{{$dataKartuKeluarga->jemaat_gelar_belakang}} @elseif($dataKartuKeluarga->jemaat_gelar_depan !=""){{$dataKartuKeluarga->jemaat_gelar_depan}}.{{$dataKartuKeluarga->jemaat_nama}},@elseif($dataKartuKeluarga->jemaat_gelar_belakang !=""){{$dataKartuKeluarga->jemaat_nama}},{{$dataKartuKeluarga->jemaat_gelar_belakang}}@else{{$dataKartuKeluarga->jemaat_nama}}
                    @endif
                </td>
                <td @if($dataKartuKeluarga->jemaat_nama_alias != null) rowspan="2" @endif>
                    @if($dataKartuKeluarga->jemaat_status_dikeluarga == 1 || $dataKartuKeluarga->jemaat_status_dikeluarga == 0 && $dataKartuKeluarga->jemaat_kk_status==true) KEPALA KELUARGA
                        @elseif ($dataKartuKeluarga->jemaat_status_dikeluarga == 2) ISTRI
                        @elseif ($dataKartuKeluarga->jemaat_status_dikeluarga == 3) ANAK
                        @elseif ($dataKartuKeluarga->jemaat_status_dikeluarga == 4) FAMILI
                        @elseif ($dataKartuKeluarga->jemaat_status_dikeluarga == 5) SAUDARA KANDUNG
                        @elseif ($dataKartuKeluarga->jemaat_status_dikeluarga == 6) ORANG TUA
                    @endif
                </td>
                <td @if($dataKartuKeluarga->jemaat_nama_alias != null) rowspan="2" @endif>@if($dataKartuKeluarga->jemaat_jenis_kelamin == "l") LAKI-LAKI
                    @else PEREMPUAN
                    @endif
                </td>
                <td @if($dataKartuKeluarga->jemaat_nama_alias != null) rowspan="2" @endif>{{$dataKartuKeluarga->jemaat_tempat_lahir}}</td>
                <td @if($dataKartuKeluarga->jemaat_nama_alias != null) rowspan="2" @endif>
                    @if($dataKartuKeluarga->jemaat_tanggal_lahir != null){{$dataKartuKeluarga->jemaat_tanggal_lahir->formatLocalized('%d %B %Y')}}
                    @else -
                    @endif
                </td>
                <td @if($dataKartuKeluarga->jemaat_nama_alias != null) rowspan="2" @endif>
                    @if($dataKartuKeluarga->jemaat_tanggal_baptis != null){{$dataKartuKeluarga->jemaat_tanggal_baptis->formatLocalized('%d %B %Y')}}
                    @else -
                    @endif
                </td>
                <td @if($dataKartuKeluarga->jemaat_nama_alias != null) rowspan="2" @endif>
                    @if($dataKartuKeluarga->jemaat_tanggal_sidi != null){{$dataKartuKeluarga->jemaat_tanggal_sidi->formatLocalized('%d %B %Y')}}
                    @else -
                    @endif
                </td>
                <td @if($dataKartuKeluarga->jemaat_nama_alias != null) rowspan="2" @endif>
                    @if($dataKartuKeluarga->jemaat_status_perkawinan==1) KAWIN @elseif($dataKartuKeluarga->jemaat_status_perkawinan==2) BELUM KAWIN @elseif($dataKartuKeluarga->jemaat_status_perkawinan==3) DUDA @elseif($dataKartuKeluarga->jemaat_status_perkawinan==4) JANDA @endif
                </td>
                <td @if($dataKartuKeluarga->jemaat_nama_alias != null) rowspan="2" @endif>
                    @if($dataKartuKeluarga->jemaat_tanggal_perkawinan != null) {{$dataKartuKeluarga->jemaat_tanggal_perkawinan->formatLocalized('%d %B %Y')}}
                    @else -
                    @endif
                </td>
            </tr>
            @if($dataKartuKeluarga->jemaat_nama_alias != null)
            <tr>
                <td>{{$dataKartuKeluarga->jemaat_nama_alias}}</td>
            </tr>
            @endif
            <?php $i++ ?>
            @endforeach
            @if($i < 10)
                @for($j=$i; $j <= 10; $j++)
                    <tr>
                        <td style="text-align:center;">{{$j}}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                @endfor
            @else
            @endif
            <tr>
                <td colspan="10" height="10px"></td>
            </tr>
            <tr id="head">
                <td rowspan="2">NO URUT </td>
                <td rowspan="2">PEKERJAAN</td>
                <td rowspan="2">PENDIDIKAN TERAKHIR</td>
                <td colspan="4">NAMA</td>
                <td rowspan="2">STAMBUK NOMOR</td>
                <td rowspan="2">TGL/BLN/THN MENINGGAL</td>
                <td rowspan="2">KETERANGAN</td>
            </tr>
            <tr id="head">
                <td colspan="2">AYAH</td>
                <td colspan="2">IBU</td>
            </tr>
            <tr id="bluehead">
                <td></td>
                <td>10</td>
                <td>11</td>
                <td colspan="2">12</td>
                <td colspan="2">13</td>
                <td>14</td>
                <td>15</td>
                <td>16</td>
            </tr>
            <?php $i=1 ?>
            @foreach ($dataKartuKeluargas as $dataKartuKeluarga)
                <tr>
                    <td style="text-align:center">{{$i}}</td>
                    <td>{{$dataKartuKeluarga->pekerjaan->jenis_pekerjaan}}</td>
                    <td>
                        @if($dataKartuKeluarga->id_pendidikan_akhir != null){{$dataKartuKeluarga->pendidikan->nama_pendidikan}}
                        @else -
                        @endif
                    </td>
                        <td colspan="2">
                            @if (!empty($dataKartuKeluarga->datakeluarga->ayah))
                                @if (!empty($dataKartuKeluarga->datakeluarga->ayah->riwayatinaktif))
                                    @if ($dataKartuKeluarga->datakeluarga->ayah->riwayatinaktif->jemaat_keterangan_status == "Meninggal")
                                        {{$dataKartuKeluarga->datakeluarga->ayah->jemaat_nama."(Alm)"}}
                                    @endif
                                @else
                                    {{$dataKartuKeluarga->datakeluarga->ayah->jemaat_nama}}
                                @endif
                            @else
                                {{ $dataKartuKeluarga->datakeluarga->ayah->jemaat_nama ?? $dataKartuKeluarga->datakeluarga->nama_ayah ?? "-"}}
                            @endif
                        </td>
                        <td colspan="2">
                            @if (!empty($dataKartuKeluarga->datakeluarga->ibu))
                                @if (!empty($dataKartuKeluarga->datakeluarga->ibu->riwayatinaktif))
                                    @if ($dataKartuKeluarga->datakeluarga->ibu->riwayatinaktif->jemaat_keterangan_status == "Meninggal")
                                        {{$dataKartuKeluarga->datakeluarga->ibu->jemaat_nama."(Alm)"}}
                                    @endif
                                @else
                                    {{$dataKartuKeluarga->datakeluarga->ibu->jemaat_nama}}
                                @endif
                            @else
                                {{ $dataKartuKeluarga->datakeluarga->ibu->jemaat_nama ?? $dataKartuKeluarga->datakeluarga->nama_ibu ?? "-"}}
                            @endif
                        </td>
                    <td>{{$dataKartuKeluarga->jemaat_nomor_stambuk}}</td>
                    <td>
                        @if($dataKartuKeluarga->jemaat_tanggal_status != null){{$$dataKartuKeluarga->jemaat_tanggal_status->formatLocalized('%d %B %Y')}}
                        @else -
                        @endif
                    </td>
                    <td>-</td>
                </tr>
            <?php $i++ ?>
            @endforeach
            @if($i < 10)
                @for($j=$i; $j <= 10; $j++)
                    <tr id="tablecontent">
                        <td style="text-align:center;">{{$j}}</td>
                        <td></td>
                        <td></td>
                        <td colspan="2"></td>
                        <td colspan="2"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                @endfor
            @else
            @endif
        </tbody>
    </table>
    <div class="row" style="margin-left:35px;font-size:11px; text-transform:none">
        <div class="col-md-4">
            <p> Dikeluarkan tanggal : 10 November 2020 <br>
            Keterangan : <br>
            -  Lembar 1 : Untuk Kepala Keluarga yang bersangkutan (Warna Kuning) <br>
            -  Lembar 2 : Untuk BPMJ/sekretaris Jemaat (Warna Putih) <br>
            -  Lembar 3 : Untuk Database (Warna Biru) <br>
            -  Lembar 4 : Untuk SNK Lingkungan (Warna Merah)
            </p>
        </div>
        <div class="col-md-2">
            <p> 
                <br>
                Keterangan Nomor Stambuk : <br>
                - Digit 1 - 4 &nbsp;&nbsp;: Tahun Lahir <br>
                - Digit 5 - 6 &nbsp;&nbsp;: Bulan Lahir <br>
                - Digit 7 - 8 &nbsp;&nbsp;: Tanggal Lahir <br>
                - Digit 9 - 12 : Tahun Baptis
            </p>
        </div>
        <div class="col-md-2">
            <p> 
                <br>
                <br>
                - Digit 13 - 14 : Bulan Baptis <br>
                - Digit 15 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: Jenis Kelamin <br>
                - Digit 16 - 18 : Nomor Urut
            </p>
        </div>
        
    </div>

    <div class="row" id="fixed">
    <div class="row" style="font-size:11px;">
        <div class="col-md-6" style="text-align:center; margin-bottom:55px">Kepala Keluarga</div>
        <div class="col-md-6" style="text-align:center; margin-bottom:55px">Badan Pekerja majelis jemaat kota gunungsitoli </div>
        
    </div>
    <div class="row" style="font-size:11px; clear:left">
        <div class="col-md-6" style="text-align:center;">
            @if($data_jemaat->jemaat_gelar_depan !="" && $data_jemaat->jemaat_gelar_belakang !=""){{$data_jemaat->jemaat_gelar_depan}}.{{$data_jemaat->jemaat_nama}},{{$data_jemaat->jemaat_gelar_belakang}} @elseif($data_jemaat->jemaat_gelar_depan !=""){{$data_jemaat->jemaat_gelar_depan}}.{{$data_jemaat->jemaat_nama}},@elseif($data_jemaat->jemaat_gelar_belakang !=""){{$data_jemaat->jemaat_nama}},{{$data_jemaat->jemaat_gelar_belakang}}@else{{$data_jemaat->jemaat_nama}}
            @endif
        </div>
        <div class="col-md-3" style="text-align:center; text-transform:none">SNK dr. VICTOR K.F. TEL, Sp.B. <br> KETUA</div>
        <div class="col-md-3" style="text-align:center;">SNK Asanudin TELAUMBANUA <br> SEKRETARIS</div>
    </div>
    </div>              
{{-- </div> --}}
</body>

</html>