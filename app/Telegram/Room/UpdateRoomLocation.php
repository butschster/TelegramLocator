<?php

namespace App\Telegram\Room;

use App\Infrastructure\Telegram\ManagerCommand;
use App\Infrastructure\Telegram\StringInput;
use App\Models\Room;
use MStaack\LaravelPostgis\Geometries\Point;

class UpdateRoomLocation extends ManagerCommand
{
    public function signature(): string
    {
        return '/setlocation {lat : Latitude} {lon : Longitude}';
    }

    public function description(): string
    {
        return 'Update room location';
    }

    public function handle(StringInput $input): void
    {
        /** @var Room $room */
        $room = $input->getArgument('room');

        $room->update([
            'location' => new Point(
                $input->getArgument('lat'),
                $input->getArgument('lon')
            ),
        ]);

        $this->bot->reply('Room location updated.');
    }
}

