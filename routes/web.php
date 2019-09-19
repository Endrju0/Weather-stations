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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/map', 'MapController@index')->name('map');
Route::get('/station/{id}', 'StationController@show')->name('station.show');
Route::get('/station/{id}/edit', 'StationController@edit')->name('station.edit');
Route::patch('/station/{id}', 'StationController@update')->name('station.update');
Route::delete('/station/{id}', 'StationController@destroy')->name('station.destroy');
Route::delete('/station/{id}/restart', 'StationReadingsController@destroy')->name('station-readings.destroy');
Route::get('/station/{id}/key', 'KeyController@update')->name('key.update');

