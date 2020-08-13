<?php

namespace App\Telegram\Room;

use App\Infrastructure\Telegram\ManagerCommand;
use App\Models\Room;

class RemovePassword extends ManagerCommand
{
    public function signature(): string
    {
        return '/room_remove_password';
    }

    public function description(): string
    {
        return 'Remove room password';
    }

    public function handle(Room $room)
    {
        $room->update([
            'password' => null
        ]);

        $this->bot->reply('Password for room removed.');
    }
}
