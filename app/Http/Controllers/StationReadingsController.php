<?php

namespace App\Http\Controllers;

use App\StationReadings;
use Illuminate\Http\Request;

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
        $readings->delete();
        return  back()->with('success_message', 'Successfully restarted station\'s readings.');
    }
}
