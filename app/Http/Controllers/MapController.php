<?php

namespace App\Http\Controllers;

use App\Station;
use Illuminate\Http\Request;

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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $stations = Station::get();
        $filters = array();
        foreach($stations as $key => $value) {
            $filters[$key]['latitude'] = $value->latitude; 
            $filters[$key]['longitude'] = $value->longitude; 
            $filters[$key]['readings'] = $value->stationReadings()->latest()->first();
        }
        return view('map')->with([
            'stations' => $stations,
            'filters' => $filters,
        ]);
    }
}
