<?php

namespace App\Http\Controllers;

use App\Station;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KeyController extends Controller
{
    /**
     * Generate new key
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $station = Station::find($id);
        if(Auth::id() != $station->user_id) return back()->withErrors('You\'re not allowed to do this.');
        $station->key = Str::random(20);
        $station->save();

        return back()->with('success_message', 'Successfully generated new key.');
    }
}
