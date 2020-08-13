<?php

namespace App\Telegram\Room;

use App\Infrastructure\Telegram\Command;
use App\Models\Room;
use Hash;
use Illuminate\Auth\Access\AuthorizationException;

class AuthUser extends Command
{
    public function signature(): string
    {
        return '/room_auth {password}';
    }

    public function description(): string
    {
        return 'Authenticate user by password';
    }

    public function handle(Room $room, string $password): void
    {
        $hash = $this->getUserHash();

        if ($room->hasAccess($hash)) {
            $this->bot->reply('You don\'t need auth.');
            return;
        }

        if (!Hash::check($password, $room->password)) {
            throw new AuthorizationException('Incorrect password.');
            return;
        }

        $room->addUser($hash);
        $this->bot->reply('Authenticated!');
    }
}
