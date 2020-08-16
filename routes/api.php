<?php

use Illuminate\Support\Facades\Route;

Route::get('points/{room}', 'TelegramController@getPointsForLastDay')
    ->name('room.points');
