<?php

namespace App\Telegram\Room;

use App\Infrastructure\Telegram\ManagerCommand;
use App\Infrastructure\Telegram\StringInput;
use App\Models\Room;

class SetPointsLifeTime extends ManagerCommand
{
    public function signature(): string
    {
        return '/setpointslifetime {hours : Lifetime in hours. (0 - infinitely. Max 87600 - 10 years.)}';
    }

    public function description(): string
    {
        return 'Set room points lifetime.';
    }

    public function handle(StringInput $input): void
    {
        /** @var Room $room */
        $room = $input->getArgument('room');

        $room->update([
            'points_lifetime' => $input->getArgument('hours')
        ]);

        $this->bot->reply('Points lifetime changed.');
    }

    public function argsRules(): array
    {
        return [
            'hours' => ['digits_between:0,87600'],
        ];
    }
}
