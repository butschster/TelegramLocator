<?php

namespace App\Telegram\Room;

use App\Infrastructure\Telegram\Command;
use App\Infrastructure\Telegram\StringInput;
use App\Models\Room;

class GetInformation extends Command
{
    public function signature(): string
    {
        return '/info';
    }

    public function description(): string
    {
        return 'Get information about room';
    }

    public function handle(StringInput $input): void
    {
        /** @var Room $room */
        $room = $input->getArgument('room');
        $this->checkAuthentication($room);
        $isAnonymous = $room->is_anonymous ? 'Yes' : 'No';
        $isPublic = $room->is_public ? 'Yes' : 'No';
        $hasPassword = $room->hasPassword() ? 'Yes' : 'No';
        $publicUrl = route('room.points', $room);

        $information = <<<EOL
ID: {$room->uuid}
Title: {$room->title}
Description: {$room->description}
Total points: {$room->notExpiredPoints()->count()}
Anonymous: {$isAnonymous}
Public: {$isPublic}
Password required: {$hasPassword}
Last activity: {$room->lastActivity()}
Public url: {$publicUrl}
EOL;

        $this->bot->reply($information);
    }
}
