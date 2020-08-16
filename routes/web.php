<?php

use Illuminate\Support\Facades\Route;

Route::get('map/{room}', 'TelegramController@map')->name('map');
