<?php

namespace App\Telegram\Room;

use App\Infrastructure\Telegram\StringInput;
use App\Models\Room;

class UpdateDescription extends ManagerCommand
{
    public function signature(): string
    {
        return sprintf(
            '/setdescription {description : %s}',
            trans('app.command.update_room_description.arg')
        );
    }

    public function description(): string
    {
        return trans('app.command.update_room_description.description');
    }

    public function handle(StringInput $input): void
    {
        /** @var Room $room */
        $room = $input->getArgument('room');

        $room->update([
            'description' => $input->getArgument('description')
        ]);

        $this->bot->reply(
            trans('app.command.update_room_description.updated')
        );
    }

    public function argsRules(): array
    {
        return [
            'description' => ['string'],
        ];
    }
}
