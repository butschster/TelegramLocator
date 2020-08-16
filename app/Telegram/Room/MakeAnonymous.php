<?php

namespace App\Telegram\Room;

use App\Infrastructure\Telegram\ManagerCommand;
use App\Infrastructure\Telegram\StringInput;
use App\Models\Room;

class MakeAnonymous extends ManagerCommand
{

    public function signature(): string
    {
        return '/makeanonymous';
    }

    public function description(): string
    {
        return 'Make room anonymous';
    }

    public function handle(StringInput $input): void
    {
        /** @var Room $room */
        $room = $input->getArgument('room');

        $room->update([
            'is_anonymous' => true
        ]);

        $room->points()->update([
            'username' => null
        ]);

        $this->bot->reply('Done. All username were cleared.');
    }
}
