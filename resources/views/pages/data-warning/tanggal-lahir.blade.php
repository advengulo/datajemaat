@extends('layouts.app')

@section('content')
<!-- Static Table Start -->
<div class="data-table-area mg-tb-15">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="sparkline13-list">
                    <div class="sparkline13-hd">
                        <div class="main-sparkline13-hd">
                            <h1>Warning Tanggal Lahir</h1>
                        </div>
                        <p>Menampilkan data jemaat yang memiliki <b>Tahun lahir dibawah 1935</b> dan melebihi Tahun saat ini</p>
                    </div>
                    <div class="row mg-b-15">
                        <div class="col-md-4">
                            {{-- <a class="btn btn-success btn-sm" href="{{ route('export.datajemaat') }}"><i class="fas fa-download"></i> Export Data</a> --}}
                        </div>
                    </div>
                    <div class="sparkline13-graph">
                        <div class="table-responsive">
                            @if ($message = Session::get('update'))
                                <div class="row">
                                    <div class="col-md-12">
                                    <div class="alert alert-warning alert-block">
                                        <button type="button" class="close" data-dismiss="alert">×</button>
                                        <strong>{{ $message }}</strong>
                                    </div>
                                    </div>
                                </div>
                            @elseif($message = Session::get('success'))
                                <div class="row">
                                    <div class="col-md-12">
                                    <div class="alert alert-success alert-block">
                                        <button type="button" class="close" data-dismiss="alert">×</button>
                                        <strong>{{ $message }}</strong>
                                    </div>
                                    </div>
                                </div>
                            @endif
                            <table id="tableDataJemaat" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Nama</th>
                                        <th>Nama Alias</th>
                                        <th style="width: 15%">Tanggal Lahir</th>
                                        <th>Lingkungan </th>
                                        <th>Status Jemaat</th>
                                        <th style="width: 15%"></th>
                                    </tr>
                                </thead>
                                
                            </table>
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
        var table = $('#tableDataJemaat').DataTable({
            "scrollX": true,
            "pageLength": 25,
            processing: true,
            serverSide: true, //aktifkan server-side 
            ajax: {
                url: "{{ route('warning.tanggal-lahir') }}",
                type: 'GET',
            },
            columns: [{
                    data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false,
                },
                {
                    data: 'jemaat_nama',
                    name: 'jemaat_nama'
                },
                {
                    data: 'jemaat_nama_alias',
                    name: 'jemaat_nama_alias'
                },
                {
                    data: 'jemaat_tanggal_lahir',
                    name: 'jemaat_tanggal_lahir'
                },
                {
                    data: 'lingkungan',
                    name: 'id_lingkungan'
                },
                {
                    data: 'jemaat_status_aktif',
                    name: 'jemaat_status_aktif'
                },
                {
                    data: 'action', name: 'action', orderable: false, searchable: false,
                },

            ],
            order: [
                [1, 'asc']
            ],
        });
    });
</script>
@endsection