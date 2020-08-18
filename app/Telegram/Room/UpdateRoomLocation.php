<?php

namespace App\Telegram\Room;

use App\Infrastructure\Telegram\ManagerCommand;
use App\Infrastructure\Telegram\StringInput;
use App\Models\Room;
use App\Validation\Rules\Latitude;
use App\Validation\Rules\Longitude;
use MStaack\LaravelPostgis\Geometries\Point;

class UpdateRoomLocation extends ManagerCommand
{
    public function signature(): string
    {
        return '/setlocation {lat : Latitude} {lng : Longitude}';
    }

    public function description(): string
    {
        return trans('app.command.update_room_location.description');
    }

    public function handle(StringInput $input): void
    {
        /** @var Room $room */
        $room = $input->getArgument('room');

        $room->update([
            'location' => new Point(
                $input->getArgument('lat'),
                $input->getArgument('lng')
            ),
        ]);

        $this->bot->reply(
            trans('app.command.update_room_location.updated')
        );
    }

    public function argsRules(): array
    {
        return [
            'lat' => new Latitude(),
            'lng' => new Longitude()
        ];
    }
}

