<?php

namespace App\Http\Resources;

use Illuminate\Support\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class StationReadings extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            // 'id' => $this->id,
            'temperature' => $this->temperature,
            'pressure' => $this->pressure,
            'humidity' => $this->humidity,
            'station_name' => $this->station->name,
            'post_date' => Carbon::now()->toDateTimeString(),
        ];
    }
}
