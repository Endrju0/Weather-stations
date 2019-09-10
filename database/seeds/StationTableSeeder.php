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
                'user_id' => 1,
            ]);
        }
    }
}
