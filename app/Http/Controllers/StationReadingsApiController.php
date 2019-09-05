<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\StationReadings;
use App\Http\Resources\StationReadings as StationReadingsResource;
use Illuminate\Support\Facades\Validator;


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
        ]);

        if($validator->fails()) {
            return response()->json($validator->messages(), 400);
        }

        $readings = new StationReadings();
        $readings->temperature = $request->input('temperature');
        $readings->pressure = $request->input('pressure');
        $readings->humidity = $request->input('humidity');

        if($readings->save()) {
            return new StationReadingsResource($readings);
        }
    }
}
