<?php

namespace App\Telegram\Room;

use App\Infrastructure\Telegram\StringInput;
use App\Models\Room;
use Hash;

class SetPassword extends ManagerCommand
{
    public function signature(): string
    {
        return sprintf(
            '/setpwd {password : %s}',
            trans('app.command.set_password.password')
        );
    }

    public function description(): string
    {
        return trans('app.command.set_password.description');
    }

    public function handle(StringInput $input): void
    {
        /** @var Room $room */
        $room = $input->getArgument('room');

        $room->update([
            'password' => Hash::make(
                $input->getArgument('password')
            )
        ]);

        $this->bot->reply(
            trans('app.command.set_password.updated')
        );
    }

    public function argsRules(): array
    {
        return [
            'password' => ['required', 'string', 'min:6', 'max:128'],
        ];
    }
}
