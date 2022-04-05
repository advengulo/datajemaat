@extends('layouts.app')

@section('scriptshead')
<meta name="csrf-token" content="{{ csrf_token() }}">
    
@endsection

@section('content')
<!-- Static Table Start -->
<div class="data-table-area mg-tb-15">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="sparkline13-list">
                    <div class="sparkline13-hd">
                        <div class="main-sparkline13-hd row mg-b-30">
                            <div class="alert alert-danger print-error-msg" style="display:none">
                                <ul></ul>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <h1>Data Lingkungan</h1>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <button class="btn btn-primary pull-right" style="padding: 6px 32px" data-toggle="modal" data-target="#tambahdata">
                                    <i class="fas fa-plus" style="padding-right:20px"></i>
                                    Tambah Data
                                </button>
                            </div>
                        </div>
                        <div class="row">
                            @if ($message = Session::get('success'))
                                <div class="col-md-12">
                                    <div class="alert alert-info alert-block">
                                        <button type="button" class="close" data-dismiss="alert">×</button>
                                        <strong>{{ $message }}</strong>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    <!-- Modal  add data-->
                    <form action="{{route('lingkungan.store')}}" id="formLingkungan" method="post" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="modal fade" id="tambahdata" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLongTitle">Tambah Data Lingkungan</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>   
                                    </div>
                                    <div class="modal-body">                                    
                                        <div class="form-group">
                                            <label for="nomorlingkungan">Nomor Lingkungan</label>
                                            <input type="text" class="form-control" name="nomor_lingkungan" id="nomorlingkungan">
                                        </div>
                                        <div class="form-group">
                                            <label for="namalingkungan">Nama Lingkungan</label>
                                            <input type="text" class="form-control" name="nama_lingkungan" id="namalingkungan">
                                        </div>
                                        <div class="form-group">
                                            <label for="snk">Nama Satua Niha Keriso (SNK)</label>
                                            <input type="text" class="form-control" name="nama_snk" id="snk">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                        <button type="subbmit" class="btn btn-primary"><i class="fas fa-plus" style="padding-right:10px"></i>Tambah Data</button>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </form>
                    <!--  End of Modal -->

                    <!-- Modal update data-->
                    <form action="" id="formLingkunganUpdate" method="post" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        {{ method_field('PATCH') }} 
                        <div class="modal fade" id="updateData" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLongTitle">Update Data Lingkungan</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>   
                                    </div>
                                    <div class="modal-body">                                    
                                        <div class="form-group">
                                            <label for="nomorlingkungan">Nomor Lingkungan</label>
                                            <input type="text" disabled class="form-control" name="nomor_lingkungan" id="updateNoLing">
                                        </div>
                                        <div class="form-group">
                                            <label for="namalingkungan">Nama Lingkungan</label>
                                            <input type="text" disabled class="form-control" name="nama_lingkungan" id="updateNamaLing">
                                        </div>
                                        <div class="form-group">
                                            <label for="snk">Nama Satua Niha Keriso (SNK)</label>
                                            <input type="text" class="form-control" name="nama_snk" id="updateSnk">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                        <button type="subbmit" class="btn btn-info"><i class="fas fa-check-square" style="padding-right:10px"></i>Update Data</button>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </form>
                    <!--  End of Modal -->

                    <div class="sparkline13-graph">
                        <div class="table-responsive">
                            @if ($message = Session::get('warning'))
                                <div class="row">
                                    <div class="col-md-12">
                                    <div class="alert alert-warning alert-block">
                                        <button type="button" class="close" data-dismiss="alert">×</button>
                                        <strong>{{ $message }}</strong>
                                    </div>
                                    </div>
                                </div>
                            @elseif($message = Session::get('error'))
                                <div class="row">
                                    <div class="col-md-12">
                                    <div class="alert alert-danger alert-block">
                                        <button type="button" class="close" data-dismiss="alert">×</button>
                                        <strong>{{ $message }}</strong>
                                    </div>
                                    </div>
                                </div>
                            @endif                                
                            <table id="table" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        
                                        <th>Nomor</th>
                                        <th>Lingkungan</th>
                                        <th>Satua Niha Keriso (SNK)</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data_lingkungan as $lingkungan)
                                        <tr>
                                            <td style="text-align:center">{{$lingkungan->nomor_lingkungan}}</td>
                                            <td>{{$lingkungan->nama_lingkungan}}</td>
                                            <td>{{$lingkungan->nama_snk}}</td>
                                            <td style="text-align:center">
                                                <button class="btn btn-sm btn-warning"
                                                    id="btnEdit"
                                                    data-route="{{ route('lingkungan.update', $lingkungan->id) }}" 
                                                    data-noling="{{ $lingkungan->nomor_lingkungan }}"
                                                    data-namaling="{{ $lingkungan->nama_lingkungan }}"
                                                    data-namasnk="{{ $lingkungan->nama_snk }}"
                                                    >Edit</button>
                                                <form style="display: inline" action="{{ route('lingkungan.destroy', $lingkungan->id)}}" method="POST">
                                                    {{ csrf_field() }}
                                                    {{ method_field('PATCH') }}
                                                    <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash-alt"> Hapus </a>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 col-md-offset-6">
                        <div id="modalDestroy" class="modal modal-edu-general FullColor-popup-DangerModal fade" role="dialog">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('lingkungan.destroy', $lingkungan->id)}}" method="POST"  class="dropzone dropzone-custom needsclick add-professors" id="demo1-upload">
                                        {{ csrf_field() }}
                                        {{ method_field('PATCH') }}
                                    <div class="modal-close-area modal-close-df">
                                        <a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
                                    </div>
                                    <div class="modal-body">
                                        <p>Hapus Data : </p><h4></h4>
                                    </div>
                                    <div class="modal-footer danger-md">
                                        <button type="submit" class="btn btn-danger">Hapus</button>
                                    </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#table').DataTable( {
            "columnDefs": [
                { "width": "10%", "className": "dt-center", "targets": 0 },
                { "width": "30%", "targets": 1 },
                { "width": "40%", "targets": 2 },
                { "width": "20%", "className": "dt-center", "targets": 3 },
            ]
        });

        $('#btnEdit').click(function(){
            var route = $(this).data('route')
            $("#updateNoLing").val($(this).data('noling'))
            $("#updateNamaLing").val($(this).data('namaling'))
            $("#updateSnk").val($(this).data('namasnk'))
            $('#updateData').modal('show')
            $('#formLingkunganUpdate').prop('action', route);
        })
    });
</script>
@endsection