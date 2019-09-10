<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Station extends Model
{
    protected $table = 'station';

    public function stationReadings() {
        return $this->hasMany('App\StationReadings');
    }

    public function station() {
        return $this->belongsTo('App\User');
    }
}
