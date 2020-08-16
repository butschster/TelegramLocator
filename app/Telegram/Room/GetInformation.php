<?php

namespace App\Telegram\Room;

use App\Infrastructure\Telegram\Command;
use App\Infrastructure\Telegram\StringInput;
use App\Models\Room;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;

class GetInformation extends Command
{
    public function signature(): string
    {
        return '/info';
    }

    public function description(): string
    {
        return 'Get information about room';
    }

    public function handle(StringInput $input): void
    {
        /** @var Room $room */
        $room = $input->getArgument('room');
        $this->checkAuthentication($room);

        $information = Cache::remember('room:info:' . $room->uuid, now()->addMinute(), function () use ($room) {
            return $this->getInformation($room);
        });

        $this->bot->reply($information);
    }

    protected function getInformation(Room $room): string
    {
        $rows = [
            'ID' => $room->uuid,
            'Title' => $room->title,
            'Description' => $room->description,
            'Total points' => Room\Point::getForRoom($room)->count(),
            'Anonymous' => $room->is_anonymous ? 'Yes' : 'No',
            'Public' => $room->is_public ? 'Yes' : 'No',
            'Password required' => $room->hasPassword() ? 'Yes' : 'No',
            'Last activity' => $room->lastActivity()
        ];

        if ($room->isOwner($this->getUser()) && Gate::allows('show', $room)) {
            $rows['Points GeoJson'] = route('room.points', $room);
            $rows['Points map'] = route('map', $room);
        }

        return collect($rows)->map(function ($value, $key) {
            return "{$key}: *{$value}*";
        })->implode("\n");
    }
}
