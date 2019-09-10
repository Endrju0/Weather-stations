<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\StationReadings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\StationReadings as StationReadingsResource;
use App\Station;


class StationReadingsApiController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'temperature' => 'required|numeric|between:-100,100',
            'pressure' => 'required|numeric|between:969,1020',
            'humidity' => 'required|numeric|between:0,100',
            'key' => 'required'
        ]);

        if($validator->fails()) {
            return response()->json($validator->messages(), 400);
        }

        $readings = new StationReadings();
        $readings->temperature = $request->input('temperature');
        $readings->pressure = $request->input('pressure');
        $readings->humidity = $request->input('humidity');

        if(DB::table('station')->where('key', $request->key)->exists()) {

            $station = Station::where(['key'=>$request->key])->firstOrFail();
            $readings->station()->associate($station);

            if($readings->save()) {
                return new StationReadingsResource($readings);
                // return response()->json($readings);
            }
        }
        return response()->json(['Invalid token'], 400);
    }
}