<?php

namespace App\Http\Controllers;

use App\User;
use App\Station;
use App\StationReadings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MapController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display map with station points
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $stations = Station::get();
        $user = User::find(Auth::id());
        $filters = array();
        foreach($stations as $key => $value) {
            $filters[$key]['latitude'] = $value->latitude; 
            $filters[$key]['longitude'] = $value->longitude; 
            $filters[$key]['readings'] = $value->stationReadings()->latest()->first();
        }
        return view('map')->with([
            'stations' => $stations,
            'filters' => $filters,
            'center' => $user->center_latlng,
        ]);
    }

    /**
     * Get stations latlng
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function stationsLatlng()
    {
        $stations = Station::all();
        $geoJSON = $stations->map(function ($station) {
            return [
                'type'       => 'Feature',
                'properties' => [
                    "name" => $station->name,
                    "stationID" => $station->id,
                    "ownerID" => $station->user_id
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

    /**
     * Get latest specific station readings
     *
     * @param  int  $stationID
     * @return \Illuminate\Http\Response
     */
    public function showLatestReadings($stationID)
    {
        $readings = StationReadings::where('station_id', '=', $stationID)->latest()->firstOrFail();

        return response()->json([
                // 'name' => $readings->station->name,
                'temperature' => $readings->temperature,
                'pressure' => $readings->pressure,
                'humidity' => $readings->humidity,
                'timestamp' => $readings->created_at,
        ]);
    }
}
