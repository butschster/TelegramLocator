<?php

namespace App\Infrastructure\Telegram\Contracts;

use App\Models\Room;
use App\Models\User;

interface BotManager
{
    /**
     * Получение телеграм бота для менеджеров
     *
     * @return Bot
     */
    public function forManager(): Bot;

    /**
     * Получение телеграм бота для комнаты
     *
     * @param Room $room
     * @return Bot
     */
    public function forRoom(Room $room): Bot;

    /**
     * Регистрация телеграм бота для комнаты
     * @param Room $room
     * @return string
     */
    public function registerWebhookForRoom(Room $room): string;

    /**
     * Регистрация телеграм бота для менеджеров
     * @return string
     */
    public function registerWebhookForManager(): string;
}
