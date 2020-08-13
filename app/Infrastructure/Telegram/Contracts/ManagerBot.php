<?php

namespace App\Infrastructure\Telegram\Contracts;

interface ManagerBot
{
    public function handleCommand(): void;
}
