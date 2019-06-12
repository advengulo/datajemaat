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

Route::get('/', function () {
    return view('pages.admin.index');
});

Route::get('/all-jemaat', function(){
    return view('pages.admin.jemaat.jemaat');
});

Route::get('/data-jemaat', 'dataJemaatController@index');
Route::get('/data-jemaat/profile/{data_jemaat}', 'dataJemaatController@show')->name('profiledetail');
Route::get('/data-jemaat/{id}/edit', 'dataJemaatController@edit')->name('jemaatedit');
Route::get('/data-jemaat/profile/{id}/edit', 'dataJemaatController@edit')->name('jemaateditprofile');
Route::patch('/data-jemaat/{id}/update', 'dataJemaatController@update')->name('jemaatupdate');


Route::get('/lihat-data-jemaat', function () {
    return view('pages.admin.jemaat.edit-jemaat');
});



Route::get('/tambah-jemaat', function () {
    return view('pages.admin.jemaat.tambah-jemaat');
});
