<?php

namespace App\Http\Controllers;

use App\User;
use App\Station;
use App\Http\Requests;
use App\StationReadings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\StationReadings as StationReadingsResource;


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
        // basic validation
        $validator = Validator::make($request->all(), [
            'temperature' => 'required|numeric|between:-100,100',
            'pressure' => 'required|numeric|between:969,1020',
            'humidity' => 'required|numeric|between:0,100',
            'key' => 'required',
            'email' => 'required'
        ]);

        if($validator->fails()) {
            return response()->json($validator->messages(), 400);
        }

        // check if email exists in base to prevent wrong api response (and if exists get model)
        if(DB::table('users')->where('email', $request->email)->exists()) {
            $user = User::where('email', $request->email)->firstOrFail();
        } else return response()->json(['Invalid email'], 400);

        // if validation is successful
        $readings = new StationReadings();
        $readings->temperature = $request->input('temperature');
        $readings->pressure = $request->input('pressure');
        $readings->humidity = $request->input('humidity');

        // check if user email and station token matches
        if(DB::table('station')->where('key', $request->key)->where('user_id', $user->id)->exists()) {

            $station = Station::where(['key'=>$request->key])->firstOrFail();
            $readings->station()->associate($station);

            //if everything is ok insert data in db and respond
            if($readings->save()) {
                return new StationReadingsResource($readings);
                // return response()->json($readings);
            }
        }
        return response()->json(['Invalid token'], 400);
    }
}