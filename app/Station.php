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

    // Delete station with its readings
    public static function boot() {
        parent::boot();

        static::deleting(function($station) {
             $station->stationReadings()->delete();
        });
    }
}
