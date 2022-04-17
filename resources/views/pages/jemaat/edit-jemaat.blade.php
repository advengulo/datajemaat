@extends('layouts.app', ['title' => 'Edit'])

@section('content')
    <!-- Single pro tab review Start-->
<div class="single-pro-review-area mg-tb-15">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="product-payment-inner-st">
                    <div class="row">
                        @if ($message = Session::get('update'))
                            <div class="col-md-12">
                                <div class="alert alert-info alert-block">
                                    <button type="button" class="close" data-dismiss="alert">×</button>
                                    <strong>{{ $message }}</strong>
                                </div>
                            </div>
                        @endif
                    </div>
                    <form action="{{route('jemaatupdate', $data_jemaat)}}" method="post" enctype="multipart/form-data" class="dropzone dropzone-custom needsclick add-professors" id="demo1-upload">
                        {{ csrf_field() }}
                        {{ method_field('PATCH') }} 
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" style="margin-bottom:4vh;margin-top:2vh">
                                <ul id="myTabedu1" class="tab-review-design">
                                    <li class="active"><a href="#description">Data Pribadi</a></li>
                                </ul>
                                <div id="myTabContent" class="tab-content custom-product-edit">
                                    <div class="product-tab-list tab-pane fade active in" id="description">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="review-content-section">
                                                    <div id="dropzone1" class="pro-ad">
                                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                    <div class="row">
                                                                        <div class="col-md-4" style="padding-right:0">
                                                                            <div class="form-group" style="">
                                                                                <input style="text-align:right" type="text" class="form-control" value="Nama" readonly="readonly">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-8" style="padding-left:0">
                                                                            <div class="form-group">
                                                                                <input style="border=0;" type="text" class="form-control" name="jemaat_nama" value="{{$data_jemaat->jemaat_nama}}">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-3" style="padding-right:0">
                                                                            <div class="form-group" style="">
                                                                                <input style="text-align:right" type="text" class="form-control" value="Gelar Depan" readonly="readonly">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-3" style="padding-left:0">
                                                                            <div class="form-group">
                                                                                <input style="border=0;" type="text" class="form-control" name="jemaat_gelar_depan" value="{{$data_jemaat->jemaat_gelar_depan}}">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-3" style="padding-right:0">
                                                                            <div class="form-group" style="">
                                                                                <input style="text-align:right" type="text" class="form-control" value="Gelar Belakang" readonly="readonly">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-3" style="padding-left:0">
                                                                            <div class="form-group">
                                                                                <input style="border=0;" type="text" class="form-control" name="jemaat_gelar_belakang" value="{{$data_jemaat->jemaat_gelar_belakang}}">
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
                                                                                <input style="border=0;" type="text" class="form-control" name="jemaat_nomor_stambuk" value="{{$data_jemaat->jemaat_nomor_stambuk}}" readonly="readonly">
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
                                                                                <input style="border=0;" type="text" class="form-control" name="jemaat_nama_alias" value="{{$data_jemaat->jemaat_nama_alias}}">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-4" style="padding-right:0">
                                                                            <div class="form-group" style="">
                                                                                <input style="text-align:right" type="text" class="form-control" value="Tempat Tanggal Lahir" readonly="readonly">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4" style="padding-left:0">
                                                                            <div class="form-group">
                                                                                <input style="border=0;" type="text" class="form-control" name="jemaat_tempat_lahir" value="{{$data_jemaat->jemaat_tempat_lahir }}">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-1" style="padding-left:0">
                                                                            <div class="form-group">
                                                                                <div class="form-control" style="width:46px;"><i class="fa fa-calendar" style="font-size:26px"></i></div>                                                                    
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-3" style="padding-left:0">
                                                                            <div class="form-group">
                                                                                <input class="datepicker form-control"  type="text" name="jemaat_tanggal_lahir" value="{{$data_jemaat->jemaat_tanggal_lahir->format('d-m-Y')}}"  required>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-4" style="padding-right:0">
                                                                            <div class="form-group" style="">
                                                                                <input style="text-align:right" type="text" class="form-control"  value="Jenis Kelamin" readonly="readonly">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-8" style="padding-left:0">
                                                                            
                                                                                {{-- <input style="border=0;" type="text" class="form-control" name="jemaat_jenis_kelamin" value="{{$data_jemaat->jemaat_jenis_kelamin}}"> --}}
                                                                                <div class="form-group-inner" style="margin: 10px 0">
                                                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                                        <div class="i-checks pull-left" style="width:100px; height:40px; padding: 8px 0px;">
                                                                                            <input type="radio" @if($data_jemaat->jemaat_jenis_kelamin == "l")checked="" @endif value="l" name="jemaat_jenis_kelamin"> <i></i> Laki-Laki
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                                                        <div class="i-checks pull-left" style="width:100px; height:40px; padding: 8px 0px;">
                                                                                            <input type="radio" @if($data_jemaat->jemaat_jenis_kelamin == "p")checked="" @endif value="p" name="jemaat_jenis_kelamin"> <i></i> Perempuan
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            
                                                                            
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-4" style="padding-right:0">
                                                                            <div class="form-group" style="">
                                                                                <input style="text-align:right" type="text" class="form-control" value="Status Perkawinan" readonly="readonly">
                                                                            </div>
                                                                            
                                                                        </div>
                                                                        <div class="col-md-8" style="padding-left:0">
                                                                            <div class="form-group">
                                                                                {{-- <input style="border=0;" type="text" class="form-control" name="jemaat_status_perkawinan" value="{{ $data_jemaat->jemaat_status_perkawinan}}"> --}}
                                                                                <select class="form-control" name="jemaat_status_perkawinan">
                                                                                    <option @if($data_jemaat->jemaat_status_perkawinan == "l") selected="" @endif value="1">Kawin</option>
                                                                                    <option @if($data_jemaat->jemaat_status_perkawinan == "2") selected="" @endif value="2">Belum Kawin</option>
                                                                                    <option @if($data_jemaat->jemaat_status_perkawinan == "3") selected="" @endif value="3">Duda</option>
                                                                                    <option @if($data_jemaat->jemaat_status_perkawinan == "4") selected="" @endif value="4">Janda</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-4" style="padding-right:0">
                                                                            <div class="form-group" style="">
                                                                                <input style="text-align:right" type="text" class="form-control" value="Tanggal Perkawinan" readonly="readonly">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-8" style="padding-left:0">
                                                                            <div class="form-group">
                                                                                {{-- <input style="border=0;" type="text" class="form-control" name="jemaat_tanggal_perkawinan" value="{{$data_jemaat->jemaat_tanggal_perkawinan}}"> --}}
                                                                                <input class="datepicker form-control" type="text" name="jemaat_tanggal_perkawinan" value="@if($data_jemaat->jemaat_tanggal_perkawinan != null) {{$data_jemaat->jemaat_tanggal_perkawinan->format('d-m-Y')}} @endif" placeholder="-">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-4" style="padding-right:0">
                                                                            <div class="form-group" style="">
                                                                                <input style="text-align:right" type="text" class="form-control" value="Tanggal Baptis" readonly="readonly">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-8" style="padding-left:0">
                                                                            <div class="form-group">
                                                                                {{-- <input style="border=0;" type="text" class="form-control" name="jemaat_tanggal_baptis" value="{{$data_jemaat->jemaat_tanggal_baptis}}"> --}}
                                                                                <input class="datepicker form-control" type="text" name="jemaat_tanggal_baptis" value="@if($data_jemaat->jemaat_tanggal_baptis != null){{$data_jemaat->jemaat_tanggal_baptis->format('d-m-Y')}} @endif" placeholder="-">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-4" style="padding-right:0">
                                                                            <div class="form-group" style="">
                                                                                <input style="text-align:right" type="text" class="form-control" value="Tanggal Sidi" readonly="readonly">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-8" style="padding-left:0">
                                                                            <div class="form-group">
                                                                                {{-- <input style="border=0;" type="text" class="form-control" name="jemaat_tanggal_sidi" value="{{$data_jemaat->jemaat_tanggal_sidi}}"> --}}
                                                                                <input class="datepicker form-control" type="text" name="jemaat_tanggal_sidi" value="@if($data_jemaat->jemaat_tanggal_sidi != null){{$data_jemaat->jemaat_tanggal_sidi->format('d-m-Y')}} @endif" placeholder="-">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-4" style="padding-right:0">
                                                                            <div class="form-group" style="">
                                                                                <input style="text-align:right" type="text" class="form-control" value="Tanggal Bergabung" readonly="readonly">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-8" style="padding-left:0">
                                                                            <div class="form-group">
                                                                                {{-- <input style="border=0;" type="text" class="form-control" name="jemaat_tanggal_bergabung" value="{{$data_jemaat->jemaat_tanggal_bergabung}}"> --}}
                                                                                <input class="datepicker form-control" type="text" name="jemaat_tanggal_bergabung" value="{{$data_jemaat->jemaat_tanggal_bergabung->format('d-m-Y')}}" placeholder="-">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-4" style="padding-right:0">
                                                                            <div class="form-group" style="">
                                                                                <input style="text-align:right" type="text" class="form-control" value="Pendidikan Akhir" readonly="readonly">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-8" style="padding-left:0">
                                                                            <div class="form-group">
                                                                                <select class="form-control" name="id_pendidikan_akhir">
                                                                                    @foreach($data_pendidikans as $data_pendidikan)
                                                                                        <option @if($data_pendidikan->id == $data_jemaat->id_pendidikan_akhir) selected="" @endif value="{{$data_pendidikan->id}}">{{$data_pendidikan->nama_pendidikan}}</option>
                                                                                    @endforeach                                                                        
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-4" style="padding-right:0">
                                                                            <div class="form-group" style="">
                                                                                <input style="text-align:right" type="text" class="form-control" value="Lingkungan" readonly="readonly">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-8" style="padding-left:0">
                                                                            <div class="form-group">
                                                                                <input style="border=0;" type="text" class="form-control" name="id_lingkungan" value="{{$data_jemaat->id_lingkungan}}">
                                                                                {{-- <select class="form-control selectpicker" name="id_lingkungan" data-live-search="true">
                                                                                    @foreach($data_lingkungans as $data_lingkungan)
                                                                                        <option @if($data_lingkungan->nomor_lingkungan == $data_jemaat->id_lingkungan) selected="" @endif value="{{$data_lingkungan->nomor_lingkungan}}" data-tokens="{{$data_lingkungan->nomor_lingkungan}}">{{$data_lingkungan->nomor_lingkungan}} - {{$data_lingkungan->nama_lingkungan}}</option>
                                                                                    @endforeach
                                                                                </select> --}}
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-4" style="padding-right:0">
                                                                            <div class="form-group" style="">
                                                                                <input style="text-align:right" type="text" class="form-control"  value="Alamat" readonly="readonly">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-8" style="padding-left:0">
                                                                            <div class="form-group">
                                                                                <input style="border=0;" type="text" class="form-control" name="jemaat_alamat_rumah" value="{{$data_jemaat->jemaat_alamat_rumah}}">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-4" style="padding-right:0">
                                                                            <div class="form-group" style="">
                                                                                <input style="text-align:right" type="text" class="form-control" value="No Telp" readonly="readonly">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-8" style="padding-left:0">
                                                                            <div class="form-group">
                                                                                <input style="border=0;" type="text" class="form-control" name="jemaat_nomor_hp" value="{{$data_jemaat->jemaat_nomor_hp}}">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-4" style="padding-right:0">
                                                                            <div class="form-group" style="">
                                                                                <input style="text-align:right" type="text" class="form-control" value="Email" readonly="readonly">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-8" style="padding-left:0">
                                                                            <div class="form-group">
                                                                                <input style="border=0;" type="text" class="form-control" name="jemaat_email" value="{{$data_jemaat->jemaat_email}}">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-4" style="padding-right:0">
                                                                            <div class="form-group" style="">
                                                                                <input style="text-align:right" type="text" class="form-control" value="Pekerjaan" readonly="readonly">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-8" style="padding-left:0">
                                                                            <div class="form-group">
                                                                                {{-- <input style="border=0;" type="text" class="form-control" name="id_pekerjaan" value="{{$data_jemaat->id_pekerjaan}}"> --}}
                                                                                <select class="form-control" name="id_pekerjaan">                                                                        
                                                                                    @foreach($data_pekerjaans as $data_pekerjaan)
                                                                                        <option @if($data_pekerjaan->id == $data_jemaat->id_pekerjaan) selected="" @endif value="{{$data_pekerjaan->id}}">{{$data_pekerjaan->jenis_pekerjaan}}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-4" style="padding-right:0">
                                                                            <div class="form-group" style="">
                                                                                <input style="text-align:right" type="text" class="form-control" value="Golongan Darah" readonly="readonly">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-8" style="padding-left:0">
                                                                            <div class="form-group">
                                                                                <input style="border=0;" type="text" class="form-control" name="jemaat_golongan_darah" value="{{$data_jemaat->jemaat_golongan_darah}}">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                    
                                                                </div>
                                                            <div class="row">
                                                                <div class="col-lg-12">
                                                                    <div class="payment-adress">
                                                                        <button type="submit" class="btn btn-primary waves-effect waves-light">Simpan</button>
                                                                        <a href={{ route('profiledetail', $data_jemaat->id) }}><button type="button" class="btn btn-primary waves-effect waves-light">Batal</button></a>
                                                                        
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="product-tab-list tab-pane fade" id="reviews">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="review-content-section">
                                                    <div class="row">
                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            <div class="devit-card-custom">
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control" placeholder="Email" value="Admin@gmail.com">
                                                                </div>
                                                                <div class="form-group">
                                                                    <input type="number" class="form-control" placeholder="Phone" value="01962067309">
                                                                </div>
                                                                <div class="form-group">
                                                                    <input type="password" class="form-control" placeholder="Password" value="#123#123">
                                                                </div>
                                                                <div class="form-group">
                                                                    <input type="password" class="form-control" placeholder="Confirm Password" value="#123#123">
                                                                </div>
                                                                <a href="#!" class="btn btn-primary waves-effect waves-light">Submit</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="product-tab-list tab-pane fade" id="INFORMATION">
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
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" style="margin-bottom:4vh;margin-top:2vh">
                                <ul id="myTabedu1" class="tab-review-design">
                                    <li class="active"><a href="#description">Data Keluarga</a></li>
                                </ul>
                                <div id="myTabContent" class="tab-content custom-product-edit">
                                    <div class="product-tab-list tab-pane fade active in" id="description">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="review-content-section">
                                                    <div id="dropzone1" class="pro-ad">
                                                        <div class="row">
                                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                <div class="row">
                                                                    <div class="col-md-4" style="padding-right:0">
                                                                        <div class="form-group" style="">
                                                                            <input style="text-align:right" type="text" class="form-control" value="Status Dengan KK" readonly="readonly">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-8" style="padding-left:0">
                                                                        <div class="form-group">
                                                                            <select class="form-control" name="jemaat_status_dikeluarga">
                                                                                <option @if($data_jemaat->jemaat_status_dikeluarga == "1") selected="" @endif value="1">Kepala Keluarga</option>
                                                                                <option @if($data_jemaat->jemaat_status_dikeluarga == "2") selected="" @endif value="2">Istri</option>
                                                                                <option @if($data_jemaat->jemaat_status_dikeluarga == "3") selected="" @endif value="3">Anak</option>
                                                                                <option @if($data_jemaat->jemaat_status_dikeluarga == "4") selected="" @endif value="4">Adik Kandung</option>
                                                                                <option @if($data_jemaat->jemaat_status_dikeluarga == "5") selected="" @endif value="5">Famili</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-4" style="padding-right:0">
                                                                        <div class="form-group" style="">
                                                                            <input style="text-align:right" type="text" class="form-control" value="Pilih Kepala Keluarga" readonly="readonly">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-8" style="padding-left:0">
                                                                        <div class="form-group">
                                                                            <select id="nameid" class="form-control" name="id_parent" style="padding:0px;">
                                                                                <option disabled selected></option>
                                                                                @foreach($dataKK as $data)
                                                                                    <option @if($data->id_parent == $data_jemaat->id_parent) selected="" @endif value="{{$data->id}}" {{ old('id_parent') == $data->id ? 'selected' : '' }}>{{$data->jemaat_nama}} ({{$data->jemaat_nama_alias ?? "-"}})</option>
                                                                                @endforeach                                                                        
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-4" style="padding-right:0">
                                                                        <div class="form-group" style="">
                                                                            <input style="text-align:right" type="text" class="form-control" value="Nama Ayah" readonly="readonly">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-8" style="padding-left:0">
                                                                        <div class="form-group">
                                                                            <input style="border=0;" type="text" class="form-control" name="namaAyah" value="{{$data_keluarga->nama_ayah}}">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-4" style="padding-right:0">
                                                                        <div class="form-group" style="">
                                                                            <input style="text-align:right" type="text" class="form-control" value="Nama Ibu" readonly="readonly">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-8" style="padding-left:0">
                                                                        <div class="form-group">
                                                                            <input style="border=0;" type="text" class="form-control" name="namaIbu" value="{{$data_keluarga->nama_ibu}}">
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
                    {{ csrf_field() }}
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">

    $('.datepicker').datepicker({

        format: 'dd-mm-yyyy',
        endDate: '+0d',
        autoclose: true
    }); 
</script> 

<script type="text/javascript">
    $(function() {
        $('.selectpicker').selectpicker();
    });
</script> 

<script type="text/javascript">

    $("#nameid").select2({
          placeholder: "Pilih KK"
      });
</script>

@endsection