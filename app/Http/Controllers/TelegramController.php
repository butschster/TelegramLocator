<?php

namespace App\Http\Controllers;

use App\Http\Resources\RoomPointResource;
use App\Infrastructure\Telegram\Contracts\BotManager;
use App\Models\Room;

class TelegramController extends Controller
{
    /**
     * Получение списка точек за последние 24 часа
     * @param Room $room
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getPointsForLastDay(Room $room)
    {
        return RoomPointResource::collection(
            Room\Point::getForRoom($room)
        );
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

        return 'ok';
    }

    /**
     * Обработка вебхуков для менеджера
     * @param BotManager $bots
     * @return string
     */
    public function managerWebhook(BotManager $bots)
    {
        $bots->forManager()->handleCommand();

        return 'ok';
    }
}
