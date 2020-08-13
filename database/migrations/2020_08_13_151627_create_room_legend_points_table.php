<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoomLegendPointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('room_legend_points', function (Blueprint $table) {
            $table->uuid('room_uuid')->index();
            $table->unsignedBigInteger('legend_id');
            $table->point('location');

            $table->timestamps();

            $table->foreign('room_uuid')
                ->references('uuid')
                ->on('rooms');

            $table->foreign('legend_id')
                ->references('id')
                ->on('room_legend');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('room_legend_points');
    }
}
