<?php

namespace App\Infrastructure\Telegram\Contracts;

interface Bot
{
    /**
     * Регистрация команд, которые будут доступны боту
     */
    public function handleCommand(): void;
}
