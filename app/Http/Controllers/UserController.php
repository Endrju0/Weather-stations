<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
     /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show settings
     *
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $user = User::find(Auth::id());

        return view('settings')->with([
            'user' => $user
        ]);
    }

    /**
     * Update settings
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $user = User::find(Auth::id());
        $user->name = $request->name;
        if($request->latitude != null && $request->longitude != null)
            $user->center_latlng = array($request->latitude, $request->longitude);
        else $user->center_latlng = null;
        $user->save();

        return redirect('map')->with('success_message', 'Successfully updated settings.');
    }
}
