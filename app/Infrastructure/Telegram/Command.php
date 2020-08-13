<?php

namespace App\Infrastructure\Telegram;

use App\Models\Room;
use App\Models\User;
use BotMan\BotMan\BotMan;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

abstract class Command implements Contracts\Command
{
    protected BotMan $bot;

    public function __construct(BotMan $bot)
    {
        $this->bot = $bot;
    }

    public function forManager(): bool
    {
        return false;
    }

    public function getManager(): User
    {
        try {
            return User::findOrFail($this->getUserHash());
        } catch (ModelNotFoundException $e) {
            throw new AuthorizationException(
                'You should register an account. Use /register command.'
            );
        }
    }

    public function getUserHash(): string
    {
        return hash_hmac(
            'sha1',
            $this->bot->getUser()->getId(),
            config('app.key')
        );
    }

    public function checkAuthentication(Room $room): void
    {
        $userHash = $this->getUserHash();
        if (!$room->hasAccess($userHash)) {
            throw new AuthorizationException('Unauthorized! Please use /room_auth for authentication.');
        }
    }
}
