<?php

namespace App\Telegram\Room;

use App\Infrastructure\Telegram\StringInput;
use App\Models\Room;

class MakeAnonymous extends ManagerCommand
{

    public function signature(): string
    {
        return '/mkanonymous';
    }

    public function description(): string
    {
        return trans('app.command.make_anonymous.description');
    }

    public function handle(StringInput $input): void
    {
        /** @var Room $room */
        $room = $input->getArgument('room');

        $room->update([
            'is_anonymous' => true
        ]);

        $this->bot->reply(
            trans('app.command.make_anonymous.updated')
        );
    }
}
