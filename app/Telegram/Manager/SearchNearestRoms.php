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
        return trans('app.command.search_nearest_room.description');
    }

    public function handle(StringInput $input): void
    {
        $rooms = Room::nearest(new Point(
            $input->getArgument('lat'),
            $input->getArgument('lng')
        ));

        if ($rooms->isEmpty()) {
            $this->bot->reply(
                trans('app.command.search_nearest_room.nothing_found')
            );
            return;
        }

        $result = sprintf("%s\n\n", trans('app.command.search_nearest_room.found_rooms', ['total' => $rooms->count()]));

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
