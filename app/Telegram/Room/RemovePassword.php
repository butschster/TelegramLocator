<?php

namespace App\Telegram\Room;

use App\Infrastructure\Telegram\StringInput;
use App\Models\Room;

class RemovePassword extends ManagerCommand
{
    public function signature(): string
    {
        return '/rmpwd';
    }

    public function description(): string
    {
        return trans('app.command.remove_password.description');
    }

    public function handle(StringInput $input): void
    {
        /** @var Room $room */
        $room = $input->getArgument('room');

        $room->update([
            'password' => null
        ]);

        $this->bot->reply(
            trans('app.command.remove_password.updated')
        );
    }
}
