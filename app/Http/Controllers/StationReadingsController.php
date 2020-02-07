<?php

namespace App\Http\Controllers;

use App\Station;
use Carbon\Carbon;
use App\StationReadings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StationReadingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        $station = Station::find($id);
        
        // Select unique dates, when station got readings
        $dates = StationReadings::where('station_id', $id)->pluck('created_at')->toArray();
        foreach($dates as $key => $date) {
            $dates[$key] = Carbon::parse($date)->format('Y-m-d');
        }
        $dates = array_unique($dates);
        
        $readings = null;
        if($request->input('query') != null) {
            $dt1 = Carbon::create($request->input('query'));
            $dt2 = Carbon::create($request->input('query_range'));
            $query = StationReadings::query()->where('station_id', $id);

            if($request->input('query_range') != null ) {
                if($dt1->greaterThan($dt2)) {
                    $query->whereDate('created_at', '>=', $request->input('query_range'));
                    $query->whereDate('created_at', '<=', $request->input('query'));
                } else {
                    $query->whereDate('created_at', '>=', $request->input('query'));
                    $query->whereDate('created_at', '<=', $request->input('query_range'));
                }
            } else {
                $query->whereDate('created_at', $request->input('query'));
            }
            $query->orderBy('created_at');
            $readings = $query->get();
        }


        return view('station-date')->with([
            'station' => $station,
            'dates' => $dates,
            'readings' => $readings,
            'query' => $request->input('query'),
            'query_range' => $request->input('query_range'),
            'check_range' => $request->input('check_range') ,
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
