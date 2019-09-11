<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Station extends Model
{
    protected $table = 'station';

    public function stationReadings() {
        return $this->hasMany(StationReadings::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
