<?php

namespace App\Http\Controllers;

use App\Http\Resources\RoomPointResource;
use App\Infrastructure\Telegram\Contracts\BotManager;
use App\Models\Room;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TelegramController extends Controller
{
    /**
     * Получение списка точек за последние 24 часа в формате GeoJSON
     * @param Request $request
     * @param Room $room
     * @return array
     * @throws AuthorizationException
     */
    public function getPointsForLastDay(Request $request, Room $room)
    {
        $this->authorize('show', $room);

        return Cache::remember(
            'points:' . $room->uuid,
            now()->addMinute(),
            function () use ($room, $request) {
                $response = [
                    'type' => 'FeatureCollection',
                    'features' => RoomPointResource::collection(
                        Room\Point::getForRoom($room)
                    )->toArray($request),
                ];

                if ($room->location) {
                    $response['center'] = [$room->location->getLng(), $room->location->getLat()];
                }

                return $response;
            });
    }

    /**
     * Просмотр карты с метками
     * @param Room $room
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws AuthorizationException
     */
    public function map(Room $room)
    {
        $this->authorize('show', $room);

        return view('map', [
            'token' => config('services.mapbox.token'),
            'center' => $room->location ? [$room->location->getLat(), $room->location->getLng()] : [],
            'apiUrl' => route('room.points', $room)
        ]);
    }

    /**
     * Обработка вебхуков для комнаты
     * @param BotManager $bots
     * @param Room $room
     * @return string
     */
    public function roomWebhook(BotManager $bots, Room $room)
    {
        $bots->forRoom($room)->handleCommand();

        return 'OK';
    }

    /**
     * Обработка вебхуков для менеджера
     * @param BotManager $bots
     * @return string
     */
    public function managerWebhook(BotManager $bots)
    {
        $bots->forManager()->handleCommand();

        return 'OK';
    }
}
