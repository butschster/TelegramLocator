<?php

namespace App\Telegram\Room;

use App\Infrastructure\Telegram\Command;
use App\Models\Room;

class GetInformation extends Command
{
    public function signature(): string
    {
        return '/room_info';
    }

    public function description(): string
    {
        return 'Get information about room';
    }

    public function handle(Room $room): void
    {
        $this->checkAuthentication($room);

        $information = <<<EOL
ID: {$room->uuid}
Title: {$room->title}
Description: {$room->description}
Total points: {$room->points()->count()}
EOL;

        $this->bot->reply($information);
    }
}
