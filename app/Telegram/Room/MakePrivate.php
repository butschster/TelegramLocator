<?php

namespace App\Telegram\Room;

use App\Infrastructure\Telegram\StringInput;
use App\Models\Room;

class MakePrivate extends ManagerCommand
{

    public function signature(): string
    {
        return '/mkprivate';
    }

    public function description(): string
    {
        return trans('app.command.make_private.description');
    }

    public function handle(StringInput $input): void
    {
        /** @var Room $room */
        $room = $input->getArgument('room');

        $room->update([
            'is_public' => false
        ]);

        $this->bot->reply(
            trans('app.command.make_private.updated')
        );
    }
}
