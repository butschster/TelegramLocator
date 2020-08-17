<?php

namespace App\Telegram\Manager;

use App\Infrastructure\Telegram\Command;
use App\Infrastructure\Telegram\StringInput;
use App\Models\Room;
use App\Validation\Rules\Latitude;
use App\Validation\Rules\Longitude;
use MStaack\LaravelPostgis\Geometries\Point;

class SearchNearestRoms extends Command
{
    public function signature(): string
    {
        return '/near {lat : Latitude} {lng : Longitude}';
    }

    public function description(): string
    {
        return 'Search public rooms in 1km radius near by coordinates';
    }

    public function handle(StringInput $input): void
    {
        $rooms = Room::nearest(new Point(
            $input->getArgument('lat'),
            $input->getArgument('lng')
        ));

        if ($rooms->isEmpty()) {
            $this->bot->reply('Nothing found near you.');
            return;
        }

        $result = sprintf("We found [%d] public rooms near by you.\n\n", $rooms->count());

        foreach ($rooms as $room) {
            if (!empty($room->title)) {
                $result .= "Title: *{$room->title}*\n";
                $result .= "Bot: @{$room->title}\n";
            } else {
                $result .= "Bot: *@{$room->name}*\n";
            }

            if (!empty($room->descrption)) {
                $result .= "Description: {$room->descrption}\n";
            }

            $result .= "\n\n";
        }

        $this->bot->reply($result);
    }

    public function argsRules(): array
    {
        return [
            'lat' => new Latitude(),
            'lng' => new Longitude()
        ];
    }
}
