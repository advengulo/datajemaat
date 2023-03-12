@extends('layouts.app')

@section('content')
<div class="widgets-programs-area mg-tb-15">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-6 mg-b-15">
                <div class="hpanel widget-int-shape responsive card-radius card-shadow">
                    <div class="panel-body" style="min-height:109px;">
                        <div class="pull-left">
                            <h2 style="color:#5C6BC0">{{$data['total_jemaat']}}</h2>
                        </div>
                        <div class="stats-icon pull-right">
                            <i class="fa fa-users fa-2x"></i>
                        </div>
                        <div class="m-t-xl widget-cl-3">
                            <br>
                            <h4 class="stats-title">Total Jemaat</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-6 mg-b-15">
                <div class="hpanel widget-int-shape responsive card-radius card-shadow">
                    <div class="panel-body" style="min-height:109px;">
                        <div class="pull-left">
                            <h2 style="color:#0f7173">{{$data['total_jemaat_simpatisan']}}</h2>
                        </div>
                        <div class="stats-icon pull-right">
                            <i class="fa fa-users fa-2x"></i>
                        </div>
                        <div class="m-t-xl widget-cl-3">
                            <br>
                            <h4 class="stats-title">Total Jemaat Simpatisan</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-6 mg-b-15">
                <div class="hpanel widget-int-shape responsive card-radius card-shadow">
                    <div class="panel-body" style="min-height:109px;">
                        <div class="pull-left">
                            <h2 style="color:#26A69A">{{$data['total_kk']}}</h2>
                        </div>
                        <div class="stats-icon pull-right">
                            <i class="fa fa-user fa-2x"></i>
                        </div>
                        <div class="m-t-xl widget-cl-3">
                            <br>
                            <h4 class="stats-title">Total Kepala Keluarga</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-6 mg-b-15">
                <div class="hpanel widget-int-shape responsive card-radius card-shadow">
                    <div class="panel-body" style="min-height:109px;">
                        <div class="pull-left">
                            <h2 style="color:#9CCC65">{{$data['total_lingkungan']}}</h2>
                        </div>
                        <div class="stats-icon pull-right">
                            <i class="fas fa-map fa-2x"></i>
                        </div>
                        <div class="m-t-xl widget-cl-3">
                            <br>
                            <h4 class="stats-title">Total Lingkungan</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-6 mg-b-15">
                <div class="hpanel widget-int-shape responsive card-radius card-shadow">
                    <div class="panel-body" style="min-height:109px;">
                        <div class="pull-left">
                            <h2 style="color:#FDD835">{{$data['total_bergabung_thisyear']}}</h2>
                        </div>
                        <div class="stats-icon pull-right">
                            <i class="fa fa-users fa-2x"></i>
                        </div>
                        <div class="m-t-xl widget-cl-3">
                            <br>
                            <h4 class="stats-title">Total Jemaat Bergabung {{$thisyear}}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-6 mg-b-15">
                <div class="hpanel widget-int-shape responsive card-radius card-shadow">
                    <div class="panel-body" style="min-height:109px;">
                        <div class="pull-left">
                            <h2 style="color:#20cac2">{{$data['total_laki_laki']}}</h2>
                        </div>
                        <div class="stats-icon pull-right">
                            <i class="fa fa-users fa-2x"></i>
                        </div>
                        <div class="m-t-xl widget-cl-3">
                            <br>
                            <h4 class="stats-title">Total Laki-laki</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-6 mg-b-15">
                <div class="hpanel widget-int-shape responsive card-radius card-shadow">
                    <div class="panel-body" style="min-height:109px;">
                        <div class="pull-left">
                            <h2 style="color:#fc6e2d">{{$data['total_perempuan']}}</h2>
                        </div>
                        <div class="stats-icon pull-right">
                            <i class="fa fa-users fa-2x"></i>
                        </div>
                        <div class="m-t-xl widget-cl-3">
                            <br>
                            <h4 class="stats-title">Total Perempuan</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="product-sales-area mg-b-15">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="product-sales-chart card-radius card-shadow">
                    <div class="portlet-title">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <div class="caption pro-sl-hd">
                                    <span class="caption-subject"><b></b></span>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <div class="actions graph-rp graph-rp-dl">
                                    <p></p>
                                </div>
                            </div>
                        </div>
                        <div id="chartJemaatBergabung"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script>
        Highcharts.chart('chartJemaatBergabung', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Chart Data Jemaat Bergabung'
            },
            xAxis: {
                categories: {!!json_encode($years)!!},
                crosshair: true
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Orang'
                }
            },
            tooltip: {
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            series: [{
                name: 'Total',
                data: {{json_encode($total)}},
                color: '#1A337B'
            }, {
                name: 'Laki-Laki',
                data: {{json_encode($laki)}},
                color: '#EF5350'
            }, {
                name: 'Perempuan',
                data: {{json_encode($perempuan)}},
                color: '#80CBC4'
            }]
        });

    </script>
@endsection