<?php

namespace App\Telegram\Room;

use App\Infrastructure\Telegram\ManagerCommand;
use App\Models\Room;

class UpdateDescription extends ManagerCommand
{
    public function signature(): string
    {
        return '/room_description {description}';
    }

    public function description(): string
    {
        return 'Update room title';
    }

    public function handle(Room $room, string $description)
    {
        $room->update([
            'description' => $description
        ]);

        $this->bot->reply('Room description updated.');
    }
}
