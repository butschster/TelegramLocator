<?php

namespace App\Telegram\Room;

use App\Infrastructure\Telegram\Command;
use App\Infrastructure\Telegram\StringInput;
use App\Models\Room;
use Hash;
use Illuminate\Auth\Access\AuthorizationException;

class AuthUser extends Command
{
    public function signature(): string
    {
        return '/auth {password : Room password}';
    }

    public function description(): string
    {
        return 'Authenticate user by password';
    }

    public function handle(StringInput $input): void
    {
        /** @var Room $room */
        $room = $input->getArgument('room');
        $password = $input->getArgument('password');
        $user = $this->getUser();

        if ($room->hasAccess($user)) {
            $this->bot->reply('You don\'t need auth.');
            return;
        }

        if (!Hash::check($password, $room->password)) {
            throw new AuthorizationException('Incorrect password.');
        }

        $room->addUser($user);
        $this->bot->reply('Authenticated!');
    }
}
