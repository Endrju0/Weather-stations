<?php

namespace App\Http\Controllers;

use App\Station;
use App\StationReadings;
use Illuminate\Http\Request;

class StationApiController extends Controller
{
    /**
     * Get stations on leaflet js
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $stations = Station::all();

        $geoJSON = $stations->map(function ($station) {
            return [
                'type'       => 'Feature',
                'properties' => [
                    "name" => $station->name,
                    "stationID" => $station->id,
                ],
                'geometry'   => [
                    'type'        => 'Point',
                    'coordinates' => [
                        $station->longitude,
                        $station->latitude,
                    ],
                ],
            ];
        });
        return response()->json([
            'type'     => 'FeatureCollection',
            'features' => $geoJSON,
        ]);
    }
}
