<?php

return [
    'webhook_secret' => env('WEBHOOK_SECRET'),
    'middleware' => [
        App\Telegram\MIddleware\DetectUserLocale::class,
    ],

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
            App\Telegram\Manager\SearchNearestRoms::class,
        ]
    ],
    'room' => [
        'commands' => [
            App\Telegram\Room\UpdateTitle::class,
            App\Telegram\Room\UpdateDescription::class,
            App\Telegram\Room\UpdateRoomLocation::class,
            App\Telegram\Room\MakeAnonymous::class,
            App\Telegram\Room\MakePrivate::class,
            App\Telegram\Room\MakePublic::class,
            App\Telegram\Room\DownloadGeoJson::class,
            App\Telegram\Room\SetPassword::class,
            App\Telegram\Room\RemovePassword::class,
            App\Telegram\Room\SetPointsLifeTime::class,
            App\Telegram\Room\AuthUser::class,
            App\Telegram\Room\GetInformation::class,
            App\Telegram\Room\StoreLocation::class,
            App\Telegram\Room\SendMessage::class,
        ]
    ],
];
