<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStationReadingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('station_readings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->double('temperature', 6, 3);
            $table->double('pressure', 7, 3);
            $table->double('humidity', 6, 3);

            $table->unsignedBigInteger('station_id');
            $table->foreign('station_id')->references('id')->on('station')->onDelete('cascade');

            $table->timestamp('created_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));

            $table->index('station_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('station_readings');
    }
}
