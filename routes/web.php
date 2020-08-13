<?php

use App\Infrastructure\Telegram\Contracts\BotManager;
use App\Models\Room;
use Illuminate\Support\Facades\Route;

Route::get('', function () {
   return view('welcome');
});

Route::any('/webhook/telegram/room/{room}', function (\Illuminate\Http\Request $request, BotManager $bots, Room $room) {
    logger()->debug('telegram', $request->all());

    $bots->forRoom($room)->handleCommand();
})->name('telegram.webhook.room');

Route::any('/webhook/telegram/manager', function (\Illuminate\Http\Request $request, BotManager $bots) {
    logger()->debug('telegram', $request->all());

    $bots->forManager()->handleCommand();
})->name('telegram.webhook.manager');
