<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(App\StationReadings::class, function (Faker $faker) {
    return [
        'temperature' => $faker->numberBetween(0, 100),
        'pressure' => $faker->numberBetween(0, 100),
        'humidity' => $faker->numberBetween(0, 100)
    ];
});
