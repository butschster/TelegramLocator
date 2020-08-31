<?php

namespace App\Telegram\Room;

use App\Infrastructure\Telegram\Command;
use App\Infrastructure\Telegram\StringInput;
use App\Models\Room;
use Carbon\Carbon;
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
        return trans('app.command.get_info.description');
    }

    public function handle(StringInput $input): void
    {
        /** @var Room $room */
        $room = $input->getArgument('room');
        $this->checkAuthentication($room);

        $information = Cache::remember(
            'room:info:' . $room->uuid . ':' . app()->getLocale(),
            now()->addMinute(), function () use ($room) {
                return $this->getInformation($room);
            }
        );

        if ($room->hasAccess($this->getUser()) && Gate::allows('show', $room)) {
            $information['points_geojson_url'] = route('room.points', $room);
            $information['points_map_url'] = route('map', $room);
        }

        $this->bot->reply(
            collect($information)->map(function ($value, $key) {
                $key = trans('app.command.get_info.field.' . $key);

                return "{$key}: *{$value}*";
            })->implode("\n")
        );
    }

    protected function getInformation(Room $room): array
    {
        return [
            'title' => $room->title,

            'description' => $room->description,

            'total_points' => Room\Point::getForRoom($room)->count(),

            'points_lifetime' => $room->points_lifetime > 0
                ? trans('app.command.get_info.value.points_lifetime', ['hours' => $room->points_lifetime])
                : trans('app.command.get_info.value.points_lifetime_infinitely'),

            'points_noise' => trans('app.command.get_info.value.points_noise', ['jitter' => $room->jitter]),

            'anonymous' => $room->is_anonymous
                ? trans('app.command.get_info.value.yes')
                : trans('app.command.get_info.value.no'),

            'public' => $room->is_public
                ? trans('app.command.get_info.value.yes')
                : trans('app.command.get_info.value.no'),

            'password_required' => $room->hasPassword()
                ? trans('app.command.get_info.value.yes')
                : trans('app.command.get_info.value.no'),

            'last_activity' => $room->lastActivity()
        ];
    }
}
