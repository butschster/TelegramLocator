<?php

namespace App\Telegram\Room;

use App\Infrastructure\Telegram\ManagerCommand;
use App\Infrastructure\Telegram\StringInput;
use App\Models\Room;

class UpdateDescription extends ManagerCommand
{
    public function signature(): string
    {
        return '/setdescription {description : Room description}';
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
            'description' => $input->getArgument('description')
        ]);

        $this->bot->reply('Room description updated.');
    }

    public function argsRules(): array
    {
        return [
            'description' => ['string'],
        ];
    }
}
