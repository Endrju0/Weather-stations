<?php

namespace App\Http\Controllers;

use App\Station;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $stationQuery->where('name', 'like', '%'.request('query').'%')->where('user_id', Auth::id());
        $stations = $stationQuery->paginate(5);

        return view('station-list', compact('stations'));
    }
}
