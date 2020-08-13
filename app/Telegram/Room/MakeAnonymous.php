<?php

namespace App\Telegram\Room;

use App\Infrastructure\Telegram\ManagerCommand;
use App\Models\Room;

class MakeAnonymous extends ManagerCommand
{

    public function signature(): string
    {
        return '/room_make_anonymous';
    }

    public function description(): string
    {
        return 'Make room anonymous';
    }

    public function handle(Room $room)
    {
        $room->update([
            'is_anonymous' => true
        ]);

        $room->points()->update([
            'username' => null
        ]);

        $this->bot->reply('Done. All username were cleared.');
    }
}
