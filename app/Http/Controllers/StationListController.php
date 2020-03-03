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
    public function index(Request $request)
    {
        $stationQuery = Station::query();
        $stationQuery->where('name', 'like', '%'.$request->name.'%');
        if($request->self != null && $request->self == 'on')
            $stationQuery->where('user_id', Auth::id());
        $stations = $stationQuery->paginate(5);

        return view('station-list')->with([
            'stations' => $stations,
            'name' => $request->name,
            'self' => $request->self
        ]);
    }
}
