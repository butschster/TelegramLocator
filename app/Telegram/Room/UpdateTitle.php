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
        return sprintf(
            '/settitle {title : %s}',
            trans('app.command.update_room_title.arg')
        );
    }

    public function description(): string
    {
        return trans('app.command.update_room_title.description');
    }

    public function handle(StringInput $input): void
    {
        /** @var Room $room */
        $room = $input->getArgument('room');

        $room->update([
            'title' => $input->getArgument('title')
        ]);

        $this->bot->reply(
            trans('app.command.update_room_title.updated')
        );
    }

    public function argsRules(): array
    {
        return [
            'title' => ['string', 'max:255'],
        ];
    }
}
