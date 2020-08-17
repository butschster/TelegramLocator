<?php

namespace App\Infrastructure\Telegram;

use Telegram\Bot\Objects\User;

class Api implements Contracts\Api
{
    public function getMe(string $token): User
    {
        $client = new \Telegram\Bot\Api($token);

        return $client->getMe();
    }
}
