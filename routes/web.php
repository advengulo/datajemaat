<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => ['auth']], function () {
    Route::resource('/','HomeController');
    
    Route::controller(DataJemaatController::class)->group(function () {
        Route::get('/data-jemaat', 'index')->name('datajemaat');
        Route::get('/data-jemaat/profile/{data_jemaat}', 'show')->name('profiledetail');
        Route::get('/data-jemaat/profile/{data_jemaat}/edit', 'edit')->name('jemaateditprofile');
        Route::patch('/data-jemaat/{id}/update', 'update')->name('jemaatupdate');
        Route::patch('/data-jemaat/profile/{id}/update1', 'updateStatusPindah')->name('updatestatuspindah');
        Route::patch('/data-jemaat/profile/{id}/update2', 'updateStatusMeninggal')->name('updatestatusmeninggal');
        Route::patch('/data-jemaat/profile/{id}/update3', 'destroy')->name('hapusdatajemaat');
        Route::patch('/data-jemaat/profile/{id}/update4', 'jadikankk')->name('jadikankk');
        Route::get('/tambah-jemaat', 'create')->name('tambahjemaat');
        Route::post('/tambah-jemaat', 'store')->name('tambahdatajemaat');
        Route::get('/data-jemaat/export','exportDataJemaat')->name('export.datajemaat');
    });
    

    Route::controller(KepalaKeluargaController::class)->group(function () {
        Route::get('/data-kepala-keluarga', 'index')->name('data-kk');
        Route::get('/data-kepala-keluarga/export', 'exportDataKK')->name('export.dataKK');
    });

    Route::get('/import-data','ImportExportController@importIndex')->name('import.index');
    Route::post('/import','ImportExportController@import')->name('import.datajemaat');
    
    
    Route::get('/kartu-jemaat', 'KartuJemaatController@index')->name('kartujemaat');
    Route::post('/kartu-jemaat/download-all', 'KartuJemaatController@downloadZip')->name('download.all');
    Route::get('/kartu-jemaat/{data_jemaat}', 'KartuJemaatController@show')->name('lihatdatakk');
    Route::get('/kartu-jemaat/cetak-kartu/{data_jemaat}', 'KartuJemaatController@cetak_pdf')->name('cetakpdf');

    Route::get('/data-jemaat-meninggal', 'JemaatInAktifController@meninggal')->name('datameninggal');
    Route::get('/data-jemaat-pindah', 'JemaatInAktifController@pindah')->name('datapindah');

    Route::prefix('laporan')->group(function () {
        Route::get('/tahunan', 'LaporanController@tahunan')->name('laporan.tahunan');
        Route::get('/statistik', 'LaporanController@statistik')->name('laporan.statistik');
        Route::get('/sidi','Laporan\SidiController')->name('laporan.sidi');
        Route::get('/data-sidi','Laporan\SidiController@nama')->name('laporan.namasidi');
        
    });

    Route::get('/rekap-lingkungan', 'RekapDataController@lingkungan');
    Route::get('/rekap-kepalakeluarga', 'RekapDataController@kepalakeluarga');
    Route::get('/rekap-jenis-kelamin', 'RekapDataController@jeniskelamin');
    Route::get('/rekap-jenis-usia', 'RekapDataController@jenisusia');
    Route::get('/rekap-status-perkawinan', 'RekapDataController@statusperkawinan');
    Route::get('/rekap-pendidikan', 'RekapDataController@pendidikan');
    Route::get('/rekap-pekerjaan', 'RekapDataController@pekerjaan');
    Route::get('/rekap-pekerjaan/show', 'RekapDataController@getPekerjaan')->name('getPekerjaan');
    Route::get('/rekap-jemaat-bergabung', 'RekapDataController@jemaatbergabung');

    Route::get('/grafik-lingkungan', 'GrafikController@lingkungan');
    Route::get('/grafik-jenis-kelamin', 'GrafikController@jeniskelamin');
    Route::get('/grafik-jenis-usia', 'GrafikController@jenisusia');
    Route::get('/grafik-status-perkawinan', 'GrafikController@statusperkawinan');
    Route::get('/grafik-pendidikan', 'GrafikController@pendidikan');
    Route::get('/grafik-pekerjaan', 'GrafikController@pekerjaan');
    Route::get('/grafik-jemaat-bergabung', 'GrafikController@jemaatbergabung');

    Route::get('/data-lingkungan', 'LingkunganMasterController@index')->name('datalingkungan');
    Route::post('/data-lingkungan', 'LingkunganMasterController@store')->name('lingkungan.store');
    Route::patch('/data-lingkungan/delete/{id}', 'LingkunganMasterController@destroy')->name('lingkungan.destroy');

    Route::prefix('data-warning')->group(function () {
        Route::get('/tanggal-lahir', 'DataWarningController@tanggalLahir')->name('warning.tanggal-lahir');
        Route::get('/data-tunggal', 'DataWarningController@tunggal')->name('warning.data-tunggal');
        Route::get('/data-ganda', 'DataWarningController@duplicate')->name('warning.data-ganda');
    });

    Route::post('/data-keluarga/{id}','DataJemaatController@updateDataKeluarga')->name('update.data-keluarga');
});

Auth::routes();

