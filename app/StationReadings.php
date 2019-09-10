<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StationReadings extends Model
{
    /* https://laravel.com/docs/5.6/eloquent#defining-models
       Timestamps heading */
    public $timestamps = false;

    public function station() {
        return $this->belongsTo('App\Station');
    }
}
