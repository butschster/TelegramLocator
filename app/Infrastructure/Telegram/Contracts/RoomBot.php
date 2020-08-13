<?php

namespace App\Infrastructure\Telegram\Contracts;

interface RoomBot
{
    public function handleCommand(): void;
}
