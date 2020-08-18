<?php

namespace App\Telegram\Room;

use App\Http\Resources\RoomPointResource;
use App\Infrastructure\Telegram\ManagerCommand;
use App\Infrastructure\Telegram\StringInput;
use App\Models\Room;
use Illuminate\Support\Facades\Storage;

class DownloadGeoJson extends ManagerCommand
{
    public function signature(): string
    {
        return '/dlgeojson';
    }

    public function description(): string
    {
        return trans('app.command.download_points.description');
    }

    public function handle(StringInput $input): void
    {
        /** @var Room $room */
        $room = $input->getArgument('room');

        $data = [
            'type' => 'FeatureCollection',
            'features' => RoomPointResource::collection(
                Room\Point::getForRoom($room)
            )->toArray(null),
        ];

        $storage = Storage::cloud();
        $filename = $room->uuid . '.json';
        $storage->put($filename, json_encode($data));

        $sharedLinks = $storage->getAdapter()->getClient()->listSharedLinks($filename);
        if (empty($sharedLinks)) {
            $sharedData = $storage->getAdapter()->createSharedLinkWithSettings($filename, [
                'requested_visibility' => 'public',
                'audience' => 'public',
            ]);
        } else {
            $sharedData = $sharedLinks[0];
        }

        $this->bot->reply(
            trans('app.command.download_points.result', ['link' => $sharedData['url']])
        );
    }
}
