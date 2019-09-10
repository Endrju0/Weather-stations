<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\StationReadings;
use Faker\Generator as Faker;

$factory->define(StationReadings::class, function (Faker $faker) {
    return [
        'temperature' => $faker->numberBetween(0, 100),
        'pressure' => $faker->numberBetween(0, 100),
        'humidity' => $faker->numberBetween(0, 100),
        'station_id' => $faker->numberBetween(1, 3),
    ];
});
