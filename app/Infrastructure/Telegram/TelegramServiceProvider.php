<?php

namespace App\Infrastructure\Telegram;

use App\Infrastructure\Telegram\Contracts\BotManager as BotManagerConstruct;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\Drivers\Telegram\TelegramDriver;
use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;

class TelegramServiceProvider extends ServiceProvider
{
    public function register()
    {
        DriverManager::loadDriver(TelegramDriver::class);

        $this->app->singleton(BotManagerConstruct::class, function () {
            return new BotManager(
                new Client(),
                config('telegram.manager.token'),
                (array) config('telegram.manager.commands'),
                (array) config('telegram.room.commands')
            );
        });
    }
}
