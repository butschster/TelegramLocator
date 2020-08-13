<?php

namespace App\Infrastructure\Telegram\Contracts;

use App\Models\Room;
use App\Models\User;

interface BotManager
{
    /**
     * Получение телеграм бота для менеджеров
     *
     * @return ManagerBot
     */
    public function forManager(): ManagerBot;

    /**
     * Получение телеграм бота для комнаты
     *
     * @param Room $room
     * @return RoomBot
     */
    public function forRoom(Room $room): RoomBot;

    /**
     * Регистрация телеграм бота для комнаты
     * @param Room $room
     */
    public function registerWebhookForRoom(Room $room): void;

    /**
     * Регистрация телеграм бота для менеджеров
     */
    public function registerWebhookForManager(): void;
}
