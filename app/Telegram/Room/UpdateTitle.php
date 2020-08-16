<?php

namespace App\Telegram\Room;

use App\Infrastructure\Telegram\ManagerCommand;
use App\Infrastructure\Telegram\StringInput;
use App\Models\Room;
use App\Validation\Rules\Latitude;
use App\Validation\Rules\Longitude;

class UpdateTitle extends ManagerCommand
{
    public function signature(): string
    {
        return '/settitle {title : Room title}';
    }

    public function description(): string
    {
        return 'Update room title';
    }

    public function handle(StringInput $input): void
    {
        /** @var Room $room */
        $room = $input->getArgument('room');

        $room->update([
            'title' => $input->getArgument('title')
        ]);

        $this->bot->reply('Room title updated.');
    }

    public function argsRules(): array
    {
        return [
            'title' => ['string', 'max:255'],
        ];
    }
}
