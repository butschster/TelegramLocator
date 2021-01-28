<?php

return [
    'webhook_secret' => env('WEBHOOK_SECRET'),

    'middleware' => [
        App\Telegram\Middleware\DetectUserLocale::class,
    ],

    'matchers' => [
        App\Telegram\Matchers\Command::class,
        App\Telegram\Matchers\LocationCommand::class,
        App\Telegram\Matchers\Conversation::class,
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
        'bot_name' => env('TELEGRAM_MANAGER_BOT_NAME'),

        'commands' => [
            App\Telegram\Help::class,
            App\Telegram\Manager\RegisterUser::class,
            App\Telegram\Manager\CreateRoom::class,
            App\Telegram\Manager\DeleteRoom::class,
            App\Telegram\Manager\SearchNearestRoms::class,
            App\Telegram\Manager\CheckRoomSignature::class,
            App\Telegram\Commands::class,
        ]
    ],
    'room' => [
        'commands' => [
            App\Telegram\Help::class,
            App\Telegram\Room\UpdateTitle::class,
            App\Telegram\Room\UpdateDescription::class,
            App\Telegram\Room\UpdateRoomLocation::class,
            App\Telegram\Room\MakeAnonymous::class,
            App\Telegram\Room\MakePrivate::class,
            App\Telegram\Room\MakePublic::class,
            App\Telegram\Room\DownloadGeoJson::class,
            App\Telegram\Room\SetPassword::class,
            App\Telegram\Room\SetJitter::class,
            App\Telegram\Room\RemovePassword::class,
            App\Telegram\Room\SetPointsLifeTime::class,
            App\Telegram\Room\AuthUser::class,
            App\Telegram\Room\GetInformation::class,
            App\Telegram\Room\StoreLocation::class,
            App\Telegram\Room\SendMessage::class,
            App\Telegram\Room\Start::class,
            App\Telegram\Commands::class,
        ]
    ],
];
