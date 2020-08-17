<?php

namespace App\Infrastructure\Telegram\Contracts;

use Telegram\Bot\Exceptions\TelegramSDKException;
use Telegram\Bot\Objects\User;

interface Api
{
    /**
     * A simple method for testing your bot's auth token.
     * Returns basic information about the bot in form of a User object.
     *
     * @link https://core.telegram.org/bots/api#getme
     *
     * @param string $token
     * @return User
     * @throws TelegramSDKException
     */
    public function getMe(string $token): User;
}
