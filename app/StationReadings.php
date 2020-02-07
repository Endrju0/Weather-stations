<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\Model;

class StationReadings extends Model
{
    /* https://laravel.com/docs/5.6/eloquent#defining-models
       Timestamps heading */
    public $timestamps = false;

    public function station() {
        return $this->belongsTo(Station::class);
    }

    public function getCreatedAtAttribute($value) {
        $timezone = $this->station->user->timezone ? $this->station->user->timezone : Config::get('app.timezone');
        return Carbon::createFromTimestamp(strtotime($value))
            ->timezone($timezone)
            ->toDateTimeString();
    }
}
