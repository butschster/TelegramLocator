<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoomLegendTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('room_legend', function (Blueprint $table) {
            $table->id();
            $table->uuid('room_uuid');
            $table->string('key');
            $table->string('title');
            $table->text('description')->nullable();

            $table->timestamps();

            $table->unique(['room_uuid', 'key']);

            $table->foreign('room_uuid')
                ->references('uuid')
                ->on('rooms');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('room_legend');
    }
}
