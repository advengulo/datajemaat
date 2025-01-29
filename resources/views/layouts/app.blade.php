<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>@isset($title)
            {{ $title }} |
        @endisset Data Jemaat BNKP</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- inline css/script
		============================================ -->
    @yield('scriptshead')
    <!-- favicon
		============================================ -->
    <link rel="shortcut icon" type="image/x-icon" href="{{asset('img/cross-logo.png')}}">
    <!-- Google Fonts
		============================================ -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,700,900" rel="stylesheet">
    <!-- Jquery JS CSS
		============================================ -->
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <!-- Bootstrap CSS
		============================================ -->
    <link rel="stylesheet" href={{ asset('css/bootstrap.min.css') }}>
    <!-- DataTable CSS
		============================================ -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css">
    <!-- DataTable JS
		============================================ -->
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>
    <!-- DataTable Button CSS
		============================================ -->
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.min.css">
    <!-- DataTable Button JS
		============================================ -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
    {{-- <!-- Waypoints JS
		============================================ -->
    <script src="https://code.jquery.com/jquery-migrate-1.4.1.js"></script> --}}
    <!-- Moment JS
		============================================ -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
    <!-- DateTimePicker CSS
		============================================ -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
    <!-- DateTimePicker JS
		============================================ -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>
    <!-- Select Select2 CSS
		============================================ -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/css/bootstrap-select.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet">
    <!-- FontAwesome CSS
		============================================ -->
    <link rel="stylesheet" href={{ asset('css/font-awesome.min.css') }}>
    <!-- owl.carousel CSS
		============================================ -->
    <link rel="stylesheet" href={{ asset('css/owl.carousel.css') }} >
    <link rel="stylesheet" href={{ asset('css/owl.theme.css') }}>
    <link rel="stylesheet" href={{ asset('css/owl.transitions.css') }}>
    <!-- animate CSS
		============================================ -->
    <link rel="stylesheet" href={{ asset('css/animate.css') }}>
    <!-- normalize CSS
		============================================ -->
    <link rel="stylesheet" href={{ asset('css/normalize.css') }}>
    <!-- meanmenu icon CSS
		============================================ -->
    <link rel="stylesheet" href={{ asset('css/meanmenu.min.css') }}>
    <!-- main CSS
		============================================ -->
    <link rel="stylesheet" href={{ asset('css/main.css') }}>
    <!-- educate icon CSS
		============================================ -->
    <link rel="stylesheet" href={{ asset('css/educate-custon-icon.css') }}>
    <!-- morrisjs CSS
		============================================ -->
    <link rel="stylesheet" href={{ asset('css/morrisjs/morris.css') }}>
    <!-- mCustomScrollbar CSS
		============================================ -->
    <link rel="stylesheet" href={{ asset('css/scrollbar/jquery.mCustomScrollbar.min.css') }}>
    <!-- metisMenu CSS
		============================================ -->
    <link rel="stylesheet" href={{ asset('css/metisMenu/metisMenu.min.css') }}>
    <link rel="stylesheet" href={{ asset('css/metisMenu/metisMenu-vertical.css') }}>
    <!-- calendar CSS
		============================================ -->
    <link rel="stylesheet" href={{ asset('css/calendar/fullcalendar.min.css') }}>
    <link rel="stylesheet" href={{ asset('css/calendar/fullcalendar.print.min.css') }}>
    <!-- x-editor CSS
		============================================ -->
    <link rel="stylesheet" href={{ asset('css/editor/select2.css') }}>
    <link rel="stylesheet" href={{ asset('css/editor/datetimepicker.css') }}>
    <link rel="stylesheet" href={{ asset('css/editor/bootstrap-editable.css') }}>
    <link rel="stylesheet" href={{ asset('css/editor/x-editor-style.css') }}>
    <!-- normalize CSS
        ============================================ -->
    <link rel="stylesheet" href={{ asset('css/data-table/bootstrap-table.css') }}>
    <link rel="stylesheet" href={{ asset('css/data-table/bootstrap-editable.css') }}>
    <!-- Buttons
        ============================================ -->
    <link rel="stylesheet" href={{ asset('css/buttons.css') }}>
    <!-- Modals
        ============================================ -->
        <link rel="stylesheet" href={{ asset('css/modals.css') }}>
    <!-- Form
        ============================================ -->
        <link rel="stylesheet" href={{ asset('css/form/all-type-forms.css') }}>
    <!-- datepicker
        ============================================ -->
        <link rel="stylesheet" href={{ asset('css/datapicker/datepicker3.css') }}>
    <!-- style CSS
		============================================ -->
    <link rel="stylesheet" href={{ asset('style.css') }}>
    <!-- responsive CSS
		============================================ -->
    <link rel="stylesheet" href={{ asset('css/responsive.css') }}>
    <!-- modernizr JS
		============================================ -->
    <script src={{ asset('js/vendor/modernizr-2.8.3.min.js') }}></script>
    <!-- Awsomeicon
        ============================================ -->
    <link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.7.0/css/all.css' integrity='sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ' crossorigin='anonymous'>



</head>

<body>
    <div class="left-sidebar-pro">
        <nav id="sidebar" class="">
            <div class="sidebar-header">
                <a href={{ asset('/') }}><img class="main-logo" width="200px" src={{ asset('img/logo/logo.png') }} style="width:200px" alt="" /></a>
                <strong><a href={{ asset('/') }}><img src={{ asset('img/logo/logosn.jpg') }} style="border:1px solid #5c5c5c; border-radius:30px;"  width="37px" alt="" /></a></strong>
            </div>
            <div class="left-custom-menu-adp-wrap comment-scrollbar mg-t-25">
                <nav class="sidebar-nav left-sidebar-menu-pro">
                    <ul class="metismenu" id="menu1" style='font-size:17px'>
                        <li class="{{Request::is("/")?'active':''}}" >
                            <a title="Home" href={{ asset('/') }}>
								   <span class="fas fa-church fa-fw" style="font-size:17px"></span>
								   <span class="mini-click-non">Dashboard</span>
								</a>
                        </li>
                        <li>
                            <a class="has-arrow" href="#" aria-expanded="false"><span class="fas fa-user-friends fa-fw" style='font-size:17px'></span> <span class="mini-click-non">Jemaat</span></a>
                            <ul class="submenu-angle" aria-expanded="false">
                                <li class="{{Request::is("data-jemaat")?'active':''}} || {{Request::is("data-jemaat/profile/*")?'active':''}}"><a title="Data Jemaat" href={{asset('/data-jemaat')}}><span class="mini-sub-pro">Data Jemaat</span></a></li>
                                <li class="{{Request::is("tambah-jemaat")?'active':''}}"><a title="Tambah Jemaat" href={{asset('/tambah-jemaat')}}><span class="mini-sub-pro">Tambah Jemaat</span></a></li>
                                <li class="{{Request::is("data-kepala-keluarga")?'active':''}}"><a title="Data Kepala Keluarga" href={{asset('/data-kepala-keluarga')}}><span class="mini-sub-pro">Kepala Keluarga</span></a></li>
                            </ul>
                        </li>
                        <li>
                            <a class="has-arrow" href="#" aria-expanded="false"><span class="fas fa-user-friends fa-fw" style='font-size:17px'></span> <span class="mini-click-non">Simpatisan</span></a>
                            <ul class="submenu-angle" aria-expanded="false">
                                <li class="{{Request::is("data-jemaat-simpatisan")?'active':''}} || {{Request::is("tambah-jemaat-simpatisan")?'active':''}}""><a title="Data Jemaat Simpatisan" href={{asset('/data-jemaat-simpatisan')}}><span class="mini-sub-pro">Data Simpatisan</span></a></li>
                                <li class="{{Request::is("data-kepala-keluarga-simpatisan")?'active':''}}"><a title="Data Kepala Keluarga" href={{asset('/data-kepala-keluarga-simpatisan')}}><span class="mini-sub-pro">Kepala Keluarga</span></a></li>
                            </ul>
                        </li>
                        <li class="{{Request::is("kartu-jemaat")?'active':''}}" >
                            <a title="Kartu Jemaat" href={{ asset('/kartu-jemaat') }}>
								   <span class="fas fa-id-card fa-fw" style='font-size:17px'></span>
								   <span class="mini-click-non">Kartu Jemaat</span>
								</a>
                        </li>
                        <li>
                            <a class="has-arrow" href="#" aria-expanded="false"><span class="fas fa-table fa-fw"></span> <span class="mini-click-non">Jemaat Inaktif</span></a>
                            <ul class="submenu-angle" aria-expanded="false">
                                <li class="{{Request::is("data-jemaat-meninggal") ? 'active' : ''}}"><a href="{{asset('/data-jemaat-meninggal')}}"><span class="mini-sub-pro">Meninggal</span></a></li>
                                <li class="{{Request::is("data-jemaat-pindah") ? 'active' : ''}}"><a href="{{asset('/data-jemaat-pindah')}}"><span class="mini-sub-pro">Pindah</span></a></li>
                            </ul>
                        </li>
                        <li>
                            <a class="has-arrow" href="#" aria-expanded="false"><span class="fas fa-database fa-fw"></span> <span class="mini-click-non">Data Master</span></a>
                            <ul class="submenu-angle" aria-expanded="false">
                                <li class="{{Request::is("data-lingkungan") ? 'active' : ''}}"><a href="{{asset('/data-lingkungan')}}"><span class="mini-sub-pro">Lingkungan</span></a></li>
                                <li class="{{Request::is("#") ? 'active' : ''}}"><a href="{{asset('#')}}"><span class="mini-sub-pro">Pekerjaan</span></a></li>
                                <li class="{{Request::is("#") ? 'active' : ''}}"><a href="{{asset('#')}}"><span class="mini-sub-pro">Pendidikan</span></a></li>
                            </ul>
                        </li>
                        <li>
                            <a class="has-arrow" href="#" aria-expanded="false"><span class="fas fa-exclamation-triangle fa-fw"></span> <span class="mini-click-non">Data Warning<span> {!! $notif['dataWarning'] !!}</i> </span></span></a>
                            <ul class="submenu-angle" aria-expanded="false">
                                <li class="{{Request::is("data-warning/tanggal-lahir") ? 'active' : ''}}"><a href="{{asset('/data-warning/tanggal-lahir')}}"><span class="mini-sub-pro">Tanggal Lahir</span></a></li>
                                {{-- <li class="{{Request::is("data-warning/data-tunggal") ? 'active' : ''}}"><a href="{{asset('/data-warning/data-tunggal')}}"><span class="mini-sub-pro">Data Tunggal</span></a></li>                             --}}
                                <li class="{{Request::is("data-warning/data-ganda") ? 'active' : ''}}"><a href="{{asset('/data-warning/data-ganda')}}"><span class="mini-sub-pro">Data Ganda</span></a></li>
                            </ul>
                        </li>
                        <li>
                            <a class="has-arrow" href="#" aria-expanded="false"><span class="fas fa-scroll fa-fw"></span> <span class="mini-click-non">Laporan</span></a>
                            <ul class="submenu-angle" aria-expanded="false">
                                {{-- <li class="{{Request::is("laporan/tahunan") ? 'active' : ''}}"><a href="{{asset('/laporan/tahunan')}}"><span class="mini-sub-pro">Tahunan</span></a></li>                             --}}
                                <li class="{{Request::is("laporan/statistik") ? 'active' : ''}}"><a href="{{asset('/laporan/statistik')}}"><span class="mini-sub-pro">Statistik</span></a></li>
                                <li class="{{Request::is("laporan/sidi") ? 'active' : ''}}"><a href="{{asset('/laporan/sidi')}}"><span class="mini-sub-pro">Sidi</span></a></li>
                                <li class="{{Request::is("laporan/data-sidi") ? 'active' : ''}}"><a href="{{asset('/laporan/data-sidi')}}"><span class="mini-sub-pro">Data Sidi</span></a></li>
                            </ul>
                        </li>
                        <li>
                            <a class="has-arrow" href="#" aria-expanded="false"><span class="fas fa-table fa-fw"></span> <span class="mini-click-non">Rekap Data</span></a>
                            <ul class="submenu-angle" aria-expanded="false">
                                <li class="{{Request::is("rekap-lingkungan") ? 'active' : ''}}"><a href="{{asset('/rekap-lingkungan')}}"><span class="mini-sub-pro">Lingkungan</span></a></li>
                                <li class="{{Request::is("rekap-kepalakeluarga") ? 'active' : ''}}"><a href="{{asset('/rekap-kepalakeluarga')}}"><span class="mini-sub-pro">Kepala Keluarga</span></a></li>
                                <li class="{{Request::is("rekap-jenis-kelamin") ? 'active' : ''}}"><a href="{{asset('/rekap-jenis-kelamin')}}"><span class="mini-sub-pro">Jenis Kelamin</span></a></li>
                                <li class="{{Request::is("rekap-jenis-usia") ? 'active' : ''}}"><a href="{{asset('/rekap-jenis-usia')}}"><span class="mini-sub-pro">Jenis Usia</span></a></li>
                                <li class="{{Request::is("rekap-status-perkawinan") ? 'active' : ''}}"><a href="{{asset('/rekap-status-perkawinan')}}"><span class="mini-sub-pro">Status Perkawinan</span></a></li>
                                <li class="{{Request::is("rekap-pendidikan") ? 'active' : ''}}"><a href="{{asset('/rekap-pendidikan')}}"><span class="mini-sub-pro">Pendidikan</span></a></li>
                                <li class="{{Request::is("rekap-pekerjaan") ? 'active' : ''}} || {{Request::is("rekap-pekerjaan/*") ? 'active' : ''}}"><a href="{{asset('/rekap-pekerjaan')}}"><span class="mini-sub-pro">Pekerjaan</span></a></li>
                                <li class="{{Request::is("rekap-jemaat-bergabung") ? 'active' : ''}}"><a href="{{asset('/rekap-jemaat-bergabung')}}"><span class="mini-sub-pro">Jemaat Bergabung</span></a></li>
                            </ul>
                        </li>
                        <li>
                            <a class="has-arrow" href="#" aria-expanded="false"><span class="fas fa-chart-bar fa-fw"></span> <span class="mini-click-non">Grafik</span></a>
                            <ul class="submenu-angle" aria-expanded="false">
                                <li class="{{Request::is("grafik-lingkungan") ? 'active' : ''}}"><a href="{{asset('/grafik-lingkungan')}}"><span class="mini-sub-pro">Lingkungan</span></a></li>
                                <li class="{{Request::is("grafik-jenis-kelamin") ? 'active' : ''}}"><a href="{{asset('/grafik-jenis-kelamin')}}"><span class="mini-sub-pro">Jenis Kelamin</span></a></li>
                                <li class="{{Request::is("grafik-jenis-usia") ? 'active' : ''}}"><a href="{{asset('/grafik-jenis-usia')}}"><span class="mini-sub-pro">Jenis Usia</span></a></li>
                                <li class="{{Request::is("grafik-status-perkawinan") ? 'active' : ''}}"><a href="{{asset('/grafik-status-perkawinan')}}"><span class="mini-sub-pro">Status Perkawinan</span></a></li>
                                <li class="{{Request::is("grafik-pendidikan") ? 'active' : ''}}"><a href="{{asset('/grafik-pendidikan')}}"><span class="mini-sub-pro">Pendidikan</span></a></li>
                                <li class="{{Request::is("grafik-pekerjaan") ? 'active' : ''}} || {{Request::is("grafik-pekerjaan/*") ? 'active' : ''}}"><a href="{{asset('/grafik-pekerjaan')}}"><span class="mini-sub-pro">Pekerjaan</span></a></li>
                                <li class="{{Request::is("grafik-jemaat-bergabung") ? 'active' : ''}}"><a href="{{asset('/grafik-jemaat-bergabung')}}"><span class="mini-sub-pro">Jemaat Bergabung</span></a></li>
                            </ul>
                        </li>
                    </ul>
                </nav>
            </div>
        </nav>
    </div>
    <!-- End Left menu area -->
    <!-- Start Welcome area -->
    <div class="all-content-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="logo-pro">
                        <a href="{{asset('/')}}"><img class="main-logo" width="200px" src="{{asset('img/logo/logo.png')}}" alt="" /></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="header-advance-area">
            <div class="header-top-area">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="header-top-wraper">
                                <div class="row">
                                    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-12">
                                        <div class="menu-switcher-pro">
                                            <button type="button" id="sidebarCollapse"
                                                class="btn bar-button-pro header-drl-controller-btn btn-info navbar-btn" style="background:none">
                                                <i class="educate-icon educate-nav" style="color : #5c5c5c"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-lg-11 col-md-11 col-sm-12 col-xs-12">
                                        <div class="header-right-info">
                                            <ul class="nav navbar-nav mai-top-nav header-right-menu">
                                                <li class="nav-item">
                                                    <a href="#" data-toggle="dropdown" role="button" aria-expanded="false" class="nav-link dropdown-toggle">
                                                        <img src="{{asset('img/logo/cross-logo.jpg')}}"
                                                            style="border:1px solid #5c5c5c; border-radius:30px;" alt="" />
                                                        <span class="admin-name">{{ Auth::user()->name }}</span>
                                                        <i class="fa fa-angle-down edu-icon edu-down-arrow" style="color : #5c5c5c"></i>
                                                    </a>
                                                    <ul role="menu" class="dropdown-header-top author-log dropdown-menu animated zoomIn">
                                                        </li>
                                                        <li><a href="{{ route('logout') }}"
                                                                onclick="event.preventDefault();
                                                                                                                                    document.getElementById('logout-form').submit();">
                                                                <span class="edu-icon edu-locked author-log-ic"></span>Log Out
                                                            </a>
                                                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                                                @csrf
                                                            </form>
                                                        </li>
                                                    </ul>
                                                </li>
                                                <div class="col-md-1"></div>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Mobile Menu start -->
            <div class="mobile-menu-area">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="mobile-menu">
                                <nav id="dropdown">
                                    <ul class="mobile-menu-nav">
                                        <li><a href="{{asset('/')}}">Dashboard</a></li>
                                        <li>
                                            <a data-toggle="collapse" href="#" aria-expanded="false"><span class="fas fa-user-friends"> </span><span class="mini-click-non">Data Jemaat</span></a>
                                            <ul class="collapse dropdown-header-top">
                                                <li><a href="{{asset('/data-jemaat')}}">Data Jemaat</a></li>
                                                <li><a href="{{asset('/tambah-jemaat')}}">Tambah Jemaat</a></li>
                                                <li><a href="{{asset('/data-kepala-keluarga')}}">Kepala Keluarga</a></li>
                                                <li><a href="{{url('/data-jemaat/non-lingkungan')}}">Kepala Keluarga</a></li>
                                            </ul>
                                        </li>
                                        <li class="{{Request::is("kartu-jemaat")?'active':''}}" >
                                            <a title="Kartu Jemaat" href={{ asset('/kartu-jemaat') }}>
                                                   <span class="fas fa-id-card" style='font-size:17px'></span>
                                                   <span class="mini-click-non">Kartu Jemaat</span>
                                                </a>
                                        </li>
                                        <li>
                                            <a class="has-arrow" href="#" aria-expanded="false"><span class="fas fa-table"></span> <span class="mini-click-non">Jemaat Inaktif</span></a>
                                            <ul class="submenu-angle" aria-expanded="false">
                                                <li class="{{Request::is("data-jemaat-meninggal") ? 'active' : ''}}"><a href="{{asset('/data-jemaat-meninggal')}}"><span class="mini-sub-pro">Meninggal</span></a></li>
                                                <li class="{{Request::is("data-jemaat-pindah") ? 'active' : ''}}"><a href="{{asset('/data-jemaat-pindah')}}"><span class="mini-sub-pro">Pindah</span></a></li>
                                            </ul>
                                        </li>
                                        <li>
                                            <a class="has-arrow" href="#" aria-expanded="false"><span class="fas fa-table"></span> <span class="mini-click-non">Rekap Data</span></a>
                                            <ul class="submenu-angle" aria-expanded="false">
                                                <li class="{{Request::is("rekap-lingkungan") ? 'active' : ''}}"><a href="{{asset('/rekap-lingkungan')}}"><span class="mini-sub-pro">Lingkungan</span></a></li>
                                                <li class="{{Request::is("rekap-jenis-kelamin") ? 'active' : ''}}"><a href="{{asset('/rekap-jenis-kelamin')}}"><span class="mini-sub-pro">Jenis Kelamin</span></a></li>
                                                <li class="{{Request::is("rekap-jenis-usia") ? 'active' : ''}}"><a href="{{asset('/rekap-jenis-usia')}}"><span class="mini-sub-pro">Jenis Usia</span></a></li>
                                                <li class="{{Request::is("rekap-status-perkawinan") ? 'active' : ''}}"><a href="{{asset('/rekap-status-perkawinan')}}"><span class="mini-sub-pro">Status Perkawinan</span></a></li>
                                                <li class="{{Request::is("rekap-pendidikan") ? 'active' : ''}}"><a href="{{asset('/rekap-pendidikan')}}"><span class="mini-sub-pro">Pendidikan</span></a></li>
                                                <li class="{{Request::is("rekap-pekerjaan") ? 'active' : ''}} || {{Request::is("rekap-pekerjaan/*") ? 'active' : ''}}"><a href="{{asset('/rekap-pekerjaan')}}"><span class="mini-sub-pro">Pekerjaan</span></a></li>
                                                <li class="{{Request::is("rekap-jemaat-bergabung") ? 'active' : ''}}"><a href="{{asset('/rekap-jemaat-bergabung')}}"><span class="mini-sub-pro">Jemaat Bergabung</span></a></li>
                                            </ul>
                                        </li>
                                        <li>
                                            <a class="has-arrow" href="#" aria-expanded="false"><span class="fas fa-chart-bar"></span> <span class="mini-click-non">Grafik</span></a>
                                            <ul class="submenu-angle" aria-expanded="false">
                                                <li class="{{Request::is("grafik-lingkungan") ? 'active' : ''}}"><a href="{{asset('/grafik-lingkungan')}}"><span class="mini-sub-pro">Lingkungan</span></a></li>
                                                <li class="{{Request::is("grafik-jenis-kelamin") ? 'active' : ''}}"><a href="{{asset('/grafik-jenis-kelamin')}}"><span class="mini-sub-pro">Jenis Kelamin</span></a></li>
                                                <li class="{{Request::is("grafik-jenis-usia") ? 'active' : ''}}"><a href="{{asset('/grafik-jenis-usia')}}"><span class="mini-sub-pro">Jenis Usia</span></a></li>
                                                <li class="{{Request::is("grafik-status-perkawinan") ? 'active' : ''}}"><a href="{{asset('/grafik-status-perkawinan')}}"><span class="mini-sub-pro">Status Perkawinan</span></a></li>
                                                <li class="{{Request::is("grafik-pendidikan") ? 'active' : ''}}"><a href="{{asset('/grafik-pendidikan')}}"><span class="mini-sub-pro">Pendidikan</span></a></li>
                                                <li class="{{Request::is("grafik-pekerjaan") ? 'active' : ''}} || {{Request::is("grafik-pekerjaan/*") ? 'active' : ''}}"><a href="{{asset('/grafik-pekerjaan')}}"><span class="mini-sub-pro">Pekerjaan</span></a></li>
                                                <li class="{{Request::is("grafik-jemaat-bergabung") ? 'active' : ''}}"><a href="{{asset('/grafik-jemaat-bergabung')}}"><span class="mini-sub-pro">Jemaat Bergabung</span></a></li>
                                            </ul>
                                        </li>
                                        <li>
                                            <a href="{{ route('logout') }}"
                                                onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();">
                                                <span class="edu-icon edu-locked author-log-ic"></span>Log Out
                                            </a>
                                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                                @csrf
                                            </form>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="breadcome-area">
                {{-- <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="breadcome-list single-page-breadcome">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <div class="breadcome-heading">
                                            <form role="search" class="sr-input-func">
                                                <input type="text" placeholder="Search..." class="search-int form-control">
                                                <a href="#"><i class="fa fa-search"></i></a>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <ul class="breadcome-menu">
                                            <li><a href="#">Dashboard</a> <span class="bread-slash">/</span>
                                            </li>
                                            <li><span class="bread-blod">...</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>
        <!-- Content
            ========================================-->
        @yield('content')
    </div>

    <!-- jquery
		============================================ -->
    {{-- <script src={{ asset('js/vendor/jquery-1.12.4.min.js') }}></script> --}}
    {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script> --}}
    <!-- bootstrap JS
		============================================ -->
    <script src={{ asset('js/bootstrap.min.js') }}></script>
    <!-- wow JS
		============================================ -->
    <script src={{ asset('js/wow.min.js') }}></script>
    <!-- price-slider JS
		============================================ -->
    <script src={{ asset('js/jquery-price-slider.js') }}></script>
    <!-- meanmenu JS
		============================================ -->
    <script src={{ asset('js/jquery.meanmenu.js') }}></script>
    <!-- owl.carousel JS
		============================================ -->
    <script src={{ asset('js/owl.carousel.min.js') }}></script>
    <!-- sticky JS
		============================================ -->
    <script src={{ asset('js/jquery.sticky.js') }}></script>
    <!-- scrollUp JS
		============================================ -->
    <script src={{ asset('js/jquery.scrollUp.min.js') }}></script>
    <!-- counterup JS
		============================================ -->
    <script src={{ asset('js/counterup/jquery.counterup.min.js') }}></script>
    <script src={{ asset('js/counterup/waypoints.min.js') }}></script>
    <script src={{ asset('js/counterup/counterup-active.js') }}></script>
    <!-- mCustomScrollbar JS
		============================================ -->
    <script src={{ asset('js/scrollbar/jquery.mCustomScrollbar.concat.min.js') }}></script>
    <script src={{ asset('js/scrollbar/mCustomScrollbar-active.js') }}></script>
    <!-- metisMenu JS
		============================================ -->
    <script src={{ asset('js/metisMenu/metisMenu.min.js') }}></script>
    <script src={{ asset('js/metisMenu/metisMenu-active.js') }}></script>
    <!-- morrisjs JS
		============================================ -->
    <script src={{ asset('js/morrisjs/raphael-min.js') }}></script>
    <script src={{ asset('js/morrisjs/morris.js') }}></script>
    <script src={{ asset('js/morrisjs/morris-active.js') }}></script>
    <!-- morrisjs JS
		============================================ -->
    <script src={{ asset('js/sparkline/jquery.sparkline.min.js') }}></script>
    <script src={{ asset('js/sparkline/jquery.charts-sparkline.js') }}></script>
    <script src={{ asset('js/sparkline/sparkline-active.js') }}></script>
    <!-- calendar JS
		============================================ -->
    <script src={{ asset('js/calendar/moment.min.js') }}></script>
    <script src={{ asset('js/calendar/fullcalendar.min.js') }}></script>
    <script src={{ asset('js/calendar/fullcalendar-active.js') }}></script>
    <!-- icheck JS
		============================================ -->
    <script src={{ asset('js/icheck/icheck.min.js') }}></script>
    <script src={{ asset('js/icheck/icheck-active.js') }}></script>
     <!-- datapicker JS
		============================================ -->
    <script src={{ asset('js/datapicker/bootstrap-datepicker.js') }}></script>
    <script src={{ asset('js/datapicker/datepicker-active.js') }}></script>
    <!-- Select2
        =========================================== -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
    <!-- plugins JS
		============================================ -->
    <script src={{ asset('js/plugins.js') }}></script>
    <!-- main JS
		============================================ -->
    <script src={{ asset('js/main.js') }}></script>
    <!-- tawk chat JS
		============================================ -->
    <!-- Moment JS
        ============================================ -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.12.0/moment.js"></script>
    <!--  editable JS
        ============================================ -->
    <script src={{ asset('js/editable/jquery.mockjax.js') }}></script>
    <script src={{ asset('js/editable/mock-active.js') }}></script>
    <script src={{ asset('js/editable/select2.js') }}></script>
    <script src={{ asset('js/editable/moment.min.js') }}></script>
    <script src={{ asset('js/editable/bootstrap-datetimepicker.js') }}></script>
    <script src={{ asset('js/editable/bootstrap-editable.js') }}></script>
    <script src={{ asset('js/editable/xediable-active.js') }}></script>
    <!-- Chart JS
		============================================ -->
    <script src={{ asset('js/chart/jquery.peity.min.js') }}></script>
    <script src={{ asset('js/peity/peity-active.js') }}></script>
    <!-- tab JS
        ============================================ -->
    <script src={{ asset('js/tab.js') }}></script>
    <!-- Date Time Picker JS
        ============================================ -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/js/bootstrap-select.min.js"></script>

    <!-- extend scrip on blade
        ============================================ -->
    @yield('scripts')

</body>

</html>
