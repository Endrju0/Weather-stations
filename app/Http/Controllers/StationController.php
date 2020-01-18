<?php

namespace App\Http\Controllers;

use App\Station;
use Carbon\Carbon;
use App\StationReadings;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class StationController extends Controller
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Auth::guest()) return redirect()->route('map')->withErrors('You\'re not allowed to do this.');
        $key = Str::random(20);
        session()->flash('key', $key);
        return view('station-create')->with('key', $key);;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:3|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric'
        ]);
        $user = Auth::user();
        
        if($validator->fails()) {
            return back()->withErrors($validator);
        } else {
            $station = new Station;
            $station->name = $request->name;
            $station->latitude = $request->latitude;
            $station->longitude = $request->longitude;
            $station->key = $request->session()->get('key');
            $station->user_id = $user->id;
            $station->save();

            return redirect()->route('map')->with('success_message', 'Successfully created station.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        switch($request->filter) {
            case 'week': 
                $date = Carbon::now()->subWeek()->toDateTimeString();
                $filter = 'Last week';
                break;
            case 'month': 
                $date = Carbon::now()->subMonth()->toDateTimeString();
                $filter = 'Last month';
                break;
            default: 
                $date = Carbon::now()->subDay()->toDateTimeString();
                $filter = 'Last day';
        }
        
        $station = Station::where('id', $id)->firstOrFail();
        $stationReadings = StationReadings::where('station_id', $id)
                            ->where('created_at', '>=', $date)
                            ->orderBy('created_at', 'desc')
                            ->get()
                            ->reverse()
                            ->values();

        return view('station', compact('station', 'stationReadings', 'filter'));
    }

    /**
     * Show edit view of station (name, latlng etc)
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $station = Station::find($id);
        if(Auth::id() != $station->user_id) return back()->withErrors('You\'re not allowed to do this.');

        return view('station-edit')->with('station', $station);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:3|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric'
        ]);

        if($validator->fails()) {
            return back()->withErrors($validator);
        } else {
            $station = Station::find($id);
            if(Auth::id() != $station->user_id) return back()->withErrors('You\'re not allowed to do this.');
            $station->name = $request->name;
            $station->latitude = $request->latitude;
            $station->longitude = $request->longitude;
            $station->save();

            return redirect()->route('station.show', $id)->with('success_message', 'Successfully updated station.');
        }
    }

    /**
     * Remove the specified station with its readings from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $station = Station::find($id);
        if(Auth::id() != $station->user_id) return back()->withErrors('You\'re not allowed to do this.');
        $station->delete();
        return redirect()->route('map', $id)->with('success_message', 'Successfully deleted station.');
    }

    /**
     * Export station data to pdf
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function pdf($id)
    {        
        $station = Station::find($id);
        $readings = StationReadings::where('station_id', $id);
        if($readings->get()->isEmpty()) {
            return redirect()->back()->withErrors('There isn\'t any data to export!');
        }

        $avgTemperature = $readings->avg('temperature');
        $avgHumidity = $readings->avg('humidity');
        $avgPressure = $readings->avg('pressure');
        
        $minTemperature = $readings->orderBy('temperature', 'ASC')->first();
        $minHumidity = $readings->orderBy('humidity', 'ASC')->first();
        $minPressure = $readings->orderBy('pressure', 'ASC')->first();

        $maxTemperature = $readings->orderBy('temperature', 'DESC')->first();
        $maxHumidity = $readings->orderBy('humidity', 'DESC')->first();
        $maxPressure = $readings->orderBy('pressure', 'DESC')->first();
    
        $pdf = PDF::loadView('pdf', [
            'station' => $station,
            'date' => \Carbon\Carbon::now(),
            'avgTemperature' => $avgTemperature,
            'avgHumidity' => $avgHumidity,
            'avgPressure' => $avgPressure,
            'minTemperature' => $minTemperature,
            'minHumidity' => $minHumidity,
            'minPressure' => $minPressure,
            'maxTemperature' => $maxTemperature,
            'maxHumidity' => $maxHumidity,
            'maxPressure' => $maxPressure
        ]);
        return $pdf->download('stations-data.pdf');
    }
}
