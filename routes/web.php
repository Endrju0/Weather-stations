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
    return view('welcome');
});

Auth::routes(['verify' => true]);
Route::middleware('verified')->group(function () {
    Route::get('/map', 'MapController@index')->name('map');
    Route::get('/list', 'StationListController@index')->name('station-list.index');
    Route::get('/station/create', 'StationController@create')->name('station.create');
    Route::post('/station', 'StationController@store')->name('station.store');
    Route::get('/station/{id}', 'StationController@show')->name('station.show');
    Route::get('/station/{id}/edit', 'StationController@edit')->name('station.edit');
    Route::patch('/station/{id}', 'StationController@update')->name('station.update');
    Route::delete('/station/{id}', 'StationController@destroy')->name('station.destroy');
    Route::get('/station/{id}/date', 'StationReadingsController@show')->name('station.date.show');
    Route::get('/station/{id}/key', 'KeyController@update')->name('key.update');
    Route::delete('/station/{id}/restart', 'StationReadingsController@destroy')->name('station-readings.destroy');
    Route::get('/station/{id}/pdf', 'StationController@pdf')->name('station.pdf');
    Route::get('/settings', 'UserController@edit')->name('settings.edit');
    Route::post('/settings', 'UserController@update')->name('settings.update');
});

Route::middleware('ajax')->group(function() {
    Route::get('/stations', 'MapController@stationsLatlng')->name('stations.index');
    Route::get('/show/{stationID}', 'MapController@showLatestReadings')->name('readings.show');
});
