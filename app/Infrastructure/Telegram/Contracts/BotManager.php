<?php

namespace App\Infrastructure\Telegram\Contracts;

use App\Models\Room;
use App\Models\User;
use GuzzleHttp\Exception\GuzzleException;

interface BotManager
{
    /**
     * Создание бота для менеджера
     *
     * @return Bot
     */
    public function forManager(): Bot;

    /**
     * Создание бота для комнаты
     *
     * @param Room $room
     * @return Bot
     */
    public function forRoom(Room $room): Bot;

    /**
     * Регистрация вебхука для комнаты
     * @param Room $room
     * @return string
     * @throws GuzzleException
     */
    public function registerWebhookForRoom(Room $room): string;

    /**
     * Регистрация вебхука для менеджера
     * @throws GuzzleException
     */
    public function registerWebhookForManager(): string;
}
