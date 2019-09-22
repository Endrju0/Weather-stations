<?php

namespace App\Http\Controllers;

use App\Station;
use App\StationReadings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StationReadingsController extends Controller
{
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