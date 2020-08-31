<?php

namespace App\Telegram\Room;

use App\Infrastructure\Telegram\StringInput;
use App\Models\Room;

class SetPointsLifeTime extends ManagerCommand
{
    public function signature(): string
    {
        return sprintf(
            '/setpointslifetime {hours : %s}',
            trans('app.command.set_points_lifetime.hours')
        );
    }

    public function description(): string
    {
        return trans('app.command.set_points_lifetime.description');
    }

    public function handle(StringInput $input): void
    {
        /** @var Room $room */
        $room = $input->getArgument('room');

        $room->update([
            'points_lifetime' => $input->getArgument('hours')
        ]);

        $this->bot->reply(
            trans('app.command.set_points_lifetime.updated')
        );
    }

    public function argsRules(): array
    {
        return [
            'hours' => ['digits_between:0,87600'],
        ];
    }
}
