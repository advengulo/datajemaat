<?php

use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\DataJemaatController;
use App\Http\Controllers\JemaatSimpatisanController;
use App\Http\Controllers\KepalaKeluargaController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\LingkunganMasterController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\ApprovalController;
use App\Http\Controllers\ImportExportController;
use App\Http\Controllers\KartuJemaatController;
use App\Http\Controllers\JemaatInAktifController;
use App\Http\Controllers\RekapDataController;
use App\Http\Controllers\GrafikController;
use App\Http\Controllers\DataWarningController;

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
    Route::resource('/', 'HomeController');

    // Jemaat routes with lingkungan scope and permissions
    Route::middleware(['scope.lingkungan'])->group(function () {
        Route::controller(DataJemaatController::class)->group(function () {
            Route::get('/data-jemaat', 'index')->name('datajemaat')->middleware('permission:jemaat.view');
            Route::get('/data-jemaat/profile/{data_jemaat}', 'show')->name('profiledetail')->middleware('permission:jemaat.view');
            Route::get('/data-jemaat/profile/{data_jemaat}/edit', 'edit')->name('jemaateditprofile')->middleware('permission:jemaat.update');
            Route::patch('/data-jemaat/{id}/update', 'update')->name('jemaatupdate')->middleware('permission:jemaat.update');
            Route::patch('/data-jemaat/profile/{id}/update1', 'updateStatusPindah')->name('updatestatuspindah')->middleware('permission:jemaat.update');
            Route::patch('/data-jemaat/profile/{id}/update2', 'updateStatusMeninggal')->name('updatestatusmeninggal')->middleware('permission:jemaat.update');
            Route::patch('/data-jemaat/profile/{id}/update3', 'destroy')->name('hapusdatajemaat')->middleware('permission:jemaat.delete');
            Route::patch('/data-jemaat/profile/{id}/update4', 'jadikankk')->name('jadikankk')->middleware('permission:jemaat.update');
            Route::patch('/data-jemaat/profile/{id}/update5', 'updateStatusSimpatisan')->name('updateStatusSimpatisan')->middleware('permission:jemaat.update');
            Route::get('/tambah-jemaat', 'create')->name('tambahjemaat')->middleware('permission:jemaat.create|jemaat.update');
            Route::post('/tambah-jemaat', 'store')->name('tambahdatajemaat')->middleware('permission:jemaat.create');
            Route::get('/data-jemaat/export', 'exportDataJemaat')->name('export.datajemaat')->middleware('permission:jemaat.export');
            Route::get('/data-jemaat/non-lingkungan', 'nonLingkungan')->name('jemaat.nonLingkungan')->middleware('permission:jemaat.view');
            Route::get('/data-jemaat-ajax', 'ajax')->name('datajemaat.ajax')->middleware('permission:jemaat.view');
        });

        Route::controller(JemaatSimpatisanController::class)->group(function () {
            Route::get('/data-jemaat-simpatisan', 'index')->name('jemaat.simpatisan')->middleware('permission:simpatisan.view');
            Route::get('/data-jemaat-simpatisan-ajax', 'ajax')->name('jemaat.simpatisan.ajax')->middleware('permission:simpatisan.view');
            Route::get('/tambah-jemaat-simpatisan', 'create')->name('jemaat.simpatisan.create')->middleware('permission:simpatisan.create');
            Route::post('/tambah-jemaat-simpatisan', 'store')->name('jemaat.simpatisan.store')->middleware('permission:simpatisan.create');
        });

        Route::controller(KepalaKeluargaController::class)->group(function () {
            Route::get('/data-kepala-keluarga', 'index')->name('data-kk')->middleware('permission:jemaat.view');
            Route::get('/data-kepala-keluarga/export', 'exportDataKK')->name('export.dataKK')->middleware('permission:jemaat.export');
            Route::get('/data-kepala-keluarga-simpatisan', 'kepalaKeluargaSimpatisan')->name('data-kk-simpatisan')->middleware('permission:simpatisan.view');
            Route::get('/data-kepala-keluarga-simpatisan/export', 'exportDataKKSimpatisan')->name('export.dataKKSimpatisan')->middleware('permission:simpatisan.export');
        });
    });

    Route::middleware(['scope.lingkungan'])->group(function () {
        Route::get('/import-data', [ImportExportController::class, 'importIndex'])->name('import.index')->middleware('permission:jemaat.create');
        Route::post('/import', [ImportExportController::class, 'import'])->name('import.datajemaat')->middleware('permission:jemaat.create');

        Route::get('/kartu-jemaat', [KartuJemaatController::class, 'index'])->name('kartujemaat')->middleware('permission:jemaat.view');
        Route::post('/kartu-jemaat/download-all', [KartuJemaatController::class, 'downloadZip'])->name('download.all')->middleware('permission:jemaat.view');
        Route::get('/kartu-jemaat/{data_jemaat}', [KartuJemaatController::class, 'show'])->name('lihatdatakk')->middleware('permission:jemaat.view');
        Route::get('/kartu-jemaat/cetak-kartu/{data_jemaat}', [KartuJemaatController::class, 'cetak_pdf'])->name('cetakpdf')->middleware('permission:jemaat.view');

        Route::get('/data-jemaat-meninggal', [JemaatInAktifController::class, 'meninggal'])->name('datameninggal')->middleware('permission:jemaat.view');
        Route::get('/data-jemaat-pindah', [JemaatInAktifController::class, 'pindah'])->name('datapindah')->middleware('permission:jemaat.view');

        Route::controller(LaporanController::class)->group(function () {
            Route::get('/laporan/tahunan', 'tahunan')->name('laporan.tahunan')->middleware('permission:laporan.view');
            Route::get('/laporan/statistik', 'statistik')->name('laporan.statistik')->middleware('permission:laporan.view');
            Route::get('/laporan/sidi', 'Laporan\SidiController@sidi')->name('laporan.sidi')->middleware('permission:laporan.view');
            Route::get('/laporan/data-sidi', 'Laporan\SidiController@nama')->name('laporan.namasidi')->middleware('permission:laporan.view');
        });

        Route::middleware(['permission:laporan.view'])->group(function () {
            Route::get('/rekap-lingkungan', [RekapDataController::class, 'lingkungan']);
            Route::get('/rekap-kepalakeluarga', [RekapDataController::class, 'kepalakeluarga']);
            Route::get('/rekap-jenis-kelamin', [RekapDataController::class, 'jeniskelamin']);
            Route::get('/rekap-jenis-usia', [RekapDataController::class, 'jenisusia']);
            Route::get('/rekap-status-perkawinan', [RekapDataController::class, 'statusperkawinan']);
            Route::get('/rekap-pendidikan', [RekapDataController::class, 'pendidikan']);
            Route::get('/rekap-pekerjaan', [RekapDataController::class, 'pekerjaan']);
            Route::get('/rekap-pekerjaan/show', [RekapDataController::class, 'getPekerjaan'])->name('getPekerjaan');
            Route::get('/rekap-jemaat-bergabung', [RekapDataController::class, 'jemaatbergabung']);

            Route::get('/grafik-lingkungan', [GrafikController::class, 'lingkungan']);
            Route::get('/grafik-jenis-kelamin', [GrafikController::class, 'jeniskelamin']);
            Route::get('/grafik-jenis-usia', [GrafikController::class, 'jenisusia']);
            Route::get('/grafik-status-perkawinan', [GrafikController::class, 'statusperkawinan']);
            Route::get('/grafik-pendidikan', [GrafikController::class, 'pendidikan']);
            Route::get('/grafik-pekerjaan', [GrafikController::class, 'pekerjaan']);
            Route::get('/grafik-jemaat-bergabung', [GrafikController::class, 'jemaatbergabung']);
        });

        Route::prefix('data-warning')->middleware(['permission:jemaat.view'])->group(function () {
            Route::get('/tanggal-lahir', [DataWarningController::class, 'tanggalLahir'])->name('warning.tanggal-lahir');
            Route::get('/data-tunggal', [DataWarningController::class, 'tunggal'])->name('warning.data-tunggal');
            Route::get('/data-ganda', [DataWarningController::class, 'duplicate'])->name('warning.data-ganda');
        });

        Route::post('/data-keluarga/{id}', [DataJemaatController::class, 'updateDataKeluarga'])->name('update.data-keluarga')->middleware('permission:jemaat.update');

        Route::controller(NotifikasiController::class)->group(function () {
            Route::get('/notifikasi/non-lingkungan', 'index')->name('notif.non-lingkungan')->middleware('permission:jemaat.view');
        });
    });

    // Admin routes - only accessible by superadmin
    Route::middleware(['role:superadmin'])->prefix('admin')->group(function () {
        Route::controller(LingkunganMasterController::class)->group(function () {
            Route::get('/data-lingkungan', 'index')->name('datalingkungan');
            Route::post('/data-lingkungan', 'store')->name('lingkungan.store');
            Route::patch('/data-lingkungan/update/{id}', 'update')->name('lingkungan.update');
            Route::patch('/data-lingkungan/delete/{id}', 'destroy')->name('lingkungan.destroy');
        });

        // User management routes
        Route::get('/users', [UserManagementController::class, 'index'])->name('admin.users.index');
        Route::get('/users/{user}/edit', [UserManagementController::class, 'edit'])->name('admin.users.edit');
        Route::put('/users/{user}', [UserManagementController::class, 'update'])->name('admin.users.update');
        Route::post('/users/{user}/reset-password', [UserManagementController::class, 'resetPassword'])->name('admin.users.reset-password');

        // Role management routes
        Route::resource('roles', RoleController::class)->names([
            'index' => 'admin.roles.index',
            'create' => 'admin.roles.create',
            'store' => 'admin.roles.store',
            'edit' => 'admin.roles.edit',
            'update' => 'admin.roles.update',
            'destroy' => 'admin.roles.destroy',
        ]);
    });

    // Draft Approval Routes (Admin only)
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::controller(ApprovalController::class)->group(function () {
            Route::get('/drafts/pending', 'pending')->name('drafts.pending');
            Route::get('/drafts/{draft}/review', 'review')->name('drafts.review');
            Route::post('/drafts/{draft}/approve', 'approve')->name('drafts.approve');
            Route::post('/drafts/{draft}/reject', 'reject')->name('drafts.reject');
            Route::post('/drafts/{draft}/request-revision', 'requestRevision')->name('drafts.request-revision');
        });
    });
});

Auth::routes();
