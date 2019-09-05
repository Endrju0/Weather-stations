<?php

use Illuminate\Database\Seeder;

class StationReadingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\StationReadings::class, 10)->create();
    }
}
