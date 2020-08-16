<?php

use Illuminate\Support\Facades\Route;

Route::post('/webhook/telegram/room/{room}', 'TelegramController@roomWebhook')
    ->name('telegram.webhook.room');

Route::post('/webhook/telegram/manager', 'TelegramController@managerWebhook')
    ->name('telegram.webhook.manager');
