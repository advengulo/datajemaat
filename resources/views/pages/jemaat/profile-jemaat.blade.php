@section('scriptshead')
<script type="text/javascript">
    $(document).ready(function(){
        $('a[data-toggle="tab"]').on('show.bs.tab', function(e) {
            localStorage.setItem('activeTab', $(e.target).attr('href'));
        });
        var activeTab = localStorage.getItem('activeTab');
        if(activeTab){
            $('#myTabedu1 a[href="' + activeTab + '"]').tab('show');
            window.localStorage.removeItem("activeTab");
        }
    });
    </script>
@endsection
@extends('layouts.app', ['title' => 'Profile'])

@section('content')
    <!-- Single pro tab review Start-->
<div class="single-pro-review-area mg-tb-15">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="product-payment-inner-st">
                    <ul id="myTabedu1" class="tab-review-design">
                        <li class="active"><a data-toggle="tab" href="#data_pribadi">Data Pribadi</a></li>
                        <li><a data-toggle="tab" href="#keluarga"> Data Keluarga</a></li>
                        {{-- <li><a data-toggle="tab" href="#komisi">Data Komisi</a></li> --}}
                    </ul>
                    <div id="myTabContent" class="tab-content custom-product-edit">
                        <div class="product-tab-list tab-pane fade active in" id="data_pribadi">
                            <div class="row" style="border : 1px solid rgba(197, 197, 197, 0.937);">

                                <div class="col-md-12 mt-3" style="border-bottom : 1px solid rgba(197, 197, 197, 0.937);">
                                    <div class="col-md-6 col-sm-12 col-md-offset-3">
                                        <div class="profile-data">
                                            <div class="profile-label">Nama</div>
                                            <div class="profile-value">
                                                @if($data_jemaat->jemaat_gelar_depan == "")
                                                @else{{$data_jemaat->jemaat_gelar_depan}}. 
                                                @endif{{$data_jemaat->jemaat_nama}}
                                                @if($data_jemaat->jemaat_gelar_belakang == "")
                                                @else, {{$data_jemaat->jemaat_gelar_belakang}}. 
                                                @endif
                                            </div>
                                        </div>
                                        <div class="profile-data">
                                            <div class="profile-label">Nomor Stambuk</div>
                                            <div class="profile-value">{{$data_jemaat->jemaat_nomor_stambuk}}</div>
                                        </div>
                                        <div class="profile-data mb-3">
                                            <div class="profile-label">Nama Alias</div>
                                            <div class="profile-value">{{$data_jemaat->jemaat_nama_alias}}</div>
                                        </div>
                                    </div>
                                </div>

                                <!--Button Action -->
                                <div class="col-md-12 mt-2">
                                    @if ($message = Session::get('update'))
                                        <div class="col-md-12">
                                            <div class="alert alert-info alert-block">
                                                <button type="button" class="close" data-dismiss="alert">×</button>
                                                <strong>{{ $message }}</strong>
                                            </div>
                                        </div>
                                    @elseif ($message = Session::get('error'))
                                        <div class="col-md-12">
                                            <div class="alert alert-danger alert-block">
                                                <button type="button" class="close" data-dismiss="alert">×</button>
                                                <strong>{{ $message }}</strong>
                                            </div>
                                        </div>
                                    @elseif ($message = Session::get('warning'))
                                        <div class="col-md-12">
                                            <div class="alert alert-warning alert-block">
                                                <button type="button" class="close" data-dismiss="alert">×</button>
                                                <strong>{{ $message }}</strong>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                @if($data_jemaat->jemaat_status_aktif == "t")
                                <div class="col-md-12 d-flex justify-content-between mt-2 mb-3">

                                    <div class="col-md-3">
                                        <a href="#" data-toggle="modal" data-target="#jadikankkmodal"> <button type="button" {{ $data_jemaat->jemaat_kk_status == true ? 'disabled' : '' }} class="btn btn-info btn-block">Jadikan KK</button></a>
                                    </div>
                                    <div class="col-md-3">
                                        <a href={{ route('jemaateditprofile', $data_jemaat) }}> <button type="button" class="btn btn-warning btn-block">Edit</button></a>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="dropdown">
                                            <button class="btn btn-primary btn-block dropdown-toggle" type="button" data-toggle="dropdown">Ubah Status Jemaat
                                                <span class="caret"></span></button>
                                            <ul class="dropdown-menu">
                                                <li><a href="#" data-toggle="modal" data-target="#PrimaryModalalert">Pindah</a></li>
                                                <li><a href="#" data-toggle="modal" data-target="#DangerModalalert">Meninggal</a></li>
                                                <li><a href="#" data-toggle="modal" data-target="#ModalJemaatSimpatisan">Simpatisan</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <a href="#" data-toggle="modal" data-target="#DangerModalalert1">
                                            <button type="button" class="btn btn-danger btn-block">Hapus </button>
                                        </a>
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 col-md-offset-6">
                                        <div id="DangerModalalert1" class="modal modal-edu-general FullColor-popup-DangerModal fade" role="dialog">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form action="{{ route('hapusdatajemaat', $data_jemaat)}}" method="POST"  class="dropzone dropzone-custom needsclick add-professors" id="demo1-upload">
                                                        {{ csrf_field() }}
                                                        {{ method_field('PATCH') }}
                                                    <div class="modal-close-area modal-close-df">
                                                        <a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Hapus Data : </p><h4>{{$data_jemaat->jemaat_nama}}</h4>
                                                    </div>
                                                    <div class="modal-footer danger-md">
                                                        <button type="submit" class="btn btn-danger">Hapus</button>
                                                    </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="jadikankkmodal" class="modal modal-edu-general FullColor-popup-InfoModal fade" role="dialog">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form action="{{ route('jadikankk', $data_jemaat)}}" method="POST"  class="dropzone dropzone-custom needsclick add-professors" id="demo1-upload">
                                                        {{ csrf_field() }}
                                                        {{ method_field('PATCH') }}
                                                        <div class="modal-close-area modal-close-df">
                                                            <a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Jadikan Kepala Keluarga : </p><h4>{{$data_jemaat->jemaat_nama}}</h4>
                                                        </div>
                                                        <div class="modal-footer info-md">
                                                            <button type="submit" class="btn btn-info">Simpan</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!--Wrapper Modals -->
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <div id="PrimaryModalalert" class="modal modal-edu-general default-popup-PrimaryModal fade" role="dialog"> 
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form action="{{ route('updatestatuspindah', $data_jemaat)}}" method="POST"  class="dropzone dropzone-custom needsclick add-professors" id="demo1-upload">
                                                        {{ csrf_field() }}
                                                        {{ method_field('PATCH') }}
                                                        <div class="modal-close-area modal-close-df">
                                                            <a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
                                                        </div>
                                                        <div class="modal-body">                                                                            
                                                            <h4>Update Status Jemaat - <span style="text-transform: uppercase">{{$data_jemaat->jemaat_nama}}</span> (Pindah)</h4>
                                                            <div class="row">
                                                                <div class="col-md-4" style="padding-right:0">
                                                                    <div class="form-group">
                                                                        <input style="text-align:right" type="text" class="form-control" value="Tanggal" readonly="readonly">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-8" style="padding-left:0">
                                                                    <div class="form-group">
                                                                        <input class="datepicker form-control" type="text" name="jemaat_tanggal_status" placeholder="yyyy-mm-dd" required autocomplete="off">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-4" style="padding-right:0">
                                                                    <div class="form-group">
                                                                        <input style="text-align:right" type="text" class="form-control" value="Pindah Ke" readonly="readonly">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-8" style="padding-left:0">
                                                                    <div class="form-group">
                                                                        <input class="form-control" type="text" name="jemaat_pindah_ke" autocomplete="off" required placeholder="Jemaat/Gereja">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">

                                                            <button type="submit" class="btn btn-primary waves-effect waves-light">Simpan</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div id="DangerModalalert" class="modal modal-edu-general FullColor-popup-DangerModal fade" role="dialog">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form action="{{ route('updatestatusmeninggal', $data_jemaat)}}" method="POST"  class="dropzone dropzone-custom needsclick add-professors" id="demo1-upload">
                                                        {{ csrf_field() }}
                                                        {{ method_field('PATCH') }}
                                                    <div class="modal-close-area modal-close-df">
                                                        <a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
                                                    </div>
                                                    <div class="modal-body">
                                                        <h4>Update Status Jemaat - <span style="text-transform: uppercase">{{$data_jemaat->jemaat_nama}}</span> (Meninggal)</h4>
                                                        <div class="row">
                                                            <div class="form-group">
                                                                <div class="col-md-4" style="padding-right:0">
                                                                    <input style="text-align:right" type="text" class="form-control" value="Tanggal Meninggal" readonly="readonly">
                                                                </div>
                                                                <div class="col-md-8" style="padding-left:0">
                                                                    <input class="datepicker form-control" type="text" name="jemaat_tanggal_status" autocomplete="off" placeholder="yyyy-mm-dd" required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="form-group">
                                                                <div class="col-md-4" style="padding-right:0">
                                                                    <input style="text-align:right" type="text" class="form-control" value="Tgl Dikebumikan" readonly="readonly">
                                                                </div>
                                                                <div class="col-md-8" style="padding-left:0">
                                                                    <input class="datepicker form-control" type="text" name="jemaat_tanggal_dikebumikan" autocomplete="off" placeholder="yyyy-mm-dd" required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer danger-md">
                                                        <button type="submit" class="btn btn-danger">Simpan</button>
                                                    </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <div id="ModalJemaatSimpatisan" class="modal modal-edu-general Customwidth-popup-WarningModal fade" role="dialog"> 
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form action="{{ route('updateStatusSimpatisan', $data_jemaat)}}" method="POST"  class="dropzone dropzone-custom needsclick add-professors" id="demo1-upload">
                                                        {{ csrf_field() }}
                                                        {{ method_field('PATCH') }}
                                                        <div class="modal-close-area modal-close-df">
                                                            <a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
                                                        </div>
                                                        <div class="modal-body">                                                                       
                                                            <h4>Ubah <span style="text-transform: uppercase">{{$data_jemaat->jemaat_nama}}</span> menjadi Simpatisan</h4>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-success">Ubah</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--End of wrapper Modals -->
                                </div>
                                @endif
                                <!-- End of Button Action -->

                                <!--Detai Jemaat -->
                                <div class="col-md-6 col-sm-12">
                                    <div class="profile-data">
                                        <div class="profile-label">Tempat Tanggal Lahir</div>
                                        <div class="profile-value">{{$data_jemaat->jemaat_tempat_lahir}}, {{$data_jemaat->jemaat_tanggal_lahir->formatLocalized('%d %B %Y')}}</div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <div class="profile-data">
                                        <div class="profile-label">Umur</div>
                                        <div class="profile-value">{{$data_jemaat->getAge()}}</div>
                                    </div>
                                </div>

                                <div class="col-md-6 col-sm-12">
                                    <div class="profile-data">
                                        <div class="profile-label">Jenis Kelamin</div>
                                        <div class="profile-value">
                                            @if($data_jemaat->jemaat_jenis_kelamin == "l") Laki-laki 
                                            @else Perempuan 
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12 col-sm-12"></div>

                                <div class="col-md-6 col-sm-12">
                                    <div class="profile-data">
                                        <div class="profile-label">Status Perkawinan</div>
                                        <div class="profile-value">
                                            @if($data_jemaat->jemaat_status_perkawinan==1) Kawin 
                                            @elseif($data_jemaat->jemaat_status_perkawinan==2) Belum Kawin 
                                            @elseif($data_jemaat->jemaat_status_perkawinan==3) Duda 
                                            @elseif($data_jemaat->jemaat_status_perkawinan==4) Janda 
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <div class="profile-data">
                                        <div class="profile-label">Tanggal Perkawinan</div>
                                        <div class="profile-value">
                                            @if($data_jemaat->jemaat_tanggal_perkawinan != null){{$data_jemaat->jemaat_tanggal_perkawinan->formatLocalized('%d %B %Y')}} 
                                            @else -
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 col-sm-12">
                                    <div class="profile-data">
                                        <div class="profile-label">Tanggal Baptis</div>
                                        <div class="profile-value">
                                            @if($data_jemaat->jemaat_tanggal_baptis != null){{$data_jemaat->jemaat_tanggal_baptis->formatLocalized('%d %B %Y')}} 
                                            @else - 
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <div class="profile-data">
                                        <div class="profile-label">Tanggal Sidi</div>
                                        <div class="profile-value">
                                            @if($data_jemaat->jemaat_tanggal_sidi != null){{$data_jemaat->jemaat_tanggal_sidi->formatLocalized('%d %B %Y')}} 
                                            @else - 
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 col-sm-12">
                                    <div class="profile-data">
                                        <div class="profile-label">Tanggal Bergabung</div>
                                        <div class="profile-value">
                                            @if($data_jemaat->jemaat_tanggal_bergabung != null){{$data_jemaat->jemaat_tanggal_bergabung->formatLocalized('%d %B %Y')}} 
                                            @else - 
                                            @endif
                                        </div>
                                    </div>
                                    <div class="profile-data">
                                        <div class="profile-label">Pendidikan Akhir</div>
                                        <div class="profile-value">{{$data_jemaat->pendidikan->nama_pendidikan}}</div>
                                    </div>
                                    <div class="profile-data">
                                        <div class="profile-label">Lingkungan</div>
                                        <div class="profile-value">{{$data_jemaat->id_lingkungan ?? ""}} - {{$data_jemaat->lingkungan->nama_lingkungan ?? ""}}</div>
                                    </div>
                                    <div class="profile-data">
                                        <div class="profile-label">Alamat</div>
                                        <div class="profile-value">{{$data_jemaat->jemaat_alamat_rumah}}</div>
                                    </div>
                                    <div class="profile-data">
                                        <div class="profile-label">No Telp</div>
                                        <div class="profile-value">{{$data_jemaat->jemaat_nomor_hp}}</div>
                                    </div>
                                    <div class="profile-data">
                                        <div class="profile-label">Email</div>
                                        <div class="profile-value">{{$data_jemaat->jemaat_email}}</div>
                                    </div>
                                    <div class="profile-data">
                                        <div class="profile-label">Pekerjaan</div>
                                        <div class="profile-value">{{$data_jemaat->pekerjaan->jenis_pekerjaan}}</div>
                                    </div>
                                    <div class="profile-data">
                                        <div class="profile-label">Status dengan KK</div>
                                        <div class="profile-value">
                                            @if($data_jemaat->jemaat_status_dikeluarga == 1) Kepala Keluarga
                                            @elseif ($data_jemaat->jemaat_status_dikeluarga == 2) Istri
                                            @elseif ($data_jemaat->jemaat_status_dikeluarga == 3) Anak
                                            @elseif ($data_jemaat->jemaat_status_dikeluarga == 4) Famili
                                            @elseif ($data_jemaat->jemaat_status_dikeluarga == 5) Saudara Kandung
                                            @elseif ($data_jemaat->jemaat_status_dikeluarga == 6) Orang Tua
                                            @endif
                                        </div>
                                    </div>
                                    <div class="profile-data">
                                        <div class="profile-label">Golongan Darah</div>
                                        <div class="profile-value">{{$data_jemaat->jemaat_golongan_darah}}</div>
                                    </div>
                                </div>


                                
                                
                                <!--End Of Detai Jemaat -->


                                {{-- <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="review-content-section">
                                        <div id="dropzone1" class="pro-ad">

                                                <div class="row" style="border : 1px solid grey;">
                                                    <!--Detail Top -->
                                                    <div class="col-md-12" style="border-bottom : 1px solid grey; margin-bottom: 20px; margin-top: 20px;">
                                                        <div class="col-md-6 col-md-offset-3">
                                                            <div class="row">
                                                                <div class="col-md-4" style="padding-right:0">
                                                                    <div class="form-group" style="">
                                                                        <input style="text-align:right" type="text" class="form-control" value="Nama" readonly="readonly">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-8" style="padding-left:0">
                                                                    <div class="form-group">
                                                                        <input style="border=0; background-color:white" readonly="readonly" type="text" class="form-control" 
                                                                        value="@if($data_jemaat->jemaat_gelar_depan == "")@else{{$data_jemaat->jemaat_gelar_depan}}. @endif{{$data_jemaat->jemaat_nama}}@if($data_jemaat->jemaat_gelar_belakang == "")@else, {{$data_jemaat->jemaat_gelar_belakang}}. @endif" readonly="readonly" >
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-4" style="padding-right:0">
                                                                    <div class="form-group" style="">
                                                                        <input style="text-align:right" type="text" class="form-control" value="Nomor Stambuk" readonly="readonly">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-8" style="padding-left:0">
                                                                    <div class="form-group">
                                                                        <input style="border=0; background-color:white" readonly="readonly" type="text" class="form-control" value="{{$data_jemaat->jemaat_nomor_stambuk}}" readonly="readonly">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-4" style="padding-right:0">
                                                                    <div class="form-group" style="">
                                                                        <input style="text-align:right" type="text" class="form-control" value="Nama Alias" readonly="readonly">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-8" style="padding-left:0">
                                                                    <div class="form-group" >
                                                                        <input style="border=0; background-color:white" readonly="readonly" type="text" class="form-control" value="{{$data_jemaat->jemaat_nama_alias}}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!--End of Detail Top -->

                                                    

                                                    <!--Detai Jemaat -->
                                                    <div class="col-md-12" style="margin-top:20px">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="col-md-4" style="padding-right:0">
                                                                    <div class="form-group" style="">
                                                                        <input style="text-align:right" type="text" class="form-control" value="Tempat Tanggal Lahir" readonly="readonly">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-8" style="padding-left:0">
                                                                    <div class="form-group">
                                                                    <input style="border=0; background-color:white" readonly="readonly" type="text" class="form-control" value="{{$data_jemaat->jemaat_tempat_lahir}}, {{$data_jemaat->jemaat_tanggal_lahir->formatLocalized('%d %B %Y')}}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="col-md-4" style="padding-right:0">
                                                                    <div class="form-group" style="">
                                                                        <input style="text-align:right" type="text" class="form-control" value="Umur" readonly="readonly">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-8" style="padding-left:0">
                                                                    <div class="form-group">
                                                                    <input style="border=0; background-color:white" readonly="readonly" type="text" class="form-control" value="{{$data_jemaat->getAge()}}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="col-md-4" style="padding-right:0">
                                                                    <div class="form-group" style="">
                                                                        <input style="text-align:right" type="text" class="form-control" value="Jenis Kelamin" readonly="readonly">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-8" style="padding-left:0">
                                                                    <div class="form-group">
                                                                        <input style="border=0; background-color:white" readonly="readonly" type="text" class="form-control" value="@if($data_jemaat->jemaat_jenis_kelamin == "l") Laki-laki @else Perempuan @endif ">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="col-md-4" style="padding-right:0">
                                                                    <div class="form-group" style="">
                                                                        <input style="text-align:right" type="text" class="form-control" value="Status Perkawinan" readonly="readonly">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-8" style="padding-left:0">
                                                                    <div class="form-group">
                                                                        <input style="border=0;  background-color:white" readonly="readonly" type="text" class="form-control" value="@if($data_jemaat->jemaat_status_perkawinan==1) Kawin @elseif($data_jemaat->jemaat_status_perkawinan==2) Belum Kawin @elseif($data_jemaat->jemaat_status_perkawinan==3) Duda @elseif($data_jemaat->jemaat_status_perkawinan==4) Janda @endif">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="col-md-6">
                                                                <div class="col-md-4" style="padding-right:0">
                                                                    <div class="form-group" style="">
                                                                        <input style="text-align:right" type="text" class="form-control" value="Tanggal Perkawinan" readonly="readonly">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-8" style="padding-left:0">
                                                                    <div class="form-group">
                                                                            <input style="border=0; background-color:white" readonly="readonly" type="text" class="form-control" value="@if($data_jemaat->jemaat_tanggal_perkawinan != null){{$data_jemaat->jemaat_tanggal_perkawinan->formatLocalized('%d %B %Y')}}@endif" placeholder="-">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="col-md-4" style="padding-right:0">
                                                                    <div class="form-group" style="">
                                                                        <input style="text-align:right" type="text" class="form-control" value="Tanggal Baptis" readonly="readonly">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-8" style="padding-left:0">
                                                                    <div class="form-group">
                                                                        <input style="border=0;  background-color:white" readonly="readonly" type="text" class="form-control" value="@if($data_jemaat->jemaat_tanggal_baptis != null){{$data_jemaat->jemaat_tanggal_baptis->formatLocalized('%d %B %Y')}} @else - @endif">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="col-md-6">
                                                                <div class="col-md-4" style="padding-right:0">
                                                                    <div class="form-group" style="">
                                                                        <input style="text-align:right" type="text" class="form-control" value="Tanggal Sidi" readonly="readonly">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-8" style="padding-left:0">
                                                                    <div class="form-group">
                                                                        <input style="border=0; background-color:white" readonly="readonly" type="text" class="form-control" value="@if($data_jemaat->jemaat_tanggal_sidi != null){{$data_jemaat->jemaat_tanggal_sidi->formatLocalized('%d %B %Y')}} @else - @endif">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="col-md-4" style="padding-right:0">
                                                                    <div class="form-group" style="">
                                                                        <input style="text-align:right" type="text" class="form-control" value="Tanggal Bergabung" readonly="readonly">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-8" style="padding-left:0">
                                                                    <div class="form-group">
                                                                        <input style="border=0;  background-color:white" readonly="readonly" type="text" class="form-control" value="@if($data_jemaat->jemaat_tanggal_bergabung != null){{$data_jemaat->jemaat_tanggal_bergabung->formatLocalized('%d %B %Y')}} @else - @endif">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="col-md-4" style="padding-right:0">
                                                                    <div class="form-group" style="">
                                                                        <input style="text-align:right" type="text" class="form-control" value="Pendidikan Akhir" readonly="readonly">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-8" style="padding-left:0">
                                                                    <div class="form-group">
                                                                        <input style="border=0; background-color:white" readonly="readonly" type="text" class="form-control" value="{{$data_jemaat->pendidikan->nama_pendidikan}}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="col-md-4" style="padding-right:0">
                                                                    <div class="form-group" style="">
                                                                        <input style="text-align:right" type="text" class="form-control" value="Lingkungan" readonly="readonly">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-8" style="padding-left:0">
                                                                    <div class="form-group">
                                                                        <input style="border=0; background-color:white" readonly="readonly" type="text" class="form-control" value="{{$data_jemaat->id_lingkungan ?? ""}} - {{$data_jemaat->lingkungan->nama_lingkungan ?? ""}} ">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="col-md-4" style="padding-right:0">
                                                                    <div class="form-group" style="">
                                                                        <input style="text-align:right" type="text" class="form-control" value="Alamat" readonly="readonly">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-8" style="padding-left:0">
                                                                    <div class="form-group">
                                                                        <input style="border=0; background-color:white" readonly="readonly" type="text" class="form-control" value="{{$data_jemaat->jemaat_alamat_rumah}}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="col-md-4" style="padding-right:0">
                                                                    <div class="form-group" style="">
                                                                        <input style="text-align:right" type="text" class="form-control" value="No Telp" readonly="readonly">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-8" style="padding-left:0">
                                                                    <div class="form-group">
                                                                        <input style="border=0; background-color:white" readonly="readonly" type="text" class="form-control" value="{{$data_jemaat->jemaat_nomor_hp}}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="col-md-4" style="padding-right:0">
                                                                    <div class="form-group" style="">
                                                                        <input style="text-align:right" type="text" class="form-control" value="Email" readonly="readonly">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-8" style="padding-left:0">
                                                                    <div class="form-group">
                                                                        <input style="border=0; background-color:white" readonly="readonly" type="text" class="form-control" value="{{$data_jemaat->jemaat_email}}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="col-md-4" style="padding-right:0">
                                                                    <div class="form-group" style="">
                                                                        <input style="text-align:right" type="text" class="form-control" value="Pekerjaan" readonly="readonly">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-8" style="padding-left:0">
                                                                    <div class="form-group">
                                                                        <input style="border=0; background-color:white" readonly="readonly" type="text" class="form-control" value="{{$data_jemaat->pekerjaan->jenis_pekerjaan}}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="col-md-4" style="padding-right:0">
                                                                    <div class="form-group" style="">
                                                                        <input style="text-align:right" type="text" class="form-control" value="Status dengan KK" readonly="readonly">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-8" style="padding-left:0">
                                                                    <div class="form-group">
                                                                        <input style="border=0; background-color:white" readonly="readonly" type="text" class="form-control" value="@if($data_jemaat->jemaat_status_dikeluarga == 1) Kepala Keluarga
                                                                            @elseif ($data_jemaat->jemaat_status_dikeluarga == 2) Istri
                                                                            @elseif ($data_jemaat->jemaat_status_dikeluarga == 3) Anak
                                                                            @elseif ($data_jemaat->jemaat_status_dikeluarga == 4) Famili
                                                                            @elseif ($data_jemaat->jemaat_status_dikeluarga == 5) Saudara Kandung
                                                                            @elseif ($data_jemaat->jemaat_status_dikeluarga == 6) Orang Tua
                                                                            @endif">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="col-md-4" style="padding-right:0">
                                                                    <div class="form-group" style="">
                                                                        <input style="text-align:right" type="text" class="form-control" value="Golongan Darah" readonly="readonly">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-8" style="padding-left:0">
                                                                    <div class="form-group">
                                                                        <input style="border=0; background-color:white" readonly="readonly" type="text" class="form-control" value="{{$data_jemaat->jemaat_golongan_darah}}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!--End Of Detail Jemaat -->
                                                </div>
                                            
                                        </div>
                                    </div>
                                </div> --}}
                            </div>
                        </div>
                        <div class="product-tab-list tab-pane fade" id="keluarga">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="product-status-wrap">
                                        <div class="asset-inner">
                                            <table>
                                                <thead>
                                                    <th>Nama</th>
                                                    <th>Status Keluarga</th>
                                                    <th>Aksi</th>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td id="nama_ayah_n">{{ $dataKeluarga->ayah->jemaat_nama ?? $dataKeluarga->nama_ayah}}</td>
                                                        <td>Ayah Kandung</td>
                                                        <td><a href="javascript:void(0)" id="edit_ayah" data-id="{{$dataKeluarga->id}}" class="btn btn-icon btn-sm btn-warning">Edit</a></td>
                                                    </tr>
                                                    <tr>
                                                        <td id="nama_ibu_n">{{ $dataKeluarga->ibu->jemaat_nama ?? $dataKeluarga->nama_ibu}}</td>
                                                        <td>Ibu Kandung</td>
                                                        <td><a href="javascript:void(0)" id="edit_ibu" data-id="{{$dataKeluarga->id}}" class="btn btn-icon btn-sm btn-warning">Edit</a></td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{$kepalaKeluarga->jemaat_nama}} - {{$kepalaKeluarga->jemaat_nomor_stambuk}}</td>
                                                        <td>Kepala Keluarga</td>
                                                        <td></td>
                                                    </tr>
                                                    
                                                </tbody>
                                                
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="product-tab-list tab-pane fade" id="komisi">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="review-content-section">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="devit-card-custom">
                                                    <div class="form-group">
                                                        <input type="url" class="form-control" placeholder="Facebook URL" value="http://www.facebook.com">
                                                    </div>
                                                    <div class="form-group">
                                                        <input type="url" class="form-control" placeholder="Twitter URL" value="http://www.twitter.com">
                                                    </div>
                                                    <div class="form-group">
                                                        <input type="url" class="form-control" placeholder="Google Plus" value="http://www.google-plus.com">
                                                    </div>
                                                    <div class="form-group">
                                                        <input type="url" class="form-control" placeholder="Linkedin URL" value="http://www.Linkedin.com">
                                                    </div>
                                                    <button type="submit" class="btn btn-primary waves-effect waves-light">Submit</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal_edit_ortu" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Ubah data Orangtua</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="updateOrtuForm">
            @csrf
            <input name="id" id="id" type="hidden">
          <div class="form-group">
            <label for="nama_ayah" class="col-form-label">Ayah</label>
            <input type="text" class="form-control" name="nama_ayah" id="nama_ayah">
          </div>
          <div class="form-group">
            <label for="nama_ibu" class="col-form-label">Ibu</label>
            <input type="text" class="form-control" name="nama_ibu" id="nama_ibu">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
        <button type="button" id="btnSaveOrtu" class="btn btn-primary">Simpan</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">

    $('.datepicker').datepicker({
        format: 'yyyy-mm-dd'
    });

    $(document).ready(function(){
        $('#edit_ayah, #edit_ibu').on('click', function(){
            var id = $(this).data('id')
            $('#updateOrtuForm')[0].reset();
            const na = $('#nama_ayah_n').text()
            const ni = $('#nama_ibu_n').text()
            $('#id').val(id)
            $('#nama_ayah').val(na)
            $('#nama_ibu').val(ni)
            $('#modal_edit_ortu').modal('show')
        })

        $('#btnSaveOrtu').on('click', function(){
            var id = $('#id').val()
            $.ajax({
                url : "/data-keluarga/" + id,
                type: "POST",
                data : $('#updateOrtuForm').serialize(),
                success: function(dataResult){
                    $('#updateOrtuForm').trigger("reset")
                    $('#modal_edit_ortu').modal('hide')
                    window.location.reload()
                    alert("Update Success")
                },
                error: function (err){
                    console.log(err)
                }
            })
        })
    })
</script> 
@endsection