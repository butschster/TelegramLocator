<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Telegram Token
    |--------------------------------------------------------------------------
    |
    | Your Telegram bot token you received after creating
    | the chatbot through Telegram.
    |
    */
    'manager' => [
        'token' => env('TELEGRAM_MANAGER_TOKEN'),
        'commands' => [
            App\Telegram\Manager\RegisterUser::class,
            App\Telegram\Manager\CreateRoom::class,
            App\Telegram\Manager\DeleteRoom::class,
        ]
    ],
    'room' => [
        'commands' => [
            App\Telegram\Room\UpdateTitle::class,
            App\Telegram\Room\UpdateDescription::class,
            App\Telegram\Room\SetPassword::class,
            App\Telegram\Room\RemovePassword::class,
            App\Telegram\Room\AuthUser::class,
            App\Telegram\Room\GetInformation::class,
        ]
    ],
];
