<?php

namespace App\Telegram\Room;

use App\Infrastructure\Telegram\ManagerCommand;
use App\Models\Room;
use Hash;

class SetPassword extends ManagerCommand
{
    public function signature(): string
    {
        return '/room_set_password {password}';
    }

    public function description(): string
    {
        return 'Set room password';
    }

    public function handle(Room $room, string $password)
    {
        $room->update([
            'password' => Hash::make($password)
        ]);

        $this->bot->reply('Password for room set.');
    }
}
