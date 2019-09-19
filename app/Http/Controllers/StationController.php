<?php

namespace App\Http\Controllers;

use App\Station;
use App\StationReadings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $station = Station::where('id', $id)->firstOrFail();
        $stationReadings = StationReadings::where('station_id', $id)->get();
        
        return view('station', compact('station', 'stationReadings'));
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
        $station->delete();
        return redirect()->route('map', $id)->with('success_message', 'Successfully deleted station.');
    }
}
