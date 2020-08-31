<?php

namespace App\Telegram\Room;

use App\Infrastructure\Telegram\StringInput;
use App\Models\Room;

class SetJitter extends ManagerCommand
{
    public function signature(): string
    {
        return sprintf(
            '/setjitter {jitter : %s}',
            trans('app.command.set_jitter.jitter')
        );
    }

    public function description(): string
    {
        return trans('app.command.set_jitter.description');
    }

    public function handle(StringInput $input): void
    {
        /** @var Room $room */
        $room = $input->getArgument('room');

        $room->update([
            'jitter' => $input->getArgument('jitter')
        ]);

        $this->bot->reply(
            trans('app.command.set_jitter.updated')
        );
    }

    public function argsRules(): array
    {
        return [
            'jitter' => ['required', 'numeric', 'min:0', 'max:1000'],
        ];
    }
}
