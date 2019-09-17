<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//send readings to database
Route::post('readings', 'StationReadingsApiController@store');
Route::get('stations', 'StationApiController@index')->name('stations.index');
Route::get('show/{stationID}', 'StationReadingsApiController@show')->name('readings.show');