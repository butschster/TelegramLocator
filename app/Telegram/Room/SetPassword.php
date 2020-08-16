<?php

namespace App\Telegram\Room;

use App\Infrastructure\Telegram\ManagerCommand;
use App\Infrastructure\Telegram\StringInput;
use App\Models\Room;
use Hash;

class SetPassword extends ManagerCommand
{
    public function signature(): string
    {
        return '/setpwd {password : Room password}';
    }

    public function description(): string
    {
        return 'Set room password';
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

        $this->bot->reply('Password for room set.');
    }

    public function argsRules(): array
    {
        return [
            'password' => ['required', 'string', 'min:1', 'max:128'],
        ];
    }
}
