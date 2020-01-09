<?php

namespace App\Http\Controllers;

use App\Station;
use Carbon\Carbon;
use App\StationReadings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StationReadingsController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        $station = Station::find($id);
        $dates = StationReadings::where('station_id', $id)->pluck('created_at')->toArray();

        // Change datetime to date
        foreach($dates as $key => $date) {
            $dates[$key] = Carbon::parse($date)->format('Y-m-d');
        }
        $dates = array_unique($dates);
        
        $readings = null;
        if($request->input('query') != null) {
            $readings = StationReadings::query()
                            ->where('station_id', $id)
                            ->whereDate('created_at', $request->input('query'))
                            ->orderBy('created_at')
                            ->get();
        }

        return view('station-date')->with([
            'station' => $station,
            'dates' => $dates,
            'readings' => $readings,
            'query' => $request->input('query'),
        ]);
    }

    /**
     * Remove the specified station readings from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $readings = StationReadings::where('station_id', $id);
        if(Auth::id() != Station::where('id', $id)->pluck('user_id')->first()) return back()->withErrors('You\'re not allowed to do this.');
        $readings->delete();
        return  back()->with('success_message', 'Successfully restarted station\'s readings.');
    }
}
