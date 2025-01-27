<?php

namespace App\Telegram\Room;

use App\Infrastructure\Telegram\StringInput;
use App\Models\Room;

class MakePublic extends ManagerCommand
{

    public function signature(): string
    {
        return '/mkpublic';
    }

    public function description(): string
    {
        return trans('app.command.make_public.description');
    }

    public function handle(StringInput $input): void
    {
        /** @var Room $room */
        $room = $input->getArgument('room');

        $room->update([
            'is_public' => true
        ]);

        $this->bot->reply(
            trans('app.command.make_public.updated')
        );
    }
}
