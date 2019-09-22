<?php

namespace App\Http\Controllers;

use App\Station;
use Illuminate\Http\Request;

class StationListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $stationQuery = Station::query();
        $stationQuery->where('name', 'like', '%'.request('query').'%');
        $stations = $stationQuery->paginate(25);

        return view('station-list', compact('stations'));
    }
}
