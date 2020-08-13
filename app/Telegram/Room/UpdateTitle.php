<?php

namespace App\Telegram\Room;

use App\Infrastructure\Telegram\ManagerCommand;
use App\Models\Room;

class UpdateTitle extends ManagerCommand
{
    public function signature(): string
    {
        return '/room_title {title}';
    }

    public function description(): string
    {
        return 'Update room title';
    }

    public function handle(Room $room, string $title)
    {
        $room->update([
            'title' => $title
        ]);

        $this->bot->reply('Room title updated.');
    }
}
