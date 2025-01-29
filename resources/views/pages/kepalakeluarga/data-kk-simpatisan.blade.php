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
                            <h1>Data Kepala Keluarga Simpatisan</h1>
                        </div>
                    </div>
                    <div class="row mg-b-15">
                        <div class="col-md-4">
                            <a class="btn btn-success btn-sm" href="{{ route('export.dataKKSimpatisan') }}"><i class="fas fa-download"></i> Export Data</a>
                        </div>
                    </div>
                    <div class="sparkline13-graph">
                        <div class="table-responsive">
                            <table id="tableKepalaKeluarga" class="table table-striped table-bordered" style="width:100%">
                                    <thead>
                                    <tr>
                                        <th></th>
                                        <th>Nama</th>
                                        <th>Nama Alias</th>
                                        <th>Nomor Stambuk</th>
                                        <th>Pekerjaan</th>
                                        <th>Nomor Lingkungan</th>
                                        <th>Nama Lingkungan </th>
                                        <th></th>
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
        var table = $('#tableKepalaKeluarga').DataTable({
            scrollX : true,
            pageLength : 25,
            processing: true,
            serverSide: true, //aktifkan server-side
            ajax: {
                url: "{{ route('data-kk-simpatisan') }}",
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
                    data: 'jemaat_nomor_stambuk',
                    name: 'jemaat_nomor_stambuk'
                },
                {
                    data: 'pekerjaan',
                    name: 'pekerjaan.jenis_pekerjaan'
                },
                {
                    data: 'id_lingkungan',
                    name: 'id_lingkungan'
                },
                {
                    data: 'lingkungan',
                    name: 'lingkungan.nama_lingkungan'
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
