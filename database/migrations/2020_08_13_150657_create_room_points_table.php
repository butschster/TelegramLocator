<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoomPointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mongodb')->create('room_points', function (Blueprint $table) {
            $table->id();
            $table->uuid('room_uuid')->index();
            $table->string('owner_hash');
            $table->string('username')->nullable();
            $table->point('location');
            $table->text('message')->nullable();
            $table->unique(['room_uuid', 'owner_hash']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('room_points');
    }
}
