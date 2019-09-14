<?php

use App\Station;
use Illuminate\Database\Seeder;

class StationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 3; $i++) {
            Station::create([
                'name' => 'Stacja nr.' . $i,
                'key' => Str::random(20),
                'latitude' => (50.252091283171 + 0.05 * $i),
                'longitude' => (19.012784957885 + 0.05 * $i),
                'user_id' => 1,
            ]);
        }
    }
}
