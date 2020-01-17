<?php

namespace App\Http\Controllers;

use App\User;
use App\Station;
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
}
