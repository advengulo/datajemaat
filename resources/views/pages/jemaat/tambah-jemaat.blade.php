@extends('layouts.app')

@section('content')
    <!-- Single pro tab review Start-->
<div class="single-pro-review-area mg-tb-15">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="product-payment-inner-st">
                    <form action="{{route('tambahdatajemaat')}}" method="post" enctype="multipart/form-data" class="dropzone dropzone-custom needsclick add-professors" id="demo1-upload">
                        {{ csrf_field() }}
                        <div class="row">
                            @if ($errors->any())                                                       
                                @foreach ($errors->all() as $error)
                                    <div class="col-md-12">
                                        <div class="alert alert-danger alert-block" style="margin-bottom:0px;">
                                            <button type="button" class="close" data-dismiss="alert">×</button>
                                            <strong>{{ $error }}</strong>
                                        </div>
                                    </div>
                                @endforeach                                                            
                            @endif
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" style="margin-bottom:4vh;margin-top:2vh">
                                <ul id="myTabedu1" class="tab-review-design">
                                    <li class="active"><a href="#description">Data Pribadi</a></li>
                                </ul>
                                <div id="myTabContent" class="tab-content custom-product-edit" style="padding-top:15px">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="all-form-element-inner">
                                                <div class="form-group-inner">
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                            <label class="login2 pull-right pull-right-pro">Nama</label>
                                                        </div>
                                                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                                            <input type="text" class="form-control" name="jemaat_nama" value="{{ old('jemaat_nama') }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group-inner">
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-0"></div>
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6">
                                                            <input type="text" class="form-control" name="jemaat_gelar_depan" placeholder="Gelar Depan" value="{{ old('jemaat_gelar_depan') }}">
                                                        </div>
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6">
                                                            <input type="text" class="form-control" name="jemaat_gelar_belakang" placeholder="Gelar Belakang" value="{{ old('jemaat_gelar_belakang') }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group-inner">
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                            <label class="login2 pull-right pull-right-pro">Nama Alias</label>
                                                        </div>
                                                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                                            <input type="text" class="form-control" name="jemaat_nama_alias" value="{{ old('jemaat_nama_alias') }}">                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group-inner">
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                            <label class="login2 pull-right pull-right-pro">Jenis Kelamin</label>
                                                        </div>
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6">
                                                            <div class="i-checks pull-left" style="width:100px; height:40px; padding: 8px 0px;">
                                                                <input type="radio" value="l" {{ old('jemaat_jenis_kelamin') == 'l' ? 'checked' : '' }} name="jemaat_jenis_kelamin"> <i></i> Laki-Laki
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6">
                                                            <div class="i-checks pull-left" style="width:100px; height:40px; padding: 8px 0px;">
                                                                <input type="radio" value="p" {{ old('jemaat_jenis_kelamin') == 'p' ? 'checked' : '' }} name="jemaat_jenis_kelamin"> <i></i> Perempuan
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group-inner">
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                            <label class="login2 pull-right pull-right-pro">Tempat Tanggal Lahir</label>
                                                        </div>
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6">
                                                            <input type="text" class="form-control" name="jemaat_tempat_lahir" placeholder="Tempat Lahir" value="{{ old('jemaat_tempat_lahir') }}">
                                                    </div>
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6">
                                                            <input class="datepicker form-control"  type="text" name="jemaat_tanggal_lahir" placeholder="dd/mm/yyyy" value="{{ old('jemaat_tanggal_lahir') }}">
                                                    </div>
                                                    </div>
                                                </div>
                                                <div class="form-group-inner">
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                            <label class="login2 pull-right pull-right-pro">Tanggal Baptis</label>
                                                        </div>
                                                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                                            <input class="datepicker form-control" type="text" name="jemaat_tanggal_baptis" value="{{ old('jemaat_tanggal_baptis') }}" placeholder="">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group-inner">
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                            <label class="login2 pull-right pull-right-pro">Tanggal Sidi</label>
                                                        </div>
                                                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                                            <input class="datepicker form-control" type="text" name="jemaat_tanggal_sidi" value="{{ old('jemaat_tanggal_sidi') }}" placeholder="">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group-inner">
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                            <label class="login2 pull-right pull-right-pro">Status Perkawinan</label>
                                                        </div>
                                                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                                             <select class="form-control" name="jemaat_status_perkawinan">
                                                                <option disabled selected>--Pilih Status Perkawinan--</option>
                                                                <option value="1" {{ old('jemaat_status_perkawinan') == 1 ? 'selected' : '' }}>Kawin</option>
                                                                <option value="2" {{ old('jemaat_status_perkawinan') == 2 ? 'selected' : '' }}>Belum Kawin</option>
                                                                <option value="3" {{ old('jemaat_status_perkawinan') == 3 ? 'selected' : '' }}>Duda</option>
                                                                <option value="4" {{ old('jemaat_status_perkawinan') == 4 ? 'selected' : '' }}>Janda</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group-inner">
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                            <label class="login2 pull-right pull-right-pro">Tanggal Perkawinan</label>
                                                        </div>
                                                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                                            <input class="datepicker form-control" type="text" name="jemaat_tanggal_perkawinan" value="{{ old('jemaat_tanggal_perkawinan') }}" placeholder="">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group-inner">
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                            <label class="login2 pull-right pull-right-pro">Tanggal Bergabung</label>
                                                        </div>
                                                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                                            <input class="datepicker form-control" type="text" name="jemaat_tanggal_bergabung" value="{{ old('jemaat_tanggal_bergabung') }}" placeholder="">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group-inner">
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                            <label class="login2 pull-right pull-right-pro">Nomor Lingkungan</label>
                                                        </div>
                                                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                                            <input style="border=0;" type="text" class="form-control" name="id_lingkungan" placeholder="Isi Nomor Lingkungan*" value="{{ old('id_lingkungan') }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group-inner">
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                            <label class="login2 pull-right pull-right-pro">Pekerjaan</label>
                                                        </div>
                                                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                                            <select class="form-control" name="id_pekerjaan">
                                                                <option disabled selected>--Pilih Pekerjaan--</option>
                                                                @foreach($data_pekerjaans as $data_pekerjaan)
                                                                    <option value="{{$data_pekerjaan->id}}" {{ old('id_pekerjaan') == $data_pekerjaan->id ? 'selected' : '' }}>{{$data_pekerjaan->jenis_pekerjaan}}</option>
                                                                @endforeach                                                                        
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group-inner">
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                            <label class="login2 pull-right pull-right-pro">Pendidikan Akhir</label>
                                                        </div>
                                                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                                            <select class="form-control" name="id_pendidikan_akhir">
                                                                <option disabled selected>--Pilih Pendidikan Akhir--</option>
                                                                @foreach($data_pendidikans as $data_pendidikan)
                                                                    <option value="{{$data_pendidikan->id}}" {{ old('id_pendidikan_akhir') == $data_pendidikan->id ? 'selected' : '' }}>{{$data_pendidikan->nama_pendidikan}}</option>
                                                                @endforeach                                                                        
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group-inner">
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                            <label class="login2 pull-right pull-right-pro">Alamat</label>
                                                        </div>
                                                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                                            <input style="border=0;" type="text" class="form-control" name="jemaat_alamat_rumah" value="{{ old('jemaat_alamat_rumah') }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group-inner">
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                            <label class="login2 pull-right pull-right-pro">No Telp</label>
                                                        </div>
                                                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                                            <input style="border=0;" type="text" class="form-control" name="jemaat_nomor_hp" value="{{ old('jemaat_nomor_hp') }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group-inner">
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                            <label class="login2 pull-right pull-right-pro">Email</label>
                                                        </div>
                                                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                                            <input style="border=0;" type="email" pattern="[^ @]*@[^ @]*" class="form-control" name="jemaat_email" value="{{ old('jemaat_email') }}">                                                        
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group-inner">
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                            <label class="login2 pull-right pull-right-pro">Golongan Darah</label>
                                                        </div>
                                                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                                            <input type="text" class="form-control" name="jemaat_golongan_darah" value="{{ old('jemaat_golongan_darah') }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" style="margin-top:2vh">
                                <ul id="myTabedu1" class="tab-review-design">
                                    <li class="active"><a href="#keluarga">Data Keluarga</a></li>
                                </ul>
                                <div id="myTabContent" class="tab-content custom-product-edit" style="padding-top:15px ">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom:4vh; ">
                                            <div class="all-form-element-inner">
                                                <div class="form-group-inner">
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                            <label class="login2 pull-right pull-right-pro">Kepala Keluarga </label>                                                            
                                                        </div>
                                                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                                            <div class="pull-left" id="is-kk" style="width:100px; height:40px; padding: 8px 0px;">
                                                                <label>
                                                                    <input type="radio" id="kk-true" value="1" {{ old('jemaat_kk_status') == 1 ? 'checked' : '' }} name="jemaat_kk_status"> <i></i> Ya 
                                                                </label>
                                                                <label>    
                                                                    <input type="radio" id="kk-false" value="0" {{ old('jemaat_kk_status') == 0 ? 'checked' : '' }} name="jemaat_kk_status"> <i></i> Bukan 
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group-inner" id="div-status-dikeluarga">
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                            <label class="login2 pull-right pull-right-pro">Status dengan KK</label>
                                                        </div>
                                                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                                            <select class="form-control" name="jemaat_status_dikeluarga" id="jemaat_status_dikeluarga">
                                                                <option selected disabled>--Status dikeluarga--</option>
                                                                <option value="1" {{ old('jemaat_status_dikeluarga') == 1 ? 'selected' : '' }}>Kepala Keluarga</option>
                                                                <option value="2" {{ old('jemaat_status_dikeluarga') == 2 ? 'selected' : '' }}>Istri</option>
                                                                <option value="3" {{ old('jemaat_status_dikeluarga') == 3 ? 'selected' : '' }}>Anak</option>
                                                                <option value="4" {{ old('jemaat_status_dikeluarga') == 4 ? 'selected' : '' }}>Famili</option>
                                                                <option value="5" {{ old('jemaat_status_dikeluarga') == 5 ? 'selected' : '' }}>Saudara Kandung</option>
                                                                <option value="6" {{ old('jemaat_status_dikeluarga') == 6 ? 'selected' : '' }}>Orang Tua</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group-inner" id="pilihKK">
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                            <label class="login2 pull-right pull-right-pro">Pilih Kepala Keluarga</label>
                                                        </div>
                                                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                                            <select id="select2_kk" class="form-control" name="id_parent" style="padding:0px;">
                                                                <option disabled selected></option>
                                                                @foreach($dataKK as $data)
                                                                    <option value="{{$data->id}}" {{ old('id_parent') == $data->id ? 'selected' : '' }}>{{$data->jemaat_nama}} ({{$data->jemaat_nama_alias ?? "-"}})</option>
                                                                @endforeach                                                                        
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group-inner" id="pilih_ayah">
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                            <label class="login2 pull-right pull-right-pro">Pilih Ayah</label>
                                                        </div>
                                                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                                            <select id="pilih_ayah" class="form-control" name="id_ayah" style="padding:0px;">
                                                                <option disabled selected></option>
                                                                @foreach($dataAyah as $data)
                                                                    <option value="{{$data->id}}" {{ old('id_ayah') == $data->id ? 'selected' : '' }}>{{$data->jemaat_nama}} ({{$data->jemaat_nama_alias ?? "-"}})</option>
                                                                @endforeach                                                                        
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group-inner" id="pilih_ibu">
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                            <label class="login2 pull-right pull-right-pro">Pilih Ibu</label>
                                                        </div>
                                                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                                            <select id="pilih_ibu" class="form-control" name="id_ibu" style="padding:0px;">
                                                                <option disabled selected></option>
                                                                @foreach($dataIbu as $data)
                                                                    <option value="{{$data->id}}" {{ old('id_ibu') == $data->id ? 'selected' : '' }}>{{$data->jemaat_nama}} ({{$data->jemaat_nama_alias ?? "-"}})</option>
                                                                @endforeach                                                                        
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <hr>
                                                <span>Input manual data orangtua</span><br>
                                                <small>*Digunakan jika data ayah dan ibu belum terdaftar di sistem</small>
                                                <div class="form-group-inner">
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                            <label class="login2 pull-right pull-right-pro">Nama Ayah</label>
                                                        </div>
                                                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                                            <input type="text" class="form-control" name="namaAyah" value="{{ old('namaAyah') }}">                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group-inner">
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                            <label class="login2 pull-right pull-right-pro">Nama Ibu</label>
                                                        </div>
                                                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                                            <input type="text" class="form-control" name="namaIbu" value="{{ old('namaIbu') }}">                                                                                                                    
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group-inner">
                                    <div class="login-btn-inner">
                                        <div class="row">
                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 col-lg-offset-5 col-md-offset-5 col-sm-offset-5" >
                                                <button class="btn btn-primary waves-effect waves-light btn-block" type="submit">Simpan</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>                
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
    $(document).ready(function() {
        $('#is-kk').on('click', function() {
        if($('#kk-true').is(':checked')) {
            $('#pilihKK').hide();
            $("#jemaat_status_dikeluarga").val("1").change();
            $('#div-status-dikeluarga').hide();
        } else {
            $('#pilihKK').show();
            $('#jemaat_status_dikeluarga').prop('selectedIndex',0);        
            $("#div-status-dikeluarga").show();
        }
        })

        if($('#kk-true').is(':checked')) {
            $('#pilihKK').hide();
            $("#jemaat_status_dikeluarga").val("1").change();
            $('#div-status-dikeluarga').hide();
        }

        $("select#select2_kk").select2({
            placeholder: "Pilih kepala keluarga"
        });

        $("select#pilih_ayah").select2({
            placeholder: "Pilih Ayah"
        });

        $("select#pilih_ibu").select2({
            placeholder: "Pilih Ibu"
        });

        $('.selectpicker').selectpicker();

        $('.datepicker').datepicker({
            format: 'dd-mm-yyyy'
        }); 
    });
</script>


@endsection