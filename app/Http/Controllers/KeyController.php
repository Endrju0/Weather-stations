<?php

namespace App\Http\Controllers;

use App\Station;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
        $station->key = Str::random(20);
        $station->save();

        return back()->with('success_message', 'Successfully generated new key.');
    }
}
