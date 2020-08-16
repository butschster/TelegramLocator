<?php

use Illuminate\Support\Facades\Route;

Route::get('points/{room}', 'TelegramController@getPointsForLastDay')
    ->name('room.points');

$webhookSecret = config('telegram.webhook_secret');

Route::any('webhook/'.$webhookSecret.'/manager', 'TelegramController@managerWebhook')
    ->name('telegram.webhook.manager');
Route::any('webhook/'.$webhookSecret.'/{room}', 'TelegramController@roomWebhook')
    ->name('telegram.webhook.room');
