<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->point('location')->nullable();
            $table->string('telegram_token')->unique();
            $table->string('password')->nullable();
            $table->boolean('is_public')->default(true);
            $table->boolean('is_anonymous')->default(true);

            $table->string('user_id');

            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rooms');
    }
}
